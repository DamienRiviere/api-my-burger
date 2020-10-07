<?php

namespace App\Domain\Common\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class JWTNotFoundListener
 * @package App\Domain\Common\EventListener
 */
final class JWTNotFoundListener
{

    /**
     * @param JWTNotFoundEvent $event
     */
    public function onJWTNotFound(JWTNotFoundEvent $event): void
    {
        $data = [
            'status' => '401 Authentification nécessaire',
            'message' => 'Aucun token !'
        ];

        $response = new JsonResponse($data, 401);
        $event->setResponse($response);
    }
}
