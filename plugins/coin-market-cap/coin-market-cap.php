<?php

/**
 * Plugin Name:Coin Market Cap
 * Description:Create a website similar like CoinMarketCap.com using this <strong>Coin Market Cap & Crypto Prices</strong> WordPress plugin. This crypto plugin uses coinmarketcap.com api to grab live crypto prices, market cap, charts and other data related to cryptocurrency
 * Author:Cool Plugins Team
 * Author URI:https://www.cooltimeline.com/
 * Version: 2.3.1
 * License: GPL2
 * Text Domain:cmc
 * Domain Path:languages
 *
 * @package Coin Market Cap
 */
 /*

Copyright (C) 2016  Narinder Singh narinder99143@gmail.com

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CMC', '2.3.1' );
define( 'CMC_PRO_FILE', __FILE__ );
define( 'CMC_PATH', plugin_dir_path( CMC_PRO_FILE ) );
define('CMC_PLUGIN_DIR', plugin_dir_path( CMC_PRO_FILE ) );
define( 'CMC_URL', plugin_dir_url( CMC_PRO_FILE ) );
if( !defined( 'CMC_CSS_URL' ) ) {
    define( 'CMC_CSS_URL', plugin_dir_url( __FILE__ ) . 'css' );
}


/**
 * Class CoinMarketCap
 */
final class CoinMarketCap {

	/**
	 * Plugin instance.
	 *
	 *
	 * @access private
	 */
	private static $instance = null;
	public $shortcode_obj=null;

	/**
	 * Get plugin instance.
	 *
	 *
	 * @static
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @access private
	 */
	private function __construct() {

	   register_activation_hook( CMC_PRO_FILE, array( $this, 'cmc_activate' ) );
	   register_deactivation_hook( CMC_PRO_FILE, array( $this, 'cmc_deactivate' ) );

	    $this->cmc_installation_date();

	 	 add_action( 'tf_create_options', array( $this,'cmc_createMyOptions'));
	  	add_action( 'plugins_loaded', array( $this, 'cmc_init' ) );
		add_action( 'init',  array( $this,'cmc_post_type') );
		add_action( 'init',  array( $this,'cmc_description_post_type'),11 );
		 add_action('init', array($this, 'cmc_rewrite_rule'));
		add_filter( 'query_vars', array($this,'cmc_query_vars'));

		if(is_admin()){

			add_action( 'admin_init',array($this,'cmc_check_installation_time'));
		    add_action( 'admin_init',array($this,'cmc_spare_me'), 5 );
			add_action( 'add_meta_boxes', array( $this,'register_cmc_meta_box') );
			add_filter( 'manage_cmc_posts_columns',array($this,'set_custom_edit_cmc_columns'));
			add_action( 'manage_cmc_posts_custom_column' ,array($this,'custom_cmc_column'), 10, 2 );

			add_filter( 'manage_cmc-description_posts_columns', array($this,'set_custom_cmcd_book_columns' ));
			add_action( 'manage_cmc-description_posts_custom_column' , array($this,'custom_cmcd_column'), 10, 2 );

			add_action( 'save_post', array( $this,'save_cmc_settings'),10, 3 );
			add_action( 'add_meta_boxes_cmc',array($this,'cmc_add_meta_boxes'));

			//automatic updates
			require_once(dirname(__FILE__) . "/includes/class.wp-auto-plugin-update.php");
		/*	require_once(dirname(__FILE__) ."/includes/cmc-license-manager.php");
			$license_manager = new CMC_License_Manager(
			 'coin-market-cap',
			 'Coin Market Cap',
			 'cmc');
			 */
		}

		$this->cmc_includes();
		if(is_admin()){
         add_action( 'admin_enqueue_scripts',array( $this,'cmc_remove_wp_colorpicker'),99);
        }
	}


	public function cmc_remove_wp_colorpicker() {
        wp_dequeue_script( 'wp-color-picker-alpha' );
        }

		/**
		 * Run when deactivate plugin.
		 */
		public  function cmc_deactivate() {
			 flush_rewrite_rules();
		}

		public  function cmc_activate() {
			  $this->add_coin_details_page();
			 $this->cmc_rewrite_rule();
			  flush_rewrite_rules();

		}


	/**
	 * Load plugin function files here.
	 */
	public function cmc_includes() {

		require_once( 'titan-framework/titan-framework-embedder.php');

		require_once __DIR__ . '/includes/cmc-shortcode.php';
		require_once __DIR__ . '/includes/cmc-single-shortcode.php';

		 $this->shortcode_cmc=new CMC_Shortcode();
		  $this->shortcode_cmc_single=	new CMC_Single_Shortcode();
		}

		/**
		 * Code you want to run when all other plugins loaded.
		 */
		public function cmc_init() {
			load_plugin_textdomain( 'cmc', false, basename(dirname(__FILE__)) . '/languages/');
		}
		function cmc_rewrite_rule() {
		$page_id=get_option('cmc-coin-single-page-id');
		 add_rewrite_rule('currencies/?([^/]*)/?([^/]*)/?([^/]*)', 'index.php?page_id='.$page_id.'&coin_id=$matches[1]&coin_name=$matches[2]
		 	 &currency=$matches[3]
		 	', 'top');
		}

		function cmc_query_vars( $query_vars ){
			$query_vars[] = 'coin_id';
			$query_vars[] = 'coin_name';
			$query_vars[] = 'currency';
			return $query_vars;
		}

		function add_coin_details_page(){
		 	$post_data = array(
		    'post_title' => 'cmc currency details',
		    'post_type' => 'page',
		    'post_content'=>'
				[cmc-dynamic-description]
				[coin-market-cap-details]
				[cmc-coin-extra-data]
				[cmc-chart]
				<h3>More Info About Coin</h3>
				[coin-market-cap-description]
				<h3>Historical Data</h3>
				[cmc-history]
				<h3>Twitter News Feed</h3>
				[cmc-twitter-feed]
				<h3>Submit Your Reviews</h3>
				[coin-market-cap-comments]',
		     'post_status'   => 'publish',
		      'post_author'  => get_current_user_id(),
			);

			$single_page_id=get_option('cmc-coin-single-page-id');

			if('publish' === get_post_status( $single_page_id)){
					$update_post_data=array(
					 'ID'=>$single_page_id,
					 'post_content'=>'[cmc-dynamic-description]
	 				[coin-market-cap-details]
	 				[cmc-coin-extra-data]
	 				[cmc-chart]
	 				<h3>More Info About Coin</h3>
	 				[coin-market-cap-description]
	 				<h3>Historical Data</h3>
	 				[cmc-history]
	 				<h3>Twitter News Feed</h3>
	 				[cmc-twitter-feed]
	 				<h3>Submit Your Reviews</h3>
	 				[coin-market-cap-comments]',
				);
				$post_id = wp_update_post( $update_post_data );

			}else{
				$post_id = wp_insert_post( $post_data );
				update_option('cmc-coin-single-page-id',$post_id);
			}


 		}

		function cmc_createMyOptions(){
		require_once CMC_PLUGIN_DIR .'/includes/cmc-common-settings.php';
		}


		/*
		*	CMC post type for settings panel
		*/
	function cmc_post_type() {

		$labels = array(
			'name'                  => _x( 'Coin Market Cap', 'Post Type General Name', 'cmc' ),
			'singular_name'         => _x( 'Coin Market Cap', 'Post Type Singular Name', 'cmc' ),
			'menu_name'             => __( 'Coin Market Cap', 'cmc' ),
			'name_admin_bar'        => __( 'Post Type', 'cmc' ),
			'archives'              => __( 'Item Archives', 'cmc' ),
			'attributes'            => __( 'Item Attributes', 'cmc' ),
			'parent_item_colon'     => __( 'Parent Item:', 'cmc' ),
			'all_items'             => __( 'All Shortcodes', 'cmc' ),
			'add_new_item'          => __( 'Add New Shortcode', 'cmc' ),
			'add_new'               => __( 'Add New', 'cmc' ),
			'new_item'              => __( 'New Item', 'cmc' ),
			'edit_item'             => __( 'Edit Item', 'cmc' ),
			'update_item'           => __( 'Update Item', 'cmc' ),
			'view_item'             => __( 'View Item', 'cmc' ),
			'view_items'            => __( 'View Items', 'cmc' ),
			'search_items'          => __( 'Search Item', 'cmc' ),
			'not_found'             => __( 'Not found', 'cmc' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'cmc' ),
			'featured_image'        => __( 'Featured Image', 'cmc' ),
			'set_featured_image'    => __( 'Set featured image', 'cmc' ),
			'remove_featured_image' => __( 'Remove featured image', 'cmc' ),
			'use_featured_image'    => __( 'Use as featured image', 'cmc' ),
			'insert_into_item'      => __( 'Insert into item', 'cmc' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'cmc' ),
			'items_list'            => __( 'Items list', 'cmc' ),
			'items_list_navigation' => __( 'Items list navigation', 'cmc' ),
			'filter_items_list'     => __( 'Filter items list', 'cmc' ),
		);
		$args = array(
			'label'                 => __( 'CryptoCurrency Price Widget', 'cmc' ),
			'description'           => __( 'Post Type Description', 'cmc' ),
			'labels'                => $labels,
			'supports'              => array( 'title' ),
			'taxonomies'            => array(''),
			'hierarchical'          => false,
			'public' => false,  // it's not public, it shouldn't have it's own permalink, and so on
			'show_ui'               => true,
			'show_in_nav_menus' => false,  // you shouldn't be able to add it to menus
			'menu_position'         => 5,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive' => false,  // it shouldn't have archive page
			'rewrite' => false,  // it shouldn't have rewrite rules
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			 'menu_icon'           => 'dashicons-chart-area',
			'capability_type'       => 'page',
		);
		register_post_type( 'cmc', $args );

	}

		public	function register_cmc_meta_box()
			{
			add_meta_box( 'cmc-shortcode', 'Coin Market Cap shortcode',array($this,'cmc_shortcode_meta'), 'cmc', 'side', 'high' );
			}

		public	function cmc_shortcode_meta()
		{
	    $id = get_the_ID();
	    $dynamic_attr='';
	    echo '<p>Paste this shortcode in anywhere (page/post)</p>';
	   $dynamic_attr.="[coin-market-cap id=\"{$id}\"";
	   $dynamic_attr.=']';
	    ?>
	    <input type="text" class="cmc-regular-small" name="cmc_meta_box_text" id="cmc_meta_box_text" value="<?php echo htmlentities($dynamic_attr) ;?>" readonly/>

	    <?php
		}


		 function set_custom_edit_cmc_columns($columns) {
	   	 $columns['shortcode'] = __( 'Shortcode', 'cmc' );
	   return $columns;
		}

		function custom_cmc_column( $column, $post_id ) {
				switch ( $column ) {
			   case 'shortcode' :
					echo '<code>[coin-market-cap id="'.$post_id.'"]</code>';
					break;

			}
		}


		 function set_custom_cmcd_book_columns($currency_columns) {

		$currency_columns['coin_id'] = __( 'COIN ID', 'cmc' );

		return $currency_columns;
		}
		 function custom_cmcd_column( $currency_columns, $post_id ) {
			$cmcd_id=get_the_ID();
			$meta = get_post_meta($cmcd_id, 'cmc_single_settings_des_coin_name', true);
			switch ( $currency_columns ) {
			case 'coin_id' :
			echo $meta;
			break;
				}
		}

		function cmc_description_post_type() {

		$labels = array(
			'name'                  => _x( 'Coin Description', 'Post Type General Name', 'cmc' ),
			'singular_name'         => _x( 'Coin Description', 'Post Type Singular Name', 'cmc' ),
			'menu_name'             => __( 'Coin Description', 'cmc' ),
			'name_admin_bar'        => __( 'Post Type', 'cmc' ),
			'archives'              => __( 'Item Archives', 'cmc' ),
			'attributes'            => __( 'Item Attributes', 'cmc' ),
			'parent_item_colon'     => __( 'Parent Item:', 'cmc' ),
			'all_items'             => __( 'All Descriptions', 'cmc' ),
			'add_new_item'          => __( 'Add New Description', 'cmc' ),
			'add_new'               => __( 'Add New', 'cmc' ),
			'new_item'              => __( 'New Item', 'cmc' ),
			'edit_item'             => __( 'Edit Item', 'cmc' ),
			'update_item'           => __( 'Update Item', 'cmc' ),
			'view_item'             => __( 'View Item', 'cmc' ),
			'view_items'            => __( 'View Items', 'cmc' ),
			'search_items'          => __( 'Search Item', 'cmc' ),
			'not_found'             => __( 'Not found', 'cmc' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'cmc' ),
			'featured_image'        => __( 'Featured Image', 'cmc' ),
			'set_featured_image'    => __( 'Set featured image', 'cmc' ),
			'remove_featured_image' => __( 'Remove featured image', 'cmc' ),
			'use_featured_image'    => __( 'Use as featured image', 'cmc' ),
			'insert_into_item'      => __( 'Insert into item', 'cmc' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'cmc' ),
			'items_list'            => __( 'Items list', 'cmc' ),
			'items_list_navigation' => __( 'Items list navigation', 'cmc' ),
			'filter_items_list'     => __( 'Filter items list', 'cmc' ),
		);
		$args = array(
			'label'                 => __( 'Coin Description', 'cmc' ),
			'description'           => __( 'Post Type Description', 'cmc' ),
			'labels'                => $labels,
			'supports'              => array( 'title' ),
			'taxonomies'            => array(''),
			'hierarchical'          => false,
			'public' => false,  // it's not public, it shouldn't have it's own permalink, and so on
			'show_ui'               => true,
			'show_in_nav_menus' => false,  // you shouldn't be able to add it to menus
			'menu_position'         => 5,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive' => false,  // it shouldn't have archive page
			'rewrite' => false,  // it shouldn't have rewrite rules
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			 'menu_icon'           => 'dashicons-chart-area',
			'capability_type'       => 'page',

		);
		register_post_type( 'cmc-description', $args );


}


	/*
	For ask for reviews code
	*/

	function cmc_installation_date(){
		 $get_installation_time = strtotime("now");
   	 	  add_option('cmc_activation_time', $get_installation_time );
	}

	//check if review notice should be shown or not

	function cmc_check_installation_time() {

    $spare_me = get_option('cmcc_spare_me');
	    if( !$spare_me ){
	        $install_date = get_option( 'cmc_activation_time' );
	        $past_date = strtotime( '-1 days' );
	      if ( $past_date >= $install_date ) {
	     	 add_action( 'admin_notices', array($this,'cmc_display_admin_notice'));
	     		}
	    }
	}

	/**
	* Display Admin Notice, asking for a review
	**/
	function cmc_display_admin_notice() {
	    // wordpress global variable
	    global $pagenow;
	//    if( $pagenow == 'index.php' ){

	        $dont_disturb = esc_url( get_admin_url() . '?spare_me=1' );
	        $plugin_info = get_plugin_data( __FILE__ , true, true );
	        $reviewurl = esc_url( 'https://codecanyon.net/item/coin-market-cap-prices-wordpress-cryptocurrency-plugin/reviews/21429844' );

 printf(__('<div class="cmc-review wrap" style=" background: #fff !important;  border: 2px solid #d4d4d4 !important;   padding: 22px !important;  font-size: 16px !important;">You have been using <b> %s </b> for a while. We hope you liked it ! Please give us a quick rating, it works as a boost for us to keep working on the plugin !</br><div class="ccpw-review-btn" style=
		  "margin-top: 10px !important;"><a href="%s" class="button button-primary" target=
	            "_blank">Rate Now!</a><a href="%s" class="ccpw-review-done" style="margin-left: 10px !important;"> Already Done !</a></div></div>', $plugin_info['TextDomain']), $plugin_info['Name'], $reviewurl, $dont_disturb );


	   // }
	}

    // remove the notice for the user if review already done or if the user does not want to
	function cmc_spare_me(){
	    if( isset( $_GET['spare_me'] ) && !empty( $_GET['spare_me'] ) ){
	        $spare_me = $_GET['spare_me'];
	        if( $spare_me == 1 ){
	            add_option( 'cmcc_spare_me' , TRUE );
	        }
	    }
	}



		//Plugin rating
	function cmc_add_meta_boxes($post){
		add_meta_box(
                'cmc-feedback-section',
                __( 'Hopefully you are Happy with our plugin','cmc'),
                array($this,'cmc_right_section'),
                'cmc',
                'side',
                'low'
            );
	}

	function cmc_right_section($post, $callback){
        global $post;
        $pro_add='';
        $pro_add .=
        __('May I ask you to give it a 5-star rating on  ','cmc'). '<strong><a target="_blank" href="https://codecanyon.net/item/coin-market-cap-prices-wordpress-cryptocurrency-plugin/reviews/21429844">'.__('Codecanyon','cmc').'</a></strong>?'.'<br/>'.
         __('This will help to spread its popularity and to make this plugin a better one on  ','cmc').
        '<strong><a target="_blank" href="https://codecanyon.net/item/coin-market-cap-prices-wordpress-cryptocurrency-plugin/reviews/21429844">'.__('Codecanyon','cmc').'</a></strong><br/>
        <a target="_blank" href="https://codecanyon.net/item/coin-market-cap-prices-wordpress-cryptocurrency-plugin/reviews/21429844"><img src="https://res.cloudinary.com/cooltimeline/image/upload/v1504097450/stars5_gtc1rg.png"></a>
      <div><a href="https://coinmarketcapinfo.com/plugin/" target="_blank">'.__('View Demos','cmc').'</a></div><br/>
         <div>
		<iframe src="https://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fcryptowidget.coolplugins.net&width=122&layout=button&action=like&size=large&show_faces=false&share=true&height=65&appId=1798381030436021" width="122" height="65" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe></div>';
        echo $pro_add ;

    }



		/**
	 * Save shortcode when a post is saved.
	 *
	 * @param int $post_id The post ID.
	 * @param post $post The post object.
	 * @param bool $update Whether this is an existing post being updated or not.
	 */
function save_cmc_settings( $post_id, $post, $update ) {
	// Autosave, do nothing
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		        return;
		// AJAX? Not used here
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
		        return;
		// Check user permissions
		if ( ! current_user_can( 'edit_post', $post_id ) )
		        return;
		// Return if it's a post revision
		if ( false !== wp_is_post_revision( $post_id ) )
		        return;
    /*
     * In production code, $slug should be set only once in the plugin,
     * preferably as a class property, rather than in each function that needs it.
     */
    $post_type = get_post_type($post_id);

    // If this isn't a 'book' post, don't update it.
    if ( "cmc" != $post_type ) return;
	    // - Update the post's metadata.
   		 update_option('cmc-post-id',$post_id);

   		  delete_transient( 'cmc-coins' ); // Site
   		  delete_transient('cmc-coins-list');
   		  delete_transient('cmc-global-data');
	}

		public function cmc_coins_list($limit){
				$coinslist= get_transient('cmc_top_100_list');
			   	$c_list=array();
			   if( empty($coinslist) || $coinslist==="" ) {
			   	 	$request = wp_remote_get( 'https://api.coinmarketcap.com/v1/ticker/?limit='.$limit);
					if( is_wp_error( $request ) ) {
						return false; // Bail early
					}
					$body = wp_remote_retrieve_body( $request );
					$coinslist = json_decode( $body );
					if( ! empty( $coinslist ) ) {
					 set_transient( 'cmc_top_100_list', $coinslist,12 * HOUR_IN_SECONDS);
					}
				 }
				if(!empty($coinslist ) && is_array($coinslist)) {
				foreach( $coinslist as $coin ) {
					$c_list[$coin->symbol]=$coin->name;
				}
				return $c_list;
			}
		}

	} // class end

	function CoinMarketCap() {
		return CoinMarketCap::get_instance();
	}



$GLOBALS['CoinMarketCap'] = CoinMarketCap();
