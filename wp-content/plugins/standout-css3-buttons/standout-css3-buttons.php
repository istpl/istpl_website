<?php
/*
Plugin Name: Standout CSS3 Buttons
Plugin URI: http://www.jimmyscode.com/wordpress/standout-css3-buttons/
Description: Display CSS3 style buttons with gradient color styles on your website using popular social media colors.
Version: 0.2.2
Author: Jimmy Pe&ntilde;a
Author URI: http://www.jimmyscode.com/
License: GPLv2 or later
*/

define('SCSS3B_PLUGIN_NAME', 'Standout CSS3 Buttons');
	// plugin constants
	define('SCSS3B_VERSION', '0.2.2');
	define('SCSS3B_SLUG', 'standout-css3-buttons');
	define('SCSS3B_LOCAL', 'scss3b');
	define('SCSS3B_OPTION', 'scss3b');
	define('SCSS3B_OPTIONS_NAME', 'scss3b_options');
	define('SCSS3B_PERMISSIONS_LEVEL', 'manage_options');
	define('SCSS3B_PATH', plugin_basename(dirname(__FILE__)));
	/* default values */
	define('SCSS3B_DEFAULT_ENABLED', true);
	define('SCSS3B_DEFAULT_STYLE', '');
	define('SCSS3B_DEFAULT_URL', '');
	define('SCSS3B_DEFAULT_CUSTOM_CSS', '');
	define('SCSS3B_DEFAULT_NOFOLLOW', true);
	define('SCSS3B_DEFAULT_SHOW', false);
	define('SCSS3B_DEFAULT_NEWWINDOW', false);
	define('SCSS3B_AVAILABLE_STYLES', 'button-dribbble,button-facebook,button-googleplus,button-linkedin,button-pinterest,button-rss,button-tumblr,button-twitter,button-turquoise,button-emerald,button-somekindofblue,button-amethyst,button-bluegray,button-tangerine,button-fall,button-adobe,button-lightgray,button-dull,button-fancypurple,button-dullpurple,button-crispblue,button-braised,button-midnight,button-salmon,button-neongreen,button-brown,button-sourgreen');
	/* option array member names */
	define('SCSS3B_DEFAULT_ENABLED_NAME', 'enabled');
	define('SCSS3B_DEFAULT_STYLE_NAME', 'cssclass');
	define('SCSS3B_DEFAULT_URL_NAME', 'href');
	define('SCSS3B_DEFAULT_NOFOLLOW_NAME', 'nofollow');
	define('SCSS3B_DEFAULT_SHOW_NAME', 'show');
	define('SCSS3B_DEFAULT_NEWWINDOW_NAME', 'opennewwindow');
	define('SCSS3B_DEFAULT_CUSTOM_CSS_NAME', 'customcss');

	// oh no you don't
	if (!defined('ABSPATH')) {
		wp_die(__('Do not access this file directly.', SCSS3B_LOCAL));
	}

	// delete option when plugin is uninstalled
	register_uninstall_hook(__FILE__, 'uninstall_scss3b_plugin');
	function uninstall_scss3b_plugin() {
		delete_option(SCSS3B_OPTION);
	}

	// localization to allow for translations
	// also, register the plugin CSS file for later inclusion
	add_action('init', 'scss3b_translation_file');
	function scss3b_translation_file() {
		$plugin_path = plugin_basename(dirname(__FILE__) . '/translations');
		load_plugin_textdomain(SCSS3B_LOCAL, '', $plugin_path);
		register_scss3b_style();
	}
	// tell WP that we are going to use new options
	// also, register the admin CSS file for later inclusion
	add_action('admin_init', 'scss3b_options_init');
	function scss3b_options_init() {
		register_setting(SCSS3B_OPTIONS_NAME, SCSS3B_OPTION, 'scss3b_validation');
		register_scss3b_admin_style();
		register_scss3b_admin_script();
	}
	// validation function
	function scss3b_validation($input) {
		// sanitize url
		$input[SCSS3B_DEFAULT_URL_NAME] = esc_url($input[SCSS3B_DEFAULT_URL_NAME]);
		// sanitize style
		$input[SCSS3B_DEFAULT_STYLE_NAME] = sanitize_html_class($input[SCSS3B_DEFAULT_STYLE_NAME]);
		// sanitize custom css box
		$input[SCSS3B_DEFAULT_CUSTOM_CSS_NAME] = sanitize_text_field($input[SCSS3B_DEFAULT_CUSTOM_CSS_NAME]);

		return $input;
	}
	// add Settings sub-menu
	add_action('admin_menu', 'scss3b_plugin_menu');
	function scss3b_plugin_menu() {
		add_options_page(SCSS3B_PLUGIN_NAME, SCSS3B_PLUGIN_NAME, SCSS3B_PERMISSIONS_LEVEL, SCSS3B_SLUG, 'scss3b_page');
	}
	// plugin settings page
	// http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/
	// http://www.onedesigns.com/tutorials/how-to-create-a-wordpress-theme-options-page
	function scss3b_page() {
		// check perms
		if (!current_user_can(SCSS3B_PERMISSIONS_LEVEL)) {
			wp_die(__('You do not have sufficient permission to access this page', SCSS3B_LOCAL));
		}
		?>
		<div class="wrap">
			<h2 id="plugintitle"><img src="<?php echo plugins_url(plugin_basename(dirname(__FILE__) . '/images/colors.png')) ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php echo SCSS3B_PLUGIN_NAME; ?> by <a href="http://www.jimmyscode.com/">Jimmy Pe&ntilde;a</a></h2>
			<div>You are running plugin version <strong><?php echo SCSS3B_VERSION; ?></strong>.</div>
			<form method="post" action="options.php">
			<?php settings_fields(SCSS3B_OPTIONS_NAME); ?>
			<?php $options = scss3b_getpluginoptions(); ?>
			<?php update_option(SCSS3B_OPTION, $options); ?>
			<h3 id="settings"><img src="<?php echo plugins_url(plugin_basename(dirname(__FILE__) . '/images/settings.png')) ?>" title="" alt="" height="61" width="64" align="absmiddle" />Plugin Settings</h3>
				<?php submit_button(); ?>
				<table class="form-table" id="theme-options-wrap">
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Is plugin enabled? Uncheck this to turn it off temporarily.', SCSS3B_LOCAL); ?>" for="<?php echo SCSS3B_OPTION; ?>[<?php echo SCSS3B_DEFAULT_ENABLED_NAME; ?>]"><?php _e('Plugin enabled?', SCSS3B_LOCAL); ?></label></strong></th>
						<td><input type="checkbox" id="<?php echo SCSS3B_OPTION; ?>[<?php echo SCSS3B_DEFAULT_ENABLED_NAME; ?>]" name="<?php echo SCSS3B_OPTION; ?>[<?php echo SCSS3B_DEFAULT_ENABLED_NAME; ?>]" value="1" <?php checked('1', $options[SCSS3B_DEFAULT_ENABLED_NAME]); ?> /></td>
					</tr>
					<tr valign="top"><td colspan="2"><?php _e('Is plugin enabled? Uncheck this to turn it off temporarily.', SCSS3B_LOCAL); ?></td></tr>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Select the style you would like to use as the default.', SCSS3B_LOCAL); ?>" for="<?php echo SCSS3B_OPTION; ?>[<?php echo SCSS3B_DEFAULT_STYLE_NAME; ?>]"><?php _e('Default style', SCSS3B_LOCAL); ?></label></strong></th>
						<td><select id="<?php echo SCSS3B_OPTION; ?>[<?php echo SCSS3B_DEFAULT_STYLE_NAME; ?>]" name="<?php echo SCSS3B_OPTION; ?>[<?php echo SCSS3B_DEFAULT_STYLE_NAME; ?>]">
						<?php $buttonstyles = explode(",", SCSS3B_AVAILABLE_STYLES);
							sort($buttonstyles);
							foreach($buttonstyles as $buttonstyle) {
								echo '<option value="' . $buttonstyle . '"' . selected($buttonstyle, $options[SCSS3B_DEFAULT_STYLE_NAME], false) . '>' . $buttonstyle . '</option>';
							} ?>
						</select></td>
					</tr>
			<tr valign="top"><td colspan="2"><?php _e('Select the style you would like to use as the default if no style is otherwise specified.', SCSS3B_LOCAL); ?></td></tr>
			
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Enter default URL to use for buttons, if you do not pass one to the plugin via shortcode or function.', SCSS3B_LOCAL); ?>" for="<?php echo SCSS3B_OPTION; ?>[<?php echo SCSS3B_DEFAULT_URL_NAME; ?>]"><?php _e('Default button URL', SCSS3B_LOCAL); ?></label></strong></th>
			<td><input type="url" id="<?php echo SCSS3B_OPTION; ?>[<?php echo SCSS3B_DEFAULT_URL_NAME; ?>]" name="<?php echo SCSS3B_OPTION; ?>[<?php echo SCSS3B_DEFAULT_URL_NAME; ?>]" value="<?php echo $options[SCSS3B_DEFAULT_URL_NAME]; ?>" /></td>
					</tr>
			<tr valign="top"><td colspan="2"><?php _e('Enter default URL to use for buttons. This URL will be used if you do not override it at the shortcode level.', SCSS3B_LOCAL); ?></td></tr>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Check this box to add rel=nofollow to button links.', SCSS3B_LOCAL); ?>" for="<?php echo SCSS3B_OPTION; ?>[<?php echo SCSS3B_DEFAULT_NOFOLLOW_NAME; ?>]"><?php _e('Nofollow button link?', SCSS3B_LOCAL); ?></label></strong></th>
			<td><input type="checkbox" id="<?php echo SCSS3B_OPTION; ?>[<?php echo SCSS3B_DEFAULT_NOFOLLOW_NAME; ?>]" name="<?php echo SCSS3B_OPTION; ?>[<?php echo SCSS3B_DEFAULT_NOFOLLOW_NAME; ?>]" value="1" <?php checked('1', $options[SCSS3B_DEFAULT_NOFOLLOW_NAME]); ?> /></td>
					</tr>
			<tr valign="top"><td colspan="2"><?php _e('Check this box to add rel="nofollow" to button links. You can override this at the shortcode level.', SCSS3B_LOCAL); ?></td></tr>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Check this box to open links in a new window.', SCSS3B_LOCAL); ?>" for="<?php echo SCSS3B_OPTION; ?>[<?php echo SCSS3B_DEFAULT_NEWWINDOW_NAME; ?>]"><?php _e('Open links in new window?', SCSS3B_LOCAL); ?></label></strong></th>
			<td><input type="checkbox" id="<?php echo SCSS3B_OPTION; ?>[<?php echo SCSS3B_DEFAULT_NEWWINDOW_NAME; ?>]" name="<?php echo SCSS3B_OPTION; ?>[<?php echo SCSS3B_DEFAULT_NEWWINDOW_NAME; ?>]" value="1" <?php checked('1', $options[SCSS3B_DEFAULT_NEWWINDOW_NAME]); ?> /></td>
					</tr>
			<tr valign="top"><td colspan="2"><?php _e('Check this box to open links in a new window. You can override this at the shortcode level.', SCSS3B_LOCAL); ?></td></tr>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Enter custom CSS', SCSS3B_LOCAL); ?>" for="<?php echo SCSS3B_OPTION; ?>[<?php echo SCSS3B_DEFAULT_CUSTOM_CSS_NAME; ?>]"><?php _e('Enter custom CSS', SCSS3B_LOCAL); ?></label></strong></th>
			<td><textarea rows="12" cols="75" id="<?php echo SCSS3B_OPTION; ?>[<?php echo SCSS3B_DEFAULT_CUSTOM_CSS_NAME; ?>]" name="<?php echo SCSS3B_OPTION; ?>[<?php echo SCSS3B_DEFAULT_CUSTOM_CSS_NAME; ?>]"><?php echo $options[SCSS3B_DEFAULT_CUSTOM_CSS_NAME]; ?></textarea></td>
			</tr>
			<tr valign="top"><td colspan="2"><?php _e('If you use your own custom class names, enter the CSS here. Use the custom class name (minus the "scss3b-button-" prefix) in the shortcode or when calling the function in PHP.', SCSS3B_LOCAL); ?></td></tr>
				</table>
				<?php submit_button(); ?>
			</form>
			<hr />
			<h3 id="parameters"><img src="<?php echo plugins_url(plugin_basename(dirname(__FILE__) . '/images/parameters.png')) ?>" title="" alt="" height="64" width="64" align="absmiddle" /> Plugin Parameters and Default Values</h3>
			These are the parameters for using the shortcode, or calling the plugin from your PHP code.
			
			For available colors, see the dropdown list above.
			<table class="widefat">
				<thead>
					<tr>
						<th title="<?php _e('The name of the parameter', SCSS3B_LOCAL); ?>"><strong><?php _e('Parameter Name', SCSS3B_LOCAL); ?></strong></th>
						<th title="<?php _e('Is this parameter required?', SCSS3B_LOCAL); ?>"><strong><?php _e('Is Required?', SCSS3B_LOCAL); ?></strong></th>
						<th title="<?php _e('What data type this parameter accepts', SCSS3B_LOCAL); ?>"><strong><?php _e('Data Type', SCSS3B_LOCAL); ?></strong></th>
						<th title="<?php _e('What, if any, is the default if no value is specified', SCSS3B_LOCAL); ?>"><strong><?php _e('Default Value', SCSS3B_LOCAL); ?></strong></th>
					</tr>
				</thead>
				<tbody>
			<?php $plugin_defaults_keys = array_keys(scss3b_shortcode_defaults());
						$plugin_defaults_values = array_values(scss3b_shortcode_defaults());
						$scss3b_required = scss3b_required_parameters();
						for($i=0; $i<count($plugin_defaults_keys);$i++) { ?>
					<tr>
						<td><strong><?php echo $plugin_defaults_keys[$i]; ?></strong></td>
						<td><?php 
							if ($scss3b_required[$i] === true) {
								echo '<strong>';
								_e('Yes', SCSS3B_LOCAL);
								echo '</strong>';
							} else {
								_e('No', SCSS3B_LOCAL); 
							}
						?></td>
						<td><?php echo gettype($plugin_defaults_values[$i]); ?></td>
						<td><?php 
							if ($plugin_defaults_values[$i] === true) {
								echo '<strong>';
								_e('true', SCSS3B_LOCAL);
								echo '</strong>';
							} elseif ($plugin_defaults_values[$i] === false) {
								_e('false', SCSS3B_LOCAL);
							} elseif ($plugin_defaults_values[$i] === '') {
								echo '<em>';
								_e('this value is blank by default', SCSS3B_LOCAL);
								echo '</em>';
							} elseif (is_numeric($plugin_defaults_values[$i])) {
								echo $plugin_defaults_values[$i];
							} else { 
								echo '"' . $plugin_defaults_values[$i] . '"';
							} ?></td>
					</tr>
			<?php } ?>
			</tbody>
			</table>
			<h3 id="support"><img src="<?php echo plugins_url(plugin_basename(dirname(__FILE__) . '/images/support.png')) ?>" title="" alt="" height="64" width="64" align="absmiddle" /> Support</h3>
				<div class="support">
				<?php echo '<a href="http://wordpress.org/extend/plugins/' . SCSS3B_SLUG . '/">' . __('Documentation', SCSS3B_LOCAL) . '</a> | ';
					echo '<a href="http://wordpress.org/plugins/' . SCSS3B_SLUG . '/faq/">' . __('FAQ', SCSS3B_LOCAL) . '</a><br />';
					_e('If you like this plugin, please ', SCSS3B_LOCAL);
					echo '<a href="http://wordpress.org/support/view/plugin-reviews/' . SCSS3B_SLUG . '/">';
					_e('rate it on WordPress.org', SCSS3B_LOCAL);
					echo '</a> ';
					_e('and click the ', SCSS3B_LOCAL);
					echo '<a href="http://wordpress.org/plugins/' . SCSS3B_SLUG .  '/#compatibility">';
					_e('Works', SCSS3B_LOCAL);
					echo '</a> ';
					_e('button. For support please visit the ', SCSS3B_LOCAL);
					echo '<a href="http://wordpress.org/support/plugin/' . SCSS3B_SLUG . '">';
					_e('forums', SCSS3B_LOCAL);
					echo '</a>.';
				?>
				<br /><br />
				<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7EX9NB9TLFHVW"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" alt="Donate with PayPal" title="Donate with PayPal" width="92" height="26" /></a>
				</div>
		</div>
		<?php }
	// shortcode and function
	add_shortcode('standout-css3-button', 'scss3button');
	function scss3button($atts, $content = null) {
		// get parameters
		extract(shortcode_atts(scss3b_shortcode_defaults(), $atts));
		// plugin is enabled/disabled from settings page only
		$options = scss3b_getpluginoptions();
		$enabled = $options[SCSS3B_DEFAULT_ENABLED_NAME];
		
		// ******************************
		// derive shortcode values from constants
		// ******************************
		$temp_style = constant('SCSS3B_DEFAULT_STYLE_NAME');
		$cssclass = $$temp_style;
		$temp_url = constant('SCSS3B_DEFAULT_URL_NAME');
		$url = $$temp_url;
		$temp_nofollow = constant('SCSS3B_DEFAULT_NOFOLLOW_NAME');
		$nofollow = $$temp_nofollow;
		$temp_window = constant('SCSS3B_DEFAULT_NEWWINDOW_NAME');
		$opennewwindow = $$temp_window;
		$temp_show = constant('SCSS3B_DEFAULT_SHOW_NAME');
		$show = $$temp_show;

		// ******************************
		// sanitize user input
		// ******************************
		$url = esc_url($url);
		$cssclass = sanitize_html_class($cssclass);
		if (!$cssclass) {
			$cssclass = SCSS3B_DEFAULT_STYLE;
		}
		$nofollow = (bool)$nofollow;
		$opennewwindow = (bool)$opennewwindow;
		$show = (bool)$show;

		// ******************************
		// check for parameters, then settings, then defaults
		// ******************************
		if ($enabled) {
			if ($content === null) { 
				// what is the point of a button w/ no text?
				$enabled = false;
				$output = '<!-- ' . SCSS3B_PLUGIN_NAME . ': ' . __('plugin is disabled. Either you did not pass a necessary setting to the plugin, or did not configure a default. Check Settings page.', SCSS3B_LOCAL) . ' -->';
			} else { 
				if ($url == SCSS3B_DEFAULT_URL) { // no url passed to function, try settings page
					$url = $options[SCSS3B_DEFAULT_URL_NAME];
					if (($url == SCSS3B_DEFAULT_URL) || ($url == false)) { // no url on settings page either
						$enabled = false;
						$output = '<!-- ' . SCSS3B_PLUGIN_NAME . ': ' . __('plugin is disabled. Either you did not pass a necessary setting to the plugin, or did not configure a default. Check Settings page.', SCSS3B_LOCAL) . ' -->';
					}
				}
			}
		}
		if ($enabled) {
			// plugin is enabled and there is content
			// check for overridden parameters, if nonexistent then get from DB
			if ($nofollow === SCSS3B_DEFAULT_NOFOLLOW) {
				$nofollow = $options[SCSS3B_DEFAULT_NOFOLLOW_NAME];
				if ($nofollow === false) {
					$nofollow = SCSS3B_DEFAULT_NOFOLLOW;
				}
			}
			if ($opennewwindow === SCSS3B_DEFAULT_NEWWINDOW) {
				$opennewwindow = $options[SCSS3B_DEFAULT_NEWWINDOW_NAME];
				if ($opennewwindow === false) {
					$opennewwindow = SCSS3B_DEFAULT_NEWWINDOW;
				}
			}
			// check if existing color value was passed
			if (($cssclass == false) || ($cssclass == SCSS3B_DEFAULT_STYLE)) {
				// not passed by shortcode, use default
				$cssclass = 'scss3b-' . $options[SCSS3B_DEFAULT_STYLE_NAME];
			} else { // a value was passed, check if it is one of the available classes. if not, assume a custom class and load custom.css
				$buttonclasses = explode(",", SCSS3B_AVAILABLE_STYLES);
				if (!in_array($cssclass, $buttonclasses)) {
					// write CSS to custom.css file
					$myFile = dirname(__FILE__) . '/css/custom.css';
					$fh = @fopen($myFile, 'w+');
					@fwrite($fh, $options[SCSS3B_DEFAULT_CUSTOM_CSS_NAME]);
					@fclose($fh);
					// enqueue custom css file
		scss3b_custom_styles();
					// 'fix' class name
					$cssclass = 'scss3b-button-' . $cssclass;
				} else {
					$cssclass = 'scss3b-' . $cssclass;
				}
			} // end color

			// enqueue CSS only on pages with shortcode
			scss3b_button_styles();

			$output = '<a';
			$output .= ' class="scss3b-button';
			$output .= ' ' . $cssclass . '"';
			$output .= ($opennewwindow ? ' onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;" ' : '');
			$output .= ($nofollow ? ' rel="nofollow"' : '');
			$output .= ' href="' . $url . '"';
			$output .= '>';
			$output .= do_shortcode(wp_kses(force_balance_tags($content)));
			$output .=  '</a>';
			
		} else { // plugin disabled
			$output = '<!-- ' . SCSS3B_PLUGIN_NAME . ': ' . __('plugin is disabled. Either you did not pass a necessary setting to the plugin, or did not configure a default. Check Settings page.', SCSS3B_LOCAL) . ' -->';
		} // end enabled check
		if ($show) {
			echo $output;
		} else {
			return $output;
		}
	} // end shortcode
	// show admin messages to plugin user
	add_action('admin_notices', 'scss3b_showAdminMessages');
	function scss3b_showAdminMessages() {
		// http://wptheming.com/2011/08/admin-notices-in-wordpress/
		global $pagenow;
		if (current_user_can(SCSS3B_PERMISSIONS_LEVEL)) { // user has privilege
			if ($pagenow == 'options-general.php') { // we are on Settings page
				if ($_GET['page'] == SCSS3B_SLUG) { // we are on this plugin's settings page
					$options = scss3b_getpluginoptions();
					if ($options != false) {
				$enabled = $options[SCSS3B_DEFAULT_ENABLED_NAME];
						$cssclass = $options[SCSS3B_DEFAULT_STYLE_NAME];
						$url = $options[SCSS3B_DEFAULT_URL_NAME];
						if (!$enabled) {
				echo '<div id="message" class="error">' . SCSS3B_PLUGIN_NAME . ' ' . __('is currently disabled.', SCSS3B_LOCAL) . '</div>';
			}
						if (($cssclass === SCSS3B_DEFAULT_STYLE) || ($cssclass === false) || ($url === SCSS3B_DEFAULT_URL) || ($url === false)) {
			echo '<div id="message" class="updated">' . __('Please confirm the default CSS style and URL and click "Save".', SCSS3B_LOCAL) . '</div>';
						}
					}
				}
			} // end page check
		} // end privilege check
	} // end admin msgs function
	// enqueue admin CSS if we are on the plugin options page
	add_action('admin_head', 'insert_scss3b_admin_css');
	function insert_scss3b_admin_css() {
		global $pagenow;
		if (current_user_can(SCSS3B_PERMISSIONS_LEVEL)) { // user has privilege
			if ($pagenow == 'options-general.php') {
				if ($_GET['page'] == SCSS3B_SLUG) { // we are on settings page
					scss3b_admin_styles();
				}
			}
		}
	}
	// add settings link on plugin page
	// http://bavotasan.com/2009/a-settings-link-for-your-wordpress-plugins/
	add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'scss3b_plugin_settings_link' );
	function scss3b_plugin_settings_link($links) {
		$settings_link = '<a href="options-general.php?page=' . SCSS3B_SLUG . '">' . __('Settings', SCSS3B_LOCAL) . '</a>';
		array_unshift($links, $settings_link);
		return $links;
	}
	// http://wpengineer.com/1295/meta-links-for-wordpress-plugins/
	add_filter('plugin_row_meta', 'scss3b_meta_links', 10, 2);
	function scss3b_meta_links($links, $file) {
		$plugin = plugin_basename(__FILE__);
		// create link
		if ($file == $plugin) {
			$links = array_merge($links,
				array(
					'<a href="http://wordpress.org/support/plugin/' . SCSS3B_SLUG . '">' . __('Support', SCSS3B_LOCAL) . '</a>',
					'<a href="http://wordpress.org/extend/plugins/' . SCSS3B_SLUG . '/">' . __('Documentation', SCSS3B_LOCAL) . '</a>',
					'<a href="http://wordpress.org/plugins/' . SCSS3B_SLUG . '/faq/">' . __('FAQ', SCSS3B_LOCAL) . '</a>'
			));
		}
		return $links;
	}
	// enqueue/register the plugin CSS file
	function scss3b_button_styles() {
		wp_enqueue_style('standout_css3_button_style');
	}
	function register_scss3b_style() {
		wp_register_style('standout_css3_button_style', 
			plugins_url(plugin_basename(dirname(__FILE__)) . '/css/standout-css3-buttons.css'), 
			array(), 
			SCSS3B_VERSION . "_" . date('njYHis', filemtime(dirname(__FILE__) . '/css/standout-css3-buttons.css')),
			'all' );
	}
	// enqueue/register the admin CSS file
	function scss3b_admin_styles() {
		wp_enqueue_style('scss3b_admin_style');
	}
	function register_scss3b_admin_style() {
		wp_register_style('scss3b_admin_style',
			plugins_url(plugin_basename(dirname(__FILE__)) . '/css/admin.css'),
			array(),
			SCSS3B_VERSION . "_" . date('njYHis', filemtime(dirname(__FILE__) . '/css/admin.css')),
			'all');
	}
	// enqueue/register the custom CSS file
	function scss3b_custom_styles() {
		wp_register_style('scss3b_custom_style',
			plugins_url(plugin_basename(dirname(__FILE__)) . '/css/custom.css'),
		array(),
		SCSS3B_VERSION . "_" . date('njYHis', filemtime(dirname(__FILE__) . '/css/custom.css')),
		'all');
		wp_enqueue_style('scss3b_custom_style');
	}
	// enqueue/register the admin JS file
	add_action('admin_enqueue_scripts', 'scss3b_ed_buttons');
	function scss3b_ed_buttons($hook) {
		if (($hook == 'post-new.php') || ($hook == 'post.php')) {
			wp_enqueue_script('scss3b_add_editor_button');
		}
	}
	function register_scss3b_admin_script() {
		wp_register_script('scss3b_add_editor_button',
			plugins_url(plugin_basename(dirname(__FILE__)) . '/js/editor_button.js'), 
			array('quicktags'), 
			SCSS3B_VERSION . "_" . date('njYHis', filemtime(dirname(__FILE__) . '/js/editor_button.js')),
			true);
	}
	// when plugin is activated, create options array and populate with defaults
	register_activation_hook(__FILE__, 'scss3b_activate');
	function scss3b_activate() {
		$options = scss3b_getpluginoptions();
		update_option(SCSS3B_OPTION, $options);
	}
	// generic function that returns plugin options from DB
	// if option does not exist, returns plugin defaults
	function scss3b_getpluginoptions() {
		return get_option(SCSS3B_OPTION, 
			array(
				SCSS3B_DEFAULT_ENABLED_NAME => SCSS3B_DEFAULT_ENABLED, 
				SCSS3B_DEFAULT_STYLE_NAME => SCSS3B_DEFAULT_STYLE, 
				SCSS3B_DEFAULT_URL_NAME => SCSS3B_DEFAULT_URL, 
				SCSS3B_DEFAULT_NOFOLLOW_NAME => SCSS3B_DEFAULT_NOFOLLOW, 
				SCSS3B_DEFAULT_NEWWINDOW_NAME => SCSS3B_DEFAULT_NEWWINDOW, 
				SCSS3B_DEFAULT_CUSTOM_CSS_NAME => SCSS3B_DEFAULT_CUSTOM_CSS
			));
	}
	// function to return shortcode defaults
	function scss3b_shortcode_defaults() {
		return array(
			SCSS3B_DEFAULT_URL_NAME => SCSS3B_DEFAULT_URL, 
			SCSS3B_DEFAULT_STYLE_NAME => SCSS3B_DEFAULT_STYLE, 
			SCSS3B_DEFAULT_NOFOLLOW_NAME => SCSS3B_DEFAULT_NOFOLLOW, 
			SCSS3B_DEFAULT_NEWWINDOW_NAME => SCSS3B_DEFAULT_NEWWINDOW, 
			SCSS3B_DEFAULT_SHOW_NAME => SCSS3B_DEFAULT_SHOW
			);
	}
	// function to return parameter status (required or not)
	function scss3b_required_parameters() {
		return array(
			true,
			false,
			false,
			false, 
			false
		);
	}
?>