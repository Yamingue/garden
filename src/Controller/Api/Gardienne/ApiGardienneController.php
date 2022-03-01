<?php

namespace App\Controller\Api\Gardienne;

use App\Entity\Gardienne;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiGardienneController extends AbstractController
{
    #[Route('/api/gardienne', name: 'api_gardienne')]
    public function index(): Response
    {
        /**@var Gardienne */
        $curentUser = $this->getUser();
        $user =[
            'id'=>$curentUser->getId(),
            'nom'=>$curentUser->getNom(),
            'prenom'=> $curentUser->getPrenom(),
            'email'=>$curentUser->getEmail(),
            'tel'=>$curentUser->getTel(),
            'photo'=>$curentUser->getPhoto(),
            'ecole'=>[
                'id'=>$curentUser->getEcole()->getId(),
                'nom'=>$curentUser->getEcole()->getName(),
            ],
            'salles'=>[
                'id'=>$curentUser->getSalles()->getId(),
                'nom'=>$curentUser->getSalles()->getNom(),
            ]
        ];
        return $this->json($user, 200, [], ['circular_reference_handler' => function ($object) {
            return $object->getId();
        }]);
    }
}
