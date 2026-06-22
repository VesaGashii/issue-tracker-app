<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-3 months', '+1 month');

        return [
            'name' => fake()->unique()->words(3, true),
            'description' => fake()->paragraphs(2, true),
            'start_date' => $startDate,
            'deadline' => fake()->dateTimeBetween($startDate, '+8 months'),
        ];
    }
}
