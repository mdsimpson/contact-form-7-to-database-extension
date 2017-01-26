<?php

/*
    "Contact Form to Database" Copyright (C) 2011-2016 Michael Simpson  (email : michael.d.simpson@gmail.com)

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

require_once('BaseTransform.php');

/**
 * Transform to add a column that numbers the rows
 */
class AddRowNumberField extends BaseTransform {

    var $fieldName;
    var $start;

    function __construct($fieldName = '#', $start = 1) {
        $this->fieldName = $fieldName;
        $this->start = $start;
    }

    public function getTransformedData() {
        $idx = $this->start;
        foreach ($this->data as &$entry) {
            $entry[$this->fieldName] = $idx++;
        }
        return $this->data;
    }

}
