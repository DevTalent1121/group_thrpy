<?php   
if(function_exists('current_user_can')){
	if(!current_user_can('manage_options')) {
	die('Access Denied');
}	
} else {
	die('Access Denied');
}
function html_add_Spider_Video_Player(){
	global $wpdb;
	$themes=$wpdb->get_results("SELECT `id`,`title`,`default` FROM ".$wpdb->prefix."Spider_Video_Player_theme");
	$priority=1;
	if(isset($_GET["id"]))
	{
		$row=$wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_player WHERE id=%d",$_GET["id"]));
		$value=$row->playlist;
		$title=$row->title;
		$id_for_team=$row->theme;		
		$priority=$row->priority;
		$id=$_GET["id"];
	}
	else
	{
		if(isset($_GET["task"]))
		{
			if($_GET["task"]=="Apply")
			{
		$id=$wpdb->get_var("SELECT MAX( id ) FROM ".$wpdb->prefix."Spider_Video_Player_player");
		$row=$wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_player WHERE id=%d",$id));
		$value=$row->playlist;
		$priority=$row->priority;
		$title=$row->title;
		$id_for_team=$row->theme;
			}
			else{
				for($i=0;$i<count($themes);$i++)
					{
						if($themes[$i]->default==1)
						$id_for_team=$themes[$i]->id;
					}
			$value="";
			$id=0;
			$title="";
		}
		}
		
		else{
				for($i=0;$i<count($themes);$i++)
					{
						if($themes[$i]->default==1)
						$id_for_team=$themes[$i]->id;
					}
		$value="";
		$id=0;
		$title="";
		}
	
	}
	?>
	
	
	<script type="text/javascript">
var next=0;
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
function jSelectVideo(VIDS, title, thumb, number_of_vids) {
		playlist_ids =document.getElementById('playlists').value;
		tbody = document.getElementById('playlist');
		for(i=0; i<VIDS.length; i++)
		{
			tr = document.createElement('tr');
				tr.setAttribute('playlist_id', VIDS[i]);
				tr.setAttribute('id', next);
			var td_info = document.createElement('td');
				td_info.setAttribute('id','info_'+next);
			b = document.createElement('b');
				b.innerHTML = title[i];
				b.style.width='50px';
				b.style.position="inherit";
			p_info = document.createElement('p');
				p_info.style.fontStyle="normal";
				p_info.style.color="#666";
				p_info.innerHTML ='Number of videos: '+number_of_vids[i];
			img = document.createElement('img');
			img.setAttribute('align','left');
			img.setAttribute('height','100');
			img.src = thumb[i];
			img.style.marginRight="10px";
			td_info.appendChild(img);
			td_info.appendChild(b);
			td_info.appendChild(p_info);
			var img_X = document.createElement("img");
					img_X.setAttribute("src", "<?php echo plugins_url("images/delete_el.png",__FILE__); ?>");
					img_X.style.cssText = "cursor:pointer; margin-left:30px";
					img_X.setAttribute("onclick", 'remove_row("'+next+'")');
			var td_X = document.createElement("td");
					td_X.setAttribute("id", "X_"+next);
					td_X.setAttribute("valign", "middle");
					td_X.style.width='50px';
					td_X.appendChild(img_X);
			var img_UP = document.createElement("img");
					img_UP.setAttribute("src", "<?php echo plugins_url("images/up.png",__FILE__); ?>");
					img_UP.style.cssText = "cursor:pointer";
					img_UP.setAttribute("onclick", 'up_row("'+next+'")');
			var td_UP = document.createElement("td");
					td_UP.setAttribute("id", "up_"+next);
					td_UP.setAttribute("valign", "middle");
					td_UP.style.width='20px';
					td_UP.appendChild(img_UP);
			var img_DOWN = document.createElement("img");
					img_DOWN.setAttribute("src", "<?php echo plugins_url("images/down.png",__FILE__); ?>");
					img_DOWN.style.cssText = "margin:2px;cursor:pointer";
					img_DOWN.setAttribute("onclick", 'down_row("'+next+'")');
			var td_DOWN = document.createElement("td");
					td_DOWN.setAttribute("id", "down_"+next);
					td_DOWN.setAttribute("valign", "middle");
					td_DOWN.style.width='20px';
					td_DOWN.appendChild(img_DOWN);
			tr.appendChild(td_info);
			tr.appendChild(td_X);
			tr.appendChild(td_UP);
			tr.appendChild(td_DOWN);
			tbody.appendChild(tr);
			next++;
		}
		document.getElementById('playlists').value=playlist_ids;
		refresh_();
	}
function jSelectVideoS(VIDS, title, thumb, number_of_vids) {
		playlist_ids =document.getElementById('playlists').value;
		tbody = document.getElementById('playlist');
		for(i=0; i<VIDS.length; i++)
		{
			tr = document.createElement('tr');
				tr.setAttribute('playlist_id', VIDS[i]);
				tr.setAttribute('id', next);
			var td_info = document.createElement('td');
				td_info.setAttribute('id','info_'+next);
			b = document.createElement('b');
				b.innerHTML = title[i];
				b.style.width='50px';
				b.style.position="inherit";
			p_info = document.createElement('p');
				p_info.style.fontStyle="normal";
				p_info.style.color="#666";
				p_info.innerHTML ='Number of videos: '+number_of_vids[i];
			img = document.createElement('img');
			img.setAttribute('align','left');
			img.setAttribute('height','100');
			img.src = thumb[i];
			img.style.marginRight="10px";
			td_info.appendChild(img);
			td_info.appendChild(b);
			td_info.appendChild(p_info);
			var img_X = document.createElement("img");
					img_X.setAttribute("src", "<?php echo plugins_url("images/delete_el.png",__FILE__); ?>");
					img_X.style.cssText = "cursor:pointer; margin-left:30px";
					img_X.setAttribute("onclick", 'remove_row("'+next+'")');
			var td_X = document.createElement("td");
					td_X.setAttribute("id", "X_"+next);
					td_X.setAttribute("valign", "middle");
					td_X.style.width='50px';
					td_X.appendChild(img_X);
			var img_UP = document.createElement("img");
					img_UP.setAttribute("src", "<?php echo plugins_url("images/up.png",__FILE__); ?>");
					img_UP.style.cssText = "cursor:pointer";
					img_UP.setAttribute("onclick", 'up_row("'+next+'")');
			var td_UP = document.createElement("td");
					td_UP.setAttribute("id", "up_"+next);
					td_UP.setAttribute("valign", "middle");
					td_UP.style.width='20px';
					td_UP.appendChild(img_UP);
			var img_DOWN = document.createElement("img");
					img_DOWN.setAttribute("src", "<?php echo plugins_url("images/down.png",__FILE__); ?>");
					img_DOWN.style.cssText = "margin:2px;cursor:pointer";
					img_DOWN.setAttribute("onclick", 'down_row("'+next+'")');
			var td_DOWN = document.createElement("td");
					td_DOWN.setAttribute("id", "down_"+next);
					td_DOWN.setAttribute("valign", "middle");
					td_DOWN.style.width='20px';
					td_DOWN.appendChild(img_DOWN);
			tr.appendChild(td_info);
			tr.appendChild(td_X);
			tr.appendChild(td_UP);
			tr.appendChild(td_DOWN);
			tbody.appendChild(tr);
			next++;
		}
		document.getElementById('playlists').value=playlist_ids;
		tb_remove();
		refresh_();
	}
function remove_row(id){
	tr=document.getElementById(id);
	tr.parentNode.removeChild(tr);
	refresh_();
}
function refresh_(){
	playlist=document.getElementById('playlist');
	GLOBAL_tbody=playlist;
	tox='';
	for (x=0; x < GLOBAL_tbody.childNodes.length; x++)
	{
		tr=GLOBAL_tbody.childNodes[x];
		tox=tox+tr.getAttribute('playlist_id')+',';
	}
	document.getElementById('playlists').value=tox;
}
function up_row(id){
	form=document.getElementById(id).parentNode;
	k=0;
	while(form.childNodes[k])
	{
	if(form.childNodes[k].getAttribute("id"))
	if(id==form.childNodes[k].getAttribute("id"))
		break;
	k++;
	}
	if(k!=0)
	{
		up=form.childNodes[k-1];
		down=form.childNodes[k];
		form.removeChild(down);
		form.insertBefore(down, up);
		refresh_();
	}
}
function down_row(id){
	form=document.getElementById(id).parentNode;
	l=form.childNodes.length;
	k=0;
	while(form.childNodes[k])
	{
	if(id==form.childNodes[k].id)
		break;
	k++;
	}
	if(k!=l-1)
	{
		up=form.childNodes[k];
		down=form.childNodes[k+2];
		form.removeChild(up);
if(!down)
down=null;
		form.insertBefore(up, down);
		refresh_();
	}
}
function submitbutton(pressbutton) {
	var form = document.adminForm;	
	if(form.title.value=="")
	{
		alert('Set Playlist title');
		return;
	}
	submitform( pressbutton );
}
function submitform(pressbutton)
{
	document.getElementById("adminForm").action=document.getElementById("adminForm").action+"&task="+pressbutton;
	document.getElementById("adminForm").submit();
}
function change_player_type(type) {
	if(type==="html"){
		document.getElementById("flash_note").style.display="none";
	}else {
		document.getElementById("flash_note").removeAttribute('style');
	}
}
window.onload = function () {
	<?php
	if($priority==0){

		echo "document.getElementById('flash_note').removeAttribute('style');";
	}?>
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
#flash_note{
	display: inline-block;
	margin-left: 30px;
	color: red;
	line-height: 0px;
}
</style>
<form action="admin.php?page=Spider_Video_Player<?php if($id) echo "&id=".$id; ?>" onkeypress="doNothing()" method="post" name="adminForm" id="adminForm">
<input type="hidden" name="params" id="playlists" value="<?php echo $value; ?>">
<table width="90%">
<tr>   
<td style="font-size:14px; font-weight:bold;"><a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-6.html" target="_blank" style="color:blue; text-decoration:none;"><a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-6.html" target="_blank" style="color:blue; text-decoration:none;">User Manual</a></a><br />
This section allows you to create players, providing them with playlist(s) and a distinct visual theme. <a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-6.html" target="_blank" style="color:blue; text-decoration:none;">More...</a></td>   
        </tr>
    <tr>
  <td width="100%"><h2><?php if($title) echo $title; else echo "Add Spider Video Player"; ?></h2></td>
  <td align="right"><input type="button" onclick="submitbutton('Save')" value="Save" class="button-secondary action"> </td>  
  <td align="right"><input type="button" onclick="submitbutton('Apply')" value="Apply" class="button-secondary action"> </td> 
  <td align="right"><input type="button" onclick="window.location.href='admin.php?page=Spider_Video_Player'" value="Cancel" class="button-secondary action"> </td> 
  </tr>
  </table>
<table class="admintable" cellspacing="0">
</tr>
<tr>
<td>Priority</td>
<td>
	<input type="radio" name="priority" value="1" onchange="change_player_type('html')" <?php if($priority == 1) echo 'checked="checked"';?>>HTML5</input>
	<input type="radio" name="priority" value="0" onchange="change_player_type('flash')" <?php if($priority == 0) echo 'checked="checked"';?>>Flash</input>
	<p id="flash_note" style="display:none">YouTube and Vimeo videos are not supported on flash player.</p>
</td>
</tr>
<tr>
<tr>
<td>Title</td>
<td>
<input type="text" name="title" id="title" value="<?php if($title) echo $title; ?>"  />
</td>
</tr>
<tr>
<td width="300px">Select Theme</td><td>
<select name="params_theme" id="paramstheme">
<?php ?>
<option value="-1" disabled="disabled">Select Theme</option>
<?php foreach($themes as $theme) { ?>
<option value="<?php echo $theme->id; ?>" <?php if($theme->id==$id_for_team) echo'selected="selected"';?>><?php echo $theme->title ?></option>
<?php } ?>
</select>
</td>
</tr>
<tr>
<td>Playlist</td>
<td>
<a href="<?php echo admin_url('admin-ajax.php?action=spiderVeideoPlayerselectplaylist') ?>&post_id=270&amp;TB_iframe=1&amp;width=1024&amp;height=394" class="thickbox thickbox-preview" id="content-add_media" title="Add Media" onclick="return false;"><img src="<?php echo plugins_url("images/add_but.png",__FILE__) ?>" style="border:none;"></a>
</td>
</tr>
</table>
<table width="100%">
<tbody id="playlist"></tbody>
</table>
<?php
	$playlists=array();
	$playlists_id=explode(',',$value);
	$playlists_id= array_slice($playlists_id,0, count($playlists_id)-1);  
	foreach($playlists_id as $playlist_id)
	{
		$query ="SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_playlist WHERE id=".$playlist_id ;
		$playlists[]=$wpdb->get_results($query);
	}
if($playlists)
{
	foreach($playlists as $playlist)
	{
		$v_ids[]=$playlist[0]->id;
		$v_titles[]=addslashes($playlist[0]->title);
		$v_thumbs[]=addslashes($playlist[0]->thumb);
		$v_number_of_vids[]=substr_count($playlist[0]->videos, ',');
	}
	$v_id='["'.implode('","',$v_ids).'"]';
	$v_title='["'.implode('","',$v_titles).'"]';
	$v_thumb='["'.implode('","',$v_thumbs).'"]';
	$v_number_of_vid='["'.implode('","',$v_number_of_vids).'"]';
	?>
    
    
   
<script type="text/javascript">                
jSelectVideo(<?php echo $v_id?>,<?php echo $v_title?>,<?php echo $v_thumb?>,<?php echo $v_number_of_vid?>);
<?php
}
?>
 </script>
 <?php wp_nonce_field('nonce_sp_vid', 'nonce_sp_vid'); ?>
 </form>
      <?php
}
function html_show_Spider_Video_Player( $rows, $pageNav, $sort){
		
	global $wpdb;
	?>
    <script language="javascript">
	function ordering(name,as_or_desc)
	{
		document.getElementById('asc_or_desc').value=as_or_desc;		
		document.getElementById('order_by').value=name;
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
    <form method="post" action="admin.php?page=Spider_Video_Player" onkeypress="doNothing()" id="admin_form" name="admin_form" >
	<?php $sp_vid_nonce = wp_create_nonce('nonce_sp_vid'); ?>
	<table cellspacing="10" width="100%">
   <tr>   
<td width="100%" style="font-size:14px; font-weight:bold"><a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-6.html" target="_blank" style="color:blue; text-decoration:none;">User Manual</a><br />
This section allows you to create players, providing them with playlist(s) and a distinct visual theme. <a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-6.html" target="_blank" style="color:blue; text-decoration:none;">More...</a></td>   
        </tr>
    <tr>
    <td style="width:210px">
    <?php echo "<h2 style=\"float:left\">".'Spider Video Players'. "</h2>"; ?>
    <input type="button" style="float:left; position:relative; top:10px; margin-left:20px" class="button-secondary action" value="Add a player" name="custom_parametrs" onclick="window.location.href='admin.php?page=Spider_Video_Player&task=add_Spider_Video_Player'" />
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
		 <input type="button" value="Reset" onclick="window.location.href=\'admin.php?page=Spider_Video_Player\'" class="button-secondary action">
    </div>';
	 print_html_nav($pageNav['total'],$pageNav['limit'],$serch_fields);	
	
	?>
  <table class="wp-list-table widefat fixed pages" style="width:95%">
 <thead>
 <tr>
 <th scope="col" id="id" class="<?php if($sort["sortid_by"]=="id") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="width:110px" ><a href="javascript:ordering('id',<?php if($sort["sortid_by"]=="id") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>ID</span><span class="sorting-indicator"></span></a></th>
 <th scope="col" id="title" class="<?php if($sort["sortid_by"]=="title") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="" ><a href="javascript:ordering('title',<?php if($sort["sortid_by"]=="title") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>Title</span><span class="sorting-indicator"></span></a></th>
 <th style="width:80px">Edit</th>
 <th style="width:80px">Delete</th>
 </tr>
 </thead>
 <tbody>
 <?php for($i=0; $i<count($rows);$i++){ ?>
 <tr>
         <td><?php echo $rows[$i]->id; ?></td>
         <td><a  href="admin.php?page=Spider_Video_Player&task=edit_Spider_Video_Player&id=<?php echo $rows[$i]->id?>"><?php echo $rows[$i]->title; ?></a></td>
         <td><a  href="admin.php?page=Spider_Video_Player&task=edit_Spider_Video_Player&id=<?php echo $rows[$i]->id?>">Edit</a></td>
         <td><a  href="#" href-data="admin.php?page=Spider_Video_Player&task=remove_Spider_Video_Player&id=<?php echo $rows[$i]->id?>&_wpnonce=<?php echo $sp_vid_nonce; ?>">Delete</a></td>
  </tr> 
 <?php } ?>
 </tbody>
 </table>
 <?php wp_nonce_field('nonce_sp_vid', 'nonce_sp_vid'); ?>
 <input type="hidden" name="asc_or_desc" id="asc_or_desc" value="<?php if(isset($_POST['asc_or_desc'])) echo esc_js(esc_html(stripslashes($_POST['asc_or_desc'])));?>"  />
 <input type="hidden" name="order_by" id="order_by" value="<?php if(isset($_POST['order_by'])) echo esc_js(esc_html(stripslashes($_POST['order_by'])));?>"  />
 <?php
?>
    
    
   
 </form>
    <?php
	}
	
	
	
	
	
	
	
 
?>