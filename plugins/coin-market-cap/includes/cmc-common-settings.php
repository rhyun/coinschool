<?php

$cmc_titan =  TitanFramework::getInstance( 'cmc_single_settings' );
	$cmc_panel = $cmc_titan->createAdminPanel( array(
		'name' => __('Coin Details Settings','cmc' ),
		'parent' => 'edit.php?post_type=cmc',
		
		) );

	$metaBox = $cmc_titan->createMetaBox( array(
		'name' => __('Settings','cmc' ),
		'post_type' => 'cmc',
		) );

	/* meta boxes */

	/**
	 * Create the options for metabox.
	 *
	 * Now we will create options for our metabox that we just created called `$aa_metbox`.
	 */

  $metaBox->createOption( array(
   'name'    =>  __('Select Currency','cmc' ),
    'desc'    => '',
   'id'      => 'old_currency',
   'type'    => 'select',
   'options' => array(
       'USD'   => 'USD', 
	   'GBP'   => 'GBP',
	   'EUR'   => 'EUR',
	   'INR'   => 'INR',
       'JPY'   => 'JPY',
	   'CNY'   => 'CNY',
	   'ILS'   => 'ILS',
	   'KRW'   => 'KRW',
	   'RUB'   => 'RUB',
	   
   ),
   'default' => 'USD',
   ) );
	$metaBox->createOption( array(
   'name'    => __( 'Coins Per Page','cmc' ),
    'desc'    => '',
   'id'      => 'show_currencies',
   'type'    => 'select',
   'options' => array(
       '10'   => '10',
       '25'   => '25',
       '50' => '50', 
       '100' =>  '100',
        '150' => '150',
        '200' => '200',
         '500' => '500',
        '1519' => __( 'All', 'cmc' ),
	   ),
	   'default' => '100',
	   ) );

	  // Add other metaboxes as needed
	   
  $metaBox->createOption( array(
    'name' => __('Display Price? (Optional)','cmc' ),
    'desc' => __('Select if you want to display Price?','cmc' ),
    'id'   => 'display_price',
    'type' => 'checkbox',
    'default'=>true,
    ) );
  $metaBox->createOption( array(
    'name' => __('Display Changes 1h? (Optional)','cmc' ),
    'desc' => __('Select if you want to display 1hour % Changes ?','cmc' ),
    'id'   => 'display_changes1h',
    'type' => 'checkbox',
    'default'=>false,
    ) );
  $metaBox->createOption( array(
    'name' => __('Display Changes 24h? (Optional)','cmc' ),
    'desc' => __('Select if you want to display 24hour % Changes ?','cmc' ),
    'id'   => 'display_changes24h',
    'type' => 'checkbox',
    'default'=>true
  
    ) );
  $metaBox->createOption( array(
    'name' => __('Display Changes 7d? (Optional)','cmc' ),
    'desc' => __('Select if you want to display 7days % Changes ?','cmc' ),
    'id'   => 'display_Changes7d',
    'type' => 'checkbox',
    'default'=>false
    ) );
    
  $metaBox->createOption( array(
    'name' => __('Display supply? (Optional)','cmc' ),
    'desc' => __('Select if you want to display Currency Available Supply?','cmc' ),
    'id'   => 'display_supply',
    'type' => 'checkbox',
	'default'=>true
    ) );

	
	$metaBox->createOption( array(
    'name' => __(' Volume 24h ? (Optional)','cmc' ),
    'desc' => __('Select if you want to display Currency Volume 24h ?','cmc' ),
    'id'   => 'display_Volume_24h',
    'type' => 'checkbox',
	'default'=>true,
    ) );
	
	
	$metaBox->createOption( array(
    'name' => __('Display Market Cap? (Optional)','cmc' ),
    'desc' => __('Select if you want to display Market Cap?','cmc' ),
    'id'   => 'display_market_cap',
    'type' => 'checkbox',
	'default'=>true,
    ) );
	
	 $metaBox->createOption( array(
    'name' => __('Display Price chart 7days?','cmc' ),
    'desc' => __('Select if you want to generate chart','cmc' ),
    'id'   => 'coin_price_chart',
    'type' => 'checkbox',
    'default'=>true,
    ) );  


   $metaBox->createOption( array(
    'name' => __('Enable Live Updates','cmc' ),
    'desc' => __('Select if you want to display Live Changes ?</br>(only supports currency USD)','cmc' ),
    'id'   => 'live_updates',
    'type' => 'checkbox',
    'default'=>false
    ) );
    
	$metaBox->createOption( array(
   'name'    => __( 'Single Coin Link Setting','cmc' ),
    'desc'    => __('Select if you want to open single page in a new tab','cmc' ),
   'id'      => 'single_page_type',
   'type' => 'checkbox',
   'default' => false
	   ) );
	
	
	

	/*-----meta boxes end here--- */



	/*----- CMC settings panel -----*/
   $cmc_panel->createOption( array(
    'name' => __('Dynamic Title','cmc'),
    'desc' => '',
    'id'   => 'dynamic_title',
    'type' => 'text',
    'desc' => __('<p>Placeholders:-<code>[coin-name],[coin-price],[coin-marketcap],[coin-changes]</code></p>','cmc'),
    'default'=>'[coin-name] current price is [coin-price].',
    ) );

   $cmc_panel->createOption( array(
    'name' => __('Dynamic Description','cmc'),
    'desc' => '',
    'id'   => 'dynamic_desciption',
    'type' => 'textarea',
    'desc' => __('<p>Placeholders:-<code>[coin-name],[coin-price],[coin-marketcap],[coin-changes]</code></p>','cmc'),
    'default'=>'[coin-name] current price is [coin-price] with a marketcap of [coin-marketcap]. Its price is [coin-changes] in last 24 hours.',
    ) );  
   
		$cmc_panel->createOption( array(
		'name' => __('Display Changes 1h? (Optional)','cmc'),
		'id' => 'display_changes1h_single',
		'type' => 'checkbox',
		
		'desc' => __('Select if you want to display 1hour % Changes ?','cmc'),
		'default'=>true
		) );
		
		$cmc_panel->createOption( array(
		'name' => __('Display Changes 24h? (Optional)','cmc'),
		'id' => 'display_changes24h_single',
		'type' => 'checkbox',
		'desc' => __('Select if you want to display 24hour % Changes ?','cmc'),
		'default' => true
	
		) );
		
		
		$cmc_panel->createOption( array(
		'name' => __('Display Changes 7d? (Optional)','cmc'),
		'id' => 'display_Changes7d_single',
		'type' => 'checkbox',
		'desc' => __('Select if you want to display 7days % Changes ?','cmc'),
	    'default'=>true
		) );
		
		$cmc_panel->createOption( array(
		'name' => __('Display supply? (Optional)','cmc'),
		'id' => 'display_supply_single',
		'type' => 'checkbox',
		'desc' => __('Select if you want to display Currency Available Supply?','cmc'),
		'default'=>true
		) );
		
		$cmc_panel->createOption( array(
       'name' => __(' Volume 24h ? (Optional)','cmc'),
       'desc' => __('Select if you want to display Currency Volume 24h ?','cmc'),
       'id'   => 'display_Volume_24h_single',
       'type' => 'checkbox',
	   'default'=>true
        ) );
		
		$cmc_panel->createOption( array(
    'name' => __('Display Market Cap? (Optional)','cmc'),
    'desc' => __('Select if you want to display Market Cap?','cmc'),
    'id'   => 'display_market_cap_single',
    'type' => 'checkbox',
	'default'=>true));
	$cmc_panel->createOption( array(
    'name'    => __('Chart Color','cmc'),
    'id'      => 'chart_color',
    'type' => 'color',
    'default' => '#8BBEED',
) );


	$cmc_panel->createOption( array(
   'name'    =>  __('Select Default Currency','cmc' ),
    'desc'    => '',
   'id'      => 'default_currency',
   'type'    => 'select',
   'options' => array(
       'USD'   => 'USD', 
     'GBP'   => 'GBP',
     'EUR'   => 'EUR',
     'INR'   => 'INR',
       'JPY'   => 'JPY',
     'CNY'   => 'CNY',
     'ILS'   => 'ILS',
     'KRW'   => 'KRW',
     'RUB'   => 'RUB',  
   ),
   'default' => 'USD',
   ) );  

  $cmc_panel->createOption( array(
    'name' => __('Facebok APP ID','cmc'),
    'desc' => '',
    'id'   => 'cmc_fb_app_Id',
    'type' => 'text',
    'default'=>'',
    ) );  


	$cmc_panel->createOption( array(
   'name'    => __( 'Twitter Feed Type','cmc' ),
	'desc'    => '<strong>'. __('In order to display Twitter Feed please install and activate <a target="_blank" href="https://wordpress.org/plugins/custom-twitter-feeds">Custom Twitter Feeds</a> plugin.','cmc').'</strong>',
	'id'      => 'twitter_feed_type',
   'type'    => 'select',
   'options' => array(
       'url'   => 'URL',
	   'hashtag'   => 'Hashtag',
       ),
	   'default' => 'url',
	   ) );
	
		
$cmc_panel->createOption( array(

		'type' => 'save'
		) );


function cmc_titan_checkbox_default_new_post_single( $default ) {
	return isset( $_GET['post'] ) ? '' : ( $default ? (string) $default : '' );
}

	
      $metaBox2 = $cmc_titan->createMetaBox( array(
		'name' => __('Coin Description','cmc' ),
		'post_type' => 'cmc-description',
		) );
		
	$metaBox2->createOption( array(
   'name'    =>  __('Select Coin','cmc' ),
    'desc'    => '',
   'id'      => 'des_coin_name',
   'type'    => 'select',
   'options' =>$this->cmc_coins_list(1500),
   'default' => '',
   ) );	

  	
	$metaBox2->createOption( array(
	'name' => __('Coin Description','cmc'),
	'id' => 'coin_description_editor',
	'type' => 'editor',
	'desc' => '',
	//'media_buttons' =>false,
	) );
	

	
	
?>