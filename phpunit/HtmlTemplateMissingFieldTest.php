<?php
include_once(dirname(dirname(__FILE__)) . '/ExportToHtmlTemplate.php');

include_once('MockQueryResultIterator.php');
include_once('WP_Mock_Functions.php');
include_once('WPDB_Mock.php');
include_once('SquashOutputUnitTest.php');

class HtmlTemplateMissingFieldTest extends SquashOutputUnitTest {

    public function setUp() {
        date_default_timezone_set('America/New_York');
        $str = file_get_contents('HtmlTemplateMissingFieldTest.json');
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

    public function test_missing_lname_field() {
        $options = array();
        $options['content'] = '${fname} ${lname} | ';

        $exp = new ExportToHtmlTemplate();
        ob_start();
        $exp->export('dates', $options);
        $text = ob_get_contents();

        $this->assertEquals('Mike Simpson | Oya  | ', $text);
    }

    public function test_unknown_field_off() {
        $options = array();
        $options['content'] = '${fname} ${anunknownfield} | ';

        $exp = new ExportToHtmlTemplate();
        ob_start();
        $exp->export('dates', $options);
        $text = ob_get_contents();

        $this->assertEquals('Mike ${anunknownfield} | Oya ${anunknownfield} | ', $text);
    }

    public function test_unknown_field_on() {
        $options = array();
        $options['content'] = '${fname} ${anunknownfield} | ';
        $options['unknownfields'] = 'true';

        $exp = new ExportToHtmlTemplate();
        ob_start();
        $exp->export('dates', $options);
        $text = ob_get_contents();

        $this->assertEquals('Mike  | Oya  | ', $text);
    }

    public function test_unknown_fields_off() {
        $options = array();
        $options['content'] = '${fname} ${anunknownfield1} ${anunknownfield2} | ';

        $exp = new ExportToHtmlTemplate();
        ob_start();
        $exp->export('dates', $options);
        $text = ob_get_contents();

        $this->assertEquals('Mike ${anunknownfield1} ${anunknownfield2} | Oya ${anunknownfield1} ${anunknownfield2} | ', $text);
    }

    public function test_unknown_fields_on() {
        $options = array();
        $options['content'] = '${fname} ${anunknownfield1} ${anunknownfield2} | ';
        $options['unknownfields'] = 'true';

        $exp = new ExportToHtmlTemplate();
        ob_start();
        $exp->export('dates', $options);
        $text = ob_get_contents();

        $this->assertEquals('Mike   | Oya   | ', $text);
    }

    public function test_unknown_fields_with_default_trans() {
        $options = array();
        $options['content'] = '${fname} ${anunknownfield1} ${anunknownfield2} | ';
        $options['trans'] = 'DefaultField(anunknownfield1,hi,anunknownfield2,there)';

        $exp = new ExportToHtmlTemplate();
        ob_start();
        $exp->export('dates', $options);
        $text = ob_get_contents();

        $this->assertEquals('Mike hi there | Oya hi there | ', $text);
    }

}
