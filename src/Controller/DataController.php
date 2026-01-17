<?php

namespace App\Controller;

use App\Entity\Data;
use App\Form\DataType;
use App\Repository\DataRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/data')]
final class DataController extends BaseController
{
    #[Route(name: 'app_data_index', methods: ['GET'])]
    public function index(DataRepository $dataRepository, \App\Repository\UserRepository $userRepository, \Doctrine\ORM\EntityManagerInterface $em, \Symfony\Bundle\SecurityBundle\Security $security): Response
    {
        $this->ensureGuest($userRepository, $em, $security);
        
        return $this->render('data/index.html.twig', [
            'datas' => $dataRepository->findBy([], ['createdAt' => 'DESC']),
            'css_file_path' => 'styles/global.css',
        ]);
    }

    #[Route('/new', name: 'app_data_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = new Data();
        $form = $this->createForm(DataType::class, $data, [
            'user' => $this->getUser(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($data);
            $entityManager->flush();

            return $this->redirectToRoute('app_data_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('data/new.html.twig', [
            'data' => $data,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_data_show', methods: ['GET'])]
    public function show(Data $data): Response
    {
        return $this->render('data/show.html.twig', [
            'data' => $data,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_data_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Data $data, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DataType::class, $data, [
            'user' => $this->getUser(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_data_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('data/edit.html.twig', [
            'data' => $data,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_data_delete', methods: ['POST'])]
    public function delete(Request $request, Data $data, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $data->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($data);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_data_index', [], Response::HTTP_SEE_OTHER);
    }
}
