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

class CFDBIntegrationGravityForms {

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
        add_action('gform_after_submission', array(&$this, 'saveFormData'), 10, 2);
    }

    public function saveFormData($entry, $form) {
        try {
            $data = $this->convertData($entry, $form);

            return $this->plugin->saveFormData($data);
        } catch (Exception $ex) {
            $this->plugin->getErrorLog()->logException($ex);
        }
        return true;
    }

    /**
     * http://www.gravityhelp.com/documentation/page/Gform_after_submission
     * @param $entry Entry Object The entry that was just created.
     * http://www.gravityhelp.com/documentation/page/Entry_Object
     * @param $form Form Object The current form
     * http://www.gravityhelp.com/documentation/page/Form_Object
     * @return object
     */
    public function convertData($entry, $form) {

        //$errorLog = $this->plugin->getErrorLog();
        //$errorLog->log('Form Definition: ' . print_r($form, true)); // debug
        //$errorLog->log('Entry Definition: ' . print_r($entry, true)); // debug

        $postedData = array();
        $uploadFiles = array();

        // Iterate through the field definitions and get their values
        if (!is_array($form['fields'])) {
            return true;
        }
        foreach ($form['fields'] as $field) {

            // Gravity Forms 1.8.5 $field was an array
            // Gravity Forms 1.9.1.2 $field is an object
            if (is_object($field)) {
                $field = (array)$field;
            }

            $fieldId = $field['id'];
            $fieldName = (isset($field['adminLabel']) && $field['adminLabel']) ?
                    $field['adminLabel'] : // Use override label if exists
                    $field['label'];
            
            if (isset($entry[$fieldId])) {
                switch ($field['type']) {
                    case 'list' :
                        $list = unserialize($entry[$fieldId]);
                        if ($list) {
                            // $list may be a list of strings or
                            // or in the case of Gravity Form List with columns,
                            /*
                             Array
                                (
                                    [0] => Array
                                        (
                                            [Column 1] => hi
                                            [Column 2] => there
                                            [Column 3] => howdy
                                        )
                                )
                             */
                            if (! empty($list) && is_array($list[0])) {
                                $colMatrix = array();
                                foreach ($list as $colArray) {
                                    $colList = array();
                                    foreach ($colArray as $colKey => $colValue) {
                                        $colList[] = $colKey . '=' . $colValue;
                                    }
                                    $colMatrix[] = implode('|', $colList);
                                }
                                $postedData[$fieldName] = implode("\n", $colMatrix);
                            } else {
                                $postedData[$fieldName] = implode('|', $list);
                            }
                        } else {
                            if (!isset($postedData[$fieldName]) || $postedData[$fieldName] === '') { // handle duplicate empty hidden fields
                                // List - value is serialized array
                                $valueArray = @unserialize($entry[$fieldId]);
                                if (is_array($valueArray)) {
                                    //$postedData[$fieldName] = '';
                                    // Array of (Array of column-name => value)
                                    $tmpArray = array();
                                    foreach ($valueArray as $listArray) {
                                        $tmpArray[] = implode(',', array_values($listArray));
                                    }
                                    $postedData[$fieldName] = implode('|', $tmpArray);
                                } else {
                                    $postedData[$fieldName] = $entry[$fieldId];
                                }
                            }
                        }
                        break;

                    case 'fileupload':
                        if (!isset($postedData[$fieldName]) || $postedData[$fieldName] === '') { // handle duplicate empty hidden fields
                            // File Upload - value is file URL
                            // http://<SITE>/wp-content/uploads/gravity_forms/<PATH>/<FILE>
                            $url = $entry[$fieldId];
                            $fileName = basename($url);
                            $postedData[$fieldName] = $fileName;

                            $filePath = ABSPATH . substr($url, strlen(get_site_url()));
                            $uploadFiles[$fieldName] = $filePath;
                        }
                        break;

                    default:
                        if (!isset($postedData[$fieldName]) || $postedData[$fieldName] === '') { // handle duplicate empty hidden fields
                            $postedData[$fieldName] = $entry[$fieldId];
                        }
                        break;
                }
            } else {
                if (!empty($field['inputs']) && is_array($field['inputs'])) {
                    if ($field['type'] == 'checkbox') {
                        // This is a multi-input field
                        if (!isset($postedData[$fieldName]) || $postedData[$fieldName] === '') { // handle duplicate empty hidden fields
                            $values = array();
                            foreach ($field['inputs'] as $input) {
                                $inputId = strval($input['id']); // Need string value of number like '1.3'
                                if (!empty($entry[$inputId])) {
                                    $values[] = $entry[$inputId];
                                }
                            }
                            $postedData[$fieldName] = implode(',', $values);
                        }
                    } else {
                        foreach ($field['inputs'] as $input) {
                            $inputId = strval($input['id']); // Need string value of number like '1.3'
                            $label = $input['label']; // Assumption: all inputs have diff labels
                            $effectiveFieldName = $fieldName;
                            if (!empty($label)) {
                                $effectiveFieldName = $fieldName . ' ' . $label;
                            }
                            if (!isset($postedData[$effectiveFieldName]) || $postedData[$effectiveFieldName] === '') {  // handle duplicate empty hidden fields
                                if (isset($entry[$inputId])) {
                                    $postedData[$effectiveFieldName] = $entry[$inputId];
                                } else if (isset($entry[$fieldId])) {
                                    $postedData[$effectiveFieldName] = $entry[$fieldId];
                                }
                            }
                        }
                    }
                }
            }
        }

        // Other form metadata
        $paymentMetaData = array(
            //'currency',
                'payment_status', 'payment_date',
                'transaction_id', 'payment_amount', 'payment_method',
                'is_fulfilled', 'transaction_type');
        foreach ($paymentMetaData as $pmt) {
            $hasPaymentInfo = false;
            if (!empty($entry[$pmt])) {
                $postedData[$pmt] = $entry[$pmt];
                $hasPaymentInfo = true;
            }
            if ($hasPaymentInfo && !empty($entry['currency'])) {
                // It seems currency is always set but only meaningful
                // if the other payment info is set.
                $postedData['currency'] = $entry['currency'];
            }
        }


        return (object)array(
                'title' => $form['title'],
                'posted_data' => $postedData,
                'uploaded_files' => $uploadFiles);
    }


}