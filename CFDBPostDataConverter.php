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
 * Class CFDBPostDataConverter Converts $_POST and $_FILE into the standard format for
 * this plugin to save it
 */
class CFDBPostDataConverter {

    /**
     * @var array name of fiels not to save
     */
    var $excludeFields = array();

    public function addExcludeField($fieldName) {
        $this->excludeFields[] = $fieldName;
    }

    /**
     * @param $title string form title
     * @return object that can be passed to CF7DBPlugin::saveData()
     */
    public function convert($title) {
        $data = null;

        if (is_array($_POST) && !empty($_POST)) {
            $posted_data = array();
            $uploaded_files = array();

            // Get posted values
            foreach ($_POST as $key => $val) {
                if (!in_array($key, $this->excludeFields)) {
                    $posted_data[$key] = $val;
                }
            }


            // Deal with upload files
            // $_FILES = Array (
            //    [your-upload] => Array
            //        (
            //            [name] => readme.txt
            //            [type] => text/plain
            //            [tmp_name] => /tmp/php3tQ1zg
            //            [error] => 0
            //            [size] => 1557
            //        )
            //)
            if (is_array($_FILES) && !empty($_FILES)) {
                foreach ($_FILES as $key => $file) {
                    if (is_uploaded_file($file['tmp_name'])) {
                        $posted_data[$key] = $file['name'];
                        $uploaded_files[$key] = $file['tmp_name'];
                    }
                }
            }

            if (!$title) {
                $title = 'Untitled';
            }
            // Prepare data structure for call to hook
            $data = (object)array('title' => $title,
                    'posted_data' => $posted_data,
                    'uploaded_files' => $uploaded_files);

        }

        return $data;
    }
}
