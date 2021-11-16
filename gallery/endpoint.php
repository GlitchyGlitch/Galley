<?php

require_once 'data/database.php';
require_once 'repos/photos_repo.php';
require_once 'repos/user_repo.php';
require_once 'jwt.php';


class Endpoint
{

  private $dbh;
  public $repos;
  public $get;
  public $body;
  public $resource_id;
  public $user_id;
  public $user_role;


  public function __construct()
  {
    $this->dbh = (new Database())->connect();
    $this->repos = new RepoWrapper($this->dbh);

    $this->get = $_GET;
    $this->resource_id = $this->get['id'] ?? null;
    $this->body = file_get_contents("php://input");

    $user_auth = $this->get_user_auth();
    $this->user_id = $user_auth['id'] ?? null;
    $this->user_role = $user_auth['role'] ?? null;
  }

  public function send_code($code)
  {
    http_response_code($code);
    exit();
  }

  private function get_user_auth()
  {
    $header = get_authorization_header();
    $token = null;

    if (empty($header)) {
      return;
    }

    if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
      $token = $matches[1];
    }

    if (!is_jwt_valid($token)) {
      return;
    }

    $payload = jwt_extract_payload($token);
    $role = $this->repos->userRepo->get_role($payload->sub);
    if ($role === null) {
      return;
    }
    return ['id' => $payload->sub, 'role' => $role];
  }

  public function send($data)
  {
    echo $data;
  }

  public function set_mime($mime)
  {
    header("Content-Type: $mime");
  }

  public function require_user()
  {
    if (is_null($this->user_id) || !($this->user_role === "regular" || $this->user_role === "admin")) {
      $this->send_code(403);
    }
  }
  public function require_admin()
  {
    if ($this->user_id === null && $this->user_role !== 'admin') {
      $this->send_code(403);
    }
  }

  protected function __clone()
  {
  }
}

class RepoWrapper
{
  public $photosRepo;
  public $userRepo;
  public function __construct($dbh)
  {
    $this->photosRepo = new PhotoRepository($dbh);
    $this->userRepo = new UserRepository($dbh);
  }
}
