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

require_once('ShortCodeLoader.php');
require_once('CFDBPostDataConverter.php');

class CFDBShortCodeSavePostData extends ShortCodeLoader {

    const FORM_TITLE_FIELD = 'form_title';

    /**
     * @param $atts array of short code attributes
     * @param $content string not used
     * @return void
     */
    public function handleShortcode($atts, $content = null) {

        $atts = $this->decodeAttributes($atts);

        if (is_array($atts) && isset($atts['debug']) && $atts['debug'] == 'true') {
            echo '<pre>';
            print_r($_POST);
            echo "\n";
            print_r($_FILES);
            echo '</pre>';
        }

        $converter = new CFDBPostDataConverter();
        $converter->addExcludeField(self::FORM_TITLE_FIELD);
        $title = isset($_POST[self::FORM_TITLE_FIELD]) ? $_POST[self::FORM_TITLE_FIELD] : 'Untitled';
        $data = $converter->convert($title);
        if ($data) {
            // Call hook to submit data
            do_action_ref_array('cfdb_submit', array(&$data));
        }
    }
}
