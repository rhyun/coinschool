<?php
/**
 * Coin Market Cap Plugin Extensions
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

/**
 * Coin Market Cap Plugin Extensions
 *
 * Check if Coin Market Cap Plugin is active
 *
 * @since  1.0.0
 */
//include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	// if ( function_exists('cmc_description_post_type') ){
	// 	remove_action ('CALLED_HOOK','cmc_description_post_type');
	// 	add_action ('CALLED_HOOK','cmc_coin_post_type');
	// }
	// else {
	// 	add_action( 'admin_notices', 'my_plugin_patch_error' );
	// }
	// // error message
	// function my_plugin_patch_error() {
	// 	$class = 'notice notice-error';
	// 	$message = __( ' plugin patch (functions.php line ...) not workng any longer');
	// 	printf( '%2$s', $class, $message );
	// }

                   
/**
 * Filter the CMC Coin Description CPT to register more options.
 *
 * @param $args       array    The original CPT args.
 * @param $post_type  string   The CPT slug.
 *
 * @return array
 */
function cmc_description_filter_cpt( $args, $post_type ) {
	// If not CMC Description CPT, bail.
	if ( 'cmc-description' !== $post_type ) {
		return $args;
	}
	$labels = array(
		'name'                  => _x( 'Coins', 'Post Type General Name', 'cmc' ),
		'singular_name'         => _x( 'Coin', 'Post Type Singular Name', 'cmc' ),
		'menu_name'             => __( 'Coins', 'cmc' ),
		'add_new'               => __( 'Add New Coin', 'cmc' ),
	);
	// Add additional Products CPT options.
	$cmc_description_args = array(
		'label'                 => __( 'Coins', 'cmc' ),
		'labels'                => $labels,
		'capability_type'       => 'page',
		'can_export'            => true,
		'description'           => __( 'Post Type Description', 'cmc' ),
		'exclude_from_search'   => false,
		'has_archive' 					=> true, 
		'hierarchical'          => false,
		'menu_icon'           	=> 'dashicons-chart-area',
		'menu_position'         => 5,
		'public' 								=> false, // it's not public, it shouldn't have it's own permalink, and so on
		'publicly_queryable'    => true,
		'rewrite' 							=> false, // it shouldn't have rewrite rules
		'show_in_admin_bar'     => true,
		'show_in_nav_menus' 		=> true,
		'show_ui'               => true,
		'supports'              => array( 'title' ),
		'taxonomies'            => array( 'category' ),
	);
	// Merge args together.
	return array_merge( $args, $cmc_description_args );
}
add_filter( 'register_post_type_args', 'cmc_description_filter_cpt', 10, 2 );


// if ( function_exists('my_plugin_function') ){
// 	remove_action ('CALLED_HOOK','my_plugin_function');
// 	add_action ('CALLED_HOOK','my_NEW_plugin_function');
// } else {
// 	add_action( 'admin_notices', 'my_plugin_patch_error' );
// }

// function my_plugin_patch_error() {
// 	$class = 'notice notice-error';
// 	$message = __( ' plugin patch (coin-market-cap-extensions.php line ...) not workng any longer');
// 	printf( '%2$s', $class, $message );
// }
// function my_NEW_plugin_function(){
// 	//modified function here
// }



function remove_scripts_styles_footer() {
	//----- CSS
	wp_deregister_style('cmc-historical-datatable-css'); // Jetpack

	//----- JS
	//wp_deregister_script('devicepx'); // Jetpack
}

add_action('wp_footer', 'remove_scripts_styles_footer');

