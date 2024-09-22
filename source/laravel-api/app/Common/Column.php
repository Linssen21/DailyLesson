<?php

declare(strict_types=1);

namespace App\Common;

class Column
{
    private string $column;
    private ?string $operator;
    private ?string $value;
    private string $boolean;

    private const VALID_OPERATORS = ['=', '<', '>', '<=', '>=', '!=', '<>', 'LIKE', 'IN'];
    private const VALID_BOOLEANS = ['and', 'or'];

    public function __construct(
        string $column,
        ?string $operator = null,
        ?string $value = null,
        string $boolean = 'and'
    ) {
        $this->column = $column;
        $this->operator = $operator;
        $this->value = $value;
        $this->boolean = $boolean;

        $this->validate();
    }

    private function validate(): void
    {
        if (!in_array($this->boolean, self::VALID_BOOLEANS, true)) {
            throw new \InvalidArgumentException("Invalid boolean value: {$this->boolean}. Allowed values are: " . implode(', ', self::VALID_BOOLEANS));
        }

        if ($this->operator !== null && !in_array($this->operator, self::VALID_OPERATORS, true)) {
            throw new \InvalidArgumentException("Invalid operator: {$this->operator}. Allowed operators are: " . implode(', ', self::VALID_OPERATORS));
        }
    }

    public function getColumn(): string
    {
        return $this->column;
    }

    public function getOperator(): ?string
    {
        return $this->operator;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getBoolean(): string
    {
        return $this->boolean;
    }
}
