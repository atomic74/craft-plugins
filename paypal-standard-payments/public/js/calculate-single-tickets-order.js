$(document).ready(function(){
  $('.fee-input').change(function() {
    fees = $(':input.fee-input');
    var total = 0.0;

    for(var i=0; i<fees.length; i++){
      qty = parseInt(fees.get(i).value);
      price = parseFloat($('#'+fees.get(i).id+'_amount').data('amount'));

      if(isNaN(qty)) qty = 0;

      total += qty*price;
    }
    $('#payment-total').html(total.toFixed(2));
    $('.payment-total-amount').val(total.toFixed(2));
  });
});
