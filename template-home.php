<?php
/**
 * Template Name: Home Template
 */
  get_header();
  if ( have_posts() ) : while ( have_posts() ) : the_post();
?>

<main class="home-main home-section">
  <?php the_content(); ?>
</main>
<?php endwhile; endif; ?>
<?php get_footer(); ?>
