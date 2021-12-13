<?php
if(function_exists('current_user_can')){
	if(!current_user_can('manage_options')) {
	die('Access Denied');
}	
} else {
	die('Access Denied');
}
function html_add_theme($row){
	if($row->ctrlsStack)
			$value=$row->ctrlsStack;
		else
			$value='playPrev:1,playPause:1,stop:1,vol:1,time:1,playNext:1,+:0,repeat:1,shuffle:1,hd:1,playlist:1,lib:1,fullScreen:1,play:1,pause:1';
		$ctrls = explode(",", $value);
		$n = count($ctrls);
		$path=plugins_url("images",__FILE__)."/";
		
		?>
        <script type="text/javascript">
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
<script language="javascript" type="text/javascript">
SqueezeBox.presets.onClose=function (){document.getElementById('sbox-content').innerHTML=""};
SqueezeBox.presets.onOpen=function (){refresh_ctrl();};
function get_radio_value(name)
{
	for (var i=0; i < document.getElementsByName(name).length; i++)   
	{   
		if (document.getElementsByName(name)[i].checked)      
		{      
			var rad_val = document.getElementsByName(name)[i].value;      
			return rad_val;      
		}   
	}
}
function refresh_()
{	
	appWidth			=parseInt(document.getElementById('appWidth').value);
	appHeight			=parseInt(document.getElementById('appHeight').value);
	refresh_ctrl();
	document.getElementById('priview_td').childNodes[0].href='<?php echo admin_url('admin-ajax.php?action=spiderVeideoPlayerPrewieve')?>&post_id=270&appWidth='+appWidth+'&appHeight='+appHeight+'&amp;TB_iframe=1';
}
function submitbutton(pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'cancel_theme') 
	{
		submitform( pressbutton );
		return;
	}
	if(form.title.value=="")
	{
		alert('Set Theme title');
		return;
	}
	refresh_ctrl();
	submitform( pressbutton );
}
function IsNumeric(input){
    var RE = /^-{0,1}\d*\.{0,1}\d+$/;
    return (RE.test(input));
}
function refresh_ctrl(){
	ctrlStack="";
	w=document.getElementById('tr_arr').childNodes;
	for(i in w)
	if (IsNumeric(i))
		if(w[i].nodeType!=3)
			{
				if(ctrlStack=="")
					ctrlStack=w[i].getAttribute('value')+':'+w[i].getAttribute('active'); ///???
				else
					ctrlStack=ctrlStack+","+w[i].getAttribute('value')+':'+w[i].getAttribute('active'); ///???
			}
	document.getElementById('ctrlsStack').value=ctrlStack;
}
function check_isnum(e){
   	var chCode1 = e.which || e.keyCode;
    	if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57))
        return false;
	return true;
}
<?php 
$sliders=array("defaultVol", "centerBtnAlpha", "watermarkAlpha", "framesBgAlpha", "ctrlsMainAlpha", "itemBgAlpha" );
foreach( $sliders as $slider)
{
	
?>
jQuery(function() {
	jQuery( "#slider-<?php echo $slider?>" ).slider({
		range: "min",
		value: <?php echo $row->$slider?>,
		min: 1,
		max: 100,
		slide: function( event, ui ) {
			jQuery( "#<?php echo $slider?>" ).val( "" + ui.value );
		}
	});
	jQuery( "#<?php echo $slider?>" ).val( "" + jQuery( "#slider-<?php echo $slider?>" ).slider( "value" ) );
});
<?php
}
?>
jQuery(document).ready(function($) {	
	jQuery(document).ready(function(){
	for (var i = 0; i < <?php echo $n ?>; i++)
	{
		jQuery("#arr_" + i).bind('click',{i:i},function(event){
			i=event.data.i;
			image=document.getElementById("arr_" + i).getAttribute('image');
			if(document.getElementById("td_arr_" + i).getAttribute('active') == 0)
			{
				document.getElementById("arr_" + i).src="<?php echo $path ;?>"+image+'_1.png';
				document.getElementById("td_arr_" + i).setAttribute('active','1');
			}
			else
			{
				document.getElementById("arr_" + i).src="<?php echo $path ;?>"+image+'_0.png';
				document.getElementById("td_arr_" + i).setAttribute('active','0');
			}
		});	
	}	  
	});
});
function submitform(pressbutton)
{
	document.getElementById("adminForm").action=document.getElementById("adminForm").action+"&task="+pressbutton;
	document.getElementById("adminForm").submit();
}
jQuery(function() {
	jQuery( "#tr_arr" ).sortable();
	jQuery( "#tr_arr" ).disableSelection();
});
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
.admintable td table td
{
border:0px;
}
</style>
<style>
#minwidth { min-width: 960px; }
.clr { clear: both; overflow:hidden; height: 0; }
a, img { padding: 0; margin: 0; }
img { border: 0 none; }
form { margin: 0; padding: 0; }
h1 {
	margin: 0; padding-bottom: 8px;
	color: #0B55C4; font-size: 20px; font-weight: bold;
}
h3 {
	font-size: 13px;
}
a:link    { color: #0B55C4; text-decoration: none; }
a:visited { color: #0B55C4; text-decoration: none; }
a:hover   { text-decoration: underline; }
fieldset {
	margin-bottom: 10px;
	border: 1px #ccc solid;
	padding: 5px;
	text-align: left;
}
fieldset p {  margin: 10px 0px;  }
legend    {
	color: #0B55C4;
	font-size: 12px;
	font-weight: bold;
}
input, select { font-size: 10px;  border: 1px solid silver; }
textarea      { font-size: 11px;  border: 1px solid silver; }
button        { font-size: 10px;  }
input.disabled { background-color: #F0F0F0; }
input.button  { cursor: pointer;   }
input:focus,
select:focus,
textarea:focus { background-color: #ffd }
/* -- overall styles ------------------------------ */
#border-top.h_green          { background: url(../images/h_green/j_header_middle.png) repeat-x; }
#border-top.h_green div      { background: url(../images/h_green/j_header_right.png) 100% 0 no-repeat; }
#border-top.h_green div div  { background: url(../images/h_green/j_header_left.png) no-repeat; height: 54px; }
#border-top.h_teal          { background: url(../images/h_teal/j_header_middle.png) repeat-x; }
#border-top.h_teal div      { background: url(../images/h_teal/j_header_right.png) 100% 0 no-repeat; }
#border-top.h_teal div div  { background: url(../images/h_teal/j_header_left.png) no-repeat; height: 54px; }
#border-top.h_cherry          { background: url(../images/h_cherry/j_header_middle.png) repeat-x; }
#border-top.h_cherry div      { background: url(../images/h_cherry/j_header_right.png) 100% 0 no-repeat; }
#border-top.h_cherry div div  { background: url(../images/h_cherry/j_header_left.png) no-repeat; height: 54px; }
#border-top .title {
	font-size: 22px; font-weight: bold; color: #fff; line-height: 44px;
	padding-left: 180px;
}
#border-top .version {
	display: block; float: right;
	color: #fff;
	padding: 25px 5px 0 0;
}
#border-bottom 			{ background: url(../images/j_bottom.png) repeat-x; }
#border-bottom div  		{ background: url(../images/j_corner_br.png) 100% 0 no-repeat; }
#border-bottom div div 	{ background: url(../images/j_corner_bl.png) no-repeat; height: 11px; }
#footer .copyright { margin: 10px; text-align: center; }
#header-box  { border: 1px solid #ccc; background: #f0f0f0; }
#content-box {
	border-left: 1px solid #ccc;
	border-right: 1px solid #ccc;
}
#content-box .padding  { padding: 10px 10px 0 10px; }
#toolbar-box 			{ background: #fbfbfb; margin-bottom: 10px; }
#submenu-box { background: #f6f6f6; margin-bottom: 10px; }
#submenu-box .padding { padding: 0px;}
/* -- status layout */
#module-status      { float: right; }
#module-status span { display: block; float: left; line-height: 16px; padding: 4px 10px 0 22px; margin-bottom: 5px; }
#module-status { background: url(../images/mini_icon.png) 3px 5px no-repeat; }
.legacy-mode{ color: #c00;}
#module-status .preview 			  { background: url(../images/menu/icon-16-media.png) 3px 3px no-repeat; }
#module-status .unread-messages,
#module-status .no-unread-messages { background: url(../images/menu/icon-16-messages.png) 3px 3px no-repeat; }
#module-status .unread-messages a  { font-weight: bold; }
#module-status .loggedin-users     { background: url(../images/menu/icon-16-user.png) 3px 3px no-repeat; }
#module-status .logout             { background: url(../images/menu/icon-16-logout.png) 3px 3px no-repeat; }
/* -- various styles -- */
span.note {
	display: block;
	background: #ffd;
	padding: 5px;
	color: #666;
}
/** overlib **/
.ol-foreground {
	background-color: #ffe;
}
.ol-background {
	background-color: #6db03c;
}
.ol-textfont {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #666;
}
.ol-captionfont {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #fff;
	font-weight: bold;
}
.ol-captionfont a {
	color: #0b5fc6;
	text-decoration: none;
}
.ol-closefont {}
/** toolbar **/
div.header {
	font-size: 22px; font-weight: bold; color: #0B55C4; line-height: 48px;
	padding-left: 55px;
	background-repeat: no-repeat;
	margin-left: 10px;
}
div.header span { color: #666; }
div.configuration {
	font-size: 14px; font-weight: bold; color: #0B55C4; line-height: 16px;
	padding-left: 30px;
	margin-left: 10px;
	background-image: url(../images/menu/icon-16-config.png);
	background-repeat: no-repeat;
}
div.toolbar { float: right; text-align: right; padding: 0; }
table.toolbar    			 { border-collapse: collapse; padding: 0; margin: 0;	 }
table.toolbar td 			 { padding: 1px 1px 1px 4px; text-align: center; color: #666; height: 48px; }
table.toolbar td.spacer  { width: 10px; }
table.toolbar td.divider { border-right: 1px solid #eee; width: 5px; }
table.toolbar span { float: none; width: 32px; height: 32px; margin: 0 auto; display: block; }
table.toolbar a {
   display: block; float: left;
	white-space: nowrap;
	border: 1px solid #fbfbfb;
	padding: 1px 5px;
	cursor: pointer;
}
table.toolbar a:hover {
	border-left: 1px solid #eee;
	border-top: 1px solid #eee;
	border-right: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
	text-decoration: none;
	color: #0B55C4;
}
/** for massmail component **/
td#mm_pane			{ width: 90%; }
input#mm_subject    { width: 200px; }
textarea#mm_message { width: 100%; }
/* pane-sliders  */
.pane-sliders .title {
	margin: 0;
	padding: 2px;
	color: #666;
	cursor: pointer;
}
.pane-sliders .panel   { border: 1px solid #ccc; margin-bottom: 3px;}
.pane-sliders .panel h3 { background: #f6f6f6; color: #666}
.pane-sliders .content { background: #f6f6f6; }
.pane-sliders .adminlist     { border: 0 none; }
.pane-sliders .adminlist td  { border: 0 none; }
.jpane-toggler  span     { background: transparent url(../images/j_arrow.png) 5px 50% no-repeat; padding-left: 20px;}
.jpane-toggler-down span { background: transparent url(../images/j_arrow_down.png) 5px 50% no-repeat; padding-left: 20px;}
.jpane-toggler-down {  border-bottom: 1px solid #ccc; }
/* tabs */
dl.tabs {
	float: left;
	margin: 10px 0 -1px 0;
	z-index: 50;
}
dl.tabs dt {
	float: left;
	padding: 4px 10px;
	border-left: 1px solid #ccc;
	border-right: 1px solid #ccc;
	border-top: 1px solid #ccc;
	margin-left: 3px;
	background: #f0f0f0;
	color: #666;
}
dl.tabs dt.open {
	background: #F9F9F9;
	border-bottom: 1px solid #F9F9F9;
	z-index: 100;
	color: #000;
}
div.current {
	clear: both;
	border: 1px solid #ccc;
	padding: 10px 10px;
}
div.current dd {
	padding: 0;
	margin: 0;
}
/** cpanel settings **/
#cpanel div.icon {
	text-align: center;
	margin-right: 5px;
	float: left;
	margin-bottom: 5px;
}
#cpanel div.icon a {
	display: block;
	float: left;
	border: 1px solid #f0f0f0;
	height: 97px;
	width: 108px;
	color: #666;
	vertical-align: middle;
	text-decoration: none;
}
#cpanel div.icon a:hover {
	border-left: 1px solid #eee;
	border-top: 1px solid #eee;
	border-right: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
	background: #f9f9f9;
	color: #0B55C4;
}
#cpanel img  { padding: 10px 0; margin: 0 auto; }
#cpanel span { display: block; text-align: center; }
/* standard form style table */
div.col { float: left; }
div.width-45 { width: 45%; }
div.width-55 { width: 55%; }
div.width-50 { width: 50%; }
div.width-70 { width: 70%; }
div.width-30 { width: 30%; }
div.width-60 { width: 60%; }
div.width-40 { width: 40%; }
table.admintable td 					 { padding: 3px; }
table.admintable td.key,
table.admintable td.paramlist_key {
	background-color: #f6f6f6;
	text-align: right;
	width: 140px;
	color: #666;
	font-weight: bold;
	border-bottom: 1px solid #e9e9e9;
	border-right: 1px solid #e9e9e9;
}
table.paramlist td.paramlist_description {
	background-color: #f6f6f6;
	text-align: left;
	width: 170px;
	color: #333;
	font-weight: normal;
	border-bottom: 1px solid #e9e9e9;
	border-right: 1px solid #e9e9e9;
}
table.admintable td.key.vtop { vertical-align: top; }
table.adminform {
	background-color: #f9f9f9;
	border: solid 1px #d5d5d5;
	width: 100%;
	border-collapse: collapse;
	margin: 8px 0 10px 0;
	margin-bottom: 15px;
	width: 100%;
}
table.adminform.nospace { margin-bottom: 0; }
table.adminform tr.row0 { background-color: #f9f9f9; }
table.adminform tr.row1 { background-color: #eeeeee; }
table.adminform th {
	font-size: 11px;
	padding: 6px 2px 4px 4px;
	text-align: left;
	height: 25px;
	color: #000;
	background-repeat: repeat;
}
table.adminform td { padding: 3px; text-align: left; }
table.adminform td.filter{
	text-align: left;
}
table.adminform td.helpMenu{
	text-align: right;
}
fieldset.adminform { border: 1px solid #ccc; margin: 0 10px 10px 10px; }
/** Table styles **/
table.adminlist {
	width: 100%;
	border-spacing: 1px;
	background-color: #e7e7e7;
	color: #666;
}
table.adminlist td,
table.adminlist th { padding: 4px; }
table.adminlist thead th {
	text-align: center;
	background: #f0f0f0;
	color: #666;
	border-bottom: 1px solid #999;
	border-left: 1px solid #fff;
}
table.adminlist thead a:hover { text-decoration: none; }
table.adminlist thead th img { vertical-align: middle; }
table.adminlist tbody th { font-weight: bold; }
table.adminlist tbody tr			{ background-color: #fff;  text-align: left; }
table.adminlist tbody tr.row1 	{ background: #f9f9f9; border-top: 1px solid #fff; }
table.adminlist tbody tr.row0:hover td,
table.adminlist tbody tr.row1:hover td  { background-color: #ffd ; }
table.adminlist tbody tr td 	   { height: 25px; background: #fff; border: 1px solid #fff; }
table.adminlist tbody tr.row1 td { background: #f9f9f9; border-top: 1px solid #FFF; }
table.adminlist tfoot tr { text-align: center;  color: #333; }
table.adminlist tfoot td,
table.adminlist tfoot th { background-color: #f3f3f3; border-top: 1px solid #999; text-align: center; }
table.adminlist td.order 		{ text-align: center; white-space: nowrap; }
table.adminlist td.order span { float: left; display: block; width: 20px; text-align: center; }
table.adminlist .pagination { display:table; padding:0;  margin:0 auto;	 }
.pagination div.limit {
	float: left;
	height: 22px;
	line-height: 22px;
	margin: 0 10px;
}
/** stu nicholls solution for centering divs **/
.container {clear:both; text-decoration:none;}
* html .container {display:inline-block;}
/** table solution for global config **/
table.noshow   		 { width: 100%; border-collapse: collapse; padding: 0; margin: 0; }
table.noshow tr 		 { vertical-align: top; }
table.noshow td 		 { }
table.noshow fieldset { margin: 15px 7px 7px 7px; }
#editor-xtd-buttons { padding: 5px; }
/* -- buttons -> STILL NEED CLEANUP*/
.button1,
.button1 div{
	height: 1%;
	float: right;
}
.button2-left,
.button2-right,
.button2-left div,
.button2-right div {
	float: left;
}
.button1 { background: url(../images/j_button1_left.png) no-repeat; white-space: nowrap; padding-left: 10px; margin-left: 5px;}
.button1 .next { background: url(../images/j_button1_next.png) 100% 0 no-repeat; }
.button1 a {
	display: block;
	height: 26px;
	float: left;
	line-height: 26px;
	font-size: 12px;
	font-weight: bold;
	color: #333;
	cursor: pointer;
	padding: 0 30px 0 6px;
}
.button1 a:hover { text-decoration: none; color: #0B55C4; }
.button2-left a,
.button2-right a,
.button2-left span,
.button2-right span {
	display: block;
	height: 22px;
	float: left;
	line-height: 22px;
	font-size: 11px;
	color: #333;
	cursor: pointer;
}
.button2-left span,
.button2-right span { cursor: default; color: #999; }
.button2-left .page a,
.button2-right .page a,
.button2-left .page span,
.button2-right .page span,
.button2-left .blank a,
.button2-right .blank a,
.button2-left .blank span,
.button2-right .blank span { padding: 0 6px; }
.page span,
.blank span {
	color: #000;
	font-weight: bold;
}
.button2-left a:hover,
.button2-right a:hover { text-decoration: none; color: #0B55C4; }
.button2-left a,
.button2-left span { padding: 0 24px 0 6px; }
.button2-right a,
.button2-right span { padding: 0 6px 0 24px; }
.button2-left { background: url(../images/j_button2_left.png) no-repeat; float: left; margin-left: 5px; }
.button2-right { background: url(../images/j_button2_right.png) 100% 0 no-repeat; float: left; margin-left: 5px; }
.button2-right .prev { background: url(../images/j_button2_prev.png) no-repeat; }
.button2-right.off .prev { background: url(../images/j_button2_prev_off.png) no-repeat; }
.button2-right .start { background: url(../images/j_button2_first.png) no-repeat; }
.button2-right.off .start { background: url(../images/j_button2_first_off.png) no-repeat; }
.button2-left .page,
.button2-left .blank { background: url(../images/j_button2_right_cap.png) 100% 0 no-repeat; }
.button2-left .next { background: url(../images/j_button2_next.png) 100% 0 no-repeat; }
.button2-left.off .next { background: url(../images/j_button2_next_off.png) 100% 0 no-repeat; }
.button2-left .end { background: url(../images/j_button2_last.png) 100% 0 no-repeat; }
.button2-left.off .end { background: url(../images/j_button2_last_off.png) 100% 0 no-repeat; }
.button2-left .image 		{ background: url(../images/j_button2_image.png) 100% 0 no-repeat; }
.button2-left .readmore 	{ background: url(../images/j_button2_readmore.png) 100% 0 no-repeat; }
.button2-left .pagebreak 	{ background: url(../images/j_button2_pagebreak.png) 100% 0 no-repeat; }
.button2-left .blank	 	{ background: url(../images/j_button2_blank.png) 100% 0 no-repeat; }
/* Tooltips */
.tool-tip {
	float: left;
	background: #ffc;
	border: 1px solid #D4D5AA;
	padding: 5px;
	max-width: 200px;
	z-index: 50;
}
.tool-title {
	padding: 0;
	margin: 0;
	font-size: 100%;
	font-weight: bold;
	margin-top: -15px;
	padding-top: 15px;
	padding-bottom: 5px;
	background: url(../images/selector-arrow.png) no-repeat;
}
.tool-text {
	font-size: 100%;
	margin: 0;
}
/* Calendar */
a img.calendar {
	width: 16px;
	height: 16px;
	margin-left: 3px;
	background: url(../images/calendar.png) no-repeat;
	cursor: pointer;
	vertical-align: middle;
}
/* System Standard Messages */
#system-message dd.message ul { background: #C3D2E5 url(../images/notice-info.png) 4px center no-repeat;}
/* System Error Messages */
#system-message dd.error ul { color: #c00; background: #E6C0C0 url(../images/notice-alert.png) 4px top no-repeat; border-top: 3px solid #DE7A7B; border-bottom: 3px solid #DE7A7B;}
/* System Notice Messages */
#system-message dd.notice ul { color: #c00; background: #EFE7B8 url(../images/notice-note.png) 4px top no-repeat; border-top: 3px solid #F0DC7E; border-bottom: 3px solid #F0DC7E;}
</style>
    <table width="90%">
        <tr>   
<td width="100%" style="font-size:14px; font-weight:bold"><a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-5/spider-video-player-wordpress-guide-step-5-1.html" target="_blank" style="color:blue; text-decoration:none;">User Manual</a><br />
This section allows you to create themes to customize the design of the player. <a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-5/spider-video-player-wordpress-guide-step-5-1.html" target="_blank" style="color:blue; text-decoration:none;">More...</a></td>   
</tr>
    <tr>
  <td width="100%"><h2>Adding New Theme</h2></td>
<td id="priview_td" onclick="refresh_ctrl();"><a href="<?php echo admin_url('admin-ajax.php?action=spiderVeideoPlayerPrewieve') ?>&post_id=270&appWidth=640&appHeight=480&amp;TB_iframe=1&amp;width=640&amp;height=218" class="thickbox thickbox-preview" id="content-add_media" title="Spider Video Player" onclick="return false;"><input type="button"  value="preview" class="button-primary"></a></td>
  <td align="right"><input type="button" onclick="submitbutton('Save')" value="Save" class="button-secondary action"> </td>  
  <td align="right"><input type="button" onclick="submitbutton('Apply')" value="Apply" class="button-secondary action"> </td> 
  <td align="right"><input type="button" onclick="window.location.href='admin.php?page=Spider_Video_Player_Themes'" value="Cancel" class="button-secondary action"> </td> 
  </tr>
  </table>
<form action="admin.php?page=Spider_Video_Player_Themes"  method="post" id="adminForm" name="adminForm" >
 <table><tr><td>
 
 
 
 
 
 
 
 
 
 
 
 
 
 <div style=" float:left; width:390px"">
 <fieldset class="adminform">
						<legend>General Parameters</legend>
                        <table class="admintable">
                        <tr>
							<td class="key">
								<label for="title">
									<?php echo _e( 'Title of theme:' ); ?>
								</label>
							</td>
							<td >
                                    <input type="text" name="title" id="title" size="40" value=""/>
							</td>
						</tr>
                         <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label id="appWidth-lbl" for="appWidth" >Width of player:</label>
                                </span>
                            </td>
                            <td class="paramlist_value">
                                <input type="text" style="" name="appWidth" id="appWidth" value="<?php echo $row->appWidth; ?>" class="text_area" onchange="refresh_()" onkeypress="return check_isnum(event)" />
                            </td>
                    	</tr>
                        <tr>
                        <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label id="appHeight-lbl" for="appHeight">Height of player:</label>
                            </span>
                            </td>
                            <td class="paramlist_value">
                                <input type="text" name="appHeight" id="appHeight"  class="text_area"  value="<?php echo $row->appHeight; ?>" onchange="refresh_()" onkeypress="return check_isnum(event)" />
                            </td>
                  	  	</tr>
                         <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Start with:</label>
                            </span>
                            </td>
                           <td class="paramlist_value">                        
                            <input type="radio" name="startWithLib" id="startWithLib0" value="0" <?php cheched($row->startWithLib,0) ?>  class="inputbox">
                            <label for="startWithLib0">Video</label>
                            <input type="radio" name="startWithLib" id="startWithLib1" value="1" <?php cheched($row->startWithLib,1) ?> class="inputbox">
                            <label for="startWithLib1">Library</label>
                   		 </td>
             		   </tr>
                       <tr>
                            <td class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Show Track Id:</label>
                            </span>
                            </td>
                            <td class="paramlist_value">
                                <input type="radio" id="show_trackid0" name="show_trackid" value="0" <?php cheched($row->show_trackid,0) ?>><label for="show_trackid0">No</label> 
                                <input type="radio" id="show_trackid1" name="show_trackid" value="1" <?php cheched($row->show_trackid,1) ?>><label for="show_trackid1">Yes</label> 
                            </td>
                        </tr>
                        <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Auto hide time:</label>
                            </span>
                            </td>
                            <td class="paramlist_value">
                                <input type="text" name="autohideTime" id="autohideTime" value="<?php echo $row->autohideTime ?>" class="text_area" onkeypress="return check_isnum(event)">sec
                            </td>
                        </tr>
            
                        <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Keep aspect ratio (only for flash):</label>
                            </span>
                            </td>
                           <td class="paramlist_value">
                                
                                <input type="radio" name="keepAspectRatio" id="keepAspectRatio0" value="0" class="inputbox" <?php cheched($row->keepAspectRatio,0) ?>>
                                <label for="keepAspectRatio0">No</label>
                                <input type="radio" name="keepAspectRatio" id="keepAspectRatio1" value="1" <?php cheched($row->keepAspectRatio,1) ?> class="inputbox">
                                <label for="keepAspectRatio1">Yes</label>
                            </td>
                        </tr>
                         <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Control bar over video  (only for flash):</label>
                            </span>
                            </td>
                            <td class="paramlist_value">
                                
                                <input type="radio" name="ctrlsOverVid" id="ctrlsOverVid0" value="0" <?php cheched($row->ctrlsOverVid,0) ?> class="inputbox">
                                <label for="ctrlsOverVid0">No</label>
                                <input type="radio" name="ctrlsOverVid" id="ctrlsOverVid1" value="1" <?php cheched($row->ctrlsOverVid,1) ?> class="inputbox">
                                <label for="ctrlsOverVid1">Yes</label>
                            </td>
                		</tr>
                         <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Watermark image  (only for flash):</label>
                                </span>
                                </td>
                                <td>
                                    <input type="text" value="<?php if($row->watermarkUrl )echo htmlspecialchars($row->watermarkUrl); ?>" name="watermarkUrl" id="post_image" class="text_input" style="width:137px"/><a class="button lu_upload_button" href="#" />Select</a><br />
                                    <a href="javascript:removeImage();">Remove Image</a><br />
                                                 <div style="height:150px;">
                                                           <img style=" display:<?php if($row->watermarkUrl=='') echo 'none'; else echo 'block' ?>; border:none;" height="150" id="imagebox" src="<?php echo $row->watermarkUrl ?>" />     
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
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Watermark Position  (only for flash):</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="radio" id="watermarkPos1" name="watermarkPos" value="1" <?php cheched($row->watermarkPos,1) ?> ><label for="watermarkPos1"> Top left</label>
                                    <input type="radio" id="watermarkPos2" name="watermarkPos" value="2" <?php cheched($row->watermarkPos,2) ?> ><label for="watermarkPos2">Top right</label>
                                    <input type="radio" id="watermarkPos3" name="watermarkPos" value="3" <?php cheched($row->watermarkPos,3) ?> ><label for="watermarkPos3">Bottom left</label>
                                    <input type="radio" id="watermarkPos4" name="watermarkPos" value="4" <?php cheched($row->watermarkPos,4) ?> ><label for="watermarkPos4">Bottom right</label>
                                </td>
              			  </tr>
                          <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Watermark size  (only for flash):</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="text" name="watermarkSize" id="watermarkSize" value="<?php echo $row->watermarkSize; ?>" class="text_area" onkeypress="return check_isnum(event)">pixels
                                </td>
                            </tr>
                             <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Watermark Margin  (only for flash):</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="text" name="watermarkSpacing" id="watermarkSpacing" value="<?php echo $row->watermarkSpacing; ?>" class="text_area" onkeypress="return check_isnum(event)">pixels
                                </td>
                            </tr>
                             <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Watermark transparency  (only for flash):</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <p style="border:0; color:#f6931f;">
                                        <input type="text" name="watermarkAlpha" id="watermarkAlpha"  value="<?php echo $row->watermarkAlpha; ?>" style="border:0; color:#f6931f; font-weight:bold; width:30px" onkeypress="return check_isnum(event)">%
                                    </p>
                                    <div id="slider-watermarkAlpha" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" style="width:240px"></div>
                                </td>
                            </tr>
                        </table>
</fieldset>
</div>
<div style="float:left; width:390px"">
<fieldset class="adminform">
						<legend>Style Parameters</legend>
                        <table class="admintable">
                                    
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Center button transparency  (only for flash):</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <p style="border:0; color:#f6931f;">
                                        <input type="text" name="centerBtnAlpha"   value="<?php echo $row->centerBtnAlpha ?>" id="centerBtnAlpha" style="border:0; color:#f6931f; font-weight:bold; width:30px" onkeypress="return check_isnum(event)">%
                                    </p>
                                    <div id="slider-centerBtnAlpha" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" style="width:240px"></div>
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Background color:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="text" name="appBgColor" id="appBgColor" value="<?php echo $row->appBgColor; ?>" class="color">
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Video background color:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="text" name="vidBgColor" id="vidBgColor" value="<?php echo $row->vidBgColor; ?>" class="color">
                                </td>
                            </tr>
                             <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Frames background color:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="text" name="framesBgColor" id="framesBgColor" value="<?php echo $row->framesBgColor; ?>" class="color">
                                </td>
                            </tr>
                             <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Frames background transparency:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <p style="border:0; color:#f6931f;">
                                        <input type="text" name="framesBgAlpha" id="framesBgAlpha" value="<?php echo $row->framesBgAlpha; ?>" style="border:0; color:#f6931f; font-weight:bold; width:30px" onkeypress="return check_isnum(event)">%
                                    </p>
                                    <div id="slider-framesBgAlpha" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" style="width:240px"></div>
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Control buttons main color:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="text" name="ctrlsMainColor" id="ctrlsMainColor" value="<?php echo $row->ctrlsMainColor; ?>" class="color">
                                </td>
               				 </tr>
                          <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Control buttons hover color (only for flash):	</label>
                            </span>
                            </td>
                            <td class="paramlist_value">
                                <input type="text" name="ctrlsMainHoverColor" id="ctrlsMainHoverColor" value="<?php echo $row->ctrlsMainHoverColor; ?>" class="color">
                            </td>
                        </tr>
                        <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Opacity:</label>
                            </span>
                            </td>
                            <td class="paramlist_value">
                                <p style="border:0; color:#f6931f;">
                                    <input type="text" name="ctrlsMainAlpha"  value="<?php echo $row->ctrlsMainAlpha; ?>" id="ctrlsMainAlpha" style="border:0; color:#f6931f; font-weight:bold; width:30px" onkeypress="return check_isnum(event)">%
                                </p>
                                <div id="slider-ctrlsMainAlpha" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" style="width:240px"></div>
                            </td>
                        </tr>
                        <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Sliders color:</label>
                            </span>
                            </td>
                            <td class="paramlist_value">
                                <input type="text" name="slideColor" id="slideColor" value="<?php echo $row->slideColor; ?>" class="color">
                            </td>
                        </tr>
                        <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Hovered item background Color:</label>
                            </span>
                            </td>
                            <td class="paramlist_value">
                                <input type="text" name="itemBgHoverColor" id="itemBgHoverColor" value="<?php echo $row->itemBgHoverColor; ?>" class="color">
                            </td>
                        </tr>
                        <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Selected item background Color:</label>
                            </span>
                            </td>
                            <td class="paramlist_value">
                                <input type="text" name="itemBgSelectedColor" id="itemBgSelectedColor" value="<?php echo $row->itemBgSelectedColor; ?>" class="color">
                            </td>
                        </tr>
                         <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Text color:</label>
                            </span>
                            </td>
                            <td class="paramlist_value">
                                <input type="text" name="textColor" id="textColor" value="<?php echo $row->textColor; ?>" class="color">
                            </td>
                        </tr>
                        <tr>
                    <td  class="paramlist_key">
                        <span class="editlinktip">
                            <label>Hovered text color:</label>
                    </span>
                    </td>
                    <td class="paramlist_value">
                        <input type="text" name="textHoverColor" id="textHoverColor" value="<?php echo $row->textHoverColor; ?>" class="color">
                    </td>
                </tr>
                 <tr>
                    <td  class="paramlist_key">
                        <span class="editlinktip">
                            <label>Selected text color:</label>
                    </span>
                    </td>
                    <td class="paramlist_value">
                        <input type="text" name="textSelectedColor" id="textSelectedColor" value="<?php echo $row->textSelectedColor; ?>" class="color">
                    </td>
                </tr>
                <tr>
                    <td  class="paramlist_key">
                        <span class="editlinktip">
                            <label>Loading animation type  (only for flash):</label>
                    </span>
                    </td>
                    <td class="paramlist_value">
                        <input type="radio" id="loadinAnimType1" name="loadinAnimType" value="1" <?php cheched($row->loadinAnimType,1) ?>><label for="loadinAnimType1"> Circles</label>
                        <input type="radio" id="loadinAnimType2" name="loadinAnimType" value="2" <?php cheched($row->loadinAnimType,2) ?>><label for="loadinAnimType2"> Lines</label>
                    </td>
                
                <tr>
                                
                        </table>
</fieldset>
</div>
<div style="float:left; width:390px;">
<fieldset class="adminform">
						<legend>Playlist and Library Parameters</legend>
                        <table class="admintable">
                       		 <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Playlist Position:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="radio" id="playlistPos1" name="playlistPos" value="1" <?php cheched($row->playlistPos,1) ?>><label for="playlistPos1">Left</label> 
                                    <input type="radio" id="playlistPos2" name="playlistPos" value="2" <?php cheched($row->playlistPos,2) ?>><label for="playlistPos2">Right</label> 
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label id="playlistWidth-lbl" for="playlistWidth">Width of playlist:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="text" name="playlistWidth" id="playlistWidth" value="<?php echo $row->playlistWidth; ?>" class="text_area"  onkeypress="return check_isnum(event)" />
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Playlist over video:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    
                                    <input type="radio" name="playlistOverVid" id="playlistOverVid0" value="0" <?php cheched($row->playlistOverVid,0) ?> class="inputbox">
                                    <label for="playlistOverVid0">No</label>
                                    <input type="radio" name="playlistOverVid" id="playlistOverVid1" value="1" <?php cheched($row->playlistOverVid,1) ?> class="inputbox">
                                    <label for="playlistOverVid1">Yes</label>
                                </td>
                            </tr>
							<tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Open playlist at start:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    
                                    <input type="radio" name="openPlaylistAtStart" id="openPlaylistAtStart0" value="0" <?php cheched($row->openPlaylistAtStart,0) ?> class="inputbox">
                                    <label for="playlistAutoHide0">No</label>
                                    <input type="radio" name="openPlaylistAtStart" id="openPlaylistAtStart1" value="1" <?php cheched($row->openPlaylistAtStart,1) ?> class="inputbox">
                                    <label for="playlistAutoHide1">Yes</label>
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Playlist auto hide:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    
                                    <input type="radio" name="playlistAutoHide" id="playlistAutoHide0" value="0" <?php cheched($row->playlistAutoHide,0) ?> class="inputbox">
                                    <label for="playlistAutoHide0">No</label>
                                    <input type="radio" name="playlistAutoHide" id="playlistAutoHide1" value="1" <?php cheched($row->playlistAutoHide,1) ?> class="inputbox">
                                    <label for="playlistAutoHide1">Yes</label>
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Playlist text size:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="text" name="playlistTextSize" id="playlistTextSize" value="<?php echo $row->playlistTextSize; ?>" class="text_area" onkeypress="return check_isnum(event)">pixels
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Library colums:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="text" name="libCols" id="libCols" value="<?php echo $row->libCols; ?>" class="text_area" onkeypress="return check_isnum(event)">
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Library rows:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="text" name="libRows" id="libRows" value="<?php echo $row->libRows; ?>" class="text_area" onkeypress="return check_isnum(event)">
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Library list text size:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="text" name="libListTextSize" id="libListTextSize" value="<?php echo $row->libListTextSize; ?>" class="text_area" onkeypress="return check_isnum(event)">pixels
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Library details text size:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="text" name="libDetailsTextSize" id="libDetailsTextSize" value="<?php echo $row->libDetailsTextSize; ?>" class="text_area" onkeypress="return check_isnum(event)">pixels
                                </td>
                            </tr>
                
                        
                        
                        </table>
</fieldset>
</div>
<div style="float:left; width:390px;">
<fieldset class="adminform">
						<legend>Playback Parameters</legend>
                        <table class="admintable">
                         <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Auto play:</label>
                            </span>
                            </td>
                            <td class="paramlist_value">   
                                <input type="radio" name="autoPlay" id="autoPlay0" value="0" <?php cheched($row->autoPlay,0) ?> class="inputbox">
                                <label for="autoPlay0">No</label>
                                <input type="radio" name="autoPlay" id="autoPlay1" value="1" <?php cheched($row->autoPlay,1) ?> class="inputbox">
                                <label for="autoPlay1">Yes</label>
                            </td>
                        </tr>
                           <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Auto next song:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">                
                                <input type="radio" name="autoNext" id="autoNext0" value="0" <?php cheched($row->autoNext,0) ?> class="inputbox">
                                <label for="autoNext0">No</label>
                                <input type="radio" name="autoNext" id="autoNext1" value="1" <?php cheched($row->autoNext,1) ?> class="inputbox">
                                <label for="autoNext1">Yes</label>
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Auto next album:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">                        
                                    <input type="radio" name="autoNextAlbum" id="autoNextAlbum0" value="0" <?php cheched($row->autoNextAlbum,0) ?> class="inputbox">
                                    <label for="autoNextAlbum0">No</label>
                                    <input type="radio" name="autoNextAlbum" id="autoNextAlbum1" value="1" <?php cheched($row->autoNextAlbum,1) ?> class="inputbox">
                                    <label for="autoNextAlbum1">Yes</label>
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Default Volume:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <p style="border:0; color:#f6931f;">
                                        <input type="text" name="defaultVol" value="<?php echo $row->defaultVol; ?>" id="defaultVol" style="border:0; color:#f6931f; font-weight:bold; width:30px" onkeypress="return check_isnum(event)">%
                                    </p>
                                    <div id="slider-defaultVol" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" style="width:240px"></div>
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Repeat:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="radio" id="defaultRepeat1" name="defaultRepeat" value="repeatOff"  <?php cheched($row->defaultRepeat,"repeatOff") ?>><label for="defaultRepeat1">Off</label> 
                                    <input type="radio" id="defaultRepeat2" name="defaultRepeat" value="repeatOne"  <?php cheched($row->defaultRepeat,"repeatOne") ?>><label for="defaultRepeat2">One</label>  
                                    <input type="radio" id="defaultRepeat3" name="defaultRepeat" value="repeatAll"  <?php cheched($row->defaultRepeat,"repeatAll") ?>><label for="defaultRepeat3">All</label> 
                                </td>
                            </tr>
                            <tr>
                                    <td  class="paramlist_key">
                                        <span class="editlinktip">
                                            <label>Shuffle:</label>
                                    </span>
                                    </td>
                                    <td class="paramlist_value">
                                        <input type="radio" id="defaultShuffle1" name="defaultShuffle" value="shuffleOff" <?php cheched(str_replace ('Shuffle', 'shuffle', $row->defaultShuffle),"shuffleOff") ?>><label for="defaultShuffle1">Off</label> 
                
                                        <input type="radio" id="defaultShuffle2" name="defaultShuffle" value="shuffleOn" <?php cheched(str_replace ('Shuffle', 'shuffle', $row->defaultShuffle),"shuffleOn") ?>><label for="defaultShuffle2">On</label> 
                                    </td>
                                </tr>
                                 <tr>
                                    <td  class="paramlist_key">
                                        <span class="editlinktip">
                                            <label>Control bar auto hide:</label>
                                    </span>
                                    </td>
                                    <td class="paramlist_value">
                                        
                                            <input type="radio" name="ctrlsSlideOut" id="ctrlsSlideOut0" value="0" <?php cheched($row->ctrlsSlideOut,0) ?> class="inputbox">
                                            <label for="ctrlsSlideOut0">No</label>
                                            <input type="radio" name="ctrlsSlideOut" id="ctrlsSlideOut1" value="1" <?php cheched($row->ctrlsSlideOut,1) ?> class="inputbox">
                                            <label for="ctrlsSlideOut1">Yes</label>
                                    </td>
                                </tr>
                        
                        
                        </table>
</fieldset>
</div>
<div style="width:100%;">
<fieldset class="adminform" style="width: 100%;">
						<legend>Video Control Parameters</legend>
                        <table class="admintable">
                        <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Play/pause on click:</label>
                            </span>
                            </td>
                            <td class="paramlist_value">
                                
                                <input type="radio" name="clickOnVid" id="clickOnVid0" value="0" class="inputbox" <?php cheched($row->clickOnVid,0) ?>>
                                <label for="clickOnVid0">No</label>
                                <input type="radio" name="clickOnVid" id="clickOnVid1" value="1" <?php cheched($row->clickOnVid,1) ?> class="inputbox">
                                <label for="clickOnVid1">Yes</label>
                            </td>
                        </tr>
                         <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Play/pause by space key:</label>
                            </span>
                            </td>
                           <td class="paramlist_value">
                                
                                    <input type="radio" name="spaceOnVid" id="spaceOnVid0" value="0" <?php cheched($row->spaceOnVid,0) ?> class="inputbox">
                                    <label for="spaceOnVid0">No</label>
                                    <input type="radio" name="spaceOnVid" id="spaceOnVid1" value="1" <?php cheched($row->spaceOnVid,1) ?> class="inputbox">
                                    <label for="spaceOnVid1">Yes</label>
                            </td>
                        </tr>
                        <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Volume control by mouse scroll  (only for flash):</label>
                            </span>
                            </td>
                           <td class="paramlist_value">
                                                    
                                <input type="radio" name="mouseWheel" id="mouseWheel0" value="0"   <?php cheched($row->mouseWheel,0) ?> class="inputbox">
                                <label for="mouseWheel0">No</label>
                                <input type="radio" name="mouseWheel" id="mouseWheel1" value="1"  <?php cheched($row->mouseWheel,1) ?> class="inputbox">
                                <label for="mouseWheel1">Yes</label>
                            </td>
                        </tr>
                        <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Control bar position:</label>
                            </span>
                            </td>
                            <td class="paramlist_value">
                                <input type="radio" id="ctrlsPos1" name="ctrlsPos" value="1" <?php cheched($row->ctrlsPos,1) ?>><label for="ctrlsPos1">Up</label> 
                                <input type="radio" id="ctrlsPos2"  name="ctrlsPos" value="2" <?php cheched($row->ctrlsPos,2) ?>><label for="ctrlsPos2">Down</label> 
                            </td>
                        </tr>
                        <tr>
                    <td  class="paramlist_key">
                        <span class="editlinktip">
                            <label>Buttons order on control bar:</label>
                    </span>
                    </td>
                    <td class="paramlist_value">
                        <table border="0" style="background-color:#666" cellpadding="0" cellspacing="0" width="100%">
                                        <?php 
										echo '<tr valign="top" id="tr_arr" valign="middle">';
                                        foreach($ctrls as $key =>  $x) 
                                         {
                                                $y = explode(":", $x);
                                                $ctrl	=$y[0];
                                                $active	=$y[1];
                                                    echo '<td id="td_arr_'.$key.'"  active="'.$active.'" value="'.$ctrl.'" width="40" align="center" style="padding:4px">
                                                                <img src="'.$path.$ctrl.'_'.$active.'.png" id="arr_'.$key.'" image="'.$ctrl.'" style=" cursor:pointer; border:none;"/>
                                                            </td>';
                                        }
                                         echo '</tr>';
										 ?>
                        </table>           
                    <input type="hidden" name="ctrlsStack" id="ctrlsStack" value="<?php echo $value; ?>" size="100">
                    </td>
                </tr>
                        
                        
                        </table>
</fieldset>
</div>
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 </td></tr></table>
    <?php wp_nonce_field('nonce_sp_vid', 'nonce_sp_vid'); ?>            
    <input type="hidden" name="option" value="com_player" />
    <input type="hidden" name="task" value="" />
</form>
<?php	
	//JToolBarHelper::preferences('com_player', '700','1000','Spider_Video_Player Parameters','');
	?>
<?php
}
function html_show_theme($rows, $pageNav, $sort){
		
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
    <script language="JavaScript">
</script>
    <form method="post" onkeypress="doNothing()"  action="admin.php?page=Spider_Video_Player_Themes" id="admin_form" name="admin_form">
	<?php $sp_vid_nonce = wp_create_nonce('nonce_sp_vid'); ?>
	<table cellspacing="10" width="100%">
        <tr>   
<td width="100%" style="font-size:14px; font-weight:bold"><a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-5/spider-video-player-wordpress-guide-step-5-1.html" target="_blank" style="color:blue; text-decoration:none;">User Manual</a><br />
This section allows you to create themes to customize the design of the player. <a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-5/spider-video-player-wordpress-guide-step-5-1.html" target="_blank" style="color:blue; text-decoration:none;">More...</a></td>   
      </tr>
    <tr>
    <td style="width:80px">
    <?php echo "<h2 style=\"float:left\">".'Themes'. "</h2>"; ?>
    <input type="button" style="float:left; position:relative; top:10px; margin-left:20px" class="button-secondary action" value="Add a Theme" name="custom_parametrs" onclick="window.location.href='admin.php?page=Spider_Video_Player_Themes&task=add_theme'" />
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
		 <input type="button" value="Reset" onclick="window.location.href=\'admin.php?page=Spider_Video_Player_Themes\'" class="button-secondary action">
    </div>';
	 print_html_nav($pageNav['total'],$pageNav['limit'],$serch_fields);	
	
	?>
  <table class="wp-list-table widefat fixed pages" style="width:95%">
 <thead>
 <TR>
 <th scope="col" id="id" class="<?php if($sort["sortid_by"]=="id") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style=" width:120px" ><a href="javascript:ordering('id',<?php if($sort["sortid_by"]=="id") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>ID</span><span class="sorting-indicator"></span></a></th>
 <th scope="col" id="title" class="<?php if($sort["sortid_by"]=="title") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="" ><a href="javascript:ordering('title',<?php if($sort["sortid_by"]=="title") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>Title</span><span class="sorting-indicator"></span></a></th>
 <th>Default</th>
 <th style="width:80px">Edit</th>
 <th style="width:80px">Delete</th>
 </TR>
 </thead>
 <tbody>
 <?php for($i=0; $i<count($rows);$i++){ ?>
 <tr>
         <td><?php echo $rows[$i]->id; ?></td>
         <td><a  href="admin.php?page=Spider_Video_Player_Themes&task=edit_theme&id=<?php echo $rows[$i]->id?>"><?php echo $rows[$i]->title; ?></a></td>
         <td><a <?php if(!$rows[$i]->default) echo 'style="color:#C00"';  ?>  href="admin.php?page=Spider_Video_Player_Themes&task=default&id=<?php echo $rows[$i]->id?>&_wpnonce=<?php echo $sp_vid_nonce; ?>"><?php if($rows[$i]->default) echo "Default"; else echo "Not Default";  ?></a></td>
         <td><a  href="admin.php?page=Spider_Video_Player_Themes&task=edit_theme&id=<?php echo $rows[$i]->id?>">Edit</a></td>
         <td><a  href="#" href-data="admin.php?page=Spider_Video_Player_Themes&task=remove_theme&id=<?php echo $rows[$i]->id?>&_wpnonce=<?php echo $sp_vid_nonce; ?>">Delete</a></td>
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
function html_edit_theme($row, $id){
		if($row->ctrlsStack)
			$value=$row->ctrlsStack;
		else
			$value='playPrev:1,playPause:1,stop:1,vol:1,time:1,playNext:1,+:0,repeat:1,shuffle:1,hd:1,playlist:1,lib:1,fullScreen:1,play:1,pause:1';
		$ctrls = explode(",", $value);
		$n = count($ctrls);
		$path=plugins_url("images",__FILE__)."/";
		
		?>
        <script type="text/javascript">
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
<script language="javascript" type="text/javascript">
SqueezeBox.presets.onClose=function (){document.getElementById('sbox-content').innerHTML=""};
SqueezeBox.presets.onOpen=function (){refresh_ctrl();};
function get_radio_value(name)
{
	for (var i=0; i < document.getElementsByName(name).length; i++)   
	{   
		if (document.getElementsByName(name)[i].checked)      
		{      
			var rad_val = document.getElementsByName(name)[i].value;      
			return rad_val;      
		}   
	}
}
function refresh_()
{	
	appWidth			=parseInt(document.getElementById('appWidth').value);
	appHeight			=parseInt(document.getElementById('appHeight').value);
	refresh_ctrl();
	document.getElementById('priview_td').childNodes[0].href='<?php echo admin_url('admin-ajax.php?action=spiderVeideoPlayerPrewieve') ?>&post_id=270&appWidth='+appWidth+'&appHeight='+appHeight+'&amp;TB_iframe=1';
}
function submitbutton(pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'cancel_theme') 
	{
		submitform( pressbutton );
		return;
	}
	if(form.title.value=="")
	{
		alert('Set Theme title');
		return;
	}
	refresh_ctrl();
	submitform( pressbutton );
}
function IsNumeric(input){
    var RE = /^-{0,1}\d*\.{0,1}\d+$/;
    return (RE.test(input));
}
function refresh_ctrl(){
	ctrlStack="";
	w=document.getElementById('tr_arr').childNodes;
	for(i in w)
	if (IsNumeric(i))
		if(w[i].nodeType!=3)
			{
				if(ctrlStack=="")
					ctrlStack=w[i].getAttribute('value')+':'+w[i].getAttribute('active'); ///???
				else
					ctrlStack=ctrlStack+","+w[i].getAttribute('value')+':'+w[i].getAttribute('active'); ///???
			}
	document.getElementById('ctrlsStack').value=ctrlStack;
}
function check_isnum(e){
   	var chCode1 = e.which || e.keyCode;
    	if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57))
        return false;
	return true;
}
function submitform(pressbutton)
{
	document.getElementById("adminForm").action=document.getElementById("adminForm").action+"&task="+pressbutton;
	document.getElementById("adminForm").submit();
}
<?php 
$sliders=array("defaultVol", "centerBtnAlpha", "watermarkAlpha", "framesBgAlpha", "ctrlsMainAlpha", "itemBgAlpha" );
foreach( $sliders as $slider)
{
	
?>
jQuery(function() {
	jQuery( "#slider-<?php echo $slider?>" ).slider({
		range: "min",
		value: <?php echo $row->$slider?>,
		min: 1,
		max: 100,
		slide: function( event, ui ) {
			jQuery( "#<?php echo $slider?>" ).val( "" + ui.value );
		}
	});
	jQuery( "#<?php echo $slider?>" ).val( "" + jQuery( "#slider-<?php echo $slider?>" ).slider( "value" ) );
});
<?php
}
?>
jQuery(document).ready(function($) {	
	jQuery(document).ready(function(){
	for (var i = 0; i < <?php echo $n ?>; i++)
	{
		jQuery("#arr_" + i).bind('click',{i:i},function(event){
			i=event.data.i;
			image=document.getElementById("arr_" + i).getAttribute('image');
			if(document.getElementById("td_arr_" + i).getAttribute('active') == 0)
			{
				document.getElementById("arr_" + i).src="<?php echo $path ;?>"+image+'_1.png';
				document.getElementById("td_arr_" + i).setAttribute('active','1');
			}
			else
			{
				document.getElementById("arr_" + i).src="<?php echo $path ;?>"+image+'_0.png';
				document.getElementById("td_arr_" + i).setAttribute('active','0');
			}
		});	
	}	  
	});
});
jQuery(function() {
	jQuery( "#tr_arr" ).sortable();
	jQuery( "#tr_arr" ).disableSelection();
});
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
.admintable td table td
{
border:0px;
}
</style>
<style>
#minwidth { min-width: 960px; }
.clr { clear: both; overflow:hidden; height: 0; }
a, img { padding: 0; margin: 0; }
img { border: 0 none; }
form { margin: 0; padding: 0; }
h1 {
	margin: 0; padding-bottom: 8px;
	color: #0B55C4; font-size: 20px; font-weight: bold;
}
h3 {
	font-size: 13px;
}
a:link    { color: #0B55C4; text-decoration: none; }
a:visited { color: #0B55C4; text-decoration: none; }
a:hover   { text-decoration: underline; }
fieldset {
	margin-bottom: 10px;
	border: 1px #ccc solid;
	padding: 5px;
	text-align: left;
}
fieldset p {  margin: 10px 0px;  }
legend    {
	color: #0B55C4;
	font-size: 12px;
	font-weight: bold;
}
input, select { font-size: 10px;  border: 1px solid silver; }
textarea      { font-size: 11px;  border: 1px solid silver; }
button        { font-size: 10px;  }
input.disabled { background-color: #F0F0F0; }
input.button  { cursor: pointer;   }
input:focus,
select:focus,
textarea:focus { background-color: #ffd }
/* -- overall styles ------------------------------ */
#border-top.h_green          { background: url(../images/h_green/j_header_middle.png) repeat-x; }
#border-top.h_green div      { background: url(../images/h_green/j_header_right.png) 100% 0 no-repeat; }
#border-top.h_green div div  { background: url(../images/h_green/j_header_left.png) no-repeat; height: 54px; }
#border-top.h_teal          { background: url(../images/h_teal/j_header_middle.png) repeat-x; }
#border-top.h_teal div      { background: url(../images/h_teal/j_header_right.png) 100% 0 no-repeat; }
#border-top.h_teal div div  { background: url(../images/h_teal/j_header_left.png) no-repeat; height: 54px; }
#border-top.h_cherry          { background: url(../images/h_cherry/j_header_middle.png) repeat-x; }
#border-top.h_cherry div      { background: url(../images/h_cherry/j_header_right.png) 100% 0 no-repeat; }
#border-top.h_cherry div div  { background: url(../images/h_cherry/j_header_left.png) no-repeat; height: 54px; }
#border-top .title {
	font-size: 22px; font-weight: bold; color: #fff; line-height: 44px;
	padding-left: 180px;
}
#border-top .version {
	display: block; float: right;
	color: #fff;
	padding: 25px 5px 0 0;
}
#border-bottom 			{ background: url(../images/j_bottom.png) repeat-x; }
#border-bottom div  		{ background: url(../images/j_corner_br.png) 100% 0 no-repeat; }
#border-bottom div div 	{ background: url(../images/j_corner_bl.png) no-repeat; height: 11px; }
#footer .copyright { margin: 10px; text-align: center; }
#header-box  { border: 1px solid #ccc; background: #f0f0f0; }
#content-box {
	border-left: 1px solid #ccc;
	border-right: 1px solid #ccc;
}
#content-box .padding  { padding: 10px 10px 0 10px; }
#toolbar-box 			{ background: #fbfbfb; margin-bottom: 10px; }
#submenu-box { background: #f6f6f6; margin-bottom: 10px; }
#submenu-box .padding { padding: 0px;}
/* -- status layout */
#module-status      { float: right; }
#module-status span { display: block; float: left; line-height: 16px; padding: 4px 10px 0 22px; margin-bottom: 5px; }
#module-status { background: url(../images/mini_icon.png) 3px 5px no-repeat; }
.legacy-mode{ color: #c00;}
#module-status .preview 			  { background: url(../images/menu/icon-16-media.png) 3px 3px no-repeat; }
#module-status .unread-messages,
#module-status .no-unread-messages { background: url(../images/menu/icon-16-messages.png) 3px 3px no-repeat; }
#module-status .unread-messages a  { font-weight: bold; }
#module-status .loggedin-users     { background: url(../images/menu/icon-16-user.png) 3px 3px no-repeat; }
#module-status .logout             { background: url(../images/menu/icon-16-logout.png) 3px 3px no-repeat; }
/* -- various styles -- */
span.note {
	display: block;
	background: #ffd;
	padding: 5px;
	color: #666;
}
/** overlib **/
.ol-foreground {
	background-color: #ffe;
}
.ol-background {
	background-color: #6db03c;
}
.ol-textfont {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #666;
}
.ol-captionfont {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #fff;
	font-weight: bold;
}
.ol-captionfont a {
	color: #0b5fc6;
	text-decoration: none;
}
.ol-closefont {}
/** toolbar **/
div.header {
	font-size: 22px; font-weight: bold; color: #0B55C4; line-height: 48px;
	padding-left: 55px;
	background-repeat: no-repeat;
	margin-left: 10px;
}
div.header span { color: #666; }
div.configuration {
	font-size: 14px; font-weight: bold; color: #0B55C4; line-height: 16px;
	padding-left: 30px;
	margin-left: 10px;
	background-image: url(../images/menu/icon-16-config.png);
	background-repeat: no-repeat;
}
div.toolbar { float: right; text-align: right; padding: 0; }
table.toolbar    			 { border-collapse: collapse; padding: 0; margin: 0;	 }
table.toolbar td 			 { padding: 1px 1px 1px 4px; text-align: center; color: #666; height: 48px; }
table.toolbar td.spacer  { width: 10px; }
table.toolbar td.divider { border-right: 1px solid #eee; width: 5px; }
table.toolbar span { float: none; width: 32px; height: 32px; margin: 0 auto; display: block; }
table.toolbar a {
   display: block; float: left;
	white-space: nowrap;
	border: 1px solid #fbfbfb;
	padding: 1px 5px;
	cursor: pointer;
}
table.toolbar a:hover {
	border-left: 1px solid #eee;
	border-top: 1px solid #eee;
	border-right: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
	text-decoration: none;
	color: #0B55C4;
}
/** for massmail component **/
td#mm_pane			{ width: 90%; }
input#mm_subject    { width: 200px; }
textarea#mm_message { width: 100%; }
/* pane-sliders  */
.pane-sliders .title {
	margin: 0;
	padding: 2px;
	color: #666;
	cursor: pointer;
}
.pane-sliders .panel   { border: 1px solid #ccc; margin-bottom: 3px;}
.pane-sliders .panel h3 { background: #f6f6f6; color: #666}
.pane-sliders .content { background: #f6f6f6; }
.pane-sliders .adminlist     { border: 0 none; }
.pane-sliders .adminlist td  { border: 0 none; }
.jpane-toggler  span     { background: transparent url(../images/j_arrow.png) 5px 50% no-repeat; padding-left: 20px;}
.jpane-toggler-down span { background: transparent url(../images/j_arrow_down.png) 5px 50% no-repeat; padding-left: 20px;}
.jpane-toggler-down {  border-bottom: 1px solid #ccc; }
/* tabs */
dl.tabs {
	float: left;
	margin: 10px 0 -1px 0;
	z-index: 50;
}
dl.tabs dt {
	float: left;
	padding: 4px 10px;
	border-left: 1px solid #ccc;
	border-right: 1px solid #ccc;
	border-top: 1px solid #ccc;
	margin-left: 3px;
	background: #f0f0f0;
	color: #666;
}
dl.tabs dt.open {
	background: #F9F9F9;
	border-bottom: 1px solid #F9F9F9;
	z-index: 100;
	color: #000;
}
div.current {
	clear: both;
	border: 1px solid #ccc;
	padding: 10px 10px;
}
div.current dd {
	padding: 0;
	margin: 0;
}
/** cpanel settings **/
#cpanel div.icon {
	text-align: center;
	margin-right: 5px;
	float: left;
	margin-bottom: 5px;
}
#cpanel div.icon a {
	display: block;
	float: left;
	border: 1px solid #f0f0f0;
	height: 97px;
	width: 108px;
	color: #666;
	vertical-align: middle;
	text-decoration: none;
}
#cpanel div.icon a:hover {
	border-left: 1px solid #eee;
	border-top: 1px solid #eee;
	border-right: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
	background: #f9f9f9;
	color: #0B55C4;
}
#cpanel img  { padding: 10px 0; margin: 0 auto; }
#cpanel span { display: block; text-align: center; }
/* standard form style table */
div.col { float: left; }
div.width-45 { width: 45%; }
div.width-55 { width: 55%; }
div.width-50 { width: 50%; }
div.width-70 { width: 70%; }
div.width-30 { width: 30%; }
div.width-60 { width: 60%; }
div.width-40 { width: 40%; }
table.admintable td 					 { padding: 3px; }
table.admintable td.key,
table.admintable td.paramlist_key {
	background-color: #f6f6f6;
	text-align: right;
	width: 140px;
	color: #666;
	font-weight: bold;
	border-bottom: 1px solid #e9e9e9;
	border-right: 1px solid #e9e9e9;
}
table.paramlist td.paramlist_description {
	background-color: #f6f6f6;
	text-align: left;
	width: 170px;
	color: #333;
	font-weight: normal;
	border-bottom: 1px solid #e9e9e9;
	border-right: 1px solid #e9e9e9;
}
table.admintable td.key.vtop { vertical-align: top; }
table.adminform {
	background-color: #f9f9f9;
	border: solid 1px #d5d5d5;
	width: 100%;
	border-collapse: collapse;
	margin: 8px 0 10px 0;
	margin-bottom: 15px;
	width: 100%;
}
table.adminform.nospace { margin-bottom: 0; }
table.adminform tr.row0 { background-color: #f9f9f9; }
table.adminform tr.row1 { background-color: #eeeeee; }
table.adminform th {
	font-size: 11px;
	padding: 6px 2px 4px 4px;
	text-align: left;
	height: 25px;
	color: #000;
	background-repeat: repeat;
}
table.adminform td { padding: 3px; text-align: left; }
table.adminform td.filter{
	text-align: left;
}
table.adminform td.helpMenu{
	text-align: right;
}
fieldset.adminform { border: 1px solid #ccc; margin: 0 10px 10px 10px; }
/** Table styles **/
table.adminlist {
	width: 100%;
	border-spacing: 1px;
	background-color: #e7e7e7;
	color: #666;
}
table.adminlist td,
table.adminlist th { padding: 4px; }
table.adminlist thead th {
	text-align: center;
	background: #f0f0f0;
	color: #666;
	border-bottom: 1px solid #999;
	border-left: 1px solid #fff;
}
table.adminlist thead a:hover { text-decoration: none; }
table.adminlist thead th img { vertical-align: middle; }
table.adminlist tbody th { font-weight: bold; }
table.adminlist tbody tr			{ background-color: #fff;  text-align: left; }
table.adminlist tbody tr.row1 	{ background: #f9f9f9; border-top: 1px solid #fff; }
table.adminlist tbody tr.row0:hover td,
table.adminlist tbody tr.row1:hover td  { background-color: #ffd ; }
table.adminlist tbody tr td 	   { height: 25px; background: #fff; border: 1px solid #fff; }
table.adminlist tbody tr.row1 td { background: #f9f9f9; border-top: 1px solid #FFF; }
table.adminlist tfoot tr { text-align: center;  color: #333; }
table.adminlist tfoot td,
table.adminlist tfoot th { background-color: #f3f3f3; border-top: 1px solid #999; text-align: center; }
table.adminlist td.order 		{ text-align: center; white-space: nowrap; }
table.adminlist td.order span { float: left; display: block; width: 20px; text-align: center; }
table.adminlist .pagination { display:table; padding:0;  margin:0 auto;	 }
.pagination div.limit {
	float: left;
	height: 22px;
	line-height: 22px;
	margin: 0 10px;
}
/** stu nicholls solution for centering divs **/
.container {clear:both; text-decoration:none;}
* html .container {display:inline-block;}
/** table solution for global config **/
table.noshow   		 { width: 100%; border-collapse: collapse; padding: 0; margin: 0; }
table.noshow tr 		 { vertical-align: top; }
table.noshow td 		 { }
table.noshow fieldset { margin: 15px 7px 7px 7px; }
#editor-xtd-buttons { padding: 5px; }
/* -- buttons -> STILL NEED CLEANUP*/
.button1,
.button1 div{
	height: 1%;
	float: right;
}
.button2-left,
.button2-right,
.button2-left div,
.button2-right div {
	float: left;
}
.button1 { background: url(../images/j_button1_left.png) no-repeat; white-space: nowrap; padding-left: 10px; margin-left: 5px;}
.button1 .next { background: url(../images/j_button1_next.png) 100% 0 no-repeat; }
.button1 a {
	display: block;
	height: 26px;
	float: left;
	line-height: 26px;
	font-size: 12px;
	font-weight: bold;
	color: #333;
	cursor: pointer;
	padding: 0 30px 0 6px;
}
.button1 a:hover { text-decoration: none; color: #0B55C4; }
.button2-left a,
.button2-right a,
.button2-left span,
.button2-right span {
	display: block;
	height: 22px;
	float: left;
	line-height: 22px;
	font-size: 11px;
	color: #333;
	cursor: pointer;
}
.button2-left span,
.button2-right span { cursor: default; color: #999; }
.button2-left .page a,
.button2-right .page a,
.button2-left .page span,
.button2-right .page span,
.button2-left .blank a,
.button2-right .blank a,
.button2-left .blank span,
.button2-right .blank span { padding: 0 6px; }
.page span,
.blank span {
	color: #000;
	font-weight: bold;
}
.button2-left a:hover,
.button2-right a:hover { text-decoration: none; color: #0B55C4; }
.button2-left a,
.button2-left span { padding: 0 24px 0 6px; }
.button2-right a,
.button2-right span { padding: 0 6px 0 24px; }
.button2-left { background: url(../images/j_button2_left.png) no-repeat; float: left; margin-left: 5px; }
.button2-right { background: url(../images/j_button2_right.png) 100% 0 no-repeat; float: left; margin-left: 5px; }
.button2-right .prev { background: url(../images/j_button2_prev.png) no-repeat; }
.button2-right.off .prev { background: url(../images/j_button2_prev_off.png) no-repeat; }
.button2-right .start { background: url(../images/j_button2_first.png) no-repeat; }
.button2-right.off .start { background: url(../images/j_button2_first_off.png) no-repeat; }
.button2-left .page,
.button2-left .blank { background: url(../images/j_button2_right_cap.png) 100% 0 no-repeat; }
.button2-left .next { background: url(../images/j_button2_next.png) 100% 0 no-repeat; }
.button2-left.off .next { background: url(../images/j_button2_next_off.png) 100% 0 no-repeat; }
.button2-left .end { background: url(../images/j_button2_last.png) 100% 0 no-repeat; }
.button2-left.off .end { background: url(../images/j_button2_last_off.png) 100% 0 no-repeat; }
.button2-left .image 		{ background: url(../images/j_button2_image.png) 100% 0 no-repeat; }
.button2-left .readmore 	{ background: url(../images/j_button2_readmore.png) 100% 0 no-repeat; }
.button2-left .pagebreak 	{ background: url(../images/j_button2_pagebreak.png) 100% 0 no-repeat; }
.button2-left .blank	 	{ background: url(../images/j_button2_blank.png) 100% 0 no-repeat; }
/* Tooltips */
.tool-tip {
	float: left;
	background: #ffc;
	border: 1px solid #D4D5AA;
	padding: 5px;
	max-width: 200px;
	z-index: 50;
}
.tool-title {
	padding: 0;
	margin: 0;
	font-size: 100%;
	font-weight: bold;
	margin-top: -15px;
	padding-top: 15px;
	padding-bottom: 5px;
	background: url(../images/selector-arrow.png) no-repeat;
}
.tool-text {
	font-size: 100%;
	margin: 0;
}
/* Calendar */
a img.calendar {
	width: 16px;
	height: 16px;
	margin-left: 3px;
	background: url(../images/calendar.png) no-repeat;
	cursor: pointer;
	vertical-align: middle;
}
/* System Standard Messages */
#system-message dd.message ul { background: #C3D2E5 url(../images/notice-info.png) 4px center no-repeat;}
/* System Error Messages */
#system-message dd.error ul { color: #c00; background: #E6C0C0 url(../images/notice-alert.png) 4px top no-repeat; border-top: 3px solid #DE7A7B; border-bottom: 3px solid #DE7A7B;}
/* System Notice Messages */
#system-message dd.notice ul { color: #c00; background: #EFE7B8 url(../images/notice-note.png) 4px top no-repeat; border-top: 3px solid #F0DC7E; border-bottom: 3px solid #F0DC7E;}
</style>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    <table width="90%">
        <tr>   
<td width="100%" style="font-size:14px; font-weight:bold"><a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-5/spider-video-player-wordpress-guide-step-5-1.html" target="_blank" style="color:blue; text-decoration:none;">User Manual</a><br />
This section allows you to create themes to customize the design of the player. <a href="https://web-dorado.com/spider-video-player-wordpress-guide-step-5/spider-video-player-wordpress-guide-step-5-1.html" target="_blank" style="color:blue; text-decoration:none;">More...</a></td>           </tr>
    <tr>
  <td width="100%"><h2><?php echo htmlspecialchars($row->title)?></h2></td>
<td id="priview_td" onclick="refresh_ctrl();"><a href="<?php echo admin_url('admin-ajax.php?action=spiderVeideoPlayerPrewieve') ?>&post_id=270&appWidth=640&appHeight=480&amp;TB_iframe=1&amp;width=640&amp;height=218" class="thickbox thickbox-preview" id="content-add_media" title="Spider Video Player" onclick="return false;"><input type="button"  value="preview" class="button-primary"></a></td>
  <td align="right"><input type="button" onclick="submitbutton('Save')" value="Save" class="button-secondary action"> </td>  
  <td align="right"><input type="button" onclick="submitbutton('Apply')" value="Apply" class="button-secondary action"> </td> 
  <td align="right"><input type="button" onclick="window.location.href='admin.php?page=Spider_Video_Player_Themes'" value="Cancel" class="button-secondary action"> </td> 
  </tr>
  </table>
<form action="admin.php?page=Spider_Video_Player_Themes&id=<?php echo $id; ?>" method="post" id="adminForm" name="adminForm" >
 
 
 
 
 
 
 
 
 
 <div style="float:left; width:390px"">
 <fieldset class="adminform">
						<legend>General Parameters</legend>
                        <table class="admintable">
                        <tr>
							<td class="key">
								<label for="title">
									<?php echo _e( 'Title of theme:' ); ?>
								</label>
							</td>
							<td >
                                    <input type="text" name="title" id="title" size="40" value="<?php echo $row->title; ?>"/>
							</td>
						</tr>
                         <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label id="appWidth-lbl" for="appWidth" >Width of player:</label>
                                </span>
                            </td>
                            <td class="paramlist_value">
                                <input type="text" style="" name="appWidth" id="appWidth" value="<?php echo $row->appWidth; ?>" class="text_area" onchange="refresh_()" onkeypress="return check_isnum(event)" />
                            </td>
                    	</tr>
                        <tr>
                        <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label id="appHeight-lbl" for="appHeight">Height of player:</label>
                            </span>
                            </td>
                            <td class="paramlist_value">
                                <input type="text" name="appHeight" id="appHeight"  class="text_area"  value="<?php echo $row->appHeight; ?>" onchange="refresh_()" onkeypress="return check_isnum(event)" />
                            </td>
                  	  	</tr>
                         <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Start with:</label>
                            </span>
                            </td>
                           <td class="paramlist_value">                        
                            <input type="radio" name="startWithLib" id="startWithLib0" value="0" <?php cheched($row->startWithLib,0) ?>  class="inputbox">
                            <label for="startWithLib0">Video</label>
                            <input type="radio" name="startWithLib" id="startWithLib1" value="1" <?php cheched($row->startWithLib,1) ?> class="inputbox">
                            <label for="startWithLib1">Library</label>
                   		 </td>
             		   </tr>
                       <tr>
                            <td class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Show Track Id</label>
                            </span>
                            </td>
                            <td class="paramlist_value">
                                <input type="radio" name="show_trackid" id="show_trackid0" value="0" <?php cheched($row->show_trackid,0) ?>><label for="show_trackid0">No</label>
                                <input type="radio" name="show_trackid" value="1" id="show_trackid1"  <?php cheched($row->show_trackid,1) ?>><label for="show_trackid1">Yes</label> 
                            </td>
                        </tr>
                        <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Auto hide time:</label>
                            </span>
                            </td>
                            <td class="paramlist_value">
                                <input type="text" name="autohideTime" id="autohideTime" value="<?php echo $row->autohideTime ?>" class="text_area" onkeypress="return check_isnum(event)">sec
                            </td>
                        </tr>
            
                        <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Keep aspect ratio:</label>
                            </span>
                            </td>
                           <td class="paramlist_value">
                                
                                <input type="radio" name="keepAspectRatio" id="keepAspectRatio0" value="0" class="inputbox" <?php cheched($row->keepAspectRatio,0) ?>>
                                <label for="keepAspectRatio0">No</label>
                                <input type="radio" name="keepAspectRatio" id="keepAspectRatio1" value="1" <?php cheched($row->keepAspectRatio,1) ?> class="inputbox">
                                <label for="keepAspectRatio1">Yes</label>
                            </td>
                        </tr>
                         <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Control bar over video (only for flash):</label>
                            </span>
                            </td>
                            <td class="paramlist_value">
                                
                                <input type="radio" name="ctrlsOverVid" id="ctrlsOverVid0" value="0" <?php cheched($row->ctrlsOverVid,0) ?> class="inputbox">
                                <label for="ctrlsOverVid0">No</label>
                                <input type="radio" name="ctrlsOverVid" id="ctrlsOverVid1" value="1" <?php cheched($row->ctrlsOverVid,1) ?> class="inputbox">
                                <label for="ctrlsOverVid1">Yes</label>
                            </td>
                		</tr>
                         <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Watermark image (only for flash):</label>
                                </span>
                                </td>
                                <td>
                                    <input type="text" value="<?php if($row->watermarkUrl )echo htmlspecialchars($row->watermarkUrl); ?>" name="watermarkUrl" id="post_image" class="text_input" style="width:137px"/><a class="button lu_upload_button" href="#" />Select</a><br />
                                    <a href="javascript:removeImage();">Remove Image</a><br />
                                                 <div style="height:150px;">
                                                           <img style=" display:<?php if($row->watermarkUrl=='') echo 'none'; else echo 'block' ?>; border:none;" height="150" id="imagebox" src="<?php echo $row->watermarkUrl ?>" />     
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
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Watermark Position (only for flash):</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="radio" name="watermarkPos" id="watermarkPos1" value="1" <?php cheched($row->watermarkPos,1) ?> ><label for="watermarkPos1"> Top left</label>
                                    <input type="radio" name="watermarkPos" id="watermarkPos2" value="2" <?php cheched($row->watermarkPos,2) ?> ><label for="watermarkPos2"> Top right</label>
                                    <input type="radio" name="watermarkPos" id="watermarkPos3" value="3" <?php cheched($row->watermarkPos,3) ?> ><label for="watermarkPos3"> Bottom left</label>
                                    <input type="radio" name="watermarkPos" id="watermarkPos4" value="4" <?php cheched($row->watermarkPos,4) ?> ><label for="watermarkPos4"> Bottom right</label>
                                </td>
              			  </tr>
                          <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Watermark size (only for flash):</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="text" name="watermarkSize" id="watermarkSize" value="<?php echo $row->watermarkSize; ?>" class="text_area" onkeypress="return check_isnum(event)">pixels
                                </td>
                            </tr>
                             <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Watermark Margin (only for flash):</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="text" name="watermarkSpacing" id="watermarkSpacing" value="<?php echo $row->watermarkSpacing; ?>" class="text_area" onkeypress="return check_isnum(event)">pixels
                                </td>
                            </tr>
                             <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Watermark transparency (only for flash):</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <p style="border:0; color:#f6931f;">
                                        <input type="text" name="watermarkAlpha" id="watermarkAlpha"  value="<?php echo $row->watermarkAlpha; ?>" style="border:0; color:#f6931f; font-weight:bold; width:30px" onkeypress="return check_isnum(event)">%
                                    </p>
                                    <div id="slider-watermarkAlpha" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" style="width:240px"></div>
                                </td>
                            </tr>
                        </table>
</fieldset>
</div>
<div style="float:left; width:390px"">
<fieldset class="adminform">
						<legend>Style Parameters</legend>
                        <table class="admintable">
                                    
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Center button transparency (only for flash):</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <p style="border:0; color:#f6931f;">
                                        <input type="text" name="centerBtnAlpha"   value="<?php echo $row->centerBtnAlpha ?>" id="centerBtnAlpha" style="border:0; color:#f6931f; font-weight:bold; width:30px" onkeypress="return check_isnum(event)">%
                                    </p>
                                    <div id="slider-centerBtnAlpha" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" style="width:240px"></div>
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Background color:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="text" name="appBgColor" id="appBgColor" value="<?php echo $row->appBgColor; ?>" class="color">
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Video background color:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="text" name="vidBgColor" id="vidBgColor" value="<?php echo $row->vidBgColor; ?>" class="color">
                                </td>
                            </tr>
                             <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Frames background color:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="text" name="framesBgColor" id="framesBgColor" value="<?php echo $row->framesBgColor; ?>" class="color">
                                </td>
                            </tr>
                             <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Frames background transparency:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <p style="border:0; color:#f6931f;">
                                        <input type="text" name="framesBgAlpha" id="framesBgAlpha" value="<?php echo $row->framesBgAlpha; ?>" style="border:0; color:#f6931f; font-weight:bold; width:30px" onkeypress="return check_isnum(event)">%
                                    </p>
                                    <div id="slider-framesBgAlpha" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" style="width:240px"></div>
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Control buttons main color:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="text" name="ctrlsMainColor" id="ctrlsMainColor" value="<?php echo $row->ctrlsMainColor; ?>" class="color">
                                </td>
               				 </tr>
                          <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Control buttons hover color (only for flash):	</label>
                            </span>
                            </td>
                            <td class="paramlist_value">
                                <input type="text" name="ctrlsMainHoverColor" id="ctrlsMainHoverColor" value="<?php echo $row->ctrlsMainHoverColor; ?>" class="color">
                            </td>
                        </tr>
                        <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Opacity:</label>
                            </span>
                            </td>
                            <td class="paramlist_value">
                                <p style="border:0; color:#f6931f;">
                                    <input type="text" name="ctrlsMainAlpha"  value="<?php echo $row->ctrlsMainAlpha; ?>" id="ctrlsMainAlpha" style="border:0; color:#f6931f; font-weight:bold; width:30px" onkeypress="return check_isnum(event)">%
                                </p>
                                <div id="slider-ctrlsMainAlpha" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" style="width:240px"></div>
                            </td>
                        </tr>
                        <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Sliders color:</label>
                            </span>
                            </td>
                            <td class="paramlist_value">
                                <input type="text" name="slideColor" id="slideColor" value="<?php echo $row->slideColor; ?>" class="color">
                            </td>
                        </tr>
                        <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Hovered item background Color:</label>
                            </span>
                            </td>
                            <td class="paramlist_value">
                                <input type="text" name="itemBgHoverColor" id="itemBgHoverColor" value="<?php echo $row->itemBgHoverColor; ?>" class="color">
                            </td>
                        </tr>
                        <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Selected item background Color:</label>
                            </span>
                            </td>
                            <td class="paramlist_value">
                                <input type="text" name="itemBgSelectedColor" id="itemBgSelectedColor" value="<?php echo $row->itemBgSelectedColor; ?>" class="color">
                            </td>
                        </tr>
                         <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Text color:</label>
                            </span>
                            </td>
                            <td class="paramlist_value">
                                <input type="text" name="textColor" id="textColor" value="<?php echo $row->textColor; ?>" class="color">
                            </td>
                        </tr>
                        <tr>
                    <td  class="paramlist_key">
                        <span class="editlinktip">
                            <label>Hovered text color:</label>
                    </span>
                    </td>
                    <td class="paramlist_value">
                        <input type="text" name="textHoverColor" id="textHoverColor" value="<?php echo $row->textHoverColor; ?>" class="color">
                    </td>
                </tr>
                 <tr>
                    <td  class="paramlist_key">
                        <span class="editlinktip">
                            <label>Selected text color:</label>
                    </span>
                    </td>
                    <td class="paramlist_value">
                        <input type="text" name="textSelectedColor" id="textSelectedColor" value="<?php echo $row->textSelectedColor; ?>" class="color">
                    </td>
                </tr>
                <tr>
                    <td  class="paramlist_key">
                        <span class="editlinktip">
                            <label>Loading animation type (only for flash):</label>
                    </span>
                    </td>
                    <td class="paramlist_value">
                        <input type="radio" id="loadinAnimType1" name="loadinAnimType" value="1" <?php cheched($row->loadinAnimType,1) ?>><label for="loadinAnimType1"> Circles</label>
                        <input type="radio" id="loadinAnimType2" name="loadinAnimType" value="2" <?php cheched($row->loadinAnimType,2) ?>><label for="loadinAnimType2"> Lines</label>
                    </td>
                
                <tr>
                                
                        </table>
</fieldset>
</div>
<div style="float:left; width:390px;">
<fieldset class="adminform">
						<legend>Playlist and Library Parameters</legend>
                        <table class="admintable">
                       		 <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Playlist Position:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="radio" name="playlistPos" id="playlistPos1" value="1" <?php cheched($row->playlistPos,1) ?>><label for="show_trackid1"> Left</label>
                                    <input type="radio" name="playlistPos" id="playlistPos2" value="2" <?php cheched($row->playlistPos,2) ?>><label for="show_trackid1">Right</label>
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label id="playlistWidth-lbl" for="playlistWidth">Width of playlist:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="text" name="playlistWidth" id="playlistWidth" value="<?php echo $row->playlistWidth; ?>" class="text_area"  onkeypress="return check_isnum(event)" />
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Playlist over video:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    
                                    <input type="radio" name="playlistOverVid" id="playlistOverVid0" value="0" <?php cheched($row->playlistOverVid,0) ?> class="inputbox">
                                    <label for="playlistOverVid0">No</label>
                                    <input type="radio" name="playlistOverVid" id="playlistOverVid1" value="1" <?php cheched($row->playlistOverVid,1) ?> class="inputbox">
                                    <label for="playlistOverVid1">Yes</label>
                                </td>
                            </tr>
							<tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Open playlist at start:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    
                                    <input type="radio" name="openPlaylistAtStart" id="openPlaylistAtStart0" value="0" <?php cheched($row->openPlaylistAtStart,0) ?> class="inputbox">
                                    <label for="playlistAutoHide0">No</label>
                                    <input type="radio" name="openPlaylistAtStart" id="openPlaylistAtStart1" value="1" <?php cheched($row->openPlaylistAtStart,1) ?> class="inputbox">
                                    <label for="playlistAutoHide1">Yes</label>
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Playlist auto hide:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    
                                    <input type="radio" name="playlistAutoHide" id="playlistAutoHide0" value="0" <?php cheched($row->playlistAutoHide,0) ?> class="inputbox">
                                    <label for="playlistAutoHide0">No</label>
                                    <input type="radio" name="playlistAutoHide" id="playlistAutoHide1" value="1" <?php cheched($row->playlistAutoHide,1) ?> class="inputbox">
                                    <label for="playlistAutoHide1">Yes</label>
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Playlist text size:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="text" name="playlistTextSize" id="playlistTextSize" value="<?php echo $row->playlistTextSize; ?>" class="text_area" onkeypress="return check_isnum(event)">pixels
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Library colums:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="text" name="libCols" id="libCols" value="<?php echo $row->libCols; ?>" class="text_area" onkeypress="return check_isnum(event)">
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Library rows:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="text" name="libRows" id="libRows" value="<?php echo $row->libRows; ?>" class="text_area" onkeypress="return check_isnum(event)">
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Library list text size:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="text" name="libListTextSize" id="libListTextSize" value="<?php echo $row->libListTextSize; ?>" class="text_area" onkeypress="return check_isnum(event)">pixels
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Library details text size:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="text" name="libDetailsTextSize" id="libDetailsTextSize" value="<?php echo $row->libDetailsTextSize; ?>" class="text_area" onkeypress="return check_isnum(event)">pixels
                                </td>
                            </tr>
                
                        
                        
                        </table>
</fieldset>
</div>
<div style="float:left; width:390px;">
<fieldset class="adminform">
						<legend>Playback Parameters</legend>
                        <table class="admintable">
                         <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Auto play:</label>
                            </span>
                            </td>
                            <td class="paramlist_value">   
                                <input type="radio" name="autoPlay" id="autoPlay0" value="0" <?php cheched($row->autoPlay,0) ?> class="inputbox">
                                <label for="autoPlay0">No</label>
                                <input type="radio" name="autoPlay" id="autoPlay1" value="1" <?php cheched($row->autoPlay,1) ?> class="inputbox">
                                <label for="autoPlay1">Yes</label>
                            </td>
                        </tr>
                           <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Auto next song:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">                
                                <input type="radio" name="autoNext" id="autoNext0" value="0" <?php cheched($row->autoNext,0) ?> class="inputbox">
                                <label for="autoNext0">No</label>
                                <input type="radio" name="autoNext" id="autoNext1" value="1" <?php cheched($row->autoNext,1) ?> class="inputbox">
                                <label for="autoNext1">Yes</label>
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Auto next album:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">                        
                                    <input type="radio" name="autoNextAlbum" id="autoNextAlbum0" value="0" <?php cheched($row->autoNextAlbum,0) ?> class="inputbox">
                                    <label for="autoNextAlbum0">No</label>
                                    <input type="radio" name="autoNextAlbum" id="autoNextAlbum1" value="1" <?php cheched($row->autoNextAlbum,1) ?> class="inputbox">
                                    <label for="autoNextAlbum1">Yes</label>
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Default Volume:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <p style="border:0; color:#f6931f;">
                                        <input type="text" name="defaultVol" value="<?php echo $row->defaultVol; ?>" id="defaultVol" style="border:0; color:#f6931f; font-weight:bold; width:30px" onkeypress="return check_isnum(event)">%
                                    </p>
                                    <div id="slider-defaultVol" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" style="width:240px"></div>
                                </td>
                            </tr>
                            <tr>
                                <td  class="paramlist_key">
                                    <span class="editlinktip">
                                        <label>Repeat:</label>
                                </span>
                                </td>
                                <td class="paramlist_value">
                                    <input type="radio" id="defaultRepeat1" name="defaultRepeat" value="repeatOff"  <?php cheched($row->defaultRepeat,"repeatOff") ?>><label for="defaultRepeat1">Off</label> 
                                    <input type="radio" id="defaultRepeat2" name="defaultRepeat" value="repeatOne"  <?php cheched($row->defaultRepeat,"repeatOne") ?>><label for="defaultRepeat2">One</label>  
                                    <input type="radio" id="defaultRepeat3" name="defaultRepeat" value="repeatAll"  <?php cheched($row->defaultRepeat,"repeatAll") ?>><label for="defaultRepeat3">All</label> 
                                </td>
                            </tr>
                            <tr>
                                    <td  class="paramlist_key">
                                        <span class="editlinktip">
                                            <label>Shuffle:</label>
                                    </span>
                                    </td>
                                    <td class="paramlist_value">
                                        <input type="radio" id="defaultShuffle1" name="defaultShuffle" value="shuffleOff" <?php cheched(str_replace ('Shuffle', 'shuffle', $row->defaultShuffle),"shuffleOff") ?>><label for="defaultShuffle1">Off</label> 
                
                                        <input type="radio" id="defaultShuffle2"  name="defaultShuffle" value="shuffleOn" <?php cheched(str_replace ('Shuffle', 'shuffle', $row->defaultShuffle),"shuffleOn") ?>><label for="defaultShuffle2">On</label> 
                                    </td>
                                </tr>
                                 <tr>
                                    <td  class="paramlist_key">
                                        <span class="editlinktip">
                                            <label>Control bar auto hide:</label>
                                    </span>
                                    </td>
                                    <td class="paramlist_value">
                                        
                                            <input type="radio" name="ctrlsSlideOut" id="ctrlsSlideOut0" value="0" <?php cheched($row->ctrlsSlideOut,0) ?> class="inputbox">
                                            <label for="ctrlsSlideOut0">No</label>
                                            <input type="radio" name="ctrlsSlideOut" id="ctrlsSlideOut1" value="1" <?php cheched($row->ctrlsSlideOut,1) ?> class="inputbox">
                                            <label for="ctrlsSlideOut1">Yes</label>
                                    </td>
                                </tr>
                        
                        
                        </table>
</fieldset>
</div>
<div style="width:100%;">
<fieldset class="adminform"  style="width: 100%;">
						<legend>Video Control Parameters</legend>
                        <table class="admintable">
                        <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Play/pause on click:</label>
                            </span>
                            </td>
                            <td class="paramlist_value">
                                
                                <input type="radio" name="clickOnVid" id="clickOnVid0" value="0" class="inputbox" <?php cheched($row->clickOnVid,0) ?>>
                                <label for="clickOnVid0">No</label>
                                <input type="radio" name="clickOnVid" id="clickOnVid1" value="1" <?php cheched($row->clickOnVid,1) ?> class="inputbox">
                                <label for="clickOnVid1">Yes</label>
                            </td>
                        </tr>
                         <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Play/pause by space key:</label>
                            </span>
                            </td>
                           <td class="paramlist_value">
                                
                                    <input type="radio" name="spaceOnVid" id="spaceOnVid0" value="0" <?php cheched($row->spaceOnVid,0) ?> class="inputbox">
                                    <label for="spaceOnVid0">No</label>
                                    <input type="radio" name="spaceOnVid" id="spaceOnVid1" value="1" <?php cheched($row->spaceOnVid,1) ?> class="inputbox">
                                    <label for="spaceOnVid1">Yes</label>
                            </td>
                        </tr>
                        <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Volume control by mouse scroll (only for flash):</label>
                            </span>
                            </td>
                           <td class="paramlist_value">
                                                    
                                <input type="radio" name="mouseWheel" id="mouseWheel0" value="0"   <?php cheched($row->mouseWheel,0) ?> class="inputbox">
                                <label for="mouseWheel0">No</label>
                                <input type="radio" name="mouseWheel" id="mouseWheel1" value="1"  <?php cheched($row->mouseWheel,1) ?> class="inputbox">
                                <label for="mouseWheel1">Yes</label>
                            </td>
                        </tr>
                        <tr>
                            <td  class="paramlist_key">
                                <span class="editlinktip">
                                    <label>Control bar position:</label>
                            </span>
                            </td>
                            <td class="paramlist_value">
                                <input type="radio" name="ctrlsPos" id="ctrlsPos1" value="1" <?php cheched($row->ctrlsPos,1) ?>><label for="ctrlsPos1">Up</label> 
                                <input type="radio" name="ctrlsPos" id="ctrlsPos2" value="2" <?php cheched($row->ctrlsPos,2) ?>><label for="ctrlsPos2">Down</label>
                            </td>
                        </tr>
                        <tr>
                    <td  class="paramlist_key">
                        <span class="editlinktip">
                            <label>Buttons order on control bar:</label>
                    </span>
                    </td>
                    <td class="paramlist_value">
                        <table border="0" style="background-color:#666" cellpadding="0" cellspacing="0" width="100%">
                                        <?php 
										echo '<tr valign="top" id="tr_arr" valign="middle">';
                                        foreach($ctrls as $key =>  $x) 
                                         {
                                                $y = explode(":", $x);
                                                $ctrl	=$y[0];
                                                $active	=$y[1];
                                                    echo '<td id="td_arr_'.$key.'"  active="'.$active.'" value="'.$ctrl.'" width="40" align="center" style="padding:4px">
                                                                <img src="'.$path.$ctrl.'_'.$active.'.png" id="arr_'.$key.'" image="'.$ctrl.'" style=" cursor:pointer; border:none;"/>
                                                            </td>';
                                        }
                                         echo '</tr>';
										 ?>
                        </table>           
                    <input type="hidden" name="ctrlsStack" id="ctrlsStack" value="<?php echo $value; ?>" size="100">
                    </td>
                </tr>
                        
                        
                        </table>
</fieldset>
</div>
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
    <?php wp_nonce_field('nonce_sp_vid', 'nonce_sp_vid'); ?>           
    <input type="hidden" name="option" value="com_player" />
    <input type="hidden" name="task" value="" />
</form>
<?php	
	//JToolBarHelper::preferences('com_player', '700','1000','Spider_Video_Player Parameters','');
	?>
<?php
}
function cheched($row,$y)
{
	if($row==$y)
	{
			echo'checked="checked"';
	}
}
?>