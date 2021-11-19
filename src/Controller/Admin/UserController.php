<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

#[Route('/admin/users', name: 'user_')]
class UserController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, UserPasswordEncoderInterface $passwordEncoder, MailerService $mailerService): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $user = $form->getData();

            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $user->setRoles('ROLE_USER');

            $user->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));
            $user->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            //Envio do email
            $data['subject'] = '[Blog SF4] - Usuário criado com sucesso';
            $data['email'] = $user->getEmail();
            $data['name'] = $user->getFirstName();

            $mailerService->sendMail($data);
            //Final Envio da Mensagem

            $this->addFlash('success', 'Registro Inserido com Sucesso');

            return $this->redirectToRoute('user_create');
        }

        //dump($this->getDoctrine()->getRepository(User::class)->findAll());

        return $this->render('user/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Request $request, $id, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $user = $form->getData();

            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $user->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

            $manager = $this->getDoctrine()->getManager();

            $manager->flush();

            $this->addFlash('success', 'Registro Alterado com Sucesso');

            return $this->redirectToRoute('user_edit', ['id' => $id]);
        }

        //dump($this->getDoctrine()->getRepository(User::class)->findAll());

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function remove($id)
    {
        $doctrine = $this->getDoctrine();

        $user = $doctrine->getRepository(User::class)->find($id);

        $manager = $doctrine->getManager();
        $manager->remove($user);
        $manager->flush();

        $this->addFlash('success', 'Registro removido com sucesso!');
        return $this->redirectToRoute('user_index');
    }
}
