<?php
/**
 * Template Name: Coin Template
 */
  get_header();
  if ( have_posts() ) : while ( have_posts() ) : the_post();

  //retrieve data from api
	$coin_data 			= cmc_coin_extra_data_api($coin_id);




	// $get_coin_array = cmc_currencies_details();

	// var_dump($get_coin_array[$coin_id]);

  $coin_icon	 		= coin_logo_html($coin_id,128);
  $coin_symbol 		= $coin_id;

  $currency_icon 	= cmc_old_cur_symbol($old_currency);

  $cmc_description 	= $coin_data->description;
  $coin_description = do_shortcode('[coin-market-cap-description]');

	$blockexplorer 	= isset($coin_data->blockexplorer)?$coin_data->blockexplorer:"#";
	$facebook 			= isset($coin_data->facebook)?$coin_data->facebook:"#";
	$firstannounced = isset($coin_data->firstannounced)?$coin_data->firstannounced:"";
	$github 				= isset($coin_data->github)?$coin_data->github:"#";
	$reddit 				= isset($coin_data->reddit)?$coin_data->reddit:"#";
	$twitter 				= isset($coin_data->twitter)?$coin_data->twitter:"#";
	$website 				= isset($coin_data->website)?$coin_data->website:"#";
	$whitepaper 		= isset($coin_data->whitepaper)?$coin_data->whitepaper:"#";
	$youtube 				= isset($coin_data->youtube)?$coin_data->youtube:"#";

?>

<div <?php post_class('coin'); ?>>
	<main class="coin-main" role="main">
		<header class="coin-main-header">
			<div class="coin-main-header__title">
				<div class="coin-main-header__title-image"><?php echo $coin_icon; ?></div>
				<h1 class="coin-main-header__title-hdr"><?php echo $coin_name; ?></h1>
				<span class="coin-main-header__title-ticker"><?php echo $coin_symbol; ?></span>
			</div>
			
			<div class="coin-main-header__price">
				<h2 class="coin-main-header__price-fiat"><span class="currency-symbol"><?php echo $currency_icon ?></span> <?php echo $single_default_currency; ?></h2>
				<!-- <span class="coin-main-header__price-pairing"><?php echo $price_btc; ?> <span class="paring-ticker">btc</span></span> -->
			</div>

			<div class="coin-main-header__cats">
			</div>
		</header><!-- /.coin-main-header -->

		<div class="coin-main-description">
			<h3 class="coin-main-description__hdr"><span>Short Description</span></h3>
			<p class="coin-main-description__excerpt"><?php if($coin_description !== '') { echo do_shortcode('[coin-market-cap-description]'); } else { echo $cmc_description; } ?></p>
			
			<div class="coin-main-description__cta">
				<a class="coin-main-description__cta-read btn" href="#" title="Read More"><span>Read More</span></a>
				<a class="coin-main-description__cta-video btn-text" href="#" title="Watch Video"><span class="icon fas fa-play-circle"></span> <span>Watch Video</span></a>
			</div>
		</div><!-- /.coin-main-description -->

		<aside class="coin-main-aside">
			<ul class="coin-main-aside__stats">
				<li class="coin-main-aside__stats-item coin-main-aside__stats-item--rank">
					<ul class="coin-main-aside__stats-item__sub">
						<li class="coin-main-aside__stats-item__sub-item">Market Cap <span>Rank</span></li> 
						<li class="coin-main-aside__stats-item__sub-item">2</li>
					</ul>
				</li>
				<li class="coin-main-aside__stats-item coin-main-aside__stats-item--cap">
					<ul class="coin-main-aside__stats-item__sub">
						<li class="coin-main-aside__stats-item__sub-item">Market Cap</li> 
						<li class="coin-main-aside__stats-item__sub-item"><span class="currency-symbol">$</span> <?php echo $market_cap; ?></li>
					</ul>
				</li>
				<li class="coin-main-aside__stats-item coin-main-aside__stats-item--supply">
					<ul class="coin-main-aside__stats-item__sub">
						<li class="coin-main-aside__stats-item__sub-item">Supply</li> 
						<li class="coin-main-aside__stats-item__sub-item"><?php echo $supply; ?></li>
					</ul>
				</li>
			</ul><!-- /.coin-main-aside__stats -->

			<ul class="coin-main-aside__info">
				<?php if( $website ): ?>
		    <li class="coin-main-aside__info-item coin-main-aside__info-item--website">
		    	<span class="icon fas fa-globe"></span>
		      <a class="coin-main-aside__info-item-link" href="<?php echo $website; ?>" title="Website" target="_blank"><span><?php echo $website; ?></span></a>
		    </li>
		    <?php endif; ?>
		    <?php if( $news ): ?>
		    <li class="coin-main-aside__info-item coin-main-aside__info-item--news">
		    	<span class="icon fas fa-bullhorn"></span>
		      <a class="coin-main-aside__info-item-link" href="<?php echo $news; ?>" title="News" target="_blank"><span><?php echo $news; ?></span></a>
		    </li>
		    <?php endif; ?>
		    <?php if( $blockexplorer ): ?>
		    <li class="coin-main-aside__info-item coin-main-aside__info-item--block">
		    	<span class="icon fas fa-cubes"></span>
		      <a class="coin-main-aside__info-item-link" href="<?php echo $blockexplorer; ?>" title="Block Explorer" target="_blank"><span><?php echo $blockexplorer; ?></span></a>
		    </li>
		    <?php endif; ?>
		    <?php if( $whitepaper ): ?>
		    <li class="coin-main-aside__info-item coin-main-aside__info-item--whitepaper">
		    	<span class="icon far fa-file-alt"></span>
		      <a class="coin-main-aside__info-item-link" href="<?php echo $whitepaper; ?>" title="Whitepaper" target="_blank"><span><?php echo $whitepaper; ?></span></a>
		    </li>
		    <?php endif; ?>
		    <?php if( $github ): ?>
		    <li class="coin-main-aside__info-item coin-main-aside__info-item--github">
		    	<span class="icon fab fa-github"></span>
		      <a class="coin-main-aside__info-item-link" href="<?php echo $github_code; ?>" title="github Code" target="_blank"><span><?php echo $github; ?></span></a>
		    </li>
		    <?php endif; ?>
		  </ul><!-- /.coin-main-aside__info -->
		</aside>
	</main><!-- /.coin-main -->

	<?php include(get_template_directory() . '/modules/mod-coin-tabs.php'); ?>

	<div class="coin-body">

	</div>

</div><!-- /.coin -->

<?php endwhile; endif; ?>
<?php get_footer(); ?>
