<div class="mod-coin-tabs js-tabs">
	<nav class="mod-coin-tabs__nav">
		<ul class="mod-coin-tabs__nav-list">
			<li class="mod-coin-tabs__nav-item active"><a class="mod-coin-tabs__nav-item-link js-tabs-item" href="#overview">Overview</a></li>
			<li class="mod-coin-tabs__nav-item"><a class="mod-coin-tabs__nav-item-link js-tabs-item" href="#charts">Charts</a></li>
	<!-- 		<li class="mod-coin-tabs__nav-item"><a class="mod-coin-tabs__nav-item-link js-tabs-item" href="#markets">Markets</a></li>
			<li class="mod-coin-tabs__nav-item"><a class="mod-coin-tabs__nav-item-link js-tabs-item" href="#roadmap">Roadmap</a></li> -->
		</ul>
	</nav><!-- /.mod-coin-tabs__nav -->

	<div class="mod-coin-tabs__container">

		<section id="overview" class="mod-coin-tabs__section js-tabs-section is-active">
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
		</section>

		<section id="charts" class="mod-coin-tabs__section js-tabs-section">
			<?php echo do_shortcode('[cmc-chart]'); ?>

			<?php echo do_shortcode('[cmc-history]'); ?>
		</section>
	<!-- 
		<section id="markets" class="mod-coin-tabs__section js-tabs-section">
			3
		</section>

		<section id="roadmap" class="mod-coin-tabs__section js-tabs-section">
			4
		</section> -->
	</div>
</div><!-- /.mod-coin-tabs -->