<?php

namespace App\Controller;

use App\Classe\Search;
use App\Form\SearchType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController

{
    #[Route('/produit', name: 'app_produit')]


    public function index(ProductRepository $productRepository, Request $request): Response
    {

        $product = $productRepository->findAll();
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product = $productRepository->findWithSearch($search);
        }





        return $this->render('produit/index.html.twig', [
            'app_produit' => $product,
            'form' => $form->createView()
        ]);
    }

    #[Route('/produit/{slug}', name: 'show_produit')]


    public function show($slug, ProductRepository $productRepository): Response
    {

        $product = $productRepository->findOneBySlug($slug);

        if (!$product) {
            return $this->redirectToRoute('app_produit');
        }

        return $this->render('produit/show.html.twig', [
            'show_produit' => $product
        ]);
    }
}
