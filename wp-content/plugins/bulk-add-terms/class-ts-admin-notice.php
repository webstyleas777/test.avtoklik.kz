<?php
/**
 * A library that shows various admin notices
 */
if( !class_exists( 'TS_Admin_Notice' ) ) :
	class TS_Admin_Notice {
		
		function __construct( $args = array() ) {

			if( !function_exists('wp_get_current_user') ) {
				require_once( ABSPATH . 'wp-includes/pluggable.php' );
			}

			$args = wp_parse_args( $args, array(
				'id' => 'sample-admin-notice',
				'dissmiss' => 'option',
				'type' => 'success',
				'notice' => 'A sample admin notice!',
				'priority' => 10,
			) );

			if( !in_array( $args['type'], array( 'success', 'error', 'warning', 'info' ) ) ) {
				$args['type'] = 'success';
			}

			$this->args = $args;

			global $ts_admin_notices;

			if( !is_array( $ts_admin_notices ) ) {
				$ts_admin_notices = array();
			}

			$id = $args['id'];
			$notice = $args['notice'];
			$uid = get_current_user_id();
			$cookie = 'ts_notice_' . $id . '_' . $uid . '_dismissed';

			$option = get_transient( $cookie );

			if( isset( $args['dissmiss'] ) && !empty( $args['dissmiss'] ) ) {
				switch( $args['dissmiss'] ) {
					case 'cookie':
						if( isset( $_COOKIE[ $cookie ] ) ) {
							return;
						}
						break;
					default:
						if( !empty( $option ) ) {
							return;
						}
						break;
				}
			}

			if( !isset( $ts_admin_notices[ $id ] ) ) {
				$ts_admin_notices[ $id ] = $args;
				add_action( 'admin_notices', ( is_callable( $notice ) ? $notice : array( $this, 'callback' ) ), $args['priority'] );
			}

			global $ts_admin_notices_js;

			if( $ts_admin_notices_js !== true ) {
				add_action( 'admin_footer', array( $this, 'footer' ), 10 );
				add_action( 'wp_ajax_ts_notice_dismiss', array( $this, 'dismiss_ajax' ), 10 );
				$ts_admin_notices_js = true;
			}

		}

		function callback() {

			$args = $this->args;

			$classes = array( 'notice' );
			$classes[] = 'ts-notice-' . $args['id'];
			$classes[] = 'notice-' . $args['type'];

			if( $args['dissmiss'] != false ) {
				$classes[] = 'is-dismissible';
			}

			?>
			<div class="<?php echo esc_attr( implode( ' ', $classes )  ); ?>"> 
				<?php echo wp_kses_post( wpautop( $args['notice'] ) ); ?>
			</div>
			<?php
		}

		function dismiss_ajax() {

			check_ajax_referer( 'ts_notice_dismiss', 'security' );

			if( isset( $_POST['notice_id'] ) ) {

				$uid = get_current_user_id();

				$cookie = 'ts_notice_' . $_POST['notice_id'] . '_' . $uid . '_dismissed';

				setcookie( $cookie, true, ( time() + YEAR_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );

				delete_transient( $cookie );

				set_transient( $cookie, true, 1 * YEAR_IN_SECONDS );

			}

			die();

		}

		function footer() {

			global $ts_admin_notices;

			$all_notices = $ts_admin_notices;

			if( empty( $ts_admin_notices ) ) {
				return;
			}

			?>
			<script type="text/javascript">
				(function($) {
					var nonce = '<?php echo esc_attr( wp_create_nonce( 'ts_notice_dismiss' ) ); ?>';
				<?php foreach ( $all_notices as $id => $notice ) : ?>
					$(document).on('click', '.ts-notice-<?php echo esc_attr( $id ); ?> .notice-dismiss', function() {
						$.ajax({
							type: "post",
							dataType: "json",
							url: ajaxurl,
							data: {
								action: 'ts_notice_dismiss',
								notice_id: '<?php echo esc_attr( $id ); ?>',
								security: nonce
							},
							success: function(response) {
							}
						});
					});
				<?php endforeach; ?>
				})(jQuery);
			</script>
			<?php

		}

	}
endif;