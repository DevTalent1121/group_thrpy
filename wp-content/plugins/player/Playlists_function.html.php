<?php   
if(function_exists('current_user_can')){
	if(!current_user_can('manage_options')) {
	die('Access Denied');
}	
} else {
	die('Access Denied');
}
function html_add_playlist($lists){
	
	
		$path=plugins_url("images",__FILE__);
		?>
<script language="javascript" type="text/javascript">
function submitbutton(pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'cancel_playlist') 
	{
		submitform( pressbutton );
		return;
	}
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
var next=0;
function jSelectVideoS(VIDS, title, type, url, thumb, trackid) {
		video_ids =document.getElementById('videos').value;
		tbody = document.getElementById('video_list');
		for(i=0; i<VIDS.length; i++)
		{
			tr = document.createElement('tr');
				tr.setAttribute('video_id', VIDS[i]);
				tr.setAttribute('id', next);
			var td_info = document.createElement('td');
				td_info.setAttribute('id','info_'+next);
			b = document.createElement('b');
			b.innerHTML = title[i];
			p_info = document.createElement('p');
			p_info.style.fontStyle="normal";
			p_info.style.color="#666";
			p_info.innerHTML ='Type: '+type[i]+'<br />'+'Url: '+url[i];
			img = document.createElement('img');
			img.setAttribute('align','left');
			if(thumb[i])
			{
				img.setAttribute('height','100');
			}
			else			
			{
				img.setAttribute('width','0');
				img.setAttribute('height','0');
			}
			img.src = thumb[i];
			img.style.marginRight="10px";
			td_info.appendChild(img);
			td_info.appendChild(b);
			td_info.appendChild(p_info);
			var img_X = document.createElement("img");
					img_X.setAttribute("src", "<?php echo plugins_url("images/delete_el.png",__FILE__) ?>");
					img_X.style.cssText = "cursor:pointer; margin-left:30px";
					img_X.setAttribute("onclick", 'remove_row("'+next+'")');
			var td_X = document.createElement("td");
					td_X.setAttribute("id", "X_"+next);
					td_X.setAttribute("valign", "middle");
					td_X.style.width='50px';
					td_X.appendChild(img_X);
			var img_UP = document.createElement("img");
					img_UP.setAttribute("src", "<?php echo plugins_url("images/up.png",__FILE__) ?>");
					img_UP.style.cssText = "cursor:pointer";
					img_UP.setAttribute("onclick", 'up_row("'+next+'")');
			var td_UP = document.createElement("td");
					td_UP.setAttribute("id", "up_"+next);
					td_UP.setAttribute("valign", "middle");
					td_UP.style.width='20px';
					td_UP.appendChild(img_UP);
			var img_DOWN = document.createElement("img");
					img_DOWN.setAttribute("src", "<?php echo plugins_url("images/down.png",__FILE__) ?>");
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
		
		document.getElementById('videos').value=video_ids;
		tb_remove();
		refresh_();
	}
function remove_row(id)
{
	tr=document.getElementById(id);
	tr.parentNode.removeChild(tr);
	refresh_();
}
function refresh_()
{
	video_list=document.getElementById('video_list');
	GLOBAL_tbody=video_list;
	tox='';
	for (x=0; x < GLOBAL_tbody.childNodes.length; x++)
	{
		tr=GLOBAL_tbody.childNodes[x];
		tox=tox+tr.getAttribute('video_id')+',';
	}
	document.getElementById('videos').value=tox;
}
function up_row(id)
{
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
function down_row(id)
{
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
jQuery(function() {
	var formfield=null;
	window.original_send_to_editor = window.send_to_editor;
	window.send_to_editor = function(html){
		if (formfield) {
			var fileurl = jQuery('img',html).attr('src');
			if(fileurl)
			{
							window.parent.document.getElementById('imagebox').src=fileurl;
							window.parent.document.getElementById('imagebox').style.display="block";
			}
			formfield.val(fileurl);
			tb_remove();
		} else {
			window.original_send_to_editor(html);
		}
		formfield=null;
	};
	jQuery('.lu_upload_button').click(function() {
 		formfield = jQuery(this).parent().parent().find(".text_input");
 		tb_show('', 'media-upload.php?type=image&TB_iframe=true');
		jQuery('#TB_overlay,#TB_closeWindowButton').bind("click",function(){formfield=null;});
		return false;
	});
	jQuery(document).keyup(function(e) {
  		if (e.keyCode == 27) formfield=null;
	});
});
</script>
<style type="text/css">
.admintable td
{
padding:15px;
border-left:1px solid #cccccc;
border-right:1px solid #cccccc;
border-bottom:1px solid #cccccc;
border-top:1px solid #cccccc;
}
</style>
<form action="admin.php?page=Spider_Video_Player_Playlists" method="post" name="adminForm" id="adminForm">
 <table width="90%">
       <tr>   
<td width="100%" style="font-size:14px; font-weight:bold"><a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-4.html" target="_blank" style="color:blue; text-decoration:none;">User Manual</a><br />
This section allows you to create video playlists. <a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-4.html" target="_blank" style="color:blue; text-decoration:none;">More...</a></td>   
        </tr>
    <tr>
  <td width="100%"><h2>Add Playlist</h2></td>
  <td align="right"><input type="button" onclick="submitbutton('Save')" value="Save" class="button-secondary action"> </td>  
  <td align="right"><input type="button" onclick="submitbutton('Apply')" value="Apply" class="button-secondary action"> </td> 
  <td align="right"><input type="button" onclick="window.location.href='admin.php?page=Spider_Video_Player_Playlists'" value="Cancel" class="button-secondary action"> </td> 
  </tr>
  </table>
<table class="admintable" cellspacing="0">
				<tr>
					<td class="key" style="width:200px"> 
						<label for="title">
							<?php echo 'Title'; ?>:
						</label>
					</td>
					<td >
                                    <input type="text" name="title" id="title" size="80" />
					</td>
				</tr>
                
                
				<tr>
					<td class="key">
						<label for="videos">
							<?php echo 'VIDEOS'; ?>:
						</label>
					</td>
					<td  style="width:1000px" >
                   <?php wp_enqueue_script( 'theme-preview' ); ?>
<a href="<?php echo admin_url('admin-ajax.php?action=spiderVeideoPlayerselectvideo') ?>&post_id=270&amp;TB_iframe=1&amp;width=1024&amp;height=394" class="thickbox thickbox-preview" id="content-add_media" title="Add Media" onclick="return false;"><img src="<?php echo plugins_url("images/add_but.png",__FILE__) ?>" style="border:none;" ></a>
<table width="100%">
<tbody id="video_list"></tbody>
</table>
<input type="hidden" name="videos" id="videos" size="80" />
					</td>
				</tr>
                
                <tr>
					<td class="key">
						<label for="Thumb">
							<?php echo  'Thumb'; ?>:
						</label>
					</td>
                	<td>
					<input type="text" value="" name="thumb" id="post_image" class="text_input" style="width:137px"/><a class="button lu_upload_button" href="#" />Select</a><br />
                        <a href="javascript:removeImage();">Remove Image</a><br />
                                     <div style="height:150px;">
                                               <img style="display:none;border:none;" height="150" id="imagebox" src="" />     
                                     </div>     
                        <script type="text/javascript">    
                        function removeImage()
                        {
										document.getElementById("post_image").value='';
                                        document.getElementById("imagebox").style.display="none";
                                        document.getElementById("watermarkUrl").value='';
                        }
                        </script>    
                  </td>				
             </tr>		
             
             
             		
				<tr>
					<td class="key">
						<label for="published">
							<?php echo 'Published'; ?>:
						</label>
					</td>
					<td >
						<?php
                        echo $lists['published'];
						?>
					</td>
				</tr>                
 </table>    
    <?php wp_nonce_field('nonce_sp_vid', 'nonce_sp_vid'); ?>          
    <input type="hidden" name="option" value="com_Spider_Video_Player" />
    <input type="hidden" name="task" value="" />
</form>
<?php
		
	
}
function html_show_playlist( $rows, $pageNav, $sort){
		
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
    <form method="post" onkeypress="doNothing()" action="admin.php?page=Spider_Video_Player_Playlists" id="admin_form" name="admin_form">
	<?php $sp_vid_nonce = wp_create_nonce('nonce_sp_vid'); ?>
	<table cellspacing="10" width="100%">
     <tr>   
<td width="100%" style="font-size:14px; font-weight:bold"><a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-4.html" target="_blank" style="color:blue; text-decoration:none;">User Manual</a><br />
This section allows you to create video playlists. <a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-4.html" target="_blank" style="color:blue; text-decoration:none;">More...</a></td>   
   </tr>
    <tr>
    <td style="width:80px">
    <?php echo "<h2 style=\"float:left\">".'Playlists'. "</h2>"; ?>
     <input type="button" value="Add a Playlist" style="float:left; position:relative; top:10px; margin-left:20px" class="button-secondary action" name="custom_parametrs" onclick="window.location.href='admin.php?page=Spider_Video_Player_Playlists&task=add_playlist'" /> 
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
	$serch_fields='
            <div class="alignleft actions" style="width:180px;">
        <input type="text" name="search_events_by_title" value="'.$serch_value.'" id="search_events_by_title" onchange="clear_serch_texts()">
    </div>
	<div class="alignleft actions">
   		<input type="button" value="Search" onclick="document.getElementById(\'page_number\').value=\'1\'; document.getElementById(\'serch_or_not\').value=\'search\';
		 document.getElementById(\'admin_form\').submit();" class="button-secondary action">
		 <input type="button" value="Reset" onclick="window.location.href=\'admin.php?page=Spider_Video_Player_Playlists\'" class="button-secondary action">
    </div>';
	 print_html_nav($pageNav['total'],$pageNav['limit'],$serch_fields);	
	
	?>
  <table class="wp-list-table widefat fixed pages" style="width:95%">
 <thead>
 <TR>
 <th scope="col" id="id" class="<?php if($sort["sortid_by"]=="id") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="width:110px" ><a href="javascript:ordering('id',<?php if($sort["sortid_by"]=="id") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>ID</span><span class="sorting-indicator"></span></a></th>
 <th scope="col" id="title" class="<?php if($sort["sortid_by"]=="title") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="" ><a href="javascript:ordering('title',<?php if($sort["sortid_by"]=="title") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>Title</span><span class="sorting-indicator"></span></a></th>
 <th scope="col" id="videos" class="<?php if($sort["sortid_by"]=="videos") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="width:161px" ><a href="javascript:ordering('videos',<?php if($sort["sortid_by"]=="videos") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>Number of Videos</span><span class="sorting-indicator"></span></a></th>
  <th scope="col" id="published" class="<?php if($sort["sortid_by"]=="published") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="width:87px" ><a href="javascript:ordering('published',<?php if($sort["sortid_by"]=="published") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>Published</span><span class="sorting-indicator"></span></a></th>
 <th style="width:80px">Edit</th>
 <th style="width:80px">Delete</th>
 </TR>
 </thead>
 <tbody>
 <?php for($i=0; $i<count($rows);$i++){ ?>
 <tr>
         <td><?php echo $rows[$i]->id; ?></td>
         <td><a  href="admin.php?page=Spider_Video_Player_Playlists&task=edit_playlist&id=<?php echo $rows[$i]->id?>"><?php echo $rows[$i]->title; ?></a></td>
         <td><?php echo count(explode(",",$rows[$i]->videos))-1; ?></td>
         <td><a  href="admin.php?page=Spider_Video_Player_Playlists&task=unpublish_playlist&id=<?php echo $rows[$i]->id?>&_wpnonce=<?php echo $sp_vid_nonce; ?>"<?php if(!$rows[$i]->published){ ?> style="color:#C00;" <?php }?> ><?php if($rows[$i]->published)echo "Yes"; else echo "No"; ?></a></td>
         <td><a  href="admin.php?page=Spider_Video_Player_Playlists&task=edit_playlist&id=<?php echo $rows[$i]->id?>">Edit</a></td>
         <td><a  href="#" href-data="admin.php?page=Spider_Video_Player_Playlists&task=remove_playlist&id=<?php echo $rows[$i]->id?>&_wpnonce=<?php echo $sp_vid_nonce; ?>">Delete</a></td>
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
function html_edit_playlist($row,$videos,$lists){
	
		$path=plugins_url("images",__FILE__);
		?>
<script language="javascript" type="text/javascript">
function submitbutton(pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'cancel_playlist') 
	{
		submitform( pressbutton );
		return;
	}
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
var next=0;
 function jSelectVideo(VIDS, title, type, url, thumb, trackid) {
		video_ids =document.getElementById('videos').value;
		tbody = document.getElementById('video_list');
		for(i=0; i<VIDS.length; i++)
		{
			tr = document.createElement('tr');
				tr.setAttribute('video_id', VIDS[i]);
				tr.setAttribute('id', next);
			var td_info = document.createElement('td');
				td_info.setAttribute('id','info_'+next);
			b = document.createElement('b');
			b.innerHTML = title[i];
			p_info = document.createElement('p');
			p_info.style.fontStyle="normal";
			p_info.style.color="#666";
			p_info.innerHTML ='Type: '+type[i]+'<br />'+'Url: '+url[i];
			img = document.createElement('img');
			img.setAttribute('align','left');
			if(thumb[i])
			{
				img.setAttribute('height','100');
			}
			else			
			{
				img.setAttribute('width','0');
				img.setAttribute('height','0');
			}
			img.src = thumb[i];
			img.style.marginRight="10px";
			td_info.appendChild(img);
			td_info.appendChild(b);
			td_info.appendChild(p_info);
			var img_X = document.createElement("img");
					img_X.setAttribute("src", "<?php echo plugins_url("images/delete_el.png",__FILE__) ?>");
					img_X.style.cssText = "cursor:pointer; margin-left:30px";
					img_X.setAttribute("onclick", 'remove_row("'+next+'")');
			var td_X = document.createElement("td");
					td_X.setAttribute("id", "X_"+next);
					td_X.setAttribute("valign", "middle");
					td_X.style.width='50px';
					td_X.appendChild(img_X);
			var img_UP = document.createElement("img");
					img_UP.setAttribute("src", "<?php echo plugins_url("images/up.png",__FILE__) ?>");
					img_UP.style.cssText = "cursor:pointer";
					img_UP.setAttribute("onclick", 'up_row("'+next+'")');
			var td_UP = document.createElement("td");
					td_UP.setAttribute("id", "up_"+next);
					td_UP.setAttribute("valign", "middle");
					td_UP.style.width='20px';
					td_UP.appendChild(img_UP);
			var img_DOWN = document.createElement("img");
					img_DOWN.setAttribute("src", "<?php echo plugins_url("images/down.png",__FILE__) ?>");
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
		refresh_();
	}
function jSelectVideoS(VIDS, title, type, url, thumb, trackid) {
		video_ids =document.getElementById('videos').value;
		tbody = document.getElementById('video_list');
		for(i=0; i<VIDS.length; i++)
		{
			tr = document.createElement('tr');
				tr.setAttribute('video_id', VIDS[i]);
				tr.setAttribute('id', next);
			var td_info = document.createElement('td');
				td_info.setAttribute('id','info_'+next);
			b = document.createElement('b');
			b.innerHTML = title[i];
			p_info = document.createElement('p');
			p_info.style.fontStyle="normal";
			p_info.style.color="#666";
			p_info.innerHTML ='Type: '+type[i]+'<br />'+'Url: '+url[i];
			img = document.createElement('img');
			img.setAttribute('align','left');
			if(thumb[i])
			{
				img.setAttribute('height','100');
			}
			else			
			{
				img.setAttribute('width','0');
				img.setAttribute('height','0');
			}
			img.src = thumb[i];
			img.style.marginRight="10px";
			td_info.appendChild(img);
			td_info.appendChild(b);
			td_info.appendChild(p_info);
			var img_X = document.createElement("img");
					img_X.setAttribute("src", "<?php echo plugins_url("images/delete_el.png",__FILE__) ?>");
					img_X.style.cssText = "cursor:pointer; margin-left:30px";
					img_X.setAttribute("onclick", 'remove_row("'+next+'")');
			var td_X = document.createElement("td");
					td_X.setAttribute("id", "X_"+next);
					td_X.setAttribute("valign", "middle");
					td_X.style.width='50px';
					td_X.appendChild(img_X);
			var img_UP = document.createElement("img");
					img_UP.setAttribute("src", "<?php echo plugins_url("images/up.png",__FILE__) ?>");
					img_UP.style.cssText = "cursor:pointer";
					img_UP.setAttribute("onclick", 'up_row("'+next+'")');
			var td_UP = document.createElement("td");
					td_UP.setAttribute("id", "up_"+next);
					td_UP.setAttribute("valign", "middle");
					td_UP.style.width='20px';
					td_UP.appendChild(img_UP);
			var img_DOWN = document.createElement("img");
					img_DOWN.setAttribute("src", "<?php echo plugins_url("images/down.png",__FILE__) ?>");
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
		document.getElementById('videos').value=video_ids;
		tb_remove();
		refresh_();
	}
function remove_row(id)
{
	tr=document.getElementById(id);
	tr.parentNode.removeChild(tr);
	refresh_();
}
function refresh_()
{
	video_list=document.getElementById('video_list');
	GLOBAL_tbody=video_list;
	tox='';
	for (x=0; x < GLOBAL_tbody.childNodes.length; x++)
	{
		tr=GLOBAL_tbody.childNodes[x];
		tox=tox+tr.getAttribute('video_id')+',';
	}
	document.getElementById('videos').value=tox;
}
function up_row(id)
{
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
function down_row(id)
{
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
jQuery(function() {
	var formfield=null;
	window.original_send_to_editor = window.send_to_editor;
	window.send_to_editor = function(html){
		if (formfield) {
			
			var fileurl = jQuery('img',html).attr('src');
			if(fileurl)
			{
							window.parent.document.getElementById('imagebox').src=fileurl;
							window.parent.document.getElementById('imagebox').style.display="block";
			}
			formfield.val(fileurl);
			tb_remove();
		} else {
			window.original_send_to_editor(html);
		}
		formfield=null;
	};
	jQuery('.lu_upload_button').click(function() {
 		formfield = jQuery(this).parent().parent().find(".text_input");
 		tb_show('', 'media-upload.php?type=image&TB_iframe=true');
		jQuery('#TB_overlay,#TB_closeWindowButton').bind("click",function(){formfield=null;});
		return false;
	});
	jQuery(document).keyup(function(e) {
  		if (e.keyCode == 27) formfield=null;
	});
});
</script>
<style type="text/css">
.admintable td
{
padding:15px;
border-left:1px solid #cccccc;
border-right:1px solid #cccccc;
border-bottom:1px solid #cccccc;
border-top:1px solid #cccccc;
}
</style>
<form action="admin.php?page=Spider_Video_Player_Playlists<?php echo "&id=".$row->id?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
 <table width="90%">
    <tr>
          <tr>   
<td width="100%" style="font-size:14px; font-weight:bold"><a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-4.html" target="_blank" style="color:blue; text-decoration:none;">User Manual</a><br />
This section allows you to create video playlists. <a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-4.html" target="_blank" style="color:blue; text-decoration:none;">More...</a></td>   
        </tr>
  <td width="100%"><h2>Add Playlist</h2></td>
  <td align="right"><input type="button" onclick="submitbutton('Save')" value="Save" class="button-secondary action"> </td>  
  <td align="right"><input type="button" onclick="submitbutton('Apply')" value="Apply" class="button-secondary action"> </td> 
  <td align="right"><input type="button" onclick="window.location.href='admin.php?page=Spider_Video_Player_Playlists'" value="Cancel" class="button-secondary action"> </td> 
  </tr>
  </table>
<table class="admintable">
				<tr>
					<td class="key" style="width:200px">
						<label for="title">
							<?php echo 'Title'; ?>:
						</label>
					</td>
					<td >
                                    <input type="text" name="title" id="title" size="80" value="<?php echo $row->title ?>" />
					</td>
				</tr>
                
                
				<tr>
					<td class="key">
						<label for="videos">
							<?php echo 'VIDEOS'; ?>:
						</label>
					</td>
					<td  style="width:1000px" >
                   <?php wp_enqueue_script( 'theme-preview' ); ?>
<a href="<?php echo admin_url('admin-ajax.php?action=spiderVeideoPlayerselectvideo') ?>&post_id=270&amp;TB_iframe=1&amp;width=1024&amp;height=394" class="thickbox thickbox-preview" id="content-add_media" title="Add Media" onclick="return false;"><img src="<?php echo plugins_url("images/add_but.png",__FILE__) ?>" style="border:none;"></a>
<table width="100%">
<tbody id="video_list"></tbody>
</table>
<input type="hidden" name="videos" id="videos" size="80" />
					</td>
				</tr>
                
                <tr>
					<td class="key">
						<label for="Thumb">
							<?php echo  'Thumb'; ?>:
						</label>
					</td>
                	<td>
					<input type="text" value="<?php if($row->thumb )echo htmlspecialchars($row->thumb); ?>" name="thumb" id="post_image" class="text_input" style="width:137px"/><a class="button lu_upload_button" href="#" />Select</a><br />
                        <a href="javascript:removeImage();">Remove Image</a><br />
                                     <div style="height:150px;">
                                               <img style="display:<?php if($row->thumb=='') echo 'none'; else echo 'block' ?>; border:none;" height="150" id="imagebox" src="<?php echo htmlspecialchars($row->thumb); ?>" />     
                                     </div>     
                        <script type="text/javascript">    
                        function removeImage()
                        {
										document.getElementById("post_image").value='';
                                        document.getElementById("imagebox").style.display="none";
                                        document.getElementById("watermarkUrl").value='';
                        }
                        </script>    
                  </td>				
             </tr>		
             
             
             		
				<tr>
					<td class="key">
						<label for="published">
							<?php echo 'Published'; ?>:
						</label>
					</td>
					<td >
						<?php
                        echo $lists['published'];
						?>
					</td>
				</tr>                
 </table>    
    <?php wp_nonce_field('nonce_sp_vid', 'nonce_sp_vid'); ?>        
    <input type="hidden" name="option" value="com_Spider_Video_Player" />
    <input type="hidden" name="task" value="" />
		
	
<?php
if($videos)
{
	foreach($videos as $video)
	{
		$v_ids[]=$video->id;
		$v_titles[]=addslashes($video->title);
		$v_types[]=$video->type;
		$v_urls[]=addslashes($video->url);
		$v_thumbs[]=addslashes($video->thumb);
		$v_trackIds[]=isset($video->trackId) ? $video->trackId : "";
	}
	$v_id='["'.implode('","',$v_ids).'"]';
	$v_title='["'.implode('","',$v_titles).'"]';
	$v_type='["'.implode('","',$v_types).'"]';
	$v_url='["'.implode('","',$v_urls).'"]';
	$v_thumb='["'.implode('","',$v_thumbs).'"]';
	$v_trackId='["'.implode('","',$v_trackIds).'"]';
	}?>
<script type="text/javascript">                
jSelectVideo(<?php echo $v_id?>,<?php echo $v_title?>,<?php echo $v_type?>,<?php echo $v_url?>,<?php echo $v_thumb?>,<?php echo $v_trackId?>);
</script>
<?php
?>
	<?php wp_nonce_field('nonce_sp_vid', 'nonce_sp_vid'); ?>
    <input type="hidden" name="option" value="com_Spider_Video_Player" />
    <input type="hidden" name="id" value="<?php echo $row->id?>" />        
    <input type="hidden" name="cid[]" value="<?php echo $row->id; ?>" />        
    <input type="hidden" name="task" value="" />        
</form>
<?php
		
}
 
?>