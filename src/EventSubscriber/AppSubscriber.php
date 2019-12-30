<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class AppSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setUpdateAtValue', 63],
        ];
    }

    public function setUpdateAtValue(ViewEvent $event)
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (Request::METHOD_POST === $method || Request::METHOD_PATCH === $method || Request::METHOD_PUT === $method) {
            $user->setUpdatedAt(new \DateTime());
        }
    }
}
