<?php

namespace App\Controller\Api;

use App\Repository\SliderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SliderController extends AbstractController
{
    /**
     * @Route("/api/slider", name="api_slider_browse", methods={"GET"})
     */
    public function browse(SliderRepository $sliderRepo): Response
    {
        return $this->json($sliderRepo->findAll());
    }
}
