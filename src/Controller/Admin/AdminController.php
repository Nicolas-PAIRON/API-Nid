<?php

namespace App\Controller\Admin;

use App\Entity\AddressBill;
use App\Entity\Category;
use App\Entity\Colection;
use App\Entity\Order;
use App\Entity\OrderLine;
use App\Entity\Product;
use App\Entity\Slider;
use App\Entity\Style;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
            // redirect to some CRUD controller
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(StatusSiteCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Le nid à bijoux');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Accueil', 'fa fa-home');
        yield MenuItem::section('Sections');
        yield MenuItem::linkToCrud('Slider', 'fas fa-map', Slider::class);
        yield MenuItem::linkToCrud('Catégories', 'fas fa-list', Category::class);
        yield MenuItem::linkToCrud('Collections', 'fas fa-suitcase', Colection::class);
        yield MenuItem::linkToCrud('Styles', 'fas fa-palette', Style::class);
        yield MenuItem::linkToCrud('Produits', 'fas fa-gem', Product::class);
        yield MenuItem::linkToCrud('Commandes', 'fas fa-shopping-cart', Order::class);
        yield MenuItem::linkToCrud('Détail commandes', 'fas fa-info-circle', OrderLine::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
        yield MenuItem::section('Site');
        yield MenuItem::linkToUrl('Voir le site merveilleux', 'fas fa-globe', 'http://www.lenidabijoux.fr')->setLinkTarget('_BLANK');
        yield MenuItem::section('Super Admin');
        yield MenuItem::linkToUrl('gestion des images', 'fas fa-cog', $_ENV['BASE_URL_LOCALE'].'/admin/manage/pictures');
    }

}
