<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(SessionInterface $session, ProductRepository $productRepository): Response
    {
        $panier = $session->get("panier", []);

        $dataPanier = [];
        $total = 0;

        foreach($panier as $slug => $quantite){
            $product = $productRepository->findOneBySlug($slug);
            $dataPanier[] = [
                "product" => $product,
                "quantite" => $quantite,
                "prix" => $product->getPrix() * $quantite
            ];
            $total += $product->getPrix() * $quantite;
        }

        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
            'dataPanier' => $dataPanier,
            compact("dataPanier", "total"),
        ]);
    }

    #[Route('/add/{slug}', name: 'add_panier')]
    public function add(Product $product, SessionInterface $session){

        $panier = $session->get("panier", []);
        $slug = $product->getSlug();

        if(!empty($panier[$slug])){
            $panier[$slug]++;
        }else{
            $panier[$slug] = 1;
        }

        $session->set("panier", $panier);
       

        return $this->redirectToRoute('app_panier');
    }
}
