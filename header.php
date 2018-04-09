<?php
/**
 * Theme Header
 *
 * Header data.
 *
 * @since   1.0.0
 * @package WP
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div class="site-container">
	<header class="site-header" role="banner">
    <nav class="site-nav js-site-nav">
      <?php if(get_field('logo', 'option')): ?>
      <h2 class="site-nav__logo">
        <a class="site-nav__logo-link" href="<?= esc_url(home_url('/')); ?>" title="Home" style="background-image: url('<?php echo get_field('logo', 'option'); ?>');"><?php bloginfo('name'); ?></a>
      </h2>
      <?php endif; ?>

      <?php
        wp_nav_menu( array(
          'theme_location' => 'primary',
          'menu_class'      => 'site-nav__menu',
          'walker' => new NavigationWalker()
         ) );
      ?>

    </nav>
  </header><!-- /.site-header -->

  <div id="content" class="site-content">