<?php

namespace App\Repository;

use App\Entity\IngredientFilters;
use App\Entity\StockIngredient;
use App\Entity\StockIngredientFilters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StockIngredient>
 *
 * @method StockIngredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method StockIngredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method StockIngredient[]    findAll()
 * @method StockIngredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockIngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockIngredient::class);
    }

    public function add(StockIngredient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(StockIngredient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllQuery(StockIngredientFilters $stockIngredientFilters): Query
    {
        $qb = $this->createQueryBuilder('si')
            ->addSelect('si.stockStatus as HIDDEN initSortStockStatus')
            ->addSelect('si.expiresAt as HIDDEN initSortExpiresAt')
            ->addSelect('i.name as HIDDEN initSortIngredientName')
            ->addSelect('CASE WHEN si.expiresAt IS NULL THEN 1 ELSE 0 END as HIDDEN initSortExpiresAtIsNull')
            ->addSelect('CASE WHEN si.stockStatus IS NULL THEN 1 ELSE 0 END as HIDDEN initSortStockStatusIsNull')
            ->addSelect('CASE WHEN si.expiresAt IS NULL THEN 1 ELSE 0 END as HIDDEN expiresAtIsNull')
            ->addSelect('CASE WHEN si.stockStatus IS NULL THEN 1 ELSE 0 END as HIDDEN stockStatusIsNull')
            ->innerJoin('si.ingredient', 'i')
            ->innerJoin('i.category', 'c')
        ;

        $parameters = [];

        if ($stockIngredientFilters->getName()) {
            $qb->andWhere('i.name LIKE :filterName');
            $parameters['filterName'] = '%'.$stockIngredientFilters->getName().'%';
        }

        if ($stockIngredientFilters->getCategory()) {
            $qb->andWhere('c.id = :filterCategoryId');
            $parameters['filterCategoryId'] = $stockIngredientFilters->getCategory()->getId();
        }

        $qb->setParameters($parameters)
            ->groupBy('si.id')
            ->addOrderBy('initSortStockStatusIsNull')
            ->addOrderBy('initSortStockStatus')
            ->addOrderBy('initSortExpiresAtIsNull')
            ->addOrderBy('initSortExpiresAt')
            ->addOrderBy('c.position')
            ->addOrderBy('initSortIngredientName')
        ;

        return $qb->getQuery();
    }
}
