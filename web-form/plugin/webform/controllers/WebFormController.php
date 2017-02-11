<?php
namespace Craft;

class WebFormController extends BaseController
{
  protected $allowAnonymous = true;

  public function actionSendNotification()
  {
    $template_dir = 'email/';
    $default_template = $template_dir.'default-notification';

    $this->requirePostRequest();
    $entry_id = craft()->request->getPost('entry_id');
    $fields = craft()->request->getPost('fields');

    $entry = craft()->entries->getEntryById($entry_id);
    $testMode = ($entry->testMode == 1) ? true : false;
    $saveInCms = ($entry->saveInCms == 1) ? true : false;
    $formHandle = $entry->formHandle;

    $recipients = $entry->notificationRecipients;
    $subject = $entry->notificationSubject;
    $template = $template_dir.$formHandle.'-notification';
    $redirect_url = $entry->url."/?success=✓";

    // If custom email template does not exist use the default template
    if (!craft()->templates->doesTemplateExist($template))
    {
      $template = $default_template;
    }

    // Build the email message
    $message = new EmailModel();
    $message->subject = $subject;

    // Populate the Reply To setting if it exists
    $replyTo = craft()->templates->renderString($entry->notificationReplyTo, $fields);
    $replyTo = empty($replyTo) ? false : $replyTo;

    // Create the html version of the content
    $message->htmlBody = craft()->templates->render($template, array(
      'subject' => $subject,
      'fields'  => $fields
    ));

    // Save the form in the CMS if the setting is enabled
    if ($saveInCms) {
      $webFormModel = new WebFormModel();

      $webFormModel->handle = $formHandle;
      $webFormModel->recipients = $testMode ? '[-- test --], '.$recipients : $recipients;
      $webFormModel->subject = craft()->templates->renderString($entry->formSubject, $fields);
      $webFormModel->content = $message->htmlBody;

      craft()->webForm->addWebForm($webFormModel);
    }

    // If testMode mode is ON, display the form information on screen
    if ($testMode) {
      $pluginTemplatesPath = craft()->path->getPluginsPath().'webform/templates';
      craft()->path->setTemplatesPath($pluginTemplatesPath);
      echo craft()->templates->render('test-mode', array(
        'recipients' => $recipients,
        'subject' => $subject,
        'replyTo' => $replyTo ? $replyTo : '-- NOT SET --',
        'content' => $message->htmlBody
      ));
      exit();
    }
    else {
      // Send email message to each recipient individually
      foreach (explode(',',$recipients) as $recipient)
      {
        try
        {
          $message->toEmail = trim($recipient);
          if ($replyTo) {
            $message->replyTo = $replyTo;
          }
          if (!craft()->email->sendEmail($message)) {
            WebFormPlugin::log("Failed to send email for {$formHandle} form.", LogLevel::Error);
          }
        }
        catch (\Exception $e)
        {
          WebFormPlugin::log("Failed to send email for {$formHandle} form. Reason: ".$e->getMessage(), LogLevel::Error);
        }
      }

      // Redirect back to the form page
      $this->redirect($redirect_url);
    }
  }

  public function actionDelete()
  {
    $formId = craft()->request->getRequiredParam('formId');
    craft()->webForm->deleteWebForm($formId);
    craft()->userSession->setNotice('Form entry was deleted.');
    $this->redirect('webform');
  }

  public function actionDeleteAll()
  {
    craft()->webForm->deleteAll();
    craft()->userSession->setNotice('All form entries were deleted.');
    $this->redirect('webform');
  }
}
