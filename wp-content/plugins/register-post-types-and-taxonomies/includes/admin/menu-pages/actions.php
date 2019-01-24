<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Admin page
add_filter( 'register_post_types_and_taxonomies__add_menu_page_settings', 'register_post_types_and_taxonomies__menu_page' );