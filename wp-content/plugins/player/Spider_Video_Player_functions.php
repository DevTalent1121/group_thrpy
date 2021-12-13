<?php 
if(function_exists('current_user_can')){
	if(!current_user_can('manage_options')) {
	die('Access Denied');
}	
} else {
	die('Access Denied');
}
function add_Spider_Video_Player(){
  wp_admin_css('thickbox');
  
	$lists['published'] =  '<input type="radio" name="published" id="published0" value="0" class="inputbox">
							<label for="published0">No</label>
							<input type="radio" name="published" id="published1" value="1" checked="checked" class="inputbox">
							<label for="published1">Yes</label>';
		
// display function
	html_add_Spider_Video_Player($lists);
}
function show_Spider_Video_Player(){
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
			
			$columns = array("id", "title");
			
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
	$query = "SELECT COUNT(*) FROM ".$wpdb->prefix."Spider_Video_Player_player". $where;
	$total = $wpdb->get_var($query);
	$pageNav['total'] =$total;
	$pageNav['limit'] =	 $limit/20+1;
	
	$query = "SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_player".$where." ". $order." "." LIMIT ".$limit.",20";
	$rows = $wpdb->get_results($query);	    	
	html_show_Spider_Video_Player($rows, $pageNav, $sort);
}
function save_Spider_Video_Player(){
	global $wpdb;
	$save_or_no= $wpdb->insert($wpdb->prefix.'Spider_Video_Player_player', array(
		'id'	=> NULL,
        'title'       => esc_sql(esc_html(stripslashes($_POST["title"]))),
        'playlist'    => esc_sql(esc_html(stripslashes($_POST["params"]))),
        'theme'       => esc_sql(esc_html(stripslashes($_POST["params_theme"]))),
		'priority'    => esc_sql(esc_html(stripslashes($_POST["priority"])))
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
function Apply_Spider_Video_Player($id)
{
	global $wpdb;
	$save_or_no= $wpdb->update($wpdb->prefix.'Spider_Video_Player_player', array(
		
        'title'       => esc_sql(esc_html(stripslashes($_POST["title"]))),
        'playlist'    => esc_sql(esc_html(stripslashes($_POST["params"]))),
        'theme'       => esc_sql(esc_html(stripslashes($_POST["params_theme"]))),
		'priority'    => esc_sql(esc_html(stripslashes($_POST["priority"])))
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
function remove_Spider_Video_Player($id){
   global $wpdb;
 $sql_remov_tag=$wpdb->prepare("DELETE FROM ".$wpdb->prefix."Spider_Video_Player_player WHERE id=%d",$id);
 if(!$wpdb->query($sql_remov_tag))
 {
	  ?>
	  <div id="message" class="error"><p>Spider Video Player Not Deleted</p></div>
      <?php
	 
 }
 else{
 ?>
 <div class="updated"><p><strong><?php _e('Item Deleted.' ); ?></strong></p></div>
 <?php
 }
}
?>