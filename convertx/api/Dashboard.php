<?php

include_once "Base.php";

class Dashboard{
    public $db;
    public $table = "clickLog";

    function __construct(){
        $database = new Database();
        $this->db = $database->connect();
    }

    function actionType(){
        $query = "SELECT * FROM clickLog";

        try{
            $search = $this->db->prepare($query);
            $search->execute();
        }catch(PDOException $Exception){
            Log::error(__METHOD__, $query, false);
            Base::returnError("RUNTIME ERROR - API can't search the results in database");
        }

        $data = array(
            array(
                "category" => "Checkout",
                "value" => 0
            ),
            array(
                "category" => "Carrinho",
                "value" => 0
            ),
            array(
                "category" => "Saiba Mais",
                "value" => 0
            ),
            array(
                "category" => "Registre-se agora",
                "value" => 3
            )
        );


        $results = array();
        while($row = $search->fetchObject()){

            switch($row->class){
                case 'checkout':
                    $data[0]['value']++;
                break;
                case 'cart':
                    $data[1]['value']++;
                break;
                case 'getMore':
                    $data[2]['value']++;
                break;
                case 'signup':
                    $data[3]['value']++;
                break;
            }   
            $results[] = $row;
        }

        $response = array("TYPE" => 'SUCCESS', "RESULTS" => $data);
        echo json_encode($response);
        die();
    }

    function clicksPerSite(){
        $auth = Base::checkAcess();
        if(!$auth) Base::returnError("Invalid token");

        $variables['key'] = $auth->id;

        $query = "SELECT * FROM clickLog WHERE user = :key";

        try{
            $search = $this->db->prepare($query);
            $search->execute($variables);
        }catch(PDOException $Exception){
            Base::returnError("RUNTIME ERROR");
        }

        $result = array();
        while($data = $search->fetchObject()){
            $site = str_replace("#", "", $data->locationUrl);
            $result[$site]['val']++;
        }

        $response = array("TYPE" => 'SUCCESS', "RESULTS" => $result);
        echo json_encode($response);
        die();
    }

    function devicesChart(){
        //$auth = Base::checkAcess();
        //if(!$auth) Base::returnError("Invalid token");

        $variables['key'] = 1;

        $query = "SELECT * FROM clickLog WHERE user = :key";

        try{
            $search = $this->db->prepare($query);
            $search->execute($variables);
        }catch(PDOException $Exception){
            Base::returnError("RUNTIME ERROR");
        }

        $results = array(
            array(
                "title" => 'Mobile',
                "value" => 0
            ),
            array(
                "title" => 'Desktop',
                "value" => 0
            )
        );
        while($data = $search->fetchObject()){
            if(strpos(strtolower($data->device), 'android')){
                $results[0]['value']++;
            }else{
                $results[1]['value']++;
            }
        }

        $response = array("TYPE" => 'SUCCESS', "RESULTS" => $results);
        echo json_encode($response);
        die();
    }
}

$dash = new Dashboard();

switch($_GET['function']){
    case 'actionType':
        $dash->actionType();
    break;
    case 'clicksPerSite':
        $dash->clicksPerSite();
    break;
    case 'devicesChart':
        $dash->devicesChart();
}

