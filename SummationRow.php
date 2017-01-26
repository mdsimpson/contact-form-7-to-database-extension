<?php

/*
    "Contact Form to Database" Copyright (C) 2011-2015 Michael Simpson  (email : michael.d.simpson@gmail.com)

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

class SummationRow {

    var $data = array();
    var $fieldsToSum = array();
    var $hardCodedFields = array();
    var $fieldList = array();
    var $sumRow = array();
    var $isSumRowAppended = false;

    /**
     * Pass in names of fields to sum
     */
    function __construct() {
        $args = func_get_args();
        foreach ($args as $arg) {
            $name_value = explode(':', $arg, 2);
            if (count($name_value) == 2) {
                $this->hardCodedFields[$name_value[0]] = $name_value[1];
            } else {
                $this->fieldsToSum[] = $arg;
            }
        }
    }

    public function addEntry(&$entry) {
        // List of all the fields in a row
        if (empty($fieldList)) {
            $this->fieldList = array_keys($entry);
        }
        // Compute sum as we go
        foreach ($this->fieldsToSum as $field) {
            if (isset($entry[$field])) {
                // First value seen
                if (!isset($this->sumRow[$field])) {
                    $this->sumRow[$field] = $entry[$field];
                } else {
                    $this->sumRow[$field] += $entry[$field];
                }
            }
        }
        $this->data[] = $entry;
    }

    public function getTransformedData() {
        if (!$this->isSumRowAppended) {
            if (!empty($this->data)) {
                foreach ($this->fieldList as $field) {
                    if (isset($this->hardCodedFields[$field])) {
                        // Hardcoded values
                        $this->sumRow[$field] = $this->hardCodedFields[$field];
                    } elseif (!isset($this->sumRow[$field])) {
                        // Non-sum values
                        $this->sumRow[$field] = '';
                    }
                }
                $this->data[] = $this->sumRow;
            }
            $this->isSumRowAppended = true;
        }
        return $this->data;
    }

}