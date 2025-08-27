<?php

namespace App\Http\Controllers;

use App\Mail\PaymentNotification;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = auth()->user()->invoices()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }

    public function uploadReceipt(Request $request, Invoice $invoice)
    {
        $request->validate([
            'receipt' => 'required|file|mimes:jpeg,jpg,png,pdf|max:10240', // 10MB max
        ]);

        if ($invoice->status !== 'pending') {
            return back()->with('error', 'No se puede subir comprobante para una factura que no está pendiente.');
        }

        // Store the receipt file
        $receiptPath = $request->file('receipt')->store('receipts', 'public');

        // Update invoice with receipt path (we'll need to add this field to migration)
        $invoice->update([
            'status' => 'waiting_review',
            'paid_at' => now(),
            'receipt_path' => $receiptPath,
        ]);

        Mail::to($invoice->user)
            ->cc([
                'cuentascorrientesrrbb@smsv.com.ar',
                'promociones@smsv.com.ar',
            ])
            ->send(new PaymentNotification($invoice));

        return back()->with('success', 'Comprobante subido exitosamente. Procesaremos su pago en breve.');
    }

    public function showReceipt(Invoice $invoice)
    {
        if (! $invoice->receipt_path) {
            abort(404, 'No receipt found for this invoice.');
        }

        $path = storage_path('app/public/'.$invoice->receipt_path);

        if (! file_exists($path)) {
            abort(404, 'Receipt file not found.');
        }

        return response()->file($path);
    }
}
