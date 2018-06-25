<?php
class CMC_Top{

	function __construct(){
		require_once(CMC_PATH.'/includes/cmc-functions.php');
	 	require_once(CMC_PATH.'/includes/pagination.class.php');
		add_action( 'wp_enqueue_scripts','cmc_register_scripts');
		add_shortcode('cmc-top',array($this,'cmc_top_gainer_losers_shortcode'));
	}


	function cmc_top_gainer_losers_shortcode($atts, $content = null)
	{
		
		$atts = shortcode_atts( array(
		'id'  => '',
		'type'=>'gainers',
		'layout'=>'basic',
		'currency'=>'USD',
		'show-coins'=>30,
		), $atts);
$topclass='';
 $topclass='';
 $output='';
        if($atts['layout']=="basic"){
		$topclass='basic';
		}
		else{
        $topclass='advance';
		}
			wp_enqueue_style( 'cmc-bootstrap');
			wp_enqueue_style( 'dataTables_bootstrap4');
			wp_enqueue_style( 'cmc-fixedcolumn-bootstrap');
			wp_enqueue_style( 'cmc-custom');
			wp_enqueue_script( 'bootstrapcdn');
			wp_enqueue_script( 'cmc-jquery-number');
			wp_enqueue_script( 'cmc-datatables.net');
			wp_enqueue_script( 'cmc-fixed-column-js');
			
			if( $atts['layout']=="basic"){
				
			wp_add_inline_script( 'cmc-datatables.net',"jQuery(document).ready( function($) {
				  $('.cmc-top-".$topclass."').dataTable({
				  	searching:false,
				  	paging:false,
				  	order:[],
				  });
				} );" );	
			}
			
			else{
			wp_add_inline_script( 'cmc-datatables.net',"jQuery(document).ready( function($) {
				  $('.cmc-top-".$topclass."').dataTable({
				  	searching:false,
				  	paging:false,
					
				  	order:[],
					scrollX:true,
					fixedColumns:{
						leftColumns:2
					},
					
				  });
				} );" );	
				
			}
	
		$single_default_currency =$atts['currency'];
		$old_currency=isset($single_default_currency)?$single_default_currency:"USD";
		$load_only=!empty($atts['show-coins'])?$atts['show-coins']:30;
   		$type=$atts['type'];
 
   		$fetch_coins=1494;
	$all_coin_data=cmc_coins_data($fetch_coins,$old_currency);
		
		if(is_array($all_coin_data)&& count($all_coin_data)>0){
		$coins_data_arr=objectToArray($all_coin_data);
		}else{
		 $output.=__('API Request timeout','cmc');
		}
		$min=500000;
		$coins_data_arr = array_filter(
		    $coins_data_arr,
		    function ($value) use($min) {
		      return ($value['24h_volume_usd'] >= $min);
		    }
		 );

			if($type=="gainers"){
				uasort($coins_data_arr,'sort_by_gainers');
			}else{
				
				uasort($coins_data_arr,'sort_by_losers');

			}
			
	 $pagination = new pagination($coins_data_arr,1,$load_only);
		  $show_coins = $pagination->getResults();
		$output .='<div id="cryptocurency-market-cap-wrapper">
		<table id="cmc-top-gainer-lossers" class="table cmc-top-'.$topclass.'  cmc-datatable table-striped table-bordered" width="100%">
		<thead><tr>';
		if($atts['layout']=="advance"){
		$output .='<th data-orderable="false" class="all" >'.__( '#', 'cmc' ).'</th>';
		}
		$output .='<th data-orderable="false" class="all">'.__( 'Name', 'cmc' ).'</th>';
		$output .='<th class="all">'.__( 'Price', 'cmc' ).'</th>';
		$output .='<th>'.__( 'Changes ', 'cmc' ).'<span class="badge  badge-default">'. __('24H', 'cmc' ).'</span></th>';

		if($atts['layout']=="advance"){

		$output .='<th>'.__( 'Market Cap', 'cmc' ).'</th>';
		$output .='<th>'.__( 'Volume ', 'cmc' ).'<span class="badge  badge-default">'.__('24H', 'cmc' ).'</span></th>';
		$output .='<th  data-orderable="false">'.__( 'Price Graph ', 'cmc' ).'<span class="badge badge-default">(24H)</span></th>';
		}
		$output .='</tr></thead><tbody>';
		$single_page_slug=cmc_get_page_slug();
		$i=0;
		 if(is_array($show_coins) ) {
		 	
			foreach( $show_coins as $coin ) {

				$coin_name = $coin['name'];
				$coin_data='';
				$c_id = $coin['id'];
				$coin_symbol =$coin['symbol'];
				$i++;
				$coin_icon= coin_logo_html($c_id);
				$supply=$coin['available_supply'];
				$percent_change_7d=$coin['percent_change_7d'] . '%';
				$percent_change_24h=$coin['percent_change_24h'] . '%';
				 $price_index="price_".strtolower($old_currency);
		         $m_index="market_cap_".strtolower($old_currency);
		       	 $v_index="24h_volume_".strtolower($old_currency);
				 $change_sign_minus='-';
				 $changes_24h_coin_html ='';

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

					$formatted_volume=cmc_format_coin_values($volume);
					 $volume=format_number($volume);

					 if(isset($coin[$m_index])){
			 			 $market_cap = $coin[$m_index];
				        }else{
				        	 $market_cap = $coin['market_cap_usd'];
							}
					$formatted_market_cap=cmc_format_coin_values($market_cap);
				  $market_cap=format_number($market_cap);
				  $currency_icon=cmc_old_cur_symbol($old_currency);
			       if($coin_symbol=="MIOTA"){
			       	 	$coinId='IOT';
			       } else{
			       	$coinId=$coin_symbol;
			       }

	  if($old_currency==$single_default_currency){
       	 $coin_url=esc_url( home_url($single_page_slug.'/'.$coin_symbol.'/'.$c_id,'/') );
   		}else{

   		  $coin_url=esc_url( home_url($single_page_slug.'/'.$coin_symbol.'/'.$c_id.'/'.$old_currency,'/') );
   		}
		$coin_link_open ='<a href="'.$coin_url.'">';
		$coin_link_close='</a>';
	
 		 $change_sign_24h ='<i class="fa fa-arrow-up" aria-hidden="true"></i>';
          $change_class_24h ='cmc-up';
         if ( strpos( $coin['percent_change_24h'], $change_sign_minus ) !==false) {
            $change_sign_24h = '<i class="fa fa-arrow-down" aria-hidden="true"></i>';
            $change_class_24h = 'cmc-down';
        }

			$changes_coin_html='';
			$changes_coin_html.='<span class="cmc-changes '.$change_class_24h.'">';
			$changes_coin_html.=$change_sign_24h.$percent_change_24h ;
			$changes_coin_html.= '</span>';
			$logo='<span class="cmc_coin_logo">'.$coin_icon.'</span>';
			$coin_symbol_html='<span class="cmc_coin_symbol">('.$coin_symbol.')</span><br/>';
			$coin_name_html='<span class="cmc_coin_name cmc-desktop">'.$coin_name.'</span>';
		$coin_data.='<tr data-coin-price="'.$coin['price_usd'].'" data-coin-symbol="'.$coin_symbol.'">';

		if($atts['layout']=="advance"){
			$coin_data .='<td><span class="cmc_c_order">'.$i.'</span></td>';
		}
			$coin_data .='<td><span class="cmc-name">';
			$coin_data .=$coin_link_open.$logo.$coin_symbol_html.$coin_name_html.$coin_link_close;
			$coin_data .='</span></td>';
			$coin_data .='<td data-order="'.$coin_price.'"><span class="cmc-price">' . $currency_icon. $coin_price . '</span></td>';
			$coin_data .='<td>'.$changes_coin_html.'</td>';

			if($atts['layout']=="advance"){
			$coin_data .='<td data-order="'.$market_cap.'" ><span class="cmc_live_cap">'.$currency_icon.$formatted_market_cap.'</span></td>';
			$coin_data .='<td data-order="'.$volume.'" ><span class="cmc_live_vol">'.$currency_icon.$formatted_volume.'</span></td>';
			$coin_data .='<td><div class="small-chart-area">'.$coin_link_open.get_coin_chart($coin_symbol).$coin_link_close.'</div></td>';
			}
	$coin_data.='</tr>';
	$output .= $coin_data;
			}
				
		} 

	$output .='</tbody></table></div>';
	return $output;

	}



	}
