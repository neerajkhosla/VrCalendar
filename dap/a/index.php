<?php
	include_once "../dap-config.php";
	
	$a = isset($_REQUEST['a']) ? $_REQUEST['a'] : ""; //a = affiliate id
	$p = isset($_REQUEST['p']) ? $_REQUEST['p'] : ""; //p = URL to which user to be redirected - any valid URL
	
	//logToFile("aff: p: $p  , a: $a"); 
	
	//If $p is set, redirect to that page - else redir to site home page
	if($p == "") {
		$p = Dap_Config::get('DEFAULT_AFF_LANDING');
		if($p == "") {
			$p = SITE_URL_DAP;
		}
	} else if(substr($p, 0, 7) != "http://") {
		$p = "http://" . $p;
	}
	
	//echo Dap_Config::get("NO_FREE_AFFILIATES"); exit;
						  
	//Store affiliate id in cookie
	if($a != "") { //set cookie for one year : 31536000 seconds
		//First, do some checks on affiliate
		$user = Dap_User::loadUserById($a); //Load User
		//echo $user->isPaidUser(); exit;
		if( 
		   	(!isset($user)) //Either user is not set 
			|| 
			( (Dap_Config::get("NO_FREE_AFFILIATES") == "Y") && (!$user->isPaidUser()) ) //No free affiliates
			||
			( $user->getIs_affiliate() == "N" ) //Affiliate feature disabled
		)
		{
			//echo "in here"; exit;
			header( "HTTP/1.1 301 Moved Permanently" ); 
			header("Location: $p");
			exit;
		}		
		
		//Checks are ok, now set affiliate cookie
		//AFF_COOKIE_LENGTH in days multiplied by 24 * 60 * 60 to convert into seconds
		$cookieLifetime = intval(Dap_Config::get('AFF_COOKIE_LENGTH')) * 24 * 60 * 60;
		
		/**
			If last-cookie, then blindly create-new or overwrite-existing cookie
			If first-cookie, then set only if no existing cookie
		*/
		$firstOrLast = Dap_Config::get('FIRST_LAST_COOKIE');
		//logToFile("firstOrLast: " . $firstOrLast); 
		
		if($firstOrLast == "Last-Cookie") {
			setcookie("dapa",$a,time()+$cookieLifetime,"/",str_replace("www.", "", "." . $_SERVER['HTTP_HOST']));
		} else if($firstOrLast == "First-Cookie") {
			if(!isset($_COOKIE['dapa'])) {
				setcookie("dapa",$a,time()+$cookieLifetime,"/",str_replace("www.", "", "." . $_SERVER['HTTP_HOST']));
			}
		}
		
		//write affiliate referral details into db
		$http_referer = "";
		$ip = getIpOfUser();
		$useragent = $_SERVER['HTTP_USER_AGENT'];
		
		if(isset($_SERVER['HTTP_REFERER'])) {
			$http_referer = $_SERVER['HTTP_REFERER'];
		}
		
		Dap_AffStats::saveAffiliateStats($a, $http_referer, date("Y-m-d H:i:s"), $useragent, $ip, $p);

	}
	header( "HTTP/1.1 301 Moved Permanently" );
	header("Location: $p");
?>