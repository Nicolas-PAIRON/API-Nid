<?php

namespace App\Controller\Api;

use App\Entity\Order;
use App\Entity\OrderLine;
use App\Repository\OrderLineRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;


class CartController extends AbstractController
{
    /**
     * @Route("/api/carts/users", name="api_carts_add", methods={"POST"})
     */
    public function add(OrderRepository $orderRepo, ProductRepository $productRepo, OrderLineRepository $orderLineRepo, Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        $user = $this->getUser();

        if($user){

            $infoFromClientAsObject = json_decode($request->getContent(), false); //we retrieve the provided data
            
            if(empty($infoFromClientAsObject)){
                return $this->json("request is not valid",400);
            }

            $errorsProduct=[];
            $errorsStock=[];

            foreach ($infoFromClientAsObject as $cart) {
                $productId = $cart->productId;
                $quantity = $cart->quantity;

                    // we verify if the provided data is valid
                if (! is_int($productId) || ! $product = $productRepo->find($productId)) {
                    $errorsProduct[]='the product: - '.$productId.' - doesn\'t exist';
                }

                if (! is_int($quantity) || $quantity<=0) {
                    $errorsProduct[]='the quantity: - '.$quantity.' - is not valid';
                }

                if(count($errorsProduct) > 0){  // if the data has a wrong format we stop the process
                    return $this->json($errorsProduct, 400);
                }

                if ($quantity > $product->getStock()) { // we verify if the products are in stock
                    if($product->getStock()>0){
                        $errorsStock[]='Il ne reste que '.$product->getStock().' '.$product->getName().' en stock';
                    }else{
                        $errorsStock[]='L\'article: '.$product->getName().' n\'est plus en stock';
                    }
                }
            }

            if(count($errorsStock) > 0){ // if the products are sold out we return a specific response
                return $this->json($errorsStock, 302);
                
            }

            // if all the data is ok (user+products+quantity) we save the order in the data base
            $order = new Order();
            $order->setUser($user);
            $em->persist($order);
            $em->flush();
            
            foreach($infoFromClientAsObject as $cart){ // and we can save the details of the order in database just now
                                                      // because the object '$order' is a mandatory foreigh key.
                $productId = $cart->productId;
                $quantity = $cart->quantity;
                $product = $productRepo->find($productId);

                $product->setStock($product->getStock() - $quantity); // update the stock
                $em->persist($product);

                $orderLine = new OrderLine();
                $orderLine->setQuantity($quantity);
                $orderLine->setLabelProduct($product->getName());
                $orderLine->setPriceProduct($product->getPrice());
                $orderLine->setOrderEntity($order);
                $em->persist($orderLine);
            }

            $em->flush();

            // -------------------return the validation by email to the seller and the buyer:---------------------

            $orderlines = $orderLineRepo->findBy(['orderEntity' => $order->getId()]); // retrieve the order from the database

            $messageDetails = '';
            $total = 0;

            foreach($orderlines as $orderline){ // making a text summary message of the passed order
                $ligne = 'Article: '.$orderline->getLabelProduct().' / quantité: '.$orderline->getQuantity().' / prix unitaire: '.number_format($orderline->getPriceProduct(), 2, ",", " ").' euros TTC'."\n";
                $messageDetails .= $ligne;
                $total += $orderline->getPriceProduct()*$orderline->getQuantity();
            } 
            $messageDetails .= "\n".' Pour un Total de : '.number_format($total, 2, ",", " ").' euros TTC';

            $emailBuyer = (new Email())
            ->from('contact@lenidabijoux.fr')
            ->to($user->getEmail())
            ->subject('Votre Commande du Nid à Bijoux !')
            ->text(
            'Votre commande n° '.$order->getId().' du '.$order->getDate()->format('d-m-Y \à H\hi\m\i\ns\s').' est confirmée !'."\n"."\n".
            'Détails: '."\n".$messageDetails       
            );

            $mailer->send($emailBuyer); // send the confirmation email to the buyer

            $emailSeller = (new Email())
            ->from('contact@lenidabijoux.fr')
            ->to('contact@lenidabijoux.fr')
            ->replyTo($user->getEmail())
            ->subject('BON DE COMMANDE n° '.$order->getId().' du Nid à Bijoux !')
            ->text(
            'Commande n° '.$order->getId().' de '.$user->getFirstname().' '.$user->getLastname().' du '.$order->getDate()->format('d-m-Y \à H\hi\m\i\ns\s').' est confirmée !'."\n"."\n".
            'Email de l\'acheteur: '.$user->getEmail()."\n".
            'Téléphone de l\'acheteur: '.$user->getPhoneNumber()."\n"."\n".
            'Détails: '."\n".$messageDetails      
            );

            $mailer->send($emailSeller); // send the confirmation email to the seller

            return $this->json($order, 201);
            

        }else{

            return $this->json("this user doesn't exit", 404);

        }  
    }

    /**
     * @Route("/api/carts/{id}/users", name="api_carts_delete", methods={"DELETE"})
     */
    public function delete(int $id, OrderRepository $orderRepo, EntityManagerInterface $em, MailerInterface $mailer, OrderLineRepository $orderLineRepo, ProductRepository $productRepo): Response
    {
        $user = $this->getUser();

        if($user){
       
            if($order = $orderRepo->find($id)){

                $orderlines = $orderLineRepo->findBy(['orderEntity' => $order->getId()]); // retrieve the order from the database
    
                $messageDetails = '';
                $total = 0;
    
                foreach($orderlines as $orderline){ // making a text summary message of the passed order
                    $ligne = 'Article: '.$orderline->getLabelProduct().' / quantité: '.$orderline->getQuantity().' / prix unitaire: '.number_format($orderline->getPriceProduct(), 2, ",", " ").' euros TTC'."\n";
                    $messageDetails .= $ligne;
                    $total += $orderline->getPriceProduct()*$orderline->getQuantity();

                    $product = $productRepo->findOneBy(["name"=>$orderline->getLabelProduct(), "price"=>$orderline->getPriceProduct()]); // retrieve the product

                    $product->setStock($product->getStock() + $orderline->getQuantity());// update the stock
                    $em->persist($product);

                } 
                $messageDetails .= "\n".' Pour un Total de : '.number_format($total, 2, ",", " ").' euros TTC';

                $emailBuyer = (new Email()) // Preparing the email of cancellation to the buyer
                ->from('contact@lenidabijoux.fr')
                ->to($user->getEmail())
                ->subject('ANNULATION de votre Commande du Nid à Bijoux')
                ->text(
                'Votre commande n° '.$order->getId().' du '.$order->getDate()->format('d-m-Y \à H\hi\m\i\ns\s').' est ANNULÉE !'."\n"."\n".
                'Détails: '."\n".$messageDetails."\n"."\n".'ANNULÉE'        
                );

                $emailSeller = (new Email())// Preparing the email of cancellation to the seller
                ->from('contact@lenidabijoux.fr')
                ->to('contact@lenidabijoux.fr')
                ->replyTo($user->getEmail())
                ->subject('ANNULATION COMMANDE n° '.$order->getId().' du Nid à Bijoux')
                ->text(
                'Commande n° '.$order->getId().' de '.$user->getFirstname().' '.$user->getLastname().' du '.$order->getDate()->format('d-m-Y \à H\hi\m\i\ns\s').' est ANNULÉE !'."\n"."\n".
                'Email de l\'acheteur: '.$user->getEmail()."\n".
                'Téléphone de l\'acheteur: '.$user->getPhoneNumber()."\n"."\n".
                'Détails: '."\n".$messageDetails."\n"."\n".'ANNULÉE'    
                );

                $user->removeOrder($order); // delete the order with all order lines

                $em->flush();

                $mailer->send($emailSeller); // send the cancellation email to the seller
                $mailer->send($emailBuyer); // send the cancellation email to the buyer
            }

            return $this->json(null,204);

        }else{

            return $this->json("this user doesn't exit", 404);

        }  
    }
}