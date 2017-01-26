<?php

/*
    "Contact Form to Database" Copyright (C) 2016-2016 Michael Simpson  (email : michael.d.simpson@gmail.com)

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

class CFDBIntegrationFromCraft {

    public function registerHooks() {
        add_action('formcraft_after_save', array(&$this, 'saveFormData'), 10, 4);
    }

    // http://formcraft-wp.com/help/formcraft-hooks-filters/
    public function saveFormData($content, $meta, $raw_content, $integrations) {

        $form_data = array();
        $uploaded_files = array();

        if (!is_array($raw_content)) {
            return;
        }

        foreach ($raw_content as $field) {
            if (is_array($field)) {
                if ($field['type'] == 'fileupload' &&
                        is_array($field['value']) &&
                        is_array($field['url'])
                ) {
                    // Handle file uploads
                    $idx = 0;
                    // Array of file names and array of urls to them
                    foreach ($field['value'] as $fileName) {
                        $url = $field['url'][$idx];
                        $pos = strpos($url, 'wp-content');
                        if ($pos !== FALSE) {
                            $path = ABSPATH . substr($url, $pos);
                            $label = $field['label'];
                            if ($idx > 0) {
                                // FormCraft allows more then one file to be uploaded under the same form field name
                                // so create a new field name for additional files
                                $label = "$label-$idx";
                            }
                            $form_data[$label] = $fileName;
                            $uploaded_files[$label] = $path;
                        }
                        ++$idx;
                    }
                } else if ($field['type'] = 'matrix' &&
                        is_array($field['value'])) {
                    // Matrix value question:answer
                    $questionAndAnswerArray = array();
                    foreach ($field['value'] as $qAndA) {
                        $questionAndAnswerArray[] = "{$qAndA['question']}|{$qAndA['value']}";
                    }
                    $form_data[$field['label']] = implode("\n", $questionAndAnswerArray);
                } else if (is_array($field['value'])) {
                    // Array of Strings Value
                    $form_data[$field['label']] = implode(',', $field['value']);
                } else {
                    // String Value
                    $form_data[$field['label']] = $field['value'];
                }
            }
        }

        $cfdb_data = (object)array(
                'title' => $content['Form Name'],
                'posted_data' => $form_data,
                'uploaded_files' => $uploaded_files);

        do_action_ref_array('cfdb_submit', array(&$cfdb_data));

    }
}