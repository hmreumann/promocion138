<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first 10 users to create invoices for
        $users = User::take(10)->get();

        foreach ($users as $user) {
            // Create 3 invoices for each user (different months)
            Invoice::factory()->create([
                'user_id' => $user->id,
                'amount' => 27000.00,
                'billing_period' => '2025-08',
                'status' => 'pending',
            ]);

            Invoice::factory()->create([
                'user_id' => $user->id,
                'amount' => 27000.00,
                'billing_period' => '2025-07',
                'status' => 'paid',
            ]);

            Invoice::factory()->create([
                'user_id' => $user->id,
                'amount' => 27000.00,
                'billing_period' => '2025-06',
                'status' => 'overdue',
            ]);
        }
    }
}
