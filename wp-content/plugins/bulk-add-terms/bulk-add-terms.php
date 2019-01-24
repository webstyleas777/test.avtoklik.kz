<?php
/**
 * Plugin Name: Bulk add terms
 * Description: This plugin will help you to add multiple taxonomy terms in one go. Ajax is used to add terms.
 * Version:     2.0.4
 * Author:      Sohan Zaman
 * Author URI:  https://github.com/sohan5005
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /lang
 * Text Domain: ts_bat_domain
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once( dirname(__FILE__) . '/includes/class-ts-bulk-add-term-options.php' );
require_once( dirname(__FILE__) . '/class-ts-bulk-add-terms.php' );
require_once( dirname(__FILE__) . '/class-ts-admin-notice.php' );

$href = admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=super-blog-pack&amp;TB_iframe=true&amp' );

if( !function_exists('wp_get_current_user') ) {
	require_once( ABSPATH . 'wp-includes/pluggable.php' );
}

if( current_user_can( 'install_plugins' ) || current_user_can( 'activate_plugins' ) ) {
	new TS_Admin_Notice( array(
		'id' => 'super-blog-pack',
		'notice' => '<strong>Power up your website</strong>! You can now show Post likes, Post views counter, Related posts, Post reviews with 5 star rating system and much more with a few clicks.<br><a href="' . esc_url( $href ) . '" class="thickbox open-plugin-details-modal" target="_blank">Try now for free!</a> or <a href="https://goo.gl/Ywr7L9" target="_blank">Get more details about this plugin.</a>',
		'type' => 'success',
		'dissmiss' => 'cookie',
	) );
}

new TS_Bulk_Add_Terms;