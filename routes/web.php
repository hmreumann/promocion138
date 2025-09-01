<?php

use App\Http\Controllers\Admin\InvoiceController as AdminInvoiceController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', fn () => to_route('invoices.index'))
        ->name('dashboard');

    Route::view('profile', 'profile')
        ->name('profile');

    Route::get('invoices', [InvoiceController::class, 'index'])
        ->name('invoices.index');

    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])
        ->middleware('invoice.owner')
        ->name('invoices.show');

    Route::post('invoices/{invoice}/upload-receipt', [InvoiceController::class, 'uploadReceipt'])
        ->middleware('invoice.owner')
        ->name('invoices.upload-receipt');

    Route::get('invoices/{invoice}/receipt', [InvoiceController::class, 'showReceipt'])
        ->middleware('invoice.owner')
        ->name('invoices.show-receipt');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('invoices', [AdminInvoiceController::class, 'index'])
        ->name('invoices.index');

    Route::patch('invoices/{invoice}/mark-paid', [AdminInvoiceController::class, 'markAsPaid'])
        ->name('invoices.mark-paid');

    Route::patch('invoices/{invoice}/mark-pending', [AdminInvoiceController::class, 'markAsPending'])
        ->name('invoices.mark-pending');

    Route::delete('invoices/{invoice}', [AdminInvoiceController::class, 'destroy'])
        ->name('invoices.destroy');
});

require __DIR__.'/auth.php';
