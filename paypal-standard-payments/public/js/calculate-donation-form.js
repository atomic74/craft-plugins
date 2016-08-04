$(document).ready(function(){
  $('#amounts_enclosed_gift').change(function() {
    var total = 0.0;
    var enclosed_gift = parseFloat($('#amounts_enclosed_gift').val());

    if(isNaN(enclosed_gift)) enclosed_gift = 0;

    total = enclosed_gift;
    $('#donation-total').html(total.toFixed(2));
    $('.payment-total-amount').val(total.toFixed(2));
  });
  $('#fields_payment_option').change(function() {
    if ($(this).val() != 'Credit Card') {
      $('.payment-type').val('offline')
    }
    else {
      $('.payment-type').val('online')
    }
  });
});
