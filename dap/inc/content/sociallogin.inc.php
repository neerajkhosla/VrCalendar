<?php
    //require_once 'config.php';
	$lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
	if(file_exists($lldocroot . "/dap/dap-config.php")) include_once ($lldocroot . "/dap/dap-config.php");
	
	$blogpath=urldecode($_SESSION["blogpath"]);
	$pathtoplugin = urldecode($_SESSION["pathtoplugin"]);
	logToFile("sociallogin.inc.php: pathtoplugin=".$pathtoplugin,LOG_DEBUG_DAP);	
	$currentURL = urldecode($_SESSION["currenturl"]);	
	
	$fbaccountnotlinked=false;
	$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'] : "http://".$_SERVER['SERVER_NAME'];	
	//$_SESSION["clickedonfbconnect"]="N";
	//define('YOUR_APP_ID', '343899079025933');
		
    /*
	dapdemo
	$facebook = new Facebook(array(
	  'appId'  => '343899079025933',
	  'secret' => '1095eed82a281f1b09bc22428d5249dd',
	));*/
	
	//techizens
	define('YOUR_APP_ID', '326849394103792');
	$facebook = new Facebook(array(
	  'appId'  => '326849394103792',
	  'secret' => '3a3a90f3002442196fb3a5766d7ff069',
	));
		
	if (isset($_SESSION["redirecturl"])) {
		$redirectURL=$_SESSION["redirecturl"];
	}
	
	//no sidebar: /wp-content/plugins/dapsociallogin/includes/templates/template1/daplogin.html
	//yes sidebar: /wp-content/plugins/dapsociallogin/includes/templates/sidebar1/daplogin.html
	
	$daplogin_form_content = getFileContent($_SESSION['pagetemplate'].'.html');
	
	//no sidebar: /wp-content/plugins/dapsociallogin/includes/templates/template1/facebooklogin.html
	//yes sidebar: /wp-content/plugins/dapsociallogin/includes/templates/sidebar1/facebooklogin.html
	
	//$facebookimagepath = $pathtoplugin . "/includes/images/btn_facebook_connect.png";
	
	//$facebook_form_content = getFileContent("facebooklogin.html");
	//$facebook_form_content = str_replace( '[FACEBOOKCONNECTIMAGESRC]', $facebookimagepath, $facebook_form_content); 
	
	//header('Content-type: text/html; charset=UTF-8');
	
	$user = null;
	$registerOrLoginNeeded = true;
	
	//NOT logged in to DAP, check if logged into FB, if yes, check if linked to FB, if yes, and if FB email matches dap email //redirect.
	// NOT logged in to DAP, check if logged into FB, if yes, check if linked to FB, if no, alert.
	// NOT logged in to DAP, check if logged into FB, if no, present login form, upon login, check if linked to FB, if yes, and //if FB email matches dap email redirect.
	
	if( !Dap_Session::isLoggedIn() ) {  
		//send viewer to login page
		logToFile("send viewer to login page, not logged in to dap",LOG_DEBUG_DAP);	
		$result=tryToConnectWithFaceBook($facebook);
		$registerOrLoginNeeded = true;	
		
		if($result == 0) {
			$fbaccountnotlinked=true;
		}
		
	}
	else if( Dap_Session::isLoggedIn() ) { 
		//get userid
		$session = Dap_Session::getSession();
		$user = $session->getUser();
		$user = Dap_User::loadUserById($user->getId()); //reload User object
		$registerOrLoginNeeded = true;
		if(!isset($user)) {
			//send viewer to login page
			$registerOrLoginNeeded = true;
			$result=tryToConnectWithFaceBook($facebook);
		} else {
			$registerOrLoginNeeded = false;
			logToFile("registerOrLoginNeeded=false, user already logged in to dap, showsidebar=".$_SESSION["showsidebar"],LOG_DEBUG_DAP);	
			if($_SESSION["showsidebar"] != "YES") {
			  //echo "<meta http-equiv='refresh' content='0;url=$redirectURL' />";
			  //exit;
			  
			  	$msg = "[" . MSG_ALREADY_LOGGEDIN_1 . " <a href='" . $redirectURL . "'>" . MSG_ALREADY_LOGGEDIN_2 . "</a>]";
				//$output = str_replace("%%LOGIN_FORM%%",$msg,$data);
				echo $msg;
				return;
			}
	        else {
				
			  $logoutHTMLFilepath = "/includes/templates/DAPLogoutHTML.html";
			  $output = file_get_contents($pathtoplugin . $logoutHTMLFilepath);
			  logToFile("showsidebar= Y, connected via FB, show logout: ".$_SESSION["showsidebar"]);
			  echo $output;
			  return ;
			}
		}
		
		if($result == 0) {
			$fbaccountnotlinked=true;
		}
    }

?>

<div id="fb-root"></div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript">
  window.fbAsyncInit = function() {
	FB.init({
	  appId      : "326849394103792",
	  status     : true, // check login status
	  cookie     : true, // enable cookies to allow the server to access the session
	  oauth      : true, // enable OAuth 2.0
	  xfbml      : true  // parse XFBML
	});
	
	FB.Event.subscribe('auth.login', function(response) {
	 // window.location.reload();
	});
	FB.Event.subscribe('auth.logout', function(response) {
	  window.location.reload();
	});
  };
  (function(d){
	 var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
	 js = d.createElement('script'); js.id = id; js.async = true;
	 js.src = "//connect.facebook.net/en_US/all.js";
	 d.getElementsByTagName('head')[0].appendChild(js);
   }(document));
 
</script>

<script>
function fblogin() {
  FB.login(function(response) {
	  //User logged in!
	  
	  if (response.authResponse) {
		  alert("in fb authresponse login");
		   jQuery.ajax({
			  url: '/dap/dap-facebookconnect.php',
			  cache: false,
			  type: 'POST',
			  data: {},
			  dataType: 'json',
			  success: function (returnval) {
				  alert("return="+returnval);
				if(returnval == 0) {
				  var fbnotconnected="<?php echo $_SESSION["clickedonfbconnect"]; ?>";
//				  alert("session set, now reload = " + fbnotconnected);
				  window.location.reload();
				}
			  }
			});
		  
	   } else {
		  alert("CANCELLED");
	  // user cancelled login
		
	   } 

  }, {perms:"email,user_checkins"});
} 
</script>
  
<?php 
$ret .= '<!DOCTYPE html>
<html lang="en-US">
  <head>
      <title>Simple Registration System</title>
      <style>
          body { font: normal 14px Verdana; }
          h1 { font-size: 24px; }
          h2 { font-size: 18px; }
      </style>
  </head>
  <body>
  <div id="wrap">
  <section id="main">';
  
  if (!$fbaccountnotlinked) //fb not connected to dap or not logged in to fb
  { 
  ?>

  <?php 
    $ret .=  $facebook_form_content;
  } else { 
  ?>
  <script type="text/javascript">
    alert("Your FB account is not linked to your membership, to link the accounts, please login to your membership profile and set the facebook email id. After the account is linked, then the next time you can connect to your membership using facebook login?"); 
  </script>
  <?php 
   } 
   $ret .=  $daplogin_form_content;
   $ret .= "</section>
  </div>
  </body>
</html>";

echo $ret;
?>