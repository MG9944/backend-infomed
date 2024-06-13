<?php

namespace App\Dto\Response;

class MedicamenteDto
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly string $category,
        private readonly string $atcCode,
        private readonly string $figure,
        private readonly string $packageContents
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

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getAtcCode(): string
    {
        return $this->atcCode;
    }

    public function getFigure(): string
    {
        return $this->figure;
    }

    public function getPackageContents(): string
    {
        return $this->packageContents;
    }
}
