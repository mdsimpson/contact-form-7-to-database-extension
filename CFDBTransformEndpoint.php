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

require_once('CFDBAbstractQueryResultsIterator.php');
require_once('CFDBDataIteratorDecorator.php');

class CFDBTransformEndpoint extends CFDBDataIteratorDecorator {

    /**
     * @var CFDBTransformEndpointQueryResultsIterator
     */
    var $postProcessor;

    function __construct() {
       $this->postProcessor = new CFDBTransformEndpointQueryResultsIterator($this);
    }

    /**
     * Fetch next row into variable
     * @return bool if next row exists
     */
    public function nextRow() {
        if ($this->postProcessor->nextRow()) {
            $this->row =& $this->source->row;
            if (empty($this->displayColumns)) {
                $this->displayColumns =& $this->source->displayColumns;
            }
            return true;
        } else {
            $this->row = null;
            return false;
        }
    }

    /**
     * @return CFDBTransformEndpointQueryResultsIterator
     */
    public function getPostProcessor() {
        return $this->postProcessor;
    }
}


class CFDBTransformEndpointQueryResultsIterator extends CFDBAbstractQueryResultsIterator {

    /**
     * @var CFDBTransformEndpoint
     */
    var $endPoint;

    function __construct($endPoint) {
        $this->endPoint = $endPoint;
    }

    /**
     * Execute the query
     * @param $sql string query
     * @param $queryOptions array associative
     * @return void
     */
    public function queryDataSource(&$sql, $queryOptions) {
        // Do nothing. Data is in $this->$endPoint->source
    }

    /**
     * Get the next row from query results
     * @return array associative
     */
    public function fetchRow() {
        if($this->endPoint->source->nextRow()) {
            return $this->endPoint->source->row;
        }
        return null;
    }

    /**
     * @return boolean
     */
    public function hasResults() {
        // this is called by nextRow() in superclass
        // return true and let next row sort it out
        return true;
    }

    /**
     * If you do not iterate over all the rows returned, be sure to call this function
     * on all remaining rows to free resources.
     * @return void
     */
    public function freeResult() {
        // Do nothing
    }
}