<?php

include_once(dirname(dirname(__FILE__)) . '/CF7DBPlugin.php');

class ParseFieldMatchesTest extends PHPUnit_Framework_TestCase {


    public function dataProvider() {
        $data = array();

        $data[] = array(null, array());

        $data[] = array('', array());

        $data[] = array(' ', array(' '));

        $data[] = array('hello', array('hello'));

        $data[] = array('hi,there,how,are,you',
                array('hi', 'there', 'how', 'are', 'you'));

        $data[] = array('hi,there,,how,are,you',
                array('hi', 'there', 'how', 'are', 'you'));

        $data[] = array('hi,there,how,are,you,',
                array('hi', 'there', 'how', 'are', 'you'));

        $data[] = array('hi,there ,how,are,you',
                array('hi', 'there ', 'how', 'are', 'you'));

        $data[] = array(' hi,there,how,are,you',
                array(' hi', 'there', 'how', 'are', 'you'));

        $data[] = array(' hi,there,how,are,you,',
                array(' hi', 'there', 'how', 'are', 'you'));


        $data[] = array('/.*wpcf7.*/,_wpnonce',
                array('/.*wpcf7.*/', '_wpnonce'));

        $data[] = array('/.*wpcf7.*/,_wpnonce,/^[a-f0-9]{32}$/',
                array('/.*wpcf7.*/', '_wpnonce', '/^[a-f0-9]{32}$/'));

        $data[] = array('/.*wpcf7.*/,_wpnonce,/^[a-f0-9]{32,32}$/',
                array('/.*wpcf7.*/', '_wpnonce', '/^[a-f0-9]{32,32}$/'));

        $data[] = array('hello,/,*\/abc/,there',
                array('hello', '/,*\/abc/', 'there'));

        $data[] = array('hello,/,*\/ x/i,there',
                array('hello', '/,*\/ x/i', 'there'));

        $data[] = array('hello,/,*\\ \/,abc/i,there',
                array('hello', '/,*\\ \/,abc/i', 'there'));

        $data[] = array('hello,/,*\\\\/,abc/i,there',
                array('hello', '/,*\\\\/','abc/i', 'there'));

        return $data;
    }

    /**
     * @dataProvider dataProvider
     * @param $option string
     * @param $expected array
     */
    public function test_export($option, $expected) {
        $cfdb = new CF7DBPlugin();
        $actual = $cfdb->parseOption($option);
        $this->assertEquals($expected, $actual);
    }

}