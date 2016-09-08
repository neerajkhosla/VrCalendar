<?php
require_once('wp_table_list.php');
class VRCalendarTableFront extends WP_List_Table_CS
{
    public $page;
    function __construct()
    {        
        parent::__construct();
        global $post;
        $page = $post->post_name;
    }
    /**
     * @Method name  column_default
     * @Params       $cal,$column_name
     * @description  display static column name and corrosponding value
     */
    function column_default($cal, $column_name)
    {
        /* display all dynamic data from database  */
        switch ($column_name)
        {
            case 'title':
                echo  $cal['calendar_name'];
                break;
            case 'calendar_shortcode':
              //  echo  '[vrcalendar id="'.$cal['calendar_id'].'" /]';
			  $api_key= base64_encode($cal['calendar_id']."--".uniqid());
			  $script= htmlspecialchars("<script id='vr_".$api_key."'>window.options = { api_key:'".$api_key."',height:'100%',width:'100%'};var s = document.createElement('script');s.src = \"".site_url()."/embed.js\";s.async = true; document.body.appendChild(s);</script>");
			  
			  $calid=$cal["calendar_id"];

               echo '<a class="btn" data-popup-open="popup-'.$cal['calendar_id'].'" href="#">Get Embed Code</a><div class="popup" data-popup="popup-'.$cal['calendar_id'].'"><div class="popup-inner"><h4 class="embed-heading">Embed Code for '.$cal['calendar_name'].'</h4><p class="embed-section" ><span class="response" style="display:none;"></span><code id="'.$cal['calendar_id'].'">'.$script.'</code><button type="button" onclick=copyToClipboard("#'.$calid.'")>copy to clipboard</button></p><a class="popup-close" data-popup-close="popup-'.$cal['calendar_id'].'" href="#">x</a></div></div>';
                break;
            case 'booking_shortcode':
                echo  '[vrcalendar_booking_btn id="'.$cal['calendar_id'].'" class=""]'.__('Book Now', VRCALENDAR_PLUGIN_TEXT_DOMAIN).'[/vrcalendar_booking_btn]';
                break;
            case 'author':
                echo   get_the_author_meta( 'display_name', $cal['calendar_author_id'] );
                break;
            case 'last_synchronized':
                echo  date('F d, Y \a\t h:i a', strtotime($cal['calendar_last_synchronized']));
                break;
            case 'created_on':
                echo  date('Y-m-d', strtotime($cal['calendar_created_on']));
                break;
            default:
                return $cal->$column_name;
        }
    }
    /**
     * @Method name  column_name
     * @Params       $cal
     * @description  display static column name and corrosponding value
     */
    function column_title($cal)
    {
        global $post;
        $post->post_name;
        $actions = array(
           'edit' => '<a href="' .site_url($post->post_name.'/?page='.VRCALENDAR_PLUGIN_SLUG.'-add-calendar&cal_id='.$cal['calendar_id']). '&vrc_slug='.$post->post_name.'" title="Edit" class="dashboard-icons"><i class="fa fa-edit"></i></a>',
            'delete' => '<a href="' .site_url($post->post_name.'/?page='.VRCALENDAR_PLUGIN_SLUG.'-dashboard&vrc_cmd=VRCalendarFrontAdmin:deleteCalendar&cal_id='.$cal['calendar_id']). '&vrc_slug='.$post->post_name.'" title="Delete" class="dashboard-icons" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"></i></a>',
            'clone' => '<a href="' .site_url($post->post_name.'/?page='.VRCALENDAR_PLUGIN_SLUG.'-dashboard&vrc_cmd=VRCalendarFrontAdmin:cloneCalendar&cal_id='.$cal['calendar_id']). '&vrc_slug='.$post->post_name.'"title="Clone" class="dashboard-icons"><i class="fa fa-clone"></i></a>',
            'bookings' => '<a href="' .site_url($post->post_name.'/?page='.VRCALENDAR_PLUGIN_SLUG.'-dashboard&view=bookings&cal_id='.$cal['calendar_id']).'&vrc_slug='.$post->post_name.'"title="Booking" class="dashboard-icons"><i class="fa fa-calendar"></i></a>',
            'export_ical' => '<a href="' .add_query_arg(array('vrc_pcmd'=>'ical','cal_id'=>$cal['calendar_id']), site_url()).'"title="Export" class="dashboard-icons"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>',
            'sync' => '<a href="' .site_url($post->post_name.'/?page='.VRCALENDAR_PLUGIN_SLUG.'-dashboard&vrc_cmd=VRCalendarFrontAdmin:syncCalendar&cal_id='.$cal['calendar_id']).'&vrc_slug='.$post->post_name.'"title="Sync" class="dashboard-icons"><i class="fa fa-refresh"></i></a>'
        );
        return $cal['calendar_name'].$this->row_actions($actions) ;
    }

    /**
     * @Method name  column_cb
     * @Params       $cal
     * @description  display check box for all Calendar data value
     */
    function column_cb($cal)
    {
        return '<input type="checkbox" name="check[]" value="'.$cal['calendar_id'].'" />';
    }

    /**
     * @Method name  get_columns
     * @description  display head tr for table
     */
    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox"/>',
            'title' => __('Title', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            'calendar_shortcode' =>__('Calendar Shortcode', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            'created_on' => __('Date', VRCALENDAR_PLUGIN_TEXT_DOMAIN)
        );
		/* 'booking_shortcode' =>__('Booking Shortcode', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'author'=> __('Author', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
		'last_synchronized'=> __('Last Sync', VRCALENDAR_PLUGIN_TEXT_DOMAIN), */
		return $columns;
    }

    function process_bulk_action()
    {
		
		global $post; 
        extract($_REQUEST);
        if(isset($check))
        {
            if( 'trash'===$this->current_action() )
            {
                $msg = 'Calendars deleted successfully';
                global $wpdb;
                $calendar_table = $wpdb->prefix."vrcalandar";
                foreach($check as $cal_id)
                {
                    $cal_query = "delete  FROM ".$calendar_table." where calendar_id='".$cal_id."' ";
                    $wpdb->query($cal_query);
                }
                $redirectTo = site_url($post->post_name.'/?page='.VRCALENDAR_PLUGIN_SLUG.'-dashboard');
				$_SESSION['vrc_msg']=$msg;
				echo '<script>window.location = "'.$redirectTo.'"</script>'; 
				exit;
                //exit;
            }
        }
    }

    /**
     * @Method name  get_sortable_columns
     * @description  implement sorting on elments included in $sortable_columns array
     */
    function get_sortable_columns()
    {
        $sortable_columns = array(
            'title' => array(
                'calendar_name',
                false
            ),
            'created_date' => array(
                'created_date',
                false
            )
        );
        return $sortable_columns;
    }
    /**
     * @Method name  get_bulk_actions
     * @description  implement bulk action included in $actions array
     */
    function get_bulk_actions()
    {
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
        $user_id = get_current_user_id();
        $calendar_table = $wpdb->prefix."vrcalandar";
        $cal_per_page   = 4;
        //retrive all calendar  from database

        $cal_query = "SELECT * FROM {$calendar_table} WHERE calendar_author_id = ".$user_id." order by calendar_created_on DESC";
        $calendar_data = $wpdb->get_results($cal_query, ARRAY_A);
        $columns   = $this->get_columns();
        $sortable  = $this->get_sortable_columns();
        $this->process_bulk_action();
        $this->_column_headers = array(
            $columns,
            array(),
            $sortable
        );


        $current_page = $this->get_pagenum();
        $total_cal = count($calendar_data);
        $calendar_data = array_slice(
            $calendar_data,(
                ($current_page-1)*$cal_per_page
            ),$cal_per_page
        );
        $this->items = $calendar_data;
        $this->set_pagination_args(
            array(
                'total_items'=>$total_cal,
                'per_page'=> $cal_per_page,
                'total_pages'=>ceil($total_cal/$cal_per_page)
            )
        );
        //pagging code ends from here
    }
	public function get_pagenum() {
		
		$pagenum = get_query_var( 'paged') ? absint( get_query_var( 'paged') ) : 0;
	 
		if ( isset( $this->_pagination_args['total_pages'] ) && $pagenum > $this->_pagination_args['total_pages'] )
			$pagenum = $this->_pagination_args['total_pages'];
	 
		return max( 1, $pagenum );
	}
    /**
     * @Method name  sort_data
     * @params $a $b
     * @description  sort product member data
     */
    public function sort_data($a, $b)
    {
        // Set defaults
        $orderby = 'calendar_name';
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
        $result = strnatcmp($a->$orderby, $b->$orderby);
        if ($order =='asc')
        {
            return $result;
        }
        return -$result;
    }
}
?>
<div class="cont-fuild vr-dashboard">
	<div class="left-panel-vr-plg">
		<?php include(VRCALENDAR_PLUGIN_DIR.'/FrontAdmin/Views/sidebar.php');?>
	</div>
	<div class="right-panel-vr-plg">
		<h2>
			<?php _e('My Calendars', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?> <a href="<?php global $post;  echo site_url($post->post_name.'/?page='.VRCALENDAR_PLUGIN_SLUG.'-add-calendar'.'&vrc_slug='.$post->post_name) ?>" class="add-new-h2"><i class="fa fa-calendar-plus-o" aria-hidden="true"></i>
 <?php _e('Add new', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></a>
		</h2>
		<form id="my-calendars" name="my-calendars" method="post" action="">

			<?php
			$VRCalendarTable = new VRCalendarTableFront();
			$VRCalendarTable->prepare_items();
			$VRCalendarTable->display();
			$VRCalendarTable->process_bulk_action();
			?>
		</form>
	</div>
	<script>
	jQuery(".manage-column input").change(function(){  //"select all" change
    jQuery("tbody .check-column > input").prop('checked', jQuery(this).prop("checked")); //change all ".checkbox" checked status
	});
            jQuery(function() {
        //----- OPEN
        jQuery('[data-popup-open]').on('click', function(e)  {
            var targeted_popup_class = jQuery(this).attr('data-popup-open');
            jQuery('[data-popup="' + targeted_popup_class + '"]').fadeIn(350);

            e.preventDefault();
        });

        //----- CLOSE
        jQuery('[data-popup-close]').on('click', function(e)  {
            var targeted_popup_class = jQuery(this).attr('data-popup-close');
            jQuery('[data-popup="' + targeted_popup_class + '"]').fadeOut(350);

            e.preventDefault();
        });
    });
	</script>
	<script>
	function copyToClipboard(element) {
	
	  var $temp = jQuery("<input>");
	  jQuery("body").append($temp);
	  $temp.val(jQuery(element).text()).select();
	  document.execCommand("copy");
	  $temp.remove();
	  jQuery(".response").show();
	  jQuery(".response").html('Copied').delay(2000).fadeOut('fast');
	}
	jQuery(document).ready(function(){	
		jQuery('.bulkactions .action').prop("disabled",true);
		jQuery('.bulkactions select').on('change',function(){
				if(jQuery(this).val() == 'trash'){
					jQuery('.bulkactions .action').prop("disabled",false);
				}
				else{
				jQuery('.bulkactions .action').prop("disabled",true);
				}
		});
		jQuery(".button.action").on('click',function(){
			if(jQuery('.check-column input[type="checkbox"]:checked').length) {
				return true;
			} else {
				alert("Please select at least one item !");
				return false;
			}  
		});

	});
	</script>
</div>
