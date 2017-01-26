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

require_once('CFDBParserBase.php');
require_once('CFDBTransformByFunctionIterator.php');
require_once('CFDBTransformByClassIterator.php');
require_once('CFDBTransformEndpoint.php');

// Includes these just to have them as known classes in case they are in the transform.
require_once('SortByField.php');
require_once('SortByMultiField.php');
require_once('NaturalSortByField.php');
require_once('NaturalSortByMultiField.php');
require_once('SortByDateField.php');
require_once('SummationRow.php');

require_once('CountField.php');
require_once('CountInField.php');
require_once('DefaultField.php');
require_once('SplitField.php');
require_once('SumField.php');
require_once('MinField.php');
require_once('MaxField.php');
require_once('AverageField.php');
require_once('TotalField.php');
require_once('AddRowNumberField.php');

require_once('cfdb-transform-functions.php');

class CFDBTransformParser extends CFDBParserBase {

    var $tree = array();

    /**
     * @var array[CFDBDataIterator]
     */
    var $transformIterators = array();


    public function getExpressionTree() {
        return $this->tree;
    }

    public function parse($string) {
        $arrayOfANDedStrings = $this->parseANDs($string); // e.g. "xx=yy()&&zz()" -> ["xx=yy(a,b,c)", "zz"]
        foreach ($arrayOfANDedStrings as $expressionString) {
            $rawExpression = $this->parseExpression(trim($expressionString)); // e.g. ["xx" "=" "yy(a,b,c)"] or ["zz"]
            if (empty($rawExpression)) {
                continue;
            }
            $expression = array();
            $function = null;
            if (count($rawExpression) >= 3) { // e.g. ["xx" "=" "yy(a,b,c)"]
                $expression[] = trim($rawExpression[0]); // field name
                $expression[] = trim($rawExpression[1]); // =
                $function = trim($rawExpression[2]); // function call
            } else {
                $function = trim($rawExpression[0]); // function call
            }
            $function = $this->parseValidFunctionOrClassTransform($function); // ["zz(a,b,c)"] -> ["zz", "a", "b", "c"]
            if (is_array($function)) {
                $expression = array_merge($expression, $function);
            } else {
                $expression[] = $function;
            }
            $this->tree[] = $expression;
        }
    }


    /**
     * Parse a comparison expression into its three components
     * @param  $comparisonExpression string in the form 'value1' . 'operator' . 'value2' where
     * operator is a php comparison operator or '='
     * @return array of string [ value1, operator, value2 ]
     */
    public function parseExpression($comparisonExpression) {
        return preg_split('/(=)/', $comparisonExpression, -1,
                PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    }


    public function setupTransforms() {
        if ($this->tree) {
            /** @var $previousTransformIterator CFDBDataIteratorDecorator */
            $previousTransformIterator = null;
            foreach ($this->tree as $transformArray) {
                // [field, =, func, a1, a2, ...] or [func] or [class] or [class, a1, a2, ...]
                $transform = null;
                if (!empty($transformArray)) {
                    $transform = null;
                    if (class_exists($transformArray[0])) {
                        $reflect = new ReflectionClass($transformArray[0]);
                        $args = array_slice($transformArray, 1);
                        $instance = $reflect->newInstanceArgs($args);
                        $transform = new CFDBTransformByClassIterator();
                        /** @var $instance CFDBTransform */
                        $transform->setTransformObject($instance);
                    } else {
                        // assume it is a function
                        $transform = new CFDBTransformByFunctionIterator();
                        $transform->setFunctionEvaluator($this->functionEvaluator);
                        if (count($transformArray) > 1 && $transformArray[1] == '=') {
                            // [field_name, =, function_name, arg1, arg2, ...]
                            $transform->setFieldToAssign($transformArray[0]);
                            $transform->setFunctionArray(array_slice($transformArray, 2));
                        } else {
                            // [function_name, arg1, arg2, ...]
                            $transform->setFunctionArray($transformArray);
                        }
                    }
                    // Set the data source for each transform as the previous transform
                    // to set up a pipeline/decorator pattern.
                    // The first transform is left with null data source to be hooked up later
                    // to query results.
                    $transform->setSource($previousTransformIterator); // is null for first one
                    $previousTransformIterator = $transform;
                    $this->transformIterators[] = $transform;
                }
            }
            if ($previousTransformIterator) {
                // Stick a CFDBTransformEndpoint at the end of the list of transforms
                $transform = new CFDBTransformEndpoint();
                $transform->setSource($previousTransformIterator);
                $this->transformIterators[] = $transform;
            }
        }
    }

    /**
     * @param $dataSource CFDBDataIterator
     */
    public function setDataSource($dataSource) {
        if (count($this->transformIterators) > 0) {
            $this->transformIterators[0]->setSource($dataSource);
        }
    }

    /**
     * @return CFDBDataIteratorDecorator
     */
    public function getIterator() {
        return end($this->transformIterators);
    }

}