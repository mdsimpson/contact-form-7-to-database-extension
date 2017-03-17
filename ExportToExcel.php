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

require_once('ExportBase.php');
require_once('CFDBExport.php');

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Style;

class ExportToExcel extends ExportBase implements CFDBExport {

    /**
     * @param $formName string
     * @param $options array of option_name => option_value
     * @return void
     */
    public function export($formName, $options = null) {

        $this->setOptions($options);
        $this->setCommonOptions();

        // Security Check
        if (!$this->isAuthorized()) {
            $this->assertSecurityErrorMessage();
            return;
        }

        if (version_compare(phpversion(), '5.4') < 0) {
            $this->echoHeaders(array('Content-Type: text/html'));
            printf('<html><head><title>%s</title></head>',
                    __('PHP Upgrade Needed', 'contact-form-7-to-database-extension'));
            _e('CFDB Excel file export requires PHP 5.4 or later on your server.',  'contact-form-7-to-database-extension');
            echo '<br/>';
            _e('Your server\'s PHP version: ', 'contact-form-7-to-database-extension');
            echo phpversion();
            echo '<br/>';
            printf('<a href="https://wordpress.org/about/requirements/">%s</a>',
                    __('See WordPress Recommended PHP Version', 'contact-form-7-to-database-extension'));
            printf('</body></html>');
            return;
        }
        require_once 'Spout-2.7.1/Autoloader/autoload.php'; // requires PHP 5.4

        // Query DB for the data for that form
        $submitTimeKeyName = 'Submit_Time_Key';
        $this->setDataIterator($formName, $submitTimeKeyName);
        $this->clearAllOutputBuffers();

        $type = Type::XLSX;
        $suffix = 'xlsx';
        if (isset($options['format'])) {
            switch ($options['format']) {
                case 'ods' :
                    $type = Type::ODS;
                    $suffix = 'ods';
                    break;
//                case 'csv' :
//                    $type = Type::CSV;
//                    $suffix = 'csv';
//                    break;
                default:
                    break;
            }
        }
        $writer = WriterFactory::create($type);
        $writer->openToBrowser("$formName.$suffix"); // stream data directly to the browser

        // Column Headers
        if (isset($this->options['header']) && $this->options['header'] != 'true') {
            // do not output column headers
        } else {
            $headerRow = array();
            foreach ($this->dataIterator->getDisplayColumns() as $aCol) {
                $colDisplayValue = $aCol;
                if ($this->headers && isset($this->headers[$aCol])) {
                    $colDisplayValue = $this->headers[$aCol];
                }
                $headerRow[] = $colDisplayValue;
            }
            $headerStyle = new Style();
            $headerStyle->setFontBold();
            $writer->addRowWithStyle($headerRow, $headerStyle); // add a row at a time
        }

        // Rows
//        $showFileUrlsInExport = $this->plugin->getOption('ShowFileUrlsInExport') == 'true';
        while ($this->dataIterator->nextRow()) {
            $dataRow = array();
            $fields_with_file = null;
            if (//$showFileUrlsInExport &&
                    isset($this->dataIterator->row['fields_with_file']) &&
                    $this->dataIterator->row['fields_with_file'] != null
            ) {
                $fields_with_file = explode(',', $this->dataIterator->row['fields_with_file']);
            }
            foreach ($this->dataIterator->getDisplayColumns() as $aCol) {
                $cell = isset($this->dataIterator->row[$aCol]) ? $this->dataIterator->row[$aCol] : '';
                if ($aCol == 'Submitted' && isset($this->dataIterator->row[$submitTimeKeyName])) {
                    // Put date in a format that Excel et. al. understand
                    $timestamp = $this->dataIterator->row[$submitTimeKeyName];
                    $cell = date('Y-m-d H:i:s', $timestamp);
                }

                if (//$showFileUrlsInExport &&
                        $fields_with_file &&
                        $cell &&
                        in_array($aCol, $fields_with_file)
                ) {
                    // In the case of file links, we want to create a HYPERLINK formula as a link to download the file.
                    $url = $this->plugin->getFileUrl($this->dataIterator->row[$submitTimeKeyName], $formName, $aCol);
                    if ($type == Type::ODS) {
                        // But the Spout library doesn't support creating formulas.
                        // So people will have to convert them after the fact
                        // https://cfdbplugin.com/?p=1430
                        $cell = "=HYPERLINK(\"$url\"; \"$cell\")";
                    } else {
                        // A code change I introduced in the included Spout library will make this become a formula
                        $cell = "=HYPERLINK(\"$url\",\"$cell\")";
                    }
                }
                $dataRow[] = $cell;
            }
            $writer->addRow($dataRow); // add a row at a time
        }

        $writer->close();
    }

}
