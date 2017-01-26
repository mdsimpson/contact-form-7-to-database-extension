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

class CFDBIntegrationVerySimpleContactForm {
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
        // Very Simple Contact Form
        add_action('vscf_before_send_mail', array(&$this, 'saveVscfFormData'), 10, 1);

        // Very Simple Signup Form
        add_action('vssf_before_send_mail', array(&$this, 'saveVssfFormData'), 10, 1);

    }

    /**
     * Very Simple Contact Form
     * @param $form_data
     * @return bool
     */
    public function saveVscfFormData($form_data) {
        try {
            $title = 'Very Simple Contact Form';
            $data = $this->convertData($form_data, $title);
            return $this->plugin->saveFormData($data);
        } catch (Exception $ex) {
            $this->plugin->getErrorLog()->logException($ex);
        }
        return true;
    }

    /**
     * Very Simple Signup Form
     * @param $form_data
     * @return bool
     */
    public function saveVssfFormData($form_data) {
        try {
            $title = 'Very Simple Signup Form';
            $data = $this->convertData($form_data, $title);
            return $this->plugin->saveFormData($data);
        } catch (Exception $ex) {
            $this->plugin->getErrorLog()->logException($ex);
        }
        return true;
    }

    public function convertData(&$form_data, $title) {
        $data = (object)array(
                'title' => $title,
                'posted_data' => $form_data,
                'uploaded_files' => null);
        return $data;
    }

}
