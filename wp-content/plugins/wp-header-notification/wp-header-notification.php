<?php
/*

**************************************************************************

Plugin Name:  WP Header Notification
Plugin URI:   http://www.arefly.com/wp-header-notification/
Description:  Add a Notification bar similar to Google in your blog's header.
Version:      1.2.5
Author:       Arefly
Author URI:   http://www.arefly.com/
Text Domain:  wp-header-notification
Domain Path:  /lang/

**************************************************************************

	Copyright 2014  Arefly  (email : eflyjason@gmail.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

**************************************************************************/

define("WP_HEADER_NOTIFICATION_PLUGIN_URL", plugin_dir_url( __FILE__ ));
define("WP_HEADER_NOTIFICATION_FULL_DIR", plugin_dir_path( __FILE__ ));
define("WP_HEADER_NOTIFICATION_TEXT_DOMAIN", "wp-header-notification");

/* Plugin Localize */
function wp_header_notification_load_plugin_textdomain() {
	load_plugin_textdomain(WP_HEADER_NOTIFICATION_TEXT_DOMAIN, false, dirname(plugin_basename( __FILE__ )).'/lang/');
}
add_action('plugins_loaded', 'wp_header_notification_load_plugin_textdomain');

include_once WP_HEADER_NOTIFICATION_FULL_DIR."options.php";

/* Add Links to Plugins Management Page */
function wp_header_notification_action_links($links){
	$links[] = '<a href="'.get_admin_url(null, 'options-general.php?page='.WP_HEADER_NOTIFICATION_TEXT_DOMAIN.'-options').'">'.__("Settings", WP_HEADER_NOTIFICATION_TEXT_DOMAIN).'</a>';
	return $links;
}
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'wp_header_notification_action_links');

if(get_option("wp_header_notification_status") == "open"){

$notification_code = '<div id="top_notice">
	<div class="top_notice_close" onclick="wp_header_notification_pushdownclose();"> × </div>
	<div class="top_notice_text_box">
		<span class="top_notice_text">'.get_option("wp_header_notification_text").'</span>
		<div class="top_notice_button" onclick="wp_header_notification_pushdownyes();">'.get_option("wp_header_notification_button_value").'</div>
		<div class="top_notice_button" onclick="wp_header_notification_pushdownclose();">'.get_option("wp_header_notification_close_value").'</div>
	</div>
</div>';

/* Add CSS and JS code */
function wp_header_notification_enqueue_styles() {
	wp_enqueue_style(WP_HEADER_NOTIFICATION_TEXT_DOMAIN, WP_HEADER_NOTIFICATION_PLUGIN_URL.'style.min.css');
}
add_action('wp_enqueue_scripts', 'wp_header_notification_enqueue_styles');

function wp_header_notification_enqueue_script(){
	wp_enqueue_script(WP_HEADER_NOTIFICATION_TEXT_DOMAIN, WP_HEADER_NOTIFICATION_PLUGIN_URL.'script.min.js');
	$wp_header_notification_renotice_time = get_option('wp_header_notification_renotice_time');
	$wp_header_notification_renotice_time = sscanf($wp_header_notification_renotice_time, "%d %d", $month, $day);
	$wp_header_notification_renotice_time = $month * 30 + $day;
	wp_localize_script(WP_HEADER_NOTIFICATION_TEXT_DOMAIN, 'wp_header_notification_info', array('button_link' => get_option('wp_header_notification_button_link'), 'renotice_time' => $wp_header_notification_renotice_time));
}
add_action('wp_enqueue_scripts', 'wp_header_notification_enqueue_script');

function wp_header_notification_template_include($template) {
	ob_start();
	return $template;
}
add_filter('template_include', 'wp_header_notification_template_include', 1);

/* Use a SPECIAL WAY to add code after <body> tag */
function wp_header_notification() {
	global $notification_code;
	if(!isset($_COOKIE['close_wp_header_notification'])){
		if(!in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'))){
			$content = ob_get_clean();
			$content = preg_replace('#<body([^>]*)>#i', "<body$1>{$notification_code}", $content);
			echo $content;
		}
	}
}
add_filter('shutdown', 'wp_header_notification', 0);

function get_wp_header_notification(){
	global $notification_code;
	echo $notification_code;
}

}