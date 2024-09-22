<?php

declare(strict_types=1);

namespace App\Common;

use Illuminate\Support\Collection;

class QueryParams
{
    /** @var Collection<int, Column> */
    private readonly Collection $columns;
    private readonly array $fields;
    private readonly int $page;
    private readonly int $perPage;
    private readonly mixed $operator;
    private readonly array $order;

    public function __construct(
        Collection $columns,
        array $fields = ['*'],
        int $page = 1,
        int $perPage = 10,
        mixed $operator = "=",
        array $order = ['created_at' => 'asc']
    ) {

        if (!$columns->every(fn ($item) => $item instanceof Column)) {
            throw new \InvalidArgumentException('All elements in the columns collection must be instances of Column.');
        }

        $this->columns = $columns;
        $this->fields = $fields;
        $this->page = $page;
        $this->perPage = $perPage;
        $this->operator = $operator;
        $this->order = $order;
    }

    /**
     * Get the list of columns
     *
     * @return Collection<int, Column>
     */
    public function getColumns(): Collection
    {
        return $this->columns;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getOperator(): mixed
    {
        return $this->operator;
    }

    public function getOrder(): array
    {
        return $this->order;
    }

    public function getFields(): array
    {
        return $this->fields;
    }
}
