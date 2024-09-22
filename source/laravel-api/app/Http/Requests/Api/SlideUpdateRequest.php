<?php

namespace App\Http\Requests\Api;

use App\Domains\Post\DTO\PostDto;
use App\Domains\Post\Slides\Template;
use App\Domains\Post\ValueObjects\PostStatus;

class SlideUpdateRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'id' => $this->route('id')
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:posts,id'],
            'content' =>  ['string', 'max:2000'],
            'title' => ['required', 'string', 'max:100', 'min:5'],
            'excerpt' => ['string', 'max:100'],
            'status' => ['required', 'integer', 'in:0,1,2,3'],
            'slug' => ['string', 'max:50'],
            'ppt' => ['nullable', 'string', 'max:200'],
            'google' => ['nullable','url:http,https'],
            'canva' => ['nullable','url:http,https'],
        ];
    }


    public function toPostDto(): PostDto
    {
        return new PostDto(
            $this->attributes->get('admin_id'),
            $this->input('content', ''),
            $this->input('title', ''),
            $this->input('excerpt', ''),
            new PostStatus((int) $this->input('status')),
            $this->input('slug', ''),
        );
    }

    public function getTemplate(): Template
    {
        return new Template(
            $this->input('ppt', ''),
            $this->input('google', ''),
            $this->input('canva', '')
        );
    }

}
