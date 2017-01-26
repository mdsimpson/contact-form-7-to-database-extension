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

require_once('SortTransform.php');


class SortByMultiField extends SortTransform {

    var $fieldName1;
    var $fieldName2;
    var $fieldName3;

    function __construct($fieldName1, $fieldName2 = null, $fieldName3 = null) {
        $this->fieldName1 = $fieldName1;
        $this->fieldName2 = $fieldName2;
        $this->fieldName3 = $fieldName3;
    }

    public function sort($a, $b) {
        $result = strcmp($a[$this->fieldName1], $b[$this->fieldName1]);
        if ($result == 0 && $this->fieldName2) {
            $result = strcmp($a[$this->fieldName2], $b[$this->fieldName2]);
            if ($result == 0 && $this->fieldName3) {
                $result = strcmp($a[$this->fieldName3], $b[$this->fieldName3]);
            }
        }
        return $result;
    }

}
