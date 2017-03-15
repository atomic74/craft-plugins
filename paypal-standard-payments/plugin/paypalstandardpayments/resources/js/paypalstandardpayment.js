// Paypal Standard Payment JS
var paypalStandardPayment = {
  processOrder: function (form) {
    console.log('Processing order...');
    var data = $(form).serialize();
    if (window.csrfTokenName) {
      console.log('[CSRF protection enabled]');
      data[window.csrfTokenName] = window.csrfTokenValue; // Append CSRF Token
    }
    // Process order
    $.post('/actions/paypalStandardPayments/processOrder', data, function(response) {
      console.log(response.message);

      // Show the email notification content in a new tab if in TEST mode
      if (response.message == 'preview') {
        var win=window.open('about:blank');
        with(win.document)
        {
          open();
          write(response.content);
          close();
        }
      };

      // Retrieve the Order ID from the response and update the Paypal form
      orderId = response.orderId;
      $('.payment-order-title').val($('input[name="settings[notificationSubject]"]').val() + ' - ' + orderId);
      $('.payment-order-id').val(orderId);

      // Check if the payment should be submitted to Paypal
      if ($('.js-payment-type').val() == 'online') {
        form.submit();
      }
      else {
        // Redirect to Offline Payment page if not submitting to Paypal
        window.location = $('input[name=offline_return]').val();
      };
    });
  }
}
