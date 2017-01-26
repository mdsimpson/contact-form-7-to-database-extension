<?php

include_once(dirname(dirname(__FILE__)) . '/SortByDateField.php');

class SortByDateFieldTest extends PHPUnit_Framework_TestCase {

    public function setup() {
        date_default_timezone_set('America/New_York');
    }

    public function test_no_format_strtotime() {
        $sort = new SortByDateField('date');

        $before = array('date' => 'yesterday');
        $after = array('date' => 'today');

        $this->assertEquals(-1, $sort->sort($before, $after));
        $this->assertEquals(1, $sort->sort($after, $before));
        $this->assertEquals(0, $sort->sort($after, $after));
    }

    public function test_no_format_strtotime2() {
        $sort = new SortByDateField('date');

        $before = array('date' => '1/1/2010');
        $after = array('date' => '1/1/2014');

        $this->assertEquals(-1, $sort->sort($before, $after));
        $this->assertEquals(1, $sort->sort($after, $before));
        $this->assertEquals(0, $sort->sort($after, $after));
    }

    public function test_no_format_strtotime2_asc() {
        $sort = new SortByDateField('date', 'ASC');

        $before = array('date' => '1/1/2010');
        $after = array('date' => '1/1/2014');

        $this->assertEquals(-1, $sort->sort($before, $after));
        $this->assertEquals(1, $sort->sort($after, $before));
        $this->assertEquals(0, $sort->sort($after, $after));
    }

    public function test_no_format_strtotime2_desc() {
        $sort = new SortByDateField('date', 'DESC');

        $before = array('date' => '1/1/2010');
        $after = array('date' => '1/1/2014');

        $this->assertEquals(1, $sort->sort($before, $after));
        $this->assertEquals(-1, $sort->sort($after, $before));
        $this->assertEquals(0, $sort->sort($after, $after));
    }

    public function test_format() {
        $sort = new SortByDateField('date', 'ASC', 'j.n.Y H:iP');

        $before = array('date' => '6.1.2009 13:00+01:00');
        $after = array('date' => '6.2.2009 13:00+01:00');

        $this->assertEquals(-1, $sort->sort($before, $after));
        $this->assertEquals(1, $sort->sort($after, $before));
        $this->assertEquals(0, $sort->sort($after, $after));
    }

    // bad data tests
    public function test_bad_data1() {
        $sort = new SortByDateField('date');

        $before = array('date' => 'crap');
        $after = array('date' => 'today');

        $this->assertEquals(-1, $sort->sort($before, $after));
        $this->assertEquals(1, $sort->sort($after, $before));
        $this->assertEquals(0, $sort->sort($after, $after));
    }

    public function test_bad_data2() {
        $sort = new SortByDateField('date');

        $before = array('date' => 'yesterday');
        $after = array('date' => 'crap');

        $this->assertEquals(1, $sort->sort($before, $after));
        $this->assertEquals(-1, $sort->sort($after, $before));
        $this->assertEquals(0, $sort->sort($after, $after));
    }

    public function test_bad_data3() {
        $sort = new SortByDateField('date');

        $before = array('date' => 'crap');
        $after = array('date' => 'more crap');

        $this->assertEquals(0, $sort->sort($before, $after));
        $this->assertEquals(0, $sort->sort($after, $before));
        $this->assertEquals(0, $sort->sort($after, $after));
    }


}