<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
     /**
     * @Route("/home", name="home")
     */
    public function home(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
     /**
     * @Route("/index", name="home")
     */
    public function home1(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
