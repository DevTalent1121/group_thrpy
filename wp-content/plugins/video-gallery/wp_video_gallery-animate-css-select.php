<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
function wp_video_gallery_get_animate_css_select($val) {
  $values = array('bounce', 'flash', 'pulse', 'rubberBand', 'shake', 'swing', 'tada', 'wobble', 'jello', 'bounceIn', 'bounceInDown', 'bounceInLeft', 'bounceInRight', 'bounceInUp', 'fadeIn', 'fadeInDown', 'fadeInDownBig', 'fadeInLeft', 'fadeInLeftBig', 'fadeInRight', 'fadeInRightBig', 'fadeInUp', 'fadeInUpBig', 'flip', 'flipInX', 'flipInY', 'lightSpeedIn', 'rotateIn', 'rotateInDownLeft', 'rotateInDownRight', 'rotateInUpLeft', 'rotateInUpRight', 'slideInUp', 'slideInDown', 'slideInLeft', 'slideInRight', 'zoomIn', 'zoomInDown', 'zoomInLeft', 'zoomInRight', 'zoomInUp', 'rollIn');
  $out = '
    <select class="video-animation">
  ';
  
  foreach ($values as $key => $value) {
    if ($value != $val)
      $out .= '<option value="' . $value . '">' . $value . '</option>';
    else
      $out .= '<option value="' . $value . '" selected>' . $value . '</option>';
  }
  $out .= '
    </select>
  ';

  return $out;
}