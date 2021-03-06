(function ($) {
    'use strict';

    if ($("#coin-market-cap-widget").hasClass("cmc_live_updates")) {
        var $liveUpdates = $(".cmc_live_updates");

        var apiUrl = 'https://coincap.io';
        var socket = io.connect('https://socket.coincap.io');

        socket.on('trades', function (response) {
            var $row = $liveUpdates.find('tr[data-coin-symbol="' + response.coin + '"]');
            var fixed_row = $('.DTFC_Cloned').find('tr[data-coin-symbol="' + response.coin + '"]');
            var currency_rate = $('#cmc_usd_conversion_box option:selected').data('currency-rate');
            var currency_name = $('#cmc_usd_conversion_box option:selected').val();
            var currency_symbol = $('#cmc_usd_conversion_box option:selected').data('currency-symbol');

            if ($row.length) {
                var cssClass = (response.msg.price > $row.attr('data-coin-price')) ? 'price-plus' : 'price-minus';

                var changesCls = (response.msg.price > $row.attr('data-coin-price')) ? 'up' : 'down';
                var changes_html = '';
                if (response.msg.cap24hrChange > 0) {
                    changes_html = '<span class="changes up"><i class="fa fa-arrow-up" aria-hidden="true"></i>' + response.msg.perc + '%</span>';
                } else {
                    changes_html = '<span class="changes down"><i class="fa fa-arrow-down" aria-hidden="true"></i>' + response.msg.perc + '%</span>';
                }

                if (currency_name == "USD") {
                    var formatted_price = response.msg.price;
                } else if (currency_name == "BTC") {
                    if (response.coin != "BTC") {
                        var formatted_price = response.msg.price / currency_rate;
                    } else {
                        formatted_price = '1.00';
                    }
                } else {
                    var formatted_price = response.msg.price * currency_rate;
                }

                $row.addClass(cssClass);
                fixed_row.addClass(cssClass);
                $row.attr('data-coin-price', formatted_price);
                $row.find('.cmc-price').html(currency_symbol + '<span class="cmc-formatted-price">' + formatted_price + '</span>');
                $row.find('.cmc_live_ch').html(changes_html);
                initColumnNumbers();
                setTimeout(function () {
                    $row.removeClass('price-plus price-minus');
                    fixed_row.removeClass('price-plus price-minus');
                }, 1500);
            }
        });

    }
    // format numbers
    var initColumnNumbers = function () {
        $(".cmc_live_updates").find('.cmc-formatted-price').number(true, 4, '.', ',');

    };
})(jQuery);