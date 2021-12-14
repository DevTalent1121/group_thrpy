<?php
/**
* Plugin Name: WP Video Gallery
* Plugin URI: https://wp-video-gallery.com/
* Description: Manage your Vimeo/Youtube video catalog and create stylish and dynamic galleries in just 3 steps thanks to our WYSIWYG backeng preview.
* Version: 1.7.1
* Author: Majba
* Author URI: http://majba.com
* Text Domain: wp_video_gallery
* Domain Path: /languages
*/
if (! defined('ABSPATH')) exit; // Exit if accessed directly

// Load translations
add_action('plugins_loaded', 'wp_video_gallery_load_translation');
function wp_video_gallery_load_translation() {
		load_plugin_textdomain('wp_video_gallery', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

// Load WP_VIDEO_GALLERY and Vimeo' classes
require(plugin_dir_path(__FILE__) . 'Classes/vimeo.php/autoload.php');
require(plugin_dir_path(__FILE__) . 'Classes/WP_VIDEO_GALLERY_activation.php');
require(plugin_dir_path(__FILE__) . 'Classes/WP_VIDEO_GALLERY_Tools.php');
require(plugin_dir_path(__FILE__) . 'Classes/WP_VIDEO_GALLERY_VimeoManager.php');

// Require ajax function
require(plugin_dir_path(__FILE__) . 'wp_video_gallery-ajax.php');
require(plugin_dir_path(__FILE__) . 'wp_video_gallery-animate-css-select.php');


// Define WP_VIDEO_GALLERY_VIMEO_AT in init
add_action('init', 'wp_video_gallery_define_vimeo_token');
function wp_video_gallery_define_vimeo_token() {
	$con = wp_video_gallery_get_user_conf();
	$con = unserialize($con->value);
	define("WP_VIDEO_GALLERY_VIMEO_AT", $con['vimeo']['token']);
	define("WP_VIDEO_GALLERY_PATH", plugin_dir_path(__FILE__));
	global $wpdb;
	$table_name = $wpdb->prefix . 'wp_video_gallery_params';
	$t = $wpdb->get_var("SELECT value FROM $table_name WHERE param='pro_key'");
	define("WP_VIDEO_GALLERY_PRO_KEY", $t);
}

// Connect Vimeo Account
function wp_video_gallery_connect() {
	$lib = new \Vimeo\Vimeo('', '');
	$token = is_null(WP_VIDEO_GALLERY_VIMEO_AT) ? "" : WP_VIDEO_GALLERY_VIMEO_AT;
	$lib->setToken($token);
	return $lib;
}

// Update last refresh date
function wp_video_gallery_save_last_refresh($date) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'wp_video_gallery_params';
	$ldate = $wpdb->get_row("SELECT * FROM ".$table_name.' WHERE param = "last_refresh_date"');
	if ($ldate == NULL) {
		$wpdb->insert(
			$table_name,
			array(
				'param'         => 'last_refresh_date',
				'value'         => $date
			)
		);
	}
	else {
		$wpdb->update(
			$table_name,
			array(
				'value'          => $date
			),
			array(
				'param' => 'last_refresh_date'
			)
		);
	}
}

// Get last refresh date
function wp_video_gallery_get_last_refresh() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'wp_video_gallery_params';
	$ldate = $wpdb->get_row("SELECT * FROM ".$table_name.' WHERE param = "last_refresh_date"');
	return $ldate->value;
}

// Get videos
function wp_video_gallery_refresh_database() {
	global $wpdb;
	$last_refresh = wp_video_gallery_get_last_refresh();
	$lib = wp_video_gallery_connect();
	$i = 1;
	$q = 1;
	$total = 2;
	$per_page = 10;
	$qo = get_wp_video_gallery_day();
	$activated = wp_video_gallery_check_pro_key();
	while ($i <= $total && $q && ($qo < 50 || $activated)) {
		$response = $lib->request('/me/videos', array('per_page' => $per_page, 'sort' => 'date', 'direction' => 'desc', 'page' => $i), 'GET');
		$total = $response["body"]["total"] % $per_page == 0 ? ($response["body"]["total"] / $per_page) : (int)($response["body"]["total"] / $per_page) + 1;
		$i++;
		foreach ($response["body"]["data"] as $key => $value) {
			if ($qo < 50 || $activated) {
				if ($i == 2 && $key == 0)
					wp_video_gallery_save_last_refresh($value["modified_time"]);
				if (strcmp($last_refresh, $value["modified_time"]) < 0) {
					$uri = explode(':', $value['uri']);
					$video = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix.'wp_video_gallery WHERE uri = "' . $uri[0].'"');
					if ($video == NULL) {
						$qo++;
						WP_VIDEO_GALLERY_VimeoManager::register_video($value);
					}
					else
						WP_VIDEO_GALLERY_VimeoManager::update_video($value);
					WP_VIDEO_GALLERY_VimeoManager::update_tags($value);
				}
				else {
					$q = 0;
					break;
				}
			}
		}
	}

}

// Fill database when empty
function wp_video_gallery_fill_database() {
	global $wpdb;
	$lib = wp_video_gallery_connect();
	$i = 1;
	$q = 1;
	$total = 2;
	$per_page = 10;
	$qo = get_wp_video_gallery_day();
	$activated = wp_video_gallery_check_pro_key();
	while ($i <= $total && $q && ($qo < 50 || $activated)) {
		$response = $lib->request('/me/videos', array('per_page' => $per_page, 'sort' => 'date', 'direction' => 'desc', 'page' => $i), 'GET');
		$total = $response["body"]["total"] % $per_page == 0 ? ($response["body"]["total"] / $per_page) : (int)($response["body"]["total"] / $per_page) + 1;
		$i++;
		if (is_array($response["body"]["data"]) && count($response["body"]["data"])) {
			foreach ($response["body"]["data"] as $key => $value) {
				if ($qo < 50 || $activated) {
					if ($i == 2 && $key == 0)
						wp_video_gallery_save_last_refresh($value["modified_time"]);
					$uri = explode(':', $value['uri']);
					$video = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix.'wp_video_gallery WHERE uri = "' . $uri[0] . '"');
					if ($video == NULL) {
						$qo++;
						WP_VIDEO_GALLERY_VimeoManager::register_video($value);
					}
					else
						WP_VIDEO_GALLERY_VimeoManager::update_video($value);
					WP_VIDEO_GALLERY_VimeoManager::update_tags($value);
				}
			}
		}
	}
}

function wp_video_galleryGetAllGrids() {
	global $wpdb;
	return $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."wp_video_gallery_grids ORDER BY `id` ASC", ARRAY_A);
}

add_action('admin_menu', 'wp_video_gallery_vimeo_add_menu');

function wp_video_gallery_vimeo_add_menu() {
	$con = wp_video_gallery_get_user_conf();
	$con = unserialize($con->value);
	$token = $con['vimeo']['token'];
	$activated = wp_video_gallery_check_pro_key();
	if ($activated)
		add_menu_page('WP Video Gallery - Pro', 'WP Video Gallery - Pro', 'manage_options', 'wp_video_gallery-edit-grids', 'wp_video_gallery_edit_grids',  plugin_dir_url(__FILE__).'img/menu-logo.png');
	else
		add_menu_page('WP Video Gallery - Free', 'WP Video Gallery - Free', 'manage_options', 'wp_video_gallery-edit-grids', 'wp_video_gallery_edit_grids',  plugin_dir_url(__FILE__).'img/menu-logo.png');
	add_submenu_page('wp_video_gallery-edit-grids', __('Portfolios', 'wp_video_gallery'), __('Portfolios', 'wp_video_gallery'), 'manage_options', 'wp_video_gallery-edit-grids', 'wp_video_gallery_edit_grids');
	add_submenu_page('wp_video_gallery-edit-grids', __('New portfolio', 'wp_video_gallery'), __('New portfolio', 'wp_video_gallery'), 'manage_options', 'wp_video_gallery-new-grid', 'wp_video_gallery_add_grid');
	add_submenu_page('wp_video_gallery-edit-grids', __('Videos catalog', 'wp_video_gallery'), __('Videos catalog', 'wp_video_gallery'), 'manage_options', 'wp_video_gallery-catalog', 'wp_video_gallery_catalog');
	add_submenu_page('wp_video_gallery-edit-grids', __('Account configuration', 'wp_video_gallery'), __('Account configuration', 'wp_video_gallery'), 'manage_options', 'wp_video_gallery-conf', 'wp_video_gallery_conf_account');
	add_submenu_page('wp_video_gallery-edit-grids', __('Import demo', 'wp_video_gallery'), __('Import demo', 'wp_video_gallery'), 'manage_options', 'wp_video_gallery-demo', 'wp_video_gallery_import_demo');
	add_submenu_page('wp_video_gallery-edit-grids', __('WP Video Gallery Pro', 'wp_video_gallery'), __('WP Video Gallery Pro', 'wp_video_gallery'), 'manage_options', 'wp_video_gallery-pro', 'wp_video_gallery_pro_version');
}

function wp_video_gallery_pro_version() {
	$activated = '';
	wp_enqueue_script('majba-add-grid-scripts');
	if (wp_video_gallery_check_pro_key()) {
		$activated = '
			<div class="wp_video_gallery_pro_activated">
				<p>
				' . __('Thank you for activating the pro version of WP Video Gallery. You have an unlimited use of this plugin.', 'wp_video_gallery') . '
				</p>
			</div>
		';
	}
	$output = "
		<style>
		.wp_video_gallery_pro_activated {
			padding: 20px;
		    display: block;
		    background: rgba(0, 115, 170, 0.51);
		    width: 600px;
		    margin: 50px 0;
		    color: white;
		    text-align: center;
		}
		.wp_video_gallery_pro_activated p {
			font-size: 15px;
    		font-weight: bold;
		}
		.wp_video_gallery-pro-key-input {
			padding: 20px;
		    text-align: center;
		    font-weight: bold;
		    letter-spacing: 1px;
		    display: block;
		    margin-bottom: 30px;
		}
		.activation_errors .error {
			display: none;
	 	    color: red;
    		font-style: italic;
		 }
		</style>
		<div class='pro_version_page'>
			" . $activated . "
			<div class='key_div'>
				<h2>" . __('WP Video Gallery Pro key', 'wp_video_gallery') . "</h2>
				<p>" . __("Here you can enter your WP Video Gallery pro version key. You can get it from <a href='https://wp-video-gallery.com/' target='_blank'>our website</a>", 'wp_video_gallery') . "</p>
				<input type='text' class='wp_video_gallery-pro-key-input' value='" . WP_VIDEO_GALLERY_PRO_KEY . "'>
				<div class='activation_errors'>
					<p class='error error-0'>" . __('The provided key is not valid.', 'wp_video_gallery') . "</p>
					<p class='error error--1'>" . __("The provided key is already being used to it's max.", 'wp_video_gallery') . "</p>
				</div>
				<p class='button-primary validate-pro-key'>" . __('Validate', 'wp_video_gallery') . "</p>
			</div>
		</div>
	";
	echo $output;
}

function wp_video_gallery_catalog() {
	wp_enqueue_style('wp_video_gallery');
	wp_enqueue_script('majba-add-grid-scripts');
	wp_enqueue_style('font-awesome');
	wp_enqueue_style('magnific-css');
	wp_enqueue_script('lazy-loading');
	wp_enqueue_script('magnific-js');
	require_once plugin_dir_path(__FILE__) . 'catalog.php';
	if (WP_VIDEO_GALLERY_Tools::vimeoAccountConnected())
		wp_video_gallery_catalog_page();
	else {
		echo '<div id="wp_video_gallery-account" class="catalog">';
		wp_video_gallery_catalog_page_inside();
		echo '</div>';
	}
}

function wp_video_gallery_conf_account() {
	wp_enqueue_style('wp_video_gallery');
	wp_enqueue_style('magnific-css');
	wp_enqueue_script('majba-add-grid-scripts');
	wp_enqueue_script('magnific-js');
	wp_enqueue_style('font-awesome');
	require_once plugin_dir_path(__FILE__) . 'account-config.php';
	wp_video_gallery_account_conf_page();
}

function wp_video_gallery_edit_grids() {
	wp_enqueue_style('wp_video_gallery');
	wp_enqueue_style('font-awesome');
	wp_enqueue_style('lightbox-css');
	wp_enqueue_script('freewall');
	wp_enqueue_script('lazy-loading');
	wp_enqueue_script('lightbox-js');
	wp_enqueue_script('majba-scripts');
	wp_enqueue_style('wp_video_gallery-front-css');
	wp_enqueue_style('bxslider-css');
	wp_enqueue_script('bxslider-js');
	wp_enqueue_style('spectrum-css', plugin_dir_url(__FILE__) . '/css/spectrum.css');
	wp_enqueue_script('spectrum-js', plugin_dir_url(__FILE__) . '/js/spectrum.js');
	wp_enqueue_style('magnific-css');
	wp_enqueue_style('animate-css');
	wp_enqueue_script('magnific-js');
	if (!wp_script_is('froogaloop2', 'enqueued')) {
		wp_enqueue_script('froogaloop2');
	}
	if (isset($_GET['id'])) {
		if ((int)$_GET['id'] != 0) {
			require_once plugin_dir_path(__FILE__) . 'single-grid.php';
			wp_video_gallery_edit_grid((int)$_GET['id']);
			return 0;
		}
	}
	wp_video_gallery_show_grids();
}

function wp_video_gallery_add_grid() {
	wp_enqueue_style('wp_video_gallery');
	wp_enqueue_script('majba-add-grid-scripts');
	wp_enqueue_style('font-awesome');
	$days = get_wp_video_gallery_day();
	if ($days < 50) {
		echo wp_video_gallery_days_html();
	}
	else
		echo wp_video_gallery_days_html_ex();
	if (WP_VIDEO_GALLERY_Tools::catalogContainsVideos()) {
		echo '
			<div id="vimeoMajbaGrids" class="add-grid-back">
		';
	    echo '
				<h2>' . __('New portfolio', 'wp_video_gallery') . ' :</h2>
				<input type="text" placeholder="' . __('How would you like to name your portfolio?', 'wp_video_gallery') . '" class="titleInput">
				<button class="button-primary createNewGrid">' . __('Add a new portfolio', 'wp_video_gallery') . '</button>
				<p class="error">' . __('You must provide a title.', 'wp_video_gallery') . '</p>
		';
	    echo '
	    	</div>
	    	<script>
	    		(jQuery)(document).on("load ready", function() {
	    			(jQuery)(".titleInput").focus();
	    		})
	    	</script>
		';
	}
	else {
		$catalogUrl = home_url() . '/wp-admin/admin.php?page=wp_video_gallery-catalog';
		$output = '
			<div id="vimeoMajbaGrids" class="neddToAddVideos">
				<h2>' . __('New portfolio', 'wp_video_gallery') . ' :</h2>
				<span style="font-size:18px;">' . __("You can't create nor manage portfolios if you do not have any videos.<br><br>To register public and/or private videos, <a href='", 'wp_video_gallery') . $catalogUrl . __("'>go to the <b>Videos catalog</b> page</a>", 'wp_video_gallery') . '.</span>
			</div>
		';
		echo $output;
	}
}

function wp_video_galleryGetAllTags($search_query = "") {
	global $wpdb;
	$tags   = (array)NULL;
	$videos = wp_video_galleryGetAllVideos($search_query);
	foreach ($videos as $video) {
		$vidTags = unserialize($video->tags);
		foreach ($vidTags as $tag) {
			$tags[] = $tag['name'];
		}
	}
	$tags = array_unique($tags);
	uasort($tags, function ($a, $b) {
	    return strcmp(strtolower($a), strtolower($b));
	});
	return $tags;
}

function wp_video_galleryGetAllGridTags($gridId) {
	global $wpdb;
	$tags = (array)NULL;
	$gridUris = wp_video_galleryGetSelectedVideosUris($gridId);
	foreach ($gridUris as $value) {
		$video = wp_video_galleryGetVideoFromUri($value);
		$vidTags = unserialize($video->tags);
		foreach ($vidTags as $tag) {
			$tags[] = $tag['name'];
		}
	}
	$tags = array_unique($tags);
	return $tags;
}

function wp_video_galleryGetAllGridTagsInOrder($gridId) {
	$tags = wp_video_galleryGetAllGridTags($gridId);
	uasort($tags, function ($a, $b) {
	    return strcmp(strtolower($a), strtolower($b));
	});
	return $tags;
}

function wp_video_galleryGetFrontGridTags($gridId) {
	$grid = wp_video_gallery_get_grid($gridId);
	$gridConf = unserialize($grid['configuration']);
	$cpt = 0;
	$tags = (array)NULL;
	foreach (wp_video_galleryGetAllGridTags($gridId) as $key => $value) {
		$j = 0;
		foreach ($gridConf['tags'] as $tag) {
			if ($tag[0] == $value && $tag[1] == 0)
				$j = 1;
		}
		if ($j == 0)
			$tags[] = $value;
	}
	uasort($tags, function ($a, $b) {
	    return strcmp(strtolower($a), strtolower($b));
	});
	return $tags;
}

function wp_video_galleryGetAllVideos($search_query = "") {
	global $wpdb;
	$videos = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."wp_video_gallery WHERE (tags LIKE '%" . $search_query . "%' OR name LIKE '%" . $search_query . "%' OR description LIKE '%" . $search_query . "%')");
	foreach ($videos as $key => $value)
		$return[] = $value;
	return $return;
} 

function wp_video_gallery_show_grids() {
	require_once plugin_dir_path(__FILE__) . 'table.php';
    $myListTable = new Vimeo_Majba_Table();
    $myListTable->prepare_items();
    $days = get_wp_video_gallery_day();
	if ($days < 50) {
		echo wp_video_gallery_days_html();
	}
	else
		echo wp_video_gallery_days_html_ex();
	if (WP_VIDEO_GALLERY_Tools::catalogContainsVideos()) {
	    echo '
	    	<div id ="vimeoMajbaGrids">
	    		<h2>' . __('Edit portfolios', 'wp_video_gallery') . ' : <a href="?page=wp_video_gallery-new-grid" class="add-new-h2">' . __('Add a new portfolio', 'wp_video_gallery') . '</a></h2>
	    ';
	    $myListTable->display();
	    echo '</div>';
	}
	else {
		$catalogUrl = home_url() . '/wp-admin/admin.php?page=wp_video_gallery-catalog';
		$output = '
			<div id="vimeoMajbaGrids" class="neddToAddVideos">
				<h2>' . __('New portfolio', 'wp_video_gallery') . ' :</h2>
				<span style="font-size:18px;">' . __("You can't create nor manage portfolios if you do not have any videos.<br><br>To register public and/or private videos, <a href='", 'wp_video_gallery') . $catalogUrl . __("'>go to the <b>Videos catalog</b> page</a>", 'wp_video_gallery') . '.</span>
			</div>
		';
		echo $output;
	}
}

//add_action('init', 'init_wp_video_gallery');
wp_register_style('wp_video_gallery', plugin_dir_url(__FILE__) . 'css/wp_video_gallery.css');
wp_register_style('lightbox-css', plugin_dir_url(__FILE__) . 'css/lightbox.min.css');
wp_register_style('magnific-css', plugin_dir_url(__FILE__) . 'css/magnific.css');
wp_register_style('animate-css', plugin_dir_url(__FILE__) . 'css/animate.css');
wp_register_style('wp_video_gallery-front-css', plugin_dir_url(__FILE__) . 'front/wp_video_gallery-front-css.css', [], "14012021");
wp_register_style('bxslider-css', plugin_dir_url(__FILE__) . 'css/bxslider.css');
wp_register_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');

register_activation_hook(__FILE__, array('WP_VIDEO_GALLERY_Activation', 'onActivation'));

wp_register_script('freewall', plugin_dir_url(__FILE__) . 'js/freewall.js');

wp_register_script('lazy-loading', plugin_dir_url(__FILE__) . 'js/lazy-loading.js');
wp_register_script('majba-scripts', plugin_dir_url(__FILE__) . 'js/scripts.js', [], "13012021");
wp_localize_script('majba-scripts', 'preview_path', plugin_dir_url(__FILE__) . 'js/preview.js');

wp_register_script('majba-add-grid-scripts', plugin_dir_url(__FILE__) . 'js/add-grid.js', [], "13012021");
wp_register_script('magnific-js', plugin_dir_url(__FILE__) . 'js/magnific.min.js');
wp_register_script('lightbox-js', plugin_dir_url(__FILE__) . 'js/lightbox.min.js');
wp_register_script('wp_video_gallery-front-js', plugin_dir_url(__FILE__) . 'front/wp_video_gallery-front-js.js', array('jquery'));
wp_register_script('froogaloop2', 'https://f.vimeocdn.com/js/froogaloop2.min.js');
wp_register_script('ajax-form', plugin_dir_url(__FILE__) . 'js/ajax-form.min.js');

wp_register_script('bxslider-js', plugin_dir_url(__FILE__) . 'js/jquery.bxslider.min.js');

function wp_video_gallery_get_grid($id) {
	global $wpdb;
	return $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."wp_video_gallery_grids WHERE id=".$id, ARRAY_A);
}

function wp_video_galleryGetVideoFromUri($uri) {
	global $wpdb;
	$video = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix.'wp_video_gallery WHERE uri = "' . $uri . '"');
	return $video;
}

function wp_video_galleryGetSelectedVideosUris($gridId) {
	global $wpdb;
	$results = $wpdb->get_results("SELECT video_uri FROM ".$wpdb->prefix."wp_video_gallery_grids_videos WHERE id_grid=$gridId ORDER BY `orders` ASC", ARRAY_A);
	$return = (array)NULL;
	foreach ($results as $value) {
		$return[] = $value['video_uri'];
	}
	return $return;
}

function get_wp_video_gallery_day() {
	global $wpdb;
	if (wp_video_gallery_check_pro_key())
		return 0;
	$table_name = $wpdb->prefix . 'wp_video_gallery';
	$videos = $wpdb->get_results("SELECT COUNT(id) as n FROM " . $table_name);
	$n = $videos[0];
	$n = $n->n;
	return ($n);
}

function wp_video_gallery_check_pro_key($key = WP_VIDEO_GALLERY_PRO_KEY, $activate = 0) {
	$url = 'https://wp-video-gallery.com/prov2.php';
	$hostData = parse_url(get_site_url());
	$v = 'k=' . $key . '&a=' . $activate . '&h=' . $hostData["host"];
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $v);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
	$response = curl_exec($ch);
	if (!$response && strlen(WP_VIDEO_GALLERY_PRO_KEY)) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'wp_video_gallery_params';
		$wpdb->delete(
			$table_name,
			array(
				'param' => 'pro_key'
			)
		);
	}
	return (int)$response;
}

function wp_video_gallery_get_demo_ids() {
	$url = 'https://wp-video-gallery.com/demo-test.php';
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
	$response = curl_exec($ch);
	return $response;
}

function wp_video_gallery_days_html() {
	if (wp_video_gallery_check_pro_key())
		return;
	$o = '
		<div id="wp_video_gallery_daez">
			' . __("You can manage up to 50 videos with the WP Video Gallery free version. For a professional and unrestricted use of WP Video Gallery, <a href='https://wp-video-gallery.com/pro-video-gallery/' target='_blank'>Get the premium version</a>", 'wp_video_gallery') . '
		</div>
	';
	return $o;
}

function wp_video_gallery_days_html_ex() {
	if (wp_video_gallery_check_pro_key())
		return;
	$o = '
		<div id="wp_video_gallery_daez_ex">
			' . __("You have already reached your 50 videos limit. For a professional and unrestricted use of this plugin, <a href='https://wp-video-gallery.com/pro-video-gallery/' target='_blank'>Get the premium version</a>", 'wp_video_gallery') . '
		</div>
	';
	return $o;
}

function wp_video_galleryGetSelectedVideos($gridId) {
	global $wpdb;
	$return = (array)NULL;
	$uris = wp_video_galleryGetSelectedVideosUris($gridId);
	foreach ($uris as $uri) {
		$video = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix.'wp_video_gallery WHERE uri = "' . $uri . '"');
		$return[] = $video;
	}
	return $return;
}

function wp_video_galleryBigOrNot($gridId, $uri) {
	global $wpdb;
	$video = $wpdb->get_row("SELECT big FROM ".$wpdb->prefix.'wp_video_gallery_grids_videos WHERE video_uri = "' . $uri . '" AND id_grid='.$gridId);
	return (int)$video->big;
}

function get_wp_video_gallery_grids() {
	global $wpdb;
	$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."wp_video_gallery_grids", ARRAY_A);
	return $results;
}

/* Register vimeo majba in enfold */
add_filter('avia_load_shortcodes', 'avia_include_shortcode_wp_video_gallery', 16, 2);
function avia_include_shortcode_wp_video_gallery($paths) {
	array_unshift($paths, plugin_dir_path(__FILE__) . 'shortcodes/');
	return $paths;
}

add_shortcode('wp_video_gallery_grid', 'wp_video_gallery_portfolio_shortcode_callback');

function wp_video_gallery_portfolio_shortcode_callback($atts)
{
	require_once plugin_dir_path(__FILE__) . 'get-front-grid.php';
	if (!wp_script_is('wp_video_gallery-front-js', 'enqueued')) {
		wp_enqueue_script('wp_video_gallery-front-js');
	}
	if (!wp_script_is('freewall', 'enqueued')) {
		wp_enqueue_script('freewall');
	}
	if (!wp_script_is('froogaloop2', 'enqueued')) {
		wp_enqueue_script('froogaloop2');
	}
	if (!wp_style_is('wp_video_gallery-front-css', 'enqueued')) {
		wp_enqueue_style('wp_video_gallery-front-css');
	}
	if (!wp_style_is('font-awesome', 'enqueued')) {
		wp_enqueue_style('font-awesome');
	}
	if (!wp_style_is('lazy-loading', 'enqueued')) {
		wp_enqueue_script('lazy-loading');
	}
	wp_enqueue_style('bxslider-css');
	wp_enqueue_script('bxslider-js');
	wp_enqueue_style('lightbox-css');
	wp_enqueue_style('animate-css');
	wp_enqueue_script('lightbox-js');
	$output  = wp_video_gallery_get_front_grid((int)$atts['id']);
    return $output;
}

function moyenne_videos($vids_co_v) {
	global $wpdb;
	$table = $wpdb->prefix . 'wp_video_gallery';
	$oldest = $wpdb->get_var("SELECT date_created FROM $table ORDER BY date_created ASC");
	$oldest = date('Y-m', strtotime($oldest));
	$d1 = new DateTime($oldest);
	$d2 = new DateTime(date('Y-m'));
	$interval = $d2->diff($d1);
	$interval = $interval->y * 12 + $interval->m;
	$m = round($vids_co_v / $interval);
	return $m;
}

function wp_video_gallery_post_stats($installed = 1) {
	global $wpdb;
	$table = $wpdb->prefix . 'wp_video_gallery';
	$vids_co = $wpdb->get_var("SELECT COUNT(*) FROM $table");
	$vids_co_v = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE source='vimeo'");
	$vids_co_s = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE source='vimeo_single'");
	$vids_co_y = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE source='youtube'");
	$table = $wpdb->prefix . 'wp_video_gallery_grids';
	$grids_co = $wpdb->get_var("SELECT COUNT(*) FROM $table");
	$grids_co_c = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE theme='Classic'");
	$grids_co_m = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE theme='Mosaic'");
	$grids_co_s = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE theme='Slider'");
	$table = $wpdb->prefix . 'wp_video_gallery_params';
	$token = unserialize($wpdb->get_var("SELECT value FROM $table WHERE param='user_conf'"));
	$token = $token['vimeo']['token'];
	$token = strlen($token) ? 1 : 0;
	$table = $wpdb->prefix . 'wp_video_gallery_grids_videos';
	$t = $wpdb->get_results("SELECT COUNT(id) as n FROM $table GROUP BY id_grid");
	$m = moyenne_videos($vids_co_v);
	$tt = 0;
	$c = $grids_co;
	foreach ($t as $key => $value) {
		$tt += $value->n / $c;
	}
	$tt = round($tt);
	$version = 0;
	if (!function_exists('get_plugin_data'))
	    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
	$r = get_plugin_data(__FILE__);$version = $r['Version'];
	$hostData = parse_url(get_site_url());
	$activated = wp_video_gallery_check_pro_key();
	if ($activated)
		$post_d = serialize(array($hostData["host"], $vids_co, $vids_co_v, $vids_co_s, $grids_co, $grids_co_c, $grids_co_m, $grids_co_s, $token, $tt, $m, 'Pro', WP_VIDEO_GALLERY_PRO_KEY, $installed, $vids_co_y, $version));
	else
		$post_d = serialize(array($hostData["host"], $vids_co, $vids_co_v, $vids_co_s, $grids_co, $grids_co_c, $grids_co_m, $grids_co_s, $token, $tt, $m, 'Free', 'Free', $installed, $vids_co_y, $version));
	$url = 'https://wp-video-gallery.com/statsv2.php';
	$v = 'd=' . $post_d;
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $v);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
	$response = curl_exec($ch);
}

function wp_video_galleryGetUncheckedSpe($color) {
	$color = $color == '' ? '#000000' : $color;
	return '<svg class="unchecked" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
		 width="12px" height="12px" viewBox="0 0 408 408" style="enable-background:new 0 0 408 408;fill:' . $color . '" xml:space="preserve">
			<g>
				<g id="crop-square">
					<path d="M357,0H51C22.95,0,0,22.95,0,51v306c0,28.05,22.95,51,51,51h306c28.05,0,51-22.95,51-51V51C408,22.95,385.05,0,357,0z
						 M357,357H51V51h306V357z"/>
				</g>
			</g>
		</svg>';
}

function wp_video_galleryGetCheckedSpe($color) {
	$color = $color == '' ? '#000000' : $color;
	return '<svg class="checked" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="12px" height="12px" viewBox="0 0 987.331 987.332" style="enable-background:new 0 0 987.331 987.332;fill:' . $color . '" xml:space="preserve">
				<g>
					<path d="M120,955.166h683.1c66.2,0,120-53.801,120-120v-467.1l-120,120v347.1H120v-683h547.3l96.3-96.3
						c9.101-9.1,19.101-17,29.801-23.7H120c-66.2,0-120,53.8-120,120v683.1C0,901.365,53.8,955.166,120,955.166z"/>
					<path d="M958.2,99.465c-19.4-20.1-44.8-30.8-70.5-32.3c-2.101-0.1-4.101-0.2-6.2-0.2c-27.3,0-54.6,10.4-75.5,31.3l-334,334
						c-11.3-12.101-81.1-82.4-81.1-82.4c-20.801-20.8-48.2-31.3-75.5-31.3c-27.3,0-54.601,10.4-75.5,31.3c-41.7,41.7-41.7,109.3,0,151
						l81.8,81.801l75.5,75.5c20,20,47.2,31.301,75.5,31.301s55.5-11.201,75.5-31.301l406.2-406.2
						C996,210.266,999.2,141.766,958.2,99.465z"/>
				</g>
			</svg>';
}

function wp_video_gallery_get_user_conf() {
	global $wpdb;
	$con = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."wp_video_gallery_params WHERE param='user_conf'");
	return $con;
}

function wp_video_gallery_set_user_conf($conf) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'wp_video_gallery_params';
	$wpdb->delete($table_name, array('param' => 'user_conf'));
	$wpdb->insert(
		$table_name,
		array(
			'param'         => 'user_conf',
			'value'         => serialize($conf)
		)
	);
}

register_deactivation_hook( __FILE__, 'wp_video_gallery_deactivate' );

function wp_video_gallery_deactivate() {
	wp_video_gallery_post_stats(0);
	global $wpdb;
	$table_name = $wpdb->prefix . 'wp_video_gallery_params';
	$wpdb->delete(
		$table_name,
		array(
			'param' => 'pro_key'
		)
	);
}

function get_youtube_public_data_old($url) {
	$parts = parse_url($url);
	parse_str($parts['query'], $query);
	$content = file_get_contents("https://youtube.com/get_video_info?video_id=".$query ['v']);
	parse_str($content, $return);
	$return ['v'] = $query['v'];
	if (!isset($return['video_id']))
		return null;
	return $return;
}

function wp_video_gallery_import_demo() {
	wp_enqueue_script('majba-add-grid-scripts');
	wp_enqueue_style('wp_video_gallery');
	wp_enqueue_style('wpvg-import-demo', plugin_dir_url(__FILE__).'css/import-demo.css');
	wp_enqueue_style('font-awesome');
	$res = WP_VIDEO_GALLERY_VimeoManager::add_single_video($_POST['url']);
	$days = get_wp_video_gallery_day();
	if ($days < 50) {
		echo wp_video_gallery_days_html();
	}
	else
		echo wp_video_gallery_days_html_ex();
	if ($days > 34) {
		$output = "
			<div class='back-container'>
				<h1 class='titre-principal'>" . __('Import demo content', 'wp_video_gallery') . "</h1>
				<p>" . __("You can not import our demo because you have more than 34 videos, and the demo contains 16 videos.<br>With the free version of the plugin, you can't have more than 50 videos in your catalog. For more information about the <a target='_blank' href='https://wp-video-gallery.com/pro-video-gallery/'>pro plugin</a>", 'wp_video_gallery') . "</p>
			</div>
		";	
	}
	else {
		$output = "
			<div class='back-container'>
				<h1 class='titre-principal'>" . __('Import demo content', 'wp_video_gallery') . "</h1>
				<p>" . __('Here you can get your 16 videos demo.', 'wp_video_gallery') . "</p>
				<p class='button-primary import-demo-button'>" . __('Import demo', 'wp_video_gallery') . "</p>
			</div>
		";
	}
	echo $output;
}

function check_youtube_api_key($key) {
	$url = "https://www.googleapis.com/youtube/v3/search?part=snippet&q=YouTube+Data+API&type=video&key={$key}";
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$return = curl_exec($curl);
	curl_close($curl);
	$return = json_decode($return, true);
	if (isset($return["etag"]))
		return 1;
	return 0;
}

function wpvg_performe_curl($url) {
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$return = curl_exec($curl);
	curl_close($curl);
	$return = json_decode($return, true);
	return $return;
}

function get_youtube_public_data($url) {
	$youtube = "https://www.youtube.com/oembed?url=". $url ."&format=json";
	$curl = curl_init($youtube);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$return = curl_exec($curl);
	curl_close($curl);
	//$returnOld = get_youtube_public_data_old($url);
	$return = json_decode($return, true);
	$return['v'] = getYouTubeVideoID($url);
	$return['video_id'] = $return['v'];
	return $return;
}

function getYouTubeVideoID($url) {
    $queryString = parse_url($url, PHP_URL_QUERY);
    parse_str($queryString, $params);
    if (isset($params['v']) && strlen($params['v']) > 0) {
        return $params['v'];
    } else {
        return "";
    }
}

//add_action('init', "tests");
function tests() {
	global $wpdb;
	$table_name = $wpdb->prefix . "wp_video_gallery_grids_videos";
	// $originalGrid = $wpdb->get_row('SELECT * FROM ' . $table_name . ' WHERE id="1"', ARRAY_A);
	// unset($originalGrid["id"]);
	// $originalGrid["name"] = $originalGrid["name"] . " (copie)";
	$originalGridVideos = $wpdb->get_results('SELECT * FROM ' . $table_name . ' WHERE id_grid="1"', ARRAY_A);
	var_dump(count($originalGridVideos));
	exit();
}