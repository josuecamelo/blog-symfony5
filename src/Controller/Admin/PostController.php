<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/posts', name: 'post_')]
class PostController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(): Response
    {
        $authUser = $this->getUser();
        $roles = $authUser->getRoles();

        if(in_array('ROLE_AUTHOR',$roles)){
            $posts = $authUser->getPosts();
        }

        if(in_array('ROLE_ADMIN',$roles)){
            $posts = $this->getDoctrine()
                ->getRepository(Post::class)
                ->findAll();
        }

        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $post = $form->getData();
            $post->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));
            $post->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

            /*$user = $this->getDoctrine()
                ->getRepository(User::class)
                ->find(1);
            $post->setAuthor($user);*/

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($post);
            $manager->flush();

            $this->addFlash('success', 'Registro Inserido com Sucesso');

            return $this->redirectToRoute('user_create');
        }

        //dump($this->getDoctrine()->getRepository(Post::class)->findAll());

        return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /*#[Route('/save', name: 'save')]
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
    }*/

    /*#[Route('/edit/{id}', name: 'edit')]
    public function edit($id)
    {
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);

        return $this->render('post/edit.html.twig',[
            'post' => $post
        ]);
    }*/

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Request $request, $id): Response
    {
        $post = $this->getDoctrine()
            ->getRepository(post::class)
            ->find($id);

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $post = $form->getData();
            $post->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

            $manager = $this->getDoctrine()->getManager();

            $manager->flush();

            $this->addFlash('success', 'Registro Alterado com Sucesso');

            return $this->redirectToRoute('post_edit', ['id' => $id]);
        }

        //dump($this->getDoctrine()->getRepository(User::class)->findAll());

        return $this->render('post/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /*#[Route('/update/{id}', name: 'update')]
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
    }*/

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
