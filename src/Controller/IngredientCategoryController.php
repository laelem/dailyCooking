<?php

namespace App\Controller;

use App\Entity\IngredientCategory;
use App\Form\IngredientCategoryType;
use App\Repository\IngredientCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ingredient-category')]
class IngredientCategoryController extends AbstractController
{
    #[Route('/', name: 'app_ingredient_category_index', methods: ['GET'])]
    public function index(IngredientCategoryRepository $ingredientCategoryRepository): Response
    {
        $categories = $ingredientCategoryRepository->findBy([], ['position' => 'ASC']);

        return $this->render('ingredient_category/index.html.twig', ['categories' => $categories]);
    }

    #[Route('/new', name: 'app_ingredient_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, IngredientCategoryRepository $ingredientCategoryRepository): Response
    {
        $category = new IngredientCategory();
        $form = $this->createForm(IngredientCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredientCategoryRepository->add($category, true);

            return $this->redirectToRoute('app_ingredient_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ingredient_category/new.html.twig', [
            'category' => $category,
            'form'     => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ingredient_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, IngredientCategory $category, IngredientCategoryRepository $ingredientCategoryRepository): Response
    {
        $form = $this->createForm(IngredientCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredientCategoryRepository->add($category, true);

            return $this->redirectToRoute('app_ingredient_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ingredient_category/edit.html.twig', [
            'category' => $category,
            'form'     => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ingredient_category_delete', methods: ['POST'])]
    public function delete(Request $request, IngredientCategory $category, IngredientCategoryRepository $ingredientCategoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $ingredientCategoryRepository->remove($category, true);
        }

        return $this->redirectToRoute('app_ingredient_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
