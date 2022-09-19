<?php

namespace App\Controller;

use App\Entity\IngredientTag;
use App\Form\IngredientTagType;
use App\Repository\IngredientTagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ingredient-tag')]
class IngredientTagController extends AbstractController
{
    private IngredientTagRepository $repository;

    public function __construct(IngredientTagRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('/', name: 'app_ingredient_tag_index', methods: ['GET'])]
    public function index(): Response
    {
        $tags = $this->repository->findBy([], ['name' => 'ASC']);

        return $this->render('ingredient_tag/index.html.twig', ['tags' => $tags]);
    }

    #[Route('/new', name: 'app_ingredient_tag_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $tag = new IngredientTag();
        $form = $this->createForm(IngredientTagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->add($tag, true);

            return $this->redirectToRoute('app_ingredient_tag_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ingredient_tag/new.html.twig', [
            'tag'  => $tag,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ingredient_tag_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, IngredientTag $tag): Response
    {
        $form = $this->createForm(IngredientTagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->add($tag, true);

            return $this->redirectToRoute('app_ingredient_tag_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ingredient_tag/edit.html.twig', [
            'tag'  => $tag,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ingredient_tag_delete', methods: ['POST'])]
    public function delete(Request $request, IngredientTag $tag): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tag->getId(), $request->request->get('_token'))) {
            $this->repository->remove($tag, true);
        }

        return $this->redirectToRoute('app_ingredient_tag_index', [], Response::HTTP_SEE_OTHER);
    }
}
