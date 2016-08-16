<?php
	$lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
	if(file_exists($lldocroot . "/dap/dap-config.php")) include_once ($lldocroot . "/dap/dap-config.php");

	$blogpath=urldecode($_SESSION["blogpath"]);
	if(!validateRequest($blogpath)) exit;

	$columns=$_SESSION['CS_COLUMNS'];
	if($columns > 1) {
	  $template_path=$blogpath."/wp-content/plugins/dapcreditstorefront/categories/html/multicolumn/";
	  $temp_content=getCSTemplateContent($template_path, "template.html");
	  $formcss=getFormCSS($template_path, "multicolumn.css");
	}
	else {
	  $template_path=$blogpath."/wp-content/plugins/dapcreditstorefront/categories/html/" . $columns . "column/";
	  $temp_content=getCSTemplateContent($template_path, "template.html");
	  $ind_item_temp_content=getCSTemplateContent($template_path, "individualItemsTemplate.html");
	  $ind_item_temp_button_content=getCSTemplateContent($template_path, "individualItemsButtonTemplate.html");
	  $formcss=getFormCSS($template_path, "1column.css");
	}

	if( Dap_Session::isLoggedIn() ) {
		$session = Dap_Session::getSession();
		$user = $session->getUser();
		$user = Dap_User::loadUserById($user->getId()); //reload User object
		$userId=$user->getId();
	}

	$btncode=urldecode($_SESSION["CS_BTNCODE"]);
	$categoryname=$_SESSION['CS_CATEGORYNAME'];

	if(($categoryname != "") && ($categoryname != "ALL"))
		$categories = Dap_Category::loadCategoryByCode(urldecode($categoryname));
 	else
		$categories = Dap_Category::loadAllCategories("");

	$resellChild = "Y";
	if (defined('RESELL_CHILD')) {
	  $resellChild = RESELL_CHILD;
	}

	if(!defined('CS_NO_PRODUCTS_AVAILABLE_MSG'))
		$cs_no_prod_available="Sorry, currently no products are available in the store.";
	else
	  $cs_no_prod_available=CS_NO_PRODUCTS_AVAILABLE_MSG;

	if(!defined('CS_NO_PRODUCTS_ON_THIS_PAGE_MSG'))
		$cs_no_more_prod_available="Sorry, there are no more products. Go back to previous page.";
	else
	  $cs_no_more_prod_available=CS_NO_PRODUCTS_ON_THIS_PAGE_MSG;

	$atleastoneproductavailable=0;
	logToFile("creditStore.inc: READY : userid=".$userId);

	$pagenumber=0;
	if( (isset($_REQUEST["pagenumber"])) && ($_REQUEST["pagenumber"] != ""))
	  $pagenumber=intval($_REQUEST["pagenumber"]) - 1;

	$numproductsperpage=3;
	$numinditemsperpage=4;

	if((isset($_REQUEST["numproductsperpage"])) && ($_REQUEST["numproductsperpage"] != "")) {
	  $numproductsperpage=intval($_REQUEST["numproductsperpage"]);
	  $_SESSION["numproductsperpage"]=$numproductsperpage;
	}
	else if((isset($_SESSION["numproductsperpage"])) && ($_SESSION["numproductsperpage"] != "")) {
	  $numproductsperpage=intval($_SESSION["numproductsperpage"]);
	}

	if((isset($_REQUEST["numinditemsperpage"])) && ($_REQUEST["numinditemsperpage"] != "")) {
	  $numinditemsperpage=intval($_REQUEST["numinditemsperpage"]);
	  $_SESSION["numinditemsperpage"]=$numinditemsperpage;
	}
	else if((isset($_SESSION["numinditemsperpage"])) && ($_SESSION["numinditemsperpage"] != "")) {
	  $numinditemsperpage=intval($_SESSION["numinditemsperpage"]);
	}

	//$getCurrentPageArray = $pageProductsArray[$pagenumber];
	$pagearr=array();
	$catIdIndexArr=array();

	  // get results for the requested pagenumber from page array
	logToFile("creditStore.inc: requested pagenumber=" . $pagenumber);
	logToFile("creditStore.inc: requested numproductsperpage=" . $numproductsperpage);

	$totalnumberofitemsperproduct=array();
	$totalnumberofproducts=buildProductsPerPageArray ($pagearr, $catIdIndexArr, $numproductsperpage, $totalnumberofitemsperproduct,$categories, $userId,$pagenumber);

	logToFile("creditStore.inc: totalnumberofproducts=" . $totalnumberofproducts);
  //  $catArray = getProductsForRequestedPage($pagearr, $pagenumber);
	$skipeverythingdisplaynoprod=false;
	$displayNoMoreProductsFound=false;

	$catArray=$pagearr[$pagenumber];
	$num_pages=count($pagearr);
	$number_of_cat_on_requested_page=count($catArray);
	if(!count($catArray))  {
	  //nothing found in the requested page check if nothing even on first page
	  if($_REQUEST["pagenumber"] != 1) {
		$firstpageprods=count($pagearr[0]);
		if($firstpageprods==0) {
		  $skipEverythingDisplayNoProd=true;
		}
		else {
		  $displayNoMoreProductsFound=true;
		}
	  }
	  else {
		$skipEverythingDisplayNoProd=true;
	  }
	}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="<?php echo $formcss ?>" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript" src="/dap/javascript/ajaxWrapper.js"></script>
<script language="javascript" type="text/javascript" src="/dap/javascript/common.js"></script>
<script type="text/javascript" src="<?php echo $blogpath;?>/wp-content/plugins/dapcreditstorefront/categories/html/js/common.js"></script>
<script language="javascript">

function get_individual_items_array() {
	<?php
	$k=0;
	for ($i=0; $i < $number_of_cat_on_requested_page; $i++) {
		$prodArray=$catArray[$i];
		for ($j=0; $j < count($prodArray); $j++) {
			if(!isset($prodArray[$j]))break;
		?>
	//	alert("number of line items="+<?php echo count($prodArray[$j][3]); ?>);
		<?php
			if(!count($prodArray[$j][3])) {
				$individual_items_array[$k]=array();
			}
			else {
				for ($r=0; $r < count($prodArray[$j][3]); $r++) {
					$individual_items_array[$k][$r][0]='option'.$k;
					$individual_items_array[$k][$r][1]=$prodArray[$j][3][$r]["res_credits_assigned"]+'c';
					$individual_items_array[$k][$r][2]=$prodArray[$j][3][$r]["excerpt"];
					$converted = html_entity_decode($prodArray[$j][3][$r]["name"] , ENT_COMPAT, 'UTF-8');
					$individual_items_array[$k][$r][3]=html_entity_decode ($converted);
				}
			}
		?>
			console.log("product array="+ "<?php echo $k;?>");
		<?php
		$k++;
		}

	}

	?>
	return <?php echo json_encode($individual_items_array); ?>;
}

jQuery(document).ready(function () {
  jQuery("#singleCol .prodDesc").css("font-size", "<?php echo $_SESSION['producttextfont']; ?>");

  //jQuery(".listUL li").css("width", "<?php //echo $_SESSION['productboxwidth']; ?>");

  jQuery("#multiCol .creditsCount").css("color", "<?php echo $_SESSION['creditscounttext']; ?>");
  //jQuery("#multiCol .orderTitle").css("background", "<?php //echo $_SESSION['totalcreditsbarbackground']; ?>");
  //jQuery("#multiCol .orderTitle").css("color", "<?php //echo $_SESSION['totalcreditsavailabletextcolor']; ?>");

  jQuery("#singleCol .redeemBar .txtCredits").css("color", "<?php echo $_SESSION['credittextcolor']; ?>");
 //text color
  jQuery("#singleCol .creditsAvail").css("color", "<?php echo $_SESSION['totalcreditsavailabletextcolor']; ?>");
  //background color
  jQuery("#singleCol .creditsAvail").css("background", "<?php echo $_SESSION['totalcreditsbarbackground']; ?>");
  jQuery("#singleCol .prodBox").css("background", "<?php echo $_SESSION['productdetailsbackground']; ?>");
  jQuery("#singleCol .redeemBar").css("background", "<?php echo $_SESSION['creditbackground']; ?>");
  jQuery("#singleCol .individualItems ol li").css("background", "<?php echo $_SESSION['buyindividuallineitembackground']; ?>");
  jQuery("#singleCol .individualItems .individualRight").css("background", "<?php echo $_SESSION['buyindividuallineitembackground']; ?>");
  jQuery("#singleCol .individualItems").css("background", "<?php echo $_SESSION['buyindividualitemsbackground']; ?>");

	var numproductsperpage = <?php echo $numproductsperpage; ?>,
		total_number_of_products = <?php echo $totalnumberofproducts; ?>,
		number_of_pages = <?php echo $num_pages; ?>,
		number_of_cat_per_page=<?php echo count($pagearr[$pagenumber]) ; ?>;


		// Two Variables are needed from the backend:
		// 1- numinditemsperpage permitted
		// 3- total number of individual items in each product
	var numinditemsperpage = <?php echo $numinditemsperpage; ?>;
	//var total_number_of_individual_items_per_product = new Array();
	//	total_number_of_individual_items_per_product[0] = 5;
	//	total_number_of_individual_items_per_product[1] = 0;
	//	total_number_of_individual_items_per_product[2] = 5;
	var total_number_of_individual_items_per_product =  <?php echo json_encode($totalnumberofitemsperproduct); ?>;

		var item_class = '';
		for (var i=0; i < numproductsperpage; i++) {
		//	alert("ind items per product:"+i+"="+total_number_of_individual_items_per_product[i]);
			var number_of_individual_items_pages = Math.ceil(total_number_of_individual_items_per_product[i] / numinditemsperpage);
			for (var j=0; j < number_of_individual_items_pages; j++) {
				if(j==0) {
					item_class = 'selected';
				} else {
					item_class = '';
				}
				$('.prodBox').eq(i).next('.individualItems').find('.buySelectedItems').find('.individualItemsPagination').append('<li class="item '+item_class+'"><a href="javascript:void(0)" class="page_number" data-number="'+j+'">'+parseInt(j+1)+'</a></li>');
			}
			$('.individualItems').eq(i).find('li').not('.headerIndividual').not('.buySelectedItems').each(function(index,element) {
				if(index < numinditemsperpage) {
					$(element).show();
				}
			})
		};
		$('.page_number').click(function(e) {
			$this = $(this);
			$(this).parents('.buySelectedItems, .productsPagination').find('.item').removeClass('selected');
			$(this).parent().addClass('selected');
			var offset = $this.data('number');
			$this.parents('.individualItems').find('li').not('.headerIndividual').not('.buySelectedItems').not('.item').hide();
			$this.parents('.individualItems').find('li').not('.headerIndividual').not('.buySelectedItems').each(function(index,element) {
				if(index > offset*numinditemsperpage - 1 && index < offset*numinditemsperpage + numinditemsperpage) {
					$(element).show();
				}
			})
		})


		// Number of Characters
		//limit text with jquery
		function textlimit(element,limit) {

			var $elem = $(element);
			// Adding this allows u to apply this anywhere
			var $limit = limit;
			// The number of characters to show
			var $str = $elem.text().replace(/^\s+/g, "");
			console.log(limit);
			console.log($str.length);
			if($str.length > limit){
				// Getting the text
				var $strtemp = $str.substr(0, $limit);
				// Get the visible part of the string
				$str = $strtemp + '<span class="hideText">' + $str.substr($limit, $str.length) + '</span><span class="dots"> ... </span><a href="javascript:void(0)" class="readMore">More</a>';
				$elem.html($str);
			}

		}
		//Readmore clickable text
		jQuery('#multiCol').find('.prodDesc').each(function(index, element) {
			textlimit(element,90);
		});
		jQuery('#singleCol').find('.prodDesc > p').each(function(index, element) {
			textlimit(element,<?php echo $_SESSION['productdescmaxlen'];?>);
		});
		jQuery('#multiCol,#singleCol').find('.readMore').click(function() {
			$(this).toggleClass('opened');
			$(this).siblings('.dots').toggleClass('hideDots');
			$(this).siblings('.hideText').toggle();
			if ($(this).hasClass('opened')) {
				$(this).text('Less');
			} else {
				$(this).text('More');
			}
		});
});

var userId = <?php if(isset($user)) echo $user->getId(); else echo -1; ?>;
var totalCredits = <?php if(isset($user)) echo $user->getCredits_available();else echo 0; ?>;
var productArray = new Array();
var childproductresourceArray = new Array();
var childproductresourceNameArray = new Array();
var creditsSpent = 0;
var creditsAvailable = new Array();
var item_resource = new Array();

function processChange(responseText, responseStatus, responseXML) {
  if (responseStatus == 200) {// 200 means "OK"
	  var resource = eval('(' + responseText + ')');
	  if(resource.whatRequest == 'viewOrder')
	  {
		  toggleDiv("view_order_div");
		  document.getElementById("view_order_div").innerHTML = resource.responseJSON;
	  }
  } else {// anything else means a problem
	  alert("There was a problem in the returned data:\n");
  }
}

function addToCart(formname, productId, credits, resourceId, credits_assigned, productName, resourceName) {
  var is_checked = false;
  var submiturl="/dap/creditStoreRemoveFromCart.php";

  var addtocartmsg = "<?php echo MSG_CS_COULD_NOT_ADDTOCART ?>";
  if(addtocartmsg=="")
	addtocartmsg = "Sorry, could not add the selected item to Cart";

  var removefromcartmsg = "<?php echo MSG_CS_COULD_NOT_REMOVEFROMCART ?>";
  if(removefromcartmsg=="")
	removefromcartmsg = "Sorry, could not remove the selected item from Cart";

  if(eval('document.getElementById("credits_"+productId+resourceId).checked == true')) {
	submiturl="/dap/creditStoreAddToCart.php";
	jQuery.ajax({
	  url: submiturl,
	  type: "POST",
	  data: {"productId":productId,"credits_assigned":credits_assigned,"resourceId":resourceId,"productName":productName,"resourceName":resourceName},
	  cache: false,
	  success: function (returnval) {
		if(returnval == 0) {
		  alert(addtocartmsg + ": Error: " + returnval);
		}
	  }
	}); //ajax
  } else {
	jQuery.ajax({
	  url: submiturl,
	  type: "POST",
	  data: {"productId":productId,"credits_assigned":credits_assigned,"resourceId":resourceId,"productName":productName,"resourceName":resourceName},
	  cache: false,
	  success: function (returnval) {
		if(returnval == 0) {
		  alert(removefromcartmsg + ": Error: " + returnval);
		}
	  }
	}); //ajax
  } // else
}

function readMore(excerpt) {
	excerpt=decodeURIComponent( excerpt.replace( /\+/g, '%20' ).replace( /\%21/g, '!' ).replace( /\%27/g, "'" ).replace( /\%28/g, '(' ).replace( /\%29/g, ')' ).replace( /\%2A/g, '*' ).replace( /\%7E/g, '~' ) );;
	excerpt=unescape(excerpt);
	showDiv("readmore_div");
	document.getElementById("readmore_div").style.left = mouseX - 40 + "px";
	document.getElementById("readmore_div").style.top = mouseY - 345 + "px";

	var closelink = '<div align="center"><a href="#" onClick="hideDiv(' + "'readmore_div'" + '); return false;">close</a></div>';
	document.getElementById("readmore_div").innerHTML= closelink + excerpt;
}

function viewOrderHTML() {
  var submiturl="/dap/creditStoreViewCartItems.php";
  jQuery.ajax({
	url: submiturl,
	type: "POST",
	data: {},
	cache: false,
	success: function (returnval) {
	  if(returnval == 0) {
		alert("Sorry, could not view cart: Error: " + returnval);
	  }
	  toggleDiv("view_order_div");
	  document.getElementById("view_order_div").innerHTML = returnval;
	}
  }); //ajax
  return;
}

function doSubmit(form) {
  var userid=form.userId.value;
  if((userid==null) || (userid == -1)) {
	var cs_notloggedin = "<?php echo MSG_CS_NOTLOGGEDIN; ?>";
  	if(cs_notloggedin=="")
		cs_notloggedin = "Sorry, please login to redeem credits";

  	alert(cs_notloggedin);
	return;
  }

  var msg = "<?php echo MSG_CS_REDEEMSELECTEDITEMS; ?>";
  if(msg=="")
	msg = "Do you want to proceed with the purchase of the selected items using credits? Press Ok to continue with the purchase.";

  var r=confirm(msg);
  if (r != true)
  	return;

  form.purchaseChild.value = "N";

  var redirect = "<?php echo $_SERVER["REQUEST_URI"]; ?>";
  form.redirect.value = redirect;

  form.action = "/dap/creditStoreProcessSubmit.php";
  form.submit();
}

function purchaseChildProduct(form, childProdId, childCredit) {
  var userid=form.userId.value;

  if((userid==null) || (userid == -1)) {
	var cs_notloggedin = "<?php echo MSG_CS_NOTLOGGEDIN; ?>";
  	if(cs_notloggedin=="")
		cs_notloggedin = "Sorry, please login to redeem credits";
	alert(cs_notloggedin);
	return false;
  }

  var msg = "<?php echo MSG_CS_REDEEMPRODUCT; ?>";
  if(msg=="")
	msg = "Do you want to proceed with the purchase of this product using credits? Press Ok to continue with the purchase.";

  var r=confirm(msg);
  if (r != true)
  	return;

  form.purchaseChild.value = "Y";
  form.creditsSpent.value = childCredit;
  form.childId.value = childProdId;

//  alert("Credits spent now = " + childCredit);
 // alert("Credits available = " + form.creditsAvailable.value);
  //return false;
  var redirect = "<?php echo $_SERVER["REQUEST_URI"]; ?>";
  form.redirect.value = redirect;

  form.action = "/dap/creditStoreProcessSubmit.php";
  form.submit();
}

</script>
</head>

<body>
<div id="readmore_div" style="border: 2px solid rgb(0, 0, 0); position:absolute; margin: 0 auto; background-color:#DDDDDD; overflow:auto; display: none; word-wrap:break-word;z-index: 500 !important;" class="bodytext">
    <a href="#" onClick="hideDiv('readmore_div'); return false;">close</a>
</div>

<?php displayTemplate("HEADER_CONTENT"); ?>

<center>

<?php
  $err_msg=reportAnyError();
?>

<?php
$cs_available_text=CREDITS_AVAILABLE_TEXT;
  if($cs_available_text=="")
  	$cs_available_text="Credits Available";
if($columns==1) {
?>
<?php if ($_SESSION["cssidebar"]=="NO") { ?>
  <div id="singleCol" class="withNoSidebar">
<?php } else { ?>
  <div id="singleCol" class="withSidebar">
<?php }
} elseif ($columns == 2){ ?>
  <div id="multiCol" class="twoCols">
<?php } elseif ($columns >= 3){ ?>
  <div id="multiCol" class="threeCols">
<?php }?>
<div class="creditsAvail">
  <span class="creditAvailableText"><?php echo $cs_available_text ; ?></span><span class="creditAvailableAmount"><?php
  if( Dap_Session::isLoggedIn() )  echo $user->getCredits_available();
  else echo urldecode($_SESSION["CS_NOTLOGGEDINLINK"]);
  ?></span>
  <div class="buyCredits"><?php  if((isset($_SESSION["CS_BTNCODE"])) && ($_SESSION["CS_BTNCODE"]!="")) echo urldecode($_SESSION["CS_BTNCODE"]); ?></div>
</div>
<form name="formcreditstore" id="formcreditstore">
<?php

  $i=0;

  if(!$displayNoMoreProductsFound && !$skipEverythingDisplayNoProd) {
	if($columns == 1) {
	  //logToFile("creditStore.inc: call processOneColPerRowNEW(): columns = " . $columns);
	  $return = processOneColPerRowNEW($user, $catArray, $temp_content, $ind_item_temp_content, $ind_item_temp_button_content, $resellChild, $trimdesc);
	  //logToFile("creditStore.inc: return = " . $return);
	  $atleastoneproductavailable=$atleastoneproductavailable+$return;
	  //logToFile("creditStore.inc: atleastoneproductavailable = " . $atleastoneproductavailable);

	}
	else {
	  //logToFile("creditStore.inc: call processMultipleColsPerRow(): columns = " . $columns);
	  logToFile("creditStore.inc.php: main: cat loop.. how many categories?=" . count($catArray));
	//  for ($catnumber=0;$catnumber < count($catArray);$catnumber++) {
		 //$catId = $catArray[$catnumber][0][0];
		 //logToFile("creditStore.inc: before foreach.. category for requested page.. cat ID =" . $catId);
		 //logToFile("creditStore.inc: processMultipleColsPerRow, columns = " . $columns);
		 //if($catId != "") {
		 //$cat=Dap_Category::loadCategory($catId);
		 if($columns > 1) {
			//logToFile("creditStore.inc: categoryname = ". $categoryname);
			foreach ($categories as $cat) {
			  if( (!isset($cat)) || ($cat==NULL)) break;
			  $return = processMultipleColsPerRow($user, $cat, $temp_content, $resellChild, $trimdesc);
			  logToFile("creditStore.inc: processMultipleColsPerRow, return = " . $return);
			 // logToFile("creditStore.inc: processMultipleColsPerRow,atleastoneproductavailable return = " . $atleastoneproductavailable);
			} //foreach cat
		 } // $columns > 1
		/* else {
		   $return = processOneColPerRow($user, $catArray, $temp_content, $ind_item_temp_content, $ind_item_temp_button_content, $resellChild, $trimdesc);
		   logToFile("creditStore.inc: return = " . $return);
		   $atleastoneproductavailable=$atleastoneproductavailable+$return;
		   logToFile("creditStore.inc: atleastoneproductavailable = " . $atleastoneproductavailable);
		 } // $columns = 1
		 */

	  //}  for ($catnumber=0
	}
  }
?>

<input type="hidden" name="productArray" value="">
<input type="hidden" name="item_resource" value="">
<input type="hidden" name="childproductresourceArray" value="">
<input type="hidden" name="childproductresourceNameArray" value="">

<input type="hidden" name="totalCredits" value="">
<input type="hidden" name="creditsAvailable" value="<?php  if(isset($user)) echo $user->getCredits_available();else echo 0; ?>">

<input type="hidden" name="childId" value="">
<input type="hidden" name="creditsSpent" value="">
<input type="hidden" name="purchaseChild" value="N">
<input type="hidden" name="redirect" value="<?php echo $_SERVER['REQUEST_URI']; ?>">

<input type="hidden" name="userId" value="<?php if(isset($user)) echo $user->getId(); else echo -1; ?>">


<?php
//logToFile("creditStore.inc.php: totalproducts=" .  $prdarr["totalproducts"] . "totalcontentlevelproducts=" . $prdarr["totalcontentlevelproducts"]);

logToFile("creditStore.inc.php: allowContentLevelCredits=" . $allowContentLevelCredits . "creditsNeeded=" . $creditsNeeded);
logToFile("creditStore.inc.php: atleastoneproductavailable=" . $atleastoneproductavailable);

if( ($columns==1) && $skipEverythingDisplayNoProd) {
?>
<div class="orderTitle">
  <?php echo $cs_no_prod_available; ?>
</div>
<?php
}
else if (($columns==1) && $displayNoMoreProductsFound) {
?>
<div class="orderTitle">
  <?php echo $cs_no_more_prod_available; ?>
</div>
<?php
}
?>

<?php displayTemplate("FOOTER_CONTENT"); ?>
<?php
// $numproductsperpage = 5;
// $total_number_of_products = 20;
// $number_of_pages = $total_number_of_products / $numproductsperpage;
if ($columns==1) {
$number_of_pages = $num_pages;
$item_class = '';?>
<ul class="productsPagination">
<?php
for ($i=0; $i < $number_of_pages; $i++) {
if($i==0) {	$item_class = 'selected';} else {$item_class = '';}
?>
<li class="item <?php echo $item_class ?>">
<a href="?pagenumber=<?php echo $i+1 ?>&numproductsperpage=<?php echo $numproductsperpage ?>" class="page_number" data-number="<?php echo $i ?>"><?php echo $i+1; ?></a>
</li>
<?php } ?>
</ul>
<?php } ?>
</form>
</div>

</center>
</body>
</html>

<?php

function processMultipleColsPerRow ($user, $cat, $temp_content, $resellChild, $trimdesc) {
  logToFile("ENTER processMultipleColsPerRow()");
  $catId=$cat->getId();
  $i=0;
  $displayDescription="N";
  $startedRow="N";

  $prodscat=Dap_ProductCategory::loadProductsUnderCategory($catId);  //get sss enabled child products that belong to this category
 // $prodsnocat=Dap_ProductCategory::loadProductsNotCategorized();  //get sss enabled child products that do not belong to ANY category

  if($prodscat==NULL) {
?>
</ol>
<?php
	logToFile("RETURN processMultipleColsPerRow()");
	return;
  }
  if(isset($prodscat) && ($prodscat!=NULL)) {
?>
<div class="orderTitle"><?php $code=$cat->getCode(); echo $code;  ?>
<!-- <div class="creditsAvail"> <?php  //$cs_available_text=CREDITS_AVAILABLE_TEXT;if($cs_available_text=="")$cs_available_text="Credits Available"; echo $cs_available_text . ":  "; if(Dap_Session::isLoggedIn()) echo $user->getCredits_available(); else echo urldecode($_SESSION["CS_NOTLOGGEDINLINK"]); ?> &nbsp; &nbsp;
</div> -->
</div>
<div class="listContent">
<ul class="listUL">
<?php
$count=0;
$columns=$_SESSION['CS_COLUMNS'];
 $atleastoneproductavailable=0;
 $allowAccessToFutureContentAutomatically = "N";

	foreach ($prodscat as $prodcat) {
	  if(!isset($prodcat) ||  ($prodcat == NULL)) break;
	  $childprodId = $prodcat->getProduct_id();
	  $hasAccess=false;
	  if(isset($user)) {
	  	$hasAccess = $user->hasAccessTo($childprodId); //TO DO:  CHECK IF FULL ACCESS
	  }
	  $product = Dap_Product::loadProduct($childprodId);
	  $allowAccessToFutureContentAutomatically = $product->getAllowAccessToFutureContent();
	  $resellChild = $product->getResellProduct();

	  logToFile("creditStore.inc:CATID=" .  $cat->getCode() . ", CHILD PRODUCT NAME=" . $product->getName() . ", allowAccessToFutureContentAutomatically=" . $allowAccessToFutureContentAutomatically . ", resell=" . $resellChild);

	  if(($resellChild == "N") && ($hasAccess) && ($allowAccessToFutureContentAutomatically == "Y")) {
		//$atleastoneproductavailable=0;
		logToFile("resell=N and user already has access, atleastoneproductavailable = ".$atleastoneproductavailable);
		continue;
	  }


	  $resFound = "N";
	  $productCredits = $product->getCredits();
	  $creditsNeeded=0;

	  if ($hasAccess) { // allow purchase of individual items
		if(isset($user)) {
			$UserResources = Dap_FileResource::loadFileResourcesSSS($user->getId(), $product->getId());
			foreach($UserResources as $UserResource) {
			  $resFound = "Y";
  //		  	logToFile("creditStore.inc: UserResources found=" . $resFound . ", CHILD PRODUCT NAME=" . $product->getName());

			  $creditsNeeded =  $creditsNeeded +  $UserResource['credits_assigned'];
			}
			if(($allowContentLevelCredits != "Y") && ($product->getCredits() > 0)) {
				$creditsNeeded = $product->getCredits();
				$UserResources=NULL;
			}
			logToFile("creditStore.inc: allowContentLevel=" . $allowContentLevelCredits . ", resFound=" . $resFound . ", CHILD PRODUCT NAME=" . $product->getName() . ", CREDITS NEEDED=" . $creditsNeeded );
		}
	  }

	  if($productCredits>0) { // product level credit always gets higher priority (over content level) if set
	  	$creditsNeeded = $product->getCredits();
		logToFile("creditStore.inc: product level credits=" . $creditsNeeded . ", CHILD PRODUCT NAME=" . $product->getName());
	  }

	  if( ( ($resFound == "N") || (($resFound == "Y") && ($allowAccessToFutureContentAutomatically == "Y"))) && ($hasAccess) && ($resellChild == "N") ) { // no new resource added since user got access last and resell not allowed
		logToFile("creditStore.inc.php: resellChild=N, and user already has access and no new resources added after the user's purchase or new resources added but user gets auto access to future content (per CS settings), so user will not see this product = " . $product->getId() . " in the store");
		//$atleastoneproductavailable=0;
		continue;
	  }

	  logToFile("creditStore.inc:atleastoneproductavailable=" .  $atleastoneproductavailable);

	  $atleastoneproductavailable=1;
	  $url=$product->getSales_page_url();
	  $allowContentLevelCredits = $product->getAllowContentLevelCredits();

	  logToFile("creditStore.inc.php: processMultipleColsPerRow: allowContentLevelCredits=" . $allowContentLevelCredits . "creditsNeeded=" . $creditsNeeded);

	  $image_src = $product->getProduct_image_url();
	  if ($image_src == "") {
		$image_src = "/dap/images/noimagesfound.jpg";
		if(file_exists($lldocroot . "/dap/images/customnoimagesfound.jpg"))
			$image_src = "/dap/images/noimagesfound.jpg";
	  } // end if image_src


	  $productNameShort = trimString($product->getName(),30,30,5);
	  $productDescShort = $product->getShortDescription();
	  $productDescLong = $product->getLongDescription();
	  //$productDescShort = trimStringLength($product->getShortDescription(),$trimdesc,$trimdesc);
	  //logToFile("product shortENED desc=" . $productDescShort);

	  $url=$product->getProductImageUrlLinkedTo();
	  $current_msg=$temp_content;
	  $current_msg = str_replace( '[CHILDIDCREDITS]', "credits_".$product->getId(), $current_msg);
	  $current_msg = str_replace( '[FORMNAME]', "document.formcreditstore", $current_msg);
	  $current_msg = str_replace( '[CHILDID]', $product->getId(), $current_msg);
	  //$current_msg = str_replace( '[CREDITS]', $product->getCredits(), $current_msg);

	  $current_msg = str_replace( '[ITEMNAME]', $product->getName(), $current_msg);
	  $current_msg = str_replace( '[ITEMNAMESHORT]', $productDescLong, $current_msg);
	  $current_msg = str_replace( '[URL]', $url, $current_msg);
	  $current_msg = str_replace( '[IMAGEURL]', $image_src, $current_msg);
	  $current_msg = str_replace( '[ITEMDESC]', $product->getDescription(), $current_msg);
	  $current_msg = str_replace( '[ITEMDESCSHORT]', $productDescLong, $current_msg);
	  $current_msg = str_replace( '[CREDITS]', $creditsNeeded, $current_msg);
	  $current_msg = str_replace( '[PRODINFO]', $product->getDescription(), $current_msg);
	  $current_msg = str_replace( '[REDEEMSRC]', $_SESSION["redeemimagemultiple"], $current_msg);
	  $buylink = $product->getBuyLink();
	  if(isset($buylink) && (trim($buylink) != "")) {
		$current_msg = str_replace( '[BUYPRODUCTSRC]',"<div class='txtCredits'>Product Price: <b>[CURRENCY_SYMBOL][PRODPRICE]</b></div>".urldecode($buylink), $current_msg);
	  	//$current_msg = str_replace( '[BUYPRODUCTSRC]',urldecode($buylink), $current_msg);

	  }
	  else {
		  $current_msg = str_replace( '[BUYPRODUCTSRC]',"", $current_msg);
	  }

	  $currency_symbol=$_SESSION["currencysymbol"];
	  if($currency_symbol=="") {
		  $currency_symbol=trim(Dap_Config::get('CURRENCY_SYMBOL'));
	  }
	  $current_msg = str_replace( '[CURRENCY_SYMBOL]', $currency_symbol, $current_msg);
	  $current_msg = str_replace( '[PRODPRICE]', $product->getPrice(), $current_msg);

	   //logToFile("b4 echo");
	  echo $current_msg;
	   //logToFile("creditStore.inc: After echo");
	  // $current_msg = file_get_contents($template_path);
	  $count++;
	  logToFile("creditStore.inc: COUNT=".$count . ", col=" . $columns);
      if($count == $columns) {
	     $count=0;
		 logToFile("creditStore.inc: COUNT MATCHES COLUMN, ADD SPACING, count=" .   $count . ", prodId=".$prodcat->getProduct_id() );
		?>

		<li class="clearRow">&nbsp;</li>
		<?php

	  }
	} //foreach $prodscat as $prodcat
?>
</ul>
</div>
<?php
  } //if isset($prodscat) && ($prodscat!=NULL)
?>

<?php
logToFile("EXIT processMultipleColsPerRow()");
return $atleastoneproductavailable;
} // end function

function getCSTemplateContent($template_path, $filename) {
	if(file_exists($template_path)) {
		$temp_content = file_get_contents($template_path . "custom" . $filename);
	}
	else {
		$temp_content = file_get_contents($template_path . $filename);
	}
	return $temp_content;
}

function validateRequest($blogpath) {
	if($blogpath=="") {
		$_SESSION["crediterrmsg"]="Sorry, this page cannot be accessed directly";
		@header("Location:".Dap_Config::get("LOGGED_IN_URL"));
		exit;
	}

	return TRUE;
}

function getFormCSS($template_path, $cssfilename) {
	if(file_exists($template_path . "custom" . $cssfilename))
		$formcss = $customcss;
	else
		$formcss = $template_path . $cssfilename;

	return $formcss;
}

function reportAnyError() {
	$err_msg = $_REQUEST['crediterrmsg'];

	if ($err_msg == "") {
	  if( (isset($_SESSION['crediterrmsg'])) && ($_SESSION['crediterrmsg'] != "") )
		$err_msg = $_SESSION['crediterrmsg'];
		unset($_SESSION['crediterrmsg']);
	}

	if ($err_msg != "")
		echo '<strong><font size="+1" color="RED">' . $err_msg . '</font></strong>';

	$_SESSION['crediterrmsg']="";
}

function fillResourcesArray(&$userresources,$product, $userId, $productId, $allowContentLevelCredits) {
  $UserResources = Dap_FileResource::loadFileResourcesSSS($userId,$productId);
  $userresnum=0;
  foreach($UserResources as $UserResource) {
	$resFound = "Y";
	//logToFile("creditStore.inc: fillResourcesArray: UserResources found=" . $resFound . ", CHILD PRODUCT NAME=" . $product->getName());
	$resId = $UserResource["id"];
	if($resId=="") continue;
	if($UserResource['credits_assigned']>0)
		$creditsNeeded =  $creditsNeeded +  $UserResource['credits_assigned'];

	if($allowContentLevelCredits == "Y") {
		if(!isset($UserResource['credits_assigned']))
		  $userresources[$userresnum]['res_credits_assigned']=0;
		else
		  $userresources[$userresnum]['res_credits_assigned']=$UserResource['credits_assigned'];

		if ($UserResource["name"] == "") {
			$name = $UserResource["url"];
		}
		else {
			$name = $UserResource["name"];
		}
		$userresources[$userresnum]['id']=$UserResource['id'];
		$userresources[$userresnum]['name']=$name;
		$userresources[$userresnum]['url']=$UserResource["url"];
		if(strstr($UserResource['url'],'http') == 0) {
			$userresources[$userresnum]['url']=SITE_URL_DAP.$UserResource['url'];
			logToFile("creditStore.inc: RESURL WITH HTTP=".$userresources[$userresnum]['url']);
		}
		$userresources[$userresnum]['excerpt']=$UserResource['excerpt'];
		logToFile("creditStore.inc: resname=".$name.", CHILD PRODUCT NAME=" . $product->getName());
		$userresnum++;
	}
  }

  if(($allowContentLevelCredits != "Y") && ($product->getCredits() > 0))
	$creditsNeeded = $product->getCredits();

  if($creditsNeeded == 0)
	$creditsNeeded = $product->getCredits();

  logToFile("creditStore.inc: fillResourcesArray: rescount=" . count($userresources) . ", CHILD PRODUCT NAME=" . $product->getName());
  return $creditsNeeded;
}

function buildProductsPerPageArray (&$productArr, &$catIdIndexArr, $numproductsperpage, &$totalnumberofitemsperproduct, $categories, $userId,$pagenumber) {
  $pn=0;
  $catindex=0;
  $i=0;

  $totalnumberofproducts=0;

  //prodnumber just a sequential number, it's not the product id.
  //format = arr[pagenumber][prodnumber][0][0]=indivitualitemname
  //arr[pagenumber][prodnumber][0][0]=indivituallink
  //... arr[pagenumber][prodnumber][1]=prodid...
  //arr[pagenumber][prodnumber][2]=catid
  $catnum=0;
  $productsperpagecounter=0;
  
  if($userId!="")
  	$user=Dap_User::loadUserById($userId);
  
  $prodsthatmatchrule=array();
  foreach ($categories as $cat) {
	  $catId=$cat->getId();
	  logToFile("creditStore.inc.php: buildProductsPerPageArray(): ENTER CAT LOOP: CatId=".$catId);

	  $catId=$cat->getId();
	  $prodsPerCatArr=Dap_ProductCategory::loadProductsUnderCategoryArr($catId);

	//  logToFile("creditStore.inc.php: buildProductsPerPageArray(): called loadProductsUnderCategoryArr");

	  $found="N";

	  if(!isset($prodsPerCatArr) ||  ($prodsPerCatArr==NULL)) {
		  logToFile("creditStore.inc.php: buildProductsPerPageArray(): no prods found");
		  continue;
	  }
	  if($userId=="")
		  $userId=-1;

	  $totalprodnumber=0;
	  $matchprodnumber=0;

	 // logToFile("creditStore.inc.php:  buildProductsPerPageArray(): iterrate , count=".  count($prodsPerCatArr));

	  for ($prodnumber=0;$prodnumber < count($prodsPerCatArr);$prodnumber++) {
		$catId = $prodsPerCatArr[$prodnumber][0]; //cat id
		$childprodId = $prodsPerCatArr[$prodnumber][1]; //product id

		logToFile("creditStore.inc.php:  buildProductsPerPageArray(): cat id =".$catId .", childprodId NAME=".$childprodId);

		$hasAccess=0;

		if(isset($user)) {
			$userId=$user->getId();
			$hasAccess=Dap_UserCredits::hasAccessTo($userId, $childprodId);
		}

		$product = Dap_Product::loadProduct($childprodId);
		$resellChild = $product->getResellProduct();
		$allowContentLevelCredits = $product->getAllowContentLevelCredits();
		$allowAccessToFutureContentAutomatically = $product->getAllowAccessToFutureContent();

		logToFile("creditStore.inc.php: buildProductsPerPageArray(): PRODUCT NAME=".$product->getName() . ", allowContentLevelCredits=".$allowContentLevelCredits." hasAccess=".$hasAccess." allowAccessToFutureContentAutomatically=" . $allowAccessToFutureContentAutomatically . ", resell=" . $resellChild);

		if(($resellChild == "N") && ($hasAccess) && ($allowAccessToFutureContentAutomatically == "Y")) {
//			logToFile("creditStore.inc.php: resellChild=N, and user already has access");
			continue;
		}

		if(($allowContentLevelCredits == "Y") && ($hasAccess) && ($allowAccessToFutureContentAutomatically == "Y"))  {
	//		logToFile("creditStore.inc.php: allowContentLevelCredits=Y, cannot allow user to repurchase if user already has access");
			continue;
		}

		$creditsNeeded = $product->getCredits();
		$resFound = "N";
		$entered=false;

		$userresources=array();

		if ( ($allowContentLevelCredits == "Y") || (($hasAccess) && ($allowAccessToFutureContentAutomatically == "N")) || (!isset($user)) || ($creditsNeeded==0)) { // allow purchase of individual items
		  $creditsNeeded = 0;
		  $userresnum=0;

		  $productId= $product->getId();
		  if(isset($user)) $userId=$user->getId();
		  $creditsNeeded=fillResourcesArray($userresources, $product, $userId, $productId, $allowContentLevelCredits);

		  $entered=true;

		  //logToFile("creditStore.inc: buildProductsPerPageArray(): allowContentLevel=" . $allowContentLevelCredits . ", resFound=" . $resFound . ", CHILD PRODUCT NAME=" . $product->getName());

		  ///logToFile("creditStore.inc: buildProductsPerPageArray():  count userresources=". count( $userresources ));

		  //$userresources['credits_assigned']=$creditsNeeded;

		  if(count( $userresources ) )
			  $prodsthatmatchrule[$catnum][$matchprodnumber][3] = $userresources; //resource list

		}
		
		$totalAssignedResources=Dap_FileResource::findContentWithCredits($product->getId());
		$totalUserProductResources=Dap_UserCredits::hasAccessToResourcesCount($userId, $productId);
		
		logToFile("creditStore.inc.php: buildProductsPerPageArray(): totalAssignedResources=".$totalAssignedResources);
		logToFile("creditStore.inc.php: buildProductsPerPageArray(): totalUserProductResources=".$totalUserProductResources);
		$newcontentadded=false;
		if($allowAccessToFutureContentAutomatically == "N") {
			if((int)$totalAssignedResources>(int)$totalUserProductResources) {
				// new resources added after the users purchase of product.. show the product even if resell child == N
				logToFile("creditStore.inc.php: buildProductsPerPageArray():  new resources added after the users purchase of product.. show the product even if resell child == N");
				$newcontentadded=true;
			}
		}
		
		if(($resFound == "N") && ($hasAccess) && ($allowAccessToFutureContentAutomatically == "N") && ($resellChild == "N") && ($newcontentadded==false)) {
		  logToFile("creditStore.inc.php: buildProductsPerPageArray(): resellChild=N, and user already has access");
		  continue;
		}

		//reset resFound so the individual item div is not displayed if allow content level credits = N
		if ($allowContentLevelCredits == "N") $resFound = "N";

  //	  if($creditsNeeded <= 0) && ($resFound == "N")) {
		if($creditsNeeded <= 0) {
		  if( ($entered=true) && (!$hasAccess) && (isset($user))){
			  logToFile("creditStore.inc: credits needed to get product access <= 0 (user does not have access to product=". $product->getName() . " but the user already has access to all content that's part of this product");
		  }
		  else {
			  logToFile("creditStore.inc: credits needed to get product access <= 0 (both product credits + adding up content level credit results in 0), dont show product, child=" . $product->getName());
		  }
		  continue;
		}


		//$prodsthatmatchrule=array();
		$prodsthatmatchrule[$catnum][$matchprodnumber][0] = $prodsPerCatArr[$prodnumber][0]; //catid
		$prodsthatmatchrule[$catnum][$matchprodnumber][1] = $prodsPerCatArr[$prodnumber][1]; //prodid
		$prodsthatmatchrule[$catnum][$matchprodnumber][2] = $creditsNeeded; //creditsNeeded to get full product
		logToFile("creditStore.inc.php: USERRESCOUNT=" . count($userresources) . ", prod=" . $prodsPerCatArr[$prodnumber][1]);

		if(count($userresources)==0) {
			$prodsthatmatchrule[$catnum][$matchprodnumber][3] =NULL;
			//logToFile("creditStore.inc.php: NO RES: prodsthatmatchrule[$catnum][$matchprodnumber][3]: prodId=". $prodsPerCatArr[$prodnumber][1]);
		}
		//unset($userresources);
//		$creditsNeeded
//		$prodsthatmatchrule[$catnum][$matchprodnumber][2] = $prodsPerCatArr[$prodnumber][2]; //catid

		//logToFile("creditStore.inc.php: prodsthatmatchrule[$catnum][$matchprodnumber][0]: catId=". $prodsPerCatArr[$prodnumber][0]);
		//logToFile("creditStore.inc.php: prodsthatmatchrule[$catnum][$matchprodnumber][1]: prodId=". $prodsPerCatArr[$prodnumber][1]);

		if($pagenumber==$pn) {
			$totalnumberofitemsperproduct[$productsperpagecounter]=count($userresources);
		}

		if($productsperpagecounter >= ($numproductsperpage -1)) {
			$prarr=arrayCopy($prodsthatmatchrule);
			//unset($prodsthatmatchrule);
			$productArr[$pn] = $prarr;
			$pn++; //  move to next page
			logToFile("creditStore.inc.php: move to next page...". $pn);
			$matchprodnumber=0;
			$catnum=0;
			$productsperpagecounter=0;
			$prodsthatmatchrule=array();
		}
		else {
			$matchprodnumber++;
			$productsperpagecounter++;
		}

		$totalnumberofproducts++;

		//logToFile("creditStore.inc.php: buildProductsPerPageArray(): Products under Cat=" . $catId . " totalnumberofproducts=".$totalnumberofproducts);
	  } //for prod loop

	  if($productsperpagecounter==0)
		$catnum=0; // new page, reset cat counter to 0
	  else
		$catnum++;
  } //foreach category

  if($productsperpagecounter < ($numproductsperpage)) {
	if(count($prodsthatmatchrule)) {
  		$prarr=arrayCopy($prodsthatmatchrule);
		//unset($prodsthatmatchrule);
		$productArr[$pn] = $prarr;
	}
  }

 /* Use this during debugging if products per page display is not correct
 $pageArray=$productArr;
  for ($pagenumber=0;$pagenumber < count($pageArray);$pagenumber++) {
	logToFile("creditStore.inc.php: page loop.. how many pages?=" . count($pageArray));
	$catArray = $pageArray[$pagenumber];
	for ($catnumber=0;$catnumber < count($catArray);$catnumber++) {
	   logToFile("creditStore.inc.php: cat loop.. how many categories?=" . count($catArray));
	   $prodArray = $catArray[$catnumber];
	   for ($prodnumber=0;$prodnumber < count($prodArray);$prodnumber++) {
		  logToFile("creditStore.inc.php: prod loop.. how many products?=" . count($prodArray));
		  $catId=$prodArray[$prodnumber][0]; // catId
		  $prodId=$prodArray[$prodnumber][1]; // prodId
		  logToFile("creditStore.inc.php: prod loop.. catId=" . $catId);
		  logToFile("creditStore.inc.php: prod loop.. prodId=" . $prodId);
	   }
	}
  }*/

  return $totalnumberofproducts;

} //function


function getProductsForRequestedPage(&$pageArray,$pagenumber) {
  logToFile("creditStore.inc.php: getProductsForRequestedPage: requested page number=".$pagenumber);

  $catArray = $pageArray[$pagenumber];
  for ($catnumber=0;$catnumber < count($catArray);$catnumber++) {
	 logToFile("creditStore.inc.php: getProductsForRequestedPage: cat loop.. how many categories?=" . count($catArray));
	 $prodArray = $catArray[$catnumber];
	 for ($prodnumber=0;$prodnumber < count($prodArray);$prodnumber++) {
		logToFile("creditStore.inc.php: getProductsForRequestedPage: prod loop.. how many products?=" . count($prodArray));

		$catId=$prodArray[$prodnumber][0]; // catId
		$prodId=$prodArray[$prodnumber][1]; // prodId
		$creditsNeeded=$prodArray[$prodnumber][2]; // creditsNeeded
		$userResources=$prodArray[$prodnumber][3]; // res

		logToFile("creditStore.inc.php: getProductsForRequestedPage: prod loop.. how many resources?=" . count($userResources));

		for ($resnumber=0;$resnumber < count($userResources);$resnumber++) {
		   logToFile("creditStore.inc.php: getProductsForRequestedPage: RES loop.. name=" . $userResources[$resnumber]["name"]);
		}

		logToFile("creditStore.inc.php: getProductsForRequestedPage : prod loop.. catId=" . $catId);
		logToFile("creditStore.inc.php: getProductsForRequestedPage: prod loop.. prodId=" . $prodId);
	 }
  }

  return $catArray;

} //function

function arrayCopy( array $array ) {
    $result = array();
    foreach( $array as $key => $val ) {
        if( is_array( $val ) ) {
            $result[$key] = arrayCopy( $val );
        } elseif ( is_object( $val ) ) {
            $result[$key] = clone $val;
        } else {
            $result[$key] = $val;
        }
    }
    return $result;
}

function getProductsForAPage($pagenumber) {
	// apply all rules, figure out the products per page, then this array is re-bulit upon each page load, redeem will reload
	// no hasaccess rules applied in prodcess1c... method.. all rules applied in the begeining, array created, then prcoess... will refer to the array to update template

}


function processOneColPerRowNEW($user, &$catArray, $temp_content, $ind_item_temp_content, $ind_item_temp_button_content, $resellChild, $trimdesc) {
  $displaycatname=true;
  logToFile("creditStore.inc: ENTER processOneColPerRow()");
  $ind_items_msg=$ind_item_temp_content;
 // $catId=$cat->getId();

  for ($catnumber=0;$catnumber < count($catArray);$catnumber++) {
	logToFile("creditStore.inc:  processOneColPerRow(): catnumber=".$catnumber);
	$ind_items_msg=$ind_item_temp_content;
	$prodArray = $catArray[$catnumber];
	$displaycatname=true;
	//logToFile("creditStore.inc:  processOneColPerRow(): count prodArray=".count($prodArray) );
	if(isset($prodArray) && ($prodArray!=NULL)) {
	  $atleastoneproductavailable=0;

	  for ($prodnumber=0;$prodnumber < count($prodArray);$prodnumber++) {
		logToFile("creditStore.inc:  processOneColPerRow(): prodnumber=".$prodnumber);
		$catId=$prodArray[$prodnumber][0]; // catId
		$cat=Dap_Category::loadCategory($catId);
		$childprodId=$prodArray[$prodnumber][1]; // prodId
		$creditsNeeded=$prodArray[$prodnumber][2]; // creditsNeeded

		$userResources=array();
		if(isset($prodArray[$prodnumber][3]))
			$userResources=$prodArray[$prodnumber][3]; // userResources

		$product = Dap_Product::loadProduct($childprodId);
		$productNameShort = trimString($product->getName(),30,30,5);
		$url=$product->getSales_page_url();
		$image_src = $product->getProduct_image_url();
		//logToFile("processOneColPerRowNEW: childprodId=".$childprodId);
		if ($image_src == "") {
		  $image_src = "/dap/images/noimagesfound.jpg";
		  if(file_exists($lldocroot . "/dap/images/customnoimagesfound.jpg"))
			  $image_src = "/dap/images/noimagesfound.jpg";
		} // end if image_src

		$productDesc = $product->getDescription();
		$productDescLong = $product->getLongDescription();
		$url=$product->getProductImageUrlLinkedTo();
		$buylink = $product->getBuyLink();
		$atleastoneproductavailable=1;
  ?>
  <?php if($displaycatname) { ?>
  <div class="orderTitle"><?php $code=$cat->getCode(); echo $code;  ?></div>
  <?php
  $displaycatname=false;
  } ?>
  <ol>
  <li>
  <?php
		$atleastoneproductavailable=1;
		$current_msg=$temp_content;

		$current_msg = str_replace( '[CHILDIDCREDITS]', "credits_".$product->getId(), $current_msg);
		$current_msg = str_replace( '[FORMNAME]', "document.formcreditstore", $current_msg);
		$current_msg = str_replace( '[CHILDID]', $product->getId(), $current_msg);

		$current_msg = str_replace( '[CREDITS]', $creditsNeeded, $current_msg);



		$current_msg = str_replace( '[ITEMNAME]', $product->getName(), $current_msg);
		$current_msg = str_replace( '[ITEMNAMESHORT]', $productNameShort, $current_msg);
		$current_msg = str_replace( '[URL]', $url, $current_msg);
		$current_msg = str_replace( '[IMAGEURL]', $image_src, $current_msg);

		$current_msg = str_replace( '[ITEMDESC]', $product->getDescription() , $current_msg);
		$current_msg = str_replace( '[ITEMDESCSHORT]', $productDescLong , $current_msg);

		$current_msg = str_replace( '[PRODINFO]', $product->getDescription(), $current_msg);
		$current_msg = str_replace( '[REDEEMSRC]', $_SESSION["redeemimage"], $current_msg);

		if(isset($buylink) && (trim($buylink) != "")) {
		  $current_msg = str_replace( '[BUYPRODUCTSRC]',"<div class='txtCredits'>Product Price: <b>[CURRENCY_SYMBOL][PRODPRICE]</b></div>".urldecode($buylink), $current_msg);
		}
		else {
			$current_msg = str_replace( '[BUYPRODUCTSRC]',"", $current_msg);
		}
		$currency_symbol=$_SESSION["currencysymbol"];
		if($currency_symbol=="") {
			$currency_symbol=trim(Dap_Config::get('CURRENCY_SYMBOL'));
		}
		$current_msg = str_replace( '[CURRENCY_SYMBOL]', $currency_symbol, $current_msg);
		$current_msg = str_replace( '[PRODPRICE]', $product->getPrice(), $current_msg);
		echo $current_msg;
		$current_msg="";
		logToFile("processOneColPerRowNEW: how many resources?=". count($userResources) . "for $childprodId");

		if( count($userResources) ) {
			logToFile("processOneColPerRowNEW: configure RES");
		?>
  		<div class="individualItems">
		  <ol>
		  <li class="headerIndividual">
			  <div class="individualLeft">Redeem individual items</div>
			  <div class="individualRight">Credits Required</div>
		  </li>
		<?php
		$readmoreimage = $_SESSION["readmoreimage"];

		for ($resnumber=0;$resnumber < count($userResources);$resnumber++) {
		  if(!isset($userResources[$resnumber]))
			  break;

		  $resCredits = $userResources[$resnumber]['res_credits_assigned'];
		  $resId = $userResources[$resnumber]["id"];
		  if ($userResources[$resnumber]["name"] == "") {
			  $name = $userResources[$resnumber]["url"];
		  }
		  else {
			  $name = $userResources[$resnumber]["name"];
		  }

		  //logToFile("creditStore.inc.php: content name = " . $name . ", credits=" . $userResources[$resnumber]['credits_assigned']);
		  $excerpt=$userResources[$resnumber]['excerpt'];

		  $prodIdResId=$product->getId().  $resId;

		  $ind_items_msg=$ind_item_temp_content;
		  $ind_items_msg = str_replace( '[CHILDNAME]', $product->getName(), $ind_items_msg);
		  $ind_items_msg = str_replace( '[PRODIDRESID]', "credits_".$prodIdResId, $ind_items_msg);
		  $ind_items_msg = str_replace( '[FORMNAME]', "document.formcreditstore", $ind_items_msg);
		  $ind_items_msg = str_replace( '[CHILDID]', $product->getId(), $ind_items_msg);
		  $ind_items_msg = str_replace( '[CREDITS]', $creditsNeeded, $ind_items_msg);

		  if($excerpt!="") {
			$readmore='<a href="#" name="readmore" class="readmore" id="readmore" onClick="readMore(' . "'" . urlencode($excerpt) . "'" . '); return false;"><p>More</p></a>';
		  }

		  //logToFile("creditStore.inc.php: excerpt = " . $excerpt);
		  $ind_items_msg = str_replace( '[READMORE]', $readmore, $ind_items_msg);
		  $ind_items_msg = str_replace( '[EXCERPT]', $excerpt, $ind_items_msg);
		  $ind_items_msg = str_replace( '[RESID]', $resId, $ind_items_msg);
		  $ind_items_msg = str_replace( '[RESCREDITS]', $userResources[$resnumber]['res_credits_assigned'], $ind_items_msg);
		  $ind_items_msg = str_replace( '[RESNAME]',  urlencode($name), $ind_items_msg);
		  $ind_items_msg = str_replace( '[RESNAMEFULL]',  $name, $ind_items_msg);

		  logToFile("creditStore.inc.php: RESURL=". $userResources[$resnumber]["url"]);


		  $ind_items_msg = str_replace( '[RESURL]', $userResources[$resnumber]["url"], $ind_items_msg);

		  echo $ind_items_msg;
		} //foreach individual items


		$ind_items_button_msg=$ind_item_temp_button_content;
		$ind_items_button_msg = str_replace( '[FORMNAME]', "document.formcreditstore", $ind_items_button_msg);
		$ind_items_button_msg = str_replace( '[BUYSELECTEDITEMSSRC]', $_SESSION["buyselecteditemsimage"], $ind_items_button_msg);

		echo $ind_items_button_msg; ?>
        </ol>
        </div>
        <?php 	} //if ?>
        </li>
        </ol>
        <div class="orderClear">&nbsp;</div>
          <?php

	  } //foreach $prodscat as $prodcat
	} //if isset($prodscat) && ($prodscat!=NULL)
  }//for category arr
?>

<?php
logToFile("EXIT processOneColPerRow(): " . $atleastoneproductavailable);
return $atleastoneproductavailable;
} // end function

?>