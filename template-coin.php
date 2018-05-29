<?php
/**
 * Template Name: Coin Template
 */
  get_header();
  if ( have_posts() ) : while ( have_posts() ) : the_post();
  //retrieve data from api
	$coin_data 			= cmc_coin_extra_data_api($coin_id);

  $coin_icon	 		= coin_logo_html($coin_id,256);
  $coin_ticker 		= $coin_id;

  $coin_price = do_shortcode('[coin-market-cap-price]');
  $coin_stats = do_shortcode('[coin-market-cap-info]');

  $cmc_description 	= $coin_data->description;
  $coin_description = do_shortcode('[coin-market-cap-description]');

	
	$blog = get_field('blog');
	$slack = get_field('slack');
	$telegram = get_field('telegram');

	//$all_coins = cmc_coins_arr($old_currency);
?>
<div <?php post_class('coin'); ?>>
	<main class="coin-main" role="main">
		<header class="coin-header js-coin-header">
			<div class="coin-header__container">
				<div class="coin-header__ticker"><span><?php echo $coin_ticker; ?></span></div>
				<div class="coin-header__title">
					<div class="coin-header__title-hdr">
						<div class="coin-header__title-hdr-icon"><?php echo $coin_icon; ?></div>

						<h1 class="coin-header__title-hdr-name js-coin-header__title-hdr-name"><?php echo $coin_name; ?></h1>
					</div><!-- /.coin-header__title -->

					<?php include(get_template_directory() . '/coin/includes/coin-social.php'); ?>
				</div><!-- /.coin-header__title -->

				<div class="coin-header__price">
					<?php echo $coin_price; ?>
				</div>
			</div><!-- /.coin-header__container -->
		</header><!-- /.coin-header -->

		<section class="coin-content">
			<div class="coin-content__container">
				<h3 class="coin-content__hdr"><?php the_date('D'); ?> <span class="date"><?php the_time('F j, Y'); ?></span></h3>

				<div class="coin-content__stats">
					<?php echo $coin_stats; ?>
				</div> 

				<div class="coin-content__info">
					<?php include(get_template_directory() . '/coin/includes/coin-links.php'); ?>
				</div>
				
				<div class="coin-content__summary">
					<h3 class="coin-content__summary-hdr">Summary</h3>
					<div class="coin-content__summary-text"><?php if($coin_description !== '') { echo do_shortcode('[coin-market-cap-description]'); } else { echo $cmc_description; } ?></div>
				</div>
			</div><!-- /.coin-content__container -->
		</section><!-- /.coin-content -->
	</main><!-- /.coin-main -->

	<?php include(get_template_directory() . '/coin/includes/coin-tabs.php'); ?>

	<div class="coin-body">

	</div>
</div><!-- /.coin -->

<?php endwhile; endif; ?>
<?php get_footer(); ?>
