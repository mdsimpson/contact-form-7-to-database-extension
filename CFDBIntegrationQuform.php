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

class CFDBIntegrationQuform {

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
        // http://support.themecatcher.net/quform-wordpress/guides/hooks/iphorm_post_process
        add_action('iphorm_post_process', array(&$this, 'saveFormData'), 10, 1);
    }

    /**
     * @param $form iPhorm
     * @return bool
     */
    public function saveFormData($form) {
        try {
            $data = $this->convertData($form);
            return $this->plugin->saveFormData($data);
        } catch (Exception $ex) {
            $this->plugin->getErrorLog()->logException($ex);
        }
        return true;
    }


    /**
     * @param $form iPhorm
     * @return object
     */
    public function convertData($form) {
        // http://support.themecatcher.net/quform-wordpress/guides/basic/getting-form-values
        $allValues = $form->getValues();

//        $this->plugin->getErrorLog()->log(
//                print_r($form, true));

        if (is_array($allValues)) {

            $postedData = array();
            $uploadFiles = array();

            foreach ($allValues as $fieldId => $value) {
                // $fieldId is something like "iphorm_2_1"
                // get the human-readable field label
                $fieldName = $fieldId; //iPhorm_Element
                $element = $form->getElement($fieldId);
                if (is_object($element)) {
                    $fieldName = $element->getLabel();
                }

                if (is_array($value)) {
                    if (array_key_exists('day', $value)) {
                        $postedData[$fieldName] = sprintf('%s-%s-%s', $value['year'], $value['month'], $value['day']);
                    } else if (array_key_exists('hour', $value)) {
                        $postedData[$fieldName] = sprintf('%s:%s %s', $value['hour'], $value['minute'], $value['ampm']);
                    } else if (array_key_exists(0, $value)) {
                        if (is_array($value[0])) {
                            // file upload
                            foreach ($value as $upload) {
                                $postedData[$fieldName] = $upload['text'];
                                $uploadFiles[$fieldName] = $upload['fullPath'];
                            }
                        } else {
                            $postedData[$fieldName] = implode(',', array_values($value));
                        }
                    }
                } else {
                    $postedData[$fieldName] = $value;
                }
            }

            return (object)array(
                    'title' => $form->getName(),
                    'posted_data' => $postedData,
                    'uploaded_files' => $uploadFiles);
        }

    }

}