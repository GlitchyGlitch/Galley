<?php

require_once "primitives.php";

class Photo extends Model
{
  public $path;
  public $owner;
  public $mime;
  public $base64_img;
}

class PhotoList extends ModelList
{
  public function __construct()
  {
    parent::set_type("Photo");
  }
}
