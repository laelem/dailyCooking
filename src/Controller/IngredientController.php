<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientCategoryRepository;
use App\Repository\IngredientRepository;
use App\Repository\TagRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ingredient')]
class IngredientController extends AbstractController
{
    const PER_PAGE_OPTIONS = [10, 25, 50, 100];
    const DEFAULT_PER_PAGE_OPTION = 25;

    #[Route('/', name: 'app_ingredient_index', methods: ['GET'])]
    public function index(
        IngredientRepository $ingredientRepository,
        IngredientCategoryRepository $ingredientCategoryRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response
    {
        $numItemsPerPage = $request->query->getInt('numItemsPerPage', self::DEFAULT_PER_PAGE_OPTION);
        $filterName = $request->query->getAlpha('filterName');
        $filterCategoryId = $request->query->getInt('filterCategoryId');

        $categories = $ingredientCategoryRepository->findBy([], ['position' => 'asc']);

        $query = $ingredientRepository->findAllQuery($filterName, $filterCategoryId);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $numItemsPerPage
        );

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->findAll(),
            'pagination' => $pagination,
            'numItemsPerPage' => $numItemsPerPage,
            'perPageOptions' => self::PER_PAGE_OPTIONS,
            'filterName' => $filterName,
            'filterCategoryId' => $filterCategoryId,
            'categories' => $categories,
        ]);
    }

    #[Route('/new', name: 'app_ingredient_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        IngredientRepository $ingredientRepository,
        TagRepository $tagRepository
    ): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredientRepository->add($ingredient, true);

            return $this->redirectToRoute('app_ingredient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ingredient/new.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ingredient_show', methods: ['GET'])]
    public function show(Ingredient $ingredient): Response
    {
        return $this->render('ingredient/show.html.twig', [
            'ingredient' => $ingredient,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ingredient_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ingredient $ingredient, IngredientRepository $ingredientRepository): Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredientRepository->add($ingredient, true);

            return $this->redirectToRoute('app_ingredient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ingredient/edit.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ingredient_delete', methods: ['POST'])]
    public function delete(Request $request, Ingredient $ingredient, IngredientRepository $ingredientRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ingredient->getId(), $request->request->get('_token'))) {
            $ingredientRepository->remove($ingredient, true);
        }

        return $this->redirectToRoute('app_ingredient_index', [], Response::HTTP_SEE_OTHER);
    }
}
