<?php
/**
 * Template Name: Coin Template
 */
  get_header();
  if ( have_posts() ) : while ( have_posts() ) : the_post();
  //retrieve data from api
	$coin_data 			= cmc_coin_extra_data_api($coin_id);

  $coin_icon	 		= coin_logo_html($coin_id,128);
  $coin_ticker 		= $coin_id;

  $coin_price = do_shortcode('[coin-market-cap-price]');
  $coin_info = do_shortcode('[coin-market-cap-info]');

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

	//$all_coins = cmc_coins_arr($old_currency);
?>
<div <?php post_class('coin'); ?>>
	<main class="coin-main" role="main">
		<header class="coin-header">
			<div class="coin-header__title">
				<div class="coin-header__title-ticker"><?php echo $coin_ticker; ?></div>

				<div class="coin-header__title-icon"><?php echo $coin_icon; ?></div>

				<div class="coin-header__title-hdr">
					<h1 class="coin-header__title-hdr-name"><?php echo $coin_name; ?></h1>

					<ul class="coin-header__title-hdr-social">
						<?php if( $facebook ): ?>
				    <li class="coin-header__title-hdr-social__item">
				      <a class="coin-header__title-hdr-social__item-link" href="<?php echo $facebook; ?>" title="Facebook" target="_blank"><span class="icon fab fa-facebook-f"></span></a>
				    </li>
				    <?php endif; ?>
				    <?php if( $twitter ): ?>
				    <li class="coin-header__title-hdr-social__item">
				      <a class="coin-header__title-hdr-social__item-link" href="<?php echo $twitter; ?>" title="Twitter" target="_blank"><span class="icon fab fa-twitter"></span></a>
				    </li>
				    <?php endif; ?>
				    <?php if( $youtube ): ?>
				    <li class="coin-header__title-hdr-social__item">
				      <a class="coin-header__title-hdr-social__item-link" href="<?php echo $youtube; ?>" title="Youtube" target="_blank"><span class="icon fab fa-youtube"></span></a>
				    </li>
				    <?php endif; ?>
				    <li class="coin-header__title-hdr-social__item">
				      <a class="coin-header__title-hdr-social__item-link" href="#" title="Share" target="_blank"><span class="icon fas fa-share"></span></a>
				    </li>
					</ul><!-- /.coin-header__title-hdr-social -->
				</div><!-- /.coin-header__title -->
			</div><!-- /.coin-header-->

			<div class="coin-header__price">
				<?php echo $coin_price; ?>
			</div>
		</header><!-- /.coin-header -->

		<section class="coin-content">
			<div class="coin-content__info">
				<?php echo $coin_info; ?>
			</div> 

			<div class="coin-content__about">
				<h3 class="coin-content__about-hdr">About Coin.</h3>

				<div class="coin-content__about-cta">
					<ul class="coin-content__about-cta-list">
						<?php if( $website ): ?>
				    <li class="coin-content__about-cta-item coin-content__about-cta-item--website">
				      <a class="coin-content__about-cta-item-link" href="<?php echo $website; ?>" title="<?php echo $website; ?>" target="_blank"><span class="icon fas fa-globe"></span> Website</a>
				    </li>
				    <?php endif; ?>
				    <?php if( $news ): ?>
				    <li class="coin-content__about-cta-item coin-content__about-cta-item--news">
				      <a class="coin-content__about-cta-item-link" href="<?php echo $news; ?>" title="<?php echo $news; ?>" target="_blank"><span class="icon fas fa-bullhorn"></span> Announcements</a>
				    </li>
				    <?php endif; ?>
				    <?php if( $blockexplorer ): ?>
				    <li class="coin-content__about-cta-item coin-content__about-cta-item--block">
				      <a class="coin-content__about-cta-item-link" href="<?php echo $blockexplorer; ?>" title="<?php echo $blockexplorer; ?>" target="_blank"><span class="icon fas fa-cubes"></span> Blockexplorer</a>
				    </li>
				    <?php endif; ?>
				    <?php if( $whitepaper ): ?>
				    <li class="coin-content__about-cta-item coin-content__about-cta-item--whitepaper">
				      <a class="coin-content__about-cta-item-link" href="<?php echo $whitepaper; ?>" title="<?php echo $whitepaper; ?>" target="_blank"><span class="icon far fa-file-alt"></span> Whitepaper</a>
				    </li>
				    <?php endif; ?>
				    <?php if( $github ): ?>
				    <li class="coin-content__about-cta-item coin-content__about-cta-item--github">
				      <a class="coin-content__about-cta-item-link" href="<?php echo $github; ?>" title="<?php echo $github; ?>" target="_blank"><span class="icon fab fa-github"></span> Github</a>
				    </li>
				    <?php endif; ?>
				    <?php if( $reddit ): ?>
				    <li class="coin-content__about-cta-item coin-content__about-cta-item--reddit">
				      <a class="coin-content__about-cta-item-link" href="<?php echo $reddit; ?>" title="<?php echo $reddit; ?>" target="_blank"><span class="icon fab fa-reddit"></span> Reddit</a>
				    </li>
				    <?php endif; ?>
				  </ul><!-- /.coin-main-aside__info -->

				  <!-- <p class="coin-content__about-cta-founded"><span>Announced:</span> <?php echo $firstannounced ?></p> -->
				</div>

				<div class="coin-content__about-description">
					<h3 class="coin-content__about-description-hdr">Short Description.</h3>
					<div class="coin-content__about-description-excerpt"><?php if($coin_description !== '') { echo do_shortcode('[coin-market-cap-description]'); } else { echo $cmc_description; } ?></div>
					
					<!-- <div class="coin-content__about-description-cta">
						<a class="coin-content__about-description-cta-read btn" href="#" title="Read More"><span>See Purpose</span></a>
						<a class="coin-description__cta-video btn-text" href="#" title="Watch Video"><span class="icon fas fa-play-circle"></span> <span>Watch Video</span></a>
					</div> -->
				</div>
			</div><!-- /.coin-content__about -->

			<!-- <aside class="coin-content__aside">
				<?php include(get_template_directory() . '/modules/mod-ad-300x250.php'); ?>
			</aside> -->
		</section><!-- /.coin-content -->
	</main><!-- /.coin-main -->

	<?php include(get_template_directory() . '/modules/mod-coin-tabs.php'); ?>

	<div class="coin-body">

	</div>

</div><!-- /.coin -->

<?php endwhile; endif; ?>
<?php get_footer(); ?>
