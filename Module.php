<?php

namespace JvsAcl;

use JvsAcl\Event\AclEvents;
use Zend\ModuleManager\Feature;
use Zend\Mvc\MvcEvent;

use BjyAuthorize\Provider\Rule\ProviderInterface;
use BjyAuthorize\Service\AuthorizeAwareInterface;


class Module
{


    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attachAggregate(new AclEvents());
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }


}
