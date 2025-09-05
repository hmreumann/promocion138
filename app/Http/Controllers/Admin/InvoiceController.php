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

        $invoices = $query->orderBy('created_at', 'desc')->paginate(20);
        $users = User::orderBy('name')->get();

        // Prepare waiting review data for clipboard functionality
        $waitingReviewPayments = $invoices->where('status', 'waiting_review')->map(function ($invoice) {
            return [
                'name' => $invoice->user->name,
                'amount' => $invoice->amount,
                'paid_at' => $invoice->paid_at ? $invoice->paid_at->format('M d, Y') : 'N/A',
            ];
        })->values();

        return view('admin.invoices.index', compact('invoices', 'users', 'waitingReviewPayments'));
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
