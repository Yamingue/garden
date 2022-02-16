<?php

namespace App\Controller\Gardeinne;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/gardienne")
 */
class GardienneController extends AbstractController
{
    /**
     * @Route("/", name="gardienne")
     */
    public function index(): Response
    {
        return $this->render('gardienne/index.html.twig', [
            'controller_name' => 'GardienneController',
        ]);
    }

     /**
     * @Route("/parking", name="gardienne_parking")
     */
    public function parking(): Response
    {
        return $this->render('gardienne/parking.html.twig', [
            'controller_name' => 'GardienneController',
        ]);
    }
}
