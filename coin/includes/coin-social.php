<?php

	$facebook  = isset($coin_data->facebook)?$coin_data->facebook:"#";
	$twitter 	 = isset($coin_data->twitter)?$coin_data->twitter:"#";
	$youtube 	 = isset($coin_data->youtube)?$coin_data->youtube:"#";
?>

<ul class="coin-social">
	<?php if( $facebook ): ?>
  <li class="coin-social__item">
    <a class="coin-social__item-link" href="<?php echo $facebook; ?>" title="Facebook" target="_blank"><span class="icon fab fa-facebook-f"></span></a>
  </li>
  <?php endif; ?>
  <?php if( $twitter ): ?>
  <li class="coin-social__item">
    <a class="coin-social__item-link" href="<?php echo $twitter; ?>" title="Twitter" target="_blank"><span class="icon fab fa-twitter"></span></a>
  </li>
  <?php endif; ?>
  <?php if( $youtube ): ?>
  <li class="coin-social__item">
    <a class="coin-social__item-link" href="<?php echo $youtube; ?>" title="Youtube" target="_blank"><span class="icon fab fa-youtube"></span></a>
  </li>
  <?php endif; ?>
  <li class="coin-social__item">
    <a class="coin-social__item-link" href="#" title="Share" target="_blank"><span class="icon fas fa-share"></span></a>
  </li>
</ul><!-- /.coin-social -->