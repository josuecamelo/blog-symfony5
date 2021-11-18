<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/categories', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $category = $form->getData();
            $category->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));
            $category->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($category);
            $manager->flush();

            $this->addFlash('success', 'Registro Inserido com Sucesso');

            return $this->redirectToRoute('category_create');
        }

        //dump($this->getDoctrine()->getRepository(Post::class)->findAll());

        return $this->render('category/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Request $request, $id): Response
    {
        $post = $this->getDoctrine()
            ->getRepository(Category::class)
            ->find($id);

        $form = $this->createForm(CategoryType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $post = $form->getData();
            $post->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

            $manager = $this->getDoctrine()->getManager();

            $manager->flush();

            $this->addFlash('success', 'Registro Alterado com Sucesso');

            return $this->redirectToRoute('category_edit', ['id' => $id]);
        }

        //dump($this->getDoctrine()->getRepository(User::class)->findAll());

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete($id)
    {
        $post = $this->getDoctrine()
            ->getRepository(Category::class)
            ->find($id);

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($post);
        $manager->flush();

        $this->addFlash('success', 'Registro removido com Sucesso');

        return $this->redirectToRoute('category_index');
    }
}
