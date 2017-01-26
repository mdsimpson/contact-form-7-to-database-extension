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

abstract class ShortCodeLoader {

    /**
     * @param  $shortcodeName mixed either string name of the shortcode
     * (as it would appear in a post, e.g. [shortcodeName])
     * or an array of such names in case you want to have more than one name
     * for the same shortcode
     * @return void
     */
    public function register($shortcodeName) {
        $this->registerShortcodeToFunction($shortcodeName, 'handleShortcode');
    }

    /**
     * @param $shortcodeName string|array name of the shortcode
     * as it would appear in a post, e.g. [shortcodeName]
     * or an array of such names in case you want to have more than one name
     * for the same shortcode
     * @param $functionName string name of public function in this class to call as the
     * shortcode handler
     * @return void
     */
    protected function registerShortcodeToFunction($shortcodeName, $functionName) {
        if (is_array($shortcodeName)) {
            foreach ($shortcodeName as $aName) {
                add_shortcode($aName, array($this, $functionName));
            }
        }
        else {
            add_shortcode($shortcodeName, array($this, $functionName));
        }
    }

    /**
     * @param $atts array
     * @return array
     */
    public function decodeAttributes($atts) {
        if (is_array($atts)) {
            foreach ($atts as $key => $value) {
                $atts[$key] = $this->decodeString($value);
            }
            $atts = $this->workAround_29658($atts);
        }
        return $atts;
    }

    // Work-around for https://core.trac.wordpress.org/ticket/29658
    public function workAround_29658($atts) {
        if (isset($atts[0])) {
            if (isset($atts['filter']) && strpos($atts['filter'], '”') === 0) {
                $atts['filter'] = $atts['filter'] . ' ' . $atts[0];
                $atts['filter'] = $this->stripCurlyQuotes($atts['filter']);
                unset($atts[0]);
            }
            else if (isset($atts['tfilter']) && strpos($atts['tfilter'], '”') === 0) {
                $atts['tfilter'] = $atts['tfilter'] . ' ' . $atts[0];
                $atts['tfilter'] = $this->stripCurlyQuotes($atts['tfilter']);
                unset($atts[0]);
            }
            else if (isset($atts['trans']) && strpos($atts['trans'], '”') === 0) {
                $atts['trans'] = $atts['trans'] . ' ' . $atts[0];
                $atts['trans'] = $this->stripCurlyQuotes($atts['trans']);
                unset($atts[0]);
            }
        }
        return $atts;
    }

    /**
     * Deal with WordPress editor or theme replacing quoted short code attributed
     * with different variations of quotes, which would then be included in the attribute
     * string and cause errors
     * @param $text string
     * @return string
     */
    public function decodeString($text) {
        $text = html_entity_decode($text);
        $text = $this->stripCurlyQuotes($text);
        return $text;
    }

    /**
     * Remove leading-ending curly quotes
     * @param $text string
     * @return string
     */
    public function stripCurlyQuotes($text) {
        $quotes = array('“', '”', '‟', '〝', '〞', '″');
        $startsWith = false;
        $startQuote = null;
        foreach ($quotes as $startQuote) {
            $quoteLen = strlen($startQuote);
            $startsWith = substr($text, 0, $quoteLen) == $startQuote;
            if ($startsWith) {
                break;
            }
        }
        if ($startsWith) {
            foreach ($quotes as $endQuote) {
                $quoteLen = strlen($endQuote);
                $endsWith = substr($text, -$quoteLen) == $endQuote;
                if ($endsWith) {
                    $text = substr($text,
                            strlen($startQuote),
                            strlen($text) - $quoteLen -  strlen($startQuote));
                    break;
                }
            }
        }
        return $text;
    }

    /**
     * @abstract Override this function and add actual shortcode handling here
     * @param $atts array (associative) of shortcode inputs
     * @param $content string inner content of short code
     * @return string shortcode content
     */
    public abstract function handleShortcode($atts, $content = null);

}
