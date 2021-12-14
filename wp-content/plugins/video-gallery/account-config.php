<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
function wp_video_gallery_account_conf_page() {
	$con = wp_video_gallery_get_user_conf();
	$con = unserialize($con->value);
	$token = $con['vimeo']['token'];
	$tokenY = $con['youtube']['token'];
	$activated = strlen($token) ? 1 : 0;
	$activatedY = strlen($tokenY) ? 1 : 0;
	$days = get_wp_video_gallery_day();
	if ($days < 50) {
		echo wp_video_gallery_days_html();
	}
	else
		echo wp_video_gallery_days_html_ex();
?>
	<div id="wp_video_gallery-account">
		<h1><?php _e('Account configuration', 'wp_video_gallery');  ?> :</h1>
		<div class="vimeoPrivateBox ">
			<p class="description">
				<?php _e("The Vimeo api key enables you to get your account's private videos and keep them synchronized. To learn how to get your key, please refer to the video bellow.", 'wp_video_gallery'); ?>
			</p>
			<span class="label"><?php _e('Vimeo API Key', 'wp_video_gallery'); ?> :</span>
<?php
	if ($activated) {
?>
			<input type="text" class="vimeoAT" value="<?php echo $token; ?>" disabled style="color:gray;">
			<button class="button-primary deleteVimeoConf"><?php _e('Disconnect', 'wp_video_gallery'); ?></button>
<?php
		}
		else {
?>
			<input type="text" placeholder="<?php _e('Enter your key', 'wp_video_gallery'); ?>" class="vimeoAT" value="">
			<button class="button-primary saveVimeoConf"><?php _e('Validate', 'wp_video_gallery'); ?></button>
			<p class="error"><?php _e('The token you provided is unvalid', 'wp_video_gallery'); ?></p>
<?php
		}
?>
			<div class="clearfix"></div>
			<div style="text-align: center;margin-top: 50px;">
				<iframe src="https://www.youtube.com/embed/hvG-BG9l7Xs" frameborder="0" allowfullscreen></iframe>
			</div>
		</div>
		<div class="youtubePrivateBox ">
			<p class="description">
				<?php _e("The Youtube api key enables you to retrieve all the public videos from a channel.", 'wp_video_gallery'); ?>
				<br><a href="https://developers.google.com/youtube/v3/getting-started" target="_blank">Guide</a>
			</p>
			<span class="label"><?php _e('Youtube API Key', 'wp_video_gallery'); ?> :</span>
<?php
	if ($activatedY) {
?>
			<input type="text" class="youtubeAT" value="<?php echo $tokenY; ?>" disabled style="color:gray;">
			<button class="button-primary deleteYoutubeConf"><?php _e('Remove', 'wp_video_gallery'); ?></button>
<?php
		}
		else {
?>
			<input type="text" placeholder="<?php _e('Enter your key', 'wp_video_gallery'); ?>" class="youtubeAT" value="">
			<button class="button-primary saveYoutubeConf"><?php _e('Validate', 'wp_video_gallery'); ?></button>
			<p class="error"><?php _e('The token you provided is unvalid', 'wp_video_gallery'); ?></p>
<?php
		}
?>
			<div class="clearfix"></div>
			<div style="text-align: center;margin-top: 50px;">
				<iframe src="https://www.youtube.com/embed/qXhIpThTMlk" frameborder="0" allowfullscreen></iframe>
			</div>
		</div>
	</div>
<?php
	if ($activated) {
?>
	<div id="account-popup" class="white-popup mfp-hide">
  		<h2><?php _e('You are about to disconnect your Vimeo account. Would you like to keep your private Vimeo videos ?', 'wp_video_gallery') ?></h2>
  		<div class="actions">
  			<p class="button-primary keep"><?php _e('Keep the videos', 'wp_video_gallery'); ?></p>
  			<p class="button-primary clear"><?php _e('Remove the videos', 'wp_video_gallery'); ?></p>
  			<p class="button-primary cancel"><?php _e('Cancel', 'wp_video_gallery'); ?></p>
  		</div>
	</div>
<?php
	}
?>
<?php
	if ($activatedY) {
?>
	<div id="account-popup-Y" class="white-popup mfp-hide">
  		<h2><?php _e('You are about to remove your Youtube api key. Would you like to keep your Youtube videos ?', 'wp_video_gallery') ?></h2>
  		<div class="actions">
  			<p class="button-primary keep"><?php _e('Keep the videos', 'wp_video_gallery'); ?></p>
  			<p class="button-primary clear"><?php _e('Remove the videos', 'wp_video_gallery'); ?></p>
  			<p class="button-primary cancel"><?php _e('Cancel', 'wp_video_gallery'); ?></p>
  		</div>
	</div>
<?php
	}
}

?>