<?PHP 

include_once "Base.php";

class Auth{
    public $db;
    function __construct(){
        $database = new Database();
        $this->db = $database->connect();
    }

    function login(){
        if(empty($_POST['username'])) Base::returnError("Insira seu UserName para prosseguir com o login");
        $user = $_POST['username'];

        if(empty($_POST['password'])) Base::returnError("Insira sua Senha para prosseguir com o login");
        $pass = $_POST['password'];

        $variables = array(
            "user" => $user,
            "pass" => $pass
        );
        $query = "SELECT * FROM users WHERE user = :user AND pass = :pass";
        try{
            $exec = $this->db->prepare($query);
            $exec->execute($variables);
        }catch(PDOException $Exception){
            Log::error(__METHOD__, $query, $variables);
            print_r($Exception);
            Base::returnError("Nao foi possivel efetuar o login");
        }

        $item = $exec->fetchObject();
        if(!$item){
            Log::error(__METHOD__, $query, serialize($variables));
            Base::returnError("Credenciais incorretas");
        }
        
        session_start();

        $userData['user'] = $item->user;
        $userData['access_level'] = $item->access_level;
        $userData['id'] = $item->id;
        Log::access(__METHOD__, $query, $variables, $item->id);
        Base::returnSuccess("Login efetuado com sucesso", $userData);        
        die();
    }

    function checkToken($tokenId){
        if(empty($tokenId['chave'])) Base::returnError("Requisicao invalida");
        $variables['chave'] = $tokenId['chave'];

        $query = "SELECT * FROM tokens WHERE id = :chave";

        try{
            $check = $this->db->prepare($query);
            $check->execute($variables);
        }catch(PDOException $Exception){
            Log::error(__METHOD__, $query, $variables);
            Base::returnError("Nao foi possivel encotrar o token");
        }

        $check = $check->fetchObject();
        if(!$check) Base::returnError("Chave incorreta, token nao existe");

        $retorno = array("TYPE" => 'SUCCESS', "TOKEN" => $check->access_token, "USER_ID" => $check->user_id);
        echo json_encode($retorno);
        die();        
    }
}

$user = new Auth();
if(isset($_GET['function'])){
    $func = $_GET['function'];
    switch($func){
        case 'checkToken':
            $user->checkToken($_GET);
        break;
        default:
            $user->login();
        break;
    }
}else{
    $user->login();
}

