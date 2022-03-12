<?php

namespace App\Controller\Api\Gardienne;

use App\Entity\User;
use App\Entity\Enfant;
use App\Entity\Message;
use App\Service\SerialiseMessages;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

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
            return $this->json([]);
        }
   
        //dd(count($enfant->getMessages()));
        return $this->json(SerialiseMessages::collectionToArray($enfant->getMessages()),200,[],[AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object){
            return $object->getId();
        }]);
    }

    #[Route('/send/{enfant}/parent-{user}', name: 'app_api_gardienne_message_send', methods: ['POST'])]
    public function send(Enfant $enfant=null ,User $user=null,Request $request): JsonResponse
    {
       if (!$enfant->getParent()->contains($user)) {
           # code...
           return $this->json(['code'=>403,'message'=>'Action not authorize'],403);
       }
        
        if ($enfant == null) {
            # code...
            return $this->json([]);
        }
      //  $messages = $enfant->getMessages();
        $messageData = json_decode($request->getContent(),true);
        if (isset($messageData['message']) && !empty($messageData['message']) ) {
            $message = new Message();
            $message->setBody($messageData['message']);
            $message->setEnfant($enfant);
            $message->setUser($user);
            $message->setGardienne($this->getUser());
            // dd($message);
            $this->manager->persist($message);
            $this->manager->flush();
            # code...
            return $this->json([
                'code'=>200,
                'message'=>'Message sent'
            ]);
        }

        return $this->json(['code'=>200,'message'=>'Data  empty']);
    }
}
