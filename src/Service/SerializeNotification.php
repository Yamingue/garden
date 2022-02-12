<?php

namespace App\Service;

use App\Entity\Notification;

class SerializeNotification
{
    static function converteToArray(Notification $notif)
    {
        $enfant = $notif->getEnfant();
        $not = [
            'id' => $notif->getId(),
            'updateAt' => $notif->getUpdateAt(),
            'createAt' => $notif->getCreateAt(),
            'time' => $notif->getTime(),
            'waiting' => $notif->getWaiting(),
            'close' => $notif->getClose(),
            'restTime' => $notif->getRestTime(),
            'isReady' => $notif->getIsReady(),
            'enfant' => [
                'id' => $enfant->getId(),
                'photo' => $enfant->getPhoto(),
                'nom' => $enfant->getNom(),
                'prenom' => $enfant->getPrenom(),
                'age' => $enfant->getAge()
            ]
        ];
        return $not;
    }

    static function collectionToArray(Array $notifications)
    {
        $notifs = [];
        foreach ($notifications as $notif) {
            
            $notifs[] = self::converteToArray($notif);
        }

        return $notifs;
    }
}
