<?php
	if( Dap_Session::isLoggedIn() ) { 
		//get userid
		$session = Dap_Session::getSession();
		$user = $session->getUser();
		if(isset($user)) {
			$userProducts = Dap_UsersProducts::loadProducts($user->getId());
		}
	}
?>
<?php getCss(); ?>
<div align="left">
<form name="UserLinksForm">
<a name="userlinks"></a>
<?php echo USER_LINKS_YOUCURRENTLYHAVEACCESSTO_TEXT . count($userProducts). USER_LINKS_PRODUCTS_TEXT; ?><br/><br/>
<?php
	//loop over each product from the list
	foreach ($userProducts as $userProduct) { 
		$product = Dap_Product::loadProduct($userProduct->getProduct_id());
		?> 
       	<div class="dap_product_links_table"> 
        <div class="dap_product_heading"><?php echo $product->getName(); ?></div>
        <div><strong><?php echo USER_LINKS_ACCESS_START_DATE_TEXT; ?></strong>: <?php echo $userProduct->getAccess_start_date(); ?></div>
        <div><strong><?php echo USER_LINKS_ACCESS_END_DATE_TEXT; ?></strong>: <?php echo $userProduct->getAccess_end_date(); ?></div>
        <div><strong><?php echo USER_LINKS_DESCRIPTION_TEXT; ?></strong>: <?php echo $product->getDescription(); ?></div>
        <?php 
		if ($product->getSelf_service_allowed() == "N") { ?>
        	<div><strong><?php echo USER_LINKS_LINKS_TEXT; ?></strong>: <?php echo $userProduct->getActiveResources(); ?></div>
        <?php } 
		else if (($product->getSelf_service_allowed() == "Y")) {?>
        	<div><strong><?php echo USER_LINKS_LINKS_TEXT; ?></strong>: <?php logToFile("show links"); echo $userProduct->getActiveResources("Y"); ?></div>
        <?php } ?>
      	</div>
      	<br/><br/>
  	<?php
	} //end foreach
	?> 
</form>
</div>