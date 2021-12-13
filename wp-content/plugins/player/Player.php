<?php
/*
Plugin Name: SpiderVPlayer 
Plugin URI: https://web-dorado.com/products/wordpress-player.html
Version: 1.5.22
Author: WebDorado
Author URI: https://web-dorado.com/
License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/

define('WD_WDSVP_DIR', WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__)));
define('WD_WDSVP_URL', plugins_url(plugin_basename(dirname(__FILE__))));
define('WD_WDSVP_VERSION', "1.5.22");

$many_players = 0;

/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-*/
add_action('wp_head', 'askofen_1', 0);
function askofen_1($id){
    wp_enqueue_script("jquery");
    wp_enqueue_script("jquery-ui", plugins_url('js/jquery-ui.min.js', __FILE__));
    wp_enqueue_script("transit", plugins_url('js/jquery.transit.js', __FILE__));
    wp_enqueue_style("jqueri_ui", plugins_url('js/jquery-ui.css', __FILE__));
    wp_enqueue_script("flsh_detect", plugins_url('js/flash_detect.js', __FILE__));
}

/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-*/
function my_scripts_method() {
	wp_enqueue_script(
		'player_admin',
		plugins_url( '/js/player_js.js', __FILE__ ),
		array( 'jquery' )
	);
}    
add_action( 'admin_enqueue_scripts', 'my_scripts_method' );

/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-*/
add_action('init', 'Player_language_load');
$ident = 1;
function Player_language_load(){
    load_plugin_textdomain('Player', false, basename(dirname(__FILE__)) .'/Languages');
}

/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-*/
function Spider_Video_Player_shotrcode($atts){
    extract(shortcode_atts(array(
        'id' => 'no Spider Video Player',
    ), $atts));
    if (!(is_numeric($atts['id'])))
        return 'insert numerical  shortcode in `id`';
    return front_end_Spider_Video_Player($id);
}
add_shortcode('Spider_Video_Player', 'Spider_Video_Player_shotrcode');

/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-*/
function Spider_Single_Video_shotrcode($atts){
    extract(shortcode_atts(array(
        'track' => '',
        'theme_id' => '1',
        'priority' => '1'
    ), $atts));
    if (!(is_numeric($atts['track'])))
        return 'insert numerical  shortcode in `track`';
    if (!(is_numeric($atts['theme_id'])))
        return 'insert numerical  shortcode in `theme_id`';
    if (!($atts['priority'] == 1 || $atts['priority'] == 0))
        return 'insert valid `priority`';
    return front_end_Spider_Single_Video($atts['track'], $atts['theme_id'], $atts['priority']);
}
add_shortcode('Spider_Single_Video', 'Spider_Single_Video_shotrcode');

/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-*/
function front_end_Spider_Single_Video($track, $theme_id, $priority) {
    global $wpdb, $ident;
    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM " .$wpdb->prefix ."Spider_Video_Player_video WHERE id=%d", $track));
    $params = $wpdb->get_row($wpdb->prepare("SELECT * FROM " .$wpdb->prefix ."Spider_Video_Player_theme WHERE id=%d", $theme_id));
    if ($priority == 0){
        $scripttt = '    <script type="text/javascript"> 
		var html5_' .$ident .' = document.getElementById("spidervideoplayerhtml5_' .$ident .'");
		var flash_' .$ident .' = document.getElementById("spidervideoplayerflash_' .$ident .'");
		if(!FlashDetect.installed){
		flash_' .$ident .'.parentNode.removeChild(flash_' .$ident .');
		spidervideoplayerhtml5_' .$ident .'.style.display=\'\';
		}
		else{
		html5_' .$ident .'.parentNode.removeChild(html5_' .$ident .');
		spidervideoplayerflash_' .$ident .'.style.display=\'\';
		}
		</script>';
    } else {
        $scripttt = '';
    }
    if ($priority == 0) {
        global $post;
        $track_for_posts = $post->ID;
        $all_player_ids = $wpdb->get_col("SELECT id FROM " .$wpdb->prefix ."Spider_Video_Player_video");
        $b = false;
        foreach ($all_player_ids as $all_player_id) {
            if ($all_player_id == $track)
                $b = true;
        }
        if (!$b)
            return "";
       
		$Spider_Single_Video_front_end = "";
        $params = $wpdb->get_row($wpdb->prepare("SELECT * FROM " .$wpdb->prefix ."Spider_Video_Player_theme WHERE id=%d", $theme_id));
        $playlist = '';
        
		if ($params->appWidth != "")
            $width = $params->appWidth;
        else
            $width = '700';
        
		if ($params->appHeight != "")
            $height = $params->appHeight;
        else
            $height = '400';
        
		$show_trackid = $params->show_trackid;
        global $many_players;

        $Spider_Single_Video_front_end = "<script type=\"text/javascript\" src=\"" .plugins_url("swfobject.js", __FILE__) ."\"></script>
		<div id=\"spidervideoplayerflash_" .$ident ."\" style=\"display:none\">		
		<div id=\"" .$track_for_posts ."_" .$many_players ."_flashcontent\"  style=\"width: " .$width ."px; height:" .$height ."px\"></div>
		<script type=\"text/javascript\">
		function flashShare(type,b,c)	
		{
		u=location.href;
			u=u.replace('/?','/index.php?');
			if(u.search('&AlbumId')!=-1)
			{
				var u_part2='';
				u_part2=u.substring(u.search('&TrackId')+2, 1000)
				if(u_part2.search('&')!=-1)
				{
					u_part2=u_part2.substring(u_part2.search('&'),1000);
				}
				u=u.replace(u.substring(u.search('&AlbumId'), 1000),'')+u_part2;		
			}
			if(!location.search)
					u=u+'?';
				else
					u=u+'&';
			t=document.title;
			switch (type)
			{
			case 'fb':	
				window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u+'AlbumId='+b+'&TrackId='+c)+'&t='+encodeURIComponent(t), \"Facebook\",\"menubar=1,resizable=1,width=350,height=250\");
				break;
			case 'g':
				window.open('http://plus.google.com/share?url='+encodeURIComponent(u+'AlbumId='+b+'&TrackId='+c)+'&t='+encodeURIComponent(t), \"Google\",\"menubar=1,resizable=1,width=350,height=250\");
				break;
			case 'tw':
				window.open('http://twitter.com/home/?status='+encodeURIComponent(u+'AlbumId='+b+'&TrackId='+c), \"Twitter\",\"menubar=1,resizable=1,width=350,height=250\");
				break;
			}
		}		
		var so = new SWFObject(\"" .plugins_url("videoSpider_Video_Player.swf", __FILE__) ."?wdrand=" .mt_rand() ."\", \"Spider_Video_Player\", \"100%\", \"100%\", \"8\", \"#000000\");
		so.addParam(\"FlashVars\", \"settingsUrl=" .str_replace("&", "@", str_replace("&amp;", "@", admin_url('admin-ajax.php?action=spiderVeideoPlayersettingsxml') ."&playlist=" .$playlist ."&theme=" .$theme_id ."&s_v_player_id=" .$track ."&single=1")) ."&playlistUrl=" .str_replace("&", "@", str_replace("&amp;", "@", admin_url('admin-ajax.php?action=spiderVeideoPlayerplaylistxml') ."&priority=" .$priority ."&trackID=" .$track ."&single=1&show_trackid=" .$show_trackid)) ."&defaultAlbumId=" .(isset($_GET['AlbumId']) ? htmlspecialchars($_GET['AlbumId']) : "") ."&defaultTrackId=" .(isset($_GET['TrackId']) ? htmlspecialchars($_GET['TrackId']) : "") ."\");
		so.addParam(\"quality\", \"high\");
		so.addParam(\"menu\", \"false\");
		so.addParam(\"wmode\", \"transparent\");
		so.addParam(\"loop\", \"false\");
		so.addParam(\"allowfullscreen\", \"true\");
		so.write(\"" .$track_for_posts ."_" .$many_players ."_flashcontent\");
		</script>
		</div>
		";
        $many_players++;

        return $Spider_Single_Video_front_end .Spider_Single_Video_front_end($track, $theme_id, $priority) .$scripttt;
    } 
	else {
        $identt = $ident;
        return Spider_Single_Video_front_end($track, $theme_id, $priority) .'<script>document.getElementById("spidervideoplayerhtml5_' .$identt .'").style.display=\'\'</script>';
    }
}


/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-*/
function Spider_Single_Video_front_end($track, $theme_id, $priority) {
    ob_start();
    global $ident; ?>
    <div id="spidervideoplayerhtml5_<?php echo $ident ?>" style="display:none">
    <?php if ($priority == 1 ) 
	{
        global $wpdb;
        $playlist_array = [];
        $trackk = $wpdb->get_row($wpdb->prepare("SELECT * FROM " .$wpdb->prefix ."Spider_Video_Player_video WHERE id=%d", $track));
        global $many_players;
        $track_URL = '';
        $track_poster = $trackk->thumb;
        if (($trackk->urlHtml5 == "" || !strpos($trackk->url, 'embed')) && $trackk->type!="vimeo") {
            if($trackk->type=="youtube" ){
                $track_URL = "https://www.youtube.com/embed/".substr($trackk->url, strpos($trackk->url, '?v=')+3,11)."?enablejsapi=1&html5=1&controls=1&modestbranding=1&rel=0";
            } else {
                $track_URL = $trackk->url;  
            }
            
        } else
            $track_URL = $trackk->urlHtml5;
        $theme = $wpdb->get_row($wpdb->prepare("SELECT * FROM " .$wpdb->prefix ."Spider_Video_Player_theme WHERE id=%d", $theme_id));
        $videos = $wpdb->get_results($wpdb->prepare("SELECT url,urlHtml5,type,title,thumb FROM " .$wpdb->prefix ."Spider_Video_Player_video WHERE id=%d", $track));
        $video_urls = '';
        for ($i = 0; $i < count($videos); $i++) {
            if ($videos[$i]->urlHtml5 != "") {
                $video_urls .= "'" .$videos[$i]->urlHtml5 ."'" .',';
            } else $video_urls .= "'" .$videos[$i]->url ."'" .',';
        }
        $video_urls = substr($video_urls, 0, -1);
        $playlists = $wpdb->get_results("SELECT * FROM " .$wpdb->prefix ."Spider_Video_Player_playlist");
        if (isset($_POST['play'])) {
            $p = esc_html(stripslashes($_POST['play']));
        } else $p = 0;
        $display = 'style="width:100%;height:100% !important;border-collapse: collapse; margin-left:8px !important;"';
        $table_count = 1;
        $itemBgHoverColor = '#' .$theme->itemBgHoverColor;
        $vds = $wpdb->get_results("SELECT * FROM " .$wpdb->prefix ."Spider_Video_Player_video");
        $ctrlsStack = $theme->ctrlsStack;
        if ($theme->ctrlsPos == 2) {
            $ctrl_top = $theme->appHeight - 35 .'px';
            $share_top = '-148px';
        } else {
            $ctrl_top = '5px';
            $share_top = '-' .$theme->appHeight + 25 .'px';
        }
        if (isset($_POST['AlbumId']))
            $AlbumId = esc_html(stripslashes($_POST['AlbumId']));
        else
            $AlbumId = '';
        if (isset($_POST['TrackId']))
            $TrackId = esc_html(stripslashes($_POST['TrackId']));
        else
            $TrackId = '';
        ?>
        <style>
            a#dorado_mark_<?php echo $ident;?>:hover {
                background: none !important;
            }
            #album_table_<?php  echo $ident?> td,
            #album_table_<?php  echo $ident?> tr,
            #album_table_<?php  echo $ident?> img {
                padding: 3px 9px 0px 0px !important;
                line-height: 1em !important;
            }
            #share_buttons_<?php echo $ident;?> img {
                display: inline !important;
            }
            #album_div_<?php  echo $ident?> table {
                margin: 0px !important;
            }
            #album_table_<?php  echo $ident?> {
                margin: -1 0 1.625em !important;
            }
            table {
                margin: 0em;
            }
            #global_body_<?php echo $ident;?> .control_<?php  echo $ident?> {
                position: absolute;
                background-color: rgba(<?php echo HEXDEC(SUBSTR($theme->framesBgColor, 0, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->framesBgColor, 2, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->framesBgColor, 4, 2)) ?>, <?php echo $theme->framesBgAlpha/100; ?>);
                top: <?php echo $ctrl_top?> !important;
                width: <?php echo $theme->appWidth; ?>px;
                height: 40px;
                z-index: 7;
                margin-top: -5px;
            }
            #global_body_<?php echo $ident;?> img {
                background: none !important;
            }
            #global_body_<?php echo $ident;?> .control_<?php  echo $ident?> td {
                padding: 0px !important;
                margin: 0px !important;
            }
            #global_body_<?php echo $ident;?> .control_<?php  echo $ident?> td img {
                padding: 0px !important;
                margin: 0px !important;
            }
            #global_body_<?php echo $ident;?> .progressBar_<?php  echo $ident?> {
                position: relative;
                width: 100%;
                height: 6px;
                z-index: 5;
                cursor: pointer;
                border-top: 1px solid rgba(<?php echo HEXDEC(SUBSTR($theme->slideColor, 0, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->slideColor, 2, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->slideColor, 4, 2)) ?>, <?php echo $theme->framesBgAlpha/100; ?>);
                border-bottom: 1px solid rgba(<?php echo HEXDEC(SUBSTR($theme->slideColor, 0, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->slideColor, 2, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->slideColor, 4, 2)) ?>, <?php echo $theme->framesBgAlpha/100; ?>);
            }
            #global_body_<?php echo $ident;?> .timeBar_<?php  echo $ident?> {
                position: absolute;
                top: 0;
                left: 0;
                width: 0;
                height: 100%;
                background-color: <?php echo '#'.$theme->slideColor; ?>;
                z-index: 5;
            }
            #global_body_<?php echo $ident;?> .bufferBar_<?php  echo $ident?> {
                position: absolute;
                top: 0;
                left: 0;
                width: 0;
                height: 100%;
                background-color: <?php echo '#'.$theme->slideColor; ?>;
                opacity: 0.3;
            }
            #global_body_<?php echo $ident;?> .volumeBar_<?php echo $ident;?> {
                position: relative;
                overflow: hidden;
                width: 0px;
                height: 4px;
                background-color: rgba(<?php echo HEXDEC(SUBSTR($theme->framesBgColor, 0, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->framesBgColor, 2, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->framesBgColor, 4, 2)) ?>, <?php echo $theme->framesBgAlpha/100; ?>);
                border: 1px solid rgba(<?php echo HEXDEC(SUBSTR($theme->slideColor, 0, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->slideColor, 2, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->slideColor, 4, 2)) ?>, <?php echo $theme->framesBgAlpha/100; ?>);
            }
            #global_body_<?php echo $ident;?> .volume_<?php echo $ident;?> {
                position: absolute;
                top: 0;
                left: 0;
                width: 0;
                height: 100%;
                background-color: <?php echo '#'.$theme->slideColor; ?>;
            }
            #play_list_<?php  echo $ident?> {
                height: <?php echo $theme->appHeight; ?>px;
                width: 0px;
            <?php
	if ($theme->playlistPos==1)
	echo 'position:absolute;float:left !important;';
	else
	echo 'position:absolute;float:right !important;right:0;';
	?>;
                position: absolute;
                background-color: rgba(<?php echo HEXDEC(SUBSTR($theme->framesBgColor, 0, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->framesBgColor, 2, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->framesBgColor, 4, 2)) ?>, <?php echo $theme->framesBgAlpha/100; ?>);
                color: white;
                z-index: 100;
                padding: 0px !important;
                margin: 0px !important;
            }
            #play_list_<?php  echo $ident?> img,
            #play_list_<?php  echo $ident?> td {
                background-color: transparent !important;
                color: white;
                padding: 0px !important;
                margin: 0px !important;
            }
            .control_btns_<?php  echo $ident?> {
                opacity: <?php echo $theme->ctrlsMainAlpha/100; ?>;
            }
            #control_btns_<?php  echo $ident?>,
            #volumeTD_<?php echo $ident;?> {
                margin: 0px;
            }
            img {
                box-shadow: none !important;
            }
            #td_ik_<?php echo $ident;?> {
                border: 0px;
            }
            
            </style>
    <?php
    $player_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM " .$wpdb->prefix ."Spider_Video_Player_player WHERE id=%d", $track));
    ?>
        <div id="global_body_<?php echo $ident; ?>"
             style="width:<?php echo $theme->appWidth; ?>px;height:<?php echo $theme->appHeight; ?>px; position:relative;">
        <?php
        $row1 = $wpdb->get_row($wpdb->prepare("SELECT * FROM " .$wpdb->prefix ."Spider_Video_Player_theme WHERE id=%d", $theme_id));
        $start_lib = $row1->startWithLib;
        ?>
        <div id="video_div_<?php echo $ident; ?>"
             style="display:block;width:<?php echo $theme->appWidth; ?>px;height:<?php echo $theme->appHeight; ?>px;background-color:<?php echo "#" .$theme->vidBgColor; ?>">
            <div id="play_list_<?php echo $ident ?>">
            
            <?php 
if($trackk->type=="http")
		{
		if($trackk->urlHtml5!='')
		{
		if(strpos($trackk->urlHtml5, "http:")===false and strpos($trackk->urlHtml5, "https:")===false )
		$html5Url=$trackk->urlHtml5;
		else
		$html5Url=$trackk->urlHtml5;
		}
		else
		{
		if(strpos($trackk->url, "http:")===false and strpos($trackk->url, "https:")===false )
		$html5Url=$trackk->url;
		else
		$html5Url=$trackk->url;
		}
		
		
		if($trackk->urlHdHtml5!='')
		{
		if(strpos($trackk->urlHdHtml5, "http:")===false and strpos($trackk->urlHdHtml5, "https:")===false )
		$html5UrlHD=$trackk->urlHdHtml5;
		else
		$html5UrlHD=$trackk->urlHdHtml5;
		}
		else
		{
		if(strpos($trackk->urlHD, "http:")===false and strpos($trackk->urlHD, "https:")===false )
		$html5UrlHD=$trackk->urlHD;
		else
		$html5UrlHD=$trackk->urlHD;
		
		}
}
?>
<input type='hidden' value='<?php echo $html5UrlHD ?>' id="urlHD_<?php echo $ident; ?>" />
<input type='hidden' value='<?php echo $html5Url ?>' id="trackURL_<?php echo $ident; ?>" />
                <input type='hidden' value='0' id="track_list_<?php echo $ident; ?>"/>
                <div style="height:90%" id="play_list1_<?php echo $ident; ?>">
                    <div id="arrow_up_<?php echo $ident ?>"
                         onmousedown="scrolltp2=setInterval('scrollTop2_<?php echo $ident; ?>()', 30)"
                         onmouseup="clearInterval(scrolltp2)" onclick="scrollTop2_<?php echo $ident; ?>()"
                         style="overflow:hidden; text-align:center;width:<?php echo $theme->playlistWidth; ?>px; height:20px">
                        <img src="<?php echo plugins_url('', __FILE__) ?>/images/top.png"
                             style="cursor:pointer;  border:none;" id="button20_<?php echo $ident ?>"/>
                    </div>
                    <div style="height:<?php echo $theme->appHeight - 40; ?>px;overflow:hidden;"
                         id="video_list_<?php echo $ident; ?>">
                        <?php
                        //echo '<p onclick="document.getElementById("videoID").src="'.$videos[$i]["url"].'" ">'.$videos[$i]['title'].'</p>';
                        for ($i = 0; $i < count($playlist_array) - 1; $i++) {
                            $playy = $wpdb->get_row("SELECT * FROM " .$wpdb->prefix ."Spider_Video_Player_playlist WHERE id=" .$playlist_array[$i]);
                            $v_ids = explode(',', $playy->videos);
                            $vi_ids = substr($playy->videos, 0, -1);
                            if ($i != 0)
                                echo '<table id="track_list_' .$ident .'_' .$i .'"  style="display:none;height:100%;width:100%;border-spacing:0px;border:none;border-collapse: inherit;" >';
                            else
                                echo '<table id="track_list_' .$ident .'_' .$i .'"  style="display:block;height:100%;width:100%;border-spacing:0px;border:none;border-collapse: inherit;" > ';
                            echo '<tr style="background:transparent ">
<td id="td_ik_' .$ident .'" style="text-align:left;border:0px solid grey;width:100%;vertical-align:top;">
<div id="scroll_div2_' .$i .'_' .$ident .'" class="playlist_values_' .$ident .'" style="position:relative">';
                            $jj = 0;
                            for ($j = 0; $j < count($v_ids) - 1; $j++) {
                                $vdss = $wpdb->get_row("SELECT * FROM " .$wpdb->prefix ."Spider_Video_Player_video WHERE id=" .$v_ids[$j]);
                                if ($vdss->type == "http" || $vdss->type == "youtube") {
                                    if ($vdss->urlHtml5 != "") {
                                        $html5Url = $vdss->urlHtml5;
                                    } else $html5Url = $vdss->url;
                                    $vidsTHUMB = $vdss->thumb;
                                    if ($vdss->urlHDHtml5 != "") {
                                        $html5UrlHD = $vdss->urlHDHtml5;
                                    } else $html5UrlHD = $vdss->urlHD;
                                    echo '<div id="thumb_' .$jj .'_' .$ident .'"  onclick="jQuery(\'#HD_on_' .$ident .'\').val(0);document.getElementById(\'videoID_' .$ident .'\').src=\'' .$html5Url .'\';play_' .$ident .'();vid_select_' .$ident .'(this);vid_num=' .$jj .';jQuery(\'#current_track_' .$ident .'\').val(' .$jj .');" class="vid_thumb_' .$ident .'" style="color:#' .$theme->textColor .';cursor:pointer;width:' .$theme->playlistWidth .'px;text-align:center; "  >';
                                    if ($vdss->thumb)
                                        echo '<img   src="' .$vidsTHUMB .'" width="90px" style="display:none;  border:none;"  />';
                                    echo '<p style="font-size:' .$theme->playlistTextSize .'px !important;line-height:30px;cursor:pointer;" >' .($theme->show_trackid ? ($jj + 1) .'-' : '') .$vdss->title .'</p></div>';
                                    echo '<input type="hidden" id="urlHD_' .$jj .'_' .$ident .'" value="' .$html5UrlHD .'" />';
                                    echo '<input type="hidden" id="vid_type_' .$jj .'_' .$ident .'" value="' .$vdss->type .'" />';
                                    $jj = $jj + 1;
                                }
                            }
                            echo '</div></td>
</tr></table>';
                        }
                        ?>
                    </div>
                                      <div onmousedown="scrollBot2=setInterval('scrollBottom2_<?php echo $ident; ?>()', 30)"
                                           onmouseup="clearInterval(scrollBot2)" onclick="scrollBottom2_<?php echo $ident; ?>()"
                         style="position:absolute;overflow:hidden; text-align:center;width:<?php echo $theme->playlistWidth; ?>px; height:20px"
                         id="divulushka_<?php echo $ident; ?>"><img
                            src="<?php echo plugins_url('', __FILE__) ?>/images/bot.png"
                            style="cursor:pointer;  border:none;" id="button21_<?php echo $ident ?>"/></div>
                </div>
            </div>
            
            <?php if($trackk->type=="youtube" ){?>
             <iframe id="videoID_<?php echo $ident ?>" type="text/html" width="<?php echo $theme->appWidth; ?>" height="<?php echo $theme->appHeight; ?>"
                     src="<?php echo substr($track_URL,0,  strpos($track_URL, "?"));?>?enablejsapi=1&version=3&playerapiid=ytplayer&modestbranding=1&rel=0"
                frameborder="0" allowfullscreen></iframe>
             
            <?php }elseif($trackk->type=="vimeo"){?>
            <iframe id="videoID_<?php echo $ident ?>" type="text/html" width="<?php echo $theme->appWidth; ?>" height="<?php echo $theme->appHeight; ?>"
                     src="<?php echo $track_URL;?>"
                frameborder="0" allowfullscreen></iframe>
            <?php }else{?>
            <video ontimeupdate="timeUpdate_<?php echo $ident ?>()"
                   ondurationchange="durationChange_<?php echo $ident ?>();" id="videoID_<?php echo $ident ?>"
                   src="<?php echo $track_URL ?>" poster="<?php echo $track_poster ?>"
                   style="width:100%; height:100%;margin:0px;position: absolute;">
                <p>Your browser does not support the video tag.</p>
            </video>
            <?php } ?>
			 <img src="<?php echo plugins_url('', __FILE__) ?>/images/wd_logo.png" style="bottom: 30px;position: absolute;width: 140px;height: 73px; border: 0px !important; left:0px;"/>
            <div class="control_<?php echo $ident; ?>" id="control_<?php echo $ident; ?>"
                 style="overflow:hidden;top: 5px;<?php if($trackk->type=="youtube" || $trackk->type=="vimeo") echo "visibility: hidden !important; ";?>">
                <?php if ($theme->ctrlsPos == 2) { ?>
                    <div class="progressBar_<?php echo $ident; ?>">
                        <div class="timeBar_<?php echo $ident; ?>"></div>
                        <div class="bufferBar_<?php echo $ident; ?>"></div>
                    </div>
                <?php
                }
                $ctrls = explode(',', $ctrlsStack);
                $y = 1;
                echo '<table id="control_btns_' .$ident .'" style="width: 100%; border:none;border-collapse: inherit; background: transparent; margin-top: 4px;padding: 0px !important;"><tr style="background: transparent;">';
                for ($i = 0; $i < count($ctrls); $i++) {
                    $ctrl = explode(':', $ctrls[$i]);
                    if ($ctrl[0] == 'playlist') $ctrl[1] = 0;
                    if ($ctrl[0] == 'lib') $ctrl[1] = 0;
                    if ($ctrl[1] == 1) {
                        echo '<td style="border:none;background: transparent;">';
                        if ($ctrl[0] == 'playPause') {
                            if ($theme->appWidth > 400) {
                                echo '<img id="button' .$y .'_' .$ident .'"  class="btnPlay" width="16" style="position: relative;vertical-align: middle;cursor:pointer;  border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .'; height:19px"   src="' .plugins_url('', __FILE__) .'/images/play.png" />';
                                echo '<img id="button' .($y + 1) .'_' .$ident .'" width="16"  class="btnPause" style="position: relative;vertical-align: middle;display:none;cursor:pointer;  border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';height:18px"  src="' .plugins_url('', __FILE__) .'/images/pause.png" />';
                            } else {
                                echo '<img id="button' .$y .'_' .$ident .'"  class="btnPlay" style="vertical-align: middle;cursor:pointer;max-width:7px;  border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';"   src="' .plugins_url('', __FILE__) .'/images/play.png" />';
                                echo '<img id="button' .($y + 1) .'_' .$ident .'" width="16"  class="btnPause" style="vertical-align: middle;height: 18px !important;display:none;cursor:pointer;max-width:7px;  border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';"  src="' .plugins_url('', __FILE__) .'/images/pause.png" />';
                            }
                            $y = $y + 2;
                        } else
                            if ($ctrl[0] == '+') {
                                echo '<span id="space" style="position: relative;vertical-align: middle;padding-left:' .(($theme->appWidth * 20) / 100) .'px"></span>';
                            } else
                                if ($ctrl[0] == 'time') {
                                    echo '						
						  <span style="color:#' .$theme->ctrlsMainColor .';opacity:' .$theme->ctrlsMainAlpha / 100 .'; position:relative; vertical-align: middle; " id="time_' .$ident .'">00:00</span>
						  <span style="color:#' .$theme->ctrlsMainColor .'; opacity:' .$theme->ctrlsMainAlpha / 100 .';position:relative; vertical-align: middle;">/</span> 
						  <span style="color:#' .$theme->ctrlsMainColor .';opacity:' .$theme->ctrlsMainAlpha / 100 .';position:relative; vertical-align: middle;" id="duration_' .$ident .'">00:00</span>';
                                } else
                                    if ($ctrl[0] == 'vol') {
                                        if ($theme->appWidth > 400) {
                                            $img_button = '<img  style="position: relative;cursor:pointer; border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';vertical-align: middle;"  id="button' .$y .'_' .$ident .'"    src="' .plugins_url('', __FILE__) .'/images/vol.png"  />';
                                        } else {
                                            $img_button = '<img  style="vertical-align: middle;cursor:pointer;max-width:7px; border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';"  id="button' .$y .'_' .$ident .'"    src="' .plugins_url('', __FILE__) .'/images/vol.png"  />';
                                        }
                                        echo '<table  id="volumeTD_' .$ident .'" style="border:none;border-collapse: inherit; min-width: 0;background: transparent;padding: 0px !important;" >
						<tr style="background: transparent;">
							<td id="voulume_img_' .$ident .'" style="top:5px;border:none;min-width:13px;  background: transparent; width:20px;" >' .$img_button .'
							</td>
							<td id="volumeTD2_' .$ident .'" style="width:0px; border:none; position:relative;background: transparent; ">
									<span id="volumebar_player_' .$ident .'" class="volumeBar_' .$ident .'" style="vertical-align: middle;">
								    <span class="volume_' .$ident .'" style="vertical-align: middle;"></span>
									</span>
							 </td>
						</tr>
						</table> ';
                                        $y = $y + 1;
                                    } else
                                        if ($ctrl[0] == 'shuffle') {
                                            if ($theme->appWidth > 400) {
                                                echo '<img  id="button' .$y .'_' .$ident .'" class="shuffle_' .$ident .'" style="position: relative;vertical-align: middle;cursor:pointer; border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';"   src="' .plugins_url('', __FILE__) .'/images/shuffle.png" />';
                                                echo '<img  id="button' .($y + 1) .'_' .$ident .'"  class="shuffle_' .$ident .'" style="position: relative;vertical-align: middle;display:none;cursor:pointer; border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';"  src="' .plugins_url('', __FILE__) .'/images/shuffleoff.png" />';
                                            } else {
                                                echo '<img  id="button' .$y .'_' .$ident .'" class="shuffle_' .$ident .'" style="vertical-align: middle;cursor:pointer;max-width:7px;  border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';"   src="' .plugins_url('', __FILE__) .'/images/shuffle.png" />';
                                                echo '<img  id="button' .($y + 1) .'_' .$ident .'"  class="shuffle_' .$ident .'" style="vertical-align: middle;display:none;cursor:pointer;max-width:7px; border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';"  src="' .plugins_url('', __FILE__) .'/images/shuffleoff.png" />';
                                            }
                                            $y = $y + 2;
                                        } else
                                            if ($ctrl[0] == 'repeat') {
                                                if ($theme->appWidth > 400) {
                                                    echo '
					<img  id="button' .$y .'_' .$ident .'" class="repeat_' .$ident .'" style="position: relative;vertical-align: middle;cursor:pointer; border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';"   src="' .plugins_url('', __FILE__) .'/images/repeat.png"/>
					<img  id="button' .($y + 1) .'_' .$ident .'"  class="repeat_' .$ident .'" style="position: relative;vertical-align: middle;display:none;cursor:pointer; border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';"   src="' .plugins_url('', __FILE__) .'/images/repeatOff.png"/>
					<img  id="button' .($y + 2) .'_' .$ident .'"  class="repeat_' .$ident .'" style="osition: relative;vertical-align: middle;display:none;cursor:pointer; border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';"  src="' .plugins_url('', __FILE__) .'/images/repeatOne.png"/>
					';
                                                } else {
                                                    echo '
				<img  id="button' .$y .'_' .$ident .'" class="repeat_' .$ident .'" style="vertical-align: middle;cursor:pointer;max-width:7px; border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';"   src="' .plugins_url('', __FILE__) .'/images/repeat.png"/>
				<img  id="button' .($y + 1) .'_' .$ident .'"  class="repeat_' .$ident .'" style="vertical-align: middle;display:none;cursor:pointer;max-width:7px; border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';"   src="' .plugins_url('', __FILE__) .'/images/repeatOff.png"/>
				<img  id="button' .($y + 2) .'_' .$ident .'"  class="repeat_' .$ident .'" style="vertical-align: middle;display:none;cursor:pointer;max-width:7px; border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';"  src="' .plugins_url('', __FILE__) .'/images/repeatOne.png"/>
				';
                                                }
                                                $y = $y + 3;
                                            } else {
                                                if($ctrl[0] !== 'playPrev' && $ctrl[0] !== 'playNext'){
                                                    if ($theme->appWidth > 400) {
                                                        echo '<img  style="position: relative;vertical-align: middle;cursor:pointer; border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';" id="button' .$y .'_' .$ident .'" class="' .$ctrl[0] .'_' .$ident .'"  src="' .plugins_url('', __FILE__) .'/images/' .$ctrl[0] .'.png" />';
                                                    } else {
                                                        echo '<img  style="vertical-align: middle;cursor:pointer;max-width:7px; border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';" id="button' .$y .'_' .$ident .'" class="' .$ctrl[0] .'_' .$ident .'"  src="' .plugins_url('', __FILE__) .'/images/' .$ctrl[0] .'.png" />';
                                                    }
                                                }
                                                $y = $y + 1;
                                            }
                        echo '</td>';
                    }
                }
                echo '</tr></table>';
                if ($theme->ctrlsPos == 1) {
                    ?>
                    <div class="progressBar_<?php echo $ident; ?>">
                        <div class="timeBar_<?php echo $ident; ?>"></div>
                        <div class="bufferBar_<?php echo $ident; ?>"></div>
                    </div>
                <?php
                }
                ?>
            </div>
             
        </div>
        </div>
        <div id="album_div_<?php echo $ident; ?>"
             style="display:none;background-color:<?php echo "#" .$theme->appBgColor; ?>;height:100%; overflow:hidden;position:relative;">
            <table width="<?php echo $theme->appWidth ?>px " height="<?php echo $theme->appHeight ?>px"
                   style="border:none;border-collapse: inherit;" id="album_table_<?php echo $ident ?>">
                <tr id="tracklist_up_<?php echo $ident ?>" style="display:none; background: transparent;">
                    <td height="12px" colspan="2" style="text-align:right; border:none;background: transparent;">
                        <div
                            onmouseover="this.style.background='rgba(<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 0, 2)) ?>,<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 2, 2)) ?>,<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 4, 2)) ?>,0.4)'"
                            onmouseout="this.style.background='none'" id="scroll"
                            style="overflow:hidden;width:50%;height:100%;text-align:center;float:right;cursor:pointer;"
                            onmousedown="scrolltp=setInterval('scrollTop_<?php echo $ident; ?>()', 30)"
                            onmouseup="clearInterval(scrolltp)" onclick="scrollTop_<?php echo $ident; ?>()">
                            <img src="<?php echo plugins_url('', __FILE__) ?>/images/top.png"
                                 style="cursor:pointer; margin: 0px !important; padding: 0px !important; border:none;background: transparent;"
                                 id="button25_<?php echo $ident; ?>"/>
                            <div>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:middle; border:none;background: transparent; ">
                        <img src="<?php echo plugins_url('', __FILE__) ?>/images/prev.png"
                             style="cursor:pointer; margin: 0px !important; padding: 0px !important; background: transparent;border:none;min-width: 16px;"
                             id="button28_<?php echo $ident ?>" onclick="prevPage_<?php echo $ident ?>();"/>
                    </td>
                    <td style="border:none;background: transparent;padding: 2px 0px 2px 0px !important;width: 100% !important;"
                        id="lib_td_<?php echo $ident; ?>">
                    </td>
                    <td style="vertical-align:bottom; border:none;background: transparent; top: -13px;position: relative;width: 6%;">
                        <table
                            style='height:<?php echo $theme->appHeight - 46 ?>px; border:none;border-collapse: inherit;'>
                            <tr style="background: transparent;">
                                <td height='100%' style="border:none;background: transparent; vertical-align: middle;">
                                    <img src="<?php echo plugins_url('', __FILE__) ?>/images/next.png"
                                         style="cursor:pointer;border:none;background: transparent;display:inline !important; "
                                         id="button27_<?php echo $ident ?>" onclick="nextPage_<?php echo $ident ?>()"/>
                                </td>
                            </tr>
                            <tr style="background: transparent;">
                                <td style="border:none;background: transparent;">
                                    <img src="<?php echo plugins_url('', __FILE__) ?>/images/back.png"
                                         style="cursor:pointer; display:none; border:none;background: transparent;"
                                         id="button29_<?php echo $ident ?>"
                                         onclick="openLibTable_<?php echo $ident ?>()"/>
                                </td>
                            </tr>
                            <tr style="background: transparent;">
                                <td style="border:none;background: transparent;">
                                    <img
                                        style="cursor:pointer;border:none;background: transparent; position:relative; top:-5px;"
                                        id="button19_<?php echo $ident ?>" class="show_vid_<?php echo $ident ?>"
                                        src="<?php echo plugins_url('', __FILE__) ?>/images/lib.png"/>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr id="tracklist_down_<?php echo $ident ?>" style="display:none;background: transparent">
                    <td height="12px" colspan="2" style="text-align:right;border:none;background: transparent;">
                        <div
                            onmouseover="this.style.background='rgba(<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 0, 2)) ?>,<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 2, 2)) ?>,<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 4, 2)) ?>,0.4)'"
                            onmouseout="this.style.background='none'" id="scroll"
                            style="overflow:hidden;width:50%;height:100%;text-align:center;float:right;cursor:pointer;"
                            onmousedown="scrollBot=setInterval('scrollBottom_<?php echo $ident; ?>()', 30)"
                            onmouseup="clearInterval(scrollBot)" onclick="scrollBottom_<?php echo $ident; ?>()">
                            <img src="<?php echo plugins_url('', __FILE__) ?>/images/bot.png"
                                 style="cursor:pointer;border:none;background: transparent;"
                                 id="button26_<?php echo $ident ?>"/>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <script type="text/javascript">
            function flashShare(type, b, c) {
                u = location.href;
                u = u.replace('/?', '/index.php?');
                if (u.search('&AlbumId') != -1) {
                    var u_part2 = '';
                    u_part2 = u.substring(u.search('&TrackId') + 2, 1000)
                    if (u_part2.search('&') != -1) {
                        u_part2 = u_part2.substring(u_part2.search('&'), 1000);
                    }
                    u = u.replace(u.substring(u.search('&AlbumId'), 1000), '') + u_part2;
                }
                if (!location.search)
                    u = u + '?';
                else
                    u = u + '&';
                t = document.title;
                switch (type) {
                    case 'fb':
                        window.open('http://www.facebook.com/sharer.php?u=' + encodeURIComponent(u + 'AlbumId=' + b + '&TrackId=' + c) + '&t=' + encodeURIComponent(t), "Facebook", "menubar=1,resizable=1,width=350,height=250");
                        break;
                    case 'g':
                        window.open('http://plus.google.com/share?url=' + encodeURIComponent(u + 'AlbumId=' + b + '&TrackId=' + c) + '&t=' + encodeURIComponent(t), "Google", "menubar=1,resizable=1,width=350,height=250");
                        break;
                    case 'tw':
                        window.open('http://twitter.com/home/?status=' + encodeURIComponent(u + 'AlbumId=' + b + '&TrackId=' + c), "Twitter", "menubar=1,resizable=1,width=350,height=250");
                        break;
                }
            }
        </script>
        <div id="embed_Url_div_<?php echo $ident; ?>"
             style="display:none;text-align:center;background-color:rgba(0,0,0,0.5); height:160px;width:300px;position:relative;left:<?php echo ($theme->appWidth / 2) - 150 ?>px;top:-<?php echo ($theme->appHeight / 2) + 75 ?>px">
            <textarea
                onclick="jQuery('#embed_Url_<?php echo $ident ?>').focus(); jQuery('#embed_Url_<?php echo $ident ?>').select();"
                id="embed_Url_<?php echo $ident ?>" readonly="readonly"
                style="font-size:11px;width:285px;overflow-y:scroll;resize:none;height:100px;position:relative;top:5px;"></textarea>
            <span style="position:relative;top:10px;"><button
                    onclick="jQuery('#embed_Url_div_<?php echo $ident ?>').css('display','none')" style="border:0px">
                    Close
                </button><p style="color:white">Press Ctrl+C to copy the embed code to clipboard</p></span>
        </div>
        <div id="share_buttons_<?php echo $ident; ?>"
             style="text-align:center;height:113px;width:30px;background-color:rgba(0,0,0,0.5);position:relative;z-index:20000;top:<?php echo $share_top; ?>;display:none;">
            <img
                onclick="flashShare('fb',document.getElementById('current_playlist_table_<?php echo $ident; ?>').value,document.getElementById('current_track_<?php echo $ident; ?>').value)"
                style="cursor:pointer;  border:none;background: transparent;padding:0px;max-width: auto;"
                src="<?php echo plugins_url('', __FILE__) ?>/images/facebook.png"/><br>
            <img
                onclick="flashShare('tw',document.getElementById('current_playlist_table_<?php echo $ident; ?>').value,document.getElementById('current_track_<?php echo $ident; ?>').value)"
                style="cursor:pointer; border:none;background: transparent;padding:0px;max-width: auto;"
                src="<?php echo plugins_url('', __FILE__) ?>/images/twitter.png"/><br>
            <img
                onclick="flashShare('g',document.getElementById('current_playlist_table_<?php echo $ident; ?>').value,document.getElementById('current_track_<?php echo $ident; ?>').value)"
                style="cursor:pointer; border:none;background: transparent;padding:0px;max-width: auto;"
                src="<?php echo plugins_url('', __FILE__) ?>/images/googleplus.png"/><br>
            <img
                onclick="jQuery('#embed_Url_div_<?php echo $ident; ?>').css('display','');embed_url_<?php echo $ident; ?>(document.getElementById('current_playlist_table_<?php echo $ident; ?>').value,document.getElementById('current_track_<?php echo $ident; ?>').value)"
                style="cursor:pointer; border:none; background: transparent;padding:0px;max-width: auto;"
                src="<?php echo plugins_url('', __FILE__) ?>/images/embed.png"/>
        </div>
        </div>
    <?php
    $sufffle = str_replace('Shuffle', 'shuffle', $theme->defaultShuffle);
    if ($sufffle == 'shuffleOff')
        $shuffle = 0;
    else
        $shuffle = 1;
    $admin_url = admin_url('admin-ajax.php?action=spiderVeideoPlayervideoonly');
    ?>
        <input type="hidden" id="color_<?php echo $ident; ?>" value="<?php echo "#" .$theme->ctrlsMainColor ?>"/>
        <input type="hidden" id="support_<?php echo $ident; ?>" value="1"/>
        <input type="hidden" id="event_type_<?php echo $ident; ?>" value="mouseenter"/>
        <input type="hidden" id="current_track_<?php echo $ident; ?>" value="0"/>
        <input type="hidden" id="shuffle_<?php echo $ident; ?>" value="<?php echo $shuffle ?>"/>
        <input type="hidden" id="scroll_height_<?php echo $ident ?>" value="0"/>
        <input type="hidden" id="scroll_height2_<?php echo $ident; ?>" value="0"/>
        <input type="hidden" value="" id="lib_table_count_<?php echo $ident ?>"/>
        <input type="hidden" value="" id="current_lib_table_<?php echo $ident ?>"/>
        <input type="hidden" value="0" id="current_playlist_table_<?php echo $ident; ?>"/>
        <input type="hidden" value="<?php echo $theme->defaultRepeat ?>" id="repeat_<?php echo $ident ?>"/>
        <input type="hidden" value="0" id="HD_on_<?php echo $ident ?>"/>
        <input type="hidden" value="" id="volumeBar_width_<?php echo $ident ?>"/>
        <script>
        function is_youtube_video_<?php echo $ident ?>(){
            if(jQuery("#videoID_<?php echo $ident ?>").attr("src").indexOf("youtube.com/")>-1 || jQuery("#videoID_<?php echo $ident ?>").attr("src").indexOf("vimeo.com/")>-1){
                return true;}
            return false;
        }                 
        var video_<?php echo $ident;?> = jQuery('#videoID_<?php  echo $ident?>');
        var paly_<?php echo $ident;?> = jQuery('#global_body_<?php echo $ident;?> .btnPlay');
        var pause_<?php echo $ident;?> = jQuery('#global_body_<?php echo $ident;?> .btnPause');
        var check_play_<?php echo $ident;?> = false;
        function embed_url_<?php echo $ident;?>(a, b) {
           // jQuery('#embed_Url_<?php  echo $ident?>').html('<iframe allowFullScreen allowTransparency="true" frameborder="0" width="<?php echo $theme->appWidth ?>" height="<?php echo $theme->appHeight ?>" src="<?php echo $admin_url?>&single=1&priority=<?php echo $priority?>&video=<?php echo $ident?>&theme=<?php echo $theme_id?>&AlbumId=' + a + '&TrackId=' + b + '" type="text/html" ></iframe>')
            jQuery('#embed_Url_<?php  echo $ident?>').focus();
            jQuery('#embed_Url_<?php  echo $ident?>').select();
        }
        jQuery('#global_body_<?php echo $ident;?> .share_<?php echo $ident;?>, #global_body_<?php echo $ident;?> #share_buttons_<?php echo $ident;?>').on('mouseenter', function () {
            left = jQuery('#global_body_<?php echo $ident;?> .share_<?php echo $ident;?>').position().left
            if (parseInt(jQuery('#global_body_<?php echo $ident;?> #play_list_<?php  echo $ident?>').css('width')) == 0)
                jQuery('#global_body_<?php echo $ident;?> #share_buttons_<?php echo $ident;?>').css('left', left)
            else
                <?php if ($theme->playlistPos==1){ ?>
                jQuery('#global_body_<?php echo $ident;?> #share_buttons_<?php echo $ident;?>').css('left', left +<?php echo $theme->playlistWidth ?>)
            <?php } else {?>
            jQuery('#global_body_<?php echo $ident;?> #share_buttons_<?php echo $ident;?>').css('left', left)
            <?php }?>
            jQuery('#global_body_<?php echo $ident;?> #share_buttons_<?php echo $ident;?>').css('display', '')
        })
        jQuery('#global_body_<?php echo $ident;?> .share_<?php echo $ident;?>,#global_body_<?php echo $ident;?> #share_buttons_<?php echo $ident;?>').on('mouseleave', function () {
            jQuery('#global_body_<?php echo $ident;?> #share_buttons_<?php echo $ident;?>').css('display', 'none')
        })
        if (<?php echo $theme->autoPlay ?>==1
        )
        {
            setTimeout(function () {
                jQuery('#thumb_0_<?php echo $ident?>').click()
            }, 500);
            setTimeout(function () {
                video_<?php echo $ident;?>[0].click()
            }, 500);
        }
        <?php if($sufffle=='shuffleOff') {?>
        if (jQuery('#global_body_<?php echo $ident;?> .shuffle_<?php  echo $ident?>')[0]) {
            jQuery('#global_body_<?php echo $ident;?> .shuffle_<?php  echo $ident?>')[0].style.display = "none";
            jQuery('#global_body_<?php echo $ident;?> .shuffle_<?php  echo $ident?>')[1].style.display = "";
        }
        <?php
		}
		else
		{
		?>
        if (jQuery('#global_body_<?php echo $ident;?> .shuffle_<?php  echo $ident?>')[0]) {
            jQuery('#global_body_<?php echo $ident;?> .shuffle_<?php  echo $ident?>')[1].style.display = "none";
            jQuery('#global_body_<?php echo $ident;?> .shuffle_<?php  echo $ident?>')[0].style.display = "";
        }
        <?php } ?>
        jQuery('#global_body_<?php echo $ident;?> .fullScreen_<?php echo $ident;?>').on('click', function () {
            if (video_<?php echo $ident;?>[0].mozRequestFullScreen)
                video_<?php echo $ident;?>[0].mozRequestFullScreen();
            if (video_<?php echo $ident;?>[0].webkitEnterFullscreen)
                video_<?php echo $ident;?>[0].webkitEnterFullscreen()
        })
        jQuery('#global_body_<?php echo $ident;?> .stop_<?php echo $ident;?>').on('click', function () {
            video_<?php echo $ident;?>[0].currentTime = 0;
            video_<?php echo $ident;?>[0].pause();
            paly_<?php echo $ident;?>.css('display', "");
            pause_<?php echo $ident;?>.css('display', "none");
        })
        <?php if($theme->defaultRepeat=='repeatOff'){ ?>
        if (jQuery('#global_body_<?php echo $ident;?> .repeat_<?php  echo $ident?>')[0]) {
            jQuery('#global_body_<?php echo $ident;?> .repeat_<?php  echo $ident?>')[0].style.display = "none";
            jQuery('#global_body_<?php echo $ident;?> .repeat_<?php  echo $ident?>')[1].style.display = "";
            jQuery('#global_body_<?php echo $ident;?> .repeat_<?php  echo $ident?>')[2].style.display = "none";
        }
        <?php }?>
        <?php if($theme->defaultRepeat=='repeatOne'){ ?>
        if (jQuery('#global_body_<?php echo $ident;?> .repeat_<?php  echo $ident?>')[0]) {
            jQuery('#global_body_<?php echo $ident;?> .repeat_<?php  echo $ident?>')[0].style.display = "none";
            jQuery('#global_body_<?php echo $ident;?> .repeat_<?php  echo $ident?>')[1].style.display = "none";
            jQuery('#global_body_<?php echo $ident;?> .repeat_<?php  echo $ident?>')[2].style.display = "";
        }
        <?php }?>
        <?php if($theme->defaultRepeat=='repeatAll'){ ?>
        if (jQuery('.repeat_<?php  echo $ident?>')[0]) {
            jQuery('#global_body_<?php echo $ident;?> .repeat_<?php  echo $ident?>')[0].style.display = "";
            jQuery('#global_body_<?php echo $ident;?> .repeat_<?php  echo $ident?>')[1].style.display = "none";
            jQuery('#global_body_<?php echo $ident;?> .repeat_<?php  echo $ident?>')[2].style.display = "none";
        }
        <?php }?>
        jQuery('.repeat_<?php  echo $ident?>').on('click', function () {
            repeat_<?php  echo $ident?> = jQuery('#repeat_<?php  echo $ident?>').val();
            switch (repeat_<?php  echo $ident?>) {
                case 'repeatOff':
                    jQuery('#repeat_<?php  echo $ident?>').val('repeatOne');
                    jQuery('.repeat_<?php  echo $ident?>')[0].style.display = "none";
                    jQuery('.repeat_<?php  echo $ident?>')[1].style.display = "none";
                    jQuery('.repeat_<?php  echo $ident?>')[2].style.display = "";
                    break;
                case 'repeatOne':
                    jQuery('#repeat_<?php  echo $ident?>').val('repeatAll');
                    jQuery('.repeat_<?php  echo $ident?>')[0].style.display = "";
                    jQuery('.repeat_<?php  echo $ident?>')[1].style.display = "none";
                    jQuery('.repeat_<?php  echo $ident?>')[2].style.display = "none";
                    break;
                case 'repeatAll':
                    jQuery('#repeat_<?php  echo $ident?>').val('repeatOff');
                    jQuery('.repeat_<?php  echo $ident?>')[0].style.display = "none";
                    jQuery('.repeat_<?php  echo $ident?>')[1].style.display = "";
                    jQuery('.repeat_<?php  echo $ident?>')[2].style.display = "none";
                    break;
            }
        })
        jQuery('#global_body_<?php echo $ident;?> #voulume_img_<?php echo $ident;?>').on('click', function () {
            if (jQuery('#global_body_<?php echo $ident;?> .volume_<?php echo $ident;?>')[0].style.width != '0%') {
                video_<?php echo $ident;?>[0].volume = 0;
                jQuery('body #volumeBar_width_<?php  echo $ident?>').val(jQuery('#global_body_<?php echo $ident;?> .volume_<?php echo $ident;?>')[0].style.width)
                jQuery('#global_body_<?php echo $ident;?> .volume_<?php echo $ident;?>').css('width', '0%')
            }
            else {
                video_<?php echo $ident;?>[0].volume = 0;
              
                video_<?php echo $ident;?>[0].volume = parseInt(jQuery('body  #volumeBar_width_<?php  echo $ident?>').val()) / 100;
                jQuery('#global_body_<?php echo $ident;?> .volume_<?php echo $ident;?>').css('width', jQuery('body #volumeBar_width_<?php  echo $ident?>').val());
            }
        })
        
        
        jQuery('.hd_<?php echo $ident;?>').on('click',function(){
          current_time=video_<?php echo $ident;?>[0].currentTime;
          HD_on=jQuery('#HD_on_<?php echo $ident;?>').val();
          current_playlist_table=jQuery('#current_playlist_table_<?php echo $ident;?>').val();
          current_track=jQuery('#current_track_<?php echo $ident;?>').val();
          
          if(jQuery('#urlHD_<?php echo $ident;?>').val() && HD_on==0)
          {
          document.getElementById('videoID_<?php echo $ident;?>').src=jQuery('#urlHD_<?php echo $ident;?>').val();
          play_<?php echo $ident;?>();
          setTimeout('video_<?php echo $ident;?>[0].currentTime=current_time',500)
          jQuery('#HD_on_<?php echo $ident;?>').val(1);
          }
          if(jQuery('#urlHD_<?php echo $ident;?>').val() && HD_on==1)
          {
          document.getElementById('videoID_<?php echo $ident;?>').src=jQuery('#trackURL_<?php echo $ident;?>').val();
          play_<?php echo $ident;?>();
          setTimeout('video_<?php echo $ident;?>[0].currentTime=current_time',500)
          jQuery('#HD_on_<?php echo $ident;?>').val(0);
          }
        })
        function support_<?php echo $ident;?>(i, j) {
            if (jQuery('#track_list_<?php  echo $ident?>_' + i).find('#vid_type_' + j + '_<?php echo $ident?>').val() != 'http') {
                jQuery('#not_supported_<?php  echo $ident?>').css('display', '');
                jQuery('#support_<?php echo $ident;?>').val(0);
            }
            else {
                jQuery('#not_supported_<?php  echo $ident?>').css('display', 'none');
                jQuery('#support_<?php echo $ident;?>').val(1);
            }
        }
        jQuery('.play_<?php echo $ident;?>').on('click', function () {
        if(!is_youtube_video_<?php echo $ident ?>())
            video_<?php echo $ident;?>[0].play();
            else
                if(typeof player_<?php echo $ident;?> != 'undefined'){if(youtube_ready_<?php echo $ident ?>)player_<?php echo $ident ?>.playVideo();}                
        })
        jQuery('.pause_<?php echo $ident;?>').on('click', function () {
           if(!is_youtube_video_<?php echo $ident ?>())
            video_<?php echo $ident;?>[0].pause();
            else
           if(typeof player_<?php echo $ident;?> != 'undefined')player_<?php echo $ident;?>.pauseVideo();     
        })
        function vid_select_<?php echo $ident?>(x) {
            jQuery("div.vid_thumb_<?php echo $ident?>").each(function () {
                if (jQuery(this).find("img")) {
                    jQuery(this).find("img").hide(20);
                    if (jQuery(this).find("img")[0])
                        jQuery(this).find("img")[0].style.display = "none";
                }
                jQuery(this).css('background', 'none');
            })
            jQuery("div.vid_thumb_<?php echo $ident?>").each(function () {
                jQuery(this).mouseenter(function () {
                    if (jQuery(this).find("img"))
                        jQuery(this).find("img").slideDown(100);
                    jQuery(this).css('background', 'rgba(<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 0, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 2, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 4, 2)) ?>, <?php echo $theme->framesBgAlpha/100; ?>)')
                    jQuery(this).css('color', '#<?php echo $theme->textHoverColor  ?>')
                })
                jQuery(this).mouseleave(function () {
                    if (jQuery(this).find("img"))
                        jQuery(this).find("img").slideUp(300);
                    jQuery(this).css('background', 'none');
                    jQuery(this).css('color', '#<?php echo $theme->textColor  ?>')
                });
                jQuery(this).css('color', '#<?php echo $theme->textColor  ?>')
            })
            jQuery(x).unbind('mouseleave mouseenter');
            if (jQuery(x).find("img"))
                jQuery(x).find("img").show(10);
            jQuery(x).css('background', 'rgba(<?php echo HEXDEC(SUBSTR($theme->itemBgSelectedColor, 0, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->itemBgSelectedColor, 2, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->itemBgSelectedColor, 4, 2)) ?>, <?php echo $theme->framesBgAlpha/100; ?>)')
            jQuery(x).css('color', '#<?php echo $theme->textSelectedColor  ?>')
        }
        function vid_select2_<?php echo $ident?>(x) {
            jQuery("p.vid_title_<?php echo $ident?>").each(function () {
                this.onmouseover = function () {
                    this.style.color = '#' + '<?php echo $theme->textHoverColor?>';
                    this.style.background = 'rgba(<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 0, 2)) ?>,<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 2, 2))?>,<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 4, 2)) ?>,0.4)'
                }
                this.onmouseout = function () {
                    this.style.color = '<?php echo '#'.$theme->textColor ?>';
                    this.style.background = " none"
                }
                jQuery(this).css('background', 'none');
                jQuery(this).css('color', '#<?php echo $theme->textColor  ?>');
            })
            jQuery(x).css('background', 'rgba(<?php echo HEXDEC(SUBSTR($theme->itemBgSelectedColor, 0, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->itemBgSelectedColor, 2, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->itemBgSelectedColor, 4, 2)) ?>, <?php echo $theme->framesBgAlpha/100; ?>)')
            jQuery(x).css('color', '#<?php echo $theme->textSelectedColor  ?>')
            x.onmouseover = null;
            x.onmouseout = null;
        }
        function playlist_select_<?php echo $ident;?>(x) {
            jQuery("#global_body_<?php echo $ident;?> td.playlist_td_<?php echo $ident;?>").each(function () {
                jQuery(this).css('background', 'none');
                jQuery(this).css('color', '#<?php echo $theme->textColor  ?>');
                this.onmouseover = function () {
                    this.style.color = '#' + '<?php echo $theme->textHoverColor?>';
                    this.style.background = 'rgba(<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 0, 2)) ?>,<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 2, 2))?>,<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 4, 2)) ?>,0.4)'
                }
                this.onmouseout = function () {
                    this.style.color = '<?php echo '#'.$theme->textColor ?>';
                    this.style.background = " none"
                }
            })
            jQuery('#playlist_' + x + '_<?php  echo $ident?>').css('background', 'rgba(<?php echo HEXDEC(SUBSTR($theme->itemBgSelectedColor, 0, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->itemBgSelectedColor, 2, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->itemBgSelectedColor, 4, 2)) ?>, <?php echo $theme->framesBgAlpha/100; ?>)')
            jQuery('#playlist_' + x + '_<?php  echo $ident?>').css('color', '#<?php echo $theme->textSelectedColor  ?>')
            jQuery('#playlist_' + x + '_<?php  echo $ident?>')[0].onmouseover = null
            jQuery('#playlist_' + x + '_<?php  echo $ident?>')[0].onmouseout = null
        }
        jQuery('.shuffle_<?php  echo $ident?>').on('click', function () {
            if (jQuery('#shuffle_<?php  echo $ident?>').val() == 0) {
                jQuery('#shuffle_<?php  echo $ident?>').val(1);
                jQuery('.shuffle_<?php  echo $ident?>')[1].style.display = "none";
                jQuery('.shuffle_<?php  echo $ident?>')[0].style.display = "";
            }
            else {
                jQuery('#shuffle_<?php  echo $ident?>').val(0);
                jQuery('.shuffle_<?php  echo $ident?>')[0].style.display = "none";
                jQuery('.shuffle_<?php  echo $ident?>')[1].style.display = "";
            }
        });
        jQuery("div.vid_thumb_<?php echo $ident?>").each(function () {
            jQuery(this).mouseenter(function () {
                if (jQuery(this).find("img"))
                    jQuery(this).find("img").slideToggle(100);
                jQuery(this).css('background', 'rgba(<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 0, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 2, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 4, 2)) ?>, <?php echo $theme->framesBgAlpha/100; ?>)')
                jQuery(this).css('color', '#<?php echo $theme->textHoverColor  ?>')
            })
            jQuery(this).mouseleave(function () {
                if (jQuery(this).find("img"))
                    jQuery(this).find("img").slideToggle(300);
                jQuery(this).css('background', 'none');
                jQuery(this).css('color', '#<?php echo $theme->textColor  ?>')
            });
        })
        function timeUpdate_<?php  echo $ident?>() {
            if (parseInt(document.getElementById('videoID_<?php  echo $ident?>').currentTime / 60) < 10 && parseInt(document.getElementById('videoID_<?php  echo $ident?>').currentTime % 60 < 10))
                document.getElementById('time_<?php  echo $ident?>').innerHTML = '0' + parseInt(document.getElementById('videoID_<?php  echo $ident?>').currentTime / 60) + ':0' + parseInt(document.getElementById('videoID_<?php  echo $ident?>').currentTime % 60);
            if (parseInt(document.getElementById('videoID_<?php  echo $ident?>').currentTime / 60) < 10)
                document.getElementById('time_<?php  echo $ident?>').innerHTML = '0' + parseInt(document.getElementById('videoID_<?php  echo $ident?>').currentTime / 60) + ':' + parseInt(document.getElementById('videoID_<?php  echo $ident?>').currentTime % 60);
            if (parseInt(document.getElementById('videoID_<?php  echo $ident?>').currentTime % 60) < 10)
                document.getElementById('time_<?php  echo $ident?>').innerHTML = '0' + parseInt(document.getElementById('videoID_<?php  echo $ident?>').currentTime / 60) + ':0' + parseInt(document.getElementById('videoID_<?php  echo $ident?>').currentTime % 60);
        }
        function durationChange_<?php  echo $ident?>() {
            if (parseInt(document.getElementById('videoID_<?php  echo $ident?>').duration / 60) < 10 && parseInt(document.getElementById('videoID_<?php  echo $ident?>').duration % 60 < 10))
                document.getElementById('duration_<?php  echo $ident?>').innerHTML = '0' + parseInt(document.getElementById('videoID_<?php  echo $ident?>').duration / 60) + ':0' + parseInt(document.getElementById('videoID_<?php  echo $ident?>').duration % 60);
            if (parseInt(document.getElementById('videoID_<?php  echo $ident?>').duration / 60) < 10)
                document.getElementById('duration_<?php  echo $ident?>').innerHTML = '0' + parseInt(document.getElementById('videoID_<?php  echo $ident?>').duration / 60) + ':' + parseInt(document.getElementById('videoID_<?php  echo $ident?>').duration % 60);
            if (parseInt(document.getElementById('videoID_<?php  echo $ident?>').duration % 60) < 10)
                document.getElementById('duration_<?php  echo $ident?>').innerHTML = parseInt(document.getElementById('videoID_<?php  echo $ident?>').duration / 60) + ':0' + parseInt(document.getElementById('videoID_<?php  echo $ident?>').duration % 60);
        }
        function scrollBottom_<?php echo $ident;?>() {
            current_playlist_table_<?php echo $ident;?> = document.getElementById('current_playlist_table_<?php echo $ident;?>').value;
            if (document.getElementById('scroll_div_' + current_playlist_table_<?php  echo $ident?> + '_<?php echo $ident?>').offsetHeight + parseInt(document.getElementById("scroll_div_" + current_playlist_table_<?php  echo $ident?> + '_<?php echo $ident?>').style.top) + 55 <= document.getElementById('global_body_<?php  echo $ident?>').offsetHeight)
                return false;
            document.getElementById('scroll_height_<?php  echo $ident?>').value = parseInt(document.getElementById('scroll_height_<?php  echo $ident?>').value) + 5
            document.getElementById("scroll_div_" + current_playlist_table_<?php echo $ident;?> + '_<?php echo $ident?>').style.top = "-" + document.getElementById('scroll_height_<?php  echo $ident?>').value + "px";
        }
        ;
        function scrollTop_<?php echo $ident;?>() {
            current_playlist_table_<?php  echo $ident?> = document.getElementById('current_playlist_table_<?php echo $ident;?>').value;
            if (document.getElementById('scroll_height_<?php  echo $ident?>').value <= 0)
                return false;
            document.getElementById('scroll_height_<?php  echo $ident?>').value = parseInt(document.getElementById('scroll_height_<?php  echo $ident?>').value) - 5
            document.getElementById("scroll_div_" + current_playlist_table_<?php  echo $ident?> + '_<?php echo $ident?>').style.top = "-" + document.getElementById('scroll_height_<?php  echo $ident?>').value + "px";
        }
        ;
        function scrollBottom2_<?php echo $ident;?>() {
            current_playlist_table_<?php  echo $ident?> = document.getElementById('current_playlist_table_<?php echo $ident;?>').value;
            if (!current_playlist_table_<?php  echo $ident?>) {
                current_playlist_table_<?php  echo $ident?> = 0;
            }
            if (document.getElementById('scroll_div2_' + current_playlist_table_<?php  echo $ident?> + '_<?php  echo $ident?>').offsetHeight + parseInt(document.getElementById("scroll_div2_" + current_playlist_table_<?php  echo $ident?> + "_<?php  echo $ident?>").style.top) + 150 <= document.getElementById('global_body_<?php  echo $ident?>').offsetHeight)
                return false;
            document.getElementById('scroll_height2_<?php echo $ident;?>').value = parseInt(document.getElementById('scroll_height2_<?php echo $ident;?>').value) + 5
            document.getElementById("scroll_div2_" + current_playlist_table_<?php  echo $ident?> + "_<?php  echo $ident?>").style.top = "-" + document.getElementById('scroll_height2_<?php echo $ident;?>').value + "px";
        }
        ;
        function scrollTop2_<?php echo $ident;?>() {
            current_playlist_table_<?php  echo $ident?> = document.getElementById('current_playlist_table_<?php echo $ident;?>').value;
            if (document.getElementById('scroll_height2_<?php echo $ident;?>').value <= 0)
                return false;
            document.getElementById('scroll_height2_<?php echo $ident;?>').value = parseInt(document.getElementById('scroll_height2_<?php echo $ident;?>').value) - 5
            document.getElementById("scroll_div2_" + current_playlist_table_<?php  echo $ident?> + "_<?php  echo $ident?>").style.top = "-" + document.getElementById('scroll_height2_<?php echo $ident;?>').value + "px";
        }
        ;
        function openPlaylist_<?php  echo $ident?>(i, j) {
            document.getElementById('scroll_height_<?php  echo $ident?>').value = 0;
            lib_table_count_<?php  echo $ident?> = document.getElementById('lib_table_count_<?php  echo $ident?>').value;
            for (lib_table = 0; lib_table < lib_table_count_<?php  echo $ident?>; lib_table++) {
                document.getElementById('lib_table_' + lib_table + '_<?php  echo $ident?>').style.display = "none";
            }
            jQuery("#playlist_table_" + i + "_<?php  echo $ident?>").fadeIn(700);
            document.getElementById('current_lib_table_<?php  echo $ident?>').value = j;
            document.getElementById('current_playlist_table_<?php echo $ident;?>').value = i;
            document.getElementById('tracklist_down_<?php  echo $ident?>').style.display = "";
            document.getElementById('tracklist_up_<?php  echo $ident?>').style.display = "";
            document.getElementById('button29_<?php  echo $ident?>').style.display = "block";
            document.getElementById('button27_<?php  echo $ident?>').onclick = function () {
                nextPlaylist_<?php echo $ident;?>()
            };
            document.getElementById('button28_<?php  echo $ident?>').onclick = function () {
                prevPlaylist_<?php echo $ident;?>()
            };
        }
        function nextPlaylist_<?php echo $ident;?>() {
            document.getElementById('scroll_height_<?php  echo $ident?>').value = 0;
            lib_table_count_<?php  echo $ident?> = document.getElementById('lib_table_count_<?php  echo $ident?>').value;
            for (lib_table = 0; lib_table < lib_table_count_<?php  echo $ident?>; lib_table++) {
                document.getElementById('lib_table_' + lib_table + '_<?php  echo $ident?>').style.display = "none";
            }
            current_lib_table_<?php  echo $ident?> = document.getElementById('current_lib_table_<?php  echo $ident?>').value;
            next_playlist_table_<?php  echo $ident?> = parseInt(document.getElementById('current_playlist_table_<?php echo $ident;?>').value) + 1;
            current_playlist_table_<?php  echo $ident?> = parseInt(document.getElementById('current_playlist_table_<?php echo $ident;?>').value);
            if (next_playlist_table_<?php  echo $ident?> ><?php  echo count($playlist_array)-2 ?>)
                return false;
            jQuery("#playlist_table_" + current_playlist_table_<?php  echo $ident?> + "_<?php  echo $ident?>").css('display', 'none');
            jQuery("#playlist_table_" + next_playlist_table_<?php  echo $ident?> + "_<?php  echo $ident?>").fadeIn(700);
            document.getElementById('current_playlist_table_<?php echo $ident;?>').value = next_playlist_table_<?php  echo $ident?>;
            document.getElementById('tracklist_down_<?php  echo $ident?>').style.display = "";
            document.getElementById('tracklist_up_<?php  echo $ident?>').style.display = "";
            document.getElementById('button29_<?php  echo $ident?>').style.display = "block";
        }
        function prevPlaylist_<?php echo $ident;?>() {
            document.getElementById('scroll_height_<?php  echo $ident?>').value = 0;
            lib_table_count_<?php  echo $ident?> = document.getElementById('lib_table_count_<?php  echo $ident?>').value;
            for (lib_table = 0; lib_table < lib_table_count_<?php  echo $ident?>; lib_table++) {
                document.getElementById('lib_table_' + lib_table + '_<?php  echo $ident?>').style.display = "none";
            }
            current_lib_table_<?php  echo $ident?> = document.getElementById('current_lib_table_<?php  echo $ident?>').value;
            prev_playlist_table_<?php  echo $ident?> = parseInt(document.getElementById('current_playlist_table_<?php echo $ident;?>').value) - 1;
            current_playlist_table_<?php  echo $ident?> = parseInt(document.getElementById('current_playlist_table_<?php echo $ident;?>').value);
            if (prev_playlist_table_<?php  echo $ident?> < 0)
                return false;
            jQuery("#playlist_table_" + current_playlist_table_<?php  echo $ident?> + "_<?php  echo $ident?>").css('display', 'none');
            jQuery("#playlist_table_" + prev_playlist_table_<?php  echo $ident?> + "_<?php  echo $ident?>").fadeIn(700);
            document.getElementById('current_playlist_table_<?php echo $ident;?>').value = prev_playlist_table_<?php  echo $ident?>;
            document.getElementById('tracklist_down_<?php  echo $ident?>').style.display = "";
            document.getElementById('tracklist_up_<?php  echo $ident?>').style.display = "";
            document.getElementById('button29_<?php  echo $ident?>').style.display = "block";
        }
        function openLibTable_<?php  echo $ident?>() {
            current_lib_table_<?php  echo $ident?> = document.getElementById('current_lib_table_<?php  echo $ident?>').value;
            document.getElementById('scroll_height_<?php  echo $ident?>').value = 0;
            current_playlist_table_<?php  echo $ident?> = document.getElementById('current_playlist_table_<?php echo $ident;?>').value;
            jQuery("#lib_table_" + current_lib_table_<?php  echo $ident?> + "_<?php  echo $ident?>").fadeIn(700);
            document.getElementById('playlist_table_' + current_playlist_table_<?php  echo $ident?> + '_<?php  echo $ident?>').style.display = "none";
            document.getElementById('tracklist_down_<?php  echo $ident?>').style.display = "none";
            document.getElementById('tracklist_up_<?php  echo $ident?>').style.display = "none";
            document.getElementById('button29_<?php  echo $ident?>').style.display = "none";
            document.getElementById('button27_<?php  echo $ident?>').onclick = function () {
                nextPage_<?php  echo $ident?>()
            };
            document.getElementById('button28_<?php  echo $ident?>').onclick = function () {
                prevPage_<?php  echo $ident?>()
            };
        }
        var next_page_<?php  echo $ident?> = 0;
        function nextPage_<?php  echo $ident?>() {
            if (next_page_<?php  echo $ident?> == document.getElementById('lib_table_count_<?php  echo $ident?>').value - 1)
                return false;
            next_page_<?php  echo $ident?> = next_page_<?php  echo $ident?> + 1;
            for (g = 0; g < document.getElementById('lib_table_count_<?php  echo $ident?>').value; g++) {
                document.getElementById('lib_table_' + g + '_<?php  echo $ident?>').style.display = "none";
                if (g == next_page_<?php  echo $ident?>) {
                    jQuery("#lib_table_" + g + "_<?php  echo $ident?>").fadeIn(900);
                }
            }
        }
        function prevPage_<?php  echo $ident?>() {
            if (next_page_<?php  echo $ident?> == 0)
                return false;
            next_page_<?php  echo $ident?> = next_page_<?php  echo $ident?> - 1;
            for (g = 0; g < document.getElementById('lib_table_count_<?php  echo $ident?>').value; g++) {
                document.getElementById('lib_table_' + g + '_<?php  echo $ident?>').style.display = "none";
                if (g == next_page_<?php  echo $ident?>) {
                    jQuery("#lib_table_" + g + "_<?php  echo $ident?>").fadeIn(900);
                }
            }
        }
        function playBTN_<?php echo $ident;?>() {
            current_playlist_table_<?php  echo $ident?> = document.getElementById('current_playlist_table_<?php echo $ident;?>').value;
            track_list_<?php  echo $ident?> = document.getElementById('track_list_<?php echo $ident;?>').value;
            document.getElementById('track_list_<?php echo $ident;?>_' + current_playlist_table_<?php  echo $ident?>).style.display = "block";
            if (current_playlist_table_<?php  echo $ident?> != track_list_<?php  echo $ident?>)
                document.getElementById('track_list_<?php echo $ident;?>_' + track_list_<?php  echo $ident?>).style.display = "none";
            document.getElementById('track_list_<?php echo $ident;?>').value = current_playlist_table_<?php  echo $ident?>;
            video_<?php echo $ident;?>[0].play();
            paly_<?php echo $ident;?>.css('display', "none");
            pause_<?php echo $ident;?>.css('display', "");
        }
        function play_<?php echo $ident;?>() {
     
            if(!is_youtube_video_<?php echo $ident ?>())
            video_<?php echo $ident;?>[0].play()
            else
                if(typeof player_<?php echo $ident;?> != 'undefined'){if(youtube_ready_<?php echo $ident ?>)player_<?php echo $ident ?>.playVideo();}
            paly_<?php echo $ident;?>.css('display', "none");
            pause_<?php echo $ident;?>.css('display', "");
        }
        jQuery('#global_body_<?php echo $ident;?> .btnPlay <?php if($theme->clickOnVid==1) echo ',#videoID_'.$ident.'' ?>, #global_body_<?php echo $ident;?> .btnPause').on('click', function () {
            if (video_<?php echo $ident;?>[0].paused) {
                if(!is_youtube_video_<?php echo $ident ?>())
                video_<?php echo $ident;?>[0].play();
                else
                    if(typeof player_<?php echo $ident;?> != 'undefined'){if(youtube_ready_<?php echo $ident ?>)player_<?php echo $ident ?>.playVideo();}
                paly_<?php echo $ident;?>.css('display', "none");
                pause_<?php echo $ident;?>.css('display', "");
            }
            else {
                if(!is_youtube_video_<?php echo $ident ?>())
                video_<?php echo $ident;?>[0].pause();
                else
                    if(typeof player_<?php echo $ident;?> != 'undefined')player_<?php echo $ident;?>.pauseVideo();
                paly_<?php echo $ident;?>.css('display', "");
                pause_<?php echo $ident;?>.css('display', "none");
            }
            return false;
        });
        function check_volume_<?php echo $ident;?>() {
            percentage_<?php echo $ident;?> = video_<?php echo $ident;?>[0].volume * 100;
            jQuery('#global_body_<?php echo $ident;?> .volume_<?php echo $ident;?>').css('width', percentage_<?php echo $ident;?> + '%');
            document.getElementById("play_list_<?php  echo $ident?>").style.width = '0px';
            document.getElementById("play_list_<?php  echo $ident?>").style.display = 'none';
        }
        window.onload = check_volume_<?php echo $ident;?>();
        video_<?php echo $ident;?>.on('loadedmetadata', function () {
            jQuery('.duration_<?php echo $ident?>').text(video_<?php echo $ident;?>[0].duration);
        });
        video_<?php echo $ident;?>.on('timeupdate', function () {
            var progress_<?php  echo $ident?> = jQuery('#global_body_<?php echo $ident;?> .progressBar_<?php  echo $ident?>');
            var currentPos_<?php  echo $ident?> = video_<?php echo $ident;?>[0].currentTime; //Get currenttime
            var maxduration_<?php  echo $ident?> = video_<?php echo $ident;?>[0].duration; //Get video duration  
            var percentage_<?php  echo $ident?> = 100 * currentPos_<?php  echo $ident?> / maxduration_<?php echo $ident;?>; //in %
            var position_<?php  echo $ident?> = (<?php echo $theme->appWidth; ?> * percentage_<?php  echo $ident?> / 100
            )
            -progress_<?php  echo $ident?>.offset().left;
            jQuery('#global_body_<?php echo $ident;?> .timeBar_<?php  echo $ident?>').css('width', percentage_<?php  echo $ident?> + '%');
        });
        video_<?php echo $ident;?>.on('ended', function () {
            if (jQuery('#repeat_<?php  echo $ident?>').val() == "repeatOne") {
                video_<?php echo $ident;?>[0].currentTime = 0;
                video_<?php echo $ident;?>[0].play();
                paly_<?php echo $ident;?>.css('display', "none");
                pause_<?php echo $ident;?>.css('display', "");
            }
            if (jQuery('#repeat_<?php  echo $ident?>').val() == "repeatAll") {
                jQuery('#global_body_<?php echo $ident;?> .playNext_<?php  echo $ident?>').click();
            }
            if (jQuery('#repeat_<?php  echo $ident?>').val() == "repeatOff") {
                if (vid_num_<?php  echo $ident?> == video_urls_<?php  echo $ident?>.length - 1) {
                    video_<?php echo $ident;?>[0].currentTime = 0;
                    video_<?php echo $ident;?>[0].pause();
                    paly_<?php echo $ident;?>.css('display', "");
                    pause_<?php echo $ident;?>.css('display', "none");
                }
            }
            <?php if($theme->autoNext==1) { ?>
            if (jQuery('#repeat_<?php  echo $ident?>').val() == "repeatOff")
                if (vid_num_<?php  echo $ident?> == video_urls_<?php  echo $ident?>.length - 1) {
                    video_<?php echo $ident;?>[0].currentTime = 0;
                    video_<?php echo $ident;?>[0].pause();
                    paly_<?php echo $ident;?>.css('display', "");
                    pause_<?php echo $ident;?>.css('display', "none");
                }
                else {
                    jQuery('#global_body_<?php echo $ident;?> .playNext_<?php echo $ident;?>').click();
                }
            <?php }?>
        })
        var timeDrag_<?php echo $ident;?> = false;
        /* Drag status */
        jQuery('#global_body_<?php echo $ident;?> .progressBar_<?php  echo $ident?>').mousedown(function (e) {
            timeDrag_<?php echo $ident;?> = true;
            updatebar_<?php  echo $ident?>(e.pageX);
        });
        jQuery('#global_body_<?php echo $ident;?> .progressBar_<?php  echo $ident?>').select(function () {
        })
        jQuery(document).mouseup(function (e) {
            if (timeDrag_<?php echo $ident;?>) {
                timeDrag_<?php echo $ident;?> = false;
                updatebar_<?php  echo $ident?>(e.pageX);
            }
        });
        jQuery(document).mousemove(function (e) {
            if (timeDrag_<?php echo $ident;?>) {
                updatebar_<?php  echo $ident?>(e.pageX);
            }
        });
        var updatebar_<?php  echo $ident?> = function (x) {
            var progress_<?php  echo $ident?> = jQuery('#global_body_<?php echo $ident;?> .progressBar_<?php  echo $ident?>');
            var maxduration_<?php  echo $ident?> = video_<?php echo $ident;?>[0].duration; //Video duraiton
            var position_<?php  echo $ident?> = x - progress_<?php  echo $ident?>.offset().left; //Click pos
            var percentage_<?php  echo $ident?> = 100 * position_<?php  echo $ident?> / progress_<?php  echo $ident?>.width();
            if (percentage_<?php  echo $ident?> > 100) {
                percentage_<?php  echo $ident?> = 100;
            }
            if (percentage_<?php  echo $ident?> < 0) {
                percentage_<?php  echo $ident?> = 0;
            }
            jQuery('#global_body_<?php echo $ident;?> .timeBar_<?php  echo $ident?>').css('width', percentage_<?php  echo $ident?> + '%');
            jQuery('.spanA').css('left', position_<?php  echo $ident?> + 'px');
            video_<?php echo $ident;?>[0].currentTime = maxduration_<?php  echo $ident?> * percentage_<?php  echo $ident?> / 100;
        };
        function startBuffer_<?php echo $ident;?>() {
            setTimeout(function () {
                var maxduration_<?php echo $ident;?> = video_<?php echo $ident;?>[0].duration;
                var currentBuffer_<?php echo $ident;?> = video_<?php echo $ident;?>[0].buffered.end(0);
                var percentage_<?php echo $ident;?> = 100 * currentBuffer_<?php echo $ident;?> / maxduration_<?php echo $ident;?>;
                jQuery('#global_body_<?php echo $ident;?> .bufferBar_<?php  echo $ident?>').css('width', percentage_<?php echo $ident;?> + '%');
                if (currentBuffer_<?php echo $ident;?> < maxduration_<?php echo $ident;?>) {
                    setTimeout(startBuffer_<?php echo $ident;?>, 500);
                }
            }, 800)
        }
        ;
        checkVideoLoad = setInterval(function () {
            if (video_<?php echo $ident;?>[0].duration) {
                setTimeout(startBuffer_<?php echo $ident;?>(), 500);
                clearInterval(checkVideoLoad)
            }
        }, 1000)
        var volume_<?php echo $ident;?> = jQuery('#global_body_<?php echo $ident;?> .volumeBar_<?php echo $ident;?>');
        jQuery('#global_body_<?php echo $ident;?> .muted').click(function () {
        alert(video_<?php echo $ident;?>[0].muted = !video_<?php echo $ident;?>[0].muted)
            video_<?php echo $ident;?>[0].muted = !video_<?php echo $ident;?>[0].muted;
            return false;
        });
        jQuery('#global_body_<?php echo $ident;?> .volumeBar_<?php echo $ident;?>').on('mousedown', function (e) {
            var position_<?php echo $ident;?> = e.pageX - volume_<?php echo $ident;?>.offset().left;
            var percentage_<?php  echo $ident?> = 100 * position_<?php echo $ident;?> / volume_<?php echo $ident;?>.width();
            jQuery('#global_body_<?php echo $ident;?> .volume_<?php echo $ident;?>').css('width', percentage_<?php  echo $ident?> + '%');
            video_<?php echo $ident;?>[0].volume = percentage_<?php  echo $ident?> / 100;
        });
        var volumeDrag_<?php  echo $ident?> = false;
        /* Drag status */
        jQuery('#global_body_<?php echo $ident;?> .volumeBar_<?php echo $ident;?>').mousedown(function (e) {
            volumeDrag_<?php  echo $ident?> = true;
            updateVolumeBar_<?php  echo $ident?>(e.pageX);
        });
        jQuery(document).mouseup(function (e) {
            if (volumeDrag_<?php  echo $ident?>) {
                volumeDrag_<?php  echo $ident?> = false;
                updateVolumeBar_<?php  echo $ident?>(e.pageX);
            }
        });
        jQuery(document).mousemove(function (e) {
            if (volumeDrag_<?php  echo $ident?>) {
                updateVolumeBar_<?php  echo $ident?>(e.pageX);
            }
        });
        var updateVolumeBar_<?php  echo $ident?> = function (x) {
            var progress_<?php  echo $ident?> = jQuery('#global_body_<?php echo $ident;?> .volumeBar_<?php echo $ident;?>');
            var position_<?php echo $ident;?> = x - progress_<?php  echo $ident?>.offset().left; //Click pos
            var percentage_<?php  echo $ident?> = 100 * position_<?php echo $ident;?> / progress_<?php  echo $ident?>.width();
            if (percentage_<?php  echo $ident?> > 100) {
                percentage_<?php  echo $ident?> = 100;
            }
            if (percentage_<?php  echo $ident?> < 0) {
                percentage_<?php  echo $ident?> = 0;
            }
            jQuery('#global_body_<?php echo $ident;?> .volume_<?php echo $ident;?>').css('width', percentage_<?php  echo $ident?> + '%');
            video_<?php echo $ident;?>[0].volume = percentage_<?php  echo $ident?> / 100;
        };
        var yy = 1;
        controlHideTime_<?php  echo $ident?> = '';
        jQuery("#global_body_<?php  echo $ident?>").each(function () {
            jQuery(this).mouseleave(function () {
                controlHideTime_<?php  echo $ident?> = setInterval(function () {
                    yy = yy + 1;
                    if (yy <<?php echo $theme->autohideTime ?>) {
                        return false
                    }
                    else {
                        clearInterval(controlHideTime_<?php  echo $ident?>);
                        yy = 1;
                        jQuery("#event_type_<?php echo $ident;?>").val('mouseleave');
                        <?php if($theme->playlistAutoHide==1){ ?>
                        jQuery("#play_list_<?php  echo $ident?>").animate({
                            width: "0px",
                        }, 300);
                        setTimeout(function () {
                            jQuery("#play_list_<?php  echo $ident?>").css('display', 'none');
                        }, 300)
                        jQuery("#global_body_<?php echo $ident;?> .control_<?php  echo $ident?>").animate({
                            width: <?php echo $theme->appWidth; ?>+"px",
                            <?php if ($theme->playlistPos==1){ ?>
                            marginLeft: '0px'
                            <?php } else {?>
                            marginRight: '0px'
                            <?php } ?>
                        }, 300);
                        jQuery("#global_body_<?php echo $ident;?> #control_btns_<?php  echo $ident?>").animate({
                            width: <?php echo $theme->appWidth?>+"px",
                        }, 300);
                        /*jQuery("#space").animate({
                         paddingLeft:
                        <?php echo (($theme->appWidth*20)/100) ?>+"px"},300)*/
                        <?php }?>
                        <?php if($theme->ctrlsSlideOut==1){ ?>
                        jQuery('#global_body_<?php echo $ident;?> .control_<?php  echo $ident?>').hide("slide", { direction: "<?php if($theme->ctrlsPos==1) echo 'up'; else echo 'down'; ?>"
                        }, 1000);
                        <?php } ?>
                    }
                }, 1000);
            });
            jQuery(this).mouseenter(function () {
                if (controlHideTime_<?php  echo $ident?>) {
                    clearInterval(controlHideTime_<?php  echo $ident?>)
                    yy = 1;
                }
                if (document.getElementById('control_<?php  echo $ident?>').style.display == "none") {
                    jQuery('#global_body_<?php echo $ident;?> .control_<?php  echo $ident?>').show("slide", { direction: "<?php if($theme->ctrlsPos==1) echo 'up'; else echo 'down'; ?>" }, 450);
                    
                }
            })
        })
        var xx = 1;
        volumeHideTime_<?php echo $ident;?> = '';
        jQuery("#volumeTD_<?php echo $ident;?>").each(function () {
            jQuery('#volumeTD_<?php echo $ident;?>').mouseleave(function () {
                volumeHideTime_<?php echo $ident;?> = setInterval(function () {
                    xx = xx + 1;
                    if (xx < 2) {
                        return false
                    }
                    else {
                        clearInterval(volumeHideTime_<?php echo $ident;?>);
                        xx = 1;
                        jQuery("#global_body_<?php echo $ident;?> #space").animate({
                            paddingLeft:<?php echo '"'.(($theme->appWidth*20)/100).'px"'; ?>,
                        }, 1000);
                        jQuery("#global_body_<?php echo $ident;?> #volumebar_player_<?php echo $ident;?>").animate({
                            width: '0px',
                        }, 1000);
                        percentage_<?php  echo $ident?> = video_<?php echo $ident;?>[0].volume * 100;
                        jQuery('#global_body_<?php echo $ident;?> .volume_<?php echo $ident;?>').css('width', percentage_<?php  echo $ident?> + '%');
                    }
                }, 1000)
            })
            jQuery('#volumeTD_<?php echo $ident;?>').mouseenter(function () {
                if (volumeHideTime_<?php echo $ident;?>) {
                    clearInterval(volumeHideTime_<?php echo $ident;?>)
                    xx = 1;
                }
                jQuery("#global_body_<?php echo $ident;?> #space").animate({
                    paddingLeft:<?php echo '"'.((($theme->appWidth*20)/100)-100).'px"' ?>,
                }, 500);
                jQuery("#global_body_<?php echo $ident;?> #volumebar_player_<?php echo $ident;?>").animate({
                    <?php if($theme->appWidth > 400){ ?>
                    width: '100px',
                    <?php } 
else { ?>
                    width: '50px',
                    <?php } ?>
                }, 500);
            });
        })
        jQuery('#global_body_<?php echo $ident;?> .playlist_<?php  echo $ident?>').on('click', function () {
            if (document.getElementById("play_list_<?php  echo $ident?>").style.width == "0px") {
                jQuery("#play_list_<?php  echo $ident?>").css('display', '')
                jQuery("#play_list_<?php  echo $ident?>").animate({
                    width: <?php echo $theme->playlistWidth; ?>+"px",
                }, 500);
                jQuery("#global_body_<?php echo $ident;?> .control_<?php  echo $ident?>").animate({
                    width: <?php echo $theme->appWidth-$theme->playlistWidth; ?>+"px",
                    <?php if ($theme->playlistPos==1){ ?>
                    marginLeft: <?php echo $theme->playlistWidth; ?>+'px'
                    <?php } else {?>
                    marginRight: <?php echo $theme->playlistWidth; ?>+'px'
                    <?php } ?>
                }, 500);
                /*jQuery("#space").animate({paddingLeft:
                <?php echo (($theme->appWidth*20)/100)-$theme->playlistWidth ?>+"px"},500)*/
                jQuery("#global_body_<?php echo $ident;?> #control_btns_<?php  echo $ident?>").animate({
                    width: <?php echo $theme->appWidth-$theme->playlistWidth; ?>+"px",
                }, 500);
            }
            else {
                jQuery("#global_body_<?php echo $ident;?> #play_list_<?php  echo $ident?>").animate({
                    width: "0px",
                }, 1500);
                setTimeout(function () {
                    jQuery("#play_list_<?php  echo $ident?>").css('display', 'none');
                }, 1500)
                jQuery("#global_body_<?php echo $ident;?> .control_<?php  echo $ident?>").animate({
                    width: <?php echo $theme->appWidth; ?>+"px",
                    <?php if ($theme->playlistPos==1){ ?>
                    marginLeft: '0px'
                    <?php } else {?>
                    marginRight: '0px'
                    <?php } ?>
                }, 1500);
                jQuery("#global_body_<?php echo $ident;?> #control_btns_<?php  echo $ident?>").animate({
                    width: <?php echo $theme->appWidth?>+"px",
                }, 1500);
                /*jQuery("#space").animate({paddingLeft:
                <?php echo (($theme->appWidth*20)/100)?>+'px'},1500)*/
            }
        });
        current_playlist_table_<?php  echo $ident?> = document.getElementById('current_playlist_table_<?php echo $ident;?>').value;
        video_urls_<?php echo $ident;?> = jQuery('#track_list_<?php  echo $ident?>_' + current_playlist_table_<?php  echo $ident?>).find('.vid_thumb_<?php echo $ident?>');
        function current_playlist_videos_<?php  echo $ident?>() {
            current_playlist_table_<?php  echo $ident?> = document.getElementById('current_playlist_table_<?php echo $ident;?>').value;
            video_urls_<?php  echo $ident?> = jQuery('#track_list_<?php  echo $ident?>_' + current_playlist_table_<?php  echo $ident?>).find('.vid_thumb_<?php echo $ident?>');
        }
        function in_array(needle, haystack, strict) {	// Checks if a value exists in an array
            // 
            // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
            var found = false, key, strict = !!strict;
            for (key in haystack) {
                if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {
                    found = true;
                    break;
                }
            }
            return found;
        }
        var vid_num_<?php  echo $ident?> = 0; //for set cur video number
        var used_track_<?php  echo $ident?> = new Array(); // played vido numbers 
        jQuery('.playPrev_<?php  echo $ident?>').on('click', function () {
            next_vid_<?php  echo $ident?> = true;
            used_track_<?php  echo $ident?>[used_track_<?php  echo $ident?>.length] = vid_num_<?php  echo $ident?>;
            vid_num_<?php  echo $ident?>--;
            if (used_track_<?php  echo $ident?>.length >= video_urls_<?php  echo $ident?>.length) {
                // reset old list
                used_track_<?php  echo $ident?> = [];
                if (jQuery('#shuffle_<?php  echo $ident?>').val() == 1) {
// get new vido number out of used_tracks
                    vid_num_<?php  echo $ident?> = parseInt(Math.random() * (video_urls_<?php  echo $ident?>.length - 0) + 0);
                    while (in_array(vid_num_<?php  echo $ident?>, used_track_<?php  echo $ident?>)) {
                        vid_num_<?php  echo $ident?> = parseInt(Math.random() * (video_urls_<?php  echo $ident?>.length - 0) + 0);
                    }
                }
            }
            if (jQuery('#shuffle_<?php  echo $ident?>').val() == 1) {
// get new vido number out of used_tracks
                vid_num_<?php  echo $ident?> = parseInt(Math.random() * (video_urls_<?php  echo $ident?>.length - 0) + 0);
                while (in_array(vid_num_<?php  echo $ident?>, used_track_<?php  echo $ident?>)) {
                    vid_num_<?php  echo $ident?> = parseInt(Math.random() * (video_urls_<?php  echo $ident?>.length - 0) + 0);
                }
            }
            if (vid_num_<?php  echo $ident?> < 0) {
                vid_num_<?php  echo $ident?> = video_urls_<?php  echo $ident?>.length - 1;
            }
            //jQuery('.playPrev_<?php  echo $ident?>').click();
            video_urls_<?php  echo $ident?>[vid_num_<?php  echo $ident?>].click();

        });
        jQuery('#global_body_<?php echo $ident;?> .playNext_<?php echo $ident;?>').on('click', function () {
            next_vid_<?php echo $ident;?> = true;
            used_track_<?php  echo $ident?>[used_track_<?php  echo $ident?>.length] = vid_num_<?php  echo $ident?>;
            vid_num_<?php  echo $ident?>++;
            if (used_track_<?php  echo $ident?>.length >= video_urls_<?php  echo $ident?>.length) {
                // reset old list
                used_track_<?php  echo $ident?> = [];
                if (jQuery('#shuffle_<?php  echo $ident?>').val() == 1) {
// get new vido number out of used_tracks
                    vid_num_<?php  echo $ident?> = parseInt(Math.random() * (video_urls_<?php  echo $ident?>.length - 0) + 0);
                    while (in_array(vid_num_<?php  echo $ident?>, used_track_<?php  echo $ident?>)) {
                        vid_num_<?php  echo $ident?> = parseInt(Math.random() * (video_urls_<?php  echo $ident?>.length - 0) + 0);
                    }
                }
            }
            if (jQuery('#shuffle_<?php  echo $ident?>').val() == 1) {
// get new vido number out of used_tracks
                vid_num_<?php  echo $ident?> = parseInt(Math.random() * (video_urls_<?php  echo $ident?>.length - 0) + 0);
                while (in_array(vid_num_<?php  echo $ident?>, used_track_<?php  echo $ident?>)) {
                    vid_num_<?php  echo $ident?> = parseInt(Math.random() * (video_urls_<?php  echo $ident?>.length - 0) + 0);
                }
            }
            jQuery('#global_body_<?php echo $ident;?> .timeBar_<?php  echo $ident?>').css('width', '0%');
            if (vid_num_<?php  echo $ident?> == video_urls_<?php  echo $ident?>.length) {
                vid_num_<?php  echo $ident?> = 0;
            }
            jQuery('#control_btns_<?php  echo $ident?> .btnPlay ').trigger("click");
        });
        jQuery(".lib_<?php  echo $ident?>").click(function () {
            jQuery('#album_div_<?php  echo $ident?>').css('transform', '');
            jQuery('#global_body_<?php  echo $ident?>').css('transform', '');
            jQuery('#global_body_<?php  echo $ident?>').transition({
                perspective: '700px',
                rotateY: '180deg',
            }, 1000);
            setTimeout(function () {
                jQuery('#album_div_<?php  echo $ident?>').css('-ms-transform', 'rotateY(180deg)')
                jQuery('#album_div_<?php  echo $ident?>').css('transform', 'rotateY(180deg)')
                jQuery('#album_div_<?php  echo $ident?>').css('-o-transform', 'rotateY(180deg)')
                document.getElementById('album_div_<?php  echo $ident?>').style.display = 'block'
                document.getElementById('video_div_<?php  echo $ident?>').style.display = 'none'
            }, 300);
            setTimeout(function () {
                jQuery('#album_div_<?php  echo $ident?>').css('-ms-transform', '');
                jQuery('#global_body_<?php  echo $ident?>').css('-ms-transform', '');
                jQuery('#album_div_<?php  echo $ident?>').css('transform', '');
                jQuery('#global_body_<?php  echo $ident?>').css('transform', '');
                jQuery('#album_div_<?php  echo $ident?>').css('-o-transform', '');
                jQuery('#global_body_<?php  echo $ident?>').css('-o-transform', '');
            }, 1100);
        })
        jQuery(".show_vid_<?php  echo $ident?>").click(function () {
            jQuery('#global_body_<?php  echo $ident?>').transition({
                perspective: '700px',
                rotateY: '180deg',
            }, 1000);
            setTimeout(function () {
                jQuery('#video_div_<?php  echo $ident?>').css('-ms-transform', 'rotateY(180deg)')
                jQuery('#video_div_<?php  echo $ident?>').css('transform', 'rotateY(180deg)')
                jQuery('#video_div_<?php  echo $ident?>').css('-o-transform', 'rotateY(180deg)')
                document.getElementById('album_div_<?php  echo $ident?>').style.display = 'none'
                document.getElementById('video_div_<?php  echo $ident?>').style.display = 'block'
            }, 300);
            setTimeout(function () {
                jQuery('#video_div_<?php  echo $ident?>').css('-ms-transform', '');
                jQuery('#global_body_<?php  echo $ident?>').css('-ms-transform', '');
                jQuery('#video_div_<?php  echo $ident?>').css('transform', '');
                jQuery('#global_body_<?php  echo $ident?>').css('transform', '');
                jQuery('#video_div_<?php  echo $ident?>').css('-o-transform', '');
                jQuery('#global_body_<?php  echo $ident?>').css('-o-transform', '');
            }, 1100);
        })
        var canvas_<?php  echo $ident?> = []
        var ctx_<?php  echo $ident?> = []
        var originalPixels_<?php  echo $ident?> = []
        var currentPixels_<?php  echo $ident?> = []
        for (i = 1; i < 30; i++)
            if (document.getElementById('button' + i + '_<?php  echo $ident?>')) {
                canvas_<?php  echo $ident?>[i] = document.createElement("canvas");
                ctx_<?php  echo $ident?>[i] = canvas_<?php  echo $ident?>[i].getContext("2d");
                originalPixels_<?php  echo $ident?>[i] = null;
                currentPixels_<?php  echo $ident?>[i] = null;
            }
        function getPixels_<?php  echo $ident?>() {
            for (i = 1; i < 30; i++)
                if (document.getElementById('button' + i + '_<?php  echo $ident?>')) {
                    img = document.getElementById('button' + i + '_<?php  echo $ident?>');
                    canvas_<?php  echo $ident?>[i].width = img.width;
                    canvas_<?php  echo $ident?>[i].height = img.height;
                    ctx_<?php  echo $ident?>[i].drawImage(img, 0, 0, img.naturalWidth, img.naturalHeight, 0, 0, img.width, img.height);
                    originalPixels_<?php  echo $ident?>[i] = ctx_<?php  echo $ident?>[i].getImageData(0, 0, img.width, img.height);
                    currentPixels_<?php  echo $ident?>[i] = ctx_<?php  echo $ident?>[i].getImageData(0, 0, img.width, img.height);
                    img.onload = null;
                }
        }
        function HexToRGB_<?php  echo $ident?>(Hex) {
            var Long = parseInt(Hex.replace(/^#/, ""), 16);
            return {
                R: (Long >>> 16) & 0xff,
                G: (Long >>> 8) & 0xff,
                B: Long & 0xff
            };
        }
        function changeColor_<?php  echo $ident?>() {
            for (i = 1; i < 30; i++)
                if (document.getElementById('button' + i + '_<?php  echo $ident?>')) {
                    if (!originalPixels_<?php  echo $ident?>[i]) return; // Check if image has loaded
                    var newColor = HexToRGB_<?php  echo $ident?>(document.getElementById("color_<?php echo $ident;?>").value);
                    for (var I = 0, L = originalPixels_<?php  echo $ident?>[i].data.length; I < L; I += 4) {
                        if (currentPixels_<?php  echo $ident?>[i].data[I + 3] > 0) {
                            currentPixels_<?php  echo $ident?>[i].data[I] = originalPixels_<?php  echo $ident?>[i].data[I] / 255 * newColor.R;
                            currentPixels_<?php  echo $ident?>[i].data[I + 1] = originalPixels_<?php  echo $ident?>[i].data[I + 1] / 255 * newColor.G;
                            currentPixels_<?php  echo $ident?>[i].data[I + 2] = originalPixels_<?php  echo $ident?>[i].data[I + 2] / 255 * newColor.B;
                        }
                    }
                    ctx_<?php  echo $ident?>[i].putImageData(currentPixels_<?php  echo $ident?>[i], 0, 0);
                    img = document.getElementById('button' + i + '_<?php  echo $ident?>');
                    img.src = canvas_<?php  echo $ident?>[i].toDataURL("image/png");
                }
        }
        <?php if($theme->spaceOnVid==1) { ?>
        var video_focus;
        jQuery('#global_body_<?php  echo $ident?> ,#videoID_<?php  echo $ident?>').each(function () {
            jQuery(this).on('click', function () {
                setTimeout("video_focus=1", 100)
            })
        })
        jQuery('body').on('click', function () {
            video_focus = 0
        })
        jQuery(window).keypress(function (event) {
            if (event.which == 13) {
                event.preventDefault();
            }
            if (event.keyCode == 32 && video_focus == 1) {
                vidOnSpace_<?php echo $ident;?>()
                return false;
            }
        });
        <?php }?>
        function vidOnSpace_<?php echo $ident;?>() {
            if (video_<?php echo $ident;?>[0].paused) {
                video_<?php echo $ident;?>[0].play();
                paly_<?php echo $ident;?>.css('display', "none");
                pause_<?php echo $ident;?>.css('display', "");
            }
            else {
                video_<?php echo $ident;?>[0].pause();
                paly_<?php echo $ident;?>.css('display', "");
                pause_<?php echo $ident;?>.css('display', "none");
            }
        }
        jQuery('#track_list_<?php  echo $ident?>_0').find('#thumb_0_<?php echo $ident?>').click();
        if(!is_youtube_video_<?php echo $ident ?>())    
        video_<?php echo $ident;?>[0].pause();
        else
            if(typeof player_<?php echo $ident;?> != 'undefined')player_<?php echo $ident;?>.pauseVideo();
        if (paly_<?php echo $ident;?> && pause_<?php echo $ident;?>) {
            paly_<?php echo $ident;?>.css('display', "");
            pause_<?php echo $ident;?>.css('display', "none");
        }
        <?php if($AlbumId!=''){ ?>
        jQuery('#track_list_<?php  echo $ident?>_<?php echo $AlbumId ?>').find('#thumb_<?php echo $identId ?>_<?php echo $ident?>').click();
        <?php } ?>
        jQuery(window).load(function () {
            getPixels_<?php  echo $ident?>();
            changeColor_<?php  echo $ident?>()
        })
        jQuery('.volume_<?php  echo $ident?>')[0].style.width = '<?php echo $theme->defaultVol?>%';
        video_<?php echo $ident;?>[0].volume =<?php echo $theme->defaultVol/100 ;?>;
       </script>
    <?php
    }
    ?>
    </div>
	</div><br/>
    <?php
    global $many_players;
    $many_players++;
    $ident++;
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}


/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-*/
function   front_end_Spider_Video_Player($id) {
    global $wpdb;
    global $ident;
    $find_priority = $wpdb->get_row($wpdb->prepare("SELECT priority FROM " .$wpdb->prefix ."Spider_Video_Player_player WHERE id=%d", $id));
    $priority = $find_priority->priority;
    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM " .$wpdb->prefix ."Spider_Video_Player_player WHERE id=%d", $id));
    $params = $wpdb->get_row($wpdb->prepare("SELECT * FROM " .$wpdb->prefix ."Spider_Video_Player_theme WHERE id=%d", $row->theme));
    if ($priority == 0) {
        $scripttt = '    <script type="text/javascript"> 
var html5_' .$ident .' = document.getElementById("spidervideoplayerhtml5_' .$ident .'");
var flash_' .$ident .' = document.getElementById("spidervideoplayerflash_' .$ident .'");
if(!FlashDetect.installed){
flash_' .$ident .'.parentNode.removeChild(flash_' .$ident .');
spidervideoplayerhtml5_' .$ident .'.style.display=\'\';
}
else{
html5_' .$ident .'.parentNode.removeChild(html5_' .$ident .');
spidervideoplayerflash_' .$ident .'.style.display=\'\';
}
</script>';
    } else {
        $scripttt = '';
    }
    if ($priority == 0) {
        global $post;
        $id_for_posts = $post->ID;
        $all_player_ids = $wpdb->get_col("SELECT id FROM " .$wpdb->prefix ."Spider_Video_Player_player");
        $b = false;
        foreach ($all_player_ids as $all_player_id) {
            if ($all_player_id == $id)
                $b = true;
        }
        if (!$b)
            return "";
        $Spider_Video_Player_front_end = "";
        $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM " .$wpdb->prefix ."Spider_Video_Player_player WHERE id=%d", $id));
        $params = $wpdb->get_row($wpdb->prepare("SELECT * FROM " .$wpdb->prefix ."Spider_Video_Player_theme WHERE id=%d", $row->theme));
        $theme = $row->theme;
        $playlist = $row->id;
        if ($params->appWidth != "")
            $width = $params->appWidth;
        else
            $width = '700';
        if ($params->appHeight != "")
            $height = $params->appHeight;
        else
            $height = '400';
        $show_trackid = $params->show_trackid;
        global $many_players;
        ?>
        <?php
        $Spider_Video_Player_front_end = "<script type=\"text/javascript\" src=\"" .plugins_url("swfobject.js", __FILE__) ."\"></script>
		<div id=\"spidervideoplayerflash_" .$ident ."\" style=\"display:none\">		
		  <div id=\"" .$id_for_posts ."_" .$many_players ."_flashcontent\"  style=\"width: " .$width ."px; height:" .$height ."px\"></div>
			<script type=\"text/javascript\">
function flashShare(type,b,c)	
{
u=location.href;
	u=u.replace('/?','/index.php?');
	if(u.search('&AlbumId')!=-1)
	{
		var u_part2='';
		u_part2=u.substring(u.search('&TrackId')+2, 1000)
		if(u_part2.search('&')!=-1)
		{
			u_part2=u_part2.substring(u_part2.search('&'),1000);
		}
		u=u.replace(u.substring(u.search('&AlbumId'), 1000),'')+u_part2;		
	}
	if(!location.search)
			u=u+'?';
		else
			u=u+'&';
	t=document.title;
	switch (type)
	{
	case 'fb':	
		window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u+'AlbumId='+b+'&TrackId='+c)+'&t='+encodeURIComponent(t), \"Facebook\",\"menubar=1,resizable=1,width=350,height=250\");
		break;
	case 'g':
		window.open('http://plus.google.com/share?url='+encodeURIComponent(u+'AlbumId='+b+'&TrackId='+c)+'&t='+encodeURIComponent(t), \"Google\",\"menubar=1,resizable=1,width=350,height=250\");
		break;
	case 'tw':
		window.open('http://twitter.com/home/?status='+encodeURIComponent(u+'AlbumId='+b+'&TrackId='+c), \"Twitter\",\"menubar=1,resizable=1,width=350,height=250\");
		break;
	}
}		
     var so = new SWFObject(\"" .plugins_url("videoSpider_Video_Player.swf", __FILE__) ."?wdrand=" .mt_rand() ."\", \"Spider_Video_Player\", \"100%\", \"100%\", \"8\", \"#000000\");
	 so.addParam(\"FlashVars\", \"settingsUrl=" .str_replace("&", "@", str_replace("&amp;", "@", admin_url('admin-ajax.php?action=spiderVeideoPlayersettingsxml') ."&playlist=" .$playlist ."&theme=" .$theme ."&s_v_player_id=" .$id ."&single=0")) ."&playlistUrl=" .str_replace("&", "@", str_replace("&amp;", "@", admin_url('admin-ajax.php?action=spiderVeideoPlayerplaylistxml') ."&playlist=" .$playlist ."&single=0&show_trackid=" .$show_trackid)) ."&defaultAlbumId=" .(isset($_GET['AlbumId']) ? htmlspecialchars($_GET['AlbumId']) : "") ."&defaultTrackId=" .(isset($_GET['TrackId']) ? htmlspecialchars($_GET['TrackId']) : "") ."\");
		   so.addParam(\"quality\", \"high\");
		   so.addParam(\"menu\", \"false\");
		   so.addParam(\"wmode\", \"transparent\");
		   so.addParam(\"loop\", \"false\");
		   so.addParam(\"allowfullscreen\", \"true\");
		   so.write(\"" .$id_for_posts ."_" .$many_players ."_flashcontent\");
			</script>
			</div>
			";
        $many_players++;
        ?>
        <?php
        return $Spider_Video_Player_front_end .Spider_Video_Player_front_end($id) .$scripttt;
    } else {
        $identt = $ident;
        return Spider_Video_Player_front_end($id) .'<script>document.getElementById("spidervideoplayerhtml5_' .$identt .'").style.display=\'\'</script>';
    }
}

/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-*/
function Spider_Video_Player_front_end($id) {
    ob_start();
    global $ident;
    global $wpdb;
    $find_priority = $wpdb->get_row($wpdb->prepare("SELECT priority FROM " .$wpdb->prefix ."Spider_Video_Player_player WHERE id=%d", $id));
    $priority = $find_priority->priority;
    ?>
    <div id="spidervideoplayerhtml5_<?php echo $ident ?>" style="display:none">
    <?php
    if ($priority == 1 ) {
        $theme_id = $wpdb->get_row($wpdb->prepare("SELECT theme FROM " .$wpdb->prefix ."Spider_Video_Player_player WHERE id=%d", $id));
        $playlist = $wpdb->get_row($wpdb->prepare("SELECT playlist FROM " .$wpdb->prefix ."Spider_Video_Player_player WHERE id=%d", $id));
        $playlist_array = explode(',', $playlist->playlist);
        global $many_players;
        if (isset($_POST['playlist_id'])) {
            $playlistID = esc_html(stripslashes($_POST['playlist_id']));
        } else $playlistID = 1;
        $key = $playlistID - 1;
        if (isset($playlist->playlist)) {
            $playlistID = count($playlist_array)-1;
        } else $playlistID = 1;
        $key = $playlistID - 1;
    
        $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM " .$wpdb->prefix ."Spider_Video_Player_playlist WHERE id=%d", $playlist_array[0]));
        $theme = $wpdb->get_row($wpdb->prepare("SELECT * FROM " .$wpdb->prefix ."Spider_Video_Player_theme WHERE id=%d", $theme_id->theme));
        $row1 = $wpdb->get_row($wpdb->prepare("SELECT * FROM " .$wpdb->prefix ."Spider_Video_Player_player WHERE id=%d", $id));
                
        $themeid = $row1->theme;
        if (isset($row->videos))
            $video_ids = substr($row->videos, 0, -1);
        else
            $video_ids = 0;
        $videos = $wpdb->get_results("SELECT url,urlHtml5,type,title,thumb FROM " .$wpdb->prefix ."Spider_Video_Player_video WHERE id IN ($video_ids)");
        $video_urls = '';
        for ($i = 0; $i < count($videos); $i++) {
            if ($videos[$i]->urlHtml5 != "") {
                $video_urls .= "'" .$videos[$i]->urlHtml5 ."'" .',';
            } else $video_urls .= "'" .$videos[$i]->url ."'" .',';
        }
        $video_urls = substr($video_urls, 0, -1);
        $playlists = $wpdb->get_results("SELECT * FROM " .$wpdb->prefix ."Spider_Video_Player_playlist");
        $libRows = $theme->libRows;
        $libCols = $theme->libCols;
        $cellWidth = 100 / $libCols .'%';
        $cellHeight = 100 / $libRows .'%';
        $play = $wpdb->get_results("SELECT * FROM " .$wpdb->prefix ."Spider_Video_Player_playlist");
        // load the row from the db table
        $k = $libRows * $libCols;
        if (isset($_POST['play'])) {
            $p = esc_html(stripslashes($_POST['play']));
        } else $p = 0;
        $display = 'style="width:100%;height:100% !important;border-collapse: collapse;"';
        $table_count = 1;
        $itemBgHoverColor = '#' .$theme->itemBgHoverColor;
        $vds = $wpdb->get_results("SELECT * FROM " .$wpdb->prefix ."Spider_Video_Player_video");
        $ctrlsStack = $theme->ctrlsStack;
        if ($theme->ctrlsPos == 2) {
            $ctrl_top = $theme->appHeight - 35 .'px';
            $share_top = '-140px';
        } else {
            $ctrl_top = '5px';
            $share_top = '-' .$theme->appHeight + 20 .'px';
        }
        if (isset($_POST['AlbumId']))
            $AlbumId = esc_html(stripslashes($_POST['AlbumId']));
        else
            $AlbumId = '';
        if (isset($_POST['TrackId']))
            $TrackId = esc_html(stripslashes($_POST['TrackId']));
        else
            $TrackId = '';
        ?>
        <style>
            a#dorado_mark_<?php echo $ident;?>:hover {
                background: none !important;
            }
            #album_table_<?php  echo $ident?> td,
            #album_table_<?php  echo $ident?> tr,
            #album_table_<?php  echo $ident?> img {
                line-height: 1em !important;
            }
            #share_buttons_<?php echo $ident;?> img {
                display: inline !important;
            }
            #album_div_<?php  echo $ident?> table {
                margin: 0px !important;
            }
            #album_table_<?php  echo $ident?> {
                margin: -1 0 1.625em !important;
            }
            table {
                margin: 0em;
            }
            #global_body_<?php echo $ident;?> .control_<?php  echo $ident?> {
                position: absolute;
                background-color: rgba(<?php echo HEXDEC(SUBSTR($theme->framesBgColor, 0, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->framesBgColor, 2, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->framesBgColor, 4, 2)) ?>, <?php echo $theme->framesBgAlpha/100; ?>);
                top: <?php echo $ctrl_top?> !important;
                width: <?php echo $theme->appWidth; ?>px;
                height: 40px;
                z-index: 7;
                margin-top: -5px;
            }
            #global_body_<?php echo $ident;?> .control_<?php  echo $ident?> td {
                padding: 0px !important;
                margin: 0px !important;
            }
            #global_body_<?php echo $ident;?> .control_<?php  echo $ident?> td img {
                padding: 0px !important;
                margin: 0px !important;
            }
            #global_body_<?php echo $ident;?> .progressBar_<?php  echo $ident?> {
                position: relative;
                width: 100%;
                height: 6px;
                z-index: 5;
                cursor: pointer;
                border-top: 1px solid rgba(<?php echo HEXDEC(SUBSTR($theme->slideColor, 0, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->slideColor, 2, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->slideColor, 4, 2)) ?>, <?php echo $theme->framesBgAlpha/100; ?>);
                border-bottom: 1px solid rgba(<?php echo HEXDEC(SUBSTR($theme->slideColor, 0, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->slideColor, 2, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->slideColor, 4, 2)) ?>, <?php echo $theme->framesBgAlpha/100; ?>);
            }
            #global_body_<?php echo $ident;?> .timeBar_<?php  echo $ident?> {
                position: absolute;
                top: 0;
                left: 0;
                width: 0;
                height: 100%;
                background-color: <?php echo '#'.$theme->slideColor; ?>;
                z-index: 5;
            }
            #global_body_<?php echo $ident;?> .bufferBar_<?php  echo $ident?> {
                position: absolute;
                top: 0;
                left: 0;
                width: 0;
                height: 100%;
                background-color: <?php echo '#'.$theme->slideColor; ?>;
                opacity: 0.3;
            }
            #global_body_<?php echo $ident;?> .volumeBar_<?php echo $ident;?> {
                position: relative;
                overflow: hidden;
                width: 0px;
                height: 4px;
                background-color: rgba(<?php echo HEXDEC(SUBSTR($theme->framesBgColor, 0, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->framesBgColor, 2, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->framesBgColor, 4, 2)) ?>, <?php echo $theme->framesBgAlpha/100; ?>);
                border: 1px solid rgba(<?php echo HEXDEC(SUBSTR($theme->slideColor, 0, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->slideColor, 2, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->slideColor, 4, 2)) ?>, <?php echo $theme->framesBgAlpha/100; ?>);
            }
            #global_body_<?php echo $ident;?> img {
                background: none !important;
            }
            #global_body_<?php echo $ident;?> .volume_<?php echo $ident;?> {
                position: absolute;
                top: 0;
                left: 0;
                width: 0;
                height: 100%;
                background-color: <?php echo '#'.$theme->slideColor; ?>;
            }
            #play_list_<?php  echo $ident?> {
                height: <?php echo $theme->appHeight; ?>px;
                width: 0px;
            <?php
	if ($theme->playlistPos==1)
	echo 'position:absolute;float:left !important;';
	else
	echo 'position:absolute;float:right !important;right:0;';
	?>;
                background-color: rgba(<?php echo HEXDEC(SUBSTR($theme->framesBgColor, 0, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->framesBgColor, 2, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->framesBgColor, 4, 2)) ?>, <?php echo $theme->framesBgAlpha/100; ?>) !important;
                color: white;
                z-index: 100;
                padding: 0px !important;
                margin: 0px !important;
            }
            #play_list_<?php  echo $ident?> img,
            #play_list_<?php  echo $ident?> td {
                background-color: transparent !important;
                color: white;
                padding: 0px !important;
                margin: 0px !important;
            }
            .control_btns_<?php  echo $ident?> {
                opacity: <?php echo $theme->ctrlsMainAlpha/100; ?>;
            }
            #control_btns_<?php  echo $ident?>,
            #volumeTD_<?php echo $ident;?> {
                margin: 0px;
            }
            img {
                box-shadow: none !important;
            }
            #td_ik_<?php echo $ident;?> {
                border: 0px;
            }
            
        </style>
		<?php
		$player_id = $wpdb->get_var($wpdb->prepare("SELECT theme FROM " .$wpdb->prefix ."Spider_Video_Player_player WHERE id=%d", $id));
		?>
        <div id="global_body_<?php echo $ident; ?>"
             style="width:<?php echo $theme->appWidth; ?>px;height:<?php echo $theme->appHeight; ?>px; position:relative;">
        <?php
        $row1 = $wpdb->get_row($wpdb->prepare("SELECT * FROM " .$wpdb->prefix ."Spider_Video_Player_theme WHERE id=%d", $player_id));
        $start_lib = $row1->startWithLib;
        ?>
        <div id="video_div_<?php echo $ident; ?>"
             style="display:<?php if ($start_lib == 1) echo 'none'; else echo 'block' ?>;width:<?php echo $theme->appWidth; ?>px;height:<?php echo $theme->appHeight; ?>px;background-color:<?php echo "#" .$theme->vidBgColor; ?>">
            <div id="play_list_<?php echo $ident ?>">
                <input type='hidden' value='0' id="track_list_<?php echo $ident; ?>"/>
                <div style="height:90%" id="play_list1_<?php echo $ident; ?>">
                    <div id="arrow_up_<?php echo $ident ?>"
                         onmousedown="scrolltp2=setInterval('scrollTop2_<?php echo $ident; ?>()', 30)"
                         onmouseup="clearInterval(scrolltp2)" onclick="scrollTop2_<?php echo $ident; ?>()"
                         style="overflow:hidden; text-align:center;width:<?php echo $theme->playlistWidth; ?>px; height:20px">
                        <img src="<?php echo plugins_url('', __FILE__) ?>/images/top.png"
                             style="cursor:pointer;  border:none;" id="button20_<?php echo $ident ?>"/>
                    </div>
                    <div style="height:<?php echo $theme->appHeight - 40; ?>px;overflow:hidden;"
                         id="video_list_<?php echo $ident; ?>">
                        <?php
                        //echo '<p onclick="document.getElementById("videoID").src="'.$videos[$i]["url"].'" ">'.$videos[$i]['title'].'</p>';
                        for ($i = 0; $i < count($playlist_array) - 1; $i++) {
                            $playy = $wpdb->get_row($wpdb->prepare("SELECT * FROM " .$wpdb->prefix ."Spider_Video_Player_playlist WHERE id=%d", $playlist_array[$i]));
                            $v_ids = explode(',', $playy->videos);
                            $vi_ids = substr($playy->videos, 0, -1);
                            if ($i != 0)
                                echo '<table id="track_list_' .$ident .'_' .$i .'" width="100%" style="display:none;height:100%;border-spacing:0px;border:none;border-collapse: inherit;padding:0px !important;background: transparent !important;" >';
                            else
                                echo '<table id="track_list_' .$ident .'_' .$i .'" width="100%" style="display:block;height:100%; border-spacing:0px;border:none;border-collapse: inherit;padding:0px !important;background: transparent !important;" > ';
                            echo '<tr style="background:transparent ">
							<td id="td_ik_' .$ident .'" style="text-align:left;border:0px solid grey;width:100%;vertical-align:top;">
							<div id="scroll_div2_' .$i .'_' .$ident .'" class="playlist_values_' .$ident .'" style="position:relative">';
                            $jj = 0;
                            $vtttt = ''; 
                            for ($j = 0; $j < count($v_ids) - 1; $j++) {
                                $vdss = $wpdb->get_row($wpdb->prepare("SELECT * FROM " .$wpdb->prefix ."Spider_Video_Player_video WHERE id=%d", $v_ids[$j]));
                              
                                if ($vdss->type == "http" || $vdss->type == "youtube" || $vdss->type == "vimeo") {
                                    if (($vdss->urlHtml5 == "" || !strpos($vdss->url, 'embed')) && $vdss->type != "vimeo") {
                                        if ($vdss->type == "youtube") {
                                            $html5Url = "https://www.youtube.com/embed/" . substr($vdss->url, strpos($vdss->url, '?v=') + 3, 11) . "?enablejsapi=1&html5=1&controls=1&modestbranding=1&rel=0";
                                        } else {
                                            $html5Url = $vdss->url;
                                        }
                                    } else
                                        $html5Url = $vdss->urlHtml5;
                                    $vidsTHUMB = $vdss->thumb;
                                    $vtttt = (!$j || ($j && !$vtttt) ? $vidsTHUMB : $vtttt);
                                    if ($vdss->urlHDHtml5 != "") {
                                        $html5UrlHD = $vdss->urlHDHtml5;
                                    } else $html5UrlHD = $vdss->urlHD;
                                    echo '<div id="thumb_' .$jj .'_' .$ident .'"  onclick="jQuery(\'#HD_on_' .$ident .'\').val(0);';
                                           if($vdss->type=="youtube")
                                               echo 'youtube_control_'.$ident.'(\''.$html5Url.'\'); ';
                                           elseif($vdss->type=="vimeo")
                                                echo 'vimeo_control_'.$ident.'(\''.$html5Url.'\'); ';
                                            else 
                                                echo ' document.getElementById(\'videoID_' .$ident .'\').src=\'' .$html5Url.'\';video_control_'.$ident.'(\''.$html5Url.'\');play_' .$ident .'();';
                                               echo    'document.getElementById(\'videoID_' .$ident .'\').poster=\'' .$vidsTHUMB .'\';vid_select_' .$ident .'(this);vid_num=' .$jj .';jQuery(\'#current_track_' .$ident .'\').val(' .$jj .');" class="vid_thumb_' .$ident .'" style="color:#' .$theme->textColor .';cursor:pointer;width:' .$theme->playlistWidth .'px;text-align:center; "  >';
                                    if ($vdss->thumb)
                                        echo '<img   src="' .$vidsTHUMB .'" width="90px" style="display:none;  border:none;"  />';
                                    echo '<p style="font-size:' .$theme->playlistTextSize .'px !important;line-height:30px !important;cursor:pointer;margin: 0px !important;padding:0px !important" >' .($theme->show_trackid ? ($jj + 1) .'-' : '') .$vdss->title .'</p></div>';
                                    echo '<input type="hidden" id="urlHD_' .$jj .'_' .$ident .'" value="' .$html5UrlHD .'" />';
                                    echo '<input type="hidden" id="vid_type_' .$jj .'_' .$ident .'" value="' .$vdss->type .'" />';
                                    $jj = $jj + 1;
                                }
                            } 
                            echo '</div></td>
					</tr></table>';
                        }
                        ?>
                    </div>
                    <div onmousedown="scrollBot2=setInterval('scrollBottom2_<?php echo $ident; ?>()', 30)"
                         onmouseup="clearInterval(scrollBot2)" onclick="scrollBottom2_<?php echo $ident; ?>()"
                         style="position:absolute;overflow:hidden; text-align:center;width:<?php echo $theme->playlistWidth; ?>px; height:20px"
                         id="divulushka_<?php echo $ident; ?>"><img
                            src="<?php echo plugins_url('', __FILE__) ?>/images/bot.png"
                            style="cursor:pointer;  border:none;" id="button21_<?php echo $ident ?>"/></div>
                </div>
            </div>
           
            <video ontimeupdate="timeUpdate_<?php echo $ident ?>()"
                   ondurationchange="durationChange_<?php echo $ident ?>();" id="videoID_<?php echo $ident ?>"
                   src="" poster="<?php echo $vtttt ?>" 
                   style="width:100%; height:100%;margin:0px;position: absolute;">
                <p>Your browser does not support the video tag.</p>
            </video>
            <img src="<?php echo plugins_url('', __FILE__) ?>/images/wd_logo.png" style="bottom: 30px;position: absolute;width: 140px;height: 73px; border: 0px !important;left: 0px;"/> 
            <div class="control_<?php echo $ident; ?>" id="control_<?php echo $ident; ?>" style="overflow:hidden;">
                <?php if ($theme->ctrlsPos == 2 ) { ?>
                    <div class="progressBar_<?php echo $ident; ?>">
                        <div class="timeBar_<?php echo $ident; ?>"></div>
                        <div class="bufferBar_<?php echo $ident; ?>"></div>
                    </div>
                <?php
                }
                $ctrls = explode(',', $ctrlsStack);
                
                $y = 1;
                echo '<table id="control_btns_' .$ident .'" style="width: 100%; border:none;border-collapse: inherit; background: transparent;padding: 0px !important;"><tr style="background: transparent;">';
                for ($i = 0; $i < count($ctrls); $i++) {
                    $ctrl = explode(':', $ctrls[$i]);
                   
                    if ($ctrl[1] == 1) {
                        echo '<td style="border:none;background: transparent;">';
                        if ($ctrl[0] == 'playPause') {
                            if ($theme->appWidth > 400) {
                                echo '<img id="button' .$y .'_' .$ident .'"  class="btnPlay" width="16" style="position: relative;vertical-align: middle;cursor:pointer;  border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .'; height:19px"   src="' .plugins_url('', __FILE__) .'/images/play.png" />';
                                echo '<img id="button' .($y + 1) .'_' .$ident .'" width="16"  class="btnPause" style="position: relative;vertical-align: middle;display:none;cursor:pointer;  border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';height:18px"  src="' .plugins_url('', __FILE__) .'/images/pause.png" />';
                            } else {
                                echo '<img id="button' .$y .'_' .$ident .'"  class="btnPlay" style="vertical-align: middle;cursor:pointer;max-width:7px;  border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';"   src="' .plugins_url('', __FILE__) .'/images/play.png" />';
                                echo '<img id="button' .($y + 1) .'_' .$ident .'" width="16"  class="btnPause" style="vertical-align: middle;height: 18px !important;display:none;cursor:pointer;max-width:7px;  border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';"  src="' .plugins_url('', __FILE__) .'/images/pause.png" />';
                            }
                            $y = $y + 2;
                        } else
                            if ($ctrl[0] == '+') {
                                echo '<span id="space" style="position: relative;vertical-align: middle;padding-left:' .(($theme->appWidth * 20) / 100) .'px"></span>';
                            } else
                                if ($ctrl[0] == 'time' ) {
                                    echo '						
						  <span style="color:#' .$theme->ctrlsMainColor .';opacity:' .$theme->ctrlsMainAlpha / 100 .'; position:relative; vertical-align: middle; " id="time_' .$ident .'">00:00</span>
						  <span style="color:#' .$theme->ctrlsMainColor .'; opacity:' .$theme->ctrlsMainAlpha / 100 .';position:relative; vertical-align: middle;">/</span> 
						  <span style="color:#' .$theme->ctrlsMainColor .';opacity:' .$theme->ctrlsMainAlpha / 100 .';position:relative; vertical-align: middle;" id="duration_' .$ident .'">00:00</span>';
                                } else
                                    if ($ctrl[0] == 'vol' ) {
                                        if ($theme->appWidth > 400) {
                                            $img_button = '<img  style="position: relative;cursor:pointer; border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';vertical-align: middle;"  id="button' .$y .'_' .$ident .'"    src="' .plugins_url('', __FILE__) .'/images/vol.png"  />';
                                        } else {
                                            $img_button = '<img  style="vertical-align: middle;cursor:pointer;max-width:7px; border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';"  id="button' .$y .'_' .$ident .'"    src="' .plugins_url('', __FILE__) .'/images/vol.png"  />';
                                        }
                                        echo '<table  id="volumeTD_' .$ident .'" style="border:none;border-collapse: inherit; min-width: 0;background: transparent;padding: 0px !important;" >
						<tr style="background: transparent;">
							<td id="voulume_img_' .$ident .'" style="top:5px;border:none;min-width:13px;  background: transparent; width:20px;" >' .$img_button .'
							</td>
							<td id="volumeTD2_' .$ident .'" style="width:0px; border:none; position:relative;background: transparent; ">
									<span id="volumebar_player_' .$ident .'" class="volumeBar_' .$ident .'" style="vertical-align: middle;">
								    <span class="volume_' .$ident .'" style="vertical-align: middle;"></span>
									</span>
							 </td>
						</tr>
						</table> ';
                                        $y = $y + 1;
                                    } else
                                        if ($ctrl[0] == 'shuffle') {
                                            if ($theme->appWidth > 400) {
                                                echo '<img  id="button' .$y .'_' .$ident .'" class="shuffle_' .$ident .'" style="position: relative;vertical-align: middle;cursor:pointer; border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';"   src="' .plugins_url('', __FILE__) .'/images/shuffle.png" />';
                                                echo '<img  id="button' .($y + 1) .'_' .$ident .'"  class="shuffle_' .$ident .'" style="position: relative;vertical-align: middle;display:none;cursor:pointer; border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';"  src="' .plugins_url('', __FILE__) .'/images/shuffleoff.png" />';
                                            } else {
                                                echo '<img  id="button' .$y .'_' .$ident .'" class="shuffle_' .$ident .'" style="vertical-align: middle;cursor:pointer;max-width:7px;  border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';"   src="' .plugins_url('', __FILE__) .'/images/shuffle.png" />';
                                                echo '<img  id="button' .($y + 1) .'_' .$ident .'"  class="shuffle_' .$ident .'" style="vertical-align: middle;display:none;cursor:pointer;max-width:7px; border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';"  src="' .plugins_url('', __FILE__) .'/images/shuffleoff.png" />';
                                            }
                                            $y = $y + 2;
                                        } else
                                            if ($ctrl[0] == 'repeat') {
                                                if ($theme->appWidth > 400) {
                                                    echo '
					<img  id="button' .$y .'_' .$ident .'" class="repeat_' .$ident .'" style="position: relative;vertical-align: middle;cursor:pointer; border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';"   src="' .plugins_url('', __FILE__) .'/images/repeat.png"/>
					<img  id="button' .($y + 1) .'_' .$ident .'"  class="repeat_' .$ident .'" style="position: relative;vertical-align: middle;display:none;cursor:pointer; border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';"   src="' .plugins_url('', __FILE__) .'/images/repeatOff.png"/>
					<img  id="button' .($y + 2) .'_' .$ident .'"  class="repeat_' .$ident .'" style="osition: relative;vertical-align: middle;display:none;cursor:pointer; border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';"  src="' .plugins_url('', __FILE__) .'/images/repeatOne.png"/>
					';
                                                } else {
                                                    echo '
				<img  id="button' .$y .'_' .$ident .'" class="repeat_' .$ident .'" style="vertical-align: middle;cursor:pointer;max-width:7px; border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';"   src="' .plugins_url('', __FILE__) .'/images/repeat.png"/>
				<img  id="button' .($y + 1) .'_' .$ident .'"  class="repeat_' .$ident .'" style="vertical-align: middle;display:none;cursor:pointer;max-width:7px; border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';"   src="' .plugins_url('', __FILE__) .'/images/repeatOff.png"/>
				<img  id="button' .($y + 2) .'_' .$ident .'"  class="repeat_' .$ident .'" style="vertical-align: middle;display:none;cursor:pointer;max-width:7px; border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';"  src="' .plugins_url('', __FILE__) .'/images/repeatOne.png"/>
				';
							}
							$y = $y + 3;
							} else {
								if ($theme->appWidth > 400) {
									echo '<img  style="position: relative;vertical-align: middle;cursor:pointer; border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';" id="button' .$y .'_' .$ident .'" class="' .$ctrl[0] .'_' .$ident .'"  src="' .plugins_url('', __FILE__) .'/images/' .$ctrl[0] .'.png" />';
								} else {
									echo '<img  style="vertical-align: middle;cursor:pointer;max-width:7px; border:none;opacity:' .$theme->ctrlsMainAlpha / 100 .';" id="button' .$y .'_' .$ident .'" class="' .$ctrl[0] .'_' .$ident .'"  src="' .plugins_url('', __FILE__) .'/images/' .$ctrl[0] .'.png" />';
								}
								$y = $y + 1;
								#echo "<script>jQuery(document).ready(show_hide_playlist);</script>";
							}
                        echo '</td>';
                    }
                }
                echo '</tr></table>';
                if ($theme->ctrlsPos == 1) {
                    ?>
                    <div class="progressBar_<?php echo $ident; ?>">
                        <div class="timeBar_<?php echo $ident; ?>"></div>
                        <div class="bufferBar_<?php echo $ident; ?>"></div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
        <div id="album_div_<?php echo $ident; ?>"
             style="display:<?php if ($start_lib == 0) echo 'none' ?>;background-color:<?php echo "#" .$theme->appBgColor; ?>;overflow:hidden;position:relative;height:<?php echo $theme->appHeight; ?>px;">
            <table width="100%" height="100%"
                   style="padding:0px !important;border:none;border-collapse: inherit;width: 100% !important;"
                   id="album_table_<?php echo $ident ?>">
                <tr id="tracklist_up_<?php echo $ident ?>" style="display:none; background: transparent;">
                    <td height="12px" colspan="2"
                        style="text-align:right; border:none;background: transparent;padding: 0px !important;">
                        <div
                            onmouseover="this.style.background='rgba(<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 0, 2)) ?>,<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 2, 2)) ?>,<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 4, 2)) ?>,0.4)'"
                            onmouseout="this.style.background='none'" id="scroll"
                            style="overflow:hidden;width:50%;height:100%;text-align:center;float:right;cursor:pointer;"
                            onmousedown="scrolltp=setInterval('scrollTop_<?php echo $ident; ?>()', 30)"
                            onmouseup="clearInterval(scrolltp)" onclick="scrollTop_<?php echo $ident; ?>()">
                            <img src="<?php echo plugins_url('', __FILE__) ?>/images/top.png"
                                 style="cursor:pointer; margin: 0px !important; padding: 0px !important; border:none;background: transparent;"
                                 id="button25_<?php echo $ident; ?>"/>
                            <div>
                    </td>
                </tr>
                <tr style="background: transparent;">
                    <td style="vertical-align:middle; border:none;background: transparent;padding: 0px !important;width: 4% !important; ">
                        <img src="<?php echo plugins_url('', __FILE__) ?>/images/prev.png"
                             style="cursor:pointer; margin: 0px !important; padding: 0px !important; background: transparent;border:none;min-width: 16px;"
                             id="button28_<?php echo $ident ?>" onclick="prevPage_<?php echo $ident ?>();"/>
                    </td>
                    <td style="border:none;background: transparent;padding: 2px !important;width:92% !important;"
                        id="lib_td_<?php echo $ident; ?>">
                        <?php
                        for ($l = 0; $l < $table_count; $l++) {
                            echo '<table class="lib_tbl_' .$ident .'" id="lib_table_' .$l .'_' .$ident .'" ' .$display .'> ';
                            for ($i = 0; $i < $libRows; $i++) {
                                echo '<tr style="background: transparent;">';
                                for ($j = 0; $j < $libCols; $j++) {
                                    if ($p < count($playlist_array) - 1) {
                                        $playyy = $wpdb->get_row("SELECT * FROM " .$wpdb->prefix ."Spider_Video_Player_playlist WHERE id=" .$playlist_array[$p]);
                                        $playTHUMB = $playyy->thumb;
                                        if ($playTHUMB != "") {
                                            $image_nk = '<img src="' .$playTHUMB .'" style="border:none; width:50% !important;background: transparent;"/>';
                                        } else $image_nk = "";
                                        echo '<td  class="playlist_td_' .$ident .'" id="playlist_' .$p .'_' .$ident .'"  onclick="openPlaylist_' .$ident .'(' .$p .',' .$l .')" onmouseover="this.style.color=\'#' .$theme->textHoverColor .'\';this.style.background=\'rgba(' .HEXDEC(SUBSTR($theme->itemBgHoverColor, 0, 2)) .',' .HEXDEC(SUBSTR($theme->itemBgHoverColor, 2, 2)) .',' .HEXDEC(SUBSTR($theme->itemBgHoverColor, 4, 2)) .',0.4)\'" onmouseout="this.style.color=\'#' .$theme->textColor .'\';this.style.background=\' none\'" onclick="" style="color:#' .$theme->textColor .';border:1px solid white;background: transparent;vertical-align:center; text-align:center;width:' .$cellWidth .';height:' .$cellHeight .';cursor:pointer;padding:5px !important; ">' .$image_nk .'
		<p style="font-size:' .$theme->libListTextSize .'px !important;margin-bottom: 0px !important;padding:0px !important;">' .$playyy->title .'</p>
		</td>';
                                        $p = $p + 1;
                                    } else {
                                        echo '<td style="border:1px solid white;vertical-align:top;background: transparent; align:center;width:' .$cellWidth .';height:' .$cellHeight .'">
			</td>';
                                    }
                                }
                                echo '</tr>';
                            }
                            if ($p < count($playlist_array) - 1) {
                                $table_count = $table_count + 1;
                                $display = 'style="display:none;width:100%;height:100%;border-collapse: collapse;"';
                            }
                            echo '</table>';
                        }
                        for ($i = 0; $i < $p; $i++) {
                            $play1 = $wpdb->get_row("SELECT * FROM " .$wpdb->prefix ."Spider_Video_Player_playlist WHERE id=" .$playlist_array[$i]);
                            $v_ids = explode(',', $play1->videos);
                            $vi_ids = substr($play1->videos, 0, -1);
                            $playTHUMB = $play1->thumb;
                            if ($playTHUMB != "")
                                $image_nkar = '<img src="' .$playTHUMB .'"  style="border:none;width:70% !important" /><br /><br />';
                            else
                                $image_nkar = "";
                            echo '<table playlist_id="' .$i .'" id="playlist_table_' .$i .'_' .$ident .'"  style="border:none;border-collapse: inherit;display:none;height:100%;width:100% !important; padding:0px !important;" >
</tr>
<tr style="background: transparent;">
<td style="text-align:center;vertical-align:top;background: transparent;border:none;background: transparent;padding:5px !important;">';
                            echo $image_nkar;
                            echo '<p style="color:#' .$theme->textColor .'; font-size:' .$theme->libDetailsTextSize .'px !important;margin-bottom: 0px !important;">' .$play1->title .'</p>';
                            echo '</td>
<td style="width:50% !important;border:none; background: transparent;padding: 5px !important;">
<div style="width:100%;text-align:left;border:1px solid white;height:' .($theme->appHeight - 55) .'px;vertical-align:top;position:relative;overflow:hidden; min-width: 130px;">
<div id="scroll_div_' .$i .'_' .$ident .'" style="position:relative;">';
                            $jj = 0;
                            
                            for ($j = 0; $j < count($v_ids) - 1; $j++) {
                                $vds1 = $wpdb->get_results("SELECT * FROM " .$wpdb->prefix ."Spider_Video_Player_video WHERE id=" .$v_ids[$j]);
                                if ($vds1[0]->type == 'http') {
                                    echo '<p class="vid_title_' .$ident .'" ondblclick="jQuery(\'.show_vid_' .$ident .'\').click()" onmouseover="this.style.color=\'#' .$theme->textHoverColor .'\';this.style.background=\'rgba(' .HEXDEC(SUBSTR($theme->itemBgHoverColor, 0, 2)) .',' .HEXDEC(SUBSTR($theme->itemBgHoverColor, 2, 2)) .',' .HEXDEC(SUBSTR($theme->itemBgHoverColor, 4, 2)) .',0.4)\'" onmouseout="this.style.color=\'#' .$theme->textColor .'\';this.style.background=\' none\'" style="padding: 0px !important;color:#' .$theme->textColor .' !important;font-size:' .$theme->libDetailsTextSize .'px !important;line-height:30px !important;cursor:pointer; margin: 0px !important;" onclick="jQuery(\'#HD_on_' .$ident .'\').val(0);jQuery(\'#track_list_' .$ident .'_' .$i .'\').find(\'.vid_thumb_' .$ident .'\')[' .$jj .'].click();playBTN_' .$ident .'();current_playlist_videos_' .$ident .'();vid_num=' .$jj .';jQuery(\'#current_track_' .$ident .'\').val(' .$jj .');vid_select2_' .$ident .'(this);playlist_select_' .$ident .'(' .$i .') ">' .($jj + 1) .' - ' .$vds1[0]->title .'</p>';
                                    $jj = $jj + 1;
                                }elseif($vds1[0]->type == 'youtube'){
                                    echo '<p class="vid_title_' .$ident .'" ondblclick="jQuery(\'.show_vid_' .$ident .'\').click()" onmouseover="this.style.color=\'#' .$theme->textHoverColor .'\';this.style.background=\'rgba(' .HEXDEC(SUBSTR($theme->itemBgHoverColor, 0, 2)) .',' .HEXDEC(SUBSTR($theme->itemBgHoverColor, 2, 2)) .',' .HEXDEC(SUBSTR($theme->itemBgHoverColor, 4, 2)) .',0.4)\'" onmouseout="this.style.color=\'#' .$theme->textColor .'\';this.style.background=\' none\'" style="padding: 0px !important;color:#' .$theme->textColor .' !important;font-size:' .$theme->libDetailsTextSize .'px !important;line-height:30px !important;cursor:pointer; margin: 0px !important;" onclick="jQuery(\'#HD_on_' .$ident .'\').val(0);jQuery(\'#track_list_' .$ident .'_' .$i .'\').find(\'.vid_thumb_' .$ident .'\')[' .$jj .'].click();playBTN_' .$ident .'();current_playlist_videos_' .$ident .'();vid_num=' .$jj .';jQuery(\'#current_track_' .$ident .'\').val(' .$jj .');vid_select2_' .$ident .'(this);playlist_select_' .$ident .'(' .$i .') ">' .($jj + 1) .' - ' .$vds1[0]->title .'</p>';
                                    $jj = $jj + 1;
                                }elseif($vds1[0]->type == 'vimeo'){
                                    echo '<p class="vid_title_' .$ident .'" ondblclick="jQuery(\'.show_vid_' .$ident .'\').click()" onmouseover="this.style.color=\'#' .$theme->textHoverColor .'\';this.style.background=\'rgba(' .HEXDEC(SUBSTR($theme->itemBgHoverColor, 0, 2)) .',' .HEXDEC(SUBSTR($theme->itemBgHoverColor, 2, 2)) .',' .HEXDEC(SUBSTR($theme->itemBgHoverColor, 4, 2)) .',0.4)\'" onmouseout="this.style.color=\'#' .$theme->textColor .'\';this.style.background=\' none\'" style="padding: 0px !important;color:#' .$theme->textColor .' !important;font-size:' .$theme->libDetailsTextSize .'px !important;line-height:30px !important;cursor:pointer; margin: 0px !important;" onclick="jQuery(\'#HD_on_' .$ident .'\').val(0);jQuery(\'#track_list_' .$ident .'_' .$i .'\').find(\'.vid_thumb_' .$ident .'\')[' .$jj .'].click();playBTN_' .$ident .'();current_playlist_videos_' .$ident .'();vid_num=' .$jj .';jQuery(\'#current_track_' .$ident .'\').val(' .$jj .');vid_select2_' .$ident .'(this);playlist_select_' .$ident .'(' .$i .') ">' .($jj + 1) .' - ' .$vds1[0]->title .'</p>';
                                    $jj = $jj + 1;
                                }
                            }
                            echo '</div></div>
</td>
</tr>
</table>';
                        }
                        ?>
                    </td>
                    <td style="vertical-align:bottom; border:none;background: transparent; position: relative;width: 5% !important;padding: 0px !important;max-width: 20px !important;">
                        <table
                            style='height:<?php echo $theme->appHeight - 46 ?>px; border:none;border-collapse: inherit;'>
                            <tr style="background: transparent;">
                                <td height='100%'
                                    style="border:none;background: transparent; vertical-align: middle;padding: 0px !important;">
                                    <img src="<?php echo plugins_url('', __FILE__) ?>/images/next.png"
                                         style="cursor:pointer;border:none;background: transparent;display:inline !important; "
                                         id="button27_<?php echo $ident ?>" onclick="nextPage_<?php echo $ident ?>()"/>
                                </td>
                            </tr>
                            <tr style="background: transparent;">
                                <td style="border:none;background: transparent;padding: 0px !important;">
                                    <img src="<?php echo plugins_url('', __FILE__) ?>/images/back.png"
                                         style="cursor:pointer; display:none; border:none;background: transparent;"
                                         id="button29_<?php echo $ident ?>"
                                         onclick="openLibTable_<?php echo $ident ?>()"/>
                                </td>
                            </tr>
                            <tr style="background: transparent;">
                                <td style="border:none;background: transparent;padding: 0px !important;">
                                    <img style="cursor:pointer;border:none;background: transparent; position:relative;"
                                         id="button19_<?php echo $ident ?>" class="show_vid_<?php echo $ident ?>"
                                         src="<?php echo plugins_url('', __FILE__) ?>/images/lib.png"/>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr id="tracklist_down_<?php echo $ident ?>" style="display:none;background: transparent">
                    <td height="12px" colspan="2"
                        style="text-align:right;border:none;background: transparent;padding: 5px !important;">
                        <div
                            onmouseover="this.style.background='rgba(<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 0, 2)) ?>,<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 2, 2)) ?>,<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 4, 2)) ?>,0.4)'"
                            onmouseout="this.style.background='none'" id="scroll"
                            style="overflow:hidden;width:50%;height:100%;text-align:center;float:right;cursor:pointer;"
                            onmousedown="scrollBot=setInterval('scrollBottom_<?php echo $ident; ?>()', 30)"
                            onmouseup="clearInterval(scrollBot)" onclick="scrollBottom_<?php echo $ident; ?>()"> 
                            <img src="<?php echo plugins_url('', __FILE__) ?>/images/bot.png"
                                 style="cursor:pointer;border:none;background: transparent;padding:0px !important;"
                                 id="button26_<?php echo $ident ?>"/>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <script type="text/javascript">
            function flashShare(type, b, c) {
                u = location.href;
                u = u.replace('/?', '/index.php?');
                if (u.search('&AlbumId') != -1) {
                    var u_part2 = '';
                    u_part2 = u.substring(u.search('&TrackId') + 2, 1000)
                    if (u_part2.search('&') != -1) {
                        u_part2 = u_part2.substring(u_part2.search('&'), 1000);
                    }
                    u = u.replace(u.substring(u.search('&AlbumId'), 1000), '') + u_part2;
                }
                if (!location.search)
                    u = u + '?';
                else
                    u = u + '&';
                t = document.title;
                switch (type) {
                    case 'fb':
                        window.open('http://www.facebook.com/sharer.php?u=' + encodeURIComponent(u + 'AlbumId=' + b + '&TrackId=' + c) + '&t=' + encodeURIComponent(t), "Facebook", "menubar=1,resizable=1,width=350,height=250");
                        break;
                    case 'g':
                        window.open('http://plus.google.com/share?url=' + encodeURIComponent(u + 'AlbumId=' + b + '&TrackId=' + c) + '&t=' + encodeURIComponent(t), "Google", "menubar=1,resizable=1,width=350,height=250");
                        break;
                    case 'tw':
                        window.open('http://twitter.com/home/?status=' + encodeURIComponent(u + 'AlbumId=' + b + '&TrackId=' + c), "Twitter", "menubar=1,resizable=1,width=350,height=250");
                        break;
                }
            }
        </script>
        <div id="embed_Url_div_<?php echo $ident; ?>"
             style="display:none;text-align:center;background-color:rgba(0,0,0,0.5); height:160px;width:300px;position:relative;left:<?php echo ($theme->appWidth / 2) - 150 ?>px;top:-<?php echo ($theme->appHeight / 2) + 75 ?>px">
            <textarea
                onclick="jQuery('#embed_Url_<?php echo $ident ?>').focus(); jQuery('#embed_Url_<?php echo $ident ?>').select();"
                id="embed_Url_<?php echo $ident ?>" readonly="readonly"
                style="font-size:11px;width:285px;overflow-y:scroll;resize:none;height:100px;position:relative;top:5px;"></textarea>
            <span style="position:relative;top:10px;"><button
                    onclick="jQuery('#embed_Url_div_<?php echo $ident ?>').css('display','none')" style="border:0px">
                    Close
                </button><p style="color:white">Press Ctrl+C to copy the embed code to clipboard</p></span>
        </div>
        <div id="share_buttons_<?php echo $ident; ?>"
             style="text-align:center;height:113px;width:30px;background-color:rgba(0,0,0,0.5);position:relative;z-index:20000;top:<?php echo $share_top; ?>;display:none;">
            <img
                onclick="flashShare('fb',document.getElementById('current_playlist_table_<?php echo $ident; ?>').value,document.getElementById('current_track_<?php echo $ident; ?>').value)"
                style="cursor:pointer;  border:none;background: transparent;padding:0px;max-width: auto;"
                src="<?php echo plugins_url('', __FILE__) ?>/images/facebook.png"/><br>
            <img
                onclick="flashShare('tw',document.getElementById('current_playlist_table_<?php echo $ident; ?>').value,document.getElementById('current_track_<?php echo $ident; ?>').value)"
                style="cursor:pointer; border:none;background: transparent;padding:0px;max-width: auto;"
                src="<?php echo plugins_url('', __FILE__) ?>/images/twitter.png"/><br>
            <img
                onclick="flashShare('g',document.getElementById('current_playlist_table_<?php echo $ident; ?>').value,document.getElementById('current_track_<?php echo $ident; ?>').value)"
                style="cursor:pointer; border:none;background: transparent;padding:0px;max-width: auto;"
                src="<?php echo plugins_url('', __FILE__) ?>/images/googleplus.png"/><br>
            <img
                onclick="jQuery('#embed_Url_div_<?php echo $ident; ?>').css('display','');embed_url_<?php echo $ident; ?>(document.getElementById('current_playlist_table_<?php echo $ident; ?>').value,document.getElementById('current_track_<?php echo $ident; ?>').value)"
                style="cursor:pointer; border:none; background: transparent;padding:0px;max-width: auto;"
                src="<?php echo plugins_url('', __FILE__) ?>/images/embed.png"/>
        </div>
        </div>
    <?php
    $sufffle = str_replace('Shuffle', 'shuffle', $theme->defaultShuffle);
    if ($sufffle == 'shuffleOff')
        $shuffle = 0;
    else
        $shuffle = 1;
    $admin_url = admin_url('admin-ajax.php?action=spiderVeideoPlayervideoonly');
    ?>
        <input type="hidden" id="color_<?php echo $ident; ?>" value="<?php echo "#" .$theme->ctrlsMainColor ?>"/>
        <input type="hidden" id="support_<?php echo $ident; ?>" value="1"/>
        <input type="hidden" id="event_type_<?php echo $ident; ?>" value="mouseenter"/>
        <input type="hidden" id="current_track_<?php echo $ident; ?>" value="0"/>
        <input type="hidden" id="shuffle_<?php echo $ident; ?>" value="<?php echo $shuffle ?>"/>
        <input type="hidden" id="scroll_height_<?php echo $ident ?>" value="0"/>
        <input type="hidden" id="scroll_height2_<?php echo $ident; ?>" value="0"/>
        <input type="hidden" value="<?php echo $l ?>" id="lib_table_count_<?php echo $ident ?>"/>
        <input type="hidden" value="" id="current_lib_table_<?php echo $ident ?>"/>
        <input type="hidden" value="0" id="current_playlist_table_<?php echo $ident; ?>"/>
        <input type="hidden" value="<?php echo $theme->defaultRepeat ?>" id="repeat_<?php echo $ident ?>"/>
        <input type="hidden" value="0" id="HD_on_<?php echo $ident ?>"/>
        <input type="hidden" value="" id="volumeBar_width_<?php echo $ident ?>"/>
        <script src="http://www.youtube.com/player_api"></script>
        <script src="https://f.vimeocdn.com/js/froogaloop2.min.js"></script>
        <script>
       function is_youtube_video_<?php echo $ident ?>(){
            if(jQuery("#videoID_<?php echo $ident ?>").attr("src").indexOf("youtube.com/")>-1 || jQuery("#videoID_<?php echo $ident ?>").attr("src").indexOf("vimeo.com/")>-1){
                return true;}
            return false;
        }
        var ua = navigator.userAgent.toLowerCase();
        var isAndroid = ua.indexOf("android") > -1; 
        var next_vid_<?php echo $ident ?> = false;
        var player_<?php echo $ident ?>;
        var vimeo_play_<?php echo $ident ?> = false;
        var youtube_ready_<?php echo $ident ?> = false; 
        function onPlayerReady_<?php echo $ident ?>(event) {
            youtube_ready_<?php echo $ident ?> = true; 
          <?php  if($theme->autoPlay ==1)
           echo  "player_".$ident.".playVideo();";
               ?>     
           if(next_vid_<?php echo $ident ?>){  
               player_<?php echo $ident ?>.playVideo();}
       
        }
         function onPlayerStateChange_<?php echo $ident ?>(event) {  
            if(event.data === 0) {          
              if (jQuery('#repeat_<?php echo $ident ?>').val() == 'repeatOne') {
                  {if(youtube_ready_<?php echo $ident ?>)player_<?php echo $ident ?>.playVideo();}
                 paly_<?php echo $ident ?>.css('display', 'none');
                pause_<?php echo $ident ?>.css('display', '');
            }
            if (jQuery('#repeat_<?php echo $ident ?>').val() == 'repeatAll') {
                jQuery('#global_body_<?php echo $ident ?> .playNext_<?php echo $ident ?>').click();
                    setTimeout(function(){player_<?php echo $ident ?>.playVideo()},0);
            }
            }if(event.data == YT.PlayerState.PLAYING){
                paly_<?php echo $ident ?>.css('display', 'none');
                pause_<?php echo $ident ?>.css('display', ''); 
            }
            if(event.data == YT.PlayerState.PAUSED)
            {
                paly_<?php echo $ident ?>.css('display', '');
                pause_<?php echo $ident ?>.css('display', 'none'); 
            }
        };
        function onVimeoReady_<?php echo $ident;?>(){
            youtube_ready_<?php echo $ident ?> = true;
            try{
            player_<?php echo $ident;?>.addEvent('play', onPlay_<?php echo $ident ?>);
            player_<?php echo $ident;?>.addEvent('pause', onPause_<?php echo $ident ?>);
            player_<?php echo $ident;?>.addEvent('finish', onFinish_<?php echo $ident ?>);
            
            }catch(err){
                return false;
            }
        }
        function onPlay_<?php echo $ident ?>(id){
            vimeo_play_<?php echo $ident ?> = true;
            paly_<?php echo $ident ?>.css('display', 'none');
            pause_<?php echo $ident ?>.css('display', ''); 
        }
        function onPause_<?php echo $ident ?>(id){
            vimeo_play_<?php echo $ident ?> = false;
            paly_<?php echo $ident ?>.css('display', '');
            pause_<?php echo $ident ?>.css('display', 'none'); 
        }
        function onFinish_<?php echo $ident ?>(id){
            if (jQuery('#repeat_<?php echo $ident ?>').val() == 'repeatOne') {
                {if(youtube_ready_<?php echo $ident ?>)player_<?php echo $ident ?>.playVideo();}
                paly_<?php echo $ident ?>.css('display', 'none');
                pause_<?php echo $ident ?>.css('display', '');
            }
            if (jQuery('#repeat_<?php echo $ident ?>').val() == 'repeatAll') {
                jQuery('#global_body_<?php echo $ident ?> .playNext_<?php echo $ident ?>').click();
               
            }
        }
        function youtube_control_<?php echo $ident;?>(src){
            jQuery("#time_<?php echo $ident;?>").parent('td').hide();
            jQuery("#control_<?php echo $ident;?> .btnPlay").parent('td').hide();
            jQuery(".progressBar_<?php echo $ident;?>").hide();
            jQuery("#volumeTD_<?php echo $ident;?>").parent('td').hide();
            jQuery(".hd_<?php echo $ident;?>").parent('td').hide();
            jQuery(".fullScreen_<?php echo $ident;?>").parent('td').hide();
            var button_width = <?php echo $theme->appWidth; ?> - 240;
            jQuery("#control_<?php echo $ident;?>").attr('style',"overflow:hidden;");
            jQuery("#control_<?php echo $ident;?>").attr("style",jQuery("#control_<?php echo $ident;?>").attr("style")+" top : auto !important;bottom: 0px;width:"+button_width+"px; margin-left:160px;height: 27px;line-height: 27px;background: transparent;");
            jQuery("#control_<?php echo $ident;?> #control_btns_<?php echo $ident;?>").css({"line-height":"27px","width":"100%"});
            jQuery('#global_body_<?php echo $ident;?> #share_buttons_<?php echo $ident;?>').css({'background':'transparent','margin-left': '160px','text-align': 'inherit','top':'-130px'});
            jQuery("#videoID_<?php echo $ident;?>").replaceWith('<iframe id="videoID_<?php echo $ident ?>" type="text/html" width="<?php echo $theme->appWidth; ?>" height="<?php echo $theme->appHeight; ?>" src="'+src+'" frameborder="0" allowfullscreen></iframe>');
            jQuery("#videoID_<?php echo $ident;?>").load(function(){
                youtube_ready_<?php echo $ident ?> = false;
                set_youtube_player_<?php echo $ident;?>();
                var ua = navigator.userAgent.toLowerCase();
                var isAndroid = ua.indexOf("android") > -1; //&& ua.indexOf("mobile");
                if(!isAndroid) {
                        play_<?php echo $ident; ?>();
                       
                }else{
                     paly_<?php echo $ident; ?>.css('display', "");
                    pause_<?php echo $ident; ?>.css('display', "none");
                }
            });            
        }
        function vimeo_control_<?php echo $ident;?>(src){
            jQuery("#time_<?php echo $ident;?>").parent('td').hide();
            jQuery("#control_<?php echo $ident;?> .btnPlay").parent('td').hide();
            jQuery(".progressBar_<?php echo $ident;?>").hide();
            jQuery("#volumeTD_<?php echo $ident;?>").parent('td').hide();
            jQuery(".hd_<?php echo $ident;?>").parent('td').hide();
            jQuery(".fullScreen_<?php echo $ident;?>").parent('td').hide();
            var button_width = <?php echo $theme->appWidth; ?> - 240;
            jQuery("#control_<?php echo $ident;?>").attr('style',"overflow:hidden;");
            jQuery("#control_<?php echo $ident;?>").attr("style",jQuery("#control_1").attr("style")+"top : auto !important;bottom: 0px;width:"+button_width+"px; margin-left:160px;background: transparent;height: 27px;");
            jQuery("#control_<?php echo $ident;?> #control_btns_<?php echo $ident;?>").css({"line-height":"27px","width":"100%"});
            jQuery('#global_body_<?php echo $ident;?> #share_buttons_<?php echo $ident;?>').css({'background':'transparent','margin-left': '160px','text-align': 'inherit','top':'-130px'});
            if(typeof player_<?php echo $ident;?> != "undefined" && player_<?php echo $ident;?> !=null){
            if(typeof player_<?php echo $ident;?>.api == "function"){
                jQuery("#videoID_<?php echo $ident;?>").attr("src",src+';player_id=videoID_<?php echo $ident; ?>;badge=0;byline=0;portrait=0;');
                return false;}
            }
            jQuery("#videoID_<?php echo $ident;?>").replaceWith('<iframe id="videoID_<?php echo $ident ?>" type="text/html" name="videoID_<?php echo $ident ?>" width="<?php echo $theme->appWidth; ?>" height="<?php echo $theme->appHeight; ?>" src="'+src+';player_id=videoID_<?php echo $ident; ?>;badge=0;byline=0;portrait=0;" frameborder="0" allowfullscreen></iframe>');
            jQuery("#videoID_<?php echo $ident;?>").load(function(){
                youtube_ready_<?php echo $ident ?> = false;
                vimeo_play_<?php echo $ident ?> = false;
                set_vimeo_player_<?php echo $ident;?>();
                var ua = navigator.userAgent.toLowerCase();
                var isAndroid = ua.indexOf("android") > -1; //&& ua.indexOf("mobile");
                if(!isAndroid) {
                        play_<?php echo $ident; ?>();
                }else{
                     paly_<?php echo $ident; ?>.css('display', "");
                    pause_<?php echo $ident; ?>.css('display', "none");
                }
            });
            
        }
        function set_youtube_player_<?php echo $ident;?>(){
            
            if(typeof YT.Player != 'undefined'){
            player_<?php echo $ident;?> = new YT.Player('videoID_<?php echo $ident;?>', {
                  events: {'onReady':onPlayerReady_<?php echo $ident;?>,'onStateChange': onPlayerStateChange_<?php echo $ident;?>             
                    }
                 });
            }else
                setTimeout(function(){set_youtube_player_<?php echo $ident;?>()},200);
        }
  
        function set_vimeo_player_<?php echo $ident;?>(){
            
            if(typeof $f != 'undefined'){ 
            jQuery('#videoID_<?php echo $ident;?>').each(function(){
                player_<?php echo $ident;?> =  $f(this);
                player_<?php echo $ident;?>.addEvent('ready', onVimeoReady_<?php echo $ident ?>);
                player_<?php echo $ident;?>.playVideo = function(){
                    if(!vimeo_play_<?php echo $ident ?>)
                    setTimeout(function(){player_<?php echo $ident;?>.playVideo();},500);
                    player_<?php echo $ident;?>.api("play");
                }
                player_<?php echo $ident;?>.pauseVideo = function(){player_<?php echo $ident;?>.api("pause");}
            });
            }else
                setTimeout(function(){set_vimeo_player_<?php echo $ident;?>()},200);
        }
        function video_control_<?php echo $ident;?>(src){
           jQuery("#time_<?php echo $ident;?>").parent('td').show();
           jQuery("#control_<?php echo $ident;?> .btnPlay").parent('td').show();
            jQuery(".progressBar_<?php echo $ident;?>").show();
            jQuery("#volumeTD_<?php echo $ident;?>").parent('td').show();
            jQuery(".hd_<?php echo $ident;?>").parent('td').show();
            jQuery(".fullScreen_<?php echo $ident;?>").parent('td').show();
            jQuery("#control_<?php echo $ident;?>").attr('style',"overflow:hidden;");
            jQuery("#control_<?php echo $ident;?> #control_btns_<?php echo $ident;?>").css("line-height","");
            jQuery('#global_body_<?php echo $ident;?> #share_buttons_<?php echo $ident;?>').css({'background':'transparent','margin-left': '','text-align': 'center','top':'<?php echo $share_top;?>'});
            jQuery("#videoID_<?php echo $ident;?>").replaceWith('<video ontimeupdate="timeUpdate_<?php echo $ident ?>()" ondurationchange="durationChange_<?php echo $ident ?>();" id="videoID_<?php echo $ident ?>" src="'+src+'" poster="<?php echo $vtttt ?>" style="width:100%; height:100%;margin:0px;position: absolute;top:0px;"><p>Your browser does not support the video tag.</p></video>');
            video_<?php echo $ident;?> = jQuery('#videoID_<?php  echo $ident?>');
            v_timeupdate_<?php  echo $ident?>(video_<?php echo $ident;?>);
            v_ended_<?php  echo $ident?>(video_<?php echo $ident;?>);
            youtube_ready_<?php echo $ident ?> = false;
            vimeo_play_<?php echo $ident ?> = false;
            player_<?php echo $ident;?> = null;
        }
        var video_<?php echo $ident;?> = jQuery('#videoID_<?php  echo $ident?>');
        var paly_<?php echo $ident;?> = jQuery('#global_body_<?php echo $ident;?> .btnPlay');
        var pause_<?php echo $ident;?> = jQuery('#global_body_<?php echo $ident;?> .btnPause');
        var check_play_<?php echo $ident;?> = false;
        function embed_url_<?php echo $ident;?>(a, b) {
//            jQuery('#embed_Url_<?php  echo $ident?>').html('<iframe allowFullScreen allowTransparency="true" frameborder="0" width="<?php echo $theme->appWidth ?>" height="<?php echo $theme->appHeight ?>" src="' + location.href + '&AlbumId=' + a + '&TrackId=' + b + '" type="text/html" ></iframe>')
            jQuery('#embed_Url_<?php  echo $ident?>').focus();
            jQuery('#embed_Url_<?php  echo $ident?>').select();
        }
        
        jQuery('#global_body_<?php echo $ident;?> .share_<?php echo $ident;?>, #global_body_<?php echo $ident;?> #share_buttons_<?php echo $ident;?>').on('mouseenter', function () {
            left = jQuery('#global_body_<?php echo $ident;?> .share_<?php echo $ident;?>').position().left;
            if (parseInt(jQuery('#global_body_<?php echo $ident;?> #play_list_<?php  echo $ident?>').css('width')) == 0)
                jQuery('#global_body_<?php echo $ident;?> #share_buttons_<?php echo $ident;?>').css('left', left)
            else
                <?php if ($theme->playlistPos==1){ ?>
                jQuery('#global_body_<?php echo $ident;?> #share_buttons_<?php echo $ident;?>').css('left', left +<?php echo $theme->playlistWidth ?>)
            <?php } else {?>
            jQuery('#global_body_<?php echo $ident;?> #share_buttons_<?php echo $ident;?>').css('left', left)
            <?php }?>
            jQuery('#global_body_<?php echo $ident;?> #share_buttons_<?php echo $ident;?>').css('display', '')
        })
        jQuery('#global_body_<?php echo $ident;?> .share_<?php echo $ident;?>,#global_body_<?php echo $ident;?> #share_buttons_<?php echo $ident;?>').on('mouseleave', function () {
            jQuery('#global_body_<?php echo $ident;?> #share_buttons_<?php echo $ident;?>').css('display', 'none')
        })
        if (<?php echo $theme->autoPlay ?>==1
        )
        {
            setTimeout(function () {
                jQuery('#thumb_0_<?php echo $ident?>').click()
            }, 500);
        }
        <?php if($sufffle=='shuffleOff') {?>
        if (jQuery('#global_body_<?php echo $ident;?> .shuffle_<?php  echo $ident?>')[0]) {
            jQuery('#global_body_<?php echo $ident;?> .shuffle_<?php  echo $ident?>')[0].style.display = "none";
            jQuery('#global_body_<?php echo $ident;?> .shuffle_<?php  echo $ident?>')[1].style.display = "";
        }
        <?php
		}
		else
		{
		?>
        if (jQuery('#global_body_<?php echo $ident;?> .shuffle_<?php  echo $ident?>')[0]) {
            jQuery('#global_body_<?php echo $ident;?> .shuffle_<?php  echo $ident?>')[1].style.display = "none";
            jQuery('#global_body_<?php echo $ident;?> .shuffle_<?php  echo $ident?>')[0].style.display = "";
        }
        <?php } ?>
        jQuery('#global_body_<?php echo $ident;?> .fullScreen_<?php echo $ident;?>').on('click', function () {
            if (video_<?php echo $ident;?>[0].mozRequestFullScreen)
                video_<?php echo $ident;?>[0].mozRequestFullScreen();
            if (video_<?php echo $ident;?>[0].webkitEnterFullscreen)
                video_<?php echo $ident;?>[0].webkitEnterFullscreen()
        })
        jQuery('#global_body_<?php echo $ident;?> .stop_<?php echo $ident;?>').on('click', function () {
            video_<?php echo $ident;?>[0].currentTime = 0;
            video_<?php echo $ident;?>[0].pause();
            paly_<?php echo $ident;?>.css('display', "");
            pause_<?php echo $ident;?>.css('display', "none");
        })
        <?php if($theme->defaultRepeat=='repeatOff'){ ?>
        if (jQuery('#global_body_<?php echo $ident;?> .repeat_<?php  echo $ident?>')[0]) {
            jQuery('#global_body_<?php echo $ident;?> .repeat_<?php  echo $ident?>')[0].style.display = "none";
            jQuery('#global_body_<?php echo $ident;?> .repeat_<?php  echo $ident?>')[1].style.display = "";
            jQuery('#global_body_<?php echo $ident;?> .repeat_<?php  echo $ident?>')[2].style.display = "none";
        }
        <?php }?>
        <?php if($theme->defaultRepeat=='repeatOne'){ ?>
        if (jQuery('#global_body_<?php echo $ident;?> .repeat_<?php  echo $ident?>')[0]) {
            jQuery('#global_body_<?php echo $ident;?> .repeat_<?php  echo $ident?>')[0].style.display = "none";
            jQuery('#global_body_<?php echo $ident;?> .repeat_<?php  echo $ident?>')[1].style.display = "none";
            jQuery('#global_body_<?php echo $ident;?> .repeat_<?php  echo $ident?>')[2].style.display = "";
            jQuery('#videoID_<?php echo $ident;?>').attr('scr',jQuery('#videoID_<?php echo $ident;?>').attr('scr')+"&loop=1");
        }
        <?php }?>
        <?php if($theme->defaultRepeat=='repeatAll'){ ?>
        if (jQuery('.repeat_<?php  echo $ident?>')[0]) {
            jQuery('#global_body_<?php echo $ident;?> .repeat_<?php  echo $ident?>')[0].style.display = "";
            jQuery('#global_body_<?php echo $ident;?> .repeat_<?php  echo $ident?>')[1].style.display = "none";
            jQuery('#global_body_<?php echo $ident;?> .repeat_<?php  echo $ident?>')[2].style.display = "none";
        }
        <?php }?>
        jQuery('.repeat_<?php  echo $ident?>').on('click', function () {
            repeat_<?php  echo $ident?> = jQuery('#repeat_<?php  echo $ident?>').val();
            switch (repeat_<?php  echo $ident?>) {
                case 'repeatOff':
                    jQuery('#repeat_<?php  echo $ident?>').val('repeatOne');
                    jQuery('.repeat_<?php  echo $ident?>')[0].style.display = "none";
                    jQuery('.repeat_<?php  echo $ident?>')[1].style.display = "none";
                    jQuery('.repeat_<?php  echo $ident?>')[2].style.display = "";
                    jQuery('#videoID_<?php echo $ident;?>').attr('scr',jQuery('#videoID_<?php echo $ident;?>').attr('scr')+"&loop=1");
                    break;
                case 'repeatOne':
                    jQuery('#repeat_<?php  echo $ident?>').val('repeatAll');
                    jQuery('.repeat_<?php  echo $ident?>')[0].style.display = "";
                    jQuery('.repeat_<?php  echo $ident?>')[1].style.display = "none";
                    jQuery('.repeat_<?php  echo $ident?>')[2].style.display = "none";
                    break;
                case 'repeatAll':
                    jQuery('#repeat_<?php  echo $ident?>').val('repeatOff');
                    jQuery('.repeat_<?php  echo $ident?>')[0].style.display = "none";
                    jQuery('.repeat_<?php  echo $ident?>')[1].style.display = "";
                    jQuery('.repeat_<?php  echo $ident?>')[2].style.display = "none";
                    break;
            }
        })
        jQuery('#global_body_<?php echo $ident;?> #voulume_img_<?php echo $ident;?>').live('click', function () {
            if (jQuery('#global_body_<?php echo $ident;?> .volume_<?php echo $ident;?>')[0].style.width != '0%') {
                video_<?php echo $ident;?>[0].volume = 0;
                jQuery('body #volumeBar_width_<?php  echo $ident?>').val(jQuery('#global_body_<?php echo $ident;?> .volume_<?php echo $ident;?>')[0].style.width)
                jQuery('#global_body_<?php echo $ident;?> .volume_<?php echo $ident;?>').css('width', '0%')
            }
            else {
                video_<?php echo $ident;?>[0].volume = parseInt(jQuery('body  #volumeBar_width_<?php  echo $ident?>').val()) / 100;
                jQuery('#global_body_<?php echo $ident;?> .volume_<?php echo $ident;?>').css('width', jQuery('body #volumeBar_width_<?php  echo $ident?>').val())
            }
        })
        jQuery('.hd_<?php echo $ident;?>').live('click', function () {
            current_time_<?php  echo $ident?> = video_<?php echo $ident;?>[0].currentTime;
            HD_on_<?php  echo $ident?> = jQuery('#HD_on_<?php  echo $ident?>').val();
            current_playlist_table_<?php echo $ident;?> = jQuery('#current_playlist_table_<?php echo $ident;?>').val();
            current_track_<?php echo $ident;?> = jQuery('#current_track_<?php echo $ident;?>').val();
            if (jQuery('#track_list_<?php  echo $ident?>_' + current_playlist_table_<?php echo $ident;?>).find('#urlHD_' + current_track_<?php echo $ident?> + '_' +<?php echo $ident?>).val() && HD_on_<?php  echo $ident?> == 0) {
                document.getElementById('videoID_<?php  echo $ident?>').src = jQuery('#track_list_<?php  echo $ident?>_' + current_playlist_table_<?php  echo $ident?>).find('#urlHD_' + current_track_<?php echo $ident?> + '_' +<?php echo $ident?>).val();
                play_<?php  echo $ident?>();
                setTimeout('video_<?php echo $ident;?>[0].currentTime=current_time_<?php echo $ident?>', 500)
                jQuery('#HD_on_<?php  echo $ident?>').val(1);
            }
            if (jQuery('#track_list_<?php  echo $ident?>_' + current_playlist_table_<?php  echo $ident?>).find('#urlHD_' + current_track_<?php echo $ident?> + '_' +<?php echo $ident?>).val() && HD_on_<?php echo $ident?> == 1) {
                jQuery('#track_list_<?php  echo $ident?>_' + current_playlist_table_<?php  echo $ident?>).find('#thumb_' + current_track_<?php echo $ident?> + '_' +<?php echo $ident?>).click();
                setTimeout('video_<?php echo $ident;?>[0].currentTime=current_time_<?php echo $ident?>', 500)
                jQuery('#HD_on_<?php  echo $ident?>').val(0);
            }
        })
        function support_<?php echo $ident;?>(i, j) {
            if (jQuery('#track_list_<?php  echo $ident?>_' + i).find('#vid_type_' + j + '_<?php echo $ident?>').val() != 'http') {
                jQuery('#not_supported_<?php  echo $ident?>').css('display', '');
                jQuery('#support_<?php echo $ident;?>').val(0);
            }
            else {
                jQuery('#not_supported_<?php  echo $ident?>').css('display', 'none');
                jQuery('#support_<?php echo $ident;?>').val(1);
            }
        }
        jQuery('.play_<?php echo $ident;?>').on('click', function () {
            video_<?php echo $ident;?>[0].play();
        })
        jQuery('.pause_<?php echo $ident;?>').on('click', function () {
            video_<?php echo $ident;?>[0].pause();
        })
        function vid_select_<?php echo $ident?>(x) {
            jQuery("div.vid_thumb_<?php echo $ident?>").each(function () {
                if (jQuery(this).find("img")) {
                    jQuery(this).find("img").hide(20);
                    if (jQuery(this).find("img")[0])
                        jQuery(this).find("img")[0].style.display = "none";
                }
                jQuery(this).css('background', 'none');
            })
            jQuery("div.vid_thumb_<?php echo $ident?>").each(function () {
                jQuery(this).mouseenter(function () {
                    if (jQuery(this).find("img"))
                        jQuery(this).find("img").slideDown(100);
                    jQuery(this).css('background', 'rgba(<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 0, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 2, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 4, 2)) ?>, <?php echo $theme->framesBgAlpha/100; ?>)')
                    jQuery(this).css('color', '#<?php echo $theme->textHoverColor  ?>')
                })
                jQuery(this).mouseleave(function () {
                    if (jQuery(this).find("img"))
                        jQuery(this).find("img").slideUp(300);
                    jQuery(this).css('background', 'none');
                    jQuery(this).css('color', '#<?php echo $theme->textColor  ?>')
                });
                jQuery(this).css('color', '#<?php echo $theme->textColor  ?>')
            })
            jQuery(x).unbind('mouseleave mouseenter');
            if (jQuery(x).find("img"))
                jQuery(x).find("img").show(10);
            jQuery(x).css('background', 'rgba(<?php echo HEXDEC(SUBSTR($theme->itemBgSelectedColor, 0, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->itemBgSelectedColor, 2, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->itemBgSelectedColor, 4, 2)) ?>, <?php echo $theme->framesBgAlpha/100; ?>)')
            jQuery(x).css('color', '#<?php echo $theme->textSelectedColor  ?>')
        }
        function vid_select2_<?php echo $ident?>(x) {
            jQuery("p.vid_title_<?php echo $ident?>").each(function () {
                this.onmouseover = function () {
                    this.style.color = '#' + '<?php echo $theme->textHoverColor?>';
                    this.style.background = 'rgba(<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 0, 2)) ?>,<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 2, 2))?>,<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 4, 2)) ?>,0.4)'
                }
                this.onmouseout = function () {
                    this.style.color = '<?php echo '#'.$theme->textColor ?>';
                    this.style.background = " none"
                }
                jQuery(this).css('background', 'none');
                jQuery(this).css('color', '#<?php echo $theme->textColor  ?>');
            })
            jQuery(x).css('background', 'rgba(<?php echo HEXDEC(SUBSTR($theme->itemBgSelectedColor, 0, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->itemBgSelectedColor, 2, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->itemBgSelectedColor, 4, 2)) ?>, <?php echo $theme->framesBgAlpha/100; ?>)')
            jQuery(x).css('color', '#<?php echo $theme->textSelectedColor  ?>')
            x.onmouseover = null;
            x.onmouseout = null;
        }
        function playlist_select_<?php echo $ident;?>(x) {
            jQuery("#global_body_<?php echo $ident;?> td.playlist_td_<?php echo $ident;?>").each(function () {
                jQuery(this).css('background', 'none');
                jQuery(this).css('color', '#<?php echo $theme->textColor  ?>');
                this.onmouseover = function () {
                    this.style.color = '#' + '<?php echo $theme->textHoverColor?>';
                    this.style.background = 'rgba(<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 0, 2)) ?>,<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 2, 2))?>,<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 4, 2)) ?>,0.4)'
                }
                this.onmouseout = function () {
                    this.style.color = '<?php echo '#'.$theme->textColor ?>';
                    this.style.background = " none"
                }
            })
            jQuery('#playlist_' + x + '_<?php  echo $ident?>').css('background', 'rgba(<?php echo HEXDEC(SUBSTR($theme->itemBgSelectedColor, 0, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->itemBgSelectedColor, 2, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->itemBgSelectedColor, 4, 2)) ?>, <?php echo $theme->framesBgAlpha/100; ?>)')
            jQuery('#playlist_' + x + '_<?php  echo $ident?>').css('color', '#<?php echo $theme->textSelectedColor  ?>')
            jQuery('#playlist_' + x + '_<?php  echo $ident?>')[0].onmouseover = null
            jQuery('#playlist_' + x + '_<?php  echo $ident?>')[0].onmouseout = null
        }
        jQuery('.shuffle_<?php  echo $ident?>').on('click', function () {
            if (jQuery('#shuffle_<?php  echo $ident?>').val() == 0) {
                jQuery('#shuffle_<?php  echo $ident?>').val(1);
                jQuery('.shuffle_<?php  echo $ident?>')[1].style.display = "none";
                jQuery('.shuffle_<?php  echo $ident?>')[0].style.display = "";
            }
            else {
                jQuery('#shuffle_<?php  echo $ident?>').val(0);
                jQuery('.shuffle_<?php  echo $ident?>')[0].style.display = "none";
                jQuery('.shuffle_<?php  echo $ident?>')[1].style.display = "";
            }
        });
        jQuery("div.vid_thumb_<?php echo $ident?>").each(function () {
            jQuery(this).mouseenter(function () {
                if (jQuery(this).find("img"))
                    jQuery(this).find("img").slideToggle(100);
                jQuery(this).css('background', 'rgba(<?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 0, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 2, 2)) ?>, <?php echo HEXDEC(SUBSTR($theme->itemBgHoverColor, 4, 2)) ?>, <?php echo $theme->framesBgAlpha/100; ?>)')
                jQuery(this).css('color', '#<?php echo $theme->textHoverColor  ?>')
            })
            jQuery(this).mouseleave(function () {
                if (jQuery(this).find("img"))
                    jQuery(this).find("img").slideToggle(300);
                jQuery(this).css('background', 'none');
                jQuery(this).css('color', '#<?php echo $theme->textColor  ?>')
            });
        })
        function timeUpdate_<?php  echo $ident?>() {
            if (parseInt(document.getElementById('videoID_<?php  echo $ident?>').currentTime / 60) < 10 && parseInt(document.getElementById('videoID_<?php  echo $ident?>').currentTime % 60 < 10))
                document.getElementById('time_<?php  echo $ident?>').innerHTML = '0' + parseInt(document.getElementById('videoID_<?php  echo $ident?>').currentTime / 60) + ':0' + parseInt(document.getElementById('videoID_<?php  echo $ident?>').currentTime % 60);
            if (parseInt(document.getElementById('videoID_<?php  echo $ident?>').currentTime / 60) < 10)
                document.getElementById('time_<?php  echo $ident?>').innerHTML = '0' + parseInt(document.getElementById('videoID_<?php  echo $ident?>').currentTime / 60) + ':' + parseInt(document.getElementById('videoID_<?php  echo $ident?>').currentTime % 60);
            if (parseInt(document.getElementById('videoID_<?php  echo $ident?>').currentTime % 60) < 10)
                document.getElementById('time_<?php  echo $ident?>').innerHTML = '0' + parseInt(document.getElementById('videoID_<?php  echo $ident?>').currentTime / 60) + ':0' + parseInt(document.getElementById('videoID_<?php  echo $ident?>').currentTime % 60);
        }
        function durationChange_<?php  echo $ident?>() {
            if (parseInt(document.getElementById('videoID_<?php  echo $ident?>').duration / 60) < 10 && parseInt(document.getElementById('videoID_<?php  echo $ident?>').duration % 60 < 10))
                document.getElementById('duration_<?php  echo $ident?>').innerHTML = '0' + parseInt(document.getElementById('videoID_<?php  echo $ident?>').duration / 60) + ':0' + parseInt(document.getElementById('videoID_<?php  echo $ident?>').duration % 60);
            if (parseInt(document.getElementById('videoID_<?php  echo $ident?>').duration / 60) < 10)
                document.getElementById('duration_<?php  echo $ident?>').innerHTML = '0' + parseInt(document.getElementById('videoID_<?php  echo $ident?>').duration / 60) + ':' + parseInt(document.getElementById('videoID_<?php  echo $ident?>').duration % 60);
            if (parseInt(document.getElementById('videoID_<?php  echo $ident?>').duration % 60) < 10)
                document.getElementById('duration_<?php  echo $ident?>').innerHTML = parseInt(document.getElementById('videoID_<?php  echo $ident?>').duration / 60) + ':0' + parseInt(document.getElementById('videoID_<?php  echo $ident?>').duration % 60);
        }
        function scrollBottom_<?php echo $ident;?>() {
            current_playlist_table_<?php echo $ident;?> = document.getElementById('current_playlist_table_<?php echo $ident;?>').value;
            if (document.getElementById('scroll_div_' + current_playlist_table_<?php  echo $ident?> + '_<?php echo $ident?>').offsetHeight + parseInt(document.getElementById("scroll_div_" + current_playlist_table_<?php  echo $ident?> + '_<?php echo $ident?>').style.top) + 55 <= document.getElementById('global_body_<?php  echo $ident?>').offsetHeight)
                return false;
            document.getElementById('scroll_height_<?php  echo $ident?>').value = parseInt(document.getElementById('scroll_height_<?php  echo $ident?>').value) + 5
            document.getElementById("scroll_div_" + current_playlist_table_<?php echo $ident;?> + '_<?php echo $ident?>').style.top = "-" + document.getElementById('scroll_height_<?php  echo $ident?>').value + "px";
        }
        ;
        function scrollTop_<?php echo $ident;?>() {
            current_playlist_table_<?php  echo $ident?> = document.getElementById('current_playlist_table_<?php echo $ident;?>').value;
            if (document.getElementById('scroll_height_<?php  echo $ident?>').value <= 0)
                return false;
            document.getElementById('scroll_height_<?php  echo $ident?>').value = parseInt(document.getElementById('scroll_height_<?php  echo $ident?>').value) - 5
            document.getElementById("scroll_div_" + current_playlist_table_<?php  echo $ident?> + '_<?php echo $ident?>').style.top = "-" + document.getElementById('scroll_height_<?php  echo $ident?>').value + "px";
        }
        ;
        function scrollBottom2_<?php echo $ident;?>() {
            current_playlist_table_<?php  echo $ident?> = document.getElementById('current_playlist_table_<?php echo $ident;?>').value;
            if (!current_playlist_table_<?php  echo $ident?>) {
                current_playlist_table_<?php  echo $ident?> = 0;
            }
            if (document.getElementById('scroll_div2_' + current_playlist_table_<?php  echo $ident?> + '_<?php  echo $ident?>').offsetHeight + parseInt(document.getElementById("scroll_div2_" + current_playlist_table_<?php  echo $ident?> + "_<?php  echo $ident?>").style.top) + 150 <= document.getElementById('global_body_<?php  echo $ident?>').offsetHeight)
                return false;
            document.getElementById('scroll_height2_<?php echo $ident;?>').value = parseInt(document.getElementById('scroll_height2_<?php echo $ident;?>').value) + 5
            document.getElementById("scroll_div2_" + current_playlist_table_<?php  echo $ident?> + "_<?php  echo $ident?>").style.top = "-" + document.getElementById('scroll_height2_<?php echo $ident;?>').value + "px";
        }
        ;
        function scrollTop2_<?php echo $ident;?>() {
            current_playlist_table_<?php  echo $ident?> = document.getElementById('current_playlist_table_<?php echo $ident;?>').value;
            if (document.getElementById('scroll_height2_<?php echo $ident;?>').value <= 0)
                return false;
            document.getElementById('scroll_height2_<?php echo $ident;?>').value = parseInt(document.getElementById('scroll_height2_<?php echo $ident;?>').value) - 5
            document.getElementById("scroll_div2_" + current_playlist_table_<?php  echo $ident?> + "_<?php  echo $ident?>").style.top = "-" + document.getElementById('scroll_height2_<?php echo $ident;?>').value + "px";
        }
        ;
        function openPlaylist_<?php  echo $ident?>(i, j) {
            document.getElementById('scroll_height_<?php  echo $ident?>').value = 0;
            lib_table_count_<?php  echo $ident?> = document.getElementById('lib_table_count_<?php  echo $ident?>').value;
            for (lib_table = 0; lib_table < lib_table_count_<?php  echo $ident?>; lib_table++) {
                document.getElementById('lib_table_' + lib_table + '_<?php  echo $ident?>').style.display = "none";
            }
            jQuery("#playlist_table_" + i + "_<?php  echo $ident?>").fadeIn(700);
            document.getElementById('current_lib_table_<?php  echo $ident?>').value = j;
            document.getElementById('current_playlist_table_<?php echo $ident;?>').value = i;
            document.getElementById('tracklist_down_<?php  echo $ident?>').style.display = "";
            document.getElementById('tracklist_up_<?php  echo $ident?>').style.display = "";
            document.getElementById('button29_<?php  echo $ident?>').style.display = "block";
            document.getElementById('button27_<?php  echo $ident?>').onclick = function () {
                nextPlaylist_<?php echo $ident;?>()
            };
            document.getElementById('button28_<?php  echo $ident?>').onclick = function () {
                prevPlaylist_<?php echo $ident;?>()
            };
        }
        function nextPlaylist_<?php echo $ident;?>() {
            document.getElementById('scroll_height_<?php  echo $ident?>').value = 0;
            lib_table_count_<?php  echo $ident?> = document.getElementById('lib_table_count_<?php  echo $ident?>').value;
            for (lib_table = 0; lib_table < lib_table_count_<?php  echo $ident?>; lib_table++) {
                document.getElementById('lib_table_' + lib_table + '_<?php  echo $ident?>').style.display = "none";
            }
            current_lib_table_<?php  echo $ident?> = document.getElementById('current_lib_table_<?php  echo $ident?>').value;
            next_playlist_table_<?php  echo $ident?> = parseInt(document.getElementById('current_playlist_table_<?php echo $ident;?>').value) + 1;
            current_playlist_table_<?php  echo $ident?> = parseInt(document.getElementById('current_playlist_table_<?php echo $ident;?>').value);
            if (next_playlist_table_<?php  echo $ident?> ><?php echo count($playlist_array)-2 ?>)
                return false;
            jQuery("#playlist_table_" + current_playlist_table_<?php  echo $ident?> + "_<?php  echo $ident?>").css('display', 'none');
            jQuery("#playlist_table_" + next_playlist_table_<?php  echo $ident?> + "_<?php  echo $ident?>").fadeIn(700);
            document.getElementById('current_playlist_table_<?php echo $ident;?>').value = next_playlist_table_<?php  echo $ident?>;
            document.getElementById('tracklist_down_<?php  echo $ident?>').style.display = "";
            document.getElementById('tracklist_up_<?php  echo $ident?>').style.display = "";
            document.getElementById('button29_<?php  echo $ident?>').style.display = "block";
        }
        function prevPlaylist_<?php echo $ident;?>() {
            document.getElementById('scroll_height_<?php  echo $ident?>').value = 0;
            lib_table_count_<?php  echo $ident?> = document.getElementById('lib_table_count_<?php  echo $ident?>').value;
            for (lib_table = 0; lib_table < lib_table_count_<?php  echo $ident?>; lib_table++) {
                document.getElementById('lib_table_' + lib_table + '_<?php  echo $ident?>').style.display = "none";
            }
            current_lib_table_<?php  echo $ident?> = document.getElementById('current_lib_table_<?php  echo $ident?>').value;
            prev_playlist_table_<?php  echo $ident?> = parseInt(document.getElementById('current_playlist_table_<?php echo $ident;?>').value) - 1;
            current_playlist_table_<?php  echo $ident?> = parseInt(document.getElementById('current_playlist_table_<?php echo $ident;?>').value);
            if (prev_playlist_table_<?php  echo $ident?> < 0)
                return false;
            jQuery("#playlist_table_" + current_playlist_table_<?php  echo $ident?> + "_<?php  echo $ident?>").css('display', 'none');
            jQuery("#playlist_table_" + prev_playlist_table_<?php  echo $ident?> + "_<?php  echo $ident?>").fadeIn(700);
            document.getElementById('current_playlist_table_<?php echo $ident;?>').value = prev_playlist_table_<?php  echo $ident?>;
            document.getElementById('tracklist_down_<?php  echo $ident?>').style.display = "";
            document.getElementById('tracklist_up_<?php  echo $ident?>').style.display = "";
            document.getElementById('button29_<?php  echo $ident?>').style.display = "block";
        }
        function openLibTable_<?php  echo $ident?>() {
            current_lib_table_<?php  echo $ident?> = document.getElementById('current_lib_table_<?php  echo $ident?>').value;
            document.getElementById('scroll_height_<?php  echo $ident?>').value = 0;
            current_playlist_table_<?php  echo $ident?> = document.getElementById('current_playlist_table_<?php echo $ident;?>').value;
            jQuery("#lib_table_" + current_lib_table_<?php  echo $ident?> + "_<?php  echo $ident?>").fadeIn(700);
            document.getElementById('playlist_table_' + current_playlist_table_<?php  echo $ident?> + '_<?php  echo $ident?>').style.display = "none";
            document.getElementById('tracklist_down_<?php  echo $ident?>').style.display = "none";
            document.getElementById('tracklist_up_<?php  echo $ident?>').style.display = "none";
            document.getElementById('button29_<?php  echo $ident?>').style.display = "none";
            document.getElementById('button27_<?php  echo $ident?>').onclick = function () {
                nextPage_<?php  echo $ident?>()
            };
            document.getElementById('button28_<?php  echo $ident?>').onclick = function () {
                prevPage_<?php  echo $ident?>()
            };
        }
        var next_page_<?php  echo $ident?> = 0;
        function nextPage_<?php  echo $ident?>() {
            if (next_page_<?php  echo $ident?> == document.getElementById('lib_table_count_<?php  echo $ident?>').value - 1)
                return false;
            next_page_<?php  echo $ident?> = next_page_<?php  echo $ident?> + 1;
            for (g = 0; g < document.getElementById('lib_table_count_<?php  echo $ident?>').value; g++) {
                document.getElementById('lib_table_' + g + '_<?php  echo $ident?>').style.display = "none";
                if (g == next_page_<?php  echo $ident?>) {
                    jQuery("#lib_table_" + g + "_<?php  echo $ident?>").fadeIn(900);
                }
            }
        }
        function prevPage_<?php  echo $ident?>() {
            if (next_page_<?php  echo $ident?> == 0)
                return false;
            next_page_<?php  echo $ident?> = next_page_<?php  echo $ident?> - 1;
            for (g = 0; g < document.getElementById('lib_table_count_<?php  echo $ident?>').value; g++) {
                document.getElementById('lib_table_' + g + '_<?php  echo $ident?>').style.display = "none";
                if (g == next_page_<?php  echo $ident?>) {
                    jQuery("#lib_table_" + g + "_<?php  echo $ident?>").fadeIn(900);
                }
            }
        }
        function playBTN_<?php echo $ident;?>() {
            current_playlist_table_<?php  echo $ident?> = document.getElementById('current_playlist_table_<?php echo $ident;?>').value;
            track_list_<?php  echo $ident?> = document.getElementById('track_list_<?php echo $ident;?>').value;
            document.getElementById('track_list_<?php echo $ident;?>_' + current_playlist_table_<?php  echo $ident?>).style.display = "block";
            if (current_playlist_table_<?php  echo $ident?> != track_list_<?php  echo $ident?>)
                document.getElementById('track_list_<?php echo $ident;?>_' + track_list_<?php  echo $ident?>).style.display = "none";
            document.getElementById('track_list_<?php echo $ident;?>').value = current_playlist_table_<?php  echo $ident?>;
             if(!is_youtube_video_<?php echo $ident ?>()){
                 
            video_<?php echo $ident;?>[0].play();
             paly_<?php echo $ident;?>.css('display', "none");
            pause_<?php echo $ident;?>.css('display', "");
        }
            else
                if(typeof player_<?php echo $ident;?> != 'undefined'){if(youtube_ready_<?php echo $ident ?>)player_<?php echo $ident ?>.playVideo();}
           
        }
        function play_<?php echo $ident;?>() {
            next_vid_<?php  echo $ident?> = true;
            if(!is_youtube_video_<?php echo $ident ?>()){
            video_<?php echo $ident;?>[0].play();
            }      
            else{
                if(typeof player_<?php echo $ident;?> != 'undefined'){
                    if(youtube_ready_<?php echo $ident ?>){
                        player_<?php echo $ident ?>.playVideo();
                    }
                }
            }
            paly_<?php echo $ident; ?>.css('display', "none");
            pause_<?php echo $ident; ?>.css('display', "");
        }
        jQuery('#global_body_<?php echo $ident;?> .btnPlay <?php if($theme->clickOnVid==1) echo ',#videoID_'.$ident.'' ?>, #global_body_<?php echo $ident;?> .btnPause').on('click', function () {
           if(!is_youtube_video_<?php echo $ident ?>()){
        if (video_<?php echo $ident;?>[0].paused) {
                video_<?php echo $ident;?>[0].play();
                paly_<?php echo $ident;?>.css('display', "none");
                pause_<?php echo $ident;?>.css('display', "");
            }
            else {
                video_<?php echo $ident;?>[0].pause();
                paly_<?php echo $ident;?>.css('display', "");
                pause_<?php echo $ident;?>.css('display', "none");
            }
        
            }else{
                if(!check_play_<?php echo $ident;?>){
                     if(typeof player_<?php echo $ident;?> != 'undefined'){if(youtube_ready_<?php echo $ident ?>)player_<?php echo $ident ?>.playVideo();}
                   check_play_<?php echo $ident;?> = true;
                    paly_<?php echo $ident;?>.css('display', "none");
                pause_<?php echo $ident;?>.css('display', "");
                }else{
                     if(typeof player_<?php echo $ident;?> != 'undefined')player_<?php echo $ident;?>.pauseVideo();
                     check_play_<?php echo $ident;?> = false;
                      paly_<?php echo $ident;?>.css('display', "");
                pause_<?php echo $ident;?>.css('display', "none");
                }
            }
            return false;
        });
        function check_volume_<?php echo $ident;?>() {
            percentage_<?php echo $ident;?> = video_<?php echo $ident;?>[0].volume * 100;
            jQuery('#global_body_<?php echo $ident;?> .volume_<?php echo $ident;?>').css('width', percentage_<?php echo $ident;?> + '%');
            document.getElementById("play_list_<?php  echo $ident?>").style.width = '0px';
            document.getElementById("play_list_<?php  echo $ident?>").style.display = 'none';
        }
        window.onload = check_volume_<?php echo $ident;?>();
        video_<?php echo $ident;?>.on('loadedmetadata', function () {
            jQuery('.duration_<?php echo $ident?>').text(video_<?php echo $ident;?>[0].duration);
        });
        
        function v_timeupdate_<?php  echo $ident?>(el){
             el.on('timeupdate', function () {
                var progress_<?php  echo $ident?> = jQuery('#global_body_<?php echo $ident;?> .progressBar_<?php  echo $ident?>');
                var currentPos_<?php  echo $ident?> = el[0].currentTime; //Get currenttime
                var maxduration_<?php  echo $ident?> = el[0].duration; //Get video duration  
                var percentage_<?php  echo $ident?> = 100 * currentPos_<?php  echo $ident?> / maxduration_<?php echo $ident;?>; //in %
                var position_<?php  echo $ident?> = (<?php echo $theme->appWidth; ?> * percentage_<?php  echo $ident?> / 100
                )
                -progress_<?php  echo $ident?>.offset().left;
                jQuery('#global_body_<?php echo $ident;?> .timeBar_<?php  echo $ident?>').css('width', percentage_<?php  echo $ident?> + '%');
            });
        }
        function v_ended_<?php  echo $ident?>(el){
        el.on('ended', function () {
            if (jQuery('#repeat_<?php  echo $ident?>').val() == "repeatOne") {
               el[0].currentTime = 0;
                if(!is_youtube_video_<?php echo $ident ?>())
                el[0].play();
                 else
                if(typeof player_<?php echo $ident;?> != 'undefined'){if(youtube_ready_<?php echo $ident ?>)player_<?php echo $ident ?>.playVideo();}
                paly_<?php echo $ident;?>.css('display', "none");
                pause_<?php echo $ident;?>.css('display', "");
            }
            if (jQuery('#repeat_<?php  echo $ident?>').val() == "repeatAll") {
                jQuery('#global_body_<?php echo $ident;?> .playNext_<?php  echo $ident?>').click();
            }
            if (jQuery('#repeat_<?php  echo $ident?>').val() == "repeatOff") {
                if (vid_num_<?php  echo $ident?> == video_urls_<?php  echo $ident?>.length - 1) {
                    el[0].currentTime = 0;
                    el[0].pause();
                    paly_<?php echo $ident;?>.css('display', "");
                    pause_<?php echo $ident;?>.css('display', "none");
                }
            }
            <?php if($theme->autoNext==1) { ?>
            if (jQuery('#repeat_<?php  echo $ident?>').val() == "repeatOff")
                if (vid_num_<?php  echo $ident?> == video_urls_<?php  echo $ident?>.length - 1) {
                    el[0].currentTime = 0;
                    el[0].pause();
                    paly_<?php echo $ident;?>.css('display', "");
                    pause_<?php echo $ident;?>.css('display', "none");
                }
                else {
                    jQuery('#global_body_<?php echo $ident;?> .playNext_<?php echo $ident;?>').click();
                }
            <?php }?>
        });
        }
        var timeDrag_<?php echo $ident;?> = false;
        /* Drag status */
        jQuery('#global_body_<?php echo $ident;?> .progressBar_<?php  echo $ident?>').mousedown(function (e) {
            timeDrag_<?php echo $ident;?> = true;
            updatebar_<?php  echo $ident?>(e.pageX);
        });
        jQuery('#global_body_<?php echo $ident;?> .progressBar_<?php  echo $ident?>').select(function () {
        })
        jQuery(document).mouseup(function (e) {
            if (timeDrag_<?php echo $ident;?>) {
                timeDrag_<?php echo $ident;?> = false;
                updatebar_<?php  echo $ident?>(e.pageX);
            }
        });
        jQuery(document).mousemove(function (e) {
            if (timeDrag_<?php echo $ident;?>) {
                updatebar_<?php  echo $ident?>(e.pageX);
            }
        });
        var updatebar_<?php  echo $ident?> = function (x) {
            var progress_<?php  echo $ident?> = jQuery('#global_body_<?php echo $ident;?> .progressBar_<?php  echo $ident?>');
            var maxduration_<?php  echo $ident?> = video_<?php echo $ident;?>[0].duration; //Video duraiton
            var position_<?php  echo $ident?> = x - progress_<?php  echo $ident?>.offset().left; //Click pos
            var percentage_<?php  echo $ident?> = 100 * position_<?php  echo $ident?> / progress_<?php  echo $ident?>.width();
            if (percentage_<?php  echo $ident?> > 100) {
                percentage_<?php  echo $ident?> = 100;
            }
            if (percentage_<?php  echo $ident?> < 0) {
                percentage_<?php  echo $ident?> = 0;
            }
            jQuery('#global_body_<?php echo $ident;?> .timeBar_<?php  echo $ident?>').css('width', percentage_<?php  echo $ident?> + '%');
            jQuery('.spanA').css('left', position_<?php  echo $ident?> + 'px');
            video_<?php echo $ident;?>[0].currentTime = maxduration_<?php  echo $ident?> * percentage_<?php  echo $ident?> / 100;
        };
        function startBuffer_<?php echo $ident;?>() {
            setTimeout(function () {               
                var maxduration_<?php echo $ident;?> = video_<?php echo $ident;?>[0].duration;
                try{
                    var currentBuffer_<?php echo $ident;?> = video_<?php echo $ident;?>[0].buffered.end(0);
                }catch(err){
                     setTimeout(startBuffer_<?php echo $ident;?>, 500);
                     return false;
                }
                var percentage_<?php echo $ident;?> = 100 * currentBuffer_<?php echo $ident;?> / maxduration_<?php echo $ident;?>;
                jQuery('#global_body_<?php echo $ident;?> .bufferBar_<?php  echo $ident?>').css('width', percentage_<?php echo $ident;?> + '%');
                if (currentBuffer_<?php echo $ident;?> < maxduration_<?php echo $ident;?>) {
                    setTimeout(startBuffer_<?php echo $ident;?>, 500);
                }
            }, 800)
        }
        ;
        checkVideoLoad = setInterval(function () {
            if (video_<?php echo $ident;?>[0].duration) {
                setTimeout(startBuffer_<?php echo $ident;?>(), 500);
                clearInterval(checkVideoLoad)
            }
        }, 1000)
        var volume_<?php echo $ident;?> = jQuery('#global_body_<?php echo $ident;?> .volumeBar_<?php echo $ident;?>');
        jQuery('#global_body_<?php echo $ident;?> .muted').click(function () {
            video_<?php echo $ident;?>[0].muted = !video_<?php echo $ident;?>[0].muted;
            return false;
        });
        jQuery('#global_body_<?php echo $ident;?> .volumeBar_<?php echo $ident;?>').on('mousedown', function (e) {
            var position_<?php echo $ident;?> = e.pageX - volume_<?php echo $ident;?>.offset().left;
            var percentage_<?php  echo $ident?> = 100 * position_<?php echo $ident;?> / volume_<?php echo $ident;?>.width();
            jQuery('#global_body_<?php echo $ident;?> .volume_<?php echo $ident;?>').css('width', percentage_<?php  echo $ident?> + '%');
            video_<?php echo $ident;?>[0].volume = percentage_<?php  echo $ident?> / 100;
        });
        var volumeDrag_<?php  echo $ident?> = false;
        /* Drag status */
        jQuery('#global_body_<?php echo $ident;?> .volumeBar_<?php echo $ident;?>').mousedown(function (e) {
            volumeDrag_<?php  echo $ident?> = true;
            updateVolumeBar_<?php  echo $ident?>(e.pageX);
        });
        jQuery(document).mouseup(function (e) {
            if (volumeDrag_<?php  echo $ident?>) {
                volumeDrag_<?php  echo $ident?> = false;
                updateVolumeBar_<?php  echo $ident?>(e.pageX);
            }
        });
        jQuery(document).mousemove(function (e) {
            if (volumeDrag_<?php  echo $ident?>) {
                updateVolumeBar_<?php  echo $ident?>(e.pageX);
            }
        });
        var updateVolumeBar_<?php  echo $ident?> = function (x) {
            var progress_<?php  echo $ident?> = jQuery('#global_body_<?php echo $ident;?> .volumeBar_<?php echo $ident;?>');
            var position_<?php echo $ident;?> = x - progress_<?php  echo $ident?>.offset().left; //Click pos
            var percentage_<?php  echo $ident?> = 100 * position_<?php echo $ident;?> / progress_<?php  echo $ident?>.width();
            if (percentage_<?php  echo $ident?> > 100) {
                percentage_<?php  echo $ident?> = 100;
            }
            if (percentage_<?php  echo $ident?> < 0) {
                percentage_<?php  echo $ident?> = 0;
            }
            jQuery('#global_body_<?php echo $ident;?> .volume_<?php echo $ident;?>').css('width', percentage_<?php  echo $ident?> + '%');
            video_<?php echo $ident;?>[0].volume = percentage_<?php  echo $ident?> / 100;
        };
        var yy = 1;
        controlHideTime_<?php  echo $ident?> = '';
        
       
        jQuery("#global_body_<?php  echo $ident?>").each(function () {
            jQuery(this).mouseleave(function () {
                controlHideTime_<?php  echo $ident?> = setInterval(function () {
                    
                    yy = yy + 1;
                    if (yy <<?php echo $theme->autohideTime ?>) {
                        return false;
                    }
                    else {
                        if(is_youtube_video_<?php echo $ident ?>()){
                        clearInterval(controlHideTime_<?php  echo $ident?>);
                        yy = 1;
                        return false;
                    }
                        clearInterval(controlHideTime_<?php  echo $ident?>);
                        yy = 1;
                        jQuery("#event_type_<?php echo $ident;?>").val('mouseleave');
                        <?php if($theme->playlistAutoHide==1){ ?>
                        jQuery("#play_list_<?php  echo $ident?>").animate({
                            width: "0px",
                        }, 300);
                        setTimeout(function () {
                            jQuery("#play_list_<?php  echo $ident?>").css('display', 'none');
                        }, 300)
                        jQuery("#global_body_<?php echo $ident;?> .control_<?php  echo $ident?>").animate({
                            width: <?php echo $theme->appWidth; ?>+"px",
                            <?php if ($theme->playlistPos==1){ ?>
                            marginLeft: '0px'
                            <?php } else {?>
                            marginRight: '0px'
                            <?php } ?>
                        }, 300);
                        jQuery("#global_body_<?php echo $ident;?> #control_btns_<?php  echo $ident?>").animate({
                            width: <?php echo $theme->appWidth?>+"px",
                        }, 300);
                        /*jQuery("#space").animate({
                         paddingLeft:
                        <?php echo (($theme->appWidth*20)/100) ?>+"px"},300)*/
                        <?php if($theme->playlistOverVid==0 && $theme->playlistPos==1){ ?>
                        jQuery("#videoID_<?php echo $ident;?>").animate({
                            width: <?php echo $theme->appWidth ?>+"px",
                            marginLeft: '0px'
                        }, 300);
                        <?php } ?>
                        <?php if($theme->playlistOverVid==0 && $theme->playlistPos==2){ ?>
                        jQuery("#videoID_<?php echo $ident;?>").animate({
                            width: <?php echo $theme->appWidth ?>+"px",
                        }, 300);
                        <?php } ?>
                        <?php if($theme->ctrlsSlideOut==1){ ?>
                        jQuery('.control').hide("slide", { direction: "<?php if($theme->ctrlsPos==1) echo 'up'; else echo 'down'; ?>" }, 1000);
                        <?php } ?>
                        <?php if($theme->playlistOverVid==0 && $theme->playlistPos==1){ ?>
                        jQuery("#videoID_<?php echo $ident;?>").animate({
                            width: <?php echo $theme->appWidth ?>+"px",
                            marginLeft: '0px'
                        }, 300);
                        
                        <?php } ?>
                        <?php if($theme->playlistOverVid==0 && $theme->playlistPos==2){ ?>
                        jQuery("#videoID_<?php echo $ident;?>").animate({
                            width: <?php echo $theme->appWidth ?>+"px",
                        }, 300);
                        <?php } ?>
                        <?php }?>
                        <?php if($theme->ctrlsSlideOut==1){ ?>
                        jQuery('#global_body_<?php echo $ident;?> .control_<?php  echo $ident?>').hide("slide", { direction: "<?php if($theme->ctrlsPos==1) echo 'up'; else echo 'down'; ?>", queue: false }, 1000);
                        
                        <?php } ?>
                    }
                }, 1000);
            });
            jQuery(this).mouseenter(function () {
                if (controlHideTime_<?php  echo $ident?>) {
                    clearInterval(controlHideTime_<?php  echo $ident?>);
                    yy = 1;
                }
                if (document.getElementById('control_<?php  echo $ident?>').style.display == "none") {
                    jQuery('#global_body_<?php echo $ident;?> .control_<?php  echo $ident?>').show("slide", { direction: "<?php if($theme->ctrlsPos==1) echo 'up'; else echo 'down'; ?>" }, 450);
                    
                }
            })
        });
        
        var xx = 1;
        volumeHideTime_<?php echo $ident;?> = '';
        jQuery("#volumeTD_<?php echo $ident;?>").each(function () {
            jQuery('#volumeTD_<?php echo $ident;?>').mouseleave(function () {
                volumeHideTime_<?php echo $ident;?> = setInterval(function () {
                    xx = xx + 1;
                    if (xx < 2) {
                        return false
                    }
                    else {
                        clearInterval(volumeHideTime_<?php echo $ident;?>);
                        xx = 1;
                        jQuery("#global_body_<?php echo $ident;?> #space").animate({
                            paddingLeft:<?php echo '"'.(($theme->appWidth*20)/100).'px"' ?>,
                        }, 1000);
                        jQuery("#global_body_<?php echo $ident;?> #volumebar_player_<?php echo $ident;?>").animate({
                            width: '0px',
                        }, 1000);
                        percentage_<?php  echo $ident?> = video_<?php echo $ident;?>[0].volume * 100;
                        jQuery('#global_body_<?php echo $ident;?> .volume_<?php echo $ident;?>').css('width', percentage_<?php  echo $ident?> + '%');
                    }
                }, 1000)
            })
            jQuery('#volumeTD_<?php echo $ident;?>').mouseenter(function () {
                if (volumeHideTime_<?php echo $ident;?>) {
                    clearInterval(volumeHideTime_<?php echo $ident;?>)
                    xx = 1;
                }
                jQuery("#global_body_<?php echo $ident;?> #space").animate({
                    paddingLeft:<?php echo '"'.((($theme->appWidth*20)/100)-100).'px"' ?>,
                }, 500);
                jQuery("#global_body_<?php echo $ident;?> #volumebar_player_<?php echo $ident;?>").animate({
                    <?php if($theme->appWidth > 400){ ?>
                    width: '100px',
                    <?php } 
else { ?>
                    width: '50px',
                    <?php } ?>
                }, 500);
            });
        })
        function show_hide_playlist() {
            if (document.getElementById("play_list_<?php  echo $ident?>").style.width == "0px") {
                jQuery("#play_list_<?php  echo $ident?>").css('display', '')
                jQuery("#play_list_<?php  echo $ident?>").animate({
                    width: <?php echo $theme->playlistWidth; ?>+"px",
                }, 500);
                if(!is_youtube_video_<?php echo $ident ?>()){
                jQuery("#global_body_<?php echo $ident;?> .control_<?php  echo $ident?>").animate({
                    width: <?php echo $theme->appWidth-$theme->playlistWidth; ?>+"px",
                    <?php if ($theme->playlistPos==1){ ?>
                    marginLeft: <?php echo $theme->playlistWidth; ?>+'px'
                    <?php } else {?>
                    marginRight: <?php echo $theme->playlistWidth; ?>+'px'
                    <?php } ?>
                }, 500);
                /*jQuery("#space").animate({paddingLeft:
                <?php echo (($theme->appWidth*20)/100)-$theme->playlistWidth ?>+"px"},500)*/
                jQuery("#global_body_<?php echo $ident;?> #control_btns_<?php  echo $ident?>").animate({
                    width: <?php echo $theme->appWidth-$theme->playlistWidth; ?>+"px",
                }, 500);
                <?php if($theme->playlistOverVid==0 && $theme->playlistPos==1){ ?>
                jQuery("#videoID_<?php echo $ident;?>").animate({
                    width: <?php echo $theme->appWidth-$theme->playlistWidth; ?>+'px',
                    marginLeft: <?php echo $theme->playlistWidth; ?>+'px'
                }, 500);
                <?php } ?>
                <?php if($theme->playlistOverVid==0 && $theme->playlistPos==2){ ?>
                jQuery("#videoID_<?php echo $ident;?>").animate({
                    width: <?php echo $theme->appWidth-$theme->playlistWidth; ?>+"px",
                }, 500);
                <?php } ?>
            }
            }
            else {
                jQuery("#global_body_<?php echo $ident;?> #play_list_<?php  echo $ident?>").animate({
                    width: "0px",
                }, 1500);
                setTimeout(function () {
                    jQuery("#play_list_<?php  echo $ident?>").css('display', 'none');
                }, 1500)
                if(!is_youtube_video_<?php echo $ident ?>()){
                jQuery("#global_body_<?php echo $ident;?> .control_<?php  echo $ident?>").animate({
                    width: <?php echo $theme->appWidth; ?>+"px",
                    <?php if ($theme->playlistPos==1){ ?>
                    marginLeft: '0px'
                    <?php } else {?>
                    marginRight: '0px'
                    <?php } ?>
                }, 1500);
                jQuery("#global_body_<?php echo $ident;?> #control_btns_<?php  echo $ident?>").animate({
                    width: <?php echo $theme->appWidth?>+"px",
                }, 1500);
                /*jQuery("#space").animate({paddingLeft:
                <?php echo (($theme->appWidth*20)/100)?>+'px'},1500)*/
                <?php if($theme->playlistOverVid==0 && $theme->playlistPos==1){ ?>
                jQuery("#videoID_<?php echo $ident;?>").animate({
                    width: <?php echo $theme->appWidth ?>+"px",
                    marginLeft: '0px'
                }, 1500);
                <?php } ?>
                <?php if($theme->playlistOverVid==0 && $theme->playlistPos==2){ ?>
                jQuery("#videoID_<?php echo $ident;?>").animate({
                    width: <?php echo $theme->appWidth ?>+"px",
                }, 1500);
                <?php } ?>
            }
            }
        }
        jQuery('#global_body_<?php echo $ident;?> .playlist_<?php  echo $ident?>').on('click', show_hide_playlist);
        current_playlist_table_<?php  echo $ident?> = document.getElementById('current_playlist_table_<?php echo $ident;?>').value;
        video_urls_<?php echo $ident;?> = jQuery('#track_list_<?php  echo $ident?>_' + current_playlist_table_<?php  echo $ident?>).find('.vid_thumb_<?php echo $ident?>');
        function current_playlist_videos_<?php  echo $ident?>() {
            current_playlist_table_<?php  echo $ident?> = document.getElementById('current_playlist_table_<?php echo $ident;?>').value;
            video_urls_<?php  echo $ident?> = jQuery('#track_list_<?php  echo $ident?>_' + current_playlist_table_<?php  echo $ident?>).find('.vid_thumb_<?php echo $ident?>');
        }
        function in_array(needle, haystack, strict) {	// Checks if a value exists in an array
            // 
            // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
            var found = false, key, strict = !!strict;
            for (key in haystack) {
                if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {
                    found = true;
                    break;
                }
            }
            return found;
        }
        var vid_num_<?php  echo $ident?> = 0; //for set cur video number
        var used_track_<?php  echo $ident?> = new Array(); // played vido numbers 
        jQuery('#global_body_<?php echo $ident;?> .playPrev_<?php  echo $ident?>').on('click', function () {
            next_vid_<?php  echo $ident?> = true;
            used_track_<?php  echo $ident?>[used_track_<?php  echo $ident?>.length] = vid_num_<?php  echo $ident?>;
            vid_num_<?php  echo $ident?>--;
            if (used_track_<?php  echo $ident?>.length >= video_urls_<?php  echo $ident?>.length) {
                // reset old list
                used_track_<?php  echo $ident?> = [];
                if (jQuery('#shuffle_<?php  echo $ident?>').val() == 1) {
// get new vido number out of used_tracks
                    vid_num_<?php  echo $ident?> = parseInt(Math.random() * (video_urls_<?php  echo $ident?>.length - 0) + 0);
                    while (in_array(vid_num_<?php  echo $ident?>, used_track_<?php  echo $ident?>)) {
                        vid_num_<?php  echo $ident?> = parseInt(Math.random() * (video_urls_<?php  echo $ident?>.length - 0) + 0);
                    }
                }
            }
            if (jQuery('#shuffle_<?php  echo $ident?>').val() == 1) {
// get new vido number out of used_tracks
                vid_num_<?php  echo $ident?> = parseInt(Math.random() * (video_urls_<?php  echo $ident?>.length - 0) + 0);
                while (in_array(vid_num_<?php  echo $ident?>, used_track_<?php  echo $ident?>)) {
                    vid_num_<?php  echo $ident?> = parseInt(Math.random() * (video_urls_<?php  echo $ident?>.length - 0) + 0);
                }
            }
            if (vid_num_<?php  echo $ident?> <= 0) {
                vid_num_<?php  echo $ident?> = video_urls_<?php  echo $ident?>.length - 1;
            }
            //jQuery('.playPrev_<?php  echo $ident?>').click();
            video_urls_<?php  echo $ident?>[vid_num_<?php  echo $ident?>].click();
        });
        jQuery('#global_body_<?php echo $ident;?> .playNext_<?php echo $ident;?>').on('click', function () {
            next_vid_<?php  echo $ident?> = true;
            used_track_<?php  echo $ident?>[used_track_<?php  echo $ident?>.length] = vid_num_<?php  echo $ident?>;
            vid_num_<?php  echo $ident?>++;
            if (used_track_<?php  echo $ident?>.length >= video_urls_<?php  echo $ident?>.length) {
                // reset old list
                used_track_<?php  echo $ident?> = [];
                if (jQuery('#shuffle_<?php  echo $ident?>').val() == 1) {
// get new vido number out of used_tracks
                    vid_num_<?php  echo $ident?> = parseInt(Math.random() * (video_urls_<?php  echo $ident?>.length - 0) + 0);
                    while (in_array(vid_num_<?php  echo $ident?>, used_track_<?php  echo $ident?>)) {
                        vid_num_<?php  echo $ident?> = parseInt(Math.random() * (video_urls_<?php  echo $ident?>.length - 0) + 0);
                    }
                }
            }
            if (jQuery('#shuffle_<?php  echo $ident?>').val() == 1) {
// get new vido number out of used_tracks
                vid_num_<?php  echo $ident?> = parseInt(Math.random() * (video_urls_<?php  echo $ident?>.length - 0) + 0);
                while (in_array(vid_num_<?php  echo $ident?>, used_track_<?php  echo $ident?>)) {
                    vid_num_<?php  echo $ident?> = parseInt(Math.random() * (video_urls_<?php  echo $ident?>.length - 0) + 0);
                }
            }
            jQuery('#global_body_<?php echo $ident;?> .timeBar_<?php  echo $ident?>').css('width', '0%');
            if (vid_num_<?php  echo $ident?> == video_urls_<?php  echo $ident?>.length) {
                vid_num_<?php  echo $ident?> = 0;
            }
            video_urls_<?php  echo $ident?>[vid_num_<?php  echo $ident?>].click();
        });
        jQuery(".lib_<?php  echo $ident?>").click(function () {
            jQuery('#album_div_<?php  echo $ident?>').css('transform', '');
            jQuery('#global_body_<?php  echo $ident?>').css('transform', '');
            jQuery('#global_body_<?php  echo $ident?>').transition({
                perspective: '700px',
                rotateY: '180deg',
            }, 1000);
            setTimeout(function () {
                jQuery('#album_div_<?php  echo $ident?>').css('-ms-transform', 'rotateY(180deg)')
                jQuery('#album_div_<?php  echo $ident?>').css('transform', 'rotateY(180deg)')
                jQuery('#album_div_<?php  echo $ident?>').css('-o-transform', 'rotateY(180deg)')
                document.getElementById('album_div_<?php  echo $ident?>').style.display = 'block'
                document.getElementById('video_div_<?php  echo $ident?>').style.display = 'none'
            }, 300);
            setTimeout(function () {
                jQuery('#album_div_<?php  echo $ident?>').css('-ms-transform', '');
                jQuery('#global_body_<?php  echo $ident?>').css('-ms-transform', '');
                jQuery('#album_div_<?php  echo $ident?>').css('transform', '');
                jQuery('#global_body_<?php  echo $ident?>').css('transform', '');
                jQuery('#album_div_<?php  echo $ident?>').css('-o-transform', '');
                jQuery('#global_body_<?php  echo $ident?>').css('-o-transform', '');
            }, 1100);
        })
        jQuery(".show_vid_<?php  echo $ident?>").click(function () {
            jQuery('#global_body_<?php  echo $ident?>').transition({
                perspective: '700px',
                rotateY: '180deg',
            }, 1000);
            setTimeout(function () {
                jQuery('#video_div_<?php  echo $ident?>').css('-ms-transform', 'rotateY(180deg)')
                jQuery('#video_div_<?php  echo $ident?>').css('transform', 'rotateY(180deg)')
                jQuery('#video_div_<?php  echo $ident?>').css('-o-transform', 'rotateY(180deg)')
                document.getElementById('album_div_<?php  echo $ident?>').style.display = 'none'
                document.getElementById('video_div_<?php  echo $ident?>').style.display = 'block'
            }, 300);
            setTimeout(function () {
                jQuery('#video_div_<?php  echo $ident?>').css('-ms-transform', '');
                jQuery('#global_body_<?php  echo $ident?>').css('-ms-transform', '');
                jQuery('#video_div_<?php  echo $ident?>').css('transform', '');
                jQuery('#global_body_<?php  echo $ident?>').css('transform', '');
                jQuery('#video_div_<?php  echo $ident?>').css('-o-transform', '');
                jQuery('#global_body_<?php  echo $ident?>').css('-o-transform', '');
            }, 1100);
        })
        var canvas_<?php  echo $ident?> = []
        var ctx_<?php  echo $ident?> = []
        var originalPixels_<?php  echo $ident?> = []
        var currentPixels_<?php  echo $ident?> = []
        for (i = 1; i < 30; i++)
            if (document.getElementById('button' + i + '_<?php  echo $ident?>')) {
                canvas_<?php  echo $ident?>[i] = document.createElement("canvas");
                ctx_<?php  echo $ident?>[i] = canvas_<?php  echo $ident?>[i].getContext("2d");
                originalPixels_<?php  echo $ident?>[i] = null;
                currentPixels_<?php  echo $ident?>[i] = null;
            }
        function getPixels_<?php  echo $ident?>() {
            for (i = 1; i < 30; i++)
                if (document.getElementById('button' + i + '_<?php  echo $ident?>')) {
                    img = document.getElementById('button' + i + '_<?php  echo $ident?>');
                    canvas_<?php  echo $ident?>[i].width = img.width;
                    canvas_<?php  echo $ident?>[i].height = img.height;
                    ctx_<?php  echo $ident?>[i].drawImage(img, 0, 0, img.naturalWidth, img.naturalHeight, 0, 0, img.width, img.height);
                    originalPixels_<?php  echo $ident?>[i] = ctx_<?php  echo $ident?>[i].getImageData(0, 0, img.width, img.height);
                    currentPixels_<?php  echo $ident?>[i] = ctx_<?php  echo $ident?>[i].getImageData(0, 0, img.width, img.height);
                    img.onload = null;
                }
        }
        function HexToRGB_<?php  echo $ident?>(Hex) {
            var Long = parseInt(Hex.replace(/^#/, ""), 16);
            return {
                R: (Long >>> 16) & 0xff,
                G: (Long >>> 8) & 0xff,
                B: Long & 0xff
            };
        }
        function changeColor_<?php  echo $ident?>() {
            for (i = 1; i < 30; i++)
                if (document.getElementById('button' + i + '_<?php  echo $ident?>')) {
                    if (!originalPixels_<?php  echo $ident?>[i]) return; // Check if image has loaded
                    var newColor = HexToRGB_<?php  echo $ident?>(document.getElementById("color_<?php echo $ident;?>").value);
                    for (var I = 0, L = originalPixels_<?php  echo $ident?>[i].data.length; I < L; I += 4) {
                        if (currentPixels_<?php  echo $ident?>[i].data[I + 3] > 0) {
                            currentPixels_<?php  echo $ident?>[i].data[I] = originalPixels_<?php  echo $ident?>[i].data[I] / 255 * newColor.R;
                            currentPixels_<?php  echo $ident?>[i].data[I + 1] = originalPixels_<?php  echo $ident?>[i].data[I + 1] / 255 * newColor.G;
                            currentPixels_<?php  echo $ident?>[i].data[I + 2] = originalPixels_<?php  echo $ident?>[i].data[I + 2] / 255 * newColor.B;
                        }
                    }
                    ctx_<?php  echo $ident?>[i].putImageData(currentPixels_<?php  echo $ident?>[i], 0, 0);
                    img = document.getElementById('button' + i + '_<?php  echo $ident?>');
                    img.src = canvas_<?php  echo $ident?>[i].toDataURL("image/png");
                }
        }
        <?php if($theme->spaceOnVid==1) { ?>
        var video_focus;
        jQuery('#global_body_<?php  echo $ident?> ,#videoID_<?php  echo $ident?>').each(function () {
            jQuery(this).live('click', function () {
                setTimeout("video_focus=1", 100)
            })
        })
        jQuery('body').live('click', function () {
            video_focus = 0
        })
        jQuery(window).keypress(function (event) {
            if(!is_youtube_video_<?php echo $ident ?>()){
        if (video_<?php echo $ident;?>[0].paused) {
                video_<?php echo $ident;?>[0].play();
                paly_<?php echo $ident;?>.css('display', "none");
                pause_<?php echo $ident;?>.css('display', "");
            }
            else {
                video_<?php echo $ident;?>[0].pause();
                paly_<?php echo $ident;?>.css('display', "");
                pause_<?php echo $ident;?>.css('display', "none");
            }
        
            }else{
                if(!check_play_<?php echo $ident;?>){
                     if(typeof player_<?php echo $ident;?> != 'undefined'){if(youtube_ready_<?php echo $ident ?>)player_<?php echo $ident ?>.playVideo();}
                   check_play_<?php echo $ident;?> = true;
                    paly_<?php echo $ident;?>.css('display', "none");
                pause_<?php echo $ident;?>.css('display', "");
                }else{
                     if(typeof player_<?php echo $ident;?> != 'undefined')player_<?php echo $ident;?>.pauseVideo();
                     check_play_<?php echo $ident;?> = false;
                      paly_<?php echo $ident;?>.css('display', "");
                pause_<?php echo $ident;?>.css('display', "none");
                }
            }
            return false;
        });
        <?php }?>
        function vidOnSpace_<?php echo $ident;?>() {
            if (video_<?php echo $ident;?>[0].paused) {
                video_<?php echo $ident;?>[0].play();
                paly_<?php echo $ident;?>.css('display', "none");
                pause_<?php echo $ident;?>.css('display', "");
            }
            else {
                video_<?php echo $ident;?>[0].pause();
                paly_<?php echo $ident;?>.css('display', "");
                pause_<?php echo $ident;?>.css('display', "none");
            }
        }
        jQuery('#track_list_<?php  echo $ident?>_0').find('#thumb_0_<?php echo $ident?>').click();
        if(!is_youtube_video_<?php echo $ident ?>())
        video_<?php echo $ident;?>[0].pause();
        else
                if(typeof player_<?php echo $ident;?> != 'undefined')player_<?php echo $ident;?>.pauseVideo();            
        if (paly_<?php echo $ident;?> && pause_<?php echo $ident;?>) {
            paly_<?php echo $ident;?>.css('display', "");
            pause_<?php echo $ident;?>.css('display', "none");
        }
        <?php if($AlbumId!=''){ ?>
        jQuery('#track_list_<?php  echo $ident?>_<?php echo $AlbumId ?>').find('#thumb_<?php echo $TrackId ?>_<?php echo $ident?>').click();
        <?php } ?>
        jQuery(window).load(function () {
            getPixels_<?php  echo $ident?>();
            changeColor_<?php  echo $ident?>()
        })
        jQuery('.volume_<?php  echo $ident?>')[0].style.width = '<?php echo $theme->defaultVol?>%';
        video_<?php echo $ident;?>[0].volume =<?php echo $theme->defaultVol/100 ;?>;
        </script>
    <?php
    if ($theme->openPlaylistAtStart)
        echo "<script>show_hide_playlist();</script>";
    }
    ?>
    </div><br/>
    <?php
    global $many_players;
    $many_players++;
    $ident++;
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

/* add editor new mce button */
add_filter('mce_external_plugins', "Spider_Video_Player_register");
add_filter('mce_buttons', 'Spider_Video_Player_add_button', 0);

/* function for add new button */
function Spider_Video_Player_add_button($buttons) {
    array_push($buttons, "Spider_Video_Player_mce");
    return $buttons;
}

/* function for registr new button */
function Spider_Video_Player_register($plugin_array){
    $url = plugins_url('js/editor_plugin.js', __FILE__);
    $plugin_array["Spider_Video_Player_mce"] = $url;
    return $plugin_array;
}

function add_button_style_Spider_Video_Player(){
    echo '<script>var svp_plugin_url = "' .plugins_url('', __FILE__) .'";</script>';
}
add_action('admin_head', 'add_button_style_Spider_Video_Player');

/* actions for popup and xmls */
require_once('functions_for_xml_and_ajax.php');
add_action('wp_ajax_spiderVeideoPlayerPrewieve', 'spider_Veideo_Player_Prewieve');
add_action('wp_ajax_spiderVeideoPlayerpreviewsettings', 'spider_video_preview_settings');
add_action('wp_ajax_spiderVeideoPlayerpreviewplaylist', 'spider_video_preview_playlist');
add_action('wp_ajax_spiderVeideoPlayerselectplaylist', 'spider_video_select_playlist');
add_action('wp_ajax_spiderVeideoPlayerselectvideo', 'spider_video_select_video');
add_action('wp_ajax_spiderVeideoPlayersettingsxml', 'generete_sp_video_settings_xml');
add_action('wp_ajax_spiderVeideoPlayerplaylistxml', 'generete_sp_video_playlist_xml');
add_action('wp_ajax_spiderVeideoPlayervideoonly', 'viewe_sp_video_only');


/* ajax for users */
add_action('wp_ajax_nopriv_spiderVeideoPlayervideoonly', 'viewe_sp_video_only');
add_action('wp_ajax_nopriv_spiderVeideoPlayersettingsxml', 'generete_sp_video_settings_xml');
add_action('wp_ajax_nopriv_spiderVeideoPlayerplaylistxml', 'generete_sp_video_playlist_xml');
add_action('admin_menu', 'Spider_Video_Player_options_panel');


add_action('wp_ajax_spider_categories_shortcode', 'spider_categories_shortcode');
function spider_categories_shortcode(){
	$path  = '';
	if ( !defined('WP_LOAD_PATH') ) {
		/** classic root path if wp-content and plugins is below wp-config.php */
		$classic_root = dirname(dirname(dirname(dirname(__FILE__)))) . '/' ;

		if (file_exists( $classic_root . 'wp-load.php') )
			define( 'WP_LOAD_PATH', $classic_root);
		else
		if (file_exists( $path . 'wp-load.php') )
			define( 'WP_LOAD_PATH', $path);
		else
			exit("Could not find wp-load.php");
	}
	// let's load WordPress
	require_once( WP_LOAD_PATH . 'wp-load.php');
	global $wpdb; ?>
	<div class="tabs" role="tablist" tabindex="-1">
		<center><h3>Single Video</h3></center>
	</div>
	<style>			
	#spider_single_panel.gut table label.optTitle { 
		font-size:16px;
		font-style:italic;
	}
	#spider_single_panel.gut #Spider_cat_Category { padding:8px; }			
	
	.mceActionPanel.gut #insert {
		background-color: #329832;
		padding: 10px 15px;
		color: #ffffff;
		border: none;
	}
	.mceActionPanel.gut #insert:hover { background-color: #329838; }
	.mceActionPanel.gut #insert:focus { outline:none; }
	</style>
	<div class="panel_wrapper">
		<div id="spider_single_panel" class="panel gut">
			<table border="0" cellspacing="15">
				<tr>
					<td><label class="optTitle">Select a Video</label></td>
					<td>
						<select name="Spider_Single_Videoname" id="Spider_Single_Videoname" style="width:200px;" >
							<option value="- Select Video -" selected="selected">- Select -</option>
							<?php $ids_Spider_Single_Video=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_video order by title",0);
							foreach($ids_Spider_Single_Video as $arr_Spider_Single_Video){ ?>
								<option value="<?php echo $arr_Spider_Single_Video->id; ?>"><?php echo $arr_Spider_Single_Video->title; ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td><label class="optTitle">Select a Theme</label></td>
					<td>
						<select name="Spider_Video_Theme" id="Spider_Video_Theme" style="width:200px;" >
							<option value="- Select Theme -" selected="selected"> - Select -</option>
							<?php  $ids_Spider_Video_Theme=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_theme order by title",0);
							foreach($ids_Spider_Video_Theme as $arr_Spider_Video_Theme) { ?>
								<option value="<?php echo $arr_Spider_Video_Theme->id; ?>"><?php echo $arr_Spider_Video_Theme->title; ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td><label class="optTitle">Priority</label></td>
					<td>
						<input type="radio" name="priority" id="flash" value="0" checked="checked">
							<label for="flash">Flash</label>
						<input type="radio" name="priority" style="margin-left: 12px;" id="html5" value="1">
							<label for="html5">HTML5</label>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="mceActionPanel gut">
		<div style="text-align:center">
			<input type="submit" id="insert" name="insert" value="Insert" onClick="insert_Spider_Video_Player();" />
		</div>
	</div>
	<script type="text/javascript">
	function insert_Spider_Video_Player() {
		if((document.getElementById('Spider_Single_Videoname').value=='- Select Video -') || (document.getElementById('Spider_Video_Theme').value=='- Select Theme -')) {
			tinyMCEPopup.close();
		}
		else
		{ 
			var priority;
			priority=1;
			if(!document.getElementById('flash').checked) {
				priority=1;
			}
			var tagtext;
			tagtext='[Spider_Single_Video track="'+document.getElementById('Spider_Single_Videoname').value+'" theme_id="'+document.getElementById('Spider_Video_Theme').value+'" priority="'+priority+'"]';      
			if (window.parent.window['wdg_cb_tw/spider-video-categories']) {
				window.parent['wdg_cb_tw/spider-video-categories'](tagtext, 0);
				return;
			}else {
				window.parent.send_to_editor( tagtext );
				window.parent.tb_remove();
			}
		}
	}
	</script>
<?php
   die();
}


function Spider_Video_Player_options_panel() {
    add_menu_page('Theme page title', 'Video Player', 'manage_options', 'Spider_Video_Player', 'Spider_Video_Player_player');
    add_submenu_page('Spider_Video_Player', 'Player', 'Video Player', 'manage_options', 'Spider_Video_Player', 'Spider_Video_Player_player');
    add_submenu_page('Spider_Video_Player', 'Tags', 'Tags', 'manage_options', 'Tags_Spider_Video_Player', 'Tags_Spider_Video_Player');
    add_submenu_page('Spider_Video_Player', 'Videos', 'Videos', 'manage_options', 'Spider_Video_Player_Videos', 'Spider_Video_Player_Videos');
    add_submenu_page('Spider_Video_Player', 'Playlists', 'Playlists', 'manage_options', 'Spider_Video_Player_Playlists', 'Spider_Video_Player_Playlists');
    $page_theme = add_submenu_page('Spider_Video_Player', 'Themes', 'Themes', 'manage_options', 'Spider_Video_Player_Themes', 'Spider_Video_Player_Themes');
    add_submenu_page('Spider_Video_Player', 'Uninstall Spider_Video_Player ', 'Uninstall  Video Player', 'manage_options', 'Uninstall_Spider_Video_Player', 'Uninstall_Spider_Video_Player');
    add_action('admin_print_styles-' .$page_theme, 'sp_video_player_admin_styles_scripts');
}


function sp_video_player_admin_styles_scripts($id) {
    if (get_bloginfo('version') > 3.3) {
        wp_enqueue_script("jquery");
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script("jquery-ui-widget");
        wp_enqueue_script("jquery-ui-mouse");
        wp_enqueue_script("jquery-ui-slider");
        wp_enqueue_script("jquery-ui-sortable");
    } else {
        wp_deregister_script('jquery');
        wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js');
        wp_enqueue_script('jquery');
        wp_deregister_script('jquery-ui-slider');
        wp_register_script('jquery-ui-slider', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js');
        wp_enqueue_script('jquery-ui-slider');
    }
    wp_enqueue_script("mootols", plugins_url('elements/mootools.js', __FILE__));
    wp_enqueue_script("modal", plugins_url('elements/modal.js', __FILE__));
    wp_enqueue_script("colcor_js", plugins_url('jscolor/jscolor.js', __FILE__));
    wp_enqueue_style("jqueri_ui_css", plugins_url('elements/jquery-ui.css', __FILE__));
    wp_enqueue_style("parsetheme_css", plugins_url('elements/parseTheme.css', __FILE__));
}


/* TAGS */
require_once("nav_function/nav_html_func.php");
add_filter('admin_head', 'ShowTinyMCE');
function ShowTinyMCE($id) {
    // conditions here
    wp_enqueue_script('common');
    wp_enqueue_script('jquery-color');
    wp_print_scripts('editor');
    if (function_exists('add_thickbox')) add_thickbox();
    wp_print_scripts('media-upload');
    if (version_compare(get_bloginfo('version'), 3.3) < 0) {
        if (function_exists('wp_tiny_mce')) wp_tiny_mce();
    }
    wp_admin_css();
    wp_enqueue_script('utils');
    do_action("admin_print_styles-post-php");
    do_action('admin_print_styles');
}


function Spider_Video_Player_player() {
    global $wpdb;
    $url = $wpdb->get_results("SELECT urlHdHtml5,urlHtml5 FROM " .$wpdb->prefix ."Spider_Video_Player_video");
    if (!$url) {
        $wpdb->query("ALTER TABLE " .$wpdb->prefix ."Spider_Video_Player_video  ADD urlHdHtml5 varchar(255) AFTER thumb, ADD urlHtml5 varchar(255) AFTER urlHD;");
        $wpdb->query("ALTER TABLE " .$wpdb->prefix ."Spider_Video_Player_player  ADD priority varchar(255) AFTER title;");
    }
    require_once("Spider_Video_Player_functions.php"); // add functions for player
    require_once("Spider_Video_Player_functions.html.php"); // add functions for vive player
    wp_enqueue_script('common');
    wp_enqueue_script('jquery-color');
    wp_print_scripts('editor');
    
	if (function_exists('add_thickbox')) add_thickbox();
		wp_print_scripts('media-upload');
    
	if (version_compare(get_bloginfo('version'), 3.3) < 0) {
        if (function_exists('wp_tiny_mce')) wp_tiny_mce();
    }
    wp_admin_css();
    wp_enqueue_script('utils');
    do_action("admin_print_styles-post-php");
    do_action('admin_print_styles');
    
	if (isset($_GET["task"])) {
        $task = htmlspecialchars($_GET["task"]);
    } else {
        $task = "default";
    }
    if (isset($_GET["id"])) {
        $id = htmlspecialchars($_GET["id"]);
    } else {
        $id = 0;
    }
    switch ($task) {
        case 'Spider_Video_Player':
            show_Spider_Video_Player();
            break;
        case "unpublish_Spider_Video_Player":
            check_admin_referer('nonce_sp_vid', 'nonce_sp_vid');
			change_tag($id);
            show_Spider_Video_Player();
            break;
        case 'add_Spider_Video_Player':
            add_Spider_Video_Player();
            break;
        case 'Save':
            if ($id) {
			    check_admin_referer('nonce_sp_vid', 'nonce_sp_vid');
                Apply_Spider_Video_Player($id);
            } else {
			    check_admin_referer('nonce_sp_vid', 'nonce_sp_vid');
                save_Spider_Video_Player();
            }
            show_Spider_Video_Player();
            break;
        case 'Apply':
            if ($id == 0) {
				check_admin_referer('nonce_sp_vid', 'nonce_sp_vid');
                $save_or_no = save_Spider_Video_Player();			
			}
            else {
				check_admin_referer('nonce_sp_vid', 'nonce_sp_vid');
                $save_or_no = Apply_Spider_Video_Player($id);
			}
            if ($save_or_no) {
                add_Spider_Video_Player();
            } else {
                show_Spider_Video_Player();
            }
            break;
        case 'edit_Spider_Video_Player':
            add_Spider_Video_Player();
            break;
        case 'remove_Spider_Video_Player':
			$nonce_sp_vid = $_REQUEST['_wpnonce'];
			if (! wp_verify_nonce($nonce_sp_vid, 'nonce_sp_vid') )
			  die("Are you sure you want to do this?");
            remove_Spider_Video_Player($id);
            show_Spider_Video_Player();
            break;
        case 'select_Spider_Video_Player':
            select_Spider_Video_Player();
            break;
        default:
            show_Spider_Video_Player();
            break;
    }
}


function Tags_Spider_Video_Player() {
    global $wpdb;
    require_once("tag_functions.php"); // add functions for Spider_Video_Player
    require_once("tag_function.html.php"); // add functions for vive Spider_Video_Player 
    if (isset($_GET["task"])) {
        $task = htmlspecialchars($_GET["task"]);
    } else {
        $task = "default";
    }
    if (isset($_GET["id"])) {
        $id = htmlspecialchars($_GET["id"]);
    } else {
        $id = 0;
    }
    switch ($task) {
        case 'tag':
            show_tag();
            break;
        case 'add_tag':
            add_tag();
            break;
        case 'cancel_tag';
            cancel_tag();
            break;
        case 'apply_tag':
            if ($id == 0) {
				check_admin_referer('nonce_sp_vid', 'nonce_sp_vid');
                $save_or_no = save_tag();
			}
            else {
				check_admin_referer('nonce_sp_vid', 'nonce_sp_vid');
                $save_or_no = apply_tag($id);
			}
            if ($save_or_no) {
                edit_tag($id);
            } else {
                show_tag();
            }
            break;
        case 'save_tag':
            if (!$id) {
				check_admin_referer('nonce_sp_vid', 'nonce_sp_vid');
                save_tag();
            } else {
				check_admin_referer('nonce_sp_vid', 'nonce_sp_vid');
                apply_tag($id);
            }
            show_tag();
            break;
        case 'saveorder':
		    check_admin_referer('nonce_sp_vid', 'nonce_sp_vid');
            saveorder();
            break;
        case 'orderup' :
            ordertag(-1);
            break;
        case 'orderdown' :
            ordertag(1);
            break;
        case 'edit_tag':
            edit_tag($id);
            break;
        case 'remove_tag':
		    $nonce_sp_vid = $_REQUEST['_wpnonce'];
			if (! wp_verify_nonce($nonce_sp_vid, 'nonce_sp_vid') )
			  die("Are you sure you want to do this?");
            remove_tag($id);
            show_tag();
            break;
        case 'publish_tag':
            $nonce_sp_vid = $_REQUEST['_wpnonce'];
			if (! wp_verify_nonce($nonce_sp_vid, 'nonce_sp_vid') )
			  die("Are you sure you want to do this?");
			change_tag($id);
            show_tag();
            break;
        case 'unpublish_tag':
            $nonce_sp_vid = $_REQUEST['_wpnonce'];
			if (! wp_verify_nonce($nonce_sp_vid, 'nonce_sp_vid') )
			  die("Are you sure you want to do this?");
			change_tag($id);
            show_tag();
            break;
        case 'required_tag':
            $nonce_sp_vid = $_REQUEST['_wpnonce'];
			if (! wp_verify_nonce($nonce_sp_vid, 'nonce_sp_vid') )
			  die("Are you sure you want to do this?");
			required_tag($id);
            show_tag();
            break;
        case 'unrequired_tag':
            $nonce_sp_vid = $_REQUEST['_wpnonce'];
			if (! wp_verify_nonce($nonce_sp_vid, 'nonce_sp_vid') )
			  die("Are you sure you want to do this?");
			required_tag($id);
            show_tag();
            break;
        default:
            show_tag();
            break;
    }
}


/* VIDEOS */
function Spider_Video_Player_Videos() {
    wp_enqueue_script('media-upload');
    wp_admin_css('thickbox');
    require_once("video_functions.php"); // add functions for Spider_Video_Player
    require_once("video_function.html.php"); // add functions for vive Spider_Video_Player

    if (isset($_GET["task"])) {
        $task = htmlspecialchars($_GET["task"]);
    } else {
        $task = "default";
    }
    if (isset($_GET["id"])) {
        $id = htmlspecialchars($_GET["id"]);
    } else {
        $id = 0;
    }
    switch ($task) {
        case 'video':
            show_video();
            break;
        case 'add_video':
            add_video();
            break;
        case 'published';
            $nonce_sp_vid = $_REQUEST['_wpnonce'];
			if (! wp_verify_nonce($nonce_sp_vid, 'nonce_sp_vid') )
			  die("Are you sure you want to do this?");
			published($id);
            show_video();
            break;
        case 'Save':
            if (!$id) {
			    check_admin_referer('nonce_sp_vid', 'nonce_sp_vid');
                save_video();
            } else {
			    check_admin_referer('nonce_sp_vid', 'nonce_sp_vid');
                apply_video($id);
            }
            show_video();
            break;
        case 'Apply':
            if (!$id) {
			    check_admin_referer('nonce_sp_vid', 'nonce_sp_vid');
                save_video();
            } else {
			    check_admin_referer('nonce_sp_vid', 'nonce_sp_vid');
                apply_video($id);
            }
            edit_video($id);
            break;
        case 'edit_video':
            edit_video($id);
            break;
        case 'remove_video':
            $nonce_sp_vid = $_REQUEST['_wpnonce'];
			if (! wp_verify_nonce($nonce_sp_vid, 'nonce_sp_vid') )
			  die("Are you sure you want to do this?");
			remove_video($id);
            show_video();
            break;
        case 'publish_video':
            $nonce_sp_vid = $_REQUEST['_wpnonce'];
			if (! wp_verify_nonce($nonce_sp_vid, 'nonce_sp_vid') )
			  die("Are you sure you want to do this?");
			change_video(1);
            break;
        case 'unpublish_video':
            $nonce_sp_vid = $_REQUEST['_wpnonce'];
			if (! wp_verify_nonce($nonce_sp_vid, 'nonce_sp_vid') )
			  die("Are you sure you want to do this?");
			change_video(0);
            break;
        default:
            show_video();
            break;
    }
}


/* PLAYLISTS */
function Spider_Video_Player_Playlists() {
    require_once("Playlist_functions.php"); // add functions for Spider_Video_Player
    require_once("Playlists_function.html.php"); // add functions for vive Spider_Video_Player	
    if (isset($_GET["task"])) {
        $task = htmlspecialchars($_GET["task"]);
    } else {
        $task = "default";
    }
    if (isset($_GET["id"])) {
        $id = htmlspecialchars($_GET["id"]);
    } else {
        $id = 0;
    }
    switch ($task) {
        case 'playlist':
            show_playlist();
            break;
        case "unpublish_playlist":
            $nonce_sp_vid = $_REQUEST['_wpnonce'];
			if (! wp_verify_nonce($nonce_sp_vid, 'nonce_sp_vid') )
			  die("Are you sure you want to do this?");
			change_tag($id);
            show_playlist();
            break;
        case 'add_playlist':
            add_playlist();
            break;
        case 'cancel_playlist';
            cancel_playlist();
            break;
        case 'Save':
            if ($id) {
				check_admin_referer('nonce_sp_vid', 'nonce_sp_vid');
                Apply_playlist($id);
            } else {
				check_admin_referer('nonce_sp_vid', 'nonce_sp_vid');
                save_playlist();
            }
            show_playlist();
            break;
        case 'Apply':
             if ($id == 0) {
                check_admin_referer('nonce_sp_vid', 'nonce_sp_vid');
				$save_or_no = save_playlist();
			}
            else {
                check_admin_referer('nonce_sp_vid', 'nonce_sp_vid');
				$save_or_no = Apply_playlist($id);
			}
            if ($save_or_no) {
                edit_playlist($id);
            } else {
                show_playlist();
            }
            break;
        case 'edit_playlist':
            edit_playlist($id);
            break;
        case 'remove_playlist':
            $nonce_sp_vid = $_REQUEST['_wpnonce'];
			if (! wp_verify_nonce($nonce_sp_vid, 'nonce_sp_vid') )
			  die("Are you sure you want to do this?");
			remove_playlist($id);
            show_playlist();
            break;
        case 'select_playlist':
            select_playlist();
            break;
        default:
            show_playlist();
            break;
    }
}


/* THEMS */
function Spider_Video_Player_Themes() {
    wp_enqueue_script('media-upload');
    wp_admin_css('thickbox');
    require_once("Theme_functions.php"); // add functions for Spider_Video_Player
    require_once("Themes_function.html.php"); // add functions for vive Spider_Video_Player
    if (isset($_GET["task"])) {
        $task = htmlspecialchars($_GET["task"]);
    } else {
        $task = "";
    }
    if (isset($_GET["id"])) {
        $id = htmlspecialchars($_GET["id"]);
    } else {
        $id = 0;
    }
    switch ($task) {
        case 'theme':
            show_theme();
            break;
        case 'default':
            $nonce_sp_vid = $_REQUEST['_wpnonce'];
			if (! wp_verify_nonce($nonce_sp_vid, 'nonce_sp_vid') )
			  die("Are you sure you want to do this?");
			default_theme($id);
            show_theme();
            break;
        case 'add_theme':
            add_theme();
            break;
        case 'Save':
             if ($id) {
				check_admin_referer('nonce_sp_vid', 'nonce_sp_vid');
                apply_theme($id);
            } else {
				check_admin_referer('nonce_sp_vid', 'nonce_sp_vid');
                save_theme();
            }
            show_theme();
            break;
        case 'Apply':
            if ($id) {
				check_admin_referer('nonce_sp_vid', 'nonce_sp_vid');
                apply_theme($id);
            } else {
				check_admin_referer('nonce_sp_vid', 'nonce_sp_vid');
                save_theme();
            }
            edit_theme($id);
            break;
        case 'edit_theme':
            edit_theme($id);
            break;
        case 'remove_theme':
            $nonce_sp_vid = $_REQUEST['_wpnonce'];
			if (! wp_verify_nonce($nonce_sp_vid, 'nonce_sp_vid') )
			  die("Are you sure you want to do this?");
			remove_theme($id);
            show_theme();
            break;
        default:
            show_theme();
    }
}


function Uninstall_Spider_Video_Player() {
    global $wpdb;
    $base_name = plugin_basename('Spider_Video_Player');
    $base_page = 'admin.php?page=' .$base_name;
    if (isset($_GET['mode']))
        $mode = trim(htmlspecialchars($_GET['mode']));
    else
        $mode = '';
    if (!empty($_POST['do'])) {
        if ($_POST['do'] == "UNINSTALL Spider_Video_Player") {
            check_admin_referer('Spider_Video_Player uninstall');
            if (trim($_POST['Spider_Video_Player_yes']) == 'yes') {
                echo '<div id="message" class="updated fade">';
                echo '<p>';
                echo "Table 'Spider_Video_Player_tag' has been deleted.";
                $wpdb->query("DROP TABLE " .$wpdb->prefix ."Spider_Video_Player_playlist");
                echo '<font style="color:#000;">';
                echo '</font><br />';
                echo '</p>';
                echo '<p>';
                echo "Table 'Spider_Video_Player_theme' has been deleted.";
                $wpdb->query("DROP TABLE " .$wpdb->prefix ."Spider_Video_Player_tag");
                echo '<font style="color:#000;">';
                echo '</font><br />';
                echo '</p>';
                echo "Table 'Spider_Video_Player_video' has been deleted.";
                $wpdb->query("DROP TABLE " .$wpdb->prefix ."Spider_Video_Player_theme");
                echo '<font style="color:#000;">';
                echo '</font><br />';
                echo '</p>';
                echo "Table 'Spider_Video_Player_playlist' has been deleted.";
                $wpdb->query("DROP TABLE " .$wpdb->prefix ."Spider_Video_Player_video");
                echo '<font style="color:#000;">';
                echo '</font><br />';
                echo '</p>';
                echo "Table 'Spider_Video_Player_player' has been deleted.";
                $wpdb->query("DROP TABLE " .$wpdb->prefix ."Spider_Video_Player_player");
                echo '<font style="color:#000;">';
                echo '</font><br />';
                echo '</p>';
                echo '</div>';
                $mode = 'end-UNINSTALL';
            }
        }
    }
    switch ($mode) {
        case 'end-UNINSTALL':
            $deactivate_url = wp_nonce_url('plugins.php?action=deactivate&amp;plugin=' .plugin_basename(__FILE__), 'deactivate-plugin_' .plugin_basename(__FILE__));
            echo '<div class="wrap">';
            echo '<h2>Uninstall Spider Video Player</h2>';
            echo '<p><strong>' .sprintf('<a href="%s">Click Here</a> To Finish The Uninstallation And Spider Video Player Will Be Deactivated Automatically.', $deactivate_url) .'</strong></p>';
            echo '</div>';
		break;
        // Main Page
        default: ?>
		<form method="post" action="<?php echo admin_url('admin.php?page=Uninstall_Spider_Video_Player'); ?>">
			<?php wp_nonce_field('Spider_Video_Player uninstall'); ?>
			<div class="wrap">
				<div id="icon-Spider_Video_Player" class="icon32"><br/></div>
				<h2><?php echo 'Uninstall Spider Video Player'; ?></h2>
				<p><?php echo 'Deactivating Spider Video Player plugin does not remove any data that may have been created.To completely remove this plugin, you can uninstall it here.'; ?></p>
				<p style="color: red"><strong><?php echo 'WARNING:'; ?></strong><br/><?php echo 'Once uninstalled, this cannot be undone.You should use a Database Backup plugin of WordPress to back up all the data first.'; ?>
				</p>
				<p style="color: red"><strong><?php echo 'The following WordPress Options/Tables will be DELETED:'; ?></strong></p>
				<table class="widefat">
					<thead>
						<tr>
							<th><?php echo 'WordPress Tables'; ?></th>
						</tr>
					</thead>
					<tr>
						<td valign="top">
							<ol>
								<?php
								echo '<li>Spider_Video_Player_playlist</li>' ."\n";
								echo '<li>Spider_Video_Player_tag</li>' ."\n";
								echo '<li>Spider_Video_Player_theme</li>' ."\n";
								echo '<li>Spider_Video_Player_video</li>' ."\n";
								echo '<li>Spider_Video_Player_player</li>' ."\n";
								?>
							</ol>
						</td>
					</tr>
				</table>
				<p style="text-align: center;">
				<?php echo 'Do you really want to uninstall Spider Video Player?'; ?><br/><br/>
				<input type="checkbox" name="Spider_Video_Player_yes" value="yes"/>&nbsp;<?php echo 'Yes'; ?><br/><br/>
				<input type="submit" name="do" value="<?php echo 'UNINSTALL Spider_Video_Player'; ?>" class="button-primary" onclick="return confirm('<?php echo 'You Are About To Uninstall Spider Video Player From WordPress.\nThis Action Is Not Reversible.\n\n Choose [Cancel] To Stop, [OK] To Uninstall.'; ?>')"/></p>
            </div>
        </form>
    <?php
    }
}


function Spider_Video_Player_activate(){
global $wpdb;

$sql_playlist = "CREATE TABLE IF NOT EXISTS `" .$wpdb->prefix ."Spider_Video_Player_playlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `thumb` varchar(200) DEFAULT NULL,
  `published` tinyint(1) unsigned DEFAULT NULL,
  `videos` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";

$sql_tag = "CREATE TABLE IF NOT EXISTS `" .$wpdb->prefix ."Spider_Video_Player_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `required` int(11) DEFAULT NULL,
  `published` int(11) unsigned DEFAULT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";

$sql_theme = "CREATE TABLE IF NOT EXISTS `" .$wpdb->prefix ."Spider_Video_Player_theme` (
`id` int(11) NOT NULL auto_increment,
  `default` int(2) NOT NULL,
  `title` varchar(256) NOT NULL,
  `appWidth` int(11) NOT NULL,
  `appHeight` int(11) NOT NULL,
  `playlistWidth` int(11) DEFAULT NULL,
  `startWithLib` tinyint(1) DEFAULT NULL,
  `autoPlay` tinyint(1) DEFAULT NULL,
  `autoNext` tinyint(1) DEFAULT NULL,
  `autoNextAlbum` tinyint(1) DEFAULT NULL,
  `defaultVol` double DEFAULT NULL,
  `defaultRepeat` varchar(20) NOT NULL,
  `defaultShuffle` varchar(20) NOT NULL,
  `autohideTime` int(11) DEFAULT NULL,
  `centerBtnAlpha` double DEFAULT NULL,
  `loadinAnimType` tinyint(4) DEFAULT NULL,
  `keepAspectRatio` tinyint(1) DEFAULT NULL,
  `clickOnVid` tinyint(1) DEFAULT NULL,
  `spaceOnVid` tinyint(1) DEFAULT NULL,
  `mouseWheel` tinyint(1) DEFAULT NULL,
  `ctrlsPos` tinyint(4) DEFAULT NULL,
  `ctrlsStack` text NOT NULL,
  `ctrlsOverVid` tinyint(1) DEFAULT NULL,
  `ctrlsSlideOut` tinyint(1) DEFAULT NULL,
  `watermarkUrl` varchar(255) DEFAULT NULL,
  `watermarkPos` tinyint(4) DEFAULT NULL,
  `watermarkSize` int(11) DEFAULT NULL,
  `watermarkSpacing` int(11) DEFAULT NULL,
  `watermarkAlpha` double DEFAULT NULL,
  `playlistPos` int(11) DEFAULT NULL,
  `playlistOverVid` tinyint(1) DEFAULT NULL,
  `playlistAutoHide` tinyint(1) DEFAULT NULL,
  `playlistTextSize` int(11) NOT NULL,
  `libCols` int(11) NOT NULL,
  `libRows` int(11) NOT NULL,
  `libListTextSize` int(11) NOT NULL,
  `libDetailsTextSize` int(11) NOT NULL,
  `appBgColor` varchar(16) NOT NULL,
  `vidBgColor` varchar(16) NOT NULL,
  `framesBgColor` varchar(16) NOT NULL,
  `ctrlsMainColor` varchar(16) NOT NULL,
  `ctrlsMainHoverColor` varchar(16) NOT NULL,
  `slideColor` varchar(16) NOT NULL,
  `itemBgHoverColor` varchar(16) NOT NULL,
  `itemBgSelectedColor` varchar(16) NOT NULL,
  `textColor` varchar(16) NOT NULL,
  `textHoverColor` varchar(16) NOT NULL,
  `textSelectedColor` varchar(16) NOT NULL,
  `framesBgAlpha` double NOT NULL,
  `ctrlsMainAlpha` double NOT NULL,
  `itemBgAlpha` double NOT NULL,
  `show_trackid` tinyint(1) DEFAULT NULL,
  `openPlaylistAtStart` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ";

$sql_video = "CREATE TABLE IF NOT EXISTS `" .$wpdb->prefix ."Spider_Video_Player_video` (
  `id` int(11) NOT NULL auto_increment,
  `url` varchar(200) NOT NULL,
  `urlHtml5` varchar(200) DEFAULT NULL,
  `urlHD` varchar(200) DEFAULT NULL,
  `urlHDHtml5` varchar(200) DEFAULT NULL,
  `thumb` varchar(200) DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `published` int(11) unsigned DEFAULT NULL,
  `type` varchar(20) NOT NULL,
  `fmsUrl` varchar(256) DEFAULT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";

$sql_Spider_Video_Player = "CREATE TABLE IF NOT EXISTS `" .$wpdb->prefix ."Spider_Video_Player_player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(600) NOT NULL,
  `playlist` varchar(800) NOT NULL,
  `theme` int(11) NOT NULL,
  `priority` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";

$table_name = $wpdb->prefix ."Spider_Video_Player_theme";

$sql_theme1 = "INSERT INTO `" .$table_name ."` VALUES(1, 1, 'Theme 1', 650, 400, 100, 0, 1, 0, 0, 50, 'repeatOff', 'shuffleOff', 5, 50, 2, 1, 1, 1, 1, 2, 'playlist:1,playPrev:1,playPause:1,playNext:1,lib:1,stop:0,time:1,vol:1,+:1,hd:1,repeat:1,shuffle:1,play:0,pause:0,share:1,fullScreen:1', 1, 0, '', 1, 0, 0, 50, 1, 1, 1, 12, 3, 3, 16, 20, '001326', '001326', '3665A3', 'C0B8F2', '000000', '00A2FF', 'DAE858', '0C8A58', 'DEDEDE', '000000', 'FFFFFF', 50, 79, 50, 1, 0)";

$sql_theme2 = "INSERT INTO `" .$table_name ."` VALUES(2, 0, 'Theme 2', 650, 400, 60, 0, 0, 0, 0, 50, 'repeatOff', 'shuffleOff', 5, 50, 2, 1, 1, 1, 1, 2, 'playPrev:1,playPause:1,playNext:1,stop:0,playlist:1,lib:1,play:0,vol:1,+:1,time:1,hd:1,repeat:1,shuffle:1,pause:0,share:1,fullScreen:1', 1, 0, '', 1, 0, 0, 50, 1, 0, 1, 6, 3, 3, 6, 8, 'FFBB00', '001326', 'FFA200', '030000', '595959', 'FF0000', 'E8E84D', 'FF5500', 'EBEBEB', '000000', 'FFFFFF', 82, 79, 0, 1, 0)";

$sql_theme3 = "INSERT INTO `" .$table_name ."` VALUES(3, 0, 'Theme 3', 650, 400, 100, 0, 0, 0, 0, 50, 'repeatOff', 'shuffleOff', 5, 50, 2, 1, 1, 1, 1, 2, 'playPause:1,play:0,playlist:1,lib:1,playPrev:1,playNext:1,stop:0,vol:1,+:1,time:1,hd:1,repeat:1,shuffle:0,pause:0,share:1,fullScreen:1', 1, 0, '', 1, 0, 0, 50, 1, 1, 0, 12, 3, 3, 16, 20, 'FF0000', '070801', 'D10000', 'FFFFFF', '00A2FF', '00A2FF', 'F0FF61', '00A2FF', 'DEDEDE', '000000', 'FFFFFF', 65, 99, 0, 1, 0)";

$sql_theme4 = "INSERT INTO `" .$table_name ."` VALUES(4, 0, 'Theme 4', 650, 400, 60, 0, 0, 0, 0, 50, 'repeatOff', 'shuffleOff', 5, 60, 2, 1, 1, 1, 1, 2, 'playPause:1,playlist:1,lib:1,vol:1,playPrev:0,playNext:0,stop:0,+:1,hd:1,repeat:1,shuffle:0,play:0,pause:0,share:1,time:1,fullScreen:1', 1, 0, '', 1, 0, 0, 50, 1, 1, 1, 6, 4, 4, 6, 8, '239DC2', '000000', '2E6DFF', 'F5DA51', 'FFA64D', 'BFBA73', 'FF8800', 'FFF700', 'FFFFFF', 'FFFFFF', '000000', 71, 82, 0, 1, 0)";

$sql_theme5 = "INSERT INTO `" .$table_name ."` VALUES(5, 0, 'Theme 5', 650, 400, 100, 0, 0, 0, 0, 50, 'repeatOff', 'shuffleOff', 5, 50, 2, 1, 1, 1, 1, 2, 'playPrev:0,playPause:1,playlist:1,lib:1,playNext:0,stop:0,time:1,vol:1,+:1,hd:1,repeat:1,shuffle:1,play:0,pause:0,share:1,fullScreen:1', 1, 0, '', 1, 0, 0, 50, 1, 1, 1, 14, 4, 4, 14, 16, '878787', '001326', 'FFFFFF', '000000', '525252', '14B1FF', 'CCCCCC', '14B1FF', '030303', '000000', 'FFFFFF', 100, 75, 0, 1, 0)";

$sql_theme6 = "INSERT INTO `" .$table_name ."` VALUES(6, 0, 'Theme 6', 650, 400, 100, 0, 0, 0, 0, 50, 'repeatOff', 'shuffleOff', 5, 50, 2, 1, 1, 1, 1, 2, 'playPause:1,playlist:1,lib:1,vol:1,playPrev:0,playNext:0,stop:0,+:1,repeat:0,shuffle:0,play:0,pause:0,hd:1,share:1,time:1,fullScreen:1', 1, 0, '', 1, 0, 0, 50, 1, 1, 1, 14, 3, 3, 16, 16, '080808', '000000', '1C1C1C', 'FFFFFF', '40C6FF', '00A2FF', 'E8E8E8', '40C6FF', 'DEDEDE', '2E2E2E', 'FFFFFF', 61, 79, 0, 1, 0)";

$sql_theme7 = "INSERT INTO `" .$table_name ."` VALUES(7, 0, 'Theme  7', 650, 400, 100, 0, 0, 0, 0, 50, 'repeatOff', 'shuffleOff', 5, 50, 2, 1, 1, 1, 1, 2, 'playPause:1,playlist:1,lib:1,playPrev:0,playNext:0,stop:0,vol:1,+:1,hd:0,repeat:0,shuffle:0,play:0,pause:0,share:1,fullScreen:1,time:1', 1, 0, '', 1, 0, 0, 50, 1, 1, 1, 12, 3, 3, 16, 16, '212121', '000000', '222424', 'FFCC00', 'FFFFFF', 'ABABAB', 'B8B8B8', 'EEFF00', 'DEDEDE', '000000', '000000', 90, 78, 0, 1, 0)";

$table_name = $wpdb->prefix ."Spider_Video_Player_video";

$sql_video_insert_row1 = "INSERT INTO `" .$table_name ."` (`id`, `url`,  `urlHtml5`, `urlHD`, `urlHDHtml5`, `thumb`, `title`, `published`, `type`, `fmsUrl`, `params`) VALUES
(1, 'http://www.youtube.com/watch?v=eaE8N6alY0Y', 'http://www.youtube.com/watch?v=eaE8N6alY0Y', '', '', '" .plugins_url("images_for_start/red-sunset-casey1.jpg", __FILE__) ."', 'Sunset 1', 1, 'youtube', '', '2#===#Nature#***#1#===#2012#***#'),
(2, 'http://www.youtube.com/watch?v=y3eFdvDdXx0', 'http://www.youtube.com/watch?v=y3eFdvDdXx0', '', '', '" .plugins_url("images_for_start/sunset10.jpg", __FILE__) ."', 'Sunset 2', 1, 'youtube', '', '2#===#Nature#***#1#===#2012#***#');";

$table_name = $wpdb->prefix ."Spider_Video_Player_tag";
$sql_tag_insert_row1 = "INSERT INTO `" .$table_name ."` VALUES(1, 'Year', 1, 1, 2)";
$sql_tag_insert_row2 = "INSERT INTO `" .$table_name ."` VALUES(2, 'Genre', 1, 1, 1)";
$table_name = $wpdb->prefix ."Spider_Video_Player_playlist";
$sql_playlist_insert_row1 = "INSERT INTO `" .$table_name ."` VALUES(1, 'Nature', '" .plugins_url("images_for_start/sunset4.jpg", __FILE__) ."', 1, '1,2,')";

//create tables
    $wpdb->query($sql_playlist);
    $wpdb->query($sql_Spider_Video_Player);
    $wpdb->query($sql_tag);
    $wpdb->query($sql_theme);
    $wpdb->query($sql_video);
	
    $exist_that_col = false;
    $query = "SHOW COLUMNS FROM `" .$wpdb->prefix ."Spider_Video_Player_theme`";
    $colExists = $wpdb->get_results($query);
    foreach($colExists as $col) {
      if($col->Field == 'openPlaylistAtStart') {
  	    $exist_that_col = true;
	    break;
      }
    }
    $sql_alter_theme = "ALTER TABLE `" .$wpdb->prefix ."Spider_Video_Player_theme` ADD `openPlaylistAtStart` tinyint(1) NOT NULL";
    if (!$exist_that_col)
        $wpdb->query($sql_alter_theme);
    
    $spider_video_player_theme = $wpdb->get_var("SELECT * FROM " . $wpdb->prefix . "Spider_Video_Player_theme");	
    /* insert themt rows */
    if ($spider_video_player_theme == NULL) {
      $wpdb->query($sql_theme1);
      $wpdb->query($sql_theme2);
      $wpdb->query($sql_theme3);
      $wpdb->query($sql_theme4);
      $wpdb->query($sql_theme5);
      $wpdb->query($sql_theme6);
      $wpdb->query($sql_theme7);
    }
    $spider_video_player_video = $wpdb->get_var("SELECT * FROM " . $wpdb->prefix . "Spider_Video_Player_video");
    /* insert video rows */
    if ($spider_video_player_video == NULL) {
      $wpdb->query($sql_video_insert_row1);
    }
    $spider_video_player_tag = $wpdb->get_var("SELECT * FROM " . $wpdb->prefix . "Spider_Video_Player_tag");
    /* insert tag rows */
    if ($spider_video_player_tag == NULL) {
      $wpdb->query($sql_tag_insert_row1);
      $wpdb->query($sql_tag_insert_row2); 
    }
    /* insert playlist rows */
    $spider_video_player_playlist = $wpdb->get_var("SELECT * FROM " . $wpdb->prefix . "Spider_Video_Player_playlist");
  
    if ($spider_video_player_playlist == NULL) {
		$wpdb->query($sql_playlist_insert_row1);
    }
}

Spider_Video_Player_activate();
register_activation_hook(__FILE__, 'Spider_Video_Player_activate');

add_action( 'init', "wd_wdsvp_init" );
function wd_wdsvp_init(){
    if( !isset($_REQUEST['ajax']) && is_admin() )
	{
        if( !class_exists("DoradoWeb") )
		{
            require_once(WD_WDSVP_DIR . '/wd/start.php');
        }
        global $wdsvp_options;
        $wdsvp_options = array (
          "prefix" => "wdsvp",
          "wd_plugin_id" => 14,
          "plugin_title" => "Spider Video Player",
          "plugin_wordpress_slug" => "player",
          "plugin_dir" => WD_WDSVP_DIR,
          "plugin_main_file" => __FILE__,
          "description" => __('Spider Video Player allows you to add videos to your WordPress blogs, posts, and pages quickly and easily.', 'wdsvp'),
          // from web-dorado.com
          "plugin_features" => array(
            0 => array(
              "title" => __("Customizable", "wdsvp"),
              "description" => __("Spider Video Player is highly customizable. You can choose to customize the library, videos and frames background color adjustment features for the video player, the height and width of the video player, transparency level for the video player buttons and more.", "wdsvp"),
            ),
            1 => array(
              "title" => __("Unlimited playlists & videos", "wdsvp"),
              "description" => __("Spider video player has wonderful flash effects. You can add several video players in one page with different parameters and playlists. With Spider video player you can have unlimited playlists with unlimited number of videos.", "wdsvp"),
            ),
            2 => array(
              "title" => __("Supported Video Types", "wdsvp"),
              "description" => __("Spider Video Player supports types of videos, such as Http, YouTube, Vimeo and rtmp", "wdsvp"),
            ),
            3 => array(
              "title" => __("Image watermark", "wdsvp"),
              "description" => __("The WordPress plugin comes with the image watermark support for the video player (in Flash mode). You can choose to modify the parameters of the watermark image, including the  size, position and border spacing.", "wdsvp"),
            ),
          ),
            // user guide from web-dorado.com
          "user_guide" => array(
            0 => array(
              "main_title" => __("Installing", "wdsvp"),
              "url" => "https://web-dorado.com/wordpress-spider-video-player/installing.html",
              "titles" => array()
            ),
            1 => array(
              "main_title" => __("Adding Tags", "wdsvp"),
              "url" => "https://web-dorado.com/wordpress-spider-video-player/adding-tags-into-player.html",
              "titles" => array()
            ),
            2 => array(
              "main_title" => __("Adding Videos", "wdsvp"),
              "url" => "https://web-dorado.com/wordpress-spider-video-player/adding-videos-into-player.html",
              "titles" => array()
            ),
            3 => array(
              "main_title" => __("Creating Playlists", "wdsvp"),
              "url" => "https://web-dorado.com/wordpress-spider-video-player/creating-playlists.html",
              "titles" => array()
            ),
            4 => array(
              "main_title" => __("Creating Themes ", "wdsvp"),
              "url" => "https://web-dorado.com/wordpress-spider-video-player/creating-themes.html",
              "titles" => array(
                array(
                  "title" => __("General Parameters", "wdsvp"),
                  "url" => "https://web-dorado.com/wordpress-spider-video-player/creating-themes/general-parameters.html"
                ),
                array(
                  "title" => __("Style Parameters", "wdsvp"),
                  "url" => "https://web-dorado.com/wordpress-spider-video-player/creating-themes/style-parameters.html"
                ),
                array(
                  "title" => __("Playback Parameters", "wdsvp"),
                  "url" => "https://web-dorado.com/wordpress-spider-video-player/creating-themes/playback-parameters.html"
                ),
                array(
                  "title" => __("Playlist and Library Parameters", "wdsvp"),
                  "url" => "https://web-dorado.com/wordpress-spider-video-player/creating-themes/playlist-and-library-parameters.html"
                ),
                array(
                  "title" => __("Video Control Parameters", "wdsvp"),
                  "url" => "https://web-dorado.com/wordpress-spider-video-player/creating-themes/video-control-parameters.html"
                ),
              )
            ),
            5 => array(
              "main_title" => __("Adding Player", "wdsvp"),
              "url" => "https://web-dorado.com/wordpress-spider-video-player/adding-player.html",
              "titles" => array()
            ),
            6 => array(
              "main_title" => __("Publishing the Created Player", "wdsvp"),
              "url" => "https://web-dorado.com/wordpress-spider-video-player/publishing-player.html",
              "titles" => array()
            ),
          ),
          "overview_welcome_image" => WD_WDSVP_URL . '/images/welcome_image.png',
          "video_youtube_id" => null,  // e.g. https://www.youtube.com/watch?v=acaexefeP7o youtube id is the acaexefeP7o
          "plugin_wd_url" => "https://web-dorado.com/products/wordpress-player.html",
          "plugin_wd_demo_link" => "http://wpdemo.web-dorado.com/spider-video-player/?_ga=1.174040928.212018776.1470817467",
          "plugin_wd_addons_link" => "",
          "after_subscribe" => "admin.php?page=overview_wdsvp", // this can be plagin overview page or set up page
          "plugin_wizard_link" => null,
          "plugin_menu_title" => "Video Player",
          "plugin_menu_icon" => '',
          "deactivate" => false,
          "subscribe" => false,
          "custom_post" => 'Spider_Video_Player',  // if true => edit.php?post_type=contact
          "menu_capability" => "manage_options",
          "menu_position" => 9,
        );
        dorado_web_init($wdsvp_options);
    }
}


/*-=- Gutenberg Integration -=-*/
add_filter('tw_get_block_editor_assets', 'spider_video_player_register_block_editor_assets');
add_action( 'enqueue_block_editor_assets', 'spider_video_player_enqueue_block_editor_assets');
 function spider_video_player_register_block_editor_assets($assets) {
    $version = '1.5.22';
    $js_path = plugin_dir_url(__FILE__). '/tw-gb/block.js';
    $css_path = plugin_dir_url(__FILE__). '/tw-gb/block.css';
    if (!isset($assets['version']) || version_compare($assets['version'], $version) === -1) {
      $assets['version'] = $version;
      $assets['js_path'] = $js_path;
      $assets['css_path'] = $css_path;
    }
    return $assets;
}
// video player
function spider_video_player_enqueue_block_editor_assets() {
    $key = 'tw/spider-video';
    $key_categories = 'tw/spider-video-categories';
    
    $plugin_name = __('Spider Video Player', 'spider_player');
    $plugin_name_categories = __('Spider Player Single Video', 'spider_player');
    
    $icon_url = plugin_dir_url(__FILE__). '/tw-gb/spider_video_player.svg';
    $icon_svg = plugin_dir_url(__FILE__). '/tw-gb/spider_video_player_grey.svg';
    
    $url = add_query_arg(array('action' => 'spider_categories_shortcode'), admin_url('admin-ajax.php'));
    $data = get_video_shortcode_data();
    ?>
    <script>
      if ( !window['tw_gb'] ) {
        window['tw_gb'] = {};
      }
      if ( !window['tw_gb']['<?php echo $key; ?>'] ) {
        window['tw_gb']['<?php echo $key; ?>'] = {
          title: '<?php echo $plugin_name; ?>',
          titleSelect: '<?php echo sprintf(__('Select %s', 'spider_player'), $plugin_name); ?>',
          iconUrl: '<?php echo $icon_url; ?>',
          iconSvg: {
            width: '20',
            height: '20',
            src: '<?php echo $icon_svg; ?>'
          },
          isPopup: false,
          data: '<?php echo $data; ?>'
        }
      }
      if ( !window['tw_gb']['<?php echo $key_categories; ?>'] ) {
        window['tw_gb']['<?php echo $key_categories; ?>'] = {
          title: '<?php echo $plugin_name_categories; ?>',
          titleSelect: '<?php echo sprintf(__('Select %s', 'spider_player'), $plugin_name); ?>',
          iconUrl: '<?php echo $icon_url; ?>',
          iconSvg: {
            width: '20',
            height: '20',
            src: '<?php echo $icon_svg; ?>'
          },
          isPopup: true,
          containerClass: 'tw-container-wrap-520-400',
          data: {
            shortcodeUrl: '<?php echo $url; ?>'
          }
        }
      }
    </script>	
    <?php
    // Remove previously registered or enqueued versions
    $wp_scripts = wp_scripts();
    foreach ($wp_scripts->registered as $key => $value) {
      // Check for an older versions with prefix.
      if (strpos($key, 'tw-gb-block') > 0) {
        wp_deregister_script( $key );
        wp_deregister_style( $key );
      }
    }
    // Get the last version from all 10Web plugins.
    $assets = apply_filters('tw_get_block_editor_assets', array());
    // Not performing unregister or unenqueue as in old versions all are with prefixes.
    wp_enqueue_script('tw-gb-block', $assets['js_path'], array( 'wp-blocks', 'wp-element' ), $assets['version']);
    wp_localize_script('tw-gb-block', 'tw_obj', array(
      'nothing_selected' => __('Nothing selected.', 'spider_player'),
      'empty_item' => __('- Select -', 'spider_player'),
    ));
    wp_enqueue_style('tw-gb-block', $assets['css_path'], array( 'wp-edit-blocks' ), $assets['version']);
}
 
function get_video_shortcode_data() {
    global $wpdb;
    $rows = $wpdb->get_results("SELECT `id`, `title` as name FROM `" . $wpdb->prefix . "Spider_Video_Player_player`");
    $data = array();
    $data['shortcode_prefix'] = 'Spider_Video_Player';
    $data['inputs'][] = array(
      'type' => 'select',
      'id' => 'spider_player_cat_id',
      'name' => 'spider_player_cat_id',
      'shortcode_attibute_name' => 'id',
      'options'  => $rows,
    );
    return json_encode($data);
}

/*---- END_Gutenberg Integration*/