<?php
namespace Craft;

class PaypalStandardPaymentsPlugin extends BasePlugin
{

  public function getName()
  {
    $pluginName = $this->getSettings()->pluginName;
    if (is_string($pluginName) ){
      return $pluginName;
    } else {
      return Craft::t( 'Paypal Standard Payments' );
    }
  }

  public function getVersion()
  {
    return '2.0.0';
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
    return "https://github.com/ohlincik/craft-plugins/tree/master/paypal-standard-payments";
  }

  public function getReleaseFeedUrl()
  {
    return 'https://tcg-craft.s3.amazonaws.com/plugins/paypal-standard-payments/releases.json';
  }

  protected function defineSettings()
  {
    return array(
      'testEnabled' => array(AttributeType::Bool, 'default' => true),
      'pluginName' => array(AttributeType::String, 'default' => 'Paypal Standard Payments')
    );
  }

  public function getSettingsHtml()
  {
    return craft()->templates->render('paypalstandardpayments/_settings', array(
      'settings' => $this->getSettings()
    ));
  }

  public function hasCpSection()
  {
    return true;
  }
}
