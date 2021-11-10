<?php
class UserRepository
{
  private $dbh;

  public function __construct($dbh)
  {
    $this->dbh = $dbh;
  }

  public function get_role($id)
  {
    $query = 'SELECT role FROM users WHERE id = uuid_to_bin(:id)';
    try {
      $sth = $this->dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
      $sth->execute([':id' => $id]);
      $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      exit($e->getMessage());
    }
    if (!isset($result[0]['role'])) {
      return;
    }
    return $result[0]['role'];
  }

  public function insert($photo)
  {
    $query = 'INSERT INTO photos (path, owner) VALUES (:owner, :path)';
  }
}
