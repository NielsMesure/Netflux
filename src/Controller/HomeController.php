<?php

namespace App\Controller;



use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $movie = $this->entityManager->getRepository(Movie::class)->findByIsBest(1);
        $headers = $this->entityManager->getRepository(Movie::class)->findAll();
        return $this->render('home/index.html.twig',[
            'movies'=>$movie,
            'illustrationHeaders'=>$headers
        ]);
    }
}
