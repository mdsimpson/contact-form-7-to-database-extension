<?php

include_once(dirname(dirname(__FILE__)) . '/CFDBErrorLog.php');

class CFDBErrorLogTest extends PHPUnit_Framework_TestCase {

    public function testIsEmailAddress() {
        $dateFormatter = new MockDateFormatter();
        $err = new CFDBErrorLog($dateFormatter);
        $email = 'me@here.com';
        $this->assertTrue($err->isEmailAddress($email), $email);
        $email = 'michael_d_simpson@gmail.com';
        $this->assertTrue($err->isEmailAddress($email), $email);
        $email = 'some-body@xxx.pl';
        $this->assertTrue($err->isEmailAddress($email), $email);
    }

    public function testIsEmailAddress_isPath() {
        $dateFormatter = new MockDateFormatter();
        $err = new CFDBErrorLog($dateFormatter);
        $this->assertFalse($err->isEmailAddress('/path/to/something'), '/path/to/something');
    }

    public function testIsEmailAddress_emptyString() {
        $dateFormatter = new MockDateFormatter();
        $err = new CFDBErrorLog($dateFormatter);
        $this->assertFalse($err->isEmailAddress(''), 'empty string');
    }

    public function testIsEmailAddress_null() {
        $dateFormatter = new MockDateFormatter();
        $err = new CFDBErrorLog($dateFormatter);
        $this->assertFalse($err->isEmailAddress(null), 'null');
    }

    public function testNoDestination() {
        $dateFormatter = new MockDateFormatter();
        $err = new CFDBErrorLog($dateFormatter);
        $this->assertNull($err->getOutputFilePath());
        $this->assertNull($err->getEmailAddress());
    }

    public function testFileDestination() {
        $dateFormatter = new MockDateFormatter();
        $path = '/path/to/file';
        $err = new CFDBErrorLog($dateFormatter, $path);
        $this->assertEquals($path, $err->getOutputFilePath());
        $this->assertNull($err->getEmailAddress());
    }

    public function testEmailDestination() {
        $dateFormatter = new MockDateFormatter();
        $email = 'I_am_here@somwhere-here.com';
        $err = new CFDBErrorLog($dateFormatter, $email);
        $this->assertNull($err->getOutputFilePath());
        $this->assertEquals($email, $err->getEmailAddress());
    }

    public function testWriteToFile() {
        $dateFormatter = new MockDateFormatter();
        $path = tempnam(sys_get_temp_dir(), 'CFDBLogTest-');
        $err = new CFDBErrorLog($dateFormatter, $path);
        $err->log("Test message");
        $this->assertFileExists($path, 'file exists: ' . $path);
        $fileContents = file_get_contents($path);
        $this->assertEquals("CFDB Error (Today): Test message\n", $fileContents);
        unlink($path);
    }

    public function testWriteExceptionToFile() {
        $dateFormatter = new MockDateFormatter();
        $path = tempnam(sys_get_temp_dir(), 'CFDBLogTest-');
        $err = new CFDBErrorLog($dateFormatter, $path);
        try {
            throw new Exception('Test Exception');
        } catch (Exception $ex) {
            $err->logException($ex);
        }
        $this->assertFileExists($path, 'file exists: ' . $path);
        $fileContents = file_get_contents($path);
        $this->assertStringStartsWith('CFDB Error (Today): Test Exception', $fileContents);
        unlink($path);
    }

    // Can only test this by running then checking email
    /*
    public function testEmailException() {
        $dateFormatter = new MockDateFormatter();
        $email = 'michael.d.simpson@gmail.com';
        $err = new CFDBErrorLog($dateFormatter, $email);
        try {
            throw new Exception('Test Exception');
        } catch (Exception $ex) {
            $err->logException($ex);
        }
    }
    */
}

class MockDateFormatter implements CFDBDateFormatter {

    public function formatDate($time) {
        return 'Today';
    }
}