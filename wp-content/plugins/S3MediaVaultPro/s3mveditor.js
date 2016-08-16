(function() {
	tinymce.create('tinymce.plugins.s3mvbuttonPlugin', {
		init : function(edltu, url) {
			// Register commands
			edltu.addCommand('s3mvmcebutton', function() {
				edltu.windowManager.open({
					title : 'Add s3mvEditor Button',
					file : url + '/s3mveditor_manager.php', // file that contains HTML for our modal window
					width : 800 + parseInt(edltu.getLang('button.delta_width', 0)), // size of our window
					height : 640 + parseInt(edltu.getLang('button.delta_height', 0)), // size of our window
					inline : 1
				}, {
					plugin_url : url
				});
			});
			 
			// Register buttons
			edltu.addButton('s3mveditor_button', {title : 'S3MediaVault', cmd : 's3mvmcebutton', image: url + '/includes/images/s3mveditor.png' });
		},
		 
		getInfo : function() {
			return {
				longname : 'S3MediaVault Shortcode Editor',
				author : 'Veena Prashanth & Ravi Jayagopal',
				authorurl : 'http://WickedCoolPlugins.com',
				infourl : 'http://WickedCoolPlugins.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});
	 
	// Register plugin
	// first parameter is the button ID and must match ID elsewhere
	// second parameter must match the first parameter of the tinymce.create() function above
	tinymce.PluginManager.add('s3mveditor_button', tinymce.plugins.s3mvbuttonPlugin);

})();