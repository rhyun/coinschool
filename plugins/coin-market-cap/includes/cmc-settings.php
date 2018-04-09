<?php

    /**
     * Initiate the metabox
     */
    /**
     * Initiate the metabox
     */
  
  //Main page setting section
   
   $cmc_cmb = new_cmb2_box( array(
        'id'            => 'cmc_settings',
        'title'         => __( 'Main Page Settings', 'cmb2' ),
        'object_types'  => array( 'cmc'), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ) );

   $cmc_cmb->add_field( array(
   'name'    => 'Show in Currency',
    'desc'    => '',
   'id'      => 'old_currency',
   'type'    => 'select',
   'options' => array(
       'USD'   => __( 'USD', 'cmb2' ),
       'EUR'   => __( 'EUR', 'cmb2' )
     
   ),
   'default' => 'USD',
   ) );
/*
$cmc_cmb->add_field( array(
   'name'    => 'Fetch Number of Currencies',
    'desc'    => '',
   'id'      => 'load_curriences',
   'type'    => 'text',
   'default' => '100'
   ) );
   */
	$cmc_cmb->add_field( array(
   'name'    => 'Currencies Per Page',
    'desc'    => '',
   'id'      => 'show_currencies',
   'type'    => 'select',
   'options' => array(
       '10'   => __( '10', 'cmb2' ),
       '25'   => __( '25', 'cmb2' ),
       '50' => __( '50', 'cmb2' ),
       '100' => __( '100', 'cmb2' ),
        '150' => __( '150', 'cmb2' ),
        '200' => __( '200', 'cmb2' ),
         '500' => __( '500', 'cmb2' ),
   ),
   'default' => '100',
   ) );
    // Add other metaboxes as needed
	   
    $cmc_cmb->add_field( array(
    'name' => 'Display Price? (Optional)',
    'desc' => 'Select if you want to display Price?',
    'id'   => 'display_price',
    'type' => 'checkbox',
    'default'=>cmc_set_checkbox_default_for_new_post( true ),
    ) );
    $cmc_cmb->add_field( array(
    'name' => 'Display Changes 1h? (Optional)',
    'desc' => 'Select if you want to display 1hour % Changes ?',
    'id'   => 'display_changes1h',
    'type' => 'checkbox',
    'default'=>cmc_set_checkbox_default_for_new_post( true ),
    ) );
     $cmc_cmb->add_field( array(
    'name' => 'Display Changes 24h? (Optional)',
    'desc' => 'Select if you want to display 24hour % Changes ?',
    'id'   => 'display_changes24h',
    'type' => 'checkbox',
    'default'=>true,
    'default'=>cmc_set_checkbox_default_for_new_post( true ),
    ) );
  $cmc_cmb->add_field( array(
    'name' => 'Display Changes 7d? (Optional)',
    'desc' => 'Select if you want to display 7days % Changes ?',
    'id'   => 'display_Changes7d',
    'type' => 'checkbox',
    'default'=>cmc_set_checkbox_default_for_new_post( true ),
    ) );
    
   	$cmc_cmb->add_field( array(
    'name' => 'Display supply? (Optional)',
    'desc' => 'Select if you want to display Currency Available Supply?',
    'id'   => 'display_supply',
    'type' => 'checkbox',
	'default'=>cmc_set_checkbox_default_for_new_post( true ),
    ) );

	
	$cmc_cmb->add_field( array(
    'name' => ' Volume 24h ? (Optional)',
    'desc' => 'Select if you want to display Currency Volume 24h ?',
    'id'   => 'display_Volume_24h',
    'type' => 'checkbox',
	'default'=>cmc_set_checkbox_default_for_new_post( true ),
    ) );
	
	
	$cmc_cmb->add_field( array(
    'name' => 'Display Market Cap? (Optional)',
    'desc' => 'Select if you want to display Market Cap?',
    'id'   => 'display_market_cap',
    'type' => 'checkbox',
	'default'=>cmc_set_checkbox_default_for_new_post( true ),
    ) );
	
  $cmc_cmb->add_field( array(
    'name' => 'Display Price chart 7days?',
    'desc' => 'Select if you want to generate chart',
    'id'   => 'coin_price_chart',
    'type' => 'checkbox',
    'default'=>cmc_set_checkbox_default_for_new_post( true ),
    ) );  
		
	function cmc_set_checkbox_default_for_new_post( $default ) {
	return isset( $_GET['post'] ) ? '' : ( $default ? (string) $default : '' );
}
