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

class CFDBWpdbUnbufferedResultIterator extends CFDBAbstractQueryResultsIterator {

    /**
     * @var resource|mysqli_result
     */
    var $mysqlResults;

    /**
     * @var boolean
     */
    var $useMysqli;

    /**
     * @var bool
     */
    var $debug = false;

    /**
     * Doesn't work right if we use the global $wpdb,
     * so this class constructs its own instance
     * @var wpdb
     */
    var $wpdb;


    /**
     * Execute the query
     * @param $sql string query
     * @param $queryOptions array associative
     * @return void
     */
    public function queryDataSource(&$sql, $queryOptions) {
        $this->wpdb = new wpdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);

        $this->debug = WP_DEBUG ||
                (is_array($queryOptions) &&
                        isset($queryOptions['debug']) &&
                        $queryOptions['debug'] == true);

        $this->useMysqli = $this->shouldUseMySqli();

        if ($this->useMysqli) {
            if ($this->debug) {
                $this->mysqlResults = mysqli_query($this->wpdb->dbh, $sql, MYSQLI_USE_RESULT);
            } else {
                $this->mysqlResults = @mysqli_query($this->wpdb->dbh, $sql, MYSQLI_USE_RESULT);
            }
        } else {
            if ($this->debug) {
                $this->mysqlResults = mysql_unbuffered_query($sql, $this->wpdb->dbh);
            } else {
                $this->mysqlResults = @mysql_unbuffered_query($sql, $this->wpdb->dbh);
            }
        }
    }

    /**
     * Get the next row from query results
     * @return array|null|false (array is associative)
     */
    public function fetchRow() {
        $row = null;
        if ($this->mysqlResults) {
            if ($this->useMysqli) {
                if ($this->debug) {
                    // returns array|null
                    $row = mysqli_fetch_assoc($this->mysqlResults);
                } else {
                    // returns array|null
                    $row = @mysqli_fetch_assoc($this->mysqlResults);
                }
            } else {
                if ($this->debug) {
                    // returns array|false
                    $row = mysql_fetch_assoc($this->mysqlResults);
                } else {
                    // returns array|false
                    $row = @mysql_fetch_assoc($this->mysqlResults);
                }
            }
        }
        return $row;
    }

    /**
     * If you do not iterate over all the rows returned, be sure to call this function
     * on all remaining rows to free resources.
     * @return void
     */
    public function freeResult() {
        if ($this->mysqlResults) {
            if ($this->useMysqli) {
                if ($this->debug) {
                    mysqli_free_result($this->mysqlResults);
                } else {
                    @mysqli_free_result($this->mysqlResults);
                }
            } else {
                if ($this->debug) {
                    mysql_free_result($this->mysqlResults);
                } else {
                    @mysql_free_result($this->mysqlResults);
                }
            }
            $this->mysqlResults = null;
            $this->wpdb->flush();
        }
    }


    public function shouldUseMySqli() {
        // This code taken from wp-db.php and adapted
        // Had to add this here because $wpdb->use_mysqli is private/inaccessible
        $use_mysqli = false;
        if (function_exists('mysqli_connect')) {
            if (defined('WP_USE_EXT_MYSQL')) {
                $use_mysqli = !WP_USE_EXT_MYSQL;
            } elseif (version_compare(phpversion(), '5.5', '>=') || !function_exists('mysql_connect')) {
                $use_mysqli = true;
            } elseif (false !== strpos($GLOBALS['wp_version'], '-')) {
                $use_mysqli = true;
            }
        }
        return $use_mysqli;
    }

}