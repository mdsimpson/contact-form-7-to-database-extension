<?php

include_once(dirname(dirname(__FILE__)) . '/SplitField.php');
include_once('SquashOutputUnitTest.php');

class SplitFieldDefaultDelimiterTest extends SquashOutputUnitTest {

    var $data;

    public function setUp() {
        parent::setup();
        date_default_timezone_set('America/New_York');
        $str = file_get_contents('compoundfieldsComma.json');
        $this->data = json_decode($str, true);
    }

    public function test_split() {
        $trans = new SplitField('Choice'); // pass only first parameter
        foreach ($this->data as $entry) {
            $trans->addEntry($entry);
        }
        $data = $trans->getTransformedData();

        print_r($data);

        $idx = 0;
        $this->assertEquals('AAA', $data[$idx]['Choice-1']);
        $this->assertEquals('BBB', $data[$idx]['Choice-2']);
        $this->assertEquals('CCC', $data[$idx]['Choice-3']);
        $this->assertFalse(isset($data[$idx]['Choice-4']));

        $idx++;
        $this->assertEquals('FFF', $data[$idx]['Choice-1']);
        $this->assertEquals('GGG', $data[$idx]['Choice-2']);
        $this->assertFalse(isset($data[$idx]['Choice-3']));
        $this->assertFalse(isset($data[$idx]['Choice-4']));

        $idx++;
        $this->assertEquals('HHH', $data[$idx]['Choice-1']);
        $this->assertEquals('III', $data[$idx]['Choice-2']);
        $this->assertEquals('JJJ', $data[$idx]['Choice-3']);
        $this->assertEquals('KKK', $data[$idx]['Choice-4']);

        $idx++;
        $this->assertEquals('LLL', $data[$idx]['Choice-1']);
        $this->assertFalse(isset($data[$idx]['Choice-2']));
        $this->assertFalse(isset($data[$idx]['Choice-3']));
        $this->assertFalse(isset($data[$idx]['Choice-4']));

    }
}