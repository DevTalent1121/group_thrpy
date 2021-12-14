<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( !class_exists( 'avia_sc_wp_video_gallery' ) && defined('MSWP_AVERTA_VERSION'))
{
  class avia_sc_wp_video_gallery extends aviaShortcodeTemplate
	{
			static $slide_count = 0;

			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['name']		= __('WP Video Gallery', 'wp_video_gallery' );
				$this->config['tab']		= __('Plugin Additions', 'avia_framework' );
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-slideshow-layer.png";
				$this->config['order']		= 9;
				$this->config['target']		= 'avia-target-insert';
				$this->config['shortcode'] 	= 'av_wp_video_gallery';
				$this->config['tooltip'] 	= __('Insert an WP Video Gallery portfolio/slider', 'wp_video_gallery' );
				$this->config['tinyMCE'] 	= array('disable' => "true");
				$this->config['drag-level'] = 3;
			}


			/**
			 * Register fullwidth shortcode
			 */
			function extra_assets()
			{
				AviaBuilder::$full_el_no_section[] = $this->config['shortcode'];
				AviaBuilder::$full_el[] = $this->config['shortcode'];
			}


			/**
			 * Editor Element - this function defines the visual appearance of an element on the AviaBuilder Canvas
			 * Most common usage is to define some markup in the $params['innerHtml'] which is then inserted into the drag and drop container
			 * Less often used: $params['data'] to add data attributes, $params['class'] to modify the className
			 *
			 *
			 * @param array $params this array holds the default values for $content and $args.
			 * @return $params the return array usually holds an innerHtml key that holds item specific markup.
			 */
			function editor_element($params)
			{
				//fetch all registered slides and save them to the slides array
                $gridsC = get_wp_video_gallery_grids();
                $grids = (array)NULL;
                foreach ($gridsC as $value) {
                	$grids[$value['name']] = $value['id'];
                }
				if(empty($params['args']['id']) && is_array($grids)) $params['args']['id'] = reset($grids);

                $element = array(
					'subtype' => $grids,
					'type'=>'select',
					'std' => $params['args']['id'],
					'class' => 'avia-recalc-shortcode',
					'data'	=> array('attr'=>'id')
				);

				$inner		 = "<img src='".$this->config['icon']."' title='".$this->config['name']."' />";


				if(empty($grids))
				{
					$inner.= "<div><a target='_blank' href='".admin_url( 'admin.php?page=wp_video_gallery-new-grids' )."'>".__('No portfolio Found. Click here to create a new one','wp_video_gallery' )."</a></div>";
				}
				else
				{
					$inner .= "<div class='avia-element-label'>".$this->config['name']."</div>";
					$inner .= AviaHtmlHelper::render_element($element);
					$inner .= "<a target='_blank' href='".admin_url( 'admin.php?page=wp_video_gallery-edit-grids' )."'>".__('Edit your portfolios here','wp_video_gallery' )."</a>";
				}


				$params['class'] = "av_sidebar";
				$params['content']	 = NULL;
				$params['innerHtml'] = $inner;

				return $params;
			}

			/**
			 * Frontend Shortcode Handler
			 *
			 * @param array $atts array of attributes
			 * @param string $content text within enclosing form of shortcode element
			 * @param string $shortcodename the shortcode found, when == callback name
			 * @return string $output returns the modified html string
			 */
			function shortcode_handler($atts, $content = "", $shortcodename = "", $meta = "")
			{
				require_once plugin_dir_path(__FILE__) . '../get-front-grid.php';

				$grid = wp_video_gallery_get_grid((int)$atts["id"]);
				if (!wp_script_is( 'froogaloop2', 'enqueued' )) {
					wp_enqueue_script('froogaloop2');
				}
				if (!wp_style_is('font-awesome', 'enqueued')) {
					wp_enqueue_style('font-awesome');
				}
				if (!wp_script_is( 'wp_video_gallery-front-js', 'enqueued' )) {
					wp_enqueue_script('wp_video_gallery-front-js');
				}
				if (!wp_style_is('lazy-loading', 'enqueued')) {
					wp_enqueue_script('lazy-loading');
				}
				wp_enqueue_style('lightbox-css');
				wp_enqueue_style('animate-css');
				wp_enqueue_script('lightbox-js');
				switch ($grid['theme']) {
					case 'Slider':
						if (!wp_style_is('bxslider-css', 'enqueued')) {
							wp_enqueue_style('bxslider-css');
						}
						if (!wp_script_is( 'bxslider-js', 'enqueued' )) {
							wp_enqueue_script('bxslider-js');
						}
						break;
					default:
						if (!wp_script_is( 'freewall', 'enqueued' )) {
							wp_enqueue_script('freewall');
						}
						if (!wp_style_is( 'wp_video_gallery-front-css', 'enqueued' )) {
							wp_enqueue_style('wp_video_gallery-front-css');
						}
						break;
				}

				$output  = wp_video_gallery_get_front_grid((int)$atts["id"]);
				return $output;
			}

	}
}
