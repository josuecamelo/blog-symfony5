<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/post', name: 'post_')]
class PostController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create()
    {
        return $this->render('post/create.html.twig');
    }

    #[Route('/save', name: 'save')]
    public function save(Request $request)
    {
        $data = $request->request->all();
        $post = new Post();
        $post->setTitle($data['title']);
        $post->setDescription($data['description']);
        $post->setContent($data['content']);
        $post->setSlug($data['slug']);
        $post->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));
        $post->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));


        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->persist($post);
        $doctrine->flush();

        $this->addFlash('success', 'Registro inserido com Sucesso');
        return $this->redirectToRoute('post_index');
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit($id)
    {
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);

        return $this->render('post/edit.html.twig',[
            'post' => $post
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    public function update(Request $request, $id)
    {
        $data = $request->request->all();
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);

        $post->setTitle($data['title']);
        $post->setDescription($data['description']);
        $post->setContent($data['content']);
        $post->setSlug($data['slug']);
        $post->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->persist($post);
        $doctrine->flush();

        $this->addFlash('success', 'Registro atualizado com Sucesso');
        return $this->redirectToRoute('post_index');
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete($id)
    {
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($post);
        $manager->flush();

        $this->addFlash('success', 'Registro removido com Sucesso');

        return $this->redirectToRoute('post_index');
    }
}
