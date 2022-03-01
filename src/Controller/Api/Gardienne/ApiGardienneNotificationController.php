<?php

namespace App\Controller\Api\Gardienne;

use App\Entity\Enfant;
use App\Entity\Gardienne;
use App\Service\SerializeNotification;
use App\Repository\NotificationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiGardienneNotificationController extends AbstractController
{
    private $notificationRepository;
    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }
    #[Route('/api/gardienne/notification', name: 'api_gardienne_notification')]
    public function index()
    {
        /**@var Gardienne */
        $gardienne = $this->getUser();
        $salle = $gardienne->getSalles();

        return $this->json(SerializeNotification::collectionToArray($this->notificationRepository->findSalleOnWay($salle)));
    }

    #[Route('/api/gardienne/parking', name: 'api_gardienne_parking')]
    public function inparking()
    {
        /**@var Gardienne */
        $gardienne = $this->getUser();
        $salle = $gardienne->getSalles();

        return $this->json(SerializeNotification::collectionToArray($this->notificationRepository->findSalleParking($salle)));
    }
    #[Route('/api/gardienne/ready/{id}', name: 'api_gardienne_ready')]
    public function ready(Enfant $e)
    {
        /**@var Gardienne */
        $gardienne = $this->getUser();
        $salle = $gardienne->getSalles();

        return $this->json(SerializeNotification::collectionToArray($this->notificatonRepository->findSalleParking($salle)));
    }
}
