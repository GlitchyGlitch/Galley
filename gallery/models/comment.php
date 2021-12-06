<?php

require_once "primitives.php";

class Comment extends Model
{
  public $id;
  public $content;
  public $owner_id;
  public $photo_id;
  public $created_at;
}

class CommentList extends ModelList
{
  public function __construct()
  {
    parent::set_type("Comment");
  }
}
