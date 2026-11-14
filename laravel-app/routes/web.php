<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

/*
|--------------------------------------------------------------------------
| الجزء الأول: Livewire Task Manager
|--------------------------------------------------------------------------
| الصفحة تعرض مكوّن Livewire كاملاً. كل العمليات (إضافة/تعديل/حذف/تغيير حالة)
| تُنفَّذ داخل المكوّن دون إعادة تحميل الصفحة.
*/
Route::view('/tasks', 'tasks.index')->name('tasks.index');

/*
|--------------------------------------------------------------------------
| الجزء الثاني: Laravel Frontend + Go Backend (REST API)
|--------------------------------------------------------------------------
| Laravel هو الواجهة، وGo هو الخادم الخلفي. الـ Controller يستخدم
| GoBooksApi (Laravel HTTP Client) لإرسال GET / POST / DELETE.
*/
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
Route::post('/books', [BookController::class, 'store'])->name('books.store');
Route::delete('/books/{id}', [BookController::class, 'destroy'])
    ->whereNumber('id')
    ->name('books.destroy');
