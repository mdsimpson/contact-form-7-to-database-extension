<?php
/*
    "Contact Form to Database" Copyright (C) 2011-2012 Michael Simpson  (email : michael.d.simpson@gmail.com)

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

abstract class CFDBView {

    /**
     * @abstract
     * @param  $plugin CF7DBPlugin
     * @return void
     */
    abstract function display(&$plugin);

    protected function pageHeader(&$plugin) {
        $this->sponsorLink($plugin);
        $this->headerLinks($plugin);
    }

    /**
     * @param $plugin CF7DBPlugin
     * @return void
     */
    protected function sponsorLink(&$plugin) {
    }

    /**
     * @param $plugin CF7DBPlugin
     * @return void
     */
    protected function headerLinks(&$plugin) {
        $notDonated = 'true' != $plugin->getOption('Donated', 'false', true);
        ?>
    <table style="width:100%;">
        <tbody>
        <tr>
            <td width="20%" style="font-size:x-small;">
                <div style="float:left"><a href="http://cfdbplugin.com/" target="_doc">
                        <img src="<?php echo $plugin->getPluginFileUrl('img/icon-50x50.png') ?>" alt="CFDB"/>
                    </a>
                </div>
            </td>
            <td width="20%" style="font-size:x-small;">
                <a target="_cf7todb"
                   href="http://wordpress.org/extend/plugins/contact-form-7-to-database-extension">
                    <?php echo esc_html(__('Rate this Plugin', 'contact-form-7-to-database-extension')) ?>
                </a>
            </td>
            <td width="20%" style="font-size:x-small;">
                <a target="_cf7todb"
                   href="http://cfdbplugin.com/">
                    <?php echo esc_html(__('Documentation', 'contact-form-7-to-database-extension')) ?>
                </a>
            </td>
            <td width="20%" style="font-size:x-small;">
                <a target="_cf7todb"
                   href="http://wordpress.org/support/plugin/contact-form-7-to-database-extension">
                    <?php echo esc_html(__('Support', 'contact-form-7-to-database-extension')) ?>
                </a>
            </td>
            <td width="20%" style="font-size:x-small;">
                <?php
                if ($notDonated) { ?>
                    <a target="_donate"
                       href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=NEVDJ792HKGFN&lc=US&item_name=Wordpress%20Plugin&item_number=cf7%2dto%2ddb%2dextension&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted">
                        <img src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" alt="Donate">
                    </a> <?php } ?>
            </td>
        </tr>
        </tbody>
    </table>
    <?php

    }
}
