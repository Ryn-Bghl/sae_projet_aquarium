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

final class DashBoardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(AquariumRepository $aquariumRepository, UserRepository $userRepository, DataRepository $dataRepository, FishRepository $fishRepository, SettingRepository $setting): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'title' => 'AquaTrack | Dashboard',
            'controller_name' => 'DashboardController',
            'aquariums' => $aquariumRepository->findAll(),
            'users' => $userRepository->findAll(),
            'user' => $this->getUser(),
            'datas' => $dataRepository->findAll(),
            'fishes' => $fishRepository->findAll(),
            'settings' => $setting->findAll(),
        ]);
    }
}
