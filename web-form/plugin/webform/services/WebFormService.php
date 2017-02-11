<?php
namespace Craft;

class WebFormService extends BaseApplicationComponent
{
  public function getCriteria(array $attributes=array())
  {
    return craft()->elements->getCriteria('Entry', $attributes);
  }

  public function getWebForms()
  {
    $webFormRecord = new WebFormRecord();
    $webFormRecords = $webFormRecord->findAll(array('order' => 'dateCreated desc'));

    if ($webFormRecords) {
      $webFormModels = WebFormModel::populateModels($webFormRecords);
      return $webFormModels;
    }

    return false;
  }

  public function getWebForm($formId)
  {
    $webFormModel = new WebFormModel();

    $webFormRecord = WebFormRecord::model()->findByAttributes(array('id' => $formId));

    if ($webFormRecord) {
      $webFormModel = WebFormModel::populateModel($webFormRecord);
    }

    return $webFormModel;
  }

  public function addWebForm($webFormModel)
  {
    $webFormRecord = new WebFormRecord();

    $webFormRecord->handle = $webFormModel->handle;
    $webFormRecord->recipients = $webFormModel->recipients;
    $webFormRecord->subject = $webFormModel->subject;
    $webFormRecord->content = $webFormModel->content;

    $webFormRecord->save(false);

    return true;
  }

  public function deleteWebForm($formId)
  {
    $webFormRecord = WebFormRecord::model()->findByAttributes(array('id' => $formId));

    if ($webFormRecord) {
      $webFormRecord->delete();
    }
  }

  public function deleteAll()
  {
    $webFormRecord = new WebFormRecord();;

    if ($webFormRecord) {
      $webFormRecord->deleteAll();
    }
  }
}
