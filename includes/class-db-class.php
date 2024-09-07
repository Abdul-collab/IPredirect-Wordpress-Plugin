<?php

class DB_Handler {

    public function create_tables() {
        global $wpdb;

        $ipd_settings = $wpdb->prefix . 'ipd_settings';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $ipd_settings (
            id mediumint(5) NOT NULL AUTO_INCREMENT,
            cc varchar(3) NOT NULL,
            pp int(5) NOT NULL,
            readlink varchar(550) NOT NULL,
            PRIMARY KEY  (id)
    ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
