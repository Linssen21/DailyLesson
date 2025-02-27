<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Requests\Web\VerificationRequest;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;

/**
 * [WEB] UserController
 *
 * WEB Controller for User
 *
 * @ticket Feature/DL-2
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class UserController
{
    public function __construct(private UserService $userService)
    {
    }

    /**
     * Email verification
     *
     * @ticket Feature/DL-2
     *
     * @param VerificationRequest $verificationRequest
     * @return RedirectResponse
     */
    public function verificationEmail(VerificationRequest $verificationRequest): RedirectResponse
    {
        $intId = (int) $verificationRequest->route('id');
        $aryRes = $this->userService->verify($intId);

        if ($aryRes['status'] == config('constants.STATUS_FAILED')) {
            abort(422);
        }

        return redirect()->intended(
            config('app.frontend_url').'?verified=1'
        );
    }
}
