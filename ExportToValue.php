<?php
/*
    "Contact Form to Database" Copyright (C) 2011-2012 Michael Simpson  (email : michael.d.simpson@gmail.com)

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
require_once('CFDBShortCodeContentParser.php');

class ExportToValue extends ExportBase implements CFDBExport {

    /**
     * @param string $formName
     * @param null $options
     * @return void|String
     */
    public function export($formName, $options = null) {

        // Allow for multiple form name inputs, comma-delimited
        $tmp = explode(',', $formName);
        if (count($tmp) > 1) {
            $formName = &$tmp;
        }
        else if ($formName == '*') {
            $formName = null; // Allow for no form specified implying all forms
        }

        $this->setOptions($options);
        $this->setCommonOptions();

        // Security Check
        if (!$this->isAuthorized()) {
            $this->assertSecurityErrorMessage();
            return;
        }

        // Break out sections: Before, Content, After
        $before = '';
        $content = '';
        $after = '';
        if (isset($options['content'])) {
            $contentParser = new CFDBShortCodeContentParser;
            list($before, $content, $after) = $contentParser->parseBeforeContentAfter($options['content']);
        }
        if ($before) {
            $before = do_shortcode($before);
        }
        if ($after) {
            $after = do_shortcode($after);
        }

        // See if a function is to be applied
        $funct = null;
        $delimiter = ', ';
        if ($this->options && is_array($this->options)) {
            if (isset($this->options['function'])) {
                $funct = $this->options['function'];
            }
            if (isset($this->options['delimiter'])) {
                $delimiter = $this->options['delimiter'];
            }
        }

        // Headers
        // don't set content type to text because in some browsers this becomes
        // the content type for the whole HTML page. 
        $this->echoHeaders(); //'Content-Type: text/plain; charset=UTF-8');

        // Get the data
        $this->setDataIterator($formName);
        //$this->clearAllOutputBuffers();

        // count function or coming from cfdb-count shortcode
        if (empty($this->showColumns) &&
            empty($this->hideColumns)) {
            if ($funct == 'count') {
                $count = 0;
                while ($this->dataIterator->nextRow()) {
                    $count += 1;
                }
                if ($this->isFromShortCode) {
                    return $before . $count . $after;
                }
                else {
                    echo $before . $count . $after;
                    return;
                }
            }
        }


        if ($funct) {
            // Apply function to dataset
            switch ($funct) {
                case 'count':
                    $count = 0;
                    $colsPerRow = count($this->dataIterator->getDisplayColumns());
                    while ($this->dataIterator->nextRow()) {
                        $count += $colsPerRow;
                    }
                    if ($this->isFromShortCode) {
                        return $before . $count . $after;
                    }
                    else {
                        echo $before . $count . $after;
                        return;
                    }

                case 'min':
                    $min = null;
                    while ($this->dataIterator->nextRow()) {
                        foreach ($this->dataIterator->getDisplayColumns() as $col) {
                            $val = $this->dataIterator->row[$col];
                            if (is_numeric($val)) {
                                if ($min === null) {
                                    $min = $val;
                                }
                                else {
                                    if ($val < $min) {
                                        $min = $val;
                                    }
                                }
                            }
                        }
                    }
                    if ($this->isFromShortCode) {
                        return $before . $min . $after;
                    }
                    else {
                        echo $before . $min . $after;
                        return;
                    }

                case 'max':
                    $max = null;
                    while ($this->dataIterator->nextRow()) {
                        foreach ($this->dataIterator->getDisplayColumns() as $col) {
                            $val = $this->dataIterator->row[$col];
                            if (is_numeric($val)) {
                                if ($max === null) {
                                    $max = $val;
                                }
                                else {
                                    if ($val > $max) {
                                        $max = $val;
                                    }
                                }
                            }
                        }
                    }
                    if ($this->isFromShortCode) {
                        return $before . $max . $after;
                    }
                    else {
                        echo $before . $max . $after;
                        return;
                    }


                case 'sum':
                    $sum = 0;
                    while ($this->dataIterator->nextRow()) {
                        foreach ($this->dataIterator->getDisplayColumns() as $col) {
                            if (is_numeric($this->dataIterator->row[$col])) {
                                $sum = $sum + $this->dataIterator->row[$col];
                            }
                        }
                    }
                    if ($this->isFromShortCode) {
                        return $before . $sum . $after;
                    }
                    else {
                        echo $before . $sum .$after;
                        return;
                    }

                case 'mean':
                    $sum = 0;
                    $count = 0;
                    while ($this->dataIterator->nextRow()) {
                        foreach ($this->dataIterator->getDisplayColumns() as $col) {
                            if (is_numeric($this->dataIterator->row[$col])) {
                                $count += 1;
                                $sum += $this->dataIterator->row[$col];
                            }
                        }
                    }
                    $mean = ($count != 0) ? $sum / $count : 'undefined'; // Avoid div by zero error
                    if ($this->isFromShortCode) {
                        return $before . $mean . $after;
                    }
                    else {
                        echo $before . $mean . $after;
                        return;
                    }

                case 'percent':
                    $count = 0;
                    while ($this->dataIterator->nextRow()) {
                        foreach ($this->dataIterator->getDisplayColumns() as $col) {
                            $count += 1;
                        }
                    }

                    $total = $this->getDBRowCount($formName);
                    $numShowCols = count($this->showColumns);
                    if ($numShowCols > 1) {
                        $total = $total * $numShowCols;
                    }
                    else if ($numShowCols == 0) {
                        $total = $total * count($this->dataIterator->getDisplayColumns());
                    }

                    if ($total != 0) {
                        $percentNum = 100.0 * $count / $total;
                        $percentDisplay = round($percentNum) . '%';
                        //$percentDisplay = "$count / $total = $percentNum as $percentDisplay"; // debug
                    }
                    else {
                        // Avoid div by zero error
                        $percentDisplay = '0%';
                    }

                    if ($this->isFromShortCode) {
                        return $before . $percentDisplay . $after;
                    }
                    else {
                        echo $before . $percentDisplay . $after;
                        return;
                    }
            }
        }

        // At this point in the code: $funct not defined or not recognized
        // output values for each row/column
        if ($this->isFromShortCode) {
            $outputData = array();
            while ($this->dataIterator->nextRow()) {
                foreach ($this->dataIterator->getDisplayColumns() as $col) {
                    $outputData[] = $this->dataIterator->row[$col];
                }
            }
            ob_start();
            echo $before;
            switch (count($outputData)) {
                case 0:
                    echo '';
                    break;
                case 1:
                    echo $outputData[0];
                    break;
                default:
                    echo implode($delimiter, $outputData);
                    break;
            }
            echo $after;
            $output = ob_get_contents();
            ob_end_clean();
            // If called from a shortcode, need to return the text,
            // otherwise it can appear out of order on the page
            return $output;
        }
        else {
            echo $before;
            $first = true;
            while ($this->dataIterator->nextRow()) {
                foreach ($this->dataIterator->getDisplayColumns() as $col) {
                    if ($first) {
                        $first = false;
                    }
                    else {
                        echo $delimiter;
                    }
                    echo  $this->dataIterator->row[$col];
                }
            }
            echo $after;
        }
    }
}
