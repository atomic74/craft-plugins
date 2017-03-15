<?php
namespace Craft;

class PaypalStandardPaymentsService extends BaseApplicationComponent
{
  public function getCriteria(array $attributes=array())
  {
    return craft()->elements->getCriteria('Entry', $attributes);
  }

  public function getHandles()
  {
    $orderRecord = new PaypalStandardPayments_OrderRecord();

    $handles = $orderRecord->findAll(array(
      'select' => 't.handle',
      'order' => 't.handle',
      'distinct' => true
    ));

    return $handles;
  }

  public function getStaleOrdersCount($handle)
  {
    $orderRecord = new PaypalStandardPayments_OrderRecord();

    if (empty($handle))
    {
      $staleOrdersCount = $orderRecord->count(array(
        'condition' => 'dateCreated < :deleteLimit',
        'params' => array(':deleteLimit' => date('Y-m-d', strtotime('-3 months')))
      ));
    }
    else
    {
      $staleOrdersCount = $orderRecord->count(array(
        'condition' => 'dateCreated < :deleteLimit AND handle=:handle',
        'params' => array(':deleteLimit' => date('Y-m-d', strtotime('-3 months')), ':handle' => $handle)
      ));
    }

    return $staleOrdersCount;
  }

  public function getOrders($handle)
  {
    $orderRecord = new PaypalStandardPayments_OrderRecord();

    if (empty($handle)) {
      $orderRecords = $orderRecord->findAll(array('order' => 'dateCreated desc'));
    } else {
      $orderRecords = $orderRecord->findAll(array(
        'condition' => 'handle=:handle',
        'params' => array(':handle' => $handle),
        'order' => 'dateCreated desc'
      ));
    }

    if ($orderRecords)
    {
      $orderModels = PaypalStandardPayments_OrderModel::populateModels($orderRecords);
      return $orderModels;
    }

    return false;
  }

  public function getOrder($orderId)
  {
    $orderModel = new PaypalStandardPayments_OrderModel();

    $orderRecord = PaypalStandardPayments_OrderRecord::model()->findByAttributes(array('id' => $orderId));

    if ($orderRecord)
    {
      $orderModel = PaypalStandardPayments_OrderModel::populateModel($orderRecord);
    }

    return $orderModel;
  }

  public function addOrder($orderModel)
  {
    $orderRecord = new PaypalStandardPayments_OrderRecord();

    $orderRecord->handle = $orderModel->handle;
    $orderRecord->paymentType = $orderModel->paymentType;
    $orderRecord->orderId = $orderModel->orderId;
    $orderRecord->orderTotal = $orderModel->orderTotal;
    $orderRecord->recipients = $orderModel->recipients;
    $orderRecord->subject = $orderModel->subject;
    $orderRecord->purchaserName = $orderModel->purchaserName;
    $orderRecord->purchaserEmail = $orderModel->purchaserEmail;
    $orderRecord->content = $orderModel->content;

    $orderRecord->save(false);

    return true;
  }

  public function deleteOrder($orderId)
  {
    $orderRecord = PaypalStandardPayments_OrderRecord::model()->findByAttributes(array('id' => $orderId));

    if ($orderRecord)
    {
      $orderRecord->delete();
    }
  }

  public function deleteAll($handle)
  {
    $orderRecord = new PaypalStandardPayments_OrderRecord();;

    if (empty($handle))
    {
      $deletedOrdersCount = $orderRecord->deleteAll();
    }
    else
    {
      $deletedOrdersCount = $orderRecord->deleteAll(array(
        'condition' => 'handle=:handle',
        'params' => array(':handle' => $handle))
      );
    }

    return $deletedOrdersCount;
  }

  public function deleteStale($handle)
  {
    $orderRecord = new PaypalStandardPayments_OrderRecord();

    if (empty($handle))
    {
      $deletedStaleOrdersCount = $orderRecord->deleteAll(array(
        'condition' => 'dateCreated < :deleteLimit',
        'params' => array(':deleteLimit' => date('Y-m-d', strtotime('-3 months'))))
      );
    }
    else
    {
      $deletedStaleOrdersCount = $orderRecord->deleteAll(array(
        'condition' => 'dateCreated < :deleteLimit AND handle=:handle',
        'params' => array(':deleteLimit' => date('Y-m-d', strtotime('-3 months')), ':handle' => $handle))
      );
    }

    return $deletedStaleOrdersCount;
  }

  public function sendNotification($orderData, $testMode)
  {
    $errorMessage = '';

    $formHandle = $orderData['formHandle'];
    $recipients = $orderData['recipients'];
    $subject = $orderData['subject'];
    $orderId = $orderData['orderId'];

    // Build the email message
    $message = new EmailModel();
    $message->subject = $subject;

    // Create the html version of the content
    $message->htmlBody = $this->renderEmailContent($orderData);

    // If testMode mode is ON, return the form information on screen
    if ($testMode)
    {
      $honeypot_test = ($this->validateHoneypot('your-birthday-is')) ? "PASSED" : "FAILED";
      $honeypot_test = "PASSED";

      $templateContents = array(
        'recipients' => $recipients,
        'subject' => $subject,
        'honeypot'  => $honeypot_test,
        'content' => $message->htmlBody,
      );

      $responseContent = $this->renderPluginTemplate('test-mode', $templateContents);

      return array(
        'message' => 'preview',
        'orderId' => $orderId,
        'content' => $responseContent
      );
    }
    else
    {
      // Only actually send it if the honeypot test was valid
      if ($this->validateHoneypot('your-birthday-is'))
      {
        // Send email message to each recipient individually
        foreach (explode(',',$recipients) as $recipient)
        {
          try
          {
            $message->toEmail = trim($recipient);
            if (!craft()->email->sendEmail($message))
            {
              PaypalStandardPaymentsPlugin::log("Failed to send notification email for {$formHandle} form.", LogLevel::Error);
              $errorMessage = $errorMessage."Failed to send notification email for {$formHandle} form.\n";
            }
          }
          catch (\Exception $e)
          {
            PaypalStandardPaymentsPlugin::log("Failed to send notification email for {$formHandle} form. Reason: ".$e->getMessage(), LogLevel::Error);
            $errorMessage = $errorMessage."Failed to send notification email for {$formHandle} form. Reason: ".$e->getMessage()."\n";
          }
        }
      }
      else
      {
        PaypalStandardPaymentsPlugin::log("Honeypot validation failed. Most likely a Spambot tired to submit the {$formHandle} form.", LogLevel::Info);
        $errorMessage = $errorMessage."Honeypot validation failed. Most likely a Spambot tired to submit the {$formHandle} form.\n";
      }

      // Return the Verdict
      return array(
        'message' => 'success',
        'orderId' => $orderId,
        'errorMessage' => empty($errorMessage) ? 'no errors' : $errorMessage
      );
    }
  }

  public function renderEmailContent($orderData)
  {
    $formHandle = $orderData['formHandle'];

    $templateDir = 'email/';
    $defaultTemplate = $templateDir.'default-payment-notification';
    $template = $templateDir.$formHandle.'-payment-notification';

    // If custom email template does not exist use the default template
    if (!craft()->templates->doesTemplateExist($template))
    {
      $template = $defaultTemplate;
    }

    return craft()->templates->render($template, $orderData);
  }

  public function renderPluginTemplate($templateName, $templateContents = array())
  {
    $siteTemplatesPath = craft()->path->getTemplatesPath();
    $pluginTemplatesPath = craft()->path->getPluginsPath().'paypalstandardpayments/templates';
    craft()->path->setTemplatesPath($pluginTemplatesPath);
    $renderedTemplate = craft()->templates->render($templateName, $templateContents);
    craft()->path->setTemplatesPath($siteTemplatesPath);

    return $renderedTemplate;
  }

  public function buildPurchaserName($fields)
  {
    $purchaserName = '';

    if (array_key_exists('full_name', $fields)) {
      $purchaserName = $fields['full_name']['value'];
    }

    if (empty($purchaserName)) {
      $purchaserName = $fields['first_name']['value'] . ' ' . $fields['last_name']['value'];
    }

    return $purchaserName;
  }

  public function buildPurchaserEmail($fields) {
    $purchaserEmail = '';

    if (array_key_exists('email', $fields)) {
      $purchaserEmail = $fields['email']['value'];
    }

    if (empty($purchaserEmail)) {
      $purchaserEmail = $fields['email_address']['value'];
    }

    return $purchaserEmail;
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
