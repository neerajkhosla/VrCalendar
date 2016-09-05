jQuery(document).ready(function ($){
	jQuery( "#vrc-booking-form" ).submit(function( event ) {
        if(jQuery('#nightlimit').val() == 1)
        {
           // var nightCounter = jQuery('#nightCounter').val();
            //alert('Sorry, the minimum booking is for '+nightCounter+' nights. Please book again for at least '+nightCounter+' nights.');
            //return false;
        }
    });
    $.validator.setDefaults({
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });
    $('.vrc-validate').each(function (e){
        $(this).validate({

        });
    });
    $('#vrc-stripe-payment-form').ajaxForm({
        url: vrc_data.ajax_url,
        beforeSubmit: function (formData, jqForm, options) {
            //console.log(formData);
        },
        success: function (responseText, statusText, xhr, $form){
            var response = JSON.parse(responseText);
            if(response.result == 'success'){
                var thankyou_url = vrc_data.thankyou_url;
                var redirect_url = thankyou_url.replace('{bid}', response.bid);
                window.location = redirect_url;
            }
            else {
                $('#vrc-payment-error').html(response.msg);
                $('#vrc-payment-error').removeClass('hidden');
            }
        }
    });
    $(".vrc-calendar .calendar-slides").owlCarousel({
        singleItem:true,
        navigation:false,
        pagination:false,
        autoHeight:true
    });
    $(".vrc-calendar .btn-prev").click(function(){
        var parent = $(this).parent().parent().parent().parent().parent().parent();
        $(".calendar-slides", parent).trigger('owl.prev');
    });
    $(".vrc-calendar .btn-next").click(function(){
        var parent = $(this).parent().parent().parent().parent().parent().parent();
        $(".calendar-slides", parent).trigger('owl.next');
    });

    $(".vrc.vrc-calendar .day-number.no-event-day").tipsy({
        title: 'data-tooltip',
        gravity: $.fn.tipsy.autoNS,
        fade: true
    });

    $(".vrc.vrc-calendar.vrc-calendar-booking-yes .day-number.no-event-day").click(function (e){
        
         var onepagevalue = jQuery('#onepagevalue').val();
         if(onepagevalue == 1){
            //alert("onepagevalue"); //23-02-2016
            //jQuery('.vrcbookingformwrapper').show();
            var booking_url = vrc_data.booking_url;
            var cid = jQuery(this).attr('data-calendar-id');
            var bdate = jQuery(this).attr('data-booking-date');
            var redirect_url = booking_url.replace('{cid}', cid);
            /*alert(booking_url);
            alert(cid);
            alert(bdate);
            alert(redirect_url);*/
            var firstInDate = jQuery('#booking_checkin_date').val();
            var firstOutDate = jQuery('#booking_checkout_date').val();
            
            var valid_hourly_booking = jQuery('#valid_hourly_booking').val();
                        
             if(valid_hourly_booking == 0)
             {
                if(firstInDate == '')
                {
                    jQuery('#booking_checkin_date').val(bdate);
                }else if(firstInDate > firstOutDate && firstOutDate != '')
                {
                    jQuery('#booking_checkin_date').val(bdate);
                }else if(firstInDate > bdate){
                    jQuery('#booking_checkin_date').val(bdate);
                    jQuery('#booking_checkout_date').val('');
                }else
                {
                 jQuery('#booking_checkout_date').val(bdate);
                }
             }
             else{

                   jQuery('#booking_checkin_date').val(bdate);
                   jQuery('#booking_checkout_date').val(bdate);
                   jQuery('#date_of_only_hourly_booking').val(bdate);
                   firstDatetimeChange(bdate); //today
             }

            
            
            var date1 = jQuery('#booking_checkin_date').val();
            var date2 = jQuery('#booking_checkout_date').val();
            var get_calendar_id = jQuery('#cal_id').val();
            var data = {
                        'action': 'getavailablerangebgcolorset',
                        'date1': date1,
                        'date2': date2,
                        'get_calendar_id' : get_calendar_id
                        };
            
            
            $.post(vrc_data.ajax_url, data, function(response) {
                //alert(response);
                if(response == 3)
                {
                   /*****/
                    jQuery('#price-per-night').html('0.00');
                    jQuery('#table-price-per-night').html('0.00');
                    jQuery('#table-booking-days').html('0.00');
                    jQuery('#table-base-booking-price').html('0.00');
                    jQuery('#table-cleaning-fee').html('0.00');
                    jQuery('#table-tax-amt').html('0.00');
                    jQuery('#table-price-special').html('0.00');
                    jQuery('#table-booking-price-with-taxes').html('0.00');
                    jQuery('.rest_of_dates_data').html('');
                    jQuery('#nightlimit').val('0.00');
                    jQuery('#nightCounter').val('0.00');
                    
                    jQuery('#table-price-additional').html('0.00');
                    jQuery('#table-extra-fees').html('0.00');
                    jQuery('#table-price-original').removeClass('spanhide');
                    jQuery('#table-price-special').addClass('spanhide');
                   /*****/
                }
                else if(response == 1)
                 {
                    jQuery('#booking_checkin_date').val(bdate);
                    jQuery('#booking_checkout_date').val('');
                    
                    jQuery('#price-per-night').html('0.00');
                    jQuery('#table-price-per-night').html('0.00');
                    jQuery('#table-booking-days').html('0.00');
                    jQuery('#table-base-booking-price').html('0.00');
                    jQuery('#table-cleaning-fee').html('0.00');
                    jQuery('#table-tax-amt').html('0.00');
                    jQuery('#table-price-special').html('0.00');
                    jQuery('#table-booking-price-with-taxes').html('0.00');
                    jQuery('.rest_of_dates_data').html('');
                    jQuery('#nightlimit').val('0.00');
                    jQuery('#nightCounter').val('0.00');
                    
                    jQuery('#table-price-additional').html('0.00');
                    jQuery('#table-extra-fees').html('0.00');
                    jQuery('#table-price-original').removeClass('spanhide');
                    jQuery('#table-price-special').addClass('spanhide');
                    
                 }else
                 {
                    updatePrice();
                 }
             });
            //jQuery(this).css('background','#1e73be');
                                 
            /*if(jQuery('#booking_checkin_date').val() != '' && jQuery('#booking_checkout_date').val() != '')
            {
                updatePrice();
            }*/
            return false;
            
         }else{
            var booking_url = vrc_data.booking_url;
            var cid = jQuery(this).attr('data-calendar-id');
            var bdate = jQuery(this).attr('data-booking-date');		
            var redirect_url = booking_url.replace('{cid}', cid);
			redirect_url = redirect_url.replace('{bdate}', bdate);		
            window.location = redirect_url;
        }
    });
    $('#booking_checkin_date').datepicker({
        dateFormat : 'yy-mm-dd',
        minDate: -0,
        maxDate: "+3Y",
        beforeShowDay: function(date){
            /* disable unavailable dates */
            var booked_dates = jQuery('#booked_dates').val();
			if(booked_dates != ''){
				booked_dates = JSON.parse(booked_dates);
			
				//console.log(booked_dates);
				var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
				return [$.inArray(string, booked_dates) == -1];
			}
        },
        onClose: function( selectedDate, inst ) {
            /* Make ajax call for get max selectable date */
            var data = {
                'action': 'get_available_range_end',
                'cal_id': $('#cal_id').val(),
                'start_date': selectedDate
            };
            //$( "#booking_checkout_date" ).datepicker( "option", "minDate", selectedDate );
            var minDate = new Date(selectedDate);
            minDate.setDate(minDate.getDate() + 1);
            var minDateStr = $.datepicker.formatDate('yy-mm-dd', minDate);
            $( "#booking_checkout_date" ).datepicker( "option", "minDate", minDateStr );
            $.post(vrc_data.ajax_url, data, function(response) {
                //console.log(response);
                $( "#booking_checkout_date" ).datepicker( "option", "maxDate", response );
            });
            updatePrice();
        }
    });
    $('#booking_checkout_date').datepicker({
        dateFormat : 'yy-mm-dd',
        minDate: -0,
        maxDate: "+3Y",
        onClose: function( selectedDate ) {
            //$( "#booking_checkin_date" ).datepicker( "option", "maxDate", selectedDate );
            updatePrice();
        }
    });
	$('#booking_guests_count').change(function(){
		updatePrice();
	});
    var selectedDate = $('#booking_checkin_date').val();
    var minDate = new Date(selectedDate);
    
    var proonedaybook = jQuery('#proonedaybook').val();
    if(proonedaybook == 0)
      {
        minDate.setDate(minDate.getDate() + 1);
      }
    
    //minDate.setDate(minDate.getDate() + 1);
    var minDateStr = $.datepicker.formatDate('yy-mm-dd', minDate);
    $( "#booking_checkout_date" ).datepicker( "option", "minDate", minDateStr );
    var data = {
        'action': 'get_available_range_end',
        'cal_id': $('#cal_id').val(),
        'start_date': selectedDate
    };
    $.post(vrc_data.ajax_url, data, function(response) {
        console.log(response);
        $( "#booking_checkout_date" ).datepicker( "option", "maxDate", response );
    });
	// .................added searchbars..............................start...........................
   /* jQuery('.vrc-calendar-searchbar').datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: -0,
        maxDate: "+3Y"
    });*/
	jQuery('#searchbar_checkindate').datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: -0,
        maxDate: "+3Y",    
		onClose: function( selectedDate ) {
            var minDate = new Date(selectedDate);
			minDate.setDate(minDate.getDate() + 1);
			var minDateStr = $.datepicker.formatDate('yy-mm-dd', minDate);
			$( "#searchbar_checkoutdate" ).datepicker( "option", "minDate", minDateStr );
        }	
	});
	jQuery('#searchbar_checkoutdate').datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: -0,
        maxDate: "+3Y",
		onClose: function( selectedDate ) {
		$( "#searchbar_checkindate" ).datepicker( "option", "maxDate", selectedDate );
		}
	});	
	var minval= $('#searchbar-booking-price-min').val();
	var maxval= $('#searchbar-booking-price-max').val();	
	$( "#searchbar-price-range" ).slider({
      range: true,
      min: 0,
      max: 1000,
      values: [ minval, maxval ],
      slide: function( event, ui ) {
        $( "#searchbar-booking-price-from" ).text(  ui.values[ 0 ] );
        $( "#searchbar-booking-price-to" ).text(  ui.values[ 1 ] );
		$( "#searchbar-booking-price-min" ).val(ui.values[ 0 ]);
		$( "#searchbar-booking-price-max" ).val(ui.values[ 1 ]);
      }
    });
	$( "#searchbar-booking-price-from" ).text($( "#searchbar-price-range" ).slider( "values", 0 ) );
    $( "#searchbar-booking-price-to" ).text(  $( "#searchbar-price-range" ).slider( "values", 1 ) );
	$( "#searchbar-booking-price-min" ).val($( "#searchbar-price-range" ).slider( "values", 0 ));
	$( "#searchbar-booking-price-max" ).val($( "#searchbar-price-range" ).slider( "values", 1 ));	
    // .................added searchbars..............................end...........................
	jQuery('.booking-list').dataTable({"bFilter": false,
		                               "bSort": false,
		                              // "paging" :jQuery(".booking-list").find('tbody tr').length>10,		                               
		                               "dom": 'rt<"bottom"flp><"clear">'});
	if(jQuery(".booking-list").find('tbody tr').length<11)
		jQuery('.dataTables_length').hide();
});
function updatePrice() {

    
    
    var data = {
        'action': 'get_updated_price',
        'cal_id': jQuery('#cal_id').val(),
        'checkin_date': jQuery('#booking_checkin_date').val(),
        'checkout_date': jQuery('#booking_checkout_date').val(),
        'booking_guests_count': jQuery('#booking_guests_count').val()
    };
    jQuery.post(vrc_data.ajax_url, data, function(response) {
        
        var price_data = JSON.parse(response);
        
        var rest_of_dates_data = '';
        if(price_data.remining_price_amount > 0)
        {
            rest_of_dates_data = 'Balance of <b>'+price_data.render_currency+''+price_data.remining_price_amount.toFixed(2)+'</b> will be due in <b>'+price_data.rest_of_days+'</b> days before arrival '+price_data.rest_of_pay_date;
        }

        //console.log(price_data);
                
        jQuery('.default_price').css('display','none');
        jQuery('.updated_price').css('display','none');
         
         //alert(price_data.deposit_enable_by_date);
		if(price_data.booking_days >= price_data.number_of_nights){
			
			jQuery('.table-hover').removeClass( ' rowhide' );
			jQuery('.numberofnights').css( 'display','none' );
			jQuery('.booking-form-action').removeClass( 'rowhide' );
		}else{
			alert('Plese choose minimum '+price_data.number_of_nights+' nights');
			window.location.reload(true);
			return false;
		}
        if(price_data.deposit_enable_by_date == 1)
        {
            jQuery('.updated_price').css('display','block');
            jQuery('.rest_of_dates_data').html(rest_of_dates_data);
        }else
        {
            jQuery('.default_price').css('display','block');
        }
        
        jQuery('.total_reservation_amount').html(price_data.total_reservation_amount);
        //----------------------------------------
		jQuery('#table-basetext').html(price_data.texthtml);
        jQuery('#table-baseprice').html(price_data.pricehtml);
		//---------------------------------------------------
        jQuery('#price-per-night').html(price_data.price_per_night);
        jQuery('#table-price-per-night').html(price_data.price_per_night);

		if(price_data.weekend_night_data.booking == 'yes'){
			jQuery('#friday_night_booking').css('display','block');
			jQuery('#weekend-offer').css('display','block');
		}else{
			jQuery('#friday_night_booking').css('display','none');
			jQuery('#weekend-offer').css('display','none');
		}
		jQuery('#friday-price-per-night').html(price_data.weekend_night_data.fridaynights);
		jQuery('#friday-booking-days').html(price_data.weekend_night_data.nightsper.Friday);
		jQuery('#friday-total-booking-price').html(price_data.weekend_night_data.prices.Friday);
		//jQuery('#saturday_nights_booking').html(price_data.weekend_night_data.Saturday.booking);
		if(price_data.weekend_night_data.booking == 'yes'){
			jQuery('#saturday_night_booking').css('display','block');
			jQuery('#weekend-offer-saturday').css('display','block');
		}else{
			jQuery('#saturday_night_booking').css('display','none');
			jQuery('#weekend-offer-saturday').css('display','none');
		}
		jQuery('#saturday-price-per-night').html(price_data.weekend_night_data.saturdaynights);
		jQuery('#saturday-booking-days').html(price_data.weekend_night_data.nightsper.Saturday);
		jQuery('#saturday-total-booking-price').html(price_data.weekend_night_data.prices.Saturday);
		//jQuery('#sunday_nights_booking').html(price_data.weekend_night_data.Sunday.booking);
		if(price_data.weekend_night_data.booking == 'yes'){
			jQuery('#sunday_night_booking').css('display','block');
			jQuery('#weekend-offer-sunday').css('display','block');
		}else{
			jQuery('#sunday_night_booking').css('display','none');
			jQuery('#weekend-offer-sunday').css('display','none');
		}
		
		jQuery('#sunday-price-per-night').html(price_data.weekend_night_data.sundaynights);
		jQuery('#sunday-booking-days').html(price_data.weekend_night_data.nightsper.Sunday);
		jQuery('#sunday-total-booking-price').html(price_data.weekend_night_data.prices.Sunday);
		jQuery( ".xyz" ).remove();
		var json = price_data.weekend_night_data.nightsper.Seasonal;
		var totalseasonalarray = price_data.weekend_night_data.prices.Seasonal;
		console.log(totalseasonalarray);
		count = 0;
		if(json.length >= 1){
			jQuery.each( totalseasonalarray, function( keys, value ) {
				var hh =  keys.split("_"); 
				key = hh[1];
				var seasonalmultiple = key*value;
				var seasonal = "<span class='weekend-offer xyz' id='seasonal_night_booking"+count+"'><span id='table-price-original"+count+"'>$<span id='seasonal_price"+count+"'>"+value+"</span> x <span id='seasonal-price-per-night"+count+"'>" +key+"</span></span> Seasonal nights</span>";
				jQuery("#sunday_night_booking").append(seasonal);
				var seasonal1 = "<span class='weekend-offer xyz' id='seasonal_offers"+count+"'>$<span id='seasonal-booking-days"+count+"'>"+seasonalmultiple+".00</span></span>";
				jQuery("#sunday-total-booking-price").append(seasonal1);
				count++;
			});
		}
		jQuery('#table-booking-days').html(price_data.booking_days);
        jQuery('#table-base-booking-price').html(price_data.base_booking_price);
        jQuery('#table-cleaning-fee').html(price_data.cleaning_fee);
        jQuery('#table-tax-amt').html(price_data.tax_amt);
		jQuery('#table-price-special').html(price_data.special_offer_text);
		if(price_data.deposit_enable == 'yes'){
			jQuery('#table-booking-price-with-taxes').html(price_data.booking_price_dsicounted);
		}else{
			jQuery('#table-booking-price-with-taxes').html(price_data.booking_price_with_taxes);
		}
        
        jQuery('#nightlimit').val(price_data.nightlimit);
        jQuery('#nightCounter').val(price_data.nightcounter);
         

		if(price_data.totaladditionalcharge > 0)
                {
			jQuery('#table-row-additional').removeClass('rowhide');
                    jQuery('#table-price-additional').html(price_data.totaladditionalcharge);
		}else{
			jQuery('#table-row-additional').addClass('rowhide');
                        jQuery('#table-price-additional').html('');
		}
		if(price_data.special_offer_text ==''){
		    jQuery('#table-price-original').removeClass('spanhide');
		    jQuery('#table-price-special').addClass('spanhide');
		}else{
                    jQuery('#table-price-original').addClass('spanhide');
		    jQuery('#table-price-special').removeClass('spanhide');  
		}
		
		jQuery('#table-extra-fees').html(price_data.extra_fees);
    });
}
/*
var geocoder = new google.maps.Geocoder();
var map = [];
var pos = [];
function initialize()
{
    google.maps.visualRefresh = true;
    getGeoCode();
}

function makeMap(i,addr) {
    geocoder.geocode({'address': addr}, function(results, status) {
        if (status === google.maps.GeocoderStatus.OK)
        {

            pos[i] = results[0].geometry.location;
			zoomval = 12;
			if(addr == 'USA')
			zoomval = 2;
            var mapOptions = {
                zoom: zoomval,
                center: pos[i],
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                disableDefaultUI: true,                
            };
            map[i] = new google.maps.Map(document.getElementById("googleMap" + i), mapOptions);
            // var image = '<?php echo get_template_directory_uri(); ?>/images/marker.png';
            var marker = new google.maps.Marker({
                position: pos[i],
                // icon:image
            });
			var infowindow = new google.maps.InfoWindow({
				content: "<p><a href='javascript:void(0)' class='close' style='position:absolute;right:0px;font-size:11px;'><strong>X</strong></a></p>"+ addr +"<br><a target='_blank' href='http://maps.google.com/?q="+ addr +"'>View on Google Maps</a>"
			});

			marker.addListener('click', function() {
				infowindow.open(map[i], marker);
			});

            if(addr != 'USA') 
            marker.setMap(map[i]); 			
        }
        else
        {
           // alert("Not found");
        }
    });
}
function getGeoCode()
{  
    jQuery('.gmap').each(function(){
		var mapid = jQuery(this).attr('id');
		var arr = mapid.split('Map');
		var i = arr[1];
		if(jQuery(this).prev('.col-sm-3').length){
		    var prevdiv = jQuery(this).prev('.col-sm-3');		   
			if(prevdiv.find('.listing_image').length >0){
                var h= prevdiv.find('.listing_image').height();
				if(h > 180)
		        jQuery('#'+mapid).height(h);
		    }	
		}		
		var addr = jQuery(this).attr('rel');
        makeMap(i, addr);
		
});
}
google.maps.event.addDomListener(window, 'load', initialize);
*/