	function getQueryVariable(variable) {
			var query = window.location.search.substring(1);
			var vars = query.split("?");
			for (var i=0;i<vars.length;i++) {
				var pair = vars[i].split("=");
				if (pair[0] == variable) {
				return pair[1];
				}
			} 
	}

	var param1var = getQueryVariable("apikey");
	alert(apikey);
	var iframe = document.createElement('iframe');       
	document.body.appendChild(iframe);
	iframe.src = 'http://vrcalendarsync.local/iframe/ ';       
	iframe.width = '100';
	iframe.height = '100';