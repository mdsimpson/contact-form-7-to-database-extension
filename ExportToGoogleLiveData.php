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
require_once('CFDBExport.php');
include_once('CFDBDie.php');

class ExportToGoogleLiveData implements CFDBExport {

    public function export($formName, $options = null) {
        $plugin = new CF7DBPlugin();
        if (!$plugin->canUserDoRoleOption('CanSeeSubmitData')) {
            CFDBDie::wp_die(__('You do not have sufficient permissions to access this page.', 'contact-form-7-to-database-extension'));
        }
        header('Expires: 0');
        header('Cache-Control: no-store, no-cache, must-revalidate');

        $pluginUrlDir = $plugin->getPluginDirUrl();
        $scriptLink = $pluginUrlDir . 'CFDBGoogleSSLiveData.php';
        $imageUrlDir = $pluginUrlDir . "help";
        $userName = is_user_logged_in() ? wp_get_current_user()->user_login : 'user';

        ob_start();
        ?>
        <style type="text/css">
            *.popup-trigger {
                position: relative;
                z-index: 0;
            }

            *.popup-trigger:hover {
                background-color: transparent;
                z-index: 50;
            }

            *.popup-content {
                position: absolute!important;
                background-color: #ffffff;
                padding: 5px;
                border: 2px gray;
                visibility: hidden!important;
                color: black;
                text-decoration: none;
                min-width:400px;
                max-width:600px;
                overflow: auto;
            }

            *.popup-trigger:hover *.popup-content {
                visibility: visible!important;
                top: 50px!important;
                left: 50px!important;
            }
        </style>
        <?php echo esc_html(__('Setting up a Google Spreadsheet to pull in data from WordPress requires these manual steps:', 'contact-form-7-to-database-extension')); ?>
        <table cellspacing="15px" cellpadding="15px">
            <tbody>
            <tr>
                <td>
                    <div class="popup-trigger">
                        <a href="<?php echo $imageUrlDir ?>/GoogleNewSS.png">
                            <img src="<?php echo $imageUrlDir ?>/GoogleNewSS.png" alt="Create a new spreadsheet" height="100px" width="61px"/>

                            <div class="popup-content">
                                <img src="<?php echo $imageUrlDir ?>/GoogleNewSS.png" alt="Create a new spreadsheet" height="75%" width="75%"/>
                            </div>
                        </a>
                    </div>
                </td>
                <td><p><?php echo esc_html(__('Log into Google Docs and create a new Google Spreadsheet', 'contact-form-7-to-database-extension')); ?></p></td>
            </tr>
            <tr>
                <td>
                    <div class="popup-trigger">
                        <a href="<?php echo $imageUrlDir ?>/GoogleOpenScriptEditor.png">
                            <img src="<?php echo $imageUrlDir ?>/GoogleOpenScriptEditor.png" alt="Create a new spreadsheet" height="69px" width="100px"/>

                            <div class="popup-content">
                                <img src="<?php echo $imageUrlDir ?>/GoogleOpenScriptEditor.png" alt="Create a new spreadsheet" height="75%" width="75%"/>
                            </div>
                        </a>
                    </div>
                </td>
                <td><p><?php _e('Go to <strong>Tools</strong> menu -> <strong>Script Editor...', 'contact-form-7-to-database-extension'); ?></p></td>
            </tr>
            <tr>
                <td>
                    <div class="popup-trigger">
                        <a href="<?php echo $imageUrlDir ?>/GoogleChooseSpreadsheet.png">
                            <img src="<?php echo $imageUrlDir ?>/GoogleChooseSpreadsheet.png" alt="Choose Spreadsheet" height="69px" width="100px"/>

                            <div class="popup-content">
                                <img src="<?php echo $imageUrlDir ?>/GoogleChooseSpreadsheet.png" alt="GoogleChooseSpreadsheet Spreadsheet" height="75%" width="75%"/>
                            </div>
                        </a>
                    </div>
                </td>
                <td><p><?php _e('Choose <strong>Spreadsheet</strong>', 'contact-form-7-to-database-extension'); ?></p></td>
            </tr>
            <tr>
                <td>
                    <div class="popup-trigger">
                        <a href="<?php echo $imageUrlDir ?>/GooglePasteScriptEditor.png">
                            <img src="<?php echo $imageUrlDir ?>/GooglePasteScriptEditor.png" alt="Paste script text" height="68px" width="100px"/>

                            <div class="popup-content">
                                <img src="<?php echo $imageUrlDir ?>/GooglePasteScriptEditor.png" alt="Paste script text" height="75%" width="75%"/>
                            </div>
                        </a>
                    </div>
                </td>
                <td>
                    <p><?php echo esc_html(__('Delete any text that is already there', 'contact-form-7-to-database-extension')); ?></p>
                    <p><?php _e('<strong>Copy</strong> the text from ', 'contact-form-7-to-database-extension'); ?>
                        <a target="_gscript" href="<?php echo($scriptLink) ?>"><?php echo esc_html(__('THIS SCRIPT FILE', 'contact-form-7-to-database-extension')); ?></a>
                        <?php _e('and <strong>paste</strong> it into the Google script editor', 'contact-form-7-to-database-extension'); ?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="popup-trigger">
                        <a href="<?php echo $imageUrlDir ?>/GoogleSaveScriptEditor.png">
                            <img src="<?php echo $imageUrlDir ?>/GoogleSaveScriptEditor.png" alt="Create a new spreadsheet" height="100px" width="83px"/>

                            <div class="popup-content">
                                <img src="<?php echo $imageUrlDir ?>/GoogleSaveScriptEditor.png" alt="Create a new spreadsheet" height="75%" width="75%"/>
                            </div>
                        </a>
                    </div>
                </td>
                <td>
                    <p><?php _e('<strong>Save</strong> the script', 'contact-form-7-to-database-extension'); ?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="popup-trigger">
                        <a href="<?php echo $imageUrlDir ?>/GoogleEnterFormula.png">
                            <img src="<?php echo $imageUrlDir ?>/GoogleEnterFormula.png" alt="Create a new spreadsheet" height="43px" width="100px"/>

                            <div class="popup-content">
                                <img src="<?php echo $imageUrlDir ?>/GoogleEnterFormula.png" alt="Create a new spreadsheet" height="75%" width="75%"/>
                            </div>
                        </a>
                    </div>
                </td>
                <td>
                    <p><?php echo esc_html(__('Click on a cell A1 in the Spreadsheet (or any cell)', 'contact-form-7-to-database-extension')); ?>
                        <br/><?php echo esc_html(__('Enter in the cell the formula:', 'contact-form-7-to-database-extension')); ?>
                        <br/><span style="background-color: yellow">
                            <code><?php printf('=cfdbdata("%s", "%s", "%s", "%s")',
                                        esc_html(get_option('home')),
                                        esc_html($formName),
                                        esc_html($userName),
                                        esc_html('<password>')) ?></code></span>
                    </p>
                    <ul>
                        <li><?php echo esc_html(__('Replace the fourth argument with your WordPress password', 'contact-form-7-to-database-extension')); ?></li>
                    </ul>
                    <?php
                    $scBuilderPageUrl = admin_url('admin.php?page=CF7DBPluginShortCodeBuilder&enc=GLD&form=' . urlencode($formName));
                    ?>
                    <p>
                        <a href="<?php echo $scBuilderPageUrl ?>" target="sc"><?php echo esc_html(__('Customize the output by creating a Google Spreadsheet Function call with additional options', 'contact-form-7-to-database-extension')); ?></a>
                    </p>
                </td>
            </tr>
            </tbody>
        </table>
        <span style="color:red; font-weight:bold;">
            WARNING: since you are putting your login information into the Google Spreadsheet, be sure not to share
        the spreadsheet with others.</span>
        <?php
            $html = ob_get_contents();
        ob_end_clean();
        CFDBDie::wp_die($html,
               __('How to Set up Google Spreadsheet to pull data from WordPress', 'contact-form-7-to-database-extension'),
               array('response' => 200, 'back_link' => true));
    }
}
