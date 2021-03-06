  (function($) {
    'use strict';
     
      $(document).ready(function() {
      	  $('#cmc_crypto_list').select2();
      	  $('#cmc_currencies_list').select2();

      	  $(document).on("change keyup",".cmc_calculate_price",function(){
      	  	 var amount=$("#cmc_amount").val();
			    if(amount==''){
			        amount=10;
			    }

			 var label = $("#cmc_currencies_list option:selected").closest('optgroup').prop('label');
   			 var cryptocurrency=$("#cmc_crypto_list").val();
			 var currency=$("#cmc_currencies_list").val();  
		
      	 	 var coin_name=$("#cmc_crypto_list option:selected").text();
      	 	 var currency_name=$("#cmc_currencies_list option:selected").text();
      	 	  var default_currency=$("#cmc_currencies_list").data('default-currency');
      	 
      	 	  if(label=="Crypto Currencies"){
      	 	  	//10 * (1 BTC Price in USD / 1 ETH Price in USD)
      	 	 	var calculate_price=amount*(parseFloat(cryptocurrency)/ parseFloat(currency));
	      	    var formated_price=calculate_price.formatMoney(2, '.', ',');

      	 	  }else{
    
	      	 	var calculate_price=(parseFloat(cryptocurrency)*amount)*parseFloat(currency);
	      	    var formated_price=calculate_price.formatMoney(2, '.', ',');
      			}

      	 	   $(".cmc_cal_rs").text(formated_price +' '+currency_name);
		       $(".cmc_rs_lbl").text(amount+' '+coin_name);

      	  });

 Number.prototype.formatMoney = function(c, d, t){
		var n = this, 
		    c = isNaN(c = Math.abs(c)) ? 2 : c, 
		    d = d == undefined ? "." : d, 
		    t = t == undefined ? "," : t, 
		    s = n < 0 ? "-" : "", 
		    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
		    j = (j = i.length) > 3 ? j % 3 : 0;
		   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
		 };
      	}); 



})(jQuery);