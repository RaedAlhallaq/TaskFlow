<?php $__env->startSection('title', 'الرئيسية'); ?>

<?php $__env->startSection('content'); ?>
    <section class="hero">
        <span class="badge">Laravel · Livewire · Go REST API</span>
        <h1>واجهات تفاعلية بـ Livewire وربط مع Backend بلغة Go</h1>
        <p>
            تطبيق لإدارة المهام يعمل بـ Livewire دون إعادة تحميل الصفحة، وواجهة Laravel تتصل بتطبيق Go عبر HTTP Client لعرض وإضافة وحذف الكتب باستخدام JSON.
        </p>
        <div class="badges">
            <span class="badge">Livewire</span>
            <span class="badge">Laravel HTTP Client</span>
            <span class="badge">Go net/http</span>
            <span class="badge">REST JSON</span>
            <span class="badge">SQLite</span>
        </div>
    </section>

    <div class="home-grid">
        <article class="card feature-card">
            <h2>الجزء الأول: إدارة المهام</h2>
            <p>
                عرض وإضافة وتعديل وحذف المهام وتغيير حالتها دون إعادة تحميل الصفحة باستخدام
                <code class="inline">wire:model</code> و<code class="inline">wire:click</code> و<code class="inline">wire:submit</code>.
            </p>
            <div class="flex">
                <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-primary">فتح إدارة المهام</a>
            </div>
        </article>

        <article class="card feature-card">
            <h2>الجزء الثاني: Laravel متصل بـ Go API</h2>
            <p>
                إرسال طلبات GET وPOST وDELETE إلى خادم Go باستخدام Laravel HTTP Client، مع التحقق من البيانات وعرض تبادل JSON بين التطبيقين.
            </p>
            <div class="flex">
                <a href="<?php echo e(route('books.index')); ?>" class="btn btn-primary">فتح مكتبة الكتب</a>
                <a href="<?php echo e(route('books.create')); ?>" class="btn btn-ghost">إضافة كتاب</a>
            </div>
        </article>
    </div>

    <div class="home-grid">
        <section class="card card-pad">
            <h2>تشغيل المشروع</h2>
            <ol class="steps">
                <li>تشغيل خادم Go: <code class="inline">cd go-backend && go run .</code></li>
                <li>تشغيل Laravel: <code class="inline">cd laravel-app && php artisan serve</code></li>
                <li>فتح <code class="inline">http://127.0.0.1:8000</code></li>
                <li>ضبط <code class="inline">GO_API_URL=http://127.0.0.1:8080</code> في ملف <code class="inline">.env</code></li>
            </ol>
        </section>

        <section class="card card-pad">
            <h2>آلية تبادل البيانات</h2>
            <dl class="kv">
                <dt>طلب</dt>
                <dd>Laravel إلى Go مع بيانات JSON</dd>
                <dt>استجابة</dt>
                <dd>Go إلى Laravel بصيغة JSON</dd>
                <dt>نقطة النهاية</dt>
                <dd><code class="inline">http://127.0.0.1:8080/api/books</code></dd>
                <dt>الطرق</dt>
                <dd>GET / POST / PUT / DELETE</dd>
            </dl>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Raed Alhallaq\Documents\Default Project\laravel-app\resources\views/home.blade.php ENDPATH**/ ?>