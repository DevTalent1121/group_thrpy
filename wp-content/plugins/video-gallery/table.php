<?php
/**
 * Projects waiting for admin approval Class
 * By: Younes :)
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Load WP_List_Table if not loaded
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Vimeo_Majba_Table extends WP_List_Table {

	
	function videos_data() {
		global $wpdb;
		$videos_data = array();
		$output = array();

		$videos_data = wp_video_galleryGetAllGrids();
		$i = 0;
		if (count($videos_data)) {
			while ($videos_data[$i]) {
				$videos_data[$i]["name"] = '<a class="titleLink" href="?page=wp_video_gallery-edit-grids&id=' . $videos_data[$i]['id'] . '">'.$videos_data[$i]['name'].'</a>';
				$i++;
			}
		}
		return $videos_data;

	}

	function column_default( $item, $column_name ) {
		switch( $column_name ) {
			case "name":
				$actions = array(
			    	'edit'      => sprintf('<a href="?page=wp_video_gallery-edit-grids&id=' . $item['id'] . '">' . __('Edit', 'wp_video_gallery') . '</a>'),
			    	'duplicate'      => sprintf('<a href="#" class="duplicate-grid" data-id="' . $item['id'] . '">' . __('Duplicate', 'wp_video_gallery') . '</a>'),
			    	'delete'      => sprintf('<a href="#" class="removeGrid" data-text="' . __('Are you sure you want to remove completely : ', 'wp_video_gallery') . '" data-id="' . $item['id'] . '">' . __('Delete', 'wp_video_gallery') . '</a>')
		    	);
		    	return sprintf($item[$column_name].' %1$s', $this->row_actions($actions) );
		    	break;
		    case "shortcode":
		    	return '[wp_video_gallery_grid id=' . __($item["id"], 'wp_video_gallery') . ']';
		    	break;
		}
		return $item[$column_name];
	}

	function get_columns(){
	  $columns = array(
	    'id'          => 'ID',
	    'name'        => __('Title', 'wp_video_gallery'),
	    'shortcode'   => __('Shortcode', 'wp_video_gallery'),
	    'theme'	      => __('Theme', 'wp_video_gallery')
	  );
	  return $columns;
	}

	function prepare_items() {
	  $columns = $this->get_columns();
	  $hidden = array();
	  $sortable = $this->get_sortable_columns();
	  $this->_column_headers = array($columns, $hidden, $sortable);
	  $data = $this->videos_data();
	  $search_data = (array) NULL;
	  $search_data = $data;
	  $per_page = 20;
	  $current_page = $this->get_pagenum();
	  $total_items = count($data);
	  $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
	  $this->set_pagination_args( array(
	    'total_items' => $total_items,
	    'per_page'    => $per_page
	  ) );
	  $this->items = $data;
	}

	function no_items() {
		echo __('There are no portfolios', 'wp_video_gallery');
	}

	function get_sortable_columns() {
		$sortable_columns = array();
		return $sortable_columns;
	}
}