<?php

declare(strict_types=1);

namespace App\Services;

use App\Domains\User\DTO\UserAuthDTO;
use App\Domains\User\Service\AdminService as AdminDomainService;
use Illuminate\Support\Facades\DB;
use Log;

class AdminService
{
    public function __construct(private AdminDomainService $adminService)
    {
    }

    public function authentication(UserAuthDTO $userAuthDTO): array
    {
        try {
            DB::beginTransaction();
            $strToken = $this->adminService->authentication($userAuthDTO);
            if (empty($strToken)) {
                return [
                    'token' => '',
                    'message' => 'Authentication failed, please check your username and password',
                    'status' => config('constants.STATUS_FAILED')
                ];
            }
            DB::commit();
            return [
                'token' => $strToken,
                'message' => 'Authentication successful',
                'status' => config('constants.STATUS_SUCCESS')
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('applog')->error(
                '[Authentication] An error occurred during authentication',
                ['data' => json_encode($userAuthDTO), 'message: ' => $th->getMessage()]
            );
            return [
                'token' => '',
                'message' => 'Authentication failed',
                'status' => config('constants.STATUS_FAILED')
            ];
        }
    }
}
