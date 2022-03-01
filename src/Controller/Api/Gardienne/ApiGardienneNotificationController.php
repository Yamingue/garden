<?php

namespace App\Controller\Api\Gardienne;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiGardienneNotificationController extends AbstractController
{
    #[Route('/api/gardienne/notification', name: 'api_gardienne_notification')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ApiGardienneNotificationController.php',
        ]);
    }
}
