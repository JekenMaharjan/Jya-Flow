<?php

namespace Database\Factories;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
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
        $allowedExtensions = ['png', 'jpg', 'jpeg', 'docs', 'txt'];

        $filenames = collect(range(1, fake()->numberBetween(1, 4)))->map(function () use ($allowedExtensions) {
            $extension = fake()->randomElement($allowedExtensions);
            return 'uploads/' . fake()->word() . '.' . $extension;
        })->toArray();

        return [
            'user_id' => User::factory(),

            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(2),

            'filename' => $filenames,

            'due_at' => fake()->dateTimeBetween('now', '+1 month'),

            'status' => fake()->randomElement(TaskStatus::cases()),
            'priority' => fake()->randomElement(TaskPriority::cases()),
        ];
    }
}