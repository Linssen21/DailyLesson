<?php

declare(strict_types=1);

namespace App\Services;

use App\Common\Service;
use App\Domains\User\DTO\UserAuthDTO;
use App\Domains\User\Service\AdminService as AdminDomainService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminService extends Service
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
                return $this->tokenResponse('', 'Authentication failed, please check your username and password', false);
            }
            DB::commit();
            return $this->tokenResponse($strToken, 'Authentication successful', true);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('applog')->error(
                '[Authentication] An error occurred during authentication',
                ['data' => json_encode($userAuthDTO), 'message: ' => $th->getMessage()]
            );
            return $this->tokenResponse('', 'Authentication failed', false);
        }
    }
}
