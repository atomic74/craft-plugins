<?php
namespace Craft;

class WebFormService extends BaseApplicationComponent
{
  public function getCriteria(array $attributes=array())
  {
    return craft()->elements->getCriteria('Entry', $attributes);
  }

  public function getFormHandles()
  {
    $webFormRecord = new WebFormRecord();

    $webFormHandles = $webFormRecord->findAll(array(
      'select' => 't.handle',
      'order' => 't.handle',
      'distinct' => true
    ));

    return $webFormHandles;
  }

  public function getStaleWebFormsCount($formHandle)
  {
    $webFormRecord = new WebFormRecord();

    if (empty($formHandle))
    {
      $staleWebFormsCount = $webFormRecord->count(array(
        'condition' => 'dateCreated < :deleteLimit',
        'params' => array(':deleteLimit' => date('Y-m-d', strtotime('-3 months')))
      ));
    }
    else
    {
      $staleWebFormsCount = $webFormRecord->count(array(
        'condition' => 'dateCreated < :deleteLimit AND handle=:handle',
        'params' => array(':deleteLimit' => date('Y-m-d', strtotime('-3 months')), ':handle' => $formHandle)
      ));
    }

    return $staleWebFormsCount;
  }

  public function getWebForms($formHandle)
  {
    $webFormRecord = new WebFormRecord();

    if (empty($formHandle)) {
      $webFormRecords = $webFormRecord->findAll(array('order' => 'dateCreated desc'));
    } else {
      $webFormRecords = $webFormRecord->findAll(array(
        'condition' => 'handle=:handle',
        'params' => array(':handle' => $formHandle),
        'order' => 'dateCreated desc'
      ));
    }

    if ($webFormRecords)
    {
      $webFormModels = WebFormModel::populateModels($webFormRecords);
      return $webFormModels;
    }

    return false;
  }

  public function getWebForm($formId)
  {
    $webFormModel = new WebFormModel();

    $webFormRecord = WebFormRecord::model()->findByAttributes(array('id' => $formId));

    if ($webFormRecord)
    {
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

    if ($webFormRecord)
    {
      $webFormRecord->delete();
    }
  }

  public function deleteAll($formHandle)
  {
    $webFormRecord = new WebFormRecord();;

    if (empty($formHandle))
    {
      $deletedWebFormsCount = $webFormRecord->deleteAll();
    }
    else
    {
      $deletedWebFormsCount = $webFormRecord->deleteAll(array(
        'condition' => 'handle=:handle',
        'params' => array(':handle' => $formHandle))
      );
    }

    return $deletedWebFormsCount;
  }

  public function deleteStale($formHandle)
  {
    $webFormRecord = new WebFormRecord();

    if (empty($formHandle))
    {
      $deletedStaleWebFormsCount = $webFormRecord->deleteAll(array(
        'condition' => 'dateCreated < :deleteLimit',
        'params' => array(':deleteLimit' => date('Y-m-d', strtotime('-3 months'))))
      );
    }
    else
    {
      $deletedStaleWebFormsCount = $webFormRecord->deleteAll(array(
        'condition' => 'dateCreated < :deleteLimit AND handle=:handle',
        'params' => array(':deleteLimit' => date('Y-m-d', strtotime('-3 months')), ':handle' => $formHandle))
      );
    }

    return $deletedStaleWebFormsCount;
  }
}
