<?php

class Dap_EmailResource extends Dap_Resource {

	var $id;
	var $subject;
	var $message;
	var $attachment;
	var $attachmentArray = array();
	var $sendTo3rdParty;
	var $thirdPartyEmailId;
	
	function Dap_EmailResource($resourceId = "") {
        $this->setId($resourceId);
    }

	function getId() {
	        return $this->id;
	}
	
	function setId($o) {
	      $this->id = $o;
	}
	
	function getSubject() {
	        return $this->subject;
	}
	
	function setSubject($o) {
	      $this->subject = $o;
	}
	
	function getMessage() {
	        return $this->message;
	}
	
	function setMessage($o) {
	      $this->message = $o;
	}
	
	function getAttachment() {
	        return $this->attachment;
	}
	
	function setAttachment($o) {
	      $this->attachment = $o;
	}
	
	function getAttachmentArray() {
	        return $this->attachmentArray;
	}
	
	function setAttachmentArray($o) {
	      $this->attachmentArray = $o;
	}
	
	function getSendTo3rdParty() {
	        return $this->sendTo3rdParty;
	}
	
	function setSendTo3rdParty($o) {
	      $this->sendTo3rdParty = $o;
	}
	
	function getThirdPartyEmailId() {
	        return $this->thirdPartyEmailId;
	}
	
	function setThirdPartyEmailId($o) {
	      $this->thirdPartyEmailId = $o;
	}
	
	
	
	public static function loadAllEmailResources() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
		
			$sql = "select 
						*
					from 
						dap_email_resources
					order by 
						id asc";

			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();	

			$EmailResourceList = array();
			while ($row = $stmt->fetch()) {
				$emailResource = new Dap_EmailResource();
				
				$emailResource->setId($row["id"]);
				$emailResource->setSubject($row["subject"]);
				$emailResource->setMessage($row["message"]);
				$emailResource->setSendTo3rdParty($row["sendTo3rdParty"]);
				$emailResource->setThirdPartyEmailId($row["thirdPartyEmailId"]);

				$EmailResourceList[] = $emailResource;
			}
			
			return $EmailResourceList;
			
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			return $e->getMessage();
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			return $e->getMessage();
		}
	}
	

	public static function addEmailResourceToProduct($productId, $emailResourceId) {
		//Add given resource to Product
		try {
			$dap_dbh = Dap_Connection::getConnection();

			/*
				First, check to see if this resource is already
				a part of this product. If yes, then return error.
			*/
			
			/*
			$sql = "SELECT 
						*
					FROM 
						dap_email_resources er, 
						dap_products_resources_jn prj
					WHERE 
						er.id = :emailResourceId AND
						er.id = prj.resource_id AND
						prj.product_id = :productId AND
						prj.resource_type = 'E'";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->bindParam(':emailResourceId', $emailResourceId, PDO::PARAM_INT);
			$stmt->execute();
			if ( $stmt->rowCount() > 0 ) {
				return "<b>" . ERROR_DB_RESOURCE_ALREADY_ASSIGNED . "</b><br/>";
			}
			*/
			$stmt = null;
			

			//Insert resource into products_resources
			$sql = "insert into dap_products_resources_jn
						(product_id, resource_id, resource_type)
						values(:productId, :resource_id, 'E')";		
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->bindParam(':resource_id', $emailResourceId, PDO::PARAM_INT);
			$stmt->execute();
			
			$stmt = null;
			$dap_dbh = null;
			return "<span class='BodyTextRed'><b>SUCCESS: Email has been added to this product</b></span><br/>";
			
		} catch (PDOException $e) {
			logToFile("addEmailResourceToProduct: " . $e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile("addEmailResourceToProduct: " . $e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}

	public static function addEmailResourceToProductAutomatedDrip($productId, $emailResourceId) {
		//Add given resource to Product
		try {
			$dap_dbh = Dap_Connection::getConnection();

			/*
				First, check to see if this resource is already
				a part of this product. If yes, then return error.
			*/
			
			/*
			$sql = "SELECT 
						*
					FROM 
						dap_email_resources er, 
						dap_products_resources_jn prj
					WHERE 
						er.id = :emailResourceId AND
						er.id = prj.resource_id AND
						prj.product_id = :productId AND
						prj.resource_type = 'E'";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->bindParam(':emailResourceId', $emailResourceId, PDO::PARAM_INT);
			$stmt->execute();
			if ( $stmt->rowCount() > 0 ) {
				return "<b>" . ERROR_DB_RESOURCE_ALREADY_ASSIGNED . "</b><br/>";
			}
			*/
			$stmt = null;
			

			//Insert resource into products_resources
			$sql = "insert into dap_products_resources_jn
						(product_id, resource_id, resource_type)
						values(:productId, :resource_id, 'E')";		
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->bindParam(':resource_id', $emailResourceId, PDO::PARAM_INT);
			$stmt->execute();
			
			$new_id = $dap_dbh->lastInsertId();
			
			$stmt = null;
			$dap_dbh = null;
			return $new_id;
			
		} catch (PDOException $e) {
			logToFile("addEmailResourceToProduct: " . $e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile("addEmailResourceToProduct: " . $e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}

	public static function removeAutomatedEmailResourceFromProduct($productId, $auto_drip_id) { 

		/*
			This works differently from removeFileResourceFromProduct().
			In this case, only the Product<=>Resource relationship is 
			deleted. The Email resource is NEVER deleted, even if the 
			resource is assigned to just one Product.
		*/
		
		//Remove Resource from Product
		try {
			$dap_dbh = Dap_Connection::getConnection();
		
			//Remove Product<->Resource association
			$sql = "DELETE FROM
						dap_products_resources_jn
					WHERE 
						product_id = :productId AND
						file_resource_id = :auto_drip_id AND
						resource_type = 'E'
					";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':auto_drip_id', $auto_drip_id, PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
		
			$stmt->execute();	
			
			$stmt = null;
			$dap_dbh = null;

			return 0;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}
	
	public static function updateEmailProductRel($productId, $resourceId, $product_resource_status, $start_day, $start_date, $is_free, $prjId, $file_resource_id="") {
		//Update resouce details
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			logToFile("updateEmailProductRel: file_resource_id: $file_resource_id",LOG_DEBUG_DAP);
			logToFile("updateEmailProductRel: prjId: $prjId",LOG_DEBUG_DAP);
			
			//Update products_resources table
			if($start_day == "") $start_day = null;
			if($start_date == "") $start_date = null;
			if($product_resource_status == "") $product_resource_status = "I";
			if($is_free == "") $is_free = "N";
			
			if($file_resource_id == "") {
			$sql = "update
						dap_products_resources_jn
					set 
						start_day = :start_day,
						start_date = STR_TO_DATE(:start_date, '%m-%d-%Y'),
						status = :product_resource_status,
						is_free = :is_free
					where
					   id = :prjId
					";
					$stmt = $dap_dbh->prepare($sql);
			}
			else {
			$sql = "update
						dap_products_resources_jn
					set 
						start_day = :start_day,
						start_date = STR_TO_DATE(:start_date, '%m-%d-%Y'),
						status = :product_resource_status,
						is_free = :is_free,
						file_resource_id = :file_resource_id
					where
						id = :prjId
					";	
					$stmt = $dap_dbh->prepare($sql);
					logToFile("updateEmailProductRel: SET file_resource_id: $file_resource_id",LOG_DEBUG_DAP);
					$stmt->bindParam(':file_resource_id', $file_resource_id, PDO::PARAM_STR);
			}
						//product_id = :productId and
						//resource_id = :resourceId and
						//resource_type = 'E' and 
						
			
			$stmt->bindParam(':start_day', $start_day, PDO::PARAM_STR);
			$stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
			$stmt->bindParam(':product_resource_status', $product_resource_status, PDO::PARAM_STR);
			//$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			//$stmt->bindParam(':resourceId', $resourceId, PDO::PARAM_INT);
			$stmt->bindParam(':is_free', $is_free, PDO::PARAM_STR);
			$stmt->bindParam(':prjId', $prjId, PDO::PARAM_STR);
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


	public static function loadEmailProductRel($productId, $resourceId, $prjId) {
		try {
			//Load resource details from database
			$dap_dbh = Dap_Connection::getConnection();
	
			$sql = "select 
						prj.id as prjId,
						prj.is_free,
						prj.start_day,
						DATE_FORMAT(prj.start_date, '%m-%d-%Y') as start_date, 
						prj.status as product_resource_status,
						prj.resource_id as resource_id
					from
						dap_email_resources er, 
						dap_products_resources_jn prj
					where
						er.id = :resourceId and
						er.id = prj.resource_id and
						prj.product_id = :productId and
						prj.resource_type = 'E' and
						prj.id = :prjId";

			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':resourceId', $resourceId, PDO::PARAM_STR);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_STR);		
			$stmt->bindParam(':prjId', $prjId, PDO::PARAM_INT);		
			$stmt->execute();
			
			$resourceArray = array();
			$found=false;
			if ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
				$resourceArray[] = $obj;
				$found=true;
			}
			
			if($found==false) {
				$sql = "select 
						prj.id as prjId,
						prj.is_free,
						prj.start_day,
						DATE_FORMAT(prj.start_date, '%m-%d-%Y') as start_date, 
						prj.status as product_resource_status,
						prj.resource_id as resource_id
					from
						dap_products_resources_jn prj
					where
						prj.product_id = :productId and
						prj.resource_type = 'E' and
						prj.id = :prjId";

			  $stmt = $dap_dbh->prepare($sql);
			  
			  $stmt->bindParam(':productId', $productId, PDO::PARAM_STR);		
			  $stmt->bindParam(':prjId', $prjId, PDO::PARAM_INT);		
			  $stmt->execute();
			  
			  $resourceArray = array();
			
			  if ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
				  $resourceArray[] = $obj;
			  }
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


	public function load() {
		try {

			//Load resource details from database
			$dap_dbh = Dap_Connection::getConnection();
	
			$sql = "select 
						subject,
						message,
						attachment,
						sendTo3rdParty,
						thirdPartyEmailId
					from
						dap_email_resources
					where
						id = :resourceId";

			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':resourceId', $this->getId(), PDO::PARAM_INT);
			$stmt->execute();
			
			if ($row = $stmt->fetch()) {
				$this->setSubject(stripslashes($row["subject"])) ;
				$this->setMessage(stripslashes($row["message"])) ;
				$this->setAttachment(stripslashes($row["attachment"])) ;
				$this->setSendTo3rdParty(stripslashes($row["sendTo3rdParty"]));
				$this->setThirdPartyEmailId(stripslashes($row["thirdPartyEmailId"]));
			}
			
			return;
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}

	public function toArray() {
		try {
			$emailResourceArray = array();
			
			$emailResourceArray["id"] = $this->getId();
			$emailResourceArray["subject"] = $this->getSubject();
			$emailResourceArray["message"] = $this->getMessage();
			$emailResourceArray["attachment"] = $this->getAttachment();
			$emailResourceArray["sendTo3rdParty"] = $this->getSendTo3rdParty();
			$emailResourceArray["thirdPartyEmailId"] = $this->getThirdPartyEmailId();
			
			$this->setAttachmentArray(explode("||", $this->getAttachment()));
			$emailResourceArray["attachmentArray"] = $this->getAttachmentArray();

			return $emailResourceArray;
		
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
	
			$sql = "update 
						dap_email_resources
					set
						subject = :subject,
						message = :message,
						attachment = :attachment,
						sendTo3rdParty = :sendTo3rdParty,
						thirdPartyEmailId = :thirdPartyEmailId
					where
						id = :id";

			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $this->getId(), PDO::PARAM_INT);
			$stmt->bindParam(':subject', $this->getSubject(), PDO::PARAM_STR);
			$stmt->bindParam(':message', $this->getMessage(), PDO::PARAM_STR);
			$stmt->bindParam(':attachment', $this->getAttachment(), PDO::PARAM_STR);
			$stmt->bindParam(':sendTo3rdParty', $this->getSendTo3rdParty(), PDO::PARAM_STR);
			$stmt->bindParam(':thirdPartyEmailId', $this->getThirdPartyEmailId(), PDO::PARAM_STR);
			$stmt->execute();

			return;
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	
	public function create() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
	
			$sql = "insert into 
						dap_email_resources
						(subject, message,sendTo3rdParty,thirdPartyEmailId)
					values 
						(:subject, :message, :sendTo3rdParty, :thirdPartyEmailId)";

			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':subject', $this->getSubject(), PDO::PARAM_STR);
			$stmt->bindParam(':message', $this->getMessage(), PDO::PARAM_STR);
			$stmt->bindParam(':sendTo3rdParty', $this->getSendTo3rdParty(), PDO::PARAM_STR);
			$stmt->bindParam(':thirdPartyEmailId', $this->getThirdPartyEmailId(), PDO::PARAM_STR);
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
	

	public static function removeAllEmailResourcesFromProduct($productId) {
		//Remove Resource from Product
		try {
			$dap_dbh = Dap_Connection::getConnection();
		
			//Remove Product<->Resource association
			$sql = "DELETE FROM
						dap_products_resources_jn
					WHERE 
						product_id = :productId and
						resource_type = 'E'
					";
			//logToFile("productId in removeAllEmailResourcesFromProduct: $productId"); 
			//logToFile("sql in removeAllEmailResourcesFromProduct: $sql"); 
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->execute();	
			
			$stmt = null;
			$dap_dbh = null;

			return "<span class='BodyTextRed'><b>".INFO_EMAILS_DELETED."</b></span><br/>";
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}
	
	public static function removeEmailResourceFromProduct($productId, $resourceId, $prjId) {
		/*
			This works differently from removeFileResourceFromProduct().
			In this case, only the Product<=>Resource relationship is 
			deleted. The Email resource is NEVER deleted, even if the 
			resource is assigned to just one Product.
		*/
		
		//Remove Resource from Product
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			if ($resourceId <= -1000)
			logToFile("productId in removeAllEmailResourcesFromProduct: $productId"); 
			logToFile("resourceId in removeAllEmailResourcesFromProduct: $resourceId"); 
			logToFile("prjId in removeAllEmailResourcesFromProduct: $prjId"); 
			
			//Remove Product<->Resource association
			$sql = "DELETE FROM
						dap_products_resources_jn
					WHERE 
						product_id = :productId AND
						resource_id = :resourceId AND
						resource_type = 'E' and
						id = :prjId
					";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':resourceId', $resourceId, PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->bindParam(':prjId', $prjId, PDO::PARAM_INT);
			$stmt->execute();	
			
			logToFile("DELETED SUCCESSFULLY"); 
			
			//Check if this resource is part of more than 1 product
			$sql = "select 
						*
					from
						dap_products_resources_jn 
					where
						resource_id = :resourceId and
						resource_type = 'E' and
						product_id <> :productId
					";
			
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':resourceId', $resourceId, PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->execute();

			if ( $stmt->rowCount() > 0 ) {
				return "<b>WARNING: Email has been successfully removed<br/>from this Product. But it still remains as<br/>part of one or more other products</b><br/>";
			}

			$stmt = null;
			$dap_dbh = null;

			return "<span class='BodyTextRed'><b>".INFO_EMAIL_DELETED."</b></span><br/>";
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}


	/*
		Completely deletes email resource from system as well from 
		all Products that it was a part of 
	*/
	public static function deleteEmailResource($resourceId) { 
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$dap_dbh->beginTransaction(); //begin the transaction
			
			//Remove Product<->Resource association
			$sql = "DELETE FROM
						dap_products_resources_jn
					WHERE 
						resource_id = :resourceId AND
						resource_type = 'E'
					";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':resourceId', $resourceId, PDO::PARAM_INT);
			$stmt->execute();	
			
			$stmt = null;
			
			//Now delete from dap_email_resources
			$sql = "DELETE FROM
						dap_email_resources
					WHERE 
						id = :resourceId
					LIMIT 1
					";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':resourceId', $resourceId, PDO::PARAM_INT);
			$stmt->execute();	
			
			$dap_dbh->commit(); //commit the transaction
			$stmt = null;
			$dap_dbh = null;

			return "SUCCESS! The Email was completely deleted from the system and all DAP Products";
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

}
?>
