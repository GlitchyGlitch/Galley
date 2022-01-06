<?php
require_once __DIR__ . '../../models/user.php';

define("BCRYPT_COST", 16); //TODO: move to config module later on

class UserRepository
{
  private $dbh;

  public function __construct($dbh)
  {
    $this->dbh = $dbh;
  }

  public function get_all($limit = 50, $offset = 0)
  {
    $query = 'SELECT (id, first_name, last_name, email, role) FROM users ORDER BY id DESC LIMIT :offset, :limit'; //FIXME: returns empty
    try {
      $sth = $this->dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
      $sth->execute([
        ':limit' => $limit,
        ':offset' => $offset,
      ]);
      $result = $sth->fetchAll(PDO::FETCH_ASSOC);
      $user_list = new UserList();
      $user_list->array_load($result);
      return $user_list;
    } catch (PDOException $e) {
      exit($e->getMessage()); //TODO: Hendle errors properly
    }
  }

  public function get_by_id($id, $extended = false)
  {
    if ($extended) {
      $query = 'SELECT (id, first_name, last_name, email, role) FROM users WHERE id = uuid_to_bin(:id)';
    } else {
      $query = 'SELECT * FROM users WHERE id = uuid_to_bin(:id)'; // FIXME: get only id first and last name
    }

    try {
      $sth = $this->dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
      $sth->execute([':id' => $id]);
      $result = $sth->fetchAll(PDO::FETCH_ASSOC);
      $user = new User();
      $user->array_load($result[0]);
      return $user;
    } catch (PDOException $e) {
      exit($e->getMessage()); //TODO: Hendle errors properly
    }
  }

  public function insert($user_input)
  {
    $id = gen_uuidv4();
    $passwd_hash = password_hash($user_input->password, PASSWORD_BCRYPT, ['cost' => BCRYPT_COST]);
    $insert_query = 'INSERT INTO users (id, email, first_name, last_name, passwd_hash, role) VALUES (uuid_to_bin(:id), :email, :first_name, :last_name, :passwd_hash, :role)';
    try {
      $sth = $this->dbh->prepare($insert_query);
      $sth->execute([
        ':id' => $id,
        ':passwd_hash' => $passwd_hash,
        ':first_name' => $user_input->first_name,
        ':last_name' => $user_input->last_name,
        ':email' => $user_input->email,
        ':role' => $user_input->role,
      ]);
    } catch (PDOException $e) {
      if ($e->getCode() === "23000") {
        throw new ExistsException();
      }
      // TODO: Consider throwing internal at this point. Maybe error handler callback wrapper on api endpoints
    }

    $select_query = 'SELECT id, first_name, last_name, email, role FROM users WHERE id = uuid_to_bin(:id);'; // TODO: Handle errors
    $sth = $this->dbh->prepare($select_query);
    $sth->execute([
      ':id' => $id,
    ]);
    $user_result_array = $sth->fetchAll(PDO::FETCH_ASSOC)[0];
    $user_result = new User();
    $user_result->array_load($user_result_array);
    return $user_result;
  }
}
