<?php

namespace App\Controller\Admin;

use App\Entity\Setting;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class SettingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Setting::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            CodeEditorField::new('settings')
                ->setLanguage('javascript')
                ->formatValue(static function ($value) {
                    return is_array($value) ? json_encode($value, JSON_PRETTY_PRINT) : $value;
                }),
            AssociationField::new('user'),
        ];
    }
}
