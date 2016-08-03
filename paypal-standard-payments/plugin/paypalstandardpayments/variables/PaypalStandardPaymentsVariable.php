<?php
namespace Craft;

class PaypalStandardPaymentsVariable
{

  private $_csrfIncluded = false;

  // Render form and hidden fields
  public function formTagWithHiddenFields($options = array())
  {
    // Minimum requirements
    if (!array_key_exists('paymentEmail', $options) || !$options['paymentEmail']) {
      return "No payment email provided.";
    }

    $pluginSettings = craft()->plugins->getPlugin('paypalstandardpayments')->getSettings();

    if ($pluginSettings['testEnabled']) {
      $formAction = "https://www.sandbox.paypal.com/cgi-bin/webscr";
      $options['paymentEmail'] = 'test-merchant@thenewline.com';
    } else {
      $formAction = "https://www.paypal.com/cgi-bin/webscr";
    }

    // Provide default values if missing
    if (!array_key_exists('notificationRecipients', $options) || !$options['notificationRecipients']) {
      $options['notificationRecipients'] = $options['paymentEmail'];
    }
    if (!array_key_exists('paymentType', $options) || !$options['paymentType']) {
      $options['paymentType'] = '_xclick';
    }
    if (!array_key_exists('notificationSubject', $options) || !$options['notificationSubject']) {
      $options['notificationSubject'] = 'Online Order';
    }
    if (!array_key_exists('returnUrl', $options) || !$options['returnUrl']) {
      $options['returnUrl'] = craft()->getSiteUrl();
    }
    if (!array_key_exists('cancelUrl', $options) || !$options['cancelUrl']) {
      $options['cancelUrl'] = craft()->getSiteUrl();
    }
    if (!array_key_exists('offlineUrl', $options) || !$options['offlineUrl']) {
      $options['offlineUrl'] = craft()->getSiteUrl();
    }

    // JS
    craft()->templates->includeJsResource('paypalstandardpayments/js/paypalstandardpayment.js');
    $this->_csrf();

    return TemplateHelper::getRaw('
<form role="form" id="paypal-payment-form" method="post" action="'.$formAction.'" accept-charset="UTF-8">
  <input type="hidden" name="cmd" value="'.trim($options['paymentType']).'">
  <input type="hidden" name="lc" value="US">
  <input type="hidden" name="currency_code" value="USD">
  <input type="hidden" name="amount" value="0.00" class="payment-total-amount">
  <input type="hidden" name="business" value="'.trim($options['paymentEmail']).'">
  <input type="hidden" name="item_name" value="'.$options['notificationSubject'].'" class="payment-order-title">
  <input type="hidden" name="invoice" value="order-id" class="payment-order-id">
  <input type="hidden" name="return" value="'.$options['returnUrl'].'">
  <input type="hidden" name="cancel_return" value="'.$options['cancelUrl'].'">
  <input type="hidden" name="offline_return" value="'.$options['offlineUrl'].'">
  <input type="hidden" name="payment_type" value="online" class="payment-type">
  <input type="hidden" name="settings[formHandle]" value="'.trim($options['formHandle']).'">
  <input type="hidden" name="settings[notificationRecipients]" value="'.trim($options['notificationRecipients']).'">
  <input type="hidden" name="settings[notificationSubject]" value="'.trim($options['notificationSubject']).'">
  <input type="text" name="your-birthday-is" id="birthday-form-field">');
  }

  // Render the Paypal Sandbox alert if plugin is in test mode
  public function sandboxAlert()
  {
    $pluginSettings = craft()->plugins->getPlugin('paypalstandardpayments')->getSettings();

    if ($pluginSettings['testEnabled']) {
      return TemplateHelper::getRaw('
<div class="alert alert-danger" role="alert"><strong>In test mode.</strong><br>Go to Paypal Standard Payments plugin settings for instructions and to disable test mode.</div>');
    }
  }

  // If CSRF protection is enabled, include JavaScript
  private function _csrf()
  {
    if (craft()->config->get('enableCsrfProtection') === true) {
      if (!$this->_csrfIncluded) {
        craft()->templates->includeJs('
window.csrfTokenName = "'.craft()->config->get('csrfTokenName').'";
window.csrfTokenValue = "'.craft()->request->getCsrfToken().'";');
        $this->_csrfIncluded = true;
      }
    }
  }
}
