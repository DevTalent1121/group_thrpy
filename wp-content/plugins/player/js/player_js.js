jQuery(document).on('keydown','#search_events_by_title',function(e){
   if(e.keyCode == 13) {
        document.getElementById('page_number').value='1'; document.getElementById('serch_or_not').value='search';
        document.getElementById('admin_form').submit();
   }
});
jQuery("#admin_form a:contains('Delete')").live('click',function(){
    if(confirm("Are you sure you want to delete ?")){
        window.location = jQuery(this).attr('href-data');
    }else{
    }
});
jQuery(document).ready(function(){
	jQuery('body').on('change','#url_youtube',function () {
		var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
		var url = jQuery(this).val();
		var match = url.match(regExp);
		if (match) {
			jQuery(this).val( 'http://www.youtube.com/watch?v='+match[2]);
		}
	});
});