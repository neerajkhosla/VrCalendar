<?php 

add_shortcode('DAPLoginForm', 'dap_loginform');

function dap_loginform($atts, $content=null){ 
	
	return dap_login("%%LOGIN_FORM%%","");
}


?>