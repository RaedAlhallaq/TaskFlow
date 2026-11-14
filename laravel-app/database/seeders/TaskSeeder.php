<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = [
            [
                'title' => 'إنشاء واجهة Livewire لعرض المهام',
                'description' => 'استخدام wire:model و wire:click و wire:submit لتنفيذ العمليات بدون إعادة تحميل الصفحة.',
                'priority' => 'high',
                'status' => 'done',
                'due_date' => now()->subDays(2),
            ],
            [
                'title' => 'ربط Laravel بتطبيق Go عبر REST API',
                'description' => 'إرسال طلبات GET و POST و DELETE باستخدام Laravel HTTP Client وتبادل بيانات JSON.',
                'priority' => 'high',
                'status' => 'in_progress',
                'due_date' => now()->addDays(2),
            ],
            [
                'title' => 'مراجعة كود Go Backend وفهم طريقة عمله',
                'description' => 'قراءة main.go وفهم الـ handlers والـ mutex وطريقة تحويل البيانات إلى JSON.',
                'priority' => 'medium',
                'status' => 'todo',
                'due_date' => now()->addDays(5),
            ],
            [
                'title' => 'كتابة اختبارات Livewire و HTTP Client',
                'description' => 'استخدام Livewire::test و Http::fake للتأكد من عمل العمليات دون الحاجة لخادم حقيقي.',
                'priority' => 'medium',
                'status' => 'todo',
                'due_date' => now()->addDays(7),
            ],
            [
                'title' => 'تجهيز ملف المشروع المضغوط',
                'description' => 'استبعاد مجلدي vendor و node_modules وتسمية الملف بالرقم الجامعي.',
                'priority' => 'low',
                'status' => 'todo',
                'due_date' => now()->addDays(9),
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
