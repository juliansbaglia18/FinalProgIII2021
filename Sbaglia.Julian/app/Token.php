<?php

namespace Clases;

use \Firebase\JWT\JWT;



class Token
{
    /**
     * devuelve string con el token.
     */
    static function crearToken($payload, $key)
    {

        $retorno = "";
        if (!empty($key) && !empty($payload)) {
            $retorno = JWT::encode($payload, $key);
        } else {
            $retorno = "ERROR!!";
        }

        return $retorno;
    }

    static function obtenerToken($key)
    {
        try {
            //Modificar si no se entra al token como siempre
            $token = $_SERVER['HTTP_TOKEN'];

            $decoded = JWT::decode($token, $key, array('HS256'));

            return $decoded;
        } catch (\Throwable $th) {
            return 'error';
        }
    }

    /***
     * 
     */
    static function autenticarToken($key, $ok, $fail)
    {
        if (Token::obtenerToken($key) == 'error') {
            echo $fail;
            return false;
        } else {
            echo $ok;
            return true;
        }
    }
}
