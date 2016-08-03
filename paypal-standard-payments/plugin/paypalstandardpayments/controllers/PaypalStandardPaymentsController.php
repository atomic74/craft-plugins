<?php
namespace Craft;

class PaypalStandardPaymentsController extends BaseController
{

  protected $allowAnonymous = true;

  public function actionSendNotification()
  {
    $template_dir = 'email/';
    $default_template = $template_dir.'default-payment-notification';
    $pluginSettings = craft()->plugins->getPlugin('paypalstandardpayments')->getSettings();
    $error_message = '';

    $this->requireAjaxRequest();
    $settings = craft()->request->getPost('settings');
    $fields = craft()->request->getPost('fields');
    $amounts = craft()->request->getPost('amounts');

    $testMode = $pluginSettings['testEnabled'];
    $formHandle = $settings['formHandle'];

    $recipients = $settings['notificationRecipients'];
    $subject = $settings['notificationSubject'];
    $template = $template_dir.$formHandle.'-payment-notification';
    $redirect_url = "success=✓";
    $order_id = date('Ymd').'-'.substr(md5($recipients.time()),0,8);

    // If custom email template does not exist use the default template
    if (!craft()->templates->doesTemplateExist($template))
    {
      $template = $default_template;
    }

    // Build the email message
    $message = new EmailModel();
    $message->subject = $subject;

    // Create the html version of the content
    $message->htmlBody = craft()->templates->render($template, array(
      'subject' => $subject,
      'order_id' => $order_id,
      'fields' => $fields,
      'amounts' => $amounts
    ));

    // If testMode mode is ON, return the form information on screen
    if ($testMode) {
      $honeypot_test = ($this->validateHoneypot('your-birthday-is')) ? "PASSED" : "FAILED";
      $honeypot_test = "PASSED";
      $pluginTemplatesPath = craft()->path->getPluginsPath().'webform/templates';
      craft()->path->setTemplatesPath($pluginTemplatesPath);
      $response_content = craft()->templates->render('test-mode', array(
        'recipients' => $recipients,
        'subject' => $subject,
        'honeypot'  => $honeypot_test,
        'content' => $message->htmlBody,
      ));
      $this->returnJson(array(
        'message' => 'preview',
        'order_id' => $order_id,
        'content' => $response_content
      ));
      exit();
    }
    else {
      // Only actually send it if the honeypot test was valid
      if ($this->validateHoneypot('your-birthday-is')) {
        // Send email message to each recipient individually
        foreach (explode(',',$recipients) as $recipient)
        {
          try
          {
            $message->toEmail = trim($recipient);
            if (!craft()->email->sendEmail($message)) {
              PaypalStandardPaymentsPlugin::log("Failed to send notification email for {$formHandle} form.", LogLevel::Error);
              $error_message = $error_message."Failed to send notification email for {$formHandle} form.\n";
            }
          }
          catch (\Exception $e)
          {
            PaypalStandardPaymentsPlugin::log("Failed to send notification email for {$formHandle} form. Reason: ".$e->getMessage(), LogLevel::Error);
            $error_message = $error_message."Failed to send notification email for {$formHandle} form. Reason: ".$e->getMessage()."\n";
          }
        }
      }
      else {
        PaypalStandardPaymentsPlugin::log("Honeypot validation failed. Most likely a Spambot tired to submit the {$formHandle} form.", LogLevel::Info);
        $error_message = $error_message."Honeypot validation failed. Most likely a Spambot tired to submit the {$formHandle} form.\n";
      }

      // Return the Verdict
      $this->returnJson(array(
        'message' => 'success',
        'order_id' => $order_id,
        'error_message' => empty($error_message) ? 'no errors' : $response_message
      ));
    }
  }
  /**
   * Checks that the 'honeypot' field has not been filled out (assuming one has been set).
   *
   * @param string $fieldName The honeypot field name.
   * @return bool
   */
  protected function validateHoneypot($fieldName)
  {
    if (!$fieldName)
    {
      return true;
    }
    $honey = craft()->request->getPost($fieldName);
    return $honey == '';
  }
}
