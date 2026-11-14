@extends('layouts.app')

@section('title', 'إضافة كتاب')

@section('content')
    <div class="page-head">
        <div>
            <span class="eyebrow">الجزء الثاني · Laravel + Go</span>
            <h1>إضافة كتاب إلى خادم Go</h1>
            <p class="lead">
                يُرسل هذا النموذج بياناته إلى Laravel، ثم يقوم Laravel بإرسالها كـ JSON
                إلى <code class="inline">POST {{ $apiUrl }}/api/books</code>.
            </p>
        </div>
        <a href="{{ route('books.index') }}" class="btn btn-ghost">رجوع للمكتبة</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-error" role="alert">
            <div>
                <strong>تعذّرت الإضافة:</strong>
                <ul style="margin: .3rem 0 0; padding-inline-start: 1.2rem;">
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="home-grid">
        <section class="card">
            <div class="card-head">
                <div>
                    <h2>بيانات الكتاب</h2>
                    <p class="hint">تُتحقق البيانات في Laravel أولاً، ثم في Go مرة أخرى.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('books.store') }}" class="card-pad">
                @csrf

                <div class="form-grid">
                    <div class="field span-2">
                        <label for="title">عنوان الكتاب <span class="req">*</span></label>
                        <input id="title" name="title" type="text" class="input"
                               value="{{ old('title') }}" placeholder="مثال: The Pragmatic Programmer">
                        @error('title') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field">
                        <label for="author">اسم المؤلف <span class="req">*</span></label>
                        <input id="author" name="author" type="text" class="input"
                               value="{{ old('author') }}" placeholder="مثال: Andrew Hunt">
                        @error('author') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field">
                        <label for="year">سنة النشر <span class="req">*</span></label>
                        <input id="year" name="year" type="number" class="input ltr"
                               value="{{ old('year', now()->year) }}" min="1000" max="{{ now()->year + 1 }}">
                        @error('year') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-actions mt-3">
                    <button type="submit" class="btn btn-primary">إضافة عبر Go API</button>
                    <a href="{{ route('books.index') }}" class="btn btn-ghost">إلغاء</a>
                </div>
            </form>
        </section>

        <aside class="card card-pad">
            <h2>ما الذي سيُرسَل؟</h2>
            <p class="small muted">
                بعد نجاح التحقق في Laravel، يُرسل الطلب التالي إلى Go:
            </p>

            <pre class="code">POST {{ $apiUrl }}/api/books
Content-Type: application/json
Accept: application/json

{
    "title": "The Pragmatic Programmer",
    "author": "Andrew Hunt",
    "year": 1999
}</pre>

            <h3 class="mt-3" style="font-size: .95rem;">الاستجابة المتوقعة</h3>
            <pre class="code">HTTP/1.1 201 Created

{
    "data": {
        "id": 4,
        "title": "The Pragmatic Programmer",
        "author": "Andrew Hunt",
        "year": 1999,
        "created_at": "2026-10-31T12:00:00Z"
    },
    "message": "تمت إضافة الكتاب بنجاح"
}</pre>

            <p class="small muted mt-2 mb-0">
                إذا كان خادم Go متوقفاً ستظهر رسالة خطأ واضحة، ولن تُفقد البيانات التي أدخلتها.
            </p>
        </aside>
    </div>
@endsection
