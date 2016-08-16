<?php

class Dap_Product {
	var $id;
	var $name;
	var $description;
	var $error_page_url;
	var $is_recurring;
	var $price;
	var $trial_price;
	var $price_increment;
	var $price_increment_ceil;
	var $quantity;
	var $num_sales;
	var $num_days;
	var $timed_pricing_start_date;
	var $selfservice_start_date;
	var $selfservice_end_date;
	var $total_occur;
	var $recurring_cycle_1;
	var $recurring_cycle_2;
	var $recurring_cycle_3;
	var $status; //Status can be "A": Active, "I": Inactive, "S": Suspended
	var $notification_id;
	var $thankyou_page_url;
	var $thirdPartyEmailIds;
	var $subscribeTo;
	var $unsubscribeFrom;
   	var $sales_page_url;
   	var $self_service_allowed;
	var $is_master;
   	var $product_image_url = "";
	var $credits;
	var $double_optin_subject;
	var $double_optin_body;
	var $thankyou_email_subject;
	var $thankyou_email_body;
	var $logged_in_url;
	var $getContentLevelCredit;
	var $is_free_product;
	var $allow_free_signup;
	//added on 080412 for SSS
	var $short_description = "";
	var $long_description = "";
	var $resell_product = "";
	var $future_content_access = "";
	var $product_image_url_linkedto = "";
	var $recurring_credits = "";
	var $access_expiration_action = "";
	var $access_upon_final_payment = "";
	//08/11
	var $show_buy_link;
	var $buy_link;
	
	function getId() {
		return $this->id;
	}
	function setId($o) {
		$this->id = $o;
	}

	function getName() {
		return $this->name;
	}
	function setName($o) {
		$this->name = $o;
	}

	function getDescription() {
		return $this->description;
	}
	function setDescription($o) {
		$this->description = $o;
	}

	function getError_page_url() {
		return $this->error_page_url;
	}
	function seterror_page_url($o) {
		$this->error_page_url = $o;
	}

	function getIs_recurring() {
		return $this->is_recurring;
	}
	function setIs_recurring($o) {
		$this->is_recurring = $o;
	}

	function getPrice() {
		return $this->price;
	}
	function setPrice($o) {
		$this->price = $o;
	}
	
	function getTrial_price() {
		return $this->trial_price;
	}
	function setTrial_price($o) {
		$this->trial_price = $o;
	}
	
	function getPrice_increment() {
		return $this->price_increment;
	}
	function setPrice_increment($o) {
		$this->price_increment = $o;
	}
	
	function getPrice_increment_ceil() {
		return $this->price_increment_ceil;
	}
	function setPrice_increment_ceil($o) {
		$this->price_increment_ceil = $o;
	}

	function getQuantity() {
		return $this->quantity;
	}
	function setQuantity($o) {
		$this->quantity = $o;
	}
	
	function getNum_sales() {
		return $this->num_sales;
	}
	function setNum_sales($o) {
		$this->num_sales = $o;
	}
	
	function getNum_days() {
		return $this->num_days;
	}
	function setNum_days($o) {
		$this->num_days = $o;
	}
	function getTimed_pricing_start_date() {
		return $this->timed_pricing_start_date;
	}
	function setTimed_pricing_start_date($o) {
		$this->timed_pricing_start_date = $o;
	}
	
	function getSelfservice_start_date() {
		return $this->selfservice_start_date;
	}
	function setSelfservice_start_date($o) {
		$this->selfservice_start_date = $o;
	}
	
	function getSelfservice_end_date() {
		return $this->selfservice_end_date;
	}
	function setSelfservice_end_date($o) {
		$this->selfservice_end_date = $o;
	}
	
	function getTotal_occur() {
		return $this->total_occur;
	}
	function setTotal_occur($o) {
		$this->total_occur = $o;
	}

	function getRecurring_cycle_1() {
		return $this->recurring_cycle_1;
	}
	function setRecurring_cycle_1($o) {
		$this->recurring_cycle_1 = $o;
	}

	function getRecurring_cycle_2() {
		return $this->recurring_cycle_2;
	}
	function setRecurring_cycle_2($o) {
		$this->recurring_cycle_2 = $o;
	}

	function getRecurring_cycle_3() {
		return $this->recurring_cycle_3;
	}
	function setRecurring_cycle_3($o) {
		$this->recurring_cycle_3 = $o;
	}

	function getStatus() {
		return $this->status;
	}
	function setStatus($o) {
		$this->status = $o;
	}

	function getNotification_id() {
		return $this->notification_id;
	}
	function setNotification_id($o) {
		$this->notification_id = $o;
	}

	function getThankyou_page_url() {
		return $this->thankyou_page_url;
	}
	function setThankyou_page_url($o) {
		$this->thankyou_page_url = $o;
	}

	function getSubscribeTo() {
		return $this->subscribeTo;
	}
	function setSubscribeTo($o) {
		$this->subscribeTo = $o;
	}
	
	function getUnsubscribeFrom() {
		return $this->unsubscribeFrom;
	}
	function setUnsubscribeFrom($o) {
		$this->unsubscribeFrom = $o;
	}
	
	function getThirdPartyEmailIds() {
		return $this->thirdPartyEmailIds;
	}
	function setThirdPartyEmailIds($o) {
		$this->thirdPartyEmailIds = $o;
	}
	
	function getSales_page_url() {
		return $this->sales_page_url;
	}
	function setSales_page_url($o) {
		$this->sales_page_url = $o;
	}

	function getSelf_service_allowed() {
		return $this->self_service_allowed;
	}
	function setSelf_service_allowed($o) {
		$this->self_service_allowed = $o;
	}

	function getIs_master() {
		return $this->is_master;
	}
	function setIs_master($o) {
		$this->is_master = $o;
	}

	function getShowBuyLink() {
		return $this->show_buy_link;
	}
	function setShowBuyLink($o) {
		$this->show_buy_link = $o;
	}
	
	function getBuyLink() {
		return $this->buy_link;
	}
	function setBuyLink($o) {
		$this->buy_link = $o;
	}

	function getProduct_image_url() {
		return $this->product_image_url;
	}
	function setProduct_image_url($o) {
		$this->product_image_url = $o;
	}

	function getCredits() {
		return $this->credits;
	}
	function setCredits($o) {
		$this->credits = $o;
	}


	function getDouble_optin_subject() {
		return $this->double_optin_subject;
	}
	function setDouble_optin_subject($o) {
		$this->double_optin_subject = $o;
	}


	function getDouble_optin_body() {
		return $this->double_optin_body;
	}
	function setDouble_optin_body($o) {
		$this->double_optin_body = $o;
	}


	function getThankyou_email_subject() {
		return $this->thankyou_email_subject;
	}
	function setThankyou_email_subject($o) {
		$this->thankyou_email_subject = $o;
	}


	function getThankyou_email_body() {
		return $this->thankyou_email_body;
	}
	function setThankyou_email_body($o) {
		$this->thankyou_email_body = $o;
	}
	
	
	function getLogged_in_url() {
		return $this->logged_in_url;
	}
	function setLogged_in_url($o) {
		$this->logged_in_url = $o;
	}


	function getAllowContentLevelCredits() {
		return $this->allowContentLevelCredits;
	}
	function setAllowContentLevelCredits($o) {
		$this->allowContentLevelCredits = $o;
	}
	
	
	function getIs_free_product() {
		return $this->is_free_product;
	}
	function setIs_free_product($o) {
		$this->is_free_product = $o;
	}
	
	
	function getAllow_free_signup() {
		return $this->allow_free_signup;
	}
	function setAllow_free_signup($o) {
		$this->allow_free_signup = $o;
	}
	
	function getRecurringCredits() {
		return $this->recurring_credits;
	}
	function setRecurringCredits($o) {
		$this->recurring_credits = $o;
	}
	
	function getShortDescription() {
		return $this->short_description;
	}
	function setShortDescription($o) {
		$this->short_description = $o;
	}
	
	function getLongDescription() {
		return $this->long_description;
	}
	function setLongDescription($o) {
		$this->long_description = $o;
	}
	
	function getResellProduct() {
		return $this->resell_product;
	}
	function setResellProduct($o) {
		$this->resell_product = $o;
	}
	
	function getAllowAccessToFutureContent() {
		return $this->future_content_access;
	}
	function setAllowAccessToFutureContent($o) {
		$this->future_content_access = $o;
	}
	
	function getProductImageUrlLinkedTo() {
		return $this->product_image_url_linkedto;
	}
	function setProductImageUrlLinkedTo($o) {
		$this->product_image_url_linkedto = $o;
	}
	
	function getAccessExpirationAction() {
		return $this->access_expiration_action;
	}
	function setAccessExpirationAction($o) {
		$this->access_expiration_action = $o;
	}
	
	function getRenewal_redirect_url() {
		return $this->renewal_redirect_url;
	}
	function setRenewal_redirect_url($o) {
		$this->renewal_redirect_url = $o;
	}	

	function getRenewal_html() {
		return $this->renewal_html;
	}
	function setRenewal_html($o) {
		$this->renewal_html = $o;
	}	

	function getAccessUponFinalPayment() {
		return $this->access_upon_final_payment;
	}
	function setAccessUponFinalPayment($o) {
		$this->access_upon_final_payment = $o;
	}
	
	
	//Derive the remaining recurring days to be awarded based on total.
	// we need to calculate to see what recurring cycle
	// total_days can be zero or negative = meaning this is new cycle (first cycle).
	// total_days can be less than or equal to cycle_1, meaning return cycle_2
	// total_days can be less than or equal to sum of cycle_1, cycle_2 - return cycle_3
	// else return cycle_3
	function deriveRecurringDaysFromTotal($total_days) {
		if($total_days <= 0) return $this->recurring_cycle_1;
		if($total_days <= $this->recurring_cycle_1) return $this->recurring_cycle_2;
		//if($total_days <= ($this->recurring_cycle_1 + $this->recurring_cycle_2)) return $this->recurring_cycle_3;
		return $this->recurring_cycle_3;
	}


 	public static function getErrorPageByResource($resource) {
		$dap_dbh = Dap_Connection::getConnection();
		$errorPageURL = Dap_Config::get("SITEWIDE_ERROR");
		$url = NULL;
		
		if(!isset($resource)) {
			return $url;
		}
		
		$sql =
			"select p.error_page_url
					as error_page_url
			from
				dap_products p,
				dap_products_resources_jn prj,
				dap_file_resources fr
			where
				p.status = 'A' and
				prj.status = 'A' and
				fr.url =:resource and
				prj.resource_id = fr.id and
				prj.product_id = p.id
			";
		$stmt = $dap_dbh->prepare($sql);
		$stmt->bindParam(':resource', $resource, PDO::PARAM_STR);
		$stmt->execute();
		$count = $stmt->rowCount();
		
		if(!($count == 1)) {
			/*
				Either more than one product this resource is associated with.
				Or this is an image or a zip file that is directly uploaded to the WP uploads folder
				and is not really a part of any product yet.
				Lets return the config error page url.
			*/
			
			if ( !isset($errorPageURL) || ($errorPageURL == "") ) $errorPageURL = "/dap/product-error.php";
			
			return $errorPageURL;
		}
		
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row['error_page_url'];
	}


	public function create() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$price = "0.00"; //We don't care about price at this time
	
			$sql = "insert into dap_products
						(name, description, thankyou_page_url, error_page_url, is_recurring, price, trial_price, price_increment, price_increment_ceil, num_sales, num_days, timed_pricing_start_date, selfservice_start_date, selfservice_end_date, total_occur, recurring_cycle_1, recurring_cycle_2, recurring_cycle_3, thirdPartyEmailIds, subscribe_to, unsubscribe_from, status, sales_page_url, self_service_allowed, is_master, allowContentLevelCredits, product_image_url, credits, double_optin_subject, double_optin_body, thankyou_email_subject, thankyou_email_body, logged_in_url, is_free_product, allow_free_signup,access_expiration_action,access_upon_final_payment,show_buy_link,buy_link)
					values
						(:name, :description, :thankyou_page_url, :error_page_url, :is_recurring, :price, :trial_price, :price_increment, :price_increment_ceil, :num_sales, :num_days, :timed_pricing_start_date, :selfservice_start_date, :selfservice_end_date, :total_occur, :recurring_cycle_1, :recurring_cycle_2, :recurring_cycle_3, :thirdPartyEmailIds, :subscribeTo, :unsubscribeFrom, :status, :sales_page_url, :self_service_allowed, :is_master, :allowContentLevelCredits, :product_image_url, :credits, :double_optin_subject, :double_optin_body, :thankyou_email_subject, :thankyou_email_body, :logged_in_url, :is_free_product, :allow_free_signup,:access_expiration_action,:access_upon_final_payment,:show_buy_link,:buy_link)";
			
			$is_free_product = $this->getIs_free_product();
			if( is_null($is_free_product) || ($is_free_product == "") ) {
				$is_free_product = "N";
			}
			
			$allow_free_signup = $this->getAllow_free_signup();
			if( is_null($allow_free_signup) || ($allow_free_signup == "") ) {
				$allow_free_signup = "N";
			}
			
			$is_recurring = $this->getIs_recurring();
			if( is_null($is_recurring) || ($is_recurring == "") ) {
				$is_recurring = "N";
			}
			
			$price = $this->getPrice();
			if( is_null($price) || ($price == "") ) {
				$price = "0.00";
			}
			
			$status = $this->getStatus();
			if( is_null($status) || ($status == "") ) {
				$status = "A";
			}
			
			$self_service_allowed = $this->getSelf_service_allowed();
			if( is_null($self_service_allowed) || ($self_service_allowed == "") ) {
				$self_service_allowed = "N";
			}
			
			$is_master = $this->getIs_master();
			if( is_null($is_master) || ($is_master == "") ) {
				$is_master = "N";
			}
			
			$show_buy_link = $this->getShowBuyLink();
			if( is_null($show_buy_link) || ($show_buy_link == "") ) {
				$show_buy_link = "N";
			}
			
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':name', $this->getName(), PDO::PARAM_STR);
			$stmt->bindParam(':description', $this->getDescription(), PDO::PARAM_STR);
			$stmt->bindParam(':thankyou_page_url', $this->getThankyou_page_url(), PDO::PARAM_STR);
			$stmt->bindParam(':error_page_url', $this->getError_page_url(), PDO::PARAM_STR);
			$stmt->bindParam(':is_recurring', $is_recurring, PDO::PARAM_STR);
			$stmt->bindParam(':price', $price, PDO::PARAM_STR);
			$stmt->bindParam(':trial_price', $this->getTrial_price(), PDO::PARAM_STR);
			$stmt->bindParam(':price_increment', $this->getPrice_increment(), PDO::PARAM_STR);
			$stmt->bindParam(':price_increment_ceil', $this->getPrice_increment_ceil(), PDO::PARAM_STR);
			$stmt->bindParam(':num_sales', $this->getNum_sales(), PDO::PARAM_STR);
			$stmt->bindParam(':num_days', $this->getNum_days(), PDO::PARAM_STR);
			$stmt->bindParam(':timed_pricing_start_date', $this->getTimed_pricing_start_date(), PDO::PARAM_STR);
			$stmt->bindParam(':selfservice_start_date', $this->getSelfservice_start_date(), PDO::PARAM_STR);
			$stmt->bindParam(':selfservice_end_date', $this->getSelfservice_end_date(), PDO::PARAM_STR);
			$stmt->bindParam(':total_occur', $total_occur, PDO::PARAM_STR);
			$stmt->bindParam(':recurring_cycle_1', $this->getRecurring_cycle_1(), PDO::PARAM_STR);
			$stmt->bindParam(':recurring_cycle_2', $this->getRecurring_cycle_2(), PDO::PARAM_STR);
			$stmt->bindParam(':recurring_cycle_3', $this->getRecurring_cycle_3(), PDO::PARAM_STR);
			$stmt->bindParam(':thirdPartyEmailIds', $this->getThirdPartyEmailIds(), PDO::PARAM_STR);
			$stmt->bindParam(':subscribeTo', $this->getSubscribeTo(), PDO::PARAM_STR);
			$stmt->bindParam(':unsubscribeFrom', $this->getUnsubscribeFrom(), PDO::PARAM_STR);
			$stmt->bindParam(':status', $status, PDO::PARAM_STR);
			$stmt->bindParam(':sales_page_url', $this->getSales_page_url(), PDO::PARAM_STR);
			$stmt->bindParam(':self_service_allowed', $self_service_allowed, PDO::PARAM_STR);
			$stmt->bindParam(':is_master', $is_master, PDO::PARAM_STR);
			$stmt->bindParam(':allowContentLevelCredits', $this->getAllowContentLevelCredits(), PDO::PARAM_STR);
			$stmt->bindParam(':product_image_url', $this->getProduct_image_url(), PDO::PARAM_STR);
			$stmt->bindParam(':credits', $this->getCredits(), PDO::PARAM_STR);
			$stmt->bindParam(':double_optin_subject', $this->getDouble_optin_subject(), PDO::PARAM_STR);
			$stmt->bindParam(':double_optin_body', $this->getDouble_optin_body(), PDO::PARAM_STR);
			$stmt->bindParam(':thankyou_email_subject', $this->getThankyou_email_subject(), PDO::PARAM_STR);
			$stmt->bindParam(':thankyou_email_body', $this->getThankyou_email_body(), PDO::PARAM_STR);
			$stmt->bindParam(':logged_in_url', $this->getLogged_in_url(), PDO::PARAM_STR);
			$stmt->bindParam(':is_free_product', $is_free_product, PDO::PARAM_STR);
			$stmt->bindParam(':allow_free_signup', $allow_free_signup, PDO::PARAM_STR);
			$stmt->bindParam(':access_expiration_action', $access_expiration_action, PDO::PARAM_STR);
			$stmt->bindParam(':access_upon_final_payment', $access_upon_final_payment, PDO::PARAM_STR);
			$stmt->bindParam(':show_buy_link', $show_buy_link, PDO::PARAM_STR);
			$stmt->bindParam(':buy_link', $this->getBuyLink(), PDO::PARAM_STR);
			//$stmt->bindParam(':quantity', $quantity, PDO::PARAM_STR);
			
			$stmt->execute();

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
			
			$sql = "update dap_products set
						name = :name,
						description = :description,
						thankyou_page_url = :thankyou_page_url,
						error_page_url = :error_page_url,
						is_recurring = :is_recurring,
						price = :price,
						trial_price = :trial_price,
						price_increment = :price_increment,
						price_increment_ceil = :price_increment_ceil,
						num_sales = :num_sales,
						num_days = :num_days,
						timed_pricing_start_date = :timed_pricing_start_date,
						selfservice_start_date = :selfservice_start_date,
						selfservice_end_date = :selfservice_end_date,
						total_occur = :total_occur,
						recurring_cycle_1 = :recurring_cycle_1,
						recurring_cycle_2 = :recurring_cycle_2,
						recurring_cycle_3 = :recurring_cycle_3,
						thirdPartyEmailIds = :thirdPartyEmailIds,
						subscribe_to = :subscribeTo,
						unsubscribe_from = :unsubscribeFrom,
						status = :status,
						sales_page_url = :sales_page_url,
						self_service_allowed = :self_service_allowed,
						is_master = :is_master,
						allowContentLevelCredits = :allowContentLevelCredits,
						product_image_url = :product_image_url,
						credits = :credits,
						double_optin_subject = :double_optin_subject,
						double_optin_body = :double_optin_body,
						thankyou_email_subject = :thankyou_email_subject,
						thankyou_email_body = :thankyou_email_body,
						logged_in_url = :logged_in_url,
						is_free_product = :is_free_product,
						allow_free_signup = :allow_free_signup,
						short_description = :short_description,
						long_description = :long_description,
						resell_product = :resell_product,
						future_content_access = :future_content_access,
						product_image_url_linkedto = :product_image_url_linkedto,
						access_expiration_action=:access_expiration_action,						
						recurring_credits = :recurring_credits,
						renewal_redirect_url = :renewal_redirect_url,
						renewal_html = :renewal_html,
						access_upon_final_payment = :access_upon_final_payment,
						show_buy_link = :show_buy_link,
						buy_link = :buy_link
					where
						id = :productId";

			//logToFile("HERE in Dap_Products update");
			
			$is_free_product = $this->getIs_free_product();
			if( is_null($is_free_product) || ($is_free_product == "") ) {
				$is_free_product = "N";
			}
			
			$allow_free_signup = $this->getAllow_free_signup();
			if( is_null($allow_free_signup) || ($allow_free_signup == "") ) {
				$allow_free_signup = "N";
			}
			
			$show_buy_link = $this->getShowBuyLink();
			if( is_null($show_buy_link) || ($show_buy_link == "") ) {
				$show_buy_link = "N";
			}
			
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':name', $this->getName(), PDO::PARAM_STR);
			$stmt->bindParam(':description', $this->getDescription(), PDO::PARAM_STR);
			$stmt->bindParam(':thankyou_page_url', $this->getThankyou_page_url(), PDO::PARAM_STR);
			$stmt->bindParam(':error_page_url', $this->getError_page_url(), PDO::PARAM_STR);
			$stmt->bindParam(':is_recurring', $this->getIs_recurring(), PDO::PARAM_STR);
			$stmt->bindParam(':price', $this->getPrice(), PDO::PARAM_STR);
			$stmt->bindParam(':trial_price', $this->getTrial_price(), PDO::PARAM_STR);
			$stmt->bindParam(':price_increment', $this->getPrice_increment(), PDO::PARAM_STR);
			$stmt->bindParam(':price_increment_ceil', $this->getPrice_increment_ceil(), PDO::PARAM_STR);
			$stmt->bindParam(':num_sales', $this->getNum_sales(), PDO::PARAM_STR);
			$stmt->bindParam(':num_days', $this->getNum_days(), PDO::PARAM_STR);
			$stmt->bindParam(':timed_pricing_start_date', $this->getTimed_pricing_start_date(), PDO::PARAM_STR);
			$stmt->bindParam(':selfservice_start_date', $this->getSelfservice_start_date(), PDO::PARAM_STR);
			$stmt->bindParam(':selfservice_end_date', $this->getSelfservice_end_date(), PDO::PARAM_STR);
			$stmt->bindParam(':total_occur', $this->getTotal_occur(), PDO::PARAM_STR);
			$stmt->bindParam(':recurring_cycle_1', $this->getRecurring_cycle_1(), PDO::PARAM_STR);
			$stmt->bindParam(':recurring_cycle_2', $this->getRecurring_cycle_2(), PDO::PARAM_STR);
			$stmt->bindParam(':recurring_cycle_3', $this->getRecurring_cycle_3(), PDO::PARAM_STR);
			$stmt->bindParam(':thirdPartyEmailIds', $this->getThirdPartyEmailIds(), PDO::PARAM_STR);
			$stmt->bindParam(':subscribeTo', $this->getSubscribeTo(), PDO::PARAM_STR);
			$stmt->bindParam(':unsubscribeFrom', $this->getUnsubscribeFrom(), PDO::PARAM_STR);
			$stmt->bindParam(':status', $this->getStatus(), PDO::PARAM_STR);
			$stmt->bindParam(':sales_page_url', $this->getSales_page_url(), PDO::PARAM_STR);
			$stmt->bindParam(':self_service_allowed', $this->getSelf_service_allowed(), PDO::PARAM_STR);
			$stmt->bindParam(':is_master', $this->getIs_master(), PDO::PARAM_STR);
			$stmt->bindParam(':allowContentLevelCredits', $this->getAllowContentLevelCredits(), PDO::PARAM_STR);
			$stmt->bindParam(':product_image_url', $this->getProduct_image_url(), PDO::PARAM_STR);
			$stmt->bindParam(':credits', $this->getCredits(), PDO::PARAM_STR);
			$stmt->bindParam(':double_optin_subject', $this->getDouble_optin_subject(), PDO::PARAM_STR);
			$stmt->bindParam(':double_optin_body', $this->getDouble_optin_body(), PDO::PARAM_STR);
			$stmt->bindParam(':thankyou_email_subject', $this->getThankyou_email_subject(), PDO::PARAM_STR);
			$stmt->bindParam(':thankyou_email_body', $this->getThankyou_email_body(), PDO::PARAM_STR);
			$stmt->bindParam(':logged_in_url', $this->getLogged_in_url(), PDO::PARAM_STR);
			$stmt->bindParam(':is_free_product', $is_free_product, PDO::PARAM_STR);
			$stmt->bindParam(':allow_free_signup', $allow_free_signup, PDO::PARAM_STR);
			$stmt->bindParam(':short_description', $this->getShortDescription(), PDO::PARAM_STR);
			$stmt->bindParam(':long_description', $this->getLongDescription(), PDO::PARAM_STR);
			$stmt->bindParam(':resell_product', $this->getResellProduct(), PDO::PARAM_STR);
			$stmt->bindParam(':future_content_access', $this->getAllowAccessToFutureContent(), PDO::PARAM_STR);
			$stmt->bindParam(':product_image_url_linkedto', $this->getProductImageUrlLinkedTo(), PDO::PARAM_STR);
			$stmt->bindParam(':access_expiration_action', $this->getAccessExpirationAction(), PDO::PARAM_STR);
			$stmt->bindParam(':recurring_credits', $this->getRecurringCredits(), PDO::PARAM_STR);
			$stmt->bindParam(':renewal_redirect_url', $this->getRenewal_redirect_url(), PDO::PARAM_STR);
			$stmt->bindParam(':renewal_html', $this->getRenewal_html(), PDO::PARAM_STR);
			$stmt->bindParam(':access_upon_final_payment', $this->getAccessUponFinalPayment(), PDO::PARAM_STR);
			$stmt->bindParam(':show_buy_link', $show_buy_link, PDO::PARAM_STR);
			$stmt->bindParam(':buy_link', $this->getBuyLink(), PDO::PARAM_STR);
			//$stmt->bindParam(':quantity', $quantity, PDO::PARAM_STR);
				
			$stmt->bindParam(':productId', $this->getId(), PDO::PARAM_INT);

			//logToFile("Before exec in Dap_Products update");
			
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


	function displayFileResources($productId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();

			$sql = "select
						fr.id,
						fr.url,
						prj.start_day,
						prj.start_date,
						prj.num_clicks,
						prj.is_free,
					    prj.credits_assigned,
						prj.display_order as do
					from
						dap_products_resources_jn prj,
						dap_file_resources fr
					where
						prj.product_id = :productId and
						prj.resource_id = fr.id and
						prj.resource_type = 'F'
					order by
						prj.start_day desc, 
						prj.start_date desc, 
						prj.display_order desc,
						prj.num_clicks desc
						";

			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_STR);
			$stmt->execute();

			$product = DAP_Product::loadProduct($productId); //loads product details from db
			if(isset($product)) {
			  $isSSSMaster=$product->getIs_master();
			  $sss=$product->getSelf_service_allowed();
			  $allowContentLevelCredits=$product->getAllowContentLevelCredits();
			//  $allowContentLevelCredits=$product->setAllowContentLevelCredits();
			}
			logToFile("DAP_Products.class.php: sss=".$sss);
			logToFile("DAP_Products.class.php: isSSSMaster=".$isSSSMaster);
			$displayHTML = "
			  <div id='sortableFileListHeader'>
				  <ul>
					  <li>
					  <span style='width:40px; float:left'><strong>Edit</strong></span>";
			 if(($sss!="Y") || ($isSSSMaster=="Y")) {		
				 $displayHTML .= "<span style='width:70px; float:left; align=center'><strong>Drip<br/>Day</strong></span>";
			 }
			 else {		
				  $displayHTML .= "<span style='width:70px; float:left; align=center'><strong>Credits</strong></span>";
			  }
			  
			  $displayHTML .=	"<span style='width:30px; float:left;'><a href=\"#\" onClick=\"javascript:removeAllFileResourcesFromProduct(); return false;\" title=\"Delete all protected content from this Product\"><img src=\"/dap/images/ximage.jpg\" border=\"0\" width=\"20\" height=\"20\" style='align: middle'></a></span>
					  <span style='display:block; overflow:auto;'><strong>URL of Protected Content</strong></span>
					  </li>
				  </ul>
			  </div>
			  ";
						
			$displayHTML .= "<div id='sortableFileList'>
				<ul>";
			//<table width='100%' cellspacing='0' cellpadding='0'>";
			/**<tr align='left' bgcolor=\"#EFEFEF\" class=\"bodytext\">
				<td>&nbsp;</td>
				<td align='center'><b>Drip on<br/>Day #</b></td>
				<td align='center'><b>URL</b></td>
				<td><a href=\"#\" onClick=\"javascript:removeAllFileResourcesFromProduct(); return false;\" title=\"Delete all protected content from this Product\"><img src=\"/dap/images/ximage.jpg\" border=\"0\" width=\"13\" height=\"13\"></a></td>
				</tr>";
			*/	
			//$displayHTML .= "<tr align='left' bgcolor=\"$bgcolor\" class=\"bodytext\"><td colspan = '4'>
			//$displayHTML .= "";
			
			$dataFound = false;
			$bgcolor = "#EEEEEE";
			while ($row = $stmt->fetch()) {
				$dataFound = true;
				if($bgcolor == "#FFFFFF") {
					$bgcolor = "#EEEEEE";
				} else if ($bgcolor == "#EEEEEE") {
					$bgcolor = "#FFFFFF";
				}
				$id = $row["id"];
				$url = $row["url"];
				$start_day = $row["start_day"];
				$start_date = $row["start_date"];
				$num_clicks = $row["num_clicks"];
				$is_free = $row["is_free"];
				$do = $row["do"];
				$credits = $row["credits_assigned"];
				if ($credits == "") $credits=0;
				//logToFile("$start_day, $start_date, $num_clicks",LOG_DEBUG_DAP);

				$whichOne = "";

				if( ($start_day != "") && ($start_day != 0) ){
					$whichOne = $start_day;
				} else if( ($start_date != "") && ($start_date != 0) ){
					$whichOne = $start_date;
				} else if( ($num_clicks != "") && ($num_clicks != 0) ){
					$whichOne = $num_clicks . " (clicks)";
				}
				
				//$urlShort = $url;
				$urlShort = trimString($url,80,50,30);

				/**$displayHTML .= "<tr align='left' bgcolor=\"$bgcolor\" class=\"bodytext\">
				<td width='30'><a href=\"#\" onClick=\"loadFileResource('" . $id . "'); clearDiv('file_resource_message'); return false;\" title='Click to edit drip settings'>edit</a></td>
				<td align='center'>$whichOne</td>
				<td><a href='$url' target='_blank' title='$url'>$urlShort</a></td>
				<td width='20' align='center'><a href=\"#\" onClick=\"javascript:removeFileResourceFromProduct('" .$id. "'); return false;\" title=\"Delete this protected content from this Product\"><img src=\"/dap/images/ximage.jpg\" border=\"0\" width=\"13\" height=\"13\"></a></td>
				</tr>";*/
				
			 
				$displayHTML .= "<li id='recordsArray_".$id."'>
				<span style='width:40px; float:left'>&nbsp;&nbsp;<a href=\"#\" onClick=\"loadFileResource('" . $id . "'); clearDiv('file_resource_message'); return false;\" title='Click to edit drip settings. Click & Drag to re-arrange order within a given drip day'><img src='/dap/images/edit.png' width='12' height='12' style='align:absmiddle' border='0'/></a></span>";
				
				if(($sss!="Y") || ($isSSSMaster=="Y")) {
				  $displayHTML .= "<span style='width:70px; float:left'>$whichOne</span>";
				}
				else {
				   $displayHTML .= "<span style='width:70px; float:left'>$credits</span>";
			    }
				$displayHTML .= "<span style='width:30px; float:left'><a href=\"#\" onClick=\"javascript:removeFileResourceFromProduct('" .$id. "'); return false;\" title=\"Delete this protected content from this Product\"><img src=\"/dap/images/ximage.jpg\" border=\"0\" width=\"14\" height=\"14\" align=\"absmiddle\"></a></span>
				<span style='display:block; overflow:auto; background-color:#EFEFEF;' title='$url'><a href='$url' target='_blank'>$urlShort</a></span>
				</li>
				";
			  
			}

			if($dataFound == false) {
				$displayHTML .= "There are no Files currently assigned to this Product";
			}
			
			$displayHTML .= "</ul></div>";

			//return $displayHTML;
			return mb_convert_encoding($displayHTML, "UTF-8", "auto");
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			return $e->getMessage();
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			return $e->getMessage();
		}
	}

	function displayFileResourcesSSS($productId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();

			$sql = "select
						fr.id,
						fr.url,
						prj.credits_assigned,
						prj.display_order,
						prj.is_free
					from
						dap_products_resources_jn prj,
						dap_file_resources fr
					where
						prj.product_id = :productId and
						prj.resource_id = fr.id and
						prj.resource_type = 'F' and
						prj.credits_assigned > 0
					order by
						prj.display_order desc";
						
			//logToFile($sql,LOG_DEBUG_DAP);
			logToFile("Dap_Product.class.php.displayFileResourcesSSS(): productId=". $productId,LOG_DEBUG_DAP);
			
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_STR);
			$stmt->execute();

			$displayHTML = "<table width='100%' cellspacing='0' cellpadding='3'>
			<tr align='center' bgcolor=\"#EFEFEF\" class=\"bodytext\">
				<td>&nbsp;</td>
				<td align='center'><b>Credits</b></td>
				<td align='center'><b>Display<br/>Order</b></td>
				<td align='center'><b>URL</b></td>
				<td><a href=\"#\" onClick=\"javascript:removeAllFileResourcesFromProduct(); return false;\" title='Delete all protected content from this Product'><img src=\"/dap/images/ximage.jpg\" border=\"0\" width=\"13\" height=\"13\"></a></td>
				</tr>";
			$bgcolor = "#EEEEEE";
			while ($row = $stmt->fetch()) {
				if($bgcolor == "#FFFFFF") {
					$bgcolor = "#EEEEEE";
				} else if ($bgcolor == "#EEEEEE") {
					$bgcolor = "#FFFFFF";
				}
				$id = $row["id"];
				$url = $row["url"];
				$credits_assigned = $row["credits_assigned"];
				$display_order = $row["display_order"];
				$is_free = $row["is_free"];

				//logToFile("$start_day, $start_date, $num_clicks",LOG_DEBUG_DAP);

				$whichOne = "";

				$displayHTML .= "<tr align='left' bgcolor=\"$bgcolor\" class=\"bodytext\">
				<td width='30'><a href=\"#\" onClick=\"loadFileResource('" . $id . "'); clearDiv('file_resource_message'); return false;\" title='Click to edit drip settings'>edit</a></td>
				<td align='center'>$credits_assigned</td>
				<td align='center'>$display_order</td>
				<td><a href='$url' target='_blank' title='Click to open in a new window'>$url</a></td>
				<td width='20' align='center'><a href=\"#\" onClick=\"javascript:removeFileResourceFromProduct('" .$id. "'); return false;\" title='Delete this protected content from this Product'><img src=\"/dap/images/ximage.jpg\" border=\"0\" width=\"13\" height=\"13\"></a></td>
				</tr>";
			}

			if($displayHTML == "<table width='100%' cellspacing='0' cellpadding='5'>") {
				$displayHTML = "<tr align='left'><td colspan='4'>There are no Files currently assigned to this Product</td></tr>";
			} else {
				$displayHTML .= "</table>";
			}

			return $displayHTML;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			return $e->getMessage();
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			return $e->getMessage();
		}
	}
	
	public static function loadProduct($productId) {
		$dap_dbh = Dap_Connection::getConnection();
		$product = null;

		//Load product details from database
		$sql = "select *
			from
				dap_products
			where
				id = :productId";

		$stmt = $dap_dbh->prepare($sql);
		$stmt->bindParam(':productId', $productId, PDO::PARAM_STR);
		$stmt->execute();

		//echo "sql: $sql<br>"; exit;
		//$result = mysql_query($sql);
		//echo "rows returned: " . mysql_num_rows($result) . "<br>";

		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$product = new Dap_Product();
			$product->setId( $row["id"] );
			$product->setName( stripslashes($row["name"]) );
			$product->setDescription( stripslashes($row["description"]) );
			$product->setError_page_url( stripslashes($row["error_page_url"]) );
			$product->setIs_recurring( stripslashes($row["is_recurring"]) );
			$product->setPrice( $row["price"] );
			$product->setTrial_price( $row["trial_price"] );
			$product->setPrice_increment( $row["price_increment"] );
			$product->setPrice_increment_ceil( $row["price_increment_ceil"] );
			$product->setNum_sales( $row["num_sales"] );
			$product->setNum_days( $row["num_days"] );
			$product->setTimed_pricing_start_date( $row["timed_pricing_start_date"] );
			$product->setSelfservice_start_date( $row["selfservice_start_date"] );
			$product->setSelfservice_end_date( $row["selfservice_end_date"] );
			$product->setTotal_occur( $row["total_occur"] );
			$product->setRecurring_cycle_1( $row["recurring_cycle_1"] );
			$product->setRecurring_cycle_2( $row["recurring_cycle_2"] );
			$product->setRecurring_cycle_3( $row["recurring_cycle_3"] );
			$product->setStatus( $row["status"] );
			$product->setNotification_id( $row["notification_id"] );
			$product->setThankyou_page_url( stripslashes($row["thankyou_page_url"]) );
			$product->setThirdPartyEmailIds( stripslashes($row["thirdPartyEmailIds"]) );
			$product->setSubscribeTo( stripslashes($row["subscribe_to"]) );
			$product->setUnsubscribeFrom( stripslashes($row["unsubscribe_from"]) );
			$product->setSales_page_url( stripslashes($row["sales_page_url"]) );
			$product->setSelf_service_allowed( stripslashes($row["self_service_allowed"]) );
			$product->setIs_master( stripslashes($row["is_master"]) );
			$product->setAllowContentLevelCredits( stripslashes($row["allowContentLevelCredits"]) );
			$product->setProduct_image_url( stripslashes($row["product_image_url"]) );
			$product->setCredits( stripslashes($row["credits"]) );
			$product->setDouble_optin_subject( stripslashes($row["double_optin_subject"]) );
			$product->setDouble_optin_body( stripslashes($row["double_optin_body"]) );
			$product->setThankyou_email_subject( stripslashes($row["thankyou_email_subject"]) );
			$product->setThankyou_email_body( stripslashes($row["thankyou_email_body"]) );
			$product->setLogged_in_url( stripslashes($row["logged_in_url"]) );
			$product->setIs_free_product( stripslashes($row["is_free_product"]) );
			$product->setAllow_free_signup( stripslashes($row["allow_free_signup"]) );
			$product->setRecurringCredits( stripslashes($row["recurring_credits"]) );
			$product->setShortDescription( stripslashes($row["short_description"]) );
			$product->setLongDescription( stripslashes($row["long_description"]) );
			$product->setResellProduct( stripslashes($row["resell_product"]) );
			$product->setAllowAccessToFutureContent( stripslashes($row["future_content_access"]) );
			$product->setProductImageUrlLinkedTo( stripslashes($row["product_image_url_linkedto"]) );
			$product->setAccessExpirationAction( stripslashes($row["access_expiration_action"]) );
			$product->setRenewal_redirect_url( stripslashes($row["renewal_redirect_url"]) );
			$product->setRenewal_html( stripslashes($row["renewal_html"]) );
			$product->setAccessUponFinalPayment( stripslashes($row["access_upon_final_payment"]) );
			$product->setBuyLink( stripslashes($row["buy_link"]) );
			$product->setShowBuyLink( stripslashes($row["show_buy_link"]) );
			//$product->setQuantity( stripslashes($row["quantity"]) );
			//echo "id: " . $product->getDescription(); exit;
			//return $product;
		}

		return $product;
	}
	
	public static function isExists($productId) {
		$dap_dbh = Dap_Connection::getConnection();

		//Load product details from database
		$sql = "select id
			from
				dap_products
			where
				id = :productId";

		$stmt = $dap_dbh->prepare($sql);
		$stmt->bindParam(':productId', $productId, PDO::PARAM_STR);
		$stmt->execute();

		//echo "sql: $sql<br>"; exit;
		//$result = mysql_query($sql);
		//echo "rows returned: " . mysql_num_rows($result) . "<br>";

		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		  return TRUE;
		}

		return FALSE;
	}	

	public static function loadProductByName($productName) {
		$dap_dbh = Dap_Connection::getConnection();

		$productName=utf8_encode($productName);
		//Load product details from database
		$sql = "select *
			from
				dap_products
			where
				name = :productName";
		
		//echo "sql: $sql<br>"; exit;
		//$result = mysql_query($sql);
		//echo "rows returned: " . mysql_num_rows($result) . "<br>";
		try {
		$stmt = $dap_dbh->prepare($sql);
		$stmt->bindParam(':productName', $productName, PDO::PARAM_STR);
		$stmt->execute();
		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$product = new Dap_Product();
			$product->setId( $row["id"] );
			$product->setName( stripslashes($row["name"]) );
			$product->setDescription( stripslashes($row["description"]) );
			$product->setError_page_url( stripslashes($row["error_page_url"]) );
			$product->setIs_recurring( stripslashes($row["is_recurring"]) );
			$product->setPrice( $row["price"] );
			$product->setTrial_price( $row["trial_price"] );
			$product->setPrice_increment( $row["price_increment"] );
			$product->setPrice_increment_ceil( $row["price_increment_ceil"] );
			$product->setNum_sales( $row["num_sales"] );
			$product->setNum_days( $row["num_days"] );
			$product->setTimed_pricing_start_date( $row["timed_pricing_start_date"] );
			$product->setSelfservice_start_date( $row["selfservice_start_date"] );
			$product->setSelfservice_end_date( $row["selfservice_end_date"] );
			$product->setTotal_occur( $row["total_occur"] );
			$product->setRecurring_cycle_1( $row["recurring_cycle_1"] );
			$product->setRecurring_cycle_2( $row["recurring_cycle_2"] );
			$product->setRecurring_cycle_3( $row["recurring_cycle_3"] );
			$product->setStatus( $row["status"] );
			$product->setNotification_id( $row["notification_id"] );
			$product->setThankyou_page_url( stripslashes($row["thankyou_page_url"]) );
			$product->setThirdPartyEmailIds( stripslashes($row["thirdPartyEmailIds"]) );
			$product->setSubscribeTo( stripslashes($row["subscribe_to"]) );
			$product->setUnsubscribeFrom( stripslashes($row["unsubscribe_from"]) );
			$product->setSales_page_url( stripslashes($row["sales_page_url"]) );
			$product->setSelf_service_allowed( stripslashes($row["self_service_allowed"]) );
			$product->setIs_master( stripslashes($row["is_master"]) );
			$product->setAllowContentLevelCredits( stripslashes($row["allowContentLevelCredits"]) );
			$product->setProduct_image_url( stripslashes($row["product_image_url"]) );
			$product->setCredits( stripslashes($row["credits"]) );
			$product->setDouble_optin_subject( stripslashes($row["double_optin_subject"]) );
			$product->setDouble_optin_body( stripslashes($row["double_optin_body"]) );
			$product->setThankyou_email_subject( stripslashes($row["thankyou_email_subject"]) );
			$product->setThankyou_email_body( stripslashes($row["thankyou_email_body"]) );
			$product->setLogged_in_url( stripslashes($row["logged_in_url"]) );
			$product->setIs_free_product( stripslashes($row["is_free_product"]) );
			$product->setAllow_free_signup( stripslashes($row["allow_free_signup"]) );
			$product->setAccessExpirationAction( stripslashes($row["access_expiration_action"]) );
			$product->setRenewal_redirect_url( stripslashes($row["renewal_redirect_url"]) );
			$product->setRenewal_html( stripslashes($row["renewal_html"]) );
			$product->setAccessUponFinalPayment( stripslashes($row["access_upon_final_payment"]) );
			$product->setBuyLink( stripslashes($row["buy_link"]) );
			$product->setShowBuyLink( stripslashes($row["show_buy_link"]) );
			//$product->setQuantity( stripslashes($row["quantity"]) );
			//echo "id: " . $product->getDescription(); exit;
			return $product;
		}
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}

		return;
	}


	public static function loadProductByDesc($productName) {
		$dap_dbh = Dap_Connection::getConnection();
		$productName=utf8_encode($productName);
		//Load product details from database
		$sql = "select *
			from
				dap_products
			where
				description = :productName";

		$stmt = $dap_dbh->prepare($sql);
		$stmt->bindParam(':productName', $productName, PDO::PARAM_STR);
		$stmt->execute();

		//echo "sql: $sql<br>"; exit;
		//$result = mysql_query($sql);
		//echo "rows returned: " . mysql_num_rows($result) . "<br>";

		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$product = new Dap_Product();
			$product->setId( $row["id"] );
			$product->setName( stripslashes($row["name"]) );
			$product->setDescription( stripslashes($row["description"]) );
			$product->setError_page_url( stripslashes($row["error_page_url"]) );
			$product->setIs_recurring( stripslashes($row["is_recurring"]) );
			$product->setPrice( $row["price"] );
			$product->setTrial_price( $row["trial_price"] );
			$product->setPrice_increment( $row["price_increment"] );
			$product->setPrice_increment_ceil( $row["price_increment_ceil"] );
			$product->setNum_sales( $row["num_sales"] );
			$product->setNum_days( $row["num_days"] );
			$product->setTimed_pricing_start_date( $row["timed_pricing_start_date"] );
			$product->setSelfservice_start_date( $row["selfservice_start_date"] );
			$product->setSelfservice_end_date( $row["selfservice_end_date"] );
			$product->setTotal_occur( $row["total_occur"] );
			$product->setRecurring_cycle_1( $row["recurring_cycle_1"] );
			$product->setRecurring_cycle_2( $row["recurring_cycle_2"] );
			$product->setRecurring_cycle_3( $row["recurring_cycle_3"] );
			$product->setStatus( $row["status"] );
			$product->setNotification_id( $row["notification_id"] );
			$product->setThankyou_page_url( stripslashes($row["thankyou_page_url"]) );
			$product->setThirdPartyEmailIds( stripslashes($row["thirdPartyEmailIds"]) );
			$product->setSubscribeTo( stripslashes($row["subscribe_to"]) );
			$product->setUnsubscribeFrom( stripslashes($row["unsubscribe_from"]) );
			$product->setSales_page_url( stripslashes($row["sales_page_url"]) );
			$product->setSelf_service_allowed( stripslashes($row["self_service_allowed"]) );
			$product->setIs_master( stripslashes($row["is_master"]) );
			$product->setAllowContentLevelCredits( stripslashes($row["allowContentLevelCredits"]) );
			$product->setProduct_image_url( stripslashes($row["product_image_url"]) );
			$product->setCredits( stripslashes($row["credits"]) );
			$product->setDouble_optin_subject( stripslashes($row["double_optin_subject"]) );
			$product->setDouble_optin_body( stripslashes($row["double_optin_body"]) );
			$product->setThankyou_email_subject( stripslashes($row["thankyou_email_subject"]) );
			$product->setThankyou_email_body( stripslashes($row["thankyou_email_body"]) );
			$product->setLogged_in_url( stripslashes($row["logged_in_url"]) );
			$product->setIs_free_product( stripslashes($row["is_free_product"]) );
			$product->setAllow_free_signup( stripslashes($row["allow_free_signup"]) );
			$product->setAccessExpirationAction( stripslashes($row["access_expiration_action"]) );
			$product->setRenewal_redirect_url( stripslashes($row["renewal_redirect_url"]) );
			$product->setRenewal_html( stripslashes($row["renewal_html"]) );
			$product->setAccessUponFinalPayment( stripslashes($row["access_upon_final_payment"]) );
			$product->setBuyLink( stripslashes($row["buy_link"]) );
			$product->setShowBuyLink( stripslashes($row["show_buy_link"]) );
			//$product->setQuantity( stripslashes($row["quantity"]) );
			//echo "id: " . $product->getDescription(); exit;
			return $product;
		}

		return;
	}
	
	//Load products matching filter criteria
	public static function loadProducts($productFilter, $status, $orderBy = "id", $orderHow = "asc") {
		$ProductsList = array();

		$whereClauseForStatus1 = ($status == "") ? "" : " where status = '$status'";
		$whereClauseForStatus2 = ($status == "") ? "" : " and status = '$status'";
		$orderClause = " order by $orderBy $orderHow ";

		if(trim($productFilter) == "") {
			$sql = "select * from dap_products " . $whereClauseForStatus1 . $orderClause;
		} else {
			$sql = "select * from dap_products
					where
						(id = '$productFilter' or
						name like '%$productFilter%' or
						description like '%$productFilter%' or
						error_page_url like '%$productFilter%' or
						thankyou_page_url like '%$productFilter%') " . $whereClauseForStatus2 . $orderClause;
		}

		try {
			$dap_dbh = Dap_Connection::getConnection();
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();

			while ($row = $stmt->fetch()) {
				$product = new Dap_Product();

				$product->setId( $row["id"] );
				$product->setName( $row["name"] );
				$product->setDescription( $row["description"] );
				$product->setError_page_url( $row["error_page_url"] );
				$product->setPrice( $row["price"] );
				$product->setTrial_price( $row["trial_price"] );
				$product->setPrice_increment( $row["price_increment"] );
				$product->setPrice_increment_ceil( $row["price_increment_ceil"] );
				$product->setNum_sales( $row["num_sales"] );
				$product->setNum_days( $row["num_days"] );
				$product->setTimed_pricing_start_date( $row["timed_pricing_start_date"] );
				$product->setSelfservice_start_date( $row["selfservice_start_date"] );
				$product->setSelfservice_end_date( $row["selfservice_end_date"] );
				$product->setTotal_occur( $row["total_occur"] );
				$product->setIs_recurring( $row["is_recurring"] );
				$product->setRecurring_cycle_1( $row["recurring_cycle_1"] );
				$product->setRecurring_cycle_2( $row["recurring_cycle_2"] );
				$product->setRecurring_cycle_3( $row["recurring_cycle_3"] );
				$product->setStatus( $row["status"] );
				$product->setNotification_id( $row["notification_id"] );
				$product->setThankyou_page_url( $row["thankyou_page_url"] );
				$product->setThirdPartyEmailIds( $row["thirdPartyEmailIds"] );
				$product->setSubscribeTo( stripslashes($row["subscribe_to"]) );
				$product->setUnsubscribeFrom( stripslashes($row["unsubscribe_from"]) );
				$product->setSelf_service_allowed( stripslashes($row["self_service_allowed"]) );
				$product->setIs_master( stripslashes($row["is_master"]) );
				$product->setAllowContentLevelCredits( stripslashes($row["allowContentLevelCredits"]) );
				$product->setProduct_image_url( stripslashes($row["product_image_url"]) );
				$product->setCredits( stripslashes($row["credits"]) );
				$product->setDouble_optin_subject( stripslashes($row["double_optin_subject"]) );
				$product->setDouble_optin_body( stripslashes($row["double_optin_body"]) );
				$product->setThankyou_email_subject( stripslashes($row["thankyou_email_subject"]) );
				$product->setThankyou_email_body( stripslashes($row["thankyou_email_body"]) );
				$product->setLogged_in_url( stripslashes($row["logged_in_url"]) );
				$product->setIs_free_product( stripslashes($row["is_free_product"]) );
				$product->setAllow_free_signup( stripslashes($row["allow_free_signup"]) );
				$product->setAccessExpirationAction( stripslashes($row["access_expiration_action"]) );
				$product->setRenewal_redirect_url( stripslashes($row["renewal_redirect_url"]) );
				$product->setRenewal_html( stripslashes($row["renewal_html"]) );
				$product->setAccessUponFinalPayment( stripslashes($row["access_upon_final_payment"]) );
				
				$product->setBuyLink( stripslashes($row["buy_link"]) );
				$product->setShowBuyLink( stripslashes($row["show_buy_link"]) );
				//$product->setQuantity( stripslashes($row["quantity"]) );
				$ProductsList[] = $product;
			}

			return $ProductsList;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}

//Load products matching filter criteria
	public static function loadProductsForSSS($isMaster) {
		$ProductsList = array();
		try {
			
			if ($isMaster=="ALL") {
				$sql = "select * from dap_products
							where self_service_allowed='Y'";
				$dap_dbh = Dap_Connection::getConnection();
				$stmt = $dap_dbh->prepare($sql);
				$stmt->execute();
			}
			else if ($isMaster=="Y") {
				$sql = "select * from dap_products
							where is_master=:isMaster and self_service_allowed='Y'";
				$dap_dbh = Dap_Connection::getConnection();
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':isMaster', $isMaster, PDO::PARAM_STR);
				$stmt->execute();
			}
			else {
				$sql = "select * from dap_products
							where is_master<>'Y' and self_service_allowed='Y'";
							$dap_dbh = Dap_Connection::getConnection();
					$stmt = $dap_dbh->prepare($sql);
					$stmt->execute();
			}
		
			while ($row = $stmt->fetch()) {
				$product = new Dap_Product();

				$product->setId( $row["id"] );
				$product->setName( $row["name"] );
				$product->setDescription( $row["description"] );
				$product->setError_page_url( $row["error_page_url"] );
				$product->setPrice( $row["price"] );
				$product->setTrial_price( $row["trial_price"] );
				$product->setPrice_increment( $row["price_increment"] );
				$product->setPrice_increment_ceil( $row["price_increment_ceil"] );
				$product->setNum_sales( $row["num_sales"] );
				$product->setNum_days( $row["num_days"] );
				$product->setTimed_pricing_start_date( $row["timed_pricing_start_date"] );
				$product->setSelfservice_start_date( $row["selfservice_start_date"] );
				$product->setSelfservice_end_date( $row["selfservice_end_date"] );
				$product->setTotal_occur( $row["total_occur"] );
				$product->setIs_recurring( $row["is_recurring"] );
				$product->setRecurring_cycle_1( $row["recurring_cycle_1"] );
				$product->setRecurring_cycle_2( $row["recurring_cycle_2"] );
				$product->setRecurring_cycle_3( $row["recurring_cycle_3"] );
				$product->setStatus( $row["status"] );
				$product->setNotification_id( $row["notification_id"] );
				$product->setThankyou_page_url( $row["thankyou_page_url"] );
				$product->setThirdPartyEmailIds( $row["thirdPartyEmailIds"] );
				$product->setSubscribeTo( stripslashes($row["subscribe_to"]) );
				$product->setUnsubscribeFrom( stripslashes($row["unsubscribe_from"]) );
				$product->setSelf_service_allowed( stripslashes($row["self_service_allowed"]) );
				$product->setIs_master( stripslashes($row["is_master"]) );
				$product->setAllowContentLevelCredits( stripslashes($row["allowContentLevelCredits"]) );
				$product->setProduct_image_url( stripslashes($row["product_image_url"]) );
				$product->setCredits( stripslashes($row["credits"]) );
				$product->setDouble_optin_subject( stripslashes($row["double_optin_subject"]) );
				$product->setDouble_optin_body( stripslashes($row["double_optin_body"]) );
				$product->setThankyou_email_subject( stripslashes($row["thankyou_email_subject"]) );
				$product->setThankyou_email_body( stripslashes($row["thankyou_email_body"]) );
				$product->setLogged_in_url( stripslashes($row["logged_in_url"]) );
				$product->setIs_free_product( stripslashes($row["is_free_product"]) );
				$product->setAllow_free_signup( stripslashes($row["allow_free_signup"]) );
				$product->setAccessExpirationAction( stripslashes($row["access_expiration_action"]) );
				$product->setRenewal_redirect_url( stripslashes($row["renewal_redirect_url"]) );
				$product->setRenewal_html( stripslashes($row["renewal_html"]) );
				$product->setAccessUponFinalPayment( stripslashes($row["access_upon_final_payment"]) );
				$product->setBuyLink( stripslashes($row["buy_link"]) );
				$product->setShowBuyLink( stripslashes($row["show_buy_link"]) );
				//$product->setQuantity( stripslashes($row["quantity"]) );
				$ProductsList[] = $product;
			}

			return $ProductsList;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}

	//TODO: THIS MAY RESULT IN MORE THAN ONE PRODUCT.
	public static function getProductDetailsByResource($resource) {
		try {
			//logToFile("Resource: $resource"); 
			
			$dap_dbh = Dap_Connection::getConnection();
			//Load product details from database
			$sql = "select p.id as id,
							p.name as name,
							p.description as description,
							p.error_page_url as error_page_url,
							p.is_recurring as is_recurring,
							p.recurring_cycle_1 as recurring_cycle_1,
							p.recurring_cycle_2 as recurring_cycle_2,
							p.recurring_cycle_3 as recurring_cycle_3,
							p.thirdPartyEmailIds as thirdPartyEmailIds,
							p.sales_page_url as sales_page_url,
							p.logged_in_url as logged_in_url
				from
					dap_products p,
					dap_products_resources_jn prj,
					dap_file_resources fr
				where
					p.status = 'A' and
					p.id = prj.product_id and
					prj.status = 'A' and
					prj.resource_id = fr.id and
					prj.resource_type = 'F' and
					fr.url = :resource
					";
	
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':resource', $resource, PDO::PARAM_STR);
			$stmt->execute();
	
			while ($row = $stmt->fetch()) {
				$product = new Dap_Product();
				$product->setId( $row["id"] );
				$product->setName( $row["name"] );
				$product->setDescription( $row["description"] );
				$product->setPrice( $row["price"] );
				$product->setTrial_price( $row["trial_price"] );
				$product->setPrice_increment( $row["price_increment"] );
				$product->setPrice_increment_ceil( $row["price_increment_ceil"] );
				$product->setNum_sales( $row["num_sales"] );
				$product->setNum_days( $row["num_days"] );
				$product->setTimed_pricing_start_date( $row["timed_pricing_start_date"] );
				$product->setSelfservice_start_date( $row["selfservice_start_date"] );
				$product->setSelfservice_end_date( $row["selfservice_end_date"] );
				$product->setTotal_occur( $row["total_occur"] );
				$product->setError_page_url( $row["error_page_url"] );
				$product->setRecurring_cycle_1( $row["recurring_cycle_1"] );
				$product->setRecurring_cycle_2( $row["recurring_cycle_2"] );
				$product->setRecurring_cycle_3( $row["recurring_cycle_3"] );
				$product->setThirdPartyEmailIds( $row["thirdPartyEmailIds"] );
				$product->setSubscribeTo( stripslashes($row["subscribe_to"]) );
				$product->setUnsubscribeFrom( stripslashes($row["unsubscribe_from"]) );
				$product->setSales_page_url( $row["sales_page_url"] );
				$product->setSelf_service_allowed( stripslashes($row["self_service_allowed"]) );
				$product->setIs_master( stripslashes($row["is_master"]) );
				$product->setAllowContentLevelCredits( stripslashes($row["allowContentLevelCredits"]) );
				$product->setProduct_image_url( stripslashes($row["product_image_url"]) );
				$product->setCredits( stripslashes($row["credits"]) );
				$product->setLogged_in_url( stripslashes($row["logged_in_url"]) );
				$product->setIs_free_product( stripslashes($row["is_free_product"]) );
				$product->setAllow_free_signup( stripslashes($row["allow_free_signup"]) );
				$product->setAccessExpirationAction( stripslashes($row["access_expiration_action"]) );
				$product->setAccessUponFinalPayment( stripslashes($row["access_upon_final_payment"]) );
				$product->setBuyLink( stripslashes($row["buy_link"]) );
				$product->setShowBuyLink( stripslashes($row["show_buy_link"]) );
				//$product->setQuantity( stripslashes($row["quantity"]) );
				return $product;
			}
			return;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}

	
	public static function isPartOfHowManyDistinctProducts($resource) {
		try {
			//logToFile("in isPartOfHowManyDistinctProducts. resource: $resource"); 
			$dap_dbh = Dap_Connection::getConnection();
			$count = 0;
			$user = null;
			$sql = "";
		
			if( Dap_Session::isLoggedIn() ) { 
				//get userid
				$session = Dap_Session::getSession();
				$user = $session->getUser();
			}
			
			if ( !isset($user) || is_null($user) ) {
				$sql = "select 
							count(*) as count
						from
							dap_products_resources_jn prj,
							dap_file_resources fr
						where
							fr.url = :resource and
							fr.id = prj.resource_id and
							prj.status = 'A' and
							prj.resource_type = 'F'
						";
		
				//logToFile($sql,LOG_DEBUG_DAP);
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':resource', $resource, PDO::PARAM_STR);
				$stmt->execute();
		
				if ($row = $stmt->fetch()) {
					$count = $row["count"];
				}
			} 
			
			else { //user is available, so check only for products belonging to user
				$userId = $user->getId();
				//logToFile("userId $userId , so check only for products belonging to user"); 
				$sql = "select 
							count(*) as count
						from
							dap_file_resources fr,
							dap_products_resources_jn prj,
							dap_users_products_jn upj,
							dap_products p
						where
							fr.url = :resource and
							fr.id = prj.resource_id and
							prj.status = 'A' and 
							prj.resource_type = 'F' and
							prj.product_id = p.id and
							prj.product_id = upj.product_id and
							upj.user_id = :userId						
						";
		
				//logToFile($sql,LOG_DEBUG_DAP);
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':resource', $resource, PDO::PARAM_STR);
				$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
				$stmt->execute();
		
				if ($row = $stmt->fetch()) {
					$count = $row["count"];
				}
				
				
				if(intval($count) == 0) {
					//It is a logged in user - but user doesn't have access to product that has this resource
					//So do generic query again
					$sql2 = "select 
								count(*) as count
							from
								dap_products_resources_jn prj,
								dap_file_resources fr
							where
								fr.url = :resource and
								fr.id = prj.resource_id and
								prj.status = 'A' and
								prj.resource_type = 'F'
							";
		
					//logToFile($sql2,LOG_DEBUG_DAP);
					$stmt2 = $dap_dbh->prepare($sql2);
					$stmt2->bindParam(':resource', $resource, PDO::PARAM_STR);
					$stmt2->execute();
			
					if ($row2 = $stmt2->fetch()) {
						$count = $row2["count"];
					}
				}
			}
			
			$stmt = null;
			$row = null;
			$sql = null;
			$stmt2 = null;
			$row2 = null;
			$sql2 = null;
			$dap_dbh = null;
			
			return $count;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}

	function displayAssignedEmailResources ($productId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();

			$sql = "select
						prj.id as prjId,
						prj.start_day,
						prj.start_date,
						prj.is_free,
						prj.resource_id,
						prj.file_resource_id
						
					from
						dap_products_resources_jn prj
					where
						prj.product_id = :productId and
						prj.resource_type = 'E' 
					order by
						prj.start_day desc, prj.start_date desc";

			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_STR);
			$stmt->execute();

			$bgcolor = "#EEEEEE";
			$dataFound = false;
			
			/**
			$displayHTML = "<table width='100%' cellspacing='0' cellpadding='3'>
				<tr align='center' bgcolor=\"$bgcolor\" class=\"bodytext\">
				<td align='center'>&nbsp;</td>
				<td align='center'><b>Drip on<br/>Day #</b></td>
				<td align='center'><b>Is<br/>Free?</b></td>
				<td align='center'><b>Subject</b></td>
				<td align='center'>&nbsp;</td>
				</tr>";
			*/
				
			$displayHTML = "
				<div id='emailListHeader'>
					<ul>
						<li>
						<span style='width:40px; float:left'><strong>Edit</strong></span>
						<span style='width:85px; float:left; align=center'><strong>Send<br/>Day</strong></span>
						<span style='width:30px; float:left;'><a href=\"#\" onClick=\"javascript:removeAllEmailResourcesFromProduct(); return false;\" title=\"Delete all Emails from this Product\"><img src=\"/dap/images/ximage.jpg\" border=\"0\" width=\"20\" height=\"20\" style='align: middle'></a></span>
						<span style='display:block; overflow:auto;'><strong>Email Subject</strong></span>
						</li>
					</ul>
				</div>
				
				
				";

			$displayHTML .= "<div id='emailList'>
				<ul>";
				
			//$id=-1000;
			while ($row = $stmt->fetch()) {
				$dataFound = true;
				if($bgcolor == "#FFFFFF") {
					$bgcolor = "#EEEEEE";
				} else if ($bgcolor == "#EEEEEE") {
					$bgcolor = "#FFFFFF";
				}

				
				$prjId = $row["prjId"];
				$start_day = $row["start_day"];
				$start_date = $row["start_date"];
				$is_free = $row["is_free"];
				$resource_id = $row["resource_id"];
				$file_resource_id = $row["file_resource_id"];
				
				logToFile("displayAssignedEmailResources: resource_id: " . $resource_id,LOG_DEBUG_DAP);

				$sql = "select
						id,
						subject
					from
						dap_email_resources 
					where
						id = :resource_id";
				
				$sqlstmt = $dap_dbh->prepare($sql);
				$sqlstmt->bindParam(':resource_id', $resource_id, PDO::PARAM_STR);
				$sqlstmt->execute();
			
				$found=false;
				if ($emailrow = $sqlstmt->fetch()) {
					$found=true;
					$id = $emailrow["id"];
					$subject = mb_convert_encoding($emailrow["subject"], "auto");
					logToFile("displayAssignedEmailResources: id: " . $prjId,LOG_DEBUG_DAP);
					logToFile("displayAssignedEmailResources: subject: " . $subject,LOG_DEBUG_DAP);
				}
				
				if (!$found) {
					$id=$resource_id;
					logToFile("displayAssignedEmailResources: AUTOMATED EMAIL ROW FOUND: " . $id,LOG_DEBUG_DAP);
					$FileResource = Dap_FileResource::loadFileResourceAutomated($productId, $file_resource_id);
					//foreach($FileResources as $FileResource) {
					$resourceURL = $FileResource["url"];
					//break;
					//}
					$resourceURL = trimString($resourceURL,40,20,10);
					$subjectShort="Automated: " . $resourceURL;
					$subject=$subjectShort;
					logToFile("displayAssignedEmailResources:AUTOMATED EMAIL DRIP: " . $subjectShort,LOG_DEBUG_DAP);
				}
				logToFile("displayAssignedEmailResources: EMAIL ROW FOUND: " . $id,LOG_DEBUG_DAP);
				
				$whichOne = "";

				if( ($start_day != "") && ($start_day != 0) ){
					$whichOne = $start_day;
				} else if( ($start_date != "") && ($start_date != 0) ){
					$whichOne = $start_date;
				}
				
				$isEnglish = true;
				if (preg_match('/[^A-Za-z0-9]/', $subject)) {
					logToFile("Inside preg_match - this is NON English"); 
					$isEnglish = false;
				}
				
				$subjectShort = $subject;
				if( $isEnglish && (strlen($subject) > 40)) {
					$subjectShort = substr($subject,0,20) . "......." . substr($subject,-10);
				}

				/**
				$displayHTML .= "<tr bgcolor=\"$bgcolor\" class=\"bodytext\">
				<td><a href=\"javascript:\" onClick=\"loadEmailResource($id, $prjId); clearDiv('email_resource_message'); return false;\" title='Click to edit email drip settings (when this email is to be sent)'>edit</a></td>
				<td align='center'>$whichOne</td>
				<td align='center'>$is_free</td>
				<td align='left'><span title='$subject'>$subjectShort</span></td>
				<td width='20' align='center'><a href=\"javascript:\" onClick=\"javascript:removeEmailResourceFromProduct($id, $prjId); return false;\"><img src=\"/dap/images/ximage.jpg\" border=\"0\" width=\"13\" height=\"13\"></a></td>
				</tr>";
				*/
				
				
				if($id > -1000) {
				   	logToFile("if: displayAssignedEmailResources: emailresource found: " . $id,LOG_DEBUG_DAP);
					$displayHTML .= "<li id='recordsArrayEmail_".$id."'>
					<span style='width:40px; float:left'>&nbsp;&nbsp;<a href=\"#\" onClick=\"loadEmailResource($id, $prjId); clearDiv('email_resource_message'); return false;\" title='Click to edit email drip settings (when this email is to be sent)'><img src='/dap/images/edit.png' width='12' height='12' style='align:absbottom' border='0'/></a></span>
					<span style='width:85px; float:left'>$whichOne</span>
					<span style='width:30px; float:left'><a href=\"#\" onClick=\"javascript:removeEmailResourceFromProduct($id, $prjId); return false;\" title=\"Delete this Email from this Product\"><img src=\"/dap/images/ximage.jpg\" border=\"0\" width=\"15\" height=\"14\"></a></span>
					<span style='display:block; overflow:auto; background-color:#EFEFEF;' title='$title'>$subjectShort</span>
					</li>
				";
				}
				else {
					logToFile("else: displayAssignedEmailResources: emailresource found: " . $id,LOG_DEBUG_DAP);
					//$id=$id-1;
					
					$displayHTML .= "<li id='recordsArrayEmail_".$id."'>
					<span style='width:40px; float:left'>&nbsp;&nbsp;<a href=\"#\" onClick=\"loadEmailResource($id, $prjId); clearDiv('email_resource_message'); return false;\" title='Click to edit email drip settings (when this email is to be sent)'><img src='/dap/images/edit.png' width='12' height='12' style='align:absbottom' border='0'/></a></span>
					<span style='width:85px; float:left'>$whichOne</span>
					<span style='width:30px; float:left'><a href=\"#\" onClick=\"javascript:removeEmailResourceFromProduct($id, $prjId); return false;\" title=\"Delete this Email from this Product\"><img src=\"/dap/images/ximage.jpg\" border=\"0\" width=\"15\" height=\"14\"></a></span>
					<span style='display:block; overflow:auto; background-color:#EFEFEF;' title='$title'>$subjectShort</span>
					</li>";
					
					logToFile("displayAssignedEmailResources: emailresource NOT found: " . $id,LOG_DEBUG_DAP);
				}
				
			}

			if($dataFound == false) {
				$displayHTML .= "There are no Emails currently assigned to this Product";
			}
			
			$displayHTML .= "</ul></div>";
			
			logToFile("displayAssignedEmailResources: " . $displayHTML); 
			
			return $displayHTML;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			return $e->getMessage();
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			return $e->getMessage();
		}
	}
	
	
	function displayAssignedEmailResourcesUsingDripDay ($productId, $dripDay) {
		try {
			$dap_dbh = Dap_Connection::getConnection();

			$sql = "select
						prj.id as prjId,
						prj.start_day,
						prj.start_date,
						prj.is_free,
						prj.resource_id,
						prj.file_resource_id
						
					from
						dap_products_resources_jn prj
					where
						prj.product_id = :productId and
						prj.resource_type = 'E' and
						prj.start_day = :dripDay
					order by
						prj.start_day desc, prj.start_date desc";

			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_STR);
			$stmt->bindParam(':dripDay', $dripDay, PDO::PARAM_STR);
			$stmt->execute();

			$bgcolor = "#EEEEEE";
			$dataFound = false;
			
			/**
			$displayHTML = "<table width='100%' cellspacing='0' cellpadding='3'>
				<tr align='center' bgcolor=\"$bgcolor\" class=\"bodytext\">
				<td align='center'>&nbsp;</td>
				<td align='center'><b>Drip on<br/>Day #</b></td>
				<td align='center'><b>Is<br/>Free?</b></td>
				<td align='center'><b>Subject</b></td>
				<td align='center'>&nbsp;</td>
				</tr>";
			*/
				
			$displayHTML = "
				<div id='emailListHeader'>
					<ul>
						<li>
						<span style='width:85px; float:left; align=center'><strong>Send<br/>Day</strong></span>
						<span style='display:block; overflow:auto;'><strong>Email Subject</strong></span>
						</li>
					</ul>
				</div>
				
				
				";

			$displayHTML .= "<div id='emailList'>
				<ul>";
				
			//$id=-1000;
			while ($row = $stmt->fetch()) {
				$dataFound = true;
				if($bgcolor == "#FFFFFF") {
					$bgcolor = "#EEEEEE";
				} else if ($bgcolor == "#EEEEEE") {
					$bgcolor = "#FFFFFF";
				}

				
				$prjId = $row["prjId"];
				$start_day = $row["start_day"];
				$start_date = $row["start_date"];
				$is_free = $row["is_free"];
				$resource_id = $row["resource_id"];
				$file_resource_id = $row["file_resource_id"];
				
				logToFile("displayAssignedEmailResources: resource_id: " . $resource_id,LOG_DEBUG_DAP);

				$sql = "select
						id,
						subject
					from
						dap_email_resources 
					where
						id = :resource_id";
				
				$sqlstmt = $dap_dbh->prepare($sql);
				$sqlstmt->bindParam(':resource_id', $resource_id, PDO::PARAM_STR);
				$sqlstmt->execute();
			
				$found=false;
				if ($emailrow = $sqlstmt->fetch()) {
					$found=true;
					$id = $emailrow["id"];
					$subject = mb_convert_encoding($emailrow["subject"], "auto");
					logToFile("displayAssignedEmailResources: id: " . $prjId,LOG_DEBUG_DAP);
					logToFile("displayAssignedEmailResources: subject: " . $subject,LOG_DEBUG_DAP);
				}
				
				if (!$found) {
					$id=$resource_id;
					logToFile("displayAssignedEmailResources: AUTOMATED EMAIL ROW FOUND: " . $id,LOG_DEBUG_DAP);
					$FileResource = Dap_FileResource::loadFileResourceAutomated($productId, $file_resource_id);
					//foreach($FileResources as $FileResource) {
					$resourceURL = $FileResource["url"];
					//break;
					//}
					$resourceURL = trimString($resourceURL,40,20,10);
					$subjectShort="Automated: " . $resourceURL;
					$subject=$subjectShort;
					logToFile("displayAssignedEmailResources:AUTOMATED EMAIL DRIP: " . $subjectShort,LOG_DEBUG_DAP);
				}
				logToFile("displayAssignedEmailResources: EMAIL ROW FOUND: " . $id,LOG_DEBUG_DAP);
				
				$whichOne = "";

				if( ($start_day != "") && ($start_day != 0) ){
					$whichOne = $start_day;
				} else if( ($start_date != "") && ($start_date != 0) ){
					$whichOne = $start_date;
				}
				
				$isEnglish = true;
				if (preg_match('/[^A-Za-z0-9]/', $subject)) {
					logToFile("Inside preg_match - this is NON English"); 
					$isEnglish = false;
				}
				
				$subjectShort = $subject;
				if( $isEnglish && (strlen($subject) > 40)) {
					$subjectShort = substr($subject,0,20) . "......." . substr($subject,-10);
				}

				/**
				$displayHTML .= "<tr bgcolor=\"$bgcolor\" class=\"bodytext\">
				<td><a href=\"javascript:\" onClick=\"loadEmailResource($id, $prjId); clearDiv('email_resource_message'); return false;\" title='Click to edit email drip settings (when this email is to be sent)'>edit</a></td>
				<td align='center'>$whichOne</td>
				<td align='center'>$is_free</td>
				<td align='left'><span title='$subject'>$subjectShort</span></td>
				<td width='20' align='center'><a href=\"javascript:\" onClick=\"javascript:removeEmailResourceFromProduct($id, $prjId); return false;\"><img src=\"/dap/images/ximage.jpg\" border=\"0\" width=\"13\" height=\"13\"></a></td>
				</tr>";
				*/
				
				
				if($id > -1000) {
				   	logToFile("if: displayAssignedEmailResources: emailresource found: " . $id,LOG_DEBUG_DAP);
					$displayHTML .= "<li id='recordsArrayEmail_".$id."'>
					<span style='width:85px; float:left'>$whichOne</span>
					<span style='display:block; overflow:auto; background-color:#EFEFEF;' title='$title'>$subjectShort</span>
					</li>
				";
				}
				else {
					logToFile("else: displayAssignedEmailResources: emailresource found: " . $id,LOG_DEBUG_DAP);
					//$id=$id-1;
					
					$displayHTML .= "<li id='recordsArrayEmail_".$id."'>
					<span style='width:85px; float:left'>$whichOne</span>
					<span style='display:block; overflow:auto; background-color:#EFEFEF;' title='$title'>$subjectShort</span>
					</li>";
					
					logToFile("displayAssignedEmailResources: emailresource NOT found: " . $id,LOG_DEBUG_DAP);
				}
				
			}

			if($dataFound == false) {
				$displayHTML .= "There are no Emails currently assigned to this Product";
			}
			
			$displayHTML .= "</ul></div>";
			
			logToFile("displayAssignedEmailResources: " . $displayHTML); 
			
			return $displayHTML;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			return $e->getMessage();
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			return $e->getMessage();
		}
	}
	
	function displayAssignedEmailResourcesORIG($productId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();

			$sql = "select
						prj.id as prjId,
						er.id,
						er.subject,
						prj.start_day,
						prj.start_date,
						prj.is_free
					from
						dap_products_resources_jn prj,
						dap_email_resources er
					where
						prj.product_id = :productId and
						prj.resource_type = 'E' and
						prj.resource_id = er.id
					order by
						prj.start_day desc, prj.start_date desc";

			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_STR);
			$stmt->execute();

			$bgcolor = "#EEEEEE";
			$dataFound = false;
			
			/**
			$displayHTML = "<table width='100%' cellspacing='0' cellpadding='3'>
				<tr align='center' bgcolor=\"$bgcolor\" class=\"bodytext\">
				<td align='center'>&nbsp;</td>
				<td align='center'><b>Drip on<br/>Day #</b></td>
				<td align='center'><b>Is<br/>Free?</b></td>
				<td align='center'><b>Subject</b></td>
				<td align='center'>&nbsp;</td>
				</tr>";
			*/
				
			$displayHTML = "
				<div id='emailListHeader'>
					<ul>
						<li>
						<span style='width:40px; float:left'><strong>Edit</strong></span>
						<span style='width:85px; float:left; align=center'><strong>Send<br/>Day</strong></span>
						<span style='width:30px; float:left;'><a href=\"#\" onClick=\"javascript:removeAllEmailResourcesFromProduct(); return false;\" title=\"Delete all Emails from this Product\"><img src=\"/dap/images/ximage.jpg\" border=\"0\" width=\"20\" height=\"20\" style='align: middle'></a></span>
						<span style='display:block; overflow:auto;'><strong>Email Subject</strong></span>
						</li>
					</ul>
				</div>
				
				
				";

			$displayHTML .= "<div id='emailList'>
				<ul>";

			while ($row = $stmt->fetch()) {
				$dataFound = true;
				if($bgcolor == "#FFFFFF") {
					$bgcolor = "#EEEEEE";
				} else if ($bgcolor == "#EEEEEE") {
					$bgcolor = "#FFFFFF";
				}

				$id = $row["id"];
				$prjId = $row["prjId"];
				$subject = $row["subject"];
				$start_day = $row["start_day"];
				$start_date = $row["start_date"];
				$is_free = $row["is_free"];

				$whichOne = "";

				if( ($start_day != "") && ($start_day != 0) ){
					$whichOne = $start_day;
				} else if( ($start_date != "") && ($start_date != 0) ){
					$whichOne = $start_date;
				}
				
				$subjectShort = $subject;
				if( strlen($subject) > 40) {
					$subjectShort = substr($subject,0,20) . "......." . substr($subject,-10);
				}
				
				$subjectShort = mb_convert_encoding($subjectShort, "UTF-8", "auto");

				/**
				$displayHTML .= "<tr bgcolor=\"$bgcolor\" class=\"bodytext\">
				<td><a href=\"javascript:\" onClick=\"loadEmailResource($id, $prjId); clearDiv('email_resource_message'); return false;\" title='Click to edit email drip settings (when this email is to be sent)'>edit</a></td>
				<td align='center'>$whichOne</td>
				<td align='center'>$is_free</td>
				<td align='left'><span title='$subject'>$subjectShort</span></td>
				<td width='20' align='center'><a href=\"javascript:\" onClick=\"javascript:removeEmailResourceFromProduct($id, $prjId); return false;\"><img src=\"/dap/images/ximage.jpg\" border=\"0\" width=\"13\" height=\"13\"></a></td>
				</tr>";
				*/
				
				
				$displayHTML .= "<li id='recordsArrayEmail_".$id."'>
					<span style='width:40px; float:left'>&nbsp;&nbsp;<a href=\"#\" onClick=\"loadEmailResource($id, $prjId); clearDiv('email_resource_message'); return false;\" title='Click to edit email drip settings (when this email is to be sent)'><img src='/dap/images/edit.png' width='12' height='12' style='align:absbottom' border='0'/></a></span>
					<span style='width:85px; float:left'>$whichOne</span>
					<span style='width:30px; float:left'><a href=\"#\" onClick=\"javascript:removeEmailResourceFromProduct($id, $prjId); return false;\" title=\"Delete this Email from this Product\"><img src=\"/dap/images/ximage.jpg\" border=\"0\" width=\"15\" height=\"14\"></a></span>
					<span style='display:block; overflow:auto; background-color:#EFEFEF;' title='$subject'>$subjectShort</span>
					</li>
				";
			}

			if($dataFound == false) {
				$displayHTML .= "There are no Emails currently assigned to this Product";
			}
			
			$displayHTML .= "</ul></div>";
			
			return $displayHTML;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			return $e->getMessage();
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			return $e->getMessage();
		}
	}

	public static function deleteProduct($id) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$dap_dbh->beginTransaction(); //begin the transaction
			$response = "SUCCESS! Product $productId was deleted from the database";
			$count = 0;

			//Check if there are any users associated with this product
			$sql = "select count(*) as count from dap_users_products_jn where product_id = :id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();

			if ($row = $stmt->fetch()) {
				$count = $row["count"];
				if($count > 0) {
					return "There are Users associated with this Product. <br/>Remove them first before you can delete this Product.";
				}
			}

			//$sql = "delete from dap_products_resources_jn where product_id = :id";
			$sql = "DELETE 
						prj, fr
					FROM 
						dap_products_resources_jn AS prj
					LEFT JOIN 
						dap_file_resources AS fr ON prj.id = fr.id
					WHERE 
						prj.product_id = :id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			
			//If none, then delete from dap_users_products_jn table
			$sql = "delete from dap_users_products_jn where product_id = :id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();

			$sql = "delete from dap_products where id = :id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			
			$sql = "delete from
						dap_file_resources
					where
						id not in 
							(select distinct resource_id from dap_products_resources_jn where resource_type = 'F')";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();
					

			$dap_dbh->commit(); //commit the transaction
			$sql = null;
			$stmt = null;
			$dap_dbh = null;

			return $response;

		} catch (PDOException $e) {
			$dap_dbh->rollback();
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			$dap_dbh->rollback();
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}

	}

	/* 
		Copy all resources from one product to another, if not exists in another already.
		Params: source product id, destination product id.
		Return: nothing.
	*/
	public static function copyResourcesP2P($srcProductId, $destProductId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$dap_dbh->beginTransaction(); //begin the transaction
			
			//TODO: check if both source and destination products exists.					
			
			/*
				Lets get all product-resource rows for the src product id.				
			*/
			$sql = "SELECT 
						resource_id, 
						is_free, 
						start_day, 
						end_day, 
						start_date, 
						end_date,
						num_clicks,
						resource_type, 
						status,
						credits_assigned, 
						sss_enabled, 
						excerpt, 
						display_order
					FROM
						dap_products_resources_jn prj
					WHERE
						prj.product_id = :productId";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':productId', $srcProductId, PDO::PARAM_INT);
			$stmt->execute();
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

			foreach ($results as $key => $row) {
				//Lets insert into products resources join table.
				try {
					$sql2 = "insert into dap_products_resources_jn
								(product_id, resource_id, is_free, start_day, end_day, 
								start_date, end_date, num_clicks, resource_type, status, credits_assigned, sss_enabled, excerpt, display_order)
								values(:product_id, :resource_id, :is_free, :start_day, :end_day,
								:start_date, :end_date, :num_clicks, :resource_type, :status, :credits_assigned, :sss_enabled, :excerpt, :display_order)";		
					$stmt2 = $dap_dbh->prepare($sql2);
					$stmt2->bindParam(':product_id', $destProductId, PDO::PARAM_INT);
					$stmt2->bindParam(':resource_id', $row['resource_id'], PDO::PARAM_INT);
					$stmt2->bindParam(':is_free', $row['is_free'], PDO::PARAM_STR);
					$stmt2->bindParam(':start_day', $row['start_day'], PDO::PARAM_INT);
					$stmt2->bindParam(':end_day', $row['end_day'], PDO::PARAM_INT);
					$stmt2->bindParam(':start_date', $row['start_date'], PDO::PARAM_STR);
					$stmt2->bindParam(':end_date', $row['end_date'], PDO::PARAM_STR);
					$stmt2->bindParam(':num_clicks', $row['num_clicks'], PDO::PARAM_INT);
					$stmt2->bindParam(':resource_type', $row['resource_type'], PDO::PARAM_STR);
					$stmt2->bindParam(':status', $row['status'], PDO::PARAM_STR);
					$stmt2->bindParam(':credits_assigned', $row['credits_assigned'], PDO::PARAM_INT);
					$stmt2->bindParam(':sss_enabled', $row['sss_enabled'], PDO::PARAM_STR);
					$stmt2->bindParam(':excerpt', $row['excerpt'], PDO::PARAM_STR);
					$stmt2->bindParam(':display_order', $row['display_order'], PDO::PARAM_INT);
					$stmt2->execute();	
				} catch (PDOException $e) {
					logToFile($e->getMessage());
					throw $e;
				} catch (Exception $e) {
					logToFile($e->getMessage());
					throw $e;
				}		
			}
			
			$dap_dbh->commit(); //commit the transaction
			$stmt = null;
			$dap_dbh = null;
			//return "<b>SUCCESS: File '" . $file . "' <br/>has been added to this product</b><br/><br/>";
		} catch (PDOException $e1) { 
			$dap_dbh->rollback();
			logToFile($e1->getMessage(),LOG_FATAL_DAP);
			throw $e1;
		} catch (Exception $e1) {
			$dap_dbh->rollback();
			logToFile($e1->getMessage(),LOG_FATAL_DAP);
			throw $e1;
		}	
	}


	public static function getMinProductId() {
		$dap_dbh = Dap_Connection::getConnection();
		$id = 0;
		
		$sql = "select 
					min(id) as id
				from
					dap_products";
					
		$stmt = $dap_dbh->prepare($sql);
		$stmt->execute();

		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$id = $row["id"];
		}

		return $id;
	}


	public static function isResourceProtectedInThisProduct($productId, $url) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$isProtected = false;
			
			//logToFile("url: $url"); 

			$sql = "select
						fr.id
					from
						dap_products_resources_jn prj,
						dap_file_resources fr
					where
						fr.url like :url and
						fr.id = prj.resource_id and
						prj.product_id = :productId and
						prj.resource_type = 'F'
					";

			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':url', $url, PDO::PARAM_STR);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->execute();
			
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$isProtected = true;
			}

			//logToFile("isProtected: $isProtected"); 
			return $isProtected;
			
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			return $e->getMessage();
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			return $e->getMessage();
		}
	}
	
	
	public static function getProtectedResourceCount($productId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$protectedResourceCount = 0;

			$sql = "select 
						count(*) as count
					from
						dap_products_resources_jn prj,
						dap_file_resources fr
					where
						prj.product_id = :productId and
						prj.resource_id = fr.id and
						prj.resource_type = 'F'
					order by
						prj.start_day desc, 
						prj.start_date desc, 
						prj.display_order desc,
						prj.num_clicks desc
						";

			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_STR);
			$stmt->execute();

			while ($row = $stmt->fetch()) {
				$protectedResourceCount = $row["count"];
			}

			return $protectedResourceCount;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			return $e->getMessage();
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			return $e->getMessage();
		}
	}
	

}
?>