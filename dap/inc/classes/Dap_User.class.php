<?php
	
class Dap_User extends Dap_Base {

   	var $id;
   	var $first_name;
   	var $last_name;
   	var $user_name;
   	var $email;
   	var $password;
   	var $address1;
   	var $address2;
   	var $city;
   	var $state;
   	var $zip;
   	var $country;
   	var $phone;
   	var $fax;
   	var $company;
   	var $title;
   	var $login_count;
   	var $is_affiliate;
   	var $last_login_date;
   	var $activation_key;
   	var $status = "U";
   	var $ipaddress;
   	var $account_type = "U";
   	var $signup_date;
   	var $paypal_email;
   	var $last_update_date;
   	var $aff_nick;
   	var $opted_out;
	var $self_service_status = "I";
	var $credits_available;
//	var $credits_earned;
	var $exclude_iplogging = "N";


	//Additional fields
	var $product_id;
	var $product_name;

	var $user_custom_fields;
	
	public function __construct() {
        $this->user_custom_fields = array();
    }
	
	function getId() {
		return $this->id;
	}
	function setId($o) {
		$this->id = $o;
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
	
	function getUser_name() {
		return $this->user_name;
	}
	function setUser_name($o) {
		$this->user_name = $o;
	}	

	function getEmail() {
		return $this->email;
	}
	function setEmail($o) {
		$this->email = $o;
	}

	function getPassword() {
		return $this->password;
	}
	function setPassword($o) {
		$this->password = $o;
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

	function getCompany() {
		return $this->company;
	}
	function setCompany($o) {
		$this->company = $o;
	}
	
	function getTitle() {
		return $this->title;
	}
	function setTitle($o) {
		$this->title = $o;
	}
	
	function getLogin_count() {
		return $this->login_count;
	}
	function setLogin_count($o) {
		$this->login_count = $o;
	}

	function getIs_affiliate() {
		return $this->is_affiliate;
	}
	function setIs_affiliate($o) {
		$this->is_affiliate = $o;
	}

	function getLast_login_date() {
		return $this->last_login_date;
	}
	function setLast_login_date($o) {
		$this->last_login_date = $o;
	}

	function getActivation_key() {
		return $this->activation_key;
	}
	function setActivation_key($o) {
		$this->activation_key = $o;
	}

	function getStatus() {
		return $this->status;
	}
	function setStatus($o) {
		$this->status = $o;
	}

	function getIpaddress() {
		return $this->ipaddress;
	}
	function setIpaddress($o) {
		$this->ipaddress = $o;
	}

	function getAccount_type() {
		return $this->account_type;
	}
	function setAccount_type($o) {
		$this->account_type = $o;
	}

	function getSignup_date() {
		return $this->signup_date;
	}
	function setSignup_date($o) {
		$this->signup_date = $o;
	}

	function getPaypal_email() {
		return $this->paypal_email;
	}
	function setPaypal_email($o) {
		$this->paypal_email = $o;
	}

	function getLast_update_date() {
		return $this->last_update_date;
	}
	function setLast_update_date($o) {
		$this->last_update_date = $o;
	}
	
	function getAff_nick() {
		return $this->aff_nick;
	}
	function setAff_nick($o) {
		$this->aff_nick = $o;
	}

	function getOpted_out() {
		return $this->opted_out;
	}
	function setOpted_out($o) {
		$this->opted_out = $o;
	}
	
	function getSelf_service_status() {
		return $this->self_service_status;
	}
	function setSelf_service_status($o) {
		$this->self_service_status = $o;
	}

	
	function getCredits_available() {
		return $this->credits_available;
	}
	function setCredits_available($o) {
		$this->credits_available = $o;
	}
/*	function getCredits_earned() {
		return $this->credits_earned;
	}
	function setCredits_earned($o) {
		$this->credits_earned = $o;
	}*/

	function getExclude_iplogging() {
		return $this->exclude_iplogging;
	}
	
	function setExclude_iplogging($o) {
		$this->exclude_iplogging = $o;
	}

	
	//Additional functions for storing Product-related info
	function getProduct_id() {
		return $this->product_id;
	}
	function setProduct_id($o) {
		$this->product_id = $o;
	}

	function getProduct_name() {
		return $this->product_name;
	}
	function setProduct_name($o) {
		$this->product_name = $o;
	}
	
	//custom field
	function getUser_custom_fields() {
		return $this->user_custom_fields;
	}
	
	function setUser_custom_fields($o) {
		$this->user_custom_fields = $o;
	}



	
	public static function loadUserCustomFields($userId) {
		$userCustomFields = Dap_UserCustomFields::loadUserCustomFields($userId);
		return $userCustomFields;
	}
	
	public static function loadUserByEmail($email) {
		$user = NULL;
		try {
			$dap_dbh = Dap_Connection::getConnection();
			//echo $_SESSION['dap_user_email'];
			//var $user;
			
			//Loads current user data into the $User object
			//lets first check if we have session data for this user.
			logToFile("loadUserByEmail: email=".$email,LOG_DEBUG_DAP);
			if(!isset($email)) {
				logToFile("loadUserByEmail: could not find user",LOG_DEBUG_DAP);
				return $user;
			}
			$sql = "SELECT 
						*
					FROM
						dap_users
					WHERE
						email =:email
					";
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':email', $email, PDO::PARAM_STR);
			$stmt->execute();
			//echo "Error Code:".$stmt->errorCode().", Number of rows:".$stmt->fetchColumn();
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				//logToFile("found user",LOG_DEBUG_DAP);
				
				//echo "Error Code:".$stmt->errorCode().", Number of rows:".$stmt->rowCount();
				$user = new Dap_User();
				$user->setId( stripslashes($row["id"]) );
				$user->setFirst_name( stripslashes($row["first_name"]) );
				$user->setLast_name( stripslashes($row["last_name"]) );
				$user->setUser_name( stripslashes($row["user_name"]) );
				$user->setEmail( stripslashes($row["email"]) );
				$user->setPassword( decryptPassword(stripslashes($row["password"])) );
				$user->setAddress1( stripslashes($row["address1"]) );
				$user->setAddress2( stripslashes($row["address2"]) );
				$user->setCity( stripslashes($row["city"]) );
				$user->setState( stripslashes($row["state"]) );
				$user->setZip( stripslashes($row["zip"]) );
				$user->setCountry( stripslashes($row["country"]) );
				$user->setPhone( stripslashes($row["phone"]) );
				$user->setFax( stripslashes($row["fax"]) );
				$user->setCompany( stripslashes($row["company"]) );
				$user->setTitle( stripslashes($row["title"]) );
				$user->setLogin_count( stripslashes($row["login_count"]) );
				$user->setIs_affiliate( stripslashes($row["is_affiliate"]) );
				$user->setLast_login_date( stripslashes($row["last_login_date"]) );
				$user->setActivation_key( stripslashes($row["activation_key"]) );
				$user->setStatus( stripslashes($row["status"]) );
				$user->setIpaddress( stripslashes($row["ipaddress"]) );
				$user->setAccount_type( stripslashes($row["account_type"]) );
				$user->setSignup_date( stripslashes($row["signup_date"]) );
				$user->setPaypal_email( stripslashes($row["paypal_email"]) );
				$user->setLast_update_date( stripslashes($row["last_update_date"]) );		
				$user->setSelf_service_status( stripslashes($row["self_service_status"]) );		
				$user->setCredits_available( stripslashes($row["credits_available"]) );	
			//	$user->setCredits_earned( stripslashes($row["credits_earned"]) );	
				$user->setExclude_iplogging( stripslashes($row["exclude_iplogging"]) );
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $user;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	
	public static function loadByUsername($username) {
		$user = NULL;
		try {
			$dap_dbh = Dap_Connection::getConnection();
			//echo $_SESSION['dap_user_email'];
			//var $user;
			
			//Loads current user data into the $User object
			//lets first check if we have session data for this user.
			logToFile("loadByUsername: username=".$username,LOG_DEBUG_DAP);
			if($username=="") {
				logToFile("loadByUsername: could not find user",LOG_DEBUG_DAP);
				return $user;
			}
			$sql = "SELECT 
						*
					FROM
						dap_users
					WHERE
						user_name =:username
					";
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':username', $username, PDO::PARAM_STR);
			$stmt->execute();
			//echo "Error Code:".$stmt->errorCode().", Number of rows:".$stmt->fetchColumn();
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				//logToFile("found user",LOG_DEBUG_DAP);
				
				//echo "Error Code:".$stmt->errorCode().", Number of rows:".$stmt->rowCount();
				$user = new Dap_User();
				$user->setId( stripslashes($row["id"]) );
				$user->setFirst_name( stripslashes($row["first_name"]) );
				$user->setLast_name( stripslashes($row["last_name"]) );
				$user->setUser_name( stripslashes($row["user_name"]) );
				$user->setEmail( stripslashes($row["email"]) );
				$user->setPassword( decryptPassword(stripslashes($row["password"])) );
				$user->setAddress1( stripslashes($row["address1"]) );
				$user->setAddress2( stripslashes($row["address2"]) );
				$user->setCity( stripslashes($row["city"]) );
				$user->setState( stripslashes($row["state"]) );
				$user->setZip( stripslashes($row["zip"]) );
				$user->setCountry( stripslashes($row["country"]) );
				$user->setPhone( stripslashes($row["phone"]) );
				$user->setFax( stripslashes($row["fax"]) );
				$user->setCompany( stripslashes($row["company"]) );
				$user->setTitle( stripslashes($row["title"]) );
				$user->setLogin_count( stripslashes($row["login_count"]) );
				$user->setIs_affiliate( stripslashes($row["is_affiliate"]) );
				$user->setLast_login_date( stripslashes($row["last_login_date"]) );
				$user->setActivation_key( stripslashes($row["activation_key"]) );
				$user->setStatus( stripslashes($row["status"]) );
				$user->setIpaddress( stripslashes($row["ipaddress"]) );
				$user->setAccount_type( stripslashes($row["account_type"]) );
				$user->setSignup_date( stripslashes($row["signup_date"]) );
				$user->setPaypal_email( stripslashes($row["paypal_email"]) );
				$user->setLast_update_date( stripslashes($row["last_update_date"]) );		
				$user->setSelf_service_status( stripslashes($row["self_service_status"]) );		
				$user->setCredits_available( stripslashes($row["credits_available"]) );	
			//	$user->setCredits_earned( stripslashes($row["credits_earned"]) );	
				$user->setExclude_iplogging( stripslashes($row["exclude_iplogging"]) );
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $user;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	public static function loadUserByPaypalEmail($email) {
		$user = NULL;
		try {
			$dap_dbh = Dap_Connection::getConnection();
			//echo $_SESSION['dap_user_email'];
			//var $user;
			
			//Loads current user data into the $User object
			//lets first check if we have session data for this user.
			if(!isset($email)) {
				return $user;
			}
			$sql = "SELECT 
						*
					FROM
						dap_users
					WHERE
						paypal_email =:email
					";
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':email', $email, PDO::PARAM_STR);
			$stmt->execute();
			//echo "Error Code:".$stmt->errorCode().", Number of rows:".$stmt->fetchColumn();
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				//logToFile("found user",LOG_DEBUG_DAP);
				
				//echo "Error Code:".$stmt->errorCode().", Number of rows:".$stmt->rowCount();
				$user = new Dap_User();
				$user->setId( $row["id"] );
				$user->setFirst_name( $row["first_name"] );
				$user->setLast_name( $row["last_name"] );
				$user->setUser_name( $row["user_name"] );
				$user->setEmail( $row["email"] );
				$user->setPassword( decryptPassword($row["password"]) );
				$user->setAddress1( $row["address1"] );
				$user->setAddress2( $row["address2"] );
				$user->setCity( $row["city"] );
				$user->setState( $row["state"] );
				$user->setZip( $row["zip"] );
				$user->setCountry( $row["country"] );
				$user->setPhone( $row["phone"] );
				$user->setFax( $row["fax"] );
				$user->setCompany( $row["company"] );
				$user->setTitle( $row["title"] );
				$user->setLogin_count( $row["login_count"] );
				$user->setIs_affiliate( $row["is_affiliate"] );
				$user->setLast_login_date( $row["last_login_date"] );
				$user->setActivation_key( $row["activation_key"] );
				$user->setStatus( $row["status"] );
				$user->setIpaddress( $row["ipaddress"] );
				$user->setAccount_type( $row["account_type"] );
				$user->setSignup_date( $row["signup_date"] );
				$user->setPaypal_email( $row["paypal_email"] );
				$user->setLast_update_date( $row["last_update_date"] );		
				$user->setSelf_service_status( $row["self_service_status"] );		
				$user->setCredits_available( $row["credits_available"] );	
				$user->setExclude_iplogging( $row["exclude_iplogging"] );
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $user;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	public static function loadUserByCMCCAcctNum($cmcc_acctnum) {
		$user = NULL;
		try {
			$dap_dbh = Dap_Connection::getConnection();
			//echo $_SESSION['dap_user_email'];
			//var $user;
			
			//Loads current user data into the $User object
			//lets first check if we have session data for this user.
			//logToFile("before executing sql " + $cmcc_acctnum,LOG_DEBUG_DAP);
			
			if(!isset($cmcc_acctnum)) {
				return $user;
			}
			$sql = "SELECT 
						*
					FROM
						dap_users
					WHERE
						acct_num =:cmcc_acctnum";
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':cmcc_acctnum', $cmcc_acctnum, PDO::PARAM_STR);
			$stmt->execute();
			//echo "Error Code:".$stmt->errorCode().", Number of rows:".$stmt->fetchColumn();
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				//logToFile("found user",LOG_DEBUG_DAP);
				
				//echo "Error Code:".$stmt->errorCode().", Number of rows:".$stmt->rowCount();
				$user = new Dap_User();
				$user->setId( $row["id"] );
				$user->setAcct_num( $row["acct_num"] );
				$user->setFirst_name( $row["first_name"] );
				$user->setLast_name( $row["last_name"] );
				$user->setUser_name( $row["user_name"] );
				$user->setEmail( $row["email"] );
				$user->setPassword( decryptPassword($row["password"]) );
				$user->setPayment_gateway( $row["payment_gateway"] );
				$user->setApi_login_id( $row["api_login_id"] );
				$user->setTrans_key( $row["trans_key"] );
				$user->setAddress1( $row["address1"] );
				$user->setAddress2( $row["address2"] );
				$user->setCity( $row["city"] );
				$user->setState( $row["state"] );
				$user->setZip( $row["zip"] );
				$user->setCountry( $row["country"] );
				$user->setPhone( $row["phone"] );
				$user->setFax( $row["fax"] );
				$user->setCompany( $row["company"] );
				$user->setTitle( $row["title"] );
				$user->setLogin_count( $row["login_count"] );
				$user->setIs_affiliate( $row["is_affiliate"] );
				$user->setLast_login_date( $row["last_login_date"] );
				$user->setActivation_key( $row["activation_key"] );
				$user->setStatus( $row["status"] );
				$user->setIpaddress( $row["ipaddress"] );
				$user->setAccount_type( $row["account_type"] );
				$user->setSignup_date( $row["signup_date"] );
				$user->setPaypal_email( $row["paypal_email"] );
				$user->setLast_update_date( $row["last_update_date"] );		
				$user->setSelf_service_status( $row["self_service_status"] );		
				$user->setCredits_available( $row["credits_available"] );	
				$user->setExclude_iplogging( $row["exclude_iplogging"] );
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $user;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	public static function authenticateUser($email, $password) {
		//PASSWORD ENCRYPTED RIGHT AT THE BEGINNING
		//logToFile("------------------------------ authenticateUser"); 
		//logToFile("Incoming: email: $email , password: $password"); 
		$dap_dbh = Dap_Connection::getConnection();
		$encryptedPassword = encryptPassword($password);
		//echo $_SESSION['dap_user_email'];
		//var $user;
		$user = NULL;
		//Loads current user data into the $User object
		//lets first check if we have session data for this user.
		if(!isset($email)) {
			return $user;
		}
		$sql = "SELECT 
					*
				FROM
					dap_users
				WHERE
					email =:email and
				    status = 'A' and
				    password = :password
				";
		$stmt = $dap_dbh->prepare($sql);
		$stmt->bindParam(':email', $email, PDO::PARAM_STR);
		$stmt->bindParam(':password', $password, PDO::PARAM_STR);
		$stmt->execute();
		
		$usingPlaintextPassword = false;

		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			//logToFile("Checking against Plaintext Password"); 
			$usingPlaintextPassword = true;
			$user = new Dap_User();
			$user->setId( stripslashes($row["id"]) );
			$user->setFirst_name( stripslashes($row["first_name"]) );
			$user->setLast_name( stripslashes($row["last_name"]) );
			$user->setUser_name( stripslashes($row["user_name"]) );
			$user->setEmail( stripslashes($row["email"]) );
			$user->setAddress1( stripslashes($row["address1"]) );
			$user->setAddress2( stripslashes($row["address2"]) );
			$user->setCity( stripslashes($row["city"]) );
			$user->setState( stripslashes($row["state"]) );
			$user->setZip( stripslashes($row["zip"]) );
			$user->setCountry( stripslashes($row["country"]) );
			$user->setPhone( stripslashes($row["phone"]) );
			$user->setFax( stripslashes($row["fax"]) );
			$user->setCompany( stripslashes($row["company"]) );
			$user->setTitle( stripslashes($row["title"]) );
			$user->setLogin_count( stripslashes($row["login_count"]) );
			$user->setIs_affiliate( stripslashes($row["is_affiliate"]) );
			$user->setLast_login_date( stripslashes($row["last_login_date"]) );
			$user->setActivation_key( stripslashes($row["activation_key"]) );
			$user->setStatus( stripslashes($row["status"]) );
			$user->setIpaddress( stripslashes($row["ipaddress"]) );
			$user->setAccount_type( stripslashes($row["account_type"]) );
			$user->setSignup_date( stripslashes($row["signup_date"]) );
			$user->setPaypal_email( stripslashes($row["paypal_email"]) );
			$user->setLast_update_date( stripslashes($row["last_update_date"]) );	
			$user->setSelf_service_status( stripslashes($row["self_service_status"]) );		
			$user->setCredits_available( stripslashes($row["credits_available"]) );	
			$user->setExclude_iplogging( stripslashes($row["exclude_iplogging"]) );
		}
		
		if($usingPlaintextPassword === false) { 
			//Might already be using encrypted password
			//So compared with encrypted version
			//logToFile("Checking against Encrypted Password"); 
	
			$sql = "SELECT 
						*
					FROM
						dap_users
					WHERE
						email =:email and
						status = 'A' and
						password = :encryptedPassword
					";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':email', $email, PDO::PARAM_STR);
			$stmt->bindParam(':encryptedPassword', $encryptedPassword, PDO::PARAM_STR);
			$stmt->execute();
			
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				//logToFile("User already has encrypted password in db"); 
				$user = new Dap_User();
				$user->setId( stripslashes($row["id"]) );
				$user->setFirst_name( stripslashes($row["first_name"]) );
				$user->setLast_name( stripslashes($row["last_name"]) );
				$user->setUser_name( stripslashes($row["user_name"]) );
				$user->setEmail( stripslashes($row["email"]) );
				$user->setAddress1( stripslashes($row["address1"]) );
				$user->setAddress2( stripslashes($row["address2"]) );
				$user->setCity( stripslashes($row["city"]) );
				$user->setState( stripslashes($row["state"]) );
				$user->setZip( stripslashes($row["zip"]) );
				$user->setCountry( stripslashes($row["country"]) );
				$user->setPhone( stripslashes($row["phone"]) );
				$user->setFax( stripslashes($row["fax"]) );
				$user->setCompany( stripslashes($row["company"]) );
				$user->setTitle( stripslashes($row["title"]) );
				$user->setLogin_count( stripslashes($row["login_count"]) );
				$user->setIs_affiliate( stripslashes($row["is_affiliate"]) );
				$user->setLast_login_date( stripslashes($row["last_login_date"]) );
				$user->setActivation_key( stripslashes($row["activation_key"]) );
				$user->setStatus( stripslashes($row["status"]) );
				$user->setIpaddress( stripslashes($row["ipaddress"]) );
				$user->setAccount_type( stripslashes($row["account_type"]) );
				$user->setSignup_date( stripslashes($row["signup_date"]) );
				$user->setPaypal_email( stripslashes($row["paypal_email"]) );
				$user->setLast_update_date( stripslashes($row["last_update_date"]) );	
				$user->setSelf_service_status( stripslashes($row["self_service_status"]) );		
				$user->setCredits_available( stripslashes($row["credits_available"]) );	
				$user->setExclude_iplogging( stripslashes($row["exclude_iplogging"]) );
			}
		} //end if($usingRegularPassword === false)
		
		$sql = "";
		$stmt = null;
		$ipaddress = getIpOfUser();

		if( isset($user) && !is_null($user) ) { //do this only if user found
			if ($usingPlaintextPassword) {
				//logToFile("About to update password - this is because of plaintext password in db");
				//update password too
				//this should happen only once during password transition
				$sql = "update
							dap_users
						set
							login_count = (login_count + 1),
							ipaddress = :ipaddress,
							last_login_date = CURDATE(),
							last_update_date = CURDATE(),
							password = :encryptedPassword
						WHERE
							email =:email and
							password = :password
						";
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':email', $email, PDO::PARAM_STR);
				$stmt->bindParam(':password', $password, PDO::PARAM_STR);
				$stmt->bindParam(':encryptedPassword', $encryptedPassword, PDO::PARAM_STR);
				$stmt->bindParam(':ipaddress', $ipaddress, PDO::PARAM_STR);
			} else {
				//update just other stuff
				$sql = "update
							dap_users
						set
							login_count = (login_count + 1),
							ipaddress = :ipaddress,
							last_login_date = CURDATE(),
							last_update_date = CURDATE()
						WHERE
							email =:email and
							password = :encryptedPassword
						";
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':email', $email, PDO::PARAM_STR);
				$stmt->bindParam(':encryptedPassword', $encryptedPassword, PDO::PARAM_STR);
				$stmt->bindParam(':ipaddress', $ipaddress, PDO::PARAM_STR);
			}
			//Now execute the update
			$stmt->execute();	
		}

		$dap_dbh = null;
		$stmt = null;
		$sql = null;
		
		return $user;
	}
	
	public static function loadAdminUserByMinId() {
		//Returns password as is from db
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$user = NULL;
			
			$sql = "SELECT 
						min(id) as id, 
						email, 
						password
					FROM
						dap_users
					WHERE
						account_type = 'A' and
				    	status = 'A' 
					group by id
					limit 1";
						
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();
			
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$user = new Dap_User();
				$user->setId( $row["id"] );
				$user->setEmail( $row["email"] );
				$user->setPassword( decryptPassword(stripslashes($row["password"])) );
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $user;	
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	
	
	public static function loadUserById($id) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$user = NULL;
			if(!isset($id)) {
				return $user;
			}
			
			$sql = "SELECT 
						*
					FROM
						dap_users
					WHERE
						id =:id
					";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();

			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$user = new Dap_User();
				$user->setId( stripslashes($row["id"]) );
				$user->setFirst_name( stripslashes($row["first_name"]) );
				$user->setLast_name( stripslashes($row["last_name"]) );
				$user->setUser_name( stripslashes($row["user_name"]) );
				$user->setEmail( stripslashes($row["email"]) );
				$user->setPassword( decryptPassword(stripslashes($row["password"])) );
				$user->setAddress1( stripslashes($row["address1"]) );
				$user->setAddress2( stripslashes($row["address2"]) );
				$user->setCity( stripslashes($row["city"]) );
				$user->setState( stripslashes($row["state"]) );
				$user->setZip( stripslashes($row["zip"]) );
				$user->setCountry( stripslashes($row["country"]) );
				$user->setPhone( stripslashes($row["phone"]) );
				$user->setFax( stripslashes($row["fax"]) );
				$user->setCompany( stripslashes($row["company"]) );
				$user->setTitle( stripslashes($row["title"]) );
				$user->setLogin_count( stripslashes($row["login_count"]) );
				$user->setIs_affiliate( stripslashes($row["is_affiliate"]) );
				$user->setLast_login_date( stripslashes($row["last_login_date"]) );
				$user->setActivation_key( stripslashes($row["activation_key"]) );
				$user->setStatus( stripslashes($row["status"]) );
				$user->setIpaddress( stripslashes($row["ipaddress"]) );
				$user->setAccount_type( stripslashes($row["account_type"]) );
				$user->setSignup_date( stripslashes($row["signup_date"]) );
				$user->setPaypal_email( stripslashes($row["paypal_email"]) );
				$user->setLast_update_date( stripslashes($row["last_update_date"]) );		
				$user->setOpted_out( stripslashes($row["opted_out"]) );		
				$user->setSelf_service_status( stripslashes($row["self_service_status"]) );		
				$user->setCredits_available( stripslashes($row["credits_available"]) );	
			//	$user->setCredits_earned( stripslashes($row["credits_earned"]) );	
				$user->setExclude_iplogging( stripslashes($row["exclude_iplogging"]) );
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $user;	
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	
	
	//Load User BY Activation Key
	public static function loadUserByActivationKey($key) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$user = NULL;
			if(!isset($key)) {
				return $user;
			}
			
			$sql = "SELECT 
						*
					FROM
						dap_users
					WHERE
						activation_key =:key
					";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':key', $key, PDO::PARAM_INT);
			$stmt->execute();
			
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$user = new Dap_User();
				$user->setId( stripslashes($row["id"]) );
				$user->setFirst_name( stripslashes($row["first_name"]) );
				$user->setLast_name( stripslashes($row["last_name"]) );
				$user->setUser_name( stripslashes($row["user_name"]) );
				$user->setEmail( stripslashes($row["email"]) );
				$user->setPassword( decryptPassword(stripslashes($row["password"])) );
				$user->setAddress1( stripslashes($row["address1"]) );
				$user->setAddress2( stripslashes($row["address2"]) );
				$user->setCity( stripslashes($row["city"]) );
				$user->setState( stripslashes($row["state"]) );
				$user->setZip( stripslashes($row["zip"]) );
				$user->setCountry( stripslashes($row["country"]) );
				$user->setPhone( stripslashes($row["phone"]) );
				$user->setFax( stripslashes($row["fax"]) );
				$user->setCompany( stripslashes($row["company"]) );
				$user->setTitle( stripslashes($row["title"]) );
				$user->setLogin_count( stripslashes($row["login_count"]) );
				$user->setIs_affiliate( stripslashes($row["is_affiliate"]) );
				$user->setLast_login_date( stripslashes($row["last_login_date"]) );
				$user->setActivation_key( stripslashes($row["activation_key"]) );
				$user->setStatus( stripslashes($row["status"]) );
				$user->setIpaddress( stripslashes($row["ipaddress"]) );
				$user->setAccount_type( stripslashes($row["account_type"]) );
				$user->setSignup_date( stripslashes($row["signup_date"]) );
				$user->setPaypal_email( stripslashes($row["paypal_email"]) );
				$user->setLast_update_date( stripslashes($row["last_update_date"]) );		
				$user->setSelf_service_status( stripslashes($row["self_service_status"]) );		
				$user->setCredits_available( stripslashes($row["credits_available"]) );	
				$user->setExclude_iplogging( stripslashes($row["exclude_iplogging"]) );
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $user;	
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}	
	
	public static function loadUserArrayById($userId) {
		//Load resource details from database
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			//Since we need to Date_Format for the dates, select the columns individually instead of 'select *'
			$sql = "select
						id, first_name, last_name, user_name, email, password, address1, address2, 
						city, state, zip, country, phone, fax, company, title,
						is_affiliate, DATE_FORMAT(last_login_date, '%m-%d-%Y') as last_login_date, 
						activation_key, status, login_count, account_type, ipaddress,
						DATE_FORMAT(signup_date, '%m-%d-%Y %H:%i:%s') as signup_date, paypal_email, 
						DATE_FORMAT(last_update_date, '%m-%d-%Y') as last_update_date,
						aff_nick, opted_out, self_service_status, credits_available, exclude_iplogging
					from
						dap_users
					where
						id = :userId";
						
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->execute();

			$resourceArray = array();
			
			if ($obj = $stmt->fetch()) {
				//$obj["first_name"] = urlencode($obj["first_name"]);
				//logToFile("loadUserArrayById: " . $obj["first_name"]); 
				//$obj["first_name"] = mb_convert_encoding($row["first_name"], "ISO-8859-1", "UTF-8");
				//$obj["last_name"] = mb_convert_encoding($row["last_name"], "ISO-8859-1", "UTF-8");
				
				$password = $obj["password"];
				$decryptedPassword = @decryptPassword($password);
				
				$isValidEncryptedPassword = false;
				
				if( $password == encryptPassword(decryptPassword($password)) ) {
					$isValidEncryptedPassword = true;
					//logToFile("isValidEncryptedPassword = true"); 
				}
				
				//logToFile("user " . $userId . "'s in loadUserArrayById: password from db: $password, decrypted password: A".$decryptedPassword."B"); 
				$obj["password"] = ( 
										($isValidEncryptedPassword) &&
										isset($decryptedPassword) &&
										!is_null($decryptedPassword) &&
										(trim($decryptedPassword) != "")
									) 
									? $decryptedPassword : $password;
				
				//logToFile("What's now in obj[password]: " . $obj["password"]); 
				//logToFile("loadUserArrayById: " . $obj["first_name"]); 
				$resourceArray[] = $obj;
			}
			
			$resourceArray[] = Dap_UserCustomFields::loadUserCustomFields($userId);
			$resourceArray = removeSlashesDAP($resourceArray);
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $resourceArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	/*
		automatically create password and activation key
	*/
	/**
	 * Create user record - automatically create password and activation key.
	 *
	 * @return user id of created user.
	 */
	public function create() {
		try {
			logToFile("(Dap_User.create()) New User Create. FirstName:".$this->getFirst_name());
			//generate password if its not set already
			if(!isset($this->password)) {
				$this->password = Dap_User::GeneratePassword(5);
			}
			//$this->activation_key = mktime(); //md5(uniqid(rand(), true)); //Dap_User::generatePassword(10);
			$this->activation_key = uniqid();
			
			//Check if caller has already set the status - if not, mark user as Unconfirmed
			$status = $this->getStatus();
			if( !isset($status) || ($status == "") ) {
				$this->setStatus("U");
			}

			$dap_dbh = Dap_Connection::getConnection();

			$sql = "insert into dap_users 
						(first_name, last_name, user_name, email, password, address1, address2, city, state, zip, country, phone, fax, company, title, status, account_type, paypal_email, activation_key, signup_date, last_update_date)
					values 
						(:first_name, :last_name, :user_name, :email, :password, :address1, :address2, :city, :state, :zip, :country, :phone, :fax, :company, :title, :status, :account_type, :paypal_email, :activation_key, now(), now())";

			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':first_name', $this->getFirst_name(), PDO::PARAM_STR);
			$stmt->bindParam(':last_name', $this->getLast_name(), PDO::PARAM_STR);
			$stmt->bindParam(':user_name', $this->getUser_name(), PDO::PARAM_STR);
			$stmt->bindParam(':email', $this->getEmail(), PDO::PARAM_STR);
			$stmt->bindParam(':password', encryptPassword($this->getPassword()), PDO::PARAM_STR);
			$stmt->bindParam(':address1', $this->getAddress1(), PDO::PARAM_STR);
			$stmt->bindParam(':address2', $this->getAddress2(), PDO::PARAM_STR);
			$stmt->bindParam(':city', $this->getCity(), PDO::PARAM_STR);
			$stmt->bindParam(':state', $this->getState(), PDO::PARAM_STR);
			$stmt->bindParam(':zip', $this->getZip(), PDO::PARAM_STR);
			$stmt->bindParam(':country', $this->getCountry(), PDO::PARAM_STR);
			$stmt->bindParam(':phone', $this->getPhone(), PDO::PARAM_STR);
			$stmt->bindParam(':fax', $this->getFax(), PDO::PARAM_STR);
			$stmt->bindParam(':company', $this->getCompany(), PDO::PARAM_STR);
			$stmt->bindParam(':title', $this->getTitle(), PDO::PARAM_STR);
			$stmt->bindParam(':status', $this->getStatus(), PDO::PARAM_STR);
			$stmt->bindParam(':account_type', $this->getAccount_type(), PDO::PARAM_STR);
			$stmt->bindParam(':paypal_email', $this->getPaypal_email(), PDO::PARAM_STR);
			$stmt->bindParam(':activation_key', $this->getActivation_key(), PDO::PARAM_STR);

			$stmt->execute();
			
			//Dap_User::activate($this->getActivation_key());
			
			//TODO:  send email on user create. 
			//sendNewUserInvite($this->getEmail(), $this->getFirst_name(), $this->getActivation_key(), $this->getPassword());
			//sendWelcomeUserEmail($this);
			//TODO: SEND ACTIVATION EMAIL
			/* 
			$double_optin = Dap_Config::get("IS_DOUBLE_OPTIN");
			if($double_optin == "Y") {
				sendUserActivationEmail($this);
			} else { //directly activate
				Dap_User::activate($this->getActivation_key());
			}
			*/
			
			$last_insert_id = $dap_dbh->lastInsertId();
			$this->setId($last_insert_id);
			/*
			$userCustom = $this->getUser_custom_fields();
			$userCustom->create();*/
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $last_insert_id;
	
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
			logToFile("(Dap_User.update()) email: ".$this->getEmail());
			$dap_dbh = Dap_Connection::getConnection();
	
			if (($this->getUser_name() == NULL) || ($this->getUser_name() == "")) {
				
				$sql = "update 
						dap_users 
					set
						first_name =:first_name ,
						last_name =:last_name ,
						email =:email ,
						password =:password ,
						address1 =:address1 ,
						address2 =:address2 ,
						city =:city ,
						state =:state ,
						zip =:zip ,
						country =:country ,
						phone = :phone,
						fax = :fax,
						company = :company,
						title = :title,
						status =:status ,
						account_type =:account_type ,
						paypal_email =:paypal_email,
						credits_available = :credits_available,
						exclude_iplogging = :exclude_iplogging,
						last_update_date = now()
					where
						id =:id";
					
					$stmt = $dap_dbh->prepare($sql);
			}
			else {
				$sql = "update 
						dap_users 
					set
						first_name =:first_name ,
						last_name =:last_name ,
						user_name =:user_name ,
						email =:email ,
						password =:password ,
						address1 =:address1 ,
						address2 =:address2 ,
						city =:city ,
						state =:state ,
						zip =:zip ,
						country =:country ,
						phone = :phone,
						fax = :fax,
						company = :company,
						title = :title,
						status =:status ,
						account_type =:account_type ,
						paypal_email =:paypal_email,
						credits_available = :credits_available,
						exclude_iplogging = :exclude_iplogging,
						last_update_date = now()
					where
						id =:id";
				
					$stmt = $dap_dbh->prepare($sql);
					$stmt->bindParam(':user_name', $this->getUser_name(), PDO::PARAM_STR);
			}
			
			//$password = $this->getPassword();
			logToFile("Dap_USer.class.php: update(): CREDITS AVAILABLE: " . $this->getCredits_available()); 
			//$encryptedPassword = encryptPassword($password);
			//logToFile("encryptedPassword: $encryptedPassword" ); 
			
			$stmt->bindParam(':first_name', $this->getFirst_name(), PDO::PARAM_STR);
			$stmt->bindParam(':last_name', $this->getLast_name(), PDO::PARAM_STR);
		 	$stmt->bindParam(':email', $this->getEmail(), PDO::PARAM_STR);
			$stmt->bindParam(':password', encryptPassword($this->getPassword()), PDO::PARAM_STR);
			$stmt->bindParam(':address1', $this->getAddress1(), PDO::PARAM_STR);
			$stmt->bindParam(':address2', $this->getAddress2(), PDO::PARAM_STR);
			$stmt->bindParam(':city', $this->getCity(), PDO::PARAM_STR);
			$stmt->bindParam(':state', $this->getState(), PDO::PARAM_STR);
			$stmt->bindParam(':zip', $this->getZip(), PDO::PARAM_STR);
			$stmt->bindParam(':country', $this->getCountry(), PDO::PARAM_STR);
			$stmt->bindParam(':phone', $this->getPhone(), PDO::PARAM_STR);
			$stmt->bindParam(':fax', $this->getFax(), PDO::PARAM_STR);
			$stmt->bindParam(':company', $this->getCompany(), PDO::PARAM_STR);
			$stmt->bindParam(':title', $this->getTitle(), PDO::PARAM_STR);
			$stmt->bindParam(':status', $this->getStatus(), PDO::PARAM_STR);
			$stmt->bindParam(':account_type', $this->getAccount_type(), PDO::PARAM_STR);
			$stmt->bindParam(':paypal_email', $this->getPaypal_email(), PDO::PARAM_STR);
			$stmt->bindParam(':credits_available', $this->getCredits_available(), PDO::PARAM_INT);
			$stmt->bindParam(':exclude_iplogging', $this->getExclude_iplogging(), PDO::PARAM_STR);
			$stmt->bindParam(':id', $this->getId(), PDO::PARAM_INT);
			//logToFile($sql,LOG_DEBUG_DAP);
			
			$stmt->execute();
			
			//logToFile("update complete",LOG_DEBUG_DAP);
			
			
			$customArray = $this->getUser_custom_fields();
			
			if ($customArray) {
				$custom = explode(",", $customArray);
					
				foreach($custom as $customNameValue) {
					$nv = explode("||", $customNameValue);
					//logToFile("Dap_User.class.php: updateUser(): custom name= " . $nv[0], LOG_DEBUG_DAP);
					$customFld = Dap_CustomFields::loadCustomfieldsByName($nv[0]);
					//logToFile("Dap_User.class.php: updateUser(): called loadCustomfieldsByName", LOG_DEBUG_DAP);
					if ($customFld) {
						$id = $customFld->getId();
						//logToFile("Dap_User.class.php: updateUser(): id=" . $id, LOG_DEBUG_DAP);
						
						$usercustom = new Dap_UserCustomFields();
					
						$usercustom->setUser_id($this->getId());
						$usercustom->setCustom_value($nv[1]);
						$usercustom->setCustom_id($id);
						
						$cf = Dap_UserCustomFields::loadUserCustomFieldsByCustomFieldId($id, $this->getId());
						if ($cf) {
							//logToFile("Dap_User.class.php: updateUser(): call update to update value=" . $nv[1], LOG_DEBUG_DAP);
							$usercustom->update();
						}
						else {
							//logToFile("Dap_User.class.php: updateUser(): call create to add custom value=" . $nv[1], LOG_DEBUG_DAP);
							$usercustom->create();
						}
					}
				}
			}
			
			$sql = null;
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

	//This is the shorter version of the main "update()" function
	//called from the user's home page. That is why it has fewer fields.
	public function updateShort() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
	
			$sql = "update 
						dap_users 
					set
						first_name =:first_name ,
						last_name =:last_name ,
						user_name =:user_name ,
						email =:email ,
						password =:password ,
						address1 =:address1 ,
						address2 =:address2 ,
						city =:city ,
						state =:state ,
						zip =:zip ,
						country =:country ,
						phone = :phone,
						fax = :fax,
						company = :company,
						title = :title,
						paypal_email =:paypal_email,
						last_update_date = now(),
						opted_out = :opted_out
					where
						id =:id ";
						
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':first_name', $this->getFirst_name(), PDO::PARAM_STR);
			$stmt->bindParam(':last_name', $this->getLast_name(), PDO::PARAM_STR);
			$stmt->bindParam(':user_name', $this->getUser_name(), PDO::PARAM_STR);
			$stmt->bindParam(':email', $this->getEmail(), PDO::PARAM_STR);
			$stmt->bindParam(':password', encryptPassword($this->getPassword()), PDO::PARAM_STR);
			$stmt->bindParam(':address1', $this->getAddress1(), PDO::PARAM_STR);
			$stmt->bindParam(':address2', $this->getAddress2(), PDO::PARAM_STR);
			$stmt->bindParam(':city', $this->getCity(), PDO::PARAM_STR);
			$stmt->bindParam(':state', $this->getState(), PDO::PARAM_STR);
			$stmt->bindParam(':zip', $this->getZip(), PDO::PARAM_STR);
			$stmt->bindParam(':country', $this->getCountry(), PDO::PARAM_STR);
			$stmt->bindParam(':phone', $this->getPhone(), PDO::PARAM_STR);
			$stmt->bindParam(':fax', $this->getFax(), PDO::PARAM_STR);
			$stmt->bindParam(':company', $this->getCompany(), PDO::PARAM_STR);
			$stmt->bindParam(':title', $this->getTitle(), PDO::PARAM_STR);
			$stmt->bindParam(':paypal_email', $this->getPaypal_email(), PDO::PARAM_STR);
			$stmt->bindParam(':opted_out', $this->getOpted_out(), PDO::PARAM_STR);
			$stmt->bindParam(':id', $this->getId(), PDO::PARAM_INT);

			$stmt->execute();
			
			$customArray = $this->getUser_custom_fields();
			
			if ($customArray) {
				$custom = explode(",", $customArray);
					
				foreach($custom as $customNameValue) {
					$nv = explode("||", $customNameValue);
					//logToFile("Dap_User.class.php: updateUser(): custom name= " . $nv[0], LOG_DEBUG_DAP);
					$customFld = Dap_CustomFields::loadCustomfieldsByName($nv[0]);
					//logToFile("Dap_User.class.php: updateUser(): called loadCustomfieldsByName", LOG_DEBUG_DAP);
					if ($customFld) {
						$id = $customFld->getId();
						//logToFile("Dap_User.class.php: updateUser(): id=" . $id, LOG_DEBUG_DAP);
						
						$usercustom = new Dap_UserCustomFields();
					
						$usercustom->setUser_id($this->getId());
						$usercustom->setCustom_value($nv[1]);
						$usercustom->setCustom_id($id);
						
						$cf = Dap_UserCustomFields::loadUserCustomFieldsByCustomFieldId($id, $this->getId());
						if ($cf) {
							//logToFile("Dap_User.class.php: updateUser(): call update to update value=" . $nv[1], LOG_DEBUG_DAP);
							$usercustom->update();
						}
						else {
							//logToFile("Dap_User.class.php: updateUser(): call create to add custom value=" . $nv[1], LOG_DEBUG_DAP);
							$usercustom->create();
						}
					}
				}
			}
			
			$sql = null;
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


	public static function updateUserProfile($userData, $customArray=null) {
		try {
			logToFile("in Dap_User::updateUserProfile"); 
			$dap_dbh = Dap_Connection::getConnection();
			$userId = $userData["id"];
			
			$sql = "update 
						dap_users 
					set ";
			
			foreach ($userData as $key => $value) {
				//logToFile("Dap_User::updateUserProfile: " . $key . "=" . $value);
				if($key != "id") {
					if( 
					   ( ($key == "password") || ($key == "first_name") || ($key == "email") )
					   && 
					   ($value == "") 
					) {
						continue;
					}
					
					$sql .= $key."=:".$key.", ";
				}
			}
			
			$sql = trim($sql,', ');
			$sql .= " where id =:id ";
			//logToFile("sql: $sql"); 
			//echo $sql; exit;
	
			/**
			$sql = "update 
						dap_users 
					set
						first_name =:first_name ,
						last_name =:last_name ,
						user_name =:user_name ,
						email =:email ,
						password =:password ,
						address1 =:address1 ,
						address2 =:address2 ,
						city =:city ,
						state =:state ,
						zip =:zip ,
						country =:country ,
						phone = :phone,
						fax = :fax,
						company = :company,
						title = :title,
						paypal_email =:paypal_email,
						last_update_date = now(),
						opted_out = :opted_out
					where
						id =:id ";
			*/
			
			$stmt = $dap_dbh->prepare($sql);
			
			if(isset($userData["first_name"])) {
				//logToFile("binding first_name"); 
				$stmt->bindParam(':first_name', $userData["first_name"], PDO::PARAM_STR);
			}
				
			if(isset($userData["last_name"])) {
				//logToFile("binding last_name"); 
				$stmt->bindParam(':last_name', $userData["last_name"], PDO::PARAM_STR);
			}

			if(isset($userData["user_name"])) {
				//logToFile("binding user_name"); 
				$stmt->bindParam(':user_name', $userData["user_name"], PDO::PARAM_STR);
			}
				
			if(isset($userData["email"])) {
				//$userData["email"] = urldecode($userData["email"]);
				//logToFile("binding email: " . $userData["email"]); 
				$stmt->bindParam(':email', urldecode($userData["email"]), PDO::PARAM_STR);
			}
				
			if(isset($userData["password"]) && ($userData["password"] != "") ) {
				//logToFile("binding password"); 
				$stmt->bindParam(':password', encryptPassword($userData["password"]), PDO::PARAM_STR);
			}
				
				
			if(isset($userData["address1"])) {
				//logToFile("binding address1"); 
				$stmt->bindParam(':address1', $userData["address1"], PDO::PARAM_STR);
			}				
				
			if(isset($userData["address2"])) {
				//logToFile("binding address2"); 
				$stmt->bindParam(':address2', $userData["address2"], PDO::PARAM_STR);
			}
				
			if(isset($userData["city"])) {
				//logToFile("binding city"); 
				$stmt->bindParam(':city', $userData["city"], PDO::PARAM_STR);
			}				
			
			if(isset($userData["state"])) {
				//logToFile("binding state"); 
				$stmt->bindParam(':state', $userData["state"], PDO::PARAM_STR);
			}
				
			if(isset($userData["zip"]))	 {
				//logToFile("binding zip"); 
				$stmt->bindParam(':zip', $userData["zip"], PDO::PARAM_STR);
			}
			
			if(isset($userData["country"])) {
				//logToFile("binding country"); 
				$stmt->bindParam(':country', $userData["country"], PDO::PARAM_STR);
			}
			
			if(isset($userData["phone"])) {
				//logToFile("binding phone"); 
				$stmt->bindParam(':phone', $userData["phone"], PDO::PARAM_STR);
			}
			
			if(isset($userData["fax"])) {
				//logToFile("binding fax"); 
				$stmt->bindParam(':fax', $userData["fax"], PDO::PARAM_STR);
			}
				
			if(isset($userData["company"])) {
				//logToFile("binding company"); 
				$stmt->bindParam(':company', $userData["company"], PDO::PARAM_STR);
			}
				
			if(isset($userData["title"])) {
				//logToFile("binding title"); 
				$stmt->bindParam(':title', $userData["title"], PDO::PARAM_STR);
			}
				
			if(isset($userData["paypal_email"])) {
				//logToFile("binding paypal_email"); 
				$stmt->bindParam(':paypal_email', $userData["paypal_email"], PDO::PARAM_STR);
			}
				
			if(isset($userData["opted_out"])) {
				//logToFile("binding opted_out"); 
				$stmt->bindParam(':opted_out', $userData["opted_out"], PDO::PARAM_STR);
			}
				
			$stmt->bindParam(':id', $userId, PDO::PARAM_INT);

			$stmt->execute();
			
			//$customArray = $this->getUser_custom_fields();
			//$customArray = false;
			
			if (isset($customArray)) {
				//logToFile("customArray is set"); 
				foreach($customArray as $customNameValue) {
					$nv = explode("||", $customNameValue);
					//logToFile("Dap_User.updateUserProfile(): nv[0]nv[1]=".$nv[0].",".$nv[1], LOG_DEBUG_DAP);
					$customFld = Dap_CustomFields::loadCustomfieldsByName($nv[0]);
					//logToFile("Dap_User.updateUserProfile(): called loadCustomfieldsByName", LOG_DEBUG_DAP);
					if ($customFld) {
						$id = $customFld->getId();
						//logToFile("Dap_User.updateUserProfile(): id=" . $id, LOG_DEBUG_DAP);
						
						$usercustom = new Dap_UserCustomFields();
					
						$usercustom->setUser_id($userId);
						$usercustom->setCustom_value($nv[1]);
						$usercustom->setCustom_id($id);
						
						$cf = Dap_UserCustomFields::loadUserCustomFieldsByCustomFieldId($id, $userId);
						if ($cf) {
							//logToFile("Dap_User.updateUserProfile(): call update to update value=" . $nv[1], LOG_DEBUG_DAP);
							$usercustom->update();
						}
						else {
							//logToFile("Dap_User.updateUserProfile(): call create to add custom value=" . $nv[1], LOG_DEBUG_DAP);
							$usercustom->create();
						}
					}
				}
			}
			
			$sql = null;
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

	public static function activate($code) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$dap_dbh->beginTransaction(); //begin the transaction

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
			//lets make sure we really updated something.
			$count = $stmt->rowCount();
			
			if($count > 0) { //User was indeed activated
				$user = Dap_User::loadUserByActivationKey($code);
				if(isset($user) && $user != NULL) {
					//Update user-product dates for all products for this user
					$sql2 = "update 
								dap_users_products_jn 
							set
								access_start_date = '" . date("Y-m-d") . "'
							where
								user_id = " . $user->getId() ;
								
					$stmt2 = $dap_dbh->prepare($sql2);
					$stmt2->execute();
					$stmt2 = null;
					
					//Send welcome email to user
					logToFile("sending welcome email to " . $user->getEmail(),LOG_DEBUG_DAP); 
					sendWelcomeUserEmail($user);
					
					//TODO send activation/welcome email to admin	
				}
			}
		  	
			$dap_dbh->commit(); //commit the transaction
			
			$sql = null;
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
	
	
	/*
		Delete UsersProducts
		Delete User.
	*/
	public function delete() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$dap_dbh->beginTransaction();
			$uid = $this->getId();
			//delete from usersproducts table
			$sql = "delete from  
					dap_users_products_jn
					where user_id =:id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $uid, PDO::PARAM_INT);
			$stmt->execute();
			//delete from users table
			$sql = "delete from  
					dap_users 
					where id =:id and
					status != 'U' and
					account_type != 'A'";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $uid, PDO::PARAM_INT);
			$stmt->execute();
			
			Dap_UserCustomFields::deleteUserCustom($uid);
			
			//commit
			
			$dap_dbh->commit(); 
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return;			
		} catch (PDOException $e) {
			$dap_dbh->rollback();
			$stmt = null;
			$dap_dbh = null;				
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			$dap_dbh->rollback();
			$stmt = null;
			$dap_dbh = null;			
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	
	public static function loadUsersByProduct($productId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$UsersList = array();
	
			$sql = "select 
						u.id,
						u.first_name,
						u.last_name,
						u.user_name,
						u.email,
						u.paypal_email
					from 
						dap_users u, 
						dap_users_products_jn upj
					where
						upj.product_id = :productId and
						upj.user_id = u.id
					";
					
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_STR);
			$stmt->execute();
			
			while ($row = $stmt->fetch()) {
				$user = new Dap_User();
				
				$user->setId( $row["id"] );
				$user->setFirst_name( $row["first_name"] );
				$user->setLast_name( $row["last_name"] );
				$user->setUser_name( $row["user_name"] );
				$user->setEmail( $row["email"] );
				$user->setPaypal_email( $row["paypal_email"] );
	
				$UsersList[] = $user;
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $UsersList;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}


	//Load products matching filter criteria
	public static function loadUsers($userFilter, $userStatus, $productId, $productStatus, $userSearchType, $start) {
		try {
			//logToFile("in loadUsers(): " . $userFilter. " " . $userStatus. " " . $productId. " " . $userSearchType . " " . $start,LOG_DEBUG_DAP);
			$dap_dbh = Dap_Connection::getConnection();
			$UsersList = array();
			//logToFile("In Dap_User::loadUsers... Dap_Session::get(start): " . Dap_Session::get('start')); 
			//Quick check to make sure no text is coming in for user_id
			//if( ($userSearchType == "user_id") && !is_int($userSearchType) ) {
				//return $UsersList;
			//}
			
			$sql = "";
			if( !isset($start) || ($start == "undefined")  || ($start == "")) {
				//if(Dap_Session::get('start')
				$start = 0;
			}
			
			//$howManyUsers = (Dap_Session::get('howManyUsers') == "") ? HOW_MANY_USERS_SEARCH : Dap_Session::get('howManyUsers');
			//Dap_Session::set('howManyUsers', $howManyUsers);
			$end = Dap_Config::get("SEARCH_USERS_COUNT");
			//logToFile("in loadUsers(): start: $start , end: $end", LOG_DEBUG_DAP);

			$whereClauseForProductId = ($productId == "All") ? "" : " and p.id = $productId ";
			
			$whereClauseUserSearchTypeIs = "";

			if($userSearchType == "email") $whereClauseUserSearchTypeIs = " u.email like '%$userFilter%' ";
			if($userSearchType == "first_name") $whereClauseUserSearchTypeIs = " u.first_name like '%$userFilter%' ";
			if($userSearchType == "last_name") $whereClauseUserSearchTypeIs = " u.last_name like '%$userFilter%' ";
			if($userSearchType == "user_id") $whereClauseUserSearchTypeIs = " u.id = $userFilter ";
			
			//logToFile("whereClauseUserSearchTypeIs: " . $whereClauseUserSearchTypeIs); 
			
			$whereClauseForUserStatus1 = ($userStatus == "All") ? "" : " and u.status = '$userStatus' ";
			$whereClauseForProductStatus1 = ($productStatus == "All") ? "" : " and p.status = '$productStatus' ";
			
			//First, pick up all users NOT associated with a Product
			if(strtolower($productId) == "all") {
				$whereClauseForProductId = "";
				//$whereClauseUserSearchTypeIsEmail = " u.email like '%$userFilter%' ";
				//$whereClauseUserSearchTypeIsFirstName = " u.first_name like '%$userFilter%' ";
				//$whereClauseUserSearchTypeIsLastName = " u.last_name like '%$userFilter%' ";
				//$whereClauseUserSearchTypeIsUserId = " u.id = $userFilter ";
				$sql = "select
							distinct(u.id) as uid,
							u.*,
							ar.affiliate_id
						from 
							dap_users u left join dap_aff_referrals ar
						on 
							u.id = ar.user_id and
							ar.tier = 1
						where 
							u.id not in (select user_id from dap_users_products_jn) 
						";

				$sql .= $whereClauseForUserStatus1 ;
				$sql .= "and " . $whereClauseUserSearchTypeIs;
				//if($userSearchType == "email") $sql .= "and " . $whereClauseUserSearchTypeIsEmail;
				//if($userSearchType == "last_name") $sql .= "and " . $whereClauseUserSearchTypeIsLastName;
				//if($userSearchType == "user_id") $sql .= "and " . $whereClauseUserSearchTypeIsUserId;
						
				$sql .= " order by u.id desc
						limit $start, $end
				";
				//logToFile($sql,LOG_DEBUG_DAP);
				
				$stmt = $dap_dbh->prepare($sql);
				$stmt->execute();	
				while ($row = $stmt->fetch()) {
					$userArray = array();
					$userArray["id"] = $row["id"] ;
					$userArray["first_name"] = htmlentities($row["first_name"], ENT_QUOTES, 'UTF-8');
					$userArray["last_name"] = htmlentities($row["last_name"], ENT_QUOTES, 'UTF-8');
					$userArray["user_name"] = htmlentities($row["user_name"], ENT_QUOTES, 'UTF-8');
					$userArray["email"] = htmlentities($row["email"], ENT_QUOTES, 'UTF-8');
					
					/**
					$userArray["first_name"] = $row["first_name"] ;
					$userArray["last_name"] = $row["last_name"] ;
					$userArray["user_name"] = $row["user_name"] ;
					$userArray["email"] = $row["email"] ;
					*/
					
					$userArray["paypal_email"] = $row["paypal_email"] ;
					$userArray["product_id"] = "None" ;
					$userArray["product_name"] = "None";
					$userArray["is_recurring"] = "-";
					$userArray["access_start_date"] = "-";
					$userArray["access_end_date"] = "-";
					$userArray["transaction_id"] = "-";
					$userArray["coupon_id"] = "-";
					$userArray["opted_out"] = $row["opted_out"];
					$userArray["status"] = $row["status"];
					$userArray["affiliate_id"] = $row["affiliate_id"];
				    $userArray["account_type"] = $row["account_type"] ;
					$userArray["credits_available"] = $row["credits_available"] ;
					$userArray["login_count"] = $row["login_count"] ;
					$userArray["ipaddress"] = $row["ipaddress"] ;
					$userArray["last_login_date"] = $row["last_login_date"] ;
					$UsersList[] = $userArray;
				}
			} 
			
			$sql = "select
						distinct (u.id),
						u.first_name,
						u.last_name,
						u.user_name,
						u.email,
						u.paypal_email,
						u.account_type,
						u.opted_out,
						u.status,
						u.credits_available,
						u.login_count,
						u.ipaddress,
						u.last_login_date,
						ar.affiliate_id,
						p.id as product_id,
						p.name as product_name,
						p.is_recurring,
						upj.access_start_date,
						upj.access_end_date,
						upj.transaction_id,
						upj.coupon_id,
						upj.status as upj_status
					from 
						dap_users u left join dap_aff_referrals ar
							on u.id = ar.user_id and
							ar.tier = 1
						join dap_users_products_jn upj
							on u.id = upj.user_id
						join dap_products p
							on upj.product_id = p.id 
					";
						
			$sql .= "and " . $whereClauseUserSearchTypeIs;
			//if($userSearchType == "email") $sql .= "and " . $whereClauseUserSearchTypeIsEmail;
			//if($userSearchType == "last_name") $sql .= "and " . $whereClauseUserSearchTypeIsLastName;
			//if($userSearchType == "user_id") $sql .= "and " . $whereClauseUserSearchTypeIsUserId;
			
			$sql .= "
							$whereClauseForUserStatus1 
							$whereClauseForProductStatus1
							$whereClauseForProductId
						order by u.id desc
						limit $start, $end
				";
			
			//logToFile("2nd sql: $sql",LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();
			
			while ($row = $stmt->fetch()) {
				$userArray = array();
				
				$userArray["id"] = $row["id"] ;
				/*
				$userArray["first_name"] = iconv("ISO-8859-1", "UTF-8", htmlentities($row["first_name"]));
				$userArray["last_name"] = iconv("ISO-8859-1", "UTF-8", htmlentities($row["last_name"]));
				$userArray["user_name"] = iconv("ISO-8859-1", "UTF-8", htmlentities($row["user_name"]));
				$userArray["email"] = iconv("ISO-8859-1", "UTF-8", htmlentities($row["email"]));
				*/
				
				/*
				$userArray["first_name"] = mb_convert_encoding($row["first_name"], "UTF-8", "ISO-8859-1");
				$userArray["last_name"] = mb_convert_encoding($row["last_name"], "UTF-8", "ISO-8859-1");
				$userArray["user_name"] = mb_convert_encoding($row["user_name"], "UTF-8", "ISO-8859-1");
				$userArray["email"] = mb_convert_encoding($row["email"], "UTF-8", "ISO-8859-1");
				*/
				
				/*
				$userArray["first_name"] = $row["first_name"] ;
				$userArray["last_name"] = $row["last_name"] ;
				$userArray["user_name"] = $row["user_name"] ;
				$userArray["email"] = $row["email"] ;
				*/
				
				
				$userArray["first_name"] = htmlentities($row["first_name"], ENT_QUOTES, 'UTF-8');
				$userArray["last_name"] = htmlentities($row["last_name"], ENT_QUOTES, 'UTF-8');
				$userArray["user_name"] = htmlentities($row["user_name"], ENT_QUOTES, 'UTF-8');
				$userArray["email"] = htmlentities($row["email"], ENT_QUOTES, 'UTF-8');
				
				
				$userArray["paypal_email"] = $row["paypal_email"] ;
				$userArray["product_id"] = $row["product_id"] ;
				$userArray["product_name"] = $row["product_name"] ;
				//$userArray["product_name"] = mb_convert_encoding($row["product_name"], "UTF-8", "ISO-8859-1");
				$userArray["product_name"] = htmlentities($row["product_name"], ENT_QUOTES, 'UTF-8');
				
				
				
				$userArray["is_recurring"] = $row["is_recurring"] ;
				$userArray["access_start_date"] = $row["access_start_date"];
				$userArray["access_end_date"] = $row["access_end_date"];
				$userArray["transaction_id"] = $row["transaction_id"];
				$userArray["coupon_id"] = $row["coupon_id"];
				$userArray["upj_status"] = $row["upj_status"];
				$userArray["opted_out"] = $row["opted_out"];
				$userArray["status"] = $row["status"];
				$userArray["affiliate_id"] = $row["affiliate_id"];
				$userArray["account_type"] = $row["account_type"] ;
				$userArray["credits_available"] = $row["credits_available"] ;
				$userArray["login_count"] = $row["login_count"] ;
				$userArray["ipaddress"] = $row["ipaddress"] ;
				$userArray["last_login_date"] = $row["last_login_date"] ;
				
				//logToFile("Dap_User.class.php: credits_available: " . $userArray["credits_available"],LOG_DEBUG_DAP);
				
				$UsersList[] = $userArray;
				
				//logToFile("first_name: " . $userArray["first_name"],LOG_DEBUG_DAP);
				//logToFile("last_name: " . $userArray["last_name"],LOG_DEBUG_DAP);
				//logToFile("user_name: " . $userArray["user_name"],LOG_DEBUG_DAP);
				//logToFile("coupon: " . $userArray["coupon"],LOG_DEBUG_DAP);
			}
			
			$howManyUsers = Dap_Config::get("SEARCH_USERS_COUNT");
			$start = $start + $howManyUsers;
			
			//logToFile($start,LOG_DEBUG_DAP);
			Dap_Session::set('start', $start);
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $UsersList;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	
	
	// A function to generate random alphanumeric passwords in PHP
	public static function GeneratePassword($length = 5) {
		//logToFile("In GeneratePassword",LOG_DEBUG_DAP); 
		// This variable contains the list of allowable characters
		// for the password.  Note that the number 0 and the letter
		// 'O' have been removed to avoid confusion between the two.
		// The same is true of 'I' and 1
		
		//If admin has set default password in config, then return that
		if ( Dap_Config::get('DEFAULT_PASSWORD') != "" ) {
			return Dap_Config::get('DEFAULT_PASSWORD');
		}
		
		//Else, generate new one
		$allowable_characters = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789";
		
		// We see how many characters are in the allowable list
		$ps_len = strlen($allowable_characters);
	
		// Seed the random number generator with the microtime stamp
		// (current UNIX timestamp, but in microseconds)
		mt_srand((double)microtime()*1000000);
	
		// Declare the password as a blank string.
		$pass = "";
	
		// Loop the number of times specified by $length
		for($i = 0; $i < $length; $i++) {
		
			// Each iteration, pick a random character from the
			// allowable string and append it to the password.
			$pass .= $allowable_characters[mt_rand(0,$ps_len-1)];
		
		} // End for
	
		// Retun the password we've selected
		return $pass;
	}



	public static function authenticateLogins($email, $ip) {
		$dap_dbh = Dap_Connection::getConnection();
		$count = 0;
		
		//If anything wrong with email or ip, just return true so that user can at least log in
		if( !isset($email) || ($email == "") || !isset($ip) || ($ip == "") ) return true;

		//If user is admin, then let him thru
		$user = Dap_User::loadUserByEmail($email); //load user object
		if(!isset($user)) { 
			logToFile("Oops, invalid user"); 
			return false;
		}

		if($user->getAccount_type() == "A") return true;
		if($user->getExclude_iplogging() == "N") {
			//logToFile("Exlude_iplogging() = N"); 
			
			//Now see if user's current IP already exists in db - if yes, then simply let her thru
			$sql = "SELECT 
						count(login_ip) as count
					FROM
						dap_users_logins ul,
						dap_users u
					WHERE
						u.email = :email and
						u.id = ul.user_id and
						ul.login_ip = :login_ip
						";
			
			//logToFile("$sql",LOG_DEBUG_DAP);			
			logToFile("$email, $ip",LOG_DEBUG_DAP);			
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':email', $email, PDO::PARAM_STR);
			$stmt->bindParam(':login_ip', $ip, PDO::PARAM_STR);
			$stmt->execute();

			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$count = $row["count"];
				logToFile("Existing Login IP Count: $count",LOG_DEBUG_DAP);
				
				if($count > 0) {
					return true;
				} 
			}
		
			//Else, this is a new IP
			//Now check to see if user has any login attempts (from new ip) left
			$sql = "SELECT 
						count(ul.login_ip) as count
					FROM
						dap_users_logins ul,
						dap_users u
					WHERE
						u.email = :email and
						u.id = ul.user_id
						";
						
			//logToFile("$sql",LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':email', $email, PDO::PARAM_STR);
			$stmt->execute();
	
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$count = $row["count"];
				$maxUserLogins = Dap_Config::get("MAX_USER_LOGINS"); //current log in will count as 1
				//logToFile("count: $count, maxUserLogsin: $maxUserLogins",LOG_DEBUG_DAP);
				if($count >= $maxUserLogins) {
					//flag user as locked, send email to admin
					logToFile("******** Oops, user is over login limit. So returning false"); 
					Dap_User::lockUser($user,true);
					return false;
				} 
			}
		} //end of IP check
		
		
		//Else - count is ok to let user login - now insert record
		//We don't care about whether insert will go through,
		//because duplicate key (existing IP) will get kicked out and
		//only new IP will get inserted.
		try {
			$user_id = $user->getId();

			$sql = "insert into dap_users_logins
					(user_id, login_ip) values
					(:user_id, :login_ip)
					";

			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
			$stmt->bindParam(':login_ip', $ip, PDO::PARAM_STR);
			$stmt->execute();
			
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
		}
		
		//Even if there's some db error, just return true 
		//so that it doesn't affect user login
		$sql = null;
		$stmt = null;
		$dap_dbh = null; 
		
		return true;
	}
	
	
	/*
		To lock user, call this with $lockUser = true
		To unlock, call with $lockUser = false;
	*/
	public static function lockUser($user, $lockUser=true) {
		try {
			$reponse = "Sorry, user not found!";
			if(is_null($user)) return $reponse;
			
			$status = 'A';
			if($lockUser) $status = 'L';
			
			$email = $user->getEmail();
			if( is_null($email) || ($email == "") ) return $reponse;
			
			$dap_dbh = Dap_Connection::getConnection();
			$sql = "update 
						dap_users
					set 
						status = :status
					where 
						email = :email";
					
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':email', $email, PDO::PARAM_STR);
			$stmt->bindParam(':status', $status, PDO::PARAM_STR);
			$stmt->execute();
			$stmt = null;
			
			if($lockUser) {
				//If locking...
				$subject = Dap_Config::get("LOCKED_EMAIL_SUBJECT");				
				$body = Dap_Templates::getContentByName("LOCKED_EMAIL_CONTENT");
				$body = personalizeMessage($user, $body);
				sendEmail($email, $subject, $body);
				
				//Add extra header for admin
				$body = Dap_Config::get("ADMIN_NAME") . ",\r\n\r\nThis is just your copy of the email that\r\nhas also been sent to the user below.\r\n***********************\r\n\r\n" . $body;
				$subject = "(Copy) " . $subject;
				sendAdminEmail($subject, $body);
				$response = "SUCCESS! User has been locked and an email notification has been sent to them.";
			} else {
				//If unlocking, then also delete all IP records for the user
				$sql = "delete from 
							dap_users_logins
						where 
							user_id = :user_id";
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':user_id', $user->getId(), PDO::PARAM_INT);
				$stmt->execute();
				$stmt = null;
				$dap_dbh = null;
				
				$subject = Dap_Config::get("UNLOCKED_EMAIL_SUBJECT");
				$body = Dap_Templates::getContentByName("UNLOCKED_EMAIL_CONTENT");
				$body = personalizeMessage($user, $body);
				sendEmail($email, $subject, $body);
				sendAdminEmail($subject, $body);
				$response = "SUCCESS! User has been unlocked and an email notification has been sent to them.";
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $response;
			
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}

	}


	function resetLogins($user) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$reponse = "";
			
			//delete all IP records for the user
			$sql = "delete 
					from 
						dap_users_logins
					where 
						user_id = :user_id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':user_id', $user->getId(), PDO::PARAM_INT);
			$stmt->execute();
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null;
			
			return "SUCCESS! Login IP records of user have been reset";
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}

	}


	public static function deleteUser($id) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$dap_dbh->beginTransaction(); //begin the transaction
			
			//Delete from dap_users table
			$sql = "delete from dap_users where id = :id and account_type != 'A'";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			
			//Delete from all other tables 
			$sql = "delete from dap_users_logins where user_id = :id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			
			$sql = "delete from dap_users_products_jn where user_id = :id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			
			$sql = "delete from dap_users_resources_jn where user_id = :id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			
			$sql = "delete from dap_users_products_jn where user_id = :id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			
			$dap_dbh->commit(); //commit the transaction
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
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
		Unsubscribe user
	*/
	public static function unsubscribe($email, $code) {
		try {
			logToFile("in unsubscribe",LOG_DEBUG_DAP); 
			$dap_dbh = Dap_Connection::getConnection();
			$sql = "update 
						dap_users 
					set
						opted_out = 'Y'
					where
						email = :email and
						activation_key = :code
						";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':email', $email, PDO::PARAM_STR);
			$stmt->bindParam(':code', $code, PDO::PARAM_INT);
			$stmt->execute();

			logToFile("Sending unsubscribe notification to admin"); 
			$user = Dap_User::loadUserByEmail($email);
			$subject = "Unsubscribe Notification";
			$body = "This user has Unsubscribed...\n\nName: " . $user->getFirst_name() . " " . $user->getLast_name() . "\nEmail: " . $user->getEmail();
			sendAdminEmail($subject, $body);
			
			$sql = null;
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
	
	public static function exportUsers($group,$userstatus,$productstatus,$access,$optin,$display) {
		//Load user data from database
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$sql = "";
			$stmt = null;
			$bindgroup=true;
			
			//logToFile("exportUsers: group=$group, userstatus=$userstatus, productstatus=$productstatus, access=$access, optin=$optin, display=$display"); 
			
			if($optin!="All") {
				if($optin=="OI") {
					$optinsql=" and u.opted_out = 'N'";
					$optinnowheresql=" where u.opted_out = 'N'";
				}
				if($optin=="OO") {
					$optinsql=" and u.opted_out = 'Y'";
					$optinnowheresql=" where u.opted_out = 'Y'";
				}
			}
			
			if($userstatus!="All") {
				$userstatussql=" and u.status = '" . $userstatus . "'";
				$userstatusnowheresql=" where u.status = '" . $userstatus . "'";
			}
			
			if($productstatus!="All") {
				$productstatussql=" and upj.status = '" . $productstatus . "'";
				$productstatusnowheresql=" where upj.status = '" . $productstatus . "'";
			}
			
			
			if($display=="U")
				$selectFields="select distinct u.*";
			else 	
				$selectFields="select  u.id,u.first_name,u.last_name,u.user_name,u.email,u.password,u.city,u.state,u.zip,u.country,u.phone,u.fax,u.company,u.title,u.is_affiliate,u.last_login_date,u.activation_key,u.status,u.login_count,u.ipaddress,u.account_type,u.signup_date,u.paypal_email,u.last_update_date,u.aff_nick,u.opted_out,u.self_service_status,u.credits_available,upj.product_id,p.name,upj.access_start_date,upj.access_end_date,upj.transaction_id,upj.status";
			
			$select_users_sql_all_active_users = $selectFields . " FROM 
					dap_products p,
					dap_users u,
					dap_users_products_jn upj
				WHERE
					p.status = 'A'
					and upj.user_id = u.id
					and upj.product_id = p.id
					and ( datediff(upj.access_end_date, curdate()) >= 0 )
					 ";
			
			
			$select_users_sql_all_expired_users = $selectFields . " FROM 
					dap_products p,
					dap_users u,
					dap_users_products_jn upj
				WHERE
					p.status = 'A'
					and upj.user_id = u.id
					and upj.product_id = p.id
					and ( datediff(upj.access_end_date, curdate()) < 0 )
					";
			
			
			if($group == "All") {
				if($access=="All") {
					if($display=="U")
					  $sql = $selectFields . " FROM 
								dap_users u,
								dap_products p,
								dap_users_products_jn upj
							where
								p.id = upj.product_id and
								upj.user_id = u.id 
								";
			 		  else 
					  $sql = $selectFields . " FROM 
								dap_users u,
								dap_products p,
								dap_users_products_jn upj
							where
								p.id = upj.product_id and
								upj.user_id = u.id 
								";
				}
				else if($access=="A") {
					$sql=$select_users_sql_all_active_users;
				} 
				else if($access=="E") {
				  	$sql=$select_users_sql_all_expired_users;
				} 
				
				
				$bindgroup=false;
			} 
			else {
				if($group=="AllP") {
					if($access=="All") {
					  $sql = $selectFields . " FROM 
								dap_users u,
								dap_products p,
								dap_users_products_jn upj
							where
								p.id = upj.product_id and
								upj.user_id = u.id";
					  
					  $sql = $sql . " and p.is_free_product = 'N'";
					  
					}
					else if($access=="A") {
						$sql=$select_users_sql_all_active_users;
						$sql = $sql . " and p.is_free_product = 'N'";
					} 
					else if($access=="E") {
						$sql=$select_users_sql_all_expired_users;
						$sql = $sql . " and p.is_free_product = 'N'";
					}
					$bindgroup=false;
				} 
				else if($group=="AllF") {
					if($access=="All") {
					  $sql = $selectFields . " FROM 
								dap_users u,
								dap_products p,
								dap_users_products_jn upj
							where
								p.id = upj.product_id and
								p.is_free_product = 'Y' and
								upj.user_id = u.id 
								";
					}
					else if($access=="A") {
						$sql=$select_users_sql_all_active_users;
						$sql = $sql . " and p.is_free_product = 'Y'";
					} 
					else if($access=="E") {
						$sql=$select_users_sql_all_expired_users;
						$sql = $sql . " and p.is_free_product = 'Y'";
					}
					$bindgroup=false;
				}
				else { //product level
					if( ($access=="All") ) {
					  $sql = $selectFields . " FROM
								dap_users u,
								dap_products p,
								dap_users_products_jn upj
							where
								p.id = :group and
								p.id = upj.product_id and
								upj.user_id = u.id
								";		
					}
					else if($access=="A") {
						$sql=$select_users_sql_all_active_users;
						$sql = $sql . " and p.id = :group";
					} 
					else if($access=="E") {
					  	$sql=$select_users_sql_all_expired_users;
						$sql = $sql . " and p.id = :group";
					} 
				}
				
				
			}
			//logToFile("exportUsers: before OPTIN SQL: $sql");
			if($optinsql!="") {
				if(stristr($sql,"where")!=FALSE) 
					$sql=$sql . $optinsql;
				else
					$sql=$sql . $optinnowheresql ;
				//logToFile("exportUsers: OPTIN SQL: $sql"); 
			}
			
			if($userstatussql!="") {
				if(stristr($sql,"where")!=FALSE) 
					$sql=$sql . $userstatussql;
				else
					$sql=$sql . $userstatusnowheresql;
			}
				
			
			if($productstatussql!="") {
				if(stristr($sql,"dap_users_products_jn")!=FALSE) {
				  if(stristr($sql,"where")!=FALSE) 
					  $sql=$sql . $productstatussql;
				  else
					  $sql=$sql . $productstatusnowheresql;
				}
			}
			
			//logToFile("exportUsers: OPTIN SQL: $sql"); 
			
			$stmt = $dap_dbh->prepare($sql);
			
			if($bindgroup) {
				//logToFile("exportUsers: bindparam: $group $access"); 
				$stmt->bindParam(':group', $group, PDO::PARAM_INT);
			}
			
			$stmt->execute();
			
			//logToFile("exportUsers: execute"); 
			
			$UsersList = array();
			
			while ($obj = $stmt->fetch(PDO::FETCH_ASSOC)) {
				/**
				$obj["first_name"] = htmlentities($obj["first_name"]);
				$obj["last_name"] = htmlentities($obj["last_name"]);
				$obj["email"] = htmlentities($obj["email"]);
				*/
				
				//logToFile($obj[0] . ", " . $obj[1] . ", " . $obj[2] . ", " . $obj[3] . ", " . $obj[4]); 
				$UsersList[] = $obj;

			}
			
			//logToFile("exportUsers: got result"); 
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $UsersList;
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
	

	public static function saveBlob($resourceId, $blob, $blobType="", $title="", $description="") {
		try {
			logToFile("In Dap_User::savePhoto",LOG_DEBUG_DAP); 
			$dap_dbh = Dap_Connection::getConnection();

			$sql = "select 
						*
					from 
						dap_blob
					where
						resource_id = :resourceId";
			
			//logToFile("saveBlob 1: $sql",LOG_DEBUG_DAP); 
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':resourceId', $resourceId, PDO::PARAM_INT);
			$stmt->execute();
			
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				//Found existing photo
				$sql = "update dap_blob
					set 
						content = :blob,
						content_type = :blobType,
						title = :title,
						description = :description
					where
						resource_id = :resourceId";
						
			} else {
				//First time insert
				$sql = "insert into 
							dap_blob
						set
							resource_id = :resourceId,
							content = :blob,
							content_type = :blobType,
							title = :title,
							description = :description
						";
			}
			
			//logToFile("saveBlob 2: $sql",LOG_DEBUG_DAP); 
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':resourceId', $resourceId, PDO::PARAM_INT);
			$stmt->bindParam(':blob', $blob, PDO::PARAM_LOB);
			$stmt->bindParam(':blobType', $blobType, PDO::PARAM_STR);
			$stmt->bindParam(':title', $title, PDO::PARAM_STR);
			$stmt->bindParam(':description', $description, PDO::PARAM_STR);
			$stmt->execute();
			
			$sql = null;
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

	public static function readBlob($resourceId) {
		try {
			logToFile("In Dap_User::readBlob, resourceId: $resourceId",LOG_DEBUG_DAP); 
			$dap_dbh = Dap_Connection::getConnection();

			$sql = "select * from 
						dap_blob
					where
						resource_id = :resourceId";
			
			//logToFile("readBlob: $sql",LOG_DEBUG_DAP); 
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':resourceId', $resourceId, PDO::PARAM_INT);
			$stmt->execute();
			
			$row = null;
			
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				//logToFile("(readBlob) Image found - resourceId: $resourceId , row: " . $row["content_type"]);
			}
				
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $row;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}
	
	public static function deleteBlob($resourceId) {
		try {
			logToFile("In Dap_User::deleteBlob, resourceId: $resourceId",LOG_DEBUG_DAP); 
			$dap_dbh = Dap_Connection::getConnection();

			$sql = "delete from 
						dap_blob
					where
						resource_id = :resourceId";
			
			//logToFile("readBlob: $sql",LOG_DEBUG_DAP); 
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':resourceId', $resourceId, PDO::PARAM_INT);
			$stmt->execute();
			
			$sql = null;
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
	
	
	public function isPaidUser() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$isPaidUser = false;
			
			//First activate user
			$sql = "select 
						* 
					from 
						dap_users_products_jn 
					where 
						user_id = :userId and
						transaction_id not in (0, -1, -2) and
						CURDATE() <= access_end_date
					";
						
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $this->getId(), PDO::PARAM_INT);
			$stmt->execute();
			
			if ($row = $stmt->fetch()) {
				//$count = $row["count"];
				$isPaidUser = true;
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $isPaidUser;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}
	

	public function isExpiredUserInAtleastOneProduct() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$isExpiredUser = false;
			
			//First activate user
			$sql = "select 
						* 
					from 
						dap_users_products_jn 
					where 
						user_id = :userId and
						CURDATE() > access_end_date
					";
						
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $this->getId(), PDO::PARAM_INT);
			$stmt->execute();
			
			if ($row = $stmt->fetch()) {
				//$count = $row["count"];
				$isExpiredUser = true;
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $isExpiredUser;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}


	public function hasAccessTo($productId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$hasAccessTo = false;
			
			//First activate user
			$sql = "select 
						* 
					from 
						dap_users_products_jn 
					where
						user_id = :userId and 
						product_id = :productId and
						CURDATE() <= access_end_date";
						
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $this->getId(), PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->execute();
			
			if ($row = $stmt->fetch()) {
				//$count = $row["count"];
				$hasAccessTo = true;
				//logToFile("DAP_User.class.php: hasaccess=true");	
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $hasAccessTo;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}
	
	
	public function hasAccessToProducts($productIds) {
		try {
			//logToFile("productIds: $productIds"); 
			$dap_dbh = Dap_Connection::getConnection();
			$hasAccessTo = false;
			
			$post_cancel_access = Dap_Config::get("POST_CANCEL_ACCESS");
			if(isset($post_cancel_access)) {
				$post_cancel_access = strtolower($post_cancel_access);
			}
			
			$sql = "";
			
			if($post_cancel_access == 'y') { //Then don't worry if user has expired - include ALL
				$sql = "select 
							* 
						from 
							dap_users_products_jn 
						where
							user_id = :userId and 
							product_id in ( " . $productIds . ")";
						//logToFile("1"); 
							
			} else { //Ignore those who have expired, select only CURRENT
				$sql = "select 
							* 
						from 
							dap_users_products_jn 
						where
							user_id = :userId and 
							product_id in ( " . $productIds . ") and
							CURDATE() <= access_end_date";
						//logToFile("2"); 
			}
			
			//logToFile("sql: $sql"); 
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $this->getId(), PDO::PARAM_INT);
			//$stmt->bindParam(':productIds', $productIds, PDO::PARAM_STR);
			$stmt->execute();
			
			if ($row = $stmt->fetch()) {
				//$count = $row["count"];
				$hasAccessTo = true;
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $hasAccessTo;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}
	
	
	public function hasExpiredAccessToProducts($productIds) {
		try {
			//logToFile("productIds: $productIds"); 
			//logToFile("userId: " . $this->getId()); 
			$dap_dbh = Dap_Connection::getConnection();
			$hasExpiredAccess = false;
			
			$sql = "select 
						* 
					from 
						dap_users_products_jn 
					where
						user_id = :userId and ";
			
			if($productIds != "ANY") $sql .= " product_id in ( " . $productIds . ") and ";		
						
			$sql .= " access_end_date < CURDATE()";
			
			
			//logToFile("sql: $sql"); 
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $this->getId(), PDO::PARAM_INT);
			//$stmt->bindParam(':productIds', $productIds, PDO::PARAM_STR);
			$stmt->execute();
			
			if ($row = $stmt->fetch()) {
				//$count = $row["count"];
				//logToFile("here"); 
				$hasExpiredAccess = true;
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $hasExpiredAccess;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}

	
	
	public function hasPaidAccessTo($productId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$hasPaidAccessTo = false;
			
			$sql = "select 
						* 
					from 
						dap_users_products_jn 
					where
						user_id = :userId and 
						product_id = :productId and
						transaction_id not in (0, -1, -2) and
						CURDATE() <= access_end_date";
						
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $this->getId(), PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->execute();
			
			if ($row = $stmt->fetch()) {
				//$count = $row["count"];
				$hasPaidAccessTo = true;
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $hasPaidAccessTo;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}
	
	
	public function hasPaidAccessToProducts($productIds) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$hasAccessTo = false;
			
			//First activate user
			$sql = "select 
						* 
					from 
						dap_users_products_jn 
					where
						user_id = :userId and 
						product_id in ( " . $productIds . ") and
						transaction_id not in (0, -1, -2) and
						CURDATE() <= access_end_date";
						
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $this->getId(), PDO::PARAM_INT);
			//$stmt->bindParam(':productIds', $productIds, PDO::PARAM_INT);
			$stmt->execute();
			
			if ($row = $stmt->fetch()) {
				//$count = $row["count"];
				$hasAccessTo = true;
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $hasAccessTo;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}
	
	public function hasEverHadAccessTo($productId) {
		try {
			//logToFile("In hasEverHadAccessTo, user id: " . $this->getId() . ", productId: $productId"); 
			$dap_dbh = Dap_Connection::getConnection();
			$hasEverHadAccessTo = false;
			
			//First activate user
			$sql = "select 
						* 
					from 
						dap_users_products_jn 
					where
						user_id = :userId and 
						product_id = :productId";
						
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $this->getId(), PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->execute();
			
			if ($row = $stmt->fetch()) {
				$hasEverHadAccessTo = true;
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $hasEverHadAccessTo;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}
	

	public function hasAccessToHowManyDistinctProducts() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$count = 0;
			
			//Return total number of products user has ever had access to (regardless of access_end_date)
			$sql = "select
						count(distinct(product_id)) as count
					from
						dap_users_products_jn
					where
						user_id = :userId";
			
			$userId = $this->getId();
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->execute();
			
			if ($row = $stmt->fetch()) {
				$count = $row["count"];
			}
			
			$sql = null;
			$stmt = null;
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
	
	
	public function getAffiliate() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$affiliateId = null;
			
			$sql = "select
						affiliate_id
					from
						dap_aff_referrals
					where
						user_id = :userId";
						
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $this->getId(), PDO::PARAM_INT);
			$stmt->execute();
			
			if ($row = $stmt->fetch()) {
				$affiliateId = $row["affiliate_id"];
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $affiliateId;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}


	public static function getAffiliateForUser($userId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$affiliateId = null;
			
			$sql = "select
						affiliate_id
					from
						dap_aff_referrals
					where
						user_id = :userId";
						
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->execute();
			
			if ($row = $stmt->fetch()) {
				$affiliateId = $row["affiliate_id"];
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $affiliateId;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}

	public static function loadUserByUsername($email, $user_name) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$user = NULL;
			logToFile($email . "   " . $user_name); 
			//Loads current user data into the $User object
			//TODO: What is this check? lets first check if we have session data for this user.
			$sql = "SELECT
						*
					FROM
						dap_users
					WHERE
						email != :email and
						user_name = :user_name
					";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':email', $email, PDO::PARAM_STR);
			$stmt->bindParam(':user_name', $user_name, PDO::PARAM_STR);
			$stmt->execute();
			//echo "Error Code:".$stmt->errorCode().", Number of rows:".$stmt->fetchColumn();
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				//echo "Error Code:".$stmt->errorCode().", Number of rows:".$stmt->rowCount();
				$user = new Dap_User();
				$user->setId( $row["id"] );
				$user->setFirst_name( $row["first_name"] );
				$user->setLast_name( $row["last_name"] );
				$user->setUser_name( $row["user_name"] );
				$user->setEmail( $row["email"] );
				$user->setPassword( decryptPassword($row["password"]) );
				$user->setAddress1( $row["address1"] );
				$user->setAddress2( $row["address2"] );
				$user->setCity( $row["city"] );
				$user->setState( $row["state"] );
				$user->setZip( $row["zip"] );
				$user->setCountry( $row["country"] );
				$user->setPhone( $row["phone"] );
				$user->setFax( $row["fax"] );
				$user->setCompany( $row["company"] );
				$user->setTitle( $row["title"] );
				$user->setLogin_count( $row["login_count"] );
				$user->setIs_affiliate( $row["is_affiliate"] );
				$user->setLast_login_date( $row["last_login_date"] );
				$user->setActivation_key( $row["activation_key"] );
				$user->setStatus( $row["status"] );
				$user->setIpaddress( $row["ipaddress"] );
				$user->setAccount_type( $row["account_type"] );
				$user->setSignup_date( $row["signup_date"] );
				$user->setPaypal_email( $row["paypal_email"] );
				$user->setLast_update_date( $row["last_update_date"] );		
				$user->setSelf_service_status( $row["self_service_status"] );		
				$user->setCredits_available( $row["credits_available"] );	
				$user->setExclude_iplogging( $row["exclude_iplogging"] );
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $user;	
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	

	public static function loadUserByEmailAndUsername($email, $user_name) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$user = NULL;
			logToFile($email . "   " . $user_name); 
			//Loads current user data into the $User object
			//TODO: What is this check? lets first check if we have session data for this user.
			$sql = "SELECT
						*
					FROM
						dap_users
					WHERE
						email = :email and
						user_name = :user_name
					";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':email', $email, PDO::PARAM_STR);
			$stmt->bindParam(':user_name', $user_name, PDO::PARAM_STR);
			$stmt->execute();
			//echo "Error Code:".$stmt->errorCode().", Number of rows:".$stmt->fetchColumn();
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				//echo "Error Code:".$stmt->errorCode().", Number of rows:".$stmt->rowCount();
				$user = new Dap_User();
				$user->setId( $row["id"] );
				$user->setFirst_name( $row["first_name"] );
				$user->setLast_name( $row["last_name"] );
				$user->setUser_name( $row["user_name"] );
				$user->setEmail( $row["email"] );
				$user->setPassword( decryptPassword($row["password"]) );
				$user->setAddress1( $row["address1"] );
				$user->setAddress2( $row["address2"] );
				$user->setCity( $row["city"] );
				$user->setState( $row["state"] );
				$user->setZip( $row["zip"] );
				$user->setCountry( $row["country"] );
				$user->setPhone( $row["phone"] );
				$user->setFax( $row["fax"] );
				$user->setCompany( $row["company"] );
				$user->setTitle( $row["title"] );
				$user->setLogin_count( $row["login_count"] );
				$user->setIs_affiliate( $row["is_affiliate"] );
				$user->setLast_login_date( $row["last_login_date"] );
				$user->setActivation_key( $row["activation_key"] );
				$user->setStatus( $row["status"] );
				$user->setIpaddress( $row["ipaddress"] );
				$user->setAccount_type( $row["account_type"] );
				$user->setSignup_date( $row["signup_date"] );
				$user->setPaypal_email( $row["paypal_email"] );
				$user->setLast_update_date( $row["last_update_date"] );		
				$user->setSelf_service_status( $row["self_service_status"] );		
				$user->setCredits_available( $row["credits_available"] );	
				$user->setExclude_iplogging( $row["exclude_iplogging"] );
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $user;	
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}

	
	public static function isInUse($what, $key) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$isInUse = false;
			
			$sql = "select 
						* 
					from 
						dap_users 
					where 
						$what = :what
					";
						
			//logToFile("sql: $sql"); 
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':what', $key, PDO::PARAM_INT);
			$stmt->execute();
			
			if ($row = $stmt->fetch()) {
				//$count = $row["count"];
				$isInUse = true;
				//logToFile("is in use: $key"); 
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $isInUse;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}

	
	/**
		This calculates if the user's current "day" (of access for given product) falls within given start and end days
	*/
	public function isDripEligible($productIds, $startday, $endday=9999) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$isDripEligible = false;
			
			if($startday == "") $startday = 1;
			if($endday == "") $endday = 9999;
			
			//logToFile("productIds: $productIds , startday: $startday, endday: $endday"); 
			
			//First activate user
			$sql = "select
						( TO_DAYS(CURDATE()) - TO_DAYS(access_start_date) + 1) as isOnWhichDay
					from 
						dap_users_products_jn 
					where
						user_id = :userId and 
						product_id in ( " . $productIds . ") and
						CURDATE() <= access_end_date";
						
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $this->getId(), PDO::PARAM_INT);
			//$stmt->bindParam(':productIds', $productIds, PDO::PARAM_STR);
			$stmt->execute();
			
			if ($row = $stmt->fetch()) {
				$isOnWhichDay = $row["isOnWhichDay"];
				if( (intval($isOnWhichDay) >= intval($startday)) && (intval($isOnWhichDay) <= intval($endday)) ) {
					$isDripEligible = true;
				}
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $isDripEligible;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}



	/**
		This calculates if the user's current negative "day" (of access for given product) 
		falls within given start and end days
	*/
	public function isDripEligibleReverse($productIds=1, $startday, $endday=9999) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$isDripEligible = false;
			
			if($startday == "") $startday = 1;
			if($endday == "") $endday = 9999;
			
			$startday = abs(intval($startday));
			$endday = abs(intval($endday));
			
			//logToFile("productIds: $productIds , startday: $startday, endday: $endday"); 
			
			//First activate user
			$sql = "select
						(TO_DAYS(access_end_date) - TO_DAYS(CURDATE())) as daysToGoBeforeExpiry
					from 
						dap_users_products_jn 
					where
						user_id = :userId and 
						product_id in ( " . $productIds . ") and
						CURDATE() <= access_end_date";
						
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $this->getId(), PDO::PARAM_INT);
			//$stmt->bindParam(':productIds', $productIds, PDO::PARAM_STR);
			$stmt->execute();
			
			if ($row = $stmt->fetch()) {
				$daysToGoBeforeExpiry = $row["daysToGoBeforeExpiry"];
				//logToFile("daysToGoBeforeExpiry: $daysToGoBeforeExpiry"); 
				
				if( ((intval($daysToGoBeforeExpiry)) <= intval($startday)) && ( (intval($daysToGoBeforeExpiry)) >= intval($endday)) ) {
					$isDripEligible = true;
				}
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $isDripEligible;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}
	
	public function updateCredits($creditsRemaining,$creditsEarned="",$connection=null) {
	  try {
		  logToFile("(Dap_User.update()) creditsRemaining: ".$creditsRemaining);
		  $dap_dbh = null;
		   
	
		  //If connection is passed from another function that is using a transaction, then use that.
		  //If not, create own connection
		  if(!is_null($connection)) {
			  $dap_dbh = $connection;
		  } else {
			  $dap_dbh = Dap_Connection::getConnection();
		  }
		  
		  $dapuser = Dap_User::loadUserById($this->getId());
		  
		  $available = $dapuser->getCredits_available() + $creditsEarned;
		  //$earned = $dapuser->getCredits_earned() + $creditsEarned;
		  
		 /*  logToFile("Dap_User.class.php:updateCredits():  USERID=".$this->getId(),LOG_DEBUG_DAP);
		   
		   logToFile("Dap_User.class.php:updateCredits():  getCredits_available=".$dapuser->getCredits_available(),LOG_DEBUG_DAP);
		    logToFile("Dap_User.class.php:updateCredits():  creditsEarned=".$creditsEarned,LOG_DEBUG_DAP);
		    logToFile("Dap_User.class.php:updateCredits():  available=".$available,LOG_DEBUG_DAP);
		    logToFile("Dap_User.class.php:updateCredits():  earned=".$earned,LOG_DEBUG_DAP);
			*/
		  if((($creditsRemaining <= 0) || ($creditsRemaining == "")) && ($creditsEarned > 0)) {
		    logToFile("update creditsEarned=".$available,LOG_DEBUG_DAP);
		   $sql = "update dap_users 
				set	credits_available = $available
				where id =:id";
			 
			 //logToFile("Dap_User.class.php:updateCredits():  creditsEarned=".$creditsEarned,LOG_DEBUG_DAP);
			
			$stmt = $dap_dbh->prepare($sql);  
			//$stmt->bindParam(':creditsEarned', $creditsEarned, PDO::PARAM_INT);
			//$stmt->bindParam(':available', $available, PDO::PARAM_INT);
			//$stmt->bindParam(':earned',  $earned, PDO::PARAM_INT);
			
			$stmt->bindParam(':id', $this->getId(), PDO::PARAM_INT);			 
			$stmt->execute();
			//logToFile("Dap_User.class.php:updateCredits():  SQL=".$sql,LOG_DEBUG_DAP);
			$dapuser = Dap_User::loadUserById($this->getId());
			logToFile("Dap_User.class.php:updateCredits():  dapuser credits avail=".$dapuser->getCredits_available(),LOG_DEBUG_DAP);
		  }
		  else {
			  //logToFile("update creditsSpent",LOG_DEBUG_DAP);
		  $sql = "update 
				  dap_users 
			  set
				  credits_available = :creditsRemaining
			  where
				  id =:id";
				    
			 $stmt = $dap_dbh->prepare($sql);
	  	
			$stmt->bindParam(':creditsRemaining', $creditsRemaining, PDO::PARAM_INT);
			//logToFile("(Dap_User.update()) UPDATE creditsRemaining: ".$creditsRemaining);
		  
			$stmt->bindParam(':id', $this->getId(), PDO::PARAM_INT);
			$stmt->execute();
		  }
		  
		  //logToFile($sql,LOG_DEBUG_DAP);
		  
		 // logToFile("update complete",LOG_DEBUG_DAP);
		  $sql = null;
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
	
	public static function loadLoginHistory($userId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$IPList = array();
	
			$sql = "select 
						login_ip,
						time
					from 
						dap_users_logins ul,
						dap_users u
					where
						ul.user_id = :userId and
						ul.user_id = u.id
					order by
						ul.time desc
					";
			
			//logToFile("loadLoginHistory: sql: $sql"); 
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->execute();
			
			while ($obj = $stmt->fetch()) {
				$IPList[] = $obj;
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return $IPList;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	
	/*
		Send reset-password link to user's email id
	*/
	
	public static function sendResetPasswordLink($email, $code) {
		try {
			logToFile("in sendResetPasswordLink",LOG_DEBUG_DAP); 
			$dap_dbh = Dap_Connection::getConnection();
			//logToFile("Sending password reset link to user"); 
			$user = Dap_User::loadUserByActivationKey($code);
			$subject = "Password Reset Link - " . Dap_Config::get("SITE_NAME");
			$body = "This user has Unsubscribed...\n\nName: " . $user->getFirst_name() . " " . $user->getLast_name() . "\nEmail: " . $user->getEmail();
			sendAdminEmail($subject, $body);
			
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
		This function does the actual resetting of password, and sends them another link to a page
		where they can reset their password - http://YourSite.com/dap/setNewPassword.php
	*/
	
	public static function sendSetNewPasswordLink($email, $code) {
		try {
			logToFile("in sendSetNewPasswordLink",LOG_DEBUG_DAP); 
			$dap_dbh = Dap_Connection::getConnection();
			$sql = "update 
						dap_users 
					set
						opted_out = 'Y'
					where
						email = :email and
						activation_key = :code
						";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':email', $email, PDO::PARAM_STR);
			$stmt->bindParam(':code', $code, PDO::PARAM_INT);
			$stmt->execute();

			$stmt = null;
			
			//logToFile("Sending unsubscribe notification to admin"); 
			$user = Dap_User::loadUserByEmail($email);
			$subject = "Unsubscribe Notification";
			$body = "This user has Unsubscribed...\n\nName: " . $user->getFirst_name() . " " . $user->getLast_name() . "\nEmail: " . $user->getEmail();
			sendAdminEmail($subject, $body);
			
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
	
	
	public function hasAccessToHowManyExpiredProducts() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$count = 0;
			
			//Return total number of expired products
			$sql = "select
						count(*) as count
					from
						dap_users_products_jn
					where
						user_id = :userId and
						CURDATE() > access_end_date";
						
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $this->getId(), PDO::PARAM_INT);
			$stmt->execute();
			
			if ($row = $stmt->fetch()) {
				$count = $row["count"];
			}
			
			$sql = null;
			$stmt = null;
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
	
	
	/** Returns boolean */
	public static function isAccessAllowed($accountType, $actionType) {
		//Being passed "M" or "A"
		logToFile("accountType: $accountType , actionType: $actionType"); 
		$allowed = false;
		try {
			$dap_dbh = Dap_Connection::getConnection();
			if( !isset($accountType) || ($accountType == "") ) {
				return $user;
			}
			$sql = "SELECT 
						*
					FROM
						dap_roles r,
						dap_capabilities c,
						dap_roles_caps_jn rcj
					WHERE
						r.name = :accountType and
						c.name = :actionType and
						r.id = rcj.roles_id and
						c.id = rcj.caps_id and
						rcj.allowed = 'Y'
					";
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':accountType', $accountType, PDO::PARAM_STR);
			$stmt->bindParam(':actionType', $actionType, PDO::PARAM_STR);
			$stmt->execute();
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				//logToFile("allowed access",LOG_DEBUG_DAP);
				$allowed = true;
			}
			
			$row = null;
			$stmt = null;
			$dap_dbh = null;
			
			return $allowed;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}	


	/** Returns string */
	public static function toggleUserStatus($userId, $userStatus) {
		try {
			//logToFile("toggleUserStatus, userId: $userId"); 
			$dap_dbh = Dap_Connection::getConnection();
			$newUserStatus = "";
			$sql = "";
			$stmt = null;
			
			if($userStatus == "A") $newUserStatus = "I";
			if($userStatus == "I") $newUserStatus = "A";
			if($userStatus == "U") $newUserStatus = "A";
			if($userStatus == "N") $newUserStatus = "Y";
			if($userStatus == "Y") $newUserStatus = "N";
			
			if( ($userStatus == "A") || ($userStatus == "I") || ($userStatus == "U") ) {
				//Means updating user status
				$sql = "update
							dap_users
						set
							status = :newUserStatus
						where
							id = :id
						";
			}

			else if( ($userStatus == "Y") || ($userStatus == "N") ) {
				//Means updating optin status
				$sql = "update
							dap_users
						set
							opted_out = :newUserStatus
						where
							id = :id
						";
			}
			
			//logToFile("sql: $sql"); 
			//logToFile("userStatus: $userStatus , newUserStatus: $newUserStatus"); 
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':newUserStatus', $newUserStatus, PDO::PARAM_STR);
			$stmt->bindParam(':id', $userId, PDO::PARAM_INT);
			$stmt->execute();
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return "User info successfully updated";
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			return $e->getMessage();
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			return $e->getMessage();
		}
	}	


	/** Returns string */
	public static function toggleOptinStatus($userId, $userStatus) {
		try {
			//logToFile("toggleOptinStatus, userId: $userId"); 
			$dap_dbh = Dap_Connection::getConnection();
			$newUserStatus = "";
			$sql = "";
			$stmt = null;
			
			if($userStatus == "Y") $newUserStatus = "N";
			if($userStatus == "N") $newUserStatus = "Y";

			$sql = "update
						dap_users
					set
						opted_out = :newUserStatus
					where
						id = :id
					";
			//logToFile("sql: $sql"); 
			//logToFile("userStatus: $userStatus , newUserStatus: $newUserStatus"); 
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':newUserStatus', $newUserStatus, PDO::PARAM_STR);
			$stmt->bindParam(':id', $userId, PDO::PARAM_INT);
			$stmt->execute();
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null; 
			
			return "User info successfully updated";
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			return $e->getMessage();
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			return $e->getMessage();
		}
	}	


}
?>