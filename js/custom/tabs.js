(function($) {
  init();
  function init() {
    var $module = $('.js-tabs');
    $module.each(function() {
      var $me = $(this);
      var $tabsItem = $('.js-tabs-item', $me);
      var $tabsSection = $('.js-tabs-section', $me);

      $tabsItem.on('click', function(event) {
        event.preventDefault();
        $(this).parent().addClass('active');
        $(this).parent().siblings().removeClass('active');
        var tab = $(this).attr('href');
        $tabsSection.not(tab).removeClass('is-active');
        $(tab).addClass('is-active');
      });
    });
  }
  $(document).on(init);

})(jQuery);
