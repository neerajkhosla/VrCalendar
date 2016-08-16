/* <![CDATA[ */
		 
var authenticate_url = "/dap/authenticate.php";

function validateDAPLoginInc(form) {
	//alert("inside validateDAPLoginInc");
	var badTextArray = new Array("\"","`","!","%","^","&","*","(",")","'",";");
	
	for(i=0; i<form.elements.length; i++){
   		if( (form.elements[i].name == "email") || (form.elements[i].name == "password")) {
			//alert(form.elements[i].name + "=>" + form.elements[i].value);
			if(form.elements[i].value == "") {
				alert(unescape(MSG_MISSING_INFO));
				form.elements[i].focus();
				return false;
			}

			for(j=0; j<badTextArray.length; j++){
				//alert(badTextArray[j]);
				if(form.elements[i].value.indexOf(badTextArray[j]) != -1) {
					alert(unescape(MSG_NO_SPECIAL));
					form.elements[i].select();
					form.elements[i].focus();
					return false;
				}
			}

		}
   	}
   
    form.action=authenticate_url;
    form.submit();
}

function doDapForgotPasswordLoginInc() {
	form = document.loginFormLoginInc;
	
	if(form.email.value == "") {
		alert(unescape(MSG_ENTER_EMAIL)); 
		form.email.focus();
		return false;
	}
	
	form.forgot.value = "Y";	
    form.action=authenticate_url;
    form.submit();
	
}

/* ]]> */
