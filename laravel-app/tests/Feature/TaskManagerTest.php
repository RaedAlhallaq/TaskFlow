<?php

namespace Tests\Feature;

use App\Livewire\TaskManager;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TaskManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_tasks_page_loads(): void
    {
        $this->get('/tasks')
            ->assertOk()
            ->assertSee('إدارة المهام');
    }

    public function test_a_task_can_be_created(): void
    {
        Livewire::test(TaskManager::class)
            ->set('title', 'مراجعة مشروع Livewire')
            ->set('description', 'قراءة الكود وشرحه')
            ->set('priority', 'high')
            ->set('status', 'todo')
            ->set('due_date', now()->addWeek()->format('Y-m-d'))
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('tasks', [
            'title' => 'مراجعة مشروع Livewire',
            'priority' => 'high',
            'status' => 'todo',
        ]);
    }

    public function test_title_is_required_and_minimum_length_is_enforced(): void
    {
        Livewire::test(TaskManager::class)
            ->set('title', 'ab')
            ->call('save')
            ->assertHasErrors(['title' => 'min']);

        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_a_task_can_be_updated_inline(): void
    {
        $task = Task::factory()->create([
            'title' => 'العنوان القديم',
            'status' => 'todo',
        ]);

        Livewire::test(TaskManager::class)
            ->call('edit', $task->id)
            ->assertSet('editingId', $task->id)
            ->set('editingTitle', 'العنوان الجديد')
            ->set('editingStatus', 'done')
            ->call('update')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'العنوان الجديد',
            'status' => 'done',
        ]);
    }

    public function test_status_moves_to_the_next_state(): void
    {
        $task = Task::factory()->create(['status' => 'todo']);

        Livewire::test(TaskManager::class)->call('advanceStatus', $task->id);
        $this->assertSame('in_progress', $task->fresh()->status);

        Livewire::test(TaskManager::class)->call('advanceStatus', $task->id);
        $this->assertSame('done', $task->fresh()->status);

        Livewire::test(TaskManager::class)->call('advanceStatus', $task->id);
        $this->assertSame('todo', $task->fresh()->status);
    }

    public function test_a_task_can_be_deleted(): void
    {
        $task = Task::factory()->create();

        Livewire::test(TaskManager::class)
            ->call('delete', $task->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_search_and_filter_only_return_matching_tasks(): void
    {
        Task::factory()->create(['title' => 'مهمة Laravel', 'status' => 'todo']);
        Task::factory()->create(['title' => 'مهمة Go', 'status' => 'done']);

        Livewire::test(TaskManager::class)
            ->set('search', 'Laravel')
            ->assertSee('مهمة Laravel')
            ->assertDontSee('مهمة Go')
            ->set('search', '')
            ->set('filter', 'done')
            ->assertSee('مهمة Go')
            ->assertDontSee('مهمة Laravel');
    }
}
