<?php

include_once "Base.php";

class Access{
    public $db;
    function __construct(){
        $database = new Database();
        $this->db = $database->connect();
    }

    function AccessToken(){
        if(!$_POST['access_token']) Base::returnError("O access_token nao foi gerado");
        $variables = $_POST;
        $variables['userId'] = $_GET['id'];
        $query = "INSERT INTO tokens (".implode(', ', array_keys($variables)).") VALUES (:".implode(', :', array_keys($variables)).")";

        try{
            $tokenInsert = $this->db->prepare($query);
            $tokenInsert->execute($variables);
            $response = array("token_id" => $this->db->lastInsertId());
            Log::access(__METHOD__, $query, $variables, $variables['userId']);
            Base::returnSuccess("Access token gerado e gravado com sucesso", $response);
        }catch(PDOException $Exception){
            Log::error(__METHOD__, $query, $variables);
            Base::returnError("Nao foi possivel gravar o access_token");
        }
        die();
      
        

    }
}


$accessAuth = new Access();
$accessAuth->AccessToken();