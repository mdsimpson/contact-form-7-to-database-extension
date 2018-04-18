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
require_once('CFDBView.php');
require_once('ExportToHtmlTable.php');

class CFDBViewWhatsInDB extends CFDBView {

    function display(&$plugin) {
        if ($plugin == null) {
            $plugin = new CF7DBPlugin;
        }
        echo '<div id="cfdb-admin">';
        $canEdit = $plugin->canUserDoRoleOption('CanChangeSubmitData');
        $this->pageHeader($plugin);

        global $wpdb;
        $tableName = $plugin->getSubmitsTableName();
        $useDataTables = $plugin->getOption('UseDataTablesJS', 'true', true) == 'true';
        $tableHtmlId = 'cf2dbtable';

        // Identify which forms have data in the database
        $formsList = $plugin->getForms();
        if (count($formsList) == 0) {
            echo esc_html(__('No form submissions in the database', 'contact-form-7-to-database-extension'));
            return;
        }
        $page = 1;
        if (isset($_REQUEST['dbpage'])) {
            // sanitize as int value
            $page = intval($_REQUEST['dbpage'] ? $_REQUEST['dbpage'] : '0');
        }
        $currSelection = null;
        if (isset($_REQUEST['form_name'])) {
            // $currSelection is unsanitized to pass to parametrized DB query.
            // $currSelectionEscaped is the sanitized version for display on the page
            $currSelection = $_REQUEST['form_name'] ? $_REQUEST['form_name'] : '0';
        }
        else if (isset($_REQUEST['form'])) {
            // $currSelection is unsanitized to pass to parametrized DB query.
            // $currSelectionEscaped is the sanitized version for display on the page
            $currSelection = $_REQUEST['form'] ? $_REQUEST['form'] : '0';
        }

        if ($currSelection) {
            $currSelection = stripslashes($currSelection);
            $currSelection = htmlspecialchars_decode($currSelection, ENT_QUOTES);
            $currSelection = strip_tags($currSelection); // guard against xss
        }

        // Sanitized version of $currSelection for display on the page
        $currSelectionEscaped = htmlspecialchars($currSelection, ENT_QUOTES, 'UTF-8');

        // If there is only one form in the DB, select that by default
        if (!$currSelection && count($formsList) == 1) {
            $currSelection = $formsList[0];
            // Bug fix: Need to set this so the Editor plugin can reference it
            $_REQUEST['form_name'] = $formsList[0];
        }
        if ($currSelection) {
            // Check for delete operation
            if (isset($_POST['cfdbdel']) &&
                    $canEdit &&
                    wp_verify_nonce($_REQUEST['_wpnonce'])) {
                if (isset($_POST['submit_time'])) {
                    $submitTime = $_POST['submit_time'];
                    $wpdb->query(
                        $wpdb->prepare(
                            "delete from `$tableName` where `form_name` = '%s' and `submit_time` = %F",
                            $currSelection, $submitTime));
                }
                else if (isset($_POST['all'])) {
                    $wpdb->query(
                        $wpdb->prepare(
                            "delete from `$tableName` where `form_name` = '%s'", $currSelection));
                }
                else {
                    foreach ($_POST as $name => $value) { // checkboxes
                        if ($value == 'row') {
                            // Dots and spaces in variable names are converted to underscores. For example <input name="a.b" /> becomes $_REQUEST["a_b"].
                            // http://www.php.net/manual/en/language.variables.external.php
                            // We are expecting a time value like '1300728460.6626' but will instead get '1300728460_6626'
                            // so we need to put the '.' back in before going to the DB.
                            $name = str_replace('_', '.', $name);
                            $wpdb->query(
                                $wpdb->prepare(
                                    "delete from `$tableName` where `form_name` = '%s' and `submit_time` = %F",
                                    $currSelection, $name));
                        }
                    }
                }
            }
            else if (isset($_POST['delete_wpcf7']) &&
                    $canEdit &&
                    wp_verify_nonce($_REQUEST['_wpnonce'])) {
                $plugin->delete_wpcf7_fields($currSelection);
                $plugin->add_wpcf7_noSaveFields();
            }
        }
        // Form selection drop-down list
        $pluginDirUrl = $plugin->getPluginDirUrl();

        ?>
    <table id="cfdb-controls" width="100%" cellspacing="20">
        <tr>
            <td align="left" valign="top">
                <form method="get" action="<?php echo $_SERVER['REQUEST_URI']?>" name="displayform" id="displayform">
                    <input type="hidden" name="page" value="<?php echo esc_attr($_REQUEST['page'] ? $_REQUEST['page'] : '') ?>"/>
                    <select name="form_name" id="form_name" onchange="this.form.submit();">
                        <option value=""><?php echo esc_html(__('* Select a form *', 'contact-form-7-to-database-extension')); ?></option>
                        <?php foreach ($formsList as $formName) {
                            $selected = ($formName == $currSelection) ? "selected" : "";
                            $formNameEscaped = htmlspecialchars($formName, ENT_QUOTES, 'UTF-8');
                        ?>
                        <option value="<?php echo $formNameEscaped ?>" <?php echo $selected ?>><?php echo $formNameEscaped ?></option>
                        <?php } ?>
                    </select>
                </form>
            </td>
            <td align="center" valign="top">
                <?php if ($currSelection) { ?>
                <script type="text/javascript" language="Javascript">
                    var showHideExportLinkDelimiter = function() {
                        var enc = jQuery('#enc_cntl').val();
                        if (['CSVUTF8BOM', 'CSVUTF8', 'CSVSJIS'].indexOf(enc) > -1) {
                            jQuery('#csvdelim_span').show();
                        }
                        else {
                            jQuery('#csvdelim_span').hide();
                        }
                    };
                    jQuery(document).ready(function() {
                        showHideExportLinkDelimiter();
                        jQuery('#enc_cntl').change(showHideExportLinkDelimiter)
                    });
                    function getDelimiterValue() {
                        return jQuery('#csv_delim').val();
                    }
                    function changeDbPage(page) {
                        var newdiv = document.createElement('div');
                        newdiv.innerHTML = "<input id='dbpage' name='dbpage' type='hidden' value='" + page + "'>";
                        var dispForm = document.forms['displayform'];
                        dispForm.appendChild(newdiv);
                        dispForm.submit();
                    }
                    function getSearchFieldValue() {
                        var searchVal = '';
                        if (typeof jQuery == 'function') {
                            try {
                                searchVal = jQuery('#<?php echo $tableHtmlId;?>_filter input').val();
                            }
                            catch (e) {
                            }
                        }
                        return searchVal;
                    }
                    function exportData(encSelect) {
                        var enc = encSelect.options[encSelect.selectedIndex].value;

                        var checkedValues = [];
                        jQuery('input[id^="delete_"]:checked').each(function() {
                            checkedValues.push(this.name);
                        });
                        checkedValues = checkedValues.join(',');

                        var url;
                        if (enc == 'GLD') {
                            alert("<?php echo esc_js(__('You will now be navigated to the builder page where it will generate a function to place in your Google Spreadsheet', 'contact-form-7-to-database-extension')); ?>");
                            url = '<?php echo $plugin->getAdminUrlPrefix('admin.php') ?>page=CF7DBPluginShortCodeBuilder&form=<?php echo urlencode($currSelection) ?>&enc=' + enc;
                            if (checkedValues) {
                                url += "&filter=submit_time[in]" + checkedValues;
                            }
                            location.href = url;
                        }
                        else {
                            url = '<?php echo $plugin->getAdminUrlPrefix('admin-ajax.php') ?>action=cfdb-export&form=<?php echo urlencode($currSelection) ?>&enc=' + enc;
                            var delimiter = getDelimiterValue();
                            if (delimiter) {
                                url += "&delimiter=" + encodeURIComponent(delimiter);
                            } else {
                                url += "&regionaldelimiter=true";
                            }
                            var searchVal = getSearchFieldValue();
                            if (searchVal) {
                                url += '&search=' + encodeURIComponent(searchVal);
                            }
                            if (checkedValues) {
                                url += "&filter=submit_time[in]" + checkedValues;
                            }
                            //alert(url);
                            location.href = url;
                        }
                    }
                </script>
                <form name="exportcsv" action="<?php echo $_SERVER['REQUEST_URI']?>">
                    <input type="hidden" name="unbuffered" value="true"/>
                    <select size="1" name="enc" id="enc_cntl">
                        <option id="xlsx" value="xlsx">
                            <?php echo esc_html(__('Excel .xlsx', 'contact-form-7-to-database-extension')); ?>
                        </option>
                        <option id="ods" value="ods">
                            <?php echo esc_html(__('OpenDocument .ods', 'contact-form-7-to-database-extension')); ?>
                        </option>
                        <option id="IQY" value="IQY">
                            <?php echo esc_html(__('Excel Internet Query', 'contact-form-7-to-database-extension')); ?>
                        </option>
                        <option id="CSVUTF8BOM" value="CSVUTF8BOM">
                            <?php echo esc_html(__('Excel CSV (UTF8-BOM)', 'contact-form-7-to-database-extension')); ?>
                        </option>
                        <option id="TSVUTF16LEBOM" value="TSVUTF16LEBOM">
                            <?php echo esc_html(__('Excel TSV (UTF16LE-BOM)', 'contact-form-7-to-database-extension')); ?>
                        </option>
                        <option id="CSVUTF8" value="CSVUTF8">
                            <?php echo esc_html(__('Plain CSV (UTF-8)', 'contact-form-7-to-database-extension')); ?>
                        </option>
                        <option id="CSVSJIS" value="CSVSJIS">
                            <?php echo esc_html(__('Excel CSV for Japanese (Shift-JIS)', 'contact-form-7-to-database-extension')); ?>
                        </option>
                        <option id="GLD" value="GLD">
                            <?php echo esc_html(__('Google Spreadsheet Live Data', 'contact-form-7-to-database-extension')); ?>
                        </option>
                        <option id="HTML" value="HTML">
                            <?php echo esc_html(__('HTML', 'contact-form-7-to-database-extension')); ?>
                        </option>
                        <option id="JSON" value="JSON">
                            <?php echo esc_html(__('JSON', 'contact-form-7-to-database-extension')); ?>
                        </option>
                    </select>
                    <input id="exportButton" name="exportButton" type="button" class="button"
                           value="<?php echo esc_attr(__('Export', 'contact-form-7-to-database-extension')); ?>"
                           onclick="exportData(this.form.elements['enc'])"/>
                    <span id="csvdelim_span" style="display:none">
                        <br />
                        <label for="csv_delim"><?php echo esc_html(__('CSV Delimiter', 'contact-form-7-to-database-extension')); ?></label>
                        <input id="csv_delim" type="text" size="2" value=""/>
                    </span>
                    <span style="font-size: x-small;"><br /><?php echo '<a href="admin.php?page=' . $plugin->getShortCodeBuilderPageSlug() . '">' .
                          __('Advanced Export', 'contact-form-7-to-database-extension') . '</a>' ?>
                </form>
                <?php } ?>
            </td>
            <td align="right" valign="top">
                <?php if ($currSelection && $canEdit) { ?>
                <form action="<?php echo $_SERVER['REQUEST_URI']?>" method="post">
                    <input name="form_name" type="hidden" value="<?php echo $currSelectionEscaped ?>"/>
                    <input name="all" type="hidden" value="y"/>
                    <?php wp_nonce_field(); ?>
                    <input id="cfdbdeleteall" name="cfdbdel" type="submit" class="button"
                           value="<?php echo esc_attr(__('Delete All This Form\'s Records', 'contact-form-7-to-database-extension')); ?>"
                           onclick="return confirm('<?php echo esc_js(__('Are you sure you want to delete all the data for this form?', 'contact-form-7-to-database-extension')); ?>')"/>
                </form>
<!--                <br/>-->
<!--                    <form action="--><?php //echo $_SERVER['REQUEST_URI']?><!--" method="post">-->
<!--                        <input name="form_name" type="hidden" value="--><?php //echo $currSelectionEscaped ?><!--"/>-->
<!--                        --><?php //wp_nonce_field(); ?>
<!--                        <input id="delete_wpcf7" name="delete_wpcf7" type="submit" class="button"-->
<!--                               value="--><?php //echo esc_attr(__('Remove _wpcf7 columns', 'contact-form-7-to-database-extension')) ?><!--"/>-->
                    </form>
                <?php } ?>
            </td>
        </tr>
        <?php
            if ($currSelection && $canEdit && $useDataTables) {
        ?>
        <tr>
            <td align="left" colspan="3">
                <span id="edit_controls">
                    <a href="https://cfdbplugin.com/?page_id=459" target="_cfdbedit"><?php  echo esc_html(__('Edit Data Mode', 'contact-form-7-to-database-extension')); ?></a>
                </span>
            </td>
        </tr>
        <?php
            }
        ?>
    </table>


    <?php
            if ($currSelection) {
            // Show table of form data
            if ($useDataTables) {
                $i18nUrl = $plugin->getDataTableTranslationUrl();

                // Work out the datatable menu for number or rows shown
                $maxVisible = $plugin->getOption('MaxVisibleRows', -1);
                if (!is_numeric($maxVisible)) {
                    $maxVisible = -1;
                }
                $menuJS = $this->createDatatableLengthMenuJavascriptString($maxVisible);

                $sScrollX = $plugin->getOption('HorizontalScroll', 'true', true) == 'true' ? '"100%"' : '""';
                ?>
            <script type="text/javascript" language="Javascript">
                var oTable;
                jQuery(document).ready(function() {
                    oTable = jQuery('#<?php echo $tableHtmlId ?>').dataTable({ <?php // "sDom": 'Rlfrtip', // ColReorder ?>
                        "bJQueryUI": true,
                        "aaSorting": [],
                        //"sScrollY": "400",
                        "bScrollCollapse": true,
                        "sScrollX": <?php echo $sScrollX ?>,
                        "iDisplayLength": <?php echo $maxVisible ?>,
                        "aLengthMenu": <?php echo $menuJS ?>
                        <?php
                        if ($i18nUrl) {
                            echo ", \"oLanguage\": { \"sUrl\":  \"$i18nUrl\" }";
                        }
                        if ($canEdit) {
                            do_action_ref_array('cfdb_edit_fnDrawCallbackJSON', array($tableHtmlId));
                        }

                        ?>
                    });
                    jQuery('th[id="delete_th"]').unbind('click'); <?php // Don't sort delete column ?>
                });

            </script>
            <?php

            }
            if ($canEdit) {
                ?>
        <form action="<?php echo $_SERVER['REQUEST_URI']?>" method="post">
            <input name="form_name" type="hidden" value="<?php echo $currSelectionEscaped ?>"/>
                <input id="cfdbdelete" name="cfdbdel" type="hidden" value="rows"/>
                <?php wp_nonce_field(); ?>
                <?php

            }
            ?>
            <?php
                $exporter = new ExportToHtmlTable();
            $dbRowCount = $exporter->getDBRowCount($currSelection);
            $maxRows = $plugin->getOption('MaxRows', '100', true);
            $startRow = $this->paginationDiv($plugin, $dbRowCount, $maxRows, $page);
            ?>
            <div <?php if (!$useDataTables) echo 'style="overflow:auto; max-height:500px; max-width:500px; min-width:75px"' ?>>
            <?php
                // Pick up any options that the user enters in the URL.
                // This will include extraneous "form_name" and "page" GET params which are in the URL
                // for this admin page
                $options = array_merge($_POST, $_GET);
                $options['canDelete'] = $canEdit;
                if ($maxRows) {
                    $limitStart = ($startRow < 1) ? 0 : ($startRow - 1);
                    $options['limit'] = "$limitStart,$maxRows";
                }
                if ($useDataTables) {
                    $options['id'] = $tableHtmlId;
                    $options['class'] = '';
                    $options['style'] = "#$tableHtmlId {padding:0;} #$tableHtmlId td > div { max-height: 100px;  min-width:75px; overflow: auto; font-size: small;}"; // don't let cells get too tall
                }
                $exporter->export($currSelection, $options);
                ?>
            </div>
            <?php if ($canEdit) {
                ?>
            </form>
        <?php

            }
        }
        ?>
        <script type="text/javascript">
            (function ($) {
                var url = "admin.php?page=<?php echo $plugin->getDBPageSlug() ?>&form_name=<?php echo urlencode($currSelection) ?>&submit_time=";
                $('td[title="Submitted"] div').each(
                        function () {
                            var submitTime = $(this).attr('id').split(",");
                            $(this).html('<a target="_cfdb_entry" href="' + url + submitTime[0] + '">' + $(this).html() + '</a>');
                        })
            })(jQuery);
        </script>
        <div id="cfdb-footer" style="margin-top:1em"> <?php // Footer ?>
        <table style="width:100%;">
            <tbody>
            <tr>
                <td align="center" colspan="4">
                    <span style="font-size:x-small; font-style: italic;">
                    <?php echo esc_html(__('Did you know: You can add this data to your posts and pages using these shortcodes:', 'contact-form-7-to-database-extension')); ?>
                        <br/>
                        <a target="_faq" href="https://cfdbplugin.com/?page_id=284">[cfdb-html]</a>
                        <a target="_faq" href="https://cfdbplugin.com/?page_id=91">[cfdb-datatable]</a>
                        <a target="_faq" href="https://cfdbplugin.com/?page_id=93">[cfdb-table]</a>
                        <a target="_faq" href="https://cfdbplugin.com/?page_id=98">[cfdb-value]</a>
                        <a target="_faq" href="https://cfdbplugin.com/?page_id=278">[cfdb-count]</a>
                        <a target="_faq" href="https://cfdbplugin.com/?page_id=96">[cfdb-json]</a>
                    </span>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="4">
                        <span style="font-size:x-small; font-style: italic;">
                            <?php echo esc_html(__('Would you like to help translate this plugin into your language?', 'contact-form-7-to-database-extension')); ?>
                            <a target="_i18n"
                               href="https://cfdbplugin.com/?page_id=7"><?php echo esc_html(__('How to create a translation', 'contact-form-7-to-database-extension')); ?></a>
                        </span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <?php
           if ($currSelection && 'true' == $plugin->getOption('ShowQuery', 'false', true)) {
            ?>
        <div id="query" style="direction: ltr; margin: 20px; border: dotted #d3d3d3 1pt;">
            <strong><?php echo esc_html(__('Query:', 'contact-form-7-to-database-extension')); ?></strong><br/>
            <pre><?php echo $exporter->getPivotQuery($currSelection); ?></pre>
        </div>
        <?php

        }
        if ($currSelection) {
            ?>
        <script type="text/javascript" language="Javascript">
            var addColumnLabelText = '<?php echo esc_attr(__('Add Column', 'contact-form-7-to-database-extension')); // input button value attribute ?>';
            var deleteColumnLabelText = '<?php echo esc_attr(__('Delete Column', 'contact-form-7-to-database-extension')); // input button value attribute ?>';
        </script>
        <?php
            do_action_ref_array('cfdb_edit_setup', array($plugin));
        }
        echo '</div>'; // cfdb-admin
    }

    /**
     * @param  $plugin CF7DBPlugin
     * @param  $totalRows integer
     * @param  $rowsPerPage integer
     * @param  $page integer
     * @return integer $startRow
     */
    protected function paginationDiv($plugin, $totalRows, $rowsPerPage, $page) {

        $nextLabel = __('next »', 'contact-form-7-to-database-extension');
        $prevLabel = __('« prev', 'contact-form-7-to-database-extension');

        echo '<link rel="stylesheet" href="';
        echo $plugin->getPluginFileUrl();
        echo '/css/paginate.css';
        echo '" type="text/css"/>';
        //        echo '<style type="text/css">';
        //        include('css/paginate.css');
        //        echo '</style>';


        if (!$page || $page < 1) $page = 1; //default to 1.
        $startRow = ($totalRows == 0) ? 1 : $rowsPerPage * ($page - 1) + 1;

        $endRow = min($startRow + $rowsPerPage - 1, $totalRows);
        if ($endRow <= 0) {
            $startRow = $endRow = 0;
        }
        echo '<span style="margin-bottom:5px;">';
        printf(__('Returned entries %s to %s of %s entries in the database', 'contact-form-7-to-database-extension'),
               $startRow, $endRow, $totalRows);
        echo '</span>';
        if ($endRow == 0) {
            return $startRow;
        }
        echo '<div class="cfdb_paginate">';

        $numPages = ($rowsPerPage > 0) ? ceil($totalRows / $rowsPerPage) : 1;
        $adjacents = 3;

        /* Setup page vars for display. */
        $prev = $page - 1; //previous page is page - 1
        $next = $page + 1; //next page is page + 1
        $lastpage = $numPages;
        $lpm1 = $lastpage - 1; //last page minus 1

        /*
            Now we apply our rules and draw the pagination object.
            We're actually saving the code to a variable in case we want to draw it more than once.
        */
        if ($lastpage > 1) {
            echo  "<div class=\"pagination\">";
            //previous button
            if ($page > 1)
                echo  $this->paginateLink($prev, $prevLabel);
            else
                echo  "<span class=\"disabled\">$prevLabel</span>";

            if ($lastpage < 7 + ($adjacents * 2)) //not enough pages to bother breaking it up
            {
                for ($counter = 1; $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page)
                        echo  "<span class=\"current\">$counter</span>";
                    else
                        echo  $this->paginateLink($counter, $counter);
                }
            }
            elseif ($lastpage > 5 + ($adjacents * 2)) //enough pages to hide some
            {
                //close to beginning; only hide later pages
                if ($page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                    {
                        if ($counter == $page)
                            echo  "<span class=\"current\">$counter</span>";
                        else
                            echo  $this->paginateLink($counter, $counter);
                    }
                    echo  '...';
                    echo  $this->paginateLink($lpm1, $lpm1);
                    echo  $this->paginateLink($lastpage, $lastpage);
                }
                    //in middle; hide some front and some back
                elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                {
                    echo  $this->paginateLink(1, 1);
                    echo  $this->paginateLink(2, 2);
                    echo  '...';
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                    {
                        if ($counter == $page)
                            echo  "<span class=\"current\">$counter</span>";
                        else
                            echo  $this->paginateLink($counter, $counter);
                    }
                    echo  '...';
                    echo  $this->paginateLink($lpm1, $lpm1);
                    echo  $this->paginateLink($lastpage, $lastpage);
                }
                    //close to end; only hide early pages
                else
                {
                    echo  $this->paginateLink(1, 1);
                    echo  $this->paginateLink(2, 2);
                    echo  '...';
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                    {
                        if ($counter == $page)
                            echo  "<span class=\"current\">$counter</span>";
                        else
                            echo  $this->paginateLink($counter, $counter);
                    }
                }
            }

            //next button
            if ($page < $counter - 1)
                echo  $this->paginateLink($next, $nextLabel);
            else
                echo  "<span class=\"disabled\">$nextLabel</span>";
            echo  "</div>\n";
        }

        // Next script is to hide the WP "Thank You" footer which can overlap the CFDB table.
        ?>
        <script type="text/javascript" language="Javascript">
            jQuery(document).ready(function () {
                jQuery('#wpfooter').hide();
            });
        </script>

        <?php
        echo '</div>';
        return $startRow;
    }

    protected function paginateLink($page, $label) {
        return "<a href=\"#\" onclick=\"changeDbPage('$page');\">$label</a>";
    }

    /**
     * Create aLengthMenu javascript string for databatable
     * @param $maxVisible
     * @return string
     */
    protected function createDatatableLengthMenuJavascriptString($maxVisible) {
        $numRowsMenu = array();
        $found = $maxVisible == -1;
        foreach (array(1, 2, 3, 4, 5, 10, 25, 50, 100) as $entry) {
            if ($found) {
                $numRowsMenu[] = $entry;
            } else {
                if ($maxVisible == $entry) {
                    $found = true;
                } else if ($maxVisible < $entry) {
                    $numRowsMenu[] = $maxVisible;
                    $found = true;
                }
                $numRowsMenu[] = $entry;
            }
        }
        if (!$found) {
            $numRowsMenu[] = $maxVisible;
        }
        $numRowsMenu[] = -1;

        $menuJS1 = '[[';
        $menuJS2 = ', [';
        foreach ($numRowsMenu as $val) {
            $menuJS1 .= $val . ',';
            if ($val == -1) {
                $val = '"' . __('All', 'contact-form-7-to-database-extension') . '"';
            }
            $menuJS2 .= $val . ',';
        }
        $menuJS1 = substr($menuJS1, 0, -1) . ']';
        $menuJS2 = substr($menuJS2, 0, -1) . ']]';
        return $menuJS1 . $menuJS2;
    }

}
