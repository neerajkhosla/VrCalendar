var key = window.options.api_key
window.onload=function(){
			var ifrm = document.createElement("iframe");
			ifrm.setAttribute("src", "http://vrcalendarsync.local/shorcode.php?api_key="+key);
			ifrm.style.width = "640px";
			if(window.options.width){
				ifrm.style.width = window.options.width;
			}
			ifrm.style.height = "480px";
			if(window.options.height){
				ifrm.style.height = window.options.height;
			}			
			document.body.appendChild(ifrm);
	}


