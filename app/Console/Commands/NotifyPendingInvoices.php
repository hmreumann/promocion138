<?php

namespace App\Console\Commands;

use App\Mail\PendingInvoicesNotification;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotifyPendingInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:notify-pending {--force : Send notifications regardless of the date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification emails to users with pending invoices on days 1, 8, 16, and 24 of each month';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $today = now();
        $dayOfMonth = $today->day;

        // Check if today is one of the notification days (1, 8, 16, 24) or if forced
        if (! $this->option('force') && ! in_array($dayOfMonth, [1, 8, 16, 24])) {
            $this->info("Today is day {$dayOfMonth}. Notifications only sent on days 1, 8, 16, and 24.");

            return self::SUCCESS;
        }

        // Get all active users with pending invoices
        $usersWithPendingInvoices = User::where('active', true)
            ->whereHas('invoices', function ($query) {
                $query->whereIn('status', ['pending']);
            })->with(['invoices' => function ($query) {
                $query->whereIn('status', ['pending'])
                    ->orderBy('due_date', 'asc');
            }])->get();

        if ($usersWithPendingInvoices->isEmpty()) {
            $this->info('No users with pending invoices found.');

            return self::SUCCESS;
        }

        $notificationsSent = 0;

        foreach ($usersWithPendingInvoices as $user) {
            if ($user->invoices->isNotEmpty()) {
                // Send notification email
                Mail::to($user)->send(new PendingInvoicesNotification($user, $user->invoices));
                sleep(1);

                $this->info("Notification sent to {$user->name} ({$user->email}) - {$user->invoices->count()} pending invoice(s)");
                $notificationsSent++;
            }
        }

        $this->info("✅ Notifications sent: {$notificationsSent}");
        $this->info("📊 Users processed: {$usersWithPendingInvoices->count()}");

        return self::SUCCESS;
    }
}
