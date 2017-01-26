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

require_once('HistogramTransform.php');

class CountInField extends HistogramTransform {

    var $delimiter = ',';

    function __construct($valueField, $groupByField = null, $delimiter = null) {
        parent::__construct($valueField, $groupByField);
        if ($delimiter) {
            $this->delimiter = $delimiter;
        }
    }

    public function addEntry(&$entry) {
        if (array_key_exists($this->valueField, $entry)) {
            $value = $entry[$this->valueField];
            $valueList = explode($this->delimiter, $value);
            foreach ($valueList as $aValue) {
                $groupByName = $aValue;

                if ($value !== null && $value !== '') {
                    if (array_key_exists($groupByName, $this->values)) {
                        $this->values[$groupByName]++;
                    } else {
                        $this->values[$groupByName] = 1;
                    }
                }
            }
        }
    }

    public function getTransformedData() {
        $data = array();
        foreach ($this->values as $name => $value) {
            if (empty($this->groupByField)) {
                $data[] = array($this->valueField => $value);
            } else {
                $data[] = array($this->valueField => $name, $this->groupByField => $value);
            }
        }
        return $data;
    }


}