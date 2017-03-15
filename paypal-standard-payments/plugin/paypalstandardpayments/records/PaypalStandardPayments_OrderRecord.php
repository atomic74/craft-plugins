<?php
namespace Craft;

class PaypalStandardPayments_OrderRecord extends BaseRecord
{
  public function getTableName()
  {
    return 'paypalstandardpayments';
  }

  public function defineAttributes()
  {
    return array(
      'handle' => array(AttributeType::String, 'required' => true),
      'paymentType' => AttributeType::String,
      'orderId' => AttributeType::String,
      'orderTotal' => array(AttributeType::Number, 'column' => ColumnType::Decimal, 'decimals' => 2),
      'subject' => AttributeType::String,
      'purchaserName' => AttributeType::String,
      'purchaserEmail' => AttributeType::String,
      'recipients' => AttributeType::String,
      'content' => array(AttributeType::String, 'column' => ColumnType::Text)
    );
  }
}
