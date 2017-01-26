<?php
namespace Craft;

class Tungsten_CmsInfoWidget extends BaseWidget
{
  public function getName()
  {
    return Craft::t('Tungsten Craft Resources');
  }

  public function getBodyHtml()
  {
    return craft()->templates->render('tungsten/widgets/cmsinfo/body', array(
      'settings' => $this->getSettings()
    ));
  }

  public function getSettings()
  {
    return craft()->plugins->getPlugin('tungsten')->getSettings();
  }
}
