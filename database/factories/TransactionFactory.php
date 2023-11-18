<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount'=> fake()->randomFloat(3,20,90),
            'user_id' => User::all()->random()->id,
            'due_on' => fake()->date(),
            'vat'=> '0.00',
            'is_vat' => fake()->numberBetween(0,1),
            'status_id' => Status::all()->random()->id
        ];
    }
}
