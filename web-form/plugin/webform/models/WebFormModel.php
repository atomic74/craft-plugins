<?php
namespace Craft;

class WebFormModel extends BaseModel
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
      'recipients' => AttributeType::String,
      'subject' => AttributeType::String,
      'content' => array(AttributeType::String, 'column' => ColumnType::Text),
      'dateCreated' => AttributeType::DateTime,
    );
  }
}
