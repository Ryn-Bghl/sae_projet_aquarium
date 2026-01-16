<?php

namespace App\Controller\Admin;

use App\Entity\Data;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class DataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Data::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            DateTimeField::new('createdAt'),
            NumberField::new('temp'),
            NumberField::new('ph'),
            NumberField::new('kh'),
            NumberField::new('gh'),
            NumberField::new('cl2'),
            NumberField::new('no2'),
            NumberField::new('no3'),
            TextareaField::new('observation'),
            AssociationField::new('aquarium'),
        ];
    }
}
