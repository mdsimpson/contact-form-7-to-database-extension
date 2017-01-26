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

require_once('ExportBase.php');
require_once('CFDBExport.php');
require_once('CFDBShortCodeContentParser.php');

class ExportToHtmlTable extends ExportBase implements CFDBExport {

    /**
     * @var bool
     */
    static $wroteDefaultHtmlTableStyle = false;

    var $useBom = false;

    public function setUseBom($use) {
        $this->useBom = $use;
    }

    /**
     * Echo a table of submitted form data
     * @param string $formName
     * @param array $options
     * @return void|string returns String when called from a short code,
     * otherwise echo's output and returns void
     */
    public function export($formName, $options = null) {
        $this->setOptions($options);
        $this->setCommonOptions(true);

        $canDelete = false;
        $useDT = false;
        $editMode = false;
        $printScripts = false;
        $printStyles = false;

        if ($options && is_array($options)) {
            if (isset($options['useDT'])) {
                $useDT = $options['useDT'];
                //$this->htmlTableClass = '';

                if (isset($options['printScripts'])) {
                    $printScripts = $options['printScripts'];
                }

                if (isset($options['printStyles'])) {
                    $printStyles = $options['printStyles'];
                }
                if (isset($options['edit'])) {
                    $this->dereferenceOption('edit');
                    $editMode = 'true' == $this->options['edit'] || 'cells' == $this->options['edit'];
                }
            }

            if (isset($options['canDelete'])) {
                $canDelete = $options['canDelete'];
            }
        }

        // Security Check
        if (!$this->isAuthorized()) {
            $this->assertSecurityErrorMessage();
            return;
        }
        if ($editMode && !$this->plugin->canUserDoRoleOption('CanChangeSubmitData')) {
            $editMode = false;
        }

        // Headers
        $this->echoHeaders('Content-Type: text/html; charset=UTF-8');

        // Query DB for the data for that form
        $submitTimeKeyName = 'Submit_Time_Key';
        $this->setDataIterator($formName, $submitTimeKeyName);
        //$this->clearAllOutputBuffers(); // will mess up the admin table view

        if ($this->isFromShortCode) {
            ob_start();
            if ($this->useBom) {
                // File encoding UTF-8 Byte Order Mark (BOM) http://wiki.sdn.sap.com/wiki/display/ABAP/Excel+files+-+CSV+format
                echo chr(239) . chr(187) . chr(191);
            }
        }
        else {
            if ($this->useBom) {
                // File encoding UTF-8 Byte Order Mark (BOM) http://wiki.sdn.sap.com/wiki/display/ABAP/Excel+files+-+CSV+format
                echo chr(239) . chr(187) . chr(191);
            }
            if ($printScripts) {
                $pluginUrl = plugins_url('/', __FILE__);
                wp_enqueue_script('datatables', $pluginUrl . 'DataTables/media/js/jquery.dataTables.min.js', array('jquery'));
                wp_print_scripts('datatables');
            }
            if ($printStyles) {
                $pluginUrl = plugins_url('/', __FILE__);
                wp_enqueue_style('datatables-demo', $pluginUrl .'DataTables/media/css/demo_table.css');
                wp_enqueue_style('jquery-ui.css', $pluginUrl . 'jquery-ui/jquery-ui.css');
                wp_print_styles(array('jquery-ui.css', 'datatables-demo'));
            }
        }

        // Break out sections: Before, Content, After
        $before = '';
        $content = '';
        $after = '';
        if (isset($options['content'])) {
            $contentParser = new CFDBShortCodeContentParser;
            list($before, $content, $after) = $contentParser->parseBeforeContentAfter($options['content']);
        }

        if ($before) {
            // Allow for short codes in "before"
            echo do_shortcode($before);
        }

        if ($useDT) {
            $dtJsOptions = isset($options['dt_options']) ?
                    $options['dt_options'] :
                    '"bJQueryUI": true, "aaSorting": [], "iDisplayLength": -1, "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "' . __('All', 'contact-form-7-to-database-extension') . '"]]';
            $i18nUrl = $this->plugin->getDataTableTranslationUrl();
            if ($i18nUrl) {
                if (!empty($dtJsOptions)) {
                    $dtJsOptions .= ',';
                }
                $dtJsOptions .=  " \"oLanguage\": { \"sUrl\":  \"$i18nUrl\" }";
            }
            $dtJsOptions = stripslashes($dtJsOptions); // unescape single quotes when posted via URL
            ?>
            <script type="text/javascript" language="Javascript">
                jQuery(document).ready(function() {
                    jQuery('#<?php echo $this->htmlTableId ?>').dataTable({
                        <?php
                            echo $dtJsOptions;
                            $editColumns = null;
                            if ($editMode) {
                                if (isset($this->options['editcolumns'])) {
                                    $editColumns = explode(',', $this->options['editcolumns']);
                                }
                                do_action_ref_array(
                                        'cfdb_edit_fnDrawCallbackJsonForSC', 
                                        array($this->htmlTableId, 
                                                $this->options['edit'],
                                                $editColumns));
                            }
                        ?> })
                });
            </script>
            <?php
        }

        if ($this->htmlTableClass == $this->defaultTableClass && !ExportToHtmlTable::$wroteDefaultHtmlTableStyle) {
            ?>
            <style type="text/css">
                table.<?php echo $this->defaultTableClass ?> {
                    margin-top: 1em;
                    border-spacing: 0;
                    border: 0 solid gray;
                    font-size: x-small;
                }

                br {
                    <?php /* Thanks to Alberto for this style which means that in Excel IQY all the text will
                     be in the same cell, not broken into different cells */ ?>
                    mso-data-placement: same-cell;
                }

                table.<?php echo $this->defaultTableClass ?> th {
                    padding: 5px;
                    border: 1px solid gray;
                }

                table.<?php echo $this->defaultTableClass ?> th > td {
                    font-size: x-small;
                    background-color: #E8E8E8;
                }

                table.<?php echo $this->defaultTableClass ?> tbody td {
                    padding: 5px;
                    border: 1px solid gray;
                    font-size: x-small;
                }

                table.<?php echo $this->defaultTableClass ?> tbody td > div {
                    max-height: 100px;
                    overflow: auto;
                }
            </style>
            <?php
            ExportToHtmlTable::$wroteDefaultHtmlTableStyle = true;
        }

        if ($this->style) {
            ?>
            <style type="text/css">
                <?php echo $this->style ?>
            </style>
            <?php
        }
        ?>

        <table <?php if ($this->htmlTableId) echo "id=\"$this->htmlTableId\" "; if ($this->htmlTableClass) echo "class=\"$this->htmlTableClass\"" ?> >
            <thead>
            <?php
            if (isset($this->options['header']) && $this->options['header'] != 'true') {
               // do not output column headers
            }
            else  {
            ?>
            <tr>
            <?php if ($canDelete) { ?>
            <th id="delete_th">
                <button id="delete" name="cfdbdel" class="button" onclick="this.form.submit()"><?php echo esc_html(__('Delete', 'contact-form-7-to-database-extension'))?></button>
                <input type="checkbox" id="selectall"/>
                <script type="text/javascript">
                    jQuery(document).ready(function() {
                        jQuery('#selectall').click(function() {
                            jQuery('#<?php echo $this->htmlTableId ?>').find('input[id^="delete_"]').attr('checked', this.checked);
                        });
                    });
                </script>
            </th>
            <?php

            }
            foreach ($this->dataIterator->getDisplayColumns() as $aCol) {
                $colDisplayValue = $aCol; // Sanitize below
                if ($this->headers && isset($this->headers[$aCol])) {
                    $colDisplayValue = $this->headers[$aCol];
                }
                printf('<th title="%s"><div id="%s,%s">%s</div></th>',
                        esc_attr($colDisplayValue),
                        esc_attr($formName),
                        esc_attr($aCol),
                        esc_html($colDisplayValue));
            }
            ?>
            </tr>
            <?php
            } ?>
            </thead>
            <tbody>
            <?php
            $showLineBreaks = $this->plugin->getOption('ShowLineBreaksInDataTable');
            $showLineBreaks = 'false' != $showLineBreaks;
            while ($this->dataIterator->nextRow()) {
                $submitKey = '';
                if (isset($this->dataIterator->row[$submitTimeKeyName])) {
                    $submitKey = $this->dataIterator->row[$submitTimeKeyName];
                }
                ?>
                <tr>
                <?php if ($canDelete && $submitKey) { // Put in the delete checkbox ?>
                    <td align="center">
                        <input type="checkbox" id="delete_<?php echo $submitKey ?>" name="<?php echo $submitKey ?>" value="row"/>
                    </td>
                <?php

                }

                $fields_with_file = null;
                if (isset($this->dataIterator->row['fields_with_file']) && $this->dataIterator->row['fields_with_file'] != null) {
                    $fields_with_file = explode(',', $this->dataIterator->row['fields_with_file']);
                }
                foreach ($this->dataIterator->getDisplayColumns() as $aCol) {
                    $cell = $this->rawValueToPresentationValue(
                        $this->dataIterator->row[$aCol],
                        $showLineBreaks,
                        ($fields_with_file && in_array($aCol, $fields_with_file)),
                        $this->dataIterator->row[$submitTimeKeyName],
                        $formName,
                        $aCol);

                    // NOTE: the ID field is used to identify the cell when an edit happens and we save that to the server
                    printf('<td title="%s"><div id="%s,%s">%s</div></td>',
                            esc_attr($aCol),
                            esc_attr($submitKey),
                            esc_attr($aCol),
                            $cell); // $cell sanitized by rawValueToPresentationValue()
                }
                ?></tr><?php

            } ?>
            </tbody>
        </table>
        <?php

        if ($after) {
            // Allow for short codes in "after"
            echo do_shortcode($after);
        }

        if ($this->isFromShortCode) {
            // If called from a shortcode, need to return the text,
            // otherwise it can appear out of order on the page
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        }
    }

    public function &rawValueToPresentationValue(&$value, $showLineBreaks, $isUrl, &$submitTimeKey, &$formName, &$fieldName) {
        $value = esc_html($value); // no HTML injection
        if ($showLineBreaks) {
            $value = str_replace("\r\n", '<br/>', $value); // preserve DOS line breaks
            $value = str_replace("\n", '<br/>', $value); // preserve UNIX line breaks
        }
        if ($isUrl) {
            $fileUrl = $this->plugin->getFileUrl($submitTimeKey, $formName, $fieldName);
            $value = "<a href=\"$fileUrl\">$value</a>";
        }

        return $value;
    }
}

