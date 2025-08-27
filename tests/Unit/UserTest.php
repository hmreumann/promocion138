<?php

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('has many invoices', function () {
    $user = User::factory()->create();
    $invoice1 = Invoice::factory()->create([
        'user_id' => $user->id,
        'billing_period' => '2025-01',
    ]);
    $invoice2 = Invoice::factory()->create([
        'user_id' => $user->id,
        'billing_period' => '2025-02',
    ]);

    expect($user->invoices)->toHaveCount(2);
    expect($user->invoices->first())->toBeInstanceOf(Invoice::class);
});

it('can scope users with SMSV payment method', function () {
    // Create users with different payment methods and statuses
    $smsvActiveUser = User::factory()->create([
        'active' => true,
        'payment_method' => 'smsv',
    ]);

    $smsvInactiveUser = User::factory()->create([
        'active' => false,
        'payment_method' => 'smsv',
    ]);

    $bankActiveUser = User::factory()->create([
        'active' => true,
        'payment_method' => 'bank',
    ]);

    $smsvUsers = User::withSmsv()->get();

    expect($smsvUsers)->toHaveCount(1);
    expect($smsvUsers->first()->id)->toBe($smsvActiveUser->id);
});

it('casts active field to boolean', function () {
    $user = User::factory()->create(['active' => 1]);

    expect($user->active)->toBeTrue();
    expect($user->active)->toBeBool();
});

it('can create user with all fillable fields', function () {
    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
        'active' => true,
        'payment_method' => 'smsv',
        'plan' => 'full',
    ];

    $user = User::create($userData);

    expect($user->name)->toBe('Test User');
    expect($user->email)->toBe('test@example.com');
    expect($user->active)->toBeTrue();
    expect($user->payment_method)->toBe('smsv');
    expect($user->plan)->toBe('full');
});
