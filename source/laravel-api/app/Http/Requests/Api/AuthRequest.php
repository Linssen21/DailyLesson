<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use App\Domains\User\DTO\UserAuthDTO;
use App\Http\Requests\Api\ApiRequest;
use Illuminate\Validation\Rules\Password;

class AuthRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
           'email' => ['required', 'string', 'email', 'max:255'],
           'password' => ['required', 'min:8', Password::defaults()]
        ];
    }

    public function toDto(): UserAuthDTO
    {
        return new UserAuthDTO(
            $this->input('email'),
            $this->input('password')
        );
    }
}
