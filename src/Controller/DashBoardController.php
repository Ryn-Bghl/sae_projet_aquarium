<?php

namespace App\Controller;

use App\Repository\AquariumRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(AquariumRepository $aquariumRepository, UserRepository $userRepository): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'css_file_path' => 'styles/dashboard.css',
            'aquariums' => $aquariumRepository->findAll(),
            'users' => $userRepository->findAll(),
            'user' => $this->getUser(),
        ]);
    }
}
