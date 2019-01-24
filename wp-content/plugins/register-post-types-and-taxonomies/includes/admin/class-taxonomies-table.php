<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

// Class for custom taxonomies table
class Register_Post_Types_and_Taxonomies__Taxonomies extends WP_List_Table {

	function get_columns() {
		$columns = array(
			'cb'           => '<input type="checkbox" />',
			'label'        => __( 'Name', 'register_post_types_and_taxonomies' ),
			'post-type'    => __( 'Post Type', 'register_post_types_and_taxonomies' ),
			'description'  => __( 'Description', 'register_post_types_and_taxonomies' ),
			'slug'         => __( 'Slug', 'register_post_types_and_taxonomies' )
		);

		return $columns;
	}

	function prepare_items() {
		$get_columns           = $this->get_columns();
		$hidden                = array();
		$get_sortable_columns  = $this->get_sortable_columns();
		
		$this->_column_headers = array( $get_columns, $hidden, $get_sortable_columns );
		
		$get_option            = get_option( 'register_post_types_and_taxonomies__taxonomies', array() );
		
		$this->items           = $get_option;
	}
	
	function column_default( $item, $column_name ) {

		switch( (string) $column_name ) {
			case 'label':
			case 'post-type':
			case 'slug':
			case 'description':
				return $item[$column_name];
		}
	}
	
	function column_cb( $item ) {

		if ( ! empty( $item['label'] ) ) {
			$label = $item['label'];
			
			$id = sanitize_key( $label );

			$html = '<label class="screen-reader-text" for="cb-select-' . $id . '">' . sprintf( __( 'Select %s', 'register_post_types_and_taxonomies' ), $label ) . '</label>'
					. '<input type="checkbox" name="select_taxonomies[]" value="' . $id . '" id="cb-select-' . $id . '" />';
					
		} else {
			$html = '';
		}
		
		return $html;
	}
	
	protected function get_bulk_actions() {
		$actions = array();
		$actions['delete'] = __( 'Delete', 'register_post_types_and_taxonomies' );

		return $actions;
	}
	

}