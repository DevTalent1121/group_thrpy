(function() {
    tinymce.create('tinymce.plugins.Spider_Video_Player_mce', {
 
        init : function(ed, url){
			
			ed.addCommand('mceSpider_Video_Player_mce', function() {
				ed.windowManager.open({
					file : url + '/../window.php',
					width : 380 + ed.getLang('Spider_Video_Player_mce.delta_width', 0),
					height : 180 + ed.getLang('Spider_Video_Player_mce.delta_height', 0),
					inline : 1
				}, {
					plugin_url : url // Plugin absolute URL
				});
			});
            ed.addButton('Spider_Video_Player_mce', {
            title : 'Insert Spider Video Player',
			cmd : 'mceSpider_Video_Player_mce',
			image: svp_plugin_url + '/images/Spider_Video_PlayerLogo.png',
            });
        }
    });
 
    tinymce.PluginManager.add('Spider_Video_Player_mce', tinymce.plugins.Spider_Video_Player_mce);
 
})();