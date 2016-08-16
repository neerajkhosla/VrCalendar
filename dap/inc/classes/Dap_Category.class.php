<?php

class Dap_Category {
   var $id;
   var $code;
   var $description;
	
	function getId() {
		return $this->id;
	}
	function setId($o) {
		$this->id = $o;
	}

	function getCode() {
		return $this->code;
	}
	function setCode($o) {
		$this->code = $o;
	}

	function getDescription() {
		return $this->description;
	}
	function setDescription($o) {
		$this->description = $o;
	}

	
	public function create() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$price = "0.00"; //We don't care about price at this time

			$sql = "insert into dap_category
						(code, description)
					values
						(:code, :description)";

			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->bindParam(':code', $this->getCode(), PDO::PARAM_STR);
			$stmt->bindParam(':description', $this->getDescription(), PDO::PARAM_STR);
		
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

	public function update() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			/*logToFile("Dap_category.class.php: code=". $this->getCode(),LOG_DEBUG_DAP);
			logToFile("Dap_category.class.php: description=". $this->getDescription(),LOG_DEBUG_DAP);
			logToFile("Dap_category.class.php: id=". $this->getId(),LOG_DEBUG_DAP);
				*/
				$sql = "update dap_category set
							code = :code,
							description = :description
						where id = :categoryId";

			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':code', $this->getCode(), PDO::PARAM_STR);
			$stmt->bindParam(':description', $this->getDescription(), PDO::PARAM_STR);
			$stmt->bindParam(':categoryId', $this->getId(), PDO::PARAM_STR);

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
	

	public static function loadCategory($categoryId) {
		$dap_dbh = Dap_Connection::getConnection();
		$category = null;

		//Load category details from database
		$sql = "select *
			from
				dap_category
			where
				id = :categoryId";

		$stmt = $dap_dbh->prepare($sql);
		$stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_STR);
		$stmt->execute();

		//echo "sql: $sql<br>"; exit;
		//$result = mysql_query($sql);
		//echo "rows returned: " . mysql_num_rows($result) . "<br>";

			
		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$category = new Dap_Category();
			$category->setId( $row["id"] );
			$category->setCode( stripslashes($row["code"]) );
			$category->setDescription( stripslashes($row["description"]) );
		}

		return $category;
	}
	
	public static function isExists($categoryId) {
		$dap_dbh = Dap_Connection::getConnection();

		//Load category details from database
		$sql = "select id
			from
				dap_category
			where
				id = :categoryId";

		$stmt = $dap_dbh->prepare($sql);
		$stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_STR);
		$stmt->execute();

		//echo "sql: $sql<br>"; exit;
		//$result = mysql_query($sql);
		//echo "rows returned: " . mysql_num_rows($result) . "<br>";

		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		  return TRUE;
		}

		return FALSE;
	}	

	public static function loadCategoryByCode($categoryCode) {
		$dap_dbh = Dap_Connection::getConnection();

		//Load category details from database
		$sql = "select *
			from
				dap_category
			where
				code = :categoryCode";

		$stmt = $dap_dbh->prepare($sql);
		$stmt->bindParam(':categoryCode', $categoryCode, PDO::PARAM_STR);
		$stmt->execute();

		//echo "sql: $sql<br>"; exit;
		//$result = mysql_query($sql);
		//echo "rows returned: " . mysql_num_rows($result) . "<br>";

		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$category = new Dap_Category();
			$category->setId( $row["id"] );
			$category->setCode( stripslashes($row["code"]) );
			$category->setDescription( stripslashes($row["description"]) );
			$CategoryList[] = $category;
			return $CategoryList;
		}

		return;
	}

	//Load category matching filter criteria
	public static function loadAllCategories($categoryFilter) {
		$CategoryList = array();

		if(trim($categoryFilter) == "") {
			$sql = "select * from dap_category";
		} else {
			$sql = "select * from dap_category
					where
						(id = '$categoryFilter' or
						code like '%$categoryFilter%' or
						description like '%$categoryFilter%')";
		}

		try {
			$dap_dbh = Dap_Connection::getConnection();
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();
			$found="N";
			while ($row = $stmt->fetch()) {
				$category = new Dap_Category();
				$found="Y";
				$category->setId( $row["id"] );
				$category->setCode( stripslashes($row["code"]) );
				$category->setDescription( stripslashes($row["description"]) );
				//logToFile("Dap_Category.class: found=" . $found);			
				$CategoryList[] = $category;
			}
			 if($found=="N") {
			  //logToFile("Dap_Category.class: found=" . $found);
			  return NULL;
		    } 
			return $CategoryList;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	public static function deleteCategory($id) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$dap_dbh->beginTransaction(); //begin the transaction
			$response = "SUCCESS! Category $categoryId was deleted from the database";
			$count = 0;

			//Check if there are any products associated with this category
/*			$sql = "select count(*) as count from dap_users_category_jn where category_id = :id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();

			if ($row = $stmt->fetch()) {
				$count = $row["count"];
				if($count > 0) {
					return "There are Users associated with this Category. <br/>Remove them first before you can delete this Category.";
				}
			}
*/
	
			$sql = "delete from dap_category where id = :id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();

			$dap_dbh->commit(); //commit the transaction
			$dap_dbh = null;

			return $response;

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

	public static function getMinCId() {
		$dap_dbh = Dap_Connection::getConnection();
		$id = 0;
		
		$sql = "select 
					min(id) as id
				from
					dap_category";
					
		$stmt = $dap_dbh->prepare($sql);
		$stmt->execute();

		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$id = $row["id"];
		}

		return $id;
	}

}
?>