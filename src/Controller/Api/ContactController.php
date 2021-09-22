<?php

namespace App\Controller\Api;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/api/contacts", name="api_contacts", methods={"POST"})
     */
    public function send(Request $request, MailerInterface $mailer): Response
    {
        $infoFromClientAsArray = json_decode($request->getContent(), true);

        $form = $this->createForm(ContactType::class, null, ['csrf_protection' => false]);

        $form->submit($infoFromClientAsArray);

        if($form->isValid()){

            $message = $infoFromClientAsArray['message'];

            $emailSeller = (new Email())
            ->from('contact@lenidabijoux.fr')
            ->to('contact@lenidabijoux.fr')
            ->replyTo($infoFromClientAsArray['email'])
            ->subject('MESSAGE du Nid à Bijoux !')
            ->text(
            'Vous avez reçu un message de '.$infoFromClientAsArray['firstname'].' '.$infoFromClientAsArray['lastname'].' ( '.$infoFromClientAsArray['email'].' )'."\n"."\n".
            'Message: '."\n"."\n".
            '" '.$message.' "' 
            );

            $mailer->send($emailSeller); // send the message email to the seller
            
            $emailBuyer = (new Email())
            ->from('contact@lenidabijoux.fr')
            ->to($infoFromClientAsArray['email'])
            ->subject('Votre message a bien été envoyé au Nid à Bijoux')
            ->text(
            'Votre message a bien été envoyé: '."\n".
            '" '.$message.' "'       
            );

            $mailer->send($emailBuyer); // send a confirmation email to the buyer

            return $this->json($message,200);

        }else{

            return $this->json((string) $form->getErrors(true,false),400);

        }

    }
}
