<?php

namespace App\Repository;

use App\Entity\Recipe;
use App\Entity\RecipeFilters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 *
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function add(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllQuery(RecipeFilters $recipeFilters): Query
    {
        $qb = $this->createQueryBuilder('r')
            ->leftJoin('r.recipeIngredients', 'ri')
            ->leftJoin('ri.portionNumbers', 'ripn');

        $parameters = [];

        if ($recipeFilters->getTitle()) {
            $qb->andWhere('r.title LIKE :filterName');
            $parameters['filterName'] = '%'.$recipeFilters->getTitle().'%';
        }

        $qb->setParameters($parameters)
            ->groupBy('r.id')
            ->orderBy('r.title')
        ;

        return $qb->getQuery();
    }

    public function findWithIngredientsAndSteps(int $recipeId)
    {
        return $this->createQueryBuilder('r')
            ->addSelect('ri', 'i', 'c', 's')
            ->leftJoin('r.recipeIngredients', 'ri')
            ->leftJoin('ri.ingredient', 'i')
            ->leftJoin('i.category', 'c')
            ->leftJoin('r.recipeSteps', 's')
            ->where('r.id = :recipeId')
            ->setParameter('recipeId', $recipeId)
            ->addOrderBy('c.position', 'ASC')
            ->addOrderBy('i.name', 'ASC')
            ->addOrderBy('s.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
