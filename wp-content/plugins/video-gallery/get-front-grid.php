<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
function wp_video_gallery_get_front_grid($gridId) {
	$grid = wp_video_gallery_get_grid($gridId);
	switch ($grid['theme']) {
		case 'Mosaic':
			$output = get_front_grid_mosaic($gridId);
			break;
		case 'Slider':
			$output = get_front_grid_slider($gridId);
			break;
		default:
			$output = get_front_grid_default($gridId);
			break;
	}
	return $output;
}


function get_front_grid_mosaic($gridId) {
	$selectedVideos = wp_video_galleryGetSelectedVideos($gridId);
	$videos = (array)NULL;
	$grid = wp_video_gallery_get_grid($gridId);
	$tagsPosition = '';
	$gridConf = unserialize($grid['configuration']);
	$videoWidthBlock = (int)$gridConf["video_width"];
	$videoSpacingX = (int)$gridConf["video_spacing_x"];
	$videoSpacingY = (int)$gridConf["video_spacing_y"];
	$animation_delay = (int)$gridConf['animation_delay'];
	$display_trigger_class = (int)$gridConf['display-trigger'] ? ' load-on-scroll-to' : ' ';
	if ($videoWidthBlock == 0)
		$videoWidthBlock = 350;
	foreach ($selectedVideos as $key => $value)
		$selectedVideos[$key]->big = wp_video_galleryBigOrNot($gridId, $value->uri);
	$output = get_style($gridId);
	$output .= '<div class="gridContainer-' . $gridId . '" style="display:none;" data-videowidthblock="' . $videoWidthBlock . '" data-videospacingy="' . $videoSpacingY . '" data-videospacingx="' . $videoSpacingX . '" data-animationdelay="' . $animation_delay . '">';
	$tagsStyle     = ' style="color:'.$gridConf['tags_color'].';"';
	//$textStyle     = ' style="color:'.$gridConf['text_color'].';"';
	switch ((int)$gridConf['show_tags']) {
		case 0:
			$tagsPosition = "noTags";
			break;
		case 1:
			$tagsPosition = "topTags";
			break;
		case 2:
			$tagsPosition = "leftTags";
			break;
		case 3:
			$tagsPosition = "rightTags";
			break;
	}
	$modalTags = array_merge(wp_video_galleryGetAllGridTagsInOrder($gridId));
	if (!count($modalTags))
		$gridConf['show_tags'] = 0;
	$search_query = $gridConf['search_query'];
	if ((int)$gridConf['show_tags'] != 3 && (int)$gridConf['show_tags'] != 0) {
		$output .= '<div class="tags ' . $tagsPosition . '"' . $tagsStyle . '>
						<div class="check-all-tags" data-filter="all-tags"><div class="checkboxContainer checked">' . wp_video_galleryGetUncheckedSpe($gridConf['tags_color']) . wp_video_galleryGetCheckedSpe($gridConf['tags_color']) . '<input type="checkbox" checked></div><span>' . __('All tags', 'wp_video_gallery') . '</span></div>';
		foreach ($selectedVideos as $key => $value) {
			$tags = unserialize($value->tags);
			if (count($tags) == 0 || (count($tags) == 1 && $tags[0]['name'] == $search_query)) {
				$output .= '<div class="check-no-tag" data-filter=".no-tag"><div class="checkboxContainer">' . wp_video_galleryGetUncheckedSpe($gridConf['tags_color']) . wp_video_galleryGetCheckedSpe($gridConf['tags_color']) . '<input type="checkbox"></div><span>' . __('No tag', 'wp_video_gallery') . '</span></div>';
				break;
			}
		}
		foreach (wp_video_galleryGetFrontGridTags($gridId) as $key => $value) {
			$classSuf = preg_replace('/[^A-Za-z0-9-]+/', '-', $value);
			$output .= '<div class="check-' . $classSuf . '" data-filter=".' . $classSuf . '"><div class="checkboxContainer">' . wp_video_galleryGetUncheckedSpe($gridConf['tags_color']) . wp_video_galleryGetCheckedSpe($gridConf['tags_color']) . '<input type="checkbox"></div><span>' . $value . '</span></div>';
		}
		$output .= '</div>';
	}
	$output .= '<div class="videosListFront ' . $tagsPosition . $display_trigger_class . '" data-id="' . $gridId . '">';
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
		$bigClass = $value->big == 1 ? ' highlight' : '';
		$vimeo_class = $value->source == 'youtube' ? '' : 'vimeo';
		$aditionalParentClass = "";
		if ((int)$gridConf["title-box-mode"] == 1)
			$aditionalParentClass = "with-play";
		$output .= '<div class="brick ' . $vimeo_class . ' animated selected item-' . $id . $tagClasses . $bigClass . '" data-id="' . $id . '">
				  <img class="thumb" data-src="' . $value->thumb_big . '">
				  <div class="over"></div>
				  <table class="name"><tr><td><table class="parent ' . $aditionalParentClass . '"><tr><td><p' . $textStyle . '>' . ((int)$gridConf["title-box-mode"] == 0 ? $value->name : "<i class='fa fa-play'></i>") . '</p></td></tr></table></td></tr></table>
			  </div>';
	}
	$output .= '	</div>';
	if ((int)$gridConf['show_tags'] == 3) {
		$output .= '<div class="tags ' . $tagsPosition . '"' . $tagsStyle .'>
						<div class="check-all-tags" data-filter="all-tags"><div class="checkboxContainer checked">' . wp_video_galleryGetUncheckedSpe($gridConf['tags_color']) . wp_video_galleryGetCheckedSpe($gridConf['tags_color']) . '<input type="checkbox" checked></div><span>' . __('All tags', 'wp_video_gallery') . '</span></div>';
		foreach ($selectedVideos as $key => $value) {
			$tags = unserialize($value->tags);
			if (count($tags) == 0 || (count($tags) == 1 && $tags[0]['name'] == $search_query)) {
				$output .= '<div class="check-no-tag" data-filter=".no-tag"><div class="checkboxContainer">' . wp_video_galleryGetUncheckedSpe($gridConf['tags_color']) . wp_video_galleryGetCheckedSpe($gridConf['tags_color']) . '<input type="checkbox"></div><span>' . __('No tag', 'wp_video_gallery') . '</span></div>';
				break;
			}
		}
		foreach (wp_video_galleryGetFrontGridTags($gridId) as $key => $value) {
			$classSuf = preg_replace('/[^A-Za-z0-9-]+/', '-', $value);
			$output .= '<div class="check-' . $classSuf . '" data-filter=".' . $classSuf . '"><div class="checkboxContainer">' . wp_video_galleryGetUncheckedSpe($gridConf['tags_color']) . wp_video_galleryGetCheckedSpe($gridConf['tags_color']) . '<input type="checkbox"></div><span>' . $value . '</span></div>';
		}
		$output .= '</div>';
	}
	$output .= '</div>';
	return $output;
}

function get_front_grid_default($gridId) {
	$selectedVideos = wp_video_galleryGetSelectedVideos($gridId);
	$videos = (array)NULL;
	$grid = wp_video_gallery_get_grid($gridId);
	$tagsPosition = '';
	$gridConf = unserialize($grid['configuration']);
	$videoWidthBlock = (int)$gridConf["video_width"];
	$videoSpacingX = (int)$gridConf["video_spacing_x"];
	$videoSpacingY = (int)$gridConf["video_spacing_y"];
	$animation_delay = (int)$gridConf['animation_delay'];
	$display_trigger_class = (int)$gridConf['display-trigger'] ? ' load-on-scroll-to' : ' ';
	if ($videoWidthBlock == 0)
		$videoWidthBlock = 350;
	foreach ($selectedVideos as $key => $value)
		$selectedVideos[$key]->big = wp_video_galleryBigOrNot($gridId, $value->uri);
	$output = get_style($gridId);
	$output .= '<div class="gridContainer-' . $gridId . '" style="display:none;" data-videowidthblock="' . $videoWidthBlock . '" data-videospacingy="' . $videoSpacingY . '" data-videospacingx="' . $videoSpacingX . '" data-animationdelay="' . $animation_delay . '">';
	$tagsStyle     = ' style="color:'.$gridConf['tags_color'].';"';
	//$textStyle     = ' style="color:'.$gridConf['text_color'].';"';
	switch ((int)$gridConf['show_tags']) {
		case 0:
			$tagsPosition = "noTags";
			break;
		case 1:
			$tagsPosition = "topTags";
			break;
		case 2:
			$tagsPosition = "leftTags";
			break;
		case 3:
			$tagsPosition = "rightTags";
			break;
	}
	$modalTags = array_merge(wp_video_galleryGetAllGridTagsInOrder($gridId));
	if (!count($modalTags))
		$gridConf['show_tags'] = 0;
	if ((int)$gridConf['show_tags'] != 3 && (int)$gridConf['show_tags'] != 0) {
		$output .= '<div class="tags ' . $tagsPosition . '"' . $tagsStyle .'>
						<div class="check-all-tags" data-filter="all-tags"><div class="checkboxContainer checked">' . wp_video_galleryGetUncheckedSpe($gridConf['tags_color']) . wp_video_galleryGetCheckedSpe($gridConf['tags_color']) . '<input type="checkbox" checked></div><span>' . __('All tags', 'wp_video_gallery') . '</span></div>';
		foreach ($selectedVideos as $key => $value) {
			$tags = unserialize($value->tags);
			if (count($tags) == 0 || (count($tags) == 1 && $tags[0]['name'] == $search_query)) {
				$output .= '<div class="check-no-tag" data-filter=".no-tag"><div class="checkboxContainer">' . wp_video_galleryGetUncheckedSpe($gridConf['tags_color']) . wp_video_galleryGetCheckedSpe($gridConf['tags_color']) . '<input type="checkbox"></div><span>' . __('No tag', 'wp_video_gallery') . '</span></div>';
				break;
			}
		}
		foreach (wp_video_galleryGetFrontGridTags($gridId) as $key => $value) {
			$classSuf = preg_replace('/[^A-Za-z0-9-]+/', '-', $value);
			$output .= '<div class="check-' . $classSuf . '" data-filter=".' . $classSuf . '"><div class="checkboxContainer">' . wp_video_galleryGetUncheckedSpe($gridConf['tags_color']) . wp_video_galleryGetCheckedSpe($gridConf['tags_color']) . '<input type="checkbox"></div><span>' . $value . '</span></div>';
		}
		$output .= '</div>';
	}
	$output .= '<div class="videosListFront ' . $tagsPosition . $display_trigger_class . '" data-id="' . $gridId . '">';
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
		$bigClass = '';
		$vimeo_class = $value->source == 'youtube' ? '' : 'vimeo';
		if ((int)$gridConf["title-box-mode"] == 1)
			$aditionalParentClass = "with-play";
		$output .= '<div class="brick ' . $vimeo_class . ' animated selected item-' . $id . $tagClasses . $bigClass . '" data-id="' . $id . '">
				  <img class="thumb" data-src="' . $value->thumb_big . '">
				  <div class="over"></div>
				  <table class="name"><tr><td><table class="parent ' . $aditionalParentClass . '"><tr><td><p' . $textStyle . '>' . ((int)$gridConf["title-box-mode"] == 0 ? $value->name : "<i class='fa fa-play'></i>") . '</p></td></tr></table></td></tr></table>
			  </div>';
	}
	$output .= '	</div>';
	if ((int)$gridConf['show_tags'] == 3) {
		$output .= '<div class="tags ' . $tagsPosition . '"' . $tagsStyle .'>
						<div class="check-all-tags" data-filter="all-tags"><div class="checkboxContainer checked">' . wp_video_galleryGetUncheckedSpe($gridConf['tags_color']) . wp_video_galleryGetCheckedSpe($gridConf['tags_color']) . '<input type="checkbox" checked></div><span>' . __('All tags', 'wp_video_gallery') . '</span></div>';
		foreach ($selectedVideos as $key => $value) {
			$tags = unserialize($value->tags);
			if (count($tags) == 0 || (count($tags) == 1 && $tags[0]['name'] == $search_query)) {
				$output .= '<div class="check-no-tag" data-filter=".no-tag"><div class="checkboxContainer">' . wp_video_galleryGetUncheckedSpe($gridConf['tags_color']) . wp_video_galleryGetCheckedSpe($gridConf['tags_color']) . '<input type="checkbox"></div><span>' . __('No tag', 'wp_video_gallery') . '</span></div>';
				break;
			}
		}
		foreach (wp_video_galleryGetFrontGridTags($gridId) as $key => $value) {
			$classSuf = preg_replace('/[^A-Za-z0-9-]+/', '-', $value);
			$output .= '<div class="check-' . $classSuf . '" data-filter=".' . $classSuf . '"><div class="checkboxContainer">' . wp_video_galleryGetUncheckedSpe($gridConf['tags_color']) . wp_video_galleryGetCheckedSpe($gridConf['tags_color']) . '<input type="checkbox"></div><span>' . $value . '</span></div>';
		}
		$output .= '</div>';
	}
	$output .= '</div>';
	return $output;
}

function get_front_grid_slider($gridId) {
	$grid = wp_video_gallery_get_grid($gridId);
	$gridConf   = unserialize($grid['configuration']);
	$duration   = (int)$gridConf['duration'] != 0 ? (int)$gridConf['duration'] : 4000;
	$pagination = (int)$gridConf['show_pager'];
	//$textStyle  = ' style="color:'.$gridConf['text_color'].';"';
	$selectedVideos = wp_video_galleryGetSelectedVideos($gridId);
	$videos = (array)NULL;
	foreach ($selectedVideos as $key => $value)
		$selectedVideos[$key]->big = wp_video_galleryBigOrNot($gridId, $value->uri);
	$output = get_style($gridId, 2);
	$output .= '	<ul class="bxslider" style="display:none;" data-duration="' . $duration . '" data-pagination="' . $pagination . '" data-title="' . (int)$gridConf['show_title'] . '">';
	foreach ($selectedVideos as $key => $value) {
		$id = explode("/", $value->uri);
		$vimeo_class = $value->source == 'youtube' ? '' : 'vimeo';
		if ($value->source != 'youtube')
			$id = (int)$id[count($id) - 1];
		else
			$id = $id[count($id) - 1];
		$tableOver = '	<table class="parent">
							<tr><td><p' . $textStyle . '>' . $value->name . '</p></td></tr>
						</table>';
		$output .= '<li class="image-list">
						<div class="image-slide ' . $vimeo_class . '" data-index="' . $key . '" data-id="' . $id . '" style="background-image:url(' . $value->thumb_big . ');">
							<table>
								<tr class="video">
									<td></td>
								</tr>
								<tr class="over"><td></td></tr>
								<tr class="text">
									<td>
										' . $tableOver . '
									</td>
								</tr>
							</table>
						</div>';
		if ((int)$gridConf['show_title']) {
			$output .= '<p class="slideTitle">' . $value->name . '</p>';
		}
		$output .= '</li>';
	}
	$output .= '</ul>';
	$output .= '<div class="pagerImage"><img src=""></div>';
	return $output;
}

function get_style($gridId, $type = 1) {
	$grid = wp_video_gallery_get_grid($gridId);
	$gridConf   = unserialize($grid['configuration']);
	$animation = "";
	if ((int)$gridConf['enable_css_animation']) {
		$animation = '
				div.gridContainer-' . $gridId . ' .videosListFront .brick[data-state="start"] {
					display: block;
					-webkit-animation-name: ' . $gridConf["animation"] . ';
				    animation-name: ' . $gridConf["animation"] . ';
				}
		';
	}
	if ($type == 1) {
		$style = '
			<style>
				' . $gridConf['custom_css'] . '
				div.gridContainer-' . $gridId . ' .videosListFront .brick .name .parent {
					background-color: ' . ((int)$gridConf["title-box-mode"] == 0 ? $gridConf['box_color_initial'] : "transparent") . ' !important;
				}
				div.gridContainer-' . $gridId . ' .videosListFront .brick .name .parent p {
					color: ' . $gridConf['text_color'] . ' !important;
				}
				div.gridContainer-' . $gridId . ' .videosListFront .brick .name:hover .parent p {
					color: ' . $gridConf['text_color_hover'] . ' !important;
				}
				div.gridContainer-' . $gridId . ' .videosListFront .brick .name:hover .parent {
					background-color: ' . ((int)$gridConf["title-box-mode"] == 0 ? $gridConf['box_color'] : "transparent") . ' !important;
				}
				' . $animation . '
			</style>
		';
	}
	else {
		$style = '
			<style>
				' . $gridConf['custom_css'] . '
				.bx-viewport .image-slide .parent p {
					color: ' . $gridConf['text_color'] . ' !important;
				}
				.bx-viewport .image-slide .parent:hover p {
					color: ' . $gridConf['text_color_hover'] . ' !important;
				}
				.bx-viewport .image-slide .parent:hover {
					background-color: ' . $gridConf['box_color'] . ' !important;	
				}
				.bx-viewport .image-slide .parent {
					background-color: ' . $gridConf['box_color_initial'] . ' !important;	
				}
			</style>
		';
	}
	return $style;
}