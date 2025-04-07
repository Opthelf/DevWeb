<?php

namespace App\Controller\Admin;

use App\Entity\UserActionLog;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserActionLogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserActionLog::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('username'),
            TextEditorField::new('action'),
            TextField::new('timestamp')->setFormat('d/m/Y H:i:s'),
        ];
    }
    
}
