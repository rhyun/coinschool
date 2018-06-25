<?php

$cmc_titan =  TitanFramework::getInstance( 'cmc_single_settings' );
$cmc_panel = $cmc_titan->createAdminPanel( array(
    'name' => __('Coin Details Settings','cmc' ),
    'parent' => 'edit.php?post_type=cmc',

) );
$generalTab = $cmc_panel->createTab( array(
    'name' => 'General Settings',
) );

$extraTab = $cmc_panel->createTab( array(
    'name' => 'Extra Settings',
) );

$clearCache = $cmc_panel->createTab( array(
    'name' => 'Clear Cache',
) );
$docTab = $cmc_panel->createTab( array(
    'name' => 'Documentation',
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
        'GBP'   => 'GBP',
        'EUR'   => 'EUR',
        'INR'   => 'INR',
        'JPY'   => 'JPY',
        'CNY'   => 'CNY',
        'ILS'   => 'ILS',
        'KRW'   => 'KRW',
        'RUB'   => 'RUB',
        'USD'   => 'USD',
        'DKK'   => 'DKK',
        'PLN'   => 'PLN',
        'AUD'   => 'AUD',
        'BRL'   => 'BRL',
        'MXN'   => 'MXN',
        'SEK'   => 'SEK',
        'CAD'   => 'CAD',
        'HKD'   => 'HKD',
        'MYR'   => 'MYR',
        'SGD'   => 'SGD',
        'CHF'   => 'CHF',
        'HUF'   => 'HUF',
        'NOK'   => 'NOK',
        'THB'   => 'THB',
        'CLP'   => 'CLP',
        'IDR'   => 'IDR',
        'NZD'   => 'NZD',
        'TRY'   => 'TRY',
        'PHP'   => 'PHP',
        'TWD'   => 'TWD',
        'CZK'   => 'CZK',
        'PKR'   => 'PKR',
        'ZAR'   => 'ZAR',
        'BTC'=>'BTC'
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
        '100' =>  '100'
     
    ),
    'default' => '100',
) );

// Add other metaboxes as needed

$metaBox->createOption( array(
    'name' => __('Display Price? (Optional)','cmc' ),
    'desc' => __('Select if you want to <b>Display Price</b> ?','cmc' ),
    'id'   => 'display_price',
    'type' => 'checkbox',
    'default'=>true,
) );
$metaBox->createOption( array(
    'name' => __('Display Changes 1h? (Optional)','cmc' ),
    'desc' => __('Select if you want to display <b>1 Hour % Changes</b> ?','cmc' ),
    'id'   => 'display_changes1h',
    'type' => 'checkbox',
    'default'=>false,
) );
$metaBox->createOption( array(
    'name' => __('Display Changes 24h? (Optional)','cmc' ),
    'desc' => __('Select if you want to display <b>24 Hours % Changes</b> ?','cmc' ),
    'id'   => 'display_changes24h',
    'type' => 'checkbox',
    'default'=>true

) );
$metaBox->createOption( array(
    'name' => __('Display Changes 7d? (Optional)','cmc' ),
    'desc' => __('Select if you want to display <b>7 Days % Changes</b> ?','cmc' ),
    'id'   => 'display_Changes7d',
    'type' => 'checkbox',
    'default'=>false
) );



$metaBox->createOption( array(
    'name' => __('Display supply? (Optional)','cmc' ),
    'desc' => __('Select if you want to display currency <b>Available Supply</b> ?','cmc' ),
    'id'   => 'display_supply',
    'type' => 'checkbox',
    'default'=>true
) );

$metaBox->createOption( array(
    'name' => __(' Volume 24h ? (Optional)','cmc' ),
    'desc' => __('Select if you want to display currency <b>Volume 24H</b> ?','cmc' ),
    'id'   => 'display_Volume_24h',
    'type' => 'checkbox',
    'default'=>true,
) );


$metaBox->createOption( array(
    'name' => __('Display Market Cap? (Optional)','cmc' ),
    'desc' => __('Select if you want to display <b>Market Cap<b/> ?','cmc' ),
    'id'   => 'display_market_cap',
    'type' => 'checkbox',
    'default'=>true,
) );

$metaBox->createOption( array(
    'name' => __('Display Price chart 7days?','cmc' ),
    'desc' => __('Select if you want to display <b>7 Days Small Chart</b> in list ?','cmc' ),
    'id'   => 'coin_price_chart',
    'type' => 'checkbox',
    'default'=>true,
) );
$metaBox->createOption( array(
'name' => 'Select Chart Type',
'id' => 'cmc_chart_type',
'type' => 'radio',

'options' => array(
 'image-charts' =>'Image Charts',   
'svg-charts' =>'Dynamic SVG Charts',
),
'desc' => __('If you are loading more than 500 coins on one page then use <b>Image Charts</b><br/>otherwise it will good to use <b>Dynamic SVG Charts</b>.','cmc' ),
'default' => 'svg-charts',
) );

$metaBox->createOption( array(
    'name' => __('Enable Live Updates','cmc' ),
    'desc' => __('Select if you want to display <b>Live Changes</b> ?','cmc' ),
    'id'   => 'live_updates',
    'type' => 'checkbox',
    'default'=>false
) );
$metaBox->createOption( array(
    'name' => __('Enable Formatting','cmc' ),
    'desc' => __('Select if you want to display volumne and marketcap in <strong>(Million/Billion)</strong>','cmc' ),
    'id'   => 'enable_formatting',
    'type' => 'checkbox',
    'default'=>true
) );

$metaBox->createOption( array(
    'name'    => __( 'Single Coin Link Setting','cmc' ),
    'desc'    => __('Select if you want to open single page in a new tab ?','cmc' ),
    'id'      => 'single_page_type',
    'type' => 'checkbox',
    'default' => false
) );
$metaBox->createOption(array(
    'id' => 'cmc_ad_banners',
    'type' => 'custom',
    'name' => 'CryptoCurrency Exchange List PRO',
    'custom' => '<a href="https://bit.ly/cryptocurrency-exchanges" target="_blank"><img style="width:100%;height:auto;" src="https://res.cloudinary.com/coolplugins/image/upload/crypto-exchanges-plugin/banner-crypto-exchanges.png" /></a>
',
));
$metaBox->createOption(array(
    'id' => 'cmc_ad_banners2',
    'type' => 'custom',
    'name' => 'CryptoCurrency Price Ticker Widget PRO',
    'custom' => '<a href="https://bit.ly/crypto-widgets" target="_blank"><img style="width:100%;height:auto;" src="https://res.cloudinary.com/coolplugins/image/upload/crypto-exchanges-plugin/banner-crypto-widgets.png" /></a>',
));


/*-----meta boxes end here--- */



/*----- CMC settings panel -----*/


$generalTab->createOption( array(
    'name' => __('Dynamic Title','cmc'),
    'desc' => '',
    'id'   => 'dynamic_title',
    'type' => 'text',
    'desc' => __('<p>Placeholders:-<code>[coin-name],[coin-price],[coin-marketcap],[coin-changes]</code><br/>It will also used as <b>SEO title</b>.</p>','cmc'),
    'default'=>'[coin-name] current price is [coin-price].',
) );

$generalTab->createOption( array(
    'name' => __('Dynamic Description','cmc'),
    'desc' => '',
    'id'   => 'dynamic_desciption',
    'type' => 'textarea',
    'desc' => __('<p>Placeholders:-<code>[coin-name],[coin-price],[coin-marketcap],[coin-changes]</code><br/>It will also used as <b>SEO meta description</b>.</p>','cmc'),
    'default'=>'[coin-name] current price is [coin-price] with a marketcap of [coin-marketcap]. Its price is [coin-changes] in last 24 hours.',
) );

$generalTab->createOption( array(
    'name' => __('Display Description From API','cmc'),
    'desc' => __('Select if you want to display custom description from API','cmc'),
    'id'   =>'display_api_desc',
    'type' => 'checkbox',
    'default'=>true));
$generalTab->createOption( array(
    'name' => __('Display Changes 1h? (Optional)','cmc'),
    'id' => 'display_changes1h_single',
    'type' => 'checkbox',

    'desc' => __('Select if you want to display <b>1 Hour % Changes</b> ?','cmc'),
    'default'=>true
) );

$generalTab->createOption( array(
    'name' => __('Display Changes 24h? (Optional)','cmc'),
    'id' => 'display_changes24h_single',
    'type' => 'checkbox',
    'desc' => __('Select if you want to display <b>24 Hours % Changes</b> ?','cmc'),
    'default' => true

) );


$generalTab->createOption( array(
    'name' => __('Display Changes 7d? (Optional)','cmc'),
    'id' => 'display_Changes7d_single',
    'type' => 'checkbox',
    'desc' => __('Select if you want to display <b>7 Days % Changes</b> ?','cmc'),
    'default'=>true
) );

$generalTab->createOption( array(
    'name' => __('Display supply? (Optional)','cmc'),
    'id' => 'display_supply_single',
    'type' => 'checkbox',
    'desc' => __('Select if you want to display <b>Currency Available Supply</b> ?','cmc'),
    'default'=>true
) );

$generalTab->createOption( array(
    'name' => __(' Volume 24h ? (Optional)','cmc'),
    'desc' => __('Select if you want to display <b>Currency Volume 24h</b> ?','cmc'),
    'id'   => 'display_Volume_24h_single',
    'type' => 'checkbox',
    'default'=>true
) );

$generalTab->createOption( array(
    'name' => __('Display Market Cap? (Optional)','cmc'),
    'desc' => __('Select if you want to display <b>Market Cap</b> ?','cmc'),
    'id'   => 'display_market_cap_single',
    'type' => 'checkbox',
    'default'=>true));
$generalTab->createOption( array(
    'name' => __('Enable Formatting','cmc' ),
    'desc' => __('Select if you want to display volumne and marketcap in <strong>(Million/Billion)</strong>','cmc' ),
    'id'   => 's_enable_formatting',
    'type' => 'checkbox',
    'default'=>true
) );

$generalTab->createOption( array(
    'name'    => __('Chart Color','cmc'),
    'id'      => 'chart_color',
    'type' => 'color',
    'default' => '#8BBEED',
) );


$generalTab->createOption( array(
    'name'    =>  __('Select Default Currency','cmc' ),
    'desc'    => '',
    'id'      => 'default_currency',
    'type'    => 'select',
    'options' => array(
        'GBP'   => 'GBP',
        'EUR'   => 'EUR',
        'INR'   => 'INR',
        'JPY'   => 'JPY',
        'CNY'   => 'CNY',
        'ILS'   => 'ILS',
        'KRW'   => 'KRW',
        'RUB'   => 'RUB',
        'USD'   => 'USD',
        'DKK'   => 'DKK',
        'PLN'   => 'PLN',
        'AUD'   => 'AUD',
        'BRL'   => 'BRL',
        'MXN'   => 'MXN',
        'SEK'   => 'SEK',
        'CAD'   => 'CAD',
        'HKD'   => 'HKD',
        'MYR'   => 'MYR',
        'SGD'   => 'SGD',
        'CHF'   => 'CHF',
        'HUF'   => 'HUF',
        'NOK'   => 'NOK',
        'THB'   => 'THB',
        'CLP'   => 'CLP',
        'IDR'   => 'IDR',
        'NZD'   => 'NZD',
        'TRY'   => 'TRY',
        'PHP'   => 'PHP',
        'TWD'   => 'TWD',
        'CZK'   => 'CZK',
        'PKR'   => 'PKR',
        'ZAR'   => 'ZAR',
        'BTC'=>'BTC'
    ),
    'default' => 'USD',
) );

$generalTab->createOption( array(
    'name' => __('Facebok APP ID','cmc'),
    'desc' => '',
    'id'   => 'cmc_fb_app_Id',
    'type' => 'text',
    'default'=>'',
) );


$generalTab->createOption( array(
    'name'    => __( 'Twitter Feed Type','cmc' ),
    'desc'    => '<strong>'. __('In order to display Twitter Feed please install and activate ','cmc').'<a target="_blank" href="https://wordpress.org/plugins/custom-twitter-feeds">'. __('Custom Twitter Feeds','cmc').'</a>'. __(' plugin & authorize it with your twitter account.','cmc').'</strong>',
    'id'      => 'twitter_feed_type',
    'type'    => 'select',
    'options' => array(
        'url'   => 'URL',
        'hashtag'   => 'Hashtag',
    ),
    'default' => 'url',
) );

$generalTab->createOption( array(
   'name' => __('Affiliate Integration','cmc'),
   'desc' => '',
   'id'   => 'choose_affiliate_type',
   'type' => 'radio',
   'default'=>'changelly_aff_id',
    'options' => array(
   'changelly_aff_id' => 'Changelly',
   'any_other_aff_id' => 'Other Affiliate',
),
) );

$generalTab->createOption( array('name' => __('Changelly Affiliate ID','cmc'),
    'desc' => '',
   'id'   => 'affiliate_id',
   'type' => 'text',
   'desc' => '<p>'.__('In order to add Changelly Affiliate link .Please follow these steps:-','cmc').'<a  target="_blank" href="https://drive.google.com/file/d/1yMhXICDMaykPUQiuUx9uOFNP98JE6ELj/view">'.__('View Steps','cmc').'</a></p>',
  'default'=>'675b2e20174f',
)
);

$generalTab->createOption( array(
   'name' => __('Any Other Affiliate Link','cmc'),
   'desc' => '',
   'id'   => 'other_affiliate_link',
   'type' => 'text',
 'desc' => '<p>'.__('Please add other Affiliate link.','cmc').'</p>'
   
) );



$generalTab->createOption( array('type' => 'save') );

$extraTab->createOption( array(
    'name' => __('Change Coin Detail Page Slug','cmc'),
    'desc' => '',
    'id'   => 'single-page-slug',
    'type' => 'text',
    'desc' => __('This will update text in red color only:- http://coinmarketcap.coolplugins.net/<strong style="color:red;">currencies</strong>/{dynamic}/{dynamic}/<br/>
	Coin details page URL like:- http://coinmarketcap.coolplugins.net/currencies/BTC/bitcoin/ <br>','cmc'),
    'default'=>'',
) );
$extraTab->createOption( array(
    'id'   => 'imp_notice',
    'type' => 'custom',
    'name'=>'Important Notice :-',
    'custom' => '<h4>'.__('In order to update single page slug. Please follow bellow mentioned steps.','cmc').'</h4><ol>
   <li>'.__('Add Custom Slug and Click on Save changes button','cmc').'</li>
   <li>'.__('Go to <a href="'.admin_url( 'edit.php?post_type=cmc&page=edit.php%3Fpost_type%3Dcmc-coin-details-settings&tab=clear-cache' ).'">Clear Cache Tab</a> and Delete all API\'s Cache','cmc').'</li>
   <li>'.__('Then please Update your Permalink Settings.','cmc').'<a href="'.admin_url( 'options-permalink.php' ).'">Click Here to Update Settings</a></li>
   </ol>',
) );
$extraTab->createOption( array(
    'name' => 'Custom CSS',
    'id' => 'cmc_dynamic_css',
    'type' => 'code',
    'desc' => 'Put your custom CSS rules here',
    'lang' => 'css',
) );


$extraTab->createOption( array('type' => 'save') );

$clearCache ->createOption( array(
    'name' => 'Delete API\'s Cache',
    'type' => 'ajax-button',
    'action' => 'cmc_delete_cache',
    'label' => __( 'Delete', 'default' ),
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


/***CMC Documentation***/
$docTab->createOption( array(
    'name' => __('Global Data Shortcode','cmc'),
    'id'   => 'global_data_shortcode',
    'type' => 'custom',
    'custom' => '<code>[global-coin-market-cap]</code><br/><br/>
	            <code>[global-coin-market-cap currency="GBP"]</code> (For Specific Currency!)<br><br>
                <code>[global-coin-market-cap formatted="false"]</code>(without Million/billion)formatted values',
) );

$docTab->createOption( array(
    'name' => __('Top Gainers Shortcode','cmc'),
    'id'   => 'top_gainer_shortcode',
    'type' => 'custom',
    'custom' => '<code>[cmc-top type="gainers" currency="USD" show-coins="50"]</code>',
) );

$docTab->createOption( array(
    'name' => __('Top Losers Shortcode','cmc'),
    'id'   => 'top_loser_shortcode',
    'type' => 'custom',
    'custom' => '<code>[cmc-top type="losers" currency="USD" show-coins="50"]</code>',
) );

$docTab->createOption( array(
    'name' => __('Single Page Shortcodes','cmc'),
    'id'   => 'single_page',
    'type' => 'custom',
    'custom' => '<strong>'.__('Use below mentioned shortcodes on single page','cmc').'</strong>'
) );


$docTab->createOption( array(
    'name' => __('Dynamic Title Shortcode','cmc'),
    'id'   => 'dynamic_title_shortcode',
    'type' => 'custom',
    'custom' => '<code>[cmc-dynamic-title]</code>',
) );

$docTab->createOption( array(
    'name' => __('Dynamic Description Shortcode','cmc'),
    'id'   => 'dynamic_des_shortcode',
    'type' => 'custom',
    'custom' => '<code>[cmc-dynamic-description]</code>',
) );

$docTab->createOption( array(
    'name' => __('Changelly Buy/Sell Shortcode','cmc'),
    'id'   => 'buy_sell_shortcode',
    'type' => 'custom',
    'custom' => '<code>[cmc-affiliate-link]</code> (Display buy/sell buttons using changelly.com affiliate url.)',
) );

$docTab->createOption( array(
    'name' => __('Coin Market Cap Details Shortcode','cmc'),
    'id'   => 'dynamic_details_shortcode',
    'type' => 'custom',
    'custom' => '<code>[coin-market-cap-details]</code> (Display Price, Market Cap, Changes, Supply & Volume.)',
) );

$docTab->createOption( array(
    'name' => __('Extra Data Shortcode','cmc'),
    'id'   => 'dynamic_extra_data_shortcode',
    'type' => 'custom',
    'custom' => '<code>[cmc-coin-extra-data]</code> (Display coin social links and official website url.)',
) );


$docTab->createOption( array(
    'name' => __('Calculator Shortcode','cmc'),
    'id'   => 'calculator_shortcode',
    'type' => 'custom',
    'custom' => '<code>[cmc-calculator]</code>',
) );

$docTab->createOption( array(
    'name' => __('Custom Description Shortcode','cmc'),
    'id'   => 'custom_des_shortcode',
    'type' => 'custom',
    'custom' => '<code>[coin-market-cap-description]</code> (Show your custom content or content from api.)',
) );

$docTab->createOption( array(
    'name' => __('Price Chart Shortcode','cmc'),
    'id'   => 'cmc_charts',
    'type' => 'custom',
    'custom' => '<code>[cmc-chart]</code>',
) );

$docTab->createOption( array(
    'name' => __('Historical Data Shortcode','cmc'),
    'id'   => 'historical_shortcode',
    'type' => 'custom',
    'custom' => '<code>[cmc-history]</code>',
) );

$docTab->createOption( array(
    'name' => __('Twitter News Feed Shortcode','cmc'),
    'id'   => 'twitter_shortcode',
    'type' => 'custom',
    'custom' => '<code>[cmc-twitter-feed]</code>',
) );

$docTab->createOption( array(
    'name' => __('Submit Reviews Shortcode','cmc'),
    'id'   => 'reviews_shortcode',
    'type' => 'custom',
    'custom' => '<code>[coin-market-cap-comments]</code> (Display facebook comment box.)',
) );

$docTab->createOption(array(
    'id' => 'doc_ad_banners',
    'type' => 'custom',
    'name' => 'CryptoCurrency Exchange List PRO',
    'custom' => '<a href="https://bit.ly/cryptocurrency-exchanges" target="_blank"><img style="width:100%;height:auto;" src="https://res.cloudinary.com/coolplugins/image/upload/crypto-exchanges-plugin/banner-crypto-exchanges.png" /></a>
',
));
$docTab->createOption(array(
    'id' => 'doc_ad_banners2',
    'type' => 'custom',
    'name' => 'CryptoCurrency Price Ticker Widget PRO',
    'custom' => '<a href="https://bit.ly/crypto-widgets" target="_blank"><img style="width:100%;height:auto;" src="https://res.cloudinary.com/coolplugins/image/upload/crypto-exchanges-plugin/banner-crypto-widgets.png" /></a>',
));


?>