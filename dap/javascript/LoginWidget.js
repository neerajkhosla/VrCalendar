/* <![CDATA[ */
var dap_authenticate_url = "/dap/authenticate.php";

function validateW(form) {
	//alert("inside validate: " + form.name);
	
	for(i=0; i<form.elements.length; i++){
   		if( (form.elements[i].name == "email") || (form.elements[i].name == "password")) {
			//alert(form.elements[i].name + "=>" + form.elements[i].value);
			if(form.elements[i].value == "") {
				//alert(form.elements[i].name);
				alert(unescape(MSG_MISSING_INFO));
				form.elements[i].focus();
				return false;
			}
		}
   	}

	form.action = dap_authenticate_url;
    form.submit();
}

function doForgotPasswordW() {
	form = document.loginFormW;
	
	if(form.email.value == "") {
		alert(unescape(MSG_ENTER_EMAIL)); 
		form.email.focus();
		return false;
	}
	
	form.forgot.value = "Y";
    form.action = dap_authenticate_url;
    form.submit();
	
}
/* ]]> */
