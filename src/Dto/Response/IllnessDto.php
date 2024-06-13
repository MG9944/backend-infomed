<?php

namespace App\Dto\Response;

class IllnessDto
{
    public function __construct(
      private readonly int $id,
      private readonly string $name,
      private readonly string $category,
      private readonly string $medicamente,
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

    public function getMedicamente(): string
    {
        return $this->medicamente;
    }
}
