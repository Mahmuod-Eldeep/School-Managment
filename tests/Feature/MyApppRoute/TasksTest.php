<?php

namespace Tests\Feature\MyApppRoute;

use App\Models\Task;
use App\Traits\FeatureTestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TasksTest extends TestCase
{
    // use RefreshDatabase;
    use FeatureTestTrait;
    /**
     * A basic feature test example.
     */


    public function test_get_all_tasks_successfully(): void
    {
        $response  = $this->authorized_user()->get('/api/tasks');
        $response->assertStatus(200);
    }


    public function test_get_all_tasks_failed(): void
    {
        $response = $this->unauthorized_user()->get('/api/tasks/');
        $response->assertStatus(403);
    }


    public function test_get_task_successfully(): void
    {
        $task = Task::factory()->create();
        $response   = $this->authorized_user()->get('/api/tasks/' . $task->id);
        $response->assertStatus(200);
    }
    public function test_delete_task_successfully(): void
    {
        $task = Task::factory()->create();
        $response = $this->authorized_user()->delete('/api/tasks/' . $task->id);
        $taskArray = $task->toArray();
        $response->assertStatus(204);
        $this->assertDatabaseMissing(
            'tasks',
            $taskArray
        );
    }
    public function test_delete_task_failed(): void
    {
        $task = Task::factory()->create();
        $response = $this->unauthorized_user()->delete('/api/tasks/' . $task->id);
        $taskArray = $task->toArray();
        $response->assertStatus(403);
    }


    public function test_creat_task_successfully(): void
    {
        $task = Task::factory()->create();
        $Data = $task->toArray();
        $response
            = $this->authorized_user()->post('/api/tasks', $Data);
        $taskArray = $task->toArray();
        $response->assertStatus(201);
        $this->assertDatabaseHas(
            'tasks',
            $taskArray
        );
    }
    public function test_creat_task_failed(): void
    {
        $task = Task::factory()->create();
        $Data = $task->toArray();
        $response
            = $this->unauthorized_user()->post('/api/tasks', $Data);
        $response->assertStatus(403);
    }


    public function test_update_task_successfully(): void
    {

        $task = Task::factory()->create();
        $Data = ['title' => 'This Task Assert By Testing',];
        $response
            = $this->authorized_user()->put('/api/tasks/' .  $task->id, $Data);
        $taskArray = $task->toArray();
        $response->assertStatus(200);
        $this->assertDatabaseHas(
            'tasks',
            $Data


        );
    }
    public function test_update_task_failed(): void
    {

        $task = Task::factory()->create();
        $Data = ['title' => 'This Task Assert By Testing',];
        $response

            = $this->unauthorized_user()->put('/api/tasks/' .  $task->id, $Data);
        $response->assertstatus(403);
    }
}
