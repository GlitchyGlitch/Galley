<?php
require_once __DIR__ . '../../models/rate.php';
require_once __DIR__ . '../../crypto.php';

class RateRepository
{
  private $dbh;

  public function __construct($dbh)
  {
    $this->dbh = $dbh;
  }
  public function get_by_photo_id(string $id, int $limit = 50, int $offset = 0): RateList
  {
    $query = 'SELECT * FROM rates WHERE photo_id = uuid_to_bin(:id) ORDER BY created_at DESC LIMIT :offset, :limit'; //FIXME: returns empty
    try {
      $sth = $this->dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
      $sth->execute([
        ':id' => $id,
        ':limit' => $limit,
        ':offset' => $offset,
      ]);
      $query = 'SELECT * FROM rates WHERE photo_id = uuid_to_bin(:id) ORDER BY created_at DESC LIMIT :offset, :limit'; //FIXME: returns empty
      $result = $sth->fetchAll(PDO::FETCH_ASSOC);
      $rates_list = new RateList();
      $rates_list->array_load($result);
      return $rates_list;
    } catch (PDOException $e) {
      exit($e->getMessage()); //TODO: Hendle errors properly
    }
  }

  public function insert(Rate $rate): Rate
  {
    $check_query = 'SELECT COUNT(id) FROM rates WHERE photo_id = uuid_to_bin(:photo_id) AND owner_id = uuid_to_bin(:owner_id)';
    $sth = $this->dbh->prepare($check_query);
    $sth->execute([
      ':owner_id' => $rate->owner_id,
      ':photo_id' => $rate->photo_id,
    ]);
    $check_result = $sth->fetchAll(PDO::FETCH_ASSOC)[0]['COUNT(id)'];
    if ($check_result !== 0) {
      throw new ExistsException();
    }

    $rate->id = gen_uuidv4();
    $insert_query = 'INSERT INTO rates (id, stars, owner_id, photo_id) VALUES (uuid_to_bin(:id), :stars, uuid_to_bin(:owner_id), uuid_to_bin(:photo_id))';
    try {
      $sth = $this->dbh->prepare($insert_query);
      $sth->execute([
        ':id' => $rate->id,
        ':stars' => $rate->stars,
        ':owner_id' => $rate->owner_id,
        ':photo_id' => $rate->photo_id,
      ]);
    } catch (PDOException $e) {
      if ($e->getCode() === "23000") {
        throw new ExistsException();
      }
      exit($e->getMessage());
      // TODO: Consider throwing internal at this point. Maybe error handler callback wrapper on api endpoints
    }
    $select_query = 'SELECT * FROM rates WHERE id = uuid_to_bin(:id)'; // TODO: Handle errors
    $sth = $this->dbh->prepare($select_query);
    $sth->execute([
      ':id' => $rate->id,
    ]);
    $rate_result_array = $sth->fetchAll(PDO::FETCH_ASSOC)[0];
    $rate_result = new Rate();
    $rate_result->array_load($rate_result_array);
    return $rate_result;
  }
}
