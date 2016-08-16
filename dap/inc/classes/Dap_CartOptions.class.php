<?php

class Dap_CartOption {
   var $product_id;
	var $showCouponCode;
	var $howDidYouHear;
   var $showShipAddress;
   var $showTAndC;
	var $termsAndConditions;
   var $rTAndC;
	var $rPhone;
	var $rFax;
	var $rAddress2;
	var $choosepassword;
	var $requireBillingInfoForPaypalCheckout;
	var $customFields;
	var $rCompany;
	var $showComments;
	var $cartHeader;
	var $cartFooter;
	var $submitOrderImage;
	
	function getProduct_id() {
		return $this->product_id;
	}
	function setProduct_id($o) {
		$this->product_id = $o;
	}

	function getShowCouponCode() {
		return $this->showCouponCode;
	}
	function setShowCouponCode($o) {
		$this->showCouponCode = $o;
	}
	
	function getHowDidYouHear() {
		return $this->howDidYouHear;
	}
	function setHowDidYouHear($o) {
		$this->howDidYouHear = $o;
	}

	function getShowShipAddress() {
		return $this->showShipAddress;
	}
	function setShowShipAddress($o) {
		$this->showShipAddress = $o;
	}

	function getShowTAndC() {
		return $this->showTAndC;
	}
	function setShowTAndC($o) {
		$this->showTAndC = $o;
	}

	function getTAndC() {
		return $this->termsAndConditions;
	}
	function setTAndC($o) {
		$this->termsAndConditions = $o;
	}
	
	function getRTAndC() {
		return $this->rTAndC;
	}
	function setRTAndC($o) {
		$this->rTAndC = $o;
	}

	function getRPhone() {
		return $this->rPhone;
	}
	function setRPhone($o) {
		$this->rPhone = $o;
	}

	function getRFax() {
		return $this->rFax;
	}
	function setRFax($o) {
		$this->rFax = $o;
	}
	
	function getRAddress2() {
		return $this->rAddress2;
	}
	function setRAddress2($o) {
		$this->rAddress2 = $o;
	}
	
	function getChoosePassword() {
		return $this->choosepassword;
	}
	function setChoosePassword($o) {
		$this->choosepassword = $o;
	}
	
	function getRequireBillingInfoForPaypalCheckout() {
		return $this->requireBillingInfoForPaypalCheckout;
	}
	function setRequireBillingInfoForPaypalCheckout($o) {
		$this->requireBillingInfoForPaypalCheckout = $o;
	}
	
	
	function getCustomFields() {
		return $this->customFields;
	}
	function setCustomFields($o) {
		$this->customFields = $o;
	}
	
	function getRCompany() {
		return $this->rCompany;
	}
	function setRCompany($o) {
		$this->rCompany = $o;
	}

	function getCartHeader() {
		return $this->cartHeader;
	}
	function setCartHeader($o) {
		$this->cartHeader = $o;
	}
	
	function getCartFooter() {
		return $this->cartFooter;
	}
	function setCartFooter($o) {
		$this->cartFooter = $o;
	}
	
	function getSubmitOrderImage() {
		return $this->submitOrderImage;
	}
	function setSubmitOrderImage($o) {
		$this->submitOrderImage = $o;
	}

	function getShowComments() {
		return $this->show_comments;
	}
	function setShowComments($o) {
		$this->show_comments = $o;
	}



	public function create() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
		
	
			$sql = "insert into dap_cart
					(product_id, show_coupon, show_howdidyouhearboutus, show_shiptoaddress, show_tandc, tandc, require_tandc_acceptance, request_phone, request_fax, request_address2, require_billing_for_paypal, choose_password, custom_fields, request_company, show_comments, cart_header, cart_footer, checkout_submit_image_url)
					values
					(:product_id, :showCouponCode, :howDidYouHear, :showShipAddress, :showTAndC, :termsAndConditions, :rTAndC, :rPhone, :rFax, :rAddress2, :requireBillingInfoForPaypalCheckout, :choosepassword, :customFields, :rCompany, :showComments, :cartHeader, :cartFooter, :submitOrderImage)";
					
			$stmt = $dap_dbh->prepare($sql);
		
			if ($this->getProduct_id() == "GENERAL SETTINGS") 
				$this->setProduct_id(-1);
				
			$stmt->bindParam(':product_id', $this->getProduct_id(), PDO::PARAM_STR);
			$stmt->bindParam(':showCouponCode', $this->getShowCouponCode(), PDO::PARAM_STR);
			$stmt->bindParam(':howDidYouHear', $this->getHowDidYouHear(), PDO::PARAM_STR);
			$stmt->bindParam(':showShipAddress', $this->getShowShipAddress(), PDO::PARAM_STR);
			$stmt->bindParam(':showTAndC', $this->getShowTAndC(), PDO::PARAM_STR);
			$stmt->bindParam(':termsAndConditions', $this->getTAndC(), PDO::PARAM_STR);
			$stmt->bindParam(':rTAndC', $this->getRTAndC(), PDO::PARAM_STR);
			$stmt->bindParam(':rPhone', $this->getRPhone(), PDO::PARAM_STR);
			$stmt->bindParam(':rFax', $this->getRFax(), PDO::PARAM_STR);
			$stmt->bindParam(':rAddress2', $this->getRAddress2(), PDO::PARAM_STR);
			$stmt->bindParam(':choosepassword', $this->getChoosePassword(), PDO::PARAM_STR);
			$stmt->bindParam(':requireBillingInfoForPaypalCheckout', $this->getRequireBillingInfoForPaypalCheckout(), PDO::PARAM_STR);

			
			$stmt->bindParam(':customFields', $this->getCustomFields(), PDO::PARAM_STR);
			$stmt->bindParam(':rCompany', $this->getRCompany(), PDO::PARAM_STR);
			$stmt->bindParam(':showComments', $this->getShowComments(), PDO::PARAM_STR);
			$stmt->bindParam(':cartHeader', $this->getCartHeader(), PDO::PARAM_STR);
			$stmt->bindParam(':cartFooter', $this->getCartFooter(), PDO::PARAM_STR);
			$stmt->bindParam(':submitOrderImage', $this->getSubmitOrderImage(), PDO::PARAM_STR);
			
			$stmt->execute();
			
			logToFile("Calling Dap_CartOptions.class.php: getProduct_id" . $this->getProduct_id(),LOG_DEBUG_DAP);
			logToFile("Calling Dap_CartOptions.class.php: getShowCouponCode" . $this->getShowCouponCode(),LOG_DEBUG_DAP);
			logToFile("Calling Dap_CartOptions.class.php: getHowDidYouHear" . $this->getHowDidYouHear(),LOG_DEBUG_DAP);	
			logToFile("Calling Dap_CartOptions.class.php: getShowShipAddress" . $this->getShowShipAddress(),LOG_DEBUG_DAP);
			
			return $dap_dbh->lastInsertId();
			$stmt = null;
			$dap_dbh = null;

		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}

	public function update() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
											
			$sql = "update dap_cart set
						show_coupon = :showCouponCode,
						show_howdidyouhearboutus = :howDidYouHear,
						show_shiptoaddress = :showShipAddress,
						show_tandc = :showTAndC,
						tandc = :termsAndConditions,
						require_tandc_acceptance = :rTAndC,
						request_phone = :rPhone,
						request_fax = :rFax,
						request_address2 = :rAddress2,
						choose_password = :choosepassword,
						require_billing_for_paypal = :requireBillingInfoForPaypalCheckout,
						custom_fields = :customFields,
						request_company = :rCompany,
						show_comments = :showComments,
						cart_header = :cartHeader,
						cart_footer = :cartFooter,
						checkout_submit_image_url = :submitOrderImage
					where
						product_id = :productId";

			logToFile("HERE in Dap_CartOptions update");
			
			if ($this->getProduct_id() == "GENERAL SETTINGS") 
				$this->setProduct_id(-1);
			
			
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':showCouponCode', $this->getShowCouponCode(), PDO::PARAM_STR);
			$stmt->bindParam(':howDidYouHear', $this->getHowDidYouHear(), PDO::PARAM_STR);
			$stmt->bindParam(':showShipAddress', $this->getShowShipAddress(), PDO::PARAM_STR);
			$stmt->bindParam(':showTAndC', $this->getShowTAndC(), PDO::PARAM_STR);
			$stmt->bindParam(':termsAndConditions', $this->getTAndC(), PDO::PARAM_STR);
			$stmt->bindParam(':rTAndC', $this->getRTAndC(), PDO::PARAM_STR);
			$stmt->bindParam(':rPhone', $this->getRPhone(), PDO::PARAM_STR);
			$stmt->bindParam(':rFax', $this->getRFax(), PDO::PARAM_STR);
			$stmt->bindParam(':rAddress2', $this->getRAddress2(), PDO::PARAM_STR);
			$stmt->bindParam(':choosepassword', $this->getChoosePassword(), PDO::PARAM_STR);
			$stmt->bindParam(':requireBillingInfoForPaypalCheckout', $this->getRequireBillingInfoForPaypalCheckout(), PDO::PARAM_STR);

			
			$stmt->bindParam(':customFields', $this->getCustomFields(), PDO::PARAM_STR);
			$stmt->bindParam(':rCompany', $this->getRCompany(), PDO::PARAM_STR);
			$stmt->bindParam(':showComments', $this->getShowComments(), PDO::PARAM_STR);
			$stmt->bindParam(':cartHeader', $this->getCartHeader(), PDO::PARAM_STR);
			$stmt->bindParam(':cartFooter', $this->getCartFooter(), PDO::PARAM_STR);
			$stmt->bindParam(':submitOrderImage', $this->getSubmitOrderImage(), PDO::PARAM_STR);
			
			$stmt->bindParam(':productId', $this->getProduct_id(), PDO::PARAM_STR);

			logToFile("Before exec in Dap_CartOptions update, productId=" . $this->getProduct_id());
			
			logToFile("Before exec in Dap_CartOptions update, cartHeader=" . $this->getCartHeader());
			
			$stmt->execute();
			$stmt = null;
			$dap_dbh = null;

			return;

		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}

	
	public static function loadProductCartOptions($productId) {
		$dap_dbh = Dap_Connection::getConnection();
		$product = null;

		if ($productId == "GENERAL SETTINGS") 
				$productId = -1;
			
		//Load product details from database
		$sql = "select *
			from
				dap_cart
			where
				product_id = :productId";

		$stmt = $dap_dbh->prepare($sql);
		$stmt->bindParam(':productId', $productId, PDO::PARAM_STR);
		$stmt->execute();

		//echo "sql: $sql<br>"; exit;
		//$result = mysql_query($sql);
		//echo "rows returned: " . mysql_num_rows($result) . "<br>";

		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			//logToFile("Dap_CartOptions.class.php: tandc=".$row["checkout_submit_image_url"],LOG_DEBUG_DAP);
			
			$product = new Dap_CartOption();

			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
		
			$product->setProduct_id( $row["product_id"] );
			$product->setShowCouponCode( stripslashes($row["show_coupon"]) );
			$product->setHowDidYouHear( stripslashes($row["show_howdidyouhearboutus"]) );
			$product->setShowShipAddress( stripslashes($row["show_shiptoaddress"]) );
			$product->setShowTAndC( stripslashes($row["show_tandc"]) );
			$product->setTAndC( stripslashes($row["tandc"]) );
			$product->setRTAndC( stripslashes($row["require_tandc_acceptance"]) );
			$product->setRPhone( $row["request_phone"] );
			$product->setRFax( $row["request_fax"] );
			$product->setRAddress2( $row["request_address2"] );
			$product->setChoosePassword( $row["choose_password"] );
			$product->setRequireBillingInfoForPaypalCheckout( $row["require_billing_for_paypal"] );
			
			$product->setCustomFields( $row["custom_fields"] );
			$product->setRCompany( $row["request_company"] );
			$product->setShowComments( $row["show_comments"] );
			$product->setCartHeader( stripslashes($row["cart_header"]) );
			$product->setCartFooter( stripslashes($row["cart_footer"]) );
			$product->setSubmitOrderImage( $row["checkout_submit_image_url"] );
			
			//echo "id: " . $product->getDescription(); exit;
			//return $product;
		}

		return $product;
	}


} // end class
?>