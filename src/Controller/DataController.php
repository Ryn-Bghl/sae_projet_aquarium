<?php

namespace App\Controller;

use App\Entity\Data;
use App\Form\DataType;
use App\Repository\DataRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
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

    #[Route('/export', name: 'app_data_export', methods: ['GET'])]
    public function export(DataRepository $dataRepository): Response
    {
        $datas = $dataRepository->findBy([], ['createdAt' => 'DESC']);

        $response = new StreamedResponse(function () use ($datas) {
            $handle = fopen('php://output', 'w+');
            
            // Add BOM for Excel UTF-8 support
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header
            fputcsv($handle, [
                'Date', 'Aquarium', 'Temp (°C)', 'pH', 'KH', 'GH', 'Cl2', 'NO2', 'NO3', 'Observations', 'Créé par'
            ], ';');

            foreach ($datas as $data) {
                fputcsv($handle, [
                    $data->getCreatedAt()?->format('d/m/Y H:i'),
                    $data->getAquarium()?->getName(),
                    $data->getTemp(),
                    $data->getPh(),
                    $data->getKh(),
                    $data->getGh(),
                    $data->getCl2(),
                    $data->getNo2(),
                    $data->getNo3(),
                    $data->getObservation(),
                    $data->getCreatedBy()?->getEmail(),
                ], ';');
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="export_donnees_'.date('Ymd_His').'.csv"');

        return $response;
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
