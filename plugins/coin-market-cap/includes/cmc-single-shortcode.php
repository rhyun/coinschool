<?php

class CMC_Single_Shortcode{

	function __construct(){

		if(!is_admin()){
	    add_action( 'wp_enqueue_scripts', array( $this, 'cmc_single_assets' ) );
		/*
		Features based Shortcode for coin single page
		*/
		// exchange main basic info handler
		add_shortcode( 'coin-market-cap-price', array($this,'cmc_coin_price'));
		add_shortcode( 'coin-market-cap-stats', array($this,'cmc_coin_stats'));
		add_shortcode( 'coin-market-cap-details',array($this,'cmc_currencies_details'));

		// dynamic description 
		add_shortcode( 'coin-market-cap-description',array($this,'cmc_description'));
		
		add_shortcode( 'cmc-dynamic-description',array($this,'cmc_dynamic_description'));
		add_shortcode( 'cmc-dynamic-title',array($this,'cmc_dynamic_title'));
		add_shortcode( 'coin-market-cap-comments',array($this,'cmc_comment_box'));
		add_shortcode( 'cmc-chart',array($this,'cmc_chart_shortcode'));

		add_shortcode( 'cmc-twitter-feed',array($this,'cmc_twitter_feed'));


		add_shortcode( 'cmc-history',array($this,'cmc_historical_data'));

		add_shortcode( 'cmc-coin-extra-data',array($this,'cmc_coin_extra_data'));
		add_shortcode( 'cmc-affiliate-link',array($this,'cmc_affiliate_links'));

		add_filter( 'the_title',array($this,'cmc_custom_page_title'), 10, 2 );
        
      
		}

	 	add_filter( 'pre_get_document_title', array($this,'cmc_add_coin_name_to_title'), 10, 1);
		add_action( 'wp_head',array($this,'cmc_custom_meta_des'), 5 );
	
	 	/* Yoast Filter hooks */

		add_filter( 'wpseo_title', array($this,'cmc_add_coin_name_to_title'), 10, 1);
		add_filter( 'wpseo_opengraph_title', array($this,'cmc_add_coin_name_to_title'), 10, 1);
		add_filter( 'wpseo_metadesc', array($this,'cmc_open_graph_desc'),10,1);
		add_filter( 'wpseo_opengraph_desc',array($this,'cmc_open_graph_desc'),10,1);
		add_action('wp', array($this,'remove_canonical'));//After WP object is 

		//calculator
		require_once(CMC_PATH.'/includes/cmc-calculator.php');
		add_shortcode( 'cmc-calculator','cmc_calculator');
	}

	// Remove - Canonical for - [cmc currency details - Page]
    function remove_canonical() {
    if ( is_page('cmc-currency-details') ) {
      add_filter( 'wpseo_canonical', '__return_false',  10, 1 );
    }
    }

	/*
	  Single Page Top main Title
	*/

	function cmc_add_coin_name_to_title( $cmc_title) {
		global $post;
		 $single_page_id= get_option('cmc-coin-single-page-id');
		 if($post==null){
			 return;
		 }
		 if($post->ID==$single_page_id){
		$cmc_title=$this->cmc_generate_title($position='top');
			}
		/* Return the title. */
		return $cmc_title;
	}

	/*
	 Single coin page  title
	*/
	function cmc_custom_page_title( $title, $id = null ){
	  $single_page_id= get_option('cmc-coin-single-page-id');
	 if( $id==$single_page_id){
	  	  $title=$this->cmc_generate_title($position='default');
		}
		return $title;
	}

	/* Yoast open tag description */

	function cmc_open_graph_desc( $desc ) {
  	 $single_page_id= get_option('cmc-coin-single-page-id');
	 if(is_page($single_page_id)){
	 	return $desc=$this->cmc_generate_desc($position="top");
	  	
		}
	  return $desc;
	}

	

		/*Display custom meta description*/
		function cmc_custom_meta_des(){

		// coin detail page meta desc
		 global $post;
		if ($post == null){
			return;
		}

		 $single_page_id= get_option('cmc-coin-single-page-id');
		 if($post->ID==$single_page_id){
		 	$coin_symbol=get_query_var('coin_id');
 			$coin_name=trim(get_query_var('coin_name'));
 			global $wp;
			$single_page_slug=cmc_get_page_slug();
			$coin_url=esc_url( home_url($single_page_slug.'/'.$coin_symbol.'/'.$coin_name ,'/') );
			$desc=$this->cmc_generate_desc($position="top");
				$meta_des = esc_html($desc);
			echo'<link rel="canonical" href="'.$coin_url.'" />';
			
			if (!defined('WPSEO_VERSION') ) {
			echo'<meta name="description" content="'.$meta_des.'"/>';
			echo'<meta property="og:description" content="'.$meta_des.'"/>';
		    echo '<meta property="og:title" content="'. get_the_title().'"/>';
		
			$current_page= home_url( $wp->request );

	        echo '<meta property="og:type" content="article"/>';
	        echo '<meta property="og:url" content="'.$current_page.'"/>';
	        $site_name=get_bloginfo( 'name' );
	        // Customize the below with the name of your site
	        echo '<meta property="og:site_name" content="'.$site_name.'"/>';
	          $coin_id=(string) trim(get_query_var('coin_name'));
			if($coin_id){
			$logo= coin_logo_url($coin_id);
				if(isset($coin_icon['logo'])){
					$logo_path=CMC_PATH.'/assets/coins-logos/'.$logo;
			 echo '<meta property="og:image" content="'.$logo_path.'"/>';

		       		}
	   			}
	   		}	
		 }

	}


	/*
	Dynamic description for SEO
	*/

	function cmc_dynamic_description($atts, $content = null){
		 	$atts = shortcode_atts( array(
			'id'  => '',
			), $atts);
			$output='';
				$desc=$this->cmc_generate_desc($position="default");
			 $output.='<div class="cmc_dynamic_description"><p>
             '.$desc.'</p></div>';
			return $output;
	}

// fetching custom desciption
function cmc_generate_desc($position){
$desc='';
$cmc_titan =TitanFramework::getInstance( 'cmc_single_settings' );
$dynamic_desciption = $cmc_titan->getOption('dynamic_desciption');
$enable_formatting =$cmc_titan->getOption('s_enable_formatting');
	if(get_query_var('coin_id')){
		//changed to coin name 
 	   $coin_id=(string) trim(get_query_var('coin_name'));
 		$real_cur=get_query_var('currency');
 		$single_default_currency =$cmc_titan->getOption('default_currency');
		$old_currency=trim($real_cur)!=="" ?trim($real_cur):$single_default_currency;
		$all_coins=cmc_coins_arr($old_currency);
	    $currency_icon=cmc_old_cur_symbol($old_currency);
			
		if(isset($all_coins[$coin_id])){
			$name=$all_coins[$coin_id]['name'];
			$price=format_number($all_coins[$coin_id]['price']);
			
			if($enable_formatting){
			$marketcap=cmc_format_coin_values($all_coins[$coin_id]['marketcap']);
			}else{
			$marketcap=format_number($all_coins[$coin_id]['marketcap']);	
			}
			$marketcap=$marketcap?$currency_icon.$marketcap:__('N/A','cmc');
			$change_sign_minus = "-";
			$change_lbl='';
			    if ( strpos($all_coins[$coin_id]['percent_change_24h'], $change_sign_minus ) !==false) {
				$change_lbl=__('down','cmc');
			    }else{
			    $change_lbl=__('up','cmc');
			    }
		   $changes=$all_coins[$coin_id]['percent_change_24h'].'%';
		   $dynamic_array=array($name, $currency_icon.$price,$marketcap,$changes.' '.$change_lbl);
			$placeholders=array('[coin-name]','[coin-price]','[coin-marketcap]','[coin-changes]');
			 $desc=str_replace($placeholders,$dynamic_array,$dynamic_desciption);
			}
		return $desc;
		}

	}
/*--------------------------------end--------------------------- */	/*
	 *	Dynamic Title for SEO
	*/

	function cmc_dynamic_title($atts, $content = null){
		 	$atts = shortcode_atts( array(
			'id'  => '',
			), $atts);
			$output='';
			$desc='';
		$title_txt=$this->cmc_generate_title($position='default');
	$output='<h1 class="cmc-dynamic-title">'.$title_txt.'</h1>';
	return $output;
}


	//creating dynamic title
	function cmc_generate_title($position){
		$title_txt='';
	if(get_query_var('coin_id')){
		$cmc_titan =TitanFramework::getInstance( 'cmc_single_settings' );
		$dynamic_title = $cmc_titan->getOption('dynamic_title');
		$single_default_currency =$cmc_titan->getOption('default_currency');
		$enable_formatting =$cmc_titan->getOption('s_enable_formatting');
 		$coin_id=(string) trim(get_query_var('coin_name'));
 		$real_cur=get_query_var('currency');
		$old_currency=trim($real_cur)!=="" ?trim($real_cur):$single_default_currency;
		$all_coins=cmc_coins_arr($old_currency);
		$currency_icon=cmc_old_cur_symbol($old_currency);
	
		if(isset($all_coins[$coin_id])){
			$name=$all_coins[$coin_id]['name'].' ('.$all_coins[$coin_id]['symbol'].')';
			$price=format_number($all_coins[$coin_id]['price']);
			
			if($enable_formatting){
			$marketcap=cmc_format_coin_values($all_coins[$coin_id]['marketcap']);
			 }else{
			 	$marketcap=format_number($all_coins[$coin_id]['marketcap']);
			 }
			$marketcap=$marketcap?$currency_icon.$marketcap:__('N/A','cmc');

		$changes=$all_coins[$coin_id]['percent_change_24h'].'%';

			$dynamic_array=array($name,$currency_icon.$price,$marketcap,$changes);
			$placeholders=array('[coin-name]','[coin-price]','[coin-marketcap]','[coin-changes]');
			 $title_txt=str_replace($placeholders,$dynamic_array,$dynamic_title);
		 }
		 return  $title_txt;
	  }
}
/*--------------------------------end--------------------------- */
	// custom desciption from settings panel

	 function cmc_description($atts, $content = null){
	 	$atts = shortcode_atts( array(
		'id'  => '',
		), $atts);
		$output='';
		$cmc_titan =TitanFramework::getInstance( 'cmc_single_settings' );
		$description='';
		$display_api_desc = $cmc_titan->getOption('display_api_desc');
		if(get_query_var('coin_id')){
		 $coin_id=(string) trim(get_query_var('coin_name'));
		$coin_data=cmc_coin_extra_data_api(get_query_var('coin_id'));
		if(isset($coin_data)){

		if($display_api_desc){
		$description =isset($coin_data->description)?$coin_data->description:'';
		  }
		}
		// The Query
		$query=array('post_type' => 'cmc-description','meta_value' => get_query_var('coin_name'));
		$the_query = new WP_Query( $query );
		// The Loop
		if ( $the_query->have_posts() ) {

			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$cmcd_id=get_the_ID();
				$meta = get_post_meta($cmcd_id, 'cmc_single_settings_coin_description_editor', true);
				}
				/* Restore original Post Data*/
				wp_reset_postdata();
				}


			$coin_desc = isset($meta)?do_shortcode($meta):$description;
				if($coin_desc!=''){
				$output .='<div class="cmc-coin-info">'.$coin_desc.'</div>';
				}
			 }
				return $output;
	}

/*--------------------------------end--------------------------- */

	 // fb comment box shortcode
	  function cmc_comment_box($atts, $content = null){
	 	$atts = shortcode_atts( array(
		'id'  => '',
		), $atts);
		$output='';
		global $wp;
		$page_url=home_url($wp->request,'/');

		global $post;
		$page_id=$post->ID;
		$single_page_id= get_option('cmc-coin-single-page-id');

		if(is_page($page_id) && $page_id==$single_page_id)
		{
		$cmc_titan =TitanFramework::getInstance( 'cmc_single_settings' );
		$fb_app_id = $cmc_titan->getOption("cmc_fb_app_Id");
		$app_id=$fb_app_id?$fb_app_id:'1798381030436021';

		$output.='<div class="fb-comments" data-href="'.$page_url.'" data-width="100%" data-numposts="10"></div>';

		$output.='<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src ="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.12&appId='.$app_id.'&autoLogAppEvents=1";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, "script", "facebook-jssdk"));</script>';
		}

		return $output;
	 }

/*--------------------------------end--------------------------- */
	/*
	 Generating Coin details
	*/

	function cmc_currencies_details($atts, $content = null){
	// shortcode
	$atts = shortcode_atts( array(
		'id'  => '',
	), $atts, 'cmc' );
	$output='';
	$post_id=get_option('cmc-post-id');

	$cmc_titan =TitanFramework::getInstance( 'cmc_single_settings' );

	if(get_query_var('coin_id')){
		// changed from symbol to name based
 	 	$coin_id=(string) trim(get_query_var('coin_name'));
 		$real_cur=get_query_var('currency');
 		$single_default_currency =$cmc_titan->getOption('default_currency');
 		$enable_formatting =$cmc_titan->getOption('s_enable_formatting');

		$old_currency=trim($real_cur)!=="" ?trim($real_cur):$single_default_currency;
	
		$all_coins=cmc_coins_arr($old_currency);

		if(isset($all_coins[$coin_id])){
			$name=$all_coins[$coin_id]['name'];
			$coin_slug=$all_coins[$coin_id]['coin-slug'];

		$coin= $all_coins[$coin_id];
		$output='';
		$coin_released='365day';
	  	$mainId=$coin['id'];
	  	$coin_symbol=$coin['symbol'];
		$coin_name=$coin['name'];
		 $currency_icon=cmc_old_cur_symbol($old_currency);
		 $percent_change_7d=$coin['percent_change_7d'] . '%';
	     $percent_change_24h=$coin['percent_change_24h'] . '%';
		 $available_supply=$coin["available_supply"];
		 $price_index="price_".strtolower($old_currency);
	     $m_index="market_cap_".strtolower($old_currency);
	   	 $v_index="24h_volume_".strtolower($old_currency);
	   	
       	  if(isset($coin['price'])){
 			 $coin_price=format_number($coin['price']);
       		 }
       	  $volume='';
         if(isset($coin['24h_volume'])){
 			 $volume = $coin['24h_volume'];
 			 if($enable_formatting){
 			 	$volume=cmc_format_coin_values($volume);
 				}else{
 				$volume=format_number($volume);	
 				}
	   		}else{
	   		 $volume=__('N/A','cmc');
	   		}
         $market_cap='';
		 if(isset($coin['marketcap'])){
 			 $market_cap = $coin['marketcap'];
 			  if($enable_formatting){
 			 $market_cap=$currency_icon.cmc_format_coin_values($market_cap);
 			 	}else{
 			 	$market_cap=$currency_icon.format_number($market_cap);
 			 	}
	        }else{
	        	$market_cap=__('N/A','cmc');
	        }

 			if($enable_formatting){
 			 $available_supply=cmc_format_coin_values( $available_supply);
 			 	}else{
 			 	$available_supply=number_format( $available_supply);
 			 	}
	     

        $percent_change_1h = $coin['percent_change_1h'] . '%';
        $change_sign ='<i class="fa fa-arrow-up" aria-hidden="true"></i>';
        $change_class = 'cmc-up';
     	$change_sign_minus = "-";

     	 if ( strpos( $coin['percent_change_1h'], $change_sign_minus ) !==false) {
            $change_sign = '<i class="fa fa-arrow-down" aria-hidden="true"></i>';
            $change_class = 'cmc-down';
        }

 		  $change_sign_24h ='<i class="fa fa-arrow-up" aria-hidden="true"></i>';
          $change_class_24h = 'cmc-up';
         if ( strpos( $coin['percent_change_24h'], $change_sign_minus ) !==false) {
            $change_sign_24h = '<i class="fa fa-arrow-down" aria-hidden="true"></i>';
            $change_class_24h = 'cmc-down';
        }

          $change_sign_7d ='<i class="fa fa-arrow-up" aria-hidden="true"></i>';
          $change_class_7d = 'cmc-up';
         if ( strpos( $coin['percent_change_7d'], $change_sign_minus ) !==false) {
            $change_sign_7d = '<i class="fa fa-arrow-down" aria-hidden="true"></i>';
            $change_class_7d = 'cmc-down';
        }
        $all_c_p_html='';

         if($this->cmc_single_page_setting("display_changes1h_single")){
        $all_c_p_html.='<li  class="cmc_changes">
        <strong>'.__('1h % ','cmc').'</strong>
        <div class="cmc_coin_price_change" ><span class="'.$change_class.'">'.$change_sign.$percent_change_1h .'</span></div></li>';
      }

       if($this->cmc_single_page_setting("display_changes24h_single")){
        $all_c_p_html.='<li  class="cmc_changes">
        <strong>'.__('24h % ','cmc').'</strong>
        <div class="cmc_coin_price_change" ><span class="'.$change_class_24h.'">'.$change_sign_24h.$percent_change_24h.'</span></div></li>';
        }
         if($this->cmc_single_page_setting("display_Changes7d_single")){
         $all_c_p_html.='<li  class="cmc_changes">
        <strong>'.__('7d %','cmc').'</strong>
       <div class="cmc_coin_price_change" ><span class="'.$change_class_7d.'">'.$change_sign_7d.$percent_change_7d.'</span></div></li>';
  		 }
		$this->cmc_coin_details_assets();
		$coin_icon= cmc_coin_single_logo($mainId);
		$coin_price_html='';
		$coin_price_html.='<span class="ticker-price">' . $currency_icon. $coin_price . '</span>';

		if($coin_symbol=="MIOTA"){
	       	 	$coin_symbol='IOT';
	       }

			
		    $output.='<div class="cmc_coin_details"><ul>
	           <li><div class="ccpw_icon">'.$coin_icon.'</div>
	           <div class="ticker-name"><strong>' . $coin_name.'('.$coin_symbol.')</strong></div>
	           </li>
	          <li class="c_info pri"><strong>'.__('Price ','cmc').'</strong> <div class="chart_coin_price CCP-'.$coin_symbol.'">'.$coin_price_html.'</div></li>';
	          $output.=$all_c_p_html;

	           if($this->cmc_single_page_setting("display_market_cap_single")){
	          $output.='<li  class="c_info cap"><strong>'.__('Market Cap','cmc').'</strong> <div class="coin_market_cap"><span class="CCMC">'.$market_cap.'</span></div></li>';
	      		}
  			if($this->cmc_single_page_setting("display_Volume_24h_single")){
	           $output.=' <li  class="cmc_info vol"><strong>'.__('Volume','cmc').'</strong> <div class="cmc_coin_volume" ><span class="CCV-'.$coin_symbol.'">'.$currency_icon.$volume.'</span></div></li>';
	       }

	       if($this->cmc_single_page_setting("display_supply_single")){
			 $output.='<li  class="cmc_info sup"><strong>'.__('Available Supply','cmc').'</strong> <div class="cmc_coin_supply"><span class="CCS-'.$coin_symbol.'">'.$available_supply.'</span> <span class="coin-symbol">'.$coin_symbol.'</span></div></li>';
			}

		$output.='<li  class="cmc_info rank"><strong>'.__('Rank','cmc').'</strong> <div class="cmc_rank_badge"><span class="CCS-'.$coin_symbol.'">'.$coin['rank'].'</span></div></li>';
		  //$output.='<li class="cmc_rank_badge">'.__('Rank:','cmc').$coin['rank'].'</li>';

	      $output.='</ul></div>';


 		}else{
 		 return __('Currency Not Found','cmc');
 		}
 	}else{
 		 return __('Something wrong with URL','cmc');
 	}

return $output;

	}

	/*Historical data shortcode*/

	function cmc_historical_data($atts, $content = null){

	// shortcode
	$atts = shortcode_atts(array(
	'id'=>'',
	), $atts);

	$output='';
	wp_enqueue_style( 'cmc-historical-datatable-css');
	wp_enqueue_style('dataTables_bootstrap4');
	wp_enqueue_script( 'cmc-historical-datatable-js');

	//Initialize Titan
	$cmc_titan = TitanFramework::getInstance( 'cmc_single_settings' );
	$enable_formatting =$cmc_titan->getOption('s_enable_formatting');
	if(get_query_var('coin_id')){

 	$coin_id=get_query_var('coin_id');
	$historical_all_data=cmc_historical_coins_arr($coin_id);


	if(!empty($historical_all_data)){
	$count=count($historical_all_data->price);
	$all_c_p_html='';


	for($i=0;$i<$count;$i++){

			$price_index=$historical_all_data->price[$i][1];

			$volume_index=$historical_all_data->volume[$i][1];
			if($enable_formatting){
			$format_volume=cmc_format_coin_values($volume_index);
			}else{
			$format_volume=format_number($volume_index);	
			}
			$marketcap_index=$historical_all_data->market_cap[$i][1];
			if($enable_formatting){
			$format_marketcap=cmc_format_coin_values($marketcap_index);
			}else{
			$format_marketcap=format_number($marketcap_index);	
			}
			$time_index=$historical_all_data->price[$i][0];

			$milli_seconds =  $time_index;
			$time_stamp = $milli_seconds/1000;
			$time_index=date("d/m/Y", $time_stamp);
            $hidden_time_index=date("Y/m/d", $time_stamp);

			$all_c_p_html.='<tr>';
			$all_c_p_html.='<td data-order="'.$hidden_time_index.'">'.$time_index.'</td>
			<td>$'.$price_index.'</td>
			<td>$'.$format_volume.'</td>
			<td>$'.$format_marketcap.'</td>
		    </tr>';

	}

	$cmc_prev = __( 'Previous','cmc');
	$cmc_next = __( 'Next','cmc');				
	$cmc_show = __( 'Show','cmc');
	$cmc_entries = __( 'Entries','cmc');
	$cmc_show_entries = sprintf("%s _MENU_ %s",$cmc_show,$cmc_entries);
	
	$output.=' <table id="cmc_historical_tbl" class="display" data-order="[[ 0, &quot;desc&quot; ]]" data-show-entries="'.$cmc_show_entries.'" data-prev="'.$cmc_prev.'" data-next="'.$cmc_next.'">
	<thead> <tr>
	<th>'.__( 'Date', 'cmc' ).'</th>
            <th>'.__( 'Price', 'cmc' ).'</th>
            <th>'.__( 'Volume', 'cmc' ).'</th>
			<th>'.__( 'Market Cap', 'cmc' ).'</th>
        </tr> </thead><tbody>';
	$output.=$all_c_p_html;
	$output.= '</tbody>
	</table>';
	}
	else{
	return '<b>'.__('No Historical Data','cmc').'</b>';
	}
   }
else{
	return '<b>'.__('Something Wrong With URL','cmc').'</b>';
	}

	return $output;
    }


function cmc_affiliate_links($atts, $content = null){
		$atts = shortcode_atts( array(
			'id'  => '',
		), $atts);
			$output='';

	if(get_query_var('coin_id')){
		$coin_id=ucfirst(get_query_var('coin_id'));
		$coin_name=ucfirst(get_query_var('coin_name'));
		$cmc_titan =TitanFramework::getInstance( 'cmc_single_settings' );
		$affiliate_type =$cmc_titan->getOption('choose_affiliate_type');
	
		$buy_affiliate_link="#";
		$sell_affiliate_link="#";
		if($affiliate_type=="changelly_aff_id"){
			$affiliate_id='';
			$affiliate_id =$cmc_titan->getOption('affiliate_id');
			$buy_affiliate_link=sprintf('https://changelly.com/exchange/USD/%s/1?ref_id=%s',$coin_id,$affiliate_id);
			$sell_affiliate_link=sprintf('https://changelly.com/exchange/%s/BTC/1?ref_id=%s',$coin_id,$affiliate_id);

		}else if($affiliate_type=="any_other_aff_id"){
			$other_affiliate_link =$cmc_titan->getOption('other_affiliate_link');
			if($other_affiliate_link){
			$buy_affiliate_link=$other_affiliate_link;
			$sell_affiliate_link=$other_affiliate_link;
			}
		}
		
		$output='<span class="cmc_affiliate_links">
		<a target="_blank" class="cmc_buy" href="'. $buy_affiliate_link.'"><i class="fa fa-cart-plus" aria-hidden="true"></i> '.__('Buy','cmc').' '.$coin_name.'</a>
		<a target="_blank" class="cmc_sell" href="'.$sell_affiliate_link.'"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></i> '.__('Sell','cmc').' '.$coin_name.'</a>
		</span>';
	   
	}
	 return $output;	

}

	/*
		Single page chart shortcode handler
	*/

	function cmc_chart_shortcode($atts, $content = null){
		$atts = shortcode_atts( array(
		'id'  => '',
	), $atts);
		$output='';

if(get_query_var('coin_id')){
	
		$this->cmc_coin_details_assets();
		$cmc_titan =TitanFramework::getInstance( 'cmc_single_settings' );
		$single_default_currency =$cmc_titan->getOption('default_currency');
 		$coin_symbol=get_query_var('coin_id');
 		$real_cur=get_query_var('currency');
 		
		$old_currency=trim($real_cur)!=="" ?trim($real_cur):$single_default_currency;
		$chart_height='100%';
		$coin_released='365day';
  	
  		if($coin_symbol=="MIOTA"){
	       	$coin_symbol='IOT';
	       }


		$c_color = $cmc_titan->getOption('chart_color');

 		if(isset($c_color)&& !empty($c_color)){
	         	$chart_color=$c_color;
	         }else{
	         	$chart_color="#8BBEED";
	         }
			 
		$chart_from = __( 'From','cmc');
		$chart_to = __( 'To','cmc');
		$chart_zoom = __( 'Zoom','cmc');
		$output.='<div class="cmc-chart" data-coin-period="'. $coin_released.'" data-coin-id="'.$coin_symbol.'" data-chart-color="'.$chart_color.'" data-chart-from="'.$chart_from.'" data-chart-to="'.$chart_to.'" data-chart-zoom="'.$chart_zoom.'">';
		$output.='<img class="cmc-preloader" src="'.CMC_URL.'images/chart-loading.svg">';
		$output.='<div class="cmc-wrp"  id="CMC-CHART-'.$coin_symbol.'" style="width:100%; height:'.$chart_height.';" >
				</div></div>';

   }
		return $output;
	}


		//Shortcode for Twitter Feeds
	function cmc_twitter_feed(){
		if(get_query_var('coin_id')){
			$cmc_titan =TitanFramework::getInstance( 'cmc_single_settings' );

			$twitter_feed_type = $cmc_titan->getOption('twitter_feed_type');

			$coin_id=get_query_var('coin_name');
			$coin_symbol=get_query_var('coin_id');
			$coin_data=cmc_coin_extra_data_api($coin_symbol);

			$coin_name = isset($coin_data->twitterusername)?$coin_data->twitterusername:"";

		if($twitter_feed_type=='hashtag' || $coin_name==''){
						return do_shortcode( '[custom-twitter-feeds hashtag="#'.$coin_symbol.'"]' );
					  }
					  else{
						return do_shortcode( '[custom-twitter-feeds screenname="'.$coin_name.'"]' );
					  }

		}
	}

	function cmc_coin_extra_data($coin_id){

		if(get_query_var('coin_id')){
		$coin_id=get_query_var('coin_id');
		//grabing data from api
		$coin_data=cmc_coin_extra_data_api($coin_id);

		$blockexplorer = isset($coin_data->blockexplorer)?$coin_data->blockexplorer:"#";
		$website = isset($coin_data->website)?$coin_data->website:"#";
		$facebook = isset($coin_data->facebook)?$coin_data->facebook:"#";
		$firstannounced = isset($coin_data->firstannounced)?$coin_data->firstannounced:"";
		$github = isset($coin_data->github)?$coin_data->github:"#";
		$reddit = isset($coin_data->reddit)?$coin_data->reddit:"#";
		$twitter = isset($coin_data->twitter)?$coin_data->twitter:"#";
		$whitepaper = isset($coin_data->whitepaper)?$coin_data->whitepaper:"#";
		$youtube = isset($coin_data->youtube)?$coin_data->youtube:"#";

		$output='';
		$output.='<div class="cmc-extra-info">
		<ul>';

		if($blockexplorer!=''){
			$output.='<a class="blockexplorer" target="_blank" href="'.$blockexplorer.'" rel="nofollow" title="Block Explorer"><li>'.__('Block','cmc').'<br/>'.__('Explorer','cmc').'</li></a>';
		};
		if($website!=''){
			$output.='<a class="website" target="_blank" href="'.$website.'" rel="nofollow" title="Official Website"><li>'.__('Official','cmc').'<br/>'.__('Website','cmc').'</li></a>';
		};
		if($whitepaper!=''){
			$output.='<a class="whitepaper" target="_blank" href="'.$whitepaper.'" rel="nofollow" title="Coin Whitepaper"><li>'.__('White','cmc').'<br/>'.__('Paper','cmc').'</li></a>';
		};
		if($youtube!=''){
			$output.='<a class="youtube" target="_blank" href="'.$youtube.'" rel="nofollow" title="Official Youtube"><li>'.$coin_id.'<br/>'.__('YouTube','cmc').'</li></a>';
		};
		if($firstannounced!=''){
			$output.='<a class="firstannounced" title="First Announced / Published"><li>'.$firstannounced.'</li></a>';
		};
		if($github!=''){
			$output.='<a class="github" target="_blank" href="'.$github.'" rel="nofollow" title="Github"><li>'.$coin_id.'<br/>'.__('Github','cmc').'</li></a>';
		};
		if($reddit!=''){
			$output.='<a class="reddit" target="_blank" href="'.$reddit.'" rel="nofollow" title="Reddit"><li>'.$coin_id.'<br/>'.__('Reddit','cmc').'</li></a>';
		};
		if($twitter!=''){
			$output.='<a class="twitter" target="_blank" href="'.$twitter.'" rel="nofollow" title="Twitter"><li>'.$coin_id.'<br/>'.__('Twitter','cmc').'</li></a>';
		};
		if($facebook!=''){
			$output.='<a class="facebook" target="_blank" href="'.$facebook.'" rel="nofollow" title="Facebook"><li>'.$coin_id.'<br/>'.__('Facebook','cmc').'</li></a>';
		};

		$output.='</ul>
		</div>';
		return $output;

			}
	}

	//TITAN FRAMEWORK SINGLE PAGE SETTINGS
	function cmc_single_page_setting($settingKey){
		$cmc_titan =TitanFramework::getInstance( 'cmc_single_settings' );
		$settings = $cmc_titan->getOption($settingKey);
		if($settings){
			return true;
			}else{
				return false;
			}
	}


	/*
		Assets for single page
	*/

	function cmc_coin_details_assets(){
			wp_enqueue_script('amcharts','https://www.amcharts.com/lib/3/amcharts.js');
			wp_enqueue_script('amcharts-serial','https://www.amcharts.com/lib/3/serial.js');
			wp_enqueue_script('amcharts-stock','https://www.amcharts.com/lib/3/amstock.js');
			wp_enqueue_script('amcharts-export','https://www.amcharts.com/lib/3/plugins/export/export.min.js');
			wp_enqueue_style('amcharts-export-css', 'https://www.amcharts.com/lib/3/plugins/export/export.css');
			
			wp_enqueue_script('cmc-single-js');
			
			wp_enqueue_style('cmc-bootstrap4');
			wp_enqueue_style('cmc-custom');
			


		}
		// common assets for all shortcodes
		function cmc_single_assets(){
		
	 
		wp_register_script( 'cmc-historical-datatable-js', 'https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js' );
		wp_register_script('cmc-single-js', CMC_URL . 'assets/js/cmc-single.min.js', array('jquery'), false, true);
		
		wp_register_style('cmc-historical-datatable-css', 'https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css');
		wp_register_style('cmc-bootstrap4', CMC_URL . '/assets/css/bootstrap.min.css');
		wp_register_style('dataTables_bootstrap4', 'https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css');	

		wp_register_style('cmc-custom', CMC_URL . 'assets/css/cmc-custom.min.css');	

		wp_add_inline_script('cmc-historical-datatable-js', 'jQuery(document).ready(function($){
		var showtxt=$("#cmc_historical_tbl").data("show-entries");
        var showprev=$("#cmc_historical_tbl").data("prev");
        var shownext=$("#cmc_historical_tbl").data("next");
		$("#cmc_historical_tbl").DataTable(
	{
	ordering:true,
	searching:false,
	pageLength: 10,
	"oLanguage": {
					
        "oPaginate": {
        "sPrevious": showprev,
		"sNext": shownext,
         },
	    "sLengthMenu": showtxt
    }		
	});
});' 

);

		}



	/*
	 Generating Coin Price
	*/

	function cmc_coin_price($atts, $content = null){
		// shortcode
		$atts = shortcode_atts( array(
			'id'  => '',
		), $atts, 'cmc' );
		$output='';
		$post_id=get_option('cmc-post-id');

		$cmc_titan =TitanFramework::getInstance( 'cmc_single_settings' );

		if(get_query_var('coin_id')) {
			// changed from symbol to name based
	 	 	$coin_id=(string) trim(get_query_var('coin_name'));
	 		$real_cur=get_query_var('currency');
	 		$single_default_currency =$cmc_titan->getOption('default_currency');
			$old_currency=trim($real_cur)!=="" ?trim($real_cur):$single_default_currency;
			$all_coins=cmc_coins_arr($old_currency);

			if(isset($all_coins[$coin_id])){
				$coin_slug=$all_coins[$coin_id]['coin-slug'];
				$coin= $all_coins[$coin_id];
				$output='';
				$coin_released='365day';
				$coinId=$coin_id;
				//$coin_symbol=$coin['symbol'];
				$currency_icon=cmc_old_cur_symbol($old_currency);
				$percent_change_7d=$coin['percent_change_7d'] . '%';
				$percent_change_24h=$coin['percent_change_24h'] . '%';
				$price_index="price_".strtolower($old_currency);

       	if(isset($coin['price'])){
 			 		$coin_price=format_number($coin['price']);
       	}

        $percent_change_1h = $coin['percent_change_1h'] . '%';
        $change_sign ='<span class="icon fa fa-arrow-up"></span>';
        $change_class = __('cmc-up','cmc');
     		$change_sign_minus = "-";

     	 	if ( strpos( $coin['percent_change_1h'], $change_sign_minus ) !==false) {
          $change_sign = '<span class="icon fa fa-arrow-down"></span>';
          $change_class = __('cmc-down','cmc');
        }

 		  	$change_sign_24h ='<span class="icon fa fa-arrow-up"></span>';
        $change_class_24h = __('cmc-up','cmc');
        if ( strpos( $coin['percent_change_24h'], $change_sign_minus ) !==false) {
          $change_sign_24h = '<span class="icon fa fa-arrow-down"></span>';
          $change_class_24h = __('cmc-down','cmc');
        }

        $change_sign_7d ='<span class="icon fa fa-arrow-up"></span>';
        $change_class_7d = __('cmc-up','cmc');
        if ( strpos( $coin['percent_change_7d'], $change_sign_minus ) !==false) {
          $change_sign_7d = '<span class="icon fa fa-arrow-down"></span>';
          $change_class_7d = __('cmc-down','cmc');
        }
        $all_c_p_html='';

        if($this->cmc_single_page_setting("display_changes1h_single")) {
        	$all_c_p_html.='<li class="coin-price-change__item"><div class="cmc_coin_price_change" ><span class="'.$change_class.'">'.$change_sign.$percent_change_1h .'</span></div><span class="cmc_coin_price_label">'.__('1h','cmc').'</span></li>';
      	}

       	if($this->cmc_single_page_setting("display_changes24h_single")){
        	$all_c_p_html.='<li class="coin-price-change__item"><div class="cmc_coin_price_change" ><span class="'.$change_class_24h.'">'.$change_sign_24h.$percent_change_24h.'</span></div><span class="cmc_coin_price_label">'.__('24h','cmc').'</span></li>';
      	}
         
        if($this->cmc_single_page_setting("display_Changes7d_single")){
         	$all_c_p_html.='<li class="coin-price-change__item"><div class="cmc_coin_price_change" ><span class="'.$change_class_7d.'">'.$change_sign_7d.$percent_change_7d.'</span></div><span class="cmc_coin_price_label">'.__('7d','cmc').'</span></li>';
  		 	}
				$this->cmc_coin_details_assets();
		
		    $output.='<h2 class="coin-price"><span class="coin-price__symbol">'. $currency_icon . '</span><span class="coin-price__value js-coin-price__value">' . $coin_price .'</span></h2>';
	      $output.='<ul class="coin-price-change">'.$all_c_p_html.'</ul>';

 			} else {
 		 		return __('Currency Not Found','cmc');
 			}
 		} else {
 			return __('Something wrong with URL','cmc');
 		}
		return $output;
	}

	function cmc_coin_stats($atts, $content = null){
		// shortcode
		$atts = shortcode_atts( array(
			'id'  => '',
		), $atts, 'cmc' );
		$output='';
		$post_id=get_option('cmc-post-id');

		$cmc_titan =TitanFramework::getInstance( 'cmc_single_settings' );

		if(get_query_var('coin_id')) {
			// changed from symbol to name based
	 	 	$coin_id=(string) trim(get_query_var('coin_name'));
	 		$real_cur=get_query_var('currency');
	 		$single_default_currency =$cmc_titan->getOption('default_currency');
			$old_currency=trim($real_cur)!=="" ?trim($real_cur):$single_default_currency;
			$all_coins=cmc_coins_arr($old_currency);

			if(isset($all_coins[$coin_id])){
				$name=$all_coins[$coin_id]['name'];
				$coin_slug=$all_coins[$coin_id]['coin-slug'];
				$coin= $all_coins[$coin_id];
				$output='';
				$coin_released='365day';
				$coinId=$coin_id;

				$available_supply=$coin["available_supply"];
				$m_index="market_cap_".strtolower($old_currency);
	   	 	$v_index="24h_volume_".strtolower($old_currency);
      
       	$volume='';
        if(isset($coin['24h_volume'])){
 			 		$volume = $coin['24h_volume'];
 			 		$volume=cmc_format_coin_values($volume);
	   		} else {
	   		 	$volume=__('N/A','cmc');
	   		}

        $market_cap='';
		 		if(isset($coin['marketcap'])) {
 			 		$market_cap = $coin['marketcap'];
 			 		$market_cap=$currency_icon.cmc_format_coin_values($market_cap);
	      } else {
	        $market_cap=__('N/A','cmc');
	      }

	      $available_supply=cmc_format_coin_values($available_supply);

				$output.='<ul class="coin-stats"><li class="coin-stats__item coin-stats__item--rank"><span class="icon fas fa-star"></span><span class="coin-stats__item-value">'.$coin['rank'].'</span><span class="coin-stats__item-label">Market cap rank</span></li>';

	      $output.='<li class="coin-stats__item coin-stats__item--cap"><span class="icon fas fa-rocket"></span><span class="coin-stats__item-value">'.$market_cap.'</span><span class="coin-stats__item-label">Market cap</span></li>';

	      $output.='<li class="coin-stats__item coin-stats__item--volume"><span class="icon fas fa-chart-line"></span><span class="coin-stats__item-value">'.$currency_icon.$volume.'</span><span class="coin-stats__item-label">24h volume</span></li>';
	 
			 	$output.='<li class="coin-stats__item coin-stats__item--supply"><span class="icon fas fa-trophy"></span><span class="coin-stats__item-value">'.$available_supply.$coin_symbol.'</span><span class="coin-stats__item-label">Circulating supply</span></li></ul>';

 			} else {
 		 		return __('Currency Not Found','cmc');
 			}
 		} else {
 			return __('Something wrong with URL','cmc');
 		}
		return $output;
	}

	}
