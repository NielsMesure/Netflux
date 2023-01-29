<?php

namespace App\Controller;


use App\Classes\Search;
use App\Entity\Comment;
use App\Entity\Movie;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieListController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/catalogue', name: 'app_movies')]
    public function index(Request $request): Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class,$search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $movies=$this->entityManager->getRepository(Movie::class)->findWithSearch($search);
        }else{
            $movies=$this->entityManager->getRepository(Movie::class)->findAll();
        }

        return $this->render('movie_list_controlleur/index.html.twig',[
            'movies'=>$movies,
            'form'=> $form->createView()
        ]);
    }



    #[Route('/film/{slug}', name: 'app_film')]
    public function show($slug): Response
    {

        $movie=$this->entityManager->getRepository(Movie::class)->findOneBy([
            'slug'=>$slug
        ]);

        $comments=$this->entityManager->getRepository(Comment::class)->findBy(["movie" => $movie]);

        if (!$movie){
            return $this->redirectToRoute('app_movies');
        }

        return $this->render('movie_list_controlleur/show.html.twig',[
            'movie'=>$movie,
            'comments'=>$comments
        ]);
    }
}
