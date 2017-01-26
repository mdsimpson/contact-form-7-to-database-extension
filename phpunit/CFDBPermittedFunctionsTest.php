<?php

include_once(dirname(dirname(__FILE__)) . '/CFDBPermittedFunctions.php');

class CFDBPermittedFunctionsTest extends PHPUnit_Framework_TestCase {

    public function tearDown() {
        CFDBPermittedFunctions::getInstance()->init();
    }

    public function testSet() {
        $p = CFDBPermittedFunctions::getInstance();
        $this->assertFalse($p->isFunctionPermitted('blahblah'));

        $p->setPermitAllFunctions(false);
        $this->assertFalse($p->isFunctionPermitted('blahblah'));

        $p->setPermitAllFunctions(true);
        $this->assertTrue($p->isFunctionPermitted('blahblah'));

        $p->setPermitAllFunctions(false);
        $this->assertFalse($p->isFunctionPermitted('blahblah'));
    }

    public function testAddFunction() {
        $p = CFDBPermittedFunctions::getInstance();
        $this->assertFalse($p->isFunctionPermitted('blahblah'));

        $p->addPermittedFunction("blahblah");
        $this->assertTrue($p->isFunctionPermitted('blahblah'));
    }

    public function testSingleton() {
        $this->assertFalse(
                CFDBPermittedFunctions::getInstance()->isFunctionPermitted('blahblah'));
        CFDBPermittedFunctions::getInstance()->addPermittedFunction('blahblah');
        $this->assertTrue(
                CFDBPermittedFunctions::getInstance()->isFunctionPermitted('blahblah'));

    }

    public function testRegisterFunction() {
        $this->assertFalse(
                CFDBPermittedFunctions::getInstance()->isFunctionPermitted('blahblah'));
        cfdb_register_function('blahblah');
        $this->assertTrue(
                CFDBPermittedFunctions::getInstance()->isFunctionPermitted('blahblah'));
    }

} 