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

/**
 * Class ShiftJisConverter
 * Includes functions to convert UTF8 strings to Shift JIS (Japanese)
 */
class ShiftJisConverter {

    // The code number of Japanese two-byte character "ãƒ¼" is separated by Japanese encoding types.
    // Hyphen, Centered dot
    var $utf_escape_patterns_search = array('/\xE2\x80\x93/', '/\xE2\x80\xA2/');
    var $utf_escape_patterns_replace = array("\xE2\x88\x92", "\xE3\x83\xBB");

    /**
     * Convert Shift-JIS (Standard Encoding for Japanese Applications) to UTF-8.
     * @param $str string
     * @return string
     */
    public function convertUtf8ToSjis($str) {
        return mb_convert_encoding($this->replaceShiftjisEscapeChars($str), 'SJIS-win', 'utf-8');
    }

    public function replaceShiftjisEscapeChars($str) {
        return preg_replace(
                $this->utf_escape_patterns_search,
                $this->utf_escape_patterns_replace,
                $str);
    }

    public function getContentTypeCharSet() {
        return 'Shift_JIS';
    }

    public function canConvert() {
        // If mb_convert_encoding function is not enabled (mb_string module is not installed),
        // then converting cannot be done.
        return function_exists('mb_convert_encoding');
    }

}
