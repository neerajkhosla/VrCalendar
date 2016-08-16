<?php 

  $lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
  if(file_exists($lldocroot . "/dap/dap-config.php")) include_once ($lldocroot . "/dap/dap-config.php");

  $session = Dap_Session::getSession();
  $user = $session->getUser();
  
  if( !Dap_Session::isLoggedIn() || !isset($user) ) {
	  //logToFile("Not logged in, returning errmsgtemplate");
	  $errorHTML = mb_convert_encoding(MSG_PLS_LOGIN, "UTF-8", "auto") . " <a href=\"" . Dap_Config::get("LOGIN_URL") . "\">". mb_convert_encoding(MSG_CLICK_HERE_TO_LOGIN, "UTF-8", "auto") . "</a>";
	  return $errorHTML;
  }

  if( Dap_Session::isLoggedIn() ) { 
	//get userid
	$session = Dap_Session::getSession();
	$user = $session->getUser();
	$user = Dap_User::loadUserById($user->getId()); //reload User object
	if(isset($user)) {
	  $sessionemail=$user->getEmail();
	  $sessionfirstname=$user->getFirst_name();
	  $sessionlastname=$user->getLast_name();
	  $sessionaddress1=$user->getAddress1();
	  $sessioncity=$user->getCity();
	  $sessionzip=$user->getZip();
	  $sessionstate=$user->getState();
	  $sessioncountry=$user->getCountry();
	  $sessioncompany=$user->getCompany();
	  $sessionphone=$user->getPhone();

	}
  }
  
  $siteurlhttps=SITE_URL_DAP;
  $siteurlhttps = str_replace ( "http:", "https:", $siteurlhttps );
  
  if ($_SERVER["HTTPS"] == "on") {
   $siteurlhttps = str_replace ( "http:", "https:", $siteurlhttps );
   logToFile("buynow.php:HTTPS is on");
  }

  $stripePublishableKey=Dap_Config::get('STRIPE_PUBLISH_KEY');


  if ( (isset($_REQUEST["blogpath"])) && ( $_REQUEST["blogpath"] != "" ))
	$blogpath=$_REQUEST["blogpath"];

  if($blogpath == "")
	  $blogpath=$_SESSION["blogpath"];
  
  if ( (isset($_REQUEST["wpfoldername"])) && ( $_REQUEST["wpfoldername"] != "" ))
	$wpfoldername=$_REQUEST["wpfoldername"];

  if($wpfoldername == "")
	  $wpfoldername=$_SESSION["wpfoldername"];
  
  $_SESSION["wpfoldername"]="";
  unset($_SESSION["wpfoldername"]);

  if ( (isset($_REQUEST["template"])) && ( $_REQUEST["template"] != "" ))
	$template=$_REQUEST["template"];

  if($template == "") {
	  if ($_SESSION["template"] != "") {
		$template=$_SESSION["template"];
		$_SESSION['template']="";
	  }
	  else {
		  $template='template1';
		  $_SESSION['template']=$template;
	  }
  }
  
  $fullcustomhtml =  $blogpath ."/wp-content/plugins/dapshoppingcart/includes/templates/updatecart/".$template."/customupdateCCProfile.html";   
  if(file_exists($fullcustomhtml)) {
	   $updatecarttemplate=$fullcustomhtml;
  }
  else {
	  $updatecarttemplate =  $blogpath ."/wp-content/plugins/dapshoppingcart/includes/templates/updatecart/".$template."/updateCCProfile.html";   
  }
  
  //$checkouttemplate=$lldocroot ."/dap/inc/template/checkout.html";
  $tempcontent = file_get_contents($updatecarttemplate);
  
  if (isset($_REQUEST['payment_gateway'])) {
	  logToFile("checkout.php: set payment gateway=".$_REQUEST['payment_gateway'],LOG_DEBUG_DAP);	
	  $_SESSION['payment_gateway'] = $_REQUEST['payment_gateway'];
	  $payment_gateway = $_SESSION['payment_gateway'];
  }
  
  $lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
  
  $fullformcss = $blogpath . "/wp-content/plugins/dapshoppingcart/includes/templates/updatecart/".$template."/css/updateCCProfile.css";
  $fullcustomcss =  $blogpath . "/wp-content/plugins/dapshoppingcart/includes/templates/updatecart/".$template."/css/customupdateCCProfile.css";

  $customcss=$wpfoldername."/wp-content/plugins/dapshoppingcart/includes/templates/updatecart/" . $template . "/css/customupdateCCProfile.css";
  
  logToFile("updateUserCCProfile.php: custom css: ". $customcss);	
  
  $formcss=$wpfoldername."/wp-content/plugins/dapshoppingcart/includes/templates/updatecart/" . $template . "/css/updateCCProfile.css";
  logToFile("updateUserCCProfile.php: formcss css: ". $formcss);	
  
  if(file_exists($fullcustomcss)) {
 	 //logToFile("buynow.php: custom css: ". $customcss);	
  	 $formcss=$customcss;
 	 //logToFile("buynow.php: custom css: ". $customcss);
  }

  if (isset($_REQUEST['err_text'])) {	
	  //logToFile("EDITCART.php: session=" . $_SESSION['err_text'],LOG_DEBUG_DAP);
	  //logToFile("EDITCART.php: request=" . $_REQUEST['err_text'],LOG_DEBUG_DAP);
	  $errorvisibility="block";
	  //if ($_SESSION['err_text'])
		  //$errortext="ERROR: " . $_SESSION['err_text']; 
	  //else 
	  $errortext="ERROR: " . $_REQUEST['err_text']; 
	  $current_msg = '<div id="errortext" style="display:block">' . $errortext . '</div>';
  
	  unset($_SESSION['err_text']);
	  $_SESSION['err_text']=NULL;
	  $_SESSION['err_text']="";
  }
  else if (isset($_REQUEST['msg'])) {
	  if( strstr($_REQUEST['msg'], "div") != 0)
		  $current_msg =  $_REQUEST['msg'];
	  else
		  $current_msg = '<div id="errortext" style="display:block">' . $_REQUEST['msg'] . '</div>';			
  }
  else 
	  $errorvisibility="none";
  
?>
  
<link rel='stylesheet' type='text/css' href='<?php echo $formcss; ?>'>
<script type='text/javascript' language='javascript' src='<?php echo $siteurlhttps; ?>/dap/javascript/dapcart/countries.js'></script>
<script type='text/javascript' language='javascript' src='<?php echo $siteurlhttps; ?>/dap/javascript/common.js'></script>

<script>
	jQuery(document).ready(function(){
	  // binds form submission and fields to the validation engine
	  jQuery("#formUpdateCCProfile").validationEngine();
	  
	});

	function checkHELLO(field, rules, i, options){
		if (field.val() != "HELLO") {
			// this allows to use i18 for the error msgs
			return options.allrules.validate2fields.alertText;
		}
	}
</script>

<?php
	$button="<input type='button' value='Please wait' id='please'>";
    $current_msg .='<script type="text/javascript" src="https://js.stripe.com/v1/"></script>
	<script>
	var stripePublishableKey = "' . $stripePublishableKey . '"; 
	Stripe.setPublishableKey(stripePublishableKey);
	function askvalidation()
	{
	  //alert("here");
	  var form = document.formUpdateCCProfile;
	  var valid = validateEditCart(document.formUpdateCCProfile);
	  if(valid == false) {
		  //alert("Sorry, form validation failed. Please check the messages in red");
		  return false;
	  }
	  var confirmmessage="'.$updateconfirmmsg.'";
	  var r=confirm(confirmmessage);
	  if (r==true) {
		var payment_gateway = "'.$payment_gateway .'";
		var month = document.getElementById("exp_date");
		var monthval = month.options[month.selectedIndex].text;
		var year = document.getElementById("exp_date_year");
		var yearval = year.options[year.selectedIndex].text;
		var first_name=document.getElementById("first_name").value;
		var last_name=document.getElementById("last_name").value;
		var address=document.getElementById("address").value;
		var country=document.getElementById("country").value;
		var state=document.getElementById("state").value;
		var city=document.getElementById("city").value;
		var zip=document.getElementById("zip").value;
		if((payment_gateway)  && (payment_gateway == "stripe")) {
		  Stripe.createToken({
		  number: form.card_num.value,
		  cvc: form.card_code.value,
		  exp_month: monthval,
		  exp_year: yearval,
		  country: country,
		  address_state: state,
		  address_city: city,
		  address_zip: zip,
		  address_line1: address,
		  name: first_name + " " + last_name
		  }, stripeUpdateResponseHandler);
		  return false; 
		}
		document.getElementById("btnSubmit").style.display="none";
		document.getElementById("please").style.display="block";
		//alert("submit");
		document.getElementById("formUpdateCCProfile").submit();
		return true;
	  }
	  else
		  return false;
	}
	
	function stripeUpdateResponseHandler(status, response) {
		if (response.error) {
		// re-enable the submit button
			document.getElementById("btnSubmit").style.display = "initial";
			document.getElementById("please").style.display = "none";
			  //setErrortext
			var submiturl="/dap/setCartError.php";
			jQuery.ajax({
			url: submiturl,
			type: "POST",
			async: false,
			cache: false,
			data: {"err_text":escape(response.error.message)},
			success: function (returnval) {
				window.location.reload();
			}
			}); //ajax
		} else {
			  var form$ = jQuery("#formUpdateCCProfile");
			  // token contains id, last4, and card type
			  var token = response["id"];
			  // insert the token into the form so it gets submitted to the server
			  document.getElementById("stripeToken").value=token;
			  document.getElementById("btnSubmit").style.display = "none";
			  document.getElementById("please").style.display = "initial";
			  // and submit
			  form$.get(0).submit();
		}
	}
	</script>';
	?>
	
	<?php

	$current_msg.=$tempcontent;
	
	if ($_SESSION['paymentObj'] != null) { $fname=$_SESSION['paymentObj']->getFirst_name(); } else if ($sessionfirstname != "") {		
		$fname=$sessionfirstname;
	}
	$firstname ='<input type="text" id="first_name" name="first_name" maxLength="50" size="20" class="validate[required] text-input" value="'.$fname.'" />';   
	if ($_SESSION['paymentObj'] != null) {$lname= $_SESSION['paymentObj']->getLast_name();}  else if ($sessionlastname != "") {		
		$lname=$sessionlastname;
	}
	$lastname .='<input type="text" id="last_name" name="last_name" maxLength="50" size="20" class="validate[required] text-input" value="'.$lname.'" />';
	if ($_SESSION['paymentObj'] != null){$add1=$_SESSION['paymentObj']->getAddress1(); } else if ($sessionaddress1 != "") {		
		$add1=$sessionaddress1;
	}
	$address ='<input type="text" id="address" name="address" maxLength="100" size="20" class="validate[required] text-input" value="'.$add1.'" />';
	if ($_SESSION['paymentObj'] != null) {$add2=$_SESSION['paymentObj']->getAddress2(); }else if ($sessionaddress2 != "") {		
		$add2=$sessionaddress2;
	}
	$address2 ='<input type="text" id="address2" name="address2" maxLength="100" size="20" value="'.$add2.'"';
	if ($show_address2 == "Y") {
		$current_msg = str_replace( '%%ADDRESS2VISIBILITY%%', 'block', $current_msg);
	}
	else{
		$current_msg = str_replace( '%%ADDRESS2VISIBILITY%%', 'none', $current_msg);	
	}
	$address2 .=' />';
	if ($_SESSION['paymentObj'] != null) {$bcity=$_SESSION['paymentObj']->getCity(); }else if ($sessioncity != "") 	{		
		$bcity=$sessioncity;
	}
	$city .='<input type="text" id="city" name="city" maxLength="100" size="20" class="validate[required] text-input" value="'.$bcity.'"';
	$city .=' />';
	if ($_SESSION['paymentObj'] != null) {$bzip= $_SESSION['paymentObj']->getZip();} else if ($sessionzip != "") {		
		$bzip=$sessionzip;
	}
	$zip .='<input type="text" id="zip" name="zip" maxLength="10" size="20" class="validate[required] text-input" value="'.$bzip.'"';
	$zip .='" />';
	if ($_SESSION['paymentObj'] != null) {$gcountry= $_SESSION['paymentObj']->getCountry();} else if ($sessioncountry != "") {		
		$gcountry=$sessioncountry;
	}
	if ($_SESSION['paymentObj'] != null) {$gstate= $_SESSION['paymentObj']->getState();} else if ($sessionstate != "") {		
		$gstate=$sessionstate;
	}
	
	$country ='<select onchange="print_state(';
	$country .="'state',this.selectedIndex,'".$gstate."');";
	$country .='" id="country" name ="country" class="validate[required]"></select>';
	$state='<select name ="state" id ="state" class="validate[required]"><option value="">Select state</option></select>';
	$state .='<script language="javascript">print_country("country","'.$gcountry.'","'.$gstate.'");</script>';

    if ($_SESSION['paymentObj'] != null) {
		$bemail= $_SESSION['paymentObj']->getEmail(); 
	}
	else if ($sessionemail != "") {		
		$bemail= $sessionemail;
	}
	
	$billingemail ='<input type="hidden" id="email" name="email" value="'.$bemail.'" />';
	$billingemail .='<label for="email">' . $bemail . '</label>'; 
	
	if ($_SESSION['paymentObj'] != null) {$bphone= $_SESSION['paymentObj']->getPhone();} else if ($sessionphone != "") {		
		$bphone=$sessionphone;
	}
	if ($_SESSION['paymentObj'] != null) { 
		$bphone=$_SESSION['paymentObj']->getPhone();
	}
	else{$bphone="";}
	$phone .='<input type="text" id="phone" name="phone" maxLength="25" size="20" class="validate[custom[phone]] text-input" value="'.$bphone;
	$phone .='" />';
	
	$cardnum="";
	$exp_date="";
	if ($_SESSION['paymentObj'] != null) 
		$cardnum=$_SESSION['paymentObj']->getCard_num();
	if ($_SESSION['paymentObj'] != null) 
		$exp_date=$_SESSION['paymentObj']->getExp_date();
	
	$fmonth = substr($exp_date, 0, 2);
    $fyear = substr($exp_date, 2, 4);
	//logToFile("EDITCART.php: fmonth=" . $fmonth,LOG_DEBUG_DAP);
	//logToFile("EDITCART.php: fyear=" . $fyear,LOG_DEBUG_DAP);
//	$fmonth=$expirationdate[0];
	//$fyear=$expirationdate[1];
	
	$cardnumber='<input type="text" id="card_num" name="card_num" maxlength="16" size="18" class="validate[condRequired[exp_date,exp_date_year,card_code],creditCard] text-input" value="'.$cardnum.'"';
	$cardnumber .=' />';
	$expirationdate='<select name="exp_date" id="exp_date" class="validate[condRequired[card_num,exp_date_year,card_code]]">';
	$expirationdate .='<option value=" ">Month</option>';
	for($month=1; $month <= 12; ++$month):
        if($month < 10)
        {
        	$month = "0".$month;
        }
		if($fmonth==$month) 
        	$expirationdate .='<option selected value="'.$fmonth.'">'.$month.'</option>';
        else 
        	$expirationdate .='<option value="'.$month.'">'.$month.'</option>';
    endfor; 
	
	$expirationdate .='</select>';
	$expirationdate .='<select name="exp_date_year" id="exp_date_year" class="validate[condRequired[card_num,exp_date,card_code]]">';
	$expirationdate .='<option value="">Year</option>';
	$year = date("Y") ;
	$yearvalue = date("y") ;
	
	//logToFile("EDITCART.php: fyear=" . $fyear,LOG_DEBUG_DAP);
		logToFile("EDITCART.php: year=" . $year,LOG_DEBUG_DAP);
	//logToFile("EDITCART.php: yearvalue=" . $yearvalue,LOG_DEBUG_DAP);
	
    for ($i = 0; $i <= 60; ++$i) {
	  if($fyear==$year) {
		  $expirationdate .="<option selected value='$fyear'>$year</option>"; ++$year;++$yearvalue;	}
	  else {
		  $expirationdate .="<option value='$year'>$year</option>"; ++$year;++$yearvalue; }
	}
	
	$expirationdate .='</select>';
	$paymentcardcode="";
	if ($_SESSION['paymentObj'] != null) 
		$paymentcardcode=$_SESSION['paymentObj']->getCard_code();
	
	$cardcode='<input type="text" id="card_code" name="card_code" maxLength="4" class="validate[condRequired[card_num,exp_date_year,exp_date],custom[onlyNumberSp]] text-input" text-input" size="4" value="'.$paymentcardcode.'"';
	$cardcode .=' />';
	if($_SESSION["submitimage"]!='') {
	//echo "here";
	  $actionbutton ='<input type="image" name="btnSubmit" id="btnSubmit" style="margin:2px!important; box-shadow:0 0 0 transparent !important;height:51px!important;width:105px!important;	border-radius:0;	border:0 none !important;" src="'.$submitimage.'" >';
	  $actionbutton .="<input type='button' value='Please wait' id='please' style='display:none;'>";
	}
	else 
		$actionbutton='<input type="submit" name="btnSubmit" id="btnSubmit" value="Edit" onclick="return askvalidation()">';
	
	$hiddenfields ='<input type="hidden" name="payment_gateway" value="'.$payment_gateway.'">';
	$hiddenfields .='<input type="hidden" name="successmsg" value="'.$successmsg.'">';
	$hiddenfields .='  <input type="hidden" id="cardtype" name="cardtype" value="">';
    $hiddenfields .='  <input type="hidden" id="statevar" name="statevar" value="">';
    $hiddenfields .='  <input type="hidden" id="statecode" name="statecode" value="">';
    $hiddenfields .='  <input type="hidden" id="countryvar" name="countryvar" value="">';
    $hiddenfields .='  <input type="hidden" id="countrycode" name="countrycode" value="">';
	$hiddenfields .='  <input type="hidden" id="editcarturl" name="editcarturl" value="'.$_SERVER['REQUEST_URI'].'">';
	
	$current_msg = str_replace( '%%FIRSTNAME%%', $firstname, $current_msg);
	$current_msg = str_replace( '%%LASTNAME%%', $lastname, $current_msg);
	$current_msg = str_replace( '%%ADDRESS%%', $address, $current_msg);
	$current_msg = str_replace( '%%ADDRESS2%%', $address2, $current_msg);
	$current_msg = str_replace( '%%CITY%%', $city, $current_msg);
	$current_msg = str_replace( '%%STATE%%', $state, $current_msg);
	$current_msg = str_replace( '%%ZIPCODE%%', $zip, $current_msg);
	$current_msg = str_replace( '%%COUNTRY%%', $country, $current_msg);
	$current_msg = str_replace( '%%EMAIL%%', $billingemail, $current_msg);
	$current_msg = str_replace( '%%COMPANY%%', $company, $current_msg);
	$current_msg = str_replace( '%%PHONE%%', $phone, $current_msg);
	$current_msg = str_replace( '%%FAX%%', $fax, $current_msg);
	$current_msg = str_replace( '%%CARDTYPE%%', '', $current_msg);
	$current_msg = str_replace( '%%CARDNUM%%', $cardnumber, $current_msg);
	$current_msg = str_replace( '%%EXPIRATIONDATE%%', $expirationdate, $current_msg);
	$current_msg = str_replace( '%%CARDCODE%%', $cardcode, $current_msg);
	$current_msg = str_replace( '%%ACTIONBUTTON%%', $actionbutton, $current_msg);
	$current_msg = str_replace( '%%ERRORVISIBILITY%%', $errorvisibility, $current_msg);
	$current_msg = str_replace( '%%HIDDENFIELDS%%', $hiddenfields, $current_msg);
	
	$content .= $current_msg;
	echo $content;

?>