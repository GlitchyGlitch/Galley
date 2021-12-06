<?php

require_once "primitives.php";

class Comment extends Model implements ModelInterface
{
  public $id;
  public $content;
  public $owner_id;
  public $photo_id;
  public $created_at;

  public function validate(bool $input_only = false): bool
  {
    if ($input_only && $this->validate_content()) {
      return true;
    }
    if ($this->validate_content() && is_valid_uuid($this->id) && is_valid_uuid($this->owner_id) && is_valid_uuid($this->id)) {
      return true;
    } // TODO: Add created_at validation later
    return false;
  }
  public function validate_content(): bool
  {
    if (!(strlen($this->content) > 510) && !preg_match('/[^\x20-\x7f]/', $this->content)) {
      return true;
    }
    return false;
  }
}

class CommentList extends ModelList
{
  public function __construct()
  {
    parent::set_type("Comment");
  }
}
