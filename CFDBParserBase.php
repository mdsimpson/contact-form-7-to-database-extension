<?php
/*
    "Contact Form to Database" Copyright (C) 2011-2014 Michael Simpson  (email : michael.d.simpson@gmail.com)

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

require_once('CFDBFunctionEvaluator.php');

abstract class CFDBParserBase {

    /**
     * @var CFDBFunctionEvaluator
     */
    var $functionEvaluator;

    /**
     * @var CFDBPermittedFunctions
     */
    var $permittedFilterFunctions;

    public function __construct() {
        $this->functionEvaluator = new CFDBFunctionEvaluator();
    }

    public abstract function parse($string);

    /**
     * @param  $converter CFDBValueConverter
     * @return void
     */
    public function setComparisonValuePreprocessor($converter) {
        $this->functionEvaluator->setCompValuePreprocessor($converter);
    }

    /**
     * @param $cFDBPermittedFilterFunctions CFDBPermittedFunctions
     * @return void
     */
    public function setPermittedFilterFunctions($cFDBPermittedFilterFunctions) {
        $this->permittedFilterFunctions = $cFDBPermittedFilterFunctions;
    }

    /**
     * To prevent a security hole, not all functions are permitted
     * @param $functionName string
     * @return bool
     */
    public function functionIsPermitted($functionName) {
        if ($this->permittedFilterFunctions) {
            return $this->permittedFilterFunctions->isFunctionPermitted($functionName);
        }
        return true;
    }

    public function parseValidFunction($filterString) {
        $parsed = $this->parseFunction($filterString);
        if (is_array($parsed)) {
            if (!is_callable($parsed[0]) || !$this->functionIsPermitted($parsed[0])) {
                return $filterString;
            }
        }
        return $parsed;
    }

    public function parseValidFunctionOrClassTransform($transString) {
        $parsed = $this->parseFunction($transString);

        if (!is_array($parsed)) {
            return $parsed;
        }

        if (class_exists($parsed[0])) {
            return $parsed;
        }

        $isFunction = is_callable($parsed[0]);
        $isPermitted = $this->functionIsPermitted($parsed[0]);
        if ($isFunction && $isPermitted) {
            return $parsed;
        }

        return $transString;
    }


    /**
     * @param $filterString string
     * @return string|array if a function like "funct(arg1, arg2, ...)" then returns array['funct', arg1, arg2, ...]
     * otherwise just returns the string passed in
     */
    public function parseFunction($filterString) {
        $matches = array();
        // Parse function name
        if (preg_match('/^(\w+)\((.*)\)$/', trim($filterString), $matches)) {
            $functionArray = array();
            $functionArray[] = $matches[1]; // function name
            // Parse function parameters
            if ($matches[2] != '') {
                $paramMatches = explode(',', $matches[2]);
                foreach ($paramMatches as $param) {
                    $param = stripslashes($param);
                    $param = $this->unSingleQuoteString($param);
                    $functionArray[] = $param;
                }
            }
            return $functionArray;
        }
        return $filterString;
    }

    /**
     * @param $string string
     * @return string
     */
    public function unSingleQuoteString($string) {
        $matches = array();
        if (preg_match("/'(.*)'/", $string, $matches)) {
            return $matches[1];
        }
        return $string;
    }

    /**
     * @param  $string
     * @return array
     */
    public function parseORs($string) {
        return preg_split('/\|\|/', $string, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * @param  $string
     * @return array
     */
    public function parseANDs($string) {
        // Deal with various && encoding problems
        $string = html_entity_decode($string);

        $retVal = preg_split('/&&/', $string, -1, PREG_SPLIT_NO_EMPTY);
        //echo "<pre>Parsed '$filterString' into " . print_r($retVal, true) . '</pre>';
        return $retVal;
    }

    public function setTimezone() {
        static $timezoneNotSet = true;
        if ($timezoneNotSet && function_exists('get_option')) {
            $tz = get_option('CF7DBPlugin_Timezone'); // see CFDBPlugin->setTimezone()
            if (!$tz) {
                $tz = get_option('timezone_string');
            }
            if ($tz) {
                date_default_timezone_set($tz);
            }
            $timezoneNotSet = false;
        }
    }

}