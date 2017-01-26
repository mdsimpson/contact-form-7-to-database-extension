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

class CFDBIntegrationEnfoldTheme {
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
        add_filter('avf_form_send', array(&$this, 'saveFormData'), 10, 3);
    }

    public function saveFormData($bool, $new_post, $form_params) {

//        $msg = '$new_post=' . print_r($new_post, true) . "\n" .
//                '$form_params=' . print_r($form_params, true);
//        $this->plugin->getErrorLog()->log($msg);

        try {
            if (is_array($new_post)) {
                $postedData = array();
                foreach ($new_post as $key => $value) {
                    $postedData[$key] = urldecode($value);
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