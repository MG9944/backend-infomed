<?php

namespace App\Dto\Response;

class PatientCardPatientInfoDto
{
    public function __construct(
        private readonly int $id,
        private readonly string $pesel,
        private readonly string $firstname,
        private readonly string $lastname
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
}
