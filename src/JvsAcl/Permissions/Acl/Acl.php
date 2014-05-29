<?php

namespace JvsAcl\Permissions\Acl;

use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

class Acl extends ZendAcl {

    protected function addPrivileges($functionName, $role, $resources, $privileges)
    {

        if($functionName != 'deny' && $functionName != 'allow')
            throw new \Exception('Inválid command name. Please call \'deny\' or \'allow\'');

        $this->$functionName($role, $resources, $privileges);
    }

    public function __construct($config, $controllerName)
    {
        if(!is_array($config))
            throw new \InvalidArgumentException('Config must be array!');

        if(!isset($config['groups']) || !is_array($config['groups']))
            throw new \InvalidArgumentException('Config groups not found!');

        if(isset($config['controllers']) && !is_array($config['controllers']))
            throw new \InvalidArgumentException('Config controllers must be array!');

        // ADD ROLES
        foreach($config['groups'] as $var => $value) {
            $role = $value;
            $parent = null;

            if(is_array($value)){
                $role = $var;
                $parent = $value['parent'] ?: null;
            }

            $this->addRole(new Role($role), $parent);
        }

        //create resources
        $this->addResource(new Resource($controllerName));

        $this->deny();

        if(!isset($config['controllers'][$controllerName]))
            throw new \InvalidArgumentException('Config controllers not found controller ' . $controllerName);

        foreach($config['controllers'][$controllerName] as $var => $value) {


            $role = $value;
            $function = 'allow';
            $privileges = array();

            if(!is_int($var)){
                $role = $var;
                $function = $value;
                if(is_array($value)) {
                    foreach($value as $var2 => $value2){
                        $function = $value2;

                        if(!is_int($var2)) {
                            $function = $var2;
                            $privileges = $value2;
                        }
                        $this->addPrivileges($function, $role, $controllerName, $privileges);
                        //$this->$function($role, $controllerName, $privileges);
                    }
                    continue;
                }
            }
            $this->addPrivileges($function, $role, $controllerName, $privileges);
            //$this->$function($role, $controllerName, $privileges);
        }

    }

} 