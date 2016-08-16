<?php 
/* 
	Plugin Name: DigitalAccessPass LiveLinks
	Plugin URI: http://www.DigitalAccessPass.com
	Description: DigitalAccessPass is a Premium Membership Plugin for WordPress that can "Drip" WordPress Posts, Pages, Categories and Custom Post Types, has a built-in Shopping Cart, Email Autoresponders & Broadcasting, Affiliate Program, File Protection and lots more!
	Author: DigitalAccessPass.com
	Author URI: http://www.DigitalAccessPass.com
	Version: 1.11.2
*/

/*
	For sites that have a sub-domain problem, do the following:
	
	1. Go to the folder where dap is supposed to be installed. Do a getpath in that folder. Get the path (let's call it NEWPATH)
	2. Add these two lines of code somewhere towards the middle, after any existing similar if(!defined...) statement, within wp-config.php
		if ( !defined('SITEROOT') )
			define('SITEROOT', '/home/content/html');
	3. Try to activate LiveLinks.
	4. If you get an error saying NEWPATH/dap/dap-config.php cannot be found, 
		then create dap-config.php manually by copying db settings from wp-config.php, 
		and upload to dap folder, and now try activating LiveLinks again.
	
	That's it!
*/

require_once(ABSPATH.'wp-includes/pluggable.php');
$lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
if( isset($_SESSION["lldocroot"]) && ($_SESSION["lldocroot"] != "") ) {
  //logToFile("DAP-LiveLinks-session, docroot=".$_SESSION["lldocroot"]); 
  $lldocroot = $_SESSION["lldocroot"];
}

if(file_exists($lldocroot . "/dap/dap-config.php")) include_once ($lldocroot . "/dap/dap-config.php");
require_once ($lldocroot . "/dap/inc/classes/Dap_Base.class.php");
require_once ($lldocroot . "/dap/inc/classes/Dap_Session.class.php");
require_once("DAP-LoginLogout.php");
require_once("DAP-Product-Links.php");
require_once("DAP-ShortCodes.php");
require_once("DAP-ShortCodes-UserLinks.php");
require_once("DAP-ShortCodes-ComingSoon.php");
require_once("DAP-ShortCodes-UserProfile.php");
require_once("DAP-ShortCodes-LoginForm.php");
require_once("DAP-Upline.php");
require_once("DAP-Auto-Cancellation.php");
require_once("DAP-ShortCodes-Transactions.php");
require_once("DAP-User-Subscription.php");
require_once("DAPUpgradeButton.php");
require_once("DAP-ShortCodes-RefAffId.php");
require_once("DAP-1ClickBuy.php");

global $current_user;

add_action('init', 'figureOutWPConfigPath');
function figureOutWPConfigPath() {
	$current_user = wp_get_current_user();
	if(!isset($_SESSION["wpconfigpath"]))
		$_SESSION["wpconfigpath"] = ABSPATH.'wp-config.php';
}

if( isset($_GET['msg']) && ($_GET['msg'] != "") ) {
	if( defined($_GET['msg']) ) {
		$_GET['msg'] = constant($_GET['msg']);
	} else {
		$_GET['msg'] = str_replace("_"," ",$_GET['msg']);
	}
	if(isset($_GET['email'])) $_GET['msg'] = $_GET['msg'] . " " . $_SESSION['email'];
}

//Auto-login DAP Admin (with min id) if WP Admin logs in
add_action( 'admin_init', 'dapAdminLogin' );
function dapAdminLogin() {
	//$dapcron = Dap_Config::get("DAP_WP_CRON"); //needed for wpengine
	//if($dapcron=="Y") {
		//register_setting('myplug_options_group', 'myplug_settings', 'myplug_validate');
	//}
	global $current_user;
	//logToFile("current_user->ID: " . $current_user->ID); 

	if( is_admin() && user_can($current_user->ID, 'administrator') ) {
		global $lldocroot;
		$configFileName = $lldocroot . "/dap/dap-config.php";
		if( (file_exists($configFileName)) && (!Dap_Session::isLoggedIn()) ) {
			include_once ($configFileName);
			Dap_Config::loadConfig(true);

			if (Dap_Config::get("AUTOLOGIN_DAPWP_ADM") == "Y") {
				//logToFile("You are a WP Admin, AUTOLOGIN_DAPWP_ADM = 'Y', so logging you in..."); 
				$userAdmin = Dap_User::loadAdminUserByMinId();
				$email = $userAdmin->getEmail();
				$rawPassword = $userAdmin->getPassword();
				$decryptedPassword = decryptPassword($rawPassword);
				$encryptedPassword = encryptPassword($rawPassword);
				//logToFile("in LiveLinks: email: $email , rawPassword: $rawPassword , encryptedPassword: $encryptedPassword"); 
				
				//if(validate($email, $rawPassword) || validate($email, $decryptedPassword)) {
				if( validate($email, $rawPassword) ) {
					//logToFile("WP Admin validated ok in DAP Admin in LiveLinks"); 
					$rememberMe = "rememberMe";
					//Set cookie only if rememberMe is set. If not, no cookie is set
					//Set cookie for 2 weeks
					//logToFile("remembering...",LOG_DEBUG_DAP);
					$_SESSION["dapRememberMe"] = "dapRememberMe";
					$i = 14;
					setcookie("dapcookie_email",$email,time()+(3600*24*$i),"/");
					setcookie("dapcookie_password",$encryptedPassword,time()+(3600*24*$i),"/");
					pluginLogin($email);
				}
			}
		}
	}
}


function dap_filter_posts($posts) {
	global $current_user;
	
	//return if admin.
	//logToFile("------------------------- in dap_filter_posts"); 
	//if(is_admin()) return $posts;
	if(is_admin() && user_can($current_user->ID, 'administrator')) return $posts;
	
	if(is_feed()) {
		//logToFile("I'm a feed");
		$key = ( isset($_GET['key']) && ($_GET['key'] != "") ) ? stripslashes($_GET['key']) : "";
		if($key != "") {
			//Someone with a legit key trying to access the feed. So load user & determine access
			//logToFile("key: $key");
			$user = Dap_User::loadUserByActivationKey($key);
			if( isset($user) && ($user != null) ) {
				$session = Dap_Session::getSession();
				if( validateLogins($user->getEmail(), getIpOfUser()) ) {
					//logToFile("user is " . $user->getEmail());
					//$session->setUser($user);
					define('DAP_FEED_USER_ID',$user->getId());
				} else {
					return null;
				}
			}
		} //end if($key != "")
		/* else, if isfeed but no key, then just keep going and filter out everything */
	}
	
	
	global $lldocroot;
	include_once ($lldocroot . "/dap/dap-config.php");
	dap_wp_sync();

	$sneak_peek = Dap_Config::get("SNEAK_PEEK");
	
	//If sneak-peek is on, is category and there's only 1 post in the array (like home page or archive page or category page)
	$size = count($posts);
	//logToFile("1) size: $size");
	
	if( is_category() && ($sneak_peek == "Y") && ($size = 1)  && !is_feed() ) return $posts;
	
	
	//if ( false ) {
	if ( is_category()  && ($sneak_peek == "N") ) {
		//logToFile("is category and sneak peek is N"); 
		//If error page URL is set, just redirect to that URL
		global $wp_query;
		$cat_obj = $wp_query->get_queried_object();
		//logToFile( $cat_obj->name . " has id ". $cat_obj->term_id );

		$cat_permalink = get_category_link($cat_obj->term_id);
		$cat_permalink = getResourceFromString($cat_permalink);
		//logToFile("cat_permalink: $cat_permalink"); 
		
		$resource = isURLAllowed($cat_permalink);
		$errId = $resource->getError_id();
		if(isset($errId)) {
			//logToFile("Protected category"); 
			//protected category
			$errorPageURL = Dap_Config::get("SITEWIDE_ERROR"); //initially point to global error page
			//logToFile("errorPageURL: " . $errorPageURL); 
			$productCount = Dap_Product::isPartOfHowManyDistinctProducts($cat_permalink);
			//logToFile("category permalink: productCount: $productCount");
			if( $productCount == 1 ) {
				//logToFile("productCount is 1"); 
				$product = Dap_Product::getProductDetailsByResource($cat_permalink);
				$productErrorPageURL = $product->getError_page_url();
				if ( ($productErrorPageURL != "") && ($productErrorPageURL != "/dap/product-error.php") )  {
					$errorPageURL = $productErrorPageURL; 
				}
			}			
			
			//logToFile("errorPageURL: $errorPageURL"); 
			if ( ($errorPageURL != "") && ($errorPageURL != "/dap/product-error.php") )  {
				//Redirect to product-specific error page
				//logToFile("Redirecting to product-specific error page " . $errorPageURL); 
				@header( "Location: " . $errorPageURL );
				exit;
			}
		} 
		
	}

	//If sneak-peek is on, and there's more than 1 post in the array (like home page or archive page or category page)
	$size = count($posts);
	//logToFile("2) size: $size");
	if( ($sneak_peek == "Y") && ($size > 1)  && !is_feed() ) return $posts;
	
	
	$permalink = null;
	$unsetAtleastOne = false;
	$session = Dap_Session::getSession();
	$user = $session->getUser();
	$errId = null;
	
	for ($i=0; $i < $size; $i++) {
		//logToFile("Going through individual posts"); 
		$postId = $posts[$i]->ID;
		$permalink = get_permalink($postId);
		$permalink = getResourceFromString($permalink);
		//logToFile("Permalink: $permalink",LOG_DEBUG_DAP); 
		$resource = isURLAllowed($permalink);
		$errId = $resource->getError_id();
		
		if(isset($errId)) {
			//something wrong with link, unset and continue
			if($sneak_peek == "N") {
				//logToFile("Removing post $i : " . $posts[$i]->ID); 
				unset($posts[$i]);
				$unsetAtleastOne = true;
			} else {
				//logToFile("There is an error and Sneak peek is Y");
				$beforeMore = strpos($posts[$i]->post_content, '<!--more-->');
				$content = substr($posts[$i]->post_content,0,$beforeMore);
				//logToFile("Content: " . $content); 
				$posts[$i]->post_content = trim($content);
			}
			continue;
		}
		
		if($user != NULL && isset($user)) {
			Dap_Resource::isResourceClickCountOK($user->getId(), $permalink);
		}		
		
		//Now, let's also check the categories to which this post belongs to, to see 
		//if in case maybe the post itself is not protected, but the category it belongs to is protected
		$categories = wp_get_post_categories($posts[$i]->ID);
      	foreach($categories as $cat_id) {
           $cat_permalink = get_category_link($cat_id);
           $cat_permalink = getResourceFromString($cat_permalink);
			$resource = isURLAllowed($cat_permalink);	
			$errId = $resource->getError_id();
			if(isset($errId)) {
				//remove post as there is error
				//logToFile("post itself not protected, but category is"); 
				if($sneak_peek == "N") {
					unset($posts[$i]);
					$unsetAtleastOne = true;
				} else {
					//logToFile("Category: There is an error and Sneak peek is Y");
					$beforeMore = strpos($posts[$i]->post_content, '<!--more-->');
					$content = substr($posts[$i]->post_content,0,$beforeMore);
					//logToFile("Content: " . $content); 
					$posts[$i]->post_content = trim($content);
				}
				continue 2;
			}
			//if it is part of at least one category (isURLAllowed generates no error), 
			//then don't check other categories - simply exit the immediate loop
			break;
      	}
	} //end for
	
	
	//if this is only one post/page and we have error code, then it means user may not have access to it
	if( isset($errId) && ($size == 1) ) {
		//Only two things to possibly do: 1) Replace or 2) Redirect
		//Replace if sneak-peek is Y - Don't even look at any redirection
		//logToFile("Single Post/Page"); 
		if($sneak_peek == "Y") { //Sneak-Peek is yes, means need to call dap_sanitize_post
			//logToFile("---------------- 1) Sneak Peek is Y"); 
			//dap_sanitize_post($posts[0], $resource, $permalink );
			//dap_sanitize_post($post0 = $posts[0], $resource, $permalink );
			//$post0 = $posts[0];
			//$posts[0] = dap_sanitize_post($post0 = $posts[0], $resource, $permalink );
			$posts[0] = dap_sanitize_post($posts[0], $resource, $permalink );			
		} else if($sneak_peek == "N") {
			//logToFile("---------------- 1) Sneak Peek is N"); 
			//If error page is empty/default, then REPLACE
			//Else if error page is anything BUT empty/default, then REDIRECT
			$errorPageURL = Dap_Config::get("SITEWIDE_ERROR"); //initially point to global error page
			$productCount = Dap_Product::isPartOfHowManyDistinctProducts($permalink);
			//logToFile("productCount: $productCount");
			if( $productCount == 1 ) {
				$product = Dap_Product::getProductDetailsByResource($permalink);
				if( isset($product) && !is_null($product) ) {
					$errorPageURL = $product->getError_page_url();
				}
			}			
			
			//logToFile("errorPageURL: $errorPageURL"); 
			if ( ($errorPageURL == "") || ($errorPageURL == "/dap/product-error.php") )  {//Empty/default, so do REPLACE
				//logToFile("errorPageURL is blank"); 
				//dap_sanitize_post($posts[0], $resource, $permalink );
				//dap_sanitize_post($post0 = $posts[0], $resource, $permalink );
				//$post0 = $posts[0];
				$posts[0] = dap_sanitize_post($posts[0], $resource, $permalink );
			} else {
				//Redirect to product-specific error page
				//logToFile("Redirecting to error page: $errorPageURL");
				@header( "Location: " . $errorPageURL . "?request=" . urlencode($_SERVER['REQUEST_URI']));
				exit;
			}
		}
	}
	
	//Make sure the array index is reset properly. 
	if (!is_feed()) {
		$posts = @array_merge($posts);
	}
	$size = count($posts);
	
	//logToFile("size: $size"); 
	
	if( ($size == 0) && $unsetAtleastOne ) {
		//logToFile("---------- About to createPost"); 
		//No posts were found
		global $wp;
		global $wp_query;
		
		$errorPageURL = Dap_Config::get("SITEWIDE_ERROR");
		
		if(is_null($errorPageURL) || ($errorPageURL == "")) {
			$posts = NULL;
        	$posts[] = createPost();
		} else {
			@header( "Location: " . $errorPageURL . "?request=" . urlencode($_SERVER['REQUEST_URI']));
			exit;		
		}
	}
	//logToFile("-------------------------------- about to return from dap_filter_posts"); 	
	return $posts;

} //end function

//New function since 4.3 to filter out menu links
function dap_filter_posts_menu($posts) {
	//return if admin.
	//logToFile("######################### in dap_filter_posts_menu"); 
	if(is_admin()) return $posts;
	
	global $lldocroot;
	include_once ($lldocroot . "/dap/dap-config.php");
	$sneak_peek = Dap_Config::get("SNEAK_PEEK");
	$showProtectedLinksInMenu = Dap_Config::get("SHOW_PROT_MENULINKS");
	
	// No filtering is done if Sneak Peek=Y or showProtectedLinksInMenu=N
	if(($showProtectedLinksInMenu == "Y") || ($sneak_peek == "Y")) {
		//logToFile("dap_filter_posts_menu: showProtectedLinksInMenu = Y or sneak peek = Y - so returning"); 
		return $posts;
	}	
	
	//If sneak-peek is on, is category and there's only 1 post in the array (like home page or archive page or category page)
	$size = count($posts);
	//logToFile("1) size: $size");
	
	if( is_category() && ($sneak_peek == "Y") && ($size = 1)  && !is_feed() ) return $posts;
	
	if ( is_category()  && ($sneak_peek == "N") ) {
		//If error page URL is set, just redirect to that URL
		global $wp_query;
		$cat_obj = $wp_query->get_queried_object();
		//logToFile( $cat_obj->name . " has id ". $cat_obj->term_id );

		$cat_permalink = get_category_link($cat_obj->term_id);
		$cat_permalink = getResourceFromString($cat_permalink);
		//logToFile("cat_permalink: $cat_permalink"); 
		
		$resource = isURLAllowed($cat_permalink);
		$errId = $resource->getError_id();
	}

	//If sneak-peek is on, and there's more than 1 post in the array (like home page or archive page or category page)
	$size = count($posts);
	//logToFile("2) size: $size");
	if( ($sneak_peek == "Y") && ($size > 1)  && !is_feed() ) return $posts;
	
	$permalink = null;
	$unsetAtleastOne = false;
	$session = Dap_Session::getSession();
	$user = $session->getUser();
	$errId = null;
	
	for ($i=0; $i < $size; $i++) {
		$postId = $posts[$i]->ID;
		$permalink = get_permalink($postId);
		$permalink = getResourceFromString($permalink);
		//logToFile("Permalink: $permalink",LOG_DEBUG_DAP); 
		$resource = isURLAllowed($permalink);
		$errId = $resource->getError_id();
		
		if(isset($errId)) {
			//something wrong with link, unset and continue
			if($sneak_peek == "N") {
				//logToFile("Removing post $i : " . $posts[$i]->ID); 
				unset($posts[$i]);
				$unsetAtleastOne = true;
			}
			continue;
		}
		
		if($user != NULL && isset($user)) {
			Dap_Resource::isResourceClickCountOK($user->getId(), $permalink);
		}		
		
		//Now, let's also check the categories to which this post belongs to, to see 
		//if in case maybe the post itself is not protected, but the category it belongs to is protected
		$categories = wp_get_post_categories($posts[$i]->ID);
      	foreach($categories as $cat_id) {
           $cat_permalink = get_category_link($cat_id);
           $cat_permalink = getResourceFromString($cat_permalink);
			$resource = isURLAllowed($cat_permalink);	
			$errId = $resource->getError_id();
			if(isset($errId)) {
				//remove post as there is error
				//logToFile("post itself not protected, but category is"); 
				if($sneak_peek == "N") {
					unset($posts[$i]);
					$unsetAtleastOne = true;
				}
				continue 2;
			} 
      	}
	} //end for
	
	
	//if this is only one post/page and we have error code, then it means user may not have access to it
	if( isset($errId) && ($size == 1) ) {
		//Only two things to possibly do: 1) Replace or 2) Redirect
		//Replace if sneak-peek is Y - Don't even look at any redirection
		//logToFile("Single Post/Page"); 
		if($sneak_peek == "Y") { //Sneak-Peek is yes, means need to call dap_sanitize_post
			//logToFile("---------------- 1) Sneak Peek is Y"); 
			//dap_sanitize_post($posts[0], $resource, $permalink );
			//dap_sanitize_post($post0 = $posts[0], $resource, $permalink );
			$post0 = $posts[0];
			$posts[0] = dap_sanitize_post($post0 = $posts[0], $resource, $permalink );
		} else if($sneak_peek == "N") {
			//do nothing
		}
	}
	
	//logToFile("########################### about to return from dap_filter_posts_menu"); 	
	//logToFile(""); 	
	return $posts;
	

} //end function


function createPost() {
	
	//logToFile("In create post"); 
	
	//If error page URL is set, just redirect to that URL
	global $wp_query;
	$cat_obj = $wp_query->get_queried_object();
	//logToFile( $cat_obj->name . " has id ". $cat_obj->term_id );

	$cat_permalink = get_category_link($cat_obj->term_id);
	$permalink = getResourceFromString($cat_permalink);
	//logToFile("cat_permalink: " . $cat_permalink); 
	
	
	$lldocroot = "";
	if( defined('SITEROOT') ) {
		$lldocroot = SITEROOT;
	} else {
		$lldocroot = $_SERVER['DOCUMENT_ROOT'];
	}
	include_once ($lldocroot . "/dap/dap-config.php");
	$output = "";
	$redirectURL = "";
	$salesPageURL = Dap_Config::get("SALES_PAGE_URL"); //global SPU
	$loggedInURL = Dap_Config::get("LOGGED_IN_URL");
	
	if($salesPageURL == "") $salesPageURL = "/";
	if($loggedInURL == "") $loggedInURL = "/";
	
	if ( isset($_GET['request']) && ($_GET['request'] != "") ) {
		//request is available in query string
		$redirectURL = $_GET['request'];
	} else {
		//No 'request' available. Check if this is the home page.
		//If not home page, then whatever is the URL being visited, is the redirectURL
		wp_reset_query();
		if (!is_home() && !is_front_page()) {
			$redirectURL = $_SERVER['REQUEST_URI'];
		}
	}
	
	
	if(Dap_Session::isLoggedIn()) {//LOGGED IN
		//logToFile("Logged in"); 
		$session = Dap_Session::getSession();
		$user = $session->getUser();		
		
		$errorHTMLPath = $lldocroot . "/dap/inc/error-loggedin.php";
		//Check for custom error page
		if( file_exists($lldocroot . "/dap/inc/customerror-loggedin.php") ) {
			$errorHTMLPath = $lldocroot . "/dap/inc/customerror-loggedin.php";
		}
		
		//$productCount = $user->hasAccessToHowManyDistinctProducts();
		$productCount = Dap_Product::isPartOfHowManyDistinctProducts($permalink);
		//logToFile("productCount: $productCount");
		if( $productCount == 1 ) {
			$product = Dap_Product::getProductDetailsByResource($permalink);
			if(!is_null($product) && isset($product)) {
				if ($product->getSales_page_url() != "") {
					$salesPageURL = $product->getSales_page_url();
				}
				if ($product->getLogged_in_url() != "") {
					$loggedInURL = $product->getLogged_in_url();
				}
			}
		}
		
		$output = replaceFillers($errorHTMLPath,"%%LOGIN_URL%%",Dap_Config::get("LOGIN_URL"));
		$output = str_replace("%%REQUEST%%",$redirectURL,$output);
		$output = str_replace("%%LOGGED_IN_URL%%",$loggedInURL,$output);
		$output = str_replace("%%SALES_PAGE_URL%%",$salesPageURL,$output);
		//logToFile("About to return");
	} 
	
	else {//NOT LOGGED IN
		//logToFile("NOT Logged in createPost"); 
		$errorHTMLPath = $lldocroot . "/dap/inc/error-notloggedin.php";
		//Check for custom error page
		if( file_exists($lldocroot . "/dap/inc/customerror-notloggedin.php") ) {
			$errorHTMLPath = $lldocroot . "/dap/inc/customerror-notloggedin.php";
		}
		
		$productCount = Dap_Product::isPartOfHowManyDistinctProducts($permalink);
		//logToFile("productCount: $productCount");
		if( intval($productCount) == 1 ) {
			$product = Dap_Product::getProductDetailsByResource($permalink);
			if ($product->getSales_page_url() != "") {
				$salesPageURL = $product->getSales_page_url();
			}
			if ($product->getLogged_in_url() != "") {
				$loggedInURL = $product->getLogged_in_url();
			}
		}
		
		$output = replaceFillers($errorHTMLPath,"%%LOGIN_URL%%",Dap_Config::get("LOGIN_URL"));
		$output = str_replace("%%REQUEST%%",$redirectURL,$output);
		$output = str_replace("%%LOGGED_IN_URL%%",$loggedInURL,$output);
		$output = str_replace("%%SALES_PAGE_URL%%",$salesPageURL,$output);
		$output = str_replace("%%LOGIN_FORM%%",dap_login("%%LOGIN_FORM%%",$redirectURL),$output);
		
		//logToFile("output: $output"); 
		
		//add_filter("comments_template", "dap_incl_nothing_file", 10);
		//add_action('init','less_filters',20);
	}
	
	//logToFile("creating temp post"); 
	//$output = mb_convert_encoding($output, "UTF-8", "auto");
	
	$post = new stdClass;
	$post->post_author = 1;
	$post->post_name = "error";
	$post->guid = get_bloginfo('wpurl');
	$post->post_title = "For Members Only";
	$post->post_content = $output;
	$post->ID = -1;
	//$post->post_status = 'static';
	$post->comment_status = 'closed';
	//$post->ping_status = $this->ping_status;
	$post->comment_count = 0;
	$post->post_date = current_time('mysql');
	$post->post_date_gmt = current_time('mysql', 1);
	return($post);		
}

function dap_sanitize_post(&$post, $resource, $permalink ) {
	//Only time this gets called is when a permalink (single post/page) is accessed
	//logToFile("in dap_sanitize_post"); 
	$lldocroot = "";
	if( defined('SITEROOT') ) {
		$lldocroot = SITEROOT;
	} else {
		$lldocroot = $_SERVER['DOCUMENT_ROOT'];
	}
	
	include_once ($lldocroot . "/dap/dap-config.php");
	$results = get_extended($post->post_content);
	
	//logToFile("Post author: " . $post->post_author); 
	$main = $results['main'];
	
	$sneak_peek = Dap_Config::get("SNEAK_PEEK");
	if($sneak_peek == "Y") {
		if ( $post->post_excerpt ) {
			$main = $post->post_excerpt;
		}
	}
	
	$output = "";
	$redirectURL = "";
	$salesPageURL = Dap_Config::get("SALES_PAGE_URL"); //global SPU
	$loggedInURL = Dap_Config::get("LOGGED_IN_URL");
	
	if($salesPageURL == "") $salesPageURL = "/";
	if($loggedInURL == "") $loggedInURL = "/";
	
	if ( isset($_GET['request']) && ($_GET['request'] != "") ) {
		//request is available in query string
		$redirectURL = $_GET['request'];
	} else {
		//No 'request' available. Check if this is the home page.
		//If not home page, then whatever is the URL being visited, is the redirectURL
		//logToFile("No request available, so redirect url is: " . $_SERVER['REQUEST_URI']); 
		wp_reset_query();
		if (!is_home() && !is_front_page()) {
			$redirectURL = $_SERVER['REQUEST_URI'];
		}
	}
	
	if(Dap_Session::isLoggedIn()) {//LOGGED IN
		//logToFile("Logged in"); 
		$session = Dap_Session::getSession();
		$user = $session->getUser();		
		
		$errorHTMLPath = $lldocroot . "/dap/inc/error-loggedin.php";
		//Check for custom error page
		if( file_exists($lldocroot . "/dap/inc/customerror-loggedin.php") ) {
			$errorHTMLPath = $lldocroot . "/dap/inc/customerror-loggedin.php";
		}
		//logToFile("errorHTMLPath: $errorHTMLPath"); 
		
		//$productCount = $user->hasAccessToHowManyDistinctProducts();
		$productCount = Dap_Product::isPartOfHowManyDistinctProducts($permalink);
		//logToFile("productCount: $productCount");
		if( $productCount == 1 ) {
			$product = Dap_Product::getProductDetailsByResource($permalink);
			//logToFile("Back from Dap_Product:getProductDetailsByResource"); 
			//logToFile("product name: " . $product->getName()); 
			
			if(isset($product)) {
				if ($product->getSales_page_url() != "") {
					$salesPageURL = $product->getSales_page_url();
				}
				if ($product->getLogged_in_url() != "") {
					$loggedInURL = $product->getLogged_in_url();
				}
			}
		}
		
		$output = replaceFillers($errorHTMLPath,"%%LOGIN_URL%%",Dap_Config::get("LOGIN_URL"));
		$output = str_replace("%%REQUEST%%",$redirectURL,$output);
		$output = str_replace("%%LOGGED_IN_URL%%",$loggedInURL,$output);
		$output = str_replace("%%SALES_PAGE_URL%%",$salesPageURL,$output);
		$output = str_replace("%%ERROR_MSG%%",$resource->getError(0),$output);
		//logToFile("About to return"); 
	} 
	
	else {//NOT LOGGED IN
		//logToFile("Not logged in");
		$errorHTMLPath = $lldocroot . "/dap/inc/error-notloggedin.php";
		//Check for custom error page
		if( file_exists($lldocroot . "/dap/inc/customerror-notloggedin.php") ) {
			$errorHTMLPath = $lldocroot . "/dap/inc/customerror-notloggedin.php";
		}
		
		$productCount = Dap_Product::isPartOfHowManyDistinctProducts($permalink);
		//logToFile("productCount: $productCount");
		if( intval($productCount) == 1 ) {
			$product = Dap_Product::getProductDetailsByResource($permalink);
			if ($product->getSales_page_url() != "") {
				$salesPageURL = $product->getSales_page_url();
			}
			if ($product->getLogged_in_url() != "") {
				$loggedInURL = $product->getLogged_in_url();
			}
		}
		
		$output = replaceFillers($errorHTMLPath,"%%LOGIN_URL%%",Dap_Config::get("LOGIN_URL"));
		$output = str_replace("%%REQUEST%%",$redirectURL,$output);
		$output = str_replace("%%LOGGED_IN_URL%%",$loggedInURL,$output);
		$output = str_replace("%%SALES_PAGE_URL%%",$salesPageURL,$output);
		$output = str_replace("%%LOGIN_FORM%%",dap_login("%%LOGIN_FORM%%",$redirectURL),$output);
	}
	
	//logToFile("output: $output"); 
	//$output = mb_convert_encoding($output, "UTF-8", "auto");
	$main .= $output;
	if(is_array($post)){  
		$post['post_content'] = $main; 
	}    
	else if (isset($post)) {
		$post->post_content = $main;
	}
	else {
		$post = new StdClass;
		$post->post_content = $main;
	}
	
	add_filter("comments_template", "dap_incl_nothing_file", 10);
	//logToFile("returning from dap_sanitize_post"); 
	return $post;
}

function dap_incl_nothing_file() {
	if( Dap_Config::get('SHOW_COMMENTS') == "Y" ) {
		return;
	}
	//If "" or "N", return blank file
	return dirname(__FILE__) . '/do-nothing.php';
	
}

function dap_livelinks_activate() {
	//This deliberately uses dap-settings.php (instead of dap-config.php)
	//because when LL is being activated, dap-config.php does not exist yet.
	
	//global $lldocroot;
	//include_once ($lldocroot . "/dap/dap-settings.php");	
	
	if( defined('SITEROOT') ) {
		include_once (SITEROOT . "/dap/dap-settings.php");	
	} else {
		include_once ($_SERVER['DOCUMENT_ROOT'] . "/dap/dap-settings.php");
	}	
	
	$site_url = get_option("siteurl");
	$admin_email = get_option("admin_email");
	$list = parse_url($site_url);
	$site_url = $list['scheme']."://".$list['host'];
	$dap_install_result = dap_install(DB_NAME, DB_USER, DB_PASSWORD, DB_HOST, $site_url, "admin", $admin_email);
	$var = "<h3 style='color:#C00'>" . $dap_install_result[1] . " (" . $dap_install_result[0] . ") </h3>";
	update_option('dap_install_result', $var);
}

if(get_option('dap_install_result')) {
	function dap_warning() {
		$var = get_option('dap_install_result');
		echo "
		<div id='dap-warning' class='updated fade'><p>".$var."</p></div>
		";
		delete_option('dap_install_result');

	}
	add_action('admin_notices', 'dap_warning');
}


function dap_mod_rewrite($rules) { 
	$rules = str_replace("</IfModule>\n","",$rules);
	$rules .= "\n\n#----- START DAP -----\n";
	$rules .= "RewriteCond %{REQUEST_FILENAME} -f \n";
	$rules .= "RewriteCond %{REQUEST_FILENAME} !-d \n";
	$rules .= "RewriteCond %{REQUEST_FILENAME} (.*)/wp-content/uploads/(.*) \n";
	$rules .= "RewriteCond %{REQUEST_FILENAME} !(.*)(\.php|\.css|\.js|\.jpg|\.gif|\.png|\.txt|\.ico|\.jpeg)$ \n";
	$rules .= "RewriteRule (.*) /dap/client/website/dapclient.php?dapref=%{REQUEST_URI}&plug=wp&%{QUERY_STRING}  [L] \n";
	$rules .= "#----- END DAP -----\n\n";
	$rules .= "</IfModule>\n";
	return $rules;
}


//This function replaces %%LOGIN_FORM%% tags with actual form code
function dap_login($data,$redirectURL="") {
	if (strpos($data,'%%LOGIN_FORM') === false) return $data;
	
	$lldocroot = "";
	if( defined('SITEROOT') ) {
		$lldocroot = SITEROOT;
	} else {
		$lldocroot = $_SERVER['DOCUMENT_ROOT'];
	}
	include_once ($lldocroot . "/dap/dap-config.php");	
	
	//logToFile("data: $data",LOG_DEBUG_DAP); 
	//$redirectURL = "";
	
	//if (strpos($data,'%%LOGIN_FORM%%')!== false) {//found the string - so specific to login page
	$session = Dap_Session::getSession();
	$signup_page = Dap_Config::get("SITE_URL_DAP");
	//logToFile("in here",LOG_DEBUG_DAP); 
	
	//$redirectURL = "";
	
	if ( isset($_GET['request']) && ($_GET['request'] != "") && ($redirectURL == "") ) {
		//request is available in query string
		//logToFile("There's a request URL: " . $_GET['request']); 
		$redirectURL = $_GET['request'];
	} 
	
	//else {
		//No 'request' available. Check if this is the home page.
		//If not home page, then whatever is the URL being visited, is the redirectURL
		//logToFile("here...."); 
		//wp_reset_query();
		//if (!is_home() && !is_front_page()) {
			//logToFile("not is_home"); 
			//$redirectURL = $_SERVER['REQUEST_URI'];
		//}
	//}
	
	//logToFile("redirectURL: $redirectURL"); 
	//logToFile("Current page where %%LOGIN_FORM%% is getting triggered from: " . $_SERVER['REQUEST_URI']); 
	//$httpURL = "http://" . str_replace("www.","",$_SERVER['HTTP_HOST']) . $_SERVER['REQUEST_URI'];
	
	//$loginURL = str_replace("www.","",Dap_Config::get("LOGIN_URL"));
	//logToFile("loginURL: " . $loginURL);
	//logToFile("httpURL: " . $httpURL);
	//logToFile("trim(httpURL): " . trim($httpURL,"/"));
	//logToFile("request_uri: " . $_SERVER['REQUEST_URI']);
	//logToFile("trim(request_uri): " . trim($_SERVER['REQUEST_URI'],"/"));
	
	/*
	if( 
		($loginURL == $httpURL) ||
		($loginURL == trim($httpURL,"/")) ||
		($loginURL == $_SERVER['REQUEST_URI']) ||
		(trim($loginURL,"/") == trim($_SERVER['REQUEST_URI'],"/"))
	) {
		//%%LOGIN_FORM%% is getting triggered on same page as true login page - so make sure post-login never comes back here
		//logToFile("In here"); 
		$redirectURL = "";
	}
	*/
	
	$product = Dap_Product::getProductDetailsByResource($redirectURL);
	if( isset($product) && !empty($product) && ($product->getSales_page_url() != "") ) {
		$signup_page =  $product->getSales_page_url();
	}
	
	if(!Dap_Session::isLoggedIn()) { //Not logged in
		//logToFile("not logged in livelinks",LOG_DEBUG_DAP);
		$output = "";
		
		$loginFormFilepath = WP_PLUGIN_DIR . "/DAP-WP-LiveLinks/DAP-WP-LoginForm.html";
		if( file_exists(WP_PLUGIN_DIR . "/DAP-WP-LiveLinks/customDAP-WP-LoginForm.html") ) {
			$loginFormFilepath = WP_PLUGIN_DIR . "/DAP-WP-LiveLinks/customDAP-WP-LoginForm.html";
		}
		
		$output .= replaceFillers($loginFormFilepath,"%%LOGIN_URL%%",Dap_Config::get("LOGIN_URL"));
		$output = str_replace("%%SIGNUP_PAGE%%",$signup_page,$output);
		$output = str_replace("%%LOGGED_IN_URL%%",Dap_Config::get("LOGGED_IN_URL"),$output);
		$output = str_replace("%%REQUEST%%",$redirectURL,$output);

		//Finally, replace just the %%LOGIN_FORM%% and leave the rest of the content alone
		$output = str_replace("%%LOGIN_FORM%%",$output,$data);
		//$output = mb_convert_encoding($output, "UTF-8", "auto");
		
		if( isset($_GET['msg']) ) {
			//$msg = mb_convert_encoding($msg, "UTF-8", "auto");
			$output = "<div class='confirmationMessage'>" . stripslashes($_GET['msg']) . "</div>" . $output;
		}
		
		//logToFile("output: $output",LOG_DEBUG_DAP);
		return $output;
	} //End Not Logged In
	
	else if(Dap_Session::isLoggedIn()) {
		//Check if there is a request uri
		//logToFile("loggedIn",LOG_DEBUG_DAP);
		$msg = "";
		$session = DAP_Session::getSession();
		$user = $session->getUser();		
		//ob_end_clean();
		$redirectURL = Dap_UsersProducts::getLoggedInURL();
		//@header("Location:".$redirectURL);
		
		$msg = "[" . MSG_ALREADY_LOGGEDIN_1 . " <a href='" . $redirectURL . "'>" . MSG_ALREADY_LOGGEDIN_2 . "</a>]<br/><br/>";
		
		if( $session->isAdmin() ) {
			$msg .= ">>> <a href='/dap/admin/'>You are logged in as DAP Admin. Proceed to DAP Admin Dashboard</a> <<< <br/><br/>";
		}
		
		$output = str_replace("%%LOGIN_FORM%%",$msg,$data);
		//$output = mb_convert_encoding($output, "UTF-8", "auto");

		if( isset($_GET['msg']) ) {
			//$msg = mb_convert_encoding($msg, "UTF-8", "auto");
			$output = "<div class='confirmationMessage'>" . stripslashes($_GET['msg']) . "</div><br/><br/>" . $output;
		}
		
		if( isset($_GET['request']) && ($_GET['request'] != "") ) {
			$permalink = $_GET['request'];
			//logToFile("permalink: " . $permalink); 
			
			$salesPageURL = Dap_Config::get("SALES_PAGE_URL"); //global SPU
			if($salesPageURL == "") $salesPageURL = "/";

			/**
				It means user already logged in, and was redirected back here 
				with a query string, because user not eligible to view that content
			*/
		
			//$msg = mb_convert_encoding($msg, "UTF-8", "auto");
			//$msg = "[You are already logged in as admin. <a href='" . $redirectURL . "'>Click here</a> to continue to DAP Admin Control Panel]";
		
			/** Append error-loggedin.php content to message */
			$errorHTMLPath = $lldocroot . "/dap/inc/error-loggedin.php";
			//Check for custom error page
			if( file_exists($lldocroot . "/dap/inc/customerror-loggedin.php") ) {
				$errorHTMLPath = $lldocroot . "/dap/inc/customerror-loggedin.php";
			}
			
			//$productCount = $user->hasAccessToHowManyDistinctProducts();
			//$permalink = str_replace( $_SERVER['REQUEST_QUERY'], '', $_SERVER['REQUEST_URI'] );
			//logToFile("permalink: " . $permalink); 
			//logToFile($_SERVER['REQUEST_QUERY']); 
			//logToFile($_SERVER['REQUEST_URI']); 
			$productCount = Dap_Product::isPartOfHowManyDistinctProducts($permalink);
			logToFile("productCount: $productCount");
			if( $productCount == 1 ) {
				$product = Dap_Product::getProductDetailsByResource($permalink);
				if(!is_null($product) && isset($product)) {
					if ($product->getSales_page_url() != "") {
						$salesPageURL = $product->getSales_page_url();
					}
					if ($product->getLogged_in_url() != "") {
						$loggedInURL = $product->getLogged_in_url();
					}
				}
			}
			$output2 = replaceFillers($errorHTMLPath,"%%LOGIN_URL%%",Dap_Config::get("LOGIN_URL"));
			$output2 = str_replace("%%SALES_PAGE_URL%%",$salesPageURL,$output2);
			//logToFile("About to return");
			
			$output = $output2 . "<br/><br/>" . $output;
		}
		
		

		return $output;  
		exit;
	}


	//} //End if (strpos($data,'%%LOGIN_FORM%%')!== false) 

	//simply return blog post content
	return $data;

} //end function
	
	
function dap_personalize($data) {
	if (strpos($data,'%%') === false) return $data;
	
	$lldocroot = "";
	if( defined('SITEROOT') ) {
		$lldocroot = SITEROOT;
	} else {
		$lldocroot = $_SERVER['DOCUMENT_ROOT'];
	}
	include_once ($lldocroot . "/dap/dap-config.php");	
	$errorHTML = mb_convert_encoding(MSG_PLS_LOGIN, "UTF-8", "auto") . " <a href=\"" . Dap_Config::get("LOGIN_URL") . "\">". mb_convert_encoding(MSG_CLICK_HERE_TO_LOGIN, "UTF-8", "auto") . "</a>";

	if (
			(
				(strpos($data,'%%AFFDETAILS%%')!== false) ||
				(strpos($data,'%%USERPROFILE%%')!== false) ||
				(strpos($data,'%%USERLINKS%%')!== false) ||
				(strpos($data,'%%FEEDLINK%%')!== false) ||
				(strpos($data,'%%ACTIVATION_KEY%%')!== false) ||
				(strpos($data,'%%AFF_LINK%%')!== false) || 
				(strpos($data,'%%SELFSERVICE%%')!== false) || 
				(strpos($data,'%%CREDITSTORE%%')!== false) || 
				(strpos($data,'%%PAYPALCONFIRM%%')!== false) || 
				(strpos($data,'%%CREDITHISTORY%%')!== false) ||
				(strpos($data,'%%USER_ID%%')!== false) ||
				(strpos($data,'%%USERNAME%%')!== false)
			)
			&&
			!Dap_Session::isLoggedIn()
		) 
	{
		
		$data = str_replace ("%%AFFDETAILS%%", $errorHTML, $data);
		$data = str_replace ("%%USERPROFILE%%", $errorHTML, $data);
		$data = str_replace ("%%USERLINKS%%", $errorHTML, $data);
		$data = str_replace ("%%FEEDLINK%%", $errorHTML, $data);
		$data = str_replace ("%%ACTIVATION_KEY%%", $errorHTML, $data);
		$data = str_replace ("%%AFF_LINK%%", $errorHTML, $data);
		$data = str_replace ("%%SELFSERVICE%%", $errorHTML, $data);
		$data = str_replace ("%%CREDITHISTORY%%", $errorHTML, $data);
		$data = str_replace ("%%USER_ID%%", $errorHTML, $data);
		$data = str_replace ("%%USERNAME%%", $errorHTML, $data);
		
		return $data;
	}
	
	$session = Dap_Session::getSession();
	$user = $session->getUser();
	
	if (strpos($data,'%%FEEDLINK%%')!== false) {//found the string - so replace with session data
		//if( isset($user) && ($user != null) && ($user->isPaidUser()) ) {
		if( isset($user) && ($user != null) ) {
			$activation_key = $user->getActivation_key();
			$feedlink = "?key=" . $activation_key;
			$data = str_replace ("%%FEEDLINK%%", $feedlink, $data);
		}
	}
	
	if (strpos($data,'%%ACTIVATION_KEY%%')!== false) {//found the string - so replace with session data
		if( isset($user) && ($user != null) ) {
			$activation_key = $user->getActivation_key();
			$data = str_replace ("%%ACTIVATION_KEY%%", $activation_key, $data);
		}
	}
	
	if (strpos($data,'%%FIRST_NAME%%')!== false) {//found the string - so replace with session data
		if(isset($user)) {
			$first_name = $user->getFirst_name();
			$data = str_replace ("%%FIRST_NAME%%", $first_name, $data);
		} else {
			$data = str_replace ("%%FIRST_NAME%%", "reader", $data);
		}
	}
	
	if (strpos($data,'%%LAST_NAME%%')!== false) {//found the string - so replace with session data
		if(isset($user)) {
			$last_name = $user->getLast_name();
			$data = str_replace ("%%LAST_NAME%%", $last_name, $data);
		} else {
			$data = str_replace ("%%LAST_NAME%%", "", $data);
		}
	}

	if (strpos($data,'%%AFFDETAILS%%')!== false) {//found the string - so replace with session data
		ob_start();
		eval("?>" . file_get_contents($lldocroot . "/dap/inc/content/affdetails.inc.php"));
		$output = ob_get_contents();
		ob_end_clean();
		$data = str_replace ("%%AFFDETAILS%%", $output, $data);
	}

	if (strpos($data,'%%USERPROFILE%%')!== false) {//found the string - so replace with session data
		$data = str_replace ("%%USERPROFILE%%", "[DAPUserProfile]", $data);
		do_shortcode(dap_clean_shortcode_content($data));
		/**
		ob_start();
		eval("?>" . file_get_contents($lldocroot . "/dap/inc/content/userprofile.inc.php"));
		$output = ob_get_contents();
		ob_end_clean();
		$data = str_replace ("%%USERPROFILE%%", $output, $data);
		*/
	}

	if (strpos($data,'%%USERLINKS%%')!== false) {//found the string - so replace with session data
		$data = str_replace ("%%USERLINKS%%", "[DAPUserLinks]", $data);
		do_shortcode(dap_clean_shortcode_content($data));
		
		/**
		ob_start();
		eval("?>" . file_get_contents($lldocroot . "/dap/inc/content/userlinks.inc.php"));
		$output = ob_get_contents();
		ob_end_clean();
		$data = str_replace ("%%USERLINKS%%", $output, $data);
		*/
	}

	if (strpos($data,'%%EMAIL%%')!== false) {//found the string - so replace with session data
		if(isset($user)) {
			$email = $user->getEmail();
			$data = str_replace ("%%EMAIL%%", $email, $data);
		} else {
			$data = str_replace ("%%EMAIL%%", "[No Email Id Found]", $data);
		}
	}
	
	if (strpos($data,'%%EMAIL_ID%%')!== false) {//found the string - so replace with session data
		if(isset($user)) {
			$email = $user->getEmail();
			$data = str_replace ("%%EMAIL_ID%%", $email, $data);
		} else {
			$data = str_replace ("%%EMAIL_ID%%", "[No Email Id Found]", $data);
		}
	}
	
	if (strpos($data,'%%USER_NAME%%')!== false) {//found the string - so replace with session data
		if(isset($user)) {
			$user_name = $user->getUser_name();
			$data = str_replace ("%%USER_NAME%%", $user_name, $data);
		} else {
			$data = str_replace ("%%USER_NAME%%", "[No Username Found]", $data);
		}
	}

	if (strpos($data,'%%MEMBER_HOME_PAGE%%')!== false) {//found the string - so replace with session data
		$data = str_replace ("%%MEMBER_HOME_PAGE%%", $_SESSION['dap_member_home_page'], $data);
	}
	
	if (strpos($data,'%%AFF_LINK%%')!== false) {//found the string
		if(isset($user)) {
			$userId = $user->getId();
			$affLink = trim(Dap_Config::get("SITE_URL_DAP"),"/") . "/dap/a/?a=" . $userId; //Aff id looks like this: http://digitalaccesspass.com/dap/a/?a=1
			$data = str_replace ("%%AFF_LINK%%", $affLink, $data);
		} else {
			$data = str_replace ("%%AFF_LINK%%", "[No Affiliate Info Found]", $data);
		}
	}
	
	if (strpos($data,'%%SELFSERVICE%%')!== false) {//found the string - so replace with session data
		ob_start();
		eval("?>" . file_get_contents($lldocroot . "/dap/inc/content/selfService.inc.php"));
		$output = ob_get_contents();
		ob_end_clean();
		$data = str_replace ("%%SELFSERVICE%%", $output, $data);
	}

	if (strpos($data,'%%CREDITHISTORY%%')!== false) {//found the string - so replace with session data
		ob_start();
		eval("?>" . file_get_contents($lldocroot . "/dap/inc/content/creditHistory.inc.php"));
		$output = ob_get_contents();
		ob_end_clean();
		$data = str_replace ("%%CREDITHISTORY%%", $output, $data);
	}	

	if (strpos($data,'%%CREDITSTORE%%')!== false) {//found the string - so replace with session data
		ob_start();
		eval("?>" . file_get_contents($lldocroot . "/dap/inc/content/creditStore.inc.php"));
		$output = ob_get_contents();
		ob_end_clean();
		$data = str_replace ("%%CREDITSTORE%%", $output, $data);
	}
	
	if (strpos($data,'%%PAYPALCONFIRM%%')!== false) {//found the string - so replace with session data
		ob_start();
		eval("?>" . file_get_contents($lldocroot . "/dap/PaypalCheckoutConfirm.php"));
		$output = ob_get_contents();
		ob_end_clean();
		$data = str_replace ("%%PAYPALCONFIRM%%", $output, $data);
		
	}
	
	
	if (strpos($data,'%%SALES_PAGE_URL%%') !== false) {//found the string - so replace with data
		//logToFile("SERVER['REQUEST_URI']: " . $_SERVER['REQUEST_URI']); 
		$msg = "";
		$req = rtrim($_SERVER['REQUEST_URI'],"/");
		$product = Dap_Product::getProductDetailsByResource($req);
		if(isset($product)) {
			//logToFile("Yup, product is set"); 
			$msg = $product->getSales_page_url();
		}
		$data = str_replace ("%%SALES_PAGE_URL%%", $msg, $data);
	}
	
	if (strpos($data,'%%DAPCART%%')!== false) {//found the string - so replace with session data
		if ( session_id() == '' ) { // no session has been started yet, which is needed for validation
      		session_start();
    	}
		ob_start();
		eval("?>" . file_get_contents($lldocroot . "/dap/checkout.php"));
		$output = ob_get_contents();
		ob_end_clean();
		//logToFile("DAPCART:  output=" . $output);
		$data = str_replace ("%%DAPCART%%", $output, $data);
		
		//unset($_SESSION["err_text"]);
		return $data;
	}	
	
	if (strpos($data,'%%DAPCARTSUMMARY%%')!== false) {//found the string - so replace with session data
		if ( session_id() == '' ) { // no session has been started yet, which is needed for validation
      		session_start();
    	}
		ob_start();
		eval("?>" . file_get_contents($lldocroot . "/dap/CheckoutConfirm.php"));
		$output = ob_get_contents();

		//logToFile("DAPCARTCONFIRM:  output=" . $output);
		$data = str_replace ("%%DAPCARTSUMMARY%%", $output, $data);
		
		//unset($_SESSION["err_text"]);
		return;	
	}	
	
	if (strpos($data,'%%USER_ID%%')!== false) {//found the string - so replace with session data
		if(isset($user)) {
			$userId = $user->getId();
			$data = str_replace ("%%USER_ID%%", $userId, $data);
		} else {
			$data = str_replace ("%%USER_ID%%", "[Not logged in]", $data);
		}
	}

	if (strpos($data,'%%PASSWORD%%')!== false) {//found the string - so replace with session data
		if(isset($user)) {
			$password = $user->getPassword();
			$data = str_replace ("%%PASSWORD%%", $password, $data);
		} else {
			$data = str_replace ("%%PASSWORD%%", "[Not logged in]", $data);
		}
	}
	
	
	if (strpos($data,'%%SITE_NAME%%')!== false) {//found the string - so replace with session data
		$data = str_replace ("%%SITE_NAME%%", Dap_Config::get("SITE_NAME"), $data);
	}	
	
	if (strpos($data,'%%DAPCUSTOMFIELD_')!== false) {//found the string - so replace with session data
		$data = dap_personalize_custom_fields($user, $data);
	}	
	
	return $data;
}


function dap_personalize_custom_fields($user, $data) {
	//hi, your tax id is %%custom_tax%% and your ssn is %%custom_ssn%%.
	$count = substr_count($data, "%%DAPCUSTOMFIELD_"); 
	//count = 2
	//logToFile("DAP-WP-LiveLinks.php: dap_personalize_custom_fields: occurences=" . $count);
	
	for($i=0; $i<$count;$i++) {
		$remaining_data = stristr($data,"%%DAPCUSTOMFIELD_");
		
		//$remaining_data = tax%% and your ssn is %%custom_ssn%%
		//logToFile("DAP-WP-LiveLinks.php: dap_personalize_custom_fields:: remaining_data=" . $remaining_data);
		
		if($remaining_data) {
			$new_data = substr($remaining_data,17);
			$pos = strpos($new_data, "%%");
			//$pos=3
			//logToFile("DAP-WP-LiveLinks.php: dap_personalize_custom_fields: pos=" . $pos);
					
			if ($pos !== false) {
				$custom_field_name = substr($new_data, 0, intval($pos));
				//custom_field_name = tax
				//logToFile("DAP-WP-LiveLinks.php: dap_personalize_custom_fields: custom_field_name=" . $custom_field_name);
				
				if(isset($user)) {
					$userId = $user->getId();
				  	$user_custom_value = Dap_UserCustomFields::loadUserCustomFieldsByCustomFieldName($custom_field_name, $userId);
				  	$value = "";
			  
				  	if ($user_custom_value) {
					  	foreach ($user_custom_value as $val) {
							//logToFile("DAP-WP-LiveLinks.php: dap_personalize_custom_fields: val=" . $val['custom_value']);
							$value = $val['custom_value'];
							$data = str_replace("%%DAPCUSTOMFIELD_" . $custom_field_name . "%%", $value, $data);
						} //foreach
					} else {
						//logToFile("DAP-WP-LiveLinks.php: dap_personalize_custom_fields: " . $custom_field_name);
						$data = str_replace("%%DAPCUSTOMFIELD_" . $custom_field_name . "%%", $value, $data);
					}
				} //if(isset($user))
				else {
					$data = str_replace("%%DAPCUSTOMFIELD_" . $custom_field_name . "%%", "[Not logged in]", $data);
				}
 			}
			else { break; }
		} //if
		else break;
	} // for
	
	return $data;
}

function dap_personalize_error($data) {
	if (strpos($data,'%%') === false) return $data;
	
	$lldocroot = "";
	if( defined('SITEROOT') ) {
		$lldocroot = SITEROOT;
	} else {
		$lldocroot = $_SERVER['DOCUMENT_ROOT'];
	}
	include_once ($lldocroot . "/dap/dap-config.php");	

	if (strpos($data,'%%MSG%%') !== false) {//found the string - so replace with data
		$msg = "Nothing to see here :-)";
		//if( isset($_GET['msg']) ) {
			//$msg = "<h3><div align='center'><font color='#CC0000'>" . stripslashes($_GET['msg']) . "</font></div></h3>";
		//}
		
		if(isset($_GET['request']) && isset($_GET['msg'])) {
			$msg = $_GET['msg'];
			$req = $_GET['request'];		
			$product = Dap_Product::getProductDetailsByResource($req);
			$_SESSION['request'] = $req;
			$pos = strpos($msg, "DAP006");
			if(!($pos === false)) {
				header("Location:".Dap_Config::get("LOGIN_URL")."?msg=$msg&request=$req");
			}
			
			if(isset($product)) {
				$msg = "This content is part of the product: <br/><br/>
				<a href=" . $product->getSales_page_url() . ">" . $product->getName() . "</a><br/>" . 
				$product->getDescription();
			}
		}
		
		$data = str_replace ("%%MSG%%", $msg, $data);
	}
	
	if (strpos($data,'%%SALES_PAGE_URL%%') !== false) {//found the string - so replace with data
		if( isset($_GET['request']) ) {
			$msg = "";
			Dap_Config::loadConfig(true);
			$req = $_GET['request'];		
			$product = Dap_Product::getProductDetailsByResource($req);
			$_SESSION['request'] = $req;
			if(isset($product)) {
				$msg = $product->getSales_page_url();
			}
		}
		$data = str_replace ("%%SALES_PAGE_URL%%", $msg, $data);
	}
	
	$data = mb_convert_encoding($data, "UTF-8", "auto");
	return $data;
}

function dap_permalink_dump($data) {
	global $wpdb;
	global $table_prefix;
	
	$lldocroot = "";
	if( defined('SITEROOT') ) {
		$lldocroot = SITEROOT;
	} else {
		$lldocroot = $_SERVER['DOCUMENT_ROOT'];
	}
	
	$fp = fopen($lldocroot . '/dap/dap_permalink_dump.php', 'w');
	fwrite($fp,  "----------------- POSTS -----------------\n");
	
	//$posts = get_posts('showposts=1000');
	$q = "SELECT ID FROM " . $table_prefix . "posts WHERE post_status='publish' AND post_type='post' ORDER BY post_date DESC";
	$posts = $wpdb->get_results($q);
	foreach( $posts as $post ) :
		fwrite($fp,  get_permalink($post->ID) . "\n");
	endforeach;
	
	//$pages = get_pages('');
	fwrite($fp,  "----------------- PAGES -----------------\n");
	$q = "SELECT ID FROM " . $table_prefix . "posts WHERE post_status='publish' AND post_type='page' ORDER BY post_date DESC";
	$pages = $wpdb->get_results($q);
	foreach( $pages as $page ) :
		fwrite($fp,  get_page_link($page->ID) . "\n");
	endforeach;

	fclose($fp);
  	return $data;
}

function dap_add_pages() {
    //add_menu_page('DigitalAccessPass', 'DigitalAccessPass', 8, 'dapHome', 'dap_wp_admin');
	add_menu_page('DigitalAccessPass', 'Digital Access Pass (DAP)', 'manage_options', 'dapHome', 'dap_wp_admin');
}


// Top Level Menu Page (Configs)
function dap_wp_admin() {
	$lldocroot = "";
	if( defined('SITEROOT') ) {
		$lldocroot = SITEROOT;
	} else {
		$lldocroot = $_SERVER['DOCUMENT_ROOT'];
	}
	include_once ($lldocroot . "/dap/dap-config.php");	
    
	$session = Dap_Session::getSession();
	if( !isset($session) || !Dap_Session::isLoggedIn() || !$session->isAdmin() ) {
	    //session_destroy();
		//$msg = "Sorry, you are either not logged in - or not authorized to view this page.";
		echo '
		<iframe src ="/dap/login.php?request=/dap/admin/" width="99%" height="1024" frameborder="0" marginheight="0" marginwidth="0" scrolling="auto">
  		<p>Your browser does not support iframes.</p>
		</iframe>
		';
	} else if( Dap_Session::isLoggedIn() && $session->isAdmin() ) {
		echo '
			<iframe src ="/dap/admin/" width="99%" height="3000" frameborder="0" marginheight="0" marginwidth="0" scrolling="auto">
			<p>Your browser does not support iframes.</p>
			</iframe>
			';
	}
}



function dap_wp_sync() {
	//logToFile("In dap_wp_sync");
	
	global $current_user;
	
	$lldocroot = "";
	if( defined('SITEROOT') ) {
		$lldocroot = SITEROOT;
	} else {
		$lldocroot = $_SERVER['DOCUMENT_ROOT'];
	}
	include_once ($lldocroot . "/dap/dap-config.php");
	
	//Do not sync WP admin or DAP admin
	$session = Dap_Session::getSession();
	if( is_admin() || user_can($current_user->ID, 'administrator') || (isset($session) && Dap_Session::isLoggedIn() && $session->isAdmin()) ) {
		return;
	}
	
	$blogName = get_bloginfo('name');
	$blogName = preg_replace("/[^A-Za-z0-9]/", "", $blogName );
	//logToFile("blog name: " . $blogName); 
	
	$matchFound = false;
	
	if( Dap_Config::get("DAP_WP_SYNC") == "N" ) {
		//logToFile("DAP_WP_SYNC = 'N'"); 
		return;
	}
	
	//If dap-wp-nosync is deliberately set (to anything), don't do any syncing and just return
	if( isset($_SESSION["dap-wp-nosync"]) && ($_SESSION["dap-wp-nosync"] != "") ) {
		//logToFile("dap-wp-nosync: " . $_SESSION["dap-wp-nosync"]);
		if( isset($_SESSION[$blogName]) && ($_SESSION[$blogName] != "") ) { //already synced for this specific blog
			//logToFile("blogName: " . $_SESSION[$blogName]);
			//logToFile("Already synced for this blog: Not syncing and returning"); 
			return; //No need to sync or create user in WP
		}
	}
	
	//logToFile("Going to sync now...");
	if(!Dap_Session::isLoggedIn()) { //Not logged in
		//logToFile("NOT Logged In, so returning");
		return;
	}
	
	require_once ( ABSPATH . WPINC . '/registration.php' );
	require_once( ABSPATH . 'wp-includes/class-phpass.php');

	if(Dap_Session::isLoggedIn()) {
		//If it gets here, it means new session, or user updated profile, and sync/create is required
		//logToFile("Logged In");
		$session = DAP_Session::getSession();
		$user = $session->getUser();
		//logToFile("Id: " . $user->getId() . ", First name: " . $user->getFirst_name() . ", Last Name: " . $user->getLast_name() . ", User Name: " . $user->getUser_name()); 
		
		try {
			
			$username = "";
			
			/** 
				First, check if there's a $_SESSION["wpUsername"] in session. 
				If yes, previously synced user, and WP username is known
			*/
			$wpUsername = ( isset($_SESSION["wpUsername"]) && ($_SESSION["wpUsername"] != "") ) ? $_SESSION["wpUsername"] : "";
			//logToFile("wpUsername: $wpUsername"); 
			if( ($wpUsername != "") && isset($_SESSION[$blogName]) ) {
				//Simply sync blindly from DAP to WP and get out
				$dapUsername = $user->getUser_name();
					
				$userarrayupd = array();
				$id = username_exists($wpUsername);
				$userarrayupd['ID'] = $id;
				$wpUser = get_userdata($id);
				
				if( (Dap_Config::get("WP_SYNC_IF_USERNAME") == "N") && isset($dapUsername) && ($dapUsername!="") ) {
					$userarrayupd['user_login'] = $dapUsername;
				} else {
					$userarrayupd['user_login'] = $wpUsername;
				}
				

				$userarrayupd['user_pass'] = $user->getPassword();                 
				$userarrayupd['user_email'] = $user->getEmail();
				$userarrayupd['first_name'] = $user->getFirst_name();
				$userarrayupd['last_name'] = $user->getLast_name();
				//$userarrayupd['role'] = $userarray['role'];
				
				if($wpUser->display_name != "") {
					$userarrayupd['display_name'] = $wpUser->display_name;
				} else {
					$userarrayupd['display_name'] = $user->getFirst_name() . $user->getLast_name();
				}
				//logToFile("Updating user role to: " . $userarrayupd['role']); 

				wp_update_user($userarrayupd);
				$_SESSION["dap-wp-nosync"] = "Y";
				$_SESSION[$blogName] = "Y";
				return;
			}
			
			/** 
				Before checking anything, see if DAP username/email match with WP username/email
				If yes, then it means matching user found. So don't even bother checking for anything else.
				Simply log in user using actual WP 'username' field
			*/
			//logToFile("Dap username: " . $user->getUser_name());
			//logToFile("Does it exist in WP: " . username_exists($user->getUser_name()));
			
			if(Dap_Config::get("WP_SYNC_IF_USERNAME") == "Y") {
				/*
					v>=4.0: Sync only if username exists.
					If DAP username is not set, don't sync
				*/
				//logToFile("WP_SYNC_IF_USERNAME is set to 'Y'"); 
				$username = $user->getUser_name();
				if( !isset($username) || ($username=="") ) {
					//Set sync to something. Setting to "Y" so it won't try to sync again for the rest of this session
					//Unless of course a) user updates profile, or b) logs out and logs back in
					//logToFile("Username is not set in DAP. So no sync. Returning");
					$_SESSION["dap-wp-nosync"] = "Y";
					$_SESSION[$blogName] = "Y";
					return;
				}
				
			}

			
			//Continuing.....
			
			/**
				If you get here, then it means WP_SYNC_IF_USERNAME == "N" (Which means sync regardless of DAP user name)
				OR
				It could also mean WP_SYNC_IF_USERNAME == "Y" but there IS a username in DAP
				(could happen if user updated user profile to enter new username in DAP)
			*/
			
			/**
			Check for these 3... if none match, then new user
			1.WP username = DAP username?			
			2.WP username = DAP Email?			
			3.WP username = DAP FirstLast?			
			*/
			
			//1.WP username = DAP username?			
			if( ($user->getUser_name() != "") && username_exists($user->getUser_name()) ) {
				//logToFile("Case 1 match"); 
				//If DAP username found in WP, then also make sure emails and passwords match
				$id = username_exists($user->getUser_name());
				$userWP = get_userdata($id);
				//logToFile("Email in WP: " . $userWP->user_email); 
				//logToFile("Email in DAP: " . $user->getEmail()); 
				//logToFile("Password in WP: " . $userWP->user_pass); 
				//logToFile("Password (normal) in DAP: " . $user->getPassword()); 
				//logToFile("Password in DAP: " . wp_hash_password($user->getPassword())); 
				//$wpPass = new PasswordHash();
				
				$passwordsMatched = false;
				$wp_hasher = new PasswordHash(8, TRUE);
				if($wp_hasher->CheckPassword($user->getPassword(), $userWP->user_pass)) {
   					//logToFile("YES, Passwords Matched");
					$passwordsMatched = true;
				}
				
				if( ($userWP->user_email === $user->getEmail()) && ($passwordsMatched) ) { 
					//Found correct user in both DAP & WP, so use actual 'username' to log in
					//logToFile("Matching username found: " . $user->getUser_name() . ", so not bothering with anything else"); 
					$username = $user->getUser_name();
					$matchFound = true;
				} else {
					//Username match found, but no match between DAP & WP emails. So username already taken by someone else
					//So do not sync, and return
					//logToFile("Matching username " . $user->getUser_name() . " found. But no match between DAP and WP emails or password. So someone in WP already has taken this username");
					$_SESSION["dap-wp-nosync"] = "Y";
					$_SESSION[$blogName] = "Y";
					return;
				}
			} 
			
			
			//2.WP username = DAP Email?
			if( !$matchFound && ($id = username_exists($user->getEmail())) ) {
				//logToFile("Case 2 match"); 
				//Check if passwords match
				$userWP = get_userdata($id);
				if( $userWP->user_pass == md5($user->getPassword()) ) { 
					//Found correct user in both DAP & WP, so use actual 'username' to log in
					//logToFile("Matching username found: " . $user->getUser_name() . ", so not bothering with anything else"); 
					$username = $user->getEmail();
					$matchFound = true;
				} else {
					//Username match found, but no match between DAP & WP passwords. So username already taken by someone else
					//So do not sync, and return
					//logToFile("Matching username " . $user->getUser_name() . " found. But no match between DAP and WP emails. So someone in WP already has taken this username");
					$_SESSION["dap-wp-nosync"] = "Y";
					$_SESSION[$blogName] = "Y";
					return;
				}
			}
			
			
			/**
				No username in WP same as DAP username.
				No username in WP same as DAP email.
				Now check if username exists in WP that is same as FirstNameLastName in DAP
			*/
			if( !$matchFound && ($id = username_exists($user->getFirst_name() . $user->getLast_name())) ) {
				//logToFile("Case 3 match"); 
				//$id = username_exists($user->getFirst_name() . $user->getLast_name());
				$userWP = get_userdata($id);
				if( ($userWP->user_email == $user->getEmail()) && ( $userWP->user_pass == md5($user->getPassword()) ) ) { 
					//Found correct user in both DAP & WP, so use actual 'username' to log in
					//logToFile("Matching username found: " . $user->getUser_name() . ", so not bothering with anything else"); 
					$username = $user->getFirst_name() . $user->getLast_name();
				}  else {
					/*
						Username match found, but no match between DAP & WP passwords. 
						So FirstnameLastname already taken by someone else
						But it's ok if someone else has taken FirstnameLastName as username
						Still need to check if DAP username is different from WP Username. 
						If they are different, then this is a different user.
					*/

					if( $user->getUser_name() ==  $userWP->user_login ) {
						//Sorry, DAP username & WP username are also same. Can't use this username
						//logToFile("Username " . $user->getUser_name() . " found. But no match between DAP FirstnameLastname and WP username. Also, DAP username is same as WP username. So someone in WP already has taken this username");
						$_SESSION["dap-wp-nosync"] = "Y";
						$_SESSION[$blogName] = "Y";
						return;
					}
				}
			}
			
			/**
				No username or email or FirstnameLastname match between DAP and WP
				Possible New User
			*/
			if (!$matchFound) {
				/**
					USED TO BE...
					v<=3.9. Not forced to "sync only if username exists"
					So just sync using email
					
					Starting 03/11/2011, no longer using email as username. For now, hard-coded to FirstnameLastname.
					In next release of LL, it will become a configurable option
				*/
				//logToFile("WP_SYNC_IF_USERNAME is set to 'N' or is blank ''"); 
				//$username = $user->getEmail();			
				/* Check if username already figured out - if not, then make it FirstnameLastname */
				//logToFile("Case ELSE"); 
				if( !isset($username) || ($username=="") ) { //username not figured out
					if ( $user->getUser_name() == "" ) { //if DAP user name is also blank, then set WP username to FirstLast
						$username = $user->getFirst_name() . $user->getLast_name();
						$username = preg_replace('/\s+/', '', $username);
						
						//Now WP username is set to FirstLast
						//DAP username is still blank
						/**
							If we update DAP username also to be FirstLast, then it will be a problem for vB users
							So one option is to let it remain blank
							which means, it will never come to this block ever again, 
							because next time, WP username will have a value, and will be 
							caught by previous IF block
							
							Or better yet, do not do this if vb is installed
						*/
						
						if( !defined("VBFORUMPATH") ) {
							//logToFile("Going to choose FirstnameLastname as username"); 
							//Update DAP username with this username
							$user->setUser_name($username);
							$user->update();
						}
						
						
					} else { //DAP username is not blank - so set WP username = DAP username
						$username = $user->getUser_name();
					}
				}
			}
			
			//logToFile("About to insert/update"); 
			$userarray = array();
			$userarray['user_login'] = $username;
			$userarray['display_name'] = $username;  
			//$userarray['display_name'] = $user->getFirst_name() . $user->getLast_name();
			$userarray['user_email'] = $user->getEmail();
			$userarray['user_pass'] = $user->getPassword();                 
			$userarray['first_name'] = $user->getFirst_name();
			$userarray['last_name'] = $user->getLast_name(); 
			
			//$userarray['user_url'] = "";
			//$userarray['description'] = "";
			//$userarray['aim'] = "";
			//$userarray['yim'] = "";
			//$userarray['jabber'] = "";
			$userarray['user_nicename'] = $user->getFirst_name() . $user->getLast_name();  
			
			if( (Dap_Config::get("WP_SYNC_PAID_ONLY")=="Y") && (!$user->isPaidUser()) ) {
				//Sync only paid users is true - disable everyone else, except any WP admins
				//logToFile("Not a paid user"); 
				if ( username_exists($username) || email_exists($user->getEmail()) ) { //just do an update
					$id = username_exists($username);
					
					//Now check if the WP user we just found has WP admin role.
					//If yes, then DO NOT disable even though he may be free user
					//$user = new WP_User( $user_id );
					if ( isUserToBeSyncedAWPAdmin ( $id ) ){
						$_SESSION["dap-wp-nosync"] = "Y";
						$_SESSION[$blogName] = "Y";
						return;
					}
					
					//Coming here means user is not paid user, and needs to be disabled
					//So just updating role to blank, so user cannot use anything in WP
					//that needs user to be logged in - like forums, commenting, etc
					//for when user was previously eligible and now not eligible (eg., because of a refund)
					//logToFile("Updating to nothing...");
					$userarray['ID'] = $id;
					$userarray['role'] = ""; //set role to blank
					wp_set_current_user($id, null);
					wp_set_auth_cookie($id,false,'');
					wp_update_user($userarray);
				} 
				return;
			} else {
				//Sync everybody
				//Check if username also exists in WP
				//logToFile("About to Sync User now");
				$id = 0;
				
				/**
					This is the case where DAP username is blank
					So update as usual
				*/
				
				if ( username_exists($username) ) {   //just do an update
					//logToFile("Username $username exists in WP, so just doing an update");
					$userarrayupd = array();
					$id = username_exists($username);
					$userarrayupd['ID'] = $id;
					$userarrayupd['user_login'] = $username;
					$userarrayupd['user_pass'] = $user->getPassword();                 
					$userarrayupd['user_email'] = $user->getEmail();
					$userarrayupd['first_name'] = $user->getFirst_name();
					$userarrayupd['last_name'] = $user->getLast_name();
					//$userarrayupd['role'] = $userarray['role'];
					//$userarrayupd['display_name'] = $user->getFirst_name() . $user->getLast_name();
					//logToFile("Updating user role to: " . $userarrayupd['role']); 

					wp_update_user($userarrayupd);
				} 
				
				else { 
					//New User in WP - First time sync
					//logToFile("Username $username does not exist in WP, so doing an insert");
					//logToFile("New user"); 
					//logToFile("username: $username"); 
					$userarray['role'] = "";
					if ($user->isPaidUser()) {
						$userarray['role'] = Dap_Config::get("WP_DEF_ROLE_PAID");
					} else {
						$userarray['role'] = Dap_Config::get("WP_DEF_ROLE_FREE");
					}
					$userarray['role'] = ($userarray['role'] == "") ? "subscriber" : $userarray['role']; //default to subscriber for v<=3.9
					wp_insert_user($userarray); //otherwise create			
					
					//retrieve login
					$id = username_exists($username);
					//logToFile("Got user's id: $id"); 
				}
			
				//Auto login user into WP
				//ob_end_clean();
				//ob_start();
				//logToFile("About to log username - $username - in to WP"); 
				wp_set_current_user($id, $username);
				
				if( isset($_SESSION["dapRememberMe"]) && ($_SESSION["dapRememberMe"] == "dapRememberMe") ) {
					//logToFile("Remember me is ON. Doing wp_set_auth_cookie for id: $id"); 
					wp_set_auth_cookie($id,true,'');
				} else {
					//logToFile("Remember me is OFF. Doing wp_set_auth_cookie for id: $id"); 
					wp_set_auth_cookie($id,false,'');
				}
				
				//logToFile("Autologging in $username"); 
				do_action('wp_login', $username);
				$_SESSION["wpUsername"] = $username;
			}
			
			//Set sync to something. Setting to "Y" so it won't try to sync again for the duration of this session
			//Unless of course a) user updates profile, or b) logs out and logs back in
			//logToFile("Setting dap-wp-nosync so no more sync for this session - until User Profile is updated");
			$_SESSION["dap-wp-nosync"] = "Y";
			$_SESSION[$blogName] = "Y";
			return;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			//throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			//throw $e;
		}
	}
}


// Convert any properly structed HTML (or XML) into nested multidimensional arrays
function dap_xml2ary(&$string) {
    $parser = xml_parser_create();
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parse_into_struct($parser, $string, $vals, $index);
    xml_parser_free($parser);

    $mnary=array();
    $ary=&$mnary;
    foreach ($vals as $r) {
        $t=$r['tag'];
        if ($r['type']=='open') {
            if (isset($ary[$t])) {
                if (isset($ary[$t][0])) $ary[$t][]=array(); else $ary[$t]=array($ary[$t], array());
                $cv=&$ary[$t][count($ary[$t])-1];
            } else $cv=&$ary[$t];
            if (isset($r['attributes'])) {foreach ($r['attributes'] as $k=>$v) $cv['_a'][$k]=$v;}
            $cv['_c']=array();
            $cv['_c']['_p']=&$ary;
            $ary=&$cv['_c'];
        } elseif ($r['type']=='complete') {
            if (isset($ary[$t])) { // same as open
                if (isset($ary[$t][0])) $ary[$t][]=array(); else $ary[$t]=array($ary[$t], array());
                $cv=&$ary[$t][count($ary[$t])-1];
            } else $cv=&$ary[$t];
            if (isset($r['attributes'])) {foreach ($r['attributes'] as $k=>$v) $cv['_a'][$k]=$v;}
            $cv['_v']=(isset($r['value']) ? $r['value'] : '');
        } elseif ($r['type']=='close') $ary=&$ary['_p'];
    }
    _dap_del_p($mnary);
    return $mnary;
}

// _Internal: Remove recursion in result array
function _dap_del_p(&$ary) {
    foreach ($ary as $k=>$v) {
        if ($k==='_p') unset($ary[$k]);
        elseif (is_array($ary[$k])) _dap_del_p($ary[$k]);
    }
}

// Array to XML
function dap_ary2xml($cary, $d=0, $forcetag='') {
	//logToFile($_SESSION['dap_url_cache']);
    $cache = $_SESSION['dap_url_cache'];
    if (!is_array($cache)) $cache = array();
	$size = count($cache);
	//logToFile("Cache size: " . $size); 
    
    $res=array();
    foreach ($cary as $tag=>$r) {
        if (isset($r[0])) $res[] = dap_ary2xml($r, $d, $tag);
        else {
            if ($forcetag) $tag=$forcetag;
            $sp=str_repeat("\t", $d);
            
            if ($tag == "li")
	        	if (isset($r["_c"]["a"]["_a"]["href"])) {
	            	// Should always go here, since all LIs should contain an A tag
					$permalink = getResourceFromString($r["_c"]["a"]["_a"]["href"]);
					
					if (array_key_exists($permalink, $cache)) {
						//logToFile("Page permalink exists: $permalink"); 
						//logToFile("cache[permalink]: " . $cache[$permalink]); 
						if ($cache[$permalink] == 'hide') {
							unset($cary[$tag]);
						}
					} else {
						$resource = isURLAllowed($permalink);
						$errId = $resource->getError_id();
						// Something wrong with link, so unset it
						if (isset($errId)) {
							$cache[$permalink] = 'hide';
							unset($cary[$tag]);
						} else $cache[$permalink] = 'show';
					}
	            }
            // Only output the link and go to sublinks if permitted
            if ($cache[$permalink] != 'hide') {
	            $res[]="$sp<$tag";
	            if (isset($r['_a'])) {foreach ($r['_a'] as $at=>$av) $res[]=" $at=\"$av\"";}
	            $res[]=">".((isset($r['_c'])) ? "\n" : '');
	            if (isset($r['_c'])) $res[] = dap_ary2xml($r['_c'], $d+1);
	            elseif (isset($r['_v'])) $res[]=$r['_v'];
	            $res[]=(isset($r['_c']) ? $sp : '')."</$tag>\n";
	        }
        }
    }
	$_SESSION['dap_url_cache'] = $cache;
    return implode('', $res);
}

function dap_list_pages($content) {
	//logToFile($_SESSION['dap_list_pages_cached_already']); 
	//logToFile("dap_list_pages BEFORE: " . $content); 
	
	//logToFile("in dap_list_pages"); 
	$lldocroot = "";
	if( defined('SITEROOT') ) {
		$lldocroot = SITEROOT;
	} else {
		$lldocroot = $_SERVER['DOCUMENT_ROOT'];
	}
	include_once ($lldocroot . "/dap/dap-config.php");	
	
	$showProtectedLinksInMenu = Dap_Config::get("SHOW_PROT_MENULINKS");
	$sneak_peek = Dap_Config::get("SNEAK_PEEK");
	
	// No filtering is done if Sneak Peek=Y or showProtectedLinksInMenu=N
	if(($showProtectedLinksInMenu == "Y") || ($sneak_peek == "Y")) {
		//logToFile("showProtectedLinksInMenu = Y or sneak peek = Y - so returning"); 
		return $content;
	}
	
	// Add wrapping UL tag, since wp_list_pages doesn't include it
	$content = "<ul>$content</ul>";
	
	// Convert the HTML returned by wp_list_pages into nested associative arrays
	$ary_content = dap_xml2ary($content);
	
	//if ($_GET["showlog"] == 1) var_dump($_SESSION["dap_url_cache"]);
			
	// The heavy lifting: filter the array for "allowed" pages
	// and convert array back into HTML (nested ULs)
	$content = dap_ary2xml($ary_content);
		
	// Remove the outermost UL tag we added at the start
	$content = preg_replace('/^([^<]*)<ul>(.*)<\/ul>([^<]*)$/s', '\1\2\3', $content);
	
	//logToFile("dap_list_pages AFTER: " . $content); 
	return $content;
}
//add_filter('get_pages', 'dap_list_pages');



function load_dap_widgets() {
	register_widget( 'DAP_LoginLogout' );
	register_widget('DAP_ProductLinks');
}


function wp_noautop($pee) {
	global $post;
	$noautop = get_post_meta($post->ID, 'noautop', true);
	if ( $noautop == '1' || $noautop = '') {
		return $pee;
	}
	else {
		return wpautop($pee);
	}
}

// disable auto-p
function less_filters(){
	// add conditional auto-p
	// disable auto-p
	remove_filter('the_content', 'wpautop');
	// add conditional auto-p
	add_filter('the_content', 'wp_noautop');
	
	// disable texturize
	remove_filter('comment_text', 'wptexturize');
	remove_filter('the_excerpt', 'wptexturize');
	remove_filter('the_content', 'wptexturize');
	#remove_filter('the_title', 'wptexturize');

	disable_kses_content();
}

// disable KSES for content, Working now
function disable_kses_content() {
	remove_filter('content_save_pre', 'wp_filter_post_kses');
	remove_filter('excerpt_save_pre', 'wp_filter_post_kses');
	remove_filter('content_filtered_save_pre', 'wp_filter_post_kses');
}


function dap_product_links($data) {
	$lldocroot = "";
	if( defined('SITEROOT') ) {
		$lldocroot = SITEROOT;
	} else {
		$lldocroot = $_SERVER['DOCUMENT_ROOT'];
	}
	include_once ($lldocroot . "/dap/dap-config.php");	
	
	//include ($_SERVER['DOCUMENT_ROOT'] . "/dap/dap-config.php");	

	if (strpos($data,'%%PRODUCT_DETAILS')!== false) {//found the string - so replace with session data
		if ( !Dap_Session::isLoggedIn() ) {
			return "Sorry, please <a href=\"" . Dap_Config::get("LOGIN_URL") . "\">login</a> before you can view this page.";
		}
		
		$session = Dap_Session::getSession();
		$user = $session->getUser();
		$content = "Sorry, you do not have access to this Product.";
		
		preg_match_all("/%%PRODUCT_DETAILS_(.*?)%%/", $data, $matches);
		$productId = $matches[1][0];
		
		$userProduct = Dap_UsersProducts::load($user->getId(), $productId);
		
		if( isset($userProduct) && ($userProduct != null) ) {
			$product = Dap_Product::loadProduct($productId);
		
			$content = getCss() . 
			'
	
			  <table id="dap_single_product_links_table"> 
					<tr> 
					  <td><strong>Product Name</strong>: ' . $product->getName() . '</span></td> 
					</tr> 
					<tr> 
					  <td><strong>Access Start Date</strong>: ' . $userProduct->getAccess_start_date() . '</td> 
					</tr> 
					<tr> 
					  <td><strong>Access End Date</strong>: ' . $userProduct->getAccess_end_date() . '</td> 
					</tr> 

					<tr> 
					  <td><strong>Description</strong>: ' . $product->getDescription() . '</td> 
					</tr> 
					<tr> 
					  <td>&nbsp;</td> 
					</tr> 
					<tr> 
					  <td><strong>Links</strong>: ' . $userProduct->getActiveResources() . '</td> 
					</tr> 
			   </table><br/><br/>';
		}
		  
		//logToFile("Content: $content"); 
		$data = str_replace("%%PRODUCT_DETAILS_".$matches[1][0]."%%", $content, $data);
	}

	return $data;
}

function addUserFacingCss() {
	$dapCssFile = "/dap/inc/content/userfacing.css";
	$lldocroot = "";
	if( defined('SITEROOT') ) {
		$lldocroot = SITEROOT;
	} else {
		$lldocroot = $_SERVER['DOCUMENT_ROOT'];
	}
	
	if( file_exists($lldocroot . "/dap/inc/content/customuserfacing.css") ) {
		$dapCssFile = "/dap/inc/content/customuserfacing.css";
	}
	
	wp_register_style('dapUserFacingCss', $dapCssFile);
	wp_enqueue_style( 'dapUserFacingCss');
}

function dap_user_profile_update($id) {
	if ( is_admin() && current_user_can('administrator') ) return;
	
	//logToFile("User being updated: id: $id"); 
	if( Dap_Session::isLoggedIn() ) { 
		$userWP = get_userdata($id);
		$session = Dap_Session::getSession();
		$user = $session->getUser();
		
		$wpFirstName = $userWP->user_firstname;
		$wpLastName = $userWP->user_lastname;
		$wpEmail = $userWP->user_email;
		//$wpPassword = $userWP->pre_user_user_pass;
		//$wpPassword = $old_user_data->user_firstname;
		//logToFile("$wpEmail | $wpFirstName | $wpLastName"); 
		
		$user->setFirst_name($wpFirstName);
		$user->setLast_name($wpLastName);
		$user->setEmail($wpEmail);
		try {
			$user->update();
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
		}
		$session->setUser($user);
		unset($_SESSION["dap-wp-nosync"]);
		unset($_SESSION[$blogName]);
	}
}

function dap_logoutofdap() {
	//logToFile("User logging out of WP"); 
	$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : Dap_Config::get("LOGGED_OUT_URL");
	if($redirect == "") $redirect = "/";
	pluginLogout();
	Dap_Session::closeSession();
	header("Location: $redirect");
	exit;
}

function disable_password_fields() {
	if ( !current_user_can('administrator') )
		$show_password_fields = add_filter( 'show_password_fields', '__return_false' );
}




//-------------------------------------------------------------//
add_action('admin_menu', 'dap_add_pages');

add_filter('wp_insert_post' , 'dap_permalink_dump');
add_filter('deleted_post' , 'dap_permalink_dump');
add_filter('pre_post_update' , 'dap_permalink_dump');
add_filter('mod_rewrite_rules', 'dap_permalink_dump');	
add_filter('publish_page', 'dap_permalink_dump');	

register_activation_hook( __FILE__, 'dap_livelinks_activate');

add_filter('the_posts', 'dap_filter_posts'); 
add_filter('the_content_feed', 'dap_filter_posts'); 
add_filter('mod_rewrite_rules', 'dap_mod_rewrite');	

//Personalization
add_filter('the_content', 'dap_login'); 
add_filter('the_content', 'dap_personalize');
add_filter('the_content', 'dap_personalize_error');
add_filter('the_content', 'dap_product_links');
add_filter('the_title', 'dap_personalize');

add_action( 'widgets_init', 'load_dap_widgets' );
//DO NOT USE add_action('plugins_loaded', 'dap_loginform_widget_init');
//DO NOT USE add_action('wp_print_styles', 'addUserFacingCss');

add_filter('get_pages', 'dap_filter_posts_menu');
add_filter('wp_nav_menu_items', 'dap_list_pages');

add_action('profile_update','dap_user_profile_update');
add_action('wp_logout','dap_logoutofdap');
if ( is_admin() ) {
	add_action( 'init', 'disable_password_fields', 10 );
}
add_action( 'wp_enqueue_scripts', 'addDAPCSS' );


function isUserToBeSyncedAWPAdmin( $id ) {
	//logToFile("in isUserToBeSyncedAWPAdmin"); 
	$userWP = get_userdata($id);
	if ( !empty( $userWP->roles ) && is_array( $userWP->roles ) ) {
		foreach ( $userWP->roles as $role ) {
			//logToFile("------------------------------ ROLE: $role");
			if( $role == "administrator" ) { //Leave WP admins alone
				return true;
			}
		}
	}
	return false;
}

// wc order - cancel
function dap_woocommerce_order_status_cancelled( $order_id ) {

  $lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
  if(file_exists($lldocroot . "/dap/dap-config.php")) include_once ($lldocroot . "/dap/dap-wcpayment.php");
  
  logToFile("DAP-WP-LiveLinks.php:dap_woocommerce_order_status_cancelled(): ENTER... order_id=$order_id, order_status=" . $order_status,LOG_DEBUG_DAP);
  $order = new WC_Order( $order_id );
  processWCOrderCancellation($order, $post, "DAP-WP-Livelinks.dap_woocommerce_order_status_cancelled(): ");
  logToFile("DAP-WP-LiveLinks.php:dap_woocommerce_order_status_cancelled(): EXIT",LOG_DEBUG_DAP);
  return $order_status;
}

add_action( 'woocommerce_order_status_cancelled', 'dap_woocommerce_order_status_cancelled' );


// wc order - refund
function dap_woocommerce_order_status_refunded( $order_id ) {

  $lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
  if(file_exists($lldocroot . "/dap/dap-config.php")) include_once ($lldocroot . "/dap/dap-wcpayment.php");
  
  logToFile("DAP-WP-LiveLinks.php:dap_woocommerce_order_status_refunded(): ENTER... order_id=$order_id",LOG_DEBUG_DAP);
  $order = new WC_Order( $order_id );
  processWCSubscriptionRefund($order, $post, "DAP-WP-Livelinks.dap_woocommerce_order_status_refunded(): ");
  logToFile("DAP-WP-LiveLinks.php:dap_woocommerce_order_status_refunded(): EXIT",LOG_DEBUG_DAP);
  return $order_status;
}

add_action("woocommerce_order_status_refunded", "dap_woocommerce_order_status_refunded");

// wc order - complete
function dap_woocommerce_order_status_completed( $order_id ) {
	
  logToFile("DAP-WP-LiveLinks.php:dap_woocommerce_order_status_completed(): ENTER... order_id=$order_id",LOG_DEBUG_DAP);
  processPayment("completed", $order_id,"FIRST_PAYMENT");
  logToFile("DAP-WP-LiveLinks.php:dap_woocommerce_order_status_completed(): EXIT",LOG_DEBUG_DAP);
  return;
}

add_action( 'woocommerce_order_status_completed', 'dap_woocommerce_order_status_completed' );


//subscription order complete 
function processWCSubscriptionPayment( $order_status, $order_id ) {
	
  logToFile("DAP-WP-LiveLinks.php:processWCSubscriptionPayment(): ENTER... order_id=$order_id, order_status=" . $order_status,LOG_DEBUG_DAP);
  processPayment($order_status, $order_id,"FIRST_PAYMENT");
  // non-virtual order, return original status
  logToFile("DAP-WP-LiveLinks.php:processWCSubscriptionPayment(): EXIT",LOG_DEBUG_DAP);
  return $order_status;
}

add_filter( 'woocommerce_payment_complete_order_status', 'processWCSubscriptionPayment', 10, 2 );

function processPayment($order_status, $order_id, $paymentType, $recur_item_name="",$complete_payment="", $product_id="") {
	
	$lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
	if(file_exists($lldocroot . "/dap/dap-config.php")) include_once ($lldocroot . "/dap/dap-wcpayment.php");

	$order = new WC_Order( $order_id );
    $post=array();
	logToFile("DAP-WP-LiveLinks.php:processPayment(): ENTER... $order_status, $order_id",LOG_DEBUG_DAP);
	processSuccessfulWCPayment($order, $order_status, $order_id, $paymentType, $recur_item_name,$complete_payment, $product_id);
	logToFile("DAP-WP-LiveLinks.php:processPayment(): EXIT... $user_id, $subscription_key",LOG_DEBUG_DAP);

}

// subscription process cancellation
function processWCSubscriptionCancellation($user_id, $subscription_key) {
  logToFile("DAP-WP-LiveLinks.php:processWCSubscriptionCancellation(): ENTER... $user_id, $subscription_key",LOG_DEBUG_DAP);
  global $woocommerce;
  
  $lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
  if(file_exists($lldocroot . "/dap/dap-config.php")) include_once ($lldocroot . "/dap/dap-wcpayment.php");

  $subscription = WC_Subscriptions_Manager::get_users_subscription( $user_id, $subscription_key );
  processDAPWCSubscriptionCancellation($subscription, $post, "DAP-WP-Livelinks.processPaymentCancellation(): ");
  logToFile("DAP-WP-LiveLinks.php:processWCSubscriptionCancellation(): EXIT",LOG_DEBUG_DAP);
}

add_action("cancelled_subscription", "processWCSubscriptionCancellation", 10, 2);

//add_action("refunded_subscription", "processPaymentRefund", 10, 2);

//add_action("activated_subscription", "processRenewalOrder", 10, 2);

//public static function processRenewalOrder( $user_id, $subscription_key ) {

//recurring / subscription payment
function processSubscriptionRenewal(  $user_id, $subscription_key ) {
	
	logToFile("DAP-WP-Livelinks.php:processRenewalOrder(): ENTER processRenewalOrder : user=".$user_id);
	
	sendAdminEmail("WooCommerce->DAP: subscription payment received", "Subscription payment received for user_id=".$user_id);
	
	global $woocommerce;
	$lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
    if(file_exists($lldocroot . "/dap/dap-config.php")) include_once ($lldocroot . "/dap/dap-wcpayment.php");
	
	$subscription = WC_Subscriptions_Manager::get_users_subscription( $user_id, $subscription_key );
	processWCRenewals( $subscription, "DAP-WP-Livelinks.processSubscriptionRenewal(): " );
	
}

add_action( 'processed_subscription_payment', 'processSubscriptionRenewal', 10, 2 );


add_action( 'user_register', 'wp_dap_registration_save', 10, 1 );

function wp_dap_registration_save( $user_id ) {
    $user_info = get_userdata($user_id);
    $username = $user_info->user_login;
    $user_email = $user_info->user_email;
	$first_name = $user_info->first_name;
    $last_name = $user_info->last_name;
	//$password = $user_info->user_pass;
	$password=$_POST['account_password'];
	logToFile("DAP-WP-Livelinks.php:wp_dap_registration_save(): WP user=".$user_id.", email=".$user_email.", password=".$password);
	
	$user = new Dap_User();

	$user->setFirst_name( $first_name );
	$user->setLast_name( $last_name );
	$user->setUser_name( $username );	
	$user->setEmail( $user_email );	
	$user->setPassword( $password );	
	
	$user->setStatus("A");
	
	$user->create();
	logToFile("DAP-WP-Livelinks.php:wp_dap_registration_save(): created WP user in DAP successfully");
}

?>