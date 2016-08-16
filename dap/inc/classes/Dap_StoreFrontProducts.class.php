<?php

class Dap_StoreFrontProducts{
  var $product_id;
  var $showCouponCode;
  var $cartHeader;
  var $cartFooter;
  var $buyImage;
  var $addtocartImage;
  var $resell_product;
  var $start_date;
  var $end_date;
  var $max_usage;
  var $actual_usage; 
  var $storefront_price;
  var $storefront_enabled;
  
  function getProduct_id() {
	  return $this->product_id;
  }
  function setProduct_id($o) {
	  $this->product_id = $o;
  }
  
  function getShowCouponCode() {
	  return $this->showCouponCode;
  }
  function setShowCouponCode($o) {
	  $this->showCouponCode = $o;
  }
  
  function getCartHeader() {
	  return $this->cartHeader;
  }
  function setCartHeader($o) {
	  $this->cartHeader = $o;
  }
  
  function getCartFooter() {
	  return $this->cartFooter;
  }
  function setCartFooter($o) {
	  $this->cartFooter = $o;
  }
  
  function getBuyImage() {
	  return $this->buyImage;
  }
  function setBuyImage($o) {
	  $this->buyImage = $o;
  }
  
  function getAddtocartImage() {
	  return $this->addtocartImage;
  }
  function setAddtocartImage($o) {
	  $this->addtocartImage = $o;
  }

  function getResell_product() {
	  return $this->resell_product;
  }
  function setResell_product($o) {
	  $this->resell_product = $o;
  }

  function getStart_date() {
	  return $this->start_date;
  }
  function setStart_date($o) {
	  $this->start_date = $o;
  }

  function getEnd_date() {
	  return $this->end_date;
  }
  function setEnd_date($o) {
	  $this->end_date = $o;
  }

  function getMax_usage() {
	  return $this->max_usage;
  }
  
  function setMax_usage($o) {
	  $this->max_usage = $o;
  }
  
  function getActual_usage() {
	  return $this->actual_usage;
  }
  function setActual_usage($o) {
	  $this->actual_usage = $o;
  }

  function getStorefront_price() {
	  return $this->storefront_price;
  }
  function setStorefront_price($o) {
	  $this->storefront_price = $o;
  }
  
  function getStorefront_enabled() {
	  return $this->storefront_enabled;
  }
  function setStorefront_enabled($o) {
	  $this->storefront_enabled = $o;
  }
  
  public function create() {
	  try {
		  $dap_dbh = Dap_Connection::getConnection();
	  
  		  $sql = "insert into dap_storefrontproducts
				  (product_id, show_coupon, cart_header, cart_footer, buyImage, addtocartImage, resell_product, start_date, end_date, max_usage, actual_usage, storefront_price, storefront_enabled)
				  values
				  (:product_id, :showCouponCode, :cartHeader, :cartFooter, :buyImage, :addtocartImage, :resell_product, :start_date, :end_date, :max_usage, :actual_usage, :storefront_price, :storefront_enabled)";
				  
		  $stmt = $dap_dbh->prepare($sql);
	  
		  if ($this->getProduct_id() == "GENERAL SETTINGS") 
			  $this->setProduct_id(-1);
			  
		  $stmt->bindParam(':product_id', $this->getProduct_id(), PDO::PARAM_STR);
		  $stmt->bindParam(':showCouponCode', $this->getShowCouponCode(), PDO::PARAM_STR);
		  $stmt->bindParam(':cartHeader', $this->getCartHeader(), PDO::PARAM_STR);
		  $stmt->bindParam(':cartFooter', $this->getCartFooter(), PDO::PARAM_STR);
		  $stmt->bindParam(':buyImage', $this->getBuyImage(), PDO::PARAM_STR);
		  $stmt->bindParam(':addtocartImage', $this->getAddtocartImage(), PDO::PARAM_STR);
		  
		  $stmt->bindParam(':resell_product', $this->getResell_product(), PDO::PARAM_STR);
		  $stmt->bindParam(':start_date', $this->getStart_date(), PDO::PARAM_STR);
		  $stmt->bindParam(':end_date', $this->getEnd_date(), PDO::PARAM_STR);
		  $stmt->bindParam(':max_usage', $this->getMax_usage(), PDO::PARAM_INT);
		  $stmt->bindParam(':actual_usage', $this->getActual_usage(), PDO::PARAM_INT);
		  $stmt->bindParam(':storefront_price', $this->getStorefront_price(), PDO::PARAM_INT);
		  $stmt->bindParam(':storefront_enabled', $this->getStorefront_enabled(), PDO::PARAM_INT);
		   
		  $stmt->execute();
		  
		  logToFile("Calling Dap_StoreFrontProducts.class.php: getProduct_id" . $this->getProduct_id(),LOG_DEBUG_DAP);
		  logToFile("Calling Dap_StoreFrontProducts.class.php: getShowCouponCode" . $this->getShowCouponCode(),LOG_DEBUG_DAP);
		  logToFile("Calling Dap_StoreFrontProducts.class.php: getCartHeader" . $this->getCartHeader(),LOG_DEBUG_DAP);	
		  logToFile("Calling Dap_StoreFrontProducts.class.php: getCartFooter" . $this->getCartFooter(),LOG_DEBUG_DAP);	
		  logToFile("Calling Dap_StoreFrontProducts.class.php: getAddtocartImage" . $this->getAddtocartImage(),LOG_DEBUG_DAP);
		  
		  return $dap_dbh->lastInsertId();
		  $stmt = null;
		  $dap_dbh = null;

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
		  $dap_dbh = Dap_Connection::getConnection();
										  
		  $sql = "update dap_storefrontproducts set
					  show_coupon = :showCouponCode,
					  cart_header = :cartHeader,
					  cart_footer = :cartFooter,
					  buyImage = :buyImage,
					  addtocartImage = :addtocartImage,
					  resell_product = :resell_product,
					  start_date = :start_date,
					  end_date = :end_date,
					  max_usage = :max_usage,
					  actual_usage = :actual_usage,
					  storefront_price = :storefront_price,
					  storefront_enabled = :storefront_enabled
				  where
					  product_id = :productId";

		  logToFile("HERE in Dap_StoreFrontProducts update");
		  
		  if ($this->getProduct_id() == "GENERAL SETTINGS") 
			  $this->setProduct_id(-1);
		  
		  
		  $stmt = $dap_dbh->prepare($sql);
		  $stmt->bindParam(':showCouponCode', $this->getShowCouponCode(), PDO::PARAM_STR);
		  $stmt->bindParam(':cartHeader', $this->getCartHeader(), PDO::PARAM_STR);
		  $stmt->bindParam(':cartFooter', $this->getCartFooter(), PDO::PARAM_STR);
		  $stmt->bindParam(':buyImage', $this->getBuyImage(), PDO::PARAM_STR);
		  $stmt->bindParam(':addtocartImage', $this->getAddtocartImage(), PDO::PARAM_STR);
		  $stmt->bindParam(':resell_product', $this->getResell_product(), PDO::PARAM_STR);
		  $stmt->bindParam(':start_date', $this->getStart_date(), PDO::PARAM_STR);
		  $stmt->bindParam(':end_date', $this->getEnd_date(), PDO::PARAM_STR);
		  $stmt->bindParam(':max_usage', $this->getMax_usage(), PDO::PARAM_INT);
		  $stmt->bindParam(':actual_usage', $this->getActual_usage(), PDO::PARAM_INT);
		  $stmt->bindParam(':productId', $this->getProduct_id(), PDO::PARAM_STR);
		  $stmt->bindParam(':storefront_price', $this->getStorefront_price(), PDO::PARAM_INT);
		  $stmt->bindParam(':storefront_enabled', $this->getStorefront_enabled(), PDO::PARAM_INT);
		  
		  logToFile("Before exec in Dap_StoreFrontProducts update, productId=" . $this->getProduct_id());
		  logToFile("Before exec in Dap_StoreFrontProducts update, addtocart image=" . $this->getAddtocartImage());
		  
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

  public static function loadStoreFrontOptions($productId) {
	  $dap_dbh = Dap_Connection::getConnection();
	  $product = null;

	  if ($productId == "GENERAL SETTINGS") 
			  $productId = -1;
		  
	  //Load product details from database
	  $sql = "select *
		  from
			  dap_storefrontproducts
		  where
			  product_id = :productId";

	  $stmt = $dap_dbh->prepare($sql);
	  $stmt->bindParam(':productId', $productId, PDO::PARAM_STR);
	  $stmt->execute();

	  //echo "sql: $sql<br>"; exit;
	  //$result = mysql_query($sql);
	  //echo "rows returned: " . mysql_num_rows($result) . "<br>";

	  if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		  logToFile("Dap_StoreFrontProducts.class.php: addtocartImage=".$row["addtocartImage"],LOG_DEBUG_DAP);
		  logToFile("Dap_StoreFrontProducts.class.php: buyImage=".$row["buyImage"],LOG_DEBUG_DAP);
		  logToFile("Dap_StoreFrontProducts.class.php: storefront_price=".$row["storefront_price"],LOG_DEBUG_DAP);
		  logToFile("Dap_StoreFrontProducts.class.php: storefront_enabled=".$row["storefront_enabled"],LOG_DEBUG_DAP);
		  
		  $product = new Dap_StoreFrontProducts();

		  $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
	  
		  $product->setProduct_id( $row["product_id"] );
		  $product->setShowCouponCode( stripslashes($row["show_coupon"]) );
		  $product->setCartHeader( stripslashes($row["cart_header"]) );
		  $product->setCartFooter( stripslashes($row["cart_footer"]) );
		  $product->setBuyImage( $row["buyImage"] );
		  $product->setAddtocartImage( $row["addtocartImage"] );
		  
		  $product->setResell_product( stripslashes($row["resell_product"]) );
		  $product->setStart_date( stripslashes($row["start_date"]) );
		  $product->setEnd_date( stripslashes($row["end_date"]) );
		  $product->setMax_usage( $row["max_usage"] );
		  $product->setActual_usage( $row["actual_usage"] );
		  $product->setStorefront_price( $row["storefront_price"] );
		  $product->setStorefront_enabled( $row["storefront_enabled"] );
		  
		  //echo "id: " . $product->getDescription(); exit;
		  //return $product;
	  }

	  return $product;
  }


} // end class
?>