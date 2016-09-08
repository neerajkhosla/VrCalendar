var key = window.options.api_key
var title = "";
window.onload=function(){
			var container=document.createElement("div");
				container.setAttribute("id", "div_"+key);  
				
			var span =document.createElement("span");
				span.setAttribute("id", "span_"+key);
				span.style.fontFamily="arial"; 
				span.style.fontWeight="bold"; 
				span.style.textAlign="center"; 
				span.style.display ="Block"; 
				
			var loader_message = document.createTextNode("Please Wait....Calendar is loading"); 
				span.appendChild(loader_message); 
				container.appendChild(span);
				
			var ifrm = document.createElement("iframe");
			ifrm.setAttribute("src", "http://vrcalendar.customerdemourl.com/shorcode.php?api_key="+key+"&title="+title);
			ifrm.style.width = "100%";
			ifrm.frameBorder=0;
			if(window.options.width){
				ifrm.style.width = window.options.width;
			}
			ifrm.style.height = "100%";
			if(window.options.height){
				ifrm.style.height = window.options.height;
			}	
			if(window.options.border){
				ifrm.style.border = window.options.border;
			}			
			ifrm.setAttribute("id", key);
			ifrm.setAttribute("onload", 'frameload("span_'+key+'")');
			container.appendChild(ifrm);
			var s = document.getElementById("vr_"+key);
			s.parentNode.insertBefore(container, s);
	}
	function frameload(key){
		document.getElementById(key).innerHTML="";
	}


