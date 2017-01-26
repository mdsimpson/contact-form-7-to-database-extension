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

/**
 * Class TotalField a Transform used to add a total row to the end of the data set
 * with totals for specified fields.
 */
class TotalField implements CFDBTransform {

    /**
     * @var array
     */
    var $rows = array();

    /**
     * @var array
     */
    var $fields = array();

    /**
     * @var array
     */
    var $totals;

    /**
     * @var bool
     */
    var $finished = false;

    function __construct() {
        $this->fields = func_get_args();
        foreach ($this->fields as $field) {
            $this->totals[$field] = 0.0;
        }
    }

    /**
     * @param $entry array associative array of a single for entry
     * @return void
     */
    public function addEntry(&$entry) {
        $this->rows[] = $entry;
        foreach ($this->fields as $field) {
            if (isset($entry[$field])) {
                $this->totals[$field] += floatval($entry[$field]);
            }
        }
    }

    /**
     * Call this when done adding entries. Apply transform across all entered data,
     * then return the entire set. The returned set may be entirely different data than
     * what was input (e.g. statistics)
     * @return array of associative of array of data.
     */
    public function getTransformedData() {
        if (!$this->finished) {
            $this->rows[] = $this->totals;
            $this->finished = true;
        }
        return $this->rows;
    }
}