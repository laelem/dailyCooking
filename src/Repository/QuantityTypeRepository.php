<?php

namespace App\Repository;

use App\Entity\QuantityType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuantityType>
 *
 * @method QuantityType|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuantityType|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuantityType[]    findAll()
 * @method QuantityType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuantityTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuantityType::class);
    }

    public function add(QuantityType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(QuantityType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
