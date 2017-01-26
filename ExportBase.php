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

require_once('CF7DBPlugin.php');
require_once('CFDBQueryResultIteratorFactory.php');
require_once('CFDBQueryResultIterator.php');
require_once('DereferenceShortcodeVars.php');

class ExportBase {

    /**
     * @var string
     */
    var $defaultTableClass = 'cf7-db-table';

    /**
     * @var array
     */
    var $options;

    /**
     * @var bool
     */
    var $debug = false;

    /**
     * @var array
     */
    var $showColumns;

    /**
     * @var array
     */
    var $hideColumns;

    /**
     * @var string
     */
    var $htmlTableId;

    /**
     * @var string
     */
    var $htmlTableClass;

    /**
     * @var string
     */
    var $style;

    /**
     * @var array assoc array of column names to display names
     */
    var $headers;

    /**
     * @var CFDBEvaluator|CFDBFilterParser|CFDBSearchEvaluator
     */
    var $rowFilter;

    /**
     * @var CFDBEvaluator|CFDBFilterParser|CFDBSearchEvaluator
     */
    var $rowTransformFilter;

    /**
     * @var CFDBTransformParser
     */
    var $transform;

    /**
     * @var bool
     */
    var $isFromShortCode = false;

    /**
     * @var bool
     */
    var $showSubmitField;

    /**
     * @var CF7DBPlugin
     */
    var $plugin;

    /**
     * @var CFDBDataIterator|CFDBAbstractQueryResultsIterator
     */
    var $dataIterator;

    function __construct() {
        $this->plugin = new CF7DBPlugin();
    }

    /**
     * This method is the first thing to call after construction to set state for other methods to work
     * @param  $options array|null
     * @return void
     */
    public function setOptions($options) {
        $this->options = $options;
    }

    public function dereferenceOption($optionName) {
        if (isset($this->options[$optionName])) {
            $dereferenceVars = new DereferenceShortcodeVars;
            $this->options[$optionName] = $dereferenceVars->convert($this->options[$optionName]);
        }
    }

    public function setCommonOptions($htmlOptions = false) {

        if ($this->options && is_array($this->options)) {
            foreach (array(
                             'debug', 'permissionmsg', 'unbuffered', 'show', 'hide', 'class', 'style', 'id',
                             'orderby', 'limit', 'tlimit', 'header', 'headers', 'content',
                             'filter', 'tfilter', 'search', 'tsearch', 'trans', 'delimiter')
                     as $optionName) {
                $this->dereferenceOption($optionName);
            }

            if (isset($this->options['debug']) && $this->options['debug'] != 'false') {
                $this->debug = true;
            }

            $this->isFromShortCode = isset($this->options['fromshortcode']) &&
                    $this->options['fromshortcode'] === true;

            if (!isset($this->options['unbuffered'])) {
                //$this->options['unbuffered'] = $this->isFromShortCode ? 'false' : 'true'; // todo
                $this->options['unbuffered'] = 'false';
            } else {
                if ($this->options['unbuffered'] == 'checked') {
                    $this->options['unbuffered'] = 'true';
                }
            }

            if (isset($this->options['showColumns'])) {
                $this->showColumns = $this->options['showColumns'];
            }
            else if (isset($this->options['show'])) {
                $this->showColumns = preg_split('/,/', $this->options['show'], -1, PREG_SPLIT_NO_EMPTY);
            }

            if (isset($this->options['hideColumns'])) {
                $this->hideColumns = $this->options['hideColumns'];
            }
            else if (isset($this->options['hide'])) {
                $this->hideColumns = preg_split('/,/', $this->options['hide'], -1, PREG_SPLIT_NO_EMPTY);
            }


            if ($htmlOptions) {
                if (isset($this->options['class'])) {
                    $this->htmlTableClass = $this->options['class'];
                }
                else {
                    $this->htmlTableClass = $this->defaultTableClass;
                }

                if (isset($this->options['id'])) {
                    $this->htmlTableId = $this->options['id'];
                }
                else {
                    $this->htmlTableId = 'cftble_' . rand();
                }

                if (isset($this->options['style'])) {
                    $this->style = $this->options['style'];
                }
            }

            $permittedFunctions = null;
            if (isset($this->options['filter']) || isset($this->options['trans'])) {
                require_once('CFDBPermittedFunctions.php');
                $permittedFunctions = CFDBPermittedFunctions::getInstance();
                $permitAll = $this->queryPermitAllFunctions();
                $permittedFunctions->setPermitAllFunctions($permitAll);
            }


            $filters = array();
            if (isset($this->options['filter'])) {
                require_once('CFDBFilterParser.php');
                $aFilter = new CFDBFilterParser;
                $aFilter->setComparisonValuePreprocessor(new DereferenceShortcodeVars);
                $aFilter->setPermittedFilterFunctions($permittedFunctions);
                $aFilter->parse($this->options['filter']);
                if ($this->debug) {
                    echo '<pre>\'' . $this->options['filter'] . "'\n";
                    print_r($aFilter->tree);
                    echo '</pre>';
                }
                $filters[] = $aFilter;
            }

            $transformFilters = array();
            if (isset($this->options['tfilter'])) {
                require_once('CFDBFilterParser.php');
                $aFilter = new CFDBFilterParser;
                $aFilter->setComparisonValuePreprocessor(new DereferenceShortcodeVars);
                $aFilter->setPermittedFilterFunctions($permittedFunctions);
                $aFilter->parse($this->options['tfilter']);
                if ($this->debug) {
                    echo '<pre>\'' . $this->options['tfilter'] . "'\n";
                    print_r($aFilter->tree);
                    echo '</pre>';
                }
                $transformFilters[] = $aFilter;
            }

            if (isset($this->options['search'])) {
                require_once('CFDBSearchEvaluator.php');
                $aFilter = new CFDBSearchEvaluator;
                $aFilter->setSearch($this->options['search']);
                $filters[] = $aFilter;
            }

            if (isset($this->options['tsearch'])) {
                require_once('CFDBSearchEvaluator.php');
                $aFilter = new CFDBSearchEvaluator;
                $aFilter->setSearch($this->options['tsearch']);
                $transformFilters[] = $aFilter;
            }

            $numFilters = count($filters);
            if ($numFilters == 1) {
                $this->rowFilter = $filters[0];
            }
            else if ($numFilters > 1) {
                require_once('CFDBCompositeEvaluator.php');
                $this->rowFilter = new CFDBCompositeEvaluator;
                $this->rowFilter->setEvaluators($filters);
            }

            $numTransformFilters = count($transformFilters);
            if ($numTransformFilters == 1) {
                $this->rowTransformFilter = $transformFilters[0];
            }
            else if ($numTransformFilters > 1) {
                require_once('CFDBCompositeEvaluator.php');
                $this->rowTransformFilter = new CFDBCompositeEvaluator;
                $this->rowTransformFilter->setEvaluators($transformFilters);
            }

            if (isset($this->options['trans'])) {
                require_once('CFDBTransformParser.php');
                $this->transform = new CFDBTransformParser();
                $this->transform->setComparisonValuePreprocessor(new DereferenceShortcodeVars);
                $this->transform->setPermittedFilterFunctions($permittedFunctions);

                $transformOption = $this->options['trans'];
                // Set up "orderby" post-processing
                if (isset($this->options['orderby'])) {
                    $orderByStrings = explode(',', $this->options['orderby']);
                    foreach ($orderByStrings as $anOrderBy) {
                        $anOrderBy = trim($anOrderBy);
                        $ascOrDesc = null;
                        list($ascOrDesc, $anOrderBy) = $this->parseOrderBy($anOrderBy);
                        $ascOrDesc = trim($ascOrDesc);
                        if (empty($ascOrDesc)) {
                            $ascOrDesc = 'ASC';
                        }
                        // Append a Sort transform
                        $transformOption .= '&&NaturalSortByField(' . $anOrderBy . ',' . $ascOrDesc . ')';
                    }
                }

                $this->transform->parse($transformOption);
                if ($this->debug) {
                    echo '<pre>\'' . $transformOption . "'\n";
                    print_r($this->transform->tree);
                    echo '</pre>';
                }
                $this->transform->setupTransforms();
            }

            if (isset($this->options['headers'])) { // e.g. "col1=Column 1 Display Name,col2=Column2 Display Name"
                $headersList = preg_split('/,/', $this->options['headers'], -1, PREG_SPLIT_NO_EMPTY);
                if (is_array($headersList)) {
                    $this->headers = array();
                    foreach ($headersList as $nameEqualValue) {
                        $nameEqualsValueArray = explode('=', $nameEqualValue, 2); // col1=Column 1 Display Name
                        if (count($nameEqualsValueArray) >= 2) {
                            $this->headers[$nameEqualsValueArray[0]] = $nameEqualsValueArray[1];
                        }
                    }
                }
            }
        }
    }

    /**
     * @return bool
     */
    protected function isAuthorized() {
        if (!$this->isFromShortCode) {
            return $this->plugin->canUserDoRoleOption('CanSeeSubmitData');
        }
        else {
            $isAuth = $this->plugin->canUserDoRoleOption('CanSeeSubmitDataViaShortcode');
            if ($isAuth && isset($this->options['role'])) {
                $isAuth = $this->plugin->isUserRoleEqualOrBetterThan($this->options['role']);
            }
            return $isAuth;
        }
    }

    protected function assertSecurityErrorMessage() {
        $showMessage = true;

        if (isset($this->options['role'])) {
            // If role is being used, but default do not show the error message
            $showMessage = false;
        }

        if (isset($this->options['permissionmsg'])) {
            $showMessage = $this->options['permissionmsg'] != 'false';
        }

        $errMsg = $showMessage ? __('You do not have sufficient permissions to access this data.', 'contact-form-7-to-database-extension') : '';
        if ($this->isFromShortCode) {
            echo $errMsg;
        }
        else {
            include_once('CFDBDie.php');
            CFDBDie::wp_die($errMsg);
        }
    }


    /**
     * @param string|array|null $headers mixed string header-string or array of header strings.
     * E.g. Content-Type, Content-Disposition, etc.
     * @return void
     */
    protected function echoHeaders($headers = null) {
        if (!headers_sent()) {
            header('Expires: 0');
            header('Cache-Control: no-store, no-cache, must-revalidate');
            // Hoping to keep the browser from timing out if connection from Google SS Live Data
            // script is calling this page to get information
            header("Keep-Alive: timeout=60"); // Not a standard HTTP header; browsers may disregard

            if ($headers) {
                if (is_array($headers)) {
                    foreach ($headers as $aheader) {
                        header($aheader);
                    }
                }
                else {
                    header($headers);
                }
            }
            flush();
        }
    }

    /**
     * @param  $dataColumns array
     * @return array
     */
    protected function &getColumnsToDisplay($dataColumns) {

        if (empty($dataColumns)) {
            $retCols = array();
            return $retCols;
        }

        //$dataColumns = array_merge(array('Submitted'), $dataColumns);
        $showCols = empty($this->showColumns) ? $dataColumns : $this->matchColumns($this->showColumns, $dataColumns);
        if (empty($this->hideColumns)) {
            return $showCols;
        }

        $hideCols = $this->matchColumns($this->hideColumns, $dataColumns);
        if (empty($hideCols)) {
            return $showCols;
        }

        $retCols = array();
        foreach ($showCols as $aShowCol) {
            if (!in_array($aShowCol, $hideCols)) {
                $retCols[] = $aShowCol;
            }
        }
        return $retCols;
    }

    protected function matchColumns(&$patterns, &$subject) {
        $returnCols = array();
        foreach ($patterns as $pCol) {
            if (substr($pCol, 0, 1) == '/') {
                // Show column value is a REGEX
                foreach($subject as $sCol) {
                    if (preg_match($pCol, $sCol) && !in_array($sCol, $returnCols)) {
                        $returnCols[] = $sCol;
                    }
                }
            }
            else {
                $returnCols[] = $pCol;
            }
        }
        return $returnCols;
    }

    /**
     * @return bool
     */
    protected function getShowSubmitField() {
        $showSubmitField = true;
        if ($this->hideColumns != null && is_array($this->hideColumns) && in_array('Submitted', $this->hideColumns)) {
            $showSubmitField = false;
        }
        else if ($this->showColumns != null && is_array($this->showColumns)) {
            $showSubmitField = in_array('Submitted', $this->showColumns);
        }
        return $showSubmitField;
    }

    /**
     * Execute the query and set up the results iterator
     * @param string|array $formName (if array, must be array of string)
     * @param null|string $submitTimeKeyName
     * @return void
     */
    protected function setDataIterator($formName, $submitTimeKeyName = null) {

        $submitTimes = $this->queryRandomSubmitTimes($formName);

        $sql = $this->getPivotQuery($formName, false, $submitTimes);

        $queryOptions = array();
        if ($submitTimeKeyName) {
            $queryOptions['submitTimeKeyName'] = $submitTimeKeyName;
        }
        if (isset($this->options['limit']) && $this->hasFilterOrTransform()) {
            // have data iterator apply the limit if it is not already
            // being applied in SQL directly, which we do when there are
            // no filter constraints.
            $queryOptions['limit'] = $this->options['limit'];
        }
        $unbuffered = false;;
        if (isset($this->options['unbuffered'])) {
            $queryOptions['unbuffered'] = $this->options['unbuffered'];
            $unbuffered = $queryOptions['unbuffered'] == 'true';
        }

        if ($this->debug) {
            $queryOptions['debug'] = 'true';
        }

        $this->dataIterator = CFDBQueryResultIteratorFactory::getInstance()->newQueryIterator($unbuffered);

        if ($this->transform && !empty($this->transform->transformIterators)) {
            $postProcessOptions = $queryOptions; // make a copy

            // If we have a transform, then alternatively-named options like 'tlimit' are used
            // in the actual query (CFDBQueryResultIterator) whereas the normally named
            // ones are handled by the CFDBTransformEndpoint post-processor
            unset($queryOptions['limit']);
            if (isset($this->options['tlimit'])) {
                $queryOptions['limit'] = $this->options['tlimit'];
            }
            unset($queryOptions['orderby']);
            if (isset($this->options['torderby'])) {
                $queryOptions['orderby'] = $this->options['torderby'];
            }
            // These aren't really needed b/c we have already setup $this->rowTransformFilter
            unset($queryOptions['filter']);
            if (isset($this->options['tfilter'])) {
                $queryOptions['filter'] = $this->options['tfilter'];
            }
            unset($queryOptions['search']);
            if (isset($this->options['tsearch'])) {
                $queryOptions['search'] = $this->options['tsearch'];
            }

            $this->dataIterator->query($sql, $this->rowTransformFilter, $queryOptions);
            $queryDisplayColumns = $this->getColumnsToDisplay($this->dataIterator->columns);

            $this->transform->setTimezone();
            // Hookup query iterator as first transform, hookup last iterator as $this->dataIterator
            $this->transform->setDataSource($this->dataIterator);
            $this->dataIterator = $this->transform->getIterator();

            // $this->dataIterator is a CFDBTransformEndpoint
            $this->dataIterator->getPostProcessor()->query($sql, $this->rowFilter, $postProcessOptions);

            $displayColumns = $this->getColumnsToDisplay($this->dataIterator->getDisplayColumns());

            // Not sure why I need to do this to make show/hide work in some cases
            $this->dataIterator->displayColumns = empty($displayColumns) ? $queryDisplayColumns : $displayColumns;

        } else {
            // No transform, just query
            $this->dataIterator->query($sql, $this->rowFilter, $queryOptions);
            $this->dataIterator->displayColumns = $this->getColumnsToDisplay($this->dataIterator->columns);
        }
    }

    /**
     * Clear the "ob_" buffer (all of them in the stack)
     * Call immediately after setDataIterator() to prevent misc chars
     * from being printed during export
     */
    public function clearAllOutputBuffers() {
        while (ob_get_length()) {
            // Prevents misc crap from being printed during export.
            // Seen on one customer's site where a newline is injected
            // causes an empty header row for .csv and corrupting .xlsx file
            ob_clean();
        }
    }

//    protected function &getFileMetaData($formName) {
//        global $wpdb;
//        $tableName = $this->plugin->getSubmitsTableName();
//        $rows = $wpdb->get_results(
//            "select distinct `field_name`
//from `$tableName`
//where `form_name` = '$formName'
//and `file` is not null");
//
//        $fileColumns = array();
//        foreach ($rows as $aRow) {
//            $files[] = $aRow->field_name;
//        }
//        return $fileColumns;
//    }

    /**
     * @param string|array $formName (if array, must be array of string)
     * @param bool $count
     * @param $submitTimes array of string submit_time values that are to be specifically queried
     * @return string
     */
    public function &getPivotQuery($formName, $count = false, $submitTimes = null) {
        global $wpdb;
        $tableName = $this->plugin->getSubmitsTableName();

        $formNameClause = '1=1';
        if (is_array($formName)) {
            $formNameArray = $this->escapeAndQuoteArrayValues($formName);
            $formNameClause = '`form_name` in ( ' . implode(', ', $formNameArray) . ' )';
        }
        else if ($formName !== null && $formName != '*') { // * => all forms
            $formNameLength = strlen($formName);
            if ($formNameLength > 2 && strpos($formName, '/') === 0 && $formName[$formNameLength - 1] == '/') {
                // Regular Expression
                // Get rid of the enclosing '/' for MySQL REGEX
                $pattern = substr($formName, 1, $formNameLength - 2);
                $formNameClause =  "`form_name` REGEXP '". $this->escapeString($pattern) . "'";
            }
            else if (strpos($formName, ',') !== false) {
                // Comma-delimited list of forms
                $formNameArray = explode(',', $formName);
                $formNameArray[] = $formName; // in case the form name is literally the string with commas in it
                $formNameArray = $this->escapeAndQuoteArrayValues($formNameArray);
                $formNameClause = '`form_name` in ( ' . implode(', ', $formNameArray) . ' )';
            }
            else {
                $formNameClause =  "`form_name` = '". $this->escapeString($formName) . "'";
            }
        }

        $submitTimesClause = '';
        if (is_array($submitTimes) && !empty($submitTimes)) {
            $submitTimesClause = 'AND submit_time in ( ' . implode(', ', $submitTimes) . ' )';
        }

        //$rows = $wpdb->get_results("SELECT DISTINCT `field_name`, `field_order` FROM `$tableName` WHERE $formNameClause ORDER BY field_order"); // Pagination bug
        $rows = $wpdb->get_results("SELECT DISTINCT `field_name` FROM `$tableName` WHERE $formNameClause ORDER BY field_order");
        $fields = array();
        foreach ($rows as $aRow) {
            if ($aRow->field_name && trim($aRow->field_name) != '') {
                // Saw a case of a column name of '' and ' ' which caused query to fail
                // and no date to be displayed.
                $fields[] = $aRow->field_name;
            }
        }
        $sql = '';
        if ($count) {
            $sql .= 'SELECT count(*) as count FROM (';
        }
        $sql .= "SELECT `submit_time` AS 'Submitted'";
        foreach ($fields as $aCol) {
            // Escape single quotes in column name
            $aCol = $this->escapeString($aCol);
            $sql .= ",\n max(if(`field_name`='$aCol', `field_value`, null )) AS '$aCol'";
        }
        if (!$count) {
            $sql .= ",\n GROUP_CONCAT(if(`file` is null or length(`file`) = 0, null, `field_name`)) AS 'fields_with_file'";
        }
        $sql .=  "\nFROM `$tableName` \nWHERE $formNameClause $submitTimesClause \nGROUP BY `submit_time` ";
        if ($count) {
            $sql .= ') form';
        }
        else {
            $orderBys = array();
            if ($this->options && isset($this->options['orderby'])) {
                $orderByStrings = explode(',', $this->options['orderby']);
                foreach ($orderByStrings as $anOrderBy) {
                    $anOrderBy = trim($anOrderBy);
                    $ascOrDesc = null;
                    list($ascOrDesc, $anOrderBy) = $this->parseOrderBy($anOrderBy);
                    if (in_array($anOrderBy, $fields) || $anOrderBy == 'submit_time') {
                        $orderBys[] = '`' . $anOrderBy . '`' . $ascOrDesc;
                    }
                    else {
                        // Want to add a different collation as a different sorting mechanism
                        // Actually doesn't work because MySQL does not allow COLLATE on a select that is a group function
                        $collateIdx = stripos($anOrderBy, ' COLLATE');
                        if ($collateIdx > 0) {
                            $collatedField = substr($anOrderBy, 0, $collateIdx);
                            if (in_array($collatedField, $fields)) {
                                $orderBys[] = '`' . $collatedField . '`' . substr($anOrderBy, $collateIdx) . $ascOrDesc;
                            }
                        }
                    }
                }
            }
            if (empty($orderBys)) {
                $sql .= "\nORDER BY `submit_time` DESC";
            }
            else {
                $sql .= "\nORDER BY ";
                $first = true;
                foreach ($orderBys as $anOrderBy) {
                    if ($first) {
                        $sql .= $anOrderBy;
                        $first = false;
                    }
                    else {
                        $sql .= ', ' . $anOrderBy;
                    }
                }
            }

            if (!$this->hasFilterOrTransform() && $this->options && isset($this->options['limit'])) {
                // If no filter constraints and have a limit, add limit to the SQL
                $sql .= "\nLIMIT " . $this->options['limit'];
            }
        }
        //echo $sql; // debug
        return $sql;
    }

    /**
     * @param $anArray array
     * @return array of quoted escaped values
     */
    public function escapeAndQuoteArrayValues($anArray) {
        $retArray = array();
        foreach ($anArray as $aValue) {
            $retArray[] = '\'' . $this->escapeString($aValue) . '\'';
        }
        return $retArray;
    }

    /**
     * Simple alternative to the deprecated mysql_real_escape_string() function
     * @param $text String
     * @return String
     */
    public function escapeString($text) {
        // Taken from: http://www.gamedev.net/topic/448909-php-alternative-to-mysql_real_escape_string/
        return strtr($text, array(
                "\x00" => '\x00',
                "\n" => '\n',
                "\r" => '\r',
                '\\' => '\\\\',
                "'" => "\'",
                '"' => '\"',
                "\x1a" => '\x1a'
        ));
    }

    /**
     * @param string|array $formName (if array, must be array of string)
     * @return int
     */
    public function getDBRowCount($formName) {
        global $wpdb;
        $count = 0;
        $rows = $wpdb->get_results($this->getPivotQuery($formName, true));
        foreach ($rows as $aRow) {
            $count = $aRow->count;
            break;
        }
        return $count;
    }

    public function hasFilterOrTransform() {
        return $this->rowFilter || $this->transform;
    }

    /**
     * Query for n random submit times if 'random' option is set indicting number of random
     * values to return (n)
     * @param $formName
     * @return array|null array of n submit_times or null if not applicable
     */
    protected function queryRandomSubmitTimes($formName) {
        $submitTimes = null;

        if (isset($this->options['random'])) {
            $numRandom = intval($this->options['random']);
            if ($numRandom > 0) {
                // Digression: query for n unique random submit_time values
                $justSubmitTimes = new ExportBase();
                $justSubmitTimes->setOptions($this->options);
                $justSubmitTimes->setCommonOptions();
                unset($justSubmitTimes->options['random']);
                $justSubmitTimes->showColumns = array('submit_time');
                $jstSql = $justSubmitTimes->getPivotQuery($formName);
                $justSubmitTimes->setDataIterator($formName, 'submit_time');
                $justSubmitTimes->dataIterator->query(
                        $jstSql,
                        $justSubmitTimes->rowFilter);

                $allSubmitTimes = null;
                while ($justSubmitTimes->dataIterator->nextRow()) {
                    $allSubmitTimes[] = $justSubmitTimes->dataIterator->row['submit_time'];
                }
                if (!empty($allSubmitTimes)) {
                    if (count($allSubmitTimes) < $numRandom) {
                        $submitTimes = $allSubmitTimes;
                        return $submitTimes;
                    } else {
                        shuffle($allSubmitTimes); // randomize
                        $submitTimes = array_slice($allSubmitTimes, 0, $numRandom);
                        return $submitTimes;
                    }
                }
                return $submitTimes;
            }
            return $submitTimes;
        }
        return $submitTimes;
    }

    /**
     * @return bool
     */
    public function queryPermitAllFunctions() {
        return $this->plugin->getOption('FunctionsInShortCodes', 'false', true) === 'true';
    }

    /**
     * @param $anOrderBy string a single order by clause like "field1 DESC"
     * @return array
     */
    protected function parseOrderBy($anOrderBy) {
        $ascOrDesc = null;
        if (strtoupper(substr($anOrderBy, -5)) == ' DESC') {
            $ascOrDesc = ' DESC';
            $anOrderBy = trim(substr($anOrderBy, 0, -5));
        } else if (strtoupper(substr($anOrderBy, -4)) == ' ASC') {
            $ascOrDesc = ' ASC';
            $anOrderBy = trim(substr($anOrderBy, 0, -4));
        }
        if ($anOrderBy == 'Submitted') {
            $anOrderBy = 'submit_time';
            return array($ascOrDesc, $anOrderBy);
        }
        return array($ascOrDesc, $anOrderBy);
    }

    // http://stackoverflow.com/questions/14678281/get-list-separator-character-for-any-locale
    public function get_csv_delimiter($locale) {
        $locales_with_colon_delimiter =
            'az_AZ be_BY bg_BG bs_BA ca_ES crh_UA cs_CZ da_DK de_AT de_BE de_DE de_LU el_CY el_GR es_AR es_BO es_CL es_CO es_CR es_EC es_ES es_PY es_UY es_VE et_EE eu_ES eu_ES@euro ff_SN fi_FI fr_BE fr_CA fr_FR fr_LU gl_ES hr_HR ht_HT hu_HU id_ID is_IS it_IT ka_GE kk_KZ ky_KG lt_LT lv_LV mg_MG mk_MK mn_MN nb_NO nl_AW nl_NL nn_NO pap_AN pl_PL pt_BR pt_PT ro_RO ru_RU ru_UA rw_RW se_NO sk_SK sl_SI sq_AL sq_MK sr_ME sr_RS sr_RS@latin sv_SE tg_TJ tr_TR tt_RU@iqtelif uk_UA vi_VN wo_SN';
        if (stripos($locales_with_colon_delimiter, $locale) !== false) {
            return ';';
        }
        return ',';
    }


}
