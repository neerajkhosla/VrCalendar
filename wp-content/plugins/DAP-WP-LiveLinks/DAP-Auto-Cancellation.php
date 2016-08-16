<?php 

add_shortcode('DAPUserCancel', 'dap_usercancellation');

function dap_usercancellation($atts, $content=null){ 
  extract(shortcode_atts(array(
	  'showcancel' => 'Y',
	  'showalltransactions' => 'N',
	  'productid' => 'ALL',
	  'template'  =>'template1',
	  'payment' => 'PAYPAL',		
	  'showrenewal' => 'Y',
	  'dateformat'=>'YYYY-MM-DD',
	  'cancelsuccess'=>'Subscription Cancellation Completed Successfully!',
	  'cancelfailed'=>'Sorry, could not cancel the subscription.',
	  'cancelimage'=>''
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
  
  if($cancelimage == "") 
    $cancelimage = get_option('siteurl'). "/wp-content/plugins/DAP-WP-LiveLinks/includes/images/CancelButtonUp.gif";

  logToFile("cancelimage= ". $cancelimage);	


  if( Dap_Session::isLoggedIn() && isset($user) ) { 
	  //get userid
	  $userProducts = Dap_UsersProducts::loadProducts($user->getId());
  }
  
  $blogpath=get_option("siteurl");
  
  $fullformcss=  WP_PLUGIN_DIR . "/DAP-WP-LiveLinks/includes/".$template."/css/autocanceltemplate.css";
  $fullcustomcss =  WP_PLUGIN_DIR . "/DAP-WP-LiveLinks/includes/".$template."/css/customautocanceltemplate.css";   

  $customcss=get_option('siteurl')."/wp-content/plugins/DAP-WP-LiveLinks/includes/".$template."/css/customautocanceltemplate.css?ver=1";
  $fcss = parse_url($customcss);
  $customcss=$fcss["path"];
  logToFile("custom css: ". $customcss);	
   
  $formcss=get_option('siteurl')."/wp-content/plugins/DAP-WP-LiveLinks/includes/".$template."/css/autocanceltemplate.css?ver=1";
  $fcss = parse_url($formcss);
  $formcss=$fcss["path"];
  logToFile("formcss css: ". $formcss);	
  
  if(file_exists($fullcustomcss)) {
	 logToFile("custom css: ". $customcss);	
	 $formcss=$customcss;
	 logToFile("custom css: ". $customcss);
  }
	
  $content .= '<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
  <script>
  function cancelSubscription(formname, productId, recurringId, paymentProcessor,transactionId) {
	//alert("in cancel subscription " + formname + "," + productId + "," + recurringId);
    var submiturl="/dap/dap-changeSubscriptionStatus.php";
	
	var cancelsuccess = "' . $cancelsuccess . '";
	var cancelfailed = "' . $cancelfailed . '";
	//alert("here");
	//alert(cancelsuccess);
	jQuery.ajax({
	  url: submiturl,
	  type: "POST",
	  async: false,
	  data: {"productId":productId,"recurringId":recurringId,"action":"Cancel","paymentProcessor":paymentProcessor,"transactionId":transactionId},
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
  </script>';
  
  $content .= '<link rel="stylesheet" type="text/css" href="' . $formcss . '" />';
  $content .= '<div id="wrapper">';
  $content .= '<form name="formautoaction" id="formautoaction">';
  if($template =='template3')
  {
	  $content .= '<div id="autoCancellationLight">';
  }
  $template_headerpath=$blogpath."/wp-content/plugins/DAP-WP-LiveLinks/includes/cancel/".$template."/autocancelheadertemplate.html";
  
  $temp_headercontent = file_get_contents($template_headerpath);
  	$current_msg=$temp_headercontent;
			$current_msg = str_replace( '[USERNAME]', $user->getFirst_name(), $current_msg); 
			$current_msg = str_replace( '[USEREMAIL]', $user->getEmail(), $current_msg);
  $content .= $current_msg;
  if($template=='template1')
  {
  if($showcancel != "N") { 
  	$content .= '<div class="cell-header">Cancel</div>'; 
  }
  
  $content .=  '</div>';
  }
  
  $template_path=$blogpath."/wp-content/plugins/DAP-WP-LiveLinks/includes/cancel/".$template."/autocanceltemplate.html";
  $temp_content = file_get_contents($template_path);
	  
  //loop over each product from the list
  foreach ($userProducts as $userProduct) {
		logToFile("DAP USER CANCELLATION : USERPRODUCTS ENTER",LOG_INFO_DAP);
		
		if($productid != "ALL") {
			$productIdArray = explode(",",$productid);
			
			//if( $userProduct->getProduct_id() != $productid ) {
			if( !in_array($userProduct->getProduct_id(), $productIdArray) ) {
				continue;
			}
		}
		
		$product = Dap_Product::loadProduct($userProduct->getProduct_id());
		
		$expired = false;
		$productId=$product->getId();
		logToFile("DAP USER CANCELLATION : check if users access to product=".$productId." has expired",LOG_INFO_DAP);
			
		if($user->hasAccessTo($product->getId()) === false) {
			logToFile("Users access to product=".$productId." expired",LOG_INFO_DAP);
			$expired = true;
		}
		
		//$product->getName()
		
		$oldDate = $userProduct->getAccess_start_date();
		logToFile("DAP USER CANCELLATION : oldDate newStartDate=".$oldDate,LOG_INFO_DAP);
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
		logToFile("DAP USER CANCELLATION : newEndDate=".$newEndDate,LOG_INFO_DAP);
		
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
		
		if($transIdFilter <= 0) {
			logToFile("SKIP transNumFilter=".$transNumFilter . " less than 0, product=".$productIdFilter,LOG_INFO_DAP);
			continue;
		}
		
		$statusFilter = "";
		
		// TODO: only pick up subscription transaction
		logToFile("transIdFilter=".$transIdFilter,LOG_INFO_DAP);
		logToFile("transNumFilter=".$transNumFilter,LOG_INFO_DAP);
		logToFile("emailFilter=".$emailFilter,LOG_INFO_DAP);
		logToFile("productIdFilter=".$productIdFilter,LOG_INFO_DAP);
		logToFile("statusFilter=".$statusFilter,LOG_INFO_DAP);
		
		$TransactionsList = Dap_Transactions::loadTransactions($transNumFilter, $emailFilter, $productIdFilter, $statusFilter,"","",$transIdFilter);
		
		$foundTransaction=false;
		$authnet=false;
		$paypal=false;
		$recurring_id ="";
		$cancelled_subscription="";
		foreach ($TransactionsList as $transaction) {
		  parse_str($transaction->getTrans_blob(), $list);
		 // logToFile("DAP-Auto-Cancellation: Payment processor is paypal, setting address details before list",LOG_INFO_DAP); 
		  
		  if (($list == NULL) || !isset($list))
			 logToFile("DAP-Auto-Cancellation::LIST EMPTY"); 
			  
		  foreach ($list as $key => $value) {
			// logToFile("DAP-Auto-Cancellation::LIST DETAILS(): Key=".$key.", Value=".$value); 
		  }
		  
		  logToFile("DAP-Auto-Cancellation::TRANSACTION TYPE  =  " . $transaction->getTrans_type()); 
		  
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
			  $recurring_id = $list["sub_id"];
		  }
		  
		  if(array_key_exists('cancel',$list)) {
			  $cancelled_subscription = $list["cancel"];
			  logToFile("DAP-Auto-Cancellation::check if cancelled_subscription".$cancelled_subscription); 
		  }
		  
		  logToFile("DAP-Auto-Cancellation::recurring_id".$recurring_id); 
		  
		  $payment_processor = $transaction->getPayment_processor();
		  if ($payment_processor == "AUTHNET") {
			  $authnet=true;
		  }
		  if (strstr($payment_processor,"PAYPAL")) {
			  $paypal=true;
		  }
		  
		  $transaction_id=$transaction->getTrans_num();
		  $amount= $transaction->getPayment_value();
		  $foundTransaction=true;
		  logToFile("DAP USER CANCELLATION : foundTransaction=".$foundTransaction,LOG_INFO_DAP);
		  break; 
		} //foreach transaction
		
		logToFile("DAP USER CANCELLATION : found recurring_id=".$recurring_id,LOG_INFO_DAP);
		
		if($foundTransaction) {
		  $showTran="Y";
		  
		  if($showalltransactions=="N") {
			if($recurring_id != "") 
				$showTran="Y";
			else $showTran="N";
		  }
		  
		  if($showTran=="Y") {
			  
			logToFile("DAP USER CANCELLATION : found TRANS=".$paypal,LOG_INFO_DAP);
			
			$current_msg=$temp_content;
		  
			$current_msg = str_replace( '[ORDERPRODID]', "order_".$product->getId(), $current_msg); 
			$current_msg = str_replace( '[FORMNAME]', "document.formautoaction", $current_msg); 
			$current_msg = str_replace( '[AMOUNT]', $amount, $current_msg); 
			$current_msg = str_replace( '[ITEMNAME]', $product->getName(), $current_msg); 
			$current_msg = str_replace( '[ACCESSSTART]', $newStartDate, $current_msg); 
			$current_msg = str_replace( '[ACCESSEND]', $newEndDate, $current_msg); 
			$current_msg = str_replace( '[RECURRINGID]', $recurring_id, $current_msg); 
			
			$today = date("Y-m-d", strtotime('today'));
			if($expired)
			{
				//echo "<style>.cell-left-end{background:rgb(255,223,176);} </style>";
			} 
			
			if($showcancel=="Y") {
			  if($cancelled_subscription) {
				  $current_msg = str_replace( '[BTN_CODE]', "<div class='cell-left' align='justify'><strong> <p style='color:red'>CANCELLED</p></strong></div>", $current_msg);
				  $current_msg = str_replace( '[BTN_CANCEL]', "CANCELLED", $current_msg);
				   
			  }
			  else {
				if($paypal) {
				  if($recurring_id != "") {
					logToFile("DAP USER CANCELLATION : paypal recurring id found=".$product->getName(),LOG_INFO_DAP);
					$pbtn='<div class="cell-left">';
					$pbtn .='<input type="image" src="[CANCELIMAGESRC]"  name="[PAYPALPRODID]" id="[PAYPALPRODID]" onClick="';
					$pbtn .="cancelSubscription(document.formautoaction,'";
					$pbtn .="[PRODID]', '[RECURRINGID]','PAYPAL','[TRANSACTION_ID]');";
					$pbtn .='" value="CANCEL">';
					$pbtn .='</div>';
					$cbtn='(<a href="" id="[PAYPALPRODID]" onclick="';
					$cbtn .="cancelSubscription(document.formautoaction,'";
					$cbtn .="[PRODID]', '[RECURRINGID]','PAYPAL','[TRANSACTION_ID]');";
					$cbtn .='">Cancel</a>)';
					$ubtn='(<a href="" id="[PAYPALPRODID]" onclick="';
					$ubtn .="UpgradeSubscription(document.formautoaction,'";
					$ubtn .="[PRODID]', '[RECURRINGID]',$productfrom,$productto,'PAYPAL','[TRANSACTION_ID]');";
					$ubtn .='">Upgrade</a>)';
					$current_msg = str_replace( '[BTN_CODE]', $pbtn, $current_msg); 
					$current_msg = str_replace( '[BTN_CANCEL]', $cbtn, $current_msg);
					$current_msg = str_replace( '[BTN_UPGRADE]', $ubtn, $current_msg);
					$current_msg = str_replace( '[PAYPALPRODID]', "paypalorder_".$product->getId(), $current_msg);
				  }
				  else {
					$nocanceldiv='<div class="cell-left" align="justify"></div>';
					$current_msg = str_replace( '[BTN_CODE]', $nocanceldiv, $current_msg);   
				  }
				}
				else if($authnet) {
				  if ($recurring_id != "") {
					  logToFile("DAP USER CANCELLATION : authnet recurring id found=".$product->getName(),LOG_INFO_DAP);
					  $abtn ='<div class="cell-left">';
					  $abtn .='<input type="image" src="[CANCELIMAGESRC]" name="[AUTHNETPRODID]" id="[AUTHNETPRODID]" onClick="';
					  $abtn .="cancelSubscription(document.formautoaction,'";
					  $abtn .="[PRODID]', '[RECURRINGID]','AUTHNET','[TRANSACTION_ID]');";
					  $abtn .='" value="CANCEL">';
					  $abtn .='</div>';
					  $cbtn='(<a href="" id="[PAYPALPRODID]" onclick="';
					$cbtn .="cancelSubscription(document.formautoaction,'";
					$cbtn .="[PRODID]', '[RECURRINGID]','PAYPAL','[TRANSACTION_ID]');";
					$cbtn .='">Cancel</a>)';
					$ubtn='(<a href="" id="[PAYPALPRODID]" onclick="';
					$ubtn .="UpgradeSubscription(document.formautoaction,'";
					$ubtn .="[PRODID]', '[RECURRINGID]',$productfrom,$productto,'PAYPAL','[TRANSACTION_ID]');";
					$ubtn .='">Upgrade</a>)';
					$current_msg = str_replace( '[BTN_CODE]', $pbtn, $current_msg); 
					$current_msg = str_replace( '[BTN_CANCEL]', $cbtn, $current_msg);
					$current_msg = str_replace( '[BTN_UPGRADE]', $ubtn, $current_msg);
					  
					  $current_msg = str_replace( '[AUTHNETPRODID]', "authnetorder_".$product->getId(), $current_msg); 
					  
				  }
				  else {
					$nocanceldiv='<div class="cell-left" align="justify"></div>';
					$current_msg = str_replace( '[BTN_CODE]', $nocanceldiv, $current_msg);   
				  }
				}
				else  {
					$nocanceldiv='<div class="cell-left" align="justify"></div>';
					$current_msg = str_replace( '[BTN_CODE]', $nocanceldiv, $current_msg);   
				  }
				
			  }
			  
			  $current_msg = str_replace( '[CANCELIMAGESRC]', $cancelimage, $current_msg); 
			} //showcancel=Y
			else { //showcancel=N
				$current_msg = str_replace( '[BTN_CODE]', "", $current_msg);  
			}
			
			$current_msg = str_replace( '[TRANSACTION_ID]', $transaction_id, $current_msg); 
			$current_msg = str_replace( '[RECURRINGID]', $recurring_id, $current_msg); 
			$current_msg = str_replace( '[PRODID]', $product->getId(), $current_msg); 
			$current_msg = str_replace( '[PAYMENTPROCESSOR]', $payment_processor, $current_msg); 
			
			$content .= $current_msg;	
		  } //showTran=Y
		  
		} //found trans
		else {
		  //$current_msg = str_replace( '[BTN_CODE]', "SUBSCRIPTION ID NOT FOUND", $current_msg); 	
		}
		
	} //end foreach

 
  $content.='</form></div>';
  if($template =='template3')
  {
  $content .=  '</div>';
  }
  
  return $content;
}		

?>