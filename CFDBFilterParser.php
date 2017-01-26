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

include_once('CFDBEvaluator.php');
require_once('CFDBParserBase.php');

/**
 * Used to parse boolean expression strings like 'field1=value1&&field2=value2||field3=value3&&field4=value4'
 * Where logical AND and OR are represented by && and || respectively.
 * Individual expressions (like 'field1=value1') are of the form $name . $operator . $value where
 * $operator is any PHP comparison operator or '=' which is interpreted as '=='.
 * $value has a special case where if it is 'null' it is interpreted as the value null
 */
class CFDBFilterParser extends CFDBParserBase implements CFDBEvaluator {

    /**
     * @var array of arrays of string where the top level array is broken down on the || delimiters
     */
    var $tree;


    public function hasFilters() {
        return count($this->tree) > 0; // count is null-safe
    }

    public function getFilterTree() {
        return $this->tree;
    }

    /**
     * Parse a string with delimiters || and/or && into a Boolean evaluation tree.
     * For example: aaa&&bbb||ccc&&ddd would be parsed into the following tree,
     * where level 1 represents items ORed, level 2 represents items ANDed, and
     * level 3 represent individual expressions.
     * Array
     * (
     *     [0] => Array
     *         (
     *             [0] => Array
     *                 (
     *                     [0] => aaa
     *                     [1] => =
     *                     [2] => bbb
     *                 )
     *
     *         )
     *
     *     [1] => Array
     *         (
     *             [0] => Array
     *                 (
     *                     [0] => ccc
     *                     [1] => =
     *                     [2] => ddd
     *                 )
     *
     *             [1] => Array
     *                 (
     *                     [0] => eee
     *                     [1] => =
     *                     [2] => fff
     *                 )
     *
     *         )
     *
     * )
     * @param  $filterString string with delimiters && and/or ||
     * which each element being an array of strings broken on the && delimiter
     */
    public function parse($filterString) {
        $this->tree = array();
        $arrayOfORedStrings = $this->parseORs($filterString);
        foreach ($arrayOfORedStrings as $anANDString) {
            $arrayOfANDedStrings = $this->parseANDs($anANDString);
            $andSubTree = array();
            foreach ($arrayOfANDedStrings as $anExpressionString) {
                $exprArray = $this->parseExpression($anExpressionString);
                $count = count($exprArray);
                if ($count > 0) {
                    $exprArray[0] = $this->parseValidFunction($exprArray[0]);
                    if ($count > 2) {
                        $exprArray[2] = $this->parseValidFunction($exprArray[2]);
                    } else if ($count > 1) {
                        // e.g. "field=" which can happen if it was "field=$_POST(field)" with no $_POST['field'] set
                        $exprArray[2] = null;
                    } else {
                        // Case of "function()" parse as if "function()==true"
                        $exprArray[1] = '==';
                        $exprArray[2] = true;
                    }

                    // if one side of the operation is a function and the other is 'true' or 'false'
                    // then convert to Boolean true or false which signals to not try to dereference
                    // true or false during evaluateComparison()
                    if (is_array($exprArray[0])) {
                        if ($exprArray[2] === 'true') {
                            $exprArray[2] = true;
                        } else if ($exprArray[2] === 'false') {
                            $exprArray[2] = false;
                        }
                    }
                    if (is_array($exprArray[2])) {
                        if ($exprArray[0] === 'true') {
                            $exprArray[0] = true;
                        }
                        if ($exprArray[0] === 'false') {
                            $exprArray[0] = false;
                        }
                    }
                }
                $andSubTree[] = $exprArray;
            }
            $this->tree[] = $andSubTree;
        }
    }

    /**
     * Parse a comparison expression into its three components
     * @param  $comparisonExpression string in the form 'value1' . 'operator' . 'value2' where
     * operator is a php comparison operator or '='
     * @return array of string [ value1, operator, value2 ]
     */
    public function parseExpression($comparisonExpression) {
        // Sometimes get HTML codes for greater-than and less-than; replace them with actual symbols
        $comparisonExpression = str_replace('&gt;', '>', $comparisonExpression);
        $comparisonExpression = str_replace('&lt;', '<', $comparisonExpression);
        return preg_split('/(===)|(==)|(=)|(!==)|(!=)|(<>)|(<=)|(<)|(>=)|(>)|(~~)|(\[in\])|(\[!in\])/',
                $comparisonExpression, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    }

    /**
     * Evaluate expression against input data. Assumes parse was called to set up the expression to
     * evaluate. Expression should have key . operator . value tuples and input $data should have the same keys
     * with values to check against them.
     * For example, an expression in this object is 'name=john' and the input data has [ 'name' => 'john' ]. In
     * this case true is returned. if $data has [ 'name' => 'fred' ] then false is returned.
     * @param  $data array [ key => value]
     * @return boolean result of evaluating $data against expression tree
     */
    public function evaluate(&$data) {
        $this->setTimezone();

        $retVal = true;
        if ($this->tree) {
            $retVal = false;
            foreach ($this->tree as $andArray) { // loop each OR'ed $andArray
                $andBoolean = true;
                // evaluation the list of AND'ed comparison expressions
                foreach ($andArray as $comparison) {
                    $andBoolean = $this->evaluateComparison($comparison, $data); //&& $andBoolean
                    if (!$andBoolean) {
                        break; // short-circuit AND expression evaluation
                    }
                }
                $retVal = $retVal || $andBoolean;
                if ($retVal) {
                    break; // short-circuit OR expression evaluation
                }
            }
        }
        return $retVal;
    }

    public function evaluateComparison($andExpr, &$data) {
        if (is_array($andExpr) && count($andExpr) == 3) {
            // $andExpr = [$left $op $right]

            // Left operand
            $left = $andExpr[0];
            // Boolean type means it was set in parse in response
            // to a filter like "function(x)" that was turned into an expression
            // like "function(x) === true"
            if ($left !== true && $left !== false) {
                if (is_array($left)) { // function call
                    $left = $this->functionEvaluator->evaluateFunction($left, $data);
                } else {
                    $left = $this->functionEvaluator->preprocessValues($left);
                    // Dereference $left assuming it is the name of a form field
                    // and set it to the value of the field. When not found make it null
                    $left = isset($data[$left]) ? $data[$left] : null;
                }
            }

            // Operator
            $op = $andExpr[1];

            // Right operand
            $right = $andExpr[2];
            if (is_array($right)) { // function call
                $right = $this->functionEvaluator->evaluateFunction($right, $data);
            } else {
                $right = $this->functionEvaluator->preprocessValues($right);
            }

            if ($andExpr[0] === 'submit_time') {
                if (!is_numeric($right)) {
                    $tmp = strtotime($right);
                    if ($tmp) {
                        $right = $tmp;
                    }
                }
            }

            if ($left === null && $right === null) {
                // Addresses case where 'Submitted Login' = $user_login but there exist some submissions
                // with no 'Submitted Login' field. Without this clause, those rows where 'Submitted Login' == null
                // would be returned when what we really want to is affirm that there is a 'Submitted Login' value ($left)
                // But we want to preserve the correct behavior for the case where 'field'=null is the constraint.
                return false;
            }
            return $this->evaluateLeftOpRightComparison($left, $op, $right);
        }
        return false;
    }

    /**
     * @param  $left mixed
     * @param  $operator string representing any PHP comparison operator or '=' which is taken to mean '=='
     * @param  $right $mixed. SPECIAL CASE: if it is the string 'null' it is taken to be the value null
     * @return bool evaluation of comparison $left $operator $right
     */
    public function evaluateLeftOpRightComparison($left, $operator, $right) {
        if ($right === 'null') {
            // special case
            $right = null;
        }

        // Try to do numeric comparisons when possible
        if (is_numeric($left) && is_numeric($right)) {
            $left = (float)$left;
            $right = (float)$right;
        }

        // Could do this easier with eval() but since this text ultimately
        // comes form a shortcode's user-entered attributes, I want to avoid a security hole
        $retVal = false;
        switch ($operator) {
            case '=' :
            case '==':
                $retVal = $left == $right;
                break;

            case '===':
                $retVal = $left === $right;
                break;

            case '!=':
                $retVal = $left != $right;
                break;

            case '!==':
                $retVal = $left !== $right;
                break;

            case '<>':
                $retVal = $left <> $right;
                break;

            case '>':
                $retVal = $left > $right;
                break;

            case '>=':
                $retVal = $left >= $right;
                break;

            case '<':
                $retVal = $left < $right;
                break;

            case '<=':
                $retVal = $left <= $right;
                break;

            case '~~':
                $retVal = @preg_match($right, $left) > 0;
                break;

            case '[in]':
                $retVal = in_array($left, explode(',', $right));
                break;

            case '[!in]':
                $retVal = !in_array($left, explode(',', $right));
                break;

            default:
                trigger_error("Invalid operator: '$operator'", E_USER_NOTICE);
                break;
        }

        return $retVal;
    }

}
