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
 * Since PHP has a built-in concatenation operator, there is no function to do concatenation.
 * But for filters and transforms, we want to express a function for concatenation.
 */
function concat() { // concat(...) // splat operator in PHP 5.3
    $string = '';
    foreach (func_get_args() as $arg) {
        $string .= $arg;
    }
    return $string;
}

/**
 * Sum all input parameters
 * @return float|int
 */
function sum() { // concat(...) // splat operator in PHP 5.3
    $sum = 0.0;
    foreach (func_get_args() as $arg) {
        $sum += floatval($arg);
    }
    return $sum;
}

/**
 * Subtract all input values from the first input value
 * @return float
 */
function diff() {
    $diff = 0.0;
    $first = true;
    foreach (func_get_args() as $arg) {
        if ($first) {
            $diff = floatval($arg);
            $first = false;
        } else {
            $diff -= floatval($arg);
        }
    }
    return $diff;
}

/**
 * Sum all input parameters
 * @return float|int
 */
function multiply() { // concat(...) // splat operator in PHP 5.3
    $product = '';
    $first = true;
    foreach (func_get_args() as $arg) {
        if (is_numeric($arg)) {
            if ($first) {
                $product = floatval($arg);
                $first = false;
            } else {
                $product *= floatval($arg);
            }
        }
    }
    return $product;
}

function cfdb_date_diff($start, $end) {
    $start_time = strtotime($start);
    $end_time = strtotime($end);
    return $end_time - $start_time;
}

/**
 * @param $start
 * @param $end
 * @param $format String format: http://php.net/manual/en/dateinterval.format.php
 * @return string
 */
function cfdb_duration($start, $end, $format) {
    $datetime1 = new DateTime($start);
    $datetime2 = new DateTime($end);
    $interval = $datetime1->diff($datetime2);
    return $interval->format($format);
}


