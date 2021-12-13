<?php 
if(function_exists('current_user_can')){
	if(!current_user_can('manage_options')) {
	die('Access Denied');
}	
} else {
	die('Access Denied');
}
function add_playlist(){
  wp_admin_css('thickbox');
	$lists['published'] =  '<input type="radio" name="published" id="published0" value="0" class="inputbox">
							<label for="published0">No</label>
							<input type="radio" name="published" id="published1" value="1" checked="checked" class="inputbox">
							<label for="published1">Yes</label>';
		
// display function
	html_add_playlist($lists);
}
function show_playlist(){
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
		$where= ' WHERE title LIKE "%'.$search_tag.'%"';
	}
	
	
	
	// get the total number of records
	$query = "SELECT COUNT(*) FROM ".$wpdb->prefix."Spider_Video_Player_playlist". $where;
	$total = $wpdb->get_var($query);
	$pageNav['total'] =$total;
	$pageNav['limit'] =	 $limit/20+1;
	
	$query = "SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_playlist".$where." ". $order." "." LIMIT ".$limit.",20";
	if($sort["sortid_by"] == 'videos')
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
					$order=" ASC";
				}
				else
				{
					$order=" DESC";
				}
			}
	$query = 'SELECT *, (LENGTH(  `videos` ) - LENGTH( REPLACE(  `videos` ,  ",",  "" ) )) AS video_count FROM '.$wpdb->prefix.'Spider_Video_Player_playlist'. $where. ' ORDER BY  `video_count` '.$order." LIMIT ".$limit.",20";
	}
	$rows = $wpdb->get_results($query);	    	
	html_show_playlist($rows, $pageNav, $sort);
}
function save_playlist(){
	global $wpdb;
	$save_or_no= $wpdb->insert($wpdb->prefix.'Spider_Video_Player_playlist', array(
		'id'	    => NULL,
        'title'     => esc_sql(esc_html(stripslashes($_POST["title"]))),
        'thumb'     => esc_sql(esc_html(stripslashes($_POST["thumb"]))),
        'published' => esc_sql(esc_html(stripslashes($_POST["published"]))),
        'videos'    => esc_sql(esc_html(stripslashes($_POST["videos"])))
                ),
				array(
				'%d',
				'%s',
				'%s',
				'%d',
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
function Apply_playlist($id)
{
	global $wpdb;
	$save_or_no= $wpdb->update($wpdb->prefix.'Spider_Video_Player_playlist', array(
		
        'title'       => esc_sql(esc_html(stripslashes($_POST["title"]))),
        'thumb'       => esc_sql(esc_html(stripslashes($_POST["thumb"]))),
        'published'   => esc_sql(esc_html(stripslashes($_POST["published"]))),
        'videos'      => esc_sql(esc_html(stripslashes($_POST["videos"])))
                ),
				array('id'	=> $id),
				array(
				'%s',
				'%s',
				'%d',
				'%s'				
				)
                );
	
	?>
	<div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
	<?php
	
    return true;
}
function edit_playlist($id){
	global $wpdb;
	
	if(!$id)
	{
		$id=$wpdb->get_var("SELECT MAX( id ) FROM ".$wpdb->prefix."Spider_Video_Player_playlist");
	}
	wp_admin_css('thickbox');
	$row=$wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_playlist WHERE `id`=%d",$id));
	$published0="";
	$published1="";
	if($row->published)
	{
		$published1='checked="checked"';
	}
	else
	{
		$published0='checked="checked"';
	}
	
	$lists['published'] =  '<input type="radio" name="published" id="published0" value="0" '.$published0.' class="inputbox">
							<label for="published0">No</label>
							<input type="radio" name="published" id="published1" value="1" '.$published1.' class="inputbox">
							<label for="published1">Yes</label>';
	$viedos=array();
	$videos_id=explode(',',$row->videos);
	$videos_id= array_slice($videos_id,0, count($videos_id)-1); 	
	foreach($videos_id as $video_id)
	{
		$query =$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_video WHERE id =%d",$video_id) ;
		$viedos[] = $wpdb->get_row($query);
	}
	// display function 
	
	html_edit_playlist($row,$viedos,$lists);
}
function change_tag( $id ){
  global $wpdb;
  $published=$wpdb->get_var($wpdb->prepare("SELECT published FROM ".$wpdb->prefix."Spider_Video_Player_playlist WHERE `id`=%d",$id ));
  if($published)
   $published=0;
  else
   $published=1;
  $savedd=$wpdb->update($wpdb->prefix.'Spider_Video_Player_playlist', array(
			'published'    =>$published,
              ), 
              array('id'=>$id),
			  array(  '%d' )
			  );
	if(!$savedd)
	{
		?>
	<div class="error"><p><strong><?php _e('Error. Please install plugin again'); ?></strong></p></div>
	<?php
		return false;
	}
	?>
	<div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
	<?php
	
    return true;
}
function remove_playlist($id){
   global $wpdb;
 $sql_remov_tag=$wpdb->prepare("DELETE FROM ".$wpdb->prefix."Spider_Video_Player_playlist WHERE id=%d",$id);
 if(!$wpdb->query($sql_remov_tag))
 {
	  ?>
	  <div id="message" class="error"><p>Spider_Video_Player Playlist Not Deleted</p></div>
      <?php
	 
 }
 else{
 ?>
 <div class="updated"><p><strong><?php _e('Item Deleted.' ); ?></strong></p></div>
 <?php
 }
}
?>