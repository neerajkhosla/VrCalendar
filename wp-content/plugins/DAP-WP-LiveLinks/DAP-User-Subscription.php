<?php 

add_shortcode('DAPUserSubscriptions', 'dap_usersubscription');

function dap_usersubscription($atts, $content=null){ 
  extract(shortcode_atts(array(
	  'showcancel' => 'Y',
	  'showalltransactions' => 'N',
	  'productid' => 'ALL',
	  'template'  =>'template1',
	  'payment' => 'PAYPAL',		
	  'showrenewal' => 'Y',
	  'bgcolor'     =>'#f9f9f9',
	  'templatetextcolor' =>'#000',
	  'dateformat'=>'YYYY-MM-DD',
	  'cancelsuccess'=>'Subscription Cancellation Completed Successfully!',
	  'cancelfailed'=>'Sorry, could not cancel the subscription.',
	  'confirmmsg' =>'Are you sure you want to cancel subscription?',
	  'cancelimage'=>'',
	  'upgradelink' =>'',
	  'upgradetext' =>'UPGRADE',
	  'upgradetextcolor'=>'blue',
	  'canceltext' =>'CANCEL',
	  'canceltextcolor'=>'blue',
	  'cancelledtextcolor' =>'blue',
	  'notransfoundmsg'=>'Sorry, no active transactions found'
  ), $atts));
 
  $content = do_shortcode(dap_clean_shortcode_content($content));	
  
  $session = Dap_Session::getSession();
  $user = $session->getUser();
  
  if( !Dap_Session::isLoggedIn() || !isset($user) ) {
	  //logToFile("Not logged in, returning errmsgtemplate");
	  $errorHTML = mb_convert_encoding(MSG_PLS_LOGIN, "UTF-8", "auto") . " <a href=\"" . Dap_Config::get("LOGIN_URL") . "\">". mb_convert_encoding(MSG_CLICK_HERE_TO_LOGIN, "UTF-8", "auto") . "</a>";
	  return $errorHTML;
  }

  $userId = $user->getId();
  $userProducts = null;
  
  if($cancelimage == "") {
    //$cancelimage = get_option('siteurl'). "/wp-content/plugins/DAP-WP-LiveLinks/includes/images/CancelButtonUp.gif";
	if($template=='template2')
		$cancelimage ='';
  }
  //logToFile("cancelimage= ". $cancelimage);	

  if( Dap_Session::isLoggedIn() && isset($user) ) { 
	  //get userid
	  $userProducts = Dap_UsersProducts::loadProducts($user->getId());
  }
  
  $blogpath=get_option("siteurl");
  
  $fullformcss=  WP_PLUGIN_DIR . "/DAP-WP-LiveLinks/includes/cancel/".$template."/css/autocanceltemplate.css";
  $fullcustomcss =  WP_PLUGIN_DIR . "/DAP-WP-LiveLinks/includes/cancel/".$template."/css/customautocanceltemplate.css";   

  $customcss=get_option('siteurl')."/wp-content/plugins/DAP-WP-LiveLinks/includes/cancel/".$template."/css/customautocanceltemplate.css?ver=1";
  $fcss = parse_url($customcss);
  $customcss=$fcss["path"];
  //logToFile("custom css: ". $customcss);	
   
  $formcss=get_option('siteurl')."/wp-content/plugins/DAP-WP-LiveLinks/includes/cancel/".$template."/css/autocanceltemplate.css?ver=1";
  $fcss = parse_url($formcss);
  $formcss=$fcss["path"];
  //logToFile("formcss css: ". $formcss);	
  
  if(file_exists($fullcustomcss)) {
	 //logToFile("custom css: ". $customcss);	
	 $formcss=$customcss;
	 //logToFile("custom css: ". $customcss);
  }
	
  $content .= '<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
  <script>
  function cancelSubscription(formname, productId, recurringId, paymentProcessor,transNum) {
	//alert("in cancel subscription " + formname + "," + productId + "," + recurringId);
	var r=confirm("'.$confirmmsg.'");
	if (r==true)
  	{
  		
    var submiturl="/dap/dap-userSubscriptionStatus.php";
	
	var cancelsuccess = "' . $cancelsuccess . '";
	var cancelfailed = "' . $cancelfailed . '";
	//alert("here");
	//alert(cancelsuccess);
	jQuery.ajax({
	  url: submiturl,
	  type: "POST",
	  async: false,
	  data: {"productId":productId,"recurringId":recurringId,"action":"Cancel","paymentProcessor":paymentProcessor,"transNum":transNum},
	  cache: false,
	  success: function (returnval) {
		if(returnval==0) {
		 alert("Completed cancellation successfully");  
		 // alert(cancelsuccess);
		  window.location.reload();
 		}
		else if(returnval==1) {
		  alert(cancelfailed);
		}
	  }
	}); //ajax
	return;
  	}
	else{
		return false;
	
	}
  }
  </script>';
  
  $content .= '<link rel="stylesheet" type="text/css" href="' . $formcss . '" />';
  $content .= '<div id="wrapper">';
  if($bgcolor)
	  echo "<style>#autoCancellationDark{background:$bgcolor;}</style>";  
  if($upgradetextcolor)
	  echo "<style>.upgrade > a{color:$upgradetextcolor;}</style>";  

  if($canceltextcolor)
	  echo "<style>.cancel > a{color:$canceltextcolor;}</style>";  

  $content .= '<form name="formautoaction" id="formautoaction">';
  if($template =='template2') {
	$content .= '<div id="autoCancellationDark">';
	if($templatetextcolor){
	  echo "<style>#autoCancellationDark{color:$templatetextcolor;}</style>";  
	}
  }
  
  $lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
  $fullcustomheaderhtml =  $lldocroot ."/wp-content/plugins/DAP-WP-LiveLinks/includes/cancel/".$template."/customautocancelheadertemplate.html";   
  if(file_exists($fullcustomheaderhtml)) 
    $template_headerpath=$fullcustomheaderhtml;
  else
    $template_headerpath=ABSPATH."/wp-content/plugins/DAP-WP-LiveLinks/includes/cancel/".$template."/autocancelheadertemplate.html";
   
   //logToFile("DAP USER CANCELLATION : template_headerpath =" . $template_headerpath,LOG_INFO_DAP);
   
  $temp_headercontent = file_get_contents($template_headerpath);
  $current_msg=$temp_headercontent;
  $current_msg = str_replace( '[USERNAME]', $user->getFirst_name().$user->getLast_name(), $current_msg); 
  $current_msg = str_replace( '[USEREMAIL]', $user->getEmail(), $current_msg);
  $content .= $current_msg;
  if($template=='template1') {
	if($upgradelink !='') 
	  $content .= '<div class="cell-head">Upgrade</div>';
	if($showcancel != "N") 
	  $content .= '<div class="cell-head">Cancel</div>'; 
	$content .=  '</div>';
  }
  
  //logToFile("DAP USER CANCELLATION : cancel =" . $temp_headercontent,LOG_INFO_DAP);
  
  $fullcustomhtml =  $lldocroot ."/wp-content/plugins/DAP-WP-LiveLinks/includes/cancel/".$template."/customautocanceltemplate.html";   
  if(file_exists($fullcustomhtml))
	$template_path=$fullcustomhtml;
  else
	$template_path=$blogpath."/wp-content/plugins/DAP-WP-LiveLinks/includes/cancel/".$template."/autocanceltemplate.html";
  //$template_path=$blogpath."/wp-content/plugins/DAP-WP-LiveLinks/includes/".$template."/autocanceltemplate.html";
  $temp_content = file_get_contents($template_path);
//echo "<pre>";
//print_r($userProducts); exit;
  //loop over each product from the list
  foreach ($userProducts as $userProduct) {
	//logToFile("DAP USER CANCELLATION : USERPRODUCTS ENTER",LOG_INFO_DAP);
	
	if($productid != "ALL") {
		$productIdArray = explode(",",$productid);
		if( !in_array($userProduct->getProduct_id(), $productIdArray) ) {
		  continue;
	    }
	}
		  
	$product = Dap_Product::loadProduct($userProduct->getProduct_id());
	$is_recur = $product->getIs_recurring();

	$expired = false;
	$productId=$product->getId();
	//logToFile("DAP USER CANCELLATION : check if users access to product=".$productId." has expired",LOG_INFO_DAP);
		
	if($user->hasAccessTo($product->getId()) === false) {
		//logToFile("Users access to product=".$productId." expired",LOG_INFO_DAP);
		$expired = true;
	}
		
	//$product->getName()
	
	$oldDate = $userProduct->getAccess_start_date();
	//logToFile("DAP USER CANCELLATION : oldDate newStartDate=".$oldDate,LOG_INFO_DAP);
	$middle = new DateTime($oldDate);
	
	//logToFile("DAP USER CANCELLATION : middle newStartDate=".$middle,LOG_INFO_DAP);
	
	$stringFormat = "";
	if($dateformat == "MM-DD-YYYY") {
		$stringFormat = "m-d-Y";
	} else if($dateformat == "DD-MM-YYYY") {
		$stringFormat = "d-m-Y";
	}  else if($dateformat == "YYYY-MM-DD") {
		$stringFormat = "Y-m-d";
	}
	
	$newStartDate = $middle->format($stringFormat);
	logToFile("DAP USER CANCELLATION : newStartDate=".$newStartDate,LOG_INFO_DAP);
	
	$oldDate = $userProduct->getAccess_end_date();
	$middle = new DateTime($oldDate);
	//logToFile("DAP USER CANCELLATION : middle newEndDate=".$middle,LOG_INFO_DAP);
	$stringFormat = "";
	if($dateformat == "MM-DD-YYYY") {
		$stringFormat = "m-d-Y";
	} else if($dateformat == "DD-MM-YYYY") {
		$stringFormat = "d-m-Y";
	}  else if($dateformat == "YYYY-MM-DD") {
		$stringFormat = "Y-m-d";
	}
	$newEndDate = $middle->format($stringFormat);
	//logToFile("DAP USER CANCELLATION : newEndDate=".$newEndDate,LOG_INFO_DAP);
	
	$highlightCode = "";
	if($expired) {
		$highlightCode = ' class="dap_highlight_bg" ';
	}
	
	if( $expired && ($showrenewal == "Y") ) {
		//If user's access to product has expired, then show renewal HTML
		//$content .= '<div>'.$product->getRenewal_html().'</div>';
	}
		
	$emailFilter = $user->getEmail();
	$productIdFilter = $userProduct->getProduct_id();
	
	$transIdFilter = $userProduct->getTransaction_id();
	$showTran="N";
	$foundTransaction=false;
	$authnet=false;
	$paypal=false;
	$stripe=false;
	$recurring_id="";
	$stripe_customer_id="";
	
	$cancelled_subscription="";
	
	if($transIdFilter <= 0) {
		//logToFile("SKIP transNumFilter=".$transNumFilter . " less than 0, product=".$productIdFilter,LOG_INFO_DAP);
		continue;
	}
	
	$statusFilter = "";
	
	// TODO: only pick up subscription transaction
	/*logToFile("transIdFilter=".$transIdFilter,LOG_INFO_DAP);
	logToFile("transNumFilter=".$transNumFilter,LOG_INFO_DAP);
	logToFile("emailFilter=".$emailFilter,LOG_INFO_DAP);
	logToFile("productIdFilter=".$productIdFilter,LOG_INFO_DAP);
	logToFile("statusFilter=".$statusFilter,LOG_INFO_DAP);
	*/
	$TransactionsList = Dap_Transactions::loadTransactions($transNumFilter, $emailFilter, $productIdFilter, $statusFilter,"","",$transIdFilter);
	
	foreach ($TransactionsList as $transaction) {
	  $authnet=false;
	  $paypal=false;
	  $stripe=false;
	  
	  parse_str($transaction->getTrans_blob(), $list);
	 // logToFile("DAP-Auto-Cancellation: Payment processor is paypal, setting address details before list",LOG_INFO_DAP); 
	  
	  if (($list == NULL) || !isset($list))
		 //logToFile("DAP-Auto-Cancellation::LIST EMPTY"); 
		  
	  foreach ($list as $key => $value) {
		// logToFile("DAP-Auto-Cancellation::LIST DETAILS(): Key=".$key.", Value=".$value); 
	  }
	  
	  //logToFile("DAP-Auto-Cancellation::TRANSACTION TYPE  =  " . $transaction->getTrans_type()); 
	  
	  if($transaction->getTrans_type() == "subscr_signup") {
		  continue;
	  }
	  
	  if(array_key_exists('recurring_payment_id',$list)) {
		  $recurring_id = $list["recurring_payment_id"];
	  }
	  else if(array_key_exists('subscr_id',$list)) {
		  $recurring_id = $list["subscr_id"];
	  }
	  else if(array_key_exists('sub_id',$list)) {
		 if($list["sub_id"] != ":")
		  $recurring_id = $list["sub_id"];
	  } 
	  else if(array_key_exists('stripe_customer_id',$list)) {
		  $recurring_id = $list["stripe_customer_id"];
	  }
	  
	  if(array_key_exists('cancel',$list)) {
		  $cancelled_subscription = $list["cancel"];
		  //logToFile("DAP-Auto-Cancellation::check if cancelled_subscription".$cancelled_subscription); 
	  }
	  
	  //logToFile("DAP-Auto-Cancellation::recurring_id".$recurring_id); 
	  
	  //PaypalPDT
	  $payment_processor = $transaction->getPayment_processor();
	  if ($payment_processor == "MANUAL") {
		  continue;
	  }
	  if ($payment_processor == "AUTHNET") {
		  $authnet=true;
	  }
	  if (stristr($payment_processor,"PAYPAL")) {
		  $paypal=true;
	  }
	  if (stristr($payment_processor,"STRIPE")) {
		  $stripe=true;
	  }
	  
	  $transaction_id=$transaction->getTrans_num();
	  $newtrans=explode(':',$transaction_id);
	  $amount= $transaction->getPayment_value();
	  $foundTransaction=true;
	  //logToFile("DAP USER CANCELLATION : foundTransaction=".$foundTransaction,LOG_INFO_DAP);
	  break; 
	} //foreach transaction
		
	//logToFile("DAP USER CANCELLATION : found recurring_id=".$recurring_id,LOG_INFO_DAP);
		
	if($foundTransaction) { //Start Found Trans
	  $showTran="Y";
	  
	  if($showalltransactions=="N") {
		if($recurring_id != "") 
			$showTran="Y";
		else $showTran="N";
	  }
		  
	  if($showTran=="Y") { // Show Trans Yes
		logToFile("DAP USER CANCELLATION : found TRANS=".$paypal,LOG_INFO_DAP);
		$ubtn='<span1 class="upgrade">&nbsp;&nbsp;<a href="'.$upgradelink.'" target="_blank" id="[PAYPALPRODID]"' ;
		$ubtn .='>'.$upgradetext.'</a></span1>';
		if($template=='template1') {
			$ubtn='<div class="cell-data-left"><a id="upgrade" target="_blank" href="'.$upgradelink.'" id="[PAYPALPRODID]"' ;
			$ubtn .='>'.$upgradetext.'</a></div>';
		}
		if($upgradetextcolor)
			echo "<style>#upgrade{color:$upgradetextcolor;}</style>";
			
		$current_msg=$temp_content;
	  
		$current_msg = str_replace( '[ORDERPRODID]', "order_".$product->getId(), $current_msg); 
		$current_msg = str_replace( '[FORMNAME]', "document.formautoaction", $current_msg); 
		$current_msg = str_replace( '[AMOUNT]', $amount, $current_msg); 
		$current_msg = str_replace( '[ITEMNAME]', $product->getName(), $current_msg); 
		$current_msg = str_replace( '[ACCESSSTART]', $newStartDate, $current_msg); 
		$current_msg = str_replace( '[ACCESSEND]', $newEndDate, $current_msg); 
		$current_msg = str_replace( '[RECURRINGID]', $newtrans[0], $current_msg); 
		
		$today = date("Y-m-d", strtotime('today'));
			
		if($showcancel=="Y") { //Show cancel yes
		  if($cancelled_subscription) { //IF cancelled Subscription
			  $current_msg = str_replace( '[BTN_CODE]', "<div class='cell-data-left' align='justify' style='text-align:center;color:".$cancelledtextcolor."'><strong>CANCELLED</strong></div>", $current_msg);
			  $current_msg = str_replace( '[BTN_CANCEL]', "<span style='color:red;font-size: 12px;text-align:center;'>&nbsp;&nbsp;CANCELLED</span>", $current_msg);
			   
		  } // END IF cancelled Subscription
		  else { // If not cancelled subscription
			if($paypal) { //Paypal start
			  if($recurring_id != "") {
				logToFile("DAP USER CANCELLATION : paypal recurring id found=".$product->getName(),LOG_INFO_DAP);
				if($cancelimage) {
				  $pbtn='<div class="cell-data-left">';
				  $pbtn .='<input class="imageclass" type="image" src="[CANCELIMAGESRC]"  name="[PAYPALPRODID]" id="[PAYPALPRODID]" onClick="';
				  $pbtn .="return cancelSubscription(document.formautoaction,'";
				  $pbtn .="[PRODID]', '[RECURRINGID]','PAYPAL','[TRANSACTION_ID]');";
				  $pbtn .='" value="CANCEL">';
				  $pbtn .='</div>';
				}
				else {
				  $pbtn='<div class="cell-data-left">';
				  $pbtn .='<span1 class="cancel">&nbsp;&nbsp;<a href="" id="[PAYPALPRODID]" onclick="';
				  $pbtn .="return cancelSubscription(document.formautoaction,'";
				  $pbtn .="[PRODID]', '[RECURRINGID]','PAYPAL','[TRANSACTION_ID]');";
				  $pbtn .='">'.$canceltext.'</a></span1>';
				  $pbtn .='</div>';
				}
				if($cancelimage) {
				  $cbtn ='<input class="imageclass" type="image" src="[CANCELIMAGESRC]"  name="[PAYPALPRODID]" id="[PAYPALPRODID]" onClick="';
				  $cbtn .="return cancelSubscription(document.formautoaction,'";
				  $cbtn .="[PRODID]', '[RECURRINGID]','PAYPAL','[TRANSACTION_ID]');";
				  $cbtn .='" value="CANCEL">';
				}
				else {
				  $cbtn='<span1 class="cancel">&nbsp;&nbsp;<a href="" id="[PAYPALPRODID]" onclick="';
				  $cbtn .="return cancelSubscription(document.formautoaction,'";
				  $cbtn .="[PRODID]', '[RECURRINGID]','PAYPAL','[TRANSACTION_ID]');";
				  $cbtn .='">'.$canceltext.'</a></span1>';
				}
				$current_msg = str_replace( '[BTN_CODE]', $pbtn, $current_msg); 
				$current_msg = str_replace( '[BTN_CANCEL]', $cbtn, $current_msg);
					
				if($upgradelink !='')
				  $current_msg = str_replace( '[BTN_UPGRADE]', $ubtn, $current_msg);
				else
				  $current_msg = str_replace( '[BTN_UPGRADE]', "", $current_msg);
				  $current_msg = str_replace( '[PAYPALPRODID]', "paypalorder_".$product->getId(), $current_msg);
				}
				else {
				  $nocanceldiv='<div class="cell-data-left" align="justify"></div>';
				  $current_msg = str_replace( '[BTN_CODE]', $nocanceldiv, $current_msg);   
				  $current_msg = str_replace( '[BTN_CANCEL]', " ", $current_msg);
				}
			  }//paypal end
			  else if($authnet) {//Authnet start
				if ($recurring_id != "") {
					logToFile("DAP USER CANCELLATION : authnet recurring id found=".$product->getName(),LOG_INFO_DAP);
					if($cancelimage) {
					  $abtn ='<div class="cell-data-left">';
					  $abtn .='<input type="image" class="imageclass" src="[CANCELIMAGESRC]" name="[AUTHNETPRODID]" id="[AUTHNETPRODID]" onClick="';
					  $abtn .="return cancelSubscription(document.formautoaction,'";
					  $abtn .="[PRODID]', '[RECURRINGID]','AUTHNET','[TRANSACTION_ID]');";
					  $abtn .='" value="CANCEL">';
					  $abtn .='</div>';
					}
					else {
					  $abtn ='<div class="cell-data-left">';
					  $abtn .='<span1 class="cancel">&nbsp;&nbsp;<a href="" id="[PAYPALPRODID]" onclick="';
					  $abtn .="return cancelSubscription(document.formautoaction,'";
					  $abtn .="[PRODID]', '[RECURRINGID]','AUTHNET','[TRANSACTION_ID]');";
					  $abtn .='">'.$canceltext.'</a></span1>';
					  $abtn .='</div>';	
					}
					if($cancelimage) {
					  $cbtn ='<input type="image" class="imageclass" src="[CANCELIMAGESRC]"  name="[AUTHNETPRODID]" id="[AUTHNETPRODID]" onClick="';
					  $cbtn .="return cancelSubscription(document.formautoaction,'";
					  $cbtn .="[PRODID]', '[RECURRINGID]','AUTHNET','[TRANSACTION_ID]');";
					  $cbtn .='" value="CANCEL">';
					}
					else {
					  $cbtn='<span1 class="cancel">&nbsp;&nbsp;<a href="" id="[PAYPALPRODID]" onclick="';
					  $cbtn .="return cancelSubscription(document.formautoaction,'";
					  $cbtn .="[PRODID]', '[RECURRINGID]','AUTHNET','[TRANSACTION_ID]');";
					  $cbtn .='">'.$canceltext.'</a></span1>';
					}
					$ubtn='<span1 class="upgrade">&nbsp;&nbsp;<a target="_blank" href="'.$upgradelink.'" id="[AUTHNETPRODID]"' ;
					$ubtn .='>'.$upgradetext.'</a></span1>';
					if($template=='template1') {
						$ubtn='<div class="cell-data-left"><a target="_blank" href="'.$upgradelink.'" id="[AUTHNETPRODID]"' ;
						$ubtn .='>'.$upgradetext.'</a></div>';
					}
					
					$current_msg = str_replace( '[BTN_CODE]', $abtn, $current_msg); 
					$current_msg = str_replace( '[BTN_CANCEL]', $cbtn, $current_msg);
					if($upgradelink !='')
						$current_msg = str_replace( '[BTN_UPGRADE]', $ubtn, $current_msg);
					else
						$current_msg = str_replace( '[BTN_UPGRADE]', "", $current_msg);
									  
					$current_msg = str_replace( '[AUTHNETPRODID]', "authnetorder_".$product->getId(), $current_msg); 
				  }
				  else {
					$nocanceldiv='<div class="cell-data-left" align="justify"></div>';
					$current_msg = str_replace( '[BTN_CODE]', $nocanceldiv, $current_msg);
					$current_msg = str_replace( '[BTN_CANCEL]', " ", $current_msg);
					if($upgradelink !='')
						$current_msg = str_replace( '[BTN_UPGRADE]', $ubtn, $current_msg);
					else
						$current_msg = str_replace( '[BTN_UPGRADE]', "", $current_msg);
				  }
				} // Authnet end
				else if($stripe) {//Stripe start
				 // if (strtoupper($is_recur) == "Y") {
				if ( ($recurring_id != "") && (strtoupper($is_recur) == "Y") )  {
					logToFile("DAP USER CANCELLATION : stripe recurring id found=".$product->getName(),LOG_INFO_DAP);
					if($cancelimage) {
					  $abtn ='<div class="cell-data-left">';
					  $abtn .='<input type="image" class="imageclass" src="[CANCELIMAGESRC]" name="[STRIPEPRODID]" id="[STRIPEPRODID]" onClick="';
					  $abtn .="return cancelSubscription(document.formautoaction,'";
					  $abtn .="[PRODID]', '[RECURRINGID]','STRIPE','[TRANSACTION_ID]');";
					  $abtn .='" value="CANCEL">';
					  $abtn .='</div>';
					}
					else {
					  $abtn ='<div class="cell-data-left">';
					  $abtn .='<span1 class="cancel">&nbsp;&nbsp;<a href="" id="[STRIPEPRODID]" onclick="';
					  $abtn .="return cancelSubscription(document.formautoaction,'";
					  $abtn .="[PRODID]', '[RECURRINGID]','STRIPE','[TRANSACTION_ID]');";
					  $abtn .='">'.$canceltext.'</a></span1>';
					  $abtn .='</div>';	
					}
					if($cancelimage) {
					  $cbtn ='<input type="image" class="imageclass" src="[CANCELIMAGESRC]"  name="[STRIPEPRODID]" id="[STRIPEPRODID]" onClick="';
					  $cbtn .="return cancelSubscription(document.formautoaction,'";
					  $cbtn .="[PRODID]', '[RECURRINGID]','STRIPE','[TRANSACTION_ID]');";
					  $cbtn .='" value="CANCEL">';
					}
					else {
					  $cbtn='<span1 class="cancel">&nbsp;&nbsp;<a href="" id="[STRIPEPRODID]" onclick="';
					  $cbtn .="return cancelSubscription(document.formautoaction,'";
					  $cbtn .="[PRODID]', '[RECURRINGID]','STRIPE','[TRANSACTION_ID]');";
					  $cbtn .='">'.$canceltext.'</a></span1>';
					}
					$ubtn='<span1 class="upgrade">&nbsp;&nbsp;<a target="_blank" href="'.$upgradelink.'" id="[STRIPEPRODID]"' ;
					$ubtn .='>'.$upgradetext.'</a></span1>';
					if($template=='template1') {
						$ubtn='<div class="cell-data-left"><a target="_blank" href="'.$upgradelink.'" id="[STRIPEPRODID]"' ;
						$ubtn .='>'.$upgradetext.'</a></div>';
					}
					
					$current_msg = str_replace( '[BTN_CODE]', $abtn, $current_msg); 
					$current_msg = str_replace( '[BTN_CANCEL]', $cbtn, $current_msg);
					if($upgradelink !='')
						$current_msg = str_replace( '[BTN_UPGRADE]', $ubtn, $current_msg);
					else
						$current_msg = str_replace( '[BTN_UPGRADE]', "", $current_msg);
									  
					$current_msg = str_replace( '[STRIPEPRODID]', "stripeorder_".$product->getId(), $current_msg); 
				  }
				  else {
					$nocanceldiv='<div class="cell-data-left" align="justify"></div>';
					$current_msg = str_replace( '[BTN_CODE]', $nocanceldiv, $current_msg);
					$current_msg = str_replace( '[BTN_CANCEL]', " ", $current_msg);
					if($upgradelink !='')
						$current_msg = str_replace( '[BTN_UPGRADE]', $ubtn, $current_msg);
					else
						$current_msg = str_replace( '[BTN_UPGRADE]', "", $current_msg);
				  }
				} // Stripe end
				else  {  // if Infusion and others (not paypal, authnet)
				
					logToFile("DAP-User-Subscription(): dap_usersubscription:  Payment Processor = ". $payment_processor,LOG_INFO_DAP);
			
					$nocanceldiv='<div class="cell-data-left" align="justify"></div>';
					$current_msg = str_replace( '[BTN_CODE]', $nocanceldiv, $current_msg);
					$current_msg = str_replace( '[BTN_CANCEL]', "", $current_msg);  
					if($upgradelink !='')
						$current_msg = str_replace( '[BTN_UPGRADE]', $ubtn, $current_msg);
					else
						$current_msg = str_replace( '[BTN_UPGRADE]', "", $current_msg);
					
					logToFile("DAP-User-Subscription(): dap_usersubscription:  Complete Payment Processor = ". $payment_processor,LOG_INFO_DAP);
					
				}//Others end
						
			  }//If not cancelled subscription
			  
			  $current_msg = str_replace( '[CANCELIMAGESRC]', $cancelimage, $current_msg); 
			} // END showcancel=Y
			else { // Start showcancel=N
				$current_msg = str_replace( '[BTN_CODE]', "", $current_msg);
				$current_msg = str_replace( '[BTN_CANCEL]', "", $current_msg);
				
			}// End Show cancel=N
			if($upgradelink !='')
				$current_msg = str_replace( '[BTN_UPGRADE]', $ubtn, $current_msg);
			else
				$current_msg = str_replace( '[BTN_UPGRADE]', "", $current_msg);
			
			$current_msg = str_replace( '[TRANSACTION_ID]', $transaction_id, $current_msg); 
			$current_msg = str_replace( '[RECURRINGID]', $recurring_id, $current_msg); 
			$current_msg = str_replace( '[PRODID]', $product->getId(), $current_msg); 
			$current_msg = str_replace( '[PAYMENTPROCESSOR]', $payment_processor, $current_msg); 
			
			$content .= $current_msg;	
		  } //End showTran=Y
		  
		} //End found trans
		else {
		  //$current_msg = str_replace( '[BTN_CODE]', "SUBSCRIPTION ID NOT FOUND", $current_msg); 	
		}
		
  } //end foreach

  logToFile("DAP-User-Subscription(): showTran=".$showTran,LOG_INFO_DAP);
  
  $content.='</form></div>';
  if($template =='template2')
   $content .=  '</div>';
  
  if($showTran!="Y") {
	$content .= $notransfoundmsg;
	logToFile("DAP-User-Subscription(): no active transactions found",LOG_INFO_DAP);
  }
  
  return $content;
}		

?>