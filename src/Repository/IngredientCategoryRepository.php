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

    public function findFirstCategoryPosition(?IngredientCategory $excludedCategory = null): float
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT MIN(position) FROM ingredient_category';
        $params = [];

        if ($excludedCategory) {
            $sql .= ' WHERE id <> :categoryId';
            $params['categoryId'] = $excludedCategory->getId();
        }

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery($params);

        return $resultSet->fetchOne();
    }

    public function findLastCategoryPosition(?IngredientCategory $excludedCategory = null): float
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT MAX(position) FROM ingredient_category';
        $params = [];

        if ($excludedCategory) {
            $sql .= ' WHERE id <> :categoryId';
            $params['categoryId'] = $excludedCategory->getId();
        }

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery($params);

        return $resultSet->fetchOne();
    }

    public function findCategoryPositionWithNextOne(
        IngredientCategory $category,
        ?IngredientCategory $excludedCategory = null
    ): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $conditions = ['position >= :position'];
        $params = ['position' => $category->getPosition()];

        if ($excludedCategory) {
            $conditions[] = 'id <> :categoryId';
            $params['categoryId'] = $excludedCategory->getId();
        }

        $sql = sprintf(
            'SELECT position FROM ingredient_category WHERE %s ORDER BY position LIMIT 2',
            implode(' AND ', $conditions)
        );

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery($params);

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
