(function($) {
    'use strict';
    
     if($("#coin-market-cap-widget").hasClass( "cmc_live_updates" )&&
       $("#coin-market-cap-widget").attr('data-old-currency')=="USD"
      ){
    var $liveUpdates=$(".cmc_live_updates");

    var apiUrl = 'https://coincap.io';
    var socket = io.connect('https://socket.coincap.io');
  
   socket.on('trades', function(response) {
        var $row = $liveUpdates.find('tr[data-coin-symbol="'+ response.coin +'"]');
        var fixed_row= $('.DTFC_Cloned').find('tr[data-coin-symbol="'+ response.coin +'"]');
        if ($row.length) {
            var cssClass = (response.msg.price > $row.attr('data-coin-price')) ? 'price-plus' : 'price-minus';
        
             var changesCls = (response.msg.price > $row.attr('data-coin-price')) ? 'up' : 'down';
             var changes_html='';
             if(response.msg.cap24hrChange > 0){
              
                  changes_html='<span class="changes up"><i class="fa fa-arrow-up" aria-hidden="true"></i>'+response.msg.perc +'%</span>';
             }else{
                 changes_html='<span class="changes down"><i class="fa fa-arrow-down" aria-hidden="true"></i>'+response.msg.perc +'%</span>';
                  
             }


             var formatted_price=response.msg.price;
          $row.addClass(cssClass);
          fixed_row.addClass(cssClass);
           //   $('.cmc_live_updates > tbody').find('tr').addClass(cssClass);
            $row.attr('data-coin-price', response.msg.price);
          // $row.find('.cmc_live_cap').html('<i class="fa fa-usd" aria-hidden="true"></i><span>'+ response.msg.mktcap +'</span>');
            $row.find('.cmc-price').html('<i class="fa fa-usd" aria-hidden="true"></i><span class="cmc-formatted-price">'+formatted_price+'</span>');
          //  $row.find('.live_vwap24').html('<i class="fa fa-usd" aria-hidden="true"></i><span>'+ response.msg.vwapData +'</span>');
           // $row.find('.cmc_live_supply').text(response.msg.supply);
          // $row.find('.cmc_live_vol').html('<i class="fa fa-usd" aria-hidden="true"></i><span>'+ response.msg.volume +'</span>');
        
            $row.find('.cmc_live_ch').html(changes_html);
            initColumnNumbers();
            setTimeout(function() {
              $row.removeClass('price-plus price-minus');
              fixed_row.removeClass('price-plus price-minus');
            },1500);
        }
    });
 
 }
    // format numbers
    var initColumnNumbers = function() {
     $(".cmc_live_updates").find('.cmc-formatted-price').number(true,4, '.', ',');
    
    };
})(jQuery);