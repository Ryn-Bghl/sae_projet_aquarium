<?php

namespace App\Entity;

use App\Repository\DataRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DataRepository::class)]
class Data
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(nullable: false)]
    private int $id = 0;

    #[ORM\Column(nullable: false)]
    private \DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(targetEntity: Aquarium::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $aquarium;

    #[ORM\Column(nullable: false)]
    private float $temp = 26.0;

    #[ORM\Column(nullable: false)]
    private float $ph = 7.0;

    #[ORM\Column(nullable: false)]
    private float $kh = 4.0;

    #[ORM\Column(nullable: false)]
    private float $gh = 8.0;

    #[ORM\Column(nullable: false)]
    private float $cl2 = 0.0;

    #[ORM\Column(nullable: false)]
    private float $no2 = 0.0;

    #[ORM\Column(nullable: false)]
    private float $no3 = 0.0;

    #[ORM\Column(nullable: false)]
    private string $observation = '';

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function setObservation(?string $observation): static
    {
        $this->observation = $observation;

        return $this;
    }

    public function getKh(): ?float
    {
        return $this->kh;
    }

    public function setKh(?float $kh): static
    {
        $this->kh = $kh;

        return $this;
    }

    public function getGh(): ?float
    {
        return $this->gh;
    }

    public function setGh(?float $gh): static
    {
        $this->gh = $gh;

        return $this;
    }

    public function getPh(): ?float
    {
        return $this->ph;
    }

    public function setPh(?float $ph): static
    {
        $this->ph = $ph;

        return $this;
    }

    public function getCl2(): ?float
    {
        return $this->cl2;
    }

    public function setCl2(?float $cl2): static
    {
        $this->cl2 = $cl2;

        return $this;
    }

    public function getNo2(): ?float
    {
        return $this->no2;
    }

    public function setNo2(?float $no2): static
    {
        $this->no2 = $no2;

        return $this;
    }

    public function getNo3(): ?float
    {
        return $this->no3;
    }

    public function setNo3(?float $no3): static
    {
        $this->no3 = $no3;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAquarium(): ?Aquarium
    {
        return $this->aquarium;
    }

    public function setAquarium(?Aquarium $aquarium): static
    {
        $this->aquarium = $aquarium;

        return $this;
    }

    public function getTemp(): ?float
    {
        return $this->temp;
    }

    public function setTemp(?float $temp): static
    {
        $this->temp = $temp;

        return $this;
    }
}
