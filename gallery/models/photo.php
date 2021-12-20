<?php

require_once "primitives.php";

class Photo extends Model implements ModelInterface
{
  public $id;
  public $path;
  public $mime;
  public $base64_img;
  public $owner_id;
  public $album_id;
  public $created_at;

  public function validate(bool $input_only = false): bool
  {
    if ($input_only && $this->validate_mime() && $this->validate_base64_img()) {
      return true;
    }
    if (
      $this->validate_path()
      && $this->validate_mime()
      && $this->validate_base64_img()
      && is_valid_uuid($this->id)
      && is_valid_uuid($this->owner_id)
      && is_valid_uuid($this->album_id)
    ) {
      return true;
    }
    return false;
  }
  public function validate_path(): bool
  {
    if (!(strlen($this->path) > 255) && preg_match('/\/[a-zA-Z0-9]{1,}\/[a-f0-9]{8}-\b[a-f0-9]{4}-\b[a-f0-9]{4}-\b[a-f0-9]{4}-[a-f0-9]{12}/', $this->path)) {
      return true;
    }
    return false;
  }
  public function validate_mime(): bool // TODO: Chcek with suported list
  {
    if (!(strlen($this->mime) > 255) && preg_match('/image\/[A-Za-z\-\+]{1,}/', $this->mime)) {
      return true;
    }
    return false;
  }
  public function validate_base64_img(): bool
  {
    if (preg_match('/^(?:[A-Za-z0-9+\/]{4})*(?:[A-Za-z0-9+\/]{2}==|[A-Za-z0-9+\/]{3}=|[A-Za-z0-9+\/]{4})$/', $this->base64_img)) {
      return true;
    }
    return false;
  }
}

class PhotoList extends ModelList // TODO: Add validation
{
  public function __construct()
  {
    parent::set_type("Photo");
  }
}
