<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;




class UserCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('age')->hideOnIndex(),
            IdField::new('id')->hideOnForm(),
            EmailField::new('email'),
            TextField::new('username'),
            TextField::new('nom')->hideOnIndex(),
            TextField::new('prenom')->hideOnIndex(),
            ChoiceField::new('roles')
            ->setChoices([
                'Visiteur' => 'ROLE_VISITOR',
                'Utilisateur Simple' => 'ROLE_SIMPLE',
                'Utilisateur Avancé' => 'ROLE_ADVANCED',
                'Admin' => 'ROLE_ADMIN',
            ])
            ->allowMultipleChoices(),
            ChoiceField::new('type')
            ->setChoices([
                'Chercheur' => 'Chercheur',
                'Etudiant' => 'Etudiant',
                'Enseignant' => 'Enseignant',
                'Enseignant-Chercheur' => 'Enseignant-Chercheur',
            ])
             // Permet de choisir plusieurs rôles
            ->setHelp('Choisissez un ou plusieurs rôles'),
            ChoiceField::new('genre')
            ->setChoices([
                'Homme' => 'homme',
                'Femme' => 'femme',
                'Autre' => 'autre',
            ]),
            
            TextField::new('password')->onlyOnForms(), // Masque le champ mot de passe dans la liste
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User && $entityInstance->getPassword()) {
            $hashedPassword = $this->passwordHasher->hashPassword($entityInstance, $entityInstance->getPassword());
            $entityInstance->setPassword($hashedPassword);
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User && $entityInstance->getPassword()) {
            $hashedPassword = $this->passwordHasher->hashPassword($entityInstance, $entityInstance->getPassword());
            $entityInstance->setPassword($hashedPassword);
        }

        parent::updateEntity($entityManager, $entityInstance);
    }
    
}
