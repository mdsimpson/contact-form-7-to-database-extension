<?php

include_once(dirname(dirname(__FILE__)) . '/NaturalSortByMultiField.php');

class NaturalSortByMultiFieldTest extends PHPUnit_Framework_TestCase {

    public function testSort4Deep() {

        $sort = new NaturalSortByMultiField('country', 'city', 'start', 'name');

        $this->assertEquals(-1,
                $sort->sort(
                        array('country' => 'Germany',
                                'city' => 'Berlin',
                                'start' => '11:00',
                                'name' => 'school 1'),
                        array('country' => 'Germany',
                                'city' => 'Berlin',
                                'start' => '11:00',
                                'name' => 'school 2')));
        $this->assertEquals(1,
                $sort->sort(
                        array('country' => 'Germany',
                                'city' => 'Berlin',
                                'start' => '11:00',
                                'name' => 'school 5'),
                        array('country' => 'Germany',
                                'city' => 'Berlin',
                                'start' => '11:00',
                                'name' => 'school 2')));

        $this->assertEquals(0,
                $sort->sort(
                        array('country' => 'Germany',
                                'city' => 'Berlin',
                                'start' => '11:00',
                                'name' => 'school 5'),
                        array('country' => 'Germany',
                                'city' => 'Berlin',
                                'start' => '11:00',
                                'name' => 'school 5')));

        $this->assertEquals(-1,
                $sort->sort(
                        array('country' => 'Germany',
                                'city' => 'Berlin',
                                'start' => '11:00',
                                'name' => 'school 09'),
                        array('country' => 'Germany',
                                'city' => 'Berlin',
                                'start' => '11:00',
                                'name' => 'school 5')));

        $this->assertEquals(1,
                $sort->sort(
                        array('country' => 'Germany',
                                'city' => 'Munich',
                                'start' => '11:00',
                                'name' => 'school 1'),
                        array('country' => 'Germany',
                                'city' => 'Berlin',
                                'start' => '11:00',
                                'name' => 'school 2')));

    }
}