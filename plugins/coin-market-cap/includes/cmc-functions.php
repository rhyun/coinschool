<?php

		/*
			Fetching all coins data and creating cache for 10 minute
		*/

	function cmc_coins_data($limit,$old_currency,$post_id){
    	$coinslist= get_transient($old_currency.'-cmc-coins');
		 $price_index="price_".strtolower($old_currency);
         $m_index="market_cap_".strtolower($old_currency);
       	 $v_index="24h_volume_".strtolower($old_currency);
	   if( empty($coinslist) || $coinslist==="" ) {
	   	 	$request = wp_remote_get( 'https://api.coinmarketcap.com/v1/ticker/?limit='.$limit.'&convert='.$old_currency );
			if( is_wp_error( $request ) ) {
				return false; // Bail early
			}
		$body = wp_remote_retrieve_body( $request );
			$coinslist = json_decode( $body );
			if( ! empty( $coinslist ) ) {
			 set_transient( $old_currency.'-cmc-coins', $coinslist, 10* MINUTE_IN_SECONDS);

			 if(is_array($coinslist)){
			foreach( $coinslist as $coin ) {
					$coin =(array)$coin;
					$coin_symbol=$coin['symbol'];
					$c_list[$coin_symbol]=array(
						"price"=>$coin[$price_index],
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

			 set_transient($old_currency.'-cmc-coins-list', $c_list, 5* MINUTE_IN_SECONDS);

				}
			}
		 }
			return $coinslist;
		}

		/*
			All coins array for single page
		*/

	   function cmc_coins_arr($old_currency){
				$coinslist= get_transient($old_currency.'-cmc-coins-list-');
				 $c_list=array();
				 $price_index="price_".strtolower($old_currency);
       			 $m_index="market_cap_".strtolower($old_currency);
       			 $v_index="24h_volume_".strtolower($old_currency);

				   if( empty($coinslist) || $coinslist==="" ) {
				   	 	$request = wp_remote_get( 'https://api.coinmarketcap.com/v1/ticker/?limit=1500&convert='.$old_currency);

						if( is_wp_error( $request ) ) {
							return false; // Bail early
						}
						$body = wp_remote_retrieve_body( $request );
						$coinsdata = json_decode( $body );
						if( ! empty( $coinsdata ) ) {
						 	foreach($coinsdata as $coin){
								$coin =(array)$coin;
								$coin_symbol=$coin['symbol'];

								$c_list[$coin_symbol]=array(
									"price"=>$coin[$price_index],
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
						 set_transient($old_currency.'-cmc-coins-list', $c_list, 5* MINUTE_IN_SECONDS);
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
				   	 	$request = wp_remote_get( 'http://coincap.io/history/365day/'.$coin_id);
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
			 set_transient($old_currency.'-cmc-global-data', $global_data, DAY_IN_SECONDS);
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

	if (file_exists($coin_svg)) {
		$coin_svg=CMC_URL.'/assets/coins-logos/'.strtolower($coin_id).'.svg';
    	$logo_html='<img style="width:'.$size.'px;" id="'.$coin_id.'" alt="'.$coin_id.'" src="'.$coin_svg.'">';
		} else {
		if($size==32){
		$index="32x32";
		}else{
		$index="128x128";
		}
	$coin_icon='https://res.cloudinary.com/pinkborder/image/upload/coinmarketcap-coolplugins/'.$index.'/'. cmc_get_coin_logo($coin_id). '.png';
  	$logo_html='<img id="'.$coin_id.'" alt="'.$coin_id.'" src="'.$coin_icon.'">';
	}

	 return $logo_html;

	}
	//generating coin logo URL based upon coin id
	function coin_logo_url($coin_id,$size=32){
	$logo_html='';
	$coin_svg=CMC_PATH.'/assets/coins-logos/'.strtolower($coin_id).'.svg';

	if (file_exists($coin_svg)) {
	return 	$coin_svg=CMC_URL.'/assets/coins-logos/'.strtolower($coin_id).'.svg';

		} else {
		if($size==32){
		$index="32x32";
		}else{
		$index="128x128";
		}
	return $coin_icon='https://res.cloudinary.com/pinkborder/image/upload/coinmarketcap-coolplugins/'.$index.'/'. cmc_get_coin_logo($coin_id). '.png';

	}

	}
	// currencies symbol
	function cmc_old_cur_symbol($name){
		$currency_symbol='';
		if($name=="EUR"){
				$currency_symbol='€';
			}
			else if($name=="GBP"){
				$currency_symbol='£';
			}
			else if($name=="INR"){
				$currency_symbol='₹';
			}
			else if($name=="JPY"){
				$currency_symbol='¥‎';
			}
			else if($name=="CNY"){
				$currency_symbol='¥';
			}
			else if($name=="ILS"){
				$currency_symbol='₪';
			}
			else if($name=="KRW"){
				$currency_symbol='₩';
			}
			else if($name=="RUB"){
				$currency_symbol='₽';
			}else{
				$currency_symbol='$';
			}
		return $currency_symbol;
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
	$chart_img='https://coolplugins.net/cryptoapi/cryptocharts/img/'.cmc_get_coin_logo($coin_id).'.png';
	$output='<div class="cmc-coin-chart"><img src="'.$chart_img.'" id="'.$coin_id.'.png"></div>';
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
			set_transient('cmc-coin-logos-ids', $coinslist, 24*HOUR_IN_SECONDS);
		}
	}
	if(is_array($coinslist)){
	if(isset($coinslist[$coin_id])){
		return $coinslist[$coin_id];
	}

	}
}
