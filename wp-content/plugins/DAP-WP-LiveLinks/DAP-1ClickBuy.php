<?php 

add_filter('widget_text', 'do_shortcode');
add_shortcode('DAP-1ClickBuy', 'dap_1clickbuy');


function dap_1clickbuy($atts, $content=null){ 
	extract(shortcode_atts(array(
		'productid' => '',
		'payment_gateway' => '',
		'eligibleimage' => '',
		'eligiblelinkmsg' => 'Click Here to Get Instant Access',
		'noteligibleimage' => '',
		'noteligiblelinkmsg' => 'Click Here to Get Access',
		'not1clickeligibleurl' => '',
		'confirmmsg'=>'Do you want to proceed with the purchase of this item?',
		'hasaccessto' => ''
	), $atts));
	
	$content = do_shortcode($content);
	$content = dap_clean_shortcode_content($content);	
	
	$session = Dap_Session::getSession();
	$user = $session->getUser();

	if( (!Dap_Session::isLoggedIn()) && (!isset($user)) ) {
		//logToFile("Shortcode says this is be shown only to those NOT logged in - and this person is NOT logged in, so return content and stop");
		return $content;
	}
	
	$userId = $user->getId();
	
	//logToFile("DAP-1ClickBuy: completedpurchase:".$_SESSION["completedpurchase"]);
	//logToFile("DAP-1ClickBuy: $productid eligibleimage:".$eligibleimage);
	
	if($_SESSION["completedpurchase"]==$productid) {
		$_SESSION["completedpurchase"]=0;
		//$linkstr='#" onclick="return confirm(\''.$confirmmsg.'\')"';
		$linkstr='<a href="#" onclick="return confirm(\''.$confirmmsg.'\');">'.$eligibleimage."</a>";
		$content .=  $linkstr;  
		return $content;
	}

	//Arriving here means user is logged in
	logToFile("DAP-1ClickBuy::User is valid and logged in. Userid: " . $user->getId());
	
	//	hasaccessto means either...
	
	if ( ($hasaccessto!="") && ($hasaccessto != "ANY")) { 
		//User MUST have access to the products listed
		if( !$user->hasAccessToProducts($hasaccessto) ) { 
			//true means user does NOT have access, which is not what we want
			return $content;
		}
	}
	  
//    require_once($lldocroot . "/dap/1clickbuy.php");
	
    //$linkstr='/dap/dappay.php?productid='.$productid.'&redirect='.$_SERVER["REQUEST_URI"]'" onclick="return confirm(\'Do you want to proceed with the purchase of this item?\')"';
	
	$qstr="productid=".$productid."&redirect=".$_SERVER["REQUEST_URI"];
	
	$emailFilter = $user->getEmail();
	$paymentProcessor="STRIPE";
	$statusFilter = "";
	$transNumFilter="";
	$productIdFilter="";
	
	//logToFile("dap-1clickbuy: emailFilter=".$emailFilter,LOG_INFO_DAP);
	//logToFile("dap-1clickbuy: paymentProcessorFilter=".$paymentProcessor,LOG_INFO_DAP);
	
	$TransactionsList = Dap_Transactions::loadTransactionsByProcessor($transNumFilter, $emailFilter, $productIdFilter, $statusFilter, $paymentProcessor);
		  
	$foundTransaction=false;
	$_SESSION['customerId']="";
	$emailId= $user->getEmail();
	
	//logToFile("Iterate the transactions table",LOG_INFO_DAP); 
	foreach ($TransactionsList as $transaction) {
		$foundTransaction=true;
		parse_str($transaction->getTrans_blob(), $list);
		//logToFile("dap-1clickbuy: found transaction",LOG_INFO_DAP); 
		
		if(array_key_exists('trans_id',$list)) {
			$trans_id = $list["txn_id"];
		}
		else if(array_key_exists('txn_id',$list)) {
			$trans_id = $list["txn_id"];
		}
		
		$currency=$transaction->getPayment_currency();
		$_SESSION['CURRENCYCODE']=$currency;
		$payment_processor = $transaction->getPayment_processor();
		//logToFile("dap-1clickbuy: payment_processor: ".$payment_processor);  
		
		if(stristr($payment_gateway,$payment_processor)==false) {
			$not1clickeligible="Y";
		}
		else { 
			$not1clickeligible="N";
		}
		
		if (array_key_exists('stripe_customer_id',$list)) {
			$stripe_customer_id = $list["stripe_customer_id"];
			$_SESSION['customerId']=$stripe_customer_id;
			logToFile("dap-1clickbuy: stripe_customer_id".$stripe_customer_id); 
		}
	
		//callCreateStripeSubscription($emailId,$productId,$stripe_customer_id);
		break;	
	}
	
	if($not1clickeligible=="Y") {
		if($noteligibleimage=="") {
			$linkstr="<a href='".$not1clickeligibleurl."'>".$noteligiblelinkmsg."</a>";
		}
		//$linkstr=$not1clickeligibleurl.'"';
		else {
			$linkstr="<a href='".$not1clickeligibleurl."'>".$noteligibleimage."</a>";
		}
		logToFile("dap-1clickbuy: not eligible noteligibleimage: ".$noteligibleimage);  
		logToFile("dap-1clickbuy: not eligible noteligiblelink: ".$linkstr);  
	}
	else {
		if($eligibleimage=="") {
		//$linkmsg="Click here to unlock all 12 months";
		$linkstr='<a href=\'' . "/dap/dappay.php?" . $qstr. '\' onclick="return confirm(\''.$confirmmsg.'\');">'.$eligiblelinkmsg."</a>";
		}
		else {
		$linkstr='<a href=\'' . "/dap/dappay.php?" . $qstr.'\' onclick="return confirm(\''.$confirmmsg.'\');">'.$eligibleimage."</a>";	
		}
		logToFile("dap-1clickbuy: yes eligible linkstr: ".$linkstr);  
		//$linkstr='/dap/dappay.php?'.$qstr.'" onclick="return confirm(\''.$confirmmsg.'\')"';
	}
	
    $content .=  $linkstr;  
    
	//logToFile("DAP-1ClickBuy::linkstr=" . $linkstr);
	return $content;
}




function dap_1clickbuy1($atts, $content=null){ 
	extract(shortcode_atts(array(
		'productid' => '',
		'not1clickeligibleurl' => '',
		'confirmmsg'=>'Do you want to proceed with the purchase of this item?',
		'hasaccessto' => ''
	), $atts));
	
	$content = do_shortcode($content);
	$content = dap_clean_shortcode_content($content);	
	
	
	$session = Dap_Session::getSession();
	$user = $session->getUser();

	
	if( (!Dap_Session::isLoggedIn()) && (!isset($user)) ) {
		//logToFile("Shortcode says this is be shown only to those NOT logged in - and this person is NOT logged in, so return content and stop");
		return $content;
	}
	
	$userId = $user->getId();
	
	logToFile("DAP-1ClickBuy: completedpurchase:".$_SESSION["completedpurchase"]);
	logToFile("DAP-1ClickBuy: not1clickeligibleurl:".$not1clickeligibleurl);
	
	
	if($_SESSION["completedpurchase"]==$productid) {
		$_SESSION["completedpurchase"]=0;
		$linkstr='#" onclick="return confirm(\''.$confirmmsg.'\')"';
		$content .=  $linkstr;  
		return $content;
	}

	
	//Arriving here means user is logged in
	logToFile("DAP-1ClickBuy::User is valid and logged in. Userid: " . $user->getId());
	
	//	hasaccessto means either...
	
	if ( ($hasaccessto!="") && ($hasaccessto != "ANY")) { 
		//User MUST have access to the products listed
		if( !$user->hasAccessToProducts($hasaccessto) ) { 
			//true means user does NOT have access, which is not what we want
			return $content;
		}
	}
	  
//    require_once($lldocroot . "/dap/1clickbuy.php");
	

	
    //$linkstr='/dap/dappay.php?productid='.$productid.'&redirect='.$_SERVER["REQUEST_URI"]'" onclick="return confirm(\'Do you want to proceed with the purchase of this item?\')"';
	
	$qstr="productid=".$productid."&redirect=".$_SERVER["REQUEST_URI"];
	
	
	$emailFilter = $user->getEmail();
	$paymentProcessor="STRIPE";
	$statusFilter = "";
	$transNumFilter="";
	$productIdFilter="";
	
	logToFile("dap-1clickbuy: emailFilter=".$emailFilter,LOG_INFO_DAP);
	logToFile("dap-1clickbuy: paymentProcessorFilter=".$paymentProcessor,LOG_INFO_DAP);
	
	$TransactionsList = Dap_Transactions::loadTransactionsByProcessor($transNumFilter, $emailFilter, $productIdFilter, $statusFilter, $paymentProcessor);
		  
	$foundTransaction=false;
	$_SESSION['customerId']="";
	$emailId= $user->getEmail();
	
	//logToFile("Iterate the transactions table",LOG_INFO_DAP); 
	foreach ($TransactionsList as $transaction) {
		$foundTransaction=true;
		parse_str($transaction->getTrans_blob(), $list);
		logToFile("dap-1clickbuy: found transaction",LOG_INFO_DAP); 
		
		if(array_key_exists('trans_id',$list)) {
			$trans_id = $list["txn_id"];
		}
		else if(array_key_exists('txn_id',$list)) {
			$trans_id = $list["txn_id"];
		}
		
		$currency=$transaction->getPayment_currency();
		$_SESSION['CURRENCYCODE']=$currency;
		$payment_processor = $transaction->getPayment_processor();
		logToFile("dap-1clickbuy: payment_processor: ".$payment_processor);  
		
		if ($payment_processor != "STRIPE") {
			$not1clickeligible="Y";
		}
		else { 
			$not1clickeligible="N";
		}
		
		if (array_key_exists('stripe_customer_id',$list)) {
			$stripe_customer_id = $list["stripe_customer_id"];
			$_SESSION['customerId']=$stripe_customer_id;
			logToFile("dap-1clickbuy: stripe_customer_id".$stripe_customer_id); 
		}
	
		//callCreateStripeSubscription($emailId,$productId,$stripe_customer_id);
		break;	
	}

	if($not1clickeligible=="N") {
		$linkstr='/dap/dappay.php?'.$qstr.'" onclick="return confirm(\''.$confirmmsg.'\')"';
	}
	else {
		$linkstr=$not1clickeligibleurl.'"';
	}
	
    $content .=  $linkstr;  
    
	logToFile("DAP-1ClickBuy::linkstr=" . $linkstr);
	return $content;
}


?>