<?php

/*
    "Contact Form to Database" Copyright (C) 2011-2016 Michael Simpson  (email : michael.d.simpson@gmail.com)

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

require_once('CFDBView.php');

class CFDBViewOptions extends CFDBView {

    /**
     * @param  $plugin CF7DBPlugin
     * @return void
     */
    function display(&$plugin) {
        $this->pageHeader($plugin);
        if ($this->outputHeader()) {
            $this->outputOptions($plugin);
        }
    }

    public function enqueueSettingsPageScripts() {
        wp_enqueue_style('jquery-ui', plugins_url('/css/jquery-ui.css', __FILE__));
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core', array('jquery'));
        wp_enqueue_script('jquery-ui-tabs', array('jquery'));
    }

    /**
     * @param $plugin CF7DBPlugin
     */
    public function outputOptions($plugin) {
        ?>

        <script type="text/javascript">
            jQuery(function () {
                jQuery("#cfdb_options_tabs").tabs();
            });
        </script>
        <div class="cfdb_options_div">
            <div id="cfdb_options_tabs">
                <ul>
                    <li>
                        <a href="#integrations"><?php _e('Integrations', 'contact-form-7-to-database-extension'); ?></a>
                    </li>
                    <li>
                        <a href="#security"><?php _e('Security', 'contact-form-7-to-database-extension'); ?></a>
                    </li>
                    <li>
                        <a href="#saving"><?php _e('Saving', 'contact-form-7-to-database-extension'); ?></a>
                    </li>
                    <li>
                        <a href="#export"><?php _e('Export', 'contact-form-7-to-database-extension'); ?></a>
                    </li>
                    <li>
                        <a href="#adminview"><?php _e('Admin View', 'contact-form-7-to-database-extension'); ?></a>
                    </li>
                    <li>
                        <a href="#system"><?php _e('System', 'contact-form-7-to-database-extension'); ?></a>
                    </li>
                </ul>
                <div id="integrations">
                    <h3><?php _e('Capture form submissions from these plugins', 'contact-form-7-to-database-extension') ?></h3>
                    <?php
                    $filter = function ($name) {
                        return strpos($name, 'IntegrateWith') === 0 || $name == 'GenerateSubmitTimeInCF7Email';
                    };
                    $this->outputSettings($filter, $plugin);
                    ?>
                </div>
                <div id="security">
                    <?php
                    $filter = function ($name) {
                        return in_array($name, array(
                                'HideAdminPanelFromNonAdmins', 'CanSeeSubmitDataViaShortcode', 'CanSeeSubmitData', 'CanChangeSubmitData',
                                'FunctionsInShortCodes', 'AllowRSS'));
                    };
                    $this->outputSettings($filter, $plugin);
                    ?>
                    <p>
                        <a target="_blank" href="http://cfdbplugin.com/?page_id=625" style="font-weight: bold"><?php _e('Notes on security settings', 'contact-form-7-to-database-extension'); ?></a>
                    </p>
                </div>
                <div id="saving">
                    <?php
                    $filter = function ($name) {
                        return in_array($name, array(
                                'Timezone', 'NoSaveFields', 'NoSaveForms',
                                'SaveCookieData', 'SaveCookieNames'));
                    };
                    $this->outputSettings($filter, $plugin);
                    ?>
                </div>
                <div id="export">
                    <?php
                    $filter = function ($name) {
                        return in_array($name, array(
                                'SubmitDateTimeFormat', 'UseCustomDateTimeFormat', 'ShowFileUrlsInExport'));
                    };
                    $this->outputSettings($filter, $plugin);
                    ?>
                </div>
                <div id="adminview">
                    <?php
                    $filter = function ($name) {
                        return in_array($name, array(
                                'MaxRows', 'MaxVisibleRows', 'HorizontalScroll', 'UseDataTablesJS',
                                'ShowLineBreaksInDataTable', 'ShowQuery'));
                    };
                    $this->outputSettings($filter, $plugin);
                    ?>
                </div>
                <div id="system">
                    <?php $this->outputSystemSettings($plugin);
                    $filter = function ($name) {
                        return in_array($name, array(
                                'ErrorOutput', 'DropOnUninstall', '_version'));
                    };
                    $this->outputSettings($filter, $plugin);
                    ?>
                </div>
            </div>

        </div>
        <?php
        $this->outputFooter();
    }

    /**
     * @return bool false means don't display additional contents because PHP version is too old
     */
    public function outputHeader() {
        if (version_compare(phpversion(), '5.3') < 0) {
            printf('<h1>%s</h1>',
                    __('PHP Upgrade Needed', 'contact-form-7-to-database-extension'));
            _e('This page requires PHP 5.3 or later on your server.', 'contact-form-7-to-database-extension');
            echo '<br/>';
            _e('Your server\'s PHP version: ', 'contact-form-7-to-database-extension');
            echo phpversion();
            echo '<br/>';
            printf('<a href="https://wordpress.org/about/requirements/">%s</a>',
                    __('See WordPress Recommended PHP Version', 'contact-form-7-to-database-extension'));
            return false;
        }

    ?>
        <style type="text/css">
            table.cfdb-options-table {
                width: 100%
            }

            table.cfdb-options-table tr:nth-child(even) {
                background: #f9f9f9
            }

            table.cfdb-options-table tr:nth-child(odd) {
                background: #FFF
            }

            table.cfdb-options-table td:first-child {
                width: 350px;
            }

            table.cfdb-options-table td p {
                margin-bottom: 0;
                margin-top: 0;
                padding-right: 4px
            }
        </style>
        <div>
            <form method="post" action="">
                <p class="submit">
                    <input type="submit" class="button-primary"
                           value="<?php echo esc_attr(__('Save Changes', 'contact-form-7-to-database-extension')); ?>"/>
                </p>

            <?php
            $settingsGroup = get_class($this) . '-settings-group';
            settings_fields($settingsGroup);
            return true;

        }

    public function outputFooter() {
        ?>
            </form>
        </div>
        <?php
    }

    /**
     * @param $plugin CF7DBPlugin
     */
    public function outputSystemSettings(&$plugin) {
        ?>
        <table class="cfdb-options-table">
            <tbody>
            <?php
            if (function_exists('php_uname')) {
                try { ?>
                    <tr>
                        <td><?php echo esc_html(__('System', 'contact-form-7-to-database-extension')); ?></td>
                        <td><?php echo php_uname(); ?></td>
                    </tr>
                    <?php
                } catch (Exception $ex) {
                }
            } ?>
            <tr>
                <td><?php echo esc_html(__('PHP Version', 'contact-form-7-to-database-extension')); ?></td>
                <td><?php echo phpversion(); ?>
                    <?php
                    if (version_compare('5.2', phpversion()) > 0) {
                        echo '&nbsp;&nbsp;&nbsp;<span style="background-color: #ffcc00;">';
                        echo esc_html(__('(WARNING: This plugin may not work properly with versions earlier than PHP 5.2)', 'contact-form-7-to-database-extension'));
                        echo '</span>';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td><?php echo esc_html(__('MySQL Version', 'contact-form-7-to-database-extension')); ?></td>
                <td><?php echo $plugin->getMySqlVersion() ?>
                    <?php
                    echo '&nbsp;&nbsp;&nbsp;<span style="background-color: #ffcc00;">';
                    if (version_compare('5.0', $plugin->getMySqlVersion()) > 0) {
                        echo esc_html(__('(WARNING: This plugin may not work properly with versions earlier than MySQL 5.0)', 'contact-form-7-to-database-extension'));
                    }
                    echo '</span>';
                    ?>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            </tbody>
        </table>
        <?php
    }


    /**
     * @param $filterFunction callable
     * @param $plugin CF7DBPlugin
     */
    public function outputSettings($filterFunction, &$plugin) {
        $optionMetaData = $plugin->getOptionMetaData();
        if ($optionMetaData == null) {
            return;
        }

        ?>
        <table class="cfdb-options-table">
            <tbody>
            <?php
            foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
                if ($filterFunction($aOptionKey)) {
                    $displayText = is_array($aOptionMeta) ? $aOptionMeta[0] : $aOptionMeta;
                    $displayText = __($displayText, 'contact-form-7-to-database-extension');
                    ?>
                    <tr valign="top">
                        <td><p><label for="<?php echo $aOptionKey ?>"><?php echo $displayText ?></label></p></td>
                        <td>
                            <?php $plugin->createFormControl($aOptionKey, $aOptionMeta, $plugin->getOption($aOptionKey)); ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
            </tbody>
        </table>

        <?php

    }

}

