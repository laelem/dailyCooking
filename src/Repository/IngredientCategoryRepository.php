<?php

namespace App\Repository;

use App\Entity\IngredientCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IngredientCategory>
 *
 * @method IngredientCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method IngredientCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method IngredientCategory[]    findAll()
 * @method IngredientCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IngredientCategory::class);
    }

    public function add(IngredientCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(IngredientCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return IngredientCategory[] Returns an array of IngredientCategory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function findFirstCategoryPosition(): float
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT MIN(position) FROM ingredient_category';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        return $resultSet->fetchOne();
    }

    public function findLastCategoryPosition(): float
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT MAX(position) FROM ingredient_category';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        return $resultSet->fetchOne();
    }

    public function findCategoryPositionWithNextOne(IngredientCategory $category): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT position FROM ingredient_category WHERE position >= :position ORDER BY position LIMIT 2';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['position' => $category->getPosition()]);

        return $resultSet->fetchAllAssociative();
    }

    public function findBeforeCategory(IngredientCategory $category): ?IngredientCategory
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.position < :position')
            ->setParameter('position', $category->getPosition())
            ->addOrderBy('c.position', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
