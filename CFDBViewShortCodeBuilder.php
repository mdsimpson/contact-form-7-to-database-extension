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

require_once('CF7DBPlugin.php');
require_once('CFDBView.php');
require_once('CFDBShortCodeContentParser.php');

class CFDBViewShortCodeBuilder extends CFDBView {

    /**
     * @var CF7DBPlugin
     */
    var $plugin;

    /**
     * @var String URL
     */
    var $infoImg;

    /**
     * @var String URL
     */
    var $siteUrl;

    /**
     * @param $plugin CF7DBPlugin
     * @return void
     */
    function display(&$plugin) {
        $this->plugin = $plugin == null ? $plugin : new CF7DBPlugin;
        $this->pageHeader($this->plugin);

        $this->siteUrl = get_option('home');
        $this->infoImg = $this->plugin->getPluginFileUrl('/img/info.jpg');

        $this->outputJavascript();
        $this->outputCSS();

        $this->outputSectionHeader();
        $this->outputTabLayout();
    }

    public function outputSectionHeader() {
        ?>
        <h2>
            <?php echo esc_html(__('Shortcode and Export Builder', 'contact-form-7-to-database-extension')) ?>
            <?php // RESET  ?>
            <span style="margin-left:10px">
                <button id="reset_button"
                        class="button"><?php echo esc_html(__('Reset', 'contact-form-7-to-database-extension')) ?></button>
            </span>
        </h2>
        <?php
    }

    public function outputTabLayout() {
        ?>
        <div class="cfdb_top_div">
            <div id="cfdb_top_tabs">
                <ul>
                    <li>
                        <a href="#tab_shortcode"><?php _e('Shortcode', 'contact-form-7-to-database-extension'); ?></a>
                    </li>
                    <li>
                        <a href="#tab_export"><?php _e('Export', 'contact-form-7-to-database-extension'); ?></a>
                    </li>
                </ul>
                <div id="tab_shortcode">
                    <?php $this->displayShortCodeControl(); ?>
                </div>
                <div id="tab_export">
                    <?php $this->displayExportControl(); ?>
                </div>
            </div>
        </div>
        <div class="cfdb_params_div">
            <div id="cfdb_params_tabs">
                <ul>
                    <li>
                        <a href="#tab_form"><?php _e('Form', 'contact-form-7-to-database-extension'); ?></a>
                    </li>
                    <li>
                        <a href="#tab_columns"><?php _e('Columns', 'contact-form-7-to-database-extension'); ?></a>
                    </li>
                    <li>
                        <a href="#tab_rows"><?php _e('Rows', 'contact-form-7-to-database-extension'); ?></a>
                    </li>
                    <li>
                        <a href="#tab_transform"><?php _e('Transform', 'contact-form-7-to-database-extension'); ?></a>
                    </li>
                    <li>
                        <a href="#tab_before_after"><?php _e('Before/After', 'contact-form-7-to-database-extension'); ?></a>
                    </li>
                    <li>
                        <a href="#tab_shortcode_specific"><?php _e('Shortcode Specific', 'contact-form-7-to-database-extension'); ?></a>
                    </li>
                    <li>
                        <a href="#tab_security"><?php _e('Security/Performance', 'contact-form-7-to-database-extension'); ?></a>
                    </li>
                </ul>
                <div id="tab_form">
                    <?php $this->displayFormControl(); ?>
                </div>
                <div id="tab_columns">
                    <?php $this->displayColumnControl(); ?>
                </div>
                <div id="tab_rows">
                    <?php $this->displayRowControl(); ?>
                </div>
                <div id="tab_transform">
                    <?php $this->displayTransformControl(); ?>
                </div>
                <div id="tab_before_after">
                    <?php $this->displayBeforeAfterControl(); ?>
                </div>
                <div id="tab_shortcode_specific">
                    <?php $this->displayShortCodeSpecificControl(); ?>
                </div>
                <div id="tab_security">
                    <?php $this->displaySecurityControl(); ?>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            function htmlEntities(str) {
                return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            }

            jQuery(function () {
                jQuery("#cfdb_top_tabs").tabs(
                        <?php
                        if (isset($_REQUEST['enc']) && !isset($_REQUEST['sc'])) {
                            // Make the Export tab selected initially
                            echo '{active: 1}';
                        }
                        ?>
                );
                jQuery("#cfdb_params_tabs").tabs();


            });
        </script>
        <?php
    }

    public function displayShortCodeControl() {
        ?>
        <div>
            <div style="margin-bottom:10px">
                <div class="label_box"><label
                            for="shortcode_ctrl"><?php echo esc_html(__('Shortcode', 'contact-form-7-to-database-extension')); ?></label>
                </div>
                <select name="shortcode_ctrl" id="shortcode_ctrl">
                    <option value=""><?php echo esc_html(__('* Select a short code *', 'contact-form-7-to-database-extension')); ?></option>
                    <option value="[cfdb-html]">[cfdb-html]</option>
                    <option value="[cfdb-table]">[cfdb-table]</option>
                    <option value="[cfdb-datatable]">[cfdb-datatable]</option>
                    <option value="[cfdb-value]">[cfdb-value]</option>
                    <option value="[cfdb-count]">[cfdb-count]</option>
                    <option value="[cfdb-json]">[cfdb-json]</option>
                    <option value="[cfdb-export-link]">[cfdb-export-link]</option>
                </select>
                <a id="doc_url_tag" target="_docs"
                   href="https://cfdbplugin.com/?page_id=89"><?php echo esc_html(__('Documentation', 'contact-form-7-to-database-extension')); ?></a>
                <br/>
            </div>


            <div id="shortcode_result_div">
                <?php echo esc_html(__('Generated Shortcode:', 'contact-form-7-to-database-extension')); ?>
                <br/>
                <div class="generated" id="shortcode_result_text"></div>
            </div>
            <div id="shortcode_validations_text" class="validation"></div>
        <span style="font-size: x-small;">
            <a target="_docs"
               href="https://cfdbplugin.com/?page_id=444"><?php echo esc_html(__('(Did you know: you can create your own short code)', 'contact-form-7-to-database-extension')); ?></a>
        </span>

        </div>
        <?php
    }

    public function displayExportControl() {
        $user = wp_get_current_user();
        $userName = $user ? $user->user_login : '';
        ?>
        <div>
            <label for="export_cntl"><?php echo esc_html(__('Export File', 'contact-form-7-to-database-extension')); ?></label>
            <select id="export_cntl" name="export_cntl">
                <option value=""></option>
                <option value="xlsx">
                    <?php echo esc_html(__('Excel .xlsx', 'contact-form-7-to-database-extension')); ?>
                </option>
                <option value="ods">
                    <?php echo esc_html(__('OpenDocument .ods', 'contact-form-7-to-database-extension')); ?>
                </option>
                <option value="CSVUTF8BOM">
                    <?php echo esc_html(__('Excel CSV (UTF8-BOM)', 'contact-form-7-to-database-extension')); ?>
                </option>
                <option value="TSVUTF16LEBOM">
                    <?php echo esc_html(__('Excel TSV (UTF16LE-BOM)', 'contact-form-7-to-database-extension')); ?>
                </option>
                <option value="CSVUTF8">
                    <?php echo esc_html(__('Plain CSV (UTF-8)', 'contact-form-7-to-database-extension')); ?>
                </option>
                <option value="CSVSJIS">
                    <?php echo esc_html(__('Excel CSV for Japanese (Shift-JIS)', 'contact-form-7-to-database-extension')); ?>
                </option>
                <option value="IQY">
                    <?php echo esc_html(__('Excel Internet Query', 'contact-form-7-to-database-extension')); ?>
                </option>
                <option value="GLD">
                    <?php echo esc_html(__('Google Spreadsheet Live Data', 'contact-form-7-to-database-extension')); ?>
                </option>
                <option value="RSS">
                    <?php echo esc_html(__('RSS', 'contact-form-7-to-database-extension')); ?>
                </option>
                <option value="JSON">
                    <?php echo esc_html(__('JSON', 'contact-form-7-to-database-extension')); ?>
                </option>
            </select>
        <span id="csvdelim_span">
            <label for="csv_delim"><?php echo esc_html(__('CSV Delimiter', 'contact-form-7-to-database-extension')); ?></label>
            <input id="csv_delim" type="text" size="2" value=""/>
        </span>
        <span id="itemtitle_span">
            <label for="add_itemtitle"><?php echo esc_html(__('Item Title', 'contact-form-7-to-database-extension')); ?></label>
            <select name="add_itemtitle" id="add_itemtitle"></select>
        </span>
        <span id="userpass_span">
            <br/>
            <span id="gld_userpass_span_msg">
            <?php echo esc_html(__('Provide a WP login for the Google Spreadsheet to use to connect to your WP site', 'contact-form-7-to-database-extension')); ?>
            </span>
            <span id="userpass_span_msg" style="display: none">
            <?php echo esc_html(__('Optional: provide a WP login for the link to work without being already logged in', 'contact-form-7-to-database-extension')); ?>
            </span>
            <br/>
            <label for="gld_user"><?php echo esc_html(__('WP User', 'contact-form-7-to-database-extension')); ?></label>
            <input id="gld_user" type="text" value="<?php echo esc_attr($userName); ?>"/>
            <label for="gld_pass"><?php echo esc_html(__('WP Password', 'contact-form-7-to-database-extension')); ?></label>
            <input id="gld_pass" type="password" value=""/>
            <input id="obfuscate_cntl" type="checkbox"
                   checked/><?php echo esc_html(__('Hide Credentials', 'contact-form-7-to-database-extension')); ?>
        </span>

            <div id="export_result_div">
                <span id="label_export_link"><?php echo esc_html(__('Generated Export Link:', 'contact-form-7-to-database-extension')); ?></span>
            <span id="label_gld_function" style="display:none">
                <?php echo esc_html(__('Enter this function into a cell in your Google Spreadsheet:', 'contact-form-7-to-database-extension')); ?>
            </span>
            <span id="label_gld_script" style="display:none">
                <?php echo esc_html(__('Generated Google Spreadsheet Function:', 'contact-form-7-to-database-extension')); ?>
                <?php _e('Replace <strong>&lt;password&gt;</strong> with your <em>WordPress</em> password', 'contact-form-7-to-database-extension'); ?>
                <br/>
                <?php echo esc_html(__('Requires code installed in your Google Spreadsheet script editor.')); ?>
                <a target="code"
                   href="<?php echo plugins_url('CFDBGoogleSSLiveData.php', __FILE__) ?>"><?php echo esc_html(__('Get code', 'contact-form-7-to-database-extension')); ?></a>.
                <a target="instructions" id="gld_instructions" href="<?php echo admin_url('admin-ajax.php?action=cfdb-export&enc=GLD&form=' . urlencode(isset($_REQUEST['form']) ? $_REQUEST['form'] : '')) ?>">
                    <?php echo esc_html(__('See instructions.', 'contact-form-7-to-database-extension')); ?>
                </a>
          </span>
                <br/>
                <div class="generated" id="export_result_text"></div>
            </div>
            <div id="export_validations_text" class="validation"></div>
        </div>
        <?php
    }

    public function displayFormControl() {
        // Identify which forms have data in the database
        global $wpdb;
        $tableName = $this->plugin->getSubmitsTableName();
        $rows = $wpdb->get_results("select distinct `form_name` from `$tableName` order by `form_name`");

        ?>
        <div class="shortcodeoptions">
            <select name="form_name_cntl" id="form_name_cntl" multiple size="20">
                <?php
                foreach ($rows as $aRow) {
                    $formName = $aRow->form_name;
                    ?>
                    <option value="<?php echo esc_html($formName) ?>"><?php echo esc_html($formName) ?></option>
                    <?php
                }
                ?>
                <option value="*"><?php echo esc_html(__('* All Forms *', 'contact-form-7-to-database-extension')) ?></option>
            </select>

        </div>
        <?php
    }

    public function displayColumnControl() {
        ?>
        <div id="show_hide_div" class="shortcodeoptions">
            <?php echo esc_html(__('Which fields/columns do you want to display?', 'contact-form-7-to-database-extension')); ?>
            <div>
                <div class="label_box">
                    <label for="show_cntl"><?php echo esc_html(__('show', 'contact-form-7-to-database-extension')); ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=89#show"><img alt="?"
                                                                                         src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <select name="add_show" id="add_show" class="button"></select>
                <button id="btn_show" class="button">&raquo;</button>
                <input name="show_cntl" id="show_cntl" type="text" size="100"
                       placeholder="<?php echo esc_attr(__('field1,field2,field3', 'contact-form-7-to-database-extension')) ?>"/>
            </div>
            <div>
                <div class="label_box">
                    <label for="hide_cntl"><?php echo esc_html(__('hide', 'contact-form-7-to-database-extension')); ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=89#hide"><img alt="?"
                                                                                         src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <select name="add_hide" id="add_hide" class="button"></select>
                <button id="btn_hide" class="button">&raquo;</button>
                <input name="hide_cntl" id="hide_cntl" type="text" size="100"
                       placeholder="<?php esc_attr(__('field1,field2,field3', 'contact-form-7-to-database-extension')) ?>"/>
            </div>
        </div>
        <?php // HEADERS  ?>
        <div id="headers_div" class="shortcodeoptions">
            <div><?php echo esc_html(__('Table Headers', 'contact-form-7-to-database-extension')); ?></div>
            <div>
                <div class="label_box">
                    <input id="header_cntl" type="checkbox" checked/>
                    <label for="header_cntl"><?php echo esc_html(__('Include Header Row', 'contact-form-7-to-database-extension')); ?></label>
                </div>
            </div>
            <div>
                <div class="label_box">
                    <label for="headers_cntl"><?php echo esc_html(__('headers', 'contact-form-7-to-database-extension')); ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=93#headers"><img alt="?"
                                                                                            src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <select name="add_headers" id="add_headers"></select>
                <?php echo esc_html(__('display as', 'contact-form-7-to-database-extension')); ?>
                <input name="headers_val" id="headers_val" type="text" size="20"
                       placeholder="<?php echo esc_attr(__('display value', 'contact-form-7-to-database-extension')); ?>"/>
                <button id="btn_headers" class="button">&raquo;</button>
                <br/>
                <input name="headers_cntl" id="headers_cntl" type="text" size="100"
                       placeholder="<?php echo esc_attr(__('field1=Display Name 1,field2=Display Name 2', 'contact-form-7-to-database-extension')); ?>"/>
            </div>
        </div>

        <?php
    }

    public function displayRowControl() {
        ?>
        <?php // SEARCH FILTER  ?>
        <div id="filter_div" class="shortcodeoptions">
            <div><?php echo esc_html(__('Which rows/submissions do you want to display?', 'contact-form-7-to-database-extension')); ?></div>
            <div>
                <div class="label_box">
                    <label for="search_cntl"><?php echo esc_html(__('search', 'contact-form-7-to-database-extension')) ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=89#search"><img alt="?"
                                                                                           src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <input name="search_cntl" id="search_cntl" type="text" size="30"
                       placeholder="<?php echo esc_attr(__('search text', 'contact-form-7-to-database-extension')) ?>"/>
            </div>
            <div>
                <div class="label_box">
                    <label for="filter_cntl"><?php echo esc_html(__('filter', 'contact-form-7-to-database-extension')) ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=89#filter"><img alt="?"
                                                                                           src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <select name="filter_bool" id="filter_bool">
                    <option value="&&">&&</option>
                    <option value="||">||</option>
                </select>
                <select name="add_filter" id="add_filter"></select>
                <select name="filter_op" id="filter_op">
                    <option value="=">=</option>
                    <option value="!=">!=</option>
                    <option value=">">></option>
                    <option value="<"><</option>
                    <option value=">=">>=</option>
                    <option value="<="><=</option>
                    <option value="===">===</option>
                    <option value="!==">!==</option>
                    <option value="[like]">[like]</option>
                    <option value="~~">~~</option>
                    <option value="[in]">[in]</option>
                    <option value="[!in]">[!in]</option>
                </select>
                <input name="filter_val" id="filter_val" type="text" size="20"
                       placeholder="<?php echo esc_attr(__('value', 'contact-form-7-to-database-extension')) ?>"/>
                <button id="btn_filter" class="button">&raquo;</button>
            <span id="span_validate_submit_time" style="display:none;">
                <button id="btn_validate_submit_time"
                        class="button"><?php echo esc_html(__('Validate submit_time', 'contact-form-7-to-database-extension')); ?></button>
                <a target="_blank"
                   href="https://cfdbplugin.com/?page_id=553"><?php echo esc_html(__('Formats', 'contact-form-7-to-database-extension')); ?></a>
            </span>
                <br/>
                <input name="filter_cntl" id="filter_cntl" type="text" size="100"
                       placeholder="<?php echo esc_attr(__('filter expression', 'contact-form-7-to-database-extension')) ?>"/>
            </div>
        </div>
        <?php // LIMIT, ORDER BY, RANDOM ?>
        <div id="limitorder_div" class="shortcodeoptions">
            <div>
                <div class="label_box">
                    <label for="limit_rows_cntl"><?php echo esc_html(__('limit', 'contact-form-7-to-database-extension')) ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=89#limit"><img alt="?"
                                                                                          src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <?php echo esc_html(__('Num Rows', 'contact-form-7-to-database-extension')); ?> <input
                        name="limit_rows_cntl" id="limit_rows_cntl" type="text" size="10"
                        placeholder="<?php echo esc_attr(__('number', 'contact-form-7-to-database-extension')); ?>"/>
                <?php echo esc_html(__('Start Row (0)', 'contact-form-7-to-database-extension')); ?> <input
                        name="limit_start_cntl" id="limit_start_cntl" type="text" size="10"
                        placeholder="<?php echo esc_attr(__('number', 'contact-form-7-to-database-extension')); ?>"/>
            </div>
            <div>
                <div class="label_box">
                    <label for="random_cntl"><?php echo esc_html(__('random', 'contact-form-7-to-database-extension')) ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=89#random"><img alt="?"
                                                                                           src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <input name="random_cntl" id="random_cntl" type="text" size="10"
                       placeholder="<?php echo esc_attr(__('number', 'contact-form-7-to-database-extension')) ?>"/>
            </div>
            <div id="orderby_div">
                <div class="label_box">
                    <label for="orderby_cntl"><?php echo esc_html(__('orderby', 'contact-form-7-to-database-extension')) ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=89#orderby"><img alt="?"
                                                                                            src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <select name="add_orderby" id="add_orderby"></select>
                <button id="btn_orderby" class="button"
                        placeholder="<?php echo esc_attr(__('field', 'contact-form-7-to-database-extension')) ?>">&raquo;</button>
                <input name="orderby_cntl" id="orderby_cntl" type="text" size="100"
                       placeholder="<?php echo esc_attr(__('field1,field2,field3', 'contact-form-7-to-database-extension')) ?>"/>
                <select id="orderbydir_cntl" name="orderbydir_cntl">
                    <option value=""></option>
                    <option value="ASC"><?php echo esc_html(__('ASC', 'contact-form-7-to-database-extension')) ?></option>
                    <option value="DESC"><?php echo esc_html(__('DESC', 'contact-form-7-to-database-extension')) ?></option>
                </select>
            </div>
        </div>
        <?php
    }

    public function displayTransformControl() {
        ?>
        <div id="trans_div" class="shortcodeoptions">
            <?php echo esc_html(__('Transform', 'contact-form-7-to-database-extension')); ?>
            <div>
                <div class="label_box">
                    <label for="trans_cntl"><?php echo esc_html(__('trans', 'contact-form-7-to-database-extension')) ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=1118#trans"><img alt="?"
                                                                                            src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <select name="add_trans" id="add_trans"></select>
                <input name="trans_val" id="trans_val" type="text" size="20"
                       placeholder="<?php echo esc_attr(__('PHP function or class', 'contact-form-7-to-database-extension')) ?>"/>
                <button id="btn_trans" class="button">&raquo;</button>
                <br/>
                <input name="trans_cntl" id="trans_cntl" type="text" size="100"
                       placeholder="<?php echo esc_attr(__('transform expression', 'contact-form-7-to-database-extension')) ?>"/>
            </div>
            <div>
                <div class="label_box">
                    <label for="tsearch_cntl"><?php echo esc_html(__('tsearch', 'contact-form-7-to-database-extension')) ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=1118#tsearch"><img alt="?"
                                                                                              src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <input name="tsearch_cntl" id="tsearch_cntl" type="text" size="30"
                       placeholder="<?php echo esc_attr(__('search text', 'contact-form-7-to-database-extension')) ?>"/>
            </div>
            <div>
                <div class="label_box">
                    <label for="tfilter_cntl"><?php echo esc_html(__('tfilter', 'contact-form-7-to-database-extension')) ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=1118#tfilter"><img alt="?"
                                                                                              src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <select name="tfilter_bool" id="tfilter_bool">
                    <option value="&&">&&</option>
                    <option value="||">||</option>
                </select>
                <select name="add_tfilter" id="add_tfilter"></select>
                <select name="tfilter_op" id="tfilter_op">
                    <option value="=">=</option>
                    <option value="!=">!=</option>
                    <option value=">">></option>
                    <option value="<"><</option>
                    <option value=">=">>=</option>
                    <option value="<="><=</option>
                    <option value="===">===</option>
                    <option value="!==">!==</option>
                    <option value="~~">~~</option>
                    <option value="[in]">[in]</option>
                    <option value="[!in]">[!in]</option>
                </select>
                <input name="tfilter_val" id="tfilter_val" type="text" size="20"
                       placeholder="<?php echo esc_attr(__('value', 'contact-form-7-to-database-extension')) ?>"/>
                <button id="btn_tfilter" class="button">&raquo;</button>
            <span id="span_validate_submit_time" style="display:none;">
                <button id="btn_validate_submit_time"
                        class="button"><?php echo esc_html(__('Validate submit_time', 'contact-form-7-to-database-extension')); ?></button>
                <a target="_blank"
                   href="https://cfdbplugin.com/?page_id=553"><?php echo esc_html(__('Formats', 'contact-form-7-to-database-extension')); ?></a>
            </span>
                <br/>
                <input name="tfilter_cntl" id="tfilter_cntl" type="text" size="100"
                       placeholder="<?php echo esc_attr(__('filter expression', 'contact-form-7-to-database-extension')) ?>"/>
            </div>
            <div>
                <div class="label_box">
                    <label for="tlimit_rows_cntl"><?php echo esc_html(__('tlimit', 'contact-form-7-to-database-extension')) ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=1118#tlimit"><img alt="?"
                                                                                             src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <?php echo esc_html(__('Num Rows', 'contact-form-7-to-database-extension')) ?> <input
                        name="tlimit_rows_cntl" id="tlimit_rows_cntl" type="text" size="10"
                        placeholder="<?php echo esc_attr(__('number', 'contact-form-7-to-database-extension')) ?>"/>
                <?php echo esc_html(__('Start Row (0)', 'contact-form-7-to-database-extension')) ?> <input
                        name="tlimit_start_cntl" id="tlimit_start_cntl" type="text" size="10"
                        placeholder="<?php echo esc_attr(__('number', 'contact-form-7-to-database-extension')) ?>"/>
            </div>
            <div id="torderby_div">
                <div class="label_box">
                    <label for="torderby_cntl"><?php echo esc_html(__('torderby', 'contact-form-7-to-database-extension')) ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=1118#torderby"><img alt="?"
                                                                                               src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <select name="add_torderby" id="add_torderby"></select>
                <button id="btn_torderby" class="button"
                        placeholder="<?php echo esc_attr(__('field', 'contact-form-7-to-database-extension')) ?>">&raquo;</button>
                <input name="torderby_cntl" id="torderby_cntl" type="text" size="100"
                       placeholder="<?php echo esc_attr(__('field1,field2,field3', 'contact-form-7-to-database-extension')) ?>"/>
                <select id="torderbydir_cntl" name="torderbydir_cntl">
                    <option value=""></option>
                    <option value="ASC"><?php echo esc_html(__('ASC', 'contact-form-7-to-database-extension')) ?></option>
                    <option value="DESC"><?php echo esc_html(__('DESC', 'contact-form-7-to-database-extension')) ?></option>
                </select>
            </div>
        </div>
        <?php
    }

    public function displayBeforeAfterControl() {
        ?>
        <div id="beforeafter_div" class="shortcodeoptions">
            <div>
                <div class="label_box">
                    <label for="before_cntl"><?php echo esc_html(__('Before', 'contact-form-7-to-database-extension')); ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=284#before"><img alt="?"
                                                                                            src="<?php echo $this->infoImg ?>"/></a><br/>
                </div>
                <br/>
                <textarea name="before_cntl" id="before_cntl" cols="100" rows="5"
                          placeholder="<?php echo esc_attr(__('Optional HTML/Javascript before the short code output', 'contact-form-7-to-database-extension')); ?>"></textarea>
            </div>
            <div>
                <div class="label_box">
                    <label for="after_cntl"><?php echo esc_html(__('After', 'contact-form-7-to-database-extension')); ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=284#after"><img alt="?"
                                                                                           src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <br/>
                <textarea name="after_cntl" id="after_cntl" cols="100" rows="5"
                          placeholder="<?php echo esc_attr(__('Optional HTML/Javascript after the short code output', 'contact-form-7-to-database-extension')); ?>"></textarea>
            </div>
        </div>
        <?php
    }

    public function displayShortCodeSpecificControl() {
        ?>
        <?php // ID, CLASS, STYLE  ?>
        <div id="html_format_div" class="shortcodeoptions">
            <div><?php echo esc_html(__('HTML Table Formatting', 'contact-form-7-to-database-extension')); ?></div>
            <div>
                <div class="label_box">
                    <label for="id_cntl"><?php echo esc_html(__('id', 'contact-form-7-to-database-extension')); ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=93#id"><img alt="?"
                                                                                       src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <input name="id_cntl" id="id_cntl" type="text" size="10"
                       placeholder="<?php echo esc_attr(__('HTML id', 'contact-form-7-to-database-extension')); ?>"/>
            </div>
            <div>
                <div class="label_box">
                    <label for="class_cntl"><?php echo esc_html(__('class', 'contact-form-7-to-database-extension')); ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=93#class"><img alt="?"
                                                                                          src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <input name="class_cntl" id="class_cntl" type="text" size="10"
                       placeholder="<?php echo esc_attr(__('HTML class', 'contact-form-7-to-database-extension')); ?>"/>
            </div>
            <div>
                <div class="label_box">
                    <label for="style_cntl"><?php echo esc_html(__('style', 'contact-form-7-to-database-extension')); ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=93#style"><img alt="?"
                                                                                          src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <input name="style_cntl" id="style_cntl" type="text" size="100"
                       placeholder="<?php echo esc_attr(__('CSS style', 'contact-form-7-to-database-extension')); ?>"/>
            </div>
        </div>
        <?php // DT_OPTIONS  ?>
        <div id="dt_options_div" class="shortcodeoptions">
            <div><?php echo esc_html(__('[cfdb-datatable] Options', 'contact-form-7-to-database-extension')); ?></div>
            <div id="edit_mode_div">
                <div class="label_box">
                    <label for="edit_mode_cntl"><?php echo esc_html(__('edit', 'contact-form-7-to-database-extension')); ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=91#edit"><img alt="?"
                                                                                         src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <select id="edit_mode_cntl" name="edit_mode_cntl">
                    <option value=""></option>
                    <option value="true"><?php echo esc_html(__('true', 'contact-form-7-to-database-extension')); ?></option>
                    <option value="cells"><?php echo esc_html(__('cells', 'contact-form-7-to-database-extension')); ?></option>
                </select>
            </div>
            <div>
                <div class="label_box" id="editcolumns">
                    <label for="editcolumns_cntl"><?php echo esc_html(__('editcolumns', 'contact-form-7-to-database-extension')) ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=91#editcolumns"><img alt="?"
                                                                                                src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <select name="add_editcolumns" id="add_editcolumns"></select>
                <button id="btn_editcolumns" class="button"
                        placeholder="<?php echo esc_attr(__('field', 'contact-form-7-to-database-extension')) ?>">&raquo;</button>
                <input name="editcolumns_cntl" id="editcolumns_cntl" type="text" size="100"
                       placeholder="<?php echo esc_attr(__('field1,field2,field3', 'contact-form-7-to-database-extension')) ?>"/>
            </div>
            <div>
                <div class="label_box">
                    <label for="dt_options_cntl"><?php echo esc_html(__('dt_options', 'contact-form-7-to-database-extension')) ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=91#dt_options"><img alt="?"
                                                                                               src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <input name="dt_options_cntl" id="dt_options_cntl" type="text" size="100"
                       placeholder="<?php echo esc_attr(__('datatable options (JSON)', 'contact-form-7-to-database-extension')) ?>"/>
            </div>
        </div>
        <?php // JSON VAR, FORMAT  ?>
        <div id="json_div" class="shortcodeoptions">
            <div><?php echo esc_html(__('[cfdb-json] Options', 'contact-form-7-to-database-extension')); ?></div>
            <div>
                <div class="label_box">
                    <label for="var_cntl"><?php echo esc_html(__('var', 'contact-form-7-to-database-extension')) ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=96#var"><img alt="?"
                                                                                        src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <input name="var_cntl" id="var_cntl" type="text" size="10"
                       placeholder="<?php echo esc_attr(__('JS var name', 'contact-form-7-to-database-extension')) ?>"/>
            </div>
            <div>
                <div class="label_box">
                    <label for="format_cntl"><?php echo esc_html(__('format', 'contact-form-7-to-database-extension')) ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=96#format"><img alt="?"
                                                                                           src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <select id="format_cntl" name="format_cntl">
                    <option value=""></option>
                    <option value="map"><?php echo esc_html(__('map', 'contact-form-7-to-database-extension')) ?></option>
                    <option value="array"><?php echo esc_html(__('array', 'contact-form-7-to-database-extension')) ?></option>
                    <option value="arraynoheader"><?php echo esc_html(__('arraynoheader', 'contact-form-7-to-database-extension')) ?></option>
                </select>
            </div>
        </div>
        <?php // VALUE FUNCTION, DELIMITER  ?>
        <div id="value_div" class="shortcodeoptions">
            <div><?php echo esc_html(__('[cfdb-value] Options', 'contact-form-7-to-database-extension')); ?></div>
            <div>
                <div class="label_box">
                    <label for="function_cntl"><?php echo esc_html(__('function', 'contact-form-7-to-database-extension')) ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=98#function"><img alt="?"
                                                                                             src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <select id="function_cntl" name="function_cntl">
                    <option value=""></option>
                    <option value="min"><?php echo esc_html(__('min', 'contact-form-7-to-database-extension')) ?></option>
                    <option value="max"><?php echo esc_html(__('max', 'contact-form-7-to-database-extension')) ?></option>
                    <option value="sum"><?php echo esc_html(__('sum', 'contact-form-7-to-database-extension')) ?></option>
                    <option value="mean"><?php echo esc_html(__('mean', 'contact-form-7-to-database-extension')) ?></option>
                    <option value="percent"><?php echo esc_html(__('percent', 'contact-form-7-to-database-extension')) ?></option>
                </select>
            </div>
            <div>
                <div class="label_box">
                    <label for="delimiter_cntl"><?php echo esc_html(__('delimiter', 'contact-form-7-to-database-extension')) ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=98#delimiter"><img alt="?"
                                                                                              src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <input name="delimiter_cntl" id="delimiter_cntl" type="text" size="10"/>
            </div>
        </div>
        <?php // HTML TEMPLATE  ?>
        <div id="template_div" class="shortcodeoptions">
            <div><?php echo esc_html(__('[cfdb-html] Options', 'contact-form-7-to-database-extension')); ?></div>
            <div>
                <div class="label_box">
                    <label for="filelinks_cntl"><?php echo esc_html(__('filelinks', 'contact-form-7-to-database-extension')) ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=284#filelinks"><img alt="?"
                                                                                               src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <select id="filelinks_cntl" name="filelinks_cntl">
                    <option value=""></option>
                    <option value="url"><?php echo esc_html(__('url', 'contact-form-7-to-database-extension')) ?></option>
                    <option value="name"><?php echo esc_html(__('name', 'contact-form-7-to-database-extension')) ?></option>
                    <option value="link"><?php echo esc_html(__('link', 'contact-form-7-to-database-extension')) ?></option>
                    <option value="img"><?php echo esc_html(__('img', 'contact-form-7-to-database-extension')) ?></option>
                </select>
                <div class="label_box">
                    <label for="stripbr_cntl"><?php echo esc_html(__('stripbr', 'contact-form-7-to-database-extension')) ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=284#stripbr"><img alt="?"
                                                                                             src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <select id="stripbr_cntl" name="stripbr_cntl">
                    <option value=""></option>
                    <option value="false"><?php echo esc_html(__('false', 'contact-form-7-to-database-extension')) ?></option>
                    <option value="true"><?php echo esc_html(__('true', 'contact-form-7-to-database-extension')) ?></option>
                </select>
                <div class="label_box">
                    <label for="wpautop_cntl"
                           style="text-decoration:line-through;"><?php echo esc_html(__('wpautop', 'contact-form-7-to-database-extension')) ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=284#wpautop"><img alt="?"
                                                                                             src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <select id="wpautop_cntl" name="wpautop_cntl">
                    <option value=""></option>
                    <option value="false"><?php echo esc_html(__('false', 'contact-form-7-to-database-extension')) ?></option>
                    <option value="true"><?php echo esc_html(__('true', 'contact-form-7-to-database-extension')) ?></option>
                </select>
            </div>
            <div>
                <div class="label_box">
                    <label for="content_cntl"><?php echo esc_html(__('Template', 'contact-form-7-to-database-extension')) ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=284#template"><img alt="?"
                                                                                              src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <select name="add_content" id="add_content"></select>
                <button id="btn_content" class="button">&raquo;</button>
                <br/>
                <textarea name="content_cntl" id="content_cntl" cols="100" rows="10"
                          placeholder="<?php echo esc_attr(__('Per-entry HTML using ${field name} variables', 'contact-form-7-to-database-extension')); ?>"></textarea>
            </div>
        </div>
        <?php // URL ENC, URL_ONLY LINK_TEXT      ?>
        <div id="url_link_div" class="shortcodeoptions">
            <div><?php echo esc_html(__('[cfdb-export-link] Options', 'contact-form-7-to-database-extension')); ?></div>
            <div>
                <div class="label_box">
                    <label for="enc_cntl"><?php echo esc_html(__('enc', 'contact-form-7-to-database-extension')); ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=419"><img alt="?"
                                                                                     src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <select id="enc_cntl" name="enc_cntl">
                    <option value=""></option>
                    <option id="xlsx" value="xlsx">
                        <?php echo esc_html(__('Excel .xlsx', 'contact-form-7-to-database-extension')); ?>
                    </option>
                    <option id="ods" value="ods">
                        <?php echo esc_html(__('OpenDocument .ods', 'contact-form-7-to-database-extension')); ?>
                    </option>
                    <option id="CSVUTF8BOM" value="CSVUTF8BOM">
                        <?php echo esc_html(__('Excel CSV (UTF8-BOM)', 'contact-form-7-to-database-extension'));; ?>
                    </option>
                    <option id="TSVUTF16LEBOM" value="TSVUTF16LEBOM">
                        <?php echo esc_html(__('Excel TSV (UTF16LE-BOM)', 'contact-form-7-to-database-extension')); ?>
                    </option>
                    <option id="CSVUTF8" value="CSVUTF8">
                        <?php echo esc_html(__('Plain CSV (UTF-8)', 'contact-form-7-to-database-extension')); ?>
                    </option>
                    <option value="CSVSJIS">
                        <?php echo esc_html(__('Excel CSV for Japanese (Shift-JIS)', 'contact-form-7-to-database-extension')); ?>
                    </option>
                    <option id="IQY" value="IQY">
                        <?php echo esc_html(__('Excel Internet Query', 'contact-form-7-to-database-extension')); ?>
                    </option>
                </select>
            <span id="export_link_csvdelim_span" style="display:none">
                <label for="export_link_csv_delim"><?php echo esc_html(__('CSV Delimiter', 'contact-form-7-to-database-extension')); ?></label>
                <input id="export_link_csv_delim" type="text" size="2" value=""/>
            </span>
            </div>
            <div>
                <div class="label_box">
                    <label for="urlonly_cntl"><?php echo esc_html(__('urlonly', 'contact-form-7-to-database-extension')); ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=419#urlonly"><img alt="?"
                                                                                             src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <select id="urlonly_cntl" name="urlonly_cntl">
                    <option value=""></option>
                    <option value="true"><?php echo esc_html(__('true', 'contact-form-7-to-database-extension')); ?></option>
                    <option value="false"><?php echo esc_html(__('false', 'contact-form-7-to-database-extension')); ?></option>
                </select>
            </div>
            <div>
                <div class="label_box">
                    <label for="linktext_cntl"><?php echo esc_html(__('linktext', 'contact-form-7-to-database-extension')); ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=419#linktext"><img alt="?"
                                                                                              src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <input name="linktext_cntl" id="linktext_cntl" type="text" size="30"/>
            </div>
        </div>
        <?php
    }

    public function displaySecurityControl() {
        ?>
        <div id="security_div" class="shortcodeoptions">
            <?php echo esc_html(__('Security', 'contact-form-7-to-database-extension')); ?>
            <div>
                <div class="label_box">
                    <label for="role_cntl"><?php echo esc_html(__('role', 'contact-form-7-to-database-extension')); ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=89#role"><img alt="?"
                                                                                         src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <select id="role_cntl" name="role_cntl">
                    <option value=""></option>
                    <option value="Administrator"><?php echo esc_html(__('Administrator', 'contact-form-7-to-database-extension')); ?></option>
                    <option value="Editor"><?php echo esc_html(__('Editor', 'contact-form-7-to-database-extension')); ?></option>
                    <option value="Author"><?php echo esc_html(__('Author', 'contact-form-7-to-database-extension')); ?></option>
                    <option value="Contributor"><?php echo esc_html(__('Contributor', 'contact-form-7-to-database-extension')); ?></option>
                    <option value="Subscriber"><?php echo esc_html(__('Subscriber', 'contact-form-7-to-database-extension')); ?></option>
                    <option value="Anyone"><?php echo esc_html(__('Anyone', 'contact-form-7-to-database-extension')); ?></option>
                </select>
                <div class="label_box">
                    <label for="permissionmsg_cntl"><?php echo esc_html(__('permissionmsg', 'contact-form-7-to-database-extension')); ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?page_id=89#permissionmsg"><img alt="?"
                                                                                                  src="<?php echo $this->infoImg ?>"/></a>
                </div>
                <select id="permissionmsg_cntl" name="permissionmsg_cntl">
                    <option value=""></option>
                    <option value="true"><?php echo esc_html(__('true', 'contact-form-7-to-database-extension')); ?></option>
                    <option value="false"><?php echo esc_html(__('false', 'contact-form-7-to-database-extension')); ?></option>
                </select>
                <div>
                    <a target="_docs" href="<?php
                    echo $this->plugin->getAdminUrlPrefix('admin.php') . 'page=' . $this->plugin->getSettingsSlug() . '#security';
                    ?>"><?php echo esc_html(__('Global Security Settings', 'contact-form-7-to-database-extension')) ?></a>
                </div>
            </div>
        </div>
        <div id="performance_div" class="shortcodeoptions">
            <?php echo esc_html(__('Performance', 'contact-form-7-to-database-extension')); ?>
            <div>
                <div class="label_box">
                    <label for="unbuffered_cntl"><?php echo esc_html(__('unbuffered', 'contact-form-7-to-database-extension')); ?></label>
                    <a target="_docs" href="https://cfdbplugin.com/?p=696"><img alt="?"
                                                                               src="<?php echo $this->infoImg ?>"/></a>
                    <input id="unbuffered_cntl"
                           type="checkbox"/>
                </div>
            </div>
        </div>
        <?php
    }

    public function outputJavascript() {
        ?>
        <script type="text/javascript" language="JavaScript">

            var shortCodeDocUrls = {
                '': 'https://cfdbplugin.com/?page_id=89',
                '[cfdb-html]': 'https://cfdbplugin.com/?page_id=284',
                '[cfdb-table]': 'https://cfdbplugin.com/?page_id=93',
                '[cfdb-datatable]': 'https://cfdbplugin.com/?page_id=91',
                '[cfdb-value]': 'https://cfdbplugin.com/?page_id=98',
                '[cfdb-count]': 'https://cfdbplugin.com/?page_id=278',
                '[cfdb-json]': 'https://cfdbplugin.com/?page_id=96',
                '[cfdb-export-link]': 'https://cfdbplugin.com/?page_id=419'
            };

            function showHideOptionDivs() {
                var shortcode = jQuery('#shortcode_ctrl').val();
                jQuery('#doc_url_tag').attr('href', shortCodeDocUrls[shortcode]);
                jQuery('#doc_url_tag').html(shortcode + " <?php echo esc_js(__('Documentation', 'contact-form-7-to-database-extension')); ?>");
                switch (shortcode) {
                    case "[cfdb-html]":
                        jQuery('#show_hide_div').show();
                        jQuery('#limitorder_div').show();
                        jQuery('#html_format_div').hide();
                        jQuery('#dt_options_div').hide();
                        jQuery('#editcolumns_div').hide();
                        jQuery('#json_div').hide();
                        jQuery('#value_div').hide();
                        jQuery('#template_div').show();
                        jQuery('#url_link_div').hide();
                        jQuery('#headers_div').hide();
                        break;
                    case "[cfdb-table]":
                        jQuery('#show_hide_div').show();
                        jQuery('#limitorder_div').show();
                        jQuery('#html_format_div').show();
                        jQuery('#dt_options_div').hide();
                        jQuery('#editcolumns_div').hide();
                        jQuery('#json_div').hide();
                        jQuery('#value_div').hide();
                        jQuery('#template_div').hide();
                        jQuery('#url_link_div').hide();
                        jQuery('#headers_div').show();
                        break;
                    case "[cfdb-datatable]":
                        jQuery('#show_hide_div').show();
                        jQuery('#limitorder_div').show();
                        jQuery('#html_format_div').show();
                    <?php
                    if (!$this->plugin->isEditorActive()) { ?>
                        jQuery('#edit_mode_cntl').attr('disabled', 'disabled'); <?php
                    }
                    ?>
                        jQuery('#dt_options_div').show();
                        jQuery('#editcolumns_div').show();
                        jQuery('#json_div').hide();
                        jQuery('#value_div').hide();
                        jQuery('#template_div').hide();
                        jQuery('#url_link_div').hide();
                        jQuery('#headers_div').show();
                        break;
                    case "[cfdb-value]":
                        jQuery('#show_hide_div').show();
                        jQuery('#limitorder_div').show();
                        jQuery('#html_format_div').hide();
                        jQuery('#dt_options_div').hide();
                        jQuery('#editcolumns_div').hide();
                        jQuery('#json_div').hide();
                        jQuery('#value_div').show();
                        jQuery('#template_div').hide();
                        jQuery('#url_link_div').hide();
                        jQuery('#headers_div').hide();
                        break;
                    case "[cfdb-count]":
                        jQuery('#show_hide_div').hide();
                        jQuery('#limitorder_div').hide();
                        jQuery('#html_format_div').hide();
                        jQuery('#dt_options_div').hide();
                        jQuery('#editcolumns_div').hide();
                        jQuery('#json_div').hide();
                        jQuery('#value_div').hide();
                        jQuery('#template_div').hide();
                        jQuery('#url_link_div').hide();
                        jQuery('#headers_div').hide();
                        break;
                    case "[cfdb-json]":
                        jQuery('#show_hide_div').show();
                        jQuery('#limitorder_div').show();
                        jQuery('#html_format_div').hide();
                        jQuery('#dt_options_div').hide();
                        jQuery('#editcolumns_div').hide();
                        jQuery('#json_div').show();
                        jQuery('#value_div').hide();
                        jQuery('#template_div').hide();
                        jQuery('#url_link_div').hide();
                        jQuery('#headers_div').show();
                        break;
                    case "[cfdb-export-link]":
                        jQuery('#show_hide_div').show();
                        jQuery('#limitorder_div').show();
                        jQuery('#html_format_div').hide();
                        jQuery('#dt_options_div').hide();
                        jQuery('#editcolumns_div').hide();
                        jQuery('#json_div').hide();
                        jQuery('#value_div').hide();
                        jQuery('#template_div').hide();
                        jQuery('#url_link_div').show();
                        jQuery('#headers_div').show();
                        break;
                    default:
                        jQuery('#show_hide_div').show();
                        jQuery('#limitorder_div').show();
                        jQuery('#html_format_div').hide();
                        jQuery('#dt_options_div').hide();
                        jQuery('#editcolumns_div').hide();
                        jQuery('#json_div').hide();
                        jQuery('#value_div').hide();
                        jQuery('#template_div').hide();
                        jQuery('#url_link_div').hide();
                        jQuery('#headers_div').hide();
                        break;
                }
                var exportSelected = jQuery('#export_cntl').val();
                jQuery('#label_export_link').show();
                jQuery('#label_gld_function').hide();
                jQuery('#userpass_span_msg').show();
                jQuery('#gld_userpass_span_msg').hide();
                if (exportSelected) {
                    if (exportSelected == 'RSS') {
                        jQuery('#itemtitle_span').show();
                        jQuery('#csvdelim_span').hide();
                    }
                    else {
                        jQuery('#itemtitle_span').hide();
                        jQuery('#headers_div').show();
                        if (exportSelected == "GLD") {
                            jQuery('#userpass_span_msg').hide();
                            jQuery('#gld_userpass_span_msg').show();
                            jQuery('#label_export_link').hide();
                            jQuery('#label_gld_function').show();
                        }

                        if (exportSelected == "JSON") {
                            jQuery('#json_div').show();
                        }
                        else {
                            jQuery('#json_div').hide();
                        }

                        if (['CSVUTF8BOM', 'CSVUTF8', 'CSVSJIS'].indexOf(exportSelected) > -1) {
                            jQuery('#csvdelim_span').show();
                        }
                        else {
                            jQuery('#csvdelim_span').hide();
                        }
                    }
                } else {
                    jQuery('#itemtitle_span').hide();
                    jQuery('#csvdelim_span').hide();
                    jQuery('#userpass_span_msg').show();
                    jQuery('#gld_userpass_span_msg').hide();
                    jQuery('#label_gld_script').hide();
                }
            }

            function getValue(attr, value, errors) {
                if (value) {
                    if (errors && value.indexOf('"') > -1) {
                        errors.push('<?php echo esc_js(__('Error: "', 'contact-form-7-to-database-extension')); ?>'
                                + attr +
                                '<?php echo esc_js(__('" should not contain double-quotes (")', 'contact-form-7-to-database-extension')); ?>');
                        value = value.replace('"', "'");
                    }
                    return attr + '="' + value + '"';
                }
                return '';
            }

            function pushNameValue(attr, value, array, errors) {
                if (value) {
                    if (errors && value.indexOf('"') > -1) {
                        errors.push('<?php echo esc_js(__('Error: "', 'contact-form-7-to-database-extension')); ?>'
                                + attr +
                                '<?php echo esc_js(__('" should not contain double-quotes (")', 'contact-form-7-to-database-extension')); ?>');
                        value = value.replace('"', "'");
                    }
                    array.push(attr);
                    array.push(value);
                    return true;
                }
                return false;
            }

            function getValueUrl(attr, value) {
                if (value) {
                    return attr + '=' + encodeURIComponent(value)
                }
                return '';
            }


            function join(arr, delim) {
                if (delim == null) {
                    delim = ' ';
                }
                var tmp = [];
                for (idx = 0; idx < arr.length; idx++) {
                    if (arr[idx] != '') {
                        tmp.push(arr[idx]);
                    }
                }
                return tmp.join(delim);
            }

            function chopLastChar(text) {
                return text ? text.substr(0, text.length - 1) : text;
            }

            function escapeHTML(string) {
                var pre = document.createElement('pre');
                var text = document.createTextNode(string);
                pre.appendChild(text);
                return pre.innerHTML;
            }


            function createShortCodeAndExportLink() {
                var scElements = [];
                var scUrlElements = [];
                var scValidationErrors = [];

                var exportUrlElements = [];
                var exportValidationErrors = [];

                var googleScriptElements = [];
                var googleScriptValidationErrors = [];

                var shortcode = jQuery('#shortcode_ctrl').val();
                if (shortcode == '') {
                    jQuery('#shortcode_result_text').html('');
                }
                scElements.push(chopLastChar(shortcode));

                var pushErrorMessagesToAll = function (errMsg) {
                    scValidationErrors.push(errMsg);
                    exportValidationErrors.push(errMsg);
                    googleScriptValidationErrors.push(errMsg);
                };

                var formName = jQuery('#form_name_cntl').val();
                var errMsg;
                if (!formName || Array.isArray(formName) && !formName.length) {
                    errMsg = '<?php echo esc_js(__('Error: no form is chosen', 'contact-form-7-to-database-extension')) ?>';
                    jQuery('#shortcode_validations_text').html(errMsg);
                    pushErrorMessagesToAll(errMsg);
                }
                else {
                    jQuery('#shortcode_validations_text').html('');
                    scElements.push('form="' + formName + '"');
                    scUrlElements.push('form=' + encodeURIComponent(formName));
                    exportUrlElements.push('form=' + encodeURIComponent(formName));
                    googleScriptElements.push('<?php echo $this->siteUrl ?>');
                    googleScriptElements.push(formName);
                    googleScriptElements.push('<?php echo is_user_logged_in() ?
                            wp_get_current_user()->user_login :
                            'user' ?>');
                    googleScriptElements.push('&lt;password&gt;');
                }

                var pushValueToAll = function (name, val) {
                    scElements.push(getValue(name, val, scValidationErrors));
                    scUrlElements.push(getValueUrl(name, val));
                    exportUrlElements.push(getValueUrl(name, val));
                    pushNameValue(name, val, googleScriptElements, googleScriptValidationErrors);
                };

                var val;
                if (shortcode != '[cfdb-count]') {
                    val = jQuery('#show_cntl').val();
                    pushValueToAll('show', val);

                    val = jQuery('#hide_cntl').val();
                    pushValueToAll('hide', val);
                }

                val = jQuery('#role_cntl').val();
                pushValueToAll('role', val);

                val = jQuery('#permissionmsg_cntl').val();
                pushValueToAll('permissionmsg', val);

                val = jQuery('#trans_cntl').val();
                pushValueToAll('trans', val);


                var handleFilterSearch = function (filterName, filter, searchName, search) {
                    if (filter) {
                        pushValueToAll(filterName, filter);
                        if (search) {
                            var errMsg = '<?php echo esc_js(__('Warning: "search" field ignored because FIELD is used (use one but not both)', 'contact-form-7-to-database-extension')); ?>'.replace('FIELD', filterName);
                            pushErrorMessagesToAll(errMsg);
                        }
                    }
                    else {
                        pushValueToAll(searchName, search);
                    }
                };
                var filter = jQuery('#filter_cntl').val();
                var search = jQuery('#search_cntl').val();
                handleFilterSearch('filter', filter, 'search', search);

                var tfilter = jQuery('#tfilter_cntl').val();
                var tsearch = jQuery('#tsearch_cntl').val();
                handleFilterSearch('tfilter', tfilter, 'tsearch', tsearch);


                if (shortcode != '[cfdb-count]') {

                    var handleLimit = function (limitName, limitRows, limitStart) {
                        if (limitStart && !limitRows) {
                            errMsg = '<?php echo esc_js(__('Error: "FIELD": if you provide a value for "Start Row" then you must also provide a value for "Num Rows"', 'contact-form-7-to-database-extension')); ?>'.replace('FIELD', limitName);
                            pushErrorMessagesToAll(errMsg);
                        }
                        if (limitRows) {
                            if (!/^\d+$/.test(limitRows)) {
                                errMsg = '<?php echo esc_js(__('Error: "FIELD": "Num Rows" must be a positive integer', 'contact-form-7-to-database-extension')); ?>'.replace('FIELD', limitName);
                                pushErrorMessagesToAll(errMsg);
                            }
                            else {
                                var limitOption = '';
                                var limitOptionUrl = limitName + '=';
                                if (limitStart) {
                                    if (!/^\d+$/.test(limitStart)) {
                                        errMsg = '<?php echo esc_js(__('Error: "FIELD": "Start Row" must be a positive integer', 'contact-form-7-to-database-extension')); ?>'.replace('FIELD', limitName);
                                        pushErrorMessagesToAll(errMsg);
                                    }
                                    else {
                                        limitOption += limitStart + ",";
                                        limitOptionUrl += encodeURIComponent(limitStart + ",");
                                    }
                                }
                                limitOption += limitRows;
                                limitOptionUrl += limitRows;
                                scElements.push(limitName + '="' + limitOption + '"');
                                scUrlElements.push(limitOptionUrl);
                                exportUrlElements.push(limitOptionUrl);
                                pushNameValue(limitName, limitOption, googleScriptElements, googleScriptValidationErrors);
                            }
                        }
                    };

                    var limitRows = jQuery('#limit_rows_cntl').val();
                    var limitStart = jQuery('#limit_start_cntl').val();
                    handleLimit('limit', limitRows, limitStart);

                    var tlimitRows = jQuery('#tlimit_rows_cntl').val();
                    var tlimitStart = jQuery('#tlimit_start_cntl').val();
                    handleLimit('tlimit', tlimitRows, tlimitStart);


                    val = jQuery('#random_cntl').val();
                    scElements.push(getValue('random', val, scValidationErrors));
                    scUrlElements.push(getValueUrl('random', val));
                    pushNameValue("random", val, googleScriptElements, googleScriptValidationErrors);

                    if (jQuery('#unbuffered_cntl').is(':checked')) {
                        scElements.push('unbuffered="true"');
                        scUrlElements.push(getValueUrl('unbuffered', 'true'));
                        exportUrlElements.push('unbuffered=true');
                        pushNameValue("unbuffered", "true", googleScriptElements, googleScriptValidationErrors);
                    }

                    var handleOrderBy = function (name, val) {
                        if (val) {
                            var orderByElem = getValue(name, val, scValidationErrors);
                            var orderByElemUrl = getValueUrl(name, val);
                            var orderByDir = jQuery('#' + name + 'dir_cntl').val();
                            if (orderByDir) {
                                orderBy += ' ' + orderByDir;
                                orderByElem = chopLastChar(orderByElem) + ' ' + orderByDir + '"';
                                orderByElemUrl = orderByElemUrl + encodeURIComponent(' ' + orderByDir);
                            }
                            scElements.push(orderByElem);
                            scUrlElements.push(orderByElemUrl);
                            exportUrlElements.push(orderByElemUrl);
                            pushNameValue(name, orderBy, googleScriptElements, googleScriptValidationErrors);
                        }
                    };
                    var orderBy = jQuery('#orderby_cntl').val();
                    handleOrderBy('orderby', orderBy);

                    var torderBy = jQuery('#torderby_cntl').val();
                    handleOrderBy('torderby', torderBy);
                }

                var scText;
                switch (shortcode) {
                    case '[cfdb-html]':
                        val = jQuery('#filelinks_cntl').val();
                        scElements.push(getValue('filelinks', val, scValidationErrors));
                        scUrlElements.push(getValueUrl('filelinks', val));

                        val = jQuery('#wpautop_cntl').val();
                        scElements.push(getValue('wpautop', val, scValidationErrors));
                        scUrlElements.push(getValueUrl('wpautop', val));

                        val = jQuery('#stripbr_cntl').val();
                        scElements.push(getValue('stripbr', val, scValidationErrors));
                        scUrlElements.push(getValueUrl('stripbr', val));

                        var template = jQuery('#content_cntl').val();
                        var content = template;
                        var contentBefore = jQuery('#before_cntl').val();
                        var contentAfter = jQuery('#after_cntl').val();
                        if (contentBefore) {
                            content = "<?php echo CFDBShortCodeContentParser::BEFORE_START_DELIMITER ?>" + contentBefore + "<?php echo CFDBShortCodeContentParser::BEFORE_END_DELIMITER ?>" + content;
                        }
                        if (contentAfter) {
                            content += "<?php echo CFDBShortCodeContentParser::AFTER_START_DELIMITER ?>" + contentAfter + "<?php echo CFDBShortCodeContentParser::AFTER_END_DELIMITER ?>";
                        }
                        scUrlElements.push('content=' + encodeURIComponent(content));
                        scUrlElements.push('enc=HTMLTemplate');
                        scText = join(scElements) + ']' +
                                // Escape html tags for display on page
                                content.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;') +
                                '[/cfdb-html]';
                        if (template == "") {
                            scValidationErrors.push('<?php
                                    echo esc_js(__('Error: [cfdb-html] has empty Template. It will not output anything. ', 'contact-form-7-to-database-extension'));
                                    echo esc_js(__('(Shortcode Specific option)', 'contact-form-7-to-database-extension'));?>');
                            jQuery('#content_cntl').addClass('validation'); // highlight template area
                        }
                        else {
                            jQuery('#content_cntl').removeClass('validation'); // remove highlight template area
                        }
                        break;
                    case '[cfdb-table]':
                        if (!jQuery('#header_cntl').is(':checked')) {
                            scElements.push('header="false"');
                            scUrlElements.push(getValueUrl('header', 'false'));
                            pushNameValue("header", "false", googleScriptElements, googleScriptValidationErrors);
                        }
                        val = jQuery('#headers_cntl').val();
                        scElements.push(getValue('headers', val, scValidationErrors));
                        scUrlElements.push(getValueUrl('headers', val));

                        val = jQuery('#id_cntl').val();
                        scElements.push(getValue('id', val, scValidationErrors));
                        scUrlElements.push(getValueUrl('id', val));

                        val = jQuery('#class_cntl').val();
                        scElements.push(getValue('class', val, scValidationErrors));
                        scUrlElements.push(getValueUrl('class', val));

                        val = jQuery('#style_cntl').val();
                        scElements.push(getValue('style', val, scValidationErrors));
                        scUrlElements.push(getValueUrl('style', val));

                        var contentBefore = jQuery('#before_cntl').val();
                        var contentAfter = jQuery('#after_cntl').val();
                        var content = '';
                        if (contentBefore) {
                            content = "<?php echo CFDBShortCodeContentParser::BEFORE_START_DELIMITER ?>" + contentBefore + "<?php echo CFDBShortCodeContentParser::BEFORE_END_DELIMITER ?>" + content;
                        }
                        if (contentAfter) {
                            content += "<?php echo CFDBShortCodeContentParser::AFTER_START_DELIMITER ?>" + contentAfter + "<?php echo CFDBShortCodeContentParser::AFTER_END_DELIMITER ?>";
                        }
                        scUrlElements.push('content=' + encodeURIComponent(content));

                        scUrlElements.push('enc=HTML');
                        scText = join(scElements) + ']';
                        if (content) {
                            // Escape html tags for display on page
                            scText += content.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;') +
                                    '[/cfdb-table]';
                        }
                        break;
                    case '[cfdb-datatable]':
                        if (!jQuery('#header_cntl').is(':checked')) {
                            scElements.push('header="false"');
                            scUrlElements.push(getValueUrl('header', 'false'));
                        }
                        val = jQuery('#headers_cntl').val();
                        scElements.push(getValue('headers', val, scValidationErrors));
                        scUrlElements.push(getValueUrl('headers', val));
                        var hadHeaders = val != '';

                        val = jQuery('#id_cntl').val();
                        scElements.push(getValue('id', val, scValidationErrors));
                        scUrlElements.push(getValueUrl('id', val));

                        val = jQuery('#class_cntl').val();
                        scElements.push(getValue('class', val, scValidationErrors));
                        scUrlElements.push(getValueUrl('class', val));

                        val = jQuery('#style_cntl').val();
                        scElements.push(getValue('style', val, scValidationErrors));
                        scUrlElements.push(getValueUrl('style', val));

                        val = jQuery('#edit_mode_cntl').val();
                        scElements.push(getValue('edit', val, scValidationErrors));
                        scUrlElements.push(getValueUrl('edit', val));
                        if (hadHeaders && val == 'true') {
                            scValidationErrors.push('<?php echo esc_js(__('Error: "edit=true" will not work properly when setting "headers" ', 'contact-form-7-to-database-extension')); ?>');
                        }

                        val = jQuery('#dt_options_cntl').val();
                        scElements.push(getValue('dt_options', val, scValidationErrors));
                        scUrlElements.push(getValueUrl('dt_options', val));

                        val = jQuery('#editcolumns_cntl').val();
                        scElements.push(getValue('editcolumns', val, scValidationErrors));
                        scUrlElements.push(getValueUrl('editcolumns', val));

                        var contentBefore = jQuery('#before_cntl').val();
                        var contentAfter = jQuery('#after_cntl').val();
                        var content = '';
                        if (contentBefore) {
                            content = "<?php echo CFDBShortCodeContentParser::BEFORE_START_DELIMITER ?>" + contentBefore + "<?php echo CFDBShortCodeContentParser::BEFORE_END_DELIMITER ?>" + content;
                        }
                        if (contentAfter) {
                            content += "<?php echo CFDBShortCodeContentParser::AFTER_START_DELIMITER ?>" + contentAfter + "<?php echo CFDBShortCodeContentParser::AFTER_END_DELIMITER ?>";
                        }
                        scUrlElements.push('content=' + encodeURIComponent(content));

                        scUrlElements.push('enc=DT');
                        scText = join(scElements) + ']';
                        if (content) {
                            // Escape html tags for display on page
                            scText += content.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;') +
                                    '[/cfdb-datatable]';
                        }
                        break;
                    case '[cfdb-value]':
                        val = jQuery('#function_cntl').val();
                        scElements.push(getValue('function', val, scValidationErrors));
                        scUrlElements.push(getValueUrl('function', val));

                        val = jQuery('#delimiter_cntl').val();
                        scElements.push(getValue('delimiter', val, scValidationErrors));
                        scUrlElements.push(getValueUrl('delimiter', val));

                        var contentBefore = jQuery('#before_cntl').val();
                        var contentAfter = jQuery('#after_cntl').val();
                        var content = '';
                        if (contentBefore) {
                            content = "<?php echo CFDBShortCodeContentParser::BEFORE_START_DELIMITER ?>" + contentBefore + "<?php echo CFDBShortCodeContentParser::BEFORE_END_DELIMITER ?>" + content;
                        }
                        if (contentAfter) {
                            content += "<?php echo CFDBShortCodeContentParser::AFTER_START_DELIMITER ?>" + contentAfter + "<?php echo CFDBShortCodeContentParser::AFTER_END_DELIMITER ?>";
                        }
                        scUrlElements.push('content=' + encodeURIComponent(content));

                        scUrlElements.push('enc=VALUE');
                        scText = join(scElements) + ']';
                        if (content) {
                            // Escape html tags for display on page
                            scText += content.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;') +
                                    '[/cfdb-value]';
                        }
                        break;
                    case '[cfdb-count]':
                        var contentBefore = jQuery('#before_cntl').val();
                        var contentAfter = jQuery('#after_cntl').val();
                        var content = '';
                        if (contentBefore) {
                            content = "<?php echo CFDBShortCodeContentParser::BEFORE_START_DELIMITER ?>" + contentBefore + "<?php echo CFDBShortCodeContentParser::BEFORE_END_DELIMITER ?>" + content;
                        }
                        if (contentAfter) {
                            content += "<?php echo CFDBShortCodeContentParser::AFTER_START_DELIMITER ?>" + contentAfter + "<?php echo CFDBShortCodeContentParser::AFTER_END_DELIMITER ?>";
                        }
                        scUrlElements.push('content=' + encodeURIComponent(content));

                        scUrlElements.push('enc=COUNT');
                        scText = join(scElements) + ']'; // hopLastChar(scElements.join(' ')) + ']';
                        if (content) {
                            // Escape html tags for display on page
                            scText += content.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;') +
                                    '[/cfdb-count]';
                        }
                        break;
                    case '[cfdb-json]':
                        if (!jQuery('#header_cntl').is(':checked')) {
                            scElements.push('header="false"');
                            scUrlElements.push(getValueUrl('header', 'false'));
                            pushNameValue("header", "false", googleScriptElements, googleScriptValidationErrors);
                        }
                        val = jQuery('#headers_cntl').val();
                        scElements.push(getValue('headers', val, scValidationErrors));
                        scUrlElements.push(getValueUrl('headers', val));

                        val = jQuery('#var_cntl').val();
                        scElements.push(getValue('var', val, scValidationErrors));
                        scUrlElements.push(getValueUrl('var', val));

                        val = jQuery('#format_cntl').val();
                        scElements.push(getValue('format', val, scValidationErrors));
                        scUrlElements.push(getValueUrl('format', val));

                        var contentBefore = jQuery('#before_cntl').val();
                        var contentAfter = jQuery('#after_cntl').val();
                        var content = '';
                        if (contentBefore) {
                            content = "<?php echo CFDBShortCodeContentParser::BEFORE_START_DELIMITER ?>" + contentBefore + "<?php echo CFDBShortCodeContentParser::BEFORE_END_DELIMITER ?>" + content;
                        }
                        if (contentAfter) {
                            content += "<?php echo CFDBShortCodeContentParser::AFTER_START_DELIMITER ?>" + contentAfter + "<?php echo CFDBShortCodeContentParser::AFTER_END_DELIMITER ?>";
                        }
                        scUrlElements.push('content=' + encodeURIComponent(content));

                        scUrlElements.push('enc=JSON');
                        scText = join(scElements) + ']';
                        if (content) {
                            // Escape html tags for display on page
                            scText += content.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;') +
                                    '[/cfdb-json]';
                        }
                        break;
                    case '[cfdb-export-link]':
                        var enc = jQuery('#enc_cntl').val();
                        scElements.push(getValue('enc', enc, scValidationErrors));
                        scUrlElements.push(getValueUrl('enc', enc));

                        if (['CSVUTF8BOM', 'CSVUTF8', 'CSVSJIS'].indexOf(enc) > -1) {
                            var delim = jQuery('#export_link_csv_delim').val();
                            if (delim) {
                                scElements.push(getValue('delimiter', delim, scValidationErrors));
                                scUrlElements.push(getValueUrl('delimiter', delim));
                            }
                        }

                        scElements.push(getValue('urlonly', jQuery('#urlonly_cntl').val(), scValidationErrors));
                        scElements.push(getValue('linktext', jQuery('#linktext_cntl').val(), scValidationErrors));

                        if (!jQuery('#header_cntl').is(':checked')) {
                            scElements.push('header="false"');
                            scUrlElements.push(getValueUrl('header', 'false'));
                        }
                        val = jQuery('#headers_cntl').val();
                        scElements.push(getValue('headers', val, scValidationErrors));
                        scUrlElements.push(getValueUrl('headers', val));

                        scText = join(scElements) + ']';
                        break;
                    default:
                        scText = shortcode;
                        break;
                }

                var urlBase = '<?php echo $this->plugin->getAdminUrlPrefix('admin-ajax.php') ?>action=cfdb-export&';

                if (shortcode) {
                    // Output short code text
                    var scUrl = urlBase + join(scUrlElements, '&');
                    jQuery('#shortcode_result_text').html('<a target="_cfdb_sc_results" href="' + scUrl + '">' + escapeHTML(scText) + '</a>');

                    // Output short code errors
                    jQuery('#shortcode_validations_text').html(scValidationErrors.join('<br/>'));
                }
                else {
                    // Don't report errors
                    jQuery('#shortcode_validations_text').html('');
                }

                // Export link or Google Spreadsheet function call
                var exportSelection = jQuery('#export_cntl').val();
                if (exportSelection) {
                    if (exportSelection != 'GLD') {
                        exportUrlElements.push(getValueUrl('enc', exportSelection));
                    }
                    if (exportSelection == 'RSS') {
                        exportUrlElements.push(getValueUrl('itemtitle', jQuery('#add_itemtitle').val()));
                    } else {
                        if (!jQuery('#header_cntl').is(':checked')) {
                            exportUrlElements.push(getValueUrl('header', 'false'));
                            pushNameValue("header", "false", googleScriptElements, googleScriptValidationErrors);
                        }
                        val = jQuery('#headers_cntl').val();
                        exportUrlElements.push(getValueUrl('headers', val, scValidationErrors));
                        pushNameValue("headers", val, googleScriptElements, googleScriptValidationErrors);

                        exportUrlElements.push(getValueUrl('format', jQuery('#format_cntl').val(), scValidationErrors));
                    }

                    if (['CSVUTF8BOM', 'CSVUTF8', 'CSVSJIS'].indexOf(exportSelection) > -1) {
                        delim = jQuery('#csv_delim').val();
                        if (delim != '') {
                            exportUrlElements.push(getValueUrl("delimiter", delim));
                        }
                    }

                    var user = jQuery("#gld_user").val();
                    var pass = jQuery("#gld_pass").val();
                    var obfuscate = jQuery('#obfuscate_cntl').is(':checked')
                    if (user || pass) {
                        if (obfuscate) {
                            var key = 'kx82XcPjq8q8S!xafx%$&7p6';
                            exportUrlElements.push("l=" + encodeURI(printHex(des(key, user + "/" + pass, 1))));
                        } else {
                            exportUrlElements.push("username=" + encodeURI(user));
                            exportUrlElements.push("password=" + encodeURI(pass));
                        }
                        urlBase = '<?php echo $this->plugin->getAdminUrlPrefix('admin-ajax.php') ?>action=cfdb-login&cfdb-action=cfdb-export&';
                        exportValidationErrors.push("<?php echo esc_js(__('Warning: the function includes your WP login information. Avoid sharing it.', 'contact-form-7-to-database-extension')) ?>");
                    }

                    // Output
                    var exportUrl = urlBase + join(exportUrlElements, '&');
                    if (exportSelection == 'GLD') {
                        if (!user || !pass) {
                            exportValidationErrors.push("<?php echo esc_js(__('Error: WP User and Password are required for the Google Spreadsheet to pull data from your WordPress site.', 'contact-form-7-to-database-extension')) ?>");
                        }
                        if (exportUrl.length > 255) {
                            exportValidationErrors.push("<?php echo esc_js(__('Because the generated URL would be too long, you must use this alternative function and add its script to your Google Spreadsheet', 'contact-form-7-to-database-extension')) ?>");
                            jQuery('#label_gld_script').show();
                            jQuery('#label_gld_function').hide();
                            jQuery('#export_result_text').html(formName ?
                                    ("=cfdbdata(\"" + googleScriptElements.join("\", \"") + "\")") :
                                    "");
                        } else {
                            jQuery('#export_result_text').html(formName ?
                                    ("<a target='_cfdb_exp_results' href='" + exportUrl + "'>=IMPORTDATA(\"" + exportUrl + "\")</a>") :
                                    "");
                        }
                        jQuery("#gld_instructions").attr('href', '<?php echo admin_url('admin-ajax.php?action=cfdb-export&enc=GLD&form=') ?>' + encodeURIComponent(formName));
                    } else {
                        jQuery('#export_result_text').html(formName ? ('<a target="_cfdb_exp_results" href="' + exportUrl + '">' + exportUrl + '</a>') : '');
                    }

                    // Output export errors
                    jQuery('#export_validations_text').html(exportValidationErrors.join('<br/>'));

                } else {
                    jQuery('#export_result_text').html('');
                    // Don't report errors
                    jQuery('#export_validations_text').html('');
                }
            }

            var getFormFieldsUrlBase = '<?php echo $this->plugin->getFormFieldsAjaxUrlBase() ?>';
            function getFormFields() {
                jQuery('[id^=add]').attr('disabled', 'disabled');
                jQuery('[id^=btn]').attr('disabled', 'disabled');
                var formName = jQuery('#form_name_cntl').val();
                var url = getFormFieldsUrlBase + encodeURIComponent(formName);
                jQuery.ajax({
                    dataType: "json",
                    url: url,
                    async: false,
                    success: function (json) {
                        var optionsHtml = '<option value=""></option>';
                        jQuery(json).each(function () {
                            var value = htmlEntities(this);
                            optionsHtml += '<option value="' + value + '">' + value + '</option>';
                        });
                        optionsHtml += '<option value="$_POST(param)">$_POST(param)</option>';
                        optionsHtml += '<option value="$_GET(param)">$_GET(param)</option>';
                        optionsHtml += '<option value="$_COOKIE(param)">$_COOKIE(param)</option>';
                        jQuery('[id^=add]').html(optionsHtml).removeAttr('disabled');
                        jQuery('[id^=btn]').removeAttr('disabled');
                    }
                });
            }

            function validateSubmitTime() {
                var url = "<?php echo $this->plugin->getValidateSubmitTimeAjaxUrlBase() ?>" + jQuery('#filter_val').val();
                jQuery.get(url, function (data) {
                    alert(data);
                });
            }

            function showValidateSubmitTimeHelp(show) {
                if (show) {
                    jQuery('#span_validate_submit_time').show();
                }
                else {
                    jQuery('#span_validate_submit_time').hide();
                }
            }

            function addFieldToShow() {
                var value = jQuery('#show_cntl').val();
                if (value) {
                    value += ',';
                }
                jQuery('#show_cntl').val(value + jQuery('#add_show').val());
                createShortCodeAndExportLink();
            }

            function addFieldToHide() {
                var value = jQuery('#hide_cntl').val();
                if (value) {
                    value += ',';
                }
                jQuery('#hide_cntl').val(value + jQuery('#add_hide').val());
                createShortCodeAndExportLink();
            }

            function addFieldToOrderBy(field) {
                var value = jQuery('#' + field + '_cntl').val();
                if (value) {
                    value += ',';
                }
                jQuery('#' + field + '_cntl').val(value + jQuery('#add_' + field).val());
                createShortCodeAndExportLink();
            }

            function addFieldToFilter(field) {
                var value = jQuery('#' + field + '_cntl').val();
                if (value) {
                    value += jQuery('#' + field + '_bool').val();
                }
                value += jQuery('#add_' + field).val() + jQuery('#' + field + '_op').val() + jQuery('#' + field + '_val').val();
                jQuery('#' + field + '_cntl').val(value);
                createShortCodeAndExportLink();
            }

            function addToTrans() {
                var value = jQuery('#trans_cntl').val();
                if (value) {
                    value += "&&";
                }
                var field = jQuery('#add_trans').val();
                if (field) {
                    value += field;
                    value += "="
                }
                value += jQuery('#trans_val').val();
                jQuery('#trans_cntl').val(value);
                createShortCodeAndExportLink();
            }

            function addFieldToHeaders() {
                var col = jQuery('#add_headers').val();
                var disp = jQuery('#headers_val').val();
                if (!col || !disp) {
                    return;
                }
                var value = jQuery('#headers_cntl').val();
                if (value) {
                    value += ',';
                }
                value += col + '=' + disp;
                jQuery('#headers_cntl').val(value);
                createShortCodeAndExportLink();
            }

            function addFieldToContent() {
                jQuery('#content_cntl').val(jQuery('#content_cntl').val() + '${' + jQuery('#add_content').val() + '}');
            }

            function reset() {
                // Form
                jQuery('#form_name_cntl').val('<?php echo esc_js($_REQUEST['form'] ? $_REQUEST['form'] : '')?>'.split(','));
                getFormFields();

                // Export File
                jQuery('#export_cntl').val('<?php echo esc_js($_REQUEST['enc'] ? $_REQUEST['enc'] : '')?>'); // same input used for #enc_cntl
                jQuery('#add_itemtitle').val('<?php echo esc_js($_REQUEST['itemtitle'] ? $_REQUEST['itemtitle'] : '')?>');
                jQuery('#csv_delim').val('<?php echo esc_js($_REQUEST['delimiter'] ? $_REQUEST['delimiter'] : '')?>'); // same input used for #delimiter_cntl

                // Short Code
                jQuery('#shortcode_ctrl').val('<?php echo esc_js($_REQUEST['sc'] ? $_REQUEST['sc'] : '')?>');
                jQuery('#show_cntl').val('<?php echo esc_js($_REQUEST['show'] ? $_REQUEST['show'] : '')?>');
                jQuery('#hide_cntl').val('<?php echo esc_js($_REQUEST['hide'] ? $_REQUEST['hide'] : '')?>');
                jQuery('#role_cntl').val('<?php echo esc_js($_REQUEST['role'] ? $_REQUEST['role'] : '')?>');
                jQuery('#permissionmsg_cntl').val('<?php echo esc_js($_REQUEST['permissionmsg'] ? $_REQUEST['permissionmsg'] : '')?>');
                jQuery('#trans_cntl').val('<?php echo esc_js($_REQUEST['trans'] ? $_REQUEST['trans'] : '')?>');
                jQuery('#search_cntl').val('<?php echo esc_js($_REQUEST['search'] ? $_REQUEST['search'] : '')?>');
                jQuery('#filter_cntl').val('<?php echo esc_js($_REQUEST['filter'] ? $_REQUEST['filter'] : '')?>');
                jQuery('#tsearch_cntl').val('<?php echo esc_js($_REQUEST['tsearch'] ? $_REQUEST['tsearch'] : '')?>');
                jQuery('#tfilter_cntl').val('<?php echo esc_js($_REQUEST['tfilter'] ? $_REQUEST['tfilter'] : '')?>');

                <?php
                $limitRows = $limitStart = '';
                $postedLimitComponents = explode(',', $_REQUEST['limit'] ? $_REQUEST['limit'] : '');
                switch (count($postedLimitComponents)) {
                    case 2:
                        $limitStart = $postedLimitComponents[0];
                        $limitRows = $postedLimitComponents[1];
                        break;
                    case 1:
                        $limitRows = $postedLimitComponents[0];
                        break;
                    default:
                        break;
                }
                ?>
                jQuery('#limit_rows_cntl').val('<?php echo esc_js($limitRows)?>');
                jQuery('#limit_start_cntl').val('<?php echo esc_js($limitStart)?>');

                jQuery('#random_cntl').val('<?php echo esc_js($_REQUEST['random'] ? $_REQUEST['random'] : '')?>');

                jQuery('#unbuffered_cntl').attr('checked', <?php echo esc_js($_REQUEST['unbuffered'] ? $_REQUEST['unbuffered'] : 'false')?>);

                jQuery('#orderby_cntl').val('<?php echo esc_js($_REQUEST['orderby'] ? $_REQUEST['orderby'] : '')?>');
                jQuery('#torderby_cntl').val('<?php echo esc_js($_REQUEST['torderby'] ? $_REQUEST['torderby'] : '')?>');

                jQuery('#header_cntl').prop("checked",  <?php echo esc_js($_REQUEST['header'] ? $_REQUEST['header'] : 'true')?>); // default = true

                jQuery('#headers_cntl').val('<?php echo esc_js($_REQUEST['headers'] ? $_REQUEST['headers'] : '')?>');
                jQuery('#id_cntl').val('<?php echo esc_js($_REQUEST['id'] ? $_REQUEST['id'] : '')?>');
                jQuery('#class_cntl').val('<?php echo esc_js($_REQUEST['class'] ? $_REQUEST['class'] : '')?>');
                jQuery('#style_cntl').val('<?php echo esc_js($_REQUEST['style'] ? $_REQUEST['style'] : '')?>');
                jQuery('#edit_mode_cntl').val('<?php echo esc_js($_REQUEST['edit'] ? $_REQUEST['edit'] : '')?>');
                jQuery('#dt_options_cntl').val('<?php echo esc_js($_REQUEST['dt_options'] ? $_REQUEST['dt_options'] : '')?>');
                jQuery('#editcolumns_cntl').val('<?php echo esc_js($_REQUEST['editcolumns'] ? $_REQUEST['editcolumns'] : '')?>');
                jQuery('#var_cntl').val('<?php echo esc_js($_REQUEST['var'] ? $_REQUEST['var'] : '')?>');
                jQuery('#format_cntl').val('<?php echo esc_js($_REQUEST['format'] ? $_REQUEST['format'] : '')?>');
                jQuery('#function_cntl').val('<?php echo esc_js($_REQUEST['function'] ? $_REQUEST['function'] : '')?>');
                jQuery('#delimiter_cntl').val('<?php echo esc_js($_REQUEST['delimiter'] ? $_REQUEST['delimiter'] : '')?>'); // same input used for #cvs_delim
                jQuery('#filelinks_cntl').val('<?php echo esc_js($_REQUEST['filelinks'] ? $_REQUEST['filelinks'] : '')?>');
                jQuery('#wpautop_cntl').val('<?php echo esc_js($_REQUEST['wpautop'] ? $_REQUEST['wpautop'] : '')?>');
                jQuery('#stripbr_cntl').val('<?php echo esc_js($_REQUEST['stripbr'] ? $_REQUEST['stripbr'] : '')?>');
                jQuery('#enc_cntl').val('<?php echo esc_js($_REQUEST['enc'] ? $_REQUEST['enc'] : '')?>'); // same input used for #export_cntl
                jQuery('#urlonly_cntl').val('<?php echo esc_js($_REQUEST['urlonly'] ? $_REQUEST['urlonly'] : '')?>');
                jQuery('#linktext_cntl').val('<?php echo esc_js($_REQUEST['linktext'] ? $_REQUEST['linktext'] : '')?>');

                <?php
                    $content = $before = $after = '';
                if (isset($_REQUEST['content']) && $_REQUEST['content']) {
                    $parser = new CFDBShortCodeContentParser;
                    list($before, $content, $after) = $parser->parseBeforeContentAfter($_REQUEST['content']);
                }
                ?>
                jQuery('#content_cntl').val('<?php echo esc_js($content)?>');
                jQuery('#before_cntl').val('<?php echo esc_js($before)?>');
                jQuery('#after_cntl').val('<?php echo esc_js($after)?>');

                showValidateSubmitTimeHelp(false);
                showHideOptionDivs();
                createShortCodeAndExportLink();
            }

            jQuery.ajaxSetup({
                cache: false
            });

            jQuery(document).ready(function () {
                reset();
                showHideOptionDivs();
                createShortCodeAndExportLink();
                jQuery('#shortcode_ctrl').change(showHideOptionDivs);
                jQuery('#shortcode_ctrl').change(createShortCodeAndExportLink);
                jQuery('select[id$="cntl"]').change(createShortCodeAndExportLink);
                jQuery('input[id$="cntl"]').keyup(createShortCodeAndExportLink);
                jQuery('textarea[id$="cntl"]').keyup(createShortCodeAndExportLink);
                jQuery('#form_name_cntl').change(getFormFields);
                jQuery('#btn_show').click(addFieldToShow);
                jQuery('#btn_hide').click(addFieldToHide);
                jQuery('#btn_orderby').click(function () {
                    addFieldToOrderBy('orderby');
                });
                jQuery('#btn_torderby').click(function () {
                    addFieldToOrderBy('torderby');
                });
                jQuery('#btn_filter').click(function () {
                    addFieldToFilter('filter');
                });
                jQuery('#btn_tfilter').click(function () {
                    addFieldToFilter('tfilter');
                });
                jQuery('#btn_trans').click(addToTrans);
                jQuery('#header_cntl').click(createShortCodeAndExportLink);
                jQuery('#unbuffered_cntl').click(createShortCodeAndExportLink);
                jQuery('#edit_mode_cntl').click(createShortCodeAndExportLink);
                jQuery('#btn_headers').click(addFieldToHeaders);
                jQuery('#btn_content').click(function () {
                    addFieldToContent();
                    createShortCodeAndExportLink();
                });
                jQuery('#btn_editcolumns').click(function () {
                    addFieldToOrderBy('editcolumns');
                });

                var showHideExportLinkDelimiter = function () {
                    var enc = jQuery('#enc_cntl').val();
                    if (['CSVUTF8BOM', 'CSVUTF8', 'CSVSJIS'].indexOf(enc) > -1) {
                        jQuery('#export_link_csvdelim_span').show();
                    }
                    else {
                        jQuery('#export_link_csvdelim_span').hide();
                    }
                };
                jQuery('#enc_cntl').change(function () {
                    showHideExportLinkDelimiter();
                    createShortCodeAndExportLink();
                });
                showHideExportLinkDelimiter();
                jQuery('#urlonly_cntl').click(createShortCodeAndExportLink);
                jQuery('#reset_button').click(reset);
                jQuery('#btn_validate_submit_time').click(validateSubmitTime);
                jQuery('#add_filter').change(function () {
                    showValidateSubmitTimeHelp(jQuery('#add_filter').val() == "submit_time");
                });
                jQuery('#export_cntl').change(function () {
                    showHideOptionDivs();
                    createShortCodeAndExportLink();
                });
                jQuery('#add_itemtitle').change(createShortCodeAndExportLink);
                jQuery('#csv_delim').keyup(createShortCodeAndExportLink);
                jQuery('#export_link_csv_delim').keyup(createShortCodeAndExportLink);
                jQuery('#gld_user').change(createShortCodeAndExportLink);
                jQuery('#gld_user').keyup(createShortCodeAndExportLink);
                jQuery('#gld_pass').change(createShortCodeAndExportLink);
                jQuery('#gld_pass').keyup(createShortCodeAndExportLink);
                jQuery('#obfuscate_cntl').click(createShortCodeAndExportLink);
                jQuery('#form_name_cntl').change(createShortCodeAndExportLink);
            });


        </script>

        <?php
    }

    function outputCSS() {
        ?>
        <!--suppress CssInvalidPropertyValue -->
        <style type="text/css">
            div.shortcodeoptions {
                border: #ccccff groove;
                margin-bottom: 10px;
                padding: 5px;
            }

            div.shortcodeoptions label {
                font-weight: bold;
                font-family: Arial sans-serif;
                margin-top: 5px;
            }

            #shortcode_result_div {
                margin-top: 1em;
            }

            .label_box {
                display: inline-block;
                min-width: 50px;
                padding-left: 2px;
                padding-right: 2px;
                border: 1px;
                margin-right: 5px;
            }

            .generated {
                margin-top: 5px;
                margin-bottom: 5px;
                margin-left: 10px;
                white-space: -moz-pre-wrap;
                white-space: -pre-wrap;
                white-space: -o-pre-wrap;
                white-space: pre-wrap;
                word-wrap: break-word;
                font-size: larger;
                font-weight: bold;
                font-family: "courier new", monospace;
                background-color: #ffffc3;
            }

            .validation {
                background-color: #ffe200;
                font-style: italic;
            }
        </style>

        <?php
    }

}
