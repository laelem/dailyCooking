<?php

namespace App\Controller;

use App\Entity\StockIngredient;
use App\Entity\StockIngredientFilters;
use App\Form\StockIngredientFiltersType;
use App\Form\StockIngredientType;
use App\Repository\StockIngredientRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/stock-ingredient')]
class StockIngredientController extends AbstractController
{
    const PER_PAGE_OPTIONS = [10, 25, 50, 100];
    const DEFAULT_PER_PAGE_OPTION = 25;

    private StockIngredientRepository $repository;

    public function __construct(StockIngredientRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('/', name: 'app_stock_ingredient_index', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $numItemsPerPage = $request->query->getInt('numItemsPerPage', self::DEFAULT_PER_PAGE_OPTION);

        $stockIngredientFilters = new StockIngredientFilters();
        $formFilters = $this->createForm(StockIngredientFiltersType::class, $stockIngredientFilters, ['method' => 'GET']);
        $formFilters->handleRequest($request);

        $query = $this->repository->findAllQuery($stockIngredientFilters);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $numItemsPerPage
        );

        return $this->render('stock_ingredient/index.html.twig', [
            'pagination'             => $pagination,
            'numItemsPerPage'        => $numItemsPerPage,
            'perPageOptions'         => self::PER_PAGE_OPTIONS,
            'stockIngredientFilters' => $stockIngredientFilters,
            'formFilters'            => $formFilters->createView(),
        ]);
    }

    #[Route('/new', name: 'app_stock_ingredient_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $stockIngredient = new StockIngredient();
        $form = $this->createForm(StockIngredientType::class, $stockIngredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->add($stockIngredient, true);

            return $this->redirectToRoute('app_stock_ingredient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stock_ingredient/new.html.twig', [
            'stockIngredient'  => $stockIngredient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_stock_ingredient_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, StockIngredient $stockIngredient): Response
    {
        $form = $this->createForm(StockIngredientType::class, $stockIngredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->add($stockIngredient, true);

            return $this->redirectToRoute('app_stock_ingredient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stock_ingredient/edit.html.twig', [
            'stockIngredient' => $stockIngredient,
            'form'            => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stock_ingredient_delete', methods: ['POST'])]
    public function delete(Request $request, StockIngredient $stockIngredient): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stockIngredient->getId(), $request->request->get('_token'))) {
            $this->repository->remove($stockIngredient, true);
        }

        return $this->redirectToRoute('app_stock_ingredient_index', [], Response::HTTP_SEE_OTHER);
    }
}