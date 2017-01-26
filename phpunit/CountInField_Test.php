<?php

include_once(dirname(dirname(__FILE__)) . '/CountInField.php');
include_once('SquashOutputUnitTest.php');

class CountInField_Test extends SquashOutputUnitTest {

    var $data;

    public function setUp() {
        parent::setup();
        date_default_timezone_set('America/New_York');
        $str = file_get_contents('votes.json');
        $this->data = json_decode($str, true);
    }

    public function test_count() {
        $trans = new CountInField('Vote', 'count');
        foreach ($this->data as $entry) {
            $trans->addEntry($entry);
        }
        $data = $trans->getTransformedData();
        
        
        print_r($data);

        $this->assertEquals(7, count($data));

        $idx = 0;
        $this->assertEquals('AA', $data[$idx]['Vote']);
        $this->assertEquals(4, $data[$idx]['count']);
        $this->assertEquals(2, count($data[$idx]));

        $idx++;
        $this->assertEquals('FF', $data[$idx]['Vote']);
        $this->assertEquals(1, $data[$idx]['count']);
        $this->assertEquals(2, count($data[$idx]));

        $idx++;
        $this->assertEquals('CC', $data[$idx]['Vote']);
        $this->assertEquals(2, $data[$idx]['count']);
        $this->assertEquals(2, count($data[$idx]));

        $idx++;
        $this->assertEquals('DD', $data[$idx]['Vote']);
        $this->assertEquals(2, $data[$idx]['count']);
        $this->assertEquals(2, count($data[$idx]));

        $idx++;
        $this->assertEquals('EE', $data[$idx]['Vote']);
        $this->assertEquals(1, $data[$idx]['count']);
        $this->assertEquals(2, count($data[$idx]));

        $idx++;
        $this->assertEquals('GG', $data[$idx]['Vote']);
        $this->assertEquals(1, $data[$idx]['count']);
        $this->assertEquals(2, count($data[$idx]));

        $idx++;
        $this->assertEquals('HH', $data[$idx]['Vote']);
        $this->assertEquals(1, $data[$idx]['count']);
        $this->assertEquals(2, count($data[$idx]));
    }

}