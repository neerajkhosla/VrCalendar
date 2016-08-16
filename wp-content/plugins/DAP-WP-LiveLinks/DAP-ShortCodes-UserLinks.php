<?php 

add_shortcode('DAPUserLinks', 'dap_userlinks');

function addDAPCSS() {
	$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'] : "http://".$_SERVER['SERVER_NAME'];	
	wp_register_style('dap-css', $url . getCssFullURL());
	//logToFile("getCssFullURL: " . getCssFullURL()); 
	wp_enqueue_style('dap-css');
}

function dap_userlinks($atts, $content=null){ 
	extract(shortcode_atts(array(
		'showproductname' => 'Y',
		'showaccessstartdate' => 'Y',
		'showaccessenddate' => 'Y',
		'showdescription' => 'Y',
		'showlinks' => 'Y',
		'orderoflinks' => 'NEWESTFIRST',
		'howmanylinks' => '10000',		
		'errmsgtemplate' => 'SHORT',
		'productid' => 'ALL',
		'dateformat' => 'MM-DD-YYYY',
		'showproductcount' => 'Y',
		'showrenewalhtml' => 'Y',
		'hideproductid' => 'NONE',
	), $atts));
	
	$content = do_shortcode(dap_clean_shortcode_content($content));	
	
	$session = Dap_Session::getSession();
	$user = $session->getUser();
	
	if( !Dap_Session::isLoggedIn() || !isset($user) ) {
		//logToFile("Not logged in, returning errmsgtemplate");
		$errorHTML = mb_convert_encoding(MSG_PLS_LOGIN, "UTF-8", "auto") . " <a href=\"" . Dap_Config::get("LOGIN_URL") . "\">". mb_convert_encoding(MSG_CLICK_HERE_TO_LOGIN, "UTF-8", "auto") . "</a>";
		return $errorHTML;
	}

	//if( !Dap_Session::isLoggedIn() || !isset($user) ) {
		//logToFile("Not logged in, returning errmsgtemplate");
		//return getErrorMessage($errmsgtemplate);
	//}
	
	$userId = $user->getId();
	$userProducts = null;
	//$content = $content . "<br/><br/>";
	
	if( Dap_Session::isLoggedIn() && isset($user) ) { 
		//get userid
		$userProducts = Dap_UsersProducts::loadProducts($user->getId());
	}

	if($showproductcount == 'Y') {
		$content = USER_LINKS_YOUCURRENTLYHAVEACCESSTO_TEXT . count($userProducts). USER_LINKS_PRODUCTS_TEXT . "<br/><br/>";
	}
	
	//loop over each product from the list
	foreach ($userProducts as $userProduct) {
		
		if($hideproductid != "NONE") {
			$hideProductIdArray = explode(",",$hideproductid);
			if( in_array($userProduct->getProduct_id(), $hideProductIdArray) ) {
				continue;
			}
		}
		
		if($productid != "ALL") {
			$productIdArray = explode(",",$productid);
			if( !in_array($userProduct->getProduct_id(), $productIdArray) ) {
				continue;
			}
		}
		
		$product = Dap_Product::loadProduct($userProduct->getProduct_id());
		
		$expired = false;
		if($user->hasAccessTo($product->getId()) === false) {
			$expired = true;
		}
		
		$content .= '<div class="dap_product_links_table">';
		
		if(strtolower($showproductname) == "y") {
			$content .= '<div class="dap_product_heading">'.$product->getName().'</div>';
		}
		
		if(strtolower($showaccessstartdate) == "y") {
			$oldDate = $userProduct->getAccess_start_date();
			//$middle = strtotime($oldDate);
			$middle = new DateTime($oldDate);
			$stringFormat = "";
			if($dateformat == "MM-DD-YYYY") {
				$stringFormat = "m-d-Y";
			} else if($dateformat == "DD-MM-YYYY") {
				$stringFormat = "d-m-Y";
			}  else if($dateformat == "YYYY-MM-DD") {
				$stringFormat = "Y-m-d";
			}
			//$newDate = date($stringFormat, $middle);
			$newDate = $middle->format($stringFormat);

			$content .= '<div><strong>'.USER_LINKS_ACCESS_START_DATE_TEXT.'</strong>: '.$newDate.'</div>';
		}
		
		if(strtolower($showaccessenddate) == "y") {
			$oldDate = $userProduct->getAccess_end_date();
			//$middle = strtotime($oldDate);
			$middle = new DateTime($oldDate);
			$stringFormat = "";
			if($dateformat == "MM-DD-YYYY") {
				$stringFormat = "m-d-Y";
			} else if($dateformat == "DD-MM-YYYY") {
				$stringFormat = "d-m-Y";
			}  else if($dateformat == "YYYY-MM-DD") {
				$stringFormat = "Y-m-d";
			}
			//$newDate = date($stringFormat, $middle);
			$newDate = $middle->format($stringFormat);
			$highlightCode = "";
			if($expired) {
				$highlightCode = ' class="dap_highlight_bg" ';
			}
			$content .= '<div><strong>'.USER_LINKS_ACCESS_END_DATE_TEXT.'</strong>: <span '.$highlightCode.'>'.$newDate.'</span></div>';
		}
		
		if(strtolower($showdescription) == "y") {
			$content .= '<div><strong>'.USER_LINKS_DESCRIPTION_TEXT.'</strong>: '.$product->getDescription().'</div>';
		}
		
		if( $expired && ($showrenewalhtml == "Y") ) {
			//If user's access to product has expired, then show renewal HTML
			$content .= '<div>'.$product->getRenewal_html().'</div>';
		}

		if(strtolower($showlinks) == "y") {
			$orderBy = "desc";
			//$howmanylinks = intval($howmanylinks) + 1;
			if(strtolower($orderoflinks) == "oldestfirst") {
				$orderBy = "asc";
			}
			if ($product->getSelf_service_allowed() == "N") { 
				$content .= '<div><strong>'.USER_LINKS_LINKS_TEXT.'</strong>: '.$userProduct->getActiveResources($product->getSelf_service_allowed(),$orderBy,$howmanylinks).'</div>';
			}
			else {
				$content .= '<div><strong>'.USER_LINKS_LINKS_TEXT.'</strong>: '.$userProduct->getActiveResources("Y",$orderBy,$howmanylinks).'</div>';
			}
		}
      	
		$content .= '</div>';
		$content .= '<br/><br/>';
	} //end foreach

	return $content;
}


?>