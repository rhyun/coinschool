(function($) {
    'use strict';
     
    /*
        Single Page chart js
    */
    
      jQuery('.cmc-chart').each(function(index)
        {
            var coinId=jQuery(this).data("coin-id");
            var chart_color=jQuery(this).data("chart-color");
            var coinperiod=jQuery(this).data("coin-period");
            var priceData = [];
              var volData = [];
            var apiUrl = 'https://coincap.io';
             var price_section =jQuery(this).find(".CCP-"+coinId);
             $(this).find('.CCP').number(true); 
             jQuery.ajax({
                    url: apiUrl + '/history/'+coinperiod+'/' + coinId,
                    method: 'GET',
                    beforeSend: function() {
                        jQuery(this).closest('.cmc-preloader').show();
                    },
                    success: function(history) {
                    if(history===null){
                        $(".cmc-wrp").hide();
                        $(".cmc_coin_details").after('<h3>API\'s does not have any chart data</h3>');  
                      }else{
                      jQuery.each(history.price, function(i, value) {
                             priceData.push(value);
                        });
                      volData=history.volume;
                    }
                        setTimeout(function() {
                            gernateChart(coinId,priceData,chart_color,volData);
                            jQuery(this).closest('.cmc-preloader').hide();
                        }, 500);
                    }
                });
        
        });
         
        var gernateChart = function(coin,priceData,color,volData) {
            // Create the chart
            Highcharts.stockChart('CMC-CHART-'+coin, {
                
                rangeSelector: {
                    allButtonsEnabled: true,
                    buttons: [
                    {
                        type: 'day',
                        count: 1,
                        text: '1d'
                    }, 
                    {
                        type: 'week',
                        count: 1,
                        text: '1w'
                    }, 
                    {
                        type: 'month',
                        count: 1,
                        text: '1m'
                    },
                    {
                        type: 'month',
                        count:3,
                        text: '3m'
                    },
                    {
                        type: 'month',
                        count:6,
                        text: '6m'
                    },
                    {
                        type: 'year',
                        count: 1,
                        text: '1y'
                    }
                    ],
                    selected:1
                },
                    
           xAxis : {
            minRange: 3600 * 1000 // one hour
            },

           yAxis: [{
              labels: {
                align: 'right',
                x: -3
              },
              title: {
                text: 'Price(USD)'
              },
               labels: {
                        formatter: function () {
                            return '$'+this.value ;
                             }
                        },
              height: '60%',
              lineWidth: 2,
              resize: {
                enabled: true
              }
            }, {
              labels: {
                align: 'right',
                x: -3
              },
              title: {
                text: 'Volume(USD)'
              },
              top: '65%',
              height: '35%',
              offset: 0,
              lineWidth: 2
            }],

            tooltip: {
              split: true
            },
         responsive: {
                        rules: [{
                            condition: {
                                maxWidth: 500
                            },
                            chartOptions: {
                                chart: {
                                    height: 300
                                },
                                subtitle: {
                                    text: null
                                },
                                navigator: {
                                    enabled: false
                                }
                            }
                        }]
                    },
                    
        series: [
            {
            name: 'Price(USD)',
            data: priceData,
            color:color,
            type: 'line',
            marker: {
            enabled: true,
            radius:6
            },
            tooltip: {
            valueDecimals: 2
            }
            },
             {
          type: 'column',
          name: 'Volume(USD)',
          data: volData,
          color:color,
          yAxis: 1,
            tooltip: {
            valueDecimals: 2
            }
        }]
            }); 
        }
        
})(jQuery);