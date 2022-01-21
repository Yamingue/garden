<?php

namespace App\Controller\Gardeinne\Api;

use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/gardienne/api")
 */
class GardienneApiController extends AbstractController
{
    private $notificationRepository;
    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }
    /**
     * @Route("/onway", name="onway_gardienne_api")
     */
    public function index(): Response
    {
        $gardienne = $this->getUser();
        $salle = $gardienne->getSalles();

        return $this->json($this->notificationRepository->findSalleOnWay($salle),200,[],['circular_reference_handler' => function ($object) {
            return $object->getId();
         }]);
    }

    /**
     * @Route("/parking", name="parking_gardienne_api")
     */
    public function inparking(): Response
    {
        $gardienne = $this->getUser();
        $salle = $gardienne->getSalles();

        return $this->json($this->notificationRepository->findSalleParking($salle),200,[],['circular_reference_handler' => function ($object) {
            return $object->getId();
         }]);
    }
}
