<?php

namespace App\Controller\Api\Gardienne;

use App\Entity\User;
use App\Entity\Enfant;
use App\Entity\Message;
use App\Service\FcmNotification;
use App\Service\SerialiseMessages;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/gardienne/message')]
class ApiGardienneMessageController extends AbstractController
{

    private $manager;
    public function __construct(ManagerRegistry $man)
    {
        $this->manager = $man->getManager();
    }
    #[Route('/{id}', name: 'app_api_gardienne_message', methods: ['GET'])]
    public function index(Enfant $enfant = null): JsonResponse
    {
        if ($enfant == null) {
            # code...
            return $this->json(['code'=>200,'message'=>'child not selected']);
        }

        //dd(count($enfant->getMessages()));
        return $this->json(SerialiseMessages::collectionToArray($enfant->getMessages()), 200, [], [AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
            return $object->getId();
        }]);
    }

    #[Route('/send/{enfant}/parent-{user}', name: 'app_api_gardienne_message_send', methods: ['POST'])]
    public function send(Enfant $enfant = null, User $user = null, Request $request): JsonResponse
    {

        if ($enfant == null) {
            # code...
            return $this->json([]);
        }
        if (!$enfant->getParent()->contains($user)) {
            # code...
            return $this->json(['code' => 403, 'message' => 'Action not authorize'], 403);
        }


        //  $messages = $enfant->getMessages();
        $messageData = json_decode($request->getContent(), true);
        if (isset($messageData['message']) && !empty($messageData['message'])) {
            $message = new Message();
            $message->setBody($messageData['message']);
            $message->setEnfant($enfant);
            $message->setGardienne($this->getUser());
            // dd($message);
            $this->manager->persist($message);
            $this->manager->flush();
            FcmNotification::sendToTopic('message: '.$enfant->getNom(),$messageData['message'],'parent-'.$user->getId());
            # code...
            return $this->json([
                'code' => 200,
                'message' => 'Message sent'
            ]);
        }

        return $this->json(['code' => 200, 'message' => 'Data  empty']);
    }
}
