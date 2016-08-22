<div class="menu-list">
    <ul>
        <li class="<?php if(isset($_GET['page']) &&$_GET['page']=="vr-calendar-dashboard"){ echo "active"; } ?>"><a href="<?php global $post;  echo site_url($post->post_name."?page=vr-calendar-dashboard"); ?>" ><i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard</a></li>
        <li class="<?php if(isset($_GET['page']) && $_GET['page']=="vr-calendar-add-calendar"){ echo "active"; } ?>"><a href="<?php global $post;  echo site_url($post->post_name."?page=vr-calendar-add-calendar"); ?>" ><i class="fa fa-calendar-plus-o" aria-hidden="true"></i>
         Add Calendar</a></li>
        <li><a href="#"><i class="fa fa-search" aria-hidden="true"></i> Add Search Bar</a></li>
        <li class="<?php if(isset($_GET['page']) && $_GET['page']=="vr-calendar-settings"){ echo "active"; } ?>"><a href="<?php global $post;  echo site_url($post->post_name."?page=vr-calendar-settings"); ?>" ><i class="fa fa-cogs" aria-hidden="true"></i> Settings</a></li>
        <li><a href="<?php echo wp_logout_url('http://vrcalendar.customerdemourl.com/user-login/'); ?>"><i class="fa fa-sign-out" aria-hidden="true"></i>Logout </a></li>
    </ul>
</div>