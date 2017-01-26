This directory is for the i18n translations for the DataTable widget that is used by this plugin.
Most translations herein were take from DataTables.net: http://www.datatables.net/plug-ins/i18n

To create a file for a language, give it a name following the format:
        ll.json

And to create a file specific to a language and country, give it a name following the format:
        ll_CC.json

Or use whatever the locale string in for your system:
        locale.json

Where:
    "ll"  is an ISO 639 two- or three-letter language code
            http://www.gnu.org/software/autoconf/manual/gettext/Language-Codes.html#Language-Codes

    "CC" is an ISO 3166 two-letter country code
            http://www.gnu.org/software/autoconf/manual/gettext/Country-Codes.html#Country-Codes

    locale is your locale string, which is typically in the form "ll_CC", but is whatever
            is returned from WordPress function get_locale()

** If you create a new translation file, please post a copy to the author of this plugin
   so he can share it with others: http://cfdbplugin.com/?page_id=62
   Also, send it to a contact at DataTable.net.
