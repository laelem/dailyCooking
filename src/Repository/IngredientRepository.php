<?php

namespace App\Repository;

use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ingredient>
 *
 * @method Ingredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ingredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ingredient[]    findAll()
 * @method Ingredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

    public function add(Ingredient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Ingredient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllQuery(
        ?string $filterName,
        ?int $filterCategoryId,
        ?string $filterTags,
        ?string $filterWhereToKeep
    ): Query
    {
        $qb = $this->createQueryBuilder('i')
            ->innerJoin('i.category', 'c')
            ->leftJoin('i.tags', 't')
        ;

        $parameters = [];

        if ($filterName) {
            $qb->andWhere('i.name LIKE :filterName');
            $parameters['filterName'] = '%'.$filterName.'%';
        }

        if ($filterCategoryId) {
            $qb->andWhere('c.id = :filterCategoryId');
            $parameters['filterCategoryId'] = $filterCategoryId;
        }

        if ($filterWhereToKeep) {
            $qb->andWhere('i.whereToKeep = :filterWhereToKeep');
            $parameters['filterWhereToKeep'] = $filterWhereToKeep;
        }

        if ($filterTags) {
            $tags = explode(';', $filterTags);
            if (!empty($tags)) {
                $tagsConditions = [];
                foreach($tags as $i => $tag) {
                    $tagsConditions[] = 'LOWER(t.name) = :filterTag'.$i;
                    $parameters['filterTag'.$i] = strtolower(trim($tag));
                }
                $qb->andWhere(implode(' OR ', $tagsConditions));
            }
        }

        $qb->setParameters($parameters)
            ->groupBy('i.id')
            ->orderBy('c.position')
            ->addOrderBy('i.name')
        ;

        return $qb->getQuery();
    }

//    /**
//     * @return Ingredient[] Returns an array of Ingredient objects
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

//    public function findOneBySomeField($value): ?Ingredient
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
