<?php

class Dap_BulkEmail extends Dap_Resource {

	public static function bulkEmailInsert($actionType,$payload) {
		//  SEQ/BE-COLLECTIONCODE/SUBJECT||BODY||ATTACHMENTS(multiple attachments separated by commas)
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$key = mktime();
			//Insert into dap_mass_actions

			$sql = "insert into dap_mass_actions
						(actionType, actionKey, payload, status)
						values
						(:actionType, :key, :payload, 'NEW')";		
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':actionType', $actionType, PDO::PARAM_STR);
			$stmt->bindParam(':key', $key, PDO::PARAM_STR);
			$stmt->bindParam(':payload', $payload, PDO::PARAM_STR);
			$stmt->execute();
			
			$stmt = null;
			$dap_dbh = null;
			return $key;
		} catch (PDOException $e) {
			logToFile("bulkEmailInsertCollectionCode: " . $e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile("bulkEmailInsertCollectionCode: " . $e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}
}
?>
