<?php

namespace App\Domain\Common\EventSubscriber;

use App\Domain\Common\Exception\ValidationException;
use App\Responder\JsonResponder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

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

    public function onException(ExceptionEvent $event, JsonResponder $responder): void
    {
        $statusCode = 500;
        $message = [
            'message' => $event->getThrowable()->getMessage()
        ];

        switch (get_class($event->getThrowable())) {
            case ValidationException::class:
                $statusCode = 400;
                $message = $event->getThrowable()->getParams();
                break;
        }

        $event->setResponse($responder($message, $statusCode));
    }
}
