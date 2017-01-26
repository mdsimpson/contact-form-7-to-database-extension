<?php

include_once(dirname(dirname(__FILE__)) . '/DereferenceShortcodeVars.php');
include_once('SquashOutputUnitTest.php');

class DereferenceShortcodeVarsTest extends SquashOutputUnitTest {

    public function test() {
        $dref = new DereferenceShortcodeVars;
        $this->assertEquals('stuff', $dref->extractParamName('_POST', '$_POST(\'stuff\')'));
    }

    public function testf() {
        $dref = new DereferenceShortcodeVars;
        $this->assertEquals('', $dref->extractParamName('_POST', '$_POSTxxxx(\'stuff\')'));
    }

    public function test_doublequotes() {
        $dref = new DereferenceShortcodeVars;
        $this->assertEquals('stuff', $dref->extractParamName('_POST', '$_POST("stuff")'));
    }

    public function testf_doublequotes() {
        $dref = new DereferenceShortcodeVars;
        $this->assertEquals('', $dref->extractParamName('_POST', '$_POSTx("stuff")'));
    }

    public function test_noquotes() {
        $dref = new DereferenceShortcodeVars;
        $this->assertEquals('stuff', $dref->extractParamName('_POST', '$_POST(stuff)'));
    }

    public function test_mixedQuotes() {
        $dref = new DereferenceShortcodeVars;
        $this->assertEquals('"stuff\'', $dref->extractParamName('_POST', '$_POST("stuff\')'));
    }

    public function test_empty1() {
        $dref = new DereferenceShortcodeVars;
        $this->assertEquals('', $dref->extractParamName('_POST', '$_POST("")'));
    }

    public function test_empty2() {
        $dref = new DereferenceShortcodeVars;
        $this->assertEquals('', $dref->extractParamName('_POST', '$_POST(\'\')'));
    }




    public function test_getMatches_in_middle() {
        $dref = new DereferenceShortcodeVars;
        $matches = $dref->getMatches('_POST', 'blah blah $_POST(mike1)blah');
        print_r($matches);
        $this->assertEquals(1, count($matches));
        $this->assertEquals('$_POST(mike1)', $matches[0]);

        $this->assertEquals('mike1', $dref->extractParamName('_POST', $matches[0]));
    }

    public function test_getMatches_multi_in_middle_1_noquotes() {
        $dref = new DereferenceShortcodeVars;
        $matches = $dref->getMatches('_POST', 'blah blah $_POST(mike2)blah$_POST(oya)');
        print_r($matches);
        $this->assertEquals(2, count($matches));
        $this->assertEquals('$_POST(mike2)', $matches[0]);
        $this->assertEquals('$_POST(oya)', $matches[1]);

        $this->assertEquals('mike2', $dref->extractParamName('_POST', $matches[0]));
        $this->assertEquals('oya', $dref->extractParamName('_POST', $matches[1]));
    }

    public function test_getMatches_multi_in_middle_1_quotes() {
        $dref = new DereferenceShortcodeVars;
        $matches = $dref->getMatches('_POST', 'blah blah $_POST("mike2")blah$_POST("oya")');
        print_r($matches);
        $this->assertEquals(2, count($matches));
        $this->assertEquals('$_POST("mike2")', $matches[0]);
        $this->assertEquals('$_POST("oya")', $matches[1]);

        $this->assertEquals('mike2', $dref->extractParamName('_POST', $matches[0]));
        $this->assertEquals('oya', $dref->extractParamName('_POST', $matches[1]));

    }

    public function test_getMatches_multi_in_middle_2_quotes() {
        $dref = new DereferenceShortcodeVars;
        $matches = $dref->getMatches('_POST', 'blah blah $_POST(\'mike3\')blah$_POST(oya)');
        print_r($matches);
        $this->assertEquals(2, count($matches));
        $this->assertEquals('$_POST(\'mike3\')', $matches[0]);
        $this->assertEquals('$_POST(oya)', $matches[1]);

        $this->assertEquals('mike3', $dref->extractParamName('_POST', $matches[0]));
        $this->assertEquals('oya', $dref->extractParamName('_POST', $matches[1]));

    }

    public function test_getMatches_multi_in_middle_4_spaceBeforeOpenParen() {
        $dref = new DereferenceShortcodeVars;
        $matches = $dref->getMatches('_POST', 'blah blah $_POST  (\'mike4\')blah$_POST  (oya4)');
        print_r($matches);
        $this->assertEquals(2, count($matches));
        $this->assertEquals('$_POST  (\'mike4\')', $matches[0]);
        $this->assertEquals('$_POST  (oya4)', $matches[1]);

        $this->assertEquals('mike4', $dref->extractParamName('_POST', $matches[0]));
        $this->assertEquals('oya4', $dref->extractParamName('_POST', $matches[1]));
    }

    public function test_getMatches_multi_in_middle_5_spaceBeforeCloseParen() {
        $dref = new DereferenceShortcodeVars;
        $matches = $dref->getMatches('_POST', 'blah blah $_POST  (\'mike5\'  )blah$_POST  (oya5  )');
        print_r($matches);
        $this->assertEquals(2, count($matches));
        $this->assertEquals('$_POST  (\'mike5\'  )', $matches[0]);
        $this->assertEquals('$_POST  (oya5  )', $matches[1]);

        $this->assertEquals('mike5', $dref->extractParamName('_POST', $matches[0]));
        $this->assertEquals('oya5  ', $dref->extractParamName('_POST', $matches[1]));

    }


    public function test_getMatches_multi_in_middle_6_spacesAfterOpenParen() {
        $dref = new DereferenceShortcodeVars;
        $matches = $dref->getMatches('_POST', 'blah blah $_POST  (     \'mike6\'  )blah$_POST  (  oya  )');
        print_r($matches);
        $this->assertEquals(2, count($matches));
        $this->assertEquals('$_POST  (     \'mike6\'  )', $matches[0]);
        $this->assertEquals('$_POST  (  oya  )', $matches[1]);

        $this->assertEquals('mike6', $dref->extractParamName('_POST', $matches[0]));
        $this->assertEquals('  oya  ', $dref->extractParamName('_POST', $matches[1]));
   }

    public function test_qname() {
        $dref = new DereferenceShortcodeVars;
        $this->assertEquals('qname', $dref->extractParamName('_POST', '$_POST(qname)'));
    }

    public function testConvert() {
        $dref = new DereferenceShortcodeVars;
        $string = $dref->convert('your-name=$_POST(aname)&&your-subject=$_POST(subject)');
        $this->assertEquals('your-name=&&your-subject=', $string);
    }

}

