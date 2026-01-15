<?php

namespace App\Entity;

use App\Repository\AquariumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AquariumRepository::class)]
class Aquarium
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $capacity = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    /**
     * @var Collection<int, Fish>
     */
    #[ORM\OneToMany(targetEntity: Fish::class, mappedBy: 'aquarium', orphanRemoval: true)]
    private Collection $fishes;

    /**
     * @var Collection<int, Data>
     */
    #[ORM\OneToMany(targetEntity: Data::class, mappedBy: 'aquarium', orphanRemoval: true)]
    private Collection $data;

    public function __construct()
    {
        $this->fishes = new ArrayCollection();
        $this->data = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCapacity(): ?float
    {
        return $this->capacity;
    }

    public function setCapacity(float $capacity): static
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Fish>
     */
    public function getFishes(): Collection
    {
        return $this->fishes;
    }

    public function addFish(Fish $fish): static
    {
        if (!$this->fishes->contains($fish)) {
            $this->fishes->add($fish);
            $fish->setAquarium($this);
        }

        return $this;
    }

    public function removeFish(Fish $fish): static
    {
        if ($this->fishes->removeElement($fish)) {
            // set the owning side to null (unless already changed)
            if ($fish->getAquarium() === $this) {
                $fish->setAquarium(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Data>
     */
    public function getData(): Collection
    {
        return $this->data;
    }

    public function addData(Data $data): static
    {
        if (!$this->data->contains($data)) {
            $this->data->add($data);
            $data->setAquarium($this);
        }

        return $this;
    }

    public function removeData(Data $data): static
    {
        if ($this->data->removeElement($data)) {
            // set the owning side to null (unless already changed)
            if ($data->getAquarium() === $this) {
                $data->setAquarium(null);
            }
        }

        return $this;
    }
}