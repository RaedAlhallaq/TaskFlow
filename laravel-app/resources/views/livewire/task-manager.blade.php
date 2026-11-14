<div class="task-manager">
    <div class="page-head">
        <div>
            <span class="eyebrow">الجزء الأول · Livewire</span>
            <h1>إدارة المهام</h1>
            <p class="lead">
                كل العمليات هنا تُنفَّذ عبر Livewire دون إعادة تحميل الصفحة:
                إضافة، تعديل، حذف، وتغيير الحالة.
            </p>
        </div>
        <div class="pill-row">
            <span class="badge badge-soft">wire:model</span>
            <span class="badge badge-soft">wire:click</span>
            <span class="badge badge-soft">wire:submit</span>
        </div>
    </div>

    @if (session('task_success'))
        <div class="alert alert-success" role="status">{{ session('task_success') }}</div>
    @endif

    <div class="stats">
        <div class="stat total">
            <div class="label">إجمالي المهام</div>
            <div class="value">{{ $stats['total'] }}</div>
        </div>
        <div class="stat todo">
            <div class="label">قيد الانتظار</div>
            <div class="value">{{ $stats['todo'] }}</div>
        </div>
        <div class="stat progress">
            <div class="label">قيد التنفيذ</div>
            <div class="value">{{ $stats['in_progress'] }}</div>
        </div>
        <div class="stat done">
            <div class="label">مكتملة</div>
            <div class="value">{{ $stats['done'] }}</div>
        </div>
    </div>

    {{-- إضافة مهمة جديدة --}}
    <section class="card" style="margin-bottom: 1.4rem;">
        <div class="card-head">
            <div>
                <h2>إضافة مهمة جديدة</h2>
                <p class="hint">يُرسل النموذج عبر <code class="inline">wire:submit="save"</code> دون إعادة تحميل.</p>
            </div>
        </div>

        <form wire:submit="save" class="card-pad">
            <div class="form-grid">
                <div class="field span-2">
                    <label for="title">عنوان المهمة <span class="req">*</span></label>
                    <input id="title" type="text" class="input" placeholder="مثال: مراجعة كود Go Backend"
                           wire:model="title" autocomplete="off">
                    @error('title') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="field span-2">
                    <label for="description">الوصف (اختياري)</label>
                    <textarea id="description" class="textarea" placeholder="تفاصيل إضافية عن المهمة…"
                              wire:model="description"></textarea>
                    @error('description') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="field">
                    <label for="priority">الأولوية</label>
                    <select id="priority" class="select" wire:model="priority">
                        @foreach (\App\Models\Task::PRIORITIES as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('priority') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="field">
                    <label for="status">الحالة</label>
                    <select id="status" class="select" wire:model="status">
                        @foreach (\App\Models\Task::STATUSES as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="field span-2">
                    <label for="due_date">تاريخ الاستحقاق (اختياري)</label>
                    <input id="due_date" type="date" class="input" wire:model="due_date">
                    @error('due_date') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-actions mt-2">
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save">
                    <span wire:loading.remove wire:target="save">إضافة المهمة</span>
                    <span wire:loading wire:target="save">جارٍ الحفظ…</span>
                </button>
                <span class="small muted">الحقول المعلَّمة بـ <span style="color: var(--red);">*</span> مطلوبة.</span>
            </div>
        </form>
    </section>

    {{-- قائمة المهام والفلاتر --}}
    <section class="card">
        <div class="toolbar">
            <div class="filter-tabs" role="group" aria-label="تصفية المهام">
                <button type="button" wire:click="$set('filter', 'all')"
                        class="{{ $filter === 'all' ? 'active' : '' }}">
                    الكل ({{ $stats['total'] }})
                </button>
                <button type="button" wire:click="$set('filter', 'todo')"
                        class="{{ $filter === 'todo' ? 'active' : '' }}">
                    قيد الانتظار ({{ $stats['todo'] }})
                </button>
                <button type="button" wire:click="$set('filter', 'in_progress')"
                        class="{{ $filter === 'in_progress' ? 'active' : '' }}">
                    قيد التنفيذ ({{ $stats['in_progress'] }})
                </button>
                <button type="button" wire:click="$set('filter', 'done')"
                        class="{{ $filter === 'done' ? 'active' : '' }}">
                    مكتملة ({{ $stats['done'] }})
                </button>
            </div>

            <div class="grow">
                <label class="sr-only" for="search">بحث</label>
                <input id="search" type="search" class="input" placeholder="ابحث في العنوان أو الوصف…"
                       wire:model.live.debounce.300ms="search">
            </div>

            @if ($stats['done'] > 0)
                <button type="button" class="btn btn-danger btn-sm"
                        wire:click="clearCompleted"
                        wire:confirm="سيتم حذف جميع المهام المكتملة. هل تريد المتابعة؟">
                    حذف المكتملة
                </button>
            @endif
        </div>

        @if ($tasks->isEmpty())
            <div class="empty">
                <h3>لا توجد مهام مطابقة</h3>
                <p class="mb-0">
                    @if (filled($search) || $filter !== 'all')
                        جرّب تغيير كلمة البحث أو الفلتر.
                    @else
                        أضف أول مهمة من النموذج أعلاه.
                    @endif
                </p>
            </div>
        @else
            <ul class="task-list">
                @foreach ($tasks as $task)
                    <li class="task {{ $task->status === 'done' ? 'is-done' : '' }}"
                        wire:key="task-{{ $task->id }}">

                        @if ($editingId === $task->id)
                            {{-- نموذج التعديل داخل الصفحة --}}
                            <form wire:submit="update" class="edit-box" style="width: 100%;">
                                <div class="form-grid">
                                    <div class="field span-2">
                                        <label>عنوان المهمة <span class="req">*</span></label>
                                        <input type="text" class="input" wire:model="editingTitle">
                                        @error('editingTitle') <span class="error">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="field span-2">
                                        <label>الوصف</label>
                                        <textarea class="textarea" wire:model="editingDescription"></textarea>
                                        @error('editingDescription') <span class="error">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="field">
                                        <label>الأولوية</label>
                                        <select class="select" wire:model="editingPriority">
                                            @foreach (\App\Models\Task::PRIORITIES as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('editingPriority') <span class="error">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="field">
                                        <label>الحالة</label>
                                        <select class="select" wire:model="editingStatus">
                                            @foreach (\App\Models\Task::STATUSES as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('editingStatus') <span class="error">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="field span-2">
                                        <label>تاريخ الاستحقاق</label>
                                        <input type="date" class="input" wire:model="editingDueDate">
                                        @error('editingDueDate') <span class="error">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="form-actions mt-2">
                                    <button type="submit" class="btn btn-primary btn-sm"
                                            wire:loading.attr="disabled" wire:target="update">
                                        <span wire:loading.remove wire:target="update">حفظ التعديل</span>
                                        <span wire:loading wire:target="update">جارٍ الحفظ…</span>
                                    </button>
                                    <button type="button" class="btn btn-ghost btn-sm" wire:click="cancelEdit">
                                        إلغاء
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="task-main">
                                <div class="task-title-row">
                                    <h3 class="task-title">{{ $task->title }}</h3>

                                    <span class="badge badge-{{ $task->status }}">
                                        <span class="badge-dot"></span>
                                        {{ $task->statusLabel() }}
                                    </span>

                                    <span class="badge badge-{{ $task->priority }}">
                                        أولوية {{ $task->priorityLabel() }}
                                    </span>

                                    @if ($task->isOverdue())
                                        <span class="badge badge-overdue">متأخرة</span>
                                    @endif
                                </div>

                                @if (filled($task->description))
                                    <p class="task-desc">{{ $task->description }}</p>
                                @endif

                                <div class="task-meta">
                                    <span>#{{ $task->id }}</span>
                                    <span>·</span>
                                    <span>أُضيفت {{ $task->created_at->diffForHumans() }}</span>
                                    @if ($task->due_date)
                                        <span>·</span>
                                        <span>الاستحقاق: {{ $task->due_date->format('Y-m-d') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="task-actions">
                                {{-- تغيير الحالة عبر wire:click --}}
                                <button type="button"
                                        class="btn btn-ghost btn-sm status-toggle"
                                        wire:click="advanceStatus({{ $task->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="advanceStatus({{ $task->id }})"
                                        title="تغيير الحالة">
                                    @if ($task->status === 'todo')
                                        بدء
                                    @elseif ($task->status === 'in_progress')
                                        إكمال
                                    @else
                                        إعادة فتح
                                    @endif
                                </button>

                                <button type="button" class="btn btn-ghost btn-sm"
                                        wire:click="edit({{ $task->id }})">
                                    تعديل
                                </button>

                                <button type="button" class="btn btn-danger btn-sm"
                                        wire:click="delete({{ $task->id }})"
                                        wire:confirm="هل تريد حذف هذه المهمة نهائياً؟">
                                    حذف
                                </button>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </section>

    <p class="small muted mt-2">
        ملاحظة: البحث والفلترة يستخدمان <code class="inline">wire:model.live</code> و
        <code class="inline">wire:click</code>، والقائمة تُحدَّث فوراً من قاعدة بيانات SQLite.
    </p>
</div>