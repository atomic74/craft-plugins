$(document).ready(function(){
  $("input[name*='fields[payment_option]']").change(function() {
    var total = parseFloat($(this).data('amount'));

    $('#payment-total').html(total.toFixed(2));
    $('.payment-total-amount').val(total.toFixed(2));
    $('.js-order-total').val(total.toFixed(2));
  });
  $('#fields_payment_method').change(function() {
    if ($(this).val() != 'Credit Card') {
      $('.js-payment-type').val('offline')
    }
    else {
      $('.js-payment-type').val('online')
    }
  });
});
