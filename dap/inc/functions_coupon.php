<?php
	
	function validateCouponCode($productId, $couponCode) {
			
		if ($couponCode != "") {
			$coupon = Dap_Coupon::loadCouponByCode($couponCode); //loads coupon details from db
			if (isset($coupon) && (isValidCoupon($coupon) == TRUE)) {
				$productCoupon =	Dap_ProductCoupon::findCouponIdAndProductId($productId, $coupon->getId());
				//See if match found for coupon and product
				if (isset($productCoupon)) {
					logToFile("functions_coupon.php: couponcode=" . $couponCode . " tied to productId=" . $productId);
					return TRUE;	
				}
				else {
					logToFile("functions_coupon.php: couponcode=" . $couponCode . " not tied to productId=" . $productId);
					return FALSE;
				}
			}
			else {
				logToFile("functions_coupon.php: couponcode=" . $couponCode . " not found or not valid.");
				return FALSE;
			}
		}	
		
	} //validateCoupon

	function updateUsage($couponCode) {
		try 
		{	
			if ($couponCode != "") {
				$coupon = Dap_Coupon::loadCouponByCode($couponCode); //loads coupon details from db
				$couponId = $coupon->getId();
				$actual_usage = $coupon->getActual_usage();
				
				if (isset($coupon)) {
					$couponObj = new Dap_Coupon();
					$couponObj->setId($couponId);
					$couponObj->updateUsage($actual_usage + 1);
					return TRUE;
				}
				else {
					logToFile("functions_coupon.php: couponcode=" . $couponCode . " not found");
					return FALSE;
				}
			}	
		}
		catch (PDOException $e) {
			logToFile("functions_coupon.php: " . $e->getMessage(),LOG_FATAL_DAP);
			return FALSE;
		} catch (Exception $e) {
			logToFile("functions_coupon.php: " . $e->getMessage(),LOG_FATAL_DAP);
			return FALSE;
		}	
		
	} //updateUsage
	
	function updateUsageById($couponId) {
		try 
		{	
			if ($couponId != "") {
				$coupon = Dap_Coupon::loadCoupon($couponId); //loads coupon details from db
				$actual_usage = $coupon->getActual_usage();
				
				if (isset($coupon)) {
					$couponObj = new Dap_Coupon();
					$couponObj->setId($couponId);
					$couponObj->updateUsage($actual_usage + 1);
					return TRUE;
				}
				else {
					logToFile("functions_coupon.php: couponcode=" . $couponCode . " not found");
					return FALSE;
				}
			}	
		}
		catch (PDOException $e) {
			logToFile("functions_coupon.php: " . $e->getMessage(),LOG_FATAL_DAP);
			return FALSE;
		} catch (Exception $e) {
			logToFile("functions_coupon.php: " . $e->getMessage(),LOG_FATAL_DAP);
			return FALSE;
		}	
		
	} //updateUsage
	
	
?>