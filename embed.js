var key = window.options.api_key
var title = window.options.title
window.onload=function(){
			var ifrm = document.createElement("iframe");
			ifrm.setAttribute("src", "http://vrcalendarsync.local/shorcode.php?api_key="+key+"&title="+title);
			ifrm.style.width = "640px";
			if(window.options.width){
				ifrm.style.width = window.options.width;
			}
			ifrm.style.height = "480px";
			if(window.options.height){
				ifrm.style.height = window.options.height;
			}	
			if(window.options.border){
				ifrm.style.border = window.options.border;
			}			
			document.body.appendChild(ifrm);
	}


