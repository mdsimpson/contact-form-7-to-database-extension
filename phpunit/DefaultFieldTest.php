<?php

include_once(dirname(dirname(__FILE__)) . '/DefaultField.php');
include_once('SquashOutputUnitTest.php');

class DefaultFieldTest extends SquashOutputUnitTest {

    var $data;

    public function setUp() {
        parent::setup();
        date_default_timezone_set('America/New_York');
        $str = file_get_contents('scores.json');
        $this->data = json_decode($str, true);
    }

    public function test_default() {
        $trans = new DefaultField('score', '0', 'greeting', 'hi');
        foreach ($this->data as $entry) {
            $trans->addEntry($entry);
        }
        $data = $trans->getTransformedData();


        print_r($data);

        $idx = 0;
        $this->assertEquals('AAA', $data[$idx]['name']);
        $this->assertEquals(1514, $data[$idx]['score']);
        $this->assertEquals('hi', $data[$idx]['greeting']);

        $idx = 13;
        $this->assertEquals('AAA', $data[$idx]['name']);
        $this->assertEquals(0, $data[$idx]['score']);
        $this->assertEquals('hi', $data[$idx]['greeting']);

        $idx++;
        $this->assertEquals('BBB', $data[$idx]['name']);
        $this->assertEquals(0, $data[$idx]['score']);
        $this->assertEquals('hi', $data[$idx]['greeting']);

        $idx++;
        $this->assertEquals('CCC', $data[$idx]['name']);
        $this->assertEquals(0, $data[$idx]['score']);
        $this->assertEquals('hi', $data[$idx]['greeting']);

    }

}