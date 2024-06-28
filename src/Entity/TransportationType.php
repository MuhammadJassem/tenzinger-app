<?php

declare(strict_types=1);

namespace App\Entity;

use App\Domain\TransportationType as DomainTransportationType;
use App\Repository\TransportationTypeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: TransportationTypeRepository::class)]
class TransportationType
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    protected UuidInterface|null $id;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\NotBlank]
    #[Assert\Choice(callback: [DomainTransportationType::class, 'toArray'], message: 'Invalid transportation type.')]
    private string $code = '';

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Assert\PositiveOrZero]
    private ?int $minDistance = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Assert\PositiveOrZero]
    private ?int $maxDistance = null;

    #[ORM\Column(type: Types::FLOAT)]
    #[Assert\Positive]
    private ?float $cost = null;

    public function __construct()
    {
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getMinDistance(): ?int
    {
        return $this->minDistance;
    }

    public function setMinDistance(?int $minDistance = null): self
    {
        $this->minDistance = $minDistance;

        return $this;
    }

    public function getMaxDistance(): ?int
    {
        return $this->maxDistance;
    }

    public function setMaxDistance(?int $maxDistance = null): self
    {
        $this->maxDistance = $maxDistance;

        return $this;
    }

    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function setCost(?float $cost): self
    {
        $this->cost = $cost;

        return $this;
    }
}
