<?php

namespace App\Dto\Response;

use DateTimeInterface;

class AppointmentDto
{
    public function __construct(
      private readonly int $id,
      private readonly DateTimeInterface $date,
      private readonly string $doctorFullName,
      private readonly string $patientFullName,
      private readonly string $diagnosis,
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

    public function getDoctorFullName(): string
    {
        return $this->doctorFullName;
    }

    public function getPatientFullName(): string
    {
        return $this->patientFullName;
    }

    public function getDiagnosis(): string
    {
        return $this->diagnosis;
    }
}
