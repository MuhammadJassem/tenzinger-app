<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use App\Utils\CompensationTimeHelper;

class CompensationRequest
{
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    public int $year;

    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    public int $month;

    #[Assert\Callback]
    public function validateYear(ExecutionContextInterface $context, $payload): void
    {
        if (!CompensationTimeHelper::isValidYear($this->year)) {
            $context->buildViolation('The year must be within the last 10 years.')
                ->atPath('year')
                ->addViolation();
        }
    }

    #[Assert\Callback]
    public function validateMonth(ExecutionContextInterface $context, $payload): void
    {
        if (!CompensationTimeHelper::isValidMonth($this->month)) {
            $context->buildViolation('The month must be a valid month(1-12).')
                ->atPath('month')
                ->addViolation();
        }
    }
}
