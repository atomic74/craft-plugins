<?php
namespace Craft;

class GrowDoughService extends BaseApplicationComponent
{

  /**
   * Get the full list of donation items
   *
   * @return array
   **/
  public function getDonationItems()
  {
    return craft()->httpSession->get('growDoughItems', array());
  }

  /**
   * Add donation item to the growDoughItems session variable.
   *
   * If the item already exists in the array, it will not be added again.
   *
   * @param string $itemName The full name of the donation designation
   * @return void
   **/
  public function addDonationItem($itemId, $itemTitle, $itemAttributes)
  {
    $donationItems = $this->getDonationItems();
    if (array_key_exists($itemId, $donationItems) === false) {
      $donationItems[$itemId]['title'] = $itemTitle;
      $donationItems[$itemId]['attributes'] = $itemAttributes;
    }
    craft()->httpSession->add('growDoughItems', $donationItems);
  }

  /**
   * Remove donation item from the growDoughItems session variable
   *
   * @param string $itemName The full name of the donation designation
   * @return void
   **/
  public function removeDonationItem($itemId)
  {
    $donationItems = $this->getDonationItems();
    if (array_key_exists($itemId, $donationItems) === true) {
      unset($donationItems[$itemId]);
      craft()->httpSession->add('growDoughItems', $donationItems);
    }
  }

}
