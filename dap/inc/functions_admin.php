<?php 
	//$folderFilter = array("cbdp");
	register_shutdown_function("tooManyFiles");
	$depth = -1;
	$response = "";
	global $transaction_statuses;

		
	function viewSSSOrderHTML($childproductresourceNameArray) {
		
		$viewOrderHTML = '<a href="javascript:" onClick=toggleDiv("view_order_div"); return false;" class="bodytextLarge">close</a> <br/>';
		
		$viewOrderHTML .= '<table id="viewSSSOrderTable" width="90%"  border="1" cellspacing="5" cellpadding="5">';
		$viewOrderHTML .= '<tr class="scriptsubheading" bgcolor="#EFEFEF" >
		<td>Product Name</td>
		<td>Resource Name</td>
		<td>Credits</td>
		';
		
		$totalCreditsUsedup = 0;
		
		$childproductresource = explode(",", $childproductresourceNameArray);

		foreach($childproductresource as $resource) {
					
			$productresource = explode(":", $resource);
			$totalCreditsUsedup = $totalCreditsUsedup + $productresource[2];
			logToFile("functions_admin.php: name: " . $productresource[0], LOG_DEBUG_DAP);
			logToFile("functions_admin.php: resource name: " . $productresource[1], LOG_DEBUG_DAP);
			logToFile("functions_admin.php: credits: " . $productresource[2], LOG_DEBUG_DAP);
			$viewOrderHTML .= 
			"<tr align=\"left\" class='bodytextArial'>
				<td>" . mb_convert_encoding($productresource[0], "UTF-8", "auto") . "</td>
				<td>" . mb_convert_encoding($productresource[1], "UTF-8", "auto") . " </td>
				<td>" . mb_convert_encoding($productresource[2], "UTF-8", "auto") . " </td>
			</tr>";
		}
		
		
		$viewOrderHTML .= '</table>';
		
		$viewOrderHTML .= '&nbsp;&nbsp;<table id="viewSSSOrderTable" width="90%"  border="1" cellspacing="5" cellpadding="5">
			<tr align=\"left\" class="bodytextArial">
			<td align=\"right\"> Total Credits Used = ' . $totalCreditsUsedup . 
			'</tr> </table>';
			
		return $viewOrderHTML;
	}
		
		
	function generateIPNScript($ipnArray) {
		$formVar="";
		logToFile("I am in functions_admin, product name=".$ipnArray['success'],LOG_DEBUG_DAP);
		logToFile("I am in functions_admin, txn_id=".$ipnArray['txn_id'],LOG_DEBUG_DAP);
		
		$formVar = 	'<?php 
		include_once ("dap-config.php");
		
		$req = "cmd=_notify-validate";
		foreach ($_REQUEST as $key => $value) {
		$value = urlencode(stripslashes($value));
		$req .= "&$key=$value";
		logToFile("dap-ipn: " . $key . "=" . $value, LOG_DEBUG_DAP);
		}
		
		logToFile($req);
		
		$txn_num = 0;
		$i=1;
		$post = array();
		foreach($_REQUEST as $key=>$value)
		{
		$post[$key] = $value;
		}';
		
		$formVar .= "\n";
		
		if ($ipnArray['txn_id'] != '')
			$formVar .= '
			$post["txn_id"] = $post["' . $ipnArray['txn_id'] . '"];' .  "\n";
			
		if ($ipnArray['item_name'] != '')
			$formVar .= '
			$post["item_name"] = $post["' . $ipnArray['item_name'] . '"];' .  "\n";
		
		if ($ipnArray['txn_type'] != '')
			$formVar .= '
			$post["txn_type"] = $post["' . $ipnArray['txn_type'] . '"];' .  "\n";
		
		if ($ipnArray['buy_now'] != '')
			$formVar .= '
			$post["buy_now"] = $post["' . $ipnArray['buy_now'] . '"];' .  "\n";
			
		if ($ipnArray['subscription'] != '')
			$formVar .= '
			$post["subscription"] = $post["' . $ipnArray['subscription'] . '"];' .  "\n";
			
		if ($ipnArray['cart'] != '')
			$formVar .= '
			$post["cart"] = $post["' . $ipnArray['cart'] . '"];' .  "\n";
			
		
		if ($ipnArray['first_name'] != '')
			$formVar .= '
			$post["first_name"] = $post["' . $ipnArray['first_name'] . '"];' .  "\n";
		
		if ($ipnArray['last_name'] != '')
			$formVar .= '
			$post["last_name"] = $post["' . $ipnArray['last_name'] . '"];' .  "\n";
		
		if ($ipnArray['email'] != '')
			$formVar .= '
			$post["email"] = $post["' . $ipnArray['email'] . '"];' .  "\n";
	
		if ($ipnArray['address1'] != '')
			$formVar .= '
			$post["address1"] = $post["' . $ipnArray['address1'] . '"];' .  "\n";
	
		if ($ipnArray['city'] != '')
			$formVar .= '
			$post["city"] = $post["' . $ipnArray['city'] . '"];' .  "\n";
	
		if ($ipnArray['state'] != '')
			$formVar .= '
			$post["state"] = $post["' . $ipnArray['state'] . '"];' .  "\n";
	
		if ($ipnArray['zip'] != '')
			$formVar .= '
			$post["zip"] = $post["' . $ipnArray['zip'] . '"];' .  "\n";
	
		if ($ipnArray['country'] != '')
			$formVar .= '
			$post["country"] = $post["' . $ipnArray['country'] . '"];' .  "\n";
	
		if ($ipnArray['phone'] != '')
			$formVar .= '
			$post["phone"] = $post["' . $ipnArray['phone'] . '"];' .  "\n";
	
		if ($ipnArray['ship_to_first_name'] != '')
			$formVar .= '
			$post["ship_to_first_name"] = $post["' . $ipnArray['first_name'] . '"];' .  "\n";
	
		if ($ipnArray['ship_to_last_name'] != '')
			$formVar .= '
			$post["ship_to_last_name"] = $post["' . $ipnArray['last_name'] . '"];' .  "\n";
	
		if ($ipnArray['ship_to_address1'] != '')
			$formVar .= '
			$post["ship_to_address1"] = $post["' . $ipnArray['address1'] . '"];' .  "\n";
		
		if ($ipnArray['ship_to_city'] != '')
			$formVar .= '
			$post["ship_to_city"] = $post["' . $ipnArray['city'] . '"];' .  "\n";
		
		if ($ipnArray['ship_to_state'] != '')
			$formVar .= '
			$post["ship_to_state"] = $post["' . $ipnArray['state'] . '"];' .  "\n";
		
		if ($ipnArray['ship_to_zip'] != '')
			$formVar .= '
			$post["ship_to_zip"] = $post["' . $ipnArray['zip'] . '"];' .  "\n";
		
		if ($ipnArray['ship_to_country'] != '')
			$formVar .= '
			$post["ship_to_country"] = $post["' . $ipnArray['country'] . '"];' .  "\n";
		
		if ($ipnArray['amount'] != '')
			$formVar .= '
			$post["amount"] = $post["' . $ipnArray['amount'] . '"];' .  "\n";
		
		if ($ipnArray['mc_currency'] != '')
			$formVar .= '
			$post["mc_currency"] = $post["' . $ipnArray['mc_currency'] . '"];' .  "\n";
	
		if ($ipnArray['payment_status'] != '')
			$formVar .= '
			$post["payment_status"] = $post["' . $ipnArray['payment_status'] . '"];' .  "\n";
		
		if ($ipnArray['payment_processor'] != '')
			$formVar .= '
			$post["payment_processor"] = "' . $ipnArray['payment_processor'] . '";' .  "\n";
		
		if ($ipnArray['secret_key'] != '')
			$formVar .= '
			$post["secret_key"] = "' . $ipnArray['secret_key'] . '";' .  "\n";	

		$formVar .= '
		
			if( isset($post["secret_key"]) || ($post["secret_key"] != "") ) {
				if (securityCheck($post) == -1) {
					return;
				}
			}';
		
		$formVar .= '
			$txn_num = 0;
			$i=1;	
			if ( !isset($post["txn_id"]) || ($post["txn_id"] == "")) {
				$post["txn_id"] = rand() + 10000001;
			}
			if ( !isset($post["mc_currency"]) || ($post["mc_currency"] == "")) {
				$post["mc_currency"] = "USD";
			}
			
			while (true) 
			{
				logToFile("dap-ipn: success val" . $post["success"], LOG_DEBUG_DAP);
				logToFile("dap-ipn: payment_status" . $post["payment_status"], LOG_DEBUG_DAP);
				
				if(strcmp($post["payment_status"],"';
			
			$formVar .= $ipnArray['success'];
			
			$formVar .=	'") == 0) 
				{
					$post["payment_status"] = "Completed";
					if(strcmp($post["txn_type"],"cart") == 0) 
					{
						$post["item_name"] = isset($_REQUEST["item_name" . $i]) ? $_REQUEST["item_name" . $i] : "";
							
						if ($post["item_name"] == "") {
							break;
						}
						
						$post["quantity"] = isset($_REQUEST["quantity" . $i]) ? $_REQUEST["quantity" . $i] : "";
					
						if(isset($post["amount" . $i]))
							$post["mc_gross"] = $post["amount" . $i];
						
						$post["txn_id"] = $post["txn_id"] . "-" . $i;
						processIPNResponse($req, $post, $i);
						$i++;
						$txn_num++;
					}
					else {
						$post["mc_gross"] = $post["amount"];
						processIPNResponse($req, $post, $i);
						break;
					}
				}
				else 
				{
						$dap_error = "payment status = " . $post["status"] . " for the user = " . $post["email"];
						logToFile("dap-ipn: " . $dap_error, LOG_DEBUG_DAP);
						sendAdminEmail("dap-ipn:payment not accepted", $dap_error);
						return;	
				}
				
				echo "SUCCESS!!!";
			
			}';
		
		
		$formVar .= '
			function processIPNResponse($req, $post, $i) 
			{
				//translate
				
				logToFile("processIPNResponse", LOG_DEBUG_DAP);
				$post["payer_email"] = $post["email"];
				
				if (securityCheck($post) == -1) {
					return;
				}
				
				$record_id = Dap_IPNProcessor::recordIPNIncoming($post);
	
				logToFile("dap-ipn-script.php: Recorded incoming . id:". $record_id);
				//$original_request = $req;
				// post back to PayPal system to validate
				
				if ($record_id != -1) {
					//finally process the transaction
					Dap_Transactions::setRecordStatus($record_id, 1);
					Dap_Transactions::processTransaction($record_id);
				}
				
				$user = Dap_User::loadUserByEmail($post["payer_email"]);
				if(isset($user)) {
					logToFile("dap-ipn-script.php: user all set. SUCCESS". $record_id);
					
				}
				else {
					logToFile("dap-ipn.php: user not found, contact site admin". $record_id);
				}
			}
			
			function securityCheck($post) {
				logToFile("Running securityCheck");
				if ($post["secret_key"] != Dap_Config::get("SECRET_KEY")) {
					$dap_error = "security check failed: the cartId in payment notification does not match the cartId set in in dap setup -> config -> payment processing -> secret key  for the user = " . $post["email"];
					sendAdminEmail("';
		
		$formVar .= $ipnArray["payment_processor"] . ': security check failed", $dap_error);
					echo "ERROR: " . $dap_error;
					return -1;
				}
				logToFile("dap-ipn.php: securityCheck successfully completed", LOG_DEBUG_DAP);
				return 0;
			}';

		$formVar .=  "\n" . '?>';
	
		//logToFile("formvar = ".$formVar,LOG_DEBUG_DAP); 
		
/*		$ourFileName = SITE_URL_DAP . '/dap/dap-IPN-script.php';
		try {
			$fp = fopen($ourFileName, 'a');
			
			if (fwrite($fp, $formVar) == FALSE) {
     	   	
				logToFile("Cannot write to file ($filename)",LOG_DEBUG_DAP);
      		exit;
    		}
			else {
						logToFile("done writing to file",LOG_DEBUG_DAP);
			}
			flush();
			fclose($fp);
			
		}
		catch (Exception $e) {
			logToFile("Error writing to file = " . $e->getMessage(),LOG_DEBUG_DAP);
		}*/
		
		
		return $formVar;
	}
	

	function loadConfigDisplayHTML($cat) { //Load config table with HTML
		$configArray = Dap_Config::loadConfig(true,$cat);
		//$configArray = Dap_Session::get('config');
		$html = "<table width='800' border='0' cellspacing='10' cellpadding='5' class='bodytextArial'>";
		$html .= "<tr align='left'>
			<td><b>Description</b></td>
			<td><b>Value</b></td>
			<td><b>Action</b></td>
		</tr>
		";
		$i = 1;
		foreach($configArray as $row) {
			$html .= "<tr align='left'>
				<td valign='top'>".$row["description"]."</td>";
				
			if($row["editable"] == "Y") {
				if($row["type"] == "html_text") {
					logToFile("Config value: " . $row["value"],LOG_DEBUG_DAP);
					$html .= "<td><input type='text' name='config_".$row["name"]."' size='50' max='40' value='".$row["value"]."'></td>";
				} else if($row["type"] == "html_textarea") {
					$html .= "<td><textarea name='config_".$row["name"]."' cols='50' rows='10'>".$row["value"]."</textarea></td>";
				} else if($row["type"] == "html_select") {
					$values = explode("|",$row["values"]);
					$html .= "<td><select name='config_".$row["name"]."'>";
					foreach ($values as $value) {
						if($value == $row["value"]) {
							$selected = " selected ";
						} else {
							$selected = "";
						}
						$html .= "<option $selected value=\"".$value."\">".$value."</option>";
					}
					$html .= "</select></td>";
				} 
	
				$html .= "<td><input type='button' value='Update' name='button_updateConfig".$i."' onClick='modButton(this); updateConfig(".$row["id"].",this.form.config_".$row["name"].".value);'></td>
					</tr>";
			}
			
			else if($row["editable"] == "N") {
				$html .= "<td>" . $row["value"] . "</td>";
				$html .= "<td>&nbsp;</td></tr>";
			}

			$i++;
		}
		$html .= "</table>";
		//logToFile("HTML from loadConfigDisplayHTML: " . $html,LOG_DEBUG_DAP); 
		$configArray = Dap_Config::loadConfig(true);
		return $html;
	}
	
	function html_encode($var) {
		return htmlentities($var, ENT_QUOTES, 'UTF-8') ;
	}

	function loadProductUsersForProductsPageDisplayHTML($productId) {
		
		$productUsersListHTML = '<table id="usersTable" width="100%"  border="0" cellspacing="5" cellpadding="0" class="bodytextArial">';
		$productUsersListHTML .= '<tr><td colspan="5" class="scriptheader">Filtered: Showing Users For Product</td></tr>';
		$rowsFound = false;

		try {
			$UsersList = Dap_User::loadUsersByProduct($productId);
		
			foreach ($UsersList as $user) {
				$rowsFound = true;
				$productUsersListHTML .=   
				"<tr bgcolor=\"#EFEFEF\" onClick=\"highlightRow(this,'usersTable');\">
					<td width=\"20\"><input name=\"form_selected_users[]\" value=" . $user->getId() . " type=\"checkbox\"></td>
					<td><a href='/dap/admin/addEditUsers.php?userId=" . $user->getId() . "'>" . $user->getId() . "</a></td>
					<td>" . $user->getFirst_name() . " " . $user->getLast_name() . "</td>
					<td>" . $user->getEmail() . "</td>
					<td class=\"bodytext\"><a href=\"#\" onClick=\"javascript:loadUserProductRel(".$user->getId()."," . $productId . ");\">manage access</a></td>
					<td class=\"bodytext\"><a href=\"manageCommissions.php?product_id=".$productId. "\">manage comm.</a></td>
				</tr>";
			}
			
			if($rowsFound == false) {
				$productUsersListHTML .= '<tr><td colspan="5">Sorry, no Users found for this Product.</td></tr>';
			}
		} catch (PDOException $e) {
			$productUsersListHTML .= '<tr><td colspan="5">' . ERROR_GENERAL . '</td></tr>';
		} catch (Exception $e) {
			$productUsersListHTML .= '<tr><td colspan="5">' . ERROR_GENERAL . '</td></tr>';
		}
		
		$productUsersListHTML .= '</table>';
		return $productUsersListHTML;
	}
	
	function generateAuthnet1ClickHTML($productId) {
		$formVar="";
		try {
			logToFile("I am in functions_admin, product=".$productId,LOG_DEBUG_DAP);

			$gateway_url = Dap_Config::get('gateway_url');
			$cmcc_acctnum = Dap_Config::get('CMCC_ACCTNUM');
			$payment_succ_page = SITE_URL_DAP. "/dap/continue.php?url=/dap/upsell2.html";
			$payment_succ_page= str_replace ( "http:", "https:", $payment_succ_page );
			
			$payment_err_page = SITE_URL_DAP. "/dap/paymentError.php";
			$site_url = SITE_URL_DAP;
			
			$hosted_cmcc=false;  
			if (isset($cmcc_acctnum) && ($cmcc_acctnum != '')) {
				$hosted_cmcc=true;  
				$buy_url = "https://contentresponder.com/cmcc/buy-submit.php";
			}
			else if (isset ($gateway_url)) {
				$hosted_cmcc=false;	
				$buy_url = SITE_URL_DAP . "/dap/buy-submit.php";
				$buy_url= str_replace ( "http:", "https:", $buy_url );
			}
			else {
				$formVar .= '<tr><td colspan="5">' . 'Sorry, missing payment config params' .$productId. '</td></tr>';
				return $formVar;	
			}
						
			$label = "Buy Now";
			
			$product = Dap_Product::loadProduct($productId);
			logToFile("product = ".$product->getName(),LOG_DEBUG_DAP); 

			if(isset($product)) {
				if ($hosted_cmcc == false) {
					logToFile("Self-hosted authnet",LOG_DEBUG_DAP);
					
					$formVar .= '<form name="generate_authnet" method="post" action="'.$buy_url.'">'."\n".
					'<input type="hidden" name="item_name" value="'.$product->getName().'"/>' ."\n".
					'<input type="hidden" name="description" value="'.$product->getDescription().'" />'."\n".
					'<input type="hidden" name="amount" value="'.$product->getPrice().'" />'."\n".
					'<input type="hidden" name="trial_amount" value="'.$product->getTrial_price().'" />'."\n".
					'<input type="hidden" name="total_occurrences" value="'.$product->getTotal_occur().'" />'."\n".
					'<input type="hidden" name="is_recurring" value="'.$product->getIs_recurring().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_1" value="'.$product->getRecurring_cycle_1().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_2" value="'.$product->getRecurring_cycle_2().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_3" value="'.$product->getRecurring_cycle_3().'" />'."\n".
					'<input type="hidden" name="payment_succ_page" value="'.$payment_succ_page.'" />'."\n".
					'<input type="hidden" name="payment_gateway" value="authnet" />'."\n".
					'<input type="hidden" name="is_submitted" value="Y" />'."\n".
					'<input type="submit" value="'.$label.'" />'."\n".
					'</form>';
										
				}
				else {
					logToFile("Hosted cmcc",LOG_DEBUG_DAP);
					
					$formVar .= '<form name="generate_authnet" method="post" action="'.$buy_url.'">'."\n".
					'<input type="hidden" name="item_name" value="'.$product->getName().'"/>' ."\n".
					'<input type="hidden" name="description" value="'.$product->getDescription().'" />'."\n".
					'<input type="hidden" name="amount" value="'.$product->getPrice().'" />'."\n".
					'<input type="hidden" name="trial_amount" value="'.$product->getTrial_price().'" />'."\n".
					'<input type="hidden" name="total_occurrences" value="'.$product->getTotal_occur().'" />'."\n".
					'<input type="hidden" name="is_recurring" value="'.$product->getIs_recurring().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_1" value="'.$product->getRecurring_cycle_1().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_2" value="'.$product->getRecurring_cycle_2().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_3" value="'.$product->getRecurring_cycle_3().'" />'."\n".
					'<input type="hidden" name="cmcc_acctnum" value="'.$cmcc_acctnum.'" />'."\n".
					'<input type="hidden" name="payment_succ_page" value="'.$payment_succ_page.'" />'."\n".
					'<input type="hidden" name="payment_gateway" value="authnet" />'."\n".
					'<input type="hidden" name="is_submitted" value="Y" />'."\n".
					'<input type="submit" value="'.$label.'" />'."\n".
					'</form>';
					
				}
				logToFile("formvar = ".$formVar,LOG_DEBUG_DAP); 
			}
			else {
				$formVar .= '<tr><td colspan="5">'.'Sorry, no product found for the ID'.$productId.'</td></tr>';
			}
		} catch (PDOException $e) {
			$formVar .= '<tr><td colspan="5">' . ERROR_GENERAL . '</td></tr>';
		} catch (Exception $e) {
			$formVar .= '<tr><td colspan="5">' . ERROR_GENERAL . '</td></tr>';
		}
		
		return $formVar;
	}
	
	function generateAuthnetHTML($productId) {
		$formVar="";
		try {
			logToFile("I am in functions_admin, product=".$productId,LOG_DEBUG_DAP);

			$gateway_url = Dap_Config::get('gateway_url');
			$cmcc_acctnum = Dap_Config::get('CMCC_ACCTNUM');
			$payment_succ_page = SITE_URL_DAP. "/dap/continue.php?url=/dap/upsell1.html";
			$payment_succ_page= str_replace ( "http:", "https:", $payment_succ_page );
			
			$payment_err_page = SITE_URL_DAP. "/dap/paymentError.php";
			$site_url = SITE_URL_DAP;
			
			$hosted_cmcc=false;  
			if (isset($cmcc_acctnum) && ($cmcc_acctnum != '')) {
				$hosted_cmcc=true;  
				$buy_url = "https://contentresponder.com/cmcc/buy.php";
			}
			else if (isset ($gateway_url)) {
				$hosted_cmcc=false;	
				$buy_url = SITE_URL_DAP . "/dap/buy.php";
				$buy_url= str_replace ( "http:", "https:", $buy_url );
			}
			else {
				$formVar .= '<tr><td colspan="5">' . 'Sorry, missing payment config params' .$productId. '</td></tr>';
				return $formVar;	
			}
						
			$label = "Buy Now";
			
			$product = Dap_Product::loadProduct($productId);
			logToFile("product = ".$product->getName(),LOG_DEBUG_DAP); 

			if(isset($product)) {
				if ($hosted_cmcc == false) {
					logToFile("Self-hosted authnet",LOG_DEBUG_DAP);
					
					$formVar .= '<form name="generate_authnet" method="post" action="'.$buy_url.'">'."\n".
					'<input type="hidden" name="item_name" value="'.$product->getName().'"/>' ."\n".
					'<input type="hidden" name="description" value="'.$product->getDescription().'" />'."\n".
					'<input type="hidden" name="amount" value="'.$product->getPrice().'" />'."\n".
					'<input type="hidden" name="trial_amount" value="'.$product->getTrial_price().'" />'."\n".
					'<input type="hidden" name="total_occurrences" value="'.$product->getTotal_occur().'" />'."\n".
					'<input type="hidden" name="is_recurring" value="'.$product->getIs_recurring().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_1" value="'.$product->getRecurring_cycle_1().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_2" value="'.$product->getRecurring_cycle_2().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_3" value="'.$product->getRecurring_cycle_3().'" />'."\n".
					'<input type="hidden" name="payment_succ_page" value="'.$payment_succ_page.'" />'."\n".
					'<input type="hidden" name="payment_gateway" value="authnet" />'."\n".
					'<input type="hidden" name="is_submitted" value="Y" />'."\n".
					'<input type="submit" value="'.$label.'" />'."\n".
					'</form>';
					
				}
				else {
					logToFile("Hosted cmcc",LOG_DEBUG_DAP);
					$formVar .= '<form name="generate_authnet" method="post" action="'.$buy_url.'">'."\n".
					'<input type="hidden" name="item_name" value="'.$product->getName().'"/>' ."\n".
					'<input type="hidden" name="description" value="'.$product->getDescription().'" />'."\n".
					'<input type="hidden" name="amount" value="'.$product->getPrice().'" />'."\n".
					'<input type="hidden" name="trial_amount" value="'.$product->getTrial_price().'" />'."\n".
					'<input type="hidden" name="total_occurrences" value="'.$product->getTotal_occur().'" />'."\n".
					'<input type="hidden" name="is_recurring" value="'.$product->getIs_recurring().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_1" value="'.$product->getRecurring_cycle_1().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_2" value="'.$product->getRecurring_cycle_2().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_3" value="'.$product->getRecurring_cycle_3().'" />'."\n".
					'<input type="hidden" name="cmcc_acctnum" value="'.$cmcc_acctnum.'" />'."\n".
					'<input type="hidden" name="payment_succ_page" value="'.$payment_succ_page.'" />'."\n".
					'<input type="hidden" name="payment_gateway" value="authnet" />'."\n".
					'<input type="hidden" name="is_submitted" value="Y" />'."\n".
					'<input type="submit" value="'.$label.'" />'."\n".
					'</form>';
					
				}
				logToFile("formvar = ".$formVar,LOG_DEBUG_DAP); 
			}
			else {
				$formVar .= '<tr><td colspan="5">'.'Sorry, no product found for the ID'.$productId.'</td></tr>';
			}
		} catch (PDOException $e) {
			$formVar .= '<tr><td colspan="5">' . ERROR_GENERAL . '</td></tr>';
		} catch (Exception $e) {
			$formVar .= '<tr><td colspan="5">' . ERROR_GENERAL . '</td></tr>';
		}
		
		return $formVar;
	}

	function generatePaypal1ClickHTML($productId) {
		$formVar="";
		try {
			logToFile("I am in functions_admin, product=".$productId,LOG_DEBUG_DAP);

			$gateway_url = Dap_Config::get('gateway_url');
			$cmcc_acctnum = Dap_Config::get('CMCC_ACCTNUM');
			$payment_succ_page = SITE_URL_DAP. "/dap/continue.php?url=/dap/upsell2.html";
			$payment_succ_page= str_replace ( "http:", "https:", $payment_succ_page );
			
			$payment_err_page = SITE_URL_DAP. "/dap/paymentError.php";
			$site_url = SITE_URL_DAP;
			
			$hosted_cmcc=false;  
			if (isset($cmcc_acctnum) && ($cmcc_acctnum != '')) {
				$hosted_cmcc=true;  
				$buy_url = "https://contentresponder.com/cmcc/buy-submit.php";
			}
			else if (isset ($gateway_url)) {
				$hosted_cmcc=false;	
				$buy_url = SITE_URL_DAP . "/dap/buy-submit.php";
				$buy_url= str_replace ( "http:", "https:", $buy_url );
			}
			else {
				$formVar .= '<tr><td colspan="5">' . 'Sorry, missing payment config params' .$productId. '</td></tr>';
				return $formVar;	
			}
						
			$label = "Buy Now";
			
			$product = Dap_Product::loadProduct($productId);
			logToFile("product = ".$product->getName(),LOG_DEBUG_DAP); 

			if(isset($product)) {
				if ($hosted_cmcc == false) {
					logToFile("Self-hosted authnet",LOG_DEBUG_DAP);
					
					$formVar .= '<form name="generate_paypal" method="post" action="'.$buy_url.'">'."\n".
					'<input type="hidden" name="item_name" value="'.$product->getName().'"/>' ."\n".
					'<input type="hidden" name="description" value="'.$product->getDescription().'" />'."\n".
					'<input type="hidden" name="amount" value="'.$product->getPrice().'" />'."\n".
					'<input type="hidden" name="trial_amount" value="'.$product->getTrial_price().'" />'."\n".
					'<input type="hidden" name="total_occurrences" value="'.$product->getTotal_occur().'" />'."\n".
					'<input type="hidden" name="is_recurring" value="'.$product->getIs_recurring().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_1" value="'.$product->getRecurring_cycle_1().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_2" value="'.$product->getRecurring_cycle_2().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_3" value="'.$product->getRecurring_cycle_3().'" />'."\n".
					'<input type="hidden" name="payment_succ_page" value="'.$payment_succ_page.'" />'."\n".
					'<input type="hidden" name="payment_gateway" value="paypal" />'."\n".
					'<input type="hidden" name="is_submitted" value="Y" />'."\n".
					'<input type="submit" value="'.$label.'" />'."\n".
					'</form>';
										
				}
				else {
					logToFile("Hosted cmcc",LOG_DEBUG_DAP);
					
					$formVar .= '<form name="generate_paypal" method="post" action="'.$buy_url.'">'."\n".
					'<input type="hidden" name="item_name" value="'.$product->getName().'"/>' ."\n".
					'<input type="hidden" name="description" value="'.$product->getDescription().'" />'."\n".
					'<input type="hidden" name="amount" value="'.$product->getPrice().'" />'."\n".
					'<input type="hidden" name="trial_amount" value="'.$product->getTrial_price().'" />'."\n".
					'<input type="hidden" name="total_occurrences" value="'.$product->getTotal_occur().'" />'."\n".
					'<input type="hidden" name="is_recurring" value="'.$product->getIs_recurring().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_1" value="'.$product->getRecurring_cycle_1().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_2" value="'.$product->getRecurring_cycle_2().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_3" value="'.$product->getRecurring_cycle_3().'" />'."\n".
					'<input type="hidden" name="cmcc_acctnum" value="'.$cmcc_acctnum.'" />'."\n".
					'<input type="hidden" name="payment_succ_page" value="'.$payment_succ_page.'" />'."\n".
					'<input type="hidden" name="payment_gateway" value="paypal" />'."\n".
					'<input type="hidden" name="is_submitted" value="Y" />'."\n".
					'<input type="submit" value="'.$label.'" />'."\n".
					'</form>';
					
				}
				logToFile("formvar = ".$formVar,LOG_DEBUG_DAP); 
			}
			else {
				$formVar .= '<tr><td colspan="5">'.'Sorry, no product found for the ID'.$productId.'</td></tr>';
			}
		} catch (PDOException $e) {
			$formVar .= '<tr><td colspan="5">' . ERROR_GENERAL . '</td></tr>';
		} catch (Exception $e) {
			$formVar .= '<tr><td colspan="5">' . ERROR_GENERAL . '</td></tr>';
		}
		
		return $formVar;
	}
	
	function generatePaypalHTML($productId) {
		$formVar="";
		try {
			logToFile("I am in functions_admin, product=".$productId,LOG_DEBUG_DAP);

			$gateway_url = Dap_Config::get('gateway_url');
			$cmcc_acctnum = Dap_Config::get('CMCC_ACCTNUM');
			$payment_succ_page = SITE_URL_DAP. "/dap/continue.php?url=/dap/upsell1.html";
			$payment_succ_page= str_replace ( "http:", "https:", $payment_succ_page );
			
			$payment_err_page = SITE_URL_DAP. "/dap/paymentError.php";
			$site_url = SITE_URL_DAP;
			
			$hosted_cmcc=false;  
			if (isset($cmcc_acctnum) && ($cmcc_acctnum != '')) {
				$hosted_cmcc=true;  
				$buy_url = "https://contentresponder.com/cmcc/buy.php";
			}
			else if (isset ($gateway_url)) {
				$hosted_cmcc=false;	
				$buy_url = SITE_URL_DAP . "/dap/buy.php";
				$buy_url= str_replace ( "http:", "https:", $buy_url );
			}
			else {
				$formVar .= '<tr><td colspan="5">' . 'Sorry, missing payment config params' .$productId. '</td></tr>';
				return $formVar;	
			}
						
			$label = "Buy Now";
			
			$product = Dap_Product::loadProduct($productId);
			logToFile("product = ".$product->getName(),LOG_DEBUG_DAP); 

			if(isset($product)) {
				if ($hosted_cmcc == false) {
					logToFile("Self-hosted authnet",LOG_DEBUG_DAP);
					
					$formVar .= '<form name="generate_paypal" method="post" action="'.$buy_url.'">'."\n".
					'<input type="hidden" name="item_name" value="'.$product->getName().'"/>' ."\n".
					'<input type="hidden" name="description" value="'.$product->getDescription().'" />'."\n".
					'<input type="hidden" name="amount" value="'.$product->getPrice().'" />'."\n".
					'<input type="hidden" name="trial_amount" value="'.$product->getTrial_price().'" />'."\n".
					'<input type="hidden" name="total_occurrences" value="'.$product->getTotal_occur().'" />'."\n".
					'<input type="hidden" name="is_recurring" value="'.$product->getIs_recurring().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_1" value="'.$product->getRecurring_cycle_1().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_2" value="'.$product->getRecurring_cycle_2().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_3" value="'.$product->getRecurring_cycle_3().'" />'."\n".
					'<input type="hidden" name="payment_succ_page" value="'.$payment_succ_page.'" />'."\n".
					'<input type="hidden" name="payment_gateway" value="paypal" />'."\n".
					'<input type="hidden" name="is_submitted" value="Y" />'."\n".
					'<input type="submit" value="'.$label.'" />'."\n".
					'</form>';
					
				}
				else {
					logToFile("Hosted cmcc",LOG_DEBUG_DAP);
					$formVar .= '<form name="generate_paypal" method="post" action="'.$buy_url.'">'."\n".
					'<input type="hidden" name="item_name" value="'.$product->getName().'"/>' ."\n".
					'<input type="hidden" name="description" value="'.$product->getDescription().'" />'."\n".
					'<input type="hidden" name="amount" value="'.$product->getPrice().'" />'."\n".
					'<input type="hidden" name="trial_amount" value="'.$product->getTrial_price().'" />'."\n".
					'<input type="hidden" name="total_occurrences" value="'.$product->getTotal_occur().'" />'."\n".
					'<input type="hidden" name="is_recurring" value="'.$product->getIs_recurring().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_1" value="'.$product->getRecurring_cycle_1().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_2" value="'.$product->getRecurring_cycle_2().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_3" value="'.$product->getRecurring_cycle_3().'" />'."\n".
					'<input type="hidden" name="cmcc_acctnum" value="'.$cmcc_acctnum.'" />'."\n".
					'<input type="hidden" name="payment_succ_page" value="'.$payment_succ_page.'" />'."\n".
					'<input type="hidden" name="payment_gateway" value="paypal" />'."\n".
					'<input type="hidden" name="is_submitted" value="Y" />'."\n".
					'<input type="submit" value="'.$label.'" />'."\n".
					'</form>';
					
				}
				logToFile("formvar = ".$formVar,LOG_DEBUG_DAP); 
			}
			else {
				$formVar .= '<tr><td colspan="5">'.'Sorry, no product found for the ID'.$productId.'</td></tr>';
			}
		} catch (PDOException $e) {
			$formVar .= '<tr><td colspan="5">' . ERROR_GENERAL . '</td></tr>';
		} catch (Exception $e) {
			$formVar .= '<tr><td colspan="5">' . ERROR_GENERAL . '</td></tr>';
		}
		
		return $formVar;
	}

	function generatePaypalStandard1ClickHTML($productId) {
		$formVar="";
		try {
			logToFile("I am in functions_admin, product=".$productId,LOG_DEBUG_DAP);

			$gateway_url = Dap_Config::get('gateway_url');
			$cmcc_acctnum = Dap_Config::get('CMCC_ACCTNUM');
			
/*			$payment_succ_page = SITE_URL_DAP. "/dap/PaypalCheckoutConfirm.php";
			$payment_succ_page= str_replace ( "http:", "https:", $payment_succ_page );
*/
			
			$payment_succ_page = "/dap/PaypalCheckoutConfirm.php";
			
			$payment_err_page = SITE_URL_DAP. "/dap/paymentError.php";
			$site_url = SITE_URL_DAP;
			
			$hosted_cmcc=false;  
			if (isset($cmcc_acctnum) && ($cmcc_acctnum != '')) {
				$hosted_cmcc=true;  
				$buy_url = "https://contentresponder.com/cmcc/PaypalAddToCart.php";
			}
			else if (isset ($gateway_url)) {
				$hosted_cmcc=false;	
			//	$buy_url = SITE_URL_DAP . "/dap/PaypalAddToCart.php";
			//	$buy_url= str_replace ( "http:", "https:", $buy_url );
			
				$buy_url = "/dap/PaypalAddToCart.php";
			}
			else {
				$formVar .= '<tr><td colspan="5">' . 'Sorry, missing payment config params' .$productId. '</td></tr>';
				return $formVar;	
			}
						
			$label = "AddToCart";
			
			$product = Dap_Product::loadProduct($productId);
			logToFile("product = ".$product->getName(),LOG_DEBUG_DAP); 

			if(isset($product)) {
				if ($hosted_cmcc == false) {
					logToFile("Self-hosted authnet",LOG_DEBUG_DAP);
					
					$formVar .= '<form name="generate_paypal" method="post" action="'.$buy_url.'">'."\n".
					'<input type="hidden" name="item_name" value="'.$product->getName().'"/>' ."\n".
					'<input type="hidden" name="description" value="'.$product->getDescription().'" />'."\n".
					'<input type="hidden" name="amount" value="'.$product->getPrice().'" />'."\n".
					'<input type="hidden" name="trial_amount" value="'.$product->getTrial_price().'" />'."\n".
					'<input type="hidden" name="total_occurrences" value="'.$product->getTotal_occur().'" />'."\n".
					'<input type="hidden" name="is_recurring" value="'.$product->getIs_recurring().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_1" value="'.$product->getRecurring_cycle_1().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_2" value="'.$product->getRecurring_cycle_2().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_3" value="'.$product->getRecurring_cycle_3().'" />'."\n".
					'<input type="hidden" name="payment_succ_page" value="'.$payment_succ_page.'" />'."\n".
					'<input type="hidden" name="payment_gateway" value="paypal" />'."\n".
					'<input type="hidden" name="is_submitted" value="Y" />'."\n".
					'<input type="submit" value="'.$label.'" />'."\n".
					'</form>';
										
				}
				else {
					logToFile("Hosted cmcc",LOG_DEBUG_DAP);
					
					$formVar .= '<form name="generate_paypal" method="post" action="'.$buy_url.'">'."\n".
					'<input type="hidden" name="item_name" value="'.$product->getName().'"/>' ."\n".
					'<input type="hidden" name="description" value="'.$product->getDescription().'" />'."\n".
					'<input type="hidden" name="amount" value="'.$product->getPrice().'" />'."\n".
					'<input type="hidden" name="trial_amount" value="'.$product->getTrial_price().'" />'."\n".
					'<input type="hidden" name="total_occurrences" value="'.$product->getTotal_occur().'" />'."\n".
					'<input type="hidden" name="is_recurring" value="'.$product->getIs_recurring().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_1" value="'.$product->getRecurring_cycle_1().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_2" value="'.$product->getRecurring_cycle_2().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_3" value="'.$product->getRecurring_cycle_3().'" />'."\n".
					'<input type="hidden" name="cmcc_acctnum" value="'.$cmcc_acctnum.'" />'."\n".
					'<input type="hidden" name="payment_succ_page" value="'.$payment_succ_page.'" />'."\n".
					'<input type="hidden" name="payment_gateway" value="paypal" />'."\n".
					'<input type="hidden" name="is_submitted" value="Y" />'."\n".
					'<input type="submit" value="'.$label.'" />'."\n".
					'</form>';
					
				}
				logToFile("formvar = ".$formVar,LOG_DEBUG_DAP); 
			}
			else {
				$formVar .= '<tr><td colspan="5">'.'Sorry, no product found for the ID'.$productId.'</td></tr>';
			}
		} catch (PDOException $e) {
			$formVar .= '<tr><td colspan="5">' . ERROR_GENERAL . '</td></tr>';
		} catch (Exception $e) {
			$formVar .= '<tr><td colspan="5">' . ERROR_GENERAL . '</td></tr>';
		}
		
		return $formVar;
	}
	
	function generatePaypalStandardHTML($productId) {
		$formVar="";
		try {
			logToFile("I am in functions_admin, product=".$productId,LOG_DEBUG_DAP);

			$gateway_url = Dap_Config::get('gateway_url');
			$cmcc_acctnum = Dap_Config::get('CMCC_ACCTNUM');
			$payment_succ_page = SITE_URL_DAP. "/dap/continue.php?url=/dap/upsell1-paypalstandard-sample.html";
			$payment_succ_page= str_replace ( "http:", "https:", $payment_succ_page );
			
			$payment_err_page = SITE_URL_DAP. "/dap/paymentError.php";
			$payment_err_page= str_replace ( "http:", "https:", $payment_err_page );
			
			$payment_cancel_page = SITE_URL_DAP. "/dap/cancel.php";
			$payment_cancel_page= str_replace ( "http:", "https:", $payment_cancel_page );

			$site_url = SITE_URL_DAP;
			
			$hosted_cmcc=false;  
			if (isset($cmcc_acctnum) && ($cmcc_acctnum != '')) {
				$hosted_cmcc=true;  
				$buy_url = "https://contentresponder.com/cmcc/PaypalSetExpressCheckout.php";
			}
			else if (isset ($gateway_url)) {
				$hosted_cmcc=false;	
			//	$buy_url = SITE_URL_DAP . "/dap/PaypalSetExpressCheckout.php";
			//	$buy_url= str_replace ( "http:", "https:", $buy_url );
				$buy_url =   "/dap/PaypalSetExpressCheckout.php";
			}
			else {
				$formVar .= '<tr><td colspan="5">' . 'Sorry, missing payment config params' .$productId. '</td></tr>';
				return $formVar;	
			}
						
			$label = "Buy Now";
			
			$product = Dap_Product::loadProduct($productId);
			logToFile("product = ".$product->getName(),LOG_DEBUG_DAP); 

			if(isset($product)) {
				if ($hosted_cmcc == false) {
					logToFile("Self-hosted authnet",LOG_DEBUG_DAP);
					
					$formVar .= '<form name="generate_paypal" method="post" action="'.$buy_url.'">'."\n".
					'<input type="hidden" name="item_name" value="'.$product->getName().'"/>' ."\n".
					'<input type="hidden" name="description" value="'.$product->getDescription().'" />'."\n".
					'<input type="hidden" name="amount" value="'.$product->getPrice().'" />'."\n".
					'<input type="hidden" name="trial_amount" value="'.$product->getTrial_price().'" />'."\n".
					'<input type="hidden" name="total_occurrences" value="'.$product->getTotal_occur().'" />'."\n".
					'<input type="hidden" name="is_recurring" value="'.$product->getIs_recurring().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_1" value="'.$product->getRecurring_cycle_1().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_2" value="'.$product->getRecurring_cycle_2().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_3" value="'.$product->getRecurring_cycle_3().'" />'."\n".
					'<input type="hidden" name="payment_succ_page" value="'.$payment_succ_page.'" />'."\n".
					'<input type="hidden" name="payment_err_page" value="'.$payment_err_page.'" />'."\n".
					'<input type="hidden" name="payment_cancel_page" value="'.$payment_cancel_page.'" />'."\n".
					'<input type="hidden" name="payment_gateway" value="paypal" />'."\n".
					'<input type="hidden" name="is_submitted" value="Y" />'."\n".
					'<input type="image" src="/dap/images/btn_xpressCheckout.gif" align="left" width="200" height="50" style="margin-right:7px;" value="Submit" alt="Submit">'."\n".
					'</form>';
					
				}
				else {
					logToFile("Hosted cmcc",LOG_DEBUG_DAP);
					$formVar .= '<form name="generate_paypal" method="post" action="'.$buy_url.'">'."\n".
					'<input type="hidden" name="item_name" value="'.$product->getName().'"/>' ."\n".
					'<input type="hidden" name="description" value="'.$product->getDescription().'" />'."\n".
					'<input type="hidden" name="amount" value="'.$product->getPrice().'" />'."\n".
					'<input type="hidden" name="trial_amount" value="'.$product->getTrial_price().'" />'."\n".
					'<input type="hidden" name="total_occurrences" value="'.$product->getTotal_occur().'" />'."\n".
					'<input type="hidden" name="is_recurring" value="'.$product->getIs_recurring().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_1" value="'.$product->getRecurring_cycle_1().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_2" value="'.$product->getRecurring_cycle_2().'" />'."\n".
					'<input type="hidden" name="recurring_cycle_3" value="'.$product->getRecurring_cycle_3().'" />'."\n".
					'<input type="hidden" name="cmcc_acctnum" value="'.$cmcc_acctnum.'" />'."\n".
					'<input type="hidden" name="payment_succ_page" value="'.$payment_succ_page.'" />'."\n".
					'<input type="hidden" name="payment_gateway" value="paypal" />'."\n".
					'<input type="hidden" name="is_submitted" value="Y" />'."\n".
					'<input type="image" src="/dap/images/btn_xpressCheckout.gif" align="left" width="200" height="50" style="margin-right:7px;" value="Submit" alt="Submit">'."\n".
					'</form>';
					
				}
				logToFile("formvar = ".$formVar,LOG_DEBUG_DAP); 
			}
			else {
				$formVar .= '<tr><td colspan="5">'.'Sorry, no product found for the ID'.$productId.'</td></tr>';
			}
		} catch (PDOException $e) {
			$formVar .= '<tr><td colspan="5">' . ERROR_GENERAL . '</td></tr>';
		} catch (Exception $e) {
			$formVar .= '<tr><td colspan="5">' . ERROR_GENERAL . '</td></tr>';
		}
		
		return $formVar;
	}
	
	function loadLoginHistoryDisplayHTML($userId) {
		$IPList = Dap_User::loadLoginHistory($userId);
		$IPListHTML = '<table id="loginHistoryTable" width="100%"  border="0" cellspacing="2" cellpadding="0">
		<tr>
			<td colspan="2" class="scriptheader">User Login History</td>
		</tr>
		<tr class="bodytextLarge">
			<td><b>Login Date/Time</b></td>
			<td><b>IP Address</b></td>
		</tr>
		';
		$dataFound = false;
		
		foreach ($IPList as $ipRow) {
			$dataFound = true;
			$IPListHTML .= 
			"<tr border='1' bgcolor=\"#EFEFEF\" onClick=\"highlightRow(this,'loginHistoryTable');\">
				<td>".$ipRow["time"]."</td>
				<td>".$ipRow["login_ip"]."</td>
			</tr>";
		}
		
		if($dataFound == false) {
			$IPListHTML .= "<tr><td colspan='2'>Sorry, no login data found for this user</td></tr>"; 
		}
		
		$IPListHTML .= '</table>';
		return $IPListHTML;
	}
	
	function loadProductsDisplayHTML($productFilter, $status) {
		$ProductsList = Dap_Product::loadProducts($productFilter, $status);
		$productListHTML = '<table id="productsTable" width="100%"  border="0" cellspacing="3" cellpadding="0" class="bodytext">';
		
		foreach ($ProductsList as $product) {
			$productListHTML .= 
			"<tr bgcolor=\"#EFEFEF\" onClick=\"highlightRow(this,'productsTable');\">
				<td align=\"left\" width=\"20\"><input name=\"form_selected_products[]\" value=" . $product->getId() . " type=\"checkbox\"></td>
				<td><a href='addEditProducts.php?productId=" . $product->getId() . "'>" . $product->getId() . "</a></td>
				<td>" . $product->getName() . "</td>
				<td class=\"bodytext\"><a href=\"#\" onClick=\"javascript:loadProductUsers(".$product->getId().");\">show users</a></td>
			</tr>";
		}
		
		$productListHTML .= '</table>';
		return $productListHTML;
	}
	
	function loadProductsUnderWhichContentIsProtectedHTML($aipurl) {
		
		logToFile("functions_admin.php:loadProductsUnderWhichContentIsProtectedHTML(): aipurl = ".$aipurl,LOG_DEBUG_DAP); 
		
		$url_parts = parse_url($aipurl);
    	$aipurl = $url_parts['path'];
		$aipurl = rtrim($aipurl, '/');
		logToFile("functions_admin.php:loadProductsUnderWhichContentIsProtectedHTML(): without domain = ".$aipurl,LOG_DEBUG_DAP); 
		
		$ProductsList =Dap_FileResource::loadProductsUnderWhichContentIsProtected($aipurl);
		$productListHTML = '<table id="productsTable" width="100%"  border="1" cellspacing="3" cellpadding="2" class="bodytextLarge">';
		$found=false;
		$productListHTML .= "<tr class='bodytextLarge' bgcolor=\"#8FD8D8\" onClick=\"highlightRow(this,'productsTable');\">
				<td>Product ID</td>
				<td>Product Name</td>
				<td>Drip Start Day</td>
				<td>Drip End Day</td>
				<td>Drip Start Date</td>
				<td>Drip End Date</td>
			</tr>";
			
		foreach ($ProductsList as $product) {
			$productListHTML .= 
			"<tr class='bodytextLarge' bgcolor=\"#EFEFEF\" onClick=\"highlightRow(this,'productsTable');\">
				<td><a href='addEditProducts.php?productId=" . $product["pid"] . "'>" . $product["pid"] . "</a></td>
				<td>" . $product["pname"] . "</td>
				<td>" . $product["start_day"] . "</td>
				<td>" . $product["end_day"] . "</td>
				<td>" . $product["start_date"] . "</td>
				<td>" . $product["end_date"] . "</td>
			</tr>";
			$found=true;
		}
		if($found==false) {
			$productListHTML = 
			"<table id='productsTable' width='100%'  border='1' cellspacing='3' cellpadding='2' class='bodytextLarge'><tr bgcolor=\"#EFEFEF\" onClick=\"highlightRow(this,'productsTable');\">
				<td><h3>&nbsp;This URL is Not Protected Under Any of Your Products!<h3></td>
			</tr></table>";	
		}
		else {
			$productListHTML .= '</table>';
		}
		
		logToFile("loadProductsUnderWhichContentIsProtectedHTML(): productListHTML = ".$productListHTML,LOG_DEBUG_DAP); 
		return $productListHTML;
	}


	function loadUserProductsDisplayHTML($userId) {
		$UserProductsList = loadProductsByUser($userId);
		$userProductsListHTML = '<table id="productsTable" width="95%"  border="0" cellspacing="5" cellpadding="0" class="bodytextArial">';
		$userProductsListHTML .= '<tr><td colspan="4" class="scriptheader">Filtered: Showing Products for User</td></tr>';
		$rowsFound = false;
		
		foreach ($UserProductsList as $userProduct) {
			$rowsFound = true;
			$userProductsListHTML .=   
			"<tr bgcolor=\"#EFEFEF\" onClick=\"highlightRow(this,'productsTable');\">
				<td width=\"20\"><input name=\"form_selected_products[]\" value=" . $userProduct->getProduct_id() . " type=\"checkbox\"></td>
				<td><a href='addEditProducts.php?productId=" . $userProduct->getProduct_id() . "'>" . $userProduct->getProduct_id() . "</a></td>
				<td>" . $userProduct->getProduct_name() . "</td>
				<td class=\"bodytext\"><a href=\"#\" onClick=\"javascript:loadProductUsers(".$userProduct->getProduct_id().");\">show users</a></td>
			</tr>";
		}
		
		if($rowsFound == false) {
			$userProductsListHTML .= '<tr><td colspan="4">Sorry, no Products found for this User.</td></tr>';
		}
		$userProductsListHTML .= '</table>';
		return $userProductsListHTML;
	}
	

	function loadUsersDisplayHTML($userFilter, $userStatus, $productId, $productStatus, $userSearchType, $start) {
		try {
			$UsersList = Dap_User::loadUsers($userFilter, $userStatus, $productId, $productStatus, $userSearchType, $start);
			$userListHTML = formatUserList($UsersList); 
			//logToFile($userListHTML); 
			return $userListHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}
	
		
	function loadCustomFieldsHTML() {
		try {
			//logToFile("functions_admin: loadCustomFieldsHTML");
			$customFieldList = Dap_CustomFields::loadCustomFields();
			$customFieldHTML = formatCustomFieldsList($customFieldList); 
			return $customFieldHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}
	
	function loadProductChainingDisplayHTML () {
		try {
			$productChainingList = Dap_ProductChaining::loadChainedProducts();
			$productChainHTML = formatProductChainingList($productChainingList); 
			return $productChainHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}
	
	function loadCSUserResourcesDisplayHTML ($userId) {
		try {
			logToFile("function_admin: loadCSUserResourcesDisplayHTML(): userId".$userId,LOG_DEBUG_DAP);
			$CSUserResources = Dap_UserCredits::loadUserCreditResources($userId);
			$CSUserResourcesHTML = formatCSUserResourcesList($CSUserResources,$userId); 
			return $CSUserResourcesHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}
	
	function loadUserDAPCreditsDisplayHTML ($userId) {
		try {
			$CreditsUsed = Dap_Credits::loadCreditsUsedByUserId($userId);
			logToFile("function_admin: loadCreditsUsedByUserId() called: userId".$userId,LOG_DEBUG_DAP);
			$CreditsUsedHTML = formatUserDAPCreditsList($CreditsUsed,$userId); 
			logToFile("function_admin: formatUserDAPCreditsList() called: userId".$userId,LOG_DEBUG_DAP);
			return $CreditsUsedHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}
	
	function loadSupportTicketIntegrationRulesDisplayHTML () {
		try {
			$supportTicketList = Dap_SupportTicket::loadSupportTicketProducts();
			$supportTicketHTML = formatSupportTicketList($supportTicketList); 
			return $supportTicketHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}
	
	function loadProductCouponAssociationHTML () {
		try {
			$productCouponList = Dap_ProductCoupon::loadProductCoupon();
			$productCouponHTML = formatProductCouponList($productCouponList); 
			return $productCouponHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}
	
	function loadProductCategoryAssociationHTML () {
		try {
			$productCategoryList = Dap_ProductCategory::loadProductCategory();
			
			$productCategoryHTML = formatProductCategoryList($productCategoryList); 
			return $productCategoryHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}
	
	function loadMasterChildAssociationHTML () {
		try {
			$masterChildList = Dap_MasterChildSSS::loadMasterChild();
			$masterChildHTML = formatMasterChildList($masterChildList); 
			return $masterChildHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}
	
	function loadProductForumMappingDisplayHTML () {
		try {
			$productForumList = Dap_VBForum::loadProductForumMappingRule();
			//logToFile("in load product forum display",LOG_DEBUG_DAP);
			$productForumHTML = formatProductForumList($productForumList); 
		//	logToFile("after format: " . $productForumHTML ,LOG_DEBUG_DAP);

			return $productForumHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}

	function loadPriorityDisplayHTML () {
		try {
		//	logToFile("Call Dap_Priority::loadPriority",LOG_DEBUG_DAP);
			$forumPriorityList = Dap_Priority::loadPriority();
		//	logToFile("in load forum priority display",LOG_DEBUG_DAP);
			$forumPriorityHTML = formatForumPriorityList($forumPriorityList); 
		//	logToFile("after format: " . $productForumHTML ,LOG_DEBUG_DAP);

			return $forumPriorityHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}
	
	function loadUsersByIdsDisplayHTML($userIdsArray, $productId) {
		try {
			$inClause = "(";
			foreach($userIdsArray as $userId) {
				$inClause .= $userId . ",";
			}
			$inClause = substr($inClause, 0, -1);
			$inClause .= ")";
			
			//logToFile($inClause,LOG_DEBUG_DAP);
			
			$UsersList = Dap_UsersProducts::loadUsersByIds($userIdsArray, $productId, $inClause);
			$userListHTML = formatUserList($UsersList);
			logToFile($userListHTML,LOG_DEBUG_DAP);
			return $userListHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}
	}
	
	function formatProductChainingList($productChainingList) {
		$productChainHTML = "";
		$productChainHTML .= '<table id="productChainTable" width="100%"  border="0" cellspacing="3" cellpadding="3" class="bodytextArial">
						<tr class="bodytextArial">
						  <td><b>Source Operation</b></td>
						  <td><b>Source Product</b></td>
						  <td><b>Target Operation</b></td>
						  <td><b>Target Product</b></td>
						  <td><b>Access Type</b></td>
						  <td><b>Access Start</b></td>
						 </tr>';
		$rulesFound = false;
		
		foreach ($productChainingList as $productChainingArray) {
			$rulesFound = true;
			
			$source_product_id = $productChainingArray["source_product_id"];
			$target_product_id = $productChainingArray["target_product_id"];
			$source_product = DAP_Product::loadProduct($source_product_id); 
			$target_product = DAP_Product::loadProduct($target_product_id); 
			
			if ($productChainingArray["source_operation"] == "AT")
				$source_oper = "Added To";
			if ($productChainingArray["source_operation"] == "RF")
				$source_oper = "Removed From";
			if ($productChainingArray["target_operation"] == "A")
				$target_oper = "Add To";
			if ($productChainingArray["target_operation"] == "R")
				$target_oper = "Remove From";
				
			if ($productChainingArray["transaction_id"] == "-3")
				$transactionId = "Paid User";
			else
				$transactionId="Free User";
			
			$add_days = $productChainingArray["add_days"];
			logToFile("functions admin, add_days=" . $productChainingArray["add_days"],LOG_INFO_DAP);
			
			$showDelete = '<a href="#" onclick="javascript:removeRule('.$productChainingArray["id"].'); return false;"><img src="/dap/images/ximage.jpg" width="13" border="0" height="13"></a>';

			$productChainHTML .= "<tr align=\"left\" valign=\"top\" bgcolor=\"#EFEFEF\" onmouseover=\"this.className='highlightedRow'\" onmouseout=\"this.className='nonHighlightedRow'\">";
			$productChainHTML .= "<td align=\"left\" valign=\"top\">" . "If " . $source_oper . "</td>";
			$productChainHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\"><a href=\"addEditProducts.php?productId=".$productChainingArray["source_product_id"]."\">".$source_product->getName()."</a></td>";
			$productChainHTML .= "<td align=\"left\" valign=\"top\">" . " then " . $target_oper . "</td>";
			$productChainHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\"><a href=\"addEditProducts.php?productId=".$productChainingArray["target_product_id"]."\">".$target_product->getName()."</a></td>";
			$productChainHTML .= "<td align=\"left\" valign=\"top\" > <b>" . $transactionId . "</b></td>";
			$productChainHTML .= "<td align=\"left\" valign=\"top\" > <b>" . $add_days . "</b></td>";
			$productChainHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\">" . $showDelete . "</td></tr>";
			$productChainHTML .= "</tr>";
			
		}
		
		$productChainHTML .= '</table><br/>';
		
		if($rulesFound == false) { //No data found
			$productChainHTML .= "Sorry, no Product Chaining rules found.";
		} 
		
		return $productChainHTML;
	}
	
	function formatUserList($UsersList) {
		$start = Dap_Session::get('start');
		$userListHTML = "";
		$howManyUsers = Dap_Config::get("SEARCH_USERS_COUNT");
		$startButton = '<input type="button" name="Previous" value="Previous" onClick="loadUsers(document.ManageUsersProductsForm,' . ($start - ($howManyUsers*2)) . '); return false;">';
		$startButtonD = '<input type="button" name="Previous" value="Previous" disabled onClick="loadUsers(document.ManageUsersProductsForm,' . ($start - ($howManyUsers*2)) . '); return false;">';
		$endButton = '<input type="button" name="Next" value="Next" onClick="loadUsers(document.ManageUsersProductsForm,' . $start . '); return false;">';
		$endButtonD = '<input type="button" name="Next" value="Next" disabled onClick="loadUsers(document.ManageUsersProductsForm,' . $start . '); return false;">';
		$buttonsHTML = "";
		
		$userListHTML .= '<table id="usersTable" width="100%"  border="0" cellspacing="3" cellpadding="3" class="bodytextArial">
						 <tr class="bodytext">
						  <td width=\"20\"><input type="checkbox" name="selectAllUsersCheckbox" onClick="javascript:selectAllUsers(this.form);"></td>
						  <td><b>User Id</b></td>
						  <td><b>Name</b></td>
						  <td><b>Email</b></td>
						  <td><b>User<br/>Status</b></td>
						  <td><b>Product<br/>Status</b></td>
						  <td><b>Opted-out</b></td>
						  <td><b>Product Name</b></td>
						  <td><b>Resend<br/>Email</b></td>
						  <td><b>Last Login<br/>Date</b></td>
						  <td><b>Login<br/>Count</b></td>
						  <td><b>Access Start<br/>Date</b></td>
						  <td><b>Access End<br/>Date</b></td>
						  <td><b>Order Id</b></td>
						  <td nowrap><b>Product Access</b></td>
						  <td><b>Aff Id</b></td>
						  <td><b>Coupon<br/>Id</b></td>
						 </tr>';
		$usersFound = false;
		
		foreach ($UsersList as $userArray) {
			
			if ($userArray["product_name"] != "") $productNameShort = trimString($userArray["product_name"],46,30,10);
			if ($userArray["last_login_date"] == "0000-00-00") $userArray["last_login_date"] = "";
						
			$userArray["first_name"] = isset($userArray["first_name"]) && ($userArray["first_name"]!="") ? stripslashes($userArray["first_name"]) : $userArray["first_name"];
			$userArray["last_name"] = isset($userArray["last_name"]) && ($userArray["last_name"]!="") ? stripslashes($userArray["last_name"]) : $userArray["last_name"];
			$userArray["product_name"] = isset($userArray["product_name"]) && ($userArray["product_name"]!="") ? stripslashes($userArray["product_name"]) : $userArray["product_name"];

			//logToFile("User coupon: " . $userArray["coupon_id"]); 
			//logToFile("User first_name: " . $userArray["first_name"]); 
			//logToFile("User email: " . $userArray["email"]); 
			//logToFile("User status: " . $userArray["status"]); 
			//logToFile("User upj_status: " . $userArray["upj_status"]); 
			//logToFile("User opted_out: " . $userArray["opted_out"]); 
			//logToFile("-------------------------------------------"); 
			
			//logToFile("first_name: " . $userArray["first_name"],LOG_DEBUG_DAP);
			//logToFile("last_name: " . mb_convert_encoding($userArray["first_name"], "UTF-8", "ISO-8859-1"),LOG_DEBUG_DAP);
			//logToFile("user_name: " . $userArray["user_name"],LOG_DEBUG_DAP);
			//logToFile("email: " . $userArray["email"],LOG_DEBUG_DAP);
			
			//$userArray["first_name"] = iconv("UTF-8", "ISO-8859-1", $userArray["first_name"]);
			//$userArray["last_name"] = iconv("UTF-8", "ISO-8859-1", $userArray["last_name"]);
			
			$userArray["first_name"] = mb_convert_encoding($userArray["first_name"], "UTF-8", "auto");
			$userArray["last_name"] = mb_convert_encoding($userArray["last_name"], "UTF-8", "auto");
			$userArray["email"] = mb_convert_encoding($userArray["email"], "UTF-8", "auto");
			
			$usersFound = true;
			$affHTML = "";
			$affExistsColor = "";
			if($userArray["affiliate_id"] != ""){
				$affExistsColor = "#E4CAFF";
			}
			$warningColor = "#FFDFB0";
			
			if ( isset($userArray["affiliate_id"]) && (trim($userArray["affiliate_id"]) != "") ) {
				$affHTML = "<td class=\"bodytext\" align=\"right\" bgcolor=\"$affExistsColor\"><a href=\"#\" onClick=\"javascript:affiliatePopup(".$userArray["id"].",".$userArray["affiliate_id"]."); return false;\" style='text-decoration:none' title='Click to give credit to an Affiliate'>".$userArray["affiliate_id"]."</a></td>";
			} else if ( !isset($userArray["affiliate_id"]) || (trim($userArray["affiliate_id"]) == "") ) {
				$affHTML = "<td class=\"bodytext\" align=\"right\"><a href=\"#\" onClick=\"javascript:affiliatePopup(".$userArray["id"]."); return false;\" style='text-decoration:none' title='Click to give credit to an Affiliate'>+</a></td>";
			}	

			$userListHTML .=   
			 "<tr bgcolor=\"#EFEFEF\" onmouseover=\"this.className='highlightedRow'\" onmouseout=\"this.className='nonHighlightedRow'\">
				<td class=\"bodytext\" width=\"20\"><input name=\"selected_users[]\" id=\"selected_users[]\" value=" . $userArray["id"] . " type=\"checkbox\"></td>";
				
			$userListHTML .=   
				"<td class=\"bodytext\" align=\"center\" bgcolor=\"$affExistsColor\"><a href='/dap/admin/addEditUsers.php?userId=".$userArray["id"]."'>".$userArray["id"]."</a></td>";

			$isAdminColor = "";
			if($userArray["account_type"] == "A"){
				$isAdminColor = "#DCE1EF";
			}
			$userListHTML .=  
				"<td  class=\"bodytext\" bgcolor=\"$isAdminColor\"><a href='/dap/admin/addEditUsers.php?userId=".$userArray["id"]."'>". $userArray["first_name"]." ".$userArray["last_name"]."</a></td>" . 
			 	"<td class=\"bodytext\">" . $userArray["email"] . "</td>"
				;

			$inactiveColor = "";
			if(  ($userArray["status"] == "I") || ($userArray["status"] == "U") || ($userArray["status"] == "P") || ($userArray["status"] == "L") ){
				$inactiveColor = $warningColor;
			}
			$title = "";
			if($userArray["status"] == "A") { $title = "Change status to 'Inactive'"; }
			if($userArray["status"] == "I") { $title = "Change status to 'Active'"; }
			if($userArray["status"] == "U") { $title = "Change status to 'Active'"; }
			if($userArray["status"] == "P") { $title = "Change status to 'Active'"; }
			if($userArray["status"] == "L") { $title = "Unlock User & Send 'Unlocked' notification email"; }
			
			if(  ($userArray["status"] == "A") || ($userArray["status"] == "I") || ($userArray["status"] == "U") || ($userArray["status"] == "P") ){
				$userListHTML .= "<td  class=\"bodytext\" align=\"center\" bgcolor=\"$inactiveColor\"><a href=\"#\" onClick=\"toggleUserStatus(".$userArray["id"].",'".$userArray["status"]."');\" title=\"$title\">".$userArray["status"]."</a></td>";
			} else if($userArray["status"] == "L") {
				$userListHTML .= "<td  class=\"bodytext\" align=\"center\" bgcolor=\"$inactiveColor\"><a href=\"#\" onClick=\"unlockUser(".$userArray["id"].");\" title=\"$title\">".$userArray["status"]."</a></td>";
			}
				
			
			$inactiveColor = "";
			$title = "Change status to 'Inactive'";
			if($userArray["upj_status"] == "I"){
				$inactiveColor = $warningColor;
				$title = "Change status to 'Active'";
			}	
			$userListHTML .= "<td  class=\"bodytext\" align=\"center\" bgcolor=\"$inactiveColor\"><a href=\"#\" onClick=\"toggleProductStatus(".$userArray["id"].",".$userArray["product_id"].",'".$userArray["upj_status"]."');\" title=\"$title\">".$userArray["upj_status"]."</a></td>";
			
			
			$inactiveColor = "";
			if($userArray["opted_out"] == "Y"){
				$inactiveColor = $warningColor;
			}	
			
			//$userListHTML .= "<td align=\"center\" bgcolor=\"$inactiveColor\">" . $userArray["opted_out"] . "</td>";
			
			if($userArray["opted_out"] == "Y") {
				$title = "Currently not receiving Autoresponder or Broadcast emails. Click to change 'Opted Out' to 'N' (will then start receiving emails)";
				$userListHTML .= "<td class=\"bodytext\" align=\"center\" bgcolor=\"$inactiveColor\"><a href=\"#\" onClick=\"toggleOptinStatus(".$userArray["id"].",'".$userArray["opted_out"]."');\" title=\"$title\">".$userArray["opted_out"]."</a></td>";
			} else {
				$title = "Currently receiving Autoresponder and Broadcast emails. Click to change 'Opted Out' to 'Y' (will then NO LONGER receive emails)";
				$userListHTML .= "<td class=\"bodytext\" align=\"center\" bgcolor=\"$inactiveColor\"><a href=\"#\" onClick=\"toggleOptinStatus(".$userArray["id"].",'".$userArray["opted_out"]."');\" title=\"$title\">".$userArray["opted_out"]."</a></td>";
			}


			if($userArray["product_id"] == "None") {
					$userListHTML .= "<td class=\"bodytext\">None</td>
									  <td class=\"bodytext\">&nbsp;</td>
									  <td class=\"bodytext\" align=\"center\">".$userArray["last_login_date"]."</td>
									  <td class=\"bodytext\" align=\"center\">".$userArray["login_count"]."</td>
									  <td class=\"bodytext\">&nbsp;</td>
									  <td class=\"bodytext\">&nbsp;</td>
									  <td class=\"bodytext\">&nbsp;</td>
									  $affHTML
									  "
									  ;
																  
				} else {
					$transaction_id = $userArray["transaction_id"];
					$transIdHTML = "<td class=\"bodytext\"><a href='/dap/admin/manageTransactions.php?transactionId=$transaction_id'>" . $transaction_id . "</a></td>";					
					$transactionArray = array(  
											"-1" => "FREE", //(Signup)
											"-2" => "FREE", //(Admin)
											"-3" => "PAID"
										);
										
					//if transaction_id is in the transactionArray, then do the conversion - if not, keep it as it is
					if(array_key_exists($transaction_id, $transactionArray)) {
						$transaction_id = $transactionArray[$transaction_id];
						$transIdHTML = "<td class=\"bodytext\">" . $transaction_id . "</td>";					
					}
					
					$product = Dap_Product::loadProduct($userArray["product_id"]);
					$addSSS = "";
					
					if(($product->getSelf_service_allowed() == 'Y') || ($product->getIs_master() == 'Y')) {
						//$usercredit = Dap_UserCredits::loadCreditsPerProduct($userArray["id"], $userArray["product_id"]);
						//$user = Dap_User::loadCreditsPerProduct($userArray["id"], $userArray["product_id"]);
									
						$creditsEarned = $userArray['credits_earned'];
						if($creditsEarned == "")$creditsEarned=0;
						
						$creditsSpent = $userArray['credits_available'];
						if($creditsSpent == "")$creditsSpent=0;
						
						//logToFile("functions_admin.php, creditsEarned: " . $creditsEarned, LOG_DEBUG_DAP);
						
						//logToFile("functions_admin.php, creditsSpent: " . $creditsSpent, LOG_DEBUG_DAP);
						//if ($product->getIs_master() == 'Y') {
							$addSSS =" | <a href=\"#\" onClick=\"javascript:addCreditForUserProductSetup(".$userArray["id"].",".$userArray["product_id"].",'Y'," . $creditsEarned . "," . $creditsSpent."); hideDiv('set_credit_spent_div'); return false;\">Credits</a>";
						//}
						//else {
							//$addSSS =" | <a href=\"#\" onClick=\"javascript:addCreditForUserProductSetup(".$userArray["id"].",".$userArray["product_id"].",'N'," . $creditsEarned . ",".$creditsSpent."); hideDiv('set_credit_div'); return false;\">Credits</a>";
						//}
						
					}
					
					$inactiveColor = "";
					$pos = strpos($userArray["access_end_date"], "9999");
					if( ($pos === false) && (strtotime(date($userArray["access_end_date"])) < strtotime(date("Y-m-d"))) ){
						$inactiveColor = $warningColor;
					}	
					
					$userListHTML .=  
						"<td class=\"bodytext\"><a href=\"addEditProducts.php?productId=".$userArray["product_id"]."\" title=\"".$userArray["product_name"]."\">".$productNameShort."</a></td>
						
						
						<td class=\"bodytext\" align=\"center\" nowrap><a href=\"#\" onClick=\"resendEmail(".$userArray["id"].",".$userArray["product_id"].",'DO');\" title=\"Resend Double-Optin Email\">D.O</a> | <a href=\"#\" onClick=\"resendEmail(".$userArray["id"].",".$userArray["product_id"].",'WE');\" title=\"Resend Welcome Email\">W.E</a></td>
						
						
						<td class=\"bodytext\" align=\"center\">".$userArray["last_login_date"]."</td>
						<td class=\"bodytext\" align=\"center\">".$userArray["login_count"]."</td>
						<td class=\"bodytext\" align=\"center\">" . $userArray["access_start_date"]. "</td>
						<td class=\"bodytext\" align=\"center\" bgColor='$inactiveColor'>" . $userArray["access_end_date"]. "</td>
						$transIdHTML
						<td class=\"bodytext\">
							<a href=\"#\" onClick=\"javascript:loadUserProductRel(".$userArray["id"].",".$userArray["product_id"]."); return false;\" title='Click to modify Access Start & End Dates'>Modify</a> | 
							<a href=\"#\" onClick=\"javascript:markSelectedUsersAsPaidDirect(".$userArray["id"].",".$userArray["product_id"].",'-3'); return false;\" title='Mark as a PAID user - useful when you want to categorize user (for your own reporting purposes) that user is just like a paid user even though there is no order for this user in the system'>Paid</a> | 
							<a href=\"#\" onClick=\"javascript:markSelectedUsersAsFreeDirect(".$userArray["id"].",".$userArray["product_id"].",'-1'); return false;\" title='Mark as a FREE user - this is for your own reporting purposes - usually a free user is someone who signed up through a free signup form, or someone to whom you have given free access but wish to categorize as a free user for whatever reason'>Free</a> | 
							<a href=\"#\" onClick=\"javascript:removeSelectedUsersFromProductDirect(".$userArray["id"].",".$userArray["product_id"]."); return false;\" title=\"Completely remove User's access to this Product\">Remove</a> | 
							<a href=\"#\" onClick=\"javascript:addTransactionForUserProductSetup(".$userArray["id"].",".$userArray["product_id"]."); return false;\" title='Click to Add a Manual/Offline Transaction to the sytem'>Add Trans</a> ";
					if ($addSSS != "") {		
							$userListHTML .= $addSSS;
					}
					$userListHTML .= "<span id=\"rowdiv".$userArray["id"]."\"></span>
						</td>
						$affHTML 
						";
				}
				
				$userProduct = Dap_UsersProducts::load($userArray["id"], $userArray["product_id"]);
				
				$coupon_id = "";
				if($userProduct != NULL) {
					$coupon_id = $userProduct->getCoupon_id();
				}
				//else {
					//logToFile("user product association not found"); 	
				//}
				
				$userListHTML .=  
				"<td><a href='/dap/admin/addEditCoupons.php?couponId=".$coupon_id."'>". $coupon_id . "</a></td>";
		
				$userListHTML .= "</tr>";
		}
		
		$userListHTML .= '</table><br/>';
		
		if($usersFound == false) { //No data found
			if($start > $howManyUsers) {
				$userListHTML = $startButton . "&nbsp;" . $endButtonD;
			} else {
				$userListHTML = $startButtonD . "&nbsp;" . $endButton;
			}
			$userListHTML .= "<br/><br/>Sorry, no matching users found.";
		} else { //Data found
			if($start > $howManyUsers ) { //If any page other than 1st page, only then show "Previous" button
				$buttonsHTML .= $startButton . "&nbsp;";
			} else {
				$buttonsHTML .= $startButtonD . "&nbsp;";
			}
			
			if ( sizeof($UsersList) >= Dap_Config::get("SEARCH_USERS_COUNT") ) {
				$buttonsHTML .= $endButton;
			} else {
				$buttonsHTML .= $endButtonD;
			
			}
			
			$userListHTML = $buttonsHTML . $userListHTML . $buttonsHTML;

		}

		return $userListHTML;
	}

	function formatCustomFieldsList($customFieldsList) {
		$customFieldsHTML = "";
		if (!isset($customFieldsList)) {
			$customFieldsHTML .= "Sorry, no custom fields found.";
			return $customFieldsHTML;
		}
			
		$customFieldsHTML .= '<table id="customFieldTable" width="100%"  border="0" cellspacing="3" cellpadding="3" class="bodytextArial">
						<tr class="bodytextArial">
						  <td><b>ID</b></td>
						  <td><b>Name</b></td>
						  <td><b>Label</b></td>
						  <td><b>Description</b></td>
						  <td align="center"><b>Show Only<br/>To Admin?</b></td>
						  <td align="center"><b>Required?</b></td>
				   	</tr>';
						
		$rulesFound = false;
				
		foreach ($customFieldsList as $customField) {
			$rulesFound = true;
			$keyId = $customField['id'];
			$name = $customField['name'];
			$label = $customField['label'];
			$allow_delete = $customField['allow_delete'];
			$description = $customField['description'];
			$showonlytoadmin = $customField['showonlytoadmin'];
			$required = $customField['required'];
			
	//		logToFile("functions admin, name=" . $name,LOG_INFO_DAP);
			
			$showDelete = "";
			if ($allow_delete == "Y") {
				$showDelete = '<a href="#" onclick="javascript:removeCustomField('.$keyId.'); return false;"><img src="/dap/images/ximage.jpg" width="13" border="0" height="13"></a>';
			}
			
			$customFieldsHTML .= "<tr align=\"left\" valign=\"top\" bgcolor=\"#EFEFEF\" onmouseover=\"this.className='highlightedRow'\" onmouseout=\"this.className='nonHighlightedRow'\">";
			
			$customFieldsHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\"><a href=\"createCustomFields.php?keyId=".$keyId."\">".$keyId."</a></td>";
			
			$customFieldsHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\">".$name."</td>";
			
			$customFieldsHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\">" . $label . "</td>";
						
			$customFieldsHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\"> " . $description . "</td>";
		
			$customFieldsHTML .= "<td align=\"center\" valign=\"top\" class=\"bodytext\"> " . $showonlytoadmin . "</td>";
			
			$customFieldsHTML .= "<td align=\"center\" valign=\"top\" class=\"bodytext\"> " . $required . "</td>";
			
			$customFieldsHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\">" . $showDelete . "</td></tr>";
		
			$customFieldsHTML .= "</tr>";
			
			
		}
		
		
		if($rulesFound == false) { //No data found
			//logToFile("functions admin no custom fields found",LOG_INFO_DAP);
			$customFieldsHTML .= "<tr><td>";
			
			$customFieldsHTML .= "Sorry, no custom fields found.";
			
			$customFieldsHTML .= "</td></tr>";
			
		} 
		$customFieldsHTML .= '</table><br/>';
		
		//logToFile("functions admin:  custom fields table=" . $customFieldsHTML, LOG_INFO_DAP);
		return $customFieldsHTML;
	}
	
	function formatUserDAPCreditsList($CreditsUsed,$userId) {
		$CreditsUsedHTML = "";
		$rulesFound=false;
		if(!$userId) {
			$CreditsUsedHTML .= '<form name="DAPCreditsListForm" method="" action="" onSubmit="doSubmit(this); return false;">';
			$CreditsUsedHTML .= "Sorry, User not found";
			logToFile("functions_admin.php:  formatUserDAPCreditsList: CreditsUsedHTML=" . $CreditsUsedHTML,LOG_DEBUG_DAP);
			$CreditsUsedHTML .= '</form><br/>';
			return $CreditsUsedHTML;
		}
		$user = Dap_User::loadUserById($userId); 
	    $CreditsUsedHTML .= '<form name="DAPCreditsListForm" method="" action="" onSubmit="doSubmit(this); return false;">';
		$CreditsUsedHTML .= '<table id="DAPCreditsTotal" width="100%"  border="0" cellspacing="3" cellpadding="3" class="bodytextArial">
						<tr class="bodytextArial">
						 <td><b>User currently has a total of ' . $user->getCredits_available() . ' Credits Available.</b></td>
						 </tr>
						 </table>';
		$CreditsUsedHTML .= '<table id="UserDAPCreditsTable" width="100%"  border="0" cellspacing="3" cellpadding="3" class="bodytextArial">
						<tr class="bodytextArial">
						  <td><b>UserId</b></td>
						  <td><b>Page/Post Id</b></td>
						  <td><b>Page/Post Title</b></td>
						  <td><b>Type</b></td>
						  <td><b>Credits Earned</b></td>
						  <td><b>Total number of times user shared/liked your post/page to a social media source</b></td>
						  <td><b>Update</b></td>
						  <td><b>Remove</b></td>
						 </tr>';
		if($CreditsUsed) {
		  foreach ($CreditsUsed as $CreditUsed) {
			  
			  if(!$CreditUsed) break;
			  logToFile("functions_admin.php: ENTER formatUserDAPCreditsList: type=". $type ,LOG_DEBUG_DAP);
			  $userid=$CreditUsed->getUserid();
			  $type=$CreditUsed->getType();
			  $used=$CreditUsed->getUsed();
			  $credits=$CreditUsed->getCredits();
			  $postid=$CreditUsed->getPostid();
 			  $title=$CreditUsed->getTitle();
			  
			  $rulesFound=true;
			  //logToFile("functions_admin.php: ENTER formatCSUserResourcesList: postid=". $postid ,LOG_DEBUG_DAP);
			  //logToFile("functions_admin.php: ENTER formatCSUserResourcesList: title=". $title ,LOG_DEBUG_DAP);
			  //logToFile("functions_admin.php: ENTER formatCSUserResourcesList: credits=". $credits ,LOG_DEBUG_DAP);
			  //logToFile("functions_admin.php: ENTER formatCSUserResourcesList: used=". $used ,LOG_DEBUG_DAP);
			  $showDelete = '<a href="#" onclick="javascript:removeRecord('.$userid.','.$postid.',\''.$type.'\'); return false;"><img src="/dap/images/ximage.jpg" width="13" border="0" height="13"></a>';
						  
			  $username=
			  $showUpdate = '<a href="#" onclick="javascript:updateRecord('.$userid.','.$postid.','.$credits.',document.DAPCreditsListForm.used' . $type . $postid . '.value'.',document.DAPCreditsListForm.credits' . $type . $postid . '.value,\''.$type.'\'); return false;">Update</a>';
			  
			  //logToFile("functions_admin.php:  showDelete" . $showDelete,LOG_DEBUG_DAP);
			  
			  $CreditsUsedHTML .=   
			   "<tr bgcolor=\"#EFEFEF\" onmouseover=\"this.className='highlightedRow'\" onmouseout=\"this.className='nonHighlightedRow'\">";
				  
	  //		$CreditsUsed .= "<tr align=\"left\" valign=\"top\" bgcolor=\"#EFEFEF\" onmouseover=\"this.className='highlightedRow'\" onmouseout=\"this.className='nonHighlightedRow'\">";
			  
			  $CreditsUsedHTML .= "<td align=\"left\" valign=\"top\" >" . $userid . "</td>";
			  $CreditsUsedHTML .= "<td align=\"left\" valign=\"top\" > " . $postid . "</td>";
			  $CreditsUsedHTML .= "<td align=\"left\" valign=\"top\" > " . $title . "</td>";
			  $CreditsUsedHTML .= "<td align=\"left\" valign=\"top\" >" . $type . "</td>";
			  $CreditsUsedHTML .= "<td align=\"left\" valign=\"top\" >" . '<input name="credits' . $type . $postid . '" size="10" maxlength="50" value="' . $credits . '" type="text">' . "</td>";
			  $CreditsUsedHTML .= "<td align=\"left\" valign=\"top\" >" . '<input name="used' . $type . $postid . '" size="10" maxlength="50" value="' . $used . '" type="text">' . "</td>";
			  
			  $CreditsUsedHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\">" . $showUpdate . "</td>";
			  $CreditsUsedHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\">" . $showDelete . "</td>";
			  $CreditsUsedHTML .= "</tr>";
			  
		  //	logToFile("functions_admin.php:  formatCSUserResourcesList: CreditsUsed" . $CreditsUsed,LOG_DEBUG_DAP);
			  
		  }
		}
		$CreditsUsedHTML .= '</table></form><br/>';
		
		if($rulesFound == false) { //No data found
			$CreditsUsedHTML .= "Sorry, User has not earned any DAP Social Credits yet";
		} 
		logToFile("functions_admin.php:  formatUserDAPCreditsList: CreditsUsedHTML=" . $CreditsUsedHTML,LOG_DEBUG_DAP);
		return $CreditsUsedHTML;
	}
	
	function formatCSUserResourcesList($CSUserResources,$userId) {
		$CSUserResourcesHTML = "";
		$rulesFound=false;
	    $_SESSION['CSSelectProductId']="";
		
		if(!$userId) {
			$CSUserResourcesHTML .= '<form name="CSUserResourcesForm" method="" action="" onSubmit="doSubmit(this); return false;">';
			$CSUserResourcesHTML .= "Sorry, User not found";
			logToFile("functions_admin.php:  formatCSUserResourcesList: CSUserResourcesHTML=" . $CSUserResourcesHTML,LOG_DEBUG_DAP);
			$CSUserResourcesHTML .= '</form><br/>';
			return $CSUserResourcesHTML;
		}
		$user = Dap_User::loadUserById($userId); 
		
		$CSUserResourcesHTML .= '<table id="CSUserResourcesTable" width="100%"  border="0" cellspacing="3" cellpadding="3" class="bodytextArial">
						<tr class="bodytextArial">
						 <td><b>User currently has a total of ' . $user->getCredits_available() . ' Credits Available.</b></td>
						 </tr>
						 </table>';
						 
		$CSUserResourcesHTML .= '<table id="CSUserResourcesTable" width="100%"  border="0" cellspacing="3" cellpadding="3" class="bodytextArial">
						<tr class="bodytextArial">
						  <td width=\"25\"><input type="checkbox" name="selectAllUsersCheckbox" onClick="javascript:selectAllUsers(this.form);"></td>
						  <td><b>Product</b></td>
						  <td><b>Content Name</b></td>
						  <td><b>URL</b></td>
						  <td><b>Add Resources</b></td>
						  <td><b>TransactionId</b></td>
						  <td><b>Earned</b></td>
						  <td><b>Spent</b></td>
						  <td><b>Comments</b></td>
						  <td><b>Date</b></td>
						 </tr>';
		if($CSUserResources) {
		  foreach ($CSUserResources as $CSUserResourcesArray) {
		  
			  
			  $rulesFound=true;
			  $datetime=$CSUserResourcesArray->datetime;
			  $product_id=$CSUserResourcesArray->product_id;
			  
			  $product=Dap_Product::loadProduct($product_id);
			  if(isset($product)) {
				$childproduct = Dap_Product::loadProduct($product_id);
				$credits = $childproduct->getCredits();
				$allowContentLevelCredits = $childproduct->getAllowContentLevelCredits(); 	
				$name=$product->getName();
  //			  $_SESSION['CSSelectProductId']=$product_id;
			  }
			  
			  
			  $user_id=$CSUserResourcesArray->user_id;
			  $resource_id=$CSUserResourcesArray->resource_id;
			  $transaction_id=$CSUserResourcesArray->transaction_id;
			  $credits_earned=$CSUserResourcesArray->credits_earned;
			  $credits_spent=$CSUserResourcesArray->credits_spent;
			  $comments=$CSUserResourcesArray->comments;
			  logToFile("functions_admin.php: ENTER formatCSUserResourcesList: resource_id=". $resource_id ,LOG_DEBUG_DAP);
			  logToFile("functions_admin.php: ENTER formatCSUserResourcesList: product_id=". $product_id ,LOG_DEBUG_DAP);
			  
  
			  $resname="N/A";
			  $resur="N/A";
			  if($resource_id > 0) {
				  $resource = Dap_FileResource::getResourceName($resource_id);
			  	  $resname=$resource['name'];
			  	  $resur=$resource['url'];
				//  logToFile("resname=".$resname.",resurl=".$resur,LOG_DEBUG_DAP);
			  }
			  //logToFile("productId = " . $creditObj->product_id . " name = " . $resource['name'],LOG_DEBUG_DAP);
			  //$product = Dap_Product::loadProduct($product_id);
		  
			  $showDelete = '<a href="#" onclick="javascript:removeRecord('.$user_id.','.$product_id.','.$resource_id.',\''.$datetime.'\',\''.$comments.'\'); return false;"><img src="/dap/images/ximage.jpg" width="13" border="0" height="13"></a>';
  
			 // logToFile("functions_admin.php:  showDelete" . $showDelete,LOG_DEBUG_DAP);
			  
			  $CSUserResourcesHTML .=   
			   "<tr bgcolor=\"#EFEFEF\" onmouseover=\"this.className='highlightedRow'\" onmouseout=\"this.className='nonHighlightedRow'\">
				  <td width=\"20\"><input name=\"selected_users[]\" id=\"selected_users[]\" value=" . $user_id . "," . $product_id . "," . $resource_id . " type=\"checkbox\"></td>";
				  
	  //		$CSUserResourcesHTML .= "<tr align=\"left\" valign=\"top\" bgcolor=\"#EFEFEF\" onmouseover=\"this.className='highlightedRow'\" onmouseout=\"this.className='nonHighlightedRow'\">";
			  
			  $CSUserResourcesHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\"><a href=\"addEditProducts.php?productId=".$product_id."\">".$name."</a></td>";
			  $CSUserResourcesHTML .= "<td align=\"left\" valign=\"top\" >" . $resname . "</td>";
			  $CSUserResourcesHTML .= "<td align=\"left\" valign=\"top\" >" . $resur . "</td>";
			  
			  if($allowContentLevelCredits=="Y") {
				  $CSUserResourcesHTML .= "<td align=\"left\" valign=\"top\" > " . "<a href=\"#\" onClick=\"javascript:loadProductResources(".$user_id.",".$product_id."); return false;\" title='Click to add/edit resources to the user'>Add/Edit Resources</a> " . "</td>";
			  }
			  else {
				  $CSUserResourcesHTML .= "<td align=\"left\" valign=\"top\" > " . "Content Level Credits Not Enabled" . "</td>";
			  }
			  
			  $CSUserResourcesHTML .= "<td align=\"left\" valign=\"top\" > " . $transaction_id . "</td>";
			  $CSUserResourcesHTML .= "<td align=\"left\" valign=\"top\" > " . $credits_earned . "</td>";
			  $CSUserResourcesHTML .= "<td align=\"left\" valign=\"top\" > " . $credits_spent . "</td>";
			  $CSUserResourcesHTML .= "<td align=\"left\" valign=\"top\" > " . $comments . "</td>";
			  $CSUserResourcesHTML .= "<td align=\"left\" valign=\"top\" > " . $datetime . "</td>";
			  
			  $CSUserResourcesHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\">" . $showDelete . "</td></tr>";
			  $CSUserResourcesHTML .= "</tr>";
			  
		//  	logToFile("functions_admin.php:  formatCSUserResourcesList: CSUserResourcesHTML" . $CSUserResourcesHTML,LOG_DEBUG_DAP);
			  
		  }
		}
		$CSUserResourcesHTML .= '</table><br/>';
		
		if($rulesFound == false) { //No data found
			$CSUserResourcesHTML .= "Sorry, no User Credit Resources found";
		} 
		
		
		return $CSUserResourcesHTML;
	}
	
	function formatSupportTicketList($supportTicketList) {
		$supportTicketHTML = "";
		$supportTicketHTML .= '<table id="supportTicketTable" width="100%"  border="0" cellspacing="3" cellpadding="3" class="bodytextArial">
						<tr class="bodytextArial">
						  <td><b>Source Product</b></td>
						  <td><b>Get Support Access URL</b></td>
						</tr>';
		$rulesFound = false;
		
		foreach ($supportTicketList as $supportTicketArray) {
			$rulesFound = true;
			
			$source_product_id = $supportTicketArray["source_product_id"];
			$support_access_url = $supportTicketArray["support_access_url"];
			$source_product = DAP_Product::loadProduct($source_product_id); 
					
			$showDelete = '<a href="#" onclick="javascript:removeRule('.$supportTicketArray["id"].'); return false;"><img src="/dap/images/ximage.jpg" width="13" border="0" height="13"></a>';

			$supportTicketHTML .= "<tr align=\"left\" valign=\"top\" bgcolor=\"#EFEFEF\" onmouseover=\"this.className='highlightedRow'\" onmouseout=\"this.className='nonHighlightedRow'\">";
			$supportTicketHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\"><a href=\"addEditProducts.php?productId=".$supportTicketArray["source_product_id"]."\">".$source_product->getName()."</a></td>";
			$supportTicketHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\">".$supportTicketArray["support_access_url"]."</td>";
			$supportTicketHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\">" . $showDelete . "</td></tr>";
			$supportTicketHTML .= "</tr>";
			
		}
		
		$supportTicketHTML .= '</table><br/>';
		
		if($rulesFound == false) { //No data found
			$supportTicketHTML .= "Sorry, No Support Ticket Rules Found.";
		} 
		
		return $supportTicketHTML;
	}
	
	function formatProductCouponList($productCouponList) {
		$productCouponHTML = "";
		$productCouponHTML .= '<table id="productCouponTable" width="100%"  border="1" cellspacing="3" cellpadding="3" class="bodytextArial">
						<tr class="bodytextArial">
						  <td><b>Product Id</b></td>
						  <td><b>Product Name</b></td>
						  <td><b>Coupon Id</b></td>
						  <td><b>Coupon Code</b></td>
					   </tr>';
		$rulesFound = false;
		
		//logToFile("functions admin, formatProductCouponList=",LOG_INFO_DAP);
			
		foreach ($productCouponList as $productCouponArray) {
			$rulesFound = true;
			
			$product_id = $productCouponArray["product_id"];
			$coupon_id = $productCouponArray["coupon_id"];
			//logToFile("functions admin, formatProductCouponList, productID=".$product_id,LOG_INFO_DAP);
			$product = Dap_Product::loadProduct($product_id);
			$coupon = Dap_Coupon::loadCoupon($coupon_id);
								
			$showDelete = '<a href="#" onclick="javascript:removeProductCouponAssociation('.$productCouponArray["id"].'); return false;"><img src="/dap/images/ximage.jpg" width="13" border="0" height="13"></a>';

			$productCouponHTML .= "<tr align=\"left\" valign=\"top\" bgcolor=\"#EFEFEF\" onmouseover=\"this.className='highlightedRow'\" onmouseout=\"this.className='nonHighlightedRow'\">";
			$productCouponHTML .= "<td align=\"left\" valign=\"top\">" . $product_id . "</td>";
			$productCouponHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\"><a href=\"addEditProducts.php?productId=".$productCouponArray["product_id"]."\">".$product->getName()."</a></td>";
			$productCouponHTML .= "<td align=\"left\" valign=\"top\">" . $coupon_id . "</td>";
			$productCouponHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\"><a href=\"addEditProducts.php?productId=".$productCouponArray["product_id"]."\">".$coupon->getCode()."</a></td>";
			
			$productCouponHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\">" . $showDelete . "</td></tr>";
			$productCouponHTML .= "</tr>";
			
		}
		
		$productCouponHTML .= '</table><br/>';
		
		if($rulesFound == false) { //No data found
			$productCouponHTML .= "Sorry, no Product Coupon Assciation found.";
		} 
		
		return $productCouponHTML;
	}
	
	function formatProductCategoryList($productCategoryList) {
		$productCategoryHTML = "";
		$productCategoryHTML .= '<table id="productCategoryTable" width="100%"  border="0" cellspacing="3" cellpadding="3" class="bodytextArial">
						<tr class="bodytextArial">
						  <td><b>Product Id</b></td>
						  <td><b>Product Name</b></td>
						  <td><b>Category Id</b></td>
						  <td><b>Category Code</b></td>
						  <td>&nbsp;</td>
					   </tr>';
		$rulesFound = false;
		
		//logToFile("functions admin, formatProductCategoryList",LOG_DEBUG_DAP);
		
		if ((!isset($productCategoryList)) || ($productCategoryList == NULL)) {
		//  logToFile("functions admin, formatProductCategoryList, product category association missing",LOG_DEBUG_DAP);
		  $productCategoryHTML .= "Sorry, no Product->Category Association found.";
		  return $productCategoryHTML;
		}
		
		foreach ($productCategoryList as $productCategoryArray) {
			$rulesFound = true;
				
			$product_id =  $productCategoryArray->getProduct_id(); 
			$category_id =$productCategoryArray->getCategory_id(); 
				
		//	logToFile("functions admin, formatProductCategoryList, product_id=".$product_id,LOG_DEBUG_DAP);
		//	logToFile("functions admin, formatProductCategoryList, category_id=".$category_id,LOG_DEBUG_DAP);
			$product = Dap_Product::loadProduct($product_id);
			$category = Dap_Category::loadCategory($category_id);
								
			$showDelete = '<a href="#" onclick="javascript:removeProductCategoryAssociation('.$productCategoryArray->getId().'); return false;"><img src="/dap/images/ximage.jpg" width="13" border="0" height="13"></a>';

			$productCategoryHTML .= "<tr align=\"left\" valign=\"top\" bgcolor=\"#EFEFEF\" onmouseover=\"this.className='highlightedRow'\" onmouseout=\"this.className='nonHighlightedRow'\">";
			$productCategoryHTML .= "<td align=\"left\" valign=\"top\">" . $product_id . "</td>";
			$productCategoryHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\"><a href=\"addEditProducts.php?productId=".$product_id."\">".$product->getName()."</a></td>";
			$productCategoryHTML .= "<td align=\"left\" valign=\"top\">" . $category_id . "</td>";
			$productCategoryHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\"><a href=\"addEditProducts.php?productId=".$product_id."\">".$category->getCode()."</a></td>";
			
			$productCategoryHTML .= "<td align=\"center\" valign=\"top\" class=\"bodytext\">" . $showDelete . "</td></tr>";
			$productCategoryHTML .= "</tr>";
			
		}
		
		$productCategoryHTML .= '</table><br/>';
		
		if($rulesFound == false) { //No data found
			$productCategoryHTML .= "Sorry, no Product->Category Assciation found.";
		} 
		
		return $productCategoryHTML;
	}
	
	
	function formatMasterChildList($masterChildList) {
		$masterChildHTML = "";
		$masterChildHTML .= '<table id="masterChildTable" width="100%"  border="1" cellspacing="3" cellpadding="3" class="BodyTextLarge">
						<tr class="BodyTextLarge">
						  <td><b>Master Product Id</b></td>
						  <td><b>Master Product Name</b></td>
						  <td><b>Master Credits</b></td>
						  <td><b>Child Product Id</b></td>
						  <td><b>Child Product Name</b></td>
						  <td><b>Child Credits</b></td>
						  <td><b>Delete</b></td>
					   </tr>';
		$rulesFound = false;
		
		foreach ($masterChildList as $masterChildArray) {
			$rulesFound = true;
			
			$master_product_id = $masterChildArray["master_product_id"];
			$child_product_id = $masterChildArray["child_product_id"];
		
			$master_product = Dap_Product::loadProduct($master_product_id);
			$child_product = Dap_Product::loadProduct($child_product_id);
								
			$showDelete = '<a href="#" onclick="javascript:removeMasterChildAssociation('.$masterChildArray["id"].'); return false;"><img src="/dap/images/ximage.jpg" width="13" border="0" height="13"></a>';

			$masterChildHTML .= "<tr align=\"left\" valign=\"top\"  class=\"BodyTextLarge\" bgcolor=\"#EFEFEF\"  onmouseover=\"this.className='highlightedRow'\" onmouseout=\"this.className='nonHighlightedRow'\">";
			$masterChildHTML .= "<td align=\"left\" valign=\"top\">" . $master_product_id . "</td>";
			$masterChildHTML .= "<td align=\"left\" valign=\"top\" class=\"BodyTextLarge\"><a href=\"addEditProducts.php?productId=".$masterChildArray["master_product_id"]."\">".$master_product->getName()."</a></td>";
			$masterChildHTML .= "<td align=\"left\" valign=\"top\">" . $master_product->getCredits() . "</td>";
			$masterChildHTML .= "<td align=\"left\" valign=\"top\">" . $child_product_id . "</td>";
			$masterChildHTML .= "<td align=\"left\" valign=\"top\" class=\"BodyTextLarge\"><a href=\"addEditProducts.php?productId=".$masterChildArray["child_product_id"]."\">".$child_product->getName()."</a></td>";
			$masterChildHTML .= "<td align=\"left\" valign=\"top\">" . $child_product->getCredits() . "</td>";
			$masterChildHTML .= "<td align=\"left\" valign=\"top\" class=\"BodyTextLarge\">" . $showDelete . "</td></tr>";
			$masterChildHTML .= "</tr>";
			
		}
		
		$masterChildHTML .= '</table><br/>';
		
		if($rulesFound == false) { //No data found
			$masterChildHTML .= "Sorry, no Product Coupon Assciation found.";
		} 
		
		return $masterChildHTML;
	}
	
	function formatForumPriorityList($forumPriorityList) {
		$forumPriorityHTML = "";
		$forumPriorityHTML .= '<table id="forumPriorityTable" width="80%"  border="0" cellspacing="3" cellpadding="3" class="bodytextArial">
						<tr class="bodytextArial">
						  <td><b>Id</b></td>
						  <td><b>Forum Usergroup Id</b></td>
						  <td><b>Forum Usergroup Title</b></td>
						  <td><b>Priority</b></td>
						</tr>';
		$rulesFound = false;
		logToFile("functions admin, formatForumPriorityList, ENTER FUNCTION", LOG_DEBUG_DAP);
		if($forumPriorityList) {
		//  logToFile("functions admin, formatForumPriorityList, ENTER", LOG_DEBUG_DAP);
		  foreach ($forumPriorityList as $forumPriorityArray) {
			  //logToFile("functions admin, formatForumPriorityList, rulesFound=YES", LOG_DEBUG_DAP);
			  $rulesFound = true;
			  
			  $id = $forumPriorityArray["id"];
			  $target_usergroupId = $forumPriorityArray["target_usergroupId"];
			  $title=Dap_VBForum::fetch_usergroup_name($target_usergroupId);
			  $priority = $forumPriorityArray["priority"];
						  
			  $showDelete = '<a href="#" onclick="javascript:removePriority('.$forumPriorityArray["id"].'); return false;"><img src="/dap/images/ximage.jpg" width="13" border="0" height="13"></a>';
  
			  $forumPriorityHTML .= "<tr align=\"left\" valign=\"top\" bgcolor=\"#EFEFEF\" onmouseover=\"this.className='highlightedRow'\" onmouseout=\"this.className='nonHighlightedRow'\">";
			  $forumPriorityHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\">" . $id . "</td>";
			  $forumPriorityHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\">" . $target_usergroupId . "</td>";
			  $forumPriorityHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\">" . $title . "</td>";
			  $forumPriorityHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\">" . $priority . "</td>";
			  $forumPriorityHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\">" . $showDelete . "</td></tr>";
			  $forumPriorityHTML .= "</tr>";
		  }
		}
		$forumPriorityHTML .= '</table><br/>';
		
		if($rulesFound == false) { //No data found
		// logToFile("functions admin, formatForumPriorityList, rulesFound=NO", LOG_DEBUG_DAP);
			$forumPriorityHTML .= "Sorry, usergroup priority not set.";
		} 
		
		return $forumPriorityHTML;
	}
	
	
	function formatProductForumList($productForumList) {
		$productForumHTML = "";
		$productForumHTML .= '<table id="productForumTable" width="100%"  border="0" cellspacing="3" cellpadding="3" class="bodytextArial">
						<tr class="bodytextArial">
						  <td><b>Source Operation</b></td>
						  <td><b>Source Product</b></td>
						  <td><b>Target Operation</b></td>
						  <td><b>Forum UserGroup Id/Title</b></td>
						</tr>';
		$rulesFound = false;
		
		foreach ($productForumList as $productForumArray) {
			$rulesFound = true;
			
			$source_product_id = $productForumArray["source_product_id"];
			$target_usergroup_id = $productForumArray["target_usergroup_id"];
			$source_product = DAP_Product::loadProduct($source_product_id); 
			
			if ($productForumArray["source_operation"] == "AT")
				$source_oper = "Added To";
			if ($productForumArray["source_operation"] == "RF")
				$source_oper = "Removed From";
			if ($productForumArray["target_operation"] == "A")
				$target_oper = "Add To";
			if ($productForumArray["target_operation"] == "R")
				$target_oper = "Remove From";
				
						
			$showDelete = '<a href="#" onclick="javascript:removeRule('.$productForumArray["id"].'); return false;"><img src="/dap/images/ximage.jpg" width="13" border="0" height="13"></a>';

			$productForumHTML .= "<tr align=\"left\" valign=\"top\" bgcolor=\"#EFEFEF\" onmouseover=\"this.className='highlightedRow'\" onmouseout=\"this.className='nonHighlightedRow'\">";
			$productForumHTML .= "<td align=\"left\" valign=\"top\">" . "If " . $source_oper . "</td>";
			$productForumHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\"><a href=\"addEditProducts.php?productId=".$productForumArray["source_product_id"]."\">".$source_product->getName()."</a></td>";
			$productForumHTML .= "<td align=\"left\" valign=\"top\">" . " then " . $target_oper . "</td>";
			$productForumHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\">" . $productForumArray["title"] . "</td>";
			$productForumHTML .= "<td align=\"left\" valign=\"top\" class=\"bodytext\">" . $showDelete . "</td></tr>";
			$productForumHTML .= "</tr>";
			
				//logToFile("functions admin, title=" . $productForumArray["title"],LOG_INFO_DAP);
		}
		
		$productForumHTML .= '</table><br/>';
		
		if($rulesFound == false) { //No data found
			$productForumHTML .= "Sorry, no Product Forum rules found.";
		} 
		
		return $productForumHTML;
	}
	
	function getHowHighToGo($currentLocation) {
		$steps = explode("/",$currentLocation);
		$stepsRequired = sizeof($steps) - 2;
		$howHigh = "";
		$i = 0;
		while($i++ < $stepsRequired) {
			$howHigh .= "../";
		}
		$howHigh = substr($howHigh, 0, -1);
		return $howHigh;
	}
	

	function getResourcesByProductId($productId) {
	//TODO: Stub
	}

	
	/*
		This function returns a list of all available, filtered, appropriate resources on the server
	*/
	function ListFolder($path, $folderFilterText) {
		global $depth;
		$depth = $depth + 1;
		global $folderFilter;
		global $parentRoot;
		global $timeout;
		global $response;
		
		//echo sizeof($fFilter); exit;
		$folderFilter = $folderFilterText;
		$timeout = true;
		
		//using the opendir function
		$dir_handle = opendir($path) or die("Unable to open $path");
	   
		//Leave only the lastest folder name
		$dirname = end(explode("/", $path));
		
		//echo sizeof($folderFilter); exit;
	   
		//display the target folder.
		if( (sizeof($folderFilter) == 0) || ((sizeof($folderFilter) > 0) && in_array($dirname, $folderFilter)) || ( ($depth > 1) && ($parentRoot == true) ) ) {
			//echo ("<li>$dirname - D:$depth / P:$parentRoot)\n");
			$ind = 1;
			$response .= "<img src=\"../images/folder.gif\">$dirname <a href=\"#\" onClick=\"javascript:addFolderResourcesToProduct('" .$path."/".$file. "'); return false;\">Add</a> <a href=\"#\" onClick=\"javascript:removeFolderResourcesFromProduct('" .$path."/".$file. "'); return false;\">Remove</a> </li>";
			$response .= "<ul>";
		}
						
		if( ($dirname == "..") || (sizeof($folderFilter) == 0) || ( (sizeof($folderFilter)!= 0) && in_array($dirname, $folderFilter))  || ( ($depth > 0) && ($parentRoot == true) ) )
		{
			//echo "here";
			if($depth == 1) {
				if( (sizeof($folderFilter) == 0) || in_array($dirname, $folderFilter)) {
					//echo "making parentroot true<br>";
					$parentRoot = true;
				} else {
					//echo "making parentroot false<br>";
					$response .=  "</ul>";
					$parentRoot = false;
					$depth = $depth - 1;
					return;
				}
			}
			while (false !== ($file = readdir($dir_handle)) )
			{
				//if($file!="." && $file!=".." && (!in_array($file, $folderFilter)) )
				if($file!="." && $file!="..")
				{
					if (is_dir($path."/".$file) )
					{
						//Display a list of sub folders.
						ListFolder($path."/".$file, $folderFilterText);
					}
					else
					{
						//Display a list of files.
						//if ( ($dirname != "..") && ($depth >= 0) )
							//echo "<li>$depth: $file <a href=\"#\" onClick=\"javascript:addResourceToProduct('" .$path."/".$file. "'); return false;\">Add</a></li>";
							//if( (sizeof($folderFilter) == 0) || ($parentRoot == true) )
							if( ($parentRoot == true) )
								$response .=  "<li>$file <a href=\"#\" onClick=\"javascript:addFileResourceToProduct('" .$path."/".$file. "'); return false;\">Add</a></li>";
					}
				}
			}
		}
		//$response .= "</ul>";
		//$response .= "</li>";
		$response .= "</ul>";
	   
		//closing the directory
		closedir($dir_handle);
		$depth = $depth - 1;
		$timeout = false;		
		return;
	}
	
	
	function tooManyFiles() {
		global $timeout;
		if($timeout == TRUE) {
        	echo "Sorry, too many files being returned. Please use filter to reduce the number.";
		}
    }


	function getCurrentLogFileName() {
		$date = date("m-d-Y");
		
	}
	
	function addUsersToProductsController($productId, $user_list) {
		foreach($user_list as $userId) {
			//Add given users to this product
			Dap_UsersProducts::addUsersProducts($userId, $productId);
		}
	}

	
	function removeUsersFromProductsController($productId, $user_list) {
		//Remove all users from this product
		foreach($user_list as $userId) {
			//Add all users to this product
			Dap_UsersProducts::removeUsersProducts($userId, $productId);
		}
	}

	
	function loadUsersFullDisplayHTML($userFilter, $status) {
		$UsersList = loadUsers($userFilter, $status);
		$userListHTML = '<table id="usersTable" width="95%"  border="0" cellspacing="5" cellpadding="0" class="bodytextArial">';
		$dataFound = false;
		
		foreach ($UsersList as $user) {
			$dataFound = true;
			$userListHTML .=   
			"<tr bgcolor=\"#EFEFEF\">
				<td width=\"20\"><input name=\"form_selected_users[]\" id=\"form_selected_users[]\" value=" . $user->getId() . " type=\"checkbox\"></td>
				<td><a href='/dap/admin/addEditUsers.php?userId=" . $user->getId() . "'>" . $user->getId() . "</a></td>
				<td>" . $user->getFirst_name() . " " . $user->getLast_name() . "</td>
				<td>" . $user->getEmail() . "</td>
			</tr>";
		}
		
		if($dataFound == false) {
			//logToFile("false",LOG_DEBUG_DAP);
			$userListHTML .= "<tr bgcolor=\"#EFEFEF\">
				<td colspan=\"4\">Sorry, no data found.</td>
			</tr>";
		};

		$userListHTML .= '</table>';
		return $userListHTML;
	}


	function loadTransactionsDisplayHTML($transNumFilter, $emailFilter, $productIdFilter, $statusFilter, $couponIdFilter="", $userIdFilter="", $startDateFilter, $endDateFilter) {
		global $transaction_statuses;
		
		$TransactionsList = Dap_Transactions::loadTransactions($transNumFilter, $emailFilter, $productIdFilter, $statusFilter, $couponIdFilter, $userIdFilter, "", $startDateFilter, $endDateFilter);
		$transactionsListHTML = '<table id="transactionsTable" width="100%"  border="0" cellspacing="10" cellpadding="0" class="bodytextArial">';
		$transactionsListHTML .=   
		"<tr valign=\"top\" bgcolor=\"#EFEFEF\" >
			<td width=\"20\">&nbsp;</td>
			<td><b>Id</b></td>
			<td><b>Trans. Number</b></td>
			<td><b>Payer Email</b></td>
			<td><b>Transaction Type</b></td>
			<td><b>Product Id</b></td>
			<td><b>User Id</b></td>
			<td><b>Coupon Id</b></td>
			<td nowrap><b>Date</b></td>
			<td><b>Status</b></td>
			<td><b>Payment Status</b></td>
			<td><b>Amount</b></td>
		</tr>";
		$dataFound = false;
		
		foreach ($TransactionsList as $transaction) {
			$dataFound = true;
			//logToFile("coupon code=" . $transaction->getCoupon_id() ,LOG_DEBUG_DAP);
			$transactionsListHTML .=   
			"<tr bgcolor=\"#EFEFEF\">
				<td width=\"20\"><input name=\"form_selected_transactions[]\" value=" . $transaction->getId() . " type=\"checkbox\"></td>
				<td><a href='/dap/admin/manageTransactions.php?transactionId=" . $transaction->getId() . "'>" . $transaction->getId() . "</a></td>
				<td nowrap>" . $transaction->getTrans_num() . "</td>
				<td nowrap>" . $transaction->getPayer_email() . "</td>
				<td nowrap>" . $transaction->getTrans_type() . "</td>
				<td nowrap>" . $transaction->getProduct_name() . "</td>
				<td nowrap><a href='/dap/admin/manageUsersProducts.php?userId=" . $transaction->getUser_id() . "'>" . $transaction->getUser_id() . "</a></td>
				<td nowrap><a href='/dap/admin/addEditCoupons.php?couponId=" . $transaction->getCoupon_id() . "'>" . $transaction->getCoupon_id() . "</a></td>
				<td nowrap>" . $transaction->getDate() . "</td>
				<td nowrap>" . $transaction_statuses[$transaction->getStatus()] . "</td>
				<td nowrap>" . $transaction->getPayment_status() . "</td>
				<td nowrap>" . $transaction->getPayment_value() . "</td>
			</tr>";
		}
		
		if($dataFound == false) {
			//logToFile("false",LOG_DEBUG_DAP);
			$transactionsListHTML .= "<tr bgcolor=\"#EFEFEF\">
				<td colspan=\"9\">Sorry, no data found.</td>
			</tr>";
		};
		
		$transactionsListHTML .= '</table>';
		return $transactionsListHTML;
	}


	function loadCategoryAsSelectBox() {
		try {
			// logToFile("functions_admin: loadCategoryAsSelectBox(): value=".$value,LOG_DEBUG_DAP);  	
			$categoryList = Dap_Category::loadAllCategories("");
			//	$couponListHTML = '<option value="Create New">Create New</option>';
			
			
			foreach ($categoryList as $category) {
				$categoryListHTML .= "<option value=\"" . $category->getId() . "\">" . stripslashes($category->getCode()) . "</option>\n";
			}
			// logToFile("functions_admin: loadCategoryAsSelectBox(): value=".$value,LOG_DEBUG_DAP);  
			return $categoryListHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}
	
	function loadCouponsAsSelectBox() {
		try {
			$couponsList = Dap_Coupon::loadCoupons("");
			//	$couponListHTML = '<option value="Create New">Create New</option>';
							  
			foreach ($couponsList as $coupon) {
				$couponListHTML .= "<option value=\"" . $coupon->getId() . "\">" . stripslashes($coupon->getCode()) . "</option>\n";
			}
			
			return $couponListHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}
	
	function loadProductsAsSelectBox() {
		try {
			$orderBy = isset($_SESSION['orderBy']) ? $_SESSION['orderBy'] : "id";
			$orderHow = isset($_SESSION['orderHow']) ? $_SESSION['orderHow'] : "asc";
			
			$ProductsList = Dap_Product::loadProducts("","",$orderBy,$orderHow);
			//$productListHTML = '<option selected value="All">All</option>';
			$productListHTML = "";
					  
			foreach ($ProductsList as $product) {
				$productName = stripslashes($product->getName());
				$productNameShort = trimString($productName,40,30,10);
				$productListHTML .= "<option value=\"" . $product->getId() . "\" title=\"".$productName."\">" . $productNameShort . "</option>\n";
			}
			
			return $productListHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}
	
	function trimString($str,$trimOverLength,$keepFirstXChars,$keepLastXChars) {
		$trimOverLength = intval($trimOverLength);
		$keepFirstXChars = intval($keepFirstXChars);
		$keepLastXChars = intval($keepLastXChars);
		
		
		$str = stripslashes($str);
		if( strlen($str) > $trimOverLength) {
			$str = substr($str,0,$keepFirstXChars) . "....." . substr($str,-$keepLastXChars);
		}
		//return $str;
		return mb_convert_encoding($str, "UTF-8", "auto");
	}

	function trimStringLength($str,$trimOverLength,$keepFirstXChars) {
		$trimOverLength = intval($trimOverLength);
		$keepFirstXChars = intval($keepFirstXChars);
	//	$keepLastXChars = intval($keepLastXChars);
		
		
		$str = stripslashes($str);
		if( strlen($str) > $trimOverLength) {
			$str = substr($str,0,$keepFirstXChars);
		}
		logToFile("functions_admin: trimOverLength=" . $trimOverLength);	
		logToFile("functions_admin: keepFirstXChars=" . $keepFirstXChars);	
		logToFile("functions_admin: product short desc=" . $str);	
		return $str;
	}
	
	function loadCustomFieldsAsSelectBox() {
		try {
			$customFieldsList = Dap_CustomFields::loadCustomFields();
			//$productListHTML = '<option selected value="All">All</option>';
			$customField = "";
					  
			foreach ($customFieldsList as $customField) {
				$customListHTML .= "<option value=\"" . $customField['id'] . "\">" . stripslashes($customField['name']) . "</option>\n";
			}
			
			return $customListHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}
	
	function loadMasterProductsAsSelectBox() {
		try {
			$ProductsList = Dap_Product::loadProductsForSSS("Y");
			//$productListHTML = '<option selected value="All">All</option>';
			$productListHTML = "";
					  
			foreach ($ProductsList as $product) {
				$productListHTML .= "<option value=\"" . $product->getId() . "\">" . stripslashes($product->getName()) . "</option>\n";
			}
			
			return $productListHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}
	
	function loadChildProductsAsSelectBox() {
		try {
			$ProductsList = Dap_Product::loadProductsForSSS("N");
			//$productListHTML = '<option selected value="All">All</option>';
			$productListHTML = "";
					  
			foreach ($ProductsList as $product) {
				$productListHTML .= "<option value=\"" . $product->getId() . "\">" . stripslashes($product->getName()) . "</option>\n";
				//logToFile("loadChildProductsAsSelectBox: " . $productListHTML,LOG_DEBUG_DAP);
			}
			
			return $productListHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}
	
	function loadCSProductsAsSelectBox() {
		try {
			$ProductsList = Dap_Product::loadProductsForSSS("ALL");
			//$productListHTML = '<option selected value="All">All</option>';
			$productListHTML = "";
					  
			foreach ($ProductsList as $product) {
				$productListHTML .= "<option value=\"" . $product->getId() . "\">" . stripslashes($product->getName()) . "</option>\n";
				//logToFile("loadChildProductsAsSelectBox: " . $productListHTML,LOG_DEBUG_DAP);
			}
			
			return $productListHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}
	
		
	function loadResourcesUnderContentLevelChildProductsAsHTML($userId,$productId) {
		//logToFile("loadResourcesUnderContentLevelChildProductsAsHTML: productId=" . $productId,LOG_DEBUG_DAP);
		
	 	$UserResourcesHTML .= '<table width="99%" border="0" align="center" cellpadding="10" cellspacing="10" class="bodytextLarge">';
        $UserResourcesHTML .= '<tr>
		<td class="bodytext"><select name="pickResource" class="bodytext">
		<option selected value="">Select</option>';
        $UserResourcesHTML .= loadResourcesUnderContentLevelChildProductsAsSelectBox($userId,$productId);
		$UserResourcesHTML .= '</select></td>';
		$UserResourcesHTML .=  '</tr> 
        <tr> 
        <td><input name="addCSResourceInput" type="button" value="Add Credit Resource" onClick="addCSResource(document.ManageCSUserResourcesForm); return false;"></td> 
        </tr> 
		</table>
		 <div align="center"><br> 
		  <a href="#" onClick="hideDiv(' . "'add_productresources_div'" . '); return false;">close</a>
		</div>';
      
		return $UserResourcesHTML;
	}
		
		
	function loadResourcesUnderContentLevelChildProductsAsSelectBox($userId,$productId) {
	  try {
		//logToFile("loadResourcesUnderContentLevelChildProductsAsSelectBox: : ENTER",LOG_DEBUG_DAP);
		//$productId=$_SESSION['CSSelectProductId'];
		//logToFile("loadResourcesUnderContentLevelChildProductsAsSelectBox: productId=" . $productId,LOG_DEBUG_DAP);
		
		if($productId) {
		  $UserResources = Dap_FileResource::loadProtectedFileResourcesPerProduct($productId);
		  
		  //$productListHTML = '<option selected value="All">All</option>';
		  $productListHTML = "";
					
		  foreach ($UserResources as $resource) {
			  $UserResourcesHTML .= "<option value=\"" . $resource['file_resource_id'] . "\">" . stripslashes($resource['url']) . "</option>\n";
			  //logToFile("loadResourcesUnderContentLevelChildProductsAsSelectBox: " . $resource['file_resource_id'],LOG_DEBUG_DAP);
		  }
		}
		
		return $UserResourcesHTML;
	  } catch (PDOException $e) {
		  return ERROR_DB_OPERATION;
	  } catch (Exception $e) {
		  return ERROR_GENERAL;
	  }	
	}
	
	function loadCategorysAsSelectBox() {
		try {
			$categoryList = Dap_Category::loadCategory("");
			//	$couponListHTML = '<option value="Create New">Create New</option>';
							  
			foreach ($categoryList as $category) {
				$categoryListHTML .= "<option value=\"" . $category->getId() . "\">" . stripslashes($category->getCode()) . "</option>\n";
			}
			
			return $categoryListHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}
	
	
	function loadAllEmailResourcesAsSelectBox() {
		try {
			$EmailResourceList = Dap_EmailResource::loadAllEmailResources();
			$EmailResourceListHTML = "";
			
			foreach ($EmailResourceList as $emailResource) {
				$subject = stripslashes($emailResource->getSubject());
				$subjectShort = trimString($subject,40,20,10);
				$EmailResourceListHTML .= "<option value=\"" . $emailResource->getId() . "\" title=\"$subject\">" . $subjectShort . "</option>\n";
			}
			return $EmailResourceListHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}
	
	function loadAllFileResourcesAsDropdown() {
		try {
			//$FileResourceList = Dap_FileResource::loadAllAvailableFileResources();
			$fileResourceListHTML='<select id="fileResources" name="fileResources" style="width:600px!important;" class="scriptsubheading">';
			$bgcolor = "#EEEEEE";	
			$fileResourceListHTML .= "<option selected value=''>Select Page / Post</option>\n";
			if( file_exists("../dap_permalink_dump.php") ) {
    			$fileContents = file_get_contents("../dap_permalink_dump.php");
				$permalinks = explode("\n", $fileContents);
				foreach ($permalinks as $permalink) {
					if( isset($permalink) && ($permalink != "") ) {
						$permalink = trim($permalink);
						if( substr($permalink, 0, 4) == "----" ) { //Title
							$fileResourceListHTML .= "<option value=\"\">" . $permalink . "</option>\n";
						} else {
							$permalinkShort = substr($permalink,strpos($permalink, "/", 7));
							$fileResourceListHTML .= "<option value=\"" . $permalink . "\" onDblClick=\"window.open('".$permalink."');\">" . $permalinkShort . "</option>\n";
						}
					}
				}
			}
							
			/*foreach ($FileResourceList as $fileResource) {
				$url = stripslashes($fileResource["url"]);
				$url = trimString($url,40,20,10);
				$id=$fileResource["id"];
				$fileResourceListHTML .= "<option value=\"" . $id . "\">" . $url . "</option>\n";
			}*/
			$fileResourceListHTML.="</select>";
			
			return $fileResourceListHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}
	
	function loadAllEmailResourcesAsHTML() {
		try {
			$EmailResourceList = Dap_EmailResource::loadAllEmailResources();
			$EmailResourceListHTML = "<ul class=\"sidebullet\">";
			
			$bgcolor = "#EEEEEE";		  
			foreach ($EmailResourceList as $emailResource) {
				//$EmailResourceListHTML .= "<option value=\"" . $emailResource->getId() . "\" onClick=\"loadEmailResource(".$emailResource->getId().");\">" . $emailResource->getSubject() . "</option>\n";
				$subject = stripslashes($emailResource->getSubject());
				$subjectShort = trimString($subject,40,20,10);
				$EmailResourceListHTML .= "<li title='$subject'>".$subjectShort."&nbsp;&nbsp;&nbsp;<a href=\"#\" onClick=\"javascript:addEmailResourceToProduct(".$emailResource->getId()."); return false;\" title=\"Add this Email to this Product's Autoresponder series\">add</a>&nbsp;|&nbsp;<a href=\"manageEmails.php?emailResourceId=" . $emailResource->getId() . "\" title='Edit Email Subject & Body'>edit</a></li>";
			}
			
			$EmailResourceListHTML .= "</ul>";
			return $EmailResourceListHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}

	function loadProductsAsDropDown() {
		try {
			$ProductsList = Dap_Product::loadProducts("","A");
			//$productListHTML = "<option value=\"All\">All</option>\n";
			$productListHTML  = "";
					  
			foreach ($ProductsList as $product) {
				$productName = stripslashes($product->getName());
				$productNameShort = trimString($productName,51,35,10);
				
				$productListHTML .= "<option value=\"" . $product->getId() . "\">" . $productNameShort . "</option>\n";
			}
			
			return $productListHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}
	
	function loadAllCategoriesAsDropdown() {
		try {
			$CategoryList = Dap_Category::loadAllCategories("");
			$categoryListHTML  = "";
			$categoryListHTML .= "<option value=\"ALL\">ALL</option>\n";		  
			foreach ($CategoryList as $category) {
				$categoryName = stripslashes($category->getCode());
				$categoryNameShort = trimString($categoryName,51,35,10);
				$categoryListHTML .= "<option value=\"" . $categoryName . "\">" . $categoryNameShort . "</option>\n";
			}
			return $categoryListHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}
	
		function loadUsergroupIdsAsDropDownForForumMapping() {
		try {
			$forum = new Dap_VBForum();
			$UserGroupListHTML = $forum->fetch_usergroups();
			return $UserGroupListHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}
	
	function loadProductsAsDropDownForChaining() {
		try {
			$ProductsList = Dap_Product::loadProducts("","A");
			//$productListHTML = "<option value=\"All\">All</option>\n";
			$productListHTML  = "";
			$productListHTML .= "<option SELECTED value=\"" . "0" . "\">" . "Select A Product" . "</option>\n";		  
			foreach ($ProductsList as $product) {
				$productListHTML .= "<option value=\"" . $product->getId() . "\">" . stripslashes($product->getName()) . "</option>\n";
			}
			
			return $productListHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}
	
	function loadProductDescAsDropDown() {
		try {
			$ProductsList = Dap_Product::loadProducts("","A");
			//$productListHTML = "<option value=\"All\">All</option>\n";
			$productListHTML  = "";
					  
			foreach ($ProductsList as $product) {
				$productListHTML .= "<option value=\"" . $product->getName() . "\">" . $product->getName() . "</option>\n";
			}
			
			return $productListHTML;
		} catch (PDOException $e) {
			return ERROR_DB_OPERATION;
		} catch (Exception $e) {
			return ERROR_GENERAL;
		}	
	}
	
	function writeToFile($msg, $fileName, $mode="a") {
		$fd = fopen($fileName, $mode);
		fwrite($fd, $msg);
		fclose($fd);
	}

	//File Browser function for a specific, given folder
	function ls($pattern="*", $folder="", $recursivly=false, $options=array('return_files','return_folders')) {
		$current_folder = getcwd();
		if($folder) {
			$folder = preg_replace('#([\\/]){2,}#', '$1', $folder);
			if(in_array('quiet', $options)) { // If quiet is on, we will suppress the 'no such folder' error
				if(!file_exists($folder)) return array();
			}
			
			if(!chdir($folder)) return array();
		}
		
		$get_files    = in_array('return_files', $options);
		$get_folders= in_array('return_folders', $options);
		$both = array();
		
		// Get the all files and folders in the given directory.
		if($get_files) $both = glob($pattern, GLOB_BRACE + GLOB_MARK);
		if($recursivly or $get_folders) $folders = glob("*", GLOB_ONLYDIR + GLOB_MARK);
		
		//If a pattern is specified, make sure even the folders match that pattern.
		$matching_folders = array();
		if($pattern !== '*') $matching_folders = glob($pattern, GLOB_ONLYDIR + GLOB_MARK);
		
		chdir($current_folder); // Necessary incase of relative filepaths
		
		//Get just the files by removing the folders from the list of all files.
		$all = array_values(array_diff($both,$folders));
			
		if($recursivly or $get_folders) {
			foreach ($folders as $this_folder) {
				if($get_folders) {
					//If a pattern is specified, make sure even the folders match that pattern.
					if($pattern !== '*') {
						if(in_array($this_folder, $matching_folders)) array_push($all, $this_folder);
					}
					else array_push($all, $this_folder);
				}
				
				if($recursivly) {
					// Continue calling this function for all the folders
					$new_folder = "$folder/$this_folder";
					if(!$folder) $new_folder = $this_folder;
					$deep_items = ls($pattern, $new_folder, $recursivly, $options); # :RECURSION:
					foreach ($deep_items as $item) {
						array_push($all, $this_folder . $item);
					}
				}
			}
		}
		return $all;
	}
	
	function loadEmailResourceAsArray($emailResourceId) {
		$emailResource = new DAP_EmailResource($emailResourceId);
		$emailResource->load();
		$emailResourceArray = $emailResource->toArray();
		
		if($emailResourceArray["attachment"] == "") {
			$emailResourceArray["attachment"] = "No attachments found in this Email";
		} else {
			$emailResourceArray["attachment"] = formatAssignedAttachments($emailResourceArray["attachment"]);
		}
		
		Dap_Session::set('emailResource', $emailResource);
		return $emailResourceArray;
	}
	
	
	function formatAssignedAttachments($attachmentsString) {
		$attachmentsArray = explode(",",trim($attachmentsString,","));
		$response = "<ul>";
		foreach($attachmentsArray as $file) {
			$response .= "<li>" . $file. "&nbsp;&nbsp;<a href=\"#\" onClick=\"removeAttachmentFromEmailResource('$file');\">remove</a></li>";
		}
		$response .= "</ul>";
		
		return $response;
	}
	
	function logToFileArray($inputArray) {
		foreach($inputArray as $input) {
			logToFile($input,LOG_DEBUG_DAP);
		}
	}

	function cleanArray($inputArray) {
		foreach($inputArray as $key => $value) {
			if( (trim($key) == null) || (trim($key) == "") ) {
				unset($inputArray[$key]);
			}
		}

		return array_values($inputArray);
	}
	
	function removeElementFromArray($element, $inputArray) {
		foreach($inputArray as $key => $value) {
			if($value == $element) {
				unset($inputArray[$key]);
			}
		}
		return array_values($inputArray);
	}
	 
	function loadAllCommissions($user_id, $product_id) {
		$affCommissionsArray = Dap_AffCommissions::loadAllCommissions($user_id, $product_id);
		$html = '<table width="800" border="0" cellspacing="0" cellpadding="7">
				<tr>
				  <td><b>User Id</b></td>
				  <td nowrap><b>Product Name</b></td>
				  <td><b>Per-Lead<br/>Fixed (Cash)</b></td>
				  <td><b>Per-Sale<br/>Fixed (Cash)</b></td>
				  <td><b>Per-Sale<br/>Percentage (Cash)</b></td>
				  <td bgColor="#EFEFEF"> </td>
				  <td><b>Per-Lead<br/>Fixed (Credits)</b></td>
				  <td><b>Per-Sale<br/>Fixed (Credits)</b></td>
				  <td><b>Per-Sale<br/>Percentage (Credits)</b></td>
				  <td bgColor="#EFEFEF"> </td>
				  <td><b>Commission<br/>
					Recurring?</b></td>
				  <td><b>Tier</b></td>
				  <td>&nbsp;</td>
				  <td>&nbsp;</td>
				</tr>';
		$resultsFound = false;
		foreach ($affCommissionsArray as $affCommission) {
			$resultsFound = true;
			$user_id_alt = ($affCommission->getUser_id() == 0) ? "ALL" : $affCommission->getEmail();
			$html .= "<tr>
					  <td><a href='manageCommissions.php?userId=".$affCommission->getUser_id()."'>".$user_id_alt."</a></td>
					  <td><a href='manageCommissions.php?productId=".$affCommission->getProduct_id()."'>".$affCommission->getProduct_name()."</a></td>
					  
					  <td><input type='text' size='5' name='per_lead_fixed_".$affCommission->getId()."' value='".$affCommission->getPer_lead_fixed()."'></td>
					  <td><input type='text' size='5' name='per_sale_fixed_".$affCommission->getId()."' value='".$affCommission->getPer_sale_fixed()."'></td>
					  <td><input type='text' size='5' name='per_sale_percentage_".$affCommission->getId()."' value='".$affCommission->getPer_sale_percentage()."'></td>
					  <td bgColor='#EFEFEF'> </td>
					  <td><input type='text' size='5' name='per_lead_fixed_credits_".$affCommission->getId()."' value='".$affCommission->getPer_lead_fixed_credits()."'></td>
					  <td><input type='text' size='5' name='per_sale_fixed_credits_".$affCommission->getId()."' value='".$affCommission->getPer_sale_fixed_credits()."'></td>
					  <td><input type='text' size='5' name='per_sale_percentage_credits_".$affCommission->getId()."' value='".$affCommission->getPer_sale_percentage_credits()."'></td>
					  <td bgColor='#EFEFEF'> </td>
					  <td><input type='text' size='1' maxlength='1' name='is_comm_recurring_".$affCommission->getId()."' value='".$affCommission->getIs_comm_recurring()."'></td>
					  <td><input type='text' size='2' maxlength='2' name='tier_".$affCommission->getId()."' value='".$affCommission->getTier()."'></td>
					  <td><input type='button' value='Update' onClick=update(".$affCommission->getId().");></td>
					  <td><a href='deleteCommission.php?id=".$affCommission->getId()."'>del</a></td>
					</tr>";
		}
		
		if(!$resultsFound) {
			$html .= '<tr><td colspan="8">Sorry, no commission records found for this user/product.<br/><br/>
			<a href="#" onClick="toggleTwoDivs();">Click here to add a new record</a></td></tr></table>';
		}
		$html .= '</table>';
		return $html;
		//logToFile($html,LOG_DEBUG_DAP);
	}
	
	
	function loadAffiliateEarningsSummaryDisplayHTML($email, $start_date, $end_date) {
		$result = Dap_AffCommissions::loadAffiliateEarningsSummary($email, $start_date, $end_date);
		$dataFound = false;
		//logToFile(sizeof($result),LOG_DEBUG_DAP);
		$html = '<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0" class="bodytextArial">
				  <tr class="scriptsubheading">
					<td>' . COLNAME_AFFID_TEXT . ' </td>
					<td>' . COLNAME_FIRSTNAME_TEXT . ' </td>
					<td>' . COLNAME_LASTNAME_TEXT . ' </td>
					<td>' . COLNAME_EMAIL_TEXT . ' </td>
					<td>' . COLNAME_AMTEARNED_TEXT . '('.Dap_Config::get("CURRENCY_SYMBOL").')</td>
				  </tr>
				';
		foreach($result as $element) {
			$dataFound = true;
			$html .= "<tr>
						<td><a href='/dap/admin/addEditUsers.php?userId=".$element['affiliate_id']."'>".$element['affiliate_id']."</a></td>
						<td>".$element['first_name']."</td>
						<td>".$element['last_name']."</td>
						<td>".$element['email']."</td>
						<td>".$element['amt_earned']."</td>
					  </tr>";
		}
		
		if($dataFound == false) {
			$html .= "<tr><td colspan='5'>" . MSG_SORRY_NO_DATA_FOUND . "</td></tr>";
		}
		
		$html .= "</table>";
		return $html;
	}
	
	function loadAffiliateEarningsSummaryForAffiliateDisplayHTML($email, $start_date, $end_date) {
		$result = Dap_AffCommissions::loadAffiliateEarningsSummary($email, $start_date, $end_date);
		$dataFound = false;
		$amountEarned = MSG_NO_COMM;
		$html = '<table class="dap_affiliate_details_table" border="0">
			  <tr>
				<td align="center">
				<div align="center" class="regulartextVeryLargeBoldRed">';

		foreach($result as $element) {
			$amountEarned = Dap_Config::get("CURRENCY_SYMBOL") . ' ' . $element['amt_earned'];
		}
		
		$html .= $amountEarned . '</div></td></tr></table><br/><br/>';
		return $html;
	}

	function loadAffiliateEarningsDetailsDisplayHTML($email, $start_date, $end_date, $earningTypes) {
		$earningTypes = stripslashes($earningTypes);
		$result = Dap_AffCommissions::loadAffiliateEarningsDetails($email, $start_date, $end_date, $earningTypes);
		$dataFound = false;
		$totalEarnedCash = 0;
		$totalEarnedCredits = 0;
		//logToFile(sizeof($result),LOG_DEBUG_DAP);
		$html = '<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0">
				  <tr class="scriptsubheading">
					<td>Aff Id</td>
					<td>Name</td>
					<td>Email</td>
					<td>Product</td>
					<td>Referral Date</td>
					<td>Date/Time</td>
					<td>Trans. Id</td>
					<td>Amount Earned ('.Dap_Config::get("CURRENCY_SYMBOL").')</td>
					<td>Earnings Type <img src="/dap/images/tooltips/help_icon_shaded.png" alt="L:Lead, S:Sale, C:Credits" title="L:Lead, S:Sale, C:Credits" border="0" align="absmiddle"></td>
					<td>Id of User Referred</div></td>
					<td>Already Paid?</div></td>
					<td>Delete</div></td>
				  </tr>
				';
				
		foreach($result as $element) {
			$dataFound = true;
			$bgColor="#FFFFFF";
			$bgColorPaid="#D5F9D4";
			$isPaid = "Yes";
			
			if($element['earning_type'] == "C") $bgColor = "#D9D9F9";
			if(intval($element["amount_earned"]) < 0) $bgColor = "#FFDFB0";
			if( is_null($element['aff_exports_id']) || ($element['aff_exports_id'] == "") ) {
				$isPaid = "";
				$bgColorPaid="#CFCFCF";
			}
			
			$transIdHTML = ($element['transaction_id'] == 0) ? 
					"-"
					:
					"<a href='/dap/admin/manageTransactions.php?transactionId=".$element['transaction_id']."'>".$element['transaction_id']."</a>";
					
			$deleteHTML = ($isPaid != "") ? "" : "<a href='#' onclick=\"javascript:deleteCommissionRow(".$element['earnings_id']."); return false;\"><img src='/dap/images/ximage.jpg' height='13' width='13' border='0' title='You can only delete unpaid commissions'></a>";

			$html .= "<tr class='bodytext' style='background: $bgColor'>
						<td><a href='/dap/admin/addEditUsers.php?userId=".$element['affiliate_id']."'>".$element['affiliate_id']."</a></td>
						<td>".$element['first_name']." ".$element['last_name']."</td>
						<td>".$element['email']."</td>
						<td>".$element['product_name']."</td>
						<td>".$element['referral_date']."</td>
						<td>".$element['datetime']."</td>
						<td>$transIdHTML</td>
						<td align='right'>".$element['amount_earned']."</td>
						<td>".$element['earning_type']."</td>
						<td><a href='/dap/admin/addEditUsers.php?userId=".$element['user_id']."' target='_blank'>".$element['user_id']."</a></td>
						<td align='center' style='background: $bgColorPaid'><strong>".$isPaid."</strong></td>
						<td align='center'>$deleteHTML</td>
					  </tr>";
			
			if($element['earning_type'] == "C") {
				$totalEarnedCredits += floatval($element['amount_earned']);
			} else if( ($element['earning_type'] == "L") || ($element['earning_type'] == "S") ) {
				$totalEarnedCash += floatval($element['amount_earned']);
			}
		}
		
		if($dataFound == false) {
			$html .= "<tr><td colspan='12'>Sorry, no data found.</td></tr>";
		} else {
			$html .= "<tr>
						<td colspan='7' align='right'><h2>Total Earned</h2></td>
						<td align='right'><h2>Cash: $totalEarnedCash<br/><br/>
						Credits: $totalEarnedCredits</h2></td>
						<td colspan='4'>&nbsp;</td>
					 </tr>";
		}
		
		$html .= "</table>";
		return $html;
	}


	function loadAffiliateEarningsDetailsForAffiliateDisplayHTML($email, $start_date, $end_date) {
		$result = Dap_AffCommissions::loadAffiliateEarningsDetails($email, $start_date, $end_date);
		$dataFound = false;
		$showReferralDetails = Dap_Config::get("SHOW_REFERRAL_DETAIL");
		
		//logToFile(sizeof($result),LOG_DEBUG_DAP);
		$html = '<table class="dap_affiliate_details_table">
				  <tr class="regulartextLargeBold">
					<td>'.COLNAME_REFERRAL_DETAILS.'</td>
					<td>'.COLNAME_REFERRAL_DATE.'</td>
					<td>'.COLNAME_REFUND_DATE.'</td>
					<td>'.COLNAME_PRODUCT.'</td>
					<td>'.COLNAME_EARNING_TYPE.'<br/><div class="plaintext">'.COLNAME_LSC.'</div></td>
					<td>'.COLNAME_AMTEARNED_TEXT.' ('.Dap_Config::get("CURRENCY_SYMBOL").')</td>
				  </tr>
				';
		foreach($result as $element) {
			if(!isset($element['refund_date'])) $element['refund_date'] = "";
			
			//Default data exposed to Affiliate about his Referral, is F-LI (First Name, Last Initial)
			$referralDetails = $element['first_name_buyer'] . " " . substr($element['last_name_buyer'],0,1);
			if($showReferralDetails == "F-L") {
				$referralDetails = $element['first_name_buyer'] . " " . $element['last_name_buyer'];
			} else if($showReferralDetails == "F-L-E") {
				$referralDetails = $element['first_name_buyer'] . " " . $element['last_name_buyer'] . "<br/> " . 
				$element['email_buyer'];
			} else if($showReferralDetails == "F-L-E-P") {
				$referralDetails = $element['first_name_buyer'] . " " . $element['last_name_buyer'] . "<br/> " . 
				$element['email_buyer'] . "<br/>Ph: " . $element['phone_buyer'];
			}
			
			$dataFound = true;
			$bgColor = "#FFFFFF";
			$transNum = $element['trans_num'];
			if(intval($element["amount_earned"]) < 0) {
				$element['referral_date'] = "";
				//$transNum = substr($transNum,-6);
				$bgColor = "#FFDFB0";
			} else {
				$element['refund_date'] = "";
				//$transNum = substr($transNum,-4);
			}

			$html .= "<tr valign='top' class='regulartext'>
						<td align='left' style='background: $bgColor'>". $referralDetails . "</td>
						<td align='left' style='background: $bgColor'>".$element['referral_date']."</td>
						<td align='left' style='background: $bgColor'>".$element['refund_date']."</td>
						<td align='left' style='background: $bgColor'>".$element['product_name']."</td>
						<td align='center' style='background: $bgColor'>".$element['earning_type']."</td>
						<td align='right' style='background: $bgColor'>".$element['amount_earned']."</td>
					  </tr>";
		}
		
		if($dataFound == false) {
			$html .= "<tr><td colspan='6'>".MSG_SORRY_NO_DATA_FOUND."</td></tr>";
		}
		
		$html .= "</table>";
		return $html;
	}

	function loadAffiliatePaymentsDisplayHTML($email, $start_date, $end_date) {
		$result = Dap_AffCommissions::loadAffiliatePayments($email, $start_date, $end_date);
		$dataFound = false;
		//logToFile(sizeof($result),LOG_DEBUG_DAP);
		$html = '<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0">
				  <tr class="scriptsubheading">
					<td>' . COLNAME_AFFID_TEXT . '</td>
					<td>' . COLNAME_NAME_TEXT . '</td>
					<td>' . COLNAME_EMAIL_TEXT . '</td>
					<td>' . COLNAME_DATETIME_TEXT . '</td>
					<td>' . COLNAME_AMTPAID_TEXT . ' ('.Dap_Config::get("CURRENCY_SYMBOL").')</td>
					<td>' . COLNAME_EARNINGTYPE_TEXT . '</td>
				  </tr>
				';
		foreach($result as $element) {
			$dataFound = true;
			$bgcolor = "#FFFFFF";
			if( $element['earning_type'] == "CREDITS") $bgcolor = "#D9D9F9";
			
			$html .= "<tr class='bodytextArial' bgcolor='$bgcolor'>
						<td><a href='/dap/admin/addEditUsers.php?userId=".$element['affiliate_id']."'>".$element['affiliate_id']."</a> </td>
						<td>".$element['first_name']." ".$element['last_name']." </td>
						<td>".$element['email']." </td>
						<td>".$element['datetime']." </td>
						<td align='right'>".$element['amount_paid']." </td>
						<td>".strtolower($element['earning_type'])." </td>
					  </tr>";
		}
		
		if($dataFound == false) {
			$html .= "<tr><td colspan='5'>".MSG_SORRY_NO_DATA_FOUND."</td></tr>";
		}
		$html .= "</table>";
		//logToFile($html,LOG_DEBUG_DAP);
		return $html;
	}

	function loadAffiliatePaymentsForAffiliateDisplayHTML($email, $start_date, $end_date) {
		$result = Dap_AffCommissions::loadAffiliatePayments($email, $start_date, $end_date);
		$dataFound = false;
		//logToFile(sizeof($result),LOG_DEBUG_DAP);
		$html = '<table class="dap_affiliate_details_table">
				  <tr class="regulartextLargeBold">
					<td>'.COLNAME_DATETIME_TEXT.'</td>
					<td>'.COLNAME_AMTPAID_TEXT.' ('.Dap_Config::get("CURRENCY_SYMBOL").')</td>
				  </tr>
				';
		foreach($result as $element) {
			$dataFound = true;
			$html .= "<tr class='regulartext'>
						<td nowrap align='left'>".$element['datetime']."</td>
						<td align='right'>".$element['amount_paid']."</td>
					  </tr>";
		}
		
		if($dataFound == false) {
			$html .= "<tr><td colspan='2'>".MSG_SORRY_NO_DATA_FOUND."</td></tr>";
		}
		$html .= "</table>";
		//logToFile($html,LOG_DEBUG_DAP);
		return $html;
	}

	function loadPaymentsDueDisplayHTML($email, $start_date, $end_date) {
		$html = "<p><strong><div align='center' class='bodytextLarge'>Sorry, no payments due at this time.</div></strong></p>";
		$result = Dap_AffCommissions::loadPaymentsDue($email, $start_date, $end_date);
		if(!empty($result)) {
			$html = "<br/><p><strong><div align='center' class='bodytextLarge'>[NOTE: Credits will be paid out (credited) within the next hour to their respective accounts <br/>once you click on the 'Export These Affiliates For Payment' button below.<br/>But Cash payments need an additional few steps.]</div></strong></p><br/><br/>";
			$html .= '<p align="center"><input name="button_export" type="button" id="button_export" value="Export These Affiliates For Payment" onClick="exportAffiliatesForPayment();"</p>';
			$html .= '<br/><table width="800" border="1" align="center" cellpadding="5" cellspacing="0" class="bodytextArial">
					  <tr class="scriptsubheading">
						<td>Affiliate Id</td>
						<td>Name</td>
						<td>Email</td>
						<td>Amount Due (Cash)</td>
						<td>Amount Due (Credits)</td>
					  </tr>
					';
			//logToFile(sizeof($result),LOG_DEBUG_DAP);
			foreach($result as $element) {
				$amtDue = 0;
				$amt_earned_cash = isset($element['amt_earned_cash']) ? $element['amt_earned_cash'] : 0;
				$amt_earned_credits = isset($element['amt_earned_credits']) ? $element['amt_earned_credits'] : 0;
				
				$html .= "<tr>
						<td><a href='/dap/admin/addEditUsers.php?userId=".$element['affiliate_id']."'>".$element['affiliate_id']."</a></td>
						<td>".$element['first_name'].", ".$element['last_name']."</td>
						<td>".$element['email']."</td>
						<td align='right'>".$amt_earned_cash."</td>
						<td align='right'>".$amt_earned_credits."</td>
						</tr>";
			}
			
			$html .= "</table>";
		}
		return $html;
	}
	
	function loadAffiliateStatsForAffiliateDisplayHTML($email, $start_date, $end_date) {
		$result = Dap_AffCommissions::loadAffiliateStats($email, $start_date, $end_date);
		$dataFound = false;
		//logToFile(sizeof($result),LOG_DEBUG_DAP);
		$html = '<table class="dap_affiliate_details_table">
				  <tr class="regulartextLargeBold">
					<td>'.COLNAME_HTTPREFERER_TEXT.'</td>
					<td>'.COLNAME_DESTINATION_TEXT.'</td>
					<td>'.COLNAME_DATETIME_TEXT.'</td>
				  </tr>
				';
		foreach($result as $element) {
			$dataFound = true;
			$html .= "<tr class='regulartext' valign='top'>
						<td align='left'><a href='".$element['http_referer']."' target='_blank' title=".$element['http_referer'].">".trimString($element['http_referer'],80,45,30)."</a></td>
						<td align='left'><a href='".$element['dest_url']."' target='_blank' title=".$element['dest_url'].">".trimString($element['dest_url'],80,45,30)."</a></td>
						<td nowrap align='left'>".$element['datetime']." </td>
					  </tr>";
		}
		
		if($dataFound == false) {
			$html .= "<tr><td colspan='3'>".MSG_SORRY_NO_DATA_FOUND."</td></tr>";
		}
		$html .= "</table>";
		//logToFile($html,LOG_DEBUG_DAP);
		return $html;
	}
	
	function loadDapMassActionsDisplayHTML($massActionsDataArray) {
		$massActionsHTML = '<table id="massActionsTable" width="100%"  border="0" cellspacing="5" cellpadding="5">';
		$massActionsHTML .= '<tr class="scriptsubheading" bgcolor="#EFEFEF" >
		<td>Id</td>
		<td>Type</td>
		<td>Key</td>
		<td>Payload</td>
		<td>Time</td>
		<td>Status</td>
		<td>Comments</td>
		<td>Delete</td>
		';
		
		foreach ($massActionsDataArray as $massAction) {
			$massActionsHTML .= 
			"<tr align=\"left\" class='bodytextArial'>
				<td>" . mb_convert_encoding($massAction["id"], "UTF-8", "auto") . "</td>
				<td>" . mb_convert_encoding($massAction["actionType"], "UTF-8", "auto") . " </td>
				<td>" . mb_convert_encoding($massAction["actionKey"], "UTF-8", "auto") . " </td>
				<td>" . mb_convert_encoding($massAction["payload"], "UTF-8", "auto") . " </td>
				<td>" . mb_convert_encoding($massAction["last_update_ts"], "UTF-8", "auto") . " </td>
				<td>" . mb_convert_encoding($massAction["status"], "UTF-8", "auto") . " </td>
				<td>" . mb_convert_encoding($massAction["comments"], "UTF-8", "auto") . " </td>
				<td><a href='#' onclick=\"javascript:deleteJobFromJobQueue('".$massAction["id"]."'); return false;\"><img src='/dap/images/ximage.jpg' width='13' border='0' height='13'></a></td>
			</tr>";
		}
		
		$massActionsHTML .= '</table>';
		//logToFile($massActionsHTML,LOG_DEBUG_DAP);
		return $massActionsHTML;
	}




	function loadDapMassActionsDisplayPaginationHTML($massActionsDataArray) {
		
		
		$massActionsHTML = '<table id="massActionsTable" width="100%"  border="0" cellspacing="5" cellpadding="5">';
		$massActionsHTML .= '<tr class="scriptsubheading" bgcolor="#EFEFEF" >
		<td>Id</td>
		<td>Type</td>
		<td>Key</td>
		<td>Payload</td>
		<td>Time</td>
		<td>Status</td>
		<td>Comments</td>
		<td>Delete</td>
		';
		
		foreach ($massActionsDataArray as $massAction) {
			$massActionsHTML .= 
			"<tr align=\"left\" class='bodytextArial'>
				<td>" . mb_convert_encoding($massAction["id"], "UTF-8", "auto") . "</td>
				<td>" . mb_convert_encoding($massAction["actionType"], "UTF-8", "auto") . " </td>
				<td>" . mb_convert_encoding($massAction["actionKey"], "UTF-8", "auto") . " </td>
				<td>" . mb_convert_encoding($massAction["payload"], "UTF-8", "auto") . " </td>
				<td>" . mb_convert_encoding($massAction["last_update_ts"], "UTF-8", "auto") . " </td>
				<td>" . mb_convert_encoding($massAction["status"], "UTF-8", "auto") . " </td>
				<td>" . mb_convert_encoding($massAction["comments"], "UTF-8", "auto") . " </td>
				<td><a href='#' onclick=\"javascript:deleteJobFromJobQueue('".$massAction["id"]."'); return false;\"><img src='/dap/images/ximage.jpg' width='13' border='0' height='13'></a></td>
			</tr>";
		}
		
		$massActionsHTML .= '</table><br>';
		//logToFile($massActionsHTML,LOG_DEBUG_DAP);
		
		return $massActionsHTML;
	}
	
	
	function isVersionDifferent() {
		Dap_Config::loadConfig(true);
		$dap_version = Dap_Config::get("DAP_VERSION");
		$dap_db_version = Dap_Config::get("DAP_VERSION_CLIENT");
		if($dap_version == $dap_db_version) return FALSE;
		return TRUE;
	}
	
	function dbUpdate($doit) {
		global $versions;
		$dap_version = Dap_Config::get("DAP_VERSION");
		$dap_db_version = Dap_Config::get("DAP_VERSION_CLIENT");
		echo "Currently installed DAP version: $dap_db_version <br>";
		echo "Version of DAP about to be installed: $dap_version <br>";
		//echo "Number of updates to perform: $uptotal ";
		//echo "All Avilable Versions:";
		//print_r($versions);
		//		echo "<br>";
		//echo $versions[$dap_db_version] . "<br>";

		foreach ($versions as $version => $upfile) {
			//if($counter > 0 && $upcount < $uptotal) {
			if($counter > 0 ) {
				$update_avail = TRUE;
				if($doit == TRUE) {
					include $upfile;
					echo "Including Update: $upfile <br>\n";
				} else {
					echo "Available Update: $upfile <br>\n";
				}
				$uptotal++;
				$counter++;
			}
			//lets start counter as soon as we hit versions array where current installed version matches.
			// we need to start updates from next element in the versions array after this.
			if($version == $dap_db_version) $counter = 1;
			//lets exit as soon as we hit the to_be_installed version in the versions array. 
			// this is handy if versions array has more elements after the to_be_installed version.
			if($version == $dap_version) break; 
		}
		if($doit == TRUE && $update_avail == TRUE) {
			$dap_dbh = Dap_Connection::getConnection();

			//Create DB Tables
			foreach($sql_ddl as $sql) {
				try {
					logToFile($sql,LOG_DEBUG_DAP);
					$stmt = $dap_dbh->prepare($sql);
					$stmt->execute();
					logToFile("Number of Rows Affected:".$stmt->rowCount());
				} catch (PDOException $e) {
					$response .= $e->getMessage() . "<br/>\n";
					//$response .= ERROR_GENERAL;
				} catch (Exception $e) {
					$response .= $e->getMessage() . "<br/>\n";
				}
			}

			if($response == "") {
				echo  "<br>SUCCESS! Database Tables have been successfully updated<br/>";
				echo "<br/><a href='../index.php'>Proceed To Admin Panel</a><br/>";
			} else {
				echo "<br>ERROR: Some or more of the updates failed to execute.<br>Please send the following content to DAP Support.";
				echo "<br><textarea rows='20' cols='100'>$response</textarea>";
				echo "<br/><a href='../index.php'>Proceed To Admin Panel</a><br/>";
			}

		} else if ($update_avail == TRUE) {
			echo "<br/><a href='update.php?doit=true'>Proceed With Update</a><br/>";
		}
	}


	function loadSMTPDisplayHTML() { //Load config table with HTML
		$smtpArray = Dap_SMTPServer::loadSMTP();
		$i = 1;
		$html = "<form name='ManageSMTPForm' method='POST' action=''>";
		$html .= "<table width='100%' border='0' align='center' cellpadding='3' cellspacing='0'> ";
		$html .= "<tr><td colspan='11' class='scriptheader'>Manage Existing SMTP Servers</td></tr>";
		$html.= "<tr align='left'>
			<td>Description</td>
			<td>Server</td>
			<td>Port</td>
			<td>SSL</td>
			<td>User Id </td>
			<td>Password</td>
			<td>Email Sending<br/>Limit Per Hour</td>
			<td>Emails sent</td>
			<td>Activated?</td>
			<td>&nbsp;</td>
			<td width='50'>&nbsp;</td>
		  </tr>";
		
		foreach($smtpArray as $row) {
			$showDelete = '<a href="#" onclick="javascript:deleteSMTP('.$row["id"].'); return false;"><img src="/dap/images/ximage.jpg" width="13" border="0" height="13"></a>';
			$disabled = "";
		
			if($row["server"] == "Local_Web_Host") {
				$showDelete = "";
				$disabled = "disabled";
			}
			
			$html.= "
				<tr align='left' class='bodyTextArial'>
				<td valign='top' align='left'><input type='text' name='smtp_".$row["id"]."_description' size='20' maxlength='30' value='".$row["description"]."' $disabled></td>
				<td valign='top' align='left'><input type='text' name='smtp_".$row["id"]."_server' size='30' maxlength='50' value='".$row["server"]."' $disabled></td>
				<td valign='top'><input type='text' name='smtp_".$row["id"]."_port' size='3' maxlength='5' value='".$row["port"]."' $disabled></td>
				<td valign='top'><input type='text' name='smtp_".$row["id"]."_ssl' size='3' maxlength='1' value='".$row["ssl"]."' $disabled></td>
				<td valign='top'><input type='text' name='smtp_".$row["id"]."_userid' size='20' maxlength='50' value='".$row["userid"]."' $disabled></td>
				<td valign='top'><input type='text' name='smtp_".$row["id"]."_password' size='10' maxlength='50' value='".$row["password"]."' $disabled></td>
				<td valign='top'><input type='text' name='smtp_".$row["id"]."_limit_per_hour' size='5' maxlength='10' value='".$row["limit_per_hour"]."'></td>
				<td valign='top'><input type='text' name='smtp_".$row["id"]."_running_total' size='10' maxlength='10' value='".$row["running_total"]."' disabled></td>
				";
				$selectedY = "";
				$selectedN = "";
				if($row["active"] == "Y") {
					$selectedY = "selected ";
				} else if($row["active"] == "N") {
					$selectedN = "selected ";
				}
					
			$html .= "<td>
				<select name='smtp_".$row["id"]."_active'>
				<option value='Y' $selectedY>Y</option>
				<option value='N' $selectedN>N</option>
				</select>
				</td>";
			$html .= "<td><input type='button' value='Update' name='button_updateSmtp".$i."' onClick=\"modButton(this); updateSMTP(this.form, ".$row["id"].");\"></td>";
			
			$html .= "<td width='50' valign='bottom'>$showDelete</td></tr>";
					
			$i++;
		}
		$html .= "</table></form><br/><br/>";
		
		//Add a new row for adding 
		$html .= "<form name='AddSMTPForm' method='' action='' onSubmit='doSubmit(this); return false;'> 
					<table width='100%' border='0' align='center' cellpadding='5' cellspacing='0'>";
		$html .= "<tr><td colspan='11' class='scriptheader'>Add a New SMTP Server</td></tr>";
		$html.= "<tr align='left'>
			<td>Description</td>
			<td>Server</td>
			<td>Port</td>
			<td>SSL</td>
			<td>User Id </td>
			<td>Password</td>
			<td>Email Sending<br/>Limit Per Hour</td>
			<td>Emails sent</td>
			<td>Activated?</td>
			<td>&nbsp;</td>
			<td width='50'>&nbsp;</td>
		  </tr>";
		$html .= '<tr class="bodyTextArial" align="left">
			<td valign="top"><input name="smtp_0_description" size="20" maxlength="30" value="" type="text"></td>
			<td valign="top"><input name="smtp_0_server" size="30" maxlength="50" value="" type="text"></td>
			<td valign="top"><input name="smtp_0_port" size="3" maxlength="5" value="25" type="text"></td>
			<td valign="top"><input name="smtp_0_ssl" size="3" maxlength="1" value="N" type="text"></td>
			<td valign="top"><input name="smtp_0_userid" size="20" maxlength="50" value="" type="text"></td>
			<td valign="top"><input name="smtp_0_password" size="10" maxlength="50" value="" type="text"></td>
			<td valign="top"><input name="smtp_0_limit_per_hour" size="5" maxlength="10" value="180" type="text"></td>
			<td valign="top"><input name="smtp_0_running_total" size="10" maxlength="10" value="0" type="text" disabled></td>
			<td valign="top">
				<select name="smtp_0_active">
				<option value="Y" selected="selected">Y</option>
				<option value="N">N</option></select>
			</td>
			<td><input type="button" value="Add" name="button_updateSmtp_Add" onClick="modButton(this); createSMTP(this.form, 0);"></td>
			<td width="50">&nbsp;</td>
			';
		$html .= '</tr></table>
		<input type="hidden" name="id" value="0">
		</form>';
		//logToFile("$html",LOG_DEBUG_DAP);
		return $html;
	}
	
	function getAffPaymentsEndDate() {
		$refund_period = Dap_Config::get('REFUND_PERIOD');
		$time_period = mktime(0, 0, 0, date("m"), date("d")-$refund_period-1, date("y"));
		$end_date = date("m-d-Y", $time_period); 
		return $end_date;
	}

	function loadAffiliateExportDisplayHTML($export_id) {
		$export = Dap_AffCommissions::loadAffiliateExportForDisplay($export_id);
		$data = "";
		foreach ($export as $row) {
			$affiliate_id = $row['affiliate_id'];
			logToFile("Aff id: $affiliate_id, amt_earned: " . $row['amt_earned'],LOG_DEBUG_DAP);
			$amt_earned = isset($row['amt_earned']) ? $row['amt_earned'] : 0;
			$data .= $row['email']."\t".$amt_earned ."\t" . Dap_Config::get('CURRENCY_TEXT') . "\r\n";
		}
		
		return $data;
	}
	
	function getCss() {
		if(file_exists(DAP_ROOT . "inc/content/" . CUSTOM_CSS)) {
			echo '<link href="/dap/inc/content/'.CUSTOM_CSS.'" rel="stylesheet" type="text/css">';
		} else {
			echo '<link href="/dap/inc/content/userfacing.css" rel="stylesheet" type="text/css">';
		}
	}


	function getCssRelativePath() {
		if(file_exists(DAP_ROOT . "inc/content/" . CUSTOM_CSS)) {
			return DAP_ROOT . "inc/content/" . CUSTOM_CSS;
		} else {
			return DAP_ROOT . "inc/content/userfacing.css";
		}
	}
	
	
	function getCssFullURL() {
		if(file_exists(DAP_ROOT . "inc/content/" . CUSTOM_CSS)) {
			return rtrim("/",SITE_URL_DAP)."/dap/inc/content/" . CUSTOM_CSS;
		} else {
			return rtrim("/",SITE_URL_DAP)."/dap/inc/content/userfacing.css";
		}
	}	

	
	function getCheckoutCss() {
		if(file_exists(DAP_ROOT . "inc/content/" . CUSTOM_CHECKOUT_CSS)) {
			echo '<link href="/dap/inc/content/'.CUSTOM_CHECKOUT_CSS.'" rel="stylesheet" type="text/css">';
		} else {
			echo '<link href="/dap/inc/content/checkoutconfirm.css" rel="stylesheet" type="text/css">';
		}
	}
	
	function getCartCss() {
		if(file_exists(DAP_ROOT . "inc/content/" . CUSTOM_CART_CSS)) {
			echo '<link href="/dap/inc/content/'.CUSTOM_CART_CSS.'" rel="stylesheet" type="text/css">';
		} else {
			echo '<link href="/dap/inc/content/dapcart.css" rel="stylesheet" type="text/css">';
		}
	}
	
	function getSelfServiceCss() {
		if(file_exists(DAP_ROOT . "inc/content/" . CUSTOM_SSS_CSS)) {
			echo '<link href="/dap/inc/content/'.CUSTOM_SSS_CSS.'" rel="stylesheet" type="text/css">';
		} else {
			echo '<link href="/dap/inc/content/selfservice.css" rel="stylesheet" type="text/css">';
		}
	}

	function exportUsersDisplayHTML($group,$userstatus,$productstatus,$access,$optin,$display) {
		$result = Dap_User::exportUsers($group,$userstatus,$productstatus,$access,$optin,$display);
		$dataFound = false;
		$html = "Number of Users Found: " . count($result) . "\n\n\n";
		
		if($display == "U") {
			//$html .= "User Id,,Email\n";
			$html .= "UserId,Email,FirstName,LastName\n";
			foreach($result as $element) {
				$dataFound = true;
				//$html .= $element['id'].",".$element['first_name'].",".$element['last_name'].",".$element['email']."\n";
				$html .= $element['id'].",".$element['email'].",".$element['first_name'].",".$element['last_name']."\n";
			}
		} else {
			$html .= "user_id,first_name,last_name,user_name,email,password,city,state,zip,country,phone,fax,company,title,is_affiliate,last_login_date,activation_key,status,login_count,ipaddress,account_type,signup_date,paypal_email,last_update_date,aff_nick,opted_out,self_service_status,credits_available,product_id,product_name,access_start_date,access_end_date,transaction_id,user_product_status\n";
			
			foreach($result as $element) {
				$dataFound = true;
				//logToFile(count($element)); 
				foreach($element as $col) {
					//logToFile($col); 
					$html .= ($col . ",");
				}
				$html .= "\n";
				//logToFile($html); 
			}
		}
	
		if($dataFound == false) {
			$html .= "Sorry, no data found.";
		}
		//$html .= "</textarea></form></div>";
		return $html;
	}

	
	function loadPublisherEarningsSummaryDisplayHTML($start_date, $end_date, $merchant_commissions, $publisher_commissions) {
		$result = Dap_AffCommissions::loadPublisherEarningsSummary($start_date, $end_date);
		logToFile("$start_date, $end_date, $merchant_commissions, $publisher_commissions"); 
		$dataFound = false;
		$html = '<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0" class="bodytextArial">
				  <tr class="scriptsubheading" valign="top">
					<td>Product Id</td>
					<td>Product Name</td>
					<td>Product Description</td>
					<td>Total Sales</td>
					<td>Merchant Earnings ('.Dap_Config::get("CURRENCY_SYMBOL").')</td>
					<td>Publisher Earnings ('.Dap_Config::get("CURRENCY_SYMBOL").')</td>
					<td>Publisher Email</td>
				  </tr>
				';
		foreach($result as $element) {
			$dataFound = true;
			$html .= "<tr>
						<td><a href='/dap/admin/addEditProducts.php.php?productId=".$element['id']."'>".$element['id']."</a></td>
						<td>".$element['name']."</td>
						<td nowrap>".substr($element['description'],0,50)."...</td>
						<td align='right'>".$element['product_total_sales']."</td>
						<td align='right'>".number_format(($merchant_commissions * $element['product_total_sales'] / 100), 2, '.', '')."</td>
						<td align='right'>".number_format(($publisher_commissions * $element['product_total_sales'] / 100), 2, '.', '')."</td>
						<td>".$element['thirdPartyEmailIds']."</td>
					  </tr>";
		}
		
		if($dataFound == false) {
			$html .= "<tr><td colspan='5'>Sorry, no data found.</td></tr>";
		}
		
		$html .= "</table>";
		return $html;
	}


	function exportPublishersForPaymentDisplayHTML($start_date, $end_date, $publisher_commissions) {
		$result = Dap_AffCommissions::loadPublisherEarningsSummary($start_date, $end_date);
		
		logToFile("$start_date, $end_date, $publisher_commissions"); 
		
		$data = "";
		foreach($result as $element) {
			logToFile(Dap_Config::get('CURRENCY_TEXT')); 
			$amt_earned = number_format( ($publisher_commissions * $element['product_total_sales'] / 100) );
			$data .= $element['thirdPartyEmailIds']."\t".$amt_earned ."\t" . Dap_Config::get('CURRENCY_TEXT') . "\r\n";
		}
		
		return $data;
	}
	
	
	function loadUserLogin404IssueReportHTML($emailId, $wpFolderName) {
		
		
		logToFile("functions_admin: loadUserLogin404IssueReportHTML(): emailid=$emailId: ENTER"); 
		
		$user = Dap_User::loadUserByEmail($emailId);
		$html="";
		
		if(!isset($user)) {
			$html.='<p style="font-size:12px;">
				  <img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/cross-icon.png" />
				  Sorry, could not find the user in DAP users->manage page. Cannot proceed until this issue is addressed. <br><br>
			  </p>';	
			$html.='</blockquote></div>';
		
			return $html; 
		}
		
		$count=0;
		$ustatus=$user->getStatus();
		$uid=$user->getId();
		
		$redirectURL = Dap_UsersProducts::getLoggedInURL($user);
		logToFile("functions_admin: loadUserLogin404IssueReportHTML() redirectURL: " . $redirectURL); 
		
		$html="";
		if(strstr($redirectURL,"php")!=false) {
			$result=URLCheck ($redirectURL);
			if($result=="") {
				logToFile("functions_admin: loadUserLogin404IssueReportHTML() non-WP, called URLCheck() but no such URL found"); 
				
				$html.="<p style='font-size:12px;'>
				  <img  class='img_set' src='" . SITE_URL_DAP . "/dap/images/cross-icon.png' />
				  Based on your 'post-login-redirect' setting, this user will land on " . $redirectURL . " page upon login. But this page/post does NOT exist. It's not a valid URL. <br><br> Please make sure to set the 'post-login' redirect URL in DAP setup->config section as well as in DAP Products/Levels page to URL that exists to prevent a 404 error.<br><br>
			  </p>";
				return $html;
			}
		}
		else {
			logToFile("functions_admin: loadUserLogin404IssueReportHTML() WP call verify_WP_URL()"); 
			$result="";
			$return=verify_WP_url ($redirectURL,$wpFolderName,$result);
			logToFile("functions_admin: loadUserLogin404IssueReportHTML() WP called verify_WP_URL(): result=".$result); 
			if($return!=0) {
				logToFile("functions_admin: loadUserLogin404IssueReportHTML() WP called verify_WP_URL() but no such URL found"); 
				
				if($result=="") {
				$html.="<p style='font-size:12px;'>
				  <img  class='img_set' src='" . SITE_URL_DAP . "/dap/images/cross-icon.png' />
				  Based on your 'post-login-redirect' setting, this user will land on " . $redirectURL . " page upon login. But this page/post does NOT exist in Wordpress. It's not a valid URL. <br><br>Please make sure to create a WP page/post and use the permalink of that page/post as the 'post-login' redirect URL to prevent a 404 error. <br><br>
			  </p>";	
				}
				else {
					$html.="<p style='font-size:12px;'>
				  <img  class='img_set' src='" . SITE_URL_DAP . "/dap/images/cross-icon.png' />
				  Based on your 'post-login-redirect' setting, this user will land on " . $redirectURL . " page upon login.<<br><br>" . $result . "<br><br>
			  </p>";
					
				}
				return $html;
			}
		}
		
		$html.='<p style="font-size:12px;">
			  <img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />
			  No issues found. This user is expected to land on ' . $redirectURL . ' page upon login. And this URL appears to be a valid URL. Sorry, could not find cause of 404 error</p>';
		
		logToFile("functions_admin: loadUserLogin404IssueReportHTML(): EXIT"); 
		
		return $html;
		
	//6 login redirect
	}
	
	function validateConfigURLHTML($wpFolderName) {
		logToFile("functions_admin: validateConfigURLHTML(): wpFolderName=$wpFolderName: ENTER"); 
		
		$url = Dap_Config::get("LOGIN_URL");
		if($url=="") {
			$errmsg="You have not set the LOGIN url in DAP Setup=>Config=>Access & Navigation Section=>Login URL section.";
			$response.="<p style='font-size:14px;'>
				  <img  class='img_set' src='" . SITE_URL_DAP . "/dap/images/cross-icon.png' />".$errmsg."
			  </p>";
		}
		else {
			logToFile("functions_admin: validateConfigURLHTML() loginURL: " . $url); 
			$fullerrmsg =  "You have set the DAP Setup=>Config=>Access & Navigation Section=>Login URL to " . $url . ". But there is no such page. It's not a valid URL. <br><br> Please make sure to set the 'LOGIN' URL in DAP setup->config section as well as in DAP Products/Levels page to a URL that exists to prevent a 404 error.<br><br>
				Please see <a target='_blank' href='http://DigitalAccessPass.com/documentation/?page=/doc/creating-a-login-page-within-wordpress'/>this document</a> on how to create a Login Page in WP.";
			$partialerrmsg="You have set the DAP Setup=>Config=>Access & Navigation Section=>Login URL to " . $url . ".<br><br>";
			$goodmsg="Your Login URL looks good. It is set to " . $url . ". It appears to be a valid URL.";
			$custommsg = "Please see <a target='_blank' href='http://DigitalAccessPass.com/documentation/?page=/doc/creating-a-login-page-within-wordpress'/>this document</a> on how to create a Login Page in WP.";
			$response.=validateURLHTML($wpFolderName, $url, $fullerrmsg, $partialerrmsg, $goodmsg, $custommsg,$customwpmsg);
		}
		
		$url = Dap_Config::get("LOGGED_IN_URL");
		if($url=="") {
			$errmsg="You have not set the POST-LOGIN REDIRECT URLin DAP Setup=>Config=>Access & Navigation Section=>Post-Login URL (Global) section.";
			$response.="<p style='font-size:14px;'>
				  <img  class='img_set' src='" . SITE_URL_DAP . "/dap/images/cross-icon.png' />".$errmsg."
			  </p>";
			
		}
		else {
			logToFile("functions_admin: validateConfigURLHTML() loggedinurl: " . $url); 
			$fullerrmsg =  "You have set the DAP Setup=>Config=>Access & Navigation Section=>Post-Login URL (Global) to " . $url . ". But there is no such page. It's not a valid URL. <br><br> Please make sure to set the 'Post-Login URL' in DAP setup->config section as well as in DAP Products/Levels => Post-Login URL to a valid URL to prevent a 404 error.<br><br>
			Please See <a target='_blank' href='http://www.digitalaccesspass.com/doc/login-redirect-issues/'/>this document</a> on how login redirect works.";
			$partialerrmsg="You have set the DAP Setup=>Config=>Access & Navigation Section=>Post-Login URL (Global) to " . $url . ".<br><br>";
			$goodmsg="Your Post-Login URL (Global) looks good. It is set to " . $url . ". It appears to be a valid URL.";
			$custommsg = "Please See <a target='_blank' href=http://www.digitalaccesspass.com/doc/login-redirect-issues/'/>this document</a> on how login redirect works";
			$customwpmsg=$custommsg;
			$response.=validateURLHTML($wpFolderName, $url, $fullerrmsg, $partialerrmsg, $goodmsg, $custommsg,$customwpmsg);
		}
		
		$url = Dap_Config::get("SALES_PAGE_URL");
		if($url=="") {
			$errmsg="You have not set the SALES PAGE URL in DAP Setup=>Config=>Access & Navigation Section=>Sales Page URL Section.";
			$response.="<p style='font-size:14px;'>
				  <img  class='img_set' src='" . SITE_URL_DAP . "/dap/images/cross-icon.png' />".$errmsg."
			  </p>";
		}
		else {		
			logToFile("functions_admin: validateConfigURLHTML() sales page url: " . $url); 
			$fullerrmsg =  "You have set the DAP Setup=>Config=>Access & Navigation Section=>Sales Page URL (Global) to " . $url . ". But there is no such page. It's not a valid URL. <br><br> Please make sure to set the 'Sales Page URL (Global)' in DAP setup->config section as well as in DAP Products/Levels => Sales Page URL to a valid URL to prevent a 404 error.<br><br>
			DAP uses the sales page url on the 'error page' that is shown to the user when they access content that they are not eligible for.<br><br>
			Please see <a target='_blank' href='http://www.digitalaccesspass.com/doc/customizing-error-messages-on-protected-pages/'/>this document</a> on how sales page url is used.";
			$partialerrmsg="You have set the DAP Setup=>Config=>Access & Navigation Section=>Post-Login URL (Global) to " . $url . ".<br><br>";
			$goodmsg="Your Sales Page URL looks good. It is set to " . $url . ". It appears to be a valid URL.";
			$custommsg = "Please see <a target='_blank' href='http://www.digitalaccesspass.com/doc/customizing-error-messages-on-protected-pages/'/>this document</a> on how sales page url is used.";
			$customwpmsg=$custommsg;
			$response.=validateURLHTML($wpFolderName, $url, $fullerrmsg, $partialerrmsg, $goodmsg, $custommsg,$customwpmsg);
		}
		return $response;
	}
										
	function validateURLHTML($wpFolderName, $checkthisurl, $fullerrmsg, $partialerrmsg, $goodmsg, $custommsg,$customwpmsg){
		logToFile("functions_admin: validateURLHTML(): wpFolderName=$wpFolderName, url=$checkthisurl: ENTER"); 
		
		$html="";
		$phpurl=false;
		if(strstr($checkthisurl,"php")!=false) {
			$result=URLCheck ($checkthisurl);
			$phpurl=true;
			if($result=="") {
				logToFile("functions_admin: validateURLHTML() non-WP, called URLCheck() but no such URL found"); 
				
				$html="<p style='font-size:14px;'>
				  <img  class='img_set' src='" . SITE_URL_DAP . "/dap/images/cross-icon.png' />".$fullerrmsg."
			  </p>";
				return $html;
			}
		}
		else {
			logToFile("functions_admin: validateURLHTML() WP call verify_WP_URL()"); 
			$result="";
			$return=verify_WP_url ($checkthisurl,$wpFolderName,$result);
			logToFile("functions_admin: validateURLHTML() WP called verify_WP_URL(): result=".$result); 
			if($return!=0) {
				logToFile("functions_admin: validateURLHTML() WP called verify_WP_URL() but no such URL found"); 
				
				$html="<p style='font-size:14px;'>
				  <img  class='img_set' src='" . SITE_URL_DAP . "/dap/images/cross-icon.png' />" .
				  $fullerrmsg."</p>";
			  
				return $html;
			}
		}
		
		if($phpurl) {
			if($custommsg!="") {
			  $html='<p style="font-size:14px;">
				<img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />' . $goodmsg . 
				"<br><br>".$custommsg.
			  '</p>';	
			}
			else {
				$html='<p style="font-size:14px;">
				<img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />' . $goodmsg . 
			  '</p>';		
			}
		}
		else {
			if($customwpmsg!="") {
			  $html='<p style="font-size:14px;">
				<img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />' . $goodmsg . 
				"<br><br>".$customwpmsg.
			  '</p>';
			}
			else {
				$html='<p style="font-size:14px;">
				<img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />' . $goodmsg . 
			  '</p>';		
			}
		}
		
		logToFile("functions_admin: validateURLHTML(): EXIT"); 
		
		return $html;
		
	//6 login redirect
	}
	
	
	function URLCheck($redirectURL) {
	
		$handlerr = curl_init($redirectURL);
		curl_setopt($handlerr,  CURLOPT_RETURNTRANSFER, TRUE);
		$resp = curl_exec($handlerr);
		$ht = curl_getinfo($handlerr, CURLINFO_HTTP_CODE);
			
		if ($ht == '404') 
			return "";
		else  
			return $redirectURL;
	
	}

	function verify_WP_url( $post_login_url, $wpFolderName, &$result ) {
		
		$lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
		
		if($wpFolderName=="")
			require_once($lldocroot."/".'wp-config.php');
		else
			require_once($lldocroot."/".$wpFolderName.'/wp-config.php');
		
		logToFile("functions_admin: verify_WP_url(): post_login_url=".$post_login_url); 
		
		if(strstr($post_login_url,"http") == false) {
			logToFile("functions_admin: verify_WP_url(): no http"); 
			if(strstr($post_login_url,"www") == false) {
				logToFile("functions_admin: verify_WP_url(): no www"); 
				if($post_login_url[0]!="/") {
					logToFile("functions_admin: verify_WP_url():first char missing /"); 
					$result= "You have set the post-login-url to relative path but missed a '/' in front of the relative path. If your url is http://yoursite.com/hello, then the relative path should be /hello. It cannot be 'hello' without the leading /. <br><br>Please check the post-login-redirect URL in dap setup -> config and in dap products/levels page and make sure to either use the FULL URL (starting with http) or if you use the relative path, make sure it's set correctly";			
					return -1; //failed
				}
				
				$post_login_url=SITE_URL_DAP.$post_login_url;
				logToFile("functions_admin: verify_WP_url():relative path. new url=".$post_login_url); 
			}
			else {
				logToFile("functions_admin: verify_WP_url():found www, new url=".$post_login_url); 
			}
		}
		
    /* Make sure the visitor actually filled up the URL field, and that we're not on the dashboard. */
		logToFile("functions_admin: verify_WP_url():wp_remote_head(): url=".$post_login_url); 
	
        $response = wp_remote_head( $post_login_url, array( 'timeout' => 5 ) );
        /* We'll match these status codes against the HTTP response. */
        $accepted_status_codes = array( 200, 301, 302 );
 
        /* If no error occured and the status code matches one of the above, go on... */
        if ( ! is_wp_error( $response ) && in_array( wp_remote_retrieve_response_code( $response ), $accepted_status_codes ) ) {
            /* Target URL exists. Let's return the (working) URL */
			logToFile("functions_admin: verify_WP_url():valid url"); 
			$result="SUCCESS"; //success
            return 0;
        }
        /* If we have reached this point, it means that either the HEAD request didn't work or that the URL
         * doesn't exist. This is a fallback so we don't show the malformed URL */
		logToFile("functions_admin: verify_WP_url():not a valid url"); 
		$result=""; //failed
        return -1;
	 
	 }
	
	function loadUserLoginRedirectIssueReportHTML($emailId, $blogpath) {
		$user = Dap_User::loadUserByEmail($emailId);
		$html="";
		if(!isset($user)) {
			$html.='<p style="font-size:12px;">
				  <img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/cross-icon.png" />
				  Sorry, could not find the user in DAP users->manage page. Cannot proceed until this issue is addressed. <br><br>
			  </p>';	
			$html.='</blockquote></div>';
		
			return $html; 
		}
		
		$status=$user->getStatus();			
		if($status=="A") {
			$html.='<p style="font-size:12px;">
			  <img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />
			  This user\'s account status is ACTIVE (=A).
		  </p>';	
		}
		else {
			$html.='<p style="font-size:12px;">
			  <img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/cross-icon.png" />
			  This user\'s status is set to ' . $status . '. It needs to be activated to allow user login.
			 <strong> <p style="color:red;font-size:13px;">Click on the user status (hyperlink) in the table below </a> to activate user status.</p></strong>
		  </p>';	
		}
		
		$count=0;
		$ustatus=$user->getStatus();
		$uid=$user->getId();
		$productsFound=false;
		
		$count=getUserDetails($html,$productId,$user,$emailId,$productsFound);
		
		return $html;
		
	//6 login redirect
	}

	function loadUserLoginIssueReportHTML($emailId, $blogpath) {
	
		//1 check if cache plugin
		//2 session save path
		//3 register globals
		//4 user status
		//5 List of products user has and the status
		//6 login redirect
		//7 protected? if yes- does user have access
		//8 No issues found... 
		//In a different browser login as the user and see if you find any issues
		
	
		$html .= '<div><blockquote><li style="font-size:14px;"><strong>CACHE Plugin Check</strong></li>';
		$wpfile=$blogpath . "wp-config.php";
		
		if (file_exists($wpfile)) {
			$config = file_get_contents ($blogpath . "wp-config.php");
		}
		
		if( isset($config) && ($config !="") ) {
		  $result = preg_match('/define.(.*)_CACHE(.*)\'/', $config, $m);
		  
		  if($result) {
			  logToFile("functions_admin.php: loadUserLoginIssueReportHTML: cache plugin found" ); 
			  $html .= '<p style="color:red;font-size:12px;"><img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/cross-icon.png" />
			  It appears that you have a CACHE plugin (e.g. WP Super Cache / Total Cache) active. <br>
			  If cache plugin is active, it can cause unexpected login issues (if not used correctly). <br><br>
			  Please follow these steps to deactivate the cache plugin fully and see if it resolves the issue. <br><br>
			  1) De-activate the cache plugin<br>
			  2) Open your wp-config\.php file. If there are lines in there that look like this: <br><br>
			  define("WP_CACHE", true); <br>
			  define( "WPCACHEHOME", "/home/xyz/public_html/yoursite.com/wp-content/plugins/wp-super-cache/" );<br><br>
			  Remove these lines and save wp-config.php. <br><br>
			 
			  Please <a target="_blank" href="http://digitalaccesspass.com/doc/cache-plugin-setup/">read this post</a> on how to use cache plugin for membership sites.
			  </p>';
			  logToFile("functions_admin.php: loadUserLoginIssueReportHTML(): html=".$html ); 
		  }
		  else {
			  logToFile("functions_admin.php: loadUserLoginIssueReportHTML: no cache plugin found" ); 
			  $html.='<p style="font-size:13px;">
				  <img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />
				  No cache related issues found!. 
			  </p>';
		  }
		}
		/*else {
			 $html.='<p style="font-size:13px;">
				  <img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />
				  Could not find wp-config\.php file. 
			  </p>';
		}*/
		
		$filename=$blogpath . "/wp-content/cache";
		if (file_exists($filename)) {
			$html .= '<p style="color:red;font-size:13px;"><img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/cross-icon.png" />
			  It appears that you have a "cache" folder under /wp-content. <br><br>
			  Please ftp to your site and go to your wp-content folder. <br>
			  See if there is a folder in there called "cache". If yes, then rename it to "_cache".<br><br>
			  Please <a target="_blank" href="http://digitalaccesspass.com/doc/cache-plugin-setup/">read this post</a> on how to use cache plugin for membership sites.
			</p>';
		}
		
		
		$html .= '<br><li style="font-size:14px;"><strong>SESSION CHECK</strong></li>';
		
		if( ( session_save_path() == '' ) || (session_save_path() == "no value") ) {
			$html .= '<p style="color:red;font-size:13px;"><img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/cross-icon.png" /><a target="_blank" href="' . SITE_URL_DAP . '/dap/phpinfo.php">Visit this page </a>. Look for session.save_path. You will notice that session.save_path is set to "no value". This can cause login and several other issues. Please talk to your webhost to have it set to a valid folder like /tmp.</p>';
		}
		else {
			$html.='<p style="font-size:13px;">
				  <img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />
				 session.save_path is set correctly.
			  </p>';
		}
		
		$html .= '<br><li style="font-size:14px;"><strong>REGISTER GLOBAL CHECK</strong></li>';
		
		if( ini_get('register_globals') != 0) {
			$html .= '<p style="color:red;font-size:13px;"><img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/cross-icon.png" /><a target="_blank" href="' . SITE_URL_DAP . '/dap/phpinfo.php">Visit this page </a>. Look for register_globals. You will notice that register_globals is set to "On". This can cause login and security issues. Please talk to your webhost to have it set to Off.</p>';
		}
		else {
			$html.='<p style="font-size:13px;">
				  <img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />
				   register_globals is set correctly.
			  </p>';
		}
	
		
		$html .= '<br><li style="font-size:14px;"><strong>USER STATUS CHECK</strong></li>';
		
		$user = Dap_User::loadUserByEmail($emailId);
		if(!isset($user)) {
			$html.='<p style="font-size:13px;">
				  <img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/cross-icon.png" />
				  Sorry, could not find the user in DAP users->manage page. Cannot proceed until this issue is addressed. <br><br>
			  </p>';	
			$html.='</blockquote></div>';
		
			return $html; 
		}
		
		$status=$user->getStatus();			
		if($status=="A") {
			$html.='<p style="font-size:13px;">
			  <img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />
			  This user\'s account status is ACTIVE (=A).
		  </p>';	
		}
		else {
			$html.='<p style="font-size:13px;">
			  <img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/cross-icon.png" />
			  This user\'s status is set to ' . $status . '. The user status needs to be activated to allow user login.
			 <strong> <p style="color:red;font-size:13px;">Click on the user status (hyperlink) in the table below </a> to activate user status.</p></strong>
		  </p>';	
		}
		
		$count=0;
		
		$ustatus=$user->getStatus();
		$uid=$user->getId();
		$productsFound=false;
		
		$count=getUserDetails($html,$productId,$user,$emailId,$productsFound);

		/*$html .=  '<p style="font-size:12px;"><table id="usersTable" width="100%"  border="1" cellspacing="3" cellpadding="3" class="bodytextArial">
				   <tr class="bodytext">
				    <td><b>Email<br/></b></td>
					<td><b>User<br/>Status</b></td>
					<td><b>Product<br/>Status</b></td>
					<td><b>Product Name</b></td>
					<td><b>Access Start<br/>Date</b></td>
					<td><b>Access End<br/>Date</b></td>
					<td><b>Order Id</b></td>
					<td nowrap><b>Product Access</b></td>
					</tr>';

		$userProducts = Dap_UsersProducts::loadProductsIgnoreStatus($user->getId());
		foreach ($userProducts as $userProduct) {
			$productsFound=true;
			$count++;
			$productId = $userProduct->getProduct_id();
			//logToFile("productId: $productId"); 
			$product = Dap_Product::loadProduct( $productId );
			
			$pname = $product->getName();
			$productNameShort = trimString($pname,46,30,10);
			$email = mb_convert_encoding($emailId, "UTF-8", "auto"); 
			
			$upjstatus = $userProduct->getStatus();
			$accessStart = $userProduct->getAccess_start_date();
			$accessEnd = $userProduct->getAccess_end_date();
			
			$html .=  "<tr bgcolor=\"#EFEFEF\" onmouseover=\"this.className='highlightedRow'\" onmouseout=\"this.className='nonHighlightedRow'\">";
			$html .=  "<td  class=\"bodytext\" bgcolor=\"$isAdminColor\"><a href='/dap/admin/addEditUsers.php?userId=".$user->getId()."'>". $email."</a></td>";
			
			$inactiveColor = "";
			if(  ($ustatus == "I") || ($ustatus == "U") || ($ustatus == "P") || ($ustatus == "L") ){
				$inactiveColor = $warningColor;
			}
			
			if($ustatus == "A") { $title = "Change status to 'Inactive'"; }
			if($ustatus == "I") { $title = "Change status to 'Active'"; }
			if($ustatus == "U") { $title = "Change status to 'Active'"; }
			if($ustatus == "P") { $title = "Change status to 'Active'"; }
			if($ustatus == "L") { $title = "Unlock User & Send 'Unlocked' notification email"; }
			
				
			if(  ($ustatus == "A") || ($ustatus == "I") || ($ustatus == "U") || ($ustatus == "P") ){
				$html .= "<td  class=\"bodytext\" align=\"center\" bgcolor=\"$inactiveColor\"><a href=\"#\" onClick=\"toggleUserStatus(".$uid.",'".$ustatus."');\" title=\"$title\">".$ustatus."</a></td>";
			} else if($ustatus == "L") {
				$html .= "<td  class=\"bodytext\" align=\"center\" bgcolor=\"$inactiveColor\"><a href=\"#\" onClick=\"unlockUser(".$uid.");\" title=\"$title\">".$ustatus."</a></td>";
			}
			$inactiveColor = "";
			$title = "Change status to 'Inactive'";
			if($upjstatus == "I"){
				$inactiveColor = $warningColor;
				$title = "Change status to 'Active'";
			}	
			
			$html .= "<td  class=\"bodytext\" align=\"center\" bgcolor=\"$inactiveColor\"><a href=\"#\" onClick=\"toggleProductStatus(".$uid.",".$productId.",'".$upjstatus."');\" title=\"$title\">".$upjstatus."</a></td>";
			
			
			$transaction_id = $userProduct->getTransaction_id();
			$thtml = "<td class=\"bodytext\"><a href='/dap/admin/manageTransactions.php?transactionId=$transaction_id'>" . $transaction_id . "</a></td>";					
			$transactionArray = array(  
									"-1" => "FREE", //(Signup)
									"-2" => "FREE", //(Admin)
									"-3" => "PAID"
								);
								
			//if transaction_id is in the transactionArray, then do the conversion - if not, keep it as it is
			if(array_key_exists($transaction_id, $transactionArray)) {
				$transaction_id = $transactionArray[$transaction_id];
				$thtml = "<td class=\"bodytext\">" . $transaction_id . "</td>";					
			}
			
			
			
			$html .=  "<td class=\"bodytext\"><a href=\"addEditProducts.php?productId=".$productId."\" title=\"".$pname."\">".$productNameShort."</a></td>
						<td class=\"bodytext\" align=\"center\">" . $accessStart. "</td>
						<td class=\"bodytext\" align=\"center\" bgColor='$inactiveColor'>" . $accessEnd. "</td>
						$thtml
						<td class=\"bodytext\">
							<a href=\"#\" onClick=\"javascript:loadUserProductRel(".$uid.",".$productId."); return false;\" title='Click to modify Access Start & End Dates'>Modify</a> | 
							<a href=\"#\" onClick=\"javascript:markSelectedUsersAsPaidDirect(".$uid.",".$productId.",'-3'); return false;\" title='Mark as a PAID user - useful when you want to categorize user (for your own reporting purposes) that user is just like a paid user even though there is no order for this user in the system'>Paid</a> | 
							<a href=\"#\" onClick=\"javascript:markSelectedUsersAsFreeDirect(".$uid.",".$productId.",'-1'); return false;\" title='Mark as a FREE user - this is for your own reporting purposes - usually a free user is someone who signed up through a free signup form, or someone to whom you have given free access but wish to categorize as a free user for whatever reason'>Free</a> | 
							<a href=\"#\" onClick=\"javascript:removeSelectedUsersFromProductDirect(".$uid.",".$productId."); return false;\" title=\"Completely remove User's access to this Product\">Remove</a>";
							
			$html .= "</tr>";				
	
		}
		
		
		$html.='</table></p>';
		
		$redirectURL = Dap_UsersProducts::getLoggedInURL();
		$html .= '<br><li style="font-size:14"><strong>LOGIN REDIRECT CHECK</strong></li>';
		
		$html.='<p style="font-size:12px;"><img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />This user has access to ' . $count . ' product(s). <p style="color:red;font-size:14px;"> This user will be redirected to ' . $redirectURL . ' upon login.</p>';

		if($count>1) {
			$html.='<p style="font-size:13px;"><strong>NOTE: </strong><strong>When a user has access to more than one product, DAP uses post-login redirect set in DAP SetUp => Config => Access & Navigation Section => Post Login Redirect URL.</strong><br><p>';			
		}
		else {
			$html.='<p style="font-size:13px;"><strong>NOTE: </strong><strong>When a user has access to just one product, DAP uses post-login redirect set in DAP Products page for that product to redirect user upon login.<br><br> But if product-level post-login redirect URL is missing, then DAP will use the post-login URL set in SetUp => Config => Access & Navigation Section => Post Login Redirect URL.</strong><p>';
		}
		
		$html .= '<br><li style="font-size:14"><strong>LOGIN REDIRECT URL ACCESSIBLITY CHECK</strong></li>';
		
		$handlerr = curl_init($redirectURL);
		curl_setopt($handlerr,  CURLOPT_RETURNTRANSFER, TRUE);
		$resp = curl_exec($handlerr);
		$ht = curl_getinfo($handlerr, CURLINFO_HTTP_CODE);
		
		if ($ht == '404') { 
			$html.='<p style="font-size:13px;color:red;">Your POST-LOGIN REDIRECT URL is incorrect. You have set it to ' . $redirectURL . ' but there is no such page. The page yields a 404 error. Please set the post-login redirect to point to a VALID URL.<p>';			
		}
		else { 
			$html.='<p style="font-size:12px;"><strong><img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />Well Done! Your post-login-redirect URL is set to ' .  $redirectURL . '. It\'s a valid URL.<p>';			
		}
		
		$html .= '<br><li style="font-size:14"><strong>LOGIN REDIRECT ACCESSIBLE BY USER CHECK</strong></li>';
		if(!Dap_Resource::isResourceProtected($redirectURL)) {
			$html.='<p style="font-size:12px;"><img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />Your POST-LOGIN REDIRECT URL is NOT protected. This user ('. $email . ') has access to this post-login URL.<p>';		
		}
		else {
			$sss="N";
			$access = Dap_UsersProducts::isProtectedResourceAvailableToUser($uid, $productId, $redirectURL, $sss);
			if($access) {
				$html.='<p style="font-size:13px;color:red;"><img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />Your POST-LOGIN REDIRECT URL is a PROTECTED URL. The good news is this user has access to product(s) under which this URL/page is protected. This user should be able to land on this page successfully upon login.<p>';	
			}
			else {
				$html.='<p style="font-size:13px;color:red;">Your POST-LOGIN REDIRECT URL is a PROTECTED URL. But this user does not have access to this protected URL. If you redirect users to a protected page when they login, then please make sure to make that protected page/url available to ALL your products or even valid users cannot login.<p>';
			}
		}
		
		$html.='</blockquote></div>';
		*/
		return $html;
	}

	function getUserDetails(&$html,$productId,$user,$emailId,$productsFound,$fullDetails="Y") {
	$html .=  '<p style="font-size:13px;"><table id="usersTable" width="100%"  border="1" cellspacing="3" cellpadding="3" class="bodytextArial">
				   <tr class="bodytext" style="font-size:11px!important;">
				    <td><b>Email<br/></b></td>
					<td><b>User<br/>Status</b></td>
					<td><b>Product<br/>Status</b></td>
					<td><b>Product Name</b></td>
					<td><b>Access Start<br/>Date</b></td>
					<td><b>Access End<br/>Date</b></td>
					<td><b>Order Id</b></td>
					<td nowrap><b>Product Access</b></td>
					</tr>';
		
		$userProducts = Dap_UsersProducts::loadProductsForUser($user->getId());
		
		$ustatus=$user->getStatus();
		$uid=$user->getId();

		foreach ($userProducts as $userProduct) {
			$productsFound=true;
			$count++;
			$productId = $userProduct->getProduct_id();
			//logToFile("productId: $productId"); 
			$product = Dap_Product::loadProduct( $productId );
			
			$pname = $product->getName();
			$productNameShort = trimString($pname,46,30,10);
			$email = mb_convert_encoding($emailId, "UTF-8", "auto"); 
			
			$upjstatus = $userProduct->getStatus();
			$accessStart = $userProduct->getAccess_start_date();
			$accessEnd = $userProduct->getAccess_end_date();
			$postLoginRedirect = $product->getLogged_in_url();
			
			$html .=  "<tr style='font-size:11px!important;' bgcolor=\"#EFEFEF\" onmouseover=\"this.className='highlightedRow'\" onmouseout=\"this.className='nonHighlightedRow'\">";
			$html .=  "<td  class=\"bodytext\" style='font-size:11px!important;' bgcolor=\"$isAdminColor\"><a href='/dap/admin/addEditUsers.php?userId=".$user->getId()."'>". $email."</a></td>";
			
			$inactiveColor = "";
			if(  ($ustatus == "I") || ($ustatus == "U") || ($ustatus == "P") || ($ustatus == "L") ){
				$inactiveColor = $warningColor;
			}
			
			if($ustatus == "A") { $title = "Change status to 'Inactive'"; }
			if($ustatus == "I") { $title = "Change status to 'Active'"; }
			if($ustatus == "U") { $title = "Change status to 'Active'"; }
			if($ustatus == "P") { $title = "Change status to 'Active'"; }
			if($ustatus == "L") { $title = "Unlock User & Send 'Unlocked' notification email"; }
			
				
			if(  ($ustatus == "A") || ($ustatus == "I") || ($ustatus == "U") || ($ustatus == "P") ){
				$html .= "<td  style='font-size:11px!important;' class=\"bodytext\" align=\"center\" bgcolor=\"$inactiveColor\"><a href=\"#\" onClick=\"toggleUserStatus(".$uid.",'".$ustatus."',0);\" title=\"$title\">".$ustatus."</a></td>";
			} else if($ustatus == "L") {
				$html .= "<td  style='font-size:11px!important;' class=\"bodytext\" align=\"center\" bgcolor=\"$inactiveColor\"><a href=\"#\" onClick=\"unlockUser(".$uid.");\" title=\"$title\">".$ustatus."</a></td>";
			}
			$inactiveColor = "";
			$title = "Change status to 'Inactive'";
			if($upjstatus == "I"){
				$inactiveColor = $warningColor;
				$title = "Change status to 'Active'";
			}	
			
			$html .= "<td style='font-size:11px!important;' class=\"bodytext\" align=\"center\" bgcolor=\"$inactiveColor\"><a href=\"#\" onClick=\"toggleProductStatus(".$uid.",".$productId.",'".$upjstatus."',0);\" title=\"$title\">".$upjstatus."</a></td>";
			
			
			$transaction_id = $userProduct->getTransaction_id();
			$thtml = "<td style='font-size:11px!important;' class=\"bodytext\"><a href='/dap/admin/manageTransactions.php?transactionId=$transaction_id'>" . $transaction_id . "</a></td>";					
			$transactionArray = array(  
									"-1" => "FREE", //(Signup)
									"-2" => "FREE", //(Admin)
									"-3" => "PAID"
								);
								
			//if transaction_id is in the transactionArray, then do the conversion - if not, keep it as it is
			if(array_key_exists($transaction_id, $transactionArray)) {
				$transaction_id = $transactionArray[$transaction_id];
				$thtml = "<td style='font-size:11px!important;' class=\"bodytext\">" . $transaction_id . "</td>";					
			}
						
			$html .=  "<td style='font-size:11px!important;' class=\"bodytext\"><a href=\"addEditProducts.php?productId=".$productId."\" title=\"".$pname."\">".$productNameShort."</a></td>
						<td style='font-size:11px!important;' class=\"bodytext\" align=\"center\">" . $accessStart. "</td>
						<td style='font-size:11px!important;' class=\"bodytext\" align=\"center\" bgColor='$inactiveColor'>" . $accessEnd. "</td>
						$thtml
						<td style='font-size:11px!important;' class=\"bodytext\">
							<a href=\"#\" onClick=\"javascript:loadUserProductRel(".$uid.",".$productId.",0); return false;\" title='Click to modify Access Start & End Dates'>Modify</a> | 
							<a href=\"#\" onClick=\"javascript:markSelectedUsersAsPaidDirect(".$uid.",".$productId.",'-3'); return false;\" title='Mark as a PAID user - useful when you want to categorize user (for your own reporting purposes) that user is just like a paid user even though there is no order for this user in the system'>Paid</a> | 
							<a href=\"#\" onClick=\"javascript:markSelectedUsersAsFreeDirect(".$uid.",".$productId.",'-1'); return false;\" title='Mark as a FREE user - this is for your own reporting purposes - usually a free user is someone who signed up through a free signup form, or someone to whom you have given free access but wish to categorize as a free user for whatever reason'>Free</a> | 
							<a href=\"#\" onClick=\"javascript:removeSelectedUsersFromProductDirect(".$uid.",".$productId.",0); return false;\" title=\"Completely remove User's access to this Product\">Remove</a>";
							
			$html .= "</tr>";				
	
		}
		
		$html.='</table></p>';
		
		if($fullDetails!="Y") {
			//$html.='</blockquote></div>';
			return;
		}
		
		$redirectURL = Dap_UsersProducts::getLoggedInURL($user);
		$html .= '<br><li style="font-size:14px;"><strong>POST LOGIN LANDING PAGE (POST-LOGIN-REDIRECT)</strong></li>';
		
		$html.='<p style="font-size:13px;"><img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />This user has access to ' . $count . ' product(s). <p style="color:red;font-size:14px;"> This user will be redirected to ' . $redirectURL . ' upon login.</p>';

		if($count>1) {
			$html.='<p style="font-size:13px;"><strong>NOTE: </strong><strong>When a user has access to more than one product, DAP uses post-login redirect set in DAP SetUp => Config => Access & Navigation Section => Post Login Redirect URL.</strong><br><br>In this case, the user has access to ' . $count . ' products. So DAP will use the post-login redirect set in DAP SetUp => Config => Access & Navigation Section => Post Login Redirect URL to redirect user.<p>';			
		}
		else {
			if($postLoginRedirect=="") {
				$html.='<p style="font-size:13px;"><strong>NOTE: </strong><strong>When a user has access to just one product, DAP uses post-login redirect set in DAP Products page for that product to redirect users upon login.<br><br> But if product-level post-login redirect URL is missing, then DAP will use the post-login URL set in SetUp => Config => Access & Navigation Section => Post Login Redirect URL.</strong><p style="font-size:13px;">In this case, the user has access to just 1 product and the product level post-login-redirect URL is NOT set. So DAP will use the post-login redirect set in DAP SetUp => Config => Access & Navigation Section => Post Login Redirect URL to redirect user.<p>';
			}
			else {
	$html.='<p style="font-size:13px;"><strong>NOTE: </strong><strong>When a user has access to just one product, DAP uses post-login redirect set in DAP Products page for that product to redirect users upon login.<br><br> But if product-level post-login redirect URL is missing, then DAP will use the post-login URL set in SetUp => Config => Access & Navigation Section => Post Login Redirect URL.</strong><p>In this case, the user has access to just 1 product and the product level post-login-redirect URL is set to ' . $postLoginRedirect . '. So DAP will redirect user to ' . $postLoginRedirect . '.<p>';
			}
		}
		
//		$html .= '<br><li style="font-size:14"><strong>LOGIN REDIRECT URL ACCESSIBLITY CHECK</strong></li>';
		
		$handlerr = curl_init($redirectURL);
		curl_setopt($handlerr,  CURLOPT_RETURNTRANSFER, TRUE);
		$resp = curl_exec($handlerr);
		$ht = curl_getinfo($handlerr, CURLINFO_HTTP_CODE);
		
	
		$html .= '<br><li style="font-size:14px;"><strong>IS THE POST-LOGIN LANDING PAGE A PROTECTED URL?</strong></li>';
		
		if ($ht == '404') { 
			$html.='<p style="font-size:13px;color:red;">Your POST-LOGIN REDIRECT URL is incorrect. You have set it to ' . $redirectURL . ' but there is no such page. The page yields a 404 error. Please set the post-login redirect to point to a VALID URL.<p><br>';			
		}
		
		if(!Dap_Resource::isResourceProtected($redirectURL)) {
			$html.='<p style="font-size:13px;"><img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />Your POST-LOGIN REDIRECT URL is NOT protected. This user ('. $email . ') has access to this post-login URL.<p>';		
		}
		else {
			$sss="N";
		
			$access=Dap_UsersProducts::isProtectedResourceAvailableToUser($uid, $productId, $redirectURL, $accesserrstr);
			logToFile("functions_admin.php: isProtectedResourceAvailableToUser() returned = " . $access);
			
			if($access==0) {
				$html.='<p style="font-size:13px;"><img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />Your POST LOGIN REDIRECT URL is a PROTECTED URL. The good news is this user has access to product(s) under which this URL/page is protected. This user should be able to land on this page successfully upon login.<p>';	
			}
			else {
				$html.='<p style="font-size:13px;color:red;">Your POST-LOGIN REDIRECT URL is a PROTECTED URL. But this user does not have access to this protected URL. <br><br>
				
				Here\'s why this user does not have access:<br><br>' .  $accesserrstr . '<br><br>If you redirect users to a protected page when they login, the user must have access to atleast one product under which the page is protected. Looks like this user does not have access to any product under which this URL is protected/dripped. <br> <br>If you want to redirect users to a protected page upon login, then please make sure to make that protected page/url available to ALL the products in the DAP Products => Content Responder tab. <br><br> This way as long as the user has access to ANY of your products, they can login.<p>';
			}
		}
		
		$html.='</blockquote></div>';
		
		return $count;
	}
	
	
	function getUserDetailsForContentAccess(&$html,$productId,$user,$emailId,$productsFound,$fullDetails="Y") {
	$html .=  '<p style="font-size:13px;"><table id="usersTable" width="100%"  border="1" cellspacing="3" cellpadding="3" class="bodytextArial">
				   <tr class="bodytext" style="font-size:11px!important;">
				    <td><b>Email<br/></b></td>
					<td><b>User<br/>Status</b></td>
					<td><b>Product<br/>Status</b></td>
					<td><b>Product Name</b></td>
					<td><b>Access Start<br/>Date</b></td>
					<td><b>Access End<br/>Date</b></td>
					<td><b>Order Id</b></td>
					<td nowrap><b>Product Access</b></td>
					</tr>';
		
		$userProducts = Dap_UsersProducts::loadProductsForUser($user->getId());
		
		$ustatus=$user->getStatus();
		$uid=$user->getId();

		foreach ($userProducts as $userProduct) {
			$productsFound=true;
			$count++;
			$productId = $userProduct->getProduct_id();
			//logToFile("productId: $productId"); 
			$product = Dap_Product::loadProduct( $productId );
			
			$pname = $product->getName();
			$productNameShort = trimString($pname,46,30,10);
			$email = mb_convert_encoding($emailId, "UTF-8", "auto"); 
			
			$upjstatus = $userProduct->getStatus();
			$accessStart = $userProduct->getAccess_start_date();
			$accessEnd = $userProduct->getAccess_end_date();
			$postLoginRedirect = $product->getLogged_in_url();
			
			$html .=  "<tr style='font-size:11px!important;' bgcolor=\"#EFEFEF\" onmouseover=\"this.className='highlightedRow'\" onmouseout=\"this.className='nonHighlightedRow'\">";
			$html .=  "<td  class=\"bodytext\" style='font-size:11px!important;' bgcolor=\"$isAdminColor\"><a href='/dap/admin/addEditUsers.php?userId=".$user->getId()."'>". $email."</a></td>";
			
			$inactiveColor = "";
			if(  ($ustatus == "I") || ($ustatus == "U") || ($ustatus == "P") || ($ustatus == "L") ){
				$inactiveColor = $warningColor;
			}
			
			if($ustatus == "A") { $title = "Change status to 'Inactive'"; }
			if($ustatus == "I") { $title = "Change status to 'Active'"; }
			if($ustatus == "U") { $title = "Change status to 'Active'"; }
			if($ustatus == "P") { $title = "Change status to 'Active'"; }
			if($ustatus == "L") { $title = "Unlock User & Send 'Unlocked' notification email"; }
			
				
			if(  ($ustatus == "A") || ($ustatus == "I") || ($ustatus == "U") || ($ustatus == "P") ){
				$html .= "<td  style='font-size:11px!important;' class=\"bodytext\" align=\"center\" bgcolor=\"$inactiveColor\"><a href=\"#\" onClick=\"toggleUserStatus(".$uid.",'".$ustatus."',1);\" title=\"$title\">".$ustatus."</a></td>";
			} else if($ustatus == "L") {
				$html .= "<td  style='font-size:11px!important;' class=\"bodytext\" align=\"center\" bgcolor=\"$inactiveColor\"><a href=\"#\" onClick=\"unlockUser(".$uid.",1);\" title=\"$title\">".$ustatus."</a></td>";
			}
			$inactiveColor = "";
			$title = "Change status to 'Inactive'";
			if($upjstatus == "I"){
				$inactiveColor = $warningColor;
				$title = "Change status to 'Active'";
			}	
			
			$html .= "<td style='font-size:11px!important;' class=\"bodytext\" align=\"center\" bgcolor=\"$inactiveColor\"><a href=\"#\" onClick=\"toggleProductStatus(".$uid.",".$productId.",'".$upjstatus."',1);\" title=\"$title\">".$upjstatus."</a></td>";
			
			
			$transaction_id = $userProduct->getTransaction_id();
			$thtml = "<td style='font-size:11px!important;' class=\"bodytext\"><a href='/dap/admin/manageTransactions.php?transactionId=$transaction_id'>" . $transaction_id . "</a></td>";					
			$transactionArray = array(  
									"-1" => "FREE", //(Signup)
									"-2" => "FREE", //(Admin)
									"-3" => "PAID"
								);
								
			//if transaction_id is in the transactionArray, then do the conversion - if not, keep it as it is
			if(array_key_exists($transaction_id, $transactionArray)) {
				$transaction_id = $transactionArray[$transaction_id];
				$thtml = "<td style='font-size:11px!important;' class=\"bodytext\">" . $transaction_id . "</td>";					
			}
						
			$html .=  "<td style='font-size:11px!important;' class=\"bodytext\"><a href=\"addEditProducts.php?productId=".$productId."\" title=\"".$pname."\">".$productNameShort."</a></td>
						<td style='font-size:11px!important;' class=\"bodytext\" align=\"center\">" . $accessStart. "</td>
						<td style='font-size:11px!important;' class=\"bodytext\" align=\"center\" bgColor='$inactiveColor'>" . $accessEnd. "</td>
						$thtml
						<td style='font-size:11px!important;' class=\"bodytext\">
							<a href=\"#\" onClick=\"javascript:loadUserProductRel(".$uid.",".$productId.",1); return false;\" title='Click to modify Access Start & End Dates'>Modify Access Dates</a> | 
							<a href=\"#\" onClick=\"javascript:removeSelectedUsersFromProductDirect(".$uid.",".$productId.",1); return false;\" title=\"Completely remove User's access to this Product\">Remove</a>";
							
			$html .= "</tr>";				
	
		}
		
		$html.='</table></p>';
		
		if($fullDetails!="Y") {
			//$html.='</blockquote></div>';
			return;
		}
		
		$redirectURL = Dap_UsersProducts::getLoggedInURL($user);
		$html .= '<br><li style="font-size:14px;"><strong>POST LOGIN LANDING PAGE (POST-LOGIN-REDIRECT)</strong></li>';
		
		$html.='<p style="font-size:13px;"><img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />This user has access to ' . $count . ' product(s). <p style="color:red;font-size:14px;"> This user will be redirected to ' . $redirectURL . ' upon login.</p>';

		if($count>1) {
			$html.='<p style="font-size:13px;"><strong>NOTE: </strong><strong>When a user has access to more than one product, DAP uses post-login redirect set in DAP SetUp => Config => Access & Navigation Section => Post Login Redirect URL.</strong><br><br>In this case, the user has access to ' . $count . ' products. So DAP will use the post-login redirect set in DAP SetUp => Config => Access & Navigation Section => Post Login Redirect URL to redirect user.<p>';			
		}
		else {
			if($postLoginRedirect=="") {
				$html.='<p style="font-size:13px;"><strong>NOTE: </strong><strong>When a user has access to just one product, DAP uses post-login redirect set in DAP Products page for that product to redirect users upon login.<br><br> But if product-level post-login redirect URL is missing, then DAP will use the post-login URL set in SetUp => Config => Access & Navigation Section => Post Login Redirect URL.</strong><p style="font-size:13px;">In this case, the user has access to just 1 product and the product level post-login-redirect URL is NOT set. So DAP will use the post-login redirect set in DAP SetUp => Config => Access & Navigation Section => Post Login Redirect URL to redirect user.<p>';
			}
			else {
	$html.='<p style="font-size:13px;"><strong>NOTE: </strong><strong>When a user has access to just one product, DAP uses post-login redirect set in DAP Products page for that product to redirect users upon login.<br><br> But if product-level post-login redirect URL is missing, then DAP will use the post-login URL set in SetUp => Config => Access & Navigation Section => Post Login Redirect URL.</strong><p>In this case, the user has access to just 1 product and the product level post-login-redirect URL is set to ' . $postLoginRedirect . '. So DAP will redirect user to ' . $postLoginRedirect . '.<p>';
			}
		}
		
//		$html .= '<br><li style="font-size:14"><strong>LOGIN REDIRECT URL ACCESSIBLITY CHECK</strong></li>';
		
		$handlerr = curl_init($redirectURL);
		curl_setopt($handlerr,  CURLOPT_RETURNTRANSFER, TRUE);
		$resp = curl_exec($handlerr);
		$ht = curl_getinfo($handlerr, CURLINFO_HTTP_CODE);
		
	
		$html .= '<br><li style="font-size:14px;"><strong>IS THE POST-LOGIN LANDING PAGE A PROTECTED URL?</strong></li>';
		
		if ($ht == '404') { 
			$html.='<p style="font-size:13px;color:red;">Your POST-LOGIN REDIRECT URL is incorrect. You have set it to ' . $redirectURL . ' but there is no such page. The page yields a 404 error. Please set the post-login redirect to point to a VALID URL.<p><br>';			
		}
		
		if(!Dap_Resource::isResourceProtected($redirectURL)) {
			$html.='<p style="font-size:13px;"><img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />Your POST-LOGIN REDIRECT URL is NOT protected. This user ('. $email . ') has access to this post-login URL.<p>';		
		}
		else {
			$sss="N";
			$access = Dap_UsersProducts::isProtectedResourceAvailableToUser($uid, $productId, $redirectURL, $accesserrstr);
			logToFile("functions_admin.php: isProtectedResourceAvailableToUser() returned=" . $access);
			
			if($access==0) {
				$html.='<p style="font-size:13px;"><img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />Your POST-LOGIN REDIRECT URL is a PROTECTED URL. The good news is this user has access to product(s) under which this URL/page is protected. This user should be able to land on this page successfully upon login.<p>';	
			}
			else {
				$html.='<p style="font-size:13px;color:red;">Your POST-LOGIN REDIRECT URL is a PROTECTED URL. But this user does not have access to this protected URL. <br><br>
				Here\'s why this user does not have access:<br><br>' .  $accesserrstr . '<br><br>If you redirect users to a protected page when they login, the user must have access to atleast one product under which the page is protected. Looks like this user does not have access to any product under which this URL is protected/dripped.<br> <br>If you want to redirect users to a protected page upon login, then please make sure to make that protected page/url available to ALL the products in the DAP Products => Content Responder tab. <br><br> This way as long as the user has access to ANY of your products, they can login.<p>';
			}
			
		}
		
		$html.='</blockquote></div>';
		
		return $count;
	}
	
	function findCauseOfContentAccessIssueHTML($caurl, $emailId, $caCat, $blogpath) {
		
		$user = Dap_User::loadUserByEmail($emailId);
			
		$html="";
		
		$html .= '<div><blockquote>';
		
		
		$url_parts = parse_url($caurl);
		$caurl = $url_parts['path'];
		$caurl = rtrim($caurl, '/');
		
		logToFile("functions_admin: caurl=".$caurl,LOG_DEBUG_DAP);
			
		//Why is user able to access content that they should not be able to access
		if($caCat==1) {
			if(!isset($user)) {
			  $html.='<p style="font-size:12px;">
					<img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/cross-icon.png" />
					Sorry, could not find the user in DAP users->manage page. Cannot proceed until this issue is addressed. <br><br>
				</p>';	
			  $html.='</blockquote></div>';
		  
			  return $html; 
			}
			
			$ustatus=$user->getStatus();
			$uid=$user->getId();
			$status=$user->getStatus();
			
			$html .= '<li style="font-size:14px;"><strong>Is DAP allowing access to content that the user is not elgible for?</strong></li>';
			
			if(!Dap_Resource::isResourceProtected($caurl)) {
				$html.='<p style="font-size:13px;"><img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />Your POST-LOGIN REDIRECT URL is NOT protected under any of your products. This user ('. $emailId . ') has access to this post-login URL because it\'s not protected.<p>';
				$html.='</blockquote></div>';
				return $html;
			}
			
			$access = Dap_UsersProducts::isProtectedResourceAvailableToUser($uid, $productId, $caurl,$accesserrstr);
			
			if($access==0) {
				$html.='<p style="font-size:12px;">
				  <img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/cross-icon.png" />This (' . $caurl . ') is a PROTECTED URL and the user HAS access to this protected content as you can see in the table below :<br><p>';
				  
				$ustatus=$user->getStatus();
				$uid=$user->getId();
				$productsFound=false;
				
				$ret=getUserDetailsForContentAccess($html,$productId,$user,$emailId,$productsFound,"N");	
			}
			else {
				$html.='<p style="font-size:13px;color:red;">The user is not elgible for this content.<br>				
				Here\'s why this user does not have access:<br><br>' .  $accesserrstr . '<br><br>
				DAP will NOT allow the user access to this content. If you are sure the user is able to access this content, then this is likely a caching issue. Please check the next step (below) on cache plugin check..<p>';
			}
			
			/*if($accessResults == -1) {
				logToFile("functions_admin: accessResults=-1",LOG_DEBUG_DAP);
				$html.='<p style="font-size:14px;color:red;">
				  <img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />
				  The user is not elgible for this content. The user does NOT have access to any product(s) under which this content is currently available. DAP will NOT allow the user access to this content. If you are sure the user is able to access this content, then this is likely a caching issue. Please check the next step (below) on cache plugin check.<p>';	
			}
			else if($accessResults == -2) {
				logToFile("functions_admin: accessResults=-2",LOG_DEBUG_DAP);
				$html.='<p style="font-size:14px;color:red;">
				  <img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />The user is not elgible for this content. This user\'s status to the product(s) under which this URL/page is protected is INACTIVE (I). DAP will NOT allow the user access to this content. If you are sure the user is able to access this content, then this is likely a caching issue. Please check the next step (below) on cache plugin check.<p>';	
			}
			else if($accessResults == -3){
				logToFile("functions_admin: accessResults=-3",LOG_DEBUG_DAP);
				$html.='<p style="font-size:14px;color:red;">
				  <img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />The user is not elgible for this content. This (' . $caurl . ') is a PROTECTED URL but it is not available to the user. This user\'s access to all products under which this content is dripped / protected,  has expired i.e. the access end date is in the past. If you are sure the user is able to access this content, then this is likely a caching issue. Please check the next step (below) on cache plugin check.<br><p>';
			}
			else if($accessResults==true) {
			
				
			}
			*/
			
			$html .= '<br><p style="font-size:13px;"><strong>Here\'s a List of Products under which this URL/Content is currently Available/Protected</strong></p>';
		
			$html .= loadProductsUnderWhichContentIsProtectedHTML($caurl);
			
		}
		
		else {
			$html .= '<li style="font-size:14px;"><strong>User Status Check</strong></li>';
			
			
			if(!isset($user)) {
				$html.='<p style="font-size:12px;">
					  <img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/cross-icon.png" />
					  Sorry, could not find the user in DAP users->manage page. Cannot proceed until this issue is addressed. <br><br>
				  </p>';	
				$html.='</blockquote></div>';
			
				return $html; 
			}
			
			$ustatus=$user->getStatus();
			$uid=$user->getId();
			$status=$user->getStatus();
			
			if($status=="A") {
				$html.='<p style="font-size:12px;">
				  <img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />
				  This user\'s account status is ACTIVE (=A).
			  </p>';	
			}
			else {
				$html.='<p style="font-size:12px;">
				  <img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/cross-icon.png" />
				  This user\'s status is set to ' . $status . '. It needs to be activated to allow user login.
				 <strong> <p style="color:red;font-size:13px;">Click on the user status (hyperlink) in the table below </a> to activate user status.</p></strong>
			  </p>';	
			}
			
			$count=0;
			
			
			$productsFound=false;
			
			if(!Dap_Resource::isResourceProtected($caurl)) {
				$html.='<p style="font-size:13px;"><img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />Your POST-LOGIN REDIRECT URL is NOT protected under any of your products. This user ('. $emailId . ') should have access to this post-login URL because it\'s not protected. <br><br>Sorry, could not find any reason for content access issue. DAP does not control access to unprotected content. Use a new browser where you are NOT logged-in and see if you can access this open/unprotected content. <br><br> If you still get an error page, then please  <a target="_blank" href="http://digitalaccesspass.com/support">click here</a> to open a Support Ticket<p>';
				$html.='</blockquote></div>';
				return $html;
			}
			
			$ret=getUserDetailsForContentAccess($html,$productId,$user,$emailId,$productsFound,"N");
			
			$access = Dap_UsersProducts::isProtectedResourceAvailableToUser($uid, $productId, $caurl,$accesserrstr);
			
			$html .= '<br><li style="font-size:14px;"><strong>Is this URL/Content available to the user='.$emailId.' ?</strong></li>';
			
			logToFile("functions_admin: accessResults = ".$accesserrstr,LOG_DEBUG_DAP);
			if($access==0) {
				$html.='<p style="font-size:12px;">
				  <img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />This (' . $caurl . ') is a PROTECTED URL and the user HAS access to this protected content. No issue found.<br><p>';
			}
			else {
				$html.='<p style="font-size:13px;color:red;">
				<img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/cross-icon.png" />
				The user is not elgible for this content.<br>				
				Here\'s why this user does not have access:<br><br>' .  $accesserrstr . '<br><br>
				DAP will NOT allow the user access to this content until this issue is addressed.<p>';
			}
			
	
			
			/*if($accessResults == -1) {
				logToFile("functions_admin: accessResults=-1",LOG_DEBUG_DAP);
				$html.='<p style="font-size:14px;color:red;">
				  <img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/cross-icon.png" />
				  Content cannot be accessed because the user does not have access to any products under which this content is currently available. Please check the DAP Admin => Products/Levels => "Content Responder tab" to make sure the content is dripped/available under the right products.<p>';	
			}
			else if($accessResults == -2) {
				logToFile("functions_admin: accessResults=-2",LOG_DEBUG_DAP);
				$html.='<p style="font-size:14px;color:red;">
				  <img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/cross-icon.png" />This user\'s status to the product(s) under which this URL/page is protected is INACTIVE (I). <p>';	
			}
			else if($accessResults == -3){
				logToFile("functions_admin: accessResults=-3",LOG_DEBUG_DAP);
				$html.='<p style="font-size:14px;color:red;">
				  <img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/cross-icon.png" />This (' . $caurl . ') is a PROTECTED URL but it is not available to the user. This user\'s access to all products under which this content is dripped / protected,  has expired i.e. the access end date is in the past.<br><p>';
			}
			else if($accessResults==true) {
				$html.='<p style="font-size:12px;">
				  <img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />This (' . $caurl . ') is a PROTECTED URL and the user HAS access to this protected content. No issue found.<br><p>';
				
			}*/
			
			$html .= '<br><p style="font-size:13px;"><strong>Here\'s a List of Products under which this URL/Content is currently Available/Protected</strong></p>';
			
			$url_parts = parse_url($caurl);
			$caurl = $url_parts['path'];
			$caurl = rtrim($caurl, '/');
			
			logToFile("functions_admin: caurl=".$caurl,LOG_DEBUG_DAP);
			
			$html .= loadProductsUnderWhichContentIsProtectedHTML($caurl);
			
		}
		
		$html .= '<br><br><li style="font-size:14px;"><strong>CACHE Plugin Check</strong></li>';
		
		$wpfile=$blogpath . "wp-config.php";
		
		if (file_exists($wpfile)) {
			$config = file_get_contents ($blogpath . "wp-config.php");
		}
		
		if( isset($config) && ($config !="") ) {
		  $result = preg_match('/define.(.*)_CACHE(.*)\'/', $config, $m);
		  
		  if($result) {
			  logToFile("functions_admin.php: loadUserLoginIssueReportHTML: cache plugin found" ); 
			  $html .= '<p style="color:red;font-size:12px;"><img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/cross-icon.png" />
			  It appears that you have a CACHE plugin (e.g. WP Super Cache / Total Cache) active. <br>
			  If cache plugin is active, it can cause unexpected login and content access issues (if not used correctly). <br><br>
			  Please follow these steps to deactivate the cache plugin fully and see if it resolves the issue. <br><br>
			  1) De-activate the cache plugin<br>
			  2) Open your wp-config\.php file. If there are lines in there that look like this: <br><br>
			  define("WP_CACHE", true); <br>
			  define( "WPCACHEHOME", "/home/xyz/public_html/yoursite.com/wp-content/plugins/wp-super-cache/" );<br><br>
			  Remove these lines and save wp-config.php. <br><br>
			 
			  Please <a target="_blank" href="http://digitalaccesspass.com/doc/cache-plugin-setup/">read this post</a> on how to use cache plugin for membership sites.
			  </p>';
			  logToFile("functions_admin.php: loadUserLoginIssueReportHTML(): html=".$html ); 
		  }
		  else {
			  logToFile("functions_admin.php: loadUserLoginIssueReportHTML: no cache plugin found" ); 
			  $html.='<p style="font-size:13px;">
				  <img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />
				  No cache related issues found. 
			  </p>';
		  }
		}
		/*else {
			 $html.='<p style="font-size:13px;">
				  <img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/right.png" />
				  Could not find wp-config\.php file. 
			  </p>';
		}*/
		
		$filename=$blogpath . "/wp-content/cache";
		if (file_exists($filename)) {
			$html .= '<p style="color:red;font-size:13px;"><img  class="img_set" src="' . SITE_URL_DAP . '/dap/images/cross-icon.png" />
			  It appears that you have a "cache" folder under /wp-content. <br><br>
			  Please ftp to your site and go to your wp-content folder. <br>
			  See if there is a folder in there called "cache". If yes, then rename it to "_cache".<br><br>
			  Please <a target="_blank" href="http://digitalaccesspass.com/doc/cache-plugin-setup/">read this post</a> on how to use cache plugin for membership sites.
			</p>';
		}
		
		
		$html.='</blockquote></div>';
		return $html;
	}
	
	
	function loadMemberSummaryDisplayHTML($start_date, $end_date) {
		$result = Dap_Reports::loadMemberSummary($start_date, $end_date);
		
		//return;
		//logToFile("$start_date, $end_date, $merchant_commissions, $publisher_commissions"); 
		$dataFound = false;
		$html = '<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0" class="bodytextArial">
				  <tr class="scriptsubheading" valign="top" align="center">
					<td colspan="4">Members Per Product (per date range)</td>
				  </tr>
				  <tr class="scriptsubheading" valign="top" align="center">
					<td>Product</td>
					<td>Active</td>
					<td>Expired</td>
					<td>TOTAL</td>
				  </tr>
				';
		$i = 0;
		$totalPaid = 0;
		$totalAdminPaid = 0;
		$totalFree = 0;
		$totalExpired = 0;
		$totalRowTotal = 0;
		
		foreach($result as $element) {
			$dataFound = true;
			$totalActive += $element["active"];
			$totalExpired += $element["expired"];
			$totalRowTotal += $element["rowTotal"];
			
			if($element["active"] == 0) $element["active"] = "";
			if($element["expired"] == 0) $element["expired"] = "";
			if($element["rowTotal"] == 0) $element["rowTotal"] = "";
			
			$element["name"] = htmlentities(stripslashes($element["name"]), ENT_QUOTES, 'UTF-8');
			
			$html .= "<tr>
						<td><a href='/dap/admin/addEditProducts.php?productId=".$element["id"]."'>".$element["name"]."</a></td>
						<td align='right' bgcolor='#E8FFE8'>".$element["active"]."</td>
						<td align='right' bgcolor='#FEECCB'>".$element['expired']."</td>
						<td align='right'>".$element['rowTotal']."</td>
					  </tr>";
			$i++;
		}
		
		if($dataFound != false) {
			$html .= "<tr>
						<td align='right'><strong>TOTALS</strong></td>
						<td align='right'><strong>$totalActive</strong></td>
						<td align='right'><strong>$totalExpired</strong></td>
						<td align='right'><strong>$totalRowTotal</strong></td>
					  </tr>";
		} else {
			$html .= "<tr><td colspan='5'>Sorry, no data found for this period.</td></tr>";
		}
		
		$html .= "</table>";
		
		$html0 = '<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0" class="bodytextArial">
		  <tr class="scriptsubheading" valign="top" align="center">
			<td>&nbsp;</td>
			<td>Active</td>
			<td>Expired</td>
			<td>TOTAL</td>
		  </tr>
		';

		$html0 .= "<tr>
						<td align='center'><strong>Member Totals</strong></td>
						<td align='center' bgcolor='#E8FFE8'><strong>$totalActive</strong></td>
						<td align='center' bgcolor='#FEECCB'><strong>$totalExpired</strong></td>
						<td align='center'><strong>$totalRowTotal</strong></td>
					  </tr>";
		$html0 .= "</table><br/><br/>";
		$html0 .= $html;
		return $html0;
	}


	
	function loadEarningsSummaryDisplayHTML($start_date, $end_date) {
		$result = Dap_Reports::loadEarningsSummary($start_date, $end_date);
		//logToFile("$start_date, $end_date, $merchant_commissions, $publisher_commissions"); 
		$dataFound = false;
		$html = '<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0" class="bodytextArial">
				  <tr class="scriptsubheading" valign="top" align="center">
					<td>Id</td>
					<td>Product<br/>Name</td>
					<td nowrap>No. of<br/>Sales</td>
					<td>Sales Amt</td>
					<td nowrap>No. of<br/>Refunds</td>
					<td>Refund Amt</td>
					<td>Net Amt</td>
				  </tr>
				';
		$grandProduct_total_sales = 0;
		$grandProduct_total_refunds = 0;
		$grandProduct_net_sales = 0;
		$grandNum_sales = 0;
		$grandNum_refunds = 0;
		foreach($result as $element) {
			$dataFound = true;
			$grandProduct_total_sales += $element['product_total_sales'];
			$grandProduct_total_refunds += $element['product_total_refunds'];
			$grandProduct_net_sales += $element['product_net_sales'];
			$grandNum_sales += $element['num_sales'];
			$grandNum_refunds += $element['num_refunds'];
			//$grandTotal_transactions += $element['total_transactions'];
			
			$element["name"] = stripslashes($element["name"]);
			$html .= "<tr>
						<td><a href='/dap/admin/addEditProducts.php?productId=".$element['id']."'>".$element['id']."</a></td>
						<td>".$element['name']."</td>
						<td align='right'>".$element['num_sales']."</td>
						<td align='right'>".number_format($element['product_total_sales'], 2, '.', '')."</td>
						<td align='right'>".$element['num_refunds']."</td>
						<td align='right'>".number_format($element['product_total_refunds'], 2, '.', '')."</td>
						<td align='right'>".number_format($element['product_net_sales'], 2, '.', '')."</td>
					  </tr>";
		}
		
		if($dataFound != false) {
			$html .= "<tr bgColor='#EFEFEF' class='scriptsubheading'>
						<td colspan='2' align='right'>TOTALS</td>
						<td align='right'>".$grandNum_sales."</td>
						<td align='right'>".number_format($grandProduct_total_sales, 2, '.', '')."</td>
						<td align='right'>".$grandNum_refunds."</td>
						<td align='right'>".number_format($grandProduct_total_refunds, 2, '.', '')."</td>
						<td align='right'><strong>".number_format($grandProduct_net_sales, 2, '.', '')."</strong></td>
					  </tr>";
		} else {
			$html .= "<tr><td colspan='8'>Sorry, no data found for this period.</td></tr>";
		}
		
		$html .= "</table>";
		return $html;
	}

	function loadMemberStickRateSummaryHTML($start_date, $end_date) {
		
		
		$result = Dap_Reports::loadMemberStickRateSummary($start_date, $end_date);
		//logToFile("$start_date, $end_date, $merchant_commissions, $publisher_commissions"); 
		$dataFound = false;
		$html = '<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0" class="bodytextArial">
				  <tr class="scriptsubheading" valign="top" align="center">
					<td>Id</td>
					<td>Product<br/>Name</td>
					<td nowrap>No. of<br/>Sales</td>
					<td>Sales Amt</td>
					<td nowrap>No. of<br/>Refunds</td>
					<td>Refund Amt</td>
					<td>Net Amt</td>
				  </tr>
				';
		$grandProduct_total_sales = 0;
		$grandProduct_total_refunds = 0;
		$grandProduct_net_sales = 0;
		$grandNum_sales = 0;
		$grandNum_refunds = 0;
		foreach($result as $element) {
			$dataFound = true;
			$grandProduct_total_sales += $element['product_total_sales'];
			$grandProduct_total_refunds += $element['product_total_refunds'];
			$grandProduct_net_sales += $element['product_net_sales'];
			$grandNum_sales += $element['num_sales'];
			$grandNum_refunds += $element['num_refunds'];
			//$grandTotal_transactions += $element['total_transactions'];
			
			$element["name"] = stripslashes($element["name"]);
			$html .= "<tr>
						<td><a href='/dap/admin/addEditProducts.php?productId=".$element['id']."'>".$element['id']."</a></td>
						<td>".$element['name']."</td>
						<td align='right'>".$element['num_sales']."</td>
						<td align='right'>".number_format($element['product_total_sales'], 2, '.', '')."</td>
						<td align='right'>".$element['num_refunds']."</td>
						<td align='right'>".number_format($element['product_total_refunds'], 2, '.', '')."</td>
						<td align='right'>".number_format($element['product_net_sales'], 2, '.', '')."</td>
					  </tr>";
		}
		
		if($dataFound != false) {
			$html .= "<tr bgColor='#EFEFEF' class='scriptsubheading'>
						<td colspan='2' align='right'>TOTALS</td>
						<td align='right'>".$grandNum_sales."</td>
						<td align='right'>".number_format($grandProduct_total_sales, 2, '.', '')."</td>
						<td align='right'>".$grandNum_refunds."</td>
						<td align='right'>".number_format($grandProduct_total_refunds, 2, '.', '')."</td>
						<td align='right'><strong>".number_format($grandProduct_net_sales, 2, '.', '')."</strong></td>
					  </tr>";
		} else {
			$html .= "<tr><td colspan='8'>Sorry, no data found for this period.</td></tr>";
		}
		
		$html .= "</table>";
		return $html;
	}
	
	function loadUniqueMembersDisplayHTML($start_date, $end_date) {
		$result = Dap_Reports::loadUniqueMembers($start_date, $end_date);
		
		//return;
		$dataFound = false;
		$html = '<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0" class="bodytextArial">
				  <tr class="scriptsubheading" valign="top" align="center">
					<td colspan="4">Unique Members Per Product (all-time)</td>
				  </tr>
				  <tr class="scriptsubheading" valign="top" align="center">
					<td>Product</td>
					<td>Active</td>
					<td>Expired</td>
					<td>TOTAL</td>
				  </tr>
				';
		$i = 0;
		$totalActive = 0;
		$totalExpired = 0;
		$totalRowTotal = 0;
		
		foreach($result as $element) {
			logToFile("loadUniqueMembersDisplayHTML"); 
			$dataFound = true;
			$totalActive += $element["active"];
			$totalExpired += $element["expired"];
			$totalRowTotal += $element["rowTotal"];
			
			if($element["active"] == 0) $element["active"] = "";
			if($element["expired"] == 0) $element["expired"] = "";
			if($element["rowTotal"] == 0) $element["rowTotal"] = "";
			
			$element["name"] = htmlentities(stripslashes($element["name"]), ENT_QUOTES, 'UTF-8');
			
			$html .= "<tr>
						<td><a href='/dap/admin/addEditProducts.php?productId=".$element["id"]."'>".$element["name"]."</a></td>
						<td align='right' bgcolor='#E8FFE8'>".$element["active"]."</td>
						<td align='right' bgcolor='#FEECCB'>".$element['expired']."</td>
						<td align='right'>".$element['rowTotal']."</td>
					  </tr>";
			$i++;
		}
		
		if($dataFound != false) {
			$html .= "<tr>
						<td align='right'><strong>TOTALS</strong></td>
						<td align='right'><strong>$totalActive</strong></td>
						<td align='right'><strong>$totalExpired</strong></td>
						<td align='right'><strong>$totalRowTotal</strong></td>
					  </tr>";
		} else {
			$html .= "<tr><td colspan='4'>Sorry, no data found for this period.</td></tr>";
		}
		
		$html .= "</table>";
		
		$html0 = '<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0" class="bodytextArial">
		  <tr class="scriptsubheading" valign="top" align="center">
			<td>&nbsp;</td>
			<td>Active</td>
			<td>Expired</td>
			<td>TOTAL</td>
		  </tr>
		';

		$html0 .= "<tr>
						<td align='center'><strong>Member Totals</strong></td>
						<td align='center' bgcolor='#E8FFE8'><strong>$totalActive</strong></td>
						<td align='center' bgcolor='#FEECCB'><strong>$totalExpired</strong></td>
						<td align='center'><strong>$totalRowTotal</strong></td>
					  </tr>";
		$html0 .= "</table><br/><br/>";
		$html0 .= $html;
		return $html0;
	}


	function loadAffiliateStatsForAdminDisplayHTML($email, $start_date, $end_date) {
		$result = Dap_AffCommissions::loadAffiliateStats($email, $start_date, $end_date);
		$dataFound = false;
		//logToFile(sizeof($result),LOG_DEBUG_DAP);
		$html = '<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0" class="bodytextArial">
				  <tr class="scriptsubheading">
					<td>User Id</td>
					<td>Name</td>
					<td>HTTP Referer</td>
					<td>Destination</td>
					<td>Date/Time</td>
				  </tr>
				';
		foreach($result as $element) {
			$dataFound = true;
			$html .= "<tr class='bodytext'>
						<td><a href='/dap/admin/addEditUsers.php?userId=".$element['user_id']."' target='_blank' title='".$element['email']."'>".$element['user_id']."</a></td>
						<td nowrap>".$element['first_name']. " " .$element['last_name']. " </td>
						<td><a href='".$element['http_referer']."' target='_blank' title=".$element['http_referer'].">".trimString($element['http_referer'],80,45,30)."</a></td>
						<td><a href='".$element['dest_url']."' target='_blank' title=".$element['dest_url'].">".trimString($element['dest_url'],80,45,30)."</a></td>
						<td nowrap>".$element['datetime']." </td>
					  </tr>";
		}
		
		if($dataFound == false) {
			$html .= "<tr><td colspan='5'>Sorry, no data found.</td></tr>";
		}
		$html .= "</table>";
		//logToFile($html,LOG_DEBUG_DAP);
		return $html;
	}
	
	
	function loadEarningsSummaryByMonthDisplayHTML($start_date, $end_date) {
		$result = Dap_Reports::loadEarningsSummaryByMonth($start_date, $end_date);
		//logToFile("$start_date, $end_date, $merchant_commissions, $publisher_commissions"); 
		$dataFound = false;
		$html = '<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0" class="bodytextArial">
				  <tr class="scriptsubheading" valign="top">
					<td>Year</td>
					<td>Month</td>
					<td>Number of Sales</td>
					<td>Net Amt</td>
				  </tr>
				';
		$grandTotal = 0;
		foreach($result as $element) {
			$dataFound = true;
			$grandTotal += $element['total'];
			$html .= "<tr>
						<td>".$element['year']."</td>
						<td>".$element['month']."</td>
						<td align='right'>".$element['numtrans']."</td>
						<td align='right'>".number_format($element['total'], 2, '.', '')."</td>
					  </tr>";
		}
		
		if($dataFound != false) {
			$html .= "<tr>
						<td colspan='2' align='right'><strong>TOTAL</strong></td>
						<td colspan='2' align='right'><strong>".number_format($grandTotal, 2, '.', '')."</strong></td>
					  </tr>";
		} else {
			$html .= "<tr><td colspan='4'>Sorry, no data found for this period.</td></tr>";
		}
		
		$html .= "</table>";
		return $html;
	}

	
	function loadBlogPostsList() {
		$blogPostsHTML = "";
		
		if( file_exists("../dap_permalink_dump.php") ) {
    		$fileContents = file_get_contents("../dap_permalink_dump.php");
			$permalinks = explode("\n", $fileContents);
			foreach ($permalinks as $permalink) {
				if( isset($permalink) && ($permalink != "") ) {
					$permalink = trim($permalink);
					if( substr($permalink, 0, 4) == "----" ) { //Title
						$blogPostsHTML .= "<option value=\"\">" . $permalink . "</option>\n";
					} else {
						$permalinkShort = substr($permalink,strpos($permalink, "/", 7));
						$blogPostsHTML .= "<option value=\"" . $permalink . "\" onDblClick=\"window.open('".$permalink."');\">" . $permalinkShort . "</option>\n";
					}
				}
			}
		}
		return $blogPostsHTML;
	}
	
	
	function loadBlogPostsListHighlightProtected() {
		$blogPostsHTML = "";
		global $productId;
		
		if( file_exists("../dap_permalink_dump.php") ) {
    		$fileContents = file_get_contents("../dap_permalink_dump.php");
			$permalinks = explode("\n", $fileContents);
			foreach ($permalinks as $permalink) {
				if( isset($permalink) && ($permalink != "") ) {
					$permalink = trim($permalink);
					if( substr($permalink, 0, 4) == "----" ) { //Title
						$blogPostsHTML .= "<option value=\"\">" . $permalink . "</option>\n";
					} else {
						$permalinkShort = substr($permalink,strpos($permalink, "/", 7));
						$isProtected = Dap_Product::isResourceProtectedInThisProduct($productId, rtrim($permalinkShort,"/"));
						
						if($isProtected) {
							$blogPostsHTML .= "<option value=\"" . $permalink . "\" onDblClick=\"window.open('".$permalink."');\" style=\"background: #DDDDDD; color: #555555;\">" . $permalinkShort . "</option>\n";
						} else {
							$blogPostsHTML .= "<option value=\"" . $permalink . "\" onDblClick=\"window.open('".$permalink."');\">" . $permalinkShort . "</option>\n";
						}
						
					}
				}
			}
		}
		return $blogPostsHTML;
	}
	
	
	//Given any kind of string, returns file name if exists
	function getFileExtension($fileName) {
		return end(explode(".",$fileName));
	}
	


	function utf8json($inArray) {
	
		static $depth = 0;
	
		/* our return object */
		$newArray = array();
	
		/* safety recursion limit */
		$depth ++;
		if($depth >= '30') {
			return false;
		}
	
		/* step through inArray */
		foreach($inArray as $key=>$val) {
			if(is_array($val)) {
				/* recurse on array elements */
				$newArray[$key] = utf8json($val);
			} else {
				/* encode string values */
				$newArray[$key] = utf8_encode($val);
			}
		}
	
		/* return utf8 encoded array */
		return $newArray;
	} 
	
	
	function loadAffiliatePerformanceSummaryForAdminDisplayHTML($email, $start_date, $end_date) {
		$result = Dap_AffStats::loadAffiliatePerformanceSummary($email, $start_date, $end_date);
		$dataFound = false;
		$html = '<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0" class="bodytextArial">
				  <tr class="scriptsubheading">
					<td>Affiliate Id</td>
					<td>Name</td>
					<td>Email</td>
					<td>Clicks</td>
					<td>Referrals<br/>(Free & Paid)</td>
					<td>Cash Earned</td>
					<td>Credits Earned</td>
					<td>Sales Generated</td>
					<td>Earnings Per Click<br/>(EPC) Cash</td>
				  </tr>
				';
		foreach($result as $element) {
			$dataFound = true;
			$epc = "";
			if(intval($element['clicks']) != 0) {
				$epc = number_format(intval($element['amt_earned_cash'])/intval($element['clicks']), 2, '.', '');
			}
			
			if($epc == 0.00) $epc = "";
			
			$html .= "<tr onmouseover=\"this.className='highlightedRow'\" onmouseout=\"this.className='nonHighlightedRowWhite'\">
						<td><a href='/dap/admin/addEditUsers.php?userId=".$element['id']."' target='_blank' title='".$element['email']."'>".$element['id']."</a></td>
						<td>".$element['first_name']. " " .$element['last_name']. "</td>
						<td>".$element['email']."</td>
						<td>".$element['clicks']."</td>
						<td>".$element['leads']."</td>
						<td>".$element['amt_earned_cash']." </td>
						<td>".$element['amt_earned_credits']." </td>
						<td>".$element['sales']." </td>
						<td>".$epc."</td>
					  </tr>";
		}
		
		if($dataFound == false) {
			$html .= "<tr><td colspan='9'>Sorry, no data found.</td></tr>";
		}
		$html .= "</table>";
		//logToFile($html,LOG_DEBUG_DAP);
		return $html;
	}
	

	function loadAffiliatePerformanceSummaryForAffiliateDisplayHTML($email, $start_date, $end_date) {
		$result = Dap_AffStats::loadAffiliatePerformanceSummary($email, $start_date, $end_date);
		$dataFound = false;
		$html = '<table width="900" border="1" align="center" cellpadding="10" cellspacing="0" class="bodytextArial">
				  <tr class="scriptsubheading">
					<td>Affiliate Id</td>
					<td>Name</td>
					<td>Email</td>
					<td>Clicks</td>
					<td>Referrals<br/>(Free & Paid)</td>
					<td>Cash Earned</td>
					<td>Credits Earned</td>
					<td>Sales Generated</td>
					<td>Earnings Per<br/>Click (EPC)</td>
				  </tr>
				';
		foreach($result as $element) {
			$dataFound = true;
			$clicks = (intval($element['clicks']) == 0) ? 1 : intval($element['clicks']);
			$html .= "<tr class='bodytext'>
						<td><a href='/dap/admin/addEditUsers.php?userId=".$element['id']."' target='_blank' title='".$element['email']."'>".$element['id']."</a></td>
						<td>".$element['first_name']. " " .$element['last_name']. "</td>
						<td>".$element['email']."</td>
						<td>".$element['clicks']."</td>
						<td>".$element['leads']."</td>
						<td>".$element['amt_earned_cash']." </td>
						<td>".$element['amt_earned_credits']." </td>
						<td>".$element['sales']." </td>
						<td>".number_format(intval($element['amt_earned_cash'])/$clicks, 2, '.', '')."</td>
					  </tr>";
		}
		
		if($dataFound == false) {
			$html .= "<tr><td colspan='8'>Sorry, no data found.</td></tr>";
		}
		$html .= "</table>";
		//logToFile($html,LOG_DEBUG_DAP);
		return $html;
	}
	
	function decryptPassword($encrypted) {
		//logToFile("decryptPassword1: Incoming password: " . $encrypted); 
		$decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(ENCKEY), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5(ENCKEY))), "\0");
		//logToFile("decryptPassword2: Decrypted password: " . $decrypted); 
		
		if($encrypted != encryptPassword($decrypted) ) {
			//logToFile("This user still has unencrypted pass in db. So returning original back"); 
			return trim($encrypted);
		}
		
		return trim($decrypted);
	}

	function encryptPassword($decrypted) {
		//logToFile("encryptPassword: Incoming password: $decrypted"); 
		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(ENCKEY), $decrypted, MCRYPT_MODE_CBC, md5(md5(ENCKEY))));
		//logToFile("setPassword2: Encrypted password: " . $encrypted); 
		return trim($encrypted);
	}	
	
	
	function getCSVReportNamesFromBulkFolder($filename1,$filename2="") {
		// create an array to hold directory list
		
		$csvname="report";
		$csvPath = DAP_ROOT."/".BULKFOLDER."/";
		
		$results = array();
		
		// create a handler for the directory
	
		$handler = opendir($csvPath);
		
		$reportListHTML  = "";
		$files = array();
		// open directory and walk through the filenames
		logToFile("getCSVReportNamesFromBulkFolder: filename1=".$filename1); 
		logToFile("getCSVReportNamesFromBulkFolder: handler=".$handler); 
		while ($file = readdir($handler)) {
			//logToFile("getCSVReportNamesFromBulkFolder: readdir"); 
			// if file isn't this directory or its parent, add it to the results
			if ($file != "." && $file != "..") {
				// check with regex that the file format is what we're expecting and not something else
				
				//if( (stristr($file,$filename1)!=FALSE) || ( ($filename!="") && (stristr($file,$filename2)!=FALSE)) ) {
				if(stristr($file,$filename1)!=FALSE ) {
					// add to our file array for later use
					$files[] = $file; // put in array.
					//logToFile("getCSVReportNamesFromBulkFolder: file=".$file); 
					
					//$files[filemtime($file)] = $file;
				}
			}
		}
		
		//usort($files); // sort.
		//array_reverse($files,TRUE);
		//foreach($files as $file) {
			//$reportListHTML .= "<option value=\"" . $file . "\">" . $file . "</option>\n";
		//}
		
		logToFile("getCSVReportNamesFromBulkFolder: count=".count($files)); 
		
		$i= count($files);
		$i--;
		
		while ($i>=0) {
			if($i==(count($files)-1))
				$reportListHTML .= "<option selected value=\"" . $files[$i] . "\">" . $files[$i] . "</option>\n";
			else 
				$reportListHTML .= "<option value=\"" . $files[$i] . "\">" . $files[$i] . "</option>\n";
			$i--;
		}
		
		logToFile("getCSVReportNamesFromBulkFolder: return"); 
		
		return $reportListHTML;

	}
	
	
	function generateDAPToWPSyncIssueReport ($dapEmail, $folderName) {
		logToFile("functions_admin.php : generateDAPToWPSyncIssueReport(): About to generate report"); 
			
		$user = Dap_User::loadUserByEmail($dapEmail);
		$dapUsername = $user->getUser_name();
		
		$userarray = array();
		$username=$dapUsername;
		
		
		$lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
		
		if($folderName=="")
			require_once($lldocroot."/".'wp-config.php');
		else
			require_once($lldocroot."/".$folderName.'/wp-config.php');
		
		//Sync DAP User Data to WordPress
		if( (Dap_Config::get("DAP_WP_SYNC")!="Y")  ) {
		   $errmsg="<p style='color:red;font-size:12px;'><strong>YOU HAVE NOT ENABLED WP SYNCING IN DAP SETUP -> CONFIG SECTION. <br><br>IF YOU WANT TO SYNC USERS TO WP, MAKE SURE TO ENABLE 'SYNC DAP USER DATA TO WP' IN DAP SETUP=>CONFIG=>WORDPRESS-RELATED SECTION.</strong></p>";
		   return $errmsg;		 
		}
		
		if( (Dap_Config::get("WP_SYNC_PAID_ONLY")=="Y") ) {
			
			if( ! $user->isPaidUser() ) {
				$errmsg="<p style='color:red;font-size:12px;'><strong>SORRY, CANNOT SYNC USER. YOU HAVE SET DAP TO ONLY SYNC USERS OF PAID PRODUCTS TO WP. BUT THIS USER ONLY HAS ACCESS TO FREE PRODUCTS. IF YOU WANT TO SYNC ALL REGISTERED MEMBERS TO WP, MAKE SURE TO DISABLE 'SYNC PAID USERS ONLY TO WP' IN DAP SETUP=>CONFIG=>WORDPRESS-RELATED SECTION.</strong></p>";
				return $errmsg;
			}
			//Sync only paid users is true - disable everyone else, except any WP admins
			//logToFile("Not a paid user"); 
			if ( username_exists($dapUsername) || email_exists($user->getEmail()) ) { //just do an update
				$id = username_exists($dapUsername);
				$emailfound=false;
				if(!$id) {
					$id = email_exists($user->getEmail());
					$emailfound=true;
				}
				
				$wp_user_info = get_userdata($id );
				$wp_username=$wp_user_info->user_login;
				$wp_email=$wp_user_info->user_email;
				$wppassword=$wp_user_info->user_pass;
				$dappasswordclear=$user->getPassword();
				
				$passwordcheck="PASSED";
				
				if(!wp_check_password( $dappasswordclear, $wppassword, $id )) {
					$passwordcheck="FAILED";
				}
				
				if ( isUserToBeSyncedAWPAdmin ( $id ) ){
					$errmsg="CANNOT SYNC WP ADMIN USER";
				}
				else if($emailfound==true) {
					if($passwordcheck=="FAILED")
						$errmsg="DAP EMAIL AND WP EMAIL MATCH BUT USERNAME DOES NOT MATCH. ALSO PASSWORD DOES NOT MATCH. FIX: 1) SET DAP USERNAME TO MATCH THE WP USERNAME.  2) THEN PUSH THE DAP PASSWORD TO WP.";
					else
						$errmsg="DAP EMAIL AND WP EMAIL MATCH BUT USERNAME DOES NOT MATCH. FIX: SET DAP USERNAME TO MATCH WP USERNAME.";
				}
				else {
					if($passwordcheck=="FAILED")
						$errmsg="DAP USERNAME AND WP USERNAME MATCH BUT EMAIL DOES NOT MATCH. ALSO PASSWORD DOES NOT MATCH. FIX: SET WP EMAIL / PASSWORD TO MATCH DAP EMAIL / PASSWORD.";
					else
						$errmsg="DAP USERNAME AND WP USERNAME MATCH BUT EMAIL DOES NOT MATCH. FIX: SET WP EMAIL TO MATCH DAP EMAIL";
				}
			
				
				//Now check if the WP user we just found has WP admin role.
				//If yes, then DO NOT disable even though he may be free user
				//$user = new WP_User( $user_id );
				

			} 
			else {
				$errmsg="NO SUCH USER IN WP. NEW USER. SYNC PAID DAP USER TO WP";
			}
		} else {
			//Sync everybody
			//Check if username also exists in WP
			//logToFile("About to Sync User now");
			$id = 0;
			
			/**
				This is the case where DAP username is blank
				So update as usual
			*/
			
			if( ($username!="") && ( username_exists($username) ) ) {   //just do an update
				//logToFile("Username $username exists in WP, so just doing an update");
				$userarrayupd = array();
				$id = username_exists($username);
				
				
				$wp_user_info = get_userdata($id );
				$wp_username=$wp_user_info->user_login;
				$wp_email=$wp_user_info->user_email;
				$wppassword=$wp_user_info->user_pass;
				$dappasswordclear=$user->getPassword();
				$passwordcheck="PASSED";
				$dapemail=$user->getEmail();
				if($wp_email!=$dapemail)
					$emailmatch="WP EMAIL DOES NOT MATCH THE DAP EMAIL. ";				
				if(!wp_check_password( $dappasswordclear, $wppassword, $id )) {
					$passwordcheck="FAILED";
				}
				
				if ( isUserToBeSyncedAWPAdmin ( $id ) ){
					$errmsg="CANNOT SYNC WP ADMIN USER";
				}
				else if($passwordcheck=="FAILED")
					$errmsg=$emailmatch."DAP PASSWORD DOES NOT MATCH WP PASSWORD. SYNC DAP USER TO WP BY CLICKING ON THE SYNC BUTTON ABOVE.";
				else {
					if($emailmatch!="")
						$errmsg=$emailmatch."SYNC DAP USER TO WP BY CLICKING ON THE SYNC BUTTON ABOVE.";
					else
						$errmsg="EVERYTHING LOOKS FINE. YOU CAN CLICK ON THE 'SYNC USER TO WP' BUTTON ABOVE TO RE-SYNC USER TO WP.";
				}
			} 
			else { 
				// dap username is empty 
				if ($username=="")  {   //just do an update
					//New User in WP - First time sync
					//logToFile("Username $username does not exist in WP, so doing an insert");
					//logToFile("New user"); 
					//logToFile("username: $username"); 
					if( email_exists($user->getEmail()) ) {
						
						$id = email_exists($user->getEmail());
						
						$wp_user_info = get_userdata($id );
						$wp_username=$wp_user_info->user_login;
						$wp_email=$wp_user_info->user_email;
						$wppassword=$wp_user_info->user_pass;
						$dappasswordclear=$user->getPassword();
						$passwordcheck="PASSED";
						
						if(!wp_check_password( $dappasswordclear, $wppassword, $id )) {
							$passwordcheck="FAILED";
						}
						
						
						if ( isUserToBeSyncedAWPAdmin ( $id ) ){
							$errmsg="CANNOT SYNC WP ADMIN USER";
						}
						else if($passwordcheck=="FAILED") {
							$errmsg="DAP USERNAME MISSING. ALSO THE DAP PASSWORD DOES NOT MATCH WP PASSWORD. FIX: SET DAP USERNAME TO MATCH THE WP USERNAME. ALSO SYNC DAP PASSWORD TO WP.";
						}
						else {
							$errmsg="DAP USERNAME MISSING.  FIX: SET DAP USERNAME TO MATCH THE WP USERNAME.";
						}
					}
					else 					
						$errmsg="NO SUCH USER IN WP. NEW USER. SYNC TO WP";
					
					
				} //if 
				else 	//dap username found but not in WP				
					$errmsg="NO SUCH USER IN WP. NEW USER. SYNC TO WP";
					
				
			}//else 

		}	//else Sync everybody
		
		$temp =  "<p style='font-size:12px'><strong> DAP USER NAME: </strong>" . $username . "</p>";
	
		$temp .=  "<p style='font-size:12px'><strong> DAP EMAIL: </strong>" . $user->getEmail() . "</p>";
		$temp .=  "<p style='font-size:12px'><strong> WP USER NAME: </strong>" . $wp_username . "</p>";
		$temp .=  "<p style='font-size:12px'><strong> WP EMAIL: </strong>" . $wp_email . "</p>";
		$temp .=  "<p style='font-size:12px'><strong> PASSWORD CHECK: </strong>" . $passwordcheck . "</p>";
		$temp .=  "<p style='font-size:14px;color:red;'><strong> SYNC ACTION: </strong>" . $errmsg . "</p>";
		
		$temp .= "<p style='font-size:14px;color:red;'><strong>YOU CAN CLICK ON THE 'SYNC USER TO WP' BUTTON ABOVE TO RE-SYNC USER TO WP</strong></p>";
		
		if( (Dap_Config::get("WP_SYNC_PAID_ONLY")=="Y") ) {
				$temp .="<br><p style='color:red;font-size:12px;'><strong>PLEASE NOTE: YOU HAVE SET DAP TO ONLY SYNC USERS OF PAID PRODUCTS TO WP. IF YOU WANT TO SYNC ALL REGISTERED MEMBERS TO WP, MAKE SURE TO DISABLE 'SYNC PAID USERS ONLY TO WP' IN DAP SETUP=>CONFIG=>WORDPRESS-RELATED SECTION.</strong></p>";
		}
		
		
		return $temp;
		
	}
	
	
	function syncUserFromDAPToWP($dapEmail, $folderName) {
	//logToFile("About to insert/update"); 
			
			$user = Dap_User::loadUserByEmail($dapEmail);
			$dapUsername = $user->getUser_name();
			
			$userarray = array();
			$username=$dapUsername;
			$userarray['user_login'] = $dapUsername;
			if($dapUsername=="")
				$dapUsername=
			$userarray['display_name'] = $dapUsername;  
			$userarray['user_email'] = $dapEmail;
			$userarray['user_pass'] = $user->getPassword();                 
			$userarray['first_name'] = $user->getFirst_name();
			$userarray['last_name'] = $user->getLast_name(); 
			$userarray['user_nicename'] = $user->getFirst_name() . $user->getLast_name();  
			
			$lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
			
			if($folderName=="")
				require_once($lldocroot."/".'wp-config.php');
			else
				require_once($lldocroot."/".$folderName.'/wp-config.php');
			
			if( (Dap_Config::get("DAP_WP_SYNC")!="Y")  ) {
		  	  $errmsg="<p style='color:red;font-size:12px;'><strong>YOU HAVE NOT ENABLED WP SYNCING IN DAP SETUP -> CONFIG SECTION. <br><br>IF YOU WANT TO SYNC USERS TO WP, MAKE SURE TO ENABLE 'SYNC DAP USER DATA TO WP' IN DAP SETUP=>CONFIG=>WORDPRESS-RELATED SECTION.</strong></p>";
			  return $errmsg;		 
			}
		
			if( (Dap_Config::get("WP_SYNC_PAID_ONLY")=="Y") ) {
				//Sync only paid users is true - disable everyone else, except any WP admins
				//logToFile("Not a paid user"); 
				if( ! $user->isPaidUser() ) {
					$errmsg="<p style='color:red;font-size:12px;'><strong>SORRY, CANNOT SYNC USER. YOU HAVE SET DAP TO ONLY SYNC USERS OF PAID PRODUCTS TO WP. BUT THIS USER ONLY HAS ACCESS TO FREE PRODUCTS. IF YOU WANT TO SYNC ALL REGISTERED MEMBERS TO WP, MAKE SURE TO DISABLE 'SYNC PAID USERS ONLY TO WP' IN DAP SETUP=>CONFIG=>WORDPRESS-RELATED SECTION.</strong></p>";
					return $errmsg;
				}
				if ( username_exists($dapUsername) || email_exists($user->getEmail()) ) { //just do an update
					$id = username_exists($dapUsername);
					$emailfound=false;
					if(!$id) {
						$id = email_exists($user->getEmail());
						$emailfound=true;
					}
					
					$wp_user_info = get_userdata($id );
					$wp_username=$wp_user_info->user_login;
					$wp_email=$wp_user_info->user_email;
					$wppassword=$wp_user_info->user_pass;
					$dappasswordclear=$user->getPassword();
					
					//Now check if the WP user we just found has WP admin role.
					//If yes, then DO NOT disable even though he may be free user
					//$user = new WP_User( $user_id );
					if ( isUserToBeSyncedAWPAdmin ( $id ) ){
						return 'SORRY, CANNOT SYNC WP ADMIN USERS'; // cannot sync admin user
					}
					
					//Coming here means user is not paid user, and needs to be disabled
					//So just updating role to blank, so user cannot use anything in WP
					//that needs user to be logged in - like forums, commenting, etc
					//for when user was previously eligible and now not eligible (eg., because of a refund)
					//logToFile("Updating to nothing...");
					
					else if($emailfound==true) {
						  $user->setUser_name($wp_username);
						  $user->update();
					}
					
					
					$userarray['ID'] = $id;
					$userarray['role'] = ""; //set role to blank
					wp_set_current_user($id, null);
					wp_update_user($userarray);
					
					
	//1 - email matches between dap/wp. Set dap username to match WP username
	//2- username matches between dap/wp but not email. Sync DAP user to WP.
	
					if($emailfound==true) {
						return "<p style='color:red;font-size:12px;'><strong>DAP EMAIL MATCHED WP EMAIL. UPDATED DAP USERNAME TO MATCH WP USERNAME=".$wp_user_info->user_login. ". ALSO SYNC'D USER=".$user->getEmail()."  FROM DAP => WP</strong></p>"; // 4- updated dap username using wp username
						//return 1;
					}
					else {
						return "<p style='color:red;font-size:12px;'><strong>DAP USERNAME MATCHED WP USERNAME. EMAIL DIDNOT MATCH. SUCCESSFULLY SYNC'D THE USER FROM DAP => WP</strong></p>"; // 4- updated dap username using wp username
						//return 2;
					}
				} 
				else {
					//username / email not found in WP.. NEW USER
					$userarray['role'] = "";
					if ($user->isPaidUser()) {
						$userarray['role'] = Dap_Config::get("WP_DEF_ROLE_PAID");
					} else {
						$userarray['role'] = Dap_Config::get("WP_DEF_ROLE_FREE");
					}
					
					$userarray['role'] = ($userarray['role'] == "") ? "subscriber" : $userarray['role']; //default to subscriber for v<=3.9
					wp_insert_user($userarray); //otherwise create			
					
					return "<p style='color:red;font-size:12px;'><strong>Created NEW USER in WP FOR DAP PAID USER = " . $user->getEmail() . "</strong></p>"; // 6- new user in WP	
				}
				
			} else {
				//Sync everybody
				//Check if username also exists in WP
				//logToFile("About to Sync User now");
				$id = 0;
				
				/**
					This is the case where DAP username is blank
					So update as usual
				*/
				
				if( ($username!="") && ( username_exists($username) ) ) {   //just do an update
					//logToFile("Username $username exists in WP, so just doing an update");
					$userarrayupd = array();
					$id = username_exists($username);
					if ( isUserToBeSyncedAWPAdmin ( $id ) ){
						return -1; // cannot sync admin user
					}
					
					
					$userarrayupd['ID'] = $id;
					$userarrayupd['user_login'] = $username;
					$userarrayupd['user_pass'] = $user->getPassword();                 
					$userarrayupd['user_email'] = $user->getEmail();
					$userarrayupd['first_name'] = $user->getFirst_name();
					$userarrayupd['last_name'] = $user->getLast_name();
		
					wp_update_user($userarrayupd);
					return "<p style='color:red;font-size:12px;'><strong>DAP USERNAME MATCHES THE WP USERNAME. SUCCESSFULLY SYNC'D THE DAP USER = ".$user->getEmail(). " TO WP</strong></p>"; // 3
//					return 3;
					
				} 
				
				else { 
					// dap username is empty 
					if ($username=="")  {   //just do an update
						//New User in WP - First time sync
						//logToFile("Username $username does not exist in WP, so doing an insert");
						//logToFile("New user"); 
						//logToFile("username: $username"); 
						if( email_exists($user->getEmail()) ) {
							
							$id = email_exists($user->getEmail());
							if ( isUserToBeSyncedAWPAdmin ( $id ) ){
								return -1; // cannot sync admin user
							}
							
							$wp_user_info = get_userdata($id );
							//update dap user
							$user->setUser_name($wp_user_info->user_login);
							$user->update();
							
							//check password match
							$wppassword=$wp_user_info->user_pass;
							$dappasswordclear=$user->getPassword();
							
							
							//sync dap password to WP
							
							$userarrayupd['ID'] = $id;
							$userarrayupd['user_login'] = $wp_user_info->user_login;
							$userarrayupd['user_pass'] = $user->getPassword();                 
							$userarrayupd['user_email'] = $user->getEmail();
							$userarrayupd['first_name'] = $user->getFirst_name();
							$userarrayupd['last_name'] = $user->getLast_name();
	
							wp_update_user($userarrayupd);
								
						 	//return 4; // updated dap username using wp username
							return "<p style='color:red;font-size:12px;'><strong>DAP USERNAME WAS EMPTY. UPDATED DAP USERNAME TO MATCH WP USERNAME=".$wp_user_info->user_login. ". ALSO SYNC'D USER=".$user->getEmail()."  FROM DAP => WP</strong></p>"; // 4- updated dap username using wp username
						}
						
						//new user in WP
						if($username=="") {
							$uname=generateUsername("DAPDoctor: Sync: functions_admin.php",$user->getEmail(),$user->getFirst_name(),$user->getLast_name());
							$user->setUser_name($uname);	
							$user->update();
							$userarray["user_login"]=$uname;
						}
						
						$userarray['role'] = "";
						if ($user->isPaidUser()) {
							$userarray['role'] = Dap_Config::get("WP_DEF_ROLE_PAID");
						} else {
							$userarray['role'] = Dap_Config::get("WP_DEF_ROLE_FREE");
						}
						
						$userarray['role'] = ($userarray['role'] == "") ? "subscriber" : $userarray['role']; //default to subscriber for v<=3.9
						wp_insert_user($userarray); //otherwise create			
						
						return "<p style='color:red;font-size:12px;'><strong>Created NEW USER in WP FOR DAP USER = " . $user->getEmail(). "</strong></p>"; // 5- new user in WP
					}
					else {
						//username in DAP but not in WP
						$userarray['role'] = "";
						if ($user->isPaidUser()) {
							$userarray['role'] = Dap_Config::get("WP_DEF_ROLE_PAID");
						} else {
							$userarray['role'] = Dap_Config::get("WP_DEF_ROLE_FREE");
						}
						
						$userarray['role'] = ($userarray['role'] == "") ? "subscriber" : $userarray['role']; //default to subscriber for v<=3.9
						wp_insert_user($userarray); //otherwise create			
						
						return "<p style='color:red;font-size:12px;'><strong>Created NEW USER in WP FOR DAP USER = " . $user->getEmail() . "</strong></p>"; // 5- new user in WP	
					}
					
				}

			}			
	}
	
	//1 - email matches between dap/wp. Set dap username to match WP username
	//2- username matches between dap/wp but not email. Sync DAP user to WP.
	//3 - sync all users enabled, username matches, simply sync DAP->WP
	//4 - dapusername empty - set it to match wp username. Also sync DAP->WP
	//5- new user 
	
?>