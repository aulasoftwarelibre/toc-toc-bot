<?php

declare(strict_types=1);

namespace App\Message;

final class AddUserMessage
{
    private int $id;
    private ?string $username;
    private string $firstName;
    private ?string $lastName;

    public function __construct(int $id, ?string $username, string $firstName, ?string $lastName)
    {
        $this->id        = $id;
        $this->username  = $username;
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }
}
