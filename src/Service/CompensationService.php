<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\CompensationRequest;
use App\Entity\CommutingCompensation;
use App\Entity\Employee;
use App\Repository\CommutingCompensationRepository;
use App\Repository\EmployeeRepository;
use App\Repository\TransportationTypeRepository;
use App\Utils\CompensationTimeHelper;
use InvalidArgumentException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class CompensationService
{
    public function __construct(
        private EmployeeRepository  $employeeRepository,
        private CommutingCompensationRepository $commutingCompensationRepository,
        private TransportationTypeRepository $transportRepository,
        private ValidatorInterface $validator,
    )
    {
    }

    /**
     * @return CommutingCompensation[]
     */
    public function getCompensations(int $year, int $month, ?Employee $employee = null): array
    {
        $this->validateYearMonth($year, $month);
        $compensations = [];
        if ($employee instanceof Employee) {
            $employees[] = $employee;
        } else {
            $employees = $this->employeeRepository->getEmployees();
        }
        foreach ($employees as $employee) {
            $compensation = $this->commutingCompensationRepository->getEmployeeCompensation($year, $month, $employee);
            if (!$compensation instanceof CommutingCompensation) {
                $compensationAmount = self::calculateCompensation($year, $month, $employee);
                $compensation = $this->commutingCompensationRepository->create(
                    $employee,
                    $year,
                    $month,
                    $employee->getTransportationType(),
                    $compensationAmount[0],
                    $compensationAmount[1],
                    $compensationAmount[2],
                    CompensationTimeHelper::getFirstMondayOfNextMonth($year, $month)
                );
            }
            $compensations[] = $compensation;
        }
        return $compensations;
    }

    public function calculateCompensation(int $year, int $month, Employee $employee): array
    {
        $this->validateYearMonth($year, $month);
        $commutingDistance = $employee->getCommutingDistance();
        $transportationType = $employee->getTransportationType();
        $monthWorkingWeeks = CompensationTimeHelper::getMonthWorkingWeeks($year, $month);
        $monthWorkingDays = $monthWorkingWeeks * (int) ceil($employee->getWeeklyOfficeWorkingDays());
        $transportation = $this->transportRepository->getTransportation($transportationType, $commutingDistance);

        $totalCommutingDistance = $commutingDistance * 2 * $monthWorkingDays;

        return [
            $monthWorkingDays,
            $totalCommutingDistance,
            $transportation->getCost() * $totalCommutingDistance
        ];
    }

    private function validateYearMonth(int $year, int $month): void
    {
        $compensationRequest = new CompensationRequest();
        $compensationRequest->year = $year;
        $compensationRequest->month = $month;

        $errors = $this->validator->validate($compensationRequest);

        if (count($errors) > 0) {
            throw new InvalidArgumentException('Invalid year or month provided.');
        }
    }
}
