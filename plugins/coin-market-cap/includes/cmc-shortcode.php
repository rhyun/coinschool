<?php
class CMC_Shortcode{


	function __construct(){
		
		add_action('wp_enqueue_scripts',array($this,'cmc_register_scripts'));
		add_shortcode('global-coin-market-cap',array($this,'cmc_global_data'));
		add_shortcode( 'coin-market-cap',array($this,'cmc_shortcode'));
		
		require_once(CMC_PATH.'/includes/pagination.class.php');
		add_action( 'wp_ajax_cmc_small_charts',array($this,'cmc_small_chart_data') );
		add_action( 'wp_ajax_nopriv_cmc_small_charts',array($this,'cmc_small_chart_data') );
		delete_transient( 'cmc-coins-new-links');
	}	

	function cmc_small_chart_data() {

	if(isset($_POST['symbol'])){
			$symbol=$_POST['symbol'];
			$history=cmc_histo_7days($symbol);
			
			if(!empty($history)&& count($history)>0){
				echo json_encode(array("type"=>"success","data"=>$history));
			}else{
				echo json_encode(array("type"=>"error","data"=>$history));
			}
	}
		wp_die();
	}

	function cmc_get_chart_cache_data($coin_symbol){
		$historical_data= get_transient('7d-historical-'.$coin_symbol);
		
		if( is_array($historical_data) && count($historical_data)>0) {
			return $historical_data;
		}else{
			return false;
		}

	}

	function cmc_global_data($atts, $content = null){

	 	$atts = shortcode_atts( array(
		'id'  => '',
		'currency'=>'USD',
		'formatted'=>true
		), $atts);

		wp_enqueue_style('cmc-font-awesome-g','https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

		$cmc_g_styles='/* Global Market Cap Data */
		.cmc_global_data {
			display:inline-block;
			margin-bottom:5px;
			width:100%;
		}
		.cmc_global_data ul {
		    list-style: none;
		    margin: 0;
		    padding: 0;
		    display: inline-block;
		    width: 100%;
		}
		.cmc_global_data ul li {
		    display: inline-block;
		    margin-right: 20px;
			font-size:14px;
			margin-bottom: 5px;
		}
		.cmc_global_data ul li .global_d_lbl {
			font-weight: bold;
		    background: #f9f9f9;
		    padding: 4px;
		    color: #3c3c3c;
		    border: 1px solid #e7e7e7;
		    margin-right: 5px;
		}
		.cmc_global_data ul li .global_data {
		    font-size: 13px;
			white-space:nowrap;
			display:inline-block;
		}
		/* Global Market Cap Data END */ ';
	
		wp_add_inline_style('cmc-font-awesome-g',$cmc_g_styles);


		$output='';
		$old_currency=$atts['currency']?$atts['currency']:'USD';
		
		$currency_symbol=cmc_old_cur_symbol($old_currency);
		$market_cap_index="total_market_cap_".strtolower($old_currency);
		$volume_index="total_24h_volume_".strtolower($old_currency);
		$global_data=(array)cmc_get_global_data($old_currency);
		
		if(is_array($global_data)){
			$bitcoin_percentage_of_market_cap=$global_data['bitcoin_percentage_of_market_cap'];

			$output.='<div class="cmc_global_data"><ul>';
	
			
			if(isset($global_data[$market_cap_index])){
			
			if($atts['formatted']=="true"){
			$mci_html=$currency_symbol .cmc_format_coin_values($global_data[$market_cap_index]);
			 }else{
			 $mci_html=$currency_symbol .format_number($global_data[$market_cap_index]);
			 }
			$output.='<li><span class="global_d_lbl">'.__('Market Cap:','cmc').'</span><span class="global_data"> '.$mci_html.'</span></li>';
			}

			if(isset($global_data[$volume_index])){
				if($atts['formatted']=="true"){
					$vci_html=$currency_symbol .cmc_format_coin_values($global_data[$volume_index]);
				 }else{
				 	$vci_html=$currency_symbol .format_number($global_data[$volume_index]);
				 }	
			$output.='<li><span class="global_d_lbl">'.__('24h Vol:','cmc').'</span><span class="global_data"> '.$vci_html.'</span></li>';
			}
			$output.='<li><span class="global_d_lbl">'.__('BTC Dominance: ','cmc').'</span><span class="global_data">'.$bitcoin_percentage_of_market_cap.'%</span></li>';

			$output.='</ul></div>';
			}
		return $output;
	}

	
	
	// shortcode
	function cmc_shortcode( $atts, $content = null ) {
	$atts = shortcode_atts( array(
		'id'  => '',
		'class' => '',
		'info' => true,
		'paging' => false,
		'scrollx' => true,
		'ordering' => true,
		'searching' => false,
	), $atts, 'cmc' );
	
	wp_enqueue_style( 'cmc-fixed-column');	
	wp_enqueue_style( 'cmc-fixedcolumn-bootstrap');
	wp_enqueue_style( 'cmc-bootstrap');
	wp_enqueue_style( 'dataTables_bootstrap4');
	
	wp_enqueue_style( 'cmc-custom');
	wp_enqueue_script( 'bootstrapcdn');
	wp_enqueue_script( 'cmc-jquery-number');
	wp_enqueue_script( 'cmc-datatables.net');
	wp_enqueue_script('cmc-typeahead');
	wp_enqueue_script('cmc-handlebars');
	wp_enqueue_script('cmc-sparkline');
	wp_enqueue_script( 'cmc-fixed-column-js' );
	wp_enqueue_script( 'cmc-js' );
	
	wp_localize_script( 'cmc-js', 'ajax_object',
             array('ajax_url' => admin_url( 'admin-ajax.php' )) );
	wp_enqueue_script('ccc-socket','https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.3/socket.io.js');
	wp_enqueue_script('ccc_stream',CMC_URL. 'assets/js/cmc-stream.min.js', array(),null,true);	
	$cmc_link_array=array();
	 $slug='';
	 $post_id=$atts['id'];
	 $post = get_post($post_id); 
	$slug = $post->post_name;
	// Initialize Titan
	$cmc_titan = TitanFramework::getInstance( 'cmc_single_settings' );
 
	$show_coins =$cmc_titan->getOption('show_currencies',$post_id);

	$real_currency =$cmc_titan->getOption('old_currency',$post_id);
	$old_currency=$real_currency?$real_currency:"USD";
	// for currency dropdown
	$currencies_price_list=cmc_usd_conversions('all');
	$selected_currency_rate=cmc_usd_conversions($old_currency);
	$currency_symbol=cmc_old_cur_symbol($old_currency);
	$single_default_currency =$cmc_titan->getOption('default_currency',$post_id);

	$fetch_coins=1495;
	$pagination=$show_coins?$show_coins:50;

	$display_supply =$cmc_titan->getOption('display_supply',$post_id);
	$display_Volume_24h =$cmc_titan->getOption('display_Volume_24h',$post_id);
	$display_market_cap =$cmc_titan->getOption('display_market_cap',$post_id);
$display_price =$cmc_titan->getOption('display_price',$post_id);
$display_Changes7d =$cmc_titan->getOption('display_Changes7d',$post_id);

	$display_changes24h =$cmc_titan->getOption('display_changes24h',$post_id);
	$display_changes1h =$cmc_titan->getOption('display_changes1h',$post_id);

	$display_chart=$cmc_titan->getOption('coin_price_chart',$post_id);
	$cmc_small_charts=$cmc_titan->getOption('cmc_chart_type',$post_id);
	$live_updates_cls='';
	$live_updates=$cmc_titan->getOption('live_updates',$post_id);
	if($live_updates){
		$live_updates_cls='cmc_live_updates';
	}else{
		$live_updates_cls='';
	}
	$enable_formatting=$cmc_titan->getOption('enable_formatting',$post_id);

	
	$single_page_type=$cmc_titan->getOption('single_page_type',$post_id);
	
	$single_page_slug=cmc_get_page_slug();

	$cmc_data_attributes = '';
	$att['info'] = $this->cmc_data_attribute( $atts['info'] );
	$att['paging'] = $this->cmc_data_attribute( $atts['paging'] );
	$att['scrollx'] = $this->cmc_data_attribute( $atts['scrollx'] );
	$att['ordering'] = $this->cmc_data_attribute( $atts['ordering'] );
	$att['searching'] = $this->cmc_data_attribute( $atts['searching'] );

		foreach ( $att as $att_key => $att_value ) {
			$att_key = esc_attr( $att_key );
			$att_value = esc_attr( $att_value );
			$cmc_data_attributes .= " data-{$att_key}=\"{$att_value}\" ";
		}

		$cmc_data_attributes .='data-pageLength="'.$pagination.'"';
		$cmc_coins_page=(get_query_var( 'page' ) ? get_query_var( 'page' ) : 1);
	
		$all_coin_data=cmc_coins_data($fetch_coins,$old_currency);
		$bitcoin_price= isset($all_coin_data[0]->price_usd)? $all_coin_data[0]->price_usd:1;
	
		if(is_array($all_coin_data)&& count($all_coin_data)>0){
		 $pagination = new pagination($all_coin_data,$cmc_coins_page ,$pagination);
		 $show_coins = $pagination->getResults();
		}else{
			_e('API Request timeout','cmc');
		}


		 $c_json= currencies_json();

		ob_start();
		$search=__('search','cmc');
		?>

		<!---------- CMC Version:- <?php echo CMC;?> By Cool Plugins Team-------------->
		<div id="cryptocurency-market-cap-wrapper">
		
		<script id="cmc_curr_list" type="application/json">
		 <?php echo $c_json;?>
		</script>	
	
		<div class="cmc_price_conversion">
			<select id="cmc_usd_conversion_box" class="cmc_conversions">
				<?php 
				$currencies_price_list['BTC']= $bitcoin_price;
				
				foreach($currencies_price_list  as $name=>$price){
					$csymbol=cmc_old_cur_symbol($name);
						if($name==$old_currency){
			
					echo '<option selected="selected" data-currency-symbol="' . $csymbol . '" data-currency-rate="' . $price . '"  value="'.$name.'" >'.$name.'</option>';
						}else{
					echo '<option data-currency-symbol="'.$csymbol.'" data-currency-rate="'.$price.'"  value="'.$name.'">'.$name.'</option>';
						}
					}
				unset($currencies_price_list['BTC']);	
					
					?>
			</select>
		</div>

		<div  data-currency="<?php echo $old_currency;?>" class="cmc_search" id="custom-templates">
  		<input class="typeahead" type="text" placeholder="<?php echo $search ?>">
		</div>
		<?php 
if(is_array($all_coin_data ) && count($all_coin_data)>0 ) {
		
	foreach( $all_coin_data as $coin ) {
				$coin=(array)$coin;
				$coin_id=$coin['id'];
				$coin_symbol=$coin['symbol'];
				$c_slug=strtolower(str_replace(" ","-",$coin['name']));	
		     	$name=$coin['name']." ".$coin_symbol."";
				$coin_logo=coin_logo_url($coin_id,$size=32);

		     	if($old_currency==$single_default_currency){
		       	 $coin_url=$single_page_slug.'/'.$coin_symbol.'/'.$c_slug;	
		   		}else{
		   		 $coin_url=$single_page_slug.'/'.$coin_symbol.'/'.$c_slug.'/'.$old_currency;
		   		}
		   	$cmc_link_array[]=array("link"=>$coin_url,"name"=>$name,"symbol"=>$coin_symbol,"logo"=>$coin_logo);
   		}
   		 $search_links=json_encode($cmc_link_array);
}   		
		?>
		<div class="cmc_pagi">
		<?php
		if(is_array($all_coin_data)&& count($all_coin_data)>0){
		 $pagination->setShowFirstAndLast(true);
       		 echo $pageNumbers = '<div class="cmc_p_wrp"><ul  class="cmc_pagination">'.$pagination->getLinks($_GET).'</li></div>';
       	 }
            ?>
	</div>
<div class="top-scroll-wrapper">
    <div class="top-scroll">
    </div>
	</div>
<table id="coin-market-cap-widget" data-currency-symbol="<?php echo $currency_symbol; ?>" data-currency-rate="<?php echo $selected_currency_rate;?>" data-old-currency="<?php echo $old_currency;?>" class="<?php echo $live_updates_cls;?> cmc-datatable table table-striped table-bordered" width="100%"   <?php echo $cmc_data_attributes; ?>>
				<thead>
				<tr>
				<th class="desktop"><?php _e( '#', 'cmc' ); ?></th>
					<th class="all"><?php _e( 'Name', 'cmc' ); ?></th>
				
				<?php if($display_price==true){ ?>
				<th class="all"><?php _e( 'Price', 'cmc' ); ?></th>
				<?php } ?>

				<?php if($display_changes1h==true){ ?>
	
				<th><?php _e( 'Changes ', 'cmc' ).' <span class="badge  badge-default">'. _e('1H ', 'cmc' ).'</span>'; ?></th>	<?php } ?>

				<?php if($display_changes24h==true){ ?>
				<th><?php _e( 'Changes ', 'cmc' ).'<span class="badge  badge-default">'. _e('24H', 'cmc' ).'</span>'; ?></th>	<?php } ?>

				<?php if($display_Changes7d==true){ ?>
				<th><?php _e( 'Changes ', 'cmc' ).'<span class="badge badge-default">'. _e('7D', 'cmc' ).'</span>'; ?></th>	<?php } ?>
				
				<?php if($display_market_cap==true){ ?>
				<th><?php _e( 'Market Cap', 'cmc' ); ?></th>	<?php } ?>
				
                <?php if($display_Volume_24h==true){ ?>
				<th><?php _e( 'Volume ', 'cmc' ).'<span class="badge  badge-default">'. _e('24H', 'cmc' ).'</span>'; ?></th>	<?php } ?>
				
				<?php if($display_supply==true){ ?>
				<th><?php _e( 'Available Supply', 'cmc' ); ?></th>	<?php } ?>
			
				<?php if($display_chart==true){ ?>
				<th  data-orderable="false"><?php _e( 'Price Graph ', 'cmc' ).'<span class="badge badge-default">(7D)</span>'; ?></th>
				<?php } ?>
				
				</tr>

				</thead>
				<tbody>
		<?php 	
	if(is_array($show_coins ) && count($show_coins)>0) {
		foreach( $show_coins as $coin ) {
				$coin=(array)$coin;
		$coin_id=$coin['id'];
		$coin_symbol=$coin['symbol'];
		$c_slug=strtolower(str_replace(" ","-",$coin['name']));	
     	
     	if($old_currency==$single_default_currency){
       	 $coin_url=esc_url( home_url($single_page_slug.'/'.$coin_symbol.'/'.$c_slug,'/') );	
   		}else{
   		 
   		  $coin_url=esc_url( home_url($single_page_slug.'/'.$coin_symbol.'/'.$c_slug.'/'.$old_currency,'/') );
   		}
			

			echo $this->gernate_coin_html($coin,$old_currency,$post_id
				,$display_supply,
				$display_Volume_24h,
				$display_market_cap,
				$display_price,
				$display_Changes7d,
				$display_changes24h,
				$display_changes1h,
				$display_chart,
				$single_page_type,
				$currencies_price_list,
				$coin_url,
				$cmc_small_charts,
				$enable_formatting
			);

			
	
			  }
			}
		
		 ?></tbody>
				<tfoot>
				
				</tfoot>
			</table>
		</div>
		<script id="cmc_search_html" type="application/json">
		<?php echo $search_links; ?>
		</script>
		<script id="search_temp" type="text/x-handlebars-template">
	 <div class="cmc-search-sugestions"><a href="<?php echo esc_url( home_url( '/' ) ); ?>{{link}}">
	 	 {{#if logo.local}}
	 	<img src="<?php echo CMC_URL; ?>assets/coins-logos/{{logo.logo}}">
	 	 {{else}}
	 	 <img src="{{logo.logo}}">
	 	{{/if}} 
	 {{name}}</a></div>
		</script>

		<?php
		if(is_array($all_coin_data)&& count($all_coin_data)>0){
		 echo $pageNumbers;
		}
        ?>
		<?php return ob_get_clean();
		
	}
	
	function gernate_coin_html($coin,$old_currency,$post_id,
				$display_supply,
				$display_Volume_24h,
				$display_market_cap,
				$display_price,
				$display_Changes7d,
				$display_changes24h,
				$display_changes1h,
				$display_chart,
				
				$single_page_type,
				$currencies_price_list,
				$coin_url,
				$cmc_small_charts,
				$enable_formatting
	){
		$coin_data='';
		$coin_name = $coin['name'];
	
		$c_id = $coin['id'];
		$coin_symbol =$coin['symbol'];
		
		$coin_icon= coin_logo_html($c_id);
		$supply=$coin['available_supply'];
		$percent_change_7d=$coin['percent_change_7d'] . '%';
		$percent_change_24h=$coin['percent_change_24h'] . '%';
		 $price_index="price_".strtolower($old_currency);
         $m_index="market_cap_".strtolower($old_currency);
       	 $v_index="24h_volume_".strtolower($old_currency);

		
	if(isset($coin[$price_index])){
	 			 $coin_price = $coin[$price_index];
	        }else{
	        	 $coin_price = $coin['price_usd'];
	        }

	     $coin_price=format_number($coin_price);
		   if(isset($coin[$v_index])){
	 			 $volume = $coin[$v_index];
	        }else{
	        	 $volume = $coin['price_usd'];
	        }
		
			 if($enable_formatting){
        	$formatted_volume=cmc_format_coin_values($volume);
        	}else{
        	$formatted_volume=format_number($volume);	
        	}	
			 if(isset($coin[$m_index])){
	 			 $market_cap = $coin[$m_index];
		        }else{
		        	 $market_cap = $coin['market_cap_usd'];

		        }
		  $volume=format_number($volume);
		
	 	 
	 	 if($enable_formatting){
        	 $formatted_supply=cmc_format_coin_values($supply);	
        	}else{
        	 $formatted_supply=format_number($supply);
        	}
          $supply=format_number($supply);	
		 
		 if($market_cap==""){
			$formatted_market_cap="?";	 
		 }else{

		 	 if($enable_formatting){	
				$formatted_market_cap=cmc_format_coin_values($market_cap);	
			 }else{
			 	$formatted_market_cap=format_number($market_cap);
			 } 
		 }
		  $market_cap=format_number($market_cap);  
		  $currency_icon=cmc_old_cur_symbol($old_currency);
	       if($coin_symbol=="MIOTA"){
	       	 	$coinId='IOT';
	       } else{
	       	$coinId=$coin_symbol;
	       }
	      $json_data=array();  
	    $usd_price= $coin['price_usd'];
	    $usd_cap=$coin['market_cap_usd'];
	 	$usd_vol=$coin['24h_volume_usd'];
		$btc_price= $coin['price_btc'];
	
	    $json_data['btc_price']=format_number($btc_price);
	    $json_data['btc_cap']=cmc_format_coin_values($usd_cap/ $btc_price);
		$json_data['btc_vol']=cmc_format_coin_values($usd_vol/ $btc_price);

		$json_data['usd_price'] = format_number($usd_price);
		$json_data['usd_cap'] = cmc_format_coin_values($usd_cap);
		$json_data['usd_vol'] = cmc_format_coin_values($usd_vol);
		if(is_array($currencies_price_list )){
			foreach ($currencies_price_list as $c_sym=>$currecy) {
			$p_index=strtolower($c_sym).'_price';
			$c_index=strtolower($c_sym).'_cap';
			$v_index=strtolower($c_sym).'_vol';
			$json_data[$p_index]=format_number( $usd_price*$currecy);
			$json_data[$c_index]=cmc_format_coin_values( $usd_cap*$currecy);
			$json_data[$v_index]=cmc_format_coin_values($usd_vol*$currecy);
			}
	  }

		$coin_json=htmlspecialchars(json_encode($json_data),ENT_QUOTES, 'UTF-8');

        $percent_change_1h = $coin['percent_change_1h'] . '%';
        $change_sign ='<i class="fa fa-arrow-up" aria-hidden="true"></i>';
        $change_class = __('up','cmc');
     	$change_sign_minus = "-";
     	
     	
		if($single_page_type==true){$coin_link_open ='<a target="_blank" title="'.$coin_name.'"  href="'.$coin_url.'">';
		$coin_link_close='</a>';
		}
		else{$coin_link_open ='<a title="'.$coin_name.'"  href="'.$coin_url.'">';
		$coin_link_close='</a>';
		}

     	   $small_chart_color='#006400';
     	   $small_chart_bgcolor='#90EE90';
 		 $change_sign_24h ='<i class="fa fa-arrow-up" aria-hidden="true"></i>';
          $change_class_24h =  'cmc-up';
         if ( strpos( $coin['percent_change_24h'], $change_sign_minus ) !==false) {
            $change_sign_24h = '<i class="fa fa-arrow-down" aria-hidden="true"></i>';
            $change_class_24h = 'cmc-down';
            $small_chart_color='#ff0000	';
           $small_chart_bgcolor='#ff9999
';
        }

		 $changes_coin_html='';
       	 $changes_coin_html.='<span class="ccpw-changes '.$change_class_24h.'">';
			$changes_coin_html.=$change_sign_24h.$percent_change_24h ;
			$changes_coin_html.= '</span>';	

			 $changes_24h_coin_html='';
       	 $changes_24h_coin_html.='<span class="cmc_live_ch cmc-changes '.$change_class_24h.'">';
		 $changes_24h_coin_html.=$change_sign_24h.$percent_change_24h ;
		 $changes_24h_coin_html.= '</span>';	
		$change_sign_1h ='<i class="fa fa-arrow-up" aria-hidden="true"></i>';

        $change_class_1h =  'cmc-up';
         if ( strpos( $coin['percent_change_1h'], $change_sign_minus ) !==false) {
            $change_sign_1h = '<i class="fa fa-arrow-down" aria-hidden="true"></i>';
            $change_class_1h = 'cmc-down';
        }
		
		$changes_1h_coin_html='';
       	$changes_1h_coin_html.='<span class="cmc-changes '.$change_class_1h.'">';
		$changes_1h_coin_html.=$change_sign_1h.$percent_change_1h ;
		$changes_1h_coin_html.= '</span>';		
		$change_sign_7d ='<i class="fa fa-arrow-up" aria-hidden="true"></i>';
        $change_class_7d =  'cmc-up';
        if ( strpos( $coin['percent_change_7d'], $change_sign_minus ) !==false) {
            $change_sign_7d = '<i class="fa fa-arrow-down" aria-hidden="true"></i>';
            $change_class_7d = 'cmc-down';
        }
			
		$changes_7d_coin_html='';
       	$changes_7d_coin_html.='<span class="cmc-changes '.$change_class_7d.'">';
		$changes_7d_coin_html.=$change_sign_7d.$percent_change_7d ;
		$changes_7d_coin_html.= '</span>';	
	  $logo='<span class="cmc_coin_logo">'.$coin_icon.'</span>';
	  $coin_symbol_html='<span class="cmc_coin_symbol">('.$coin_symbol.')</span><br/>';
	  $coin_name_html='<span class="cmc_coin_name cmc-desktop">'.$coin_name.'</span>';	
	  $coin_data.='<tr data-coin-symbol="'.$coin_symbol.'">';
	  $coin_data .='<td>'.$coin['rank'].'</td>';	
	 $coin_data .='<td data-order="'.$coin_name.'"><span class="cmc-name">';
	 $coin_data .=$coin_link_open.$logo.$coin_symbol_html.$coin_name_html.$coin_link_close;
	 $coin_data .='</span></td>';
	
  if($display_price==true){
	$coin_data .='<td data-coin-json="'.$coin_json.'" data-coin-price="'.$coin['price_usd'].'" data-coin-symbol="'.$coin_symbol.'" class="cmc_price_section" data-order="'.$coin_price.'"><span class="cmc-price">' . $currency_icon. $coin_price . '</span></td>';
		}
if($display_changes1h==true){
	$coin_data .='<td>'.$changes_1h_coin_html.'</td>';
	}	
	if($display_changes24h==true){
	$coin_data .='<td>'.$changes_24h_coin_html.'</td>';	
	}	
	if($display_Changes7d==true){
	$coin_data .='<td>'.$changes_7d_coin_html.'</td>';
	}	

    
	if($display_market_cap==true){
	$coin_data .='<td data-order="'.$market_cap.'" ><span class="cmc_live_cap">'.$currency_icon.$formatted_market_cap.'</span></td>';
		}	
	
	if($display_Volume_24h==true){
	$coin_data .='<td data-order="'.$volume.'" ><span class="cmc_live_vol">'.$currency_icon.$formatted_volume.'</span></td>';
	}
	
	
	if($display_supply==true){
	$coin_data .='<td data-order="'.$supply.'" ><span class="cmc_live_supply">'.$formatted_supply.' '.$coin_symbol.'</span></td>';
	}

	 if($display_chart==true){
	$coin_data .='<td><div class="small-chart-area">';
	
	if($cmc_small_charts=="image-charts"){
		$coin_data .=$coin_link_open.get_coin_chart($coin_symbol).$coin_link_close;
	}
	else{
		if($coin_symbol=="MIOTA"){
			$coin_symbol="IOT";
		}
		$chart_cache=$this->cmc_get_chart_cache_data($coin_symbol);
		$cachedata='';
		$content='';
		if($chart_cache===false){
			$cachedata='false';
		}else{
			$cachedata='true';
			$content=json_encode($chart_cache);
		}
		$no_data_lbl=__('No Graphical Data','cmc');
		
		$coin_data .='<div data-bg-color="'.$small_chart_bgcolor.'" data-color="'.$small_chart_color.'" data-msz="'.$no_data_lbl.'" data-content="'.$content.'" data-cache="'.$cachedata.'" data-coin-symbol="'.$coin_symbol.'" class="cmc-sparkline-charts"></div>';
		$coin_data .='<img class="cmc-small-preloader" src="'.CMC_URL.'images/chart-loading.svg">';
		}

		$coin_data.='</div></td>'; 
	}

	  $coin_data.='</tr>';
	return $coin_data;
	
	}	

	function cmc_get_settings($post_id,$index){
		if($post_id && $index){
			// Initialize Titan
	$cmc_titan = TitanFramework::getInstance( 'cmc_single_settings' );

	$val=$cmc_titan->getOption($index,$post_id);
		if($val){
			return true;
			}else{
				return false;
			}
		}
	}
	
	 function cmc_data_attribute( $attr ) {
			 $coin_html='';
			if ( 'true' === $attr || true === $attr || 1 == $attr ) {
			    return 'true';
			    }
				else 
				{
				return 'false';
			    }
		}

	/**
	 * Register scripts and styles
	 */
	 //add cdn and change js file functions
	public function cmc_register_scripts() {
		if ( ! is_admin() ) {
			
			if( ! wp_script_is( 'jquery', 'done' ) ){
                wp_enqueue_script( 'jquery' );
               }
			wp_enqueue_style('cmc-font-awesome','https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

			wp_register_script( 'cmc-datatables.net', 'https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js' );
			wp_register_script('bootstrapcdn','https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js');

          
            wp_register_style( 'dataTables_bootstrap4', 'https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css');	

		    wp_register_style( 'cmc-fixed-column', 'https://cdn.datatables.net/fixedcolumns/3.2.4/css/fixedColumns.dataTables.min.css' );	
			
			wp_register_style( 'cmc-custom',CMC_URL.'assets/css/cmc-custom.min.css');
			
			wp_register_style( 'cmc-bootstrap',CMC_URL.'assets/css/bootstrap.min.css' );	
		    wp_register_style( 'cmc-fixedcolumn-bootstrap', 'https://cdn.datatables.net/fixedcolumns/3.2.0/css/fixedColumns.bootstrap.min.css' );
			wp_register_script( 'cmc-jquery-number', 'https://cdnjs.cloudflare.com/ajax/libs/df-number-format/2.1.6/jquery.number.min.js' );
			
			wp_register_script( 'cmc-js', CMC_URL . 'assets/js/cmc-main.min.js', array( 'jquery','cmc-datatables.net'), false, true );
	
            wp_register_script( 'cmc-fixed-column-js', 'https://cdn.datatables.net/fixedcolumns/3.2.4/js/dataTables.fixedColumns.min.js', array( 'jquery'), false, true );

            wp_register_script('cmc-typeahead', CMC_URL . 'assets/js/typeahead.bundle.min.js', array( 'jquery'), false, true );
             wp_register_script('cmc-handlebars', CMC_URL . 'assets/js/handlebars-v4.0.11.js', array( 'jquery'), false, true );

              wp_register_script('cmc-sparkline','https://omnipotent.net/jquery.sparkline/2.1.2/jquery.sparkline.min.js.gz');
  
			wp_localize_script( 'cmc-js', 'ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
			 $dynamic_css=cmc_dynamic_style();	
         	 wp_add_inline_style( 'cmc-custom', $dynamic_css );
         	  
		}
	}

			
	}