<?php 

add_shortcode('DAPShowTransactions', 'dap_displaytransactions');

function dap_displaytransactions($atts, $content=null){ 
  extract(shortcode_atts(array(
	  'template' =>'template1',
	  'invoicetemplate'=>'template1',
	  'adminemail'=>'',
	  'companyname'=>'',
	  'companyurl'=>'',
	  'logo'=>'',		
	  'notranmsg'=>'Sorry, no transactions found',
	  'showinvoice' =>'Y'
  ), $atts));
  
  $content = do_shortcode(dap_clean_shortcode_content($content));	
  
  $session = Dap_Session::getSession();
  $user = $session->getUser();
  if($adminemail=='')
  {
 	 $adminemail = Dap_Config::get("ADMIN_EMAIL");
  }
  if($companyname=='')
  {
  	$companyname = Dap_Config::get("SITE_URL_DAP"); 
  }
  
  if( !Dap_Session::isLoggedIn() || !isset($user) ) {
	  //logToFile("Not logged in, returning errmsgtemplate");
	  $errorHTML = mb_convert_encoding(MSG_PLS_LOGIN, "UTF-8", "auto") . " <a href=\"" . Dap_Config::get("LOGIN_URL") . "\">". mb_convert_encoding(MSG_CLICK_HERE_TO_LOGIN, "UTF-8", "auto") . "</a>";
	  return $errorHTML;
  }

  $userId = $user->getId();
  $blogpath=get_option("siteurl");
  
  $fullcustomcss =  WP_PLUGIN_DIR . "/DAP-WP-LiveLinks/includes/transactions/".$template."/css/customtransactiontemplate.css";   
  $customcss=get_option('siteurl')."/wp-content/plugins/DAP-WP-LiveLinks/includes/transactions/".$template."/css/customtransactiontemplate.css?ver=1";
  $fcss = parse_url($customcss);
  $customcss=$fcss["path"];
   
  $formcss=get_option('siteurl')."/wp-content/plugins/DAP-WP-LiveLinks/includes/transactions/".$template."/css/transactiontemplate.css?ver=1";
  $fcss = parse_url($formcss);
  $formcss=$fcss["path"];
  logToFile("DAP-ShortCodes-Transactions: formcss= ". $formcss); 
  
  if($logo == "") 
  	$logo=get_option('siteurl')."/wp-content/plugins/DAP-WP-LiveLinks/includes/transactions/invoices/" . $template . "/images/invoice_logo.jpg";
	
  if(file_exists($fullcustomcss)) {
	 $formcss=$customcss;
	 logToFile("DAP-ShortCodes-Transactions: custom css: ". $customcss);
  }
  
  $content .= '<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
  <script>
  function invoicepdf(formname, prodId,invoicetemplate,adminemail,companyname,currency,status,email,fname,lname, amount,  paymentProcessor,recurringId,transactionId,logo,tdate,blogpath) {
	var submiturl="/dap/inc/tp/invoices/converter.php";
	
var url = "/dap/inc/tp/invoices/converter.php?productId="+prodId+"&invoicetemplate="+invoicetemplate+"&currency="+currency+"&amount="+amount+"&ptype="+paymentProcessor+"&transactionId="+transactionId+"&tdate="+tdate+"&logo="+logo+"&email="+email+"&fname="+fname+"&lname="+lname+"&companyname="+companyname;

	//alert(url);
	window.open(url);		 
	return;
  }
  </script>';
  $content .= '<link rel="stylesheet" type="text/css" href="' . $formcss . '" />';
  $content .= '<div id="wrapper">';
  $content .= '<form name="formshowtrans" id="formshowtrans">';
  
  $lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
  $template_headerpath= ABSPATH ."/wp-content/plugins/DAP-WP-LiveLinks/includes/transactions/".$template."/customtransactionHeaderTemplate.html";
  
  if(file_exists($template_headerpath)) {
  	$temp_headercontent = file_get_contents($template_headerpath);
  }
  
  if($temp_headercontent == "") {
  	$template_headerpath=ABSPATH . "/wp-content/plugins/DAP-WP-LiveLinks/includes/transactions/".$template."/transactionHeaderTemplate.html";
	 logToFile("DAP-ShortCodes-Transactions: template_headerpath= ". $template_headerpath); 
	 
	if(file_exists($template_headerpath)) {
	  $temp_headercontent = file_get_contents($template_headerpath);
	  $content .= $temp_headercontent;   
	  if($showinvoice=='Y')
	  {
		 $content .= '<div class="cell-header">Invoice</div>';
	  } 
	  $content .=  '</div>';
	}
	else{
		$temp_headercontent ='<div class="cell-header">Sorry template is not found</div>';
		$content .= $temp_headercontent;
	}
  	
	logToFile("DAP-ShortCodes-Transactions: no custom header template found=".$template_headerpath,LOG_INFO_DAP);
  } else {
	logToFile("DAP-ShortCodes-Transactions: custom headertemplate found=".$template_headerpath,LOG_INFO_DAP);  
  }
  
  $template_path=$ABSPATH . "/wp-content/plugins/DAP-WP-LiveLinks/includes/transactions/".$template."/customtransactionTemplate.html";
  if(file_exists($template_path)) {
	  $temp_content = file_get_contents($template_path);
  }
  
  if($temp_content == "") {
  	$template_path=ABSPATH . "/wp-content/plugins/DAP-WP-LiveLinks/includes/transactions/".$template."/transactionTemplate.html";
	if(file_exists($template_headerpath)) {
		$temp_content = file_get_contents($template_path);
	}
	else{
		$temp_content = "";
  	}
  	
	logToFile("DAP-ShortCodes-Transactions: no custom template found=".$template_path,LOG_INFO_DAP);
  } else {
	logToFile("DAP-ShortCodes-Transactions: custom template found=".$template_path,LOG_INFO_DAP);  
  }
  
  $emailFilter = $user->getEmail();
 
  logToFile("DAP-ShortCodes-Transactions: emailFilter=".$emailFilter,LOG_INFO_DAP);
 		
  $TransactionsList = Dap_Transactions::loadTransactions($transNumFilter, $emailFilter, $productIdFilter, $statusFilter);
  $recurring_id ="";
  $found=false;
  foreach ($TransactionsList as $transaction) {
	logToFile("DAP-ShortCodes-Transactions: found transaction for emailFilter=".$emailFilter,LOG_INFO_DAP);
	parse_str($transaction->getTrans_blob(), $list);	  
	if($transaction->getTrans_type() == "subscr_signup") {
		continue;
	}
	$recurring_id="";
	if(array_key_exists('recurring_payment_id',$list)) {
		$recurring_id = $list["recurring_payment_id"];
	}
	else if(array_key_exists('subscr_id',$list)) {
		$recurring_id = $list["subscr_id"];
	}
	else if(array_key_exists('sub_id',$list)) {
		$recurring_id = $list["sub_id"];
	}
	
	$payment_processor = $transaction->getPayment_processor();
	$transaction_id=$transaction->getTrans_num();
	
	$newtrans=explode(':',$transaction_id);
	if($recurring_id=="")
		$recurring_id=$newtrans[0];
		
	$amount= $transaction->getPayment_value();
	$productId=$transaction->getProduct_id();
	$transDate=$transaction->getTime();
	$status=$transaction->getPayment_status();
	$currency=$transaction->getPayment_currency();
	
	logToFile("DAP-ShortCodes-Transactions: productId=".$productId,LOG_INFO_DAP);
	 
	if( $productId != "") {
	  $product = Dap_Product::loadProduct($productId);  
	  $pname=$product->getName();
	  logToFile("DAP-ShortCodes-Transactions: product name=".$pname,LOG_INFO_DAP);
	  $prodId=$product->getId();
	  $email=$user->getEmail();
	  $fname=$user->getFirst_name();
	  $lname=$user->getLast_name();
	  $desc=$product->getDescription();
	}
	
	$current_msg=$temp_content;
		
	$current_msg = str_replace( '[FORMNAME]', "document.formshowtrans", $current_msg); 
	$current_msg = str_replace( '[AMOUNT]', trim(Dap_Config::get('CURRENCY_SYMBOL')).$amount, $current_msg); 
	$current_msg = str_replace( '[ITEMNAME]', $product->getName(), $current_msg); 
	$current_msg = str_replace( '[DATE]', $transDate, $current_msg); 
	$current_msg = str_replace( '[CURRENCY]', $currency, $current_msg); 
	$current_msg = str_replace( '[PAYSTATUS]', $status, $current_msg); 
	$current_msg = str_replace( '[PAYMENTPROCESSOR]', $payment_processor, $current_msg); 
	$current_msg = str_replace( '[TRANSACTIONID]', $newtrans[0], $current_msg); 
	
	$_SESSION["invblogpath"]=ABSPATH;
	$_SESSION["invinvoicetemplate"]=$invoicetemplate;
	$_SESSION["invadminemail"]=$adminemail;
	$_SESSION["invcompanyname"]=$companyname;
	$_SESSION["invcompanyurl"]=$companyurl;
	$_SESSION["invemail"]=$email;
	$_SESSION["invfname"]=$fname;
	$_SESSION["invlname"]=$lname;
	$_SESSION["invlogo"]=$logo;
	
	$_SESSION["invpid"]=$prodId;
	$_SESSION["invcurrency"]=$amount;
	$_SESSION["invamount"]=$currency;
	$_SESSION["incptype"]=$payment_processor;
	$_SESSION["invtid"]=$recurring_id;
	$_SESSION["invtdate"]=$transDate;
	
	if($showinvoice=='Y') {
	  $pdf = ' <div class="cell-left">';
	  $pdf .='<a href="" onclick="';
	  $pdf .="return invoicepdf(document.formshowtrans,'";
	  $pdf .="$prodId','$invoicetemplate','$adminemail','$companyname','$currency','$status','$email','$fname','$lname','$amount','$payment_processor','$recurring_id','$newtrans[0]','$logo','$transDate','" . ABSPATH . "');";
	  $pdf .='">View PDF</a>';
	  $pdf .='</div>';
	  $current_msg = str_replace( '[pdf]', $pdf, $current_msg); 
	}
	else
	{
	  $current_msg = str_replace( '[pdf]', "", $current_msg);
	}
	$content .= $current_msg;	
	 
	$found=true;
  } //end foreach
	
  if(!$found) {
	 $content.=$notranmsg;
  }
   
  $content.='</form></div>';
  return $content;
}		

?>