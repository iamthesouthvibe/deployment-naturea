<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\SearchProduct;
use App\Form\SearchProductType;
use App\Repository\ProductRepository;
use App\Repository\AccueilSliderRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(ProductRepository $repoProduct, AccueilSliderRepository $repoSlider): Response
    {
        $products = $repoProduct->findAll();
        $accueilSlider = $repoSlider->findBy(['isDisplayed'=>true]);
        //dd($accueilSlider);
        $productBest = $repoProduct->findByIsBest(1);
        $productNew = $repoProduct->findByIsNew(1);
        $productFeatured = $repoProduct->findByIsFeatured(1);
        $productSpecialOffer = $repoProduct->findByIsSpecialOffer(1);

        //dd($products);
        //dd([$productBest, $productNew, $productFeatured, $productSpecialOffer]);
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'products' => $products,
            'productBest' => $productBest,
            'productNew' => $productNew,
            'productFeatured' => $productFeatured,
            'productSpecialOffer' => $productSpecialOffer,
            'accueilSlider'=>$accueilSlider
        ]);
    }

    /**
     * @Route("/product/{slug}", name="product_details")
     */
    public function show(?Product $product): Response{
        
        if(!$product){
            return $this->redirectToRoute("accueil");
        }

        return $this->render("accueil/single_product.html.twig",[
            'product' => $product
        ]);
    }

    /**
     * @Route("/shop", name="boutique")
     */
    public function shop(ProductRepository $repoProduct, Request $request): Response
    {
        $products = $repoProduct->findAll();

        //vider le formulaire
        $search = new SearchProduct();//Entity sans enregistrement dans la BD
        $form = $this->createForm(SearchProductType::class, $search);//il faut encapsuler les choix du formulaire dans une entité sans reporitory, objet métier
        //quand le formulaire est soumis
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //$data = $form->getData();
            $products = $repoProduct->findWithSearch($search);
            //dd($data);
            //dd($search);
        }

        return $this->render('accueil/boutique.html.twig',[
            'products' => $products,
            'search' => $form->createView()
        ]);
    }
}
