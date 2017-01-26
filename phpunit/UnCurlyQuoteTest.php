<?php

include_once(dirname(dirname(__FILE__)) . '/ShortCodeLoader.php');

class UnCurlyQuoteTest extends PHPUnit_Framework_TestCase {

    public function testStripCurlyQuote() {
        $sl = new UnCurlyQuoteTestShortCodeLoader;
        $stripped = $sl->stripCurlyQuotes('”hello”');
        $this->assertEquals('hello', $stripped);
    }

    public function testStripCurlyQuote2() {
        $sl = new UnCurlyQuoteTestShortCodeLoader;
        $stripped = $sl->stripCurlyQuotes('”3″');
        $this->assertEquals('3', $stripped);
    }

    public function testStripCurlyQuote3() {
        $sl = new UnCurlyQuoteTestShortCodeLoader;
        $stripped = $sl->decodeString('”submit_time>-6 0=days”');
        $this->assertEquals('submit_time>-6 0=days', $stripped);
    }

    public function testNotStripCurlyQuoteStart() {
        $sl = new UnCurlyQuoteTestShortCodeLoader;
        $stripped = $sl->stripCurlyQuotes('”hello');
        $this->assertEquals('”hello', $stripped);
    }

    public function testNotStripCurlyQuoteEnd() {
        $sl = new UnCurlyQuoteTestShortCodeLoader;
        $stripped = $sl->stripCurlyQuotes('hello”');
        $this->assertEquals('hello”', $stripped);
    }

    // https://core.trac.wordpress.org/ticket/29658#comment:4
    public function testWorkAroundForSpaceParseBug() {
        $sl = new UnCurlyQuoteTestShortCodeLoader;
        $atts['filter'] = '”submit_time>-6';
        $atts[0] = 'days”';
        $atts = $sl->decodeAttributes($atts);
        $this->assertEquals('submit_time>-6 days', $atts['filter']);
        $this->assertFalse(isset($atts[0]));
    }

}

class UnCurlyQuoteTestShortCodeLoader extends ShortCodeLoader {
    public function handleShortcode($atts, $content = null) {
    }
}