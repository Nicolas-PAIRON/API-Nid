<?php

namespace App\Controller\Api;

use App\Repository\OrderLineRepository;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class OrderLineController extends AbstractController
{
    /**
     * @Route("/api/order-lines/orders/{id}", name="api_order_lines_browse_by_order", methods={"GET"})
     */
    public function browseByOrder(OrderLineRepository $orderLineRepo, int $id, OrderRepository $orderRepo): Response
    {
        if($orderRepo->find($id)){

            return $this->json($orderLineRepo->findBy(['orderEntity' => $id]), 200);

        }else{

            return $this->json("this order doesn't exist", 404);
        }
    }
}