<?php
namespace Craft;

class GrowDoughVariable
{

  /**
   * Provide the donation items to a template.
   *
   * @return array|false The donation items or false if none exist.
   **/
  public function getDonationItems()
  {
    $donationItems = craft()->growDough->getDonationItems();
    if (count($donationItems) > 0) {
      return $donationItems;
    } else {
      return false;
    }
  }

  /**
   * Check if the donation item with the provided id is already in the list of donation items.
   *
   * @param string Id that uniquely identifies the donation item in the list of donation items.
   * @return bool True if the donation item with the id is already in the list, false otherwise.
   **/
  public function donationItemInList($itemId)
  {
    $donationItems = craft()->growDough->getDonationItems();
    return array_key_exists($itemId, $donationItems);
  }

    /**
     * Opening form tag to add a donation item to the donation items list.
     *
     * @param string $itemId Unique id for the donation item
     * @param string $itemTitle Donation item title that is used for display
     * @param array $itemAttributes Donation item attributes that will be stored as JSON
     * @param string $redirectUrl URL to which the action should redirect upon completion (optional)
     *
     * {{ craft.growDough.addDonationItemFormTag(
     *  fund.id,
     *  fund.title,
     *   {
     *     'Attribute Key': 'Attribute Value',
     *     'Attribute Key': 'Attribute Value',
     *     'Attribute Key': fund.title
     *   },
     *   siteUrl
     * )}}
     *
     * @return string Opening form tag with hidden fields.
     **/
    public function addDonationItemFormTag($itemId, $itemTitle, $itemAttributes = array(), $redirectUrl = "")
    {
      $itemAttributesJson = json_encode($itemAttributes);
      if ($redirectUrl) {
        $redirectUrl = "<input type=\"hidden\" name=\"redirectUrl\" value=\"".$redirectUrl."\">";
      }
      return TemplateHelper::getRaw('
  <form method="post" action="" accept-charset="UTF-8">
    <input type="hidden" name="action" value="growDough/addDonationItem">
    '.$redirectUrl.'
    <input type="hidden" name="itemId" value="'.$itemId.'">
    <input type="hidden" name="itemTitle" value="'.htmlspecialchars($itemTitle).'">
    <input type="hidden" name="itemAttributes" value="'.htmlspecialchars($itemAttributesJson).'">');
    }

  /**
   * Opening form tag to submit donation to GrowDough. Includes the GrowDough post URL and hidden fields.
   *
   * @param array $options Array structure with optional values for hidden form tags
   *
   * {{ craft.growDough.formTag({
   *   'templateVariables': {
   *     'Variable Key': 'Variable Value',
   *     'Variable Key': 'Variable Value',
   *     ...
   *   },
   *   'donationItems': [
   *     {
   *      "title": "Item title",
   *      "attributes": {
   *        "Attribute Key": "Attribute Value",
   *        "Attribute Key": "Attribute Value",
   *        ...
   *      }
   *    },
   *    {
   *      "title": "Item title",
   *      "attributes": {
   *        "Attribute Key": "Attribute Value",
   *        "Attribute Key": "Attribute Value",
   *        ...
   *      }
   *    }
   *   ],
   *   'paymentMethod': 'credit_card|giving_card'
   * }) }}
   *
   * @return string Opening form tag with hidden fields.
   **/
  public function formTag($options = array())
  {
    if (!array_key_exists('templateVariables', $options) || !$options['templateVariables']) {
      $options['templateVariables'] = array();
    }

    if (!array_key_exists('donationItems', $options) || !$options['donationItems']) {
      $donationItems = $this->getDonationItemsJson();
    } else {
      $donationItems = json_encode($options['donationItems']);
    }

    if (!array_key_exists('paymentMethod', $options) || !$options['paymentMethod']) {
      $paymentMethod = "";
    }
    else {
      $paymentMethod = "<input type=\"hidden\" id=\"growdough_payment_method\" name=\"payment_method\" value=\"".$options['paymentMethod']."\">";
    }

    $templateVariables = json_encode($options['templateVariables']);

    return TemplateHelper::getRaw('
<form role="form" id="growdough-form" method="post" action="'.$this->donationsUrl().'" accept-charset="UTF-8">
  '.$paymentMethod.'
  <input type="hidden" id="growdough_template_variables" name="template_variables" value="'.htmlspecialchars($templateVariables).'">
  <input type="hidden" id="growdough_donation_items" name="donation_items" value="'.htmlspecialchars($donationItems).'">');
  }

  /**
   * Format the donation items as JSON.
   *
   *  [
   *    {
   *      "title": "Item title",
   *      "attributes": {
   *        "Attribute Key": "Attribute Value",
   *        "Attribute Key": "Attribute Value",
   *        ...
   *      }
   *    },
   *    {
   *      "title": "Item title",
   *      "attributes": {
   *        "Attribute Key": "Attribute Value",
   *        "Attribute Key": "Attribute Value",
   *        ...
   *      }
   *    }
   *  ]
   *
   * @return string JSON formatted donation items.
   **/
  public function getDonationItemsJson()
  {
    $donationItems = craft()->growDough->getDonationItems();
    if (count($donationItems) > 0) {
      $donationItemsArray = [];
      foreach ($donationItems as $donationItem) {
        array_push($donationItemsArray, $donationItem);
      }
      $donationItems = $donationItemsArray;
    }
    return json_encode($donationItems);
  }

  /**
   * Retrieve the GrowDough donation URL and provide to a template.
   *
   * @return string The GrowDough donation URL stored in plugin settings.
   **/
  public function donationsUrl()
  {
    return craft()->plugins->getPlugin('growDough')->getSettings()->growDoughDonationsUrl;
  }

}
