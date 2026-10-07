<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TaskListTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders_the_task_list(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSeeLivewire('task-list');
    }

    public function test_it_adds_a_task(): void
    {
        Livewire::test('task-list')
            ->set('title', 'Learn about Slash')
            ->call('addTask')
            ->assertSet('title', '');

        $this->assertDatabaseHas('tasks', [
            'title' => 'Learn about Slash',
            'completed' => false,
        ]);
    }

    public function test_it_does_not_add_a_blank_task(): void
    {
        Livewire::test('task-list')
            ->set('title', '   ')
            ->call('addTask');

        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_it_toggles_a_task_as_completed(): void
    {
        $task = Task::create(['title' => 'Buy flight to Portugal']);

        Livewire::test('task-list')
            ->call('toggleTask', $task->id);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'completed' => true,
        ]);
    }

    public function test_it_deletes_a_task(): void
    {
        $task = Task::create(['title' => 'Answer emails for 30 minutes']);

        Livewire::test('task-list')
            ->call('deleteTask', $task->id);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
