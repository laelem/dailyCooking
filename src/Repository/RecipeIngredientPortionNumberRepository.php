<?php

namespace App\Repository;

use App\Entity\Recipe;
use App\Entity\RecipeIngredientPortionNumber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RecipeIngredientPortionNumber>
 *
 * @method RecipeIngredientPortionNumber|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecipeIngredientPortionNumber|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecipeIngredientPortionNumber[]    findAll()
 * @method RecipeIngredientPortionNumber[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeIngredientPortionNumberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecipeIngredientPortionNumber::class);
    }

    public function add(RecipeIngredientPortionNumber $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RecipeIngredientPortionNumber $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllByRecipe(Recipe $recipe)
    {
        return $this->createQueryBuilder('ripn')
            ->leftJoin('ripn.recipeIngredient', 'ri')
            ->leftJoin('ri.ingredient', 'i')
            ->leftJoin('ri.recipe', 'r')
            ->leftJoin('i.category', 'c')
            ->where('r.id = :recipeId')
            ->setParameter('recipeId', $recipe->getId())
            ->addOrderBy('ripn.portionNumber', 'ASC')
            ->addOrderBy('c.position', 'ASC')
            ->addOrderBy('i.name', 'ASC')
            ->getQuery()
            ->execute()
            ;
    }
}
