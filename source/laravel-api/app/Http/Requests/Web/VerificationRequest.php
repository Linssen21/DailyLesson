<?php

declare(strict_types=1);

namespace App\Http\Requests\Web;

use App\Domains\User\Contracts\UserRepository;
use Illuminate\Foundation\Http\FormRequest;

class VerificationRequest extends FormRequest
{
    public function __construct(private UserRepository $userRepository)
    {

    }
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $intUserId = (int) $this->route('id');
        $strHash = (string) $this->route('hash');

        // Find and return user
        $user = $this->userRepository->findById($intUserId);

        if (! hash_equals((string) $user->getKey(), (string) $intUserId)) {
            return false;
        }

        if (! hash_equals(sha1($user->getEmailForVerification()), (string) $strHash)) {
            return false;
        }

        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [];
    }


}
