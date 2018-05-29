<?php
	/*
	Fetching all coins data and creating cache for 10 minute
	*/

	function cmc_coins_data($limit,$old_currency){
    	$coinslist= get_transient($old_currency.'-cmc-all-coins-data');
		 $price_index="price_".strtolower($old_currency);
         $m_index="market_cap_".strtolower($old_currency);
       	 $v_index="24h_volume_".strtolower($old_currency);
	   if( empty($coinslist) || $coinslist==="" ) {
	   	 	$request = wp_remote_get( 'https://api.coinmarketcap.com/v1/ticker/?limit='.$limit.'&convert='.$old_currency,array('timeout'=> 120));
			if( is_wp_error( $request ) ) {
				return false; // Bail early
			}
		$body = wp_remote_retrieve_body( $request );
			$coinslist = json_decode( $body );
			if( ! empty( $coinslist ) ) {
			 set_transient( $old_currency.'-cmc-all-coins-data', $coinslist, 5* MINUTE_IN_SECONDS);

			 if(is_array($coinslist)){
			foreach( $coinslist as $coin ) {
					$coin =(array)$coin;
					$coin_symbol=strtolower(str_replace(" ","-",$coin['name']));
					$c_list[$coin_symbol]=array(
						"price"=>$coin[$price_index],
						"price_usd"=>$coin['price_usd'],
						"marketcap"=>$coin[$m_index],
						"24h_volume"=>$coin[$v_index],
						"percent_change_24h"=>$coin['percent_change_24h'],
						"percent_change_1h"=>$coin['percent_change_1h'],
						"percent_change_7d"=>$coin['percent_change_7d'],
						"available_supply"=>$coin['available_supply'],
						"name"=>$coin['name'],
						"rank"=>$coin['rank'],
						"symbol"=>$coin['symbol'],
						"id"=>$coin['id'],
						"coin-slug"=>$coin['id']
						);
			}

			 set_transient($old_currency.'-cmc-single-page-data', $c_list, 5* MINUTE_IN_SECONDS);

				}
			}
		 }
			return $coinslist;
		}

		/*
			All coins array for single page
		*/

	   function cmc_coins_arr($old_currency){
				$coinslist= get_transient($old_currency.'-cmc-single-page-data');
				 $c_list=array();
				 $price_index="price_".strtolower($old_currency);
       			 $m_index="market_cap_".strtolower($old_currency);
       			 $v_index="24h_volume_".strtolower($old_currency);

				   if( empty($coinslist) || $coinslist==="" ) {
				   	 	$request = wp_remote_get( 'https://api.coinmarketcap.com/v1/ticker/?limit=1500&convert='.$old_currency,array('timeout'=> 120));

						if( is_wp_error( $request ) ) {
							return false; // Bail early
						}
						$body = wp_remote_retrieve_body( $request );
						$coinsdata = json_decode( $body );
						if( ! empty( $coinsdata ) ) {
						 	foreach($coinsdata as $coin){
								$coin =(array)$coin;
								$coin_symbol=strtolower(str_replace(" ","-",$coin['name']));

								$c_list[$coin_symbol]=array(
									"price"=>$coin[$price_index],
									"price_usd"=>$coin['price_usd'],
									"marketcap"=>$coin[$m_index],
									"24h_volume"=>$coin[$v_index],
									"percent_change_24h"=>$coin['percent_change_24h'],
									"percent_change_1h"=>$coin['percent_change_1h'],
									"percent_change_7d"=>$coin['percent_change_7d'],
									"available_supply"=>$coin['available_supply'],
									"name"=>$coin['name'],
									"rank"=>$coin['rank'],
									"symbol"=>$coin['symbol'],
									"id"=>$coin['id'],
									"coin-slug"=>$coin['id']
									);
							}
						 set_transient($old_currency.'-cmc-single-page-data', $c_list, 5* MINUTE_IN_SECONDS);
						 $coinslist=$c_list;
						}
					}

					if(!empty($coinslist ) && is_array($coinslist)) {
						return $coinslist;
						}
			}

       /*
		 Historical data for a given coin
		*/

		function cmc_historical_coins_arr($coin_id){

		$historical_coin_list= get_transient('historical-coin-data-'.$coin_id);
				 $historical_c_list=array();

				   if( empty($historical_coin_list) || $historical_coin_list==="" ) {
				   	 	$request = wp_remote_get( 'http://coincap.io/history/365day/'.$coin_id,array('timeout'=> 120));
				  	if( is_wp_error( $request ) ) {
							return false; // Bail early
						}
						$body = wp_remote_retrieve_body( $request );
						$historical_coinsdata = json_decode( $body );
						if( ! empty( $historical_coinsdata ) ) {
						 set_transient('historical-coin-data-'.$coin_id, $historical_coinsdata, 10* HOUR_IN_SECONDS);
						 $historical_coin_list=$historical_coinsdata;

						}
					}

					if(!empty($historical_coin_list )) {
						return $historical_coin_list;
						}
		}

		function cmc_histo_7days($coin_id){

		$historical_coin_list= get_transient('7d-historical-'.$coin_id);
				 $historical_c_list=array();

				   if( empty($historical_coin_list) || $historical_coin_list==="" ) {
				   	 	$request = wp_remote_get( 'https://min-api.cryptocompare.com/data/histohour?fsym='.$coin_id.'&tsym=USD&limit=60&aggregate=3&e=CCCAGG',array('timeout'=> 120));
				  	if( is_wp_error( $request ) ) {
							return false; // Bail early
						}
						$body = wp_remote_retrieve_body( $request );
						$historical_coinsdata = json_decode( $body );
						if( ! empty( $historical_coinsdata ) ) {
						 
						 foreach($historical_coinsdata->Data as $index=> $content){
						 		$coin_data[]=$content->close;
						 }
					
						 set_transient('7d-historical-'.$coin_id, $coin_data, 1* HOUR_IN_SECONDS);
						 $historical_coin_list=$coin_data;

						}
					}

					if(!empty($historical_coin_list )) {
						return $historical_coin_list;
						}
		}

		// fetching coin extra data for single page
		function cmc_coin_extra_data_api($coin_id){

		$coin_data= get_transient('coin-extra-data-'.$coin_id);

		 if( empty($coin_data) || $coin_data==="" ) {
			$r_url = wp_remote_get( 'https://us-central1-crypto-currencies-data-606eb.cloudfunctions.net/cryptocurrencies/'.$coin_id);

						if( is_wp_error( $r_url ) ) {
							return false; // Bail early
						}
						$body_url = wp_remote_retrieve_body( $r_url );
						$coins_data = json_decode( $body_url );
			if( ! empty( $coins_data ) ) {
			 set_transient('coin-extra-data-'.$coin_id,$coins_data, 10* HOUR_IN_SECONDS);
			 $coin_data=$coins_data;
			}
		}
					return $coin_data;
	}

	// grabing total currencies marketcap

	function cmc_get_global_data($old_currency){
		$global_data= get_transient($old_currency.'-cmc-global-data');
	   if( empty($global_data) || $global_data==="" ) {

	   	 	$request = wp_remote_get( 'https://api.coinmarketcap.com/v1/global/?convert='.$old_currency );
			if( is_wp_error( $request ) ) {
				return false; // Bail early
			}
		$body = wp_remote_retrieve_body( $request );
			$global_data = json_decode( $body );
			if( ! empty( $global_data ) ) {
			 set_transient($old_currency.'-cmc-global-data', $global_data, HOUR_IN_SECONDS);
			 }
		 }
			return $global_data;
		}

		//Using it for formatting large valaues in billion/million
	function cmc_format_coin_values($value, $precision = 2) {
	    if ($value < 1000000) {
	        // Anything less than a million
	        $formated_str = number_format($value);
	    } else if ($value < 1000000000) {
	        // Anything less than a billion
	       $formated_str = number_format($value / 1000000, $precision) . 'M';
	    } else {
	        // At least a billion
	       $formated_str= number_format($value / 1000000000, $precision) . 'B';
	    }

    return $formated_str;
    }

	function format_number($n){

	if($n < 0.50){
	return	$formatted = number_format($n, 6, '.', ',');
	}
	else{
	return	$formatted = number_format($n, 2, '.', ',');
    }
	}

	function cmc_get_settings($post_id,$index){
		if($post_id && $index){
		$val=get_post_meta($post_id,$index,true);
		if($val){
			return true;
			}else{
				return false;
			}
		}
	}

// creating coins logo html
	function coin_logo_html($coin_id,$size=32){
	$logo_html='';
	$coin_svg=CMC_PATH.'/assets/coins-logos/'.strtolower($coin_id).'.svg';
	$coin_png=CMC_PATH.'/assets/coins-logos/'.strtolower($coin_id).'.png';
	$coin_alt=$coin_id;
    
    if(has_filter('cmc_alt_filter')) {
   	 $coin_alt = apply_filters('cmc_alt_filter', $coin_alt);
    }
if (file_exists($coin_svg)) {
		$coin_svg=CMC_URL.'assets/coins-logos/'.strtolower($coin_id).'.svg';
    	$logo_html='<img style="width:'.$size.'px;" id="'.$coin_id.'" alt="'.$coin_alt.'" src="'.$coin_svg.'">';

} else if(file_exists($coin_png)){

		if($size==32){
		$index="32x32";
		}else{
		$index="128x128";
		}
$coin_icon=CMC_URL.'assets/coins-logos/'.strtolower($coin_id).'.png';
 $logo_html='<img id="'.$coin_id.'" alt="'.$coin_alt.'" src="'.$coin_icon.'">';
	
	}else{
		if($size==32){
		$index="32x32";
		}else{
		$index="128x128";
		}

$coin_icon='https://res.cloudinary.com/coolplugins/image/upload/cryptocurrency-logos/'.$index.'/'.$coin_id. '.png';

  $logo_html='<img id="'.$coin_id.'" alt="'.$coin_alt.'" src="'.$coin_icon.'" onerror="this.src = \'https://res.cloudinary.com/pinkborder/image/upload/coinmarketcap-coolplugins/'.$index.'/default-logo.png\';">';
	}

	 return $logo_html;

	}


	//generating coin logo URL based upon coin id
	function coin_logo_url($coin_id,$size=32){
	$logo_html='';
	$coin_logo_info=array();
	$coin_svg=CMC_PATH.'/assets/coins-logos/'.$coin_id.'.svg';
	$coin_png=CMC_PATH.'/assets/coins-logos/'.$coin_id.'.png';
	
	if (file_exists($coin_svg)) {
		$coin_logo_info['logo']=$coin_id.'.svg';
		$coin_logo_info['local']=true;
		return $coin_logo_info;
	}else if(file_exists($coin_png)){
		$coin_logo_info['logo']=$coin_id.'.png';
		$coin_logo_info['local']=true;
		return $coin_logo_info;

		}
		else {
		if($size==32){
		$index="32x32";
		}else{
		$index="128x128";
		}
		
		$coin_icon='https://res.cloudinary.com/coolplugins/image/upload/cryptocurrency-logos/'.$index.'/'.$coin_id. '.png';
		$coin_logo_info['logo']=$coin_icon;
		$coin_logo_info['local']=false;
		return $coin_logo_info;

	}
}
	function cmc_coin_single_logo($coin_id){
	$logo_html='';
	$coin_svg=CMC_PATH.'/assets/coins-logos/'.strtolower($coin_id).'.svg';
	$size=128;
	if (file_exists($coin_svg)) {
		$coin_svg=CMC_URL.'assets/coins-logos/'.strtolower($coin_id).'.svg';
    	$logo_html='<img style="width:'.$size.'px;" id="'.$coin_id.'" alt="'.$coin_id.'" src="'.$coin_svg.'">';
    }else{
	$index="128x128";
	$coin_icon='https://res.cloudinary.com/coolplugins/image/upload/cryptocurrency-logos/'.$index.'/'.$coin_id. '.png';
	$logo_html='<img id="'.$coin_id.'" alt="'.$coin_id.'" src="'.$coin_icon.'" onerror="this.src = \'https://res.cloudinary.com/pinkborder/image/upload/coinmarketcap-coolplugins/'.$index.'/default-logo.png\';">';
	}
	return $logo_html;
	}

	// currencies symbol
	function cmc_old_cur_symbol($name){
		 $cc = strtoupper($name);
		    $currency = array(
		    "USD" => "&#36;" , //U.S. Dollar
		    "AUD" => "&#36;" , //Australian Dollar
		    "BRL" => "R&#36;" , //Brazilian Real
		    "CAD" => "C&#36;" , //Canadian Dollar
		    "CZK" => "K&#269;" , //Czech Koruna
		    "DKK" => "kr" , //Danish Krone
		    "EUR" => "&euro;" , //Euro
		    "HKD" => "&dollar;" , //Hong Kong Dollar
		    "HUF" => "Ft" , //Hungarian Forint
		    "ILS" => "&#x20aa;" , //Israeli New Sheqel
		    
			"INR" => "&#8377;", //Indian Rupee
		    "JPY" => "&yen;" , //Japanese Yen 
		    "MYR" => "RM" , //Malaysian Ringgit 
		    "MXN" => "&#36;" , //Mexican Peso
		    "NOK" => "kr" , //Norwegian Krone
		    "NZD" => "&#36;" , //New Zealand Dollar
		    "PHP" => "&#x20b1;" , //Philippine Peso
		    "PLN" => "&#122;&#322;" ,//Polish Zloty
		    "GBP" => "&pound;" , //Pound Sterling
		    "SEK" => "kr" , //Swedish Krona
		    
			"CHF" => "Fr " , //Swiss Franc
		    "TWD" => "NT&#36;" , //Taiwan New Dollar 
		    "THB" => "&#3647;" , //Thai Baht
		    "TRY" => "&#8378;", //Turkish Lira
		    
			"CNY" => "&yen;" , //China Yuan Renminbi
			'KRW'   => "&#8361;", //Korea (South) Won
			'RUB'   => "&#8381;", //Russia Ruble
			'SGD'   => "S&dollar;",  //Singapore Dollar
			'CLP'   => "&dollar;", //Chile Peso
			'IDR'   => "Rp ", //Indonesia Rupiah
			'PKR'   => "₨ ", //Pakistan Rupee
			'ZAR'   => "R ", //South Africa Rand
			'BTC'=>'&#579;'
			);
		    
		    if(array_key_exists($cc, $currency)){
		        return $currency[$cc];
		    }
	}

	function currencies_json(){

		 $currency = array(
		    "USD" => "&#36;" , //U.S. Dollar
		    "AUD" => "&#36;" , //Australian Dollar
		    "BRL" => "R&#36;" , //Brazilian Real
		    "CAD" => "C&#36;" , //Canadian Dollar
		    "CZK" => "K&#269;" , //Czech Koruna
		    "DKK" => "kr" , //Danish Krone
		    "EUR" => "&euro;" , //Euro
		    "HKD" => "&dollar;" , //Hong Kong Dollar
		    "HUF" => "Ft" , //Hungarian Forint
		    "ILS" => "&#x20aa;" , //Israeli New Sheqel
		    
			"INR" => "&#8377;", //Indian Rupee
		    "JPY" => "&yen;" , //Japanese Yen 
		    "MYR" => "RM" , //Malaysian Ringgit 
		    "MXN" => "&#36;" , //Mexican Peso
		    "NOK" => "kr" , //Norwegian Krone
		    "NZD" => "&#36;" , //New Zealand Dollar
		    "PHP" => "&#x20b1;" , //Philippine Peso
		    "PLN" => "&#122;&#322;" ,//Polish Zloty
		    "GBP" => "&pound;" , //Pound Sterling
		    "SEK" => "kr" , //Swedish Krona
		    
			"CHF" => "Fr " , //Swiss Franc
		    "TWD" => "NT&#36;" , //Taiwan New Dollar 
		    "THB" => "&#3647;" , //Thai Baht
		    "TRY" => "&#8378;", //Turkish Lira
		    
			"CNY" => "&yen;" , //China Yuan Renminbi
			'KRW'   => "&#8361;", //Korea (South) Won
			'RUB'   => "&#8381;", //Russia Ruble
			'SGD'   => "S&dollar;",  //Singapore Dollar
			'CLP'   => "&dollar;", //Chile Peso
			'IDR'   => "Rp ", //Indonesia Rupiah
			'PKR'   => "₨ ", //Pakistan Rupee
			'ZAR'   => "R ", //South Africa Rand
		    );
		return json_encode($currency);
	}
	
	 function cmc_crypto_currency_arr( $limit,$fiat_curr){

			$c_list= get_transient($fiat_curr.'-cmc-single-page-data');
		
			   if( empty($c_list) || $c_list==="" ) {

			   		 $second_cache= get_transient('cmc-arr-list');	

			   		  if(! empty($second_cache)&& is_array($second_cache) ) {

			   		  	return  $second_cache;
						 }
			   	 	$request = wp_remote_get( 'https://api.coinmarketcap.com/v1/ticker/?limit='.$limit);
					if( is_wp_error( $request ) ) {
						return false; // Bail early
					}
					$price_index="price_usd";
					$body = wp_remote_retrieve_body( $request );
					$coinslist = json_decode( $body );
					if( ! empty( $coinslist ) ) {
						foreach($coinslist as $coin){
							$coin =(array)$coin;
							$coin_index=strtolower(str_replace(" ","-",$coin['name']));
							$symbol=$coin['symbol'];
							$c_list[$symbol]=array("name"=>$coin['name'],"slug"=>$coin_index,"price"=>$coin[$price_index],
								"price_usd"=>$coin['price_usd']
						);
							 set_transient('cmc-arr-list',$c_list, 5* MINUTE_IN_SECONDS);	
						}
					
					}
				 }
				 
				return $c_list;
		
		}
		
	// currencies icons
	function cmc_get_currency_icon($old_currency){
		$currency_icon='';
		 $icon_array = array(
	    'USD' => 'fa fa-usd',
	    'GBP' => 'fa fa-gbp',
	    'EUR' => 'fa fa-eur',
	    'INR' => 'fa fa-inr',
	    'JPY' => 'fa fa-jpy',
	    'CNY' => 'fa fa-cny',
	    'ILS' => 'fa fa-ils',
	    'KRW' => 'fa fa-krw',
	    'RUB' => 'fa fa-rub',
	     'BTC' => 'fa fa-btc',
	    );
		 if(isset($icon_array[$old_currency])){
			$icon_cls=$icon_array[$old_currency];
			 $currency_icon='<i class="'.$icon_cls.'" aria-hidden="true"></i>';
        }else{
			  $currency_icon='<i class="fa fa-usd" aria-hidden="true"></i>';
   			 }
   		return $currency_icon;
	}

// generating single page coin chart
function get_coin_chart($coin_id){
	$output='';
	$coin_alt=$coin_id;
    
    if(has_filter('cmc_alt_filter')) {
   	 $coin_alt = apply_filters('cmc_alt_filter', $coin_alt);
    }

	if(cmc_get_coin_logo($coin_id)!=''){
	$chart_img='https://coolplugins.net/cryptoapi/cryptocharts/img/'.cmc_get_coin_logo($coin_id).'.png';
	$output='<div class="cmc-coin-chart"><img src="'.$chart_img.'" id="'.$coin_id.'.png" alt="'.$coin_alt.'" onerror="this.src = \'https://coolplugins.net/cryptoapi/cryptocharts/img/no-chart.png\';"></div>';	
	}
	else{
	$output='<div class="cmc-coin-chart">'.__('No Graphical Data','cmc').'</div>';	
	}
	return $output;
}

//fetching coin ids from firebase for logo and charts

function cmc_get_coin_logo($coin_id){
	$coinslist='';
	$coinslist= get_transient('cmc-coin-logos-ids');
	if( empty($coinslist) || $coinslist==="" ) {
		 $request = wp_remote_get( 'https://us-central1-crypto-currencies-images-ids.cloudfunctions.net/coinids');
	 if( is_wp_error( $request ) ) {
		 return false; // Bail early
	 }
   $body = wp_remote_retrieve_body( $request );
	 $coinslist =json_decode( $body );
	 $coinslist=(array) $coinslist;
	 if(is_array( $coinslist ) && count($coinslist)>0) {
			set_transient('cmc-coin-logos-ids', $coinslist, 48*HOUR_IN_SECONDS);
		}
	}
	if(is_array($coinslist)){
	if(isset($coinslist[$coin_id])){
		return $coinslist[$coin_id];
	}

	}
}

  function objectToArray($d) {
        if (is_object($d)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $d = get_object_vars($d);
        }
		
        if (is_array($d)) {
            /*
            * Return array converted to object
            * Using __FUNCTION__ (Magic constant)
            * for recursive call
            */
            return array_map(__FUNCTION__, $d);
        }
        else {
            // Return array
            return $d;
        }
    }

	function sort_by_gainers($a, $b)
		{
		  
	 if ($a['percent_change_24h'] < $b['percent_change_24h']) {
                return 1;
        } else if ($a['percent_change_24h'] > $b['percent_change_24h']) {
                return -1;
        } else {
                return 0;
        }

		}

	function sort_by_losers($a, $b)
		{
		
		return $a['percent_change_24h'] - $b['percent_change_24h'];

		}	

	/**
	 * Register scripts and styles
	 */
	 //add cdn and change js file functions
	 function cmc_register_scripts() {
		if ( ! is_admin() ) {
			
			if( ! wp_script_is( 'jquery', 'done' ) ){
                wp_enqueue_script( 'jquery' );
               }
			wp_enqueue_style('cmc-font-awesome','https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

			wp_register_script( 'cmc-datatables.net', 'https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js' );
			 
			wp_register_script( 'cmc-fixed-column-js', 'https://cdn.datatables.net/fixedcolumns/3.2.4/js/dataTables.fixedColumns.min.js', array( 'jquery','cmc-datatables.net'), false, true );	

			wp_register_script('bootstrapcdn','https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js');

          
            wp_register_style( 'dataTables_bootstrap4', 'https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css');	

		    wp_register_style( 'cmc-fixed-column', 'https://cdn.datatables.net/fixedcolumns/3.2.4/css/fixedColumns.dataTables.min.css' );	
			
			wp_register_style( 'cmc-custom',CMC_URL.'assets/css/cmc-custom.min.css');
			
			wp_register_style( 'cmc-bootstrap',CMC_URL.'assets/css/bootstrap.min.css' );	
		    wp_register_style( 'cmc-fixedcolumn-bootstrap', 'https://cdn.datatables.net/fixedcolumns/3.2.0/css/fixedColumns.bootstrap.min.css' );
		    
			wp_register_script( 'cmc-jquery-number', 'https://cdnjs.cloudflare.com/ajax/libs/df-number-format/2.1.6/jquery.number.min.js' );
			
			wp_register_script( 'cmc-js', CMC_URL . 'assets/js/cmc.min.js', array( 'jquery','cmc-datatables.net'), false, true );
			
        

            wp_register_script('cmc-typeahead', CMC_URL . 'assets/js/typeahead.bundle.min.js', array( 'jquery'), false, true );
             wp_register_script('cmc-handlebars', CMC_URL . 'assets/js/handlebars-v4.0.11.js', array( 'jquery'), false, true );
            $data=get_transient("cmc-coins-new-links");
         	wp_localize_script('cmc-js','coins_links_obj',$data);   		
		}
	}	



  	/* USD conversions */

	function cmc_usd_conversions($currency){
			$conversions= get_transient('cmc_usd_conversions');
		if( empty($conversions) || $conversions==="" ) {
		   	 	$request = wp_remote_get( 'https://us-central1-usd-conversion-data.cloudfunctions.net/conversions/');
		  	if( is_wp_error( $request ) ) {
					return false;
				}
				$currency_ids = array("USD","AUD","BRL" ,"CAD","CZK","DKK", "EUR","HKD","HUF","ILS","INR" ,"JPY" ,"MYR","MXN", "NOK","NZD","PHP" ,"PLN","GBP" ,"SEK","CHF","TWD","THB" ,"TRY","CNY","KRW","RUB", "SGD","CLP", "IDR","PKR", "ZAR" );

				$body = wp_remote_retrieve_body( $request );
				$conversion_data= json_decode( $body );
				$conversion_data=(array) $conversion_data;
				if(is_array($conversion_data) && count($conversion_data)>0) {
					foreach($conversion_data as $key=> $currency_price){
							if(in_array($key,$currency_ids)){
								$conversions[$key]=$currency_price;
							}
						
						
					}
				
			
				uksort($conversions, function($key1, $key2) use ($currency_ids) {
				    return (array_search($key1, $currency_ids) > array_search($key2, $currency_ids));
				});
			
		set_transient('cmc_usd_conversions',$conversions, 2* HOUR_IN_SECONDS);
				}
			}

			if($currency=="all"){
				
				return $conversions;

			}else{
				if(isset($conversions[$currency])){
					return $conversions[$currency];
				}
			}
	}

function cmc_get_page_slug(){
		$slug=get_transient('cmc-single-page-slug');
		if(!empty($slug)){
			return $slug;
		}else{
			return $slug="currencies";
		}
}

 function cmc_dynamic_style(){
 		$cmc_dynamic_css='';
  	 	$cmc_titan = TitanFramework::getInstance( 'cmc_single_settings' );
 		
 		return	$cmc_dynamic_css =$cmc_titan->getOption('cmc_dynamic_css');
   }
