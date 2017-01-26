<?php

include_once(dirname(dirname(__FILE__)) . '/CFDBIntegrationWRContactForm.php');

class CFDBIntegrationWRContactFormTest extends PHPUnit_Framework_TestCase {

    public function testSubmission() {
        $dataForms = file_get_contents('CFDBIntegrationWRContactFormTest/dataForms.dat');
        $postID = 'i:645;';
        $post = file_get_contents('CFDBIntegrationWRContactFormTest/post.dat');
        $submissionsData = file_get_contents('CFDBIntegrationWRContactFormTest/submissionsData.dat');
        $dataContentEmail = file_get_contents('CFDBIntegrationWRContactFormTest/dataContentEmail.dat');
        $nameFileByIdentifier = file_get_contents('CFDBIntegrationWRContactFormTest/nameFileByIdentifier.dat');
        $requiredField = file_get_contents('CFDBIntegrationWRContactFormTest/requiredField.dat');
        $fileAttach = 'N;';

        $dataForms = unserialize($dataForms);
        $postID = unserialize($postID);
        $post = unserialize($post);
        $submissionsData = unserialize($submissionsData);
        $dataContentEmail = unserialize($dataContentEmail);
        $nameFileByIdentifier = unserialize($nameFileByIdentifier);
        $requiredField = unserialize($requiredField);
        $fileAttach = unserialize($fileAttach);

        $wr = new CFDBIntegrationWRContactForm(null);
        $data = $wr->convertData($dataForms, $postID, $post, $submissionsData, $dataContentEmail,
                $nameFileByIdentifier, $requiredField, $fileAttach);

        $this->assertEquals("Title_$postID", $data->title);
        $this->assertEquals('hi there', $data->posted_data['my single line']);
        $this->assertEquals('second line', $data->posted_data['my next single line']);
        $this->assertEquals('Mr Michael D Simpson', $data->posted_data['my name']);
        $this->assertEquals("123 Main Street, line2, Washington, DC 12345 United States", $data->posted_data['My Address']);
        $this->assertEquals('345', $data->posted_data['My Number']);
        $this->assertEquals('Checkbox 1  Checkbox 2', $data->posted_data['My Checkboxes']);
        $this->assertEquals('Choice 1', $data->posted_data['My Radio Buttons']);
        $this->assertEquals('Value 3', $data->posted_data['My List']);
        $this->assertEquals('info@cfdbplugin.com', $data->posted_data['My Email']);
        $this->assertEquals('http://cfdbplugin.com', $data->posted_data['Website']);
        $this->assertEquals('1234567890', $data->posted_data['Phone']);
        $this->assertEquals('Value 3', $data->posted_data['Dropdown']);
        $this->assertEquals('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris fermentum odio sed ipsum fringilla ut tempor magna accumsan. Aliquam erat volutpat. Vestibulum euismod ipsum non risus dignissim hendrerit. Nam metus arcu, blandit in cursus nec, placerat vitae arcu. Maecenas ornare porta mi, et tincidunt nulla luctus non.”', $data->posted_data['Text']);
        $this->assertEquals('N/A', $data->posted_data['File Upload']);
        $this->assertEquals('01/19/2015', $data->posted_data['Date/Time']);
        $this->assertEquals('45,09', $data->posted_data['Currency']);
        $this->assertEquals("Line1\nLine2\nLine3", $data->posted_data['Multi-line Input']);
        $this->assertEquals('dogs: Good  cats: Average  frogs: Poor', $data->posted_data['My likes']);
        $this->assertEquals('United States', $data->posted_data['My Country']);
        $this->assertEquals('hello', $data->posted_data['My Password']);
    }


    public function dataProviderFileUrl() {
        $wpContentDirPath = dirname(dirname(dirname(dirname(__FILE__))));
        $data = array();
        $data[] = array('<a href="http://www.site.com/wp-content/uploads/2015/08/Amazon-icon1.png">Amazon.png</a>',
                $wpContentDirPath . '/uploads/2015/08/Amazon-icon1.png');
        $data[] = array('<a href="http://www.site-something.com/wp-content/uploads/2015/06/twitter.png">Twitter.png</a>',
                $wpContentDirPath . '/uploads/2015/06/twitter.png');
        $data[] = array('<a href="http://site.com/wp-content/uploads/wr_contactform/2015/01/icon-50x50.png">icon-50x50.png</a>',
                $wpContentDirPath . '/uploads/wr_contactform/2015/01/icon-50x50.png');
        return $data;
    }

    /**
     * @dataProvider dataProviderFileUrl
     */
    public function testParseFileUrl($fileUrl, $filePath) {
        $wr = new CFDBIntegrationWRContactForm(null);
        $this->assertEquals($filePath, $wr->parseFileUrl($fileUrl));
    }

}

/**
 * Mock of WP get_the_title
 * @param $postID
 * @return string
 */
function get_the_title($postID) {
    return "Title_$postID";
}

