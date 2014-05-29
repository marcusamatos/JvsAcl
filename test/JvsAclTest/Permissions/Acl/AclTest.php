<?php

namespace JvsAclTest\Permissions\Acl;

use JvsAcl\Permissions\Acl\Acl;
use PHPUnit_Framework_TestCase;

class AclTest extends PHPUnit_Framework_TestCase {

    /*
            return array(
                'jvs-acl' => array(
                    'groups' => array(
                        'guest',
                        'member' => array('name' => 'Member', 'parent' => 'guest'),
                        'admin' => array('name' => 'Administrator', 'parent' => 'member'),
                        'developer' => array('name' => 'Developer', 'parent' => 'admin')
                    ),
                    'controllers' => array(
                        'Application\Controller\Index' => array(
                            'guest' => 'allow'
                        ),
                        'JvsUsuario\Controller\Auth' => array(
                            'guest' => array(
                                'allow' => array('index', 'forgetPassword')
                            ),
                            'member' => array(
                                'deny'
                            )
                        ),
                        'Admin\Controller\Index' => array(
                            'admin' => 'allow'
                        ),
                    )
                )
            );
             */

    public function testClassExists()
    {
        $this->assertTrue(class_exists('JvsAcl\Permissions\Acl\Acl'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testErroIfConfigIsnotArray()
    {
        $acl = new Acl('string', 'Application\Controller\Index');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testErrorIfNoGroups()
    {
        $acl = new Acl(array(), 'Application\Controller\Index');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testErrorIfGroupIsNotArray()
    {
        $acl = new Acl(array('groups' => 'sss'), 'Application\Controller\Index');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testErrorIfControllersIsNotArray()
    {
        $config = $this->getConfig();
        $config['controllers'] = '';


        $acl = new Acl($config, 'Application\Controller\Index');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testErroIfControllerNotArray()
    {
        $config = $this->getConfig();
        $config['controllers'] = array(
            'Application\Controller\Index'
        );

        $acl = new Acl($config, 'Application\Controller\Index');

        $this->assertFalse($acl->isAllowed('guest'));

    }

    public function testDenyAllControllerInConfigControllers()
    {
        $config = $this->getConfig();
        $config['controllers'] = array(
            'Application\Controller\Index' => array()
        );

        $acl = new Acl($config, 'Application\Controller\Index');

        $this->assertFalse($acl->isAllowed('guest'));

    }

    public function testSimpleAllow()
    {
        $config = $this->getConfig();
        unset($config['controllers']['Application\Controller\Index']['member']);
        unset($config['controllers']['Application\Controller\Index']['admin']);
        unset($config['controllers']['Application\Controller\Index']['developer']);

        $acl = new Acl($config, 'Application\Controller\Index');

        $this->assertTrue($acl->isAllowed('oneuser', 'Application\Controller\Index'));
    }

    public function testSimpleDeny()
    {
        $config = $this->getConfig();
        unset($config['controllers']['Application\Controller\Index']['member']);
        unset($config['controllers']['Application\Controller\Index']['admin']);
        unset($config['controllers']['Application\Controller\Index']['developer']);

        $acl = new Acl($config, 'Application\Controller\Index');

        $this->assertFalse($acl->isAllowed('guest', 'Application\Controller\Index'));
    }

    public function testFunctionArray()
    {
        $config = $this->getConfig();
        //unset($config['controllers']['Application\Controller\Index']['member']);
        //unset($config['controllers']['Application\Controller\Index']['admin']);
        unset($config['controllers']['Application\Controller\Index']['developer']);

        $acl = new Acl($config, 'Application\Controller\Index');

        $this->assertFalse($acl->isAllowed('admin', 'Application\Controller\Index'));
    }

    public function testFunctionArrayTrue()
    {
        $config = $this->getConfig();
        //unset($config['controllers']['Application\Controller\Index']['member']);
        //unset($config['controllers']['Application\Controller\Index']['admin']);
        unset($config['controllers']['Application\Controller\Index']['developer']);

        $acl = new Acl($config, 'Application\Controller\Index');

        $this->assertTrue($acl->isAllowed('admin', 'Application\Controller\Index', 'index'));
    }

    public function testPrivileges()
    {
        $config = $this->getConfig();

        $acl = new Acl($config, 'Application\Controller\Index');

        $this->assertTrue($acl->isAllowed('developer', 'Application\Controller\Index', 'index'));
        $this->assertTrue($acl->isAllowed('developer', 'Application\Controller\Index', 'index2'));
        $this->assertFalse($acl->isAllowed('developer', 'Application\Controller\Index', 'index3'));
    }

    public function getConfig()
    {
        return array(
            'groups' => array(
                'oneuser',
                'guest',
                'member' => array('name' => 'Member', 'parent' => 'guest'),
                'admin' => array('name' => 'Administrator', 'parent' => 'member'),
                'developer' => array('name' => 'Developer', 'parent' => 'admin')
            ),
            'controllers' => array(
                'Application\Controller\Index' => array(
                    'oneuser',
                    'guest' => 'deny',
                    'member' => 'allow',
                    'admin' => array(
                        'deny',
                        'allow' => 'index'
                    ),
                    'developer' => array(
                        'allow' => array('index', 'index2')
                    )
                )
            )
        );
    }
} 