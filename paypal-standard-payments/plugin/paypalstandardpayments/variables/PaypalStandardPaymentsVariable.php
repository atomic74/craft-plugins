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
    if (!array_key_exists('formHandle', $options) || !$options['formHandle']) {
      $options['formHandle'] = 'online-order';
    }
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

    // Include the Form Validation CSS
    craft()->templates->includeCssResource('paypalstandardpayments/css/formValidation.min.css');

    // Include the Plugin JS
    craft()->templates->includeJsResource('paypalstandardpayments/js/paypalStandardPayments.js');

    // Include the Form Validation JS
    craft()->templates->includeJsResource('paypalstandardpayments/js/formValidation.min.js');
    craft()->templates->includeJsResource('paypalstandardpayments/js/bootstrapFormValidation.min.js');

    // If CSRF protection is enabled, include JavaScript
    $this->_csrf();

    $templateContents = array(
      'formAction' => $formAction,
      'paymentType' => trim($options['paymentType']),
      'paymentEmail' => trim($options['paymentEmail']),
      'notificationSubject' => $options['notificationSubject'],
      'returnUrl' => $options['returnUrl'],
      'cancelUrl' => $options['cancelUrl'],
      'offlineUrl' => $options['offlineUrl'],
      'formHandle' => trim($options['formHandle']),
      'notificationRecipients' => trim($options['notificationRecipients']),
      'notificationSubject' => trim($options['notificationSubject'])
    );

    // Render the form tag template
    return TemplateHelper::getRaw(
      craft()->paypalStandardPayments->renderPluginTemplate('form-tag', $templateContents)
    );
  }

  // Render the Paypal Sandbox alert if plugin is in test mode
  public function sandboxAlert()
  {
    $pluginSettings = craft()->plugins->getPlugin('paypalstandardpayments')->getSettings();

    if ($pluginSettings['testEnabled']) {

      return TemplateHelper::getRaw(
        craft()->paypalStandardPayments->renderPluginTemplate('sandbox-alert')
      );
    }
  }

  /**
   * Return Orders
   *
   * This could either be ALL orders or orders associated with a specific
   * handle. The handle is extracted from query or post params.
   *
   **/
  public function getOrders()
  {
    $handle = craft()->request->getParam('handle');
    return craft()->paypalStandardPayments->getOrders($handle);
  }

  /**
   * Return a specific Order
   *
   **/
  public function getOrder()
  {
    $orderId = craft()->request->getRequiredParam('orderId');
    return craft()->paypalStandardPayments->getOrder($orderId);
  }

  public function getOrderForPrint()
  {
    $order = $this->getOrder();
    return array(
      'submitted' => $order->dateCreated,
      'content' => unserialize($order->content)
    );
  }

  public function renderOrderContent($orderContent)
  {
    $orderData = unserialize($orderContent);
    return craft()->paypalStandardPayments->renderOrderContent($orderData);
  }

  /**
   * Return a count of the stale orders (older than 3 months)
   *
   **/
  public function getStaleOrdersCount($handle) {
    return craft()->paypalStandardPayments->getStaleOrdersCount($handle);
  }

  /**
   * Return the list of unique handles in the DB
   *
   * This is used to populate the filter drop-down
   *
   **/
  public function getHandles()
  {
    return craft()->paypalStandardPayments->getHandles();
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
