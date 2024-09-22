<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Log;
use Throwable;

class ApiException extends Exception
{
    public function handle(Request $request, Throwable $exception): JsonResponse|bool
    {
        if ($request->is('api/*')) {
            return $this->handleApiException($exception);
        }

        return false;
    }

    protected function handleApiException(Throwable $exception): JsonResponse
    {
        if ($exception instanceof HttpResponseException) {
            $status = $exception->getResponse()->getStatusCode();
        } elseif ($exception instanceof AuthenticationException) {
            $status = 401;
        } else {
            $status = 500;
        }

        $message = $exception->getMessage() ?: 'An error occurred';

        Log::channel('applog')->error(
            "[API Error] An Unexpected error occurred",
            ['message: ' => $message]
        );

        return response()->json([
            'message' => $message,
            'status' => $status,
        ], $status);
    }
}
