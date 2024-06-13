<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AppointmentRepository::class)
 */
class Appointment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class, inversedBy="appointments")
     */
    private $idPatient;

    /**
     * @ORM\Column(type="datetime")
     */
    private $appointmentDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $diagnosis;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Temperature;

    /**
     * @ORM\Column(type="string",length=7, nullable=true)
     */
    private $bloodPressure;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sugarLevel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $medicamenteDescription;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="appointments")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    public function __construct()
    {
        $this->doctor = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdPatient(): ?Patient
    {
        return $this->idPatient;
    }

    public function setIdPatient(?Patient $idPatient): self
    {
        $this->idPatient = $idPatient;

        return $this;
    }

    public function getAppointmentDate(): ?\DateTimeInterface
    {
        return $this->appointmentDate;
    }

    public function setAppointmentDate(\DateTimeInterface $appointmentDate): self
    {
        $this->appointmentDate = $appointmentDate;

        return $this;
    }

    public function getDiagnosis(): ?string
    {
        return $this->diagnosis;
    }

    public function setDiagnosis(string $diagnosis): self
    {
        $this->diagnosis = $diagnosis;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTemperature(): ?float
    {
        return $this->Temperature;
    }

    public function setTemperature(?float $Temperature): self
    {
        $this->Temperature = $Temperature;

        return $this;
    }

    public function getBloodPressure(): ?string
    {
        return $this->bloodPressure;
    }

    public function setBloodPressure(?string $bloodPressure): self
    {
        $this->bloodPressure = $bloodPressure;

        return $this;
    }

    public function getSugarLevel(): ?int
    {
        return $this->sugarLevel;
    }

    public function setSugarLevel(?int $sugarLevel): self
    {
        $this->sugarLevel = $sugarLevel;

        return $this;
    }

    public function getMedicamenteDescription(): ?string
    {
        return $this->medicamenteDescription;
    }

    public function setMedicamenteDescription(?string $medicamenteDescription): self
    {
        $this->medicamenteDescription = $medicamenteDescription;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
