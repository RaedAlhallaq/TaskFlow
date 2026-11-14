<?php

namespace App\Http\Controllers;

use App\Services\GoApiException;
use App\Services\GoBooksApi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    // صفحة الكتب: نجلب البيانات من Go ونعرضها
    public function index(GoBooksApi $api): View
    {
        $books = [];
        $error = null;

        try {
            $payload = $api->all();
            $books = $payload['data'] ?? [];
        } catch (GoApiException $exception) {
            $error = $exception->getMessage();
        }

        // بعد إعادة التوجيه نعرض آخر تبادل محفوظ في الجلسة
        $exchange = session('api_exchange', $api->lastExchange);

        return view('books.index', [
            'books' => $books,
            'error' => $error,
            'exchange' => $exchange,
            'apiUrl' => $api->baseUrl(),
        ]);
    }

    public function create(GoBooksApi $api): View
    {
        return view('books.create', ['apiUrl' => $api->baseUrl()]);
    }

    // نتحقق من المدخلات أولاً ثم نرسلها إلى Go
    public function store(Request $request, GoBooksApi $api): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'min:2', 'max:200'],
            'author' => ['required', 'string', 'min:2', 'max:120'],
            'year' => ['required', 'integer', 'min:1000', 'max:'.(now()->year + 1)],
        ], [], [
            'title' => 'عنوان الكتاب',
            'author' => 'اسم المؤلف',
            'year' => 'سنة النشر',
        ]);

        try {
            $result = $api->create([
                'title' => $data['title'],
                'author' => $data['author'],
                'year' => (int) $data['year'],
            ]);

            session()->flash('success', $result['message'] ?? 'تمت إضافة الكتاب بنجاح.');
            session()->flash('api_exchange', $api->lastExchange);

            return redirect()->route('books.index');
        } catch (GoApiException $exception) {
            session()->flash('api_exchange', $api->lastExchange);

            return back()
                ->withInput()
                ->withErrors(['api' => $exception->getMessage()]);
        }
    }

    // حذف كتاب من خادم Go
    public function destroy(int $id, GoBooksApi $api): RedirectResponse
    {
        try {
            $result = $api->delete($id);

            session()->flash('success', $result['message'] ?? 'تم حذف الكتاب.');
        } catch (GoApiException $exception) {
            session()->flash('error', $exception->getMessage());
        }

        session()->flash('api_exchange', $api->lastExchange);

        return redirect()->route('books.index');
    }
}
