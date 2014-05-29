<?php

namespace JvsAcl\Form\Element;

use Zend\Form\Element\Select;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Form\FormElementManager;

class UserGroupElement extends Select implements ServiceLocatorAwareInterface {

    protected $serviceLocator;

    public function init()
    {
        $config = $this->getServiceLocator()->get('Config');
        $jvsAclConfig = $config['jvs-acl'];

        $valueOptions = array();

        foreach($jvsAclConfig['groups'] as $var => $value)
        {
            $selectVar = $var;
            $selectValue = $value;
            $visible = false;

            if(is_array($value)) {
                $visible = isset($value['visible']) ? $value['visible'] : false;
                $selectValue = $value['name'] ?: $var;
            }

            if(is_int($var)){
                $selectVar = $selectValue;
            }
            if($visible){
                $valueOptions[$selectVar] = $selectValue;
            }
        }

        $this->setValueOptions($valueOptions);
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        if($serviceLocator instanceof FormElementManager)
            $serviceLocator = $serviceLocator->getServiceLocator();

        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}