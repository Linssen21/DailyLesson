<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use App\Domains\Post\DTO\SlideCreateDto;
use App\Domains\Post\ValueObjects\PostStatus;

class SlideCreateRequest extends ApiRequest
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
            'content' =>  ['nullable', 'string', 'max:2000'],
            'title' => ['required', 'string', 'max:100', 'min:5'],
            'excerpt' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'integer', 'in:0,1,2,3'],
            'slug' => ['nullable', 'string', 'max:50'],
            'ppt' => ['nullable', 'string', 'max:200'],
            'google' => ['nullable','url:http,https'],
            'canva' => ['nullable','url:http,https'],
        ];
    }

    public function toCreateSlide(): SlideCreateDto
    {
        return new SlideCreateDto(
            $this->attributes->get('admin_id'),
            $this->input('content', ''),
            $this->input('title'),
            $this->input('excerpt', ''),
            new PostStatus((int) $this->input('status')),
            $this->input('slug', ''),
            $this->input('ppt', ''),
            $this->input('google', ''),
            $this->input('canva', '')
        );
    }


}
