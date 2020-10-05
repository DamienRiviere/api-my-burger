<?php

namespace App\Domain\Common\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;

/**
 * Class JWTExpiredListener
 * @package App\Domain\Common\EventListener
 */
final class JWTExpiredListener
{

    /**
     * @param JWTExpiredEvent $event
     */
    public function onJWTExpired(JWTExpiredEvent $event): void
    {
        $response = $event->getResponse();
        $response->setStatusCode(401);
        /** @phpstan-ignore-next-line */
        $response->setMessage("Votre token a expiré, veuillez vous réidentifier !");
    }
}
