<?php
/*
Plugin Name: Friendly Functions for Welcart
Description: This is a plugin that has a few features to make Welcart just a little bit more useful.
Author: MAINICHI WEB
Author URI: https://mainichi-web.com/
Text Domain: friendly-functions-for-welcart
Domain Path: /languages/
Requires at least: 5.0
Requires PHP: 7.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Version: 1.2.4
*/

if(!defined('ABSPATH')) exit;

/* 定義 */
const MAINICHI_WEB_THIS_PLUGIN_NAME = 'friendly-functions-for-welcart';
define( 'FRIENDLY_FUNCTIONS_FOR_WELCART__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'FRIENDLY_FUNCTIONS_FOR_WELCART__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
/* 翻訳 */
function friendly_functions_for_welcart_plugin_load_textdomain() {
	load_plugin_textdomain('friendly-functions-for-welcart');
}
add_action('plugins_loaded', 'friendly_functions_for_welcart_plugin_load_textdomain');
/* テンプレート読み込み */
require_once( FRIENDLY_FUNCTIONS_FOR_WELCART__PLUGIN_DIR . 'functions/ffw_functions.php' );
require_once( FRIENDLY_FUNCTIONS_FOR_WELCART__PLUGIN_DIR . 'functions/ffw_other.php' );
require_once( FRIENDLY_FUNCTIONS_FOR_WELCART__PLUGIN_DIR . 'other_tab/other_tab_contents.php' );

/* プラグイン一覧に設定リンク追加 */
	function friendly_functions_for_welcart_plugin_action_links($links, $file) {
		static $this_plugin;
		if (!$this_plugin) {
			$this_plugin = plugin_basename(__FILE__);
		}
		if ($file == $this_plugin) {
			$settingsUrl = admin_url('admin.php?page=ffw_function_settings');
			$settings_link = sprintf(__('<a href="%s">Settings</a>', MAINICHI_WEB_THIS_PLUGIN_NAME), esc_url($settingsUrl));
			array_unshift($links, $settings_link);
		}
		return $links;
	}
	add_filter('plugin_action_links', 'friendly_functions_for_welcart_plugin_action_links', 10, 2);
?>