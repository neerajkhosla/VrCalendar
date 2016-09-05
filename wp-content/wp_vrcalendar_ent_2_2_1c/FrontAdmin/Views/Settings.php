<?php

if (edd_sample_check_license_new()){
	$VRCalendarSettings = VRCalendarSettings::getInstance();
	$availablePages = get_pages();
	$payment_mode = array(
		'live'=>__('Live', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'sandbox'=>__('Sandbox', VRCALENDAR_PLUGIN_TEXT_DOMAIN)
	);
	$auto_sync = array(
		'none'=>__('Disable', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'hourly'=>__('Hourly', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'twicedaily'=>__('Twice Daily', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'daily'=>__('Daily', VRCALENDAR_PLUGIN_TEXT_DOMAIN)
	);
	$attribution = array(
		'yes'=>__('Yes', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'no'=>__('No', VRCALENDAR_PLUGIN_TEXT_DOMAIN)
	);

	$load_jquery_ui_css = array(
		'yes'=>__('Yes', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'no'=>__('No', VRCALENDAR_PLUGIN_TEXT_DOMAIN)
	);

	$language = array(
		'english'=>__('English', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'french'=>__('French', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'spanish'=>__('Spanish', VRCALENDAR_PLUGIN_TEXT_DOMAIN)
	);
	$attr_currency = array(
		'AUD'=>__('Australian Dollar', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'AED'=> __('United Arab Emirates Dirham', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'ANG'=> __('Netherlands Antillean Gulden', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'ALL'=> __('Albanian Lek', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'ARS'=> __('Argentine Peso', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'AWG'=> __('Aruban Florin', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'BBD'=> __('Barbadian Dollar', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'BIF'=> __('Burundian Franc', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'BND'=> __('Brunei Dollar', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'BRL'=> __('Brazilian Real', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'BWP'=> __('Botswana Pula', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'BDT'=> __('Bangladeshi Taka', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'BMD'=> __('Bermudian Dollar', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'BOB'=> __('Bolivian Boliviano', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'BSD'=> __('Bahamian Dollar', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'BZD'=> __('Belize Dollar', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'CAD'=> __('Canadian Dollar', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'CLP'=> __('Chilean Peso', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'COP'=> __('Colombian Peso', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'CVE'=> __('Cape Verdean Escudo', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'CHF'=> __('Swiss Franc', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'CNY'=> __('Chinese Renminbi Yuan', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'CRC'=> __('Costa Rican Colon', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'CZK'=> __('Czech Koruna', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'DJF'=> __('Djiboutian Franc', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'DOP'=> __('Dominican Peso', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'DKK'=> __('Danish Krone', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'DZD'=> __('Algerian Dinar', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'EGP'=> __('Egyptian Pound', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'EUR'=> __('Euros', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'ETB'=> __('Ethiopian Birr', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'FKP'=> __('Falkland Islands Pound', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'FJD'=> __('Fijian Dollar', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'GBP'=> __('British Pound', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'GIP'=> __('Gibraltar Pound', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'GNF'=> __('Guinean Franc', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'GYD'=> __('Guyanese Dollar', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'GMD'=> __('Gambian Dalasi', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'GTQ'=> __('Guatemalan Quetzal', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'HNL'=> __('Honduran Lempira', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'HTG'=> __('Haitian Gourde', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'HKD'=> __('Hong Kong Dollar', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'HRK'=> __('Croatian Kuna', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'HUF'=> __('Hungarian Forint', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'IDR'=> __('Indonesian Rupiah', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'INR'=> __('Indian Rupee', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'ILS'=> __('Israeli New Sheqel', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'ISK'=> __('Icelandic Krona', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'JMD'=> __('Jamaican Dollar', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'JPY'=> __('Japanese Yen', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'KES'=> __('Kenyan Shilling', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'KMF'=> __('Comorian Franc', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'KYD'=> __('Cayman Islands Dollar', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'KHR'=> __('Cambodian Riel', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'KRW'=> __('South Korean Won', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'KZT'=> __('Kazakhstani Tenge', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'LAK'=> __('Lao Kip', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'LKR'=> __('Sri Lankan Rupee', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'LBP'=> __('Lebanese Pound', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'LRD'=> __('Liberian Dollar', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'MAD'=> __('Moroccan Dirham', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'MNT'=> __('Mongolian Togrog', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'MRO'=> __('Mauritanian Ouguiya', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'MVR'=> __('Maldivian Rufiyaa', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'MXN'=> __('Mexican Peso', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'MDL'=> __('Moldovan Leu', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'MOP'=> __('Macanese Pataca', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'MUR'=> __('Mauritian Rupee', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'MWK'=> __('Malawian Kwacha', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'MYR'=> __('Malaysian Ringgit', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'NAD'=> __('Namibian Dollar', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'NIO'=> __('Nicaraguan Cordoba', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'NPR'=> __('Nepalese Rupee', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'NGN'=> __('Nigerian Naira', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'NOK'=> __('Norwegian Krone', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'NZD'=> __('New Zealand Dollar', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'PAB'=> __('Panamanian Balboa', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'PGK'=> __('Papua New Guinean Kina', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'PKR'=> __('Pakistani Rupee', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'PYG'=> __('Paraguayan Guarani', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'PEN'=> __('Peruvian Nuevo Sol', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'PHP'=> __('Philippine Peso', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'PLN'=> __('Polish Zloty', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'QAR'=> __('Qatari Riyal', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'RUB'=> __('Russian Ruble', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'SBD'=> __('Solomon Islands Dollar', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'SEK'=> __('Swedish Krona', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'SHP'=> __('Saint Helenian Pound', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'SOS'=> __('Somali Shilling', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'SVC'=> __('Salvadoran Colon', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'SAR'=> __('Saudi Riyal', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'SCR'=> __('Seychellois Rupee', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'SGD'=> __('Singapore Dollar', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'SLL'=> __('Sierra Leonean Leone', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'STD'=> __('Sao Tome and Principe Dobra', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'SZL'=> __('Swazi Lilangeni', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'THB'=> __('Thai Baht', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'TTD'=> __('Trinidad and Tobago Dollar', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'TZS'=> __('Tanzanian Shilling', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'TOP'=> __('Tongan Paanga', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'TWD'=> __('New Taiwan Dollar', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'UGX'=> __('Ugandan Shilling', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'UYU'=> __('Uruguayan Peso', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'UAH'=> __('Ukrainian Hryvnia', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'USD'=> __('United States Dollar', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'UZS'=> __('Uzbekistani Som', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'VND'=> __('Vietnamese Dong', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'VUV'=> __('Vanuatu Vatu', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'WST'=> __('Samoan Tala', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'XOF'=> __('West African Cfa Franc', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'XAF'=> __('Central African Cfa Franc', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'XPF'=> __('Cfp Franc', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'YER'=> __('Yemeni Rial', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'ZAR'=> __('South African Rand', VRCALENDAR_PLUGIN_TEXT_DOMAIN)
	);
	?>
	<div class="wrap vrcal-content-wrapper vr-booking vr-dashboard">
		<h2><?php _e('Settings', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></h2>
		<div class="left-panel-vr-plg">
		<?php include('sidebar.php'); ?>
		</div>
		<div class="right-panel-vr-plg">
                    <div class="tabs-wrapper">
			<div class="tabs-content-wrapper">
				<form method="post" action="" >
					<div id="general-options" class="tab-content tab-content-active">
						<?php require(VRCALENDAR_PLUGIN_DIR.'/FrontAdmin/Views/Part/Settings/General.php'); ?>
					</div>
					<div>
						<input type="hidden" name="vrc_cmd" id="vrc_cmd" value="VRCalendarFrontAdmin:saveSettings" />
						<input type="submit"  id="setting_sbtn" value="<?php _e('Save', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" class="button button-primary">
					</div>
				</form>
			</div>
                    </div>
                </div>
	</div>
<script>
jQuery(document).ready(function(){
    jQuery('#setting_sbtn').on("click",function(){
                var thank_you_page  = jQuery('#thank_you_page').val();
                var status = validateUrl(thank_you_page);
                if(status===false){
                    jQuery('#thank_you_page').after("<span class='error_box'>Please enter valid url</span>");
                    jQuery('.error_box').delay(2000).fadeOut('slow');
                    jQuery('#thank_you_page').focus();
                    return false; 
                }
                var cancel_payment_page  = jQuery('#cancel_payment_page').val();
                var status_cancel = validateUrl(cancel_payment_page);
                if(status_cancel===false){
                    jQuery('#cancel_payment_page').after("<span class='error_box'>Please enter valid url</span>");
                    jQuery('.error_box').delay(2000).fadeOut('slow');
                    jQuery('#cancel_payment_page').focus();
                    return false; 
                }
                
                var paypal_email  = jQuery('#paypal_email').val();
                var status_email = validateEmail(paypal_email);
                if(status_email===false){
                    jQuery('#paypal_email').after("<span class='error_box'>Please enter valid email</span>");
                    jQuery('.error_box').delay(2000).fadeOut('slow');
                    jQuery('#paypal_email').focus();
                    return false;
                }
   });
});

function validateUrl(textval)   // return true or false.
{
    var urlregex = new RegExp(
          "^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.){1}([0-9A-Za-z]+\.)");
    return urlregex.test(textval);
}

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

</script>
<?php } else {
	_e('Please check your license key', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
}
?>
