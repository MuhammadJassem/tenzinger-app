<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\DTO\CompensationRequest;
use App\Entity\CommutingCompensation;
use App\Entity\Employee;
use App\Entity\TransportationType;
use App\Repository\CommutingCompensationRepository;
use App\Repository\EmployeeRepository;
use App\Repository\TransportationTypeRepository;
use App\Service\CompensationService;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CompensationServiceTest extends TestCase
{
    private MockObject&EmployeeRepository $employeeRepositoryMock;
    private MockObject&CommutingCompensationRepository $compensationRepositoryMock;
    private MockObject&TransportationTypeRepository $transportRepositoryMock;
    private MockObject&ValidatorInterface $validatorMock;
    private CompensationService $compensationService;

    protected function setUp(): void
    {
        $this->employeeRepositoryMock = $this->createMock(EmployeeRepository::class);
        $this->compensationRepositoryMock = $this->createMock(CommutingCompensationRepository::class);
        $this->transportRepositoryMock = $this->createMock(TransportationTypeRepository::class);
        $this->validatorMock = $this->createMock(ValidatorInterface::class);

        $this->compensationService = new CompensationService(
            $this->employeeRepositoryMock,
            $this->compensationRepositoryMock,
            $this->transportRepositoryMock,
            $this->validatorMock
        );
    }

    public function testGetCompensationsWithInvalidYear(): void
    {
        $compensationRequest = new CompensationRequest();
        $compensationRequest->year = 10000;
        $compensationRequest->month = 6;

        $errors = new ConstraintViolationList([
            new ConstraintViolation('The year must be within the last 10 years.', null, [], '', 'year', null),
        ]);

        $this->validatorMock->expects($this->once())
            ->method('validate')
            ->with($this->callback(function ($object) use ($compensationRequest) {
                return $object->year === $compensationRequest->year && $object->month === $compensationRequest->month;
            }))
            ->willReturn($errors);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid year or month provided.');

        $this->compensationService->getCompensations($compensationRequest->year, $compensationRequest->month);
    }

    public function testGetCompensationsWithInvalidMonth(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $compensationRequest = new CompensationRequest();
        $compensationRequest->year = 2023;
        $compensationRequest->month = 13;

        $errors = new ConstraintViolationList([
            new ConstraintViolation('Invalid month.', null, [], '', 'month', null),
        ]);

        $this->validatorMock->expects($this->once())
            ->method('validate')
            ->with($compensationRequest)
            ->willReturn($errors);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid year or month provided.');

        $this->compensationService->getCompensations($compensationRequest->year, $compensationRequest->month);
    }

    public function testGetCompensations(): void
    {
        $year = 2023;
        $month = 6;
        $employee = new Employee();
        $employee->setWeeklyOfficeWorkingDays(3);
        $employee->setCommutingDistance(3);

        $this->employeeRepositoryMock->method('getEmployees')
            ->willReturn([$employee]);

        $transportationMock = $this->createMock(TransportationType::class);
        $this->transportRepositoryMock->method('getTransportation')
            ->willReturn($transportationMock);

        $this->compensationRepositoryMock->expects($this->once())
            ->method('create')
            ->willReturn(new CommutingCompensation());

        $compensations = $this->compensationService->getCompensations($year, $month);

        $this->assertIsArray($compensations);
        $this->assertCount(1, $compensations);
        $this->assertInstanceOf(CommutingCompensation::class, $compensations[0]);
    }

    public function testCalculateCompensation(): void
    {
        $year = 2023;
        $month = 6;
        $commutingDistance = 10;
        $transportationType = 'bus';
        $weeklyOfficeWorkingDays = 5.0;

        $employeeMock = $this->createMock(Employee::class);
        $employeeMock->method('getCommutingDistance')->willReturn($commutingDistance);
        $employeeMock->method('getTransportationType')->willReturn($transportationType);
        $employeeMock->method('getWeeklyOfficeWorkingDays')->willReturn($weeklyOfficeWorkingDays);

        $transportationMock = $this->createMock(TransportationType::class);
        $transportationMock->method('getCost')->willReturn(0.25);

        $this->transportRepositoryMock->method('getTransportation')
            ->with($transportationType, $commutingDistance)
            ->willReturn($transportationMock);

        $monthWorkingWeeks = 4;
        $monthWorkingDays = $monthWorkingWeeks * ceil($weeklyOfficeWorkingDays);
        $totalCommutingDistance = $commutingDistance * 2 * $monthWorkingDays;
        $expectedCost = 0.25 * $totalCommutingDistance;

        $result = $this->compensationService->calculateCompensation($year, $month, $employeeMock);

        $this->assertEquals([$monthWorkingDays, $totalCommutingDistance, $expectedCost], $result);
    }
}
