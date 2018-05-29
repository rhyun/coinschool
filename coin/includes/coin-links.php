<?php
	$blockexplorer 	= isset($coin_data->blockexplorer)?$coin_data->blockexplorer:"#";
	$firstannounced = isset($coin_data->firstannounced)?$coin_data->firstannounced:"";
	$github 				= isset($coin_data->github)?$coin_data->github:"#";
	$reddit 				= isset($coin_data->reddit)?$coin_data->reddit:"#";
	$website 				= isset($coin_data->website)?$coin_data->website:"#";
	$whitepaper 		= isset($coin_data->whitepaper)?$coin_data->whitepaper:"#";

?>
<ul class="coin-links">
	<?php if( $website ): ?>
  <li class="coin-links__item">
    <a class="coin-links__item-link" href="<?php echo $website; ?>" title="<?php echo $website; ?>" target="_blank">
    	<button class="coin-links__item-link__btn"><span class="icon fas fa-globe"></span></button> 
    	<span class="coin-links__item-link__label">Website</span>
    </a>
  </li>
  <?php endif; ?>
  <?php if( $blog ): ?>
  <li class="coin-links__item">
    <a class="coin-links__item-link" href="<?php echo $blog; ?>" title="<?php echo $blog; ?>" target="_blank">
    	<button class="coin-links__item-link__btn"><span class="icon fas fa-bullhorn"></span></button> 
    	<span class="coin-links__item-link__label">Blog</span>
    </a>
  </li>
  <?php endif; ?>
  <?php if( $blockexplorer ): ?>
  <li class="coin-links__item">
    <a class="coin-links__item-link" href="<?php echo $blockexplorer; ?>" title="<?php echo $blockexplorer; ?>" target="_blank">
    	<button class="coin-links__item-link__btn"><span class="icon fas fa-cubes"></span></button> 
    	<span class="coin-links__item-link__label">Blockexplorer</span>
    </a>
  </li>
  <?php endif; ?>
  <?php if( $whitepaper ): ?>
  <li class="coin-links__item">
    <a class="coin-links__item-link" href="<?php echo $whitepaper; ?>" title="<?php echo $whitepaper; ?>" target="_blank">
    	<button class="coin-links__item-link__btn"><span class="icon far fa-file-alt"></span></button> 
    	<span class="coin-links__item-link__label">Whitepaper</span>
    </a>
  </li>
  <?php endif; ?>
  <?php if( $github ): ?>
  <li class="coin-links__item">
    <a class="coin-links__item-link" href="<?php echo $github; ?>" title="<?php echo $github; ?>" target="_blank">
    	<button class="coin-links__item-link__btn"><span class="icon fab fa-github"></span></button> 
    	<span class="coin-links__item-link__label">Github</span>
    </a>
  </li>
  <?php endif; ?>
  <?php if( $reddit ): ?>
  <li class="coin-links__item">
    <a class="coin-links__item-link" href="<?php echo $reddit; ?>" title="<?php echo $reddit; ?>" target="_blank">
    	<button class="coin-links__item-link__btn"><span class="icon fab fa-reddit"></span></button> 
    	<span class="coin-links__item-link__label">Reddit</span>
    </a>
  </li>
  <?php endif; ?>
  <?php if( $slack ): ?>
  <li class="coin-links__item">
    <a class="coin-links__item-link" href="<?php echo $slack; ?>" title="<?php echo $slack; ?>" target="_blank">
    	<button class="coin-links__item-link__btn"><span class="icon fab fa-slack"></span></button> 
    	<span class="coin-links__item-link__label">Slack</span>
    </a>
  </li>
  <?php endif; ?>
  <?php if( $telegram ): ?>
  <li class="coin-links__item">
    <a class="coin-links__item-link" href="<?php echo $telegram; ?>" title="<?php echo $telegram; ?>" target="_blank">
    	<button class="coin-links__item-link__btn"><span class="icon fab fa-reddit"></span></button> 
    	<span class="coin-links__item-link__label">Telegram</span>
    </a>
  </li>
  <?php endif; ?>
</ul><!-- /.coin-links -->
