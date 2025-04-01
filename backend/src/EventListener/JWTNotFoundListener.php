<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class JWTNotFoundListener
{
    public function onJWTNotFound(JWTNotFoundEvent $event)
    {

    $request = $event->getRequest();

        $data = [
            'status'  => '403 Forbidden',
            'message' => 'Token não encontrado. Por favor, faça login para obter um novo token.',
        ];

        $event->setResponse(new JsonResponse($data, 403));
    }
}