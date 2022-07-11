<?php

namespace App\Service;

use Exception;
use Symfony\Component\HttpClient\HttpClient;


class FcmNotification
{

    static function sendToTopic($title, $body, $topic)
    {
        $serverKey = "AAAAXmiucJo:APA91bFoOTd99qFBvFAcX8mpeNBWGa0UPgNfmiN6t7OkQsoJ3fiSQIiFKqsNEiWtZT44n8G5hJs649ctpyWjtMxl41kIdgLZDzNY4dkOSsrrm_wv2pX05fovIwwv9JetPA7AXWSE0Nmp";

        $client =  HttpClient::create([
            'max_redirects' => 7
        ]);
        try {
            //code...

            $response =  $client->request('POST', 'https://fcm.googleapis.com/fcm/send', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'key='.$serverKey
                ],
                'body' => json_encode([
                    "collapse_key" => "type_a",
                    'to' => "/topics/" . $topic,
                    "notification" => [
                        "title" => $title,
                        "body" => $body,
                        "sound" => 'default',
                        "content_available" => true,
                        "priority" => "high"
                    ]
                ])
            ]);

            $content = $response->toArray();
        } catch (Exception $th) {
            //throw $th;
            
        }
        return $content;
    }

    static function sendTodevice($title, $body, $token)
    {

        $client =  HttpClient::create([
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'key=AAAA19vK4i8:APA91bFZUbxcOSGsNp6SZK0T0ozmD0v7Y9-71O7gTtTgah8ZrMgjEORacWhKIdOX-FJouxpRN8UgTd9GeU0OSAUWf4ToytEvGRPL0aqYMYmneIHgxJIIFWym3gsYqkaBxP3FHO7H4Xwz'
            ]
        ]);
        try {


            $response = $client->request('POST', 'https://fcm.googleapis.com/fcm/send', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'key=AAAA19vK4i8:APA91bFZUbxcOSGsNp6SZK0T0ozmD0v7Y9-71O7gTtTgah8ZrMgjEORacWhKIdOX-FJouxpRN8UgTd9GeU0OSAUWf4ToytEvGRPL0aqYMYmneIHgxJIIFWym3gsYqkaBxP3FHO7H4Xwz'
                ],
                'body' => json_encode([
                    "collapse_key" => "type_a",
                    'to' => $token,
                    "notification" => [
                        "title" => $title,
                        "body" => $body,
                        "sound" => 'default',
                        "content_available" => true,
                        "priority" => "high"
                    ]
                ])
            ]);

            $content = $response->toArray();
        } catch (Exception $th) {
            //throw $th;
            dd($th->getMessage());
        }
        return $content;
    }
}
