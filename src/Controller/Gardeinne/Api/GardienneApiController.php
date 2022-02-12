<?php

namespace App\Controller\Gardeinne\Api;

use App\Entity\Gardienne;
use App\Repository\NotificationRepository;
use App\Service\SerializeNotification;
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
        /**@var Gardienne */
        $gardienne = $this->getUser();
        $salle = $gardienne->getSalles();

        return $this->json(SerializeNotification::collectionToArray($this->notificationRepository->findSalleOnWay($salle)));
    }

    /**
     * @Route("/parking", name="parking_gardienne_api")
     */
    public function inparking(): Response
    {
        /**@var Gardienne */
        $gardienne = $this->getUser();
        $salle = $gardienne->getSalles();

        return $this->json(SerializeNotification::collectionToArray($this->notificationRepository->findSalleParking($salle)));
    }
}
