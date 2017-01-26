<?php

include_once(dirname(dirname(__FILE__)) . '/CFDBQueryResultIteratorFactory.php');
include_once(dirname(dirname(__FILE__)) . '/ExportToCsvUtf8.php');
include_once(dirname(dirname(__FILE__)) . '/ExportToJson.php');
include_once(dirname(dirname(__FILE__)) . '/BaseTransform.php');

include_once('MockQueryResultIterator.php');
include_once('WP_Mock_Functions.php');
include_once('WPDB_Mock.php');
include_once('SquashOutputUnitTest.php');


$wpdb = null; // mock global

class TransformsTest extends SquashOutputUnitTest {

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
        $str = file_get_contents('TransformsTest.json');
        $data = json_decode($str, true);
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

    public function test_simple() {
        $options = array();
        $exp = new ExportToCsvUtf8();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();
        $this->assertTrue(strlen($text) > 20);
        $this->assertTrue(strpos($text, 'msimpson') > 0);
    }

    public function test_transform() {
        $options = array();
        $options['trans'] = 'name=strtoupper(name)';

        $exp = new ExportToCsvUtf8();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();

        $this->assertTrue(strlen($text) > 20);
        $this->assertTrue(strpos($text, 'msimpson') > 0);
        $this->assertTrue(strpos($text, 'B1') > 0);
        $this->assertTrue(strpos($text, 'P2') > 0);
    }

    public function testLexicalSortClass() {
        $options = array();
        $options['trans'] = 'SortByField(misc)';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();

        $stuff = json_decode($text);
        $idx = 0;
        $this->assertTrue(is_array($stuff));
        $this->assertEquals('X101', $stuff[$idx++]->misc);
        $this->assertEquals('X11', $stuff[$idx++]->misc);
        $this->assertEquals('X8', $stuff[$idx++]->misc);
        $this->assertEquals('x1', $stuff[$idx++]->misc);
        $this->assertEquals('x12', $stuff[$idx++]->misc);
        $this->assertEquals('x123', $stuff[$idx++]->misc);
        $this->assertEquals('x2', $stuff[$idx++]->misc);
        $this->assertEquals('x6', $stuff[$idx++]->misc);
    }

    public function testNaturalSortClass() {
        $options = array();
        $options['trans'] = 'NaturalSortByField(misc)';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();

        $stuff = json_decode($text);
        $this->assertTrue(is_array($stuff));
        $idx = 0;
        $this->assertEquals('X8', $stuff[$idx++]->misc);
        $this->assertEquals('X11', $stuff[$idx++]->misc);
        $this->assertEquals('X101', $stuff[$idx++]->misc);
        $this->assertEquals('x1', $stuff[$idx++]->misc);
        $this->assertEquals('x2', $stuff[$idx++]->misc);
        $this->assertEquals('x6', $stuff[$idx++]->misc);
        $this->assertEquals('x12', $stuff[$idx++]->misc);
        $this->assertEquals('x123', $stuff[$idx++]->misc);
    }

    public function testTransformThenSort() {
        $options = array();
        $options['trans'] = 'misc=strtoupper(misc)&&NaturalSortByField(misc)';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();

        $stuff = json_decode($text);
        $this->assertTrue(is_array($stuff));
        $idx = 0;
        $this->assertEquals('X1', $stuff[$idx++]->misc);
        $this->assertEquals('X2', $stuff[$idx++]->misc);
        $this->assertEquals('X6', $stuff[$idx++]->misc);
        $this->assertEquals('X8', $stuff[$idx++]->misc);
        $this->assertEquals('X11', $stuff[$idx++]->misc);
        $this->assertEquals('X12', $stuff[$idx++]->misc);
        $this->assertEquals('X101', $stuff[$idx++]->misc);
        $this->assertEquals('X123', $stuff[$idx++]->misc);
    }

    public function testNoDuplicateColumnAfterTransformThenSort() {
        $options = array();
        $options['trans'] = 'misc=strtoupper(misc)&&NaturalSortByField(misc)';
        $options['tlimit'] = '1';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();

        // misc should not appear twice
        $this->assertEquals(1, substr_count($text, 'misc'));
    }

    public function testSimpleStat() {
        $options = array();
        $options['trans'] = 'HardCodedData';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();

        $stuff = json_decode($text);
        $this->assertTrue(is_array($stuff));
        $this->assertEquals('Mike', $stuff[0]->first_name);
        $this->assertEquals('Oya', $stuff[1]->first_name);
    }

    public function test_function_on_entry() {
        $options = array();
        $options['trans'] = 'upperall2';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();

        $stuff = json_decode($text);
        $this->assertTrue(is_array($stuff));
        $this->assertEquals('B1', $stuff[0]->name);
        $this->assertEquals('X1', $stuff[0]->misc);
        $this->assertEquals('A2', $stuff[1]->name);
        $this->assertEquals('X11', $stuff[1]->misc);
        $this->assertEquals('A', $stuff[2]->name);
        $this->assertEquals('X101', $stuff[2]->misc);
    }

    public function test_hide_metadata() {
        $options = array();
        $options['trans'] = 'name=strtoupper(name)';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();
        $stuff = json_decode($text);
        $this->assertTrue(is_array($stuff));
        $this->assertEquals('B1', $stuff[0]->name);

        $this->assertFalse(isset($stuff[0]->fields_with_file));
        $this->assertFalse(isset($stuff[0]->submit_time));
        $this->assertFalse(isset($stuff[0]->Submit_Time_Key));
    }

    public function test_hide_metadata_when_sort() {
        $options = array();
        $options['trans'] = 'NaturalSortByField(name)';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();
        $stuff = json_decode($text);
        $this->assertTrue(is_array($stuff));

        $this->assertFalse(isset($stuff[0]->fields_with_file));
        $this->assertFalse(isset($stuff[0]->submit_time));
        $this->assertFalse(isset($stuff[0]->Submit_Time_Key));
    }

    public function test_limit() {
        $options = array();
        $options['trans'] = 'misc=strtoupper(misc)';
        $options['limit'] = '2';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();
        $stuff = json_decode($text);
        $this->assertTrue(is_array($stuff));

        $this->assertEquals(2, count($stuff));
        $this->assertEquals('X1', $stuff[0]->misc);
        $this->assertEquals('X11', $stuff[1]->misc);
    }

    public function test_limit_range() {
        $options = array();
        $options['trans'] = 'misc=strtoupper(misc)';
        $options['limit'] = '3,2'; // 2 rows starting at row 3

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();
        $stuff = json_decode($text);
        $this->assertTrue(is_array($stuff));

        $this->assertEquals(2, count($stuff));
        $this->assertEquals('X2', $stuff[0]->misc);
        $this->assertEquals('X6', $stuff[1]->misc);
    }

    public function test_order_by() {
        $options = array();
        $options['trans'] = 'name=strtoupper(name)';
        $options['orderby'] = 'name';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();
        $stuff = json_decode($text);
        $this->assertTrue(is_array($stuff));

        $this->assertEquals('A', $stuff[0]->name);
        $this->assertEquals('A2', $stuff[1]->name);
    }

    public function test_order_by_desc() {
        $options = array();
        $options['trans'] = 'name=strtoupper(name)';
        $options['orderby'] = 'name DESC';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();
        $stuff = json_decode($text);
        $this->assertTrue(is_array($stuff));

        $this->assertEquals('P2', $stuff[0]->name);
        $this->assertEquals('P1', $stuff[1]->name);
    }

    public function test_order_by_desc_case() {
        $options = array();
        $options['trans'] = 'name=strtoupper(name)';
        $options['orderby'] = 'name desc';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();
        $stuff = json_decode($text);
        $this->assertTrue(is_array($stuff));

        $this->assertEquals('P2', $stuff[0]->name);
        $this->assertEquals('P1', $stuff[1]->name);
    }

    public function test_multiple_order_by_desc() {
        $options = array();
        $options['trans'] = 'name=strtoupper(name)';
        $options['orderby'] = 'Submitted Login DESC,name DESC';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();
        $stuff = json_decode($text);
        $this->assertTrue(is_array($stuff));

        $this->assertEquals('P2', $stuff[0]->name);
        $this->assertEquals('P1', $stuff[1]->name);
    }

    public function test_order_by_different_fields() {
        $options = array();
        $options['trans'] = 'HardCodedData';
        $options['orderby'] = 'first_name DESC';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();
        $stuff = json_decode($text);
        $this->assertTrue(is_array($stuff));

        $this->assertEquals('Oya', $stuff[0]->first_name);
        $this->assertEquals('Mike', $stuff[1]->first_name);
    }

    public function test_headers() {
        $options = array();
        $options['trans'] = 'HardCodedData';
        $options['headers'] = 'first_name=FIRST,last_name=LAST';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();

        $stuff = json_decode($text);
        $this->assertTrue(is_array($stuff));
        $this->assertEquals('Mike', $stuff[0]->FIRST);
        $this->assertEquals('Simpson', $stuff[0]->LAST);
        $this->assertEquals('Oya', $stuff[1]->FIRST);
        $this->assertEquals('Simpson', $stuff[1]->LAST);
    }

    public function test_filter() {
        $options = array();
        $options['trans'] = 'misc=strtoupper(misc)';
        $options['filter'] = 'misc~~/^X1/';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();
        $stuff = json_decode($text);
        $this->assertTrue(is_array($stuff));

        $idx=0;
        $this->assertEquals('X1', $stuff[$idx++]->misc);
        $this->assertEquals('X11', $stuff[$idx++]->misc);
        $this->assertEquals('X101', $stuff[$idx++]->misc);
        $this->assertEquals('X123', $stuff[$idx++]->misc);
        $this->assertEquals('X12', $stuff[$idx++]->misc);
    }

    public function test_search() {
        $options = array();
        $options['trans'] = 'misc=strtoupper(misc)';
        $options['search'] = 'X1';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();
        $stuff = json_decode($text);
        $this->assertTrue(is_array($stuff));

        $idx=0;
        $this->assertEquals('X1', $stuff[$idx++]->misc);
        $this->assertEquals('X11', $stuff[$idx++]->misc);
        $this->assertEquals('X101', $stuff[$idx++]->misc);
        $this->assertEquals('X123', $stuff[$idx++]->misc);
        $this->assertEquals('X12', $stuff[$idx++]->misc);
    }

    public function test_XCount() {
        $options = array();
        $options['trans'] = 'XCount';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();
        $stuff = json_decode($text);
        $this->assertTrue(is_array($stuff));

        $this->assertEquals('X1', $stuff[0]->name);
        $this->assertEquals(5, $stuff[0]->count);

        $this->assertEquals('X2', $stuff[1]->name);
        $this->assertEquals(1, $stuff[1]->count);
    }

    public function test_tfilter() {
        $options = array();
        $options['trans'] = 'XCount';
        $options['tfilter'] = 'misc~~/^X1/';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();
        $stuff = json_decode($text);
        $this->assertTrue(is_array($stuff));

        $this->assertEquals('X1', $stuff[0]->name);
        $this->assertEquals(2, $stuff[0]->count);

        $this->assertEquals('X2', $stuff[1]->name);
        $this->assertEquals(0, $stuff[1]->count);
    }

    public function test_add_field_by_class() {
        $options = array();
        $options['trans'] = 'AddField';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();
        $stuff = json_decode($text);
        $this->assertTrue(is_array($stuff));

        $idx = 0;
        $this->assertEquals($idx, $stuff[$idx]->index); ++$idx;
        $this->assertEquals($idx, $stuff[$idx]->index); ++$idx;
        $this->assertEquals($idx, $stuff[$idx]->index); ++$idx;
        $this->assertEquals($idx, $stuff[$idx]->index); ++$idx;
        $this->assertEquals($idx, $stuff[$idx]->index); ++$idx;
    }

    public function test_add_field_by_assignment() {
        $options = array();
        $options['trans'] = 'newfield=strtoupper(name)';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();
        $stuff = json_decode($text);
        $this->assertTrue(is_array($stuff));

        $idx = 0;
        $this->assertEquals('B1', $stuff[$idx]->newfield); ++$idx;
        $this->assertEquals('A2', $stuff[$idx]->newfield); ++$idx;
        $this->assertEquals('A', $stuff[$idx]->newfield); ++$idx;
        $this->assertEquals('P1', $stuff[$idx]->newfield); ++$idx;
        $this->assertEquals('P2', $stuff[$idx]->newfield); ++$idx;
        $this->assertEquals('J', $stuff[$idx]->newfield); ++$idx;
        $this->assertEquals('D', $stuff[$idx]->newfield); ++$idx;
        $this->assertEquals('M', $stuff[$idx]->newfield); ++$idx;
    }

    public function test_add_field_then_filter() {
        $options = array();
        $options['trans'] = 'AddField';
        $options['filter'] = 'index>1';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();
        $stuff = json_decode($text);
        $this->assertTrue(is_array($stuff));

        $idx = 0;
        $this->assertEquals($idx+2, $stuff[$idx]->index); ++$idx;
        $this->assertEquals($idx+2, $stuff[$idx]->index); ++$idx;
        $this->assertEquals($idx+2, $stuff[$idx]->index); ++$idx;
        $this->assertEquals($idx+2, $stuff[$idx]->index); ++$idx;
        $this->assertEquals($idx+2, $stuff[$idx]->index); ++$idx;
    }

    public function test_show() {
        $options = array();
        $options['trans'] = 'misc=strtoupper(misc)';
        $options['orderby'] = 'misc DESC';
        $options['show'] = 'name,age';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();
        $stuff = json_decode($text);
        $this->assertTrue(is_array($stuff));

        $this->assertFalse(isset($stuff[0]->misc));
        $this->assertFalse(isset($stuff[0]->Submitted));
        $this->assertEquals('d', $stuff[0]->name);
        $this->assertEquals('.99999', $stuff[0]->age);
    }

    public function test_show_submit_time() {
        $options = array();
        $options['trans'] = 'misc=strtoupper(misc)';
        $options['orderby'] = 'misc DESC';
        $options['show'] = 'name,submit_time';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();
        $stuff = json_decode($text);
        $this->assertTrue(is_array($stuff));

        $this->assertFalse(isset($stuff[0]->misc));
        $this->assertFalse(isset($stuff[0]->Submitted));
        $this->assertEquals('d', $stuff[0]->name);
        $this->assertEquals('1401302985.4975', $stuff[0]->submit_time);
    }

    public function test_hide() {
        $options = array();
        $options['trans'] = 'misc=strtoupper(misc)';
        $options['orderby'] = 'misc DESC';
        $options['hide'] = 'misc,Submitted';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();
        $stuff = json_decode($text);
        $this->assertTrue(is_array($stuff));

        $this->assertFalse(isset($stuff[0]->misc));
        $this->assertFalse(isset($stuff[0]->Submitted));
        $this->assertEquals('d', $stuff[0]->name);
        $this->assertEquals('.99999', $stuff[0]->age);
    }

    public function test_str_replace_with_blank() {
        $options = array();
        $options['trans'] = 'misc2=str_replace(x,,misc)';

        $exp = new ExportToJson();
        ob_start();
        $exp->export('Ages', $options);
        $text = ob_get_contents();
        $stuff = json_decode($text);
        $this->assertTrue(is_array($stuff));

        foreach ($stuff as $entry) {
            $this->assertEquals(str_replace('x', '', $entry->misc), $entry->misc2);
        }

    }

    // todo: somehow to test random?


}

class HardCodedData extends BaseTransform {

    public function getTransformedData() {
        return array(
                array('first_name' => 'Mike', 'last_name' => 'Simpson'),
                array('first_name' => 'Oya', 'last_name' => 'Simpson')
        );
    }
}


class XCount extends BaseTransform {

    public function getTransformedData() {
        $x1 = 0;
        $x2 = 0;
        foreach ($this->data as $entry) {
            if (strpos(strtoupper($entry['misc']), 'X1') === 0) {
                ++$x1;
            }
            if (strpos(strtoupper($entry['misc']), 'X2') === 0) {
                ++$x2;
            }
        }
        return array(
            array('name' => 'X1', 'count' => $x1),
            array('name' => 'X2', 'count' => $x2)
        );
    }
}


class AddField extends BaseTransform {

    public function getTransformedData() {
        $idx = 0;
        foreach ($this->data as &$entry) {
            $entry['index'] = $idx++;
        }
        return $this->data;
    }
}


function upperall2($entry) {
    foreach ($entry as $key => $value) {
        $entry[$key] = strtoupper($value);
    }
    return $entry;
}
