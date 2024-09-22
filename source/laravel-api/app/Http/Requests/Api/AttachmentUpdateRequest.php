<?php

namespace App\Http\Requests\Api;

use App\Domains\Post\Common\ImageDetail;
use App\Domains\Post\Common\MediaDetail;
use App\Domains\Post\DTO\AttachmentUpdateDto;

class AttachmentUpdateRequest extends ApiRequest
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
            'name' => ['required', 'string', 'max:100'],
            'caption' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:200'],
            'title' => ['nullable', 'string', 'max:100'],
            'altText' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function getMediaDetail(): MediaDetail
    {
        $name = $this->input('name');
        $caption = $this->input('caption');
        $description = $this->input('description');
        return new MediaDetail($name, $caption, $description);
    }

    public function imageDetail(): ImageDetail
    {
        $title = $this->input('title');
        $altText = $this->input('altText');
        return new ImageDetail($title, $altText);
    }

    public function getAttachment(): AttachmentUpdateDto
    {
        // Media
        $media = $this->getMediaDetail();

        // Image
        $image = null;
        if ($this->filled('title')) {
            $image = $this->imageDetail();
        }

        return new AttachmentUpdateDto($this->input('id'), $media, $image);
    }
}
