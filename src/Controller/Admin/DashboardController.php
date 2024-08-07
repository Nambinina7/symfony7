<?php

namespace App\Controller\Admin;

use App\Entity\About;
use App\Entity\ApiUser;
use App\Entity\BankHolidays;
use App\Entity\Banner;
use App\Entity\BannerItems;
use App\Entity\Contact;
use App\Entity\Faq;
use App\Entity\Holyday;
use App\Entity\Mail;
use App\Entity\Permission;
use App\Entity\Section;
use App\Entity\Service;
use App\Entity\Technology;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
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
        yield MenuItem::linkToCrud('About', 'fas fa-building', About::class);
        yield MenuItem::linkToCrud('BannerItems', 'fas fa-list', BannerItems::class);
        yield MenuItem::linkToCrud('Banner', 'fas fa-image', Banner::class);
        yield MenuItem::linkToCrud('Contact', 'fas fa-address-book', Contact::class);
        yield MenuItem::linkToCrud('Faq', 'fas fa-comments', Faq::class);
        yield MenuItem::linkToCrud('Services', 'fas fa-info', Service::class);
        yield MenuItem::linkToCrud('Section', 'fas fa-section', Section::class);
        yield MenuItem::linkToCrud('Technologies', 'fas fa-code', Technology::class);
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('ApiUsers', 'fas fa-user', ApiUser::class);
        yield MenuItem::linkToCrud('Permissions', 'fas fa-clock', Permission::class);
        yield MenuItem::linkToCrud('Holiday', 'fas fa-calendar', Holyday::class);
        yield MenuItem::linkToCrud('Mail', 'fas fa-envelope', Mail::class);
        yield MenuItem::linkToCrud('Bank holidays', 'fas fa-calendar', BankHolidays::class);
    }
}
