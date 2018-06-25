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
          
            var chartfrom=jQuery(this).data("chart-from");
            var chartto=jQuery(this).data("chart-to");
            var chartzoom=jQuery(this).data("chart-zoom");


            var priceData = [];
              var volData = [];
            var mainThis= jQuery(this);
            var apiUrl = 'https://coincap.io';
             var price_section =jQuery(this).find(".CCP-"+coinId);
          
             jQuery.ajax({
                    url: apiUrl + '/history/'+coinperiod+'/' + coinId,
                    method: 'GET',
                    beforeSend: function() {
                     mainThis.find('.cmc-preloader').show();
                    },
                    success: function(history) {
                    if(history===null){
                        $(".cmc-wrp").hide();
                        $(".cmc-chart").hide().after('<h3>API\'s does not have any chart data</h3>');  
                      }else{
                      jQuery.each(history.price, function(i, value) {
                             
                             priceData.push( {
                              "date":new Date(value[0]),
                              "value":value[1],
                              "volume":history.volume[i][1]
                            } ); 
                        });
                    
                      setTimeout(function() {
                         gernateChart(coinId,priceData,chart_color,chartfrom,chartto,chartzoom);
                            mainThis.find('.cmc-preloader').hide(); 
                        }, 1000);
                    
                    }
                        
                    }
                });
        
        });
         

var gernateChart = function(coinId,coinData,color,chartfrom,chartto,chartzoom) {
   

    var chart = AmCharts.makeChart('CMC-CHART-'+coinId, {
      "type": "stock",
      "theme": "light",
      "hideCredits":true,
      "categoryAxesSettings": {
        "minPeriod": "mm"
      },
      "dataSets": [ {
        "title":"USD",
        "color":color,
        "fieldMappings": [ {
          "fromField": "value",
          "toField": "value"
        }, {
          "fromField": "volume",
          "toField": "volume"
        } ],

        "dataProvider":coinData,
        "categoryField": "date"
      } ],

      "panels": [ {
        "showCategoryAxis": false,
        "title": "Price",
        "percentHeight": 70,

        "stockGraphs": [ {
          "id": "g1",
          "valueField": "value",
          "type": "smoothedLine",
          "lineThickness": 2,
          "bullet": "round",
           "comparable": true,
          "compareField": "value",
          "balloonText": "[[title]]:<b>[[value]]</b>",
          "compareGraphBalloonText": "[[title]]:<b>[[value]]</b>"
        } ],


        "stockLegend": {
          "periodValueTextComparing": "[[percents.value.close]]%",
          "periodValueTextRegular": "[[value.close]]"
        },

         "allLabels": [ {
          "x": 200,
          "y": 115,
          "text": "",
          "align": "center",
          "size": 16
        } ],

      "drawingIconsEnabled": true
      }, {
        "title": "Volume",
        "percentHeight": 30,
        "stockGraphs": [ {
          "valueField": "volume",
          "type": "column",
           "showBalloon": false,
          "cornerRadiusTop": 2,
          "fillAlphas": 1
        } ],

        "stockLegend": {
          "periodValueTextRegular": "[[value.close]]"
        },

      } ],

      "chartScrollbarSettings": {
        "graph": "g1",
        "usePeriod": "10mm",
        "position": "bottom"
      },

      "chartCursorSettings": {
        "valueBalloonsEnabled": true,
        "fullWidth": true,
        "cursorAlpha": 0.1,
        "valueLineBalloonEnabled": true,
        "valueLineEnabled": true,
        "valueLineAlpha": 0.5
      },
     "periodSelector": {
        "position": "top",
		"periodsText":chartzoom,
		"fromText":chartfrom,
		"toText":chartto,
        "periods": [
        {
          "period": "DD",
      
          "count": 1,
          "label": "1D"
        },
        {
          "period": "DD",
          "selected": true,
          "count":7,
          "label": "7D"
        },
         {
          "period": "MM",
         "count": 1,
          "label": "1M"
        }, 
      {
          "period": "MM",
          "count": 3,
          "label": "3M"
        },
          {
          "period": "MM",
          "count":6,
          "label": "6M"
        },
         {
          "period": "MAX",
          "label": "1Y"
        } ]
      },

      "export": {
        "enabled": true,
        "position": "top-right"
      }
    } );

    }

        
})(jQuery);