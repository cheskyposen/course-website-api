<?php
/*
 * Base Controller
 * Loads the models and views
 */
class Controller {
    // Load model
    public function model($section, $model){
        // Require model file
        require_once '../app/models/'. $section . '/' . $model . '.php';
        // Instantiate model
        return new $model();
    }
    // @param token from http request header ($GLOBALS['headers'])
    // @param ip from address requested from ($_SERVER['REMOTE_ADDR'])
    public function verifyToken($token, $ip){
        $db = new Database;

        $db->query('SELECT * FROM auth WHERE token = :token AND expiry > now()');
        $db->bind(':token', $token);
        // check database if token exists and not expired
        if ($res = $db->single()){
            json_encode($res);
            Controller::updateTokenExpiry($token);
            // checks if token matches to ip address
            // returns student or teachers id if verified else returns false
            if($res->token === $token && $res->ip === $ip){
                Controller::cleanTokens();
                return ($res->teacher_id < 1000) ? $res->teacher_id : $res->student_id;
            }else{
                return false;
            }
        } else {
            return false;
        }
    }
    // cleans expired tokens
    private static function cleanTokens(){
        $db = new Database;
        $db->query('DELETE FROM auth WHERE  expiry < now()');
        $db->execute();
        unset($db);
    }

    // @param token from http request header ($GLOBALS['headers'])
    // @param ip from address requested from ($_SERVER['REMOTE_ADDR'])
    public static function verifyTokenUserType($token, $ip){
        $db = new Database;

        $db->query('SELECT * FROM auth WHERE token = :token AND expiry > now()');
        $db->bind(':token', $token);
        // check database if token exists and not expired
        if ($res = $db->single()){
            // checks if token matches to ip address
            // returns student or teachers id if verified else returns false
            if($res->token === $token && $res->ip === $ip){
                return ($res->student_id < 1000) ? 'student' : 'teacher';
            }else{
                return false;
            }
        } else {
            return false;
        }
    }

    private static function updateTokenExpiry($token){
        $db = new Database;
        $db->query("UPDATE auth SET expiry = NOW() + INTERVAL '30 minutes' WHERE token = :token");
        $db->bind(':token', $token);
        $db->execute();
        unset($db);
    }
}