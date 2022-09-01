<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recipe', name: 'recipe_')]
class RecipeController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function list(): Response
    {
        $number = random_int(0, 100);

        return $this->render('recipe/list.html.twig', ['number' => $number]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET', 'HEAD'])]
    public function show(int $id): Response
    {
        // ... return a JSON response with the post
    }

    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(int $id): Response
    {
        // ... edit a post
    }

    #[Route('/', name: 'post', methods: ['POST'])]
    public function post(): Response
    {
        $number = random_int(0, 100);

        return $this->render('recipe/list.html.twig', ['number' => $number]);
    }
}