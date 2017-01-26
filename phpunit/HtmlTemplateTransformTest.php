<?php
include_once(dirname(dirname(__FILE__)) . '/ExportToHtmlTemplate.php');
include_once(dirname(dirname(__FILE__)) . '/CFDBPermittedFunctions.php');

include_once('MockQueryResultIterator.php');
include_once('WP_Mock_Functions.php');
include_once('WPDB_Mock.php');
include_once('SquashOutputUnitTest.php');

class HtmlTemplateTransformTest extends SquashOutputUnitTest {

    public function setUp() {
        date_default_timezone_set('America/New_York');
        $str = file_get_contents('HtmlTemplateTransformTest.json');
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

        CFDBPermittedFunctions::getInstance()->addPermittedFunction('cambiaFecha');
    }

    public function test_transform1() {
        $options = array();
        $options['trans'] = 'inicio=cambiaFecha(inicio)';
        $options['content'] = '${name}|${inicio}|${fin} ';

        $exp = new ExportToHtmlTemplate();
        ob_start();
        $exp->export('dates', $options);
        $text = ob_get_contents();

        $this->assertEquals("Mike|1.1.2014|2/1/2014 Oya|5.1.2014|6/1/2014 ", $text);
    }

    public function test_transform2() {
        $options = array();
        $options['trans'] = 'fin=cambiaFecha(fin)';
        $options['content'] = '${name}|${inicio}|${fin} ';

        $exp = new ExportToHtmlTemplate();
        ob_start();
        $exp->export('dates', $options);
        $text = ob_get_contents();

        $this->assertEquals("Mike|1/1/2014|2.1.2014 Oya|5/1/2014|6.1.2014 ", $text);
    }

    public function test_transforms() {
        $options = array();
        $options['trans'] = 'inicio=cambiaFecha(inicio)&&fin=cambiaFecha(fin)';
        $options['content'] = '${name}|${inicio}|${fin} ';

        $exp = new ExportToHtmlTemplate();
        ob_start();
        $exp->export('dates', $options);
        $text = ob_get_contents();

        $this->assertEquals("Mike|1.1.2014|2.1.2014 Oya|5.1.2014|6.1.2014 ", $text);
    }

}

function cambiaFecha($date) {
    return str_replace('/', '.', $date);
}