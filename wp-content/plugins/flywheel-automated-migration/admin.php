<?php
global $blogvault;
global $bvNotice;
global $bvFlywheelAdminPage;
$bvNotice = '';
$bvFlywheelAdminPage = 'flywheel-automated-migration';

if (!function_exists('bvFlywheelAdminUrl')) :
	function bvFlywheelAdminUrl($_params = '') {
		global $bvFlywheelAdminPage;
		if (function_exists('network_admin_url')) {
			return network_admin_url('admin.php?page='.$bvFlywheelAdminPage.$_params);
		} else {
			return admin_url('admin.php?page='.$bvFlywheelAdminPage.$_params);
		}
	}
endif;

if (!function_exists('bvAddStyleSheet')) :
	function bvAddStyleSheet() {
		wp_register_style('form-styles', plugins_url('form-styles.css',__FILE__ ));
		wp_enqueue_style('form-styles');
	}
add_action( 'admin_init','bvAddStyleSheet');
endif;

if (!function_exists('bvFlywheelAdminInitHandler')) :
	function bvFlywheelAdminInitHandler() {
		global $bvNotice, $blogvault, $bvFlywheelAdminPage;
		global $sidebars_widgets;
		global $wp_registered_widget_updates;

		if (!current_user_can('activate_plugins'))
			return;

		if (isset($_REQUEST['bvnonce']) && wp_verify_nonce($_REQUEST['bvnonce'], "bvnonce")) {
			if (isset($_REQUEST['blogvaultkey']) && isset($_REQUEST['page']) && $_REQUEST['page'] == $bvFlywheelAdminPage) {
				if ((strlen($_REQUEST['blogvaultkey']) == 64)) {
					$keys = str_split($_REQUEST['blogvaultkey'], 32);
					$blogvault->updatekeys($keys[0], $keys[1]);
					bvActivateHandler();
					$bvNotice = "<b>Activated!</b> blogVault is now backing up your site.<br/><br/>";
					if (isset($_REQUEST['redirect'])) {
						$location = $_REQUEST['redirect'];
						wp_redirect("https://webapp.blogvault.net/migration/".$location);
						exit();
					}
				} else {
					$bvNotice = "<b style='color:red;'>Invalid request!</b> Please try again with a valid key.<br/><br/>";
				}
			}
		}

		if ($blogvault->getOption('bvActivateRedirect') === 'yes') {
			$blogvault->updateOption('bvActivateRedirect', 'no');
			wp_redirect(bvFlywheelAdminUrl());
		}
	}
	add_action('admin_init', 'bvFlywheelAdminInitHandler');
endif;

if (!function_exists('bvFlywheelAdminMenu')) :
	function bvFlywheelAdminMenu() {
		global $bvFlywheelAdminPage;
		add_menu_page('Flywheel Migrate', 'Flywheel Migrate', 'manage_options', $bvFlywheelAdminPage, 'bvFlywheelMigrate', plugins_url( 'icon.png', __FILE__ ));
	}
	if (function_exists('is_multisite') && is_multisite()) {
		add_action('network_admin_menu', 'bvFlywheelAdminMenu');
	} else {
		add_action('admin_menu', 'bvFlywheelAdminMenu');
	}
endif;

if ( !function_exists('bvSettingsLink') ) :
	function bvSettingsLink($links, $file) {
		if ( $file == plugin_basename( dirname(__FILE__).'/blogvault.php' ) ) {
			$links[] = '<a href="'.bvFlywheelAdminUrl().'">'.__( 'Settings' ).'</a>';
		}
		return $links;
	}
	add_filter('plugin_action_links', 'bvSettingsLink', 10, 2);
endif;

if ( !function_exists('bvFlywheelMigrate') ) :
	function bvFlywheelMigrate() {
		global $blogvault, $bvNotice;
		$_error = NULL;
		if (array_key_exists('error', $_REQUEST)) {
			$_error = $_REQUEST['error'];
		}
?>
		<div class="logo-container" style="padding: 50px 0px 10px 20px">
			<a href="https://getflywheel.com/" style="padding-right: 20px;"><img src="<?php echo plugins_url('flywheel-logo.png', __FILE__); ?>" /></a>
			<a href="http://blogvault.net/"><img src="<?php echo plugins_url('logo.png', __FILE__); ?>" /></a>
		</div>

		<div id="wrapper toplevel_page_flywheel-automated-migration">
			<form id="flywheel_migrate_form" dummy=">" action="https://webapp.blogvault.net/home/migrate" style="padding:0 2% 2em 1%;" method="post" name="signup">
				<h1>Migrate Your Site to Flywheel</h1>
				<p><font size="3">The Flywheel Automated Migration plugin makes it very easy to migrate your entire site from your previous hosting provider to Flywheel.</font></p>
				<p style="font-size: 11px;">This plugin is currently in beta testing.<p>
<?php if ($_error == "email") { 
	echo '<div class="error" style="padding-bottom:0.5%;"><p>There is already an account with this email.</p></div>';
} else if ($_error == "blog") {
	echo '<div class="error" style="padding-bottom:0.5%;"><p>Could not create an account. Please contact <a href="http://blogvault.net/contact/">blogVault Support</a></p></div>';
} else if (($_error == "custom") && isset($_REQUEST['bvnonce']) && wp_verify_nonce($_REQUEST['bvnonce'], "bvnonce")) {
	echo '<div class="error" style="padding-bottom:0.5%;"><p>'.base64_decode($_REQUEST['message']).'</p></div>';
}
?>
				<input type="hidden" name="bvsrc" value="wpplugin" />
				<input type="hidden" name="migrate" value="flywheel" />
				<input type="hidden" name="type" value="sftp" />
				<input type="hidden" name="setkeysredirect" value="true" />
				<input type="hidden" name="address" value="sftp.flywheelsites.com" />
				<input type="hidden" name="url" value="<?php echo $blogvault->wpurl(); ?>" />
				<input type="hidden" name="secret" value="<?php echo $blogvault->getOption('bvSecretKey'); ?>">
				<input type='hidden' name='bvnonce' value='<?php echo wp_create_nonce("bvnonce") ?>'>
				<input type='hidden' name='serverip' value='<?php echo $_SERVER["SERVER_ADDR"] ?>'>
				<input type='hidden' name='adminurl' value='<?php echo bvFlywheelAdminUrl(); ?>'>
				<input type="hidden" name="multisite" value="<?php var_export($blogvault->isMultisite()); ?>" />
				<div class="row-fluid">
					<div class="span5" style="border-right: 1px solid #EEE; padding-top:1%;">
						<label id='label_email'>Email</label>
			 			<div class="control-group">
							<div class="controls">
								<input type="text" id="email" name="email" placeholder="ex. user@mydomain.com">
							</div>
						</div>
						<label class="control-label" for="input02">Destination Site URL</label>
						<div class="control-group">
							<div class="controls">
								<input type="text" class="input-large" name="newurl" placeholder="http://example.flywheel.com">
							</div>
						</div>
						<label class="control-label" for="input02">Flywheel URL</label>
            <div class="control-group">
              <div class="controls">
                <input type="text" class="input-large" name="flypath" placeholder="https://app.getflywheel.com/user/site">
              </div>
            </div>
						<label class="control-label" for="input01">sFTP User</label>
						<div class="control-group">
							<div class="controls">
								<input type="text" class="input-large" placeholder="ex. installname" name="username">
								<p class="help-block"></p>
							</div>
						</div>
						<label class="control-label" for="input02">sFTP Password</label>
						<div class="control-group">
							<div class="controls">
								<input type="password" class="input-large" name="passwd">
							</div>
						</div>
						<label class="control-label" for="input02" style="color:red">User <small>(for this site)</small></label>
							<div class="control-group">
								<div class="controls">
									<input type="text" class="input-large" name="httpauth_dest_user">
								</div>
							</div>
						<label class="control-label" for="input02" style="color:red">Password <small>(for this site)</small></label>
							<div class="control-group">
								<div class="controls">
									<input type="password" class="input-large" name="httpauth_dest_password">
								</div>
							</div>
						</div>
					</div>
					<p style="font-size: 11px;">By pressing the "Migrate" button, you are agreeing to <a href="http://blogvault.net/tos/">BlogVault's Terms of Service</a></p>
				</div>
				<input type='submit' value='Migrate' class="button button-primary">
			</form>
		</div> <!-- wrapper ends here -->
<?php
	}
endif;