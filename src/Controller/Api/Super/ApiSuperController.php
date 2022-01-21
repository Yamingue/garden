<?php

namespace App\Controller\Api\Super;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiSuperController extends AbstractController
{
    /**
     * @Route("/api/super", name="api_super")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ApiSuperController.php',
        ]);
    }
}
