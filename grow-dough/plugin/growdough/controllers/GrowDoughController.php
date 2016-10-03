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
   * Add remove item to growDoughItems session variable
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
