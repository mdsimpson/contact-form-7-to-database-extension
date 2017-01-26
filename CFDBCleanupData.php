<?php
/*
    "Contact Form to Database" Copyright (C) 2011-2015 Michael Simpson  (email : michael.d.simpson@gmail.com)

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

require_once('CF7DBPlugin.php');

class CFDBCleanupData {

    /**
     * @var CF7DBPlugin
     */
    var $plugin;

    /**
     * @param $plugin CF7DBPlugin
     */
    function __construct($plugin) {
        $this->plugin = $plugin;
    }

    /**
     * Fix entries from different forms with same submit_time
     * @return int number of items fixed in the DB
     */
    public function cleanupForms() {
        global $wpdb;

        $table = $this->plugin->getSubmitsTableName();
        $sql = sprintf('select * from (select submit_time, count(form_name) as count
        from (
            select distinct submit_time, form_name from %s) t group by submit_time
        ) u  where count > 1', $table);
        $results = $wpdb->get_results($sql, ARRAY_A);
        //print_r($results); // debug
        if (!$results) {
            return 0;
        }

        $stSql = "select distinct submit_time, form_name from $table where submit_time = %F";
        $inDBSql = "select count(submit_time) from $table where submit_time = %F";
        $updateSql = "update $table set submit_time = %F where submit_time = %F and form_name = %s";
        $count = 0;
        foreach ($results as $row) {
            $stResults = $wpdb->get_results($wpdb->prepare($stSql, $row['submit_time']), ARRAY_A);
            $idx = 0;
            foreach ($stResults as $stResult) {
                if ($idx++ == 0) {
                    continue;
                }
                $newST = $stResult['submit_time'];
                while (true) {
                    $newST = $newST + 0.0001; // Get new submit time
                    $inDbAlready = $wpdb->get_var($wpdb->prepare($inDBSql, $newST));
                    if (!$inDbAlready) {
                        $wpdb->query($wpdb->prepare($updateSql, $newST, $stResult['submit_time'], $stResult['form_name']));
                        ++$count;
                        break;
                    }
                }
            }
        }
        return $count;
    }


    public function deleteEmptyEntries() {
        $table = $this->plugin->getSubmitsTableName();
        global $wpdb;
        return $wpdb->query("DELETE FROM $table WHERE field_name = '' AND field_value = ''");
    }

    /**
     * Fix entries in the same form with duplicate submit_time values
     * return int
     */
    public function cleanupEntries() {
        $count = 0;
        $table = $this->plugin->getSubmitsTableName();
        $dupSql =
                "SELECT DISTINCT b.submit_time FROM (
        SELECT a.submit_time, a.form_name, a.field_name, count(a.field_value) AS count
        FROM $table a
        GROUP BY a.submit_time, a.submit_time, a.form_name, a.field_name) b
        WHERE b.count > 1";

        $deleteWithFieldOrder = "DELETE FROM $table WHERE submit_time = %F AND form_name = %s AND field_name = %s AND field_value = %s AND field_order = %d LIMIT 1";
        $deleteNoFieldOrder = "DELETE FROM $table WHERE submit_time = %F AND form_name = %s AND field_name = %s AND field_value = %s AND field_order IS NULL LIMIT 1";

        $updateWithFieldOrder = "UPDATE $table SET submit_time = %F WHERE submit_time = %F AND form_name = %s AND field_name = %s AND field_value = %s AND field_order = %d LIMIT 1";
        $updateNoFieldOrder = "UPDATE $table SET submit_time = %F WHERE submit_time = %F AND form_name = %s AND field_name = %s AND field_value = %s AND field_order IS NULL LIMIT 1";

        $submitTimes = array();
        global $wpdb;
        $results = $wpdb->get_results($dupSql, ARRAY_A);
        foreach ($results as $row) {
            $submitTimes[] = $row['submit_time'];
        }

        $stSql = "SELECT * FROM $table WHERE submit_time = %F";
        foreach ($submitTimes as $st) {
            $data = array();
            $entryNum = 0;
            $data[$entryNum] = array();
            $results = $wpdb->get_results($wpdb->prepare($stSql, $st));
            foreach ($results as $row) {
                foreach ($data[$entryNum] as $entry) {
                    if ($entry->field_name == $row->field_name) {
                        $entryNum++;
                        break;
                    }
                }
                $data[$entryNum][] = $row;
            }

            foreach ($data as $idx => $entry) {
                //print "\n\n";
                //print_r($entry); // debug

                $diff = false;
                if ($idx > 0) {
                    // If the entries are identical, delete one.
                    foreach ($entry as $field) {
                        $diff = false;
                        foreach($data[0] as $firstEntryRow) {
                            if ($field->field_name == $firstEntryRow->field_name) {
                                if ($field->field_value != $firstEntryRow->field_value) {
                                    $diff = true;
                                    break;
                                }
                            }
                        }
                        if ($diff) {
                            break;
                        }
                    }
                    if (!$diff) {
                        foreach ($entry as $field) {
                            // Delete duplicate entries
                            $deleteSql = is_numeric($field->field_order) ?
                                    $wpdb->prepare($deleteWithFieldOrder, $field->submit_time, $field->form_name, $field->field_name, $field->field_value, intval($field->field_order)) :
                                    $wpdb->prepare($deleteNoFieldOrder, $field->submit_time, $field->form_name, $field->field_name, $field->field_value);
                            //echo "$deleteSql\n"; // debug
                            $wpdb->query($deleteSql);
                        }
                        ++$count;
                    } else {
                        $newST = $this->getNewSubmitTime($entry[0]->submit_time + 0.0001 * $idx);
                        foreach ($entry as $field) {
                            // Give different entries a slightly different submit_time
                            $updateSql = is_numeric($field->field_order) ?
                                    $wpdb->prepare($updateWithFieldOrder, $newST, $field->submit_time, $field->form_name, $field->field_name, $field->field_value, intval($field->field_order)) :
                                    $wpdb->prepare($updateNoFieldOrder, $newST, $field->submit_time, $field->form_name, $field->field_name, $field->field_value);
                            //echo "$updateSql\n"; // debug
                            $wpdb->query($updateSql);
                        }
                        ++$count;
                    }
                }
            }
        }

        return $count;
    }

    public function getNewSubmitTime($submitTime) {
        global $wpdb;
        $table = $this->plugin->getSubmitsTableName();
        $inDBSql = 'select count(submit_time) from ' . $table . ' where submit_time = %F';
        while (true) {
            $submitTime = $submitTime + 0.0001; // Propose new submit time
            $inDbAlready = $wpdb->get_var($wpdb->prepare($inDBSql, $submitTime));
            if (!$inDbAlready) {
                break;
            }
        }
        return $submitTime;
    }
}