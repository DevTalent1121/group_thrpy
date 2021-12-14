<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
global $wpdb;

$plugin_tables = (array)NULL;
$plugin_tables[] = $wpdb->prefix . 'wp_video_gallery';
$plugin_tables[] = $wpdb->prefix . 'wp_video_gallery_grids';
$plugin_tables[] = $wpdb->prefix . 'wp_video_gallery_grids_videos';
$plugin_tables[] = $wpdb->prefix . 'wp_video_gallery_params';
$plugin_tables[] = $wpdb->prefix . 'wp_video_gallery_tags';

foreach ($plugin_tables as $table_name) {
	$sql = "DROP TABLE IF EXISTS $table_name;";
	$e = $wpdb->query($sql);
}