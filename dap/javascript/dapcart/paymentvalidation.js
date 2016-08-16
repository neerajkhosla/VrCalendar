		function onPageLoad() {
			var buttonSub = document.getElementById('btnSubmit');
			//enableButton(buttonSub, 'Submit Order');
			paymentMethod_onClick();
			var oTrChkCopyBill = document.getElementById('trChkCopyBill');
			if (null != oTrChkCopyBill) oTrChkCopyBill.style.display = '';
			
			var oACLink = document.getElementById('oACapLink');
			var oTrCCInfo = document.getElementById('trCCInfoBold');
			var oTrBankAcctInfo = document.getElementById('trBankAcctInfoBold');
			if (null != oTrCCInfo) oTrCCInfo.style.display = 'none';
			if (null != oTrBankAcctInfo) oTrBankAcctInfo.style.display = 'none';
			if(null != oACLink) oACLink.style.display = '';
			initCardType();
		
		}
		
		function validateCustomerInfo(form) {
//			alert("TANDC="+form.tandc.checked);
		
			//Validate Billing Info
			if (!validateFirstName(form, "first_name")) { return false; }
			if (!validateLastName(form, "last_name")) { return false; }
			if (!validateEmail(form, "email")) { return false; }
			
			
			return true;
		}
		
		function validateCCInfo(form) {
			if (!validateCCNum(form)) { return false; }
			
			if( (form.exp_date.value == " ") || (form.exp_date.value == "Month"))
			{ alert("Missing card expiration month"); return false; }
			
			if( (form.exp_date_year.value == " ") || (form.exp_date_year.value == "Year"))
			{ alert("Missing card expiration year"); return false; }
			//if (!validateExpDate(form)) { return false; }
			if (!validateCode(form)) { return false; }
			
			return true;
		}
		
		function validateBillingInfo(form) {
//			alert("TANDC="+form.tandc.checked);
		
			//Validate Billing Info
			
			//if (!validateBillingFirstName(form, "billing_first_name")) { return false; }
			//if (!validateBillingLastName(form, "billing_last_name")) { return false; }
			if (!validateAddress(form, "address")) { return false; }
			if (!validateCountry(form, "country")) { return false; }
			if (!validateState(form, "state")) { return false; }
			//if (!validateAddress(form, "address2")) { return false; }
			if (!validateCity(form, "city")) { return false; }
			if (!validateZip(form, "zip")) { return false; }
			if (!validateEmail(form, "email")) { return false; }
			if (!validatePhone(form, "phone")) { return false; }
			
			//Validate Shipping Info
			if (form.chkCopyBill) {
				var oChkCopyBill = form.chkCopyBill;
				if (oChkCopyBill.checked == true) {
					if (!validateFirstName(form, "ship_to_first_name")) { return false; }			
					if (!validateLastName(form, "ship_to_last_name")) { return false; }
					if (!validateAddress(form, "ship_to_address")) { return false; }
				//	if (!validateAddress(form, "ship_to_address2")) { return false; }
					if (!validateCity(form, "ship_to_city")) { return false; }
					if (!validateState(form, "ship_to_state")) { return false; }
					if (!validateZip(form, "ship_to_zip")) { return false; }
					if (!validateCountry(form, "ship_to_country")) { return false; }
				}
			}
	
	
			return true;
		}
		
		function paymentMethod_onClick() {
			var oPmCC = document.getElementById('pmCC');
			
			var oDivCC = document.getElementById('divCreditCardInformation');
			if (null != oDivCC) {
				if ( null != oPmCC ) {
					oDivCC.style.display = '';
				}
			}
		}
		
		function CopyFromBilling() {
			var oChkCopyBill = document.getElementById('chkCopyBill');
			copyField('first_name', 'ship_to_first_name');
			copyField('last_name', 'ship_to_last_name');
			copyField('address', 'ship_to_address');
			copyField('address2', 'ship_to_address2');
			copyField('city', 'ship_to_city');
			copyDropdown('state', 'ship_to_state');
			copyField('zip', 'ship_to_zip');
			copyDropdown('country', 'ship_to_country');
		}
		
		function copyField(from, to) {
			var oFroms = document.getElementsByName(from);
			var oTos = document.getElementsByName(to);
			//if (null != oFroms && 0 < oFroms.length && null != oTos && 0 < oTos.length && 'hidden' != oTos[0].type) {
			//oTos[0].value = oFroms[0].value;
			//}
			if (null != oFroms && 0 < oFroms.length && null != oTos && 'hidden' != oTos[0].type) {
				oTos[0].value = oFroms[0].value;
			}
			
		}
			
		function copyDropdown(from, to) {
			var oFroms = document.getElementsByName(from);
			var oTos = document.getElementsByName(to);
			
			var fromObj = document.getElementById(from);
			var toObj = document.getElementById(to);
			
			if (fromObj.type == "text") {
				if (toObj.type != "text") {
					//convert List to Text
					parentObj = toObj.parentNode;
					parentObj.removeChild(toObj);
					// Create the Input Field
					var inputEl = document.createElement("input");
					inputEl.setAttribute("id", to);
					inputEl.setAttribute("type", "text");
					inputEl.setAttribute("name", to);
					inputEl.setAttribute("size", 20);
					inputEl.setAttribute("value", fromObj.value);
					parentObj.appendChild(inputEl) ;
				}
				else {
					toObj.value = fromObj.value;
				}
			}
			else if (toObj.type == "text") {
				//convert text to list
				parentObj = toObj.parentNode;
				parentObj.removeChild(toObj);
				var inputSel = document.createElement("SELECT");
				inputSel.setAttribute("name",to);
				inputSel.setAttribute("id",to);
				parentObj.appendChild(inputSel) ;
				selObj = document.getElementById(to);
			
				selObj.options[0] = new Option(fromObj.options[fromObj.selectedIndex].text, fromObj.options[fromObj.selectedIndex].value);
				//		alert ("from val=" + selObj.options[0].text);
			}
			else {
				if (!(fromObj.selectedIndex)) fromObj.selectedIndex=0;
				if (!(toObj.selectedIndex)) toObj.selectedIndex=0;
				toObj.options[toObj.selectedIndex].value = fromObj.options[fromObj.selectedIndex].value; 
				toObj.options[toObj.selectedIndex].text = fromObj.options[fromObj.selectedIndex].text;
			}
		}
			
		function reset_onClick() {
			var oForm = document.getElementById('formPayment');
			oForm.reset();
			paymentMethod_onClick();
			return false;
		}
		
		function PopupLink(oLink) {
			if (null != oLink) {
				window.open(oLink.href, null, 'height=350, width=450, scrollbars=1, resizable=1');
				return false;
			}
			return true;
		}
		
		function ClearHiddenCC() {
			var oDivCC = document.getElementById('divCreditCardInformation');
			
			if (null != oDivCC) {
				var oFld;
				var list = new Array();
				if ('none' == oDivCC.style.display) {
					list = new Array('card_num', 'exp_date', 'card_code', 'auth_code');
				}
			
				for (i=0; i < list.length; i++) {
					oFld = document.getElementById(list[i]);
					if (null != oFld && 'text' == oFld.type) oFld.value = '';
				}
			}
		}
			
		function validate(form) {
//			alert("TANDC="+form.tandc.checked);
			if (!validateCCNum(form)) { return false; }
			//if (!validateExpDate(form)) { return false; }
			if (!validateCode(form)) { return false; }
						
			//Validate Billing Info
			if (!validateFirstName(form, "first_name")) { return false; }
			if (!validateLastName(form, "last_name")) { return false; }
			if (!validateAddress(form, "address")) { return false; }
			//if (!validateAddress(form, "address2")) { return false; }
			if (!validateCity(form, "city")) { return false; }
			if (!validateState(form, "state")) { return false; }
			if (!validateZip(form, "zip")) { return false; }
			if (!validateCountry(form, "country")) { return false; }
			if (!validateEmail(form, "email")) { return false; }
			if (!validatePhone(form, "phone")) { return false; }
			
			//Validate Shipping Info
			if (form.chkCopyBill) {
				var oChkCopyBill = form.chkCopyBill;
				if (oChkCopyBill.checked == true) {
					if (!validateFirstName(form, "ship_to_first_name")) { return false; }			
					if (!validateLastName(form, "ship_to_last_name")) { return false; }
					if (!validateAddress(form, "ship_to_address")) { return false; }
				//	if (!validateAddress(form, "ship_to_address2")) { return false; }
					if (!validateCity(form, "ship_to_city")) { return false; }
					if (!validateState(form, "ship_to_state")) { return false; }
					if (!validateZip(form, "ship_to_zip")) { return false; }
					if (!validateCountry(form, "ship_to_country")) { return false; }
				}
			}
	
			
			return true;
		}
		
		function validateEditCart(form) {
//			alert("TANDC="+form.tandc.checked);
			
			if( (form.card_num.value != "") && (form.card_code.value != "") && (form.exp_date_year.value != "") && (form.exp_date.value != "") && (form.exp_date_year.value != "Month") && (form.exp_date.value != "Year") ) {
			  if(form.card_num.value == "") { 
				  alert("Missing Credit Card Number"); 
				  return false;
			  }
			  
			  if(  (form.card_num.value) || (form.card_code.value) || (form.exp_date_year.value != " ") || (form.exp_date.value != " ") ) {
				  if (!validateCCNum(form)) { return false; }
			  //if (!validateExpDate(form)) { return false; }
				  if (!validateCode(form)) { return false; }
				  
				  if(form.exp_date_year.value == " ") { alert("Missing card expiration year"); return false; }
				  if(form.exp_date.value == " ") { alert("Missing card expiration month"); return false; }
			  }	
			}
			//Validate Billing Info
			if (!validateFirstName(form, "first_name")) { return false; }
			if (!validateLastName(form, "last_name")) { return false; }
			if (!validateAddress(form, "address")) { return false; }
			//if (!validateAddress(form, "address2")) { return false; }
			if (!validateCity(form, "city")) { return false; }
			if (!validateState(form, "state")) { return false; }
			if (!validateZip(form, "zip")) { return false; }
			if (!validateCountry(form, "country")) { return false; }
			if (!validateEmail(form, "email")) { return false; }
			if (!validatePhone(form, "phone")) { return false; }
			
			//Validate Shipping Info
			if (form.chkCopyBill) {
				var oChkCopyBill = form.chkCopyBill;
				if (oChkCopyBill.checked == true) {
					if (!validateFirstName(form, "ship_to_first_name")) { return false; }			
					if (!validateLastName(form, "ship_to_last_name")) { return false; }
					if (!validateAddress(form, "ship_to_address")) { return false; }
				//	if (!validateAddress(form, "ship_to_address2")) { return false; }
					if (!validateCity(form, "ship_to_city")) { return false; }
					if (!validateState(form, "ship_to_state")) { return false; }
					if (!validateZip(form, "ship_to_zip")) { return false; }
					if (!validateCountry(form, "ship_to_country")) { return false; }
				}
			}
	
			
			return true;
		}
			
		function notEmpty(elem, helperMsg){
			if(elem.value.length==0){
				alert(helperMsg);
				elem.style.background = '#D5D5FF';
				elem.focus();
				return false;
			}
			
			elem.style.background = 'White';
			return true;
		}
			
		function validateFirstName(form, field) {
			var isEmpty = notEmpty(document.getElementById(field), "Please enter your First Name");
			return isEmpty;
		}
			
		function validateLastName(form, field) {
			var isEmpty = notEmpty(document.getElementById(field), "Please enter your Last Name");
			return isEmpty;
		}
		
		function validateBillingFirstName(form, field) {
			var isEmpty = notEmpty(document.getElementById(field), "Please enter your Billing First Name");
			return isEmpty;
		}
			
		function validateBillingLastName(form, field) {
			var isEmpty = notEmpty(document.getElementById(field), "Please enter your Billing Last Name");
			return isEmpty;
		}

		function validateAddress(form, field) {
			var isEmpty = notEmpty(document.getElementById(field), "Please enter your Address");
			return isEmpty;
		}
			
		function validateCity(form, field) {
			var isEmpty = notEmpty(document.getElementById(field), "Please enter your City");
			return isEmpty;
		}
			
		function validateState(form, field) {
			if (document.getElementById(field).type != "text") {
				var isEmpty = notEmpty(document.getElementById(field), "Please enter your State/Province");
				return isEmpty;
			}
			return true;
		}
			
		function validateZip(form, field) {
			var isEmpty = notEmpty(document.getElementById(field), "Please enter your Postal Code");
			return isEmpty;
		}
			
		function validateCountry(form, field) {
			var isEmpty = notEmpty(document.getElementById(field), "Please enter your Country");
			var fld = document.getElementById(field);
			if (!isEmpty)
			{
				fld.style.background = '#D5D5FF';
			}
			else 
				fld.style.background = 'White';
				
			return isEmpty;
		}
			
		function trim(s)
		{
			return s.replace(/^\s+|\s+$/, '');
		}

		function validateEmail(form, field) {
			var error="";
			var fld = document.getElementById(field);
			var tfld = trim(fld.value);                        // value of field with whitespace trimmed off
			var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/ ;
			var illegalChars= /[\(\)\<\>\,\;\:\\\"\[\]]/ ;

			if (fld.value == "") {
				fld.style.background = '#D5D5FF';
				fld.focus();
				alert("Please enter an email address.\n");
				return false;
			} else if (!emailFilter.test(tfld)) {              //test email for illegal characters
				fld.style.background = '#D5D5FF';
				fld.focus();
				alert ("Please enter a valid email address.\n");
				return false;
			} else if (fld.value.match(illegalChars)) {
				fld.style.background = '#D5D5FF';
				fld.focus();
				alert ("The email address contains illegal characters.\n");
				return false;
			} else {
				fld.style.background = 'White';
			}
		
			return true;
		}

		function isInteger(s)
		{   var i;
			for (i = 0; i < s.length; i++)
			{   
				// Check that current character is number.
				var c = s.charAt(i);
				if (((c < "0") || (c > "9"))) return false;
			}
			// All characters are numbers.
			return true;
		}
		
		function strtrim(s)
		{   var i;
			var returnString = "";
			// Search through string's characters one by one.
			// If character is not a whitespace, append to returnString.
			for (i = 0; i < s.length; i++)
			{   
				// Check that current character isn't whitespace.
				var c = s.charAt(i);
				if (c != " ") returnString += c;
			}
			return returnString;
		}
		
		function stripCharsInBag(s, bag)
		{   var i;
			var returnString = "";
			// Search through string's characters one by one.
			// If character is not in bag, append to returnString.
			for (i = 0; i < s.length; i++)
			{   
				// Check that current character isn't whitespace.
				var c = s.charAt(i);
				if (bag.indexOf(c) == -1) returnString += c;
			}
			return returnString;
		}
		
		function checkInternationalPhone(strPhone){
			var bracket=3;
			// non-digit characters which are allowed in phone numbers
			var phoneNumberDelimiters = "()- ";
			// characters which are allowed in international phone numbers
			// (a leading + is OK)
			var validWorldPhoneChars = phoneNumberDelimiters + "+";
			// Minimum no of digits in an international phone no.
			var minDigitsInIPhoneNumber = 10;

			strPhone=strtrim(strPhone);
			if(strPhone.indexOf("+")>1) 
				return false;
			if(strPhone.indexOf("-")!=-1)
				bracket=bracket+1;
			if(strPhone.indexOf("(")!=-1 && strPhone.indexOf("(")>bracket)
				return false;
			
			var brchr=strPhone.indexOf("(");
			if(strPhone.indexOf("(")!=-1 && strPhone.charAt(brchr+2)!=")")
				return false;
			if(strPhone.indexOf("(")==-1 && strPhone.indexOf(")")!=-1)
				return false;
			
			s=stripCharsInBag(strPhone,validWorldPhoneChars);
			return (isInteger(s) && s.length >= minDigitsInIPhoneNumber);
		}

		function validatePhone(form, field) {
			var Phone = document.getElementById(field);
			
			if ((Phone == null) || (Phone.value==null)||(Phone.value=="")){
				
				return true;
			}
			if (checkInternationalPhone(Phone.value)==false){
				alert("Please Enter a Valid Phone Number");
				Phone.style.background = '#D5D5FF';
				Phone.value="";
				Phone.focus();
				return false;
			}
			return true;
		}


		// Credit Card Validation Javascript
		/*
		Basically, the alorithum takes each digit, from right to left and muliplies each second
		digit by two. If the multiple is two-digits long (i.e.: 6 * 2 = 12) the two digits of
		the multiple are then added together for a new number (1 + 2 = 3). You then add up the 
		string of numbers, both unaltered and new values and get a total sum. This sum is then
		divided by 10 and the remainder should be zero if it is a valid credit card. Hense the
		name Mod 10 or Modulus 10. */
		
		function validateCCNum(form) {  // v2.0
			ccNumb = form.card_num.value;
			var valid = "0123456789"  // Valid digits in a credit card number
			var len = ccNumb.length;  // The length of the submitted cc number
			var sCCN = ccNumb.toString();  // string of ccNumb
			sCCN=sCCN.replace(/\s/g, "")
			var iCCN = parseInt(sCCN, 10);  // integer of ccNumb
			//sCCN = sCCN.replace (/^\s+|\s+$/g,'');  // strip spaces
			var iTotal = 0;  // integer total set at zero
			var bNum = true;  // by default assume it is a number
			var bResult = true;  // by default assume it is NOT a valid cc
			var temp;  // temp variable for parsing string
			var calc;  // used for calculation of each digit
			
			form.card_num.style.background = 'White';
				
			// Determine if the ccNumb is in fact all numbers
			for (var j=0; j<len; j++) {
				temp = "" + sCCN.substring(j, j+1);
				//alert(temp);
				if(temp==" ")continue;
				if (valid.indexOf(temp) == "-1"){bNum = false;}
			}
			
			// if it is NOT a number, you can either alert to the fact, or just pass a failure
			if(!bNum){
				alert("Not a Number..."+form.card_num.value);
				bResult = false;
			}
			
			// Determine if it is the proper length 
			if((len == 0)&&(bResult)){  // nothing, field is blank AND passed above # check
				bResult = false;
			} else{  // ccNumb is a number and the proper length - let's see if it is a valid card number
				if(len >= 15){  // 15 or 16 for Amex or V/MC
					for(var i=len;i>0;i--){  // LOOP throught the digits of the card
						calc = parseInt(iCCN, 10) % 10;  // right most digit
						calc = parseInt(calc, 10);  // assure it is an integer
						iTotal += calc;  // running total of the card number as we loop - Do Nothing to first digit
						i--;  // decrement the count - move to the next digit in the card
						iCCN = iCCN / 10;                               // subtracts right most digit from ccNumb
						calc = parseInt(iCCN, 10) % 10 ;    // NEXT right most digit
						calc = calc *2;                                 // multiply the digit by two
						// Instead of some screwy method of converting 16 to a string and then parsing 1 and 6 and then adding them to make 7,
						// I use a simple switch statement to change the value of calc2 to 7 if 16 is the multiple.
						switch(calc){
							case 10: calc = 1; break;       //5*2=10 & 1+0 = 1
							case 12: calc = 3; break;       //6*2=12 & 1+2 = 3
							case 14: calc = 5; break;       //7*2=14 & 1+4 = 5
							case 16: calc = 7; break;       //8*2=16 & 1+6 = 7
							case 18: calc = 9; break;       //9*2=18 & 1+8 = 9
							default: calc = calc;           //4*2= 8 &   8 = 8  -same for all lower numbers
						}                                               
						iCCN = iCCN / 10;  // subtracts right most digit from ccNum
						iTotal += calc;  // running total of the card number as we loop
					}  // END OF LOOP
					if ((iTotal%10)==0){  // check to see if the sum Mod 10 is zero
						bResult = true;  // This IS (or could be) a valid credit card number.
					} else {
						bResult = false;  // This could NOT be a valid credit card number
					}
				}
				else {
					bResult = false;
				}
			}
			// change alert to on-page display or other indication as needed.
			
			if(!bResult){
				alert("This is NOT a valid Credit Card Number!");
				form.card_num.style.background = '#D5D5FF';
				form.card_num.focus;
			}
			
		
			return bResult; // Return the results
				
		}
			
		/*function validateExpDate (form) {	
			var temp;
			var bNum = true;  // by default assume it is a number
			var val = form.exp_date.value;
			var bResult = true;
			var valid = "0123456789"  // Valid digits
			var len = val.length;  // The length of the submitted cc number
			var iExp = parseInt(val, 10);  // integer of ccNumb
			var sExp = val.toString();  // string of ccNumb
			
			form.exp_date.style.background = 'White';
				
			if (len != 4) {
				alert ("Please use 4 digit mmyy format for exp date");
				bResult = false;
			}
			// Determine if exp date is in fact all numbers
			for (var j=0; j<len; j++) {
				temp = "" + sExp.substring(j, j+1);
				if (valid.indexOf(temp) == "-1") {
					bNum = false;
				}
			}
			// if it is NOT a number, you can either alert to the fact, or just pass a failure
			if(!bNum){
				alert("Not a Number");
				bResult = false;
			}
			
			var month = parseInt(sExp.substring(0,2), 10);
			var year = parseInt(sExp.substring(2,2), 10);
			
			
			if (month < 1 || month > 12) {
				alert ("Invalid month, should be between 01 - 12");
				bResult = false;
			}
			if (year < 0 || year > 99) {
				alert ("Invalid year, should be greater than 00");
				bResult = false;
			}
			
			if (!bResult) {
				form.exp_date.style.background = '#D5D5FF';
				form.exp_date.focus;
			}
			return bResult;
		}*/
			
		function validateCode (form) {
			var code = form.card_code.value;
			var len = code.length;
			var temp;
			var sCode = code.toString();
			var bNum = true;  // by default assume it is a number
			var bResult = true;
			var valid = "0123456789"  // Valid digits
			
			form.card_code.style.background = 'White';
			
			if (len < 3 || len > 4) {
				alert ("Please use the 3-4 digit card code");
				bResult = false;
			}
			
			// Determine if card code is in fact all numbers
			for (var j=0; j<len; j++) {
				temp = "" + sCode.substring(j, j+1);
				if (valid.indexOf(temp) == "-1") {
					bNum = false;
				}
			}
			// if it is NOT a number, you can either alert to the fact, or just pass a failure
			if(!bNum){
				alert("Not a Number");
				bResult = false;
			}
			
			if (!bResult) {
				form.card_code.style.background = '#D5D5FF';
				form.card_code.focus;
			}
			
			return bResult;
		}
			
			
		function onSubmit(form) {
			ClearHiddenCC();
			
			//disableButton(form.btnSubmit, 'Please wait...');
			
			if (validate(form) == false) {
				//	enableButton(form.btnSubmit, 'Submit Order');
				return false;
				
			}
			
			//alert("TANDC="+form.tandc.checked);
			if ((form.tandc) && (form.tandc.checked == false)) {
			//alert ("tandc=" + form.tandc_acceptance.value);
				var tandc_acceptance = form.tandc_acceptance.value;
				if (form.tandc_acceptance.value == "Y") {
					alert ("Please read and accept the terms and conditions\n");
					//enableButton(form.btnSubmit, 'Submit Order');
					form.tandctext.focus();
					return false;
				}
			}
			
			
			if (form.chkCopyBill) {
				var oChkCopyBill = form.chkCopyBill;
				if (oChkCopyBill.checked == false) {
					CopyFromBilling();
				}
			}
			
			var cardtype = document.getElementById('card_type');
			
			if (cardtype != null) {
				document.getElementById('cardtype').value = trim(document.getElementById('card_type').options[document.getElementById('card_type').selectedIndex].value);
			}
			
			if (document.getElementById('state').type == "text") {
				document.getElementById('statevar').value =  trim(document.getElementById('state').value);
				document.getElementById('statecode').value =  trim(document.getElementById('state').value);
			}
			else {
				document.getElementById('statevar').value = trim(document.getElementById('state').options[document.getElementById('state').selectedIndex].text);
				document.getElementById('statecode').value = trim(document.getElementById('state').options[document.getElementById('state').selectedIndex].value);
				
			}
			
			document.getElementById('countryvar').value = trim(document.getElementById('country').options[document.getElementById('country').selectedIndex].text);
			
			document.getElementById('countrycode').value = trim(document.getElementById('country').options[document.getElementById('country').selectedIndex].value);

			if ((document.getElementById('ship_to_state') != null) && (document.getElementById('ship_to_state').type == "text")) {
				document.getElementById('shipstatevar').value =  trim(document.getElementById('ship_to_state').value);
				document.getElementById('shipstatecode').value =  trim(document.getElementById('ship_to_state').value);
			}
			else {
				if (document.getElementById('shipstatevar') != null) {
					if (document.getElementById('ship_to_state')) {
						document.getElementById('shipstatevar').value = trim(document.getElementById('ship_to_state').options[document.getElementById('ship_to_state').selectedIndex].text);
						document.getElementById('shipstatecode').value = trim(document.getElementById('ship_to_state').options[document.getElementById('ship_to_state').selectedIndex].value);
					}
				}
			}
			
			if (document.getElementById('shipcountryvar') != null) {
				if (document.getElementById('ship_to_country')) {
					document.getElementById('shipcountryvar').value = trim(document.getElementById('ship_to_country').options[document.getElementById('ship_to_country').selectedIndex].text);
				}
				if (document.getElementById('ship_to_country')) {
					document.getElementById('shipcountrycode').value = trim(document.getElementById('ship_to_country').options[document.getElementById('ship_to_country').selectedIndex].value);
				}
			}
			
						
			form.submit();
			return true;
		}
		
		function showHideShippingDiv(form) {
			var oChkCopyBill = form.chkCopyBill;
			if (oChkCopyBill.checked == true) {
				showDiv('divShippingInformation');
			} else {
				hideDiv('divShippingInformation');
			}
		}
			
		function disableButton(buttonName, buttonValue) {
			buttonName.value = buttonValue;
			buttonName.disabled = true;
		}
			
		function enableButton(buttonName, buttonValue) {
			buttonName.value = buttonValue;
			buttonName.disabled = false;
		}
			
		function MoreLessText(elemId, bMore) {
			if (bMore) {
				document.getElementById(elemId + 'More').style.display = '';
				document.getElementById(elemId + 'Less').style.display = 'none';
			} else {
				document.getElementById(elemId + 'More').style.display = 'none';
				document.getElementById(elemId + 'Less').style.display = '';
			}
			return false;
		}
		
