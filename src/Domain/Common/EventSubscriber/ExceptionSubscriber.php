<?php

namespace App\Domain\Common\EventSubscriber;

use App\Responder\JsonResponder;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpKernel\KernelEvents;
use App\Domain\Common\Exception\ValidationException;
use App\Domain\Common\Exception\PageNotFoundException;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use App\Domain\Common\Exception\AuthorizationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ExceptionSubscriber
 * @package App\Domain\Common\EventSubscriber
 */
class ExceptionSubscriber implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onException'
        ];
    }

    public function onException(ExceptionEvent $event): void
    {
        $statusCode = 500;
        $message = [
            'message' => $event->getThrowable()->getMessage()
        ];

        switch (get_class($event->getThrowable())) {
            case PageNotFoundException::class:
            case EntityNotFoundException::class:
                $statusCode = 404;
                break;
            case AuthorizationException::class:
                $statusCode = 403;
                break;
            case ValidationException::class:
                $statusCode = 400;
                $message = $event->getThrowable()->getParams();
                break;
        }

        $response = new JsonResponder();

        $event->setResponse($response($message, $statusCode));
    }
}
