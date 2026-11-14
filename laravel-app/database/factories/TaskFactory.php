<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'priority' => fake()->randomElement(array_keys(Task::PRIORITIES)),
            'status' => fake()->randomElement(array_keys(Task::STATUSES)),
            'due_date' => fake()->optional()->dateTimeBetween('-3 days', '+14 days'),
        ];
    }
}
