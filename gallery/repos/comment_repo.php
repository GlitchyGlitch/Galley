<?php
require_once __DIR__ . '../../models/comment.php';


class CommentRepository
{
  private $dbh;

  public function __construct($dbh)
  {
    $this->dbh = $dbh;
  }

  public function get_all($limit = 50, $offset = 0)
  {
    $query = 'SELECT * FROM comments ORDER BY created_at DESC LIMIT :offset, :limit'; //FIXME: returns empty
    try {
      $sth = $this->dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
      $sth->execute([
        ':limit' => $limit,
        ':offset' => $offset,
      ]);
      $result = $sth->fetchAll(PDO::FETCH_ASSOC);
      $comments_list = new CommentList();
      $comments_list->array_load($result);
      return $comments_list;
    } catch (PDOException $e) {
      exit($e->getMessage()); //TODO: Hendle errors properly
    }
  }

  public function get_by_photo_id($id, $limit = 50, $offset = 0)
  {
    $query = 'SELECT * FROM comments WHERE photo_id = uuid_to_bin(:id) ORDER BY created_at DESC LIMIT :offset, :limit'; //FIXME: returns empty
    try {
      $sth = $this->dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
      $sth->execute([
        ':id' => $id,
        ':limit' => $limit,
        ':offset' => $offset,
      ]);
      $result = $sth->fetchAll(PDO::FETCH_ASSOC);
      $comments_list = new CommentList();
      $comments_list->array_load($result);
      return $comments_list;
    } catch (PDOException $e) {
      exit($e->getMessage()); //TODO: Hendle errors properly
    }
  }

  public function insert($comment)
  {
    $comment->id = gen_uuidv4();

    $insert_query = 'INSERT INTO comments (id, content, owner_id, photo_id) VALUES (uuid_to_bin(:id), :content, uuid_to_bin(:owner_id), uuid_to_bin(:photo_id))';
    $sth = $this->dbh->prepare($insert_query);
    $sth->execute([
      ':id' => $comment->id,
      ':content' => $comment->content,
      ':owner_id' => $comment->owner_id,
      ':photo_id' => $comment->photo_id,
    ]);

    $select_query = 'SELECT * FROM comments WHERE id = uuid_to_bin(:id);';
    $sth = $this->dbh->prepare($select_query);
    $sth->execute([
      ':id' => $comment->id,
    ]);
    $comment_result_array = $sth->fetchAll(PDO::FETCH_ASSOC)[0];
    $comment_result = new Comment();
    $comment_result->array_load($comment_result_array);
    return $comment_result;
  }
}
