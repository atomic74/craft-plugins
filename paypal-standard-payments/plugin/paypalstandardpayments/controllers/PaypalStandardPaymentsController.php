<?php
namespace Craft;

class PaypalStandardPaymentsController extends BaseController
{

  protected $allowAnonymous = array('actionProcessOrder');

  public function actionProcessOrder()
  {
    $this->requireAjaxRequest();
    $pluginSettings = craft()->plugins->getPlugin('paypalstandardpayments')->getSettings();
    $testMode = $pluginSettings['testEnabled'];

    $settings = craft()->request->getPost('settings');
    $fields = craft()->request->getPost('fields');
    $amounts = craft()->request->getPost('amounts');

    $formHandle = $settings['formHandle'];
    $recipients = $settings['notificationRecipients'];
    $subject = $settings['notificationSubject'];
    $orderId = date('Ymd').'-'.substr(md5($recipients.time()),0,8);

    $orderData = array(
      'formHandle' => $formHandle,
      'recipients' => $recipients,
      'subject' => $subject,
      'orderId' => $orderId,
      'fields' => $fields,
      'amounts' => $amounts
    );

    // Save the payment record in the CMS
    $orderModel = new PaypalStandardPayments_OrderModel();

    $orderModel->handle = $formHandle;
    $orderModel->paymentType = $settings['paymentType'];
    $orderModel->orderId = $orderId;
    $orderModel->orderTotal = $settings['orderTotal'];
    $orderModel->recipients = $testMode ? '[-- test --], '.$recipients : $recipients;
    $orderModel->subject = $subject;
    $orderModel->purchaserName = craft()->paypalStandardPayments->buildPurchaserName($fields);
    $orderModel->purchaserEmail = craft()->paypalStandardPayments->buildPurchaserEmail($fields);
    $orderModel->content = serialize($orderData);

    craft()->paypalStandardPayments->addOrder($orderModel);

    // Send out the email notification
    $notificationResult = craft()->paypalStandardPayments->sendNotification($orderData, $testMode);

    // return the response in JSON format
    $this->returnJson($notificationResult);
  }

  public function actionDelete()
  {
    $orderId = craft()->request->getRequiredParam('orderId');
    craft()->paypalStandardPayments->deleteOrder($orderId);
    craft()->userSession->setNotice('Order was deleted.');
    $this->redirect('paypalstandardpayments');
  }

  public function actionDeleteAll()
  {
    $handle = craft()->request->getParam('handle');
    $deletedOrdersCount = craft()->paypalStandardPayments->deleteAll($handle);
    craft()->userSession->setNotice("All {$deletedOrdersCount} orders were deleted.");
    $this->redirect($this->buildRedirectUrl($handle));
  }

  public function actionDeleteStale()
  {
    $handle = craft()->request->getParam('handle');
    $deletedStaleOrdersCount = craft()->paypalStandardPayments->deleteStale($handle);
    craft()->userSession->setNotice("{$deletedStaleOrdersCount} orders older than 3 months were successfully deleted.");
    $this->redirect($this->buildRedirectUrl($handle));
  }

  protected function buildRedirectUrl($handle)
  {
    if (empty($handle))
    {
      return 'paypalstandardpayments';
    }
    else
    {
      return UrlHelper::getCpUrl() . '/paypalstandardpayments?handle=' . urlencode($handle);
    }
  }
}
