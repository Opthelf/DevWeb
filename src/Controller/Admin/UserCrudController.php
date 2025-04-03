<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;


class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('username'),
            ChoiceField::new('roles')
            ->setChoices([
                'Visiteur' => 'ROLE_VISITEUR',
                'Utilisateur' => 'ROLE_UTILISATEUR',
                'Admin' => 'ROLE_ADMIN',
            ])
            ->allowMultipleChoices() // Permet de choisir plusieurs rôles
            ->setHelp('Choisissez un ou plusieurs rôles'),
            TextField::new('password')->hideOnIndex(), // Masque le champ mot de passe dans la liste
        ];
    }
    
}
