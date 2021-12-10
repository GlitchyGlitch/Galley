<?php

require_once __DIR__ . "/primitives.php";
require_once __DIR__ . "/../crypto.php";

class Rate extends Model implements ModelInterface
{
  public $id;
  public $stars;
  public $owner_id;
  public $photo_id;
  public $created_at;

  public function validate(bool $input_only = false): bool
  {
    if ($input_only && $this->validate_stars()) {
      return true;
    }
    if ($this->validate_stars() && is_valid_uuid($this->id) && is_valid_uuid($this->owner_id) && is_valid_uuid($this->photo_id)) {
      return true;
    }
    return false;
  }

  public function validate_stars()
  {
    if (is_int($this->stars) && $this->stars > 1 && $this->stars <= 5) {
      return true;
    }
    return false;
  }
}

class RateList extends ModelList
{
  public function __construct()
  {
    parent::set_type("Rate");
  }
}
