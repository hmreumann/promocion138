<?php

use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;

it('can create an invoice with all required fields', function () {
    $user = User::factory()->create();

    $invoice = Invoice::create([
        'user_id' => $user->id,
        'amount' => 27000.00,
        'invoice_date' => '2025-08-01',
        'due_date' => '2025-08-31',
        'status' => 'pending',
        'billing_period' => '2025-08',
        'description' => 'Test invoice',
    ]);

    expect($invoice)->toBeInstanceOf(Invoice::class);
    expect($invoice->user_id)->toBe($user->id);
    expect($invoice->amount)->toBe('27000.00');
    expect($invoice->status)->toBe('pending');
    expect($invoice->billing_period)->toBe('2025-08');
});

it('belongs to a user', function () {
    $user = User::factory()->create();
    $invoice = Invoice::factory()->create(['user_id' => $user->id]);

    expect($invoice->user)->toBeInstanceOf(User::class);
    expect($invoice->user->id)->toBe($user->id);
});

it('can mark invoice as paid', function () {
    $invoice = Invoice::factory()->create(['status' => 'pending']);

    expect($invoice->status)->toBe('pending');
    expect($invoice->paid_at)->toBeNull();

    $invoice->markAsPaid();

    $invoice->refresh();
    expect($invoice->status)->toBe('paid');
    expect($invoice->paid_at)->not->toBeNull();
});

it('can check if invoice is overdue', function () {
    // Create overdue invoice
    $overdueInvoice = Invoice::factory()->create([
        'status' => 'pending',
        'due_date' => Carbon::yesterday(),
    ]);

    // Create current invoice
    $currentInvoice = Invoice::factory()->create([
        'status' => 'pending',
        'due_date' => Carbon::tomorrow(),
    ]);

    // Create paid invoice (should not be overdue even if past due date)
    $paidInvoice = Invoice::factory()->create([
        'status' => 'paid',
        'due_date' => Carbon::yesterday(),
    ]);

    expect($overdueInvoice->isOverdue())->toBeTrue();
    expect($currentInvoice->isOverdue())->toBeFalse();
    expect($paidInvoice->isOverdue())->toBeFalse();
});

it('casts dates correctly', function () {
    $invoice = Invoice::factory()->create([
        'invoice_date' => '2025-08-01',
        'due_date' => '2025-08-31',
        'paid_at' => '2025-08-15 10:30:00',
    ]);

    expect($invoice->invoice_date)->toBeInstanceOf(Carbon::class);
    expect($invoice->due_date)->toBeInstanceOf(Carbon::class);
    expect($invoice->paid_at)->toBeInstanceOf(Carbon::class);
});

it('casts amount to decimal', function () {
    $invoice = Invoice::factory()->create(['amount' => 27000]);

    expect($invoice->amount)->toBe('27000.00');
});

it('has default pending status', function () {
    $invoice = new Invoice([
        'user_id' => 1,
        'amount' => 27000.00,
        'invoice_date' => '2025-08-01',
        'due_date' => '2025-08-31',
        'billing_period' => '2025-08',
    ]);

    expect($invoice->status)->toBe('pending');
});
