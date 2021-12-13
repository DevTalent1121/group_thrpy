<?php

function spider_Veideo_Player_Prewieve()
{
	
	
	
	if(isset($_GET["appWidth"])){
	if($_GET["appWidth"])
	$width=htmlspecialchars($_GET["appWidth"]);
}
else
{
	$width='700';
}
if(isset($_GET["appHeight"])){
	if($_GET["appHeight"])
	$height=htmlspecialchars($_GET["appHeight"]);
}
else
{
	$height='400';
}
	if($height && $width){
	
?>
<script type="text/javascript" src="<?php echo plugins_url("js/swfobject.js",__FILE__) ?>"></script>
  <div id="flashcontent"  style="width: <?php echo $width; ?>px; height: <?php echo $height; ?>px"></div>


<script>
function get_radio_value(name)
{
	for (var i=0; i < window.parent.document.getElementsByName(name).length; i++)   
	{   
		if (window.parent.document.getElementsByName(name)[i].checked)      
		{      
			var rad_val = window.parent.document.getElementsByName(name)[i].value;      
			return rad_val;      
		}   
	}
}
	appWidth			=parseInt(window.parent.document.getElementById('appWidth').value);
	appHeight			=parseInt(window.parent.document.getElementById('appHeight').value);
	playlistWidth		=window.parent.document.getElementById('playlistWidth').value;
	startWithLib		=get_radio_value('startWithLib');
	show_trackid		=get_radio_value('show_trackid');
	autoPlay			=get_radio_value('autoPlay');
	autoNext			=get_radio_value('autoNext');
	autoNextAlbum		=get_radio_value('autoNextAlbum');
	defaultVol			=window.parent.document.getElementById('defaultVol').value;
	defaultRepeat		=get_radio_value('defaultRepeat');
	defaultShuffle		=get_radio_value('defaultShuffle');
	autohideTime		=window.parent.document.getElementById('autohideTime').value;
	centerBtnAlpha		=window.parent.document.getElementById('centerBtnAlpha').value;
	loadinAnimType		=get_radio_value('loadinAnimType');
	keepAspectRatio		=get_radio_value('keepAspectRatio');
	clickOnVid			=get_radio_value('clickOnVid');
	spaceOnVid			=get_radio_value('spaceOnVid');
	mouseWheel			=get_radio_value('mouseWheel');
	ctrlsPos			=get_radio_value('ctrlsPos');
	ctrlsStack			=window.parent.document.getElementById('ctrlsStack').value;
	ctrlsOverVid		=get_radio_value('ctrlsOverVid');
	ctrlsSlideOut		=get_radio_value('ctrlsSlideOut');
	watermarkUrl		=window.parent.document.getElementById('post_image').value;
	watermarkPos		=get_radio_value('watermarkPos');
	watermarkSize		=window.parent.document.getElementById('watermarkSize').value;
	watermarkSpacing	=window.parent.document.getElementById('watermarkSpacing').value;
	watermarkAlpha		=window.parent.document.getElementById('watermarkAlpha').value;
	playlistPos			=get_radio_value('playlistPos');
	playlistOverVid		=get_radio_value('playlistOverVid');
	openPlaylistAtStart	=get_radio_value('openPlaylistAtStart');
	playlistAutoHide	=get_radio_value('playlistAutoHide');
	playlistTextSize	=window.parent.document.getElementById('playlistTextSize').value;
	libCols				=window.parent.document.getElementById('libCols').value;
	libRows				=window.parent.document.getElementById('libRows').value;
	libListTextSize		=window.parent.document.getElementById('libListTextSize').value;
	libDetailsTextSize	=window.parent.document.getElementById('libDetailsTextSize').value;
	appBgColor			=window.parent.document.getElementById('appBgColor').value;
	vidBgColor			=window.parent.document.getElementById('vidBgColor').value;
	framesBgColor		=window.parent.document.getElementById('framesBgColor').value;
	ctrlsMainColor		=window.parent.document.getElementById('ctrlsMainColor').value;
	ctrlsMainHoverColor	=window.parent.document.getElementById('ctrlsMainHoverColor').value;
	slideColor			=window.parent.document.getElementById('slideColor').value;
	itemBgHoverColor	=window.parent.document.getElementById('itemBgHoverColor').value;
	itemBgSelectedColor	=window.parent.document.getElementById('itemBgSelectedColor').value;
	textColor			=window.parent.document.getElementById('textColor').value;
	textHoverColor		=window.parent.document.getElementById('textHoverColor').value;
	textSelectedColor	=window.parent.document.getElementById('textSelectedColor').value;
	framesBgAlpha		=window.parent.document.getElementById('framesBgAlpha').value;
	ctrlsMainAlpha		=window.parent.document.getElementById('ctrlsMainAlpha').value;
	str='@appWidth='+appWidth
	+'@appHeight='+appHeight
	+'@playlistWidth='+playlistWidth
	+'@startWithLib='+startWithLib
	+'@show_trackid='+show_trackid
	+'@autoPlay='+autoPlay
	+'@autoNext='+appHeight
	+'@autoNextAlbum='+autoNextAlbum
	+'@defaultVol='+defaultVol
	+'@defaultRepeat='+defaultRepeat
	+'@defaultShuffle='+defaultShuffle
	+'@autohideTime='+autohideTime
	+'@centerBtnAlpha='+centerBtnAlpha
	+'@loadinAnimType='+loadinAnimType
	+'@keepAspectRatio='+keepAspectRatio
	+'@clickOnVid='+clickOnVid
	+'@spaceOnVid='+spaceOnVid
	+'@mouseWheel='+mouseWheel
	+'@ctrlsPos='+ctrlsPos
	+'@ctrlsStack=['+ctrlsStack
	+']@ctrlsOverVid='+ctrlsOverVid
	+'@ctrlsSlideOut='+ctrlsSlideOut
	+'@watermarkUrl='+watermarkUrl
	+'@watermarkPos='+watermarkPos
	+'@watermarkSize='+watermarkSize
	+'@watermarkSpacing='+watermarkSpacing
	+'@watermarkAlpha='+watermarkAlpha
	+'@playlistPos='+playlistPos
	+'@playlistOverVid='+playlistOverVid
	+'@openPlaylistAtStart='+openPlaylistAtStart
	+'@playlistAutoHide='+playlistAutoHide
	+'@playlistTextSize='+playlistTextSize
	+'@libCols='+libCols
	+'@libRows='+libRows
	+'@libListTextSize='+libListTextSize
	+'@libDetailsTextSize='+libDetailsTextSize
	+'@appBgColor='+appBgColor
	+'@vidBgColor='+vidBgColor
	+'@framesBgColor='+framesBgColor
	+'@ctrlsMainColor='+ctrlsMainColor
	+'@ctrlsMainHoverColor='+ctrlsMainHoverColor
	+'@slideColor='+slideColor
	+'@itemBgHoverColor='+itemBgHoverColor
	+'@itemBgSelectedColor='+itemBgSelectedColor
	+'@textColor='+textColor
	+'@textHoverColor='+textHoverColor
	+'@textSelectedColor='+textSelectedColor
	+'@framesBgAlpha='+framesBgAlpha
	+'@ctrlsMainAlpha='+ctrlsMainAlpha;
    var so = new SWFObject("<?php echo  plugins_url("videoSpider_Video_Player.swf",__FILE__) ?>?wdrand=<?php echo mt_rand() ?>", "Spider_Video_Player", "100%", "100%", "8", "#000000");   
   so.addParam("FlashVars", "settingsUrl=<?php echo admin_url('admin-ajax.php?action=spiderVeideoPlayerpreviewsettings') ?>@gago=77"+str+"&playlistUrl=<?php echo str_replace("&","@",str_replace("&amp;","@",admin_url('admin-ajax.php?action=spiderVeideoPlayerpreviewplaylist')));?>@show_trackid="+show_trackid);
   so.addParam("quality", "high");
   so.addParam("menu", "false");
   so.addParam("wmode", "transparent");
   so.addParam("loop", "false");
   so.addParam("allowfullscreen", "true");
   so.write("flashcontent");
	</script>
    <?php
	die();
	}
}




function spider_video_preview_playlist(){
	
	$show_trackid=htmlspecialchars($_GET['show_trackid']);
	echo '


<library>



	<albumFree title="Nature" thumb="'.plugins_url("images_for_start/sunset4.jpg",__FILE__).'" id="1">



<track id="1" type="youtube" url="http://www.youtube.com/watch?v=eaE8N6alY0Y" thumb="'.plugins_url("images_for_start/red-sunset-casey1.jpg",__FILE__).'"  trackId="1"  >Sunset 1</track>



<track id="2" type="youtube" url="http://www.youtube.com/watch?v=y3eFdvDdXx0" thumb="'.plugins_url("images_for_start/sunset10.jpg",__FILE__).'"   trackId="2"  >Sunset 2</track>



	</albumFree>



</library>';
exit;
	
}
function change_to_str($x)
{
	if($x)
		return 'true';
	return 'false';
}
function spider_video_preview_settings(){
	$ctrlsStack	=str_replace("[", "", htmlspecialchars($_GET['ctrlsStack']));
$ctrlsStack	=str_replace(", :", ", +:", $ctrlsStack);
$new="";
$single=htmlspecialchars($_GET["single"]);
if($ctrlsStack)
{
	$ctrls = explode(",", $ctrlsStack);
		foreach($ctrls as $key =>  $x) 
		 {
			$y = explode(":", $x);
			$ctrl	=$y[0];
			$active	=$y[1];
			if($single==1)
			{
				if($ctrl=='playlist')
				$active=0;
				
				if($ctrl=='lib')
				$active=0;
			}
			if($active==1)
			if($new=="")
				$new=$y[0];
			else
				$new=$new.','.$y[0];
		 }
};
	echo '<settings>
	<appWidth>'.htmlspecialchars($_GET["appWidth"]).'</appWidth>
	<appHeight>'.htmlspecialchars($_GET["appHeight"]).'</appHeight>
	<playlistWidth>'.htmlspecialchars($_GET["playlistWidth"]).'</playlistWidth>
	<startWithLib>'.change_to_str(htmlspecialchars($_GET["startWithLib"])).'</startWithLib>
	<autoPlay>'.change_to_str(htmlspecialchars($_GET["autoPlay"])).'</autoPlay>
	<autoNext>'.change_to_str(htmlspecialchars($_GET["autoNext"])).'</autoNext>
	<autoNextAlbum>'.change_to_str(htmlspecialchars($_GET["autoNextAlbum"])).'</autoNextAlbum>
	<defaultVol>'.((htmlspecialchars($_GET["defaultVol"])+0)/100).'</defaultVol>
	<defaultRepeat>'.htmlspecialchars($_GET["defaultRepeat"]).'</defaultRepeat>
	<defaultShuffle>'.htmlspecialchars($_GET["defaultShuffle"]).'</defaultShuffle>
	<autohideTime>'.htmlspecialchars($_GET["autohideTime"]).'</autohideTime>
	<centerBtnAlpha>'.((htmlspecialchars($_GET["centerBtnAlpha"])+0)/100).'</centerBtnAlpha>
	<loadinAnimType>'.htmlspecialchars($_GET["loadinAnimType"]).'</loadinAnimType>
	<keepAspectRatio>'.change_to_str(htmlspecialchars($_GET["keepAspectRatio"])).'</keepAspectRatio>
	<clickOnVid>'.change_to_str(htmlspecialchars($_GET["clickOnVid"])).'</clickOnVid>
	<spaceOnVid>'.change_to_str(htmlspecialchars($_GET["spaceOnVid"])).'</spaceOnVid>
	<mouseWheel>'.change_to_str(htmlspecialchars($_GET["mouseWheel"])).'</mouseWheel>
	<ctrlsPos>'.htmlspecialchars($_GET["ctrlsPos"]).'</ctrlsPos>
	<ctrlsStack>'.$new.'</ctrlsStack>
	<ctrlsOverVid>'.change_to_str(htmlspecialchars($_GET["ctrlsOverVid"])).'</ctrlsOverVid>
	<ctrlsAutoHide>'.change_to_str(htmlspecialchars($_GET["ctrlsSlideOut"])).'</ctrlsAutoHide>
	<watermarkUrl>'.htmlspecialchars($_GET["watermarkUrl"]).'</watermarkUrl>
	<watermarkPos>'.htmlspecialchars($_GET["watermarkPos"]).'</watermarkPos>
	<watermarkSize>'.htmlspecialchars($_GET["watermarkSize"]).'</watermarkSize>
	<watermarkSpacing>'.htmlspecialchars($_GET["watermarkSpacing"]).'</watermarkSpacing>
	<watermarkAlpha>'.((htmlspecialchars($_GET["watermarkAlpha"])+0)/100).'</watermarkAlpha>
	<playlistPos>'.htmlspecialchars($_GET["playlistPos"]).'</playlistPos>
	<playlistOverVid>'.change_to_str(htmlspecialchars($_GET["playlistOverVid"])).'</playlistOverVid>
	<openPlaylistAtStart>'.change_to_str(htmlspecialchars($_GET["openPlaylistAtStart"])).'</openPlaylistAtStart>
	<playlistAutoHide>'.change_to_str(htmlspecialchars($_GET["playlistAutoHide"])).'</playlistAutoHide>
	<playlistTextSize>'.htmlspecialchars($_GET["playlistTextSize"]).'</playlistTextSize>
	<libCols>'.htmlspecialchars($_GET["libCols"]).'</libCols>
	<libRows>'.htmlspecialchars($_GET["libRows"]).'</libRows>
	<libListTextSize>'.htmlspecialchars($_GET["libListTextSize"]).'</libListTextSize>
	<libDetailsTextSize>'.htmlspecialchars($_GET["libDetailsTextSize"]).'</libDetailsTextSize>
	<playBtnHint>'. __('play','Player').'</playBtnHint>
	<pauseBtnHint>'. __('pause','Player').'</pauseBtnHint>
	<playPauseBtnHint>'. __('toggle pause','Player').'</playPauseBtnHint>
	<stopBtnHint>'. __('stop','Player').'</stopBtnHint>
	<playPrevBtnHint>'. __('play previous','Player').'</playPrevBtnHint>
	<playNextBtnHint>'. __('play next','Player').'</playNextBtnHint>
	<volBtnHint>'. __('volume','Player').'</volBtnHint>
	<repeatBtnHint>'. __('repeat','Player').'</repeatBtnHint>
	<shuffleBtnHint>'. __('shuffle','Player').'</shuffleBtnHint>
	<hdBtnHint>'. __('HD','Player').'</hdBtnHint>
	<playlistBtnHint>'. __('open/close playlist','Player').'</playlistBtnHint>
	<libOnBtnHint>'. __('open library','Player').'</libOnBtnHint>
	<libOffBtnHint>'. __('close library','Player').'</libOffBtnHint>
	<fullScreenBtnHint>'. __('switch full screen','Player').'</fullScreenBtnHint>
	<backBtnHint>'. __('back to list','Player').'</backBtnHint>
	<replayBtnHint>'. __('Replay','Player').'</replayBtnHint>
	<nextBtnHint>'.__('Next','Player').'</nextBtnHint>
	<appBgColor>'."0x".htmlspecialchars($_GET["appBgColor"]).'</appBgColor>
	<vidBgColor>'."0x".htmlspecialchars($_GET["vidBgColor"]).'</vidBgColor>
	<framesBgColor>'."0x".htmlspecialchars($_GET["framesBgColor"]).'</framesBgColor>
	<framesBgAlpha>'.((htmlspecialchars($_GET["framesBgAlpha"])+0)/100).'</framesBgAlpha>
	<ctrlsMainColor>'."0x".htmlspecialchars($_GET["ctrlsMainColor"]).'</ctrlsMainColor>
	<ctrlsMainHoverColor>'."0x".htmlspecialchars($_GET["ctrlsMainHoverColor"]).'</ctrlsMainHoverColor>
	<ctrlsMainAlpha>'.((htmlspecialchars($_GET["ctrlsMainAlpha"])+0)/100).'</ctrlsMainAlpha>
	<slideColor>'."0x".htmlspecialchars($_GET["slideColor"]).'</slideColor>
	<itemBgHoverColor>'."0x".htmlspecialchars($_GET["itemBgHoverColor"]).'</itemBgHoverColor>
	<itemBgSelectedColor>'."0x".htmlspecialchars($_GET["itemBgSelectedColor"]).'</itemBgSelectedColor>
	<itemBgAlpha>'.((htmlspecialchars($_GET["framesBgAlpha"])+0)/100).'</itemBgAlpha>
	<textColor>'."0x".htmlspecialchars($_GET["textColor"]).'</textColor>
	<textHoverColor>'."0x".htmlspecialchars($_GET["textHoverColor"]).'</textHoverColor>
	<textSelectedColor>'."0x".htmlspecialchars($_GET["textSelectedColor"]).'</textSelectedColor>
	<embed></embed>

	</settings>';
	exit;
	
}




function spider_video_select_playlist(){
	require_once("nav_function/nav_html_func.php");
if(get_bloginfo( 'version' )>3.3){
	?>
<link rel="stylesheet" href="<?php echo bloginfo("url") ?>/wp-admin/load-styles.php?c=0&amp;dir=ltr&amp;load=admin-bar,dashicons,wp-admin&amp;ver=7f0753feec257518ac1fec83d5bced6a" type="text/css" media="all">
<?php
}
else
{
	?>
 <link rel="stylesheet" href="<?php echo bloginfo("url") ?>/wp-admin/load-styles.php?c=1&amp;dir=ltr&amp;load=global,wp-admin&amp;ver=aba7495e395713976b6073d5d07d3b17" type="text/css" media="all">
 <?php
}
 ?>
<link rel="stylesheet" id="thickbox-css" href="<?php echo bloginfo('url')?>/wp-includes/js/thickbox/thickbox.css?ver=20111117" type="text/css" media="all">
	<link rel="stylesheet" id="colors-css" href="<?php echo bloginfo('url')?>/wp-admin/css/colors/light/colors<?php echo ((get_bloginfo('version') < '3.8') ? '-classic' : '')?>.css?ver=20111206" type="text/css" media="all">
	<link rel="stylesheet" id="form-css" href="<?php echo bloginfo('url')?>/wp-admin/css/forms<?php echo ((get_bloginfo('version') < '3.8') ? '-classic' : '')?>.css?ver=20111206" type="text/css" media="all">

<?php
	////////////////////////////////////////////////////////////////////////
	
	
	
	
	
	
		global $wpdb;
	$sort["default_style"]="manage-column column-autor sortable desc";
	$sort["sortid_by"]='title';
	$sort["custom_style"]='manage-column column-autor sortable desc';
    $sort["1_or_2"]=1;
	if(isset($_POST['page_number']))
	{
			
			if($_POST['asc_or_desc'])
			{
			$columns = array("id", "title", "videos", "published");
			
			if(in_array($_POST['order_by'], $columns )) 
			{
				$sort["sortid_by"]=esc_sql(esc_html(stripslashes($_POST['order_by'])));
			}
				if($_POST['asc_or_desc']==1)
				{
					$sort["custom_style"]="manage-column column-title sorted asc";
					$sort["1_or_2"]="2";
					$order="ORDER BY ".$sort["sortid_by"]." ASC";
				}
				else
				{
					$sort["custom_style"]="manage-column column-title sorted desc";
					$sort["1_or_2"]="1";
					$order="ORDER BY ".$sort["sortid_by"]." DESC";
				}
			}
			
	if($_POST['page_number'])
		{
			$limit=(esc_sql(esc_html(stripslashes($_POST['page_number'])))-1)*20;
		}
		else
		{
			$limit=0;
		}
	}
	else
		{
			$limit=0;
		}
	if(isset($_POST['search_events_by_title'])){
		$search_tag=esc_sql(esc_html(stripslashes($_POST['search_events_by_title'])));
		}
		
		else
		{
		$search_tag="";
		}

	if ( $search_tag ) {
		$whereee= ' WHERE published=1 AND title LIKE "%'.$search_tag.'%"';
	}
	else
	{
		$whereee=' WHERE published=1';
	}
	
	
	
	// get the total number of records
	$query = "SELECT COUNT(*) FROM ".$wpdb->prefix."Spider_Video_Player_playlist ". $whereee;
	$total = $wpdb->get_var($query);
	$pageNav['total'] =$total;
	$pageNav['limit'] =	 $limit/20+1;
	
	$query = "SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_playlist ".$whereee." ". (isset($order)? $order : "")." "." LIMIT ".$limit.",20";
	if($sort["sortid_by"] == 'videos')
	{
		if($_POST['asc_or_desc'])
			{
				$sort["sortid_by"]=esc_sql(esc_html(stripslashes($_POST['order_by'])));
				if($_POST['asc_or_desc']==1)
				{
					$order=" ASC";
				}
				else
				{
					$order=" DESC";
				}
			}
	$query = 'SELECT *, (LENGTH(  `videos` ) - LENGTH( REPLACE(  `videos` ,  ",",  "" ) )) AS video_count FROM '.$wpdb->prefix.'Spider_Video_Player_playlist '. $whereee. ' ORDER BY  `video_count` '.$order." LIMIT ".$limit.",20";
	
	}
	$rows = $wpdb->get_results($query);
	html_select_video($rows, $pageNav,$sort);
	exit;
	
	}
	
	
	
	
	
	
	
	
function html_select_video($rows, $pageNav, $sort)
{
	?>
<script type="text/javascript">
function submitbutton(pressbutton) {
var form = document.adminForm;
if (pressbutton == 'cancel') 
{
submitform( pressbutton );
return;
}
submitform( pressbutton );
}
function xxx()
{
	var VIDS =[];
	var title =[];
	var thumb =[];
	var number_of_vids =[];
	for(i=0; i<<?php echo count($rows) ?>; i++)
		if(document.getElementById("v"+i))
			if(document.getElementById("v"+i).checked)
			{
				VIDS.push(document.getElementById("v"+i).value);
				title.push(document.getElementById("title_"+i).value);
				thumb.push(document.getElementById("thumb_"+i).value);
				number_of_vids.push(document.getElementById("number_of_vids_"+i).value);
			}
	window.parent.jSelectVideoS(VIDS, title, thumb, number_of_vids);
}
function ordering(name,as_or_desc)
	{
		document.getElementById('asc_or_desc').value=as_or_desc;		
		document.getElementById('order_by').value=name;
		document.getElementById('admin_form').submit();
	}
function checkAll( n, fldName ) {
  if (!fldName) {
     fldName = 'cb';
  }
	var f = document.adminForm;
	var c = f.toggle.checked;
	var n2 = 0;
	for (i=0; i < n; i++) {
		cb = eval( 'f.' + fldName + '' + i );
		if (cb) {
			cb.checked = c;
			n2++;
		}
	}
	if (c) {
		document.adminForm.boxchecked.value = n2;
	} else {
		document.adminForm.boxchecked.value = 0;
	}
}
</script>

	<form action="<?php echo admin_url('admin-ajax.php?action=spiderVeideoPlayerselectplaylist') ?>" method="post" id="admin_form" name="adminForm">
    
		<table width="95%">
           <td align="right" width="100%">
            <button onclick="xxx();">+ Add Playlist +</button>           
             </td>

       </tr>
		</table>    
    
        <?php 
		
        if(isset($_POST['serch_or_not'])) {if($_POST['serch_or_not']=="search"){ $serch_value=esc_sql(esc_html(stripslashes($_POST['search_events_by_title']))); }else{$serch_value="";}}
	$serch_fields='<div class="alignleft actions" style="width:180px;">
    	<label for="search_events_by_title" style="font-size:14px">Title: </label>
        <input type="text" name="search_events_by_title" value="'.(isset($serch_value) ? $serch_value : "").'" id="search_events_by_title" onchange="clear_serch_texts()">
    </div>
	<div class="alignleft actions">
   		<input type="button" value="Search" onclick="document.getElementById(\'page_number\').value=\'1\'; document.getElementById(\'serch_or_not\').value=\'search\';
		 document.getElementById(\'admin_form\').submit();" class="button-secondary action">
		 <input type="button" value="Reset" onclick="window.location.href=\''. admin_url('admin-ajax.php?action=spiderVeideoPlayerselectplaylist').'\'" class="button-secondary action">
    </div>';
	 print_html_nav($pageNav['total'],$pageNav['limit'],$serch_fields);	
	 ?>
    <table class="wp-list-table widefat fixed pages" >
    <thead>
    	<tr>
            <th style="width:30px"><?php echo '#'; ?></th>
            <th class="manage-column column-cb check-column">
            <input  type="checkbox" name="toggle" id="toggle" value="" onclick="checkAll(<?php echo count($rows)?>, 'v')">
            </th>
           <th scope="col" id="id" class="<?php if($sort["sortid_by"]=="id") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="width:50px" ><a href="javascript:ordering('id',<?php if($sort["sortid_by"]=="id") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>ID</span><span class="sorting-indicator"></span></a></th>
 <th scope="col" id="title" class="<?php if($sort["sortid_by"]=="title") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="" ><a href="javascript:ordering('title',<?php if($sort["sortid_by"]=="title") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>Title</span><span class="sorting-indicator"></span></a></th>
 <th scope="col" id="videos" class="<?php if($sort["sortid_by"]=="videos") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="width:161px" ><a href="javascript:ordering('videos',<?php if($sort["sortid_by"]=="videos") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>The number of Videos</span><span class="sorting-indicator"></span></a></th>
  <th scope="col" id="published" class="<?php if($sort["sortid_by"]=="published") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="width:87px" ><a href="javascript:ordering('published',<?php if($sort["sortid_by"]=="published") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>Published</span><span class="sorting-indicator"></span></a></th>
       </tr>
    </thead>
                
    <?php
    $k = 0;
	for($i=0, $n=count($rows); $i < $n ; $i++)
	{
		$row = &$rows[$i];
		$published 	= $row->published;
		
		$number_of_vids=substr_count($row->videos, ',');
?>
        <tr class="<?php echo "row$k"; ?>">
        	<td align="center"><?php echo $i+1?></td>
        	<td>
            <input type="checkbox" id="v<?php echo $i?>" value="<?php echo $row->id;?>" />
            <input type="hidden" id="title_<?php echo $i?>" value="<?php echo  htmlspecialchars($row->title);?>" />
            <input type="hidden" id="thumb_<?php echo $i?>" value="<?php echo  htmlspecialchars($row->thumb);?>" />
            <input type="hidden" id="number_of_vids_<?php echo $i?>" value="<?php echo  $number_of_vids;?>" />
            </td>
        	<td align="center"><?php echo $row->id?></td>
        	<td><a style="cursor: pointer;" onclick="window.parent.jSelectVideoS(['<?php echo $row->id?>'],['<?php echo htmlspecialchars(addslashes($row->title));?>'],['<?php echo htmlspecialchars(addslashes($row->thumb));?>'],['<?php echo $number_of_vids;?>'])"><?php echo $row->title?></a></td>            
         	<td align="center"><?php echo $number_of_vids?></td>            
        	<td align="center"><?php echo $published?></td>            
        </tr>
        <?php
		$k = 1 - $k;
	}
	?>
    </table>
    <input type="hidden" name="asc_or_desc" id="asc_or_desc" value="<?php if(isset($_POST['asc_or_desc'])) { echo esc_js(esc_html(stripslashes($_POST['asc_or_desc']))); } ?>"  />
 	<input type="hidden" name="order_by" id="order_by" value="<?php if(isset($_POST['order_by'])) { echo esc_js(esc_html(stripslashes($_POST['order_by']))); } ?>"  />
    <input type="hidden" name="option" value="com_Spider_Video_Player">
    <input type="hidden" name="task" value="select_playlist">    
    <input type="hidden" name="boxchecked" value="0"> 
    <input type="hidden" name="filter_order_playlist" value="<?php echo (isset($lists['order']) ? esc_js(esc_html(stripslashes($lists['order']))) : ""); ?>" />
    <input type="hidden" name="filter_order_Dir_playlist" value="<?php echo (isset($lists['order_Dir']) ? esc_js(esc_html(stripslashes($lists['order_Dir']))) : ""); ?>" />       
    </form>
    <?php
}











function spider_video_select_video(){
	
	require_once("nav_function/nav_html_func.php");
if(get_bloginfo( 'version' )>3.3){
	?>
<link rel="stylesheet" href="<?php echo bloginfo("url") ?>/wp-admin/load-styles.php?c=0&amp;dir=ltr&amp;load=admin-bar,dashicons,wp-admin&amp;ver=7f0753feec257518ac1fec83d5bced6a" type="text/css" media="all">
<?php
}
else
{
	?>
 <link rel="stylesheet" href="<?php echo bloginfo("url") ?>/wp-admin/load-styles.php?c=1&amp;dir=ltr&amp;load=global,wp-admin&amp;ver=aba7495e395713976b6073d5d07d3b17" type="text/css" media="all">
 <?php
}

 ?>

<link rel="stylesheet" id="thickbox-css" href="<?php echo bloginfo('url')?>/wp-includes/js/thickbox/thickbox.css?ver=20111117" type="text/css" media="all">
	<link rel="stylesheet" id="colors-css" href="<?php echo bloginfo('url')?>/wp-admin/css/colors/light/colors<?php echo ((get_bloginfo('version') < '3.8') ? '-classic' : '')?>.css?ver=20111206" type="text/css" media="all">
	<link rel="stylesheet" id="form-css" href="<?php echo bloginfo('url')?>/wp-admin/css/forms<?php echo ((get_bloginfo('version') < '3.8') ? '-classic' : '')?>.css?ver=20111206" type="text/css" media="all">

	<?php
	////////////////////////////////////////////////////////////////////////
	
	
	
	
	
	
	global $wpdb;
		
	$sort["default_style"]="manage-column column-autor sortable desc";
	$sort["custom_style"]='manage-column column-autor sortable desc';
	$sort["1_or_2"]=1;
	$where='';
	$order='';
	$search_tag='';
	$sort["sortid_by"]='title';
	if(isset($_POST['page_number']))
	{
			
			if($_POST['asc_or_desc'])
			{
				$columns = array("id", "title", "type", "url", "urlHtml5", "urlHD", "urlhdHtml5", "thumb", "published" );
			
			if(in_array($_POST['order_by'], $columns )) 
			{
				$sort["sortid_by"]=esc_sql(esc_html(stripslashes($_POST['order_by'])));
			}
				if($_POST['asc_or_desc']==1)
				{
					$sort["custom_style"]="manage-column column-title sorted asc";
					$sort["1_or_2"]="2";
					$order="ORDER BY ".esc_sql($sort["sortid_by"])." ASC";
				}
				else
				{
					$sort["custom_style"]="manage-column column-title sorted desc";
					$sort["1_or_2"]="1";
					$order="ORDER BY ".esc_sql($sort["sortid_by"])." DESC";
				}
			}
			
	if($_POST['page_number'])
		{
			$limit=(esc_sql(esc_html(stripslashes($_POST['page_number'])))-1)*20;
		}
		else
		{
			$limit=0;
		}
	}
	else
		{
			$limit=0;
		}
	if(isset($_POST['search_video'])){
		$where=' WHERE title LIKE "%'.esc_sql(esc_html(stripslashes($_POST['search_video']))).'%" AND published=1 ';
		
		}
		
		else
		{
		$where=" WHERE published=1 ";
		}
		
	$query = "SELECT COUNT(*) FROM ".$wpdb->prefix."Spider_Video_Player_video". $where;
	$total = $wpdb->get_var($query);
	if(!$total) $total=0;
	$pageNav['total'] =$total;
	$pageNav['limit'] =	 $limit/20+1;
	
	$query = "SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_video".$where." ". $order." "." LIMIT ".$limit.",20";
	$rows = $wpdb->get_results($query);	    
		
	// table ordering
		// get list of Playlists for dropdown filter
	
	///////////////tags
	$query = "SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_tag WHERE published=1 ORDER BY ordering";
	$tags =$wpdb->get_results($query);
	$search_tags=array();
	foreach($tags as $tag)
	{
		if(isset($_POST['param_'.$tag->id]))
		{
		
		$search_tags[$tag->id] = esc_sql(esc_html(stripslashes($_POST['param_'.$tag->id])));
		$search_tags[$tag->id] = strtolower( $search_tags[$tag->id] );	
		}

	}
	
	$param= array( array ());
	foreach($rows as $row)
	{
		$params=explode('#***#', $row->params);
		$params= array_slice($params,0, count($params)-1);   
		foreach ($params as $param_temp)
		{
			$param_temp							= explode('#===#', $param_temp);
			$param[$row->id][$param_temp[0]]	= strtolower($param_temp[1]);
		}
	}
	$new_rows=array();
	foreach($rows as $row)
	{
		$t=true;
		foreach($search_tags as $key =>$search_tag)
		{
			if($search_tag)
			if(isset($param[$row->id][$key]))
			{
				if(!is_numeric(strpos($param[$row->id][$key], $search_tag)))
					$t=false;
			}
			else
					$t=false;
		}
		
		if($t)
			$new_rows[]=$row;
	}

	//$db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
	 
    // display function
	html_select_video_admin($new_rows, $pageNav,$sort,$tags);
	exit;




	
	
	}
	
	
	
	
	
	
	
	
	
function html_select_video_admin($rows, $pageNav, $sort,$tags)
{
	?>
<script type="text/javascript">
	function ordering(name,as_or_desc)
	{
		document.getElementById('asc_or_desc').value=as_or_desc;		
		document.getElementById('order_by').value=name;
		document.getElementById('admin_form').submit();
	}
function submitbutton(pressbutton) {
var form = document.admin_form;
if (pressbutton == 'cancel') 
{
submitform( pressbutton );
return;
}
submitform( pressbutton );
}
function tableOrdering( order, dir, task ) {
    var form = document.admin_form;
    form.filter_order_video.value     = order;
    form.filter_order_Dir_video.value = dir;
    submitform( task );
}
function xxx()
{
	var VIDS =[];
	var title =[];
	var type =[];
	var url =[];
	var thumb =[];
	var trackid =[];
	for(i=0; i<<?php echo count($rows) ?>; i++)
		if(document.getElementById("v"+i))
			if(document.getElementById("v"+i).checked)
			{
				VIDS.push(document.getElementById("v"+i).value);
				title.push(document.getElementById("title_"+i).value);
				type.push(document.getElementById("type_"+i).value);
				url.push(document.getElementById("url_"+i).value);
				thumb.push(document.getElementById("thumb_"+i).value);
				trackid.push(document.getElementById("trackId_"+i).value);
			}
	window.parent.jSelectVideoS(VIDS, title, type, url, thumb, trackid);
}
function reset_all()
{
	document.getElementById('search_video').value='';
<?php  if($tags)   foreach($tags as $tag) {?>
	document.getElementById('param_<?php echo $tag->id; ?>').value='';
<?php }?>
	this.form.submit();
}
function checkAll( n, fldName ) {
  if (!fldName) {
     fldName = 'cb';
  }
	var f = document.admin_form;
	var c = f.toggle.checked;
	var n2 = 0;
	for (i=0; i < n; i++) {
		cb = eval( 'f.' + fldName + '' + i );
		if (cb) {
			cb.checked = c;
			n2++;
		}
	}
	if (c) {
		document.adminForm.boxchecked.value = n2;
	} else {
		document.adminForm.boxchecked.value = 0;
	}
}
</script>

	<form action="<?php echo admin_url('admin-ajax.php?action=spiderVeideoPlayerselectvideo'); ?>" method="post" name="admin_form" id="admin_form">

		<table>
		<tr>
			<td align="left">
				<?php echo  'Title' ; ?>:
            </td>
            <td>
				<input type="text" name="search_video" id="search_video" value="<?php if(isset($_POST["search_video"])){echo esc_js(esc_html(stripslashes($_POST["search_video"])));} ?>" class="text_area" />
            </td>
            <td rowspan="50">
                <button ><?php echo  'Go' ; ?></button>
            </td>
            <td rowspan="50">
				<button onclick="reset_all()"><?php echo  'Reset' ; ?></button>
			</td>
           <td align="right" width="100%">
                <button onclick="xxx();">+ Add VIDEO +</button>           
           </td>

       </tr>
       
       <?php
	   if($tags)
	   foreach($tags as $tag)
	   {?>
       <tr>
		 <td align="left">
		<?php echo $tag->name ; ?>:
         </td>
		 <td align="left">
             <input type="text" name="param_<?php echo $tag->id;?>" id="param_<?php echo $tag->id; ?>" value="<?php if(isset($_POST["param_".$tag->id])){echo esc_js(esc_html(stripslashes($_POST["param_".$tag->id])));} ?>" class="text_area" />
         </td>
        </tr> 
		<?php 
        }
	  	?>
      
		</table>    
    
     <?php    print_html_nav($pageNav['total'],$pageNav['limit']); ?>
    <table class="wp-list-table widefat fixed pages" style="width:95% !important"  align="center">
    <thead>
    	<tr>
            <th width="50px"><?php echo '#'; ?></th>
            <th scope="col" name="toggle" id="cb" class="manage-column column-cb check-column" style=""><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows)?>, 'v')"></th>
<th scope="col"  id="id" class="<?php if($sort["sortid_by"]=="id") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style=" width:50px" ><a href="javascript:ordering('id',<?php if($sort["sortid_by"]=="id") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>ID</span><span class="sorting-indicator"></span></a></th>
 <th scope="col" id="title" class="<?php if($sort["sortid_by"]=="title") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="width:120px" ><a href="javascript:ordering('title',<?php if($sort["sortid_by"]=="title") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>Title</span><span class="sorting-indicator"></span></a></th>
 <th scope="col" id="type" class="<?php if($sort["sortid_by"]=="type") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="width:80px" ><a href="javascript:ordering('type',<?php if($sort["sortid_by"]=="type") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>Type</span><span class="sorting-indicator"></span></a></th>
  <th scope="col" id="URL" class="<?php if($sort["sortid_by"]=="url") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="" ><a href="javascript:ordering('url',<?php if($sort["sortid_by"]=="url") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>URL</span><span class="sorting-indicator"></span></a></th>
  <th scope="col" id="UrlHD" class="<?php if($sort["sortid_by"]=="urlHD") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="" ><a href="javascript:ordering('urlHD',<?php if($sort["sortid_by"]=="urlHD") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>UrlHD</span><span class="sorting-indicator"></span></a></th><th scope="col" id="thumb" class="<?php if($sort["sortid_by"]=="thumb") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style=" width:120px" ><a href="javascript:ordering('thumb',<?php if($sort["sortid_by"]=="thumb") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>Thumb</span><span class="sorting-indicator"></span></a></th>
  <th scope="col" id="published" class="<?php if($sort["sortid_by"]=="published") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style=" width:120px" ><a href="javascript:ordering('published',<?php if($sort["sortid_by"]=="published") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>Published</span><span class="sorting-indicator"></span></a></th>
        </tr>
    </thead>
	
    <?php
    $k = 0;
	for($i=0, $n=count($rows); $i < $n ; $i++)
	{
		$row = $rows[$i];
?>
        <tr class="<?php echo "row$k"; ?>">
        	<td class="check-column" align="center"><?php echo $i+1?></td>
        	<th class="check-column">
            <input type="checkbox" name="post[]" class="checkbox" id="v<?php echo $i?>" value="<?php echo $row->id;?>" />
            <input type="hidden" id="title_<?php echo $i?>" value="<?php echo  htmlspecialchars($row->title);?>" />
            <input type="hidden" id="type_<?php echo $i?>" value="<?php echo  $row->type?>" />
            <input type="hidden" id="url_<?php echo $i?>" value="<?php echo  htmlspecialchars($row->url);?>" />
            <input type="hidden" id="thumb_<?php echo $i?>" value="<?php echo  htmlspecialchars($row->thumb);?>" />
            <input type="hidden" id="trackId_<?php echo $i?>" value="<?php echo  $row->id?>" />

            </th>
        	<td align="center"><?php echo $row->id?></td>
        	<td><a style="cursor: pointer;" onclick="window.parent.jSelectVideoS(['<?php echo $row->id?>'],['<?php echo htmlspecialchars(addslashes($row->title))?>'],['<?php echo $row->type?>'],['<?php echo htmlspecialchars(addslashes($row->url))?>'],['<?php echo htmlspecialchars(addslashes($row->thumb))?>'],['<?php echo $row->id?>'])"><?php echo $row->title?></a></td>            
        	<td><?php echo $row->type ?></td>    
        	<td><?php echo $row->url ?></td>    
        	<td><?php echo $row->urlHD ?></td>
        	<td><img style="max-height:60px; max-width:60px" src="<?php echo $row->thumb ?>"  /></td>                      
        	<td align="center"><?php echo  $row->published?></td>            
        </tr>
        <?php
		$k = 1 - $k;
	}
	?>
    </table>
    <input type="hidden" name="asc_or_desc" id="asc_or_desc" value="<?php if(isset($_POST['asc_or_desc'])) echo esc_js(esc_html(stripslashes($_POST['asc_or_desc'])));?>"  />
 <input type="hidden" name="order_by" id="order_by" value="<?php if(isset($_POST['order_by'])) echo esc_js(esc_html(stripslashes($_POST['order_by'])));?>"  />
    <input type="hidden" name="option" value="com_Spider_Video_Player">
    <input type="hidden" name="task" value="select_video">    
    <input type="hidden" name="boxchecked" value="0">   
    </form>
    <?php
}








function generete_sp_video_playlist_xml(){
global $wpdb;
$single=htmlspecialchars($_GET["single"]);

if($single==0){
$id_for_playlist=htmlspecialchars($_GET["playlist"]);
$playerr=$wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_player WHERE id=%d",$id_for_playlist));
$playlists=array();
$playlists_id=array();
$show_trackid=htmlspecialchars($_GET['show_trackid']);

				$playlists_id=explode(',',$playerr->playlist);
				$playlists_id= array_slice($playlists_id,0, count($playlists_id)-1); 
				foreach($playlists_id as $playlist_id)
				{
					$query =$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_playlist WHERE published='1' AND id=%d",$playlist_id) ;
					
                                        
                                        
                                        $playlists[] = $wpdb->get_row($query);
				}
foreach($playlists as $playlist)
				{
					if($playlist)
					{
						$viedos_temp=array();
						$videos_id=explode(',',$playlist->videos);
						$videos_id= array_slice($videos_id,0, count($videos_id)-1);   
						foreach($videos_id as $video_id)
						{
							$query =$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_video WHERE type!=%s AND id = %d",'vimeo',$video_id);
							$video_row = $wpdb->get_row($query);
                                                        if($video_row){
                                                            $viedos_temp[] =$video_row;
                                                        }
						}
			
						$videos[$playlist->id] = $viedos_temp;
					}
				}

	echo '<library>
';
		foreach($playlists as $playlist)

		{
			
			if($playlist)
			{
echo	'	<albumFree title="'.htmlspecialchars($playlist->title).'" thumb="'.$playlist->thumb.'" id="'.$playlist->id.'">
';
$i=0;
				foreach($videos[$playlist->id] as $video)
		
				{
					$i++;
				echo '<track  id="'.$video->id.'" type="'.$video->type.'"';
				if($video->type=="rtmp")
					echo ' fmsUrl="'.htmlspecialchars($video->fmsUrl).'"';
				if($video->type=="http")
					echo ' url="'.htmlspecialchars($video->url).'"';
				else
					echo ' url="'.htmlspecialchars($video->url).'"';
				if($video->type=="http")
					echo ' urlHD="'.htmlspecialchars($video->urlHD).'"';
				else
				if($video->type=="rtmp")
					echo ' urlHD="'.htmlspecialchars($video->urlHD).'"';
				echo ' thumb="';
				if($video->thumb)
					if(is_file(htmlspecialchars($video->thumb)))
						echo htmlspecialchars($video->thumb);
					else
						echo htmlspecialchars($video->thumb);
				echo '"';
				if($show_trackid)
				echo ' trackId="'.$i.'" ';
				echo '>'.$video->title.'</track>
';
				}
				
				
echo '	</albumFree>
';
			}
		}





echo '</library>' ;
exit;
	
	

}
else{
$track_ID=htmlspecialchars($_GET["trackID"]);
$single_vid=$wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_video WHERE id=%d",$track_ID));


	echo '<library>
';
		
			
echo	'	<albumFree title="Single Video" thumb="" id="0">
';
if($single_vid->type!='youtube'){
    if($single_vid->type=='rtmp')
        echo '<track id="'.$single_vid->id.'" type="'.$single_vid->type.'" fmsUrl="'.htmlspecialchars($single_vid->fmsUrl).'" url="'.htmlspecialchars($single_vid->url).'" urlHD="'.htmlspecialchars($single_vid->urlHD).'" thumb="'.htmlspecialchars($single_vid->thumb).'">'.$single_vid->title.'</track>';
    else
        echo '<track id="'.$single_vid->id.'" type="'.$single_vid->type.'" url="'.htmlspecialchars($single_vid->url).'" thumb="'.htmlspecialchars($single_vid->thumb).'">'.$single_vid->title.'</track>';
}else
    echo '<track id="'.$single_vid->id.'" type="'.$single_vid->type.'" url="'.htmlspecialchars($single_vid->url).'" thumb="'.htmlspecialchars($single_vid->thumb).'">'.$single_vid->title.'</track>';
			
			
			

				
				
echo '	</albumFree>';

echo '</library>' ;
exit;	
	}
}
function generete_sp_video_settings_xml(){
	
global $wpdb;
$single=htmlspecialchars($_GET["single"]);
$id_theme=htmlspecialchars($_GET["theme"]);
$params=$wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_theme WHERE id=%d",$id_theme));
//$new	=str_replace("#", "0x", $params->textColor'));
$new="";
if($params->ctrlsStack)
{
	$ctrls = explode(",", $params->ctrlsStack);
		foreach($ctrls as $key =>  $x) 
		 {
			$y = explode(":", $x);
			$ctrl	=$y[0];
			$active	=$y[1];
			if($single==1)
			{
				if($ctrl=='playlist')
				$active=0;				
				if($ctrl=='lib')
				$active=0;
				$embed_url=admin_url('admin-ajax.php?action=spiderVeideoPlayervideoonly').'&single=1&trackID='.htmlspecialchars($_GET['s_v_player_id']).'&theme='.htmlspecialchars($_GET['theme']).'&priority='.(isset($_GET['priority']) ? htmlspecialchars($_GET['priority']) : "");
			}else{
			    $embed_url=admin_url('admin-ajax.php?action=spiderVeideoPlayervideoonly').'&single=0&id_player='.htmlspecialchars($_GET['s_v_player_id']);
			}
			if($active==1)
			if($new=="")
				$new=$y[0];
			else
				$new=$new.','.$y[0];
		 }
};

$playlist=htmlspecialchars($_GET["playlist"]);
$theme=htmlspecialchars($_GET["theme"]);
	echo '<settings>
	<appWidth>'.$params->appWidth.'</appWidth>
	<appHeight>'.$params->appHeight.'</appHeight>
	<playlistWidth>'.$params->playlistWidth.'</playlistWidth>
	<startWithLib>'.change_to_str($params->startWithLib).'</startWithLib>
	<autoPlay>'.change_to_str($params->autoPlay).'</autoPlay>
	<autoNext>'.change_to_str($params->autoNext).'</autoNext>
	<autoNextAlbum>'.change_to_str($params->autoNextAlbum).'</autoNextAlbum>
	<defaultVol>'.(($params->defaultVol+0)/100).'</defaultVol>
	<defaultRepeat>'.$params->defaultRepeat.'</defaultRepeat>
	<defaultShuffle>'.str_replace ('Shuffle', 'shuffle',$params->defaultShuffle).'</defaultShuffle>
	<autohideTime>'.$params->autohideTime.'</autohideTime>
	<centerBtnAlpha>'.(($params->centerBtnAlpha+0)/100).'</centerBtnAlpha>
	<loadinAnimType>'.$params->loadinAnimType.'</loadinAnimType>
	<keepAspectRatio>'.change_to_str($params->keepAspectRatio).'</keepAspectRatio>
	<clickOnVid>'.change_to_str($params->clickOnVid).'</clickOnVid>
	<spaceOnVid>'.change_to_str($params->spaceOnVid).'</spaceOnVid>
	<mouseWheel>'.change_to_str($params->mouseWheel).'</mouseWheel>
	<ctrlsPos>'.$params->ctrlsPos.'</ctrlsPos>
	<ctrlsStack>'.$new.'</ctrlsStack>
	<ctrlsOverVid>'.change_to_str($params->ctrlsOverVid).'</ctrlsOverVid>
	<ctrlsAutoHide>'.change_to_str($params->ctrlsSlideOut).'</ctrlsAutoHide>
	<watermarkUrl>'.$params->watermarkUrl.'</watermarkUrl>
	<watermarkPos>'.$params->watermarkPos.'</watermarkPos>
	<watermarkSize>'.$params->watermarkSize.'</watermarkSize>
	<watermarkSpacing>'.$params->watermarkSpacing.'</watermarkSpacing>
	<watermarkAlpha>'.(($params->watermarkAlpha+0)/100).'</watermarkAlpha>
	<playlistPos>'.$params->playlistPos.'</playlistPos>
	<playlistOverVid>'.change_to_str($params->playlistOverVid).'</playlistOverVid>
	<openPlaylistAtStart>'.change_to_str($params->openPlaylistAtStart).'</openPlaylistAtStart>
	<playlistAutoHide>'.change_to_str($params->playlistAutoHide).'</playlistAutoHide>
	<playlistTextSize>'.$params->playlistTextSize.'</playlistTextSize>
	<libCols>'.$params->libCols.'</libCols>
	<libRows>'.$params->libRows.'</libRows>
	<libListTextSize>'.$params->libListTextSize.'</libListTextSize>
	<libDetailsTextSize>'.$params->libDetailsTextSize.'</libDetailsTextSize>
	<playBtnHint>'.__('play','Player').'</playBtnHint>
	<pauseBtnHint>'.__('pause','Player').'</pauseBtnHint>
	<playPauseBtnHint>'.__('toggle pause','Player').'</playPauseBtnHint>
	<stopBtnHint>'.__('stop','Player').'</stopBtnHint>
	<playPrevBtnHint>'.__('play previous','Player').'</playPrevBtnHint>
	<playNextBtnHint>'.__('play next','Player').'</playNextBtnHint>
	<volBtnHint>'.__('volume','Player').'</volBtnHint>
	<repeatBtnHint>'.__('repeat','Player').'</repeatBtnHint>
	<shuffleBtnHint>'.__('shuffle','Player').'</shuffleBtnHint>
	<hdBtnHint>'.__('HD','Player').'</hdBtnHint>
	<playlistBtnHint>'.__('open/close playlist','Player').'</playlistBtnHint>
	<libOnBtnHint>'.__('open library','Player').'</libOnBtnHint>
	<libOffBtnHint>'.__('close library','Player').'</libOffBtnHint>
	<fullScreenBtnHint>'.__('switch full screen','Player').'</fullScreenBtnHint>
	<backBtnHint>'.__('back to list','Player').'</backBtnHint>
	<replayBtnHint>'.__('Replay','Player').'</replayBtnHint>
	<nextBtnHint>'.__('Next','Player').'</nextBtnHint>
	<appBgColor>'."0x".$params->appBgColor.'</appBgColor>
	<vidBgColor>'."0x".$params->vidBgColor.'</vidBgColor>
	<framesBgColor>'."0x".$params->framesBgColor.'</framesBgColor>
	<framesBgAlpha>'.(($params->framesBgAlpha+0)/100).'</framesBgAlpha>
	<ctrlsMainColor>'."0x".$params->ctrlsMainColor.'</ctrlsMainColor>
	<ctrlsMainHoverColor>'."0x".$params->ctrlsMainHoverColor.'</ctrlsMainHoverColor>
	<ctrlsMainAlpha>'.(($params->ctrlsMainAlpha+0)/100).'</ctrlsMainAlpha>
	<slideColor>'."0x".$params->slideColor.'</slideColor>
	<itemBgHoverColor>'."0x".$params->itemBgHoverColor.'</itemBgHoverColor>
	<itemBgSelectedColor>'."0x".$params->itemBgSelectedColor.'</itemBgSelectedColor>
	<itemBgAlpha>'.(($params->itemBgAlpha+0)/100).'</itemBgAlpha>
	<textColor>'."0x".$params->textColor.'</textColor>
	<textHoverColor>'."0x".$params->textHoverColor.'</textHoverColor>
	<textSelectedColor>'."0x".$params->textSelectedColor.'</textSelectedColor>
	<embed>'.$embed_url.'</embed>

	</settings>';
	exit;
	}

function viewe_sp_video_only() {
  
	 global $wpdb;
	 global $post;
	 $single=htmlspecialchars($_GET['single']);
	 if($single==0){
	 $id=htmlspecialchars($_GET['id_player']);
	 $id_for_posts = $post->ID;
	 $all_player_ids=$wpdb->get_col($wpdb->prepare("SELECT id FROM ".$wpdb->prefix."Spider_Video_Player_player"));
				$b=false;
				foreach($all_player_ids as $all_player_id)
				{
					if($all_player_id==$id)
					$b=true;
				}
				if(!$b){
				echo "<h2>Error svpv_31</h2>";
				return "";
				}
			
				$Spider_Video_Player_front_end="";
		
		
		$row=$wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_player WHERE id=%d",$id));
		
		$params=$wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_theme WHERE id=%d",$row->theme));
		$theme=$row->theme;
		$playlist=$row->id;
		if($params->appWidth!="")
			$width=$params->appWidth;
		else
			$width='700';
		
		if($params->appHeight!="")
			$height=$params->appHeight;
		else
			$height='400';
			
			$show_trackid=$params->show_trackid;
		?>
		<?php
	
		$Spider_Video_Player_front_end="<script type=\"text/javascript\" src=\"".plugins_url("swfobject.js",__FILE__)."\"></script>
		  <div id=\"".$id_for_posts."_flashcontent\"  style=\"width: ".($width+20)."px; height:".($height+20)."px; margin-top:-40px; margin-left:0px;\"></div>
			<script type=\"text/javascript\">
function flashShare(type,b,c)	
{
	u=location.href;
	u=u.replace('/?','/index.php?');
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
		window.open('http://twitter.com/home/?status='+encodeURIComponent(u+'&AlbumId='+b+'&TrackId='+c), \"Twitter\",\"menubar=1,resizable=1,width=350,height=250\");
		break;
	}
}		
		   var so = new SWFObject(\"".plugins_url("videoSpider_Video_Player.swf",__FILE__)."?wdrand=".mt_rand() ."\", \"Spider_Video_Player\", \"100%\", \"100%\", \"8\", \"#000000\");
		   so.addParam(\"FlashVars\", \"settingsUrl=".str_replace("&","@",  str_replace("&amp;","@",admin_url('admin-ajax.php?action=spiderVeideoPlayersettingsxml')."&playlist=".$playlist."&theme=".$theme."&s_v_player_id=".$id))."&playlistUrl=".str_replace("&","@",str_replace("&amp;","@",admin_url('admin-ajax.php?action=spiderVeideoPlayerplaylistxml')."&playlist=".$playlist."&show_trackid=".$show_trackid))."&defaultAlbumId=".htmlspecialchars($_GET['AlbumId'])."&defaultTrackId=".htmlspecialchars($_GET['TrackId'])."\");
		   so.addParam(\"quality\", \"high\");
		   so.addParam(\"menu\", \"false\");
		   so.addParam(\"wmode\", \"transparent\");
		   so.addParam(\"loop\", \"false\");
		   so.addParam(\"allowfullscreen\", \"true\");
		   so.write(\"".$id_for_posts."_flashcontent\");
			</script>";
			
			echo $Spider_Video_Player_front_end;
			
			exit;
	}
	
	else{
	$theme_id=htmlspecialchars($_GET['theme']);
	$track=htmlspecialchars($_GET['trackID']);
	$priority=htmlspecialchars($_GET['priority']);
	if($priority==0){	
	 $id_for_posts = $post->ID;
	 $all_player_ids=$wpdb->get_col($wpdb->prepare("SELECT id FROM ".$wpdb->prefix."Spider_Video_Player_video"));
				$b=false;
				foreach($all_player_ids as $all_player_id)
				{
					if($all_player_id==$track)
					$b=true;
					
				}
				if(!$b){
				
				echo "<h2>Error svpv_31</h2>";
				return "";
				}
				
				$Spider_Video_Player_front_end="";
		
		
		$row=$wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_video WHERE id=%d",$track));
		
		$params=$wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_theme WHERE id=%d",$theme_id));
		

		if($params->appWidth!="")
			$width=$params->appWidth;
		else
			$width='700';
		
		if($params->appHeight!="")
			$height=$params->appHeight;
		else
			$height='400';
			
			$show_trackid=$params->show_trackid;
		?>
		<?php
		
		$Spider_Video_Player_front_end="<script type=\"text/javascript\" src=\"".plugins_url("swfobject.js",__FILE__)."\"></script>
		  <div id=\"".$id_for_posts."_flashcontent\"  style=\"width: ".($width+20)."px; height:".($height+20)."px; margin-top:-40px; margin-left:0px;\"></div>
			<script type=\"text/javascript\">
function flashShare(type,b,c)	
{
	u=location.href;
	u=u.replace('/?','/index.php?');
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
		window.open('http://twitter.com/home/?status='+encodeURIComponent(u+'&AlbumId='+b+'&TrackId='+c), \"Twitter\",\"menubar=1,resizable=1,width=350,height=250\");
		break;
	}
}		
		   var so = new SWFObject(\"".plugins_url("videoSpider_Video_Player.swf",__FILE__)."?wdrand=".mt_rand() ."\", \"Spider_Video_Player\", \"100%\", \"100%\", \"8\", \"#000000\");
		   so.addParam(\"FlashVars\", \"settingsUrl=".str_replace("&","@",  str_replace("&amp;","@",admin_url('admin-ajax.php?action=spiderVeideoPlayersettingsxml')."&playlist=".$playlist."&theme=".$theme_id."&s_v_player_id=".$track."&single=1"))."&playlistUrl=".str_replace("&","@",str_replace("&amp;","@",admin_url('admin-ajax.php?action=spiderVeideoPlayerplaylistxml')."&trackID=".$track."&single=1&show_trackid=".$show_trackid))."&defaultAlbumId=".htmlspecialchars($_GET['defaultAlbumId'])."&defaultTrackId=".htmlspecialchars($_GET['defaultTrackId'])."\");
		   so.addParam(\"quality\", \"high\");
		   so.addParam(\"menu\", \"false\");
		   so.addParam(\"wmode\", \"transparent\");
		   so.addParam(\"loop\", \"false\");
		   so.addParam(\"allowfullscreen\", \"true\");
		   so.write(\"".$id_for_posts."_flashcontent\");
			</script>";
			
			echo $Spider_Video_Player_front_end;
			
			exit;
	
	}else{
	 echo Spider_Single_Video_front_end($track, $theme_id, $priority);
	}
	}
	}
?>