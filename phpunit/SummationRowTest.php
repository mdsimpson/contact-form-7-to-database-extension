<?php

include_once(dirname(dirname(__FILE__)) . '/SummationRow.php');

class SummationRowTest extends PHPUnit_Framework_TestCase {

    public function test_no_data_no_fields() {
        $sum = new SummationRow();
        $results = $sum->getTransformedData();
        $this->assertTrue(is_array($results));
        $this->assertEmpty($results);
    }


    public function test_no_data() {
        $sum = new SummationRow('alpha', 'beta');
        $results = $sum->getTransformedData();
        $this->assertTrue(is_array($results));
        $this->assertEmpty($results);
    }


    public function test_no_fields_to_sum() {
        $sum = new SummationRow('alpha', 'beta');
        $row = array('gamma' => '1', 'omega' => '2');
        $sum->addEntry($row);
        $results = $sum->getTransformedData();
        $this->assertTrue(is_array($results));
        $this->assertEquals(2, count($results));
        $this->assertEquals(2, count($results[0]));
        $this->assertEquals(2, count($results[1]));
        $this->assertEquals('', $results[1]['alpha']);
        $this->assertEquals('', $results[1]['beta']);
        $this->assertTrue(isset($results[1]['gamma']));
        $this->assertTrue(isset($results[1]['omega']));
        $this->assertFalse(isset($results[1]['alpha']));
    }

    public function test_one_row_to_sum() {
        $sum = new SummationRow('alpha', 'beta');
        $row = array('alpha' => '1', 'beta' => '2');
        $sum->addEntry($row);
        $results = $sum->getTransformedData();
        $this->assertTrue(is_array($results));
        $this->assertEquals(2, count($results));
        $this->assertEquals(2, count($results[0]));
        $this->assertEquals(2, count($results[1]));
        $this->assertEquals(1, $results[1]['alpha']);
        $this->assertEquals(2, $results[1]['beta']);
    }

    public function test_several_rows_to_sum() {
        $sum = new SummationRow('alpha', 'beta');
        $row = array('alpha' => '1', 'beta' => '20');
        $sum->addEntry($row);
        $row = array('alpha' => '2', 'beta' => '30');
        $sum->addEntry($row);
        $row = array('alpha' => '3', 'beta' => '60');
        $sum->addEntry($row);
        $results = $sum->getTransformedData();
        $this->assertTrue(is_array($results));
        $this->assertEquals(4, count($results));
        $this->assertEquals(2, count($results[0]));
        $this->assertEquals(2, count($results[3]));
        $this->assertEquals(6, $results[3]['alpha']);
        $this->assertEquals(110, $results[3]['beta']);
    }

    public function test_rows_with_string_in_the_mix_to_sum() {
        $sum = new SummationRow('alpha', 'beta');
        $row = array('alpha' => '5', 'beta' => '20');
        $sum->addEntry($row);
        $row = array('alpha' => 'timber', 'beta' => '30');
        $sum->addEntry($row);
        $row = array('alpha' => '7', 'beta' => '60');
        $sum->addEntry($row);
        $results = $sum->getTransformedData();
        $this->assertTrue(is_array($results));
        $this->assertEquals(4, count($results));
        $this->assertEquals(2, count($results[0]));
        $this->assertEquals(2, count($results[3]));
        $this->assertEquals(12, $results[3]['alpha']);
        $this->assertEquals(110, $results[3]['beta']);
    }

    public function test_several_rows_to_sum_with_hardcoded() {
        $sum = new SummationRow('alpha', 'beta', 'Name:Total:');
        $row = array('alpha' => '1', 'beta' => '20', 'Name' => 'Mike1');
        $sum->addEntry($row);
        $row = array('alpha' => '2', 'beta' => '30', 'Name' => 'Mike2');
        $sum->addEntry($row);
        $row = array('alpha' => '3', 'beta' => '60', 'Name' => 'Mike3');
        $sum->addEntry($row);
        $results = $sum->getTransformedData();
        $this->assertTrue(is_array($results));
        $this->assertEquals(4, count($results));
        $this->assertEquals(3, count($results[0]));
        $this->assertEquals(3, count($results[3]));
        $this->assertEquals(6, $results[3]['alpha']);
        $this->assertEquals(110, $results[3]['beta']);
        $this->assertEquals('Total:', $results[3]['Name']);
    }

    public function test_several_rows_to_sum_with_hardcoded_and_empty() {
        $sum = new SummationRow('alpha', 'beta', 'Name:Total:');
        $row = array('alpha' => '1', 'beta' => '20', 'gamma' => 7, 'Name' => 'Mike1');
        $sum->addEntry($row);
        $row = array('alpha' => '2', 'beta' => '30', 'gamma' => 7, 'Name' => 'Mike2');
        $sum->addEntry($row);
        $row = array('alpha' => '3', 'beta' => '60', 'gamma' => 7,  'Name' => 'Mike3');
        $sum->addEntry($row);
        $results = $sum->getTransformedData();
        $this->assertTrue(is_array($results));
        $this->assertEquals(4, count($results));
        $this->assertEquals(4, count($results[0]));
        $this->assertEquals(4, count($results[3]));
        $this->assertEquals(6, $results[3]['alpha']);
        $this->assertEquals(110, $results[3]['beta']);
        $this->assertEquals('Total:', $results[3]['Name']);
        $this->assertEquals('', $results[3]['gamma']);
    }


}