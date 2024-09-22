<?php

namespace App\Common;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    /**
     * Json response utility function
     *
     * @ticket Feature/DL-4
     *
     * @param array $result
     * @return JsonResponse
     */
    protected function response(array $result): JsonResponse
    {
        $status = $result['status'] == config('constants.STATUS_SUCCESS') ? 200 : 500;
        return response()->json($result, $status);
    }
}
