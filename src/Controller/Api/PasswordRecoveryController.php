<?php

namespace App\Controller\Api;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordRecoveryController extends AbstractController
{
    /**
     * @Route("/api/passwords_recovery", name="api_passwords_recovery", methods={"PATCH"})
     */
    public function send(Request $request, UserRepository $userRepo, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer): Response
    {

        $infoFromClientAsObject = json_decode($request->getContent(), false);

        if($user = $userRepo->findOneBy(["email"=>$infoFromClientAsObject->email])){ // verify the email

            $compareFirstname = strcasecmp ($user->getFirstname() , $infoFromClientAsObject->firstname);
            $compareLastname = strcasecmp ($user->getLastname() , $infoFromClientAsObject->lastname); 
            // verify the first name and the last name with case insensitive

            if($compareFirstname == 0 && $compareLastname == 0){

                $newPasswordPlain = uniqid(); // create a new password
                $newPasswordEncoded = $passwordEncoder->encodePassword($user, $newPasswordPlain);

                $user->setPassword($newPasswordEncoded);
                $em->flush();

                $emailBuyer = (new Email()) // send the mail with new password to the buyer
                ->from('contact@lenidabijoux.fr')
                ->to($user->getEmail())
                ->subject('Nid à Bijoux')
                ->text(
                'Votre nouveau mot de passe est: '.$newPasswordPlain."\n".
                'Connectez-vous avec ce nouveau mot de passe puis modifiez le dans la section "Mon Compte"'
                );
                $mailer->send($emailBuyer);

                return $this->json($user->getPassword(),200);

            }else{

                return $this->json("this user doesn't exist",404);
            }

        }else{

            return $this->json("this user doesn't exist",404);
        }
    }
}
