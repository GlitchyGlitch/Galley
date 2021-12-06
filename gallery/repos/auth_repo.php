<?php

class AuthRepository
{
  private $dbh;

  public function __construct($dbh)
  {
    $this->dbh = $dbh;
  }

  public function authenticate($login_input)
  {
    $query = 'SELECT id, passwd_hash FROM users WHERE email = :email';
    try {
      $sth = $this->dbh->prepare($query, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
      $sth->execute([':email' => $login_input->email]);
      $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return null; //TODO: Error handling
    }
    if (!isset($result[0]["id"]) || !isset($result[0]["passwd_hash"])) {
      return null;
    }
    $id = bin_to_uuid($result[0]["id"]);
    $password_hash = $result[0]["passwd_hash"];
    $password_valid = password_verify($login_input->password, $password_hash);

    if ($password_valid === true) {
      $login = new Login();
      $login->array_load(['jwt' => generate_jwt($id)]); // TODO: Load another secret from subsystem for security reasons
      return $login;
    }
    return null;
  }

  public function get_role($id)
  {
    $query = 'SELECT role FROM users WHERE id = uuid_to_bin(:id)';
    try {
      $sth = $this->dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
      $sth->execute([':id' => $id]);
      $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      exit($e->getMessage()); //TODO: error handling
    }
    if (!isset($result[0]['role'])) {
      return;
    }
    return $result[0]['role'];
  }
}
