<?php

include_once(dirname(dirname(__FILE__)) . '/CFDBQueryResultIteratorFactory.php');
include_once(dirname(dirname(__FILE__)) . '/ExportToJson.php');
include_once(dirname(dirname(__FILE__)) . '/SortByDateField.php');

include_once('MockQueryResultIterator.php');
include_once('WP_Mock_Functions.php');
include_once('WPDB_Mock.php');
include_once('SquashOutputUnitTest.php');


$wpdb = null; // mock global

class SortByDateField_TransformTest extends SquashOutputUnitTest {


    public function tearDown() {
        CFDBQueryResultIteratorFactory::getInstance()->clearMock();
        $wpdb = null;
        try {
            ob_flush();
            ob_end_clean();
        } catch (Exception $e) {
        }
        parent::tearDown();
    }

    public function setUp() {
        parent::setup();
        date_default_timezone_set('America/New_York');
        $dataJson = '[
    {
        "Submitted": "1401303038.5193",
        "name": "C",
        "date": "1/2/2010",
        "Submitted Login": "msimpson",
        "Submitted From": "192.168.1.1",
        "fields_with_file" : ""
    },
    {
        "Submitted": "1401303039.5193",
        "name": "A",
        "date": "5/25/1997",
        "Submitted Login": "msimpson",
        "Submitted From": "192.168.1.1",
        "fields_with_file" : ""
    },
    {
        "Submitted": "1401303040.5193",
        "name": "B",
        "date": "5/25/2003",
        "Submitted Login": "msimpson",
        "Submitted From": "192.168.1.1",
        "fields_with_file" : ""
    }
    ]';
        $data = json_decode($dataJson, true);
        $mock = new MockQueryResultIterator($data);
        CFDBQueryResultIteratorFactory::getInstance()->setQueryResultsIteratorMock($mock);

        global $wpdb;
        $wpdb = new WPDB_Mock;

        $fields = array();
        foreach (array_keys($data[0]) as $key) {
            $fields[] = (object)array('field_name' => $key);
        }
        $wpdb->getResultReturnVal = $fields;
    }

    public function test_SortByDateField() {
        $options = array();
        $options['trans'] = 'SortByDateField(date)';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Form', $options);
        $text = ob_get_contents();

        $stuff = json_decode($text);
        $idx = 0;
        $this->assertTrue(is_array($stuff));
        $this->assertEquals('A', $stuff[$idx++]->name);
        $this->assertEquals('B', $stuff[$idx++]->name);
        $this->assertEquals('C', $stuff[$idx++]->name);
    }

    public function test_SortByDateField_desc() {
        $options = array();
        $options['trans'] = 'SortByDateField(date,DESC)';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Form', $options);
        $text = ob_get_contents();

        $stuff = json_decode($text);
        $idx = 0;
        $this->assertTrue(is_array($stuff));
        $this->assertEquals('C', $stuff[$idx++]->name);
        $this->assertEquals('B', $stuff[$idx++]->name);
        $this->assertEquals('A', $stuff[$idx++]->name);
    }

    public function test_SortByDateField_desc_format() {
        $options = array();
        $options['trans'] = 'SortByDateField(date,DESC,m/d/Y)';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Form', $options);
        $text = ob_get_contents();

        $stuff = json_decode($text);
        $idx = 0;
        $this->assertTrue(is_array($stuff));
        $this->assertEquals('C', $stuff[$idx++]->name);
        $this->assertEquals('B', $stuff[$idx++]->name);
        $this->assertEquals('A', $stuff[$idx++]->name);
    }

}