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

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // 1.1) If you have enabled the "pretty URLs" feature:
        // return $this->redirectToRoute('admin_user_index');
        //
        // 1.2) Same example but using the "ugly URLs" that were used in previous EasyAdmin versions:
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
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
        //yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
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
