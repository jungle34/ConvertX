<?php

include_once "Base.php";

class Logger{
    public $db;
    public $table = "clickLog";

    function __construct(){
        $database = new Database();
        $this->db = $database->connect();
    }
    
    function logging(){
        $auth = Base::checkAcess();
        if(!$auth) Base::returnError("Invalid token");

        $variables = array();
        foreach($_POST as $field => $val){
            if(!empty($val)){
                $variables[$field] = $val;
            }
        }

        $variables['user'] = $auth->id;

        $query = "INSERT INTO ".$this->table." (".implode(', ', array_keys($variables)).") VALUES (:".implode(', :', array_keys($variables)).")";

        try{
            $logging = $this->db->prepare($query);
            $logging->execute($variables);

            Base::returnSuccess("API log action with success!");
        }catch(PDOException $Exception){
            Base::returnError("EXECUTION ERROR - The API cannot save the click log in database");
        }
        
    }
}

$log = new Logger();
$log->logging();