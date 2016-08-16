<?php
	if( Dap_Session::isLoggedIn() ) { 
		$session = Dap_Session::getSession();
		$user = $session->getUser();
	}
?>
<script language="JavaScript" type="text/javascript" src="/dap/javascript/ajaxWrapper.js"></script>
<script language="JavaScript" type="text/javascript" src="/dap/javascript/common.js"></script>
<script language="javascript" type="text/javascript" src="/dap/javascript/jsstrings.php"></script>
<script language="JavaScript" type="text/javascript">

var affId = <?php echo $user->getId(); ?>;
var globalError = "";
var whatRequest = "";
var site_url = "<?php echo SITE_URL_DAP; ?>";

function processChangea(responseText, responseStatus, responseXML) {
	if (responseStatus == 200) {// 200 means "OK"
		var resource = eval('(' + responseText + ')');
		if(resource.whatRequest == 'loadAffiliatePayments') {
			changeDiv('aff_payments_div',resource.responseJSON);
		} else if(resource.whatRequest == 'loadAffiliateEarningsSummary') {
			changeDiv('aff_earnings_summary_div',resource.responseJSON);
		} else if(resource.whatRequest == 'loadAffiliateEarningsDetails') {
			changeDiv('aff_earnings_details_div',resource.responseJSON);
		} else if(resource.whatRequest == 'loadUser') {
			changeLoadUser(resource.responseJSON);
		} else if(resource.whatRequest == 'updateUser') {
			changeDiv('user_msg_div',resource.responseJSON);
			NLBfadeBg('user_msg_div','#009900','#FFFFFF','1000');
			loadUser(affId);
		}  else if(resource.whatRequest == 'loadAffiliateStats') {
			changeDiv('aff_stats_div',resource.responseJSON);
		}  else if(resource.whatRequest == 'loadAffiliatePerformanceSummary') {
			changeDiv('aff_perf_summary_div',resource.responseJSON);
		}
	} else {// anything else means a problem
		//alert("There was a problem in the returned data:\n");
	}
}


function loadAffiliateStats() {
	changeDiv('aff_stats_div','Please wait... Loading Affiliate Stats ...<br><img src="/dap/images/progressbar.gif">');
	url  =  '/dap/admin/ajax/loadAffiliateStatsAjax.php';
	var request = new ajaxObject(url,processChangea);
	request.update('email=<?php echo $user->getEmail(); ?>' + '&whatRequest=loadAffiliateStats');
}


function loadAffiliatePayments() {
	changeDiv('aff_payments_div','Please wait... Loading Affiliate Payments ...<br><img src="/dap/images/progressbar.gif">');
	url = '/dap/admin/ajax/loadAffiliatePaymentsAjax.php';
	request = new ajaxObject(url,processChangea);
	request.update('email=<?php echo $user->getEmail(); ?>' + '&whatRequest=loadAffiliatePayments');
}

function loadAffiliateEarningsSummary() {
	changeDiv('aff_earnings_summary_div','Please wait... Loading Earnings Summary ...<br><img src="/dap/images/progressbar.gif">');
	url = '/dap/admin/ajax/loadAffiliateEarningsSummaryAjax.php';
	request = new ajaxObject(url,processChangea);
	request.update('email=<?php echo $user->getEmail(); ?>' + '&whatRequest=loadAffiliateEarningsSummary');
}

function loadAffiliateEarningsDetails() {
	changeDiv('aff_earnings_details_div','Please wait... Loading Earnings Details ...<br><img src="/dap/images/progressbar.gif">');
	url = '/dap/admin/ajax/loadAffiliateEarningsDetailsAjax.php';
	request = new ajaxObject(url,processChangea);
	request.update('email=<?php echo $user->getEmail(); ?>' + '&whatRequest=loadAffiliateEarningsDetails','POST');
}

function loadAffiliatePerformanceSummary() {
	showLongProgressBar('aff_perf_summary_div', 'Please wait... Loading Your Affiliate Performance Summary...<br>');
	url  =  '/dap/admin/ajax/loadAffiliatePerformanceSummaryAjax.php';
	var start_date = '01-01-2008';
	var request = new ajaxObject(url,processChangea);
	request.update(
		'email=<?php echo $user->getEmail(); ?>' + 
		'&start_date=' + start_date + 
		'&whatRequest=loadAffiliatePerformanceSummary'
	);
	return;
}

</script>
<?php getCss(); ?>

<form name="AffLinksForm" id="AffLinksForm">
  <div id="affiliate_details_div"> <a name="affdetails" id="affdetails"></a>
    <table class="dap_affiliate_table_main">
      <!-- 
      <tr>
        <td nowrap class="affiliate_section_heading" align="center"><div align="center">< echo AFFILIATE_INFO_TOTALEARNINGS_SUBHEADING; ></div></td>
      </tr>
      <tr>
        <td align="center"><div id="aff_earnings_summary_div" align="center"></div></td>
      </tr>
      <tr>
		<td nowrap class="scriptheader" align="center">&nbsp;</td>
  	  </tr>
      -->
      <tr>
		<td nowrap class="affiliate_section_heading" align="center"><?php echo AFFILIATE_INFO_PERFSUMM_SUBHEADING; ?></td>
  	  </tr>
      <tr>
      	<td><div id="aff_perf_summary_div" align="center"></div></td>
      </tr>
      <tr>
        <td><p>&nbsp;</p>
          <table>
            <tr>
              <td><div align="center" class="affiliate_section_heading"><?php echo AFFILIATE_INFO_AFFLINK_SUBHEADING; ?></div></td>
            </tr>
            <tr>
              <td><p align="left"><span class="affiliate_sub_heading"><?php echo AFFILIATE_INFO_YOURAFFLINKHOME_LABEL; ?></span><br>
                  <input name="textfield2" onClick="this.select();" type="text" value="<?php echo SITE_URL_DAP . "/dap/a/?a=".$user->getId(); ?>" size="60">
                  &nbsp;&nbsp;<a href="<?php echo SITE_URL_DAP . "/dap/a/?a=".$user->getId(); ?>" target="_blank"><?php echo AFFILIATE_INFO_TEST_TEXT; ?></a></p>
                <p align="left" class="affiliate_sub_heading"><?php echo AFFILIATE_INFO_AFFLINKSPECIFIC_LABEL; ?></p>
                <p align="left" class="regulartextLarge"><?php echo AFFILIATE_INFO_AFFLINKSPECIFIC_EXTRA_TEXT; ?><br>
                  <input name="textfield22" onClick="this.select();" type="text" value="<?php echo SITE_URL_DAP . "/dap/a/?a=".$user->getId(); ?>&p=www.example.com/somepage.html" size="60">
                </p></td>
            </tr>
          </table>
          <p>&nbsp;</p>
          <table width="100%"  border="0" cellspacing="0" cellpadding="5">
            <tr>
              <td><div align="center" class="affiliate_section_heading"><?php echo AFFILIATE_INFO_PAYMENT_DETAILS_SUBHEADING; ?></div></td>
            </tr>
            <tr>
              <td><div id="aff_payments_div"></div></td>
            </tr>
          </table>
          <p>&nbsp;</p>
          <table width="100%" border="0" cellspacing="0" cellpadding="5">
            <tr>
              <td><div align="center" class="affiliate_section_heading"><?php echo AFFILIATE_INFO_EARNINGS_DETAILS_SUBHEADING; ?></div></td>
            </tr>
            <tr>
              <td><div id="aff_earnings_details_div"></div></td>
            </tr>
          </table>
          <?php 
	  	if(!$session->isAdmin()) { ?>
          <script>loadAffiliateEarningsDetails();</script>
          <?php } else { ?>
          <script>document.getElementById('aff_earnings_details_div').innerHTML = '<strong>NOTE: You are seeing this message only because you are a DAP Admin</strong><br/><br/>Sorry, this table has too much data to view within WordPress. <a href="/dap/">Click here to view it on your Admin Home Page</a>';</script>
          <?php } ?>
          <p>&nbsp;</p>
          <table width="100%"  border="0" cellspacing="0" cellpadding="5">
            <tr>
              <td><div align="center" class="affiliate_section_heading"><?php echo AFFILIATE_INFO_TRAFFIC_STATISTICS_SUBHEADING; ?></div></td>
            </tr>
            <tr>
              <td><div id="aff_stats_div" style="position:relative; width:100%; height:300px; background-color:#ffffff; overflow:scroll;"></div></td>
            </tr>
          </table></td>
      </tr>
    </table>
  </div>
</form>
<script language="JavaScript" type="text/javascript">
loadAffiliatePayments();
//loadAffiliateEarningsSummary();
loadAffiliateStats();
loadAffiliatePerformanceSummary();
</script>
