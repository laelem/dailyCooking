<?php

namespace App\Controller;

use App\Repository\StockIngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}