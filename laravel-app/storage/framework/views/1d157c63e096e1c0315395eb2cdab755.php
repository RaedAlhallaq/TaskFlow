<?php $__env->startSection('title', 'مكتبة Go API'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-head">
        <div>
            <span class="eyebrow">الجزء الثاني · Laravel + Go</span>
            <h1>مكتبة الكتب</h1>
            <p class="lead">
                هذه الصفحة واجهة Laravel فقط. البيانات تُقرأ وتُضاف وتُحذف من خادم
                <strong>Go</strong> عبر REST API، باستخدام
                <code class="inline">Laravel HTTP Client</code>.
            </p>
        </div>

        <div class="flex">
            <span class="badge <?php echo e(empty($error) ? 'badge-done' : 'badge-high'); ?>">
                <span class="badge-dot"></span>
                <?php echo e(empty($error) ? 'Go API متصل' : 'Go API غير متصل'); ?>

            </span>
            <a href="<?php echo e(route('books.create')); ?>" class="btn btn-primary">إضافة كتاب</a>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($error): ?>
        <div class="alert alert-warning" role="alert">
            <div>
                <strong>تعذّر الوصول إلى خادم Go:</strong> <?php echo e($error); ?>

                <div class="small mt-1">
                    شغّل الخادم من مجلد المشروع:
                    <code>cd go-backend</code> ثم <code>go run .</code>
                    — العنوان الحالي: <code><?php echo e($apiUrl); ?></code>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <section class="card" style="margin-bottom: 1.4rem;">
        <div class="card-head">
            <div>
                <h2>الكتب في خادم Go</h2>
                <p class="hint">
                    الطلب: <span class="method method-get">GET</span>
                    <code class="inline"><?php echo e($apiUrl); ?>/api/books</code>
                </p>
            </div>
            <span class="badge badge-soft"><?php echo e(count($books)); ?> كتاب</span>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty($books)): ?>
            <div class="empty">
                <h3>لا توجد كتب لعرضها</h3>
                <p class="mb-0">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($error): ?>
                        تأكد من تشغيل خادم Go ثم حدّث الصفحة.
                    <?php else: ?>
                        أضف أول كتاب من زر «إضافة كتاب».
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </p>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="data">
                    <thead>
                        <tr>
                            <th class="col-id">ID</th>
                            <th>عنوان الكتاب</th>
                            <th>المؤلف</th>
                            <th>سنة النشر</th>
                            <th class="col-created">أُضيف في</th>
                            <th style="width: 90px;">إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <tr <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'book-'.e($book['id']).''; ?>wire:key="book-<?php echo e($book['id']); ?>">
                                <td class="col-id"><span class="id-pill"><?php echo e($book['id']); ?></span></td>
                                <td><strong><?php echo e($book['title']); ?></strong></td>
                                <td><?php echo e($book['author']); ?></td>
                                <td><span class="ltr"><?php echo e($book['year']); ?></span></td>
                                <td class="small muted ltr col-created"><?php echo e($book['created_at'] ?? '—'); ?></td>
                                <td>
                                    <form method="POST" action="<?php echo e(route('books.destroy', $book['id'])); ?>"
                                          onsubmit="return confirm('حذف هذا الكتاب من خادم Go؟');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </section>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($exchange)): ?>
        <section class="card" style="margin-bottom: 1.4rem;">
            <div class="card-head">
                <div>
                    <h2>آخر تبادل JSON بين Laravel وGo</h2>
                    <p class="hint">نفس البيانات التي أرسلها Laravel واستلمها من الخادم.</p>
                </div>
                <div class="pill-row">
                    <?php ($method = strtolower($exchange['method'] ?? 'get')); ?>
                    <span class="method method-<?php echo e($method); ?>"><?php echo e($exchange['method'] ?? 'GET'); ?></span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($exchange['status'] ?? null) !== null): ?>
                        <span class="badge <?php echo e(($exchange['status'] ?? 0) < 400 ? 'badge-done' : 'badge-high'); ?>">
                            HTTP <?php echo e($exchange['status']); ?>

                        </span>
                    <?php else: ?>
                        <span class="badge badge-high">فشل الاتصال</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <span class="badge badge-soft"><?php echo e($exchange['duration_ms'] ?? 0); ?> ms</span>
                </div>
            </div>

            <div class="card-pad">
                <p class="small muted mb-0" style="direction: ltr; text-align: left;">
                    <?php echo e($exchange['method'] ?? 'GET'); ?> <?php echo e($exchange['url'] ?? ''); ?>

                </p>

                <div class="exchange-grid mt-2">
                    <div>
                        <h4>بيانات الطلب (Laravel إلى Go)</h4>
                        <pre class="code"><?php echo e(! empty($exchange['request_body']) ? json_encode($exchange['request_body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '— لا يوجد Body (طلب GET/DELETE)'); ?></pre>
                    </div>

                    <div>
                        <h4>بيانات الاستجابة (Go إلى Laravel)</h4>
                        <pre class="code"><?php echo e(json_encode($exchange['response_body'] ?? null, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)); ?></pre>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <section class="card card-pad">
        <h2>ماذا يحدث عند كل عملية؟</h2>
        <ol class="steps">
            <li>المتصفح يرسل طلباً إلى Laravel (مثلاً <code class="inline">POST /books</code> مع CSRF Token).</li>
            <li>Laravel يتحقق من المدخلات عبر <code class="inline">$request->validate()</code>.</li>
            <li>كلاس <code class="inline">GoBooksApi</code> يرسل طلب JSON إلى Go باستخدام <code class="inline">Http::send()</code>.</li>
            <li>Go يتحقق من JSON ويعدّل البيانات في الذاكرة ثم يرجع استجابة JSON مع رمز HTTP المناسب.</li>
            <li>Laravel يقرأ <code class="inline">$response->json()</code> ويعيد التوجيه لعرض النتيجة.</li>
        </ol>
        <p class="small muted mb-0">
            ملاحظة: بيانات Go في الذاكرة، لذلك إعادة تشغيل خادم Go تعيد الكتب الثلاثة الأولية.
        </p>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Raed Alhallaq\Documents\Default Project\laravel-app\resources\views/books/index.blade.php ENDPATH**/ ?>