<?php



return array(
    'jvs-acl' => array(
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