<?php

namespace App\Controller\Admin;

use App\Entity\Aquarium;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AquariumCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Aquarium::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            NumberField::new('capacity'),
            TextField::new('type'),
            AssociationField::new('user'),
            AssociationField::new('fishes')->onlyOnIndex(),
            AssociationField::new('data')->onlyOnIndex(),
        ];
    }
}
