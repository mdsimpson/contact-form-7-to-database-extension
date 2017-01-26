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

/**
 * @singleton
 */
class CFDBPermittedFunctions {

    var $permitAllFunctions = false;

    var $permittedFunctions;

    static $defaultPermitted = array(

        // PHP functions
            'addcslashes',
            'addslashes',
            'chop',
            'chr',
            'count_chars',
            'localeconv',
            'ltrim',
            'md5',
            'money_format',
            'nl2br',
            'number_format',
            'rtrim',
            'sha1',
            'str_ireplace',
            'str_pad',
            'str_repeat',
            'str_replace',
            'str_shuffle',
            'str_word_count',
            'strcasecmp',
            'strchr',
            'strcmp',
            'strcoll',
            'strcspn',
            'strip_tags',
            'stripcslashes',
            'stripos',
            'strlen',
            'strnatcasecmp',
            'strnatcmp',
            'strncasecmp',
            'strncmp',
            'strpbrk',
            'strpos',
            'strspn',
            'strrev',
            'strstr',
            'strtok',
            'strtolower',
            'strtoupper',
            'strtr',
            'substr',
            'substr_compare',
            'substr_count',
            'substr_replace',
            'trim',
            'ucfirst',
            'ucwords',
            'wordwrap',
            'date',
            'microtime',
            'strtotime',
            'idate',
            'gmstrftime',
            'mktime',
            'strftime',
            'time',
            'intval',
            'boolval',
            'floatval',
            'strval',
            'bcmul',
            'bcdiv',
            'abs',
            'acos',
            'acoh',
            'asin',
            'asinh',
            'atan2',
            'atan',
            'atanh',
            'base_convert',
            'bindec',
            'ceil',
            'cos',
            'cosh',
            'decbin',
            'dechex',
            'decoct',
            'deg2rad',
            'exp',
            'expm1',
            'floor',
            'fmod',
            'getrandmax',
            'hexdec',
            'hypot',
            'intdiv',
            'is_finite',
            'is_infinite',
            'is_nan',
            'lcg_value',
            'log10',
            'log1p',
            'log',
            'max',
            'min',
            'mb_strtolower',
            'mb_check_encoding',
            'mb_convert_case',
            'mb_convert_encoding',
            'mb_convert_kana',
            'mb_decode_numericentity',
            'mb_detect_encoding',
            'mb_detect_order',
            'mb_encode_numericentity',
            'mb_ereg_match',
            'mb_ereg_replace',
            'mb_ereg_search_getpos',
            'mb_ereg_search_pos',
            'mb_ereg_search',
            'mb_ereg',
            'mb_eregi_replace',
            'mb_eregi',
            'mb_internal_encoding',
            'mb_language',
            'mb_preferred_mime_name',
            'mb_regex_encoding',
            'mb_regex_set_options',
            'mb_strcut',
            'mb_strimwidth',
            'mb_stripos',
            'mb_stristr',
            'mb_strlen',
            'mb_strpos',
            'mb_strrchr',
            'mb_strrichr',
            'mb_strripos',
            'mb_strstr',
            'mb_strtolower',
            'mb_strtoupper',
            'mb_strwidth',
            'mb_substr_count',
            'mb_substr',
            'mt_getrandmax',
            'mt_rand',
            'mt_srand',
            'octdec',
            'pi',
            'pow',
            'rad2deg',
            'rand',
            'round',
            'sin',
            'sqrt',
            'srand',
            'tan',
            'tanh',


        // WordPress-defined functions
            'human_time_diff',
            'current_time',

        // CFDB-defined functions
            'concat',
            'sum',
            'diff',
            'multiply',
            'cfdb_date_diff',
            'cfdb_duration'

    );


    public function init() {
        $this->permittedFunctions = CFDBPermittedFunctions::$defaultPermitted;
    }

    public static function getInstance() {
        static $inst = null;
        if ($inst === null) {
            $inst = new CFDBPermittedFunctions();
            $inst->init();
        }
        return $inst;
    }

    public function setPermitAllFunctions($trueOrFalse) {
        $this->permitAllFunctions = $trueOrFalse;
    }

    public function isFunctionPermitted($functionName) {
        if ($this->permitAllFunctions === true) {
            return true;
        } else {
            return in_array($functionName, $this->permittedFunctions);
        }
    }

    public function addPermittedFunction($functionName) {
        if ($functionName && !in_array($functionName, $this->permittedFunctions)) {
            $this->permittedFunctions[] = $functionName;
        }
    }


}

/**
 * A function wrapper to register function names in a CFDBPermittedFunctions singleton
 * @param $function_name
 */
function cfdb_register_function($function_name) {
    CFDBPermittedFunctions::getInstance()->addPermittedFunction($function_name);
}
