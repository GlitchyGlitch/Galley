<?php

  class Database {
    private $type;
    private $host;
    private $name;
    private $username;
    private $password;
    private $dsn;
    private $dbh;
    
    public function __construct() {
      $this->host = getenv('DB_HOST');
      $this->type = getenv('DB_TYPE');
      $this->name = getenv('DB_NAME');
      $this->username = getenv('DB_USERNAME');
      $this->password = getenv('DB_PASSWORD');
      $this->dsn = "{$this->type}:dbname={$this->name};host={$this->host}";
    }

    public function connect() {
      $this->dbh = null;
      try {
        $this->dbh = new PDO($this->dsn, $this->username, $this->password);
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      } catch (PDOException $e) {
        echo "Connection Error: {$e->getMessage()}";
      }
      return $this->dbh;
    }
  }
?>