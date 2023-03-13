<?PHP 

include_once "Base.php";

class Account{
    public $db;
    function __construct(){
        $database = new Database();
        $this->db = $database->connect();
    }

    function createUserData($id){
        $userId = rand(1000, 9999);
        $token = "Bearer ".md5(rand(10, 90).time()."AIDS");

        $variables = array(
            "id" => $id,
            "token" => $token,
            "userId" => $userId
        );

        $query = "INSERT INTO tokens(".implode(', ', array_keys($variables)).") VALUES (:".implode(', :', array_keys($variables)).")";

        try{
            $check = $this->db->prepare($query);
            $check->execute($variables);

            Base::returnSuccess("Token saved with sucess");
        }catch(PDOException $Exception){
            Base::returnError("ERROR RUNTIME");
        }
    }
}


$user = new Account();

if(isset($_GET['function'])){
    switch($_GET['function']){
        case "createUserData":
            $user->createUserData($_GET['id']);
        break;
    }
}