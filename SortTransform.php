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

require_once('BaseTransform.php');

/**
 * Subclass this and implement the "sort" function to create
 * a sort transform using your own criteria.
 */
abstract class SortTransform extends BaseTransform {

    public function getTransformedData() {
        usort($this->data, array($this, 'sort'));
        return $this->data;
    }

    /**
     * @param $a array: associative array of 1 form entry
     * @param $b array: associative array of 1 form entry
     * @return int -1 if a>b, 0 if a==b, 1 if a<b
     */
    public abstract function sort($a, $b);

} 