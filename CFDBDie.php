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

class CFDBDie {

    /**
     * Why this function? It is meant to do what wp_die() does. But in
     * Ajax mode, wp_die just does die(-1). But in this plugin we are leveraging
     * Ajax mode to put in URL hooks to do exports. So it is not really making a in-page
     * call to the url, the full page is navigating to it, then it downloads a CSV file for
     * example. So if there are errors we want the wp_die() error page. So this
     * function is a copy of wp_die without the Ajax mode check.
     * @static
     * @param string $message HTML
     * @param string $title HTML Title
     * @param array $args see wp_die
     * @return void
     */
    static function wp_die($message, $title = '', $args = array()) {
        // Code copied from wp_die without it stopping due to AJAX
        if ( function_exists( 'apply_filters' ) ) {
            $function = apply_filters( 'wp_die_handler', '_default_wp_die_handler');
        } else {
            $function = '_default_wp_die_handler';
        }
        call_user_func( $function, $message, $title, $args );
    }
}
