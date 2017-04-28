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
    return '1.1.2';
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
    return "https://github.com/ohlincik/craft-plugins/tree/master/tungsten";
  }

  public function getReleaseFeedUrl()
  {
    return 'https://tcg-craft.s3.amazonaws.com/plugins/tungsten/releases.json';
  }

  protected function defineSettings()
  {
    return array(
      'tcgShowBootstrapGrid' => array(AttributeType::Bool, 'default' => false),
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

  public function init()
  {
    if (craft()->request->isCpRequest())
    {
      craft()->templates->includeCssResource('tungsten/redactor.css');
    }
  }
}
