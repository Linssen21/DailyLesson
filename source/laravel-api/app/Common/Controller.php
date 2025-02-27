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
        $status = $result['status'];
        if(is_bool($status)) {
            $status = $status ? 200 : 400;
        }
        return response()->json($result, $status);
    }
}
