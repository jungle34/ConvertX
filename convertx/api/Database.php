<?php

class Database{
    public $user;
    public $host;
    public $pass;
    public $db;

    public function __construct() {
        $this->user = "root";
        $this->host = "localhost";
        $this->pass = "20010506";
        $this->db = "convertx";
    }

    public function connect() {
        try{
            $base = new PDO('mysql:host='.$this->host.';dbname='.$this->db, $this->user, $this->pass);
        }catch(PDOException $Exception){
            print_r($Exception);
            die('Erro ao conectar na base de dados');
        }
        return $base;
    }
}



