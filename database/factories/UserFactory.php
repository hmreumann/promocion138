<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
            'active' => fake()->boolean(70), // 70% chance of being active
            'payment_method' => fake()->randomElement(['smsv', 'bank', 'cash', null]),
            'plan' => fake()->randomElement(['full', 'basic', null]),
        ];
    }

    /**
     * Create an active user with SMSV payment method.
     */
    public function smsvActive(): static
    {
        return $this->state([
            'active' => true,
            'payment_method' => 'smsv',
            'plan' => 'full',
        ]);
    }

    /**
     * Create an inactive user.
     */
    public function inactive(): static
    {
        return $this->state([
            'active' => false,
        ]);
    }

    /**
     * Create a user with full plan.
     */
    public function fullPlan(): static
    {
        return $this->state([
            'plan' => 'full',
        ]);
    }

    /**
     * Create a user with basic plan.
     */
    public function basicPlan(): static
    {
        return $this->state([
            'plan' => 'basic',
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
