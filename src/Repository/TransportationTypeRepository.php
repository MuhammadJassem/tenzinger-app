<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TransportationType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TransportationType>
 *
 * @method null|TransportationType find($id, $lockMode = null, $lockVersion = null)
 * @method null|TransportationType findOneBy(array $criteria, array $orderBy = null)
 * @method TransportationType[]    findAll()
 * @method TransportationType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransportationTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TransportationType::class);
    }

    public function save(TransportationType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TransportationType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getTransportation(string $transportType, int $distance): TransportationType
    {
        $query = $this->createQueryBuilder('t')
            ->where('t.code = :transportType')
            ->andWhere('t.minDistance <= :distance')
            ->andWhere('t.maxDistance >= :distance OR t.maxDistance IS NULL')
            ->setParameter('transportType', $transportType)
            ->setParameter('distance', $distance)
            ->getQuery();
        return $query->getSingleResult();
    }
}
