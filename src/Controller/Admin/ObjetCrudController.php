<?php

namespace App\Controller\Admin;

use App\Entity\Objet;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\HtmlField;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class ObjetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Objet::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            //IdField::new('id'),
            TextField::new('nom'),
            TextEditorField::new('description')->formatValue(fn ($value) => strip_tags($value)),
            BooleanField::new('status', 'À supprimer') // Champ pour le statut (true/false)
                ->renderAsSwitch(false), // Affiche un switch dans le formulaire
            BooleanField::new('actif', 'Actif') // Champ pour actif (true/false)
                ->renderAsSwitch(false), 
        ];
    }

    

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('danger', 'Vous n\'avez pas les permissions nécessaires pour supprimer cet objet.');
            return; // Empêche la suppression
        }
        if ($entityInstance instanceof Objet) {
            if ($entityInstance->isActif() !== false) {
                $this->addFlash('danger', 'Seuls les objets inactif peuvent être supprimés.');
                return; // Empêche la suppression
            }
        }

        parent::deleteEntity($entityManager, $entityInstance);
    }
}
