<?php

include_once(dirname(dirname(__FILE__)) . '/CFDBIntegrationCalderaForms.php');

/**
 * mock WP function
 * @return string
 */
function get_home_path() {
    return '/var/www/htdocs/';
}

/**
 * Class Caldera_Forms
 * Mock class for testing
 */
class Caldera_Forms {

    static function get_field_data($field_id, $form, $entry_id) {

        $fields = array(
                'fld_9798523'  => unserialize('s:5:"click";'),
                'fld_2233256'  => unserialize('a:2:{s:10:"opt1987829";s:4:"good";s:10:"opt1198663";s:3:"bad";}'),
                'fld_8640490'  => unserialize('s:4:"blue";'),
                'fld_6263042'  => unserialize('s:9:"hi@me.com";'),
                'fld_4388834'  => unserialize('s:53:"http://example.com/wp-content/uploads/2016/09/CCE.txt";'),
                'fld_6277358'  => unserialize('s:15:"my hidden value";'),
                'fld_5202649'  => unserialize('s:17:"here is some text";'),
                'fld_386033'  => unserialize('s:13:"(123)456-7890";'),
                'fld_1562186'  => unserialize('s:3:"far";'),
                'fld_6334953'  => unserialize('s:2:"BC";'),
                'fld_2875491'  => unserialize('s:9:"line text";'),
                'fld_9932413'  => unserialize('s:7:"#612C2C";'),
                'fld_5319422'  => unserialize('s:2:"36";'),
        );

        return $fields[$field_id];
    }
}

class CFDBIntegrationCalderaFormsTest extends PHPUnit_Framework_TestCase {

    public function testSubmission() {

        $form_ser = file_get_contents('CFDBIntegrationCalderaFormsTest/form.dat');
        $form = unserialize($form_ser);

        $caldera = new CFDBIntegrationCalderaForms(null);
        $data = $caldera->convertData($form, 1);

        $this->assertEquals('Caldera Form 1', $data->title);
        $this->assertEquals('click', $data->posted_data['mybutton']);
        $this->assertEquals('good,bad', $data->posted_data['mycheckbox']);
        $this->assertEquals('blue', $data->posted_data['mydropdown']);
        $this->assertEquals('hi@me.com', $data->posted_data['email']);
        $this->assertEquals('my hidden value', $data->posted_data['myhidden']);
        $this->assertEquals("here is some text", $data->posted_data['text']);
        $this->assertEquals('(123)456-7890', $data->posted_data['phone']);
        $this->assertEquals('far', $data->posted_data['howfar']);
        $this->assertEquals('BC', $data->posted_data['state']);
        $this->assertEquals('line text', $data->posted_data['line']);
        $this->assertEquals('#612C2C', $data->posted_data['color']);
        $this->assertEquals('36', $data->posted_data['range']);

        $this->assertEquals('CCE.txt', $data->posted_data['file']);
        $this->assertEquals('/var/www/htdocs//wp-content/uploads/2016/09/CCE.txt', $data->uploaded_files['file']);

    }

    public function test_getUrlWithoutSchemeHostAndPort_1() {
        $caldera = new CFDBIntegrationCalderaForms(null);
        $this->assertEquals('/wp-content/uploads/2015/05/Screen-Shot.png',
                $caldera->getUrlWithoutSchemeHostAndPort('http://www.mysite.com/wp-content/uploads/2015/05/Screen-Shot.png'));
    }

    public function test_getUrlWithoutSchemeHostAndPort_2() {
        $caldera = new CFDBIntegrationCalderaForms(null);
        $this->assertEquals('/wp-content/uploads/2015/05/Screen-Shot.png',
                $caldera->getUrlWithoutSchemeHostAndPort('https://www.mysite.com/wp-content/uploads/2015/05/Screen-Shot.png'));
    }

    public function test_getUrlWithoutSchemeHostAndPort_3() {
        $caldera = new CFDBIntegrationCalderaForms(null);
        $this->assertEquals('/wp-content/uploads/2015/05/Screen-Shot.png',
                $caldera->getUrlWithoutSchemeHostAndPort('https://www.mysite.com:8080/wp-content/uploads/2015/05/Screen-Shot.png'));
    }

}