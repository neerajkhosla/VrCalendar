<?php 

add_shortcode('DAPUpgradeButton', 'dap_upgradebutton');

function dap_upgradebutton($atts, $content=null){ 
  extract(shortcode_atts(array(
	  'upgradefrom' => '',
	  'paymentgateway' => ''
  ), $atts));
 
  $content = do_shortcode(dap_clean_shortcode_content($content));	
  
  $session = Dap_Session::getSession();
  $user = $session->getUser();
  
  if( !Dap_Session::isLoggedIn() || !isset($user) ) {
	  //logToFile("Not logged in, returning errmsgtemplate");
	  $errorHTML = mb_convert_encoding(MSG_PLS_LOGIN, "UTF-8", "auto") . " <a href=\"" . Dap_Config::get("LOGIN_URL") . "\">". mb_convert_encoding(MSG_CLICK_HERE_TO_LOGIN, "UTF-8", "auto") . "</a>";
	  return $errorHTML;
  }
  
  global $wpdb, $post, $table_prefix;
  if ($post->ID) {
	$current_postid=$post->ID;
	$post_id = get_post($current_postid); 
	$permalink =  get_permalink( $post_id ); 
	//logToFile("_dapsocialcredits.php: dapsocialcredits_shortcode post permalink=".$permalink);
  }

  if( $upgradefrom == "") {
	  //logToFile("Not logged in, returning errmsgtemplate");
	  $errorHTML = mb_convert_encoding("Missing Upgrade From Product", "UTF-8", "auto") . " <a href=\"" . $permalink . "\">". mb_convert_encoding("Please regenerate the upgrade button code. Missing upgradeFrom product in button", "UTF-8", "auto") . "</a>";
	  return $errorHTML;
  }

  if( $paymentgateway == "") {
	  //logToFile("Not logged in, returning errmsgtemplate");
	  $errorHTML = mb_convert_encoding("Missing payment gateway", "UTF-8", "auto") . " <a href=\"" . $permalink . "\">". mb_convert_encoding("Please regenerate the upgrade button code. Missing payment gateway name in button", "UTF-8", "auto") . "</a>";
	  return $errorHTML;
  }

  $userId = $user->getId();
  
  if( Dap_Session::isLoggedIn() && isset($user) ) { 
	  //get userid
	  $userProduct = Dap_UsersProducts::load($user->getId(), $upgradefrom);
  }

  $emailFilter = $user->getEmail();
  $productIdFilter = $userProduct->getProduct_id();
  $transIdFilter = $userProduct->getTransaction_id();
  
  if($transIdFilter <= 0) {
	  logToFile("DAPUpgradeButton.php : SKIP transNumFilter=".$transNumFilter . " less than 0, product=".$productIdFilter,LOG_INFO_DAP);
	  continue;
  }
  
  $statusFilter = "";
  
  // TODO: only pick up subscription transaction
  logToFile("DAPUpgradeButton.php : transIdFilter=".$transIdFilter,LOG_INFO_DAP);
  logToFile("DAPUpgradeButton.php : transNumFilter=".$transNumFilter,LOG_INFO_DAP);
  logToFile("DAPUpgradeButton.php : emailFilter=".$emailFilter,LOG_INFO_DAP);
  logToFile("DAPUpgradeButton.php : productIdFilter=".$productIdFilter,LOG_INFO_DAP);
  logToFile("DAPUpgradeButton.php : statusFilter=".$statusFilter,LOG_INFO_DAP);
  
  $TransactionsList = Dap_Transactions::loadTransactions($transNumFilter, $emailFilter, $productIdFilter, $statusFilter,"","",$transIdFilter);
  
  $foundTransaction=false;
  $authnet=false;
  $paypal=false;
  $recurring_id ="";
  $cancelled_subscription="";
  foreach ($TransactionsList as $transaction) {
	parse_str($transaction->getTrans_blob(), $list);
   // logToFile("DAPUpgradeButton.php: Payment processor is paypal, setting address details before list",LOG_INFO_DAP); 
	
	if (($list == NULL) || !isset($list))
	   logToFile("DAPUpgradeButton.php::LIST EMPTY"); 
		
	foreach ($list as $key => $value) {
	  // logToFile("DAPUpgradeButton.php::LIST DETAILS(): Key=".$key.", Value=".$value); 
	}
	
	logToFile("DAPUpgradeButton.php::TRANSACTION TYPE  =  " . $transaction->getTrans_type()); 
	
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
	
   
	logToFile("DAPUpgradeButton.php::recurring_id".$recurring_id); 
	//PaypalPDT
	$payment_processor = $transaction->getPayment_processor();
	if ($payment_processor == "MANUAL") {
		continue;
	}
	if ( ($payment_processor == "AUTHNET") && (stristr($paymentgateway, $payment_processor) == 0)) {
		//return.. show authnet button 
		logToFile("DAPUpgradeButton.php::show authnet button".$recurring_id); 
		return $content;
	}
	if ( ($payment_processor == "PAYPAL") && (stristr($paymentgateway, $payment_processor) == 0)) {
		logToFile("DAPUpgradeButton.php::show paypal button".$recurring_id); 
		return $content;
	}
	
	$transaction_id=$transaction->getTrans_num();
	$foundTransaction=true;
	logToFile("DAPUpgradeButton.php : foundTransaction=".$foundTransaction,LOG_INFO_DAP);
	break; 
  } //foreach transaction
		
  logToFile("DAPUpgradeButton.php : found recurring_id=".$recurring_id.", but not authnet or paypal subscription... cannot show upgrade button, show buy button",LOG_INFO_DAP);
  $content = str_replace( 'upgradeFrom', "upgradeNotAvailable", $content); 
  
  return $content;
  
}		

?>