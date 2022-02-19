<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Transport;
use App\Entity\Categories;
use App\Entity\AccueilSlider;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DataLoaderController extends AbstractController
{
    /**
     * @Route("/data", name="data_loader")
     */
    public function index(EntityManagerInterface $manager): Response
    {
        $file_products = dirname(dirname(__DIR__))."\products2.json";
        $data_products = json_decode(file_get_contents($file_products))[0]->rows;//decoder de string au format tableau php
        
        $file_categories = dirname(dirname(__DIR__))."\categories2.json";
        $data_categories = json_decode(file_get_contents($file_categories))[0]->rows;
        $file_transports = dirname(dirname(__DIR__))."\carrier2.json";
        $data_transports = json_decode(file_get_contents($file_transports))[0]->rows;
        $file_slider = dirname(dirname(__DIR__))."\data_slider.json";
        $data_sliders = json_decode(file_get_contents($file_slider))[0]->rows;
        $file_productCategorie = dirname(dirname(__DIR__))."\product_categories.json";
        $data_productCategories = json_decode(file_get_contents($file_productCategorie))[0]->rows;
        //dd($data_category[0]->rows);

        $categories = [];
        foreach ($data_categories as $data_category) {
            $category = new Categories();
            $category->setNameCategorie($data_category[1])
                     ->setImage($data_category[3]);
            //$manager->persist($category);
            //$categories[] = $category;
        }
        $products = [];
        foreach ($data_products as $data_product) {
            $product = new Product();
            $product->setNameProduct($data_product[1])
                    ->setDescription($data_product[2])
                    ->setMoreInformations($data_product[3])
                    ->setPrice($data_product[4])
                    ->setIsBest($data_product[5])
                    ->setIsNew($data_product[6])
                    ->setIsFeatured($data_product[7])
                    ->setIsSpecialOffer($data_product[8])
                    ->setImage($data_product[9])
                    ->setQuantity($data_product[10])
                    ->setTags($data_product[12])
                    ->setSlug($data_product[13]);
            //$manager->persist($product);
            //$products[] = $product;
        }
        $transports = [];
        foreach ($data_transports as $data_transport) {
            $transport = new Transport();
            $transport->setNameTransport($data_transport[1])
                     ->setDescription($data_transport[2])
                     ->setPrice($data_transport[3]);
            $manager->persist($transport);
            $transports[] = $transport;
        }
        $sliders = [];
        foreach ($data_sliders as $data_slider) {
            $slider = new AccueilSlider();
            $slider->setTitle($data_slider[1])
                   ->setDescription($data_slider[2])
                   ->setButtonMessage($data_slider[3])
                   ->setButtonUrl($data_slider[4])
                   ->setImageSlider($data_slider[5])
                   ->setIsDisplayed($data_slider[6]);
            //$manager->persist($slider);
            //$sliders[] = $slider;
        }
       
        
        //$manager->flush();  //décommenter pour utiliser

        return $this->json([
            'message' => 'Bienvenue dans le controller de sauvegarde!',
            'path' => 'src/Controller/DataLoaderController.php',
        ]);
    }
    /**
     * @Route("/data/role", name="data_loader")
     */
    public function role(EntityManagerInterface $manager, UserRepository $repoUser){
        $user = $repoUser->find(1);
        $user->setRoles(['ROLE_ADMIN']);
        //$manager->flush();  //décommenter pour utiliser afin de donner des droits d'administrateur

        return $this->json([
            'message' => 'Bienvenue dans le controller de sauvegarde!',
            'path' => 'src/Controller/DataLoaderController.php',
        ]);
    }
}
