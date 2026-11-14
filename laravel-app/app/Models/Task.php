<?php

namespace App\Models;

use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory;

    public const STATUSES = [
        'todo' => 'قيد الانتظار',
        'in_progress' => 'قيد التنفيذ',
        'done' => 'مكتملة',
    ];

    public const PRIORITIES = [
        'low' => 'منخفضة',
        'medium' => 'متوسطة',
        'high' => 'عالية',
    ];

    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function priorityLabel(): string
    {
        return self::PRIORITIES[$this->priority] ?? $this->priority;
    }

    public function nextStatus(): string
    {
        return match ($this->status) {
            'todo' => 'in_progress',
            'in_progress' => 'done',
            default => 'todo',
        };
    }

    public function isOverdue(): bool
    {
        return $this->due_date !== null
            && $this->status !== 'done'
            && $this->due_date->isPast();
    }

    public function scopeSearch($query, ?string $term)
    {
        return $query->when(filled($term), function ($q) use ($term) {
            $q->where(function ($inner) use ($term) {
                $inner->where('title', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            });
        });
    }
}
