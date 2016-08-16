<?php

class Dap_UsersProducts {

	var $user_id  ;
	var $product_id ;
	var $access_start_date ;
	var $access_end_date ;
	var $transaction_id;
	var $coupon_id;
	var $status;

	//Additional field from dap_products
	var $product_name ;

	function getUser_id()  {
	       return $this->user_id;
	}
	function setUser_id($o) {
	      $this->user_id = $o;
	}
	
	function getProduct_id()  {
	       return $this->product_id;
	}
	function setProduct_id($o) {
	      $this->product_id = $o;
	}

	function getCoupon_id()  {
	       return $this->coupon_id;
	}
	function setCoupon_id($o) {
	      $this->coupon_id = $o;
	}
	
	function getAccess_start_date()  {
	       return $this->access_start_date;
	}
	function setAccess_start_date($o) {
	      $this->access_start_date = $o;
	}	

	function getAccess_end_date()  {
	       return $this->access_end_date;
	}
	function setAccess_end_date($o) {
	      $this->access_end_date = $o;
	}	
	
	function getTransaction_id()  {
	       return $this->transaction_id;
	}
	function setTransaction_id($o) {
	      $this->transaction_id = $o;
	}		
	
	function getStatus()  {
	       return $this->status;
	}
	function setStatus($o) {
	      $this->status = $o;
	}		
	
	function getProduct_name()  {
	       return $this->product_name;
	}
	function setProduct_name($o) {
	      $this->product_name = $o;
	}

	public static function expireUserProductAccessOnCancel($userId, $productId) {
		logToFile("(Dap_UsersProducts.expireUserProductAccessOnCancel() - Method Init.");
		
		try {

			$dap_dbh = Dap_Connection::getConnection();
			logToFile("(Dap_UsersProducts.expireUserProductAccessOnCancel() - move forward the entire block...set access end date to previous date from current date.");
			// move forward the entire block (set access end date to previous date from current date)
			$update_sql = "update dap_products p, dap_users_products_jn upj, dap_users u
						   set upj.access_start_date=DATE( now() - INTERVAL (DATEDIFF( upj.access_end_date,upj.access_start_date) + 1) DAY ),
						   upj.access_end_date =  DATE ( now() - INTERVAL (1) DAY )
						   where upj.user_id = u.id 
						   AND upj.product_id = p.id 
						   AND u.id = :userId
						   AND product_id = :productId 
						   AND datediff( curdate( ) , upj.access_end_date ) >0
						   AND datediff( curdate( ) , DATE( now() - INTERVAL (DATEDIFF( upj.access_end_date,upj.access_start_date) + 1) DAY ) ) !=  curdate( )";
						  
			$update_stmt = $dap_dbh->prepare($update_sql);
			
			logToFile("Dap_UsersProducts.expireUserProductAccessOnCancel()- action=EXPIREACCESS : uid=$userId, pid=$productId",LOG_DEBUG_DAP);  
			
			$update_stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$update_stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$update_stmt->execute();  
			
			$update_stmt = null;
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
	
	function extendEndDays($extend_days) {
		if($this->access_end_date != "9999-12-31") {
		  $date = new DateTime($this->access_end_date);
		  
		  $date->modify("+".$extend_days." day");
		  $this->access_end_date =  $date->format("Y-m-d");
		  logToFile("Dap_UsersProducts::extendEndDays extending access_end_date=" . $this->access_end_date);
		} 
		else {
			logToFile("Dap_UsersProducts::extendEndDays not extending, date already 9999-12-31");
		}
		
	}
	//do diff between access end date and access start date
	function getActiveDays() {
		logToFile("Dap_UsersProducts::getActiveDays access_start_date:".$this->access_start_date.", access_end_date:".$this->access_end_date);
		
		$start_date = new DateTime($this->access_start_date);
	//	logToFile("Dap_UsersProducts::getActiveDays start_date:".$start_date);
		$ts1 =  $start_date->format("Y-m-d");
		logToFile("Dap_UsersProducts::getActiveDays ts1:".$ts1);
	//	logToFile("Dap_UsersProducts::getActiveDays access_end_date:".$this->access_end_date);
		$end_date = new DateTime($this->access_end_date);
		//logToFile("Dap_UsersProducts::getActiveDays end_date:".$end_date);
		$ts2 =  $end_date->format("Y-m-d");
		logToFile("Dap_UsersProducts::getActiveDays ts2:".$ts2);
		$date_diff = abs(strtotime($ts2) - strtotime($ts1));
		logToFile("Dap_UsersProducts::getActiveDays date_diff:".$date_diff);
		//echo $date_diff;
		$full_days = floor($date_diff / 86400) + 1;
		logToFile("Dap_UsersProducts::getActiveDays Start Date:".$ts1.", End Date:".$ts2.", Date Diff:".$date_diff . ", full_days:" . $full_days);
		return $full_days;
	}

	
	function deriveAccessEndDate($extend_days) {
		if($extend_days < 0) {
			$this->access_end_date = "9999-12-31";
		} else {
			$date = new DateTime($this->access_start_date);
			$date->modify("+".$extend_days." day");
			$this->access_end_date =  $date->format("Y-m-d");	
		}
	}
	
	public static function loadArray($userId, $productId) {
		try {
		
			$dap_dbh = Dap_Connection::getConnection();
			$userProductRelArray = array();
	
			$sql = "select
						upj.user_id,
						upj.product_id,
						upj.access_start_date,
						upj.access_end_date,
						upj.transaction_id,
						upj.coupon_id,
						upj.status				
					from 
						dap_users_products_jn upj
					where
						upj.user_id = :userId and
						upj.product_id = :productId
					";
	
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->execute();	
			
			if ($obj = $stmt->fetch()) {
				$userProductRelArray[] = $obj;
			}
	
			return $userProductRelArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	
	public static function loadUsersByIds($userId, $productId, $inClause) {
		try {
		
			$dap_dbh = Dap_Connection::getConnection();
			$userProductRelArray = array();
	
			$sql = "select
						u.id,
						u.first_name,
						u.last_name,
						u.email,
						u.paypal_email,
						upj.product_id,
						upj.access_start_date,
						upj.access_end_date,
						upj.transaction_id,
						upj.status,
						upj.transaction_id,
						upj.coupon_id,
						p.name as product_name
					from 
						dap_users u,
						dap_users_products_jn upj,
						dap_products p
					where
						u.id in $inClause and
						u.id = upj.user_id and
						upj.product_id = $productId and
						upj.product_id = p.id
					";
	
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			//$stmt->bindParam(':userId', $inClause, PDO::PARAM_STR);
			//$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->execute();	
			
			if ($obj = $stmt->fetch()) {
				$userProductRelArray[] = $obj;
			}
	
			return $userProductRelArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	
	public static function load($userId, $productId) {
		try {
		
			$dap_dbh = Dap_Connection::getConnection();
			$userProduct = NULL;
	
			$sql = "select
						upj.user_id,
						upj.product_id,
						upj.access_start_date,
						upj.access_end_date,
						upj.transaction_id,
						upj.coupon_id,
						upj.status				
					from 
						dap_users_products_jn upj
					where
						upj.user_id = :userId and
						upj.product_id = :productId
					";
	
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->execute();	
			
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$userProduct = new Dap_UsersProducts();
				$userProduct->setUser_id($row['user_id']);
				$userProduct->setProduct_id($row['product_id']);
				$userProduct->setAccess_start_date($row['access_start_date']);
				$userProduct->setAccess_end_date($row['access_end_date']);
				$userProduct->setTransaction_id($row['transaction_id']);
				$userProduct->setCoupon_id($row['coupon_id']);
				$userProduct->setStatus($row['status']);				
			}
			$stmt = null;
			$dap_dbh = null;

			return $userProduct;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}

	//Load UsersProducts relationships for a given user.	
	public static function loadProducts($userId) {
		try {
		
			$dap_dbh = Dap_Connection::getConnection();
			$userProductRelArray = array();
	
			$sql = "select
						upj.user_id,
						upj.product_id,
						upj.access_start_date,
						upj.access_end_date,
						upj.transaction_id,
						upj.coupon_id,
						upj.status				
					from 
						dap_users_products_jn upj
					where
						upj.user_id = :userId and
						upj.status = 'A' and
						CURDATE() >= access_start_date
					";
	
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->execute();	
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$userProduct = new Dap_UsersProducts();
				$userProduct->setUser_id($row['user_id']);
				$userProduct->setProduct_id($row['product_id']);
				$userProduct->setAccess_start_date($row['access_start_date']);
				$userProduct->setAccess_end_date($row['access_end_date']);
				$userProduct->setTransaction_id($row['transaction_id']);
				$userProduct->setCoupon_id($row['coupon_id']);
				$userProduct->setStatus($row['status']);				
				$userProductRelArray[] = $userProduct;
			}
			$stmt = null;
			$dap_dbh = null;

			return $userProductRelArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}	
	
	public static function loadProductsForUser($userId) {
		try {
		
			$dap_dbh = Dap_Connection::getConnection();
			$userProductRelArray = array();
	
			$sql = "select
						upj.user_id,
						upj.product_id,
						upj.access_start_date,
						upj.access_end_date,
						upj.transaction_id,
						upj.coupon_id,
						upj.status				
					from 
						dap_users_products_jn upj
					where
						upj.user_id = :userId
					";
	
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->execute();	
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$userProduct = new Dap_UsersProducts();
				$userProduct->setUser_id($row['user_id']);
				$userProduct->setProduct_id($row['product_id']);
				$userProduct->setAccess_start_date($row['access_start_date']);
				$userProduct->setAccess_end_date($row['access_end_date']);
				$userProduct->setTransaction_id($row['transaction_id']);
				$userProduct->setCoupon_id($row['coupon_id']);
				$userProduct->setStatus($row['status']);				
				$userProductRelArray[] = $userProduct;
			}
			$stmt = null;
			$dap_dbh = null;

			return $userProductRelArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	
	//Load UsersProducts relationships for a given user.	
	public static function loadProductsIgnoreStatus($userId) {
		try {
		
			$dap_dbh = Dap_Connection::getConnection();
			$userProductRelArray = array();
	
			$sql = "select
						upj.user_id,
						upj.product_id,
						upj.access_start_date,
						upj.access_end_date,
						upj.transaction_id,
						upj.coupon_id,
						upj.status				
					from 
						dap_users_products_jn upj
					where
						upj.user_id = :userId and
						CURDATE() >= access_start_date
					";
	
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->execute();	
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$userProduct = new Dap_UsersProducts();
				$userProduct->setUser_id($row['user_id']);
				$userProduct->setProduct_id($row['product_id']);
				$userProduct->setAccess_start_date($row['access_start_date']);
				$userProduct->setAccess_end_date($row['access_end_date']);
				$userProduct->setTransaction_id($row['transaction_id']);
				$userProduct->setCoupon_id($row['coupon_id']);
				$userProduct->setStatus($row['status']);				
				$userProductRelArray[] = $userProduct;
			}
			$stmt = null;
			$dap_dbh = null;

			return $userProductRelArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	
	//Create
	public function create($dap_dbh=NULL) {
		try {
			$dap_dbh = Dap_Connection::getConnection($dap_dbh);
			$product = Dap_Product::loadProduct($this->getProduct_id());
			$status = "A";
			
			if( ($product->getDouble_optin_subject() != "") && ($product->getDouble_optin_body() != "") ) {
				//This is double option - so set UPJ status = "I"
				$status = "I";
			}
			
			if($this->getCoupon_id() == "") $this->setCoupon_id(null);

			$sql = "insert into dap_users_products_jn
						(user_id, product_id, access_start_date, access_end_date, transaction_id, coupon_id, status)
					values
						(:user_id, :product_id, :access_start_date, :access_end_date, :transaction_id, :coupon_id, :status)
					";
			//logToFile($sql,LOG_INFO_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':user_id', $this->getUser_id(), PDO::PARAM_INT);
			$stmt->bindParam(':product_id', $this->getProduct_id(), PDO::PARAM_INT);
			$stmt->bindParam(':access_start_date', $this->getAccess_start_date(), PDO::PARAM_STR);
			$stmt->bindParam(':access_end_date', $this->getAccess_end_date(), PDO::PARAM_STR);
			$stmt->bindParam(':transaction_id', $this->getTransaction_id(), PDO::PARAM_STR);		
			$stmt->bindParam(':coupon_id', $this->getCoupon_id(), PDO::PARAM_INT);		
			$stmt->bindParam(':status', $status, PDO::PARAM_STR);
			$stmt->execute();
			$lastid = $dap_dbh->lastInsertId();
			//logToFile("lastid: $lastid"); 
			$stmt = null;
			
			//Send out welcome/activation email only if access_start_date of product is greater than or equal to today
			//Meaning, don't send out any emails for post-dated products	
			$access_start_date = $this->getAccess_start_date();
			
			logToFile("newdate=".$newdate); 
			logToFile("currdate=".strtotime(date("Y-m-d"))); 
			if( date("Y-m-d", strtotime($access_start_date)) > date("Y-m-d")) {
				return $lastid; //simply return without sending emails
			}
			
			if( ($product->getDouble_optin_subject() != "") && ($product->getDouble_optin_body() != "") ) {
				logToFile("This is double optin"); 
				$user = Dap_User::loadUserById($this->getUser_id());
				sendUserProductActivationEmail($user, $this->getProduct_id());
			} else {
				logToFile("This is single optin"); 
				sendUserProductWelcomeEmail($this->getUser_id(), $this->getProduct_id());
			}
			
			//$dap_dbh = null;
			return $lastid;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
		
	}
	//Update
	
	public function update($dap_dbh=NULL) {
		try {
			
			$dap_dbh = Dap_Connection::getConnection($dap_dbh);
			$sql = "update dap_users_products_jn set
						access_start_date =:access_start_date,
						access_end_date =:access_end_date,
						transaction_id =:transaction_id,
						coupon_id =:coupon_id,
						status =:status
					where
						user_id = :userId and
						product_id = :productId
					";

			$stmt = $dap_dbh->prepare($sql);
			logToFile("Dap_UsersProducts.update() - Sql:".$sql);
			logToFile("Dap_UsersProducts.update() - UserId:".$this->getUser_id().", ProductId:".$this->getProduct_id());
			$stmt->bindParam(':access_start_date', $this->getAccess_start_date(), PDO::PARAM_STR);
			$stmt->bindParam(':access_end_date', $this->getAccess_end_date(), PDO::PARAM_STR);
			$stmt->bindParam(':transaction_id', $this->getTransaction_id(), PDO::PARAM_STR);
			$stmt->bindParam(':status', $this->getStatus(), PDO::PARAM_STR);
			$stmt->bindParam(':userId', $this->getUser_id(), PDO::PARAM_INT);
			$stmt->bindParam(':productId', $this->getProduct_id(), PDO::PARAM_INT);
			$stmt->bindParam(':coupon_id', $this->getCoupon_id(), PDO::PARAM_INT);
				
			$stmt->execute();	
			$stmt = null;
			//$dap_dbh = null;
			Dap_UsersProducts::cleanUsersProducts($dap_dbh);
			return;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
		
	}
	
	//Delete
	public function delete($dap_dbh=NULL) {
		try {
			$dap_dbh = Dap_Connection::getConnection($dap_dbh);
			//delete from usersproducts table
			$sql = "delete from  
					dap_users_products_jn
					where user_id =:user_id and
					product_id =:product_id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':user_id', $this->getUser_id(), PDO::PARAM_INT);
			$stmt->bindParam(':product_id', $this->getProduct_id(), PDO::PARAM_INT);
			$stmt->execute();
			$stmt = null;
			//$dap_dbh = null;		
			return;			
		} catch (PDOException $e) {
			$stmt = null;
			//$dap_dbh = null;				
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			$stmt = null;
			//$dap_dbh = null;			
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}	
	/*
		move all users from srcProduct to targetProduct. Assume the srce and target products given are valid.
	*/
	public static function moveUsersProducts($srcProduct, $targetProduct)  {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$sql = "update dap_users_products_jn set
						product_id =:target_product_id
					where
						product_id =:source_product_id
					";

			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':source_product_id', $srcProduct, PDO::PARAM_INT);
			$stmt->bindParam(':target_product_id', $targetProduct, PDO::PARAM_INT);
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
	
	/*
		Copy all users from one product to other product keeping all other information same as the original product. 
		SQL : insert into  `dap_users_products_jn` (user_id, product_id, access_start_date, access_end_date, transaction_id, status)
		select user_id, 2, access_start_date, access_end_date, transaction_id, status
		from `dap_users_products_jn`
		where product_id = 1
	*/	
	public static function copyUsersProducts($srcProduct, $targetProduct)  {	
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$sql = "insert into  dap_users_products_jn
						(user_id, product_id, access_start_date, access_end_date, transaction_id, status)
					select 
						user_id, :target_product_id, access_start_date, access_end_date, transaction_id, status
					from dap_users_products_jn
					where product_id =:source_product_id
					";

			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':source_product_id', $srcProduct, PDO::PARAM_INT);
			$stmt->bindParam(':target_product_id', $targetProduct, PDO::PARAM_INT);
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
	
	/*
		THIS MIGHT BE DEPRECATED - CHECK FOLLOWING FUNCTION RIGHT BELOW THIS
		addNewUserToProduct(...)
		
		Add user to product, but
		a) Create user if not exists
		b) Assume product id exists
		
		All parameters are mandatory.
	*/
	
	public static function addUserToProduct($email, $firstname, $lastname="", $productid, $ispaid="n", $activeStatus="U", $coupon_id="", $accessStartDate="", $accessEndDate="") {
		$trans_id = -1;
		$uid = 0;
		
		logToFile("Dap_UsersProducts.addUserToProduct ispaid = " . $ispaid);
		if(strtolower($ispaid) == "y") {
			//admin added paid product
			logToFile("Dap_UsersProducts.addUserToProduct ispaid=" . $ispaid);
			$trans_id = -3;
		}
		
		//check to see if product exists
		$product = Dap_Product::loadProduct($productid);
		if( !isset($product) || ($product == NULL) ) return;
		//$doesProductExist = Dap_Product::isExists($productid);
		//if($doesProductExist === FALSE) return;
		
		$user = Dap_User::loadUserByEmail($email);
		if( !isset($user) || ($user == NULL) ) {
			$user = new Dap_User();
			$user->setEmail($email);
			$user->setFirst_name($firstname);
			$user->setLast_name($lastname);
			
			//Figure out what should be user's status
			if( ($product->getDouble_optin_subject() == "") && ($product->getDouble_optin_body() == "") ) {
				//This is single optin, so take what caller sent as $activeStatus
				$userStatus = (strtolower($activeStatus) == "u") ? "U" : "A";
			} else {
				//Double optin. So ignore what caller sent in as $activeStatus and set user status to "U"
				$userStatus = "U";
			}
			$user->setStatus($userStatus);
			
			$uid = $user->create();
			//$user = Dap_User::loadUserById($uid);
		} else {
			$uid = $user->getId();
		}
		
		
		//$uid = $user->getId();
		Dap_UsersProducts::addUsersProducts($uid, $productid, $trans_id, $accessStartDate, $coupon_id, $accessEndDate);
		logToFile("Dap_UsersProducts.addUserToProduct all done");
		return $uid;
	}
	
	public static function addNewUserToProduct($email, $firstname, $lastname="", $username="", $productid, $ispaid="n", $activeStatus="U", $coupon_id="", $password="") {
		$trans_id = -1;
		$uid = 0;
		
		if(strtolower($ispaid) == "y") {
			//admin added paid product
			$trans_id = -3;
		}
		
		logToFile("Dap_UsersProducts.addNewUserToProduct Enter:  productId=". $productid);
		
		//check to see if product exists
		$product = Dap_Product::loadProduct($productid);
		if( !isset($product) || ($product == NULL) ) return;
		
		$user = Dap_User::loadUserByEmail($email);
		if( !isset($user) || ($user == NULL) ) {
			$user = new Dap_User();
			$user->setEmail($email);
			$user->setFirst_name($firstname);
			$user->setLast_name($lastname);
			if( isset($password) && ($password != "") ) { //if specific password provided, then take that
				$user->setPassword($password);
			}

			if ($username != "")
				$user->setUser_name($username);
			else {
				//$user->setUser_name(NULL);
				$uname=generateUsername("Dap_UsersProducts.class.php.addNewUserToProduct()",$email,$firstname,$lastname);
				if($uname=="") {
					$uname=NULL;
					logToFile("Dap_UsersProducts.class.php: addNewUserToProduct(): username set to NULL",LOG_INFO_DAP);
				}
				else 
					logToFile("Dap_UsersProducts.class.php: addNewUserToProduct(): username set to ".$uname,LOG_INFO_DAP);
					
				$user->setUser_name($uname);
				
			}
			//Figure out what should be user's status
			if( ($product->getDouble_optin_subject() == "") && ($product->getDouble_optin_body() == "") ) {
				//This is single optin, so take what caller sent as $activeStatus
				$userStatus = (strtolower($activeStatus) == "u") ? "U" : "A";
			} else {
				//Double optin. So ignore what caller sent in as $activeStatus and set user status to "U"
				$userStatus = "U";
			}
			$user->setStatus($userStatus);
			
			logToFile("Dap_UsersProducts.addNewUserToProduct create new user:  username=". $username);
			
			$uid = $user->create();
			//$user = Dap_User::loadUserById($uid);
		} else {
			$uid = $user->getId();
		}
		
		
		//$uid = $user->getId();
		if ( isset($uid) && !is_null($uid) && ($uid != 0) ) {
			Dap_UsersProducts::addUsersProducts($uid, $productid, $trans_id, '', $coupon_id);
			logToFile("Dap_UsersProducts.addNewUserToProduct all done");
		}
		return $uid;
	}
	
	
	public static function markUserProductAsSomething($userId, $productId, $transactionId) {
		//transactionId values: -1: Direct Signup, etc...
		$userProduct = Dap_UsersProducts::load($userId, $productId);
		if($userProduct == NULL) {
			//logToFile("No Such UsersProducts relationship exists..".$userId.":".$productId);
			throw new Exception("This product id:".$productId." does not belong to user id:".$userId);
		}
		$userProduct->setTransaction_id($transactionId);
		$userProduct->update();
	}
	
	/*
	This function is single entry point to add user-product relationship. 
	NOTES:
	1. This function does not care if product is active or not.
	2. It does not add if product doesnt exist.
	3. It does not add if user doesnt exist.
	
	insert into dap_users_products_jn
	
	Parameters: Existing User Id, Existing Product Id, Transaction Id (optional,not validated), Access Start Date (optional)
	
	Transaction Id:
	Payment - valid transaction
	Direct Signup - 
	Admin Added - 
	TODO: Send notification to admin if the product does not exist. 
	*/
	
	public static function addUsersProducts($userId, $productId, $transId='-1', $accessStartDate='', $coupon_id="", $accessEndDate='',$addCredits="") {
		//check if its valid user id (doesnt matter if active or inactive
		//$user = Dap_User::loadUserById($userId);
		//if(!isset($user)) return;
	
		//logToFile("addUserProducts: active user: $userId");
		//logToFile("accessEndDate: $accessEndDate");
		//load the product and see if it exists
		$product = Dap_Product::loadProduct($productId);		
			
		//check if product exists
		if(!isset($product)) return;
		$recurring="N";
		//logToFile("(Dap_UsersProducts.addUsersProducts()) we have active product: $productId");
		//by the time we are here, we should be ready with valid uesrid and productid.
		$userProduct = Dap_UsersProducts::load($userId, $productId);
		if($userProduct != NULL) {
			$recurring="Y";
			logToFile("(Dap_UsersProducts.addUsersProducts()) active usersproducts name: ".$product->getName());
			logToFile("(Dap_UsersProducts.addUsersProducts()) active usersproducts isrecurring: ".$product->getIs_recurring());
			//we have existing users-products relationship, so we need to modify this.
			$userProduct->setTransaction_id($transId);
			$userProduct->setStatus("A");		
			
			logToFile("(Dap_UsersProducts.addUsersProducts()) couponId=".$coupon_id);
			$userProduct->setCoupon_id($coupon_id);
			
			if($accessStartDate != "") {
				$userProduct->setAccess_start_date($accessStartDate);
			}
			if($accessEndDate != "") {
				$userProduct->setAccess_end_date($accessEndDate);
			}
			else {
			  //lets check if this is recurring
			  if(strtolower($product->getIs_recurring()) == "y") {
				  //this is recurring so lets adjust end date
				  //TODO: calculate what recurring cycle we are in.
				  //get current active days
				  logToFile("(Dap_UsersProducts.addUsersProducts()) active Days: ".$active_days);
				  $active_days = $userProduct->getActiveDays();
				  logToFile("(Dap_UsersProducts.addUsersProducts()) active Days: ".$active_days);
				  //Took the negative -1 
				  $extend_days = $product->deriveRecurringDaysFromTotal($active_days) ; //$product->getRecurring_cycle_1() - 1;
				  logToFile("(Dap_UsersProducts.addUsersProducts()) recurring payment: extend_days: ".$extend_days);
				  $extend_days =  $extend_days + 1; //$product->getRecurring_cycle_1() - 1;
				  $userProduct->extendEndDays($extend_days);		
			  } else {
				  $extend_days = $product->getRecurring_cycle_1();
				  if( is_null($extend_days) || ($extend_days == 0) || ($extend_days == "") || ($extend_days == "9999") ) {
					  $userProduct->deriveAccessEndDate(-1);
				  } else {
					  //logToFile("New extend_days in addUserToProduct: $extend_days"); 
					  $userProduct->extendEndDays($extend_days);
					  //$userProduct->setAccess_end_date("9999-12-31");
				  }
			  }
			}
			
			logToFile("(Dap_UsersProducts.addUsersProducts()) recurring payment: before update access start: ".$userProduct->getAccess_start_date());
			logToFile("(Dap_UsersProducts.addUsersProducts()) recurring payment: before update access end: ".$userProduct->getAccess_end_date());
			
			logToFile("(Dap_UsersProducts.addUsersProducts()) recurring payment: extend_days: ".$extend_days);
			$userProduct->update();
			//$userProduct->adjustUserResourceClickCounts(true);
		} else {
			logToFile( "Dap_UsersProducts.class.php: addProductToUser()");
			if($accessStartDate == '') {
				$accessStartDate = date("Y-m-d");
			}
			Dap_UsersProducts::addProductToUser($userId, $product, $productId, $transId, $accessStartDate, $coupon_id, $accessEndDate);
			
			global $includeList;
			global $classname;
			
			$includeList = array();
			$classname = array();
			
			registeredPlugins($includeList, $email, "Add", $product);
			
			foreach ($includeList as $key => $value) {
				
				logToFile( "Dap_UsersProducts.class.php: addUsersProducts(): INCLUDELIST=" . $key . ", Value=" . $value );
				
				if (stristr($value, "vbulletin")) {
					//nothing to do yet.. wait till all chaining rules are done for final list of valid products for user		
				}
				else {
					Dap_UsersProducts::pluginAdd($userId, $productId, $value, $product);
				}
				
			} // for include_list
			logToFile("Dap_UsersProducts.class.php: addUsersProducts(): EXITING", LOG_INFO_DAP);
			
		}// else
		
		$sourceAction="AT";
		$productChainingList = Dap_ProductChaining::loadChainedProductsByProductId($productId, $sourceAction);
		
		foreach ($productChainingList as $chainedProductArray) { 
			logToFile("Dap_UsersProducts.addUsersProducts(): source_operation=" . $chainedProductArray["source_operation"],LOG_INFO_DAP);
			logToFile("source_product_id=" . $chainedProductArray["source_product_id"],LOG_INFO_DAP);
			logToFile("target_operation=" . $chainedProductArray["target_operation"],LOG_INFO_DAP);
			logToFile("target_product_id=" . $chainedProductArray["target_product_id"],LOG_INFO_DAP);
			logToFile("add_days=" . $chainedProductArray["add_days"],LOG_INFO_DAP);
			
			$chainedProduct = $chainedProductArray["target_product_id"];
						
			$product = Dap_Product::loadProduct($chainedProduct);		
			//check if product exists
			if(!isset($product)) {
				logToFile("(Dap_UsersProducts.addUsersProducts()) chained productId=" . $chainedProduct . " does not exist");
				continue;
			}
			
			$targetAction =  $chainedProductArray["target_operation"];
			$transId =  $chainedProductArray["transaction_id"];
			$addDays =  $chainedProductArray["add_days"];
			
			$dapuser = Dap_User::loadUserById($userId);
			$hasAccess = $dapuser->hasEverHadAccessTo($chainedProduct);
		
			if ($targetAction == "A") {
				
				if ($hasAccess) {
					$userProduct = Dap_UsersProducts::load($userId, $chainedProduct);
					if($userProduct != NULL) {
						logToFile("(Dap_UsersProducts.addUsersProducts()) active chained usersproducts name: ".$product->getName());
						logToFile("(Dap_UsersProducts.addUsersProducts()) active chained usersproducts isrecurring: ".$product->getIs_recurring());
						//we have existing users-products relationship, so we need to modify this.
						$userProduct->setTransaction_id($transId);
						$userProduct->setStatus("A");		
						
						//lets check if this is recurring
						if(strtolower($product->getIs_recurring()) == "y") {
							//this is recurring so lets adjust end date
							//TODO: calculate what recurring cycle we are in.
							//get current active days
							logToFile("(Dap_UsersProducts.addUsersProducts()) active Days: ".$active_days);
							$active_days = $userProduct->getActiveDays();
							logToFile("(Dap_UsersProducts.addUsersProducts()) active Days: ".$active_days);
							//Took the negative -1 
							$extend_days = $product->deriveRecurringDaysFromTotal($active_days) ; //$product->getRecurring_cycle_1() - 1;
							$extend_days= $extend_days+1;
							$userProduct->extendEndDays($extend_days);		
						} else {
							$extend_days = $product->getRecurring_cycle_1();
							if( is_null($extend_days) || ($extend_days == 0) || ($extend_days == "") || ($extend_days == "9999") ) {
								$userProduct->deriveAccessEndDate(-1);
							} else {
								//logToFile("New extend_days in addUserToProduct: $extend_days"); 
								$userProduct->extendEndDays($extend_days);
								//$userProduct->setAccess_end_date("9999-12-31");
							}
						}
					
						$userProduct->update();
						
						//$userProduct->adjustUserResourceClickCounts(true);
					}
				}
				else {
					
					logToFile("(Dap_UsersProducts.addUsersProducts()) current access start=" . $accessStartDate);
					
					$date = new DateTime();
					$date->modify("+".$addDays." day");
					$accessStartDate = $date->format("Y-m-d");	
					logToFile("(Dap_UsersProducts.addUsersProducts()) updated access start=" . $accessStartDate);
					
					Dap_UsersProducts::addProductToUser($userId, $product, $chainedProduct, $transId, $accessStartDate);
					
					// call 3rd party plugins that's configued under every individual chained product being added to main product.
					global $includeList;
					global $classname;
					$includeList = array();
					$classname = array();
					registeredPlugins($includeList, $email, "Add", $product);
					foreach ($includeList as $key => $value) {
						logToFile( "Dap_UsersProducts.class.php: addUsersProducts(): INCLUDELIST=" . $key . ", Value=" . $value );
						if (strstr($value, "vbulletin") != FALSE) { }
						else { 
  						  logToFile( "Dap_UsersProducts.class.php: call pluginAdd()");
						  Dap_UsersProducts::pluginAdd($userId, $chainedProduct, $value, $product);
						}
					}
					
					logToFile("(Dap_UsersProducts.addUsersProducts()) adding chained productId=" . $chainedProduct);
				}
			}
			else if ($targetAction == "R") {
			  $complete="Y";	
			  
			  if ($hasAccess) {
				  
				  if($addDays == -1) {
					  logToFile("(Dap_UsersProducts.addUsersProducts()) updated upgraded products access start to match the access start of product being removed, addDays=" . $addDays);
					  $userProduct = Dap_UsersProducts::load($userId, $chainedProduct);
					  $accessStartDate = 	$userProduct->getAccess_start_date();
					  
					  $userProduct = Dap_UsersProducts::load($userId, $productId);
					  if($userProduct != NULL) {
						  $userProduct->setAccess_start_date($accessStartDate);
						  $userProduct->update();
						  logToFile("(Dap_UsersProducts.addUsersProducts()) updated upgraded products access start to match the access start of product being removed, productId=" . $productId);
					  }
				  }
				  Dap_UsersProducts::removeProductFromUser ($userId, $chainedProduct, $transId, $complete, $dap_dbh);
			      //  call 3rd party plugins that's configued under every individual chained product being added to main product.
				  global $includeList;
				  global $classname;
				  $includeList = array();
				  $classname = array();
				  registeredPlugins($includeList, $email, "Remove", $product);
				  foreach ($includeList as $key => $value) {
					  logToFile( "Dap_UsersProducts.class.php: addUsersProducts(): INCLUDELIST=" . $key . ", Value=" . $value );
					  if (strstr($value, "vbulletin") != FALSE) { }
					  else { 
						Dap_UsersProducts::pluginRemove($userId, $chainedProduct, $value, $product, $productId);
					  }
				  }
				  logToFile("(Dap_UsersProducts.addUsersProducts()) removing chained productId=" . $chainedProduct . " from the main product=" . $productId);
			  }
			}
			
		}
			
		global $includeList;
		global $classname;
		
		$includeList = array();
		$classname = array();
		
		logToFile( "Dap_UsersProducts.class.php: addUsersProducts(): READY FOR VB, productName=" . $product->getName());
		
		registeredPlugins($includeList, $email, "Add", $product);
		foreach ($includeList as $key => $value) {
			
			logToFile( "Dap_UsersProducts.class.php: addUsersProducts(): INCLUDELIST=" . $key . ", Value=" . $value );
			
			if (stristr($value, "vbulletin")) {
				logToFile( "Dap_UsersProducts.class.php: addUsersProducts(): VALUE is =" . $value );
				if (AUTO_CREATE_VB_ACCOUNT_UPON_DAP_REG == "Y") {
					
					logToFile( "Dap_UsersProducts.class.php: addUsersProducts(): Key=" . $key . ", Value=" . $value );
					
					include_once ($value);
					$forum = new Dap_Vbulletin();
					$errmsg = $forum->syncUser($userId, $productId);
					
					if ($errmsg != NULL) {
						$str = "Dap_UsersProducts.class.php: addUserToProduct(): Forum Reg Failed. " . $errmsg; 
						logToFile($str,LOG_INFO_DAP);
						sendAdminEmail("Dap_UsersProducts.class.php: addUsersProducts(): could not register to the vB forum", $str);
					}
					else {
						$dapuser = Dap_User::loadUserById($userId);
						$username = trim($dapuser->getUser_name());
						
						
						//Update username in dap_user if the username was registered to vB successfully and it's not already populated in DAP
						if ((!isset($username)) || ($username=="") || (strcmp($username,"") == 0)) {
							logToFile("User: " . $username . " in DAP",LOG_INFO_DAP);
							$user = new Dap_User();
							$user->setId($userId);
							logToFile("Userid set",LOG_INFO_DAP);
							//$user->setUser_name($dapuser->getFirst_name().$dapuser->getLast_name());
							//logToFile("username set",LOG_INFO_DAP);
							$uname=generateUsername("Dap_UsersProducts.class.php.addUsersProducts()",$dapuser->getEmail(),$dapuser->getFirst_name(),$dapuser->getLast_name());
							$user->setUser_name($uname);
							logToFile("Dap_UsersProducts.class.php: addUsersProducts(): username set to ".$uname,LOG_INFO_DAP);
							$user->setFirst_name($dapuser->getFirst_name());
							$user->setLast_name($dapuser->getLast_name());
							logToFile("firstname and lastname set",LOG_INFO_DAP);
							$user->setEmail($dapuser->getEmail());
							$user->setPassword($dapuser->getPassword());
							$user->setAddress1($dapuser->getAddress1());
							$user->setAddress2($dapuser->getAddress2());
							$user->setCity($dapuser->getCity());
							$user->setState($dapuser->getState());
							$user->setZip($dapuser->getZip());
							$user->setCountry($dapuser->getCountry());
							$user->setPhone($dapuser->getPhone());
							$user->setFax($dapuser->getFax());
							$user->setCompany($dapuser->getCompany());
							$user->setTitle($dapuser->getTitle());
							$user->setStatus($dapuser->getStatus());
							$user->setAccount_type($dapuser->getAccount_type());
							logToFile("account set",LOG_INFO_DAP);
							$user->setPaypal_email( $dapuser->getPaypal_email() );	
							//$user->setCredits_earned( $dapuser->getCredits_earned() );	
							$user->setCredits_available( $dapuser->getCredits_available() );	
							
							$user->setPaypal_email( $dapuser->getPaypal_email() );	
							
							logToFile("Ready to update user",LOG_INFO_DAP);
							try {
								$user->update();
								$response = "SUCCESS! User has been successfully updated.";
								logToFile("User: " . $username . " successfully added to DAP",LOG_INFO_DAP);
							} catch (PDOException $e) {
								$response = ERROR_GENERAL;
								logToFile("User: " . $username . " could not be added:  Exception: " . $response,LOG_INFO_DAP);
								sendAdminEmail("Dap_UsersProducts.addUsersProducts(): could not add username=" . $username, $response);
							} catch (Exception $e) {
								$response = ERROR_GENERAL;
								logToFile("User: " . $username . " could not be added:  Exception: " . $response,LOG_INFO_DAP);
								sendAdminEmail("Dap_UsersProducts.addUsersProducts(): could not add username=" . $username, $response);
							}
						}
					}
				} //End if AUTO_CREATE_VB_ACCOUNT_UPON_DAP_REG
			}
		} //for
		
		
		logToFile("Dap_UsersProducts.addUsersProducts(): load before exit: userId=".$userId . ", productId=" . $productId,LOG_DEBUG_DAP); 
		
		$userProduct = Dap_UsersProducts::load($userId, $productId);
		$product = Dap_Product::loadProduct($productId);
		
		if(isset($userProduct)) {
		  logToFile("Dap_UsersProducts.addUsersProducts(): USERPROUCTSTATUS=" . $userProduct->getStatus(),LOG_DEBUG_DAP); 
		}
		
		//SSS
		if ( isset($userProduct) && ($userProduct->getStatus() == "A") && ($product->getCredits() > 0) && (($product->getIs_master() == "Y") || (($product->getSelf_service_allowed() == "Y") && ($product->getIs_master() == "N"))) && ($addCredits != "N")) {
			
		  logToFile("Dap_UsersProducts.addUsersProducts(): SSS: recurring=".$recurring,LOG_DEBUG_DAP); 
		  $productId=$product->getId();
		  $isSSSMaster=$product->getIs_master();
		  
		  if( strtolower($product->getIs_recurring()) != "y") {
			 if($product->getAllow_free_signup() != "Y")
			   $credits=$product->getCredits();
			 else {
				//free product, only give credits if not double opt-in, if double-optin, credit shd be at activation only (activate())
				if( ($product->getDouble_optin_subject() == "") && ($product->getDouble_optin_body() == "") ) {
					if ($recurring == "N") {
						logToFile("Dap_UsersProducts.addUsersProducts(): SSS: recurring=".$recurring . ", doubltoptin=N, free signup=Y, addign firsttime allow",LOG_DEBUG_DAP); 
						$credits=$product->getCredits();
					}
					else {
						logToFile("Dap_UsersProducts.addUsersProducts(): SSS: recurring=".$recurring . ", doubltoptin=Y, free signup=Y, addign not the firsttime, dont allow",LOG_DEBUG_DAP); 
					}
				}
				else {
					logToFile("Dap_UsersProducts.addUsersProducts(): SSS: DOUBLE OPTIN, free prodcut: credits assigned during activation",LOG_DEBUG_DAP); 
				}
			 }
			 	
		  }
		  else if ($recurring == "N") { //first time adding user to recurring product 
			$credits=$product->getCredits();
		  }
		  else { //recurring payment, user already exists
			if( strtolower($product->getIs_recurring()) == "y") {
			  if($product->getAllow_free_signup() != "Y")	{
			 	 $credits=$product->getRecurringCredits();
			  }
			  else {
				  logToFile("Dap_UsersProducts.addUsersProducts(): SSS: recurring product, free product: not adding for first time",LOG_DEBUG_DAP); 
			  }
			}
			else {
			  logToFile("Dap_UsersProducts.addUsersProducts(): SSS: it's a recurring payment for a non-recurring product (free product), no credits assigned. Looks like same user is trying to re-sign for the free product to earn credits. To prevent free product/signup abuse, no credits assigned",LOG_DEBUG_DAP); 
			}
		  }
		  if(($credits > 0) || (($product->getSelf_service_allowed() == "Y") && ($product->getIs_master() == "N"))) {
			if($product->getIs_master() == "Y") {
				
			  if($transId>0) {
				$accesshow="Master Purchased Via Payment";
			  	Dap_UserCredits::addCredits($userId, $productId, $transId, $credits, 0, $accesshow);
			  }
			  else {
				$accesshow="Admin Added-Master";
				logToFile("Dap_UsersProducts.addUsersProducts(): SSS: adding child, credits added to total",LOG_DEBUG_DAP); 
			  	Dap_UserCredits::addCredits($userId, $productId, $transId, $credits, 0, $accesshow);
			  }
			  
			  
			} else {
			  logToFile("Dap_UsersProducts.addUsersProducts(): SSS: adding child, credits added to total, transId=".$transId,LOG_DEBUG_DAP); 
			  
			  if(($product->getSelf_service_allowed() == "Y") && ($product->getIs_master() == "N")) {
			  	$credits=0;
			  	logToFile("Dap_UsersProducts.addUsersProducts():set SSS:credits=".$credits,LOG_DEBUG_DAP); 
			  }
			  
			  if($transId>0) {
			  	Dap_UserCredits::redeemCreditsAtProductLevel($userId, $productId, $credits, "Child Purchased Via Payment");
				$accesshow="Child Purchased Via Payment";
			  }
			  else {
				Dap_UserCredits::redeemCreditsAtProductLevel($userId, $productId, $credits, "Admin Added-Manual");
				$accesshow="Admin Added-Child";
			  }
			  
			  $UserResources = Dap_FileResource::loadFileResourcesSSS($userId, $productId);
			  foreach( $UserResources as $resource ) {
				 logToFile("Dap_UsersProducts.addUsersProducts(): SSS: purchased full product,add individual resources in usercredits: productId: " . $productId, LOG_DEBUG_DAP);
				 logToFile("Dap_UsersProducts.addUsersProducts(): SSS: purchased full product,add individual resources in usercredits: resourceId: " .$resource['id'], LOG_DEBUG_DAP);
				 logToFile("Dap_UsersProducts.addUsersProducts(): SSS: purchased full product,add individual resources in usercredits: Credits: " . $resource['credits_assigned'], LOG_DEBUG_DAP);
				 $resId = $resource["id"];
				 if(($product->getSelf_service_allowed() == "Y") && ($product->getIs_master() == "N")) {
				  	$resource['credits_assigned']=0;
					logToFile("Dap_UsersProducts.addUsersProducts():set SSS:resource credits=".$resource['credits_assigned'],LOG_DEBUG_DAP); 
				 }
				 Dap_UserCredits::redeemCredits($userId, $productId, $resId, $resource['credits_assigned'], $accesshow);
			   }

			}
			/*else {
			  logToFile("Dap_UsersProducts.addUsersProducts(): SSS: addign child, credits deducted from total",LOG_DEBUG_DAP); 
			  Dap_UserCredits::redeemCreditsAtProductLevel($userId, $productId, $credits, "Admin gave access in users->manage");
			  if ($credits != 0) {
				$user = Dap_User::loadUserById($userId); 
				logToFile("In addCredits UPDATE CREDITS=".$credits,LOG_DEBUG_DAP); 

				$user->updateCredits(0,-$credits);
			  }
			
			}*/
		  }
		}
		
		logToFile("(Dap_UsersProducts.addUsersProducts()) SUCCESS");
		
		return $userProduct;				
	}
	
	public static function addProductToUser ($userId, $product, $productId, $transId, $accessStartDate, $coupon_id="", $accessEndDate="") {
		$userProduct = new Dap_UsersProducts();
		$userProduct->setUser_id($userId);
		$userProduct->setProduct_id($productId);
		$userProduct->setTransaction_id($transId);
		$userProduct->setCoupon_id($coupon_id);
		
		logToFile("(Dap_UsersProducts.addProductToUser()) couponId=".$coupon_id);
		logToFile("(Dap_UsersProducts.addProductToUser()) accessEndDate=".$accessEndDate);
		//$userProduct->setStatus("A");
		$userProduct->setAccess_start_date($accessStartDate);
		
		// we dont have existing users-products relationship, so we need to insert.
		//insert this thing into dap_users_products_jn
		//lets check if this is recurring
		
		if ($accessEndDate != "") {
			logToFile("(Dap_UsersProducts.addProductToUser()) SET accessEndDate=".$accessEndDate);
			$userProduct->setAccess_end_date($accessEndDate);	
		}
		else {
			if(strtolower($product->getIs_recurring()) == "y") {
				//this is recurring so lets adjust end date
				//This is new user/product association, so take recurring cycle 1
				$active_days = 0;
				$extend_days = $product->deriveRecurringDaysFromTotal($active_days) - 1 ; //$product->getRecurring_cycle_1() - 1;
				$extend_days= $extend_days+1;
				$userProduct->deriveAccessEndDate($extend_days);
			} else {
				//this is not recurring, so just update enddate to recurring cycle 1 - previously was set to 9999-12-31
				//$userProduct->deriveAccessEndDate(-1);
				$extend_days = $product->getRecurring_cycle_1();
				if( is_null($extend_days) || ($extend_days == 0) || ($extend_days == "") || ($extend_days == "9999") ) {
					  $userProduct->deriveAccessEndDate(-1);
				} else {
					//logToFile("New extend_days in addUserToProduct: $extend_days"); 
					$userProduct->extendEndDays($extend_days);
					//$userProduct->setAccess_end_date("9999-12-31");
				}
			}
		}
		logToFile("(Dap_UsersProducts.userproduct create");
		$userProduct->create();
		
		$userProduct->adjustUserResourceClickCounts(true);
		//TODO send email communication to a) user, external systems on this successful create	
		sendUserProductNE($userProduct->getUser_id(), $product);
		
		return;
	}
	
	public static function pluginAdd($userId, $productId, $value, $product, $sourceProductId="") {
		logToFile("Dap_UsersProducts.class.php: in pluginAdd()", LOG_INFO_DAP);
		
		$params = explode(":",$value);
		logToFile("Dap_UsersProducts.class.php: pluginAdd(): params = " . $value, LOG_INFO_DAP);
		
		$className = $params[0];
		$lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
		$filename = $lldocroot . "/dap/plugins/" . $className . "/" . $className . ".class.php";
		//$filename = $lldocroot . "/dap/plugins/" . "plugins/vbulletin/Dap_VBulletin.class.php";
		logToFile("Dap_UsersProducts.class.php: pluginAdd(): filename = " . $filename, LOG_INFO_DAP);
		
		include_once ($filename);
						
		//$name = "Dap_VBulletin.class.php";
		try {
					
			$plugin=new $className();
			logToFile("Dap_UsersProducts.class.php:  pluginAdd(): NAME = " . $className, LOG_INFO_DAP);
			
		} catch (Exception $e) {
			$response = ERROR_GENERAL;
			logToFile("Exception=" . $e->getMessage(),LOG_FATAL_DAP);
			
		}
		//$classname = new $name();
		
		logToFile("Dap_UsersProducts.class.php:  pluginAdd(): calling register()", LOG_INFO_DAP);
		
			  
	  logToFile("(Dap_UsersProducts.pluginAdd(): calling register(): classname= $className", LOG_INFO_DAP);
	  logToFile("(Dap_UsersProducts.pluginAdd(): calling register(): sourceProductId = $sourceProductId", LOG_INFO_DAP);
	  logToFile("(Dap_UsersProducts.pluginAdd(): calling register(): productId = $productId", LOG_INFO_DAP);
	  if( ($className=="getresponse") && ($sourceProductId != "")) {
	    logToFile("(Dap_UsersProducts.pluginAdd(): calling register(): getresponse()", LOG_INFO_DAP);
		$errmsg = $plugin->register($userId, $productId, $value, $sourceProductId);
	  }
	  else
		$errmsg = $plugin->register($userId, $productId, $value);
		
		logToFile("Dap_UsersProducts.class.php:  pluginAdd(): called register()", LOG_INFO_DAP);
		
		if ($errmsg != 0) {		
			$str = "Dap_UsersProducts.class.php:  pluginAdd(): for plugin= " . $value . " failed with " . $errmsg; 
			logToFile($str,LOG_INFO_DAP);
			sendAdminEmail("Dap_UsersProducts.class.php: pluginAdd(): could not subscribe to the plugin=" . $filename, $str);
		}
		else {
			logToFile("Dap_UsersProducts.class.php:  pluginAdd(): registration completed successfully", LOG_INFO_DAP);
		}
	}
	
	//decrement and/or remove users products relationship.
	//TODO: should we return UsersProducts data back to the caller ?
	public static function removeUsersProducts($userId, $productId, $transId='-1', $complete = FALSE, $dap_dbh=NULL, $accessStartDate='') {
		$sourceAction="RF";
		$productChainingList = Dap_ProductChaining::loadChainedProductsByProductId($productId, $sourceAction);
		logToFile("(Dap_UsersProducts.removeUsersProducts()) complete=" . $complete);
		
		foreach ($productChainingList as $chainedProductArray) { 
			logToFile("(Dap_UsersProducts.removeUsersProducts()) - list chainedproduct");
			$chainedProduct = $chainedProductArray["target_product_id"];
			$product = Dap_Product::loadProduct($chainedProduct);		
			//check if product exists
			if(!isset($product)) {
				logToFile("(Dap_UsersProducts.removeUsersProducts()) chained productId=" . $chainedProduct . " does not exist");
				continue;
			}
			
			$targetAction =  $chainedProductArray["target_operation"];
			$transactionId =  $chainedProductArray["transaction_id"];
			$addDays = $chainedProductArray["add_days"];
			$setComplete = "Y";
			
			$dapuser = Dap_User::loadUserById($userId);
			$hasAccess = $dapuser->hasAccessTo($chainedProduct);
			
			if($accessStartDate == '') {
				$accessStartDate = date("Y-m-d");
			}
			$added=false;	
			if ($targetAction == "A") { 
				$added=true;
				if ($hasAccess) {
					$userProduct = Dap_UsersProducts::load($userId, $chainedProduct);
					if($userProduct != NULL) {
						logToFile("(Dap_UsersProducts.removeUsersProducts()) active chained usersproducts name: ".$product->getName());
						logToFile("(Dap_UsersProducts.removeUsersProducts()) active chained usersproducts isrecurring: ".$product->getIs_recurring());
						
						logToFile("(Dap_UsersProducts.removeUsersProducts()) transId: ".$transId);
						//we have existing users-products relationship, so we need to modify this.
						$userProduct->setTransaction_id($transId);
						$userProduct->setStatus("A");		
						
						//lets check if this is recurring
						if(strtolower($product->getIs_recurring()) == "y") {
							//this is recurring so lets adjust end date
							//TODO: calculate what recurring cycle we are in.
							//get current active days
							logToFile("(Dap_UsersProducts.removeUsersProducts()) active Days: ".$active_days);
							$active_days = $userProduct->getActiveDays();
							logToFile("(Dap_UsersProducts.removeUsersProducts()) active Days: ".$active_days);
							//Took the negative -1 
							$extend_days = $product->deriveRecurringDaysFromTotal($active_days) ; //$product->getRecurring_cycle_1() - 1;
							$userProduct->extendEndDays($extend_days);		
						} else {
							$extend_days = $product->getRecurring_cycle_1();
				  			if( is_null($extend_days) || ($extend_days == 0) || ($extend_days == "") || ($extend_days == "9999") ) {
								$userProduct->deriveAccessEndDate(-1);
							} else {
								//logToFile("New extend_days in addUserToProduct: $extend_days"); 
								$userProduct->extendEndDays($extend_days);
								//$userProduct->setAccess_end_date("9999-12-31");
							}
						}
						//logToFile("(Dap_UsersProducts.removeUsersProducts()) call update()");
						$userProduct->update();
						//logToFile("(Dap_UsersProducts.removeUsersProducts())  update()");
						//$userProduct->adjustUserResourceClickCounts(true);
					}
				}
				else {
					logToFile("(Dap_UsersProducts.removeUsersProducts()) current access start=" . $accessStartDate);
					
					$date = new DateTime($accessStartDate);
					$date->modify("+".$addDays." day");
					$accessStartDate = $date->format("Y-m-d");	
					logToFile("(Dap_UsersProducts.removeUsersProducts()) updated access start=" . $accessStartDate);
					
					Dap_UsersProducts::addProductToUser($userId, $product, $chainedProduct, $transactionId, $accessStartDate);
					
					$includeList = array();
					$product = Dap_Product::loadProduct($chainedProduct);		
					registeredPlugins($includeList, $email, "Add", $product);
					$dapuser = Dap_User::loadUserById($userId);
					foreach ($includeList as $key => $value) {
						if (strstr($value, "vbulletin") != FALSE) {
						}
						else {
							logToFile("(Dap_UsersProducts.removeUsersProducts()): call pluginAdd(): chainedProduct=" . $chainedProduct);
							Dap_UsersProducts::pluginAdd($userId, $productId, $value, $product, $chainedProduct);
						}
					} // foreach includeList
					$added=true;	
					logToFile("(Dap_UsersProducts.removeUsersProducts()) adding chained productId=" . $chainedProduct);
				}
			}
			else if ($targetAction == "R") {
				if ($hasAccess) {
					
					Dap_UsersProducts::removeProductFromUser ($userId, $chainedProduct, $transId, $setComplete, $dap_dbh);
					logToFile("(Dap_UsersProducts.removeUsersProducts()) removing chained productId=" . $chainedProduct . " from the main product=" . $productId);	
					$includeList = array();
					$product = Dap_Product::loadProduct($chainedProduct);		
					registeredPlugins($includeList, $email, "Remove", $product);
					$dapuser = Dap_User::loadUserById($userId);
					foreach ($includeList as $key => $value) {
						if (strstr($value, "vbulletin") != FALSE) {
						}
						else {
							logToFile("(Dap_UsersProducts.removeUsersProducts()) call pluginRemove1()");	
							Dap_UsersProducts::pluginRemove($userId, $productId, $value, $product);
						}
					} // foreach includeList	
				}
			}
			
		}
		
		$includeList = array();
		$product = Dap_Product::loadProduct($productId);		
		registeredPlugins($includeList, $email, "Remove", $product);
		$dapuser = Dap_User::loadUserById($userId);
		
		foreach ($includeList as $key => $value) {
			if (strstr($value, "vbulletin") != FALSE) {
			
				// call VB after the product is removed from the user			
			}
			else if (strstr($value, "getresponse") != FALSE) {
			
				// call VB after the product is removed from the user	
				if($added==false)  // if added is true, chaining would have resulted in user move to right list
					Dap_UsersProducts::pluginRemove($userId, $productId, $value, $product,$added);
			}
			else {
				
				logToFile("(Dap_UsersProducts.removeUsersProducts()) call pluginRemove2()");
				
				Dap_UsersProducts::pluginRemove($userId, $productId, $value, $product,$added);
			}
						
		} // foreach includeList
		
		logToFile("(Dap_UsersProducts.removeUsersProducts()) removing main productId=" . $productId);
		
		
		// START - SSS credits remove for master.. add for child
		$product = Dap_Product::loadProduct($productId);
		$userProduct = Dap_UsersProducts::load($userId, $productId);
		$recurring="N";
		if($userProduct != NULL) {
			$recurring="Y";
		}
		//SSS
		/*if (($product->getCredits() > 0) && ($product->getSelf_service_allowed() == "Y")) {
		  logToFile("Dap_UsersProducts.removeUsersProducts(): SSS: recurring=".$recurring,LOG_DEBUG_DAP); 
		  $productId=$product->getId();
		  $isSSSMaster=$product->getIs_master();
		  
		  if($isSSSMaster == "Y") {
			$credits = Dap_UserCredits::getTotalCreditsEarnedForProduct($userId,$productId);
			$credits=-($credits);
			logToFile("Dap_UsersProducts.removeUsersProducts(): SSS: adding child, credits added to total=".$credits,LOG_DEBUG_DAP); 
			Dap_UserCredits::addCredits($userId, $productId, -1, $credits, 0, "By Purchase");
		  }
		  else {
			$credits = Dap_UserCredits::getTotalCreditsUsedToRedeemProduct($userId,$productId);
			logToFile("Dap_UsersProducts.removeUsersProducts(): SSS: addign child, credits deducted from total=".$credits,LOG_DEBUG_DAP); 
			Dap_UserCredits::addCredits($userId, $productId, -1, 0, $credits, "Admin removed access, add back credits");
		  }
		  
		}*/
		// DONE: SSS credits remove for master.. add for child
		
		
		
		Dap_UsersProducts::removeProductFromUser ($userId, $productId, $transId, $complete, $dap_dbh);
		
		foreach ($includeList as $key => $value) {
			if (strstr($value, "vbulletin") != FALSE) {
				logToFile( "Dap_UsersProducts.class.php: addUsersProducts(): VALUE is =" . $value );
				if (AUTO_CREATE_VB_ACCOUNT_UPON_DAP_REG == "Y") {
					logToFile( "Dap_UsersProducts.class.php: addUsersProducts(): Key=" . $key . ", Value=" . $value );
					
					include_once ($value);
					$forum = new Dap_Vbulletin();
					$errmsg = $forum->syncUser($userId, $productId);
					
					if ($errmsg != NULL) {
						$str = "Dap_UsersProducts.class.php: addUserToProduct(): Forum Reg Failed. " . $errmsg; 
						logToFile($str,LOG_INFO_DAP);
						sendAdminEmail("Dap_UsersProducts.class.php: addUsersProducts(): could not register to the vB forum", $str);
					}
					else {
						$dapuser = Dap_User::loadUserById($userId);
						$username = trim($dapuser->getUser_name());
						
						//Update username in dap_user if the username was registered to vB successfully and it's not already populated in DAP
						if ((!isset($username)) || ($username=="") || (strcmp($username,"") == 0)) {
							logToFile("User: " . $username . " in DAP",LOG_INFO_DAP);
							$user = new Dap_User();
							$user->setId($userId);
							logToFile("Userid set",LOG_INFO_DAP);
							//$user->setUser_name($dapuser->getFirst_name().$dapuser->getLast_name());
							//logToFile("username set",LOG_INFO_DAP);
							$uname=generateUsername("Dap_UsersProducts.class.php.addUsersProducts()",$dapuser->getEmail(),$dapuser->getFirst_name(),$dapuser->getLast_name());
							$user->setUser_name($uname);
							logToFile("Dap_UsersProducts.class.php: addUsersProducts(): username set to ".$uname,LOG_INFO_DAP);
							$user->setFirst_name($dapuser->getFirst_name());
							$user->setLast_name($dapuser->getLast_name());
							logToFile("firstname and lastname set",LOG_INFO_DAP);
							$user->setEmail($dapuser->getEmail());
							$user->setPassword($dapuser->getPassword());
							$user->setAddress1($dapuser->getAddress1());
							$user->setAddress2($dapuser->getAddress2());
							$user->setCity($dapuser->getCity());
							$user->setState($dapuser->getState());
							$user->setZip($dapuser->getZip());
							$user->setCountry($dapuser->getCountry());
							$user->setPhone($dapuser->getPhone());
							$user->setFax($dapuser->getFax());
							$user->setCompany($dapuser->getCompany());
							$user->setTitle($dapuser->getTitle());
							$user->setStatus($dapuser->getStatus());
							$user->setAccount_type($dapuser->getAccount_type());
							logToFile("account set",LOG_INFO_DAP);
							$user->setPaypal_email( $dapuser->getPaypal_email() );	
							//$user->setCredits_earned( $dapuser->getCredits_earned() );	
							$user->setCredits_available( $dapuser->getCredits_available() );	
							
							$user->setPaypal_email( $dapuser->getPaypal_email() );	
							
							logToFile("Ready to update user",LOG_INFO_DAP);
							try {
								$user->update();
								$response = "SUCCESS! User has been successfully updated.";
								logToFile("User: " . $username . " successfully added to DAP",LOG_INFO_DAP);
							} catch (PDOException $e) {
								$response = ERROR_GENERAL;
								logToFile("User: " . $username . " could not be added:  Exception: " . $response,LOG_INFO_DAP);
								sendAdminEmail("Dap_UsersProducts.addUsersProducts(): could not add username=" . $username, $response);
							} catch (Exception $e) {
								$response = ERROR_GENERAL;
								logToFile("User: " . $username . " could not be added:  Exception: " . $response,LOG_INFO_DAP);
								sendAdminEmail("Dap_UsersProducts.addUsersProducts(): could not add username=" . $username, $response);
							}
						}
					}
				} //End if AUTO_CREATE_VB_ACCOUNT_UPON_DAP_REG
				
				
			}
						
		} // foreach includeList
		
		
		
		return;
	}	
	
	public static function pluginRemove($userId, $productId, $value, $product, $sourceProductId="",$added=false) {
	  $params = explode(":",$value);
	  logToFile("(Dap_UsersProducts.removeUsersProducts()): pluginRemove(): params = " . $value, LOG_INFO_DAP);
	  
	  $className = $params[0];
	  $lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
	  $filename = $lldocroot . "/dap/plugins/" . $className . "/" . $className . ".class.php";
	  //$filename = $lldocroot . "/dap/plugins/" . "plugins/vbulletin/Dap_VBulletin.class.php";
	  logToFile("(Dap_UsersProducts.removeUsersProducts()): pluginRemove(): filename = " . $filename, LOG_INFO_DAP);
	  
	  include_once ($filename);
					  
	  //$name = "Dap_VBulletin.class.php";
	  try {
				  
		  $plugin=new $className();
		  logToFile("(Dap_UsersProducts.removeUsersProducts()): pluginRemove(): NAME = " . $className, LOG_INFO_DAP);
		  
	  } catch (Exception $e) {
		  $response = ERROR_GENERAL;
		  logToFile("(Dap_UsersProducts.removeUsersProducts()): pluginRemove(): Exception=" . $e->getMessage(),LOG_FATAL_DAP);
		  
	  }
	  //$classname = new $name();
	  
	  logToFile("(Dap_UsersProducts.pluginRemove(): calling unregister(): classname= $className", LOG_INFO_DAP);
	  logToFile("(Dap_UsersProducts.pluginRemove(): calling unregister(): sourceProductId = $sourceProductId", LOG_INFO_DAP);
	  
	  if( ($className=="getresponse") && ($sourceProductId != "")) {
	    logToFile("(Dap_UsersProducts.pluginRemove(): calling unregister(): getresponse()", LOG_INFO_DAP);
		$errmsg = $plugin->unregister($userId, $productId, $value, $sourceProductId,$added);
	  }
	  else
		$errmsg = $plugin->unregister($userId, $productId, $value);
	  
	  logToFile("(Dap_UsersProducts.pluginRemove(): called unregister()", LOG_INFO_DAP);
	  
	  if ($errmsg != 0) {
		  $str = "(Dap_UsersProducts.pluginRemove(): for userID = " . $userId . ", plugin= " . $value . " failed with " . $errmsg; 
		  logToFile($str,LOG_INFO_DAP);
		  sendAdminEmail("(Dap_UsersProducts.pluginRemove(): could not subscribe to the plugin=" . $filename, $str);
	  }
	  else {
		  logToFile("(Dap_UsersProducts.pluginRemove(): userID = " . $userId . " unregistered successfully", LOG_INFO_DAP);
	  }
	  
	}
	
	public static function removeProductFromUser($userId, $productId, $transId, $complete, $dap_dbh) {

		//load the product and see if it exists
		$product = Dap_Product::loadProduct($productId);	
		logToFile("(Dap_UsersProducts.removeUserProducts()) active product: $productId");
		
		//by the time we are here, we should be ready with valid uesrid and productid.
		$userProduct = Dap_UsersProducts::load($userId, $productId); //loadUserProducts($userId, $productId);
		if($userProduct != NULL) {
			 logToFile("removeUserProducts: active usersproducts: ".$product->getName());
			//logToFile("removeUserProducts: active usersproducts: ".$product->getIs_recurring());
			//if product exists, and request is not for complete removal and product is recurring then do negation of extend days.
			if(isset($product) && !($complete) && (strtolower($product->getIs_recurring()) == "y")) {
				logToFile("Updating UsersProducts as the product is recurring..");
				//this is recurring so lets adjust end date
				//TODO initially we were doing 0 - recurring days + 1, but took +1 out on 7/26/
				//TODO we need to calculate what to negate - based on what recurring cycle we are on.
				$extend_days = 0 - $product->getRecurring_cycle_1();
				$userProduct->extendEndDays($extend_days);
				$userProduct->update($dap_dbh);
				$userProduct->adjustUserResourceClickCounts(false);
			} else {
				logToFile("Deleting UsersProducts.."); //.$userProduct->getId());
				//this is not recurring, so just update enddate to '12-31-9999				
				$userProduct->delete($dap_dbh);
				logToFile("No Such UsersProducts deleted");
				$userProduct->adjustUserResourceClickCounts(false);
			}			
		} else {
			logToFile("No Such UsersProducts relationship exists..".$userId.":".$productId);
			throw new Exception("This product id:".$productId." does not belong to user id:".$userId);
		}
		logToFile("removeProductFromUser: return");
	}	
	
	
	//clean up all Users Products relationships where acces start date is equal to or less than access end date. 
	function cleanUsersProducts($dap_dbh=NULL) {
		try {
			$dap_dbh = Dap_Connection::getConnection($dap_dbh);
		
			$sql = "DELETE 
					from 
					dap_users_products_jn 
					where 
					access_start_date > access_end_date
					";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();	
			//logToFile("in cleanUsersProducts - after execute",LOG_DEBUG_DAP);
			$stmt = null;
			//$dap_dbh = null;			
			return;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}	
	// check out the user-resouce relationship with max allowed resource click counts.
	//ASSM we assume product id and user id associated with this userproduct is valid and all checks such as valid product etc are already done.
	//ASSM we also assume that num clicks are present for file resources only in product resource join.

	function adjustUserResourceClickCounts($credit) {
		try {
			//logToFile("Dap_UsersProducts.adjustUserResourceClickCounts ..");
			$dap_dbh = Dap_Connection::getConnection();
		
			$sql = "select prj.resource_id, prj.num_clicks 
					from 
					dap_products_resources_jn prj
					where 
					prj.product_id = :product_id and
					prj.status = 'A' and
					(prj.num_clicks <> null or prj.num_clicks <> 0)
					
					";
			if($credit) {
				$update_sql = "insert into dap_users_resources_jn
						(user_id, resource_id, click_count)
					values
						(:user_id, :resource_id, :click_count)
					ON DUPLICATE KEY
					update click_count = click_count + :click_count
					";
			} else {
				$update_sql = "insert into dap_users_resources_jn
						(user_id, resource_id, click_count)
					values
						(:user_id, :resource_id, :click_count)
					ON DUPLICATE KEY
					update click_count = click_count - :click_count
					";
			}
			$stmt = $dap_dbh->prepare($sql);
			$update_stmt = $dap_dbh->prepare($update_sql);
			//
			$stmt->bindParam(':product_id', $this->product_id, PDO::PARAM_INT);
			//logToFile($sql,LOG_INFO_DAP);
			$stmt->execute();	
			//now we got resource url and num clicks allowed on that url as part of a product-resource association.
			//Lets add it to the user-resource table with insert update if exists. 	
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				//$url = $row['user_id'];
				$update_stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
				$update_stmt->bindParam(':resource_id', $row['resource_id'], PDO::PARAM_INT);
				$update_stmt->bindParam(':click_count', $row['num_clicks'], PDO::PARAM_INT);
				$update_stmt->execute();	
				
			}
			$stmt = null;
			$update_stmt = null;
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


	public static function storeGenerateCSVReportOnSyncJobToQueue($syncorder,$csvFileName) {
		try {
			$actionType = "REPORTSYNCISSUES";
			$payload = $csvFileName."||".$syncorder;
			$status = "NEW";
			
			$dap_dbh = Dap_Connection::getConnection();
			$key = mktime();
                        //Insert into dap_mass_actions
			
			logToFile("Dap_UsersProducts.class.php:reportSyncIssuesDAPWPAjax. payload - $payload");
						
			$sql = "insert into dap_mass_actions
									(actionType, actionKey, payload, status)
									values
									(:actionType, :key, :payload, 'NEW')";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':actionType', $actionType, PDO::PARAM_STR);
			$stmt->bindParam(':key', $key, PDO::PARAM_STR);
			$stmt->bindParam(':payload', $payload, PDO::PARAM_STR);
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
	
	public static function storeSyncJobToQueue($foldername,$csvFileName,$pickacat) {
		try {
			$actionType = "SYNCUSERSWPDAP";
			$payload = $csvFileName."||".$pickacat."||".$foldername;
			$status = "NEW";
			
			logToFile("in storeSyncJobToQueue - $foldername,$csvFileName,$pickacat",LOG_DEBUG_DAP);
			
			$dap_dbh = Dap_Connection::getConnection();
			$key = mktime();
                        //Insert into dap_mass_actions

			$sql = "insert into dap_mass_actions
									(actionType, actionKey, payload, status)
									values
									(:actionType, :key, :payload, 'NEW')";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':actionType', $actionType, PDO::PARAM_STR);
			$stmt->bindParam(':key', $key, PDO::PARAM_STR);
			$stmt->bindParam(':payload', $payload, PDO::PARAM_STR);
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
	
	public static function bulkAddCSVToProduct($productId,$csvFileName,$paid) {
		try {
			$actionType = "BULKADDCSVTOPRODUCT";
			$payload = $productId."||".$csvFileName."||".$paid;
			$status = "NEW";
			
			$dap_dbh = Dap_Connection::getConnection();
			$key = mktime();
                        //Insert into dap_mass_actions

                        $sql = "insert into dap_mass_actions
                                                (actionType, actionKey, payload, status)
                                                values
                                                (:actionType, :key, :payload, 'NEW')";
                        $stmt = $dap_dbh->prepare($sql);
                        $stmt->bindParam(':actionType', $actionType, PDO::PARAM_STR);
                        $stmt->bindParam(':key', $key, PDO::PARAM_STR);
                        $stmt->bindParam(':payload', $payload, PDO::PARAM_STR);
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

	public static function isProtectedResourceAvailableToUser1($uid, $pid, $url, $sss="N") {
		try {
			logToFile("Dap_UsersResources.getActiveResources() - Init...User Id:".$uid.", Product Id:".$pid);
			logToFile("Dap_UsersResources.getActiveResources() - Init...url:".$url);
			logToFile("Dap_UsersResources.getActiveResources() - SSS=" . $sss);
			
			
			$product = Dap_Product::loadProduct($pid);
			$isSSSMaster=$product->getIs_master();
			logToFile("userlinks.inc: isSSSMaster=". $isSSSMaster);
			
			if (($sss == "N") || ($isSSSMaster == "Y")){
				$sql = "select 
						upj.transaction_id as transid,
						TO_DAYS(now()) as today,
						TO_DAYS(upj.access_start_date) as access_start_days,
						TO_DAYS(upj.access_end_date) as access_end_days,
						prj.is_free as is_free,
						prj.start_day as start_day,
						prj.end_day as end_day,
						TO_DAYS(prj.start_date) as res_start_days,
						TO_DAYS(prj.end_date) as res_end_days,
						prj.num_clicks as num_clicks,
						prj.resource_id as resource_id,
						p.error_page_url as error_page_url,
						fr.url as url,
						fr.name as name
				from
					dap_products p,
					dap_products_resources_jn prj,
					dap_file_resources fr,
					dap_users u,
					dap_users_products_jn upj				
				where
					u.id =:uid and
					u.status = 'A' and 
					prj.resource_id = fr.id and 					
					prj.resource_type = 'F' and
					p.id = prj.product_id and
					p.status = 'A' and
					upj.user_id = u.id and
					upj.product_id = p.id and
					upj.status = 'A' and
					fr.url = :url
					";
			}
			else {
				
				   $hasAccess=Dap_UserCredits::hasAccessTo($uid, $pid); 
				   $sssHasAccess=true;
				   if($hasAccess) {
					   $sql = "select DISTINCT 
						upj.transaction_id as transid,
						TO_DAYS(now()) as today,
						TO_DAYS(upj.access_start_date) as access_start_days,
						TO_DAYS(upj.access_end_date) as access_end_days,
						prj.is_free as is_free,
						prj.start_day as start_day,
						prj.end_day as end_day,
						TO_DAYS(prj.start_date) as res_start_days,
						TO_DAYS(prj.end_date) as res_end_days,
						prj.num_clicks as num_clicks,
						prj.resource_id as resource_id,
						p.error_page_url as error_page_url,
						fr.url as url,
						fr.name as name
				from
					dap_products p,
					dap_products_resources_jn prj,
					dap_file_resources fr,
					dap_users u,
					dap_users_products_jn upj,
					dap_users_credits duc
				where
					u.id =:uid and
					u.status = 'A' and 
					prj.resource_id = fr.id and 					
					prj.resource_type = 'F' and
					p.id = prj.product_id and
					p.status = 'A' and
					upj.user_id = u.id and
					upj.product_id = p.id and
					upj.status = 'A' and
					duc.user_id = u.id and
					duc.product_id = p.id  and
					fr.url = :url
					";
				   }
				   else {
					   	$sql = "select 
						upj.transaction_id as transid,
						TO_DAYS(now()) as today,
						TO_DAYS(upj.access_start_date) as access_start_days,
						TO_DAYS(upj.access_end_date) as access_end_days,
						prj.is_free as is_free,
						prj.start_day as start_day,
						prj.end_day as end_day,
						TO_DAYS(prj.start_date) as res_start_days,
						TO_DAYS(prj.end_date) as res_end_days,
						prj.num_clicks as num_clicks,
						prj.resource_id as resource_id,
						p.error_page_url as error_page_url,
						fr.url as url,
						fr.name as name
				from
					dap_products p,
					dap_products_resources_jn prj,
					dap_file_resources fr,
					dap_users u,
					dap_users_products_jn upj,
					dap_users_credits duc
				where
					u.id =:uid and
					u.status = 'A' and 
					prj.resource_id = fr.id and 					
					prj.resource_type = 'F' and
					p.id = prj.product_id and
					p.status = 'A' and
					upj.user_id = u.id and
					upj.product_id = p.id and
					upj.status = 'A' and
					duc.user_id = u.id and
					duc.product_id = p.id and
					duc.resource_id = prj.resource_id and
					fr.url = :url
					";
				   }
			}
				//	now() between upj.access_start_date and upj.access_end_date	and
				//	(TO_DAYS(NOW()) - TO_DAYS(access_start_date)) between prj.start_day and prj.end_day
			
			//echo "sql: $sql<br>"; exit;
			$dap_dbh = Dap_Connection::getConnection();
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':uid', $uid, PDO::PARAM_INT);

			$stmt->bindParam(':url', $url, PDO::PARAM_STR);
			$stmt->execute();
			
			$access=false;
			$post_cancel_access = Dap_Config::get("POST_CANCEL_ACCESS");
			if(isset($post_cancel_access)) {
				$post_cancel_access = strtolower($post_cancel_access);
			}			
			//lets loop over the resource list
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$row["name"] = mb_convert_encoding($row["name"], "UTF-8", "auto");
				logToFile("Resource URL:".$row["url"]);
				
			  //lets present two modes of operation 
			  if($sssHasAccess){
				  logToFile("DAP_UsersProducts.class.php: getActiveResources(): SSS product, show content list");
			  }
			  else if($post_cancel_access == 'y') {
				  //we have dates on the resource
				  if($row["res_start_days"] <> 0 && $row["res_start_days"] <> "" &&
					  $row["res_end_days"] <> 0 && $row["res_end_days"] <> "" ) {
					  //set resource start days 
					  $resource_start_days = $row["res_start_days"];
					  $resource_end_days = $row["res_end_days"];
				  }
							  
				  //we have "days" on the resource
				  if($row["start_day"] <> 0 && $row["start_day"] <> "" &&
					  $row["end_day"] <> 0 && $row["end_day"] <> "" ) {
					  //set resource start days 
					  $resource_start_days = $row["access_start_days"] + $row["start_day"] - 1 ;
					  $resource_end_days = $row["access_start_days"] + $row["end_day"] - 1 ;
					  
					  //logToFile("We have 'days' on the resource"); 
					  //logToFile("resource_start_days: $resource_start_days"); 
					  //logToFile("resource_end_days: $resource_end_days"); 
					  //logToFile("User's row[access_start_days]: " . $row["access_start_days"]); 
					  
					  
					  /**
						  So if resource "days" are hard-coded, but resource end day is in the past,
						  then do not allow access. Will help offer expiring bonuses even though post-cancel-access is yes.
					  */
					  if($row["today"] > $resource_end_days ) { //Expiring Bonuses
						  //logToFile("Sorry: The 'end day' for this content is in the past...");
						  //logToFile("DAP001");
						  continue;
					  }					
				  }
  
				  //if resource starts in future, lets not grant access.
				  if($row["today"] < $resource_start_days ) {
					  //logToFile("Resource Start Date is in future...");
					  //logToFile("DAP001");
					  continue;
				  }					
  
				  
				  //
				  // If resource ends before upj start date - then no acesss. This could only happen 
				  //    when calendar dates are used at the resource level.
				  // If resource starts after upj end date - then no access. This could only happen
				  //    when calendar dates are used at the resource level.
				  //
				  //
				  if($resource_end_days < $row["access_start_days"] ||
					  $resource_start_days > $row["access_end_days"]) {
					  //logToFile("Product Start Date is in future...");
					  //logToFile("Resource Start Days:".$resource_start_days);
					  //logToFile("Resource End Days:".$resource_end_days);
					  //logToFile("Access Start Days:".$row["access_start_days"]);
					  //logToFile("Access End Days:".$row["access_end_days"]);
					  //logToFile("DAP005");
					  //logToFile($ERROR_CODES["DAP005"]);
					  continue;						
				  }				
			  
			  } else {				
				  //Product did not lauch yet.
				  if($row["today"] < $row["access_start_days"]) {
					  //logToFile("Product Start Date is in future...");
					  //logToFile("DAP001");
					  continue;
					  //return $resource;						
					  //$_SESSION['DAP_ERROR'] = $ERROR_CODES["DAP001"];
					  //$_SESSION['ERROR_URL'] = $row['error_page_url'];
					  //return FALSE;
				  }
				  //Product expired 
				  if($row["today"] > $row["access_end_days"]) {
					  //logToFile("Product End Date is in past...");
					  //logToFile("DAP002");
					  continue;
				  }
				  //product is available.
				  //check start date(days).
				  if($row["res_start_days"] <> 0 && $row["res_start_days"] <> "" &&
					  $row["res_end_days"] <> 0 && $row["res_end_days"] <> "" ) {
					  //resource is available in future.
					  if($row["today"] < $row["res_start_days"]) {
						  //logToFile("Resource Start Date  is in future...");
						  //logToFile("DAP003");
						  continue;
					  }
					  //resource  expired.
					  if($row["today"] > $row["res_end_days"]) {
						  //logToFile("Resource End Date  is in past...");
						  //logToFile("DAP004");
						  continue;
					  }
					  //logToFile("Start Days(Date) and End Days(Date) check passed...");
	  
				  } else {
					  //logToFile("Start Days(Date) and End Days(Date) are empty or ZERO.. not checking...");
				  }
				  //check start day
				  $lag_days = $row["today"] - $row["access_start_days"] + 1;
				  //check resource start and end day only if they are both non zero. 
				  if($row["start_day"] <> 0 && $row["start_day"] <> "" &&
					  $row["end_day"] <> 0 && $row["end_day"] <> "" ) {
  
					  //resource is available in future.
					  if($lag_days < $row["start_day"]) {
						  //logToFile("Lag Days:".$lag_days);
						  //logToFile("Start Day:".$row["start_day"]);
						  //logToFile("Resource Start Day  is in future...");
						  //logToFile("DAP003");
						  continue;
						  //return $resource;
						  //$_SESSION['DAP_ERROR'] = $ERROR_CODES["DAP003"];
						  //$_SESSION['DAP_ERROR_URL'] = $row['error_page_url'];
						  //return FALSE;
					  }
					  //resource availability expired.
					  if($lag_days > $row["end_day"]) {
						  //logToFile("Lag Days:".$lag_days);
						  //logToFile("End Days:".$row["end_day"]);
						  //logToFile("Resource Start Day  is in past...");
						  //logToFile("DAP004");
						  continue;
						  //return $resource;
						  //$_SESSION['DAP_ERROR'] = $ERROR_CODES["DAP004"];
						  //$_SESSION['DAP_ERROR_URL'] = $row['error_page_url'];
						  //return FALSE;
					  }
					  //logToFile("Start Day and End Day check passed...");
					  } else {
						  //logToFile("Start Day and End Day are empty or ZERO.. not checking...");
					  }
				  } //end of config allow post cancel access
				  if(!Dap_Resource::isCountAvailable($uid, $row['url'])) {
					  //logToFile("Click Count is Negative...");
					  continue;
				  }
				  if(!Dap_Resource::displayResource($row['url'])) {
					  //logToFile("This Resource is not displayable...");
					  continue;
				  }
				  //grant access - we should reach here ONLY IF THE PRODUCT RESOURCE RELATIONSHIP IS CLEAN AND ALLOWED.
				  //return $resource;
				  //$_SESSION['DAP_ERROR'] = $ERROR_CODES["DAP002"];
				  //logToFile("Granting Access To Resource URL:".$row["url"].", Resource ID:".$row["resource_id"]);
				  if($row['name'] == "") {
					  $name = $row['url'];
				  }
				  else {
					  $name = mb_convert_encoding($row["name"], "UTF-8", "auto");
					  //$name = "";
				  }
  
				  $name = stripslashes($name);
				  $access=true;
				  //logToFile("HTML: " . $html);
			  } //while
			
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			return $access;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			return $access;
		}		
		return $access;
	}

	public static function isProtectedResourceAvailableToUser($uid, $pid, $url, &$accesserrstr) {
		try {
			logToFile("Dap_UsersResources.isProtectedResourceAvailableToUser() - Init...User Id:".$uid.", Product Id:".$pid);
			logToFile("Dap_UsersResources.isProtectedResourceAvailableToUser() - Init...url:".$url);
			
			$sql = "select 
						upj.transaction_id as transid,
						upj.status as pstatus,
						TO_DAYS(now()) as today,
						TO_DAYS(upj.access_start_date) as access_start_days,
						TO_DAYS(upj.access_end_date) as access_end_days,
						prj.is_free as is_free,
						prj.start_day as start_day,
						prj.end_day as end_day,
						TO_DAYS(prj.start_date) as res_start_days,
						TO_DAYS(prj.end_date) as res_end_days,
						prj.num_clicks as num_clicks,
						prj.resource_id as resource_id,
						p.error_page_url as error_page_url,
						fr.url as url,
						fr.name as name
				from
					dap_products p,
					dap_products_resources_jn prj,
					dap_file_resources fr,
					dap_users u,
					dap_users_products_jn upj				
				where
					u.id =:uid and
					u.status = 'A' and 
					prj.resource_id = fr.id and 					
					prj.resource_type = 'F' and
					p.id = prj.product_id and
					p.status = 'A' and
					upj.user_id = u.id and
					upj.product_id = p.id and
					fr.url = :url
					";
		
			
		
				//	now() between upj.access_start_date and upj.access_end_date	and
				//	(TO_DAYS(NOW()) - TO_DAYS(access_start_date)) between prj.start_day and prj.end_day
			
			//echo "sql: $sql<br>"; exit;
			
			$dap_dbh = Dap_Connection::getConnection();
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':uid', $uid, PDO::PARAM_INT);

			$stmt->bindParam(':url', $url, PDO::PARAM_STR);
			$stmt->execute();
			
			$access=true;
			$post_cancel_access = Dap_Config::get("POST_CANCEL_ACCESS");
			if(isset($post_cancel_access)) {
				$post_cancel_access = strtolower($post_cancel_access);
			}	
			
			$sql1rows=false;
			$inactproduct="";
			$entered=false;
			//lets loop over the resource list
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				
				logToFile("Dap_UsersResources.isProtectedResourceAvailableToUser() - found rows");
			
				$entered=true;
				if($row["pstatus"]!="A") {
					$inactproduct="Y";
					$access=false;
					$errmsg="This user's status to the product is 'I' (INACTIVE) in DAP Users=>Manage page. The user cannot access any content under this product.";
					continue;
				}
				
				$inactproduct="N";
				
				$row["name"] = mb_convert_encoding($row["name"], "UTF-8", "auto");
				logToFile("Resource URL:".$row["url"]);
				
			  //lets present two modes of operation 
			  if($sssHasAccess){
				  logToFile("DAP_UsersProducts.class.php: getActiveResources(): SSS product, show content list");
			  }
			  else if($post_cancel_access == 'y') {
				  //we have dates on the resource
				  logToFile("post cance = y");
				  if($row["res_start_days"] <> 0 && $row["res_start_days"] <> "" &&
					  $row["res_end_days"] <> 0 && $row["res_end_days"] <> "" ) {
					  //set resource start days 
					  $resource_start_days = $row["res_start_days"];
					  $resource_end_days = $row["res_end_days"];
				  }
							  
				  //we have "days" on the resource
				  if($row["start_day"] <> 0 && $row["start_day"] <> "" &&
					  $row["end_day"] <> 0 && $row["end_day"] <> "" ) {
					  //set resource start days 
					  $resource_start_days = $row["access_start_days"] + $row["start_day"] - 1 ;
					  $resource_end_days = $row["access_start_days"] + $row["end_day"] - 1 ;
					  
					  //logToFile("We have 'days' on the resource"); 
					  //logToFile("resource_start_days: $resource_start_days"); 
					  //logToFile("resource_end_days: $resource_end_days"); 
					  //logToFile("User's row[access_start_days]: " . $row["access_start_days"]); 
					  
					  
					  /**
						  So if resource "days" are hard-coded, but resource end day is in the past,
						  then do not allow access. Will help offer expiring bonuses even though post-cancel-access is yes.
					  */
					  if($row["today"] > $resource_end_days ) { //Expiring Bonuses
						  //logToFile("Sorry: The 'end day' for this content is in the past...");
						  //logToFile("DAP001");
						  $access=false;
						  $errmsg="Sorry, the 'drip end date' for this protected content is in the past... You can find the drip setting for this content in the content responder tab of this product.";
						  continue;
					  }					
				  }
  
				  //if resource starts in future, lets not grant access.
				  if($row["today"] < $resource_start_days ) {
					  //logToFile("Resource Start Date is in future...");
					  //logToFile("DAP001");
					  $access=false;
					  $errmsg="Sorry, the 'Drip Start Date' for this protected content is in future... You can find the drip setting for this content in the content responder tab of this product.";
					  continue;
				  }					
  
				  
				  //
				  // If resource ends before upj start date - then no acesss. This could only happen 
				  //    when calendar dates are used at the resource level.
				  // If resource starts after upj end date - then no access. This could only happen
				  //    when calendar dates are used at the resource level.
				  //
				  //
				  if($resource_end_days < $row["access_start_days"] ||
					  $resource_start_days > $row["access_end_days"]) {
					  //logToFile("Product Start Date is in future...");
					  //logToFile("Resource Start Days:".$resource_start_days);
					  //logToFile("Resource End Days:".$resource_end_days);
					  //logToFile("Access Start Days:".$row["access_start_days"]);
					  //logToFile("Access End Days:".$row["access_end_days"]);
					  //logToFile("DAP005");
					  //logToFile($ERROR_CODES["DAP005"]);
					  $access=false;
					  $errmsg="Sorry, the user's 'Access Start Date' for the Product is in future... You can find the access start date of this user->product in dap users->manage page.";
					  continue;						
				  }		
				  
				  $access=true;
				  break;
			  
			  } else {				
				  //Product did not lauch yet.
				  logToFile("post cancel = n");
				  if($row["today"] < $row["access_start_days"]) {
					  logToFile("Product Start Date is in future...");
					  //logToFile("DAP001");
					  $access=false;
					    $errmsg="Sorry, the user's 'Access Start Date' for the Product is in future... You can find the access start date of this user->product in dap users->manage page.";
					  continue;
					  //return $resource;						
					  //$_SESSION['DAP_ERROR'] = $ERROR_CODES["DAP001"];
					  //$_SESSION['ERROR_URL'] = $row['error_page_url'];
					  //return FALSE;
				  }
				  //Product expired 
				  if($row["today"] > $row["access_end_days"]) {
					  logToFile("Product End Date is in past...");
					  //logToFile("DAP002");
					      $errmsg="Sorry, the user's 'Access End Date' for the Product is in past... You can find the access end date of this user->product in dap users->manage page.";
					  $access=false;
					  continue;
				  }
				  //product is available.
				  //check start date(days).
				  if($row["res_start_days"] <> 0 && $row["res_start_days"] <> "" &&
					  $row["res_end_days"] <> 0 && $row["res_end_days"] <> "" ) {
					  //resource is available in future.
					  if($row["today"] < $row["res_start_days"]) {
						  logToFile("Resource Start Date  is in future...");
						  //logToFile("DAP003");
						  $errmsg="Sorry, the user's 'Access End Date' for the Product is in past... You can find the access end date of this user->product in dap users->manage page.";
						  $access=false;
						  continue;
					  }
					  //resource  expired.
					  if($row["today"] > $row["res_end_days"]) {
						  logToFile("Resource End Date  is in past...");
						   $errmsg="Sorry, the 'drip end date' for this protected content is in the past... Check the drip setting for this content in the content responder tab of this product.";
						  //logToFile("DAP004");
						  $access=false;
						  continue;
					  }
					  //logToFile("Start Days(Date) and End Days(Date) check passed...");
	  
				  } else {
					  logToFile("Start Days(Date) and End Days(Date) are empty or ZERO.. not checking...");
				  }
				  //check start day
				  $lag_days = $row["today"] - $row["access_start_days"] + 1;
				  //check resource start and end day only if they are both non zero. 
				  if($row["start_day"] <> 0 && $row["start_day"] <> "" &&
					  $row["end_day"] <> 0 && $row["end_day"] <> "" ) {
  
					  //resource is available in future.
					  if($lag_days < $row["start_day"]) {
						  //logToFile("Lag Days:".$lag_days);
						  //logToFile("Start Day:".$row["start_day"]);
						  //logToFile("Resource Start Day  is in future...");
						  $errmsg="Sorry, the 'Drip Start Date' for this protected content is in future... Check the drip setting for this content in the content responder tab of this product.";
						  logToFile("DAP003");
						  $access=false;
						  continue;
						  //return $resource;
						  //$_SESSION['DAP_ERROR'] = $ERROR_CODES["DAP003"];
						  //$_SESSION['DAP_ERROR_URL'] = $row['error_page_url'];
						  //return FALSE;
					  }
					  //resource availability expired.
					  if($lag_days > $row["end_day"]) {
						  //logToFile("Lag Days:".$lag_days);
						  //logToFile("End Days:".$row["end_day"]);
						  //logToFile("Resource Start Day  is in past...");
						   $errmsg="Sorry, the 'drip end date' for this protected content is in the past... You can find the drip setting for this content in the content responder tab of this product.";
						  logToFile("DAP004");
						  $access=false;
						  continue;
						  //return $resource;
						  //$_SESSION['DAP_ERROR'] = $ERROR_CODES["DAP004"];
						  //$_SESSION['DAP_ERROR_URL'] = $row['error_page_url'];
						  //return FALSE;
					  }
					  //logToFile("Start Day and End Day check passed...");
					  } else {
						  logToFile("Start Day and End Day are empty or ZERO.. not checking...");
					  }
				  } //end of config allow post cancel access
				  if(!Dap_Resource::isCountAvailable($uid, $row['url'])) {
					  logToFile("Click Count is Negative...");
					  $access=false;
					  continue;
				  }
				  if(!Dap_Resource::displayResource($row['url'])) {
					  logToFile("This Resource is not displayable...");
					   $errmsg="Sorry, this Resource is not displayable...... You can find the drip setting for this content in the content responder tab of this product.";
					  $access=false;
					  continue;
				  }
				  //grant access - we should reach here ONLY IF THE PRODUCT RESOURCE RELATIONSHIP IS CLEAN AND ALLOWED.
				  //return $resource;
				  //$_SESSION['DAP_ERROR'] = $ERROR_CODES["DAP002"];
				  //logToFile("Granting Access To Resource URL:".$row["url"].", Resource ID:".$row["resource_id"]);
				  if($row['name'] == "") {
					  $name = $row['url'];
				  }
				  else {
					  $name = mb_convert_encoding($row["name"], "UTF-8", "auto");
					  //$name = "";
				  }
  
				  $name = stripslashes($name);
				  logToFile("Granting Access To Resource URL:".$row["url"].", Resource ID:".$row["resource_id"]);
				  $access=true;
				  break;
				  
				  //logToFile("HTML: " . $html);
			  } //while
			  
			  if($entered==false) {
				  logToFile("user does not have access to product under which content is protected.");
				  $errmsg="The user or user/product status is NOT active OR the user does not have access to any product under which this content is protected.";
				  $accesserrstr=$errmsg;
				  return -1;
			  }
			  
			  if ($access!=true) {
				  //user has access to the products under which this content is dripped but the access end date is likely in the past. 
				  logToFile("inactproduct (-3) =" . $access);
				  $accesserrstr=$errmsg;
				  return -1;
			  }
			  else {
			  	return 0; //true;
			  }
			 
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			$access=false;
			return $access;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			$access=false;
			return $access;
		}	
		
		//user has access to the products under which this content is dripped but the access end date is likely in the past. 
		logToFile("inactproduct (-3) =" . $access);
		$accesserrstr=$errmsg;
		return -1;
		
	}

	/*
		Return active file resources (as of today) associated with this User Product association.

	*/	
	public function getActiveResources($sss="N", $orderBy="desc", $limit=10000) {
		try {
			logToFile("Dap_UsersResources.getActiveResources() - Init...User Id:".$this->user_id.", Product Id:".$this->product_id);
			logToFile("Dap_UsersResources.getActiveResources() - SSS=" . $sss);
			$html = "";
			
			$product = Dap_Product::loadProduct($this->product_id);
			$isSSSMaster=$product->getIs_master();
			logToFile("userlinks.inc: isSSSMaster=". $isSSSMaster);
			
			if (($sss == "N") || ($isSSSMaster == "Y")){
				$sql = "select 
						upj.transaction_id as transid,
						TO_DAYS(now()) as today,
						TO_DAYS(upj.access_start_date) as access_start_days,
						TO_DAYS(upj.access_end_date) as access_end_days,
						prj.is_free as is_free,
						prj.start_day as start_day,
						prj.end_day as end_day,
						TO_DAYS(prj.start_date) as res_start_days,
						TO_DAYS(prj.end_date) as res_end_days,
						prj.num_clicks as num_clicks,
						prj.resource_id as resource_id,
						p.error_page_url as error_page_url,
						fr.url as url,
						fr.name as name
				from
					dap_products p,
					dap_products_resources_jn prj,
					dap_file_resources fr,
					dap_users u,
					dap_users_products_jn upj				
				where
					u.id =:uid and
					p.id =:product_id and
					u.status = 'A' and 
					prj.resource_id = fr.id and 					
					prj.resource_type = 'F' and
					p.id = prj.product_id and
					p.status = 'A' and
					upj.user_id = u.id and
					upj.product_id = p.id and
					upj.status = 'A' 
				order by 
					start_day $orderBy,
					res_start_days $orderBy,
					display_order $orderBy,
					prj.num_clicks $orderBy
				limit 0, $limit
					";
			}
			else {
				
				   $hasAccess=Dap_UserCredits::hasAccessTo($this->user_id, $this->product_id); 
				   $sssHasAccess=true;
				   if($hasAccess) {
					   $sql = "select DISTINCT 
						upj.transaction_id as transid,
						TO_DAYS(now()) as today,
						TO_DAYS(upj.access_start_date) as access_start_days,
						TO_DAYS(upj.access_end_date) as access_end_days,
						prj.is_free as is_free,
						prj.start_day as start_day,
						prj.end_day as end_day,
						TO_DAYS(prj.start_date) as res_start_days,
						TO_DAYS(prj.end_date) as res_end_days,
						prj.num_clicks as num_clicks,
						prj.resource_id as resource_id,
						p.error_page_url as error_page_url,
						fr.url as url,
						fr.name as name
				from
					dap_products p,
					dap_products_resources_jn prj,
					dap_file_resources fr,
					dap_users u,
					dap_users_products_jn upj,
					dap_users_credits duc
				where
					u.id =:uid and
					p.id =:product_id and
					u.status = 'A' and 
					prj.resource_id = fr.id and 					
					prj.resource_type = 'F' and
					p.id = prj.product_id and
					p.status = 'A' and
					upj.user_id = u.id and
					upj.product_id = p.id and
					upj.status = 'A' and
					duc.user_id = u.id and
					duc.product_id = p.id 
				order by 
					fr.name, prj.display_order
				limit 0, $limit
					";
				   }
				   else {
					   	$sql = "select 
						upj.transaction_id as transid,
						TO_DAYS(now()) as today,
						TO_DAYS(upj.access_start_date) as access_start_days,
						TO_DAYS(upj.access_end_date) as access_end_days,
						prj.is_free as is_free,
						prj.start_day as start_day,
						prj.end_day as end_day,
						TO_DAYS(prj.start_date) as res_start_days,
						TO_DAYS(prj.end_date) as res_end_days,
						prj.num_clicks as num_clicks,
						prj.resource_id as resource_id,
						p.error_page_url as error_page_url,
						fr.url as url,
						fr.name as name
				from
					dap_products p,
					dap_products_resources_jn prj,
					dap_file_resources fr,
					dap_users u,
					dap_users_products_jn upj,
					dap_users_credits duc
				where
					u.id =:uid and
					p.id =:product_id and
					u.status = 'A' and 
					prj.resource_id = fr.id and 					
					prj.resource_type = 'F' and
					p.id = prj.product_id and
					p.status = 'A' and
					upj.user_id = u.id and
					upj.product_id = p.id and
					upj.status = 'A' and
					duc.user_id = u.id and
					duc.product_id = p.id and
					duc.resource_id = prj.resource_id
				order by 
					fr.name, prj.display_order
				limit 0, $limit
					";
				   }
			}
				//	now() between upj.access_start_date and upj.access_end_date	and
				//	(TO_DAYS(NOW()) - TO_DAYS(access_start_date)) between prj.start_day and prj.end_day
			
			//echo "sql: $sql<br>"; exit;
			$dap_dbh = Dap_Connection::getConnection();
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':uid', $this->user_id, PDO::PARAM_INT);
			$stmt->bindParam(':product_id', $this->product_id, PDO::PARAM_INT);
			$stmt->execute();
			
			$html = "<ul class=\"dap_product_links_list\">";
			$post_cancel_access = Dap_Config::get("POST_CANCEL_ACCESS");
			if(isset($post_cancel_access)) {
				$post_cancel_access = strtolower($post_cancel_access);
			}			
			//lets loop over the resource list
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$row["name"] = mb_convert_encoding($row["name"], "UTF-8", "auto");
				logToFile("Resource URL:".$row["url"]);
				//logToFile("Resource ID:".$row["resource_id"]);
				//logToFile("Today:".$row["today"]);
				//echo "<br>";
				//logToFile("Product Access Start Days:".$row["access_start_days"]);
				//echo "<br>";
				//logToFile("Product Access End Days:" . $row["access_end_days"]);
				//echo "<br>";
				//logToFile("Resource Start Day:" . $row["start_day"]);
				//echo "<br>";
				//logToFile("Resource End Day:" . $row["end_day"]);
				//echo "<br>";
				//logToFile("Resource Start (Date)DAYS:" . $row["res_start_days"]);
				//echo "<br>";
				//logToFile("Resource End (Date)DAYS:" . $row["res_end_days"]);
				//echo "<br>";
				//logToFile("Product Resource Num Clicks:" . $row["num_clicks"]);
				//echo "<br>";
				
				//product is SIGNUP only, then check if resource is free
				// -1 - direct sign up for free resources
				// -2 - admin sign up - for free resources
				// -3 - admin paid sign up - for all resources
				// TODO: WE NEED TO COME UP WITH SPECIAL TRANS ID FOR SIGNUP ONLY.
				//TODO: ADMIN ADDED USER-PRODUCT RELATIONSHIP IS NOT WORKING. because the transid is put in as 0.
				
				/** 
				if((($row["transid"] == "-2") || ($row["transid"] == "-1")) && (strtolower($row["is_free"]) != "y")) {
					//logToFile("Not Free Resource, but User is FREE...");
					//logToFile("DAP005");
					continue;
					//return $resource;			
					//$_SESSION['DAP_ERROR'] = $ERROR_CODES["DAP005"];
					//$_SESSION['DAP_ERROR_URL'] = $row['error_page_url'];
					//return FALSE;			
				}
				*/
				
			//lets present two modes of operation 
			if($sssHasAccess){
				logToFile("DAP_UsersProducts.class.php: getActiveResources(): SSS product, show content list");
			}
			else if($post_cancel_access == 'y') {
				//we have dates on the resource
				if($row["res_start_days"] <> 0 && $row["res_start_days"] <> "" &&
					$row["res_end_days"] <> 0 && $row["res_end_days"] <> "" ) {
					//set resource start days 
					$resource_start_days = $row["res_start_days"];
					$resource_end_days = $row["res_end_days"];
				}
							
				//we have "days" on the resource
				if($row["start_day"] <> 0 && $row["start_day"] <> "" &&
					$row["end_day"] <> 0 && $row["end_day"] <> "" ) {
					//set resource start days 
					$resource_start_days = $row["access_start_days"] + $row["start_day"] - 1 ;
					$resource_end_days = $row["access_start_days"] + $row["end_day"] - 1 ;
					
					//logToFile("We have 'days' on the resource"); 
					//logToFile("resource_start_days: $resource_start_days"); 
					//logToFile("resource_end_days: $resource_end_days"); 
					//logToFile("User's row[access_start_days]: " . $row["access_start_days"]); 
					
					
					/**
						So if resource "days" are hard-coded, but resource end day is in the past,
						then do not allow access. Will help offer expiring bonuses even though post-cancel-access is yes.
					*/
					if($row["today"] > $resource_end_days ) { //Expiring Bonuses
						//logToFile("Sorry: The 'end day' for this content is in the past...");
						//logToFile("DAP001");
						continue;
					}					
				}

				//if resource starts in future, lets not grant access.
				if($row["today"] < $resource_start_days ) {
					//logToFile("Resource Start Date is in future...");
					//logToFile("DAP001");
					continue;
				}					

				
				//
				// If resource ends before upj start date - then no acesss. This could only happen 
				//    when calendar dates are used at the resource level.
				// If resource starts after upj end date - then no access. This could only happen
				//    when calendar dates are used at the resource level.
				//
				//
				if($resource_end_days < $row["access_start_days"] ||
					$resource_start_days > $row["access_end_days"]) {
					//logToFile("Product Start Date is in future...");
					//logToFile("Resource Start Days:".$resource_start_days);
					//logToFile("Resource End Days:".$resource_end_days);
					//logToFile("Access Start Days:".$row["access_start_days"]);
					//logToFile("Access End Days:".$row["access_end_days"]);
					//logToFile("DAP005");
					//logToFile($ERROR_CODES["DAP005"]);
					continue;						
				}				
			
			} else {				
				//Product did not lauch yet.
				if($row["today"] < $row["access_start_days"]) {
					//logToFile("Product Start Date is in future...");
					//logToFile("DAP001");
					continue;
					//return $resource;						
					//$_SESSION['DAP_ERROR'] = $ERROR_CODES["DAP001"];
					//$_SESSION['ERROR_URL'] = $row['error_page_url'];
					//return FALSE;
				}
				//Product expired 
				if($row["today"] > $row["access_end_days"]) {
					//logToFile("Product End Date is in past...");
					//logToFile("DAP002");
					continue;
				}
				//product is available.
				//check start date(days).
				if($row["res_start_days"] <> 0 && $row["res_start_days"] <> "" &&
					$row["res_end_days"] <> 0 && $row["res_end_days"] <> "" ) {
					//resource is available in future.
					if($row["today"] < $row["res_start_days"]) {
						//logToFile("Resource Start Date  is in future...");
						//logToFile("DAP003");
						continue;
					}
					//resource  expired.
					if($row["today"] > $row["res_end_days"]) {
						//logToFile("Resource End Date  is in past...");
						//logToFile("DAP004");
						continue;
					}
					//logToFile("Start Days(Date) and End Days(Date) check passed...");
	
				} else {
					//logToFile("Start Days(Date) and End Days(Date) are empty or ZERO.. not checking...");
				}
				//check start day
				$lag_days = $row["today"] - $row["access_start_days"] + 1;
				//check resource start and end day only if they are both non zero. 
				if($row["start_day"] <> 0 && $row["start_day"] <> "" &&
					$row["end_day"] <> 0 && $row["end_day"] <> "" ) {

					//resource is available in future.
					if($lag_days < $row["start_day"]) {
						//logToFile("Lag Days:".$lag_days);
						//logToFile("Start Day:".$row["start_day"]);
						//logToFile("Resource Start Day  is in future...");
						//logToFile("DAP003");
						continue;
						//return $resource;
						//$_SESSION['DAP_ERROR'] = $ERROR_CODES["DAP003"];
						//$_SESSION['DAP_ERROR_URL'] = $row['error_page_url'];
						//return FALSE;
					}
					//resource availability expired.
					if($lag_days > $row["end_day"]) {
						//logToFile("Lag Days:".$lag_days);
						//logToFile("End Days:".$row["end_day"]);
						//logToFile("Resource Start Day  is in past...");
						//logToFile("DAP004");
						continue;
						//return $resource;
						//$_SESSION['DAP_ERROR'] = $ERROR_CODES["DAP004"];
						//$_SESSION['DAP_ERROR_URL'] = $row['error_page_url'];
						//return FALSE;
					}
					//logToFile("Start Day and End Day check passed...");
					} else {
						//logToFile("Start Day and End Day are empty or ZERO.. not checking...");
					}
				} //end of config allow post cancel access
				if(!Dap_Resource::isCountAvailable($this->user_id, $row['url'])) {
					//logToFile("Click Count is Negative...");
					continue;
				}
				if(!Dap_Resource::displayResource($row['url'])) {
					//logToFile("This Resource is not displayable...");
					continue;
				}
				//grant access - we should reach here ONLY IF THE PRODUCT RESOURCE RELATIONSHIP IS CLEAN AND ALLOWED.
				//return $resource;
				//$_SESSION['DAP_ERROR'] = $ERROR_CODES["DAP002"];
				//logToFile("Granting Access To Resource URL:".$row["url"].", Resource ID:".$row["resource_id"]);
				if($row['name'] == "") {
					$name = $row['url'];
				}
				else {
					$name = mb_convert_encoding($row["name"], "UTF-8", "auto");
					//$name = "";
				}

				$name = stripslashes($name);
				$html .= "<li><a href='".stripslashes($row['url'])."'>".stripslashes($name)."</a></li>";
				//logToFile("HTML: " . $html);
			}
			
			//logToFile("html: $html"); 
			if($html == "<ul class=\"dap_product_links_list\">") {
				$html .= "<li>".USER_LINKS_NOLINKSFOUND_TEXT."</li>";
			}
			$html .= "</ul>";
			return $html;
			
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		

	}




	/*
		Return FUTURE file resources (as of today) associated with this User Product association.

	*/	
	public function getFutureResources($sss="N", $orderBy="desc", $limit=10000, $makeLinksClickable="Y") {
		try {
			//logToFile("Dap_UsersResources.getActiveResources() - Init...User Id:".$this->user_id.", Product Id:".$this->product_id);
			$html = "";
			if ($sss == "N") {
				$sql = "select 
						upj.transaction_id as transid,
						TO_DAYS(now())+1 as today,
						TO_DAYS(upj.access_start_date) as access_start_days,
						TO_DAYS(upj.access_end_date) as access_end_days,
						prj.is_free as is_free,
						prj.start_day as start_day,
						prj.end_day as end_day,
						TO_DAYS(prj.start_date) as res_start_days,
						TO_DAYS(prj.end_date) as res_end_days,
						prj.num_clicks as num_clicks,
						prj.resource_id as resource_id,
						p.error_page_url as error_page_url,
						fr.url as url,
						fr.name as name
				from
					dap_products p,
					dap_products_resources_jn prj,
					dap_file_resources fr,
					dap_users u,
					dap_users_products_jn upj				
				where
					u.id =:uid and
					p.id =:product_id and
					u.status = 'A' and 
					prj.resource_id = fr.id and 
					prj.resource_type = 'F' and
					p.id = prj.product_id and
					p.status = 'A' and
					upj.user_id = u.id and
					upj.product_id = p.id and
					upj.status = 'A' 
				order by 
					start_day $orderBy,
					res_start_days $orderBy,
					prj.num_clicks $orderBy
				limit 0, $limit
					";
			}
			else {
				$sql = "
				select 
					upj.transaction_id as transid,
					TO_DAYS(now())+1 as today,
					TO_DAYS(upj.access_start_date) as access_start_days,
					TO_DAYS(upj.access_end_date) as access_end_days,
					prj.is_free as is_free,
					prj.start_day as start_day,
					prj.end_day as end_day,
					TO_DAYS(prj.start_date) as res_start_days,
					TO_DAYS(prj.end_date) as res_end_days,
					prj.num_clicks as num_clicks,
					prj.resource_id as resource_id,
					p.error_page_url as error_page_url,
					fr.url as url,
					fr.name as name
				from
					dap_products p,
					dap_products_resources_jn prj,
					dap_file_resources fr,
					dap_users u,
					dap_users_products_jn upj,
					dap_users_credits duc
				where
					u.id =:uid and
					p.id =:product_id and
					u.status = 'A' and 
					prj.resource_id = fr.id and 
					prj.resource_type = 'F' and
					p.id = prj.product_id and
					p.status = 'A' and
					upj.user_id = u.id and
					upj.product_id = p.id and
					upj.status = 'A' and
					duc.user_id = u.id and
					duc.product_id = p.id and
					duc.resource_id = prj.resource_id
				order by 
					fr.name, prj.display_order
				limit 0, $limit
					";
				
			}
				//	now() between upj.access_start_date and upj.access_end_date	and
				//	(TO_DAYS(NOW()) - TO_DAYS(access_start_date)) between prj.start_day and prj.end_day
			
			//echo "sql: $sql<br>"; exit;
			$dap_dbh = Dap_Connection::getConnection();
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':uid', $this->user_id, PDO::PARAM_INT);
			$stmt->bindParam(':product_id', $this->product_id, PDO::PARAM_INT);
			$stmt->execute();
			
			$html = "<ul class=\"dap_product_links_list\">";
			//lets loop over the resource list
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				//product is SIGNUP only, then check if resource is free
				// -1 - direct sign up for free resources
				// -2 - admin sign up - for free resources
				// -3 - admin paid sign up - for all resources
				//TODO: WE NEED TO COME UP WITH SPECIAL TRANS ID FOR SIGNUP ONLY.
				//TODO: ADMIN ADDED USER-PRODUCT RELATIONSHIP IS NOT WORKING. because the transid is put in as 0.
				
				/** 
				if((($row["transid"] == "-2") || ($row["transid"] == "-1")) && (strtolower($row["is_free"]) != "y")) {
					//logToFile("Not Free Resource, but User is FREE...");
					//logToFile("DAP005");
					continue;
					//return $resource;			
					//$_SESSION['DAP_ERROR'] = $ERROR_CODES["DAP005"];
					//$_SESSION['DAP_ERROR_URL'] = $row['error_page_url'];
					//return FALSE;			
				}
				*/
			
			
			logToFile("---------------------------------------------------------"); 
			logToFile("url: " . $row["url"]);
			logToFile("today: " . $row["today"]);
			$comingInDays = "";
			//logToFile("start_day: " . $row["start_day"]); 
			//logToFile("resource_start_days: $resource_start_days");
			
			/**
				What we need:
				$row["today"]
				res_start_days - start DATE
				start_day - start DAY
			*/
			
			//logToFile("resource_end_days: $resource_end_days"); 
			//logToFile("User's row[access_start_days]: " . $row["access_start_days"]); 
			
			//we have dates on the resource
			if($row["res_start_days"] <> 0 && $row["res_start_days"] <> "" &&
				$row["res_end_days"] <> 0 && $row["res_end_days"] <> "" ) {
				//set resource start days 
				$resource_start_days = $row["res_start_days"];
				$resource_end_days = $row["res_end_days"];
			}
							
			//we have "days" on the resource
			if($row["start_day"] <> 0 && $row["start_day"] <> "" &&
				$row["end_day"] <> 0 && $row["end_day"] <> "" ) {
				//set resource start days 
				$resource_start_days = $row["access_start_days"] + $row["start_day"] - 1 ;
				$resource_end_days = $row["access_start_days"] + $row["end_day"] - 1 ;
				
				//logToFile("We have 'days' on the resource"); 
				logToFile("resource_start_days: $resource_start_days"); 
			}
			
			//if resource start dau was in past, then user already has access - so ignore for future content list
			if($row["today"] > $resource_start_days ) {
				logToFile("Resource Start Date is in past...");
				logToFile("DAP001");
				continue;
			}					

				
			//Product expired 
			if($row["today"] > $row["access_end_days"]) {
				//logToFile("Product End Date is in past...");
				logToFile("DAP002");
				continue;
			}
			//check start day
			$lag_days = $row["today"] - $row["access_start_days"] + 1;
			//check resource start and end day only if they are both non zero. 
			if($row["start_day"] <> 0 && $row["start_day"] <> "" &&
				$row["end_day"] <> 0 && $row["end_day"] <> "" ) {

				//resource is available in future.
				if($lag_days > $row["start_day"]) {
					//logToFile("Lag Days:".$lag_days);
					//logToFile("Start Day:".$row["start_day"]);
					//logToFile("Resource Start Day  is in future...");
					logToFile("DAP003");
					continue;
					//return $resource;
					//$_SESSION['DAP_ERROR'] = $ERROR_CODES["DAP003"];
					//$_SESSION['DAP_ERROR_URL'] = $row['error_page_url'];
					//return FALSE;
				}
				//resource availability expired.
				if($lag_days > $row["end_day"]) {
					//logToFile("Lag Days:".$lag_days);
					//logToFile("End Days:".$row["end_day"]);
					//logToFile("Resource Start Day  is in past...");
					logToFile("DAP004");
					continue;
					//return $resource;
					//$_SESSION['DAP_ERROR'] = $ERROR_CODES["DAP004"];
					//$_SESSION['DAP_ERROR_URL'] = $row['error_page_url'];
					//return FALSE;
				}
				//logToFile("Start Day and End Day check passed...");
				} else {
					//logToFile("Start Day and End Day are empty or ZERO.. not checking...");
				}
			//} //end of config allow post cancel access
				if(!Dap_Resource::isCountAvailable($this->user_id, $row['url'])) {
					//logToFile("Click Count is Negative...");
					continue;
				}
				if(!Dap_Resource::displayResource($row['url'])) {
					//logToFile("This Resource is not displayable...");
					continue;
				}
				//grant access - we should reach here ONLY IF THE PRODUCT RESOURCE RELATIONSHIP IS CLEAN AND ALLOWED.
				//return $resource;
				//$_SESSION['DAP_ERROR'] = $ERROR_CODES["DAP002"];
				//logToFile("Granting Access To Resource URL:".$row["url"].", Resource ID:".$row["resource_id"]);
				if($row['name'] == "") {
					$name = $row['url'];
				}
				else {
					$name = mb_convert_encoding($row["name"], "UTF-8", "auto");
					//$name = "";
				}

				$name = stripslashes($name);
				$comingInDays = intval($resource_start_days) - intval($row["today"]) + 1;
				$comingInDaysPrefix = str_replace("XXX",$comingInDays,USER_LINKS_COMINGSOON_PREFIX_TEXT);

				if($makeLinksClickable == "Y") {
					$html .= "<li>$comingInDaysPrefix <a href='".stripslashes($row['url'])."'>".stripslashes($name)."</a></li>";
				} else {
					$html .= "<li>$comingInDaysPrefix ".stripslashes($name)."</li>";
				}
				
				//logToFile("HTML: " . $html);
			}
			if($html == "<ul id=\"dap_product_links_list\">") {
				$html .= "<li>".USER_LINKS_NOLINKSFOUND_TEXT."</li>";
			}
			$html .= "</ul>";
			return $html;
			
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		

	}

	//For a given user, load products that user does NOT have access to
	public static function loadProductsNoAccess($userId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$userProductRelArray = array();
			$sss_order_by = Dap_Config::get("SSS_ORDER_BY");
			$sql = "";
	
			switch ($sss_order_by) {
				case "Most-Popular":
				$sql = "SELECT 
					upj.product_id as id, 
					count(product_id) as counta 
				FROM 
					dap_users_products_jn upj, 
					dap_products p
				where
					p.is_master = 'N' and 
					p.self_service_allowed = 'Y' and 
					p.id not in (SELECT product_id FROM dap_users_products_jn WHERE user_id = :userId) 
				group by 
					upj.product_id order by counta desc";
				break;
			
				case "Oldest-First":
				$sql = "select id from dap_products where is_master = 'N' and self_service_allowed = 'Y' and id not in ( SELECT product_id FROM dap_users_products_jn WHERE user_id = :userId) order by id asc";
				break;

				case "Newest-First":
				$sql = "select id from dap_products where is_master = 'N' and self_service_allowed = 'Y' and id not in ( SELECT product_id FROM dap_users_products_jn WHERE user_id = :userId ) order by id desc";
				break;

				case "Alpha-Asc":
				$sql = "select id from dap_products where is_master = 'N' and self_service_allowed = 'Y' and id not in ( SELECT product_id FROM dap_users_products_jn WHERE user_id = :userId ) order by name asc";
				break;

				case "Alpha-Desc":
				$sql = "select id from dap_products where is_master = 'N' and self_service_allowed = 'Y' and id not in ( SELECT product_id FROM dap_users_products_jn WHERE user_id = :userId ) order by name desc";
			}



			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->execute();	
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$userProduct = new Dap_UsersProducts();
				$userProduct->setProduct_id($row['id']);
				$userProductRelArray[] = $userProduct;
			}
			
			$stmt = null;
			$dap_dbh = null;
			
			return $userProductRelArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}	

//For a given user, load products that user does NOT have access to
	public static function loadChildProductsForAMaster($userId, $master_product_id) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$userProductRelArray = array();
			$sss_order_by = Dap_Config::get("SSS_ORDER_BY");
			$sql = "";
			
			$sql = "SELECT 
					p.id as id, 
					count(p.id) as counta 
				FROM 
					dap_products p,
					dap_products_mc_sss_jn dmcsss
				where
					p.is_master = 'N' and 
					p.self_service_allowed = 'Y' and
					dmcsss.child_product_id = p.id and
					dmcsss.master_product_id = :master_product_id and
					p.selfservice_start_date <= CURDATE( ) and
					p.selfservice_end_date >= CURDATE( )
				group by 
					p.id order by counta desc";
				
				
			switch ($sss_order_by) {
				case "Most-Popular":
				break;
			/*
				case "Oldest-First":
				$sql = "select id from dap_products where is_master = 'N' and self_service_allowed = 'Y' and id not in ( SELECT product_id FROM dap_users_products_jn WHERE user_id = :userId) order by id asc";
				break;

				case "Newest-First":
				$sql = "select id from dap_products where is_master = 'N' and self_service_allowed = 'Y' and id not in ( SELECT product_id FROM dap_users_products_jn WHERE user_id = :userId ) order by id desc";
				break;

				case "Alpha-Asc":
				$sql = "select id from dap_products where is_master = 'N' and self_service_allowed = 'Y' and id not in ( SELECT product_id FROM dap_users_products_jn WHERE user_id = :userId ) order by name asc";
				break;

				case "Alpha-Desc":
				$sql = "select id from dap_products where is_master = 'N' and self_service_allowed = 'Y' and id not in ( SELECT product_id FROM dap_users_products_jn WHERE user_id = :userId ) order by name desc";*/
			}

			//logToFile($sql,LOG_DEBUG_DAP);
			
			logToFile("In loadChildProductsForAMaster"); 
			
			$stmt = $dap_dbh->prepare($sql);
	//		$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':master_product_id', $master_product_id, PDO::PARAM_INT);

			$stmt->execute();	
			
			logToFile("after loadChildProductsForAMaster"); 
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$userProduct = new Dap_UsersProducts();
				$userProduct->setProduct_id($row['id']);
				$userProductRelArray[] = $userProduct;
			}
			
			$stmt = null;
			$dap_dbh = null;
			
			return $userProductRelArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}	

	//For a given user, load products that user does NOT have access to
	public static function loadMasterProductsSSS($userId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$userProductRelArray = array();
			$sss_order_by = Dap_Config::get("SSS_ORDER_BY");
			$sql = "";
	
			$sql = "SELECT 
				duc.product_id as id, 
				duc.credits_earned,
				duc.credits_spent
			FROM 
				dap_users_products_jn upj,
				dap_products p,
				dap_users_credits duc
			where
				upj.product_id = p.id and
				p.is_master = 'Y' and 
				p.self_service_allowed = 'Y' and
				duc.product_id = p.id and
				duc.credits_earned > 0 and
				duc.user_id = :userId and 
				duc.product_id = upj.product_id and
				duc.user_id = upj.user_id
			group by 
				upj.product_id";
		
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->execute();	
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$userCredits = new Dap_UserCredits();
				logToFile("Dap_UsersProducts.class.php: masterproductId=" . $row['id']);
				
				$userCredits->setProduct_id($row['id']);
				$userCredits->setCredits_earned($row['credits_earned']);
				$userCredits->setCredits_spent($row['credits_spent']);
				$userCreditsRelArray[] = $userCredits;
			}
			
			$stmt = null;
			$dap_dbh = null;
			
			return $userCreditsRelArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}	
	
	public static function activate($code, $user, $productId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$dap_dbh->beginTransaction(); //begin the transaction
			
			//First activate user
			$sql = "update 
						dap_users 
					set
						status = 'A',
						signup_date = '" . date("Y-m-d H:i:s") . "',
						ipaddress = '" . getIpOfUser() . "'
					where
						activation_key = '" . $code . "' and
						status = 'U'
					";
						
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();
			$stmt = null;

			//Now activate user-products join table
			$userId = $user->getId();

			$sql = "update 
						dap_users_products_jn
					set
						status = 'A',
						access_start_date = '" . date("Y-m-d") . "'
					where
						user_id = :userId and
						product_id = :productId and
						status = 'I'
					";
			
			//logToFile("-----------------Sql: $sql"); 
			//logToFile("userId: $userId, productId: $productId"); 
						
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->execute();
			$dap_dbh->commit(); //commit the transaction
			
			//lets make sure we really updated something.
			$count = $stmt->rowCount();
			$credits=0;
			
			if($count > 0) { //User was indeed activated, so send welcome email to user
			
			// add credits if SSS product (upon activation)		
			  $product = Dap_Product::loadProduct($productId);
			  $userProduct = Dap_UsersProducts::load($userId, $productId);
			  $recurring="N";
			  $transId='-1';
			  if($userProduct != NULL) {
				$recurring="Y"; // user already has access to the recurring product
			  }
			  
			  if (($product->getCredits() > 0) && ($product->getSelf_service_allowed() == "Y")) {
				logToFile("Dap_UsersProducts.addUsersProducts(): activate() : SSS: recurring=".$recurring,LOG_DEBUG_DAP); 
				$productId=$product->getId();
				$isSSSMaster=$product->getIs_master();
				
				if( strtolower($product->getIs_recurring()) != "y") {
				  $credits=$product->getCredits(); // free product (onetime) and user getting access for first time
				}
				else if ($recurring == "N") //first time adding user to recurring product
				  $credits=$product->getCredits();
				else { //recurring payment, user already exists
				  if( strtolower($product->getIs_recurring()) == "y") {
					$credits=$product->getRecurringCredits();
				  }
				  else {
					logToFile("Dap_UsersProducts.addUsersProducts(): activate() : SSS: it's a recurring payment for a non-recurring product (free product), no credits assigned. Looks like same user is trying to re-sign for the free product to earn credits. To prevent free product/signup abuse, no credits assigned",LOG_DEBUG_DAP); 
				  }
				}
				if($credits > 0) {
				  if($isSSSMaster == "Y") {
					logToFile("Dap_UsersProducts.addUsersProducts(): activate(): SSS: addign child, credits added to total",LOG_DEBUG_DAP); 
					Dap_UserCredits::addCredits($userId, $productId, $transId, $credits, 0, "By Signup - FREE");
				  }else {
					logToFile("Dap_UsersProducts.addUsersProducts(): activate(): SSS: addign child, credits added to total",LOG_DEBUG_DAP); 
					Dap_UserCredits::redeemCreditsAtProductLevel($userId, $productId, $credits, "Admin Added-Manual FREE");
				  }
				}
			  }
				
			  logToFile("sending welcome email to " . $user->getEmail(),LOG_DEBUG_DAP); 
			  sendUserProductWelcomeEmail($userId, $productId);
				
				//TODO send activation/welcome email to admin	
			}
		  	
			$stmt = null;
			$dap_dbh = null;

			return;
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
	
	
	public static function activateUserAllProducts($user) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$dap_dbh->beginTransaction(); //begin the transaction
			
			//First activate user
			$sql = "update 
						dap_users 
					set
						status = 'A',
						signup_date = '" . date("Y-m-d H:i:s") . "'
					where
						activation_key = '" . $user->getActivation_key() . "' and
						status = 'U'
					";
						
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();
			$stmt = null;

			//Now activate user-products join table
			$userId = $user->getId();

			$sql = "update 
						dap_users_products_jn
					set
						status = 'A',
						access_start_date = '" . date("Y-m-d") . "'
					where
						user_id = :userId and
						status = 'I'
					";
			
			//logToFile("-----------------Sql: $sql"); 
			//logToFile("userId: $userId, productId: $productId"); 
			
			$userId = $user->getId();
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->execute();
			$dap_dbh->commit(); //commit the transaction
			
			$stmt = null;
			$dap_dbh = null;

			return;
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

	public static function directSignupSubmit($user, $coupon_code="", $product_id, $coupon_id="", $isPaid="n") {
		try {
			
			$userDB = null;
			$username = $user->getUser_name();
			$email = $user->getEmail();
			$first_name = $user->getFirst_name();
			$last_name = $user->getLast_name();
			
			//logToFile("username in directSignupSubmit: " . $username); 
			
			//Check if username exists in db, and it matches email of same user, only then proceed forward
			if( ($username != "") && (Dap_User::isInUse("email",$user->getEmail()) || Dap_User::isInUse("user_name",$user->getUser_name()) )) {
				$userDB = Dap_User::loadUserByEmailAndUsername($user->getEmail(), $user->getUser_name());
				if(  !isset($userDB) || ($userDB == null) ) { 
					//no record found for this email/username combo - so separate users have the email and username already in use, so error
					$msg = "Sorry, username/email already in use. <br/>Please go 'back' and pick a different username & email, <br/>or use the correct email/username combination if you are <br/>an existing user";
					header( "Location:error.php?msg=" . urlencode($msg) );
					exit;
				}
				
				//If existing user whose email/username in match that of email/username from form, 
				//then simply update db data with incoming form data
				if(  isset($userDB) && ($userDB != null) ) {
					$first_name = ($user->getFirst_name() != "") ? $user->getFirst_name() : $userDB->getFirst_name();
					$last_name = ($user->getLast_name() != "") ? $user->getLast_name() : $userDB->getLast_name();
					$user_name = ($user->getUser_name() != "") ? $user->getUser_name() : $userDB->getUser_name();
					$email = ($user->getEmail() != "") ? $user->getEmail() : $userDB->getEmail();
					$address1 = ($user->getAddress1() != "") ? $user->getAddress1() : $userDB->getAddress1();
					$address2 = ($user->getAddress2() != "") ? $user->getAddress2() : $userDB->getAddress2();
					$city = ($user->getCity() != "") ? $user->getCity() : $userDB->getCity();
					$state = ($user->getState() != "") ? $user->getState() : $userDB->getState();
					$zip = ($user->getZip() != "") ? $user->getZip() : $userDB->getZip();
					$country = ($user->getCountry() != "") ? $user->getCountry() : $userDB->getCountry();
					$phone = ($user->getPhone() != "") ? $user->getPhone() : $userDB->getPhone();
					$fax = ($user->getFax() != "") ? $user->getFax() : $userDB->getFax();
					$company = ($user->getCompany() != "") ? $user->getCompany() : $userDB->getCompany();
					$title = ($user->getTitle() != "") ? $user->getTitle() : $userDB->getTitle();
					$paypal_email = ($user->getPaypal_email() != "") ? $user->getPaypal_email() : $userDB->getPaypal_email();
					
					$userDB->setFirst_name($first_name);
					$userDB->setLast_name($last_name);
					
					if (!isset($user_name) || ($user_name == "")) {
						$userDB->setUser_name(NULL);
					}
					else {
						//check to be sure
						/*$uname=generateUsername("Dap_UsersProducts.class.php.directSignupSubmit()",$email,$first_name,$last_name);
						$user->setUser_name($uname);
						logToFile("Dap_UsersProducts.class.php.directSignupSubmit: existing user whose email/username in match that of email/username from form: ".$uname,LOG_INFO_DAP); */
						$userDB->setUser_name($user_name);
					}
					
					$userDB->setEmail($email);
					$userDB->setAddress1($address1);
					$userDB->setAddress2($address2);
					$userDB->setCity($city);
					$userDB->setState($state);
					$userDB->setZip($zip);
					$userDB->setCountry($country);
					$userDB->setPhone($phone);
					$userDB->setFax($fax);
					$userDB->setCompany($company);
					$userDB->setTitle($title);
					$userDB->setPaypal_email($paypal_email);
					
					$userDB->update();
					
					$uid = Dap_UsersProducts::addNewUserToProduct($email, $first_name, $last_name, $username, $product_id, $isPaid, "A", $coupon_id);
					return $userDB;
					
					//Now check: If existing user does not already have access to incoming product, then give access. If not, DO NOT extend and simply return.
					//if ( $userDB->hasEverHadAccessTo($product_id) ) {
						//Don't extend and simply return
						//return $userDB;
						
						//logToFile("User already has access to product, so just returning"); 
						//$msg = "MSG_ALREADY_SIGNEDUP";
						//$msg = "Sorry, it appears that you have previously signed up for this product.<br/>So, no further action required from you at this time. Feel free to go 'back' <br/>and continue visiting the rest of our site.";
						//header( "Location:error.php?msg=" . urlencode($msg) );
						//exit;
					//} else {
						//Add to product and return
						//logToFile("About to add existing user to product, coupon_id=". $coupon_id); 
						//$uid = Dap_UsersProducts::addNewUserToProduct($email, $first_name, $last_name, $username, $product_id, $isPaid, "A", $coupon_id);
						//return $userDB;
					//}
				}
				
			}
			
			/**
				If it comes here, then it is a form without even a username field.
				Now, it could be...
				1) Existing user trying to sign up for new product
				2) Totally new user where both email and username are not taken and are still available
			*/
			
			//logToFile("Here in dSS"); 
			
			//First check if Existing user trying to sign up for new product
			if( ($username == "") && (Dap_User::isInUse("email",$user->getEmail())) ) {
				//logToFile("username is blank and email " . $user->getEmail() . " is in use"); 
				$userDB = Dap_User::loadUserByEmail($user->getEmail());
				if( isset($userDB) && ($userDB != null) ) {
					$uid = Dap_UsersProducts::addNewUserToProduct($email, $first_name, $last_name, $user_name, $product_id, $isPaid, "A", $coupon_id);
					return $userDB;
					
					/**
					if ( $userDB->hasEverHadAccessTo($product_id) ) {
						//logToFile("User already has access to product, so just returning"); 
						$msg = "MSG_ALREADY_SIGNEDUP";
						//$msg = "Sorry, it appears that you have previously signed up for this product.<br/>So, no further action required from you at this time. Feel free to go 'back' <br/>and continue visiting the rest of our site.";
						header( "Location:error.php?msg=" . urlencode($msg) );
						exit;
					} else {
						//Add to product and return
						//logToFile("About to add existing user to product, coupon_id=". $coupon_id); 
						$uid = Dap_UsersProducts::addNewUserToProduct($email, $first_name, $last_name, $user_name, $product_id, $isPaid, "A", $coupon_id);
						return $userDB;
					}
					*/
				}
			}
			
			
			
			//Totally new user			
			//First add user to product
						
			$first_name = ($user->getFirst_name() != "") ? $user->getFirst_name() : '';
			$last_name = ($user->getLast_name() != "") ? $user->getLast_name() : '';
			$user_name = ($user->getUser_name() != "") ? $user->getUser_name() : NULL;
			$password = ($user->getPassword() != "") ? $user->getPassword() : "";
			
			if (!isset($user_name) || ($user_name == "")) {
				$uname=generateUsername("Dap_UsersProducts.class.php.directSignupSubmit()",$user->getEmail(),$user->getFirst_name(),$user->getLast_name());
				$user->setUser_name($uname);
				logToFile("Dap_UsersProducts.class.php: directSignupSubmit(): username set to ".$uname,LOG_INFO_DAP);
				//$user->setUser_name(NULL);
			}
			
			$email = ($user->getEmail() != "") ? $user->getEmail() : '';
			
			//logToFile("About to add new user to product, coupon_id=". $coupon_id); 
			
			$userNew=NULL;
			$product = Dap_Product::loadProduct($product_id);
			if( !isset($product) || ($product == NULL) ) return $userNew;
			
			$userNew = new Dap_User();
			
			$userNew->setEmail($email);
			$userNew->setFirst_name($first_name);
			$userNew->setLast_name($last_name);

			if ($username != "")
				$userNew->setUser_name($user_name);
			else {
				//$user->setUser_name(NULL);
				$uname=generateUsername("Dap_UsersProducts.class.php.directSignupSubmit()",$email,$first_name,$last_name);
				if($uname=="") {
					$uname=NULL;
					logToFile("Dap_UsersProducts.class.php: directSignupSubmit(): username set to NULL",LOG_INFO_DAP);
				}
				$userNew->setUser_name($uname);
				logToFile("Dap_UsersProducts.class.php: directSignupSubmit(): username set to ".$uname,LOG_INFO_DAP);
			}								  
			//Figure out what should be user's status
			if( ($product->getDouble_optin_subject() == "") && ($product->getDouble_optin_body() == "") ) {
				//This is single optin, so take what caller sent as $activeStatus
				$userStatus = (strtolower($activeStatus) == "u") ? "U" : "A";
			} else {
				//Double optin. So ignore what caller sent in as $activeStatus and set user status to "U"
				$userStatus = "U";
			}
			$userNew->setStatus($userStatus);
			logToFile("Dap_UsersProducts.addNewUserToProduct create new user:  username=". $username);
			
			$address1 = $user->getAddress1();
			$address2 = $user->getAddress2();
			$city = $user->getCity();
			$state = $user->getState();
			$zip = $user->getZip();
			$country = $user->getCountry();
			$phone = $user->getPhone();
			$fax = $user->getFax();
			$company = $user->getCompany();
			$title = $user->getTitle();
			$paypal_email = $user->getPaypal_email();			
			
			//$userNew->setPassword($password);
			//logToFile("user_name is ". $user_name); 
			//logToFile("directsignupsubmit(): phone is ". $phone); 
			
			$userNew->setFirst_name($first_name );
			$userNew->setLast_name($last_name);
			$userNew->setEmail($email );
			if($password != "") $userNew->setPassword($password);
			$userNew->setAddress1($address1);
			$userNew->setAddress2($address2);
			$userNew->setCity($city);
			$userNew->setState($state);
			$userNew->setZip($zip);
			$userNew->setCountry($country);
			$userNew->setPhone($phone);
			$userNew->setFax($fax);
			$userNew->setCompany($company);
			$userNew->setTitle($title);
			$userNew->setPaypal_email($paypal_email);
			
			$uid = $userNew->create();
			  // check if custom field present
			foreach($_REQUEST as $key=>$value) {
				logToFile("Dap_UsersProducts.class.php: key=" . $key . " value=" . $value, LOG_DEBUG_DAP);		
					
				if (strstr($key, "custom_")) {	
					if ($keyval = substr($key, 7)) {
						$customFld = Dap_CustomFields::loadCustomfieldsByName($keyval);
						logToFile("Dap_UsersProducts.class.php: loadCustomfieldsByName(): keyval=" . $keyval, LOG_DEBUG_DAP);		
						
						if ($customFld) {
							$id = $customFld->getId();
							logToFile("Dap_UsersProducts.class.php: customFld Id = " . $id, LOG_DEBUG_DAP);		
							
							$usercustom = new Dap_UserCustomFields();
							$usercustom->setUser_id($uid);
							$usercustom->setCustom_id($id);
							$usercustom->setCustom_value($value);
							
							$cf = Dap_UserCustomFields::loadUserCustomFieldsByCustomFieldId($id, $uid);
							if ($cf) {
								logToFile("Dap_UsersProducts.class.php: call update() to update value=" . $value, LOG_DEBUG_DAP);
								$usercustom->update();
							}
							else {
								logToFile("Dap_UsersProducts.class.php: call create() to add custom value=" . $nv[1], LOG_DEBUG_DAP);
								$usercustom->create();
							}
						}
					}
				}
			}
			
			
			
			$uid = Dap_UsersProducts::addNewUserToProduct($email, $first_name, $last_name, $user_name, $product_id, $isPaid, "A", $coupon_id, $password);
			return $userNew;
		} catch (PDOException $e) {
			//$dap_dbh->rollback();
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			//$dap_dbh->rollback();
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}

	
	
	public static function getLoggedInURL($user="") {
		global $_POST;
		$session = Dap_Session::getSession();
		//if($session->isAdmin()) {
			//return "/dap/admin/";
			//exit;
		//} else {
			//logToFile("POST['request']: " . $_POST['request']);
		
		//Starting DAP v4.5, admin logging in via DAP login form
		//will no longer be redirected to DAP admin
		$redirectURL = Dap_Config::get("LOGGED_IN_URL");
		if( (!isset($user)) || ($user=="") )
			$user = $session->getUser();			
		
		/* If renewal redirect is enabled, then it gets highest priority */
		//If user has exactly 1 expired product, then redirect to product-specific renewal redirect URL
		//If > 1 expired products, then redirect to GLOBAL renewal redirect URL
		$redirectToRenewalURL = (Dap_Config::get("REDIR_TO_RENEW_URL") == "Y") ? true : false;
		if($redirectToRenewalURL) {
			logToFile("redirectToRenewalURL is true"); 
			$productCountExpired = $user->hasAccessToHowManyExpiredProducts();
			logToFile("productCountExpired: " . $productCountExpired); 
			if($productCountExpired != 0) {
				logToFile("has expired products");
				if( $productCountExpired == 1 ) {
					$productId = Dap_UsersProducts::loadSingleExpiredProduct($user->getId());
					$product = Dap_Product::loadProduct( $productId );
					$redirectURL = ($product->getRenewal_redirect_url() != "") ? $product->getRenewal_redirect_url() : $redirectURL;
				} else if($productCountExpired > 1) {
					logToFile("productCountExpired > 1"); 	
					$redirectURL = (Dap_Config::get("RENEWAL_REDIR_URL") != "") ? Dap_Config::get("RENEWAL_REDIR_URL") : $redirectURL;
				}
				return $redirectURL;
			}
		}
		
		//If it doesn't match any of the above, then no renewal redirection required
		//logToFile("No redirection done, redirectURL: $redirectURL"); 
		//If has access to exactly 1 product, then redirect to product-specific landing page
		//If =0 or >1, then redirect site wide LOGGED_IN_URL
		$productCount = $user->hasAccessToHowManyDistinctProducts();
		
		//logToFile("productCount: $productCount");
		if( $productCount == 1 ) {
			$userProducts = Dap_UsersProducts::loadProducts($user->getId());
			foreach ($userProducts as $userProduct) {
				$productId = $userProduct->getProduct_id();
				//logToFile("productId: $productId"); 
				$product = Dap_Product::loadProduct( $productId );
				if ($product->getLogged_in_url() != "") {
					$redirectURL = $product->getLogged_in_url();
					//logToFile("redirectURL: $redirectURL");
				} 
				break;
			}
		}
		
		return $redirectURL;
	}
	
	
	public function loadSingleExpiredProduct($userId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$productId = 0;
			
			//Return product_id of expired product
			$sql = "select
						product_id
					from
						dap_users_products_jn
					where
						user_id = :userId and
						CURDATE() > access_end_date";
						
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->execute();
			
			if ($row = $stmt->fetch()) {
				$productId = $row["product_id"];
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $productId;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}	
	
	
	/** Returns string */
	public static function toggleProductStatus($userId, $productId, $productStatusCurrent) {
		try {
			logToFile("toggleProductStatus, userId: $userId"); 
			$dap_dbh = Dap_Connection::getConnection();
			$productStatusNew = "";
			$sql = "";
			$stmt = null;
			
			if($productStatusCurrent == "A") $productStatusNew = "I";
			if($productStatusCurrent == "I") $productStatusNew = "A";

			$sql = "update
						dap_users_products_jn
					set
						status = :productStatusNew
					where
						user_id = :userId and
						product_id = :productId
					";
			//logToFile("sql: $sql"); 
			//logToFile("userStatus: $userStatus , newUserStatus: $newUserStatus"); 
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->bindParam(':productStatusNew', $productStatusNew, PDO::PARAM_STR);
			$stmt->execute();
			
			$stmt = null;
			$dap_dbh = null;
			
			return "User/Product status successfully updated";
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			return $e->getMessage();
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			return $e->getMessage();
		}
	}	
	
	
	public static function resendEmail($userId, $productId, $actionType) {
		$product = Dap_Product::loadProduct($productId);
		$user = Dap_User::loadUserById($userId);
		$response = "";
		
		if( ($actionType == "DO") && ($product->getDouble_optin_subject() != "") && ($product->getDouble_optin_body() != "") ) {
			$result=sendUserProductActivationEmail($user, $productId);
			if($result!="")return $result;
			$response = "Double-Optin email sent successfully!";
		} else if ($actionType == "WE") {
			$result=sendUserProductWelcomeEmail($userId, $productId);
			if($result!="")return $result;
			$response = "Welcome email sent successfully!";
		}
		
		return $response;
	}
	
	
	public function isAccessCurrent($userId, $productId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$current = false;
			
			$sql = "select
						*
					from
						dap_users_products_jn
					where
						user_id = :userId and
						product_id = :productId and
						CURDATE() > access_end_date";
						
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->execute();
			
			if ($row = $stmt->fetch()) {
				//Access is current
				$current = true;
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $current;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}
	
	
	//Check to see if given "date" is less than user's access end date 
	//for given product(s) (one or more separated by commas)
	//Used primarily by DAP shortcode
	public function isContentDateValid($userId, $productIds, $date) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$current = false;
			
			//logToFile("isContentDateValid: $userId, $productIds, $date"); 
			
			$sql = "select
						*
					from
						dap_users_products_jn
					where
						user_id = :userId and
						product_id in (" . $productIds . ") and
						'".$date."' <= access_end_date";
						
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			//$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->execute();
			
			if ($row = $stmt->fetch()) {
				//Access is current
				//logToFile("found row"); 
				$current = true;
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			//$currentVal = $current ? 'true' : 'false';
			//logToFile("returning $currentVal");
			return $current;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}
	
	
	public function isUserEligible($userId, $productId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$current = false;
			
			//logToFile("isContentDateValid: $userId, $productIds, $date"); 

			$sql = "select
						u.id,
						u.first_name,
						u.last_name,
						u.email,
						u.password
					from
						dap_users_products_jn upj,
						dap_users u
					where
						upj.product_id = :productId and
						upj.status =  'A' and
						u.id = upj.user_id and
						u.status = 'A' and 
						u.opted_out = 'N' and 
						u.id = :userId and
						CURDATE() <= upj.access_end_date";
						
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->execute();
			
			if ($row = $stmt->fetch()) {
				//Access is current
				logToFile("DAP_USersProducts.php: isUserEligible: found row"); 
				return true;
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			//$currentVal = $current ? 'true' : 'false';
			//logToFile("returning $currentVal");

		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
		
		
		return false;
	}
	
}
?>