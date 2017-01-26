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

class CFDBIntegrationContactForm7 {

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
        add_action('wpcf7_before_send_mail', array(&$this, 'saveFormData'));
        // Generate submit_time for CF7 mail. Some people complain this causes an error
        // so this is now optional and off by default. Seems to be related to CF7
        // checking its data against blacklist
        if ($this->plugin->getOption('GenerateSubmitTimeInCF7Email', 'false', true) == 'true') {
            add_action('wpcf7_posted_data', array(&$this, 'generateSubmitTimeForCF7'));
        }
    }

    /**
     * Callback from Contact Form 7. CF7 passes an object with the posted data which is inserted into the database
     * by this function.
     * @param $cf7 WPCF7_ContactForm
     * @return bool
     */
    public function saveFormData($cf7) {
        try {
            $data = $this->convertData($cf7);
            return $this->plugin->saveFormData($data);
        } catch (Exception $ex) {
            $this->plugin->getErrorLog()->logException($ex);
        }
        return true;
    }


    /**
     * @param $cf7 WPCF7_ContactForm
     * @return object
     */
    public function convertData($cf7) {
        if (!isset($cf7->posted_data) && class_exists('WPCF7_Submission')) {
            // Contact Form 7 version 3.9 removed $cf7->posted_data and now
            // we have to retrieve it from an API
            $submission = WPCF7_Submission::get_instance();
            if ($submission) {
                $data = array();
                $data['title'] = $cf7->title();
                $data['posted_data'] = $submission->get_posted_data();
                $data['uploaded_files'] = $submission->uploaded_files();
                $data['WPCF7_ContactForm'] = $cf7;

                if ('true' == $this->plugin->getOption('IntegrateWithCF7SavePageTitle', 'false', true)) {
                    $data['posted_data']['Page Title'] = wpcf7_special_mail_tag('', '_post_title', '');
                }
                if ('true' == $this->plugin->getOption('IntegrateWithCF7SavePageUrl', 'false', true)) {
                    $data['posted_data']['Page URL'] = wpcf7_special_mail_tag('', '_post_url', '');
                }

                return (object) $data;
            }
        }
        return $cf7;
    }

    /**
     * Generate the submit_time and submit_url so they can be added to CF7 mail
     * @param $posted_data array
     * @return array
     */
    public function generateSubmitTimeForCF7($posted_data) {
        try {
            $time = $this->plugin->generateSubmitTime();
            $posted_data['submit_time'] = $time;

// No longer generating submit_url because it seems to cause CF7 to think it is
// a spam submission and it drops it.
//            $url = $this->getAdminUrlPrefix('admin.php') . sprintf('page=%s&submit_time=%s',
//
//                    $this->getDBPageSlug(),
//                    $time);
//            $posted_data['submit_url'] = $url;
        } catch (Exception $ex) {
            $this->plugin->getErrorLog()->logException($ex);
        }
        return $posted_data;
    }


}