<?php
$path  = ''; // It should be end with a trailing slash  
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
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Spider Video Player</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<script language="javascript" type="text/javascript" src="<?php echo get_option("siteurl"); ?>/wp-includes/js/jquery/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option("siteurl"); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option("siteurl"); ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option("siteurl"); ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
	<base target="_self">
</head>
<body id="link"  style="" dir="ltr" class="forceColors">
	<div class="tabs" role="tablist" tabindex="-1">
		<ul>
			<li id="spider_player_tab" class="current" role="tab" tabindex="0"><span><a href="javascript:mcTabs.displayTab('spider_player_tab','spider_player_panel');" onMouseDown="return false;" tabindex="-1">Spider Video Player</a></span></li>
			<li id="spider_single_tab" role="tab" tabindex="-1"><span><a href="javascript:mcTabs.displayTab('spider_single_tab','spider_single_panel');" onMouseDown="return false;" tabindex="-1">Single Video</a></span></li>
		</ul>
	</div>
    <style>
    .panel_wrapper{
		height:100px !important;
	}
    </style>
    <div class="panel_wrapper">
			<div id="spider_player_panel" class="panel current">
                <table style="font-size: 13px !important;">
              	  <tr>
               		 <td style=" width:100px; vertical-align:top;">
                		Select a Player 
                	</td>
                	<td style="vertical-align:top">
						<select name="Spider_Video_Playername" id="Spider_Video_Playername" style="width:200px;" >
							<option value="- Select Spider_Video_Player -" selected="selected">- Select -</option>
<?php 
 $ids_Spider_Video_Player=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_player order by title",0);
	   foreach($ids_Spider_Video_Player as $arr_Spider_Video_Player)
	   {
		   ?>
							<option value="<?php echo $arr_Spider_Video_Player->id; ?>"><?php echo $arr_Spider_Video_Player->title; ?></option>
           <?php }?>
						</select>
					</td>
				  </tr>
                </table>
            </div>
		    <div id="spider_single_panel" class="panel">
                <table style="font-size: 13px !important;">
              	  <tr>
               		 <td style="width:100px; vertical-align:top;">
                		Select a Video 
                	</td>
                	<td style="vertical-align:top">
						<select name="Spider_Single_Videoname" id="Spider_Single_Videoname" style="width:200px;" >
							<option value="- Select Video -" selected="selected">- Select -</option>
<?php 
 $ids_Spider_Single_Video=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_video order by title",0);
	   foreach($ids_Spider_Single_Video as $arr_Spider_Single_Video)
	   {
		   ?>
							<option value="<?php echo $arr_Spider_Single_Video->id; ?>"><?php echo $arr_Spider_Single_Video->title; ?></option>
           <?php }?>
						</select>
					</td>
					</tr>
					<tr>
               		 <td style=" width:100px; vertical-align:top;">
                		Select a Theme 
                	</td>
                	<td style="vertical-align:top">
						<select name="Spider_Video_Theme" id="Spider_Video_Theme" style="width:200px;" >
							<option value="- Select Theme -" selected="selected">- Select -</option>
<?php 
 $ids_Spider_Video_Theme=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_theme order by title",0);
	   foreach($ids_Spider_Video_Theme as $arr_Spider_Video_Theme)
	   {
		   ?>
							<option value="<?php echo $arr_Spider_Video_Theme->id; ?>"><?php echo $arr_Spider_Video_Theme->title; ?></option>
           <?php }?>
						</select>
					</td>
					</tr>
					<tr>
               		 <td style=" width:100px; vertical-align:top;">
                		Priority 
                	</td>
                	<td style="vertical-align:top">
						<input type="radio" name="priority" id="flash" value="0" checked="checked">
	                    <label for="flash">Flash</label>
	                    <input type="radio" name="priority" style="margin-left: 12px;" id="html5" value="1">
	                    <label for="html5">HTML5</label>
					</td>
					</tr>
                </table>
            </div>
    </div>
        <div class="mceActionPanel">
		<div style="float: left">
			<input type="button" id="cancel" name="cancel" value="Cancel" onClick="tinyMCEPopup.close();" />
		</div>
		<div style="float: right">
			<input type="submit" id="insert" name="insert" value="Insert" onClick="insert_Spider_Video_Player();" />
		</div>
	</div>
<script type="text/javascript">
function insert_Spider_Video_Player() {
if(document.getElementById('spider_player_panel').className==='panel')
	{
		
	if((document.getElementById('Spider_Single_Videoname').value=='- Select Video -') || (document.getElementById('Spider_Video_Theme').value=='- Select Theme -'))
	{
		tinyMCEPopup.close();
	}
	else
	{ 
	   var priority;
	   priority=0;
	   if(!document.getElementById('flash').checked)
		{
		 priority=1;
		}
	   var tagtext;
	   tagtext='[Spider_Single_Video track="'+document.getElementById('Spider_Single_Videoname').value+'" theme_id="'+document.getElementById('Spider_Video_Theme').value+'" priority="'+priority+'"]';
	   window.tinyMCE.execCommand('mceInsertContent', false, tagtext);
	   tinyMCEPopup.close();		
	}
	
	}else
	{
	if(document.getElementById('Spider_Video_Playername').value=='- Select Spider_Video_Player -')
	{
		tinyMCEPopup.close();
	}
	else
	{
	   var tagtext;
	   tagtext='[Spider_Video_Player id="'+document.getElementById('Spider_Video_Playername').value+'"]';
	   window.tinyMCE.execCommand('mceInsertContent', false, tagtext);
	   tinyMCEPopup.close();		
	}
	}
}
</script>

</body></html>
