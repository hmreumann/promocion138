<?php

use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;

beforeEach(function () {
    // Create test users with different payment methods and plans
    $this->transferFullUser = User::factory()->create([
        'name' => 'Juan Perez',
        'email' => 'juan@example.com',
        'active' => true,
        'payment_method' => 'transfer',
        'plan' => 'full',
    ]);

    $this->transferBasicUser = User::factory()->create([
        'name' => 'Maria Garcia',
        'email' => 'maria@example.com',
        'active' => true,
        'payment_method' => 'transfer',
        'plan' => 'basic',
    ]);

    $this->inactiveUser = User::factory()->create([
        'name' => 'Carlos Inactive',
        'email' => 'carlos@example.com',
        'active' => false,
        'payment_method' => 'transfer',
        'plan' => 'full',
    ]);

    $this->nonTransferUser = User::factory()->create([
        'name' => 'Ana Rodriguez',
        'email' => 'ana@example.com',
        'active' => true,
        'payment_method' => 'smsv',
        'plan' => 'full',
    ]);
});

it('generates invoices for active transfer users', function () {
    $this->artisan('invoices:generate-monthly')
        ->expectsOutput('Generando facturas para el período: '.now()->format('Y-m'))
        ->expectsOutput('✅ Facturas creadas: 2')
        ->expectsOutput('📊 Total de miembros procesados: 2')
        ->assertExitCode(0);

    // Check that invoices were created for transfer users
    expect(Invoice::where('user_id', $this->transferFullUser->id)->count())->toBe(1);
    expect(Invoice::where('user_id', $this->transferBasicUser->id)->count())->toBe(1);

    // Check that no invoices were created for inactive or non-transfer users
    expect(Invoice::where('user_id', $this->inactiveUser->id)->count())->toBe(0);
    expect(Invoice::where('user_id', $this->nonTransferUser->id)->count())->toBe(0);
});

it('sets correct amounts based on plan type', function () {
    $this->artisan('invoices:generate-monthly');

    $fullPlanInvoice = Invoice::where('user_id', $this->transferFullUser->id)->first();
    $basicPlanInvoice = Invoice::where('user_id', $this->transferBasicUser->id)->first();

    expect($fullPlanInvoice->amount)->toBe('27000.00');
    expect($basicPlanInvoice->amount)->toBe('10800.00');
});

it('sets correct dates and billing period', function () {
    $this->artisan('invoices:generate-monthly');

    $invoice = Invoice::where('user_id', $this->transferFullUser->id)->first();
    $expectedDate = Carbon::now()->startOfMonth();
    $expectedDueDate = $expectedDate->copy()->addDays(10);
    $expectedPeriod = $expectedDate->format('Y-m');

    expect($invoice->invoice_date->toDateString())->toBe($expectedDate->toDateString());
    expect($invoice->due_date->toDateString())->toBe($expectedDueDate->toDateString());
    expect($invoice->billing_period)->toBe($expectedPeriod);
    expect($invoice->status)->toBe('pending');
});

it('prevents duplicate invoices for same billing period', function () {
    // Run command twice
    $this->artisan('invoices:generate-monthly');
    $this->artisan('invoices:generate-monthly')
        ->expectsOutput('✅ Facturas creadas: 0')
        ->expectsOutput('⚠️  Facturas omitidas (ya existen): 2')
        ->expectsOutput('📊 Total de miembros procesados: 2');

    // Should still have only one invoice per user
    expect(Invoice::where('user_id', $this->transferFullUser->id)->count())->toBe(1);
    expect(Invoice::where('user_id', $this->transferBasicUser->id)->count())->toBe(1);
});

it('generates invoices for specific month and year', function () {
    $this->artisan('invoices:generate-monthly', ['--month' => 12, '--year' => 2024]);

    $invoice = Invoice::where('user_id', $this->transferFullUser->id)->first();

    expect($invoice->billing_period)->toBe('2024-12');
    expect($invoice->invoice_date->toDateString())->toBe('2024-12-01');
});

it('handles case when no transfer users exist', function () {
    // Delete all users
    User::truncate();

    $this->artisan('invoices:generate-monthly')
        ->expectsOutput('No se encontraron usuarios activos con método de pago Transfer.')
        ->assertExitCode(0);

    expect(Invoice::count())->toBe(0);
});

it('generates correct description in Spanish', function () {
    Carbon::setTestNow('2025-08-01');

    $this->artisan('invoices:generate-monthly');

    $invoice = Invoice::where('user_id', $this->transferFullUser->id)->first();

    expect($invoice->description)->toBe('Cuota mensual Promoción 138 - August 2025');
});
