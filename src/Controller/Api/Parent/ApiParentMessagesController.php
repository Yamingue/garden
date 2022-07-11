<?php

namespace App\Controller\Api\Parent;

use App\Entity\Enfant;
use App\Entity\Message;
use App\Service\FcmNotification;
use App\Service\SerialiseMessages;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/parent/messages')]
class ApiParentMessagesController extends AbstractController
{

    private $manager;
    public function __construct(ManagerRegistry $man)
    {
        $this->manager = $man->getManager();
    }
    #[Route('/{id}', name: 'app_api_parent_messages', methods: ['GET'])]
    public function index(Enfant $enfant = null): JsonResponse
    {
        if ($enfant->getParent()->contains($this->getUser())) {
            # code...
            return $this->json(SerialiseMessages::collectionToArray($enfant->getMessages()));
        }
        return $this->json([]);
    }
    #[Route('/send/{id}', name: 'app_api_parent_messages_send', methods: ['POST'])]
    public function send(Enfant $enfant = null, Request $request): JsonResponse
    {

        if ($enfant == null) {
            # code...
            return $this->json(['code'=>200,'message'=>'child not selected']);
        }

        if (!$enfant->getParent()->contains($this->getUser())) {
            # code...
            return $this->json(['code' => 403, 'message' => 'Action not authorize'], 403);
        }
        $messageData = json_decode($request->getContent(), true);
        if (isset($messageData['message']) && !empty($messageData['message'])) {
            $message = new Message();
            $message->setBody($messageData['message']);
            $message->setEnfant($enfant);
            $message->setUser($this->getUser());
            // dd($message);
            FcmNotification::sendToTopic($enfant->getNom(),$messageData['message'],'class-'.$enfant->getSalle()->getId());
            $this->manager->persist($message);
            $this->manager->flush();
            # code...
            return $this->json([
                'code' => 200,
                'message' => 'Message sent'
            ]);
        }

        return $this->json(['code' => 200, 'message' => 'Data  empty']);
    }
}
