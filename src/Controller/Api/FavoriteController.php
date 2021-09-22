<?php

namespace App\Controller\Api;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class FavoriteController extends AbstractController
{
    /**
     * @Route("/api/favorites/users", name="api_favorites_browse_by_user", methods={"GET"})
     */
    public function browseByUser(): Response
    {
        $user = $this->getUser();

        if($user){

            return $this->json($user->getProduct(), 200);

        }else{

            return $this->json("this user doesn't exist", 404);
        }
    }

    /**
     * @Route("/api/favorites/{productId}/users", name="api_favorites_add_by_user", methods={"PATCH"})
     */
    public function addProductToUser(int $productId, Request $request, EntityManagerInterface $em, ProductRepository $productRepo): Response
    {
        $user = $this->getUser();

        if($user){

                if($product = $productRepo->find($productId)){
                    
                    $product->setLiked($product->getLiked() + 1);
                    $em->persist($product);
        
                    $user->addProduct($product);
                    $em->persist($user);

                    $em->flush();
                    return $this->json($user->getProduct(), 200);
        
                }else{
        
                    return $this->json("this product doesn't exist", 404);
                }

        }else{

            return $this->json("this user doesn't exist", 404);
        }
    }

    /**
     * @Route("/api/favorites/{productId}/users", name="api_favorites_delete_by_user", methods={"DELETE"})
     */
    public function deleteProductFromUser(int $productId, ProductRepository $productRepo, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if($user){

            if($product = $productRepo->find($productId)){
    
                $product->setLiked($product->getLiked() - 1);
                $em->persist($product);

                $user->removeProduct($product);
                $em->persist($user);

                $em->flush();
            }

            return $this->json(null, 204);

        }else{

            return $this->json("this user doesn't exist", 404);
        }
    }
}
