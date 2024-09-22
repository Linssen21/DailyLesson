<?php

namespace App\Http\Requests\Api;

use App\Common\Column;
use Illuminate\Support\Collection;

class QueryParamsRequest extends ApiRequest
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
            'id' => ['nullable', 'integer'],
            'author_id' => ['nullable', 'integer'],
            'content' => ['nullable', 'string'],
            'title' => ['nullable', 'string'],
            'status' => ['nullable', 'integer', 'in:0,1,2,3'],
            'type' => ['nullable', 'string'],
            'slug' => ['nullable', 'string'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer'],
            'order' => ['nullable', 'array'],
            'order.*' => ['string', 'in:created_at,updated_at,id'],
            'direction' => ['nullable', 'string', 'in:asc,desc'],
            'fields' => ['nullable', 'array'],
            'fields.*' => ['string']
        ];
    }

    /**
     * Return an empty collection or a collection of columns
     *
     * @return Collection<int, Column>
     */
    public function getColumns(): Collection
    {
        $columns = collect();

        $lInput = $this->only(['title', 'content']);
        foreach ($lInput as $key => $value) {
            $columns->add(new Column($key, 'LIKE', "%{$value}%"));
        }

        $eqInput = $this->only(['id', 'author_id', 'status', 'slug', 'type']);
        foreach ($eqInput as $key => $value) {
            $columns->add(new Column($key, '=', $value));
        }

        return $columns;
    }

    public function getOrder(): array
    {
        $order = $this->input('order', 'created_at');
        $direction = $this->input('direction', 'asc');

        return [$order => $direction];
    }
}
