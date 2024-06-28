<?php

declare(strict_types=1);

namespace App\Twig;

use App\Domain\TransportationType;
use App\Utils\CompensationTimeHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getYears', [$this, 'getYears']),
            new TwigFunction('getMonths', [$this, 'getMonths']),
            new TwigFunction('getTransportationName', [$this, 'getTransportationName']),
        ];
    }

    public function getYears(): array
    {
        return CompensationTimeHelper::getValidCompensationYears();
    }

    public function getMonths(): array
    {
        return CompensationTimeHelper::getValidMonths();
    }

    public function getTransportationName(string $transportationType): string
    {
        return TransportationType::from($transportationType)->getDisplayName();
    }
}
