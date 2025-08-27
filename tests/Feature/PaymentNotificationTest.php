<?php

use App\Mail\PaymentNotification;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

it('sends a notification when a receipt is uploaded for a pending invoice', function () {
    Mail::fake();
    Storage::fake('public');

    $user = User::factory()->create([
        'payment_method' => 'smsv',
        'plan' => 'full',
        'active' => true,
    ]);

    $invoice = Invoice::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
        'receipt_path' => null,
    ]);

    $file = UploadedFile::fake()->image('receipt.jpg');

    $this->actingAs($user);

    $response = $this->post(route('invoices.upload-receipt', $invoice), [
        'receipt' => $file,
    ]);

    $response->assertRedirect();

    // check that file got stored
    Storage::disk('public')->assertExists($invoice->fresh()->receipt_path);

    // check that invoice got updated
    $this->assertNotNull($invoice->fresh()->receipt_path);

    // check that mail was sent to the user and the two extra recipients
    Mail::assertSent(PaymentNotification::class, function ($mail) use ($invoice, $user) {
        return $mail->hasTo($user->email)
            && $mail->hasTo('cuentascorrientesrrbb@smsv.com.ar')
            && $mail->hasTo('promociones@smsv.com.ar')
            && $mail->invoice->is($invoice);
    });
});
