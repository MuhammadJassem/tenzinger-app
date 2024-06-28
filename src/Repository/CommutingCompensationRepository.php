<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CommutingCompensation;
use App\Entity\Employee;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommutingCompensation>
 *
 * @method null|CommutingCompensation find($id, $lockMode = null, $lockVersion = null)
 * @method null|CommutingCompensation findOneBy(array $criteria, array $orderBy = null)
 * @method CommutingCompensation[]    findAll()
 * @method CommutingCompensation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommutingCompensationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommutingCompensation::class);
    }

    public function save(CommutingCompensation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CommutingCompensation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getEmployeeCompensation(int $year, int $month, ?Employee $employee): ?CommutingCompensation
    {
        return $this->createQueryBuilder('c')
            ->where('c.year = :year')
            ->andWhere('c.month = :month')
            ->andWhere('c.employee = :employee')
            ->setParameter('year', $year)
            ->setParameter('month', $month)
            ->setParameter('employee', $employee)
            ->getQuery()
            ->setFetchMode(Employee::class, 'employees', ClassMetadata::FETCH_EAGER)
            ->getOneOrNullResult()
            ;
    }

    public function create(
        Employee $employee,
        int $year,
        int $month,
        string $transportationType,
        int $monthWorkingDays,
        int $totalCommutingDistance,
        float $compensationAmount,
        DateTimeImmutable $paidAt
    ): CommutingCompensation {
        $commutingCompensation = new CommutingCompensation();
        $commutingCompensation->setEmployee($employee);
        $commutingCompensation->setMonth($month);
        $commutingCompensation->setYear($year);
        $commutingCompensation->setTransportationType($transportationType);
        $commutingCompensation->setNumberOfDays($monthWorkingDays);
        $commutingCompensation->setCommutedDistance($totalCommutingDistance);
        $commutingCompensation->setCompensationAmount($compensationAmount);
        $commutingCompensation->setPaidAt($paidAt);
        $this->save($commutingCompensation, true);
        return $commutingCompensation;
    }
}
