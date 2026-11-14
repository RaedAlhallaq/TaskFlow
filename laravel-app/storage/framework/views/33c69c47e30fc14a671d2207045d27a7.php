<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'لوحة المهام'); ?> · Laravel + Livewire + Go</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>
<body>
    <header class="topbar">
        <div class="container topbar-inner">
            <a href="<?php echo e(route('home')); ?>" class="brand">
                <span class="brand-mark">TF</span>
                <span>
                    TaskFlow
                    <small>Laravel · Livewire · Go API</small>
                </span>
            </a>

            <nav class="nav" aria-label="التنقل الرئيسي">
                <a href="<?php echo e(route('home')); ?>" class="<?php echo e(request()->routeIs('home') ? 'active' : ''); ?>">
                    الرئيسية
                </a>
                <a href="<?php echo e(route('tasks.index')); ?>" class="<?php echo e(request()->routeIs('tasks.*') ? 'active' : ''); ?>">
                    إدارة المهام
                </a>
                <a href="<?php echo e(route('books.index')); ?>" class="<?php echo e(request()->routeIs('books.*') ? 'active' : ''); ?>">
                    مكتبة الكتب
                </a>
            </nav>
        </div>
    </header>

    <main class="page">
        <div class="container">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                <div class="alert alert-success" role="status"><?php echo e(session('success')); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                <div class="alert alert-error" role="alert"><?php echo e(session('error')); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>

    <footer class="footer">
        <div class="container footer-inner">
            <span>TaskFlow · Laravel Livewire · Go REST API</span>
            <span>120220871</span>
        </div>
    </footer>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</body>
</html>
<?php /**PATH C:\Users\Raed Alhallaq\Documents\Default Project\laravel-app\resources\views/layouts/app.blade.php ENDPATH**/ ?>