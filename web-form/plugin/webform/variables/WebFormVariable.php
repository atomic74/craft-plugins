<?php namespace Craft;

class WebFormVariable
{

  /**
   * Render the form tag and hidden fields.
   *
   * @param array Array structure with required and optional values for form tag and hidden fields
   *
   * {{ craft.webForm.formTag({
   *   'entryId': entry.id,
   *   'captchaLanguage': 'en',
   *   'captchaTimeout': '120',
   *   'captchaMessage': 'Please complete the captcha above before submitting this form'
   * }) }}
   *
   * @return string Opening form tag with hidden fields.
   **/
  public function formTag($options = array())
  {
    // Minimum requirements
    if (!array_key_exists('entryId', $options) || !$options['entryId'])
      return "The Entry ID must be provided.";
    else
      $entry_id = $options['entryId'];

    // Set defaults for optional settings
    if (!array_key_exists('captchaLanguage', $options) || !$options['captchaLanguage']) $options['captchaLanguage'] = 'en';

    if (!array_key_exists('captchaTimeout', $options) || !$options['captchaTimeout']) $options['captchaTimeout'] = '120';

    if (!array_key_exists('captchaMessage', $options) || !$options['captchaMessage']) $options['captchaMessage'] = 'Please complete the captcha above before submitting this form';

    // Add Captcha to form if enabled
    $entry = craft()->entries->getEntryById($entry_id);
    $captchaEnabled = ($entry->captcha == 1) ? true : false;
    $pluginSettings = craft()->plugins->getPlugin('webform')->getSettings();
    $captchaSiteKey = $pluginSettings->captchaSiteKey;
    $captchaTheme = $pluginSettings->captchaTheme;

    // Render the form tag template
    $siteTemplatesPath = craft()->path->getTemplatesPath();
    $pluginTemplatesPath = craft()->path->getPluginsPath().'webform/templates';
    craft()->path->setTemplatesPath($pluginTemplatesPath);
    $formTag = craft()->templates->render('form-tag', array(
      'entryId' => $entry_id,
      'captchaEnabled' => $captchaEnabled,
      'captchaSiteKey' => $captchaSiteKey,
      'captchaTheme' => $captchaTheme,
      'captchaLanguage' => $options['captchaLanguage'],
      'captchaTimeout' => $options['captchaTimeout'],
      'captchaMessage' => $options['captchaMessage']
    ));
    craft()->path->setTemplatesPath($siteTemplatesPath);

    return TemplateHelper::getRaw($formTag);
  }

  /**
   * Return all Web Forms
   *
   **/
  public function getWebForms()
  {
    return craft()->webForm->getWebForms();
  }

  /**
   * Return a scpecific Web Form
   *
   **/
  public function getWebForm()
  {
    $formId = craft()->request->getRequiredParam('formId');

    return craft()->webForm->getWebForm($formId);
  }
}
