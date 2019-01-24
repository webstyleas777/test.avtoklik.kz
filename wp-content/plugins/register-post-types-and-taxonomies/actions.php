<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$register_post_types_and_taxonomies = Register_Post_Types_and_Taxonomies();
$plugin_file = $register_post_types_and_taxonomies->file;

// Plugin Activation
register_activation_hook( $plugin_file,              'register_post_types_and_taxonomies__activation_action'                 );

// Plugin Deactivation
register_deactivation_hook( $plugin_file,            'flush_rewrite_rules'                                                   );