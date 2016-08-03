#Paypal Simple Payments plugin for Craft CMS

Use this Twig code:

    {{ craft.paypalStandardPayments.sandboxAlert() }}

and

    {{ craft.paypalStandardPayments.formTagWithHiddenFields({
      'formHandle': 'tickets-order',
      'paymentEmail': paypalStandardPayments.paymentEmail,
      'paymentType': '_xclick' or '_donations',
      'notificationRecipients': paypalStandardPayments.paymentNotificationReceipients,
      'notificationSubject': 'Paypal Payment',
      'returnUrl': paypalStandardPayments.paymentReturnUrl.first().url,
      'cancelUrl': paypalStandardPayments.paymentCancelUrl.first().url,
      'offlineUrl': paypalStandardPayments.offlinePaymentUrl.first().url
    }) }}

Only the `paymentEmail` value is required, all others are optional:

 - **paymentEmail:** Single email address. Will receive PayPal payment. Required.
 - **notificationEmails:** One or more email addresses, separated by commas. Will be notified of pending payment. Optional, defaults to value of "paymentEmail".
 - **donationDesignation:** Will be displayed in PayPal transaction information. Optional, defaults to `General Fund`.
 - **returnUrl:** URL to get back to the site after donating. Optional, defaults to `{{ siteUrl }}`.
 - **cancelUrl:** URL to get back to the site if transaction cancelled. Optional, defaults to `{{ siteUrl }}`.
