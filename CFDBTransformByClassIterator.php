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

//require_once('CFDBTransform.php');
require_once('CFDBDataIteratorDecorator.php');

class CFDBTransformByClassIterator extends CFDBDataIteratorDecorator {

    /**
     * @var CFDBTransform
     */
    var $transformObject;

    /**
     * @var array[array[name=>value], ...] transformed data set
     */
    var $transformedData;

    /**
     * @var int
     */
    var $count;

    /**
     * @var int
     */
    var $idx;

    /**
     * @param $transformObject CFDBTransform interface but allow for duck-typing
     */
    public function setTransformObject($transformObject) {
        $this->transformObject = $transformObject;
    }

    /**
     * Fetch next row into variable
     * @return bool if next row exists
     */
    public function nextRow() {
        if (!$this->transformedData) {
            $this->initData();
            $this->idx = 0;
            return $this->count > 0;
        } else {
            if (++$this->idx < $this->count) {
                $this->row =& $this->transformedData[$this->idx];
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @return bool
     */
    protected function initData() {
        if ($this->transformedData) {
            return; // Already initialized
        }

        // Loop the entire $source data set and transform it.
        while ($this->source->nextRow()) {
            $this->transformObject->addEntry($this->source->row);
        }

        // Transform the data
        $this->transformedData = $this->transformObject->getTransformedData();

        // Init count for iteration
        $this->count = count($this->transformedData);
        if ($this->count > 0) {
            $this->idx = -1; // nextRow will ++ it
            $this->row =& $this->transformedData[0];
        }
    }

    public function getDisplayColumns() {
        if (empty($this->displayColumns)) {
            $sourceDisplayCols = parent::getDisplayColumns(); // gets form source transform
            $this->fixDisplayColumns($sourceDisplayCols);
            return $this->displayColumns;
        }
        return $this->displayColumns;
    }

    protected function fixDisplayColumns($sourceDisplayCols) {

        if (empty($this->transformedData)) {
            $this->initData();
        }

        if (!empty($this->displayColumns)) {
            return;
        }

        $dataCols = null;
        if ($this->transformedData == null ||
                !isset($this->transformedData[0]) ||
                !is_array($this->transformedData[0])) {
            $dataCols = array();
        } else {
            $dataCols = array_keys($this->transformedData[0]);
        }
        $newDisplayColumns = array();

        foreach ($sourceDisplayCols as $col) {
            if (in_array($col, $dataCols)) {
                $newDisplayColumns[] = $col;
            }
        }

        // Ignore metadata columns for purposes of determining display columns)
        $metadataCols = array('fields_with_file', 'submit_time', 'Submit_Time_Key');
        foreach ($dataCols as $col) {
            if (!in_array($col, $metadataCols) && !in_array($col, $newDisplayColumns)) {
                $newDisplayColumns[] = $col;
            }
        }

        $this->displayColumns = $newDisplayColumns;
    }

}