<?php

/**
* Manage Vimeo's videos
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class WP_VIDEO_GALLERY_VimeoManager
{
	public function register_video($value, $source = 'vimeo') {
		global $wpdb;
		$table_name = $wpdb->prefix . "wp_video_gallery";
		$length = count($value['pictures']["sizes"]);
		$thumb_small = $value['pictures']["sizes"][$length - 3]['link'];
		$thumb_medium = $value['pictures']["sizes"][$length - 2]['link'];
		$thumb_big = $value['pictures']["sizes"][$length - 1]['link'];
		$tags = serialize($value['tags']);
		$uri = explode(':', $value['uri']);
		$wpdb->insert(
			$table_name,
			array(
				'name'          => $value["name"],
				'description'   => $value["description"],
				'date_created'  => $value["created_time"],
				'date_modified' => $value["modified_time"],
				'tags'          => $tags,
				'thumb_small'   => $thumb_small,
				'thumb_medium'  => $thumb_medium,
				'thumb_big'     => $thumb_big,
				'embed'         => $value['embed']["html"],
				'uri'           => $uri[0],
				'source'        => $source,
				'duration'      => $value['duration']
			)
		);
	}

	public function register_public_video($hash) {
		global $wpdb;
		$table_name = $wpdb->prefix . "wp_video_gallery";
		$thumb_small = $hash["thumbnail_small"];
		$thumb_medium = $hash["thumbnail_medium"];
		$thumb_big = $hash["thumbnail_large"];

		$tags = (array)null;
		$a = explode(', ', $hash['tags']);
		foreach ($a as $key => $value) {
			$tag = (array)null;
			$tag['name'] = $value;
			$tags[] = $tag;
		}

		$wpdb->insert(
			$table_name,
			array(
				'name'          => $hash["title"],
				'description'   => $hash["description"],
				'date_created'  => $hash["upload_date"],
				'date_modified' => $hash["upload_date"],
				'tags'          => serialize($tags),
				'thumb_small'   => $thumb_small,
				'thumb_medium'  => $thumb_medium,
				'thumb_big'     => $thumb_big,
				'embed'         => '',
				'uri'           => "/videos/" . $hash['id'],
				'source'        => 'vimeo_single',
				'duration'      => $hash['duration']
			)
		);
	}

	public function refresh_public_video($id, $hash) {
		global $wpdb;
		$table_name = $wpdb->prefix . "wp_video_gallery";
		$thumb_small = $hash["thumbnail_small"];
		$thumb_medium = $hash["thumbnail_medium"];
		$thumb_big = $hash["thumbnail_large"];

		$tags = (array)null;
		$a = explode(', ', $hash['tags']);
		foreach ($a as $key => $value) {
			$tag = (array)null;
			$tag['name'] = $value;
			$tags[] = $tag;
		}

		$wpdb->update(
			$table_name,
			array(
				'name'          => $hash["title"],
				'description'   => $hash["description"],
				'date_created'  => $hash["upload_date"],
				'date_modified' => $hash["upload_date"],
				'tags'          => serialize($tags),
				'thumb_small'   => $thumb_small,
				'thumb_medium'  => $thumb_medium,
				'thumb_big'     => $thumb_big,
				'embed'         => '',
				'uri'           => "/videos/" . $hash['id'],
				'source'        => 'vimeo_single',
				'duration'      => $hash['duration']
			),
			array(
				"id" => $id
			)
		);
	}


	public function register_public_video_youtube($hash) {
		global $wpdb;
		$table_name = $wpdb->prefix . "wp_video_gallery";
		$hash["thumbnail_url"] = str_replace('/default.jpg', '/hqdefault.jpg', $hash["thumbnail_url"]);
		$thumb_small = $hash["thumbnail_url"];
		$thumb_medium = $hash["thumbnail_url"];
		$thumb_big = $hash["thumbnail_url"];

		$tags = (array)null;
		$r = json_decode($hash["player_response"]);
		$a = $r->videoDetails->keywords;
		foreach ($a as $key => $value) {
			$tag = (array)null;
			$tag['name'] = $value;
			$tags[] = $tag;
		}

		$wpdb->insert(
			$table_name,
			array(
				'name'          => $hash["title"],
				'description'   => "",
				'date_created'  => "",
				'date_modified' => "",
				'tags'          => serialize($tags),
				'thumb_small'   => $thumb_small,
				'thumb_medium'  => $thumb_medium,
				'thumb_big'     => $thumb_big,
				'embed'         => '',
				'uri'           => "/videos/" . $hash['video_id'],
				'source'        => 'youtube',
				'duration'      => $hash['length_seconds']
			)
		);
	}

	public function update_public_video_youtube($hash) {
		global $wpdb;
		$table_name = $wpdb->prefix . "wp_video_gallery";
		$hash["thumbnail_url"] = str_replace('/default.jpg', '/hqdefault.jpg', $hash["thumbnail_url"]);
		$thumb_small = $hash["thumbnail_url"];
		$thumb_medium = $hash["thumbnail_url"];
		$thumb_big = $hash["thumbnail_url"];

		$tags = (array)null;
		$r = json_decode($hash["player_response"]);
		$a = $r->videoDetails->keywords;
		foreach ($a as $key => $value) {
			$tag = (array)null;
			$tag['name'] = $value;
			$tags[] = $tag;
		}

		$wpdb->update(
			$table_name,
			array(
				'name'          => $hash["title"],
				'description'   => "",
				'date_created'  => "",
				'date_modified' => "",
				'tags'          => serialize($tags),
				'thumb_small'   => $thumb_small,
				'thumb_medium'  => $thumb_medium,
				'thumb_big'     => $thumb_big,
				'embed'         => '',
				'source'        => 'youtube',
				'duration'      => $hash['length_seconds']
			),
			array(
				'uri' => "/videos/" . $hash['video_id']
			)
		);
	}

	public function update_video($value, $source = 'vimeo') {
		global $wpdb;
		$table_name = $wpdb->prefix . "wp_video_gallery";
		$tags = serialize($value['tags']);
		$length = count($value['pictures']["sizes"]);
		$thumb_small = $value['pictures']["sizes"][$length - 3]['link'];
		$thumb_medium = $value['pictures']["sizes"][$length - 2]['link'];
		$thumb_big = $value['pictures']["sizes"][$length - 1]['link'];
		$uri = explode(':', $value['uri']);
		$wpdb->update(
			$table_name,
			array(
				'name'          => $value["name"],
				'description'   => $value["description"],
				'date_created'  => $value["created_time"],
				'date_modified' => $value["modified_time"],
				'tags'          => $tags,
				'thumb_small'   => $thumb_small,
				'thumb_medium'  => $thumb_medium,
				'thumb_big'     => $thumb_big,
				'embed'         => $value['embed']["html"],
				'source'        => $source,
				'duration'      => $value['duration']
			),
			array(
				'uri' => $uri[0]
			)
		);
	}

	public function update_tags($value) {
		global $wpdb;
		$table_name = $wpdb->prefix . "wp_video_gallery_tags";
		$wpdb->delete($table_name, array('video_uri' => $value["uri"]));
		foreach ($value['tags'] as $key => $v) {
			$wpdb->insert(
				$table_name,
				array(
					'tag'       => $v["name"],
					'video_uri' => $value["uri"]
				)
			);
		}
	}

	public function reset_tags() {
		global $wpdb;
		$table_name = $wpdb->prefix . "wp_video_gallery_tags";
		$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."wp_video_gallery", ARRAY_A);
		foreach ($results as $value) {
			$wpdb->delete($table_name, array('video_uri' => $value["uri"]));
			$value['tags'] = unserialize($value['tags']);
			foreach ($value['tags'] as $key => $v) {
				$wpdb->insert(
					$table_name,
					array(
						'tag'       => $v["name"],
						'video_uri' => $value["uri"]
					)
				);
			}
		}
	}

	public function get_registred_single_videos() {
		global $wpdb;
		return $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."wp_video_gallery WHERE source = 'vimeo_single'", ARRAY_A);
	}

	public function get_registred_youtube_videos() {
		global $wpdb;
		return $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."wp_video_gallery WHERE source = 'youtube'", ARRAY_A);
	}

	public function get_account_videos() {
		global $wpdb;
		return $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."wp_video_gallery WHERE source = 'vimeo'", ARRAY_A);
	}

	public function add_single_video($url) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'wp_video_gallery';
		if (preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/", $url, $output_array)) {
	    	$id = $output_array[5];
	    	$r = $wpdb->get_results('SELECT * FROM ' . $table_name . ' WHERE uri="/videos/' . $id . '"', ARRAY_A);
	    	if (count($r))
				return 2;
			else {
				$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$id.php"));
				if (!$hash)
					return 0;
				$hash = $hash[0];
				WP_VIDEO_GALLERY_VimeoManager::register_public_video($hash);
				return 1;
			}
		}
		else
			return 0;
	}

	public function refresh_single_video($url) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'wp_video_gallery';
		if (preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/", $url, $output_array)) {
	    	$id = $output_array[5];
	    	$r = $wpdb->get_results('SELECT * FROM ' . $table_name . ' WHERE uri="/videos/' . $id . '"', ARRAY_A);
	    	if (count($r)) {
				$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$id.php"));
				if (!$hash)
					return 0;
				$hash = $hash[0];
				WP_VIDEO_GALLERY_VimeoManager::refresh_public_video($r[0]["id"], $hash);
				return 1;
	    	}
	    	else
	    		return 0;
		}
		else
			return 0;
	}

	public function refresh_single_video_youtube($url) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'wp_video_gallery';
		$hash = get_youtube_public_data($url);
		if (is_null($hash))
			return 0;
    	$id = $hash['v'];
    	$r = $wpdb->get_results('SELECT * FROM ' . $table_name . ' WHERE uri="/videos/' . $id . '"', ARRAY_A);
    	if (count($r))
			WP_VIDEO_GALLERY_VimeoManager::update_public_video_youtube($hash);
		else {
			WP_VIDEO_GALLERY_VimeoManager::register_public_video_youtube($hash);
			return 1;
		}
	}

	public function add_single_video_youtube($url) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'wp_video_gallery';
		$hash = get_youtube_public_data($url);
		if (is_null($hash))
			return 0;
    	$id = $hash['v'];
    	$r = $wpdb->get_results('SELECT * FROM ' . $table_name . ' WHERE uri="/videos/' . $id . '"', ARRAY_A);
    	if (count($r))
			return 2;
		else {
			WP_VIDEO_GALLERY_VimeoManager::register_public_video_youtube($hash);
			return 1;
		}
	}

	public function remove_single_video($uri) {
		global $wpdb;
		$wpdb->delete($wpdb->prefix . "wp_video_gallery", array( 'uri' => $_POST['uri'] ) );
		$wpdb->delete($wpdb->prefix . "wp_video_gallery_grids_videos", array( 'video_uri' => $_POST['uri'] ) );
		WP_VIDEO_GALLERY_VimeoManager::reset_tags();
	}
}