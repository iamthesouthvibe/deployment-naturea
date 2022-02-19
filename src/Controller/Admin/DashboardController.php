<?php

namespace App\Controller\Admin;

use App\Entity\Cart;
use App\Entity\Orders;
use App\Entity\Contact;
use App\Entity\Product;
use App\Entity\Transport;
use App\Entity\Categories;
use App\Entity\CartDetails;
use App\Entity\AccueilSlider;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        //return parent::index();
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(OrdersCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('AliExprass, les montants doivent être divisés par 100');
            
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Produits', 'fas fa-shopping-cart', Product::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-pager', Categories::class);
        yield MenuItem::linkToCrud('Paniers', 'fas fa-shopping-bag', Cart::class);
        yield MenuItem::linkToCrud('Commandes', 'fas fa-box-open', Orders::class);
        yield MenuItem::linkToCrud('Transport', 'fas fa-truck', Transport::class);
        yield MenuItem::linkToCrud('contact', 'fas fa-truck', Contact::class);
        yield MenuItem::linkToCrud('Accueil Slider', 'fas fa-envelope', AccueilSlider::class);
    }
}
