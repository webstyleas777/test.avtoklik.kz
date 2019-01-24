<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Page menus array
function register_post_types_and_taxonomies__menu_page( $settings_options = array() ) {

	$post_types_help = array();
	$taxonomies_help = array();
	$admin_panel_help_theme_options = array();

	$admin_panel_help_theme_options['id_0'] = array(
		'title'      => __( 'About This Plugin' , 'register_post_types_and_taxonomies' ),
		'content'    => '<p>' . __( 'If you want to create a new post type or a taxonomy then this plugin will help you.' , 'register_post_types_and_taxonomies' ) . '</p>'
	);
	
	$post_types_help['id_1'] = array(
		'title'      => __( 'How to create a new post type' , 'register_post_types_and_taxonomies' ),
		'content'    =>	'<ul>' .
							'<li>' . __( 'Type the name of the new post type.', 'register_post_types_and_taxonomies' ) . '</li>' .
							'<li>' . __( 'Select other settings you need to change.', 'register_post_types_and_taxonomies' ) . '</li>' .
							'<li>' . __( 'Press: "Add New Post Type".', 'register_post_types_and_taxonomies' ) . '</li>' .
						'</ul>'
	);
	
	$taxonomies_help['id_1'] = array(
		'title'      => __( 'How to create a new taxonomy' , 'register_post_types_and_taxonomies' ),
		'content'    =>	'<ul>' .
							'<li>' . __( 'Type the name of the new taxonomy.', 'register_post_types_and_taxonomies' ) . '</li>' .
							'<li>' . __( 'Select other settings you need to change.', 'register_post_types_and_taxonomies' ) . '</li>' .
							'<li>' . __( 'Select what post type you need to apply.', 'register_post_types_and_taxonomies' ) . '</li>' .
							'<li>' . __( 'Press: "Add New Taxonomy".', 'register_post_types_and_taxonomies' ) . '</li>' .
						'</ul>'
	);
	
	// Theme options
	$settings_options['register_post_types'] = array(
		'page_title'            => __( 'Post Types', 'register_post_types_and_taxonomies' ),
		'menu_title'            => __( 'Register Post Types', 'register_post_types_and_taxonomies' ),
		'capability'            => 'edit_theme_options',
		'menu_slug'             => 'register_post_types',
		'function'              => 'register_post_types_and_taxonomies__post_types_admin_panel',
		'help'                  => $admin_panel_help_theme_options + $post_types_help
	);

	// Theme options
	$settings_options['register_taxonomies'] = array(
		'page_title'            => __( 'Taxonomies', 'register_post_types_and_taxonomies' ),
		'menu_title'            => __( 'Register Taxonomies', 'register_post_types_and_taxonomies' ),
		'capability'            => 'edit_theme_options',
		'menu_slug'             => 'register_taxonomies',
		'function'              => 'register_post_types_and_taxonomies__taxonomies_admin_panel',
		'help'                  => $admin_panel_help_theme_options + $taxonomies_help
	);

	return $settings_options;
}