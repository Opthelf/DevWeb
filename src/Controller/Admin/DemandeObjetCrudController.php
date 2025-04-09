<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;
use App\Entity\Objet;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class DemandeObjetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Objet::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): \Doctrine\ORM\QueryBuilder
    {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $qb->andWhere('entity.status = :status') // Utilisez :status comme placeholder
           ->setParameter('status', false); // Assurez-vous que la valeur correspond à votre logique (par exemple, 'pending')

        return $qb;
    }

    public function configureFields(string $pageName): iterable
    {
    $fields = [
        TextField::new('nom', 'Nom de l\'objet'),
        TextEditorField::new('description', 'Description'),
    ];

    // Ajouter le champ "status" uniquement pour les administrateurs
    if ($this->isGranted('ROLE_ADMIN')) {
        $fields[] = ChoiceField::new('status', 'Statut')
            ->setChoices([
                'En attente' => false,
                'Validé' => true,
            ]);
    }

    return $fields;
    }

  
}
