<?php

declare(strict_types=1);

namespace App\Entity;

use App\Domain\TransportationType;
use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    protected UuidInterface|null $id;

    #[ORM\Column(type: Types::INTEGER, unique: true)]
    #[Assert\Positive]
    private ?int $employeeNumber = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\NotBlank]
    private string $firstName = '';

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\NotBlank]
    private string $lastName = '';

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\Positive]
    private ?int $commutingDistance = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\NotBlank]
    #[Assert\Choice(callback: [TransportationType::class, 'toArray'], message: 'Invalid transportation type.')]
    private string $transportationType = '';

    #[ORM\Column(type: Types::FLOAT)]
    #[Assert\Range(notInRangeMessage: "The value must be between {{ min }} and {{ max }}.", min: 0, max: 5)]
    private ?float $weeklyOfficeWorkingDays = null;


    /**
     * @var Collection<int, CommutingCompensation>
     */
    #[ORM\OneToMany(targetEntity: CommutingCompensation::class, mappedBy: 'employee')]
    private Collection $compensationRecords;

    public function __construct()
    {
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getEmployeeNumber(): ?int
    {
        return $this->employeeNumber;
    }

    public function setEmployeeNumber(int $employeeNumber): self
    {
        $this->employeeNumber = $employeeNumber;

        return $this;
    }
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getName(): string
    {
        return implode(' ', [$this->firstName, $this->lastName]);
    }

    public function getCommutingDistance(): ?int
    {
        return $this->commutingDistance;
    }

    public function setCommutingDistance(int $commutingDistance): self
    {
        $this->commutingDistance = $commutingDistance;

        return $this;
    }

    public function getTransportationType(): string
    {
        return $this->transportationType;
    }

    public function setTransportationType(string $transportationType): self
    {
        $this->transportationType = $transportationType;

        return $this;
    }

    public function getWeeklyOfficeWorkingDays(): ?float
    {
        return $this->weeklyOfficeWorkingDays;
    }

    public function setWeeklyOfficeWorkingDays(float $weeklyOfficeWorkingDays): self
    {
        $this->weeklyOfficeWorkingDays = $weeklyOfficeWorkingDays;

        return $this;
    }

    public function getCompensationRecords(): Collection
    {
        return $this->compensationRecords;
    }
}
