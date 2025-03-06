<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    private static int $taskCounter = 1;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $lastId = Task::all()->last();
        $lastId === null ? $lastId = 0 : $lastId = $lastId->id;
        return [
            'title' => 'Задача' . $lastId + self::$taskCounter,
            'description' => 'Задача' . $lastId + self::$taskCounter++ . ' описание',
            'due_date' => fake()->dateTimeBetween('now', '+1 month'),
            'create_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'status' => fake()->randomElement(['выполнена', 'не выполнена']),
            'priority' => fake()->randomElement(['низкий', 'средний', 'высокий']),
            'category' => fake()->randomElement(['Работа', 'Дом', 'Личное', 'Учеба']),
        ];
    }
}
