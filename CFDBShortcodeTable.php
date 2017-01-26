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

class CFDBShortcodeTable extends ShortCodeLoader {

    /**
     * Shortcode callback for writing the table of form data. Can be put in a page or post to show that data.
     * Shortcode options:
     * [cfdb-table form="your-form"]                             (shows the whole table with default options)
     * Controlling the Display: Apply your CSS to the table; set the table's 'class' or 'id' attribute:
     * [cfdb-table form="your-form" class="css_class"]           (outputs <table class="css_class"> (default: class="cf7-db-table")
     * [cfdb-table form="your-form" id="css_id"]                 (outputs <table id="css_id"> (no default id)
     * [cfdb-table form="your-form" id="css_id" class="css_class"] (outputs <table id="css_id" class="css_class">
     * Filtering Columns:
     * [cfdb-table form="your-form" show="field1,field2,field3"] (optionally show selected fields)
     * [cfdb-table form="your-form" hide="field1,field2,field3"] (optionally hide selected fields)
     * [cfdb-table form="your-form" show="f1,f2,f3" hide="f1"]   (hide trumps show)
     * Filtering Rows:
     * [cfdb-table form="your-form" filter="field1=value1"]      (show only rows where field1=value1)
     * [cfdb-table form="your-form" filter="field1!=value1"]      (show only rows where field1!=value1)
     * [cfdb-table form="your-form" filter="field1=value1&&field2!=value2"] (Logical AND the filters using '&&')
     * [cfdb-table form="your-form" filter="field1=value1||field2!=value2"] (Logical OR the filters using '||')
     * [cfdb-table form="your-form" filter="field1=value1&&field2!=value2||field3=value3&&field4=value4"] (Mixed &&, ||)
     * @param $atts array of short code attributes
     * @param $content string inner content of short code
     * @return string HTML output of shortcode
     */
    public function handleShortcode($atts, $content = null) {
        if (isset($atts['form'])) {
            $atts = $this->decodeAttributes($atts);
            $atts['content'] = $content;
            $atts['canDelete'] = false;
            $atts['fromshortcode'] = true;

            require_once('DereferenceShortcodeVars.php');
            $deref = new DereferenceShortcodeVars;
            $form = $deref->convert($atts['form']);

            require_once('ExportToHtmlTable.php');
            $export = new ExportToHtmlTable();
            return $export->export($form, $atts);
        }
        return '';
    }

}
