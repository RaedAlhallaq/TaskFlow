<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة المهام') · Laravel + Livewire + Go</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @livewireStyles
</head>
<body>
    <header class="topbar">
        <div class="container topbar-inner">
            <a href="{{ route('home') }}" class="brand">
                <span class="brand-mark">TF</span>
                <span>
                    TaskFlow
                    <small>Laravel · Livewire · Go API</small>
                </span>
            </a>

            <nav class="nav" aria-label="التنقل الرئيسي">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    الرئيسية
                </a>
                <a href="{{ route('tasks.index') }}" class="{{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                    إدارة المهام
                </a>
                <a href="{{ route('books.index') }}" class="{{ request()->routeIs('books.*') ? 'active' : '' }}">
                    مكتبة الكتب
                </a>
            </nav>
        </div>
    </header>

    <main class="page">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success" role="status">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-error" role="alert">{{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="footer">
        <div class="container footer-inner">
            <span>TaskFlow · Laravel Livewire · Go REST API</span>
            <span>120220871</span>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
