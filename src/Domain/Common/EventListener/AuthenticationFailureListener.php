<?php

namespace App\Domain\Common\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;

/**
 * Class AuthenticationFailureListener
 * @package App\Domain\Common\EventListener
 */
final class AuthenticationFailureListener
{

    /**
     * @param AuthenticationFailureEvent $event
     */
    public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event): void
    {
        $data = [
            'status' => '401 Non autorisé',
            'message' => 'Identifiants incorrects, veuillez entrer correctement votre email et votre mot de passe !'
        ];

        /** @phpstan-ignore-next-line */
        $response = new JWTAuthenticationFailureResponse($data);
        $event->setResponse($response);
    }
}
