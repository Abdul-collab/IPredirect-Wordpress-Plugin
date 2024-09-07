<?php
# Uninstall script

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

global $wpdb;
$table_name = $wpdb->prefix . 'ipd_settings';
$wpdb->query("DROP TABLE IF EXISTS $table_name");
