<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\Movie;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/compte', name: 'app_account')]
    public function show(): Response
    {
        $user = $this->getUser();
        $movie = $this->entityManager->getRepository(Movie::class);
        $like = $this->entityManager->getRepository(Like::class)->findBy(["user" => $user]);



        return $this->render('account/index.html.twig', [
            'movies' => $movie,
            'users' => $user,
            'likes' => $like
        ]);
    }






}
