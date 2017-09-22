![CFDB Banner](img/banner-772x250.jpg "CFDB")

[![Build Status](https://travis-ci.org/mdsimpson/contact-form-7-to-database-extension.svg?branch=master)](https://travis-ci.org/mdsimpson/contact-form-7-to-database-extension)

### [Download the latest Install](https://github.com/mdsimpson/contact-form-7-to-database-extension/releases)
### [Download GitHub Updater to get updates](https://github.com/afragen/github-updater/releases)

[Learn more at the CFDB documentation website](https://cfdbplugin.com)

The "CFDB" plugin saves contact form submissions to your WordPress database and provides and administration page and shortcodes to view and display the data.
Video tutorial on the [CFDB Plugin Site](http://cfdbplugin.com/)

By simply installing the plugin, it will automatically begin to capture form submissions from:

* [Contact Form 7 (CF7) plugin](https://wordpress.org/plugins/contact-form-7/)
* [Fast Secure Contact Form (FSCF) plugin](https://wordpress.org/plugins/si-contact-form/)
* [JetPack Contact Form plugin](https://wordpress.org/plugins/jetpack/)
* [Gravity Forms plugin](http://www.gravityforms.com)
* [WR ContactForm plugin](https://wordpress.org/plugins/wr-contactform/)
* [Form Maker plugin](https://wordpress.org/plugins/form-maker/)
* [Formidable Forms (BETA)](https://wordpress.org/plugins/formidable/)
* [Forms Management System (BETA)](http://codecanyon.net/item/forms-management-systemwordpress-frontend-plugin/8978741)
* [Quform plugin (BETA)](http://codecanyon.net/item/quform-wordpress-form-builder/706149/)
* [Ninja Forms plugin (BETA)](https://wordpress.org/plugins/ninja-forms/)
* [Caldera Forms plugin (BETA)](https://wordpress.org/plugins/caldera-forms/)
* [CFormsII (BETA)](https://wordpress.org/plugins/cforms2/)
* [FormCraft Premium (BETA)](http://codecanyon.net/item/formcraft-premium-wordpress-form-builder/5335056)
* [Enfold theme forms](http://themeforest.net/item/enfold-responsive-multipurpose-theme/4519990)

Other form submissions can be saved with the addition of the <a href="http://cfdbplugin.com/?page_id=508">[cfdb-save-form-post]</a> short code on the target submission page.

Contact form plugins are great except for one thing...the ability to save and retrieve the form data to/from the database.
If you get a lot of form submissions, then you end up sorting through a lot of email.

This plugin provides three administration pages in the administration area under the "Contact form DB" submenu.

* "Contact form DB" to view and export form submission data
* "Database Short Code" page to generate shortcodes and exports
* "Database Options" to change configuration parameters

Displaying Saved Data in Posts and Pages

Use shortcodes such as [cfdb-html], [cfdb-table], [cfdb-datatable], [cfdb-value] and [cfdb-json] to display the data on a non-admin page on your site.
Use the short code builder page to set short code options.


## Frequently Asked Questions

> Is there a tutorial?

See the <a href="https://www.youtube.com/watch?v=mcbIKJK6EJ0">Video Tutorial</a>

> I installed the plugin but I don't see any of my forms listed in the administration page 

Nothing will show until you have actual form submissions captured by this plugin. The plugin is not aware of your form definitions, it is only aware of form submissions.

> Where can I find documentation on the plugin?

Refer the [Plugin Site](https://cfdbplugin.com)

> Where do I see the data?

In the admin page, "Contact Form DB"

> Can I display form data on a non-admin web page or in a post?

Yes, [documentation on shortcodes](https://cfdbplugin.com/?page_id=89) `[cfdb-html]`, `[cfdb-datatable]`, `[cfdb-table]`, `[cfdb-json]` and `[cfdb-value]`, etc.

> What is the name of the table where the data is stored?

`wp_cf7dbplugin_submits`

Note: if you changed your WordPress MySql table prefix from the default `wp_` to something else, then this table will also have that prefix instead of `wp_` (`$wpdb->prefix`)

> If I uninstall the plugin, what happens to its data in the database?

By default it remains in your database in its own table. There is an option to have the plugin delete all its data if you uninstall it that you can set if you like.
You can always deactivate the plugin without loosing data.

