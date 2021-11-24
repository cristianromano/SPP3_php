<?php

require_once './models/Alquiler.php';
require_once './models/Cabana.php';
require_once './models/Usuario.php';
require_once './models/fpdf.php';
require_once './interfaces/IApiUsable.php';

use Psr\Http\Message\UploadedFileInterface;

//  require('fpdf.php');

class AlquilerController extends Alquiler implements IApiUsable
{

    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre_usuario = $parametros['nombre_usuario'];
        $archivos = $request->getUploadedFiles();
        $nombre_cabana = $parametros['nombre_cabana'];
        $estilo_cabana = $parametros['estilo_cabana'];
        $cantidad_dias = $parametros['cantidad_dias'];
        $extension = explode(".", $archivos['foto']->getClientFilename());
        $fecha = date("Y-m-d");

        // $ObjCripto = Cripto::obtenerCriptoPorNombre($nombre_cripto);
        // $ObjUsuario = Usuario::obtenerUsuarioPorNombre($nombre_usuario);

        // var_dump($ObjUsuario);

        if ($archivos['foto']->getError() == UPLOAD_ERR_OK) {
            $destino = "./fotosCabanas/";
            $extension = explode(".", $archivos['foto']->getClientFilename());
            $destino .= $nombre_cabana . '/';
            if (!file_exists($destino)) {
                mkdir($destino, 0777, true);
            }
            $cliente = explode('@', $nombre_usuario);
            $nombreFoto = $extension[0] . '-' . $cliente[0] . '-' . $fecha . '.' . $extension[1];
            // $archivos['foto']->getClientFilename()
            $archivos['foto']->moveTo($destino . $nombreFoto);
            //  $foto = $archivos['foto']->getClientFilename();
            // var_dump($nombreFoto);
        }

        $cripto = new Alquiler();
        $cripto->nombre_usuario = $nombre_usuario;
        $cripto->nombre_cabana = $nombre_cabana;
        $cripto->estilo_cabana = $estilo_cabana;
        $cripto->cantidad_dias = $cantidad_dias;
        $cripto->foto = $nombreFoto;
        $cripto->crearAlquiler();

        $payload = json_encode(array("mensaje" => "Venta creado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // // Buscamos usuario por nombre
        // $nacionalidad = $args['nacionalidad'];
        // $nac = Cripto::obtenerCriptoNacionalidad($nacionalidad);
        // $payload = json_encode($nac);

        // $response->getBody()->write($payload);
        // return $response
        //     ->withHeader('Content-Type', 'application/json');
    }

    public function CabanaPorEstilo($request, $response, $args)
    {
        $estilo = $args['estilo'];
        // $nac = Cripto::obtenerCriptoNacionalidad($nacionalidadID);

        $estiloCab = Alquiler::obtenerCabanasEstilo($estilo);

        $payload = json_encode($estiloCab);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function traerCabanaNombre($request, $response, $args)
    {
        $nombre = $args['nombre'];
        // $nac = Cripto::obtenerCriptoNacionalidad($nacionalidadID);

        $nombreCriptos = Alquiler::obtenerPorNombre($nombre);

        // $jsonArr = json_encode($nombreCriptos);

        $payload = json_encode($nombreCriptos);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }


    public function TraerTodos($request, $response, $args)
    {
        // $lista = Cripto::obtenerTodos();
        // $payload = json_encode(array("listaUsuario" => $lista));

        // $response->getBody()->write($payload);
        // return $response
        //     ->withHeader('Content-Type', 'application/json');
    }

    public function descargaPDF($request, $response, $args)
    {

        $lista = Alquiler::obtenerTodos();

        // $cadena = "<ul>";
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 21);
        $pdf->Cell(150,10,'Alquiler de Cabanias [ ADMIN ] ', 1, 2 , 'C');
        $pdf->Ln();
        $pdf->SetFont('courier', 'B', 8);
        foreach ($lista as $venta) {
            $cadena =  'Nombre Cabania:' . $venta->nombre_cabana . ' Nacionalidad:' . $venta->nombre_usuario . ' Cantidad dias:' . $venta->cantidad_dias .
                ' Estilo:' . $venta->estilo_cabana . ' Fecha:' . $venta->fecha;
            $pdf->Cell(12, 5, $cadena,0,1,'L');
            $pdf->Ln();
        }
        // $cadena .= "</ul>";

        $pdf->Output('F', './pdf/pdfAlquileres.pdf', false);


        $payload = json_encode(array("PDF generado"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        // $parametros = $request->getParsedBody();

        // $nombre = $parametros['nombre'];
        // Usuario::modificarUsuario($nombre);

        // $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        // $response->getBody()->write($payload);
        // return $response
        //   ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        // $parametros = $request->getParsedBody();

        // $usuarioId = $parametros['usuarioId'];
        // Usuario::borrarUsuario($usuarioId);

        // $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        // $response->getBody()->write($payload);
        // return $response
        //   ->withHeader('Content-Type', 'application/json');
    }
}
