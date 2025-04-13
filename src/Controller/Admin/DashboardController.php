<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Entity\Objet;
use App\Entity\UserActionLog;
use App\Service\UserActionLogger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    private UserActionLogger $actionLogger;

    public function __construct(UserActionLogger $actionLogger)
    {
        $this->actionLogger = $actionLogger;
    }

    public function index(): Response
    {
        $this->actionLogger->log('Accès au tableau de bord', 0.25); // Exemple d'action enregistrée

        return $this->render('admin/dashboard.html.twig');
    }   

    public function configureDashboard(): Dashboard
    {
        //$this->denyAccessUnlessGranted('admin');
        return Dashboard::new()
            ->setTitle('ProjetDevWeb'); 
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Gestion des objets', 'fas fa-cogs', Objet::class);
       

        
        //Menu pour les admin
        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
            yield MenuItem::linkToCrud('Utilisateurs non approuvés', 'fas fa-user', User::class)
               ->setController(NonApprovedUserCrudController::class);
            yield MenuItem::linkToCrud('Utilisateurs approuvés', 'fas fa-user', User::class)
               ->setController(ApprovedUserCrudController::class);
            yield MenuItem::linkToCrud('Journal des actions utilisateur', 'fas fa-history', UserActionLog::class)
               ->setController(UserActionLogCrudController::class);
           
            yield MenuItem::linkToRoute('User CSV', 'fas fa-file-csv', 'export_data_user', ['format' => 'csv']);
            yield MenuItem::linkToRoute('User PDF', 'fas fa-file-pdf', 'export_data_user', ['format' => 'pdf']);
            
            yield MenuItem::linkToRoute('Objet CSV', 'fas fa-file-csv', 'export_data_objet', ['format' => 'csv']);
            yield MenuItem::linkToRoute('Objet PDF', 'fas fa-file-pdf', 'export_data_objet', ['format' => 'pdf']);

            yield MenuItem::linkToRoute('Supprimer tous les objets', 'fas fa-trash', 'admin_delete_category', [
                'entity' => 'objet',
            ])->setCssClass('text-danger');
            yield MenuItem::linkToRoute('Supprimer tous le journal', 'fas fa-trash', 'admin_delete_category', [
                'entity' => 'UserActionLog',
            ])->setCssClass('text-danger');
        }
           
        
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }

    #[Route('/admin/delete-category/{entity}', name: 'admin_delete_category')]
    public function deleteCategory(string $entity, EntityManagerInterface $entityManager): Response
    {
        // Vérifiez les permissions (par exemple, uniquement pour les administrateurs)
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Déterminez l'entité à supprimer
        $repository = $entityManager->getRepository('App\\Entity\\' . ucfirst($entity));
        $entities = $repository->findAll();

        // Supprimez toutes les entités
        foreach ($entities as $item) {
            $entityManager->remove($item);
        }
        $entityManager->flush();

        // Ajoutez un message flash pour confirmer la suppression
        $this->addFlash('success', 'Toutes les données de la catégorie "' . ucfirst($entity) . '" ont été supprimées.');

        // Redirigez vers le tableau de bord
        return $this->redirectToRoute('admin');
    }

    #[Route('/admin/delete-user-action-log', name: 'admin_delete_user_action_log')]
    public function deleteUserActionLog(EntityManagerInterface $entityManager): Response
    {
        // Vérifiez les permissions (par exemple, uniquement pour les administrateurs)
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Récupérez toutes les entrées du journal des actions utilisateur
        $repository = $entityManager->getRepository(UserActionLog::class);
        $logs = $repository->findAll();

        // Supprimez toutes les entrées
        foreach ($logs as $log) {
            $entityManager->remove($log);
        }
        $entityManager->flush();

        // Ajoutez un message flash pour confirmer la suppression
        $this->addFlash('success', 'Le journal des actions utilisateur a été supprimé avec succès.');

        // Redirigez vers le tableau de bord
        return $this->redirectToRoute('admin');
    }
}
