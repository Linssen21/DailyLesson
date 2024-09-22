<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Domains\User\Service\AdminService;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Admin Authentication Middleware
 *
 * @ticket Feature/DL-2
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class AdminAuthentication
{
    private string $adminToken;

    public function __construct(private AdminService $adminService)
    {
        $this->adminToken = config('constants.ADMIN_TOKEN');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get Bearer token
        $token = $request->bearerToken();
        if (empty($token)) {
            return $this->unauthorizedResponse("No bearer token");
        }

        // Fetch token from Personal access token
        $tokenObj = PersonalAccessToken::findToken($token);

        if (empty($tokenObj)) {
            return $this->unauthorizedResponse("Token not found");
        }

        $response = $this->checkToken($tokenObj);
        if (!empty($response)) {
            return $this->unauthorizedResponse($response);
        }

        $request->attributes->add(['admin_id' => $tokenObj->tokenable_id]);

        return $next($request);
    }

    /**
     * Token check
     *
     * @param string $token
     * @return string
     */
    private function checkToken(object $tokenObj): string
    {

        // Check if token name is the same with ADMIN_TOKEN
        if ($tokenObj->name != $this->adminToken) {
            return "User is not an admin";
        }

        if (Carbon::now()->greaterThan($tokenObj->expires_at)) {
            return "Authentication token expired";
        }

        // Check the user authority if it's really admin
        $isAdmin = $this->adminService->isAdmin($tokenObj->tokenable_id);

        if (!$isAdmin) {
            return "The user is not an admin";
        }

        return "";
    }

    private function unauthorizedResponse(string $message): Response
    {
        Log::channel('applog')->error(
            '[Admin Authentication] Unauthorized error',
            ['message' => $message]
        );
        return response()->json(['error' => 'Unauthorized', 'message' => $message, 'status' => false], 401);
    }
}
