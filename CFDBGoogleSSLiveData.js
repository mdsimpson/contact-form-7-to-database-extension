/*
 "Contact Form to Database" Copyright (C) 2011-2014 Michael Simpson  (email : michael.d.simpson@gmail.com)

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

/* This is a script to be used with a Google Spreadsheet to make it dynamically load data (similar to Excel IQuery)
 Instructions:
1. Create a new Google Spreadsheet
2. Go to Tools menu -> Script Editor...
3. Click Spreadsheet
4. Copy the text from this file and paste it into the Google script editor.
5. Save and close the script editor.
6. Click on a cell A1 in the Spreadsheet (or any cell)
7. Enter in the cell the formula:
   =cfdbdata("site_url", "form_name", "user", "password")
  Where the parameters are (be sure to quote them):
    site_url: the URL of you site, e.g. "http://www.mywordpress.com"
    form_name: name of the form
    user: your login name on your WordPress site
    pwd: password
*/

/**
 * Use this function in your spreadsheet to fetch saved form data from your WordPress Site
 * @param site_url your top level WordPress site URL
 * @param form_name name of the WordPress form to fetch data from
 * @param user login name to your WordPress site. User must have permission to view form data
 * @param password WordPress site password. If your site_url is "http" and not "https" then
 * beware that your password is being sent unencrypted from a Google server to your WordPress server.
 * Also beware that others who can view this code in your Google Spreadsheet can see this password.
 * @param "option_name1", "option_value1", "option_name2", "option_value2", ... (optional param pairs).
 * These are CFDB option such as "filter", "name=Smith", "show", "first_name,last_name"
 * These should come in pairs.
 * @returns {*} Your WordPress saved form data in a format suitable for Google Spreadsheet to display.
 * String error message if there is an error logging into the WordPress site
 */
function cfdbdata(site_url, form_name, user, password /*, [option_name, option_value] ... */) {
    var param_array = [];
    param_array.push("action=cfdb-login");
    param_array.push("username=" + encodeURI(user));
    param_array.push("password=" + encodeURI(password));
    param_array.push("cfdb-action=cfdb-export");
    param_array.push("enc=JSON");
    param_array.push("format=array");
    param_array.push("form=" + encodeURI(form_name));

    var args = arg_slice(arguments, 4);
    args = process_name_value_args(args);
    param_array = param_array.concat(args);

    return fetch_json_url(site_url, param_array);
}

function fetch_json_url(site_url, param_array) {
    var url = site_url + "/wp-admin/admin-ajax.php";
    var payload = param_array.join("&");
    var response = UrlFetchApp.fetch(url, { method: "post", payload: payload });
    var content = response.getContentText();
    if (content.indexOf("<strong>ERROR") == 0) {
        // If error message is returned, just return that as the content
        return content;
    }
    //Logger.log(content); // For Debugging
    return JSON.parse(content);
}

/**
 * @deprecated for backward compatibility. Use cfdbdata() instead.
 */
function CF7ToDBData(site_url, form_name, search, user, password) {
    if (search != "") {
        return cfdbdata(site_url, form_name, user, password, "search", search);
    }
    return cfdbdata(site_url, form_name, user, password);
}

/**
 * "slice" function for varargs Argument object
 * @param args Argument object
 * @param position int > 0 indicating the slice position
 * @returns {Array} of args from the slide index to the end.
 * Returns empty array if slice position exceeds length of args
 */
function arg_slice(args, position) {
    var array = [];
    if (args.length > position) {
        for (var i = position; i < args.length; i++) {
            array.push(args[i]);
        }
    }
    return array;
}

/**
 * Converts array like ['a', '1', 'b', '2'] to ['a=1', 'b=2']
 * where each value is made to be URI-encoded.
 * Purpose of this is to transform and array of name,value arguments
 * into HTTP GET/POST parameters
 * @param array Array like ['a', '1', 'b', '2']
 * @returns {Array} like ['a=1', 'b=2'].
 * where each value (a, 1, b, 2) are URL-Encoded
 * If there is an odd number of arguments then the last one is dropped
 * (expecting pairs of name,value)
 */
function process_name_value_args(array) {
    var name_value_array = [];
    var flag = true;
    var name = null;
    for (var i = 0; i < array.length; i++) {
        if (flag) {
            name = array[i];
        } else {
            name_value_array.push(encodeURI(name) + "=" + encodeURI(array[i]));
        }
        flag = !flag;
    }
    return name_value_array;
}
