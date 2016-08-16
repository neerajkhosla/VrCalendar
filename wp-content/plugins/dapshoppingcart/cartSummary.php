<?php 
	$lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
	if(file_exists($lldocroot . "/dap/dap-config.php")) include_once ($lldocroot . "/dap/dap-config.php");
	$amt = 0;
	$new_amount = 0;
	$_SESSION["ssl"]=$_REQUEST["ssl"];
	$num_cart_item = $_SESSION['num_cart'];
	
	logToFile("cartSummary.php: num_cart_item: ". $num_cart_item);	
	if ( (isset($_REQUEST["blogpath"])) && ( $_REQUEST["blogpath"] != "" )) {
		$blogpath=$_REQUEST["blogpath"];
		logToFile("cartSummary.php: blogpath2: ". $blogpath);	
	}

	if($blogpath == "")
		$blogpath=$_SESSION["blogpath"];
		
	$couponfound=0; 
	
	for($i=0;$i<$num_cart_item;$i++) {
		logToFile("shoppingCartConfirmation.php: item$i=".$_SESSION['product_details'][$i]['L_NAME'.$i],LOG_DEBUG_DAP);	
	}
	if (isset($_SESSION["couponfound"]) && (intval($_SESSION["couponfound"]>0))) {
		logToFile("shoppingCartConfirmation.php: coupon found",LOG_DEBUG_DAP);	
		$couponfound=1;
	}	
	else {
		logToFile("shoppingCartConfirmation.php: NO coupon found",LOG_DEBUG_DAP);	
	}
	
	if ($_REQUEST['currency_symbol'] == "") {
		if ($_SESSION['currency_symbol'] != "") {
			$currency_symbol=$_SESSION['currency_symbol'];
			$currency = $_SESSION['currency'];
		}
		else {
			$currency_symbol="$";
			$currency=Dap_Config::get('CURRENCY_TEXT');
	    	logToFile("buynow.php: currency_symbol=".$currency_symbol,LOG_DEBUG_DAP);	
		}
	}
	else {
		$currency_symbol=$_REQUEST['currency_symbol'];
		$currency = $_REQUEST['currency'];
	}
	
	logToFile("buynow.php: currency_symbol=".$currency_symbol,LOG_DEBUG_DAP);	
	logToFile("buynow.php: currency=".$currency,LOG_DEBUG_DAP);	
	
	if(isset($_SESSION["couponfound"])) { 
		$_SESSION["couponfound"]=0;
		unset($_SESSION["couponfound"]);
	}
	
	if ($_SESSION['is_submitted'] == "Y") {
		for($i=0;$i<$num_cart_item;$i++) { 
			$amt = $amt + ( $_SESSION['product_details'][$i]['L_AMT' . $i] * $_SESSION['product_details'][$i]['L_QTY' . $i] );
			$new_amount = $amt;
			$_SESSION['new_amount'] = $amt;
		}
		logToFile("shoppingCartConfirmation.php: is submitted=Y. new_amount= " . $_SESSION['new_amount'],LOG_DEBUG_DAP);	
		
		unset($_SESSION['is_submitted']);
	}
	
	if( (isset($_REQUEST["freeTrial"])) && ($_REQUEST["freeTrial"] == "Y")) 
		$freeTrial=$_REQUEST["freeTrial"];
	else if( (isset($_SESSION["freeTrial"])) && ($_SESSION["freeTrial"] == "Y")) 
		$freeTrial=$_SESSION["freeTrial"];
	
	if (isset($_REQUEST['payment_gateway'])) {
		logToFile("shoppingCartConfirmation.php: set payment gateway=".$_REQUEST['payment_gateway'],LOG_DEBUG_DAP);	
		$_SESSION['payment_gateway'] = $_REQUEST['payment_gateway'];
	} 
	
	if ((isset($_REQUEST['payment_succ_page'])) && ($_REQUEST['payment_succ_page'] != "")) {
		$payment_succ_page = $_REQUEST['payment_succ_page'];
		logToFile("shoppingCartConfirmation.php: request  payment_succ_page IS = " . $payment_succ_page,LOG_DEBUG_DAP);	
	}
	else {
		$payment_succ_page = $_SESSION['payment_succ_page'];
		logToFile("shoppingCartConfirmation.php: session payment_succ_page IS = " . $payment_succ_page,LOG_DEBUG_DAP);	
	}
	
	logToFile("shoppingCartConfirmation.php: payment_succ_page IS = " . $payment_succ_page,LOG_DEBUG_DAP);	
	logToFile("shoppingCartConfirmation.php: session new_amount IS = " . $_SESSION['new_amount'],LOG_DEBUG_DAP);	
	
	logToFile("shoppingCartConfirmation.php: cartemptymessage = " . $cartemptymessage,LOG_DEBUG_DAP);	
	
	$template='template1';
	if(isset($templatecartsummary) && ($templatecartsummary !='') )
		$template=$templatecartsummary;

	if(isset($cancelimg) && ($cancelimg !=''))
		$cancelimg=$cancelimg;
	else $cancelimg='/dap/images/cancelgrey.png';
	
	if(isset($continueimg) && ($continueimg !=''))
		$continueimg=$continueimg;
	else $continueimg='/dap/images/continueshopping.png';
	
	if(isset($checkoutimg) && ($checkoutimg !=''))
		$checkoutimg=$checkoutimg;
 	else $checkoutimg='/dap/images/checkout.png';
	
	if(isset($cartemptymessage) && ($cartemptymessage !=''))
		$cartemptymessage=$cartemptymessage;
	else $cartemptymessage=' Sorry, your cart is empty.';
	
	if(isset($cartemptyimage) && ($cartemptyimage!='') )
		$cartemptyimage=$cartemptyimage;
	else $cartemptyimage='/dap/images/emptycart.jpg';
	
	if(isset($updatecartmsg) && ($updatecartmsg !=''))
		$updatecartmsg=$updatecartmsg;
	else $updatecartmsg='Are you sure you want to update the quantity?';
	 
	if(isset($updatecartsuccessmsg) && ($updatecartsuccessmsg !=''))
		$updatecartsuccessmsg=$updatecartsuccessmsg;
	else $updatecartsuccessmsg='Updated Successfully. If you have a coupon code, please re-enter it.';
	

	
	//$_SESSION["blogpath"]="";
	//unset($_SESSION["blogpath"]);
	
	$fullformcss = $blogpath . "/wp-content/plugins/dapshoppingcart/includes/templates/dapcartsummary/".$template."/css/dapcartsummary.css";
	$fullcustomcss =  $blogpath . "/wp-content/plugins/dapshoppingcart/includes/templates/dapcartsummary/".$template."/css/customdapcartsummary.css";
	
	//$customcss="/dap/inc/template/".$template."/css/custombuynow.css";
	//$formcss="/dap/inc/template/".$template."/css/buynow.css";
	
  if ( (isset($_REQUEST["wpfoldername"])) && ( $_REQUEST["wpfoldername"] != "" ))
	$wpfoldername=$_REQUEST["wpfoldername"];

  if($wpfoldername == "")
	  $wpfoldername=$_SESSION["wpfoldername"];
  
 // $_SESSION["wpfoldername"]="";
  //unset($_SESSION["wpfoldername"]);
  
  logToFile("shoppingCartConfirmation.php: wpfoldername = " . $wpfoldername,LOG_DEBUG_DAP);	
	
  $customcss=$wpfoldername."/wp-content/plugins/dapshoppingcart/includes/templates/dapcartsummary/" . $template . "/css/customdapcartsummary.css";
  logToFile("cartSummary.php: custom css: ". $customcss);	
  
  $formcss=$wpfoldername."/wp-content/plugins/dapshoppingcart/includes/templates/dapcartsummary/" . $template . "/css/dapcartsummary.css";
  logToFile("cartSummary.php: formcss css: ". $formcss);	
  
  if(file_exists($fullcustomcss)) {
	 logToFile("cartSummary.php: custom css: ". $customcss);	
	 $formcss=$customcss;
	 logToFile("cartSummary.php: custom css: ". $customcss);
  }
	
?>


<link rel='stylesheet' type='text/css' href='<?php echo $formcss; ?>'>
<script type='text/javascript' language='javascript' src="/dap/javascript/country-state.js"></script>
<script type='text/javascript' language='javascript' src="/dap/javascript/common.js"></script>
<script type='text/javascript' language='javascript' src="/dap/javascript/paymentvalidation.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script type='text/javascript' language='javascript'>
	
	function calculateTotal() {
		//alert ("calcualte total");
		<?php 
			logToFile("shoppingCartConfirmation.php: payment gateway IS = " . $_POST['payment_gateway'],LOG_DEBUG_DAP);	
			if ($_SESSION['post'] != "") { 
				logToFile("shoppingCartConfirmation.php: payment gateway not empty",LOG_DEBUG_DAP);	
				$_GET['msg'] = "";
				
				$_SESSION['post'] = "";
			}
		?>
	}
	
	function couponSuccessAlert() {
		var couponfound= <?php echo $couponfound; ?>;
		var couponsuccessmsg ='<?php echo $couponsuccessmsg; ?>';
		if(couponfound==1) alert(couponsuccessmsg);
	}
	
	function processUpsell(upsellForm) {
		var emptycartmsg='<?php echo $cartemptymessage; ?>';
		<?php
			unset($_SESSION['msg']);
			unset($_SESSION['err_text']);
					
			if (isset($_SESSION['num_cart']) && $_SESSION['num_cart'] != 0) {
				//upsellForm.action = "https://contentresponder.com/blog/checkout-page";
				if (!isset($_SESSION['checkoutPage']) || ($_SESSION['checkoutPage'] == null)) {
					$payment_url = SITE_URL_DAP . "/dap/buynow.php";
					logToFile("shoppingCartConfirmation: session checkoutPage not set", LOG_DEBUG_DAP);	
					/*if($_SESSION["ssl"]=="N") 
					$_SESSION['checkoutPage'] = str_replace ( "http:", "http:", $payment_url );
					else
					$_SESSION['checkoutPage'] = str_replace ( "http:", "https:", $payment_url );*/
				}
				else {
					/*if($_SESSION["ssl"]=="N")
					$_SESSION['checkoutPage'] = str_replace ( "http:", "http:", $_SESSION['checkoutPage'] );
					else
					$_SESSION['checkoutPage'] = str_replace ( "http:", "https:", $_SESSION['checkoutPage'] );*/
				}	
				logToFile("shoppingCartConfirmation: unset post params in session", LOG_DEBUG_DAP);
				
				$str = "payment_gateway=" . $_SESSION['payment_gateway']; 
				$btntype = "btntype=" . "addtocart";
				
				logToFile("shoppingCartConfirmation: number = " . $_SESSION["num_cart"], LOG_DEBUG_DAP);
				?>
				
				upsellForm.action = "<?php $redir = $_SESSION['checkoutPage'] . '?' . $str . '&' . $btntype; echo $redir;?>";
				upsellForm.submit();			
			
			<?php
			}
			else {
			?>
				var buttonSub = document.getElementById('checkout');
				alert(emptycartmsg);
				buttonSub.disabled = true; 
			<?php	
			}
		?>
	}
	
	function processContinue(continueForm) {
		continueForm.action = "<?php echo $_SESSION['continuePage'];  ?>";
		continueForm.submit();			
	}
	
	function applyCoupon(form) {
		if (form.coupon_code.value == null || form.coupon_code.value == "") {
			alert("Please enter coupon code");	
			form.coupon_code.focus();
			return;
		}
		
		if (form.coupon_code.value == "Enter Coupon (optional)") {
			alert("Please enter coupon code");	
			form.coupon_code.focus();
			return;
		}
		
		
		//use coupon code to get discount amount
		
		document.getElementById('btnCoupon').style.display = 'none';
		document.getElementById('btnPleaseWait').style.display = 'initial';

		form.action = "<?php echo '/dap/inc/validateCoupon.php'; ?>";
		form.submit();		
	}
	
	function removeAllItems() {
		
		var r=confirm("Are you sure you want to remove ALL items from the cart?");
		if (r==true)
		{			
			var submiturl="/dap/RemoveFromCart.php";
			
			jQuery.ajax({
			  url: submiturl,
			  type: "POST",
			  async: false,
			  data: {"itemname":"ALLITEMS"},
			  cache: false,
			  success: function (returnval) {
				if(returnval==0) {
				 //alert(updatecartsuccessmsg);  
				 // alert(cancelsuccess);
				  window.location.reload();
				}
				else if(returnval==1) {
				  //alert(cancelfailed);
				}
			  }
			}); //ajax
			return;
		}
		else{
			return false;
		}
	}
	
	function updatecart(id)
	{
		var str=id;
		var n=str.split("_");
		var qtyid=n[1];
		var qtyname='qty_'+qtyid;
		var updatecartmsg = '<?php echo $updatecartmsg; ?>';
		var updatecartsuccessmsg = '<?php echo $updatecartsuccessmsg; ?>';
		var qtyvalue=document.getElementById('qty_'+qtyid).value;
		//alert(qtyid);
		var r=confirm(updatecartmsg);
		if (r==true)
		{
			
		var submiturl="/dap/updateCartItems.php";
		
		jQuery.ajax({
		  url: submiturl,
		  type: "POST",
		  async: false,
		  data: {"qtyname":qtyname,"qtyvalue":qtyvalue,"qtyid":qtyid,"action":"update"},
		  cache: false,
		  success: function (returnval) {
			if(returnval==0) {
			 alert(updatecartsuccessmsg);  
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
	
</script>

<?php

	// CHANGE NAMES SHD NOT BE paypal - location for template/css shd be /dap/inc/content/dapcartsummary/
	$redirect = $_SERVER['PHP_SELF'];
	
	if (!isset($_SESSION['num_cart'])) 
		$_SESSION['num_cart'] = 0;
	
	if (!isset($_SESSION['recur_count'])) 
		$_SESSION['recur_count'] = 0;	
	
	$total_non_recur = $_SESSION['num_cart'] - $_SESSION['recur_count'];
	
	//echo "<pre>";
	//print_r($_SESSION);
 	/*******************Header*******************/
	$current_msg ='<center>';
	//$current_msg .='<form name="formOrderCheckout" id="formOrderCheckout" method="post">';
	//VEENA - SHOW ERRORS IN A DIV AT THE TOP
	if(isset($_GET['err_text'])) {
	  $errortext="Error: ".$_GET['err_text'];
	  //$errorvisibility='block';
	}
	else {
	  $errortext="";
	  $errorvisibility='none';
	}
	
	/***********************Main header*********************************/
	
	$fullcustomheaderhtml =  $blogpath ."/wp-content/plugins/dapshoppingcart/includes/templates/dapcartsummary/".$template."/customcartmainheader.html";   
	if(file_exists($fullcustomheaderhtml)) {
		 $headertemplate=$fullcustomheaderhtml;
	}
	else
	{
		 //$checkouttemplate=$lldocroot ."/dap/inc/template/".$template."/checkout.html";
		$headertemplate =  $blogpath ."/wp-content/plugins/dapshoppingcart/includes/templates/dapcartsummary/".$template."/cartmainheader.html";   
	}
		
	//$checkouttemplate=$lldocroot ."/dap/inc/template/checkout.html";
	$headercart = file_get_contents($headertemplate);
	$headercontent = $headercart; 
	$current_msg .=$headercontent;
	//echo "<pre>";
	//print_r($_SESSION);
	/*******************************End main header*********************/
	$fullcustomproductpagehtml=$blogpath ."/wp-content/plugins/dapshoppingcart/includes/templates/dapcartsummary/".$template."/customproductcartsummarypagetemplate.html";   
	if(file_exists($fullcustomproductpagehtml)) 
		 $cartproducttemplate=$fullcustomproductpagehtml;
	else
		 $cartproducttemplate=$blogpath ."/wp-content/plugins/dapshoppingcart/includes/templates/dapcartsummary/".$template."/productcartsummarypagetemplate.html";  
	
	$tempproductcart = file_get_contents($cartproducttemplate);
	$tempproductcontent = $tempproductcart; 
	if ($total_non_recur > 0) { 
		$num_cart_item = $_SESSION['num_cart'];
		$fullcustomproductheaderhtml=$blogpath ."/wp-content/plugins/dapshoppingcart/includes/templates/dapcartsummary/".$template."/customproductcartsummaryheadertemplate.html";   
		if(file_exists($fullcustomproductheaderhtml)) 
			$cartproductheadertemplate=$fullcustomproductheaderhtml;
		else
			$cartproductheadertemplate=$blogpath ."/wp-content/plugins/dapshoppingcart/includes/templates/dapcartsummary/".$template."/productcartsummaryheadertemplate.html";
		//$checkouttemplate=$lldocroot ."/dap/inc/template/checkout.html";
		$tempproductheadercart = file_get_contents($cartproductheadertemplate);
		$tempproductheadercontent = $tempproductheadercart; 
		$current_msg .= $tempproductheadercontent;
		
		$fullcustomproductpagehtml =  $blogpath ."/wp-content/plugins/dapshoppingcart/includes/templates/dapcartsummary/".$template."/customproductcartsummarypagetemplate.html";   
		if(file_exists($fullcustomproductpagehtml)) 
			 $cartproducttemplate=$fullcustomproductpagehtml;
		else
			 $cartproducttemplate=$blogpath ."/wp-content/plugins/dapshoppingcart/includes/templates/dapcartsummary/".$template."/productcartsummarypagetemplate.html";
		
		$tempproductcart = file_get_contents($cartproducttemplate);
		$tempproductcontent = $tempproductcart; 
		logToFile("NON RECURRING: num_cart_item= " .$num_cart_item, LOG_DEBUG_DAP);
		for($i=0;$i<$num_cart_item;$i++) { 
			if ($_SESSION['product_details'][$i]['L_ISRECUR' . $i] != "Y") {
		//$checkouttemplate=$lldocroot ."/dap/inc/template/checkout.html";
				$current_msg .=$tempproductcontent;
				$itemname=$_SESSION['product_details'][$i]['L_NAME' . $i];
				$product = Dap_Product::loadProductByName(trim($itemname));
				$productId = $product->getId();
	
				logToFile("NON RECURRING: productId" .$productId, LOG_DEBUG_DAP);
				$item_name = $product->getName();
				$description = $product->getDescription();
				$prodimage = $product->getProduct_image_url();
				if($prodimage == "")
					$prodimage='/dap/images/noimagesfound.jpg';
				
				if(isset($prodimage) && ($prodimage !=''))
					$prodimage=$prodimage;
				
				$itemprice=$_SESSION['product_details'][$i]['L_AMT' . $i];
				$newitemprice=$itemprice * $_SESSION['product_details'][$i]['L_QTY' . $i];
				//$currency_symbol=trim(Dap_Config::get('CURRENCY_SYMBOL'));
				$itemdesc=$_SESSION['product_details'][$i]['L_DESC' . $i];
				$removeimage='/dap/images/icon_delete.png';
				//$quantity=$_SESSION['product_details'][$i]['L_QTY' . $i];
				$quantity="<input type='text' value='".$_SESSION['product_details'][$i]['L_QTY' . $i]."' name='qty_".$i."' id='qty_".$i."' size='2' maxlength='2'/>";
				$quantity .='<input type="button" style="margin:2px;font-weight: normal;" id="update_'.$i.'" value="Update" onclick="return updatecart(this.id);" />';
				
				$current_msg = str_replace( '[PRODUCTIMAGESRC]', $prodimage, $current_msg);
				$current_msg = str_replace( '[ITEMDESCRIPTION]', $itemdesc, $current_msg);
				$current_msg = str_replace( '[ITEMNAME]', $itemname, $current_msg);
				$current_msg = str_replace( '[ITEMPRICE]',$currency_symbol.number_format($newitemprice, 2, '.', ''), $current_msg);
				$current_msg = str_replace( '[QTY]', $quantity, $current_msg);
				$current_msg = str_replace( '[DELETEIMAGESRC]', $removeimage, $current_msg);	
			}
		}
	}
	
	/********************************End header*******************/
	if ($_SESSION['recur_count'] > 0) {
		$num_cart_item = $_SESSION['num_cart'];
		$fullcustomheaderhtml =  $blogpath ."/wp-content/plugins/dapshoppingcart/includes/templates/dapcartsummary/".$template."/customcartproductcartsummaryheadertemplate.html";   
		if(file_exists($fullcustomheaderhtml)) 
			$cartheadertemplate=$fullcustomheaderhtml;
		else
			$cartheadertemplate=$blogpath ."/wp-content/plugins/dapshoppingcart/includes/templates/dapcartsummary/".$template."/cartproductcartsummaryheadertemplate.html";	
		//$checkouttemplate=$lldocroot ."/dap/inc/template/checkout.html";
		$tempheadercart = file_get_contents($cartheadertemplate);
		$tempheadercontent = $tempheadercart; 
		$current_msg .=$tempheadercontent;
		$fullcustompagehtml =$blogpath ."/wp-content/plugins/dapshoppingcart/includes/templates/dapcartsummary/".$template."/customcartproductcartsummarypagetemplate.html";   
		if(file_exists($fullcustompagehtml)) 
			 $carttemplate=$fullcustompagehtml;
		else
			 $carttemplate=$blogpath ."/wp-content/plugins/dapshoppingcart/includes/templates/dapcartsummary/".$template."/cartproductcartsummarypagetemplate.html";
		
		//$checkouttemplate=$lldocroot ."/dap/inc/template/checkout.html";
		$tempcart = file_get_contents($carttemplate);
		$tempcontent = $tempcart; 
		for($i=0;$i<$num_cart_item;$i++) { 
			if ($_SESSION['product_details'][$i]['L_ISRECUR' . $i] == "Y") {
				$current_msg .=$tempcontent;
				$itemname=$_SESSION['product_details'][$i]['L_NAME' . $i];
				
				$product = Dap_Product::loadProductByName(trim($itemname));
				$productId = $product->getId();
	
				logToFile("IS RECUR: productId" .$productId, LOG_DEBUG_DAP);
				$item_name = $product->getName();
				$description = $product->getDescription();
				$prodimage = $product->getProduct_image_url();
				if($prodimage == "")
					$prodimage='/dap/images/noimagesfound.jpg';
				if(isset($prodimage) && ($prodimage !=''))
					$prodimage=$prodimage;
				$trial_amount = $product->getTrial_price();
				$amount = $product->getPrice();
	
				$is_recurring = $product->getIs_recurring();
	
				$recurring_cycle_1 = $product->getRecurring_cycle_1();
				$recurring_cycle_2 = $product->getRecurring_cycle_2();
				$recurring_cycle_3 = $product->getRecurring_cycle_3();
				$total_occurrences = $product->getTotal_occur();
	
				$itemprice=$_SESSION['product_details'][$i]['L_AMT' . $i];
				$newitemprice=$itemprice * $_SESSION['product_details'][$i]['L_QTY' . $i];
				
				//$currency_symbol=trim(Dap_Config::get('CURRENCY_SYMBOL'));
				
				$itemdesc=$_SESSION['product_details'][$i]['L_DESC' . $i];
				if( ($trial_amount <= 0) && ( ($freeTrial != "Y") || ($_SESSION['payment_gateway']!="stripe")) ) {
					$trial_amount=$amount;
					logToFile("shoppingCartConfirmation: item_name=$item_name, trial_amount" .$trial_amount, LOG_DEBUG_DAP);
				}
				
				if( (isset($_SESSION['product_details'][$i]['L_COUPONAMT' . $i])) && ($_SESSION['product_details'][$i]['L_COUPONAMT' . $i] > 0))
					$trial_amount=$_SESSION['product_details'][$i]['L_COUPONAMT' . $i];
				
				if( (isset($_SESSION['product_details'][$i]['L_RECURCOUPONAMT' . $i])) && ($_SESSION['product_details'][$i]['L_RECURCOUPONAMT' . $i] > 0))
					$amount=$_SESSION['product_details'][$i]['L_RECURCOUPONAMT' . $i];
				
				if($total_occurrences>=999) {
					$terms="<strong style='font-size: 11px;font-weight: bold;color: #000;text-decoration: none;display: block;'>Subscription Terms</strong>"."$" . $trial_amount . " for first " . $recurring_cycle_1 . " days" . "\nthen $" . $amount . " for each " . $recurring_cycle_3 . " days.";
				}
				else {
					$terms="<strong style='font-size: 11px;font-weight: bold;color: #000;text-decoration: none;display: block;'>Subscription Terms</strong>"."$" . $trial_amount . " for first " . $recurring_cycle_1 . " days" . "\nthen $" . $amount . " for each " . $recurring_cycle_3 . " days, for " . $total_occurrences . " installments.";
				}
				$_SESSION['product_details'][$i]['L_TERMS' . $i] = $terms;
				
				$removeimage='/dap/images/icon_delete.png';
				
				$quantity="<input type='text' value='".$_SESSION['product_details'][$i]['L_QTY' . $i]."' name='qty_".$i."' id='qty_".$i."' size='2' maxlength='2' />";
				$quantity .='<input type="button"  id="update_'.$i.'" value="Update" style="margin:2px;font-weight: normal;" onclick="return updatecart(this.id);" />';
				$current_msg = str_replace( '[PRODUCTIMAGESRC]', $prodimage, $current_msg);
				
				$current_msg = str_replace( '[ITEMDESCRIPTION]', $itemdesc, $current_msg);
				$current_msg = str_replace( '[ITEMNAME]', $itemname, $current_msg);
				$current_msg = str_replace( '[ITEMTERMS]', $terms, $current_msg);
				$current_msg = str_replace( '[ITEMPRICE]', $currency_symbol.number_format($newitemprice, 2, '.', ''), $current_msg);
				$current_msg = str_replace( '[QTY]', $quantity, $current_msg);
				$current_msg = str_replace( '[DELETEIMAGESRC]', $removeimage, $current_msg);
			}
		}
	}
	
	if ($_SESSION['num_cart'] == 0 || $_SESSION['num_cart'] == "") {
		if (isset($_SESSION['product_details'])) {
			unset($_SESSION["product_details"]);
		}
		$amt=$currency_symbol . $amt;
		$current_msg .='	<table cellspacing="0" cellpadding="0" border="0" class="cartSummary">';
		$current_msg .='<tr>';
		$current_msg .='<td style="text-align: center;"><img src="'.$cartemptyimage.'"></td>';
	 	$current_msg .='</tr>';
	    $current_msg .='<tr>';
		$current_msg .='<td colspan="5" class="cartHeader">'.$cartemptymessage.'</td>';
		$current_msg .='</tr>';
			//echo "Cart is Empty... continue shopping";	
			
	}
	else {
		$amt = 0;
		if (isset($_SESSION['new_amount']) && ($_SESSION['new_amount'] != "")) {
			logToFile("shoppingCartConfirmation: NEW AMT=".$_SESSION['new_amount'], LOG_DEBUG_DAP);
			$amt = $_SESSION['new_amount'];
		   // echo $amt;
		}
		else  {	
			for($i=0;$i<$num_cart_item;$i++) { 
				$amt = $amt + ( $_SESSION['product_details'][$i]['L_AMT' . $i] * $_SESSION['product_details'][$i]['L_QTY' . $i] );
			}
		} 
			
		$amt=$currency_symbol . number_format($amt, 2, '.', '');
	}
                
	/***********************Button Template*************************/
	$fullcustombuttonpagehtml =  $blogpath ."/wp-content/plugins/dapshoppingcart/includes/templates/dapcartsummary/".$template."/customdapcartsummarybuttontemplate.html";   
	if(file_exists($fullcustombuttonpagehtml)) {	 	 
		 $cartbuttontemplate=$fullcustombuttonpagehtml;
	}
	else {
		 $cartbuttontemplate=$blogpath ."/wp-content/plugins/dapshoppingcart/includes/templates/dapcartsummary/".$template."/dapcartsummarybuttontemplate.html";	
	}
	//$checkouttemplate=$lldocroot ."/dap/inc/template/checkout.html";
	$tempbuttoncart = file_get_contents($cartbuttontemplate);
	$tempbuttoncontent = $tempbuttoncart;
	/*******************End button template*****************/
	
	$current_msg .=$tempbuttoncontent;
	$hiddenfields='<input name="redirect" type="hidden" id="redirect" value="'.$_SERVER['PHP_SELF'].'">';
    $hiddenfields .='<input type="hidden" name="err_redirect" value="'.$_SERVER['REQUEST_URI'].'">';
    $hiddenfields .='<input type="hidden" name="payment_succ_page" id="payment_succ_page" value="'.$payment_succ_page.'" />';
	if(isset($_REQUEST['payment_gateway'])){ $payment_gateway=$_REQUEST['payment_gateway'];}else {$payment_gateway=$_SESSION['payment_gateway'];}
    $hiddenfields .='<input type="hidden" name="payment_gateway" id="payment_gateway" value="'.$payment_gateway.'" />';
	$current_msg = str_replace( '[HIDDENFIELDS]', $hiddenfields, $current_msg);
	//$checkoutimg='';
	//$cancelimg='/dap/images/cancel.png';
	
    $current_msg = str_replace( '[CANCELIMGSRC]', $cancelimg, $current_msg);  
	$current_msg = str_replace( '[SHOPPINGIMGSRC]', $continueimg, $current_msg);  
	$current_msg = str_replace( '[CARTTOTAL]', $amt, $current_msg);                           
	$current_msg = str_replace( '[CHECKOUTIMGSRC]', $checkoutimg, $current_msg);
	$current_msg = str_replace( '[ERRORTEXT]', $errortext, $current_msg);
	$current_msg = str_replace( '[ERRORVISIBILITY]', $errorvisibility, $current_msg);
	//$current_msg .='</form>';
	$current_msg .='</center>';
	
	logToFile("shoppingCartConfirmation: final cart items=".$_SESSION['num_cart'], LOG_DEBUG_DAP);
	
  	echo $content = $current_msg;
	?>


<script language="javascript">
	calculateTotal();
	couponSuccessAlert();
</script>

<?php if(file_exists("inc/ordersummaryfooter.php")) include ("inc/ordersummaryfooter.php"); ?>