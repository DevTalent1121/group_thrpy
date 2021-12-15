<?php
/*
Plugin Name: works
Description:
Version: 1
Author: sinetiks.com
Author URI: http://sinetiks.com
*/
// function to create the DB / Options / Defaults					
function ss_options_install() {

    global $wpdb;

    $table_name = $wpdb->prefix . "projects";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
            `id` varchar(3) CHARACTER SET utf8 NOT NULL,
            `name` varchar(50) CHARACTER SET utf8 NOT NULL,
            PRIMARY KEY (`id`)
          ) $charset_collate; ";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

// run the install scripts upon plugin activation
register_activation_hook(__FILE__, 'ss_options_install');

//menu items
add_action('admin_menu','group_thrpy_modifymenu');
function group_thrpy_modifymenu() {
	
	//this is the main item for the menu
	add_menu_page('works', //page title
	'works', //menu title
	'manage_options', //capabilities
	'group_thrpy_list', //menu slug
	'group_thrpy_list' //function
	);
	
	//this is a submenu
	add_submenu_page('group_thrpy_list', //parent slug
	'Add New work', //page title
	'Add New', //menu title
	'manage_options', //capability
	'group_thrpy_create', //menu slug
	'group_thrpy_create'); //function
	
	//this submenu is HIDDEN, however, we need to add it anyways
	add_submenu_page(null, //parent slug
	'Update work', //page title
	'Update', //menu title
	'manage_options', //capability
	'group_thrpy_update', //menu slug
	'group_thrpy_update'); //function
}
define('ROOTDIR', plugin_dir_path(__FILE__));
require_once(ROOTDIR . 'works-list.php');
require_once(ROOTDIR . 'works-create.php');
require_once(ROOTDIR . 'works-update.php');
