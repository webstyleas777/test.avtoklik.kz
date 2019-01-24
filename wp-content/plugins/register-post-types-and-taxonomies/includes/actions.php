<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Register Post Types
add_action( 'init', 'register_post_types_and_taxonomies__register_post_types_action' );

// Register Taxonomies
add_action( 'init', 'register_post_types_and_taxonomies__register_taxonomies_action' );


$get_option = get_option( 'register_post_types_and_taxonomies__flush_rewrite_rules', false );

// Flush rewrite rules after registering post types or taxonomies
if ( (bool) $get_option ) {
	add_action( 'init', 'flush_rewrite_rules' );
	
	update_option( 'register_post_types_and_taxonomies__flush_rewrite_rules', false );
}