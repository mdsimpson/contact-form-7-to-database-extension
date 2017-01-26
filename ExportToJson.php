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

class ExportToJson extends ExportBase implements CFDBExport {

    public function export($formName, $options = null) {
        $this->setOptions($options);
        $this->setCommonOptions();

        $varName = 'cf7db';
        $html = false; // i.e. whether to create an HTML script tag and Javascript variable

        if ($options && is_array($options)) {

            if (isset($options['html'])) {
                $html = $options['html'];
            }

            if (isset($options['var'])) {
                $varName = $options['var'];
            }
        }

        // Security Check
        if (!$this->isAuthorized()) {
            $this->assertSecurityErrorMessage();
            return;
        }

        // Headers
        $contentType = $html ? 'Content-Type: text/html; charset=UTF-8' : 'Content-Type: application/json; charset=UTF-8';
        $this->echoHeaders($contentType);

        // Get the data
        $this->setDataIterator($formName);
        //$this->clearAllOutputBuffers();

        if ($this->isFromShortCode) {
            ob_start();
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
            // Allow for short codes in "before"
            echo do_shortcode($before);
        }

        if ($html) {
            ?>
            <script type="text/javascript" language="JavaScript">
                var <?php echo $varName; ?> = <?php $this->echoJsonEncode(); ?>;
            </script>
            <?php

        }
        else {
            echo $this->echoJsonEncode();
        }

        if ($after) {
            // Allow for short codes in "after"
            echo do_shortcode($after);
        }

        if ($this->isFromShortCode) {
            // If called from a shortcode, need to return the text,
            // otherwise it can appear out of order on the page
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        }
    }

    protected function echoJsonEncode() {
        $format = 'map';
        if ($this->options && isset($this->options['format'])) {
            if ($this->options['format'] == 'array' || $this->options['format'] == 'arraynoheader') {
                $format = $this->options['format'];
                if (isset($this->options['header']) && $this->options['header'] == 'false') {
                    // Another way to turn off the header
                    $format = 'arraynoheader';
                }
            }
        }

        if ($format == 'map') {

            // Create the column name JSON values only once
            $jsonEscapedColumns = array();
            foreach ($this->dataIterator->getDisplayColumns() as $col) {
                $colDisplayValue = $col;
                if ($this->headers && isset($this->headers[$col])) {
                    $colDisplayValue = $this->headers[$col];
                }
                $jsonEscapedColumns[$col] = json_encode($colDisplayValue);
            }

            echo "[\n";
            $firstRow = true;
            while ($this->dataIterator->nextRow()) {
                if ($firstRow) {
                    $firstRow = false;
                }
                else {
                    echo ",\n";
                }
                echo '{';
                $firstCol = true;
                foreach ($this->dataIterator->getDisplayColumns() as $col) {
                    if ($firstCol) {
                        $firstCol = false;
                    }
                    else {
                        echo ',';
                    }
                    printf('%s:%s',
                            $jsonEscapedColumns[$col],
                            json_encode(
                                    (isset($this->dataIterator->row[$col]) ?
                                            $this->dataIterator->row[$col] :
                                            '')));
                }
                echo '}';
            }
            echo "\n]";
        }
        else { // 'array' || 'arraynoheader'
            echo "[\n";
            $firstRow = true;
            if ($format == 'array' ||
                    // allow header option to override
                    (isset($this->options['header']) && $this->options['header'] == 'true')) {
                // Add header
                $firstCol = true;
                echo '[';
                foreach ($this->dataIterator->getDisplayColumns() as $col) {
                    if ($firstCol) {
                        $firstCol = false;
                    }
                    else {
                        echo ',';
                    }
                    $colDisplayValue = $col;
                    if ($this->headers && isset($this->headers[$col])) {
                        $colDisplayValue = $this->headers[$col];
                    }
                    echo json_encode($colDisplayValue);
                }
                echo ']';
                $firstRow = false;
            }
            // Export data rows
            while ($this->dataIterator->nextRow()) {
                if ($firstRow) {
                    $firstRow = false;
                }
                else {
                    echo ",\n";
                }
                $firstCol = true;
                echo '[';
                foreach ($this->dataIterator->getDisplayColumns() as $col) {
                    if ($firstCol) {
                        $firstCol = false;
                    }
                    else {
                        echo ',';
                    }
                    echo json_encode(isset($this->dataIterator->row[$col]) ?
                            $this->dataIterator->row[$col] :
                            '');
                }
                echo "]";
            }
            echo "\n]";
        }
    }

}
