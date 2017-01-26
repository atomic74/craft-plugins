<?php
namespace Craft;

class TungstenPlugin extends BasePlugin
{
  function getName()
  {
     return Craft::t('Tungsten');
  }

  function getVersion()
  {
    return '1.1';
  }

  function getDeveloper()
  {
    return 'Tungsten Creative';
  }

  function getDeveloperUrl()
  {
    return 'http://atomic74.com';
  }

  protected function defineSettings()
  {
    return array(
      'tcgTrainingVideosUrl' => array(AttributeType::String, 'default' => ''),
      'tcgNotes' => array(AttributeType::Mixed, 'default' => '')
    );
  }

  public function getSettingsHtml()
  {
    return craft()->templates->render('tungsten/settings', array(
      'settings' => $this->getSettings()
    ));
  }
}
