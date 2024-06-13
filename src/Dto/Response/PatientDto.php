<?php

namespace App\Dto\Response;

class PatientDto
{
    public function __construct(
        private readonly int $id,
        private readonly string $pesel,
        private readonly string $firstname,
        private readonly string $lastname,
        private readonly string $street,
        private readonly string $postCode,
        private readonly string $city,
        private readonly string $phoneNumber
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPesel(): string
    {
        return $this->pesel;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getPostCode(): string
    {
        return $this->postCode;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }
}
