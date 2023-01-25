<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once join(DIRECTORY_SEPARATOR, array(__DIR__, "securite", "vendor","autoload.php"));

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Securite{
    var $key;
    var $encodage;
    public function __construct()
    {
        $this->key = "DNA_WEBHOSTING";
        $this->encodage = "HS256";
    }

    public function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    public function encoder($payload){
        return JWT::encode($payload, $this->key, $this->encodage);
    }
    public function decoder($token){
        return JWT::decode($token,new Key($this->key, $this->encodage));
    }
}