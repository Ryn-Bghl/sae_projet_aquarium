<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ResourceController extends AbstractController
{
    #[Route('/ressources', name: 'app_resource_index')]
    public function index(): Response
    {
        return $this->render('resource/index.html.twig', [
            'controller_name' => 'ResourceController',
        ]);
    }
}
