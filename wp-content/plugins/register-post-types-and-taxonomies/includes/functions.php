<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Registering custom post types function
function register_post_types_and_taxonomies__register_post_types_action() {
	
	$get_option = get_option( 'register_post_types_and_taxonomies__post_types', array() );
	
	if ( ! empty( $get_option ) ) {
		foreach ( (array) $get_option as $post_type_id => $args ) {

			if ( isset( $args['public'] ) ) {
				$args['public'] = (bool) $args['public'];
			} else {
				$args['public'] = false;
			}
			
			if ( isset( $args['has_archive'] ) ) {
				$args['has_archive'] = (bool) $args['has_archive'];
			} else {
				$args['has_archive'] = false;
			}
			
			if ( isset( $args['slug'] ) ) {
				$args['rewrite'] = array(
					'slug' => $args['slug']
				);
				
				unset( $args['slug'] );
			}
		
			$args = apply_filters( 'register_post_types_and_taxonomies__register_post_types_filter', $args );
		
			register_post_type( $post_type_id, $args );

		}
	}

}

// Registering custom taxonomies function
function register_post_types_and_taxonomies__register_taxonomies_action() {
	
	$get_option = get_option( 'register_post_types_and_taxonomies__taxonomies', array() );

	if ( ! empty( $get_option ) ) {
		foreach ( (array) $get_option as $taxonomy_id => $args ) {

			if ( isset( $args['public'] ) ) {
				$args['public'] = (bool) $args['public'];
			} else {
				$args['public'] = false;
			}
			
			if ( isset( $args['slug'] ) ) {
				$args['rewrite'] = array(
					'slug' => $args['slug']
				);
				
				unset( $args['slug'] );
			}
			
			if ( isset( $args['post-type'] ) ) {
				$post_type = $args['post-type'];
				
				unset( $args['post-type'] );
			} else {
				$post_type = '';
			}
		
			$args = apply_filters( 'register_post_types_and_taxonomies__register_taxonomies_filter', $args );
		
			if ( ! empty( $post_type ) ) {
				register_taxonomy( $taxonomy_id, $post_type, $args );
			}

		}
	}

}