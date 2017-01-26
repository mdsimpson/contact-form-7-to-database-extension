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

require_once('CFDBValueConverter.php');

class DereferenceShortcodeVars implements CFDBValueConverter {

    public function convert($varString) {
        if ($varString == null) {
            return $varString;
        }
        $retValue = $varString; // Default return

        if (is_user_logged_in()) {
            // user is logged in
            $current_user = wp_get_current_user(); // WP_User
            $retValue = str_replace('$ID', $current_user->ID, $retValue);
            //$retValue = str_replace('$id', @$current_user->id, $retValue); // deprecated
            $retValue = str_replace('$first_name', $current_user->first_name, $retValue);
            $retValue = str_replace('$last_name', $current_user->last_name, $retValue);
            $retValue = str_replace('$user_login', $current_user->user_login, $retValue);
            $retValue = str_replace('$user_nicename', $current_user->user_nicename, $retValue);
            $retValue = str_replace('$user_email', $current_user->user_email, $retValue);
            $retValue = str_replace('$user_firstname', $current_user->user_firstname, $retValue);
            $retValue = str_replace('$user_lastname', $current_user->user_lastname, $retValue);
        }


        if (strpos($retValue, '$_POST') !== false) {
            $matches = $this->getMatches('_POST', $retValue);
            foreach ($matches as $aMatch) {
                $paramName = $this->extractParamName('_POST', $aMatch);
                $replace = '';
                if ($paramName != '' && isset($_POST[$paramName])) {
                   $replace = $_POST[$paramName];
                }
                $retValue = str_replace($aMatch, $replace, $retValue);
            }
        }

        if (strpos($retValue, '$_GET') !== false) {
            $matches = $this->getMatches('_GET', $retValue);
            foreach ($matches as $aMatch) {
                $paramName = $this->extractParamName('_GET', $aMatch);
                $replace = '';
                if ($paramName != '' && isset($_GET[$paramName])) {
                   $replace = $_GET[$paramName];
                }
                $retValue = str_replace($aMatch, $replace, $retValue);
            }
        }

        if (strpos($retValue, '$_COOKIE') !== false) {
            $matches = $this->getMatches('_COOKIE', $retValue);
            foreach ($matches as $aMatch) {
                $paramName = $this->extractParamName('_COOKIE', $aMatch);
                $replace = '';
                if ($paramName != '' && isset($_COOKIE[$paramName])) {
                   $replace = $_COOKIE[$paramName];
                }
                $retValue = str_replace($aMatch, $replace, $retValue);
            }
        }

        return $retValue;
    }

    /**
     * Extract expressions from string. E.g. extact all _POST(xxx) from 'xxx=yy&&_POST(zzz)=someval&&_POST(aaa)=othervalue'
     * @param $varName string = '_POST'|'_GET'|'_COOKIE'
     * @param $fullExpressionString string like 'xxx=yy&&_POST(zzz)=someval&&_POST(aaa)=othervalue'
     * @return array of string expressions like array ( '_POST(zzz)', '_POST(aaa)' )
     */
    public function &getMatches($varName, $fullExpressionString) {
        $matches = array();
        preg_match_all('/\$'. $varName . '\s*\(\s*[\'"]?[^\'"\)]+[\'"]?\s*\)/', $fullExpressionString, $matches);
        return $matches[0];
    }

    /**
     * See if variable name in the form of $varName('ParamName') appears in the $varString
     * (quotes optional or can be double-quotes)
     * Intended to detect $varString is of the form $_POST['param-name'] given $varName = '_POST'
     * @param  $varName string name of the variable (without the "$")
     * @param  $varString string to search
     * @return string inside the brackets and quotes or '' if there is no match or
     */
    public function extractParamName($varName, $varString)
    {
        $singleQuotes = '/^\$%s\\s*\(\\s*\'([^\']*)\'\\s*\)$/';
        $doubleQuotes = '/^\$%s\\s*\(\\s*"([^"]*)"\\s*\)$/';
        $noQuotes     = '/^\$%s\\s*\(([^\)]+)\)$/';

        $templates = array($singleQuotes, $doubleQuotes, $noQuotes);
        foreach ($templates as $template) {

            $matches = array();
            if (preg_match(sprintf($template, $varName), $varString, $matches)) {
                //print_r($matches); // debug
                if (count($matches) > 1) {
                    return $matches[1];
                }
            }
        }

        return '';
    }
}
