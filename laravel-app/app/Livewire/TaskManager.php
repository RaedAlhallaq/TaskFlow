<?php

namespace App\Livewire;

use App\Models\Task;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TaskManager extends Component
{
    // حقول نموذج الإضافة
    public string $title = '';

    public string $description = '';

    public string $priority = 'medium';

    public string $status = 'todo';

    public string $due_date = '';

    // حقول التعديل
    public ?int $editingId = null;

    public string $editingTitle = '';

    public string $editingDescription = '';

    public string $editingPriority = 'medium';

    public string $editingStatus = 'todo';

    public string $editingDueDate = '';

    // البحث والفلترة
    public string $filter = 'all';

    public string $search = '';

    protected function storeRules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'priority' => ['required', Rule::in(array_keys(Task::PRIORITIES))],
            'status' => ['required', Rule::in(array_keys(Task::STATUSES))],
            'due_date' => ['nullable', 'date'],
        ];
    }

    protected function updateRules(): array
    {
        return [
            'editingTitle' => ['required', 'string', 'min:3', 'max:120'],
            'editingDescription' => ['nullable', 'string', 'max:1000'],
            'editingPriority' => ['required', Rule::in(array_keys(Task::PRIORITIES))],
            'editingStatus' => ['required', Rule::in(array_keys(Task::STATUSES))],
            'editingDueDate' => ['nullable', 'date'],
        ];
    }

    // أسماء الحقول المستخدمة في رسائل الأخطاء
    protected function validationAttributes(): array
    {
        return [
            'title' => 'عنوان المهمة',
            'description' => 'الوصف',
            'priority' => 'الأولوية',
            'status' => 'الحالة',
            'due_date' => 'تاريخ الاستحقاق',
            'editingTitle' => 'عنوان المهمة',
            'editingDescription' => 'الوصف',
            'editingPriority' => 'الأولوية',
            'editingStatus' => 'الحالة',
            'editingDueDate' => 'تاريخ الاستحقاق',
        ];
    }

    // إضافة مهمة جديدة
    public function save(): void
    {
        $data = $this->validate($this->storeRules(), [], $this->validationAttributes());
        $data['due_date'] = ($data['due_date'] ?? null) ?: null;

        Task::create($data);

        $this->reset('title', 'description', 'due_date');
        $this->priority = 'medium';
        $this->status = 'todo';
        $this->resetValidation();

        session()->flash('task_success', 'تمت إضافة المهمة بنجاح.');
    }

    // فتح نموذج التعديل لمهمة محددة
    public function edit(int $taskId): void
    {
        $task = Task::findOrFail($taskId);

        $this->editingId = $task->id;
        $this->editingTitle = $task->title;
        $this->editingDescription = (string) $task->description;
        $this->editingPriority = $task->priority;
        $this->editingStatus = $task->status;
        $this->editingDueDate = $task->due_date?->format('Y-m-d') ?? '';

        $this->resetValidation();
    }

    // حفظ التعديل
    public function update(): void
    {
        if ($this->editingId === null) {
            return;
        }

        $data = $this->validate($this->updateRules(), [], $this->validationAttributes());

        $task = Task::findOrFail($this->editingId);
        $task->update([
            'title' => $data['editingTitle'],
            'description' => ($data['editingDescription'] ?? null) ?: null,
            'priority' => $data['editingPriority'],
            'status' => $data['editingStatus'],
            'due_date' => ($data['editingDueDate'] ?? null) ?: null,
        ]);

        $this->cancelEdit();

        session()->flash('task_success', 'تم تحديث المهمة بنجاح.');
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->reset('editingTitle', 'editingDescription', 'editingDueDate');
        $this->editingPriority = 'medium';
        $this->editingStatus = 'todo';
        $this->resetValidation();
    }

    public function delete(int $taskId): void
    {
        $task = Task::find($taskId);

        if (! $task) {
            return;
        }

        $task->delete();

        if ($this->editingId === $taskId) {
            $this->cancelEdit();
        }

        session()->flash('task_success', 'تم حذف المهمة.');
    }

    // تدوير الحالة: قيد الانتظار، قيد التنفيذ، مكتملة، ثم تعود للبداية
    public function advanceStatus(int $taskId): void
    {
        $task = Task::find($taskId);

        if (! $task) {
            return;
        }

        $task->update(['status' => $task->nextStatus()]);

        session()->flash('task_success', 'تم تغيير حالة المهمة إلى: '.$task->statusLabel());
    }

    public function clearCompleted(): void
    {
        $deleted = Task::where('status', 'done')->delete();

        if ($this->editingId !== null && ! Task::whereKey($this->editingId)->exists()) {
            $this->cancelEdit();
        }

        session()->flash('task_success', "تم حذف {$deleted} مهمة مكتملة.");
    }

    public function render()
    {
        $tasks = Task::query()
            ->search($this->search)
            ->when($this->filter !== 'all', fn ($query) => $query->where('status', $this->filter))
            ->orderByRaw("CASE status WHEN 'in_progress' THEN 0 WHEN 'todo' THEN 1 ELSE 2 END")
            ->latest()
            ->get();

        $counts = Task::query()
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        return view('livewire.task-manager', [
            'tasks' => $tasks,
            'stats' => [
                'total' => Task::count(),
                'todo' => (int) ($counts['todo'] ?? 0),
                'in_progress' => (int) ($counts['in_progress'] ?? 0),
                'done' => (int) ($counts['done'] ?? 0),
            ],
        ]);
    }
}
