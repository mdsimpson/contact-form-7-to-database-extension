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

class CFDBWpdbResultIterator extends CFDBAbstractQueryResultsIterator {

    /**
     * @var array[associative array]
     */
    var $wpdbResults;

    /**
     * @var int
     */
    var $wpdbIdx = 0;

    /**
     * @var int
     */
    var $wpdbLen = 0;

    /**
     * Execute the query
     * @param $sql string query
     * @param $queryOptions array associative
     * @return void
     */
    public function queryDataSource(&$sql, $queryOptions) {
        global $wpdb;
        $this->wpdbResults =
                $wpdb->get_results($sql, ARRAY_A);
        $this->wpdbLen = $wpdb->num_rows;
    }

    /**
     * Get the next row from query results
     * @return array associative
     */
    public function fetchRow() {
        if ($this->wpdbIdx < $this->wpdbLen) {
            return $this->wpdbResults[$this->wpdbIdx++];
        }
        return false;
    }

    /**
     * If you do not iterate over all the rows returned, be sure to call this function
     * on all remaining rows to free resources.
     * @return void
     */
    public function freeResult() {
        global $wpdb;
        $wpdb->flush();
    }
}