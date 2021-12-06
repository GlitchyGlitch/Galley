<?php

require_once "primitives.php";

class Photo extends Model
{
  public $id;
  public $created_at;
  public $path;
  public $owner_id;
  public $album_id;
  public $mime;
  public $base64_img;
}

class PhotoList extends ModelList // TODO: Add validation
{
  public function __construct()
  {
    parent::set_type("Photo");
  }
}
