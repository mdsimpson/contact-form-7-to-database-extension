<?php
/*
    "Contact Form to Database" Copyright (C) 2011-2012 Michael Simpson  (email : michael.d.simpson@gmail.com)

    This file is part of Contact Form to Database.

    Contact Form to Database is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Contact Form to Database is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database.
    If not, see <http://www.gnu.org/licenses/>.
*/

require_once('CFDBTransform.php');

abstract class HistogramTransform implements CFDBTransform {

    /**
     * @var string field holding the value
     */
    var $valueField;

    /**
     * @var string field to group by
     */
    var $groupByField;

    /**
     * @var array of name => value
     */
    var $values = array();

    function __construct($valueField, $groupByField) {
        $this->valueField = $valueField;
        $this->groupByField = $groupByField;
    }

    // https://bugs.php.net/bug.php?id=43200
    // abstract method also defined interface is an error in PHP 5.0.0 - 5.3.8
    //abstract public function addEntry(&$entry);

    public function getTransformedData() {
        $data = array();
        foreach ($this->values as $name => $value) {
            if (empty($this->groupByField)) {
                $data[] = array($this->valueField => $value);
            } else {
                $data[] = array($this->groupByField => $name, $this->valueField => $value);
            }
        }
        return $data;
    }


}