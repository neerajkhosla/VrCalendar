<?php

class Dap_ProductCategory {
   var $id;
   var $category_id;
  	var $product_id;
 
	function getId() {
		return $this->id;
	}
	function setId($o) {
		$this->id = $o;
	}

	function getCategory_id() {
		return $this->category_id;
	}
	function setCategory_id($o) {
		$this->category_id = $o;
	}

	function getProduct_id() {
		return $this->product_id;
	}
	function setProduct_id($o) {
		$this->product_id = $o;
	}
	
	public function create($productId, $categoryId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			$sql = "insert into dap_products_category_jn
						(product_id, category_id)
					values
						(:product_id, :category_id)";

			$stmt = $dap_dbh->prepare($sql);
			
			//logToFile("Dap_ProductCategory.class.php. category_id=" . $categoryId,LOG_DEBUG_DAP);
			
			$stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
			$stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
			
			$stmt->execute();

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

	public static function update($productId, $categoryId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			$sql = "update dap_products_category_jn set
							product_id = :product_id,
							category_id = :category_id							
						where id = :id";

			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
			$stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
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

	public static function findCategoryIdAndProductId ($productId, $categoryId) {
		$dap_dbh = Dap_Connection::getConnection();

		try {
			//Load category details from database
			$sql = "select *
				from
					dap_products_category_jn
					where 
							product_id=:productId and
							category_id=:categoryId";
	
			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
			
			$stmt->execute();
	
			//echo "sql: $sql<br>"; exit;
			//$result = mysql_query($sql);
			//echo "rows returned: " . mysql_num_rows($result) . "<br>";
	
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$productcategory = new Dap_ProductCategory();
				$productcategory->setId( $row["id"] );
				$productcategory->setCategory_id( stripslashes($row["category_id"]) );
				$productcategory->setProduct_id( stripslashes($row["product_id"]) );
			
				return $productcategory;
			}
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
		
		return NULL;
	}

	public static function loadProductCategory () {
		try {
		
			$dap_dbh = Dap_Connection::getConnection();
			$productCategoryArray = array();
			$sssAllowed="Y";
			$isMaster="N";
			
			$sql = "select * from dap_products_category_jn where product_id in (select id from dap_products where self_service_allowed=:sssAllowed and is_master=:isMaster) order by product_id, category_id";

				
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':sssAllowed', $sssAllowed, PDO::PARAM_STR);
			$stmt->bindParam(':isMaster', $isMaster, PDO::PARAM_STR);
			$stmt->execute();	
			$found="N";
			while ($row = $stmt->fetch()) {
				$prodcat = new Dap_ProductCategory();

				$prodcat->setId( $row["id"] );
				$prodcat->setProduct_id( $row["product_id"] );
				$prodcat->setCategory_id( $row["category_id"] );
			    $found="Y";
				//logToFile("Dap_ProductCategory.class: found=" . $found);
				$productCategoryArray[] = $prodcat;
			}
		    if($found=="N") {
			  //logToFile("Dap_ProductCategory.class: found=" . $found);
			  return NULL;
		    }  
			return $productCategoryArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	
	public static function areThereAvailableProductsWithContentLevelCreditsUnderThisCategory ($catId) {
		try {
		
			$dap_dbh = Dap_Connection::getConnection();
			$productCategoryArray = array();
			$sssAllowed="Y";
			$isMaster="N";
			
			$sql = "select * from dap_products_category_jn where product_id in (select id from dap_products where self_service_allowed=:sssAllowed and is_master=:isMaster) and category_id=:catId order by product_id, category_id";

				
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':sssAllowed', $sssAllowed, PDO::PARAM_STR);
			$stmt->bindParam(':isMaster', $isMaster, PDO::PARAM_STR);
			$stmt->bindParam(':catId', $catId, PDO::PARAM_STR);
			$stmt->execute();	
			$found="N";
			while ($row = $stmt->fetch()) {
				$prodcat = new Dap_ProductCategory();

				$prodcat->setId( $row["id"] );
				$prodcat->setProduct_id( $row["product_id"] );
				$prodcat->setCategory_id( $row["category_id"] );
			    $found="Y";
				//logToFile("Dap_ProductCategory.class: found=" . $found);
				$productCategoryArray[] = $prodcat;
			}
		    if($found=="N") {
			  //logToFile("Dap_ProductCategory.class: found=" . $found);
			  return NULL;
		    }  
			return $productCategoryArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	
	
	public static function areThereAvailableProductsUnderThisCategory ($catId) {
		try {
		
			$dap_dbh = Dap_Connection::getConnection();
			$productCategoryArray = array();
			$sssAllowed="Y";
			$isMaster="N";
			
			$sql = "select * from dap_products_category_jn where product_id in (select id from dap_products where self_service_allowed=:sssAllowed and is_master=:isMaster) and category_id=:catId order by product_id, category_id";

				
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':sssAllowed', $sssAllowed, PDO::PARAM_STR);
			$stmt->bindParam(':isMaster', $isMaster, PDO::PARAM_STR);
			$stmt->bindParam(':catId', $catId, PDO::PARAM_STR);
			$stmt->execute();	
			$found="N";
			while ($row = $stmt->fetch()) {
				$prodcat = new Dap_ProductCategory();

				$prodcat->setId( $row["id"] );
				$prodcat->setProduct_id( $row["product_id"] );
				$prodcat->setCategory_id( $row["category_id"] );
			    $found="Y";
				//logToFile("Dap_ProductCategory.class: found=" . $found);
				$productCategoryArray[] = $prodcat;
			}
		    if($found=="N") {
			  //logToFile("Dap_ProductCategory.class: found=" . $found);
			  return NULL;
		    }  
			return $productCategoryArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	
	public static function loadProductsUnderCategoryArr ($catId) {
		try {
		
			$dap_dbh = Dap_Connection::getConnection();
			$productCategoryArray = array();
			$sssAllowed="Y";
			$isMaster="N";
			
			$sql = "select * from dap_products_category_jn where product_id in (select id from dap_products where self_service_allowed=:sssAllowed and is_master=:isMaster) and category_id=:catId order by product_id, category_id";

			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':sssAllowed', $sssAllowed, PDO::PARAM_STR);
			$stmt->bindParam(':isMaster', $isMaster, PDO::PARAM_STR);
			$stmt->bindParam(':catId', $catId, PDO::PARAM_STR);
			$stmt->execute();	
			$found="N";
			
			$i=0;
			while ($row = $stmt->fetch()) {
				$prodcat[$i][0]=$row["category_id"];
				$prodcat[$i][1]=$row["product_id"];
				//$prodcat[$i][2]=$row["category_id"];
				
			    $found="Y";
				$i++;
				logToFile("Dap_ProductCategory.class: found=" . $found);
			}
			
		    if($found=="N") {
			  //logToFile("Dap_ProductCategory.class: found=" . $found);
			  return NULL;
		    }  
			logToFile("Dap_ProductCategory.class: ret prod array");
			return $prodcat;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	
	
	public static function loadProductsUnderCategory ($catId) {
		try {
		
			$dap_dbh = Dap_Connection::getConnection();
			$productCategoryArray = array();
			$sssAllowed="Y";
			$isMaster="N";
			
			$sql = "select * from dap_products_category_jn where product_id in (select id from dap_products where self_service_allowed=:sssAllowed and is_master=:isMaster) and category_id=:catId order by product_id, category_id";

				
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':sssAllowed', $sssAllowed, PDO::PARAM_STR);
			$stmt->bindParam(':isMaster', $isMaster, PDO::PARAM_STR);
			$stmt->bindParam(':catId', $catId, PDO::PARAM_STR);
			$stmt->execute();	
			$found="N";
			while ($row = $stmt->fetch()) {
				$prodcat = new Dap_ProductCategory();

				$prodcat->setId( $row["id"] );
				$prodcat->setProduct_id( $row["product_id"] );
				$prodcat->setCategory_id( $row["category_id"] );
			    $found="Y";
				//logToFile("Dap_ProductCategory.class: found=" . $found);
				$productCategoryArray[] = $prodcat;
			}
		    if($found=="N") {
			  //logToFile("Dap_ProductCategory.class: found=" . $found);
			  return NULL;
		    }  
			return $productCategoryArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	
	
	public static function loadProductsNotCategorized () {
		try {
		
			$dap_dbh = Dap_Connection::getConnection();
			$productCategoryArray = array();
			$sssAllowed="Y";
			$isMaster="N";
			
			$sql = "select * from dap_products where self_service_allowed=:sssAllowed and is_master=:isMaster and id not in (select product_id from dap_products_category_jn)";
				
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':sssAllowed', $sssAllowed, PDO::PARAM_STR);
			$stmt->bindParam(':isMaster', $isMaster, PDO::PARAM_STR);
		
			$stmt->execute();	
			
			$found="N";
			
			while ($row = $stmt->fetch()) {
				$product = new Dap_Product();

				$product->setId( $row["id"] );
				$product->setName( $row["name"] );
				$product->setDescription( $row["description"] );
				$product->setError_page_url( $row["error_page_url"] );
				$product->setPrice( $row["price"] );
				$product->setTrial_price( $row["trial_price"] );
				$product->setPrice_increment( $row["price_increment"] );
				$product->setPrice_increment_ceil( $row["price_increment_ceil"] );
				$product->setNum_sales( $row["num_sales"] );
				$product->setNum_days( $row["num_days"] );
				$product->setTimed_pricing_start_date( $row["timed_pricing_start_date"] );
				$product->setSelfservice_start_date( $row["selfservice_start_date"] );
				$product->setSelfservice_end_date( $row["selfservice_end_date"] );
				$product->setTotal_occur( $row["total_occur"] );
				$product->setIs_recurring( $row["is_recurring"] );
				$product->setRecurring_cycle_1( $row["recurring_cycle_1"] );
				$product->setRecurring_cycle_2( $row["recurring_cycle_2"] );
				$product->setRecurring_cycle_3( $row["recurring_cycle_3"] );
				$product->setStatus( $row["status"] );
				$product->setNotification_id( $row["notification_id"] );
				$product->setThankyou_page_url( $row["thankyou_page_url"] );
				$product->setThirdPartyEmailIds( $row["thirdPartyEmailIds"] );
				$product->setSubscribeTo( stripslashes($row["subscribe_to"]) );
				$product->setUnsubscribeFrom( stripslashes($row["unsubscribe_from"]) );
				$product->setSelf_service_allowed( stripslashes($row["self_service_allowed"]) );
				$product->setIs_master( stripslashes($row["is_master"]) );
				$product->setAllowContentLevelCredits( stripslashes($row["allowContentLevelCredits"]) );
				$product->setProduct_image_url( stripslashes($row["product_image_url"]) );
				$product->setCredits( stripslashes($row["credits"]) );
				$product->setDouble_optin_subject( stripslashes($row["double_optin_subject"]) );
				$product->setDouble_optin_body( stripslashes($row["double_optin_body"]) );
				$product->setThankyou_email_subject( stripslashes($row["thankyou_email_subject"]) );
				$product->setThankyou_email_body( stripslashes($row["thankyou_email_body"]) );
				$product->setLogged_in_url( stripslashes($row["logged_in_url"]) );
				$product->setIs_free_product( stripslashes($row["is_free_product"]) );
				$product->setAllow_free_signup( stripslashes($row["allow_free_signup"]) );
				//$product->setQuantity( stripslashes($row["quantity"]) );
				$ProductsList[] = $product;
				$found="Y";
				//logToFile("Dap_ProductCategory.class: found no cat prods=" . $found);
			}
			
		    if($found=="N") {
			  //logToFile("Dap_ProductCategory.class: found=" . $found);
			  return NULL;
		    }  
			return $ProductsList;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	
	/*public static function loadCategoryByProductId ($productId) {
		try {
		
			$dap_dbh = Dap_Connection::getConnection();
			$productCategoryArray = array();
	
			$sql = "select *
			from
				dap_products_category_jn
				where product_id=:productId";

	
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();	
			
			while ($obj = $stmt->fetch()) {
				$productCategoryArray[] = $obj;
			}
	
			return $productCategoryArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}*/
	
		//Delete
	public static function removeProductCategoryAssociation($id) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			//delete from usersproducts table
			$sql = "delete from  
					dap_products_category_jn
					where id =:id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			$stmt = null;
			$dap_dbh = null;		
			return;			
		} catch (PDOException $e) {
			$stmt = null;
			//$dap_dbh = null;				
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			$stmt = null;
			//$dap_dbh = null;			
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}	
	
	
}
?>