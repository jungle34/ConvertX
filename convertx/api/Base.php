<?PHP 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json; charset=UTF-8');

include_once "Database.php";
include_once "Log.php";

class Base{

    public static function returnError($msg){
        echo json_encode(array("TYPE" => 'ERROR', "MSG" => $msg));
        die();
    }

    public static function returnSuccess($msg, $data = false){
        echo json_encode(array("TYPE" => 'SUCCESS', "MSG" => $msg, "DADOS" => $data));
        die();
    }

    public static function checkAcess(){
        $database = new Database();
        $db = $database->connect();

        $token = apache_request_headers()['Authorization'];
        
        $variables = array(
            "key" => $token
        );

        $query = "SELECT * FROM tokens WHERE token = :key";

        try{
            $check = $db->prepare($query);
            $check->execute($variables);
        }catch(PDOException $Exception){
            Base::returnError("RUNTIME ERROR");
        }

        $user = $check->fetchObject();

        if(!$user){
            Base::returnError("Authorization header is invalid");
        }else{
            return $user;
        }

    }

    public static function checkToken($id){
        $database = new Database();
        $db = $database->connect();

        if(empty($id)) Base::returnError("Informe a chave para consultar o access_token");

        $variables['chave'] = $id;
        $query = "SELECT * FROM tokens WHERE id = :chave LIMIT 1";

        try{
            $check = $db->prepare($query);
            $check->execute($variables);
        }catch(PDOException $Exception){
            Log::error(__METHOD__, $query, $variables);
            Base::returnError("Nao foi possivel recuperar o seu token de acesso");
        }

        $check = $check->fetchObject();
        if(!$check) Base::returnError("Nao foi encontrado token para este usuario");

        return $check;
    }
    
}