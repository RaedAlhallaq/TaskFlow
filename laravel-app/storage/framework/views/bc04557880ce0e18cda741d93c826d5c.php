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

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('task_success')): ?>
        <div class="alert alert-success" role="status"><?php echo e(session('task_success')); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="stats">
        <div class="stat total">
            <div class="label">إجمالي المهام</div>
            <div class="value"><?php echo e($stats['total']); ?></div>
        </div>
        <div class="stat todo">
            <div class="label">قيد الانتظار</div>
            <div class="value"><?php echo e($stats['todo']); ?></div>
        </div>
        <div class="stat progress">
            <div class="label">قيد التنفيذ</div>
            <div class="value"><?php echo e($stats['in_progress']); ?></div>
        </div>
        <div class="stat done">
            <div class="label">مكتملة</div>
            <div class="value"><?php echo e($stats['done']); ?></div>
        </div>
    </div>

    
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="field span-2">
                    <label for="description">الوصف (اختياري)</label>
                    <textarea id="description" class="textarea" placeholder="تفاصيل إضافية عن المهمة…"
                              wire:model="description"></textarea>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="field">
                    <label for="priority">الأولوية</label>
                    <select id="priority" class="select" wire:model="priority">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = \App\Models\Task::PRIORITIES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['priority'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="field">
                    <label for="status">الحالة</label>
                    <select id="status" class="select" wire:model="status">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = \App\Models\Task::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="field span-2">
                    <label for="due_date">تاريخ الاستحقاق (اختياري)</label>
                    <input id="due_date" type="date" class="input" wire:model="due_date">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['due_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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

    
    <section class="card">
        <div class="toolbar">
            <div class="filter-tabs" role="group" aria-label="تصفية المهام">
                <button type="button" wire:click="$set('filter', 'all')"
                        class="<?php echo e($filter === 'all' ? 'active' : ''); ?>">
                    الكل (<?php echo e($stats['total']); ?>)
                </button>
                <button type="button" wire:click="$set('filter', 'todo')"
                        class="<?php echo e($filter === 'todo' ? 'active' : ''); ?>">
                    قيد الانتظار (<?php echo e($stats['todo']); ?>)
                </button>
                <button type="button" wire:click="$set('filter', 'in_progress')"
                        class="<?php echo e($filter === 'in_progress' ? 'active' : ''); ?>">
                    قيد التنفيذ (<?php echo e($stats['in_progress']); ?>)
                </button>
                <button type="button" wire:click="$set('filter', 'done')"
                        class="<?php echo e($filter === 'done' ? 'active' : ''); ?>">
                    مكتملة (<?php echo e($stats['done']); ?>)
                </button>
            </div>

            <div class="grow">
                <label class="sr-only" for="search">بحث</label>
                <input id="search" type="search" class="input" placeholder="ابحث في العنوان أو الوصف…"
                       wire:model.live.debounce.300ms="search">
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stats['done'] > 0): ?>
                <button type="button" class="btn btn-danger btn-sm"
                        wire:click="clearCompleted"
                        wire:confirm="سيتم حذف جميع المهام المكتملة. هل تريد المتابعة؟">
                    حذف المكتملة
                </button>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tasks->isEmpty()): ?>
            <div class="empty">
                <h3>لا توجد مهام مطابقة</h3>
                <p class="mb-0">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($search) || $filter !== 'all'): ?>
                        جرّب تغيير كلمة البحث أو الفلتر.
                    <?php else: ?>
                        أضف أول مهمة من النموذج أعلاه.
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </p>
            </div>
        <?php else: ?>
            <ul class="task-list">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <li class="task <?php echo e($task->status === 'done' ? 'is-done' : ''); ?>"
                        <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'task-'.e($task->id).''; ?>wire:key="task-<?php echo e($task->id); ?>">

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editingId === $task->id): ?>
                            
                            <form wire:submit="update" class="edit-box" style="width: 100%;">
                                <div class="form-grid">
                                    <div class="field span-2">
                                        <label>عنوان المهمة <span class="req">*</span></label>
                                        <input type="text" class="input" wire:model="editingTitle">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['editingTitle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>

                                    <div class="field span-2">
                                        <label>الوصف</label>
                                        <textarea class="textarea" wire:model="editingDescription"></textarea>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['editingDescription'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>

                                    <div class="field">
                                        <label>الأولوية</label>
                                        <select class="select" wire:model="editingPriority">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = \App\Models\Task::PRIORITIES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </select>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['editingPriority'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>

                                    <div class="field">
                                        <label>الحالة</label>
                                        <select class="select" wire:model="editingStatus">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = \App\Models\Task::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </select>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['editingStatus'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>

                                    <div class="field span-2">
                                        <label>تاريخ الاستحقاق</label>
                                        <input type="date" class="input" wire:model="editingDueDate">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['editingDueDate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                        <?php else: ?>
                            <div class="task-main">
                                <div class="task-title-row">
                                    <h3 class="task-title"><?php echo e($task->title); ?></h3>

                                    <span class="badge badge-<?php echo e($task->status); ?>">
                                        <span class="badge-dot"></span>
                                        <?php echo e($task->statusLabel()); ?>

                                    </span>

                                    <span class="badge badge-<?php echo e($task->priority); ?>">
                                        أولوية <?php echo e($task->priorityLabel()); ?>

                                    </span>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($task->isOverdue()): ?>
                                        <span class="badge badge-overdue">متأخرة</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($task->description)): ?>
                                    <p class="task-desc"><?php echo e($task->description); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <div class="task-meta">
                                    <span>#<?php echo e($task->id); ?></span>
                                    <span>·</span>
                                    <span>أُضيفت <?php echo e($task->created_at->diffForHumans()); ?></span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($task->due_date): ?>
                                        <span>·</span>
                                        <span>الاستحقاق: <?php echo e($task->due_date->format('Y-m-d')); ?></span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>

                            <div class="task-actions">
                                
                                <button type="button"
                                        class="btn btn-ghost btn-sm status-toggle"
                                        wire:click="advanceStatus(<?php echo e($task->id); ?>)"
                                        wire:loading.attr="disabled"
                                        wire:target="advanceStatus(<?php echo e($task->id); ?>)"
                                        title="تغيير الحالة">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($task->status === 'todo'): ?>
                                        بدء
                                    <?php elseif($task->status === 'in_progress'): ?>
                                        إكمال
                                    <?php else: ?>
                                        إعادة فتح
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </button>

                                <button type="button" class="btn btn-ghost btn-sm"
                                        wire:click="edit(<?php echo e($task->id); ?>)">
                                    تعديل
                                </button>

                                <button type="button" class="btn btn-danger btn-sm"
                                        wire:click="delete(<?php echo e($task->id); ?>)"
                                        wire:confirm="هل تريد حذف هذه المهمة نهائياً؟">
                                    حذف
                                </button>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </li>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </ul>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </section>

    <p class="small muted mt-2">
        ملاحظة: البحث والفلترة يستخدمان <code class="inline">wire:model.live</code> و
        <code class="inline">wire:click</code>، والقائمة تُحدَّث فوراً من قاعدة بيانات SQLite.
    </p>
</div><?php /**PATH C:\Users\Raed Alhallaq\Documents\Default Project\laravel-app\resources\views/livewire/task-manager.blade.php ENDPATH**/ ?>