<?php

include_once(dirname(dirname(__FILE__)) . '/CFDBFilterParser.php');
include_once(dirname(dirname(__FILE__)) . '/CFDBValueConverter.php');
include_once(dirname(dirname(__FILE__)) . '/DereferenceShortcodeVars.php');
include_once('SquashOutputUnitTest.php');

class CFDBFilterParserTest extends SquashOutputUnitTest {


    public function test1() {
        $filterText = 'a=b';
        $filters = preg_split('/&&|\|\|/', $filterText, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        $this->assertEquals($filterText, $filters[0]);
        print_r($filters);
    }

    public function test2() {
        $filterText = 'aaa=bbb&&ccc=ddd&&eee<>fff';
        $filters = preg_split('/&&|\|\|/', $filterText, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        $this->assertEquals('aaa=bbb', $filters[0]);
        $this->assertEquals('ccc=ddd', $filters[1]);
        $this->assertEquals('eee<>fff', $filters[2]);
        print_r($filters);
    }

    public function test3() {
        $filterText = 'aaa=bbb||ccc=ddd||eee<>fff';
        $filters = preg_split('/&&|\|\|/', $filterText, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        $this->assertEquals('aaa=bbb', $filters[0]);
        $this->assertEquals('ccc=ddd', $filters[1]);
        $this->assertEquals('eee<>fff', $filters[2]);
        print_r($filters);
    }

    public function test4() {
        $filterText = 'aaa=bbb&&ccc=ddd&&eee<>fff';
        $filters = preg_split('/(&&)|(\|\|)/', $filterText, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        $this->assertEquals('aaa=bbb', $filters[0]);
        $this->assertEquals('&&', $filters[1]);
        $this->assertEquals('ccc=ddd', $filters[2]);
        $this->assertEquals('&&', $filters[3]);
        $this->assertEquals('eee<>fff', $filters[4]);
        print_r($filters);
    }

    public function test5() {
        $filters = preg_split('/&&/', 'your-message=admin&&your-subject=Simpson', -1, PREG_SPLIT_NO_EMPTY);
        $this->assertEquals('your-message=admin', $filters[0]);
        $this->assertEquals('your-subject=Simpson', $filters[1]);
        $this->assertEquals(count($filters), 2);
    }

    public function test_parseORs_1() {
        $p = new CFDBFilterParser;
        $filters = $p->parseORs('aaa=bbb||ccc=ddd||eee<>fff');
        $this->assertEquals('aaa=bbb', $filters[0]);
        $this->assertEquals('ccc=ddd', $filters[1]);
        $this->assertEquals('eee<>fff', $filters[2]);
        $this->assertEquals(count($filters), 3);
    }

    public function test_parseORs_2() {
        $p = new CFDBFilterParser;
        $filters = $p->parseORs('aaa=bbb&&ccc=ddd||eee<>fff');
        $this->assertEquals('aaa=bbb&&ccc=ddd', $filters[0]);
        $this->assertEquals('eee<>fff', $filters[1]);
        $this->assertEquals(count($filters), 2);
    }

    public function test_parseORs_3() {
        $p = new CFDBFilterParser;
        $filters = $p->parseORs('aaa=bbb');
        $this->assertEquals('aaa=bbb', $filters[0]);
        $this->assertEquals(count($filters), 1);
    }

    public function test_parseANDs_1() {
        $p = new CFDBFilterParser;
        $filters = $p->parseANDs('aaa=bbb&&ccc=ddd&&eee<>fff');
        $this->assertEquals('aaa=bbb', $filters[0]);
        $this->assertEquals('ccc=ddd', $filters[1]);
        $this->assertEquals('eee<>fff', $filters[2]);
        $this->assertEquals(count($filters), 3);
    }

    public function test_parseANDs_2() {
        $p = new CFDBFilterParser;
        $filters = $p->parseANDs('aaa=bbb||ccc=ddd&&eee<>fff');
        $this->assertEquals('aaa=bbb||ccc=ddd', $filters[0]);
        $this->assertEquals('eee<>fff', $filters[1]);
        $this->assertEquals(count($filters), 2);
    }

    public function test_parseANDs_3() {
        $p = new CFDBFilterParser;
        $filters = $p->parseANDs('aaa=bbb');
        $this->assertEquals('aaa=bbb', $filters[0]);
        $this->assertEquals(count($filters), 1);
    }


    public function test_parseEncodedANDs_1() {
        $p = new CFDBFilterParser;
        $filters = $p->parseANDs('aaa=bbb&amp;&amp;ccc=ddd&amp;&amp;eee<>fff');
        $this->assertEquals('aaa=bbb', $filters[0]);
        $this->assertEquals('ccc=ddd', $filters[1]);
        $this->assertEquals('eee<>fff', $filters[2]);
        $this->assertEquals(count($filters), 3);
    }

    public function test_parseEncodedANDs_2() {
        $p = new CFDBFilterParser;
        $filters = $p->parseANDs('aaa=bbb&#038;&#038;ccc=ddd&#038;&#038;eee<>fff');
        $this->assertEquals('aaa=bbb', $filters[0]);
        $this->assertEquals('ccc=ddd', $filters[1]);
        $this->assertEquals('eee<>fff', $filters[2]);
        $this->assertEquals(count($filters), 3);
    }

    /*
        Array
        (
            [0] => Array
                (
                    [0] => Array
                        (
                            [0] => aaa
                            [1] => =
                            [2] => bbb
                        )

                )

        )
     */
    public function test_parseFilterString_1() {
        $p = new CFDBFilterParser;
        $p->parse('aaa=bbb');
        $tree = $p->getFilterTree();

        $this->assertEquals('aaa', $tree[0][0][0], print_r($tree, true));
        $this->assertEquals('=', $tree[0][0][1], print_r($tree, true));
        $this->assertEquals('bbb', $tree[0][0][2], print_r($tree, true));
        $this->assertEquals(count($tree), 1, print_r($tree, true));
        $this->assertEquals(count($tree[0]), 1, print_r($tree, true));
        $this->assertEquals(count($tree[0][0]), 3, print_r($tree, true));
    }

    /*
        Array
        (
            [0] => Array
                (
                    [0] => Array
                        (
                            [0] => aaa
                            [1] => =
                            [2] => bbb
                        )

                )

            [1] => Array
                (
                    [0] => Array
                        (
                            [0] => ccc
                            [1] => =
                            [2] => ddd
                        )

                )

        )
     */
    public function test_parseFilterString_2() {
        $p = new CFDBFilterParser;
        $p->parse('aaa=bbb||ccc=ddd');
        $tree = $p->getFilterTree();
        $this->assertEquals('aaa', $tree[0][0][0], print_r($tree, true));
        $this->assertEquals('=', $tree[0][0][1], print_r($tree, true));
        $this->assertEquals('bbb', $tree[0][0][2], print_r($tree, true));

        $this->assertEquals('ccc', $tree[1][0][0], print_r($tree, true));
        $this->assertEquals('=', $tree[1][0][1], print_r($tree, true));
        $this->assertEquals('ddd', $tree[1][0][2], print_r($tree, true));


        $this->assertEquals(count($tree), 2, print_r($tree, true));
        $this->assertEquals(count($tree[0][0]), 3, print_r($tree, true));
        $this->assertEquals(count($tree[1][0]), 3, print_r($tree, true));

    }

    /*
        Array
        (
            [0] => Array
                (
                    [0] => Array
                        (
                            [0] => aaa
                            [1] => =
                            [2] => bbb
                        )

                )

            [1] => Array
                (
                    [0] => Array
                        (
                            [0] => ccc
                            [1] => =
                            [2] => ddd
                        )

                    [1] => Array
                        (
                            [0] => eee
                            [1] => =
                            [2] => fff
                        )

                )

        )
     */
    public function test_parseFilterString_3() {
        $p = new CFDBFilterParser;
        $p->parse('aaa=bbb||ccc=ddd&&eee=fff');
        $tree = $p->getFilterTree();
        $this->assertEquals(count($tree), 2, print_r($tree, true));

        $this->assertEquals('aaa', $tree[0][0][0], print_r($tree, true));
        $this->assertEquals('=', $tree[0][0][1], print_r($tree, true));
        $this->assertEquals('bbb', $tree[0][0][2], print_r($tree, true));
        $this->assertEquals(count($tree[0][0]), 3, print_r($tree, true));

        $this->assertEquals('ccc', $tree[1][0][0], print_r($tree, true));
        $this->assertEquals('=', $tree[1][0][1], print_r($tree, true));
        $this->assertEquals('ddd', $tree[1][0][2], print_r($tree, true));
        $this->assertEquals(count($tree[1][0]), 3, print_r($tree, true));


        $this->assertEquals('eee', $tree[1][1][0], print_r($tree, true));
        $this->assertEquals('=', $tree[1][1][1], print_r($tree, true));
        $this->assertEquals('fff', $tree[1][1][2], print_r($tree, true));
        $this->assertEquals(count($tree[1][1]), 3, print_r($tree, true));

        $this->assertEquals(count($tree[1]), 2, print_r($tree, true));
       
        $this->assertEquals(count($tree), 2, print_r($tree, true));

    }

/*
    Array
    (
        [0] => Array
            (
                [0] => Array
                    (
                        [0] => aaa
                        [1] => =
                        [2] => bbb
                    )

            )

        [1] => Array
            (
                [0] => Array
                    (
                        [0] => ccc
                        [1] => =
                        [2] => ddd
                    )

                [1] => Array
                    (
                        [0] => eee
                        [1] => =
                        [2] => fff
                    )

                [2] => Array
                    (
                        [0] => ggg
                        [1] => =
                        [2] => hhh
                    )

            )

    )
 */
    public function test_parseFilterString_4() {
        $p = new CFDBFilterParser;
        $p->parse('aaa=bbb||ccc=ddd&&eee=fff&&ggg=hhh');
        $tree = $p->getFilterTree();
        $this->assertEquals(count($tree), 2, print_r($tree, true));

        $this->assertEquals('aaa', $tree[0][0][0], print_r($tree, true));
        $this->assertEquals('=', $tree[0][0][1], print_r($tree, true));
        $this->assertEquals('bbb', $tree[0][0][2], print_r($tree, true));
        $this->assertEquals(count($tree[0][0]), 3, print_r($tree, true));

        $this->assertEquals('ccc', $tree[1][0][0], print_r($tree, true));
        $this->assertEquals('=', $tree[1][0][1], print_r($tree, true));
        $this->assertEquals('ddd', $tree[1][0][2], print_r($tree, true));

        $this->assertEquals('eee', $tree[1][1][0], print_r($tree, true));
        $this->assertEquals('=', $tree[1][1][1], print_r($tree, true));
        $this->assertEquals('fff', $tree[1][1][2], print_r($tree, true));

        $this->assertEquals('ggg', $tree[1][2][0], print_r($tree, true));
        $this->assertEquals('=', $tree[1][2][1], print_r($tree, true));
        $this->assertEquals('hhh', $tree[1][2][2], print_r($tree, true));

        $this->assertEquals(count($tree[1]), 3, print_r($tree, true));
    }

    /*
        Array
        (
            [0] => Array
                (
                    [0] => Array
                        (
                            [0] => aaa
                            [1] => =
                            [2] => bbb
                        )

                    [1] => Array
                        (
                            [0] => yyy
                            [1] => =
                            [2] => zzz
                        )

                    [2] => Array
                        (
                            [0] => www
                            [1] => =
                            [2] => xxx
                        )

                )

            [1] => Array
                (
                    [0] => Array
                        (
                            [0] => ccc
                            [1] => =
                            [2] => ddd
                        )

                    [1] => Array
                        (
                            [0] => eee
                            [1] => =
                            [2] => fff
                        )

                    [2] => Array
                        (
                            [0] => ggg
                            [1] => =
                            [2] => hhh
                        )

                )

        )
     */
    public function test_parseFilterString_5() {
        $p = new CFDBFilterParser;
        $p->parse('aaa=bbb&&yyy=zzz&&www=xxx||ccc=ddd&&eee=fff&&ggg=hhh');
        $tree = $p->getFilterTree();
        $this->assertEquals(count($tree), 2, print_r($tree, true));

        $this->assertEquals('aaa', $tree[0][0][0], print_r($tree, true));
        $this->assertEquals('=', $tree[0][0][1], print_r($tree, true));
        $this->assertEquals('bbb', $tree[0][0][2], print_r($tree, true));

        $this->assertEquals('yyy', $tree[0][1][0], print_r($tree, true));
        $this->assertEquals('=', $tree[0][1][1], print_r($tree, true));
        $this->assertEquals('zzz', $tree[0][1][2], print_r($tree, true));

        $this->assertEquals('www', $tree[0][2][0], print_r($tree, true));
        $this->assertEquals('=', $tree[0][2][1], print_r($tree, true));
        $this->assertEquals('xxx', $tree[0][2][2], print_r($tree, true));

        $this->assertEquals(count($tree[0]), 3, print_r($tree, true));

        $this->assertEquals('ccc', $tree[1][0][0], print_r($tree, true));
        $this->assertEquals('=', $tree[1][0][1], print_r($tree, true));
        $this->assertEquals('ddd', $tree[1][0][2], print_r($tree, true));

        $this->assertEquals('eee', $tree[1][1][0], print_r($tree, true));
        $this->assertEquals('=', $tree[1][1][1], print_r($tree, true));
        $this->assertEquals('fff', $tree[1][1][2], print_r($tree, true));

        $this->assertEquals('ggg', $tree[1][2][0], print_r($tree, true));
        $this->assertEquals('=', $tree[1][2][1], print_r($tree, true));
        $this->assertEquals('hhh', $tree[1][2][2], print_r($tree, true));

        $this->assertEquals(count($tree[1]), 3, print_r($tree, true));
    }

    public function test_parseFilterString_6() {
        $p = new CFDBFilterParser;
        $p->parse('');
        $tree = $p->getFilterTree();
        $this->assertEquals(count($tree), 0);
    }

    private function parseExpression($a, $op, $b) {
        $p = new CFDBFilterParser;
        $exp = $p->parseExpression($a . $op . $b);
        $this->assertEquals(count($exp), 3);
        $this->assertEquals($exp[0], $a);
        $this->assertEquals($exp[1], $op);
        $this->assertEquals($exp[2], $b);
    }

    public function test_parseExpression_1() {
        $this->parseExpression('aaa', '=', 'b');
        $this->parseExpression('aaa', '==', 'b');
        $this->parseExpression('aaa', '===', 'b');
        $this->parseExpression('aaa', '!=', 'b');
        $this->parseExpression('aaa', '!==', 'b');
        $this->parseExpression('aaa', '<>', 'b');
        $this->parseExpression('aaa', '>=', 'b');
        $this->parseExpression('aaa', '<=', 'b');
        $this->parseExpression('aaa', '<', 'b');
        $this->parseExpression('aaa', '>', 'b');
    }


    public function test_hasFilters() {
        $p = new CFDBFilterParser;
        $p->parse('aaa=bbb');
        //$tree = $p->getFilterTree();
        $this->assertTrue($p->hasFilters());

        $p->parse('aaa=bbb&&yyy=zzz&&www=xxx||ccc=ddd&&eee=fff&&ggg=hhh');
        //$tree = $p->getFilterTree();
        $this->assertTrue($p->hasFilters());

        $p->parse('');
        //$tree = $p->getFilterTree();
        $this->assertFalse($p->hasFilters());
    }

    public function test_evaluateLeftOpRightComparison_1() {
        $p = new CFDBFilterParser;
        $this->assertTrue($p->evaluateLeftOpRightComparison('aaa', '=', 'aaa'));
        $this->assertTrue($p->evaluateLeftOpRightComparison(3, '=', 3));

        $this->assertTrue($p->evaluateLeftOpRightComparison('aaa', '==', 'aaa'));
        $this->assertTrue($p->evaluateLeftOpRightComparison(3, '==', 3));

        $this->assertTrue($p->evaluateLeftOpRightComparison('aaa', '===', 'aaa'));
        $this->assertTrue($p->evaluateLeftOpRightComparison(3, '===', 3));

        $this->assertTrue($p->evaluateLeftOpRightComparison('aaa', '!=', 'bbb'));
        $this->assertTrue($p->evaluateLeftOpRightComparison('aaa', '!==', 'bbb'));
        $this->assertTrue($p->evaluateLeftOpRightComparison('aaa', '<>', 'bbb'));
        $this->assertTrue($p->evaluateLeftOpRightComparison('aaa', '!=', 3));

        $this->assertTrue($p->evaluateLeftOpRightComparison(3, '<', 4));
        $this->assertTrue($p->evaluateLeftOpRightComparison(3, '<=', 4));
        $this->assertTrue($p->evaluateLeftOpRightComparison(3, '<=', 3));

        $this->assertTrue($p->evaluateLeftOpRightComparison(4, '>', 3));
        $this->assertTrue($p->evaluateLeftOpRightComparison(4, '>=', 3));
        $this->assertTrue($p->evaluateLeftOpRightComparison(4, '>=', 4));

    }

    public function test_evaluateLeftOpRightComparison_2() {
        $p = new CFDBFilterParser;
        $this->assertFalse($p->evaluateLeftOpRightComparison('aaa', '=', 'bbb'));
        $this->assertFalse($p->evaluateLeftOpRightComparison(3, '=', 4));

        $this->assertFalse($p->evaluateLeftOpRightComparison('aaa', '==', 'bbb'));
        $this->assertFalse($p->evaluateLeftOpRightComparison(3, '==', 4));

        $this->assertFalse($p->evaluateLeftOpRightComparison('aaa', '===', 'bbb'));
        $this->assertFalse($p->evaluateLeftOpRightComparison(3, '===', 4));

        $this->assertFalse($p->evaluateLeftOpRightComparison('aaa', '!=', 'aaa'));
        $this->assertFalse($p->evaluateLeftOpRightComparison('aaa', '!==', 'aaa'));
        $this->assertFalse($p->evaluateLeftOpRightComparison('aaa', '<>', 'aaa'));
        $this->assertFalse($p->evaluateLeftOpRightComparison(3, '!=', 3));

        $this->assertFalse($p->evaluateLeftOpRightComparison(3, '>', 4));
        $this->assertFalse($p->evaluateLeftOpRightComparison(3, '>=', 4));

        $this->assertFalse($p->evaluateLeftOpRightComparison(4, '<', 3));
        $this->assertFalse($p->evaluateLeftOpRightComparison(4, '<=', 3));

    }

    public function test_evaluateLeftOpRightComparison_badOperator() {
        ini_set('error_reporting', !E_NOTICE & !E_WARNING);
        $p = new CFDBFilterParser;
        //PHPUnit_Framework_Error_Warning::$enabled = FALSE;
        PHPUnit_Framework_Error_Notice::$enabled = FALSE;
        $this->assertFalse($p->evaluateLeftOpRightComparison('aaa', '%', 'aaa'));
    }

    public function test_evaluateLeftOpRightComparison_badOperator2() {
        ini_set('error_reporting', !E_NOTICE & !E_WARNING);
        $p = new CFDBFilterParser;
        //PHPUnit_Framework_Error_Warning::$enabled = FALSE;
        PHPUnit_Framework_Error_Notice::$enabled = FALSE;
        $this->assertFalse($p->evaluateLeftOpRightComparison('aaa', null, 'aaa'));
    }

    public function test_evaluateLeftOpRightComparison_3() {
        $p = new CFDBFilterParser;
        //PHPUnit_Framework_Error_Warning::$enabled = FALSE;
        PHPUnit_Framework_Error_Notice::$enabled = FALSE;
        $this->assertFalse($p->evaluateLeftOpRightComparison('aaa', '=', null));
    }

    public function test_evaluateLeftOpRightComparison_4() {
        $p = new CFDBFilterParser;
        //PHPUnit_Framework_Error_Warning::$enabled = FALSE;
        PHPUnit_Framework_Error_Notice::$enabled = FALSE;
        $this->assertFalse($p->evaluateLeftOpRightComparison(null, '=', 'aaa'));
    }

    public function test_evaluateLeftOpRightComparison_null() {
        $p = new CFDBFilterParser;
        $val = null;
        $this->assertTrue($p->evaluateLeftOpRightComparison($val, '=', 'null'));

        $val = 'null';
        $this->assertFalse($p->evaluateLeftOpRightComparison($val, '=', 'null'));
    }

    public function test_evaluate_1t() {
        $p = new CFDBFilterParser;
        $p->parse('aaa=bbb');
        $data = array('aaa' => 'bbb');
        $this->assertTrue($p->evaluate($data));
    }

    public function test_evaluate_1f() {
        $p = new CFDBFilterParser;
        $p->parse('aaa=bbb');
        $data = array('aaa' => 'xxx');
        $this->assertFalse($p->evaluate($data));
    }

    public function test_evaluate_2tOR() {
        $p = new CFDBFilterParser;
        $p->parse('aaa=bbb||ccc=ddd');
        $data = array('aaa' => 'bbb', 'ccc' => 'ddd');
        $this->assertTrue($p->evaluate($data));
    }

    public function test_evaluate_2tAND() {
        $p = new CFDBFilterParser;
        $p->parse('aaa=bbb&&ccc=ddd');
        $data = array('aaa' => 'bbb', 'ccc' => 'ddd');
        $this->assertTrue($p->evaluate($data));
    }

    public function test_evaluate_2f() {
        $p = new CFDBFilterParser;
        $p->parse('aaa=xxx&&ccc=ddd');
        $data = array('aaa' => 'bbb', 'ccc' => 'ddd');
        $this->assertFalse($p->evaluate($data));
    }

    public function test_evaluate_3() {
        $p = new CFDBFilterParser;
        // no filters added
        $data = array('aaa' => 'bbb');
        $this->assertTrue($p->evaluate($data));
    }

    public function test_evaluate_4() {
        $p = new CFDBFilterParser;
        $p->parse('aaa=xxx||ccc=ddd');
        $data = array('aaa' => 'bbb', 'ccc' => 'ddd');
        $this->assertTrue($p->evaluate($data));
    }


    public function test_evaluate_5() {
        $p = new CFDBFilterParser;
        $p->parse('aaa=bbb&&yyy=zzz&&www=xxx||ccc=ddd&&eee=fff&&ggg=hhh');

        $data = array(
            'aaa' => 'bbb',
            'yyy' => 'zzz',
            'www' => 'xxx',

            'ccc' => 'ddd',
            'eee' => 'fff',
            'ggg' => 'hhh',
        );
        $this->assertTrue($p->evaluate($data));
    }

    public function test_evaluate_6() {
        $p = new CFDBFilterParser;
        $p->parse('aaa=bbb&&yyy=zzz&&www=xxx||ccc=ddd&&eee=fff&&ggg=hhh');

        $data = array(
            'aaa' => 'XXXX',
            'yyy' => 'zzz',
            'www' => 'xxx',

            'ccc' => 'ddd',
            'eee' => 'fff',
            'ggg' => 'hhh',
        );
        $this->assertTrue($p->evaluate($data));
    }

    public function test_evaluate_7() {
        $p = new CFDBFilterParser;
        $p->parse('aaa=bbb&&yyy=zzz&&www=xxx||ccc=ddd&&eee=fff&&ggg=hhh');

        $data = array(
            'aaa' => 'XXXX',
            'yyy' => 'XXXX',
            'www' => 'XXXX',

            'ccc' => 'ddd',
            'eee' => 'fff',
            'ggg' => 'hhh',
        );
        $this->assertTrue($p->evaluate($data));
    }

    public function test_evaluate_8() {
        $p = new CFDBFilterParser;
        $p->parse('aaa=bbb&&yyy=zzz&&www=xxx||ccc=ddd&&eee=fff&&ggg=hhh');

        $data = array(
            'aaa' => 'XXXX',
            'yyy' => 'zzz',
            'www' => 'xxx',

            'ccc' => 'XXXX',
            'eee' => 'fff',
            'ggg' => 'hhh',
        );
        $this->assertFalse($p->evaluate($data));

    }

    public function test_evaluate_9() {
        $p = new CFDBFilterParser;
        $p->parse('aaa>3');
        $data = array('aaa' => 4);
        $this->assertTrue($p->evaluate($data));
    }

    public function test_evaluate_nullValue() {
        $p = new CFDBFilterParser;
        $p->parse('aaa=null&&yyy=zzz');

        $data = array(
            //'aaa' => 'bbb',
            'yyy' => 'zzz'
        );
        $this->assertTrue($p->evaluate($data));
    }

    public function test_evaluate_nullValue2() {
        $p = new CFDBFilterParser;
        $p->parse('aaa=null&&yyy=zzz');

        $data = array(
            'aaa' => 'null',
            'yyy' => 'zzz'
        );
        $this->assertFalse($p->evaluate($data));
    }

    public function test_evaluate_regex1() {
        $p = new CFDBFilterParser;
        $p->parse('aaa~~/^b/');
        $data = array('aaa' => 'bbbb');
        $this->assertTrue($p->evaluate($data));
    }

    public function test_evaluate_regex2() {
        $p = new CFDBFilterParser;
        $p->parse('aaa~~/^b/');
        $data = array('aaa' => 'abbb');
        $this->assertFalse($p->evaluate($data));
    }


    public function test_setComparisonValuePreprocessor1() {
        $p = new CFDBFilterParser;
        $p->setComparisonValuePreprocessor(new ChangeNameToValue('$stuff', 'AAA'));
        $p->parse('aaa=$stuff');
        $data = array('aaa' => 'AAA');
        $this->assertTrue($p->evaluate($data));
    }

    public function test_setComparisonValuePreprocessor2() {
        $p = new CFDBFilterParser;
        $p->setComparisonValuePreprocessor(new ChangeNameToValue('$stuff', 'AAA'));
        $p->parse('aaa=$stuff');
        $data = array('aaa' => 'BBB');
        $this->assertFalse($p->evaluate($data));
    }

    public function test_setComparisonValuePreprocessor3() {
        $p = new CFDBFilterParser;
        $p->setComparisonValuePreprocessor(new ChangeUserVar);
        $data = array('your-message' => 'admin',
                      'your-email' => 'mike@simpson-software-studio.com');

        $p->parse('your-email=$user_email');
        $this->assertTrue($p->evaluate($data));

        $p->parse('your-message=$user_login');
        $this->assertTrue($p->evaluate($data));

        $p->parse('your-message=$user_login&&your-email=$user_email');
        $this->assertTrue($p->evaluate($data));

        $p->parse('your-message=$user_login||your-email=$user_email');
        $this->assertTrue($p->evaluate($data));

        $p->parse('your-message=$user_login||your-email!=$user_email');
        $this->assertTrue($p->evaluate($data));

        $p->parse('your-message=$user_login&&your-email!=$user_email');
        $this->assertFalse($p->evaluate($data));

    }

    public function test_submit_time() {
        $p = new CFDBFilterParser;
        $p->parse('submit_time=1305930218.2452');
        $data = array('submit_time' => '1305930218.2452');
        $this->assertTrue($p->evaluate($data));

        $p = new CFDBFilterParser;
        $p->parse('submit_time=1305930218.2452');
        $data = array('submit_time' => '1305930218.9999');
        $this->assertFalse($p->evaluate($data));

        $p = new CFDBFilterParser;
        $p->parse('submit_time<1305930218.9999');
        $data = array('submit_time' => '1305930218.2452');
        $this->assertTrue($p->evaluate($data));

        $p = new CFDBFilterParser;
        $p->parse('submit_time<=1305930218.9999');
        $data = array('submit_time' => '1305930218.2452');
        $this->assertTrue($p->evaluate($data));


        $p = new CFDBFilterParser;
        $p->parse('submit_time>1305930218');
        $data = array('submit_time' => '1305930218.2452');
        $this->assertTrue($p->evaluate($data));


    }

    public function test_submit_time_with_relative() {
        date_default_timezone_set('America/New_York');
        $p = new CFDBFilterParser;
        $p->parse('submit_time<8 weeks ago');

        $nineWeeksAgo =  microtime(true) - (60 * 60 * 24 * 7 * 9);
        $data = array('submit_time' => $nineWeeksAgo);
        $this->assertTrue($p->evaluate($data));

        $twoWeeksAgo = microtime(true) - (60 * 60 * 24 * 7 * 2);
        $data = array('submit_time' => $twoWeeksAgo);
        $this->assertFalse($p->evaluate($data));
    }

    public function test_preg_split() {
    //$comparisonExpression = 'submit_time<=1305930218.2452';
    $comparisonExpression = 'submit_time&lt;=1305930218.2452';

         $p = new CFDBFilterParser;
         $p->setComparisonValuePreprocessor(new DereferenceShortcodeVars);
         $p->parse($comparisonExpression);
//         echo '<pre>'; print_r($p->tree); echo '</pre>';
         $this->assertEquals('<=', $p->tree[0][0][1]);

         $comparisonExpression = 'submit_time&gt;=1305930218.2452';
         $p->parse($comparisonExpression);
         $this->assertEquals('>=', $p->tree[0][0][1]);
    }

    public function test_evaluateLeftOpRightComparison_float() {
        $p = new CFDBFilterParser;
        $this->assertTrue($p->evaluateLeftOpRightComparison('1305589311.4503', '<', '1305589312.4503'));
        $this->assertFalse($p->evaluateLeftOpRightComparison('1305589311.4503', '<', '1305589310.4503'));

        $this->assertFalse($p->evaluateLeftOpRightComparison('1305589311.4503', '>', '13055893109999.4503'));
    }

    public function test_conversion_float() {
//        $i = (float)'1305589311.4503';
//        $j = (float)'Mike'; // becomes 0
//        echo "$i, $j";

        $k = '1305589311.4503';
        $this->assertTrue((float)$k == $k);

//        $m = 'Mike';
//        $this->assertFalse((float)$m == $m);


        $this->assertTrue(is_numeric('1305589311.4503'));
    }

    public function testQuarterHouseAND()
    {
        $filterText = 'Week Number?=52&&Year=2015';
        $filters = preg_split('/&&|\|\|/', $filterText, -1, PREG_SPLIT_NO_EMPTY);

        $this->assertEquals('Week Number?=52', $filters[0]);
        $this->assertEquals('Year=2015', $filters[1]);
        //print_r($filters);
    }

    public function testQuarterHouseAND_2()
    {
        $filterText = 'Week Number?=52&&Year=2015';
        $filters = explode('&&', $filterText);

        $this->assertEquals('Week Number?=52', $filters[0]);
        $this->assertEquals('Year=2015', $filters[1]);
        //print_r($filters);
    }

    public function testQuarterHouse()
    {

        $filterText = 'Week Number?=52&&Year=2015';
        $p = new CFDBFilterParser;
        $p->parse($filterText);

        print_r($p->getFilterTree());

        $data = array(
            'Week Number?' => '52',
            'Year' => '2015'
        );
        $this->assertTrue($p->evaluate($data));
    }

    public function testParseFunction_noParams1() {
        $filterText = 'funct()';
        $p = new CFDBFilterParser;
        $array = $p->parseFunction($filterText);
        $this->assertEquals('funct', $array[0]);
        $this->assertEquals(1, count($array));
    }

    public function testParseFunction_noParams2() {
        $filterText = 'funct( )';
        $p = new CFDBFilterParser;
        $array = $p->parseFunction($filterText);
        $this->assertEquals('funct', $array[0]);
        $this->assertEquals(' ', $array[1]);
        $this->assertEquals(2, count($array));
    }
    public function testParseFunction_noParams3() {
        $filterText = ' funct()';
        $p = new CFDBFilterParser;
        $array = $p->parseFunction($filterText);
        $this->assertEquals('funct', $array[0]);
        $this->assertEquals(1, count($array));
    }

    public function testParseFunction_noParams4() {
        $filterText = 'funct() ';
        $p = new CFDBFilterParser;
        $array = $p->parseFunction($filterText);
        $this->assertEquals('funct', $array[0]);
        $this->assertEquals(1, count($array));
    }

    public function testParseFunction_noParams5() {
        $filterText = ' funct( ) ';
        $p = new CFDBFilterParser;
        $array = $p->parseFunction($filterText);
        $this->assertEquals('funct', $array[0]);
        $this->assertEquals(' ', $array[1]);
        $this->assertEquals(2, count($array));
    }

    public function testParseFunction_1param() {
        $filterText = 'funct(hello)';
        $p = new CFDBFilterParser;
        $array = $p->parseFunction($filterText);
        $this->assertEquals('funct', $array[0]);
        $this->assertEquals('hello', $array[1]);
        $this->assertEquals(2, count($array));
    }

    public function testParseFunction_2param() {
        $filterText = 'funct(hello,there)';
        $p = new CFDBFilterParser;
        $array = $p->parseFunction($filterText);
        $this->assertEquals('funct', $array[0]);
        $this->assertEquals('hello', $array[1]);
        $this->assertEquals($array[2], 'there');
        $this->assertEquals(3, count($array));
    }

    public function testParseFunction_2param_spaces1() {
        $filterText = 'funct(hello, there)';
        $p = new CFDBFilterParser;
        $array = $p->parseFunction($filterText);
        $this->assertEquals('funct', $array[0]);
        $this->assertEquals('hello', $array[1]);
        $this->assertEquals(' there', $array[2]);
        $this->assertEquals(3, count($array));
    }

    public function testParseFunction_2param_spaces2() {
        $filterText = ' funct( hello , there )';
        $p = new CFDBFilterParser;
        $array = $p->parseFunction($filterText);
        $this->assertEquals('funct', $array[0]);
        $this->assertEquals(' hello ', $array[1]);
        $this->assertEquals(' there ', $array[2]);
        $this->assertEquals(3, count($array));
    }

    public function testParseFunction_3param() {
        $filterText = 'funct(hello,there,buddy)';
        $p = new CFDBFilterParser;
        $array = $p->parseFunction($filterText);
        $this->assertEquals('funct', $array[0]);
        $this->assertEquals('hello', $array[1]);
        $this->assertEquals('there', $array[2]);
        $this->assertEquals('buddy', $array[3]);
        $this->assertEquals(4, count($array));
    }

    public function testParseFunction_POST() {
        $filterText = 'funct($_POST(lname))';
        $p = new CFDBFilterParser;
        $array = $p->parseFunction($filterText);
        $this->assertEquals('funct', $array[0]);
        $this->assertEquals('$_POST(lname)', $array[1]);
        $this->assertEquals(2, count($array));
    }

    public function testParseFunction_POST2() {
        $filterText = 'funct($_POST(lname), $_GET(fname))';
        $p = new CFDBFilterParser;
        $array = $p->parseFunction($filterText);
        $this->assertEquals('funct', $array[0]);
        $this->assertEquals('$_POST(lname)', $array[1]);
        $this->assertEquals(' $_GET(fname)', $array[2]);
        $this->assertEquals(3, count($array));
    }

    public function testParseFunction_danglingParam() {
        $filterText = 'funct(hello,there,buddy,)';
        $p = new CFDBFilterParser;
        $array = $p->parseFunction($filterText);
        $this->assertEquals('funct', $array[0]);
        $this->assertEquals('hello', $array[1]);
        $this->assertEquals('there', $array[2]);
        $this->assertEquals('buddy', $array[3]);
        $this->assertEquals('', $array[4]);
        $this->assertEquals(5, count($array));
    }

    public function testParseFunction_danglingParam2() {
        $filterText = 'funct(hello,there,buddy, )';
        $p = new CFDBFilterParser;
        $array = $p->parseFunction($filterText);
        $this->assertEquals('funct', $array[0]);
        $this->assertEquals('hello', $array[1]);
        $this->assertEquals('there', $array[2]);
        $this->assertEquals('buddy', $array[3]);
        $this->assertEquals(' ', $array[4]);
        $this->assertEquals(5, count($array));
    }

    public function testParseFunction_danglingParam3() {
        $filterText = 'funct(,hello,there,buddy, )';
        $p = new CFDBFilterParser;
        $array = $p->parseFunction($filterText);
        $this->assertEquals('funct', $array[0]);
        $this->assertEquals('', $array[1]);
        $this->assertEquals('hello', $array[2]);
        $this->assertEquals('there', $array[3]);
        $this->assertEquals('buddy', $array[4]);
        $this->assertEquals(' ', $array[5]);
        $this->assertEquals(6, count($array));
    }

    public function testParseFunction_danglingParam4() {
        $filterText = 'funct(, hello,there,buddy, )';
        $p = new CFDBFilterParser;
        $array = $p->parseFunction($filterText);
        $this->assertEquals('funct', $array[0]);
        $this->assertEquals('', $array[1]);
        $this->assertEquals(' hello', $array[2]);
        $this->assertEquals('there', $array[3]);
        $this->assertEquals('buddy', $array[4]);
        $this->assertEquals(' ', $array[5]);
        $this->assertEquals(6, count($array));
    }

    public function testParseFunction_danglingParam5() {
        $filterText = 'funct(, hello, ,there,,buddy, )';
        $p = new CFDBFilterParser;
        $array = $p->parseFunction($filterText);
        $this->assertEquals('funct', $array[0]);
        $this->assertEquals('', $array[1]);
        $this->assertEquals(' hello', $array[2]);
        $this->assertEquals(' ', $array[3]);
        $this->assertEquals('there', $array[4]);
        $this->assertEquals('', $array[5]);
        $this->assertEquals('buddy', $array[6]);
        $this->assertEquals(' ', $array[7]);
        $this->assertEquals(8, count($array));
    }


    public function test_parse_function() {
        $p = new CFDBFilterParser;
        $p->parse('aaa=AAA&&yyy=strtoupper(zzz)');

        $this->assertEquals('aaa', $p->tree[0][0][0]);
        $this->assertEquals('=', $p->tree[0][0][1]);
        $this->assertEquals('AAA', $p->tree[0][0][2]);

        $this->assertEquals('yyy', $p->tree[0][1][0]);
        $this->assertEquals('=', $p->tree[0][1][1]);
        $this->assertEquals('strtoupper', $p->tree[0][1][2][0]);
        $this->assertEquals('zzz', $p->tree[0][1][2][1]);
    }

    public function test_evaluate_unknown_function() {
        $p = new CFDBFilterParser;
        $p->parse('aaa=AAA&&yyy=hello(zzz)');

        $this->assertEquals('aaa', $p->tree[0][0][0]);
        $this->assertEquals('=', $p->tree[0][0][1]);
        $this->assertEquals('AAA', $p->tree[0][0][2]);

        $this->assertEquals('yyy', $p->tree[0][1][0]);
        $this->assertEquals('=', $p->tree[0][1][1]);
        $this->assertEquals('hello(zzz)', $p->tree[0][1][2]);
    }

    public function test_parse_function_injected_boolean() {
        $p = new CFDBFilterParser;
        $p->parse('aaa=AAA&&strtoupper(zzz)');

        $this->assertEquals('aaa', $p->tree[0][0][0]);
        $this->assertEquals('=',   $p->tree[0][0][1]);
        $this->assertEquals('AAA', $p->tree[0][0][2]);

        $this->assertEquals('strtoupper', $p->tree[0][1][0][0]);
        $this->assertEquals('zzz',        $p->tree[0][1][0][1]);
        $this->assertEquals('==',         $p->tree[0][1][1]); // sets the operator
        $this->assertEquals(true,         $p->tree[0][1][2]);
    }

    public function test_parse_function_boolean_true() {
        $p = new CFDBFilterParser;
        $p->parse('aaa=AAA&&true=strtoupper(zzz)');

        $this->assertEquals('aaa', $p->tree[0][0][0]);
        $this->assertEquals('=', $p->tree[0][0][1]);
        $this->assertEquals('AAA', $p->tree[0][0][2]);

        $this->assertEquals(true, $p->tree[0][1][0]);
        $this->assertEquals('=', $p->tree[0][1][1]);
        $this->assertEquals('strtoupper', $p->tree[0][1][2][0]);
        $this->assertEquals('zzz', $p->tree[0][1][2][1]);
    }

    public function test_parse_function_boolean_false() {
        $p = new CFDBFilterParser;
        $p->parse('aaa=AAA&&false=strtoupper(zzz)');

        $this->assertEquals('aaa', $p->tree[0][0][0]);
        $this->assertEquals('=', $p->tree[0][0][1]);
        $this->assertEquals('AAA', $p->tree[0][0][2]);

        $this->assertEquals(false, $p->tree[0][1][0]);
        $this->assertEquals('=', $p->tree[0][1][1]);
        $this->assertEquals('strtoupper', $p->tree[0][1][2][0]);
        $this->assertEquals('zzz', $p->tree[0][1][2][1]);
    }

    public function test_parse_function_boolean_true2() {
        $p = new CFDBFilterParser;
        $p->parse('aaa=AAA&&strtoupper(zzz)=true');

        $this->assertEquals('aaa', $p->tree[0][0][0]);
        $this->assertEquals('=',   $p->tree[0][0][1]);
        $this->assertEquals('AAA', $p->tree[0][0][2]);

        $this->assertEquals('strtoupper', $p->tree[0][1][0][0]);
        $this->assertEquals('zzz',   $p->tree[0][1][0][1]);
        $this->assertEquals('=',     $p->tree[0][1][1]);
        $this->assertEquals(true,    $p->tree[0][1][2]);
    }

    public function test_parse_function_boolean_false2() {
        $p = new CFDBFilterParser;
        $p->parse('aaa=AAA&&strtoupper(zzz)=false');

        $this->assertEquals('aaa', $p->tree[0][0][0]);
        $this->assertEquals('=',   $p->tree[0][0][1]);
        $this->assertEquals('AAA', $p->tree[0][0][2]);

        $this->assertEquals('strtoupper', $p->tree[0][1][0][0]);
        $this->assertEquals('zzz',   $p->tree[0][1][0][1]);
        $this->assertEquals('=',     $p->tree[0][1][1]);
        $this->assertEquals(false,   $p->tree[0][1][2]);
    }

    public function test_evaluate_function_1() {
        $p = new CFDBFilterParser;
        $p->parse('fname=AAA&&strtoupper(lname)=ZZZ');
        $data = array('fname' => 'AAA');
        $this->assertFalse($p->evaluate($data));
    }

    public function test_evaluate_function_1_1() {
        $p = new CFDBFilterParser;
        $p->parse('fname=AAA&&strtoupper(zzz)=ZZZ');
        $data = array('fname' => 'AAA');
        $this->assertTrue($p->evaluate($data));
    }

    public function test_evaluate_function_1_2() {
        $p = new CFDBFilterParser;
        $p->parse('fname=AAA&&strtoupper(lname)=ZZZ');
        $data = array('fname' => 'AAA',
                      'lname' => 'zzz');
        $this->assertTrue($p->evaluate($data));
    }

    public function test_evaluate_function_2() {
        $p = new CFDBFilterParser;
        $p->parse('fname=AAA&&is_numeric(lname)');
        $data = array('fname' => 'AAA',
                      'lname' => '123');
        $this->assertTrue($p->evaluate($data));
    }

    public function test_evaluate_function_2_1() {
        $p = new CFDBFilterParser;
        $p->parse('fname=AAA&&true=is_numeric(lname)');
        $data = array('fname' => 'AAA',
                      'lname' => '123');
        $this->assertTrue($p->evaluate($data));
    }

    public function test_evaluate_function_2_2() {
        $p = new CFDBFilterParser;
        $p->parse('fname=AAA&&false=is_numeric(lname)');
        $data = array('fname' => 'AAA',
                      'lname' => 'abc');
        $this->assertTrue($p->evaluate($data));
    }

    public function test_evaluate_function_2_3() {
        $p = new CFDBFilterParser;
        $p->parse('fname=AAA&&is_numeric(lname)=true');
        $data = array('fname' => 'AAA',
                      'lname' => '123');
        $this->assertTrue($p->evaluate($data));
    }

    public function test_evaluate_function_2_3_1() {
        $p = new CFDBFilterParser;
        $p->parse('fname=AAA&&is_numeric(lname)=true');
        $data = array('fname' => 'AAA',
                      'lname' => 'abc');
        $this->assertFalse($p->evaluate($data));
    }

    public function test_evaluate_function_2_4() {
        $p = new CFDBFilterParser;
        $p->parse('fname=AAA&&is_numeric(lname)=false');
        $data = array('fname' => 'AAA',
                      'lname' => 'abc');
        $this->assertTrue($p->evaluate($data));
    }

    public function test_evaluate_function_2_4_1() {
        $p = new CFDBFilterParser;
        $p->parse('fname=AAA&&is_numeric(lname)=false');
        $data = array('fname' => 'AAA',
                      'lname' => '123');
        $this->assertFalse($p->evaluate($data));
    }

    public function test_login_name() {
        //Submitted Login = $user_login with no data value for Submitted Login and not logged in
        $p = new CFDBFilterParser;
        $p->setComparisonValuePreprocessor(new ChangeNameToValue('$user_login', 'msimpson'));

        $p->parse('Submitted Login=$user_login');
        $data = array('Submitted Login' => 'msimpson');
        $this->assertTrue($p->evaluate($data));
    }

    public function test_login_name2() {
        //Submitted Login = $user_login with no data value for Submitted Login and not logged in
        $p = new CFDBFilterParser;
        $p->setComparisonValuePreprocessor(new ChangeNameToValue('$user_login', 'msimpson'));

        $p->parse('Submitted Login=$user_login');
        $data = array('Submitted Login' => 'admin');
        $this->assertFalse($p->evaluate($data));
    }

    public function test_no_login() {
        //Submitted Login = $user_login with no data value for Submitted Login and not logged in
        $p = new CFDBFilterParser;
        $p->setComparisonValuePreprocessor(new ChangeNameToValue('$user_login', null));

        $p->parse('Submitted Login=$user_login');
        $data = array();
        $this->assertFalse($p->evaluate($data));
    }

    public function test_null_field() {
        $p = new CFDBFilterParser;
        $p->parse('field=null');
        $data = array();
        $this->assertTrue($p->evaluate($data));
    }

    public function test_constants_params() {
        $p = new CFDBFilterParser;
        $p->parse('str_pad(field,10,.,STR_PAD_BOTH)=..hello...');
        $data = array('field' =>'hello');
        $this->assertTrue($p->evaluate($data));
    }

    public function test_filterTrue() {
        $p = new CFDBFilterParser;
        $p->parse('filterTrue()');
        print_r($p->tree);
        $data = array('field' =>'hello');
        $this->assertTrue($p->evaluate($data));
    }

    public function test_filterTrueEqualsTrue() {
        $p = new CFDBFilterParser;
        $p->parse('filterTrue()=true');
        print_r($p->tree);
        $data = array('field' =>'hello');
        $this->assertTrue($p->evaluate($data));
    }

    public function test_filterTrueEqualsTrue2() {
        $p = new CFDBFilterParser;
        $p->parse('true=filterTrue()');
        print_r($p->tree);
        $data = array('field' =>'hello');
        $this->assertTrue($p->evaluate($data));
    }

    public function test_filterFalse() {
        $p = new CFDBFilterParser;
        $p->parse('filterFalse()');
        print_r($p->tree);
        $data = array('field' =>'hello');
        $this->assertFalse($p->evaluate($data));
    }

    public function test_filterFalseEqualFalse() {
        $p = new CFDBFilterParser;
        $p->parse('filterFalse()=false');
        print_r($p->tree);
        $data = array('field' =>'hello');
        $this->assertTrue($p->evaluate($data));
    }

    public function test_filterFalseEqualFalse2() {
        $p = new CFDBFilterParser;
        $p->parse('false=filterFalse()');
        print_r($p->tree);
        $data = array('field' =>'hello');
        $this->assertTrue($p->evaluate($data));
    }

    public function test_blank_values() {
        global $_POST;
        $_POST = array('aname' => 'Simpson', 'subject' => 'hello');
        $p = new CFDBFilterParser;
        $p->setComparisonValuePreprocessor(new DereferenceShortcodeVars());
        $p->parse('your-name=$_POST(aname)&&your-subject=$_POST(subject)');
        print_r($p->tree);

        $this->assertEquals('your-name', $p->tree[0][0][0]);
        $this->assertEquals('=', $p->tree[0][0][1]);
        $this->assertEquals('$_POST(aname)', $p->tree[0][0][2]);

        $this->assertEquals('your-subject', $p->tree[0][1][0]);
        $this->assertEquals('=', $p->tree[0][1][1]);
        $this->assertEquals('$_POST(subject)', $p->tree[0][1][2]);
    }

}

function filterTrue() {
    return true;
}
function filterFalse() {
    return false;
}

class ChangeNameToValue implements CFDBValueConverter {
    var $name;
    var $value;
    function __construct($name, $value) {
        $this->name = $name;
        $this->value = $value;
    }
    public function convert($name) {
        return $name == $this->name ? $this->value : $name;
    }
}


class ChangeUserVar implements CFDBValueConverter {
    public function convert($value) {
        switch ($value) {
            case '$user_login' :
                return 'admin';
            case '$user_email' :
                return 'mike@simpson-software-studio.com';
            default:
                return $value;
        }
    }
}



//$suite = new PHPUnit_Framework_TestSuite("CFDBFilterParserTest");
//PHPUnit_TextUI_TestRunner::run($suite);
