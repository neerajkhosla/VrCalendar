<?php

$cwd = getcwd();

chdir(VBFORUMPATH);

// this is common to vb 4 and 5
require_once(VBFORUMPATH.'/includes/init.php'); // includes class_core.php

//these are not in vb 5
if(file_exists(VBFORUMPATH.'/includes/class_dm.php')) 
	include_once (VBFORUMPATH.'/includes/class_dm.php'); 
if(file_exists(VBFORUMPATH.'/includes/class_dm_user.php')) 
	include_once (VBFORUMPATH.'/includes/class_dm_user.php'); 

// these are common to vb 4 and 5
require_once(VBFORUMPATH.'/includes/functions.php'); // vbsetcookie etc.
require_once(VBFORUMPATH.'/includes/functions_login.php'); // process login/logout

class Dap_VBForum extends vB_DataManager_User {

	var $id;
	var $source_operation;
	var $source_product_id;
	var $target_operation;
	var $target_usergroup_id;
	var $userdm;

	function Dap_VBForum() // constructor
	{
		global $vbulletin;
		$this->userdm =& datamanager_init('User', $vbulletin, ERRTYPE_ARRAY);
	}
   
	function getId() {
		return $this->id;
	}
	function setId($o) {
		$this->id = $o;
	}
		
	function getSource_operation()  {
	       return $this->source_operation;
	}
	function setSource_operation($o) {
	      $this->source_operation = $o;
	}

	function getSource_product_id()  {
	       return $this->source_product_id;
	}
	function setSource_product_id($o) {
	      $this->source_product_id = $o;
	}	

	function getTarget_operation()  {
	       return $this->target_operation;
	}
	function setTarget_operation($o) {
	      $this->target_operation = $o;
	}	
	
	function getTarget_usergroup_id()  {
	       return $this->target_usergroup_id;
	}
	function setTarget_usergroup_id($o) {
	      $this->target_usergroup_id = $o;
	}		
	
	function getComment()  {
	       return $this->comment;
	}
	function setComment($o) {
	      $this->comment = $o;
	}		
	
	public static function fetch_usergroup_name($usergroupid)
	{
	   global $vbulletin;
	   
//	   logToFile("in fetch usergroups",LOG_DEBUG_DAP);

	   $usergroups = $vbulletin->db->query_read("SELECT usergroupid,title,description FROM "  . TABLE_PREFIX . "usergroup where usergroupid=".$usergroupid);
	
		$result = array();
		$rowcount=0;
//		logToFile("in fetch usergroups",LOG_DEBUG_DAP);
			
		// Loop through all results:
		while ($usergroup = $vbulletin->db->fetch_array($usergroups))
		{
			return $usergroup['title'];
		}	
	   
	   
	}
	
	public static function fetch_usergroups()
	{
	   global $vbulletin;
	   
//	   logToFile("in fetch usergroups",LOG_DEBUG_DAP);

	   $usergroups = $vbulletin->db->query_read("SELECT usergroupid,title,description FROM "  . TABLE_PREFIX . "usergroup");
	
		$result = array();
		$rowcount=0;
//		logToFile("in fetch usergroups",LOG_DEBUG_DAP);
		
		$UserGroupListHTML  = "";
		$UserGroupListHTML .= "<option value=\"" . "0" . "\">" . "Select A Forum UserGroup" . "</option>\n";		  
			
		// Loop through all results:
		while ($usergroup = $vbulletin->db->fetch_array($usergroups))
		{
		/*	logToFile ("usergroupID = " . $usergroup['usergroupid']);
			logToFile ("title = " . $usergroup['title']);
			logToFile ("description = " . $usergroup['description']);*/

			$UserGroupListHTML .= "<option value=\"" . $usergroup['usergroupid'] . "\">" . $usergroup['title'] . "</option>\n";
			$rowcount++;
		}	
	   
	   	return $UserGroupListHTML;
	}


	public static function loadProductForumMappingRule () {
		try {
		
			$dap_dbh = Dap_Connection::getConnection();
			$productForumArray = array();
	
			$sql = "select
						id,
						source_operation,
						source_product_id,
						target_operation,
						target_usergroup_id,
						title
					from 
						dap_productforum
					where source_product_id in (select id from dap_products)";
	
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();	
			
			while ($obj = $stmt->fetch()) {
				$productForumArray[] = $obj;
			}
	
			return $productForumArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}

	public static function loadProductForumMappingDataByProductId ($productId, $sourceOperation = "") {
		try {
		
			$dap_dbh = Dap_Connection::getConnection();
			$productForumArray = array();
			
			logToFile("loadProductForumMappingDataByProductId . source_operation=" . $sourceOperation,LOG_INFO_DAP);
			
			if ($sourceOperation == "") {
				$sql = "select DISTINCT 
						id,
						source_operation,
						source_product_id,
						target_operation,
						target_usergroup_id,
						title
					from 
						dap_productforum
					where source_product_id = :source_product_id
					order by target_usergroup_id ASC";
					//logToFile($sql,LOG_DEBUG_DAP);
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':source_product_id', $productId, PDO::PARAM_INT);
				$stmt->execute();	
				while ($obj = $stmt->fetch()) {
					$productForumArray[] = $obj;
				}
		
			}
			else {
				$sql = "select
						id,
						source_operation,
						source_product_id,
						target_operation,
						target_usergroup_id,
						title
					from 
						dap_productforum
					where 
					source_product_id = :source_product_id and
					source_operation = :source_operation 
					order by target_usergroup_id ASC";
					
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':source_product_id', $productId, PDO::PARAM_INT);
				$stmt->bindParam(':source_operation', $sourceOperation, PDO::PARAM_STR);
				$stmt->execute();	
				while ($obj = $stmt->fetch()) {
					$productForumArray[] = $obj;
				}
		
			}
					
			return $productForumArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	
	
	public static function loadProductForumMappingDataAndPriorityByProductId($productId, $sourceOperation = "") {
		try {
		
			$dap_dbh = Dap_Connection::getConnection();
			$productForumArray = array();
			
			logToFile("loadProductForumMappingDataByProductId . source_operation=" . $sourceOperation,LOG_INFO_DAP);
			
			if ($sourceOperation == "") {
				$sql = "SELECT 
						dap_productforum.id,
						source_operation,
						source_product_id,
						target_operation,
						target_usergroup_id,
						title,
						priority
						FROM dap_productforum
						LEFT OUTER JOIN dap_forum_priority ON dap_productforum.target_usergroup_id = dap_forum_priority.target_usergroupId
						WHERE dap_productforum.source_product_id = :source_product_id
						ORDER BY dap_forum_priority.priority ASC, target_usergroup_id DESC";
					//logToFile($sql,LOG_DEBUG_DAP);
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':source_product_id', $productId, PDO::PARAM_INT);
				$stmt->execute();	
				while ($obj = $stmt->fetch()) {
					
					
					$productForumArray[] = $obj;
				}
		
			}
			else {
				$sql = "SELECT 
						dap_productforum.id,
						source_operation,
						source_product_id,
						target_operation,
						target_usergroup_id,
						title,
						priority
						FROM dap_productforum
						LEFT OUTER JOIN dap_forum_priority ON dap_productforum.target_usergroup_id = dap_forum_priority.target_usergroupId
						WHERE dap_productforum.source_product_id = :source_product_id and
						dap_productforum.source_operation = :source_operation 
						ORDER BY dap_forum_priority.priority ASC, target_usergroup_id DESC
						";
					
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':source_product_id', $productId, PDO::PARAM_INT);
				$stmt->bindParam(':source_operation', $sourceOperation, PDO::PARAM_STR);
				$stmt->execute();	
				while ($obj = $stmt->fetch()) {
					$productForumArray[] = $obj;
				}
		
			}
					
			return $productForumArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	
	//Create
	public static function createProductForumMappingRules($sourceOperation, $sourceProductId, $targetOperation, $targetUsergroupId, $title) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			$sql = "insert into dap_productforum
						(source_operation, source_product_id, target_operation, target_usergroup_id, title)
					values
						(:source_operation, :source_product_id, :target_operation, :target_usergroup_id, :title)";
					
			/*logToFile($sql,LOG_INFO_DAP);
			logToFile("source_operation=" . $sourceOperation,LOG_INFO_DAP);
			logToFile("source_product_id=" . $sourceProductId,LOG_INFO_DAP);
			logToFile("target_operation=" . $targetOperation,LOG_INFO_DAP);
			logToFile("target_usergroup_id=" . $targetUsergroupId,LOG_INFO_DAP);
			logToFile("title=" . $title,LOG_INFO_DAP);*/
			
			$stmt = $dap_dbh->prepare($sql);
			$comment="";
			
			$stmt->bindParam(':source_operation', $sourceOperation, PDO::PARAM_STR);
			$stmt->bindParam(':source_product_id', $sourceProductId, PDO::PARAM_INT);
			$stmt->bindParam(':target_operation', $targetOperation, PDO::PARAM_STR);
			$stmt->bindParam(':target_usergroup_id', $targetUsergroupId, PDO::PARAM_INT);
			$stmt->bindParam(':title', $title, PDO::PARAM_STR);
			$stmt->execute();
			
			$lastid = $dap_dbh->lastInsertId();
			logToFile("lastid: $lastid"); 
			$stmt = null;
			
			$dap_dbh = null;
			return $lastid;
		} catch (PDOException $e) {
			if(stristr($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: ") == TRUE) {
				logToFile($e->getMessage(),LOG_INFO_DAP);
				return 0;
			}
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
		
	}

	//Delete
	public static function removeRule($id) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			//delete from usersproducts table
			$sql = "delete from  
					dap_productforum
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
	
	//Delete
	public static function removeRules($productId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			//delete from usersproducts table
			$sql = "delete from  
					dap_productforum
					where source_product_id =:source_product_id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':source_product_id', $productId, PDO::PARAM_INT);
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
chdir($cwd);
?>