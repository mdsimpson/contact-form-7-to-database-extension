<?php

/*
    "Contact Form to Database" Copyright (C) 2011-2015 Michael Simpson  (email : michael.d.simpson@gmail.com)

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

class CFDBIntegrationEnfoldTheme
{
    /**
     * @var CF7DBPlugin
     */
    var $plugin;

    /**
     * @param $plugin CF7DBPlugin
     */
    function __construct($plugin)
    {
        $this->plugin = $plugin;
    }

    public function registerHooks()
    {
        add_filter('avf_form_send', array(&$this, 'saveFormData'), 10, 4);
    }

    /**
     * @param $bool boolean
     * @param $new_post array
     * @param $form_params array
     * @param $avia_form avia_form defined in Enfold theme class-form-generator.php
     * @return bool always return true to allow Enfold email to proceed
     */
    public function saveFormData($bool, $new_post, $form_params, $avia_form)
    {

        // Debug
//        $msg = '$new_post=' . print_r($new_post, true) . "\n" .
//                '$form_params=' . print_r($form_params, true).  "\n" .
//                '$avia_form=' . print_r($avia_form, true);
//        $this->plugin->getErrorLog()->log($msg);

        try {
            if (is_array($new_post)) {
                $postedData = array();

//              $avia_form->form_elements =
//                [form_elements] => Array
//                (
//                    [name] => Array
//                    (
//                        [label] => Name
//                        [type] => text
//                    [options] =>
//                        [multi_select] =>
//                        [av_contact_preselect] =>
//                        [check] => is_empty
//                    [width] =>
//                ) ...
                $elements = array_values($avia_form->form_elements);
                $len = count($elements);
                $idx = 0;
                foreach ($new_post as $key => $value) {
                    // The $new_post keys in Enfold look like "1_1", "2_1" and there is no explicit mapping to the
                    // field label. But the $avia_form->form_elements array has labels for the fields.
                    // This code assumes that the order that the fields are listed in
                    // $new_post matches the order they are listed in $avia_form->form_elements
                    if ($idx < $len) {
                        if (is_array($elements[$idx]) && key_exists('label', $elements[$idx])) {
                            $key = $elements[$idx]['label'];
                        }
                    }
                    $postedData[$key] = urldecode($value);
                    $idx++;
                }

                $title = 'Enfold';
                if (is_array($form_params) &&
                    isset($form_params['heading']) &&
                    $form_params['heading']
                ) {
                    $title = strip_tags($form_params['heading']);
                }

                $data = (object)array(
                    'title' => $title,
                    'posted_data' => $postedData,
                    'uploaded_files' => array());
                $this->plugin->saveFormData($data);

            }
        } catch (Exception $ex) {
            $this->plugin->getErrorLog()->logException($ex);
        }

        return true;
    }

}