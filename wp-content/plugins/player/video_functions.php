<?php 
if(function_exists('current_user_can')){
	if(!current_user_can('manage_options')) {
	die('Access Denied');
}	
} else {
	die('Access Denied');
}
function add_video(){
	$lists['published'] =  '<input type="radio" name="published" id="published0" value="0" class="inputbox">
							<label for="published0">No</label>
							<input type="radio" name="published" id="published1" value="1" checked="checked" class="inputbox">
							<label for="published1">Yes</label>';
   global $wpdb;
	
	$query = "SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_tag order by ordering";
	$tags = $wpdb->get_results($query);
	
// display function
	html_add_video($lists, $tags );
}
function show_video(){
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
		$where= ' WHERE title LIKE "%'.$search_tag.'%"';
	}
	if(isset($_POST['id_for_playlist']))
	{
		if($_POST['id_for_playlist']>0)
		{
		if($where!="")
		{
			$id_in=$wpdb->get_var($wpdb->prepare("SELECT videos FROM ".$wpdb->prefix."Spider_Video_Player_playlist WHERE id=%d",$_POST['id_for_playlist']));
			$where.=" AND  id in (".$id_in."0)";
			
		}
		else
		{
			$id_in=$wpdb->get_var($wpdb->prepare("SELECT videos FROM ".$wpdb->prefix."Spider_Video_Player_playlist WHERE id=%d",$_POST['id_for_playlist']));
			$where=' WHERE id in ('.$id_in.'0)';
		}
		}
	}
	
	// get the total number of records
	$query = "SELECT COUNT(*) FROM ".$wpdb->prefix."Spider_Video_Player_video". $where;
	$total = $wpdb->get_var($query);
	$pageNav['total'] =$total;
	$pageNav['limit'] =	 $limit/20+1;
	
	$query = "SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_video".$where." ". $order." "." LIMIT ".$limit.",20";
	$rows = $wpdb->get_results($query);
	$query = "SELECT id,title FROM ".$wpdb->prefix."Spider_Video_Player_playlist";
	$playlists=$wpdb->get_results($query);
    // display function
	html_show_video($rows, $pageNav,$sort,$playlists);
}
function save_video(){
	global $wpdb;
	$s='';
	if(isset($_POST["params"]))
	foreach($_POST["params"] as $key=> $param )
	{
		$s=$s.$key.'#===#'.esc_html(stripslashes($param)).'#***#';
	}
	switch($_POST["type"]){
	case 'http':
		save_type_http($s);
		break;
		
	case 'youtube':
		save_type_youtobe($s);
		break;
	case 'vimeo':
            save_video_vimeo($s);
		break;	
	case 'rtmp':
		save_type_rmtp($s);
		break;	
	default:
	echo "<h1 style=\"color:#C00\">video cannot save error select type</h1>";
	break;
				
	}
	
	
}
 function save_type_http($s)
 {
	 global $wpdb;
	 
	
		$save_or_no= $wpdb->insert($wpdb->prefix.'Spider_Video_Player_video', array(
		'id'	     => NULL,
        'url'        => esc_sql(esc_html(stripslashes($_POST["http_post_video"]))),
		'urlHtml5'   => esc_sql(esc_html(stripslashes($_POST["http_post_video_html5"]))),
        'urlHD'      => esc_sql(esc_html(stripslashes($_POST["http_post_video_UrlHD"]))),
		'urlHDHtml5' => esc_sql(esc_html(stripslashes($_POST["http_post_video_UrlHD_html5"]))),
        'published'  =>esc_sql(esc_html(stripslashes($_POST["published"]))),
        'type'       => "http",
		'params'     =>esc_html($s),
		'title'      =>esc_sql(esc_html(stripslashes($_POST["title"]))),
		'thumb'      =>esc_sql(esc_html(stripslashes($_POST["post_image"])))
                ),
				array(
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',
				'%s',	
				'%s',
				'%s',
				'%s'					
				)
                );
		
		
					if(!$save_or_no)
	{
		?>
	<div class="updated"><p><strong><?php _e('Error. Please install plugin again'); ?></strong></p></div>
	<?php
		return false;
	}
	?>
	<div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
	<?php
	
    return true;
 }
  function save_type_rmtp($s)
 {
		 global $wpdb;
	 	 $save_or_no= $wpdb->insert($wpdb->prefix.'Spider_Video_Player_video', array(
		'id'	   => NULL,
		'fmsUrl'    => esc_sql(esc_html(stripslashes($_POST["fmsUrl"]))),
        'url'      => esc_sql(esc_html(stripslashes($_POST["url_rtmp"]))),
        'urlHD'    => esc_sql(esc_html(stripslashes($_POST["urlHD_rtmp"]))),
        'published' =>esc_sql(esc_html(stripslashes($_POST["published"]))),
        'type'      => "rtmp",
		'params'   =>esc_html($s),
		'title'    =>esc_sql(esc_html(stripslashes($_POST["title"]))),
		'thumb'    =>esc_sql(esc_html(stripslashes($_POST["post_image"]))),
		'urlHtml5'   => '',
		'urlHDHtml5' => ''
                ),
				array(
				'%d',
				'%s',
				'%s',
				'%s',
				'%d',
				'%s',	
				'%s',
				'%s',
				'%s',
				'%s',
				'%s'					
				)
                );
					if(!$save_or_no)
	{
		?>
	<div class="updated"><p><strong><?php _e('Error. Please install plugin again'); ?></strong></p></div>
	<?php
		return false;
	}
	?>
	<div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
	<?php
	
    return true;
 }
  function save_type_youtobe($s)
 {
      $url = str_replace('https', 'http', esc_sql(esc_html(stripslashes($_POST['url_youtube']))));
      $url5 = "https://www.youtube.com/embed/".substr($_POST['url_youtube'], strpos($_POST['url_youtube'], '?v=')+3,11)."?enablejsapi=1&html5=1&controls=1&modestbranding=1&rel=0";
            global $wpdb;
		 $save_or_no= $wpdb->insert($wpdb->prefix.'Spider_Video_Player_video', array(
		'id'	     => NULL,
        'url'        => $url,
        'published'  =>esc_sql(esc_html(stripslashes($_POST["published"]))),
        'type'       => "youtube",
		'params'     =>esc_html($s),
		'title'      =>esc_sql(esc_html(stripslashes($_POST["title"]))),
		'thumb'      =>esc_sql(esc_html(stripslashes($_POST["post_image"]))),
		'urlHtml5'   =>$url5,
		'urlHDHtml5' => ''
                ),
				array(
				'%d',
				'%s',
				'%d',
				'%s',	
				'%s',
				'%s',
				'%s',
				'%s',
				'%s'					
				)
                );
					if(!$save_or_no)
	{
		?>
	<div class="updated"><p><strong><?php _e('Error. Please install plugin again'); ?></strong></p></div>
	<?php
		return false;
	}
	?>
	<div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
	<?php
	
    return true;
 }
function save_video_vimeo($s){
        $url = "https://player.vimeo.com/video/".substr($_POST['url_vimeo'], strrpos($_POST['url_vimeo'], '/')+1)."?api=1";
            global $wpdb;
		 $save_or_no= $wpdb->insert($wpdb->prefix.'Spider_Video_Player_video', array(
		'id'	     => NULL,
        'url'        => esc_sql(esc_html(stripslashes($_POST['url_vimeo']))),
        'published'  =>esc_sql(esc_html(stripslashes($_POST["published"]))),
        'type'       => "vimeo",
		'params'     =>esc_html($s),
		'title'      =>esc_sql(esc_html(stripslashes($_POST["title"]))),
		'thumb'      =>esc_sql(esc_html(stripslashes($_POST["post_image"]))),
		'urlHtml5'   =>$url,
		'urlHDHtml5' => ''
                ),
				array(
				'%d',
				'%s',
				'%d',
				'%s',	
				'%s',
				'%s',
				'%s',
				'%s',
				'%s'					
				)
                );
					if(!$save_or_no)
	{
		?>
	<div class="updated"><p><strong><?php _e('Error. Please install plugin again'); ?></strong></p></div>
	<?php
		return false;
	}
	?>
	<div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
	<?php
	
    return true;
}
function edit_video($id){
	global $wpdb;
	if(!$id)
	{
		$id=$wpdb->get_var("SELECT MAX( id ) FROM ".$wpdb->prefix."Spider_Video_Player_video");
	}
	$query = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_video WHERE `id`=%d",$id);
	$row=$wpdb->get_row($query);
	$published0="";
	$published1="";
	if(!$row->published)
	{
		$published0='checked="checked"';
	}
	else
	{
		$published1='checked="checked"';
	}
	$query = "SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_tag order by ordering";
	$tags = $wpdb->get_results($query);
		$lists['published'] =  '<input type="radio" name="published" id="published0" value="0" '.$published0.' class="inputbox">
							<label for="published0">No</label>
							<input type="radio" name="published" id="published1" value="1" '.$published1.' class="inputbox">
							<label for="published1">Yes</label>';
	html_edit_video($lists, $row, $tags,$id);
}
function remove_video($id){
  global $wpdb;
  // If any item selected
  
    // Prepare sql statement, if cid array more than one, 
    // will be "cid1, cid2, ..."
    // Create sql statement
	 $sql_remov_vid=$wpdb->prepare("DELETE FROM ".$wpdb->prefix."Spider_Video_Player_video WHERE id=%d",$id);
 if(!$wpdb->query($sql_remov_vid))
 {
	  ?>
	  <div id="message" class="error"><p>Spider Video Player Video Not Deleted</p></div>
      <?php
	 
 }
 else{
 ?>
 <div class="updated"><p><strong><?php _e('Item Deleted.' ); ?></strong></p></div>
 <?php
 }
    // Execute query
	
  
  
  	$query = "SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_playlist ";
	
	$playlists = $wpdb->get_results($query);	
	foreach($playlists as $playlist)
	{
		$viedos_temp=array();
		$videos_id=explode(',',$playlist->videos);
		$videos_id= array_slice($videos_id,0, count($videos_id)-1);
		$new_videos='';   
		foreach($videos_id as $video_id)
		{
			if($video_id!=$id)
				$new_videos=$new_videos.$video_id.',';
		}
	 $savedd=$wpdb->update($wpdb->prefix.'Spider_Video_Player_playlist', array(
			'videos'    =>$new_videos,
              ), 
              array('id'=>$playlist->id),
			  array(  '%s' )
			  );
	}
}
function apply_video($id)
{
if(!$id)
{
	echo '<h1 style="color:#00C">error valu id=0 please reinstal plugin</h1>';
	exit;
}
global $wpdb;
	$s='';
	if(isset($_POST["params"]))
	foreach($_POST["params"] as $key=> $param )
	{
		$s=$s.$key.'#===#'.esc_sql(esc_html(stripslashes($param))).'#***#';
	}
	switch($_POST["type"]){
	case 'http':
		apply_type_http($s,$id);
		break;
		
	case 'youtube':
		apply_type_youtobe($s,$id);
		break;
	case 'vimeo':
		apply_type_vimeo($s,$id);
		break;
		
	case 'rtmp':
		apply_type_rmtp($s,$id);
		break;	
	default:
	echo "<h1 style=\"color:#C00\">video cannot save error select type</h1>";
	break;
	}
}
function apply_type_http($s,$id)
 {
	 global $wpdb;
	  
	 $save_or_no= $wpdb->update($wpdb->prefix.'Spider_Video_Player_video', array(
	 
	    'fmsUrl'      =>"",
        'url'         => esc_sql(esc_html(stripslashes($_POST["http_post_video"]))),
		'urlHtml5'    => esc_sql(esc_html(stripslashes($_POST["http_post_video_html5"]))),
        'urlHD'       => esc_sql(esc_html(stripslashes($_POST["http_post_video_UrlHD"]))),
		'urlHDHtml5'  => esc_sql(esc_html(stripslashes($_POST["http_post_video_UrlHD_html5"]))),
        'published'   =>esc_sql(esc_html(stripslashes($_POST["published"]))),
        'type'        => "http",
		'params'      =>esc_html($s),
		'title'       =>esc_sql(esc_html(stripslashes($_POST["title"]))),
		'thumb'       =>esc_sql(esc_html(stripslashes($_POST["post_image"])))
                ),
				array('id'=>$id),
				array(
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',	
				'%d',
				'%s',
				'%s',
				'%s',
				'%s'					
				)
                );
	 
				?>
				<div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
	<?php
	
 }
  function apply_type_rmtp($s,$id)
 {
		 global $wpdb;
	 	 $save_or_no= $wpdb->update($wpdb->prefix.'Spider_Video_Player_video', array(
		'fmsUrl'      => esc_sql(esc_html(stripslashes($_POST["fmsUrl"]))),
        'url'         => esc_sql(esc_html(stripslashes($_POST["url_rtmp"]))),
        'urlHD'       => esc_sql(esc_html(stripslashes($_POST["urlHD_rtmp"]))),
        'published'   =>esc_sql(esc_html(stripslashes($_POST["published"]))),
        'type'        => "rtmp",
		'params'      =>esc_html($s),
		'title'       =>esc_sql(esc_html(stripslashes($_POST["title"]))),
		'thumb'       =>esc_sql(esc_html(stripslashes($_POST["post_image"]))),
		'urlHtml5'    => '',
		'urlHDHtml5'  => ''
                ),
				array('id'=>$id),
				array(	
				'%s',
				'%s',
				'%s',
				'%d',
				'%s',	
				'%s',
				'%s',
				'%s',
				'%s',
				'%s'				
				)
				
                );
						?>
				<div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
	<?php
	
 }
  function apply_type_youtobe($s,$id) {
		 global $wpdb;
                 $url = str_replace('https', 'http', esc_sql(esc_html(stripslashes($_POST['url_youtube']))));
                  $url5 = "https://www.youtube.com/embed/".substr($_POST['url_youtube'], strpos($_POST['url_youtube'], '?v=')+3,11)."?enablejsapi=1&html5=1&controls=1&modestbranding=1&rel=0";
		 $save_or_no= $wpdb->update($wpdb->prefix.'Spider_Video_Player_video', array(
        'url'        => $url,
		'urlHD'      => esc_sql(esc_html(stripslashes($_POST["urlHD"]))),
		'fmsUrl'     => esc_sql(esc_html(stripslashes($_POST["fmsUrl"]))),
        'published'  =>esc_sql(esc_html(stripslashes($_POST["published"]))),
        'type'       => "youtube",
		'params'     =>esc_html($s),
		'title'      =>esc_sql(esc_html(stripslashes($_POST["title"]))),
		'thumb'      =>esc_sql(esc_html(stripslashes($_POST["post_image"]))),
		'urlHtml5'   => $url5,
		'urlHDHtml5' => ''
                ),
				array('id'=>$id),
				array(
				'%s',
				'%s',				
				'%s',
				'%d',
				'%s',	
				'%s',
				'%s',
				'%s',
				'%s',
				'%s'					
				)
                );
						?>
				<div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
	<?php
	
 }
  function apply_type_vimeo($s,$id) {
		 global $wpdb;
                  $url = "https://player.vimeo.com/video/".substr($_POST['url_vimeo'], strrpos($_POST['url_vimeo'], '/')+1)."?api=1";
		 $save_or_no= $wpdb->update($wpdb->prefix.'Spider_Video_Player_video', array(
        'url'        => esc_sql(esc_html(stripslashes($_POST["url_vimeo"]))),
		'urlHD'      => esc_sql(esc_html(stripslashes($_POST["urlHD"]))),
		'fmsUrl'     => esc_sql(esc_html(stripslashes($_POST["fmsUrl"]))),
        'published'  =>esc_sql(esc_html(stripslashes($_POST["published"]))),
        'type'       => "vimeo",
		'params'     =>esc_html($s),
		'title'      =>esc_sql(esc_html(stripslashes($_POST["title"]))),
		'thumb'      =>esc_sql(esc_html(stripslashes($_POST["post_image"]))),
		'urlHtml5'   => $url,
		'urlHDHtml5' => ''
                ),
				array('id'=>$id),
				array(
				'%s',
				'%s',				
				'%s',
				'%d',
				'%s',	
				'%s',
				'%s',
				'%s',
				'%s',
				'%s'					
				)
                );
						?>
				<div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
	<?php
	
 }
 
 
 
 function published($id) {
	 		 global $wpdb;
			 $yes_or_no=$wpdb->get_var($wpdb->prepare('SELECT published FROM '.$wpdb->prefix.'Spider_Video_Player_video WHERE `id`=%d',$id));
			 if( $yes_or_no)
			 $yes_or_no=0;
			 else
			 $yes_or_no=1;
		 $save_or_no= $wpdb->update($wpdb->prefix.'Spider_Video_Player_video', array(
        'published'  => $yes_or_no,
        
                ),
				array('id'=>$id),
				array(
				'%d',				
				)
                );
				?>
				<div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
                <?php
	 
 }
 ?>