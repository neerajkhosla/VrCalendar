<?php
$calendar_id = @$_GET['cal_id'];
if(empty($calendar_id)){
    _e('Missing calendar id', VRCALENDAR_PLUGIN_TEXT_DOMAIN);;
    return;
}
/* Display booking table here */
$VRCalendarEntity = VRCalendarEntity::getInstance();
$booking_data = $VRCalendarEntity->getCalendar( $calendar_id );

if($_GET['fillter'])
{
    $booking_data = $VRCalendarEntity->getCalendar( $calendar_id, $_GET['fillter'] );
}
else
{
    $booking_data = $VRCalendarEntity->getCalendar( $calendar_id, 'all' );
}
?>


    <div class="wrap vrcal-content-wrapper vr-booking vr-dashboard">
	

<?php
$cdata = $VRCalendarEntity->getCalendar($calendar_id);

class VRBookingsTable extends WP_List_Table {
		function __construct() {
			parent::__construct();
		}
		/**
		 * @Method name  column_default
		 * @Params       $booking,$column_name
		 * @description  display static column name and corrosponding value
		 */
		
		 function linkName_default($booking_calendar_id, $booking_source)
		{
			$VRCalendarEntity = VRCalendarEntity::getInstance();
			$cdata = $VRCalendarEntity->getCalendar($booking_calendar_id);
			$name = '';
			foreach($cdata->calendar_links as $calendar_links)
			{
				if($calendar_links->url == $booking_source)
				{
					$name = $calendar_links->name;
				}
			}
			
				  
			return $name;
		}
		
		function depositeTypeData($booking_id, $type)
		{
			$returnAmount = 0;
			
			$VRCalendarBooking = VRCalendarBooking::getInstance();
			$data_book =$VRCalendarBooking->getBookingByID($booking_id);
			
			$remining_price_amount = $data_book->booking_sub_price['remining_price_amount'];		
			$booking_price = $data_book->booking_total_price;
			$diposited_price_amount = $booking_price - $remining_price_amount;
			if($remining_price_amount == '')
				$remining_price_amount = 0;
			
			if($type == 'booking_total_price')
			{
				$returnAmount = $booking_price;
			}
			if($type == 'booking_deposit_amount')
			{
				$returnAmount = $diposited_price_amount;
			}
			
			if($type == 'booking_remaining_amount')
			{
				$returnAmount = $remining_price_amount;
			}
			return number_format($returnAmount, 2);
		}
				
		function column_default($booking, $column_name)
		{
			$VRCalendarEntity = VRCalendarEntity::getInstance();
			$cdata_data = $VRCalendarEntity->getCalendar($booking['booking_calendar_id']);//
			
			$VRCalendarEntity = VRCalendarEntity::getInstance();
			$cal_data = $VRCalendarEntity->getCalendar( $_GET['cal_id'] );
			
			if($cal_data->deposit_enable == "yes")
			{
			   switch ($column_name)
				{   
				   case 'booking_id':
						  return  $booking['booking_id'];
					case 'booking_total_price':
						  return  $this->depositeTypeData($booking['booking_id'], 'booking_total_price');
					case 'booking_deposit_amount':
						  return  $this->depositeTypeData($booking['booking_id'], 'booking_deposit_amount');
					case 'booking_remaining_amount':
						  return  $this->depositeTypeData($booking['booking_id'], 'booking_remaining_amount');
					case 'booking_created_on':
					case 'booking_date_from':
							if($cdata_data->hourly_booking == 'yes')
								return date('Y-m-d, g:i a', strtotime($booking[$column_name]));
							else
								return  date('Y-m-d', strtotime($booking[$column_name]));
					case 'booking_date_to':
							if($cdata_data->hourly_booking == 'yes')
								return date('Y-m-d, g:i a', strtotime($booking[$column_name]));
							else
								return  date('Y-m-d', strtotime($booking[$column_name]));
					case 'booking_user_name':
						return $booking['booking_user_fname'].' '.$booking['booking_user_lname'];
					case 'booking_user_phone':
						   if($booking['booking_user_phone'] > 0)
								return $booking['booking_user_phone'];
							else 
								return '';
					case 'booking_user_source':
						if($booking['booking_source'] == 'website')
							return $booking['booking_source'];
						else
							return $this->linkName_default($booking['booking_calendar_id'], $booking['booking_source']);
					default:
						return $booking[$column_name];
				} 
			}else
			{
			/* display all dynamic data from database  */
			
				switch ($column_name)
				{  
					case 'booking_id':
						  return  $booking['booking_id'];
					case 'booking_created_on':
					case 'booking_date_from':
							if($cdata_data->hourly_booking == 'yes')
								return date('Y-m-d, g:i a', strtotime($booking[$column_name]));
							else
								return  date('Y-m-d', strtotime($booking[$column_name]));
					case 'booking_date_to':
							if($cdata_data->hourly_booking == 'yes')
								return date('Y-m-d, g:i a', strtotime($booking[$column_name]));
							else
								return  date('Y-m-d', strtotime($booking[$column_name]));
					case 'booking_user_name':
						return $booking['booking_user_fname'].' '.$booking['booking_user_lname'];
					case 'booking_user_phone':
						   if($booking['booking_user_phone'] > 0)
								return $booking['booking_user_phone'];
							else 
								return '';
					case 'booking_user_source':
						if($booking['booking_source'] == 'website')
							return $booking['booking_source'];
						else
							return $this->linkName_default($booking['booking_calendar_id'], $booking['booking_source']);
					default:
						return $booking[$column_name];
				}
			}
		}
		/**
		 * @Method name  column_name
		 * @Params       $booking
		 * @description  display static column name and corrosponding value
		 */
		/*function column_booking_user_name($booking)
		{
			$actions = array(
				'delete' => '<a href="' .site_url($post->post_name.'/?page='.VRCALENDAR_PLUGIN_SLUG.'-dashboard&view=bookings&vrc_cmd=VRCalendarAdmin:deleteBooking&bid='.$booking['booking_id'].'&cal_id='.$_GET['cal_id']). '">'.__('Delete', VRCALENDAR_PLUGIN_TEXT_DOMAIN).'</a>'
			);

			if($booking['booking_admin_approved'] == 'no') {
				$actions['approve_booking'] = '<a href="' .site_url($post->post_name.'/?page='.VRCALENDAR_PLUGIN_SLUG.'-dashboard&view=bookings&vrc_cmd=VRCalendarAdmin:approveBooking&bid='.$booking['booking_id'].'&cal_id='.$_GET['cal_id']). '">'.__('Approve', VRCALENDAR_PLUGIN_TEXT_DOMAIN).'</a>';
			}
			return $booking['booking_user_fname'].' '.$booking['booking_user_lname'].$this->row_actions($actions) ;
		}*/
		function column_booking_id($booking)
		{
			global $post;
			$page = $post->post_name;
			$actions = array(
				'edit' => '<a href="' .site_url($post->post_name.'/?page='.VRCALENDAR_PLUGIN_SLUG.'-dashboard&view=bookings&action=editbooking&bid='.$booking['booking_id'].'&cal_id='.$_GET['cal_id']). '">'.__('Edit', VRCALENDAR_PLUGIN_TEXT_DOMAIN).'</a>',
				'delete' => '<a href="' .site_url($post->post_name.'/?page='.VRCALENDAR_PLUGIN_SLUG.'-dashboard&view=bookings&vrc_cmd=VRCalendarAdmin:deleteBooking&bid='.$booking['booking_id'].'&cal_id='.$_GET['cal_id']). '">'.__('Delete', VRCALENDAR_PLUGIN_TEXT_DOMAIN).'</a>'
			);

			if($booking['booking_admin_approved'] == 'no') {
				$actions['approve_booking'] = '<a href="' .site_url($post->post_name.'/?page='.VRCALENDAR_PLUGIN_SLUG.'-dashboard&view=bookings&vrc_cmd=VRCalendarAdmin:approveBooking&bid='.$booking['booking_id'].'&cal_id='.$_GET['cal_id']). '">'.__('Approve', VRCALENDAR_PLUGIN_TEXT_DOMAIN).'</a>';
			}
			return $booking['booking_id'].$this->row_actions($actions) ;
		}

		/**
		 * @Method name  column_cb
		 * @Params       $booking
		 * @description  display check box for all Calendar data value
		 */
		function column_cb($booking)
		{
			if(isset($booking['calendar_id']))
				return '<input type="checkbox" name="check[]" value="'.$booking['calendar_id'].'" />';
			else
				return '<input type="checkbox" name="check[]" value="" />';
		}

		/**
		 * @Method name  get_columns
		 * @description  display head tr for table
		 */
		function get_columns()
		{
			$VRCalendarEntity = VRCalendarEntity::getInstance();
			$cal_data = $VRCalendarEntity->getCalendar( $_GET['cal_id'] );
			
			if($cal_data->deposit_enable == "yes")
			{
				$columns = array(
					'cb' => '<input type="checkbox"/>',
					'booking_user_name' => __('Name', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_user_email' => __('Email', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_user_phone' => __('Phone No', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_date_from' =>__('Booking From', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_date_to' =>__('Booking To', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_status'=> __('Status', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_total_price'=>__('Booking Price ($)', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_created_on' =>__('Booking Date', VRCALENDAR_PLUGIN_TEXT_DOMAIN)
				);
				
				/*$columns = array(
					'cb' => '<input type="checkbox"/>',
					'booking_id' => __('Booking ID',RCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_user_name' => __('Name', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_user_source' => __('Source', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_user_email' => __('Email', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_user_phone' => __('Phone No', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_date_from' =>__('Booking From', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_date_to' =>__('Booking To', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_guests'=> __('Guests', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_status'=> __('Status', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_payment_status'=>__('Payment Status', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_admin_approved'=>__('Booking Approved', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_total_price'=>__('Booking Price ($)', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					
					'booking_deposit_amount'=>__('Deposit Amount ($)', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_remaining_amount'=>__('Remaining Amount ($)', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					
					'booking_created_on' =>__('Booking Date', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_summary' => __('Summary', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
				);*/
			}else
			{
				$columns = array(
					'cb' => '<input type="checkbox"/>',
					'booking_user_name' => __('Name', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_user_email' => __('Email', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_user_phone' => __('Phone No', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_date_from' =>__('Booking From', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_date_to' =>__('Booking To', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_status'=> __('Status', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_created_on' =>__('Booking Date', VRCALENDAR_PLUGIN_TEXT_DOMAIN)
				);
				/*$columns = array(
					'cb' => '<input type="checkbox"/>',
					'booking_id' => __('Booking ID',RCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_user_name' => __('Name', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_user_source' => __('Source', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_user_email' => __('Email', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_user_phone' => __('Phone No', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_date_from' =>__('Booking From', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_date_to' =>__('Booking To', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_guests'=> __('Guests', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_status'=> __('Status', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_payment_status'=>__('Payment Status', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_admin_approved'=>__('Booking Approved', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_total_price'=>__('Booking Price ($)', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_created_on' =>__('Booking Date', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
					'booking_summary' => __('Summary', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
				);*/
			}
			
			
			
			return $columns;
		}

		function process_bulk_action()
		{
			extract($_REQUEST);
			if(isset($check))
			{
				if( 'trash'===$this->current_action() )
				{
					$msg = 'delete';
					global $wpdb;
					$booking_table = $wpdb->prefix."vrcalandar_bookings";
					foreach($check as $booking_id)
					{
						$booking_query = "delete  FROM ".$booking_table." where booking_id='".$booking_id."' ";
						$wpdb->query($booking_query);
					}
					//$redirectTo = admin_url().'admin.php?page=vr-calendar/includes/controller.php&msg='.$msg;
					//wp_redirect($redirectTo);
					exit;
				}
			}
		}

		/**
		 * @Method name  get_sortable_columns
		 * @description  implement sorting on elments included in $sortable_columns array
		 */
		function get_sortable_columns()
		{
			//return;
			$sortable_columns = array(
				'booking_date_from' => array(
					'booking_date_from',
					false
				),
				'booking_date_to' => array(
					'booking_date_to',
					false
				),
				'booking_guests' => array(
					'booking_guests',
					false
				),
				'booking_status' => array(
					'booking_status',
					false
				),
				'booking_payment_status' => array(
					'booking_payment_status',
					false
				),
				'booking_created_on' => array(
					'booking_created_on',
					false
				),

			);
			return $sortable_columns;
		}
		/**
		 * @Method name  get_bulk_actions
		 * @description  implement bulk action included in $actions array
		 */
		function get_bulk_actions()
		{
			return array();
			$actions = array(
				'trash' => __('Trash', VRCALENDAR_PLUGIN_TEXT_DOMAIN)
			);
			return $actions;
		}

		/**
		 * @Method name  prepare_items
		 * @description  ready data to display
		 */
		function prepare_items()
		{
			
			global $wpdb;
			$booking_table = $wpdb->prefix."vrcalandar_bookings";
			$booking_per_page   = 20;
			//retrive all calendar  from database

			if(isset($_GET['fillter']))
			{
				if($_GET['fillter'] == 'all')
				{
					$booking_query = "SELECT * FROM {$booking_table} where booking_calendar_id='{$_GET['cal_id']}'";
				}else{
				   $booking_query = "SELECT * FROM {$booking_table} where booking_source='".$_GET['fillter']."' and booking_calendar_id='{$_GET['cal_id']}'";
				}
			}else{
				
				$booking_query = "SELECT * FROM {$booking_table} where booking_calendar_id='{$_GET['cal_id']}'";
			}

			//$booking_query = "SELECT * FROM {$booking_table} where booking_source='website' and booking_calendar_id='{$_GET['cal_id']}'";

			
			
			$booking_data = $wpdb->get_results($booking_query, ARRAY_A);
			usort($booking_data, array( &$this, 'sort_data' ) );
			$columns   = $this->get_columns();
			$sortable  = $this->get_sortable_columns();
			$this->process_bulk_action();
			$this->_column_headers = array(
				$columns,
				array(),
				$sortable
			);

			//pagging code starts from here
			$current_page = $this->get_pagenum();
			$total_cal = count($booking_data);
			$booking_data = array_slice(
				$booking_data,(
				($current_page-1)*$booking_per_page
			),$booking_per_page
			);
			$this->items = $booking_data;

			$this->set_pagination_args(
				array(
					'total_items'=>$total_cal,
					'per_page'=> $booking_per_page,
					'total_pages'=>ceil($total_cal/$booking_per_page)
				)
			);
			//pagging code ends from here
		}

		/**
		 * @Method name  sort_data
		 * @params $a $b
		 * @description  sort product member data
		 */
		public function sort_data($a, $b)
		{
			// Set defaults
			$orderby = 'booking_created_on';
			$order   = 'asc';
			// If orderby is set, use this as the sort column
			if (!empty($_GET['orderby']))
			{
				$orderby = $_GET['orderby'];
			}
			// If order is set use this as the order
			if (!empty($_GET['order']))
			{
				$order = $_GET['order'];
			}
			$result = strnatcmp($a[$orderby], $b[$orderby]);
			if ($order =='asc')
			{
				return $result;
			}
			return -$result;
		}
}


$action = @$_GET['action'];
if($action == 'editbooking'){
	$bid = $_GET['bid'];  
	require(VRCALENDAR_PLUGIN_DIR.'/FrontAdmin/Views/EditBooking.php');
}else{
	?>
	<h2>
    <?php _e('Bookings for Calendar', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?> "<?php echo $booking_data->calendar_name ?>"
</h2>
	<div class="left-panel-vr-plg">
		<?php include('sidebar.php'); ?>
	</div>
	<div class="right-panel-vr-plg">
	<strong>Filter:</strong> <select name="select_fillter" id="select_fillter">
		<option <?php if($_GET['fillter'] == 'all'){ echo 'selected="selected"'; } ?> value= 'all'>All</option>
		<option <?php if($_GET['fillter'] == 'website'){ echo 'selected="selected"'; } ?>  value= 'website'>Web Site Booking</option>
		<?php
		if(count($cdata->calendar_links) > 0)
		{
			foreach($cdata->calendar_links as $cdatas)
			{
				
				if($cdatas->name != '')
				{
					$selected = '';
					if($_GET['fillter'] == $cdatas->url)
					{
						$selected = 'selected="selected"';
					}
				?>
				<option <?php echo $selected; ?> value= '<?php echo $cdatas->url; ?>'><?php echo $cdatas->name; ?></option>
			<?php
				}
			}
		}
		?>
	</select>
	<script>
			
		jQuery('#select_fillter').change(function(){
			var selectfillder = jQuery(this).val();
			var pathname = window.location.pathname;
			var page = '<?php echo $_GET['page']; ?>';
			var view = '<?php echo $_GET['view']; ?>';
			var cal_id = '<?php echo $_GET['cal_id']; ?>';
			var fillter = selectfillder;
			
			var urlget = pathname+'?page='+page+'&view='+view+'&cal_id='+cal_id+'&fillter='+fillter;
			window.location = urlget;
		})
	</script>

	<form id="my-calendars" name=my-calendars" method="post" action="">
	<?php
		$VRBookingsTable = new VRBookingsTable();
		$VRBookingsTable->prepare_items();
		$VRBookingsTable->display();
		$VRBookingsTable->process_bulk_action();
	?>
	</form>
	</div>
		</div>
	<?php
	
}