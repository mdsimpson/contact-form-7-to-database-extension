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


class CFDBIntegrationNinjaForms {

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
        // http://docs.ninjaforms.com/article/106-ninjaformspostprocess
        add_action('init', array(&$this, 'registerHook2'));
    }

    public function registerHook2() {
        add_action('ninja_forms_post_process', array(&$this, 'saveFormData'));
    }

    /**
     * @return bool
     */
    public function saveFormData() {
        try {
            $data = $this->convertData();
            if ($data) {
                return $this->plugin->saveFormData($data);
            }
        } catch (Exception $ex) {
            $this->plugin->getErrorLog()->logException($ex);
        }
        return true;
    }

    /**
     * @return object
     */
    public function convertData() {
        /**
         * @global $ninja_forms_processing Ninja_Forms_Processing
         */
        global $ninja_forms_processing;
//        $this->plugin->getErrorLog()->log(
//                print_r($ninja_forms_processing, true)); // debug

        $postedData = array();
        $uploadFiles = array();

        // Get all the user submitted values

        $submitted_fields = $ninja_forms_processing->get_all_submitted_fields();
        if (is_array($submitted_fields)) {
            $submitted_field_ids = array_keys($submitted_fields);
            $all_fields = $ninja_forms_processing->get_all_fields($submitted_fields);
            if (is_array($all_fields)) {
                foreach ($all_fields as $field_id => $user_value) {
                    if (in_array($field_id, $submitted_field_ids)) {
                        if ($ninja_forms_processing->get_field_setting($field_id, 'type') == '_honeypot') {
                            continue;
                        }
                        $field_name = $ninja_forms_processing->get_field_setting($field_id, 'admin_label');
                        if (!$field_name) {
                            $field_name = $ninja_forms_processing->get_field_setting($field_id, 'label');
                        }
                        if (is_array($user_value)) {
                            $postedData[$field_name] = implode(',', $user_value);
                        } else {
                            $postedData[$field_name] = $user_value;
                        }
                    }
                }
                $formTitle = 'Ninja Form';
                if (isset($ninja_forms_processing->data['form']['form_title'])) {
                    $formTitle = $ninja_forms_processing->data['form']['form_title'];
                }

                return (object)array(
                        'title' => $formTitle,
                        'posted_data' => $postedData,
                        'uploaded_files' => $uploadFiles);

            }
        } else {
            // There is no form data to process.
            // Ignore the form submission event
            // Seems to happen when PayPal
            // https://wordpress.org/support/topic/weird-error-code-appearing-when-someone-submits-using-ninja-forms?replies=2#post-7889546
        }
        return null;
    }

}
