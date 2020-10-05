<?php

namespace App\Domain\Common\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;

/**
 * Class JWTInvalidListener
 * @package App\Domain\Common\EventListener
 */
final class JWTInvalidListener
{

    /**
     * @param JWTInvalidEvent $event
     */
    public function onJWTInvalid(JWTInvalidEvent $event): void
    {
        $response = new JWTAuthenticationFailureResponse(
            "Votre token est invalide, merci de vous réidentifier pour en obtenir un nouveau !",
            401
        );

        $event->setResponse($response);
    }
}
