<?php
require_once "primitives.php";

class LoginInput extends Model implements ModelInterface
{
  public $email;
  public $password;
  public function validate(bool $input_only = false): bool
  {
    if ($this->validate_email() && $this->validate_password()) {
      return true;
    }
    return false;
  }
  public function validate_email(): bool
  {
    // TODO: This type of email validation may not be safe, check if it is rfc comatibile
    if (!(strlen($this->email) > 320) && filter_var($this->email, FILTER_VALIDATE_EMAIL) !== false) {
      return true;
    }
    return false;
  }
  public function validate_password(): bool
  {
    if (!(strlen($this->password) > 256) && !preg_match('/[^\x20-\x7f]/', $this->password)) {
      return true;
    }
    return false;
  }
}
class Login extends Model
{
  public $jwt;
}
