<?php

require_once "primitives.php";

class Rate extends Model
{
  public $id;
  public $stars;
  public $owner_id;
  public $photo_id;
  public $created_at;
}

class RateList extends ModelList
{
  public function __construct()
  {
    parent::set_type("Rate");
  }
}
