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
        $this->actionLogger->log('Accès au tableau de bord'); // Exemple d'action enregistrée

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
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Utilisateurs non approuvés', 'fas fa-user', User::class)
           ->setController(NonApprovedUserCrudController::class);
        yield MenuItem::linkToCrud('Utilisateurs approuvés', 'fas fa-user', User::class)
           ->setController(ApprovedUserCrudController::class);
        yield MenuItem::linkToCrud('Gestion des objets', 'fas fa-cogs', Objet::class);
        yield MenuItem::linkToRoute('Exporter en CSV', 'fas fa-file-csv', 'export_data', ['format' => 'csv']);
        yield MenuItem::linkToRoute('Exporter en PDF', 'fas fa-file-pdf', 'export_data', ['format' => 'pdf']);
        yield MenuItem::linkToCrud('Journal des actions utilisateur', 'fas fa-history', UserActionLog::class)
            ->setController(UserActionLogCrudController::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
