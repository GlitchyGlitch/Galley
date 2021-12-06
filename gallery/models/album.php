<?php

require_once __DIR__ . "/primitives.php";
require_once __DIR__ . "/../crypto.php";

class Album extends Model implements ModelInterface
{
  public $id;
  public $name;
  public $owner_id;
  public $created_at;

  public function validate(bool $input_only = false): bool
  {
    if ($input_only && $this->validate_name()) {
      return true;
    }
    if ($this->validate_name() && is_valid_uuid($this->id) && is_valid_uuid($this->owner_id) && is_valid_uuid($this->id)) { // TODO: Add created_at validation later
      return true;
    }
    return false;
  }
  public function validate_name(): bool
  {
    if (!(strlen($this->name) > 255) && !preg_match('/[^\x20-\x7f]/', $this->name)) {
      return true;
    }
    return false;
  }
}

class AlbumList extends ModelList
{
  public function __construct()
  {
    parent::set_type("Album");
  }
}
