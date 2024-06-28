<?php

declare(strict_types=1);

namespace App\Entity;

use App\Domain\TransportationType;
use App\Repository\CommutingCompensationRepository;
use App\Utils\CompensationTimeHelper;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: CommutingCompensationRepository::class)]
class CommutingCompensation
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    protected UuidInterface|null $id;

    #[ORM\ManyToOne(targetEntity: Employee::class, fetch: 'EAGER', inversedBy: 'compensationRecords')]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Employee $employee = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\Choice(callback: [CompensationTimeHelper::class, 'getValidMonths'], message: 'Invalid month.')]
    private ?int $month = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\Choice(callback: [CompensationTimeHelper::class, 'getValidCompensationYears'], message: 'Invalid year.')]
    private ?int $year = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\NotBlank]
    #[Assert\Choice(callback: [TransportationType::class, 'toArray'], message: 'Invalid transportation type.')]
    private string $transportationType = '';

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $numberOfDays = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $commutedDistance = null;

    #[ORM\Column(type: Types::FLOAT)]
    private ?float $compensationAmount = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $paidAt;

    public function __construct()
    {
        $this->paidAt = new DateTimeImmutable();
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(Employee $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getMonth(): ?int
    {
        return $this->month;
    }

    public function setMonth(int $month): self
    {
        $this->month = $month;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getTransportationType(): ?string
    {
        return $this->transportationType;
    }

    public function setTransportationType(string $transportationType): self
    {
        $this->transportationType = $transportationType;

        return $this;
    }

    public function getNumberOfDays(): ?int
    {
        return $this->numberOfDays;
    }

    public function setNumberOfDays(int $numberOfDays): self
    {
        $this->numberOfDays = $numberOfDays;

        return $this;
    }

    public function getCommutedDistance(): ?int
    {
        return $this->commutedDistance;
    }

    public function setCommutedDistance(int $commutedDistance): self
    {
        $this->commutedDistance = $commutedDistance;

        return $this;
    }

    public function getCompensationAmount(): ?float
    {
        return $this->compensationAmount;
    }

    public function setCompensationAmount(float $compensationAmount): self
    {
        $this->compensationAmount = $compensationAmount;

        return $this;
    }

    public function getPaidAt(): DateTimeImmutable
    {
        return $this->paidAt;
    }

    public function setPaidAt(DateTimeImmutable $recordedOn): self
    {
        $this->paidAt = $recordedOn;

        return $this;
    }
}
