<?php

namespace App\Entity;

use App\Enum\HealthStatus;
use App\Repository\DinosaurRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;

#[ORM\Entity(repositoryClass: DinosaurRepository::class)]
class Dinosaur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id;

    #[ORM\Column(length: 125)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $genus = null;

    #[ORM\Column]
    private ?int $length = null;

    #[ORM\Column(length: 255)]
    private ?string $enclosure = null;
    private HealthStatus $health = HealthStatus::HEALTHY;

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

    public function getGenus(): ?string
    {
        return $this->genus;
    }

    public function setGenus(string $genus): static
    {
        $this->genus = $genus;

        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(int $length): static
    {
        $this->length = $length;

        return $this;
    }

    public function getEnclosure(): ?string
    {
        return $this->enclosure;
    }

    public function setEnclosure(string $enclosure): static
    {
        $this->enclosure = $enclosure;

        return $this;
    }

    public function getSizeDescription(): ?string
    {
       return ($this->getLength() >= 10) ? 'Large' : (($this->getLength() >= 5 and $this->getLength() <= 9) ? 'Medium' : "Small");
    }

    public function isAcceptingVisitors(): bool
    {
        return $this->health !== HealthStatus::SICK;
    }

    public function setHealth(HealthStatus $health): static
    {
        $this->health = $health;

        return $this;
    }
}
