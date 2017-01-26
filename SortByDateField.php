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

class SortByDateField extends SortTransform {

    var $fieldName;
    var $dateFormat;
    var $ascDesc;

    function __construct($fieldName, $ascDesc = 'ASC', $dateFormat = null) {
        $this->ascDesc = strtoupper($ascDesc);
        $this->dateFormat = $dateFormat;
        $this->fieldName = $fieldName;
    }


    public function sort($a, $b) {
        $aTimeString = isset($a[$this->fieldName]) ? $a[$this->fieldName] : null;
        $bTimeString = isset($b[$this->fieldName]) ? $b[$this->fieldName] : null;

        if ($this->dateFormat == null) {
            $aTime = strtotime($aTimeString);
            $bTime = strtotime($bTimeString);
        } else {
            $aTime = $this->parseToTimeStamp($this->dateFormat, $aTimeString);
            $bTime = $this->parseToTimeStamp($this->dateFormat, $bTimeString);
        }

        $sortVal = 0;
        // Unset time come before set ones
        if (!$aTime && $bTime) {
            $sortVal = 1;
        } else if ($aTime && !$bTime) {
            $sortVal = -1;
        }

        if ($aTime < $bTime) $sortVal = -1;
        if ($aTime > $bTime) $sortVal = 1;

        if ($this->ascDesc == 'DESC') {
            $sortVal *= -1;
        }
        return $sortVal;
    }

    /**
     * @param $format string date format
     * @param $dateString
     * @return int|null timestamp or null if can't parse
     */
    public function parseToTimeStamp($format, $dateString) {
        if ($dateString === null) {
            return null;
        }
        // Requires PHP >= 5.3.0
        $t = date_parse_from_format($format, $dateString);

        if (isset($t['hour']) &&
                isset($t['minute']) &&
                isset($t['second']) &&
                isset($t['month']) &&
                isset($t['day']) &&
                isset($t['year'])
        ) {
            return mktime(
                    $t['hour'],
                    $t['minute'],
                    $t['second'],
                    $t['month'],
                    $t['day'],
                    $t['year']);
        } else {
            return null;
        }
    }
}