<?php
namespace Craft;

class GrowDoughController extends BaseController
{

  protected $allowAnonymous = true;

  /**
   * Add donation item to growDoughItems session variable
   *
   * @return void
   **/
  public function actionAddDonationItem()
  {
    $itemId = craft()->request->getRequiredParam('itemId');
    $itemTitle = craft()->request->getRequiredParam('itemTitle');
    $itemAttributes = craft()->request->getRequiredParam('itemAttributes');
    craft()->growDough->addDonationItem($itemId, $itemTitle, $itemAttributes);
    $this->redirect($this->redirectUrl(craft()->request->getParam('redirectUrl')));
  }

  /**
   * Remove donation item from growDoughItems session variable
   *
   * @return void
   **/
  public function actionRemoveDonationItem()
  {
    $itemId = craft()->request->getRequiredParam('itemId');
    craft()->growDough->removeDonationItem($itemId);
    $this->redirect($this->redirectUrl(craft()->request->getParam('redirectUrl')));
  }

  /**
   * Remove all donation items from growDoughItems session variable
   *
   * Expects an AJAX request
   *
   * @return string JSON formatted string including the 'item_count' representing the number of deleted items
   **/
  public function actionRemoveAllDonationItems()
  {
    $this->requireAjaxRequest();
    $removedDonationItemsCount = craft()->growDough->removeAllDonationItems();
    $this->returnJson(array(
      'item_count' => $removedDonationItemsCount
    ));
  }

  /**
   * Redirect to a provided redirectUrl. If the URL is not provided, redirect to origin page instead.
   *
   * @param string The provided redirectUrl if exists.
   * @return string The URL to redirect to
   **/
  protected function redirectUrl($redirectUrl='')
  {
    return $redirectUrl ? $redirectUrl : craft()->request->urlReferrer;
  }
}
