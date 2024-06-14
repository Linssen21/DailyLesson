<?php

declare(strict_types=1);

namespace App\Domains\User\DTO;

class UserCreateDTO
{
    private string $name;
    private string $email;
    private string $password;
    private string $password_confirmation;
    private string $display_name;
    private ?int $role_id;

    public function __construct(
        string $name,
        string $email,
        string $display_name,
        string $password,
        ?string $password_confirmation = "",
        ?int $role_id = null
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->password_confirmation = $password_confirmation;
        $this->display_name = $display_name;
        $this->role_id = $role_id;
    }

    // Getter methods
    public function getName(): string
    {
        return $this->name;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPasswordConfirmation(): string
    {
        return $this->password_confirmation;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getDisplayName(): string
    {
        return $this->display_name;
    }

    public function getRoleId(): int
    {
        return $this->role_id;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'email' => $this->email,
            'display_name' => $this->display_name,
            'role_id' => $this->role_id,
        ];
    }


}
