<?php

namespace App\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class AppSetData
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $entity->setCreatedAt(new \DateTime());
        $entity->setUpdatedAt(new \DateTime());
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $entity->setUpdatedAt(new \DateTime());
    }
}
