<?php

class Dap_FileResource extends Dap_Resource {

	var $url;
	var $display_in_list;
	
	
	function getUrl() {
	        return $this->url;
	}
	
	function setUrl($o) {
	      $this->url = $o;
	}
	
	function getDisplay_in_list() {
	        return $this->display_in_list;
	}
	
	function setDisplay_in_list($o) {
	      $this->display_in_list = $o;
	}
	
	public static function getResourceName($resourceId) {
		try {

			//Load resource details from database
			$dap_dbh = Dap_Connection::getConnection();
			$sql = "select 
						fr.id as id, fr.url as url, fr.name as name
					from
						dap_file_resources fr
						
					where
						fr.id = :resourceId";
					
					$stmt = $dap_dbh->prepare($sql);
			
			
			logToFile("Dap_FileResource.class.php: resourceId=". $resourceId,LOG_DEBUG_DAP);
			
			
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':resourceId', $resourceId, PDO::PARAM_STR);
			$stmt->execute();	
			
			$resourceArray = array();
			
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	
				logToFile("Dap_FileResource.class.php: resource found=". $row['name'], LOG_DEBUG_DAP);
				
			}
		
			return $row;
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
			
					
	}
	
	public static function loadFileResourceForAutoDrip($product_id, $id) {
		try {

			//Load resource details from database
			$dap_dbh = Dap_Connection::getConnection();
			
			
			$sql = "select *  from 	dap_products_resources_jn 
				where
					id = :id and
					product_id = :product_id and
					prj.resource_type = 'F'
				order by prj.display_order";
			
				
			$stmt = $dap_dbh->prepare($sql);
			
			logToFile("Dap_FileResource.class.php: productId=". $product_id,LOG_DEBUG_DAP);
			logToFile("Dap_FileResource.class.php: id=". $id,LOG_DEBUG_DAP);
			
			$stmt->bindParam(':id', $id, PDO::PARAM_STR);
			$stmt->bindParam(':product_id', $product_id, PDO::PARAM_STR);		
			$stmt->execute();
			
			$resourceArray = array();
		
			if ($obj = $stmt->fetch()) {
				logToFile("Dap_FileResource.class.php: resource found=". $obj["name"], LOG_DEBUG_DAP);
				logToFile("Dap_FileResource.class.php: resource URL found=". $obj["url"], LOG_DEBUG_DAP);
			}
		
			return $obj;
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}	
		
	public static function loadFileResource($productId, $resourceId, $sss="N", $rettype="array") {
		try {

			//Load resource details from database
			$dap_dbh = Dap_Connection::getConnection();
			
			
			$sql = "select 
					fr.id, fr.url, fr.name, fr.display_in_list, prj.id as ID, prj.credits_assigned, excerpt, prj.display_order as display_order,
					prj.is_free, prj.start_day, prj.end_day, DATE_FORMAT(prj.start_date, '%m-%d-%Y') as start_date, DATE_FORMAT(prj.end_date, '%m-%d-%Y') as end_date, prj.num_clicks, prj.status as product_resource_status
				from
					dap_file_resources fr, 
					dap_products_resources_jn prj
				where
					fr.id = :resourceId and
					fr.id = prj.resource_id and
					prj.product_id = :productId and
					prj.resource_type = 'F'
				order by prj.display_order";
			
				
			$stmt = $dap_dbh->prepare($sql);
			
			logToFile("Dap_FileResource.class.php: productId=". $productId,LOG_DEBUG_DAP);
			logToFile("Dap_FileResource.class.php: resourceId=". $resourceId,LOG_DEBUG_DAP);
			
			$stmt->bindParam(':resourceId', $resourceId, PDO::PARAM_STR);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_STR);		
			$stmt->execute();
			
			$resourceArray = array();
		
			if ($obj = $stmt->fetch()) {
	
				$resourceArray[] = $obj;
				logToFile("Dap_FileResource.class.php: id=". $obj["id"], LOG_DEBUG_DAP);
				logToFile("Dap_FileResource.class.php: resource found=". $obj["name"], LOG_DEBUG_DAP);
				logToFile("Dap_FileResource.class.php: resource URL found=". $obj["url"], LOG_DEBUG_DAP);
				//logToFile("Dap_FileResource.class.php: resource found=". $resourceArray[0].fr.name, LOG_DEBUG_DAP);
				logToFile("Dap_FileResource.class.php: prj.credits_assigned found=". $obj['prj.credits_assigned'], LOG_DEBUG_DAP);
			}
		    if($rettype == "array") 
			  return $resourceArray;
			else 
			  return $obj;
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	
	public static function loadEmailResourceAutomatedUsingContentId($productId, $file_resource_id) {
		try {

			//Load resource details from database
			$dap_dbh = Dap_Connection::getConnection();
			
			
			$sql = "select *					
				from
					dap_products_resources_jn
				where
					product_id = :productId and
					resource_type = 'E' and
					file_resource_id = :file_resource_id
				order by display_order";
			
				
			$stmt = $dap_dbh->prepare($sql);
			
			logToFile("Dap_FileResource.class.php: productId=". $productId,LOG_DEBUG_DAP);
			logToFile("Dap_FileResource.class.php: file_resource_id=". $file_resource_id,LOG_DEBUG_DAP);
			
			$stmt->bindParam(':file_resource_id', $file_resource_id, PDO::PARAM_STR);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_STR);		
			$stmt->execute();
			
			$resourceArray = array();
		
			if ($obj = $stmt->fetch()) {
	
				$resourceArray[] = $obj;
				logToFile("Dap_FileResource.class.php: resource found=". $obj["name"], LOG_DEBUG_DAP);
				logToFile("Dap_FileResource.class.php: resource URL found=". $obj["url"], LOG_DEBUG_DAP);
				//logToFile("Dap_FileResource.class.php: resource found=". $resourceArray[0].fr.name, LOG_DEBUG_DAP);
				//logToFile("Dap_FileResource.class.php: prj.credits_assigned found=". $obj['prj.credits_assigned'], LOG_DEBUG_DAP);
			}
		
			return $obj;
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	public static function loadFileResourceAutomated($productId, $file_resource_id, $sss="N") {
		try {

			//Load resource details from database
			$dap_dbh = Dap_Connection::getConnection();
			
			
			$sql = "select 
					fr.id, fr.url, fr.name, fr.display_in_list, prj.id, prj.credits_assigned, prj.display_order as display_order,
					prj.is_free, prj.start_day, prj.end_day, DATE_FORMAT(prj.start_date, '%m-%d-%Y') as start_date, DATE_FORMAT(prj.end_date, '%m-%d-%Y') as end_date, prj.num_clicks, prj.status as product_resource_status
				from
					dap_file_resources fr, 
					dap_products_resources_jn prj
				where
					prj.id = :file_resource_id and
					fr.id = prj.resource_id and
					prj.product_id = :productId and
					prj.resource_type = 'F'
				order by prj.display_order";
			
				
			$stmt = $dap_dbh->prepare($sql);
			
			logToFile("Dap_FileResource.class.php: productId=". $productId,LOG_DEBUG_DAP);
			logToFile("Dap_FileResource.class.php: resourceId=". $file_resource_id,LOG_DEBUG_DAP);
			
			$stmt->bindParam(':file_resource_id', $file_resource_id, PDO::PARAM_STR);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_STR);		
			$stmt->execute();
			
			$resourceArray = array();
		
			if ($obj = $stmt->fetch()) {
	
				$resourceArray[] = $obj;
				logToFile("Dap_FileResource.class.php: resource found=". $obj["name"], LOG_DEBUG_DAP);
				logToFile("Dap_FileResource.class.php: resource URL found=". $obj["url"], LOG_DEBUG_DAP);
				//logToFile("Dap_FileResource.class.php: resource found=". $resourceArray[0].fr.name, LOG_DEBUG_DAP);
				logToFile("Dap_FileResource.class.php: prj.credits_assigned found=". $obj['prj.credits_assigned'], LOG_DEBUG_DAP);
			}
		
			return $obj;
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	

	public static function loadFileResourcesSSS($userId, $productId) {
		try {
			
			
			//Load resource details from database
			$dap_dbh = Dap_Connection::getConnection();
	
			if($userId != "") {
				$sql = "select fr.id as id, fr.url as url, fr.name as name, fr.display_in_list,
							prj.is_free as is_free, prj.credits_assigned as credits_assigned, prj.start_day, prj.end_day, DATE_FORMAT(prj.start_date, '%m-%d-%Y') as start_date, DATE_FORMAT(prj.end_date, '%m-%d-%Y') as end_date, prj.num_clicks, prj.status as product_resource_status,prj.excerpt as excerpt
						from
							dap_file_resources fr, 
							dap_products_resources_jn prj,
							dap_products prod
						where
							fr.id = prj.resource_id and
							prj.product_id = :productId and
							prod.id= prj.product_id and 
							prod.self_service_allowed = 'Y' and
							prod.is_master = 'N' and
							prj.resource_id NOT in (select resource_id from dap_users_credits where user_id=:userId)
							order by prj.display_order, fr.name";
							
				$stmt = $dap_dbh->prepare($sql);
				
				$stmt->bindParam(':productId', $productId, PDO::PARAM_STR);		
				$stmt->bindParam(':userId', $userId, PDO::PARAM_STR);	
			}
			else {
				$sql = "select fr.id as id, fr.url as url, fr.name as name, fr.display_in_list,
						prj.is_free as is_free, prj.credits_assigned as credits_assigned, prj.start_day, prj.end_day, DATE_FORMAT(prj.start_date, '%m-%d-%Y') as start_date, DATE_FORMAT(prj.end_date, '%m-%d-%Y') as end_date, prj.num_clicks, prj.status as product_resource_status,prj.excerpt as excerpt
					from
						dap_file_resources fr, 
						dap_products_resources_jn prj,
						dap_products prod
					where
						fr.id = prj.resource_id and
						prj.product_id = :productId and
						prod.id= prj.product_id and 
						prod.self_service_allowed = 'Y' and
						prod.is_master = 'N' 
						order by prj.display_order, fr.name";
						
				$stmt = $dap_dbh->prepare($sql);
				
				$stmt->bindParam(':productId', $productId, PDO::PARAM_STR);
			}
		
			$stmt->execute();
			
			$resourceArray = array();
			
			while ($obj = $stmt->fetch()) {
				
				$resourceArray[] = $obj;
				logToFile("Dap_FileResource.class.php: loadFileResourcesSSS=". $resourceArray["id"],LOG_DEBUG_DAP);
			}
		
			return $resourceArray;
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	public static function loadALLFileResourcesSSS($userId, $productId) {
		try {
			
			//Load resource details from database
			$dap_dbh = Dap_Connection::getConnection();
	
			$sql = "select fr.id as id, fr.url as url, fr.name as name, fr.display_in_list,
						prj.is_free as is_free, prj.credits_assigned as credits_assigned, prj.start_day, prj.end_day, DATE_FORMAT(prj.start_date, '%m-%d-%Y') as start_date, DATE_FORMAT(prj.end_date, '%m-%d-%Y') as end_date, prj.num_clicks, prj.status as product_resource_status
					from
						dap_file_resources fr, 
						dap_products_resources_jn prj				
					where
						fr.id = prj.resource_id and
						prj.product_id = :productId and
						prj.credits_assigned > 0 and 
						prj.resource_id NOT in (select resource_id from dap_users_credits where user_id=:userId and product_id=:productId)
						order by prj.display_order, fr.name";
						
			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->bindParam(':productId', $productId, PDO::PARAM_STR);		
			$stmt->bindParam(':userId', $userId, PDO::PARAM_STR);	
			
			$stmt->execute();
			
			$resourceArray = array();
			
			while ($obj = $stmt->fetch()) {
				
				$resourceArray[] = $obj;
//				logToFile("Dap_FileResource.class.php: loadFileResourcesSSS=". $resourceArray["id"],LOG_DEBUG_DAP);
			}
		
			return $resourceArray;
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	public static function loadResourceSSS($userId, $productId, $resourceId) {
		try {
			
			//Load resource details from database
			$dap_dbh = Dap_Connection::getConnection();
		   logToFile("Dap_FileResource.class.php: ENTERING loadResourceSSS",LOG_DEBUG_DAP);
				
			$sql = "select fr.id as id, fr.url as url, fr.name as name, fr.display_in_list,
						prj.is_free as is_free, prj.credits_assigned as credits_assigned, prj.start_day, prj.end_day, DATE_FORMAT(prj.start_date, '%m-%d-%Y') as start_date, DATE_FORMAT(prj.end_date, '%m-%d-%Y') as end_date, prj.num_clicks, prj.status as product_resource_status
					from
						dap_file_resources fr, 
						dap_products_resources_jn prj				
					where
						fr.id = prj.resource_id and
						prj.product_id=:productId and
						prj.resource_type = 'F' and
						fr.id=:resourceId";
						
			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->bindParam(':productId', $productId, PDO::PARAM_STR);	
			$stmt->bindParam(':resourceId', $resourceId, PDO::PARAM_STR);	
						
			$stmt->execute();
			
			$resourceArray = array();
			
			while ($obj = $stmt->fetch()) {
				
				$resourceArray[] = $obj;
				logToFile("Dap_FileResource.class.php: loadResourceSSS=",LOG_DEBUG_DAP);
			}
		
			return $resourceArray;
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	
	public static function loadResourceByTitleSSS($userId, $productId, $url) {
		try {
			
			//Load resource details from database
			$dap_dbh = Dap_Connection::getConnection();
			
			logToFile("Dap_FileResource.class.php: loadResourceByTitleSSS, $userId, $productId, $url",LOG_DEBUG_DAP);
			
			$sql = "select fr.id as id, fr.url as url, fr.name as name, fr.display_in_list,
						prj.is_free as is_free, prj.credits_assigned as credits_assigned, prj.start_day, prj.end_day, DATE_FORMAT(prj.start_date, '%m-%d-%Y') as start_date, DATE_FORMAT(prj.end_date, '%m-%d-%Y') as end_date, prj.num_clicks, prj.status as product_resource_status
					from
						dap_file_resources fr, 
						dap_products_resources_jn prj,	
						dap_products p
					where
						fr.id = prj.resource_id and
						prj.product_id = :productId and
						fr.url=:url and
						prj.resource_id NOT in (select resource_id from dap_users_credits where user_id=:userId) and
						p.id=prj.product_id and
						p.self_service_allowed = 'Y' and
						p.is_master = 'N'";
						
			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->bindParam(':productId', $productId, PDO::PARAM_STR);		
			$stmt->bindParam(':userId', $userId, PDO::PARAM_STR);	
			$stmt->bindParam(':url', $url, PDO::PARAM_STR);	
			$stmt->execute();
			
			$resourceArray = array();
			
			if ($obj = $stmt->fetch()) {
				
				$resourceArray[] = $obj;
  			    //logToFile("Dap_FileResource.class.php: loadResourceByTitleSSS=". $resourceArray["id"],LOG_DEBUG_DAP);
			}
		
			return $resourceArray;
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	public static function loadAllAvailableFileResources() {
		try {
			
			//Load resource details from database
			$dap_dbh = Dap_Connection::getConnection();
	
			$sql = "select id, url, name, display_in_list
					from
						dap_file_resources
					order by id";
						
			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->execute();
			
			$resourceArray = array();
			
			while ($obj = $stmt->fetch()) {
				/*$rArray = array();
				logToFile("Dap_FileResource.class.php: userId=". $userId,LOG_DEBUG_DAP);
				logToFile("Dap_FileResource.class.php: productId=". $productId,LOG_DEBUG_DAP);
				logToFile("Dap_FileResource.class.php: loadFileResourcesSSS=". $obj["name"],LOG_DEBUG_DAP);
				$rArray["id"] = $obj ["id"];
				$rArray["name"] = $obj ["name"];
				$rArray["url"] = $obj ["url"];
				
				$resourceArray[] = $rArray;*/
				$resourceArray[] = $obj;
			}
		
			return $resourceArray;
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	
	public static function loadAvailableFileResourcesSSS($userId, $productId) {
		try {
			
			//Load resource details from database
			$dap_dbh = Dap_Connection::getConnection();
	
			$sql = "select fr.id as id, fr.url as url, fr.name as name, fr.display_in_list,
						prj.is_free as is_free, prj.credits_assigned as credits_assigned, prj.start_day, prj.end_day, DATE_FORMAT(prj.start_date, '%m-%d-%Y') as start_date, DATE_FORMAT(prj.end_date, '%m-%d-%Y') as end_date, prj.num_clicks, prj.status as product_resource_status
					from
						dap_file_resources fr, 
						dap_products_resources_jn prj,
						dap_users_credits duc
					where
						fr.id = prj.resource_id and
						prj.product_id=:productId and
						prj.credits_assigned > 0 and 
						prj.resource_id=duc.resource_id and
						duc.user_id=:userId and
						duc.product_id=:productId
						order by prj.display_order";
						
			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);		
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);	
			
			$stmt->execute();
			
			$resourceArray = array();
			
			while ($obj = $stmt->fetch()) {
				/*$rArray = array();
				logToFile("Dap_FileResource.class.php: userId=". $userId,LOG_DEBUG_DAP);
				logToFile("Dap_FileResource.class.php: productId=". $productId,LOG_DEBUG_DAP);
				logToFile("Dap_FileResource.class.php: loadFileResourcesSSS=". $obj["name"],LOG_DEBUG_DAP);
				$rArray["id"] = $obj ["id"];
				$rArray["name"] = $obj ["name"];
				$rArray["url"] = $obj ["url"];
				
				$resourceArray[] = $rArray;*/
				$resourceArray[] = $obj;
			}
		
			return $resourceArray;
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	public static function loadProductsUnderWhichContentIsProtected($aipurl) {
		try {
			
			//Load resource details from database
			$dap_dbh = Dap_Connection::getConnection();
			
			logToFile("Dap_FileResource.class.php: loadProductsUnderWhichContentIsProtected(): aipurl=". $aipurl,LOG_DEBUG_DAP);
			
			$sql = "select 
					p.id as pid, p.name as pname, fr.id, fr.url, fr.name, fr.display_in_list, prj.id as ID, prj.credits_assigned, excerpt, prj.display_order as display_order,
					prj.is_free, prj.start_day, prj.end_day, DATE_FORMAT(prj.start_date, '%m-%d-%Y') as start_date, DATE_FORMAT(prj.end_date, '%m-%d-%Y') as end_date, prj.num_clicks, prj.status as product_resource_status
				from
					dap_file_resources fr, 
					dap_products_resources_jn prj,
					dap_products p
				where
					fr.url = :url and
					fr.id = prj.resource_id and
					prj.product_id=p.id and
					prj.resource_type = 'F'";

						
			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->bindParam(':url', $aipurl, PDO::PARAM_INT);		
			$stmt->execute();
			
			$resourceArray = array();
			
			while ($obj = $stmt->fetch()) {
				/*$rArray = array();
				logToFile("Dap_FileResource.class.php: userId=". $userId,LOG_DEBUG_DAP);
				logToFile("Dap_FileResource.class.php: productId=". $productId,LOG_DEBUG_DAP);
				logToFile("Dap_FileResource.class.php: loadFileResourcesSSS=". $obj["name"],LOG_DEBUG_DAP);
				$rArray["id"] = $obj ["id"];
				$rArray["name"] = $obj ["name"];
				$rArray["url"] = $obj ["url"];
				
				$resourceArray[] = $rArray;*/
				$resourceArray[] = $obj;
			}
		
			return $resourceArray;
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	
	public static function loadProtectedFileResourcesPerProduct($productId) {
		try {
			//Load resource details from database
			$dap_dbh = Dap_Connection::getConnection();
			$sql = "select prj.id as id, fr.id as file_resource_id, fr.url as url, fr.name as name, fr.display_in_list,
						prj.is_free as is_free, prj.credits_assigned as credits_assigned, prj.start_day, prj.end_day, DATE_FORMAT(prj.start_date, '%m-%d-%Y') as start_date, DATE_FORMAT(prj.end_date, '%m-%d-%Y') as end_date, prj.num_clicks, prj.status as product_resource_status
					from
						dap_file_resources fr, 
						dap_products_resources_jn prj				
					where
						fr.id = prj.resource_id and
						prj.product_id = :productId and 
						prj.resource_type = 'F' 
						order by prj.display_order, fr.name";
						
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_STR);		
			$stmt->execute();
			$resourceArray = array();
			
			while ($obj = $stmt->fetch()) {
				$resourceArray[] = $obj;
				//logToFile("Dap_FileResource.class.php: loadProtectedFileResourcesPerProduct=". $resourceArray["id"],LOG_DEBUG_DAP);
			}
		
			return $resourceArray;
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	
	public static function updateFileResource($productId, $resourceId, $name, $display_in_list, $product_resource_status, $is_free, $start_day, $end_day, $start_date, $end_date, $num_clicks, $credits_assigned="", $pay_per_post="",$order="1", $excerpt="") {
		//Update resouce details
		try {

			try {  // FOR AUTOMATED DRIPPING
			  // Check if the automated autoresponder ENABLED and requires updating
			  $resource = Dap_FileResource::loadFileResource($productId, $resourceId,"","obj");
			  $auto_drip_id=$resource["ID"];
			  
			  logToFile("Dap_FileResource.class.php: updateFileResource.  ID=". $resource["ID"],LOG_DEBUG_DAP);
			  logToFile("Dap_FileResource.class.php: updateFileResource. productId=". $productId,LOG_DEBUG_DAP);
			  logToFile("Dap_FileResource.class.php: updateFileResource. resourceId=". $resourceId,LOG_DEBUG_DAP);
			  
			  if ($auto_drip_id > 0) {
				logToFile("Dap_FileResource.class.php: delete the automated email set for this conten: auto_drip_id=". $auto_drip_id,LOG_DEBUG_DAP);	
				$FileResource = Dap_FileResource::loadEmailResourceAutomatedUsingContentId($productId, $auto_drip_id);
				
				$emailResourceId = $FileResource["resource_id"];
				//$product_resource_status = $FileResource["status"];
				$product_resource_status = "A";
				$prjId = $FileResource["id"];
				$start_day =  $start_day;
				$start_date =   $start_date;
				$is_free =  $is_free;
							
				Dap_EmailResource::updateEmailProductRel($productId, $emailResourceId, $product_resource_status, $start_day, $start_date, $is_free, $prjId, $auto_drip_id);
				logToFile("Dap_FileResource.class.php: deleted the automated email set for this content: response=". $response,LOG_DEBUG_DAP);	
			  }
			  else {
				  logToFile("Dap_FileResource.class.php: No auomated email to update",LOG_DEBUG_DAP);
			  }
			} catch (PDOException $e) {
			  logToFile($e->getMessage(),LOG_FATAL_DAP);
			  throw $e;
			} catch (Exception $e) {
			  logToFile($e->getMessage(),LOG_FATAL_DAP);
			  throw $e;
			}	
			
			$dap_dbh = Dap_Connection::getConnection();
			$dap_dbh->beginTransaction(); //begin the transaction
			
			//First, update file_resources table
			$sql = "update
						dap_file_resources
					set 
						name = :name,
						display_in_list = :display_in_list
					where
						id = :resourceId";
						
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':resourceId', $resourceId, PDO::PARAM_INT);
			$stmt->bindParam(':name', $name, PDO::PARAM_STR);
			$stmt->bindParam(':display_in_list', $display_in_list, PDO::PARAM_STR);
			$stmt->execute();	
			
			//Next, update products_resources table
			if($is_free == "") $is_free = "N";
			
			if($start_day == "") $start_day = 0;
			
			if($end_day == "") $end_day = 0;
			if($credits_assigned == "") $credits_assigned = 0;
			
			if($start_date == "") $start_date = null;
			if($end_date == "") $end_date = null;
			if($num_clicks == "") $num_clicks = 0;
			
			
			$sql = "update
						dap_products_resources_jn
					set 
						is_free = :is_free,
						credits_assigned = :credits_assigned,
						excerpt = :excerpt,
						pay_per_post = :pay_per_post,
						display_order=:order,
						start_day = :start_day,
						end_day = :end_day,
						start_date = STR_TO_DATE(:start_date, '%m-%d-%Y'),
						end_date = STR_TO_DATE(:end_date, '%m-%d-%Y'),
						num_clicks = :num_clicks,
						status = :product_resource_status
					where
						product_id = :productId and
						resource_id = :resourceId and
						resource_type = 'F'
					";
						
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':is_free', $is_free, PDO::PARAM_STR);
			$stmt->bindParam(':credits_assigned', $credits_assigned, PDO::PARAM_STR);
			$stmt->bindParam(':excerpt', $excerpt, PDO::PARAM_STR);
			$stmt->bindParam(':pay_per_post', $pay_per_post, PDO::PARAM_STR);
			$stmt->bindParam(':order', $order, PDO::PARAM_INT);
			$stmt->bindParam(':start_day', $start_day, PDO::PARAM_STR);
			$stmt->bindParam(':end_day', $end_day, PDO::PARAM_STR);
			$stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
			$stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
			$stmt->bindParam(':num_clicks', $num_clicks, PDO::PARAM_INT);
			$stmt->bindParam(':product_resource_status', $product_resource_status, PDO::PARAM_STR);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->bindParam(':resourceId', $resourceId, PDO::PARAM_INT);
			
			
			logToFile("Dap_FileResource.classphp: productId=". $productId,LOG_DEBUG_DAP);
			logToFile("Dap_FileResource.classphp: excerpt=". $excerpt,LOG_DEBUG_DAP);
			logToFile("Dap_FileResource.classphp: credits_assigned=". $credits_assigned,LOG_DEBUG_DAP);
			logToFile("Dap_FileResource.classphp: credits_assigned=". $credits_assigned,LOG_DEBUG_DAP);
			logToFile("Dap_FileResource.classphp: pay_per_post=". $pay_per_post,LOG_DEBUG_DAP);
			logToFile("Dap_FileResource.classphp: order=". $order,LOG_DEBUG_DAP);
		
			
			$stmt->execute();
			
			$dap_dbh->commit(); //commit the transaction
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


	public static function findContentWith0Credits($productId) {
		try {

			//Load resource details from database
			$dap_dbh = Dap_Connection::getConnection();
	
			$sql = "SELECT count( * ) as result
FROM dap_file_resources fr, dap_products_resources_jn prj
WHERE fr.id = prj.resource_id
AND prj.product_id =:productId
AND prj.resource_type = 'F'
AND (
prj.credits_assigned <=0
OR prj.credits_assigned IS NULL)";
		
			$stmt = $dap_dbh->prepare($sql);
			
			logToFile("Dap_FileResource.class.php: productId=". $productId,LOG_DEBUG_DAP);
			

			$stmt->bindParam(':productId', $productId, PDO::PARAM_STR);		
			$stmt->execute();
						
			if ($obj = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$contentWith0Credits = $obj["result"];
			}
			
				
			logToFile("Dap_FileResource.class.php: contentWith0Credits=". $contentWith0Credits,LOG_DEBUG_DAP);
			return $contentWith0Credits;
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}

	public static function findContentWithCredits($productId) {
		try {

			//Load resource details from database
			$dap_dbh = Dap_Connection::getConnection();
	
			$sql = "SELECT count( * ) as result
FROM dap_file_resources fr, dap_products_resources_jn prj
WHERE fr.id = prj.resource_id
AND prj.product_id =:productId
AND prj.resource_type = 'F'
AND (
prj.credits_assigned >= 0
OR prj.credits_assigned IS NOT NULL)";
		
			$stmt = $dap_dbh->prepare($sql);
			
			logToFile("Dap_FileResource.class.php: findContentWithCredits productId=". $productId,LOG_DEBUG_DAP);
			

			$stmt->bindParam(':productId', $productId, PDO::PARAM_STR);		
			$stmt->execute();
						
			if ($obj = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$contentWith0Credits = $obj["result"];
			}
			
				
			logToFile("Dap_FileResource.class.php: findContentWithCredits contentWith0Credits=". $contentWith0Credits,LOG_DEBUG_DAP);
			return $contentWith0Credits;
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}

	public static function countCreditsPerChild($productId) {
		try {

			//Load resource details from database
			$dap_dbh = Dap_Connection::getConnection();
			
		
						$sql = "select SUM(prj.credits_assigned) as totalCredits
						from
						dap_file_resources fr, 
						dap_products_resources_jn prj
					where
						fr.id = prj.resource_id and
						prj.product_id = :productId and
						prj.resource_type = 'F' and
					   prj.credits_assigned >= 0
				  	order by prj.display_order";
		
			$stmt = $dap_dbh->prepare($sql);
			
			logToFile("Dap_FileResource.class.php: productId=". $productId,LOG_DEBUG_DAP);
			

			$stmt->bindParam(':productId', $productId, PDO::PARAM_STR);		
			$stmt->execute();
			
			$totalCredits=0;			
			if ($obj = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$totalCredits = $obj["totalCredits"];
			}
		
			return $totalCredits;
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}


	public static function getChildProductCredit($productId) {
		try {

			//Load resource details from database
			$dap_dbh = Dap_Connection::getConnection();
			
		
						$sql = "select prj.credits_assigned as credits_assigned
						from
						dap_file_resources fr, 
						dap_products_resources_jn prj
					where
						fr.id = prj.resource_id and
						prj.product_id = :productId and
						prj.resource_type = 'F' and
					   prj.credits_assigned >= 0
				  	order by prj.display_order";
		
			$stmt = $dap_dbh->prepare($sql);
			
			logToFile("Dap_FileResource.class.php: productId=". $productId,LOG_DEBUG_DAP);
			

			$stmt->bindParam(':productId', $productId, PDO::PARAM_STR);		
			$stmt->execute();
						
			if ($obj = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$totalCredits = $obj["credits_assigned"];
			}
		
			return $totalCredits;
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}


  public static function getChildCredit($productId) {
		try {

			//Load resource details from database
			$dap_dbh = Dap_Connection::getConnection();
			
		
						$sql = "select credits
						from
						dap_products
					where
						id = :productId";
		
			$stmt = $dap_dbh->prepare($sql);

			$stmt->bindParam(':productId', $productId, PDO::PARAM_STR);		
			$stmt->execute();
						
			if ($obj = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$totalCredits = $obj["credits"];
							
				logToFile("Dap_FileResource.class.php:getChildCredit(): totalCredits=". $totalCredits,LOG_DEBUG_DAP);
			}
		
			return $totalCredits;
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	public static function addFileResourceToProduct($productId, $file, $start_day=1, $credits_assigned="") {
		//Add given resource to Product
		try {
			logToFile("In addFileResourceToProduct"); 
			$dap_dbh = Dap_Connection::getConnection();
			//logToFile("productId: $productId, file: $file"); 
			
			/*
				First, check to see if this resource is already
				a part of this product. If yes, then return error.
			*/
			$sql = "SELECT 
						*
					FROM 
						dap_file_resources fr, 
						dap_products_resources_jn prj
					WHERE 
						fr.url = :file AND 
						fr.id = prj.resource_id AND
						prj.product_id = :productId AND
						prj.resource_type = 'F'";
						
			logToFile("sql in addFileResourceToProduct"); 
			
			$stmt = $dap_dbh->prepare($sql);
			
			
			$stmt->bindParam(':file', $file, PDO::PARAM_STR);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->execute();
			$totalRows = count($stmt->fetchAll());
			if ( $totalRows > 0 ) {
				if ($credits_assigned != "") {
					return "<span class='BodyTextRed'><b>" . "Content already protected as part of this product.<br/>Content cannot be made available in Credit Store if added here<br/>" . "=> '$file'</b></span><br/>";
				}
				else {
					return "<b><font color='#CC0000'>" . ERROR_DB_RESOURCE_ALREADY_ASSIGNED . " $file</font></b><br/>";
				}
			}		
			$stmt = null;
			

			/*
				Next, check to see if this resource already exists
				in dap_file_resources table. If yes, then don't add
				it. Use the existing resource id to add to this Product.
			*/
			
			$resource_id = 0;
			
			$sql = "SELECT 
						id
					FROM 
						dap_file_resources
					WHERE 
						url = :file";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':file', $file, PDO::PARAM_STR);
			$stmt->execute();
			//$totalRows = count($stmt->fetchAll());
			if ($row = $stmt->fetch()) {
				//It already exists, so don't add new file resource.
				//Simply use the id of existing resource and add to dap_products_resources_jn
				$resource_id = $row["id"];
			} else {
				//Resource doesn't already exist. So, insert into file_resources
				//But first, fetch page title if available
				//Don't fetch title if file has an extension like .exe, etc
				$title = "";
				$ext = getFileExtension($file);
				if( 
					( $ext == "htm" ) ||
					( $ext == "html" ) || 
				   	(
						(substr($file, -3,1) != ".") &&
					   	(substr($file, -4,1) != ".") &&
					   	(substr($file, -5,1) != ".") &&
					   	(substr($file, -6,1) != ".") 
				  	) 
				)
				{
					//This is not a file, so get title
					$title = Dap_FileResource::getPageTitle( trim(Dap_Config::get('SITE_URL_DAP'),"/") . $file);
					$dap_dbh = null;
					$dap_dbh = Dap_Connection::getConnection();
				}

				//logToFile("Adding page: $title"); 
				$sql = "insert into dap_file_resources 
							(url, name) 
						values
							(:file, :title)";
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':file', $file, PDO::PARAM_STR);
				$stmt->bindParam(':title', $title, PDO::PARAM_STR);
				$stmt->execute();						
				$resource_id = $dap_dbh->lastInsertId();
			}
			$stmt = null;

			//Now insert resource into products_resources
			
			if ($credits_assigned != "") {
				logToFile(" credits_assgned=".$credits_assigned); 
				$sql = "insert into 
					dap_products_resources_jn
						(product_id, resource_id, credits_assigned, resource_type)
					values (:productId, :resource_id, :credits_assigned, 'F')";		
					
					$stmt = $dap_dbh->prepare($sql);
					$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
					$stmt->bindParam(':resource_id', $resource_id, PDO::PARAM_INT);
					$stmt->bindParam(':credits_assigned', $credits_assigned, PDO::PARAM_INT);
			}
			else {
				$sql = "insert into 
					dap_products_resources_jn
						(product_id, resource_id, start_day, resource_type)
					values
						(:productId, :resource_id, :start_day, 'F')";
					$stmt = $dap_dbh->prepare($sql);
					$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
					$stmt->bindParam(':resource_id', $resource_id, PDO::PARAM_INT);
					$stmt->bindParam(':start_day', $start_day, PDO::PARAM_INT);
			}
			
			$stmt->execute();
			
			$stmt = null;
			$dap_dbh = null;
			return "<span class='BodyTextRed'><b>SUCCESS: '" . $file . "' has been added</b></span><br/>";
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}
	
	public static function getPageTitle($url) {
		//logToFile("In getPageTitle"); 
		$title = "";
		try {
			if( ($fp = @fopen( $url, 'r' )) !== false ) {
				$content = "";
				while( !feof( $fp ) ) {
					$buffer = trim( fgets( $fp, 4096 ) );
					$content .= $buffer;
				}
				
				$start = "<title>";
				$end = "<\/title>";
				
				if ($content != "") {
					preg_match( "/$start(.*)$end/s", $content, $match );
					$title = strip_tags($match[1]);
				}
			}
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
		}
		return $title;
	}

/*	public static function removeFileResourceFromProduct($productId, $resourceId) {
		//Remove Resource from Product
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			$resource = Dap_FileResource::loadFileResource($productId, $resourceId);
			$auto_drip_id=$resource["id"];
			logToFile("Dap_FileResource.class.php: ID=". $resource["id"],LOG_DEBUG_DAP);
			logToFile("Dap_FileResource.class.php: productId=". $productId,LOG_DEBUG_DAP);
			logToFile("Dap_FileResource.class.php: resourceId=". $resourceId,LOG_DEBUG_DAP);
			
			$dap_dbh->beginTransaction(); //begin the transaction
			if ($auto_drip_id > 0) {
			  logToFile("Dap_FileResource.class.php: delete the automated email set for this conten: auto_drip_id=". $auto_drip_id,LOG_DEBUG_DAP);	
			  
			}
			
			//First, remove Product<->Resource association
			$sql = "DELETE FROM 
						dap_products_resources_jn
					WHERE 
						product_id = :productId AND
						resource_id = :resourceId AND
						resource_type = 'F'";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':resourceId', $resourceId, PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->execute();	
			
			// delete any automated autoresponders associated with this resource
			
			
			
			//Before deleting from dap_file_resources, check if this resource is part of more than 1 product
			$sql = "select 
						*
					from
						dap_products_resources_jn 
					where
						resource_id = :resourceId and
						resource_type = 'F' and
						product_id <> :productId
					";
			
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':resourceId', $resourceId, PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->execute();

			if ( $stmt->rowCount() > 0 ) {
				//Resource is part of more than 1 product - so do not delete the resource itself
				$dap_dbh->commit(); //commit the transaction
				return "<b>WARNING: File has been successfully removed from this product. <br/>But it still remains as part of one or more other products</b><br/><br/>";
			}

			//This resource is part of just one product - so delete the file resource too
			$sql = "delete from dap_file_resources where id = :resourceId";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':resourceId', $resourceId, PDO::PARAM_INT);
			$stmt->execute();	

			$dap_dbh->commit(); //commit the transaction
			$dap_dbh = null;

			return INFO_FILE_DELETED;
		} catch (PDOException $e) {
			$dap_dbh->rollback();
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			$dap_dbh->rollback();
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}
	*/
	public static function removeFileResourceFromProduct($productId, $resourceId) {
		//Remove Resource from Product
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			$resource = Dap_FileResource::loadFileResource($productId, $resourceId,"","obj");
			$auto_drip_id=$resource["ID"];
			logToFile("Dap_FileResource.class.php: removeFileResourceFromProduct.  ID=". $resource["ID"],LOG_DEBUG_DAP);
			logToFile("Dap_FileResource.class.php: removeFileResourceFromProduct. productId=". $productId,LOG_DEBUG_DAP);
			logToFile("Dap_FileResource.class.php: removeFileResourceFromProduct. resourceId=". $resourceId,LOG_DEBUG_DAP);
			
			if ($auto_drip_id > 0) {
			  logToFile("Dap_FileResource.class.php: delete the automated email set for this conten: auto_drip_id=". $auto_drip_id,LOG_DEBUG_DAP);	
			  $response = Dap_EmailResource::removeAutomatedEmailResourceFromProduct($productId, $auto_drip_id);
			  logToFile("Dap_FileResource.class.php: deleted the automated email set for this content: response=". $response,LOG_DEBUG_DAP);	
			}
			else {
			  	logToFile("Dap_FileResource.class.php: No auomated email to delete",LOG_DEBUG_DAP);
			}
			
			$dap_dbh->beginTransaction(); //begin the transaction
	
			
			//First, remove Product<->Resource association
			$sql = "DELETE FROM 
						dap_products_resources_jn
					WHERE 
						product_id = :productId AND
						resource_id = :resourceId AND
						resource_type = 'F'";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':resourceId', $resourceId, PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->execute();	
			
			// delete any automated autoresponders associated with this resource
			
			
			
			//Before deleting from dap_file_resources, check if this resource is part of more than 1 product
			$sql = "select 
						*
					from
						dap_products_resources_jn 
					where
						resource_id = :resourceId and
						resource_type = 'F' and
						product_id <> :productId
					";
			
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':resourceId', $resourceId, PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->execute();

			if ( $stmt->rowCount() > 0 ) {
				//Resource is part of more than 1 product - so do not delete the resource itself
				$dap_dbh->commit(); //commit the transaction
				return "<b>WARNING: File has been successfully removed from this product. <br/>But it still remains as part of one or more other products</b><br/><br/>";
			}

			//This resource is part of just one product - so delete the file resource too
			$sql = "delete from dap_file_resources where id = :resourceId";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':resourceId', $resourceId, PDO::PARAM_INT);
			$stmt->execute();	

			$dap_dbh->commit(); //commit the transaction
			$dap_dbh = null;

			return "<span class='BodyTextRed'><b>".INFO_FILE_DELETED."</b></span><br/>";
		} catch (PDOException $e) {
			$dap_dbh->rollback();
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			$dap_dbh->rollback();
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}

	public static function removeAllFileResourcesFromProduct($productId) {
		//Remove Resource from Product
		try {
			$dap_dbh = Dap_Connection::getConnection();
		
			//First, remove Product<->Resource association
			$sql = "select 
						resource_id
					from 
						dap_products_resources_jn
					where 
						product_id = :productId AND
						resource_type = 'F'";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->execute();
			
			while ($row = $stmt->fetch()) {
				$resource_id = $row["resource_id"];
				Dap_FileResource::removeFileResourceFromProduct($productId, $resource_id);				
			}		
			
			$stmt = null;
			$row = null;
			$dap_dbh = null;
			
			return "<span class='BodyTextRed'><b>".INFO_FILES_DELETED."</b></span><br/>";
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}
	
	public static function displayCSErrorPage($resourceURL) {
		//Remove Resource from Product
		try {
			$dap_dbh = Dap_Connection::getConnection();
		
			//First, remove Product<->Resource association
			$sql = "select
				p.id as id,
				p.self_service_allowed as cs
			from
				dap_products p,
				dap_products_resources_jn prj,
				dap_file_resources fr
				where
				fr.url =:requesturl and
				prj.resource_id = fr.id and
				prj.resource_type = 'F' and
				p.id = prj.product_id and
				p.status = 'A'";
			
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':requesturl', $resourceURL, PDO::PARAM_STR);
			$stmt->execute();
			
			$productArray = array();
			
			while ($obj = $stmt->fetch()) {
				
				$productArray[] = $obj;
				//logToFile("Dap_FileResource.class.php: displayCSErrorPage=". $productArray[0]["id"],LOG_DEBUG_DAP);
			}
			
			return $productArray;
			$stmt = null;
			$row = null;
			$dap_dbh = null;
			
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
		
		return NULL;
	}
	
	

}
?>