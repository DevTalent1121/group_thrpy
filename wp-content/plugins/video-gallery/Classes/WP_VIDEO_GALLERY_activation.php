<?php

/**
* Manage actions when plugin is activated
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class WP_VIDEO_GALLERY_Activation
{
	public function onActivation() {
		global $wpdb;
		$table_name = $wpdb->prefix . "wp_video_gallery";
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		    $sql = "CREATE TABLE $table_name (
		      id int(11) NOT NULL AUTO_INCREMENT,
		      uri varchar(255) NOT NULL,
		      name varchar(255) DEFAULT NULL,
		      description text DEFAULT NULL,
		      duration int(11) DEFAULT 0,
		      thumb_small varchar(255) DEFAULT NULL,
		      thumb_medium varchar(255) DEFAULT NULL,
		      thumb_big varchar(255) DEFAULT NULL,
		      tags text DEFAULT NULL,
		      embed text DEFAULT NULL,
		      date_created text DEFAULT NULL,
		      date_modified text DEFAULT NULL,
		      source varchar(255) DEFAULT NULL,
		      UNIQUE KEY id (id)
		    );";
		    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		    dbDelta( $sql );
		}
		$table_name = $wpdb->prefix . 'wp_video_gallery_params';
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		    $sql = "CREATE TABLE $table_name (
		      id int(11) NOT NULL AUTO_INCREMENT,
		      param varchar(255) NOT NULL,
		      value text DEFAULT NULL,
		      UNIQUE KEY id (id)
		    );";
		    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		    dbDelta( $sql );
		}
		$table_name = $wpdb->prefix . 'wp_video_gallery_tags';
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		    $sql = "CREATE TABLE $table_name (
		      id int(11) NOT NULL AUTO_INCREMENT,
		      tag varchar(255) NOT NULL,
		      video_uri varchar(255) DEFAULT NULL,
		      UNIQUE KEY id (id)
		    );";
		    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		    dbDelta( $sql );
		}
		$table_name = $wpdb->prefix . 'wp_video_gallery_grids';
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		    $sql = "CREATE TABLE $table_name (
		      id int(11) NOT NULL AUTO_INCREMENT,
		      name varchar(255) NOT NULL,
		      description varchar(255) DEFAULT NULL,
		      theme varchar(255) DEFAULT NULL,
		      configuration text DEFAULT NULL,
		      UNIQUE KEY id (id)
		    );";
		    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		    dbDelta( $sql );
		}
		$table_name = $wpdb->prefix . 'wp_video_gallery_grids_videos';
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		    $sql = "CREATE TABLE $table_name (
		      id int(11) NOT NULL AUTO_INCREMENT,
		      id_grid int(11) NOT NULL,
		      video_uri varchar(255) NOT NULL,
		      orders int(11) NOT NULL,
			  big int(11) NOT NULL,
		      UNIQUE KEY id (id)
		    );";
		    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		    dbDelta( $sql );
		}
		wp_video_gallery_post_stats();
	}
}