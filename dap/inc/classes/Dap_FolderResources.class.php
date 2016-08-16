<?php

class Dap_FolderResources extends Dap_Resource {

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
		
	public static function addFolderResourcesToProduct ($productId, $folder, $start_day=1, $path='/', $recur='N'){
		try {
			$success = 0;
			$filelist = '';
			
			//$siteroot = "C:\\apache2.2\\htdocs";
			//$foldername = $siteroot . $folder;
			
			if ($recur == "N") {
				$docroot = $_SERVER['DOCUMENT_ROOT'];
				$dirpath = $docroot . $path . $folder;
				logToFile("regular dirpath: " . $dirpath, LOG_DEBUG_DAP);   
				$dirHandle = opendir($dirpath);
			}
			else {
				logToFile("folder dirpath: " . $folder, LOG_DEBUG_DAP);   
				$dirpath = $folder;
				$dirHandle = opendir($folder);
			}
			
			while($file = readdir($dirHandle)){
				$origfile=$file;
				$file = $dirpath . "/" . $file;
				logToFile("FILENAME: " . $file, LOG_DEBUG_DAP);   
				logToFile("ORIG FILENAME: " . $origfile, LOG_DEBUG_DAP);   
				if (($origfile != '.') && ($origfile != '..')) {
					if(is_dir($file)){
						$recur = "Y";
						logToFile("folder check true, recursive loop for folder: " . $origfile, LOG_DEBUG_DAP);   
						Dap_FolderResources::addFolderResourcesToProduct($productId, $file, 1, $path, $recur);
						
					}
					else {
					  $filelist = $filelist . "<b>SUCCESS: '" . $file . "' has been added</b><br/>";
					  $ret = Dap_FolderResources::addToProduct($productId, $origfile);
					  logToFile("file added: " . $origfile, LOG_DEBUG_DAP);   
					}
				}
			} 
			closedir($dirHandle);
			return $filelist;
		 } catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}
	
	public static function addToProduct($productId, $file, $start_day=1) {
		//Add given resources to Product
		try {
			$dap_dbh = Dap_Connection::getConnection();			
			/*
				First, check to see if this resource is already
				a part of this product. If yes, then return error.
			*/
			// Open a known directory, and proceed to read its contents
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
						
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':file', $file, PDO::PARAM_STR);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_STR);
			$stmt->execute();
			if ( $stmt->rowCount() > 0 ) {
				return 1;
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
			if ( $stmt->rowCount() > 0 ) {
				//It already exists, so don't add new file resource.
				//Simply use the id of existing resource and add to dap_products_resources_jn
				if ($row = $stmt->fetch()) {
					$resource_id = $row["id"];
				}
			} else {
				//Resource doesn't already exist. So, insert into file_resources
				$sql = "insert into 
							dap_file_resources 
						(url) values(:file)";
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':file', $file, PDO::PARAM_STR);
				$stmt->execute();						
				$resource_id = $dap_dbh->lastInsertId();
			}
			$stmt = null;

			//Now insert resource into products_resources
			
			$sql = "insert into 
					dap_products_resources_jn
						(product_id, resource_id, start_day, resource_type)
					values
						(:productId, :resource_id, :start_day, 'F')";		
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->bindParam(':resource_id', $resource_id, PDO::PARAM_INT);
			$stmt->bindParam(':start_day', $start_day, PDO::PARAM_INT);
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


	public static function removeFolderResourcesFromProduct ($productId, $folder, $recur='N'){
		try {
			$success = 0;
			$filelist = '';
			
			//$siteroot = "C:\\apache2.2\\htdocs";
			//$foldername = $siteroot . $folder;
			
			if ($recur == "N") {
				$docroot = $_SERVER['DOCUMENT_ROOT'];
				$dirpath = $docroot . $path . $folder;
				logToFile("regular dirpath: " . $dirpath, LOG_DEBUG_DAP);   
				$dirHandle = opendir($dirpath);
			}
			else {
				logToFile("folder dirpath: " . $folder, LOG_DEBUG_DAP);   
				$dirpath = $folder;
				$dirHandle = opendir($folder);
			}
			
			while($file = readdir($dirHandle)){
				$origfile=$file;
				$file = $dirpath . "/" . $file;
				logToFile("FILENAME: " . $file, LOG_DEBUG_DAP);   
				logToFile("ORIG FILENAME: " . $origfile, LOG_DEBUG_DAP);   
				if (($origfile != '.') && ($origfile != '..')) {
					if(is_dir($file)){
						$recur = "Y";
						logToFile("folder check true, recursive loop for folder: " . $origfile, LOG_DEBUG_DAP);   
						Dap_FolderResources::removeFolderResourcesFromProduct($productId, $file, $recur);
						
					}
					else {
					  $ret = Dap_FolderResources::removeFromProduct($productId, $origfile);
					  logToFile("file removed: " . $origfile, LOG_DEBUG_DAP);   
					}
				}
			} 
			closedir($dirHandle);
			$filelist = "<b>SUCCESS: all resources under the selected folder have been unprotected</b><br/>";
			return $filelist;
		 } catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}
	
	public static function removeFromProduct($productId, $file) {
		//Remove Resource from Product
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$dap_dbh->beginTransaction(); //begin the transaction
			
			$resourceId = 0;
			
			$sql = "SELECT 
						id
					FROM 
						dap_file_resources
					WHERE 
						url = :file";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':file', $file, PDO::PARAM_STR);
			$stmt->execute();
			if ( $stmt->rowCount() == 0 ) {
				//does not exist, so dont bother deleting
				return 1;
			} else {
				//Resource exists. So, delete from product_resources
				$row = $stmt->fetch();
				$resourceId = $row["id"];
				
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
			}
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