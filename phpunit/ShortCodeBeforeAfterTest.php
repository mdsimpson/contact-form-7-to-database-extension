<?php

include_once(dirname(dirname(__FILE__)) . '/ExportToHtmlTemplate.php');
include_once(dirname(dirname(__FILE__)) . '/ExportToHtmlTable.php');
include_once(dirname(dirname(__FILE__)) . '/ExportToJson.php');
include_once(dirname(dirname(__FILE__)) . '/ExportToValue.php');

include_once('MockQueryResultIterator.php');
include_once('WP_Mock_Functions.php');
include_once('WPDB_Mock.php');
include_once('SquashOutputUnitTest.php');


class ShortCodeBeforeAfterTest extends SquashOutputUnitTest {

    var $bufferOutput = false;

    public function tearDown() {
        if ($this->bufferOutput) {
            ob_flush();
            ob_end_clean();
            $this->bufferOutput = false;
        }
        parent::tearDown();
        ob_end_clean();
        ob_end_clean(); // not sure why we need to call twice to suppress output
    }

    public function exportSetup($data) {
        date_default_timezone_set('America/New_York');
        $mock = new MockQueryResultIterator($data);
        CFDBQueryResultIteratorFactory::getInstance()->setQueryResultsIteratorMock($mock);

        global $wpdb;
        $wpdb = new WPDB_Mock;

        $fields = array();
        foreach (array_keys($data[0]) as $key) {
            $fields[] = (object)array('field_name' => $key);
        }
        $wpdb->getResultReturnVal = $fields;
        $this->bufferOutput = true;
    }


    public function dataProvider() {
        $data = array();

        // [cfdb-html]
        $data[] = array('[cfdb-html]: no before, no after',
                '<p>To: ${first-name} ${last-name}</p>',
                new ExportToHtmlTemplate(),
                array(),
                '<p>To: John Doe</p>' .
                '<p>To: Richard Roe</p>');

        $data[] = array('[cfdb-html]: before, no after',
                '{{BEFORE}}<p><b>Registered Users</b></p>{{/BEFORE}}' .
                '<p>To: ${first-name} ${last-name}</p>',
                new ExportToHtmlTemplate(),
                array(),
                '<p><b>Registered Users</b></p>' .
                '<p>To: John Doe</p>' .
                '<p>To: Richard Roe</p>'
        );

        $data[] = array('[cfdb-html]: no before, after',
                '<p>To: ${first-name} ${last-name}</p>' .
                '{{AFTER}}<p><b>Thank you!</b></p>{{/AFTER}}',
                new ExportToHtmlTemplate(),
                array(),
                '<p>To: John Doe</p>' .
                '<p>To: Richard Roe</p>' .
                '<p><b>Thank you!</b></p>'
        );

        $data[] = array('[cfdb-html]: before, after',
                '{{BEFORE}}<p><b>Registered Users</b></p>{{/BEFORE}}' .
                '<p>To: ${first-name} ${last-name}</p>' .
                '{{AFTER}}<p><b>Thank you!</b></p>{{/AFTER}}',
                new ExportToHtmlTemplate(),
                array(),
                '<p><b>Registered Users</b></p>' .
                '<p>To: John Doe</p>' .
                '<p>To: Richard Roe</p>' .
                '<p><b>Thank you!</b></p>'
        );

        // [cfdb-json]
        $jsonOutput = '[
{"first-name":"John","last-name":"Doe"},
{"first-name":"Richard","last-name":"Roe"}
]';
        $data[] = array('[cfdb-json]: no before, no after',
                '',
                new ExportToJson(),
                array(),
                $jsonOutput
        );

        $data[] = array('[cfdb-json]: before, no after',
                '{{BEFORE}}Hi!{{/BEFORE}}',
                new ExportToJson(),
                array(),
                'Hi!' . $jsonOutput
        );

        $data[] = array('[cfdb-json]: no before, after',
                '{{AFTER}}Bye!{{/AFTER}}',
                new ExportToJson(),
                array(),
                $jsonOutput . 'Bye!'
        );

        $data[] = array('[cfdb-json]: before, after',
                '{{BEFORE}}Hi!{{/BEFORE}}{{AFTER}}Bye!{{/AFTER}}',
                new ExportToJson(),
                array(),
                'Hi!' . $jsonOutput . 'Bye!'
        );

        // [cfdb-value]
        $valueOutput = 'John, Doe, Richard, Roe';
        $data[] = array('[cfdb-value]: no before, no after',
                '',
                new ExportToValue(),
                array(),
                $valueOutput
        );

        $data[] = array('[cfdb-value]: before, no after',
                '{{BEFORE}}Hi!{{/BEFORE}}',
                new ExportToValue(),
                array(),
                'Hi!' . $valueOutput
        );

        $data[] = array('[cfdb-value]: no before, after',
                '{{AFTER}}Bye!{{/AFTER}}',
                new ExportToValue(),
                array(),
                $valueOutput . 'Bye!'
        );

        $data[] = array('[cfdb-value]: before, after',
                '{{BEFORE}}Hi!{{/BEFORE}}{{AFTER}}Bye!{{/AFTER}}',
                new ExportToValue(),
                array(),
                'Hi!' . $valueOutput . 'Bye!'
        );

        // [cfdb-count]
        $valueOutput = '2';
        $data[] = array('[cfdb-count]: no before, no after',
                '',
                new ExportToValue(),
                array('function' => 'count'),
                $valueOutput
        );

        $data[] = array('[cfdb-count]: before, no after',
                '{{BEFORE}}Hi!{{/BEFORE}}',
                new ExportToValue(),
                array('function' => 'count'),
                'Hi!' . $valueOutput
        );

        $data[] = array('[cfdb-count]: no before, after',
                '{{AFTER}}Bye!{{/AFTER}}',
                new ExportToValue(),
                array('function' => 'count'),
                $valueOutput . 'Bye!'
        );

        $data[] = array('[cfdb-count]: before, after',
                '{{BEFORE}}Hi!{{/BEFORE}}{{AFTER}}Bye!{{/AFTER}}',
                new ExportToValue(),
                array('function' => 'count'),
                'Hi!' . $valueOutput . 'Bye!'
        );

        // [cfdb-table]
        $tableOutput = $this->getTableOutput();
        $data[] = array('[cfdb-table]: no before, no after',
                '',
                new ExportToHtmlTable(),
                array('id' => 'mytable', 'class' => 'myclass'),
                $tableOutput
        );

        $data[] = array('[cfdb-table]: before, no after',
                '{{BEFORE}}Hi!{{/BEFORE}}',
                new ExportToHtmlTable(),
                array('id' => 'mytable', 'class' => 'myclass'),
                'Hi!' . $tableOutput
        );

        $data[] = array('[cfdb-table]: no before, after',
                '{{AFTER}}Bye!{{/AFTER}}',
                new ExportToHtmlTable(),
                array('id' => 'mytable', 'class' => 'myclass'),
                $tableOutput . 'Bye!'
        );

        $data[] = array('[cfdb-table]: before, after',
                '{{BEFORE}}Hi!{{/BEFORE}}{{AFTER}}Bye!{{/AFTER}}',
                new ExportToHtmlTable(),
                array('id' => 'mytable', 'class' => 'myclass'),
                'Hi!' . $tableOutput . 'Bye!'
        );

        return $data;
    }

    /**
     * @dataProvider dataProvider
     * @param $message string error message
     * @param $content string inner short code content
     * @param $exp ExportBase subclass instance
     * @param $options array export code options
     * @param $expected string expected export output
     */
    public function test_export($message, $content, $exp, $options, $expected) {
        $data = array(
                array('first-name' => 'John', 'last-name' => 'Doe'),
                array('first-name' => 'Richard', 'last-name' => 'Roe')
        );
        $this->exportSetup($data);
        $options['content'] = $content;

        ob_start();
        $exp->export('Form', $options);
        $text = ob_get_contents();

        $this->assertEquals($expected, $text, $message);
    }

    public function getTableOutput() {
        $data = array(
                array('first-name' => 'John', 'last-name' => 'Doe'),
                array('first-name' => 'Richard', 'last-name' => 'Roe')
        );
        $this->exportSetup($data);
        $options = array('id' => 'mytable', 'class' => 'myclass');
        $exp = new ExportToHtmlTable();
        ob_start();
        $exp->export('Form', $options);
        $text = ob_get_contents();
        return $text;
    }

}