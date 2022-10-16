<?php

namespace App\Controller;

use App\Entity\StockIngredient;
use App\Form\StockIngredientType;
use App\Repository\StockIngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/stock-ingredient')]
class StockIngredientController extends AbstractController
{
    private StockIngredientRepository $repository;

    public function __construct(StockIngredientRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('/', name: 'app_stock_ingredient_index', methods: ['GET'])]
    public function index(): Response
    {
        $stockIngredients = $this->repository->findAll();

        return $this->render('stock_ingredient/index.html.twig', [
            'stockIngredients' => $stockIngredients,
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

}