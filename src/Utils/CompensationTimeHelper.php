<?php

declare(strict_types=1);

namespace App\Utils;

use DateTime;
use DateTimeImmutable;
use InvalidArgumentException;

class CompensationTimeHelper
{
    public static function getValidMonths(): array
    {
        return range(1, 12);
    }

    public static function getValidCompensationYears(): array
    {
        $currentYear = (int)date('Y');
        $startYear = $currentYear - 10;
        return range($currentYear, $startYear);
    }

    public static function getValidMonthsForm(): array
    {
        return array_combine(self::getValidMonths(), self::getValidMonths());
    }

    public static function getValidCompensationYearsForm(): array
    {
        return array_combine(self::getValidCompensationYears(), self::getValidCompensationYears());
    }

    public static function getMonthWorkingWeeks(int $year, int $month): int
    {
        if (!self::isValidYear($year) || !self::isValidMonth($month)) {
            throw new InvalidArgumentException('Invalid year or month provided.');
        }

        $startDate = new DateTime("$year-$month-01");
        $endDate = clone $startDate;
        $endDate->modify('last day of this month');
        $workingWeeks = 0;

        while ($startDate <= $endDate) {
            if ((int) $startDate->format('w') === 1) {
                $workingWeeks++;
            }
            $startDate->modify('+1 day');
        }
        return $workingWeeks;
    }

    public static function isValidMonth(int $month): bool
    {
        return in_array($month, self::getValidMonths(), true);
    }

    public static function isValidYear(int $year): bool
    {
        return in_array($year, self::getValidCompensationYears(), true);
    }

    public static function getFirstMondayOfNextMonth(int $year, int $month): DateTimeImmutable
    {
        if (!self::isValidYear($year) || !self::isValidMonth($month)) {
            throw new InvalidArgumentException('Invalid year or month provided.');
        }

        $date = new DateTimeImmutable("$year-$month-01");
        $nextMonth = $date->modify('first day of next month');

        if ($nextMonth->format('N') !== '1') {
            $nextMonth = $nextMonth->modify('next Monday');
        }

        return $nextMonth;
    }
}
