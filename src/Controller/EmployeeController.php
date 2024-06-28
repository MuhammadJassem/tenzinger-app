<?php

declare(strict_types=1);

namespace App\Controller;

use App\Domain\TransportationType;
use App\DTO\CompensationRequest;
use App\Entity\CommutingCompensation;
use App\Entity\Employee;
use App\Form\TimeType;
use App\Repository\EmployeeRepository;
use App\Service\CompensationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EmployeeController extends AbstractController
{
    public function __construct(
        private readonly EmployeeRepository  $employeeRepository,
        private readonly CompensationService $compensationService,
        private readonly ValidatorInterface $validator,
    ) {
    }

    #[Route('/', name: 'employees')]
    public function employees(
        Request $request,
    ): Response {
        $form = $this->createForm(TimeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('compensations', [
                'year' => $form->getData()['year'],
                'month' => $form->getData()['month'],
            ]);
        }
        return $this->render('employee/list.html.twig', [
            'employees' => $this->employeeRepository->getEmployees(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{employee}', name: 'employee')]
    public function employee(
        Employee $employee,
        Request $request
    ): Response {
        $form = $this->createForm(TimeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('compensations', [
                'year' => $form->getData()['year'],
                'month' => $form->getData()['month'],
                'employee' => $employee->getId(),
            ]);
        }
        return $this->render('employee/employee.html.twig', [
            'employee' => $employee,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/compensations/{year}/{month}/{format}/{employee?}', name: 'compensations')]
    public function compensations(
        int $year,
        int $month,
        string $format = 'html',
        ?Employee $employee = null,
    ): Response {
        $compensationRequest = new CompensationRequest();
        $compensationRequest->year = $year;
        $compensationRequest->month = $month;

        $errors = $this->validator->validate($compensationRequest);

        if (count($errors) > 0) {
            $errorsString = '';
            foreach ($errors as $error) {
                $errorsString .= $error->getMessage()."\n";
            }
            return new Response($errorsString, Response::HTTP_BAD_REQUEST);
        }

        $compensations = $this->compensationService->getCompensations($year,$month, $employee);
        if ($format === 'html') {
            return $this->render('employee/compensations.html.twig', [
                'compensations' => $this->compensationService->getCompensations($year,$month, $employee),
                'employee' => $employee,
                'year' => $year,
                'month' => $month,
            ]);
        }
        $fileName = 'Compensations-'.$month.'-'.$year.'.csv';
        if ($employee instanceof Employee) {
            $fileName = 'Compensations-' . $employee->getFirstName() . '-'.$employee->getLastName() . '-' . $month . '-' . $year . '.csv';
        }
        $csvData = $this->generateCsvData($compensations);

        $csvResponse = new Response($csvData);
        $csvResponse->headers->set('Content-Type', 'text/csv');
        $csvResponse->headers->set('Content-disposition', 'attachment;filename='.$fileName);
        return $csvResponse;
    }

    private function generateCsvData(array $compensations): string
    {
        $data = 'Employee Number, Name, Total Commuted Distance, Transportation Type, Total Office Working Days, Compensation, Paid At' . PHP_EOL;

        /** @var CommutingCompensation $compensation */
        foreach($compensations as $compensation) {
            $data .= sprintf(
                '%s, %s, %d, %s, %d, %.2f, %s',
                $compensation->getEmployee()->getEmployeeNumber(),
                $compensation->getEmployee()->getName(),
                $compensation->getCommutedDistance(),
                TransportationType::from($compensation->getTransportationType())->getDisplayName(),
                $compensation->getNumberOfDays(),
                $compensation->getCompensationAmount(),
                $compensation->getPaidAt()->format('d-m-Y'),
            ) . PHP_EOL;
        }

        return $data;
    }
}
