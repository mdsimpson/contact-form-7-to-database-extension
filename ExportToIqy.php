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

require_once('CFDBExport.php');

class ExportToIqy implements CFDBExport {

    public function export($formName, $options = null) {
        header('Content-Type: text/x-ms-iqy');
        header("Content-Disposition: attachment; filename=\"$formName.iqy\"");

        $url = get_bloginfo('wpurl');
        $encFormName = urlencode($formName);
        $uri = "?action=cfdb-export&form=$encFormName&enc=HTMLBOM";
        if (is_array($options)) {
            foreach ($options as $key => $value) {
                if ($key != 'form' && $key != 'enc') {
                    $uri = $uri . '&' . urlencode($key) . '=' . urlencode($options[$key]);
                }
            }
        }
        $encRedir = urlencode('wp-admin/admin-ajax.php' . $uri);

        if (ob_get_length()) {
            // Prevents misc chars/newlines from being printed during export.
            ob_clean();
        }

        // To get this to work right, we have to submit to the same page that the login form does and post
        // the same parameters that the login form does. This includes "log" and "pwd" for the login and
        // also "redirect_to" which is the URL of the page where we want to end up including a "form_name" parameter
        // to tell that final page to select which contact form data is to be displayed.
        //
        // "Selection=1" references the 1st HTML table in the page which is the data table.
        // "Formatting" can be "None", "All", or "RTF"
        echo (
"WEB
1
$url/wp-login.php?redirect_to=$encRedir
log=[\"Username for $url\"]&pwd=[\"Password for $url\"]

Selection=1
Formatting=All
PreFormattedTextToColumns=True
ConsecutiveDelimitersAsOne=True
SingleBlockTextImport=False
DisableDateRecognition=False
DisableRedirections=False
");
    }
}