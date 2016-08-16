(function() {
	tinymce.create('tinymce.plugins.dapshoppingcartbuttonPlugin', {
		init : function(edsmpl, url) {
			// Register commands
			edsmpl.addCommand('dapshoppingcartmcebutton', function() {
				edsmpl.windowManager.open({
					title : 'Add dapshoppingcartEditor Button',
					file : url + '/dapshoppingcarteditor_manager.php', // file that contains HTML for our modal window
					width : 900 + parseInt(edsmpl.getLang('button.delta_width', 0)), // size of our window
					height : 700 + parseInt(edsmpl.getLang('button.delta_height', 0)), // size of our window
					inline : 1
				}, {
					plugin_url : url
				});
			});
			 
			// Register buttons
			edsmpl.addButton('dapshoppingcarteditor_button', {title : 'DAP Shopping Cart', cmd : 'dapshoppingcartmcebutton', image: url + '/includes/images/dapshoppingcarteditor.png' });
		},
		 
		getInfo : function() {
			return {
				longname : 'dapshoppingcart Editor',
				author : 'Veena Prashanth',
				authorurl : 'http://WickedCoolPlugins.com',
				infourl : 'http://WickedCoolPlugins.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});
	 
	// Register plugin
	// first parameter is the button ID and must match ID elsewhere
	// second parameter must match the first parameter of the tinymce.create() function above
	tinymce.PluginManager.add('dapshoppingcarteditor_button', tinymce.plugins.dapshoppingcartbuttonPlugin);

})();