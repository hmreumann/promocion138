<?php

use App\Models\Invoice;
use App\Models\User;

it('can display invoice page with payment instructions', function () {
    $user = User::factory()->create([
        'payment_method' => 'smsv',
        'plan' => 'full',
        'active' => true,
    ]);

    $invoice = Invoice::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
        'amount' => 27000.00,
    ]);

    $this->actingAs($user);

    $response = $this->get(route('invoices.show', $invoice));

    $response->assertStatus(200);
    $response->assertSee($user->name);
    $response->assertSee('$27.000');
    $response->assertSee('3220001805000013390012'); // CBU
    $response->assertSee('1054747110'); // Reference number
    $response->assertSee('Subir Comprobante');
});

it('shows receipt uploaded message when invoice has receipt', function () {
    $user = User::factory()->create([
        'payment_method' => 'smsv',
        'plan' => 'full',
        'active' => true,
    ]);

    $invoice = Invoice::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
        'receipt_path' => 'receipts/test-receipt.jpg',
    ]);

    $this->actingAs($user);

    $response = $this->get(route('invoices.show', $invoice));

    $response->assertStatus(200);
    $response->assertSee('Comprobante subido');
    $response->assertDontSee('type="file"'); // Check for file input instead
});

it('shows paid status when invoice is paid', function () {
    $user = User::factory()->create([
        'payment_method' => 'smsv',
        'plan' => 'full',
        'active' => true,
    ]);

    $invoice = Invoice::factory()->paid()->create([
        'user_id' => $user->id,
    ]);

    $this->actingAs($user);
    $response = $this->get(route('invoices.show', $invoice));

    $response->assertStatus(200);
    $response->assertSee('Factura Pagada');
    $response->assertDontSee('Instrucciones de Pago');
});
