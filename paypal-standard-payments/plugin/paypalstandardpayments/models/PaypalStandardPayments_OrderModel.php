<?php
namespace Craft;

class PaypalStandardPayments_OrderModel extends BaseModel
{
  public function __toString()
  {
    return (string)$this->handle;
  }

  public function defineAttributes()
  {
    return array(
      'id' => AttributeType::Number,
      'handle' => AttributeType::String,
      'paymentType' => AttributeType::String,
      'orderId' => AttributeType::String,
      'orderTotal' => array(AttributeType::Number, 'column' => ColumnType::Decimal, 'decimals' => 2),
      'subject' => AttributeType::String,
      'purchaserName' => AttributeType::String,
      'purchaserEmail' => AttributeType::String,
      'recipients' => AttributeType::String,
      'content' => array(AttributeType::String, 'column' => ColumnType::Text),
      'dateCreated' => AttributeType::DateTime,
    );
  }
}
