<?php

class Dap_Reports extends Dap_Base {

	public static function loadUniqueMembers($start_date, $end_date) {
		try {
			logToFile("Start, end: $start_date, $end_date"); 
			$dap_dbh = Dap_Connection::getConnection();
			if($start_date == "") $start_date = date("m-d-Y");
			if($end_date == "") $end_date = date("m-d-Y");
			$stmt = null;
			$sql = "select id, name from dap_products";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();
			$productsArray = array();
			$i=0;
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$productId = $row["id"];
				$productName = $row["name"];
				$productRowCount = 0;
				$productsArray[$i] = array();
				$productsArray[$i]["id"] = $productId;
				$productsArray[$i]["name"] = $productName;
				$productsArray[$i]["active"] = 0;
				$productsArray[$i]["expired"] = 0;
				
				/** Select all ACTIVE users **/
				$sql2 = "SELECT 
							count(*) as count
						FROM 
							dap_users_products_jn upj,
							dap_products p
						WHERE 
							upj.product_id = " . $productId . " and
							upj.product_id = p.id and
							CURDATE() <= upj.access_end_date
						GROUP BY
							upj.product_id
						";
							
				//logToFile("ACTIVE sql: $sql2"); 
				$stmt2 = $dap_dbh->prepare($sql2);
				$stmt2->bindParam(':start_date', $start_date, PDO::PARAM_STR);
				$stmt2->bindParam(':end_date', $end_date, PDO::PARAM_STR);
				$stmt2->execute();
				
				if ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
					//$productsArray[$i]["name"] = $row2["name"];
					$productsArray[$i]["active"] = $row2["count"];
					$productRowCount += $row2["count"];
					logToFile("product id: " . $productId . ", productsArray[$i][active]: " . $row2["count"]); 
				}
				
				$row2 = null;
				$stmt2 = null;
				$sql2 = null;
			
			
				/** Select all EXPIRED users **/
				$sql2 = "SELECT 
							count(*) as count 
						FROM 
							dap_users_products_jn upj,
							dap_products p
						WHERE 
							upj.product_id = " . $productId . " and
							upj.product_id = p.id and 
							upj.access_end_date < CURDATE()
						GROUP BY
							p.name							
						";
				//logToFile("FREE sql: $sql2"); 
				$stmt2 = $dap_dbh->prepare($sql2);
				$stmt2->bindParam(':start_date', $start_date, PDO::PARAM_STR);
				$stmt2->bindParam(':end_date', $end_date, PDO::PARAM_STR);
				$stmt2->execute();
				
				if ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
					//$productsArray[$i]["name"] = $row2["name"];
					$productsArray[$i]["expired"] = $row2["count"];
					$productRowCount += $row2["count"];
					logToFile("product id: " . $productId . ", productsArray[$i][expired]: " . $row2["count"]); 
				}
				
				$row2 = null;
				$stmt2 = null;
				$sql2 = null;

				//logToFile("-----------------------------------"); 
			
			
				$productsArray[$i]["rowTotal"] = $productRowCount;
				$i++;
			}
			
			$row = null;
			$stmt = null;
			$dap_dbh = null;
			return $productsArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}			
	}
	
	public static function loadMemberSummary($start_date, $end_date) {
		try {
			logToFile("Start, end: $start_date, $end_date"); 
			$dap_dbh = Dap_Connection::getConnection();
			if($start_date == "") $start_date = date("m-d-Y");
			if($end_date == "") $end_date = date("m-d-Y");
			$stmt = null;
			$sql = "select id, name from dap_products";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();
			$productsArray = array();
			$i=0;
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$productId = $row["id"];
				$productName = $row["name"];
				$productRowCount = 0;
				$productsArray[$i] = array();
				$productsArray[$i]["id"] = $productId;
				$productsArray[$i]["name"] = $productName;
				$productsArray[$i]["active"] = 0;
				$productsArray[$i]["expired"] = 0;
				$productsArray[$i]["rowTotal"] = 0;
				
				/** Select all ACTIVE users **/
				$sql2 = "SELECT 
							count(*) as count
						FROM 
							dap_users_products_jn upj,
							dap_products p
						WHERE 
							upj.product_id = " . $productId . " and
							upj.product_id = p.id and
							upj.access_start_date between str_to_date(:start_date, '%m-%d-%Y') and str_to_date(:end_date, '%m-%d-%Y') and
							CURDATE() <= upj.access_end_date
						GROUP BY
							p.id
						";
							
				//logToFile("PAID sql: $sql2"); 
				$stmt2 = $dap_dbh->prepare($sql2);
				$stmt2->bindParam(':start_date', $start_date, PDO::PARAM_STR);
				$stmt2->bindParam(':end_date', $end_date, PDO::PARAM_STR);
				$stmt2->execute();
				
				if ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
					//$productsArray[$i]["name"] = $row2["name"];
					$productsArray[$i]["active"] = $row2["count"];
					$productRowCount += $row2["count"];
					logToFile("product id: " . $productId . ", productsArray[$i][active]: " . $row2["count"]); 
				}
				
				$row2 = null;
				$stmt2 = null;
				$sql2 = null;
			
			
				/** Select all EXPIRED users **/
				$sql2 = "SELECT 
							count(*) as count 
						FROM 
							dap_users_products_jn upj,
							dap_products p
						WHERE 
							upj.product_id = " . $productId . " and
							upj.product_id = p.id and 
							upj.access_end_date < CURDATE() and 
							upj.access_end_date between str_to_date(:start_date, '%m-%d-%Y') and str_to_date(:end_date, '%m-%d-%Y')
						GROUP BY
							p.id							
						";
				//logToFile("FREE sql: $sql2"); 
				$stmt2 = $dap_dbh->prepare($sql2);
				$stmt2->bindParam(':start_date', $start_date, PDO::PARAM_STR);
				$stmt2->bindParam(':end_date', $end_date, PDO::PARAM_STR);
				$stmt2->execute();
				
				if ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
					//$productsArray[$i]["name"] = $row2["name"];
					$productsArray[$i]["expired"] = $row2["count"];
					$productRowCount += $row2["count"];
					logToFile("product id: " . $productId . ", productsArray[$i][expired]: " . $row2["count"]); 
				}
				
				$row2 = null;
				$stmt2 = null;
				$sql2 = null;

				//logToFile("-----------------------------------"); 
			
			
				$productsArray[$i]["rowTotal"] = $productRowCount;
				$i++;
			}
			
			$row = null;
			$stmt = null;
			$dap_dbh = null;
			return $productsArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}			
	}
	
	
	public static function loadEarningsSummary($start_date, $end_date) {
		try {
			//logToFile("Start, end: $start_date, $end_date"); 
			$dap_dbh = Dap_Connection::getConnection();
			if($start_date == "") $start_date = date("m-d-Y");
			if($end_date == "") $end_date = date("m-d-Y");
			$stmt = null;
			$sql = "SELECT
						p.id,
						p.name, 
						sum(CASE WHEN t.payment_value > 0 THEN t.payment_value ELSE 0 end) as product_total_sales,
						sum(CASE WHEN t.payment_value < 0 THEN t.payment_value ELSE 0 end) as product_total_refunds,
						sum(CASE WHEN t.payment_value > 0 THEN 1 ELSE 0 end) as num_sales,
						sum(CASE WHEN t.payment_value < 0 THEN 1 ELSE 0 end) as num_refunds,
						count(*) as total_transactions,
						sum(t.payment_value) as product_net_sales
					FROM
						dap_transactions t,
						dap_products p
					WHERE
						t.product_id = p.id and
						t.payment_status in ('Completed', 'Refund') and
						t.trans_type <> 'subscr_signup' and
						t.date between str_to_date(:start_date, '%m-%d-%Y') and str_to_date(:end_date, '%m-%d-%Y')
					GROUP BY
						t.product_id
					";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
			$stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
			$stmt->execute();
			$resultsArray = array();
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$resultsArray[] = $row;
			}
			
			return $resultsArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}			
	}
	
	public static function loadEarningsSummaryByMonth($start_date, $end_date) {
		try {
			//logToFile("Start, end: $start_date, $end_date"); 
			$dap_dbh = Dap_Connection::getConnection();
			if($start_date == "") $start_date = date("m-d-Y");
			if($end_date == "") $end_date = date("m-d-Y");
			$stmt = null;
			$sql = "
						SELECT 
							YEAR(date) as year, 
							MONTHNAME(date) as month,
						  	COUNT(*) AS numtrans,
						  	sum(payment_value) as total
						FROM 
							dap_transactions
						WHERE
							payment_status in ('Completed', 'Refund') and
							trans_type <> 'subscr_signup' and
							date between str_to_date(:start_date, '%m-%d-%Y') and str_to_date(:end_date, '%m-%d-%Y')
						GROUP BY 
							YEAR(date), MONTH(date)
						";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
			$stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
			$stmt->execute();
			$resultsArray = array();
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$resultsArray[] = $row;
			}
			
			return $resultsArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}			
	}
	
}
?>
