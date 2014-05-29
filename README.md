# JvsAcl - Simple acl security for ZF2

This module provide a simple acl controller for zf2.
This module can be used with Doctrin ORM.
This is very simple, only implement the `JvsAcl\Entity\UserGroupProviderInterface`.

## Requirements

 * [Zend Framework 2](https://github.com/zendframework/zf2)

## Installation

1. Add this project in your composer.json:

```json
"required": {
    "marcusamatos/jvs-acl": "dev-master"
}
```

2. Enabling ir in your `application.config.php` file.

```php
return array(
    'modules' => array(
        #[...]
        'JvsAcl'
        #[...]
    );
);
```

3. Add `\JvsAcl\Entity\GroupProviderInterface` to your user entity

4. Add in `module.config.php`

```json
return array(
    #[...]
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
            'Application\Controller\Auth' => array(
                'guest' => array(
                    'allow' => array('index', 'forgetPassword')
                )
            ),
            'Admin\Controller\Index' => array(
                'admin'
            ),
        )
    )
    #[...]
);
```






