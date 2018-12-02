<?php
if ($_POST[ 'import_submit' ]) {
    $formName = $_POST[ 'import_form_name' ];
    if ($formName) {
        $referrer = $_SERVER[ 'HTTP_REFERER' ];
        $cfdbImport = new CF7DBPlugin();
        $data = $cfdbImport->parseCsv($_FILES[ "import_cntl" ][ "tmp_name" ], $formName);
        $cfdbImport->saveDbRows($data);
    } else {
        echo '<script>alert("Form name is missing");</script>';
    }
}