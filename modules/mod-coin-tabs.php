<div class="mod-coin-tabs js-tabs">
	<nav class="mod-coin-tabs__nav">
		<ul class="mod-coin-tabs__nav-list">
			<li class="mod-coin-tabs__nav-item active"><a class="mod-coin-tabs__nav-item-link js-tabs-item" href="#overview">Overview</a></li>
			<li class="mod-coin-tabs__nav-item"><a class="mod-coin-tabs__nav-item-link js-tabs-item" href="#history">History</a></li>
			<li class="mod-coin-tabs__nav-item"><a class="mod-coin-tabs__nav-item-link js-tabs-item" href="#markets">Markets</a></li>
			<li class="mod-coin-tabs__nav-item"><a class="mod-coin-tabs__nav-item-link js-tabs-item" href="#roadmap">Roadmap</a></li>
		</ul>
	</nav><!-- /.mod-coin-tabs__nav -->

	<section id="overview" class="mod-coin-tabs__section js-tabs-section is-active">
		<?php echo do_shortcode('[cmc-chart]'); ?>
	</section>

	<section id="history" class="mod-coin-tabs__section js-tabs-section">
		<?php echo do_shortcode('[cmc-history]'); ?>
	</section>

	<section id="markets" class="mod-coin-tabs__section js-tabs-section">
		3
	</section>

	<section id="roadmap" class="mod-coin-tabs__section js-tabs-section">
		4
	</section>
</div><!-- /.mod-coin-tabs -->