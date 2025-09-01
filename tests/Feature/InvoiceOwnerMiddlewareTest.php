<?php

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('owner can view their invoice', function () {
    $user = User::factory()->create();
    $invoice = Invoice::factory()->for($user)->create();

    $this->actingAs($user)
        ->get(route('invoices.show', $invoice))
        ->assertStatus(200);
});

test('non-owner cannot view invoice', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $invoice = Invoice::factory()->for($owner)->create();

    $this->actingAs($otherUser)
        ->get(route('invoices.show', $invoice))
        ->assertStatus(403);
});

test('owner can upload receipt to their invoice', function () {
    Storage::fake('public');
    
    $user = User::factory()->create();
    $invoice = Invoice::factory()->pending()->for($user)->create();
    $file = UploadedFile::fake()->image('receipt.jpg');

    $this->actingAs($user)
        ->post(route('invoices.upload-receipt', $invoice), [
            'receipt' => $file,
        ])
        ->assertRedirect()
        ->assertSessionHas('success');
        
    $this->assertDatabaseHas('invoices', [
        'id' => $invoice->id,
        'status' => 'waiting_review',
    ]);
});

test('non-owner cannot upload receipt to invoice', function () {
    Storage::fake('public');
    
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $invoice = Invoice::factory()->pending()->for($owner)->create();
    $file = UploadedFile::fake()->image('receipt.jpg');

    $this->actingAs($otherUser)
        ->post(route('invoices.upload-receipt', $invoice), [
            'receipt' => $file,
        ])
        ->assertStatus(403);
        
    $this->assertDatabaseHas('invoices', [
        'id' => $invoice->id,
        'status' => 'pending',
    ]);
});
