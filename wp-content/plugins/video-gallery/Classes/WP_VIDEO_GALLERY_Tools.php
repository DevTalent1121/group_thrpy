<?php

/**
* Helpers class
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class WP_VIDEO_GALLERY_Tools {
	
	public function get_grid_search_query($gridId) {
		$grid = wp_video_gallery_get_grid($gridId);
		$gridConf = unserialize($grid['configuration']);
		return $gridConf['search_query'];
	}

	public function clearData() {
		global $wpdb;
		$plugin_tables = (array)NULL;
		$plugin_tables[] = $wpdb->prefix . 'wp_video_gallery';
		$plugin_tables[] = $wpdb->prefix . 'wp_video_gallery_grids';
		$plugin_tables[] = $wpdb->prefix . 'wp_video_gallery_grids_videos';
		$plugin_tables[] = $wpdb->prefix . 'wp_video_gallery_tags';

		foreach ($plugin_tables as $table_name) {
			$sql = "TRUNCATE TABLE $table_name;";
			$e = $wpdb->query($sql);
		}
		$conf = wp_video_gallery_get_user_conf();
		$conf = unserialize($conf->value);
		$conf['vimeo']['token'] = '';
		wp_video_gallery_set_user_conf($conf);
		$table_name = $wpdb->prefix . 'wp_video_gallery_params';
		$wpdb->update(
			$table_name,
			array(
				'value' => '1990-10-24T10:06:58+00:00'
			),
			array(
				'param' => 'last_refresh_date'
			)
		);
	}

	function clearPrivateVimeoVideos() {
		global $wpdb;
		$table = $wpdb->prefix . 'wp_video_gallery';
		$vids = $wpdb->get_results('SELECT * FROM ' . $table . " WHERE source='vimeo'");
		$table = $wpdb->prefix . 'wp_video_gallery_grids_videos';
		if (count($vids)) {
			foreach ($vids as $key => $value) {
				$wpdb->delete(
					$table,
					array(
						'video_uri' => $value->uri
					)
				);		
			}
		}
		$table = $wpdb->prefix . 'wp_video_gallery';
		$wpdb->delete(
			$table,
			array(
				'source' => 'vimeo'
			)
		);
		$conf = wp_video_gallery_get_user_conf();
		$conf = unserialize($conf->value);
		$conf['vimeo']['token'] = '';
		wp_video_gallery_set_user_conf($conf);
		$table_name = $wpdb->prefix . 'wp_video_gallery_params';
		$wpdb->update(
			$table_name,
			array(
				'value' => '1990-10-24T10:06:58+00:00'
			),
			array(
				'param' => 'last_refresh_date'
			)
		);
	}

	function clearYoutubeVideos() {
		global $wpdb;
		$table = $wpdb->prefix . 'wp_video_gallery';
		$vids = $wpdb->get_results('SELECT * FROM ' . $table . " WHERE source='youtube'");
		$table = $wpdb->prefix . 'wp_video_gallery_grids_videos';
		if (count($vids)) {
			foreach ($vids as $key => $value) {
				$wpdb->delete(
					$table,
					array(
						'video_uri' => $value->uri
					)
				);		
			}
		}
		$table = $wpdb->prefix . 'wp_video_gallery';
		$wpdb->delete(
			$table,
			array(
				'source' => 'youtube'
			)
		);
		$conf = wp_video_gallery_get_user_conf();
		$conf = unserialize($conf->value);
		$conf['youtube']['token'] = '';
		wp_video_gallery_set_user_conf($conf);
	}

	public function getUserName() {
		$lib = wp_video_gallery_connect();
		$response = $lib->request('/me', 'GET');
		return $response['body']['name'];
	}

	public function catalogContainsVideos() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'wp_video_gallery';
		$res = $wpdb->get_results("SELECT uri FROM " . $table_name, ARRAY_A);
		if (count($res))
			return 1;
		return 0;
	}

	public function vimeoAccountConnected() {
		$con = wp_video_gallery_get_user_conf();
		$con = unserialize($con->value);
		$token = $con['vimeo']['token'];
		if (strlen($token))
			return 1;
		return 0;
	}
}