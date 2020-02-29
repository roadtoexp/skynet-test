<?php

declare(strict_types=1);

namespace App\Controllers;

class BaseApiController
{
    /**
     * @param  int          $code
     * @param  string|null  $message
     */
    public function abort(int $code, ?string $message)
    {
        $this->response(
            "HTTP/1.1 $code $message",
            [
                'result' => 'error',
            ]
        );
    }

    /**
     * @param  string  $header
     * @param  array   $bodyResponse
     */
    public function response(string $header, array $bodyResponse): void
    {
        header($header);
        echo json_encode(
            $bodyResponse,
            JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
        die();
    }
}