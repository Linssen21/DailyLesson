<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Common\Controller;
use App\Http\Requests\Api\AuthRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * [API] UserController
 *
 * API Controller for User
 *
 * @ticket Feature/DL-2
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    /**
     * Login controller
     *
     * @ticket Feature/DL-2
     *
     * @param AuthRequest $request
     * @return JsonResponse
     */
    public function login(AuthRequest $request): JsonResponse
    {
        $aryAuthRes = $this->userService->authentication($request->toDto());
        return $this->response($aryAuthRes);
    }

    /**
     * Register controller
     *
     * @ticket Feature/DL-2
     *
     * @param RegisterRequest $registerRequest
     * @return JsonResponse
     */
    public function register(RegisterRequest $registerRequest): JsonResponse
    {
        $aryRegRes = $this->userService->registration($registerRequest->toDto());
        return $this->response($aryRegRes);
    }

    public function logout(Request $request): JsonResponse
    {
        $strToken = $request->bearerToken();
        $aryRes = $this->userService->revokeToken($strToken);
        return $this->response($aryRes);
    }

    /**
     * Re-send verification by email address
     *
     * @ticket Feature/DL-2
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendVerificationEmail(Request $request): JsonResponse
    {
        $email = $request->input('email');
        $aryRes = $this->userService->sendVerification($email);

        return $this->response($aryRes);
    }

    /**
     * Redirect to social provider
     *
     * @ticket Feature/DL-3
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function redirect(Request $request): JsonResponse
    {
        $provider = $request->route('provider');
        $aryRes = $this->userService->redirectToProvider($provider);

        $intStatus = $aryRes['status'] == config('constants.STATUS_SUCCESS') ? 200 : 400;
        return response()->json($aryRes, $intStatus);
    }


    /**
     * Execute callback provider
     *
     * @ticket Feature/DL-3
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function callback(Request $request): JsonResponse
    {
        $provider = $request->route('provider');
        $aryRes = $this->userService->handleProviderCallback($provider);

        return $this->response($aryRes);
    }

}
