<?php

namespace App\Controller\Admin;

use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;



class UtilisateurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Utilisateur::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            ChoiceField::new('role')
            ->setChoices([
                'Visiteur' => 'visiteur',
                'Utilisateur' => 'utilisateur',
                'Admin' => 'admin',
            ])
            ->allowMultipleChoices() // Permet de choisir plusieurs rôles
            ->setHelp('Choisissez un ou plusieurs rôles'),
            BooleanField::new('isVerified')->renderAsSwitch(false), // Permet d’activer/désactiver un utilisateur
        ];
    }
    
}
