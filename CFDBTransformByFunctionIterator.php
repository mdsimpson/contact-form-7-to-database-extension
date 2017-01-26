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

require_once('CFDBDataIteratorDecorator.php');

class CFDBTransformByFunctionIterator extends CFDBDataIteratorDecorator {

    /**
     * @var array
     */
    var $functionArray;

    /**
     * @var CFDBFunctionEvaluator
     */
    var $functionEvaluator;

    /**
     * @var string name of field to assign the returned value of the function
     */
    var $fieldToAssign;

    /**
     * @param array $functionArray [function_name, arg1, arg2, ...]
     */
    public function setFunctionArray($functionArray) {
        $this->functionArray = $functionArray;
    }

    /**
     * @param $functionEvaluator CFDBFunctionEvaluator
     */
    public function setFunctionEvaluator($functionEvaluator) {
        $this->functionEvaluator = $functionEvaluator;
    }

    /**
     * @param string $fieldToAssign
     */
    public function setFieldToAssign($fieldToAssign) {
        $this->fieldToAssign = $fieldToAssign;
    }

    /**
     * Fetch next row into variable
     * @return bool if next row exists
     */
    public function nextRow() {
        if ($this->source->nextRow()) {
            $this->row =& $this->source->row;
            if (empty($this->displayColumns) && !empty($this->source->displayColumns)) {
                $this->displayColumns = $this->source->displayColumns;
            }
            $origKeys = array_keys($this->row);
            $functionReturn = $this->functionEvaluator->evaluateFunction($this->functionArray, $this->row);
            if ($this->fieldToAssign) {
                $this->source->row[$this->fieldToAssign] = $functionReturn;
                if (!in_array($this->fieldToAssign, $this->displayColumns)) {
                    $this->displayColumns[] = $this->fieldToAssign;
                }
            } else if ($functionReturn === null || is_array($functionReturn)) {
                // $functionReturn when a reference was passed in and row may be modified

                // New row returned
                if (is_array($functionReturn)) {
                    // function returns new array for the entry
                    $this->source->row = $functionReturn;
                    $this->row =& $this->source->row;
                }

                // Reconcile display columns

                // 1. Check for the addition of new columns to add to displays
                $newFieldsSeen = array();
                $newKeys = array_keys($this->row);
                $addedKeys = array_diff($newKeys, $origKeys);
                if (!empty($addedKeys)) {
                    foreach ($addedKeys as $add) {
                        if (!in_array($add, $this->displayColumns)) {
                            $this->displayColumns[] = $add;
                            $newFieldsSeen[] = $add;
                        }
                    }
                }

                // 2. Remove display columns that no longer exist
                $updatedDisplays = array();
                foreach($this->displayColumns as $aDisplay) {
                    if (in_array($aDisplay, $newKeys) || in_array($aDisplay, $newFieldsSeen)) {
                        $updatedDisplays[] = $aDisplay;
                    }
                }
                $this->displayColumns = $updatedDisplays;
            }
            return true;
        }
        return false;
    }

    public function getDisplayColumns() {
        if (empty($this->displayColumns)) {
            $cols = $this->source->getDisplayColumns();
            if ($this->fieldToAssign && !in_array($this->fieldToAssign, $cols)) {
                $cols[] = $this->fieldToAssign;
                return $cols;
            }
        }
        return $this->displayColumns;
    }

}