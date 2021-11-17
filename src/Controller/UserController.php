<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/users', name: 'user_')]
class UserController extends AbstractController
{
    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $user = $form->getData();
            $user->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));
            $user->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Registro Inserido com Sucesso');

            return $this->redirectToRoute('user_create');
        }

        //dump($this->getDoctrine()->getRepository(User::class)->findAll());

        return $this->render('user/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}