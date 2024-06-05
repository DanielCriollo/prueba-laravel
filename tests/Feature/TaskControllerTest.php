<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Http\Controllers\TaskController;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;


class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'api'); // Simular autenticación para las pruebas
    }

    /** @test */
    public function it_can_list_tasks_with_filters()
    {
        Task::factory()->count(3)->create(['status' => 'pending']);
        Task::factory()->count(2)->create(['status' => 'completed']);

        $response = $this->getJson('/api/tasks?status=pending');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_create_a_task()
    {
        $taskData = [
            'title' => 'New Task',
            'description' => 'Task description',
            'status' => 'pending',
            'due_date' => '2024-06-30',
        ];

        $response = $this->postJson('/api/tasks', $taskData);

        $response->assertStatus(201)
            ->assertJsonFragment($taskData);
    }

    /** @test */
    public function it_can_show_a_task()
    {
        $task = Task::factory()->create();

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJson($task->toArray());
    }

    /** @test */
    public function it_can_update_a_task()
    {
        $task = Task::factory()->create();

        $updatedData = [
            'title' => 'Updated Task',
            'description' => 'Updated description',
            'status' => 'in_progress',
            'due_date' => '2024-07-15',
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJsonFragment($updatedData);
    }

    /** @test */
    public function it_can_delete_a_task()
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
