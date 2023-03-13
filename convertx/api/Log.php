<?PHP 

class Log{

    public static function error($method, $query, $variables){
        $database = new Database();
        $db = $database->connect();

        $variables = array(
            "method" => $method,
            "query" => $query,
            "variables" => serialize($variables)
        );

        $query = "INSERT INTO logs (method, query, variables, tipo) VALUES (:method, :query, :variables, 'ERRO')";

        try{
            $insert = $db->prepare($query);
            $insert->execute($variables);
        }catch(PDOException $Exception){
            print_r($Exception);
            die();
        }
    }

    public static function access($method, $query, $variables, $user){
        $database = new Database();
        $db = $database->connect();

        $variables = array(
            "method" => $method,
            "query" => $query,
            "variables" => serialize($variables),
            "user" => $user
        );

        $query = "INSERT INTO logs (method, query, variables, user, tipo) VALUES (:method, :query, :variables, :user, 'SUCCESS')";

        try{
            $insert = $db->prepare($query);
            $insert->execute($variables);
        }catch(PDOException $Exception){
            print_r($Exception);
            die();
        }
    }
}