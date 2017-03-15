$(document).ready(function(){
  $('.fee-input, .contribution-input').change(function() {
    fees = $(':input.fee-input');
    var subtotal = 0.0;
    var total = 0.0;
    var contribution = parseFloat($('.contribution-input').val());
    var handling_charge = 3.0;

    for(var i=0; i<fees.length; i++){
      qty = parseInt(fees.get(i).value);
      price = parseFloat($('#'+fees.get(i).id+'_amount').data('amount'));

      if(isNaN(qty)) qty = 0;

      subtotal += qty*price;
    }
    if(isNaN(contribution)) contribution = 0;
    total = subtotal + contribution + handling_charge;
    $('#payment-subtotal').html(subtotal.toFixed(2));
    $('.payment-subtotal-amount').val(subtotal.toFixed(2));
    $('#payment-total').html(total.toFixed(2));
    $('.payment-total-amount').val(total.toFixed(2));
    $('.js-order-total').val(total.toFixed(2));
  });
  $('#fields_payment_options').change(function() {
    if ($(this).val() != 'Credit Card') {
      $('.js-payment-type').val('offline')
    }
    else {
      $('.js-payment-type').val('online')
    }
  });
});
