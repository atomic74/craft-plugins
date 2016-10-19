# GrowDough integration plugin for Craft CMS

This plugin allows to collect donation designations in a **Donations List** that works similar to a shopping cart.

## Settings

- **Donations URL:** Stores the full URL to the donations form for the account

## Variables

### getDonationItems

Retrieves the donation items stored in the session as a collection (array). Use the collection in a for loop to build the items list.

    {# Get the Donation Items stored in the session. Returns false if there are no Donation Items #}
    {% set donationItems = craft.growDough.getDonationItems %}

### donationItemInList

Check if the donation item with the provided id is already in the list of donation items.

    {% if craft.growDough.donationItemInList(fund.id) %}
      {# If the item is already in the donation list, show disabled button #}
      <a href="#" class="btn disabled" disabled="disabled">Selected</a>
    {% else %}
      {# Ir the item is NOT in the donation list, show the button to add it to the list. #}
      <input class="btn give-now" type="submit" title="Add to Donation List" value="Give Now">
    {% endif %}

### addDonationItemFormTag

Opening form tag to add a donation item to the donation items list.

#### Params

 - **itemId** Unique id for the donation item
 - **itemTitle** Donation item title that is used for display
 - **itemAttributes** Donation item attributes array that will be stored as JSON
 - **redirectUrl** Optional URL to which the action should redirect after the item is added. _If not included, the browser will redirect to the origin page (using http_referrer)._

#### Example

    {{ craft.growDough.addDonationItemFormTag(
      fund.id,
      fund.title,
      {
        'Donation Type': 'Scholarship Fund',
        'Donating To': 'The Erie Art Museum',
        'Designation': fund.title
      }
    ) }}

### formTag

Opening form tag to submit donation to GrowDough. Includes the GrowDough post URL and all hidden fields required for submission.

#### Options

 - **templateVariables** Include the template variables that should be used in the GrowDough donation workflow (donation form, email receipt, thank you page, etc.) _If not included, an empty JSON array will be submitted._
 - **donationItems** Include an array of donation items to override the items in the donation list. _If not included, an array of donation items will be automatically generated from the donation list._ 
 - **paymentMethod** Include a predefined payment method (**credit\_card** or **giving_card**) if the form is intended to use a pre-determined GrowDough donation form.

#### Most Common Use

The form tag will be most commonly used with templateVariables and paymentType.

    {{ craft.growDough.formTag({
      'templateVariables': {
        'Variable Key': 'Variable Value',
        'Variable Key': 'Variable Value',
        ...
      },
      'paymentMethod': 'credit_card|giving_card'
    }) }}

#### Full Variable Syntax

    {{ craft.growDough.formTag({
      'templateVariables': {
        'Variable Key': 'Variable Value',
        'Variable Key': 'Variable Value',
        ...
      },
      'donationItems': [
        {
          "title": "Item title",
          "attributes": {
            "Attribute Key": "Attribute Value",
            "Attribute Key": "Attribute Value",
            ...
          }
        },
        {
          "title": "Item title",
          "attributes": {
            "Attribute Key": "Attribute Value",
            "Attribute Key": "Attribute Value",
            ...
          }
        }
      ],
      'paymentMethod': 'credit_card|giving_card'
    }) }}

### getDonationItemsJson

Format the donation items list as an encoded JSON string.

    {{ craft.growDough.getDonationItemsJson }}

### donationsUrl

Retrieves the GrowDough donations URL from the plugin settings. The URL is used to post the donation to the GrowDough system for the particular account.

    <form action="{{ craft.growDough.donationsUrl }}" method="post">

### givingCardPurchaseUrl

Retrieves the GrowDough Giving Card purchase URL from the plugin settings. The URL is used to post the desired Giving Card amount to the GrowDough system for the particular account.

    <form action="{{ craft.growDough.givingCardPurchaseUrl }}" method="post">

## Actions

### addDonationItem

Add a specific donation item to the **Donations List**. If an item with the provided _itemId_ already exists in the list, it will **NOT** be added.

#### Example using addDonationItemFormTag variable

    {{ craft.growDough.addDonationItemFormTag(
      fund.id,
      fund.title,
      {
        'Attribute Key': 'Attribute Value',
        'Attribute Key': 'Attribute Value',
        ...
      }
    ) }}
      <h3>{{ fund.title }}</h3>
      <input class="btn give-now" type="submit" title="Add to Donation List" value="Give Now">
    </form>

#### Example using HTML form directly

    {% set itemId = fund.id %}
    {% set itemTitle = fund.title %}
    {% set itemAttributes = {
      'Attribute Key': 'Attribute Value',
      'Attribute Key': 'Attribute Value',
      ...
    } %}
    <form method="post" action="" accept-charset="UTF-8">
      <input type="hidden" name="action" value="growDough/addDonationItem">
      <input type="hidden" name="itemId" value="{{ itemId }}">
      <input type="hidden" name="itemTitle" value="{{ itemTitle }}">
      <input type="hidden" name="itemAttributes" value="{{ itemAttributes|json_encode()|e }}">
      <h3>{{ fund.title }}</h3>
      <input class="btn give-now" type="submit" title="Add to Donation List" value="Give Now">
    </form>

#### Optional Parameter: redirectUrl

If you'd like to redirect to a specific page after the item is added, use the **redirectUrl** parameter.

    {{ craft.growDough.addDonationItemFormTag(
      fund.id,
      fund.title,
      {
        'Attribute Key': 'Attribute Value',
        'Attribute Key': 'Attribute Value',
        ...
      },
      'some/url/here'
    ) }}

OR

    <form method="post" action="" accept-charset="UTF-8">
      <input type="hidden" name="action" value="growDough/addDonationItem">
      <input type="hidden" name="itemId" value="{{ itemId }}">
      <input type="hidden" name="itemTitle" value="{{ itemTitle }}">
      <input type="hidden" name="itemAttributes" value="{{ itemAttributes|json_encode()|e }}">
      <input type="hidden" name="redirectUrl" value="some/url/here">

### removeDonationItem

Remove a specific donation item from the **Donations List**. If an item with the provided _itemId_ does not exist in the list, the action will be ignored.

    <a href="{{ actionUrl('growDough/removeDonationItem', { itemId: itemId }) }}">Remove</i>

_Optional:_ Add a **redirectUrl** parameter if you'd like to redirect to a specific page after the item is added. If the parameter is omitted, the browser will redirect to the origin page (using http_referrer).

    <a href="{{ actionUrl('growDough/removeDonationItem', { itemId: itemId, redirectUrl: 'some/url/here' }) }}">Remove</i>

### removeAllDonationItems

Remove all donation items from growDoughItems session variable. This is useful when checking out a multi-designation donation and the session needs to be remove so that it does not stick around after the donation is complete.

This action needs to be called via AJAX while the GrowDough form is being submitted.

    $.get('/actions/growDough/removeAllDonationItems', { deleteAll: true }, function(response) {
      console.log('Deleted ' + response.item_count + ' donation items.');
    });
