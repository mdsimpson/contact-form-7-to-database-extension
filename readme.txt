=== Contact Form DB ===
Contributors: msimpson
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=NEVDJ792HKGFN&lc=US&item_name=Wordpress%20Plugin&item_number=cf7%2dto%2ddb%2dextension&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: contact form,database,contact form database,save contact form,form database,CFDB
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Requires at least: 3.2.1
Tested up to: 4.8.2
Stable tag: 2.10.36

Saves submitted form data to the database. Export the data to a file or use shortcodes to display it.

== Description ==

The "CFDB" plugin saves contact form submissions to your WordPress database and provides and administration page and shortcodes to view and display the data.
Video tutorial on the <a href="http://cfdbplugin.com/">CFDB Plugin Site</a>

By simply installing the plugin, it will automatically begin to capture form submissions from:

* <a href="https://wordpress.org/plugins/contact-form-7/">Contact Form 7 (CF7) plugin</a>
* <a href="https://wordpress.org/plugins/si-contact-form/">Fast Secure Contact Form (FSCF) plugin</a>
* <a href="https://wordpress.org/plugins/jetpack/">JetPack Contact Form plugin</a>
* <a href="http://www.gravityforms.com">Gravity Forms plugin</a>
* <a href="https://wordpress.org/plugins/wr-contactform/">WR ContactForm plugin</a>
* <a href="https://wordpress.org/plugins/form-maker/">Form Maker plugin</a>
* <a href="https://wordpress.org/plugins/formidable/">Formidable Forms (BETA)</a>
* <a href="http://codecanyon.net/item/forms-management-systemwordpress-frontend-plugin/8978741">Forms Management System (BETA)</a>
* <a href="http://codecanyon.net/item/quform-wordpress-form-builder/706149/">Quform plugin (BETA)</a>
* <a href="https://wordpress.org/plugins/ninja-forms/">Ninja Forms plugin (BETA)</a>
* <a href="https://wordpress.org/plugins/caldera-forms/">Caldera Forms plugin (BETA)</a>
* <a href="https://wordpress.org/plugins/cforms2/">CFormsII (BETA)</a>
* <a href="http://codecanyon.net/item/formcraft-premium-wordpress-form-builder/5335056">FormCraft Premium (BETA)</a>
* <a href="http://themeforest.net/item/enfold-responsive-multipurpose-theme/4519990">Enfold theme forms</a>

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

== Installation ==

1. Your WordPress site must be running PHP5 or better. This plugin will fail to activate if your site is running PHP4.
1. Be sure that one or more of the supported form plugins installed and activated.

== Frequently Asked Questions ==

= Is there a tutorial? =
See the <a href="https://www.youtube.com/watch?v=mcbIKJK6EJ0">Video Tutorial</a>

= I installed the plugin but I don't see any of my forms listed in the administration page =
Nothing will show until you have actual form submissions captured by this plugin. The plugin is not aware of your form definitions, it is only aware of form submissions.

= Where can I find documentation on the plugin? =
Refer the <a href="http://cfdbplugin.com/">Plugin Site</a>


= Where do I see the data? =

* In the admin page, "Contact Form DB"

= Can I display form data on a non-admin web page or in a post? =

Yes, <a href="http://cfdbplugin.com/?page_id=89">documentation on shortcodes</a> `[cfdb-html]`, `[cfdb-datatable]`, `[cfdb-table]`, `[cfdb-json]` and `[cfdb-value]`, etc.

= What is the name of the table where the data is stored? =

`wp_cf7dbplugin_submits`
Note: if you changed your WordPress MySql table prefix from the default `wp_` to something else, then this table will also have that prefix instead of `wp_` (`$wpdb->prefix`)

= If I uninstall the plugin, what happens to its data in the database? =

By default it remains in your database in its own table. There is an option to have the plugin delete all its data if you uninstall it that you can set if you like.
You can always deactivate the plugin without loosing data.

== Screenshots ==

1. Admin Panel view of submitted form data

== Changelog ==

= 2.10.32 =
* Improvement: Added option for CF7 forms to capture complete URL that form was posted from

= 2.10.31 =
* Improvement: Added support for GitHub updater. Install that plugin as well so that you can update CFDB directly from GitHub instead of from WP. https://github.com/afragen/github-updater

= 2.10.30 =
* Security: updates to protect against XSS attacks
* Removed: outdated upload into Google Sheet via old Zend that doesn't support MFA

= 2.10.29 =
* Security: protecting against a specific XSS vulnerability

= 2.10.28 =
* Updates: to match the following improvements in CFDB Editor 1.5
* Improvement: CSV Import - can now set delimiter, enclosure and escape characters
* Improvement: CSV Import - better de-duplication of submit_times read in CSV

= 2.10.27 =
* Improvement: Updated Excel & ODS exporter to Spout-2.7.1 from Spout-2.7.0
* New: New transform function diff(field,value,[value2,value3,...])
* New: New transform function cfdb_date_diff(StringDateStart,StringDateEnd)
* New: New transform function cfdb_duration(StringDateStart,StringDateEnd,format)

= 2.10.26 =
* Improvement: Ninja Forms integration now observes the "Admin Label" as an override for the field name.

= 2.10.25 =
* Bug Fix: Update to match latest version of wp-jalali plugin for generating Jalali-formatted dates
* Bug Fix: related to key size for mcrypt_decrypt seen on some hosts
* Improvement: Gravity Forms integration now observes teh "Admin Field Label" as an override for the field name.
* Improvement: Updated Excel & ODS exporter to Spout-2.7.0 from Spout-2.4.2

= 2.10.24 =
* Change: the new SplitField transform is changed: can be used to split a single field into multiple fields based on a delimiter in field values.
If you have field "Choice" with values like "AAA|BBB|CCC" you change that to Choice-1,Choice-2,Choice-3 fields using [cfdb-table form="form" trans="SplitField(Choice,|)"]
If you don't indicate a delimiter it is assumed to be a comma:
If you have field "Choice" with values like "AAA,BBB,CCC" you change that to Choice-1,Choice-2,Choice-3 fields using [cfdb-table form="form" trans="SplitField(Choice)"]
Use "headers" to rename those columns:
[cfdb-table form="Gravity Form 1" trans="SplitField(List,|)&&SplitField(Option,|)" headers="Option-1=TextOption,Option-2=NumericOption"]

= 2.10.23 =
* Bug Fix: Admin page Javascript bug exporting file to Google Docs
* Bug Fix: Shortcode builder page Javascript syntax error in Dutch translation due to quoting
* Improvement: Identifying IP address of poster - looking at more HTTP Headers
* New: SplitField transform can be used to split a single field into multiple fields based on a delimiter in field values. If you have field "Choice" with values like "AAA|BBB|CCC" you change that to Choice-1,Choice-2,Choice-3 fields using [cfdb-table form="form" trans="SplitField(|,Choice)"] (use "headers" to rename those columns)
[cfdb-table form="Gravity Form 1" trans="SplitField(|,List)&&SplitField(|,Option)" headers="Option-1=TextOption,Option-2=NumericOption"]
* New: DefaultField transform can be used to set the value in a field if it is not defined. [cfdb-table form="form" trans="DefaultField(score1,0,score2,0)"] would set "score1" and "score2" fields to be 0 where they have no value in the DB
* New: "unknownfields" in [cfdb-html unknownfields="true"] will replace any unknown ${NAME} in the shortcode template with a blank.

= 2.10.22 =
* Bug Fix: Changed to Caldera Forms 1.4.2 was causing an error when saving submissions
* Bug Fix: on Shortcode builder page, certain form names with special characters not displaying correctly

= 2.10.21 =
* Bug Fix: causing short code builder page to fail to generate shortcodes on some sites.

= 2.10.20 =
* Bug Fix: Form Maker submissions were being saved under "Untitled" instead of "Form Maker" form name when no form_name field specified
* Bug Fix: Shortcode builder page: sometimes text after < character in filter not showing in shortcode

= 2.10.19 =
* New: Now saves contact form submissions from Forms Management System. Requires Forms Management 2.7 or later.
* Bug Fix: Sometimes form data not found in dashboard view when form name is from certain character sets (e.g. Hebrew)

= 2.10.18 =
* Bug Fix: Shortcode builder & Import pages: Form names with double quotes in drop downs not working properly
* Improvement: Shortcode builder page layout reorganized using tabs
* New: CountInField transform (different from CountField) can could choices in one field that have multiple entries (from checkboxe field)

= 2.10.17 =
* Bug Fix: Admin View: Form names with double quotes on them could not be viewed in the admin panel
* New: Can save Page Title and Page URL from Contact Form 7 submissions. Enable this in the CFDB Options page.
* Improvement: Setting option for property "Allow only Administrators to see CFDB administration screens" to true by default

= 2.10.16 =
* Improvement: In shortcodes and exports, the form name can be a regular express, for example form names starting with "a" [cfdb-table form="/^a"]
(this is case insensitive based on your MySQL CI setting. It uses a MySQL REGEX, not PHP preg_match)
* Improvement: Helpful message on CFDB Options page when it cannot be displayed due to inadequate PHP version

= 2.10.15 =
* Bug Fix: When adding [submit_time] in Contact Form 7 mails, the value might not match what is in the DB depending on your locale setting.
* Bug Fix: In the CFDB admin page, the WP Footer would appear on top of the CFDB table when it had a lot of entries. The "wpfooter" is not hidden on that page.

= 2.10.14 =
* New: [in] and [!in] operators for filters, for example filter="name[in]Mike,John,Tom"
* Bug Fix: (Minor) Exporting from admin window when more than one work entered in the search box did not match what was shown on screen
* Improvement: When exporting from admin page, if certain rows are selected then only those rows are exported
* Improvement: Minor display fixes

= 2.10.13 =
* Improvement: Excel (.xlsx) exports for forms that include files, now have working hyperlinks to download files from the Excel spreadsheet

= 2.10.12 =
* Improvement: Plugin settings page now organized in tabs
* Improvement: Minor CSS changes on admin pages

= 2.10.11 =
* New: changes to support capturing submissions from Very Simple Contact Form and Very Simple Signup Form

= 2.10.10 =
* Improvement: Admin page allow for the setting of the CSV delimiter for export files. If not set, regional delimiter is used ("," or ";")
* Change: Previous change set CSV exports to use a delimiter based on the region. This is changed back to always use "," unless "delimiter" parameter sets the delimiter or if new "regionaldelimiter" paramter is set to "true"
* New: Added transform trans="AddRowNumberField(fieldname,start)" to add a column labeled "fieldname" that simply numbers the rows starting at number "start"
* New: Integration with Very Simple Contact Form

= 2.10.9 =
* Bug Fix: Google Spreadsheet Live data: bug was introduced in 2.10.6 where data fetched by IMPORTDATA() could return with wrong delimiter (semicolon instead of comma) based on regional settings for CSV delimiter.
This would cause Google Spreadsheet to be unable to parse data into columns

= 2.10.8 =
* Improvement: Links to exports and file downloads now redirect to login page when user is not logged in

= 2.10.7 =
* Bug Fix: Gravity Form integration was not capturing certain date fields

= 2.10.6 =
* Improvement: UTF8 CSV and Excel export now smarter about choosing "," or ";" for field and function delimiters respectively.
* Improvement: Dashboard single-record view now has download links on files which was missing
* Improvement: CFDB Option Error Output File - no longer need to specify full path. Just put in a file name and it will put in the top level WP install directory
* Note: If you have CFDB Editor installed, you will need to update that to version 1.4.3 to work properly with this update

= 2.10.5 =
* New: Now captures form submission from FormCraft Premium (BETA)
* Improvement: Excel & OpenDoc exports now formats Submitted datetime field in a format the spreadsheet recognizes
* Improvement: Gives clear error message when can't do Excel file export due to PHP version less than 5.4
* Improvement: Less queries to fetch options from DB

= 2.10.4 =
* Bug Fix: In some situations UFT-16-LE is not read correctly by Excel (issue related to output buffering)

= 2.10.3 =
* Bug Fix: Fixed issue where some files would not download
* Improvement: Can now add "Submitted Login" and "Submitted From" to the list of fields NOT to save
* Improvement: Short Code builder page for [cfdb-export-link] was missing options to set file type as .xlsx and .ods and to choose a delimiter for CSV files

= 2.10.2 =
* NOTE: Parsing of functions in shortcodes has changed to be more strict.
Check your shortcodes and update them to remove any extraneous spaces.
For example, change trans="field=str_replace(a, b, c)" to trans="field=str_replace(a,b,c)"

= 2.10.1 =
* Bug Fix: Extra output generated (possibly by other plugins or debug) ahead of export file download made export file corrupt. (Not seen on most sites)

= 2.10.0 =
* New: Now supports exports to .xlsx and .ods file formats. This requires PHP 5.4 or later. Otherwise you will get an error on export.
* Improvement: Can now specify the delimiter for CSV exports
* Improvement: Added support for multisite (BETA)
* Improvement: Variable substitution enabled for form name in shortcodes

= 2.9.16 =
* Bug Fix: [cfdb-export-link] is now processing filter $_GET, $_POST, $_COOKIE values before creating the URL
* Bug Fix: To Formidable Forms integration (BETA)

= 2.9.15 =
* Bug Fix: Where WP installations that output debug would add text to ajax return value
* Bug Fix: When WPML plugin is also installed, export and ajax URLs were incorrect

= 2.9.14 =
* New: Formidable Forms integration added (BETA)
* New: CFormsII integration added (BETA)
* Bug Fix: Ninja Form integration (BETA) - warning messages seen when using PayPal
* Performance enhancement: related to accessing options

= 2.9.13 =
* Bug Fix: on some WP installations, delete operations were being blocked

= 2.9.12 =
* Added <a href="http://cfdbplugin.com/?page_id=1167#SummationRow">SummationRow</a> transform

= 2.9.11 =
* Bug fix: where download files can be corrupted (2nd fix)

= 2.9.10 =
* Bug fix: where download files can be corrupted

= 2.9.9 =
* Bug fix: fixing rare stripslash() error
* Bug fix: fixes to permissions

= 2.9.8 =
* Bug fix: Capturing Gravity Forms List element when it has columns

= 2.9.7 =
* Bug Fix: Capturing file uploads from WR Contact Form 1.1.10

= 2.9.6 =
* Bug fix: Occasional problem with language translations being initialized
* Minor update to Contact Form 7 integration

= 2.9.5 =
* Bug fix to Google Spreadsheet Live Data export (failing to login)
* Bug fix for exporting forms with a single quote in the name

= 2.9.4 =
* Added spreadsheet-like transforms:
** Sum rows: trans="total=field1,field2,field3"
** Sum columns: trans=TotalField(field1,field2,field3)
** Sum both: trans="total=field1,field2&&TotalField(field1,field2,total)"
** Sum product prices: trans="p1_total=multiply(p1,9.99)&&p2_total=multiply(p2,8.99)&&line_total=sum(p1_total,p2_total)&&TotalField(line_total)"

= 2.9.3 =
* Fix error on systems where php_uname() is not available
* Allowing various PHP math functions in "trans"

= 2.9.2 =
* Bug Fix: for some cases where cannot delete row in administration page

= 2.9.1 =
* Bug Fix: for some cases where cannot delete row in administration page

= 2.9 =
* Updat: Additional HTML-injection protections
* New: Option when Editor is installed [cfdb-datatable edit="cells"] enables editing of table cells but not column headers

= 2.8.38 =
* Improvement: NaturalSortByMultiField transform now supports up to 10 fields to sort on

= 2.8.37 =
* New: Now captures form submission from Caldera Forms
* Improvement: Formatting changes on CFDB Options page

= 2.8.36 =
* New: Now captures form submission from Quform plugin
* New: Now captures form submission from Ninja Forms plugin

= 2.8.35 =
* Improvement: Reduced cell padding in admin table
* Improvement: Fixed typo in new option

= 2.8.34 =
* New: Now captures Enfold Theme forms
* New on Options page: "Use fixed width in Admin datatable"
* Improvement: Taiwanese language update

= 2.8.33 =
* Bug Fix: Fixed Delete button on Admin entry detail page

= 2.8.32 =
* Improvement: Security patch

= 2.8.31 =
* Bug Fix: to work with Gravity Forms 1.9.1.2

= 2.8.30 =
* Bug Fix: Minor fix to short code builder page

= 2.8.29 =
* Improvement: Tweak to install procedure

= 2.8.28 =
* Improvement: Security patch

= 2.8.27 =
* Improvement: Minor tweak to allow edit mode in short code to be set via $_GET and $_POST

= 2.8.26 =
* Bug Fix: Encoding fix on administration page

= 2.8.25 =
* New: Now captures data from WR ContactForm (BETA)
* New: Option to allow only Administrators to see CFDB administration screens
* New: Option to send CFDB errors to a file to email
* Bug Fix: to avoid rare instances of duplicate submit_time

= 2.8.24 =
* Bug Fix: related to displaying form names in administration panel with certain characters

= 2.8.23 =
* Bug Fix: for Gravity Form integration. Sometimes field value was not captured where there are multiple fields of the same name but only one shown based on conditional field definition.

= 2.8.22 =
* Improvement: Changed icon in admin panel
* Improvement: Dutch translation update

= 2.8.21 =
* Improvement: Added icons to admin panel

= 2.8.20 =
* Improvement: Additional XSS protection for admin panels

= 2.8.19 =
* Improvement: Swedish translation update

= 2.8.18 =
* Improvement: Better XSS protection for admin panels

= 2.8.17 =
* Bug Fix: in [cfdb-html] variable substitution when data for column is not present.

= 2.8.16 =
* New: Generating the [submit_time] tag for Contact Form 7 is now an option in the Options page and is off by default to avoid the post being flagged as spam
* Improvement: Added some protection against cross site scripting

= 2.8.15 =
* Bug Fix: No longer generating 'submit_url' for Contact Form 7 email because it seems to cause CF7 to think it is a spam submission and it drops it.
* Bug Fix: when form name has commas in it, retrieving its data from the DB did not work because plugin was treating it as a list of form names

= 2.8.14 =
* Bug Fix: to capturing Gravity Forms list values

= 2.8.13 =
* Bug Fix: in "trans"

= 2.8.12 =
* Work-around: for WP bug https://core.trac.wordpress.org/ticket/29658

= 2.8.11 =
* Bug Fix: related to Excel Internet Query export
* Bug Fix: when HTML special characters in short code attributes

= 2.8.10 =
* Bug Fix: related to "trans" with multiple transform functions

= 2.8.9 =
* Improvement: Added delete button in admin screen viewing single submission
* New: Can now include in CF7 emails the CFDB [submit_time] and [submit_url]
* Bug Fix: to handling line breaks in [cfdb-json]

= 2.8.8 =
* Bug Fix: work-around for bug in PHP 5.3.0 - 5.3.8 https://bugs.php.net/bug.php?id=43200

= 2.8.7 =
* Bug Fix: [cfdb-export-link] was not processing all short code options

= 2.8.6 =
* Bug Fix: on some system submit_time timestamp lost precision due to being represented in scientific notation. This can cause more than one submission being seen as the same.

= 2.8.5 =
* Bug Fix: to error message introduced in 2.8.4

= 2.8.4 =
* New: Shortcode [cfdb-save-form-maker-post] can be used to capture data from Form Maker plugin.
* Bug Fix: (recently introduced in 2.8) Fixed handling of filter expressions with $_POST(field) where field is blank
* Bug Fix: Fix for rarely experienced bug where plugin is confused about needing to upgrade itself

= 2.8.3 =
* Improvement: IMPORTANT: Addition fix to work properly with Contact Form 7 version 3.9

= 2.8.2 =
* Improvement: IMPORTANT: Contact Form 7 users will need to update to this version when they update to Contact Form 7 version 3.9 otherwise data will no longer be saved in the database!
* Bug Fix: cases where blank version saved to DB was causing the plugin to keep trying to upgrade
* Bug Fix: data with blank column name was causing query to fail and data not to be displayed

= 2.8.1 =
* Bug Fix: transform re-assigning the value of a column was creating a duplicate column

= 2.8 =
* New: Can put functions in short code filters. <a target="_cfdb_doc" href="http://cfdbplugin.com/?page_id=1073">See documentation</a>
* New: Can transform data coming into shortcodes by assigning functions or classes in the new "trans" short code attribute. <a target="_cfdb_doc" href="http://cfdbplugin.com/?page_id=1076">See documentation</a>
* New: All short code options can be set to $_POST(value), $_GET(value), $_COOKIE(value)
* New: Most shortcodes now allows for an optional "before" and "after" section
* New: Admin page view now has links on each row to bring up a window with just that entry's data. Easier to read and print (and edit if Editor extension is installed)
* Bug Fix: Support for MySqli which should fix some character encoding issues
* Bug Fix: Returning null from cfdb_form_data filter now stops the form from saving
* Bug Fix: hook to save form data was not returning "true" an causing subsequent hooks for other plugins to not be called.

= 2.7.5 =
* Improvement: Simpler Google Live Data implementation
* New: [cfdb-json] now supports "header" and "headers" options. Short code builder page now generates JSON export link.

= 2.7.4 =
* Bug Fix: short code builder page for Google Live Data typo: was giving the spreadsheet function as "cfdbddata" instead of "cfdbdata"

= 2.7.3 =
* Bug Fix: for mysql_real_escape_string error

= 2.7.2 =
* Bug Fix: Google Spreadsheet Live Data now fixed. But users need to regenerate Google script code
* New: Google Spreadsheet Live Data function now supports all standard short code options (filter, show, hide, etc.)
* New: Short Code Builder page now can build function call to be used in Google Spreadsheet
* New: [cfdb-export-link], CSV exports and Excel export Links now support "headers" options

= 2.7.1 =
* New: Integration with wp-parsidate to shows Shamsi(Jalali) dates.

= 2.7 =
* New: Integration with Gravity Forms
* Improvement: Translation updates

= 2.6.3 =
* Bug Fix: When option was set, Cookies were not being captured with the form submission unless explicitly listed
* Improvement: Option page has "have donated" option that will turn off showing the Donate button on admin pages

= 2.6.2 =
* Improvement: Admin page has checkbox for selecting all visible rows
* New: Option to set #Rows (of maximum above) visible in the Admin datatable
* New: Custom short code filters (alpha)

= 2.6.1 =
* Bug Fix: needed to strip slashes from dt_options when using cfdb-datatable by URL
* Bug Fix: avoiding divide by zero error in [cfdb-value]
* Bug Fix: avoiding error when no timezone set: "Notice: date_default_timezone_set() [function.date-default-timezone-set.php]: Timezone ID '' is invalid"

= 2.6 =
* New: [cfdb-datatable form="form-name" edit="true"] The "edit" option makes the table editable if the CFDB Editor plugin extension is also installed.
* Improvement: [cfdb-datatable] "Show" setting shows all rows by default
* Improvement: [cfdb-datatable] upgraded the included DataTables to version 1.9.4
* New: Support for import from CSV file when CFDB Editor extension is installed

= 2.5.2 =
* Bug Fix. When user has exactly one form and admin page auto-loads that form's data, the editor plugin did not know which form to edit.

= 2.5.1 =
* New: Now "headers" option on [cfdb-table] and [cfdb-datatable] allow you to change column header display names, e.g [cfdb-table form="form1" headers="ext_field1=First Name,ext_field2=Last Name"]
* New: Can now query multiple forms at once in shortcodes, e.g. [cfdb-table form="form1,form2"]
* New: Added RSS URLs
* New: Can now use regex's in options for forms & fields to ignore and which cookie values to save

= 2.5 =
* New: Added option to set the timezone in which Submit Time should be captured.

= 2.4.8 =
* Bug Fix: noSaveFields not observed for file upload files

= 2.4.7 =
* Bug Fix: for "PHP Notice: Undefined property: WPCF7_ContactForm::$user"

= 2.4.6 =
* Bug Fix: where JetPack users could not see submitted data related to form field names with a single quote in them

= 2.4.5 =
* Bug Fix: issue where admin page for data does not show for users less than administration even when permissions are set for such users to view the data

= 2.4.4 =
* Bug Fix: WordPress 3.5 compatibility issue where uploaded files were not being saved.
* New: New top-level menu on administration page
* Improvement: JetPack form submissions are now separated based on the JetPack form ID. The form name will be listed as 'JetPack Contact Form ' followed by the form's numeric ID.

= 2.4.3 =
* New: Added Japanese language support (Shift-JIS)

= 2.4.2 =
* Bug Fix: character encoding issues with Excel IQY

= 2.4.1 =
* New: Added new short code attribute "role" to limit short code output only to those with sufficient role.
* New: Added new short code attribute "permissionmsg" to allow turning off the "You do not have sufficient permissions to access this data" message

= 2.4 =
* New: Now captures data from JetPack Contact Form
* New: Added filter hook <a href="http://cfdbplugin.com/?page_id=747">cfdb_form_data</a> that can be used to change data before it gets submitted
* New: Added "random" option to short code. Used to retrieve random subset of rows from the query.
Usage example: [cfdb-table form="myform" random="1"].
The number is the number of random rows to return.
* New: Added button to remove _wpcf7 columns in admin page

= 2.3.2 =
* Bug Fix: that occasionally prevents pagination in the admin view of data
* Bug Fix: where external integrations posting to CFDB might fail if list of upload files was null instead of empty array

= 2.3.1 =
* Bug Fix: where $_GET and $_COOKIE would not work

= 2.3 =
* Bug Fix: Variable substitution in filter now works when embedded in text, e.g. filter="fname~~/.*$_POST(fname).*/i&&lname~~/.*$_POST(lname).*/i"
* Bug Fix: Filters: Handling where one has two ampersands in a filter expression to indicate logical AND but the WordPress editor
converts them to html-coded version of ampersand.
* Improvement: Excel IQuery export now takes standard shortcode options
* New: New "header=false" in table type shortcodes and IQuery will avoid showing the header.

= 2.2.7 =
* Bug Fix: Fix to match change to Contact Form 7 version 3.1 where uploaded files were not being saved to the database.

= 2.2.6 =
* Bug Fix: seeing error "undefined function is_plugin_active()"

= 2.2.5 =
* Bug Fix: Admin page data table was not showing top search banner when datatable using i18n
* New: Displays Jalali dates when wp-jalali plugin is installed and activated

= 2.2.4 =
* New: cfdb-html now supports nested shortcodes
* Bug Fix: short code builder for cfdb-html not showing template HTML tags
* Improvement: if "form" missing from a short code, PHP error no longer happens

= 2.2.3 =
* New: Can do exports via the Short Code Builder Page with the main short code options applied (show, hide, search, filter, limit, orderby)

= 2.2.2 =
* Bug Fix: for some users the fix in 2.2.1 for setting character encoding when retrieving was throwing exception because
no $wpdb->set_charset() method exists. Made code handle this case

= 2.2.1 =
* Bug Fix: relating to character encoding: umlaut characters not displaying correctly
* Bug Fix: "class" attribution on table tag was not being emitted for [cfdb-datatables]
* Bug Fix: Fixed some strings that were not being internationalized.
* Improvement: More links to documentation on Database Short Code page

= 2.2 =
* New: Short code "filter" values using submit_time can now use 'strtotime' values. E.g. "submit_time>last week"
* Improvement: Dropped index `form_name_field_name_idx` but this fails on some MySQL versions and needs to be done manually (<a href="http://bugs.mysql.com/bug.php?id=37910">MySQL Bug 37910</a>).

= 2.1.1 =
* Improvement: Upgrade of DataTables to version 1.8.2 from 1.7.6

= 2.1 =
* New: short code: [cfdb-save-form-post]
* New: [cfdb-html] new option: "stripbr"
* New: Raw submit_time is available for shortcodes as a field name
* Improvement: Removed unnecessary quotes in Short Code builder page for "enc" value in URL generated by [cfdb-export-link]
* Improvement: On uninstall, table in DB is no longer dropped by default
* New: When accessing a file link, it will it can now display in the browser instead of forcing a download. Mime-type issues resolves.

= 2.0.1 =
* Bug Fix: where [cfdb-count] always gave total, ignoring filters
* New: Added 'percent' function to [cfdb-count]

= 2.0 =
* New: Data editing support in conjunction with Contact Form to Database Edit plugin
* Bug Fix: Name of table that stores data was down-cased from wp_CF7DBPlugin_SUBMITS to wp_cf7dbplugin_submits to avoid issues
 described in http://wordpress.org/support/topic/cf7-and-cfdb-not-saving-submissions

= 1.8.8 =
* Bug Fix: when using "filter" operators < and > where not working in cases where they were represented &amp;gt; and &amp;lt; HTML codes
* Bug Fix: "orderby" in shortcode was ignoring if ordered by "Submitted" or "submit_time"
* Improvement: Filter operations now try to do numeric comparisons when possible
* New: Can now use "submit_time" field in "filter" to filter on the timestamp float.
* Improvement: [cfdb-html] now preserves line breaks in fields by converting new lines to BR tags.
* New: CFDBFormIterator now includes a 'submit_time' field showing the raw timestamp

= 1.8.7 =
* New: [cfdb-html] now has wpautop option
* Improvement: Form input is now always run through stripslashes() regardless of whether or not get_magic_quotes_gpc is on. This is to be consistent with wp_magic_quotes always being called

= 1.8.6 =
* New: shortcode: [cfdb-export-link]
* Bug Fix: in JSON output

= 1.8.5 =
* New: Added Shortcode builder page
* New: [cf7db-count] shortcode now supports counting all forms using form="*" or a list of forms using form="form1,form2,form3"
* New: [cf7db-html] now has "filelinks" option useful for displaying image uploads.
* New: Added options to turn on/off capturing form submissions from CF7 and FSCF

= 1.8.4 =
* New: Added "cfdb_submit" hook. See http://cfdbplugin.com/?page_id=377
* New: Added delimiter option for [cfdb-value] shortcode, e.g. [cfdb-value delimiter=',']
* Bug Fix: related to [cfdb-value] when not used as a shortcode (it was not observing show & hide options)
* Improvement: Now including DataTables distribution inside this distribution so that page does not reference scripts from another site (so sites using https have everything encrypted on the page)
* Improvement: In [cfdb-html] shortcode, now removing undesired leading "br" tag and ending "p" tag that WordPress injects. This was messing up table rows (tr tags) in the shortcode because WP was injecting line breaks between the rows.

= 1.8.3 =
* Bug Fix: Minor bug fixes.

= 1.8.2 =
* Bug Fix: Minor bug fixes.
* New: Added option to not delete data on uninstall

= 1.8.1 =
* Bug Fix: Fixed bug introduced in 1.8 where deleting individual rows from the admin page did nothing.

= 1.8 =
* New: shortcodes [cfdb-html] and [cfdb-count]
* New: Shortcode option: 'limit'
* New: Shortcode option: 'orderby'
* Improvement: Performance/memory enhancements to enable plugin to handle large data volumes
* New: Now capturing form submission times with microseconds to avoid collision of two submissions during the same second
* Bug Fix: Fix to work with installations where wp-content is not at the standard location
* New: Shortcode "show" and "hide" values can now use regular expressions to identify columns
* New: Option to show database query text on Admin page

= 1.7 =
* New: Creating an export from the admin panel now filters rows based on text in the DataTable "Search" field.
* New: [cfdb-json] now has "format" option.
* Bug Fix: where "Submitted" column would sometimes appear twice in shortcodes
* New: Now can filter on "Submitted" column.
* Improvement: Admin Database page is now blank by default and you have to select a form to display.

= 1.6.5 =
* New: Now fully supports internationalization (i18n) but we need people to contribute more translation files.
* Improvement: DataTables (including those created by shortcodes) will automatically i18n based on translations available from DataTables.net
* Improvement: Italian translation courtesy of Gianni Diurno
* Improvement: Turkish translation courtesy of Oya Simpson
* Improvement: Admin page DataTable: removed horizontal scrolling because headers do not scroll with columns properly
* Update: Updated license to GPL3 from GPL2

= 1.6.4 =
* Bug Fix: Fixed bug causing FireFox to not display DataTables correctly.

= 1.6.3 =
* Bug Fix: Handling problem where user is unable to export from Admin page because jQuery fails to be loaded.

= 1.6.2 =
* Bug Fix: avoiding inclusion of DataTables CSS in global admin because of style conflicts & efficiency

= 1.6.1 =
* Bug Fix: in CSV Exports where Submitted time format had a comma in it, the comma was being interpreted as a
field delimiter.
* Improvement: Accounting for local timezone offset in display of dates

= 1.6 =
* Improvement: Admin page for viewing data is now sortable and filterable
* New: shortcode: [cfdb-datatable] to putting sortable & filterable tables on posts and pages.
    This incorporates http://www.datatables.net
* New: Option for display of localized date-time format for Submitted field based on WP site configuration in
"Database Options" -> "Use Custom Date-Time Display Format"
* New: Option to save Cookie data along with the form data. "Field names" of cookies will be "Cookie <cookie-name>"
See "Database Options" -> "Save Cookie Data with Form Submissions" and "Save only cookies in DB named"

= 1.5 =
* New: Now works with Fast Secure Contact Form (FSCF)
* New: shortcode `[cfdb-value]`
* New: shortcode `[cfdb-json]`
* Update: Renamed shortcode `[cf7db-table]` to `[cfdb-table]` (dropped the "7") but old one still works.
* New: Added option to set roles that can see data when using `[cfdb-table]` shortcode
* New: Can now specify per-column CSS for `[cfdb-table]` shortcode table (see FAQ)
* Bug Fix: with `[cfdb-table]` shortcode where the table aways appeared at the top of a post instead of embedded with the rest of the post text.

= 1.4.5 =
* Improvement: Added a PHP version check. This Plugin Requires PHP5 or later. Often default configurations are PHP4. Now a more informative error is given when the user tries to activate the plugin with PHP4.

= 1.4.4 =
* New: If user is logged in when submitting a form, 'Submitted Login' is captured
* New: `[cfdb-table]` shortcode options for filtering rows including using user variables (see FAQ)
* New: `[cfdb-table]` shortcode options for CSS
* New: Can exclude forms from being saved to DB by name

= 1.4.2 =
* New: Added `[cf7db-table]` shortcode to incorporate form data on regular posts and pages. Use `[cf7db-table form="your-form"]` with optional "show" and "hide: [cf7db-table form="your-form" show="field1,field2,field3"] (optionally show selected fields), [cf7db-table form="your-form" hide="field1,field2,field3"] (optionally hide selected fields)

= 1.4 =
* New: Added export to Google spreadsheet
* New: Now saves files uploaded via a CF7 form. When defining a file upload in CF7, be sure to set a file size limit. Example: [file upload limit:10mb]
* New: Made date-time format configurable.
* New: Can specify field names to be excluded from being saved to the DB.
* Improvement: In Database page, the order of columns in the table follows the order of fields from the last form submitted.

= 1.3 =
* New: Added export to Excel Internet Query
* Improvement: "Submitted" now shows time with timezone instead of just the date.
* Improvement: The height of cells in the data display are limited to avoid really tall rows. Overflow cells will get a scroll bar.
* Improvement: Protection against HTML-injection
* New: Option to show line breaks in multi-line form submissions
* Improvement: Added POT file for i18n

= 1.2.1 =
* New: Option for UTF-8 or UTF-16LE export. UTF-16LE works better for MS Excel for some people but does it not preserve line breaks inside a form entry.

= 1.2 =
* Improvement: Admin menu now appears under CF7's "Contact" top level menu
* New: Includes an Options page to configure who can see and delete submitted data in the database
* Improvement: Saves data in DB table as UTF-8 to support non-latin character encodings.
* Improvement: CSV Export now in a more Excel-friendly encoding so it can properly display characters from different languages

= 1.1 =
* New: Added Export to CSV file
* New: Now can delete a row

= 1.0 =
* Initial Revision.

== Upgrade Notice ==

= 2.9.2 =
For users of the CFDB Editor, CFDB 2.9.x will require and upgrade of the CFDB Editor 1.4 as well. See admin notice after upgrade of CFDB.

= 2.9.1 =
For users of the CFDB Editor, CFDB 2.9.x will require and upgrade of the CFDB Editor 1.4 as well. See admin notice after upgrade of CFDB.

= 2.9 =
For users of the CFDB Editor, CFDB 2.9 will require and upgrade of the CFDB Editor 1.4 as well. See admin notice after upgrade of CFDB.

= 1.6 =
New cool DataTable

= 1.5 =
Now works with <a href="http://wordpress.org/extend/plugins/si-contact-form/">Fast Secure Contact Form</a>. Plus more and better shortcodes.
