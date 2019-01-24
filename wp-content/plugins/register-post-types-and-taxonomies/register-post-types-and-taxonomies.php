<?php

/*
 Plugin Name: Register Post Types and Taxonomies
 Plugin URI: http://wordpress.org/plugins/register-post-types-and-taxonomies
 Description: This plugin will help you register new post types and taxonomies. (Go to: Dashboard -> Plugins -> Register Post Types / Register Taxonomies)
 Version: 1.2
 Author: Alexandru Vornicescu
 Author URI: http://alexvorn.com
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Function that will run at plugin activation
function register_post_types_and_taxonomies__activation_action() {

	register_post_types_and_taxonomies__register_post_types_action();
	register_post_types_and_taxonomies__register_taxonomies_action();
	flush_rewrite_rules();
}

// Main class of the plugin
class Register_Post_Types_and_Taxonomies {

	public static function instance() {

		// Store the instance locally to avoid private static replication
		static $instance = null;

		// Only run these methods if they haven't been ran previously
		if ( null === $instance ) {
			$instance = new Register_Post_Types_and_Taxonomies;
			$instance->setup_globals();
			$instance->includes();
		}

		// Always return the instance
		return $instance;
	}
	
	private function setup_globals() {

		/** Versions **********************************************************/

		$this->version         = '1.2';

		// Setup some base path and URL information
		$this->file            = __FILE__;
		$this->basename        = plugin_basename( $this->file );
		$this->plugin_dir      = plugin_dir_path( $this->file );
		$this->plugin_url      = plugin_dir_url ( $this->file );

		// Includes
		$this->includes_dir    = trailingslashit( $this->plugin_dir . 'includes' );
		$this->includes_url    = trailingslashit( $this->plugin_url . 'includes' );
	}
	
	private function includes() {
		
		require( $this->plugin_dir . 'actions.php'                                           );
		require( $this->includes_dir . 'actions.php'                                         );
		require( $this->includes_dir . 'functions.php'                                       );
		
		// Quick admin check and load if needed
		if ( is_admin() ) {
			require( $this->includes_dir . 'admin/actions.php'                               );
			require( $this->includes_dir . 'admin/functions.php'                             );
			require( $this->includes_dir . 'admin/class-post-types-table.php'                );
			require( $this->includes_dir . 'admin/class-taxonomies-table.php'                );
			require( $this->includes_dir . 'admin/menu-pages/actions.php'                    );
			require( $this->includes_dir . 'admin/menu-pages/menu-pages.php'                 );
		}
		
	}
}


function register_post_types_and_taxonomies() {
	return Register_Post_Types_and_Taxonomies::instance();
}

// Function
register_post_types_and_taxonomies();