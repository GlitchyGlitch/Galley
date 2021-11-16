<?php
require_once __DIR__ . '../../models/photo.php';
require_once __DIR__ . '../../crypto.php';

class PhotoRepository
{
  private $dbh;

  public function __construct($dbh)
  {
    $this->dbh = $dbh;
  }

  public function get_all($limit = 50, $offset = 0)
  {
    $query = 'SELECT * FROM photos ORDER BY created_at DESC LIMIT :offset, :limit'; //FIXME: returns empty
    try {
      $sth = $this->dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
      $sth->execute([
        ':limit' => $limit,
        ':offset' => $offset,
      ]);
      $result = $sth->fetchAll(PDO::FETCH_ASSOC);
      $photo_list = new PhotoList();
      $photo_list->array_load($result);
      return $photo_list;
    } catch (PDOException $e) {
      exit($e->getMessage()); //TODO: proper errors
    }
  }

  public function get_img_by_id($id)
  {
    $query = 'SELECT path, mime FROM photos WHERE id=uuid_to_bin(:id)'; //FIXME: returns empty
    try {
      $sth = $this->dbh->prepare($query, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
      $sth->execute([
        ':id' => $id,
      ]);
      $result = $sth->fetchAll(PDO::FETCH_ASSOC);
      $mime = $result[0]['mime'] ?? null;
      $path = $result[0]['path'] ?? null;
      // TODO: Handle errors
      $img = file_get_contents('/var/lib' . $path);
      return [$img, $mime];
    } catch (PDOException $e) {
      exit($e->getMessage()); //TODO: proper errors
    }
  }

  public function insert($photo) //TODO: change it to transaction.
  {
    $photo->id = gen_uuidv4();
    $path = '/img/' . $photo->id;
    $abs_path = '/var/lib' . $path;

    $photo->path = $path;
    $binary_img = base64_decode($photo->base64_img);
    // TODO: validate data
    // Put it into db
    $insert_query = 'INSERT INTO photos (id, path, mime, owner) VALUES (uuid_to_bin(:id), :path, :mime, uuid_to_bin(:owner))';
    $sth = $this->dbh->prepare($insert_query);
    $sth->execute([
      ':id' => $photo->id,
      ':owner' => $photo->owner,
      ':path' => $photo->path,
      ':mime' => $photo->mime,
    ]);
    // Put it into fs.
    if (!$handle = fopen($abs_path, 'wb')) {
      throw new FSWriteException();
    }
    if (fwrite($handle, $binary_img) === false) {
      throw new FSWriteException();
    }

    fclose($handle);

    $select_query = 'SELECT * FROM photos WHERE id = uuid_to_bin(:id);';

    $sth = $this->dbh->prepare($select_query);
    $sth->execute([
      ':id' => $photo->id,
    ]);
    $photo_result_array = $sth->fetchAll(PDO::FETCH_ASSOC)[0];
    $photo_result = new Photo();
    $photo_result->array_load($photo_result_array);

    return $photo_result;
  }
}
