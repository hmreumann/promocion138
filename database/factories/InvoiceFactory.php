<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $invoiceDate = $this->faker->dateTimeBetween('-6 months', 'now');
        $dueDate = Carbon::instance($invoiceDate)->addDays(30);

        return [
            'user_id' => User::factory(),
            'amount' => $this->faker->randomElement([10800.00, 27000.00]),
            'invoice_date' => $invoiceDate,
            'due_date' => $dueDate,
            'status' => 'pending',
            'billing_period' => Carbon::instance($invoiceDate)->format('Y-m'),
            'description' => 'Cuota mensual Promoción 138 - '.Carbon::instance($invoiceDate)->format('F Y'),
            'paid_at' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state([
            'status' => 'pending',
            'paid_at' => null,
        ]);
    }

    public function paid(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'paid',
                'paid_at' => $this->faker->dateTimeBetween($attributes['invoice_date'], 'now'),
            ];
        });
    }

    public function overdue(): static
    {
        return $this->state([
            'status' => 'pending',
            'due_date' => $this->faker->dateTimeBetween('-30 days', '-1 day'),
            'paid_at' => null,
        ]);
    }

    public function fullPlan(): static
    {
        return $this->state([
            'amount' => 27000.00,
        ]);
    }

    public function basicPlan(): static
    {
        return $this->state([
            'amount' => 10800.00,
        ]);
    }
}
