<?php

namespace App\Controller;


use App\Classes\Search;
use App\Entity\Comment;
use App\Entity\Like;
use App\Entity\Movie;
use App\Entity\User;
use App\Form\SearchType;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\True_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

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
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $movies = $this->entityManager->getRepository(Movie::class)->findWithSearch($search);
        } else {
            $movies = $this->entityManager->getRepository(Movie::class)->findAll();
        }

        return $this->render('movie_list_controlleur/index.html.twig', [
            'movies' => $movies,
            'form' => $form->createView()
        ]);
    }


    #[Route('/film/{slug}', name: 'app_film')]
    public function show($slug): Response
    {


        $movie = $this->entityManager->getRepository(Movie::class)->findOneBy([
            'slug' => $slug
        ]);


        $comments = $this->entityManager->getRepository(Comment::class)->findBy(["movie" => $movie]);

        if (!$movie) {
            return $this->redirectToRoute('app_movies');
        }

        return $this->render('movie_list_controlleur/show.html.twig', [
            'movie' => $movie,
            'comments' => $comments
        ]);
    }


    #[Route('/favoris/ajout/{slug}', name: 'app_add_favorites')]
    public function addFavorites($slug,EntityManagerInterface $entityManager): Response
    {
        $like = new Like();

        $movie=$entityManager->getRepository(Movie::class)->findOneBy([
            'slug'=>$slug
        ]);
        if(!$movie){
            throw new NotFoundHttpException('Pas de films trouvés');
        }
        $user = $this->getUser();
        $like->setUser($user);
        $like->setMovie($movie);
        $entityManager->persist($like);
        $entityManager->flush();
        $entityManager->persist($movie);
        $entityManager->flush();

        return $this->redirectToRoute('app_film',['slug' => $slug]);
    }

    #[Route('/favoris/remove/{slug}', name: 'app_remove_favorites')]
    public function removeFavorites(Like $likeMovie,$slug,EntityManagerInterface $entityManager): Response
    {


        $likeMovie->removeLike($this->getUser());
        $entityManager->persist($likeMovie);
        $entityManager->flush();

        return $this->redirectToRoute('app_film',['slug' => $slug]);
    }


}
