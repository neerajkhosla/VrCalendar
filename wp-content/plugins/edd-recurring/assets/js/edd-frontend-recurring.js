jQuery(document).ready(function($) {
	$('.edd_subscription_cancel').on('click',function(e) {
		if( confirm( edd_recurring_vars.confirm_cancel ) ) {
			return true;
		}
		return false;
	})
});