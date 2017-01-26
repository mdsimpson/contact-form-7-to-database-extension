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

class AverageField implements CFDBTransform {

    /**
     * @var string field holding the value
     */
    var $valueField;

    /**
     * @var string field to group by
     */
    var $groupByField;

    /**
     * @var array of $groupByField => sum
     */
    var $sums = array();

    /**
     * @var array of $groupByField => count
     */
    var $counts = array();

    function __construct($valueField, $groupByField = null) {
        $this->valueField = $valueField;
        $this->groupByField = $groupByField;
    }

    public function addEntry(&$entry) {
        if (array_key_exists($this->valueField, $entry) && is_numeric($entry[$this->valueField])) {
            $value = $entry[$this->valueField];
            $groupByName = empty($this->groupByField) ? $this->valueField : $entry[$this->groupByField];

            if ($value !== null && $value !== '') {
                if (!array_key_exists($groupByName, $this->sums)) {
                    $this->sums[$groupByName] = $value;
                    $this->counts[$groupByName] = 1;
                } else {
                    $this->sums[$groupByName] += $value;
                    $this->counts[$groupByName]++;
                }
            }
        }
    }

    public function getTransformedData() {
        $data = array();
        foreach (array_keys($this->sums) as $name) {
            $average = $this->sums[$name] / $this->counts[$name];
            if (empty($this->groupByField)) {
                $data[] = array($this->valueField => $average);
            } else {
                $data[] = array($this->groupByField => $name, $this->valueField => $average);
            }
        }
        return $data;
    }

}