<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthRequest;
use App\Services\AdminService;
use Illuminate\Http\JsonResponse;

/**
 * [API] Admin Controller
 *
 * API Controller for Admin
 *
 * @ticket Feature/DL-2
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class AdminController extends Controller
{
    public function __construct(
        private AdminService $adminService
    ) {
    }

    public function login(AuthRequest $request): JsonResponse
    {
        $aryAuthRes = $this->adminService->authentication($request->toDto());
        return $this->authResponse($aryAuthRes);
    }

    /**
     * Authentication response utility function for login and registration
     *
     * @ticket Feature/DL-2
     *
     * @param array $aryData
     * @return JsonResponse
     */
    private function authResponse(array $aryData): JsonResponse
    {
        $intStatus = $aryData['status'] == config('constants.STATUS_SUCCESS') ? 200 : 500;
        return response()->json([
            'status' => $aryData['status'],
            'message' => $aryData['message'],
            'token' => $aryData['token']
        ], $intStatus);
    }
}
