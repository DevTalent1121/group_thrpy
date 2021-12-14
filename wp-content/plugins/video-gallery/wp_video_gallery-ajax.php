<?php

// For ajax requests management
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Add a new grid
add_action('wp_ajax_wp_video_gallery_ajax_add_grid', 'wp_video_gallery_ajax_add_grid');
add_action('wp_ajax_nopriv_wp_video_gallery_ajax_add_grid', 'wp_video_gallery_ajax_add_grid');
function wp_video_gallery_ajax_add_grid() {
	global $wpdb;
	$table_name = $wpdb->prefix . "wp_video_gallery_grids";
	$wpdb->insert(
		$table_name,
		array(
			'name'        => $_POST['title'],
			'theme'       => "Mosaic",
			'configuration' => 'a:19:{s:10:"filterType";s:3:"AND";s:7:"filters";N;s:12:"search_query";s:0:"";s:9:"show_tags";i:0;s:10:"show_title";i:0;s:10:"show_pager";i:0;s:8:"duration";i:4000;s:10:"tags_color";s:7:"#a8a8a8";s:9:"box_color";s:7:"#ffffff";s:10:"text_color";s:7:"#ffffff";s:16:"text_color_hover";s:7:"#000000";s:11:"video_width";i:300;s:10:"custom_css";s:0:"";s:17:"box_color_initial";s:15:"rgba(0,0,0,0.3)";s:15:"video_spacing_x";i:40;s:15:"video_spacing_y";i:40;s:9:"animation";s:8:"fadeInUp";s:20:"enable_css_animation";i:1;s:15:"animation_delay";i:300;}'
		)
	);
	echo $wpdb->insert_id;
	die();
}

// Test Vimeo Token
add_action('wp_ajax_wp_video_gallery_ajax_test_vimeo_account', 'wp_video_gallery_ajax_test_vimeo_account');
add_action('wp_ajax_nopriv_wp_video_gallery_ajax_test_vimeo_account', 'wp_video_gallery_ajax_test_vimeo_account');
function wp_video_gallery_ajax_test_vimeo_account() {
	$lib = new \Vimeo\Vimeo('', '');
	$lib->setToken($_POST['vimeoT']);
	$response = $lib->request('/me/videos', array('per_page' => 1, 'sort' => 'date', 'direction' => 'desc', 'page' => 1), 'GET');
	if (is_null($response['body']['data']))
		echo '0';
	else {
		$conf = wp_video_gallery_get_user_conf();
		$conf = unserialize($conf->value);
		$conf['vimeo']['token'] = $_POST['vimeoT'];
		wp_video_gallery_set_user_conf($conf);
		define("WP_VIDEO_GALLERY_VIMEO_AT", $_POST['vimeoT']);
		echo home_url() . '/wp-admin/admin.php?page=wp_video_gallery-catalog';
	}
	die();
}

// Disconnect account and clear all WP_VIDEO_GALLERY data
add_action('wp_ajax_wp_video_gallery_ajax_disconnect_clear', 'wp_video_gallery_ajax_disconnect_clear');
add_action('wp_ajax_nopriv_wp_video_gallery_ajax_disconnect_clear', 'wp_video_gallery_ajax_disconnect_clear');
function wp_video_gallery_ajax_disconnect_clear() {
	WP_VIDEO_GALLERY_Tools::clearPrivateVimeoVideos();
	echo home_url() . '/wp-admin/admin.php?page=wp_video_gallery-conf';
	die();
}

// Disconnect account and clear all WP_VIDEO_GALLERY data (Youtube)
add_action('wp_ajax_wp_video_gallery_ajax_disconnect_clear_y', 'wp_video_gallery_ajax_disconnect_clear_y');
add_action('wp_ajax_nopriv_wp_video_gallery_ajax_disconnect_clear_y', 'wp_video_gallery_ajax_disconnect_clear_y');
function wp_video_gallery_ajax_disconnect_clear_y() {
	WP_VIDEO_GALLERY_Tools::clearYoutubeVideos();
	echo home_url() . '/wp-admin/admin.php?page=wp_video_gallery-conf';
	die();
}

// Disconnect account and keep registred videos
add_action('wp_ajax_wp_video_gallery_ajax_disconnect_keep', 'wp_video_gallery_ajax_disconnect_keep');
add_action('wp_ajax_nopriv_wp_video_gallery_ajax_disconnect_keep', 'wp_video_gallery_ajax_disconnect_keep');
function wp_video_gallery_ajax_disconnect_keep() {
	global $wpdb;
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
	$table = $wpdb->prefix . 'wp_video_gallery';
	$wpdb->update(
		$table,
		array(
			'source' => 'vimeo_single'
		),
		array(
			'source' => 'vimeo'
		)
	);
	echo home_url() . '/wp-admin/admin.php?page=wp_video_gallery-conf';
	die();
}

// Disconnect account and keep registred videos - Youtube
add_action('wp_ajax_wp_video_gallery_ajax_disconnect_keep_y', 'wp_video_gallery_ajax_disconnect_keep_y');
add_action('wp_ajax_nopriv_wp_video_gallery_ajax_disconnect_keep_y', 'wp_video_gallery_ajax_disconnect_keep_y');
function wp_video_gallery_ajax_disconnect_keep_y() {
	global $wpdb;
	$conf = wp_video_gallery_get_user_conf();
	$conf = unserialize($conf->value);
	$conf['youtube']['token'] = '';
	wp_video_gallery_set_user_conf($conf);
	echo home_url() . '/wp-admin/admin.php?page=wp_video_gallery-conf';
	die();
}

// Count viedeos registred in database
add_action('wp_ajax_wp_video_gallery_ajax_get_nbr_videos_db', 'wp_video_gallery_ajax_get_nbr_videos_db');
add_action('wp_ajax_nopriv_wp_video_gallery_ajax_get_nbr_videos_db', 'wp_video_gallery_ajax_get_nbr_videos_db');
function wp_video_gallery_ajax_get_nbr_videos_db() {
	global $wpdb;
	$user_count = $wpdb->get_var( "SELECT COUNT(*) FROM ".$wpdb->prefix."wp_video_gallery" );
	echo $user_count;
	die();	
}

// Count videos in Vimeo Account
add_action('wp_ajax_wp_video_gallery_ajax_get_nbr_videos_vimeo', 'wp_video_gallery_ajax_get_nbr_videos_vimeo');
add_action('wp_ajax_nopriv_wp_video_gallery_ajax_get_nbr_videos_vimeo', 'wp_video_gallery_ajax_get_nbr_videos_vimeo');
function wp_video_gallery_ajax_get_nbr_videos_vimeo() {
	$lib = wp_video_gallery_connect();
	$response = $lib->request('/me/videos', array('per_page' => 1, 'sort' => 'date', 'direction' => 'desc', 'page' => 1), 'GET');
	$total = (int)$response["body"]["total"];
	echo $total;
	die();
}

// Refresh Videos
add_action('wp_ajax_wp_video_galleryAjaxRefreshVideos', 'wp_video_galleryAjaxRefreshVideos');
add_action('wp_ajax_nopriv_wp_video_galleryAjaxRefreshVideos', 'wp_video_galleryAjaxRefreshVideos');
function wp_video_galleryAjaxRefreshVideos() {
	global $wpdb;
	$days = get_wp_video_gallery_day();
	if ($days < 50) {
		$ttt = $wpdb->prefix . 'wp_video_gallery';
		$empty = $wpdb->get_col($wpdb->prepare("SELECT 1 FROM $ttt WHERE source=%s LIMIT 1", 'vimeo'));
		if ( count($empty) == 0 )
			wp_video_gallery_fill_database();
		else
			wp_video_gallery_refresh_database();
	}
	die();
}

// Enable/Disable Vimeo
add_action('wp_ajax_wp_video_gallery_ajax_enable_disable_vimeo', 'wp_video_gallery_ajax_enable_disable_vimeo');
add_action('wp_ajax_nopriv_wp_video_gallery_ajax_enable_disable_vimeo', 'wp_video_gallery_ajax_enable_disable_vimeo');
function wp_video_gallery_ajax_enable_disable_vimeo() {
	$conf = wp_video_gallery_get_user_conf();
	$conf = unserialize($conf->value);
	$conf['vimeo']['activation'] = (int)$_POST['mode'];
	wp_video_gallery_set_user_conf($conf);
	if ((int)$_POST['mode'])
		echo __('Disable', 'wp_video_gallery');
	else
		echo __('Enable', 'wp_video_gallery');
	die();
}

// Add single video
add_action('wp_ajax_wp_video_gallery_ajax_add_single_vimeo', 'wp_video_gallery_ajax_add_single_vimeo');
add_action('wp_ajax_nopriv_wp_video_gallery_ajax_add_single_vimeo', 'wp_video_gallery_ajax_add_single_vimeo');
function wp_video_gallery_ajax_add_single_vimeo() {
	global $wpdb;
	$res = WP_VIDEO_GALLERY_VimeoManager::add_single_video($_POST['url']);
	$videos = WP_VIDEO_GALLERY_VimeoManager::get_registred_single_videos();
	if ((int)$res == 1) {
		$html = '
			<h4 class="subtitle">' . __('Registred videos', 'wp_video_gallery') . ' :</h4>
			<div class="info">
				<span>' . count($videos) . ' ' . __('videos', 'wp_video_gallery') . '</span>
			</div>
			<div class="around">
				<table cellspacing="0">
		';
		if (count($videos)) {
			foreach ($videos as $key => $value) {
				$id = explode('/', $value['uri']);
				$id = $id[2];
				$html .= '
						<tr class="line" data-id="' . $id . '">
							<td class="thumb">
								<img src="' . $value['thumb_small'] . '">
							</td>
							<td class="title">
								' . $value['name'] . '
							</td>
							<td class="remove">
								<i class="fa fa-times removeSingleVideo" data-uri="' . $value['uri'] . '"></i>
							</td>
						</tr>
				';
			}
		}
		$html .= '
				</table>
			</div>
		';
		$cy = $wpdb->get_var("SELECT COUNT(id) FROM " . $wpdb->prefix . "wp_video_gallery WHERE source IN ('vimeo', 'vimeo_single')");
		echo $html . "####" . $cy;
	}
	elseif ((int)$res == 0)
		echo 'error';
	else
		echo 'duplicate';
	die();
}

// Add single video - Youtube
add_action('wp_ajax_wp_video_gallery_ajax_add_single_youtube', 'wp_video_gallery_ajax_add_single_youtube');
add_action('wp_ajax_nopriv_wp_video_gallery_ajax_add_single_youtube', 'wp_video_gallery_ajax_add_single_youtube');
function wp_video_gallery_ajax_add_single_youtube() {
	global $wpdb;
	$res = WP_VIDEO_GALLERY_VimeoManager::add_single_video_youtube($_POST['url']);
	$videos = WP_VIDEO_GALLERY_VimeoManager::get_registred_youtube_videos();
	if ((int)$res == 1) {
		$html = '
			<h4 class="subtitle">' . __('Registred videos', 'wp_video_gallery') . ' :</h4>
			<div class="info">
				<span>' . count($videos) . ' ' . __('videos', 'wp_video_gallery') . '</span>
			</div>
			<div class="around">
				<table cellspacing="0">
		';
		if (count($videos)) {
			foreach ($videos as $key => $value) {
				$id = explode('/', $value['uri']);
				$id = $id[2];
				$html .= '
						<tr class="line" data-id="' . $id . '">
							<td class="thumb">
								<img src="' . $value['thumb_small'] . '">
							</td>
							<td class="title">
								' . $value['name'] . '
							</td>
							<td class="remove">
								<i class="fa fa-times removeYoutubeVideo" data-uri="' . $value['uri'] . '"></i>
							</td>
						</tr>
				';
			}
		}
		$html .= '
				</table>
			</div>
		';
		$cy = $wpdb->get_var("SELECT COUNT(id) FROM " . $wpdb->prefix . "wp_video_gallery WHERE source='youtube'");
		echo $html . "####" . $cy;
	}
	elseif ((int)$res == 0)
		echo 'error';
	else
		echo 'duplicate';
	die();
}

// Remove single video from database
add_action('wp_ajax_wp_video_gallery_ajax_remove_single_vimeo', 'wp_video_gallery_ajax_remove_single_vimeo');
add_action('wp_ajax_nopriv_wp_video_gallery_ajax_remove_single_vimeo', 'wp_video_gallery_ajax_remove_single_vimeo');
function wp_video_gallery_ajax_remove_single_vimeo() {
	global $wpdb;
	WP_VIDEO_GALLERY_VimeoManager::remove_single_video($_POST['uri']);
	$videos = WP_VIDEO_GALLERY_VimeoManager::get_registred_single_videos();
	if (count($videos)) {
		$html = '
			<h4 class="subtitle">' . __('Registred videos', 'wp_video_gallery') . ' :</h4>
			<div class="info">
				<span>' . count($videos) . ' ' . __('videos', 'wp_video_gallery') . '</span>
			</div>
			<div class="around">
				<table cellspacing="0">
		';
		if (count($videos)) {
			foreach ($videos as $key => $value) {
				$id = explode('/', $value['uri']);
				$id = $id[2];
				$html .= '
						<tr class="line" data-id="' . $id . '">
							<td class="thumb">
								<img src="' . $value['thumb_small'] . '">
							</td>
							<td class="title">
								' . $value['name'] . '
							</td>
							<td class="remove">
								<i class="fa fa-times removeSingleVideo" data-uri="' . $value['uri'] . '"></i>
							</td>
						</tr>
				';
			}
		}
		$html .= '
				</table>
			</div>
		';
	}
	else {
		$html = '
			<h4 class="subtitle">' . __('Registred videos', 'wp_video_gallery') . ' :</h4>
			<p class="noRegistredVideos">' . __('You have no registred public video. Use the input above to add some.', 'wp_video_gallery') . '</p>
		';
	}
	$cy = $wpdb->get_var("SELECT COUNT(id) FROM " . $wpdb->prefix . "wp_video_gallery WHERE source IN ('vimeo', 'vimeo_single')");
	echo $html . "####" . $cy;
	die();
}

// Update single vimeo video
add_action('wp_ajax_wp_video_gallery_ajax_refresh_single_vimeo', 'wp_video_gallery_ajax_refresh_single_vimeo');
add_action('wp_ajax_nopriv_wp_video_gallery_ajax_refresh_single_vimeo', 'wp_video_gallery_ajax_refresh_single_vimeo');
function wp_video_gallery_ajax_refresh_single_vimeo() {
	global $wpdb;
	$lib = wp_video_gallery_connect();
	$response = $lib->request('/videos', array('uris' => $_POST['uri']), 'GET');
	if (!is_null($response['body']['data'])) {
		$value = $response['body']['data'][0];
		WP_VIDEO_GALLERY_VimeoManager::update_video($value);
		$uri = explode(':', $value['uri']);
		$id = explode('/', $uri[0]);
		$id = $id[2];
		$length = count($value['pictures']["sizes"]);
		$thumb_small = $value['pictures']["sizes"][$length - 3]['link'];
		echo json_encode(array(
			"status" => 1,
			"html" => '
				<tr class="line" data-id="' . $id . '">
					<td class="thumb">
						<img src="' . $thumb_small . '">
					</td>
					<td class="title">' . $value['name'] . '</td>
					<td class="refresh">
						<i class="fa fa-refresh refreshVimeoVideo" data-uri="' . $uri[0] . '"></i>
					</td>
				</tr>
			'
		));
	}
	else {
		echo json_encode(array("status" => 0));
	}
	die();
}

// Update single vimeo public video
add_action('wp_ajax_wp_video_gallery_ajax_refresh_single_vimeo_public', 'wp_video_gallery_ajax_refresh_single_vimeo_public');
add_action('wp_ajax_nopriv_wp_video_gallery_ajax_refresh_single_vimeo_public', 'wp_video_gallery_ajax_refresh_single_vimeo_public');
function wp_video_gallery_ajax_refresh_single_vimeo_public() {
	global $wpdb;
	WP_VIDEO_GALLERY_VimeoManager::refresh_single_video("https://vimeo.com" . $_POST['uri']);
	$table_name = $wpdb->prefix . 'wp_video_gallery';
	$value = $wpdb->get_row('SELECT * FROM ' . $table_name . ' WHERE uri="' . $_POST['uri'] . '"', ARRAY_A);
	if ($value) {
		$id = explode('/', $value['uri']);
		$id = $id[2];
		echo json_encode(array(
			"status" => 1,
			"html" => '
				<tr class="line" data-id="' . $id . '">
					<td class="thumb">
						<img src="' . $value['thumb_small'] . '">
					</td>
					<td class="title">' . $value['name'] . '</td>
					<td class="refresh">
						<i class="fa fa-refresh refreshVimeoVideo" data-uri="' . $value['uri'] . '"></i>
					</td>
					<td class="remove">
						<i class="fa fa-times removeSingleVideo" data-uri="' . $value['uri'] . '"></i>
					</td>
				</tr>
			'
		));
	}
	else {
		echo json_encode(array("status" => 0));
	}
	die();
}

// Remove single Youtube video from database
add_action('wp_ajax_wp_video_gallery_ajax_remove_single_youtube', 'wp_video_gallery_ajax_remove_single_youtube');
add_action('wp_ajax_nopriv_wp_video_gallery_ajax_remove_single_youtube', 'wp_video_gallery_ajax_remove_single_youtube');
function wp_video_gallery_ajax_remove_single_youtube() {
	global $wpdb;
	WP_VIDEO_GALLERY_VimeoManager::remove_single_video($_POST['uri']);
	$videos = WP_VIDEO_GALLERY_VimeoManager::get_registred_youtube_videos();
	if (count($videos)) {
		$html = '
			<h4 class="subtitle">' . __('Registred videos', 'wp_video_gallery') . ' :</h4>
			<div class="info">
				<span>' . count($videos) . ' ' . __('videos', 'wp_video_gallery') . '</span>
			</div>
			<div class="around">
				<table cellspacing="0">
		';
		if (count($videos)) {
			foreach ($videos as $key => $value) {
				$id = explode('/', $value['uri']);
				$id = $id[2];
				$html .= '
						<tr class="line" data-id="' . $id . '">
							<td class="thumb">
								<img src="' . $value['thumb_small'] . '">
							</td>
							<td class="title">
								' . $value['name'] . '
							</td>
							<td class="remove">
								<i class="fa fa-times removeYoutubeVideo" data-uri="' . $value['uri'] . '"></i>
							</td>
						</tr>
				';
			}
		}
		$html .= '
				</table>
			</div>
		';
	}
	else {
		$html = '
			<h4 class="subtitle">' . __('Registred videos', 'wp_video_gallery') . ' :</h4>
			<p class="noRegistredVideos">' . __('You have no registred youtube video. Use the input above to add some.', 'wp_video_gallery') . '</p>
		';
	}
	$cy = $wpdb->get_var("SELECT COUNT(id) FROM " . $wpdb->prefix . "wp_video_gallery WHERE source='youtube'");
	echo $html . "####" . $cy;
	die();
}

// Refresh catalog's videos
add_action('wp_ajax_wp_video_galleryAjaxRefreshVideosCatalog', 'wp_video_galleryAjaxRefreshVideosCatalog');
add_action('wp_ajax_nopriv_wp_video_galleryAjaxRefreshVideosCatalog', 'wp_video_galleryAjaxRefreshVideosCatalog');
function wp_video_galleryAjaxRefreshVideosCatalog() {
	global $wpdb;
	require_once plugin_dir_path( __FILE__ ) . 'catalog.php';
	$days = get_wp_video_gallery_day();
	if ($days < 50) {
		$ttt = $wpdb->prefix . 'wp_video_gallery';
		$empty = $wpdb->get_col($wpdb->prepare("SELECT id FROM $ttt WHERE source=%s LIMIT 1", 'vimeo'));
		if (count($empty) == 0)
			wp_video_gallery_fill_database();
		else
			wp_video_gallery_refresh_database();
	}
	echo wp_video_gallery_catalog_page_inside();
	die();
}

// Get grid's HTML for preview
add_action('wp_ajax_wp_video_gallery_ajax_preview_grid', 'wp_video_gallery_ajax_preview_grid');
add_action('wp_ajax_nopriv_wp_video_gallery_ajax_preview_grid', 'wp_video_gallery_ajax_preview_grid');
function wp_video_gallery_ajax_preview_grid() {
	global $wpdb;
	require_once plugin_dir_path(__FILE__) . 'get-front-grid.php';
	$table_name = $wpdb->prefix . "wp_video_gallery_grids_videos";
	$wpdb->delete($table_name, array('id_grid' => $_POST['gridId']));
	if (count($_POST["videos"])) {
		foreach ($_POST['videos'] as $video) {
			$wpdb->insert(
				$table_name,
				array(
					'id_grid'   => $_POST['gridId'],
					'video_uri' => $video[0],
					'orders'     => (int)$video[1],
					'big'       => (int)$video[2]
				)
			);
		}
	}
	$grid = wp_video_gallery_get_grid((int)$_POST['gridId']);
	$gridConf = unserialize($grid['configuration']);
	$gridConf['show_tags']   = (int)$_POST['show_tags'];
	$gridConf['show_title']  = (int)$_POST['show_title'];
	$gridConf['show_pager']  = (int)$_POST['show_pager'];
	$gridConf['duration']    = (int)$_POST['duration'];
	$gridConf['tags_color']  = $_POST['tags_color'];
	$gridConf['box_color']  = $_POST['box_color'];
	$gridConf['box_color_initial']  = $_POST['box_color_initial'];
	$gridConf['text_color']  = $_POST['text_color'];
	$gridConf['text_color_hover']  = $_POST['text_color_hover'];
	$gridConf['video_width'] = (int)$_POST['video_width'];
	$gridConf['video_spacing_x'] = (int)$_POST['video_spacing_x'];
	$gridConf['video_spacing_y'] = (int)$_POST['video_spacing_y'];
	$gridConf['animation'] = $_POST['animation'];
	$gridConf['enable_css_animation'] = (int)$_POST['enable_css_animation'];
	$gridConf['animation_delay'] = (int)$_POST['animation_delay'];
	$gridConf['custom_css'] = $_POST['custom_css'];
	$gridConf['display-trigger'] = $_POST['display_trigger'];
	$gridConf['title-box-mode'] = $_POST['title_box_mode'];

	$name = strlen($_POST['name']) ? $_POST['name'] : $grid['name'];
	$table_name = $wpdb->prefix . "wp_video_gallery_grids";
	$wpdb->update( 
		$table_name, 
		array( 
			'configuration' => serialize($gridConf),
			'theme'         => $_POST['theme'],
			'name'          => $name
		), 
		array( 'id' => (int)$_POST['gridId'] )
	);
	$html = wp_video_gallery_get_front_grid((int)$_POST['gridId']);
	echo $html;
	die();
}

// Get tags configuration
add_action('wp_ajax_wp_video_gallery_ajax_get_tags_conf', 'wp_video_gallery_ajax_get_tags_conf');
add_action('wp_ajax_nopriv_wp_video_gallery_ajax_get_tags_conf', 'wp_video_gallery_ajax_get_tags_conf');
function wp_video_gallery_ajax_get_tags_conf() {
	$grid = wp_video_gallery_get_grid((int)$_POST['gridId']);
	$gridConf = unserialize($grid['configuration']);
	$cpt = 0;
	$modalTags = array_merge(wp_video_galleryGetAllGridTagsInOrder((int)$_POST['gridId']));
	$html ='';
	$nn = (int) (count($modalTags) / 3) + 1;
	if (count($modalTags)) {
		for ($i = 0; $i < $nn; $i++) { 
			$html .= '<tr>';
			$j = 0;
			if (isset($modalTags[$i])) {
				if (is_array($gridConf['tags']) && count($gridConf['tags'])) {
					foreach ($gridConf['tags'] as $tag) {
						if ($tag[0] == $modalTags[$i] && $tag[1] == 0) {
							$html .= '<td><label><input type="checkbox"><span>' . $modalTags[$i] . '</span></label></td>';
							$j = 1;
						}
					}
				}
				if ($j == 0)
					$html .= '<td><label><input type="checkbox" checked><span>' . $modalTags[$i] . '</span></label></td>';
				$j = 0;
			}
			if (isset($modalTags[$i + $nn])) {
				if (is_array($gridConf['tags']) && count($gridConf['tags'])) {
					foreach ($gridConf['tags'] as $tag) {
						if ($tag[0] == $modalTags[$i + $nn + 1] && $tag[1] == 0) {
							$html .= '<td><label><input type="checkbox"><span>' . $modalTags[$i + $nn] . '</span></label></td>';
							$j = 1;
						}
					}
				}
				if ($j == 0)
					$html .= '<td><label><input type="checkbox" checked><span>' . $modalTags[$i + $nn] . '</span></label></td>';
				$j = 0;
			}
			else {
				$html .= '	<td></td>';
			}
			if (isset($modalTags[$i + 2 * $nn])) {
				if (is_array($gridConf['tags']) && count($gridConf['tags'])) {
					foreach ($gridConf['tags'] as $tag) {
						if ($tag[0] == $modalTags[$i + 2 * $nn + 1] && $tag[1] == 0) {
							$html .= '<td><label><input type="checkbox"><span>' . $modalTags[$i + 2 * $nn] . '</span></label></td>';
							$j = 1;
						}
					}
				}
				if ($j == 0)
					$html .= '<td><label><input type="checkbox" checked><span>' . $modalTags[$i + 2 * $nn] . '</span></label></td>';
				$j = 0;
			}
			else {
				$html .= '<td></td>';
			}
			$html .= '</tr>';
		}
		echo $html;
	}
	else
		echo 'no tags';
	die();
}

// Save search tags
add_action('wp_ajax_wp_video_gallery_ajax_disconnect_clear', 'wp_video_gallery_ajax_save_search_tags');
add_action('wp_ajax_nopriv_wp_video_gallery_ajax_disconnect_clear', 'wp_video_gallery_ajax_save_search_tags');
function wp_video_gallery_ajax_save_search_tags() {
	global $wpdb;
	$grid = wp_video_gallery_get_grid((int)$_POST['gridId']);
	$gridConf = unserialize($grid['configuration']);
	$gridConf['filterType'] = $_POST['filterType'];
	$gridConf['filters'] = $_POST['filters'];
	$table_name = $wpdb->prefix . "wp_video_gallery_grids";
	$wpdb->update( 
		$table_name, 
		array( 
			'configuration' => serialize($gridConf)
		), 
		array( 'id' => (int)$_POST['gridId'] )
	);
	die();
}

// SAve a grid
add_action('wp_ajax_wp_video_gallery_ajax_save_grid', 'wp_video_gallery_ajax_save_grid');
add_action('wp_ajax_nopriv_wp_video_gallery_ajax_save_grid', 'wp_video_gallery_ajax_save_grid');
function wp_video_gallery_ajax_save_grid() {
	global $wpdb;
	$table_name = $wpdb->prefix . "wp_video_gallery_grids_videos";
	$wpdb->delete($table_name, array('id_grid' => $_POST['gridId']));
	foreach ($_POST['videos'] as $video) {
		$wpdb->insert(
			$table_name,
			array(
				'id_grid'   => $_POST['gridId'],
				'video_uri' => $video[0],
				'orders'     => (int)$video[1],
				'big'       => (int)$video[2]
			)
		);
	}

	$grid = wp_video_gallery_get_grid((int)$_POST['gridId']);
	$gridConf = unserialize($grid['configuration']);
	$gridConf['show_tags']  = (int)$_POST['show_tags'];
	$gridConf['show_title'] = (int)$_POST['show_title'];
	$gridConf['duration']   = (int)$_POST['duration'];

	$table_name = $wpdb->prefix . "wp_video_gallery_grids";
	$wpdb->update( 
		$table_name, 
		array( 
			'configuration' => serialize($gridConf),
			'theme'         => $_POST['theme'],
			'name'          => $_POST['name']
		), 
		array( 'id' => (int)$_POST['gridId'] )
	);
	die();
}

// Save the selected tags for a grid
add_action('wp_ajax_wp_video_gallery_ajax_set_seleted_tags', 'wp_video_gallery_ajax_set_seleted_tags');
add_action('wp_ajax_nopriv_wp_video_gallery_ajax_set_seleted_tags', 'wp_video_gallery_ajax_set_seleted_tags');
function wp_video_gallery_ajax_set_seleted_tags() {
	global $wpdb;
	$grid = wp_video_gallery_get_grid((int)$_POST['gridId']);
	$gridConf = unserialize($grid['configuration']);
	$gridConf['tags']  = $_POST['tags'];

	$table_name = $wpdb->prefix . "wp_video_gallery_grids";
	$wpdb->update( 
		$table_name, 
		array( 
			'configuration' => serialize($gridConf),
			'theme'         => $_POST['theme']
		), 
		array( 'id' => (int)$_POST['gridId'] )
	);
	echo serialize($gridConf);
	die();
}

// Update search query
add_action('wp_ajax_wp_video_galleryAjaxRefreshSearchQuery', 'wp_video_galleryAjaxRefreshSearchQuery');
add_action('wp_ajax_nopriv_wp_video_galleryAjaxRefreshSearchQuery', 'wp_video_galleryAjaxRefreshSearchQuery');
function wp_video_galleryAjaxRefreshSearchQuery() {
	global $wpdb;
	$grid = wp_video_gallery_get_grid((int)$_POST['gridId']);
	$gridConf = unserialize($grid['configuration']);
	$gridConf['search_query'] = $_POST['value'];
	$gridConf['filterType'] = 'ET';
	$gridConf['filters'] = (array)NULL;
	$table_name = $wpdb->prefix . "wp_video_gallery_grids";
	$wpdb->update( 
		$table_name, 
		array( 
			'configuration' => serialize($gridConf)
		), 
		array( 'id' => (int)$_POST['gridId'] )
	);
	die();
}

// Remove a grid
add_action('wp_ajax_wp_video_gallery_ajax_remove_grid', 'wp_video_gallery_ajax_remove_grid');
add_action('wp_ajax_nopriv_wp_video_gallery_ajax_remove_grid', 'wp_video_gallery_ajax_remove_grid');
function wp_video_gallery_ajax_remove_grid() {
	global $wpdb;
	$table_name = $wpdb->prefix . "wp_video_gallery_grids";
	$wpdb->delete($table_name, array('id' => (int)$_POST['gridId']));
	$table_name = $wpdb->prefix . "wp_video_gallery_grids_videos";
	$wpdb->delete($table_name, array('id_grid' => (int)$_POST['gridId']));
	die();
}

// Duplicate a grid
add_action('wp_ajax_wp_video_gallery_ajax_duplicate_grid', 'wp_video_gallery_ajax_duplicate_grid');
add_action('wp_ajax_nopriv_wp_video_gallery_ajax_duplicate_grid', 'wp_video_gallery_ajax_duplicate_grid');
function wp_video_gallery_ajax_duplicate_grid() {
	global $wpdb;
	$table_name = $wpdb->prefix . "wp_video_gallery_grids";
	$originalGrid = $wpdb->get_row('SELECT * FROM ' . $table_name . ' WHERE id="' . $_POST['id'] . '"', ARRAY_A);
	unset($originalGrid["id"]);
	$originalGrid["name"] = $originalGrid["name"] . " (copie)";
	$newGridId = $wpdb->insert(
		$table_name,
		$originalGrid
	);
	$table_name = $wpdb->prefix . 'wp_video_gallery_grids_videos';
	$originalGridVideos = $wpdb->get_results('SELECT * FROM ' . $table_name . ' WHERE id_grid="' . $_POST['id'] . '"', ARRAY_A);
	if (count($originalGridVideos)) {
		foreach ($originalGridVideos as $key => $gridVideo) {
			unset($gridVideo['id']);
			$gridVideo['id_grid'] = $newGridId;
			$wpdb->insert(
				$table_name,
				$gridVideo
			);
		}
	}
	die();
}

// Validate the pro key
add_action('wp_ajax_wp_video_galleryValidateProKey', 'wp_video_galleryValidateProKey');
add_action('wp_ajax_nopriv_wp_video_galleryValidateProKey', 'wp_video_galleryValidateProKey');
function wp_video_galleryValidateProKey() {
	global $wpdb;
	$key = $_POST['value'];
	$response = (int)wp_video_gallery_check_pro_key($key, 1);
	if ($response == 1) {
		$table_name = $wpdb->prefix . 'wp_video_gallery_params';
		$t = $wpdb->get_var("SELECT value FROM $table_name WHERE param='pro_key'");
		if (is_null($t)) {
			$wpdb->insert(
				$table_name,
				array(
					'param' => 'pro_key',
					'value' => $key
				)
			);
		}
		else {
			$wpdb->update(
				$table_name,
				array(
					'value' => $key
				),
				array(
					'param' => 'pro_key'
				)
			);	
		}
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
	echo $response;
	die();
}

add_action('wp_ajax_wp_video_galleryForceVimeoPrivateVideosRefresh', 'wp_video_galleryForceVimeoPrivateVideosRefresh');
add_action('wp_ajax_nopriv_wp_video_galleryForceVimeoPrivateVideosRefresh', 'wp_video_galleryForceVimeoPrivateVideosRefresh');
function wp_video_galleryForceVimeoPrivateVideosRefresh() {
	global $wpdb;
	require_once plugin_dir_path( __FILE__ ) . 'catalog.php';
	$table_name = $wpdb->prefix . 'wp_video_gallery_params';
	$wpdb->update(
		$table_name,
		array(
			'value' => '1960-10-24T10:06:58+00:00'
		),
		array(
			'param' => 'last_refresh_date'
		)
	);
	wp_video_gallery_refresh_database();
	wp_video_gallery_catalog_page_inside();
	$registredVideosPub = WP_VIDEO_GALLERY_VimeoManager::get_registred_single_videos();
	if (is_array($registredVideosPub) && count($registredVideosPub)) {
		foreach ($registredVideosPub as $key => $value) {
			WP_VIDEO_GALLERY_VimeoManager::refresh_single_video("https://vimeo.com" . $value['uri']);
		}
	}
	exit();
}

add_action('wp_ajax_wp_video_galleryForceYoutubeVideosRefresh', 'wp_video_galleryForceYoutubeVideosRefresh');
add_action('wp_ajax_nopriv_wp_video_galleryForceYoutubeVideosRefresh', 'wp_video_galleryForceYoutubeVideosRefresh');
function wp_video_galleryForceYoutubeVideosRefresh() {
	global $wpdb;
	require_once plugin_dir_path( __FILE__ ) . 'catalog.php';
	$registredVideosYou = WP_VIDEO_GALLERY_VimeoManager::get_registred_youtube_videos();
	if (is_array($registredVideosYou) && count($registredVideosYou)) {
		foreach ($registredVideosYou as $key => $value) {
			$id = explode('/', $value['uri']);
			$id = $id[2];
			WP_VIDEO_GALLERY_VimeoManager::refresh_single_video_youtube("https://www.youtube.com/watch?v=" . $id);
		}
	}
	$registredVideosYou = WP_VIDEO_GALLERY_VimeoManager::get_registred_youtube_videos();
	$lines = "";
	if (is_array($registredVideosYou) && count($registredVideosYou)) {
		foreach ($registredVideosYou as $key => $value) {
			$id = explode('/', $value['uri']);
			$id = $id[2];
			$lines .= '
				<tr class="line" data-id="' . $id . '">
					<td class="thumb">
						<img src="' . $value['thumb_small'] . '">
					</td>
					<td class="title">
						' . $value['name'] . '
					</td>
					<td class="remove">
						<i class="fa fa-times removeYoutubeVideo" data-uri="' . $value['uri'] . '"></i>
					</td>
				</tr>
			';
		}
	}
	echo json_encode(array(
		"status" => 1,
		"lines" => $lines
	));
	exit();
}

add_action('wp_ajax_wp_video_galleryImportDemo', 'wp_video_galleryImportDemo');
add_action('wp_ajax_nopriv_wp_video_galleryImportDemo', 'wp_video_galleryImportDemo');
function wp_video_galleryImportDemo() {
	global $wpdb;
	$Gtable = $wpdb->prefix . 'wp_video_gallery_grids';
	$VGtable = $wpdb->prefix . 'wp_video_gallery_grids_videos';
	$r = wp_video_gallery_get_demo_ids();
	$parts = explode("---", $r);
	$wpdb->insert(
		$Gtable,
		array(
			'name' => "Demo gallery",
			'theme' => 'Mosaic',
			'configuration' => $parts[2]
		)
	);
	$gid = $wpdb->insert_id;
	$ids = explode("#", $parts[0]);
	foreach ($ids as $key => $id) {
		$url = str_replace('/videos/', 'https://vimeo.com/', $id);
		$res = WP_VIDEO_GALLERY_VimeoManager::add_single_video($url);
	}
	$ids = explode("*", $parts[1]);
	foreach ($ids as $key => $value) {
		$d = explode(',', $value);
		$wpdb->insert(
			$VGtable,
			array(
				'id_grid' => $gid,
				'video_uri' => $d[0],
				'orders' => $d[1],
				'big' => $d[2]
			)
		);
	}
	echo home_url() . "/wp-admin/admin.php?page=wp_video_gallery-edit-grids&id=" . $gid;
	die();
}

add_action('wp_ajax_wp_video_gallery_ajax_test_youtube_api_key', 'wp_video_gallery_ajax_test_youtube_api_key');
add_action('wp_ajax_nopriv_wp_video_gallery_ajax_test_youtube_api_key', 'wp_video_gallery_ajax_test_youtube_api_key');
function wp_video_gallery_ajax_test_youtube_api_key() {
	if (check_youtube_api_key($_POST['youtubeT'])) {
		$conf = wp_video_gallery_get_user_conf();
		$conf = unserialize($conf->value);
		$conf['youtube']['token'] = $_POST['youtubeT'];
		wp_video_gallery_set_user_conf($conf);
		echo home_url() . '/wp-admin/admin.php?page=wp_video_gallery-catalog';
	}
	else
		echo '0';
	die();
}

add_action('wp_ajax_wp_video_gallery_ajax_add_channel_youtube', 'wp_video_gallery_ajax_add_channel_youtube');
add_action('wp_ajax_nopriv_wp_video_gallery_ajax_add_channel_youtube', 'wp_video_gallery_ajax_add_channel_youtube');
function wp_video_gallery_ajax_add_channel_youtube() {
	global $wpdb;
	$url = $_POST['url'];
	$url = explode("/", $url);
	$url = $url[count($url) - 1];
	$items = (array)NULL;
	$conf = wp_video_gallery_get_user_conf();
	$conf = unserialize($conf->value);
	$token = $conf['youtube']['token'];
	$data = wpvg_performe_curl("https://www.googleapis.com/youtube/v3/search?type=video&key=" . $token . "&channelId=" . $url . "&maxResults=50&part=id&order=date");
	if ($data && is_array($data)) {
		$items = $data["items"];
		$qo = get_wp_video_gallery_day();
		$pro_activated = wp_video_gallery_check_pro_key();
		while (strlen($data["nextPageToken"])) {
			$data = wpvg_performe_curl("https://www.googleapis.com/youtube/v3/search?pageToken=" . $data["nextPageToken"] . "&type=video&key=" . $token . "&channelId=" . $url . "&maxResults=18&part=id&order=date");
			$items = array_merge($items, $data["items"]);
		}
		$i = 0;
		$c = 0;
		while (!is_null($items[$i]) && ($qo < 50 || $pro_activated)) {
			$yurl = "https://www.youtube.com/watch?v=" . $items[$i]["id"]["videoId"];
			$res = WP_VIDEO_GALLERY_VimeoManager::add_single_video_youtube($yurl);
			$i++;
			if ($res == 1) {
				$c++;
				$qo++;
			}
		}
		$videos = WP_VIDEO_GALLERY_VimeoManager::get_registred_youtube_videos();
		$html = '
			<h4 class="subtitle">' . __('Registred videos', 'wp_video_gallery') . ' :</h4>
			<div class="info">
				<span>' . count($videos) . ' ' . __('videos', 'wp_video_gallery') . '</span>
			</div>
			<div class="around">
				<table cellspacing="0">
		';
		foreach ($videos as $key => $value) {
			$id = explode('/', $value['uri']);
			$id = $id[2];
			$html .= '
					<tr class="line" data-id="' . $id . '">
						<td class="thumb">
							<img src="' . $value['thumb_small'] . '">
						</td>
						<td class="title">
							' . $value['name'] . '
						</td>
						<td class="remove">
							<i class="fa fa-times removeYoutubeVideo" data-uri="' . $value['uri'] . '"></i>
						</td>
					</tr>
			';
		}
		$html .= '
				</table>
			</div>
		';
		$cy = $wpdb->get_var("SELECT COUNT(id) FROM " . $wpdb->prefix . "wp_video_gallery WHERE source='youtube'");
		echo $html . "####" . $c . '####' . $cy;
	}
	else
		echo "error";
	die();
}
