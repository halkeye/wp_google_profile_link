<?php
/*
Plugin Name: Google Profile Link
Plugin URI: http://www.kodekoan.com/project/google_profile_link
Description: Does some stuff
Version: 0.1
Author: Gavin Mogan
Author URI: http://www.halkeye.net/
*/

define('GOOGLEPROFILELINK_NONCE', 'google_profile_link_nonce');

define('GOOGLEPROFILELINK_USERNAME_OPTION', 'google_profile_link_username');

function google_profile_link_reset_settings() {
	delete_option(GOOGLEPROFILELINK_USERNAME_OPTION);
}

function google_profile_link_has_settings() {
	$username = get_option(GOOGLEPROFILELINK_USERNAME_OPTION);
	return !empty($username);
}

add_action('init', 'google_profile_link_init');

function google_profile_link_init() {
	add_action('admin_menu', 'google_profile_link_admin_menu');
	add_action('wp_head', 'google_profile_link_head', 0);
}

function google_profile_link_admin_menu() {
	add_options_page(__('GoogleProfileLink Settings'), __('Google Profile'), 8, __FILE__, 'google_profile_link_settings');
}

function google_profile_link_settings() {
	if (isset($_POST['submit'])) {
		if (!current_user_can('manage_options')) {
			die(__('Unauthorized access!'));
		}
		check_admin_referer(GOOGLEPROFILELINK_NONCE);
		if (isset($_POST['google_profile_link_username'])) {
			$username = $_POST['google_profile_link_username'];
			if (empty($username)) {
				delete_option(GOOGLEPROFILELINK_USERNAME_OPTION);
			} else {
				update_option(GOOGLEPROFILELINK_USERNAME_OPTION, $username);
			}
		}
?>
<div id="google_profile_link_warning" class="updated fade">
	<p><strong><?php _e('GoogleProfileLink:'); ?></strong> <?php _e('Settings saved!'); ?></p>
</div>
<?php
	}

	$username = get_option(GOOGLEPROFILELINK_USERNAME_OPTION);

?>
<div class="wrap">
	<h2><?php _e('GoogleProfileLink Settings'); ?></h2>
	<div id="poststuff" class="metabox-holder">
		<form name="form0" method="post" action=""><?php wp_nonce_field(GOOGLEPROFILELINK_NONCE); ?>
			<div class="postbox open">
				<h3 class="hndle"><?php _e('Google Profile Info'); ?></h3>
				<div class="inside">
					<table class="form-table">
						<tr>
							<th><label for="google_profile_link_username"><?php _e('Username:'); ?></label></th>
							<td><input type="text" id="google_profile_link_username" name="google_profile_link_username" value="<?php echo $username; ?>" /></td>
						</tr>
					</table>
				</div>
			</div>
			<p class="submit">
				<input type="submit" name="submit" value="<?php _e('Save Settings'); ?>" class="button-primary" />
			</p>
		</form>	
	</div>
</div>
<?php
}

if (!google_profile_link_has_settings() && !isset($_POST['submit'])) {
	add_action('admin_notices', 'google_profile_link_admin_notices');
}

function google_profile_link_admin_notices() {
?>
<div id="google_profile_link_warning" class="updated fade">
	<p><strong><?php _e('GoogleProfileLink:'); ?></strong> <?php _e('You need to configure the plugin in order to start utilizing it!'); ?></p>
</div>
<?php
}

function google_profile_link_head() {
	if (google_profile_link_has_settings()) {
		$username = get_option(GOOGLEPROFILELINK_USERNAME_OPTION);
?>
<!-- GoogleProfileLink Plugin - Start -->
<link rel="me" href="http://www.google.com/profiles/<?php echo htmlentities($username); ?>" />
<!-- GoogleProfileLink Plugin - End -->
<?php
	}
}

?>
