<?php

namespace App\Http\Requests\Api;

use App\Common\Column;
use Illuminate\Support\Collection;

class SlideQueryParamsRequest extends ApiRequest
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
        $validColumns = ['id', 'author_id', 'content', 'title', 'status', 'slug', 'created_at', 'updated_at'];

        return [
            'id' => ['nullable', 'integer'],
            'author_id' => ['nullable', 'integer'],
            'content' => ['nullable', 'string'],
            'title' => ['nullable', 'string'],
            'status' => ['nullable', 'integer', 'in:0,1,2,3'],
            'slug' => ['nullable', 'string'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer'],
            'order' => ['nullable', 'array'],
            'order.*' => ['string', 'in:created_at,updated_at,id'],
            'direction' => ['nullable', 'string', 'in:asc,desc'],
            'fields' => ['nullable', 'string'],
            'fields.*' => ['string', 'in:' . implode(',', $validColumns)]
        ];
    }

    /**
     * Get columns that can be use on fetching by a column data
     *
     * @return Collection
     */
    public function getColumns(): Collection
    {
        $columns = collect();

        $lInput = $this->only(['title', 'content']);
        foreach ($lInput as $key => $value) {
            $columns->add(new Column($key, 'LIKE', "%{$value}%"));
        }

        $eqInput = $this->only(['id', 'author_id', 'status', 'slug']);
        foreach ($eqInput as $key => $value) {
            $columns->add(new Column($key, '=', $value));
        }

        return $columns;
    }

    /**
     * Get the fetch order data
     *
     * @return array
     */
    public function getOrder(): array
    {
        $order = $this->input('order', 'created_at');
        $direction = $this->input('direction', 'asc');

        return [$order => $direction];
    }

    /**
     * Get fields to fetch
     *
     * @return array
     */
    public function getFields(): array
    {
        $fieldsString = $this->input('fields');
        return $fieldsString ? explode(',', $fieldsString) : ['*'];
    }
}
