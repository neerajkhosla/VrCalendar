<?php

class Dap_UserCredits extends Dap_Base {

   	var $user_id;
   	var $product_id;
   	var $credits_earned;
   	var $credits_spent;
   	var $datetime;
   	var $comments;
	
			
		function getUser_id()  {
	       return $this->user_id;
		}
		function setUser_id($o) {
	      $this->user_id = $o;
		}	
		
		function getProduct_id()  {
	       return $this->product_id;
		}
		function setProduct_id($o) {
	      $this->product_id = $o;
		}	
			
		function getCredits_earned()  {
	       return $this->credits_earned;
		}
		function setCredits_earned($o) {
	      $this->credits_earned = $o;
		}	
	
		function getCredits_spent()  {
	       return $this->credits_spent;
		}
		function setCredits_spent($o) {
	      $this->credits_spent = $o;
		}	
	
	public static function redeemCredits ($userId, $productId, $resourceId="", $creditsSpent, $comments="Self-Service") {
	  try {
	  logToFile("In SSS redeemCredits",LOG_DEBUG_DAP); 
	  $sql = "";
	  
	  if( ($creditsSpent=="") || ($creditsSpent==NULL))
	  	$creditsSpent=0;
	  	
	  $dap_dbh = Dap_Connection::getConnection();
	  $dap_dbh->beginTransaction(); //begin the transaction
	  
	  logToFile("In redeemCredits userId=".$userId,LOG_DEBUG_DAP); 
	  logToFile("In redeemCredits productId=".$productId,LOG_DEBUG_DAP); 
	  logToFile("In redeemCredits resourceId=".$resourceId,LOG_DEBUG_DAP); 
	  logToFile("In redeemCredits creditsSpent=".$creditsSpent,LOG_DEBUG_DAP); 
	  logToFile("In redeemCredits comments=".$comments,LOG_DEBUG_DAP); 
	  
	  $transactionId=0;
	  
	/*  $usercred = Dap_UserCredits::loadCreditsPerResource($userId, $productId, $resourceId); 
	  
	  if (($usercred != null) && ($usercred != "")) {
	  logToFile("usercredit row exists",LOG_DEBUG_DAP); 
	  }*/
	  
	  $creditsEarned = 0;
					 
	  //Next add to credits history
	  $sql = "insert into 
				dap_users_credits 
			set
				user_id =:user_id,
				product_id =:product_id,
				resource_id=:resource_id,
				transaction_id =:transaction_id,
				credits_earned =:credits_earned,
				credits_spent =:credits_spent,
				datetime = now(),
				comments =:comments
			";
	  
	  //logToFile("SSS: $sql",LOG_DEBUG_DAP); 
	  $stmt = $dap_dbh->prepare($sql);
	  $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
	  $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
	  $stmt->bindParam(':resource_id', $resourceId, PDO::PARAM_INT);
	  $stmt->bindParam(':transaction_id', $transactionId, PDO::PARAM_INT);
	  $stmt->bindParam(':credits_earned', $creditsEarned, PDO::PARAM_INT);
	  $stmt->bindParam(':credits_spent', $creditsSpent, PDO::PARAM_INT);
	  $stmt->bindParam(':comments', $comments, PDO::PARAM_STR);
	  $stmt->execute();
	  
	  $dap_dbh->commit(); //commit the transaction

  
	  $stmt = null;
	  $dap_dbh = null;
	  
	  return;
	  } catch (PDOException $e) {
		  
		  if(stristr($e->getMessage(), "redeemCredits(): SQLSTATE[23000]: Integrity constraint violation: ") == FALSE) {
				
				logToFile($e->getMessage(),LOG_FATAL_DAP);
				$dap_dbh->rollback();
				throw $e;
				return false;
			}
			else {
				logToFile("Dap_UsersCredits:redeemCredits(): ignore integrity constraint error: " . $e->getMessage(),LOG_DEBUG_DAP);
			}
	 
	  } catch (Exception $e) {
	  $dap_dbh->rollback();
	  logToFile($e->getMessage(),LOG_FATAL_DAP);
	  throw $e;
	  }	
	}
	
	public static function redeemCreditsAtProductLevel($userId, $productId, $creditsSpent, $comments="Self-Service") {
		try {
			logToFile("In SSS addCredits",LOG_DEBUG_DAP); 
			$sql = "";
			
			$dap_dbh = Dap_Connection::getConnection();
			$dap_dbh->beginTransaction(); //begin the transaction
				
			logToFile("In redeemCreditsAtProductLevel userId=".$userId,LOG_DEBUG_DAP); 
			logToFile("In redeemCreditsAtProductLevel creditsSpent=".$creditsSpent,LOG_DEBUG_DAP); 
			logToFile("In redeemCreditsAtProductLevel productId=".$productId,LOG_DEBUG_DAP); 
			
			$creditsEarned = 0;
			$transactionId=-0;
			
			$usercred = Dap_UserCredits::loadCreditsPerProduct($userId, $productId);
							 
			//Next add to credits history
			$sql = "insert into 
						dap_users_credits 
					set
						user_id =:user_id,
						product_id =:product_id,
						transaction_id =:transaction_id,
						credits_earned =:credits_earned,
						credits_spent =:credits_spent,
						datetime = now(),
						comments =:comments
					";
			
			//logToFile("SSS: $sql",LOG_DEBUG_DAP); 
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':product_id', $productId, PDO::PARAM_STR);
			$stmt->bindParam(':transaction_id', $transactionId, PDO::PARAM_INT);
			$stmt->bindParam(':credits_earned', $creditsEarned, PDO::PARAM_INT);
			$stmt->bindParam(':credits_spent', $creditsSpent, PDO::PARAM_INT);
			$stmt->bindParam(':comments', $comments, PDO::PARAM_STR);
			$stmt->execute();
			
			$dap_dbh->commit(); //commit the transaction
			
			$stmt = null;
			$dap_dbh = null;
			
			return;
		} catch (PDOException $e) {
			if(stristr($e->getMessage(), "Dap_UsersCredits:redeemCreditsAtProductLevel(), SQLSTATE[23000]: Integrity constraint violation: ") == FALSE) {
				
				logToFile($e->getMessage(),LOG_FATAL_DAP);
				$dap_dbh->rollback();
				throw $e;
				return false;
			}
			else {
				logToFile("Dap_UsersCredits:redeemCreditsAtProductLevel(), ignore integrity constraint error: " . $e->getMessage(),LOG_DEBUG_DAP);
			}
		} catch (Exception $e) {
			$dap_dbh->rollback();
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}
	
	
	public static function addCredits($userId, $productId, $transactionId, $creditsEarned, $creditsSpent, $comments="Self-Service", $masterProductId="", $resourceId="") {
		try {
			logToFile("In SSS addCredits",LOG_DEBUG_DAP); 
			$sql = "";
			
			$dap_dbh = Dap_Connection::getConnection();
			$dap_dbh->beginTransaction(); //begin the transaction
				
			logToFile("In addCredits userId=".$userId,LOG_DEBUG_DAP); 
			logToFile("In addCredits creditsEarned=".$creditsEarned,LOG_DEBUG_DAP); 
			logToFile("In addCredits creditsSpent=".$creditsSpent,LOG_DEBUG_DAP); 
			logToFile("In addCredits productId=".$productId,LOG_DEBUG_DAP); 
			logToFile("In addCredits resourceId=".$resourceId,LOG_DEBUG_DAP); 
								 
			//Next add to credits history
			$sql = "insert into 
						dap_users_credits 
					set
						user_id =:user_id,
						product_id =:product_id,
						resource_id=:resource_id,
						transaction_id =:transaction_id,
						credits_earned =:credits_earned,
						credits_spent =:credits_spent,
						datetime = now(),
						comments =:comments
					";
			
			//logToFile("SSS: $sql",LOG_DEBUG_DAP); 
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
			$stmt->bindParam(':resource_id', $resourceId, PDO::PARAM_INT);
			$stmt->bindParam(':transaction_id', $transactionId, PDO::PARAM_INT);
			$stmt->bindParam(':credits_earned', $creditsEarned, PDO::PARAM_INT);
			$stmt->bindParam(':credits_spent', $creditsSpent, PDO::PARAM_INT);
			$stmt->bindParam(':comments', $comments, PDO::PARAM_STR);
			$stmt->execute();
		
			$dap_dbh->commit(); //commit the transaction
				  
			if ($creditsEarned != 0) {
			  $user = Dap_User::loadUserById($userId); 
			  logToFile("In addCredits UPDATE CREDITS=".$creditsEarned,LOG_DEBUG_DAP); 
			  $user->updateCredits(0,$creditsEarned);
			}
			
			
			$stmt = null;
			$dap_dbh = null;
			
			return;
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
	



	public static function addCreditsAtProductLevel($userId, $productId, $transactionId, $creditsEarned, $creditsSpent, $comments="Self-Service", $masterProductId="") {
		try {
			logToFile("In SSS addCredits",LOG_DEBUG_DAP); 
			$sql = "";
			
			$dap_dbh = Dap_Connection::getConnection();
			$dap_dbh->beginTransaction(); //begin the transaction
				
			logToFile("In addCredits userId=".$userId,LOG_DEBUG_DAP); 
			logToFile("In addCredits creditsEarned=".$creditsEarned,LOG_DEBUG_DAP); 
			logToFile("In addCredits creditsSpent=".$creditsSpent,LOG_DEBUG_DAP); 
			logToFile("In addCredits productId=".$productId,LOG_DEBUG_DAP); 
			
			$usercred = Dap_UserCredits::loadCreditsPerProduct($userId, $productId);
			
			if (($usercred != null) && ($usercred != "")) {
				logToFile("usercredit row exists",LOG_DEBUG_DAP); 
				
				if (($usercred->getCredits_earned() != "") && ($usercred->getCredits_earned() > 0) && ($comments != "By Admin")) 
					$creditsEarnedNew = $creditsEarned + $usercred->getCredits_earned();
				
				if (($usercred->getCredits_spent() != "") && ($usercred->getCredits_spent() > 0) && ($comments != "By Admin")) 
					$creditsSpentNew = $creditsSpent + $usercred->getCredits_spent();
					
				if (intval($creditsEarned) != 0) {			
					logToFile("update creditsEarned=".$creditsEarnedNew,LOG_DEBUG_DAP); 
					$sql = "update dap_users_credits set credits_earned = :credits_earned where user_id = :user_id and product_id = :product_id and resource_id=0";	
					logToFile("update sql=".$sql,LOG_DEBUG_DAP); 
					$stmt = $dap_dbh->prepare($sql);
					$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
					$stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
					$stmt->bindParam(':credits_earned', $creditsEarnedNew, PDO::PARAM_INT);
					$stmt->execute();
					
				}
				else if (intval($creditsSpent) != 0) {	
					logToFile("update creditsSpent=".$creditsSpentNew,LOG_DEBUG_DAP); 
					$sql = "update dap_users_credits set credits_spent = :credits_spent where user_id = :user_id and product_id = :product_id and resource_id=0";
					logToFile("update sql=".$sql,LOG_DEBUG_DAP); 
					$stmt = $dap_dbh->prepare($sql);
					$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
					$stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
					$stmt->bindParam(':credits_spent', $creditsSpentNew, PDO::PARAM_INT);
					$stmt->execute();
				}
				
			
				logToFile("update complete",LOG_DEBUG_DAP); 
			}
			else {
								 
				//Next add to credits history
				$sql = "insert into 
							dap_users_credits 
						set
							user_id =:user_id,
							product_id =:product_id,
							transaction_id =:transaction_id,
							credits_earned =:credits_earned,
							credits_spent =:credits_spent,
							datetime = now(),
							comments =:comments
						";
				
				//logToFile("SSS: $sql",LOG_DEBUG_DAP); 
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
				$stmt->bindParam(':product_id', $productId, PDO::PARAM_STR);
				$stmt->bindParam(':transaction_id', $transactionId, PDO::PARAM_INT);
				$stmt->bindParam(':credits_earned', $creditsEarned, PDO::PARAM_INT);
				$stmt->bindParam(':credits_spent', $creditsSpent, PDO::PARAM_INT);
				$stmt->bindParam(':comments', $comments, PDO::PARAM_STR);
				$stmt->execute();
			}
			$dap_dbh->commit(); //commit the transaction
			
			$stmt = null;
			$dap_dbh = null;
			
			return;
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
	
	public static function addMasterCredits($userId, $transactionId, $creditsSpent, $masterProductId) {
		try {
			logToFile("In SSS addCredits",LOG_DEBUG_DAP); 
						
			logToFile("In addMasterCredits creditsSpent=".$creditsSpent,LOG_DEBUG_DAP); 
			logToFile("In addMasterCredits masterProductId=".$masterProductId,LOG_DEBUG_DAP); 
			$dap_dbh = Dap_Connection::getConnection();
			$sql = "update dap_users_credits set credits_spent = credits_spent + " . $creditsSpent . " where user_id = $userId and product_id = $masterProductId";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();
			
			//$dap_dbh->commit(); //commit the transaction
			
			$stmt = null;
			$dap_dbh = null;
			
			return;
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
	
	//For a given user, load credit history
	public static function loadCreditHistory($userId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$creditHistoryArray = array();
			$sql = "SELECT 
						uc.user_id,
						uc.product_id,
						uc.product_id as productIdArray,
						uc.resource_id,
						uc.transaction_id,
						uc.credits_earned,
						uc.credits_spent,
						uc.datetime,
						uc.comments
					FROM 
						dap_users_credits uc
					WHERE
						uc.user_id = :userId and
						(uc.credits_spent > 0 or
						uc.credits_earned > 0)
					order by 
						datetime desc";

			logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->execute();	
			
			while($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
				if( !is_null($obj->productIdArray) && ($obj->productIdArray != "") && ($obj->productIdArray != 0) ) {
					$productIds = explode(",", $obj->productIdArray);
					$productName = "";
					foreach($productIds as $productId) {
						$product = Dap_Product::loadProduct($productId);
						if ($product) {
							$productName = $product->getName() . ", " . $productName;
						}
						else continue;
					}
					$obj->name = trim($productName,", ");
				} else {
					$obj->name = "";
				}
								
				if($obj->credits_earned == 0) $obj->credits_earned = "";
				if($obj->credits_spent == 0) $obj->credits_spent = "";
				if ($obj->name != "")
					$creditHistoryArray[] = $obj;
			}
			
			$stmt = null;
			$dap_dbh = null;
			
			return $creditHistoryArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}

//For a given user, load credit history
	public static function loadUserCreditResources($userId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$userCreditResourcesArray = array();
			$sql = "SELECT 
						uc.user_id,
						uc.product_id,
						uc.resource_id,
						uc.transaction_id,
						uc.credits_earned,
						uc.credits_spent,
						uc.datetime,
						uc.comments
					FROM 
						dap_users_credits uc
					WHERE
						uc.user_id = :userId						
					order by uc.product_id,uc.resource_id,datetime desc";

			logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->execute();	
			
			while($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
				logToFile("CREDITS EARNED=".$obj->credits_earned,LOG_DEBUG_DAP);
				
				if($obj->credits_earned == 0) $obj->credits_earned = "";
				if($obj->credits_spent == 0) $obj->credits_spent = "";
				
				$userCreditResourcesArray[] = $obj;
			}
			
			$stmt = null;
			$dap_dbh = null;
			
			return $userCreditResourcesArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}



	public static function addCreditsToUser($email, $credits) {
		try {
			logToFile("In SSS addCreditsToUser",LOG_DEBUG_DAP); 
			
			$user = Dap_User::loadUserByEmail($email); //load user object
			Dap_UserCredits::addCredits($user->getId(), 0, -3, $credits, 0, "Admin added");
			
			return;
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
	
	
	//For a given user, load products that user does NOT have access to
	public static function loadCreditsPerProduct($userId, $productId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			$sql = "SELECT 
				duc.credits_earned, duc.credits_spent 
			FROM 
				dap_users_credits duc
			where
				duc.product_id = :productId and
				duc.user_id = :userId";
				
			//logToFile($sql,LOG_DEBUG_DAP);
			$usercredits="";
			
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			
			$stmt->execute();	
						
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$usercredits = new Dap_UserCredits();
				$usercredits->setCredits_spent($row["credits_spent"]);
				$usercredits->setCredits_earned($row["credits_earned"]);
				$stmt = null;
				$dap_dbh = null;
			
				return $usercredits;
			}
					
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}	

	public static function getTotalCreditsEarnedForProduct($userId, $productId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			$sql = "SELECT sum(duc.credits_earned) as credits
			FROM 
				dap_users_credits duc
			where
				duc.product_id = :productId and
				duc.user_id = :userId";
				
			logToFile($sql,LOG_DEBUG_DAP);
			$usercredits="";
			
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			
			$stmt->execute();	
						
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$credits=$row["credits"];
				$stmt = null;
				$dap_dbh = null;
				logToFile("Dap_UserCredits.class.php: " . $credits,LOG_DEBUG_DAP);
				return $credits;
			}
			
			
			
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}	

  public static function getTotalCreditsUsedToRedeemProduct($userId, $productId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			$sql = "SELECT sum(duc.credits_spent) as credits
			FROM 
				dap_users_credits duc
			where
				duc.product_id = :productId and
				duc.user_id = :userId";
				
			logToFile($sql,LOG_DEBUG_DAP);
			$usercredits="";
			
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			
			$stmt->execute();	
						
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$credits=$row["credits"];
				$stmt = null;
				$dap_dbh = null;
				logToFile("Dap_UserCredits.class.php: " . $credits,LOG_DEBUG_DAP);
				return $credits;
			}
			
			
			
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}	

//For a given user, load products that user does NOT have access to
	public static function hasAccessTo($userId, $productId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			$sql = "SELECT 
				count(*) as count
			FROM 
				dap_users_credits duc			
			where
				duc.product_id = :productId and
				duc.user_id = :userId and 
				(duc.resource_id is NULL or duc.resource_id = 0)";
				
			//logToFile($sql,LOG_DEBUG_DAP);
				
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			
			$stmt->execute();	
			$found=false;	
			logToFile("Dap_UserCredits::hasAccessTo: $found");
			  
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$count=$row["count"];
				logToFile("Dap_UserCredits::count=" . $count);
				if($count>0){ 
				  $found=true;
				  logToFile("Dap_UserCredits::hasAccessTo: FOUND IT");
				}
				
				$dap_dbh = null;
			}
			return $found;
					
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}	

	public static function hasAccessToResources($userId, $productId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			$sql = "SELECT 
				count(*) as count
			FROM 
				dap_users_credits duc			
			where
				duc.product_id = :productId and
				duc.user_id = :userId and 
				(duc.resource_id is NOT NULL and duc.resource_id != 0)";
				
			//logToFile($sql,LOG_DEBUG_DAP);
				
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			
			$stmt->execute();	
			$found=false;	
			logToFile("Dap_UserCredits::hasAccessTo: $found");
			  
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$count=$row["count"];
				logToFile("Dap_UserCredits::count=" . $count);
				if($count>0){ 
				  $found=true;
				  logToFile("Dap_UserCredits::hasAccessTo: FOUND IT");
				}
				
				$dap_dbh = null;
			}
			return $found;
					
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	
	public static function hasAccessToResourcesCount($userId, $productId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			$sql = "SELECT 
				count(*) as count
			FROM 
				dap_users_credits duc			
			where
				duc.product_id = :productId and
				duc.user_id = :userId and 
				(duc.resource_id is NOT NULL and duc.resource_id != 0)";
				
			//logToFile($sql,LOG_DEBUG_DAP);
				
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			
			$stmt->execute();	
			$found=false;	
			logToFile("Dap_UserCredits::hasAccessTo: $found");
			$count=0;
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$count=$row["count"];
				logToFile("Dap_UserCredits::count=" . $count);
				if($count>0){ 
				  $found=true;
				  logToFile("Dap_UserCredits::hasAccessTo: FOUND IT");
				}
				
				$dap_dbh = null;
			}
			return $count;
					
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	
	public static function checkUserAccessToThisCreditsResource($userId, $productId, $resourceId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			//check if user purchased full product, if yes, give user access to all resourcses.
			//if no, check if  resource exists in usercredits table for that user->product
			
			$resId=0;
			
			$sql = "SELECT 
				duc.credits_earned, duc.credits_spent 
			FROM 
				dap_users_credits duc
			where
				duc.product_id = :productId and
				duc.user_id = :userId and
				duc.resource_id = :resId";
			
			$usercredits="";
			
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->bindParam(':resId', $resourceId, PDO::PARAM_INT);
				
			$stmt->execute();	
						
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$usercredits = new Dap_UserCredits();
				$usercredits->setCredits_spent($row["credits_spent"]);
				$usercredits->setCredits_earned($row["credits_earned"]);
				$stmt = null;
				$dap_dbh = null;
			
				return $usercredits;
			}
			
				
			$sql = "SELECT 
				duc.credits_earned, duc.credits_spent 
			FROM 
				dap_users_credits duc
			where
				duc.product_id = :productId and
				duc.user_id = :userId and
				duc.resource_id = :resourceId";
				
			//logToFile($sql,LOG_DEBUG_DAP);
			$usercredits="";
			
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->bindParam(':resourceId', $resourceId, PDO::PARAM_INT);
				
			$stmt->execute();	
						
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$usercredits = new Dap_UserCredits();
				$usercredits->setCredits_spent($row["credits_spent"]);
				$usercredits->setCredits_earned($row["credits_earned"]);
				$stmt = null;
				$dap_dbh = null;
			
				return $usercredits;
			}
					
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}	
	
	
//For a given user, load products that user does NOT have access to
	public static function loadCreditsPerResource($userId, $productId, $resourceId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			//check if user purchased full product, if yes, give user access to all resourcses.
			//if no, check if  resource exists in usercredits table for that user->product
			
			$usercredits="";
							
			$sql = "SELECT 
				duc.credits_earned, duc.credits_spent 
			FROM 
				dap_users_credits duc
			where
				duc.product_id = :productId and
				duc.user_id = :userId and
				duc.resource_id = :resourceId";
				
			//logToFile($sql,LOG_DEBUG_DAP);
			$usercredits="";
			
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->bindParam(':resourceId', $resourceId, PDO::PARAM_INT);
				
			$stmt->execute();	
						
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$usercredits = new Dap_UserCredits();
				$usercredits->setCredits_spent($row["credits_spent"]);
				$usercredits->setCredits_earned($row["credits_earned"]);
				$stmt = null;
				$dap_dbh = null;
			
				return $usercredits;
			}
					
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}	
	
	public static function removeUserResources($userId, $productId) {
		try {
			logToFile("Dap_UserCredits.class: removeUserResources(): $userId, $productId",LOG_DEBUG_DAP); 
			$creditsSpent = Dap_UserCredits::getTotalCreditsUsedToRedeemProduct($userId,$productId); 
				
			logToFile("Dap_UsersCredits: getTotalCreditsUsedToRedeemProduct for the product: " . $creditsSpent, LOG_DEBUG_DAP);
	    
			$dap_dbh = Dap_Connection::getConnection();
			$sql = "delete from dap_users_credits where user_id = :userId and product_id = :productId and (resource_id is NOT NULL and resource_id != 0)";
			
			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			
			$stmt->execute();
			
			$user = Dap_User::loadUserById ($userId);
			
			if($creditsSpent > 0) {
			  $credits_available_now=$user->getCredits_available() + $creditsSpent;
			  $user->updateCredits($credits_available_now);
			  logToFile("Dap_UserCredits::removeUserResources(): Insert into dap_userscredits Done = " . $credits_available_now); 
			}
			
			
			$dap_dbh = null;
			
			return;
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
	
	public static function removeFullProduct($userId, $productId) {
		try {
			logToFile("Dap_UserCredits.class: removeFullProduct() : $userId, $productId",LOG_DEBUG_DAP); 

			$creditsSpent = Dap_UserCredits::getTotalCreditsUsedToRedeemProduct($userId,$productId); 
			logToFile("Dap_UsersCredits: getTotalCreditsUsedToRedeemProduct for the product: " . $creditsSpent, LOG_DEBUG_DAP);
	    
		
			$dap_dbh = Dap_Connection::getConnection();
			$sql = "delete from dap_users_credits where user_id = :userId and product_id = :productId";
			
			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			
			$stmt->execute();
			
			$dap_dbh = null;
			
			$user = Dap_User::loadUserById ($userId);
			/*if($creditsSpent > 0) {
			  $credits_available_now=$user->getCredits_available() + $creditsSpent;
			  $user->updateCredits($credits_available_now);
			  logToFile("Dap_UserCredits::removeFullProduct(): Insert into dap_userscredits Done = " . $credits_available_now); 
			}*/
			
			return;
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
	
	public static function removeThisRow($userId, $productId, $resourceId, $datetime, $comments) {
		try {
			logToFile("Dap_UserCredits.class: $userId, $productId",LOG_DEBUG_DAP); 
			
			
			$creditsSpent = Dap_UserCredits::loadCreditsPerResource($userId, $productId, $resourceId); 
	
			$dap_dbh = Dap_Connection::getConnection();
			
			logToFile("Dap_UserCredits.class: removeThisRow: datetime=".$datetime,LOG_DEBUG_DAP); 
//			logToFile("Dap_UserCredits.class: removeThisRow: $userId, $productId, $resourceId, $datetime, $comments",LOG_DEBUG_DAP); 
			$sql = "delete from dap_users_credits where user_id=:userId and product_id=:productId and resource_id=:resourceId and comments='" . $comments . "' and datetime='" . $datetime . "'" ;
			
			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->bindParam(':resourceId', $resourceId, PDO::PARAM_INT);
			//$stmt->bindParam(':comments', $comments, PDO::PARAM_STR);
		//	$stmt->bindParam(':datetime', $datetime, PDO::PARAM_STR);
			
			$stmt->execute();
			
			$dap_dbh = null;
			
			
			$user = Dap_User::loadUserById ($userId);
			//if($creditsSpent > 0) {
			//  $credits_available_now=$user->getCredits_available() + $creditsSpent;
			  //$user->updateCredits($credits_available_now);
			  //logToFile("Dap_UserCredits::removeFullProduct(): Insert into dap_userscredits Done = " . $credits_available_now); 
			//}
			
			return;
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