<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
function wp_video_gallery_edit_grid($gridId) {
	$days = get_wp_video_gallery_day();
	wp_video_gallery_post_stats();
	if ($days < 50) {
		echo wp_video_gallery_days_html();
	}
	else
		echo wp_video_gallery_days_html_ex();
	echo '
		<div class="spinnerDiv preLoad">
			<i class="fa fa-spinner fa-spin"></i>
			<p class="loading-message">' . __("Please wait while we're refreshing your account videos data.", 'wp_video_gallery') . '</p>
		</div>
		<div id="singleGrid" class="wrap" style="display:none;">
	';
	global $wpdb;
	WP_VIDEO_GALLERY_VimeoManager::reset_tags();
	$ttt = $wpdb->prefix . 'wp_video_gallery';
	$empty = $wpdb->get_col($wpdb->prepare("SELECT 1 FROM $ttt WHERE source=%s LIMIT 1", 'vimeo'));
	if ($days < 50) {
		if ( count($empty) == 0 )
			wp_video_gallery_fill_database();
		else
			wp_video_gallery_refresh_database();
	}
	$grid = wp_video_gallery_get_grid($gridId);
	$selectedVideosUris = wp_video_galleryGetSelectedVideosUris((int)$_GET['id']);
	$selectedVideos = wp_video_galleryGetSelectedVideos((int)$_GET['id']);
	$disabledClass = count($selectedVideos) > 0 ? '' : ' disabled';
	if ($_GET['action'] == 'search') {
		echo '
			<input type="hidden" id="searching"/>
		';
	}
	echo '
			<h1>
				' . __('Edit portfolio', 'wp_video_gallery') . ' : <input type="text" class="portfolio-title" value="'.$grid["name"].'">
				<a href="#" class="page-title-action' . $disabledClass . '">' . __('Save portfolio', 'wp_video_gallery') . '</a>
			</h1>';
	if (!count($selectedVideosUris)) {
		echo '
			<p class="help">
				' . __('Steps to build your portfolio/slider', 'wp_video_gallery') . ' :<br>
				<ul>
					<li>' . __('<b>Step 1</b> : Select the videos you want', 'wp_video_gallery') . '.</li>
					<li>' . __('<b>Step 2</b> : Order your selected videos', 'wp_video_gallery') . '.</li>
					<li>' . __('<b>Step 3</b> : Preview and configure your portfolio/slider', 'wp_video_gallery') . '.</li>
				</ul>
			</p>
		';
	}
	$showTag  = (array)NULL;
	$showTitle  = (array)NULL;
	$showPage  = (array)NULL;
	$whichTheme  = (array)NULL;
	$hideConfig  = (array)NULL;
	$gridConf = unserialize($grid['configuration']);
	$allVideos = wp_video_galleryGetAllVideos($gridConf['search_query']);
	for ($i = 0; $i < 4; $i++) { 
		$showTag[$i] = (int)$gridConf['show_tags'] == $i ? ' checked' : '';
	}
	for ($i = 0; $i < 2; $i++) { 
		$showTitle[$i]  = (int)$gridConf['show_title'] == $i ? ' checked' : '';
		$showPage[$i]   = (int)$gridConf['show_pager'] == $i ? ' checked' : '';
		$hideConfig[$i] = '';
	}
	for ($i = 0; $i < 4; $i++) { 
		$whichTheme[$i] = '';
	}
	switch ($grid['theme']) {
		case 'Mosaic':
			$whichTheme[1] = ' checked';
			$hideConfig[1] = ' style="display:none;"';
			break;
		case 'Slider':
			$whichTheme[2] = ' checked';
			$hideConfig[0] = ' style="display:none;"';
			break;
		default:
			$whichTheme[0] = ' checked';
			$hideConfig[1] = ' style="display:none;"';
			break;
	}
	$hideConfig[2] = ($hideConfig[1] == '' || $showTag[0] != '') ? ' style="display:none;"' : '';
	for ($i = 0; $i < 4; $i++) { 
		$showTag[$i] = (int)$gridConf['show_tags'] == $i ? ' checked' : '';
	}
	echo '
			<div class="header">
				<div class="steps">
					<div class="item active item1" data-action="select">
						<i class="fa fa-search"></i>
						<span>' . __('Search', 'wp_video_gallery') . '</span>
					</div>
					<i class="fa fa-chevron-right"></i>
					<div  class="item item2'.$disabledClass.'" data-action="order">
						<i class="fa fa-sort"></i>
						<span>' . __('Order', 'wp_video_gallery') . '</span>
					</div>
					<i class="fa fa-chevron-right"></i>
					<div class="item item3'.$disabledClass.'" data-action="display">
						<i class="fa fa-eye"></i>
						<span>' . __('Preview', 'wp_video_gallery') . '</span>
					</div>
				</div>
				<input type="hidden" id="gridId" value="' . $_GET["id"] . '"> 
			</div>
			<div class="videosList">
				<div id="videosOrder">
					<div class="selectionCount">
						<p>
							' . __('Selection', 'wp_video_gallery') . ': <span>' . count($selectedVideos) . '</span>
						</p>
					</div>
					<div class="ordredVids">';
	foreach ($selectedVideos as $key => $value) {
		$id = explode("/", $value->uri);
		$id = $id[count($id) - 1];
		$vimeo_class = $value->source == 'youtube' ? '' : 'vimeo';
		$big = wp_video_galleryBigOrNot((int)$_GET['id'], $value->uri);
		$checked = $big ? ' checked' : '';
		echo '
						<div class="brick ' . $vimeo_class . ' chosen item-' . $id . '" data-id="' . $id . '">
							<p class="rank"></p>
							<img class="thumb" data-src="' . $value->thumb_small . '" width="765" height="574">
							<p class="name">' . $value->name . '</p>
							<div class="bigContainer">
								<label><span>' . __('Display in bigger with the Mosaic theme', 'wp_video_gallery') . '</span>
								<input type="checkbox" ' . $checked . ' class="isBig"></label>
								<i class="fa fa-times"></i>
								<i class="fa fa-bars"></i>
							</div>
						</div>
		';
	}
	foreach ($allVideos as $key => $value) {
		if (!in_array($value->uri, $selectedVideosUris)) {
			$vimeo_class = $value->source == 'youtube' ? '' : 'vimeo';
			$id = explode("/", $value->uri);
			$id = $id[count($id) - 1];
			echo '
						<div class="brick ' . $vimeo_class . ' item-' . $id . '" data-id="' . $id . '">
							<p class="rank"></p>
							<img class="thumb" data-src="' . $value->thumb_small . '" width="765" height="574">
							<p class="name">' . $value->name . '</p>
							<div class="bigContainer">
								<label><span>' . __('Display in bigger with the Mosaic theme', 'wp_video_gallery') . '</span>
								<input type="checkbox" class="isBig"></label>
								<i class="fa fa-times"></i>
								<i class="fa fa-bars"></i>
							</div>
						</div>
			';
	  	}
	}
	echo '			</div>
				</div>
				<div id="videosList">
					<div class="searchDiv">
						<input type="text" value="' . $gridConf['search_query'] . '">
						<button class="button-primary"><i class="fa fa-search"></i></button>
						<div class="sorting">
							<p class="selectText">' . __('Select', 'wp_video_gallery') . ' :</p>
							<div class="selectAll">
								<img src="' . plugin_dir_url(__FILE__) . 'img/selectAll.svg' . '">
							</div>
							<div class="unselectAll">
								<img src="' . plugin_dir_url(__FILE__) . 'img/unselectAll.svg' . '">
							</div>
							<div class="sort">
								<p>' . __('Sort by', 'wp_video_gallery') . ' :</p>
								<div class="order active" data-order="alpha-asc" style="margin-left:0;">
									<i class="fa fa-sort-alpha-asc"></i>
								</div>
								<div class="order" data-order="alpha-desc">
									<i class="fa fa-sort-alpha-desc"></i>
								</div>
								<div class="order" data-order="date-desc">
									<i class="fa fa-clock-o"></i>
									<i class="fa fa-sort-desc"></i>
								</div>
								<div class="order" data-order="date-asc">
									<i class="fa fa-clock-o"></i>
									<i class="fa fa-sort-asc"></i>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="clearfix"></div>
					</div>
	';
	if ($days < 50) {
		echo '
					<div class="refreshVideos">
						<p>' . __('Refresh videos', 'wp_video_gallery') . ' : <i class="fa fa-refresh"></i></p>
						<div class="clearfix"></div>
					</div>
		';
	}
	echo '
					<div class="resultsCount">
						<p>' . __('Results', 'wp_video_gallery') . ': <span>' . count($allVideos) . '</span></p>
						<div class="clearfix"></div>
					</div>
					<div class="firstList">
						<div class="filtersList">
							<div id="account-popup" class="white-popup mfp-hide">
						  		<h2>' . __('This list contains all tags available in your videos on Vimeo', 'wp_video_gallery') . '</h2>
						  		<div class="actions" style="margin:0;">
						  			<p class="button-primary cancel">' . __('Close', 'wp_video_gallery') . '</p>
						  		</div>
							</div>
							<h3>' . __('Tags list', 'wp_video_gallery') . '<i class="fa fa-info-circle" aria-hidden="true"></i></h3>
	';
	$andOr = (array)NULL;
	$andOr[0] = $gridConf["filterType"] == 'OR' ? "" : " checked";
	$andOr[1] = $gridConf["filterType"] == 'OR' ? " checked" : "";
	echo '
							<form id="andOrFilter">
								<p>' . __('Tags combination', 'wp_video_gallery') . '</p>
		    					<div style="margin-right:25px;">
		    						<input type="radio" name="andOr" value="0"' . $andOr[0] . '/>' . __('AND', 'wp_video_gallery') . '
	    						</div>
		    					<div>
		    						<input type="radio" name="andOr" value="1"' . $andOr[1] . '/>' . __('OR', 'wp_video_gallery') . '
	    						</div>
							</form>
	';
	$gridConfFiltersCount = is_array($gridConf['tags']) ? count($gridConf['filters']) : 0;
	$allTagsChecked = $gridConfFiltersCount > 0 ? '' : " checked";
	echo '
							<div class="check-all-tags ' . $gridConfFiltersCount . '" data-filter="all-tags">
								<input type="checkbox"' . $allTagsChecked . '>
								<span>' . __('All tags', 'wp_video_gallery') . '</span>
							</div>
	';
	foreach ($allVideos as $key => $value) {
		$tags = unserialize($value->tags);
		if (count($tags) == 0 || (count($tags) == 1 && $tags[0]['name'] == $gridConf['search_query'])) {
			if (is_null($gridConf["filters"]))
				$noTagChecked = '';
			else
				$noTagChecked = in_array(".no-tag", $gridConf["filters"]) ? " checked" : "";
			echo '
							<div class="check-no-tag" data-filter=".no-tag">
								<input type="checkbox"' . $noTagChecked . '>
								<span>' . __('No tag', 'wp_video_gallery') . '</span>
							</div>
			';
			break;
		}
	}
	foreach (wp_video_galleryGetAllTags($gridConf['search_query']) as $key => $value) {
		$classSuf = preg_replace('/[^A-Za-z0-9-]+/', '-', $value);
		if (is_null($gridConf["filters"]))
			$thisTagChecked = "";
		else
			$thisTagChecked = in_array(".".$classSuf, $gridConf["filters"]) ? " checked" : "";
		echo '
							<div class="check-' . $classSuf . '" data-filter=".' . $classSuf . '">
								<input type="checkbox"' . $thisTagChecked . '>
								<span>' . $value . '</span>
							</div>
		';
	}
	echo '
						</div>
					</div>
					<div class="secondList">
						<div class="videos">
	';
	foreach ($selectedVideos as $key => $value) {
		$tags = unserialize($value->tags);
		$tagClasses = '';
		if (count($tags) > 0) {
			foreach ($tags as $tag) {
				$tagClasses .= ' ' . preg_replace('/[^A-Za-z0-9-]+/', '-', $tag['name']);
			}
		}
		else
			$tagClasses = ' no-tag';
		$id = explode("/", $value->uri);
		$id = $id[count($id) - 1];
		$vimeo_class = $value->source == 'youtube' ? '' : 'vimeo';
		echo '<div class="brick ' . $vimeo_class . ' selected item-' . $id . $tagClasses . '" data-id="' . $id . '">
				  <img class="thumb" data-src="' . $value->thumb_medium . '" width="765" height="574">
				  <div class="over"></div>
				  <p class="date" style="display:none;">' . $value->date_created . '</p>
				  <p class="name">' . $value->name . '</p>
				  <i class="fa fa-plus-circle"></i>
				  <i class="fa fa-times-circle"></i>
				  <i class="fa fa-check-circle"></i>
			  </div>';
	}
	foreach ($allVideos as $key => $value) {
		if (!in_array($value->uri, $selectedVideosUris)) {
			$tags = unserialize($value->tags);
			$tagClasses = '';
			if (count($tags) > 0) {
				foreach ($tags as $tag) {
					$tagClasses .= ' ' . preg_replace('/[^A-Za-z0-9-]+/', '-', $tag['name']);
				}
			}
			else
				$tagClasses = ' no-tag';
			$id = explode("/", $value->uri);
			$id = $id[count($id) - 1];
			$vimeo_class = $value->source == 'youtube' ? '' : 'vimeo';
			echo '<div class="brick ' . $vimeo_class . ' item-' . $id . $tagClasses . '" data-id="' . $id . '">
					  <img class="thumb" data-src="' . $value->thumb_medium . '" width="765" height="574">
					  <div class="over"></div>
					  <p class="date" style="display:none;">' . $value->date_created . '</p>
					  <p class="name">' . $value->name . '</p>
					  <i class="fa fa-plus-circle"></i>
					  <i class="fa fa-times-circle"></i>
					  <i class="fa fa-check-circle"></i>
				  </div>';
		}
	}
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '<div id="previewDiv">';
	echo '	<div class="firstList">';
	$duration = (int)$gridConf['duration'] != 0 ? (int)$gridConf['duration'] : 4000;
	$video_width = (int)$gridConf['video_width'] != 0 ? (int)$gridConf['video_width'] : 350;
	$video_spacing_x = (int)$gridConf['video_spacing_x'];
	$video_spacing_y = (int)$gridConf['video_spacing_y'];
	$enable_css_animation = (int)$gridConf['enable_css_animation'];
	$animation_delay = (int)$gridConf['animation_delay'];
	
	$text_color       = $gridConf['text_color'] ? $gridConf['text_color'] : "#FFF";
	$text_color_hover = $gridConf['text_color_hover'] ? $gridConf['text_color_hover'] : "#000";
	$box_color        = $gridConf['box_color'] ? $gridConf['box_color'] : "#FFF";
	$box_color_initial = $gridConf['box_color_initial'] ? $gridConf['box_color_initial'] : "rgba(0,0,0,0.3)";
	$display_trigger_0 = (int)$gridConf['display-trigger'] == 0 ? 'checked' : '';
	$display_trigger_1 = (int)$gridConf['display-trigger'] == 1 ? 'checked' : '';
	$display_trigger_0 = (int)$gridConf['display-trigger'] == 0 ? 'checked' : '';
	$title_box_mode_0 = (int)$gridConf['title-box-mode'] == 0 ? 'checked' : '';
	$title_box_mode_1 = (int)$gridConf['title-box-mode'] == 1 ? 'checked' : '';
	$enable_css_animation = $enable_css_animation ? 'checked' : '';

	echo '		<div class="themeList accordion open">
					<h3>' . __('Theme', 'wp_video_gallery') . '<i class="fa fa-caret-down" aria-hidden="true"></i></h3>
					<div class="inAccordion">
						<form id="themeForm">
						    <label><input type="radio" name="theme" value="Classic"' . $whichTheme[0] . '/>' . __('Classic', 'wp_video_gallery') . '</label><br>
						    <label><input type="radio" name="theme" value="Mosaic"' . $whichTheme[1] . '/>' . __('Mosaic', 'wp_video_gallery') . '</label><br>
						    <label><input type="radio" name="theme" value="Slider"' . $whichTheme[2] . '/>' . __('Slider', 'wp_video_gallery') . '</label>
						</form>
					</div>
				</div>
				<div class="titleBox accordion close">
					<h3>' . __('Title box', 'wp_video_gallery') . '<i class="fa fa-caret-down" aria-hidden="true"></i></h3>
					<div class="inAccordion">
						<div style="margin-bottom: 10px;">
							<label>' . __("Display mode", 'wp_video_gallery') . ' :</label><br>
							<label><input type="radio" name="title-box-mode" value="0"' . $title_box_mode_0 . '/>' . __('Display video title', 'wp_video_gallery') . '</label><br>
						    <label><input type="radio" name="title-box-mode" value="1"' . $title_box_mode_1 . '/>' . __('Display play icon', 'wp_video_gallery') . '</label>
						</div>
						<div>
							<label>' . __("Text's/button's color", 'wp_video_gallery') . ' :</label>
							<input id="colorSelector1" class="colorSelector" value="' . $text_color . '">
							<div class="clearfix"></div>
						</div>
						<div>
							<label>' . __('Text/button color on hover', 'wp_video_gallery') . ' :</label>
							<input id="colorSelector3" class="colorSelector" value="' . $text_color_hover . '">
							<div class="clearfix"></div>
						</div>
						<div>
							<label>' . __("Box's color", 'wp_video_gallery') . ' :</label>
							<input id="colorSelector4" class="colorSelector" value="' . $box_color_initial . '">
							<div class="clearfix"></div>
						</div>
						<div>
							<label>' . __("Box's color on hover", 'wp_video_gallery') . ' :</label>
							<input id="colorSelector5" class="colorSelector" value="' . $box_color . '">
							<div class="clearfix"></div>
						</div>
						<div>
							<i>' . __("Tip : you can set all the color options with an opacity of 0 to hide the box", "wp_video_gallery") . '</i>
						</div>
						<p class="apply_colors button-primary">' . __('Apply colors', 'wp_video_gallery') . '</p>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="tagsList accordion close" ' . $hideConfig[0] . '>
					<h3>' . __('Tags', 'wp_video_gallery') . '<i class="fa fa-caret-down" aria-hidden="true"></i></h3>
					<div class="inAccordion">
						<form id="showTags">
							<p>' . __('Show tags filter', 'wp_video_gallery') . ' :</p>
						    <label><input type="radio" name="tags" value="0"' . $showTag[0] . '/>' . __('No', 'wp_video_gallery') . '</label><br>
						    <label><input type="radio" name="tags" value="1"' . $showTag[1] . '/>' . __('Top', 'wp_video_gallery') . '</label><br>
						    <label><input type="radio" name="tags" value="2"' . $showTag[2] . '/>' . __('Left', 'wp_video_gallery') . '</label><br>
						    <label><input type="radio" name="tags" value="3"' . $showTag[3] . '/>' . __('Right', 'wp_video_gallery') . '</label>
						</form>
						<div class="chooseTags">
							<p>' . __('Choose the tags to display', 'wp_video_gallery') . ' :</p>
							<button class="button-primary">' . __('Choose', 'wp_video_gallery') . '</button>
						</div>
						<div class="tagsColor">
							<label>' . __('Choose the tags color', 'wp_video_gallery') . ' :</label>
							<input id="colorSelector2" class="colorSelector" value="' . $gridConf['tags_color'] . '">
							<div class="clearfix"></div>
						</div>
						<p class="apply_colors button-primary">' . __('Apply colors', 'wp_video_gallery') . '</p>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="configList accordion close" ' . $hideConfig[0] . '>
					<h3>' . __('Videos customization', 'wp_video_gallery') . '<i class="fa fa-caret-down" aria-hidden="true"></i></h3>
					<div class="inAccordion">
						<div class="videoWidth">
							<div id="videoSimpleWidth"><span>' . __("Single element's width", 'wp_video_gallery') . ' :</span><input onkeypress="return event.charCode >= 48 && event.charCode <= 57" style="margin-left:0px;" type="text" value="' . $video_width . '"><span>px.</span></div>
							<p>' . __('Spacing between elements', 'wp_video_gallery') . ' :</p>
							<div id="videoSpacingX"><span>' . __('X spacing :', 'wp_video_gallery') . '</span><input onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="text" value="' . $video_spacing_x . '"><span>px.</span></div>
							<div id="videoSpacingY"><span>' . __('Y spacing :', 'wp_video_gallery') . '</span><input onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="text" value="' . $video_spacing_y . '"><span>px.</span></div>
						</div>
					</div>
				</div>
				<div class="animationList accordion close" ' . $hideConfig[0] . '>
					<h3>' . __('Animations', 'wp_video_gallery') . '<i class="fa fa-caret-down" aria-hidden="true"></i></h3>
					<div class="inAccordion">
						<div class="CSSAnimation videoWidth">
							<p>' . __('Blocks delay (in millisecondes)', 'wp_video_gallery') . ' :</p>
							<input class="animation_delay" onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="text" value="' . $animation_delay . '"><br>
							<i>' . __("You can enter 0 in order to display all videos at once", "wp_video_gallery") . '.</i>
							<p>' . __('Enable animations', 'wp_video_gallery') . ' :</p>
							<input type="checkbox" class="enable_css_animation" ' . $enable_css_animation . ' style="width:auto;">
							<p>' . __('Animation', 'wp_video_gallery') . ' :</p>
							' . wp_video_gallery_get_animate_css_select($gridConf['animation']) . '
						</div>
					</div>
				</div>
				<div class="loadingBox accordion close">
					<h3>' . __('Display triggers', 'wp_video_gallery') . '<i class="fa fa-caret-down" aria-hidden="true"></i></h3>
					<div class="inAccordion">
						<div>
							<label style="margin-bottom:5px;display:inline-block;">' . __("How do you want to trigger the gallery's display ?", 'wp_video_gallery') . ' :</label><br>
							<label><input type="radio" name="display-trigger" value="0" ' . $display_trigger_0 . ' />' . __('On page load', 'wp_video_gallery') . '</label><br>
						    <label><input type="radio" name="display-trigger" value="1" ' . $display_trigger_1 . '/>' . __('On scroll to gallery', 'wp_video_gallery') . '</label>
							<div class="clearfix"></div>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="sliderList accordion close"' . $hideConfig[1] . '>
					<h3>' . __('Slider settings', 'wp_video_gallery') . '<i class="fa fa-caret-down" aria-hidden="true"></i></h3>
					<div class="inAccordion">
						<div id="sliderOption">
						    <span>' . __('Slider duration', 'wp_video_gallery') . ' :</span><input onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="text" class="duration" value="' . $duration . '"/>' . __('ms', 'wp_video_gallery') . '.
						    <form class="formTitle">
						    	<p>' . __("Display active video's title under the slider", 'wp_video_gallery') . ' :</p>
						    	<label><input type="radio" name="showTitle" value="0"' . $showTitle[0] . '/>' . __('No', 'wp_video_gallery') . '</label><br>
					    		<label><input type="radio" name="showTitle" value="1"' . $showTitle[1] . '/>' . __('Yes', 'wp_video_gallery') . '</label>
						    </form>
						    <form class="formPage">
						    	<p>' . __('Display the pagination', 'wp_video_gallery') . ' :</p>
						    	<label><input type="radio" name="showPage" value="0"' . $showPage[0] . '/>' . __('No', 'wp_video_gallery') . '</label><br>
					    		<label><input type="radio" name="showPage" value="1"' . $showPage[1] . '/>' . __('Yes', 'wp_video_gallery') . '</label>
						    </form>
						</div>
					</div>
				</div>
				<div class="customCSS accordion close">
					<h3>' . __('Custom CSS', 'wp_video_gallery') . '<i class="fa fa-caret-down" aria-hidden="true"></i></h3>
					<div class="inAccordion">
						<textarea>' . $gridConf['custom_css'] . '</textarea>
					</div>
				</div>
			</div>
			<div class="secondList">
				<div class="pContent"></div>
			</div>';

	echo '</div>';
	echo '</div>';
	echo "</div>";
	echo '	<div class="tagsModal">
				<div class="modal-content">
					<h2>' . __('Select the tags you want to display with your portfolio', 'wp_video_gallery') . '</h2>
					<table>';
	$modalTags = array_merge(wp_video_galleryGetAllGridTagsInOrder($gridId));
	$nn = (int) (count($modalTags) / 3) + 1;
	for ($i = 0; $i < $nn; $i++) {
		echo '	<tr>';
		$j = 0;
		if (isset($modalTags[$i])) {
			if (is_array($gridConf['tags']) && count($gridConf['tags'])) {
				foreach ($gridConf['tags'] as $tag) {
					if ($tag[0] == $modalTags[$i] && $tag[1] == 0) {
						echo '<td><label><input type="checkbox"><span>' . $modalTags[$i] . '</span></label></td>';
						$j = 1;
					}
				}
			}
			if ($j == 0)
				echo '	<td><label><input type="checkbox" checked><span>' . $modalTags[$i] . '</span></label></td>';
			$j = 0;
		}
		if (isset($modalTags[$i + $nn])) {
			if (is_array($gridConf['tags']) && count($gridConf['tags'])) {
				foreach ($gridConf['tags'] as $tag) {
					if ($tag[0] == $modalTags[$i + $nn] && $tag[1] == 0) {
						echo '<td><label><input type="checkbox"><span>' . $modalTags[$i + $nn] . '</span></label></td>';
						$j = 1;
					}
				}
			}
			if ($j == 0)
				echo '<td><label><input type="checkbox" checked><span>' . $modalTags[$i + $nn] . '</span></label></td>';
			$j = 0;
		}
		else {
			echo '	<td></td>';
		}
		if (isset($modalTags[$i + 2 * $nn])) {
			if (is_array($gridConf['tags']) && count($gridConf['tags'])) {
				foreach ($gridConf['tags'] as $tag) {
					if ($tag[0] == $modalTags[$i + 2 * $nn] && $tag[1] == 0) {
						echo '<td><label><input type="checkbox"><span>' . $modalTags[$i + 2 * $nn] . '</span></label></td>';
						$j = 1;
					}
				}
			}
			if ($j == 0)
				echo '<td><label><input type="checkbox" checked><span>' . $modalTags[$i + 2 * $nn] . '</span></label></td>';
			$j = 0;
		}
		else {
			echo '	<td></td>';
		}
		echo '</tr>';
	}
	echo '			</table>
					<button class="button-primary save">' . __('Save', 'wp_video_gallery') . '</button>
					<button class="button-primary cancel">' . __('Cancel', 'wp_video_gallery') . '</button>
				</div>
			</div>';
}