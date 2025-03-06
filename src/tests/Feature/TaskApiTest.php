<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_task()
    {
        $data = [
            'title' => 'Задача1',
            'description' => 'Задача1 описание',
            'due_date' => '2025-01-20T15:00:00',
            'create_date' => '2025-01-20T15:00:00',
            'priority' => 'высокий',
            'category' => 'Работа',
            'status' => 'не выполнена'
        ];

        $response = $this->postJson('/api/tasks', $data);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Task created successfully'
            ]);

        $id = $response->decodeResponseJson()['id'];
        $response = $this->getJson("/api/tasks/{$id}");

        $response->assertStatus(200)
            ->assertJson(['data' => [
                'title' => 'Задача1',
                'description' => 'Задача1 описание',
                'due_date' => '2025-01-20 15:00:00',
                'create_date' => '2025-01-20 15:00:00',
                'priority' => 'высокий',
                'category' => 'Работа',
                'status' => 'не выполнена'
        ]]);
    }

    public function test_validation_errors()
    {
        $data = [
            'title' => '',
            'description' => 'Задача1 описание',
            'due_date' => 'asd',
            'create_date' => 'asd',
            'priority' => '',
            'category' => '',
            'status' => 'не выполнена'
        ];
        $headers = [
            'X-Requested-With' => 'XMLHttpRequest'
        ];

        $response = $this->postJson('/api/tasks', $data, $headers);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title'])
            ->assertJsonValidationErrors(['priority'])
            ->assertJsonValidationErrors(['category'])
            ->assertJsonValidationErrors(['due_date'])
            ->assertJsonValidationErrors(['create_date']);

        $data = [
            'title' => str_repeat('a', 256),
            'description' => 'Задача1 описание',
            'due_date' => '2025-01-20T15:00:00',
            'create_date' => '2025-01-20T15:00:00',
            'priority' => 'высокий',
            'category' => 'Работа',
            'status' => 'не выполнена'
        ];

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_get_task()
    {
        $task = \App\Models\Task::factory()->create();

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJson(['data' => ['title' => $task->title]]);
    }

    public function test_delete_task()
    {
        $task = \App\Models\Task::factory()->create();

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Task deleted successfully'
            ]);

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(404);
    }
}
