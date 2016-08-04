# Paypal Standard Payments plugin for Craft CMS

This plugin allows to submit payments to Paypal using the simple Paypal Standard Payments interface.

## Supported Paypal Payment Options

- **Single Payment Amount:** using the *_xclick* option that simulates the 'Buy Now' Paypal Button
- **Donation:** using the *_donations* option

## Recommended Global Fields Setup

It is a good idea to use global fields editable by the end users as defaults for the payment form setup. Create a Globals Section **Paypal Standard Payments** (paypalStandardPayments) and include the following fields in it:

#### Payment Email

- [paymentEmail, Plain Text, required]
- The primary email address associated with the Paypal account that will receive the payments.

#### Payment Notification Recipients

- [paymentNotificationRecipients, Plain Text, required]
- Email addresses of people that should be notified when payment is received

#### Payment Return URL

- [paymentReturnUrl, Entries/Plain Text]
- Web address of the page where the user is redirected upon successfully completing their payments at Paypal

#### Payment Cancel URL

- [paymentCancelUrl, Entries/Plain Text]
- Web address of the page where the user is redirected when canceling the transaction at any time at Paypal

#### Offline Payment URL

- [offlinePaymentUrl, Entries/Plain Text]
- Web address of the page where the user is redirected in case they select a payment option allowing them to submit their payment offline (usually mailing a check).

## Basic Payment Form Setup

The following Twig code will generate the appropriate form tag:

Use this Twig code:

    {# Display an alert when in TEST mode for the Paypal Standard Payments Plugin #}
    {{ craft.paypalStandardPayments.sandboxAlert() }}

    {# Generate the form tag with all hidden input setup fields #}
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

### Options

- **formHandle:** Can be any string without spaces. It is primarily used to idenify whether a custom email notification template has been provided. Defaults to 'online-order'.
- **paymentEmail:** Single email address associated with the Paypal account that will receive the payment. **REQUIRED**
- **paymentType:** Set this to *_xclick* if the form is submitting a payment (simulating the Buy Now button Paypal functionality). Set this to *_donations* if the form is used to submit a donation to a nonprofit.
- **notificationRecipients:** One or more email addresses, separated by commas, that will receive the information collected by the payment form. Defaults to value of `paymentEmail`.
- **notificationSubject:** Will be used as the email subject for the notification email and as the item name for the Paypal payment. Defaults to 'Online Order'.
- **returnUrl:** Web address of the page where the user is redirected upon successfully completing their payments at Paypal. Defaults to `{{ siteUrl }}`.
- **cancelUrl:** Web address of the page where the user is redirected when canceling the transaction at any time at Paypal. Defaults to `{{ siteUrl }}`.
- **offlineUrl:** Web address of the page where the user is redirected in case they select a payment option allowing them to submit their payment offline (usually mailing a check). Defaults to `{{ siteUrl }}`.

## Form Structure

The form is structured to collect 2 distinct pieces of data:

- **fields:** Content collected and included in the email notification. Works just like the *WebForm* plugin.
- **amounts:** Numbers that are passed to the email template and can be used in the **Payment Details** section.

Javascript needs to be used to update the hidden **amount** form field that is used to submit the payment amount to Paypal. This field can be updated using the `payment-total-amount` class.

**Order ID** is generated when the form is submitted and it is returned by the AJAX request. It is then subsequently, through the `payment-order-title` class, used to update the item name that is submitted to Paypal to conclusively match the notification email and Paypal order.

## Offline Payment Option

It is possible to generate the notification email but not submit the payment to Paypal.

Use javascript to set the hidden form input with class `payment-type` to 'offline'. The form will automatically redirect to the **offlineUrl** page specified in form variables.

## TEST setting

Use the **Test Mode** switch to utilize the built-in test features.

When enabled, the emails are not sent but rather rendered to a browser window. You will need to disable the pop-up blocker feature to allow the Plugin to open a new browser tab.

### Testing Instructions

When in test mode, the plugin will automatically submit the payment to a **Paypal Sandbox** where you can run through the entire process of donating.

Run through the following test scenarios:

1. Complete a successful payment.
2. Click the 'return to merchant' link before completing a donation to see the content for *Unsuccessful Payment*
3. Review the payment email content.

Use the following information to complete the test donation:

- **Email:** test-buyer@thenewline.com
- **Password:** otE237Uc
- **Card Number:** 4032035110273471 (Visa)
- **Exp. Date:** 11/2019
- **CVC:** anything

Use the following information to login into the Paypal Sandbox account to review the donation:

- **Login:** [Payapal Sandbox](https://www.sandbox.paypal.com)
- **Email:** test-merchant@thenewline.com
- **Password:** N4dN23kT






































