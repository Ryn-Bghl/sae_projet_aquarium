<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

class BaseController extends AbstractController
{
    protected function ensureGuest(UserRepository $userRepository, EntityManagerInterface $em, Security $security): void
    {
        if (!$this->getUser()) {
            $guestEmail = 'guest@aquatrack.fr';
            $user = $userRepository->findOneBy(['email' => $guestEmail]);

            if (!$user) {
                $user = new \App\Entity\User();
                $user->setEmail($guestEmail);
                $user->setPassword('guest_password_not_used');
                $user->setRoles(['ROLE_USER']);
                $em->persist($user);
                $em->flush();
            }

            $security->login($user);
        }
    }
}
