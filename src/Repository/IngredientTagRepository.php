<?php

namespace App\Repository;

use App\Entity\IngredientTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IngredientTag>
 *
 * @method IngredientTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method IngredientTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method IngredientTag[]    findAll()
 * @method IngredientTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientTagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IngredientTag::class);
    }

    public function add(IngredientTag $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(IngredientTag $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
