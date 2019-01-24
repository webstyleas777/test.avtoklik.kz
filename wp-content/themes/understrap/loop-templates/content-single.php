<?php
/**
 * Single post partial template.
 *
 * @package understrap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header class="entry-header">

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="entry-meta">

			<?php understrap_posted_on(); ?>

		</div><!-- .entry-meta -->

	</header><!-- .entry-header -->

	<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>

	<div class="entry-content">

		<?php the_content(); ?>

		<?php
		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'understrap' ),
				'after'  => '</div>',
			)
		);
		?>

			<td><p><strong>ПЛОЩАДЬ : </strong><?php the_field('plo'); ?></p>
<p><strong>СТОИМОСТЬ : </strong><?php the_field('cost'); ?></p>
<p><strong>АДРЕС : </strong><?php the_field('adress'); ?></p>
<p><strong>ЖИЛАЯ ПЛОЩАДЬ : </strong><?php the_field('kvplo'); ?></p>
<p><strong>ЭТАЖ : </strong><?php the_field('atag'); ?></p>

</td>

					<?php understrap_post_nav(); ?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">

		<?php understrap_entry_footer(); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
