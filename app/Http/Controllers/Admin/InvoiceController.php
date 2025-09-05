<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Invoice::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        // Get all filtered invoices for stats and charts (not paginated)
        $allFilteredInvoices = $query->orderBy('created_at', 'desc')->get();

        // Get paginated invoices for the table
        $invoices = $query->orderBy('created_at', 'desc')->paginate(20);
        $users = User::orderBy('name')->get();

        // Prepare waiting review data for clipboard functionality (from all filtered invoices)
        $waitingReviewPayments = $allFilteredInvoices->where('status', 'waiting_review')->map(function ($invoice) {
            return [
                'name' => $invoice->user->name,
                'amount' => $invoice->amount,
                'paid_at' => $invoice->paid_at ? $invoice->paid_at->format('M d, Y') : 'N/A',
            ];
        })->values();

        // Prepare overdue invoices histogram data (from all filtered invoices)
        // First, get all users who have invoices in the filtered set
        $allUsersInFilter = $allFilteredInvoices->pluck('user_id')->unique();

        // Count overdue invoices per user (including users with 0 overdue)
        $overdueByUser = $allUsersInFilter->mapWithKeys(function ($userId) use ($allFilteredInvoices) {
            $userOverdueCount = $allFilteredInvoices
                ->where('user_id', $userId)
                ->filter(function ($invoice) {
                    return $invoice->isOverdue() && ! $invoice->isWaitingReview();
                })
                ->count();

            return [$userId => $userOverdueCount];
        });

        // Find the maximum count to ensure we have all bins
        $maxCount = $overdueByUser->count() > 0 ? $overdueByUser->max() : 0;

        // Create histogram bins (0 to max count)
        $histogramData = collect();
        for ($i = 0; $i <= $maxCount; $i++) {
            $usersWithThisCount = $overdueByUser->filter(function ($count) use ($i) {
                return $count === $i;
            })->count();

            $histogramData->push([
                'overdue_count' => $i,
                'user_count' => $usersWithThisCount,
            ]);
        }

        return view('admin.invoices.index', compact('invoices', 'allFilteredInvoices', 'users', 'waitingReviewPayments', 'histogramData'));
    }

    public function markAsPaid(Invoice $invoice): RedirectResponse
    {
        $invoice->markAsPaid();

        return redirect()->back()->with('success', 'Invoice marked as paid successfully.');
    }

    public function markAsPending(Invoice $invoice): RedirectResponse
    {
        $invoice->update([
            'status' => 'pending',
            'paid_at' => null,
        ]);

        return redirect()->back()->with('success', 'Invoice marked as pending successfully.');
    }

    public function markAsWaitingReview(Invoice $invoice): RedirectResponse
    {
        $invoice->markAsWaitingReview();

        return redirect()->back()->with('success', 'Invoice marked as waiting review successfully.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();

        return redirect()->back()->with('success', 'Invoice deleted successfully.');
    }
}
