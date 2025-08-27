<?php

namespace App\Console\Commands;

use App\Mail\InvoiceCreated;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class GenerateMonthlyInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:generate-monthly {--month=} {--year=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly invoices for active transfer members';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $month = $this->option('month') ?: now()->month;
        $year = $this->option('year') ?: now()->year;

        $invoiceDate = Carbon::createFromDate($year, $month, 1);
        $billingPeriod = $invoiceDate->format('Y-m');

        $this->info("Generando facturas para el período: {$billingPeriod}");

        // Get all active users with SMSV payment method
        $users = User::withTransfer()->get();

        if ($users->isEmpty()) {
            $this->warn('No se encontraron usuarios activos con método de pago Transfer.');

            return self::SUCCESS;
        }

        $invoicesCreated = 0;
        $invoicesSkipped = 0;

        foreach ($users as $user) {
            // Check if invoice already exists for this period
            $existingInvoice = Invoice::where('user_id', $user->id)
                ->where('billing_period', $billingPeriod)
                ->first();

            if ($existingInvoice) {
                $invoicesSkipped++;

                continue;
            }

            // Calculate amount based on plan
            $amount = $this->calculateAmount($user->plan);

            // Create the invoice
            $invoice = Invoice::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'invoice_date' => $invoiceDate->toDateString(),
                'due_date' => $invoiceDate->copy()->addDays(30)->toDateString(),
                'status' => 'pending',
                'billing_period' => $billingPeriod,
                'description' => 'Cuota mensual Promoción 138 - '.$invoiceDate->format('F Y'),
            ]);

            // Send email notification
            Mail::to($user->email)->send(new InvoiceCreated($invoice));

            $invoicesCreated++;
        }

        $this->info("✅ Facturas creadas: {$invoicesCreated}");

        if ($invoicesSkipped > 0) {
            $this->warn("⚠️  Facturas omitidas (ya existen): {$invoicesSkipped}");
        }

        $this->info('📊 Total de miembros procesados: '.($invoicesCreated + $invoicesSkipped));

        return self::SUCCESS;
    }

    private function calculateAmount(string $plan): float
    {
        return match ($plan) {
            'full' => 27000.00,
            'basic' => 10800.00,
            default => 27000.00,
        };
    }
}
