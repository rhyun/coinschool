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
             //scrollY:"300px",
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


var links = JSON.parse(coins_links_obj);
var source = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  local: links
});

source.initialize();

var tbl_currency=$('#custom-templates').attr("data-currency");
var old_curr='';
if(tbl_currency!="USD"){
var old_curr=tbl_currency;
}
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
      suggestion: Handlebars.compile('<div class="cmc-search-sugestions"><a href="{{link}}'+old_curr+'"><img src="{{logo}}"> {{name}}</a></div>')
  }
});
   

        
})(jQuery);