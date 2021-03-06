  (function($) {
    'use strict';
     
      $(document).ready(function() {
        var $cmc_table = $("#coin-market-cap-widget");
        $cmc_table.DataTable({
            info: $cmc_table.data('info'),
            paging: $cmc_table.data('paging'),
            scrollX: $cmc_table.data('scrollx'),
            ordering: $cmc_table.data('ordering'),
            searching: $cmc_table.data('searching'),
            pageLength:$cmc_table.data('pagelength'),
            responsive:true,
        
            scrollCollapse: true,
            fixedColumns: {
             leftColumns: 2
           },
            lengthMenu: [[10, 25, 50,100,200, -1], [10, 25, 50,100,200, "All"]],
    
    });
    
    var scrollWidth = document.getElementsByClassName("dataTables_scrollHeadInner")[0].style.width;

    $(".top-scroll").width(scrollWidth);
    
    $(".top-scroll-wrapper").scroll(function(){
      $(".dataTables_scrollBody").scrollLeft($(".top-scroll-wrapper").scrollLeft());
    });
    $(".dataTables_scrollBody").scroll(function(){
      $(".top-scroll-wrapper").scrollLeft($(".dataTables_scrollBody").scrollLeft());
    });
    
    if ($(".top-scroll").width() <= $(".top-scroll-wrapper").width()) {
      $('.top-scroll-wrapper').css('display','none');
        }
    else {
      $('.top-scroll-wrapper').css('display','inline-block');
    }
    
    
    var tableHeight = document.getElementById("coin-market-cap-widget").clientHeight;
    $('.DTFC_LeftBodyLiner').css('height',tableHeight);
    $('.DTFC_LeftBodyLiner').css('max-height',tableHeight);
    $('.DTFC_LeftBodyLiner .table').css('height',tableHeight);
    $('.DTFC_LeftBodyWrapper').css('height',tableHeight); 

    });   

 var content=$("#cmc_search_html").html();
var search_data = JSON.parse(content);

var source = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  local: search_data
});

source.initialize();

$('#custom-templates .typeahead').typeahead(null, {
  name: 'matched-links',
  displayKey: 'name',
  source: source.ttAdapter(),
  templates: {
    empty: [
      '<div class="empty-message">',
      'Unable to find any result',
      '</div>'
    ].join('\n'),
  suggestion: Handlebars.compile(document.getElementById("search_temp").innerHTML)
  }
});
 

$(".cmc_conversions").on("change",function(){
      var selected_curr=$(this).val();
      var all_curr_list =JSON.parse($('#cmc_curr_list').html());
      var currency_slug=selected_curr.toLowerCase();
      var c_index=selected_curr.toString();
      var c_symbol=all_curr_list[c_index];
      var p_index=currency_slug+'_price';
      var cap_index=currency_slug+'_cap';
      var vol_index=currency_slug+'_vol';
  
   $(".cmc-datatable").not('.DTFC_Cloned').find('tr td.cmc_price_section').each(function (index, value){
         var coin_json=$(this).attr("data-coin-json");
         var coin_id=$(this).attr("data-coin-symbol");
         var coin_raw_data=JSON.parse(coin_json);
         var coin_price=c_symbol+coin_raw_data[p_index];
         var coin_cap=c_symbol+coin_raw_data[cap_index];
         var coin_vol=c_symbol+coin_raw_data[vol_index];
          $(this).find('.cmc-price').html(coin_price);
          $(this).parent('tr').find('.cmc_live_cap').html(coin_cap); 
          $(this).parent('tr').find('.cmc_live_vol').html(coin_vol); 
    });

});

$(".cmc-sparkline-charts").each(function(index){
    var coin_symbol=$(this).data('coin-symbol');
    var cache=$(this).data('cache');
    var color=$(this).data('color');
    var bgcolor=$(this).data('bg-color');
    var sparklineCon=$(this);
      var time=100*index;
  
  if(cache==true){
      var historical_data=$(this).data('content');
  
        sparklineCon.sparkline(eval(historical_data),{
              width:"100%",
              height:"40px",
              lineColor:color,
               fillColor:bgcolor,
              tooltipPrefix: '$'
            }); 
     
    }else{
       setTimeout( function(time){ generatechart(coin_symbol,sparklineCon,color,bgcolor); }, time);
    }
});
   
   function generatechart(symbol,ele,color,bgcolor){
   var request_data = {
      'action': 'cmc_small_charts',
      'symbol':symbol
    };
     jQuery.ajax({
         type : "post",
         dataType : "json",
          beforeSend: function() {
           ele.parent().find(".cmc-small-preloader").show();
           },
         url :ajax_object.ajax_url,
         data :request_data,
         success: function(response) {
            if(response.type == "success") {
              ele.sparkline(eval(response.data),{
                      width:"100%",
                      height:"40px",
                      lineColor:color,
                      fillColor:bgcolor,
                        tooltipPrefix: '$'
                    }); 
              ele.parent().find(".cmc-small-preloader").hide();          
            }
            else {
               ele.html(ele.data('msz'));
               ele.parent().find(".cmc-small-preloader").hide();  
            }
         }
      }) ; 
  
   }
   
})(jQuery);