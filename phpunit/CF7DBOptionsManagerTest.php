<?php

include_once('WP_Mock_Functions.php');

$optionsMetaData = array(
        'CF7DBOptionsManagerSubClass_option1' => array('Be awesome', 'true', 'false'),
        'CF7DBOptionsManagerSubClass_option2' => array('Can do 1', 'Administrator', 'Editor', 'Author', 'Contributor', 'Subscriber', 'Anyone'),
        'CF7DBOptionsManagerSubClass_option3' => array('Can do 2', 'Administrator', 'Editor', 'Author', 'Contributor', 'Subscriber', 'Anyone'),
        'CF7DBOptionsManagerSubClass_option4' => array('Which way?', 'up', 'down')
);
$options = array(
        'CF7DBOptionsManagerSubClass_option1' => 'true',
        'CF7DBOptionsManagerSubClass_option2' => 'Author',
        'CF7DBOptionsManagerSubClass_option3' => 'Editor',
        'CF7DBOptionsManagerSubClass_option4' => 'down',
);

// Mock WP function
function delete_option($option) {
    global $options;
    if (array_key_exists($option, $options)) {
        unset($options[$option]);
        return true;
    }
    return false;
}

// Mock WP function
function update_option($option, $value) {
    global $options;
    if (array_key_exists($option, $options) && $options[$option] == $value) {
        return false;
    }
    $options[$option] = $value;
    return true;
}

// Mock WP function
function CF7DBOptionsManagerTest_get_option($option, $default = false) {
    global $options;
    if (array_key_exists($option, $options)) {
        return $options[$option];
    }
    return $default;
}


// Mock WP function
function add_option($option, $value = '', $deprecated = '', $autoload = 'yes') {
    return update_option($option, $value);
}

// Mock WP function
function current_user_can($capability) {
    switch ($capability) {
        case 'manage_options':
            return false;
        case 'publish_pages':
            return false;
        case 'publish_posts':
            return true;
        case 'edit_posts':
            return true;
        case 'read':
            return true;
    }
    return false;
}

// Mock WP function
function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
}

// Mock WP function
function add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function = '', $icon_url = '', $position = null) {
}

// Mock WP function
$register_setting_count = 0;
function register_setting($option_group, $option_name, $sanitize_callback = '') {
    global $register_setting_count;
    $register_setting_count++;
}

// Mock WP function
$wp_die_count = 0;
function wp_die($message = '', $title = '', $args = array()) {
    global $wp_die_count;
    $wp_die_count++;
}

// Mock WP Function
function settings_fields($option_group) {
    return $option_group;
}

// Subclass for purposes of testing
include_once(dirname(dirname(__FILE__)) . '/CF7DBOptionsManager.php');

class CF7DBOptionsManagerSubClass extends CF7DBOptionsManager {
    public function getOptionMetaData() {
        global $optionsMetaData;
        return $optionsMetaData;
    }

    public function getMySqlVersion() {
        return 'My MySQL Version';
    }

    protected function getPluginFileUrl($text) {
        return $text;
    }
}
include_once('SquashOutputUnitTest.php');

class CF7DBOptionsManagerTest extends SquashOutputUnitTest {

    function setUp() {
        parent::setup();
        global $alt_get_options;
        $alt_get_options = 'CF7DBOptionsManagerTest_get_option';
    }

    function tearDown() {
        global $alt_get_options;
        unset($alt_get_options);
        parent::tearDown();
    }

    function test_getOptionMetaData() {
        global $optionsMetaData;
        $ops = new CF7DBOptionsManagerSubClass();
        $this->assertEquals($optionsMetaData, $ops->getOptionMetaData());
    }


    function test_getOptionNames() {
        global $optionsMetaData;
        $ops = new CF7DBOptionsManagerSubClass();
        $this->assertEquals(array_keys($optionsMetaData), $ops->getOptionNames());
    }

//    function test_initOptions() {
//        $ops = new CF7DBOptionsManagerSubClass();
//        $ops->initOptions(); // function does nothing
//    }


    function test_deleteSavedOptions() {
        global $options;
        $ops = new CF7DBOptionsManagerSubClass();
        $ops->deleteSavedOptions();
        $this->assertEmpty($options);
    }

    function test_getPluginDisplayName() {
        $ops = new CF7DBOptionsManagerSubClass();
        $this->assertEquals('CF7DBOptionsManagerSubClass', $ops->getPluginDisplayName());
    }

    function test_prefix() {
        $ops = new CF7DBOptionsManagerSubClass();
        $this->assertEquals('CF7DBOptionsManagerSubClass_teststring', $ops->prefix('teststring'));
    }

    function test_unPrefix() {
        $ops = new CF7DBOptionsManagerSubClass();
        $this->assertEquals('teststring', $ops->unprefix('CF7DBOptionsManagerSubClass_teststring'));
    }

    function test_getOption() {
        $ops = new CF7DBOptionsManagerSubClass();
        $this->assertEquals('true', $ops->getOption('option1'));
        $this->assertEquals('Author', $ops->getOption('option2'));
        $this->assertEquals('Editor', $ops->getOption('option3'));
        $this->assertEquals('down', $ops->getOption('option4'));
    }

    function test_getOption_notFound_returnDefault() {
        $ops = new CF7DBOptionsManagerSubClass();
        $this->assertEquals('My default value', $ops->getOption('NOT AN OPTION', 'My default value'));
    }

    function test_getOption_notFound_noDefault() {
        $ops = new CF7DBOptionsManagerSubClass();
        $this->assertEquals(false, $ops->getOption('NOT AN OPTION'));
    }

    function test_deleteOption() {
        global $options;
        $this->assertTrue(isset($options['CF7DBOptionsManagerSubClass_option2']));
        $ops = new CF7DBOptionsManagerSubClass();
        $this->assertTrue($ops->deleteOption('option2'));
        $this->assertFalse(isset($options['CF7DBOptionsManagerSubClass_option2']));
    }

    function test_deleteOption_notFound() {
        global $options;
        $numOps = count($options);
        $ops = new CF7DBOptionsManagerSubClass();
        $this->assertFalse($ops->deleteOption('NOT AN OPTION'));
        $this->assertEquals($numOps, count($options));
    }

    function test_addOption() {
        global $options;
        $numOps = count($options);
        $ops = new CF7DBOptionsManagerSubClass();
        $this->assertTrue($ops->addOption('option999', 'hello'));
        $this->assertEquals($numOps + 1, count($options));
        $this->assertEquals('hello', $options['CF7DBOptionsManagerSubClass_option999']);
    }

    function test_updateOption() {
        global $options;
        $numOps = count($options);
        $ops = new CF7DBOptionsManagerSubClass();
        $this->assertTrue($ops->updateOption('option2', 'hello'));
        $this->assertEquals($numOps, count($options));
        $this->assertEquals('hello', $options['CF7DBOptionsManagerSubClass_option2']);
    }

    function test_getRoleOption() {
        $ops = new CF7DBOptionsManagerSubClass();
        $this->assertEquals('Author', $ops->getRoleOption('option2'));
    }

    function test_getRoleOption_NotFound() {
        $ops = new CF7DBOptionsManagerSubClass();
        $this->assertEquals('Administrator', $ops->getRoleOption('optionXXXXX'));
    }

    function test_roleToCapability() {
        $ops = new CF7DBOptionsManagerSubClass();
        $this->assertEquals('manage_options', $ops->roleToCapability('Super Admin'));
        $this->assertEquals('manage_options', $ops->roleToCapability('Administrator'));
        $this->assertEquals('publish_pages', $ops->roleToCapability('Editor'));
        $this->assertEquals('publish_posts', $ops->roleToCapability('Author'));
        $this->assertEquals('edit_posts', $ops->roleToCapability('Contributor'));
        $this->assertEquals('read', $ops->roleToCapability('Subscriber'));
        $this->assertEquals('read', $ops->roleToCapability('Anyone'));
    }

    function test_isUserRoleEqualOrBetterThan() {
        $ops = new CF7DBOptionsManagerSubClass();
        $this->assertFalse($ops->isUserRoleEqualOrBetterThan('Super Admin'));
        $this->assertFalse($ops->isUserRoleEqualOrBetterThan('Administrator'));
        $this->assertFalse($ops->isUserRoleEqualOrBetterThan('Editor'));
        $this->assertTrue($ops->isUserRoleEqualOrBetterThan('Author'));
        $this->assertTrue($ops->isUserRoleEqualOrBetterThan('Contributor'));
        $this->assertTrue($ops->isUserRoleEqualOrBetterThan('Subscriber'));
        $this->assertTrue($ops->isUserRoleEqualOrBetterThan('Anyone'));
        $this->assertFalse($ops->isUserRoleEqualOrBetterThan('blah'));
    }

    function test_canUserDoRoleOption() {
        $ops = new CF7DBOptionsManagerSubClass();
        $this->assertTrue($ops->canUserDoRoleOption('option2'));
        $this->assertFalse($ops->canUserDoRoleOption('option3'));
    }

    function test_createSettingsMenu() {
        $ops = new CF7DBOptionsManagerSubClass();
        $ops->createSettingsMenu();
        // Not asserting anything
    }

    function test_registerSettings() {
        global $optionsMetaData;
        global $register_setting_count;
        $ops = new CF7DBOptionsManagerSubClass();
        $ops->registerSettings();
        $this->assertEquals(count($optionsMetaData), $register_setting_count);
    }

    function test_settingsPage() {
        global $wp_die_count;
        $ops = new CF7DBOptionsManagerSubClass();
        $ops->settingsPage();
        $this->assertEquals(1, $wp_die_count);
    }

//    function test_createFormControl() {
//        $ops = new CF7DBOptionsManagerSubClass();
//        $ops->createFormControl();
//    }

    function test_getOptionValueI18nString() {
        $ops = new CF7DBOptionsManagerSubClass();
        $this->assertEquals('true', $ops->getOptionValueI18nString('true'));
        $this->assertEquals('false', $ops->getOptionValueI18nString('false'));
    }

//    function test_getMySqlVersion() {
//        $this->fail('unimplemented');
//    }


}