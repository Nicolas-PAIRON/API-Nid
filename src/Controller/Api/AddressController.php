<?php

namespace App\Controller\Api;

use App\Entity\AddressBill;
use App\Entity\AddressDelivery;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddressController extends AbstractController
{
    /**
     * @Route("/api/addressbill/users", name="api_addressbill_add", methods={"POST"})
     */
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if ($user) {
            $infoFromClientAsArray = json_decode($request->getContent(), true); //we retrieve the provided data
            
            if (empty($infoFromClientAsArray)) {
                return $this->json("request is not valid", 400);
            }

            $addressBill = new AddressBill();
            $form = $this->createForm(AddressType::class, $addressBill, ['csrf_protection' => false]); // we stop the csrf token verification because the data is sent by a react form

            // we simulate the submission of the form to active the constraints validation
            $form->submit($infoFromClientAsArray);

            if ($form->isValid()) {
                $addressBill->setUser($user);
                $em->persist($addressBill);
                $em->flush();

                return $this->json($user->getAddressBill(), 201);
            } else {
                return $this->json((string) $form->getErrors(true, false), 400); // sent the errors of the constraints validation
            }
        } else {
            return $this->json("this user doesn't exit", 404);
        }
    }

    /**
     * @Route("/api/addressbill/users", name="api_addressbill_edit", methods={"PATCH"})
     */
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if ($user) {

            $infoFromClientAsArray = json_decode($request->getContent(), true);

            if (empty($infoFromClientAsArray)) {
                return $this->json("request is not valid", 400);
            }


            $addressBill = $user->getAddressBill();

            $form = $this->createForm(AddressType::class, $addressBill, ['csrf_protection' => false]);

            $form->submit($infoFromClientAsArray, false);

            if ($form->isValid()) {
                $em->flush();
            return $this->json($user->getAddressBill(),200);
            }else{
            return $this->json((string) $form->getErrors(true, false), 400);
            }
    
        }else{
        return $this->json("this user doesn't exit", 404);
        }
    }

    /**
     * @Route("/api/addressbill/users", name="api_addressbill_read", methods={"GET"})
     */
    public function read(Request $request): Response
    {
        $user = $this->getUser();

        if($user){
            return $this->json($user->getAddressBill(),200);
        }else{
            return $this->json("This user doesn't exist",404);
        }
    }

    /**
     * @Route("/api/addressdelivery/users", name="api_addressdelivery_add", methods={"POST"})
     */
    public function addDelivery(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if ($user) {
            $infoFromClientAsArray = json_decode($request->getContent(), true); //we retrieve the provided data
            
            if (empty($infoFromClientAsArray)) {
                return $this->json("request is not valid", 400);
            }

            $addressDelivery = new AddressDelivery();
            $form = $this->createForm(AddressType::class, $addressDelivery, ['csrf_protection' => false]); // we stop the csrf token verification because the data is sent by a react form

            // we simulate the submission of the form to active the constraints validation
            $form->submit($infoFromClientAsArray);

            if ($form->isValid()) {
                $addressDelivery->setUser($user);
                $em->persist($addressDelivery);
                $em->flush();

                return $this->json($user->getAddressDelivery(), 201);
            } else {
                return $this->json((string) $form->getErrors(true, false), 400); // sent the errors of the constraints validation
            }
        } else {
            return $this->json("this user doesn't exit", 404);
        }
    }

    /**
     * @Route("/api/addressdelivery/users", name="api_addressdelivery_edit", methods={"PATCH"})
     */
    public function editDelivery(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if ($user) {

            $infoFromClientAsArray = json_decode($request->getContent(), true);

            if (empty($infoFromClientAsArray)) {
                return $this->json("request is not valid", 400);
            }


            $addressDelivery = $user->getAddressDelivery();

            $form = $this->createForm(AddressType::class, $addressDelivery, ['csrf_protection' => false]);

            $form->submit($infoFromClientAsArray, false);

            if ($form->isValid()) {
                $em->flush();
            return $this->json($user->getAddressDelivery(),200);
            }else{
            return $this->json((string) $form->getErrors(true, false), 400);
            }
    
        }else{
        return $this->json("this user doesn't exit", 404);
        }
    }

     /**
     * @Route("/api/addressdelivery/users", name="api_addressdelivery_read", methods={"GET"})
     */
    public function readd(Request $request): Response
    {
        $user = $this->getUser();

        if($user){
            return $this->json($user->getAddressDelivery(),200);
        }else{
            return $this->json("user doesn't exist",404);
        }
    }
}