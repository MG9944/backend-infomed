<?php

namespace App\Dto\Response;

class MedicalCenterDto
{
    public function __construct(
      private readonly int $id,
      private readonly string $name,
      private readonly string $fullAddress,
      private readonly string $nip,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): string
    {
        return $this->fullAddress;
    }

    public function getNip(): string
    {
        return $this->nip;
    }
}
