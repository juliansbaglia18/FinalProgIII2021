<?php

namespace App\Controllers;

use Clases\Token;
use App\Models\Hortaliza;
use Mpdf\Mpdf;

use Mpdf\Utils\Arrays;
use stdClass;

class HortalizaController
{
    public function add($request, $response, $args)
    {
        $hortaliza = new Hortaliza;
        $std = new stdClass();

        $archivo = $request->getUploadedFiles();
        $foto = $archivo["foto"]->getClientFileName();
        $extension = array_reverse(explode(".", $foto));

        $parametro = (array)$request->getParsedBody();

        $hortaliza->precio = $parametro['precio'];
        $hortaliza->nombre = $parametro['nombre'];
        $hortaliza->foto = $parametro['nombre'] . "." . $extension[0];
        $hortaliza->tipo = $parametro['tipo'];

        if ($hortaliza->save()) {
            $std->exito = true;
            $std->mensaje = "Guardado con exito.";
            $archivo['foto']->moveTo("../Fotos/" . $hortaliza->foto);
        } else {
            $std->exito = false;
            $std->mensaje = "Error al guardar.";
        }
        $response->getBody()->write(json_encode($std));
        return $response;
    }

    public function getAll($request, $response, $args)
    {

        $std = new stdClass();
        $rta = Hortaliza::get();
        if ($rta) {
            $std->exito = true;
            $std->mensaje = "Listado correcto.";
            $std->tabla = $rta;
        } else {
            $std->exito = false;
            $std->mensaje = "Error al listar.";
            $std->tabla = null;
        }

        $response->getBody()->write(json_encode($std));

        return $response;
    }

    public function getTipo($request, $response, $args)
    {
        $rta = Hortaliza::where('tipo', $args['tipo'])->get();

        $response->getBody()->write(json_encode($rta));

        return $response;
    }

    public function getId($request, $response, $args)
    {
        $rta = Hortaliza::where('id', $args['id'])->get();

        $response->getBody()->write(json_encode($rta));

        return $response;
    }

    public function delete($request, $response, $args)
    {
        $std = new stdClass();
        $id = $args['id'];
        $hortaliza = Hortaliza::find($id);

        if ($hortaliza->delete()) {
            $std->exito = true;
            $std->mensaje = 'Borrado de hortaliza con id ' . $id . ' exitoso.';
        }

        $response->getBody()->write(json_encode($std));
        return $response;
    }

    public function update($request, $response, $args)
    {
        $std = new stdClass();
        $hortaliza = new Hortaliza;

        $parametro = (array)$request->getParsedBody();

        $id = $args['id'];

        $hortaliza = Hortaliza::find($id);

        if ($parametro['precio'] != "")
            $hortaliza->precio = $parametro['precio'];

        if ($parametro['nombre'] != "")
            $hortaliza->nombre = $parametro['nombre'];

        // if(isset($parametro['foto']))
        // $hortaliza->foto = $parametro['nombre'] . "." . $extension[0];

        if ($parametro['tipo'] != "")
            $hortaliza->tipo = $parametro['tipo'];

        if ($hortaliza->save()) {
            $std->exito = true;
            $std->mensaje = "Actualizado con exito.";
            $std->actualizacion = $hortaliza;
            $std->status = 200;
        } else {
            $std->exito = false;
            $std->mensaje = "Error al actualizar.";
            $std->status = 418;
        }

        $response->getBody()->write(json_encode($std));
        return $response;
    }

    public function pdf($request, $response, $args)
    {
        $id = $args['id'];

        $std = new stdClass();
        $mpdf = new Mpdf();
        $tabla = "<table border='1' align='center'>";
        $mpdf->SetHeader('Sbaglia Julián - Página: {PAGENO}');
        $mpdf->setFooter(date("F j, Y"));

        $listaHortalizas = Hortaliza::get();

        if (!$listaHortalizas) {
            $std->exito = false;
            $std->mensaje = "Error al obtener lista de HORTALIZAS";
            $std->status = 418;

            $response->getBody()->write(json_encode($std));
            return $response;
        }
        $mpdf->WriteHTML('<h1>Lista de Usuarios</h1><br>');
        $tabla .= '<tr><th>ID</th><th>PRECIO</th><th>NOMBRE</th><th>FOTO</th><th>TIPO</th></tr>';
        foreach ($listaHortalizas as $item) {
            if ($item->id == $id) {
                $tabla .= '<tr>
                <td>' . $item->id . '</td><td>' . $item->precio . '</td><td>' . $item->nombre . '</td><td>' . $item->foto . '</td><td>' . $item->tipo . '</td>
                </tr>';
            }
        }
        $tabla .= '</table>';
        $mpdf->WriteHTML($tabla);

        return $mpdf->Output();
    }
}
