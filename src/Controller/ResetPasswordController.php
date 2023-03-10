<?php

namespace App\Controller;

use App\Classes\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }



    #[Route('/mot-de-passe-oublie', name: 'app_reset_password')]
    public function index(Request $request): Response
    {

        if ($this->getUser())
        {
            return $this->redirectToRoute('app_home');
        }

        if ($request->get('email')){
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));

            if ($user)
            {
                // 1 : Enregistrer en base la demande de reset_password avec user, token, createdAt.
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new \DateTime());
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();

                // 2 : Envoyer un email à l'utilisateur avec un lien lui permettant de mettre à jour son mot de passe.


                $url = $this->generateUrl('app_update_password',[
                    'token'=>$reset_password->getToken()
                ]);

                $content='Bonjour '.$user->getFirstname()."<br/> Votre demande de réinitialisation de mot de passe à été prise en Compte.<br/><br/>";
                $content .= "Merci de bien vouloir cliquer sur le lien suivant afin de <a href='http://127.0.0.1:8000$url'> modifier votre mot de passe.</a>";
                $mail = new Mail();
                $mail->send($user->getEmail(),$user->getFirstname().''.$user->getLastname(),'Réinitialiser votre mot de passe NETFLUX',$content);
                $this->addFlash('notice','Vous allez recevoir un mail afin de réinitialiser votre Mot de passe.');
            }else{
                $this->addFlash('notice','Cette adresse e-mail est inconnue');
            }
        }
        return $this->render('reset_password/index.html.twig');
    }




    #[Route('/modifier-mot-de-passe/{token}', name: 'app_update_password')]
    public function update($token, Request $request, UserPasswordHasherInterface $encoder)
    {
        $reset_password=$this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);


        if (!$reset_password){
            return $this->redirectToRoute('app_reset_password');
        }
        else{

            //vérification si le crédit = now - 3h
            $now =new \DateTime();
            if ($now > $reset_password->getCreatedAt()->modify('+ 3 hour'))
            {
                $this->addFlash('notice','Votre demande de mot de passe a expiré. Merci de la renouveller.');
                return $this->redirectToRoute('app_reset_password');
            }

            $form = $this->createForm(ResetPasswordType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $new_pwd = $form->get('new_password')->getData();

                // Encodage MDP
                $password = $encoder->hashPassword($reset_password->getUser(),$new_pwd);
                $reset_password->getUser()->setPassword($password);
                $this->entityManager->flush();
                $this->addFlash('notice', 'Votre mot de passe à été mis a jour.');

                return $this->redirectToRoute('app_login');
            }

            return $this->render('reset_password/update.html.twig',[
                'form'=>$form->createView()
                ]);



        }
    }

}
