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

class CFDBDeobfuscate {

    // Taken from http://ditio.net/2008/11/04/php-string-to-hex-and-hex-to-string-functions/
    static function hexToStr($hex) {
        $string = '';
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $string;
    }

    static function deobfuscateHexString($hex, $key) {
        $hexString = CFDBDeobfuscate::hexToStr($hex);
        return CFDBDeobfuscate::deobfuscateString($hexString, $key);
    }

    static function deobfuscateString($string, $key) {
        if (function_exists('mcrypt_decrypt')) {
            // Although php5-mycrypt may be installed, it may not be listed in
            // php.ini file (like below), thus the function is not defined
            // extension=/usr/lib/php5/20121212/mcrypt.so
            return mcrypt_decrypt(MCRYPT_3DES, $key, $string, 'ecb');
        }
        return '';
    }

}
