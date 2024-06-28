<?php

declare(strict_types=1);

namespace App\Domain;

enum TransportationType: string
{
    case BIKE = 'BIKE';
    case BUS = 'BUS';
    case TRAIN = 'TRAIN';
    case CAR = 'CAR';

    public function getDisplayName(): string
    {
        return match($this) {
            self::BIKE => 'Bike',
            self::BUS => 'Bus',
            self::TRAIN => 'Train',
            self::CAR => 'Car',
        };
    }

    public static function toArray(): array
    {
        return [
            self::BIKE->value,
            self::BUS->value,
            self::TRAIN->value,
            self::CAR->value,
        ];
    }
}
