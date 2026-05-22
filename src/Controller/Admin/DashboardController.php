<?php

namespace App\Controller\Admin;


use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Arthur Res Wire');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');


        yield MenuItem::linkTo(ProductCrudController::class, 'Products', 'fa fa-box');
        yield MenuItem::linkTo(ImageCrudController::class, 'Images', 'fa fa-image');
        yield MenuItem::linkTo(InvoiceCrudController::class, 'Invoices', 'fa fa-file-invoice');
        yield MenuItem::linkTo(PayementMethodCrudController::class, 'Payment Methods', 'fa fa-credit-card');
        yield MenuItem::linkTo(PlatformCrudController::class, 'Platforms', 'fa fa-window-restore');
        yield MenuItem::linkTo(ProductKeyCrudController::class, 'Product Keys', 'fa fa-key');
        yield MenuItem::linkTo(UserCrudController::class, 'Users', 'fa fa-user');
    }
}
