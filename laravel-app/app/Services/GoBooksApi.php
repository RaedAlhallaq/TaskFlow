<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

// عميل صغير للتعامل مع Go Books API
class GoBooksApi
{
    /** @var array<string, mixed> */
    public array $lastExchange = [];

    public function baseUrl(): string
    {
        return rtrim((string) config('services.go_api.url'), '/');
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->request('GET', '/api/books');
    }

    /**
     * @param  array{title: string, author: string, year: int}  $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        return $this->request('POST', '/api/books', $data);
    }

    /**
     * @return array<string, mixed>
     */
    public function delete(int $id): array
    {
        return $this->request('DELETE', "/api/books/{$id}");
    }

    // تنفيذ الطلب مع حفظ تفاصيل التبادل للعرض في الواجهة
    private function request(string $method, string $path, ?array $payload = null): array
    {
        $url = $this->baseUrl().$path;
        $startedAt = microtime(true);

        try {
            $response = Http::acceptJson()
                ->timeout(5)
                ->send($method, $url, $payload === null ? [] : ['json' => $payload]);
        } catch (ConnectionException $exception) {
            $this->lastExchange = [
                'method' => $method,
                'url' => $url,
                'request_body' => $payload,
                'status' => null,
                'response_body' => ['error' => $exception->getMessage()],
                'duration_ms' => $this->duration($startedAt),
                'connected' => false,
            ];

            throw new GoApiException(
                "تعذّر الاتصال بخادم Go على {$url}. تأكد من تشغيل: cd go-backend ثم go run .",
                previous: $exception,
            );
        }

        $body = $response->json();

        $this->lastExchange = [
            'method' => $method,
            'url' => $url,
            'request_body' => $payload,
            'status' => $response->status(),
            'response_body' => is_array($body) ? $body : ['raw' => $response->body()],
            'duration_ms' => $this->duration($startedAt),
            'connected' => true,
        ];

        if ($response->failed()) {
            $message = is_array($body) && isset($body['error'])
                ? (string) $body['error']
                : 'فشل الطلب إلى خادم Go (HTTP '.$response->status().').';

            throw new GoApiException($message, $response->status());
        }

        return is_array($body) ? $body : [];
    }

    private function duration(float $startedAt): int
    {
        return (int) round((microtime(true) - $startedAt) * 1000);
    }
}
