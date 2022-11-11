<?php

namespace App\Controller;

use App\Entity\AddRecipePortionNumber;
use App\Entity\Recipe;
use App\Entity\RecipeFilters;
use App\Entity\RecipeIngredient;
use App\Entity\RecipeIngredientPortionNumber;
use App\Entity\RecipePortionNumber;
use App\Entity\RecipeStep;
use App\Form\AddRecipePortionNumberType;
use App\Form\RecipeFiltersType;
use App\Form\RecipeIngredientPortionNumberType;
use App\Form\RecipeByPortionType;
use App\Form\RecipePortionNumberType;
use App\Form\RecipeType;
use App\Repository\RecipeIngredientPortionNumberRepository;
use App\Repository\RecipeIngredientRepository;
use App\Repository\RecipeRepository;
use App\Repository\RecipeStepRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recipe')]
class RecipeController extends AbstractController
{
    const PER_PAGE_OPTIONS = [10, 25, 50, 100];
    const DEFAULT_PER_PAGE_OPTION = 25;

    private RecipeRepository $repository;
    private RecipeIngredientPortionNumberRepository $recipeIngredientPortionNumberRepository;

    public function __construct(
        RecipeRepository $repository,
        RecipeIngredientPortionNumberRepository $recipeIngredientPortionNumberRepository
    )
    {
        $this->repository = $repository;
        $this->recipeIngredientPortionNumberRepository = $recipeIngredientPortionNumberRepository;
    }

    #[Route('/', name: 'app_recipe_index', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $numItemsPerPage = $request->query->getInt('numItemsPerPage', self::DEFAULT_PER_PAGE_OPTION);

        $recipeFilters = new RecipeFilters();
        $formFilters = $this->createForm(RecipeFiltersType::class, $recipeFilters, ['method' => 'GET']);
        $formFilters->handleRequest($request);

        $query = $this->repository->findAllQuery($recipeFilters);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $numItemsPerPage
        );

        return $this->render('recipe/index.html.twig', [
            'recipes'         => $this->repository->findAll(),
            'pagination'      => $pagination,
            'numItemsPerPage' => $numItemsPerPage,
            'perPageOptions'  => self::PER_PAGE_OPTIONS,
            'recipeFilters'   => $recipeFilters,
            'formFilters'     => $formFilters->createView(),
        ]);
    }

    #[Route('/new', name: 'app_recipe_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $recipe = (new Recipe())->addRecipeStep((new RecipeStep()));

        foreach (range(0, 2) as $index) {
            $recipe->addRecipeIngredient(new RecipeIngredient());
        }

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->add($recipe, true);

            return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recipe/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $recipe = $this->repository->findWithIngredientsAndSteps($id);

        if (null === $recipe) {
            throw $this->createNotFoundException("Aucune recette trouvée pour l'id ".$id);
        }

        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recipe_edit', methods: ['GET', 'POST'])]
    public function edit(
        int $id,
        Request $request,
        RecipeIngredientRepository $recipeIngredientRepository,
        RecipeStepRepository $recipeStepRepository,
    ): Response
    {
        $recipe = $this->repository->findWithIngredientsAndSteps($id);

        if (null === $recipe) {
            throw $this->createNotFoundException("Aucune recette trouvée pour l'id ".$id);
        }

        $form = $this->createForm(RecipeType::class, $recipe);

        $originalRecipeIngredients = new ArrayCollection();
        foreach ($recipe->getRecipeIngredients() as $recipeIngredient) {
            $originalRecipeIngredients->add($recipeIngredient);
        }

        $originalRecipeSteps = new ArrayCollection();
        foreach ($recipe->getRecipeSteps() as $recipeStep) {
            $originalRecipeSteps->add($recipeStep);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($originalRecipeIngredients as $recipeIngredient) {
                if (false === $recipe->getRecipeIngredients()->contains($recipeIngredient)) {
                    $recipeIngredientRepository->remove($recipeIngredient);
                }
            }
            foreach ($originalRecipeSteps as $recipeStep) {
                if (false === $recipe->getRecipeSteps()->contains($recipeStep)) {
                    $recipeStepRepository->remove($recipeStep);
                }
            }

            $this->repository->add($recipe, true);

            return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_delete', methods: ['POST'])]
    public function delete(Request $request, Recipe $recipe): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->request->get('_token'))) {
            $this->repository->remove($recipe, true);
        }

        return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/add-portion-number', name: 'app_recipe_add_portion_number', methods: ['GET', 'POST'])]
    public function addPortionNumber(Request $request, Recipe $recipe): Response
    {
        $addRecipePortionNumber =  new AddRecipePortionNumber();

        $form = $this->createForm(AddRecipePortionNumberType::class, $addRecipePortionNumber);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $portionNumber = $addRecipePortionNumber->getPortionNumber();

            foreach($recipe->getRecipeIngredients() as $recipeIngredient) {
                $recipeIngredientPortionNumber = (new RecipeIngredientPortionNumber())
                    ->setRecipeIngredient($recipeIngredient)
                    ->setPortionNumber($portionNumber)
                ;

                $this->recipeIngredientPortionNumberRepository->add($recipeIngredientPortionNumber, true);
            }

            return $this->redirectToRoute(
                'app_recipe_manage_portion_number',
                [
                    'id'                     => $recipe->getId(),
                    'default-portion-number' => $portionNumber,
                ],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('recipe/add_portion_number.html.twig', [
            'recipe' => $recipe,
            'form'   => $form,
        ]);
    }

    #[Route('/{id}/manage-portion-number', name: 'app_recipe_manage_portion_number', methods: ['GET', 'POST'])]
    public function managePortionNumber(Recipe $recipe, Request $request): Response
    {
        $defaultPortionNumber = (int) $request->query->get('default-portion-number');

        $recipe->hydratePortions();

        if ($recipe->getPortions()->count() === 0) {
            throw new BadRequestHttpException("Cette recette n'a pas de portions.");
        }

        if (0 === $defaultPortionNumber || !in_array($defaultPortionNumber, $recipe->getPortionNumberList())) {
            $defaultPortionNumber = array_key_first($recipe->getPortionNumberList());
        }

        $form = $this->createForm(RecipeByPortionType::class, $recipe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->add($recipe, true);

            return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recipe/manage_portion_number.html.twig', [
            'recipe'               => $recipe,
            'form'                 => $form,
            'defaultPortionNumber' => $defaultPortionNumber,
        ]);
    }

    #[Route('/{id}/delete-portion-number/{portionNumber}', name: 'app_recipe_delete_portion_number', methods: ['POST'])]
    public function deletePortionNumber(Request $request, Recipe $recipe, int $portionNumber): Response
    {
        $tokenName = sprintf('delete%sportionNumber%s', $recipe->getId(), $portionNumber);

        if ($this->isCsrfTokenValid($tokenName, $request->request->get('_token'))) {
            foreach ($recipe->getRecipeIngredients() as $recipeIngredient) {
                foreach($recipeIngredient->getPortionNumbers() as $recipeIngredientPortionNumber) {
                    if ($recipeIngredientPortionNumber->getPortionNumber() == $portionNumber) {
                        $this->recipeIngredientPortionNumberRepository->remove($recipeIngredientPortionNumber, true);
                    }
                }
            }
        }

        return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
    }
}
