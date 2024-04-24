<?php

namespace App\Controller\Admin;

use App\Entity\Banner;
use App\Entity\BannerItems;
use App\Entity\Faq;
use App\Entity\Service;
use App\Entity\Technology;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(BannerItemsCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Webee Symfony');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('BannerItems', 'fas fa-list', BannerItems::class);
        yield MenuItem::linkToCrud('Banner', 'fas fa-image', Banner::class);
        yield MenuItem::linkToCrud('Technologie', 'fas fa-code', Technology::class);
        yield MenuItem::linkToCrud('Faq', 'fas fa-comments', Faq::class);
        yield MenuItem::linkToCrud('Service', 'fas fa-info', Service::class);
    }
}
