<?php

/**
 * Unit Test superclass that buffers any misc output and removes it
 */
abstract class SquashOutputUnitTest extends PHPUnit_Framework_TestCase {
    
    var $output = false;

    public function setup() {
        if (!$this->output) {
            ob_start();
        }
    }

    public function tearDown() {
        if (!$this->output) {
            ob_end_clean();
        }
    }

}