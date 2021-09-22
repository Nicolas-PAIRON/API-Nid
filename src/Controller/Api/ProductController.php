<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProductController extends AbstractController
{
    /**
     * @Route("/api/products", name="api_products_browse", methods={"GET"})
     */
    public function browse(ProductRepository $productRepo): Response
    {
        return $this->json($productRepo->findAll());
    }
    
    /**
     * @Route("/api/products/{id}", name="api_products_read", methods={"GET"})
     */
    public function read(Product $product): Response
    {
        return $this->json($product);
    }

    /**
     * @Route("/api/products/colections/{id}", name="api_products_browse_by_colection", methods={"GET"})
     */
    public function browseByColection(ProductRepository $productRepo, $id): Response
    {
        return $this->json($productRepo->findBy(['colection' => $id]));
    }

    /**
     * @Route("/api/products/categories/{id}", name="api_products_browse_by_category", methods={"GET"})
     */
    public function browseByCategory(ProductRepository $productRepo, $id): Response
    {
        return $this->json($productRepo->findBy(['category' => $id]));
    }

    /**
     * @Route("/api/products/styles/{id}", name="api_products_browse_by_style", methods={"GET"})
     */
    public function browseByStyle(ProductRepository $productRepo, $id): Response
    {
        return $this->json($productRepo->findBy(['style' => $id]));
    }

}
