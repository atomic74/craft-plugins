<?php
namespace Craft;

class GrowDough_HelpersService extends BaseApplicationComponent
{
  /**
   * Render plugin template and restore the template path to original
   */
  public function renderPluginTemplate($templateName, $templateContents = array())
  {
    $siteTemplatesPath = craft()->path->getTemplatesPath();
    $pluginTemplatesPath = craft()->path->getPluginsPath().'growdough/templates';
    craft()->path->setTemplatesPath($pluginTemplatesPath);
    $renderedTemplate = craft()->templates->render($templateName, $templateContents);
    craft()->path->setTemplatesPath($siteTemplatesPath);

    return $renderedTemplate;
  }
}
