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
    protected $signature = 'invoices:generate-monthly {--month=} {--year=} {--email= : Generate invoice for a specific user email}';

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
        $email = $this->option('email');

        $invoiceDate = Carbon::createFromDate($year, $month, 1);
        $billingPeriod = $invoiceDate->format('Y-m');

        if ($email) {
            $this->info("Generando factura para el usuario: {$email} - Período: {$billingPeriod}");

            return $this->generateForSpecificUser($email, $invoiceDate, $billingPeriod);
        }

        $this->info("Generando facturas para el período: {$billingPeriod}");

        // Get all active users with Transfer payment method
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
            $amount = $this->calculateAmount($user->plan, $user->cents);

            // Create the invoice
            $invoice = Invoice::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'invoice_date' => $invoiceDate->toDateString(),
                'due_date' => $invoiceDate->copy()->addDays(10)->toDateString(),
                'status' => 'pending',
                'billing_period' => $billingPeriod,
                'description' => 'Cuota mensual Promoción 138 - '.$invoiceDate->format('F Y'),
            ]);

            // Send email notification
            Mail::to($user->email)->send(new InvoiceCreated($invoice));
            sleep(1);

            $invoicesCreated++;
        }

        $this->info("✅ Facturas creadas: {$invoicesCreated}");

        if ($invoicesSkipped > 0) {
            $this->warn("⚠️  Facturas omitidas (ya existen): {$invoicesSkipped}");
        }

        $this->info('📊 Total de miembros procesados: '.($invoicesCreated + $invoicesSkipped));

        return self::SUCCESS;
    }

    private function generateForSpecificUser(string $email, Carbon $invoiceDate, string $billingPeriod): int
    {
        // Find the user by email
        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("❌ Usuario no encontrado con email: {$email}");

            return self::FAILURE;
        }

        if (! $user->active) {
            $this->warn("⚠️  El usuario {$email} no está activo.");

            return self::SUCCESS;
        }

        if ($user->payment_method !== 'transfer') {
            $this->warn("⚠️  El usuario {$email} no tiene método de pago 'transfer'. Método actual: {$user->payment_method}");

            return self::SUCCESS;
        }

        // Check if invoice already exists for this period
        $existingInvoice = Invoice::where('user_id', $user->id)
            ->where('billing_period', $billingPeriod)
            ->first();

        if ($existingInvoice) {
            $this->warn("⚠️  Ya existe una factura para {$email} en el período {$billingPeriod} (ID: {$existingInvoice->id})");

            return self::SUCCESS;
        }

        // Calculate amount based on plan
        $amount = $this->calculateAmount($user->plan, $user->cents);

        // Create the invoice
        $invoice = Invoice::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'invoice_date' => $invoiceDate->toDateString(),
            'due_date' => $invoiceDate->copy()->addDays(10)->toDateString(),
            'status' => 'pending',
            'billing_period' => $billingPeriod,
            'description' => 'Cuota mensual Promoción 138 - '.$invoiceDate->format('F Y'),
        ]);

        // Send email notification
        Mail::to($user->email)->send(new InvoiceCreated($invoice));

        $this->info("✅ Factura creada exitosamente para {$user->name} ({$email})");
        $this->info('💰 Monto: $'.number_format($amount, 2));
        $this->info('📧 Email de notificación enviado');
        $this->info("🆔 ID de factura: {$invoice->id}");

        return self::SUCCESS;
    }

    private function calculateAmount(string $plan, ?float $cents = 0): float
    {
        $amount = config("invoices.plan_amounts.{$plan}", config('invoices.plan_amounts.full'));

        return $amount + $cents / 100;
    }
}
