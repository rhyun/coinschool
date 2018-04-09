<?php
/**
 * Template Name: Home Template
 */
  get_header();
  if ( have_posts() ) : while ( have_posts() ) : the_post();
?>

<section class="home-main home-section">
  <?php the_title(); ?>

  <?php //the_content(); ?>

  <?php echo do_shortcode("[cmc-dynamic-description]"); ?>
</section><!-- /.home-body -->
<?php endwhile; endif; ?>
<?php get_footer(); ?>
