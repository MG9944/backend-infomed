<?php

namespace App\Entity;

use App\Repository\MedicamenteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MedicamenteRepository::class)
 */
class Medicamente
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
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $atcCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $figure;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $packageContents;

    /**
     * @ORM\ManyToMany(targetEntity=Illness::class, mappedBy="medicamente")
     */
    private $illnesses;



    public function __construct()
    {
        $this->illnesses = new ArrayCollection();
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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }


    public function getAtcCode(): ?string
    {
        return $this->atcCode;
    }

    public function setAtcCode(string $atcCode): self
    {
        $this->atcCode = $atcCode;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullaName): self
    {
        $this->fullName = $fullaName;

        return $this;
    }

    public function getFigure()
    {
        return $this->figure;
    }

    public function setFigure($figure): void
    {
        $this->figure = $figure;
    }

    public function getPackageContents()
    {
        return $this->packageContents;
    }

    public function setPackageContents($packageContents): void
    {
        $this->packageContents = $packageContents;
    }

    /**
     * @return Collection<int, Illness>
     */
    public function getIllnesses(): Collection
    {
        return $this->illnesses;
    }

    public function addIllness(Illness $illness): self
    {
        if (!$this->illnesses->contains($illness)) {
            $this->illnesses[] = $illness;
            $illness->addMedicamente($this);
        }

        return $this;
    }

    public function removeIllness(Illness $illness): self
    {
        if ($this->illnesses->removeElement($illness)) {
            $illness->removeMedicamente($this);
        }

        return $this;
    }

}
