<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GardienneController extends AbstractController
{
    /**
     * @Route("/gardienne", name="gardienne")
     */
    public function index(): Response
    {
        return $this->render('gardienne/index.html.twig', [
            'controller_name' => 'GardienneController',
        ]);
    }
}
