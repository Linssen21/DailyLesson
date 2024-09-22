<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rules\File;

class UploadRequest extends ApiRequest
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
            'attachment' => ['required', File::default()->min(100)->max(12 * 1024)]
        ];
    }
}
