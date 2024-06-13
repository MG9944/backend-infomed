<?php

namespace App\Entity;

use App\Repository\MedicalCenterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MedicalCenterRepository::class)
 */
class MedicalCenter
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=9)
     */
    private $nip;

    /**
     * @ORM\OneToMany(targetEntity=Patient::class, mappedBy="medicalCenter")
     */
    private $patients;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="medicalCenter")
     */
    private $users;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $postCode;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $city;

    public function __construct()
    {
        $this->patients = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getFullName(): string
    {
        return sprintf("%s\n%s", $this->name, $this->address);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getNip(): ?string
    {
        return $this->nip;
    }

    public function setNip(string $nip): self
    {
        $this->nip = $nip;

        return $this;
    }

    /**
     * @return Collection<int, Patient>
     */
    public function getPatients(): Collection
    {
        return $this->patients;
    }

    public function addPatient(Patient $patient): self
    {
        if (!$this->patients->contains($patient)) {
            $this->patients[] = $patient;
            $patient->setMedicalCenter($this);
        }

        return $this;
    }

    public function removePatient(Patient $patient): self
    {
        if ($this->patients->removeElement($patient)) {
            // set the owning side to null (unless already changed)
            if ($patient->getMedicalCenter() === $this) {
                $patient->setMedicalCenter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setMedicalCenter($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getMedicalCenter() === $this) {
                $user->setMedicalCenter(null);
            }
        }

        return $this;
    }

    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    public function setPostCode(string $postCode): self
    {
        $this->postCode = $postCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getFullAddress(): string
    {
        return $this->address . ' ' . $this->postCode . ' '. $this->city;
    }
}
