// Custom validator to make sure that the registration amount is not $0.00
function registrationAmountValid() {
  return parseFloat($('.js-order-total').val()) > 0;
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
    if (registrationAmountValid()) {
      $('#processing-submission').show();
      $('#paypal-payment-form').hide();
      paypalStandardPayments.processOrder(e.target);
    }
    else {
      $('#validation-error').text('Please make sure to include your donation amount.');
      $('#validation-error').show();
      $('#paypal-payment-form').data('formValidation').resetForm();
    };
  });
});
