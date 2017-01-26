<?php

include_once(dirname(dirname(__FILE__)) . '/ShiftJisConverter.php');

class ShiftJisConverterTest extends PHPUnit_Framework_TestCase {

    public function test_canConvert() {
        $shift = new ShiftJisConverter();
        $this->assertTrue($shift->canConvert());
    }

    public function test_replaceShiftjisEscapeChars() {
        $shift = new ShiftJisConverter();

        $str1 = "\xE2\x80\x93abcdedg\xE2\x80\xA2";
        $str2 = "\xE2\x88\x92abcdedg\xE3\x83\xBB";

        $str = $shift->replaceShiftjisEscapeChars($str1);
        $this->assertEquals($str2, $str);
    }

    public function test_replaceShiftjisEscapeChars2() {
        $shift = new ShiftJisConverter();

        $str1 = '–abcdedg•';
        $str2 = '−abcdedg・';

        $str = $shift->replaceShiftjisEscapeChars($str1);
        $this->assertEquals($str2, $str);
    }

//    public function test_convertUtf8ToSjis() {
//        $shift = new ShiftJisConverter();
//        $str = $shift->convertUtf8ToSjis("ハローワールド");
//    }

}
