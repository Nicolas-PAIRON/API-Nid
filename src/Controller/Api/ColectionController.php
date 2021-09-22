<?php

namespace App\Controller\Api;

use App\Repository\ColectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ColectionController extends AbstractController
{
    /**
     * @Route("/api/colections", name="api_colections_browse", methods={"GET"})
     */
    public function browse(ColectionRepository $colectionRepo): Response
    {
        return $this->json($colectionRepo->findAll());
    }
}
