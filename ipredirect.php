<?php
/**
 * Plugin Name:  IPredirect
 * Plugin URI:   https://github.com/Abdul-collab/IPredirect-Wordpress-Plugin
 * Description:  A Wordpress Plugin to Redirect the User's by their Ip addresss
 * Author:       Abdul Rahiman Jakati
 * Author URI:   linkedin.com/in/abdulrahiman-jakati-3750611b8/
 * License:      GPL-2.0-or-later
 * License URI:  license.txt
 * Text Domain:  ip-redirect
 * Version:      1.2.0
 * Requires PHP at least: 5.0
 *
 * @copyright 2024 
 * @license   GPL-2.0-or-later https://spdx.org/licenses/GPL-2.0-or-later.html
 * @link      https://github.com/Abdul-collab/IPredirect-Wordpress-Plugin
 *
 * phpcs:disable Modernize.FunctionCalls.Dirname.FileConstant
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('INC_PATH', plugin_dir_path(__FILE__));
define('INC_URL', plugin_dir_url(__FILE__));

// Include necessary files
include INC_PATH . 'includes/class-plugin.php';
require_once INC_PATH . 'includes/class-db-class.php';


// Initialize the plugin
function init_obj() {
    $new = new Ipmanager();
    $new->run();
    $new->menu();
}
add_action('plugins_loaded', 'init_obj');

function my_plugin_activate() {
    $db_handler = new DB_Handler();
    $db_handler->create_tables();

}
register_activation_hook(__FILE__, 'my_plugin_activate');
