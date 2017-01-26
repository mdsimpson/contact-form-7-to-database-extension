<?php

include_once(dirname(dirname(__FILE__)) . '/CFDBTransformParser.php');

class CFDBTransformParserTest extends PHPUnit_Framework_TestCase {

    public function  test_parse_1_1() {
        $p = new CFDBTransformParser;
        $p->parse('last_name=funct');
        $e = $p->getExpressionTree();

        $this->assertEquals(1, count($e));
        $this->assertEquals("last_name", $e[0][0]);
        $this->assertEquals("=", $e[0][1]);
        $this->assertEquals("funct", $e[0][2]);
    }

    public function  test_parse_1_2() {
        $p = new CFDBTransformParser;
        $p->parse('last_name=funct()');
        $e = $p->getExpressionTree();

        $this->assertEquals(1, count($e));
        $this->assertEquals("last_name", $e[0][0]);
        $this->assertEquals("=", $e[0][1]);
        $this->assertEquals("funct", $e[0][2]);
    }


    // 1 arg

    public function  test_parse_2_1() {
        $p = new CFDBTransformParser;
        $p->parse('last_name=strtoupper(last_name)');
        $e = $p->getExpressionTree();

        $this->assertEquals(1, count($e));
        $this->assertEquals("last_name", $e[0][0]);
        $this->assertEquals("=", $e[0][1]);
        $this->assertEquals("strtoupper", $e[0][2]);
        $this->assertEquals("last_name", $e[0][3]);
    }

    // multiple args

    public function  test_parse_2_2() {
        $p = new CFDBTransformParser;
        $p->parse('name=funct(first_name,last_name)');
        $e = $p->getExpressionTree();

        $this->assertEquals(1, count($e));
        $this->assertEquals("name", $e[0][0]);
        $this->assertEquals("=", $e[0][1]);
        $this->assertEquals("funct", $e[0][2]);
        $this->assertEquals("first_name", $e[0][3]);
        $this->assertEquals("last_name", $e[0][4]);
    }

    public function  test_parse_2_3() {
        $p = new CFDBTransformParser;
        $p->parse('name=funct(first_name, last_name)');
        $e = $p->getExpressionTree();

        $this->assertEquals(1, count($e));
        $this->assertEquals("name", $e[0][0]);
        $this->assertEquals("=", $e[0][1]);
        $this->assertEquals("funct", $e[0][2]);
        $this->assertEquals("first_name", $e[0][3]);
        $this->assertEquals(" last_name", $e[0][4]);
    }

    public function  test_parse_2_4() {
        $p = new CFDBTransformParser;
        $p->parse('name=funct(first_name,   middle_name,      last_name)');
        $e = $p->getExpressionTree();

        $this->assertEquals(1, count($e));
        $this->assertEquals("name", $e[0][0]);
        $this->assertEquals("=", $e[0][1]);
        $this->assertEquals("funct", $e[0][2]);
        $this->assertEquals("first_name", $e[0][3]);
        $this->assertEquals("   middle_name", $e[0][4]);
        $this->assertEquals("      last_name", $e[0][5]);
    }

    // multiples
    public function  test_parse_3_1() {
        $p = new CFDBTransformParser;
        $p->parse('first_name=funct1&&last_name=funct2');
        $e = $p->getExpressionTree();

        $this->assertEquals(2, count($e));
        $this->assertEquals("first_name", $e[0][0]);
        $this->assertEquals("=", $e[0][1]);
        $this->assertEquals("funct1", $e[0][2]);

        $this->assertEquals("last_name", $e[1][0]);
        $this->assertEquals("=", $e[1][1]);
        $this->assertEquals("funct2", $e[1][2]);
    }

    public function  test_parse_3_2() {
        $p = new CFDBTransformParser;
        $p->parse('first_name=funct1()&&last_name=funct2()');
        $e = $p->getExpressionTree();

        $this->assertEquals(2, count($e));
        $this->assertEquals("first_name", $e[0][0]);
        $this->assertEquals("=", $e[0][1]);
        $this->assertEquals("funct1", $e[0][2]);

        $this->assertEquals("last_name", $e[1][0]);
        $this->assertEquals("=", $e[1][1]);
        $this->assertEquals("funct2", $e[1][2]);
    }

    public function  test_parse_4_1() {
        $p = new CFDBTransformParser;
        $p->parse('first_name=funct1(x_name)&&last_name=funct2()');
        $e = $p->getExpressionTree();

        $this->assertEquals(2, count($e));
        $this->assertEquals("first_name", $e[0][0]);
        $this->assertEquals("=", $e[0][1]);
        $this->assertEquals("funct1", $e[0][2]);
        $this->assertEquals("x_name", $e[0][3]);

        $this->assertEquals("last_name", $e[1][0]);
        $this->assertEquals("=", $e[1][1]);
        $this->assertEquals("funct2", $e[1][2]);
    }

    public function test_parse_4_2() {
        $p = new CFDBTransformParser;
        $p->parse('first_name=funct1()&&last_name=funct2(x_name)');
        $e = $p->getExpressionTree();

        $this->assertEquals(2, count($e));
        $this->assertEquals("first_name", $e[0][0]);
        $this->assertEquals("=", $e[0][1]);
        $this->assertEquals("funct1", $e[0][2]);

        $this->assertEquals("last_name", $e[1][0]);
        $this->assertEquals("=", $e[1][1]);
        $this->assertEquals("funct2", $e[1][2]);
        $this->assertEquals("x_name", $e[1][3]);
    }

    public function test_parse_4_3() {
        $p = new CFDBTransformParser;
        $p->parse('first_name=funct1(y_name)&&last_name=funct2(x_name)');
        $e = $p->getExpressionTree();

        $this->assertEquals(2, count($e));
        $this->assertEquals("first_name", $e[0][0]);
        $this->assertEquals("=", $e[0][1]);
        $this->assertEquals("funct1", $e[0][2]);
        $this->assertEquals("y_name", $e[0][3]);

        $this->assertEquals("last_name", $e[1][0]);
        $this->assertEquals("=", $e[1][1]);
        $this->assertEquals("funct2", $e[1][2]);
        $this->assertEquals("x_name", $e[1][3]);
    }

    public function test_parse_4_4() {
        $p = new CFDBTransformParser;
        $p->parse('first_name=funct1(y1_name,y2_name)&&last_name=funct2(x1_name,x2_name)');
        $e = $p->getExpressionTree();

        $this->assertEquals(2, count($e));
        $this->assertEquals("first_name", $e[0][0]);
        $this->assertEquals("=", $e[0][1]);
        $this->assertEquals("funct1", $e[0][2]);
        $this->assertEquals("y1_name", $e[0][3]);
        $this->assertEquals("y2_name", $e[0][4]);

        $this->assertEquals("last_name", $e[1][0]);
        $this->assertEquals("=", $e[1][1]);
        $this->assertEquals("funct2", $e[1][2]);
        $this->assertEquals("x1_name", $e[1][3]);
        $this->assertEquals("x2_name", $e[1][4]);
    }

    public function test_parse_4_5() {
        $p = new CFDBTransformParser;
        $p->parse('  first_name=funct1(y1_name,  y2_name) &&  last_name=funct2( x1_name,  x2_name)');
        $e = $p->getExpressionTree();

        $this->assertEquals(2, count($e));
        $this->assertEquals("first_name", $e[0][0]);
        $this->assertEquals("=", $e[0][1]);
        $this->assertEquals("funct1", $e[0][2]);
        $this->assertEquals("y1_name", $e[0][3]);
        $this->assertEquals("  y2_name", $e[0][4]);

        $this->assertEquals("last_name", $e[1][0]);
        $this->assertEquals("=", $e[1][1]);
        $this->assertEquals("funct2", $e[1][2]);
        $this->assertEquals(" x1_name", $e[1][3]);
        $this->assertEquals("  x2_name", $e[1][4]);
    }

    public function test_parse_5_1() {
        $p = new CFDBTransformParser;
        $p->parse('xxxx');
        $e = $p->getExpressionTree();
        $this->assertEquals(1, count($e));
        $this->assertEquals(1, count($e[0]));
        $this->assertEquals("xxxx", $e[0][0]);
    }

    public function test_parse_5_2() {
        $p = new CFDBTransformParser;
        $p->parse('xxxx&&yyyy');
        $e = $p->getExpressionTree();
        $this->assertEquals(2, count($e));

        $this->assertEquals(1, count($e[0]));
        $this->assertEquals("xxxx", $e[0][0]);

        $this->assertEquals(1, count($e[1]));
        $this->assertEquals("yyyy", $e[1][0]);
    }

    public function test_parse_5_3() {
        $p = new CFDBTransformParser;
        $p->parse('xxxx&&first_name=funct1(y1_name,  y2_name)&&yyy&&last_name=funct2( x1_name,  x2_name)');
        $e = $p->getExpressionTree();

        $this->assertEquals(4, count($e));

        $this->assertEquals(1, count($e[0]));
        $this->assertEquals("xxxx", $e[0][0]);

        $this->assertEquals("first_name", $e[1][0]);
        $this->assertEquals("=",          $e[1][1]);
        $this->assertEquals("funct1",     $e[1][2]);
        $this->assertEquals("y1_name",    $e[1][3]);
        $this->assertEquals("  y2_name",    $e[1][4]);

        $this->assertEquals(1,     count($e[2]));
        $this->assertEquals("yyy", $e[2][0]);

        $this->assertEquals("last_name", $e[3][0]);
        $this->assertEquals("=",         $e[3][1]);
        $this->assertEquals("funct2",    $e[3][2]);
        $this->assertEquals(" x1_name",   $e[3][3]);
        $this->assertEquals("  x2_name",   $e[3][4]);
    }

    public function test_parse_6() {
        $p = new CFDBTransformParser;
        $p->parse('');
        $e = $p->getExpressionTree();
        $this->assertEquals(0, count($e));
    }

    // Evaluation Tests

    // Test function that changes a field
    public function test_eval_function_assign_1() {
        $t = new CFDBTransformParser;
        $t->parse('fname=strtoupper(fname)');
        $t->setupTransforms();

        $data = array(
                array('fname' => 'mike', 'lname' => 'simpson'),
                array('fname' => 'oya', 'lname' => 'simpson')
        );
        $source = new ArrayDataIterator($data);
        $t->setDataSource($source);
        $iter = $t->getIterator();

        $iter->nextRow();
        $this->assertEquals('MIKE', $iter->row['fname']);
        $this->assertEquals('simpson', $iter->row['lname']);

        $iter->nextRow();
        $this->assertEquals('OYA', $iter->row['fname']);
        $this->assertEquals('simpson', $iter->row['lname']);
    }

    // Test function that takes in entire entry and modifies it
    public function test_eval_function_mutate_1() {
        $t = new CFDBTransformParser;
        $t->parse('upperall');
        $t->setupTransforms();

        $data = array(
                array('fname' => 'mike', 'lname' => 'simpson'),
                array('fname' => 'oya', 'lname' => 'turkel')
        );
        $source = new ArrayDataIterator($data);
        $t->setDataSource($source);
        $iter = $t->getIterator();

        $iter->nextRow();
        $this->assertEquals('MIKE', $iter->row['fname']);
        $this->assertEquals('SIMPSON', $iter->row['lname']);

        $iter->nextRow();
        $this->assertEquals('OYA', $iter->row['fname']);
        $this->assertEquals('TURKEL', $iter->row['lname']);
    }

    // test class
    public function test_eval_class_1() {
        $t = new CFDBTransformParser;
        $t->parse('SortByLname');
        $t->setupTransforms();

        $data = array(
                array('fname' => 'mike', 'lname' => 'BBBBB'),
                array('fname' => 'mike', 'lname' => 'CCCCC'),
                array('fname' => 'mike', 'lname' => 'AAAAA')
        );
        $source = new ArrayDataIterator($data);
        $t->setDataSource($source);
        $iter = $t->getIterator();

        $iter->nextRow();
        $this->assertEquals('mike', $iter->row['fname']);
        $this->assertEquals('AAAAA', $iter->row['lname']);

        $iter->nextRow();
        $this->assertEquals('mike', $iter->row['fname']);
        $this->assertEquals('BBBBB', $iter->row['lname']);

        $iter->nextRow();
        $this->assertEquals('mike', $iter->row['fname']);
        $this->assertEquals('CCCCC', $iter->row['lname']);
    }

    public function test_eval_class_2() {
        $t = new CFDBTransformParser;
        $t->parse('UpperAllClass');
        $t->setupTransforms();

        $data = array(
                array('fname' => 'mike', 'lname' => 'simpson'),
                array('fname' => 'oya', 'lname' => 'turkel')
        );
        $source = new ArrayDataIterator($data);
        $t->setDataSource($source);
        $iter = $t->getIterator();

        $iter->nextRow();
        $this->assertEquals('MIKE', $iter->row['fname']);
        $this->assertEquals('SIMPSON', $iter->row['lname']);

        $iter->nextRow();
        $this->assertEquals('OYA', $iter->row['fname']);
        $this->assertEquals('TURKEL', $iter->row['lname']);
    }

    // test class with constructor
    public function test_eval_class_with_ctr_1() {
        $t = new CFDBTransformParser;
        $t->parse('SortByField(lname)');
        $t->setupTransforms();

        $data = array(
                array('fname' => 'xxxx', 'lname' => 'BBBBB'),
                array('fname' => 'yyyy', 'lname' => 'CCCCC'),
                array('fname' => 'zzzz', 'lname' => 'AAAAA')
        );
        $source = new ArrayDataIterator($data);
        $t->setDataSource($source);
        $iter = $t->getIterator();

        $iter->nextRow();
        $this->assertEquals('zzzz', $iter->row['fname']);
        $this->assertEquals('AAAAA', $iter->row['lname']);

        $iter->nextRow();
        $this->assertEquals('xxxx', $iter->row['fname']);
        $this->assertEquals('BBBBB', $iter->row['lname']);

        $iter->nextRow();
        $this->assertEquals('yyyy', $iter->row['fname']);
        $this->assertEquals('CCCCC', $iter->row['lname']);
    }

    public function test_eval_class_with_ctr_2() {
        $t = new CFDBTransformParser;
        $t->parse('SortByField(fname)');
        $t->setupTransforms();

        $data = array(
                array('fname' => 'zzzz', 'lname' => 'AAAAA'),
                array('fname' => 'yyyy', 'lname' => 'CCCCC'),
                array('fname' => 'xxxx', 'lname' => 'BBBBB')
        );
        $source = new ArrayDataIterator($data);
        $t->setDataSource($source);
        $iter = $t->getIterator();

        $iter->nextRow();
        $this->assertEquals('xxxx', $iter->row['fname']);
        $this->assertEquals('BBBBB', $iter->row['lname']);

        $iter->nextRow();
        $this->assertEquals('yyyy', $iter->row['fname']);
        $this->assertEquals('CCCCC', $iter->row['lname']);

        $iter->nextRow();
        $this->assertEquals('zzzz', $iter->row['fname']);
        $this->assertEquals('AAAAA', $iter->row['lname']);

    }

    // test chain of transforms all assign functions
    public function test_eval_function_chain_assign_1() {
        $t = new CFDBTransformParser;
        $t->parse('fname=strtoupper(fname)&&lname=strtoupper(lname)');
        $t->setupTransforms();

        $data = array(
                array('fname' => 'mike', 'lname' => 'simpson'),
                array('fname' => 'oya', 'lname' => 'turkel')
        );
        $source = new ArrayDataIterator($data);
        $t->setDataSource($source);
        $iter = $t->getIterator();

        $iter->nextRow();
        $this->assertEquals('MIKE', $iter->row['fname']);
        $this->assertEquals('SIMPSON', $iter->row['lname']);

        $iter->nextRow();
        $this->assertEquals('OYA', $iter->row['fname']);
        $this->assertEquals('TURKEL', $iter->row['lname']);
    }

    // test chain of transforms all functions
    public function test_eval_function_chain_mutate_1() {
        $t = new CFDBTransformParser;
        $t->parse('upperall&&reverseall');
        $t->setupTransforms();

        $data = array(
                array('fname' => 'mike', 'lname' => 'simpson'),
                array('fname' => 'oya', 'lname' => 'turkel')
        );
        $source = new ArrayDataIterator($data);
        $t->setDataSource($source);
        $iter = $t->getIterator();

        $iter->nextRow();
        $this->assertEquals('EKIM', $iter->row['fname']);
        $this->assertEquals('NOSPMIS', $iter->row['lname']);

        $iter->nextRow();
        $this->assertEquals('AYO', $iter->row['fname']);
        $this->assertEquals('LEKRUT', $iter->row['lname']);
    }

    // test chain of transforms assign & mutate functions
    public function test_eval_function_chain_assign_and_mutate_1() {
        $t = new CFDBTransformParser;
        $t->parse('lname=strtoupper(lname)&&reverseall');
        $t->setupTransforms();

        $data = array(
                array('fname' => 'mike', 'lname' => 'simpson'),
                array('fname' => 'oya', 'lname' => 'turkel')
        );
        $source = new ArrayDataIterator($data);
        $t->setDataSource($source);
        $iter = $t->getIterator();

        $iter->nextRow();
        $this->assertEquals('ekim', $iter->row['fname']);
        $this->assertEquals('NOSPMIS', $iter->row['lname']);

        $iter->nextRow();
        $this->assertEquals('ayo', $iter->row['fname']);
        $this->assertEquals('LEKRUT', $iter->row['lname']);
    }

    public function test_eval_function_chain_assign_and_mutate_2() {
        $t = new CFDBTransformParser;
        $t->parse('reverseall&&lname=strtoupper(lname)');
        $t->setupTransforms();

        $data = array(
                array('fname' => 'mike', 'lname' => 'simpson'),
                array('fname' => 'oya', 'lname' => 'turkel')
        );
        $source = new ArrayDataIterator($data);
        $t->setDataSource($source);
        $iter = $t->getIterator();

        $iter->nextRow();
        $this->assertEquals('ekim', $iter->row['fname']);
        $this->assertEquals('NOSPMIS', $iter->row['lname']);

        $iter->nextRow();
        $this->assertEquals('ayo', $iter->row['fname']);
        $this->assertEquals('LEKRUT', $iter->row['lname']);
    }

    // test chain of transforms all classes
    public function test_eval_class_chain_1() {
        $t = new CFDBTransformParser;
        $t->parse('SortByField(fname)&&UpperAllClass');
        $t->setupTransforms();

        $data = array(
                array('fname' => 'zzzz', 'lname' => 'AAAAA'),
                array('fname' => 'yyyy', 'lname' => 'CCCCC'),
                array('fname' => 'xxxx', 'lname' => 'BBBBB')
        );
        $source = new ArrayDataIterator($data);
        $t->setDataSource($source);
        $iter = $t->getIterator();

        $iter->nextRow();
        $this->assertEquals('XXXX', $iter->row['fname']);
        $this->assertEquals('BBBBB', $iter->row['lname']);

        $iter->nextRow();
        $this->assertEquals('YYYY', $iter->row['fname']);
        $this->assertEquals('CCCCC', $iter->row['lname']);

        $iter->nextRow();
        $this->assertEquals('ZZZZ', $iter->row['fname']);
        $this->assertEquals('AAAAA', $iter->row['lname']);
    }

    public function test_eval_class_chain_2() {
        $t = new CFDBTransformParser;
        $t->parse('UpperAllClass&&SortByField(fname)');
        $t->setupTransforms();

        $data = array(
                array('fname' => 'zzzz', 'lname' => 'AAAAA'),
                array('fname' => 'yyyy', 'lname' => 'CCCCC'),
                array('fname' => 'xxxx', 'lname' => 'BBBBB')
        );
        $source = new ArrayDataIterator($data);
        $t->setDataSource($source);
        $iter = $t->getIterator();

        $iter->nextRow();
        $this->assertEquals('XXXX', $iter->row['fname']);
        $this->assertEquals('BBBBB', $iter->row['lname']);

        $iter->nextRow();
        $this->assertEquals('YYYY', $iter->row['fname']);
        $this->assertEquals('CCCCC', $iter->row['lname']);

        $iter->nextRow();
        $this->assertEquals('ZZZZ', $iter->row['fname']);
        $this->assertEquals('AAAAA', $iter->row['lname']);
    }

    // test chain of transforms mixed functions and classes
    public function test_eval_class_chain_mixed_1() {
        $t = new CFDBTransformParser;
        $t->parse('upperall&&SortByField(fname)');
        $t->setupTransforms();

        $data = array(
                array('fname' => 'zzzz', 'lname' => 'AAAAA'),
                array('fname' => 'yyyy', 'lname' => 'CCCCC'),
                array('fname' => 'xxxx', 'lname' => 'BBBBB')
        );
        $source = new ArrayDataIterator($data);
        $t->setDataSource($source);
        $iter = $t->getIterator();

        $iter->nextRow();
        $this->assertEquals('XXXX', $iter->row['fname']);
        $this->assertEquals('BBBBB', $iter->row['lname']);

        $iter->nextRow();
        $this->assertEquals('YYYY', $iter->row['fname']);
        $this->assertEquals('CCCCC', $iter->row['lname']);

        $iter->nextRow();
        $this->assertEquals('ZZZZ', $iter->row['fname']);
        $this->assertEquals('AAAAA', $iter->row['lname']);
    }

    public function test_eval_class_chain_mixed_2() {
        $t = new CFDBTransformParser;
        $t->parse('SortByField(fname)&&upperall');
        $t->setupTransforms();

        $data = array(
                array('fname' => 'zzzz', 'lname' => 'AAAAA'),
                array('fname' => 'yyyy', 'lname' => 'CCCCC'),
                array('fname' => 'xxxx', 'lname' => 'BBBBB')
        );
        $source = new ArrayDataIterator($data);
        $t->setDataSource($source);
        $iter = $t->getIterator();

        $iter->nextRow();
        $this->assertEquals('XXXX', $iter->row['fname']);
        $this->assertEquals('BBBBB', $iter->row['lname']);

        $iter->nextRow();
        $this->assertEquals('YYYY', $iter->row['fname']);
        $this->assertEquals('CCCCC', $iter->row['lname']);

        $iter->nextRow();
        $this->assertEquals('ZZZZ', $iter->row['fname']);
        $this->assertEquals('AAAAA', $iter->row['lname']);
    }


}

// Define dummy functions or parsing to validate as real functions
function funct() {}
function funct1() {}
function funct2() {}

class ArrayDataIterator extends CFDBDataIterator {

    var $data;

    function __construct(&$data) {
        $this->data =& $data;
    }

    public function nextRow() {
        $this->row = array_shift($this->data);
        return $this->row != null;
    }
}

function upperall($entry) {
    foreach ($entry as $key => $value) {
        $entry[$key] = strtoupper($value);
    }
    return $entry;
}

function reverseall($entry) {
    foreach ($entry as $key => $value) {
        $entry[$key] = strrev($value);
    }
    return $entry;
}


function compareLname($a, $b) {
    return strcmp($a["lname"], $b["lname"]);
}


//require_once('../CFDBTransform.php');
class SortByLname /* implements CFDBTransform */ {

    var $data = array();

    public function addEntry(&$entry) {
        $this->data[] = $entry;
    }

    public function getTransformedData() {
        usort($this->data, 'compareLname');
        return $this->data;
    }
}


class UpperAllClass {
    var $data = array();
    public function addEntry(&$entry) {
        $this->data[] = $entry;
    }
    public function getTransformedData() {
        $newData = array();
        foreach ($this->data as $entry) {
            $newData[] = upperall($entry);
        }
        return $newData;
    }

}