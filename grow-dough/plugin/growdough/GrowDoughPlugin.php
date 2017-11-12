<?php
namespace Craft;

class GrowDoughPlugin extends BasePlugin
{
  function getName()
  {
    return Craft::t('GrowDough');
  }

  function getVersion()
  {
    return '1.1.0';
  }

  function getDeveloper()
  {
    return 'Tungsten Creative';
  }

  function getDeveloperUrl()
  {
    return 'http://atomic74.com';
  }

  public function getDocumentationUrl()
  {
    return "https://github.com/ohlincik/craft-plugins/tree/master/grow-dough";
  }

  public function getReleaseFeedUrl()
  {
    return 'https://tcg-craft.s3.amazonaws.com/plugins/grow-dough/releases.json';
  }

  protected function defineSettings()
  {
    return array(
      'growDoughDonationsUrl' => array(AttributeType::String, 'default' => ''),
      'testModeEnabled' => array(AttributeType::Bool, 'default' => true)
    );
  }

  public function getSettingsHtml()
  {
    return craft()->templates->render('growdough/settings', array(
      'settings' => $this->getSettings()
    ));
  }
}
