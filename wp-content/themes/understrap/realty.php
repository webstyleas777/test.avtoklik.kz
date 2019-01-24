<?php /* Template Name: Недвижимость */ ?>
<?php get_header(); ?>



<div class="wrapper" id="page-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

		<div class="row">

			<!-- Do the left sidebar check -->
			<?php get_template_part( 'global-templates/left-sidebar-check' ); ?>

			<main class="site-main" id="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'loop-templates/content', 'page' ); ?>

			

				<?php endwhile; // end of the loop. ?>
	
	
	 <!-- Начинается петля -->

 <?php $acsessuar = new WP_Query( array( 'post_type' => 'nedvizhimost', 'posts_per_page' => 10 ) ); ?>

 <?php while ( $acsessuar->have_posts() ) : $acsessuar->the_post(); ?>

 <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) : ?>
 <a class="post_thumbnail" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" alt=""><?php the_post_thumbnail(array( 400,200)); ?></a>
<?php endif; ?>
<a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
</div><!-- post -->
<?php endwhile; ?>
<?php wp_reset_postdata(); ?> 

 <!-- Заканчивается петля -->
	
	
	
	
			</main><!-- #main -->

			
			
			<!-- Do the right sidebar check -->
			<?php get_template_part( 'global-templates/right-sidebar-check' ); ?>

		</div><!-- .row -->

	</div><!-- #content -->

</div><!-- #page-wrapper -->

<?php get_footer(); ?>
