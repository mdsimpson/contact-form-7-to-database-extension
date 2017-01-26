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

class CFDBIntegrationJetPack {

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
        add_action('grunion_pre_message_sent', array(&$this, 'saveFormData'), 10, 3);
    }

    /**
     * @param $post_id int
     * @param $all_values array
     * @param $extra_values array
     * @return object
     */
    public function saveFormData($post_id, $all_values, $extra_values) {
        try {
            $data = $this->convertData($post_id, $all_values);

            return $this->plugin->saveFormData($data);
        } catch (Exception $ex) {
            $this->plugin->getErrorLog()->logException($ex);
        }
        return true;
    }

    public function convertData($post_id, $all_values) {

//        $errorLog = $this->plugin->getErrorLog();
//        $errorLog->log('POST=' . print_r($_POST, true));
//        $errorLog->log('$all_values=' . print_r($all_values, true));
//        $errorLog->log('$extra_values=' . print_r($extra_values, true));

        $title = 'JetPack Contact Form';
        if (isset($_POST['contact-form-id'])) {
            $title .= ' ' . $_POST['contact-form-id'];
            //$all_values['contact-form-id'] = $_POST['contact-form-id'];
        }
        else {
            $title .= ' ' . $post_id;
        }

        $all_values['post_id'] = $post_id;
        return (object)  array(
                'title' => $title,
                'posted_data' => $all_values,
                'uploaded_files' => null);
    }


}