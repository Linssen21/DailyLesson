<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use App\Domains\User\DTO\UserCreateDTO;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends ApiRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'display_name' => ['required', 'string', 'max:255']
        ];
    }

    public function toDto(): UserCreateDTO
    {
        return new UserCreateDTO(
            $this->input('name'),
            $this->input('email'),
            $this->input('display_name'),
            $this->input('password'),
            $this->input('password_confirmation'),
        );
    }
}
