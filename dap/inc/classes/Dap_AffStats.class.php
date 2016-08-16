<?php

class Dap_AffStats extends Dap_Base {

	//Returns all products (as an array) for this user that do not have an affiliate referral specified
	public static function saveAffiliateStats($user_id, $http_referer, $datetime, $useragent, $ip, $dest_url) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$sql = "insert into dap_aff_stats
						(user_id, http_referer, datetime, useragent, ip, dest_url)
					values
						(:user_id, :http_referer, :datetime, :useragent, :ip, :dest_url)
					";

			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
			$stmt->bindParam(':http_referer', $http_referer, PDO::PARAM_STR);
			$stmt->bindParam(':datetime', $datetime, PDO::PARAM_STR);
			$stmt->bindParam(':useragent', $useragent, PDO::PARAM_STR);
			$stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
			$stmt->bindParam(':dest_url', $dest_url, PDO::PARAM_STR);

			$stmt->execute();
			$stmt = null;
			$dap_dbh = null;
			
			return;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			return $e->getMessage();
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			return $e->getMessage();
		}
	}
	
	
	
	public static function loadAffiliatePerformanceSummary($email, $start_date, $end_date) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			if($start_date == "") $start_date = date("m-d-Y");
			if($end_date == "") $end_date = date("m-d-Y");
			if($email == "") $email = "%%";
			//logToFile("start_date: $start_date, end_date: $end_date, email: $email"); 
			$affArray = array();
			
			/**
				1. Get total number of clicks (dap_aff_stats)
				2. Get total leads/referrals (dap_aff_referrals)
				3. Get total purchases
			*/
			
			
			//Get leads in total
			$sql = "select
						count(afr.id) as leads,
						u.id,
						u.first_name,
						u.last_name,
						u.email
					from 
						dap_users u,
						dap_aff_referrals afr
					where 
						u.email like :email and
						u.id = afr.affiliate_id and
						DATE(referral_date) between str_to_date(:start_date, '%m-%d-%Y') and str_to_date(:end_date, '%m-%d-%Y')
						group by afr.affiliate_id
						order by afr.affiliate_id asc";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
			$stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
			$stmt->bindParam(':email', $email, PDO::PARAM_STR);
			$stmt->execute();
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$affId = $row["id"];
				$affArray[$affId]["id"] = $row["id"];
				$affArray[$affId]["leads"] = $row["leads"];
				$affArray[$affId]["first_name"] = $row["first_name"];
				$affArray[$affId]["last_name"] = $row["last_name"];
				$affArray[$affId]["email"] = $row["email"];
				
				//$affArray[$affId]["clicks"] = 0;
				//$affArray[$affId]["sales"] = 0;
				
				//logToFile("first_name: " . $affArray[$affId]["first_name"]); 
				//logToFile("last_name: " . $affArray[$affId]["last_name"]); 
				//logToFile("email: " . $affArray[$affId]["email"]); 
				//logToFile("leads: " . $affArray[$affId]["leads"]); 
			}
			
			$sql = null;
			$stmt = null;
			$row = null;
			
			
			
			
			//Get clicks
			$sql = "select
						count(afs.id) as clicks,
						u.id,
						u.first_name,
						u.last_name,
						u.email
					from 
						dap_users u,
						dap_aff_stats afs
					where 
						u.email like :email and
						u.id = afs.user_id and
						DATE(datetime) between str_to_date(:start_date, '%m-%d-%Y') and str_to_date(:end_date, '%m-%d-%Y')
						group by afs.user_id
						order by afs.user_id asc";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
			$stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
			$stmt->bindParam(':email', $email, PDO::PARAM_STR);
			$stmt->execute();
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$affId = $row["id"];
				$affArray[$affId]["id"] = $row["id"];
				$affArray[$affId]["clicks"] = $row["clicks"];
				$affArray[$affId]["first_name"] = $row["first_name"];
				$affArray[$affId]["last_name"] = $row["last_name"];
				$affArray[$affId]["email"] = $row["email"];
			}
			
			$sql = null;
			$stmt = null;
			$row = null;
			
			
			
			//Get sales in total
			$sql = "select
						sum(t.payment_value) as sales,
						u.id,
						u.first_name,
						u.last_name,
						u.email
					from 
						dap_users u,
						dap_aff_referrals afr,
						dap_products p,
						dap_transactions t,
						dap_aff_earnings ae
					where 
						u.email like :email and
						u.id = afr.affiliate_id and
						afr.product_id = p.id and
						p.id = t.product_id	and
						t.id = ae.transaction_id and
						DATE(t.date) between str_to_date(:start_date, '%m-%d-%Y') and str_to_date(:end_date, '%m-%d-%Y') and
						afr.id = ae.aff_referrals_id
						group by u.id
						order by sales desc";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
			$stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
			$stmt->bindParam(':email', $email, PDO::PARAM_STR);
			$stmt->execute();
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$affId = $row["id"];
				$affArray[$affId]["id"] = $row["id"];
				$affArray[$affId]["sales"] = $row["sales"];
				$affArray[$affId]["first_name"] = $row["first_name"];
				$affArray[$affId]["last_name"] = $row["last_name"];
				$affArray[$affId]["email"] = $row["email"];
			}

			$sql = null;
			$row = null;
			$stmt = null;
				
				
			
			//Get commissions in total
			
			$sql = "SELECT 
						ar.affiliate_id as id,
						u.first_name,
						u.last_name,
						u.email,
						sum(CASE WHEN ae.earning_type in ('L','S','T2','T3') THEN ae.amount_earned ELSE 0 end) as amt_earned_cash,
						sum(CASE WHEN ae.earning_type = 'C' THEN ae.amount_earned ELSE 0 end) as amt_earned_credits
					FROM 
						dap_aff_referrals ar,
						dap_aff_earnings ae,
						dap_users u
					WHERE
						ar.id = ae.aff_referrals_id and
						ar.affiliate_id = u.id and
						u.email like :email and
						DATE(ae.datetime) between str_to_date(:start_date, '%m-%d-%Y') and str_to_date(:end_date, '%m-%d-%Y')
					GROUP BY
						ar.affiliate_id
					";
			
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
			$stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
			$stmt->bindParam(':email', $email, PDO::PARAM_STR);
			$stmt->execute();
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$affId = $row["id"];
				$affArray[$affId]["id"] = $row["id"];
				$affArray[$affId]["amt_earned_cash"] = $row["amt_earned_cash"];
				$affArray[$affId]["amt_earned_credits"] = $row["amt_earned_credits"];
			}
			
			$sql = null;
			$stmt = null;
			$row = null;
			
			$dap_dbh = null;
			//logToFile("-----------------------------------"); 
			
			return $affArray;
			
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