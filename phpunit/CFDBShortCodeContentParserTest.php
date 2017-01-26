<?php

include_once(dirname(dirname(__FILE__)) . '/CFDBShortCodeContentParser.php');

class CFDBShortCodeContentParserTest extends PHPUnit_Framework_TestCase {

    public function test_parseHeaderTemplateFooter_no_header_no_footer() {
        $parser = new CFDBShortCodeContentParser();
        $content = 'Name: ${fname} ${lname}';

        $header = null;
        $template = null;
        $footer = null;
        list($header, $template, $footer) = $parser->parseBeforeContentAfter($content);

        $this->assertEquals(null, $header);
        $this->assertEquals($content, $template);
        $this->assertEquals(null, $footer);
    }

    public function test_parseHeaderTemplateFooter_header_no_footer() {
        $parser = new CFDBShortCodeContentParser();
        $content = '{{BEFORE}}This is my header{{/BEFORE}}Name: ${fname} ${lname}';

        $header = null;
        $template = null;
        $footer = null;
        list($header, $template, $footer) = $parser->parseBeforeContentAfter($content);

        $this->assertEquals('This is my header', $header);
        $this->assertEquals('Name: ${fname} ${lname}', $template);
        $this->assertEquals(null, $footer);
    }

    public function test_parseHeaderTemplateFooter_no_header_footer() {
        $parser = new CFDBShortCodeContentParser();
        $content = 'Name: ${fname} ${lname}{{AFTER}}This is my footer{{/AFTER}}';

        $header = null;
        $template = null;
        $footer = null;
        list($header, $template, $footer) = $parser->parseBeforeContentAfter($content);

        $this->assertEquals(null, $header);
        $this->assertEquals('Name: ${fname} ${lname}', $template);
        $this->assertEquals('This is my footer', $footer);
    }

    public function test_parseHeaderTemplateFooter_header_footer() {
        $parser = new CFDBShortCodeContentParser();
        $content = '{{BEFORE}}This is my header{{/BEFORE}}Name: ${fname} ${lname}{{AFTER}}This is my footer{{/AFTER}}';

        $header = null;
        $template = null;
        $footer = null;
        list($header, $template, $footer) = $parser->parseBeforeContentAfter($content);

        $this->assertEquals('This is my header', $header);
        $this->assertEquals('Name: ${fname} ${lname}', $template);
        $this->assertEquals('This is my footer', $footer);
    }

} 