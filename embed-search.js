var key_search_s = window.options_search.api_key_search;
var title_search_s = window.options_search.title;

window.onload=function(){
			var container_s=document.createElement("div");
				container_s.setAttribute("id", "div_"+key_search_s);  
				
			var span_s =document.createElement("span_s");
				span_s.setAttribute("id", "span_s_"+key_search_s);
				span_s.style.fontFamily="arial"; 
				span_s.style.fontWeight="bold"; 
				span_s.style.textAlign="center"; 
				span_s.style.display ="Block"; 
				
			var loader_message_s = document.createTextNode("Please Wait....Search Bar is loading"); 
				span_s.appendChild(loader_message_s); 
				container_s.appendChild(span_s);
				
			var ifrm_s = document.createElement("iframe");
			ifrm_s.setAttribute("src","http://vrcalendar.customerdemourl.com/shortcode-search.php?api_key="+key_search_s+"&title="+title_search_s);
			ifrm_s.style.width = "100%";
			ifrm_s.frameBorder=0;
			if(window.options_search.width){
				ifrm_s.style.width = window.options_search.width;
			}
			ifrm_s.style.height = "100%";
			if(window.options_search.height){
				ifrm_s.style.height = window.options_search.height;
			}	
			if(window.options_search.border){
				ifrm_s.style.border = window.options_search.border;
			}			
			ifrm_s.setAttribute("id", key_search_s);
			ifrm_s.setAttribute("onload", 'frameload_s("span_s_'+key_search_s+'")');
			container_s.appendChild(ifrm_s);
			var s_s = document.getElementById("vr_"+key_search_s);
			s_s.parentNode.insertBefore(container_s, s_s);
	}
	function frameload_s(key_text){
		document.getElementById(key_text).innerHTML="";
	}





