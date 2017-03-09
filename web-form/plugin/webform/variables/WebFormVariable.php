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
   * Return Web Forms
   *
   * This could either be ALL web forms or web forms associated with a specific
   * form handle. The form handle is extracted from query or post params.
   *
   **/
  public function getWebForms()
  {
    $formHandle = craft()->request->getParam('formHandle');
    return craft()->webForm->getWebForms($formHandle);
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

  /**
   * Return a count of the stale Web Forms (older than 3 months)
   *
   **/
  public function getStaleWebFormsCount($formHandle) {
    return craft()->webForm->getStaleWebFormsCount($formHandle);
  }

  /**
   * Return the list of unique Form Handles in the DB
   *
   * This is used to populate the form filter drop-down
   *
   **/
  public function getFormHandles()
  {
    return craft()->webForm->getFormHandles();
  }
}
