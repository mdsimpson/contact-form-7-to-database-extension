<?php

class WPDB_Mock {
    var $prefix = 'wp_';

    var $getResultReturnVal;

    public function get_results($sql) {
        return $this->getResultReturnVal;
    }
} 