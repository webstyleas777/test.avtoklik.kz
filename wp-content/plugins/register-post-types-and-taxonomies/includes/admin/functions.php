<?php 

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


// This function is called to add admin menus in the WordPress left menu
function register_post_types_and_taxonomies__admin_menu_action() {
	$settings = apply_filters( 'register_post_types_and_taxonomies__add_menu_page_settings', array() );

	foreach ( (array) $settings as $wpi ) {
		if ( isset( $wpi['page_title'], $wpi['menu_title'], $wpi['capability'], $wpi['menu_slug'], $wpi['function'] ) ) {

			// You can change where to add the page
			$admin_suffix = add_plugins_page( $wpi['page_title'], $wpi['menu_title'], $wpi['capability'], $wpi['menu_slug'], $wpi['function'] );

			// Add help data and sidebar
			add_action( 'load-' . $admin_suffix, 'register_post_types_and_taxonomies__admin_add_help_and_sidebar_action' );
		}
	}

}

// Function for adding help data and sidebar to the help tab
function register_post_types_and_taxonomies__admin_add_help_and_sidebar_action() {
	global $plugin_page;
	
	$screen = get_current_screen();
	$settings = apply_filters( 'register_post_types_and_taxonomies__add_menu_page_settings', array() );
	
	// Default admin panel help text
	$register_post_types_and_taxonomies__admin_panel_help = register_post_types_and_taxonomies__admin_panel_help();
	
	// Default sidebar text
	$sidebar_tab_help = register_post_types_and_taxonomies__sidebar_tab_help();
	
	foreach ( (array) $settings as $sub_settings ) {
	
		// Filter the admin_panel_help
		$register_post_types_and_taxonomies__admin_panel_help_filtered = apply_filters( 'register_post_types_and_taxonomies__filter_admin_panel_help', $register_post_types_and_taxonomies__admin_panel_help, $plugin_page );
		
		// Filter the sidebar help tab text
		$sidebar_tab_help_filtered = apply_filters( 'register_post_types_and_taxonomies__filter_admin_panel_help', $sidebar_tab_help, $plugin_page );
	
		// If is the current page we need
		if ( (string) $sub_settings['menu_slug'] == (string) $plugin_page ) {

			// Get help data
			if ( isset( $sub_settings['help'] ) ) {
				$admin_panel_help = array_merge( $register_post_types_and_taxonomies__admin_panel_help_filtered, $sub_settings['help'] );
			} else {
				$admin_panel_help = $register_post_types_and_taxonomies__admin_panel_help_filtered;
			}
			
			// Add help bdata to admin page
			if ( ! empty( $admin_panel_help ) ) {
				foreach ( (array) $admin_panel_help as $key => $tab ) {
					$screen->add_help_tab( array(
						'id'	    => $key,
						'title'	    => $tab['title'],
						'content'	=> $tab['content']
					) );
					
					$screen->set_help_sidebar( $sidebar_tab_help_filtered );
				}
			}
		}
	}
}

// Admin Panel help default text
function register_post_types_and_taxonomies__admin_panel_help() {
	$admin_panel_help = array();
	
	return $admin_panel_help;
}

// Sidebar tab help default text
function register_post_types_and_taxonomies__sidebar_tab_help() {
	$sidebar_tab_help = '<p><strong>' . __( 'For more information:', 'register_post_types_and_taxonomies' ) . '</strong></p>' .
		'<p>' . __( '<a href="http://wordpress.org/plugins/register-post-types-and-taxonomies/" target="_blank">' . __( 'Documentation', 'register_post_types_and_taxonomies' ) . '</a>' ) . '</p>' .
		'<p>' . __( '<a href="http://wordpress.org/support/plugin/register-post-types-and-taxonomies/" target="_blank">' . __( 'Support Forum', 'register_post_types_and_taxonomies' ) . '</a>' ) . '</p>';
	
	return $sidebar_tab_help;
}

// What will display on the Register Post Types page
function register_post_types_and_taxonomies__post_types_admin_panel() { 
	global $title, $plugin_page;
	
	$post_types_table = new Register_Post_Types_and_Taxonomies__Post_Types();

	$update_message = 0;
	$error_message = false;

	switch ( $post_types_table->current_action() ) {

		case 'add-post-type':

			check_admin_referer( 'add_post_type_nonce_action', 'add_post_type_nonce_name' );

			if ( ! current_user_can( 'edit_theme_options' ) ) {
				wp_die( __( 'Cheatin&#8217; uh?', 'register_post_types_and_taxonomies' ), 403 );
			}
			
			// Plugin post types
			$registered_post_types = get_option( 'register_post_types_and_taxonomies__post_types', array() );

			$get_post_types = get_post_types();
			
			// Reserved
			$reserved_post_type_name = array();
			$reserved_post_type_name['action'] = 'action';
			$reserved_post_type_name['author'] = 'author';
			$reserved_post_type_name['order'] = 'order';
			$reserved_post_type_name['theme'] = 'theme';

			if ( ! empty( $_POST['label'] ) ) {

				$post_type_id = sanitize_key( $_POST['label'] );
				
				if ( ! empty( $post_type_id ) ) {
					
					// Get maximum 20 characters
					$first_20_chrs_post_type_id = substr( $post_type_id, 0, 20 );
				
					if ( ! in_array( $first_20_chrs_post_type_id, $get_post_types ) ) {
		
						if ( ! in_array( $first_20_chrs_post_type_id, $reserved_post_type_name ) ) {
							unset( $_POST['action'] );
							unset( $_POST['add_post_type_nonce_name'] );
							unset( $_POST['_wp_http_referer'] );
							unset( $_POST['submit'] );
		
							$registered_post_types[$first_20_chrs_post_type_id] = $_POST;
							
							update_option( 'register_post_types_and_taxonomies__post_types', $registered_post_types );
							
							// We need to flush permalinks after this action
							update_option( 'register_post_types_and_taxonomies__flush_rewrite_rules', true );

							$update_message = 1;
						} else {
							$update_message = 6;
							$error_message = true;
						}
						
					} else {
						$update_message = 7;
						$error_message = true;
					}

				} else {
					
					$update_message = 4;
					$error_message = true;
				}

			} else {
				
				$update_message = 4;
				$error_message = true;
			}
			
			break;

		case 'delete':

			check_admin_referer( 'bulk-plugins_page_register_post_types' );

			if ( ! empty( $_POST['select_post_types'] ) ) {
				
				$select_post_types = $_POST['select_post_types'];
				
				$registered_post_types = get_option( 'register_post_types_and_taxonomies__post_types', array() );
				
				foreach ( (array) $select_post_types as $post_type_id ) {
					
					if ( isset( $registered_post_types[$post_type_id] ) ) {
						unset( $registered_post_types[$post_type_id] );
					}
				}
				
				update_option( 'register_post_types_and_taxonomies__post_types', $registered_post_types );
				
				// We need to flush permalinks after this action
				update_option( 'register_post_types_and_taxonomies__flush_rewrite_rules', true );
				
				$count = count( $select_post_types );
				
				if ( (int) $count == 1 ) {
					$update_message = 2;
				} else {
					$update_message = 8;
				}
			}
			
			break;
		
		case 'bulk-delete':
			
		case 'edit':
			
		case 'editedposttype':

	}


	$post_types_table->prepare_items();

	$messages = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __( 'Post type added.', 'register_post_types_and_taxonomies' ),
		2 => __( 'Post type deleted.', 'register_post_types_and_taxonomies' ),
		3 => __( 'Post type updated.', 'register_post_types_and_taxonomies' ),
		4 => __( 'Post type not added.', 'register_post_types_and_taxonomies' ),
		5 => __( 'Post type not updated.', 'register_post_types_and_taxonomies' ),
		6 => __( 'Post type name is reserved.', 'register_post_types_and_taxonomies' ),
		7 => __( 'Post type was already registered.', 'register_post_types_and_taxonomies' ),
		8 => __( 'Post types deleted.', 'register_post_types_and_taxonomies' )
	);

	$message = '';
	if ( ! empty( $update_message ) ) {
		$message = $messages[$update_message];
	}

	?>

	<div class="wrap nosubsub">
		<h2>
			<?php echo esc_html( $title ); ?>
		</h2>

		<?php $class = ( $error_message ) ? 'error' : 'updated'; ?>

		<?php if ( ! empty( $message ) ) { ?>

			<div id="message" class="<?php echo $class; ?> notice is-dismissible"><p><?php echo $message; ?></p></div>

		<?php } ?>

		<div id="col-container">

			<div id="col-right">
				<div class="col-wrap">
					<form id="posts-filter" method="post">

						<?php $post_types_table->display(); ?>

						<br class="clear" />
					</form>
				</div>
			</div><!-- /col-right -->
			
			<div id="col-left">
				<div class="col-wrap">

					<div class="form-wrap">
						<h3><?php echo __( 'Add New Post Type', 'register_post_types_and_taxonomies' ); ?></h3>

						<form id="addposttype" method="post" action="<?php echo menu_page_url( $plugin_page, false ); ?>" class="validate">
							<input type="hidden" name="action" value="add-post-type" />
							
							<?php wp_nonce_field( 'add_post_type_nonce_action', 'add_post_type_nonce_name' ); ?>
							
							<div class="form-field form-required term-name-wrap">
								<label for="post-type-name"><?php _e( 'Name', 'register_post_types_and_taxonomies' ); ?></label>
								<input name="label" id="post-type-name" type="text" value="" size="40" />
								<p><?php _e( 'The name of the new Post Type.', 'register_post_types_and_taxonomies' ); ?></p>
							</div>
						
							<div class="form-field term-slug-wrap">
								<label for="post-type-slug"><?php _e( 'Slug', 'register_post_types_and_taxonomies' ); ?></label>
								<input name="slug" id="post-type-slug" type="text" value="" size="40" />
								<p><?php _e( 'The &#8220;slug&#8221; is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', 'register_post_types_and_taxonomies' ); ?></p>
							</div>
							
							<div class="form-field term-parent-wrap">
								<label for="post-type-public"><?php _e( 'Public?', 'register_post_types_and_taxonomies' ); ?></label>
								<select name="public" id="post-type-public" class="postform">
									<option class="level-0" value="1"><?php echo __( 'True', 'register_post_types_and_taxonomies' ); ?></option>
									<option class="level-0" value="0"><?php echo __( 'False', 'register_post_types_and_taxonomies' ); ?></option>
								</select>
								<p><?php _e( 'Controls how the type is visible to authors (show_in_nav_menus, show_ui) and readers (exclude_from_search, publicly_queryable).', 'register_post_types_and_taxonomies' ); ?></p>
								
							</div>
							
							<div class="form-field term-description-wrap">
								<label for="post-type-description"><?php _e( 'Description', 'register_post_types_and_taxonomies'  ); ?></label>
								<textarea name="description" id="post-type-description" rows="5" cols="40"></textarea>
								<p><?php _e( 'The description is not prominent by default; however, some themes may show it.', 'register_post_types_and_taxonomies' ); ?></p>
							</div>
							
							<div class="form-field term-has-archive-wrap">
								<label for="post-type-has-archive"><?php _e( 'Enables post type archives?', 'register_post_types_and_taxonomies'  ); ?></label>
								<select name="has_archive" id="post-type-has-archive" class="postform">
									<option class="level-0" value="1"><?php echo __( 'True', 'register_post_types_and_taxonomies' ); ?></option>
									<option class="level-0" value="0"><?php echo __( 'False', 'register_post_types_and_taxonomies' ); ?></option>
								</select>
								<p><?php _e( 'Will use <tt>$post_type</tt> as archive slug by default.', 'register_post_types_and_taxonomies' ); ?></p>
							</div>
							
							<?php do_action( 'register_post_types_and_taxonomies__add_post_types_form_fields' ); ?>
							
							<?php submit_button( __( 'Add New Post Type', 'register_post_types_and_taxonomies' ) ); ?>
						</form>

					</div><!-- /form-wrap -->
				</div><!-- /col-wrap -->
			</div><!-- /#col-left -->
		</div><!-- /#col-container -->

	</div><!-- /wrap -->

<?php 
}

// What will display on the Register Taxonomies page
function register_post_types_and_taxonomies__taxonomies_admin_panel() {
	global $title, $plugin_page;			
	
	$taxonomies_table = new Register_Post_Types_and_Taxonomies__Taxonomies();

	$update_message = 0;
	$error_message = false;

	switch ( $taxonomies_table->current_action() ) {

		case 'add-taxonomy':

			check_admin_referer( 'add_taxonomy_nonce_action', 'add_taxonomy_nonce_name' );

			if ( ! current_user_can( 'edit_theme_options' ) ) {
				wp_die( __( 'Cheatin&#8217; uh?', 'register_post_types_and_taxonomies' ), 403 );
			}
			
			// Plugin taxonomies
			$registered_taxonomies = get_option( 'register_post_types_and_taxonomies__taxonomies', array() );

			$get_taxonomies = get_taxonomies();

			$reserved_taxonomy_name = array();
			
			$reserved_taxonomy_name['attachment'] = 'attachment';
			$reserved_taxonomy_name['attachment_id'] = 'attachment_id';
			$reserved_taxonomy_name['author'] = 'author';
			$reserved_taxonomy_name['author_name'] = 'author_name';
			$reserved_taxonomy_name['calendar'] = 'calendar';
			$reserved_taxonomy_name['cat'] = 'cat';
			$reserved_taxonomy_name['category'] = 'category';
			$reserved_taxonomy_name['category__and'] = 'category__and';
			$reserved_taxonomy_name['category__in'] = 'category__in';
			$reserved_taxonomy_name['category__not_in'] = 'category__not_in';
			$reserved_taxonomy_name['category_name'] = 'category_name';
			$reserved_taxonomy_name['comments_per_page'] = 'comments_per_page';
			$reserved_taxonomy_name['comments_popup'] = 'comments_popup';
			$reserved_taxonomy_name['customize_messenger_channel'] = 'customize_messenger_channel';
			$reserved_taxonomy_name['customized'] = 'customized';
			$reserved_taxonomy_name['cpage'] = 'cpage';
			$reserved_taxonomy_name['day'] = 'day';
			$reserved_taxonomy_name['debug'] = 'debug';
			$reserved_taxonomy_name['error'] = 'error';
			$reserved_taxonomy_name['exact'] = 'exact';
			$reserved_taxonomy_name['feed'] = 'feed';
			$reserved_taxonomy_name['fields'] = 'fields';
			$reserved_taxonomy_name['hour'] = 'hour';
			$reserved_taxonomy_name['link_category'] = 'link_category';
			$reserved_taxonomy_name['m'] = 'm';
			$reserved_taxonomy_name['minute'] = 'minute';
			$reserved_taxonomy_name['monthnum'] = 'monthnum';
			$reserved_taxonomy_name['more'] = 'more';
			$reserved_taxonomy_name['name'] = 'name';
			$reserved_taxonomy_name['nav_menu'] = 'nav_menu';
			$reserved_taxonomy_name['nonce'] = 'nonce';
			$reserved_taxonomy_name['nopaging'] = 'nopaging';
			$reserved_taxonomy_name['offset'] = 'offset';
			$reserved_taxonomy_name['order'] = 'order';
			$reserved_taxonomy_name['orderby'] = 'orderby';
			$reserved_taxonomy_name['p'] = 'p';
			$reserved_taxonomy_name['page'] = 'page';
			$reserved_taxonomy_name['page_id'] = 'page_id';
			$reserved_taxonomy_name['paged'] = 'paged';
			$reserved_taxonomy_name['pagename'] = 'pagename';
			$reserved_taxonomy_name['pb'] = 'pb';
			$reserved_taxonomy_name['perm'] = 'perm';
			$reserved_taxonomy_name['post'] = 'post';
			$reserved_taxonomy_name['post__in'] = 'post__in';
			$reserved_taxonomy_name['post__not_in'] = 'post__not_in';
			$reserved_taxonomy_name['post_format'] = 'post_format';
			$reserved_taxonomy_name['post_mime_type'] = 'post_mime_type';
			$reserved_taxonomy_name['post_status'] = 'post_status';
			$reserved_taxonomy_name['post_tag'] = 'post_tag';
			$reserved_taxonomy_name['post_type'] = 'post_type';
			$reserved_taxonomy_name['posts'] = 'posts';
			$reserved_taxonomy_name['posts_per_archive_page'] = 'posts_per_archive_page';
			$reserved_taxonomy_name['posts_per_page'] = 'posts_per_page';
			$reserved_taxonomy_name['preview'] = 'preview';
			$reserved_taxonomy_name['robots'] = 'robots';
			$reserved_taxonomy_name['s'] = 's';
			$reserved_taxonomy_name['search'] = 'search';
			$reserved_taxonomy_name['second'] = 'second';
			$reserved_taxonomy_name['sentence'] = 'sentence';
			$reserved_taxonomy_name['showposts'] = 'showposts';
			$reserved_taxonomy_name['static'] = 'static';
			$reserved_taxonomy_name['subpost'] = 'subpost';
			$reserved_taxonomy_name['subpost_id'] = 'subpost_id';
			$reserved_taxonomy_name['tag'] = 'tag';
			$reserved_taxonomy_name['tag__and'] = 'tag__and';
			$reserved_taxonomy_name['tag__in'] = 'tag__in';
			$reserved_taxonomy_name['tag__not_in'] = 'tag__not_in';
			$reserved_taxonomy_name['tag_id'] = 'tag_id';
			$reserved_taxonomy_name['tag_slug__and'] = 'tag_slug__and';
			$reserved_taxonomy_name['tag_slug__in'] = 'tag_slug__in';
			$reserved_taxonomy_name['taxonomy'] = 'taxonomy';
			$reserved_taxonomy_name['tb'] = 'tb';
			$reserved_taxonomy_name['term'] = 'term';
			$reserved_taxonomy_name['theme'] = 'theme';
			$reserved_taxonomy_name['type'] = 'type';
			$reserved_taxonomy_name['w'] = 'w';
			$reserved_taxonomy_name['withcomments'] = 'withcomments';
			$reserved_taxonomy_name['withoutcomments'] = 'withoutcomments';
			$reserved_taxonomy_name['year'] = 'year';


			if ( ! empty( $_POST['label'] ) ) {

				if ( ! empty( $_POST['post-type'] ) ) {
					$taxonomy_id = sanitize_key( $_POST['label'] );
					
					if ( ! empty( $taxonomy_id ) ) {
						
						// Get maximum 20 characters
						$first_20_chrs_taxonomy_id = substr( $taxonomy_id, 0, 32 );
					
						if ( ! in_array( $first_20_chrs_taxonomy_id, $get_taxonomies ) ) {
			
							// Check if the taxonomy name is reserved
							if ( ! in_array( $first_20_chrs_taxonomy_id, $reserved_taxonomy_name ) ) {
								
								unset( $_POST['action'] );
								unset( $_POST['add_taxonomy_nonce_name'] );
								unset( $_POST['_wp_http_referer'] );
								unset( $_POST['submit'] );

								$registered_taxonomies[$first_20_chrs_taxonomy_id] = $_POST;
								
								update_option( 'register_post_types_and_taxonomies__taxonomies', $registered_taxonomies );
								
								// We need to flush permalinks after this action
								update_option( 'register_post_types_and_taxonomies__flush_rewrite_rules', true );
								
								$update_message = 1;
								
							} else {
								$update_message = 6;
								$error_message = true;
							}
						} else {
							$update_message = 7;
							$error_message = true;
						}
					} else {
							$update_message = 10;
							$error_message = true;
					}

				} else {
					
					$update_message = 9;
					$error_message = true;
				}

			} else {
				
				$update_message = 4;
				$error_message = true;
			}
			
			break;

		case 'delete':

			check_admin_referer( 'bulk-plugins_page_register_taxonomies' );

			if ( ! empty( $_POST['select_taxonomies'] ) ) {
				
				$select_taxonomies = $_POST['select_taxonomies'];
				
				$registered_taxonomies = get_option( 'register_post_types_and_taxonomies__taxonomies', array() );
				
				foreach ( (array) $select_taxonomies as $taxonomy_id ) {
					
					if ( isset( $registered_taxonomies[$taxonomy_id] ) ) {
						unset( $registered_taxonomies[$taxonomy_id] );
					}
				}
				
				update_option( 'register_post_types_and_taxonomies__taxonomies', $registered_taxonomies );
				
				// We need to flush permalinks after this action
				update_option( 'register_post_types_and_taxonomies__flush_rewrite_rules', true );
				
				$count = count( $select_taxonomies );
				
				if ( (int) $count == 1 ) {
					$update_message = 2;
				} else {
					$update_message = 8;
				}
			}
			
			break;
		
		case 'bulk-delete':
			
		case 'edit':
			
		case 'editedtaxonomy':

	}

	$taxonomies_table->prepare_items();

	$messages = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __( 'Taxonomy added.', 'register_post_types_and_taxonomies' ),
		2 => __( 'Taxonomy deleted.', 'register_post_types_and_taxonomies' ),
		3 => __( 'Taxonomy updated.', 'register_post_types_and_taxonomies' ),
		4 => __( 'Taxonomy not added.', 'register_post_types_and_taxonomies' ),
		5 => __( 'Taxonomy not updated.', 'register_post_types_and_taxonomies' ),
		6 => __( 'Taxonomy name is reserved.', 'register_post_types_and_taxonomies' ),
		7 => __( 'Taxonomy was already registered.', 'register_post_types_and_taxonomies' ),
		8 => __( 'Taxonomies deleted.', 'register_post_types_and_taxonomies' ),
		9 => __( 'Post Type not selected.', 'register_post_types_and_taxonomies' )
	);

	$message = '';
	if ( ! empty( $update_message ) ) {
		$message = $messages[$update_message];
	}

	?>

	<div class="wrap nosubsub">
		<h2>
			<?php echo esc_html( $title ); ?>
		</h2>

		<?php $class = ( $error_message ) ? 'error' : 'updated'; ?>

		<?php if ( ! empty( $message ) ) { ?>

			<div id="message" class="<?php echo $class; ?> notice is-dismissible"><p><?php echo $message; ?></p></div>

		<?php } ?>

		<div id="col-container">

			<div id="col-right">
				<div class="col-wrap">
					<form id="posts-filter" method="post">

						<?php $taxonomies_table->display(); ?>

						<br class="clear" />
					</form>
				</div>
			</div><!-- /col-right -->
			
			<div id="col-left">
				<div class="col-wrap">

					<div class="form-wrap">
						<h3><?php echo __( 'Add New Taxonomy', 'register_post_types_and_taxonomies' ); ?></h3>

						<form id="addnewtaxonomy" method="post" action="<?php echo menu_page_url( $plugin_page, false ); ?>" class="validate">
							<input type="hidden" name="action" value="add-taxonomy" />
							
							<?php wp_nonce_field( 'add_taxonomy_nonce_action', 'add_taxonomy_nonce_name' ); ?>
							
							<div class="form-field form-required term-name-wrap">
								<label for="taxonomy-name"><?php _e( 'Name', 'register_post_types_and_taxonomies' ); ?></label>
								<input name="label" id="taxonomy-name" type="text" value="" size="40" />
								<p><?php _e( 'The name of the new Taxonomy.', 'register_post_types_and_taxonomies' ); ?></p>
							</div>
						
							<div class="form-field term-slug-wrap">
								<label for="taxonomy-slug"><?php _e( 'Slug', 'register_post_types_and_taxonomies' ); ?></label>
								<input name="slug" id="taxonomy-slug" type="text" value="" size="40" />
								<p><?php _e( 'The &#8220;slug&#8221; is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', 'register_post_types_and_taxonomies' ); ?></p>
							</div>
							
							<div class="form-field term-parent-wrap">
								<label for="taxonomy-public"><?php _e( 'Public?', 'register_post_types_and_taxonomies' ); ?></label>
								<select name="public" id="taxonomy-public" class="postform">
									<option class="level-0" value="1"><?php echo __( 'True', 'register_post_types_and_taxonomies' ); ?></option>
									<option class="level-0" value="0"><?php echo __( 'False', 'register_post_types_and_taxonomies' ); ?></option>
								</select>
								<p><?php _e( 'Controls how the type is visible to authors (show_in_nav_menus, show_ui) and readers (exclude_from_search, publicly_queryable).', 'register_post_types_and_taxonomies' ); ?></p>
							</div>
							
							<?php
							$post_types_args = array(
							   '_builtin' => false
							);
							
							$post_types_args = apply_filters( 'register_post_types_and_taxonomies__post_types_args_filter', $post_types_args );
							?>
							
							<?php
							$get_post_types = get_post_types( $post_types_args ); 
							
							$get_post_types['post'] = 'post';
							?>
							
							<div class="form-field term-parent-wrap">
								<label for="taxonomy-post-type"><?php _e( 'Select Post Type', 'register_post_types_and_taxonomies' ); ?></label>
								<select name="post-type" id="taxonomy-post-type" class="postform">
								
									<?php 
									if ( ! empty( $get_post_types ) ) {
										foreach ( (array) $get_post_types as $post_type_id ) { 
											?>
											
											<option class="level-0" value="<?php echo $post_type_id; ?>"><?php echo $post_type_id; ?></option>
											
											<?php
										}
									} else {
										?>
										
										<option class="level-0" value=""><?php echo __( 'No post type', 'register_post_types_and_taxonomies' ); ?></option>
										
										<?php
									}
									?>
		
								</select>
								<p><?php _e( 'Select the post type from the list (only custom post types).', 'register_post_types_and_taxonomies' ); ?></p>
							</div>
							
							<div class="form-field term-parent-wrap">
								<label for="taxonomy-hierarchical"><?php _e( 'Hierarchical', 'register_post_types_and_taxonomies' ); ?></label>
								<select name="hierarchical" id="taxonomy-hierarchical" class="postform">
									<option class="level-0" value="0"><?php echo __( 'False', 'register_post_types_and_taxonomies' ); ?></option>
									<option class="level-0" value="1"><?php echo __( 'True', 'register_post_types_and_taxonomies' ); ?></option>
								</select>
								<p><?php _e( 'Is this taxonomy hierarchical (have descendants) like categories or not hierarchical like tags.', 'register_post_types_and_taxonomies' ); ?></p>
							</div>
							
							<div class="form-field term-description-wrap">
								<label for="taxonomy-description"><?php _e( 'Description', 'register_post_types_and_taxonomies'  ); ?></label>
								<textarea name="description" id="taxonomy-description" rows="5" cols="40"></textarea>
								<p><?php _e( 'The description is not prominent by default; however, some themes may show it.', 'register_post_types_and_taxonomies' ); ?></p>
							</div>
							
							<?php do_action( 'register_post_types_and_taxonomies__add_taxonomies_form_fields' ); ?>
							
							<?php submit_button( __( 'Add New Taxonomy', 'register_post_types_and_taxonomies' ) ); ?>
						</form>

					</div><!-- /form-wrap -->
				</div><!-- /col-wrap -->
			</div><!-- /col-left -->
		</div><!-- /#col-container -->

	</div><!-- /wrap nosubsub -->

<?php 

}