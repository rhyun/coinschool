<?php
/**
 * The template for displaying all single coin posts.
 *
 * @package Coin Love
 */
	
	get_header(); 
	if ( have_posts() ) : while ( have_posts() ) : the_post();

	$coin       = get_cryptowp_coin_by_id( strtolower( get_the_title() ) );
	$coin_error = get_cryptowp( 'coins', $coin, 'error' );

	if ( ! empty( $coin_error ) )
		continue;

	$coin_name    = get_cryptowp( 'coins', $coin, 'name' );
	$coin_symbol  = get_cryptowp( 'coins', $coin, 'symbol' );
	$coin_price   = get_cryptowp( 'coins', $coin, 'price' );
	$coin_percent = get_cryptowp( 'coins', $coin, 'percent' );
	$coin_icon    = get_cryptowp( 'coins', $coin, 'icon' );
	$coin_value   = get_cryptowp( 'coins', $coin, 'value' );
	$market_cap 	= number_format( get_cryptowp( 'coins', $coin, 'market_cap' ) );
	$price_btc  	= get_cryptowp( 'coins', $coin, 'price_btc' );
	$supply     	= number_format( get_cryptowp( 'coins', $coin, 'supply' ) );

	$website     = get_field('website');
  $news        = get_field('news');
  $block   		 = get_field('block_explorer');
  $whitepaper  = get_field('whitepaper');
  $source 		 = get_field('source_code');
  $video       = get_field('video');

	$facebook = get_field('facebook');
  $twitter 	= get_field('twitter');
  $youtube 	= get_field('youtube');
  $slack 		= get_field('slack');
  $telegram = get_field('telegram');
  $reddit 	= get_field('reddit');
?>

<div <?php post_class('coin'); ?>>
	<main class="coin-main" role="main">
		<header class="coin-main-header">
			<div class="coin-main-header__title">
				<img class="coin-main-header__title-image" src="<?php echo $coin_icon; ?>'); ?>" alt="<?php the_title(); ?>" />
				<h1 class="coin-main-header__title-hdr"><?php the_title(); ?></h1>
				<span class="coin-main-header__title-ticker"><?php echo $coin_symbol; ?></span>
			</div>
			
			<div class="coin-main-header__price">
				<h2 class="coin-main-header__price-fiat"><span class="currency-symbol">$</span> <?php echo $coin_price; ?></h2>
				<span class="coin-main-header__price-pairing"><?php echo $price_btc; ?> <span class="paring-ticker">btc</span></span>
			</div>

			<div class="coin-main-header__cats">
			</div>
		</header><!-- /.coin-main-header -->

		<div class="coin-main-description">
			<h3 class="coin-main-description__hdr"><span>Short Description</span></h3>
			<p class="coin-main-description__excerpt">Ethereum is a decentralized platform that runs smart contracts: applications that run exactly as programmed without any possibility of downtime, censorship, fraud or third-party interference. Abstract The intent of Ethereum is to create an alternative protocol for building decentralized applications, providing a different set of tradeoffs that we believe will be very useful for a largehe added powers of Turing-completeness, value-awareness, blockchain-awareness and state...</p>
			
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
		      <a class="coin-main-aside__info-item-link" href="<?php echo $website; ?>" title="<?php echo $website; ?>" target="_blank"><span><?php echo $website; ?></span></a>
		    </li>
		    <?php endif; ?>
		    <?php if( $news ): ?>
		    <li class="coin-main-aside__info-item coin-main-aside__info-item--news">
		    	<span class="icon fas fa-bullhorn"></span>
		      <a class="coin-main-aside__info-item-link" href="<?php echo $news; ?>" title="News" target="_blank"><span><?php echo $news; ?></span></a>
		    </li>
		    <?php endif; ?>
		    <?php if( $block ): ?>
		    <li class="coin-main-aside__info-item coin-main-aside__info-item--block">
		    	<span class="icon fas fa-cubes"></span>
		      <a class="coin-main-aside__info-item-link" href="<?php echo $block; ?>" title="Youtube" target="_blank"><span><?php echo $block; ?></span></a>
		    </li>
		    <?php endif; ?>
		    <?php if( $whitepaper ): ?>
		    <li class="coin-main-aside__info-item coin-main-aside__info-item--whitepaper">
		    	<span class="icon far fa-file-alt"></span>
		      <a class="coin-main-aside__info-item-link" href="<?php echo $whitepaper; ?>" title="Whitepaper" target="_blank"><span><?php echo $whitepaper; ?></span></a>
		    </li>
		    <?php endif; ?>
		    <?php if( $source ): ?>
		    <li class="coin-main-aside__info-item coin-main-aside__info-item--source">
		    	<span class="icon fas fa-code"></span>
		      <a class="coin-main-aside__info-item-link" href="<?php echo $source_code; ?>" title="Source Code" target="_blank"><span><?php echo $source; ?></span></a>
		    </li>
		    <?php endif; ?>
		  </ul><!-- /.coin-main-aside__info -->
		</aside>
		<!-- 
		<nav class="coin-main-social">
			<ul class="coin-main-social__list">
		    <?php if( $facebook ): ?>
		    <li class="coin-main-social__list-item">
		      <a class="coin-main-social__list-item-link" href="<?php echo $facebook; ?>" title="Facebook" target="_blank"><span class="icon fab fa-facebook"></span></a>
		    </li>
		    <?php endif; ?>
		    <?php if( $twitter ): ?>
		    <li class="coin-main-social__list-item">
		      <a class="coin-main-social__list-item-link" href="<?php echo $twitter; ?>" title="Twitter" target="_blank"><span class="icon fab fa-twitter"></span></a>
		    </li>
		    <?php endif; ?>
		    <?php if( $youtube ): ?>
		    <li class="coin-main-social__list-item">
		      <a class="coin-main-social__list-item-link" href="<?php echo $youtube; ?>" title="Youtube" target="_blank"><span class="icon fab fa-youtube"></span></a>
		    </li>
		    <?php endif; ?>
		    <?php if( $slack ): ?>
		    <li class="coin-main-social__list-item">
		      <a class="coin-main-social__list-item-link" href="<?php echo $slack; ?>" title="Slack" target="_blank"><span class="icon fab fa-slack"></span></a>
		    </li>
		    <?php endif; ?>
		    <?php if( $telegram ): ?>
		    <li class="coin-main-social__list-item coin-main-social__list-item--store">
		      <a class="coin-main-social__list-item-link" href="<?php echo $telegram; ?>" title="Telegram" target="_blank"><span class="icon fab fa-telegram"></span></a>
		    </li>
		    <?php endif; ?>
		    <?php if( $reddit ): ?>
		    <li class="coin-main-social__list-item coin-main-social__list-item--store">
		      <a class="coin-main-social__list-item-link" href="<?php echo $reddit; ?>" title="Reddit" target="_blank"><span class="icon fab fa-reddit"></span></a>
		    </li>
		    <?php endif; ?>
		  </ul>
		</nav> -->
	</main><!-- /.coin-main -->

	<?php include(get_template_directory() . '/modules/mod-coin-tabs.php'); ?>

	<div class="coin-body">


		<div class="coin-purpose">
			Short description
			Ethereum is a decentralized platform that runs smart contracts: applications that run exactly as programmed without any possibility of downtime, censorship, fraud or third-party interference.

			Abstract
			The intent of Ethereum is to create an alternative protocol for building decentralized applications, providing a different set of tradeoffs that we believe will be very useful for a large class of decentralized applications, with particular emphasis on situations where rapid development time, security for small and rarely used applications, and the ability of different applications to very efficiently interact, are important. Ethereum does this by building what is essentially the ultimate abstract foundational layer: a blockchain with a built-in Turing-complete programming language, allowing anyone to write smart contracts and decentralized applications where they can create their own arbitrary rules for ownership, transaction formats and state transition functions. A bare-bones version of Namecoin can be written in two lines of code, and other protocols like currencies and reputation systems can be built in under twenty. Smart contracts, cryptographic "boxes" that contain value and only unlock it if certain conditions are met, can also be built on top of the platform, with vastly more power than that offered by Bitcoin scripting because of the added powers of Turing-completeness, value-awareness, blockchain-awareness and state.
		</div>
	  
	</div>


</div><!-- /.coin -->

<?php endwhile; endif; ?>
<?php get_footer(); ?>
