<?php
/*
    "Contact Form to Database" Copyright (C) 2011-2014 Michael Simpson  (email : michael.d.simpson@gmail.com)

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

require_once('CFDBDeobfuscate.php');
require_once('DereferenceShortcodeVars.php');

class CF7DBPluginExporter {

    static function doExportFromPost() {

        // Consolidate GET and POST parameters. Allow GET to override POST.
        $params = array_merge($_POST, $_GET);

//        print_r($params);
        foreach ($params as $key => $value) {
            if (is_string($value)) {
                $params[$key] = stripslashes($value);
            }
        }

        if (!isset($params['enc'])) {
            $params['enc'] = 'CSVUTF8';
        }

        $formName = '';
        if (isset($params['form'])) {
            $deref = new DereferenceShortcodeVars;
            $formName = $deref->convert($params['form']);
        }

        CF7DBPluginExporter::export(
                $formName,
                $params['enc'],
                $params);
    }

    static function export($formName, $encoding, $options) {

        switch ($encoding) {
            case 'HTML':
                require_once('ExportToHtmlTable.php');
                $exporter = new ExportToHtmlTable();
                $exporter->export($formName, $options);
                break;
            case 'HTMLBOM': // IQY callback
                require_once('ExportToHtmlTable.php');
                $exporter = new ExportToHtmlTable();
                $exporter->setUseBom(true);
                $exporter->export($formName, $options);
                break;
            case 'DT':
                require_once('ExportToHtmlTable.php');
                if (!is_array($options)) {
                    $options = array();
                }
                $options['useDT'] = true;
                if (!isset($options['printScripts'])) {
                    $options['printScripts'] = true;
                }
                if (!isset($options['printStyles'])) {
                    $options['printStyles'] = 'true';
                }
                $exporter = new ExportToHtmlTable();
                $exporter->export($formName, $options);
                break;
            case 'HTMLTemplate':
                require_once('ExportToHtmlTemplate.php');
                $exporter = new ExportToHtmlTemplate();
                $exporter->export($formName, $options);
                break;
            case 'IQY':
                require_once('ExportToIqy.php');
                $exporter = new ExportToIqy();
                $exporter->export($formName, $options);
                break;
            case 'CSVUTF8BOM':
                $options['unbuffered'] = 'true';
                require_once('ExportToCsvUtf8.php');
                $exporter = new ExportToCsvUtf8();
                $exporter->setUseBom(true);
                $exporter->export($formName, $options);
                break;
            case 'TSVUTF16LEBOM':
                $options['unbuffered'] = 'true';
                require_once('ExportToCsvUtf16le.php');
                $exporter = new ExportToCsvUtf16le();
                $exporter->export($formName, $options);
                break;
            case 'GLD':
                require_once('ExportToGoogleLiveData.php');
                $exporter = new ExportToGoogleLiveData();
                $exporter->export($formName, $options);
                break;
            case 'JSON':
                require_once('ExportToJson.php');
                $exporter = new ExportToJson();
                $exporter->export($formName, $options);
                break;
            case 'VALUE':
                require_once('ExportToValue.php');
                $exporter = new ExportToValue();
                $exporter->export($formName, $options);
                break;
            case 'COUNT':
                require_once('ExportToValue.php');
                if (!is_array($options)) {
                    $options = array();
                }
                $options['function'] = 'count';
                unset($options['show']);
                unset($options['hide']);
                $exporter = new ExportToValue();
                $exporter->export($formName, $options);
                break;
            case 'CSVSJIS':
                require_once('ExportToCsvUtf8.php');
                $exporter = new ExportToCsvUtf8();
                $exporter->setUseBom(false);
                $exporter->setUseShiftJIS(true);
                $exporter->export($formName, $options);
                break;
            case 'RSS':
                require_once('ExportToRSS.php');
                $exporter = new ExportToRSS();
                $exporter->export($formName, $options);
                break;
            case 'ENTRY':
                require_once('ExportEntry.php');
                $exporter = new ExportEntry();
                $exporter->export($formName, $options);
                break;
            case 'xlsx' :
                require_once('ExportToExcel.php');
                $exporter = new ExportToExcel();
                $exporter->export($formName, $options);
                break;
            case 'ods' :
                require_once('ExportToExcel.php');
                $exporter = new ExportToExcel();
                $options['format'] = 'ods';
                $exporter->export($formName, $options);
                break;
            case 'CSVUTF8':
            default:
                require_once('ExportToCsvUtf8.php');
                $exporter = new ExportToCsvUtf8();
                $exporter->setUseBom(false);
                $exporter->export($formName, $options);
                break;
        }
    }
}
