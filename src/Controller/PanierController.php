<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(): Response
    {
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
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

        return $this->redirectToRoute('app_produit');
    }
}
