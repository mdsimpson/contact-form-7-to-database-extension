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


class CFDBIntegrationCFormsII {

    /**
     * @var CF7DBPlugin
     */
    var $plugin;

    /**
     * @param $plugin CF7DBPlugin
     */
    function __construct($plugin) {
        $this->plugin = $plugin;
    }

    public function registerHooks() {
        add_action('cforms2_after_processing_action', array(&$this, 'saveFormData'), 10, 1);
    }

    public function saveFormData($trackf) {
        /*
    [id] =>
    [data] => Array
        (
            [$$$1] => Fieldset
            [Fieldset] => My Fieldset
            [$$$2] => Your Name
            [Your Name] => Mike
            [$$$3] => Email
            [Email] => me@example.com
            [$$$4] => Website
            [Website] => http://example.com
            [$$$5] => Message
            [Message] => example
        )

    [title] => Your default form
    [uploaded_files] => Array
        (
        )
         */
        //$this->plugin->getErrorLog()->log(print_r($trackf, true) . "\n\n"); // debug

        $form_data = array();
        foreach ($trackf['data'] as $key => $value) {
            if (strpos($key, '$$$') !== 0) {
                $form_data[$key] = $value;
            }
        }

        $uploaded_files = array();
        // TODO
//        if (is_array($trackf['uploaded_files'])) {
//            foreach ($trackf['uploaded_files'] as $file) {
//                $key = 'file'; // todo $key
//                $posted_data[$key] = $file['name'];
//                $uploaded_files[$key] = $file['tmp_name'];
//            }
//        }


        $cfdb_data = (object)array(
                'title' => $trackf['title'],
                'posted_data' => $form_data,
                'uploaded_files' => $uploaded_files);

        do_action_ref_array('cfdb_submit', array(&$cfdb_data));

    }
}

