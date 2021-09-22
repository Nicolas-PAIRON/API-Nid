<?php

namespace App\Controller\Api;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends AbstractController
{
    /**
     * @Route("/api/categories", name="api_categories_browse", methods={"GET"})
     */
    public function browse(CategoryRepository $categoryRepo): Response
    {
        return $this->json($categoryRepo->findAll());
    }
}
