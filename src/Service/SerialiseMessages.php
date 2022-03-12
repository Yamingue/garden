<?php

namespace App\Service;

use App\Entity\Message;
use Doctrine\Common\Collections\Collection;

class SerialiseMessages
{

    static function convertToArray(Message $mess)
    {
        $message = [
            'id' => $mess->getId(),
            'body' => $mess->getBody(),
            'enfant' => [
                'id' => $mess->getEnfant()->getId(),
                'nom' => $mess->getEnfant()->getNom(),
                'prenom' => $mess->getEnfant()->getPrenom(),
            ],
            'user' => [
                'id' => $mess->getUser()->getId(),
                'email' => $mess->getUser()->getEmail(),
                'nom' => $mess->getUser()->getNom(),
                'prenom' => $mess->getUser()->getPrenom(),
                'photo' => null
            ],
            'gardienne' => [
                'id' => $mess->getGardienne()->getId(),
                'email' => $mess->getGardienne()->getEmail(),
                'nom' => $mess->getGardienne()->getNom(),
                'prenom' => $mess->getGardienne()->getPrenom(),
                'photo' => $mess->getGardienne()->getPhoto()
            ]
        ];

        return $message;
    }

    static public function collectionToArray(Collection $collection)
    {
        $messages = [];
        foreach ($collection as $message) {
            $messages[] = self::convertToArray($message);
            # code...
        }

        return $messages;
    }
}
