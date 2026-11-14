<?php

namespace App\Services;

use RuntimeException;

// استثناء خاص بأخطاء الاتصال أو الاستجابة من Go API.
class GoApiException extends RuntimeException
{
    public function __construct(string $message, private readonly ?int $statusCode = null, ?\Throwable $previous = null)
    {
        parent::__construct($message, $statusCode ?? 0, $previous);
    }

    public function statusCode(): ?int
    {
        return $this->statusCode;
    }
}
