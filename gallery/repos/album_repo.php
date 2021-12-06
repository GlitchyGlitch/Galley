<?php
require_once __DIR__ . '../../models/album.php';
require_once __DIR__ . '../../crypto.php';
require_once __DIR__ . '../../exceptions.php';


class AlbumRepository
{
  private $dbh;

  public function __construct($dbh)
  {
    $this->dbh = $dbh;
  }

  public function get_all($limit = 50, $offset = 0)
  {
    $query = 'SELECT * FROM albums ORDER BY created_at DESC LIMIT :offset, :limit'; //FIXME: returns empty
    try {
      $sth = $this->dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
      $sth->execute([
        ':limit' => $limit,
        ':offset' => $offset,
      ]);
      $result = $sth->fetchAll(PDO::FETCH_ASSOC);
      $album_list = new AlbumList();
      $album_list->array_load($result);
      return $album_list;
    } catch (PDOException $e) {
      exit($e->getMessage()); //TODO: Handle errors properly
    }
  }

  public function insert($album) //TODO: Change it to transaction. Handle errors. Handle UNIQUE on name
  {
    $album->id = gen_uuidv4();

    // TODO: validate data
    $insert_query = 'INSERT INTO albums (id, name, owner_id) VALUES (uuid_to_bin(:id), :name, uuid_to_bin(:owner_id))';
    try {
      $sth = $this->dbh->prepare($insert_query);
      $sth->execute([
        ':id' => $album->id,
        ':name' => $album->name,
        ':owner_id' => $album->owner_id,
      ]);
    } catch (PDOException $e) {
      if ($e->getCode() === "23000") {
        throw new ExistsException();
      }
      // TODO: Consider throwing internal at this point. Maybe error handler callback wrapper on api endpoints
    }

    $select_query = 'SELECT * FROM albums WHERE id = uuid_to_bin(:id);';

    $sth = $this->dbh->prepare($select_query);
    $sth->execute([
      ':id' => $album->id,
    ]);
    $album_result_array = $sth->fetchAll(PDO::FETCH_ASSOC)[0];
    $album_result = new Album();
    $album_result->array_load($album_result_array);
    return $album_result;
  }
}
