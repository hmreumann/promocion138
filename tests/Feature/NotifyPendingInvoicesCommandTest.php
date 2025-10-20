<?php

use App\Mail\PendingInvoicesNotification;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();
});

it('sends notifications to active users with pending invoices on notification days', function () {
    // Create active user with pending invoice
    $activeUser = User::factory()->create([
        'active' => true,
        'name' => 'Active User',
        'email' => 'active@example.com',
    ]);

    Invoice::factory()->create([
        'user_id' => $activeUser->id,
        'status' => 'pending',
    ]);

    // Mock today as day 1 (a notification day)
    $this->travelTo(now()->startOfMonth()->addDay(0)); // Day 1

    $this->artisan('invoices:notify-pending')
        ->expectsOutput('Notification sent to Active User (active@example.com) - 1 pending invoice(s)')
        ->assertSuccessful();

    Mail::assertSent(PendingInvoicesNotification::class, function ($mail) use ($activeUser) {
        return $mail->hasTo($activeUser->email);
    });
});

it('does not send notifications to inactive users with pending invoices', function () {
    // Create inactive user with pending invoice
    $inactiveUser = User::factory()->create([
        'active' => false,
        'name' => 'Inactive User',
        'email' => 'inactive@example.com',
    ]);

    Invoice::factory()->create([
        'user_id' => $inactiveUser->id,
        'status' => 'pending',
    ]);

    // Create active user with pending invoice
    $activeUser = User::factory()->create([
        'active' => true,
        'name' => 'Active User',
        'email' => 'active@example.com',
    ]);

    Invoice::factory()->create([
        'user_id' => $activeUser->id,
        'status' => 'pending',
    ]);

    // Mock today as day 1 (a notification day)
    $this->travelTo(now()->startOfMonth()->addDay(0)); // Day 1

    $this->artisan('invoices:notify-pending')
        ->assertSuccessful();

    // Should only send to active user
    Mail::assertSent(PendingInvoicesNotification::class, 1);

    Mail::assertSent(PendingInvoicesNotification::class, function ($mail) use ($activeUser) {
        return $mail->hasTo($activeUser->email);
    });

    Mail::assertNotSent(PendingInvoicesNotification::class, function ($mail) use ($inactiveUser) {
        return $mail->hasTo($inactiveUser->email);
    });
});

it('does not send notifications on non-notification days', function () {
    $user = User::factory()->create([
        'active' => true,
    ]);

    Invoice::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
    ]);

    // Mock today as day 5 (not a notification day)
    $this->travelTo(now()->startOfMonth()->addDays(4)); // Day 5

    $this->artisan('invoices:notify-pending')
        ->expectsOutput('Today is day 5. Notifications only sent on days 1, 8, 16, and 24.')
        ->assertSuccessful();

    Mail::assertNotSent(PendingInvoicesNotification::class);
});

it('sends notifications on day 8', function () {
    $user = User::factory()->create([
        'active' => true,
    ]);

    Invoice::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
    ]);

    $this->travelTo(now()->startOfMonth()->addDays(7)); // Day 8

    $this->artisan('invoices:notify-pending')
        ->assertSuccessful();

    Mail::assertSent(PendingInvoicesNotification::class, 1);
});

it('sends notifications on day 16', function () {
    $user = User::factory()->create([
        'active' => true,
    ]);

    Invoice::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
    ]);

    $this->travelTo(now()->startOfMonth()->addDays(15)); // Day 16

    $this->artisan('invoices:notify-pending')
        ->assertSuccessful();

    Mail::assertSent(PendingInvoicesNotification::class, 1);
});

it('sends notifications on day 24', function () {
    $user = User::factory()->create([
        'active' => true,
    ]);

    Invoice::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
    ]);

    $this->travelTo(now()->startOfMonth()->addDays(23)); // Day 24

    $this->artisan('invoices:notify-pending')
        ->assertSuccessful();

    Mail::assertSent(PendingInvoicesNotification::class, 1);
});

it('can force send notifications on any day', function () {
    $user = User::factory()->create([
        'active' => true,
    ]);

    Invoice::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
    ]);

    // Mock today as day 10 (not a notification day)
    $this->travelTo(now()->startOfMonth()->addDays(9)); // Day 10

    $this->artisan('invoices:notify-pending', ['--force' => true])
        ->assertSuccessful();

    Mail::assertSent(PendingInvoicesNotification::class, 1);
});

it('does not send notifications to users without pending invoices', function () {
    $user = User::factory()->create([
        'active' => true,
    ]);

    // Create paid invoice (not pending)
    Invoice::factory()->paid()->create([
        'user_id' => $user->id,
    ]);

    $this->travelTo(now()->startOfMonth()->addDay(0)); // Day 1

    $this->artisan('invoices:notify-pending')
        ->expectsOutput('No users with pending invoices found.')
        ->assertSuccessful();

    Mail::assertNotSent(PendingInvoicesNotification::class);
});

it('sends notifications to multiple active users with pending invoices', function () {
    $user1 = User::factory()->create([
        'active' => true,
        'name' => 'User One',
    ]);

    $user2 = User::factory()->create([
        'active' => true,
        'name' => 'User Two',
    ]);

    Invoice::factory()->create([
        'user_id' => $user1->id,
        'status' => 'pending',
    ]);

    Invoice::factory()->create([
        'user_id' => $user2->id,
        'status' => 'pending',
    ]);

    $this->travelTo(now()->startOfMonth()->addDay(0)); // Day 1

    $this->artisan('invoices:notify-pending')
        ->expectsOutput('✅ Notifications sent: 2')
        ->assertSuccessful();

    Mail::assertSent(PendingInvoicesNotification::class, 2);
});
