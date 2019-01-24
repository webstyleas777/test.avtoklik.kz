<?php
/**
 * The template for displaying all single posts.
 *
 * @package understrap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
$container = get_theme_mod( 'understrap_container_type' );
?>






<div class="wrapper" id="single-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

		<div class="row">

			<!-- Do the left sidebar check -->
			<?php get_template_part( 'global-templates/left-sidebar-check' ); ?>

			<main class="site-main" id="main">

				<?php
// Retrieves the stored value from the database
$meta_url = get_post_meta( get_the_ID(), 'nedvizhimost', true );
?>
<main>
  <article>
    <?php the_post(); ?>
  <div class="titles">  <?php the_title(); ?></div>
    <?php the_content(); ?>

				<td><p><strong>ПЛОЩАДЬ : </strong><?php the_field('plo'); ?></p>
<p><strong>СТОИМОСТЬ : </strong><?php the_field('cost'); ?></p>
<p><strong>АДРЕС : </strong><?php the_field('adress'); ?></p>
<p><strong>ЖИЛАЯ ПЛОЩАДЬ : </strong><?php the_field('kvplo'); ?></p>
<p><strong>ЭТАЖ : </strong><?php the_field('atag'); ?></p>

</td>

					<?php understrap_post_nav(); ?>
    <div class="copyright-news">
        <?php if( !empty( $meta_url ) ) {
            echo 'По материалам: <a href="'.$meta_url.'" target="_blank">'.$meta_url.'</a>';
        } ?>
    </div>
  </article>
</main>

			

			<!-- Do the right sidebar check -->
			<?php get_template_part( 'global-templates/right-sidebar-check' ); ?>

		</div><!-- .row -->

	</div><!-- #content -->

</div><!-- #single-wrapper -->

<?php get_footer(); ?>
