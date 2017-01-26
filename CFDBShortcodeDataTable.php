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

require_once('ShortCodeScriptLoader.php');

class CFDBShortcodeDataTable extends ShortCodeScriptLoader {

    public function handleShortcode($atts, $content = null) {
        $atts['content'] = $content;
        $atts['useDT'] = true;
        require_once('CFDBShortcodeTable.php');
        $sc = new CFDBShortcodeTable();
        return $sc->handleShortcode($atts, $content);
    }

    public function register($shortcodeName) {
        parent::register($shortcodeName);

        // Unfortunately, can't put styles in the footer so we have to always add this style sheet
        // There is an article about how one might go about this here:
        //     http://beerpla.net/2010/01/13/wordpress-plugin-development-how-to-include-css-and-javascript-conditionally-and-only-when-needed-by-the-posts/
        // But it appears to expects posts on the page and I'm concerned it will not work in all cases

        // Just enqueuing it causes problems in some pages. Need a targeted way to do this.
//        wp_enqueue_style('datatables-demo', 'http://www.datatables.net/release-datatables/media/css/demo_table.css');
    }

    public function addScript() {
//        wp_register_style('datatables-demo', 'http://www.datatables.net/release-datatables/media/css/demo_table.css');
//        wp_print_styles('datatables-demo');

//        wp_register_script('datatables', 'http://www.datatables.net/release-datatables/media/js/jquery.dataTables.js', array('jquery'), false, true);
        wp_enqueue_script('datatables',  plugins_url('/', __FILE__) . 'DataTables/media/js/jquery.dataTables.min.js', array('jquery'));
        do_action_ref_array('cfdb_edit_enqueue', array());
        wp_print_scripts('datatables');
    }

}
