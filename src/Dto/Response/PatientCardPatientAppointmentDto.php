<?php

namespace App\Dto\Response;

use DateTimeInterface;

class PatientCardPatientAppointmentDto
{
    public function __construct(
        private readonly int $id,
        private readonly DateTimeInterface $date,
        private readonly string $diagnosis,
        private readonly float $temperature,
        private readonly string $bloodPressure,
        private readonly int $sugarLevel,
        private readonly ?string $medicamenteDescription,
        private readonly string $description
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDate(): string
    {
        return $this->date->format('Y-m-d H:i');
    }

    public function getDiagnosis(): string
    {
        return $this->diagnosis;
    }

    public function getTemperature(): float
    {
        return $this->temperature;
    }

    public function getBloodPressure(): string
    {
        return $this->bloodPressure;
    }

    public function getSugarLevel(): int
    {
        return $this->sugarLevel;
    }

    public function getMedicamenteDescription(): ?string
    {
        return $this->medicamenteDescription;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
