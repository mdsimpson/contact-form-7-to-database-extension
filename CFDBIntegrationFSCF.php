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

class CFDBIntegrationFSCF {

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

    public function registerHooks() {
        add_action('fsctf_mail_sent', array(&$this->plugin, 'saveFormData'));
        add_action('fsctf_menu_links', array(&$this, 'fscfMenuLinks'));
    }

    /**
     * Function courtesy of Mike Challis, author of Fast Secure Contact Form.
     * Displays Admin Panel links in FSCF plugin menu
     * @return void
     */
    public function fscfMenuLinks() {
        $displayName = $this->plugin->getPluginDisplayName();
        echo '
        <p>
      ' . $displayName .
                ' | <a href="admin.php?page=' . $this->plugin->getDBPageSlug() . '">' .
                __('Database', 'contact-form-7-to-database-extension') .
                '</a>  | <a href="admin.php?page=CF7DBPluginSettings">' .
                __('Database Options', 'contact-form-7-to-database-extension') .
                '</a>  | <a href="admin.php?page=' . $this->plugin->getShortCodeBuilderPageSlug() . '">' .
                __('Build Shortcode', 'contact-form-7-to-database-extension') .
                '</a> | <a href="http://cfdbplugin.com/">' .
                __('Reference', 'contact-form-7-to-database-extension') . '</a>
       </p>
      ';
    }

}