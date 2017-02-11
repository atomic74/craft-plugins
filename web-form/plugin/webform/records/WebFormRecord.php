<?php
namespace Craft;

class WebFormRecord extends BaseRecord
{
  public function getTableName()
  {
    return 'webform';
  }

  public function defineAttributes()
  {
    return array(
      'handle' => array(AttributeType::String, 'required' => true),
      'recipients' => AttributeType::String,
      'subject' => AttributeType::String,
      'content' => array(AttributeType::String, 'column' => ColumnType::Text ),
    );
  }
}
