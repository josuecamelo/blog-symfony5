<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(){
        $posts = [
            [
                'id' => 1,
                'title' => 'Post 1',
                'created_at' => '2021-11-16 22:24:30'
            ],
            [
                'id' => 2,
                'title' => 'Post 2',
                'created_at' => '2021-11-16 22:24:30'
            ],
        ];

        return $this->render('index.html.twig',[
            'title' => 'Postagem Teste',
            'posts' => $posts
        ]);
    }
}