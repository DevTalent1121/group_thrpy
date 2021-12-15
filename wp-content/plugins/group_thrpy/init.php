<?php
/*
Plugin Name: Group Thrpy
Description: This plugin is developed for Group Thrpy in order to manage video projects.
Version: 1
Author: Soumya 
Author URI: dcmanagertech1121@gmail.com
*/
// function to create the DB / Options / Defaults					
function ss_options_install() {

    global $wpdb;

    $table_name = $wpdb->prefix . "projects";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (  
		`id` INT NOT NULL AUTO_INCREMENT,
		`title` TINYTEXT,
		`short_title` TINYTEXT,
		`description` TINYTEXT,
		`video_url` TINYTEXT,
		`cd` TINYTEXT,
		`director` TINYTEXT,
		`executive_producers` TINYTEXT,
		`produced_by` TINYTEXT,
		`producer` TINYTEXT,
		`editor` TINYTEXT,
		`director_of_photography` TINYTEXT,
		`sound_design` TINYTEXT,
		`sound_mix_mastering` TINYTEXT,
		`makeup` TINYTEXT,
		`styling` TINYTEXT,
		`hair` TINYTEXT,
		`manicurist` TINYTEXT,
		`VFX_supervisor` TINYTEXT,
		PRIMARY KEY (`id`)
	  ) CHARSET=utf8 COLLATE=utf8_unicode_ci;
	   ";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

// run the install scripts upon plugin activation
register_activation_hook(__FILE__, 'ss_options_install');

//menu items
add_action('admin_menu','group_thrpy_modifymenu');
function group_thrpy_modifymenu() {
	
	//this is the main item for the menu
	add_menu_page('Group Thrpy Videos', //page title
	'Group Thrpy Works', //menu title
	'manage_options', //capabilities
	'group_thrpy_list', //menu slug
	'group_thrpy_list' //function
	);
	
	//this is a submenu
	add_submenu_page('group_thrpy_list', //parent slug
	'Add New Works', //page title
	'Add New', //menu title
	'manage_options', //capability
	'group_thrpy_create', //menu slug
	'group_thrpy_create'); //function
	
	//this submenu is HIDDEN, however, we need to add it anyways
	add_submenu_page(null, //parent slug
	'Update Works', //page title
	'Update', //menu title
	'manage_options', //capability
	'group_thrpy_update', //menu slug
	'group_thrpy_update'); //function
}
define('ROOTDIR', plugin_dir_path(__FILE__));
require_once(ROOTDIR . 'works-list.php');
require_once(ROOTDIR . 'works-create.php');
require_once(ROOTDIR . 'works-update.php');
