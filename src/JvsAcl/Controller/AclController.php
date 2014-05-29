<?php

namespace JvsAcl\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AclController extends AbstractActionController {

    public function deniedAction()
    {
        return new ViewModel();
    }

} 