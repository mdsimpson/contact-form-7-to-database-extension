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

require_once('ExportBase.php');
require_once('CFDBExport.php');

class ExportToRSS extends ExportBase implements CFDBExport {

    public function export($formName, $options = null) {
        $this->setOptions($options);
        $this->setCommonOptions(true);

        // Security Check
        if (get_option('CF7DBPlugin_AllowRSS') !== 'true') {
            if (!$this->isAuthorized()) {
                $this->assertSecurityErrorMessage();
                return;
            }
        }

        $contentType = 'Content-Type: application/rss+xml; charset=UTF-8';
        $this->echoHeaders($contentType);

        // Get the data
        $this->setDataIterator($formName);
        $this->clearAllOutputBuffers();

        if ($this->isFromShortCode) {
            ob_start();
        }

        $this->echoRSS($formName);

        if ($this->isFromShortCode) {
            // If called from a shortcode, need to return the text,
            // otherwise it can appear out of order on the page
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        }
    }

    protected function echoRSS($formName) {

        $rssUrl = get_site_url() . $_SERVER['REQUEST_URI'];
        $escapedRssUrl = esc_html($rssUrl);
        $htmlVersionOfRss = get_site_url() . str_replace('enc=RSS', 'enc=HTML', $_SERVER['REQUEST_URI']);
        $htmlSingleRow = $htmlVersionOfRss;

        // Set up $htmlSingleRow to have a "{submit_time}" value to be string replaced later
        if (strpos($htmlSingleRow, 'filter=') === false) {
            $htmlSingleRow .= '&filter=submit_time={submit_time}';
        }
        else {
            $htmlSingleRow = str_replace('filter=', 'filter=submit_time={submit_time}' . urlencode('&&'), $htmlSingleRow);
        }


        $this->setTimezone();
        $dateString = date('r');

        $titleColunm = 'Submitted';
        if (isset($this->options['itemtitle'])) {
            $titleColunm = $this->options['itemtitle'];
        }

        echo '<?xml version="1.0" encoding="UTF-8" ?>';
        ?>

<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title><?php echo esc_html($formName) ?></title>
        <description>Contact Form DB plugin Submissions</description>
        <link><?php echo $escapedRssUrl ?></link>
        <lastBuildDate><?php echo $dateString ?></lastBuildDate>
        <pubDate><?php echo $dateString ?></pubDate>
        <atom:link href="<?php echo $escapedRssUrl; ?>" rel="self" type="application/rss+xml" />
        <ttl>60</ttl><?php
        while ($this->dataIterator->nextRow()) {?>

            <item>
                <title><?php echo $this->dataIterator->row[$titleColunm]; ?></title>
                <description><?php
                    $rowUrl = esc_html(str_replace('{submit_time}', $this->dataIterator->row['submit_time'], $htmlSingleRow));
                    foreach ($this->dataIterator->getDisplayColumns() as $aCol) {
                        if ($aCol != 'Submitted') {
                            $cell = esc_html($this->dataIterator->row[$aCol], null, 'UTF-8');
                            echo "$aCol=$cell \n";
                        }
                    }
                ?></description>
                <link><?php echo $rowUrl; ?></link>
                <guid><?php echo $rowUrl ?></guid>
                <pubDate><?php echo date('r', $this->dataIterator->row['submit_time']); ?></pubDate>
            </item>
            <?php
        }?>
    </channel>
</rss>
    <?php
    }

    protected function setTimezone()
    {
        if (function_exists('get_option')) {
            $tz = get_option('CF7DBPlugin_Timezone'); // see CFDBPlugin->setTimezone()
            if (!$tz) {
                $tz = get_option('timezone_string');
            }
            if ($tz) {
                date_default_timezone_set($tz);
            }
        }
    }
}