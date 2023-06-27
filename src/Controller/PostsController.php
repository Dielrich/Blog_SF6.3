<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Form\PostsType;
use App\Repository\PostsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/articles',  name: 'app_posts_' )]
class PostsController extends AbstractController
{

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(PostsRepository $postsRepository): Response
    {

        return $this->render('posts/index.html.twig', [ 'posts' => $postsRepository->findAll() ]);
    }


    #[Route('/nouveau', name: 'new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_AUTHOR')]
    public function new(Request $request, PostsRepository $postsRepository): Response
    {
        $post = new Posts();
        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // L'auteur est enregistré avec l'utilisateur connecté
            $post->setAuthor($this->getUser());
            // Sauvegarde l'article en BDD
            $postsRepository->save($post, true);

            return $this->redirectToRoute('app_posts_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('posts/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }


    #[Route('/{slug}', name: 'show', methods: ['GET'])]
    public function show(Posts $post): Response
    {
        return $this->render('posts/show.html.twig', [
            'post' => $post,
        ]);
    }



    #[IsGranted('ROLE_AUTHOR')]
    #[Route('/{slug}/modifier', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Posts $post, PostsRepository $postsRepository): Response
    {
        // Test si l'utilisateur connecté est l'auteur de la publication
        if ( $post->getAuthor() === $this->getUser() )   {

            $form = $this->createForm(PostsType::class, $post);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $postsRepository->save($post, true);

                return $this->redirectToRoute('app_posts_index');
            }

            return $this->render('posts/edit.html.twig', [
                'post' => $post,
                'form' => $form,
            ]);

        } else {
            $this->addFlash('warning', 'Seul l\'auteur peut modifier cette publication');
            return $this->redirectToRoute('app_posts_index');
        }

    }



    #[Route('/{slug}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Posts $post, PostsRepository $postsRepository): Response
    {
        if ($this->isCsrfTokenValid('BLOG_SFjoli'.$post->getId(), $request->request->get('_token'))) {
            $postsRepository->remove($post, true);
        }

        return $this->redirectToRoute('app_posts_index', [], Response::HTTP_SEE_OTHER);
    }
}
