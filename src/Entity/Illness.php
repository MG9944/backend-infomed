<?php

namespace App\Entity;

use App\Repository\IllnessRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IllnessRepository::class)
 */
class Illness
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
     * @ORM\ManyToMany(targetEntity=Medicamente::class, inversedBy="illnesses")
     */
    private $medicamente;


    public function __construct()
    {
        $this->medicamente = new ArrayCollection();
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

    /**
     * @return Collection<int, Medicamente>
     */
    public function getMedicamente(): Collection
    {
        return $this->medicamente;
    }

    public function addMedicamente(Medicamente $medicamente): self
    {
        if (!$this->medicamente->contains($medicamente)) {
            $this->medicamente[] = $medicamente;
        }

        return $this;
    }

    public function removeMedicamente(Medicamente $medicamente): self
    {
        $this->medicamente->removeElement($medicamente);

        return $this;
    }

}
