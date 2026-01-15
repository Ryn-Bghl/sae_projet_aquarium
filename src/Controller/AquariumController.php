<?php

namespace App\Controller;

use App\Entity\Aquarium;
use App\Form\AquariumType;
use App\Repository\AquariumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/aquarium')]
final class AquariumController extends AbstractController
{
    #[Route(name: 'app_aquarium_index', methods: ['GET'])]
    public function index(AquariumRepository $aquariumRepository): Response
    {
        return $this->render('aquarium/index.html.twig', [
            'aquaria' => $aquariumRepository->findAll(),
            'css_file_path' => 'styles/global.css',
        ]);
    }

    #[Route('/new', name: 'app_aquarium_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $aquarium = new Aquarium();
        $form = $this->createForm(AquariumType::class, $aquarium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($aquarium);
            $entityManager->flush();

            return $this->redirectToRoute('app_aquarium_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('aquarium/new.html.twig', [
            'aquarium' => $aquarium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_aquarium_show', methods: ['GET'])]
    public function show(Aquarium $aquarium): Response
    {
        return $this->render('aquarium/show.html.twig', [
            'aquarium' => $aquarium,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_aquarium_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Aquarium $aquarium, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AquariumType::class, $aquarium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_aquarium_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('aquarium/edit.html.twig', [
            'aquarium' => $aquarium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_aquarium_delete', methods: ['POST'])]
    public function delete(Request $request, Aquarium $aquarium, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $aquarium->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($aquarium);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_aquarium_index', [], Response::HTTP_SEE_OTHER);
    }
}
