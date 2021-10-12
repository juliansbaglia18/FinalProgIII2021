<?php

namespace App\Controllers;

use Clases\Token;
use App\Models\Usuario;
use Mpdf\Utils\Arrays;
use stdClass;

class UsuarioController
{
    public function add($request, $response, $args)
    {
        $usuario = new Usuario;
        $std = new stdClass();
        $lista = new Usuario();

        $array[] = 0;
        $lista = Usuario::get();
        foreach ($lista as $key) {
            array_push($array, $key['id']);
        }
        $idMax = max($array) + 1;
        $archivo = $request->getUploadedFiles();
        $foto = $archivo["foto"]->getClientFileName();
        $extension = array_reverse(explode(".", $foto));

        $parametro = (array)$request->getParsedBody();

        $usuario->correo = $parametro['correo'];
        $usuario->clave = $parametro['clave'];
        $usuario->nombre = $parametro['nombre'];
        $usuario->apellido = $parametro['apellido'];
        $usuario->perfil = $parametro['perfil'];
        $usuario->foto = $parametro['correo'] . "_" . $idMax . "." . $extension[0];

        if ($usuario->save()) {
            $std->exito = true;
            $std->mensaje = "Guardado con exito.";
            $std->status = 200;
            $archivo['foto']->moveTo("../Fotos/" . $usuario->foto);
        } else {
            $std->exito = false;
            $std->mensaje = "Error al guardar.";
            $std->status = 418;
        }
        $response->getBody()->write(json_encode($std));
        return $response;
    }

    public function getAll($request, $response, $args)
    {
        $std = new stdClass();
        $lista = Usuario::get();

        if ($lista) {
            $std->exito = true;
            $std->mensaje = "Listado correcto.";
            $std->status = 200;
            $std->tabla = $lista;
        } else {
            $std->exito = false;
            $std->mensaje = "Error al listar.";
            $std->tabla = null;
            $std->status = 418;
        }

        $response->getBody()->write(json_encode($std));
        return $response;
    }

    public function login($request, $response, $args)
    {
        $std = new stdClass();
        $user = new Usuario();
        $list = Usuario::get();
        $flag = false;

        if (!$list) {
            $std->exito = false;
            $std->mensaje = "Error al encontrar lista.";
            $std->status = 418;

            $response->getBody()->write(json_encode($std));

            return $response;
        }

        $parametro = (array)$request->getParsedBody();

        $user->correo = $parametro['correo'];
        $user->clave = $parametro['clave'];

        if (isset($list) > 0) {
            foreach ($list as $a) {
                if ($a->correo == $user->correo && $a->clave == $user->clave) {
                    $std->exito = true;
                    $std->status = 200;
                    $std->mensaje = "USUARIO " . $a->nombre . " " . $a->apellido;
                    $std->jwt = Token::crearToken($a, $a->clave);
                    $flag = true;
                }
            }
        }

        if ($flag == false) {
            $std->exito = false;
            $std->jwt = null;
            $std->status = 403;
            $std->mensaje = "Usuario y/o contraseña erroneos.";
        }
        $response->getBody()->write(json_encode($std));
        return $response;
    }

    public function checkJWT($request, $response, $args)
    {
        $std = new stdClass();
        $user = new Usuario();
        $flag = false;

        if (Token::autenticarToken('1234', "", "")) {
            $user = Token::obtenerToken('1234');
            $std->exito = true;
            $std->mensaje = "Token correcto.";
            $std->user = $user;
            $std->status = 200;
        } else {
            $std->exito = false;
            $std->mensaje = "Token incorrecto.";
            $std->status = 403;
        }

        $response->getBody()->write(json_encode($std));
        return $response;
    }
}
