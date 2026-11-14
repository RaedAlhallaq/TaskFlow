@extends('layouts.app')

@section('title', 'مكتبة Go API')

@section('content')
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
            <span class="badge {{ empty($error) ? 'badge-done' : 'badge-high' }}">
                <span class="badge-dot"></span>
                {{ empty($error) ? 'Go API متصل' : 'Go API غير متصل' }}
            </span>
            <a href="{{ route('books.create') }}" class="btn btn-primary">إضافة كتاب</a>
        </div>
    </div>

    @if ($error)
        <div class="alert alert-warning" role="alert">
            <div>
                <strong>تعذّر الوصول إلى خادم Go:</strong> {{ $error }}
                <div class="small mt-1">
                    شغّل الخادم من مجلد المشروع:
                    <code>cd go-backend</code> ثم <code>go run .</code>
                    — العنوان الحالي: <code>{{ $apiUrl }}</code>
                </div>
            </div>
        </div>
    @endif

    <section class="card" style="margin-bottom: 1.4rem;">
        <div class="card-head">
            <div>
                <h2>الكتب في خادم Go</h2>
                <p class="hint">
                    الطلب: <span class="method method-get">GET</span>
                    <code class="inline">{{ $apiUrl }}/api/books</code>
                </p>
            </div>
            <span class="badge badge-soft">{{ count($books) }} كتاب</span>
        </div>

        @if (empty($books))
            <div class="empty">
                <h3>لا توجد كتب لعرضها</h3>
                <p class="mb-0">
                    @if ($error)
                        تأكد من تشغيل خادم Go ثم حدّث الصفحة.
                    @else
                        أضف أول كتاب من زر «إضافة كتاب».
                    @endif
                </p>
            </div>
        @else
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
                        @foreach ($books as $book)
                            <tr wire:key="book-{{ $book['id'] }}">
                                <td class="col-id"><span class="id-pill">{{ $book['id'] }}</span></td>
                                <td><strong>{{ $book['title'] }}</strong></td>
                                <td>{{ $book['author'] }}</td>
                                <td><span class="ltr">{{ $book['year'] }}</span></td>
                                <td class="small muted ltr col-created">{{ $book['created_at'] ?? '—' }}</td>
                                <td>
                                    <form method="POST" action="{{ route('books.destroy', $book['id']) }}"
                                          onsubmit="return confirm('حذف هذا الكتاب من خادم Go؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    {{-- لوحة عرض آخر تبادل JSON --}}
    @if (! empty($exchange))
        <section class="card" style="margin-bottom: 1.4rem;">
            <div class="card-head">
                <div>
                    <h2>آخر تبادل JSON بين Laravel وGo</h2>
                    <p class="hint">نفس البيانات التي أرسلها Laravel واستلمها من الخادم.</p>
                </div>
                <div class="pill-row">
                    @php($method = strtolower($exchange['method'] ?? 'get'))
                    <span class="method method-{{ $method }}">{{ $exchange['method'] ?? 'GET' }}</span>
                    @if (($exchange['status'] ?? null) !== null)
                        <span class="badge {{ ($exchange['status'] ?? 0) < 400 ? 'badge-done' : 'badge-high' }}">
                            HTTP {{ $exchange['status'] }}
                        </span>
                    @else
                        <span class="badge badge-high">فشل الاتصال</span>
                    @endif
                    <span class="badge badge-soft">{{ $exchange['duration_ms'] ?? 0 }} ms</span>
                </div>
            </div>

            <div class="card-pad">
                <p class="small muted mb-0" style="direction: ltr; text-align: left;">
                    {{ $exchange['method'] ?? 'GET' }} {{ $exchange['url'] ?? '' }}
                </p>

                <div class="exchange-grid mt-2">
                    <div>
                        <h4>بيانات الطلب (Laravel إلى Go)</h4>
                        <pre class="code">{{ ! empty($exchange['request_body']) ? json_encode($exchange['request_body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '— لا يوجد Body (طلب GET/DELETE)' }}</pre>
                    </div>

                    <div>
                        <h4>بيانات الاستجابة (Go إلى Laravel)</h4>
                        <pre class="code">{{ json_encode($exchange['response_body'] ?? null, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                    </div>
                </div>
            </div>
        </section>
    @endif

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
@endsection
