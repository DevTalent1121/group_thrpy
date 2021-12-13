<?php			
if(function_exists('current_user_can')){
	if(!current_user_can('manage_options')) {
	die('Access Denied');
}	
} else {
	die('Access Denied');
}
function html_add_video($lists, $tags){
		?>
<script language="javascript" type="text/javascript">
function  submitform( pressbutton )
{
	document.getElementById("adminForm").action="admin.php?page=Spider_Video_Player_Videos&task="+pressbutton;
	document.getElementById("adminForm").submit();
}
function submitbutton(pressbutton) 
{
	var form = document.getElementById("adminForm");
	if (pressbutton == 'cancel_video') 
	{
		submitform( pressbutton );
		return;
	}
	if(form.title.value=="")
	{
		alert('Set Video title');
		return;
	}
        
<?php 
foreach($tags as $tag)
{
	if($tag->published)
		if($tag->required)
			echo '		
	if(document.getElementById("params.'.$tag->id.'").value=="")
	{
		alert("Set '.$tag->name.'");
		return;
	}
		
';
}
?>
	submitform( pressbutton );
}
function removeVideo(id)
{
				document.getElementById(id+"_link").innerHTML='Select Video';
				document.getElementById(id).value='';
}
function change_type(type)
{
        document.getElementById('vimeo_note').setAttribute('style','display:none');
        document.getElementById('youtube_note').setAttribute('style','display:none');
	switch(type)
	{
		case 'http':
		if(document.getElementById('url_http').value!='')
			document.getElementById('http_post_video').innerHTML=document.getElementById('url_http').value;
		else
			document.getElementById('http_post_video').innerHTML="";
			
			document.getElementById('url_youtube').type='hidden';
			document.getElementById('url_vimeo').type='hidden';
			document.getElementById('url_rtmp').type='hidden';
			document.getElementById('div_urlHtml5').style.display="inline";
			document.getElementById('div_urlHdHtml5').style.display="inline";
			document.getElementById('div_url').style.display="inline";
			document.getElementById('urlHD_rtmp').style.display="none";
			document.getElementById('url').type='hidden';
			document.getElementById('tr_urlHtml5').removeAttribute('style');
			document.getElementById('tr_urlHdHtml5').removeAttribute('style');
			document.getElementById('http_post_video_UrlHD').innerHTML="";
			document.getElementById('div_urlHD').style.display="inline";
			document.getElementById('urlHD').type='hidden';
			document.getElementById('urlHD').value='';
			document.getElementById('tr_fmsUrl').setAttribute('style','display:none');
			document.getElementById('tr_urlHD').removeAttribute('style');
			document.getElementById('removehd').style.display="block";	
		 break;
		case 'youtube':
			document.getElementById('tr_urlHdHtml5').style.display="none";
			document.getElementById('div_url').style.display="none";
			document.getElementById('url_youtube').type='text';
			document.getElementById('url_youtube').size='80';
			document.getElementById('url_rtmp').type='hidden';
			document.getElementById('url_vimeo').type='hidden';
			document.getElementById('tr_urlHtml5').style.display="none";
			document.getElementById('div_urlHD').style.display="none";
			document.getElementById('tr_urlHD').setAttribute('style','display:none');
			document.getElementById('urlHD').type='text';
			document.getElementById('url').style.display="none";
			document.getElementById('urlHD').size='80';
			document.getElementById('tr_fmsUrl').setAttribute('style','display:none');
			document.getElementById('youtube_note').removeAttribute('style');

			break;
                  case 'vimeo':
			document.getElementById('tr_urlHdHtml5').style.display="none";
			document.getElementById('div_url').style.display="none";
			document.getElementById('url_vimeo').type='text';
			document.getElementById('url_vimeo').size='80';
			document.getElementById('url_rtmp').type='hidden';
			document.getElementById('url_youtube').type='hidden';
			document.getElementById('tr_urlHtml5').style.display="none";
			document.getElementById('div_urlHD').style.display="none";
			document.getElementById('tr_urlHD').setAttribute('style','display:none');
			document.getElementById('urlHD').type='text';
			document.getElementById('url').style.display="none";
			document.getElementById('urlHD').size='80';
			document.getElementById('tr_fmsUrl').setAttribute('style','display:none');
			document.getElementById('vimeo_note').removeAttribute('style');
		  break;
		case 'rtmp':
			document.getElementById('div_url').style.display="none";
			document.getElementById('url_youtube').type='hidden';
			document.getElementById('url_vimeo').type='hidden';
			document.getElementById('url_rtmp').size='80';
			document.getElementById('url_rtmp').type='text';
			document.getElementById('urlHD_rtmp').type='text';
			document.getElementById('urlHD_rtmp').size='80';
			document.getElementById('div_urlHD').style.display="none";
			document.getElementById('urlHD_rtmp').style.display="block";
			document.getElementById('div_urlHtml5').style.display="none";
			document.getElementById('tr_urlHtml5').setAttribute('style','display:none');					
			document.getElementById('div_urlHdHtml5').style.display="none";
			document.getElementById('tr_urlHdHtml5').setAttribute('style','display:none');			
			document.getElementById('tr_fmsUrl').removeAttribute('style');
			document.getElementById('tr_urlHD').removeAttribute('style');
			document.getElementById('urlHD').style.display="none";
			document.getElementById('removehd').style.display="none";
		  break;
		default:
		  alert('def')
	}
	}
i=0;
function add()
{
var input_tr=document.createElement('tr');
    input_tr.setAttribute("id", "params_tr_"+i); 
var input_name_td=document.createElement('td');
var input_value_td=document.createElement('td');
var input_span_td=document.createElement('td');
var input_name=document.createElement('input');
    input_name.setAttribute("type", "text"); 
    input_name.setAttribute("name", "pname_"+i); 
    input_name.setAttribute("id", "pname_"+i); 
var input_value=document.createElement('input');
    input_value.setAttribute("type", "text"); 
    input_value.setAttribute("name", "pvalue_"+i); 
    input_value.setAttribute("id", "pvalue_"+i); 
var span=document.createElement('span');
	span.setAttribute("style", "cursor:pointer; border:1px solid black; margin-left:10px; font-size:10px"); 
	span.setAttribute("id", "span_"+i); 
	span.setAttribute("onclick", "remove_('"+i+"')"); 
   	span.innerHTML="&nbsp;X&nbsp;";
input_span_td.appendChild(span);
input_tr.appendChild(input_name_td);
input_tr.appendChild(input_value_td);
input_tr.appendChild(input_span_td);
input_name_td.appendChild(input_name);
input_value_td.appendChild(input_value);
document.getElementById("params_tbody").appendChild(input_tr);
i++;
}
function remove_(x)
{
node=document.getElementById('params_tr_'+x);
parent_=node.parentNode;
parent_.removeChild(node);
}
</script>
<script type="text/javascript">
jQuery(function() {
	var formfield=null;
	window.original_send_to_editor = window.send_to_editor;
	window.send_to_editor = function(html){
		if (formfield) {
			var fileurl = jQuery('img',html).attr('src');
			if(!fileurl)
			{
			var exploded_html;
			var exploded_html_askofen;
			exploded_html=html.split('"');
			for(i=0;i<exploded_html.length;i++)
			exploded_html_askofen=exploded_html[i].split("'");
			for(i=0;i<exploded_html.length;i++)
			{
				for(j=0;j<exploded_html_askofen.length;j++)
				{
				if(exploded_html_askofen[j].search("href"))
				{
				fileurl=exploded_html_askofen[i+1];
				break;
				}
				}
			}
			}
			else
			{
							window.parent.document.getElementById('imagebox').src=fileurl;
							window.parent.document.getElementById('imagebox').style.display="block";
							window.parent.document.getElementById('thumb').value=fileurl;
			}
			formfield.val(fileurl);
			if(!window.parent.document.getElementById('post_image').value)
			window.parent.document.getElementById('imagebox').style.display="none";
			else
			window.parent.document.getElementById('imagebox').src=window.parent.document.getElementById('post_image').value;
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
border-right:1px solid #cccccc;
border-top:1px solid #cccccc;
border-left:1px solid #cccccc;
border-bottom:1px solid #cccccc;
}
#vimeo_note,#youtube_note{
    display: inline-block;
    margin-left: 30px;
    color: red;
    line-height: 0px;
}
</style>
<?php ?>
<table width="95%">
   <tr>   
        <td width="100%" style="font-size:14px; font-weight:bold"><a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-3.html" target="_blank" style="color:blue; text-decoration:none;">User Manual</a><br />
        This section allows you to upload videos or choose YouTube videos.<a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-3.html" target="_blank" style="color:blue; text-decoration:none;">More...</a></td>   
    </tr>
  <tbody>
  <tr>
  <td width="100%"><h2>Add Video</h2></td>
  <td align="right"><input type="button" onclick="submitbutton('Save')" value="Save" class="button-secondary action"> </td>  
  <td align="right"><input type="button" onclick="submitbutton('Apply')" value="Apply" class="button-secondary action"> </td> 
  <td align="right"><input type="button" onclick="window.location.href='admin.php?page=Spider_Video_Player_Videos'" value="Cancel" class="button-secondary action"> </td> 
  </tr>
  </tbody></table>
<form action="admin.php?page=Spider_Video_Player_Videos" method="post" name="adminForm" id="adminForm">
<table style="width:95%; background:#FAFBFD; border: 2px solid #4F9BC6; -webkit-border-radius: 8px; -moz-border-radius: 8px; border-radius: 8px;" class="admintable"  cellspacing="0" >
				<tr>
					<td class="key" style="width:200px; background:#E0E0E0; ">
						<label for="title">
							<?php echo 'Title'; ?>:
						</label>
					</td>
					<td style="background:#E8E8E8 ;">
                                    <input type="text" name="title" id="title" size="80" style="border:1px solid #CCCCCC;"/>
					</td>
				</tr>
                <tr>
					<td class="key" style="background:#E0E0E0;">
						<label for="type">
							<?php echo 'Type' ; ?>:
						</label>
					</td>
					<td style="background:#E8E8E8 ;">
                                   <input type="radio" value="http" name="type" checked="checked" onchange="change_type('http')" />http
                                   <input type="radio" value="youtube"  name="type" onchange="change_type('youtube')" />youtube
                                   <input type="radio" value="vimeo"  name="type" onchange="change_type('vimeo')" />vimeo
                                   <input type="radio" value="rtmp" name="type"  onchange="change_type('rtmp')" />rtmp
																		<p id="vimeo_note" style="display:none">* Only for HTML5 Player</p>
																		<p id="youtube_note" style="display:none">* Only for HTML5 Player</p>
					</td>
				</tr>
                <tr id="tr_fmsUrl" style="display:none" >
                        <td class="key" style="background:#E0E0E0;">
                                <label for="fmsUrl">
                                        <?php echo  'RTMP Server Path'; ?>:
                                </label>
                        </td>
                        <td  id="td_fmsUrl" style="background:#E8E8E8 ;">
                        <input type="text" name="fmsUrl" id="fmsUrl" size="80" style="border:1px solid #CCCCCC;"/>
                        </td>
                </tr>
                <tr>
                        <td class="key" style="background:#E0E0E0;">
                                <label for="File">
                                        <?php echo 'URL' ; ?>:
                                </label>
                        </td>
                        <td id="td_url" style="background:#E8E8E8 ;">
                        <div id="div_url">
                        <input type="text" value="" name="http_post_video" id="http_post_video" class="text_input" size="76" style="border:1px solid #CCCCCC;"/><a class="button lu_upload_button" href="#" />Select</a><br>
                       	<a href="javascript:remove_url();">Remove url</a>
                         <script type="text/javascript">    
                                    function remove_url()
                                    {
                                                    document.getElementById("http_post_video").value='';
                                                    
                                    }
                                    </script> 
                        </div>
                        <input type="hidden" name="url" id="url"  value=""  size="80" style="border:1px solid #CCCCCC;"/>
						
						<input  type="hidden" name="url_http" id="url_http" value=""  size="80" style="border:1px solid #CCCCCC;"/>
                        <input type="hidden" name="url_youtube" id="url_youtube" value=""  size="80" style="border:1px solid #CCCCCC;"/>
                        <input type="hidden" name="url_vimeo" id="url_vimeo" value=""  size="80" style="border:1px solid #CCCCCC;"/>
                        <input type="hidden" name="url_rtmp" id="url_rtmp" value=""  size="80" style="border:1px solid #CCCCCC;"/>
                        </td>
                </tr>  
                <tr id="tr_urlHtml5">
                        <td class="key" style="background:#E0E0E0;">
                                <label for="File">
                                        <?php echo 'Url(HTML5) MP4,WebM,Ogg' ; ?>:
                                </label>
                        </td>
                        <td id="td_urlHtml5" style="background:#E8E8E8 ;">
                        <div id="div_urlHtml5">
                        <input type="text" value="" name="http_post_video_html5" id="http_post_video_html5" class="text_input" style="width:417px; border:1px solid #CCCCCC;"/><a class="button lu_upload_button" href="#" />Select</a><br>
                        <a href="javascript:removeurl();">Remove url</a>
                         <script type="text/javascript">    
                                    function removeurl()
                                    {
                                                    document.getElementById("http_post_video_html5").value='';
                                                    
                                    }
                                    </script> 
                       
                        </div>
                        <input type="hidden" name="urlHtml5" id="url" style="border:1px solid #CCCCCC;"/>
                        </td>
                </tr>         
                <tr id="tr_urlHD" >
                        <td class="key" style="background:#E0E0E0;">
                                <label for="UrlHD">
                                        <?php echo  'UrlHD' ; ?>:
                                </label>
                        </td>
                        <td  id="td_urlHD" style="background:#E8E8E8 ;">
                      
                        <div id="div_urlHD" >
                       <input type="text" style="border:1px solid #CCCCCC; width:417px;" value="" name="http_post_video_UrlHD" id="http_post_video_UrlHD" class="text_input"/>
                       
                       
                       <a class="button lu_upload_button" href="#" />Select</a><br>
                        </div>
                        <input type="hidden" name="urlHD" id="urlHD" style="border:1px solid #CCCCCC;"/>
                        <input type="hidden" name="urlHD_rtmp" id="urlHD_rtmp" style="border:1px solid #CCCCCC;" size="80" value=""/>
                        <a id="removehd" href="javascript:removeurl1();">Remove url</a>
                         <script type="text/javascript">    
                                    function removeurl1()
                                    {
                                                    document.getElementById("http_post_video_UrlHD").value='';
                                                    
                                    }
                                    </script>
                        
                        </td>
                </tr>
               <tr id="tr_urlHdHtml5" >
                        <td class="key" style="background:#E0E0E0;">
                                <label for="UrlHD">
                                        <?php echo  'UrlHD(HTML5) MP4,WebM,Ogg' ; ?>:
                                </label>
                        </td>
                        <td  id="td_urlHdHtml5" style="background:#E8E8E8 ;">
                      
                        <div id="div_urlHdHtml5">
                       <input type="text" style="border:1px solid #CCCCCC; width:417px;" value="" name="http_post_video_UrlHD_html5" id="http_post_video_UrlHD_html5" class="text_input" /><a class="button lu_upload_button" href="#" />Select</a><br />
                       <a href="javascript:removeurl2();">Remove url</a>
                         <script type="text/javascript">    
                                    function removeurl2()
                                    {
                                                    document.getElementById("http_post_video_UrlHD_html5").value='';
                                                    
                                    }
                                    </script>
                        </div>
                        <input type="hidden" name="urlHD_html5" id="urlHD_html5" size="80"/>
                        </td>
                </tr>
				<tr>
					<td class="key" style="background:#E0E0E0;">
						<label for="Thumb">
							<?php echo 'Thumb'; ?>:
						</label>
					</td>
                	<td style="background:#E8E8E8 ;">
					<input type="hidden" value="" name="thumb" id="thumb" />
<input type="text" style="border:1px solid #CCCCCC; width:417px;" value="" name="post_image" id="post_image" class="text_input" /><a class="button lu_upload_button" href="#" />Select</a><br />
<a href="javascript:removeImage();">Remove Image</a><br />
             <div style="height:150px;">
                       <img style="display:none;border:none;" height="150" id="imagebox" src="" />     
             </div>     
<script type="text/javascript">    
function removeImage()
{
				document.getElementById("imagebox").style.display="none";
				document.getElementById("post_image").value="";
				document.getElementById("thumb").value='';
}
</script>              
                  </td>				
             </tr>
<?php 
if($tags)
foreach($tags as $tag)
{
	if($tag->published)
	echo '		<tr>
					<td class="key" style="background:#E0E0E0;">
						<label for="title">
							'.$tag->name.':
						</label>
					</td>
					<td style="background:#E8E8E8 ;">
                                    <input type="text" style="border:1px solid #CCCCCC;" name="params['.$tag->id.']" id="params.'.$tag->id.'" size="80" />
					</td>
				</tr>
				';
}
?>
				<tr>
					<td class="key" style="background:#E0E0E0;">
						<label for="published">
							<?php echo  'Published' ; ?>:
						</label>
					</td>
					<td style="background:#E8E8E8 ;">
						<?php
                        echo $lists['published'];
						?>
					</td>
				</tr>                
 </table>    
    <?php wp_nonce_field('nonce_sp_vid', 'nonce_sp_vid'); ?>           
    <input type="hidden" name="option" value="com_player" />
    <input type="hidden" name="task" value="" />
</form>
<div id="sbox-content" style="zoom: 1; opacity: 0; "></div>
<?php
}
function html_show_video($rows, $pageNav,$sort,$playlists){
		global $wpdb;
	?>
    <script language="javascript">
	function ordering(name,as_or_desc)
	{
		document.getElementById('asc_or_desc').value=as_or_desc;		
		document.getElementById('order_by').value=name;
		document.getElementById('admin_form').submit();
	}
	function submit_form_id(x)
				 {
					 var val=x.options[x.selectedIndex].value;
					 document.getElementById("id_for_playlist").value=val;
					 document.getElementById("admin_form").submit();
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
    <form method="post" onkeypress="doNothing()" action="admin.php?page=Spider_Video_Player_Videos" id="admin_form" name="admin_form">
	<?php $sp_vid_nonce = wp_create_nonce('nonce_sp_vid'); ?>
	<table cellspacing="10" width="100%">
      <tr>   
<td width="100%" style="font-size:14px; font-weight:bold"><a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-3.html" target="_blank" style="color:blue; text-decoration:none;">User Manual</a><br />
This section allows you to upload videos or add videos from the Internet. <a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-3.html" target="_blank" style="color:blue; text-decoration:none;">More...</a></td>           </tr>
    <tr>
    <td style="width:80px">
    <?php echo "<h2 style=\"float:left\">".'Videos'. "</h2>"; ?>
    <input type="button" style="float:left; position:relative; top:10px; margin-left:20px" class="button-secondary action" value="Add a Video" name="custom_parametrs" onclick="window.location.href='admin.php?page=Spider_Video_Player_Videos&task=add_video'" />
    </td>
	<td colspan="7" align="right" style="font-size:16px;">
  		<a href="https://web-dorado.com/files/fromSVP.php" target="_blank" style="color:red; text-decoration:none;">
		<img src="<?php echo plugins_url("images/header.png",__FILE__) ?>" border="0" alt="https://web-dorado.com/files/fromSVP.php" width="215">
		</a>
        </td>
 

    </tr>
    </table>
    
    <div class="tablenav top" style="width:95%">
	<div class="alignleft actions" style="width:150px;">
    	<label for="form_id" style="font-size:14px">Filter by a playlist: </label>
        </div>
        <div class="alignleft actions">
       <select name="form_id" id="form_id" onchange="submit_form_id(this)" style="width:130px">
            <option value="0" <?php $zxc='selected="selected"'; if(isset($_POST["id_for_playlist"])){if($_POST["id_for_playlist"]>0 ){$zxc=""; }} echo $zxc;  ?> > Select a Playlist </option>
            <?php foreach($playlists as $playlist){	?>
                        <option value="<?php echo $playlist->id ?>" <?php if(isset($_POST["id_for_playlist"])) { if($_POST["id_for_playlist"]==$playlist->id){ echo'selected="selected"'; }} ?> ><?php echo $playlist->title ?></option>
                       <?php }?> 
                     
            </select>
    </div>
</div>
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
		 <input type="button" value="Reset" onclick="window.location.href=\'admin.php?page=Spider_Video_Player_Videos\'" class="button-secondary action">
    </div>';
	 print_html_nav($pageNav['total'],$pageNav['limit'],$serch_fields);	
	
	?>
  <table class="wp-list-table widefat fixed pages" style="width:95%">
 <thead>
 <TR style="background:#E8E8E8">
 <th scope="col"  id="id" class="<?php if($sort["sortid_by"]=="id") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style=" width:50px" ><a href="javascript:ordering('id',<?php if($sort["sortid_by"]=="id") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>ID</span><span class="sorting-indicator"></span></a></th>
 <th scope="col" id="title" class="<?php if($sort["sortid_by"]=="title") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="width:150px" ><a href="javascript:ordering('title',<?php if($sort["sortid_by"]=="title") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>Title</span><span class="sorting-indicator"></span></a></th>
 <th scope="col" id="type" class="<?php if($sort["sortid_by"]=="type") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="width:80px" ><a href="javascript:ordering('type',<?php if($sort["sortid_by"]=="type") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>Type</span><span class="sorting-indicator"></span></a></th>
  <th scope="col" id="URL" class="<?php if($sort["sortid_by"]=="url") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="" ><a href="javascript:ordering('url',<?php if($sort["sortid_by"]=="url") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>URL</span><span class="sorting-indicator"></span></a></th>
  <th scope="col" id="URL_html5" class="<?php if($sort["sortid_by"]=="urlHtml5") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="" ><a href="javascript:ordering('urlHtml5',<?php if($sort["sortid_by"]=="urlHtml5") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>URL html5</span><span class="sorting-indicator"></span></a></th>
  <th scope="col" id="UrlHD" class="<?php if($sort["sortid_by"]=="urlHD") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="" ><a href="javascript:ordering('urlHD',<?php if($sort["sortid_by"]=="urlHD") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>UrlHD</span><span class="sorting-indicator"></span></a></th>
  <th scope="col" id="UrlHD_html5" class="<?php if($sort["sortid_by"]=="urlhdHtml5") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="" ><a href="javascript:ordering('urlhdHtml5',<?php if($sort["sortid_by"]=="urlhdHtml5") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>UrlHD html5</span><span class="sorting-indicator"></span></a></th>
  <th scope="col" id="thumb" class="<?php if($sort["sortid_by"]=="thumb") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style=" width:80px" ><a href="javascript:ordering('thumb',<?php if($sort["sortid_by"]=="thumb") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>Thumb</span><span class="sorting-indicator"></span></a></th>
  <th scope="col" id="published" class="<?php if($sort["sortid_by"]=="published") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style=" width:120px" ><a href="javascript:ordering('published',<?php if($sort["sortid_by"]=="published") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>Published</span><span class="sorting-indicator"></span></a></th>
 <th style="width:80px">Edit</th>
 <th  style="width:80px">Delete</th>
 </TR>
 </thead>
 <tbody>
 <?php 
 for($i=0; $i<count($rows);$i++){ ?>
 <tr>
         <td><?php echo $rows[$i]->id; ?></td>
         <td><a href="admin.php?page=Spider_Video_Player_Videos&task=edit_video&id=<?php echo $rows[$i]->id; ?>"><?php echo $rows[$i]->title; ?></a></td>
         <td><?php if($rows[$i]->type=="0") echo "rtmp"; else echo $rows[$i]->type; ?></td>
         <td><?php echo $rows[$i]->url; ?></td>
         <td><?php echo $rows[$i]->urlHtml5; ?></td>
         <td><?php echo $rows[$i]->urlHD; ?></td>
         <td><?php echo $rows[$i]->urlHDHtml5; ?></td>
         <td><img width="50" src="<?php echo $rows[$i]->thumb; ?>" title="<?php echo $rows[$i]->thumb; ?>" style="border:none;"></td>
         <td><a <?php if(!$rows[$i]->published) echo 'style="color:#C00"'; ?> href="admin.php?page=Spider_Video_Player_Videos&task=published&id=<?php echo $rows[$i]->id; ?>&_wpnonce=<?php echo $sp_vid_nonce; ?>"><?php if($rows[$i]->published) echo "Yes"; else echo "No"; ?></a></td>
         <td><a href="admin.php?page=Spider_Video_Player_Videos&task=edit_video&id=<?php echo $rows[$i]->id; ?>">Edit</a></td>
         <td><a href="#" href-data="admin.php?page=Spider_Video_Player_Videos&task=remove_video&id=<?php echo $rows[$i]->id; ?>&_wpnonce=<?php echo $sp_vid_nonce; ?>">Delete</a></td>
               
  </tr> 
 <?php } ?>
 </tbody>
 </table>
 <?php wp_nonce_field('nonce_sp_vid', 'nonce_sp_vid'); ?>
 <input type="hidden" name="id_for_playlist" id="id_for_playlist" value="<?php if(isset($_POST['id_for_playlist'])) echo esc_js(esc_html(stripslashes($_POST['id_for_playlist'])));?>" />
 <input type="hidden" name="asc_or_desc" id="asc_or_desc" value="<?php if(isset($_POST['asc_or_desc'])) echo esc_js(esc_html(stripslashes($_POST['asc_or_desc'])));?>"  />
 <input type="hidden" name="order_by" id="order_by" value="<?php if(isset($_POST['order_by'])) echo esc_js(esc_html(stripslashes($_POST['order_by'])));?>"  />
 <?php
?>
    
    
   
 </form>
  
    <?php
}
function html_edit_video($lists, $row,$tags,$id){
		?>
        
<script language="javascript" type="text/javascript">
function submitform( pressbutton ){
	document.getElementById("adminForm").action="admin.php?page=Spider_Video_Player_Videos&task="+pressbutton+"&id="+<?php echo $id; ?>;
	document.getElementById("adminForm").submit();
}
function submitbutton(pressbutton) {
var form = document.adminForm;
if (pressbutton == 'cancel_video') 
{
submitform( pressbutton );
return;
}
	if(form.title.value=="")
	{
		alert('Set Video title');
		return;
	}
<?php 
foreach($tags as $tag)
{
	if($tag->published)
	if($tag->required)
	echo '		
	if(document.getElementById("params.'.$tag->id.'").value=="")
	{
		alert("Set '.$tag->name.'");
		return;
	}
		
';
}
?>
	submitform( pressbutton );
}
function removeVideo(id)
{
				document.getElementById(id+"_link").innerHTML='Select Video';
				document.getElementById(id).value='';
}
function change_type(type)
{
	document.getElementById('vimeo_note').setAttribute('style','display:none');
	document.getElementById('youtube_note').setAttribute('style','display:none');
	switch(type)
	{
		case 'http':
		if(document.getElementById('url_http').value!='')
			document.getElementById('http_post_video').innerHTML=document.getElementById('url_http').value;
		else
			document.getElementById('http_post_video').innerHTML="";
			document.getElementById('tr_urlHtml5').removeAttribute('style');
			document.getElementById('url_youtube').type='hidden';
			document.getElementById('url_vimeo').type='hidden';
			document.getElementById('url_rtmp').type='hidden';
			document.getElementById('div_urlHtml5').style.display="inline";
			document.getElementById('div_urlHdHtml5').style.display="inline";
			document.getElementById('div_url').style.display="inline";
			document.getElementById('urlHD_rtmp').style.display="none";
			document.getElementById('url').type='hidden';
			
			document.getElementById('tr_urlHdHtml5').removeAttribute('style');
			document.getElementById('http_post_video_UrlHD').innerHTML="";
			document.getElementById('div_urlHD').style.display="inline";
			document.getElementById('urlHD').type='hidden';
			document.getElementById('urlHD').value='';
			document.getElementById('tr_fmsUrl').setAttribute('style','display:none');
			document.getElementById('tr_urlHD').removeAttribute('style');	
		 break;
		case 'youtube':
			document.getElementById('tr_urlHdHtml5').style.display="none";
			document.getElementById('div_url').style.display="none";
			document.getElementById('url_youtube').type='text';
			document.getElementById('url_youtube').size='80';
			document.getElementById('url_rtmp').type='hidden';
			document.getElementById('url_vimeo').type='hidden';
			document.getElementById('tr_urlHtml5').style.display="none";
			document.getElementById('div_urlHD').style.display="none";
			document.getElementById('tr_urlHD').setAttribute('style','display:none');
			document.getElementById('urlHD').type='text';
			document.getElementById('url').style.display="none";
			document.getElementById('urlHD').size='80';
			document.getElementById('tr_fmsUrl').setAttribute('style','display:none');
			document.getElementById('youtube_note').removeAttribute('style');
			break;
                  case 'vimeo':
			document.getElementById('tr_urlHdHtml5').style.display="none";
			document.getElementById('div_url').style.display="none";
			document.getElementById('url_vimeo').type='text';
			document.getElementById('url_vimeo').size='80';
			document.getElementById('url_rtmp').type='hidden';
			document.getElementById('url_youtube').type='hidden';
			document.getElementById('tr_urlHtml5').style.display="none";
			document.getElementById('div_urlHD').style.display="none";
			document.getElementById('tr_urlHD').setAttribute('style','display:none');
			document.getElementById('urlHD').type='text';
			document.getElementById('url').style.display="none";
			document.getElementById('urlHD').size='80';
			document.getElementById('tr_fmsUrl').setAttribute('style','display:none');
                        document.getElementById('vimeo_note').removeAttribute('style');
		  break;
		case 'rtmp':
			document.getElementById('div_url').style.display="none";
			document.getElementById('url_youtube').type='hidden';
			document.getElementById('url_vimeo').type='hidden';
			document.getElementById('url_rtmp').size='80';
			document.getElementById('url_rtmp').type='text';
			document.getElementById('urlHD_rtmp').type='text';
			document.getElementById('urlHD_rtmp').size='80';
			document.getElementById('div_urlHD').style.display="none";
			document.getElementById('urlHD_rtmp').style.display="block";
			document.getElementById('div_urlHtml5').style.display="none";
			document.getElementById('tr_urlHtml5').setAttribute('style','display:none');					
			document.getElementById('div_urlHdHtml5').style.display="none";
			document.getElementById('tr_urlHdHtml5').setAttribute('style','display:none');			
			document.getElementById('tr_fmsUrl').removeAttribute('style');
			document.getElementById('tr_urlHD').removeAttribute('style');
			document.getElementById('urlHD').style.display="none";
			document.getElementById('removehd').style.display="none";
		  break;
		default:
		  alert('def')
	}
	}
<?php 
$pname= array();
$pvalue= array();
$params=explode('#***#',$row->params);
foreach($params as $param)
{
if($param)
	{$temp=explode('#===#',$param);
	
		$pname[]=htmlspecialchars($temp[0]);
		$pvalue[]=htmlspecialchars($temp[1]);
	}
}
?>
i=<?php echo count($pname); ?>;
//-->
</script> 
<script type="text/javascript">
jQuery(function() {
	var formfield=null;
	window.original_send_to_editor = window.send_to_editor;
	window.send_to_editor = function(html){
		if (formfield) {
			var fileurl = jQuery('img',html).attr('src');
			if(!fileurl)
			{
			var exploded_html;
			var exploded_html_askofen;
			exploded_html=html.split('"');
			for(i=0;i<exploded_html.length;i++)
			exploded_html_askofen=exploded_html[i].split("'");
			for(i=0;i<exploded_html.length;i++)
			{
				for(j=0;j<exploded_html_askofen.length;j++)
				{
				if(exploded_html_askofen[j].search("href"))
				{
				fileurl=exploded_html_askofen[i+1];
				break;
				}
				}
			}
			}
			else
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
border-right:1px solid #cccccc;
border-top:1px solid #cccccc;
border-left:1px solid #cccccc;
border-bottom:1px solid #cccccc;
}
#vimeo_note,#youtube_note{
    display: inline-block;
    margin-left: 30px;
    color: red;
    line-height: 0px;
}
</style>
<table width="95%">
   <tr>   
<td width="100%" style="font-size:14px; font-weight:bold"><a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-3.html" target="_blank" style="color:blue; text-decoration:none;">User Manual</a><br />
This section allows you to upload videos or choose YouTube videos.<a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-3.html" target="_blank" style="color:blue; text-decoration:none;">More...</a></td>   
 </tr>
  <tr>
  <tbody>
  <tr>
  <td width="100%"><h2>Video - <?php echo $row->title; ?></h2></td>
  <td align="right"><input type="button" onclick="submitbutton('Save')" value="Save" class="button-secondary action"> </td>  
  <td align="right"><input type="button" onclick="submitbutton('Apply')" value="Apply" class="button-secondary action"> </td> 
  <td align="right"><input type="button" onclick="window.location.href='admin.php?page=Spider_Video_Player_Videos'" value="Cancel" class="button-secondary action"> </td> 
  </tr>
  </tbody></table>    
<form action="admin.php?page=Spider_Video_Player_Videos" method="post" name="adminForm" id="adminForm">
<table class="admintable" style="width:95%; background:#FAFBFD; border: 2px solid #4F9BC6; -webkit-border-radius: 8px; -moz-border-radius: 8px; border-radius: 8px;">
				<tr>
					<td class="key" style="width:200px; background:#E0E0E0;">
						<label for="title">
							<?php echo 'Title'; ?>:
						</label>
					</td>
					<td style="background:#E8E8E8;">
                                    <input type="text" name="title" id="title" size="80" value="<?php echo $row->title ?>"  style="border:1px solid #CCCCCC;"/>
					</td>
				</tr>
                
                <tr>
					<td class="key" style="background:#E0E0E0;">
						<label for="Type">
							<?php echo 'Type'; ?>:
						</label>
					</td>
					<td style="background:#E8E8E8;">
                                   <input type="radio" value="http" name="type" <?php if($row->type=="http") echo 'checked="checked"';?> onchange="change_type('http')" />http
                                   <input type="radio" value="youtube"  name="type" <?php if($row->type=="youtube") echo 'checked="checked"';?> onchange="change_type('youtube')" />youtube
                                    <input type="radio" value="vimeo"  name="type" <?php if($row->type=="vimeo") echo 'checked="checked"';?> onchange="change_type('vimeo')" />vimeo
                                   <input type="radio" value="rtmp" name="type" <?php if($row->type=="rtmp" || $row->type=="0") echo 'checked="checked"';?> onchange="change_type('rtmp')" />rtmp
					<p id="vimeo_note" <?php if($row->type!="vimeo") echo 'style="display:none"'; ?>>* Only for HTML5 Player</p>				
					<p id="youtube_note" <?php if($row->type!="youtube") echo 'style="display:none"'; ?>>* Only for HTML5 Player</p>
					</td>
				</tr>
                <tr id="tr_fmsUrl" <?php if($row->type=="http" || $row->type=="youtube" || $row->type=="vimeo") echo 'style="display:none"'; ?>>
                        <td class="key" style="background:#E0E0E0;">
                                <label for="fmsUrl">
                                        <?php echo 'RTMP Server Path'; ?>:
                                </label>
                        </td>
                        <td  id="td_fmsUrl" style="background:#E8E8E8;">
                        <input type="text" name="fmsUrl" id="fmsUrl" size="80" <?php if($row->type=="rtmp") echo 'value="'.htmlspecialchars($row->fmsUrl).'"'; ?> style="border:1px solid #CCCCCC;"/>
                        </td>
                </tr>
                <tr>
                              <td class="key" style="background:#E0E0E0;">
                                  <label for="URL">
                                      <?php echo 'URL'; ?>:
                                  </label>
                                
                              </td>
                              <td style="background:#E8E8E8;">
                <div id="div_url" style="display:<?php if($row->type=="http") echo "inline"; else echo "none";?>">
                 <input type="text" value="<?php if($row->type=="http") echo htmlspecialchars($row->url)?>" name="http_post_video" id="http_post_video" class="text_input" style="width:417px; border:1px solid #CCCCCC;"/><a class="button lu_upload_button" href="#" />Select</a><br />
                <a href="javascript:removeurl();">Remove url</a>
                         <script type="text/javascript">    
                                    function removeurl()
                                    {
                                                    document.getElementById("http_post_video").value='';
                                                    
                                    }
                                    </script>
                </div>       
                
                <input <?php if($row->type=="http") echo 'type="text"'; else echo 'type="hidden" size="80"';?> name="url" id="url" value="<?php if($row->type=="http") echo htmlspecialchars($row->url)?>"  style="border:1px solid #CCCCCC; display:<?php if($row->type=="http") echo "none"; else echo "inline";?>"/>    
                <input  type="hidden" name="url_http" id="url_http" value="<?php if($row->type=="http") echo htmlspecialchars($row->url); ?>" <?php if($row->type=="http") echo 'type="text"'; else echo 'type="hidden" size="80"';?> />
                
                <input <?php if($row->type=="youtube") echo 'type="text"  size="80"'; else echo 'type="hidden"';?> name="url_youtube" style="border:1px solid #CCCCCC;" id="url_youtube" value="<?php if($row->type=="youtube") echo htmlspecialchars($row->url) ?>"  />
                <input <?php if($row->type=="vimeo") echo 'type="text"  size="80"'; else echo 'type="hidden"';?> name="url_vimeo" style="border:1px solid #CCCCCC;" id="url_vimeo" value="<?php if($row->type=="vimeo") echo htmlspecialchars($row->url) ?>"  />
                <input <?php if($row->type=="rtmp" || $row->type=="0") echo 'type="text" size="80"'; else echo 'type="hidden" ';?> name="url_rtmp"  style="border:1px solid #CCCCCC;" id="url_rtmp" value="<?php if($row->type=="rtmp") echo htmlspecialchars($row->url) ?>"  />     
                        </td>
                </tr>
                <tr id="tr_urlHtml5" <?php if($row->type=="rtmp" || $row->type=="youtube" || $row->type=="0" || $row->type=="vimeo") echo 'style="display:none"'; ?>>
                        <td class="key" style="background:#E0E0E0;">
                                <label for="File">
                                        <?php echo 'Url(HTML5) MP4,WebM,Ogg' ; ?>:
                                </label>
                        </td>
                        <td id="td_urlHtml5" style="background:#E8E8E8;">
                        <div id="div_urlHtml5">
                        <input type="text" value="<?php if($row->type=="http") echo htmlspecialchars($row->urlHtml5); ?>" name="http_post_video_html5" id="http_post_video_html5" class="text_input" style="width:417px; border:1px solid #CCCCCC;"/><a class="button lu_upload_button" href="#" />Select</a><br>
                        <a href="javascript:removeurl1();">Remove url</a>
                         <script type="text/javascript">    
                                    function removeurl1()
                                    {
                                                    document.getElementById("http_post_video_html5").value='';
                                                    
                                    }
                                    </script>
                        </div>
                        <input type="hidden" name="url_html5" id="url_html5" style="border:1px solid #CCCCCC;"/>
                        </td>
                </tr>    
                
                <tr  id="tr_urlHD" <?php if($row->type=="youtube" || $row->type=="vimeo") echo 'style="display:none"'; ?> >
                        <td class="key" style="background:#E0E0E0;">
                                <label for="UrlHD">
                                        <?php echo  'UrlHD'; ?>:
                                </label>
                        </td>
                        <td style="background:#E8E8E8;">
                <div id="div_urlHD" style="display:<?php if($row->type=="http") echo "inline"; else echo "none";?>">
                <input type="text" value="<?php if($row->type=="http")echo htmlspecialchars($row->urlHD);  ?>" name="http_post_video_UrlHD" id="http_post_video_UrlHD" class="text_input" style="width:417px; border:1px solid #CCCCCC;"/>
                
                <a class="button lu_upload_button" href="#" />Select</a><br />
               <a href="javascript:removeurl2();">Remove url</a>
                         <script type="text/javascript">    
                                    function removeurl2()
                                    {
                                                    document.getElementById("http_post_video_UrlHD").value='';
                                    }
                                    </script>
                </div>
            
                <input type="hidden" name="urlHD" id="urlHD" style="border:1px solid #CCCCCC;"/> 
                <input type="<?php if($row->type=="rtmp" || $row->type=="0") echo "text"; else echo "hidden";?>" name="urlHD_rtmp" id="urlHD_rtmp" style="border:1px solid #CCCCCC;" value="<?php if($row->type=="rtmp" || $row->type=="0") echo htmlspecialchars($row->urlHD); ?>" size="80"/>
                        </td>
                </tr>
                <tr id="tr_urlHdHtml5" <?php if($row->type=="rtmp" || $row->type=="youtube" || $row->type=="0" || $row->type=="vimeo") echo 'style="display:none"'; ?>>
                        <td class="key" style="background:#E0E0E0;">
                                <label for="UrlHD">
                                        <?php echo  'UrlHD(HTML5) MP4,WebM,Ogg' ; ?>:
                                </label>
                        </td>
                        <td  id="td_urlHdHtml5" style="background:#E8E8E8;">
                      
                        <div id="div_urlHdHtml5">
                       <input type="text" value="<?php if($row->type=="http" )echo htmlspecialchars($row->urlHDHtml5); ?>" name="http_post_video_UrlHD_html5" id="http_post_video_UrlHD_html5" class="text_input" style="width:417px; border:1px solid #CCCCCC;"/><a class="button lu_upload_button" href="#" />Select</a><br>
                        <a href="javascript:removeurl3();">Remove url</a>
                         <script type="text/javascript">    
                                    function removeurl3()
                                    {
                                                    document.getElementById("http_post_video_UrlHD_html5").value='';
                                                    
                                    }
                                    </script>
                        </div>
                        <input type="hidden" name="urlHD_html5" id="urlHD_html5" style="background:#E0E0E0; border:1px solid #CCCCCC;"/>
                        </td>
                </tr>
                <tr>
					<td class="key" style="background:#E0E0E0;">
						<label for="Thumb">
							<?php echo 'Thumb'; ?>:
						</label>
					</td>
                	<td style="background:#E8E8E8;">
					<input type="text" value="<?php if($row->thumb )echo htmlspecialchars($row->thumb); ?>" name="post_image" id="post_image" class="text_input" style="width:417px; border:1px solid #CCCCCC;"; /><a class="button lu_upload_button" href="#" />Select</a><br>
<a href="javascript:removeImage();">Remove Image</a><br />
<div style="position:absolute; width:1px; height:1px; top:0px; overflow:hidden">
<textarea id="tempimage" name="tempimage" class="mce_editable"></textarea><br />
</div>
<script type="text/javascript">
function removeImage()
{
				document.getElementById("imagebox").style.display="none";
				document.getElementById("post_image").value="";
}
</script>
                                       <div style="height:150px;">
                       <img style="display:<?php if($row->thumb=='') echo 'none'; else echo 'block' ?>; border:none;" height="150" id="imagebox" src="<?php echo htmlspecialchars($row->thumb) ; ?>" />     
                                       </div>                    </td>
<?php 
foreach($tags as $tag)
{
	
	
	if($tag->published)
	if( in_array($tag->id,$pname))
	{
	$key_value = array_search($tag->id,$pname);
	echo '		<tr>
					<td class="key" style="background:#E0E0E0;">
						<label for="title">
							'.$tag->name.':
						</label>
					</td>
					<td style="background:#E8E8E8;">
                                    <input style="border:1px solid #CCCCCC;" type="text" name="params['.$tag->id.']" id="params.'.$tag->id.'" value="'.$pvalue[$key_value].'" size="80" />
					</td>
				</tr>
				';
	}
	else
	{
	echo '		<tr>
					<td class="key" style="background:#E0E0E0;">
						<label for="title">
							'.$tag->name.':
						</label>
					</td>
					<td style="background:#E8E8E8;">
                                    <input type="text" style="border:1px solid #CCCCCC;" name="params['.$tag->id.']" id="params.'.$tag->id.'" value="" size="80" />
					</td>
				</tr>
				';
	}
}
?>
    
				<tr>
					<td class="key" style="background:#E0E0E0;">
						<label for="published">
							<?php echo  'Published'; ?>:
						</label>
					</td>
					<td style="background:#E8E8E8;">
						<?php
                        echo $lists['published'];
						?>
					</td>
				</tr>                
 </table>   
<?php wp_nonce_field('nonce_sp_vid', 'nonce_sp_vid'); ?> 
<input type="hidden" name="id" value="<?php echo $row->id?>" />        
<input type="hidden" name="cid[]" value="<?php echo $row->id; ?>" />         
</form>
        <?php		
       
}
