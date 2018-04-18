<?php
/*
    "Contact Form to Database" Copyright (C) 2011-2013 Michael Simpson  (email : michael.d.simpson@gmail.com)

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
require_once('ShiftJisConverter.php');

class ExportToCsvUtf8 extends ExportBase implements CFDBExport {

    var $useBom = false;
    var $bak = false;

    /**
     * boolean For Japanese
     */
    var $useShiftJIS = false;
    var $shiftJis;


    /**
     * ExportToCsvUtf8 constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->shiftJis = new ShiftJisConverter();
    }

    public function setUseBom($use) {
        $this->useBom = $use;
    }

    public function setUseShiftJIS($use) {
        if (!$this->shiftJis->canConvert()) {
            $this->useShiftJIS = false;
        }
        else {
            $this->useShiftJIS = $use;
        }
    }

    public function export($formName, $options = null) {

        if (isset($options['bak']) && $options['bak'] == 'true') {
            $this->bak = true;
            $options['hide'] = 'Submitted';
            $options['show'] = 'submit_time,/.*/';
            $options['unbuffered'] = 'true';
        }

        $this->setOptions($options);
        $this->setCommonOptions();

        // Security Check
        if (!$this->isAuthorized()) {
            $this->assertSecurityErrorMessage();
            return;
        }

        if ($this->options && !$this->bak && is_array($this->options)) {
            if (isset($this->options['bom'])) {
                $this->useBom = $this->options['bom'] == 'true';
            }
        }

        $this->echoCsv($formName, $options);
    }

    public function echoCsv($formName, $options = null) {

        $eol = "\n";
        $delimiter = ',';
        if (isset($this->options['delimiter'])) {
            $delimiter = $this->options['delimiter'];
        } else if ($this->hasGoogleSpreadsheetHeader()) {
            // Google Spreadsheet uses comma as a delimiter
            $delimiter = ',';
        } else {
            if (isset($options['regionaldelimiter']) && $options['regionaldelimiter'] == 'true') {
                // Pick a delimiter based on regional settings
                $delimiter = $this->get_csv_delimiter(get_locale());
            }
        }
        
        // Query DB for the data for that form
        $submitTimeKeyName = 'Submit_Time_Key';
        $this->setDataIterator($formName, $submitTimeKeyName);
        $this->clearAllOutputBuffers();

        // Headers
        $charSet = 'UTF-8';
        if ($this->useShiftJIS) {
            $charSet = $this->shiftJis->getContentTypeCharSet();
        }
        $this->echoHeaders(
                array("Content-Type: text/csv; charset=$charSet",
                        "Content-Disposition: attachment; filename=\"$formName.csv\""));

        if ($this->useBom) {
            // File encoding UTF-8 Byte Order Mark (BOM) http://wiki.sdn.sap.com/wiki/display/ABAP/Excel+files+-+CSV+format
            echo chr(239) . chr(187) . chr(191);
        }


        // Column Headers
        if (isset($this->options['header']) && $this->options['header'] != 'true') {
           // do not output column headers
        }
        else  {
            foreach ($this->dataIterator->getDisplayColumns() as $aCol) {
                $colDisplayValue = $aCol;
                if ($this->headers && isset($this->headers[$aCol])) {
                    $colDisplayValue = $this->headers[$aCol];
                }
                if ($this->useShiftJIS) {
                    $colDisplayValue = $this->shiftJis->convertUtf8ToSjis($colDisplayValue);
                }
                $colDisplayValue = $this->escapeFunctionCall($colDisplayValue);
                printf('"%s"', str_replace('"', '""', $colDisplayValue));
                echo $delimiter;
            }
            echo $eol;
        }

        // Rows
        $showFileUrlsInExport = $this->plugin->getOption('ShowFileUrlsInExport') == 'true';
        while ($this->dataIterator->nextRow()) {
            $fields_with_file = null;
            if ($showFileUrlsInExport &&
                    isset($this->dataIterator->row['fields_with_file']) &&
                    $this->dataIterator->row['fields_with_file'] != null) {
                $fields_with_file = explode(',', $this->dataIterator->row['fields_with_file']);
            }
            foreach ($this->dataIterator->getDisplayColumns() as $aCol) {
                $cell = isset($this->dataIterator->row[$aCol]) ? $this->dataIterator->row[$aCol] : '';
                if ($showFileUrlsInExport &&
                        $fields_with_file &&
                        $cell &&
                        in_array($aCol, $fields_with_file)) {
                    $cell = $this->plugin->getFileUrl($this->dataIterator->row[$submitTimeKeyName], $formName, $aCol);
                }
                $cell = $this->escapeFunctionCall($cell);
                if ($this->useShiftJIS) {
                    $cell = $this->shiftJis->convertUtf8ToSjis($cell);
                }
                printf('"%s"', str_replace('"', '""', $cell));
                echo $delimiter;
            }
            echo $eol;
        }
    }

    /**
     * To avoid CSV injection of functions, add a single quote at the beginning
     * of any value that begins with an = per Excel convention
     * @param $value
     * @return string
     */
    public function escapeFunctionCall($value) {
        if (strpos($value, '=') === 0) {
            $value = "'" . $value;
        }
        return $value;
    }


    public function hasGoogleSpreadsheetHeader() {
        if (function_exists('getallheaders')) {
            $clientHeaders = getallheaders();
            if (!empty($clientHeaders) && isset($clientHeaders['User-Agent'])) {
                if (strpos($clientHeaders['User-Agent'], 'GoogleDocs') !== false) {
                    return true;
                }
                if (strpos($clientHeaders['User-Agent'], 'docs.google.com') !== false) {
                    return true;
                }
            }
        }
        return false;
    }


}