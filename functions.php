<?php
/**
 * Theme Functions
 *
 * Entire theme's function definitions.
 *
 * @since   1.0.0
 * @package WP
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Add featured image support.
add_theme_support( 'post-thumbnails' );

/**
 * Scripts & Styles.
 *
 * Frontend with no conditions, Add Custom styles to wp_head.
 *
 * @since  1.0.0
 */
function wpgt_scripts() {
	// Frontend scripts.
	if ( ! is_admin() ) {
    // Enqueue vendors first.
    wp_enqueue_script( 'wpgt_jqueryJs', get_template_directory_uri() . '/js/lib/jquery-3.3.1.min.js' );

		// Enqueue vendors first.
		wp_enqueue_script( 'wpgt_vendorsJs', get_template_directory_uri() . '/js/vendors.min.js', array( 'jquery' ), '1.0.0', true  );

		// Enqueue custom JS after vendors.
		wp_enqueue_script( 'wpgt_customJs', get_template_directory_uri() . '/js/custom.min.js', array( 'jquery' ), '1.0.0', true  );

		// Minified and Concatenated styles.
		wp_enqueue_style( 'wpgt_style', get_template_directory_uri() . '/css/style.min.css', array(), '1.0', 'all' );
	}
}
// Hook.
add_action( 'wp_enqueue_scripts', 'wpgt_scripts' );


// Register wp_nav_menu() menus
// http://codex.wordpress.org/Function_Reference/register_nav_menus
function register_theme_menus() {
  register_nav_menus(
    array(
      'primary' => __('Primary Navigation'),
      'secondary' => __('Footer Navigation')
    )
  );
}
add_action( 'init', 'register_theme_menus' );

/**
 * Custom walker class.
 */
class NavigationWalker extends Walker_Nav_Menu {

  /**
   * Starts the list before the elements are added.
   *
   * Adds classes to the unordered list sub-menus.
   *
   * @param string $output Passed by reference. Used to append additional content.
   * @param int    $depth  Depth of menu item. Used for padding.
   * @param array  $args   An array of arguments. @see wp_nav_menu()
   */
  function start_lvl( &$output, $depth = 0, $args = array() ) {
    // Depth-dependent classes.
    $indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
    $display_depth = ( $depth + 1); // because it counts the first submenu as 0
    $classes = array(
        'site-nav__sub-menu',
        ( $display_depth >=2 ? 'site-nav__sub-sub-menu' : '' ),
        'menu-depth-' . $display_depth
    );
    $class_names = implode( ' ', $classes );

    // Build HTML for output.
    $output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
  }

  /**
   * Start the element output.
   *
   * Adds main/sub-classes to the list items and links.
   *
   * @param string $output Passed by reference. Used to append additional content.
   * @param object $item   Menu item data object.
   * @param int    $depth  Depth of menu item. Used for padding.
   * @param array  $args   An array of arguments. @see wp_nav_menu()
   * @param int    $id     Current item ID.
   */
  function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
    global $wp_query;
    $indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent

    // Depth-dependent classes.
    $depth_classes = array(
        ( $depth == 0 ? 'site-nav__item' : 'site-nav__sub-item' ),
        ( $depth >=2 ? 'sub-sub-menu-item' : '' ),
        ( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),
        'menu-item-depth-' . $depth
    );
    $depth_class_names = esc_attr( implode( ' ', $depth_classes ) );

    // Passed classes.
    $classes = empty( $item->classes ) ? array() : (array) $item->classes;
    $class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );

    // Build HTML.
    $output .= $indent . '<li id="site-nav__item-'. $item->ID . '" class="' . $depth_class_names . ' ' . $class_names . '">';

    // Link attributes.
    $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
    $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
    $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
    $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
    $attributes .= ' class="menu-link ' . ( $depth > 0 ? 'site-nav__sub-link' : 'site-nav__link' ) . '"';

    // Build HTML output and pass through the proper filter.
    $item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
        $args->before,
        $attributes,
        $args->link_before,
        apply_filters( 'the_title', $item->title, $item->ID ),
        $args->link_after,
        $args->after
    );
    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
  }
}


/**
 * Register a coin post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function codex_book_init() {
	$labels = array(
		'name'               => _x( 'Coins', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'Coin', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'Coins', 'admin menu', 'your-plugin-textdomain' ),
		'name_admin_bar'     => _x( 'Coin', 'add new on admin bar', 'your-plugin-textdomain' ),
		'add_new'            => _x( 'Add New', 'coin', 'your-plugin-textdomain' ),
		'add_new_item'       => __( 'Add New Coin', 'your-plugin-textdomain' ),
		'new_item'           => __( 'New Coin', 'your-plugin-textdomain' ),
		'edit_item'          => __( 'Edit Coin', 'your-plugin-textdomain' ),
		'view_item'          => __( 'View Coin', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All Coins', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Search Coins', 'your-plugin-textdomain' ),
		'parent_item_colon'  => __( 'Parent Coins:', 'your-plugin-textdomain' ),
		'not_found'          => __( 'No coins found.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'No coins found in Trash.', 'your-plugin-textdomain' )
	);

	$args = array(
		'labels'             => $labels,
    'description'        => __( 'Description.', 'your-plugin-textdomain' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'coin' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'thumbnail', 'excerpt' ),
		'taxonomies'          => array( 'category' ),
	);

	register_post_type( 'coin', $args );
}
add_action( 'init', 'codex_book_init' );


require_once( 'coin/coin-market-cap.php' );
