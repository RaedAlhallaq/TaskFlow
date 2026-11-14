<?php

namespace Tests\Feature;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    private array $sampleBook = [
        'id' => 1,
        'title' => 'The Go Programming Language',
        'author' => 'Alan Donovan',
        'year' => 2015,
        'created_at' => '2026-10-31T10:00:00Z',
    ];

    public function test_books_page_displays_data_from_go_api(): void
    {
        Http::fake([
            '*/api/books' => Http::response([
                'data' => [$this->sampleBook],
                'count' => 1,
            ]),
        ]);

        $this->get('/books')
            ->assertOk()
            ->assertSee('The Go Programming Language')
            ->assertSee('Alan Donovan')
            ->assertSee('Go API متصل');
    }

    public function test_a_book_can_be_created_through_the_go_api(): void
    {
        Http::fake([
            '*/api/books' => Http::response([
                'data' => ['id' => 4] + $this->sampleBook,
                'message' => 'تمت إضافة الكتاب بنجاح',
            ], 201),
        ]);

        $this->post('/books', [
            'title' => 'Clean Architecture',
            'author' => 'Robert C. Martin',
            'year' => 2017,
        ])
            ->assertRedirect(route('books.index'))
            ->assertSessionHas('success');

        Http::assertSent(function (Request $request) {
            return $request->method() === 'POST'
                && str_ends_with($request->url(), '/api/books')
                && $request['title'] === 'Clean Architecture'
                && $request['author'] === 'Robert C. Martin'
                && $request['year'] === 2017;
        });
    }

    public function test_book_validation_happens_before_calling_go(): void
    {
        Http::fake();

        $this->post('/books', [
            'title' => '',
            'author' => '',
            'year' => 20,
        ])->assertSessionHasErrors(['title', 'author', 'year']);

        Http::assertNothingSent();
    }

    public function test_a_book_can_be_deleted_through_the_go_api(): void
    {
        Http::fake([
            '*/api/books/1' => Http::response(['message' => 'تم حذف الكتاب']),
        ]);

        $this->delete('/books/1')
            ->assertRedirect(route('books.index'))
            ->assertSessionHas('success');

        Http::assertSent(function (Request $request) {
            return $request->method() === 'DELETE'
                && str_ends_with($request->url(), '/api/books/1');
        });
    }

    public function test_a_helpful_message_is_shown_when_go_is_not_running(): void
    {
        Http::fake(function () {
            throw new ConnectionException('Connection refused');
        });

        $this->get('/books')
            ->assertOk()
            ->assertSee('Go API غير متصل')
            ->assertSee('go run .');
    }

    public function test_error_json_from_go_is_surfaced_to_the_user(): void
    {
        Http::fake([
            '*/api/books' => Http::response(['error' => 'عنوان الكتاب مطلوب'], 422),
        ]);

        $this->from('/books/create')
            ->post('/books', [
                'title' => 'عنوان صالح',
                'author' => 'مؤلف صالح',
                'year' => 2020,
            ])
            ->assertRedirect('/books/create')
            ->assertSessionHasErrors(['api' => 'عنوان الكتاب مطلوب']);
    }
}
