<?php

class Dap_Payment {
	var $card_type;
	var $card_num;
	var $exp_date;
	var $card_code;
	var $first_name;
	var $last_name;
	var $billing_first_name;
	var $billing_last_name;
	var $company;
	var $address1;
	var $address2;
	var $city;
	var $state;
	var $statecode;
	var $zip;
	var $country;
	var $countrycode;
	var $email;
	var $payerId;
	var $phone;
	var $fax;
	var $ship_to_first_name;
	var $ship_to_last_name;
	var $ship_to_address1;
	var $ship_to_address2;
	var $ship_to_company;
	var $ship_to_city;
	var $ship_to_state;
	var $ship_to_statecode;
	var $ship_to_zip;
	var $ship_to_country;
	var $ship_to_countrycode;
	var $howdidyouhearaboutus;
	var $comments;

	function getCard_type() {
		return $this->card_type;
	}
	function setCard_type($o) {
		$this->card_type = $o;
	}
	
	function getCard_num() {
		return $this->card_num;
	}
	function setCard_num($o) {
		$this->card_num = $o;
	}

	function getExp_date() {
		return $this->exp_date;
	}
	function setExp_date($o) {
		$this->exp_date = $o;
	}

	function getCard_code() {
		return $this->card_code;
	}	
	function setCard_code($o) {
		$this->card_code = $o;
	}

	function getFirst_name() {
		return $this->first_name;
	}
	function setFirst_name($o) {
		$this->first_name = $o;
	}

	function getLast_name() {
		return $this->last_name;
	}
	function setLast_name($o) {
		$this->last_name = $o;
	}


	function getBillingFirst_name() {
		return $this->billing_first_name;
	}
	function setBillingFirst_name($o) {
		$this->billing_first_name = $o;
	}

	function getBillingLast_name() {
		return $this->billing_last_name;
	}
	function setBillingLast_name($o) {
		$this->billing_last_name = $o;
	}
	
	function getCompany() {
		return $this->company;
	}
	function setCompany($o) {
		$this->company = $o;
	}

	function getAddress1() {
		return $this->address1;
	}
	function setAddress1($o) {
		$this->address1 = $o;
	}

	function getAddress2() {
		return $this->address2;
	}
	function setAddress2($o) {
		$this->address2 = $o;
	}

	function getCity() {
		return $this->city;
	}
	function setCity($o) {
		$this->city = $o;
	}

	function getState() {
		return $this->state;
	}
	function setState($o) {
		$this->state = $o;
	}
	
	function getStateCode() {
		return $this->statecode;
	}
	function setStateCode($o) {
		$this->statecode = $o;
	}

	function getZip() {
		return $this->zip;
	}
	function setZip($o) {
		$this->zip = $o;
	}

	function getCountry() {
		return $this->country;
	}
	function setCountry($o) {
		$this->country = $o;
	}

	function getCountryCode() {
		return $this->countrycode;
	}
	function setCountryCode($o) {
		$this->countrycode = $o;
	}
	
	function getEmail() {
		return $this->email;
	}
	function setEmail($o) {
		$this->email = $o;
	}

	function getPayerId() {
		return $this->PayerId;
	}
	function setPayerId($o) {
		$this->PayerId = $o;
	}
	
	function getPhone() {
		return $this->phone;
	}
	function setPhone($o) {
		$this->phone = $o;
	}

	function getFax() {
		return $this->fax;
	}
	function setFax($o) {
		$this->fax = $o;
	}

	function getShip_to_first_name() {
		return $this->ship_to_first_name;
	}
	function setShip_to_first_name($o) {
		$this->ship_to_first_name = $o;
	}

	function getShip_to_last_name() {
		return $this->ship_to_last_name;
	}
	function setShip_to_last_name($o) {
		$this->ship_to_last_name = $o;
	}

	function getShip_to_company() {
		return $this->ship_to_company;
	}
	function setShip_to_company($o) {
		$this->ship_to_company = $o;
	}

	function getShip_to_address1() {
		return $this->ship_to_address1;
	}
	function setShip_to_address1($o) {
		$this->ship_to_address1 = $o;
	}

	function getShip_to_address2() {
		return $this->ship_to_address2;
	}
	function setShip_to_address2($o) {
		$this->ship_to_address2 = $o;
	}

	function getShip_to_city() {
		return $this->ship_to_city;
	}
	function setShip_to_city($o) {
		$this->ship_to_city = $o;
	}

	function getShip_to_state() {
		return $this->ship_to_state;
	}
	function setShip_to_state($o) {
		$this->ship_to_state = $o;
	}

	function getShip_to_statecode() {
		return $this->ship_to_statecode;
	}
	function setShip_to_statecode($o) {
		$this->ship_to_statecode = $o;
	}

	function getShip_to_zip() {
		return $this->ship_to_zip;
	}
	function setShip_to_zip($o) {
		$this->ship_to_zip = $o;
	}

	function getShip_to_country() {
		return $this->ship_to_country;
	}
	function setShip_to_country($o) {
		$this->ship_to_country = $o;
	}

	function getShip_to_countrycode() {
		return $this->ship_to_countrycode;
	}
	function setShip_to_countrycode($o) {
		$this->ship_to_countrycode = $o;
	}
	
	function getHowdidyouhearaboutus() {
		return $this->howdidyouhearaboutus;
	}
	function setHowdidyouhearaboutus($o) {
		$this->howdidyouhearaboutus = $o;
	}
	
	function getComments() {
		return $this->comments;
	}
	function setComments($o) {
		$this->comments = $o;
	}
	public function validateInput($req) {
		if (!(isset ($req['login_name'], $req['transaction_key'], $req['gateway_url'])))
		{
			logToFile("Dap_Payment:validateInput() - missing merchant's gateway login id or trans key or gateway url", LOG_DEBUG_DAP);
			return FALSE;
		}

		return TRUE;
	}
	
	public function validatePaypalInput($req) {
		if (!(isset ($req['paypal_api_login'], $req['paypal_api_password'], $req['paypal_api_signature'], $req['paypal_api_endpoint'])))
		{
			logToFile("Dap_Payment:validateInput() - missing merchant's paypal api login id or paypal api password or api signature or api_endpoint", LOG_DEBUG_DAP);
			return FALSE;
		}

		return TRUE;
	} 
	
	public function updateCustomFields($req, $userId) {
		//update exp, $this->getExp_date()
		
		$expdate=$this->getExp_date();
		logToFile("Dap_Payment.class.php: updateCustomFields(): expdate=".$expdate, LOG_DEBUG_DAP);
		if($expdate!="") {
			$customFld = Dap_CustomFields::loadCustomfieldsByName("cart");
			logToFile("Dap_Payment.class.php: updateCustomFields(): called loadCustomfieldsByName=cart", LOG_DEBUG_DAP);
			if ($customFld) {
				$id = $customFld->getId();
				logToFile("Dap_Payment.class.php: updateCustomFields(): id=" . $id, LOG_DEBUG_DAP);
				
				$usercustom = new Dap_UserCustomFields();
			
				$usercustom->setUser_id($userId);
				$usercustom->setCustom_value($expdate);
				$usercustom->setCustom_id($id);
				
				$cf = Dap_UserCustomFields::loadUserCustomFieldsByCustomFieldId($id, $userId );
				if(isset($cf)) {
					$updateExp=true;
					foreach ($cf as $val) {
						$value= $val['custom_value'];
						$exp=substr($value,0,6);
						logToFile("Dap_Payment.class.php: updateCustomFields(): current exp date=".$exp, LOG_DEBUG_DAP);
						logToFile("Dap_Payment.class.php: updateCustomFields(): new exp date=".$expdate, LOG_DEBUG_DAP);
						
						if($exp!="")
							$updateExp=false;
						if(strstr($exp,$expdate)==false) {
						  $updateExp=true;
						  //logToFile("Dap_Payment.class.php: updateCustomFields(): new exp date does not match current, so update the field=".$expdate, LOG_DEBUG_DAP);
						}
					}
				}
				
				if($updateExp) {
					if ($cf) {
						//logToFile("Dap_Payment.class.php: updateCustomFields(): call update to update value=" . $expdate, LOG_DEBUG_DAP);
						//$expdate=$expdate.":".$value;
//						logToFile("Dap_Payment.class.php: updateCustomFields(): call update to update new value=" . $expdate, LOG_DEBUG_DAP);
						$usercustom->update();
					}
					else {
						//logToFile("Dap_Payment.class.php: updateCustomFields(): call create to add custom value=" . $expdate, LOG_DEBUG_DAP);
						$usercustom->create();
					}
				}
			}
		}
		
		if ( isset($req['howdidyouhearaboutus']) && ($req['howdidyouhearaboutus'] != "") ) {
			$customFld = Dap_CustomFields::loadCustomfieldsByName("howdidyouhearaboutus");
			logToFile("Dap_Payment.class.php: updateCustomFields(): called loadCustomfieldsByName", LOG_DEBUG_DAP);
			if ($customFld) {
				$id = $customFld->getId();
				logToFile("Dap_Payment.class.php: updateCustomFields(): id=" . $id, LOG_DEBUG_DAP);
				
				$usercustom = new Dap_UserCustomFields();
			
				$usercustom->setUser_id($userId);
				$usercustom->setCustom_value($req['howdidyouhearaboutus']);
				$usercustom->setCustom_id($id);
				
				$cf = Dap_UserCustomFields::loadUserCustomFieldsByCustomFieldId($id, $userId );
				if ($cf) {
					logToFile("Dap_Payment.class.php: updateCustomFields(): call update to update value=" . $req['howdidyouhearaboutus'], LOG_DEBUG_DAP);
					$usercustom->update();
				}
				else {
					logToFile("Dap_Payment.class.php: updateCustomFields(): call create to add custom value=" . $req['howdidyouhearaboutus'], LOG_DEBUG_DAP);
					$usercustom->create();
				}
			}
		}
		if ( isset($req['comments']) && ($req['comments'] != "") ) {
			$customFld = Dap_CustomFields::loadCustomfieldsByName("comments");
			logToFile("Dap_Payment.class.php: updateCustomFields(): called comments", LOG_DEBUG_DAP);
			if ($customFld) {
				$id = $customFld->getId();
				logToFile("Dap_Payment.class.php: updateCustomFields(): id=" . $id, LOG_DEBUG_DAP);
				
				$usercustom = new Dap_UserCustomFields();
			
				$usercustom->setUser_id($userId);
				$usercustom->setCustom_value($req['comments']);
				$usercustom->setCustom_id($id);
				
				$cf = Dap_UserCustomFields::loadUserCustomFieldsByCustomFieldId($id, $userId);
				if ($cf) {
					logToFile("Dap_Payment.class.php: updateCustomFields(): call update to update value=" . $req['comments'], LOG_DEBUG_DAP);
					$usercustom->update();
				}
				else {
					logToFile("Dap_Payment.class.php: updateCustomFields(): call create to add custom value=" . $req['comments'], LOG_DEBUG_DAP);
					$usercustom->create();
				}
			}
		}
	
	
	
		// check if custom field present
		foreach($req as $key=>$value)
		{
			//logToFile("Dap_Payment.class.php: updateCustomFields():  key=" . $key . " value=" . $value, LOG_DEBUG_DAP);		
				
			if (strstr($key, "custom_")) {	
				if ($keyval = substr($key, 7)) {
						
					$customFld = Dap_CustomFields::loadCustomfieldsByName($keyval);
					logToFile("Dap_Payment.class.php: updateCustomFields(): loadCustomfieldsByName(): keyval=" . $keyval, LOG_DEBUG_DAP);		
					
					if ($customFld) {
						$id = $customFld->getId();
						logToFile("Dap_Payment.class.php: updateCustomFields(): customFld Id = " . $id, LOG_DEBUG_DAP);		
						
						$usercustom = new Dap_UserCustomFields();
						
						$usercustom->setUser_id($userId);
						$usercustom->setCustom_id($id);
						$usercustom->setCustom_value($value);
						
						$cf = Dap_UserCustomFields::loadUserCustomFieldsByCustomFieldId($id, $userId);
						if ($cf) {
							logToFile("Dap_Payment.class.php: updateCustomFields(): call update() to update value=" . $value, LOG_DEBUG_DAP);
							$usercustom->update();
						}
						else {
							logToFile("Dap_Payment.class.php: updateCustomFields(): call create() to add custom value=" . $nv[1], LOG_DEBUG_DAP);
							$usercustom->create();
						}
					}
				}
			}
		}
		
		
		
		return true;
	}
	
	public function validateRequiredFields($req) 
	{
		if (!isset($req['payment_err_page']) || $req['payment_err_page'] == '') {
			$req['payment_err_page'] = SITE_URL_DAP."/dap/paymentError.php";
		}
		
		if (!isset($req['payment_gateway']) || $req['payment_gateway'] == '') {
			$_SESSION['err_text']="Sorry, missing gateway info";
			logToFile("Dap_Payment:validateRequiredFields(). Missing payment_gateway in $_POST", LOG_DEBUG_DAP);
			return FALSE;
		}
	
		if (!isset($req['btntype']) || ($req['btntype'] == "") || ($req['btntype'] == "buynow")) {
			logToFile("Dap_Payment:validateRequiredFields(). btn type= " . $req['btntype'], LOG_DEBUG_DAP);
			
			if (!isset($req['item_name']) || $req['item_name'] == '') {
				$_SESSION['err_text']="Sorry, missing product name info";
				logToFile("Dap_Payment:validateRequiredFields(). Missing item_name in $_POST", LOG_DEBUG_DAP);
				return FALSE;
			}
			
			if (!isset($req['description']) || $req['description'] == '') {
				$_SESSION['err_text']="Sorry, missing description";
				logToFile("Dap_Payment:validateRequiredFields(). Missing description in $_POST", LOG_DEBUG_DAP);
				return FALSE;
			}
			
			if (!isset($req['amount']) || $req['amount'] == '') {
				$_SESSION['err_text']="Sorry, missing price info";
				logToFile("Dap_Payment:validateRequiredFields(). Missing amount in $_POST", LOG_DEBUG_DAP);
				return FALSE;
			}
			
			if (!isset($req['is_recurring']) || $req['is_recurring'] == '') {
				$_SESSION['err_text']="Sorry, missing recurring confirmation info";
				logToFile("Dap_Payment:validateRequiredFields(). Missing is_recurring in $_POST", LOG_DEBUG_DAP);
				return FALSE;
			}
			
			if ($req['is_recurring'] == "Y" && ($req['recurring_cycle_1'] == '' || $req['recurring_cycle_3'] == '')) {
				$_SESSION['err_text']="Sorry, missing recurring cycle info";
				logToFile("Dap_Payment:validateRequiredFields(). Missing recurring_cycle 1 and/or 3 in $_POST", LOG_DEBUG_DAP);
				return FALSE;
			}
			
			if (!isset($req['total_occurrences']) || $req['total_occurrences'] == '') {
				$_SESSION['err_text']="Sorry, missing total_occurrences info";
				logToFile("Dap_Payment:validateRequiredFields(). Missing item_name in $_POST", LOG_DEBUG_DAP);
				return FALSE;
			}
		}
		
		if (!isset($req['payment_succ_page']) || $req['payment_succ_page'] == '') {
			$_SESSION['err_text']="Sorry, missing success page info";
			logToFile("Dap_Payment:validateRequiredFields(). Missing payment_succ_page in $_POST", LOG_DEBUG_DAP);
			return FALSE;
		}
		return TRUE;
	}
	
	public function setSessionParams($req) 
	{
		$_SESSION['payment_gateway']=$req['payment_gateway'];
		$_SESSION['product_id']=$req['product_id'];
		$_SESSION['redirect']=$req['redirect'];
		
		//if (isset($req['upgradeFrom']))
			//$_SESSION['upgradeFrom']=trim($req['upgradeFrom']);
			
	//	if (isset($req['prorated']))
		//	$_SESSION['prorated']=trim($req['prorated']);
			
		if (isset($req['item_name']))
			$_SESSION['item_name']=trim($req['item_name']);
			
		if (isset($req['description']))
			$_SESSION['description']=trim($req['description']);
			
		if (isset($req['cmcc_acctnum']))
			$_SESSION['cmcc_acctnum']=$req['cmcc_acctnum'];
		
		if (isset($req['payment_succ_page']))
			$_SESSION['payment_succ_page']=trim($req['payment_succ_page']);	
		if (isset($req['payment_err_page']))
			$_SESSION['payment_err_page']=trim($req['payment_err_page']);
		
		if (isset($req['trial_amount']))
			$_SESSION['trial_amount']=$req['trial_amount'];
			
		if (isset($req['amount']))
			$_SESSION['amount']=$req['amount'];
		
		logToFile("Dap_Payment:setSessionParams(). payment_succ_page: " . $req['payment_succ_page'], LOG_DEBUG_DAP);
		
		if (isset($req['is_recurring']))
			$_SESSION['is_recurring']=$req['is_recurring'];
			
		if (isset($req['recurring_cycle_1']))
			$_SESSION['recurring_cycle_1']=$req['recurring_cycle_1'];
			
		if (isset($req['recurring_cycle_2']))
			$_SESSION['recurring_cycle_2']=$req['recurring_cycle_2'];
			
		if (isset($req['recurring_cycle_3']))
			$_SESSION['recurring_cycle_3']=$req['recurring_cycle_3'];
		
		if (isset($req['total_occurrences']))
			$_SESSION['total_occurrences']=$req['total_occurrences'];
			
	}

	public function create_authnet_subscription($req)  // AIM
	{
		$hosted_cmcc = "N";
		logToFile("SESSION['couponCode']=" .  $_SESSION['couponCode'], LOG_DEBUG_DAP);
		
		$payment_url = $req['err_redirect'];
	
		if ($payment_url == "") {
			$payment_url = SITE_URL_DAP . "/dap/buy.php";
		}
	
		$payment_url= str_replace ( "http:", "https:", $payment_url );
			
		if (isset($req['cmcc_acctnum']) && ($req['cmcc_acctnum'] != '')) {
			$payment_url = SITE_URL_DAP . "/cmcc/buy.php";
			$cmcc_acctnum = $req['cmcc_acctnum'];
			
			$user = Dap_User::loadUserByCMCCAcctNum($cmcc_acctnum);
			if (!isset($user)) {
				logToFile("Dap_Payment:create_authnet_subscription(). CMCC account number not found for admin: " . $user->getEmail(), LOG_DEBUG_DAP);
				header("Location: ". SITE_URL_DAP . $req['payment_err_page'] . "?response_msg=CMCC account number not defined");
				return;
			}
			
			$_SESSION['cmcc_acctnum'] = $user->getAcct_num();
			logToFile("Dap_Payment:create_authnet_subscription(). CMCC authnet payment", LOG_DEBUG_DAP);
			
			$hosted_cmcc = "Y";
			$req['login_name']	= $user->getApi_login_id();
			$req['transaction_key'] = $user->getTrans_key();
			if (!isset($req['login_name']) || !isset($req['transaction_key']) || $req['transaction_key'] == '' || $req['login_name'] == '') {
				header("Location: ". SITE_URL_DAP . $req['payment_err_page'] . "?response_msg=missing site admin authnet info in  database");
				return;
			}
		}
		else {
		//	logToFile("Dap_Payment:create_authnet_subscription(). Self-hosted authnet payment", LOG_DEBUG_DAP);
			$req['login_name']	= trim(Dap_Config::get('GATEWAY_API_LOGIN'));
			$req['transaction_key'] = trim(Dap_Config::get('GATEWAY_TRANS_KEY'));
			$req['gateway_url'] = trim(Dap_Config::get('GATEWAY_URL'));
		}
		
		if (!$this->validateInput($req)) {
			header("Location: ". SITE_URL_DAP . $req['payment_err_page'] . "?response_msg=missing request params");
			return;
		}

		$invoice = isset($req['invoice']) ? $req['invoice'] : date(YmdHis);
		
		if(isset($req['btntype']) && ($req['btntype'] != "")) 
		  $_SESSION['btntype'] = $req['btntype'];	
		  
		$post_values = array();

		//Merchant info
		$post_values['x_login'] = $req['login_name'];
		$post_values['x_tran_key'] = $req['transaction_key'];
		$post_values['x_method'] = "CC";

		//Transaction Information
		$post_values['x_version'] ="3.1";
		$post_values['x_type'] = "AUTH_CAPTURE";
		$post_values['x_delim_data'] = "TRUE";
		$post_values['x_delim_char'] = "|";

		// Set x_recurring_billing to FALSE even if there is a subsequent recurring because setting to TRUE results in no validation for CC exp date and card code
		$post_values['x_recurring_billing'] = "FALSE";
		
		if($this->getCard_num())
			$cardnum = str_replace(' ', '', $this->getCard_num());	
		$post_values['x_card_num'] = $cardnum;
		$post_values['x_exp_date'] = $this->getExp_date();
		$post_values['x_card_code'] = $this->getCard_code();
		$post_values['x_test_request'] = $req['testmode'];
		$post_values['x_relay_response'] = "FALSE";

		//customer info
		$post_values['x_cust_id'] = $invoice;
		
		//Order Information
		$post_values['x_invoice_num'] = $invoice;
		$post_values['x_description'] = $req['item_name'];;

		//Itemized Order Information
		$delim = "<|>";
		$quantity = "1";
		
		if(!isset($req['product_id']))
			$req['product_id'] = "1";
			
		$post_values['AMT'] = 0;
		$recur_count=0;
		$purchase_order = array();
		$total_amount=0;
      	$amount = 0;
		
		logToFile("Dap_Payment:create_authnet_subscription().btntype=" . $req['btntype'], LOG_DEBUG_DAP);
		$prorated="";
		
		if(isset($req['upgradeFrom'])) {
			$upgradeFrom=$req['upgradeFrom'];
			if(isset($req['upgradeFrom'])) {
				$prorated=$req['prorated'];
			}
			logToFile("Dap_Payment:create_authnet_subscription().upgradeFrom=" . $req['upgradeFrom'], LOG_DEBUG_DAP);
			logToFile("Dap_Payment:create_authnet_subscription().prorated=" . $req['prorated'], LOG_DEBUG_DAP);
		}
		
		$addtocart = "Y";
		if (!isset($req['btntype']) || ($req['btntype'] == "") || ($req['btntype'] == "buynow")) {
			$addtocart = "N";
		}
		
		if ($addtocart == "N")  {
			$num_cart_items = 0;	
			$this->emptyCart();
		}
		else {
			$num_cart_items = $_SESSION['num_cart'];
			logToFile("Dap_Payment:create_authnet_subscription().addtocart is Y,  num_cart_items=" . $num_cart_items, LOG_DEBUG_DAP);
		}
		
		if (!$num_cart_items || $num_cart_items == "") {
			$num_cart_items = 0;
		}
		
		logToFile("Dap_Payment:create_authnet_subscription().num_cart_items=" . $num_cart_items, LOG_DEBUG_DAP);
			
		$post_values['x_line_item'] = "";
		
		for ($i=0;$i<$num_cart_items;$i++) {
			$is_recur = $_SESSION['product_details'][$i]['L_ISRECUR'.$i];
			
			//if ($is_recur != "Y")
			//	continue;
		/*
		x_line_item=item1<|>golf balls<|><|>2<|>18.95<|>Y&x_line_item=item2<|>golf bag<|>Wilson golf carry bag, red<|>1<|>39.99<|>Y&
x_line_item=item3<|>book<|>Golf for Dummies<|>1<|>21.99<|>Y&

*/

// 5<|>Paid Product 1<|>Paid Product 1<|>1<|>0.01<|>N&x_line_item=5<|>Paid Product 1<|>Paid Product 1<|>1<|>0.01<|>N&

			$item_id = $_SESSION['product_details'][$i]['L_NUMBER'.$i];
			$item_name = $_SESSION['product_details'][$i]['L_NAME'.$i];
			$item_desc = $_SESSION['product_details'][$i]['L_DESC'.$i];
			$item_qty = $_SESSION['product_details'][$i]['L_QTY'.$i];
			
			$amount= $_SESSION['product_details'][$i]['L_AMT'.$i];
			
			$total_amount = $total_amount + $amount;
			//Itemized Order Information
			if ($i < ($num_cart_items - 1)) {
				
				$val = urlencode($item_id) . $delim . urlencode($item_name) . $delim . urlencode($item_name) . $delim . urlencode($item_qty) . $delim . urlencode($amount) . $delim . urlencode("N");
				$post_values['x_line_item'] = $post_values['x_line_item'] . $val . "&x_line_item=";
			}
			else {
					$val = urlencode($item_id) . $delim . urlencode($item_name) . $delim . urlencode($item_name) . $delim . urlencode($item_qty) . $delim . urlencode($amount) . $delim . urlencode("N");
				$post_values['x_line_item'] = $post_values['x_line_item'] . $val;
			}
			
			logToFile("Dap_Payment:create_authnet_subscription(). recurring item_name" . $i . "=" . $_SESSION['product_details'][$i]['L_NAME'.$i], LOG_DEBUG_DAP);
			
		}
		
		logToFile("Dap_Payment:create_authnet_subscription(). x_item_name=" . $post_values['x_line_item'], LOG_DEBUG_DAP);
		
		
		if ($num_cart_items > 0) {
			if ($_SESSION['new_amount'] != "") {
				$post_values['x_amount'] = $_SESSION['new_amount'];
				logToFile("Dap_Payment:create_authnet_subscription().session new amount=" . $_SESSION['new_amount'], LOG_DEBUG_DAP);
			}
			else {
				logToFile("Dap_Payment:create_authnet_subscription().amount=" . $amount, LOG_DEBUG_DAP);
				$post_values['x_amount'] = $amount;	
				$_SESSION['new_amount'] = $post_values['x_amount'] ;
			}
		}
		else {
			// if trial period, set amount to the trial amount
			logToFile("Dap_Payment:create_authnet_subscription(). num_cart_items=" . $num_cart_items, LOG_DEBUG_DAP);
			
			if (strtoupper($req['is_recurring']) == "Y") {
				if ( isset($req['trial_amount']) && ($req['trial_amount'] != "0.00") && ($req['trial_amount'] != "0.0") && ($req['trial_amount'] != "0") ) 	{
					$amount = $req['trial_amount'];
				}
				else { 
					$amount = $req['amount'];
				}
				$post_values['x_amount'] = $amount;
				$_SESSION['new_amount'] = $post_values['x_amount'] ;
			}
			else {
				$post_values['x_amount'] = $req['amount'];
				$_SESSION['new_amount'] = $post_values['x_amount'] ;
				$amount = $req['amount'];
			}
		}
		
		logToFile("Dap_Payment:create_authnet_subscription(). Authnet AIM line_item: " . $post_values['x_line_item'], LOG_DEBUG_DAP);
		
		if (($_SESSION['new_amount'] != "") && ($_SESSION['couponCode'] != "")) {
		  $coupon = Dap_Coupon::loadCouponByCode($_SESSION['couponCode']);
		  if (isset($coupon)) {
			$post_values['coupon_id'] = $coupon->getId();
			logToFile("Dap_payment.class.php: POST VALUES: coupon found = " . $post_values['coupon_id']);
		  }
		}
		
		
		$billing_first_name= $this->getBillingFirst_name();
		//Billing Information
		if( isset($billing_first_name)  && ($this->getBillingFirst_name() != "")) {
			$billing_first_name	= $this->getBillingFirst_name();
		} 
		else
			$billing_first_name	= $this->getFirst_name();
		
		$billing_last_name= $this->getBillingLast_name();
		//Billing Information
		if( isset($billing_last_name)  && ($this->getBillingLast_name() != "")) {
			$billing_last_name	= $this->getBillingLast_name();
		} 
		else 
			$billing_last_name	= $this->getLast_name();
		
		$post_values['x_first_name'] = $billing_first_name;
		$post_values['x_last_name'] = $billing_last_name;
		
		$post_values['x_address'] = $this->getAddress1();
		$post_values['x_city'] = $this->getCity();
		$post_values['x_state'] = $this->getState();
		$post_values['x_zip'] = $this->getZip();
		$post_values['x_country'] = $this->getCountry();			
		$post_values['x_phone'] = $this->getPhone();	
		$post_values['x_email'] = $this->getEmail();	

		//Shipping Information  
		$post_values['x_ship_to_first_name'] = $this->getShip_to_first_name();
		$post_values['x_ship_to_last_name'] = $this->getShip_to_last_name();
		$post_values['x_ship_to_address'] = $this->getShip_to_address1();
		$post_values['x_ship_to_city'] = $this->getShip_to_city();
		$post_values['x_ship_to_state'] = $this->getShip_to_state();
		$post_values['x_ship_to_zip'] = $this->getShip_to_zip();
		$post_values['x_ship_to_country'] = $this->getShip_to_country();

		//custom
		$post_values['custom'] = SITE_URL_DAP;
		
		if($upgradeFrom!="") {
			logToFile("Dap_payment.class.php: set upgradeFrom before calling authnet= " . $upgradeFrom);
			$post_values['upgradeFrom'] = $upgradeFrom;	
			$post_values['prorated'] = $prorated;	
			
			if((isset($post_values['custom'])) && ($post_values['custom']!="")) {
			  $post_values['custom']=$post_values['custom'] . "|UPGF:" . $upgradeFrom;
			  logToFile("Dap_payment.class.php: set custom= " . $post_values['custom']);
			}
			else {
			  $post_values['custom']="UPGF:" . $upgradeFrom;
			  logToFile("Dap_payment.class.php: set custom = " . $post_values['custom']);
			}
		}
		
		
		// Convert to proper format for an http post
		$post_string = "";

		$userExists = "N";
		$user = Dap_User::loadUserByEmail($this->getEmail());
		$accessEndDateOfUpgTo="";
		if(isset($user)) {
			$userExists = "Y";
			if($upgradeFrom!="") {
			  //$upgFromProduct = Dap_Product::loadProduct($upgradeFrom); //loads product details from db
			  logToFile("Dap_payment.class.php:upgFromProduct - loaded product = " . $upgradeFrom);
			  $userProduct = Dap_UsersProducts::load($user->getId(), $upgradeFrom);
			  
			  if(isset($userProduct)) {
				 logToFile("Dap_payment.class.php:loaded userproduct");
			  	 $accessEndDateOfUpgTo = $userProduct->getAccess_end_date();
				 logToFile("Dap_payment.class.php:accessEndDateOfUpgTo set to" . $accessEndDateOfUpgTo);
			  }
			}
		}
		
		foreach( $post_values as $key => $value ) {
		  if ($key != "x_line_item")
			$post_string .= "$key=" . urlencode( $value ) . "&"; 
			  
		  if (($key != "x_card_num") && ($key != "x_exp_date") && ($key != "x_card_code"))
			logToFile("Dap_Payment:create_authnet_subscription(). Value= " . $value, LOG_DEBUG_DAP);
		}
		$post_string = rtrim( $post_string, "& " );

		//logToFile("Dap_Payment:create_authnet_subscription(). Authnet AIM request: " . $post_string, LOG_DEBUG_DAP);
		
		// Use CURL to establish a connection, submit the post, and record the response.
		//$post_url = "https://test.authorize.net/gateway/transact.dll";
		$post_url=$req['gateway_url'];
		$request = curl_init($post_url); // initiate curl object
		curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
		curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data
		//curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
		$post_response = curl_exec($request);
		curl_close ($request); // close curl object
		
		$_SESSION['product_id']=$req['product_id'];
		$_SESSION['payment_gateway']=$req['payment_gateway'];
				
		//if the connection and send worked $post_response holds the return from Authorize.net
		if (isset ($post_response)) // transaction approved
		{
		// This line takes the response and breaks it into an array using the specified delimiting character
			$response_array = explode($post_values["x_delim_char"], $post_response);
			// The results are output to the screen in the form of an html numbered list.
			foreach ($response_array as $value)
				logToFile("Dap_Payment:create_authnet_subscription(). Authnet AIM response: " . $value, LOG_DEBUG_DAP);

			$aresult = array();
			$aresult['response_code'] = $response_array[0];
			$aresult['response_subcode'] = $response_array[1];
			$aresult['response_reason_code'] = $response_array[2];
			$aresult['text'] = $response_array[3];
			
				
			logToFile("Dap_Payment:create_authnet_subscription(). response array: " . $response_array[0], LOG_DEBUG_DAP);
			if ( (stristr($response_array[0], '1') == TRUE) ){ //approved payment
				logToFile("Dap_Payment:create_authnet_subscription(). Approved payment for user: " . $this->getEmail(), LOG_DEBUG_DAP);
				/*if (($req['testmode'] == "Y") && ($response_array[6] == "0"))
					$aresult['txn_id'] =  $invoice . ":0"; 
				else
*/				
				$aresult['txn_id'] =  $response_array[6] . ":0";  
				$_SESSION["GA_TXN_ID"]=$response_array[6];
				$aresult['email'] = $this->getEmail();
				
				logToFile("Dap_Payment:create_authnet_subscription(): AIM transaction ID=" . $aresult['txn_id'], LOG_DEBUG_DAP);
			 	logToFile("Dap_Payment:create_authnet_subscription(): AIM 37=" . $response_array[37], LOG_DEBUG_DAP);
				logToFile("Dap_Payment:create_authnet_subscription(): AIM 38=" . $response_array[38], LOG_DEBUG_DAP);
				logToFile("Dap_Payment:create_authnet_subscription(): AIM 39=" . $response_array[39], LOG_DEBUG_DAP);
				logToFile("Dap_Payment:create_authnet_subscription(): AIM 40=" . $response_array[40], LOG_DEBUG_DAP);
				logToFile("Dap_Payment:create_authnet_subscription(): AIM 41=" . $response_array[41], LOG_DEBUG_DAP);
				logToFile("Dap_Payment:create_authnet_subscription(): AIM 42=" . $response_array[42], LOG_DEBUG_DAP);
				logToFile("Dap_Payment:create_authnet_subscription(): AIM 42=" . $response_array[43], LOG_DEBUG_DAP);
				
							
				$aresult['invoice'] = $invoice;
				$aresult['payer_email'] = $this->getEmail();
				$aresult['phone'] = $this->getPhone();
				$aresult['fax'] = $this->getFax();
				
				$aresult['payment_gateway'] = $req['payment_gateway'];
				
				// set params
				if ($num_cart_items == 0) {
					$num_cart_items = 1;
					$buynow = true;
					$aresult['payment_num'] = "0"; //first payment via AIM
					$aresult['item_name'] = $req['item_name'];
					$aresult['description'] = $req['description'];
					$aresult['amount'] = $amount;
					logToFile("not cart=" .  $buynow, LOG_DEBUG_DAP);
				}
				
				$num_cart_item = $_SESSION['num_cart'];
				$amt = 0;
				for($i=0;$i<$num_cart_item;$i++) { 
					$amt = $amt +  ($_SESSION['product_details'][$i]['L_AMT' . $i] * $_SESSION['product_details'][$i]['L_QTY' . $i]) ;
				}
				
				
				$discount = 0;
				if (isset($_SESSION['new_amount']) && ($_SESSION['new_amount'] != $amt)) {
					logToFile("session new_amount=" .  $_SESSION['new_amount'], LOG_DEBUG_DAP);
					logToFile("num_cart_item=" .  $num_cart_item, LOG_DEBUG_DAP);
					logToFile("SESSION['couponCode']=" .  $_SESSION['couponCode'], LOG_DEBUG_DAP);
				// coupon applied
					if ($num_cart_item > 0) {
						$discount = $_SESSION['new_amount'] / $num_cart_item;
					}
					else if (($_SESSION['new_amount'] != "") && ($_SESSION['couponCode'] != "")) {
					  $coupon = Dap_Coupon::loadCouponByCode($_SESSION['couponCode']);
					  if (isset($coupon)) {
					    $aresult['coupon_id'] = $coupon->getId();
						if($_SESSION["recurring_discount"]) {
							$req["recurring_discount"]=$_SESSION["recurring_discount"];
							$_SESSION["recurring_discount"]="";
						}
						logToFile("Dap_payment.class.php: coupon found = " . $aresult['coupon_id']);
					  }
					}
				}
		
				
				for ($i=0;$i<$num_cart_items;$i++) {
					if ($buynow != true) {
								
						$aresult['txn_id'] = $aresult['txn_id'] . ":" . "$i";
						
						$aresult['payment_num'] = $i; 
						$req['is_recurring'] = $_SESSION['product_details'][$i]['L_ISRECUR'.$i];
						$item_id = $_SESSION['product_details'][$i]['L_NUMBER'.$i];
						$aresult['item_name']  = $_SESSION['product_details'][$i]['L_NAME'.$i];
						$aresult['description'] = $_SESSION['product_details'][$i]['L_DESC'.$i];
						$aresult['quantity'] = $_SESSION['product_details'][$i]['L_QTY'.$i];
						
						$aresult['amount']= ($_SESSION['product_details'][$i]['L_AMT'.$i]) * $aresult['quantity'];	

						logToFile("Dap_payment.class.php: new_amount = " . $_SESSION['new_amount'] . ", couponCode = " . $_SESSION['couponCode']);
						
						if (($_SESSION['new_amount'] != "") && ($_SESSION['couponCode'] != "")) {
							
							$coupon = Dap_Coupon::loadCouponByCode($_SESSION['couponCode']);
														
							if (isset($coupon)) {
								logToFile("Dap_payment.class.php: coupon found");
						
								$itemname = $_SESSION['product_details'][$i]['L_NAME' . $i];
								$product = Dap_Product::loadProductByName($itemname);
								$productCoupon =	Dap_ProductCoupon::findCouponIdAndProductId($product->getId(), $coupon->getId());
						        $aresult['coupon_id'] = $coupon->getId();
								logToFile("Dap_payment.class.php: coupon_id IS = " . $aresult['coupon_id']);
									
								if (isset($productCoupon)) {
									$discount_amount = $coupon->getDiscount_amt();
									
									logToFile("Dap_payment.class.php: orig amount = " . $_SESSION['product_details'][$i]['L_AMT' . $i]);
									
									$aresult['amount']= (($_SESSION['product_details'][$i]['L_AMT' . $i] -  $discount_amount) * $_SESSION['product_details'][$i]['L_QTY' . $i]) ;
								
									logToFile("Dap_payment.class.php: discounted amount = " . $aresult['amount']);
									
									logToFile("Dap_payment.class.php: itemname = " . $_SESSION['product_details'][$i]['L_NAME' . $i] . ", discount amount = " . $coupon->getDiscount_amt());
								}
							}
						}
										
						$req['is_recurring'] = $_SESSION['product_details'][$i]['L_ISRECUR'.$i]; 
						$req['recurring_cycle_1'] = $_SESSION['product_details'][$i]['L_RECUR1'.$i]; 
						$req['recurring_cycle_2'] = $_SESSION['product_details'][$i]['L_RECUR2'.$i]; 
						$req['recurring_cycle_3'] = $_SESSION['product_details'][$i]['L_RECUR3'.$i]; 
						$req['total_occurrences'] = $_SESSION['product_details'][$i]['L_TOTALOCCUR'.$i];
					
						logToFile("buynow=" .  $buynow, LOG_DEBUG_DAP);
					}
				
					unset($_SESSION['new_amount']);
					unset($_SESSION['couponCode']);
					logToFile("Dap_Payment:create_authnet_subscription:is recurring=".$req['is_recurring'], LOG_DEBUG_DAP);
					
					//record user->product in database
					if (!$this->processPaymentResponse ($aresult, "Dap_Payment"))
					{
					
						$subject="DAP transaction recording failed for the user: " . $aresult['email'];
						$body="Dap_Payment:processPaymentResponse(): " . $req['payment_gateway'] . " payment transaction successfully processed but DAP transaction recording failed for the user: " . $aresult['email'] . ", who bought the item: " . $aresult['item_name'] . ", for the amount: $" . $aresult['amount'] . ". But the Silent Post should arrive shortly that would provide DAP with another chance to create user->product relationship and record the transaction. If not, this transaction will have to be handled manually within DAP admin console";
						sendAdminEmail($subject, $body);
						logToFile($body, LOG_DEBUG_DAP);
					}
					else if (strtoupper($req['is_recurring']) == "Y")
					{
						if ($buynow != true) {
							$req['amount'] = $_SESSION['product_details'][$i]['L_RECURAMT'.$i]; 
							$req['recurring_amount'] = $_SESSION['product_details'][$i]['L_RECURAMT'.$i]; 
						}
						
						logToFile("Dap_Payment:create_authnet_subscription:calling create_authnet_recurring_subscription", LOG_DEBUG_DAP);
						// show success page for the successful initial transaction via AIM but send admin email for ARB (recurring) failure
						if (!$this->create_authnet_recurring_subscription ($req, $aresult))
						{
							$subject="Recurring subscription failed for the user: " . $aresult['email'];
							
							$body="Dap_Payment:create_authnet_recurring_subscription():failed to process the recurring transaction for user: " . $aresult['email'] . ", who bought the item: " . $aresult['item_name'] . ", for the amount: $" . $aresult['amount'] . ". The recurring payment transaction will have to be handled manually within authorize.net.";
							
							sendAdminEmail($subject, $body);
							logToFile($body, LOG_DEBUG_DAP);
						}
					}	
				} // end for 
				
				
				$user = Dap_User::loadUserByEmail($aresult['email']);
				
				if(isset($user)) {
					// Update comments and howdidyouhearaboutus
					$expdate = $this->getExp_date();
					logToFile("Dap_Payment.class.php: calling updateCustomFields(): expdate=".$expdate, LOG_DEBUG_DAP);
					$ret = $this->updateCustomFields ($req, $user->getId() );
					logToFile("Dap_Payment.class.php:  create_authnet_subscription(): userExists=" . $userExists, LOG_DEBUG_DAP);
					
					// EMPTY CART
				
					logToFile("Dap_Payment.class.php:  create_authnet_subscription(): Empty cart items", LOG_DEBUG_DAP);
					$this->emptyCart();
					
									
					if( (isset($req["is_last_upsell"])) && ($req["is_last_upsell"]=="YES") )  {
						logToFile("Dap_Payment.class.php:  create_authnet_subscription(): IS LAST UPSELL: ". $req["is_last_upsell"], LOG_DEBUG_DAP);
						$this->emptyCC();
					}
				
					$product = Dap_Product::loadProductByName($req['item_name']);
					if(isset($product)) {
					  $productId = $product->getId();
					  if ( $accessEndDateOfUpgTo != "") {
						  
						  logToFile("Dap_Payment.class.php:  create_authnet_subscription: reset access end date (upgrade flow) create_authnet_subscription(): upgradeTo productId=" . $productId, LOG_DEBUG_DAP);
						  if($productId != "") {
							$userProduct = Dap_UsersProducts::load($user->getId(), $productId);
							logToFile("Dap_Payment.class.php:  create_authnet_subscription(): RESET ACCESSENDATE OF UPGRADETO PRODUCT TO =" . $accessEndDateOfUpgTo, LOG_DEBUG_DAP);			
							$userProduct->setAccess_end_date($accessEndDateOfUpgTo);
							$userProduct->update();
							
							// CANCEL UPGRADEFROM SUBSCRIPTION IN AUTHNET
							if($upgradeFrom!="") {
							  logToFile("Dap_Payment.class.php: Call cancelAuthnetSubscriptionUpgrade to cancel : " . $upgradeFrom, LOG_DEBUG_DAP);
							  $req["x_email"] = $aresult['email'];
							  $ret = cancelAuthnetSubscriptionUpgrade($upgradeFrom,$req);
							  logToFile("Dap_Payment.class.php: cancelAuthnetSubscriptionUpgrade() returned : " . $ret, LOG_DEBUG_DAP);
							}
							
						  } 
						  else {
							  logToFile("Dap_Payment.class.php: create_authnet_subscription() upgradeto productId missing, cant reset access end date, id=" . $productId, LOG_DEBUG_DAP);
						  }
					  }
					}
					
					if ( ($userExists == "N") || ((isset($_SESSION["userexistsbutallow"])) && ($_SESSION["userexistsbutallow"] == "Y")) ) {
						logToFile("Dap_Payment:create_authnet_subscription(): redirecting to authenticate.php to auto login user". $record_id);									
						$_SESSION["userexistsbutallow"]="";
						unset($_SESSION["userexistsbutallow"]);
						$user->setStatus("A");
						$user->update();
			
						logToFile("Dap_Payment:create_authnet_subscription(): pass=".$user->getPassword() );	
						
						$_SESSION["GA_PURCHASE_EMAIL"]=$aresult["email"];
						$finallanding="/dap/redirectga.php?redirect=".$req['payment_succ_page'];
						
						logToFile("Dap_Payment:create_authnet_subscription(): gatracking val=". $req["gatracking"] );	
						
						if( (isset($req["gatracking"])) && ($req["gatracking"] == "Y")) {
							//header("Location: /dap/redirectga.php?redirect=".$req['payment_succ_page']);
						  logToFile("Dap_Payment:create_authnet_subscription(): gatracking set, final landing=".$finallanding );	
						//  logToFile("Dap_Payment:create_authnet_subscription(): gatracking set, final landing= /dap/authenticate.php?email=" . $aresult['email'] . "&password=" . $user->getPassword() . "&submitted=Y&request=".$finallanding);
						  
						  header("Location: /dap/authenticate.php?email=" . $aresult['email'] . "&password=" . $user->getPassword() . "&submitted=Y&request=".$finallanding);
						}
						else	{
							logToFile("Dap_Payment:create_authnet_subscription(): NO gatracking set" );
							$password = $user->getPassword();
							$ret = authUser($aresult['email'], $password);
							if($ret==0) {
								logToFile("Dap_Payment:create_authnet_subscription(): logged in the user=" . $aresult['email'] . " successfully" );
								header("Location: ".$req['payment_succ_page']);
							}
							else {
								logToFile("Dap_Payment:create_authnet_subscription(): autologin failed" );
								header("Location: ".$req['payment_succ_page']);
							}
						}
						
						//exit;
					}
					else {
						$user->setStatus("A");
						$user->update();
						logToFile("Dap_Payment:updated user status after successful purchase");	
					}
					
				}
				else {
					// EMPTY CART
				
					logToFile("Dap_Payment.class.php:  create_authnet_subscription(): Empty cart items", LOG_DEBUG_DAP);
					$this->emptyCart();
					if( (isset($req["is_last_upsell"])) && ($req["is_last_upsell"]=="YES") )  {
						logToFile("Dap_Payment.class.php:  create_authnet_subscription(): IS LAST UPSELL: ". $req["is_last_upsell"], LOG_DEBUG_DAP);
						$this->emptyCC();
					}	
				}
				
				logToFile("Dap_Payment:create_authnet_subscription(): redirecting...". $req['payment_succ_page']);
				$_SESSION["GA_PURCHASE_EMAIL"]=$aresult["email"];
				
				if( (isset($req["gatracking"])) && ($req["gatracking"] == "Y")) 
					header("Location: /dap/redirectga.php?redirect=".$req['payment_succ_page']);
				else	
					header("Location: " . $req['payment_succ_page']);
				
				exit;
			}
			else if ( (stristr($response_array[0], '2') == TRUE) || (stristr($response_array[0], '3') == TRUE) || (stristr($response_array[0], '4') == TRUE)  ) { 
				// 2 = Declined, 3 = Error, 4 = Held for Review
				logToFile("Dap_Payment:create_authnet_subscription(). Error Code: " . $response_array[0] . ", Error Text: " . 					$aresult['text'], LOG_DEBUG_DAP); 
				
				sendAdminEmail("Authnet AIM payment failed for " . $this->getEmail(), "Dap_Payment:create_authnet_subscription(). Failed for user: " . $this->getEmail() . " for product = " . $req['item_name'] . " with Error Code: " . $response_array[0] . ", Error Text: " . $aresult['text']);
				$_SESSION['err_text']=$aresult['text'];
				if(strstr($payment_url,"?")) {
				  $payment_url = $payment_url . "&err_text=" . $aresult['text'];
				  header("Location: ".$payment_url);
				  exit;
				}
				else {
				  
				  header("Location: ".$payment_url."?err_text=".$_SESSION['err_text']);
				  exit;
				}
				
				return;
			}
			else {
				logToFile("Dap_Payment:create_authnet_subscription(). Sorry, your Authorize.net transaction did not go through. Please retry or contact the site admin", LOG_DEBUG_DAP); 
				
				$_SESSION['err_text']="Sorry, your Authorize.net transaction did not go through. Please retry or contact the site admin";
				header("Location: ".$payment_url."?err_text=".$_SESSION['err_text']);
			
				
				return;
			}
		}
		else
		{
			logToFile("Dap_Payment:create_authnet_subscription(). Failed to connect to authnet", LOG_DEBUG_DAP);
			$_SESSION['err_text']="Sorry, failed to connect to Authorize.net. Please retry or contact the site admin";
			
			header("Location: ".$payment_url."?err_text=".$_SESSION['err_text']);
			
			return;
		}
	}  // end function


// ===================
	public function create_authnet_recurring_subscription($req, $aresult)  // ARB
	{
		$gateway_recur_url = trim(Dap_Config::get('GATEWAY_RECUR_URL'));
		logToFile("Dap_Payment:create_authnet_recurring_subscription(): " . $gateway_recur_url, LOG_DEBUG_DAP);

		if(!$this->validateInput($req)) return FALSE;

		if(!($xmlpos = strpos ($gateway_recur_url, "xml"))) {
			logToFile("Dap_Payment:create_authnet_recurring_subscription(): Incorrect merchant url", LOG_DEBUG_DAP);
			header("Location: ". SITE_URL_DAP . $req['payment_err_page']);

			return FALSE;
		}

//$gateway_recur_url - https://api.authorize.net/xml/v1/request.api";
//$host = "api.authorize.net";
//$path = "/xml/v1/request.api";

		$path = substr($gateway_recur_url, $xmlpos - 1); 
		$host = substr($gateway_recur_url, 8, $xmlpos - 8 - 1 ); // skip http:// (7 char) and "/" before xml (1 char)
		logToFile("Dap_Payment:create_authnet_recurring_subscription() - path=" . $path . "host=" . $host ,LOG_DEBUG_DAP);

		//sequence number is randomly generated
		$sequence	= rand(1, 1000);
		//timestamp is generated
		$timestamp = time ();
		$login_name = $req['login_name'];
		$transaction_key = $req['transaction_key'];
		
		//logToFile("Dap_Payment:create_authnet_recurring_subscription() - login_name=" . $login_name . "transaction_key=" . $transaction_key,LOG_DEBUG_DAP);
		//The following lines generate the SIM fingerprint.  PHP versions 5.1.2 and
		//newer have the necessary hmac function built in.  For older versions, it
		//will try to use the mhash library.
		if( phpversion() >= '5.1.2' )
		{	
			$fingerprint = hash_hmac("md5", $login_name . "^" . $sequence . "^" . $timestamp . "^" . $amount . "^", $transaction_key); 
		}
		else 
		{ 
			$fingerprint = bin2hex(mhash(MHASH_MD5, $login_name . "^" . $sequence . "^" . $timestamp . "^" . $amount . "^", $transaction_key));									
		}

		$amount = isset($req['amount']) ? $req['amount'] : "0.01";
		$refId = $req['refId'];

		//subscription name... same as product name
		$item_name = $req['item_name'];
		
		
		$invoice = $aresult['invoice'] . "-0";

		//total occurrence should exclude the trial occurrence if any trial period
		$total_occurrences = isset($req['total_occurrences']) ? $req['total_occurrences']: "999";

		// if any trial period, it would be covered by initial subscription via AIM
		$trial_occurrences ="0";
		$trial_amount = "0.0";

		//frequency of billing occurrences
		$payment_length = $req['recurring_cycle_3'];
		$payment_unit = "days";

		// first recurring payment should occur at the end of first recurring period
		$start_date = date('Y-m-d', strtotime("+".$req['recurring_cycle_1']." ".$payment_unit));

		logToFile("Dap_Payment:create_authnet_recurring_subscription() - item_name=" . $item_name . ", transaction_key=" . $transaction_key,LOG_DEBUG_DAP);
		logToFile("Dap_Payment:create_authnet_recurring_subscription() - amount=" . $amount,LOG_DEBUG_DAP);
		logToFile("Dap_Payment:create_authnet_recurring_subscription() - total_occurrences=" . $total_occurrences,LOG_DEBUG_DAP);
		
		if($this->getCard_num())
			$cardnum = str_replace(' ', '', $this->getCard_num());	
		
		$billing_first_name= $this->getBillingFirst_name();
		//Billing Information
		if( isset($billing_first_name)  && ($this->getBillingFirst_name() != "")) {
			$billing_first_name	= $this->getBillingFirst_name();
		} 
		else
			$billing_first_name	= $this->getFirst_name();
		
		$billing_last_name= $this->getBillingLast_name();
		//Billing Information
		if( isset($billing_last_name)  && ($this->getBillingLast_name() != "")) {
			$billing_last_name	= $this->getBillingLast_name();
		} 
		else 
			$billing_last_name	= $this->getLast_name();
		
		//build xml to post
		$content =
		"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
		"<ARBCreateSubscriptionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
		"<merchantAuthentication>".
			"<name>" . $req['login_name'] . "</name>".
			"<transactionKey>" . $req['transaction_key'] . "</transactionKey>".
		"</merchantAuthentication>".
		"<refId>" . $refId . "</refId>".
		"<subscription>".
			"<name><![CDATA[" . $item_name . "]]></name>".
			"<paymentSchedule>".
				"<interval>".
				"<length>". $payment_length ."</length>".
				"<unit>". $payment_unit ."</unit>".
				"</interval>".
				"<startDate>" . $start_date . "</startDate>".
				"<totalOccurrences>". $total_occurrences . "</totalOccurrences>".
				"<trialOccurrences>". $trial_occurrences . "</trialOccurrences>".
			"</paymentSchedule>".
			"<amount>". $amount ."</amount>".
			"<trialAmount>" . $trial_amount . "</trialAmount>".
			"<payment>".
				"<creditCard>".
					"<cardNumber>" . $cardnum . "</cardNumber>".
					"<expirationDate>" . $this->getExp_date() . "</expirationDate>".
				"</creditCard>".
			"</payment>".
			"<order>".
				"<invoiceNumber>". $invoice . "</invoiceNumber>".
				"<description><![CDATA[" . $item_name . "]]></description>".
			"</order>".
			"<customer>".
				"<id>". $invoice . "</id>".
				"<email>". $this->getEmail() . "</email>".
				"<phoneNumber>" . $this->getPhone() . "</phoneNumber>".
				"<faxNumber>" . $this->getFax() . "</faxNumber>".
			"</customer>".
			"<billTo>".
				"<firstName>". $billing_first_name . "</firstName>".
				"<lastName>" . $billing_last_name . "</lastName>".
				"<address>" . $this->getAddress1() . "</address>".
				"<city>" . $this->getCity() . "</city>".
				"<state>" . $this->getState() . "</state>".
				"<zip>" . $this->getZip() . "</zip>".
				"<country>" . $this->getCountry() . "</country>".
			"</billTo>".
			"<shipTo>".
				"<firstName>". $this->getShip_to_first_name() . "</firstName>".
				"<lastName>" . $this->getShip_to_last_name() . "</lastName>".
				"<address>" . $this->getShip_to_address1() . "</address>".
				"<city>" . $this->getShip_to_city() . "</city>".
				"<state>" . $this->getShip_to_state() . "</state>".
				"<zip>" . $this->getShip_to_zip() . "</zip>".
				"<country>" . $this->getShip_to_country() . "</country>".
			"</shipTo>".
		"</subscription>".
		"</ARBCreateSubscriptionRequest>";

		//logToFile("Dap_Payment:XML content: " . $content, LOG_DEBUG_DAP);
		
		//send the xml via curl
		$response = send_request_via_curl($host,$path,$content);

	//if the connection and send worked $response holds the return from Authorize.net
		if ($response)
		{
			list ($ref_id, $result_code, $code, $text, $subscription_id) =parse_return($response);
			if (!strcasecmp ($result_code,"Ok")) { //SUCCESS
				logToFile("Dap_Payment:create_authnet_recurring_subscription(). Dap_Payment successfully processed by authorize.net,  Subs Id: " . $subscription_id , LOG_DEBUG_DAP);
				
				$emailFilter = "";
				$productIdFilter = "";
				$statusFilter = "";
				$transNumFilter = $aresult["txn_id"];
				
				// TODO: only pick up subscription transaction
				logToFile("Dap_Payment:create_authnet_recurring_subscription(). transNumFilter=".$transNumFilter,LOG_INFO_DAP);
				logToFile("Dap_Payment:create_authnet_recurring_subscription(). emailFilter=".$emailFilter,LOG_INFO_DAP);
				logToFile("Dap_Payment:create_authnet_recurring_subscription(). productIdFilter=".$productIdFilter,LOG_INFO_DAP);
				logToFile("Dap_Payment:create_authnet_recurring_subscription(). statusFilter=".$statusFilter,LOG_INFO_DAP);
				
				$TransactionsList = Dap_Transactions::loadTransactions($transNumFilter, $emailFilter, $productIdFilter, $statusFilter);
				$foundTransaction=false;
				$profile_id="";
				foreach ($TransactionsList as $transaction) {
				  $foundTransaction=true;
				  parse_str($transaction->getTrans_blob(), $list);
				  $blob=$transaction->getTrans_blob();
				  $blob.="&sub_id=$subscription_id";
				  logToFile("Dap_Payment:create_authnet_recurring_subscription(). new blob=".$blob, LOG_DEBUG_DAP);
				  
				  $transaction->setTrans_blob($blob);
				  $transaction->updateBlob();
				}
				
				
				return TRUE;
			}
			else {
				logToFile("Dap_Payment:create_authnet_recurring_subscription(). Error Code: " . $result_code . " Reason Code: " . $code . " text: " . $text . " Subs Id: " . $subscription_id , LOG_DEBUG_DAP);
				
				sendAdminEmail ("Authnet recurring subscription", "Authnet recurring subscription failed for " . $aresult['email'] . " with Error Code: " . $result_code . " Reason Code: " . $code . " text: " . $text);
				
				return FALSE;
			}
		}	
		else
		{
			logToFile("Dap_Payment:create_authnet_recurring_subscription(). Failed to connect to authnet", LOG_DEBUG_DAP);
			
			sendAdminEmail("Authnet connection could not be established for " . $aresult['email'], "Dap_Payment:create_authnet_subscription(). Paypal connection for recurring payment could not be established for: " . $aresult['email'] . " for product = " . $aresult['item_name']);
			
			return FALSE;
		}

	}  // end function

	// This function called by both initial payment (AIM) as well as silent post (ARB - recurring)
	public function processAuthnetResponse($inp, $source) {
		logToFile("Dap_Payment:processAuthnetResponse(): " . $source . " response: ", $inp['email']); 

		$inp['txn_type']= "subscr_payment";

		if(!isset($inp['mc_gross'])) { 
			$inp['mc_gross'] = '0.00';
		}
		if(isset($inp['mc_amount3'])) {   
			$inp['mc_gross'] = $inp['mc_amount3'];
		}
		if(isset($inp['mc_amount2'])) {
			$inp['mc_gross'] = $inp['mc_amount2'];
		}
		if(isset($inp['mc_amount1'])) {  
			$inp['mc_gross'] = $inp['mc_amount1'];
		}

		if(!isset($inp['payment_status'])) { 
			$inp['payment_status'] = 'Completed';
		}

		$inp['mc_currency'] = '$';
		$ignore_dup_and_proceed = false;
		
		// set params
		$inp['first_name'] = $this->getFirst_name();
		$inp['last_name'] = $this->getLast_name();
		$inp['address1'] = $this->getAddress1();
		$inp['city'] = $this->getCity();
		$inp['state'] = $this->getState();
		$inp['zip'] = $this->getZip();
		$inp['country'] = $this->getCountry();
		$inp['phone'] = $this->getPhone();
		$inp['fax'] = $this->getFax();
		
		$inp['ship_to_first_name'] = $this->getShip_to_first_name();
		$inp['ship_to_last_name'] = $this->getShip_to_last_name();
		$inp['ship_to_address1'] = $this->getShip_to_address1();
		$inp['ship_to_city'] = $this->getShip_to_city();
		$inp['ship_to_state'] = $this->getShip_to_state();
		$inp['ship_to_zip'] = $this->getShip_to_zip();
		$inp['ship_to_country'] = $this->getShip_to_country();
						
		try {
			$record_id = Dap_PaymentProcessor::recordAuthnetIncoming($inp);
			logToFile("Dap_Payment:processAuthnetResponse(): recorded incoming authnet. id:". $record_id);
		} 
		catch (PDOException $e) {
			if(stristr($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: ") == FALSE) {
				logToFile($e->getMessage(),LOG_FATAL_DAP);
				throw $e;
				return false;
			}
			else {
				$ignore_dup_and_proceed = true;
				logToFile("Dap_Payment: " . $e->getMessage(),LOG_DEBUG_DAP);
			}
  		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
			return false;
		}

		if (!$ignore_dup_and_proceed) {
			Dap_Transactions::setRecordStatus($record_id, 1);
			logToFile("Dap_Payment:processAuthnetResponse(): set record status to 1 for record_id=". $record_id);

			Dap_Transactions::processTransaction($record_id);
		}
		else if (strcmp($source, "Dap_Payment") == 0) {
			logToFile("Dap_Payment:processAuthnetResponse(): silent post came in first, ok to ignore initial AIM integrity constraint error, LOG_DEBUG_DAP");
		}
				
		return true;
	}
	
	public function create_paypal_subscription($req)  // Paypal Direct Payment 
	{	
		$subject = SUBJECT;
		$AuthenticationMode = API_AUTHENTICATION_MODE;
		
		$hosted_cmcc = "N";
	
		$payment_url = $req['err_redirect'];
	
		if ($payment_url == "") {
			$payment_url = SITE_URL_DAP . "/dap/buy.php";
		}
	
		$payment_url= str_replace ( "http:", "https:", $payment_url );
		
		//	logToFile("Dap_Payment:create_authnet_subscription(). Self-hosted authnet payment", LOG_DEBUG_DAP);
		$req['paypal_api_login']	= trim(Dap_Config::get('PAYPAL_API_LOGIN'));
		$req['paypal_api_password'] = trim(Dap_Config::get('PAYPAL_API_PASSWORD'));;
		$req['paypal_api_signature'] = trim(Dap_Config::get('PAYPAL_API_SIGNATURE'));
		$req['paypal_api_endpoint'] = trim(Dap_Config::get('PAYPAL_API_ENDPOINT'));
				
		if (!$this->validatePaypalInput($req)) {
			header("Location: ". SITE_URL_DAP . $req['payment_err_page'] . "?response_msg=missing request params");
			return;
		}

		$invoice = isset($req['invoice']) ? $req['invoice'] : date(YmdHis);
		$post_values = array();

		//Merchant info
		$post_values['PWD'] = urlencode($req['paypal_api_password']);
		$post_values['USER'] = urlencode($req['paypal_api_login']);
		$post_values['SIGNATURE'] = urlencode($req['paypal_api_signature']);

		//Transaction Information
		$post_values['PAYMENTACTION'] = urlencode( 'Sale');
	
		/* Construct the request string that will be sent to PayPal.
		   The variable $nvpstr contains all the variables and is a
		   name value pair string with & as a delimiter */
		
		// if trial period, set amount to the trial amount
		
		$total_amount=0;
      $amount = 0;
		
		$addtocart = "Y";
		if (!isset($req['btntype']) || ($req['btntype'] == "") || ($req['btntype'] == "buynow")) {
			$addtocart = "N";
		}
		
		if(isset($req['btntype']) && ($req['btntype'] != "")) 
		  $_SESSION['btntype'] = $req['btntype'];
		  
		if ($addtocart == "N")  {
			$num_cart_items = 0;	
			//$this->emptyCart();
		}
		else
			$num_cart_items = $_SESSION['num_cart'];
		
		logToFile("Dap_Payment:create_paypal_subscription().Total amt before IS = " . $total_amount, LOG_DEBUG_DAP);
		
		for ($i=0;$i<$num_cart_items;$i++) {
			$amount =  $_SESSION['product_details'][$i]['L_AMT' . $i] * $_SESSION['product_details'][$i]['L_QTY' . $i];
			logToFile("Dap_Payment:create_paypal_subscription().LAMOUNT IS = " . $_SESSION['product_details'][$i]['L_AMT' . $i], LOG_DEBUG_DAP);
			logToFile("Dap_Payment:create_paypal_subscription().LQUANTITY IS = " . $_SESSION['product_details'][$i]['L_QTY' . $i], LOG_DEBUG_DAP);
			$total_amount = $total_amount + $amount;
		}
		
		logToFile("Dap_Payment:create_paypal_subscription().total_amount=" . $total_amount, LOG_DEBUG_DAP);
			
		logToFile("Dap_Payment:create_paypal_subscription().session new amt is =" . $_SESSION['new_amount'], LOG_DEBUG_DAP);
		
		if (!$num_cart_items || $num_cart_items == "") {
			$num_cart_items = 0;
		}
		
		logToFile("Dap_Payment:create_paypal_subscription().num_cart_items=" . $num_cart_items, LOG_DEBUG_DAP);
		
		if ($num_cart_items > 0) {
			if ($_SESSION['new_amount'] != "") {
				$post_values['AMT'] = $_SESSION['new_amount'];
			}
			else {
				$post_values['AMT'] = $total_amount;	
				$_SESSION['new_amount'] = $total_amount;
			}
		}
		else {
			// if trial period, set amount to the trial amount
			logToFile("Dap_Payment:create_paypal_subscription(). num_cart_items=" . $num_cart_items, LOG_DEBUG_DAP);
			
			if (strtoupper($req['is_recurring']) == "Y") {
				if ( isset($req['trial_amount']) && ($req['trial_amount'] != "0.00") && ($req['trial_amount'] != "0.0") && ($req['trial_amount'] != "0") ) 	{
					$amount = $req['trial_amount'];
				}
				else { 
					$amount = $req['amount'];
				}
				$post_values['AMT'] = $amount;
				$_SESSION['new_amount'] = $amount;
			}
			else {
				$post_values['AMT'] = $req['amount'];
				$amount = $req['amount'];
				$_SESSION['new_amount'] = $amount;
			}
		}
		
			
/*	 OLD - PRE 4.2
		$amount = $req['amount'];
		if (strtoupper($req['is_recurring']) == "Y") {
			//$post_values['x_recurring_billing'] = "TRUE";
			if (isset($req['trial_amount']) && ($req['trial_amount'] != "0.0") && ($req['trial_amount'] != "0.00") && ($req['trial_amount'] != "0") ) {
				$amount = $req['trial_amount'];
			}
			else { 
				$amount = $req['amount'];
			}
		}*/ 
		
		//$post_values['x_recurring_billing'] = "FALSE";
		
		//$post_values['AMT'] = $amount;
		
		$post_values['CREDITCARDTYPE'] = urlencode($this->getCard_type());
		
		if($this->getCard_num())
			$cardnum = str_replace(' ', '', $this->getCard_num());	
		
		$post_values['ACCT'] = urlencode($cardnum);
		//$post_values['EXPDATE'] = urlencode($this->getExp_date());
		
		$padded_exp_year = "20" . substr($this->getExp_date(), 2);
		$exp_month = substr($this->getExp_date(), 0, 2);
		
		
		//$post_values['EXPDATE'] = $exp_month . $padded_exp_year;
		
		$post_values['EXPDATE'] =  $this->getExp_date();
		if(strlen($post_values['EXPDATE']) <= 4) {
		//	logToFile("Dap_Payment:create_paypal_subscription(). padded, EXP month-year =" . $post_values['EXPDATE'], LOG_DEBUG_DAP);
			$post_values['EXPDATE'] = $exp_month . $padded_exp_year;
		}
		else {
			//logToFile("Dap_Payment:create_paypal_subscription(). getdiscount, EXP MONTH IS =" . $post_values['EXPDATE'], LOG_DEBUG_DAP);
		}
		$post_values['CVV2'] = urlencode($this->getCard_code());
		
		//Itemized Order Information
				
		for ($i=0;$i<$num_cart_items;$i++) {
			$is_recur = $_SESSION['product_details'][$i]['L_ISRECUR'.$i];
			
			$item_id = $_SESSION['product_details'][$i]['L_NUMBER'.$i];
			
			if(isset($_SESSION['new_amount']) && ($_SESSION['new_amount'] != "")) {
				logToFile("Dap_Payment:create_paypal_subscription(). getdiscount, total_amount=" . $total_amount, LOG_DEBUG_DAP);
				logToFile("Dap_Payment:create_paypal_subscription(). getdiscount, session new amt is =" . $_SESSION['new_amount'], LOG_DEBUG_DAP);
		
				if (intval($total_amount) != intval($_SESSION['new_amount']))  { // there is a discount applied to final amount, apply same to individual amount  
					
					$final_amt = $this->getDiscountedAmountForAddToCart($i);
					logToFile("Dap_Payment:create_paypal_subscription(): final_amt=" . $final_amt, LOG_DEBUG_DAP);
					$_SESSION['product_details'][$i]['L_AMT' . $i] = $final_amt;
					
					logToFile("Dap_Payment:create_paypal_subscription(): updated session amount =" . $_SESSION['product_details'][$i]['L_AMT' . $i], LOG_DEBUG_DAP);
					
				}
			}
			
			$post_values['L_NAME'.$i] = $_SESSION['product_details'][$i]['L_NAME'.$i];
			$post_values['L_DESC'.$i] = $_SESSION['product_details'][$i]['L_DESC'.$i];
			$post_values['L_AMT'.$i] = $_SESSION['product_details'][$i]['L_AMT'.$i];
			$post_values['L_NUMBER'.$i] = $_SESSION['product_details'][$i]['L_NUMBER'.$i];
			$post_values['L_QTY'.$i] = $_SESSION['product_details'][$i]['L_QTY'.$i];
		
			$amount= $_SESSION['product_details'][$i]['L_AMT'.$i];
	
			//$total_amount = $total_amount + $amount;

		}
		
		
		if ($num_cart_items == 0) {
			$quantity = "1";
			
			if(!isset($req['product_id']))
				$req['product_id'] = "1";
			
			$post_values['L_NAME0'] = urlencode($req['item_name']);
			$post_values['L_DESC0'] = urlencode($req['description']);
			$post_values['L_AMT0'] = urlencode($amount);
			$post_values['ITEMAMT'] = urlencode($amount);
			
			$post_values['L_NUMBER0'] = urlencode($req['product_id']);
			$post_values['L_QTY0'] = urlencode($quantity);
			$post_values['DESC'] = urlencode($req['item_name']);
		}
		
		
		
		$notify_url = SITE_URL_DAP . "/dap/dap-paypal.php";
		$notify_url= str_replace ( "http:", "https:", $notify_url );
		
		$post_values['NOTIFYURL'] = urlencode($notify_url);
		
		$billing_first_name= $this->getBillingFirst_name();
		//Billing Information
		if( isset($billing_first_name)  && ($this->getBillingFirst_name() != "")) {
			$billing_first_name	= $this->getBillingFirst_name();
		} 
		else
			$billing_first_name	= $this->getFirst_name();
		
		$billing_last_name= $this->getBillingLast_name();
		//Billing Information
		if( isset($billing_last_name)  && ($this->getBillingLast_name() != "")) {
			$billing_last_name	= $this->getBillingLast_name();
		} 
		else 
			$billing_last_name	= $this->getLast_name();
			
		//Billing Information
		$post_values['FIRSTNAME'] = urlencode($billing_first_name);
		$post_values['LASTNAME'] = urlencode($billing_last_name);
		$post_values['STREET'] = urlencode($this->getAddress1());
		$post_values['CITY'] = urlencode($this->getCity());
		$post_values['STATE'] = urlencode($this->getState());
		//$post_values['STATE'] = urlencode("CA");
		$post_values['ZIP'] = urlencode($this->getZip());
		$post_values['COUNTRYCODE'] = $this->getCountryCode();	
		//$post_values['COUNTRYCODE'] = urlencode("US");	
		
	/*	$post_values['CURRENCYCODE'] = urlencode(trim(Dap_Config::get('CURRENCY_SYMBOL')));	
		if (!isset($post_values['CURRENCYCODE']) || $post_values['CURRENCYCODE'] == '')
			$post_values['CURRENCYCODE'] = urlencode("USD");	*/
		
		if ($req['currency'] != "") {
		  $_SESSION['currency']=$req['currency'];
		  $_SESSION['currency_symbol']=$req['currency_symbol'];
		  $post_values['CURRENCYCODE'] = urlencode(trim($req['currency']));
		  logToFile("Dap_Payment:create_paypal_subscription(). buttonlevel currency: " . $post_values['CURRENCYCODE'] , LOG_DEBUG_DAP);
		}
		else {
		  $_SESSION['currency']=Dap_Config::get('CURRENCY_TEXT');
		  $_SESSION['currency_symbol']="$";
		  $post_values['CURRENCYCODE'] = urlencode(trim(Dap_Config::get('CURRENCY_TEXT')));	
		  if (!isset($post_values['CURRENCYCODE']) || $post_values['CURRENCYCODE'] == '')
			  $post_values['CURRENCYCODE'] = urlencode("USD");	
		}
				
		logToFile("Dap_Payment:create_paypal_subscription(). CURRENCY TEXT: " . Dap_Config::get('CURRENCY_TEXT'), LOG_DEBUG_DAP);
		
		$post_values['PHONENUM'] = urlencode($this->getPhone());	
		$post_values['EMAIL'] = urlencode($this->getEmail());	

		//Shipping Information  
		$post_values['SHIPTONAME'] = urlencode($this->getShip_to_first_name() . ' ' . $this->getShip_to_last_name());
		$post_values['SHIPTOSTREET'] = urlencode($this->getShip_to_address1());
		$post_values['SHIPTOCITY'] = urlencode($this->getShip_to_city());
		$post_values['SHIPTOSTATE'] = urlencode($this->getShip_to_state());
		//$post_values['SHIPTOSTATE'] = urlencode("CA");
		
		$post_values['SHIPTOZIP'] = urlencode($this->getShip_to_zip());
		$post_values['SHIPTOCOUNTRY'] = urlencode($this->getShip_to_countrycode());
		
		if($post_values['SHIPTOCOUNTRY'] == "USA")
			$post_values['SHIPTOCOUNTRY']="US";
			
		//$post_values['SHIPTOCOUNTRY'] = "US";
		
		// Convert to proper format for an http post
		$post_string = "&";

		foreach( $post_values as $key => $value ) {
			$post_string .= "$key=" . $value . "&"; 
			if (($key != "x_card_num") && ($key != "x_exp_date") && ($key != "x_card_code"))
			  logToFile("Dap_Payment:create_paypal_subscription(). " . $key . "=" . $value	, LOG_DEBUG_DAP);
		}
		$post_string = rtrim( $post_string, "& " );

		logToFile("Dap_Payment:create_paypal_subscription(). Paypal Direct Payment Request: " . $post_string, LOG_DEBUG_DAP);
		
		// Use CURL to establish a connection, submit the post, and record the response.
		//$post_url = "https://test.authorize.net/gateway/transact.dll";
		$methodName = "doDirectPayment";
				
		$_SESSION['product_id']=$req['product_id'];
		$_SESSION['payment_gateway']=$req['payment_gateway'];
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $req['paypal_api_endpoint']);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
    	
		$version=VERSION;
		//check if version is included in $nvpStr else include the version.
		if(strlen(str_replace('VERSION=', '', strtoupper($post_string))) == strlen($post_string)) {
			$post_string = "&VERSION=" . urlencode($version) . $post_string;	
		}
	
		$nvpreq="METHOD=".urlencode($methodName).$post_string;
	
		//setting the nvpreq as POST FIELD to curl
		curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);

		//getting response from server
		$post_response = curl_exec($ch);

		//convrting post_response to an Associative Array
		$nvpReqArray=deformatNVP($nvpreq);
		
		//if the connection and send worked $post_response holds the return from Authorize.net
		if (curl_errno($ch) == 0) // curl worked
		{
			//curl_close($ch);
						
		// This line takes the response and breaks it into an array using the specified delimiting character
			$response_array=deformatNVP($post_response);
			// The results are output to the screen in the form of an html numbered list.
			foreach ($response_array as $key => $value)
				logToFile("Dap_Payment:create_paypal_subscription(). Paypal Direct Payment Response: " . $key . "=" . $value, LOG_DEBUG_DAP);

			/* Display the API response back to the browser.
		   If the response from PayPal was a success, display the response parameters'
		   If the response was an error, display the errors received using APIError.php.
		   */
			curl_close($ch);

			$ack = strtoupper($response_array["ACK"]);
			$aresult = array();
			
			if ($post_values['CURRENCYCODE'] != "")
  			  $aresult['currency'] = $post_values['CURRENCYCODE'];
					 
			if ($ack == "SUCCESS") { //approved payment
				logToFile("Dap_Payment:create_paypal_subscription(). Approved payment for user: " . $this->getEmail(), LOG_DEBUG_DAP);
			// Use same transaction Id for direct payment and notfication so only one will be recorded in transaction, the other will fail with integrity constraint that has been handled as 'success' response in the code
				
				$aresult['txn_id'] =  $response_array['TRANSACTIONID'] . ":0";  
	
				logToFile("Dap_Payment:create_paypal_subscription(): Direct Payment transaction ID=" . $aresult['txn_id'], LOG_DEBUG_DAP);
			 
				// set params
				$aresult['payment_num'] = "0"; //first payment via AIM
				$aresult['item_name'] = $req['item_name'];
				$aresult['description'] = $req['description'];
				$aresult['amount'] = $req['amount'];
				$aresult['mc_gross'] = $amount;
				
				$aresult['invoice'] = $invoice;
				$aresult['payer_email'] = $this->getEmail();
				$aresult['email'] = $this->getEmail();
				$aresult['phone'] = $this->getPhone();
				$aresult['fax'] = $this->getFax();
				
				$aresult['payment_gateway'] = $req['payment_gateway'];
							
				
					// set params
				if ($num_cart_items == 0) {
					$num_cart_items = 1;
					$buynow = true;
					$aresult['payment_num'] = "0"; //first payment via AIM
					$aresult['item_name'] = $req['item_name'];
					$aresult['description'] = $req['description'];
					$aresult['amount'] = $amount;
					logToFile("not cart=" .  $buynow, LOG_DEBUG_DAP);
				}
				
				$num_cart_item = $_SESSION['num_cart'];
				
				$amt = 0;
				for($i=0;$i<$num_cart_item;$i++) { 
					$amt = $amt +  ($_SESSION['product_details'][$i]['L_AMT' . $i] * $_SESSION['product_details'][$i]['L_QTY' . $i]) ;
				}
				
				$discount = 0;
				if (isset($_SESSION['new_amount']) && ($_SESSION['new_amount'] != $amt)) {
					logToFile("session new_amount=" .  $_SESSION['new_amount'], LOG_DEBUG_DAP);
					logToFile("num_cart_item=" .  $num_cart_item, LOG_DEBUG_DAP);
				
				// coupon applied
					if ($num_cart_item > 0) {
						$discount = $_SESSION['new_amount'] / $num_cart_item;
					}
					else if (($_SESSION['new_amount'] != "") && ($_SESSION['couponCode'] != "")) {
					  $coupon = Dap_Coupon::loadCouponByCode($_SESSION['couponCode']);
					  if (isset($coupon)) {
					    $aresult['coupon_id'] = $coupon->getId();
						logToFile("Dap_payment.class.php: coupon found = " . $aresult['coupon_id']);
					  }
					}
					
				}
				
				
				for ($i=0;$i<$num_cart_items;$i++) {
					if ($buynow != true) {
								
						$aresult['txn_id'] = $aresult['txn_id'] . ":" . "$i";
						
						$aresult['payment_num'] = $i; 
						$req['is_recurring'] = $_SESSION['product_details'][$i]['L_ISRECUR'.$i];
						$item_id = $_SESSION['product_details'][$i]['L_NUMBER'.$i];
						$aresult['item_name']  = $_SESSION['product_details'][$i]['L_NAME'.$i];
						$aresult['description'] = $_SESSION['product_details'][$i]['L_DESC'.$i];
						$aresult['quantity'] = $_SESSION['product_details'][$i]['L_QTY'.$i];
						
						$aresult['amount']= ($_SESSION['product_details'][$i]['L_AMT'.$i]) * $aresult['quantity'];	
						logToFile("Dap_payment.class.php: new_amount = " . $_SESSION['new_amount'] . ", couponCode = " . $_SESSION['couponCode']);
						if (($_SESSION['new_amount'] != "") && ($_SESSION['couponCode'] != "")) {
							
							$coupon = Dap_Coupon::loadCouponByCode($_SESSION['couponCode']);
														
							if (isset($coupon)) {
								logToFile("Dap_payment.class.php: coupon found");
						
								$itemname = $_SESSION['product_details'][$i]['L_NAME' . $i];
								$product = Dap_Product::loadProductByName($itemname);
								$productCoupon =	Dap_ProductCoupon::findCouponIdAndProductId($product->getId(), $coupon->getId());
						        $aresult['coupon_id'] = $coupon->getId();
								logToFile("Dap_payment.class.php: coupon_id IS = " . $aresult['coupon_id']);
									
								if (isset($productCoupon)) {
									$discount_amount = $coupon->getDiscount_amt();
									
									logToFile("Dap_payment.class.php: orig amount = " . $_SESSION['product_details'][$i]['L_AMT' . $i]);
									
									$aresult['amount']= (($_SESSION['product_details'][$i]['L_AMT' . $i] -  $discount_amount) * $_SESSION['product_details'][$i]['L_QTY' . $i]) ;
								
									logToFile("Dap_payment.class.php: discounted amount = " . $aresult['amount']);
									
									logToFile("Dap_payment.class.php: itemname = " . $_SESSION['product_details'][$i]['L_NAME' . $i] . ", discount amount = " . $coupon->getDiscount_amt());
								}
							}
						}
																					
						$req['is_recurring'] = $_SESSION['product_details'][$i]['L_ISRECUR'.$i]; 
						$req['recurring_cycle_1'] = $_SESSION['product_details'][$i]['L_RECUR1'.$i]; 
						$req['recurring_cycle_2'] = $_SESSION['product_details'][$i]['L_RECUR2'.$i]; 
						$req['recurring_cycle_3'] = $_SESSION['product_details'][$i]['L_RECUR3'.$i]; 
						$req['total_occurrences'] = $_SESSION['product_details'][$i]['L_TOTALOCCUR'.$i];
					
						logToFile("buynow=" .  $buynow, LOG_DEBUG_DAP);
					}
							
					unset($_SESSION['new_amount']);
					unset($_SESSION['couponCode']);
					
					
					$user = Dap_User::loadUserByEmail($aresult['email']);
					$userExists = "Y";
			
					if(!isset($user)) {
						// Update comments and howdidyouhearaboutus
						$userExists = "N";
					}
					
					//record user->product in database
					if (!$this->processPaymentResponse ($aresult, "Dap_Payment"))
					{
						$subject="DAP transaction recording failed for the user: " . $aresult['email'];
						$body="Dap_Payment:processPaymentResponse(): " . $req['payment_gateway'] . " payment transaction successfully processed but DAP transaction recording failed for the user: " . $aresult['email'] . ", who bought the item: " . $aresult['item_name'] . ", for the amount: $" . $aresult['amount'] . ". But the Silent Post from Authnet should arrive shortly that would provide DAP with another chance to create user->product relationship and record the transaction. If not, this transaction will have to be handled manually within DAP admin console";
						sendAdminEmail($subject, $body);
						logToFile($body, LOG_DEBUG_DAP);
					}
					else if (strtoupper($req['is_recurring']) == "Y")
					{
						// show success page for the successful initial transaction via AIM but send admin email for ARB (recurring) failure
						if (!$this->create_paypal_recurring_subscription ($req, $aresult))
						{
							$subject="Recurring subscription failed for the user: " . $aresult['email'];
							$body="Dap_Payment:create_paypal_recurring_subscription():failed to process the recurring transaction for user: " . $aresult['email'] . ", who bought the item: " . $aresult['item_name'] . ", for the amount: $" . $aresult['amount'] . ". The recurring payment transaction will have to be handled manually within paypal.com.";
							logToFile($body, LOG_DEBUG_DAP);
							sendAdminEmail($subject, $body);
						}
					}
					
				}
			
					
				$this->emptyCart();
				
				$user = Dap_User::loadUserByEmail($this->getEmail());			
				if(isset($user)) {
					// Update comments and howdidyouhearaboutus
					$ret = $this->updateCustomFields ($req, $user->getId() );
					logToFile("Dap_Payment:create_paypal_subscription(): update status to A");	
					$user->setStatus("A");
					$user->update();
				}
				
				if ( ($userExists == "N") || ((isset($_SESSION["userexistsbutallow"])) && ($_SESSION["userexistsbutallow"] == "Y")) ) {
						logToFile("Dap_Payment:create_paypal_subscription(): redirecting to authenticate.php to auto login user". $record_id);						
						$_SESSION["userexistsbutallow"]="";
						unset($_SESSION["userexistsbutallow"]);
						header("Location: /dap/authenticate.php?email=" . $aresult['email'] . "&password=" . $user->getPassword() . "&submitted=Y&request=".$req['payment_succ_page']);
						exit;
				}
				
				logToFile("Dap_Payment:create_paypal_subscription(): redirecting...". $req['payment_succ_page']);
				header("Location: " . $req['payment_succ_page']);
				return;
							
			}
			else { 
				
				//	$shortMessage = $resArray["L_SHORTMESSAGE0"];
			  	$aresult['response_code'] = $response_array["L_ERRORCODE0"];
				$aresult['text'] =  $response_array["L_LONGMESSAGE0"];
			
				logToFile("Dap_Payment:create_paypal_subscription(). Error Code: " . $aresult['response_code'] . ", Error Text: " . $aresult['text'], LOG_DEBUG_DAP); 
				
				sendAdminEmail("Paypal direct payment failed for " . $aresult['email'], "Dap_Payment:create_paypal_subscription(). Failed for user: " . $aresult['email'] . " with Error Code: " . $aresult['response_code'] . ", Error Text: " . $aresult['text']);
				
				$_SESSION['err_text']=$aresult['text'];
				header("Location: ".$payment_url."?err_text=".$_SESSION['err_text']);
				//header("Location: ".$payment_url);
				
				return;
			}
		}
		else
		{
			curl_close($ch);

			logToFile("Dap_Payment:create_paypal_subscription(). Failed to connect to Paypal", LOG_DEBUG_DAP);
			sendAdminEmail("Paypal connection could not be established for " . $aresult['email'], "Dap_Payment:create_paypal_subscription(). Paypal connection could not be established for: " . $aresult['email'] . " for product = " . $aresult['item_name']);
			
			$_SESSION['err_text']="Sorry, failed to connect to Paypal. Please retry or contact the site admin";
			header("Location: ".$payment_url."?err_text=".$_SESSION['err_text']);
			return;
		}
	}

// ===================
	public function create_paypal_recurring_subscription($req, $aresult)  // ARB
	{
		$req['paypal_api_login']	= trim(Dap_Config::get('PAYPAL_API_LOGIN'));
		$req['paypal_api_password'] = trim(Dap_Config::get('PAYPAL_API_PASSWORD'));;
		$req['paypal_api_signature'] = trim(Dap_Config::get('PAYPAL_API_SIGNATURE'));
		$req['paypal_api_endpoint'] = trim(Dap_Config::get('PAYPAL_API_ENDPOINT'));
		
		//subscription name... same as product name
		$item_name = $req['item_name'];
		
		logToFile("Dap_Payment:create_paypal_recurring_subscription() - item_name=" . $item_name, LOG_DEBUG_DAP);
		
		$post_values = array();

		//Merchant info
		$post_values['PWD'] = urlencode($req['paypal_api_password']);
		$post_values['USER'] = urlencode($req['paypal_api_login']);
		$post_values['SIGNATURE'] = urlencode($req['paypal_api_signature']);

		//Transaction Information
		$post_values['PAYMENTACTION'] = urlencode( 'Sale');
	
		/* Construct the request string that will be sent to PayPal.
		   The variable $nvpstr contains all the variables and is a
		   name value pair string with & as a delimiter */
		
		$post_values['AMT'] = $req['amount'];
		
		$post_values['CREDITCARDTYPE'] =  urlencode($this->getCard_type());
		
		if($this->getCard_num()!='')
			$cardnum = str_replace(' ', '', $this->getCard_num());	
		
		$post_values['ACCT'] = urlencode($cardnum);
		//$post_values['EXPDATE'] = urlencode($this->getExp_date());
			
		
		$exp_month = substr($this->getExp_date(), 0, 2);
		
		//$post_values['EXPDATE'] = $exp_month . $padded_exp_year;
		
		$post_values['EXPDATE'] = $this->getExp_date();
		
		logToFile("Dap_Payment:create_paypal_recurring_subscription(). ORIG EXP DATE: " . $post_values['EXPDATE'] , LOG_DEBUG_DAP);
		
		if(strlen($post_values['EXPDATE']) <= 4) {
		//	logToFile("Dap_Payment:create_paypal_subscription(). padded, EXP month-year =" . $post_values['EXPDATE'], LOG_DEBUG_DAP);
			$padded_exp_year = "20" . substr($this->getExp_date(), 2);
			$post_values['EXPDATE'] = $exp_month . $padded_exp_year;
			logToFile("Dap_Payment:create_paypal_recurring_subscription(). UPDATED EXP DATE: " . $post_values['EXPDATE'] , LOG_DEBUG_DAP);
		}
		
		
		
		$post_values['CVV2'] = urlencode($this->getCard_code());
		
		//Itemized Order Information
		$quantity = "1";
		if(!isset($req['product_id']))
			$req['product_id'] = "1";
				
		$post_values['L_NAME0'] = urlencode($req['item_name']);
		$post_values['L_DESC0'] = urlencode($req['item_name']);
		$post_values['L_AMT0'] = urlencode($req['amount']);
		$post_values['L_NUMBER0'] = urlencode($req['product_id']);
		$post_values['L_QTY0'] = urlencode($quantity);
	
		$billing_first_name= $this->getBillingFirst_name();
		//Billing Information
		if( isset($billing_first_name)  && ($this->getBillingFirst_name() != "")) {
			$billing_first_name	= $this->getBillingFirst_name();
		} 
		else
			$billing_first_name	= $this->getFirst_name();
		
		$billing_last_name= $this->getBillingLast_name();
		//Billing Information
		if( isset($billing_last_name)  && ($this->getBillingLast_name() != "")) {
			$billing_last_name	= $this->getBillingLast_name();
		} 
		else 
			$billing_last_name	= $this->getLast_name();
			
		//Billing Information
		$post_values['FIRSTNAME'] = urlencode($billing_first_name);
		$post_values['LASTNAME'] = urlencode($billing_last_name);
		$post_values['STREET'] = urlencode($this->getAddress1());
		$post_values['CITY'] = urlencode($this->getCity());
		$post_values['STATE'] = urlencode($this->getState());
		$post_values['ZIP'] = urlencode($this->getZip());
		$post_values['COUNTRYCODE'] = $this->getCountryCode();	
		
		//$post_values['CURRENCYCODE'] = urlencode("USD");		
/*		$post_values['CURRENCYCODE'] = urlencode(trim(Dap_Config::get('CURRENCY_SYMBOL')));	
		if (!isset($post_values['CURRENCYCODE']) || $post_values['CURRENCYCODE'] == '')
			$post_values['CURRENCYCODE'] = urlencode("USD");	*/
		
		// added on 11/10 - button level currency
		if ($req['currency'] != "") {
		  $post_values['CURRENCYCODE'] = urlencode(trim($req['currency']));
		  logToFile("Dap_Payment:create_paypal_recurring_subscription(). buttonlevel currency: " . $post_values['CURRENCYCODE'] , LOG_DEBUG_DAP);
		}
		else {
		  $post_values['CURRENCYCODE'] = urlencode(trim(Dap_Config::get('CURRENCY_TEXT')));	
		  if (!isset($post_values['CURRENCYCODE']) || $post_values['CURRENCYCODE'] == '')
			  $post_values['CURRENCYCODE'] = urlencode("USD");	
		}
				
		/*commented on 11/10
		$post_values['CURRENCYCODE'] = urlencode(trim(Dap_Config::get('CURRENCY_TEXT')));	
		if (!isset($post_values['CURRENCYCODE']) || $post_values['CURRENCYCODE'] == '')
			$post_values['CURRENCYCODE'] = urlencode("USD");	*/
		
		logToFile("Dap_Payment:create_paypal_recurring_subscription(). CURRENCY TEXT: " . Dap_Config::get('CURRENCY_TEXT'), LOG_DEBUG_DAP);
		
		$post_values['PHONENUM'] = urlencode($this->getPhone());	
		$post_values['EMAIL'] = urlencode($this->getEmail());	

		//Shipping Information  
		$post_values['SHIPTONAME'] = urlencode($this->getShip_to_first_name() . ' ' . $this->getShip_to_last_name());
		$post_values['SHIPTOSTREET'] = urlencode($this->getShip_to_address1());
		$post_values['SHIPTOCITY'] = urlencode($this->getShip_to_city());
		$post_values['SHIPTOSTATE'] = urlencode($this->getShip_to_state());
		
		$post_values['SHIPTOZIP'] = urlencode($this->getShip_to_zip());
		$post_values['SHIPTOCOUNTRY'] = urlencode($this->getShip_to_countrycode());
		
		$profileStartDate = date('Y-m-d', strtotime("+".$req['recurring_cycle_1']. " day"));
		$post_values['PROFILESTARTDATE'] = urlencode($profileStartDate.'T00:00:00Z');
		$post_values['DESC'] = urlencode($req['item_name']);
		$post_values['BILLINGPERIOD'] = urlencode('Day');
		$post_values['BILLINGFREQUENCY'] = urlencode($req['recurring_cycle_3']);
		$post_values['TOTALBILLINGCYCLES'] = urlencode($req['total_occurrences']);
		
		$notify_url = SITE_URL_DAP . "/dap/dap-paypal.php";
		$notify_url= str_replace ( "http:", "https:", $notify_url );
		
		$post_values['NOTIFYURL'] = urlencode($notify_url);
		$post_values['PROFILEREFERENCE'] = urlencode(SITE_URL_DAP);
		
		logToFile("Dap_Payment:create_paypal_recurring_subscription() - profileStartDate=" . $profileStartDate,LOG_DEBUG_DAP);
		
		// Convert to proper format for an http post
		$post_string = "&";

		foreach( $post_values as $key => $value ) {
			$post_string .= "$key=" . $value . "&"; 
		//	logToFile("Dap_Payment:create_paypal_recurring_subscription(). Paypal Recurring Billing key: " . $key . " ,value=" . $value, LOG_DEBUG_DAP);
		}
		$post_string = rtrim( $post_string, "& " );

		// Use CURL to establish a connection, submit the post, and record the response.
		//$post_url = "https://test.authorize.net/gateway/transact.dll";
		
		$methodName = "CreateRecurringPaymentsProfile";
				
		$_SESSION['product_id']=$req['product_id'];
		$_SESSION['payment_gateway']=$req['payment_gateway'];
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $req['paypal_api_endpoint']);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
    	$version=VERSION;
		//check if version is included in $nvpStr else include the version.
		if(strlen(str_replace('VERSION=', '', strtoupper($post_string))) == strlen($post_string)) {
			$post_string = "&VERSION=" . urlencode($version) . $post_string;	
		}
	
		$nvpreq="METHOD=".urlencode($methodName).$post_string;
	
		//setting the nvpreq as POST FIELD to curl
		curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);

		//getting response from server
		$post_response = curl_exec($ch);

		if (curl_errno($ch) == 0) // transaction approved
		{
			//curl_close($ch);
						
		// This line takes the response and breaks it into an array using the specified delimiting character
			$response_array=deformatNVP($post_response);
			// The results are output to the screen in the form of an html numbered list.
			foreach ($response_array as $key => $value)
				logToFile("Dap_Payment:create_paypal_recurring_subscription(). Paypal Recurring Billing Response: key=" . $key . " ,value=" . $value, LOG_DEBUG_DAP);

			/* Display the API response back to the browser.
		   If the response from PayPal was a success, display the response parameters'
		   If the response was an error, display the errors received using APIError.php.
		   */
			curl_close($ch);
			$aresult = array();
			$ack = strtoupper($response_array["ACK"]);
			//if the connection and send worked $response holds the return from Authorize.net
			if (!strcasecmp ($ack,"SUCCESS")) { //SUCCESS
				$_SESSION['reshash']=$response_array;
				logToFile("Dap_Payment:create_paypal_recurring_subscription(). Dap_Payment successfully processed by Paypal", LOG_DEBUG_DAP);
				return TRUE;
			}
			else {
					//	$shortMessage = $resArray["L_SHORTMESSAGE0"];
			  	$aresult['response_code'] = $response_array["L_ERRORCODE0"];
				$aresult['text'] =  $response_array["L_LONGMESSAGE0"];
				logToFile("Dap_Payment:create_paypal_recurring_subscription(). Error Code: " . $resultCode . " Reason Code: " . $code . " text: " . $text . " Subs Id: " . $subscription_id , LOG_DEBUG_DAP); 
				return FALSE;
			}
		}	
		else
		{
			logToFile("Dap_Payment:create_paypal_recurring_subscription(). Failed to connect to Paypal", LOG_DEBUG_DAP);
			sendAdminEmail("Paypal connection could not be established for " . $aresult['email'], "Dap_Payment:create_paypal_subscription(). Paypal connection for recurring subscription could not be established for: " . $aresult['email'] . " for product = " . $aresult['item_name']);
			
			return FALSE;
		}

	}  // end function


	// This function called by both initial payment (AIM/Direct Payment) as well as silent post / IPN notify for recurring
	public function processPaymentResponse($inp, $source) {
		logToFile("Dap_Payment:processPaymentResponse(): " . $source . " response: ", $inp['email']); 

		if (!isset($inp['txn_type']) || ($inp['txn_type'] == "")) 
			$inp['txn_type'] = "subscr_payment";

		if(!isset($inp['mc_gross'])) { 
			$inp['mc_gross'] = '0.00';
		}
		if(isset($inp['mc_amount3'])) {   
			$inp['mc_gross'] = $inp['mc_amount3'];
		}
		if(isset($inp['mc_amount2'])) {
			$inp['mc_gross'] = $inp['mc_amount2'];
		}
		if(isset($inp['mc_amount1'])) {  
			$inp['mc_gross'] = $inp['mc_amount1'];
		}

		if(!isset($inp['payment_status'])) { 
			$inp['payment_status'] = 'Completed';
		}

		if ($inp['currency'] == "") {
		  $inp['mc_currency'] = trim(Dap_Config::get('CURRENCY_SYMBOL'));	
		  if (!isset($inp['mc_currency']) || $inp['mc_currency'] == '')
			$inp['mc_currency'] = urlencode("USD");
		}
		
		if (!isset($inp['mc_currency']) || $inp['mc_currency'] == '')
		  $inp['mc_currency'] = urlencode("USD");
		
		logToFile("Dap_Payment:processPaymentResponse(): mc_currency:". $mc_currency);
		
		$ignore_dup_and_proceed = false;
		
		// set params
		$inp['first_name'] = $this->getFirst_name();
		$inp['last_name'] = $this->getLast_name();
		$inp['address1'] = $this->getAddress1();
		$inp['address2'] = $this->getAddress2();
		$inp['city'] = $this->getCity();
		$inp['state'] = $this->getState();
		$inp['zip'] = $this->getZip();
		$inp['country'] = $this->getCountry();
		$inp['phone'] = $this->getPhone();
		$inp['fax'] = $this->getFax();
		
		$inp['ship_to_first_name'] = $this->getShip_to_first_name();
		$inp['ship_to_last_name'] = $this->getShip_to_last_name();
		$inp['ship_to_address1'] = $this->getShip_to_address1();
		$inp['ship_to_address2'] = $this->getShip_to_address2();
		$inp['ship_to_city'] = $this->getShip_to_city();
		$inp['ship_to_state'] = $this->getShip_to_state();
		$inp['ship_to_zip'] = $this->getShip_to_zip();
		$inp['ship_to_country'] = $this->getShip_to_country();
						
		try {
			if ($inp['payment_gateway'] == "paypal") {
				$record_id = Dap_PaymentProcessor::recordPaypalIncoming($inp);
				logToFile("Dap_Payment:processPaymentResponse(): recorded incoming paypal. id:". $record_id);
			}
			else {
				$record_id = Dap_PaymentProcessor::recordAuthnetIncoming($inp);
				logToFile("Dap_Payment:processPaymentResponse(): recorded incoming authnet. id:". $record_id);
			}
		} 
		catch (PDOException $e) {
			if(stristr($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: ") == FALSE) {
				logToFile($e->getMessage(),LOG_FATAL_DAP);
				throw $e;
				return false;
			}
			else {
				$ignore_dup_and_proceed = true;
				logToFile("Dap_Payment: " . $e->getMessage(),LOG_DEBUG_DAP);
			}
  		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
			return false;
		}

		if (!$ignore_dup_and_proceed) {
			Dap_Transactions::setRecordStatus($record_id, 1);
			logToFile("Dap_Payment:processPaymentResponse(): set record status to 1 for record_id=". $record_id);

			Dap_Transactions::processTransaction($record_id);
		}
		else if (strcmp($source, "Dap_Payment") == 0) {
			logToFile("Dap_Payment:processPaymentResponse(): silent post/notification came in first, ok to ignore initial payment's integrity constraint error, LOG_DEBUG_DAP");
		}
				
		return true;
	}

	//AddToCart
	public function paypalAddtoCart($req)  // Paypal express checkout
	{	
		$post_values = array();
		$amount = $req['amount'];
		if (strtoupper($req['is_recurring']) == "Y") {
			$recur_count =1;
			if (!isset($_SESSION['recur_count']) || ($_SESSION['recur_count'] == ''))
				$_SESSION['recur_count'] = $recur_count;
			else {
				$recur_count = $_SESSION['recur_count']+1;
				$_SESSION['recur_count'] = $recur_count;
			}
			
			//$post_values['x_recurring_billing'] = "TRUE";
			if (isset($req['trial_amount']) && ($req['trial_amount'] != "0.0") && ($req['trial_amount'] != "0.00") && ($req['trial_amount'] != "0") ) {
				$amount = $req['trial_amount'];
			}
			else { 
				$amount = $req['amount'];
			}
		} 
		
		$num_cart = 1;
		if (!isset($_SESSION['num_cart']) || ($_SESSION['num_cart'] == ''))
			$_SESSION['num_cart'] = $num_cart;
		else {
			$num_cart = $_SESSION['num_cart']+1;
			$_SESSION['num_cart'] = $num_cart;
		}
		logToFile("Dap_Payment:paypalAddtoCart(). product amount " . $amount, LOG_DEBUG_DAP);
		logToFile("Dap_Payment:paypalAddtoCart(). num_cart" . $_SESSION['num_cart'], LOG_DEBUG_DAP);
		
		//Itemized Order Information
		$quantity=1;
		$product_id=1;
		$i = $num_cart - 1;
		$post_values['L_NAME' . $i] = $req['item_name'];
		$post_values['L_DESC' . $i] = $req['description'];
		$post_values['L_AMT' . $i] = $amount;
		$post_values['L_NUMBER' . $i] = $product_id;
		$post_values['L_QTY' . $i] = $quantity;
		$post_values['L_ISRECUR' . $i] = $req['is_recurring'];
		$post_values['L_RECURAMT' . $i] = $req['amount'] ;
		$post_values['L_RECUR1' . $i] = $req['recurring_cycle_1'];
		$post_values['L_RECUR2' . $i] = $req['recurring_cycle_2'];
		$post_values['L_RECUR3' . $i] = $req['recurring_cycle_3'];
		$post_values['L_TOTALOCCUR' . $i] = $req['total_occurrences'];
		
		
		foreach( $post_values as $key => $value ) {
			$post_string .= "$key=" . $value . "&"; 
			logToFile("Dap_Payment:paypalAddtoCart(). " . $key . "=" . $value	, LOG_DEBUG_DAP);
		}

		$_SESSION['product_id']=$req['product_id'];
		$_SESSION['payment_gateway']=$req['payment_gateway'];
		$_SESSION['product_details'][$i]=$post_values;
		
		logToFile("Dap_Payment:paypalAddtoCart(). Complete", LOG_DEBUG_DAP);
		header("Location: " . $req['payment_succ_page']);
		return;
	}

	//RemoveFromCart
	public function paypalRemoveFromCart($itemname,$redirect="")  // Paypal express checkout
	{	
		$post_values = array();
		$num_cart_item = $_SESSION['num_cart'];
		logToFile("Dap_Payment:paypalRemoveFromCart(). remove item=" . $itemname, LOG_DEBUG_DAP);
		logToFile("Dap_Payment:paypalRemoveFromCart(). numcart=" . $_SESSION['num_cart'], LOG_DEBUG_DAP);
		
		$j=0;
		for ($i=0;$i<$num_cart_item;$i++) {
			logToFile("Dap_Payment:paypalRemoveFromCart(). session itemname =" . $_SESSION['product_details'][$i]['L_NAME'.$i], LOG_DEBUG_DAP);
			if (strcmp($_SESSION['product_details'][$i]['L_NAME'.$i], $itemname) == 0) {
				$removeindex = $i;
				logToFile("Dap_Payment:paypalRemoveFromCart(). remove index=" . $removeindex, LOG_DEBUG_DAP);
				logToFile("Dap_Payment:paypalRemoveFromCart(). recur count=" . $_SESSION['recur_count'], LOG_DEBUG_DAP);
			
				$_SESSION['num_cart']--;
				if ($_SESSION['product_details'][$i]['L_ISRECUR'.$i] == "Y") {
					$_SESSION['recur_count'] = $_SESSION['recur_count'] - 1;
				}
				unset($_SESSION['product_details'][$i]);
			}
			else {
				$post_values[$j]['L_NAME'.$j] = $_SESSION['product_details'][$i]['L_NAME'.$i];
				$post_values[$j]['L_DESC'.$j] = $_SESSION['product_details'][$i]['L_DESC'.$i];
				$post_values[$j]['L_AMT'.$j] = $_SESSION['product_details'][$i]['L_AMT'.$i];
				$post_values[$j]['L_NUMBER'.$j] = $_SESSION['product_details'][$i]['L_NUMBER'.$i];
				$post_values[$j]['L_QTY'.$j] = $_SESSION['product_details'][$i]['L_QTY'.$i];
				$post_values[$j]['L_ISRECUR'.$j] = $_SESSION['product_details'][$i]['L_ISRECUR'.$i];
				$post_values[$j]['L_RECUR1'.$j] = $_SESSION['product_details'][$i]['L_RECUR1'.$i];
				$post_values[$j]['L_RECUR2'.$j] = $_SESSION['product_details'][$i]['L_RECUR2'.$i];
				$post_values[$j]['L_RECUR3'.$j] = $_SESSION['product_details'][$i]['L_RECUR3'.$i];
				$post_values[$j]['L_TOTALOCCUR'.$j] = $_SESSION['product_details'][$i]['L_TOTALOCCUR'.$i];
				$_SESSION['product_details'][$j] = $post_values[$j];
				$j++;
			}
		}
		
		/*for ($i=0;$i<$j;$i++) {
		 $_SESSION['product_details'][$i] = $post_values[$i];
		}
			*/
		logToFile("Dap_Payment:paypalRemoveFromCart(). Complete", LOG_DEBUG_DAP);
		if($redirect != "") header("Location: " . $redirect);
		else header("Location: /dap/PaypalCheckoutConfirm.php");
		return;
	}

	public function paypalExpressBuyNow($req)  // Paypal express checkout
	{	
		$req['paypal_api_login'] = trim(Dap_Config::get('PAYPAL_API_LOGIN'));
		$req['paypal_api_password'] = trim(Dap_Config::get('PAYPAL_API_PASSWORD'));;
		$req['paypal_api_signature'] = trim(Dap_Config::get('PAYPAL_API_SIGNATURE'));
		$req['paypal_api_endpoint'] = trim(Dap_Config::get('PAYPAL_API_ENDPOINT'));
		
		if (!$this->validatePaypalInput($req)) {
			header("Location: ". $req['payment_err_page'] . "?response_msg=missing request params");
			return;
		}

		$invoice = isset($req['invoice']) ? $req['invoice'] : date(YmdHis);
		$post_values = array();

		//Merchant info
		$post_values['PWD'] = urlencode($req['paypal_api_password']);
		$post_values['USER'] = urlencode($req['paypal_api_login']);
		$post_values['SIGNATURE'] = urlencode($req['paypal_api_signature']);

		//Transaction Information
		$post_values['PAYMENTACTION'] = urlencode('Sale');
		
		$num_cart_items = $_SESSION['num_cart'];
		logToFile("Dap_Payment:paypalExpressBuyNow().num_cart_items=" . $num_cart_items, LOG_DEBUG_DAP);
		
		$post_values['AMT'] = 0;
		$recur_count=0;
		$purchase_order = array();
		for ($i=0;$i<$num_cart_items;$i++) {
			//$post_values['x_recurring_billing'] = "FALSE";
			$is_recur = $_SESSION['product_details'][$i]['L_ISRECUR'.$i];
			if ($is_recur != "Y")
				continue;
			$post_values['AMT'] = urlencode($post_values['AMT'] + $_SESSION['product_details'][$i]['L_AMT'.$i]);
			//Itemized Order Information
			$post_values['L_NAME'.$recur_count] = urlencode($_SESSION['product_details'][$i]['L_NAME'.$i]);
			$post_values['L_DESC'.$recur_count] = urlencode($_SESSION['product_details'][$i]['L_DESC'.$i]);
			$post_values['L_AMT'.$recur_count] = urlencode($_SESSION['product_details'][$i]['L_AMT'.$i]);
			$post_values['L_NUMBER'.$recur_count] = urlencode($_SESSION['product_details'][$i]['L_NUMBER'.$i]);
			$post_values['L_QTY'.$recur_count] = urlencode($_SESSION['product_details'][$i]['L_QTY'.$i]);
			
			$purchase_order['L_NAME'.$recur_count] = $_SESSION['product_details'][$i]['L_NAME'.$i];
			$purchase_order['L_DESC'.$recur_count] = $_SESSION['product_details'][$i]['L_DESC'.$i];
			$purchase_order['L_AMT'.$recur_count] = $_SESSION['product_details'][$i]['L_AMT'.$i];
			$purchase_order['L_NUMBER'.$recur_count] = $_SESSION['product_details'][$i]['L_NUMBER'.$i];
			$purchase_order['L_QTY'.$recur_count] = $_SESSION['product_details'][$i]['L_QTY'.$i];
			$purchase_order['L_ISRECUR'.$recur_count] = $_SESSION['product_details'][$i]['L_ISRECUR'.$i];
			$purchase_order['L_RECURAMT'.$recur_count] = $_SESSION['product_details'][$i]['L_RECURAMT'.$i];
			$purchase_order['L_RECUR1'.$recur_count] = $_SESSION['product_details'][$i]['L_RECUR1'.$i];
			$purchase_order['L_RECUR2'.$recur_count] = $_SESSION['product_details'][$i]['L_RECUR2'.$i];
			$purchase_order['L_RECUR3'.$recur_count] = $_SESSION['product_details'][$i]['L_RECUR3'.$i];
			$purchase_order['L_TOTALOCCUR'.$recur_count] = $_SESSION['product_details'][$i]['L_TOTALOCCUR'.$i];
			$recur_count++;
			
			logToFile("Dap_Payment:paypalExpressBuyNow(). recurring item_name" . $i . "=" . $post_values['L_NAME'.$i], LOG_DEBUG_DAP);
		}
	//	$_SESSION['total_recurring_items'] = $recur_count;
		
		for ($i=0;$i<$num_cart_items;$i++) {
			//$post_values['x_recurring_billing'] = "FALSE";
			$is_recur = $_SESSION['product_details'][$i]['L_ISRECUR'.$i];
			if ($is_recur == "Y")
				continue;
			$post_values['AMT'] = urlencode($post_values['AMT'] + $_SESSION['product_details'][$i]['L_AMT'.$i]);
			//Itemized Order Information
			$post_values['L_NAME'.$recur_count] = urlencode($_SESSION['product_details'][$i]['L_NAME'.$i]);
			$post_values['L_DESC'.$recur_count] = urlencode($_SESSION['product_details'][$i]['L_DESC'.$i]);
			$post_values['L_AMT'.$recur_count] = urlencode($_SESSION['product_details'][$i]['L_AMT'.$i]);
			$post_values['L_NUMBER'.$recur_count] = urlencode($_SESSION['product_details'][$i]['L_NUMBER'.$i]);
			$post_values['L_QTY'.$recur_count] = urlencode($_SESSION['product_details'][$i]['L_QTY'.$i]);
			
			$purchase_order['L_NAME'.$recur_count] = $_SESSION['product_details'][$i]['L_NAME'.$i];
			$purchase_order['L_DESC'.$recur_count] = $_SESSION['product_details'][$i]['L_DESC'.$i];
			$purchase_order['L_AMT'.$recur_count] = $_SESSION['product_details'][$i]['L_AMT'.$i];
			$purchase_order['L_NUMBER'.$recur_count] = $_SESSION['product_details'][$i]['L_NUMBER'.$i];
			$purchase_order['L_QTY'.$recur_count] = $_SESSION['product_details'][$i]['L_QTY'.$i];
			$purchase_order['L_ISRECUR'.$recur_count] = $_SESSION['product_details'][$i]['L_ISRECUR'.$i];
			$purchase_order['L_RECUR1'.$recur_count] = $_SESSION['product_details'][$i]['L_RECUR1'.$i];
			$purchase_order['L_RECUR2'.$recur_count] = $_SESSION['product_details'][$i]['L_RECUR2'.$i];
			$purchase_order['L_RECUR3'.$recur_count] = $_SESSION['product_details'][$i]['L_RECUR3'.$i];
			$purchase_order['L_TOTALOCCUR'.$recur_count] = $_SESSION['product_details'][$i]['L_TOTALOCCUR'.$i];
			$recur_count++;
			
			logToFile("Dap_Payment:paypalExpressBuyNow(). non-recurring item_name" . $i . "=" . $post_values['L_NAME'.$i], LOG_DEBUG_DAP);
		}
		
		//$_SESSION['total_non-recurring_count'] = $recur_count - $_SESSION['total_recurring_items'];
		
		// Set request-specific fields.
		$currencyID = urlencode('USD');							// or other currency code ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
		$paymentType = urlencode('Sale');				// or 'Sale' or 'Order'
		
		$post_values['TOKEN'] = urlencode($_SESSION['paypal_express_token']);
//		$post_values['PAYERID'] = urlencode($_SESSION['paymentObj']->getPayerId());
		if($_SESSION["PAYERID"] != "")
		  $post_values['PAYERID'] = urlencode($_SESSION["PAYERID"]);
		else 
		  $post_values['PAYERID'] = urlencode($_SESSION['paymentObj']->getPayerId());
		logToFile("Dap_Payment:paypalExpressBuyNow(). PAYERID=" . $post_values['PAYERID'], LOG_DEBUG_DAP);
		
		$post_values['CURRENCYCODE'] = urlencode(trim(Dap_Config::get('CURRENCY_TEXT')));	
		if (!isset($post_values['CURRENCYCODE']) || $post_values['CURRENCYCODE'] == '')
			$post_values['CURRENCYCODE'] = urlencode("USD");	
	
		$version = VERSION;
		$post_values['VERSION'] = urlencode($version);
		$post_values['METHOD'] = urlencode("DoExpressCheckoutPayment");
		
		logToFile("Dap_Payment:paypalExpressBuyNow(). method=DoExpressCheckoutPayment", LOG_DEBUG_DAP);
		// Convert to proper format for an http post
		$post_string = "&x=y&";

		foreach( $post_values as $key => $value ) {
			$post_string .= "$key=" . $value . "&"; 
			logToFile("Dap_Payment:paypalExpressBuyNow(). " . $key . "=" . $value	, LOG_DEBUG_DAP);
		}
		
		$post_string = rtrim( $post_string, "& " );

		logToFile("Dap_Payment:paypalExpressBuyNow().  Request: " . $post_string, LOG_DEBUG_DAP);
		
		$_SESSION['product_id']=$req['product_id'];
		$_SESSION['payment_gateway']=$req['payment_gateway'];
		
		// Use CURL to establish a connection, submit the post, and record the response.
		//$post_url = "https://test.authorize.net/gateway/transact.dll";
					
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $req['paypal_api_endpoint']);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
    	curl_setopt($ch,CURLOPT_POSTFIELDS,$post_string);

		//getting response from server
		$post_response = curl_exec($ch);

		//if the connection and send worked $post_response holds the return from paypal
		
		$ret = $this->paypalDoExpressCheckoutResponse($req, $ch, $post_response, $purchase_order); 
			
		return $ret;
	}

	public function paypalExpressCheckout($req, $methodName)  // Paypal express checkout
	{	
		$req['paypal_api_login'] = trim(Dap_Config::get('PAYPAL_API_LOGIN'));
		$req['paypal_api_password'] = trim(Dap_Config::get('PAYPAL_API_PASSWORD'));;
		$req['paypal_api_signature'] = trim(Dap_Config::get('PAYPAL_API_SIGNATURE'));
		$req['paypal_api_endpoint'] = trim(Dap_Config::get('PAYPAL_API_ENDPOINT'));
		
		if (!$this->validatePaypalInput($req)) {
			header("Location: " . $req['payment_err_page'] . "?response_msg=missing request params");
			return;
		}

		$invoice = isset($req['invoice']) ? $req['invoice'] : date(YmdHis);
		$post_values = array();

		//Merchant info
		$post_values['PWD'] = urlencode($req['paypal_api_password']);
		$post_values['USER'] = urlencode($req['paypal_api_login']);
		$post_values['SIGNATURE'] = urlencode($req['paypal_api_signature']);

		//Transaction Information
		$post_values['PAYMENTACTION'] = urlencode( 'Sale');
	
		/* Construct the request string that will be sent to PayPal.
		   The variable $nvpstr contains all the variables and is a
		   name value pair string with & as a delimiter */
		
		// if trial period, set amount to the trial amount
		
		$amount = $req['amount'];
		if (strtoupper($req['is_recurring']) == "Y") {
			//$post_values['x_recurring_billing'] = "TRUE";
			if (isset($req['trial_amount']) && ($req['trial_amount'] != "0.0") && ($req['trial_amount'] != "0.00") && ($req['trial_amount'] != "0") ) {
				$amount = $req['trial_amount'];
			}
			else { 
				$amount = $req['amount'];
			}
		} 
		
		//$post_values['x_recurring_billing'] = "FALSE";
		$post_values['AMT'] = $amount;
		
		//Itemized Order Information
		$quantity = "1";
		$req['product_id'] = "1";
		
		$post_values['L_NAME0'] = urlencode($req['item_name']);
		$post_values['L_DESC0'] = urlencode($req['description']);
		$post_values['L_AMT0'] = urlencode($amount);
		$post_values['L_NUMBER0'] = urlencode($req['product_id']);
		$post_values['L_QTY0'] = urlencode($quantity);
		
		// Set request-specific fields.
		$currencyID = urlencode('USD');					// or other currency code ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
		$paymentType = urlencode('Sale');				// or 'Sale' or 'Order'
		
		$return_url = SITE_URL_DAP . "/dap/PaypalGetExpressCheckout.php";
		
		$cancel_url = trim($req['payment_cancel_page']);
		if ($cancel_url == '') {
			header("Location: " . $req['payment_err_page'] . "?response_msg=missing request params");
			logToFile("Dap_Payment:paypalExpressCheckout(). Missing cancel_url in the request");
			return;
		}
		
		if(!strstr($cancel_url,"http")) {
			$cancel_url = SITE_URL_DAP . $cancel_url;
			
		}
		logToFile("Dap_Payment:paypalExpressCheckout(). cancel_url= " . $cancel_url, LOG_DEBUG_DAP);
	
		$post_values['RETURNURL'] = urlencode(trim($return_url));
		$post_values['CANCELURL'] = urlencode($cancel_url);
		
		logToFile("Dap_Payment:paypalExpressCheckout(). methodname= " . $methodName, LOG_DEBUG_DAP);
		$post_values['L_BILLINGTYPE0'] = urlencode("RecurringPayments");
		//trim($post['description'], " \t\n\r\0\x0B.><*");
		//$post_values['L_BILLINGAGREEMENTDESCRIPTION0'] = urlencode(trim($req['description'], " \t\n\r\0\x0B.><*"));
		
		$post_values['L_BILLINGAGREEMENTDESCRIPTION0'] = urlencode(trim($req['description']));
		$post_values['CURRENCYCODE'] = urlencode(trim(Dap_Config::get('CURRENCY_TEXT')));	
		if (!isset($post_values['CURRENCYCODE']) || $post_values['CURRENCYCODE'] == '')
			$post_values['CURRENCYCODE'] = urlencode("USD");	
	
		$version = VERSION;
		$post_values['VERSION'] = urlencode($version);
		$post_values['METHOD'] = urlencode("SetExpressCheckout");
		
		// Convert to proper format for an http post
		$post_string = "&";

		foreach( $post_values as $key => $value ) {
			$post_string .= "$key=" . $value . "&"; 
			logToFile("Dap_Payment:paypalExpressCheckout(). " . $key . "=" . $value	, LOG_DEBUG_DAP);
		}
		
		$post_string = rtrim( $post_string, "& " );

		logToFile("Dap_Payment:paypalExpressCheckout(). Paypal Express Checkout Request: " . $post_string, LOG_DEBUG_DAP);
		
		$_SESSION['product_id']=$req['product_id'];
		$_SESSION['payment_gateway']=$req['payment_gateway'];
		
		// Use CURL to establish a connection, submit the post, and record the response.
		//$post_url = "https://test.authorize.net/gateway/transact.dll";
					
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $req['paypal_api_endpoint']);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
    	curl_setopt($ch, CURLOPT_POSTFIELDS,$post_string);

		//getting response from server
		$post_response = curl_exec($ch);

		$ret = $this->paypalSetExpressCheckoutResponse($req, $ch, $post_response);
		return $ret;
	}
	
	public function paypalSetExpressCheckoutResponse($req, $ch, $post_response) // Paypal express checkout
	{	
		//if the connection and send worked $post_response holds the return from Paypal
		if (curl_errno($ch) == 0) // curl worked
		{
			// This line takes the response and breaks it into an array using the specified delimiting character
			$response_array=deformatNVP($post_response);
			// The results are output to the screen in the form of an html numbered list.
			foreach ($response_array as $key => $value)
				logToFile("Dap_Payment:Dap_Payment:paypalSetExpressCheckoutResponse(). Response: " . $key . "=" . $value, LOG_DEBUG_DAP);

			/* Display the API response back to the browser.
		   If the response from PayPal was a success, display the response parameters'
		   If the response was an error, display the errors received using APIError.php.
		   */
			curl_close($ch);

			$ack = strtoupper($response_array["ACK"]);
			$aresult = array();

			if (strtoupper($ack) == "SUCCESS" || strtoupper($ack) == "SUCCESSWITHWARNING") { //approved payment
				logToFile("Dap_Payment:Dap_Payment:paypalSetExpressCheckoutResponse(). Approved payment", LOG_DEBUG_DAP);
				// Redirect to paypal.com.
				$token = urldecode($response_array["TOKEN"]);
				if(strstr(trim(Dap_Config::get('PAYPAL_API_ENDPOINT')), "sandbox"))
				  $paypalURL = "https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=$token";
				else 
  				  $paypalURL = "https://www.paypal.com/webscr&cmd=_express-checkout&token=$token";
				
				logToFile("Dap_Payment:paypalSetExpressCheckoutResponse(): REDIRECT TO Paypal URL=" . $paypalURL, LOG_DEBUG_DAP); 
				
				header("Location: " . $paypalURL);
			}
			else { 
				//	$shortMessage = $resArray["L_SHORTMESSAGE0"];
			  	$aresult['response_code'] = $response_array["L_ERRORCODE0"];
				$aresult['text'] =  $response_array["L_LONGMESSAGE0"];
				logToFile("Dap_Payment:setExpressCheckout(). Error Code: " . $aresult['response_code'] . ", Error Text: " . $aresult['text'], LOG_DEBUG_DAP); 
				sendAdminEmail("Dap_Payment:paypalSetExpressCheckoutResponse failed", "Dap_Payment:paypalSetExpressCheckoutResponse(). Failed with Error Code: " . $aresult['response_code'] . ", Error Text: " . $aresult['text']);
				
				$_SESSION['err_text']=$aresult['text'];
				header("Location: " . $req['payment_err_page'] . "?response_msg=" . $aresult['text']);
				exit;
			}
		}
		else
		{
			curl_close($ch);

			logToFile("Dap_Payment:paypalSetExpressCheckoutResponse(). Failed to connect to Paypal", LOG_DEBUG_DAP);
			sendAdminEmail("Paypal connection could not be established", "Dap_Payment:paypalSetExpressCheckoutResponse(). Paypal connection could not be established");
			
			$_SESSION['err_text']="Sorry, failed to connect to Paypal. Please retry or contact the site admin";
			header("Location: " . $req['payment_err_page'] . "?response_msg=Sorry, failed to connect to Paypal. Please retry or contact the site admin");
			exit;
		}
	}
	
	public function paypalDoExpressCheckoutResponse($req, $ch, $post_response, $purchase_order)  // Paypal do express checkout
	{	
		//if the connection and send worked $post_response holds the return from Authorize.net
		if (curl_errno($ch) == 0) // curl worked
		{
			// This line takes the response and breaks it into an array using the specified delimiting character
			$response_array=deformatNVP($post_response);
			// The results are output to the screen in the form of an html numbered list.
			foreach ($response_array as $key => $value)
				logToFile("Dap_Payment:Dap_Payment:paypalDoExpressCheckoutResponse(). Response: " . $key . "=" . $value, LOG_DEBUG_DAP);

			/* Display the API response back to the browser.
		   If the response from PayPal was a success, display the response parameters'
		   If the response was an error, display the errors received using APIError.php.
		   */
			curl_close($ch);

			$ack = strtoupper($response_array["ACK"]);
			$aresult = array();
			$post_values = array();
			
			if (strtoupper($ack) == "SUCCESS" || strtoupper($ack) == "SUCCESSWITHWARNING") { //approved payment
				// extract response params
				$this->setFirst_name($_SESSION['paymentObj']->getFirst_name());
				$this->setLast_name($_SESSION['paymentObj']->getLast_name());
				$this->setEmail($_SESSION['paymentObj']->getEmail());
				if($response_array["PAYERID"] != "")  {
 			      $this->setPayerId($response_array["PAYERID"]);
			    //$_SESSION['paymentObj']->setPayerId($response_array["PAYERID"]);
			    }
				else {
				  if($_SESSION["PAYERID"] != "")
				    $this->setPayerId($_SESSION["PAYERID"]);
				  else  
				    $this->setPayerId($_SESSION['paymentObj']->getPayerId());
				}
				
				$this->setPhone($_SESSION['paymentObj']->getPhone());
				$this->setShip_to_first_name($_SESSION['paymentObj']->getShip_to_first_name());
				$this->setShip_to_address1($_SESSION['paymentObj']->getShip_to_address1());
				$this->setShip_to_city($_SESSION['paymentObj']->getShip_to_city());
				$this->setShip_to_state($_SESSION['paymentObj']->getShip_to_state());
				$this->setShip_to_statecode($_SESSION['paymentObj']->getShip_to_statecode());
				$this->setShip_to_zip($_SESSION['paymentObj']->getShip_to_zip());
				$this->setShip_to_country($_SESSION['paymentObj']->getShip_to_country());
				$this->setShip_to_countrycode($_SESSION['paymentObj']->getShip_to_countrycode());
									
				logToFile("Dap_Payment:Dap_Payment:paypalDoExpressCheckoutResponse(). Approved payment for user: " . $_SESSION['paymentObj']->getEmail(), LOG_DEBUG_DAP);
				$aresult['payer_email'] = $_SESSION['payer_email'];
				$aresult['email'] = $_SESSION['paymentObj']->getEmail();
				$aresult['phone'] = $_SESSION['paymentObj']->getPhone();
				$aresult['fax'] = $_SESSION['paymentObj']->getFax();
				$aresult['payment_gateway'] = "paypal";
				
				//	$_SESSION['paypal_express_token'] = $response_array['TOKEN'];	
			// Use same transaction Id for direct payment and notfication so only one will be recorded in transaction, the other will fail with integrity constraint that has been handled as 'success' response in the code
				
				$num_cart = $_SESSION['num_cart'];
				for ($i=0;$i<$num_cart;$i++) {
					// set params
					$aresult['txn_id'] =  $response_array['TRANSACTIONID'] . ":" . $i;  
					$aresult['payment_num'] = $i; //first payment via setexpresscheckout
					$aresult['item_name'] = $purchase_order['L_NAME'.$i];
					$aresult['description'] = $purchase_order['L_DESC'.$i];
					$aresult['amount'] = $purchase_order['L_AMT'.$i];
					$aresult['mc_gross'] = $purchase_order['L_AMT'.$i];
					$aresult['invoice'] = isset($req['invoice']) ? $req['invoice'] : date(YmdHis);
					$aresult['txn_type'] = "cart";
					
					if (!$this->processPaymentResponse ($aresult, "Dap_Payment"))
					{
						$subject="DAP transaction recording failed for the user: " . $aresult['email'];
						$body="Dap_Payment:paypalDoExpressCheckoutResponse(): " . $req['payment_gateway'] . " payment transaction successfully processed but DAP transaction recording failed for the user: " . $aresult['email'] . ", who bought the item: " . $aresult['item_name'] . ", for the amount: $" . $aresult['amount'] . ". This transaction will have to be handled manually within DAP admin console";
						sendAdminEmail($subject, $body);
						logToFile($body, LOG_DEBUG_DAP);
					}
							
					$aresult['is_recurring'] = $purchase_order['L_ISRECUR'.$i];
					
					$aresult['recurring_amount'] = $purchase_order['L_RECURAMT'.$i];
					$aresult['recurring_cycle_1'] = $purchase_order['L_RECUR1'.$i];
					$aresult['recurring_cycle_2'] = $purchase_order['L_RECUR2'.$i];
					$aresult['recurring_cycle_3'] = $purchase_order['L_RECUR3'.$i];
					$aresult['total_occurrences'] = $purchase_order['L_TOTALOCCUR'.$i];
					$aresult['payment_gateway'] = "paypal";
					$aresult['recur_desc'] = $purchase_order['L_DESC'.$i];
					
					if ($aresult['is_recurring'] == "Y") { 
						logToFile("RECUR ITEM NAME:" . $aresult['item_name'], LOG_DEBUG_DAP);
						logToFile("RECUR ITEM AMT:" . $aresult['amount'], LOG_DEBUG_DAP);
														
						//  success page for the successful initial transaction via doexpresscheckout 
						//  but send admin email for ARB (recurring) failure
						if (!$this->create_paypal_express_recurring_subscription ($req, $aresult))
						{
							$subject="Recurring subscription failed for the user: " . $aresult['email'];
							$body="Dap_Payment:paypalDoExpressCheckoutResponse():failed to process the recurring transaction for user: " . $aresult['email'] . ", who bought the item: " . $aresult['item_name'] . ", for the amount: $" . $aresult['amount'] . ". The recurring payment transaction will have to be handled manually within paypal.com.";
							logToFile($body, LOG_DEBUG_DAP);
							sendAdminEmail($subject, $body);
						}
						else {
							$msg="Recurring subscription created for the user: " . $aresult['email'];
							logToFile($msg, LOG_DEBUG_DAP);
						}
					}
			
				}
				logToFile("Dap_Payment:paypalDoExpressCheckoutResponse(): load user by email", LOG_DEBUG_DAP);
				logToFile("Dap_Payment:paypalDoExpressCheckoutResponse(): load user by email, user=".$aresult['email'], LOG_DEBUG_DAP);
				
				$user = Dap_User::loadUserByEmail($aresult['email']);
				if(!isset($user)){
				  logToFile("Dap_Payment:paypalDoExpressCheckoutResponse(): load user by their paypal email");
				  $user = Dap_User::loadUserByPaypalEmail($aresult['email']);
				  
				}
							
				if(isset($user)) {
					$aresult['email'] = $user->getEmail();
					logToFile("Dap_Payment:paypalDoExpressCheckoutResponse(): redirecting to authenticate.php to auto login user". $record_id);
					if (isset($req['autologin_redirect']) && ($req['autologin_redirect'] != "")) {
						logToFile("Dap_Payment:paypalDoExpressCheckoutResponse(): autologin redirect set, redirecting to " . $req['autologin_redirect']);
						logToFile("Dap_Payment:paypalDoExpressCheckoutResponse(): autologin set,  /dap/authenticate.php?email=" . $aresult['email'] . "&password=" . $user->getPassword() . "&submitted=Y&request=".$req['autologin_redirect']);
						header("Location: /dap/authenticate.php?email=" . $aresult['email'] . "&password=" . $user->getPassword() . "&submitted=Y&request=".$req['autologin_redirect']);
						return;
					}
					else {
						logToFile("Dap_Payment:paypalDoExpressCheckoutResponse(): autologin set, payment_succ_page set, redirecting to " . $req['payment_succ_page']);
						logToFile("Dap_Payment:paypalDoExpressCheckoutResponse(): autologin set,  /dap/authenticate.php?email=" . $aresult['email'] . "&password=" . $user->getPassword() . "&submitted=Y&request=".$req['payment_succ_page']);
								
						header("Location: /dap/authenticate.php?email=" . $aresult['email'] . "&password=" . $user->getPassword() . "&submitted=Y&request=".$req['payment_succ_page']);
						return;
					}
				}
				else {
					$aresult['text'] =  "Sorry, could not create membership account. Please contact the site administrator";
					header("Location: " . $_SESSION['payment_err_page'] . "?response_msg=" . $aresult['text']);	
				}
			}
			else { 
				//	$shortMessage = $resArray["L_SHORTMESSAGE0"];
			  	$aresult['response_code'] = $response_array["L_ERRORCODE0"];
				$aresult['text'] =  $response_array["L_LONGMESSAGE0"];
				
				logToFile("Dap_Payment:paypalDoExpressCheckoutResponse(). Error Code: " . $aresult['response_code'] . ", Error Text: " . $aresult['text'] . " , PaymentStatus: " . $response_array["PAYMENTSTATUS"], LOG_DEBUG_DAP); 
				
				sendAdminEmail("Dap_Payment:paypalDoExpressCheckoutResponse failed", "Dap_Payment:paypalGetExpressCheckout(). Failed with Error Code: " . $aresult['response_code'] . ", Error Text: " . $aresult['text']);
				
				$_SESSION['err_text']=$aresult['text'];
				// redirect to upsell page upon success
				header("Location: " . $_SESSION['payment_err_page'] . "?response_msg=" . $aresult['text']);
				
				return;
			}
		}
		else
		{
			curl_close($ch);

			logToFile("Dap_Payment:paypalDoExpressCheckoutResponse(). Failed to connect to Paypal", LOG_DEBUG_DAP);
			sendAdminEmail("Paypal connection could not be established", "Dap_Payment:paypalDoExpressCheckoutResponse(). Paypal connection could not be established for the customer = " . $aresult['email']);
			
			$_SESSION['err_text']="Sorry, failed to connect to Paypal. Please retry or contact the site admin";
			header("Location: " . $req['payment_err_page'] . "?response_msg=Sorry, failed to connect to Paypal. Please retry or contact the site admin");
			return;
		}
	}

	public function paypalGetExpressCheckout($req)  // Paypal do express checkout
	{
		$req['paypal_api_login']	= trim(Dap_Config::get('PAYPAL_API_LOGIN'));
		$req['paypal_api_password'] = trim(Dap_Config::get('PAYPAL_API_PASSWORD'));;
		$req['paypal_api_signature'] = trim(Dap_Config::get('PAYPAL_API_SIGNATURE'));
		$req['paypal_api_endpoint'] = trim(Dap_Config::get('PAYPAL_API_ENDPOINT'));
		
		if (!$this->validatePaypalInput($req)) {
			header("Location: " . $req['payment_err_page'] . "?response_msg=missing request params");
			return;
		}
	
		$post_values = array();
		
		//Merchant info
		$post_values['PWD'] = urlencode($req['paypal_api_password']);
		$post_values['USER'] = urlencode($req['paypal_api_login']);
		$post_values['SIGNATURE'] = urlencode($req['paypal_api_signature']);
		$post_values['TOKEN'] = urlencode($_SESSION['paypal_express_token']);
		$version = VERSION;
		$post_values['VERSION'] = urlencode($version);
		$post_values['METHOD'] = urlencode("GetExpressCheckoutDetails");
		// Convert to proper format for an http post
		$post_string = "&";

		foreach( $post_values as $key => $value ) {
			$post_string .= "$key=" . $value . "&"; 
			logToFile("Dap_Payment:paypalGetExpressCheckout(). " . $key . "=" . $value	, LOG_DEBUG_DAP);
		}
		
		$post_string = rtrim( $post_string, "& " );

		logToFile("Dap_Payment:paypalGetExpressCheckout(). Request: " . $post_string, LOG_DEBUG_DAP);
		
		// Use CURL to establish a connection, submit the post, and record the response.
						
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $req['paypal_api_endpoint']);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$post_string);

		//getting response from server
		$post_response = curl_exec($ch);
		if (curl_errno($ch) == 0) // curl worked
		{
			// This line takes the response and breaks it into an array using the specified delimiting character
			$response_array=deformatNVP($post_response);
			// The results are output to the screen in the form of an html numbered list.
			foreach ($response_array as $key => $value)
				logToFile("Dap_Payment:Dap_Payment:paypalGetExpressCheckout(). Response: " . $key . "=" . $value, LOG_DEBUG_DAP);

			/* Display the API response back to the browser.
		   If the response from PayPal was a success, display the response parameters'
		   If the response was an error, display the errors received using APIError.php.
		   */
			curl_close($ch);

			$ack = strtoupper($response_array["ACK"]);
			$aresult = array();
			
			if (strtoupper($ack) == "SUCCESS" || strtoupper($ack) == "SUCCESSWITHWARNING") { //approved payment
				// extract response params
				$this->setFirst_name($response_array['FIRSTNAME']);
				$this->setLast_name($response_array['LASTNAME']);
				$this->setEmail($response_array['EMAIL']);
				$this->setPayerId($response_array['PAYERID']);
				$this->setPhone($response_array['SHIPTOPHONENUM']);
				$this->setShip_to_first_name($response_array['SHIPTONAME']);
				$this->setShip_to_address1($response_array['SHIPTOSTREET']);
				$this->setShip_to_city($response_array['SHIPTOCITY']);
				$this->setShip_to_state($response_array['SHIPTOSTATE']);
				$this->setShip_to_statecode($response_array['SHIPTOSTATE']);
				$this->setShip_to_zip($response_array['SHIPTOZIP']);
				$this->setShip_to_country($response_array['SHIPTOCOUNTRY']);
				$this->setShip_to_countrycode($response_array['SHIPTOCOUNTRYCODE']);
				
				$_SESSION['PAYERID']=$response_array['PAYERID'];
				
				$_SESSION['paymentObj']=$this;
			 	$_SESSION['payer_email']=$response_array['EMAIL'];
				logToFile("Dap_Payment:Dap_Payment:paypalGetExpressCheckout(). PAYER EMAIL= " . $_SESSION['payer_email'], LOG_DEBUG_DAP);
				
				logToFile("Dap_Payment:Dap_Payment:paypalGetExpressCheckout(). Approved payment for user: " . $_SESSION['paymentObj']->getEmail(), LOG_DEBUG_DAP);
			// Use same transaction Id for direct payment and notfication so only one will be recorded in transaction, the other will fail with integrity constraint that has been handled as 'success' response in the code
				
				$aresult['txn_id'] =  $response_array['TRANSACTIONID'] . ":0";  
				logToFile("Dap_Payment:Dap_Payment:paypalGetExpressCheckout(): Payment transaction ID=" . $aresult['txn_id'], LOG_DEBUG_DAP);
				$post_values['L_NAME' . $i] = $req['item_name'];
				$post_values['L_DESC' . $i] = $req['description'];
				$post_values['L_AMT' . $i] = $amount;
				$post_values['L_NUMBER' . $i] = $product_id;
				$post_values['L_QTY' . $i] = $quantity;
				
				
				// set params
				$aresult['payment_num'] = "0"; //first payment via setexpresscheckout
				$aresult['item_name'] = $_SESSION['item_name'];
				$aresult['description'] = $_SESSION['description'];
				
				$aresult['amount'] = $_SESSION['amount'];
				$aresult['trial_amount'] = $_SESSION['trial_amount'];
				
				$invoice = isset($req['invoice']) ? $req['invoice'] : date(YmdHis);
				$aresult['invoice'] = $invoice;
				$aresult['payer_email'] = $this->getEmail();
				$aresult['email'] = $this->getEmail();
				$aresult['phone'] = $this->getPhone();
				$aresult['fax'] = $this->getFax();
				$aresult['is_recurring'] = $_SESSION['is_recurring'];
				$aresult['recurring_cycle_1'] = $_SESSION['recurring_cycle_1'];
				$aresult['recurring_cycle_2'] = $_SESSION['recurring_cycle_2'];
				$aresult['recurring_cycle_3'] = $_SESSION['recurring_cycle_3'];
				$aresult['total_occurrences'] = $_SESSION['total_occurrences'];
				$aresult['payment_gateway'] = $_SESSION['payment_gateway'];
				
				if ($_SESSION['is_recurring']) 
					$_SESSION['recurring'] = "Y";
					
				$this->paypalAddtoCart($aresult);
				logToFile("Dap_Payment:processPaymentResponse(): redirecting...". $req['payment_succ_page']);
				header("Location: " . $_SESSION['payment_succ_page']);
				
				return;
			}
			else { 
				//	$shortMessage = $resArray["L_SHORTMESSAGE0"];
			  	$aresult['response_code'] = $response_array["L_ERRORCODE0"];
				$aresult['text'] =  $response_array["L_LONGMESSAGE0"];
			
				logToFile("Dap_Payment:paypalGetExpressCheckout(). Error Code: " . $aresult['response_code'] . ", Error Text: " . $aresult['text'], LOG_DEBUG_DAP); 
				
				sendAdminEmail("Dap_Payment:paypalGetExpressCheckout failed", "Dap_Payment:paypalGetExpressCheckout(). Failed with Error Code: " . $aresult['response_code'] . ", Error Text: " . $aresult['text']);
				
				$_SESSION['err_text']=$aresult['text'];
				// redirect to upsell page upon success
				header("Location: " . $_SESSION['payment_err_page'] . "?response_msg=" . $aresult['text'] );
				
				return;
			}
		}
		else
		{
			curl_close($ch);

			logToFile("Dap_Payment:paypalGetExpressCheckout(). Failed to connect to Paypal", LOG_DEBUG_DAP);
			sendAdminEmail("Paypal connection could not be established", "Dap_Payment:paypalGetExpressCheckout(). Paypal connection could not be established for the customer = " . $aresult['email']);
			
			$_SESSION['err_text']="Sorry, failed to connect to Paypal. Please retry or contact the site admin";
			header("Location: " . $_SESSION['payment_err_page'] . "?response_msg=Sorry, failed to connect to Paypal. Please retry or contact the site admin");
			return;
		}
	
	}
	
	public function create_paypal_express_recurring_subscription($req, $aresult)  // ARB
	{
		$req['paypal_api_login']	= trim(Dap_Config::get('PAYPAL_API_LOGIN'));
		$req['paypal_api_password'] = trim(Dap_Config::get('PAYPAL_API_PASSWORD'));;
		$req['paypal_api_signature'] = trim(Dap_Config::get('PAYPAL_API_SIGNATURE'));
		$req['paypal_api_endpoint'] = trim(Dap_Config::get('PAYPAL_API_ENDPOINT'));
		
		//subscription name... same as product name
		$item_name = $aresult['item_name'];
			
		$post_values = array();

		//Merchant info
		$post_values['PWD'] = urlencode($req['paypal_api_password']);
		$post_values['USER'] = urlencode($req['paypal_api_login']);
		$post_values['SIGNATURE'] = urlencode($req['paypal_api_signature']);
		
		$post_values['TOKEN'] = urlencode($_SESSION['paypal_express_token']);
		if($_SESSION["PAYERID"] != "")
		  $post_values['PAYERID'] = urlencode($_SESSION["PAYERID"]);
		else 
		  $post_values['PAYERID'] = urlencode($_SESSION['paymentObj']->getPayerId());
		
			
		//Transaction Information
		$post_values['PAYMENTACTION'] = urlencode( 'Sale');
		$post_values['AMT'] = $aresult['recurring_amount'];
		
		//Itemized Order Information
		$quantity = "1";
		$aresult['product_id'] = "1";
				
		logToFile("Dap_Payment:create_paypal_express_recurring_subscription() - recurring amount =" . $aresult['recurring_amount'], LOG_DEBUG_DAP);
		
		$post_values['L_NAME0'] = urlencode($aresult['item_name']);
		$post_values['L_DESC0'] = urlencode($aresult['item_name']);
		$post_values['L_AMT0'] = urlencode($aresult['recurring_amount']);
		$post_values['L_NUMBER0'] = urlencode($aresult['product_id']);
		$post_values['L_QTY0'] = urlencode($quantity);
	
		//Billing Information
		$post_values['FIRSTNAME'] = urlencode($this->getFirst_name());
		$post_values['LASTNAME'] = urlencode($this->getLast_name());
		$post_values['STREET'] = urlencode($this->getAddress1());
		$post_values['CITY'] = urlencode($this->getCity());
		$post_values['STATE'] = urlencode($this->getStateCode());
		$post_values['ZIP'] = urlencode($this->getZip());
		$post_values['COUNTRYCODE'] = $this->getCountryCode();	
		
		//$post_values['CURRENCYCODE'] = urlencode("USD");		
		$post_values['CURRENCYCODE'] = urlencode(trim(Dap_Config::get('CURRENCY_TEXT')));	
		if (!isset($post_values['CURRENCYCODE']) || $post_values['CURRENCYCODE'] == '')
			$post_values['CURRENCYCODE'] = urlencode("USD");	
			
		$post_values['PHONENUM'] = urlencode($this->getPhone());	
		$post_values['EMAIL'] = urlencode($this->getEmail());	

		//Shipping Information  
		$post_values['SHIPTONAME'] = urlencode($this->getShip_to_first_name() . ' ' . $this->getShip_to_last_name());
		$post_values['SHIPTOSTREET'] = urlencode($this->getShip_to_address1());
		$post_values['SHIPTOCITY'] = urlencode($this->getShip_to_city());
		$post_values['SHIPTOSTATE'] = urlencode($this->getShip_to_statecode());
		
		$post_values['SHIPTOZIP'] = urlencode($this->getShip_to_zip());
		$post_values['SHIPTOCOUNTRY'] = urlencode($this->getShip_to_countrycode());
		
		$profileStartDate = date('Y-m-d', strtotime("+".$aresult['recurring_cycle_1']. " day"));
		$post_values['PROFILESTARTDATE'] = urlencode($profileStartDate.'T00:00:00Z');
		$post_values['PROFILEREFERENCE'] = urlencode(SITE_URL_DAP);
		
		$post_values['DESC'] =  urlencode($aresult['recur_desc']);
		$post_values['BILLINGPERIOD'] = urlencode('Day');
		$post_values['BILLINGFREQUENCY'] = urlencode($aresult['recurring_cycle_3']);
		$post_values['TOTALBILLINGCYCLES'] = urlencode($aresult['total_occurrences']);
		
		//$notify_url = SITE_URL_DAP . "/dap/dap-paypal.php";
		//$notify_url= str_replace ( "http:", "https:", $notify_url );
		
		//$post_values['NOTIFYURL'] = urlencode($notify_url);
	
		logToFile("Dap_Payment:create_paypal_express_recurring_subscription() - profile description=" . $post_values['DESC'],LOG_DEBUG_DAP);
		
		// Convert to proper format for an http post
		$post_string = "&";

		foreach( $post_values as $key => $value ) {
			$post_string .= "$key=" . $value . "&"; 
			logToFile("Dap_Payment:create_paypal_express_recurring_subscription(). Paypal Recurring Billing request " . $key . "=" . $value, LOG_DEBUG_DAP);
		}
		$post_string = rtrim( $post_string, "& " );

		// Use CURL to establish a connection, submit the post, and record the response.
		//$post_url = "https://test.authorize.net/gateway/transact.dll";
		
		$methodName = "CreateRecurringPaymentsProfile";
				
		$_SESSION['product_id']=$aresult['product_id'];
		$_SESSION['payment_gateway']=$aresult['payment_gateway'];
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $req['paypal_api_endpoint']);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
    	$version=VERSION;
		//check if version is included in $nvpStr else include the version.
		if(strlen(str_replace('VERSION=', '', strtoupper($post_string))) == strlen($post_string)) {
			$post_string = "&VERSION=" . urlencode($version) . $post_string;	
		}
	
		$nvpreq="METHOD=".urlencode($methodName).$post_string;
	
		//setting the nvpreq as POST FIELD to curl
		curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);

		//getting response from server
		$post_response = curl_exec($ch);

		if (curl_errno($ch) == 0) // transaction approved
		{
			$response_array=deformatNVP($post_response);
			foreach ($response_array as $key => $value)
				logToFile("Dap_Payment:create_paypal_express_recurring_subscription(). Paypal Recurring Billing Response: key=" . $key . " ,value=" . $value, LOG_DEBUG_DAP);

			curl_close($ch);
			$aresult = array();
			$ack = strtoupper($response_array["ACK"]);
			if (!strcasecmp ($ack,"SUCCESS")) { //SUCCESS
				$_SESSION['reshash']=$response_array;
				logToFile("Dap_Payment:create_paypal_express_recurring_subscription(). Dap_Payment successfully processed by Paypal", LOG_DEBUG_DAP);
				return TRUE;
			}
			else {
				$aresult['response_code'] = $response_array["L_ERRORCODE0"];
				$aresult['text'] =  $response_array["L_LONGMESSAGE0"];
				logToFile("Dap_Payment:create_paypal_express_recurring_subscription(). Error Code: " . $resultCode . " Reason Code: " . $code . " text: " . $text . " Subs Id: " . $subscription_id , LOG_DEBUG_DAP); 
				return FALSE;
			}
		}	
		else
		{
			logToFile("Dap_Payment:create_paypal_express_recurring_subscription(). Failed to connect to Paypal", LOG_DEBUG_DAP);
			sendAdminEmail("Paypal connection could not be established for " . $aresult['email'], "Dap_Payment:create_paypal_express_recurring_subscription(). Paypal connection for recurring subscription could not be established for: " . $aresult['email'] . " for product = " . $aresult['item_name']);
			return FALSE;
		}

	}  
	
	//AddToCart
	public function addToCart($req)  // Paypal express checkout
	{	
		logToFile("Dap_Payment:addToCart: Enter");
		$product = Dap_Product::loadProductByName(trim($req['item_name']));
		logToFile("Dap_Payment:addToCart: Loaded product by name=".$req['item_name']);
		
		if(!isset($product)) {
		  return;			
		} 
		
		$item_name = $product->getName();
		$description = $product->getDescription();
		
		$trial_amount = $product->getTrial_price();
		$amount = $product->getPrice();
		$recur_amount = $product->getPrice();
		$is_recurring = $product->getIs_recurring();
		
		$recurring_cycle_1 = $product->getRecurring_cycle_1();
		$recurring_cycle_2 = $product->getRecurring_cycle_2();
		$recurring_cycle_3 = $product->getRecurring_cycle_3();
				
		$total_occurrences = $product->getTotal_occur();
	
		$num_cart = 1;
		
		if ($req['is_submitted'] == "Y")
			$_SESSION['is_submitted'] = "Y";
			
		
		if (!isset($_SESSION['num_cart']) || ($_SESSION['num_cart'] == ''))	
			$_SESSION['num_cart'] = $num_cart;
		else {
			$num_cart = $_SESSION['num_cart']+1;
			$_SESSION['num_cart'] = $num_cart;
		}
		
		logToFile("Dap_Payment:num_cart(). " . $_SESSION['num_cart'], LOG_DEBUG_DAP);
		
		if (strtoupper($is_recurring) == "Y") {
			$recur_count =1;
			if (!isset($_SESSION['recur_count']) || ($_SESSION['recur_count'] == ''))
				$_SESSION['recur_count'] = $recur_count;
			else {
				$recur_count = $_SESSION['recur_count']+1;
				$_SESSION['recur_count'] = $recur_count;
			}
			
			
			if( (isset($_REQUEST["freeTrial"])) && ($_REQUEST["freeTrial"] == "Y")) {
				$_SESSION["freeTrial"]=$_REQUEST["freeTrial"];
				$freeTrial=$_SESSION["freeTrial"];
			}
			
			//$post_values['x_recurring_billing'] = "TRUE";
			if ( ($freeTrial == "Y") || ( (isset($trial_amount) && ($trial_amount != "0.0") && ($trial_amount != "0.00") && ($trial_amount != "0") ))) {
				$amount = $trial_amount;
			}
			else { 
				$amount = $amount;
			}
		} 
		
		$match_found = false;
		$num_cart_items = $_SESSION['num_cart'];
		$index = 0;
		
		for ($i=0;$i<$num_cart_items;$i++) {
			logToFile("Dap_Payment:addtoCart(). session params, i=" . $i . ", item_name=" . $_SESSION['product_details'][$i]['L_NAME'.$i], LOG_DEBUG_DAP);
			if ( strcmp ($_SESSION['product_details'][$i]['L_NAME'.$i], $item_name) == 0) {
				logToFile("Dap_Payment:addtoCart(). " . match_found . "=Y", LOG_DEBUG_DAP);
				$match_found = true;
				$index = $i;
				break;
			}
		}
		
		//Itemized Order Information
		$quantity=1;
		$product_id=1;
		
		$i = $num_cart_items - 1;
		
		$product_id = $product->getId();
		
		$post_values['L_NAME' . $i] = $item_name;
		$post_values['L_DESC' . $i] = $description;
		
		if ($match_found) {
			logToFile("Dap_Payment:AddtoCart(). match found, num_cart=" . $_SESSION['num_cart'], LOG_DEBUG_DAP);
		
			$_SESSION['product_details'][$index]['L_TOTALAMT'.$index] = ($amount *  ($_SESSION['product_details'][$index]['L_QTY'.$index] + 1));
			$_SESSION['product_details'][$index]['L_TOTALRECURAMT'.$index] = ($amount *  ($_SESSION['product_details'][$index]['L_QTY'.$index] + 1));																																				  

			$_SESSION['product_details'][$index]['L_QTY'.$index] = $_SESSION['product_details'][$index]['L_QTY'.$index] + $quantity;
			
			$_SESSION['num_cart'] = $_SESSION['num_cart'] - 1;
			
			if ($_SESSION['product_details'][$index]['L_ISRECUR'.$index] == "Y") {
					$_SESSION['recur_count'] = $_SESSION['recur_count'] - 1;
			}
			logToFile("Dap_Payment:addtoCart(). num_cart after removing " . $_SESSION['num_cart'], LOG_DEBUG_DAP);
			logToFile("Dap_Payment:addtoCart(). recur_count after removing " . $_SESSION['recur_count'], LOG_DEBUG_DAP);
		}
		else {
			$post_values['L_TOTALAMT' . $i] = $amount;
			$post_values['L_TOTALRECURAMT' . $i] = $amount;
			$post_values['L_QTY' . $i] = $quantity;
		
		
			$post_values['L_AMT' . $i] = $amount;
			$post_values['L_NUMBER' . $i] = $product_id;
			$post_values['L_ISRECUR' . $i] = $is_recurring;
			
			$post_values['L_RECURAMT' . $i] = $recur_amount;
			$post_values['L_RECUR1' . $i] = $recurring_cycle_1;
			$post_values['L_RECUR2' . $i] = $recurring_cycle_2;
			$post_values['L_RECUR3' . $i] = $recurring_cycle_3;
			$post_values['L_TOTALOCCUR' . $i] = $total_occurrences;
		
			$_SESSION['product_details'][$i]=$post_values;
			
			$_SESSION['product_id']=$product_id;
			logToFile("Dap_Payment:addtoCart(). set post params in session", LOG_DEBUG_DAP);
			
		}
		
		foreach( $post_values as $key => $value ) {
			$post_string .= "$key=" . $value . "&"; 
			logToFile("Dap_Payment:addtoCart(). " . $key . "=" . $value	, LOG_DEBUG_DAP);
		}

		
		logToFile("Dap_Payment:AddtoCart(). product amount " . $amount, LOG_DEBUG_DAP);
		logToFile("Dap_Payment:AddtoCart(). num_cart=" . $_SESSION['num_cart'], LOG_DEBUG_DAP);
		
		$_SESSION['payment_gateway']=$req['payment_gateway'];
		
		if (!isset($_SESSION['checkoutPage']) || (isset($req['checkoutPage'])))
			$_SESSION['checkoutPage'] = $req['checkoutPage'];

		if (!isset($_SESSION['continuePage']) || (isset($req['continuePage'])))
			$_SESSION['continuePage'] = $req['continuePage'];
	
		if (!isset($_SESSION['cartSummaryPage']) || (isset($req['cartSummaryPage'])))
			$_SESSION['cartSummaryPage'] = $req['cartSummaryPage'];
		
		if (!isset($_SESSION['blogpath']) || (isset($req['blogpath'])))
			$_SESSION['blogpath'] = $req['blogpath'];
		
		if (!isset($_SESSION['wpfoldername']) || (isset($req['wpfoldername'])))
			$_SESSION['wpfoldername'] = $req['wpfoldername'];
		
		if (!isset($_SESSION['lldocroot']) && (isset($req['lldocroot'])))
			$_SESSION['lldocroot'] = $req['lldocroot'];

		if (!isset($_SESSION['gatracking']) && (isset($req['gatracking'])))
			$_SESSION['gatracking'] = $req['gatracking'];
			
		$_SESSION['payment_succ_page'] = $req['payment_succ_page'];
		$_SESSION['paypal_landing_page'] = $req['paypal_landing_page'];
		
		$_SESSION['btntype'] = $req['btntype'];	
		$_SESSION["is_last_upsell"] = $req["is_last_upsell"];
		$_SESSION["stripe_instant_recurring_charge"] = $req["stripe_instant_recurring_charge"];
		
		
		logToFile("DAP_Payment.class.php, AddtoCart(). is_last_upsell =" . $_SESSION['is_last_upsell'],LOG_DEBUG_DAP); 
		
		$_SESSION['post'] = "Y";
		
		if(isset($_REQUEST["showcc"]))
			$_SESSION["showcc"]=$_REQUEST["showcc"];
			
		if(isset($_REQUEST["showpaypal"]))
			$_SESSION["showpaypal"]=$_REQUEST["showpaypal"];
		
		logToFile("DAP_Payment.class.php, AddtoCart(). showcc =" . $_SESSION['showcc'],LOG_DEBUG_DAP); 
		logToFile("DAP_Payment.class.php, AddtoCart(). showpaypal =" . $_SESSION['showpaypal'],LOG_DEBUG_DAP); 
		logToFile("DAP_Payment.class.php, AddtoCart(). payment_succ_page =" . $_SESSION['payment_succ_page'],LOG_DEBUG_DAP); 
		logToFile("DAP_Payment.class.php, AddtoCart(). checkout page url =" . $req['checkoutPage'],LOG_DEBUG_DAP); 
		logToFile("DAP_Payment.class.php, AddtoCart(). continue page url =" . $req['continuePage'],LOG_DEBUG_DAP); 	
		
		logToFile("Dap_Payment:addtoCart(). Complete", LOG_DEBUG_DAP);
		
		if ($req['cartSummaryPage'] != "") {
			header("Location: " . $req['cartSummaryPage']);
		}
		else {
			header("Location: /dap/CheckoutConfirm.php");
		}
		
		//$redir = SITE_URL_DAP."/dap/PaypalCheckoutConfirm.php";
			
		//header("Location: " . $redir);
		return;
	}

	//RemoveFromCart
	public function removeFromCart($itemname, $cartSummaryPage)  // Paypal express checkout
	{	
		$post_values = array();
		$num_cart_item = $_SESSION['num_cart'];
		logToFile("Dap_Payment:removeFromCart(). remove item=" . $itemname, LOG_DEBUG_DAP);
		logToFile("Dap_Payment:removeFromCart(). numcart=" . $_SESSION['num_cart'], LOG_DEBUG_DAP);
		
		$j=0;
		
		$product = Dap_Product::loadProductByName(trim($itemname));
		logToFile("Dap_Payment:addToCart: Loaded product by name=".$itemname);
		
		if(!isset($product)) {
		  return;			
		} 
		
		$item_name = $product->getName();
		$description = $product->getDescription();
		
		$trial_amount = $product->getTrial_price();
		$amount = $product->getPrice();
		
		$is_recurring = $product->getIs_recurring();
		
		$recurring_cycle_1 = $product->getRecurring_cycle_1();
		$recurring_cycle_2 = $product->getRecurring_cycle_2();
		$recurring_cycle_3 = $product->getRecurring_cycle_3();
				
		$total_occurrences = $product->getTotal_occur();
		
		
		if (isset($trial_amount) && ($trial_amount != "0.0") && ($trial_amount != "0.00") && ($trial_amount != "0") ) {
			$amount = $trial_amount;
		}
		else { 
			$amount = $amount;
		}
			
		for ($i=0;$i<$num_cart_item;$i++) {
			logToFile("Dap_Payment:removeFromCart(). session itemname =" . $_SESSION['product_details'][$i]['L_NAME'.$i], LOG_DEBUG_DAP);
			if (strcmp($_SESSION['product_details'][$i]['L_NAME'.$i], $itemname) == 0) {
				$removeindex = $i;
				logToFile("Dap_Payment:removeFromCart(). remove index=" . $removeindex, LOG_DEBUG_DAP);
				logToFile("Dap_Payment:removeFromCart(). recur count=" . $_SESSION['recur_count'], LOG_DEBUG_DAP);
			
				$_SESSION['num_cart']--;
				
				if ($_SESSION['product_details'][$i]['L_ISRECUR'.$i] == "Y") {
					$_SESSION['recur_count'] = $_SESSION['recur_count'] - 1;
				}
				
				unset($_SESSION['new_amount']);
				unset($_SESSION['product_details'][$i]);
			}
			else {
				$post_values[$j]['L_NAME'.$j] = $_SESSION['product_details'][$i]['L_NAME'.$i];
				$post_values[$j]['L_DESC'.$j] = $_SESSION['product_details'][$i]['L_DESC'.$i];
				$post_values[$j]['L_AMT'.$j] = $_SESSION['product_details'][$i]['L_AMT'.$i];
				$post_values[$j]['L_TOTALAMT'.$j] = $_SESSION['product_details'][$i]['L_TOTALAMT'.$i];
				$post_values[$j]['L_TOTALRECURAMT'.$j] = $_SESSION['product_details'][$i]['L_TOTALRECURAMT'.$i];
				$post_values[$j]['L_NUMBER'.$j] = $_SESSION['product_details'][$i]['L_NUMBER'.$i];
				$post_values[$j]['L_QTY'.$j] = $_SESSION['product_details'][$i]['L_QTY'.$i];
				$post_values[$j]['L_ISRECUR'.$j] = $_SESSION['product_details'][$i]['L_ISRECUR'.$i];
				$post_values[$j]['L_RECURAMT'.$j] = $_SESSION['product_details'][$i]['L_RECURAMT'.$i];
				$post_values[$j]['L_RECUR1'.$j] = $_SESSION['product_details'][$i]['L_RECUR1'.$i];
				$post_values[$j]['L_RECUR2'.$j] = $_SESSION['product_details'][$i]['L_RECUR2'.$i];
				$post_values[$j]['L_RECUR3'.$j] = $_SESSION['product_details'][$i]['L_RECUR3'.$i];
				$post_values[$j]['L_TOTALOCCUR'.$j] = $_SESSION['product_details'][$i]['L_TOTALOCCUR'.$i];
				$_SESSION['product_details'][$j] = $post_values[$j];
				$j++;
			}
		}
		
		/*for ($i=0;$i<$j;$i++) {
		 $_SESSION['product_details'][$i] = $post_values[$i];
		}
			*/
		logToFile("Dap_Payment:removeFromCart(). Complete", LOG_DEBUG_DAP);
			
		if ($_REQUEST['cartSummaryPage'] != "") {
			header("Location: " . $_REQUEST['cartSummaryPage']);
		}
		if ($cartSummaryPage != "") {
			header("Location: " . $cartSummaryPage);
		}
		else {
			header("Location: /dap/CheckoutConfirm.php");
		}
		
		return;
	}
	
	public function emptyCC() {
		if(isset($_SESSION['paymentObj'])) {
			//$_SESSION['paymentObj']="";
			$_SESSION['paymentObj']->setCard_type("");
			$_SESSION['paymentObj']->setCard_num("");
			$_SESSION['paymentObj']->setExp_date("");
			$_SESSION['paymentObj']->setCard_code("");
			
			//$_SESSION["is_last_upsell"]="";
			
			logToFile("Dap_Payment.class.php:  Emptied CC info", LOG_DEBUG_DAP);	
		}
	}
	
	public function emptyCart()  // Paypal express checkout 
	{
	// EMPTY CART
  
	  logToFile("Dap_Payment.class.php:  create_authnet_subscription(): Empting cart items", LOG_DEBUG_DAP);
	  
	  unset($_SESSION['num_cart']);
	  $_SESSION['num_cart']=0;
	  unset($_SESSION['recur_count']);
	  $_SESSION['recur_count']=0;
	  unset($_SESSION['product_details']);
  //	unset($_SESSION['new_amount']);
  //	unset($_SESSION['couponCode']);

  
	  if (isset($_SESSION['recurring'])) {
	  unset($_SESSION["recurring"]);
	  }
	  
	  if (isset($_SESSION['paypal_express_token'])) {
	  unset($_SESSION["paypal_express_token"]);
	  }
	  if (isset($_SESSION['total_recurring_items'])) {
	  unset($_SESSION["total_recurring_items"]);
	  }
	  if (isset($_SESSION['total_non-recurring_count'])) {
	  unset($_SESSION["total_non-recurring_count"]);
	  }
	  
	  if (isset($_SESSION['product_details'])) {
	  unset($_SESSION["product_details"]);
	  }
	  
	  if (isset($_SESSION['checkoutPage']))
		  unset($_SESSION["checkoutPage"]);
		  
	//  if (isset($_SESSION['continuePage']))
		//  unset($_SESSION["continuePage"]);

	  if (isset($_SESSION['cartSummaryPage']))
		  unset($_SESSION["cartSummaryPage"]);
		  
	  if (isset($_SESSION['showcc']))
		  unset($_SESSION["showcc"]);

	  if (isset($_SESSION['showpaypal']))
		  unset($_SESSION["showpaypal"]);
		  
	  if (isset($_SESSION['new_amount']))
		  unset($_SESSION["new_amount"]);
		 
	  if (isset($_SESSION['couponCode']))
		  unset($_SESSION["couponCode"]);
		  
	}
		

	public function getDiscountedAmountForAddToCart($i)  // Paypal express checkout 
	{
		$coupon = Dap_Coupon::loadCouponByCode($_SESSION['couponCode']);
										
		if (isset($coupon)) {
			logToFile("Dap_payment.class.php.getDiscountAmount(): coupon found");
	
			$itemname = $_SESSION['product_details'][$i]['L_NAME' . $i];
			$product = Dap_Product::loadProductByName($itemname);
			$productCoupon =	Dap_ProductCoupon::findCouponIdAndProductId($product->getId(), $coupon->getId());
			
			if (isset($productCoupon)) {
				$discount_amount = $coupon->getDiscount_amt();
				$final_amt = ($_SESSION['product_details'][$i]['L_AMT' . $i] -  $discount_amount);
			
				logToFile("Dap_payment.class.php.getDiscountAmount(): itemname = " . $_SESSION['product_details'][$i]['L_NAME' . $i] . ", discount amount = " . $coupon->getDiscount_amt());
			}
			else {
				$final_amt = $_SESSION['product_details'][$i]['L_AMT' . $i];
			}
		}
		
		logToFile("Dap_payment.class.php.getDiscountAmount(): original amount = " . $_SESSION['product_details'][$i]['L_AMT' . $i] . ", discounted amount = " . $final_amt);

		$_SESSION['product_details'][$i]['L_AMT' . $i] = $final_amt;
		
		return $final_amt;
	
	}
	

	//StoreFrontAddToCart
	public function storefrontAddToCart($req)  // Paypal express checkout
	{	
	  $post_values = array();
	  logToFile("Dap_Payment:storefrontAddToCart: Enter");
	  $product = Dap_Product::loadProductByName(trim($req['item_name']));
	  logToFile("Dap_Payment:storefrontAddToCart: Loaded product by name=".$req['item_name']);
	  
	  if(!isset($product)) {
		return;			
	  } 
	  
	  $item_name = $product->getName();
	  $description = $product->getDescription();
	  
	  $trial_amount = $product->getTrial_price();
	  
	  $amount = $product->getPrice();
	  
	  $storefrontProductOptions = Dap_StoreFrontProducts::loadStoreFrontOptions($product->getId());
	  if(isset($storefrontProductOptions)) {
		$storefront_price=$storefrontProductOptions->getStorefront_price();
		if($storefront_price > 0) {
			$amount=$storefront_price;
		}
	  }
	
	  $recur_amount = $product->getPrice();
	  $is_recurring = $product->getIs_recurring();
	  
	  $recurring_cycle_1 = $product->getRecurring_cycle_1();
	  $recurring_cycle_2 = $product->getRecurring_cycle_2();
	  $recurring_cycle_3 = $product->getRecurring_cycle_3();
			  
	  $total_occurrences = $product->getTotal_occur();
  
	  $num_cart = 1;
	  
	  if ($req['is_submitted'] == "Y")
		$_SESSION['storefront_is_submitted'] = "Y";
		  
	  if (!isset($_SESSION['storefront_num_cart']) || ($_SESSION['storefront_num_cart'] == ''))	
		$_SESSION['storefront_num_cart'] = $num_cart;
	  else {
		  $num_cart = $_SESSION['storefront_num_cart']+1;
		$_SESSION['storefront_num_cart'] = $num_cart;
	  }
	  
	  if ($_SESSION['storefront_recur_count'] == -1) {
	    unset($_SESSION['storefront_recur_count']);
		$_SESSION['storefront_recur_count']="";
	  }
	 
	  if (strtoupper($is_recurring) == "Y") {
		$recur_count=1;
		if (!isset($_SESSION['storefront_recur_count']) || ($_SESSION['storefront_recur_count'] == ''))
		  $_SESSION['storefront_recur_count'] = $recur_count;
		else {
		  $recur_count = $_SESSION['storefront_recur_count']+1;
		  $_SESSION['storefront_recur_count'] = $recur_count;
		}
		
		//$post_values['x_recurring_billing'] = "TRUE";
		if (isset($trial_amount) && ($trial_amount != "0.0") && ($trial_amount != "0.00") && ($trial_amount != "0") ) {
		  $amount = $trial_amount;
		}
		else { 
		  $amount = $amount;
		}
	  } 
	  
	  logToFile("Dap_Payment:storefront_recur_count(). " . $_SESSION['storefront_recur_count'], LOG_DEBUG_DAP);
	  
	  
	  $match_found = false;
	  $num_cart_items = $_SESSION['storefront_num_cart'];
	  $index = 0;
	  
	  for ($i=0;$i<$num_cart_items;$i++) {
		logToFile("Dap_Payment:addtoCart(). session params, i=" . $i . ", item_name=" . $_SESSION['storefront_product_details'][$i]['L_NAME'.$i], LOG_DEBUG_DAP);
		if ( strcmp ($_SESSION['storefront_product_details'][$i]['L_NAME'.$i], $item_name) == 0) {
		  logToFile("Dap_Payment:addtoCart(). " . match_found . "=Y", LOG_DEBUG_DAP);
		  $match_found = true;
		  $index = $i;
		  break;
		}
	  }
	  
	  //Itemized Order Information
	  $quantity=1;
	  $product_id=1;
	  
	  $i = $num_cart_items - 1;  
	  $product_id = $product->getId();

	  $post_values['L_NAME' . $i] = $item_name;
	  $post_values['L_DESC' . $i] = $description;
	  
	  if ($match_found) {
		logToFile("Dap_Payment:storefrontAddtoCart(). match found, num_cart=" . $_SESSION['storefront_num_cart'], LOG_DEBUG_DAP);
	
		$_SESSION['storefront_product_details'][$index]['L_TOTALAMT'.$index] = ($amount *  ($_SESSION['storefront_product_details'][$index]['L_QTY'.$index] + 1));
		$_SESSION['storefront_product_details'][$index]['L_TOTALRECURAMT'.$index] = ($amount *  ($_SESSION['storefront_product_details'][$index]['L_QTY'.$index] + 1));																																				  

		$_SESSION['storefront_product_details'][$index]['L_QTY'.$index] = $_SESSION['storefront_product_details'][$index]['L_QTY'.$index] + $quantity;
		
		$_SESSION['storefront_num_cart'] = $_SESSION['storefront_num_cart'] - 1;
		
		if ($_SESSION['storefront_product_details'][$index]['L_ISRECUR'.$index] == "Y") {
		  $_SESSION['storefront_recur_count'] = $_SESSION['storefront_recur_count'] - 1;
		}
		logToFile("Dap_Payment:storefrontAddtoCart(). num_cart after removing " . $_SESSION['storefront_recur_count'], LOG_DEBUG_DAP);
	  }
	  else {
		$post_values['L_TOTALAMT' . $i] = $amount;
		$post_values['L_TOTALRECURAMT' . $i] = $amount;
		$post_values['L_QTY' . $i] = $quantity;
		$post_values['L_AMT' . $i] = $amount;
		$post_values['L_NUMBER' . $i] = $product_id;
		$post_values['L_ISRECUR' . $i] = $is_recurring;
		$post_values['L_RECURAMT' . $i] = $recur_amount;
		$post_values['L_RECUR1' . $i] = $recurring_cycle_1;
		$post_values['L_RECUR2' . $i] = $recurring_cycle_2;
		$post_values['L_RECUR3' . $i] = $recurring_cycle_3;
		$post_values['L_TOTALOCCUR' . $i] = $total_occurrences;
	
		$_SESSION['storefront_product_details'][$i]=$post_values;
		
		$_SESSION['storefront_product_id']=$product_id;
		logToFile("Dap_Payment:storefrontAddtoCart(). set post params in session", LOG_DEBUG_DAP);
	  }
	  
	  foreach( $post_values as $key => $value ) {
		$post_string .= "$key=" . $value . "&"; 
		logToFile("Dap_Payment:storefrontAddtoCart(). " . $key . "=" . $value	, LOG_DEBUG_DAP);
	  }
  
	  logToFile("Dap_Payment:storefrontAddtoCart(). product amount " . $amount, LOG_DEBUG_DAP);
	  logToFile("Dap_Payment:storefrontAddtoCart(). num_cart=" . $_SESSION['storefront_num_cart'], LOG_DEBUG_DAP);
	  
	  if (!isset($_SESSION['storefrontCartSummaryPage']) || (isset($req['storefrontCartSummaryPage'])))
		$_SESSION['storefrontCartSummaryPage'] = $req['storefrontCartSummaryPage'];
  
	  $_SESSION['post'] = "Y";
	  
	  logToFile("Dap_Payment:storefrontAddtoCart(). Complete", LOG_DEBUG_DAP);
	  
	  if ($req['storefrontCartSummaryPage'] != "") {
		header("Location: " . $req['storefrontCartSummaryPage']);
	  }
	  else {
		header("Location: /dap/inc/content/dapstorefront.inc.php");
	  }
	  
	  return;
	}

	//RemoveFromCart
	public function storefrontRemoveFromCart($itemname, $cartSummaryPage)  // Paypal express checkout
	{	
		$post_values = array();
		$num_cart_item = $_SESSION['storefront_num_cart'];
		logToFile("Dap_Payment:removeFromCart(). remove item=" . $itemname, LOG_DEBUG_DAP);
		logToFile("Dap_Payment:removeFromCart(). storefront_num_cart=" . $_SESSION['storefront_num_cart'], LOG_DEBUG_DAP);
		
		$j=0;
		
		$product = Dap_Product::loadProductByName(trim($itemname));
		logToFile("Dap_Payment:addToCart: Loaded product by name=".$itemname);
		
		if(!isset($product)) {
		  return;			
		} 
		
		$item_name = $product->getName();
		$description = $product->getDescription();
		
		$trial_amount = $product->getTrial_price();
		$amount = $product->getPrice();
		
		$is_recurring = $product->getIs_recurring();
		
		$recurring_cycle_1 = $product->getRecurring_cycle_1();
		$recurring_cycle_2 = $product->getRecurring_cycle_2();
		$recurring_cycle_3 = $product->getRecurring_cycle_3();
				
		$total_occurrences = $product->getTotal_occur();
		
		
		if (isset($trial_amount) && ($trial_amount != "0.0") && ($trial_amount != "0.00") && ($trial_amount != "0") ) {
			$amount = $trial_amount;
		}
		else { 
			$amount = $amount;
		}
			
		for ($i=0;$i<$num_cart_item;$i++) {
			logToFile("Dap_Payment:storefrontRemoveFromCart(). session itemname =" . $_SESSION['storefront_product_details'][$i]['L_NAME'.$i], LOG_DEBUG_DAP);
			if (strcmp($_SESSION['storefront_product_details'][$i]['L_NAME'.$i], $itemname) == 0) {
				$removeindex = $i;
				logToFile("Dap_Payment:removeFromCart(). remove index=" . $removeindex, LOG_DEBUG_DAP);
				logToFile("Dap_Payment:removeFromCart(). storefront_recur count=" . $_SESSION['storefront_recur_count'], LOG_DEBUG_DAP);
			
				$_SESSION['storefront_num_cart']--;
				
				if ($_SESSION['storefront_product_details'][$i]['L_ISRECUR'.$i] == "Y") {
					$_SESSION['storefront_recur_count'] = $_SESSION['storefront_recur_count'] - 1;
				}
				
				unset($_SESSION['storefront_new_amount']);
				unset($_SESSION['storefront_product_details'][$i]);
			}
			else {
				$post_values[$j]['L_NAME'.$j] = $_SESSION['storefront_product_details'][$i]['L_NAME'.$i];
				$post_values[$j]['L_DESC'.$j] = $_SESSION['storefront_product_details'][$i]['L_DESC'.$i];
				$post_values[$j]['L_AMT'.$j] = $_SESSION['storefront_product_details'][$i]['L_AMT'.$i];
				$post_values[$j]['L_TOTALAMT'.$j] = $_SESSION['storefront_product_details'][$i]['L_TOTALAMT'.$i];
				$post_values[$j]['L_TOTALRECURAMT'.$j] = $_SESSION['storefront_product_details'][$i]['L_TOTALRECURAMT'.$i];
				$post_values[$j]['L_NUMBER'.$j] = $_SESSION['storefront_product_details'][$i]['L_NUMBER'.$i];
				$post_values[$j]['L_QTY'.$j] = $_SESSION['storefront_product_details'][$i]['L_QTY'.$i];
				$post_values[$j]['L_ISRECUR'.$j] = $_SESSION['storefront_product_details'][$i]['L_ISRECUR'.$i];
				$post_values[$j]['L_RECURAMT'.$j] = $_SESSION['storefront_product_details'][$i]['L_RECURAMT'.$i];
				$post_values[$j]['L_RECUR1'.$j] = $_SESSION['storefront_product_details'][$i]['L_RECUR1'.$i];
				$post_values[$j]['L_RECUR2'.$j] = $_SESSION['storefront_product_details'][$i]['L_RECUR2'.$i];
				$post_values[$j]['L_RECUR3'.$j] = $_SESSION['storefront_product_details'][$i]['L_RECUR3'.$i];
				$post_values[$j]['L_TOTALOCCUR'.$j] = $_SESSION['storefront_product_details'][$i]['L_TOTALOCCUR'.$i];
				$_SESSION['storefront_product_details'][$j] = $post_values[$j];
				$j++;
			}
		}
		
		/*for ($i=0;$i<$j;$i++) {
		 $_SESSION['product_details'][$i] = $post_values[$i];
		}
			*/
		logToFile("Dap_Payment:storefrontRemoveFromCart(). Complete", LOG_DEBUG_DAP);
			
		if ($_REQUEST['storefrontCartSummaryPage'] != "") {
			header("Location: " . $_REQUEST['storefrontCartSummaryPage']);
		}
		else {
			header("Location: /dap/inc/content/dapstorefront.inc.php");
		}
		
		return;
	}
	
	function reconstruct_url($current_url_with_query_string)
	{
	//logToFile("current_url_with_query_string=".$current_url_with_query_string,LOG_DEBUG_DAP);
	 
		if(strpos($current_url_with_query_string, '?') > 0) {
		  $current_url_without_query_string = substr($current_url_with_query_string, 0, strpos($current_url_with_query_string, '?')); // This line is the key
		  //logToFile("current_url_without_query_string=".$current_url_without_query_string,LOG_DEBUG_DAP);
		  return $current_url_without_query_string;
		}
		  
		return $current_url_with_query_string;
	}


	public function update_cc_profile($req)  // AIM
	{
		$input_payment_processor = $req["payment_gateway"];
		
		
		$returnurl = $req['editcarturl'];
		$req['editcarturl'] = $this->reconstruct_url($returnurl);

		$gateway_recur_url = trim(Dap_Config::get('GATEWAY_RECUR_URL'));
		logToFile("Dap_Payment:update_cc_profile(): " . $gateway_recur_url, LOG_DEBUG_DAP);
		
		//	logToFile("Dap_Payment:create_authnet_subscription(). Self-hosted authnet payment", LOG_DEBUG_DAP);
		$req['login_name']	= trim(Dap_Config::get('GATEWAY_API_LOGIN'));
		$req['transaction_key'] = trim(Dap_Config::get('GATEWAY_TRANS_KEY'));
		$req['gateway_url'] = trim(Dap_Config::get('GATEWAY_URL'));
		
		if(stristr($input_payment_processor,"authnet") != FALSE) {
			if(!$this->validateInput($req)) return FALSE;
		}
		
		//VEENA if user not logged in add check for not logged in and if yes, header redirect to editccc page with erro div populated
		if( !Dap_Session::isLoggedIn())
		{
			header("Location: ".Dap_Config::get("LOGIN_URL")."?msg=MSG_PLS_LOGIN&request=" . $req['editcarturl']);
			return false;
		}
		$session = Dap_Session::getSession();
	    $user = $session->getUser();
		$emailFilter = $user->getEmail();
		
		// TODO: only pick up subscription transaction
		logToFile("Dap_Payment:update_cc_profile():emailFilter=".$emailFilter,LOG_INFO_DAP);
		
		$TransactionsList = Dap_Transactions::loadTransactions($transNumFilter, $emailFilter, $productIdFilter, $statusFilter,"","",$transIdFilter);
		
		$foundTransaction=false;
		$authnet=false;
		$paypal=false;
		$recurring_id ="";
	
		foreach ($TransactionsList as $transaction) {
		  parse_str($transaction->getTrans_blob(), $list);
		 // logToFile("Dap_Payment:update_cc_profile():: Payment processor is paypal, setting address details before list",LOG_INFO_DAP); 
		  
		  if (($list == NULL) || !isset($list))
			 logToFile("Dap_Payment:update_cc_profile():::LIST EMPTY"); 
			  
		  foreach ($list as $key => $value) {
			 //logToFile("DAP-Auto-Cancellation::LIST DETAILS(): Key=".$key.", Value=".$value); 
		  }
		  
		  logToFile("Dap_Payment:update_cc_profile():TRANSACTION TYPE  =  " . $transaction->getTrans_type()); 
		  
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
		  else if (array_key_exists('stripe_customer_id',$list)) {
			  $recurring_id = $list["stripe_customer_id"];
			  logToFile("DAP-Paymentclass::stripe_customer_id".$recurring_id); 
		  }
		  
		  logToFile("DAP-Paymentclass::recurring_id".$recurring_id); 
		  
		  //PaypalPDT
		  $payment_processor = $transaction->getPayment_processor();

		  if( stristr($payment_processor, $req["payment_gateway"]) == FALSE ){
			  logToFile("Dap_Payment:update_cc_profile():input gateway = ". $payment_processor . ", SKIP ... transaction processor  =  " .$payment_processor); 
		 	  continue;
		  }
		  
		  $transaction_id=$transaction->getTrans_num();
		  $foundTransaction=true;
		  
		  logToFile("Dap_Payment:update_cc_profile(): foundTransaction=".$foundTransaction,LOG_INFO_DAP);
		  break; 
		} //foreach transaction
		
		if( $foundTransaction==FALSE) {
			// VEENA - HEADER REDIRECT - ADD ERROR MESSAGE
			header("Location: ".$req['editcarturl']."?msg=Transaction not found");
			return false;
		}
		
		if ( $recurring_id == "") {
		 // VEENA - HEADER REDIRECT cant process if recurring id is empty
		 	header("Location: ".$req['editcarturl']."?msg=Recurring id is empty");
			return false;
		}
		logToFile("DAP payment class : found recurring_id=".$recurring_id,LOG_INFO_DAP);
		//}//End Forech User product
		
		/*******************************End MODIFIED****************/
		if(!($xmlpos = strpos ($gateway_recur_url, "xml"))) {
			logToFile("Dap_Payment:update_cc_profile(): Incorrect merchant url", LOG_DEBUG_DAP);
			header("Location: ". $req['editcarturl']);

			return FALSE;
		}

//$gateway_recur_url - https://api.authorize.net/xml/v1/request.api";
//$host = "api.authorize.net";
//$path = "/xml/v1/request.api";

		$path = substr($gateway_recur_url, $xmlpos - 1); 
		$host = substr($gateway_recur_url, 8, $xmlpos - 8 - 1 ); // skip http:// (7 char) and "/" before xml (1 char)
		logToFile("Dap_Payment:update_cc_profile() - path=" . $path . "host=" . $host ,LOG_DEBUG_DAP);

		//sequence number is randomly generated
		$sequence	= rand(1, 1000);
		//timestamp is generated
		$timestamp = time ();
		$login_name = $req['login_name'];
		$transaction_key = $req['transaction_key'];
		
		//logToFile("Dap_Payment:create_authnet_recurring_subscription() - login_name=" . $login_name . "transaction_key=" . $transaction_key,LOG_DEBUG_DAP);
		//The following lines generate the SIM fingerprint.  PHP versions 5.1.2 and
		//newer have the necessary hmac function built in.  For older versions, it
		//will try to use the mhash library.
		if( phpversion() >= '5.1.2' )
		{	
			$fingerprint = hash_hmac("md5", $login_name . "^" . $sequence . "^" . $timestamp . "^" . $amount . "^", $transaction_key); 
		}
		else 
		{ 
			$fingerprint = bin2hex(mhash(MHASH_MD5, $login_name . "^" . $sequence . "^" . $timestamp . "^" . $amount . "^", $transaction_key));									
		}

		/*****************Modified*****/
		logToFile("DAP payment class : FirstName=".$this->getFirst_name(),LOG_INFO_DAP);
		
		logToFile("DAP payment class : LastName=".$this->getLast_name(),LOG_INFO_DAP);
		logToFile("DAP payment class : city=".$this->getCity(),LOG_INFO_DAP);
		logToFile("DAP payment class : state=". $req['state'],LOG_INFO_DAP);
		logToFile("DAP payment class : zip=".$this->getZip(),LOG_INFO_DAP);
		logToFile("DAP payment class : country=".$req['country'],LOG_INFO_DAP);
		//logToFile("DAP payment class : Login Name=".$req['login_name'],LOG_INFO_DAP);
		logToFile("DAP payment class : Expiry Date=".$this->getExp_date(),LOG_INFO_DAP);
		
		///logToFile("DAP payment class : RefId=".$refId,LOG_INFO_DAP);
		
		//print_r($this);
		//build xml to post
		
		if(strstr($payment_processor,"AUTHNET") != FALSE) {
			$content =
			"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
			
			"<ARBUpdateSubscriptionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
			"<merchantAuthentication>".
				"<name>" . $req['login_name'] . "</name>".
				"<transactionKey>" . $req['transaction_key'] . "</transactionKey>".
			"</merchantAuthentication>".
			"<refId>" . $refId . "</refId>".
			"<subscriptionId>" . intval($recurring_id) . "</subscriptionId>".
			
			"<subscription>";
			
			//$padded_exp_year = "20" . substr($this->getExp_date(), 2);
			//$exp_month = substr($this->getExp_date(), 0, 2);
			
			if($this->getCard_num()!='')
			{
				  $cardnum = str_replace(' ', '', $this->getCard_num());	
				  $content .="<payment>".
					  "<creditCard>".
						  "<cardNumber>" . $cardnum . "</cardNumber>".
						  "<expirationDate>" . $this->getExp_date() . "</expirationDate>".
					  "</creditCard>".
				  "</payment>";
				  }
				  $content .="<customer>".
					  "<id>". $invoice . "</id>".
					  "<email>". $this->getEmail() . "</email>".
					  "<phoneNumber>" . $this->getPhone() . "</phoneNumber>".
					  "<faxNumber>" . $this->getFax() . "</faxNumber>".
				  "</customer>".
				  "<billTo>".
					  "<firstName>". $this->getFirst_name() . "</firstName>".
					  "<lastName>" . $this->getLast_name() . "</lastName>".
					  "<address>" . $this->getAddress1() . "</address>".
					  "<city>" . $this->getCity() . "</city>".
					  "<state>" . $req['state'] . "</state>".
					  "<zip>" . $this->getZip() . "</zip>".
					  "<country>" . $req['country'] . "</country>".
				  "</billTo>".
				  "<shipTo>".
					  "<firstName>". $this->getShip_to_first_name() . "</firstName>".
					  "<lastName>" . $this->getShip_to_last_name() . "</lastName>".
					  "<address>" . $this->getShip_to_address1() . "</address>".
					  "<city>" . $this->getShip_to_city() . "</city>".
					  "<state>" . $req['state'] . "</state>".
					  "<zip>" . $this->getShip_to_zip() . "</zip>".
					  "<country>" . $req['country'] . "</country>".
				  "</shipTo>".
				  
			  "</subscription>".
			  "</ARBUpdateSubscriptionRequest>";
	  
			  //logToFile("Dap_Payment:XML content: " . $content, LOG_DEBUG_DAP);
			  
			  //send the xml via curl
			  $response = send_request_via_curl($host,$path,$content);
			  
		  //if the connection and send worked $response holds the return from Authorize.net
			  if ($response)
			  {
				  list ($ref_id, $result_code, $code, $text, $subscription_id) =parse_return($response);
				  if (!strcasecmp ($result_code,"Ok")) { //SUCCESS
					  logToFile("Dap_Payment:update_cc_profile(). Dap_Payment successfully processed by authorize.net,  Subs Id: " . $subscription_id , LOG_DEBUG_DAP);
					  
					  // VEENA - NOW ADD LOGIC TO UPDATE THE USER RECORD WITH THE UPDATED BLLING INFO...
					  // USE THE DAP USER OBJECT ANd UPDATE THE BILLING INFO
					  //$user = new Dap_User();
					  //$user->setId( $row["id"] );
					  $user->setFirst_name( $this->getFirst_name() );
					  $user->setLast_name( $this->getLast_name() );
					  $user->setAddress1( $this->getAddress1() );
					  $user->setCity( $this->getCity() );
					  $user->setState( $req['state'] );
					  $user->setZip( $this->getZip() );
					  $user->setCountry($req['country'] );
					  //Empty the payment object
					  $user->update();
					  
					  $ret = $this->updateCustomFields ($req, $user->getId() );
					  
					  $this->emptyPaymentParamsFromSession();
					  if(!isset($req['successmsg'])) {
						  $req['successmsg']="Update Completed Successfully!";
					  }
					  sendAdminEmail ("Dap_Payment:update_cc_profile(): update complete!", "Update CC/Profile Successfully completed by user=" . $this->getEmail());
					  header("Location: ".$req['editcarturl']."?msg=".$req['successmsg']);
					  return TRUE;
				  }
				  else {
					  logToFile("Dap_Payment:update_cc_profile(). Error Code: " . $result_code . " Reason Code: " . $code . " text: " . $text . " Subs Id: " . $subscription_id , LOG_DEBUG_DAP);
					  sendAdminEmail ("Dap_Payment:update_cc_profile(): ", "Authnet update CC/Profile failed for user=" . $this->getEmail() . " with Error Code: " . $result_code . " Reason Code: " . $code . " text: " . $text);
					  header("Location: ".$req['editcarturl']."?err_text=".$text);
					  return FALSE;
				  }
			  }	
			  else
			  {
				  logToFile("Dap_Payment:update_cc_profile(). Failed to connect to authnet", LOG_DEBUG_DAP);
				  sendAdminEmail("Authnet connection could not be established for " . $this->getEmail(), "Dap_Payment:update_cc_profile(). Authnet connection for recurring payment could not be established.");
				  return FALSE;
			  }
		} // authnet
		else if(strstr($payment_processor,"STRIPE") != FALSE) {
			
			$stripePublishableKey=Dap_Config::get('STRIPE_SECRET_KEY');
			Stripe::setApiKey($stripePublishableKey);
			
			logToFile("Dap_payment.class.php: update_cc_profile: stripe customer id: ". $recurring_id);
			logToFile("Dap_payment.class.php: update_cc_profile: stripe token: ". $req['stripeToken']);
			
			$cu = Stripe_Customer::retrieve($recurring_id);
			
			$cards = Stripe_Customer::retrieve($recurring_id)->cards->all(array( 'count'=>10));
			
			$newcard=false;
			$savecard="";
			foreach($cards['data'] as &$card) {
				logToFile("Dap_payment.class.php: update_cc_profile: card last 4: ". $card["last4"]);
			//	logToFile("Dap_payment.class.php: update_cc_profile: cardnum: ". $cardnum);
				$cardnum = str_replace(' ', '', $this->getCard_num());
				if(strstr($cardnum, $card["last4"]) == FALSE) {
					$newcard=true;
					logToFile("Dap_payment.class.php: update_cc_profile: NEW CARD");
				}
				else {
					$newcard=false;
					logToFile("Dap_payment.class.php: update_cc_profile: EXISTING CARD");
					$savecard = $card;
					break;
				}
			}
			
			if(isset($cu)) {
				
				if($newcard) {
					try {
					  $card = $cu->cards->create(array("card" => $req['stripeToken']));
					}
					catch (Exception $e) {
						$text=$e->getMessage();
						logToFile("Dap_payment.class.php: update_cc_profile: exception: ". $text);
						header("Location: ".$req['editcarturl']."?err_text=".$text);
						return FALSE;
					}
				}
				else {
					logToFile("Dap_payment.class.php: update_cc_profile: UPDATE EXISTING CARD");	
				}
				try {
					  
					$card->address_line1=$this->getAddress1();
					$card->address_city=$this->getCity();
					$card->address_state=$req['state'];
					$card->address_zip=$this->getZip();
					$card->address_country=$req['country'];
					
					logToFile("Dap_payment.class.php: update_cc_profile: exp mon: ". $req['exp_date'] . ", exp year" . $req['exp_date_year']);
					
					$card->exp_month=$req['exp_date'];
					$card->exp_year=$req['exp_date_year'];
					//$card->card_code=$this->getCard_code();
					$card->name =  $this->getFirst_name() . " " .  $this->getLast_name();
					$card->save();
					//$cu->card = $req['stripeToken'];
					$cu->default_card = $card;
					$cu->save();
				}
				catch (Exception $e) {
					$text=$e->getMessage();
					logToFile("Dap_payment.class.php: update_cc_profile: exception: ". $text);
					header("Location: ".$req['editcarturl']."?err_text=".$text);
					return FALSE;
				}
				logToFile("Dap_payment.class.php: update_cc_profile: updated stripe successfully");
				
				$user->setFirst_name( $this->getFirst_name() );
				$user->setLast_name( $this->getLast_name() );
				$user->setAddress1( $this->getAddress1() );
				$user->setCity( $this->getCity() );
				$user->setState( $req['state'] );
				$user->setZip( $this->getZip() );
				$user->setCountry($req['country'] );
				//Empty the payment object
				$user->update();
				$ret = $this->updateCustomFields ($req, $user->getId() ); //update card expiration date
				$this->emptyPaymentParamsFromSession();
				if(!isset($req['successmsg'])) {
					$req['successmsg']="Update Completed Successfully!";
				}
				header("Location: ".$req['editcarturl']."?msg=".$req['successmsg']);
				return TRUE;
			}
			
		} // stripe update
		
	} // end function
		
	public function emptyPaymentParamsFromSession()
	{
		if (isset($_SESSION['payment_object'])) {
		unset($_SESSION["payment_object"]);
		
		}	
	$_SESSION["payment_object"]=NULL;
	}	
	
	
	
	
	
	public function create_stripe_subscription($req)  // AIM
	{
		logToFile("SESSION['couponCode']=" .  $_SESSION['couponCode'], LOG_DEBUG_DAP);
		$post_values = array();
		$payment_url = $req['err_redirect'];
		
		logToFile("DAP payment class : Expiry Date=".$this->getExp_date(),LOG_INFO_DAP);
		
		$req['login_name']	= trim(Dap_Config::get('GATEWAY_API_LOGIN'));
		$req['transaction_key'] = trim(Dap_Config::get('GATEWAY_TRANS_KEY'));
		$req['gateway_url'] = trim(Dap_Config::get('GATEWAY_URL'));
		
		$stripePublishableKey=Dap_Config::get('STRIPE_SECRET_KEY');
		Stripe::setApiKey($stripePublishableKey);
		$error = '';
		$success = '';
		
		if (!$this->validateInput($req)) {
			header("Location: ". SITE_URL_DAP . $req['payment_err_page'] . "?response_msg=missing request params");
			return;
		}
		
		if (!isset($_SESSION['stripeToken']) || ($_SESSION['stripeToken'] == "")) {
		  logToFile("Dap_Payment.class.php: stripe token missing",LOG_DEBUG_DAP);
		  $_SESSION['err_text']="Sorry, missing required field";
		  header("Location: ".$payment_url."?err_text=".$_SESSION['err_text']);
		  return;
	 	}
		
		$post_values['payment_gateway']="stripe";
		
		$billing_first_name= $this->getBillingFirst_name();
		//Billing Information
		if( isset($billing_first_name)  && ($this->getBillingFirst_name() != "")) {
			$billing_first_name	= $this->getBillingFirst_name();
		} 
		else
			$billing_first_name	= $this->getFirst_name();
		
		$billing_last_name= $this->getBillingLast_name();
		//Billing Information
		if( isset($billing_last_name)  && ($this->getBillingLast_name() != "")) {
			$billing_last_name	= $this->getBillingLast_name();
		} 
		else 
			$billing_last_name	= $this->getLast_name();
		
		logToFile("Dap_Payment.class.php: billing_first_name=" .  $billing_first_name, LOG_DEBUG_DAP);
		logToFile("Dap_Payment.class.php: billing_last_name=" .  $billing_last_name, LOG_DEBUG_DAP);
		
		//Billing Information
		$post_values['first_name'] = $billing_first_name;
		$post_values['last_name'] = $billing_last_name;
		
		$post_values['address'] = $this->getAddress1();
		$post_values['city'] = $this->getCity();
		$post_values['state'] = $this->getState();
		$post_values['zip'] = $this->getZip();
		$post_values['country'] = $this->getCountry();			
		$post_values['phone'] = $this->getPhone();	
		$post_values['email'] = $this->getEmail();	

		//Shipping Information  
		$post_values['ship_to_first_name'] = $this->getShip_to_first_name();
		$post_values['ship_to_last_name'] = $this->getShip_to_last_name();
		$post_values['ship_to_address'] = $this->getShip_to_address1();
		$post_values['ship_to_city'] = $this->getShip_to_city();
		$post_values['ship_to_state'] = $this->getShip_to_state();
		$post_values['ship_to_zip'] = $this->getShip_to_zip();
		$post_values['ship_to_country'] = $this->getShip_to_country();
		
		$invoice = isset($req['invoice']) ? $req['invoice'] : date(YmdHis);
		
		$userExists = "N";
		$user = Dap_User::loadUserByEmail($this->getEmail());
		$accessEndDateOfUpgTo="";
		if(isset($user)) {
			$userExists = "Y";
		}
		logToFile("Dap_Payment:create_stripe_subscription(). userExists=".$userExists, LOG_DEBUG_DAP);
		
		if(isset($req['btntype']) && ($req['btntype'] != "")) 
		  $_SESSION['btntype'] = $req['btntype'];	
		 
		if ($req['currency'] != "") {
		  $_SESSION['currency']=$req['currency'];
		  $_SESSION['currency_symbol']=$req['currency_symbol'];
		  $post_values['CURRENCYCODE'] = urlencode(trim($req['currency']));
		  logToFile("Dap_Payment:create_stripe_subscription(). buttonlevel currency: " . $post_values['CURRENCYCODE'] , LOG_DEBUG_DAP);
		}
		else {
		  $_SESSION['currency']=Dap_Config::get('CURRENCY_TEXT');
		  $_SESSION['currency_symbol']="$";
		  $post_values['CURRENCYCODE'] = urlencode(trim(Dap_Config::get('CURRENCY_TEXT')));	
		  if (!isset($post_values['CURRENCYCODE']) || $post_values['CURRENCYCODE'] == '')
			  $post_values['CURRENCYCODE'] = urlencode("USD");	
		}
		
		$stripe_instant_recurring_charge=$req["stripe_instant_recurring_charge"];
		if($stripe_instant_recurring_charge=="") 
			$stripe_instant_recurring_charge="Y";
		
		$num_cart_items=$_SESSION["num_cart"];
		$total_amount=0;
		
		for ($i=0;$i<$num_cart_items;$i++) {
			$is_recur = $_SESSION['product_details'][$i]['L_ISRECUR'.$i];
			$item_id = $_SESSION['product_details'][$i]['L_NUMBER'.$i];
			$item_name = $_SESSION['product_details'][$i]['L_NAME'.$i];
			$item_desc = $_SESSION['product_details'][$i]['L_DESC'.$i];
			$item_qty = $_SESSION['product_details'][$i]['L_QTY'.$i];
			
			$amount= ($_SESSION['product_details'][$i]['L_AMT'.$i]) * $item_qty ;
			
			$total_amount = $total_amount + $amount;
						
			logToFile("Dap_Payment:create_stripe_subscription(). recurring item_name" . $i . "=" . $_SESSION['product_details'][$i]['L_NAME'.$i], LOG_DEBUG_DAP);
			
		}
		
		logToFile("Dap_Payment:create_stripe_subscription(). total_amount=" . $total_amount, LOG_DEBUG_DAP);
		logToFile("Dap_Payment:create_stripe_subscription(). new amount =" . $_SESSION['new_amount'], LOG_DEBUG_DAP);
		logToFile("Dap_Payment:create_stripe_subscription(). coupon code=" . $_SESSION['couponCode'], LOG_DEBUG_DAP);
		
		if(isset($req['btntype']) && ($req['btntype'] != "")) 
		  $_SESSION['btntype'] = $req['btntype'];

		$couponFound=false;
		if (($_SESSION['new_amount'] != "") && ($_SESSION['couponCode'] != "")) {
		  logToFile("Dap_Payment:create_stripe_subscription(). coupon applied", LOG_DEBUG_DAP);
		
		  $couponFound=true;
		  $coupon = Dap_Coupon::loadCouponByCode($_SESSION['couponCode']);
		  if (isset($coupon)) {
			logToFile("Dap_Payment:create_stripe_subscription(). retrieve coupon info from Stripe", LOG_DEBUG_DAP); 
			try {
				$cpn = Stripe_Coupon::retrieve($_SESSION['couponCode']);
			}
			catch (Exception $e) {
				$errmsg=$e->getMessage();
				$_SESSION['err_text']=$errmsg;
			    header("Location: ".$payment_url."?err_text=".$_SESSION['err_text']);
			    logToFile("Dap_payment.class.php: create_stripe_subscription: exception: ". $errmsg);
				return;
			}	
			if(isset($cpn)) {
				$post_values['coupon_id'] = $coupon->getId();
				logToFile("Dap_payment.class.php: create_stripe_subscription POST VALUES: coupon found = " . $post_values['coupon_id']);
			}
			
		  }
		}
		
		if ($num_cart_items > 0) {
			if ($_SESSION['new_amount'] != "") {
				$post_values['amount'] = $_SESSION['new_amount'];
				logToFile("Dap_Payment:create_stripe_subscription().session new amount=" . $_SESSION['new_amount'], LOG_DEBUG_DAP);
			}
			else {
				logToFile("Dap_Payment:create_stripe_subscription().amount=" . $amount, LOG_DEBUG_DAP);
				$post_values['amount'] = $total_amount;	
				$_SESSION['new_amount'] = $post_values['amount'] ;
			}
		}
		else {
			// if trial period, set amount to the trial amount
			logToFile("Dap_Payment:create_stripe_subscription(). num_cart_items=" . $num_cart_items, LOG_DEBUG_DAP);
			if ($_SESSION['new_amount'] != "") {
				$post_values['amount'] = $_SESSION['new_amount'];
				logToFile("Dap_Payment:create_stripe_subscription().coupon found... session new amount=" . $_SESSION['new_amount'], LOG_DEBUG_DAP);
			}
			else {
			
				if ( isset($req['trial_amount']) && ($req['trial_amount'] != "0.00") && ($req['trial_amount'] != "0.0") && ($req['trial_amount'] != "0") ) 			
				{
					$amount = $req['trial_amount'];
				}
				else { 
					$amount = $req['amount'];
				}
				
				$post_values['amount'] = $amount;
				logToFile("Dap_Payment:create_stripe_subscription().no coupon, amount=" . $amount, LOG_DEBUG_DAP);
				
				$_SESSION['new_amount'] = $post_values['amount'] ;
			}
			
			$post_values['x_line_item'] = $req["item_name"];
		
		}
		
		logToFile("Dap_Payment:create_stripe_subscription(). AMOUNT= " . $post_values['amount'], LOG_DEBUG_DAP);
		
		
		logToFile("Dap_payment.class.php: create_stripe_subscription POST VALUES: CURRENCYCODE= " . $post_values['CURRENCYCODE']);
		logToFile("Dap_payment.class.php: create_stripe_subscription POST VALUES: amt= " . $post_values['amount']);
		try {
			if (!isset($req['stripeToken'])) {
			  $_SESSION['err_text']="The Stripe Token was not generated correctly";
			  header("Location: ".$payment_url."?err_text=".$_SESSION['err_text']);
			}
			
			// check if customer id exists
			$customerId = $this->getCustomerId($post_values['email']);
			$foundCustomer=false;
			if($customerId == "") {
			  // Create a Customer
			  logToFile("Dap_payment.class.php: create_stripe_subscription Create a NEW Customer: " . $post_values['email']);
			  logToFile("Dap_payment.class.php: create_stripe_subscription Create a NEW Customer: " . $req['stripeToken']);
			  $name = $billing_first_name . " " . $billing_last_name;
			  logToFile("Dap_payment.class.php: create_stripe_subscription customer name = " . $name);
			  
			  $customer = Stripe_Customer::create(array(
				"card" =>  $req['stripeToken'],
				"email" => $post_values['email'],
				"description" => $name
				)
			  );
			  
			  //$customer=json_decode($customer);
			  $customerId = $customer->id;
			  
			  if($customerId != "")
			  	$customer = Stripe_Customer::retrieve($customerId);
			  
			  logToFile("Dap_payment.class.php: create_stripe_subscription Created a Customer SUCCESSFULLY: id=".$customerId);
			}
			else {
				logToFile("Dap_payment.class.php: FOUND CUSTOMER ID : EXISTING CUSTOMER: create_stripe_subscription get Customer Details");
				$customer = Stripe_Customer::retrieve($customerId);	
				$foundCustomer=true;
				logToFile("Dap_payment.class.php create_stripe_subscription: retrieved customer details, id=".$customerId);
			}
		
			if( ($num_cart_items > 0) ) { 
			logToFile("Dap_payment.class.php create_stripe_subscription: num_cart_items=" . $num_cart_items);
			// Iterate thru the list of items and for each do this:
				$success = 'Your payment was successful.';
				if( ( $_SESSION["new_amount"]!="") && ($post_values['coupon_id'] != "")) {
					// one charge for all cart items if coupon found
					logToFile("Dap_payment.class.php create_stripe_subscription: new_amount=" . $_SESSION["new_amount"]);
					Stripe_Charge::create(array( "amount" => $post_values["amount"] * 100,
								"currency" =>  $post_values['CURRENCYCODE'],
								"customer" => $customerId, 
								"description" => $post_values["item_name"] ) );
				}
				for ($i=0;$i<$num_cart_items;$i++) {
				  $is_recur = $_SESSION['product_details'][$i]['L_ISRECUR'.$i];
				  $item_id = $_SESSION['product_details'][$i]['L_NUMBER'.$i];
				  $item_name = $_SESSION['product_details'][$i]['L_NAME'.$i];
				  $item_desc = $_SESSION['product_details'][$i]['L_DESC'.$i];
				  $item_qty = $_SESSION['product_details'][$i]['L_QTY'.$i];
				  $amount= ($_SESSION['product_details'][$i]['L_AMT'.$i]) * $item_qty ;
				  $recur_amount= ($_SESSION['product_details'][$i]['L_RECURAMT'.$i]) * $item_qty ; 
				  $recur_trial = $_SESSION['product_details'][$i]['L_RECUR1'.$i];
				  $recur_interval = $_SESSION['product_details'][$i]['L_RECUR3'.$i];
				  
				  //  $total_recur = $_SESSION['product_details'][$i]['L_TOTALOCCUR'.$i];
				  $total_recur=0;
				   
				  $post_values["is_recur"] = $_SESSION['product_details'][$i]['L_ISRECUR'.$i];
				  $post_values["item_id"] = $_SESSION['product_details'][$i]['L_NUMBER'.$i];
				  $post_values["item_name"] = $_SESSION['product_details'][$i]['L_NAME'.$i];
				  $post_values["item_desc"] = $_SESSION['product_details'][$i]['L_DESC'.$i];
				  $post_values["item_qty"] = $_SESSION['product_details'][$i]['L_QTY'.$i];
				  $post_values["amount"] = ($_SESSION['product_details'][$i]['L_AMT'.$i]) * $item_qty ;
				  $post_values["recur_amount"] = ($_SESSION['product_details'][$i]['L_RECURAMT'.$i]) * $item_qty ; 
				  $post_values["recur_trial"] = $_SESSION['product_details'][$i]['L_RECUR1'.$i];
				  $post_values["recur_interval"] = $_SESSION['product_details'][$i]['L_RECUR3'.$i];
				 
				  // $post_values["total_recur"] = $_SESSION['product_details'][$i]['L_TOTALRECUR'.$i];
				  $post_values["total_recur"]=0;
				  
				  $total_amount = $total_amount + $amount;
				
				  if (strtoupper($is_recur) == "Y") {
						logToFile("Dap_Payment:create_stripe_subscription(). update subscription plan in stripe for product, " . $i . "=" . $_SESSION['product_details'][$i]['L_NAME'.$i] . " , quantity=" . $item_qty, LOG_DEBUG_DAP);
						try{
							 //charge initial amount, include quantity
							 // if free trial, this field (stripe_instant_recurring_charge) is auto set to N
							if($stripe_instant_recurring_charge=="Y") {
								logToFile("Dap_payment.class.php: create_stripe_subscription: do instant charge + plan. amount=");
								if($_SESSION["couponCode"]=="") {
									logToFile("Dap_Payment:create_stripe_subscription(). recur. charge=" . $post_values["amount"] * 100, LOG_DEBUG_DAP);
									
									Stripe_Charge::create(array( "amount" => $post_values["amount"] * 100,
									"currency" =>  $post_values['CURRENCYCODE'],
									"customer" => $customerId, 
									"description" => $post_values["item_name"] ) );
								}
							}
							else {
								logToFile("Dap_payment.class.php: create_stripe_subscription: NO instant charge, just set plan");
							}
							
							$product = Dap_Product::loadProductByName($item_name);
							$first_subs_due_in_days = $product->getRecurring_cycle_1();
							$today = date('Y-m-d');
							//$first_subs_due_date = strtotime(date("Y-m-d", time() + $first_subs_due_in_days));
							$first_subs_due_date = strtotime( $today . " + " . $first_subs_due_in_days . " day" );
							logToFile("Dap_Payment:create_stripe_subscription().first_subs_due_date=".$first_subs_due_date, LOG_DEBUG_DAP);
						
							if( (isset($cpn)) && ($_SESSION["new_amount"]!="") && ($_SESSION["couponCode"]!="")) {
								logToFile("Dap_payment.class.php: create_stripe_subscription: add to cart.. coupon.. updatesubscription");
								$coupon = Dap_Coupon::loadCouponByCode($_SESSION['couponCode']);
								
								if (isset($coupon)) {
								  logToFile("Dap_payment.class.php: coupon found");
								 
								  $productCoupon =	Dap_ProductCoupon::findCouponIdAndProductId($product->getId(), $coupon->getId());
								  if (isset($productCoupon)) {
									  logToFile("Dap_payment.class.php: POST VALUES: coupon found = " . $post_values['coupon_id'] . " for product=" . $item_name . ". Check if recurring discount enabled. ");
									  
									  $recurring_discount_amt = $coupon->getRecurringDiscount_amt();
									  if($recurring_discount_amt>0) {
										logToFile("Dap_payment.class.php: recurring_discount_amt>0, total_recur=".$total_recur);
								   
										if((int)$total_recur>0) {
											logToFile("Dap_payment.class.php: recurring_discount_amt>0, set max occur in stripe=".$total_recur);
											$customer->updateSubscription(array("plan" => $item_name, "quantity" => $item_qty, "prorate" => false, "coupon" => $cpn, "trial_end" => $first_subs_due_date, "max_occurrences"=>$total_recur));
										}
										else {
											$customer->updateSubscription(array("plan" => $item_name, "quantity" => $item_qty, "prorate" => false, "coupon" => $cpn, "trial_end" => $first_subs_due_date));
										}
										
										$post_values['coupon_id'] = $coupon->getId();
										logToFile("Dap_payment.class.php: POST VALUES: coupon found = " . $post_values['coupon_id']);
									  }
									  else {
										  logToFile("Dap_payment.class.php: POST VALUES: coupon found but no recurring discount associated with coupon, total occur=".$total_recur);
										  if((int)$total_recur>0) {
											  logToFile("Dap_payment.class.php: POST VALUES: coupon found but no recurring discount associated with coupon, set max occurrences in stripe to ".$total_recur);
											  $customer->updateSubscription(array("plan" => $item_name, "quantity" => $item_qty, "prorate" => false, "trial_end" => $first_subs_due_date, "max_occurrences"=>$total_recur));
										  } else {
											  $customer->updateSubscription(array("plan" => $item_name, "quantity" => $item_qty, "prorate" => false, "trial_end" => $first_subs_due_date));				
										  }
										  $customer->deleteDiscount();
									  }
								  }
								  else {
									  logToFile("Dap_payment.class.php: POST VALUES: coupon found = " . $post_values['coupon_id'] . " but not associated to product=".$item_name.", total recur=".$total_recur);
									 if((int)$total_recur>0) {
										 logToFile("Dap_payment.class.php: POST VALUES: coupon found = " . $post_values['coupon_id'] . " but not associated to product=".$item_name.", set max occurrences in stripe, total recur=".$total_recur);
										 $customer->updateSubscription(array("plan" => $item_name, "quantity" => $item_qty, "prorate" => false, "trial_end" => $first_subs_due_date, "max_occurrences"=>$total_recur));	
									 }else {
										  $customer->updateSubscription(array("plan" => $item_name, "quantity" => $item_qty, "prorate" => false, "trial_end" => $first_subs_due_date));	
									 }
									  $customer->deleteDiscount();
									 
								  }
								}
								else {
									logToFile("Dap_payment.class.php: POST VALUES: coupon not found = " . $post_values['coupon_id'].", total recur=".$total_recur);
									if((int)$total_recur>0) {
										logToFile("Dap_payment.class.php: POST VALUES: coupon not found = " . $post_values['coupon_id'].", set max occurrences in stripe to ".$total_recur);
										$customer->updateSubscription(array("plan" => $item_name, "quantity" => $item_qty, "prorate" => false, "trial_end" => $first_subs_due_date, "max_occurrences"=>$total_recur));			
									}
									else {
										$customer->updateSubscription(array("plan" => $item_name, "quantity" => $item_qty, "prorate" => false, "trial_end" => $first_subs_due_date));	
									}
									$customer->deleteDiscount();
									
								}
							}
							else {
								logToFile("Dap_payment.class.php: create_stripe_subscription: add to cart.. no coupon.. updatesubscription, total recur=".$total_recur);
								if((int)$total_recur>0) {
									logToFile("Dap_payment.class.php: create_stripe_subscription: add to cart.. no coupon.. updatesubscription, set max occurrences in stripe to ".$total_recur);
									$customer->updateSubscription(array("plan" => $item_name, "quantity" => $item_qty, "prorate" => true, "trial_end" => $first_subs_due_date, "max_occurrences"=>$total_recur));
								} else {
									$customer->updateSubscription(array("plan" => $item_name, "quantity" => $item_qty, "prorate" => true, "trial_end" => $first_subs_due_date));	
								}
								$customer->deleteDiscount();
								
							}
							$customer->save();
							logToFile("Dap_payment.class.php: create_stripe_subscription: updateSubscription");
							
							//$invoice = Stripe_Invoice::create(array( "customer" => $customerId ));
							//$invoice->pay();
						}
						catch (Exception $e) {
							$errmsg=$e->getMessage();
							if($errmsg == "Nothing to invoice for customer") {
							  logToFile("Dap_payment.class.php: create_stripe_subscription: exception: ". $errmsg);
							}
						}	
						logToFile("Dap_Payment:create_stripe_subscription().paid invoice for plan successfully", LOG_DEBUG_DAP);
						
						$post_values["txn_type"]='subsc';
						// do we have to call the charge specifically for subscription item?
				  }
				  else {
					  logToFile("Dap_Payment:create_stripe_subscription(). create invoice in stripe for one-time product, " . $i . "=" . $_SESSION['product_details'][$i]['L_NAME'.$i] . " , quantity=" . $item_qty, LOG_DEBUG_DAP);
					  
					  if($_SESSION["couponCode"]=="") {
						  logToFile("Dap_Payment:create_stripe_subscription(). non-recur. charge=". $amount * 100, LOG_DEBUG_DAP);
						  Stripe_Charge::create(array( "amount" => $amount * 100,
						  "currency" =>  $post_values['CURRENCYCODE'],
						  "customer" => $customerId, 
						  "description" => $item_name ) );
					  }
					  /*if(isset($cpn)) {
						  
					  		Stripe_InvoiceItem::create(array(
							  "customer" => $customerId, 
							  "amount" => $amount * 100, #amount in cents, again
							  "currency" => $post_values['CURRENCYCODE'], 
							  "description" => $item_name,
							  "coupon" => $cpn) );
							
					  } else {
					  		Stripe_InvoiceItem::create(array(
							  "customer" => $customerId, 
							  "amount" => $amount * 100, #amount in cents, again
							  "currency" => $post_values['CURRENCYCODE'], 
							  "description" => $item_name) );
				 	  }*/
					  
					  $post_values["txn_type"]='buynow';
				  }
				  
				  $this->processStripePayment($post_values, $customerId, "Dap_Payment");
				  
				  logToFile("Dap_Payment:create_stripe_subscription(). recurring item_name" . $i . "=" . $_SESSION['product_details'][$i]['L_NAME'.$i], LOG_DEBUG_DAP);
				  
				}
			}
			else { //buy now
			
				$post_values['item_name'] = $req["item_name"];
				
				$allowpurchase=true;
				if( (isset($_SESSION["stripe_item_name"])) && ($_SESSION["stripe_item_name"]!="") ) {
					logToFile("Dap_Payment:create_stripe_subscription().buynow found stripe session item name: " . $_SESSION["stripe_item_name"], LOG_DEBUG_DAP);
					if(strstr($post_values['item_name'], $_SESSION["stripe_item_name"]) != FALSE) {
						logToFile("Dap_Payment:create_stripe_subscription().buynow duplicate order, do not process", LOG_DEBUG_DAP);	
						$subject="Dap_Payment:create_stripe_subscription(). duplicate order for the item:".$item_name;
						$body="Dap_Payment:create_stripe_subscription(). duplicate order for the item:".$item_name.", for user=".$this->getEmail();
						sendAdminEmail($subject, $body);
						$allowpurchase=false;
					}
				}
				if($allowpurchase==true) {
					$product=Dap_Product::loadProductByName($post_values['item_name']);
					
					//$total_recur=$product->getTotal_occur();
					$total_recur=0;
					
					logToFile("Dap_Payment:create_stripe_subscription().buynow START", LOG_DEBUG_DAP);
					// Charge the Customer instead of the card
					if (strtoupper($req['is_recurring']) == "Y") {
						logToFile("Dap_Payment:create_stripe_subscription().buynow recurring item = " . $req["item_name"], LOG_DEBUG_DAP);
						try{
							
							if($stripe_instant_recurring_charge=="Y") {
								logToFile("Dap_payment.class.php: create_stripe_subscription: do instant charge + plan");
									
								Stripe_Charge::create(array( "amount" => $post_values['amount'] * 100,
								"currency" =>  $post_values['CURRENCYCODE'],
								"customer" => $customerId, 
								"description" => $post_values['item_name'] ) );
							}
							else {
								logToFile("Dap_payment.class.php: create_stripe_subscription: NO instant charge, just set Stripe plan");	
							}
							
							logToFile("Dap_Payment:create_stripe_subscription().buynow recurring item charged initial fee" . $post_values['amount'], LOG_DEBUG_DAP);
							
							//$today = strtotime($today);
							$first_subs_due_in_days = $product->getRecurring_cycle_1();
							$today = date('Y-m-d');
							//$first_subs_due_date = strtotime(date("Y-m-d", time() + $first_subs_due_in_days));
							$first_subs_due_date = strtotime( $today . " + " . $first_subs_due_in_days . " day" );
							logToFile("Dap_Payment:create_stripe_subscription().first_subs_due_date=".$first_subs_due_date, LOG_DEBUG_DAP);
							
							if( (isset($cpn)) && ($_SESSION["new_amount"]!="")) {
								logToFile("Dap_Payment:create_stripe_subscription().ready to call updateSubscription, buynow, total recur=".$total_recur, LOG_DEBUG_DAP);
								if((int)$total_recur>0) {
									logToFile("Dap_Payment:create_stripe_subscription().ready to call updateSubscription, buynow, set max occurrences in stripe=".$total_recur, LOG_DEBUG_DAP);
									$customer->updateSubscription(array("plan" => $post_values['item_name'], "prorate" => false, "coupon" => $cpn, "trial_end" => $first_subs_due_date, "max_occurrences"=>$total_recur));
								}
								else {
									$customer->updateSubscription(array("plan" => $post_values['item_name'], "prorate" => false, "coupon" => $cpn, "trial_end" => $first_subs_due_date));
								}
								
								$customer->save();
								logToFile("Dap_Payment:create_stripe_subscription().pay invoice", LOG_DEBUG_DAP);
							}
							else {
								logToFile("Dap_Payment:create_stripe_subscription().ready to call updateSubscription. no coupon, total recur=".$total_recur, LOG_DEBUG_DAP);
								try{
									if((int)$total_recur>0) {
										logToFile("Dap_Payment:create_stripe_subscription().ready to call updateSubscription. set max occurrences in stripe to ".$total_recur, LOG_DEBUG_DAP);
										$customer->updateSubscription(array("plan" => $post_values['item_name'], "prorate" => false, "trial_end" => $first_subs_due_date, "max_occurrences"=>$total_recur));
									}
									else {
										$customer->updateSubscription(array("plan" => $post_values['item_name'], "prorate" => false, "trial_end" => $first_subs_due_date));
									}
									
									$customer->deleteDiscount();
									$customer->save();
									logToFile("Dap_Payment:create_stripe_subscription().update subscription, no coupon (null)" . $post_values['item_name'], LOG_DEBUG_DAP);							
									}
								catch (Exception $e) {
									$errmsg=$e->getMessage();
									if($errmsg == "Nothing to invoice for customer") {
									  logToFile("Dap_payment.class.php: create_stripe_subscription: updateSubscription: exception: ". $errmsg);
									}
								}
							}	
								
						}
						catch (Exception $e) {
							$errmsg=$e->getMessage();
							if($errmsg == "Nothing to invoice for customer") {
							  logToFile("Dap_payment.class.php: create_stripe_subscription: exception: ". $errmsg);
							}
						}	
						
						logToFile("Dap_Payment:create_stripe_subscription().paid invoice for plan successfully", LOG_DEBUG_DAP);
						$post_values["txn_type"]='subsc';
						// do we have to call the charge specifically for subscription item?
					}
					else {
						logToFile("Dap_Payment:create_stripe_subscription().buynow START - non recurring", LOG_DEBUG_DAP);
						
						Stripe_Charge::create(array( "amount" => $post_values['amount'] * 100,
							"currency" =>  $post_values['CURRENCYCODE'],
							"customer" => $customerId, 
							"description" => $post_values['item_name'] ) );
						
						/*if(isset($cpn)) {
						Stripe_Charge::create(array( "amount" => $post_values['amount'] * 100,
							"currency" =>  $post_values['CURRENCYCODE'],
							"customer" => $customerId, 
							"description" => $post_values['item_name'] ) );
						
						$invoiceitem = Stripe_InvoiceItem::create(array(
							"customer" => $customerId, 
							"amount" => $post_values['amount'] * 100, # amount in cents, again
							"currency" => $post_values['CURRENCYCODE'], 
							"description" => $post_values['item_name'],
							"coupon" => $cpn) );
						}
						else {
							
							
						/*$invoiceitem = Stripe_InvoiceItem::create(array(
							"customer" => $customerId, 
							"amount" => $post_values['amount'] * 100, # amount in cents, again
							"currency" => $post_values['CURRENCYCODE'], 
							"description" => $post_values['item_name'] ) );
							$id=$invoiceitem["id"];
						$invoice=Stripe_Invoice::retrieve($id);
						$invoice->pay();
						}
						*/
						$post_values["txn_type"]='buynow';
					
						logToFile("Dap_payment.class.php: create_stripe_subscription: paid invoice");
					}
					$success = 'Your payment was successful.';
					
					$ret=$this->processStripePayment($post_values, $customerId, "Dap_Payment");
					if($ret==true) {
						$_SESSION["stripe_item_name"]=$post_values['item_name'];
						logToFile("Dap_payment.class.php: create_stripe_subscription: purchase was successful, set item name in session to prevent multiple purchases of same item");
					}
					else {
						logToFile("Dap_payment.class.php: create_stripe_subscription: purchase was not successful, do not set item name in session");
					}
				} //if $_SESSION["stripe_item_name"]
			  
			} //else (buynow)
			
			logToFile("Dap_payment.class.php: create_stripe_subscription: REDIREC TO  payment_succ_page = " . $req["payment_succ_page"]);
			
			
			$user = Dap_User::loadUserByEmail($post_values['email']);
				
			if(isset($user)) {
				logToFile("Dap_Payment.class.php:  create_stripe_subscription(): userExists=Y, call updateCustomFields", LOG_DEBUG_DAP);
				$ret = $this->updateCustomFields ($req, $user->getId() );
				logToFile("Dap_Payment.class.php:  create_stripe_subscription(): userExists=" . $userExists, LOG_DEBUG_DAP);
			}
			else {
					logToFile("Dap_Payment.class.php:  create_stripe_subscription(): user not found=" . $post_values['email'], LOG_DEBUG_DAP);
			}
			
			logToFile("Dap_payment.class.php: create_stripe_subscription: STRIPE PAYMENT SUCCESSFUL");
								
			if( (isset($req["is_last_upsell"])) && ($req["is_last_upsell"]=="YES") )  {
				logToFile("Dap_Payment.class.php:  create_authnet_subscription(): IS LAST UPSELL: ". $req["is_last_upsell"], LOG_DEBUG_DAP);
				$this->emptyCart();
				$this->emptyCC();
			}
			
			$_SESSION['new_amount']="";
			if ( ($userExists == "N") || ((isset($_SESSION["userexistsbutallow"])) && ($_SESSION["userexistsbutallow"] == "Y")) ) {
				logToFile("Dap_Payment:create_stripe_subscription(): redirecting to authenticate.php to auto login user". $record_id);									
				$_SESSION["userexistsbutallow"]="";
				unset($_SESSION["userexistsbutallow"]);
				$user->setStatus("A");
				$user->update();
	
				logToFile("Dap_Payment:create_stripe_subscription(): pass=".$user->getPassword() );	
				header("Location: /dap/authenticate.php?email=" . urlencode($post_values['email']) . "&password=" . $user->getPassword() . "&submitted=Y&request=".$req['payment_succ_page']);
				exit;
			}
					
			header("Location: " .$req["payment_succ_page"]);
			exit;
		}
		catch (Exception $e) {
		  $_SESSION['err_text']=$e->getMessage();
		  logToFile("Dap_payment.class.php: create_stripe_subscription: exception: ". $_SESSION['err_text']);

		  header("Location: ".$payment_url."?err_text=".$_SESSION['err_text']);
		}	
		
		
		/*Stripe_Charge::create(array("amount" => $post_values['x_amount'] * 100 ,
			  "currency" => strtolower($post_values['CURRENCYCODE']),
			  "card" => $req['stripeToken']));
			  */
	}
	
	
	public function processStripePayment($inp, $customerId, $source) {
		logToFile("Dap_Payment:processStripePayment(): " . $source . " response: ", $inp['email']); 

		if (!isset($inp['txn_type']) || ($inp['txn_type'] == "")) 
			$inp['txn_type'] = "subscr";

		if(!isset($inp['mc_gross'])) { 
			$inp['mc_gross'] = $inp["amount"];
		}
		
		if(!isset($inp['payment_status'])) { 
			$inp['payment_status'] = 'Completed';
		}
		
		$inp["mc_currency"]=$inp['CURRENCYCODE'];
		
		if ($inp['mc_currency'] == "") {
		  $inp['mc_currency'] = trim(Dap_Config::get('CURRENCY_SYMBOL'));	
		  if (!isset($inp['mc_currency']) || $inp['mc_currency'] == '')
			$inp['mc_currency'] = urlencode("USD");
		}
		
		logToFile("Dap_Payment:processStripePayment(): mc_currency:". $mc_currency);
		
		$ignore_dup_and_proceed = false;
		
		// set params
		$inp['first_name'] = $this->getFirst_name();
		$inp['last_name'] = $this->getLast_name();
		$inp['address1'] = $this->getAddress1();
		$inp['address2'] = $this->getAddress2();
		$inp['city'] = $this->getCity();
		$inp['state'] = $this->getState();
		$inp['zip'] = $this->getZip();
		$inp['country'] = $this->getCountry();
		$inp['phone'] = $this->getPhone();
		$inp['fax'] = $this->getFax();
		
		$inp['ship_to_first_name'] = $this->getShip_to_first_name();
		$inp['ship_to_last_name'] = $this->getShip_to_last_name();
		$inp['ship_to_address1'] = $this->getShip_to_address1();
		$inp['ship_to_address2'] = $this->getShip_to_address2();
		$inp['ship_to_city'] = $this->getShip_to_city();
		$inp['ship_to_state'] = $this->getShip_to_state();
		$inp['ship_to_zip'] = $this->getShip_to_zip();
		$inp['ship_to_country'] = $this->getShip_to_country();
		
		$invoice = isset($req['invoice']) ? $req['invoice'] : date(YmdHis);
		$inp["txn_id"]=$invoice;

		try {
			$record_id = Dap_PaymentProcessor::recordStripeIncoming($inp,$customerId);
			logToFile("Dap_Payment:processPaymentResponse(): recorded incoming stripe. id:". $record_id);
		} 
		catch (PDOException $e) {
			if(stristr($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: ") == FALSE) {
				logToFile($e->getMessage(),LOG_FATAL_DAP);
				throw $e;
				return false;
			}
			else {
				$ignore_dup_and_proceed = true;
				logToFile("Dap_Payment : " . $e->getMessage(),LOG_DEBUG_DAP);
			}
  		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
			return false;
		}

		if (!$ignore_dup_and_proceed) {
			Dap_Transactions::setRecordStatus($record_id, 1);
			logToFile("Dap_Payment:processStripePayment(): set record status to 1 for record_id=". $record_id);

			Dap_Transactions::processTransaction($record_id);
		}
		else if (strcmp($source, "Dap_Payment") == 0) {
			logToFile("Dap_Payment:processStripePayment(): silent post/notification came in first, ok to ignore initial payment's integrity constraint error, LOG_DEBUG_DAP");
		}
				
		return true;
		
	}
	
	
	public function getCustomerId($email)  { 
	
		$emailFilter=$email;
		$TransactionsList = Dap_Transactions::loadTransactionsByProcessor($transNumFilter, $emailFilter, $productIdFilter, $statusFilter, "STRIPE");
		
		
		$stripe_customer_id = "";
		
		foreach ($TransactionsList as $transaction) {
			parse_str($transaction->getTrans_blob(), $list);
			// logToFile("DAP-Upgrade-Button: Payment processor is paypal, setting address details before list",LOG_INFO_DAP); 
			
			if (($list == NULL) || !isset($list)) {
			 logToFile("DAP-Upgrade-Button::LIST EMPTY"); 
			 break;
			}
					
			logToFile("getCustomerId()::TRANSACTION TYPE  =  " . $transaction->getTrans_type()); 
			
			$payment_processor = $transaction->getPayment_processor();
			if (strtoupper($payment_processor) != "STRIPE") {
			  continue;
			}
					
			if(array_key_exists('stripe_customer_id',$list)) {
			  logToFile("getCustomerId()::FOUND stripe_customer_id".$stripe_customer_id); 
			  $stripe_customer_id = $list["stripe_customer_id"];
			  return $stripe_customer_id;
			}
			
		} //foreach transaction
		
		return $stripe_customer_id;
	}
	
	
	public function process_stripe_1clickpurchase($emailId,$productId,$customerId,$currency)  // AIM
	{
		logToFile("DAP payment class : process_stripe_1clickpurchase(): ENTER: EMAIL=$emailId,PRODUCTID=$productId,CUSTID=$customerId",LOG_INFO_DAP);
		
		$post_values = array();
		
		$stripePublishableKey=Dap_Config::get('STRIPE_SECRET_KEY');
		Stripe::setApiKey($stripePublishableKey);
				
		if (!isset($_SESSION['customerId']) || ($_SESSION['customerId'] == "")) {
		  logToFile("DAP payment class : process_stripe_1clickpurchase(): purchase request didnot come in from the right channel",LOG_DEBUG_DAP);
		  $_SESSION["1clickbuyerr"]="Sorry, purchase request didnot come in from the 1click button";
		  $subject="DAP-1ClickBuy: purchase request didnot come in from the 1click button";
		  $body="DAP-1ClickBuy: : Sorry, could not complete purchase for user=".$emailId.", for productId=".$productId.". Someone tried to access the 1click script directly instead of clicking the payment button to complete purchase.";
		  
		  sendAdminEmail($subject, $body);
		  return -1;
	 	}
		
		$post_values['payment_gateway']="stripe";
		$user = Dap_User::loadUserByEmail($emailId);
		
		if(!isset($user)) {
			logToFile("DAP payment class : process_stripe_1clickpurchase(): no user found: ".$emailId,LOG_DEBUG_DAP);
			$_SESSION["1clickbuyerr"]="Sorry, user Id not found";
			$subject="DAP-1ClickBuy: user not found";
		    $body="DAP-1ClickBuy: : Sorry, could not complete purchase for user=".$emailId.", for productId=".$productId.". The 1clickbuy option is only for existing customers but could not find this user in the DAP Users page.";
			sendAdminEmail($subject, $body);
			return -1;
 		}
		
		
		
		// $0 trial not supported, if trial amount is not set, the user is charged the recurring price as the initial price
	    
		$stripe_instant_recurring_charge="Y";

		$product = Dap_Product::loadProduct($productId);
		if(!isset($product)) {
			logToFile("DAP payment class : process_stripe_1clickpurchase(): no product found: ".$productId,LOG_DEBUG_DAP);
			$_SESSION["1clickbuyerr"]="Sorry, product not found";
			$subject="DAP-1ClickBuy: product not found";
		    $body="DAP-1ClickBuy: : Sorry, could not complete purchase for user=".$emailId.", for productId=".$productId.". Could not find this product in DAP products page.";
			sendAdminEmail($subject, $body);
			return -1;
		}
		
		$productId = $product->getId();
		
		logToFile("DAP payment class : process_stripe_1clickpurchase(): productId=" .$productId, LOG_DEBUG_DAP);
		$item_id=$product->getId();
		$item_name = $product->getName();
		$item_desc = $product->getDescription();
		$trial_amount = $product->getTrial_price();
		$amount = $product->getPrice();
	
		$is_recur = $product->getIs_recurring();
		
		$recurring_cycle_1 = $product->getRecurring_cycle_1();
		$recurring_cycle_2 = $product->getRecurring_cycle_2();
		$recurring_cycle_3 = $product->getRecurring_cycle_3();
		$total_occurrences = $product->getTotal_occur();
		
		// if trial period, set amount to the trial amount
		if ( isset($trial_amount) && ($trial_amount != "0.00") && ($trial_amount != "0.0") && ($trial_amount != "0") ) 			
			$amount = $trial_amount;
		else  
			$amount = $amount;
		
		$post_values['amount'] = $amount;
		
		$amt=(int)$amount;
		if($amt==0) {
			logToFile("DAP payment class : process_stripe_1clickpurchase(): price not set: ".$productId,LOG_DEBUG_DAP);
			$_SESSION["1clickbuyerr"]="Sorry, could not complete purchase. Missing product price.";
			
			$subject="DAP-1ClickBuy:  Missing product price";
			$body="DAP-1ClickBuy: : Sorry, could not complete purchase for user=".$emailId.", for productId=".$productId.". Missing product price.";
			
			sendAdminEmail($subject, $body);
			return -1;	
		}

		logToFile("DAP payment class : process_stripe_1clickpurchase():, trial_amount=" . $trial_amount, LOG_DEBUG_DAP);
		logToFile("DAP payment class : process_stripe_1clickpurchase():, amount=" . $amount, LOG_DEBUG_DAP);
		logToFile("DAP payment class : process_stripe_1clickpurchase():. item_name= " . $post_values['item_name'], LOG_DEBUG_DAP);
		
		$post_values['item_name'] = $item_name;
		$post_values['CURRENCYCODE']=$currency;
		
		$post_values["txn_type"]='buynow';
		$post_values["payer_email"]=$emailId;
		$post_values["email"]=$emailId;
		try {
			
			// check if customer id exists
			if($customerId != "") {
			  	logToFile("DAP payment class : process_stripe_1clickpurchase(): FOUND CUSTOMER ID : EXISTING CUSTOMER: create_stripe_subscription get Customer Details");
				$customer = Stripe_Customer::retrieve($customerId);	
				logToFile("DAP payment class : process_stripe_1clickpurchase(): create_stripe_subscription: retrieved customer details, id=".$customerId);
			}
			else {
				logToFile("DAP payment class : process_stripe_1clickpurchase(): no customerid found: ".$customerId,LOG_DEBUG_DAP);
				$_SESSION["1clickbuyerr"]="Sorry, customer Id not found";
				
				$subject="DAP-1ClickBuy: Stripe Customer Id not found in existing orders for this user";
		    	$body="DAP-1ClickBuy: : Sorry, could not complete purchase for user=".$emailId.", for productId=".$productId.". Could not find this user's Stripe customer id in the DAP payments=>orders page.";
				sendAdminEmail($subject, $body);
				return -1;
			}
		
			$product=Dap_Product::loadProductByName($post_values['item_name']);
			logToFile("DAP payment class : process_stripe_1clickpurchase():.buynow START", LOG_DEBUG_DAP);
			// Charge the Customer instead of the card
			if (strtoupper($is_recur) == "Y") {
				logToFile("DAP payment class : process_stripe_1clickpurchase():.buynow recurring item = " .  $item_name, LOG_DEBUG_DAP);
				try{
					if($stripe_instant_recurring_charge=="Y") {
						logToFile("DAP payment class : process_stripe_1clickpurchase():do instant charge + plan");
							
						Stripe_Charge::create(array( "amount" => $post_values['amount'] * 100,
						"currency" =>  $post_values['CURRENCYCODE'],
						"customer" => $customerId, 
						"description" => $post_values['item_name'] ) );
					}
					else {
						logToFile("DAP payment class : process_stripe_1clickpurchase(): NO instant charge, just set Stripe plan");	
					}
					
					logToFile("DAP payment class : process_stripe_1clickpurchase(): buynow recurring item charged initial fee" . $post_values['amount'], LOG_DEBUG_DAP);
					
					//$today = strtotime($today);
					$first_subs_due_in_days = $product->getRecurring_cycle_1();
					$today = date('Y-m-d');
					//$first_subs_due_date = strtotime(date("Y-m-d", time() + $first_subs_due_in_days));
					$first_subs_due_date = strtotime( $today . " + " . $first_subs_due_in_days . " day" );
					logToFile("DAP payment class : process_stripe_1clickpurchase():.first_subs_due_date=".$first_subs_due_date, LOG_DEBUG_DAP);
					logToFile("DAP payment class : process_stripe_1clickpurchase():.ready to call updateSubscription", LOG_DEBUG_DAP);
					
					try{
						$customer->updateSubscription(array("plan" => $post_values['item_name'], "prorate" => false, "trial_end" => $first_subs_due_date));
						$customer->save();
						logToFile("DAP payment class : process_stripe_1clickpurchase():.update subscription: item=" . $post_values['item_name'], LOG_DEBUG_DAP);							
						}
					catch (Exception $e) {
						$errmsg=$e->getMessage();
						$_SESSION["1clickbuyerr"]=$errmsg;
						
						if($errmsg == "Nothing to invoice for customer") {
						  logToFile("DAP payment class : process_stripe_1clickpurchase(): updateSubscription: exception: ". $errmsg);
						}
					}
				}
				catch (Exception $e) {
					$errmsg=$e->getMessage();
					 $_SESSION["1clickbuyerr"]=$errmsg;
					 
					if($errmsg == "Nothing to invoice for customer") {
					  logToFile("DAP payment class : process_stripe_1clickpurchase(): exception: ". $errmsg);
					}
					
					$subject="DAP-1ClickBuy: Stripe purchase could not be completed";
					$body="DAP-1ClickBuy: : Sorry, could not complete purchase for user=".$emailId.", for product=".$productId. ". Failed with error message: " . $errmsg;
		 	
					sendAdminEmail($subject, $body);
					return -1;
				}	
				
				logToFile("DAP payment class : process_stripe_1clickpurchase(): paid invoice for plan successfully", LOG_DEBUG_DAP);
				$post_values["txn_type"]='stripe_subsc';
				// do we have to call the charge specifically for subscription item?
			}
			else {
				logToFile("DAP payment class : process_stripe_1clickpurchase(): buynow START - non recurring", LOG_DEBUG_DAP);
				
				Stripe_Charge::create(array( "amount" => $post_values['amount'] * 100,
					"currency" =>  $post_values['CURRENCYCODE'],
					"customer" => $customerId, 
					"description" => $post_values['item_name'] ) );
				
				$post_values["txn_type"]='stripe_buy';
				
				logToFile("DAP payment class : process_stripe_1clickpurchase(): paid invoice");
			}
			
			$success = 'Your payment was successful.';
			
			// set params
			$post_values['first_name'] = $user->getFirst_name();
			$post_values['last_name'] = $user->getLast_name();
			$post_values['address1'] = $user->getAddress1();
			$post_values['address2'] = $user->getAddress2();
			$post_values['city'] = $user->getCity();
			$post_values['state'] = $user->getState();
			$post_values['zip'] = $user->getZip();
			$post_values['country'] = $user->getCountry();
			$post_values['phone'] = $user->getPhone();
			$post_values['fax'] = $user->getFax();
			
			$post_values['ship_to_first_name'] = $user->getFirst_name();
			$post_values['ship_to_last_name'] = $user->getLast_name();
			$post_values['ship_to_address1'] = $user->getAddress1();
			$post_values['ship_to_address2'] = $user->getAddress2();
			$post_values['ship_to_city'] = $user->getCity();
			$post_values['ship_to_state'] = $user->getState();
			$post_values['ship_to_zip'] = $user->getZip();
			$post_values['ship_to_country'] = $user->getCountry();
		
		
			$this->processStripePayment($post_values, $customerId, "Dap_Payment");
		    
			logToFile("DAP payment class : process_stripe_1clickpurchase(): payment completed successfully");
			
			return 0;
		}
		catch (Exception $e) {
		  $_SESSION['1clickbuyerr']=$e->getMessage();
		  logToFile("DAP payment class : process_stripe_1clickpurchase(): exception: ". $_SESSION['err_text']);
		  
		  $subject="DAP-1ClickBuy: Stripe purchase could not be completed";
		  $body="DAP-1ClickBuy: : Sorry, could not complete purchase for user=".$emailId.", for productId=".$productId. ". Failed with error message: " . $_SESSION['err_text'];
		 	
		  sendAdminEmail($subject, $body);
			
		  return -1;
		}	
		
	} //end function
	
	

	
} //end class

?>
