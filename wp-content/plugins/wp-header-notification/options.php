<?php
function wp_header_notification_register_settings() {
	add_option('wp_header_notification_status', "open");
	add_option('wp_header_notification_text', __("Hi! Thanks for using WP Header Notification by Arefly!", WP_HEADER_NOTIFICATION_TEXT_DOMAIN));
	add_option('wp_header_notification_button_value', __('See This Plguin Now!', WP_HEADER_NOTIFICATION_TEXT_DOMAIN));
	add_option('wp_header_notification_button_link', 'http://wordpress.org/plugins/wp-header-notification/');
	add_option('wp_header_notification_close_value', __('Close', WP_HEADER_NOTIFICATION_TEXT_DOMAIN));
	add_option('wp_header_notification_renotice_time', '00 10');
	register_setting('wp_header_notification_options', 'wp_header_notification_status');
	register_setting('wp_header_notification_options', 'wp_header_notification_text');
	register_setting('wp_header_notification_options', 'wp_header_notification_button_value');
	register_setting('wp_header_notification_options', 'wp_header_notification_button_link');
	register_setting('wp_header_notification_options', 'wp_header_notification_close_value');
	register_setting('wp_header_notification_options', 'wp_header_notification_renotice_time');
}
add_action('admin_init', 'wp_header_notification_register_settings');

function wp_header_notification_register_options_page() {
	add_options_page(__('WP Header Notification Options Page', WP_HEADER_NOTIFICATION_TEXT_DOMAIN), __('WP Header Notification', WP_HEADER_NOTIFICATION_TEXT_DOMAIN), 'manage_options', WP_HEADER_NOTIFICATION_TEXT_DOMAIN.'-options', 'wp_header_notification_options_page');
}
add_action('admin_menu', 'wp_header_notification_register_options_page');

function wp_header_notification_get_select_option($select_option_name, $select_option_value, $select_option_id){
	?>
	<select name="<?php echo $select_option_name; ?>" id="<?php echo $select_option_name; ?>">
		<?php
		for($num = 0; $num < count($select_option_id); $num++){
			$select_option_value_each = $select_option_value[$num];
			$select_option_id_each = $select_option_id[$num];
			?>
			<option value="<?php echo $select_option_id_each; ?>"<?php if (get_option($select_option_name) == $select_option_id_each){?> selected="selected"<?php } ?>>
				<?php echo $select_option_value_each; ?>
			</option>
		<?php } ?>
	</select>
	<?php
}

function wp_header_notification_options_page() {
?>
<div class="wrap">
	<h2><?php _e("WP Header Notification Options Page", WP_HEADER_NOTIFICATION_TEXT_DOMAIN); ?></h2>
	<form method="post" action="options.php">
		<?php settings_fields('wp_header_notification_options'); ?>
		<h3><?php _e("General Options", WP_HEADER_NOTIFICATION_TEXT_DOMAIN); ?></h3>
			<p><?php printf(__('You can use %s to get WP Header Notification too!', WP_HEADER_NOTIFICATION_TEXT_DOMAIN), '<code>get_wp_header_notification()</code>'); ?></p>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="wp_header_notification_status"><?php _e("Open Header Notification?", WP_HEADER_NOTIFICATION_TEXT_DOMAIN); ?></label></th>
					<td>
						<?php wp_header_notification_get_select_option("wp_header_notification_status", array(__('Open', WP_HEADER_NOTIFICATION_TEXT_DOMAIN), __('Close', WP_HEADER_NOTIFICATION_TEXT_DOMAIN)), array('open', 'close')); ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="wp_header_notification_text"><?php _e("Notification's Content: ", WP_HEADER_NOTIFICATION_TEXT_DOMAIN); ?></label></th>
					<td>
						<input type="text" name="wp_header_notification_text" id="wp_header_notification_text" value="<?php echo get_option('wp_header_notification_text'); ?>" size="40" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="wp_header_notification_button_value"><?php _e("Read Now Button's Value: ", WP_HEADER_NOTIFICATION_TEXT_DOMAIN); ?></label></th>
					<td>
						<input type="text" name="wp_header_notification_button_value" id="wp_header_notification_button_value" value="<?php echo get_option('wp_header_notification_button_value'); ?>" size="40" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="wp_header_notification_button_link"><?php _e('Link Read Now Button To: ', WP_HEADER_NOTIFICATION_TEXT_DOMAIN); ?></label></th>
					<td>
						<input type="url" name="wp_header_notification_button_link" id="wp_header_notification_button_link" value="<?php echo get_option('wp_header_notification_button_link'); ?>" size="60" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="wp_header_notification_close_value"><?php _e("Close Button's Value: ", WP_HEADER_NOTIFICATION_TEXT_DOMAIN); ?></label></th>
					<td>
						<input type="text" name="wp_header_notification_close_value" id="wp_header_notification_close_value" value="<?php echo get_option('wp_header_notification_close_value'); ?>" size="40" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="wp_header_notification_renotice_time"><?php _e('Notice again for: ', WP_HEADER_NOTIFICATION_TEXT_DOMAIN); ?></label></th>
					<td>
						<input type="text" name="wp_header_notification_renotice_time" id="wp_header_notification_renotice_time" pattern="[0-9]{2} [0-9]{2}" title="<?php _e("MM DD", WP_HEADER_NOTIFICATION_TEXT_DOMAIN); ?>" value="<?php echo get_option('wp_header_notification_renotice_time'); ?>" />
						<?php _e("Use format of MM DD", WP_HEADER_NOTIFICATION_TEXT_DOMAIN); ?>
					</td>
				</tr>
			</table>
		<?php submit_button(); ?>
	</form>
</div>
<?php
}
?>