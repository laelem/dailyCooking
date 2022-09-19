<?php

namespace App\Controller;

use App\Entity\QuantityType;
use App\Form\QuantityTypeType;
use App\Repository\QuantityTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/quantity-type')]
class QuantityTypeController extends AbstractController
{
    private QuantityTypeRepository $repository;

    public function __construct(QuantityTypeRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('/', name: 'app_quantity_type_index', methods: ['GET'])]
    public function index(): Response
    {
        $quantityTypes = $this->repository->findBy([], ['name' => 'ASC']);

        return $this->render('quantity_type/index.html.twig', ['quantityTypes' => $quantityTypes]);
    }

    #[Route('/new', name: 'app_quantity_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $quantityType = new QuantityType();
        $form = $this->createForm(QuantityTypeType::class, $quantityType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->add($quantityType, true);

            return $this->redirectToRoute('app_quantity_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quantity_type/new.html.twig', [
            'quantityType' => $quantityType,
            'form'         => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_quantity_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, QuantityType $quantityType): Response
    {
        $form = $this->createForm(QuantityTypeType::class, $quantityType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->add($quantityType, true);

            return $this->redirectToRoute('app_quantity_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quantity_type/edit.html.twig', [
            'quantityType' => $quantityType,
            'form'         => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_quantity_type_delete', methods: ['POST'])]
    public function delete(Request $request, QuantityType $quantityType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quantityType->getId(), $request->request->get('_token'))) {
            $this->repository->remove($quantityType, true);
        }

        return $this->redirectToRoute('app_quantity_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
