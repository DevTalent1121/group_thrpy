<?php
if(function_exists('current_user_can')){
	if(!current_user_can('manage_options')) {
	die('Access Denied');
}	
} else {
	die('Access Denied');
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////					
/////////////////////////////////////////////////////////  F U N C T I O N S    F O R     T A G S //////////////////////////////////////////////////////////////////////////////////////////				
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
////include function for pagnav for wordpress 
require_once("nav_function/nav_html_func.php");
function html_add_tag($lists){
	?>
<script language="javascript" type="text/javascript">
function submit_form(apply_or_save)
{
	document.getElementById('adminForm').action=document.getElementById('adminForm').action+"&task="+apply_or_save;
	document.getElementById('adminForm').submit();
}
				 	function doNothing() {  
var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
    if( keyCode == 13 ) {
        if(!e) var e = window.event;
        e.cancelBubble = true;
        e.returnValue = false;
        if (e.stopPropagation) {
                e.stopPropagation();
                e.preventDefault();
        }
}
}
</script>
<table width="100%">
 <tr>   
<td style="font-size:14px; font-weight:bold">
<a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-2.html" target="_blank" style="color:blue; text-decoration:none;">User Manual</a><br />
This section allows you to create tags. <br />
A tag is a keyword or term that is assigned to the video, helping to describe it and making it easier to find it by browsing or searching.<br />
Examples of tags: Year, Date, Artist, Album, Genre, etc. <a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-2.html" target="_blank" style="color:blue; text-decoration:none;">More...</a>
</td> 
</tr>
</table>
<h2>Add Tag</h2>
<form action="admin.php?page=Tags_Spider_Video_Player" onkeypress="doNothing()" method="post" name="adminForm" id="adminForm">
<table class="form-table" style="width:inherit">
				<tr>
					<td class="key">
						<label for="name">
							<?php _e( 'Name' ); ?>:
						</label>
					</td>
					<td >
                                            <input type="text" name="name" id="name" size="30" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="required">
							<?php _e( 'Required' ); ?>:
						</label>
					</td>
					<td >
                                    <?php echo $lists['required']?>
					</td>
				</tr>
                
				<tr>
					<td class="key">
						<label for="published">
							<?php _e( 'Published' ); ?>:
						</label>
					</td>
					<td >
                                    <?php echo $lists['published']?>
					</td>
				</tr>
 </table>    
 
 
        <p class="submit">
        <input type="button" value="Save" onclick="submit_form('save_tag')">
        <input type="button" value="Apply" onclick="submit_form('apply_tag')">
        <input type="button" value="Cancel" onclick="window.location.href='admin.php?page=Tags_Spider_Video_Player'">
        </p>
        
    <?php wp_nonce_field('nonce_sp_vid', 'nonce_sp_vid'); ?>   
    <input type="hidden" name="option" value="com_player" />
    <input type="hidden" name="task" value="" />
</form>
<?php
		
	
}
function html_show_tag($rows, $pageNav, $sort){
		
	global $wpdb;
	?>
    <script language="javascript">
	function ordering(name,as_or_desc)
	{
		document.getElementById('asc_or_desc').value=as_or_desc;		
		document.getElementById('order_by').value=name;
		document.getElementById('admin_form').submit();
	}
	function saveorder()
	{
		document.getElementById('saveorder').value="save";
		document.getElementById('admin_form').submit();
	}
	function listItemTask(this_id,replace_id)
	{
		document.getElementById('oreder_move').value=this_id+","+replace_id;
		document.getElementById('admin_form').submit();
	}
				 	function doNothing() {  
var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
    if( keyCode == 13 ) {
        if(!e) var e = window.event;
        e.cancelBubble = true;
        e.returnValue = false;
        if (e.stopPropagation) {
                e.stopPropagation();
                e.preventDefault();
        }
}
}
	</script>
<style type="text/css">
.admintable
{
border-right:1px solid #cccccc;
border-top:1px solid #cccccc;
}
.admintable td
{
padding:15px;
border-left:1px solid #cccccc;
border-bottom:1px solid #cccccc;
}
</style>
    <form method="post"  onkeypress="doNothing()" action="admin.php?page=Tags_Spider_Video_Player" id="admin_form" name="admin_form">
	<?php $sp_vid_nonce = wp_create_nonce('nonce_sp_vid'); ?>
	<table cellspacing="10" width="100%">
    <thead>
    
        <tr>   
<td width="100%" style="font-size:14px; font-weight:bold"><a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-2.html" target="_blank" style="color:blue; text-decoration:none;">User Manual</a><br />
This section allows you to create tags. <br />
A tag is a keyword or term that is assigned to the video, helping to describe it and making it easier to find it by browsing or searching.<br />
Examples of tags: Year, Date, Artist, Album, Genre, etc. <a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-2.html" target="_blank" style="color:blue; text-decoration:none;">More...</a>
</td> 
        </tr>
    </thead>
    <tr>
    <td  style="width:80px">
    <?php echo "<h2 style=\"float:left\">".'Tags'. "</h2>"; ?>
    <input type="button" value="Add a Tag" style="float:left; position:relative; top:10px; margin-left:20px" class="button-secondary action" name="custom_parametrs" onclick="window.location.href='admin.php?page=Tags_Spider_Video_Player&task=add_tag'" />
    </td>       
	</td>
     <td colspan="7" align="right" style="font-size:16px;">
  		<a href="https://web-dorado.com/files/fromSVP.php" target="_blank" style="color:red; text-decoration:none;">
		<img src="<?php echo plugins_url("images/header.png",__FILE__) ?>" border="0" alt="https://web-dorado.com/files/fromSVP.php" width="215">
		</a>
        </td>

        
    </tr>
    </table>
    	<label for="search_events_by_title" style="font-size:14px">Title: </label>
    <?php
	$serch_value='';
	if(isset($_POST['serch_or_not'])) {if($_POST['serch_or_not']=="search"){ $serch_value=esc_js(esc_html(stripslashes($_POST['search_events_by_title']))); }else{$serch_value="";}}
	$serch_fields='<div class="alignleft actions" style="width:180px;">
        <input type="text" name="search_events_by_title" value="'.$serch_value.'" id="search_events_by_title" onchange="clear_serch_texts()">
    </div>
	<div class="alignleft actions">
   		<input type="button" value="Search" onclick="document.getElementById(\'page_number\').value=\'1\'; document.getElementById(\'serch_or_not\').value=\'search\';
		 document.getElementById(\'admin_form\').submit();" class="button-secondary action">
		 <input type="button" value="Reset" onclick="window.location.href=\'admin.php?page=Tags_Spider_Video_Player\'" class="button-secondary action">
    </div>';
	 print_html_nav($pageNav['total'],$pageNav['limit'],$serch_fields);	
	
	?>
  <table class="wp-list-table widefat fixed pages" style="width:95%">
 <thead>
 <TR>
 <th scope="col" id="id" class="<?php if($sort["sortid_by"]=="id") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style=" width:120px" ><a href="javascript:ordering('id',<?php if($sort["sortid_by"]=="id") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>ID</span><span class="sorting-indicator"></span></a></th>
 <th scope="col" id="title" class="<?php if($sort["sortid_by"]=="name") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="" ><a href="javascript:ordering('name',<?php if($sort["sortid_by"]=="name") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>Name</span><span class="sorting-indicator"></span></a></th>
 <th scope="col" id="ordering" class="<?php if($sort["sortid_by"]=="ordering") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="width:95px;padding-left: 15px;" ><a style="display:inline" href="javascript:ordering('ordering',<?php if($sort["sortid_by"]=="ordering") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>Order</span><span class="sorting-indicator"></span></a><div><a style="display:inline" href="javascript:saveorder(1, 'saveorder')" title="Save Order"><img onclick="saveorder(1, 'saveorder')" src="<?php echo plugins_url("images/filesave.png",__FILE__) ?>" style="border:none;" alt="Save Order"></a></div></th>
  <th scope="col" id="required" class="<?php if($sort["sortid_by"]=="required") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="" ><a href="javascript:ordering('required',<?php if($sort["sortid_by"]=="required") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>Required</span><span class="sorting-indicator"></span></a></th>
  <th scope="col" id="published" class="<?php if($sort["sortid_by"]=="published") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="" ><a href="javascript:ordering('published',<?php if($sort["sortid_by"]=="published") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>Published</span><span class="sorting-indicator"></span></a></th>
 <th style="width:80px">Edit</th>
 <th style="width:80px">Delete</th>
 </TR>
 </thead>
 <tbody>
 <?php 
  for($i=0; $i<count($rows);$i++){ 
	  if(isset($rows[$i-1]->id))
		  {
		  $move_up='<span><a href="#reorder" onclick="return listItemTask(\''.$rows[$i]->id.'\',\''.$rows[$i-1]->id.'\')" title="Move Up">   <img src="'.plugins_url('images/uparrow.png',__FILE__).'" width="16" height="16" border="0" alt="Move Up" style="border:none;"></a></span>';
		  }
	  else
	  	{
			$move_up="";
	  	}
		if(isset($rows[$i+1]->id))
  		$move_down='<span><a href="#reorder" onclick="return listItemTask(\''.$rows[$i]->id.'\',\''.$rows[$i+1]->id.'\')" title="Move Down">  <img src="'.plugins_url('images/downarrow.png',__FILE__).'" width="16" height="16" border="0" alt="Move Down" style="border:none;"></a></span>';
  		else
  		$move_down="";
  		
  ?>
 <tr>
         <td><?php echo $rows[$i]->id; ?></td>
         <td><a  href="admin.php?page=Tags_Spider_Video_Player&task=edit_tag&id=<?php echo $rows[$i]->id?>"><?php echo $rows[$i]->name; ?></a></td>
         <td style="padding-left:0px;" ><?php echo  $move_up.$move_down; ?><input type="text" name="order_<?php echo $rows[$i]->id; ?>" style="width:40px" value="<?php echo $rows[$i]->ordering; ?>" /></td>
         <td><a  href="admin.php?page=Tags_Spider_Video_Player&task=required_tag&id=<?php echo $rows[$i]->id?>&_wpnonce=<?php echo $sp_vid_nonce; ?>"<?php if(!$rows[$i]->required){ ?> style="color:#C00;" <?php }?> ><?php if($rows[$i]->required)echo "Required "; else echo "NOT Required"; ?></a></td>
         <td><a  href="admin.php?page=Tags_Spider_Video_Player&task=unpublish_tag&id=<?php echo $rows[$i]->id?>&_wpnonce=<?php echo $sp_vid_nonce; ?>"<?php if(!$rows[$i]->published){ ?> style="color:#C00;" <?php }?> ><?php if($rows[$i]->published)echo "Yes"; else echo "No"; ?></a></td>
         <td><a  href="admin.php?page=Tags_Spider_Video_Player&task=edit_tag&id=<?php echo $rows[$i]->id?>">Edit</a></td>
         <td><a  href="#" href-data="admin.php?page=Tags_Spider_Video_Player&task=remove_tag&id=<?php echo $rows[$i]->id?>&_wpnonce=<?php echo $sp_vid_nonce; ?>">Delete</a></td>
  </tr> 
 <?php } ?>
 </tbody>
 </table>
 <?php wp_nonce_field('nonce_sp_vid', 'nonce_sp_vid'); ?>
 <input type="hidden" name="oreder_move" id="oreder_move" value="" />
 <input type="hidden" name="asc_or_desc" id="asc_or_desc" value="<?php if(isset($_POST['asc_or_desc'])) echo esc_js(esc_html(stripslashes($_POST['asc_or_desc'])));?>"  />
 <input type="hidden" name="order_by" id="order_by" value="<?php if(isset($_POST['order_by'])) echo esc_js(esc_html(stripslashes($_POST['order_by'])));?>"  />
 <input type="hidden" name="saveorder" id="saveorder" value="" />
 <?php
?>
    
    
   
 </form>
    <?php
	}
function html_edit_tag($lists, $row,$id){
	?>
<script language="javascript" type="text/javascript">
function submit_form(apply_or_save)
{
	document.getElementById('adminForm').action=document.getElementById('adminForm').action+"&task="+apply_or_save+"&id="+<?php echo $id ?>;
	document.getElementById('adminForm').submit();
}
				 	function doNothing() {  
var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
    if( keyCode == 13 ) {
        if(!e) var e = window.event;
        e.cancelBubble = true;
        e.returnValue = false;
        if (e.stopPropagation) {
                e.stopPropagation();
                e.preventDefault();
        }
}
}
</script>
<table width="100%">
 <tr>   
<td style="font-size:14px; font-weight:bold">
<a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-2.html" target="_blank" style="color:blue; text-decoration:none;">User Manual</a><br />
This section allows you to create tags. <br />
A tag is a keyword or term that is assigned to the video, helping to describe it and making it easier to find it by browsing or searching.<br />
Examples of tags: Year, Date, Artist, Album, Genre, etc. <a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-2.html" target="_blank" style="color:blue; text-decoration:none;">More...</a>
</td> 
</tr>
</table>
<h2>Edit Tag</h2>
<form action="admin.php?page=Tags_Spider_Video_Player" onkeypress="doNothing()" method="post" name="adminForm" id="adminForm">
<table class="form-table" style="width:inherit">
				<tr>
					<td class="key">
						<label for="name">
							<?php _e( 'Name' ); ?>:
						</label>
					</td>
					<td >
                                    <input type="text" name="name" id="name" size="30"  value="<?php  echo $row->name; ?>"/>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="required">
							<?php _e( 'Required' ); ?>:
						</label>
					</td>
					<td >
                                    <?php echo $lists['required']?>
					</td>
				</tr>
                
				<tr>
					<td class="key">
						<label for="published">
							<?php _e( 'Published' ); ?>:
						</label>
					</td>
					<td >
                                    <?php echo $lists['published']?>
					</td>
				</tr>
 </table>    
 
 
        <p class="submit">
        <input type="button" value="Save" onclick="submit_form('save_tag')">
        <input type="button" value="Apply" onclick="submit_form('apply_tag')">
        <input type="button" value="Cancel" onclick="window.location.href='admin.php?page=Tags_Spider_Video_Player'">
        </p>
        
    <?php wp_nonce_field('nonce_sp_vid', 'nonce_sp_vid'); ?>   
    <input type="hidden" name="option" value="com_player" />
    <input type="hidden" name="task" value="" />
</form>
<?php }
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////					
/////////////////////////////////////////////////////////  E N D     T A G S //////////////////////////////////////////////////////////////////////////////////////////				
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
?>