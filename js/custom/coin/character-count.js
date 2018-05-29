(function($) {
  init();
  function init() {
    var $module = $('.js-coin-header');
    $module.each(function() {
      var $me = $(this);
      var $coinName = $('.js-coin-header__title-hdr-name', $me);
      var $coinPrice = $('.js-coin-price__value', $me);
      
      coinNameCount();
      function coinNameCount() {
        var $numWords = $coinName.html().length;
        if (($numWords >= 10) && ($numWords < 20)) {
          $coinName.addClass('more-than-10');
        }
        else if (($numWords >= 20) && ($numWords < 30)) {
          $coinName.addClass('more-than-20');
        }
        else if (($numWords >= 30) && ($numWords < 40)) {
          $coinName.addClass('more-than-30');
        } 
         else if ($numWords >= 40) {
          $coinName.addClass('more-than-40');
        } 
      }

      coinPriceCount();
      function coinPriceCount() {
        var $numPrice = $coinPrice.html().length;
        if (($numPrice >= 7) && ($numPrice < 10)) {
          $coinPrice.addClass('is-long');
        }
        else if (($numPrice >= 10) && ($numPrice < 15)) {
          $coinPrice.addClass('is-longer');
        }
        else if (($numPrice >= 15) && ($numPrice < 20)) {
          $coinPrice.addClass('is-longest');
        } 
      }

    });
  }
  $(document).on(init);

})(jQuery);
