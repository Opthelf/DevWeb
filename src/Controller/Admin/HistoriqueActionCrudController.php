<?php

namespace App\Controller\Admin;

use App\Entity\HistoriqueAction;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class HistoriqueActionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return HistoriqueAction::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('utilisateur'),
            DateTimeField::new('date'),
            TextField::new('ipAddress'),
        ];
    }
    
}
