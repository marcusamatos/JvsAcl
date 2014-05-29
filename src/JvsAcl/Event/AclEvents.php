<?php

namespace JvsAcl\Event;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\SharedEventManager;
use Zend\Mvc\MvcEvent;
use JvsAcl\Permissions\Acl\Acl;

class AclEvents implements ListenerAggregateInterface {

    protected $listeners = array();

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        /** @var SharedEventManager $shared */
        //$shared = $events->getSharedManager();

        //$this->listeners[] = $shared->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', array($this, 'verify'), 9999);
        $this->listeners[] = $events->attach(array(MvcEvent::EVENT_DISPATCH, MvcEvent::EVENT_DISPATCH_ERROR), array($this, 'verify'), 1000);
        //$this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'verify3'), 1000);
    }

    /**
     * Detach all previously attached listeners
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    public function verify(MvcEvent $e)
    {
        /** @var \Zend\Mvc\Application  $application */
        $application = $e->getApplication();
        $config = $application->getConfig();

        $jvsAclConfig = $config['jvs-acl'] ?: array();

        if($routeMatch = $e->getRouteMatch()) {
            $controllerName = $routeMatch->getParam('controller', '');
            $actionName = $routeMatch->getParam('action', '');

            if(isset($jvsAclConfig['controllers'][$controllerName])){

                if(!$application->getServiceManager()->has('Zend\Authentication\AuthenticationService'))
                    throw new \Exception('No Zend\Authentication\AuthenticationService found in services!');

                /** @var \Zend\Authentication\AuthenticationService $authService */
                $authService = $application->getServiceManager()->get('Zend\Authentication\AuthenticationService');

                $groupName = $authService->hasIdentity() ? $authService->getIdentity()->getUserGroup() : 'guest' ;


                $acl = new Acl($jvsAclConfig, $controllerName);

                if(!$acl->isAllowed($groupName, $controllerName, $actionName)){
                    $routeMatch->setParam('controller', 'JvsAcl\Controller\Acl');
                    $routeMatch->setParam('action', 'denied');
                }


            }
        }

    }

}