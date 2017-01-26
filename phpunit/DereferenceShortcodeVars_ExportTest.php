<?php

include_once(dirname(dirname(__FILE__)) . '/DereferenceShortcodeVars.php');
include_once(dirname(dirname(__FILE__)) . '/ExportBase.php');
include_once('WP_Mock_Functions.php');
include_once('SquashOutputUnitTest.php');

$_POST = null;

class DereferenceShortcodeVars_ExportTest extends SquashOutputUnitTest {

    public function setUp() {
        parent::setup();
        global $_POST;
        $_POST = array();
    }

    public function dataProviderReplace() {
        $data = array();
        $data[] = array('debug', 'true');
        $data[] = array('unbuffered', 'true');
        $data[] = array('class', 'myclass');
        $data[] = array('style', 'mystyle');
        $data[] = array('id', 'myid');
        $data[] = array('permissionmsg', 'false');
        $data[] = array('orderby', 'name');
        $data[] = array('orderby', 'name DESC');
        $data[] = array('limit', '10');
        $data[] = array('limit', '20,10');
        $data[] = array('tlimit', '10');
        $data[] = array('tlimit', '20,10');
        $data[] = array('header', 'true');
        $data[] = array('filter', 'name=Simpson');
        $data[] = array('tfilter', 'name=Simpson');
        $data[] = array('search', 'Simpson');
        $data[] = array('tsearch', 'Simpson');
        $data[] = array('trans', 'name=Simpson');
        return $data;
    }

    /**
     * @dataProvider dataProviderReplace
     */
    public function test_general_option($name, $value) {
        $options = array();
        $exp = new ExportBase;
        $exp->setOptions($options);

        $options[$name] = '$_POST(' .$name .')';
        $_POST[$name] = $value;
        $exp->setOptions($options);
        $exp->setCommonOptions(true);
        $this->assertEquals($value, $exp->options[$name], "$name=$value");
    }

    public function test_show() {
        $options = array();
        $exp = new ExportBase;
        $exp->setOptions($options);

        $options['show'] = 's1,s2,s3,$_POST(s),s6';
        $_POST['s'] = 's4,s5';
        $exp->setOptions($options);
        $exp->setCommonOptions(true);
        $this->assertEquals(explode(',', 's1,s2,s3,s4,s5,s6'), $exp->showColumns);
    }

    public function test_hide() {
        $options = array();
        $exp = new ExportBase;
        $exp->setOptions($options);

        $options['hide'] = 'h1,h2,h3,$_POST(h),h6';
        $_POST['h'] = 'h4,h5';
        $exp->setOptions($options);
        $exp->setCommonOptions(true);
        $this->assertEquals(explode(',', 'h1,h2,h3,h4,h5,h6'), $exp->hideColumns);
    }

    public function test_headers() {
        $options = array();
        $exp = new ExportBase;
        $exp->setOptions($options);

        $options['headers'] = 'h1=$_POST(h1),h2=$_POST(h2),h3=H_3';
        $_POST['h1'] = 'H_1';
        $_POST['h2'] = 'H_2';
        $exp->setOptions($options);
        $exp->setCommonOptions(true);
        $this->assertEquals(
                array(
                        'h1' => 'H_1',
                        'h2' => 'H_2',
                        'h3' => 'H_3',
                ),
                $exp->headers);
    }


    public function dataProviderNoReplace() {
        $data = array();
        $data[] = array('role', 'Anyone');
        $data[] = array('fromshortcode', 'name=Simpson');
        return $data;
    }

    /**
     * @dataProvider dataProviderNoReplace
     */
    public function test_general_option_no_replace($name, $value) {
        $options = array();
        $exp = new ExportBase;

        $options[$name] = '$_POST(' .$name .')';
        $_POST[$name] = $value;
        $exp->setOptions($options);
        $exp->setCommonOptions(true);
        // No replacement expected
        $this->assertEquals('$_POST(' .$name .')', $exp->options[$name], "$name=$value");
    }

    public function test_post_vars() {
        $options = array();
        $exp = new ExportBase;

        $options['debug'] = 'true';
        $options['filter'] = 'your-name=$_POST(aname)&&your-subject=$_POST(subject)';
        $exp->setOptions($options);
        $exp->setCommonOptions(true);

        print_r($exp->rowFilter->tree);
        $this->assertEquals('your-name', $exp->rowFilter->tree[0][0][0]);
        $this->assertEquals('=', $exp->rowFilter->tree[0][0][1]);
        $this->assertEquals('', $exp->rowFilter->tree[0][0][2]);

        $this->assertEquals('your-subject', $exp->rowFilter->tree[0][1][0]);
        $this->assertEquals('=', $exp->rowFilter->tree[0][1][1]);
        $this->assertEquals('', $exp->rowFilter->tree[0][1][2]);
    }

}
