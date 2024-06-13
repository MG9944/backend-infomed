<?php

namespace App\Dto\Response;

class UserDto
{
    public function __construct(
        private readonly int $id,
        private readonly string $email,
        private readonly string $firstname,
        private readonly string $lastname,
        private readonly ?string $phoneNumber,
        private readonly string $specialisation,
        private readonly string $medicalCenter,
        private readonly int $isActive,
        private readonly array $role,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function getSpecialisation(): string
    {
        return $this->specialisation;
    }

    public function getMedicalCenter(): string
    {
        return $this->medicalCenter;
    }

    public function getIsActive(): int
    {
        return $this->isActive;
    }

    public function getRole(): array
    {
        return $this->role;
    }
}
