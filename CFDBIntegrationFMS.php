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

require_once('CFDBPostDataConverter.php');

class CFDBIntegrationFMS {

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
        add_filter('fms_valid_contact_form', array(&$this, 'saveFormData'), 10, 1);
    }

    public function saveFormData($form_id) {
        try {
            $title = get_the_title($form_id);
            $converter = new CFDBPostDataConverter();
            $converter->addExcludeField('post_nonce_field');
            $converter->addExcludeField('form-type');
            $converter->addExcludeField('fms-ajax');
            $converter->addExcludeField('action');
            $data = $converter->convert($title);

            // CFDBPostDataConverter won't capture files how they are organized here
            if (is_array($_FILES) && !empty($_FILES)) {
                foreach ($_FILES as $key => $file) {
                    if (is_array($file['tmp_name'])) {
                        for ($idx = 0; $idx < count($file['tmp_name']); ++$idx) {
                            if (is_uploaded_file($file['tmp_name'][$idx])) {
                                $fileKey = ($idx > 0) ? ($key . $idx) : $key;
                                $data->posted_data[$fileKey] = $file['name'][$idx];
                                $data->uploaded_files[$fileKey] = $file['tmp_name'][$idx];
                            }
                        }
                    }
                }
            }

            return $this->plugin->saveFormData($data);
        } catch (Exception $ex) {
            $this->plugin->getErrorLog()->logException($ex);
        }
        return true;
    }

}
