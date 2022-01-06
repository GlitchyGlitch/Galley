<?php
require_once "primitives.php";

class UserInput extends Model
{
  public $first_name;
  public $last_name;
  public $email;
  public $password;
  public $role;
}

class User extends Model // FIXME: Validation for user
{
  public $id;
  public $first_name;
  public $last_name;
  public $email;
  public $passwd_hash;
  public $role;
}

class UserList extends ModelList
{
  public function __construct()
  {
    parent::set_type("User");
  }
}
