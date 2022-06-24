<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountPasswordController extends AbstractController
{
    /**
     * @Route("/compte/modifier-mot-de-passe", name="app_account_password")
     */





    public function index(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager): Response

    {

           $notification =null;

            $user = $this->getUser();
           
           $form =$this->createForm(ChangePasswordType::class,$user);

           $form->handleRequest($request);
           $old_pwd=$form->get('old_password')->getData();

           if($form->isSubmitted() && $form->isValid()){
              if($encoder->isPasswordValid($user, $old_pwd)){
                   $new_pwd=$form->get('new_password')->getData();
                   $password=$encoder->encodePassword($user, $new_pwd);
                    
                   $user->setPassword($password);

                //    $entityManager->persist($user); en cas de mise à jour
                   $entityManager->flush();

                   $notification="Votre mot de passe à  bien été mise à jour";

              } else{
                $notification="Votre mot de passe actuel n'est pas le bon";
              }

           }

        return $this->render('account/password.html.twig',[
     
            'form'=>$form->createView(),
            'notification'=>$notification

        ]);
    }
}
