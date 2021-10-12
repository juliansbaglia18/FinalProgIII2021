<?php

namespace App\Controllers;

use Clases\Token;
use App\Models\Empleado;
use App\Models\Venta;

use Mpdf\Utils\Arrays;
use stdClass;

class EmpleadoController
{
    public function add($request, $response, $args)
    {
        $Empleado = new Empleado;
        $std = new stdClass();
        $lista = new Empleado();

        $array[] = 0;
        $lista = Empleado::get();
        foreach ($lista as $key) {
            array_push($array, $key['id']);
        }
        $idMax = max($array) + 1;
        $archivo = $request->getUploadedFiles();
        $foto = $archivo["foto"]->getClientFileName();
        $extension = array_reverse(explode(".", $foto));

        $parametro = (array)$request->getParsedBody();

        $Empleado->mail = $parametro['mail'];
        $Empleado->clave = $parametro['clave'];
        $Empleado->nombre = $parametro['nombre'];
        $Empleado->apellido = $parametro['apellido'];
        $Empleado->perfil = $parametro['perfil'];
        $Empleado->foto = $parametro['mail'] . "_" . $idMax . "." . $extension[0];

        if ($Empleado->save()) {
            $std->exito = true;
            $std->mensaje = "Guardado con exito.";
            $std->status = 200;
            $archivo['foto']->moveTo("../Fotos/" . $Empleado->foto);
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
        $lista = Empleado::get();

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
        $user = new Empleado();
        $list = Empleado::get();
        $flag = false;

        if (!$list) {
            $std->respuesta = 'ERROR';
            $std->mensaje = "Error al encontrar lista.";

            $response->getBody()->write(json_encode($std));

            return $response;
        }

        $parametro = (array)$request->getParsedBody();

        $user->mail = $parametro['mail'];
        $user->sexo = $parametro['sexo'];
        $user->clave = $parametro['clave'];

        if (isset($list) > 0) {
            foreach ($list as $a) {
                if ($a->mail == $user->mail && $a->clave == $user->clave && $a->sexo == $user->sexo) {
                    $std->respuesta = 'OK';
                    $std->mensaje = $a;
                    $flag = true;
                }
            }
        }

        if ($flag == false) {
            $std->respuesta = 'ERROR';
            $std->mensaje = "Empleado y/o contraseña erroneos.";
        }
        $response->getBody()->write(json_encode($std));
        return $response;
    }

    public function checkJWT($request, $response, $args)
    {
        $std = new stdClass();
        $user = new Empleado();
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

    public function addVenta($request, $response, $args)
    {
        $venta = new Venta;
        $listaEmpleados = new Empleado;
        $std = new stdClass();
        $rta = '';


        $archivo = $request->getUploadedFiles();
        $foto = $archivo["foto"]->getClientFileName();
        $extension = array_reverse(explode(".", $foto));

        $parametro = (array)$request->getParsedBody();

        $venta->idHortaliza = $parametro['idHortaliza'];
        $venta->idEmpleado = $parametro['idEmpleado'];
        $venta->fecha = date("y.m.d");;
        $venta->cantidad = $parametro['cantidad'];


        $user = Empleado::find($venta->idEmpleado);


        // foreach ($listaEmpleados as $key) {
        //     if ($key->id == $venta->idEmpleado) {
        //         $rta = $key->mail;
        //     }
        //     # code...
        // }
        $venta->foto = $venta->idHortaliza . $user->mail . "." . $extension[0];

        if ($venta->save()) {
            $std->exito = true;
            $std->mensaje = "Guardado con exito.";
            $archivo['foto']->moveTo("../FotosVentas/" . $venta->foto);
        } else {
            $std->exito = false;
            $std->mensaje = "Error al guardar.";
        }
        $response->getBody()->write(json_encode($std));
        return $response;
    }
}
