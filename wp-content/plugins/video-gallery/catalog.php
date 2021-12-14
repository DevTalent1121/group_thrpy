<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

WP_VIDEO_GALLERY_Activation::onActivation();
function wp_video_gallery_catalog_page() {
?>
	<div class="loadingCatalog">
		<i class="fa fa-refresh fa-spin"></i>
		<h2><?php _e("Please wait while we're refreshing your account videos data.", 'wp_video_gallery'); ?></h2>
	</div>
	<div id="wp_video_gallery-account" class="catalog" style="display: none;">
	</div>
<?php
}

function wp_video_gallery_catalog_page_inside() {
	$con = wp_video_gallery_get_user_conf();
	wp_video_gallery_post_stats();
	$con = unserialize($con->value);
	$token = $con['vimeo']['token'];
	$tokenY = $con['youtube']['token'];
	$registredVideosPri = WP_VIDEO_GALLERY_VimeoManager::get_account_videos();
	$registredVideosPub = WP_VIDEO_GALLERY_VimeoManager::get_registred_single_videos();
	$registredVideosYou = WP_VIDEO_GALLERY_VimeoManager::get_registred_youtube_videos();
	$countPri = count($registredVideosPri);
	$countPub = count($registredVideosPub);
	$countYou = count($registredVideosYou);
	$countVim = $countPri + $countPub;
	$activatedY = strlen($tokenY) ? 1 : 0;
	$days = get_wp_video_gallery_day();
	if ($days < 50) {
		echo wp_video_gallery_days_html();
	}
	else
		echo wp_video_gallery_days_html_ex();
	$vimeo_logo = plugin_dir_url(__FILE__) . 'img/vimeo-logo.png';
	$youtube_logo = plugin_dir_url(__FILE__) . 'img/youtube-logo.png';
	?>
	<h2><?php _e('Videos catalog', 'wp_video_gallery'); ?> : <a href="?page=wp_video_gallery-new-grid" class="add-new-h2"><?php _e('Add a new portfolio', 'wp_video_gallery'); ?></a></h2>
	<div class="navigation">
		<div class="vimeo-nav active nav">
			<img src="<?php echo $vimeo_logo; ?>">
			<span class="over-tab-count"><?php echo $countVim; ?></span>
		</div>
		<div class="youtube-nav nav">
			<img src="<?php echo $youtube_logo; ?>">
			<span class="over-tab-count"><?php echo $countYou; ?></span>
		</div>
	</div>
	<div class="sections">
		<div class="youtube-section">
			<div class="youtube">
				<div class="vimeoPublicBox youtube-single">
					<h4 class="subtitle"><?php _e('Single video', 'wp_video_gallery'); ?> :</h4>
					<input type="text" placeholder="e.g. https://www.youtube.com/watch?v=HnHCP589Dvg" <?php echo $days >= 50 ? 'disabled' : ''; ?>>
					<?php if ($days < 50) { ?><i class="fa fa-plus-circle addYoutubeVideo"></i><?php } ?>
					<p class="error"><?php _e('Invalid url', 'wp_video_gallery'); ?></p>
					<p class="duplicate"><?php _e('You already have this video in your catalog.', 'wp_video_gallery'); ?></p>
				</div>
				<div class="vimeoPublicBox youtube-channel">
					<h4 class="subtitle"><?php _e('Channel videos', 'wp_video_gallery'); ?> :</h4>
					<?php
						if ($activatedY) {
					?>
					<input type="text" placeholder="e.g. https://www.youtube.com/channel/UCggQcRNVNRaH3uEb4nqf0zg" <?php echo $days >= 50 ? 'disabled' : ''; ?>>
					<?php if ($days < 50) { ?><i class="fa fa-plus-circle addYoutubeChannel"></i><?php } ?>
					<p class="error"><?php _e('Invalid url', 'wp_video_gallery'); ?></p>
					<p class="added-videos"><b></b> <?php _e('added videos.', 'wp_video_gallery'); ?></p>
					<?php
						}
						else {
							echo "<p class='no-token-p'>" . __('For this functionality, you need to <a href="/wp-admin/admin.php?page=wp_video_gallery-conf">configure</a> your Youtube api key.', 'wp_video_gallery') . "</p>";
						}
					?>
				</div>
				<div class="registredVideos">
					<h4 class="subtitle"><?php _e('Registred videos', 'wp_video_gallery'); ?> :</h4>
					<div class="info">
						<span class="refresh_base"><?php _e('Refresh all videos', 'wp_video_gallery') ?><i class="fa fa-refresh refreshYoutubeVideos" aria-hidden="true"></i></span>
						<span><?php echo $countYou . ' ' . __('videos', 'wp_video_gallery') ?></span>
					</div>
					<?php
						if (!$countYou) {
					?>
					<p class="noRegistredVideos"><?php _e('You have no registred youtube video. Use the input above to add some.', 'wp_video_gallery'); ?></p>
					<?php
						}
						else {
					?>
					<div class="around">
						<table cellspacing="0">
					<?php
							foreach ($registredVideosYou as $key => $value) {
								$id = explode('/', $value['uri']);
								$id = $id[2];
					?>
						<tr class="line" data-id="<?php echo $id; ?>">
							<td class="thumb">
								<img src="<?php echo $value['thumb_small']; ?>">
							</td>
							<td class="title">
								<?php echo $value['name']; ?>
							</td>
							<td class="remove">
								<i class="fa fa-times removeYoutubeVideo" data-uri="<?php echo $value['uri']; ?>"></i>
							</td>
						</tr>
					<?php
							}
					?>
						</table>
					</div>
					<?php
						}
					?>
				</div>
			</div>
		</div>
		<div class="vimeo-section">
			<div class="public">
				<div class="vimeoPublicBox">
					<h4 class="subtitle"><?php _e('Vimeo public videos', 'wp_video_gallery'); ?> :</h4>
					<input type="text" placeholder="e.g. https://vimeo.com/88093956" <?php echo $days >= 50 ? 'disabled' : ''; ?>>
					<?php if ($days < 50) { ?><i class="fa fa-plus-circle addVimeoVideo"></i><?php } ?>
					<p class="error"><?php _e('Invalid url', 'wp_video_gallery'); ?></p>
					<p class="duplicate"><?php _e('You already have this video in your catalog.', 'wp_video_gallery'); ?></p>
				</div>
				<div class="registredVideos">
					<h4 class="subtitle"><?php _e('Registred videos', 'wp_video_gallery'); ?> :</h4>
					<div class="info">
						<span><?php echo $countPub . ' ' . __('videos', 'wp_video_gallery') ?></span>
					</div>
					<?php
						if (!$countPub) {
					?>
					<p class="noRegistredVideos"><?php _e('You have no registred public video. Use the input above to add some.', 'wp_video_gallery'); ?></p>
					<?php
						}
						else {
					?>
					<div class="around">
						<table cellspacing="0">
					<?php
							foreach ($registredVideosPub as $key => $value) {
								$id = explode('/', $value['uri']);
								$id = $id[2];
					?>
						<tr class="line" data-id="<?php echo $id; ?>">
							<td class="thumb">
								<img src="<?php echo $value['thumb_small']; ?>">
							</td>
							<td class="title">
								<?php echo $value['name']; ?>
							</td>
							<td class="refresh">
								<i class="fa fa-refresh refreshVimeoVideo" data-uri="<?php echo $value['uri']; ?>"></i>
							</td>
							<td class="remove">
								<i class="fa fa-times removeSingleVideo" data-uri="<?php echo $value['uri']; ?>"></i>
							</td>
						</tr>
					<?php
							}
					?>
						</table>
					</div>
					<?php
						}
					?>
				</div>
			</div>
			<?php if (strlen($token)) { 
				$name = WP_VIDEO_GALLERY_Tools::getUserName();
			?>
			<div class="private">
				<div class="vimeoPublicBox">
					<h4 class="subtitle"><?php _e('Your Vimeo account', 'wp_video_gallery'); ?> :</h4>
					<div class="account-name">
						<?php echo $name; ?>
						<i class="fa fa-times deleteVimeoConf"></i>
					</div>
				</div>
				<div class="registredVideos">
					<h4 class="subtitle"><?php _e('Account videos', 'wp_video_gallery'); ?> :</h4>
					<div class="info">
						<span class="refresh_base"><?php _e('Refresh all videos', 'wp_video_gallery') ?><i class="fa fa-refresh" aria-hidden="true"></i></span>
						<span><?php echo $countPri . ' ' . __('videos', 'wp_video_gallery') ?></span>
					</div>
					<?php
						if (!$countPri) {
					?>
					<p class="noRegistredVideos"><?php _e('You have no videos in your vimeo account.', 'wp_video_gallery'); ?><?php if ($days < 50) { ?><a href="#" class="refresh_base">Scan your account.</a><?php } ?></p>
					<?php
						}
						else {
					?>
					<div class="around">
						<table cellspacing="0">
					<?php
							foreach ($registredVideosPri as $key => $value) {
								$id = explode('/', $value['uri']);
								$id = $id[2];
					?>
						<tr class="line" data-id="<?php echo $id; ?>">
							<td class="thumb">
								<img src="<?php echo $value['thumb_small']; ?>">
							</td>
							<td class="title">
								<?php echo $value['name']; ?>
							</td>
							<td class="refresh">
								<i class="fa fa-refresh refreshVimeoVideo" data-uri="<?php echo $value['uri']; ?>"></i>
							</td>
						</tr>
					<?php
							}
					?>
						</table>
					</div>
					<?php
						}
					?>
				</div>
			</div>
			<?php } else { ?>
			<div class="private">
				<div class="vimeoPublicBox">
					<h4 class="subtitle"><?php _e('Your Vimeo account is not connected', 'wp_video_gallery'); ?> :</h4>
					<div class="account-name"><a href="?page=wp_video_gallery-conf"><?php _e('Connect your account', 'wp_video_gallery'); ?></a></div>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
	<div id="account-popup" class="white-popup mfp-hide">
  		<h2><?php _e('You are about to disconnect your Vimeo account. Would you like to keep your private Vimeo videos ?', 'wp_video_gallery') ?></h2>
  		<div class="actions">
  			<p class="button-primary keep"><?php _e('Keep the videos', 'wp_video_gallery'); ?></p>
  			<p class="button-primary clear"><?php _e('Remove the videos', 'wp_video_gallery'); ?></p>
  			<p class="button-primary cancel"><?php _e('Cancel', 'wp_video_gallery'); ?></p>
  		</div>
	</div>
	<div class="clearfix"></div>
	<?php
}

?>