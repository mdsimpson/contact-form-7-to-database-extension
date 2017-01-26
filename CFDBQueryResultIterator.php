<?php
/*
    "Contact Form to Database" Copyright (C) 2011-2013 Michael Simpson  (email : michael.d.simpson@gmail.com)

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

class CFDBQueryResultIterator extends CFDBAbstractQueryResultsIterator {

    /**
     * @var resource|mysqli_result
     */
    var $results;

    /**
     * @var boolean
     */
    var $useMysqli;


    /**
     * If you do not iterate over all the rows returned, be sure to call this function
     * on all remaining rows to free resources.
     * @return void
     */
    public function freeResult() {
        if ($this->results) {
            if ($this->useMysqli) {
                mysqli_free_result($this->results);
            } else {
                mysql_free_result($this->results);
            }
            $this->results = null;
        }
    }
    /**
     * @return array associative
     */
    public function fetchRow() {
        if ($this->useMysqli) {
            return mysqli_fetch_assoc($this->results);
        } else {
            return mysql_fetch_assoc($this->results);
        }
    }

    public function hasResults() {
        return !empty($this->results);
    }

    /**
     * @param $sql
     * @param $queryOptions
     * @return void
     */
    public function queryDataSource(&$sql, $queryOptions) {
        // For performance reasons, we bypass $wpdb so we can call mysql_unbuffered_query

        $this->useMysqli = $this->shouldUseMySqli();

        $con = null;
        if ($this->useMysqli) {
            $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            if (!$con) {
                trigger_error("MySQL Connection failed: " . mysqli_error($con), E_USER_NOTICE);
                return;
            }
        } else {
            $con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD, true);
            if (!$con) {
                trigger_error("MySQL Connection failed: " . mysql_error($con), E_USER_NOTICE);
                return;
            }
        }

        // Target charset is in wp-config.php DB_CHARSET
        if (defined('DB_CHARSET')) {
            if (DB_CHARSET != '') {
                global $wpdb;
                if (method_exists($wpdb, 'set_charset')) {
                    $collate = null;
                    if (defined('DB_COLLATE')) {
                        if (DB_COLLATE != '') {
                            $collate = DB_COLLATE;
                        }
                    }
                    $wpdb->set_charset($con, DB_CHARSET, $collate);
                } else {
                    $setCharset = 'SET NAMES \'' . DB_CHARSET . '\'';
                    if (defined('DB_COLLATE')) {
                        if (DB_COLLATE != '') {
                            $setCharset = $setCharset . ' COLLATE \'' . DB_COLLATE . '\'';
                        }
                    }
                    if ($this->useMysqli) {
                        mysqli_query($con, $setCharset);
                    } else {
                        mysql_query($setCharset, $con);
                    }
                }
            }
        }

        if (!$this->useMysqli) {
            if (!mysql_select_db(DB_NAME, $con)) {
                trigger_error('MySQL DB Select failed: ' . mysql_error(), E_USER_NOTICE);
                return;
            }
        }

        if (isset($queryOptions['unbuffered']) && $queryOptions['unbuffered'] === 'true') {
            // FYI: using mysql_unbuffered_query disrupted nested shortcodes if the nested one does a query also
            if ($this->useMysqli) {
                $this->results = mysqli_query($con, $sql, MYSQLI_USE_RESULT);
                if (!$this->results) {
                    trigger_error('mysqli_query failed: ' . mysql_error(), E_USER_NOTICE);
                    return;
                }
            } else {
                $this->results = mysql_unbuffered_query($sql, $con);
                if (!$this->results) {
                    trigger_error('mysql_unbuffered_query failed: ' . mysql_error(), E_USER_NOTICE);
                    return;
                }
            }
        } else {
            if ($this->useMysqli) {
                $this->results = @mysqli_query($con, $sql);
                if (!$this->results) {
                    trigger_error('mysqli_query failed. Try adding <code>unbuffered="true"</code> to your short code. <br/>' . mysql_error(), E_USER_WARNING);
                    return;
                }
            } else {
                $this->results = @mysql_query($sql, $con);
                if (!$this->results) {
                    trigger_error('mysql_query failed. Try adding <code>unbuffered="true"</code> to your short code. <br/>' . mysql_error(), E_USER_WARNING);
                    return;
                }
            }
        }
    }

    public function shouldUseMySqli() {
        // This code taken from wp-db.php and adapted
        $use_mysqli = false;
        if ( function_exists( 'mysqli_connect' ) ) {
            if ( defined( 'WP_USE_EXT_MYSQL' ) ) {
                $use_mysqli = ! WP_USE_EXT_MYSQL;
            } elseif ( version_compare( phpversion(), '5.5', '>=' ) || ! function_exists( 'mysql_connect' ) ) {
                $use_mysqli = true;
            } elseif ( false !== strpos( $GLOBALS['wp_version'], '-' ) ) {
                $use_mysqli = true;
            }
        }
        return $use_mysqli;
    }

}
