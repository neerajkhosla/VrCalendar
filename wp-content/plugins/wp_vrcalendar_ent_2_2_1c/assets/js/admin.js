jQuery(document).ready( function ($){	
    if($('#custom-price tbody tr').length <=0 ) {
        $('#custom-price').hide();
    }	
    $('.vrcal-content-wrapper .nav-tab-wrapper .nav-tab').bind('click', function(e){
        e.preventDefault();
        var tabs_parent = $(this).parent().parent('.tabs-wrapper');
        jQuery('.nav-tab-wrapper .nav-tab', tabs_parent).removeClass('nav-tab-active');
        jQuery('.tabs-content-wrapper .tab-content', tabs_parent).removeClass('tab-content-active');

        jQuery(jQuery(this).attr('href')).addClass('tab-content-active');
        jQuery(this).addClass('nav-tab-active');
    });
    $(document).on('click', '.vrc-remove-link', function(e){
        e.preventDefault();
        if(confirm('Are you sure?'))
            $(this).parent().parent().remove();
        if($('#custom-price tbody tr').length <=0 ) {
            $('#custom-price').hide();
        }
    });

    $(document).on('click', '.vrc-remove-cal-link', function(e){
        e.preventDefault();
        if(confirm('Are you sure?'))
            $(this).parent().parent().parent().parent().parent().parent().parent().remove();

    });
    
    $('#add-more-calendar-links').bind('click', function (e){
        e.preventDefault();
        if($('#calendar-links .calendar_link_row').length>=10) {
            alert('Max 10 links are allowed');
            return;
        }
        var cloned_row = jQuery('#calendar-links-cloner .calendar_link_row').clone( true );
        jQuery('#calendar-links').append(cloned_row);
        console.log(cloned_row);
    });
    $('#add-more-price-exception').bind('click', function (e){
        e.preventDefault();
        $(".vrc-calendar").datepicker("destroy");
        var cloned_row = $('#price-exception-clone tr').clone( true );
        cloned_row.find('.vrc-calendar').each(function() {
            $(this).removeAttr('id').removeClass('hasDatepicker'); // added the removeClass part.
        });
        $('#custom-price').append(cloned_row);
        $('#custom-price').show();
        initDatePicker();
    });
    try {
        initDatePicker();
        $('.vrc-color-picker').wpColorPicker();
    } catch(e){

    }
	// .................added searchbars..............................start...........................
	$('#allcal').click(function(){
        if($(this).is(':checked'))
            $('.searchbar_cals input[type="checkbox"]') .prop('checked', true);
		else
            $('.searchbar_cals input[type="checkbox"]') .prop('checked', false);
	});
    var offer_weekly = $("input:radio[name=calendar_offer_weekly]:checked").val();
	if(offer_weekly == 'yes'){
       $('#weekly_row').show();
	}else{
        $('#weekly_row').hide();
	}
	var offer_monthly = $("input:radio[name=calendar_offer_monthly]:checked").val();
	if(offer_monthly == 'yes'){
       $('#monthly_row').show();
	}else{
        $('#monthly_row').hide();
	}
	$("input:radio[name=calendar_offer_weekly]").click(function(){
        var offer_weekly = $("input:radio[name=calendar_offer_weekly]:checked").val();
		if(offer_weekly == 'yes'){
			$('#weekly_row').show();
		}else{
			$('#weekly_row').hide();
		}
	});
    $("input:radio[name=calendar_offer_monthly]").click(function(){
        var offer_monthly = $("input:radio[name=calendar_offer_monthly]:checked").val();
		if(offer_monthly == 'yes'){
		   $('#monthly_row').show();
		}else{
			$('#monthly_row').hide();
		}
	});
	// .................added searchbars..............................end...........................
});
function initDatePicker() {
    jQuery('.vrc-calendar').datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: -0,
        maxDate: "+3Y"
    });
}
var media_uploader = null;

function open_media_uploader_image()
{
    media_uploader = wp.media({
        frame:    "post",
        state:    "insert",
        multiple: false
    });

    media_uploader.on("insert", function(){
        var json = media_uploader.state().get("selection").first().toJSON();

        var image_url = json.url;
        var image_caption = json.caption;
        var image_title = json.title;
		jQuery('#calendar_listingimage').css('display','inline');
		jQuery('#calendar_listingimage').attr('src', image_url);
		jQuery('#calendar_listing_image').val(image_url);
    });

    media_uploader.open();
}