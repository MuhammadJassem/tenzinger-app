<?php

declare(strict_types=1);

namespace App\Tests\Unit\Utils;

use PHPUnit\Framework\TestCase;
use App\Utils\CompensationTimeHelper;
use InvalidArgumentException;

class CompensationTimeHelperTest extends TestCase
{
    public function testGetValidMonths(): void
    {
        $expected = range(1, 12);
        $this->assertSame($expected, CompensationTimeHelper::getValidMonths());
    }

    public function testGetValidCompensationYears(): void
    {
        $currentYear = (int)date('Y');
        $expected = range($currentYear, $currentYear - 10);
        $this->assertSame($expected, CompensationTimeHelper::getValidCompensationYears());
    }

    public function testGetValidMonthsForm(): void
    {
        $expected = array_combine(range(1, 12), range(1, 12));
        $this->assertSame($expected, CompensationTimeHelper::getValidMonthsForm());
    }

    public function testGetValidCompensationYearsForm(): void
    {
        $currentYear = (int)date('Y');
        $expected = array_combine(range($currentYear, $currentYear - 10), range($currentYear, $currentYear - 10));
        $this->assertSame($expected, CompensationTimeHelper::getValidCompensationYearsForm());
    }

    public function testGetMonthWorkingWeeks(): void
    {
        $this->assertSame(4, CompensationTimeHelper::getMonthWorkingWeeks(2023, 4));
        $this->assertSame(5, CompensationTimeHelper::getMonthWorkingWeeks(2023, 5));
    }

    public function testGetMonthWorkingWeeksInvalidInput(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid year or month provided.');
        CompensationTimeHelper::getMonthWorkingWeeks(2023, 13);
    }

    public function testIsValidMonth(): void
    {
        $this->assertTrue(CompensationTimeHelper::isValidMonth(1));
        $this->assertFalse(CompensationTimeHelper::isValidMonth(13));
    }

    public function testIsValidYear(): void
    {
        $currentYear = (int)date('Y');
        $this->assertTrue(CompensationTimeHelper::isValidYear($currentYear));
        $this->assertFalse(CompensationTimeHelper::isValidYear($currentYear - 11));
    }

    public function testGetFirstMondayOfNextMonth(): void
    {
        $date = CompensationTimeHelper::getFirstMondayOfNextMonth(2023, 1);
        $this->assertEquals('2023-02-06', $date->format('Y-m-d'));

        $date = CompensationTimeHelper::getFirstMondayOfNextMonth(2023, 5);
        $this->assertEquals('2023-06-05', $date->format('Y-m-d'));
    }

    public function testGetFirstMondayOfNextMonthInvalidInput(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid year or month provided.');
        CompensationTimeHelper::getFirstMondayOfNextMonth(2023, 13);
    }
}
