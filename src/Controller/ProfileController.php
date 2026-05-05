<?php

namespace App\Controller;

use App\Entity\Setting;
use App\Form\SettingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var UserInterface $user */
        $user = $this->getUser();

        // Get the user's settings, or create a new one if it doesn't exist
        $setting = $entityManager->getRepository(Setting::class)->findOneBy(['user' => $user]);

        if (!$setting) {
            $setting = new Setting();
            $setting->setUser($user);
            $entityManager->persist($setting);
        }

        $form = $this->createForm(SettingType::class, $setting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Your settings have been saved!');
            return $this->redirectToRoute('app_profile');
        }
        
        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'settingForm' => $form->createView(),
        ]);
    }
}
