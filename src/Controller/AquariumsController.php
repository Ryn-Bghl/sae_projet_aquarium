<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AquariumsController extends AbstractController
{
    #[Route('/aquariums', name: 'app_aquariums')]
    public function index(): Response
    {
        return $this->render('aquariums/index.html.twig', [
            'controller_name' => 'AquariumsController',
            'css_file_path' => 'styles/aquariums.css',
        ]);
    }
}
