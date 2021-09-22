<?php

namespace App\Controller\Api;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class OrderController extends AbstractController
{
    /**
     * @Route("/api/orders/users", name="api_orders_browse_by_user", methods={"GET"})
     */
    public function browseByUser(OrderRepository $orderRepo): Response
    {
        if($user = $this->getUser()){

            return $this->json($orderRepo->findBy(['user' => $user->getId()]), 200);
            
        }else{

            return $this->json("this user doesn't exist", 404);
        }

    }
}