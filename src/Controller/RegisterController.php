<?php

namespace App\Controller;

use App\Classes\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $notification = null;
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();

            $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

            if (!$search_email)
            {
                $password= $hasher->hashPassword($user, $user->getPassword());
                $user->setPassword($password);
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $mail = new Mail();
                $content = "Bonjour".$user->getFirstname()."<br/>Bienvenue sur Netflux, la première plateforme de VOD 2.0.<br/>Votre inscription à bien été prise en compte";
                $mail->send($user->getEmail(),$user->getFirstname(),'Bienvenue sur NETFLUX, la plateforme de VOD 2.0',$content);

                $notification = 'Compte crée';

            } else{
                $notification = "L'email que vous avez renseigné existe déjà ";
            }



        }

        else if($form->isSubmitted() && !($form->isValid())){
            $notification = "Verifiez votre saisie";
        }else{
            $notification = '';
        }

        return $this->render('register/index.html.twig',[
            'form' => $form->createView(),
            'notification'=>$notification
        ]);
    }
}
