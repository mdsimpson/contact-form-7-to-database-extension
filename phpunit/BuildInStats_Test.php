<?php

include_once(dirname(dirname(__FILE__)) . '/CountField.php');
include_once(dirname(dirname(__FILE__)) . '/SumField.php');
include_once(dirname(dirname(__FILE__)) . '/MaxField.php');
include_once(dirname(dirname(__FILE__)) . '/MinField.php');
include_once(dirname(dirname(__FILE__)) . '/AverageField.php');
include_once('SquashOutputUnitTest.php');

class BuildInStats_Test extends SquashOutputUnitTest {

    var $data;

    public function setUp() {
        parent::setup();
        date_default_timezone_set('America/New_York');
        $str = file_get_contents('scores.json');
        $this->data = json_decode($str, true);
    }

    public function test_count() {
        $trans = new CountField('score');
        foreach ($this->data as $entry) {
            $trans->addEntry($entry);
        }
        $data = $trans->getTransformedData();
        print_r($data);

        $this->assertEquals(1, count($data));
        $this->assertEquals(14, $data[0]['score']);
        $this->assertEquals(1, count($data[0]));
    }

    public function test_count_groupby() {
        $trans = new CountField('score', 'name');
        foreach ($this->data as $entry) {
            $trans->addEntry($entry);
        }
        $data = $trans->getTransformedData();
        print_r($data);

        $this->assertEquals(4, count($data));

        $idx = 0;
        $this->assertEquals('AAA', $data[$idx]['name']);
        $this->assertEquals(4, $data[$idx]['score']);
        $this->assertEquals(2, count($data[$idx]));

        $idx++;
        $this->assertEquals('BBB', $data[$idx]['name']);
        $this->assertEquals(4, $data[$idx]['score']);
        $this->assertEquals(2, count($data[$idx]));

        $idx++;
        $this->assertEquals('CCC', $data[$idx]['name']);
        $this->assertEquals(4, $data[$idx]['score']);
        $this->assertEquals(2, count($data[$idx]));

        $idx++;
        $this->assertEquals('DDD', $data[$idx]['name']);
        $this->assertEquals(2, $data[$idx]['score']);
        $this->assertEquals(2, count($data[$idx]));
    }


    public function test_min() {
        $trans = new MinField('score');
        foreach ($this->data as $entry) {
            $trans->addEntry($entry);
        }
        $data = $trans->getTransformedData();
        print_r($data);

        $this->assertEquals(1, count($data));
        $this->assertEquals(-1, $data[0]['score']);
        $this->assertEquals(1, count($data[0]));
    }

    public function test_min_groupby() {
        $trans = new MinField('score', 'name');
        foreach ($this->data as $entry) {
            $trans->addEntry($entry);
        }
        $data = $trans->getTransformedData();
        print_r($data);

        $this->assertEquals(4, count($data));

        $idx = 0;
        $this->assertEquals('AAA', $data[$idx]['name']);
        $this->assertEquals(20, $data[$idx]['score']);
        $this->assertEquals(2, count($data[$idx]));

        $idx++;
        $this->assertEquals('BBB', $data[$idx]['name']);
        $this->assertEquals(9, $data[$idx]['score']);
        $this->assertEquals(2, count($data[$idx]));

        $idx++;
        $this->assertEquals('CCC', $data[$idx]['name']);
        $this->assertEquals(-1, $data[$idx]['score']);
        $this->assertEquals(2, count($data[$idx]));

        $idx++;
        $this->assertEquals('DDD', $data[$idx]['name']);
        $this->assertEquals(900, $data[$idx]['score']);
        $this->assertEquals(2, count($data[$idx]));
    }

    public function test_max() {
        $trans = new MaxField('score');
        foreach ($this->data as $entry) {
            $trans->addEntry($entry);
        }
        $data = $trans->getTransformedData();
        print_r($data);

        $this->assertEquals(1, count($data));
        $this->assertEquals(20000, $data[0]['score']);
        $this->assertEquals(1, count($data[0]));
    }

    public function test_max_groupby() {
        $trans = new MaxField('score', 'name');
        foreach ($this->data as $entry) {
            $trans->addEntry($entry);
        }
        $data = $trans->getTransformedData();
        print_r($data);

        $this->assertEquals(4, count($data));

        $idx = 0;
        $this->assertEquals('AAA', $data[$idx]['name']);
        $this->assertEquals(1514, $data[$idx]['score']);
        $this->assertEquals(2, count($data[$idx]));

        $idx++;
        $this->assertEquals('BBB', $data[$idx]['name']);
        $this->assertEquals(1500, $data[$idx]['score']);
        $this->assertEquals(2, count($data[$idx]));

        $idx++;
        $this->assertEquals('CCC', $data[$idx]['name']);
        $this->assertEquals(20000, $data[$idx]['score']);
        $this->assertEquals(2, count($data[$idx]));

        $idx++;
        $this->assertEquals('DDD', $data[$idx]['name']);
        $this->assertEquals(900, $data[$idx]['score']);
        $this->assertEquals(2, count($data[$idx]));
    }

    public function test_sum() {
        $trans = new SumField('score');
        foreach ($this->data as $entry) {
            $trans->addEntry($entry);
        }
        $data = $trans->getTransformedData();
        print_r($data);

        $this->assertEquals(1, count($data));
        $this->assertEquals(24704, $data[0]['score']);
        $this->assertEquals(1, count($data[0]));

    }

    public function test_sum_groupby() {
        $trans = new SumField('score', 'name');
        foreach ($this->data as $entry) {
            $trans->addEntry($entry);
        }
        $data = $trans->getTransformedData();
        print_r($data);
        $this->assertEquals(4, count($data));

        $idx = 0;
        $this->assertEquals('AAA', $data[$idx]['name']);
        $this->assertEquals(1764, $data[$idx]['score']);
        $this->assertEquals(2, count($data[$idx]));

        $idx++;
        $this->assertEquals('BBB', $data[$idx]['name']);
        $this->assertEquals(2021, $data[$idx]['score']);
        $this->assertEquals(2, count($data[$idx]));

        $idx++;
        $this->assertEquals('CCC', $data[$idx]['name']);
        $this->assertEquals(20019, $data[$idx]['score']);
        $this->assertEquals(2, count($data[$idx]));

        $idx++;
        $this->assertEquals('DDD', $data[$idx]['name']);
        $this->assertEquals(900, $data[$idx]['score']);
        $this->assertEquals(2, count($data[$idx]));
    }

    public function test_average() {
        $trans = new AverageField('score');
        foreach ($this->data as $entry) {
            $trans->addEntry($entry);
        }
        $data = $trans->getTransformedData();
        print_r($data);

        $this->assertEquals(1, count($data));
        $this->assertEquals(1900.3076923077, $data[0]['score']);
        $this->assertEquals(1, count($data[0]));
    }

    public function test_average_groupby() {
        $trans = new AverageField('score', 'name');
        foreach ($this->data as $entry) {
            $trans->addEntry($entry);
        }
        $data = $trans->getTransformedData();
        print_r($data);

        $this->assertEquals(4, count($data));

        $idx = 0;
        $this->assertEquals('AAA', $data[$idx]['name']);
        $this->assertEquals(441, $data[$idx]['score']);
        $this->assertEquals(2, count($data[$idx]));

        $idx++;
        $this->assertEquals('BBB', $data[$idx]['name']);
        $this->assertEquals(505.25, $data[$idx]['score']);
        $this->assertEquals(2, count($data[$idx]));

        $idx++;
        $this->assertEquals('CCC', $data[$idx]['name']);
        $this->assertEquals(5004.75, $data[$idx]['score']);
        $this->assertEquals(2, count($data[$idx]));

        $idx++;
        $this->assertEquals('DDD', $data[$idx]['name']);
        $this->assertEquals(900, $data[$idx]['score']);
        $this->assertEquals(2, count($data[$idx]));
    }

}