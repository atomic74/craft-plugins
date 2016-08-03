// Paypal Standard Payment JS
var paypalStandardPayment = {
  sendNotification: function (form) {
    console.log('Sending notification...');
    var data = $(form).serialize();
    if (window.csrfTokenName) {
      console.log('[CSRF protection enabled]');
      data[window.csrfTokenName] = window.csrfTokenValue; // Append CSRF Token
    }
    // Send notification
    $.post('/actions/paypalStandardPayments/sendNotification', data, function(response) {
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
      order_id = response.order_id;
      $('.payment-order-title').val($('input[name="settings[notificationSubject]"]').val() + ' - ' + order_id);
      $('.payment-order-id').val(order_id);

      // Check if the payment should be submitted to Paypal
      if ($('.payment-type').val() == 'online') {
        form.submit();
      }
      else {
        // Redirect to Offline Payment page if not submitting to Paypal
        window.location = $('input[name=offline_return]').val();
      };
    });
  }
}
