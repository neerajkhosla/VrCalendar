<?php

//4.7
define("MSG_REQUIRED_FIELD","* = Required field");

//Added post 4.6.2
//1clickbuy
define("MSG_PURCHASE_COMPLETED","Thanks for the purchase!");
define("MSG_PURCHASE_FAILED","Sorry, your purchase could not be completed.");

//Added post 4.6.1
define("MSG_WP_DAP_EMAIL_AND_USERNAME_MATCH","MSG_WP_DAP_EMAIL_AND_USERNAME_MATCH. DAP/WP Email & Username match. AUTO.");
define("MSG_WP_DAP_EMAIL_DONTMATCH","MSG_WP_DAP_EMAIL_DONTMATCH. DAP/WP Username match but tied to different email Ids. MANUAL.");
define("MSG_WPUSERNAME_MISSING_IN_DAP","MSG_WPUSERNAME_MISSING_IN_DAP. DAP/WP Email match. DAP Username missing. Set DAP Username=WP Username. AUTO.");
define("MSG_WP_DAP_USERNAME_CONFLICT","MSG_WP_DAP_USERNAME_CONFLICT. DAP/WP Email match but tied to different user names. Set DAP Username=WP Username. AUTO.");
define("MSG_WPUSER_NOT_FOUND_IN_DAP","MSG_WPUSER_NOT_FOUND_IN_DAP. WP Username and WP email not found in DAP. OLD WP USER. MANUAL.");
define("MSG_WP_DAP_USERNAME_MISSING","MSG_WP_DAP_USERNAME_MISSING. DAP/WP email match. DAP/WP username EMPTY. Generate new username automatically. AUTO.");
define("MSG_WPUSERNAME_MISSING","DAP/WP Email match. DAP Username is set. WP username is missing. Sync DAP User to WP. AUTO.");
define("MSG_WPEMAIL_NOT_FOUND_IN_DAP","MSG_WPEMAIL_NOT_FOUND_IN_DAP. WP email not found in DAP. WP Username Missing. OLD WP USER. MANUAL.");

//Added post 4.5.2
define("MSG_WSOPRO_THANKYOU","Thanks for the purchase! You can login to your membership using these credentials:");

//Added post 4.5.2 - 01/27 - DAP Social Login / Social Signup Plugins
define("MSG_FIRST_NAME","Please Enter First Name.");
define("MSG_EMAIL","Please Enter Your Membership Email Id.");
define("MSG_CAPTCHA","Please Enter Captcha Code.");
define("MSG_CAPTCHA_DOES_NOT_MATCH", "Sorry, the captcha code does not match.");
define("MSG_ENTER_PASSWORD", "Please Enter Your Membership Password.");

// Added post v4.5 - CS
define("NO_PRODUCTS_AVAILABLE_CATEGORY","Sorry, currently no products are available in this category.");
define("NO_PRODUCTS_AVAILABLE","Sorry, currently no products are available in the store.");
define("NOT_ENOUGH_AVAILABLE_CREDITS","Sorry, you do not have enough credits to redeem this product");

define("CS_NO_PRODUCTS_AVAILABLE_MSG","Sorry, currently no products are available in the store.");
define("CS_NO_PRODUCTS_ON_THIS_PAGE_MSG","Sorry, there are no more products. Go back to previous page");
define("CREDITS_AVAILABLE_TEXT","Credits Available");
define("NOT_AVAILABLE_FOR_CREDITS_TEXT","Sorry, this product/content is not available for credits");
define("MSG_CS_COULD_NOT_ADDTOCART","Sorry, could not add the selected item to Cart");
define("MSG_CS_COULD_NOT_REMOVEFROMCART","Sorry, could not remove the selected item from Cart");
define("MSG_CS_NOTLOGGEDIN","Sorry, please login to redeem credits");
define("MSG_CS_REDEEMSELECTEDITEMS","Do you want to proceed with the purchase of the selected items using credits? Press Ok to continue with the purchase.");
define("MSG_CS_REDEEMPRODUCT","Do you want to proceed with the purchase of this product using credits? Press Ok to continue with the purchase.");


//---------- Added post v4.4.3 ---------//

//DAP CART MESSAGES
define("PASSWORD_REQUIRED","Password is a required field. Please enter password.");
define("PASSWORD_CONFIRM_MATCH","Password and the re-typed Password fields must match.");
define("PASSWORD_ONLY_ALPHANUMERIC_ALLOWED","Only alphanumberic characters allowed in Password.");
define("TANDC_REQUIRED","Please accept Terms & Conditions to proceed!");

define("CUSTOM_FIELD_REQUIRED","cannot be empty. It's a required field.");



define("MSG_ALREADY_INACTIVE","Sorry, it appears that you have previously signed up for this and haven't confirmed your sign up yet. Please check your email inbox<br/>and click on the activation link before your account can be activated. You may now click on the 'back' button to go back to the previous page.");
define("COLNAME_REFERRAL_DETAILS","Referral Details");
define("COLNAME_REFERRAL_DATE","Referral Date");
define("COLNAME_REFUND_DATE","Refund Date");
define("COLNAME_PRODUCT","Product");
define("COLNAME_EARNING_TYPE","Earning Type");
define("COLNAME_LSC","L:Lead, S:Sale, C:Credits");
define("MSG USERNAME TAKEN","Sorry, this username is already in USE. Please pick a different username");
define("MSG_THANKYOU_SIGNUP_ACTIVATION","SUCCESS! Thank you for signing up. Please check your email for further details on how to activate your account.");

//---------- Added in v4.4 ---------//
define("AFFILIATE_INFO_PERFSUMM_SUBHEADING","Affiliate Performance Summary");
define("AFFILIATE_INFO_HEADING","Affiliate Details"); //used only in dap/index.php
define("MSG_NO_COMM","Sorry, no commissions earned yet.");
define("USER_PROFILE_SUCCESS_MESSAGE_USER","SUCCESS! Your profile has been updated.");
define("MSG_ERROR_EMAILEXISTSVB", "Sorry, this email id is already in use in our vBulletin Forum. Please try using a different email id.");
define("MSG_SUCCESS_USERUPDATE_NOVB", "SUCCESS! Your profile has been updated, but could not register this username in our vBulletin Forum. Please contact the site admin for help.");
define("AUTOMATED_AUTORESPONDER_EMAIL_SUBJECT", "New Content Available:\n%%CONTENT_NAME%%");
define("MSG_INVALID_PASS","Sorry, the password you have chosen contains special characters. Only Alphabets and Numbers allowed. Please go 'back' and choose a different password.");
define("COLNAME_EARNINGTYPE_TEXT","Cash/Credit");
define("USER_PROFILE_NEW_PASSWORD_LABEL_DAPUSERPROFILE","Password<br/>(only if changing)");

//---------- Added in v4.3 ---------//
define("USER_LINKS_COMINGSOON_TEXT","Coming Soon...");
define("USER_LINKS_COMINGSOON_PREFIX_TEXT","[In XXX days]"); //do not modify the text "XXX"
define("MSG_UNSUBSCRIBE","Sorry to see you go... You will not receive any further emails from us.");
define("MSG_MANDATORY","Sorry, all fields are mandatory. Please go 'back' and fill up the missing information.");
define("MSG_INVALID_EMAIL","Sorry, the email address you entered is invalid. Please go 'back' and enter a valid email address.");
define("MSG_INVALID_COUPON","Sorry, the coupon code you entered is invalid. Please go 'back' and enter a valid coupon code.");
define("MSG_INVALID_PRODUCT","Sorry, the product you are trying to sign up for is either invalid, or not authorized for free signup. Please email the web site owner and let them know about this error.");
define("MSG_MISSING_COUPON","Sorry, coupon code is a required field. Please go 'back' and enter a valid coupon code.");
define("MSG_PAYMENT_FAILED","Payment failed. Please contact the site admin. ");
define("MSG_THANKYOU_SIGNUP","SUCCESS! Thank you for signing up. Please check your email for further details.");
define("BUTTON_UPDATE","Update");
define("SUCCESS_CREATION","SUCCESS! Your account has been successfully created! Please check your inbox for login information...");
define("NO_AUTH","Sorry, you are either not logged in, or not authorized to perform this operation.");
define("MSG_ALREADY_SIGNEDUP","It appears that you have already signed up for this. No further action is required at this time.");


//---------- Added 03/22/2011 ---------//
define("MSG_ALREADY_LOGGEDIN_1","You are already logged in.");
define("MSG_ALREADY_LOGGEDIN_2","Click here to continue...");
define("MSG_PLS_LOGIN","Sorry, you must log in before you can view this content.");
define("MSG_CLICK_HERE_TO_LOGIN","Click here to log in");
define("MSG_SORRY_EMAIL_NOT_FOUND","Sorry, No User found with the email address ");
define("MSG_PASSWORD_SENT","Please check your email. Your Password has been sent to ");


//---------- GENERAL TEXT ---------//
define ("ACTIVATION_EMAIL_SUBJECT", "%%FIRST_NAME%%, Welcome to %%SITE_NAME%% (Activation)");
define ("AFF_PAYMENT_EMAIL_SUBJECT", "%%FIRST_NAME%%, You've Got An Affiliate Payment");
define ("FORGOT_PASSWORD_EMAIL_SUBJECT", "Lost Password");
define ("LOCKED_EMAIL_SUBJECT", "Your account has been locked");
define ("UNLOCKED_EMAIL_SUBJECT", "Your account has been Unlocked");
define ("INVALID_PASSWORD_MSG","Sorry, either you have entered an invalid username/password, or you may not have activated your account yet.");
define("SUCCESS_ACTIVATION","SUCCESS! Your account has been successfully activated! We have now sent you an email with your login details that you can use to log in below...");

define("MSG_SORRY_NO_DATA_FOUND","Sorry, no data found.");
define("COLNAME_AFFID_TEXT","Aff Id");
define("COLNAME_NAME_TEXT","Name");
define("COLNAME_FIRSTNAME_TEXT","First Name");
define("COLNAME_LASTNAME_TEXT","Last Name");
define("COLNAME_EMAIL_TEXT","Email");
define("COLNAME_AMTEARNED_TEXT","Amount Earned");
define("COLNAME_DATETIME_TEXT","Date/Time");
define("COLNAME_AMTPAID_TEXT","Amount Paid");
define("COLNAME_HTTPREFERER_TEXT","HTTP Referer");
define("COLNAME_DESTINATION_TEXT","Destination");


//---------- USER PROFILE TEXT ---------//
define("USER_PROFILE_HEADING_TEXT","Profile Information");
define("USER_PROFILE_FIRST_NAME_LABEL","First Name*");
define("USER_PROFILE_LAST_NAME_LABEL","Last Name");
define("USER_PROFILE_EMAIL_LABEL","Email*");
define("USER_PROFILE_USER_NAME_LABEL","Username");
define("USER_PROFILE_NEW_PASSWORD_LABEL","Password*");
define("USER_PROFILE_REPEAT_PASSWORD_LABEL","Repeat Password<br/>(only if changing)");
define("USER_PROFILE_PAYPAL_EMAIL_LABEL","Paypal Email");
define("USER_PROFILE_PAYPAL_EMAIL_EXTRA_LABEL","For Affiliates Only");
define("USER_PROFILE_COMPANY_LABEL","Company");
define("USER_PROFILE_TITLE_LABEL","Title");
define("USER_PROFILE_ADDRESS1_LABEL","Address Line 1");
define("USER_PROFILE_ADDRESS2_LABEL","Address Line 2");
define("USER_PROFILE_CITY_LABEL","City");
define("USER_PROFILE_STATE_LABEL","State/Province/Region");
define("USER_PROFILE_ZIP_LABEL","Zip/Postal Code");
define("USER_PROFILE_COUNTRY_LABEL","Country");
define("USER_PROFILE_PHONE_LABEL","Phone");
define("USER_PROFILE_FAX_LABEL","Fax");
define("USER_PROFILE_UNSUBSCRIBE_LABEL","Check to receive product and<br/>account related emails. Uncheck <br/>to unsubscribe (not recommended)");
define("USER_PROFILE_SUCCESS_MESSAGE","SUCCESS! User has been successfully updated.");


//---------- AFFILIATE PAGE LABELS ---------//
define("AFFILIATE_INFO_TOTALEARNINGS_SUBHEADING","Total Earnings");
define("AFFILIATE_INFO_AFFLINK_SUBHEADING","Affiliate Link");
define("AFFILIATE_INFO_YOURAFFLINKHOME_LABEL","Your Affiliate Link - redirects to home page:");
define("AFFILIATE_INFO_AFFLINKSPECIFIC_LABEL","Affiliate Link To Specific Page:");
define("AFFILIATE_INFO_AFFLINKSPECIFIC_EXTRA_TEXT","To link to a specific page, just add the text &quot;&amp;p=&lt;insert_url_here&gt;&quot; to the end of your affiliate link, like this: (no &quot;http://&quot; in the 2nd link at the end - should start with &quot;www&quot;)");
define("AFFILIATE_INFO_TEST_TEXT","test");
define("AFFILIATE_INFO_PAYMENT_DETAILS_SUBHEADING","Payment Details");
define("AFFILIATE_INFO_EARNINGS_DETAILS_SUBHEADING","Earnings Details");
define("AFFILIATE_INFO_TRAFFIC_STATISTICS_SUBHEADING","Traffic Statistics");


//---------- USER LINKS PAGE TEXT ---------//
define("USER_LINKS_YOUCURRENTLYHAVEACCESSTO_TEXT","You currently have access to ");
define("USER_LINKS_PRODUCTS_TEXT"," product(s).");
define("USER_LINKS_ACCESS_START_DATE_TEXT","Access Start Date");
define("USER_LINKS_ACCESS_END_DATE_TEXT","Access End Date");
define("USER_LINKS_DESCRIPTION_TEXT","Description");
define("USER_LINKS_LINKS_TEXT","Links");
define("USER_LINKS_NOLINKSFOUND_TEXT","Sorry, no content found. This could be because no content has been made available to you yet, or because your access to this product has expired.");


?>