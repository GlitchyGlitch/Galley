<?php
require_once "primitives.php";

class LoginInput extends Model
{
  public $email;
  public $password;
}
class Login extends Model
{
  public $jwt;
}
