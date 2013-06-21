<?php

class db {
    private $host = ""; //to be filled
    private $user = ""; //to be filled
    private $pass = ""; //to be filled
    private $databaseName = ""; //to be filled
    
    public function getHost() {
        return $this->host;
    }

    public function getUser() {
        return $this->user;
    }

    public function getPass() {
        return $this->pass;
    }

    public function getDatabaseName() {
        return $this->databaseName;
    }

    public function getDbTablePrefix() {
        return $this->dbTablePrefix;
    }
    
}

?>
