/**
 * EDD Admin Recurring JS
 *
 * @description: JS for EDD's Recurring Add-on applied in admin download (single download post) screen
 *
 */
var EDD_Recurring_Vars;

jQuery( document ).ready( function ( $ ) {

	var EDD_Recurring = {
		init: function () {

			//Recurring select field conditionals
			this.recurring_select();
			this.validate_times();
			this.edit_expiration();
			this.edit_profile_id();
			this.edit_txn_id();
			this.delete();
			//Ensure when new rows are added recurring fields respect recurring select option
			$( '.edd_add_repeatable' ).on( 'click', this.recurring_select() );

		},

		/**
		 * Recurring Select
		 * @description: Ensures that the "period", "times", and "signup fees" fields are disabled/enabled according to the "Recurring" selection yes/no option
		 */
		recurring_select: function () {
			$( 'body' ).on( 'change', '.edd-recurring-enabled select, select#edd_recurring', function () {
				var $this  = $( this ),
					fields = $this.parents( '#edd_regular_price_field' ).find( 'select,input[type="number"]' ),
					val    = $( 'option:selected', this ).val();

				// Is this a variable select? Check parent
				if ( $this.parents( '.edd_variable_prices_wrapper' ).length > 0 ) {

					fields = $this.parent().parent().find( '.times input, .edd-recurring-period select, .signup_fee input' );

				}

				// Enable/disable fields based on user selection
				if ( val == 'no' ) {
					fields.attr( 'disabled', true );
				} else {
					fields.attr( 'disabled', false );
				}

				$this.attr( 'disabled', false );

			} );

			// Kick it off
			$( '.edd-recurring-enabled select, select#edd_recurring' ).change();

			$( 'input[name$="[times]"], input[name$=times]' ).change( function () {
				$( this ).next( '.times' ).text( $( this ).val() == 1 ? EDD_Recurring_Vars.singular : EDD_Recurring_Vars.plural );
			} );
		},


		/**
		 * Validate Times
		 * @description: Used for client side validation of times set for various recurring gateways
		 */
		validate_times: function () {

			var recurring_times = $( '.times' ).find( 'input[type="number"]' );

			//Validate times on times input blur (client side then server side)
			recurring_times.on( 'blur', function () {

				var time_val = $( this ).val();
				var is_variable = $( 'input#edd_variable_pricing' ).prop( 'checked' );
				var recurring_option = $( this ).parents( '#edd_regular_price_field' ).find( '[id^=edd_recurring]' ).val();
				if ( is_variable ) {
					recurring_option = $( this ).parents( '.edd_variable_prices_wrapper' ).find( '[id^=edd_recurring]' ).val();
				}

				//Verify this is a recurring download first
				//Sanity check: only validate if recurring is set to Yes
				if ( recurring_option == 'no' ) {
					return false;
				}

				//Check if PayPal Standard is set & Validate times are over 1 - https://github.com/easydigitaldownloads/edd-recurring/issues/58
				if ( typeof EDD_Recurring_Vars.enabled_gateways.paypal !== 'undefined' && (time_val == 1 || time_val >= 53) ) {

					//Alert user of issue
					alert( EDD_Recurring_Vars.invalid_time.paypal );
					//Refocus on the faulty input
					$( this ).focus();

				}

			} );

		},

        /**
         * Edit Subscription Text Input
         *
         * @since 
         *
         * @description: Handles actions when a user clicks the edit or cancel buttons in sub details
         *
         * @param link object The edit/cancelled element the user clicked
         * @param input the editable field
         */
        edit_subscription_input: function (link, input) {

            //User clicks edit
            if (link.text() === EDD_Recurring_Vars.action_edit) {
                //Preserve current value
                link.data('current-value', input.val());
                //Update text to 'cancel'
                link.text(EDD_Recurring_Vars.action_cancel);
            } else {
                //User clicked cancel, return previous value
                input.val(link.data('current-value'));
                //Update link text back to 'edit'
                link.text(EDD_Recurring_Vars.action_edit);
            }

        },

		edit_expiration: function() {

			$('.edd-edit-sub-expiration').on('click', function(e) {
				e.preventDefault();

				var link = $(this);
                var exp_input = $('input.edd-sub-expiration');
                EDD_Recurring.edit_subscription_input(link, exp_input);

				$('.edd-sub-expiration').toggle();
				$('#edd-sub-expiration-update-notice').slideToggle();
			});

		},
        
		edit_profile_id: function() {

			$('.edd-edit-sub-profile-id').on('click', function(e) {
				e.preventDefault();

				var link = $(this);
                var profile_input = $('input.edd-sub-profile-id');
                EDD_Recurring.edit_subscription_input(link, profile_input);

				$('.edd-sub-profile-id').toggle();
				$('#edd-sub-profile-id-update-notice').slideToggle();
			});

		},

		edit_txn_id: function() {

			$('.edd-edit-sub-transaction-id').on('click', function(e) {
				e.preventDefault();

				var link = $(this);
                var txn_input = $('input.edd-sub-transaction-id');
                EDD_Recurring.edit_subscription_input(link, txn_input);

				$('.edd-sub-transaction-id').toggle();
			});

		},

		delete: function() {

			$('.edd-delete-subscription').on('click', function(e) {

				if( confirm( EDD_Recurring_Vars.delete_subscription ) ) {
					return true;
				}

				return false;
			});

		}

	};

	EDD_Recurring.init();

} );
