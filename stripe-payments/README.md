# Stripe Payments plugin for Craft CMS

This plugin allows to process orders through Stripe.

[Release Notes](https://github.com/ohlincik/craft-plugins/tree/master/stripe-payments/docs/release-notes)

- (done) Collect and Submit order information
- (done - needs improved) Process Stripe Payment
- (done - needs adjustments) Store order in Craft DB
  - we should probably store the Email address and EntryId as well
- Send Order Notification Email
  - Email recipient(s) will be retrieved from a field associated with the Entry
  - The email subject will be: "New {{ orderType }} order has been submitted"
  - Need to generate email content
    - The data to populate content template should be an array that looks like this:
      ```
      array(
        'orderId' => ...,
        'entryId' => ...,
        'firstName' => ...,
        'lastName' => ...,
        'email' => ...,
        'orderTotal' => ...,
        'orderType' => ...,
        'dateCreated' => ...,
        'fields' => {fields collection},
        'amounts' => {amounts collection}
      )
      ```
    - The email template will work similar to Paypal Payments Plugin
- Send Order Receipt Email to customer
  - Email recipient will be retrieved from the fields (should we store the email in the order table separately?)
  - Email subject will be retrieved from a field associated with the Entry
    - the subject should be in the form of twig template that can be rendered using the order data
  - Email content will be generated using a template retrieved from a field associated with the Entry and rendered using the order data
- Display Order Receipt Page
  - The content will be retrieved from a field associated with the Entry and rendered using the order data
    - entry->receiptPageContent
  - We will need to create a plugin variable that will require the OrderId.
    - Plugin variable: receiptPageContent

