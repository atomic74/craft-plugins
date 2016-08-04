// Custom validator to make sure that the ticket amount is not $0.00
function donationAmountValid() {
  return parseFloat($('#amounts_enclosed_gift').val()) > 0;
}

$(document).ready(function() {
  $('#paypal-payment-form button.submit').click(function() {
    $('#paypal-payment-form').data('formValidation').resetForm();
  });
  $('#paypal-payment-form').formValidation({
    framework: 'bootstrap',
    live: 'disabled',
    autoFocus: false
  })
  .on('err.form.fv', function(e) {
    e.preventDefault();
    $('#validation-error').text('Please correct the errors above before submitting your order.');
    $('#validation-error').show();
  })
  .on('success.form.fv', function(e) {
    e.preventDefault();
    if (donationAmountValid()) {
      $('#processing-submission').show();
      $('#paypal-payment-form').hide();
      paypalStandardPayment.sendNotification(e.target);
    }
    else {
      $('#validation-error').text('Please make sure to include your donation amount.');
      $('#validation-error').show();
      $('#paypal-payment-form').data('formValidation').resetForm();
    };
  });
});
