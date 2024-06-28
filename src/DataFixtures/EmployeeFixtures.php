<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Domain\TransportationType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Employee;

class EmployeeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 30; $i++) {
            $employee = new Employee();
            $employee->setEmployeeNumber($faker->numberBetween(1000, 9999));
            $employee->setFirstName($faker->firstName);
            $employee->setLastName($faker->lastName);
            $employee->setCommutingDistance($faker->numberBetween(1, 50));
            $employee->setTransportationType($faker->randomElement(TransportationType::toArray()));
            $employee->setWeeklyOfficeWorkingDays($faker->numberBetween(1, 5));

            $manager->persist($employee);
        }

        for ($i = 0; $i < 5; $i++) {
            $employee = new Employee();
            $employee->setEmployeeNumber($faker->numberBetween(1000, 9999));
            $employee->setFirstName($faker->firstName);
            $employee->setLastName($faker->lastName);
            $employee->setCommutingDistance($faker->numberBetween(5, 10));
            $employee->setTransportationType(TransportationType::BIKE->value);
            $employee->setWeeklyOfficeWorkingDays($faker->numberBetween(1, 5));

            $manager->persist($employee);
        }

        $manager->flush();
    }
}
