<?php
/**
 * Template Name: Coin Template
 */
  get_header();
  if ( have_posts() ) : while ( have_posts() ) : the_post();

	
  
  $coin_description = do_shortcode('[coin-market-cap-description]');
  
  // Fetch Coin Market Cap Extra Data
  $coin_data 			= cmc_coin_extra_data_api($coin_id);

	$algorithum 		= isset($coin_data->algorithum)?$coin_data->algorithum:"";
  $bitcointalk 		= isset($coin_data->bitcointalk)?$coin_data->bitcointalk:"#";
	$blockexplorer 	= isset($coin_data->blockexplorer)?$coin_data->blockexplorer:"#";
	$blog 					= isset($coin_data->blog)?$coin_data->blog:"#";
	$description 		= isset($coin_data->description)?$coin_data->description:'';
	$facebook  			= isset($coin_data->facebook)?$coin_data->facebook:"#";
	$firstannounced = isset($coin_data->firstannounced)?$coin_data->firstannounced:"";
	$github 				= isset($coin_data->github)?$coin_data->github:"#";
	$reddit 				= isset($coin_data->reddit)?$coin_data->reddit:"#";
	$twitter 	 			= isset($coin_data->twitter)?$coin_data->twitter:"#";
	$website 				= isset($coin_data->website)?$coin_data->website:"#";
	$whitepaper 		= isset($coin_data->whitepaper)?$coin_data->whitepaper:"#";
	$youtube 	 			= isset($coin_data->youtube)?$coin_data->youtube:"#";

			//$coin_ticker = $coin_id;

	 	$coin_id  = (string) trim(get_query_var('coin_name'));
	 	$real_cur = get_query_var('currency');
	 	// 	$single_default_currency =$cmc_titan->getOption('default_currency');
		$old_currency=trim($real_cur)!=="" ?trim($real_cur):$single_default_currency;

	 	 	$all_coins=cmc_coins_arr($old_currency);

	 	 	$coin = $all_coins[$coin_id];

			$coin_symbol   = $coin['symbol'];
			//$currency_icon = mc_old_cur_symbol($old_currency);

			$coin_icon = coin_logo_html($coin['id'], 256);


	if(get_query_var('coin_id')){
		$coin_id=(string) trim(get_query_var('coin_name'));
		// The Query
		$query=array('post_type' => 'cmc-description','meta_value' => get_query_var('coin_name'));
		$the_query = new WP_Query( $query );
		// The Loop
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();

				$slack = get_field('slack');
				$telegram = get_field('telegram');
				
			}
			/* Restore original Post Data*/
			wp_reset_postdata();
		}
	}

?>
<?php get_post_meta(); ?>
<div <?php post_class('coin'); ?>>
	<main class="coin-main" role="main">
		<header class="coin-header js-coin-header">
			<div class="coin-header__container">
				<div class="coin-header__ticker"><span><?php echo $coin_symbol; ?></span></div>
				<div class="coin-header__title">
					<div class="coin-header__title-hdr">
						<div class="coin-header__title-hdr-icon"><?php echo $coin_icon; ?></div>

						<h1 class="coin-header__title-hdr-name js-coin-header__title-hdr-name"><?php echo $coin_name; ?></h1>
					</div><!-- /.coin-header__title -->

					<?php include(get_template_directory() . '/coin/includes/coin-social.php'); ?>
				</div><!-- /.coin-header__title -->

				<div class="coin-header__price">
					<?php echo do_shortcode('[coin-market-cap-price]'); ?>
				</div>
			</div><!-- /.coin-header__container -->
		</header><!-- /.coin-header -->

		<section class="coin-content">
			<div class="coin-content__container">
				<h3 class="coin-content__hdr"><?php the_date('D'); ?> <span class="date"><?php the_time('F j, Y'); ?></span></h3>

				<div class="coin-content__stats">
					<?php echo do_shortcode('[coin-market-cap-stats]'); ?>
				</div> 

				<div class="coin-content__summary">
					<h3 class="coin-content__summary-hdr">Summary</h3>
					<div class="coin-content__summary-text">
						<h5 class="coin-content__summary-text__hdr">What is <?php echo $coin_name; ?>?</h5>
						<?php if($coin_description !== '') { echo do_shortcode('[coin-market-cap-description]'); } else { echo $description; } ?>	
					</div>
				</div>

				<div class="coin-content__info">
					<?php include(get_template_directory() . '/coin/includes/coin-links.php'); ?>
				</div>
				
				
			</div><!-- /.coin-content__container -->
		</section><!-- /.coin-content -->
	</main><!-- /.coin-main -->

	<?php include(get_template_directory() . '/coin/includes/coin-tabs.php'); ?>
</div><!-- /.coin -->

<?php endwhile; endif; ?>
<?php get_footer(); ?>
