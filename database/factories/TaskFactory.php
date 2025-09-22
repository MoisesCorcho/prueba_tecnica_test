<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::create([
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => bcrypt('password'),
        ]);
        return [
            'title' => fake()->title(),
            'description' => fake()->sentence(),
            'due_date' => fake()->dateTimeBetween(now(), now()->addDays(3))->format('Y-m-d'),
            'status' => fake()->randomElement([true, false]),
            'user_id' => $user->id
        ];
    }
}
