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
  $coin_info = do_shortcode('[coin-market-cap-info]');

  $cmc_description 	= $coin_data->description;
  $coin_description = do_shortcode('[coin-market-cap-description]');

	$blockexplorer 	= isset($coin_data->blockexplorer)?$coin_data->blockexplorer:"#";
	$firstannounced = isset($coin_data->firstannounced)?$coin_data->firstannounced:"";
	$github 				= isset($coin_data->github)?$coin_data->github:"#";
	$reddit 				= isset($coin_data->reddit)?$coin_data->reddit:"#";
	$website 				= isset($coin_data->website)?$coin_data->website:"#";
	$whitepaper 		= isset($coin_data->whitepaper)?$coin_data->whitepaper:"#";

	$blog = get_field('blog');
	$slack = get_field('slack');
	$telegram = get_field('telegram');

	//$all_coins = cmc_coins_arr($old_currency);
?>
<div <?php post_class('coin'); ?>>
	<main class="coin-main" role="main">
		<header class="coin-header">
			<div class="coin-header__title">
				<div class="coin-header__title-ticker"><span><?php echo $coin_ticker; ?></span></div>

				<div class="coin-header__title-hdr">
					<div class="coin-header__title-hdr-icon"><?php echo $coin_icon; ?></div>

					<h1 class="coin-header__title-hdr-name"><?php echo $coin_name; ?></h1>
				</div><!-- /.coin-header__title -->

				<?php include(get_template_directory() . '/coin/includes/coin-social.php'); ?>
			</div><!-- /.coin-header__title -->

			<div class="coin-header__price">
				<?php echo $coin_price; ?>
			</div>
			
		</header><!-- /.coin-header -->

		<section class="coin-content">
			<div class="coin-content__container">
				<div class="coin-content__stats">
					<h3 class="coin-content__stats-hdr"><?php the_date('D'); ?> <span class="date"><?php the_time('F j, Y'); ?></span></h3>
					<?php echo $coin_info; ?>
				</div> 
				<div class="coin-content__details">
					<div class="coin-content__details-info">
						<ul class="coin-content__details-info__links">
							<?php if( $website ): ?>
					    <li class="coin-content__details-info__links-item">
					      <a class="coin-content__details-info__links-item-link" href="<?php echo $website; ?>" title="<?php echo $website; ?>" target="_blank"><span class="icon fas fa-globe"></span> Website</a>
					    </li>
					    <?php endif; ?>
					    <?php if( $blog ): ?>
					    <li class="coin-content__details-info__links-item">
					      <a class="coin-content__details-info__links-item-link" href="<?php echo $blog; ?>" title="<?php echo $blog; ?>" target="_blank"><span class="icon fas fa-bullhorn"></span> Blog</a>
					    </li>
					    <?php endif; ?>
					    <?php if( $blockexplorer ): ?>
					    <li class="coin-content__details-info__links-item">
					      <a class="coin-content__details-info__links-item-link" href="<?php echo $blockexplorer; ?>" title="<?php echo $blockexplorer; ?>" target="_blank"><span class="icon fas fa-cubes"></span> Blockexplorer</a>
					    </li>
					    <?php endif; ?>
					    <?php if( $whitepaper ): ?>
					    <li class="coin-content__details-info__links-item">
					      <a class="coin-content__details-info__links-item-link" href="<?php echo $whitepaper; ?>" title="<?php echo $whitepaper; ?>" target="_blank"><span class="icon far fa-file-alt"></span> Whitepaper</a>
					    </li>
					    <?php endif; ?>
					    <?php if( $github ): ?>
					    <li class="coin-content__details-info__links-item">
					      <a class="coin-content__details-info__links-item-link" href="<?php echo $github; ?>" title="<?php echo $github; ?>" target="_blank"><span class="icon fab fa-github"></span> Github</a>
					    </li>
					    <?php endif; ?>
					    <?php if( $reddit ): ?>
					    <li class="coin-content__details-info__links-item">
					      <a class="coin-content__details-info__links-item-link" href="<?php echo $reddit; ?>" title="<?php echo $reddit; ?>" target="_blank"><span class="icon fab fa-reddit"></span> Reddit</a>
					    </li>
					    <?php endif; ?>
					    <?php if( $slack ): ?>
					    <li class="coin-content__details-info__links-item">
					      <a class="coin-content__details-info__links-item-link" href="<?php echo $slack; ?>" title="<?php echo $slack; ?>" target="_blank"><span class="icon fab fa-slack"></span> Slack</a>
					    </li>
					    <?php endif; ?>
					    <?php if( $telegram ): ?>
					    <li class="coin-content__details-info__links-item">
					      <a class="coin-content__details-info__links-item-link" href="<?php echo $telegram; ?>" title="<?php echo $telegram; ?>" target="_blank"><span class="icon fab fa-reddit"></span> Telegram</a>
					    </li>
					    <?php endif; ?>
					  </ul><!-- /.coin-main-aside__info -->

					  <p class="coin-content__details-info__links-founded"><span>Birthday:</span> <?php echo $firstannounced ?></p>
					</div><!-- /.coin-content__details-info -->

						<div class="coin-content__details-summary">
							<h3 class="coin-content__details-summary__hdr">Summary.</h3>
							<div class="coin-content__details-summary__text"><?php if($coin_description !== '') { echo do_shortcode('[coin-market-cap-description]'); } else { echo $cmc_description; } ?></div>
						</div>
				</div><!-- /.coin-content__details -->

				<!-- <button class="coin-content__about-btn btn"><span>See Purpose</span></button> -->
			</div><!-- /.coin-content__container -->
		</section><!-- /.coin-content -->
	</main><!-- /.coin-main -->

	<?php include(get_template_directory() . '/modules/mod-coin-tabs.php'); ?>

	<div class="coin-body">

	</div>

</div><!-- /.coin -->

<?php endwhile; endif; ?>
<?php get_footer(); ?>
