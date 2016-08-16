/* <![CDATA[ */

var globalError = "";
var whatRequest = "";

function processChangeu(responseText, responseStatus, responseXML) {
	if (responseStatus == 200) {// 200 means "OK"
		var resource = eval('(' + responseText + ')');
		if(resource.whatRequest == 'loadAffiliatePayments') {
			changeDiv('aff_payments_div',resource.responseJSON);
		} else if(resource.whatRequest == 'loadAffiliateEarningsSummary') {
			changeDiv('aff_earnings_summary_div',resource.responseJSON);
		} else if(resource.whatRequest == 'loadAffiliateEarningsDetails') {
			changeDiv('aff_earnings_details_div',resource.responseJSON);
		} else if(resource.whatRequest == 'loadUser') {
			changeLoadUser(resource.responseJSON);
		} else if(resource.whatRequest == 'updateUser') {
			changeDiv('user_msg_div',resource.responseJSON);
			NLBfadeBg('user_msg_div','#009900','#FFFFFF','1000');
			changeDiv('user_msg_div_2',resource.responseJSON);
			NLBfadeBg('user_msg_div_2','#009900','#FFFFFF','1000');
			loadUser(affId);
		} else if(resource.whatRequest == 'loadAffiliateStats') {
			changeDiv('aff_stats_div',resource.responseJSON);
		} else if(resource.whatRequest == 'deleteCurrentPic') {
			changeDiv('current_pic_div',"<b>SUCCESS! Your current photo has been deleted.</b>");
			NLBfadeBg('current_pic_div','#009900','#FFFFFF','1500');
		}
	} else {// anything else means a problem
		//alert("There was a problem in the returned data:\n");
	}
}


function deleteCurrentPic() {
	url  =  '/dap/admin/ajax/deleteCurrentPicAjax.php';
	var request = new ajaxObject(url,processChangeu);
	request.update('userId=' + affId + '&whatRequest=deleteCurrentPic');
}

function loadUser() {
	url  =  '/dap/admin/ajax/loadUserAjax.php';
	var request = new ajaxObject(url,processChangeu);
	request.update('userId=' + affId + '&whatRequest=loadUser');
}


function changeLoadUser(user){
	form = document.UserProfileForm;
	
	//Fill form values
	form.first_name.value = (user[0].first_name == null) ? "" : user[0].first_name;
	form.last_name.value = (user[0].last_name == null) ? "" : user[0].last_name;
	form.user_name.value = (user[0].user_name == null) ? "" : user[0].user_name;
	form.email.value = (user[0].email == null) ? "" : user[0].email;
	form.password.value = (user[0].password == null) ? "" : user[0].password;
	form.paypal_email.value = (user[0].paypal_email == null) ? "" : user[0].paypal_email;
	form.address1.value = (user[0].address1 == null) ? "" : user[0].address1;
	form.address2.value = (user[0].address2 == null) ? "" : user[0].address2;
	form.city.value = (user[0].city == null) ? "" : user[0].city;
	form.state.value = (user[0].state == null) ? "" : user[0].state;
	form.zip.value = (user[0].zip == null) ? "" : user[0].zip;
	form.country.value = (user[0].country == null) ? "" : user[0].country;
	form.phone.value = (user[0].phone == null) ? "" : user[0].phone;
	form.fax.value = (user[0].fax == null) ? "" : user[0].fax;
	form.company.value = (user[0].company == null) ? "" : user[0].company;
	form.title.value = (user[0].title == null) ? "" : user[0].title;
	
	if(user[0].opted_out == "Y") {
		form.opted_out.checked = false;
	} else if(user[0].opted_out == "N") {
		form.opted_out.checked = true;
	}
	
	if( (user[0].user_name != null) && (user[0].user_name != "") ) {
		form.user_name.readOnly = true;
		form.user_name.style.background="#CCCCCC";
	}
	
}

function updateUser(form) {
	var opted_out;
	if(form.opted_out.checked) {
		opted_out = "N";
	} else {
		opted_out = "Y";
	}
	
	if( !validateEmailJS(form.email.value) ){
		alert(unescape(MSG_EMAIL_INVALID));
		form.email.focus();
		return false; 
	}
	
	if(form.password.value == "") {
		alert(unescape(MSG_ENTER_PASSWORD));
		form.password.focus();
		return false; 
	}
	
	if(form.password.value != form.password_repeat.value) {
		alert(unescape(MSG_PASSWORDS_MISMATCH));
		form.password.focus();
		return false; 
	}
	
	if(!validatePassword(form.password.value)) {
		alert(unescape(MSG_PASSWORD_INVALID));
		return false;
	}
	
	changeDiv('user_msg_div',MSG_UPDATING_PROFILE + '<br><img src="/dap/images/progressbar.gif">');
	var url = '/dap/admin/ajax/updateUserUserAjax.php';
	var request = new ajaxObject(url,processChangeu);
	
	request.update('userId=' + affId + '&' +
			'first_name=' + form.first_name.value + '&' +
			'last_name=' + form.last_name.value + '&' +
			'user_name=' + form.user_name.value + '&' +
			'email=' + form.email.value + '&' +
			'password=' + escape(form.password.value) + '&' +
			'paypal_email=' + form.paypal_email.value + '&' + 
			'address1=' + form.address1.value + '&' + 
			'address2=' + form.address2.value + '&' + 
			'city=' + form.city.value + '&' + 
			'state=' + form.state.value + '&' + 
			'zip=' + form.zip.value + '&' + 
			'country=' + form.country.value + '&' + 
			'phone=' + form.phone.value + '&' + 
			'fax=' + form.fax.value + '&' + 
			'company=' + form.company.value + '&' + 
			'title=' + form.title.value + '&' + 
			'opted_out=' + opted_out + '&' +
			'custom=' + form.customArray.value +
			'&whatRequest=updateUser', 'POST');
}

function doWarning(form) {
	if(form.opted_out.checked) return;
	
	//Else, it means that user is trying to optout
	var check_box = confirm(MSG_OPTOUT_WARNING);
	if (check_box==true) { 
		// Output when OK is clicked
		return; 
	} else {
		// Output when Cancel is clicked
		form.opted_out.checked = true;
		return;
	}
}

function addCustomValue(customFieldName) {
	var name = customFieldName.name;
	var value = customFieldName.value;
	var customArray = new Array();
//	alert ("customFieldName=" + name);
//	alert ("customFieldName=" + value);
	var namevalue = name + "||" + value;
	customArray = removeItems(customArray, namevalue);
	customArray.push(namevalue);
	document.UserProfileForm.customArray.value = customArray;
 //  alert ("back - customArray=" + customArray);
	
}

function removeItems(array, item) {
	var i = 0;
	while (i < array.length) {
	//	alert("array=" + array[i]);
	//	alert("item=" + item);
		if (array[i] == item) {
			array.splice(i, 1);
		} else {
			i++;
		}
	}
	return array;
}

/* ]]> */