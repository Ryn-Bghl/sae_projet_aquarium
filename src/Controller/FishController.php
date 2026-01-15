<?php

namespace App\Controller;

use App\Entity\Fish;
use App\Form\FishType;
use App\Repository\FishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/fish')]
final class FishController extends AbstractController
{
    #[Route(name: 'app_fish_index', methods: ['GET'])]
    public function index(FishRepository $fishRepository): Response
    {
        return $this->render('fish/index.html.twig', [
            'fish' => $fishRepository->findAll(),
            'css_file_path' => 'styles/global.css',
        ]);
    }

    #[Route('/new', name: 'app_fish_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $fish = new Fish();
        $form = $this->createForm(FishType::class, $fish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($fish);
            $entityManager->flush();

            return $this->redirectToRoute('app_fish_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fish/new.html.twig', [
            'fish' => $fish,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fish_show', methods: ['GET'])]
    public function show(Fish $fish): Response
    {
        return $this->render('fish/show.html.twig', [
            'fish' => $fish,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fish_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Fish $fish, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FishType::class, $fish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fish_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fish/edit.html.twig', [
            'fish' => $fish,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fish_delete', methods: ['POST'])]
    public function delete(Request $request, Fish $fish, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $fish->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($fish);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fish_index', [], Response::HTTP_SEE_OTHER);
    }
}
