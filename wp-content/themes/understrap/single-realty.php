<?php get_header(); ?>
<?php
// Retrieves the stored value from the database
$meta_url = get_post_meta( get_the_ID(), 'nedvizhimost', true );
?>
<main>
  <article>
    <?php the_post(); ?>
    <?php the_title(); ?>
    <?php the_content(); ?>
    <div class="copyright-news">
        <?php if( !empty( $meta_url ) ) {
            echo 'По материалам: <a href="'.$meta_url.'" target="_blank">'.$meta_url.'</a>';
        } ?>
    </div>
  </article>
</main>
<?php get_footer(); ?>