<?php

class Ipmanager {
    protected $db_handler;

    public function __construct() {
        $this->db_handler = new DB_Handler();
    }

    public function run() {
        // Initialize database tables
        register_activation_hook(__FILE__, array($this->db_handler, 'create_tables'));
    }

    public function menu(){
        add_action( 'admin_menu' , array( $this, 'memo') );
    }

    public function memo() {
        add_menu_page('IPredirect','IP-redirect','manage_options','ip-redirect',array( $this, 'task'),'dashicons-editor-unlink',30098);

    }

    public function task() {
        include INC_PATH . 'templates/index.php';

    }
}
