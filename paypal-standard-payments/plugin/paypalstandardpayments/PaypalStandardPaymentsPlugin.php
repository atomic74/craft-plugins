<?php
namespace Craft;

class PaypalStandardPaymentsPlugin extends BasePlugin
{

  public function getName()
  {
    return 'Paypal Standard Payments';
  }

  public function getVersion()
  {
    return '1.0.0';
  }

  public function getDeveloper()
  {
    return 'Tungsten Creative';
  }

  public function getDeveloperUrl()
  {
    return 'http://atomic74.com';
  }

  public function getDocumentationUrl()
  {
    return "http://topshelfcraft.com/calendars";
  }

  protected function defineSettings()
  {
    return array(
      'testEnabled' => array(AttributeType::Bool, 'default' => true)
    );
  }

  public function getSettingsHtml()
  {
    return craft()->templates->render('paypalstandardpayments/_settings', array(
      'settings' => $this->getSettings()
    ));
  }

}
