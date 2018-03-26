<?php
namespace Craft;

class WebFormPlugin extends BasePlugin
{

  public function getName()
  {
    return Craft::t('Web Form');
  }

  public function getVersion()
  {
    return '2.4.0';
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
    return "https://github.com/ohlincik/craft-plugins/tree/master/web-form";
  }

  public function getReleaseFeedUrl()
  {
    return 'https://tcg-craft.s3.amazonaws.com/plugins/web-form/releases.json';
  }

  protected function defineSettings()
  {
    return array(
      'captchaTheme' => array(AttributeType::String, 'default' => 'light'),
      'captchaSiteKey' => array(AttributeType::String, 'default' => 'PROVIDE_REAL_KEY'),
      'captchaSecretKey' => array(AttributeType::String, 'default' => 'PROVIDE_REAL_KEY'),
    );
  }

  public function getSettingsHtml()
  {
    return craft()->templates->render('webform/settings', array(
      'settings' => $this->getSettings()
    ));
  }

  public function hasCpSection()
  {
    return true;
  }
}
