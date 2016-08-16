/* <![CDATA[ */
var authenticate_url = "/dap/authenticate.php";

function validateDAPLoginForm(form) {
	//alert("inside validateDAPLoginForm: " + form.name);
	var badTextArray = new Array("\"","`","!","%","^","&","*","(",")","'",";");
	
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

	form.action = authenticate_url;
    form.submit();
}

function doForgotPassword() {
	form = document.loginFormLoginForm;
	
	if(form.email.value == "") {
		alert(unescape(MSG_ENTER_EMAIL)); 
		form.email.focus();
		return false;
	}
	
	form.forgot.value = "Y";	
    form.action = authenticate_url;
    form.submit();
	
}
/* ]]> */
