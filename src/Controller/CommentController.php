<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Movie;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class CommentController extends AbstractController
{



    #[Route('/commentaire/{slug}', name: 'app_comment')]
    public function index(Request $request, EntityManagerInterface $entityManager,$slug): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        $movie=$entityManager->getRepository(Movie::class)->findOneBy([
            'slug'=>$slug
        ]);

        if($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();
            $comment->setUser($user);
            $comment->setMovie($movie);
            $entityManager->persist($comment);

            $entityManager->flush();
            return $this->redirectToRoute('app_film',['slug' => $slug]);

        }



        if (!$movie){
            return $this->redirectToRoute('app_movies');
        }

        return $this->render('comment/index.html.twig',[
            'form' => $form->createView()

        ]);
    }
}
