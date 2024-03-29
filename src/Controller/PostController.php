<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class PostController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Post::class);
        $posts = $repository->findAll();

        // Get the last post to show first
        $posts = array_reverse($posts);

        return $this->render('post/index.html.twig', [
            "posts" => $posts
        ]);
    }

    #[Route('/post/new')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUser($this->getUser());
            $entityManager = $doctrine->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
            return $this->redirectToRoute("home");
        }
        return $this->render('post/form.html.twig', [
            "post_form" => $form->createView()
        ]);
    }

    #[Route('/post/delete/{id<\d+>}', name: 'delete-post')]
    public function delete(Post $post, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");

        // Fetch the currently logged-in user
        $currentUser = $this->getUser();

        // Check if the user is the creator of the post
        if ($post->getUser() !== $currentUser) {
            throw new AccessDeniedException('You are not allowed to delete this post.');
        }

        $entityManager = $doctrine->getManager();
        $entityManager->remove($post);
        $entityManager->flush();
        return $this->redirectToRoute("home");
    }

    #[Route('/post/edit/{id<\d+>}', name: 'edit-post')]
    public function update(Request $request, Post $post, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");

        $currentUser = $this->getUser();
        if ($post->getUser() !== $currentUser) {
            throw new AccessDeniedException('You are not allowed to edit this post.');
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
            return $this->redirectToRoute("home");
        }
        return $this->render('post/form.html.twig', [
            "post_form" => $form->createView()
        ]);
    }
}
