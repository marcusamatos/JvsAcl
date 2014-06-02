<?php



return array(
    'jvs-acl' => array(
        'default-denied-controller' => 'JvsAcl\Controller\Acl',
        'default-denied-action' => 'denied',
        'default-denied-authenticate-controller' => null,
        'default-denied-authenticate-action' => null,
        'groups' => array(
            'guest',
            'member' => array('name' => 'Member', 'parent' => 'guest', 'visible' => true),
            'admin' => array('name' => 'Administrator', 'parent' => 'member', 'visible' => true),
            'developer' => array('name' => 'Developer', 'parent' => 'admin')
        ),
        'controllers' => array(
            'Application\Controller\Index' => array(
                'guest' => 'allow'
            ),
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'JvsAcl\Controller\Acl' => 'JvsAcl\Controller\AclController'
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    )
);