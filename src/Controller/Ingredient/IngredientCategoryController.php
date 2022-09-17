<?php

namespace App\Controller\Ingredient;

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
    private IngredientCategoryRepository $repository;

    public function __construct(IngredientCategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('/', name: 'app_ingredient_category_index', methods: ['GET'])]
    public function index(): Response
    {
        $categories = $this->repository->findBy([], ['position' => 'ASC']);

        return $this->render('ingredient_category/index.html.twig', ['categories' => $categories]);
    }

    #[Route('/new', name: 'app_ingredient_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $category = new IngredientCategory();
        $form = $this->createForm(IngredientCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managePosition($category);
            $this->repository->add($category, true);

            return $this->redirectToRoute('app_ingredient_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ingredient_category/new.html.twig', [
            'category' => $category,
            'form'     => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ingredient_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, IngredientCategory $category): Response
    {
        $beforeCategory = $this->repository->findBeforeCategory($category);
        if ($beforeCategory) {
            $category->setPositionEnum(IngredientCategory::POSITION_AFTER);
            $category->setBeforeCategory($beforeCategory);
        } else {
            $category->setPositionEnum(IngredientCategory::POSITION_FIRST);
        }

        $form = $this->createForm(IngredientCategoryType::class, $category, ['currentCategoryId' => $category->getId()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managePosition($category, $category);
            $this->repository->add($category, true);

            return $this->redirectToRoute('app_ingredient_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ingredient_category/edit.html.twig', [
            'category' => $category,
            'form'     => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ingredient_category_delete', methods: ['POST'])]
    public function delete(Request $request, IngredientCategory $category): Response
    {
        if (
            $this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))
            && 0 === $category->getIngredients()->count()
        ) {
            $this->repository->remove($category, true);
        }

        return $this->redirectToRoute('app_ingredient_category_index', [], Response::HTTP_SEE_OTHER);
    }

    private function managePosition(IngredientCategory $category, ?IngredientCategory $currentCategory = null)
    {
        switch ($category->getPositionEnum()) {
            case 'first':
                $firstCategoryPosition = $this->repository->findFirstCategoryPosition($currentCategory);
                $category->setPosition($firstCategoryPosition - 1);
                break;
            case 'last':
                $lastCategoryPosition = $this->repository->findLastCategoryPosition($currentCategory);
                $category->setPosition($lastCategoryPosition + 1);
                break;
            case 'after':
                $res = $this->repository->findCategoryPositionWithNextOne($category->getBeforeCategory(), $currentCategory);
                $pos1 = $res[0]['position'];
                $pos2 = isset($res[1]) ? $res[1]['position'] : $pos1+1;
                $category->setPosition(($pos1+$pos2)/2);
                break;
        }
    }
}
