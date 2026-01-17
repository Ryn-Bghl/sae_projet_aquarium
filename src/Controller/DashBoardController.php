<?php

namespace App\Controller;

use App\Repository\AquariumRepository;
use App\Repository\DataRepository;
use App\Repository\FishRepository;
use App\Repository\SettingRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends BaseController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(AquariumRepository $aquariumRepository, DataRepository $dataRepository, FishRepository $fishRepository, \App\Repository\UserRepository $userRepository, \Doctrine\ORM\EntityManagerInterface $em, \Symfony\Bundle\SecurityBundle\Security $security): Response
    {
        $this->ensureGuest($userRepository, $em, $security);
        
        return $this->render('dashboard/index.html.twig', [
            'title' => 'AquaTrack | Dashboard',
            'aquariums' => $aquariumRepository->findAll(),
            'user' => $this->getUser(),
            'datas' => $dataRepository->findBy([], ['createdAt' => 'DESC']),
            'fishes' => $fishRepository->findAll(),
        ]);
    }
}
