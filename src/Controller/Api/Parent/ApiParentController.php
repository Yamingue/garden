<?php

namespace App\Controller\Api\Parent;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiParentController extends AbstractController
{
    /**
     * @Route("/api/parent", name="api_parent")
     */
    public function index(): Response
    {
        return $this->json($this->getUser(),200,[],['circular_reference_handler' => function ($object) {
            return $object->getId();
         }]);
    }
}
