<?php

declare(strict_types=1);

namespace App\Common;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class Service
{
    protected function messageReponse(string $message, bool $status): array
    {
        return [
            'message' => $message,
            'status' => $status ? config('constants.STATUS_SUCCESS') : config('constants.STATUS_FAILED')
        ];
    }

    protected function urlReponse(string $url, string $message, bool $status): array
    {
        return [
            'url' => $url,
            'message' => $message,
            'status' => $status ? config('constants.STATUS_SUCCESS') : config('constants.STATUS_FAILED')
        ];
    }

    protected function tokenResponse(string $token, string $message, bool $status): array
    {
        return [
            'token' => $token,
            'message' => $message,
            'status' => $status ? config('constants.STATUS_SUCCESS') : config('constants.STATUS_FAILED')
        ];
    }

    protected function dataReponse(Collection|LengthAwarePaginator $data, string $message, bool $status): array
    {
        return [
            'data' => $data,
            'message' => $message,
            'status' => $status ? config('constants.STATUS_SUCCESS') : config('constants.STATUS_FAILED')
        ];
    }

    protected function messageReponseStatus(string $message, int $status): array
    {
        return [
            'message' => $message,
            'status' => $status
        ];
    }

}
