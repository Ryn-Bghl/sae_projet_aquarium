<?php

namespace App\EventListener;

use App\Entity\Data;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, entity: Data::class)]
#[AsEntityListener(event: Events::preUpdate, entity: Data::class)]
class DataEntityListener
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(Data $data, PrePersistEventArgs $event): void
    {
        if ($data->getCreatedBy() === null) {
            $user = $this->security->getUser();
            if ($user instanceof User) {
                $data->setCreatedBy($user);
            }
        }
    }

    public function preUpdate(Data $data, PreUpdateEventArgs $event): void
    {
        $user = $this->security->getUser();
        if ($user instanceof User) {
            $data->setUpdatedBy($user);
        }
    }
}
